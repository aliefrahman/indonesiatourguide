<?php
// File: resources/views/users/edit_users.php
$pageTitle = "Edit Pengguna - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center space-x-2">
        <a href="/admin/users" class="text-sm text-slate-500 hover:text-teal-600 font-bold transition-colors flex items-center gap-1">
            <i data-lucide="chevron-left" class="w-4 h-4"></i> Kembali ke List Pengguna
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Edit Pengguna: <span class="text-teal-600"><?php echo htmlspecialchars($user['name']); ?></span></h1>
    </div>

    <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
        <form action="/admin/users/edit/<?php echo $user['id']; ?>" method="POST" class="space-y-6">
            <?php echo \App\Core\Csrf::field(); ?>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input id="name" name="name" type="text" required placeholder="Ahmad Subarjo" value="<?php echo htmlspecialchars($user['name']); ?>"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <input id="email" name="email" type="email" required placeholder="ahmad@test.com" value="<?php echo htmlspecialchars($user['email']); ?>"
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
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Password (Kosongkan jika tidak diubah)</label>
                    <input id="password" name="password" type="password" placeholder="••••••••"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Peran / Role</label>
                    <select id="role" name="role" required
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                        <option value="traveler" <?php echo $user['role'] === 'traveler' ? 'selected' : ''; ?>>Traveler (Visitor)</option>
                        <option value="guide" <?php echo $user['role'] === 'guide' ? 'selected' : ''; ?>>Tour Guide (Pemandu)</option>
                        <option value="agent" <?php echo $user['role'] === 'agent' ? 'selected' : ''; ?>>Travel Agent Partner (Partner)</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrator (Admin)</option>
                    </select>
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

<?php require __DIR__ . '/../layouts/footer.php'; ?>
