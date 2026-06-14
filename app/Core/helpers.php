<?php
// File: app/Core/helpers.php

use App\Core\Csrf;

// Helper memuat view file dari resources/views
// Polyfill untuk fungsi string PHP 8.0 (kompatibilitas shared hosting versi lama)
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        $needle = (string)$needle;
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }
}
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return (string)$needle === '' || strpos($haystack, $needle) !== false;
}
}

// Helper pencegahan XSS (Context-Aware Output Escaping)
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('view')) {
    function view($name, $data = []) {
        // Mengubah dot notation menjadi path pemisah (misal: tour.detail -> tour/detail)
        $path = str_replace('.', '/', $name);
        $file = __DIR__ . '/../../resources/views/' . $path . '.php';

        if (file_exists($file)) {
            // Ekstrak data menjadi variabel lokal di dalam view
            extract($data);
            require $file;
        } else {
            http_response_code(500);
            die("View file tidak ditemukan: " . htmlspecialchars($name));
        }
    }
}

// Helper pengalihan halaman
if (!function_exists('redirect')) {
    function redirect($url) {
        // Mencegah Open Redirect (pastikan hanya redirect ke path internal)
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, '//') === 0) {
            $url = '/';
        }
        header("Location: " . $url);
        exit;
    }
}

// Helper CSRF input field
if (!function_exists('csrf_field')) {
    function csrf_field() {
        return Csrf::field();
    }
}

// Helper CSRF token
if (!function_exists('csrf_token')) {
    function csrf_token() {
        return Csrf::token();
    }
}

// Helper mengecek data login user aktif
if (!function_exists('auth')) {
    function auth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            return (object)[
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'traveler'
            ];
        }
        return null;
    }
}

// Helper untuk flash session messages
if (!function_exists('flash')) {
    function flash($key, $message = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($message !== null) {
            // Set message
            $_SESSION['_flash'][$key] = $message;
        } else {
            // Get and remove message
            $msg = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }
    }
}

// Helper untuk mempertahankan input lama pada form jika terjadi kesalahan submit
if (!function_exists('old')) {
    function old($key, $default = '') {
        $val = $_POST[$key] ?? $_GET[$key] ?? $default;
        return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
    }
}

// Helper untuk terjemahan dwibahasa (Indonesian / English)
if (!function_exists('translate')) {
    function translate($idText, $enText) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $lang = $_SESSION['lang'] ?? 'id';
        return $lang === 'en' ? $enText : $idText;
    }
}

// Helper memformat nominal mata uang Rupiah
if (!function_exists('format_rupiah')) {
    function format_rupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}

// Helper konversi ke Dollar (contoh estimasi konversi)
if (!function_exists('format_usd')) {
    function format_usd($number, $rate = 16000) {
        return '$' . number_format($number / $rate, 2);
    }
}

// Environment helper & loader
if (!function_exists('load_env')) {
    function load_env($filePath) {
        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Split by first equals sign
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $val = trim($parts[1]);

                // Strip surrounding quotes
                if (preg_match('/^"([^"]*)"$/', $val, $matches) || preg_match('/^\'([^\']*)\'$/', $val, $matches)) {
                    $val = $matches[1];
                }

                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $val;
                }
                if (!array_key_exists($key, $_SERVER)) {
                    $_SERVER[$key] = $val;
                }
                putenv("$key=$val");
            }
        }
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);
        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }
        
        // Handle boolean/null values in env string
        if ($value === 'true' || $value === true) {
            return true;
        }
        if ($value === 'false' || $value === false) {
            return false;
        }
        if ($value === 'null' || $value === null) {
            return null;
        }
        
        return $value;
    }
}

// Automatically load environment variables from project root .env file
load_env(__DIR__ . '/../../.env');
