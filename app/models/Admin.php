<?php

class Admin extends Model {
    
    public function login($username, $password) {
        $query = "SELECT id, username, password FROM admin WHERE username = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $username = $this->sanitize($username);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        
        return false;
    }

    public function getAllUsers() {
        $query = "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM admin WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET password = :password WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function updatePhoto($id, $photoUrl) {
        $query = "UPDATE admin SET photo = :photo WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':photo', $photoUrl);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
