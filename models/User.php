<?php

namespace App\Models;

use Exception;
use PDO;

class User {
    private $conn;
    private $table = 'users'; 

    public function __construct($db) {
        $this->conn = $db;
    }

   
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function create($username, $email, $password) {
        if ($this->findByEmail($email) || $this->findByUsername($username)) {
            throw new Exception('Username or email already exists.');
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
    
        if (!$stmt->execute()) {
            throw new Exception('Failed to create user.');
        }
    
        return true;
    }

    
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   
    public function update($id, $username, $email, $password = null) {
        $query = "UPDATE " . $this->table . " SET username = :username, email = :email" . ($password ? ", password = :password" : "") . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);

        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashedPassword);
        }
    
        if (!$stmt->execute()) {
            throw new Exception('Failed to update user.');
        }
    
        return true;
    }
}
?>

