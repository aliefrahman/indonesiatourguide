<?php
// File: resources/views/errors/403.php
$pageTitle = "403 Akses Ditolak - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-md px-4 py-24 text-center">
    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-500 mb-6 animate-pulse">
        <i data-lucide="ban" class="text-3xl"></i>
    </div>
    
    <h1 class="text-4xl font-black tracking-tight text-white mb-2">403</h1>
    <h2 class="text-xl font-bold text-slate-200 mb-4"><?php echo translate('Akses Ditolak', 'Access Forbidden'); ?></h2>
    
    <p class="text-slate-400 text-sm mb-8 leading-relaxed">
        <?php echo translate(
            'Maaf, Anda tidak memiliki hak akses/izin yang diperlukan untuk membuka halaman ini. Silakan hubungi admin atau login dengan akun lain.',
            'Sorry, you do not have the necessary permissions to access this page. Please contact the administrator or login with another account.'
        ); ?>
    </p>

    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
        <a href="/" class="w-full sm:w-auto px-6 py-2.5 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 text-slate-950 font-bold text-sm hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] transition-all">
            <i data-lucide="home" class="mr-1"></i> <?php echo translate('Kembali ke Beranda', 'Back to Home'); ?>
        </a>
        <a href="/login" class="w-full sm:w-auto px-6 py-2.5 rounded-full border border-slate-700 text-slate-300 font-semibold text-sm hover:bg-slate-800 transition-colors">
            <i data-lucide="log-in" class="mr-1"></i> <?php echo translate('Ganti Akun', 'Switch Account'); ?>
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
