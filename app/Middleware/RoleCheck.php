<?php
// File: app/Middleware/RoleCheck.php

namespace App\Middleware;

class RoleCheck {
    // Mengecek apakah user sudah login dan memiliki peran yang diizinkan
    public static function requireRole($allowedRoles = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Pastikan user sudah login
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            // Simpan url asal jika traveler ingin kembali ke halaman setelah login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
            $_SESSION['_flash']['error'] = "Silakan login terlebih dahulu untuk mengakses halaman tersebut.";
            header("Location: /login");
            exit;
        }

        $userRole = $_SESSION['user_role'];

        // Jika peran user tidak ada di dalam array yang diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            http_response_code(403);
            $errorView = __DIR__ . '/../../resources/views/errors/403.php';
            if (file_exists($errorView)) {
                require_once $errorView;
            } else {
                echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
                echo "<h1>403 Akses Ditolak</h1>";
                echo "<p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>";
                echo "<p><a href='/'>Kembali ke Halaman Utama</a></p>";
                echo "</div>";
            }
            exit;
        }
    }
}
