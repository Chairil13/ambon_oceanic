<?php

class Favorite extends Model {
    
    public function add($user_id, $destinasi_id) {
        // Check if already exists
        if ($this->exists($user_id, $destinasi_id)) {
            return false;
        }
        
        $query = "INSERT INTO favorites (user_id, destinasi_id, created_at) 
                  VALUES (:user_id, :destinasi_id, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        
        return $stmt->execute();
    }

    public function remove($user_id, $destinasi_id) {
        $query = "DELETE FROM favorites WHERE user_id = :user_id AND destinasi_id = :destinasi_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        
        return $stmt->execute();
    }

    public function getUserFavorites($user_id) {
        $query = "SELECT d.*, k.nama as kategori_nama 
                  FROM favorites f 
                  JOIN destinasi d ON f.destinasi_id = d.id 
                  LEFT JOIN kategori k ON d.kategori_id = k.id 
                  WHERE f.user_id = :user_id 
                  ORDER BY f.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists($user_id, $destinasi_id) {
        $query = "SELECT id FROM favorites WHERE user_id = :user_id AND destinasi_id = :destinasi_id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
