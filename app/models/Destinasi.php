<?php

class Destinasi extends Model {
    
    public function getAll($limit = null) {
        $query = "SELECT d.*, k.nama as kategori_nama 
                  FROM destinasi d 
                  LEFT JOIN kategori k ON d.kategori_id = k.id 
                  ORDER BY d.created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT d.*, k.nama as kategori_nama 
                  FROM destinasi d 
                  LEFT JOIN kategori k ON d.kategori_id = k.id 
                  WHERE d.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $query = "SELECT d.*, k.nama as kategori_nama 
                  FROM destinasi d 
                  LEFT JOIN kategori k ON d.kategori_id = k.id 
                  WHERE d.nama LIKE :keyword OR d.deskripsi LIKE :keyword 
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $keyword = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($kategori_id) {
        $query = "SELECT d.*, k.nama as kategori_nama 
                  FROM destinasi d 
                  LEFT JOIN kategori k ON d.kategori_id = k.id 
                  WHERE d.kategori_id = :kategori_id 
                  ORDER BY d.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kategori_id', $kategori_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO destinasi (nama, deskripsi, lokasi, gambar, jam_buka, harga_tiket, kategori_id, created_at) 
                  VALUES (:nama, :deskripsi, :lokasi, :gambar, :jam_buka, :harga_tiket, :kategori_id, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':lokasi', $data['lokasi']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':jam_buka', $data['jam_buka']);
        $stmt->bindParam(':harga_tiket', $data['harga_tiket']);
        $stmt->bindParam(':kategori_id', $data['kategori_id']);
        
        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE destinasi SET 
                  nama = :nama, 
                  deskripsi = :deskripsi, 
                  lokasi = :lokasi, 
                  gambar = :gambar, 
                  jam_buka = :jam_buka, 
                  harga_tiket = :harga_tiket, 
                  kategori_id = :kategori_id 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':lokasi', $data['lokasi']);
        $stmt->bindParam(':gambar', $data['gambar']);
        $stmt->bindParam(':jam_buka', $data['jam_buka']);
        $stmt->bindParam(':harga_tiket', $data['harga_tiket']);
        $stmt->bindParam(':kategori_id', $data['kategori_id']);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM destinasi WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
