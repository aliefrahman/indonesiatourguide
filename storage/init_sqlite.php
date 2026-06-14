<?php
// File: storage/init_sqlite.php

$dbPath = __DIR__ . '/tour_guide_db.sqlite';
echo "Menginisialisasi database SQLite di: $dbPath\n";

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hapus tabel lama jika ada untuk inisialisasi ulang
    $tables = ['audit_logs', 'reviews', 'bookings', 'itineraries', 'tour_packages', 'tour_guides', 'users', 'tour_categories', 'destinations'];
    foreach ($tables as $t) {
        $pdo->exec("DROP TABLE IF EXISTS $t");
    }

    echo "Tabel lama berhasil dibersihkan.\n";

    // 1. Users
    $pdo->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT CHECK(role IN ('admin', 'agent', 'guide', 'traveler')) NOT NULL DEFAULT 'traveler',
        phone TEXT NULL,
        avatar TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Tour Guides
    $pdo->exec("CREATE TABLE tour_guides (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        license_number TEXT NULL,
        languages_spoken TEXT NOT NULL,
        bio_id TEXT NOT NULL,
        bio_en TEXT NOT NULL,
        skills_id TEXT NULL,
        skills_en TEXT NULL,
        rating_cache REAL NOT NULL DEFAULT 0.00,
        is_available INTEGER NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // 3. Tour Packages
    $pdo->exec("CREATE TABLE tour_packages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title_id TEXT NOT NULL,
        title_en TEXT NOT NULL,
        description_id TEXT NOT NULL,
        description_en TEXT NOT NULL,
        price REAL NOT NULL,
        duration_days INTEGER NOT NULL DEFAULT 1,
        location_name TEXT NOT NULL,
        latitude REAL NULL,
        longitude REAL NULL,
        cover_image TEXT NOT NULL,
        category TEXT NOT NULL DEFAULT 'adventure',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. Itineraries
    $pdo->exec("CREATE TABLE itineraries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        package_id INTEGER NOT NULL,
        day_number INTEGER NOT NULL DEFAULT 1,
        time_start TEXT NOT NULL,
        time_end TEXT NOT NULL,
        activity_id TEXT NOT NULL,
        activity_en TEXT NOT NULL,
        notes_id TEXT NULL,
        notes_en TEXT NULL,
        FOREIGN KEY (package_id) REFERENCES tour_packages(id) ON DELETE CASCADE
    )");

    // 5. Bookings
    $pdo->exec("CREATE TABLE bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_number TEXT UNIQUE NOT NULL,
        traveler_id INTEGER NOT NULL,
        package_id INTEGER NOT NULL,
        guide_id INTEGER NULL,
        package_name_snapshot TEXT NOT NULL,
        package_price_snapshot REAL NOT NULL,
        travel_date TEXT NOT NULL,
        total_participants INTEGER NOT NULL DEFAULT 1,
        total_price REAL NOT NULL,
        payment_status TEXT CHECK(payment_status IN ('pending', 'paid', 'failed', 'refunded')) NOT NULL DEFAULT 'pending',
        payment_token TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (traveler_id) REFERENCES users(id) ON DELETE RESTRICT,
        FOREIGN KEY (package_id) REFERENCES tour_packages(id) ON DELETE RESTRICT,
        FOREIGN KEY (guide_id) REFERENCES tour_guides(id) ON DELETE SET NULL
    )");

    // 6. Reviews
    $pdo->exec("CREATE TABLE reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        booking_id INTEGER NOT NULL,
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
        comment TEXT NULL,
        is_moderated INTEGER NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )");

    // 7. Tour Categories
    $pdo->exec("CREATE TABLE tour_categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE NOT NULL,
        name_id TEXT NOT NULL,
        name_en TEXT NOT NULL,
        icon TEXT NULL,
        description_id TEXT NULL,
        description_en TEXT NULL,
        sort_order INTEGER NOT NULL DEFAULT 0,
        is_active INTEGER NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 8. Destinations
    $pdo->exec("CREATE TABLE destinations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name_id TEXT NOT NULL,
        name_en TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        province TEXT NOT NULL,
        region TEXT NULL,
        description_id TEXT NULL,
        description_en TEXT NULL,
        cover_image TEXT NULL,
        latitude REAL NULL,
        longitude REAL NULL,
        is_featured INTEGER NOT NULL DEFAULT 0,
        is_active INTEGER NOT NULL DEFAULT 1,
        sort_order INTEGER NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 9. Audit Logs
    $pdo->exec("CREATE TABLE audit_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NULL,
        action_type TEXT NOT NULL,
        ip_address TEXT NOT NULL,
        user_agent TEXT NOT NULL,
        description TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    echo "Tabel database SQLite berhasil dibuat.\n";

    // --- SEED DATA ---
    // Tour Categories
    $pdo->exec("INSERT INTO tour_categories (slug, name_id, name_en, icon, description_id, description_en, sort_order) VALUES
    ('adventure', 'Gunung & Trekking', 'Adventure & Trekking', '🧗', 'Paket wisata pendakian gunung, trekking, dan kegiatan alam bebas lainnya.', 'Mountain climbing, trekking, and outdoor adventure packages.', 1),
    ('beach', 'Pantai & Pulau', 'Beach & Islands', '🏖️', 'Paket wisata menikmati keindahan pantai dan pulau-pulau eksotis Indonesia.', 'Beach relaxation and island-hopping packages across Indonesia.', 2),
    ('heritage', 'Budaya & Candi', 'Cultural & Heritage', '⛩️', 'Paket wisata mengunjungi situs budaya, candi bersejarah, dan tradisi lokal.', 'Cultural visits, ancient temples, and local heritage experiences.', 3),
    ('diving', 'Selam & Snorkeling', 'Diving & Snorkeling', '🤿', 'Paket wisata bawah laut — diving, snorkeling, dan eksplorasi terumbu karang.', 'Underwater adventures — diving, snorkeling, and coral reef exploration.', 4)");

    // Destinations
    $pdo->exec("INSERT INTO destinations (name_id, name_en, slug, province, description_id, description_en, cover_image, latitude, longitude, is_featured, sort_order) VALUES
    ('Gunung Bromo', 'Mount Bromo', 'gunung-bromo', 'Jawa Timur', 'Gunung berapi aktif yang terkenal dengan pemandangan sunrise dan lautan pasir berbisik.', 'Active volcano famous for its stunning sunrise views and the Sea of Sand.', '[\"bromo.jpg\"]', -7.942493, 112.953012, 1, 1),
    ('Bali', 'Bali', 'bali', 'Bali', 'Pulau dewata dengan keindahan budaya, pura, pantai, dan alam yang memukau dunia.', 'Island of Gods with rich culture, temples, pristine beaches, and breathtaking nature.', '[\"bali.jpg\"]', -8.409518, 115.188919, 1, 2),
    ('Raja Ampat', 'Raja Ampat', 'raja-ampat', 'Papua Barat', 'Surga bawah laut dengan keragaman hayati terumbu karang tertinggi di dunia.', 'Underwater paradise with the world''s highest marine biodiversity and coral reefs.', '[\"rajaampat.jpg\"]', -0.224158, 130.490076, 1, 3),
    ('Labuan Bajo', 'Labuan Bajo', 'labuan-bajo', 'Nusa Tenggara NTT', 'Kota pelabuhan pintu gerbang menuju Taman Nasional Komodo dan Pulau Padar.', 'Gateway port city to Komodo National Park and Padar Island.', '[\"labuanbajo.jpg\"]', -8.499700, 119.889900, 1, 4),
    ('Danau Toba', 'Lake Toba', 'danau-toba', 'Sumatera Utara', 'Danau vulkanik terbesar di dunia dengan budaya Batak dan keindahan alam yang menakjubkan.', 'World''s largest volcanic lake with Batak culture and stunning natural scenery.', '[\"tobatoba.jpg\"]', 2.683400, 98.874700, 1, 5),
    ('Yogyakarta', 'Yogyakarta', 'yogyakarta', 'Daerah Istimewa Yogyakarta', 'Kota budaya dengan Candi Borobudur, Candi Prambanan, Keraton, dan seni tradisional Jawa.', 'Cultural capital with Borobudur Temple, Prambanan Temple, Royal Palace, and Javanese arts.', '[\"yogyakarta.jpg\"]', -7.797068, 110.370529, 1, 6)");

    // Users (passwords are "password" hashed with BCRYPT)
    $pdo->exec("INSERT INTO users (id, name, email, password, role, phone, avatar) VALUES
    (1, 'Admin Tour', 'admin@test.com', '\$2y\$10\$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'admin', '081234567890', NULL),
    (2, 'Travel Agent Partner', 'agent@test.com', '\$2y\$10\$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'agent', '081234567891', NULL),
    (3, 'Wayan Guide', 'guide@test.com', '\$2y\$10\$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'guide', '081234567892', NULL),
    (4, 'Budi Traveler', 'traveler@test.com', '\$2y\$10\$bjNfrqImSmxhjaK3Deruk.OByU0lOq4qFQ9NIuj3jXSPXDHpX5Fa.', 'traveler', '081234567893', NULL)");

    // Tour Guides
    $pdo->exec("INSERT INTO tour_guides (id, user_id, license_number, languages_spoken, bio_id, bio_en, skills_id, skills_en, rating_cache, is_available) VALUES
    (1, 3, 'LIC-98231', '[\"id\", \"en\"]', 'Pemandu wisata berpengalaman di Bali dan Jawa Timur, ahli sejarah lokal.', 'Experienced tour guide in Bali and East Java, local history expert.', 'Pendakian, Snorkeling, Fotografi', 'Trekking, Snorkeling, Photography', 4.80, 1)");

    // Tour Packages
    $pdo->exec("INSERT INTO tour_packages (id, title_id, title_en, description_id, description_en, price, duration_days, location_name, latitude, longitude, cover_image, category) VALUES
    (1, 'Eksplorasi Sunrise Bromo Indah', 'Bromo Beautiful Sunrise Exploration', 'Nikmati keindahan sunrise Gunung Bromo dengan mengendarai Jeep 4x4 melewati lautan pasir.', 'Enjoy the beauty of Mount Bromo sunrise by riding a 4x4 Jeep through the sea of sand.', 750000.00, 1, 'Gunung Bromo', -7.942493, 112.953012, '[\"bromo_pkg.jpg\"]', 'adventure'),
    (2, 'Tour Budaya Candi Borobudur & Prambanan', 'Borobudur & Prambanan Cultural Tour', 'Jelajahi dua candi bersejarah warisan dunia UNESCO di Yogyakarta dalam satu hari penuh.', 'Explore two UNESCO world heritage ancient temples in Yogyakarta in one full day.', 600000.00, 1, 'Yogyakarta', -7.797068, 110.370529, '[\"temple_pkg.jpg\"]', 'heritage'),
    (3, 'Surga Bawah Laut Nusa Penida Bali', 'Nusa Penida Bali Undersea Paradise', 'Snorkeling di Crystal Bay dan Manta Point, serta mengunjungi Kelingking Beach yang ikonik.', 'Snorkeling at Crystal Bay and Manta Point, and visiting the iconic Kelingking Beach.', 950000.00, 1, 'Bali', -8.409518, 115.188919, '[\"beach_pkg.jpg\"]', 'diving')");

    // Itineraries
    $pdo->exec("INSERT INTO itineraries (package_id, day_number, time_start, time_end, activity_id, activity_en, notes_id, notes_en) VALUES
    (1, 1, '03:00:00', '04:00:00', 'Penjemputan dengan Jeep di Meeting Point', 'Jeep pickup at Meeting Point', 'Harap memakai jaket tebal.', 'Please wear a warm jacket.'),
    (1, 1, '04:00:00', '06:00:00', 'Menikmati Sunrise di Penanjakan', 'Enjoying Sunrise at Penanjakan', 'Bawa kamera untuk momen terbaik.', 'Bring a camera for the best moments.'),
    (1, 1, '06:30:00', '09:00:00', 'Eksplorasi Kawah Bromo & Pasir Berbisik', 'Exploring Bromo Crater & Sea of Sand', 'Bisa sewa kuda jika lelah.', 'Can rent a horse if tired.'),
    (2, 1, '08:00:00', '11:30:00', 'Kunjungan Candi Borobudur Megah', 'Majestic Borobudur Temple Visit', 'Pemandu lokal menceritakan sejarah relief.', 'Local guide explains relief history.'),
    (2, 1, '12:00:00', '13:30:00', 'Makan Siang Khas Jogja', 'Traditional Jogja Lunch', 'Makan siang di restoran lokal.', 'Lunch at local restaurant.'),
    (2, 1, '14:00:00', '17:00:00', 'Eksplorasi Kompleks Candi Prambanan', 'Exploring Prambanan Temple Complex', 'Candi Hindu tercantik di Indonesia.', 'Most beautiful Hindu temple in Indonesia.'),
    (3, 1, '07:30:00', '08:30:00', 'Menyeberang ke Nusa Penida dengan Speedboat', 'Cross to Nusa Penida by Speedboat', 'Berkumpul di Pelabuhan Sanur.', 'Gather at Sanur Port.'),
    (3, 1, '09:00:00', '12:00:00', 'Snorkeling di Manta Point & Crystal Bay', 'Snorkeling at Manta Point & Crystal Bay', 'Melihat ikan Manta pari raksasa.', 'Spot the giant Manta rays.'),
    (3, 1, '13:00:00', '15:30:00', 'Mengunjungi Tebing Kelingking Beach', 'Visiting Kelingking Beach Cliff', 'Spot foto dinosaurus terkenal.', 'Famous dinosaur-shaped photo spot.')");

    echo "Data seed SQLite berhasil dimasukkan.\n";
    
    // Set izin agar web server bisa menulis ke file database
    chmod($dbPath, 0666);
    
    echo "Selesai! Database SQLite siap digunakan.\n";

} catch (PDOException $e) {
    die("Error inisialisasi SQLite: " . $e->getMessage() . "\n");
}
