<?php
// File: scratch/seed_mockup_data.php
$db = new PDO("sqlite:" . __DIR__ . "/../storage/tour_guide_db.sqlite");

// 1. Re-create / insert missing categories
$db->query("DELETE FROM tour_categories");
$categories = [
    ['adventure', 'Petualangan', 'Adventure', '🧗', 'Paket wisata pendakian gunung, trekking, dan kegiatan alam bebas lainnya.', 'Mountain climbing, trekking, and outdoor adventure packages.', 1],
    ['beach', 'Pantai & Pulau', 'Beach & Islands', '🏖️', 'Paket wisata menikmati keindahan pantai dan pulau-pulau eksotis Indonesia.', 'Beach relaxation and island-hopping packages across Indonesia.', 2],
    ['heritage', 'Budaya & Heritage', 'Culture & Heritage', '⛩️', 'Paket wisata mengunjungi situs budaya, candi bersejarah, dan tradisi lokal.', 'Cultural visits, ancient temples, and local heritage experiences.', 3],
    ['diving', 'Selam & Snorkeling', 'Diving & Snorkeling', '🤿', 'Paket wisata bawah laut — diving, snorkeling, dan eksplorasi terumbu karang.', 'Underwater adventures — diving, snorkeling, and coral reef exploration.', 4],
    ['nature', 'Satwa & Alam', 'Nature & Wildlife', '🌴', 'Paket wisata alam liar dan margasatwa.', 'Wildlife and nature exploration packages.', 5],
    ['trekking', 'Trekking & Hiking', 'Trekking & Hiking', '🥾', 'Paket petualangan jalan kaki dan mendaki.', 'Hiking and trekking adventure packages.', 6],
];

$stmtCat = $db->prepare("INSERT INTO tour_categories (slug, name_id, name_en, icon, description_id, description_en, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
foreach ($categories as $cat) {
    $stmtCat->execute($cat);
}

// 2. Re-create / insert 6 tour packages matching the mockup
$db->query("DELETE FROM tour_packages");
$packages = [
    [
        1,
        'Eksplorasi Sunrise Bromo Indah',
        'Bromo Beautiful Sunrise Exploration',
        'Nikmati keindahan sunrise Gunung Bromo dengan mengendarai Jeep 4x4 melewati lautan pasir.',
        'Enjoy the beauty of Mount Bromo sunrise by riding a 4x4 Jeep through the sea of sand.',
        7200000.00, // $450
        1,
        'East Java',
        -7.942493,
        112.953012,
        '["dest_1781204676_4051.jpg"]', // Bromo sunrise
        'adventure'
    ],
    [
        2,
        'Tour Budaya Candi Borobudur & Prambanan',
        'Borobudur & Prambanan Cultural Tour',
        'Jelajahi dua candi bersejarah warisan dunia UNESCO di Yogyakarta dalam satu hari penuh.',
        'Explore two UNESCO world heritage ancient temples in Yogyakarta in one full day.',
        4000000.00, // $250
        1,
        'Yogyakarta',
        -7.797068,
        110.370529,
        '["pkg_1781207477_114.jpg"]', // Borobudur temple
        'heritage'
    ],
    [
        3,
        'Surga Bawah Laut Nusa Penida Bali',
        'Nusa Penida Bali Undersea Paradise',
        'Snorkeling di Crystal Bay dan Manta Point, serta mengunjungi Kelingking Beach yang ikonik.',
        'Snorkeling at Crystal Bay and Manta Point, and visiting the iconic Kelingking Beach.',
        1360000.00, // $85
        1,
        'Bali',
        -8.409518,
        115.188919,
        '["dest_1781207219_8120.jpg"]', // Turquoise ocean water
        'diving'
    ],
    [
        4,
        'Petualangan Raja Ampat - 4 Hari',
        'Raja Ampat Adventure Tour - 4 Days',
        'Jelajahi keindahan gugusan kepulauan Raja Ampat dan nikmati surga bawah laut dunia.',
        'Explore the breathtaking Raja Ampat archipelago and experience the ultimate marine paradise.',
        23200000.00, // $1450
        4,
        'West Papua',
        -0.224158,
        130.490076,
        '["dest_1781205614_5301.jpg"]', // Bright blue Raja Ampat ocean
        'beach'
    ],
    [
        5,
        'Liburan Mewah Bali & Villa Pribadi',
        'Bali Luxury Escape & Private Villa',
        'Menginap di villa mewah privat, spa tradisional, dan makan malam romantis di pinggir pantai.',
        'Stay at private luxury villas, experience traditional Balinese spa, and fine dining by the beach.',
        17920000.00, // $1120
        5,
        'Bali',
        -8.409518,
        115.188919,
        '["dest_1781205614_2729.jpg"]', // Sunset pool
        'nature'
    ],
    [
        6,
        'Komodo Dragon & Pink Beach - 3 Hari',
        'Komodo Dragon & Pink Beach - 3 Days',
        'Melihat hewan purba komodo secara langsung dan menikmati keindahan pantai merah muda.',
        'Encounter the legendary Komodo dragons in their habitat and relax on the exotic Pink Beach.',
        5600000.00, // $350
        3,
        'East Nusa Tenggara',
        -8.499700,
        119.889900,
        '["dest_1781207148_7203.jpg"]', // Komodo dragon
        'trekking'
    ]
];

$stmtPkg = $db->prepare("INSERT INTO tour_packages (id, title_id, title_en, description_id, description_en, price, duration_days, location_name, latitude, longitude, cover_image, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($packages as $pkg) {
    $stmtPkg->execute($pkg);
}

// 3. Re-create / insert 4 destinations matching the mockup row of 4: Bali, Komodo Island, Lombok, Raja Ampat
$db->query("DELETE FROM destinations");
$destinations = [
    [1, 'Bali', 'Bali', 'bali', 'Bali', 'Pulau Dewata.', 'Island of Gods.', '["dest_1781205701_5597.jpg"]', -8.409518, 115.188919, 1, 1], // Bali Temple
    [2, 'Pulau Komodo', 'Komodo Island', 'komodo-island', 'NTT', 'Habitat Komodo.', 'Komodo habitat.', '["dest_1781207148_7203.jpg"]', -8.499700, 119.889900, 1, 2], // Komodo Dragon
    [3, 'Lombok', 'Lombok', 'lombok', 'NTB', 'Keindahan Lombok.', 'Lombok beauty.', '["dest_1781207219_8120.jpg"]', -8.650000, 116.350000, 1, 3], // Lombok turquoise water
    [4, 'Raja Ampat', 'Raja Ampat', 'raja-ampat', 'Papua Barat', 'Keindahan bawah laut.', 'Underwater beauty.', '["dest_1781205614_5301.jpg"]', -0.224158, 130.490076, 1, 4] // Raja Ampat ocean
];

$stmtDest = $db->prepare("INSERT INTO destinations (id, name_id, name_en, slug, province, description_id, description_en, cover_image, latitude, longitude, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($destinations as $dest) {
    $stmtDest->execute($dest);
}

echo "Database successfully updated with correct color-coded image mappings!\n";
