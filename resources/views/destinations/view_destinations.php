<?php
// File: resources/views/destinations/view_destinations.php
$pageTitle = translate($destination['name_id'], $destination['name_en']) . " - IndoTour";
require __DIR__ . '/../layouts/header.php';

$allImages = $destination['images'];
$images = [];
foreach ($allImages as $img) {
    if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($img))) {
        $images[] = $img;
    }
}
$heroImage = !empty($images) ? $images[0] : 'placeholder.jpg';
?>

<!-- Hero Banner - Cover Index 0 -->
<div class="relative h-[400px] w-full bg-slate-900 overflow-hidden border-b border-slate-200">
    <img src="/storage/uploads/<?php echo htmlspecialchars($heroImage); ?>"
        alt="<?php echo htmlspecialchars($destination['name_id']); ?>"
        class="absolute inset-0 h-full w-full object-cover opacity-90">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>

    <div class="absolute bottom-10 left-0 right-0">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span
                class="inline-flex items-center rounded-full bg-white/20 backdrop-blur-md px-3 py-1 text-xs font-bold text-white border border-white/30 uppercase mb-3 tracking-wider shadow-sm">
                <i data-lucide="map-pin" class="mr-1 w-3 h-3"></i>
                <?php echo htmlspecialchars($destination['province']); ?>
            </span>
            <h1 class="text-3xl font-black text-white sm:text-5xl tracking-tight drop-shadow-md">
                <?php echo htmlspecialchars(translate($destination['name_id'], $destination['name_en'])); ?>
            </h1>
            <?php if (!empty($destination['region'])): ?>
                    <p class="mt-2 text-slate-100 font-medium text-sm flex items-center space-x-1.5 drop-shadow-md">
                        <i data-lucide="map-pin" class="text-teal-400 w-4 h-4"></i>
                        <span><?php echo htmlspecialchars($destination['region']); ?></span>
                    </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Description details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
                <h3
                    class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-teal-500"></i>
                    <span><?php echo translate('Tentang Destinasi', 'About Destination'); ?></span>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                    <?php echo htmlspecialchars(translate($destination['description_id'], $destination['description_en'])); ?>
                </p>
            </div>

            <!-- Destinasi coordinates LBS -->
            <?php if (!empty($destination['latitude']) && !empty($destination['longitude'])): ?>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <i data-lucide="earth" class="w-4 h-4 text-teal-500"></i>
                            <span><?php echo translate('Lokasi Koordinat GPS', 'GPS Coordinates'); ?></span>
                        </h3>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                            <div class="grid grid-cols-2 gap-4 text-slate-500 font-mono bg-slate-50 px-4 py-3 rounded-xl border border-slate-100 w-full sm:w-auto">
                                <div>Latitude: <span
                                        class="text-slate-800 font-bold"><?php echo $destination['latitude']; ?></span>
                                </div>
                                <div>Longitude: <span
                                        class="text-slate-800 font-bold"><?php echo $destination['longitude']; ?></span>
                                </div>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $destination['latitude']; ?>,<?php echo $destination['longitude']; ?>"
                                target="_blank"
                                class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl hover:border-teal-300 text-teal-700 hover:text-teal-800 hover:bg-teal-50 font-bold text-center transition-all shadow-sm flex items-center justify-center gap-1.5 shrink-0">
                                <i data-lucide="map" class="w-3.5 h-3.5"></i>
                                <?php echo translate('Buka di Google Maps', 'Open in Google Maps'); ?>
                            </a>
                        </div>
                    </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar / Multi-upload Gallery -->
        <div class="space-y-6">
            <!-- 📸 Galeri Foto Section (If more than 1 image) -->
            <?php if (count($images) > 0): ?>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                        <h3
                            class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <i data-lucide="images" class="w-4 h-4 text-teal-500"></i>
                            <span><?php echo translate('Galeri Foto', 'Photo Gallery'); ?></span>
                        </h3>

                        <div class="grid grid-cols-2 gap-3">
                            <?php foreach ($images as $index => $img): ?>
                                    <button type="button"
                                        onclick="openLightbox('<?php echo htmlspecialchars($img); ?>', <?php echo $index; ?>)"
                                        class="group aspect-4/3 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 hover:border-teal-300 relative active:scale-[0.98] transition-all shadow-sm block w-full focus:outline-none">
                                        <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>"
                                            alt="Gallery item <?php echo $index; ?>"
                                            class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <div
                                            class="absolute inset-0 bg-slate-900/10 group-hover:bg-transparent transition-colors">
                                        </div>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900/30">
                                            <i data-lucide="zoom-in" class="text-white w-6 h-6"></i>
                                        </div>
                                    </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
            <?php endif; ?>

            <!-- Action panel -->
            <div
                class="bg-gradient-to-br from-teal-50 to-emerald-50 border border-teal-100 p-6 rounded-2xl text-center shadow-sm relative overflow-hidden">
                <!-- decorative background shape -->
                <div class="absolute -top-10 -right-10 text-teal-500/10 rotate-12">
                    <i data-lucide="compass" class="w-32 h-32"></i>
                </div>

                <div class="relative z-10">
                    <i data-lucide="compass"
                        class="text-teal-600 w-10 h-10 mx-auto mb-4"></i>
                    <h4 class="font-bold text-slate-900 mb-2">
                        <?php echo translate('Tertarik Mengunjungi?', 'Interested in visiting?'); ?>
                    </h4>
                    <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                        <?php echo translate('Cari paket wisata harian kami yang mencakup rute perjalanan ke destinasi indah ini.', 'Search our tour packages that cover this amazing location.'); ?>
                    </p>
                    <a href="/tours?search=<?php echo urlencode($destination['location_name'] ?? $destination['name_id']); ?>"
                        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-teal-500 text-white font-bold text-sm hover:bg-teal-600 shadow-md active:scale-[0.98] transition-all">
                        <i data-lucide="route" class="w-4 h-4"></i>
                        <?php echo translate('Cari Paket Terkait', 'Find Packages'); ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Lightbox Modal Premium (backdrop-blur-md) -->
<div id="lightbox-modal"
    class="fixed inset-0 z-50 items-center justify-center bg-slate-900/80 backdrop-blur-sm hidden transition-all"
    onclick="handleOutsideClick(event)">
    <div class="absolute top-4 right-4 z-50">
        <!-- Event klik tombol close (✕) -->
        <button type="button" onclick="closeLightbox()"
            class="h-10 w-10 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-rose-500 hover:border-rose-500 active:scale-[0.95] transition-all text-xl focus:outline-none">
            ✕
        </button>
    </div>

    <!-- Gallery Image container -->
    <div class="max-w-5xl max-h-[90vh] px-4 flex flex-col justify-center items-center relative"
        onclick="event.stopPropagation()">
        <img id="lightbox-image" src="" alt="Lightbox image"
            class="max-w-full max-h-[80vh] rounded-2xl shadow-2xl border border-white/20 object-contain bg-black/50">
        <p id="lightbox-caption"
            class="text-white text-sm mt-4 font-bold tracking-widest uppercase drop-shadow-md bg-black/40 px-4 py-1.5 rounded-full backdrop-blur-sm">
        </p>
    </div>
</div>

<!-- Lightbox Script -->
<script>
    function openLightbox(imgFilename, index) {
        const modal = document.getElementById('lightbox-modal');
        const img = document.getElementById('lightbox-image');
        const caption = document.getElementById('lightbox-caption');

        img.src = '/storage/uploads/' + imgFilename;
        caption.innerText = `Gambar ${index + 1} dari <?php echo count($images); ?>`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; // block scrolling
    }

    function closeLightbox() {
        const modal = document.getElementById('lightbox-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = ''; // allow scrolling
    }

    // Modal tertutup bila mengklik area kosong di luar gambar
    function handleOutsideClick(e) {
        if (e.target.id === 'lightbox-modal') {
            closeLightbox();
        }
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>