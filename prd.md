# 📄 Product Requirements Document (PRD): Indonesian Tour Guide

- **Versi:** 1.3
- **Tanggal:** 11 Juni 2026
- **Pemilik Produk (Product Manager):**Ziaur Rahman, Andi Muhammad Aliefrahman
- **Status:** Aktif / Rilis
- **Tanggal Pembuatan:** 11 Juni 2026
- **Tanggal Update Terakhir:** 15 Juni 2026

---

## 1. Ringkasan Eksekutif (Executive Summary)

**Indonesian Tour Guide** adalah platform berbasis web yang dirancang untuk menjembatani wisatawan (domestik dan internasional) dengan pemandu wisata lokal serta operator travel di Indonesia. Platform ini menyederhanakan proses pencarian, pemesanan paket tur, dan pemilihan _tour guide_. Selain itu, platform ini berfungsi sebagai sistem manajemen terpusat (CMS/Dashboard) bagi admin dan operator untuk mengelola inventaris, logistik, dan sumber daya manusia secara efisien.

## 2. Tujuan & Sasaran Produk (Goals & Objectives)

- **Untuk Wisatawan:** Memberikan pengalaman pemesanan tur yang transparan, aman, dan mudah disesuaikan dengan kebutuhan, termasuk akses langsung ke pemandu wisata lokal.
- **Untuk Tour Guide:** Menyediakan platform untuk memasarkan jasa, membangun reputasi melalui ulasan, dan mengatur jadwal kerja secara profesional.
- **Untuk Operator Travel:** Meningkatkan efisiensi operasional melalui digitalisasi manajemen pesanan, penjadwalan SDM, dan pemantauan keuangan.

## 3. Target Pengguna (User Personas)

1. **Wisatawan (Traveler):** Turis lokal maupun mancanegara yang mencari pengalaman wisata autentik, paket liburan lengkap, atau jasa pemandu lepas.
2. **Pemandu Wisata (Tour Guide):** Pemandu lokal atau _freelance_ tersertifikasi maupun berpengalaman yang ingin mendapatkan klien dan mengelola jadwal mereka.
3. **Admin:** Pengelola utama platform yang mengontrol seluruh data, termasuk CRUD Users, moderasi, dan laporan.
4. **Agent (Partner):** Agen perjalanan atau operator travel yang dapat mengelola paket wisata dan destinasi, namun dengan akses yang lebih terbatas dibanding admin.

---

## 4. Ruang Lingkup Fitur (Feature Scope)

### 4.1. Modul Pengguna (Wisatawan)

| Nama Fitur                             | Deskripsi & Kebutuhan (Requirements)                                                                                                                                                                                          |
| -------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Pencarian & Pemesanan Terintegrasi** | Pengguna dapat mencari paket wisata berdasarkan destinasi, tanggal, dan jumlah peserta. Saat pemesanan, pengguna dapat menyertakan opsi transportasi, akomodasi, dan memilih _tour guide_ spesifik dari daftar yang tersedia. |
| **Jadwal & Itinerary Interaktif**      | Halaman detail paket menampilkan _timeline_ visual rencana perjalanan harian (jam kunjungan, nama aktivitas, waktu istirahat/makan).                                                                                          |
| **Integrasi Payment Gateway**          | Mendukung pembayaran multi-metode yang aman (Transfer Bank/Virtual Account, E-Wallet seperti GoPay/OVO, Kartu Kredit, dan QRIS).                                                                                              |
| **Pencarian Berbasis Lokasi (LBS)**    | Fitur "Near Me" yang menggunakan geolokasi browser untuk menampilkan destinasi, paket tur, atau _tour guide_ terdekat dari posisi pengguna saat ini.                                                                          |
| **Multi-bahasa & Multi-mata uang**     | Dukungan bahasa Indonesia dan Inggris (minimal), serta estimasi konversi mata uang untuk turis asing.                                                                                                                         |
| **Galeri Foto & Lightbox Destinasi**   | Halaman detail destinasi menampilkan galeri foto dinamis jika admin mengunggah lebih dari 1 gambar cover. Foto dapat dizoom dengan lightbox interaktif premium berlatar belakang glassmorphism.                               |

### 4.2. Modul Pemandu Wisata (Tour Guide)

| Nama Fitur                           | Deskripsi & Kebutuhan (Requirements)                                                                                                                       |
| ------------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Manajemen Jadwal (Calendar Sync)** | Dasbor bagi pemandu untuk memblokir tanggal saat mereka tidak tersedia (cuti/sudah dipesan di luar sistem) untuk mencegah _double booking_.                |
| **Profil & Portofolio Publik**       | Halaman profil yang memuat foto, bio, lisensi/sertifikasi, bahasa yang dikuasai, rating bintang, dan ulasan pelanggan.                                     |
| **In-App Chat & Call**               | Fitur pesan instan terenkripsi (dan tombol panggilan VoIP/Integrasi WhatsApp) agar guide dan wisatawan dapat berkomunikasi setelah pemesanan dikonfirmasi. |
| **Notifikasi Pekerjaan**             | Peringatan via email/push notification saat ada pesanan baru atau perubahan jadwal.                                                                        |

### 4.3. Modul Admin & Operator Travel

| Nama Fitur                              | Deskripsi & Kebutuhan (Requirements)                                                                                                                                        |
| --------------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Dashboard Utama (Analytics)**         | Pusat kontrol yang menampilkan metrik utama: total pendapatan, pesanan aktif, pesanan selesai, dan tingkat pembatalan.                                                      |
| **Manajemen Pengguna (CRUD Users)**     | Modul bagi admin untuk menambah, mengubah, menghapus, serta melihat daftar seluruh pengguna (termasuk penugasan role Admin, Agent, Guide, Traveler).                        |
| **Manajemen Paket Wisata (CRUD Tour)**  | Modul CRUD lengkap untuk menambah, mengubah, atau menghapus paket wisata, termasuk harga dinamis, lokasi geografis, cover image, dan manajemen itinerary harian interaktif. |
| **Manajemen Kategori Destinasi (CRUD)** | Modul CRUD untuk mengelola kategori destinasi & tur dengan kustomisasi ikon emoji, pengelolaan slug unik, dan visualisasi statistik jumlah paket wisata terdaftar.          |
| **Manajemen Destinasi & Multi-upload**  | Modul CRUD destinasi wisata dengan dukungan upload hingga 5 gambar cover sekaligus (disimpan sebagai JSON array pada database demi efisiensi resource shared hosting).      |
| **Manajemen SDM (Assigning System)**    | Alat untuk menugaskan _tour guide_ atau supir tertentu ke dalam suatu pesanan secara manual jika wisatawan tidak memilih _guide_ secara mandiri.                            |
| **Manajemen Ulasan & Pelanggan (CRM)**  | Tabel database pelanggan. Admin memiliki hak moderasi untuk membalas, menampilkan, atau menyembunyikan ulasan yang melanggar ketentuan.                                     |
| **Manajemen Transaksi (Finance)**       | Laporan rekonsiliasi pembayaran, status _refund_, dan pencairan dana (payout) ke pemandu wisata.                                                                            |

### 4.4. Modul Autentikasi & Akun

| Nama Fitur                        | Deskripsi & Kebutuhan (Requirements)                                                                                                                                       |
| --------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Registrasi & Login**            | Sistem masuk dan daftar yang diamankan dengan proteksi CSRF dan Rate Limiting native.                                                                                      |
| **Lupa & Reset Kata Sandi**       | Alur pemulihan akun melalui verifikasi (saat ini simulasi) untuk reset kata sandi dengan aman.                                                                             |
| **Edit Profil & Pengaturan Akun** | Pengguna dapat memperbarui nama, nomor telepon, mengganti foto profil (avatar), dan sandi. _Guide_ juga dapat memperbarui lisensi, bahasa, bio, dan skill secara spesifik. |

---

## 5. Kebutuhan Non-Fungsional (Non-Functional Requirements) & Teknis

Untuk memastikan performa, skalabilitas, dan kemudahan pemeliharaan (_maintenance_), berikut adalah pedoman arsitektur yang diterapkan:

| Kategori               | Spesifikasi / Implementasi                                                                                             |
| ---------------------- | ---------------------------------------------------------------------------------------------------------------------- |
| **Arsitektur Sistem**  | Web-based Application dengan interface responsif, optimal untuk perangkat mobile wisatawan dan desktop admin/operator. |
| **Backend**            | PHP 8.3+ Native MVC (tanpa framework berat), bootstrapping time < 30ms.                                                |
| **Database**           | MySQL 8.0+ / MariaDB 10.4+, InnoDB, flat schema, PDO Prepared Statements.                                              |
| **Frontend UI**        | Tailwind CSS v4 (dikompilasi lokal), Vanilla JS — zero Node.js di server produksi.                                     |
| **Penyimpanan Gambar** | cPanel / Shared hosting friendly. Multi-upload disimpan sebagai string JSON array pada kolom VARCHAR(255) yang ada.    |
| **Reorganisasi Views** | Views dipisahkan berdasarkan folder fitur/modul (`kategori`, `tour`, `destinasi`, `user`) untuk kemudahan maintenance. |
| **Performa**           | Respons pencarian dan transaksi pemesanan < 2 detik, konsumsi memori PHP < 16MB per request.                           |
| **Keamanan**           | 6 lapis keamanan (RBAC, Zero-Trust, Audit Log, CSRF Terintegrasi, Rate Limiting, SQL Injection Prevention).            |
| **Session Storage**    | `storage/sessions/` lokal (shared hosting safe, menghindari permission denied).                                        |

---

## 6. Alur Pengguna Utama (High-Level User Flow)

**Skenario: Pemesanan Tur dengan Guide**

1. Wisatawan membuka web -> Melakukan pencarian (mis. "Tur Bromo 2 Hari").
2. Sistem menampilkan hasil -> Wisatawan melihat detail _Itinerary_.
3. Wisatawan klik "Pesan Sekarang" -> Memilih opsi hotel dan memilih _Tour Guide_ dari daftar yang tersedia di tanggal tersebut.
4. Masuk ke halaman Checkout -> Memilih metode pembayaran (mis. QRIS via API Gateway).
5. Pembayaran berhasil -> Sistem mengirim notifikasi ke Admin dan _Tour Guide_ yang dipilih.
6. H-1 Keberangkatan -> Fitur _Chat_ terbuka, Wisatawan dan _Guide_ sepakat untuk bertemu di titik kumpul.

---

## 7. Kesiapan Produksi (Production Readiness & cPanel)

Aplikasi telah dirancang dan dikonfigurasi untuk siap berjalan di lingkungan *shared hosting* (seperti cPanel) dengan langkah-langkah keamanan dan kompatibilitas ekstra:

1. **Kompatibilitas PHP 8.x**: Memiliki *polyfills* mandiri di `helpers.php` (seperti `str_starts_with`) untuk mencegah *fatal error* pada versi PHP yang sedikit lebih lama, serta perbaikan *deprecated static calls* di `Router.php`.
2. **Kepatuhan OWASP Top 10 (Keamanan Ketat)**:
   - **XSS Prevention**: Perlindungan *Context-Aware Output Escaping* otomatis di seluruh lapis *Views* dengan bantuan fungsi helper `e()`.
   - **Session Fixation Prevention**: ID Sesi langsung diregenerasi setelah keberhasilan proses autentikasi (login).
   - **Secure File Uploads**: Perlindungan RCE ganda via pembatasan MIME Type aktual (bukan sekadar ekstensi), limit ukuran MB yang rasional, dan penyisipan `.htaccess` anti-eksekusi di folder unggahan `storage/uploads/`.
   - **Information Disclosure Prevention**: Konfigurasi `display_errors` dinonaktifkan sepenuhnya di `index.php` pada mode produksi berdasarkan deteksi variabel *Environment* `APP_ENV = production`.
3. **Keamanan Direktori Berbasis Apache (`.htaccess`)**: 
   - Direktori *root* dan folder `public/` dilindungi dari pengindeksan file (`Options -Indexes`).
   - Direktori sistem (`app/`, `config/`, `resources/`, `storage/`, `node_modules/`) diblokir dari akses langsung HTTP (Status `403 Forbidden`).
   - File kredensial (`.env`, `*.md`, `*.json`) diblokir secara eksplisit di level Apache.
4. **Parsing Konfigurasi `.env` Lebih Andal**: Logika pembaca `.env` bawaan memastikan string kredensial (seperti *username* database) dibersihkan dari komentar *inline*, mencegah kegagalan koneksi database.
5. **Smart Data Synchronization**: Terdapat fitur sinkronisasi *offline-to-online* yang menggunakan logika cerdas `REPLACE INTO` untuk menyatukan data *database* SQLite sementara (lokal) ke MariaDB produksi tanpa menimbulkan bentrokan kendala duplikat (seperti insiden _slug swapping_).
