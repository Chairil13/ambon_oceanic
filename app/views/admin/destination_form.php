<?php 
/**
 * @var array $categories - List of categories from controller
 * @var array|null $destination - Destination data (only for edit mode)
 */
$active_menu = 'destinations';
$page_title = isset($destination) ? 'Edit Destination' : 'Add New Destination';

// Parse operating hours data for edit mode
$operating_hours_mode = 'global';
$operating_hours_data = [];

if (isset($destination) && !empty($destination['operating_hours_data'])) {
    $operating_hours_mode = $destination['operating_hours_mode'] ?? 'global';
    $operating_hours_data = json_decode($destination['operating_hours_data'], true) ?? [];
}

require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <a href="<?= BASE_URL ?>admin" class="hover:text-sky-600 cursor-pointer">Portal</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="<?= BASE_URL ?>admin/destinations" class="hover:text-sky-600 cursor-pointer">Destinations</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600"><?= isset($destination) ? 'Edit' : 'Add New' ?></span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">
            <?= isset($destination) ? 'Edit Destination' : 'Add New Destination' ?>
        </h1>
        <p class="text-lg text-slate-600 font-medium">
            <?= isset($destination) ? 'Update destination information and details.' : 'Create a new tourism destination for Ambon.' ?>
        </p>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-sm text-red-800"><?= $_SESSION['error'] ?></p>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Form Card -->
<div class="max-w-4xl">
    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Form Header -->
        <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-sky-50 to-blue-50">
            <h3 class="font-bold text-xl text-slate-900">Destination Information</h3>
            <p class="text-sm text-slate-600 mt-1">Fill in all required fields marked with *</p>
        </div>

        <!-- Form Body -->
        <div class="p-6 space-y-6">
            <!-- Nama Destinasi -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="nama">
                    Destination Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">location_on</span>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        required
                        value="<?= htmlspecialchars($destination['nama'] ?? '') ?>"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                        placeholder="e.g., Pantai Natsepa"
                    />
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="deskripsi">
                    Description <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-4 text-slate-400">description</span>
                    <textarea 
                        id="deskripsi" 
                        name="deskripsi" 
                        required
                        rows="5"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all resize-none"
                        placeholder="Describe the destination, its attractions, and what makes it special..."
                    ><?= htmlspecialchars($destination['deskripsi'] ?? '') ?></textarea>
                </div>
                <p class="text-xs text-slate-500 mt-2">Provide a detailed description to help visitors understand what to expect.</p>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="lokasi">
                    Location <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">place</span>
                    <input 
                        type="text" 
                        id="lokasi" 
                        name="lokasi" 
                        required
                        value="<?= htmlspecialchars($destination['lokasi'] ?? '') ?>"
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                        placeholder="e.g., Salahutu, Ambon"
                    />
                </div>
            </div>

            <!-- Map Coordinates -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Map Location
                </label>
                <p class="text-xs text-slate-500 mb-3">Click on the map to set the destination location, or enter coordinates manually</p>
                
                <!-- Map Container -->
                <div id="map" class="w-full h-96 rounded-xl border-2 border-slate-200 mb-4"></div>
                
                <!-- Coordinates Input (2 columns) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2" for="latitude">
                            Latitude
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">my_location</span>
                            <input 
                                type="text" 
                                id="latitude" 
                                name="latitude" 
                                value="<?= htmlspecialchars($destination['latitude'] ?? '') ?>"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="-3.6954"
                                step="any"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2" for="longitude">
                            Longitude
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">my_location</span>
                            <input 
                                type="text" 
                                id="longitude" 
                                name="longitude" 
                                value="<?= htmlspecialchars($destination['longitude'] ?? '') ?>"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-900 text-sm focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="128.1814"
                                step="any"
                            />
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-2">💡 Tip: Search for the location on the map or drag the marker to adjust position</p>
            </div>

            <!-- Upload Multiple Images -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Destination Images <span class="text-red-500">*</span>
                </label>
                
                <!-- Existing Gallery Images (Edit Mode) -->
                <?php if (isset($gallery) && !empty($gallery)): ?>
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-600 mb-3">📸 Existing Images (<?= count($gallery) ?>)</p>
                    <div class="grid grid-cols-4 gap-3">
                        <?php foreach ($gallery as $img): ?>
                        <div class="relative group">
                            <img src="<?= BASE_URL . $img['image_path'] ?>" 
                                 class="w-full h-24 object-cover rounded-lg border-2 <?= $img['is_primary'] ? 'border-sky-500' : 'border-slate-200' ?>">
                            <?php if ($img['is_primary']): ?>
                            <span class="absolute top-1 left-1 bg-sky-500 text-white text-xs px-2 py-0.5 rounded">Primary</span>
                            <?php endif; ?>
                            <button type="button" 
                                    onclick="deleteGalleryImage(<?= $img['id'] ?>, this)"
                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">close</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Upload Area -->
                <input 
                    type="file" 
                    id="gambar_files" 
                    name="gambar_files[]" 
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    multiple
                    class="hidden"
                    onchange="previewMultipleImages(this)"
                />
                <label for="gambar_files" class="flex items-center justify-center w-full px-6 py-8 bg-gradient-to-br from-sky-50 to-blue-50 border-2 border-dashed border-sky-300 rounded-xl cursor-pointer hover:from-sky-100 hover:to-blue-100 hover:border-sky-400 transition-all group">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-5xl text-sky-600 mb-3 group-hover:scale-110 transition-transform">collections</span>
                        <p class="text-base font-bold text-sky-900 mb-1">Click to upload images</p>
                        <p class="text-sm text-sky-700 mb-2">or drag and drop here</p>
                        <p class="text-xs text-slate-500">
                            <span class="font-bold">💡 Tip:</span> Hold Ctrl (Windows) or Cmd (Mac) to select multiple files
                        </p>
                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, or GIF • Max 5MB per file</p>
                    </div>
                </label>
                
                <!-- Preview Container -->
                <div id="multiplePreviewContainer" class="grid grid-cols-4 gap-3 mt-4 hidden"></div>
                
                <p class="text-xs text-slate-500 mt-3">
                    <span class="material-symbols-outlined text-xs align-middle">info</span>
                    First image will be set as primary image
                </p>
            </div>

            <!-- Jam Buka, Hari Operasional & Harga Tiket -->
            <div class="space-y-6">
                <!-- Operating Hours Mode Toggle -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">
                        Operating Hours Setup
                    </label>
                    <div class="flex gap-4 mb-4">
                        <label class="flex items-center gap-3 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition-all has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                            <input 
                                type="radio" 
                                name="operating_hours_mode" 
                                value="global" 
                                <?= $operating_hours_mode === 'global' ? 'checked' : '' ?>
                                onchange="toggleOperatingHoursMode('global')"
                                class="w-4 h-4 text-sky-600 focus:ring-sky-500"
                            />
                            <div>
                                <p class="font-bold text-slate-900">Global Hours</p>
                                <p class="text-xs text-slate-500">Same hours for all days</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition-all has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                            <input 
                                type="radio" 
                                name="operating_hours_mode" 
                                value="per_day" 
                                <?= $operating_hours_mode === 'per_day' ? 'checked' : '' ?>
                                onchange="toggleOperatingHoursMode('per_day')"
                                class="w-4 h-4 text-sky-600 focus:ring-sky-500"
                            />
                            <div>
                                <p class="font-bold text-slate-900">Per Day Hours</p>
                                <p class="text-xs text-slate-500">Different hours for each day</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Global Mode -->
                <div id="globalHoursSection" class="grid grid-cols-1 md:grid-cols-3 gap-6 <?= $operating_hours_mode === 'per_day' ? 'hidden' : '' ?>">
                    <!-- Jam Buka Global -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="jam_buka">
                            Opening Hours
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">schedule</span>
                            <input 
                                type="text" 
                                id="jam_buka" 
                                name="jam_buka" 
                                value="<?= htmlspecialchars($destination['jam_buka'] ?? '') ?>"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="08:00 - 17:00"
                            />
                        </div>
                    </div>

                    <!-- Hari Operasional -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="hari_operasional">
                            Operating Days
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">calendar_today</span>
                            <input 
                                type="text" 
                                id="hari_operasional" 
                                name="hari_operasional" 
                                value="<?= htmlspecialchars($destination['hari_operasional'] ?? 'Setiap Hari') ?>"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="e.g., Setiap Hari, Senin - Sabtu"
                            />
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Contoh: Setiap Hari, Senin - Jumat, Sabtu - Minggu</p>
                    </div>

                    <!-- Harga Tiket -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="harga_tiket">
                            Ticket Price (Rp)
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">payments</span>
                            <input 
                                type="number" 
                                id="harga_tiket" 
                                name="harga_tiket" 
                                min="0"
                                value="<?= htmlspecialchars($destination['harga_tiket'] ?? 0) ?>"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="0"
                            />
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Enter 0 for free entry</p>
                    </div>
                </div>

                <!-- Per Day Mode -->
                <div id="perDayHoursSection" class="<?= $operating_hours_mode === 'global' ? 'hidden' : '' ?>">
                    <div class="bg-gradient-to-br from-sky-50 to-blue-50 rounded-xl p-6 border-2 border-sky-200">
                        <h4 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sky-600">event_note</span>
                            Set Hours for Each Day
                        </h4>
                        
                        <div class="space-y-3">
                            <?php 
                            $days = [
                                'senin' => 'Senin',
                                'selasa' => 'Selasa',
                                'rabu' => 'Rabu',
                                'kamis' => 'Kamis',
                                'jumat' => 'Jumat',
                                'sabtu' => 'Sabtu',
                                'minggu' => 'Minggu'
                            ];
                            
                            foreach ($days as $key => $label): 
                                $dayData = $operating_hours_data[$key] ?? ['is_open' => true, 'open' => '08:00', 'close' => '17:00'];
                                $isOpen = $dayData['is_open'] ?? true;
                            ?>
                            <div class="bg-white rounded-lg p-4 border border-slate-200">
                                <div class="flex items-center gap-4">
                                    <!-- Checkbox Open/Closed -->
                                    <label class="flex items-center gap-2 min-w-[120px]">
                                        <input 
                                            type="checkbox" 
                                            name="day_<?= $key ?>_is_open" 
                                            value="1"
                                            <?= $isOpen ? 'checked' : '' ?>
                                            onchange="toggleDayInputs('<?= $key ?>')"
                                            class="w-4 h-4 text-sky-600 rounded focus:ring-sky-500"
                                        />
                                        <span class="font-bold text-slate-900"><?= $label ?></span>
                                    </label>
                                    
                                    <!-- Time Inputs -->
                                    <div class="flex items-center gap-3 flex-1 <?= !$isOpen ? 'hidden' : '' ?>" id="day_<?= $key ?>_inputs">
                                        <div class="flex items-center gap-2 flex-1">
                                            <span class="material-symbols-outlined text-slate-400 text-sm">schedule</span>
                                            <input 
                                                type="time" 
                                                name="day_<?= $key ?>_open" 
                                                value="<?= htmlspecialchars($dayData['open'] ?? '08:00') ?>"
                                                class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
                                            />
                                        </div>
                                        <span class="text-slate-400 font-bold">-</span>
                                        <div class="flex items-center gap-2 flex-1">
                                            <span class="material-symbols-outlined text-slate-400 text-sm">schedule</span>
                                            <input 
                                                type="time" 
                                                name="day_<?= $key ?>_close" 
                                                value="<?= htmlspecialchars($dayData['close'] ?? '17:00') ?>"
                                                class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500"
                                            />
                                        </div>
                                    </div>
                                    
                                    <!-- Closed Label -->
                                    <div class="<?= $isOpen ? 'hidden' : '' ?> text-red-600 font-bold text-sm" id="day_<?= $key ?>_closed">
                                        CLOSED
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <p class="text-xs text-slate-500 mt-4">
                            <span class="material-symbols-outlined text-xs align-middle">info</span>
                            Uncheck a day to mark it as closed
                        </p>
                    </div>
                    
                    <!-- Harga Tiket (in per day mode) -->
                    <div class="mt-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2" for="harga_tiket_perday">
                            Ticket Price (Rp)
                        </label>
                        <div class="relative max-w-xs">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">payments</span>
                            <input 
                                type="number" 
                                id="harga_tiket_perday" 
                                name="harga_tiket_perday" 
                                min="0"
                                value="<?= htmlspecialchars($destination['harga_tiket'] ?? 0) ?>"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                                placeholder="0"
                            />
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Enter 0 for free entry</p>
                    </div>
                </div>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2" for="kategori_id">
                    Category <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">category</span>
                    <select 
                        id="kategori_id" 
                        name="kategori_id" 
                        required
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all appearance-none cursor-pointer"
                    >
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" 
                                    <?= (isset($destination) && $destination['kategori_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>

        <!-- Form Footer -->
        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center gap-4">
            <button 
                type="submit"
                class="px-8 py-3 bg-sky-600 text-white rounded-full font-bold shadow-lg shadow-sky-600/20 hover:bg-sky-700 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                <?= isset($destination) ? 'Update Destination' : 'Create Destination' ?>
            </button>
            <a 
                href="<?= BASE_URL ?>admin/destinations"
                class="px-8 py-3 bg-white text-slate-700 border border-slate-200 rounded-full font-bold hover:bg-slate-50 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">close</span>
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Operating Hours Mode Toggle
function toggleOperatingHoursMode(mode) {
    const globalSection = document.getElementById('globalHoursSection');
    const perDaySection = document.getElementById('perDayHoursSection');
    
    if (mode === 'global') {
        globalSection.classList.remove('hidden');
        perDaySection.classList.add('hidden');
    } else {
        globalSection.classList.add('hidden');
        perDaySection.classList.remove('hidden');
    }
}

// Toggle day inputs when checkbox is changed
function toggleDayInputs(day) {
    const checkbox = document.querySelector(`input[name="day_${day}_is_open"]`);
    const inputs = document.getElementById(`day_${day}_inputs`);
    const closedLabel = document.getElementById(`day_${day}_closed`);
    
    if (checkbox.checked) {
        inputs.classList.remove('hidden');
        closedLabel.classList.add('hidden');
    } else {
        inputs.classList.add('hidden');
        closedLabel.classList.remove('hidden');
    }
}

// Map initialization
let map;
let marker;
const defaultLat = -3.6954; // Ambon default
const defaultLng = 128.1814;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    const lng = parseFloat(document.getElementById('longitude').value) || defaultLng;
    
    map = L.map('map').setView([lat, lng], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Add marker
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Update coordinates when marker is dragged
    marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });
    
    // Update coordinates when map is clicked
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });
    
    // Update marker when coordinates are manually entered
    document.getElementById('latitude').addEventListener('change', function() {
        updateMarkerFromInputs();
    });
    
    document.getElementById('longitude').addEventListener('change', function() {
        updateMarkerFromInputs();
    });
});

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
}

function updateMarkerFromInputs() {
    const lat = parseFloat(document.getElementById('latitude').value);
    const lng = parseFloat(document.getElementById('longitude').value);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 13);
    }
}

// Image handling - Multiple images preview
function previewMultipleImages(input) {
    const container = document.getElementById('multiplePreviewContainer');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        container.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            // Check file size
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar (max 5MB)`);
                return;
            }
            
            // Check file type
            if (!file.type.match('image.*')) {
                alert(`File ${file.name} bukan gambar`);
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border-2 ${index === 0 ? 'border-sky-500' : 'border-slate-200'}">
                    ${index === 0 ? '<span class="absolute top-1 left-1 bg-sky-500 text-white text-xs px-2 py-0.5 rounded">Primary</span>' : ''}
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                        <span class="text-white text-xs font-bold px-2 py-1 bg-black/70 rounded">${file.name}</span>
                    </div>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        // Show count
        const countDiv = document.createElement('div');
        countDiv.className = 'col-span-4 text-center text-sm text-slate-600 font-bold mt-2';
        countDiv.innerHTML = `✅ ${input.files.length} image(s) selected`;
        container.appendChild(countDiv);
    } else {
        container.classList.add('hidden');
    }
}

function deleteGalleryImage(imageId, button) {
    if (!confirm('Hapus gambar ini?')) return;
    
    fetch('<?= BASE_URL ?>admin/deleteGalleryImage/' + imageId, {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            button.closest('.relative').remove();
            // Update count if exists
            const countElement = document.querySelector('.text-xs.font-bold.text-slate-600');
            if (countElement) {
                const currentCount = parseInt(countElement.textContent.match(/\d+/)[0]);
                countElement.textContent = `📸 Existing Images (${currentCount - 1})`;
            }
        } else {
            alert('Gagal menghapus gambar');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi error');
    });
}

// Remove old functions that are no longer needed
function switchImageTab() { /* deprecated */ }
function previewImageFile() { /* deprecated */ }
function previewImageUrl() { /* deprecated */ }
function clearImage() { /* deprecated */ }

function switchImageTab(tab) {
    currentImageTab = tab;
    const uploadTab = document.getElementById('uploadTab');
    const urlTab = document.getElementById('urlTab');
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');
    const urlInput = document.getElementById('gambar');
    const fileInput = document.getElementById('gambar_file');
    
    if (tab === 'upload') {
        // Style upload tab as active
        uploadTab.className = 'flex-1 px-4 py-3 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all text-sm';
        urlTab.className = 'flex-1 px-4 py-3 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all text-sm';
        
        // Show upload section
        uploadSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        
        // Clear URL input only if it's empty or user hasn't entered anything
        if (urlInput.value.trim() === '' || !urlInput.value.startsWith('http')) {
            urlInput.value = '';
        }
    } else {
        // Style URL tab as active
        urlTab.className = 'flex-1 px-4 py-3 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all text-sm';
        uploadTab.className = 'flex-1 px-4 py-3 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all text-sm';
        
        // Show URL section
        urlSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        
        // Clear file input and preview
        fileInput.value = '';
        document.getElementById('fileName').classList.add('hidden');
    }
}

function previewImageFile(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            input.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPG, PNG, or GIF)');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            fileName.textContent = '📁 Selected: ' + file.name;
            fileName.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
    }
}

function previewImageUrl(url) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    url = url.trim();
    
    if (url) {
        previewImg.src = url;
        previewImg.onerror = function() {
            alert('Failed to load image from URL. Please check the URL and try again.');
            preview.classList.add('hidden');
        };
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function clearImage() {
    const fileInput = document.getElementById('gambar_file');
    const urlInput = document.getElementById('gambar');
    const preview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    
    fileInput.value = '';
    urlInput.value = '';
    preview.classList.add('hidden');
    fileName.classList.add('hidden');
}

// Preview on page load if editing
<?php if (isset($destination) && !empty($destination['gambar'])): ?>
    // Switch to URL tab and show existing image
    switchImageTab('url');
    previewImageUrl('<?= htmlspecialchars($destination['gambar']) ?>');
<?php endif; ?>
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
