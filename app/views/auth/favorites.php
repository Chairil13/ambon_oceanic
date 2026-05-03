<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>destinasi">Destinasi</a>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/contact">Contact</a>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm"><?= $_SESSION['user_name'] ?></span>
            <a href="<?= BASE_URL ?>auth/logout" class="text-slate-600 hover:text-sky-600">
                <span class="material-symbols-outlined">logout</span>
            </a>
        </div>
    </div>
</nav>

<main class="pt-32 pb-16 max-w-screen-2xl mx-auto px-6">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-2xl" style="font-variation-settings: 'FILL' 1;">favorite</span>
        </div>
        <div>
            <h1 class="font-['Plus_Jakarta_Sans'] text-4xl font-extrabold">Favorit Saya</h1>
            <p class="text-slate-600">Destinasi yang Anda simpan</p>
        </div>
    </div>
    
    <?php if (empty($favorites)): ?>
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-slate-400 text-4xl">favorite_border</span>
            </div>
            <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold mb-2">Belum Ada Favorit</h3>
            <p class="text-slate-600 mb-6">Anda belum memiliki destinasi favorit.</p>
            <a href="<?= BASE_URL ?>destinasi" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 text-white rounded-full px-8 py-3.5 font-bold transition-all">
                Jelajahi Destinasi
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($favorites as $dest): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group">
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?= htmlspecialchars($dest['gambar']) ?>" alt="<?= htmlspecialchars($dest['nama']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-3 py-1 rounded-full">
                                <?= htmlspecialchars($dest['kategori_nama']) ?>
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="material-symbols-outlined text-pink-500 text-2xl" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold mb-2"><?= htmlspecialchars($dest['nama']) ?></h3>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2"><?= substr(htmlspecialchars($dest['deskripsi']), 0, 100) ?>...</p>
                        <div class="space-y-2 text-sm text-slate-600 mb-4">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                <?= htmlspecialchars(explode(',', $dest['lokasi'])[0]) ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                Rp <?= number_format($dest['harga_tiket'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>destinasi/detail/<?= $dest['id'] ?>" 
                           class="block text-center bg-slate-100 hover:bg-slate-200 text-slate-900 rounded-xl px-4 py-2.5 font-bold transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
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
