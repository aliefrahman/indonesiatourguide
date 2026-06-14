<?php
// File: app/Controllers/CategoryController.php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Middleware\RoleCheck;
use App\Core\Csrf;
use App\Core\AuditLogger;

class CategoryController {

    public function index() {
        RoleCheck::requireRole(['admin']);
        $categories = CategoryModel::getAll();
        view('category.categories', ['categories' => $categories]);
    }

    public function create() {
        RoleCheck::requireRole(['admin']);
        view('category.create_category');
    }

    public function store() {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $slug = $_POST['slug'] ?? '';
        $nameId = $_POST['name_id'] ?? '';
        $nameEn = $_POST['name_en'] ?? '';
        $icon = $_POST['icon'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($slug) || empty($nameId) || empty($nameEn)) {
            flash('error', 'Slug, Nama ID, dan Nama EN wajib diisi.');
            redirect('/categories/create');
        }

        // Cek slug duplikat
        if (CategoryModel::findBySlug($slug)) {
            flash('error', 'Slug kategori tersebut sudah terdaftar.');
            redirect('/categories/create');
        }

        $catId = CategoryModel::create([
            'slug' => $slug,
            'name_id' => $nameId,
            'name_en' => $nameEn,
            'icon' => $icon,
            'description_id' => $descId,
            'description_en' => $descEn,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        if ($catId) {
            AuditLogger::logAction('category_created', "Kategori baru dibuat: $nameId (ID: $catId)");
            flash('success', 'Kategori berhasil ditambahkan.');
            redirect('/categories');
        } else {
            flash('error', 'Gagal menambahkan kategori.');
            redirect('/categories/create');
        }
    }

    public function edit($id) {
        RoleCheck::requireRole(['admin']);
        $category = CategoryModel::find($id);
        if (!$category) {
            http_response_code(404);
            view('errors.404');
            exit;
        }
        view('category.edit_category', ['category' => $category]);
    }

    public function update($id) {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $category = CategoryModel::find($id);
        if (!$category) {
            http_response_code(404);
            view('errors.404');
            exit;
        }

        $slug = $_POST['slug'] ?? '';
        $nameId = $_POST['name_id'] ?? '';
        $nameEn = $_POST['name_en'] ?? '';
        $icon = $_POST['icon'] ?? '';
        $descId = $_POST['description_id'] ?? '';
        $descEn = $_POST['description_en'] ?? '';
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($slug) || empty($nameId) || empty($nameEn)) {
            flash('error', 'Slug, Nama ID, dan Nama EN wajib diisi.');
            redirect("/categories/edit/$id");
        }

        CategoryModel::update($id, [
            'slug' => $slug,
            'name_id' => $nameId,
            'name_en' => $nameEn,
            'icon' => $icon,
            'description_id' => $descId,
            'description_en' => $descEn,
            'sort_order' => $sortOrder,
            'is_active' => $isActive
        ]);

        AuditLogger::logAction('category_updated', "Kategori diperbarui: $nameId (ID: $id)");
        flash('success', 'Kategori berhasil diperbarui.');
        redirect('/categories');
    }

    public function delete($id) {
        RoleCheck::requireRole(['admin']);
        Csrf::validate();

        $category = CategoryModel::find($id);
        if ($category) {
            CategoryModel::delete($id);
            AuditLogger::logAction('category_deleted', "Kategori dihapus: {$category['name_id']} (ID: $id)");
            flash('success', 'Kategori berhasil dihapus.');
        } else {
            flash('error', 'Kategori tidak ditemukan.');
        }
        redirect('/categories');
    }
}
