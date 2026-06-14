<?php
// File: resources/views/tour/checkout.php
$pageTitle = "Konfirmasi Pemesanan - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 py-12">
    <div class="mb-8 flex items-center space-x-2">
        <a href="/tours/detail/<?php echo e($package['id']); ?>" class="text-xs text-slate-500 hover:text-teal-400 font-bold transition-colors">
            <i data-lucide="chevron-left" class="mr-1"></i> <?php echo e(translate('Kembali ke Paket', 'Back to Package')); ?>
        </a>
    </div>

    <h1 class="text-3xl font-extrabold text-white tracking-tight mb-8 flex items-center space-x-3">
        <i data-lucide="wallet" class="text-teal-400"></i>
        <span><?php echo e(translate('Konfirmasi Pemesanan Wisata', 'Confirm Tour Booking')); ?></span>
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Form Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-slate-950/40 border border-slate-800/80 px-6 py-6 shadow-xl rounded-2xl backdrop-blur-md">
                
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5 pb-2 border-b border-slate-900"><?php echo e(translate('Lengkapi Data Keberangkatan', 'Departure Information')); ?></h3>
                
                <form action="/tours/checkout/<?php echo e($package['id']); ?>" method="POST" class="space-y-5">
                    <?php echo \App\Core\Csrf::field(); ?>

                    <div>
                        <label for="travel_date" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5"><?php echo e(translate('Tanggal Keberangkatan', 'Travel Date')); ?></label>
                        <input id="travel_date" name="travel_date" type="date" required 
                            min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                            class="block w-full rounded-xl border border-slate-800 bg-slate-900/50 py-2.5 px-3.5 text-xs text-slate-200 focus:border-teal-500 focus:bg-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>

                    <div>
                        <label for="total_participants" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5"><?php echo e(translate('Jumlah Peserta (pax)', 'Total Participants')); ?></label>
                        <input id="total_participants" name="total_participants" type="number" required min="1" max="50" value="1"
                            onchange="calculateTotal()" onkeyup="calculateTotal()"
                            class="block w-full rounded-xl border border-slate-800 bg-slate-900/50 py-2.5 px-3.5 text-xs text-slate-200 focus:border-teal-500 focus:bg-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>

                    <div>
                        <label for="guide_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5"><?php echo e(translate('Pilihan Pemandu Wisata', 'Select Tour Guide')); ?></label>
                        <select id="guide_id" name="guide_id" class="block w-full rounded-xl border border-slate-800 bg-slate-900/50 py-2.5 px-3.5 text-xs text-slate-200 focus:border-teal-500 focus:bg-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                            <option value=""><?php echo e(translate('-- Cari Guide Otomatis (Rekomendasi) --', '-- Auto Assign Guide (Recommended) --')); ?></option>
                            <?php foreach ($guides as $guide): ?>
                                <?php $isSel = ($selectedGuide && $selectedGuide['id'] === $guide['id']); ?>
                                <option value="<?php echo e($guide['id']); ?>" <?php echo e($isSel ? 'selected' : ''); ?>>
                                    👤 <?php echo htmlspecialchars($guide['name']); ?> (⭐ <?php echo e(number_format($guide['rating_cache'], 1)); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-900 flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-400"><?php echo e(translate('Estimasi Total Bayar:', 'Estimated Total Price:')); ?></span>
                        <span id="grand-total" class="text-lg text-teal-400"><?php echo e(format_rupiah($package['price'])); ?></span>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="flex w-full justify-center rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 py-3 px-4 text-sm font-bold text-slate-950 shadow-md shadow-teal-500/10 hover:opacity-90 hover:scale-[1.01] active:scale-[0.99] transition-all">
                            <i data-lucide="credit-card" class="mr-2 mt-0.5"></i> <?php echo e(translate('Proses Pembayaran Instan', 'Process Instant Payment')); ?>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-slate-950/20 border border-slate-800 p-5 rounded-2xl">
                <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-4"><?php echo e(translate('Ringkasan Paket', 'Package Summary')); ?></h4>
                <div class="space-y-4">
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold block">PAKET WISATA</span>
                        <span class="text-xs font-bold text-white leading-relaxed block"><?php echo htmlspecialchars(translate($package['title_id'], $package['title_en'])); ?></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold block">LOKASI</span>
                        <span class="text-xs text-slate-300 block"><i data-lucide="map-pin" class="text-teal-500 mr-1"></i> <?php echo htmlspecialchars($package['location_name']); ?></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-500 font-bold block">HARGA PER PAX</span>
                        <span class="text-xs text-teal-400 font-bold block"><?php echo e(format_rupiah($package['price'])); ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const packagePrice = <?php echo e((float)$package['price']); ?>;

    function calculateTotal() {
        const input = document.getElementById('total_participants');
        const totalSpan = document.getElementById('grand-total');
        let participants = parseInt(input.value) || 1;
        if (participants < 1) participants = 1;
        
        const total = packagePrice * participants;
        
        // Format Rupiah
        totalSpan.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
