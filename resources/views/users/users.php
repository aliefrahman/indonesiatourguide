<?php
// File: resources/views/users/users.php
$pageTitle = "Manajemen Pengguna - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900"><?php echo translate('Kelola Pengguna Sistem', 'Manage Users'); ?></h1>
            <p class="text-xs text-slate-500 mt-1"><?php echo translate('Kelola data otentikasi pengguna, peran (admin, agent, guide, traveler), dan kontak telepon.', 'Manage user authentication records, role scopes, and contact info.'); ?></p>
        </div>
        <a href="/admin/users/create" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-teal-500 text-white shadow-md hover:bg-teal-600 font-bold text-xs rounded-xl transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i> <?php echo translate('Tambah Pengguna', 'Add User'); ?>
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-600 font-bold">
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Nomor HP</th>
                        <th class="py-3 px-4">Tgl Terdaftar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">Belum ada pengguna terdaftar.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900 flex items-center space-x-2">
                                    <span class="h-6 w-6 rounded-full bg-teal-50 border border-teal-100 flex items-center justify-center font-bold text-[10px] text-teal-600">
                                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                    </span>
                                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                                </td>
                                <td class="py-3 px-4 font-mono"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border
                                        <?php 
                                        if($user['role'] === 'admin') echo 'bg-rose-50 text-rose-700 border-rose-200';
                                        elseif($user['role'] === 'agent') echo 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        elseif($user['role'] === 'guide') echo 'bg-sky-50 text-sky-700 border-sky-200';
                                        else echo 'bg-slate-100 text-slate-500 border-slate-200';
                                        ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td class="py-3 px-4 text-slate-500"><?php echo date('d-m-Y', strtotime($user['created_at'])); ?></td>
                                <td class="py-2 px-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-teal-600 hover:bg-teal-50 hover:border-teal-300 font-bold transition-all">Edit</a>
                                        
                                        <!-- Cegah hapus diri sendiri -->
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <form action="/admin/users/delete/<?php echo $user['id']; ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" class="inline">
                                                <?php echo \App\Core\Csrf::field(); ?>
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-200 font-bold transition-all">Hapus</button>
                                            </form>
                                        <?php else: ?>
                                            <button disabled class="px-2.5 py-1.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-400 font-bold cursor-not-allowed">Self</button>
                                        <?php endif; ?>
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
