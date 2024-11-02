<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/BaseAuthenticableController.php';

use App\Models\Comment;
use App\Controllers\BaseAuthenticableController;

class CommentController extends BaseAuthenticableController {
    private $model;

    public function __construct() {
        parent::__construct(); 
        $db = Database::getConnection(); 
        $this->model = new Comment($db); 
    }

    
    public function createComment() {
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
        $userId = $this->getUserId(); 
        $comment = htmlspecialchars(filter_input(INPUT_POST, 'comment'), ENT_QUOTES, 'UTF-8');


        
        if (!$postId || !$comment) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing or invalid fields']);
            return;
        }

        $this->model->addComment($postId, $userId, $comment);
        echo json_encode(['message' => 'Comment added']);
    }

    
    public function getComments() {
        $postId = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['message' => 'Post ID is required or invalid']);
            return;
        }

        $comments = $this->model->getCommentsByPost($postId);
        echo json_encode($comments);
    }

    
    public function updateComment() {
        $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
        $userId = $this->getUserId(); 
        $newComment = htmlspecialchars(filter_input(INPUT_POST, 'comment'), ENT_QUOTES, 'UTF-8');
    
        if (!$commentId || !$newComment) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing or invalid fields']);
            return;
        }
    
        // ACCESS check: verified if user is author of comment or not
        $authorId = $this->model->getCommentAuthor($commentId);
        if ($authorId !== $userId) {
            http_response_code(403);
            echo json_encode(['message' => 'Access denied']);
            return;
        }
    
        $updated = $this->model->updateComment($commentId, $userId, $newComment);
        if ($updated) {
            echo json_encode(['message' => 'Comment updated']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Unable to update comment']);
        }
    }
    
    public function deleteComment() {
        $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
        $userId = $this->getUserId(); 
    
        if (!$commentId) {
            http_response_code(400);
            echo json_encode(['message' => 'Comment ID is required or invalid']);
            return;
        }
    
        // ACCESS check: verified if user is author of comment or not
        $authorId = $this->model->getCommentAuthor($commentId);
        if ($authorId !== $userId) {
            http_response_code(403);
            echo json_encode(['message' => 'Access denied']);
            return;
        }
    
        $deleted = $this->model->deleteComment($commentId, $userId);
        if ($deleted) {
            echo json_encode(['message' => 'Comment deleted']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Unable to delete comment']);
        }
    }
    
}
?>
