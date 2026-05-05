<?php

class Chat extends Model {
    
    public function saveLog($user_id, $message, $response) {
        $query = "INSERT INTO chat_logs (user_id, message, response, created_at) 
                  VALUES (:user_id, :message, :response, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':response', $response);
        
        return $stmt->execute();
    }

    public function getUserHistory($user_id, $limit = 50) {
        $query = "SELECT * FROM chat_logs 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLogs($limit = 100) {
        $query = "SELECT c.*, u.name as user_name 
                  FROM chat_logs c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  ORDER BY c.created_at DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearUserHistory($user_id) {
        $query = "DELETE FROM chat_logs WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }

    public function sendToLLM($message, $context = []) {
        // Get destinations and categories data from database
        $queryKategori = "SELECT * FROM kategori ORDER BY nama ASC";
        $stmtKategori = $this->conn->prepare($queryKategori);
        $stmtKategori->execute();
        $allKategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);
        
        $queryDestinasi = "SELECT d.*, k.nama as kategori_nama 
                          FROM destinasi d 
                          LEFT JOIN kategori k ON d.kategori_id = k.id 
                          ORDER BY d.created_at DESC";
        $stmtDestinasi = $this->conn->prepare($queryDestinasi);
        $stmtDestinasi->execute();
        $allDestinasi = $stmtDestinasi->fetchAll(PDO::FETCH_ASSOC);
        
        // Build knowledge base from database
        $knowledgeBase = "DATA DESTINASI WISATA AMBON:\n\n";
        
        foreach ($allDestinasi as $dest) {
            $knowledgeBase .= "- {$dest['nama']} ({$dest['kategori_nama']})\n";
            $knowledgeBase .= "  Lokasi: {$dest['lokasi']}\n";
            $knowledgeBase .= "  Jam Buka: {$dest['jam_buka']}\n";
            $knowledgeBase .= "  Harga: Rp " . number_format($dest['harga_tiket'], 0, ',', '.') . "\n";
            $knowledgeBase .= "  Info: {$dest['deskripsi']}\n\n";
        }
        
        // System instruction (separate from conversation)
        $systemInstruction = "Anda adalah Oceanic, asisten virtual pemandu wisata Ambon yang dibuat oleh Alin, Dede, dan tim. Gunakan data berikut:\n\n" . $knowledgeBase;
        $systemInstruction .= "\nIDENTITAS ANDA:\n";
        $systemInstruction .= "- Nama: Oceanic\n";
        $systemInstruction .= "- Pembuat: Alin, Dede, dan tim\n";
        $systemInstruction .= "- Tugas: Membantu wisatawan menemukan dan mengenal destinasi wisata di Ambon\n\n";
        $systemInstruction .= "INSTRUKSI PENTING:\n";
        $systemInstruction .= "1. Jika ditanya siapa Anda, jawab: 'Saya Oceanic, asisten virtual yang dibuat oleh Alin, Dede, dan tim untuk membantu Anda menjelajahi wisata Ambon'\n";
        $systemInstruction .= "2. PRIORITAS UTAMA: Gunakan data destinasi di atas untuk menjawab pertanyaan tentang tempat wisata, harga tiket, jam buka, dan lokasi di Ambon.\n";
        $systemInstruction .= "3. Jika destinasi yang ditanyakan ADA dalam data di atas, berikan informasi HANYA dari data tersebut.\n";
        $systemInstruction .= "4. Jika destinasi yang ditanyakan TIDAK ADA dalam data, Anda BOLEH menggunakan pengetahuan umum Anda tentang Ambon, tetapi sebutkan bahwa informasi tersebut belum tersedia dalam sistem kami.\n";
        $systemInstruction .= "5. Untuk pertanyaan umum tentang Ambon (cuaca, budaya, sejarah, tips perjalanan, transportasi, dll) yang TIDAK terkait destinasi spesifik, gunakan pengetahuan umum Anda.\n";
        $systemInstruction .= "6. Jika user bertanya tentang jam buka, lokasi, harga TANPA menyebut nama tempat, lihat pesan Anda sebelumnya dan berikan info untuk tempat yang baru Anda sebutkan.\n";
        $systemInstruction .= "7. Jangan gunakan format markdown (**, *, #). Gunakan teks biasa.\n";
        $systemInstruction .= "8. Jawab dalam bahasa Indonesia dengan ramah dan informatif.\n";
        
        // Build contents array for conversation
        $contents = [];
        
        // Add conversation history
        if (!empty($context)) {
            foreach ($context as $chat) {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $chat['message']]]
                ];
                $contents[] = [
                    'role' => 'model',
                    'parts' => [['text' => $chat['response']]]
                ];
            }
        }
        
        // Add current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];
        
        // Gemini API format with system instruction
        $data = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.8,
                'maxOutputTokens' => 2000,
                'topP' => 0.95,
                'topK' => 64
            ]
        ];
        
        // Build URL with API key
        $url = LLM_API_ENDPOINT . '?key=' . LLM_API_KEY;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode == 200) {
            $result = json_decode($response, true);
            
            // Extract text from Gemini response format
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $text = trim($result['candidates'][0]['content']['parts'][0]['text']);
                
                // Remove markdown formatting
                $text = preg_replace('/\*\*\*(.+?)\*\*\*/s', '$1', $text); // Remove ***bold italic***
                $text = preg_replace('/\*\*(.+?)\*\*/s', '$1', $text);     // Remove **bold**
                $text = preg_replace('/\*(.+?)\*/s', '$1', $text);         // Remove *italic*
                $text = preg_replace('/^#+\s+/m', '', $text);              // Remove # headers
                
                return $text;
            }
            
            return 'Maaf, terjadi kesalahan dalam memproses respons.';
        }
        
        // Log error for debugging
        error_log("Gemini API Error - HTTP Code: $httpCode, Response: $response, cURL Error: $curlError");
        
        return 'Maaf, layanan chatbot sedang tidak tersedia. Silakan coba lagi nanti.';
    }
}
