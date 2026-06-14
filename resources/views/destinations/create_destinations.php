<?php
// File: resources/views/destinations/create_destinations.php
$pageTitle = "Tambah Destinasi Wisata Baru - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 py-10">
    <div class="mb-6 flex items-center space-x-2">
        <a href="/admin/destinations" class="text-xs text-slate-500 hover:text-teal-600 font-bold transition-colors">
            <i data-lucide="chevron-left" class="mr-1"></i> Kembali ke List Destinasi
        </a>
    </div>

    <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-8">Tambah Destinasi Baru</h1>

    <div class="bg-white border border-slate-200 px-6 py-6 shadow-sm rounded-2xl">
        <!-- Form upload file multipart -->
        <form action="/admin/destinations/create" method="POST" enctype="multipart/form-data" class="space-y-5">
            <?php echo \App\Core\Csrf::field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name_id"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Destinasi
                        (Bahasa)</label>
                    <input id="name_id" name="name_id" type="text" required placeholder="Raja Ampat"
                        value="<?php echo old('name_id'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="name_en"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Destinasi
                        (English)</label>
                    <input id="name_en" name="name_en" type="text" required placeholder="Raja Ampat Island"
                        value="<?php echo old('name_en'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="slug"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Slug URL
                        (Unique)</label>
                    <input id="slug" name="slug" type="text" required placeholder="raja-ampat"
                        value="<?php echo old('slug'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="province"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Provinsi</label>
                    <input id="province" name="province" type="text" required placeholder="Papua Barat"
                        value="<?php echo old('province'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="region"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kabupaten /
                        Wilayah (Opsional)</label>
                    <input id="region" name="region" type="text" placeholder="Kepulauan Raja Ampat"
                        value="<?php echo old('region'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="description_id"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi
                        Destinasi (Bahasa)</label>
                    <textarea id="description_id" name="description_id" rows="4" placeholder="Keindahan destinasi..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all"><?php echo old('description_id'); ?></textarea>
                </div>
                <div>
                    <label for="description_en"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi
                        Destinasi (English)</label>
                    <textarea id="description_en" name="description_en" rows="4"
                        placeholder="Beautiful destination details..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all"><?php echo old('description_en'); ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="latitude"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude (LBS
                        Koordinat)</label>
                    <input id="latitude" name="latitude" type="text" placeholder="-0.224158"
                        value="<?php echo old('latitude'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div>
                    <label for="longitude"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude (LBS
                        Koordinat)</label>
                    <input id="longitude" name="longitude" type="text" placeholder="130.490076"
                        value="<?php echo old('longitude'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
            </div>

            <!-- Multi-upload cover images -->
            <div>
                <label for="cover_images"
                    class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Gambar Cover
                    (Maksimal 5 Gambar)</label>
                <input id="cover_images" name="cover_images[]" type="file" multiple accept="image/*"
                    class="block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-teal-600 hover:file:bg-slate-200 file:font-bold file:text-xs">
                <p class="text-[10px] text-slate-500 mt-1.5">Format yang diizinkan: JPG, PNG, WEBP. Maks 5 file
                    sekaligus.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-2">
                <div>
                    <label for="sort_order"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan
                        Sorting</label>
                    <input id="sort_order" name="sort_order" type="number" value="0"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-xs text-slate-900 focus:border-teal-500 focus:bg-white focus:outline-none transition-all">
                </div>
                <div class="flex items-center space-x-2 pt-5">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1"
                        class="h-4 w-4 rounded border-slate-200 bg-slate-50 text-teal-500 focus:ring-teal-500">
                    <label for="is_featured"
                        class="text-xs font-bold text-slate-700 uppercase tracking-wider cursor-pointer">Featured (Top
                        Destinasi)</label>
                </div>
                <div class="flex items-center space-x-2 pt-5">
                    <input id="is_active" name="is_active" type="checkbox" checked value="1"
                        class="h-4 w-4 rounded border-slate-200 bg-slate-50 text-teal-500 focus:ring-teal-500">
                    <label for="is_active"
                        class="text-xs font-bold text-slate-700 uppercase tracking-wider cursor-pointer">Aktifkan
                        Destinasi</label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-teal-500 text-white font-bold text-xs shadow-sm hover:bg-teal-600 active:scale-[0.98] transition-all">
                    Simpan Destinasi
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>