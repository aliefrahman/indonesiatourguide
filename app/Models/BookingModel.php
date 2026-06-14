<?php
// File: app/Models/BookingModel.php

namespace App\Models;

use App\Core\Model;

class BookingModel extends Model {

    public static function find($id) {
        // Gabungkan maksimal 2 JOIN (Rule #1: Dilarang keras menulis query dengan lebih dari 2 JOIN)
        // Gabungkan booking dengan users (traveler) dan tour_guides (atau users guide)
        // Kita join booking dengan users traveler, dan details package_name_snapshot sudah ada di bookings.
        $sql = "SELECT b.*, u.name as traveler_name, u.email as traveler_email 
                FROM bookings b
                JOIN users u ON b.traveler_id = u.id
                WHERE b.id = ?";
        $stmt = self::query($sql, [$id]);
        return $stmt->fetch();
    }

    public static function findByInvoice($invoiceNumber) {
        $sql = "SELECT b.*, u.name as traveler_name, u.email as traveler_email 
                FROM bookings b
                JOIN users u ON b.traveler_id = u.id
                WHERE b.invoice_number = ?";
        $stmt = self::query($sql, [$invoiceNumber]);
        return $stmt->fetch();
    }

    public static function getWithGuideDetails($id) {
        // Melakukan join kedua secara terpisah jika butuh data guide, menghindari join > 2 level
        $booking = self::find($id);
        if ($booking && !empty($booking['guide_id'])) {
            $sql = "SELECT tg.license_number, u.name as guide_name, u.phone as guide_phone 
                    FROM tour_guides tg
                    JOIN users u ON tg.user_id = u.id
                    WHERE tg.id = ?";
            $stmt = self::query($sql, [$booking['guide_id']]);
            $booking['guide'] = $stmt->fetch();
        }
        return $booking;
    }

    public static function create($data) {
        // Generate Invoice Number
        $invoiceNumber = 'INV-' . date('YmdHis') . '-' . rand(100, 999);
        
        $sql = "INSERT INTO bookings (invoice_number, traveler_id, package_id, guide_id, package_name_snapshot, package_price_snapshot, travel_date, total_participants, total_price, payment_status, payment_token) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        self::query($sql, [
            $invoiceNumber,
            $data['traveler_id'],
            $data['package_id'],
            $data['guide_id'] ?? null,
            $data['package_name_snapshot'],
            $data['package_price_snapshot'],
            $data['travel_date'],
            $data['total_participants'] ?? 1,
            $data['total_price'],
            $data['payment_status'] ?? 'pending',
            $data['payment_token'] ?? null
        ]);

        return self::db()->lastInsertId();
    }

    public static function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET payment_status = ? WHERE id = ?";
        return self::query($sql, [$status, $id]);
    }

    public static function assignGuide($id, $guideId) {
        $sql = "UPDATE bookings SET guide_id = ? WHERE id = ?";
        return self::query($sql, [$guideId, $id]);
    }

    // Mendapatkan list booking untuk traveler
    public static function getByTravelerId($travelerId) {
        $sql = "SELECT b.*, g_u.name as guide_name 
                FROM bookings b
                LEFT JOIN tour_guides tg ON b.guide_id = tg.id
                LEFT JOIN users g_u ON tg.user_id = g_u.id
                WHERE b.traveler_id = ?
                ORDER BY b.id DESC";
        return self::query($sql, [$travelerId])->fetchAll();
    }

    // Mendapatkan list booking untuk guide
    public static function getByGuideId($guideId) {
        $sql = "SELECT b.*, u.name as traveler_name, u.phone as traveler_phone 
                FROM bookings b
                JOIN users u ON b.traveler_id = u.id
                WHERE b.guide_id = ?
                ORDER BY b.travel_date ASC";
        return self::query($sql, [$guideId])->fetchAll();
    }

    // Mendapatkan semua booking (untuk admin / agent)
    public static function getAll($limit = 100, $offset = 0) {
        // Optimasi Memori: batasi atau pecah query (Rule #4)
        $sql = "SELECT b.*, u.name as traveler_name, g_u.name as guide_name 
                FROM bookings b
                JOIN users u ON b.traveler_id = u.id
                LEFT JOIN tour_guides tg ON b.guide_id = tg.id
                LEFT JOIN users g_u ON tg.user_id = g_u.id
                ORDER BY b.id DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = self::query($sql, [(int)$limit, (int)$offset]);
        return $stmt->fetchAll();
    }

    // Perhitungan finansial / analitik untuk dashboard admin
    public static function getAnalytics() {
        $stats = [];
        
        // Total Pendapatan
        $res = self::query("SELECT SUM(total_price) as total FROM bookings WHERE payment_status = 'paid'")->fetch();
        $stats['total_revenue'] = $res['total'] ?? 0;
        
        // Pesanan Aktif
        $res = self::query("SELECT COUNT(*) as count FROM bookings WHERE payment_status = 'paid'")->fetch();
        $stats['active_bookings'] = $res['count'] ?? 0;
        
        // Pesanan Pending
        $res = self::query("SELECT COUNT(*) as count FROM bookings WHERE payment_status = 'pending'")->fetch();
        $stats['pending_bookings'] = $res['count'] ?? 0;
        
        // Pesanan Gagal/Batal
        $res = self::query("SELECT COUNT(*) as count FROM bookings WHERE payment_status = 'failed'")->fetch();
        $stats['failed_bookings'] = $res['count'] ?? 0;

        return $stats;
    }
}
