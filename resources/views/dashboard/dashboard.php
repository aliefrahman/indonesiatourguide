<?php
// File: resources/views/dashboard/dashboard.php
$pageTitle = "Dashboard Panel - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Top Greeting Banner -->
    <div class="mb-10 bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-teal-50 rounded-full blur-3xl z-0 opacity-50 pointer-events-none"></div>
        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight"><?php echo e(translate('Selamat Datang Kembali', 'Welcome Back')); ?>, <span class="text-teal-600"><?php echo htmlspecialchars($name); ?></span>!</h1>
            <p class="text-sm text-slate-500 mt-1.5"><?php echo e(translate('Kelola pesanan, jadwal perjalanan, dan detail profil Anda di panel ini.', 'Manage your orders, travel itineraries, and profile details in this dashboard.')); ?></p>
        </div>
        <div class="relative z-10 flex items-center space-x-2">
            <span class="text-xs font-bold bg-teal-50 text-teal-600 border border-teal-100 rounded-full px-4 py-1.5 uppercase tracking-wider shadow-xs">
                Role: <?php echo htmlspecialchars($role); ?>
            </span>
        </div>
    </div>

    <!-- 1. ADMIN & AGENT VIEW -->
    <?php if ($role === 'admin' || $role === 'agent'): ?>
        
                <!-- Analytics Cards Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-teal-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block"><?php echo e(translate('Total Pendapatan', 'Total Revenue')); ?></span>
                            <span class="text-2xl font-black text-teal-600 mt-3 block"><?php echo e(format_rupiah($analytics['total_revenue'])); ?></span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block"><?php echo e(translate('Pesanan Aktif', 'Active Bookings')); ?></span>
                            <span class="text-2xl font-black text-slate-800 mt-3 block"><?php echo e($analytics['active_bookings']); ?></span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block"><?php echo e(translate('Pesanan Pending', 'Pending Bookings')); ?></span>
                            <span class="text-2xl font-black text-amber-500 mt-3 block"><?php echo e($analytics['pending_bookings']); ?></span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-110 transition-transform duration-500 ease-out"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block"><?php echo e(translate('Pesanan Gagal', 'Failed Bookings')); ?></span>
                            <span class="text-2xl font-black text-rose-500 mt-3 block"><?php echo e($analytics['failed_bookings']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- CRUD Shortcut Panel -->
                <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl mb-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="layout-grid" class="w-5 h-5 text-teal-500"></i>
                            <?php echo e(translate('Pintasan Manajemen Data', 'Data Management Shortcuts')); ?>
                        </h3>
                        <?php if ($role === 'admin'): ?>
                        <form action="/dashboard/sync" method="POST" class="inline-block" onsubmit="return confirm('Mulai sinkronisasi Smart Sync? Proses ini akan melakukan copy (UPSERT) dari SQLite ke MySQL.');">
                            <?php echo \App\Core\Csrf::field(); ?>
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Sync Database (SQLite -> MySQL)
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="/admin/tours" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-teal-300 hover:bg-teal-50 hover:shadow-sm transition-all group">
                            <div class="p-2 bg-white rounded-lg shadow-xs text-slate-600 group-hover:text-teal-600">💼</div>
                            <span class="font-bold text-sm text-slate-700 group-hover:text-teal-700"><?php echo e(translate('Paket Wisata', 'Tour Packages')); ?></span>
                        </a>
                        <a href="/admin/destinations" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-teal-300 hover:bg-teal-50 hover:shadow-sm transition-all group">
                            <div class="p-2 bg-white rounded-lg shadow-xs text-slate-600 group-hover:text-teal-600">🏔️</div>
                            <span class="font-bold text-sm text-slate-700 group-hover:text-teal-700"><?php echo e(translate('Destinasi', 'Destinations')); ?></span>
                        </a>
                        <?php if ($role === 'admin'): ?>
                                    <a href="/categories" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-teal-300 hover:bg-teal-50 hover:shadow-sm transition-all group">
                                        <div class="p-2 bg-white rounded-lg shadow-xs text-slate-600 group-hover:text-teal-600">🏷️</div>
                                        <span class="font-bold text-sm text-slate-700 group-hover:text-teal-700"><?php echo e(translate('Kategori', 'Categories')); ?></span>
                                    </a>
                                    <a href="/admin/users" class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-teal-300 hover:bg-teal-50 hover:shadow-sm transition-all group">
                                        <div class="p-2 bg-white rounded-lg shadow-xs text-slate-600 group-hover:text-teal-600">👥</div>
                                        <span class="font-bold text-sm text-slate-700 group-hover:text-teal-700"><?php echo e(translate('Pengguna', 'Users')); ?></span>
                                    </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bookings manual assignment table -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 mb-8 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 mb-6 uppercase tracking-wider flex items-center space-x-2">
                        <i data-lucide="people-arrows" class="text-teal-500 w-5 h-5"></i>
                        <span><?php echo e(translate('Daftar Pesanan & Penugasan Pemandu', 'Bookings & Tour Guide Allocations')); ?></span>
                    </h3>
            
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                    <th class="py-3.5 px-4">Invoice</th>
                                    <th class="py-3.5 px-4">Traveler</th>
                                    <th class="py-3.5 px-4">Paket Wisata</th>
                                    <th class="py-3.5 px-4">Tgl Berangkat</th>
                                    <th class="py-3.5 px-4">Total Price</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4">Pemandu Terpilih (Assign)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                                <?php if (empty($bookings)): ?>
                                            <tr>
                                                <td colspan="7" class="py-8 text-center text-slate-500">
                                                    <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                                                    Belum ada pemesanan masuk harian.
                                                </td>
                                            </tr>
                                <?php else: ?>
                                            <?php foreach ($bookings as $b): ?>
                                                        <tr class="hover:bg-slate-50 transition-colors">
                                                            <td class="py-3 px-4 font-mono font-bold text-slate-800"><?php echo htmlspecialchars($b['invoice_number']); ?></td>
                                                            <td class="py-3 px-4"><?php echo htmlspecialchars($b['traveler_name']); ?></td>
                                                            <td class="py-3 px-4"><?php echo htmlspecialchars($b['package_name_snapshot']); ?></td>
                                                            <td class="py-3 px-4"><?php echo e(date('d M Y', strtotime($b['travel_date']))); ?></td>
                                                            <td class="py-3 px-4 text-teal-600 font-black"><?php echo e(format_rupiah($b['total_price'])); ?></td>
                                                            <td class="py-3 px-4">
                                                                <?php
                                                                $statusClass = $b['payment_status'] === 'paid'
                                                                    ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                                                    : 'bg-amber-100 text-amber-800 border-amber-200';
                                                                ?>
                                                                <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold border tracking-wider <?php echo e($statusClass); ?>">
                                                                    <?php echo e(strtoupper($b['payment_status'])); ?>
                                                                </span>
                                                            </td>
                                                            <td class="py-2 px-4">
                                                                <form action="/dashboard/booking/assign" method="POST" class="flex items-center gap-1.5">
                                                                    <?php echo \App\Core\Csrf::field(); ?>
                                                                    <input type="hidden" name="booking_id" value="<?php echo e($b['id']); ?>">
                                                                    <select name="guide_id" onchange="this.form.submit()" 
                                                                        class="rounded-lg border border-slate-200 bg-white py-1.5 px-2.5 text-xs text-slate-700 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 focus:outline-none transition-all shadow-xs w-full max-w-[180px]">
                                                                        <option value="">-- Bebaskan Guide --</option>
                                                                        <?php foreach ($guides as $guide): ?>
                                                                                    <option value="<?php echo e($guide['id']); ?>" <?php echo e($b['guide_id'] == $guide['id'] ? 'selected' : ''); ?>>
                                                                                        <?php echo htmlspecialchars($guide['name']); ?>
                                                                                    </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </form>
                                                            </td>
                                                        </tr>
                                            <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. ADMIN ONLY EXTRA: REVIEWS CRM MODERATION & AUDIT LOGS -->
                <?php if ($role === 'admin'): ?>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                                <!-- CRM reviews Moderation -->
                                <div class="bg-white border border-slate-200 p-6 rounded-2xl overflow-hidden shadow-sm">
                                    <h3 class="text-sm font-bold text-slate-800 mb-5 uppercase tracking-wider flex items-center space-x-2">
                                        <i data-lucide="message-square" class="text-teal-500 w-5 h-5"></i>
                                        <span>💬 <?php echo e(translate('CRM - Moderasi Ulasan Pengunjung', 'CRM - Reviews Moderation')); ?></span>
                                    </h3>
                    
                                    <div class="space-y-4">
                                        <?php if (empty($reviews)): ?>
                                                    <p class="text-slate-500 text-sm py-4 text-center">Belum ada ulasan baru masuk.</p>
                                        <?php else: ?>
                                                    <?php foreach ($reviews as $rev): ?>
                                                                <div class="bg-slate-50 border border-slate-100 p-5 rounded-xl space-y-3 shadow-xs">
                                                                    <div class="flex justify-between items-start text-xs">
                                                                        <div>
                                                                            <span class="font-bold text-slate-800 text-sm block"><?php echo htmlspecialchars($rev['traveler_name']); ?></span>
                                                                            <span class="text-slate-500 mt-0.5 block">pada <span class="font-medium text-slate-700"><?php echo htmlspecialchars($rev['package_name_snapshot']); ?></span></span>
                                                                        </div>
                                                                        <div class="text-amber-400 flex">
                                                                            <?php for ($i = 0; $i < 5; $i++)
                                                                                echo ($i < $rev['rating'] ? '<i data-lucide="star" class="w-4 h-4 fill-current"></i>' : '<i data-lucide="star" class="w-4 h-4"></i>'); ?>
                                                                        </div>
                                                                    </div>
                                                                    <p class="text-sm text-slate-600 italic">"<?php echo htmlspecialchars($rev['comment']); ?>"</p>
                                    
                                                                    <div class="flex justify-between items-center pt-3 border-t border-slate-200/60 mt-3">
                                                                        <span class="text-[10px] font-bold tracking-wider px-2 py-1 rounded-md <?php echo e($rev['is_moderated'] ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'); ?>">
                                                                            <?php echo e($rev['is_moderated'] ? 'DISETUJUI' : 'BUTUH MODERASI'); ?>
                                                                        </span>
                                        
                                                                        <form action="/dashboard/reviews/moderate" method="POST" class="flex gap-2">
                                                                            <?php echo \App\Core\Csrf::field(); ?>
                                                                            <input type="hidden" name="review_id" value="<?php echo e($rev['id']); ?>">
                                                                            <?php if ($rev['is_moderated'] == 0): ?>
                                                                                        <button type="submit" name="action" value="approve" class="px-3 py-1.5 rounded-lg bg-teal-500 text-white text-xs font-bold hover:bg-teal-600 transition-colors">Setujui</button>
                                                                            <?php else: ?>
                                                                                        <button type="submit" name="action" value="reject" class="px-3 py-1.5 rounded-lg bg-white border border-rose-200 text-rose-600 text-xs font-bold hover:bg-rose-50 transition-colors">Sembunyikan</button>
                                                                            <?php endif; ?>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                    <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Security Audit Logs (Lapis 5 Keamanan) -->
                                <div class="bg-white border border-slate-200 p-6 rounded-2xl overflow-hidden shadow-sm flex flex-col h-full">
                                    <h3 class="text-sm font-bold text-slate-800 mb-5 uppercase tracking-wider flex items-center space-x-2">
                                        <i data-lucide="user-shield" class="text-teal-500 w-5 h-5"></i>
                                        <span><?php echo e(translate('Security Audit Logs', 'Security Activity Logs')); ?></span>
                                    </h3>
                    
                                    <div class="overflow-y-auto max-h-[360px] space-y-3 pr-2 font-mono text-[10px]">
                                        <?php if (empty($audit_logs)): ?>
                                                    <p class="text-slate-500 text-center py-4 font-sans text-sm">Logs kosong.</p>
                                        <?php else: ?>
                                                    <?php foreach ($audit_logs as $log): ?>
                                                                <div class="bg-slate-50 border border-slate-100 p-3.5 rounded-xl shadow-xs">
                                                                    <div class="flex justify-between items-center text-slate-400 mb-2">
                                                                        <span>[<?php echo e(date('d-m-Y H:i:s', strtotime($log['created_at']))); ?>]</span>
                                                                        <span class="text-teal-600 font-bold px-1.5 py-0.5 bg-teal-50 rounded text-[9px]"><?php echo htmlspecialchars($log['action_type']); ?></span>
                                                                    </div>
                                                                    <p class="text-slate-700 leading-relaxed font-sans text-xs"><?php echo htmlspecialchars($log['description']); ?></p>
                                                                    <div class="flex justify-between items-center text-[9px] text-slate-500 mt-2 border-t border-slate-200 pt-2">
                                                                        <span class="truncate max-w-[150px]">User: <?php echo htmlspecialchars($log['user_email'] ?? 'Guest'); ?></span>
                                                                            <span>IP: <?php echo htmlspecialchars($log['ip_address']); ?></span>
                                                                        </div>
                                                                    </div>
                                                        <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                </div>

                            </div>
                <?php endif; ?>

    <?php endif; ?>

    <!-- 3. GUIDE VIEW -->
    <?php if ($role === 'guide'): ?>
        
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
                    <!-- Availability Sync Panel -->
                    <div class="lg:col-span-1 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider flex items-center space-x-2">
                            <i data-lucide="calendar-check" class="text-teal-500 w-5 h-5"></i>
                            <span><?php echo e(translate('Kalender Ketersediaan', 'Calendar Availability')); ?></span>
                        </h3>
                
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            <?php echo e(translate(
                                'Matikan ketersediaan jika Anda sedang tidak aktif, sakit, atau cuti kerja agar operator tidak menugaskan Anda.',
                                'Deactivate availability if you are unavailable or on leave to prevent operator assignment.'
                            )); ?>
                        </p>

                        <?php if ($guide): ?>
                                    <form action="/dashboard/guide/availability" method="POST" class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-xs">
                                        <?php echo \App\Core\Csrf::field(); ?>
                        
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">Status Jadwal</span>
                                            <span class="text-[10px] <?php echo e($guide['is_available'] ? 'text-emerald-600' : 'text-rose-600'); ?> font-bold uppercase mt-0.5 block">
                                                <?php echo e($guide['is_available'] ? 'Aktif Bekerja (Tersedia)' : 'Cuti (Tidak Tersedia)'); ?>
                                            </span>
                                        </div>
                        
                                        <!-- Toggle Button -->
                                        <label for="is_avail" class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_available" id="is_avail" class="sr-only peer" 
                                                <?php echo e($guide['is_available'] ? 'checked' : ''); ?>
                                                onchange="this.form.submit()">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500 peer-checked:after:border-white"></div>
                                        </label>
                                    </form>
                        <?php endif; ?>
                    </div>

                    <!-- Guides assignments trips -->
                    <div class="lg:col-span-2 bg-white border border-slate-200 p-6 rounded-2xl shadow-sm">
                        <h3 class="text-sm font-bold text-slate-800 mb-5 uppercase tracking-wider flex items-center space-x-2">
                            <i data-lucide="route" class="text-teal-500 w-5 h-5"></i>
                            <span><?php echo e(translate('Tugas Perjalanan Aktif Anda', 'Your Assigned Trips')); ?></span>
                        </h3>
                
                        <div class="space-y-4">
                            <?php if (empty($trips)): ?>
                                        <div class="text-center py-10">
                                            <i data-lucide="map" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                            <p class="text-slate-500 text-sm">Belum ada tugas perjalanan yang diberikan.</p>
                                        </div>
                            <?php else: ?>
                                        <?php foreach ($trips as $trip): ?>
                                                    <div class="bg-slate-50 border border-slate-100 p-5 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-xs">
                                                        <div>
                                                            <span class="font-mono text-[10px] text-teal-600 font-bold bg-teal-50 px-2 py-0.5 rounded"><?php echo htmlspecialchars($trip['invoice_number']); ?></span>
                                                            <h4 class="font-bold text-slate-900 text-base mt-2"><?php echo htmlspecialchars($trip['package_name_snapshot']); ?></h4>
                                                            <div class="flex space-x-4 text-xs font-medium text-slate-500 mt-2">
                                                                <span class="flex items-center"><i data-lucide="calendar" class="w-3.5 h-3.5 mr-1 text-teal-500"></i> <?php echo e(date('d M Y', strtotime($trip['travel_date']))); ?></span>
                                                                <span class="flex items-center"><i data-lucide="users" class="w-3.5 h-3.5 mr-1 text-teal-500"></i> <?php echo e($trip['total_participants']); ?> Travelers</span>
                                                            </div>
                                                        </div>
                                                        <div class="border-t border-slate-200 sm:border-t-0 pt-4 sm:pt-0 w-full sm:w-auto text-left sm:text-right">
                                                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?php echo e(translate('Kontak Konsumen', 'Traveler Contact')); ?></span>
                                                            <span class="block font-bold text-sm text-slate-800 mt-1"><?php echo htmlspecialchars($trip['traveler_name']); ?></span>
                                                            <a href="https://wa.me/<?php echo e($trip['traveler_phone']); ?>" target="_blank"
                                                                class="inline-flex items-center justify-center gap-1.5 mt-2 bg-emerald-50 border border-emerald-200 text-emerald-600 text-[11px] font-bold px-3 py-1.5 rounded-full hover:bg-emerald-500 hover:text-white transition-colors w-full sm:w-auto">
                                                                <i data-lucide="whatsapp" class="w-3.5 h-3.5"></i> Chat WhatsApp
                                                            </a>
                                                        </div>
                                                    </div>
                                        <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

    <?php endif; ?>

    <!-- 4. TRAVELER VIEW -->
    <?php if ($role === 'traveler'): ?>
        
                <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 mb-6 uppercase tracking-wider flex items-center space-x-2">
                        <i data-lucide="history" class="text-teal-500 w-5 h-5"></i>
                        <span><?php echo e(translate('Riwayat Pemesanan Wisata Anda', 'Your Booking History')); ?></span>
                    </h3>
            
                    <div class="space-y-6">
                        <?php if (empty($bookings)): ?>
                                    <div class="text-center py-16">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                            <i data-lucide="suitcase-rolling" class="text-teal-500 w-10 h-10"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-800 mb-2">Belum ada riwayat perjalanan</h3>
                                        <p class="text-slate-500 text-sm max-w-sm mx-auto">Mulai rencanakan liburan impian Anda bersama IndoTour sekarang!</p>
                                        <a href="/tours" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-teal-500 text-white font-bold text-sm rounded-xl shadow-md hover:bg-teal-600 transition-colors">
                                            <i data-lucide="search" class="w-4 h-4"></i> Jelajahi Paket
                                        </a>
                                    </div>
                        <?php else: ?>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <?php foreach ($bookings as $b): ?>
                                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow group">
                            
                                                    <!-- Invoice Top Bar -->
                                                    <div class="flex flex-wrap justify-between items-start gap-2 border-b border-slate-200 pb-4 mb-4">
                                                        <div>
                                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Invoice</span>
                                                            <span class="font-mono font-bold text-slate-900"><?php echo htmlspecialchars($b['invoice_number']); ?></span>
                                                        </div>
                                                        <?php
                                                        $statusClass = $b['payment_status'] === 'paid'
                                                            ? 'bg-emerald-100 text-emerald-800 border-emerald-200'
                                                            : 'bg-amber-100 text-amber-800 border-amber-200';
                                                        ?>
                                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase border <?php echo e($statusClass); ?> tracking-wider">
                                                            <?php echo e(strtoupper($b['payment_status'])); ?>
                                                        </span>
                                                    </div>

                                                    <!-- Details -->
                                                    <h4 class="font-bold text-lg text-slate-900 group-hover:text-teal-600 transition-colors line-clamp-1 mb-4"><?php echo htmlspecialchars($b['package_name_snapshot']); ?></h4>
                                                    <div class="grid grid-cols-2 gap-4 mb-5 text-sm text-slate-600 font-medium">
                                                        <div class="flex items-center gap-2">
                                                            <i data-lucide="calendar" class="w-4 h-4 text-teal-500"></i>
                                                            <?php echo e(date('d M Y', strtotime($b['travel_date']))); ?>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i data-lucide="user" class="w-4 h-4 text-teal-500"></i>
                                                            <span class="truncate"><?php echo e(!empty($b['guide_name']) ? htmlspecialchars($b['guide_name']) : 'Cari Guide Otomatis'); ?></span>
                                                        </div>
                                                    </div>

                                                    <!-- Total -->
                                                    <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                                                        <div>
                                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Total Harga</span>
                                                            <span class="font-black text-slate-900 text-base"><?php echo e(format_rupiah($b['total_price'])); ?></span>
                                                        </div>
                                
                                                        <!-- Star review button if paid -->
                                                        <?php if ($b['payment_status'] === 'paid'): ?>
                                                                    <button onclick="toggleReviewForm('<?php echo e($b['id']); ?>', '<?php echo htmlspecialchars(addslashes($b['package_name_snapshot'])); ?>')"
                                                                        class="px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-teal-300 hover:bg-teal-50 text-xs font-bold text-teal-700 active:scale-[0.98] transition-all shadow-xs flex items-center gap-1.5">
                                                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i> Ulasan
                                                                    </button>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                    <?php endforeach; ?>
                                    </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CRM Review Modal Form (Traveler) -->
                <div id="review-modal" class="fixed inset-0 z-50 items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden p-4">
                    <div class="bg-white border border-slate-200 p-6 sm:p-8 rounded-2xl w-full max-w-md shadow-2xl relative">
                        <button type="button" onclick="document.getElementById('review-modal').classList.add('hidden'); document.getElementById('review-modal').classList.remove('flex');" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Tulis Ulasan Perjalanan</h3>
                        <p id="review-pkg-title" class="text-sm text-teal-600 font-semibold mb-6"></p>
                
                        <form action="/dashboard/reviews/create" method="POST" class="space-y-5">
                            <?php echo \App\Core\Csrf::field(); ?>
                            <input type="hidden" name="booking_id" id="review-booking-id" value="">
                    
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rating Bintang</label>
                                <select name="rating" class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3.5 text-sm text-slate-800 font-medium focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                                    <option value="5">⭐⭐⭐⭐⭐ 5 Bintang</option>
                                    <option value="4">⭐⭐⭐⭐ 4 Bintang</option>
                                    <option value="3">⭐⭐⭐ 3 Bintang</option>
                                    <option value="2">⭐⭐ 2 Bintang</option>
                                    <option value="1">⭐ 1 Bintang</option>
                                </select>
                            </div>

                            <div>
                                <label for="comment" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ulasan Anda</label>
                                <textarea id="comment" name="comment" rows="4" required
                                    placeholder="Ceritakan pengalaman menyenangkan perjalanan Anda dengan guide..."
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3 px-4 text-sm text-slate-800 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all"></textarea>
                            </div>

                            <button type="submit" 
                                class="w-full py-3 bg-teal-500 text-white font-bold text-sm rounded-xl shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    function toggleReviewForm(bookingId, packageTitle) {
                        document.getElementById('review-booking-id').value = bookingId;
                        document.getElementById('review-pkg-title').innerText = packageTitle;
                        const modal = document.getElementById('review-modal');
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                </script>

    <?php endif; ?>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
