<?php
// File: app/Controllers/TourController.php

namespace App\Controllers;

use App\Models\TourPackageModel;
use App\Models\ItineraryModel;
use App\Models\TourGuideModel;
use App\Models\BookingModel;
use App\Models\ReviewModel;
use App\Models\CategoryModel;
use App\Models\DestinationModel;
use App\Middleware\RoleCheck;
use App\Core\Csrf;
use App\Core\AuditLogger;

class TourController
{

    // 1. PUBLIC TRAVELER ACTIONS

    // List Paket Wisata Publik
    public function index()
    {
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;
        $packages = TourPackageModel::getFiltered($category, $search);
        $categories = CategoryModel::getAllActive();

        view('tour.tour', [
            'packages' => $packages,
            'categories' => $categories,
            'selectedCategory' => $category,
            'searchKeyword' => $search
        ]);
    }

    // Detail Paket Wisata dengan Itinerary dan Ulasan
    public function detail($id)
    {
        $package = TourPackageModel::find($id);
        if (!$package) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $itineraries = ItineraryModel::findByPackageId($id);
        $guides = TourGuideModel::getAllAvailable();
        $reviews = ReviewModel::getByPackageId($id);

        view('tour.package_detail', [
            'package' => $package,
            'itineraries' => $itineraries,
            'guides' => $guides,
            'reviews' => $reviews
        ]);
    }

    // Halaman Booking / Checkout
    public function checkout($id)
    {
        RoleCheck::requireRole(['traveler', 'admin', 'guide', 'agent']);

        $package = TourPackageModel::find($id);
        if (!$package) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $selectedGuideId = $_GET['guide_id'] ?? null;
        $selectedGuide = null;
        if ($selectedGuideId) {
            $selectedGuide = TourGuideModel::find($selectedGuideId);
        }

        $guides = TourGuideModel::getAllAvailable();

        view('tour.checkout', [
            'package' => $package,
            'guides' => $guides,
            'selectedGuide' => $selectedGuide
        ]);
    }

    // Proses Checkout dan Pembuatan Invoice
    public function processCheckout($id)
    {
        RoleCheck::requireRole(['traveler', 'admin', 'guide', 'agent']);
        Csrf::validate();

        $package = TourPackageModel::find($id);
        if (!$package) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $traveler = auth();
        $guideId = $_POST['guide_id'] ?? null;
        $travelDate = $_POST['travel_date'] ?? '';
        $participants = (int) ($_POST['total_participants'] ?? 1);

        if (empty($travelDate) || $participants <= 0) {
            flash('error', 'Tanggal keberangkatan dan jumlah peserta wajib diisi.');
            redirect("/tours/checkout/$id");
        }

        // Hitung total harga
        $totalPrice = $package['price'] * $participants;

        // Denormalisasi Snapshot Paket sesuai Rule #1
        $bookingData = [
            'traveler_id' => $traveler->id,
            'package_id' => $package['id'],
            'guide_id' => !empty($guideId) ? $guideId : null,
            'package_name_snapshot' => $package['title_id'], // Denormalisasi nama paket
            'package_price_snapshot' => $package['price'],    // Denormalisasi harga paket
            'travel_date' => $travelDate,
            'total_participants' => $participants,
            'total_price' => $totalPrice,
            'payment_status' => 'paid', // Karena simulasi instan, status diset langsung 'paid' (atau pending)
            'payment_token' => 'TOK-' . bin2hex(random_bytes(8))
        ];

        $bookingId = BookingModel::create($bookingData);

        if ($bookingId) {
            $booking = BookingModel::find($bookingId);
            AuditLogger::logAction('booking_created', "Pemesanan paket {$package['title_id']} berhasil (Invoice: {$booking['invoice_number']})");
            redirect("/tours/booking-success/{$booking['invoice_number']}");
        } else {
            flash('error', 'Terjadi kesalahan saat memproses checkout.');
            redirect("/tours/checkout/$id");
        }
    }

    // Halaman Sukses Pemesanan
    public function bookingSuccess($invoice)
    {
        $booking = BookingModel::findByInvoice($invoice);
        if (!$booking) {
            http_response_code(404);
            view('errors.404');
            exit;
        }
        view('tour.booking_success', ['booking' => $booking]);
    }

    // 2. ADMIN & AGENT CRUD ACTIONS

    // List admin/agent
    public function adminIndex()
    {
        RoleCheck::requireRole(['admin', 'agent']);
        $packages = TourPackageModel::getAll();
        view('tour.admin_list', ['packages' => $packages]);
    }

    // Halaman tambah paket wisata
    public function create()
    {
        RoleCheck::requireRole(['admin', 'agent']);
        $categories = CategoryModel::getAllActive();
        $destinations = DestinationModel::getAllActive();
        view('tour.create_tour', [
            'categories' => $categories,
            'destinations' => $destinations
        ]);
    }

    // Simpan paket wisata baru
    public function store()
    {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $titleId = $_POST['title_id'] ?? '';
        $titleEn = $_POST['title_en'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $price = (float) ($_POST['price'] ?? 0);
        $durationDays = (int) ($_POST['duration_days'] ?? 1);
        $location = $_POST['location_name'] ?? '';
        $lat = $_POST['latitude'] ?? null;
        $lng = $_POST['longitude'] ?? null;
        $category = $_POST['category'] ?? '';

        if (empty($titleId) || empty($titleEn) || empty($location) || $price <= 0) {
            flash('error', 'Judul, Lokasi, dan Harga paket wajib diisi.');
            redirect('/admin/tours/create');
        }

        // Upload single cover image
        $coverName = 'default_pkg.jpg';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['cover_image']['tmp_name'];
            $name = basename($_FILES['cover_image']['name']);
            $size = $_FILES['cover_image']['size'];
            $mime = mime_content_type($tmp);
            
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2 MB
            
            if (in_array($mime, $allowedMimes) && $size <= $maxSize) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $coverName = 'pkg_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../storage/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    move_uploaded_file($tmp, $uploadDir . $coverName);
                }
            } else {
                flash('error', 'Format gambar tidak valid atau ukuran melebih 2MB.');
                redirect('/admin/tours/create');
            }
        }

        // Paket wisata cover image disimpan sebagai string JSON array sesuai PRD / Gemini.md
        $pkgId = TourPackageModel::create([
            'title_id' => $titleId,
            'title_en' => $titleEn,
            'description_id' => $descId,
            'description_en' => $descEn,
            'price' => $price,
            'duration_days' => $durationDays,
            'location_name' => $location,
            'latitude' => $lat ? (float) $lat : null,
            'longitude' => $lng ? (float) $lng : null,
            'cover_image' => [$coverName], // Bungkus jadi array satu-elemen
            'category' => $category
        ]);

        if ($pkgId) {
            // Simpan Itinerary harian yang dinamis
            $this->saveItineraryInputs($pkgId);

            AuditLogger::logAction('tour_package_created', "Paket wisata dibuat: $titleId (ID: $pkgId)");
            flash('success', 'Paket wisata berhasil ditambahkan.');
            redirect('/admin/tours');
        } else {
            flash('error', 'Gagal menambahkan paket wisata.');
            redirect('/admin/tours/create');
        }
    }

    // Halaman edit paket wisata
    public function edit($id)
    {
        RoleCheck::requireRole(['admin', 'agent']);

        $package = TourPackageModel::find($id);
        if (!$package) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $categories = CategoryModel::getAllActive();
        $destinations = DestinationModel::getAllActive();
        $itineraries = ItineraryModel::findByPackageId($id);

        view('tour.edit_tour', [
            'package' => $package,
            'categories' => $categories,
            'destinations' => $destinations,
            'itineraries' => $itineraries
        ]);
    }

    // Update paket wisata
    public function update($id)
    {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $package = TourPackageModel::find($id);
        if (!$package) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $titleId = $_POST['title_id'] ?? '';
        $titleEn = $_POST['title_en'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $price = (float) ($_POST['price'] ?? 0);
        $durationDays = (int) ($_POST['duration_days'] ?? 1);
        $location = $_POST['location_name'] ?? '';
        $lat = $_POST['latitude'] ?? null;
        $lng = $_POST['longitude'] ?? null;
        $category = $_POST['category'] ?? '';

        if (empty($titleId) || empty($titleEn) || empty($location) || $price <= 0) {
            flash('error', 'Judul, Lokasi, dan Harga paket wajib diisi.');
            redirect("/admin/tours/edit/$id");
        }

        $data = [
            'title_id' => $titleId,
            'title_en' => $titleEn,
            'description_id' => $descId,
            'description_en' => $descEn,
            'price' => $price,
            'duration_days' => $durationDays,
            'location_name' => $location,
            'latitude' => $lat ? (float) $lat : null,
            'longitude' => $lng ? (float) $lng : null,
            'category' => $category
        ];

        // Jika upload gambar baru
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['cover_image']['tmp_name'];
            $name = basename($_FILES['cover_image']['name']);
            $size = $_FILES['cover_image']['size'];
            $mime = mime_content_type($tmp);
            
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 2 * 1024 * 1024; // 2 MB
            
            if (in_array($mime, $allowedMimes) && $size <= $maxSize) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $coverName = 'pkg_' . time() . '_' . rand(100, 999) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../storage/uploads/';
                    if (move_uploaded_file($tmp, $uploadDir . $coverName)) {
                        $data['cover_image'] = [$coverName]; // Timpa dengan gambar baru
                    }
                }
            } else {
                flash('error', 'Format gambar tidak valid atau ukuran melebih 2MB.');
                redirect("/admin/tours/edit/$id");
            }
        }

        TourPackageModel::update($id, $data);

        // Update Itinerary
        $this->saveItineraryInputs($id);

        AuditLogger::logAction('tour_package_updated', "Paket wisata diperbarui: $titleId (ID: $id)");
        flash('success', 'Paket wisata berhasil diperbarui.');
        redirect('/admin/tours');
    }

    // Hapus paket wisata
    public function delete($id)
    {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $package = TourPackageModel::find($id);
        if ($package) {
            TourPackageModel::delete($id);
            AuditLogger::logAction('tour_package_deleted', "Paket wisata dihapus: {$package['title_id']} (ID: $id)");
            flash('success', 'Paket wisata berhasil dihapus.');
        } else {
            flash('error', 'Paket wisata tidak ditemukan.');
        }
        redirect('/admin/tours');
    }

    // Simpan data input itinerary ke database
    private function saveItineraryInputs($packageId)
    {
        $itineraries = [];
        $days = $_POST['iti_day'] ?? [];
        $starts = $_POST['iti_start'] ?? [];
        $ends = $_POST['iti_end'] ?? [];
        $actIds = $_POST['iti_activity_id'] ?? [];
        $actEns = $_POST['iti_activity_en'] ?? [];
        $notesIds = $_POST['iti_notes_id'] ?? [];
        $notesEns = $_POST['iti_notes_en'] ?? [];

        for ($i = 0; $i < count($days); $i++) {
            if (!empty($actIds[$i]) && !empty($actEns[$i])) {
                $itineraries[] = [
                    'day_number' => (int) $days[$i],
                    'time_start' => $starts[$i],
                    'time_end' => $ends[$i],
                    'activity_id' => $actIds[$i],
                    'activity_en' => $actEns[$i],
                    'notes_id' => $notesIds[$i] ?? null,
                    'notes_en' => $notesEns[$i] ?? null
                ];
            }
        }

        if (!empty($itineraries)) {
            ItineraryModel::saveBatch($packageId, $itineraries);
        }
    }
}
