<?php

class PageController extends Controller {
    
    public function about() {
        $data = [
            'title' => 'Tentang Maluku - ' . APP_NAME
        ];
        
        $this->view('page/about', $data);
    }

    public function contact() {
        $data = [
            'title' => 'Hubungi Kami - ' . APP_NAME
        ];
        
        $this->view('page/contact', $data);
    }

    public function sendContact() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $message = $input['message'] ?? '';
        
        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(['error' => 'Semua field harus diisi']);
            return;
        }
        
        // Here you can add email sending logic or save to database
        // For now, just return success
        
        echo json_encode([
            'success' => true,
            'message' => 'Terima kasih! Pesan Anda telah dikirim.'
        ]);
    }
}
