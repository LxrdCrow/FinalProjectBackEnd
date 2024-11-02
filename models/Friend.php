<?php

namespace App\Models;

use Exception;
use PDO;

class Friend {
    private $conn;
    private $table = 'friends';

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function addFriend($userId, $friendId) {
        if ($this->isFriend($userId, $friendId)) {
            throw new Exception('Friendship already exists.');
        }

        $query = "INSERT INTO " . $this->table . " (user_id, friend_id) VALUES (:userId, :friendId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':friendId', $friendId);

        if (!$stmt->execute()) {
            throw new Exception('Failed to add friend.');
        }

        return true;
    }

    
    public function removeFriend($userId, $friendId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :userId AND friend_id = :friendId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':friendId', $friendId);

        if (!$stmt->execute()) {
            throw new Exception('Failed to remove friend.');
        }

        return true;
    }

    
    public function getFriends($userId) {
        $query = "SELECT friend_id FROM " . $this->table . " WHERE user_id = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    private function isFriend($userId, $friendId) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE user_id = :userId AND friend_id = :friendId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':friendId', $friendId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

   
    public function doesUserExist($userId) {
        $query = "SELECT id FROM users WHERE id = :userId LIMIT 1"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return $stmt->rowCount() > 0; 
    }
}
?>
