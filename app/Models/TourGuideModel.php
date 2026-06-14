<?php
// File: app/Models/TourGuideModel.php

namespace App\Models;

use App\Core\Model;

class TourGuideModel extends Model {
    
    public static function find($id) {
        $sql = "SELECT tg.*, u.name, u.email, u.phone, u.avatar 
                FROM tour_guides tg 
                JOIN users u ON tg.user_id = u.id 
                WHERE tg.id = ?";
        $stmt = self::query($sql, [$id]);
        $guide = $stmt->fetch();
        if ($guide) {
            $guide['languages_spoken'] = json_decode($guide['languages_spoken'], true) ?: [];
        }
        return $guide;
    }

    public static function findByUserId($userId) {
        $sql = "SELECT tg.*, u.name, u.email, u.phone, u.avatar 
                FROM tour_guides tg 
                JOIN users u ON tg.user_id = u.id 
                WHERE tg.user_id = ?";
        $stmt = self::query($sql, [$userId]);
        $guide = $stmt->fetch();
        
        if (!$guide) {
            // Self-healing: buat baris guide default jika user ber-role guide belum punya data guide
            $userStmt = self::query("SELECT * FROM users WHERE id = ?", [$userId]);
            $user = $userStmt->fetch();
            if ($user && $user['role'] === 'guide') {
                self::create([
                    'user_id' => $userId,
                    'languages_spoken' => ['id'],
                    'bio_id' => 'Bio dalam Bahasa Indonesia',
                    'bio_en' => 'Bio in English',
                    'rating_cache' => 4.80,
                    'is_available' => 1
                ]);
                $stmt = self::query($sql, [$userId]);
                $guide = $stmt->fetch();
            }
        }

        if ($guide) {
            $guide['languages_spoken'] = json_decode($guide['languages_spoken'], true) ?: [];
        }
        return $guide;
    }

    public static function getAllAvailable() {
        $sql = "SELECT tg.*, u.name, u.avatar 
                FROM tour_guides tg 
                JOIN users u ON tg.user_id = u.id 
                WHERE tg.is_available = 1 
                ORDER BY tg.rating_cache DESC";
        $stmt = self::query($sql);
        $guides = $stmt->fetchAll();
        foreach ($guides as &$guide) {
            $guide['languages_spoken'] = json_decode($guide['languages_spoken'], true) ?: [];
        }
        return $guides;
    }

    public static function create($data) {
        $sql = "INSERT INTO tour_guides (user_id, license_number, languages_spoken, bio_id, bio_en, skills_id, skills_en, rating_cache, is_available) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return self::query($sql, [
            $data['user_id'],
            $data['license_number'] ?? null,
            json_encode($data['languages_spoken'] ?? ['id']),
            $data['bio_id'],
            $data['bio_en'],
            $data['skills_id'] ?? null,
            $data['skills_en'] ?? null,
            $data['rating_cache'] ?? 0.00,
            $data['is_available'] ?? 1
        ]);
    }

    public static function update($id, $data) {
        $fields = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'languages_spoken') {
                $fields[] = "languages_spoken = ?";
                $params[] = json_encode($value);
            } elseif ($key !== 'id') {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }
        
        $params[] = $id;
        $sql = "UPDATE tour_guides SET " . implode(', ', $fields) . " WHERE id = ?";
        return self::query($sql, $params);
    }

    // Update rating_cache berdasarkan rata-rata review
    public static function updateRatingCache($guideId) {
        $sql = "UPDATE tour_guides tg 
                SET rating_cache = COALESCE(
                    (SELECT AVG(r.rating) 
                     FROM reviews r 
                     JOIN bookings b ON r.booking_id = b.id 
                     WHERE b.guide_id = tg.id AND r.is_moderated = 1), 0.00
                )
                WHERE tg.id = ?";
        return self::query($sql, [$guideId]);
    }
}
