<?php

class Kategori extends Model {
    
    public function getAll() {
        $query = "SELECT * FROM kategori ORDER BY nama ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM kategori WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama, $deskripsi) {
        $query = "INSERT INTO kategori (nama, deskripsi) VALUES (:nama, :deskripsi)";
        
        $stmt = $this->conn->prepare($query);
        
        $nama = $this->sanitize($nama);
        $deskripsi = $this->sanitize($deskripsi);
        
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':deskripsi', $deskripsi);
        
        return $stmt->execute();
    }

    public function update($id, $nama, $deskripsi) {
        $query = "UPDATE kategori SET nama = :nama, deskripsi = :deskripsi WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $nama = $this->sanitize($nama);
        $deskripsi = $this->sanitize($deskripsi);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':deskripsi', $deskripsi);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM kategori WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
