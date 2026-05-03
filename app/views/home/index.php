<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= APP_NAME ?> - Sistem Informasi Destinasi Wisata Kota Ambon</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .glass-panel {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        .ambient-shadow {
            box-shadow: 0 20px 40px rgba(25, 28, 30, 0.06);
        }
        .primary-gradient {
            background: linear-gradient(135deg, #005e97 0%, #0077be 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #005e97 0%, #0077be 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope'] min-h-screen flex flex-col antialiased">

<!-- TopAppBar -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-3xl shadow-sm">
    <div class="flex justify-between items-center w-full px-6 py-4 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2">
            <img src="<?= BASE_URL ?>public/assets/images/logo.png" alt="Ambon Oceanic Logo" class="h-10 w-auto">
        </div>
        
        <!-- Web Navigation -->
        <div class="hidden md:flex items-center gap-8 font-['Plus_Jakarta_Sans'] font-bold tracking-tight">
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>">Beranda</a>
            <a class="text-slate-600 hover:text-sky-600 transition-colors" href="<?= BASE_URL ?>destinasi">Destinasi</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="text-slate-600 hover:text-sky-600 transition-colors" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <?php endif; ?>
            <a class="text-slate-600 hover:text-sky-600 transition-colors" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-slate-600 hover:text-sky-600 transition-colors" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-slate-600 hover:text-sky-600 transition-colors" href="<?= BASE_URL ?>page/contact">Contact</a>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="hidden md:flex items-center gap-3">
                    <span class="text-sm text-slate-600"><?= $_SESSION['user_name'] ?></span>
                    <a href="<?= BASE_URL ?>auth/logout" class="text-slate-600 hover:text-sky-600">
                        <span class="material-symbols-outlined">logout</span>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="hidden md:flex items-center justify-center text-sky-700 bg-slate-100 hover:bg-slate-200 transition-colors rounded-full px-6 py-2.5 font-bold text-sm">
                    Sign In
                </a>
            <?php endif; ?>
            <button class="md:hidden text-slate-900" onclick="toggleMobileMenu()">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>
    </div>
</nav>

<main class="flex-grow pt-24">
    <!-- Hero Section -->
    <section class="relative w-full max-w-screen-2xl mx-auto px-6 py-12 md:py-24 min-h-[600px] flex items-center">
        <div class="absolute inset-0 z-0 rounded-3xl overflow-hidden m-6 bg-slate-200">
            <img alt="Ambon Bay View" class="w-full h-full object-cover opacity-90" 
                 src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600&h=900&fit=crop"/>
            <div class="absolute inset-0 bg-gradient-to-r from-white/90 via-white/70 to-transparent"></div>
        </div>
        
        <div class="relative z-10 w-full max-w-3xl pl-4 md:pl-12 flex flex-col gap-8">
            <div class="space-y-4">
                <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-100 text-cyan-900 text-xs font-bold tracking-widest uppercase mb-2">
                    City of Music & Ocean
                </span>
                <h1 class="font-['Plus_Jakarta_Sans'] text-5xl md:text-7xl font-extrabold tracking-tighter text-slate-900 leading-[1.1]">
                    Jelajahi <br/>Keindahan Wisata <br/>
                    <span class="text-gradient">Kota Ambon</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 max-w-xl leading-relaxed">
                    Temukan permata tersembunyi Maluku. Nikmati pantai yang indah, sejarah kolonial yang kaya, dan budaya yang hidup dari Kota Musik Indonesia.
                </p>
            </div>
            
            <!-- Search Bar -->
            <form action="<?= BASE_URL ?>destinasi" method="GET" class="glass-panel p-2 rounded-2xl ambient-shadow w-full max-w-2xl flex items-center border border-slate-200">
                <div class="flex-grow flex items-center px-4 gap-3">
                    <span class="material-symbols-outlined text-slate-500">search</span>
                    <input name="search" class="w-full bg-transparent border-none focus:ring-0 text-slate-900 placeholder:text-slate-500 py-3 outline-none" 
                           placeholder="Cari pantai, situs sejarah, atau kuliner lokal..." type="text"/>
                </div>
                <button type="submit" class="primary-gradient text-white rounded-xl px-8 py-3.5 font-bold hover:opacity-90 transition-opacity whitespace-nowrap flex items-center gap-2">
                    Cari
                </button>
            </form>
            
            <div class="flex flex-wrap items-center gap-4 pt-4">
                <a href="<?= BASE_URL ?>destinasi" class="primary-gradient text-white rounded-full px-8 py-3.5 font-bold hover:opacity-90 transition-opacity flex items-center gap-2 ambient-shadow">
                    Jelajahi Destinasi
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
                <a href="<?= BASE_URL ?>chatbot" class="bg-slate-100 text-sky-700 rounded-full px-8 py-3.5 font-bold hover:bg-slate-200 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
                    Chat dengan AI
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="w-full max-w-screen-2xl mx-auto px-6 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <?php 
            $categoryIcons = [
                'Pantai' => 'beach_access',
                'Sejarah' => 'castle',
                'Kuliner' => 'restaurant',
                'Alam' => 'landscape',
                'Religi' => 'church'
            ];
            $categoryColors = [
                'Pantai' => 'bg-blue-100 text-blue-900',
                'Sejarah' => 'bg-amber-100 text-amber-900',
                'Kuliner' => 'bg-orange-100 text-orange-900',
                'Alam' => 'bg-green-100 text-green-900',
                'Religi' => 'bg-purple-100 text-purple-900'
            ];
            foreach ($categories as $category): 
                $icon = $categoryIcons[$category['nama']] ?? 'place';
                $color = $categoryColors[$category['nama']] ?? 'bg-slate-100 text-slate-900';
            ?>
                <a href="<?= BASE_URL ?>destinasi?kategori=<?= $category['id'] ?>" class="bg-white rounded-2xl p-6 flex flex-col items-center justify-center gap-4 hover:bg-slate-50 transition-colors cursor-pointer group shadow-sm">
                    <div class="w-16 h-16 rounded-full <?= $color ?> flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;"><?= $icon ?></span>
                    </div>
                    <span class="font-['Plus_Jakarta_Sans'] font-bold text-slate-900"><?= htmlspecialchars($category['nama']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Featured Destinations -->
    <section class="w-full max-w-screen-2xl mx-auto px-6 py-16 bg-slate-100 rounded-[3rem] my-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-4xl font-extrabold tracking-tight text-slate-900 mb-2">Destinasi Unggulan</h2>
                <p class="text-slate-600 max-w-2xl">Tempat-tempat pilihan yang wajib Anda kunjungi saat berada di Ambon.</p>
            </div>
            <a href="<?= BASE_URL ?>destinasi" class="font-bold text-sky-700 flex items-center gap-2 hover:underline decoration-2 underline-offset-4">
                Lihat Semua Destinasi
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[300px]">
            <?php 
            $featured = array_slice($destinations, 0, 3);
            $first = true;
            foreach ($featured as $dest): 
                if ($first):
                    $first = false;
            ?>
                <!-- Large Card -->
                <a href="<?= BASE_URL ?>destinasi/detail/<?= $dest['id'] ?>" class="md:col-span-8 md:row-span-2 relative rounded-[2rem] overflow-hidden group cursor-pointer">
                    <?php 
                    $imageSrc = $dest['gambar'];
                    if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                        $imageSrc = BASE_URL . $imageSrc;
                    }
                    ?>
                    <img alt="<?= htmlspecialchars($dest['nama']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                         src="<?= htmlspecialchars($imageSrc) ?>"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 w-full">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="bg-cyan-100 text-cyan-900 text-xs font-bold px-3 py-1 rounded-full mb-3 inline-block">
                                    <?= htmlspecialchars($dest['kategori_nama']) ?>
                                </span>
                                <h3 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold text-white mb-2"><?= htmlspecialchars($dest['nama']) ?></h3>
                                <p class="text-white/80 text-sm max-w-md"><?= substr(htmlspecialchars($dest['deskripsi']), 0, 120) ?>...</p>
                            </div>
                            <div class="glass-panel text-slate-900 px-3 py-1.5 rounded-full flex items-center gap-1 font-bold text-sm">
                                <span class="material-symbols-outlined text-amber-500 text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                4.8
                            </div>
                        </div>
                    </div>
                </a>
            <?php else: ?>
                <!-- Small Card -->
                <a href="<?= BASE_URL ?>destinasi/detail/<?= $dest['id'] ?>" class="md:col-span-4 md:row-span-1 relative rounded-[2rem] overflow-hidden group cursor-pointer bg-white">
                    <?php 
                    $imageSrc = $dest['gambar'];
                    if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                        $imageSrc = BASE_URL . $imageSrc;
                    }
                    ?>
                    <img alt="<?= htmlspecialchars($dest['nama']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                         src="<?= htmlspecialchars($imageSrc) ?>"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 w-full">
                        <span class="bg-cyan-100 text-cyan-900 text-xs font-bold px-3 py-1 rounded-full mb-2 inline-block">
                            <?= htmlspecialchars($dest['kategori_nama']) ?>
                        </span>
                        <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold text-white"><?= htmlspecialchars($dest['nama']) ?></h3>
                        <div class="flex items-center gap-1 mt-2 text-white/90 text-sm">
                            <span class="material-symbols-outlined text-xs">location_on</span>
                            <?= htmlspecialchars(explode(',', $dest['lokasi'])[0]) ?>
                        </div>
                    </div>
                </a>
            <?php endif; endforeach; ?>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="w-full mt-auto bg-slate-100 border-t border-slate-200">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 gap-6 w-full max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity">
            <span class="material-symbols-outlined text-sky-900" style="font-variation-settings: 'FILL' 1;">water</span>
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-sky-900">Ambon Oceanic</span>
        </div>
        <div class="flex flex-wrap justify-center gap-6 text-sm">
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Privacy Policy</a>
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Terms of Service</a>
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Contact Us</a>
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">About Maluku</a>
        </div>
        <div class="text-sm text-slate-500 text-center md:text-right">
            © <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
        </div>
    </div>
</footer>

<script>
function toggleMobileMenu() {
    // Add mobile menu toggle functionality
    alert('Mobile menu - to be implemented');
}
</script>

</body>
</html>
