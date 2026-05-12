<?php

class DestinasiGallery extends Model {
    
    public function getByDestinasiId($destinasi_id) {
        $query = "SELECT * FROM destinasi_gallery 
                  WHERE destinasi_id = :destinasi_id 
                  ORDER BY is_primary DESC, sort_order ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrimaryImage($destinasi_id) {
        $query = "SELECT * FROM destinasi_gallery 
                  WHERE destinasi_id = :destinasi_id AND is_primary = 1 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If no primary image, get first image
        if (!$result) {
            $query = "SELECT * FROM destinasi_gallery 
                      WHERE destinasi_id = :destinasi_id 
                      ORDER BY sort_order ASC LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':destinasi_id', $destinasi_id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function add($destinasi_id, $image_path, $is_primary = 0, $sort_order = 0) {
        // If setting as primary, unset other primary images
        if ($is_primary) {
            $this->unsetPrimary($destinasi_id);
        }
        
        $query = "INSERT INTO destinasi_gallery (destinasi_id, image_path, is_primary, sort_order, created_at) 
                  VALUES (:destinasi_id, :image_path, :is_primary, :sort_order, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':is_primary', $is_primary);
        $stmt->bindParam(':sort_order', $sort_order);
        
        return $stmt->execute();
    }

    public function delete($id) {
        // Get image info before delete
        $query = "SELECT * FROM destinasi_gallery WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Delete file if it's a local file
            if (!filter_var($image['image_path'], FILTER_VALIDATE_URL) && file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
            
            // Delete from database
            $query = "DELETE FROM destinasi_gallery WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        }
        
        return false;
    }

    public function deleteByDestinasiId($destinasi_id) {
        // Get all images
        $images = $this->getByDestinasiId($destinasi_id);
        
        // Delete files
        foreach ($images as $image) {
            if (!filter_var($image['image_path'], FILTER_VALIDATE_URL) && file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
        }
        
        // Delete from database
        $query = "DELETE FROM destinasi_gallery WHERE destinasi_id = :destinasi_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        
        return $stmt->execute();
    }

    public function setPrimary($id) {
        // Get image info
        $query = "SELECT destinasi_id FROM destinasi_gallery WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Unset other primary images
            $this->unsetPrimary($image['destinasi_id']);
            
            // Set this as primary
            $query = "UPDATE destinasi_gallery SET is_primary = 1 WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        }
        
        return false;
    }

    private function unsetPrimary($destinasi_id) {
        $query = "UPDATE destinasi_gallery SET is_primary = 0 WHERE destinasi_id = :destinasi_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        
        return $stmt->execute();
    }

    public function updateSortOrder($id, $sort_order) {
        $query = "UPDATE destinasi_gallery SET sort_order = :sort_order WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':sort_order', $sort_order);
        
        return $stmt->execute();
    }

    public function getCount($destinasi_id) {
        $query = "SELECT COUNT(*) as total FROM destinasi_gallery WHERE destinasi_id = :destinasi_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':destinasi_id', $destinasi_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
