<?php
// File: resources/views/auth/register.php
$pageTitle = "Daftar Traveler Baru - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-black tracking-tight text-slate-900">
            <?php echo e(translate('Daftar Traveler Baru', 'Register New Traveler')); ?>
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            <?php echo e(translate('Sudah punya akun?', 'Already have an account?')); ?>
            <a href="/login" class="font-bold text-teal-600 hover:text-teal-500 transition-colors">
                <?php echo e(translate('masuk disini', 'sign in here')); ?>
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white border border-slate-200 px-6 py-8 shadow-sm rounded-2xl sm:px-10">
            <form class="space-y-6" action="/register" method="POST">
                <?php echo \App\Core\Csrf::field(); ?>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"><?php echo e(translate('Nama Lengkap', 'Full Name')); ?></label>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input id="name" name="name" type="text" required 
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all" 
                            placeholder="Budi Setiawan" value="<?php echo e(old('name')); ?>">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all" 
                            placeholder="budi@email.com" value="<?php echo e(old('email')); ?>">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"><?php echo e(translate('Nomor Telepon', 'Phone Number')); ?></label>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <input id="phone" name="phone" type="text" 
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all" 
                            placeholder="08123456789" value="<?php echo e(old('phone')); ?>">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" 
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 py-3 px-4 text-sm font-bold text-white shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> <?php echo e(translate('Daftar Akun Baru', 'Register Account')); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
