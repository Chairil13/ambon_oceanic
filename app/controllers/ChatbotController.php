<?php

class ChatbotController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Chatbot - ' . APP_NAME
        ];
        
        // Load chat history only for logged-in users
        if ($this->isLoggedIn()) {
            $chatModel = $this->model('Chat');
            $data['history'] = $chatModel->getUserHistory($_SESSION['user_id'], 20);
        }
        // Guest users start fresh (no history from session)
        
        $this->view('chatbot/index', $data);
    }

    public function send() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $message = $input['message'] ?? '';
        $guestHistory = $input['guestHistory'] ?? []; // Receive history from client
        
        if (empty($message)) {
            echo json_encode(['error' => 'Message is required']);
            return;
        }
        
        $chatModel = $this->model('Chat');
        
        // Get context
        $context = [];
        $user_id = null;
        
        if ($this->isLoggedIn()) {
            // For logged-in users: get from database
            $user_id = $_SESSION['user_id'];
            $history = $chatModel->getUserHistory($user_id, 10);
            $context = array_reverse($history);
        } else {
            // For guest users: use history from client (in-memory)
            $context = $guestHistory;
            // Keep only last 10 conversations
            if (count($context) > 10) {
                $context = array_slice($context, -10);
            }
        }
        
        // Send to LLM
        $response = $chatModel->sendToLLM($message, $context);
        
        // Get all destinations for image matching
        $destinasiModel = $this->model('Destinasi');
        $allDestinasi = $destinasiModel->getAll();
        
        // Find destinations mentioned in response
        $mentionedDestinations = [];
        foreach ($allDestinasi as $dest) {
            // Check if destination name is mentioned in response
            if (stripos($response, $dest['nama']) !== false) {
                $mentionedDestinations[] = [
                    'id' => $dest['id'],
                    'nama' => $dest['nama'],
                    'gambar' => $dest['gambar'],
                    'kategori' => $dest['kategori_nama'],
                    'lokasi' => $dest['lokasi'],
                    'jam_buka' => $dest['jam_buka'],
                    'harga_tiket' => $dest['harga_tiket']
                ];
            }
        }
        
        // Save log only for logged-in users
        if ($this->isLoggedIn()) {
            $chatModel->saveLog($user_id, $message, $response);
        }
        // Guest users: don't save anywhere (client handles in-memory storage)
        
        echo json_encode([
            'success' => true,
            'response' => $response,
            'destinations' => $mentionedDestinations
        ]);
    }

    public function clearHistory() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        if (!$this->isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        $chatModel = $this->model('Chat');
        $result = $chatModel->clearUserHistory($_SESSION['user_id']);
        
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Riwayat chat berhasil dihapus' : 'Gagal menghapus riwayat chat'
        ]);
    }
}
