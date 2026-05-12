<?php
/**
 * @var string $title - Page title from controller
 * @var array $destinations - List of destinations
 * @var array $categories - List of categories for filter
 */
?>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .primary-gradient { background: linear-gradient(135deg, #005e97 0%, #0077be 100%); }
        .destination-map { 
            height: 100%;
            min-height: 120px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope'] min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-3xl shadow-sm">
    <div class="flex justify-between items-center w-full px-6 py-4 max-w-screen-2xl mx-auto">
        <a href="<?= BASE_URL ?>" class="flex items-center gap-2">
            <img src="<?= BASE_URL ?>public/assets/images/logo.png" alt="Ambon Oceanic Logo" class="h-10 w-auto">
        </a>
        <div class="hidden md:flex items-center gap-8 font-['Plus_Jakarta_Sans'] font-bold">
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>">Beranda</a>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>destinasi">Destinasi</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <?php endif; ?>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/contact">Contact</a>
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

<main class="pt-32 pb-16 max-w-screen-2xl mx-auto px-6">
    <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-8">Destinasi Wisata Ambon</h1>
    
    <!-- Search & Filter -->
    <div class="grid md:grid-cols-2 gap-4 mb-8">
        <form method="GET" action="<?= BASE_URL ?>destinasi" class="flex gap-2">
            <div class="flex-grow flex items-center bg-white rounded-xl px-4 gap-3 border border-slate-200">
                <span class="material-symbols-outlined text-slate-500">search</span>
                <input type="text" name="search" class="w-full bg-transparent border-none focus:ring-0 py-3 outline-none" 
                       placeholder="Cari destinasi..." value="<?= $_GET['search'] ?? '' ?>">
            </div>
            <button type="submit" class="primary-gradient text-white rounded-xl px-6 py-3 font-bold hover:opacity-90">
                Cari
            </button>
        </form>
        
        <form method="GET" action="<?= BASE_URL ?>destinasi">
            <select name="kategori" class="w-full bg-white border-slate-200 rounded-xl py-3 px-4 font-medium" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Destinations Grid -->
    <?php if (empty($destinations)): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
            <p class="text-blue-900">Tidak ada destinasi ditemukan.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($destinations as $dest): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow group cursor-pointer" 
                     onclick="window.location.href='<?= BASE_URL ?>destinasi/detail/<?= $dest['id'] ?>'">
                    <div class="relative h-48 overflow-hidden">
                        <?php 
                        $imageSrc = $dest['gambar'];
                        if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                            $imageSrc = BASE_URL . $imageSrc;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($dest['nama']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='<?= BASE_URL ?>public/assets/images/logo.png'"/>
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-3 py-1 rounded-full">
                                <?= htmlspecialchars($dest['kategori_nama']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-['Plus_Jakarta_Sans'] text-xl font-bold mb-2 group-hover:text-sky-600 transition-colors"><?= htmlspecialchars($dest['nama']) ?></h3>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2"><?= substr(htmlspecialchars($dest['deskripsi']), 0, 100) ?>...</p>
                        
                        <!-- Info & Map Grid -->
                        <div class="grid grid-cols-2 gap-4 items-start">
                            <!-- Left: Info -->
                            <div class="space-y-3 text-sm text-slate-600">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-sm mt-0.5 flex-shrink-0">location_on</span>
                                    <span class="line-clamp-2"><?= htmlspecialchars($dest['lokasi']) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm flex-shrink-0">schedule</span>
                                    <span><?= htmlspecialchars($dest['jam_buka']) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm flex-shrink-0">confirmation_number</span>
                                    <span class="font-bold text-slate-900">Rp <?= number_format($dest['harga_tiket'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            
                            <!-- Right: Mini Map -->
                            <?php if (!empty($dest['latitude']) && !empty($dest['longitude'])): ?>
                            <div class="h-full" onclick="event.stopPropagation();">
                                <div id="map-<?= $dest['id'] ?>" 
                                     class="destination-map rounded-xl overflow-hidden border-2 border-slate-200 cursor-pointer hover:border-sky-400 transition-colors shadow-sm"
                                     data-lat="<?= $dest['latitude'] ?>"
                                     data-lng="<?= $dest['longitude'] ?>"
                                     data-name="<?= htmlspecialchars($dest['nama']) ?>"
                                     title="Click to open in Google Maps">
                                </div>
                            </div>
                            <?php else: ?>
                            <!-- Placeholder if no coordinates -->
                            <div class="h-full flex items-center justify-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 min-h-[120px]">
                                <div class="text-center text-slate-400 text-xs">
                                    <span class="material-symbols-outlined text-2xl mb-1">location_off</span>
                                    <p>No location data</p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="w-full bg-slate-100 border-t border-slate-200 mt-auto">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 py-6 gap-4 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-900" style="font-variation-settings: 'FILL' 1;">water</span>
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-sky-900">Ambon Oceanic</span>
        </div>
        <div class="text-sm text-slate-500">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</div>
    </div>
</footer>

<!-- Leaflet JS for Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all mini maps
    const mapElements = document.querySelectorAll('[id^="map-"]');
    
    mapElements.forEach(function(mapElement) {
        const lat = parseFloat(mapElement.dataset.lat);
        const lng = parseFloat(mapElement.dataset.lng);
        const name = mapElement.dataset.name;
        
        if (!isNaN(lat) && !isNaN(lng)) {
            // Create map
            const map = L.map(mapElement, {
                center: [lat, lng],
                zoom: 13,
                zoomControl: false,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                touchZoom: false
            });
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19
            }).addTo(map);
            
            // Custom marker icon
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background-color: #0ea5e9; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><span style="transform: rotate(45deg); font-size: 16px;">📍</span></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });
            
            // Add marker
            L.marker([lat, lng], { icon: customIcon }).addTo(map);
            
            // Click to open Google Maps (prevent card navigation)
            mapElement.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
            });
            
            // Prevent map interactions from triggering card click
            mapElement.addEventListener('mousedown', function(e) {
                e.stopPropagation();
            });
        }
    });
});
</script>

</body>
</html>
