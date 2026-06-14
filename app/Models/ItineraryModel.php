<?php
// File: app/Models/ItineraryModel.php

namespace App\Models;

use App\Core\Model;

class ItineraryModel extends Model {

    public static function findByPackageId($packageId) {
        $sql = "SELECT * FROM itineraries WHERE package_id = ? ORDER BY day_number ASC, time_start ASC";
        $stmt = self::query($sql, [$packageId]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $sql = "INSERT INTO itineraries (package_id, day_number, time_start, time_end, activity_id, activity_en, notes_id, notes_en) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return self::query($sql, [
            $data['package_id'],
            $data['day_number'],
            $data['time_start'],
            $data['time_end'],
            $data['activity_id'],
            $data['activity_en'],
            $data['notes_id'] ?? null,
            $data['notes_en'] ?? null
        ]);
    }

    public static function deleteByPackageId($packageId) {
        return self::query("DELETE FROM itineraries WHERE package_id = ?", [$packageId]);
    }

    public static function saveBatch($packageId, $itineraries) {
        // Hapus itinerary lama
        self::deleteByPackageId($packageId);
        
        // Simpan itinerary baru
        foreach ($itineraries as $item) {
            $item['package_id'] = $packageId;
            self::create($item);
        }
    }
}
