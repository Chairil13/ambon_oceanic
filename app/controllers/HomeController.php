<?php

class HomeController extends Controller {
    
    public function index() {
        $destinasiModel = $this->model('Destinasi');
        $kategoriModel = $this->model('Kategori');
        
        // Get featured destinations, fallback to latest if none featured
        $featured = $destinasiModel->getFeatured();
        if (empty($featured)) {
            $featured = $destinasiModel->getAll(3);
        }
        
        $data = [
            'title' => 'Home - ' . APP_NAME,
            'destinations' => $featured,
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('home/index', $data);
    }
}
