<?php
// File: resources/views/category/categories.php
$pageTitle = "Manajemen Kategori - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900"><?php echo e(translate('Kelola Kategori Paket Wisata', 'Manage Categories')); ?></h1>
            <p class="text-xs text-slate-500 mt-1"><?php echo e(translate('Kelola slug unik, nama dwibahasa, ikon emoji, dan urutan sorting kategori.', 'Manage unique slugs, bilingual names, icons, and categories order.')); ?></p>
        </div>
        <a href="/categories/create" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-teal-500 text-white shadow-md hover:bg-teal-600 font-bold text-xs rounded-xl transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> <?php echo e(translate('Tambah Kategori', 'Add Category')); ?>
        </a>
    </div>

    <!-- Categories Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-600 font-bold">
                        <th class="py-3 px-4">Icon</th>
                        <th class="py-3 px-4">Slug / Key</th>
                        <th class="py-3 px-4">Nama (ID)</th>
                        <th class="py-3 px-4">Nama (EN)</th>
                        <th class="py-3 px-4">Order</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada kategori terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-xl"><?php echo htmlspecialchars($cat['icon']); ?></td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-900"><?php echo htmlspecialchars($cat['slug']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($cat['name_id']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($cat['name_en']); ?></td>
                                <td class="py-3 px-4"><?php echo e($cat['sort_order']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold border <?php echo e($cat['is_active'] ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'); ?>">
                                        <?php echo e($cat['is_active'] ? 'AKTIF' : 'NON-AKTIF'); ?>
                                    </span>
                                </td>
                                <td class="py-2 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="/categories/edit/<?php echo e($cat['id']); ?>" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-300 font-bold transition-all">Edit</a>
                                        
                                        <form action="/categories/delete/<?php echo e($cat['id']); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
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
