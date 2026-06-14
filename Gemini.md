# Gemini.md - Spesifikasi Teknis & Panduan Pengembangan Arsitektur

Dokumen ini berfungsi sebagai cetak biru teknis (_technical blueprint_) untuk tim pengembang dalam membangun platform **Indonesian Tour Guide** berdasarkan **PRD Versi 1.3**. Dokumen ini dirancang dengan fokus utama pada efisiensi performa, kemudahan pemeliharaan, dan optimalisasi sumber daya agar dapat berjalan secara maksimal pada lingkungan **Shared Hosting**.

---

## 1. Konteks Proyek (Project Context)

Sektor pariwisata Indonesia memiliki potensi besar yang tersebar di berbagai daerah. Namun, banyak operator lokal dan pemandu wisata (_freelance_) kesulitan menjangkau pasar digital secara mandiri.

Platform **Indonesian Tour Guide** hadir sebagai solusi inklusif yang mendigitalisasi ekosistem pariwisata lokal. Untuk memastikan keberlanjutan operasional awal tanpa membebani biaya modal (_CapEx_), sistem ini dirancang agar **sistem ini sangat efisien secara arsitektural**, memungkinkan platform berjalan stabil di atas infrastruktur ekonomis seperti **Shared Hosting**, namun tetap mempertahankan skalabilitas, keamanan tingkat tinggi, dan pengalaman pengguna (_UX_) kelas premium.

---

## 2. Gambaran Umum Proyek (Project Overview)

Platform ini adalah web aplikasi monolitik yang mengintegrasikan tiga aktor utama dalam satu ekosistem:

1. **Wisatawan (Traveler):** Antarmuka berbasis _Mobile-First_ untuk mencari destinasi terdekat menggunakan layanan berbasis lokasi (LBS), melihat _itinerary_ interaktif harian, memilih pemandu wisata, melihat galeri foto & lightbox destinasi, dan melakukan pembayaran instan yang aman.
2. **Pemandu Wisata (Guide):** Sistem manajemen jadwal mandiri (kalender kerja), sinkronisasi portofolio publik, serta modul komunikasi (Chat/Call) langsung dengan konsumen.
3. **Admin / Operator Travel:** Panel kendali terpusat (_Back-Office_) untuk analitik dashboard, mengelola alokasi logistik, penugasan (_assigning_) SDM pemandu wisata, pengawasan arus keuangan, moderasi ulasan pelanggan (CRM), serta manajemen CRUD Tour (Paket Wisata), Kategori, dan Destinasi.
4. **Pengunjung (Visitor):** Pengunjung yang belum login dapat melihat detail paket wisata, destinasi, dan pemandu wisata.
5. **agent (Partner):** Agent Travel dapat mengelola paket wisata dan destinasi, serta melihat pesanan yang masuk. Agent tidak dapat melihat data pemandu wisata.

---

## 3. Tech Stack (Spesifikasi Teknologi & Kebutuhan Teknis)

Sesuai dengan pedoman arsitektur PRD, pemilihan teknologi didasarkan pada prinsip _low-overhead_, efisiensi memori ekstrim, dan kemudahan deployment tanpa mengorbankan modernitas kode.

| Kategori               | Spesifikasi / Implementasi                                                                                                                                                      |
| :--------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Arsitektur Sistem**  | Web-based Application dengan antarmuka responsif, optimal untuk perangkat mobile (wisatawan) dan desktop (admin/operator).                                                      |
| **Backend**            | **PHP 8.3+ Native MVC** (tanpa framework berat, namun tetap mengikuti MVC dan best practise seperti Laravel, CodeIgniter, dsb.), dioptimalkan agar _bootstrapping time_ < 30ms. |
| **Database**           | **MySQL 8.0+ / MariaDB 10.4+**, InnoDB, dioptimalkan dengan skema datar (_flat schema_) dan wajib menggunakan **PDO Prepared Statements**.                                      |
| **Frontend UI**        | **Tailwind CSS v4** (dikompilasi lokal) & **Vanilla JS** (ES6+). Zero Node.js _dependency_ di server produksi.                                                                  |
| **Performa**           | Respons pencarian dan transaksi pemesanan < 2 detik. Konsumsi memori PHP dibatasi < 16MB per request.                                                                           |
| **Penyimpanan Gambar** | Disimpan pada folder `storage/uploads`. Multi-upload cover image disimpan sebagai string JSON array pada kolom `cover_image` VARCHAR(255) yang ada.                             |
| **Reorganisasi Views** | Views dipisahkan berdasarkan folder fitur/modul (`kategori`, `tour`, `destinasi`, `user`) untuk kemudahan maintenance.                                                          |
| **Keamanan (8 Lapis)** | RBAC (Role-Based Access Control), arsitektur Zero-Trust, tabel audit log, proteksi token CSRF, _Rate Limiting_ Native, pencegahan SQL Injection (via PDO), pencegahan XSS (via Context-Aware Output Escaping), dan pengamanan RCE via restriksi direktori Upload.                  |
| **Session Storage**    | Disimpan di dalam lokal direktori `storage/sessions/` yang tidak terjangkau publik untuk menghindari masalah `permission denied` pada shared hosting, dilengkapi mekanisme _Regenerate ID_ guna menangkal _Session Fixation_.                           |
| **API Eksternal**      | LeafletJS + OpenStreetMap (untuk LBS), Midtrans/Xendit (Payment Gateway).                                                                                                       |
| **Sinkronisasi Data**  | Smart Sync (_offline-to-online_) dari SQLite lokal ke MariaDB jarak jauh memanfaatkan logika `REPLACE INTO` untuk mencegah konflik integritas ganda (slug swapping).            |

---

## 4. Struktur Direktori (Directory Structure)

Struktur proyek mengadopsi standar arsitektur industri dengan pemisahan kepentingan (_Separation of Concerns_) yang ketat, mengamankan kode dari paparan publik.

```text
indonesiatourguide/
├── app/
│   ├── Core/                 # Pustaka Inti Sistem (Framework Kustom & Security)
│   │   ├── App.php           # Inisialisasi aplikasi (< 30ms boot time)
|   |   ├── Controllers/      # Kelas Induk Pengendali (Base Controller)
│   │   ├── Database.php      # Wrapper PDO Singleton
│   │   ├── Security.php      # CSRF Generator & Rate Limiter
│   │   ├── Csrf.php          # Engine Token CSRF dinamis
│   │   ├── AuditLogger.php   # Logging aktivitas untuk 6 lapis keamanan
│   │   ├── helpers.php       # Helper function untuk kemudahan pengembangan
│   │   ├── Model.php         # Model class untuk kemudahan pengembangan
│   │   ├── RateLimiter.php   # Rate Limiter untuk kemudahan pengembangan
│   │   └── Router.php        # Routing engine berbasis regex
│   ├── Controllers/          # Lapisan Pengendali Logika Bisnis (Admin, Auth, Guide, Traveler)
│   ├── Models/               # Lapisan Akses Data & Representasi Tabel (PDO Entities)
│   └── Middleware/           # Lapisan Filter Keamanan (RBAC, Zero-Trust check)
├── config/                   # Berkas Konfigurasi Global (.env lokal)
├── public/                   # Satu-satunya folder yang terekspos ke internet (Document Root)
│   ├── assets/               # Aset produksi terkompresi
│   │   ├── css/              # Berkas app.css Tailwind v4 yang dikompilasi dan input.css
│   │   └── js/               # Berkas JS modular
│   ├── .htaccess             # Aturan URL rewriting Apache & proteksi direktori
│   └── index.php             # Front Controller (Pintu masuk tunggal seluruh request HTTP)
├── resources/                # Direktori Sumber Daya
│   └── views/                # Lapisan Presentasi (Native PHP Templating Engine)
|       ├── dashboard/        # Modul Dashboard Admin, Agent, Traveler, Guide (dashboard.php)
│       ├── category/         # Modul CRUD Categories (categories.php, create_category.php, edit_category.php, delete_category.php)
│       ├── auth/             # Modul Auth (login.php, register.php, forgot.php, reset.php, verify.php)
│       ├── home/             # Halaman Utama Publik (home.php)
│       ├── tour/             # Modul CRUD Tour & Detail (tour.php, create_tour.php, edit_tour.php, delete_tour.php, package_detail.php, checkout.php, booking_success.php)
│       ├── destinations/     # Modul CRUD Destinations (destinations.php, create_destinations.php, edit_destinations.php, delete_destinations.php, view_destinations.php)
│       ├── users/            # Modul Akun users (users.php, create_users.php,edit_users.php,delete_users.php, edit_profile.php)
│       ├── errors/           # Halaman error custom (403.php, 404.php)
│       └── layouts/          # Header, footer(header.php, footer.php)
└── storage/                  # Folder penyimpanan internal server (TIDAK BOLEH DIAKSES PUBLIK)
    ├── logs/                 # Catatan kesalahan aplikasi (error.log)
    ├── sessions/             # Penyimpanan sesi lokal (Sesuai spesifikasi PRD)
    └── uploads/              # Penyimpanan dinamis berkas pengguna (dan cover images destinasi/paket)
```

---

## 5. Aturan Pengembangan Wajib (Anti-Constraint Breaking)

Semua pengembang yang berkontribusi dalam proyek ini wajib mematuhi aturan berikut demi menjaga aplikasi tetap berjalan optimal pada server shared hosting:

### Larangan Deep Joins (Maksimal 2 Level)

Dilarang keras menulis query SQL yang melibatkan lebih dari dua operasi JOIN dalam satu baris eksekusi untuk entitas transaksi harian. Informasi yang sering diakses bersama harus di-denormalisasi ke dalam tabel induk (misalnya: nama paket wisata dan harga saat transaksi disalin langsung ke dalam tabel rincian bookings).

### Kompilasi Aset Sisi Lokal (Zero Node.js di Produksi)

Server produksi shared hosting tidak boleh menjalankan perintah npm run dev atau npm run build. Semua utilitas gaya Tailwind CSS v4 wajib dikompilasi secara lokal. Hanya file public/assets/css/app.css matang yang boleh diunggah ke server.

### Isolasi Direktori Inti (Keamanan cPanel)

Seluruh kode aplikasi dalam direktori app, config, dan views harus diletakkan satu tingkat di atas folder publik server (public_html atau www). Hanya folder public/ yang dipindahkan ke dalam public_html. Hal ini mencegah berkas rahasia bocor ke publik.

### Optimasi Memori Kueri (Strict Chunking)

Saat menarik data Laporan, dilarang menggunakan metode kueri instan (fetchAll() tanpa batasan) yang memuat seluruh baris ke RAM sekaligus (maks 16MB per request). Pengambilan data massal wajib menggunakan teknik iterasi sebaris (fetch() di dalam loop) atau memecah kueri menggunakan LIMIT dan OFFSET.

### Pengelolaan Sesi Mandiri (Secure Session Storage)

Sesi PHP (\_SESSION) tidak boleh mengandalkan path temp bawaan server (seperti /tmp). Path wajib diarahkan secara eksplisit ke direktori storage/sessions/ menggunakan perintah session_save_path() pada fase bootstrapping aplikasi.

---

## 6. Data Model & Schema (Schema Datar Dwibahasa)

Skema database relasional telah dioptimalkan secara mendatar (flat) untuk menghindari beban join query yang memicu CPU throttling. Tabel audit_logs ditambahkan untuk memenuhi syarat "6 lapis keamanan".

```sql

-- 1. Tabel Users (Entitas Utama Autentikasi & Multi-Role/RBAC)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent', 'guide', 'traveler') NOT NULL DEFAULT 'traveler',
    phone VARCHAR(20) NULL,
    avatar VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Tour Guides (Profil & Manajemen Jadwal)
CREATE TABLE tour_guides (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    license_number VARCHAR(50) NULL,
    languages_spoken JSON NOT NULL,
    bio_id TEXT NOT NULL,
    bio_en TEXT NOT NULL,
    skills_id TEXT NULL,
    skills_en TEXT NULL,
    rating_cache DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    is_available TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_guide_availability (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Tour Packages (Inventaris Paket Wisata Utama)
CREATE TABLE tour_packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title_id VARCHAR(255) NOT NULL,
    title_en VARCHAR(255) NOT NULL,
    description_id TEXT NOT NULL,
    description_en TEXT NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    duration_days INT NOT NULL DEFAULT 1,
    location_name VARCHAR(150) NOT NULL,
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    cover_image VARCHAR(255) NOT NULL, -- Menyimpan nama berkas cover utama
    category VARCHAR(100) NOT NULL DEFAULT 'adventure',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_package_location (location_name),
    INDEX idx_package_coordinates (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel Itineraries (Jadwal Rencana Perjalanan Harian)
CREATE TABLE itineraries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id BIGINT UNSIGNED NOT NULL,
    day_number INT NOT NULL DEFAULT 1,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    activity_id VARCHAR(255) NOT NULL,
    activity_en VARCHAR(255) NOT NULL,
    notes_id TEXT NULL,
    notes_en TEXT NULL,
    FOREIGN KEY (package_id) REFERENCES tour_packages(id) ON DELETE CASCADE,
    INDEX idx_itinerary_day (package_id, day_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Bookings (Transaksi & Manajemen Alokasi Pesanan)
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    traveler_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NOT NULL,
    guide_id BIGINT UNSIGNED NULL,
    package_name_snapshot VARCHAR(255) NOT NULL, -- Denormalisasi (Rule #1)
    package_price_snapshot DECIMAL(12,2) NOT NULL, -- Denormalisasi (Rule #1)
    travel_date DATE NOT NULL,
    total_participants INT NOT NULL DEFAULT 1,
    total_price DECIMAL(12,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    payment_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (traveler_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (package_id) REFERENCES tour_packages(id) ON DELETE RESTRICT,
    FOREIGN KEY (guide_id) REFERENCES tour_guides(id) ON DELETE SET NULL,
    INDEX idx_booking_invoice (invoice_number),
    INDEX idx_booking_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel Reviews (Sistem Ulasan CRM & Moderasi)
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NULL,
    is_moderated TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_review_moderation (is_moderated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tabel Tour Categories (Master Kategori Paket Wisata)
CREATE TABLE tour_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,        -- Nilai yang tersimpan di tour_packages.category
    name_id VARCHAR(150) NOT NULL,            -- Nama dalam Bahasa Indonesia
    name_en VARCHAR(150) NOT NULL,            -- Nama dalam Bahasa Inggris
    icon VARCHAR(20) NULL,                    -- Emoji/Icon identifier
    description_id TEXT NULL,
    description_en TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category_slug (slug),
    INDEX idx_category_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data: Tour Categories
INSERT INTO tour_categories (slug, name_id, name_en, icon, description_id, description_en, sort_order) VALUES
('adventure', 'Gunung & Trekking', 'Adventure & Trekking', '🧗', 'Paket wisata pendakian gunung, trekking, dan kegiatan alam bebas lainnya.', 'Mountain climbing, trekking, and outdoor adventure packages.', 1),
('beach', 'Pantai & Pulau', 'Beach & Islands', '🏖️', 'Paket wisata menikmati keindahan pantai dan pulau-pulau eksotis Indonesia.', 'Beach relaxation and island-hopping packages across Indonesia.', 2),
('heritage', 'Budaya & Candi', 'Cultural & Heritage', '⛩️', 'Paket wisata mengunjungi situs budaya, candi bersejarah, dan tradisi lokal.', 'Cultural visits, ancient temples, and local heritage experiences.', 3),
('diving', 'Selam & Snorkeling', 'Diving & Snorkeling', '🤿', 'Paket wisata bawah laut — diving, snorkeling, dan eksplorasi terumbu karang.', 'Underwater adventures — diving, snorkeling, and coral reef exploration.', 4);

-- 8. Tabel Destinations (Master Data Destinasi Wisata)
CREATE TABLE destinations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_id VARCHAR(200) NOT NULL,            -- Nama destinasi dalam Bahasa Indonesia
    name_en VARCHAR(200) NOT NULL,            -- Nama destinasi dalam Bahasa Inggris
    slug VARCHAR(200) UNIQUE NOT NULL,        -- URL-friendly identifier (e.g., 'raja-ampat')
    province VARCHAR(150) NOT NULL,           -- Provinsi (e.g., 'Papua Barat')
    region VARCHAR(150) NULL,                 -- Wilayah/Kabupaten (opsional)
    description_id TEXT NULL,
    description_en TEXT NULL,
    cover_image VARCHAR(255) NULL,            -- Menyimpan 1 s.d 5 gambar sebagai JSON Array (misal: '["img1.jpg", "img2.jpg"]')
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0, -- Tampil di section "Top Destinations"
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_destination_slug (slug),
    INDEX idx_destination_featured (is_featured),
    INDEX idx_destination_coordinates (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Data: Destinations
INSERT INTO destinations (name_id, name_en, slug, province, description_id, description_en, cover_image, latitude, longitude, is_featured, sort_order) VALUES
('Gunung Bromo', 'Mount Bromo', 'gunung-bromo', 'Jawa Timur', 'Gunung berapi aktif yang terkenal dengan pemandangan sunrise dan lautan pasir berbisik.', 'Active volcano famous for its stunning sunrise views and the Sea of Sand.', 'bromo.jpg', -7.942493, 112.953012, 1, 1),
('Bali', 'Bali', 'bali', 'Bali', 'Pulau dewata dengan keindahan budaya, pura, pantai, dan alam yang memukau dunia.', 'Island of Gods with rich culture, temples, pristine beaches, and breathtaking nature.', 'bali.jpg', -8.409518, 115.188919, 1, 2),
('Raja Ampat', 'Raja Ampat', 'raja-ampat', 'Papua Barat', 'Surga bawah laut dengan keragaman hayati terumbu karang tertinggi di dunia.', 'Underwater paradise with the world''s highest marine biodiversity and coral reefs.', 'rajaampat.jpg', -0.224158, 130.490076, 1, 3),
('Labuan Bajo', 'Labuan Bajo', 'labuan-bajo', 'Nusa Tenggara NTT', 'Kota pelabuhan pintu gerbang menuju Taman Nasional Komodo dan Pulau Padar.', 'Gateway port city to Komodo National Park and Padar Island.', 'labuanbajo.jpg', -8.499700, 119.889900, 1, 4),
('Danau Toba', 'Lake Toba', 'danau-toba', 'Sumatera Utara', 'Danau vulkanik terbesar di dunia dengan budaya Batak dan keindahan alam yang menakjubkan.', 'World''s largest volcanic lake with Batak culture and stunning natural scenery.', 'tobatoba.jpg', 2.683400, 98.874700, 1, 5),
('Yogyakarta', 'Yogyakarta', 'yogyakarta', 'Daerah Istimewa Yogyakarta', 'Kota budaya dengan Candi Borobudur, Candi Prambanan, Keraton, dan seni tradisional Jawa.', 'Cultural capital with Borobudur Temple, Prambanan Temple, Royal Palace, and Javanese arts.', 'yogyakarta.jpg', -7.797068, 110.370529, 1, 6);

```

---

## 7. Catatan Implementasi Modul Kategori & Destinasi

### Alur Integrasi Kategori (`tour_categories`)

- Kolom `category` pada tabel `tour_packages` menyimpan nilai **slug** (`adventure`, `beach`, `heritage`, `diving`) yang merujuk ke tabel `tour_categories`.
- Pendekatan ini menggunakan **soft reference** (tanpa FOREIGN KEY) agar tidak memperumit query harian pada shared hosting.
- Penyaringan berdasarkan kategori dilakukan via `TourPackageModel::getFiltered($category, ...)` yang membangun klausa `WHERE category = ?` secara aman dengan PDO Prepared Statements.
- Tabel master `tour_categories` digunakan untuk keperluan manajemen kategori di panel Admin secara dinamis.

### Alur Integrasi Destinasi (`destinations`) & Multi-Upload Cover

- Tabel `destinations` adalah entitas **master data** independen yang menggunakan `location_name` sebagai _soft reference_ berbasis nama.
- Kolom `cover_image` bertipe `VARCHAR(255)` menyimpan array JSON yang berisi maksimal 5 gambar (misal: `["filename_1.jpg", "filename_2.jpg"]`).
- **Backward Compatibility (Kompatibilitas Mundur):**
  Aplikasi secara otomatis mendeteksi dan menangani data lama yang masih berupa string teks biasa (`bromo.jpg`) dengan membungkusnya menjadi array satu-elemen (`['bromo.jpg']`) saat dibaca dari database:
  ```php
  $images = json_decode($coverField, true);
  if (!is_array($images)) {
      $images = !empty($coverField) ? [$coverField] : [];
  }
  ```
- **Halaman Detail Traveler (`destination_detail.php`):**
  - Mengambil index ke-0 sebagai gambar cover utama untuk hero banner.
  - Menampilkan seksi **📸 Galeri Foto** dinamis jika destinasi tersebut memiliki lebih dari 1 gambar.
  - Dilengkapi fitur **Lightbox Modal** premium dengan background glassmorphism (`backdrop-blur-md`). Event klik diatur agar modal hanya tertutup bila mengklik tombol Close (`✕`) atau area kosong di luar gambar.

### Aturan Query Wajib

- **Dilarang** melakukan JOIN `tour_packages → tour_categories` secara berulang. Ambil data kategori terpisah lalu lakukan mapping di layer PHP.
- Pencarian destinasi harus menggunakan index `idx_destination_featured` (untuk halaman utama) dan `idx_destination_slug` (untuk halaman detail).

---

## 8. Panduan Restrukturisasi Views

Untuk menjaga kerapian kode dan mempermudah proses pemeliharaan, seluruh file presentasi (views) yang semula bercampur di folder controller lama dipisahkan ke dalam folder modular di bawah `resources/views/`:

---

## 9. Sistem Keamanan CSRF Terintegrasi

Untuk mencegah serangan _Cross-Site Request Forgery_ (CSRF) yang menyebabkan kesalahan fatal sesi kedaluwarsa:

1. **Dinamis CSRF Field Generator:**
   Setiap form submission bertipe `POST` wajib menggunakan field dinamis:
   ```php
   <?php echo \App\Core\Csrf::field(); ?>
   ```
2. **Standardisasi Nama Field:**
   Validator pada `Controller::requireCsrf()` dan `Csrf::validate()` mendeteksi token dengan nama kunci `_csrf_token`.
3. **Pemberitahuan User Friendly:**
   Jika token tidak valid atau kedaluwarsa, aplikasi akan mengalihkan kembali pengguna disertai pesan flash error: `"Sesi tidak valid atau kedaluwarsa. Silakan coba lagi."`.

---

## 10. Penyesuaian Lingkungan Produksi (cPanel / Shared Hosting)

Untuk memastikan aplikasi berjalan dengan stabil, aman, dan tanpa celah keamanan pada lingkungan produksi _shared hosting_, berikut adalah arsitektur pelengkap yang telah diimplementasikan:

### 10.1. Lapisan Keamanan `.htaccess` (Apache)
Sistem menggunakan keamanan berlapis di level web server (Apache) dengan menempatkan konfigurasi `.htaccess` di _root_ direktori dan di dalam `/public`.
- **Mencegah Directory Listing:** Opsi `Options -Indexes` diaktifkan untuk melarang _web server_ mengembalikan daftar file jika `index.php` tidak ditemukan.
- **Blokir Akses Folder Inti:** Aturan `RewriteRule ^(app|config|resources|storage)/ - [F,L]` memastikan penyerang tidak dapat mengeksekusi script PHP langsung di dalam folder internal.
- **Blokir File Rahasia:** Menggunakan direktif `<FilesMatch>` untuk memberikan status _403 Forbidden_ terhadap file `.env`, `.gitignore`, `package.json`, dan dokumentasi `.md`.

### 10.2. Kompatibilitas Parser `.env` dan MySQL
Parser `.env` mandiri pada `helpers.php` telah dirancang untuk menghindari bentrokan saat developer menaruh komentar *inline* (seperti `DB_USERNAME=root # admin`), sehingga koneksi PDO MySQL dapat dieksekusi dengan *string* kredensial yang bersih.

### 10.3. Kompatibilitas Versi PHP 8
- Menyediakan *Polyfill* secara *hardcode* di `helpers.php` (seperti `str_starts_with`, `str_ends_with`, dan `str_contains`) untuk menangani kompatibilitas fungsi _string_ pada lingkungan _shared hosting_ yang belum mutakhir sepenuhnya ke PHP 8+.
- Memperbaiki alur _routing_ pada `Router.php` untuk memisahkan inisiasi instans _controller_ dari fallback pemanggilan statis (`is_callable`) guna mencegah *deprecated warnings*.

### 10.4. Konfigurasi Mode Produksi (`index.php`)
File _entry point_ (`public/index.php`) secara tegas mengatur `ini_set('display_errors', 0)` agar tidak membocorkan masalah teknis (warnings/notices) ke pengunjung (_end-users_). Error hanya dicatat (`logged`) ke _error_log_ internal server.
