<?php

class DestinasiController extends Controller {
    
    public function index() {
        $destinasiModel = $this->model('Destinasi');
        $kategoriModel = $this->model('Kategori');
        
        $destinations = $destinasiModel->getAll();
        
        // Handle search
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $destinations = $destinasiModel->search($_GET['search']);
        }
        
        // Handle category filter
        if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
            $destinations = $destinasiModel->getByCategory($_GET['kategori']);
        }
        
        $data = [
            'title' => 'Destinasi Wisata - ' . APP_NAME,
            'destinations' => $destinations,
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('destinasi/index', $data);
    }

    public function detail($id) {
        $destinasiModel = $this->model('Destinasi');
        $destination = $destinasiModel->getById($id);
        
        if (!$destination) {
            $this->redirect('destinasi');
            return;
        }
        
        // Check if favorited by current user
        $isFavorite = false;
        if ($this->isLoggedIn()) {
            $favoriteModel = $this->model('Favorite');
            $isFavorite = $favoriteModel->exists($_SESSION['user_id'], $id);
        }
        
        $data = [
            'title' => $destination['nama'] . ' - ' . APP_NAME,
            'destination' => $destination,
            'isFavorite' => $isFavorite
        ];
        
        $this->view('destinasi/detail', $data);
    }
}
