<?php
// File: resources/views/category/create_category.php
$pageTitle = "Tambah Kategori Paket Wisata - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center space-x-2">
        <a href="/categories" class="text-sm text-slate-500 hover:text-teal-600 font-bold transition-colors flex items-center gap-1">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Kembali ke List Kategori
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Tambah Kategori Baru</h1>
    </div>

    <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
        <form action="/categories/create" method="POST" class="space-y-6">
            <?php echo \App\Core\Csrf::field(); ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="slug" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Slug Kategori (Unique)</label>
                    <input id="slug" name="slug" type="text" required placeholder="beach" value="<?php echo old('slug'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
                <div>
                    <label for="icon" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Icon Emoji (e.g. 🧗, 🏖️)</label>
                    <input id="icon" name="icon" type="text" placeholder="🏖️" value="<?php echo old('icon'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="name_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori (Bahasa)</label>
                    <input id="name_id" name="name_id" type="text" required placeholder="Pantai & Selam" value="<?php echo old('name_id'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
                <div>
                    <label for="name_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Kategori (English)</label>
                    <input id="name_en" name="name_en" type="text" required placeholder="Beach & Diving" value="<?php echo old('name_en'); ?>"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="description_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi (Bahasa)</label>
                    <textarea id="description_id" name="description_id" rows="4" placeholder="Deskripsi kategori..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo old('description_id'); ?></textarea>
                </div>
                <div>
                    <label for="description_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi (English)</label>
                    <textarea id="description_en" name="description_en" rows="4" placeholder="Category description..."
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo old('description_en'); ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center pt-2">
                <div>
                    <label for="sort_order" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Urutan Sorting</label>
                    <input id="sort_order" name="sort_order" type="number" value="0"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
                <div class="flex items-center space-x-2 pt-6">
                    <input id="is_active" name="is_active" type="checkbox" checked value="1"
                        class="h-4 w-4 rounded border-slate-300 bg-white text-teal-500 focus:ring-teal-500 cursor-pointer transition-all">
                    <label for="is_active" class="text-sm font-medium text-slate-700 cursor-pointer hover:text-teal-600 transition-colors">Aktifkan Kategori</label>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 flex justify-end">
                <button type="submit" 
                    class="px-6 py-3 rounded-xl bg-teal-500 text-white font-bold text-sm shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
