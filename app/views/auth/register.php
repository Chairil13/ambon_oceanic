<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Register - Ambon Oceanic</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope'] min-h-screen flex flex-col">

<div class="flex-grow flex items-center justify-center relative overflow-hidden p-6">
    <!-- Background -->
    <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600&h=900&fit=crop');">
        <div class="absolute inset-0 bg-white/70 backdrop-blur-[24px]"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-sky-500/5 to-transparent"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="<?= BASE_URL ?>public/assets/images/logo.png" alt="Ambon Oceanic Logo" class="h-16 w-auto">
            </div>
            <p class="text-slate-600 text-sm mt-2 font-medium">Buat akun untuk menyimpan favorit Anda</p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex p-1 bg-slate-100 rounded-full mb-8 relative">
            <a href="<?= BASE_URL ?>auth/login" class="flex-1 py-2.5 text-sm font-medium rounded-full text-slate-600 hover:text-slate-900 transition-colors text-center">
                Sign In
            </a>
            <a href="<?= BASE_URL ?>auth/register" class="flex-1 py-2.5 text-sm font-semibold rounded-full bg-white text-sky-700 shadow-sm transition-all text-center">
                Register
            </a>
        </div>

        <!-- Error Messages -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-red-600 flex-shrink-0">error</span>
                    <div class="flex-grow">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p class="text-sm text-red-800"><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Register Form -->
        <form method="POST" action="<?= BASE_URL ?>auth/register" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="name">
                    Nama Lengkap
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-lg">person</span>
                    </span>
                    <input class="w-full bg-slate-50 text-slate-900 placeholder-slate-400 rounded-xl pl-11 pr-4 py-3.5 border border-slate-200 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all text-sm outline-none" 
                           id="name" 
                           name="name"
                           placeholder="John Doe" 
                           type="text"
                           value="<?= $_SESSION['old']['name'] ?? '' ?>"
                           required/>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="email">
                    Email Address
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-lg">mail</span>
                    </span>
                    <input class="w-full bg-slate-50 text-slate-900 placeholder-slate-400 rounded-xl pl-11 pr-4 py-3.5 border border-slate-200 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all text-sm outline-none" 
                           id="email" 
                           name="email"
                           placeholder="hello@example.com" 
                           type="email"
                           value="<?= $_SESSION['old']['email'] ?? '' ?>"
                           required/>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="password">
                    Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-lg">lock</span>
                    </span>
                    <input class="w-full bg-slate-50 text-slate-900 placeholder-slate-400 rounded-xl pl-11 pr-4 py-3.5 border border-slate-200 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all text-sm outline-none" 
                           id="password" 
                           name="password"
                           placeholder="••••••••" 
                           type="password"
                           required/>
                </div>
                <p class="text-xs text-slate-500 mt-1.5">Minimal 6 karakter</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2" for="confirm_password">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 text-lg">lock</span>
                    </span>
                    <input class="w-full bg-slate-50 text-slate-900 placeholder-slate-400 rounded-xl pl-11 pr-4 py-3.5 border border-slate-200 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-200 transition-all text-sm outline-none" 
                           id="confirm_password" 
                           name="confirm_password"
                           placeholder="••••••••" 
                           type="password"
                           required/>
                </div>
            </div>

            <button class="w-full rounded-full py-3.5 px-6 font-semibold text-white bg-gradient-to-r from-sky-600 to-blue-600 hover:from-sky-700 hover:to-blue-700 hover:shadow-lg transition-all duration-300 mt-6 flex justify-center items-center gap-2 group" 
                    type="submit">
                <span>Daftar Sekarang</span>
                <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center">
            <p class="text-sm text-slate-600">
                Sudah punya akun? 
                <a href="<?= BASE_URL ?>auth/login" class="font-semibold text-sky-700 hover:text-sky-800 transition-colors">
                    Login di sini
                </a>
            </p>
        </div>

        <div class="mt-6 text-center">
            <a href="<?= BASE_URL ?>" class="text-sm text-slate-500 hover:text-slate-700 transition-colors inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>
<?php unset($_SESSION['old']); ?>
