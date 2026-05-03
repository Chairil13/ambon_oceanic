<?php

class AuthController extends Controller {
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            if (empty($name)) $errors[] = 'Nama harus diisi';
            if (empty($email)) $errors[] = 'Email harus diisi';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid';
            if (empty($password)) $errors[] = 'Password harus diisi';
            if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter';
            if ($password !== $confirm_password) $errors[] = 'Password tidak cocok';
            
            if (empty($errors)) {
                $userModel = $this->model('User');
                
                if ($userModel->emailExists($email)) {
                    $errors[] = 'Email sudah terdaftar';
                } else {
                    if ($userModel->register($name, $email, $password)) {
                        $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
                        $this->redirect('auth/login');
                        return;
                    } else {
                        $errors[] = 'Registrasi gagal';
                    }
                }
            }
            
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
        }
        
        $data = ['title' => 'Register - ' . APP_NAME];
        $this->view('auth/register', $data);
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($email)) $errors[] = 'Email harus diisi';
            if (empty($password)) $errors[] = 'Password harus diisi';
            
            if (empty($errors)) {
                $userModel = $this->model('User');
                $user = $userModel->login($email, $password);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    $this->redirect('');
                    return;
                } else {
                    $errors[] = 'Email atau password salah';
                }
            }
            
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
        }
        
        $data = ['title' => 'Login - ' . APP_NAME];
        $this->view('auth/login', $data);
    }

    public function logout() {
        session_destroy();
        $this->redirect('');
    }

    public function favorites() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
            return;
        }
        
        $favoriteModel = $this->model('Favorite');
        $favorites = $favoriteModel->getUserFavorites($_SESSION['user_id']);
        
        $data = [
            'title' => 'Favorit Saya - ' . APP_NAME,
            'favorites' => $favorites
        ];
        
        $this->view('auth/favorites', $data);
    }

    public function toggleFavorite() {
        header('Content-Type: application/json');
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Anda harus login terlebih dahulu']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $destinasi_id = $input['destinasi_id'] ?? 0;
        
        $favoriteModel = $this->model('Favorite');
        $user_id = $_SESSION['user_id'];
        
        if ($favoriteModel->exists($user_id, $destinasi_id)) {
            $favoriteModel->remove($user_id, $destinasi_id);
            echo json_encode(['success' => true, 'action' => 'removed']);
        } else {
            $favoriteModel->add($user_id, $destinasi_id);
            echo json_encode(['success' => true, 'action' => 'added']);
        }
    }
}
