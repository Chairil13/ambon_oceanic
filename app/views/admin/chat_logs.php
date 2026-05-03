<?php 
$active_menu = 'chat_logs';
$page_title = 'Chat Logs';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <span class="hover:text-sky-600 cursor-pointer">Portal</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Chat Logs</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Chat Logs</h1>
        <p class="text-lg text-slate-600 font-medium">Monitor chatbot conversations and user interactions.</p>
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
            <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Chats</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= count($logs) ?></p>
    </div>
</div>

<!-- Chat Logs Management Table Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-xl text-slate-900">Conversation History</h3>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-black">
                    <th class="px-8 py-5">User</th>
                    <th class="px-6 py-5">Message</th>
                    <th class="px-6 py-5">Response</th>
                    <th class="px-6 py-5">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($logs as $log): ?>
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold">
                                    <?= strtoupper(substr($log['user_name'] ?? 'G', 0, 1)) ?>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900"><?= htmlspecialchars($log['user_name'] ?? 'Guest') ?></p>
                                    <p class="text-xs text-slate-500">ID: <?= $log['id'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600 max-w-xs truncate" title="<?= htmlspecialchars($log['message']) ?>">
                                <?= substr(htmlspecialchars($log['message']), 0, 50) ?>...
                            </p>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600 max-w-xs truncate" title="<?= htmlspecialchars($log['response']) ?>">
                                <?= substr(htmlspecialchars($log['response']), 0, 50) ?>...
                            </p>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-6 bg-slate-50/30 flex items-center justify-between border-t border-slate-50">
        <p class="text-sm text-slate-500 font-medium">
            Showing <span class="text-slate-900 font-bold"><?= count($logs) ?></span> chat logs
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
