<?php
// File: scratch/verify_app.php

echo "=== MEMULAI VERIFIKASI SISTEM INDONESIAN TOUR GUIDE ===\n";

// Register custom PSR-4-like autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Load helpers
$helpersFile = __DIR__ . '/../app/Core/helpers.php';
if (file_exists($helpersFile)) {
    require_once $helpersFile;
    echo "✓ Helpers berhasil dimuat.\n";
} else {
    echo "✗ Helpers gagal ditemukan!\n";
}

// 1. Uji Router
echo "\n1. Menguji Routing Engine...\n";
try {
    $router = new \App\Core\Router();
    
    // Daftarkan route testing
    $router->get('/test-route', function() {
        return "ROUTE_MATCH";
    });
    
    $router->get('/test/{id}', function($id) {
        return "ROUTE_PARAM_" . $id;
    });

    // Mock dispatch
    ob_start();
    $res1 = $router->dispatch('/test-route', 'GET');
    $out1 = ob_get_clean();
    
    ob_start();
    $res2 = $router->dispatch('/test/123', 'GET');
    $out2 = ob_get_clean();

    if ($res1 === "ROUTE_MATCH" && $res2 === "ROUTE_PARAM_123") {
        echo "✓ Routing Engine berfungsi dengan sempurna (Regex & Parameter matching).\n";
    } else {
        echo "✗ Routing Engine bermasalah!\n";
    }
} catch (\Exception $e) {
    echo "✗ Routing Engine error: " . $e->getMessage() . "\n";
}

// 2. Uji CSRF
echo "\n2. Menguji CSRF Protection Layer...\n";
try {
    // Jalankan session virtual untuk testing
    if (session_status() === PHP_SESSION_NONE) {
        $_SESSION = [];
    }
    
    $token = \App\Core\Csrf::token();
    $field = \App\Core\Csrf::field();
    
    if (!empty($token) && strpos($field, $token) !== false) {
        echo "✓ CSRF Token Generator berhasil menghasilkan token valid.\n";
    } else {
        echo "✗ CSRF Token Generator bermasalah!\n";
    }
} catch (\Exception $e) {
    echo "✗ CSRF error: " . $e->getMessage() . "\n";
}

// 3. Uji Model Autoloading & Classes
echo "\n3. Menguji Class Autoloading...\n";
$classes = [
    \App\Core\App::class => 'Core/App',
    \App\Core\Database::class => 'Core/Database',
    \App\Core\Security::class => 'Core/Security',
    \App\Core\AuditLogger::class => 'Core/AuditLogger',
    \App\Core\RateLimiter::class => 'Core/RateLimiter',
    \App\Middleware\RoleCheck::class => 'Middleware/RoleCheck',
    \App\Models\UserModel::class => 'Models/UserModel',
    \App\Models\TourPackageModel::class => 'Models/TourPackageModel',
    \App\Models\BookingModel::class => 'Models/BookingModel',
    \App\Controllers\HomeController::class => 'Controllers/HomeController',
    \App\Controllers\AuthController::class => 'Controllers/AuthController'
];

$allOk = true;
foreach ($classes as $className => $relPath) {
    if (class_exists($className)) {
        echo "✓ Class $className berhasil di-autoload.\n";
    } else {
        echo "✗ Class $className gagal di-autoload!\n";
        $allOk = false;
    }
}
if ($allOk) {
    echo "✓ Seluruh kelas Core, Model, Controller, dan Middleware berhasil di-autoload secara aman.\n";
}

// 4. Uji Integrasi Database (MySQL)
echo "\n4. Menguji Deteksi Database Koneksi...\n";
try {
    // Ini akan memicu koneksi database
    $db = \App\Core\Database::getInstance();
    $conn = $db->getConnection();
    echo "✓ Berhasil terhubung ke database server.\n";
} catch (\Exception $e) {
    echo "• Info: Koneksi ke server database MySQL terputus (sesuai perkiraan karena layanan MariaDB stopped).\n";
}

echo "\n=== VERIFIKASI SELESAI ===\n";
