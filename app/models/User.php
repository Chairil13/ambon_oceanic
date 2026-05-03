<?php

class User extends Model {
    
    public function register($name, $email, $password) {
        $query = "INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $name = $this->sanitize($name);
        $email = $this->sanitize($email);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT id, name, email, password FROM users WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $email = $this->sanitize($email);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $row['password'])) {
                return $row;
            }
        }
        
        return false;
    }

    public function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $email = $this->sanitize($email);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getById($id) {
        $query = "SELECT id, name, email, created_at FROM users WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
