<?php 
$active_menu = 'users';
$page_title = 'Manage Users';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <span class="hover:text-sky-600 cursor-pointer">Portal</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Users</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Manage Users</h1>
        <p class="text-lg text-slate-600 font-medium">Monitor and manage registered users of the Ambon Oceanic platform.</p>
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

<!-- Dashboard Statistics Bento -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Users</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= count($users) ?></p>
    </div>
</div>

<!-- Users Management Table Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-xl text-slate-900">User List</h3>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-black">
                    <th class="px-8 py-5">User</th>
                    <th class="px-6 py-5">Email</th>
                    <th class="px-6 py-5">Registered</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900"><?= htmlspecialchars($user['name']) ?></p>
                                    <p class="text-xs text-slate-500">ID: <?= $user['id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600"><?= htmlspecialchars($user['email']) ?></p>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600"><?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= BASE_URL ?>admin/deleteUser/<?= $user['id'] ?>" 
                                   class="p-2 text-slate-400 hover:text-red-600 transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus user ini?')">
                                    <span class="material-symbols-outlined">delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-6 bg-slate-50/30 flex items-center justify-between border-t border-slate-50">
        <p class="text-sm text-slate-500 font-medium">
            Showing <span class="text-slate-900 font-bold"><?= count($users) ?></span> users
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
