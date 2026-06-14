<?php
// File: resources/views/destinations/admin_list.php
$pageTitle = "Manajemen Destinasi Wisata - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900"><?php echo e(translate('Kelola Destinasi Wisata', 'Manage Destinations')); ?></h1>
            <p class="text-xs text-slate-500 mt-1"><?php echo e(translate('Manajemen destinasi wisata harian dengan koordinat geografis dan multi-upload cover.', 'Manage destinations, coordinates, and multi-upload cover images.')); ?></p>
        </div>
        <a href="/admin/destinations/create" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-teal-500 text-white shadow-md hover:bg-teal-600 font-bold text-xs rounded-xl transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> <?php echo e(translate('Tambah Destinasi', 'Add Destination')); ?>
        </a>
    </div>

    <!-- Destinations Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-600 font-bold">
                        <th class="py-3 px-4">Cover</th>
                        <th class="py-3 px-4">Nama (ID)</th>
                        <th class="py-3 px-4">Provinsi</th>
                        <th class="py-3 px-4">Koordinat</th>
                        <th class="py-3 px-4">Featured</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($destinations)): ?>
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada destinasi terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($destinations as $dest): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2 px-4">
                                    <?php 
                                    $img = !empty($dest['images']) ? $dest['images'][0] : 'placeholder.jpg';
                                    ?>
                                    <img src="/storage/uploads/<?php echo htmlspecialchars($img); ?>" alt="cover" class="h-10 w-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900"><?php echo htmlspecialchars($dest['name_id']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($dest['province']); ?></td>
                                <td class="py-3 px-4 font-mono text-[10px]">
                                    <?php echo e($dest['latitude'] ? $dest['latitude'].', '.$dest['longitude'] : '-'); ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold border <?php echo e($dest['is_featured'] ? 'bg-teal-50 text-teal-700 border-teal-200' : 'bg-slate-100 text-slate-500 border-slate-200'); ?>">
                                        <?php echo e($dest['is_featured'] ? 'FEATURED' : 'REGULAR'); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold border <?php echo e($dest['is_active'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'); ?>">
                                        <?php echo e($dest['is_active'] ? 'AKTIF' : 'NON-AKTIF'); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="/destinations/detail/<?php echo e($dest['slug']); ?>" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-300 font-bold transition-all"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                        <a href="/admin/destinations/edit/<?php echo e($dest['id']); ?>" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-300 font-bold transition-all">Edit</a>
                                        
                                        <form action="/admin/destinations/delete/<?php echo e($dest['id']); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus destinasi ini?')" class="inline">
                                            <?php echo \App\Core\Csrf::field(); ?>
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-200 font-bold transition-all">Hapus</button>
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
