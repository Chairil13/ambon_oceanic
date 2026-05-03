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
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>page/contact">Contact</a>
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
            Hubungi Kami
        </h1>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">
            Ada pertanyaan? Kami siap membantu Anda
        </p>
    </div>

    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white rounded-3xl shadow-lg p-8">
            <h2 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-6">Kirim Pesan</h2>
            <form id="contactForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" id="name" required
                           class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none"
                           placeholder="Masukkan nama Anda">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" id="email" required
                           class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none"
                           placeholder="nama@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pesan</label>
                    <textarea id="message" rows="5" required
                              class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none resize-none"
                              placeholder="Tulis pesan Anda..."></textarea>
                </div>
                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-xl px-6 py-3 font-bold transition-all">
                    Kirim Pesan
                </button>
            </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-6">
            <!-- Address -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-sky-100 rounded-full p-3">
                        <span class="material-symbols-outlined text-sky-600 text-2xl">location_on</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-1">Alamat</h3>
                        <p class="text-slate-600">
                            Jl. Raya Pattimura<br>
                            Kota Ambon, Maluku<br>
                            Indonesia
                        </p>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-100 rounded-full p-3">
                        <span class="material-symbols-outlined text-amber-600 text-2xl">email</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-1">Email</h3>
                        <p class="text-slate-600">
                            info@ambonoceanic.com<br>
                            support@ambonoceanic.com
                        </p>
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-green-100 rounded-full p-3">
                        <span class="material-symbols-outlined text-green-600 text-2xl">phone</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-1">Telepon</h3>
                        <p class="text-slate-600">
                            +62 911 123 4567<br>
                            +62 911 765 4321
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-3xl shadow-lg p-6 text-white">
                <h3 class="font-bold text-lg mb-4">Ikuti Kami</h3>
                <div class="flex gap-3">
                    <a href="#" class="bg-white/20 hover:bg-white/30 rounded-full p-3 transition-colors">
                        <span class="material-symbols-outlined">share</span>
                    </a>
                    <a href="#" class="bg-white/20 hover:bg-white/30 rounded-full p-3 transition-colors">
                        <span class="material-symbols-outlined">photo_camera</span>
                    </a>
                    <a href="#" class="bg-white/20 hover:bg-white/30 rounded-full p-3 transition-colors">
                        <span class="material-symbols-outlined">video_library</span>
                    </a>
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

<script>
const contactForm = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');

contactForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';
    
    fetch('<?= BASE_URL ?>page/sendContact', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({name, email, message})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            contactForm.reset();
        } else {
            alert(data.error || 'Gagal mengirim pesan');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim Pesan';
    });
});
</script>

</body>
</html>
