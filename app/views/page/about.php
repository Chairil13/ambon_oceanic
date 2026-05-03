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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <?php endif; ?>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>page/about">About</a>
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

<main class="pt-32 pb-16 max-w-screen-xl mx-auto px-6">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-4 bg-gradient-to-r from-sky-600 to-blue-600 bg-clip-text text-transparent">
            Tentang Maluku
        </h1>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">
            Kepulauan Rempah yang Mempesona
        </p>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Sejarah -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-4xl text-sky-600" style="font-variation-settings: 'FILL' 1;">history_edu</span>
                <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Sejarah Maluku</h2>
            </div>
            <p class="text-slate-700 leading-relaxed mb-4">
                Maluku, yang dikenal sebagai "Kepulauan Rempah", memiliki sejarah panjang sebagai pusat perdagangan rempah-rempah dunia. Sejak abad ke-15, Maluku menjadi incaran bangsa-bangsa Eropa karena kekayaan cengkeh dan pala yang hanya tumbuh di wilayah ini.
            </p>
            <p class="text-slate-700 leading-relaxed">
                Kota Ambon, sebagai ibu kota Provinsi Maluku, telah menjadi saksi berbagai peristiwa bersejarah, mulai dari kedatangan Portugis, penjajahan Belanda, hingga perjuangan kemerdekaan Indonesia. Warisan sejarah ini masih dapat dilihat dari berbagai benteng dan bangunan bersejarah yang tersebar di Ambon.
            </p>
        </div>

        <!-- Budaya -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-4xl text-amber-600" style="font-variation-settings: 'FILL' 1;">celebration</span>
                <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Budaya & Tradisi</h2>
            </div>
            <p class="text-slate-700 leading-relaxed mb-4">
                Maluku kaya akan keragaman budaya dengan masyarakat yang majemuk. Tradisi "Pela dan Gandong" menjadi simbol persaudaraan dan toleransi antar masyarakat Maluku. Musik tradisional seperti Tifa dan tarian Cakalele menjadi identitas budaya yang masih lestari hingga kini.
            </p>
            <p class="text-slate-700 leading-relaxed">
                Kuliner khas Maluku seperti Papeda, Ikan Kuah Pala, dan Kohu-kohu mencerminkan kekayaan rasa Nusantara dengan sentuhan rempah khas Maluku.
            </p>
        </div>

        <!-- Alam -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-4xl text-green-600" style="font-variation-settings: 'FILL' 1;">nature</span>
                <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Keindahan Alam</h2>
            </div>
            <p class="text-slate-700 leading-relaxed mb-4">
                Maluku dianugerahi keindahan alam yang luar biasa. Pantai-pantai dengan pasir putih dan air laut jernih, terumbu karang yang masih alami, serta keanekaragaman hayati laut menjadikan Maluku surga bagi penyelam dan pecinta alam.
            </p>
            <p class="text-slate-700 leading-relaxed">
                Gunung-gunung berapi yang masih aktif, hutan tropis yang lebat, dan pulau-pulau kecil yang eksotis menawarkan pengalaman petualangan yang tak terlupakan.
            </p>
        </div>

        <!-- Visi Misi -->
        <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-3xl shadow-lg p-8 text-white">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">flag</span>
                <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Visi Kami</h2>
            </div>
            <p class="leading-relaxed mb-6">
                Ambon Oceanic Tourism hadir untuk memperkenalkan keindahan Maluku kepada dunia. Kami berkomitmen untuk mempromosikan pariwisata berkelanjutan yang menjaga kelestarian alam dan budaya Maluku.
            </p>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <span class="material-symbols-outlined text-3xl mb-2" style="font-variation-settings: 'FILL' 1;">eco</span>
                    <h3 class="font-bold mb-1">Berkelanjutan</h3>
                    <p class="text-sm opacity-90">Pariwisata ramah lingkungan</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <span class="material-symbols-outlined text-3xl mb-2" style="font-variation-settings: 'FILL' 1;">diversity_3</span>
                    <h3 class="font-bold mb-1">Inklusif</h3>
                    <p class="text-sm opacity-90">Memberdayakan masyarakat lokal</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <span class="material-symbols-outlined text-3xl mb-2" style="font-variation-settings: 'FILL' 1;">star</span>
                    <h3 class="font-bold mb-1">Berkualitas</h3>
                    <p class="text-sm opacity-90">Pengalaman wisata terbaik</p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="w-full bg-slate-100 border-t border-slate-200 mt-16">
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
