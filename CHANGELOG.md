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
- **Mode Produksi**: Menonaktifkan pengaturan `display_errors` di `public/index.php` untuk menyembunyikan pesan _warning_ dan error teknis PHP dari layar pengunjung.
- **Perbaikan UI**: Melakukan perbaikan tata letak minor pada `header.php` dan `footer.php`.
