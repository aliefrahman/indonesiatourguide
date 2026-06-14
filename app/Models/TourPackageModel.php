<?php
// File: app/Models/TourPackageModel.php

namespace App\Models;

use App\Core\Model;

class TourPackageModel extends Model {

    public static function find($id) {
        $stmt = self::query("SELECT * FROM tour_packages WHERE id = ?", [$id]);
        $package = $stmt->fetch();
        if ($package) {
            $package['images'] = self::decodeImages($package['cover_image']);
        }
        return $package;
    }

    public static function getAll($limit = 100, $offset = 0) {
        $stmt = self::query("SELECT * FROM tour_packages ORDER BY id DESC LIMIT ? OFFSET ?", [(int)$limit, (int)$offset]);
        $packages = $stmt->fetchAll();
        foreach ($packages as &$package) {
            $package['images'] = self::decodeImages($package['cover_image']);
        }
        return $packages;
    }

    // Mendapatkan paket wisata terfilter berdasarkan kategori dan pencarian keyword
    public static function getFiltered($category = null, $keyword = null, $limit = 100, $offset = 0) {
        $sql = "SELECT * FROM tour_packages WHERE 1=1";
        $params = [];

        if (!empty($category)) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        if (!empty($keyword)) {
            $sql .= " AND (title_id LIKE ? OR title_en LIKE ? OR location_name LIKE ?)";
            $search = "%$keyword%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
        // SQLite dan MySQL menangani limit di prepared statements berbeda jika data float/string, jadi cast ke int
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        $stmt = self::query($sql, $params);
        $packages = $stmt->fetchAll();
        foreach ($packages as &$package) {
            $package['images'] = self::decodeImages($package['cover_image']);
        }
        return $packages;
    }

    public static function getCountByCategory($categorySlug) {
        $sql = "SELECT COUNT(*) as count FROM tour_packages WHERE category = ?";
        $stmt = self::query($sql, [$categorySlug]);
        $res = $stmt->fetch();
        return $res['count'] ?? 0;
    }

    public static function create($data) {
        $sql = "INSERT INTO tour_packages (title_id, title_en, description_id, description_en, price, duration_days, location_name, latitude, longitude, cover_image, category) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $coverImage = is_array($data['cover_image']) ? json_encode($data['cover_image']) : $data['cover_image'];

        self::query($sql, [
            $data['title_id'],
            $data['title_en'],
            $data['description_id'],
            $data['description_en'],
            $data['price'],
            $data['duration_days'] ?? 1,
            $data['location_name'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $coverImage,
            $data['category'] ?? 'adventure'
        ]);

        return self::db()->lastInsertId();
    }

    public static function update($id, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'cover_image' && is_array($value)) {
                $fields[] = "cover_image = ?";
                $params[] = json_encode($value);
            } elseif ($key !== 'id') {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $id;
        $sql = "UPDATE tour_packages SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::query($sql, $params);
    }

    public static function delete($id) {
        return self::query("DELETE FROM tour_packages WHERE id = ?", [$id]);
    }

    // Decode cover_image dengan Backward Compatibility (Kompatibilitas Mundur)
    public static function decodeImages($coverField) {
        $images = json_decode($coverField, true);
        if (!is_array($images)) {
            $images = !empty($coverField) ? [$coverField] : [];
        }
        return $images;
    }
}
