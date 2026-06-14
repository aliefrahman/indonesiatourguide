<?php
// File: app/Models/DestinationModel.php

namespace App\Models;

use App\Core\Model;

class DestinationModel extends Model {

    public static function find($id) {
        $stmt = self::query("SELECT * FROM destinations WHERE id = ?", [$id]);
        $dest = $stmt->fetch();
        if ($dest) {
            $dest['images'] = self::decodeImages($dest['cover_image']);
        }
        return $dest;
    }

    public static function findBySlug($slug) {
        $stmt = self::query("SELECT * FROM destinations WHERE slug = ?", [$slug]);
        $dest = $stmt->fetch();
        if ($dest) {
            $dest['images'] = self::decodeImages($dest['cover_image']);
        }
        return $dest;
    }

    public static function getAllActive() {
        $stmt = self::query("SELECT * FROM destinations WHERE is_active = 1 ORDER BY sort_order ASC");
        $dests = $stmt->fetchAll();
        foreach ($dests as &$dest) {
            $dest['images'] = self::decodeImages($dest['cover_image']);
        }
        return $dests;
    }

    public static function getFeatured() {
        $stmt = self::query("SELECT * FROM destinations WHERE is_active = 1 AND is_featured = 1 ORDER BY sort_order ASC");
        $dests = $stmt->fetchAll();
        foreach ($dests as &$dest) {
            $dest['images'] = self::decodeImages($dest['cover_image']);
        }
        return $dests;
    }

    public static function getAll($limit = 100, $offset = 0) {
        $stmt = self::query("SELECT * FROM destinations ORDER BY sort_order ASC LIMIT ? OFFSET ?", [(int)$limit, (int)$offset]);
        $dests = $stmt->fetchAll();
        foreach ($dests as &$dest) {
            $dest['images'] = self::decodeImages($dest['cover_image']);
        }
        return $dests;
    }

    public static function create($data) {
        $sql = "INSERT INTO destinations (name_id, name_en, slug, province, region, description_id, description_en, cover_image, latitude, longitude, is_featured, is_active, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $coverImage = is_array($data['cover_image']) ? json_encode($data['cover_image']) : $data['cover_image'];

        self::query($sql, [
            $data['name_id'],
            $data['name_en'],
            $data['slug'],
            $data['province'],
            $data['region'] ?? null,
            $data['description_id'] ?? null,
            $data['description_en'] ?? null,
            $coverImage,
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['is_featured'] ?? 0,
            $data['is_active'] ?? 1,
            $data['sort_order'] ?? 0
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
        $sql = "UPDATE destinations SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::query($sql, $params);
    }

    public static function delete($id) {
        return self::query("DELETE FROM destinations WHERE id = ?", [$id]);
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
