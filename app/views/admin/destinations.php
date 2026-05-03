<?php 
$active_menu = 'destinations';
$page_title = 'Manage Destinations';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <span class="hover:text-sky-600 cursor-pointer">Portal</span>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Destinations</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Manage Destinations</h1>
        <p class="text-lg text-slate-600 font-medium">Curate and oversee Ambon's top travel experiences.</p>
    </div>
    <div>
        <a href="<?= BASE_URL ?>admin/createDestination" 
           class="px-8 py-3.5 bg-sky-600 text-white rounded-full font-bold shadow-xl shadow-sky-600/20 flex items-center gap-2 hover:scale-105 active:scale-95 transition-all">
            <span class="material-symbols-outlined">add</span>
            Add New Destination
        </a>
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
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">map</span>
            </div>
        </div>
        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Destinations</p>
        <p class="text-4xl font-extrabold text-slate-900"><?= count($destinations) ?></p>
    </div>
</div>

<!-- Destinations Management Table Card -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-slate-100">
    <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h3 class="font-bold text-xl text-slate-900">Destination List</h3>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="pl-10 pr-4 py-2 bg-slate-50 border-none rounded-full text-sm text-slate-600 focus:ring-sky-500/20" 
                       placeholder="Search..." 
                       type="text"
                       id="searchInput"
                       onkeyup="searchDestinations()"/>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-black">
                    <th class="px-8 py-5">Destination Name</th>
                    <th class="px-6 py-5">Category</th>
                    <th class="px-6 py-5">Location</th>
                    <th class="px-6 py-5">Price</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($destinations as $dest): ?>
                    <tr class="hover:bg-slate-50/40 transition-colors group destination-row" data-name="<?= strtolower($dest['nama']) ?>">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden shadow-sm bg-slate-100">
                                    <?php 
                                    $imageSrc = $dest['gambar'];
                                    // If it's not a full URL, prepend BASE_URL
                                    if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                                        $imageSrc = BASE_URL . $imageSrc;
                                    }
                                    ?>
                                    <img class="w-full h-full object-cover" 
                                         src="<?= htmlspecialchars($imageSrc) ?>"
                                         alt="<?= htmlspecialchars($dest['nama']) ?>"
                                         onerror="this.src='<?= BASE_URL ?>public/assets/images/logo.png'"/>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900"><?= htmlspecialchars($dest['nama']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-900">
                                <?= htmlspecialchars($dest['kategori_nama']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm text-slate-600"><?= htmlspecialchars(explode(',', $dest['lokasi'])[0]) ?></p>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-sm font-bold text-slate-900">Rp <?= number_format($dest['harga_tiket'], 0, ',', '.') ?></p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?= BASE_URL ?>admin/editDestination/<?= $dest['id'] ?>" 
                                   class="p-2 text-slate-400 hover:text-sky-600 transition-colors">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <a href="<?= BASE_URL ?>admin/deleteDestination/<?= $dest['id'] ?>" 
                                   class="p-2 text-slate-400 hover:text-red-600 transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus destinasi ini?')">
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
            Showing <span class="text-slate-900 font-bold"><?= count($destinations) ?></span> destinations
        </p>
    </div>
</div>

<script>
function searchDestinations() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.destination-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        if (name.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
