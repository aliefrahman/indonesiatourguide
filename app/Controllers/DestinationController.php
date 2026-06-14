<?php
// File: app/Controllers/DestinationController.php

namespace App\Controllers;

use App\Models\DestinationModel;
use App\Middleware\RoleCheck;
use App\Core\Csrf;
use App\Core\AuditLogger;

class DestinationController {

    // 1. PUBLIC TRAVELER ACTIONS
    
    // List Destinasi Publik
    public function index() {
        $destinations = DestinationModel::getAllActive();
        view('destinations.destinations', ['destinations' => $destinations]);
    }

    // Detail Destinasi dengan Lightbox
    public function detail($slug) {
        $destination = DestinationModel::findBySlug($slug);
        if (!$destination) {
            http_response_code(404);
            view('errors.404');
            exit;
        }
        view('destinations.view_destinations', ['destination' => $destination]);
    }

    // 2. ADMIN & AGENT CRUD ACTIONS

    // List admin/agent
    public function adminIndex() {
        RoleCheck::requireRole(['admin', 'agent']);
        
        $destinations = DestinationModel::getAll();
        view('destinations.admin_list', ['destinations' => $destinations]);
    }

    // Halaman buat destinasi
    public function create() {
        RoleCheck::requireRole(['admin', 'agent']);
        view('destinations.create_destinations');
    }

    // Simpan destinasi baru
    public function store() {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $nameId = $_POST['name_id'] ?? '';
        $nameEn = $_POST['name_en'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $province = $_POST['province'] ?? '';
        $region = $_POST['region'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $lat = $_POST['latitude'] ?? null;
        $lng = $_POST['longitude'] ?? null;
        $featured = isset($_POST['is_featured']) ? 1 : 0;
        $active = isset($_POST['is_active']) ? 1 : 0;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if (empty($nameId) || empty($nameEn) || empty($slug) || empty($province)) {
            flash('error', 'Bidang Nama, Slug, dan Provinsi wajib diisi.');
            redirect('/admin/destinations/create');
        }

        // Handle multi-upload gambar (Maksimal 5)
        $uploadedImages = $this->handleMultipleUploads();

        $destId = DestinationModel::create([
            'name_id' => $nameId,
            'name_en' => $nameEn,
            'slug' => $slug,
            'province' => $province,
            'region' => $region,
            'description_id' => $descId,
            'description_en' => $descEn,
            'cover_image' => $uploadedImages, // array akan disimpan sebagai JSON di database
            'latitude' => $lat ? (float)$lat : null,
            'longitude' => $lng ? (float)$lng : null,
            'is_featured' => $featured,
            'is_active' => $active,
            'sort_order' => $sortOrder
        ]);

        if ($destId) {
            AuditLogger::logAction('destination_created', "Destinasi baru dibuat: $nameId (ID: $destId)");
            flash('success', 'Destinasi berhasil ditambahkan.');
            redirect('/admin/destinations');
        } else {
            flash('error', 'Gagal menambahkan destinasi.');
            redirect('/admin/destinations/create');
        }
    }

    // Halaman edit destinasi
    public function edit($id) {
        RoleCheck::requireRole(['admin', 'agent']);
        $destination = DestinationModel::find($id);
        if (!$destination) {
            http_response_code(404);
            view('errors.404');
            exit;
        }
        view('destinations.edit_destinations', ['destination' => $destination]);
    }

    // Update destinasi
    public function update($id) {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $destination = DestinationModel::find($id);
        if (!$destination) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $nameId = $_POST['name_id'] ?? '';
        $nameEn = $_POST['name_en'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $province = $_POST['province'] ?? '';
        $region = $_POST['region'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $lat = $_POST['latitude'] ?? null;
        $lng = $_POST['longitude'] ?? null;
        $featured = isset($_POST['is_featured']) ? 1 : 0;
        $active = isset($_POST['is_active']) ? 1 : 0;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if (empty($nameId) || empty($nameEn) || empty($slug) || empty($province)) {
            flash('error', 'Bidang Nama, Slug, dan Provinsi wajib diisi.');
            redirect("/admin/destinations/edit/$id");
        }

        // Cek gambar yang dihapus oleh pengguna
        $deleteImages = $_POST['delete_images'] ?? [];
        foreach ($deleteImages as $img) {
            $filePath = __DIR__ . '/../../storage/uploads/' . basename($img);
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }

        // Ambil gambar lama, lalu filter gambar yang dihapus dan yang tidak ada di server (broken)
        $oldImages = $destination['images'];
        $validOldImages = [];
        foreach ($oldImages as $img) {
            if (!in_array($img, $deleteImages)) {
                $filePath = __DIR__ . '/../../storage/uploads/' . basename($img);
                if (file_exists($filePath)) {
                    $validOldImages[] = $img;
                }
            }
        }

        // Handle multi-upload jika ada gambar baru diunggah
        $newImages = $this->handleMultipleUploads();
        
        // Gabungkan gambar lama yang masih valid dengan gambar baru (maksimal 5)
        $combined = array_merge($validOldImages, $newImages);
        
        $data = [
            'name_id' => $nameId,
            'name_en' => $nameEn,
            'slug' => $slug,
            'province' => $province,
            'region' => $region,
            'description_id' => $descId,
            'description_en' => $descEn,
            'latitude' => $lat ? (float)$lat : null,
            'longitude' => $lng ? (float)$lng : null,
            'is_featured' => $featured,
            'is_active' => $active,
            'sort_order' => $sortOrder
        ];

        // Selalu perbarui cover_image jika ada perubahan pada galeri gambar
        if (!empty($deleteImages) || !empty($newImages) || count($validOldImages) !== count($oldImages)) {
            $data['cover_image'] = array_slice($combined, 0, 5);
        }

        DestinationModel::update($id, $data);
        AuditLogger::logAction('destination_updated', "Destinasi diperbarui: $nameId (ID: $id)");
        flash('success', 'Destinasi berhasil diperbarui.');
        redirect('/admin/destinations');
    }

    // Hapus destinasi
    public function delete($id) {
        RoleCheck::requireRole(['admin', 'agent']);
        Csrf::validate();

        $destination = DestinationModel::find($id);
        if ($destination) {
            DestinationModel::delete($id);
            AuditLogger::logAction('destination_deleted', "Destinasi dihapus: {$destination['name_id']} (ID: $id)");
            flash('success', 'Destinasi berhasil dihapus.');
        } else {
            flash('error', 'Destinasi tidak ditemukan.');
        }
        redirect('/admin/destinations');
    }

    // Helper upload multi-file gambar
    private function handleMultipleUploads() {
        $images = [];
        $uploadDir = __DIR__ . '/../../storage/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!empty($_FILES['cover_images']['name'][0])) {
            $files = $_FILES['cover_images'];
            $count = min(count($files['name']), 5); // maks 5 file

            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $files['tmp_name'][$i];
                    $name = basename($files['name'][$i]);
                    $size = $files['size'][$i];
                    
                    if (empty($tmpName) || !file_exists($tmpName)) {
                        continue;
                    }

                    $mime = mime_content_type($tmpName);
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $maxSize = 2 * 1024 * 1024; // 2 MB

                    if (in_array($mime, $allowedMimes) && $size <= $maxSize) {
                        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        
                        if (in_array($ext, $allowed)) {
                            $newName = 'dest_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                            if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                                $images[] = $newName;
                            }
                        }
                    }
                }
            }
        }
        return $images;
    }
}
