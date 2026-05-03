<?php 
$active_menu = '';
$page_title = 'Change Password';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <a href="<?= BASE_URL ?>admin" class="hover:text-sky-600 cursor-pointer">Portal</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Change Password</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Change Password</h1>
        <p class="text-lg text-slate-600 font-medium">Update your admin account password for security.</p>
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

<!-- Change Password Form -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50">
            <h3 class="font-bold text-xl text-slate-900">Update Password</h3>
            <p class="text-sm text-slate-600 mt-1">Please enter your current password and choose a new one.</p>
        </div>
        
        <form method="POST" action="<?= BASE_URL ?>admin/changePassword" class="p-6 space-y-6">
            <!-- Current Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="current_password">
                    Current Password
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        required
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                        placeholder="Enter current password"
                    />
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="new_password">
                    New Password
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock_open</span>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        required
                        minlength="6"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                        placeholder="Enter new password (min. 6 characters)"
                    />
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="confirm_password">
                    Confirm New Password
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">check_circle</span>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        required
                        minlength="6"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                        placeholder="Confirm new password"
                    />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-4">
                <button 
                    type="submit"
                    class="px-8 py-3 bg-sky-600 text-white rounded-full font-bold shadow-lg shadow-sky-600/20 hover:bg-sky-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Update Password
                </button>
                <a 
                    href="<?= BASE_URL ?>admin"
                    class="px-8 py-3 bg-slate-100 text-slate-700 rounded-full font-bold hover:bg-slate-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-6">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-blue-600">info</span>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Password Security Tips</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Use at least 6 characters (longer is better)</li>
                    <li>• Mix uppercase and lowercase letters</li>
                    <li>• Include numbers and special characters</li>
                    <li>• Don't use common words or personal information</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
