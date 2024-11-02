<?php

namespace App\Models;

use Exception;
use PDO;

class Comment {
    private $conn;
    private $table = 'comments';

    public function __construct($db) {
        $this->conn = $db; 
    }

   
    public function addComment($postId, $userId, $comment) {
        if (!$this->postExists($postId)) {
            throw new Exception('Post does not exist.');
        }

        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (post_id, user_id, comment) VALUES (:postId, :userId, :comment)");

        if ($stmt->execute([
            ':postId' => $postId,
            ':userId' => $userId,
            ':comment' => $comment
        ])) {
            return true;
        } else {
            throw new Exception('Failed to add comment.');
        }
    }

    
    public function getCommentsByPost($postId) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE post_id = :postId ORDER BY created_at DESC");
        
        if ($stmt->execute([':postId' => $postId])) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception('Failed to retrieve comments.');
        }
    }

   
    public function updateComment($commentId, $userId, $newComment) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET comment = :comment WHERE id = :commentId AND user_id = :userId");
        
        $stmt->execute([
            ':comment' => $newComment,
            ':commentId' => $commentId,
            ':userId' => $userId
        ]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Failed to update comment or unauthorized action.');
        }
    }

   
    public function getCommentAuthor($commentId) {
        $query = "SELECT user_id FROM " . $this->table . " WHERE id = :commentId LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commentId', $commentId);
        $stmt->execute();
        return $stmt->fetchColumn(); 
    }

    public function deleteComment($commentId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = :commentId AND user_id = :userId");
        
        $stmt->execute([
            ':commentId' => $commentId,
            ':userId' => $userId
        ]);

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            throw new Exception('Failed to delete comment or unauthorized action.');
        }
    }

    
    private function postExists($postId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM posts WHERE id = :postId");
        $stmt->execute([':postId' => $postId]);
        return $stmt->fetchColumn() > 0;
    }
}

?>

