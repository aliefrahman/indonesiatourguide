# Changelog

Semua perubahan yang signifikan pada proyek ini akan didokumentasikan dalam file ini.

## [Unreleased]

### Added
- **Modul Autentikasi & Akun**: Menambahkan fitur `Lupa Password` (`forgot.php`), `Reset Password` (`reset.php`), Login, dan Registrasi dengan implementasi anti-CSRF dan Rate Limiting.
- **Manajemen Pengguna (CRUD Users)**: Admin dapat membuat, mengubah, melihat, dan menghapus data pengguna (Traveler, Guide, Agent, Admin).
- **Edit Profil (`edit_profile.php`)**: Semua pengguna yang masuk dapat memperbarui nama, nomor telepon, dan kata sandi. Pengguna dengan role `guide` dapat secara spesifik mengatur lisensi, bahasa, bio, dan keahlian (`skills`).
- **Pembaruan Role**: Menambahkan dukungan formal untuk role `agent` pada sistem dan Middleware (`RoleCheck`).

### Changed
- **Pembaruan Skema Database (`users`)**: Mengubah kolom `role` dengan tipe ENUM untuk mendukung nilai `'admin', 'agent', 'guide', 'traveler'`.
- **Dokumentasi (`prd.md` & `Gemini.md`)**: Memperbarui spesifikasi teknis dan kebutuhan fitur agar sesuai dengan implementasi terkini (penambahan fitur CRUD pengguna dan role agent).

### Fixed (Production & Security)
- **Koneksi Database & Lingkungan**: Mengatasi bug koneksi MySQL yang terganggu oleh komentar _inline_ pada parser file `.env`.
- **Kompatibilitas PHP**: Menambahkan _polyfill_ untuk fungsi string PHP 8.0 (`str_starts_with`, `str_ends_with`, `str_contains`) di `helpers.php` guna mendukung PHP versi lebih lama pada _shared hosting_.
- **Peringatan *Deprecated* Router**: Memperbaiki logika instansiasi di `Router.php` untuk menghindari pesan _deprecated static call_ di PHP 8.x.
- **Keamanan Direktori**: Menambahkan konfigurasi `.htaccess` berlapis di _root_ proyek dan `public/.htaccess` untuk mematikan _directory listing_ secara global serta memblokir akses langsung ke folder internal (app, config, storage) dan file konfigurasi.
- **Mode Produksi & Penanganan Error**: Menonaktifkan pengaturan `display_errors` terbuka secara permanen dengan membungkusnya pada logika deteksi `APP_ENV = production` di `public/index.php`.
- **Perbaikan UI**: Melakukan perbaikan tata letak minor pada `header.php` dan `footer.php`.

### Added (Smart Sync & Security Patch)
- **Fitur Smart Sync (SQLite ke MySQL)**: Menambahkan tombol "Sync Database" di halaman Dashboard Admin (`DashboardController.php`) yang memungkinkan admin menarik data dari database SQLite ke MySQL menggunakan metode `REPLACE INTO` untuk menangkal konflik duplikasi unik (_slug swapping_).
- **Audit & Penambalan Keamanan Lanjut (OWASP)**:
  - **Mencegah Session Fixation**: Menambahkan `session_regenerate_id(true)` pada saat proses otentikasi login berhasil.
  - **Mencegah XSS via Output Escaping**: Menghapus metode `htmlspecialchars` global di komponen Model (membiarkan data tersimpan utuh) lalu mengubah lebih dari 30 file _View_ untuk mencetak data dengan perlindungan context-aware menggunakan fungsi helper `e()`.
  - **Mencegah Remote Code Execution (RCE) via File Upload**: Semua _controller_ pengunggah gambar (User, Tour, Destination) sekarang memvalidasi secara ketat dengan `mime_content_type()`, memberlakukan batasan berat file (1MB-2MB), serta membentengi direktori unggahan `storage/uploads/` menggunakan kontrol `.htaccess` (`php_flag engine off`).
