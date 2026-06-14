<?php
// File: app/Controllers/HomeController.php

namespace App\Controllers;

use App\Models\DestinationModel;
use App\Models\TourPackageModel;
use App\Models\CategoryModel;

class HomeController {
    
    // Tampilkan Halaman Utama
    public function index() {
        // Ambil data filter kategori jika ada
        $categoryFilter = $_GET['category'] ?? null;
        $searchKeyword = $_GET['search'] ?? null;
        
        // Ambil geolokasi user jika ditekan "Near Me" / LBS
        $userLat = $_GET['lat'] ?? null;
        $userLng = $_GET['lng'] ?? null;

        $categories = CategoryModel::getAllActive();
        
        // Tambahkan jumlah paket ke masing-masing kategori
        foreach ($categories as &$cat) {
            $cat['package_count'] = TourPackageModel::getCountByCategory($cat['slug']);
        }

        // Ambil destinasi unggulan
        $destinations = DestinationModel::getFeatured();

        // Ambil paket wisata harian
        $packages = TourPackageModel::getFiltered($categoryFilter, $searchKeyword);

        // LBS (Location Based Service) Sorting
        if (!empty($userLat) && !empty($userLng)) {
            $userLat = (float)$userLat;
            $userLng = (float)$userLng;

            // Hitung jarak & urutkan destinasi
            foreach ($destinations as &$dest) {
                if (!empty($dest['latitude']) && !empty($dest['longitude'])) {
                    $dest['distance'] = $this->haversineDistance($userLat, $userLng, (float)$dest['latitude'], (float)$dest['longitude']);
                } else {
                    $dest['distance'] = 999999;
                }
            }
            usort($destinations, function($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            // Hitung jarak & urutkan paket wisata
            foreach ($packages as &$pkg) {
                if (!empty($pkg['latitude']) && !empty($pkg['longitude'])) {
                    $pkg['distance'] = $this->haversineDistance($userLat, $userLng, (float)$pkg['latitude'], (float)$pkg['longitude']);
                } else {
                    $pkg['distance'] = 999999;
                }
            }
            usort($packages, function($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });
        }

        view('home.home', [
            'categories' => $categories,
            'destinations' => $destinations,
            'packages' => $packages,
            'selectedCategory' => $categoryFilter,
            'searchKeyword' => $searchKeyword,
            'userLat' => $userLat,
            'userLng' => $userLng
        ]);
    }

    // Set Bahasa Indonesia
    public function setLangId() {
        $_SESSION['lang'] = 'id';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referrer);
    }

    // Set Bahasa Inggris
    public function setLangEn() {
        $_SESSION['lang'] = 'en';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referrer);
    }

    // Rumus Haversine menghitung jarak geografis dalam Kilometer
    private function haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            
        return $angle * $earthRadius;
    }

    // Serve uploaded images securely from storage/uploads (shared hosting friendly)
    public function serveUpload($file) {
        $filePath = __DIR__ . '/../../storage/uploads/' . basename($file);
        if (file_exists($filePath)) {
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif'
            ];
            $contentType = $mimeTypes[$ext] ?? 'application/octet-stream';
            header('Content-Type: ' . $contentType);
            readfile($filePath);
            exit;
        }
        
        // Return default image placeholder if not found
        $placeholderPath = __DIR__ . '/../../public/assets/images/placeholder.jpg';
        if (file_exists($placeholderPath)) {
            header('Content-Type: image/jpeg');
            readfile($placeholderPath);
            exit;
        }
        
        http_response_code(404);
        echo "File not found";
        exit;
    }
}
