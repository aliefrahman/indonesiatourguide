<?php
// File: resources/views/layouts/header.php
$authUser = auth();
$currentLang = $_SESSION['lang'] ?? 'id';
$isHome = ($_SERVER['REQUEST_URI'] === '/' || strpos($_SERVER['REQUEST_URI'], '/?') === 0 || $_SERVER['REQUEST_URI'] === '/index.php');

$navClasses = $isHome 
    ? 'absolute left-0 bg-transparent border-transparent nav-transparent-mode transition-all duration-300' 
    : 'sticky bg-white/95 backdrop-blur-md border-b border-slate-100 transition-shadow duration-300';
?>
<!DOCTYPE html>
<html lang="<?php echo e($currentLang); ?>" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Indonesian Tour Guide - Platform Digital Pemandu Wisata Lokal'); ?></title>
    <!-- Google Fonts — Plus Jakarta Sans + Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/styles.css?v=<?php echo time(); ?>">
    <!-- Custom Design System (light theme, classes, tokens) -->
    <link rel="stylesheet" href="/assets/css/custom.css?v=<?php echo time(); ?>">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest" defer></script>
</head>

<body class="h-full flex flex-col min-h-screen bg-slate-50 text-slate-800 antialiased">

    <!-- NAVBAR — Transparent overlay on homepage, solid glassmorphic header on other pages -->
    <nav id="main-nav"
        class="top-0 z-50 w-full <?php echo e($navClasses); ?>">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-4">

                <!-- ── Logo ── -->
                <a href="/" class="flex items-center gap-2 shrink-0" aria-label="IndoTour Home">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-cyan-500 to-teal-600 text-white shadow-sm">
                        <i data-lucide="compass" class="h-4 w-4"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight">
                        <span class="text-slate-900 logo-text-brand">Indonesia</span><span class="text-gradient">Tour
                            Guide</span>
                    </span>
                </a>

                <!-- ── Desktop Navigation Links ── -->
                <div class="hidden md:flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider">
                    <a href="/"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                        <?php echo e(translate('Beranda', 'Home')); ?>
                    </a>
                    <a href="/tours"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                        <?php echo e(translate('Paket Wisata', 'Tours')); ?>
                    </a>
                    <a href="/#b2b-section"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                        <?php echo e(translate('Kemitraan B2B', 'B2B Partnership')); ?>
                    </a>
                    <a href="#"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                        <?php echo e(translate('FAQ', 'FAQs')); ?>
                    </a>
                    <a href="#contact"
                        class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                        <?php echo e(translate('Kontak', 'Contact')); ?>
                    </a>
                    <?php if ($authUser && ($authUser->role === 'admin' || $authUser->role === 'agent')): ?>
                        <a href="/dashboard"
                            class="px-3 py-2 rounded-lg text-slate-600 hover:text-teal-600 hover:bg-teal-50/50 transition-all">
                            Admin
                        </a>
                    <?php endif; ?>
                </div>

                <!-- ── Right Side: Lang + Auth ── -->
                <div class="flex items-center gap-3">

                    <!-- Language Toggle -->
                    <div
                        class="hidden sm:flex items-center bg-slate-100 rounded-full p-0.5 text-xs font-bold lang-toggle-container">
                        <a href="/lang/id"
                            class="px-2.5 py-1 rounded-full transition-all lang-toggle-link <?php echo e($currentLang === 'id' ? 'bg-white text-teal-600 shadow-sm active-lang' : 'text-slate-500 hover:text-slate-700'); ?>">ID</a>
                        <a href="/lang/en"
                            class="px-2.5 py-1 rounded-full transition-all lang-toggle-link <?php echo e($currentLang === 'en' ? 'bg-white text-teal-600 shadow-sm active-lang' : 'text-slate-500 hover:text-slate-700'); ?>">EN</a>
                    </div>

                    <?php if ($authUser): ?>
                        <!-- User Profile Dropdown -->
                        <div class="relative" id="user-menu-wrapper">
                                <button type="button" id="user-menu-button"
                                class="flex items-center gap-2 rounded-full border border-slate-200 bg-white pl-1 pr-3 py-1 text-sm font-semibold text-slate-700 hover:border-teal-400 hover:text-teal-600 transition-all shadow-sm">
                                <span
                                    class="h-6 w-6 rounded-full bg-linear-to-br from-cyan-500 to-teal-600 text-white flex items-center justify-center font-bold text-[10px]">
                                    <?php echo e(strtoupper(substr($authUser->name, 0, 1))); ?>
                                </span>
                                <span class="max-w-[90px] truncate"><?php echo htmlspecialchars($authUser->name); ?></span>
                                <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-slate-400"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="user-dropdown"
                                class="hidden absolute right-0 mt-2 w-52 rounded-xl border border-slate-100 bg-white p-1.5 shadow-xl ring-1 ring-black/5">
                                <div class="px-3 py-2 mb-1 text-[10px] text-slate-400 border-b border-slate-100">
                                    <?php echo e(translate('Masuk sebagai:', 'Logged in as:')); ?>
                                    <span class="font-bold text-teal-600"><?php echo e(strtoupper($authUser->role)); ?></span>
                                </div>
                                <a href="/dashboard"
                                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                    <i data-lucide="layout-dashboard" class="h-4 w-4"></i> Dashboard
                                </a>
                                <a href="/profile/edit"
                                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                    <i data-lucide="user-round-pen" class="h-4 w-4"></i>
                                    <?php echo e(translate('Edit Profil', 'Edit Profile')); ?>
                                </a>
                                <?php if ($authUser->role === 'admin'): ?>
                                    <div class="h-px bg-slate-100 my-1"></div>
                                    <a href="/categories"
                                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                        <i data-lucide="tags" class="h-4 w-4"></i> Kategori
                                    </a>
                                    <a href="/admin/users"
                                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                        <i data-lucide="users" class="h-4 w-4"></i> Pengguna
                                    </a>
                                <?php endif; ?>
                                <?php if ($authUser->role === 'admin' || $authUser->role === 'agent'): ?>
                                    <a href="/admin/destinations"
                                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                        <i data-lucide="building-2" class="h-4 w-4"></i> Destinasi
                                    </a>
                                    <a href="/admin/tours"
                                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                                        <i data-lucide="briefcase" class="h-4 w-4"></i> Paket Wisata
                                    </a>
                                <?php endif; ?>
                                <div class="h-px bg-slate-100 my-1"></div>
                                <a href="/logout"
                                    class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-rose-500 hover:bg-rose-50 transition-colors">
                                    <i data-lucide="log-out" class="h-4 w-4"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Guest Auth buttons -->
                        <div class="hidden sm:flex items-center gap-2">
                            <a href="/login"
                                class="text-sm font-semibold text-slate-600 hover:text-teal-600 transition-colors">
                                <?php echo e(translate('Masuk', 'Login')); ?>
                            </a>
                            <a href="/register"
                                class="flex items-center gap-1.5 text-sm font-semibold border-2 border-teal-500 text-teal-600 px-4 py-1.5 rounded-full hover:bg-teal-500 hover:text-white transition-all register-pill">
                                <i data-lucide="user" class="h-3.5 w-3.5"></i>
                                <?php echo e(translate('Daftar', 'Register')); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-button"
                        class="md:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-700 focus:outline-none transition-colors">
                        <i data-lucide="menu" class="h-5 w-5" id="menu-icon-open"></i>
                        <i data-lucide="x" class="h-5 w-5 hidden" id="menu-icon-close"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu"
            class="hidden md:hidden border-t border-slate-100 bg-white px-4 pt-3 pb-5 space-y-1 shadow-lg">
            <a href="/"
                class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                <i data-lucide="home"
                    class="h-4 w-4 inline mr-2 text-teal-500"></i><?php echo e(translate('Beranda', 'Home')); ?>
            </a>
            <a href="/tours"
                class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                <i data-lucide="route"
                    class="h-4 w-4 inline mr-2 text-teal-500"></i><?php echo e(translate('Paket Wisata', 'Tours')); ?>
            </a>
            <a href="/destinations"
                class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                <i data-lucide="map-pinned"
                    class="h-4 w-4 inline mr-2 text-teal-500"></i><?php echo e(translate('Destinasi', 'Destinations')); ?>
            </a>
            <?php if (!$authUser): ?>
                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 mt-2">
                    <a href="/login"
                        class="flex justify-center rounded-full border border-slate-300 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                        <?php echo e(translate('Masuk', 'Login')); ?>
                    </a>
                    <a href="/register"
                        class="flex justify-center rounded-full bg-linear-to-r from-cyan-500 to-teal-500 py-2.5 text-sm font-bold text-white shadow-sm transition-all">
                        <?php echo e(translate('Daftar', 'Register')); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- ══════════════════════════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════════════════════════ -->
    <main class="grow">

        <!-- Flash Messages -->
        <?php if ($flashSuccess = flash('success')): ?>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div
                    class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700 shadow-sm">
                    <i data-lucide="circle-check" class="h-5 w-5 shrink-0"></i>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($flashSuccess); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($flashError = flash('error')): ?>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div
                    class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-600 shadow-sm">
                    <i data-lucide="circle-alert" class="h-5 w-5 shrink-0"></i>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($flashError); ?></p>
                </div>
            </div>
        <?php endif; ?>