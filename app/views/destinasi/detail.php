<?php
/**
 * @var string $title - Page title from controller
 * @var array $destination - Destination details
 * @var bool $isFavorite - Whether destination is in user's favorites
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
            <a class="text-sky-700" href="<?= BASE_URL ?>destinasi">Destinasi</a>
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
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Image Slider -->
            <?php
            $galleryModel = $this->model('DestinasiGallery');
            $images = $galleryModel->getByDestinasiId($destination['id']);
            
            // If no images in gallery, use main image
            if (empty($images) && !empty($destination['gambar'])) {
                $images = [['image_path' => $destination['gambar'], 'is_primary' => 1]];
            }
            ?>
            
            <?php if (!empty($images)): ?>
            <div class="relative h-96 rounded-3xl overflow-hidden bg-slate-900 group">
                <!-- Images Container -->
                <div id="imageSlider" class="relative h-full">
                    <?php foreach ($images as $index => $img): ?>
                    <div class="slider-item absolute inset-0 transition-opacity duration-500 <?= $index === 0 ? 'opacity-100' : 'opacity-0' ?>" data-index="<?= $index ?>">
                        <?php 
                        $imageSrc = $img['image_path'];
                        if (!filter_var($imageSrc, FILTER_VALIDATE_URL)) {
                            $imageSrc = BASE_URL . $imageSrc;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($imageSrc) ?>" 
                             alt="<?= htmlspecialchars($destination['nama']) ?>" 
                             class="w-full h-full object-cover"
                             onerror="this.src='<?= BASE_URL ?>public/assets/images/logo.png'"/>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($images) > 1): ?>
                <!-- Navigation Buttons -->
                <button onclick="prevSlide()" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                    <span class="material-symbols-outlined text-slate-900">chevron_left</span>
                </button>
                <button onclick="nextSlide()" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:scale-110">
                    <span class="material-symbols-outlined text-slate-900">chevron_right</span>
                </button>
                
                <!-- Indicators -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <?php foreach ($images as $index => $img): ?>
                    <button onclick="goToSlide(<?= $index ?>)" 
                            class="indicator w-2 h-2 rounded-full transition-all <?= $index === 0 ? 'bg-white w-8' : 'bg-white/50 hover:bg-white/75' ?>" 
                            data-index="<?= $index ?>"></button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Image Counter -->
                <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm font-bold backdrop-blur-sm">
                    <span id="currentSlide">1</span> / <?= count($images) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <!-- Fallback if no images -->
            <div class="relative h-96 rounded-3xl overflow-hidden bg-slate-200 flex items-center justify-center">
                <div class="text-center text-slate-400">
                    <span class="material-symbols-outlined text-6xl mb-2">image</span>
                    <p>No image available</p>
                </div>
            </div>
            <?php endif; ?>
            
            <div>
                <span class="inline-block bg-cyan-100 text-cyan-900 text-sm font-bold px-4 py-2 rounded-full mb-4">
                    <?= htmlspecialchars($destination['kategori_nama']) ?>
                </span>
                <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-4"><?= htmlspecialchars($destination['nama']) ?></h1>
                <p class="text-lg text-slate-600 leading-relaxed"><?= nl2br(htmlspecialchars($destination['deskripsi'])) ?></p>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-8 shadow-sm">
                <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-6">Informasi</h3>
                
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-red-600" style="font-variation-settings: 'FILL' 1;">location_on</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-500 mb-1">Lokasi</p>
                            <p class="text-slate-900"><?= htmlspecialchars($destination['lokasi']) ?></p>
                        </div>
                    </div>
                    
                    <?php 
                    $operating_mode = $destination['operating_hours_mode'] ?? 'global';
                    $operating_data = !empty($destination['operating_hours_data']) ? json_decode($destination['operating_hours_data'], true) : [];
                    ?>
                    
                    <?php if ($operating_mode === 'per_day' && !empty($operating_data)): ?>
                        <!-- Per Day Operating Hours -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">schedule</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-sm text-slate-500 mb-2">Jam Operasional</p>
                                <div class="space-y-1.5">
                                    <?php 
                                    $days_id = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                                    $days_label = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                    
                                    foreach ($days_id as $index => $day_id):
                                        $day_info = $operating_data[$day_id] ?? ['is_open' => false];
                                        $is_open = $day_info['is_open'] ?? false;
                                    ?>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="font-medium text-slate-700 min-w-[70px]"><?= $days_label[$index] ?></span>
                                            <?php if ($is_open): ?>
                                                <span class="text-slate-900 font-bold">
                                                    <?= htmlspecialchars($day_info['open'] ?? '08:00') ?> - <?= htmlspecialchars($day_info['close'] ?? '17:00') ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-red-600 font-bold">TUTUP</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Global Operating Hours -->
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-amber-600" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-500 mb-1">Hari Operasional</p>
                                <p class="text-slate-900"><?= htmlspecialchars($destination['hari_operasional'] ?? 'Setiap Hari') ?></p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">schedule</span>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-500 mb-1">Jam Buka</p>
                                <p class="text-slate-900"><?= htmlspecialchars($destination['jam_buka']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
                        </div>
                        <div>
                            <p class="font-bold text-sm text-slate-500 mb-1">Harga Tiket</p>
                            <p class="text-slate-900 font-bold text-xl">Rp <?= number_format($destination['harga_tiket'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 border-t border-slate-200">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button id="favoriteBtn" data-id="<?= $destination['id'] ?>" data-favorite="<?= $isFavorite ? '1' : '0' ?>"
                                class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-2xl px-6 py-4 font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' <?= $isFavorite ? '1' : '0' ?>;">favorite</span>
                            <?= $isFavorite ? 'Hapus dari Favorit' : 'Tambah ke Favorit' ?>
                        </button>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>auth/login" 
                           class="w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-2xl px-6 py-4 font-bold flex items-center justify-center gap-2 transition-all">
                            <span class="material-symbols-outlined">favorite</span>
                            Login untuk Favorit
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Map Section - Below Info Card -->
            <?php if (!empty($destination['latitude']) && !empty($destination['longitude'])): ?>
            <div class="bg-white rounded-3xl p-8 shadow-sm">
                <h3 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-600" style="font-variation-settings: 'FILL' 1;">map</span>
                    Location Map
                </h3>
                <div id="detailMap" class="w-full h-96 rounded-2xl overflow-hidden border-2 border-slate-200"></div>
                <p class="text-sm text-slate-500 mt-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">info</span>
                    Click on the map to open in Google Maps for directions
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
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
// Initialize map if coordinates exist
<?php if (!empty($destination['latitude']) && !empty($destination['longitude'])): ?>
document.addEventListener('DOMContentLoaded', function() {
    const lat = <?= $destination['latitude'] ?>;
    const lng = <?= $destination['longitude'] ?>;
    const destinationName = <?= json_encode($destination['nama']) ?>;
    
    // Create map
    const map = L.map('detailMap').setView([lat, lng], 15);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Custom icon for marker
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #0ea5e9; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><span style="transform: rotate(45deg); color: white; font-size: 20px;">📍</span></div>',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    // Add marker with popup
    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
    marker.bindPopup(`
        <div style="text-align: center; padding: 8px;">
            <strong style="font-size: 16px; color: #0f172a;">${destinationName}</strong><br>
            <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" 
               style="color: #0ea5e9; text-decoration: none; font-size: 14px; margin-top: 8px; display: inline-block;">
                🗺️ Open in Google Maps
            </a>
        </div>
    `).openPopup();
    
    // Open Google Maps on marker click
    marker.on('click', function() {
        window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
    });
});
<?php endif; ?>

// Image Slider Functions
let currentSlideIndex = 0;
const sliderItems = document.querySelectorAll('.slider-item');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = sliderItems.length;

function showSlide(index) {
    // Hide all slides
    sliderItems.forEach(item => {
        item.classList.remove('opacity-100');
        item.classList.add('opacity-0');
    });
    
    // Show current slide
    if (sliderItems[index]) {
        sliderItems[index].classList.remove('opacity-0');
        sliderItems[index].classList.add('opacity-100');
    }
    
    // Update indicators
    indicators.forEach((indicator, i) => {
        if (i === index) {
            indicator.classList.remove('bg-white/50', 'w-2');
            indicator.classList.add('bg-white', 'w-8');
        } else {
            indicator.classList.remove('bg-white', 'w-8');
            indicator.classList.add('bg-white/50', 'w-2');
        }
    });
    
    // Update counter
    const counterElement = document.getElementById('currentSlide');
    if (counterElement) {
        counterElement.textContent = index + 1;
    }
    
    currentSlideIndex = index;
}

function nextSlide() {
    let nextIndex = currentSlideIndex + 1;
    if (nextIndex >= totalSlides) {
        nextIndex = 0; // Loop back to first slide
    }
    showSlide(nextIndex);
}

function prevSlide() {
    let prevIndex = currentSlideIndex - 1;
    if (prevIndex < 0) {
        prevIndex = totalSlides - 1; // Loop to last slide
    }
    showSlide(prevIndex);
}

function goToSlide(index) {
    if (index >= 0 && index < totalSlides) {
        showSlide(index);
    }
}

// Auto-play slider (optional - uncomment to enable)
// let autoPlayInterval;
// function startAutoPlay() {
//     autoPlayInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
// }
// function stopAutoPlay() {
//     clearInterval(autoPlayInterval);
// }
// if (totalSlides > 1) {
//     startAutoPlay();
//     // Pause on hover
//     document.getElementById('imageSlider')?.addEventListener('mouseenter', stopAutoPlay);
//     document.getElementById('imageSlider')?.addEventListener('mouseleave', startAutoPlay);
// }

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (totalSlides > 1) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        }
    }
});

// Favorite button handler
document.getElementById('favoriteBtn')?.addEventListener('click', function() {
    const btn = this;
    const destinasiId = btn.dataset.id;
    const isFavorite = btn.dataset.favorite === '1';
    
    fetch('<?= BASE_URL ?>auth/toggleFavorite', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({destinasi_id: destinasiId})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 1;">favorite</span> Hapus dari Favorit';
                btn.dataset.favorite = '1';
            } else {
                btn.innerHTML = '<span class="material-symbols-outlined" style="font-variation-settings: \'FILL\' 0;">favorite</span> Tambah ke Favorit';
                btn.dataset.favorite = '0';
            }
        }
    });
});
</script>

</body>
</html>
