-- Indonesian Tour Guide DB Schema
-- Versi 1.3 - 11 Juni 2026

CREATE DATABASE IF NOT EXISTS tour_guide_db;
USE tour_guide_db;

-- 1. Tabel Users (Entitas Utama Autentikasi & Multi-Role/RBAC)
CREATE TABLE IF NOT EXISTS users (
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
CREATE TABLE IF NOT EXISTS tour_guides (
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
CREATE TABLE IF NOT EXISTS tour_packages (
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
CREATE TABLE IF NOT EXISTS itineraries (
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
CREATE TABLE IF NOT EXISTS bookings (
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
CREATE TABLE IF NOT EXISTS reviews (
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
CREATE TABLE IF NOT EXISTS tour_categories (
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

-- 8. Tabel Destinations (Master Data Destinasi Wisata)
CREATE TABLE IF NOT EXISTS destinations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_id VARCHAR(200) NOT NULL,
    name_en VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    province VARCHAR(150) NOT NULL,
    region VARCHAR(150) NULL,
    description_id TEXT NULL,
    description_en TEXT NULL,
    cover_image VARCHAR(255) NULL,            -- Menyimpan 1 s.d 5 gambar
    latitude DECIMAL(10,8) NULL,
    longitude DECIMAL(11,8) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_destination_slug (slug),
    INDEX idx_destination_featured (is_featured),
    INDEX idx_destination_coordinates (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel Audit Logs (Pencatatan Aktivitas Keamanan & Lapis 5 Keamanan)
CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action_type VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_audit_user (user_id),
    INDEX idx_audit_action (action_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEED DATA
-- Tour Categories
INSERT INTO tour_categories (slug, name_id, name_en, icon, description_id, description_en, sort_order) VALUES
('adventure', 'Gunung & Trekking', 'Adventure & Trekking', '🧗', 'Paket wisata pendakian gunung, trekking, dan kegiatan alam bebas lainnya.', 'Mountain climbing, trekking, and outdoor adventure packages.', 1),
('beach', 'Pantai & Pulau', 'Beach & Islands', '🏖️', 'Paket wisata menikmati keindahan pantai dan pulau-pulau eksotis Indonesia.', 'Beach relaxation and island-hopping packages across Indonesia.', 2),
('heritage', 'Budaya & Candi', 'Cultural & Heritage', '⛩️', 'Paket wisata mengunjungi situs budaya, candi bersejarah, dan tradisi lokal.', 'Cultural visits, ancient temples, and local heritage experiences.', 3),
('diving', 'Selam & Snorkeling', 'Diving & Snorkeling', '🤿', 'Paket wisata bawah laut — diving, snorkeling, dan eksplorasi terumbu karang.', 'Underwater adventures — diving, snorkeling, and coral reef exploration.', 4)
ON DUPLICATE KEY UPDATE name_id=VALUES(name_id), name_en=VALUES(name_en), icon=VALUES(icon);

-- Destinations
INSERT INTO destinations (name_id, name_en, slug, province, description_id, description_en, cover_image, latitude, longitude, is_featured, sort_order) VALUES
('Gunung Bromo', 'Mount Bromo', 'gunung-bromo', 'Jawa Timur', 'Gunung berapi aktif yang terkenal dengan pemandangan sunrise dan lautan pasir berbisik.', 'Active volcano famous for its stunning sunrise views and the Sea of Sand.', '["bromo.jpg"]', -7.942493, 112.953012, 1, 1),
('Bali', 'Bali', 'bali', 'Bali', 'Pulau dewata dengan keindahan budaya, pura, pantai, dan alam yang memukau dunia.', 'Island of Gods with rich culture, temples, pristine beaches, and breathtaking nature.', '["bali.jpg"]', -8.409518, 115.188919, 1, 2),
('Raja Ampat', 'Raja Ampat', 'raja-ampat', 'Papua Barat', 'Surga bawah laut dengan keragaman hayati terumbu karang tertinggi di dunia.', 'Underwater paradise with the world''s highest marine biodiversity and coral reefs.', '["rajaampat.jpg"]', -0.224158, 130.490076, 1, 3),
('Labuan Bajo', 'Labuan Bajo', 'labuan-bajo', 'Nusa Tenggara NTT', 'Kota pelabuhan pintu gerbang menuju Taman Nasional Komodo dan Pulau Padar.', 'Gateway port city to Komodo National Park and Padar Island.', '["labuanbajo.jpg"]', -8.499700, 119.889900, 1, 4),
('Danau Toba', 'Danau Toba', 'danau-toba', 'Sumatera Utara', 'Danau vulkanik terbesar di dunia dengan budaya Batak dan keindahan alam yang menakjubkan.', 'World''s largest volcanic lake with Batak culture and stunning natural scenery.', '["tobatoba.jpg"]', 2.683400, 98.874700, 1, 5),
('Yogyakarta', 'Yogyakarta', 'yogyakarta', 'Daerah Istimewa Yogyakarta', 'Kota budaya dengan Candi Borobudur, Candi Prambanan, Keraton, dan seni tradisional Jawa.', 'Cultural capital with Borobudur Temple, Prambanan Temple, Royal Palace, and Javanese arts.', '["yogyakarta.jpg"]', -7.797068, 110.370529, 1, 6)
ON DUPLICATE KEY UPDATE name_id=VALUES(name_id), name_en=VALUES(name_en), province=VALUES(province);

-- Seed users (passwords are "password" hashed with BCRYPT)
-- admin: admin@test.com
-- agent: agent@test.com
-- guide: guide@test.com
-- traveler: traveler@test.com
INSERT INTO users (id, name, email, password, role, phone, avatar) VALUES
(1, 'Admin Tour', 'admin@test.com', '$2y$10$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'admin', '081234567890', NULL),
(2, 'Travel Agent Partner', 'agent@test.com', '$2y$10$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'agent', '081234567891', NULL),
(3, 'Wayan Guide', 'guide@test.com', '$2y$10$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'guide', '081234567892', NULL),
(4, 'Budi Traveler', 'traveler@test.com', '$2y$10$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'traveler', '081234567893', NULL)
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- Seed Tour Guide detail
INSERT INTO tour_guides (id, user_id, license_number, languages_spoken, bio_id, bio_en, skills_id, skills_en, rating_cache, is_available) VALUES
(1, 3, 'LIC-98231', '["id", "en"]', 'Pemandu wisata berpengalaman di Bali dan Jawa Timur, ahli sejarah lokal.', 'Experienced tour guide in Bali and East Java, local history expert.', 'Pendakian, Snorkeling, Fotografi', 'Trekking, Snorkeling, Photography', 4.80, 1)
ON DUPLICATE KEY UPDATE license_number=VALUES(license_number);

-- Seed Tour Package
INSERT INTO tour_packages (id, title_id, title_en, description_id, description_en, price, duration_days, location_name, latitude, longitude, cover_image, category) VALUES
(1, 'Eksplorasi Sunrise Bromo Indah', 'Bromo Beautiful Sunrise Exploration', 'Nikmati keindahan sunrise Gunung Bromo dengan mengendarai Jeep 4x4 melewati lautan pasir.', 'Enjoy the beauty of Mount Bromo sunrise by riding a 4x4 Jeep through the sea of sand.', 750000.00, 1, 'Gunung Bromo', -7.942493, 112.953012, '["bromo_pkg.jpg"]', 'adventure'),
(2, 'Tour Budaya Candi Borobudur & Prambanan', 'Borobudur & Prambanan Cultural Tour', 'Jelajahi dua candi bersejarah warisan dunia UNESCO di Yogyakarta dalam satu hari penuh.', 'Explore two UNESCO world heritage ancient temples in Yogyakarta in one full day.', 600000.00, 1, 'Yogyakarta', -7.797068, 110.370529, '["temple_pkg.jpg"]', 'heritage'),
(3, 'Surga Bawah Laut Nusa Penida Bali', 'Nusa Penida Bali Undersea Paradise', 'Snorkeling di Crystal Bay dan Manta Point, serta mengunjungi Kelingking Beach yang ikonik.', 'Snorkeling at Crystal Bay and Manta Point, and visiting the iconic Kelingking Beach.', 950000.00, 1, 'Bali', -8.409518, 115.188919, '["beach_pkg.jpg"]', 'diving')
ON DUPLICATE KEY UPDATE title_id=VALUES(title_id);

-- Seed Itineraries
INSERT INTO itineraries (package_id, day_number, time_start, time_end, activity_id, activity_en, notes_id, notes_en) VALUES
(1, 1, '03:00:00', '04:00:00', 'Penjemputan dengan Jeep di Meeting Point', 'Jeep pickup at Meeting Point', 'Harap memakai jaket tebal.', 'Please wear a warm jacket.'),
(1, 1, '04:00:00', '06:00:00', 'Menikmati Sunrise di Penanjakan', 'Enjoying Sunrise at Penanjakan', 'Bawa kamera untuk momen terbaik.', 'Bring a camera for the best moments.'),
(1, 1, '06:30:00', '09:00:00', 'Eksplorasi Kawah Bromo & Pasir Berbisik', 'Exploring Bromo Crater & Sea of Sand', 'Bisa sewa kuda jika lelah.', 'Can rent a horse if tired.'),
(2, 1, '08:00:00', '11:30:00', 'Kunjungan Candi Borobudur Megah', 'Majestic Borobudur Temple Visit', 'Pemandu lokal menceritakan sejarah relief.', 'Local guide explains relief history.'),
(2, 1, '12:00:00', '13:30:00', 'Makan Siang Khas Jogja', 'Traditional Jogja Lunch', 'Makan siang di restoran lokal.', 'Lunch at local restaurant.'),
(2, 1, '14:00:00', '17:00:00', 'Eksplorasi Kompleks Candi Prambanan', 'Exploring Prambanan Temple Complex', 'Candi Hindu tercantik di Indonesia.', 'Most beautiful Hindu temple in Indonesia.'),
(3, 1, '07:30:00', '08:30:00', 'Menyeberang ke Nusa Penida dengan Speedboat', 'Cross to Nusa Penida by Speedboat', 'Berkumpul di Pelabuhan Sanur.', 'Gather at Sanur Port.'),
(3, 1, '09:00:00', '12:00:00', 'Snorkeling di Manta Point & Crystal Bay', 'Snorkeling at Manta Point & Crystal Bay', 'Melihat ikan Manta pari raksasa.', 'Spot the giant Manta rays.'),
(3, 1, '13:00:00', '15:30:00', 'Mengunjungi Tebing Kelingking Beach', 'Visiting Kelingking Beach Cliff', 'Spot foto dinosaurus terkenal.', 'Famous dinosaur-shaped photo spot.')
ON DUPLICATE KEY UPDATE activity_id=VALUES(activity_id);
