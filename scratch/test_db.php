<?php
// File: scratch/test_db.php

require_once __DIR__ . '/../app/Core/Database.php';

// Setup autoloader
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

try {
    $db = \App\Core\Database::getInstance();
    $conn = $db->getConnection();
    echo "Koneksi berhasil!\n";
    
    // Cek driver
    $config = require __DIR__ . '/../config/database.php';
    echo "Driver aktif: " . $config['driver'] . "\n";
    
    // Ambil daftar tabel
    if ($config['driver'] === 'sqlite') {
        $stmt = $conn->query("SELECT name FROM sqlite_master WHERE type='table'");
    } else {
        $stmt = $conn->query("SHOW TABLES");
    }
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tabel yang ada: " . implode(', ', $tables) . "\n";
    
    if (in_array('destinations', $tables)) {
        $stmt = $conn->query("SELECT id, name_id, slug, cover_image FROM destinations");
        $destinations = $stmt->fetchAll();
        echo "Jumlah destinasi di tabel: " . count($destinations) . "\n";
        foreach ($destinations as $dest) {
            echo "ID: {$dest['id']}, Name: {$dest['name_id']}, Slug: {$dest['slug']}, Cover Image: {$dest['cover_image']}\n";
        }
    } else {
        echo "Tabel destinations TIDAK ditemukan!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
