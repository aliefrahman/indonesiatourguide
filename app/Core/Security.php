<?php
// File: app/Core/Security.php

namespace App\Core;

class Security {
    public static function initSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // Pindahkan session save path ke folder internal
            $sessionPath = __DIR__ . '/../../storage/sessions';
            if (!is_dir($sessionPath)) {
                mkdir($sessionPath, 0700, true);
            }

            session_save_path($sessionPath);

            // Keamanan cookie sesi
            ini_set('session.cookie_httponly', 1); // Blokir akses script JS (Cegah XSS)
            
            // Deteksi apakah sedang menggunakan HTTPS
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                        ($_SERVER['SERVER_PORT'] ?? '') == 443;
                        
            ini_set('session.cookie_secure', $isSecure ? 1 : 0); // HTTPS check
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Strict');

            session_start();
        }
    }

    // Pembersihan input secara global (hanya menghapus karakter NUL dan spasi ekstra)
    // PERHATIAN: Output escaping dipindahkan ke helper e() di level view.
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            // Hapus null byte
            $data = str_replace(chr(0), '', trim($data));
        }
        return $data;
    }
}
