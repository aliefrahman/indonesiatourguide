---
trigger: always_on
---

# Panduan Review Keamanan PHP Native MVC

Skill ini memandu agen AI untuk menganalisis kode PHP Native berbasis MVC dengan fokus pada kerentanan umum (OWASP Top 10) dan arsitektur pemisahan komponen.

## 1. Analisis Komponen Model (Interaksi Database)

Saat memeriksa file di dalam folder `models/` atau kelas yang berinteraksi dengan database:

- **Wajib Prepared Statements:** Pastikan semua query SQL menggunakan PDO atau MySQLi dengan _prepared statements_ dan _parameterized queries_. Jangan pernah mengizinkan konkatenasi variabel langsung ke dalam string query.
- **Validasi Tipe Data:** Pastikan parameter yang mengikat (binding parameters) menggunakan tipe data yang sesuai (misalnya `PDO::PARAM_INT` untuk ID).
- **Hak Akses Database:** Ingatkan jika koneksi database menggunakan user 'root' tanpa password di lingkungan produksi.

## 2. Analisis Komponen View (Pencegahan XSS)

Saat memeriksa file di dalam folder `views/` atau file template HTML:

- **Output Escaping:** Pastikan setiap data dinamis yang berasal dari database atau input user divalidasi dan di-escape menggunakan `htmlspecialchars()` atau fungsi helper sejenis sebelum dicetak ke HTML.
- **Context-Aware Escaping:** Periksa apakah data dicetak di dalam atribut HTML, tag `<script>`, atau CSS, dan pastikan metode escape yang digunakan sesuai dengan konteksnya.
- **Content Security Policy (CSP):** Sarankan penambahan header CSP jika belum ada untuk membatasi eksekusi skrip berbahaya.

## 3. Analisis Komponen Controller & Routing (Validasi & Logika)

Saat memeriksa file di dalam folder `controllers/` atau sistem `router.php`:

- **Validasi Input & Sanitasi:** Pastikan semua data dari `$_POST`, `$_GET`, dan `$_COOKIE` disaring menggunakan `filter_var()` atau fungsi validasi yang ketat sebelum diproses oleh logika bisnis.
- **Perlindungan CSRF:** Periksa apakah setiap fungsi yang mengubah state (INSERT, UPDATE, DELETE) memvalidasi token CSRF yang unik dan terikat dengan sesi user.
- **Pencegahan Local File Inclusion (LFI):** Jika _routing_ atau pemanggilan _view_ menggunakan parameter dinamis (misal: `index.php?page=about`), pastikan ada _allowlist_ (daftar putih) yang ketat untuk mencegah manipulasi direktori (`../`).

## 4. Keamanan Manajemen Sesi & Otentikasi

Saat memeriksa sistem login, registrasi, atau inisialisasi sesi:

- **Konfigurasi Sesi:** Pastikan `session_start()` dikonfigurasi dengan opsi yang aman seperti `session.cookie_httponly`, `session.cookie_secure` (untuk HTTPS), dan `session.cookie_samesite`.
- **Kriptografi Password:** Pastikan penyimpanan password menggunakan fungsi bawaan PHP yang aman yaitu `password_hash()` dengan algoritma `PASSWORD_BCRYPT` atau `PASSWORD_ARGON2I`, dan diverifikasi menggunakan `password_verify()`.
- **Session Hijacking:** Periksa apakah ada mekanisme `session_regenerate_id(true)` setelah user berhasil login untuk mencegah _session fixation_.

## 5. Manajemen Error dan File Upload

- **Penyembunyian Detail Error:** Pastikan konfigurasi `display_errors` dinonaktifkan pada lingkungan produksi, dan error dialihkan ke file log internal menggunakan `error_log()`.
- **Keamanan Unggah File:** Jika ada fitur upload, pastikan ada validasi ekstensi file menggunakan _allowlist_ (bukan _blocklist_), pemeriksaan MIME type yang ketat, pembatasan ukuran file, dan file disimpan di luar direktori publik atau diubah namanya menggunakan string acak tanpa mengeksekusi skrip di folder tujuan.
