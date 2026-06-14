<?php
// File: resources/views/layouts/footer.php
?>
</main><!-- /.flex-grow -->

<!-- ══════════════════════════════════════════════════════════════
         FOOTER — Dark navy footer matching the mockup
    ══════════════════════════════════════════════════════════════ -->
<footer class="bg-footer-dark text-slate-400 pt-14 pb-8 mt-0 border-t border-slate-900" id="contact">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-slate-800">

            <!-- Col 1: Brand -->
            <div class="sm:col-span-2 lg:col-span-1 space-y-4">
                <a href="/" class="flex items-center gap-2 group">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-linear-to-br from-cyan-500 to-teal-600 text-white shadow-sm">
                        <i data-lucide="compass" class="h-4 w-4"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight">
                        <span class="text-slate-100">Indonesia</span><span class="text-gradient">Tour Guide</span>
                    </span>
                </a>
                <p class="text-sm leading-relaxed text-slate-500 max-w-xs">
                    <?php echo translate(
                        'Platform digital terintegrasi menghubungkan wisatawan dengan pemandu lokal terbaik untuk petualangan tak terlupakan di seluruh Indonesia.',
                        'Integrated digital platform connecting travelers with the best local guides for unforgettable adventures across Indonesia.'
                    ); ?>
                </p>
                <!-- Social Icons -->
                <div class="flex gap-3 pt-1">
                    <a href="#" class="h-9 w-9 rounded-full flex items-center justify-center footer-social-icon">
                        <i data-lucide="instagram" class="h-4 w-4"></i>
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full flex items-center justify-center footer-social-icon">
                        <i data-lucide="facebook" class="h-4 w-4"></i>
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full flex items-center justify-center footer-social-icon">
                        <i data-lucide="youtube" class="h-4 w-4"></i>
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full flex items-center justify-center footer-social-icon">
                        <i data-lucide="twitter" class="h-4 w-4"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="text-slate-100 font-bold text-sm uppercase tracking-wider mb-5">
                    <?php echo translate('Tautan Cepat', 'Quick Links'); ?>
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="/" class="footer-link"><?php echo translate('Beranda', 'Home'); ?></a></li>
                    <li><a href="/tours"
                            class="footer-link"><?php echo translate('Paket Wisata', 'Tour Packages'); ?></a></li>
                    <li><a href="/destinations"
                            class="footer-link"><?php echo translate('Destinasi', 'Destinations'); ?></a></li>
                    <li><a href="/register"
                            class="footer-link"><?php echo translate('Daftar Sebagai Agen', 'Register as Agent'); ?></a>
                    </li>
                    <li><a href="/login"
                            class="footer-link"><?php echo translate('Portal Kemitraan', 'Partner Portal'); ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Categories -->
            <div>
                <h4 class="text-slate-100 font-bold text-sm uppercase tracking-wider mb-5">
                    <?php echo translate('Kategori', 'Categories'); ?>
                </h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="/?category=alam"
                            class="footer-link"><?php echo translate('Wisata Alam', 'Nature Tours'); ?></a></li>
                    <li><a href="/?category=budaya"
                            class="footer-link"><?php echo translate('Budaya & Heritage', 'Culture & Heritage'); ?></a>
                    </li>
                    <li><a href="/?category=pantai"
                            class="footer-link"><?php echo translate('Pantai & Bahari', 'Beach & Marine'); ?></a></li>
                    <li><a href="/?category=kuliner"
                            class="footer-link"><?php echo translate('Wisata Kuliner', 'Culinary Tours'); ?></a></li>
                    <li><a href="/?category=petualangan"
                            class="footer-link"><?php echo translate('Petualangan', 'Adventure'); ?></a></li>
                </ul>
            </div>

            <!-- Col 4: Contact -->
            <div>
                <h4 class="text-slate-100 font-bold text-sm uppercase tracking-wider mb-5">Contact Us</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="h-4 w-4 text-cyan-500 shrink-0 mt-0.5"></i>
                        <span class="text-slate-400 leading-relaxed">Jl. Danau Tamblingan No. 100,<br>Sanur, Bali,
                            Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="phone" class="h-4 w-4 text-cyan-500 shrink-0"></i>
                        <span class="text-slate-400">+62 361 123456</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i data-lucide="mail" class="h-4 w-4 text-cyan-500 shrink-0"></i>
                        <span class="text-slate-400">info@indonesiatourguide.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>&copy; <?php echo date('Y'); ?> Indonesia Tour Guide. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="#"
                    class="hover:text-slate-300 transition-colors"><?php echo translate('Kebijakan Privasi', 'Privacy Policy'); ?></a>
                <a href="#"
                    class="hover:text-slate-300 transition-colors"><?php echo translate('Syarat & Ketentuan', 'Terms of Service'); ?></a>
            </div>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════════════════════════════════════
         GLOBAL JAVASCRIPT — Navbar, Dropdowns, Lucide Icons
    ══════════════════════════════════════════════════════════════ -->
<script>
    // ── Navbar scroll shadow and transparent transitions ─────────
    const mainNav = document.getElementById('main-nav');
    const isHome = <?php echo json_encode($isHome ?? false); ?>;
    if (mainNav) {
        const handleScroll = () => {
            const scrolled = window.scrollY > 10;
            mainNav.classList.toggle('navbar-scrolled', scrolled);
            if (isHome) {
                if (scrolled) {
                    mainNav.classList.remove('absolute', 'bg-transparent', 'border-transparent', 'nav-transparent-mode');
                    mainNav.classList.add('fixed', 'bg-white/95', 'backdrop-blur-md', 'border-b', 'border-slate-100');
                } else {
                    mainNav.classList.add('absolute', 'bg-transparent', 'border-transparent', 'nav-transparent-mode');
                    mainNav.classList.remove('fixed', 'bg-white/95', 'backdrop-blur-md', 'border-b', 'border-slate-100');
                }
            }
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
        // Trigger immediately to evaluate initial load position
        handleScroll();
    }

    // ── Mobile Menu Toggle ────────────────────────────────────────
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuOpenIcon = document.getElementById('menu-icon-open');
    const menuCloseIcon = document.getElementById('menu-icon-close');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            menuOpenIcon.classList.toggle('hidden', !isHidden);
            menuCloseIcon.classList.toggle('hidden', isHidden);
        });
    }

    // ── User Profile Dropdown ─────────────────────────────────────
    const userMenuBtn = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!document.getElementById('user-menu-wrapper')?.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }

    // ── Initialize Lucide Icons ───────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
    // Fallback for already-loaded scripts
    if (typeof lucide !== 'undefined') lucide.createIcons();
</script>
</body>

</html>