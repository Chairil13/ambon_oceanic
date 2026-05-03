<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Admin Panel' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Manrope', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex min-h-screen">

<!-- SideNavBar Shell -->
<aside class="h-screen sticky left-0 top-0 w-72 overflow-y-auto bg-slate-100 font-['Manrope'] antialiased flex flex-col gap-2 p-6 z-50 flex-shrink-0">
    <div class="mb-8 px-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-600 flex items-center justify-center text-white">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">sailing</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-sky-900 leading-tight">Oceanic Portal</h2>
                <p class="text-xs text-slate-500 font-medium">Ambon Tourism Admin</p>
            </div>
        </div>
    </div>
    
    <nav class="flex flex-col gap-2">
        <a class="flex items-center gap-3 <?= ($active_menu ?? '') == 'dashboard' ? 'bg-sky-100/80 text-sky-800 font-bold' : 'text-slate-600 hover:bg-slate-200/50' ?> px-6 py-3 rounded-full transition-all translate-x-1 transition-transform duration-200" 
           href="<?= BASE_URL ?>admin">
            <span class="material-symbols-outlined" <?= ($active_menu ?? '') == 'dashboard' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>grid_view</span>
            <span class="font-medium">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 <?= ($active_menu ?? '') == 'destinations' ? 'bg-sky-100/80 text-sky-800 font-bold' : 'text-slate-600 hover:bg-slate-200/50' ?> px-6 py-3 rounded-full transition-all translate-x-1 transition-transform duration-200" 
           href="<?= BASE_URL ?>admin/destinations">
            <span class="material-symbols-outlined" <?= ($active_menu ?? '') == 'destinations' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>map</span>
            <span class="font-medium">Destinations</span>
        </a>
        <a class="flex items-center gap-3 <?= ($active_menu ?? '') == 'categories' ? 'bg-sky-100/80 text-sky-800 font-bold' : 'text-slate-600 hover:bg-slate-200/50' ?> px-6 py-3 rounded-full transition-all translate-x-1 transition-transform duration-200" 
           href="<?= BASE_URL ?>admin/categories">
            <span class="material-symbols-outlined" <?= ($active_menu ?? '') == 'categories' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>category</span>
            <span class="font-medium">Categories</span>
        </a>
        <a class="flex items-center gap-3 <?= ($active_menu ?? '') == 'users' ? 'bg-sky-100/80 text-sky-800 font-bold' : 'text-slate-600 hover:bg-slate-200/50' ?> px-6 py-3 rounded-full transition-all translate-x-1 transition-transform duration-200" 
           href="<?= BASE_URL ?>admin/users">
            <span class="material-symbols-outlined" <?= ($active_menu ?? '') == 'users' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>group</span>
            <span class="font-medium">Users</span>
        </a>
        <a class="flex items-center gap-3 <?= ($active_menu ?? '') == 'chat_logs' ? 'bg-sky-100/80 text-sky-800 font-bold' : 'text-slate-600 hover:bg-slate-200/50' ?> px-6 py-3 rounded-full transition-all translate-x-1 transition-transform duration-200" 
           href="<?= BASE_URL ?>admin/chatLogs">
            <span class="material-symbols-outlined" <?= ($active_menu ?? '') == 'chat_logs' ? 'style="font-variation-settings: \'FILL\' 1;"' : '' ?>>chat</span>
            <span class="font-medium">Chat Logs</span>
        </a>
    </nav>
    
    <div class="mt-auto pt-6">
        <a href="<?= BASE_URL ?>admin/logout" 
           class="w-full py-4 bg-slate-200 text-slate-700 rounded-full font-bold flex items-center justify-center gap-2 hover:bg-slate-300 transition-all">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>
    </div>
</aside>

<main class="flex-1 flex flex-col min-w-0">
    <!-- TopNavBar Shell -->
    <header class="w-full sticky top-0 z-40 bg-white/70 backdrop-blur-2xl flex justify-between items-center px-8 h-20 shadow-sm">
        <div class="flex items-center gap-6 flex-1">
            <h2 class="text-xl font-bold text-sky-900"><?= $page_title ?? 'Admin Panel' ?></h2>
        </div>
        <div class="flex items-center gap-4">
            <a href="<?= BASE_URL ?>" class="p-2 text-slate-500 hover:text-sky-600 transition-colors">
                <span class="material-symbols-outlined">public</span>
            </a>
            <div class="h-8 w-[1px] bg-slate-200 mx-2"></div>
            <div class="relative">
                <button onclick="toggleProfileMenu()" class="flex items-center gap-3 hover:bg-slate-50 rounded-full pr-2 transition-colors">
                    <span class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-sky-900 leading-none"><?= $_SESSION['admin_username'] ?? 'Admin' ?></p>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">Administrator</p>
                    </span>
                    <?php 
                    $adminModel = $this->model('Admin');
                    $adminData = $adminModel->getById($_SESSION['admin_id'] ?? 0);
                    $hasPhoto = !empty($adminData['photo']);
                    // Check if photo is URL or local path
                    $photoSrc = '';
                    if ($hasPhoto) {
                        $basePhotoSrc = filter_var($adminData['photo'], FILTER_VALIDATE_URL) 
                            ? $adminData['photo'] 
                            : BASE_URL . $adminData['photo'];
                        // Add cache buster to force browser reload
                        $photoSrc = $basePhotoSrc . '?v=' . time();
                    }
                    ?>
                    <?php if ($hasPhoto): ?>
                        <img src="<?= htmlspecialchars($photoSrc) ?>" 
                             alt="Admin photo" 
                             class="w-10 h-10 rounded-full object-cover cursor-pointer hover:ring-4 hover:ring-sky-100 transition-all"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center text-white font-bold cursor-pointer hover:ring-4 hover:ring-sky-100 transition-all" style="display: none;">
                            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                        </div>
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center text-white font-bold cursor-pointer hover:ring-4 hover:ring-sky-100 transition-all">
                            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="profileMenu" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50">
                    <div class="p-4 bg-gradient-to-br from-sky-500 to-blue-600 text-white">
                        <div class="flex items-center gap-3">
                            <?php if ($hasPhoto): ?>
                                <img src="<?= htmlspecialchars($photoSrc) ?>" 
                                     alt="Admin photo" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-white/20"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg" style="display: none;">
                                    <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                                </div>
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                    <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <p class="font-bold"><?= $_SESSION['admin_username'] ?? 'Admin' ?></p>
                                <p class="text-xs opacity-90">Administrator</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <a href="<?= BASE_URL ?>admin/changePassword" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors text-slate-700">
                            <span class="material-symbols-outlined text-sky-600">lock</span>
                            <span class="font-medium">Change Password</span>
                        </a>
                        <a href="<?= BASE_URL ?>admin/changePhoto" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors text-slate-700">
                            <span class="material-symbols-outlined text-sky-600">photo_camera</span>
                            <span class="font-medium">Change Photo</span>
                        </a>
                        <div class="h-[1px] bg-slate-100 my-2"></div>
                        <a href="<?= BASE_URL ?>admin/logout" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors text-red-600">
                            <span class="material-symbols-outlined">logout</span>
                            <span class="font-medium">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Canvas -->
    <div class="p-10 max-w-7xl mx-auto w-full">
