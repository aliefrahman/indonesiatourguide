<?php
// File: scratch/test_maintenance.php

// Backup the original .env content
$envPath = __DIR__ . '/../.env';
$originalEnv = file_get_contents($envPath);

// Create temporary .env with MAINTENANCE_MODE=true
$tempEnv = $originalEnv;
// Replace MAINTENANCE_MODE=false with MAINTENANCE_MODE=true
if (strpos($tempEnv, 'MAINTENANCE_MODE=') !== false) {
    $tempEnv = preg_replace('/MAINTENANCE_MODE=\w+/', 'MAINTENANCE_MODE=true', $tempEnv);
} else {
    $tempEnv .= "\nMAINTENANCE_MODE=true\n";
}
file_put_contents($envPath, $tempEnv);

function runTestRequest($url, $userRole = null) {
    $cmd = "";
    if ($userRole) {
        $cmd .= "TEST_USER_ROLE=" . escapeshellarg($userRole) . " ";
    }
    $cmd .= "REQUEST_URI=" . escapeshellarg($url) . " php -d display_errors=1 -r " . escapeshellarg('
        $_SERVER["REQUEST_URI"] = getenv("REQUEST_URI");
        $_SERVER["REQUEST_METHOD"] = "GET";
        
        // Mock session
        session_save_path("storage/sessions");
        session_start();
        if ($role = getenv("TEST_USER_ROLE")) {
            $_SESSION["user_role"] = $role;
            $_SESSION["user_id"] = 1;
        } else {
            $_SESSION = [];
            session_destroy();
        }
        
        // Load autoloader & helpers
        require_once "app/Core/App.php";
        
        // Run App (App constructor will trigger the check)
        $app = new App\Core\App();
        echo "SUCCESS: App loaded normal\n";
    ');
    
    // Execute command and get status & output
    $descriptorspec = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    
    $process = proc_open($cmd, $descriptorspec, $pipes);
    if (is_resource($process)) {
        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $return_value = proc_close($process);
        return [
            'output' => $output,
            'errors' => $errors,
            'code' => $return_value
        ];
    }
    return null;
}

echo "--- UJI COBA MODE MAINTENANCE ---\n\n";

try {
    // Test 1: Pengunjung publik (bukan admin) mengakses homepage '/'
    echo "Test 1: Pengunjung publik (bukan admin) mengakses homepage ('/')\n";
    $res1 = runTestRequest('/');
    if (strpos($res1['output'], 'Sedang dalam Pemeliharaan') !== false || strpos($res1['output'], 'Under Maintenance') !== false) {
        echo "✓ Hasil sesuai: Menampilkan halaman maintenance (503).\n\n";
    } else {
        echo "✗ GAGAL: Tidak menampilkan halaman maintenance.\n";
        echo "Output: " . $res1['output'] . "\n";
        echo "Error: " . $res1['errors'] . "\n\n";
    }

    // Test 2: Admin mengakses homepage '/'
    echo "Test 2: Admin mengakses homepage ('/')\n";
    $res2 = runTestRequest('/', 'admin');
    if (strpos($res2['output'], 'SUCCESS: App loaded normal') !== false) {
        echo "✓ Hasil sesuai: Admin dapat mengakses homepage secara normal.\n\n";
    } else {
        echo "✗ GAGAL: Admin diblokir.\n";
        echo "Output: " . $res2['output'] . "\n";
        echo "Error: " . $res2['errors'] . "\n\n";
    }

    // Test 3: Pengunjung publik mengakses '/login' (harus diizinkan agar admin bisa login)
    echo "Test 3: Pengunjung publik mengakses halaman '/login'\n";
    $res3 = runTestRequest('/login');
    if (strpos($res3['output'], 'SUCCESS: App loaded normal') !== false) {
        echo "✓ Hasil sesuai: Halaman login tetap dapat diakses oleh publik.\n\n";
    } else {
        echo "✗ GAGAL: Halaman login diblokir.\n";
        echo "Output: " . $res3['output'] . "\n";
        echo "Error: " . $res3['errors'] . "\n\n";
    }
} finally {
    // Restore the original .env file content
    file_put_contents($envPath, $originalEnv);
    echo "Restore file .env selesai.\n";
}
