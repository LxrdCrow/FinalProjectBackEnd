<?php

class Friend {
    private $conn;
    private $table = 'friends';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add friend
    public function addFriend($userId, $friendId) {
        $query = "INSERT INTO " . $this->table . " (user_id, friend_id) VALUES (:user_id, :friend_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':friend_id', $friendId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Remove friend
    public function removeFriend($userId, $friendId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND friend_id = :friend_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':friend_id', $friendId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Get friends
    public function getFriends($userId) {
        $query = "SELECT friend_id FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
