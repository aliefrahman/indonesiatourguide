<?php
// File: app/Core/App.php

namespace App\Core;

class App {
    private $router;

    public function __construct() {
        // Register PSR-4-like autoloader
        spl_autoload_register([$this, 'autoload']);
        
        // Load global helpers
        require_once __DIR__ . '/helpers.php';
        
        // Inisialisasi sesi aman (Lapis 2 & 3 Keamanan)
        Security::initSession();

        // Cek mode maintenance (hanya memblokir non-admin pada halaman non-auth dan non-assets)
        if (env('MAINTENANCE_MODE', false)) {
            $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
            if (!$isAdmin) {
                $uri = $_SERVER['REQUEST_URI'] ?? '/';
                $path = parse_url($uri, PHP_URL_PATH);
                $path = $path === '/' ? '/' : rtrim($path, '/');

                $allowedRoutes = ['/login', '/logout', '/register', '/forgot', '/reset', '/verify', '/lang'];
                $isAllowed = false;
                foreach ($allowedRoutes as $route) {
                    if ($path === $route || str_starts_with($path, $route . '/')) {
                        $isAllowed = true;
                        break;
                    }
                }

                // Izinkan pemuatan asset static dan folder upload
                $isAsset = str_contains($path, '/assets/') || str_contains($path, '/storage/');

                if (!$isAllowed && !$isAsset) {
                    http_response_code(503);
                    $maintenanceView = dirname(__DIR__, 2) . '/resources/views/errors/maintenance.php';
                    if (file_exists($maintenanceView)) {
                        require_once $maintenanceView;
                    } else {
                        echo "<div style='font-family: sans-serif; text-align: center; padding: 100px 20px;'>";
                        echo "<h1>Under Maintenance</h1>";
                        echo "<p>Situs sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi.</p>";
                        echo "</div>";
                    }
                    exit;
                }
            }
        }

        // Bersihkan input POST/GET secara rekursif demi keamanan XSS
        $_POST = Security::sanitize($_POST);
        $_GET = Security::sanitize($_GET);

        // Buat instance Router
        $this->router = new Router();
    }

    public function getRouter() {
        return $this->router;
    }

    // Jalankan aplikasi dengan dispatch URL ke router
    public function run() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Panggil router dispatch
        $this->router->dispatch($uri, $method);
    }

    // Custom autoloader untuk namespace App\
    private function autoload($class) {
        $prefix = 'App\\';
        // Base dir satu tingkat di atas Core (yaitu app/)
        $base_dir = dirname(__DIR__) . '/';

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}
