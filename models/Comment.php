<?php

class Comment {
    private $db;

    public function __construct() {
        
        $this->db = Database::getConnection();
    }

    // Add a new comment
    public function addComment($postId, $userId, $comment) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (:postId, :userId, :comment)");
        $stmt->execute([
            ':postId' => $postId,
            ':userId' => $userId,
            ':comment' => $comment
        ]);
    }

    // Get all comments for a post
    public function getCommentsByPost($postId) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_id = :postId ORDER BY created_at DESC");
        $stmt->execute([':postId' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a comment
    public function updateComment($commentId, $userId, $newComment) {
        $stmt = $this->db->prepare("UPDATE comments SET comment = :comment WHERE id = :commentId AND user_id = :userId");
        $stmt->execute([
            ':comment' => $newComment,
            ':commentId' => $commentId,
            ':userId' => $userId
        ]);
        return $stmt->rowCount() > 0;
    }

    // Delete a comment
    public function deleteComment($commentId, $userId) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :commentId AND user_id = :userId");
        $stmt->execute([
            ':commentId' => $commentId,
            ':userId' => $userId
        ]);
        return $stmt->rowCount() > 0;
    }
}

?>

