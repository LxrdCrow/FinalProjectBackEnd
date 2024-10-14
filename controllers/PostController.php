<?php
require_once __DIR__ . '/../models/Post.php';

class PostController {
    public function createPost() {
        $db = Database::getConnection();
        $post = new Post($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'];
        $content = $data['content'];

        if ($post->create($userId, $content)) {
            echo json_encode(['message' => 'Post created successfully']);
        } else {
            echo json_encode(['message' => 'Failed to create post']);
        }
    }
    // Get all posts
    public function getPosts() {
        $db = Database::getConnection();
        $post = new Post($db);

        $posts = $post->findAll();
        echo json_encode($posts);
    }

    // Update post
    public function updatePost() {
        $db = Database::getConnection();
        $post = new Post($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $content = $data['content'];

        if ($post->update($id, $content)) {
            echo json_encode(['message' => 'Post updated successfully']);
        } else {
            echo json_encode(['message' => 'Failed to update post']);
        }
    }

    // Delete post
    public function deletePost() {
        $db = Database::getConnection();
        $post = new Post($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];

        if ($post->delete($id)) {
            echo json_encode(['message' => 'Post deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete post']);
        }
    }
}
