<?php
// File: resources/views/destinations/destinations.php
$pageTitle = "Jelajahi Destinasi Wisata Indonesia - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl"><?php echo e(translate('Destinasi Wisata Pilihan', 'Amazing Destinations')); ?></h1>
        <p class="mt-3 text-slate-500 text-sm max-w-xl mx-auto">
            <?php echo e(translate(
                'Eksplorasi keindahan alam, peninggalan sejarah, kebudayaan lokal, dan surga bawah laut di seluruh penjuru kepulauan Indonesia.',
                'Explore natural wonders, ancient heritage, rich local cultures, and underwater paradise across the Indonesian archipelago.'
            )); ?>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($destinations as $dest): ?>
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm hover:border-teal-300 hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                <div class="flex flex-col">
                    <div class="aspect-16/10 overflow-hidden bg-slate-100 relative">
                        <?php 
                        $img = 'placeholder.jpg';
                        if (!empty($dest['images'])) {
                            foreach ($dest['images'] as $checkImg) {
                                if (file_exists(__DIR__ . '/../../../storage/uploads/' . basename($checkImg))) {
                                    $img = $checkImg;
                                    break;
                                }
                            }
                        }
                        ?>
                        <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($dest['name_id']); ?>" 
                            class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute bottom-3 left-4">
                            <span class="inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-2.5 py-0.5 text-xs font-semibold text-teal-600 border border-white/20 shadow-sm">
                                <i data-lucide="map-pin" class="mr-1 text-[10px]"></i> <?php echo htmlspecialchars($dest['province']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-slate-900 group-hover:text-teal-600 transition-colors"><?php echo htmlspecialchars(translate($dest['name_id'], $dest['name_en'])); ?></h3>
                        <?php if (!empty($dest['region'])): ?>
                            <p class="text-xs text-slate-500 mt-1"><?php echo htmlspecialchars($dest['region']); ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-slate-600 mt-3 line-clamp-3 leading-relaxed">
                            <?php echo htmlspecialchars(translate($dest['description_id'], $dest['description_en'])); ?>
                        </p>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <a href="/destinations/detail/<?php echo e($dest['slug']); ?>" 
                        class="block w-full text-center rounded-xl bg-teal-50 border border-teal-100 py-2.5 text-xs font-bold text-teal-700 hover:text-white hover:bg-teal-500 hover:border-teal-500 active:scale-[0.98] transition-all">
                        <?php echo e(translate('Eksplorasi Detail', 'Explore Details')); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
