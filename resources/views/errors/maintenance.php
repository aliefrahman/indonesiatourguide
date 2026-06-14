<?php
// File: resources/views/errors/maintenance.php
$currentLang = $_SESSION['lang'] ?? 'id';
?>
<!DOCTYPE html>
<html lang="<?php echo e($currentLang); ?>" class="h-full bg-slate-955 text-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(translate('Situs dalam Pemeliharaan - IndoTour', 'Site Under Maintenance - IndoTour')); ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Compiled Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/styles.css">
    <!-- Lucide for Premium Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, rgba(13, 148, 136, 0.15), transparent 45%),
                        radial-gradient(circle at bottom left, rgba(16, 185, 129, 0.07), transparent 45%),
                        #020617;
        }
    </style>
</head>

<body class="h-full flex flex-col items-center justify-center p-4 selection:bg-teal-500 selection:text-slate-955">
    
    <div class="relative w-full max-w-lg">
        <!-- Glowing background decoration -->
        <div class="absolute -inset-1 rounded-3xl bg-linear-to-r from-teal-500 to-emerald-500 opacity-20 blur-xl transition duration-1000 group-hover:duration-200 animate-pulse"></div>
        
        <!-- Main Card -->
        <div class="relative rounded-2xl border border-slate-800 bg-slate-905/60 p-8 text-center backdrop-blur-xl md:p-12">
            <!-- Icon with pulse animation -->
            <div class="mx-auto mb-8 inline-flex h-24 w-24 items-center justify-center rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400">
                <i data-lucide="wrench" class="h-10 w-10 animate-bounce"></i>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-black tracking-tight text-white md:text-4xl mb-4">
                <?php echo e(translate('Sedang dalam Pemeliharaan', 'Under Maintenance')); ?>
            </h1>

            <!-- Description -->
            <p class="text-slate-450 text-sm md:text-base mb-8 leading-relaxed">
                <?php echo e(translate(
                    'Kami sedang melakukan peningkatan sistem untuk memberikan pengalaman terbaik kepada Anda. Silakan kembali beberapa saat lagi.',
                    'We are currently performing scheduled system upgrades to improve our service. Please check back shortly.'
                )); ?>
            </p>

            <!-- Language selector inside card -->
            <div class="flex justify-center items-center space-x-2 bg-slate-955/50 border border-slate-800/80 rounded-full p-0.5 max-w-[120px] mx-auto mb-8 shadow-inner">
                <a href="/lang/id" class="flex-1 px-3 py-1 text-xs rounded-full font-bold transition-all <?php echo e($currentLang === 'id' ? 'bg-linear-to-r from-teal-500 to-emerald-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-slate-200'); ?>">ID</a>
                <a href="/lang/en" class="flex-1 px-3 py-1 text-xs rounded-full font-bold transition-all <?php echo e($currentLang === 'en' ? 'bg-linear-to-r from-teal-500 to-emerald-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-slate-200'); ?>">EN</a>
            </div>

            <!-- Divider -->
            <div class="h-px bg-linear-to-r from-transparent via-slate-800 to-transparent my-8"></div>

            <!-- Admin Access Link -->
            <div class="flex flex-col items-center justify-center space-y-2">
                <p class="text-xs text-slate-500">
                    <?php echo e(translate('Apakah Anda Administrator?', 'Are you an Administrator?')); ?>
                </p>
                <a href="/login" class="inline-flex items-center space-x-2 px-5 py-2 rounded-full border border-teal-500/30 hover:border-teal-500/60 bg-teal-950/20 text-teal-400 hover:text-teal-300 font-semibold text-xs transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <i data-lucide="shield-alert" class="h-4 w-4"></i>
                    <span><?php echo e(translate('Masuk sebagai Admin', 'Login as Admin')); ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Small footer -->
    <div class="mt-8 text-center text-xs text-slate-600">
        &copy; <?php echo e(date('Y')); ?> IndoTour. <?php echo e(translate('Hak Cipta Dilindungi.', 'All rights reserved.')); ?>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>

</html>
