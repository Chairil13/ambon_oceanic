<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Portal Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        .ocean-gradient {
            background: linear-gradient(135deg, #f7f9fb 0%, #cfe5ff 100%);
        }
        .primary-gradient {
            background: linear-gradient(135deg, #005e97 0%, #0077be 100%);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope'] min-h-screen flex flex-col">

<!-- Hero Background Wrapper -->
<main class="flex-grow ocean-gradient flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Abstract Decorative Shapes -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-200/30 blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-cyan-200/30 blur-[100px]"></div>
    
    <!-- Login Container -->
    <div class="w-full max-w-[480px] z-10">
        <!-- Content & Identity -->
        <div class="glass-panel rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Brand Anchor -->
                <div class="flex flex-col items-center mb-10">
                    <div class="w-16 h-16 bg-gradient-to-br from-sky-600 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-sky-500/30">
                        <span class="material-symbols-outlined text-white text-4xl fill">waves</span>
                    </div>
                    <h1 class="font-['Plus_Jakarta_Sans'] text-2xl font-extrabold text-slate-900 tracking-tight">Ambon Oceanic</h1>
                    <p class="font-['Manrope'] text-sm font-semibold text-sky-700 uppercase tracking-widest mt-2">Admin Portal Login</p>
                </div>

                <!-- Error Message -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-red-600 flex-shrink-0">error</span>
                            <p class="text-sm text-red-800"><?= $_SESSION['error'] ?></p>
                        </div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" action="<?= BASE_URL ?>admin/login" class="space-y-6">
                    <!-- Admin Username Field -->
                    <div class="space-y-2">
                        <label class="font-['Manrope'] text-sm font-bold text-slate-600 ml-1" for="username">
                            Admin Username
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-600 transition-colors">
                                <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                            </div>
                            <input class="w-full bg-slate-50 border-none rounded-xl py-4 pl-12 pr-4 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500/40 focus:bg-white transition-all duration-300" 
                                   id="username" 
                                   name="username" 
                                   placeholder="admin" 
                                   type="text"
                                   required
                                   autofocus/>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label class="font-['Manrope'] text-sm font-bold text-slate-600 ml-1" for="password">
                            Password
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-600 transition-colors">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input class="w-full bg-slate-50 border-none rounded-xl py-4 pl-12 pr-12 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500/40 focus:bg-white transition-all duration-300" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••••••" 
                                   type="password"
                                   required/>
                            <button class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-sky-600 transition-colors" 
                                    type="button"
                                    onclick="togglePassword()">
                                <span class="material-symbols-outlined text-xl" id="toggleIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Options Row -->
                    <div class="flex items-center justify-between font-['Manrope'] text-sm">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input class="h-5 w-5 rounded border-slate-300 bg-slate-50 text-sky-600 focus:ring-sky-500/40 transition-all cursor-pointer" 
                                   type="checkbox"/>
                            <span class="text-slate-600 group-hover:text-slate-900 transition-colors">Remember me</span>
                        </label>
                    </div>

                    <!-- Action Button -->
                    <button class="w-full primary-gradient text-white font-['Plus_Jakarta_Sans'] font-bold py-4 rounded-full shadow-lg shadow-sky-500/25 hover:shadow-xl hover:shadow-sky-500/30 active:scale-95 transition-all duration-200 flex items-center justify-center space-x-2" 
                            type="submit">
                        <span>Sign In to Dashboard</span>
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </button>
                </form>

                <!-- Secondary Link -->
                <div class="mt-8 pt-8 border-t border-slate-200/50 flex justify-center">
                    <a class="flex items-center space-x-2 text-slate-600 hover:text-sky-600 font-['Manrope'] text-sm font-semibold transition-colors" 
                       href="<?= BASE_URL ?>">
                        <span class="material-symbols-outlined text-lg">public</span>
                        <span>Back to Public Site</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Trust Badge -->
        <div class="mt-8 flex items-center justify-center space-x-6 opacity-40">
            <div class="flex items-center space-x-1">
                <span class="material-symbols-outlined text-sm">verified_user</span>
                <span class="font-['Manrope'] text-[10px] uppercase tracking-tighter font-bold">Secure SSL</span>
            </div>
            <div class="flex items-center space-x-1">
                <span class="material-symbols-outlined text-sm">security</span>
                <span class="font-['Manrope'] text-[10px] uppercase tracking-tighter font-bold">Encrypted Data</span>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="w-full mt-auto py-4 bg-slate-100">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 max-w-7xl mx-auto space-y-4 md:space-y-0">
        <div class="text-lg font-bold text-slate-800 font-['Plus_Jakarta_Sans']">Ambon Oceanic</div>
        <div class="text-slate-500 font-['Manrope'] text-sm text-center">
            © <?= date('Y') ?> Ambon City Tourism Office. All Rights Reserved.
        </div>
        <div class="flex space-x-6 font-['Manrope'] text-sm">
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Privacy Policy</a>
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Security Standards</a>
            <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Support</a>
        </div>
    </div>
</footer>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = 'visibility';
    }
}
</script>

</body>
</html>
