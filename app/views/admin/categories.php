<?php 
$active_menu = 'categories';
$page_title = 'Manage Categories';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <span class="hover:text-sky-600 cursor-pointer">Portal</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Categories</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Manage Categories</h1>
        <p class="text-lg text-slate-600 font-medium">Define and organize travel experience types across the Ambon archipelago.</p>
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
            <div class="p-3 bg-sky-50 text-sky-600 rounded-2xl">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">category</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Categories</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= count($categories) ?></p>
    </div>
</div>

<!-- Categories Management Table Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-xl text-slate-900">Category List</h3>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-black">
                    <th class="px-8 py-5">Category Name</th>
                    <th class="px-6 py-5">Description</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($categories as $cat): ?>
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sky-600" style="font-variation-settings: 'FILL' 1;">category</span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900"><?= htmlspecialchars($cat['nama']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600"><?= htmlspecialchars($cat['deskripsi']) ?></p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= BASE_URL ?>admin/deleteCategory/<?= $cat['id'] ?>" 
                                   class="p-2 text-slate-400 hover:text-red-600 transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                    <span class="material-symbols-outlined">delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add New Category Form -->
<div class="mt-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-xl p-8 text-white">
    <h3 class="text-2xl font-extrabold mb-6 font-display">Add New Category</h3>
    <form method="POST" action="<?= BASE_URL ?>admin/createCategory" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-bold mb-2 text-white/90" for="nama">Category Name</label>
            <input class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/50 focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all" 
                   id="nama" 
                   name="nama" 
                   placeholder="e.g., Beach & Coastal" 
                   type="text"
                   required/>
        </div>
        <div>
            <label class="block text-sm font-bold mb-2 text-white/90" for="deskripsi">Description</label>
            <input class="w-full bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/50 focus:ring-2 focus:ring-white/40 focus:bg-white/20 transition-all" 
                   id="deskripsi" 
                   name="deskripsi" 
                   placeholder="Brief description" 
                   type="text"/>
        </div>
        <div class="md:col-span-2">
            <button type="submit" 
                    class="bg-white text-sky-700 px-8 py-3 rounded-full font-bold hover:bg-white/90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                Add Category
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
