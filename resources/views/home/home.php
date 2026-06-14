<?php
// File: resources/views/home/home.php
$pageTitle = "Selamat Datang - Indonesian Tour Guide";
require __DIR__ . '/../layouts/header.php';
?>

<!-- ════════════════════════════════════════════════════════════════════
     HERO SECTION — Full-width dark overlay photo with search bar
═════════════════════════════════════════════════════════════════════ -->
<section class="relative min-h-[600px] sm:min-h-[680px] flex flex-col justify-center overflow-hidden">

    <!-- Background Photo -->
    <div class="absolute inset-0">
        <img src="/assets/images/hero_bali_temple.jpg" alt="Beautiful Indonesia"
            class="w-full h-full object-cover object-center" fetchpriority="high">
        <div class="hero-overlay absolute inset-0"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 sm:py-32 text-center">

        <!-- Label pill -->
        <div
            class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 backdrop-blur-sm px-4 py-1.5 text-[11px] font-bold uppercase tracking-widest text-white/90 mb-6">
            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
            <?php echo e(translate('JELAJAHI KEPULAUAN', 'EXPLORE THE ARCHIPELAGO')); ?>
        </div>

        <!-- Headline -->
        <h1
            class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.12] tracking-tight max-w-3xl mx-auto">
            <?php echo e(translate('Temukan Keajaiban', 'Discover the Magic of')); ?>
            <span class="block text-gradient">Indonesia</span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-5 text-base sm:text-lg text-slate-300/90 max-w-2xl mx-auto leading-relaxed">
            <?php echo e(translate(
                'Temukan paket wisata impian dan pilih sendiri pemandu tersertifikasi yang siap berbagi keindahan autentik Indonesia.',
                'Find the best local tour guides and customized packages to experience the rich culture, history, and natural beauty of Indonesia.'
            )); ?>
        </p>

        <!-- Search Card -->
        <div class="mt-10 max-w-4xl mx-auto">
            <!-- Search Type Tabs -->
            <div class="flex justify-start sm:justify-center mb-4">
                <div class="inline-flex gap-1 bg-white/10 backdrop-blur-sm rounded-full p-1 border border-white/15">
                    <button type="button"
                        class="px-5 py-2 rounded-full bg-white text-teal-700 text-xs font-bold shadow-sm transition-all">
                        <?php echo e(translate('Semua Paket', 'All Tours')); ?>
                    </button>
                    <button type="button"
                        class="px-5 py-2 rounded-full text-white/80 text-xs font-semibold hover:text-white transition-colors">
                        <?php echo e(translate('Berdasarkan Wilayah', 'By Region')); ?>
                    </button>
                </div>
            </div>

            <!-- Main Search Form (white pill container) -->
            <form action="/" method="GET"
                class="search-pill-form bg-white rounded-full p-2 pl-6 pr-2 shadow-2xl border border-slate-100/80 flex flex-col md:flex-row items-center gap-4 max-w-3xl mx-auto">

                <!-- Destination Input -->
                <div class="flex items-center gap-3 flex-1 w-full">
                    <i data-lucide="map-pin" class="h-5 w-5 text-cyan-500 shrink-0"></i>
                    <div class="flex-1 min-w-0 text-left">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">
                            <?php echo e(translate('Mau ke mana?', 'Where to go?')); ?>
                        </label>
                        <input type="text" name="search"
                            placeholder="<?php echo e(translate('Cari paket, kota, destinasi...', 'Search tours, destinations...')); ?>"
                            value="<?php echo htmlspecialchars($searchKeyword ?? ''); ?>"
                            class="w-full bg-transparent border-none outline-none text-slate-700 font-semibold text-sm placeholder-slate-400">
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block h-8 w-px bg-slate-200"></div>

                <!-- Date Input -->
                <div class="flex items-center gap-3 flex-1 w-full">
                    <i data-lucide="calendar" class="h-5 w-5 text-cyan-500 shrink-0"></i>
                    <div class="flex-1 min-w-0 text-left">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-1">
                            <?php echo e(translate('Tanggal Perjalanan', 'Travel Date')); ?>
                        </label>
                        <input type="date" name="travel_date"
                            class="w-full bg-transparent border-none outline-none text-slate-700 font-semibold text-sm"
                            style="color-scheme: light;">
                    </div>
                </div>

                <?php if (!empty($selectedCategory)): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                <?php endif; ?>

                <!-- Actions -->
                <div class="flex items-center gap-2 w-full md:w-auto shrink-0 justify-between md:justify-end">
                    <!-- Near Me -->
                    <button type="button" onclick="getGeoLocation()" id="lbs-btn"
                        class="h-12 w-12 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-teal-600 hover:border-teal-300 transition-all">
                        <i data-lucide="locate-fixed" class="h-5 w-5"></i>
                    </button>

                    <!-- Search Button -->
                    <button type="submit"
                        class="btn-teal h-12 px-8 rounded-full font-bold text-sm whitespace-nowrap flex-1 md:flex-initial">
                        <i data-lucide="search" class="h-4 w-4 mr-2"></i>
                        <?php echo e(translate('Cari', 'Search')); ?>
                    </button>
                </div>
            </form>

            <?php if (!empty($userLat) && !empty($userLng)): ?>
                <div class="mt-4 flex items-center justify-center gap-2 text-xs text-cyan-300">
                    <i data-lucide="circle-check" class="h-3.5 w-3.5"></i>
                    <span><?php echo e(translate('Geolokasi aktif — menampilkan destinasi terdekat Anda.', 'Geolocation active — showing nearest destinations.')); ?></span>
                    <a href="/" class="underline text-rose-300 font-semibold hover:text-rose-200">Reset</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════════════════
     FLOATING STATS CARD — overlapping the hero
═════════════════════════════════════════════════════════════════════ -->
<div class="relative z-10 mx-auto max-w-4xl px-4 sm:px-6 -mt-10 mb-8">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl py-6 px-4 sm:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-0 divide-y-2 md:divide-y-0 md:divide-x divide-slate-100">

            <!-- Stat 1: Tour Destinations -->
            <div class="flex flex-col items-center text-center px-4 py-3 md:py-0">
                <div class="stat-icon">
                    <i data-lucide="map-pin" class="h-5 w-5"></i>
                </div>
                <span class="text-2xl font-black text-slate-900">8+</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">
                    <?php echo e(translate('Destinasi Wisata', 'Tour Destinations')); ?>
                </span>
            </div>

            <!-- Stat 2: Experience -->
            <div class="flex flex-col items-center text-center px-4 py-3 md:py-0">
                <div class="stat-icon">
                    <i data-lucide="award" class="h-5 w-5"></i>
                </div>
                <span class="text-2xl font-black text-slate-900">6</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">
                    <?php echo e(translate('Tahun Pengalaman', 'Years Experience')); ?>
                </span>
            </div>

            <!-- Stat 3: Partners -->
            <div class="flex flex-col items-center text-center px-4 py-3 md:py-0">
                <div class="stat-icon">
                    <i data-lucide="handshake" class="h-5 w-5"></i>
                </div>
                <span class="text-2xl font-black text-slate-900">11</span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">
                    <?php echo e(translate('Mitra Bahagia', 'Happy Partners')); ?>
                </span>
            </div>

            <!-- Stat 4: Rating -->
            <div class="flex flex-col items-center text-center px-4 py-3 md:py-0">
                <div class="stat-icon" style="background:rgba(251,191,36,.12); color:#d97706;">
                    <i data-lucide="star" class="h-5 w-5"></i>
                </div>
                <span class="text-2xl font-black text-slate-900">4.9<span
                        class="text-base font-semibold text-slate-400"> / 5.0</span></span>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">
                    <?php echo e(translate('Rata-rata Rating', 'Average Rating')); ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════
     MAIN CONTENT SECTIONS
═════════════════════════════════════════════════════════════════════ -->
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    <!-- ─── Browse by Category ──────────────────────────────────────── -->
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="section-title"><?php echo e(translate('Cari Berdasarkan Kategori', 'Browse by Category')); ?></h2>
            <p class="section-subtitle mt-2">
                <?php echo e(translate('Temukan paket yang sesuai dengan gaya liburan Anda.', 'Find packages that match your travel style.')); ?>
            </p>
        </div>

        <div class="category-grid grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            <?php foreach ($categories as $cat):
                $isSel = ($selectedCategory === $cat['slug']);
                ?>
                <a href="/?category=<?php echo e($cat['slug']); ?><?php echo e(!empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''); ?>"
                    class="cat-card group <?php echo e($isSel ? 'active' : ''); ?>"
                    title="<?php echo htmlspecialchars(translate($cat['name_id'], $cat['name_en'])); ?>">
                    <div class="text-3xl mb-3 transform group-hover:scale-110 transition-transform duration-200">
                        <?php echo htmlspecialchars($cat['icon']); ?>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 leading-tight line-clamp-2">
                        <?php echo htmlspecialchars(translate($cat['name_id'], $cat['name_en'])); ?>
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-1"><?php echo e($cat['package_count']); ?>
                        <?php echo e(translate('Paket', 'Packages')); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── Featured Tours ───────────────────────────────────────────── -->
    <section class="mb-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="section-title"><?php echo e(translate('Paket Wisata Pilihan', 'Featured Tours')); ?></h2>
                <p class="section-subtitle">
                    <?php echo e(translate('Pilihan pengalaman terbaik di seluruh Indonesia.', 'Handpicked experiences across Indonesia.')); ?>
                </p>
            </div>
            <a href="/tours"
                class="flex items-center gap-1 text-sm font-bold text-teal-600 hover:text-teal-500 whitespace-nowrap transition-colors">
                <?php echo e(translate('Lihat Semua', 'View All')); ?>
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
        </div>

        <?php if (empty($packages)): ?>
            <div class="text-center py-14 bg-white rounded-2xl border border-slate-100 shadow-sm">
                <i data-lucide="route" class="h-10 w-10 text-slate-300 mx-auto mb-3"></i>
                <p class="text-slate-400 text-sm">
                    <?php echo e(translate('Tidak ada paket wisata yang cocok.', 'No packages match your search.')); ?>
                </p>
                <a href="/" class="mt-4 inline-block text-xs font-semibold text-teal-600 hover:underline">Reset
                    pencarian</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($packages as $idx => $pkg):
                    // Resolve image
                    $img = 'placeholder.jpg';
                    if (!empty($pkg['images'])) {
                        foreach ($pkg['images'] as $checkImg) {
                            if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($checkImg))) {
                                $img = $checkImg;
                                break;
                            }
                        }
                    }
                    // Mock ratings & tags
                    $ratings = [1 => '4.8', 2 => '4.9', 3 => '5.0'];
                    $rating = $ratings[$pkg['id']] ?? '4.8';
                    ?>
                    <div class="card-light group flex flex-col overflow-hidden">
                        <!-- Image -->
                        <div class="tour-card-img-wrap relative aspect-video">
                            <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>"
                                alt="<?php echo htmlspecialchars($pkg['title_id']); ?>"
                                class="tour-card-img w-full h-full object-cover" loading="lazy">

                            <!-- Rating top-right -->
                            <div class="absolute top-3 right-3">
                                <span
                                    class="inline-flex items-center gap-1 bg-amber-400 text-slate-900 text-[10px] font-black rounded-full px-2 py-0.5 shadow">
                                    <i data-lucide="star" class="h-2.5 w-2.5 fill-slate-900"></i>
                                    <?php echo e($rating); ?>
                                </span>
                            </div>

                            <!-- Duration bottom-left -->
                            <div class="absolute bottom-3 left-3">
                                <span
                                    class="inline-flex items-center gap-1 bg-black/60 backdrop-blur-sm text-white text-[10px] font-semibold rounded-full px-2.5 py-1 border border-white/10">
                                    <i data-lucide="clock" class="h-2.5 w-2.5 text-cyan-400"></i>
                                    <?php echo e($pkg['duration_days']); ?>         <?php echo e(translate('Hari', 'Days')); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center gap-1 text-[11px] text-slate-400 mb-2">
                                <i data-lucide="map-pin" class="h-3 w-3 text-cyan-500"></i>
                                <span><?php echo htmlspecialchars($pkg['location_name']); ?></span>
                                <span class="ml-auto badge-teal"><?php echo htmlspecialchars($pkg['category']); ?></span>
                            </div>

                            <h3
                                class="font-bold text-sm text-slate-900 group-hover:text-teal-600 transition-colors line-clamp-2 leading-snug">
                                <a href="/tours/detail/<?php echo e($pkg['id']); ?>">
                                    <?php echo htmlspecialchars(translate($pkg['title_id'], $pkg['title_en'])); ?>
                                </a>
                            </h3>

                            <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed flex-1">
                                <?php echo htmlspecialchars(translate($pkg['description_id'], $pkg['description_en'])); ?>
                            </p>

                            <!-- Footer: Price + CTA -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                                <div>
                                    <span
                                        class="block text-[10px] text-slate-400 font-semibold uppercase tracking-wide"><?php echo e(translate('Mulai dari', 'Starting from')); ?></span>
                                    <span class="font-extrabold text-base text-teal-600">
                                        <?php echo e($_SESSION['lang'] === 'en' ? format_usd($pkg['price']) : format_rupiah($pkg['price'])); ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400"> / pax</span>
                                </div>
                                <a href="/tours/detail/<?php echo e($pkg['id']); ?>"
                                    class="px-5 py-2 rounded-full bg-cyan-50 text-cyan-700 hover:bg-cyan-600 hover:text-white text-xs font-bold transition-all">
                                    <?php echo e(translate('Detail', 'View Details')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- ─── Top Destinations ─────────────────────────────────────────── -->
    <section class="mb-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-3">
            <div>
                <h2 class="section-title"><?php echo e(translate('Destinasi Utama', 'Top Destinations')); ?></h2>
                <p class="section-subtitle">
                    <?php echo e(translate('Tempat paling populer di Indonesia.', 'Most popular places to visit in Indonesia.')); ?>
                </p>
            </div>
            <a href="/destinations"
                class="btn-teal text-xs px-5 py-2.5 rounded-xl whitespace-nowrap self-start sm:self-auto">
                <?php echo e(translate('Semua Destinasi', 'All Destinations')); ?>
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <?php
            $destCounts = [
                'Gunung Bromo' => 1,
                'Bali' => 3,
                'Yogyakarta' => 2,
                'Raja Ampat' => 4,
                'Lombok' => 2,
                'Komodo' => 1,
            ];
            foreach ($destinations as $dest):
                $img = 'placeholder.jpg';
                if (!empty($dest['images'])) {
                    foreach ($dest['images'] as $checkImg) {
                        if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($checkImg))) {
                            $img = $checkImg;
                            break;
                        }
                    }
                }
                $pCount = $destCounts[$dest['name_id']] ?? 2;
                ?>
                <a href="/destinations/detail/<?php echo e($dest['slug']); ?>" class="dest-card">
                    <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>"
                        alt="<?php echo htmlspecialchars($dest['name_id']); ?>" loading="lazy">
                    <div class="dest-overlay"></div>
                    <div class="dest-content">
                        <span class="badge-teal"
                            style="background:rgba(14,165,233,.18); color:#7dd3fc; border-color:rgba(14,165,233,.3); font-size:.65rem; margin-bottom:.4rem; display:inline-flex; gap:.25rem;">
                            <i data-lucide="map-pin" style="height:.7rem;width:.7rem;"></i>
                            <?php echo htmlspecialchars($dest['province']); ?>
                        </span>
                        <h3
                            class="font-bold text-base text-white leading-tight group-hover:text-cyan-300 transition-colors">
                            <?php echo htmlspecialchars(translate($dest['name_id'], $dest['name_en'])); ?>
                        </h3>
                        <span
                            class="inline-block mt-2 text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-0.5 rounded-full">
                            <?php echo e($pCount); ?>     <?php echo e(translate('Tours Available', 'Tours Available')); ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ─── B2B Partner Program ───────────────────────────────────────── -->
    <section class="mb-12" id="b2b-section">
        <div
            class="relative overflow-hidden rounded-3xl bg-b2b-dark border border-slate-800 text-white p-8 md:p-12 shadow-2xl">
            <!-- Glow Decor -->
            <div class="absolute top-0 right-0 w-72 h-72 bg-cyan-500/8 blur-3xl rounded-full pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 bg-teal-500/6 blur-3xl rounded-full pointer-events-none">
            </div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
                <!-- Left: Text -->
                <div class="flex flex-col justify-center space-y-5">
                    <div>
                        <span class="badge-teal text-cyan-300 border-cyan-500/30 mb-4 inline-block"
                            style="background:rgba(14,165,233,.12);">
                            <?php echo e(translate('Untuk Agen & Operator', 'For Travel Agencies & Operators')); ?>
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-black tracking-tight leading-[1.15]">
                            <?php echo e(translate('Program Kemitraan B2B', 'B2B Partner Program')); ?>
                        </h2>
                    </div>
                    <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                        <?php echo e(translate(
                            'Terhubung dengan jaringan pemandu lokal terbaik, paket kustom, dan sistem manajemen pemesanan komprehensif untuk mendukung bisnis perjalanan Anda.',
                            'Connect with top local guides, custom packages, and a comprehensive booking management system to power your travel business.'
                        )); ?>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 pt-1">
                        <a href="/register" class="btn-teal px-6 py-3 text-sm rounded-xl gap-2">
                            <?php echo e(translate('Gabung Mitra B2B', 'Become a B2B Partner')); ?>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                        <a href="/login"
                            class="btn-outline-teal px-6 py-3 text-sm rounded-xl border-slate-600 text-slate-300 hover:bg-slate-700 hover:text-white hover:border-slate-600">
                            <?php echo e(translate('Portal Kemitraan', 'Partner Portal')); ?>
                        </a>
                    </div>
                </div>

                <!-- Right: Feature cards -->
                <div class="flex flex-col justify-center space-y-4">
                    <?php
                    $b2bFeatures = [
                        ['icon' => 'globe', 'title_id' => 'Akses Global', 'title_en' => 'Global Access', 'desc_id' => 'Terhubung dengan jaringan pemandu lokal terverifikasi di seluruh Indonesia.', 'desc_en' => 'Connect with verified local tour guides across the Indonesian archipelago.'],
                        ['icon' => 'briefcase', 'title_id' => 'Paket Kustomisasi', 'title_en' => 'Tailored Packages', 'desc_id' => 'Itinerary, akomodasi, dan transportasi disesuaikan untuk klien Anda.', 'desc_en' => 'Customize itineraries and accommodations dynamically for your clients.'],
                        ['icon' => 'headphones', 'title_id' => 'Dukungan 24/7', 'title_en' => '24/7 Support', 'desc_id' => 'Layanan customer service khusus untuk mitra perjalanan kami.', 'desc_en' => 'Dedicated support for handling partner bookings and logistics.'],
                    ];
                    foreach ($b2bFeatures as $f):
                        ?>
                        <div class="flex items-start gap-4 b2b-card">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-cyan-500/15 text-cyan-400">
                                <i data-lucide="<?php echo e($f['icon']); ?>" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-white mb-1">
                                    <?php echo e(translate($f['title_id'], $f['title_en'])); ?>
                                </h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    <?php echo e(translate($f['desc_id'], $f['desc_en'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

</div><!-- /.max-w-7xl -->

<!-- Geolocation Script -->
<script>
    function getGeoLocation() {
        const btn = document.getElementById('lbs-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = `<i data-lucide="loader-2" class="h-4 w-4 animate-spin text-teal-500"></i>`;
        lucide.createIcons({ nodes: btn.querySelectorAll('[data-lucide]') });
        btn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                ({ coords }) => {
                    const { latitude: lat, longitude: lng } = coords;
                    const search = document.querySelector('input[name="search"]')?.value || '';
                    const cat = document.querySelector('input[name="category"]')?.value || '';
                    window.location.href = `/?lat=${lat}&lng=${lng}${search ? `&search=${encodeURIComponent(search)}` : ''}${cat ? `&category=${encodeURIComponent(cat)}` : ''}`;
                },
                () => { alert('Gagal mendapatkan lokasi Anda. Pastikan izin GPS aktif.'); btn.innerHTML = orig; btn.disabled = false; },
                { enableHighAccuracy: true, timeout: 6000 }
            );
        } else {
            alert('Browser Anda tidak mendukung Geolocation.'); btn.innerHTML = orig; btn.disabled = false;
        }
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>