# Indonesia Tour Guide

![Indonesia Tour Guide Banner](https://via.placeholder.com/1200x400.png?text=Indonesia+Tour+Guide)

Platform digital terintegrasi yang menghubungkan wisatawan dengan pemandu lokal terbaik untuk petualangan tak terlupakan di seluruh Indonesia. Proyek ini dibangun dengan mempertimbangkan kecepatan, keamanan, dan kompatibilitas penuh dengan lingkungan _Shared Hosting_ (seperti cPanel).

---

## 🚀 Fitur Utama

- **Pemesanan Paket Wisata & Destinasi**: Sistem booking paket wisata lengkap dengan _itinerary_, penugasan _tour guide_, dan pengelolaan harga yang dinamis.
- **Manajemen Multi-Role Terpusat**: Mendukung 4 role pengguna:
  - **Traveler**: Mengelola profil, riwayat pemesanan, dan ulasan.
  - **Guide**: Memperbarui status ketersediaan (_availability_), lisensi, kemampuan bahasa, dan spesialisasi tur.
  - **Agent**: Mengelola destinasi, kategori wisata, dan paket tur b2b.
  - **Admin**: Akses penuh ke master data, manajemen pengguna (CRUD), audit log, dan moderasi ulasan.
- **Dukungan Multi-Bahasa**: Terjemahan _on-the-fly_ (Bahasa Indonesia & English) tanpa mengorbankan performa database.
- **Arsitektur Ringan (Lightweight MVC)**: Dibangun murni menggunakan PHP 8+ Native (OOP MVC) tanpa _framework backend_ yang berat, sehingga _bootstrapping time_ sangat cepat.
- **UI Modern & Interaktif**: Menggunakan **Tailwind CSS v4** (dikompilasi lokal) dengan _Vanilla JavaScript_, tanpa dependensi Node.js di sisi server produksi. Fitur antarmuka meliputi _Glassmorphism_, _sticky headers_, dan integrasi ikon _Lucide_.
- **Keamanan Lapis Baja**:
  - Validasi _Cross-Site Request Forgery (CSRF)_ terintegrasi untuk form POST.
  - Pencegahan _SQL Injection_ menggunakan metode _PDO Prepared Statements_.
  - Pemblokiran _Directory Listing_ secara global dan proteksi _folder_ esensial di level Apache (`.htaccess`).
  - Native _Rate Limiting_ (Login & Lupa Password).

---

## 💻 Tech Stack

- **Backend**: PHP 8.3+ (Disesuaikan agar kompatibel penuh (_backward-compatible_) hingga PHP 8.0 berkat _polyfills_ mandiri).
- **Database**: MySQL 8.0+ / MariaDB (Dukungan SQLite siap pakai untuk mode pengujian lokal cepat).
- **Frontend CSS**: Tailwind CSS v4 (Light Theme Customization).
- **Frontend JS**: Vanilla JavaScript ES6+.
- **Icons**: Lucide Icons.

---

## 🛠️ Instalasi & Setup (Lokal)

Jika Anda ingin menjalankan atau mengembangkan proyek ini di lingkungan komputer lokal Anda:

1. **Clone Repositori**

   ```bash
   git clone https://github.com/username/indonesiatourguide.git
   cd indonesiatourguide
   ```

2. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`.

   ```bash
   cp .env.example .env
   ```

   Buka file `.env` dan atur konfigurasi database.
   _Catatan penting: Jangan menaruh komentar inline (`#`) berdampingan di baris yang sama dengan pengaturan password/username._

   Jika Anda belum memiliki server MySQL aktif, Anda cukup mengubah pengaturan ke SQLite:

   ```env
   DB_DRIVER=sqlite
   ```

   Aplikasi akan secara otomatis menggunakan database lokal `storage/tour_guide_db.sqlite`.

3. **Jalankan Server PHP**
   Anda bisa menggunakan server bawaan PHP untuk _development_:
   ```bash
   php -S localhost:8000 -t public/
   ```
   Lalu buka browser Anda di `http://localhost:8000`.

---

## 🌍 Panduan Deployment (cPanel)

Aplikasi ini sangat dioptimalkan untuk diunggah ke CPanel. Silakan baca langkah-langkah detailnya pada file pelengkap **[DEPLOYMENT_CPANEL.md](./DEPLOYMENT_CPANEL.md)**.

**Ringkasan Deployment**:

1. Buat database MySQL di cPanel.
2. Unggah isi folder `public/` ke dalam folder `public_html/`.
3. Letakkan direktori inti (seperti `app/`, `config/`, `.env`) **di luar** _public_html_ (sejajar dengan _public_html_) demi keamanan maksimal.
4. Sesuaikan konstanta `__DIR__` di file `index.php` yang sudah berada di `public_html` agar mengarah dengan benar ke direktori inti aplikasi.
5. Impor file struktur `tour_guide_db.sql` ke database Anda melalui phpMyAdmin.

---

## 🔒 Catatan Keamanan

Sistem keamanan dalam aplikasi menggunakan kebijakan _Zero-Trust_ ringan. Secara bawaan, kami mengatur `display_errors = 0` pada `public/index.php` agar kesalahan (_Fatal Errors/Warnings_) di level produksi tidak terlihat oleh pengguna (_end-user_). Kesalahan akan dicatat (_logged_) pada file `error_log` bawaan server Anda.

---

## 👨‍💻 Project Leads

- **Ziaur Rahman**
- **Andi Muhammad Aliefrahman**

---
