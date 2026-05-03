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
            <a class="text-sky-700" href="<?= BASE_URL ?>destinasi">Destinasi</a>
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
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <div class="relative h-96 rounded-3xl overflow-hidden">
                <img src="<?= htmlspecialchars($destination['gambar']) ?>" alt="<?= htmlspecialchars($destination['nama']) ?>" 
                     class="w-full h-full object-cover"/>
            </div>
            
            <div>
                <span class="inline-block bg-cyan-100 text-cyan-900 text-sm font-bold px-4 py-2 rounded-full mb-4">
                    <?= htmlspecialchars($destination['kategori_nama']) ?>
                </span>
                <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-4"><?= htmlspecialchars($destination['nama']) ?></h1>
                <p class="text-lg text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($destination['deskripsi'])) ?></p>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl p-8 shadow-sm sticky top-32 space-y-6">
                <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-6">Informasi</h3>
                
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-red-600" style="font-variation-settings: 'FILL' 1;">location_on</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-500 mb-1">Lokasi</p>
                            <p class="text-slate-900"><?= htmlspecialchars($destination['lokasi']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">schedule</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-500 mb-1">Jam Buka</p>
                            <p class="text-slate-900"><?= htmlspecialchars($destination['jam_buka']) ?></p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-500 mb-1">Harga Tiket</p>
                            <p class="text-slate-900 font-bold text-xl">Rp <?= number_format($destination['harga_tiket'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-200">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button id="favoriteBtn" data-id="<?= $destination['id'] ?>" data-favorite="<?= $isFavorite ? '1' : '0' ?>"
                                class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-2xl px-6 py-4 font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $isFavorite ? '1' : '0' ?>;">favorite</span>
                            <?= $isFavorite ? 'Hapus dari Favorit' : 'Tambah ke Favorit' ?>
                        </button>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>auth/login" 
                           class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-2xl px-6 py-4 font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined">favorite</span>
                            Login untuk Favorit
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
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

<script>
document.getElementById('favoriteBtn')?.addEventListener('click', function() {
    const btn = this;
    const destinasiId = btn.dataset.id;
    const isFavorite = btn.dataset.favorite === '1';
    
    fetch('<?= BASE_URL ?>auth/toggleFavorite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({destinasi_id: destinasiId})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 1;">favorite</span> Hapus dari Favorit';
                btn.dataset.favorite = '1';
            } else {
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 0;">favorite</span> Tambah ke Favorit';
                btn.dataset.favorite = '0';
            }
        }
    });
});
</script>

</body>
</html>
