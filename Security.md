# 🛡️ Security.md - Panduan Implementasi Keamanan 6 Lapis (PHP Native)

Dokumen ini berisi panduan teknis dan cuplikan kode (_boilerplate_) untuk mengimplementasikan 6 Lapis Keamanan pada platform **Indonesian Tour Guide** menggunakan **PHP 8.3+ Native MVC**, tanpa bergantung pada _framework_ eksternal.

---

## Lapis 1: SQL Injection Prevention (PDO Singleton)

Semua interaksi database **wajib** menggunakan pola _Prepared Statements_. Berikut adalah implementasi kelas `Database` di `app/Core/Database.php`.

```php
<?php
// File: app/Core/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Kredensial idealnya dimuat dari file config/database.php
        $host = '127.0.0.1';
        $db   = 'tour_guide_db';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lempar exception jika error
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Default kembalian berupa array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Matikan emulasi prep statement untuk keamanan ekstra
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Catat ke error log, jangan tampilkan ke user
            error_log($e->getMessage());
            die("Terjadi kesalahan koneksi database.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Fungsi helper untuk eksekusi query aman
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

---

## Lapis 2 & 3: Session Storage Mandiri & CSRF Protection

Mengamankan sesi dari eksploitasi shared hosting (dimana folder /tmp bisa diakses pengguna cPanel lain) dan mencegah serangan Cross-Site Request Forgery (CSRF).

```php

<?php
// File: app/Core/Security.php

class Security {

    // Dipanggil pertama kali di public/index.php (Bootstrapping)
    public static function initSession() {
        // Pindahkan penyimpanan sesi ke folder internal yang tidak bisa diakses publik/user lain
        $sessionPath = __DIR__ . '/../../storage/sessions';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0700, true);
        }

        session_save_path($sessionPath);

        // Pengaturan keamanan cookie sesi
        ini_set('session.cookie_httponly', 1); // Cegah akses XSS via JavaScript
        ini_set('session.cookie_secure', 1);   // Wajibkan HTTPS (ubah ke 0 jika masih dev lokal HTTP)
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_samesite', 'Strict');

        session_start();
    }

    // Generate token CSRF untuk disisipkan ke dalam Form HTML
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Verifikasi token CSRF saat menerima POST request
    public static function verifyCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die("Akses ditolak: Validasi CSRF gagal.");
        }
    }
}

```

---

## Lapis 4: RBAC (Role-Based Access Control) & Zero-Trust

Memastikan pengguna hanya bisa mengakses halaman sesuai perannya (admin, guide, atau traveler). Pendekatan Zero-Trust berarti kita tidak pernah berasumsi user berhak akses sebelum diverifikasi di setiap request.

```php

<?php
// File: app/Middleware/RoleCheck.php

class RoleCheck {

    // Mengecek apakah user sudah login dan memiliki peran yang diizinkan
    public static function requireRole($allowedRoles = []) {
        // Pastikan user sudah login (Cek Session)
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header("Location: /login");
            exit;
        }

        $userRole = $_SESSION['user_role'];

        // Jika peran user tidak ada di dalam array yang diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            // Lempar ke halaman 403 Forbidden
            http_response_code(403);
            require_once __DIR__ . '/../../views/errors/403.php';
            exit;
        }
    }
}

/* CARA PENGGUNAAN DI CONTROLLER (Contoh: DashboardController.php) */
// RoleCheck::requireRole(['admin']); // Hanya admin yang bisa akses method ini
// RoleCheck::requireRole(['admin', 'guide']); // Admin dan Guide bisa akses

```

---

## Lapis 5: Audit Log (Pencatatan Aktivitas Keamanan)

Mencatat tindakan krusial (login, hapus data, ubah pesanan) ke dalam tabel audit_logs untuk keperluan investigasi jika terjadi kebocoran atau kesalahan sistem.

```php

<?php
// File: app/Core/Logger.php

require_once 'Database.php';

class Logger {
    public static function logAction($actionType, $description = null) {
        $db = Database::getInstance();

        $userId = $_SESSION['user_id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        $sql = "INSERT INTO audit_logs (user_id, action_type, ip_address, user_agent, description)
                VALUES (?, ?, ?, ?, ?)";

        $db->query($sql, [$userId, $actionType, $ipAddress, $userAgent, $description]);
    }
}

/* CARA PENGGUNAAN */
// Setelah proses login berhasil:
// Logger::logAction('login_success', 'User berhasil login dengan email: ' . $email);
//
// Jika terjadi percobaan login gagal beruntun:
// Logger::logAction('login_failed', 'Gagal login dari email: ' . $email);
```

---

## Lapis 6: Native Rate Limiting (Pencegahan Brute-Force)

Contoh sederhana membatasi percobaan login (maksimal 5 kali gagal dalam 15 menit) menggunakan Session. Untuk tingkat enterprise, ini bisa dipindahkan ke Database atau Redis.

```php

<?php
// File: app/Core/RateLimiter.php

class RateLimiter {
    public static function checkLoginAttempt($ip) {
        $maxAttempts = 5;
        $lockoutTime = 15 * 60; // 15 menit

        if (isset($_SESSION['login_attempts'])) {
            $attempts = $_SESSION['login_attempts']['count'];
            $lastAttemptTime = $_SESSION['login_attempts']['time'];

            // Jika masih dalam masa lockout
            if ($attempts >= $maxAttempts && (time() - $lastAttemptTime) < $lockoutTime) {
                Logger::logAction('rate_limit_triggered', "IP $ip terkunci karena brute-force.");
                die("Terlalu banyak percobaan login. Silakan coba lagi setelah 15 menit.");
            }

            // Reset counter jika waktu lockout sudah lewat
            if ((time() - $lastAttemptTime) > $lockoutTime) {
                unset($_SESSION['login_attempts']);
            }
        }
    }

    public static function recordFailedAttempt() {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [
                'count' => 1,
                'time' => time()
            ];
        } else {
            $_SESSION['login_attempts']['count']++;
            $_SESSION['login_attempts']['time'] = time();
        }
    }
}
```

---
