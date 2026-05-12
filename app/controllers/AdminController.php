<?php

class AdminController extends Controller {
    
    private function checkAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('admin/login');
            exit;
        }
    }

    public function index() {
        $this->checkAdmin();
        
        $destinasiModel = $this->model('Destinasi');
        $userModel = $this->model('Admin');
        $chatModel = $this->model('Chat');
        
        $data = [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'total_destinations' => count($destinasiModel->getAll()),
            'total_users' => count($userModel->getAllUsers()),
            'recent_chats' => $chatModel->getAllLogs(10)
        ];
        
        $this->view('admin/dashboard', $data);
    }

    public function login() {
        if ($this->isAdmin()) {
            $this->redirect('admin');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $adminModel = $this->model('Admin');
            $admin = $adminModel->login($username, $password);
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                $this->redirect('admin');
                return;
            } else {
                $_SESSION['error'] = 'Username atau password salah';
            }
        }
        
        $data = ['title' => 'Admin Login - ' . APP_NAME];
        $this->view('admin/login', $data);
    }

    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        $this->redirect('admin/login');
    }

    public function destinations() {
        $this->checkAdmin();
        
        $destinasiModel = $this->model('Destinasi');
        $kategoriModel = $this->model('Kategori');
        
        $data = [
            'title' => 'Kelola Destinasi - ' . APP_NAME,
            'destinations' => $destinasiModel->getAll(),
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('admin/destinations', $data);
    }

    public function createDestination() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destinasiModel = $this->model('Destinasi');
            $galleryModel = $this->model('DestinasiGallery');
            $gambarPath = '';
            
            // Create uploads directory if not exists
            $uploadDir = 'public/assets/images/destinations/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Handle multiple images upload
            if (isset($_FILES['gambar_files']) && !empty($_FILES['gambar_files']['name'][0])) {
                $files = $_FILES['gambar_files'];
                $fileCount = count($files['name']);
                $uploadedImages = [];
                
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        // Validate file type
                        $fileType = mime_content_type($files['tmp_name'][$i]);
                        if (!in_array($fileType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
                            continue; // Skip invalid files
                        }
                        
                        // Validate file size
                        if ($files['size'][$i] > 5 * 1024 * 1024) {
                            continue; // Skip files > 5MB
                        }
                        
                        // Generate unique filename
                        $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $filename = 'dest_' . time() . '_' . uniqid() . '_' . $i . '.' . $extension;
                        $uploadPath = $uploadDir . $filename;
                        
                        // Move uploaded file
                        if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                            $uploadedImages[] = $uploadPath;
                        }
                    }
                }
                
                if (empty($uploadedImages)) {
                    $_SESSION['error'] = 'Tidak ada gambar yang berhasil diupload';
                    $this->redirect('admin/createDestination');
                    return;
                }
                
                // First image as primary
                $gambarPath = $uploadedImages[0];
            } else {
                $_SESSION['error'] = 'Silakan pilih minimal 1 gambar';
                $this->redirect('admin/createDestination');
                return;
            }
            
            // Handle operating hours data
            $operating_hours_mode = $_POST['operating_hours_mode'] ?? 'global';
            $operating_hours_data = [];
            
            if ($operating_hours_mode === 'per_day') {
                $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                foreach ($days as $day) {
                    $is_open = isset($_POST["day_{$day}_is_open"]) ? true : false;
                    $operating_hours_data[$day] = [
                        'is_open' => $is_open,
                        'open' => $is_open ? ($_POST["day_{$day}_open"] ?? '08:00') : '',
                        'close' => $is_open ? ($_POST["day_{$day}_close"] ?? '17:00') : ''
                    ];
                }
                // Use harga_tiket_perday for per day mode
                $harga_tiket = $_POST['harga_tiket_perday'] ?? 0;
            } else {
                $operating_hours_data['global'] = $_POST['jam_buka'] ?? '';
                $harga_tiket = $_POST['harga_tiket'] ?? 0;
            }
            
            $data = [
                'nama' => $_POST['nama'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'lokasi' => $_POST['lokasi'] ?? '',
                'latitude' => !empty($_POST['latitude']) ? $_POST['latitude'] : null,
                'longitude' => !empty($_POST['longitude']) ? $_POST['longitude'] : null,
                'gambar' => $gambarPath,
                'jam_buka' => $_POST['jam_buka'] ?? '',
                'hari_operasional' => $_POST['hari_operasional'] ?? 'Setiap Hari',
                'operating_hours_mode' => $operating_hours_mode,
                'operating_hours_data' => json_encode($operating_hours_data),
                'harga_tiket' => $harga_tiket,
                'kategori_id' => $_POST['kategori_id'] ?? ''
            ];
            
            if ($destinasiModel->create($data)) {
                // Get the last inserted ID
                $destinasiId = $destinasiModel->getLastInsertId();
                
                // Add all images to gallery
                foreach ($uploadedImages as $index => $imagePath) {
                    $is_primary = ($index === 0) ? 1 : 0; // First image is primary
                    $galleryModel->add($destinasiId, $imagePath, $is_primary, $index);
                }
                
                $_SESSION['success'] = 'Destinasi berhasil ditambahkan dengan ' . count($uploadedImages) . ' gambar';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan destinasi';
            }
            
            $this->redirect('admin/destinations');
            return;
        }
        
        $kategoriModel = $this->model('Kategori');
        
        $data = [
            'title' => 'Tambah Destinasi - ' . APP_NAME,
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('admin/destination_form', $data);
    }

    public function editDestination($id) {
        $this->checkAdmin();
        
        $destinasiModel = $this->model('Destinasi');
        $galleryModel = $this->model('DestinasiGallery');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create uploads directory if not exists
            $uploadDir = 'public/assets/images/destinations/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Get current primary image
            $primaryImage = $galleryModel->getPrimaryImage($id);
            $gambarPath = $primaryImage ? $primaryImage['image_path'] : '';
            
            // Handle new images upload
            if (isset($_FILES['gambar_files']) && !empty($_FILES['gambar_files']['name'][0])) {
                $files = $_FILES['gambar_files'];
                $fileCount = count($files['name']);
                $currentCount = $galleryModel->getCount($id);
                $uploadedCount = 0;
                
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        // Validate file type
                        $fileType = mime_content_type($files['tmp_name'][$i]);
                        if (!in_array($fileType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
                            continue;
                        }
                        
                        // Validate file size
                        if ($files['size'][$i] > 5 * 1024 * 1024) {
                            continue;
                        }
                        
                        // Generate unique filename
                        $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $filename = 'dest_' . $id . '_' . time() . '_' . $i . '.' . $extension;
                        $uploadPath = $uploadDir . $filename;
                        
                        // Move uploaded file
                        if (move_uploaded_file($files['tmp_name'][$i], $uploadPath)) {
                            // If this is first upload and no existing images, set as primary
                            $is_primary = ($currentCount == 0 && $uploadedCount == 0) ? 1 : 0;
                            $galleryModel->add($id, $uploadPath, $is_primary, $currentCount + $uploadedCount);
                            
                            // Update primary image path if this is the first image
                            if ($is_primary) {
                                $gambarPath = $uploadPath;
                            }
                            
                            $uploadedCount++;
                        }
                    }
                }
                
                if ($uploadedCount > 0) {
                    $_SESSION['success'] = $uploadedCount . ' gambar berhasil ditambahkan';
                }
            }
            
            // Update primary image if changed
            if (empty($gambarPath)) {
                $primaryImage = $galleryModel->getPrimaryImage($id);
                $gambarPath = $primaryImage ? $primaryImage['image_path'] : '';
            }
            
            // Handle operating hours data
            $operating_hours_mode = $_POST['operating_hours_mode'] ?? 'global';
            $operating_hours_data = [];
            
            if ($operating_hours_mode === 'per_day') {
                $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
                foreach ($days as $day) {
                    $is_open = isset($_POST["day_{$day}_is_open"]) ? true : false;
                    $operating_hours_data[$day] = [
                        'is_open' => $is_open,
                        'open' => $is_open ? ($_POST["day_{$day}_open"] ?? '08:00') : '',
                        'close' => $is_open ? ($_POST["day_{$day}_close"] ?? '17:00') : ''
                    ];
                }
                // Use harga_tiket_perday for per day mode
                $harga_tiket = $_POST['harga_tiket_perday'] ?? 0;
            } else {
                $operating_hours_data['global'] = $_POST['jam_buka'] ?? '';
                $harga_tiket = $_POST['harga_tiket'] ?? 0;
            }
            
            $data = [
                'nama' => $_POST['nama'] ?? '',
                'deskripsi' => $_POST['deskripsi'] ?? '',
                'lokasi' => $_POST['lokasi'] ?? '',
                'latitude' => !empty($_POST['latitude']) ? $_POST['latitude'] : null,
                'longitude' => !empty($_POST['longitude']) ? $_POST['longitude'] : null,
                'gambar' => $gambarPath,
                'jam_buka' => $_POST['jam_buka'] ?? '',
                'hari_operasional' => $_POST['hari_operasional'] ?? 'Setiap Hari',
                'operating_hours_mode' => $operating_hours_mode,
                'operating_hours_data' => json_encode($operating_hours_data),
                'harga_tiket' => $harga_tiket,
                'kategori_id' => $_POST['kategori_id'] ?? ''
            ];
            
            if ($destinasiModel->update($id, $data)) {
                $_SESSION['success'] = 'Destinasi berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate destinasi';
            }
            
            $this->redirect('admin/destinations');
            return;
        }
        
        $kategoriModel = $this->model('Kategori');
        
        $data = [
            'title' => 'Edit Destinasi - ' . APP_NAME,
            'destination' => $destinasiModel->getById($id),
            'categories' => $kategoriModel->getAll(),
            'gallery' => $galleryModel->getByDestinasiId($id)
        ];
        
        $this->view('admin/destination_form', $data);
    }

    public function deleteGalleryImage($id) {
        $this->checkAdmin();
        
        $galleryModel = $this->model('DestinasiGallery');
        
        if ($galleryModel->delete($id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus gambar']);
        }
        exit;
    }

    public function deleteDestination($id) {
        $this->checkAdmin();
        
        $destinasiModel = $this->model('Destinasi');
        
        // Get destination data to delete image file
        $destination = $destinasiModel->getById($id);
        
        if ($destinasiModel->delete($id)) {
            // Delete image file if it's a local file (not URL)
            if (!empty($destination['gambar']) && 
                !filter_var($destination['gambar'], FILTER_VALIDATE_URL) && 
                file_exists($destination['gambar'])) {
                unlink($destination['gambar']);
            }
            
            $_SESSION['success'] = 'Destinasi berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus destinasi';
        }
        
        $this->redirect('admin/destinations');
    }

    public function categories() {
        $this->checkAdmin();
        
        $kategoriModel = $this->model('Kategori');
        
        $data = [
            'title' => 'Kelola Kategori - ' . APP_NAME,
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('admin/categories', $data);
    }

    public function createCategory() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kategoriModel = $this->model('Kategori');
            
            $nama = $_POST['nama'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            
            if ($kategoriModel->create($nama, $deskripsi)) {
                $_SESSION['success'] = 'Kategori berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan kategori';
            }
        }
        
        $this->redirect('admin/categories');
    }

    public function editCategory($id) {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kategoriModel = $this->model('Kategori');
            
            $nama = $_POST['nama'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            
            if ($kategoriModel->update($id, $nama, $deskripsi)) {
                $_SESSION['success'] = 'Kategori berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate kategori';
            }
        }
        
        $this->redirect('admin/categories');
    }

    public function deleteCategory($id) {
        $this->checkAdmin();
        
        $kategoriModel = $this->model('Kategori');
        
        if ($kategoriModel->delete($id)) {
            $_SESSION['success'] = 'Kategori berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus kategori';
        }
        
        $this->redirect('admin/categories');
    }

    public function users() {
        $this->checkAdmin();
        
        $adminModel = $this->model('Admin');
        
        $data = [
            'title' => 'Kelola User - ' . APP_NAME,
            'users' => $adminModel->getAllUsers()
        ];
        
        $this->view('admin/users', $data);
    }

    public function deleteUser($id) {
        $this->checkAdmin();
        
        $adminModel = $this->model('Admin');
        
        if ($adminModel->deleteUser($id)) {
            $_SESSION['success'] = 'User berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus user';
        }
        
        $this->redirect('admin/users');
    }

    public function chatLogs() {
        $this->checkAdmin();
        
        $chatModel = $this->model('Chat');
        
        $data = [
            'title' => 'Log Chatbot - ' . APP_NAME,
            'logs' => $chatModel->getAllLogs(100)
        ];
        
        $this->view('admin/chat_logs', $data);
    }

    public function changePassword() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validasi
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                $this->redirect('admin/changePassword');
                return;
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Password baru dan konfirmasi tidak cocok';
                $this->redirect('admin/changePassword');
                return;
            }
            
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Password minimal 6 karakter';
                $this->redirect('admin/changePassword');
                return;
            }
            
            $adminModel = $this->model('Admin');
            $admin = $adminModel->getById($_SESSION['admin_id']);
            
            // Verifikasi password lama
            if (!password_verify($currentPassword, $admin['password'])) {
                $_SESSION['error'] = 'Password lama tidak sesuai';
                $this->redirect('admin/changePassword');
                return;
            }
            
            // Update password
            if ($adminModel->updatePassword($_SESSION['admin_id'], $newPassword)) {
                $_SESSION['success'] = 'Password berhasil diubah';
                $this->redirect('admin');
            } else {
                $_SESSION['error'] = 'Gagal mengubah password';
                $this->redirect('admin/changePassword');
            }
            return;
        }
        
        $data = [
            'title' => 'Change Password - ' . APP_NAME
        ];
        
        $this->view('admin/change_password', $data);
    }

    public function changePhoto() {
        $this->checkAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminModel = $this->model('Admin');
            $photoPath = '';
            
            // Check if file was uploaded
            if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                // Handle file upload
                $file = $_FILES['photo_file'];
                
                // Check for upload errors
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $_SESSION['error'] = 'Terjadi error saat upload file';
                    $this->redirect('admin/changePhoto');
                    return;
                }
                
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($file['tmp_name']);
                
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['error'] = 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF';
                    $this->redirect('admin/changePhoto');
                    return;
                }
                
                // Validate file size (2MB max)
                $maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if ($file['size'] > $maxSize) {
                    $_SESSION['error'] = 'Ukuran file maksimal 2MB';
                    $this->redirect('admin/changePhoto');
                    return;
                }
                
                // Create uploads directory if not exists
                $uploadDir = 'public/assets/images/admin/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'admin_' . $_SESSION['admin_id'] . '_' . time() . '.' . $extension;
                $uploadPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $photoPath = $uploadPath;
                } else {
                    $_SESSION['error'] = 'Gagal mengupload file';
                    $this->redirect('admin/changePhoto');
                    return;
                }
                
            } elseif (!empty($_POST['photo_url'])) {
                // Handle URL input
                $photoPath = $_POST['photo_url'];
                
                // Validate URL format
                if (!filter_var($photoPath, FILTER_VALIDATE_URL)) {
                    $_SESSION['error'] = 'URL tidak valid';
                    $this->redirect('admin/changePhoto');
                    return;
                }
                
            } else {
                $_SESSION['error'] = 'Silakan pilih file foto atau masukkan URL';
                $this->redirect('admin/changePhoto');
                return;
            }
            
            // Delete old photo if it's a local file (not URL)
            $admin = $adminModel->getById($_SESSION['admin_id']);
            if (!empty($admin['photo']) && 
                !filter_var($admin['photo'], FILTER_VALIDATE_URL) && 
                file_exists($admin['photo'])) {
                unlink($admin['photo']);
            }
            
            // Update database with new photo path
            if ($adminModel->updatePhoto($_SESSION['admin_id'], $photoPath)) {
                $_SESSION['success'] = 'Foto profile berhasil diubah';
                $this->redirect('admin');
            } else {
                $_SESSION['error'] = 'Gagal menyimpan foto ke database';
                $this->redirect('admin/changePhoto');
            }
            return;
        }
        
        $adminModel = $this->model('Admin');
        $admin = $adminModel->getById($_SESSION['admin_id']);
        
        $data = [
            'title' => 'Change Photo - ' . APP_NAME,
            'admin' => $admin
        ];
        
        $this->view('admin/change_photo', $data);
    }

    public function toggleFeatured($id) {
        $this->checkAdmin();
        
        $destinasiModel = $this->model('Destinasi');
        $destination = $destinasiModel->getById($id);
        
        if (!$destination) {
            echo json_encode(['success' => false, 'message' => 'Destinasi tidak ditemukan']);
            exit;
        }
        
        // Check if currently featured
        $isFeatured = $destination['is_featured'] == 1;
        
        if ($isFeatured) {
            // Unpin
            if ($destinasiModel->unsetFeatured($id)) {
                echo json_encode(['success' => true, 'action' => 'unpinned', 'message' => 'Destinasi berhasil di-unpin']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal unpin destinasi']);
            }
        } else {
            // Check if already have 3 featured
            $featuredCount = $destinasiModel->getFeaturedCount();
            
            if ($featuredCount >= 3) {
                echo json_encode(['success' => false, 'message' => 'Maksimal 3 destinasi unggulan. Unpin salah satu terlebih dahulu.']);
            } else {
                // Pin with next order
                $maxOrder = $destinasiModel->getMaxFeaturedOrder();
                $newOrder = $maxOrder + 1;
                
                if ($destinasiModel->setFeatured($id, $newOrder)) {
                    echo json_encode(['success' => true, 'action' => 'pinned', 'message' => 'Destinasi berhasil di-pin']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal pin destinasi']);
                }
            }
        }
        
        exit;
    }
}
