<?php
// File: app/Core/RateLimiter.php

namespace App\Core;

class RateLimiter {
    public static function checkLoginAttempt($ip) {
        $maxAttempts = 5;
        $lockoutTime = 15 * 60; // 15 menit

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['login_attempts'])) {
            $attempts = $_SESSION['login_attempts']['count'];
            $lastAttemptTime = $_SESSION['login_attempts']['time'];

            // Jika masih dalam masa lockout
            if ($attempts >= $maxAttempts && (time() - $lastAttemptTime) < $lockoutTime) {
                AuditLogger::logAction('rate_limit_triggered', "IP $ip terkunci karena brute-force.");
                
                http_response_code(429);
                die("Terlalu banyak percobaan login. Silakan coba lagi setelah 15 menit.");
            }

            // Reset counter jika waktu lockout sudah lewat
            if ((time() - $lastAttemptTime) > $lockoutTime) {
                unset($_SESSION['login_attempts']);
            }
        }
    }

    public static function recordFailedAttempt() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

    public static function resetAttempts() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['login_attempts']);
    }
}
