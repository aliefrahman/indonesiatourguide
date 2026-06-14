<?php
// File: app/Models/CategoryModel.php

namespace App\Models;

use App\Core\Model;

class CategoryModel extends Model {

    public static function find($id) {
        $stmt = self::query("SELECT * FROM tour_categories WHERE id = ?", [$id]);
        return $stmt->fetch();
    }

    public static function findBySlug($slug) {
        $stmt = self::query("SELECT * FROM tour_categories WHERE slug = ?", [$slug]);
        return $stmt->fetch();
    }

    public static function getAllActive() {
        $stmt = self::query("SELECT * FROM tour_categories WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }

    public static function getAll() {
        $stmt = self::query("SELECT * FROM tour_categories ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $sql = "INSERT INTO tour_categories (slug, name_id, name_en, icon, description_id, description_en, sort_order, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        self::query($sql, [
            $data['slug'],
            $data['name_id'],
            $data['name_en'],
            $data['icon'] ?? null,
            $data['description_id'] ?? null,
            $data['description_en'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_active'] ?? 1
        ]);
        return self::db()->lastInsertId();
    }

    public static function update($id, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id') {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $id;
        $sql = "UPDATE tour_categories SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::query($sql, $params);
    }

    public static function delete($id) {
        return self::query("DELETE FROM tour_categories WHERE id = ?", [$id]);
    }
}
