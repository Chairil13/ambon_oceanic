<?php

class HomeController extends Controller {
    
    public function index() {
        $destinasiModel = $this->model('Destinasi');
        $kategoriModel = $this->model('Kategori');
        
        $data = [
            'title' => 'Home - ' . APP_NAME,
            'destinations' => $destinasiModel->getAll(6),
            'categories' => $kategoriModel->getAll()
        ];
        
        $this->view('home/index', $data);
    }
}
