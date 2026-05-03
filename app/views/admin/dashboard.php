<?php 
$active_menu = 'dashboard';
$page_title = 'Dashboard';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <span class="hover:text-sky-600 cursor-pointer">Portal</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Dashboard</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Welcome Back, <?= $_SESSION['admin_username'] ?? 'Admin' ?></h1>
        <p class="text-lg text-slate-600 font-medium">Your personalized dashboard for managing Ambon tourism destinations.</p>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <p class="text-sm text-green-800"><?= $_SESSION['success'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-sm text-red-800"><?= $_SESSION['error'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Dashboard Statistics Bento -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-sky-50 text-sky-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">map</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Destinations</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= $total_destinations ?></p>
    </div>
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Registered Users</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= $total_users ?></p>
    </div>
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Recent Chats</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= count($recent_chats) ?></p>
    </div>
</div>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Chats -->
    <section class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-xl text-slate-900">Recent Chats</h3>
            <a href="<?= BASE_URL ?>admin/chatLogs" class="text-sky-600 hover:text-sky-700 transition-colors">
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
        
        <div class="p-6 space-y-4">
            <?php foreach (array_slice($recent_chats, 0, 5) as $chat): ?>
                <div class="flex gap-4 items-start p-3 hover:bg-slate-50 rounded-xl transition-colors cursor-pointer group">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-sky-700 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="font-semibold text-slate-900 truncate text-sm">
                                <?= htmlspecialchars($chat['user_name'] ?? 'Guest') ?>
                            </h4>
                            <span class="text-xs text-slate-500">
                                <?= date('H:i', strtotime($chat['created_at'])) ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 truncate">
                            <?= substr(htmlspecialchars($chat['message']), 0, 50) ?>...
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
        <div class="p-6 border-b border-slate-50">
            <h3 class="font-bold text-xl text-slate-900">Quick Actions</h3>
        </div>
        
        <div class="p-6 space-y-4">
            <a href="<?= BASE_URL ?>admin/createDestination" 
               class="block p-6 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-4xl mb-3" style="font-variation-settings: 'FILL' 1;">add_location</span>
                <h4 class="text-lg font-bold mb-1">Add Destination</h4>
                <p class="text-sm opacity-90">Create new tourism spot</p>
            </a>

            <a href="<?= BASE_URL ?>admin/destinations" 
               class="block p-6 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white hover:shadow-lg transition-shadow">
                <span class="material-symbols-outlined text-4xl mb-3" style="font-variation-settings: 'FILL' 1;">edit_location</span>
                <h4 class="text-lg font-bold mb-1">Manage Destinations</h4>
                <p class="text-sm opacity-90">Edit existing spots</p>
            </a>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
