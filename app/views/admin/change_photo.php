<?php 
$active_menu = '';
$page_title = 'Change Photo';
require_once __DIR__ . '/layouts/header.php'; 
?>

<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
    <div>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 font-bold tracking-tight">
            <a href="<?= BASE_URL ?>admin" class="hover:text-sky-600 cursor-pointer">Portal</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-sky-600">Change Photo</span>
        </nav>
        <h1 class="text-5xl font-extrabold text-slate-900 tracking-tight mb-2 font-display">Change Profile Photo</h1>
        <p class="text-lg text-slate-600 font-medium">Update your admin profile picture.</p>
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

<!-- Change Photo Form -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50">
            <h3 class="font-bold text-xl text-slate-900">Update Profile Photo</h3>
            <p class="text-sm text-slate-600 mt-1">Upload a photo from your computer or enter an image URL.</p>
        </div>
        
        <!-- Tab Navigation -->
        <div class="flex border-b border-slate-100">
            <button 
                onclick="switchTab('upload')" 
                id="uploadTab"
                class="flex-1 px-6 py-4 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all">
                <span class="material-symbols-outlined text-sm align-middle mr-2">cloud_upload</span>
                Upload File
            </button>
            <button 
                onclick="switchTab('url')" 
                id="urlTab"
                class="flex-1 px-6 py-4 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all">
                <span class="material-symbols-outlined text-sm align-middle mr-2">link</span>
                Image URL
            </button>
        </div>
        
        <form method="POST" action="<?= BASE_URL ?>admin/changePhoto" enctype="multipart/form-data" class="p-6 space-y-6">
            <!-- Current Photo Preview -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Current Photo</label>
                <div class="flex items-center gap-4">
                    <?php if (!empty($admin['photo'])): ?>
                        <?php 
                        // Check if photo is URL or local path
                        $photoSrc = filter_var($admin['photo'], FILTER_VALIDATE_URL) 
                            ? $admin['photo'] 
                            : BASE_URL . $admin['photo'];
                        ?>
                        <img src="<?= htmlspecialchars($photoSrc) ?>" 
                             alt="Current photo" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-slate-100"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-24 h-24 rounded-full bg-sky-600 flex items-center justify-center text-white font-bold text-3xl" style="display: none;">
                            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                        </div>
                    <?php else: ?>
                        <div class="w-24 h-24 rounded-full bg-sky-600 flex items-center justify-center text-white font-bold text-3xl">
                            <?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="font-bold text-slate-900"><?= $_SESSION['admin_username'] ?? 'Admin' ?></p>
                        <p class="text-sm text-slate-500">Administrator</p>
                    </div>
                </div>
            </div>

            <!-- Upload File Section -->
            <div id="uploadSection">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="photo_file">
                        Choose New Photo
                    </label>
                    <div class="relative">
                        <input 
                            type="file" 
                            id="photo_file" 
                            name="photo_file" 
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="hidden"
                            onchange="previewPhotoFile(this)"
                        />
                        <label for="photo_file" class="flex items-center justify-center w-full px-6 py-4 bg-slate-50 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-100 hover:border-sky-400 transition-all">
                            <div class="text-center">
                                <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">cloud_upload</span>
                                <p class="text-sm font-bold text-slate-700">Click to upload photo</p>
                                <p class="text-xs text-slate-500 mt-1">JPG, PNG, or GIF (Max 2MB)</p>
                            </div>
                        </label>
                    </div>
                    <p id="fileName" class="text-xs text-slate-600 mt-2 hidden"></p>
                </div>
            </div>

            <!-- URL Input Section -->
            <div id="urlSection" class="hidden">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="photo_url">
                        Image URL
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">image</span>
                        <input 
                            type="url" 
                            id="photo_url" 
                            name="photo_url" 
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all"
                            placeholder="https://example.com/photo.jpg"
                            onchange="previewPhotoUrl(this.value)"
                        />
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Enter a valid image URL (JPG, PNG, or GIF)</p>
                </div>
            </div>

            <!-- Photo Preview -->
            <div id="photoPreview" class="hidden">
                <label class="block text-sm font-bold text-slate-700 mb-3">Preview</label>
                <div class="flex items-center gap-4">
                    <img id="previewImage" 
                         src="" 
                         alt="Preview" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-sky-100 shadow-lg"/>
                    <div>
                        <p class="text-sm text-slate-600">This will be your new profile photo</p>
                        <button type="button" onclick="clearPhoto()" class="text-xs text-red-600 hover:underline mt-2">
                            Remove and choose another
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-4">
                <button 
                    type="submit"
                    id="submitBtn"
                    class="px-8 py-3 bg-sky-600 text-white rounded-full font-bold shadow-lg shadow-sky-600/20 hover:bg-sky-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Update Photo
                </button>
                <a 
                    href="<?= BASE_URL ?>admin"
                    class="px-8 py-3 bg-slate-100 text-slate-700 rounded-full font-bold hover:bg-slate-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Tips -->
    <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-6">
        <div class="flex gap-3">
            <span class="material-symbols-outlined text-blue-600">info</span>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Photo Guidelines</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Use a clear, professional photo</li>
                    <li>• Square images work best (1:1 ratio)</li>
                    <li>• Recommended size: at least 200x200 pixels</li>
                    <li>• Maximum file size: 2MB (for uploads)</li>
                    <li>• Supported formats: JPG, PNG, GIF</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
let currentTab = 'upload';

function switchTab(tab) {
    currentTab = tab;
    const uploadTab = document.getElementById('uploadTab');
    const urlTab = document.getElementById('urlTab');
    const uploadSection = document.getElementById('uploadSection');
    const urlSection = document.getElementById('urlSection');
    
    if (tab === 'upload') {
        // Style upload tab as active
        uploadTab.className = 'flex-1 px-6 py-4 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all';
        urlTab.className = 'flex-1 px-6 py-4 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all';
        
        // Show upload section
        uploadSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        
        // Clear URL input
        document.getElementById('photo_url').value = '';
    } else {
        // Style URL tab as active
        urlTab.className = 'flex-1 px-6 py-4 font-bold text-sky-600 border-b-2 border-sky-600 bg-sky-50/50 transition-all';
        uploadTab.className = 'flex-1 px-6 py-4 font-bold text-slate-500 border-b-2 border-transparent hover:bg-slate-50 transition-all';
        
        // Show URL section
        urlSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        
        // Clear file input
        document.getElementById('photo_file').value = '';
        document.getElementById('fileName').classList.add('hidden');
    }
    
    // Clear preview
    clearPhoto();
}

function previewPhotoFile(input) {
    const preview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
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
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
            fileName.textContent = '📁 Selected: ' + file.name;
            fileName.classList.remove('hidden');
        };
        
        reader.readAsDataURL(file);
    }
}

function previewPhotoUrl(url) {
    const preview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    
    if (url) {
        previewImage.src = url;
        previewImage.onerror = function() {
            alert('Failed to load image from URL. Please check the URL and try again.');
            preview.classList.add('hidden');
        };
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

function clearPhoto() {
    const fileInput = document.getElementById('photo_file');
    const urlInput = document.getElementById('photo_url');
    const preview = document.getElementById('photoPreview');
    const fileName = document.getElementById('fileName');
    
    fileInput.value = '';
    urlInput.value = '';
    preview.classList.add('hidden');
    fileName.classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
