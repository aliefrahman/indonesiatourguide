<?php
// File: config/database.php

require_once __DIR__ . '/../app/Core/helpers.php';

$sqlitePath = env('DB_SQLITE_DATABASE', 'storage/tour_guide_db.sqlite');
if ($sqlitePath && !str_starts_with($sqlitePath, '/') && !str_contains($sqlitePath, ':')) {
    $sqlitePath = __DIR__ . '/../' . $sqlitePath;
}

return [
    // Pilihan driver: 'mysql' atau 'sqlite'
    // Default menggunakan mysql, namun jika MariaDB/MySQL belum dinyalakan, 
    // pengembang dapat mengubah nilai ini menjadi 'sqlite' untuk pengujian lokal.
    'driver' => env('DB_DRIVER', 'mysql'),

    'mysql' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'tour_guide_db'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', 'CDP17s1850913#^_^'),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
    ],

    'sqlite' => [
        'database' => $sqlitePath,
    ]
];
