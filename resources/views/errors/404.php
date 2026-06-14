<?php
// File: resources/views/errors/404.php
$pageTitle = "404 Halaman Tidak Ditemukan - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-md px-4 py-24 text-center">
    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-400 mb-6">
        <i data-lucide="map-pin" class="text-3xl"></i>
    </div>
    
    <h1 class="text-4xl font-black tracking-tight text-white mb-2">404</h1>
    <h2 class="text-xl font-bold text-slate-200 mb-4"><?php echo e(translate('Halaman Tidak Ditemukan', 'Page Not Found')); ?></h2>
    
    <p class="text-slate-400 text-sm mb-8 leading-relaxed">
        <?php echo e(translate(
            'Halaman yang Anda cari tidak dapat ditemukan. Mungkin alamat URL salah ketik atau halaman telah dihapus oleh pengelola sistem.',
            'The page you are looking for could not be found. It might have been moved, deleted, or the URL address was mistyped.'
        )); ?>
    </p>

    <div class="flex justify-center">
        <a href="/" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 text-slate-950 font-bold text-sm hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="home" class="mr-1"></i> <?php echo e(translate('Kembali ke Beranda', 'Back to Home')); ?>
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
