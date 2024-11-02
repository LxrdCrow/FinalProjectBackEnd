<?php

namespace App\Models;

use Exception;
use PDO;

class Post {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

   
    public function create($userId, $content) {
        if (empty($content)) {
            throw new Exception('Post content cannot be empty.');
        }

        $query = "INSERT INTO " . $this->table . " (user_id, content) VALUES (:user_id, :content)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);

        if (!$stmt->execute()) {
            throw new Exception('Failed to create post.');
        }

        return true;
    }

    
    public function findAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function update($id, $userId, $content) {
        if (empty($content)) {
            throw new Exception('Post content cannot be empty.');
        }

        $post = $this->findById($id);
        if (!$post || $post['user_id'] != $userId) {
            throw new Exception('Unauthorized action or post not found.');
        }

        $query = "UPDATE " . $this->table . " SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':content', $content);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update post.');
        }

        return true;
    }

    
    public function getAuthorId($id) {
        $stmt = $this->conn->prepare("SELECT user_id FROM " . $this->table . " WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['user_id'] : null; 
        }

        return null;
    }

   
    public function delete($id, $userId) {
        $post = $this->findById($id);
        if ($post && $post['user_id'] == $userId) {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete post.');
            }
            return true;
        }
        throw new Exception('Unauthorized action or post not found.');
    }
}

?>
