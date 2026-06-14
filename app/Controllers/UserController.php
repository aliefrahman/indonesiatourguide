<?php
// File: app/Controllers/UserController.php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TourGuideModel;
use App\Middleware\RoleCheck;
use App\Core\Csrf;
use App\Core\AuditLogger;

class UserController {

    // 1. ADMIN ACTIONS: CRUD USERS
    
    public function index() {
        RoleCheck::requireRole(['admin']);
        $users = UserModel::getAll();
        view('users.users', ['users' => $users]);
    }

    public function create() {
        RoleCheck::requireRole(['admin']);
        view('users.create_users');
    }

    public function store() {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'traveler';
        $phone = $_POST['phone'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            flash('error', 'Nama, email, dan password wajib diisi.');
            redirect('/admin/users/create');
        }

        if (UserModel::findByEmail($email)) {
            flash('error', 'Email tersebut sudah terdaftar.');
            redirect('/admin/users/create');
        }

        $userId = UserModel::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'phone' => $phone,
            'avatar' => null
        ]);

        if ($userId) {
            // Jika role adalah guide, tambahkan data pemandu kosong default
            if ($role === 'guide') {
                TourGuideModel::create([
                    'user_id' => $userId,
                    'languages_spoken' => ['id'],
                    'bio_id' => 'Bio dalam Bahasa Indonesia',
                    'bio_en' => 'Bio in English',
                    'rating_cache' => 4.80,
                    'is_available' => 1
                ]);
            }
            
            AuditLogger::logAction('user_created_by_admin', "User baru dibuat oleh admin: $email (ID: $userId)");
            flash('success', 'User berhasil dibuat.');
            redirect('/admin/users');
        } else {
            flash('error', 'Gagal membuat user.');
            redirect('/admin/users/create');
        }
    }

    public function edit($id) {
        RoleCheck::requireRole(['admin']);
        $user = UserModel::find($id);
        if (!$user) {
            http_response_code(404);
            view('errors.404');
            exit;
        }
        view('users.edit_users', ['user' => $user]);
    }

    public function update($id) {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $user = UserModel::find($id);
        if (!$user) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'traveler';
        $phone = $_POST['phone'] ?? '';

        if (empty($name) || empty($email)) {
            flash('error', 'Nama dan email wajib diisi.');
            redirect("/admin/users/edit/$id");
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'phone' => $phone
        ];

        if (!empty($password)) {
            $data['password'] = $password;
        }

        UserModel::update($id, $data);

        if ($role === 'guide') {
            TourGuideModel::findByUserId($id); // Triggers creation of tour_guides row if missing
        }

        AuditLogger::logAction('user_updated_by_admin', "User diperbarui oleh admin: $email (ID: $id)");
        flash('success', 'User berhasil diperbarui.');
        redirect('/admin/users');
    }

    public function delete($id) {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $user = UserModel::find($id);
        if ($user) {
            // Cegah hapus diri sendiri
            if ($user['id'] == $_SESSION['user_id']) {
                flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            } else {
                UserModel::delete($id);
                AuditLogger::logAction('user_deleted_by_admin', "User dihapus oleh admin: {$user['email']} (ID: $id)");
                flash('success', 'User berhasil dihapus.');
            }
        } else {
            flash('error', 'User tidak ditemukan.');
        }
        redirect('/admin/users');
    }

    // 2. PROFILE EDIT ACTION (GENERAL FOR LOGGED-IN USERS)
    
    public function editProfile() {
        RoleCheck::requireRole(['admin', 'agent', 'guide', 'traveler']);
        
        $currUser = auth();
        $user = UserModel::find($currUser->id);
        
        $guideDetail = null;
        if ($user['role'] === 'guide') {
            $guideDetail = TourGuideModel::findByUserId($user['id']);
        }
        
        view('users.edit_profile', [
            'user' => $user,
            'guideDetail' => $guideDetail
        ]);
    }

    public function updateProfile() {
        RoleCheck::requireRole(['admin', 'agent', 'guide', 'traveler']);
        Csrf::validate();

        $currUser = auth();
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($name)) {
            flash('error', 'Nama wajib diisi.');
            redirect('/profile/edit');
        }

        $data = [
            'name' => $name,
            'phone' => $phone
        ];

        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Avatar upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['avatar']['tmp_name'];
            $filename = basename($_FILES['avatar']['name']);
            $size = $_FILES['avatar']['size'];
            $mime = mime_content_type($tmp);

            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 1 * 1024 * 1024; // 1 MB
            
            if (in_array($mime, $allowedMimes) && $size <= $maxSize) {
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $avatarName = 'avatar_' . $currUser->id . '_' . time() . '.' . $ext;
                    $uploadDir = __DIR__ . '/../../storage/uploads/';
                    if (move_uploaded_file($tmp, $uploadDir . $avatarName)) {
                        $data['avatar'] = $avatarName;
                    }
                }
            } else {
                flash('error', 'Format avatar tidak valid atau ukuran melebih 1MB.');
                redirect('/profile/edit');
            }
        }

        UserModel::update($currUser->id, $data);
        
        // Update user_name session
        $_SESSION['user_name'] = $name;

        // Jika user adalah guide, simpan detail bio/sertifikasi guide
        if ($currUser->role === 'guide') {
            $guide = TourGuideModel::findByUserId($currUser->id);
            if ($guide) {
                $languages = $_POST['languages'] ?? ['id'];
                $bioId = $_POST['bio_id'] ?? '';
                $bioEn = $_POST['bio_en'] ?? '';
                $skillsId = $_POST['skills_id'] ?? '';
                $skillsEn = $_POST['skills_en'] ?? '';
                $license = $_POST['license_number'] ?? '';

                TourGuideModel::update($guide['id'], [
                    'license_number' => $license,
                    'languages_spoken' => $languages,
                    'bio_id' => $bioId,
                    'bio_en' => $bioEn,
                    'skills_id' => $skillsId,
                    'skills_en' => $skillsEn
                ]);
            }
        }

        AuditLogger::logAction('profile_updated', "User {$currUser->email} memperbarui profil.");
        flash('success', 'Profil berhasil diperbarui.');
        redirect('/profile/edit');
    }
}
