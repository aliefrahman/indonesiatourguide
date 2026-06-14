<?php
// File: app/Models/ReviewModel.php

namespace App\Models;

use App\Core\Model;

class ReviewModel extends Model {

    public static function create($data) {
        $sql = "INSERT INTO reviews (booking_id, rating, comment, is_moderated) VALUES (?, ?, ?, ?)";
        self::query($sql, [
            $data['booking_id'],
            $data['rating'],
            $data['comment'] ?? null,
            $data['is_moderated'] ?? 0
        ]);
        return self::db()->lastInsertId();
    }

    public static function updateModeration($id, $isModerated) {
        $sql = "UPDATE reviews SET is_moderated = ? WHERE id = ?";
        return self::query($sql, [$isModerated, $id]);
    }

    // Mendapatkan ulasan untuk suatu paket wisata
    public static function getByPackageId($packageId) {
        // Gabungkan maksimal 2 JOIN (reviews -> bookings -> users)
        $sql = "SELECT r.*, u.name as traveler_name, u.avatar as traveler_avatar 
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.id
                JOIN users u ON b.traveler_id = u.id
                WHERE b.package_id = ? AND r.is_moderated = 1
                ORDER BY r.id DESC";
        return self::query($sql, [$packageId])->fetchAll();
    }

    // Mendapatkan ulasan untuk pemandu wisata
    public static function getByGuideId($guideId) {
        $sql = "SELECT r.*, u.name as traveler_name, b.package_name_snapshot 
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.id
                JOIN users u ON b.traveler_id = u.id
                WHERE b.guide_id = ? AND r.is_moderated = 1
                ORDER BY r.id DESC";
        return self::query($sql, [$guideId])->fetchAll();
    }

    // Mendapatkan semua ulasan untuk panel Admin (CRM)
    public static function getAll($limit = 100, $offset = 0) {
        $sql = "SELECT r.*, u.name as traveler_name, b.package_name_snapshot, b.invoice_number 
                FROM reviews r
                JOIN bookings b ON r.booking_id = b.id
                JOIN users u ON b.traveler_id = u.id
                ORDER BY r.id DESC 
                LIMIT ? OFFSET ?";
        $stmt = self::query($sql, [(int)$limit, (int)$offset]);
        return $stmt->fetchAll();
    }
}
