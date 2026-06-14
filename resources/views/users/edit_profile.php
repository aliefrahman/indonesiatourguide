<?php
// File: resources/views/users/edit_profile.php
$pageTitle = "Edit Profil Saya - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Edit Profil Saya</h1>
    </div>

    <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
        <form action="/profile/edit" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?php echo \App\Core\Csrf::field(); ?>

            <!-- Basic Profile Card -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-900 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-teal-500"></i>
                    <span>1. Informasi Akun Dasar</span>
                </h3>
                
                <div class="flex flex-col sm:flex-row items-center gap-5 pb-2">
                    <div class="h-20 w-20 rounded-full bg-teal-50 border border-teal-100 flex items-center justify-center overflow-hidden relative shadow-sm shrink-0">
                        <?php if (!empty($user['avatar'])): ?>
                                <img src="/storage/uploads/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="h-full w-full object-cover">
                        <?php else: ?>
                                <span class="text-3xl font-bold text-teal-600"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-grow w-full sm:w-auto">
                        <label for="avatar" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Foto Profil (Avatar)</label>
                        <input id="avatar" name="avatar" type="file" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 file:font-bold file:transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($user['name']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon (HP)</label>
                        <input id="phone" name="phone" type="text" placeholder="0812XXXXXXXX" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email (Permanen)</label>
                        <input type="email" disabled value="<?php echo htmlspecialchars($user['email']); ?>"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-100 py-2.5 px-3.5 text-sm text-slate-500 font-medium cursor-not-allowed">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Password (Kosongkan jika tidak diubah)</label>
                        <input id="password" name="password" type="password" placeholder="••••••••"
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- 2. TOUR GUIDE SPECIFIC SECTION (Only if user role is guide) -->
            <?php if ($user['role'] === 'guide' && $guideDetail): ?>
                    <div class="space-y-6 pt-8 border-t border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i data-lucide="briefcase" class="w-5 h-5 text-teal-500"></i>
                            <span>2. Detail Profil Pemandu Wisata</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="license_number" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Lisensi / Sertifikasi</label>
                                <input id="license_number" name="license_number" type="text" placeholder="LIC-XXXXX" value="<?php echo htmlspecialchars($guideDetail['license_number'] ?? ''); ?>"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                            </div>
                        
                            <div>
                                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Bahasa yang Dikuasai</span>
                                <div class="flex items-center space-x-5">
                                    <?php
                                    $langs = $guideDetail['languages_spoken'];
                                    ?>
                                    <label class="inline-flex items-center text-sm font-medium text-slate-700 cursor-pointer hover:text-teal-600 transition-colors">
                                        <input type="checkbox" name="languages[]" value="id" <?php echo in_array('id', $langs) ? 'checked' : ''; ?>
                                            class="h-4 w-4 rounded border-slate-300 bg-white text-teal-500 focus:ring-teal-500 mr-2 cursor-pointer transition-all">
                                        Bahasa Indonesia
                                    </label>
                                    <label class="inline-flex items-center text-sm font-medium text-slate-700 cursor-pointer hover:text-teal-600 transition-colors">
                                        <input type="checkbox" name="languages[]" value="en" <?php echo in_array('en', $langs) ? 'checked' : ''; ?>
                                            class="h-4 w-4 rounded border-slate-300 bg-white text-teal-500 focus:ring-teal-500 mr-2 cursor-pointer transition-all">
                                        English
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="bio_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Biografi Singkat (Bahasa)</label>
                                <textarea id="bio_id" name="bio_id" rows="4" required placeholder="Ceritakan tentang diri Anda..."
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo htmlspecialchars($guideDetail['bio_id']); ?></textarea>
                            </div>
                            <div>
                                <label for="bio_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Biografi Singkat (English)</label>
                                <textarea id="bio_en" name="bio_en" rows="4" required placeholder="Tell travelers about yourself..."
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-3.5 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"><?php echo htmlspecialchars($guideDetail['bio_en']); ?></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="skills_id" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keahlian (Bahasa) - Pisahkan dengan koma</label>
                                <input id="skills_id" name="skills_id" type="text" placeholder="Snorkeling, Trekking, Fotografi" value="<?php echo htmlspecialchars($guideDetail['skills_id'] ?? ''); ?>"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                            </div>
                            <div>
                                <label for="skills_en" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keahlian (English) - Separate with comma</label>
                                <input id="skills_en" name="skills_en" type="text" placeholder="Snorkeling, Trekking, Photography" value="<?php echo htmlspecialchars($guideDetail['skills_en'] ?? ''); ?>"
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                            </div>
                        </div>
                    </div>
            <?php endif; ?>

            <div class="pt-8 border-t border-slate-200 flex justify-end">
                <button type="submit" 
                    class="px-6 py-3 rounded-xl bg-teal-500 text-white font-bold text-sm shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
