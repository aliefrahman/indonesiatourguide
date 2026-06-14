<?php
// File: resources/views/tour/tour.php
$pageTitle = "Pilihan Paket Wisata Terbaik - IndoTour";
require __DIR__ . '/../layouts/header.php';
$currentLang = $_SESSION['lang'] ?? 'id';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="mb-12 text-center">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            <?php echo e(translate('Daftar Paket Wisata Populer', 'Popular Tour Packages')); ?>
        </h1>
        <p class="mt-3 text-slate-500 text-sm max-w-xl mx-auto leading-relaxed">
            <?php echo e(translate(
                'Temukan rencana petualangan harian terbaik Anda di Indonesia. Semua paket sudah termasuk pemandu lokal.',
                'Find your ideal tour packages across beautiful Indonesia. Local tour guide and amenities are included in all packages.'
            )); ?>
        </p>
    </div>

    <!-- Filters layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar filters -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-sm">
                <h3 class="text-xs font-bold text-slate-800 mb-4 uppercase tracking-wider">
                    <?php echo e(translate('Saring Kategori', 'Filter Category')); ?>
                </h3>
                
                <div class="space-y-2.5">
                    <!-- All Packages Link -->
                    <a href="/tours<?php echo e(!empty($searchKeyword) ? '?search=' . urlencode($searchKeyword) : ''); ?>" 
                        class="flex items-center gap-2 text-xs font-bold px-3 py-2.5 rounded-xl border transition-all <?php echo e(empty($selectedCategory) ? 'border-teal-500 bg-teal-50 text-teal-600 shadow-sm' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:text-slate-800'); ?>">
                        <i data-lucide="compass" class="h-3.5 w-3.5"></i>
                        <span><?php echo e(translate('Semua Paket Wisata', 'All Packages')); ?></span>
                    </a>
                    
                    <!-- Categories Loop -->
                    <?php foreach ($categories as $cat): ?>
                        <?php $isSel = ($selectedCategory === $cat['slug']); ?>
                        <a href="/tours?category=<?php echo e($cat['slug']); ?><?php echo e(!empty($searchKeyword) ? '&search=' . urlencode($searchKeyword) : ''); ?>" 
                            class="flex items-center gap-2 text-xs font-bold px-3 py-2.5 rounded-xl border transition-all <?php echo e($isSel ? 'border-teal-500 bg-teal-50 text-teal-600 shadow-sm' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-slate-300 hover:text-slate-800'); ?>">
                            <span class="text-sm leading-none"><?php echo htmlspecialchars($cat['icon']); ?></span>
                            <span><?php echo htmlspecialchars(translate($cat['name_id'], $cat['name_en'])); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Packages lists -->
        <div class="lg:col-span-3">
            <?php if (empty($packages)): ?>
                <div class="text-center py-20 bg-white border border-slate-200/80 rounded-2xl shadow-sm">
                    <i data-lucide="route" class="h-10 w-10 text-slate-300 mx-auto mb-4 font-light"></i>
                    <p class="text-slate-500 text-sm"><?php echo e(translate('Tidak ada paket wisata yang tersedia.', 'No tour packages found.')); ?></p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($packages as $pkg): ?>
                        <div class="card-light group flex flex-col justify-between overflow-hidden">
                            <div class="flex flex-col">
                                <!-- Card Image -->
                                <div class="tour-card-img-wrap relative aspect-video bg-slate-100">
                                    <?php 
                                    $img = 'placeholder.jpg';
                                    if (!empty($pkg['images'])) {
                                        foreach ($pkg['images'] as $checkImg) {
                                            if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($checkImg))) {
                                                $img = $checkImg;
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($pkg['title_id']); ?>" 
                                        class="tour-card-img w-full h-full object-cover">
                                    
                                    <!-- Duration Badge -->
                                    <div class="absolute bottom-3 left-4">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-black/60 backdrop-blur-sm px-2.5 py-1 text-[10px] font-semibold text-white border border-white/10">
                                            <i data-lucide="clock" class="h-2.5 w-2.5 text-cyan-400"></i> 
                                            <?php echo e($pkg['duration_days']); ?> <?php echo e(translate('Hari', 'Days')); ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-5">
                                    <div class="flex items-center justify-between gap-1 text-[11px] text-slate-400 mb-2">
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="map-pin" class="h-3 w-3 text-cyan-500"></i>
                                            <span><?php echo htmlspecialchars($pkg['location_name']); ?></span>
                                        </div>
                                        <span class="badge-teal text-[10px]"><?php echo htmlspecialchars($pkg['category']); ?></span>
                                    </div>
                                    
                                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-teal-600 transition-colors line-clamp-1 leading-snug">
                                        <a href="/tours/detail/<?php echo e($pkg['id']); ?>">
                                            <?php echo htmlspecialchars(translate($pkg['title_id'], $pkg['title_en'])); ?>
                                        </a>
                                    </h3>
                                    
                                    <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                        <?php echo htmlspecialchars(translate($pkg['description_id'], $pkg['description_en'])); ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-5 pb-5 pt-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase tracking-wide font-semibold"><?php echo e(translate('Mulai dari', 'Starting from')); ?></span>
                                    <span class="font-extrabold text-base text-teal-600">
                                        <?php echo e($currentLang === 'en' ? format_usd($pkg['price']) : format_rupiah($pkg['price'])); ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400"> / pax</span>
                                </div>
                                
                                <a href="/tours/detail/<?php echo e($pkg['id']); ?>" 
                                    class="px-5 py-2 rounded-full bg-cyan-50 text-cyan-700 hover:bg-cyan-600 hover:text-white text-xs font-bold transition-all">
                                    <?php echo e(translate('Detail', 'View Details')); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
