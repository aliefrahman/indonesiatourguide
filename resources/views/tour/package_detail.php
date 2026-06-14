<?php
// File: resources/views/tour/package_detail.php
$pageTitle = translate($package['title_id'], $package['title_en']) . " - IndoTour";
require __DIR__ . '/../layouts/header.php';

$allImages = $package['images'];
$images = [];
foreach ($allImages as $img) {
    if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($img))) {
        $images[] = $img;
    }
}
$heroImage = !empty($images) ? $images[0] : 'placeholder.jpg';
?>

<!-- Overriding default dark styling on this detail page for light theme content consistency -->
<style>
    body {
        background-color: #f8fafc !important; /* bg-slate-50 */
        color: #334155 !important; /* text-slate-700 */
    }
    nav a.text-slate-300 {
        color: #cbd5e1 !important;
    }
    nav a.text-slate-300:hover {
        color: #2dd4bf !important;
    }
</style>

<!-- Premium Dark-themed Hero Banner matching the main style -->
<div class="relative h-[360px] w-full bg-slate-950 overflow-hidden border-b border-slate-900 bg-cover bg-center"
     style="background-image: linear-gradient(to bottom, rgba(2, 6, 23, 0.45), rgba(2, 6, 23, 0.85)), url('/storage/uploads/<?php echo htmlspecialchars($heroImage); ?>');">
    <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
    
    <div class="absolute bottom-10 left-0 right-0">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center rounded-full bg-teal-500/10 px-3 py-1 text-xs font-bold text-teal-400 border border-teal-500/20 uppercase mb-3 tracking-wider">
                <i data-lucide="map-pin" class="mr-1 h-3.5 w-3.5 text-teal-400"></i> <?php echo htmlspecialchars($package['location_name']); ?>
            </span>
            <h1 class="text-3xl font-black text-white sm:text-5xl tracking-tight max-w-4xl">
                <?php echo htmlspecialchars(translate($package['title_id'], $package['title_en'])); ?>
            </h1>
            <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-300">
                <span class="flex items-center"><i data-lucide="clock" class="text-teal-400 mr-1.5 h-4 w-4"></i> <?php echo e($package['duration_days']); ?> <?php echo e(translate('Hari Perjalanan', 'Days Duration')); ?></span>
                <span class="flex items-center"><i data-lucide="tags" class="text-teal-400 mr-1.5 h-4 w-4"></i> <?php echo e(strtoupper($package['category'])); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-10">
            
            <!-- Description Card (White bg, shadow, teal accents) -->
            <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-2xl shadow-sm text-slate-700">
                <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="align-left" class="text-teal-600"></i>
                    <span><?php echo e(translate('Deskripsi Paket', 'Package Description')); ?></span>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                    <?php echo htmlspecialchars(translate($package['description_id'], $package['description_en'])); ?>
                </p>
            </div>

            <!-- Daily Itinerary Timeline Card -->
            <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-2xl shadow-sm text-slate-700">
                <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="map-pinned" class="text-teal-600"></i>
                    <span>🗺️ <?php echo e(translate('Jadwal & Rencana Perjalanan Harian', 'Daily Itinerary Timeline')); ?></span>
                </h3>

                <?php if (empty($itineraries)): ?>
                    <p class="text-slate-400 text-xs py-4 text-center"><?php echo e(translate('Detail rencana perjalanan belum ditambahkan.', 'No itinerary schedule details added yet.')); ?></p>
                <?php else: ?>
                    <!-- Timeline Container -->
                    <div class="space-y-8 relative before:absolute before:inset-y-0 before:left-3.5 before:w-0.5 before:bg-slate-100">
                        <?php 
                        $currDay = 0;
                        foreach ($itineraries as $iti): 
                            if ($currDay !== $iti['day_number']):
                                $currDay = $iti['day_number'];
                        ?>
                            <!-- Day Header -->
                            <div class="relative pl-10 pt-4 first:pt-0">
                                <div class="absolute left-0 top-4 first:top-0 flex h-7.5 w-7.5 items-center justify-center rounded-full bg-teal-500 text-slate-950 font-extrabold text-xs shadow-sm border-2 border-slate-100">
                                    D<?php echo e($currDay); ?>
                                </div>
                                <h4 class="text-sm font-black text-teal-600 uppercase tracking-widest"><?php echo e(translate("Hari ke-$currDay", "Day $currDay")); ?></h4>
                            </div>
                        <?php endif; ?>

                            <!-- Timeline Item -->
                            <div class="relative pl-10 group">
                                <!-- Node bullet -->
                                <div class="absolute left-2.5 top-2.5 h-2.5 w-2.5 rounded-full bg-slate-200 border border-white group-hover:bg-teal-500 transition-colors"></div>
                                
                                <div class="bg-slate-50 border border-slate-100 hover:border-slate-200 p-4 rounded-xl transition-all">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                        <!-- Time block -->
                                        <span class="inline-flex items-center text-xs font-semibold text-slate-500 bg-white px-2 py-0.5 rounded-lg border border-slate-100 w-fit">
                                            <i data-lucide="clock" class="mr-1 h-3.5 w-3.5 text-teal-600"></i> 
                                            <?php echo e(date('H:i', strtotime($iti['time_start']))); ?> - <?php echo e(date('H:i', strtotime($iti['time_end']))); ?>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(translate('Aktivitas', 'Activity')); ?></span>
                                    </div>
                                    
                                    <h5 class="text-slate-800 font-bold text-sm"><?php echo htmlspecialchars(translate($iti['activity_id'], $iti['activity_en'])); ?></h5>
                                    
                                    <?php if (!empty($iti['notes_id']) || !empty($iti['notes_en'])): ?>
                                        <p class="text-xs text-slate-500 mt-2 italic leading-relaxed border-l-2 border-slate-200 pl-3">
                                            <?php echo htmlspecialchars(translate($iti['notes_id'], $iti['notes_en'])); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Customer Reviews Card -->
            <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-2xl shadow-sm text-slate-700">
                <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <i data-lucide="message-square" class="text-teal-600"></i>
                    <span>💬 <?php echo e(translate('Ulasan Pelanggan', 'Customer Reviews')); ?></span>
                </h3>

                <?php if (empty($reviews)): ?>
                    <div class="text-center py-6">
                        <i data-lucide="message-square" class="text-slate-300 text-2xl mb-2 mx-auto"></i>
                        <p class="text-slate-400 text-xs"><?php echo e(translate('Belum ada ulasan untuk paket wisata ini.', 'No reviews yet for this package.')); ?></p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="h-8 w-8 rounded-full bg-teal-50/50 text-teal-650 text-teal-600 flex items-center justify-center font-bold text-xs border border-teal-100">
                                            <?php echo e(strtoupper(substr($rev['traveler_name'], 0, 1))); ?>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-800"><?php echo htmlspecialchars($rev['traveler_name']); ?></h5>
                                            <p class="text-[10px] text-slate-400"><?php echo e(date('d M Y', strtotime($rev['created_at']))); ?></p>
                                        </div>
                                    </div>
                                    <!-- Stars -->
                                    <div class="flex text-amber-400 text-xs">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i data-lucide="star" class="h-3.5 w-3.5 <?php echo e($i < $rev['rating'] ? 'text-amber-400 fill-amber-400' : 'text-slate-200'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-slate-600 text-xs leading-relaxed mt-2 pl-0.5 italic">
                                    "<?php echo htmlspecialchars($rev['comment']); ?>"
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Sidebar Panel / Booking and Guides list -->
        <div class="space-y-6">
            
            <!-- Booking Card (White, clean shadow) -->
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-xl sticky top-20 text-slate-700">
                <div class="mb-4">
                    <span class="block text-[10px] text-slate-400 uppercase font-semibold tracking-wider"><?php echo e(translate('Harga Paket Wisata', 'Package Pricing')); ?></span>
                    <div class="flex items-baseline space-x-1.5 mt-1">
                        <span class="text-2xl font-black text-teal-600"><?php echo e(format_rupiah($package['price'])); ?></span>
                        <span class="text-xs text-slate-400"> / pax</span>
                    </div>
                    <!-- USD translation -->
                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">(Estimasi: <?php echo e(format_usd($package['price'])); ?> USD)</span>
                </div>

                <div class="border-t border-slate-100 my-5 pt-4">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3"><?php echo e(translate('Pilih Pemandu Wisata', 'Choose Tour Guide')); ?></h4>
                    
                    <form action="/tours/checkout/<?php echo e($package['id']); ?>" method="GET" class="space-y-4">
                        <select name="guide_id" class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 px-3.5 text-xs text-slate-700 focus:border-teal-500 focus:outline-none transition-all">
                            <option value=""><?php echo e(translate('-- Cari Guide Otomatis (Rekomendasi) --', '-- Auto Assign Guide (Recommended) --')); ?></option>
                            <?php foreach ($guides as $guide): ?>
                                <option value="<?php echo e($guide['id']); ?>">
                                    👤 <?php echo htmlspecialchars($guide['name']); ?> (⭐ <?php echo e(number_format($guide['rating_cache'], 1)); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <button type="submit" 
                            class="flex w-full justify-center items-center rounded-xl bg-linear-to-r from-teal-500 to-emerald-500 py-3 px-4 text-sm font-black text-slate-950 shadow-md shadow-teal-500/20 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] transition-all">
                            <i data-lucide="shopping-cart" class="mr-2"></i> <?php echo e(translate('Pesan Sekarang', 'Book Now')); ?>
                        </button>
                    </form>
                </div>

                <!-- Safe indicators -->
                <div class="mt-6 border-t border-slate-100 pt-4 space-y-2.5 text-[10px] text-slate-400">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="shield" class="text-teal-600"></i>
                        <span>Pembayaran Aman & Verifikasi Instan</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="headphones" class="text-teal-600"></i>
                        <span>Dukungan Operator 24/7 di Lokasi</span>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
