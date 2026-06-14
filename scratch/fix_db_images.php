<?php
// File: scratch/fix_db_images.php
$db = new PDO("sqlite:" . __DIR__ . "/../storage/tour_guide_db.sqlite");

// Update destinations
$db->query("UPDATE destinations SET cover_image = '[\"dest_1781205303_1320.jpg\"]' WHERE slug = 'bali'");
$db->query("UPDATE destinations SET cover_image = '[\"dest_1781205614_2729.jpg\"]' WHERE slug = 'raja-ampat'");
$db->query("UPDATE destinations SET cover_image = '[\"dest_1781205701_3529.jpg\"]' WHERE slug = 'labuan-bajo'");
$db->query("UPDATE destinations SET cover_image = '[\"dest_1781207148_7203.jpg\"]' WHERE slug = 'danau-toba'");
$db->query("UPDATE destinations SET cover_image = '[\"dest_1781207219_3080.jpg\"]' WHERE slug = 'yogyakarta'");

// Update tour packages
$db->query("UPDATE tour_packages SET cover_image = '[\"pkg_1781205420_651.jpg\"]' WHERE id = 1");
$db->query("UPDATE tour_packages SET cover_image = '[\"dest_1781207293_4229.jpg\"]' WHERE id = 2");
$db->query("UPDATE tour_packages SET cover_image = '[\"pkg_1781207477_114.jpg\"]' WHERE id = 3");

echo "Database cover images updated successfully!\n";
