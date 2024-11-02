<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/BaseAuthenticableController.php';

use App\Models\Post;
use App\Controllers\BaseAuthenticableController;

class PostController extends BaseAuthenticableController {
    private $postModel;

    public function __construct() {
        parent::__construct();
        $this->postModel = new Post(Database::getConnection());
    }

    
    public function createPost() {
        $data = json_decode(file_get_contents('php://input'), true);
        $authUserId = $this->getUserId(); 
        $content = htmlspecialchars($data['content'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$content) {
            http_response_code(400);
            echo json_encode(['message' => 'Content is required']);
            return;
        }

        if ($this->postModel->create($authUserId, $content)) {
            echo json_encode(['message' => 'Post created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create post']);
        }
    }

    
    public function getPosts() {
        $posts = $this->postModel->findAll();
        if ($posts) {
            echo json_encode($posts);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No posts found']);
        }
    }

    
    public function updatePost() {
        $data = json_decode(file_get_contents('php://input'), true);
        $authUserId = $this->getUserId(); 
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        $content = htmlspecialchars($data['content'] ?? '', ENT_QUOTES, 'UTF-8');
    
        if (!$id || !$content) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid or missing fields']);
            return;
        }
    
        
        $postAuthorId = $this->postModel->getAuthorId($id); 
        if ($postAuthorId !== $authUserId) {
            http_response_code(403);
            echo json_encode(['message' => 'Access denied: You are not the author of this post']);
            return;
        }
    
        if ($this->postModel->update($id, $authUserId, $content)) {
            echo json_encode(['message' => 'Post updated successfully']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Failed to update post']);
        }
    }

    public function deletePost() {
        $data = json_decode(file_get_contents('php://input'), true);
        $authUserId = $this->getUserId(); 
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
    
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'Post ID is required']);
            return;
        }
    
        
        $postAuthorId = $this->postModel->getAuthorId($id); 
        if ($postAuthorId !== $authUserId) {
            http_response_code(403);
            echo json_encode(['message' => 'Access denied: You are not the author of this post']);
            return;
        }
    
        if ($this->postModel->delete($id, $authUserId)) {
            echo json_encode(['message' => 'Post deleted successfully']);
        } else {
            http_response_code(403);
            echo json_encode(['message' => 'Failed to delete post']);
        }
    }
    
    
}

?>
