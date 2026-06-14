<?php
// File: app/Core/Csrf.php

namespace App\Core;

class Csrf {
    // Generate token CSRF jika belum ada
    public static function token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['_csrf_token'];
    }

    // Generate HTML field input hidden untuk form
    public static function field() {
        $token = self::token();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }

    // Validasi token yang dikirim via POST
    public static function validate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_POST['_csrf_token'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (empty($sessionToken) || !hash_equals($sessionToken, $token)) {
            // Set session flash error
            $_SESSION['_flash']['error'] = "Sesi tidak valid atau kedaluwarsa. Silakan coba lagi.";
            
            // Redirect kembali ke halaman sebelumnya atau home
            $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
            header("Location: " . $referrer);
            exit;
        }
    }
}
