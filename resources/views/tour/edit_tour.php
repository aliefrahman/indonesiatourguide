<?php
// File: resources/views/tour/edit_tour.php
$pageTitle = "Edit Paket Wisata - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center space-x-2">
        <a href="/admin/tours" class="text-sm text-slate-500 hover:text-teal-600 font-bold transition-colors flex items-center gap-1">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Kembali ke List Paket Wisata
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Edit Paket Wisata: <span class="text-teal-600"><?php echo htmlspecialchars($package['title_id']); ?></span></h1>
    </div>

    <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
        <form action="/admin/tours/edit/<?php echo $package['id']; ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?php echo \App\Core\Csrf::field(); ?>

            <!-- Basic Info Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-900 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-teal-500"></i>
                    <span>1. Informasi Utama Paket</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="title_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Paket (Bahasa)</label>
                        <input id="title_id" name="title_id" type="text" required placeholder="Tur Pendakian Bromo 2 Hari" value="<?php echo htmlspecialchars($package['title_id']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="title_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Paket (English)</label>
                        <input id="title_en" name="title_en" type="text" required placeholder="Bromo Hiking Tour 2 Days" value="<?php echo htmlspecialchars($package['title_en']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="description_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Rinci (Bahasa)</label>
                        <textarea id="description_id" name="description_id" rows="4" required placeholder="Detail deskripsi paket wisata..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo htmlspecialchars($package['description_id']); ?></textarea>
                    </div>
                    <div>
                        <label for="description_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Rinci (English)</label>
                        <textarea id="description_en" name="description_en" rows="4" required placeholder="Detailed package description..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo htmlspecialchars($package['description_en']); ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="price" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Paket (Rp / pax)</label>
                        <input id="price" name="price" type="number" required placeholder="750000" value="<?php echo htmlspecialchars($package['price']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="duration_days" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Durasi (Hari)</label>
                        <input id="duration_days" name="duration_days" type="number" min="1" required value="<?php echo htmlspecialchars($package['duration_days']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori Paket</label>
                        <select id="category" name="category" required 
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                            <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo $package['category'] === $cat['slug'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars(translate($cat['name_id'], $cat['name_en'])); ?>
                                        </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="location_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lokasi Soft-Ref</label>
                        <input id="location_name" name="location_name" type="text" required placeholder="Gunung Bromo" value="<?php echo htmlspecialchars($package['location_name']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Latitude (LBS)</label>
                        <input id="latitude" name="latitude" type="text" placeholder="-7.942493" value="<?php echo htmlspecialchars($package['latitude'] ?? ''); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="longitude" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Longitude (LBS)</label>
                        <input id="longitude" name="longitude" type="text" placeholder="112.953012" value="<?php echo htmlspecialchars($package['longitude'] ?? ''); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                </div>

                <!-- Current image -->
                <?php if (!empty($package['images'])): ?>
                            <div class="flex items-center space-x-4 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                                <img src="/storage/uploads/<?php echo htmlspecialchars($package['images'][0]); ?>" alt="cover" class="h-16 w-24 object-cover rounded-lg border border-slate-200 shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">Gambar Cover Aktif</span>
                                    <span class="text-[10px] text-slate-500"><?php echo htmlspecialchars($package['images'][0]); ?></span>
                                </div>
                            </div>
                <?php endif; ?>

                <div>
                    <label for="cover_image" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Gambar Cover Paket (Opsional)</label>
                    <input id="cover_image" name="cover_image" type="file" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 file:font-bold file:transition-colors">
                </div>
            </div>

            <!-- Itinerary Timeline Section (Dinamis JavaScript) -->
            <div class="space-y-6 pt-8 mt-8 border-t border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="map-pinned" class="w-5 h-5 text-teal-500"></i>
                        <span>2. Rencana Perjalanan Harian</span>
                    </h3>
                    <button type="button" onclick="addItineraryRow()" 
                        class="px-3 py-2 bg-white border border-slate-200 text-teal-600 rounded-lg text-xs font-bold hover:bg-teal-50 hover:border-teal-300 transition-all shadow-sm flex items-center gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Aktivitas
                    </button>
                </div>

                <!-- Itinerary Form Rows Container -->
                <div id="itinerary-rows" class="space-y-5">
                    <?php if (empty($itineraries)): ?>
                                <!-- Default Row 1 jika kosong -->
                                <div class="bg-slate-50 border border-slate-100 p-5 rounded-xl space-y-4 relative itinerary-row shadow-sm group">
                                    <button type="button" onclick="removeRow(this)" 
                                        class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 text-[11px] uppercase tracking-wider font-bold transition-colors flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                            
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hari ke-</label>
                                            <input type="number" name="iti_day[]" value="1" min="1" required
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                            <input type="time" name="iti_start[]" value="08:00" required
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                                            <input type="time" name="iti_end[]" value="10:00" required
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Aktivitas (ID)</label>
                                            <input type="text" name="iti_activity_id[]" placeholder="Penjemputan di Titik Kumpul" required
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Aktivitas (EN)</label>
                                            <input type="text" name="iti_activity_en[]" placeholder="Pickup at Meeting Point" required
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Tambahan (ID)</label>
                                            <input type="text" name="iti_notes_id[]" placeholder="Harap memakai pakaian tebal."
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Tambahan (EN)</label>
                                            <input type="text" name="iti_notes_en[]" placeholder="Please wear warm clothes."
                                                class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                        </div>
                                    </div>
                                </div>
                    <?php else: ?>
                                <?php foreach ($itineraries as $iti): ?>
                                            <div class="bg-slate-50 border border-slate-100 p-5 rounded-xl space-y-4 relative itinerary-row shadow-sm group">
                                                <button type="button" onclick="removeRow(this)" 
                                                    class="absolute top-4 right-4 text-slate-400 hover:text-rose-500 text-[11px] uppercase tracking-wider font-bold transition-colors flex items-center gap-1">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                                </button>
                                
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hari ke-</label>
                                                        <input type="number" name="iti_day[]" value="<?php echo $iti['day_number']; ?>" min="1" required
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                                                        <input type="time" name="iti_start[]" value="<?php echo date('H:i', strtotime($iti['time_start'])); ?>" required
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                                                        <input type="time" name="iti_end[]" value="<?php echo date('H:i', strtotime($iti['time_end'])); ?>" required
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Aktivitas (ID)</label>
                                                        <input type="text" name="iti_activity_id[]" placeholder="Aktivitas..." value="<?php echo htmlspecialchars($iti['activity_id']); ?>" required
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Aktivitas (EN)</label>
                                                        <input type="text" name="iti_activity_en[]" placeholder="Activity..." value="<?php echo htmlspecialchars($iti['activity_en']); ?>" required
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Tambahan (ID)</label>
                                                        <input type="text" name="iti_notes_id[]" placeholder="..." value="<?php echo htmlspecialchars($iti['notes_id'] ?? ''); ?>"
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Catatan Tambahan (EN)</label>
                                                        <input type="text" name="iti_notes_en[]" placeholder="..." value="<?php echo htmlspecialchars($iti['notes_en'] ?? ''); ?>"
                                                            class="block w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-700 font-medium focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-all">
                                                    </div>
                                                </div>
                                            </div>
                                <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 flex justify-end">
                <button type="submit" 
                    class="px-6 py-3 rounded-xl bg-teal-500 text-white font-bold text-sm shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Dynamic rows script -->
<script>
    function addItineraryRow() {
        const container = document.getElementById('itinerary-rows');
        const defaultRow = container.querySelector('.itinerary-row');
        const newRow = defaultRow.cloneNode(true);
        
        // Bersihkan data form clone
        newRow.querySelectorAll('input').forEach(input => {
            if(input.name !== 'iti_day[]' && input.name !== 'iti_start[]' && input.name !== 'iti_end[]') {
                input.value = '';
            }
        });
        
        container.appendChild(newRow);
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.itinerary-row');
        if (rows.length > 1) {
            btn.closest('.itinerary-row').remove();
        } else {
            alert('Wajib menyertakan minimal 1 rencana aktivitas harian.');
        }
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>