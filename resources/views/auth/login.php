<?php
// File: resources/views/auth/login.php
$pageTitle = "Masuk ke Akun Anda - IndoTour";
require __DIR__ . '/../layouts/header.php';
?>

<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-black tracking-tight text-slate-900">
            <?php echo translate('Masuk ke Akun', 'Sign In to Account'); ?>
        </h2>
        <p class="mt-2 text-center text-sm text-slate-500">
            <?php echo translate('Atau', 'Or'); ?>
            <a href="/register" class="font-bold text-teal-600 hover:text-teal-500 transition-colors">
                <?php echo translate('daftar traveler baru disini', 'register a new traveler here'); ?>
            </a>
        </p>
    </div>

    <!-- Login Card -->
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white border border-slate-200 px-6 py-8 shadow-sm rounded-2xl sm:px-10">

            <!-- Brute force warning alert -->
            <div
                class="mb-5 flex items-start space-x-2.5 rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-700">
                <i data-lucide="triangle-exclamation" class="mt-0.5 shrink-0 text-amber-500"></i>
                <p>
                    <?php echo translate(
                        'Demi keamanan, batas percobaan masuk salah adalah 5 kali. Jika melebihi batas, akun Anda akan dikunci selama 15 menit.',
                        'For security, login is limited to 5 attempts. Exceeding this limit will lock your access for 15 minutes.'
                    ); ?>
                </p>
            </div>

            <form class="space-y-6" action="/login" method="POST">
                <!-- CSRF Token Field (Lapis 3 Keamanan) -->
                <?php echo \App\Core\Csrf::field(); ?>

                <div>
                    <label for="email"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email
                        Address</label>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all"
                            placeholder="nama@email.com" value="<?php echo old('email'); ?>">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <div class="text-sm">
                            <a href="/forgot"
                                class="font-bold text-teal-600 hover:text-teal-500 text-xs transition-colors">
                                <?php echo translate('Lupa sandi?', 'Forgot password?'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-slate-800 font-medium placeholder-slate-400 focus:border-teal-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-teal-500 sm:text-sm transition-all"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 py-3 px-4 text-sm font-bold text-white shadow-md hover:bg-teal-600 active:scale-[0.98] transition-all">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <?php echo translate('Masuk Sekarang', 'Sign In'); ?>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Accounts -->
            <div class="mt-8 border-t border-slate-100 pt-6">
                <p class="text-xs text-slate-500 text-center mb-3">Demo Accounts (Password: <code>password</code>)</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button onclick="fillLogin('admin@test.com')"
                        class="text-left bg-slate-50 border border-slate-200 p-2.5 rounded-xl hover:border-teal-300 hover:bg-white shadow-sm transition-all">
                        <p class="font-bold text-teal-600">Admin</p>
                        <p class="text-[10px] text-slate-500">admin@test.com</p>
                    </button>
                    <button onclick="fillLogin('agent@test.com')"
                        class="text-left bg-slate-50 border border-slate-200 p-2.5 rounded-xl hover:border-emerald-300 hover:bg-white shadow-sm transition-all">
                        <p class="font-bold text-emerald-600">Agent Partner</p>
                        <p class="text-[10px] text-slate-500">agent@test.com</p>
                    </button>
                    <button onclick="fillLogin('guide@test.com')"
                        class="text-left bg-slate-50 border border-slate-200 p-2.5 rounded-xl hover:border-sky-300 hover:bg-white shadow-sm transition-all">
                        <p class="font-bold text-sky-600">Guide</p>
                        <p class="text-[10px] text-slate-500">guide@test.com</p>
                    </button>
                    <button onclick="fillLogin('traveler@test.com')"
                        class="text-left bg-slate-50 border border-slate-200 p-2.5 rounded-xl hover:border-purple-300 hover:bg-white shadow-sm transition-all">
                        <p class="font-bold text-purple-600">Traveler</p>
                        <p class="text-[10px] text-slate-500">traveler@test.com</p>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>