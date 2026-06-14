<?php
// File: resources/views/tour/booking_success.php
$pageTitle = "Pemesanan Berhasil - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-md px-4 py-16 text-center">
    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mb-6">
        <i data-lucide="circle-check" class="text-3xl"></i>
    </div>
    
    <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2"><?php echo translate('Pembayaran Berhasil!', 'Payment Successful!'); ?></h1>
    <p class="text-slate-400 text-xs mb-8">
        <?php echo translate(
            'Terima kasih! Pemesanan paket wisata Anda telah berhasil diproses oleh sistem.',
            'Thank you! Your tour package booking has been successfully processed.'
        ); ?>
    </p>

    <!-- Invoice Details card -->
    <div class="bg-slate-950/40 border border-slate-800/80 rounded-2xl p-6 text-left mb-8 space-y-4 text-xs">
        <div class="flex justify-between items-center border-b border-slate-900 pb-3">
            <span class="text-slate-500 font-bold">NOMOR INVOICE</span>
            <span class="font-mono font-bold text-white"><?php echo htmlspecialchars($booking['invoice_number']); ?></span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-slate-500 font-bold">PAKET WISATA</span>
            <span class="font-bold text-slate-200 text-right max-w-[200px] truncate"><?php echo htmlspecialchars($booking['package_name_snapshot']); ?></span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-slate-500 font-bold">TANGGAL KEBERANGKATAN</span>
            <span class="font-bold text-slate-200"><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-slate-500 font-bold">JUMLAH PESERTA</span>
            <span class="font-bold text-slate-200"><?php echo $booking['total_participants']; ?> pax</span>
        </div>
        <div class="flex justify-between items-center border-t border-slate-900 pt-3 text-sm font-bold">
            <span class="text-slate-400">TOTAL BAYAR</span>
            <span class="text-teal-400"><?php echo format_rupiah($booking['total_price']); ?></span>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/dashboard" class="w-full sm:w-auto px-6 py-2.5 rounded-full bg-gradient-to-r from-teal-500 to-emerald-500 text-slate-950 font-bold text-sm hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] transition-all">
            <i data-lucide="trending-up" class="mr-1"></i> <?php echo translate('Buka Dashboard', 'Go to Dashboard'); ?>
        </a>
        <a href="/" class="w-full sm:w-auto px-6 py-2.5 rounded-full border border-slate-700 text-slate-300 font-semibold text-sm hover:bg-slate-800 transition-colors">
            <i data-lucide="home" class="mr-1"></i> <?php echo translate('Kembali ke Beranda', 'Back to Home'); ?>
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
