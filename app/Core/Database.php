<?php
// File: app/Core/Database.php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $configPath = __DIR__ . '/../../config/database.php';
        if (!file_exists($configPath)) {
            die("File konfigurasi database tidak ditemukan.");
        }
        
        $config = require $configPath;
        $driver = $config['driver'] ?? 'mysql';

        try {
            if ($driver === 'sqlite') {
                $dbFile = $config['sqlite']['database'];
                $dbDir = dirname($dbFile);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0700, true);
                }
                
                $dsn = "sqlite:" . $dbFile;
                $this->pdo = new PDO($dsn);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                // Aktifkan foreign keys di SQLite
                $this->pdo->exec("PRAGMA foreign_keys = ON;");
            } else {
                $dbConf = $config['mysql'];
                $host = $dbConf['host'];
                $port = $dbConf['port'] ?? '3306';
                $db   = $dbConf['database'];
                $user = $dbConf['username'];
                $pass = $dbConf['password'];
                $charset = $dbConf['charset'] ?? 'utf8mb4';

                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                $this->pdo = new PDO($dsn, $user, $pass, $options);
                
                // Self-healing: Pastikan tabel audit_logs ada di MySQL (karena terlewat di schema.sql awal)
                $this->pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT UNSIGNED NULL,
                    action_type VARCHAR(100) NOT NULL,
                    ip_address VARCHAR(45) NOT NULL,
                    user_agent VARCHAR(255) NOT NULL,
                    description TEXT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                    INDEX idx_audit_user (user_id),
                    INDEX idx_audit_action (action_type)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            }
        } catch (PDOException $e) {
            // Tulis kesalahan ke error log bawaan
            error_log("Database Connection Error: " . $e->getMessage());
            
            // Berikan pesan kesalahan yang ramah
            if ($driver === 'mysql') {
                $errorMsg = "Gagal terhubung ke database MySQL.<br>" .
                            "Silakan jalankan layanan MariaDB/MySQL Anda (misal: <code>sudo systemctl start mariadb</code> atau <code>sudo service mysql start</code>) atau " .
                            "ubah pengaturan <code>'driver' => 'sqlite'</code> di <code>config/database.php</code> untuk pengujian lokal.<br><br><b>Pesan Asli:</b> " . htmlspecialchars($e->getMessage());
            } else {
                $errorMsg = "Gagal terhubung ke database SQLite: " . htmlspecialchars($e->getMessage());
            }
            
            http_response_code(500);
            echo "<div style='font-family: sans-serif; padding: 20px; border: 1px solid #f5c6cb; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin: 20px auto; max-width: 600px;'>";
            echo "<h4 style='margin-top: 0;'>Kesalahan Koneksi Database</h4>";
            echo "<p>" . $errorMsg . "</p>";
            echo "</div>";
            exit;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Helper untuk eksekusi query dengan prepared statement secara aman
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Mendapatkan ID terakhir yang dimasukkan
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
