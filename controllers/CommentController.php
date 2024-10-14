<?php

class CommentController {
    private $model;

    public function __construct() {
        
        $this->model = new Comment();
    }

    // Creation of comment route
    public function createComment() {
        $postId = filter_var($_POST['post_id'], FILTER_VALIDATE_INT);
    $userId = $_SESSION['user_id']; 
    $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8'); 
       

        // Validation of fields
        if (!$postId || !$comment) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing or invalid fields']);
            return;
        }

        $this->model->addComment($postId, $userId, $comment);
        echo json_encode(['message' => 'Comment added']);
    }



    // Get all comments
    public function getComments() {
        $postId = filter_var($_GET['post_id'], FILTER_VALIDATE_INT);
    
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['message' => 'Post ID is required or invalid']);
            return;
        }
    
        $comments = $this->model->getCommentsByPost($postId);
        echo json_encode($comments);
    }
    

    // Update a comment
    public function updateComment() {
        $commentId = filter_var($_POST['comment_id'], FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id']; 
        $newComment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');

        

    
        if (!$commentId || !$newComment) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing or invalid fields']);
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
    

    // Delete a comment
    public function deleteComment() {
        $commentId = filter_var($_POST['comment_id'], FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id']; 
    
        if (!$commentId) {
            http_response_code(400);
            echo json_encode(['message' => 'Comment ID is required or invalid']);
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
