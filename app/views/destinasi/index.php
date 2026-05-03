<?php
/**
 * @var string $title - Page title from controller
 * @var array $destinations - List of destinations
 * @var array $categories - List of categories for filter
 */
?>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .primary-gradient { background: linear-gradient(135deg, #005e97 0%, #0077be 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope']">

<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-3xl shadow-sm">
    <div class="flex justify-between items-center w-full px-6 py-4 max-w-screen-2xl mx-auto">
        <a href="<?= BASE_URL ?>" class="flex items-center gap-2">
            <img src="<?= BASE_URL ?>public/assets/images/logo.png" alt="Ambon Oceanic Logo" class="h-10 w-auto">
        </a>
        <div class="hidden md:flex items-center gap-8 font-['Plus_Jakarta_Sans'] font-bold">
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>">Beranda</a>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>destinasi">Destinasi</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <?php endif; ?>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/contact">Contact</a>
        </div>
        <div class="flex items-center gap-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-sm"><?= $_SESSION['user_name'] ?></span>
                <a href="<?= BASE_URL ?>auth/logout" class="text-slate-600 hover:text-sky-600">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="bg-slate-100 hover:bg-slate-200 rounded-full px-6 py-2.5 font-bold text-sm">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="pt-32 pb-16 max-w-screen-2xl mx-auto px-6">
    <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-8">Destinasi Wisata Ambon</h1>
    
    <!-- Search & Filter -->
    <div class="grid md:grid-cols-2 gap-4 mb-8">
        <form method="GET" action="<?= BASE_URL ?>destinasi" class="flex gap-2">
            <div class="flex-grow flex items-center bg-white rounded-xl px-4 gap-3 border border-slate-200">
                <span class="material-symbols-outlined text-slate-500">search</span>
                <input type="text" name="search" class="w-full bg-transparent border-none focus:ring-0 py-3 outline-none" 
                       placeholder="Cari destinasi..." value="<?= $_GET['search'] ?? '' ?>">
            </div>
            <button type="submit" class="primary-gradient text-white rounded-xl px-6 py-3 font-bold hover:opacity-90">
                Cari
            </button>
        </form>
        
        <form method="GET" action="<?= BASE_URL ?>destinasi">
            <select name="kategori" class="w-full bg-white border-slate-200 rounded-xl py-3 px-4 font-medium" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Destinations Grid -->
    <?php if (empty($destinations)): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
            <p class="text-blue-900">Tidak ada destinasi ditemukan.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($destinations as $dest): ?>
                <a href="<?= BASE_URL ?>destinasi/detail/<?= $dest['id'] ?>" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group">
                    <div class="relative h-48 overflow-hidden">
                        <?php 
                        $imageSrc = $dest['gambar'];
                        if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                            $imageSrc = BASE_URL . $imageSrc;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($dest['nama']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='<?= BASE_URL ?>public/assets/images/logo.png'"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-3 py-1 rounded-full">
                                <?= htmlspecialchars($dest['kategori_nama']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold mb-2"><?= htmlspecialchars($dest['nama']) ?></h3>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2"><?= substr(htmlspecialchars($dest['deskripsi']), 0, 100) ?>...</p>
                        <div class="space-y-2 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <?= htmlspecialchars(explode(',', $dest['lokasi'])[0]) ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <?= htmlspecialchars($dest['jam_buka']) ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                Rp <?= number_format($dest['harga_tiket'], 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="w-full bg-slate-100 border-t border-slate-200">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 gap-6 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-900" style="font-variation-settings: 'FILL' 1;">water</span>
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-sky-900">Ambon Oceanic</span>
        </div>
        <div class="text-sm text-slate-500">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</div>
    </div>
</footer>

</body>
</html>
