<?php
// File: resources/views/destinations/edit_destinations.php
$pageTitle = "Edit Destinasi Wisata - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 py-10">
    <div class="mb-6 flex items-center space-x-2">
        <a href="/admin/destinations" class="text-xs text-slate-500 hover:text-teal-600 font-bold transition-colors">
            <i data-lucide="chevron-left" class="mr-1"></i> Kembali ke List Destinasi
        </a>
    </div>

    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-8">Edit Destinasi:
        <?php echo htmlspecialchars($destination['name_id']); ?></h1>

    <div class="bg-white border border-slate-200 px-6 py-6 shadow-sm rounded-2xl">
        <form action="/admin/destinations/edit/<?php echo e($destination['id']); ?>" method="POST"
            enctype="multipart/form-data" class="space-y-5">
            <?php echo \App\Core\Csrf::field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name_id"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Destinasi
                        (Bahasa)</label>
                    <input id="name_id" name="name_id" type="text" required placeholder="Raja Ampat"
                        value="<?php echo htmlspecialchars($destination['name_id']); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="name_en"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Destinasi
                        (English)</label>
                    <input id="name_en" name="name_en" type="text" required placeholder="Raja Ampat Island"
                        value="<?php echo htmlspecialchars($destination['name_en']); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="slug"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slug URL
                        (Unique)</label>
                    <input id="slug" name="slug" type="text" required placeholder="raja-ampat"
                        value="<?php echo htmlspecialchars($destination['slug']); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="province"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Provinsi</label>
                    <input id="province" name="province" type="text" required placeholder="Papua Barat"
                        value="<?php echo htmlspecialchars($destination['province']); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="region"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kabupaten /
                        Wilayah (Opsional)</label>
                    <input id="region" name="region" type="text" placeholder="Kepulauan Raja Ampat"
                        value="<?php echo htmlspecialchars($destination['region'] ?? ''); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="description_id"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi
                        Destinasi (Bahasa)</label>
                    <textarea id="description_id" name="description_id" rows="4" placeholder="Keindahan destinasi..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all"><?php echo htmlspecialchars($destination['description_id'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="description_en"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi
                        Destinasi (English)</label>
                    <textarea id="description_en" name="description_en" rows="4"
                        placeholder="Beautiful destination details..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all"><?php echo htmlspecialchars($destination['description_en'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude (LBS
                        Koordinat)</label>
                    <input id="latitude" name="latitude" type="text" placeholder="-0.224158"
                        value="<?php echo htmlspecialchars($destination['latitude'] ?? ''); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="longitude"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude (LBS
                        Koordinat)</label>
                    <input id="longitude" name="longitude" type="text" placeholder="130.490076"
                        value="<?php echo htmlspecialchars($destination['longitude'] ?? ''); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <!-- Current Images -->
            <?php if (!empty($destination['images'])): ?>
                <div>
                    <span class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Gambar Cover Saat
                        Ini:</span>
                    <div class="flex gap-4 flex-wrap">
                        <?php foreach ($destination['images'] as $index => $img):
                            $imgExists = file_exists(__DIR__ . '/../../../storage/uploads/' . basename($img));
                            $imgSrc = $imgExists ? '/storage/uploads/' . htmlspecialchars($img) : '/storage/uploads/placeholder.jpg';
                            ?>
                            <div class="relative group flex flex-col items-center">
                                <img src="<?php echo e($imgSrc); ?>" alt="cover"
                                    class="h-16 w-24 object-cover rounded-xl border border-slate-200">
                                <div class="mt-1 flex items-center space-x-1">
                                    <input type="checkbox" name="delete_images[]" value="<?php echo htmlspecialchars($img); ?>"
                                        id="del_img_<?php echo e($index); ?>"
                                        class="h-3.5 w-3.5 rounded border-slate-300 bg-slate-50 text-rose-500 focus:ring-rose-500">
                                    <label for="del_img_<?php echo e($index); ?>"
                                        class="text-[10px] font-bold text-slate-600 uppercase tracking-wider cursor-pointer hover:text-rose-600 transition-colors">
                                        Hapus
                                        <?php if (!$imgExists)
                                            echo '<span class="text-rose-500 font-bold">(Broken)</span>'; ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Multi-upload cover images -->
            <div>
                <label for="cover_images"
                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Gambar Baru
                    (Menambahkan Gambar, Maksimal 5 Gambar)</label>
                <input id="cover_images" name="cover_images[]" type="file" multiple accept="image/*"
                    class="block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-teal-600 hover:file:bg-slate-200 file:font-bold file:text-xs">
                <p class="text-[10px] text-slate-500 mt-1.5">Format yang diizinkan: JPG, PNG, WEBP. Mengunggah gambar
                    baru akan digabungkan dengan gambar lama (maks 5).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-2">
                <div>
                    <label for="sort_order"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan
                        Sorting</label>
                    <input id="sort_order" name="sort_order" type="number"
                        value="<?php echo e($destination['sort_order']); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div class="flex items-center space-x-2 pt-5">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" <?php echo e($destination['is_featured'] ? 'checked' : ''); ?>
                        class="h-4 w-4 rounded border-slate-200 bg-slate-50 text-teal-500 focus:ring-teal-500">
                    <label for="is_featured"
                        class="text-xs font-bold text-slate-700 uppercase tracking-wider cursor-pointer">Featured (Top
                        Destinasi)</label>
                </div>
                <div class="flex items-center space-x-2 pt-5">
                    <input id="is_active" name="is_active" type="checkbox" value="1" <?php echo e($destination['is_active'] ? 'checked' : ''); ?>
                        class="h-4 w-4 rounded border-slate-200 bg-slate-50 text-teal-500 focus:ring-teal-500">
                    <label for="is_active"
                        class="text-xs font-bold text-slate-700 uppercase tracking-wider cursor-pointer">Aktifkan
                        Destinasi</label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-teal-500 text-white font-bold text-xs shadow-sm hover:bg-teal-600 active:scale-[0.98] transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>