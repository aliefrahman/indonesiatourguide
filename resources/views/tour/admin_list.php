<?php
// File: resources/views/tour/admin_list.php
$pageTitle = "Manajemen Paket Wisata - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight"><?php echo e(translate('Kelola Paket Wisata', 'Manage Tour Packages')); ?></h1>
            <p class="text-sm text-slate-500 mt-1.5"><?php echo e(translate('Manajemen paket wisata harian lengkap dengan harga dinamis, durasi, dan itinerary harian.', 'Manage packages, pricing, duration, and visual itineraries.')); ?></p>
        </div>
        <a href="/admin/tours/create" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-500 text-white font-bold text-sm rounded-xl shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> <?php echo e(translate('Tambah Paket', 'Add Tour Package')); ?>
        </a>
    </div>

    <!-- Tours Table -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3.5 px-4">Cover</th>
                        <th class="py-3.5 px-4">Judul Paket (ID)</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Lokasi</th>
                        <th class="py-3.5 px-4">Durasi</th>
                        <th class="py-3.5 px-4">Harga / Pax</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                    <?php if (empty($packages)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                                Belum ada paket wisata terdaftar.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($packages as $pkg): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4">
                                    <?php 
                                    $img = !empty($pkg['images']) ? $pkg['images'][0] : 'placeholder.jpg';
                                    ?>
                                    <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>" alt="cover" class="h-12 w-20 object-cover rounded-lg border border-slate-200 shadow-xs">
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-800"><?php echo htmlspecialchars($pkg['title_id']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border bg-teal-50 text-teal-600 border-teal-100 tracking-wider">
                                        <?php echo htmlspecialchars($pkg['category']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600"><?php echo htmlspecialchars($pkg['location_name']); ?></td>
                                <td class="py-3 px-4 text-slate-600"><?php echo e($pkg['duration_days']); ?> Hari</td>
                                <td class="py-3 px-4 text-teal-600 font-black"><?php echo e(format_rupiah($pkg['price'])); ?></td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="/tours/detail/<?php echo e($pkg['id']); ?>" target="_blank" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-teal-600 hover:border-teal-300 hover:bg-teal-50 transition-all shadow-xs" title="Lihat Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                        <a href="/admin/tours/edit/<?php echo e($pkg['id']); ?>" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-teal-300 hover:bg-teal-50 text-xs font-bold text-teal-700 transition-all shadow-xs">Edit</a>
                                        
                                        <form action="/admin/tours/delete/<?php echo e($pkg['id']); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket wisata ini?')" class="inline">
                                            <?php echo \App\Core\Csrf::field(); ?>
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-all shadow-xs">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
