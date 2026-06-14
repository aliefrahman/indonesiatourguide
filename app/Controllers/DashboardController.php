<?php
// File: app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\TourGuideModel;
use App\Models\ReviewModel;
use App\Models\AuditLogModel;
use App\Middleware\RoleCheck;
use App\Core\Csrf;
use App\Core\AuditLogger;

class DashboardController {

    // Menampilkan Dashboard Utama berdasarkan Peran
    public function index() {
        RoleCheck::requireRole(['admin', 'agent', 'guide', 'traveler']);
        
        $user = auth();
        $data = [
            'role' => $user->role,
            'name' => $user->name
        ];

        // 1. DATA UNTUK ADMIN
        if ($user->role === 'admin') {
            $data['analytics'] = BookingModel::getAnalytics();
            $data['bookings'] = BookingModel::getAll(15, 0); // chunk/limit 15 data teratas
            $data['guides'] = TourGuideModel::getAllAvailable();
            $data['reviews'] = ReviewModel::getAll(10, 0);   // chunk/limit 10 review teratas
            
            // Chunking untuk Laporan logs (Rule #4) - ambil maksimal 25 log
            $data['audit_logs'] = AuditLogModel::getAll(25, 0);
        }
        
        // 2. DATA UNTUK AGENT (Partner)
        elseif ($user->role === 'agent') {
            // Agent hanya mengelola paket & pesanan masuk (tanpa data guides dan audit logs)
            $data['analytics'] = BookingModel::getAnalytics();
            $data['bookings'] = BookingModel::getAll(20, 0);
        }
        
        // 3. DATA UNTUK GUIDE
        elseif ($user->role === 'guide') {
            $guide = TourGuideModel::findByUserId($user->id);
            if ($guide) {
                $data['guide'] = $guide;
                $data['trips'] = BookingModel::getByGuideId($guide['id']);
            } else {
                $data['guide'] = null;
                $data['trips'] = [];
            }
        }
        
        // 4. DATA UNTUK TRAVELER
        elseif ($user->role === 'traveler') {
            $data['bookings'] = BookingModel::getByTravelerId($user->id);
        }

        view('dashboard.dashboard', $data);
    }

    // Aksi Pemandu: Perbarui Ketersediaan (Calendar Sync)
    public function updateGuideAvailability() {
        RoleCheck::requireRole(['guide']);
        Csrf::validate();

        $user = auth();
        $guide = TourGuideModel::findByUserId($user->id);
        if ($guide) {
            $isAvailable = isset($_POST['is_available']) ? 1 : 0;
            TourGuideModel::update($guide['id'], ['is_available' => $isAvailable]);
            
            AuditLogger::logAction('guide_availability_changed', "Pemandu {$user->email} mengubah ketersediaan menjadi: " . ($isAvailable ? 'Tersedia' : 'Cuti'));
            flash('success', 'Jadwal ketersediaan berhasil diperbarui.');
        } else {
            flash('error', 'Detail pemandu tidak ditemukan.');
        }
        redirect('/dashboard');
    }

    // Aksi Admin/Agent: Menugaskan Pemandu Wisata secara Manual
    public function assignGuide() {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $bookingId = $_POST['booking_id'] ?? '';
        $guideId = $_POST['guide_id'] ?? '';

        if (empty($bookingId)) {
            flash('error', 'Pesanan tidak valid.');
            redirect('/dashboard');
        }

        $booking = BookingModel::find($bookingId);
        if (!$booking) {
            flash('error', 'Pesanan tidak ditemukan.');
            redirect('/dashboard');
        }

        $assignedGuideId = !empty($guideId) ? (int)$guideId : null;
        BookingModel::assignGuide($bookingId, $assignedGuideId);
        
        if ($assignedGuideId) {
            $guide = TourGuideModel::find($assignedGuideId);
            AuditLogger::logAction('booking_guide_assigned', "Invoice {$booking['invoice_number']} ditugaskan ke pemandu {$guide['name']}");
            
            // Opsional: Perbarui rating_cache pemandu tersebut
            TourGuideModel::updateRatingCache($assignedGuideId);
        } else {
            AuditLogger::logAction('booking_guide_unassigned', "Invoice {$booking['invoice_number']} pemandu dilepas.");
        }

        flash('success', 'Penugasan pemandu berhasil diperbarui.');
        redirect('/dashboard');
    }

    // Aksi Admin: Moderasi Ulasan (CRM)
    public function moderateReview() {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $reviewId = $_POST['review_id'] ?? '';
        $action = $_POST['action'] ?? ''; // 'approve' atau 'reject'

        if (empty($reviewId) || empty($action)) {
            flash('error', 'Ulasan tidak valid.');
            redirect('/dashboard');
        }

        $isModerated = ($action === 'approve') ? 1 : 0;
        ReviewModel::updateModeration($reviewId, $isModerated);

        // Cari ulasan untuk mengambil data guide_id agar bisa meng-update rating_cache
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query("SELECT b.guide_id FROM reviews r JOIN bookings b ON r.booking_id = b.id WHERE r.id = ?", [$reviewId]);
        $rev = $stmt->fetch();
        if ($rev && !empty($rev['guide_id'])) {
            TourGuideModel::updateRatingCache($rev['guide_id']);
        }

        AuditLogger::logAction('review_moderated', "Ulasan ID $reviewId dimoderasi: " . ($isModerated ? 'Disetujui' : 'Disembunyikan'));
        flash('success', 'Moderasi ulasan berhasil diperbarui.');
        redirect('/dashboard');
    }

    // Aksi Traveler: Membuat Ulasan Baru
    public function createReview() {
        RoleCheck::requireRole(['traveler']);
        Csrf::validate();

        $bookingId = $_POST['booking_id'] ?? '';
        $rating = (int)($_POST['rating'] ?? 5);
        $comment = $_POST['comment'] ?? '';

        if (empty($bookingId) || $rating < 1 || $rating > 5) {
            flash('error', 'Rating bintang harus antara 1 s.d 5.');
            redirect('/dashboard');
        }

        $booking = BookingModel::find($bookingId);
        if (!$booking || $booking['traveler_id'] != auth()->id) {
            flash('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
            redirect('/dashboard');
        }

        ReviewModel::create([
            'booking_id' => $bookingId,
            'rating' => $rating,
            'comment' => $comment,
            'is_moderated' => 0 // ulasan baru memerlukan moderasi admin
        ]);

        AuditLogger::logAction('review_created', "Traveler memberi ulasan pada Invoice {$booking['invoice_number']} (Menunggu moderasi)");
        flash('success', 'Terima kasih atas ulasan Anda! Ulasan Anda sedang diverifikasi oleh admin.');
        redirect('/dashboard');
    }
}
