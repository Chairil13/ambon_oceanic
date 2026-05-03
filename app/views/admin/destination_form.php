<?php 
$active_menu = 'destinations';
$page_title = isset($destination) ? 'Edit Destination' : 'Add New Destination';
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
    <form method="POST" class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
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

            <!-- URL Gambar / Upload -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Destination Image <span class="text-red-500">*</span>
                </label>
                
                <!-- Tab Navigation -->
                <div class="flex border-b border-slate-200 mb-4">
                    <button 
                        type="button"
                        onclick="switchImageTab('upload')" 
                        id="uploadTab"
                        class="flex-1 px-4 py-3 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all text-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">cloud_upload</span>
                        Upload File
                    </button>
                    <button 
                        type="button"
                        onclick="switchImageTab('url')" 
                        id="urlTab"
                        class="flex-1 px-4 py-3 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all text-sm">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">link</span>
                        Image URL
                    </button>
                </div>

                <!-- Upload File Section -->
                <div id="uploadSection">
                    <input 
                        type="file" 
                        id="gambar_file" 
                        name="gambar_file" 
                        accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="hidden"
                        onchange="previewImageFile(this)"
                    />
                    <label for="gambar_file" class="flex items-center justify-center w-full px-6 py-4 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-100 hover:border-sky-400 transition-all">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">add_photo_alternate</span>
                            <p class="text-sm font-bold text-slate-700">Click to upload image</p>
                            <p class="text-xs text-slate-500 mt-1">JPG, PNG, or GIF (Max 5MB)</p>
                        </div>
                    </label>
                    <p id="fileName" class="text-xs text-slate-600 mt-2 hidden"></p>
                </div>

                <!-- URL Input Section -->
                <div id="urlSection" class="hidden">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">image</span>
                        <input 
                            type="text" 
                            id="gambar" 
                            name="gambar" 
                            value="<?= htmlspecialchars($destination['gambar'] ?? '') ?>"
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                            placeholder="https://example.com/image.jpg"
                            onchange="previewImageUrl(this.value)"
                        />
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Enter image URL or relative path</p>
                </div>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <p class="text-xs font-bold text-slate-700 mb-2">Preview:</p>
                    <div class="relative inline-block">
                        <img id="previewImg" src="" alt="Preview" class="w-64 h-40 object-cover rounded-xl border-2 border-slate-200 shadow-sm" onerror="this.parentElement.parentElement.classList.add('hidden')">
                        <button type="button" onclick="clearImage()" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-all flex items-center justify-center shadow-lg">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Jam Buka & Harga Tiket (2 columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jam Buka -->
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

<script>
let currentImageTab = 'upload';

function switchImageTab(tab) {
    currentImageTab = tab;
    const uploadTab = document.getElementById('uploadTab');
    const urlTab = document.getElementById('urlTab');
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');
    
    if (tab === 'upload') {
        // Style upload tab as active
        uploadTab.className = 'flex-1 px-4 py-3 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all text-sm';
        urlTab.className = 'flex-1 px-4 py-3 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all text-sm';
        
        // Show upload section
        uploadSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        
        // Clear URL input
        document.getElementById('gambar').value = '';
    } else {
        // Style URL tab as active
        urlTab.className = 'flex-1 px-4 py-3 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all text-sm';
        uploadTab.className = 'flex-1 px-4 py-3 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all text-sm';
        
        // Show URL section
        urlSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        
        // Clear file input
        document.getElementById('gambar_file').value = '';
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
