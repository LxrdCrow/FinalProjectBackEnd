<?php
require_once __DIR__ . '/../models/Friend.php';
class FriendController {
    public function addFriend() {
        $db = Database::getConnection();
        $friend = new Friend($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'];
        $friendId = $data['friend_id'];

        if ($friend->addFriend($userId, $friendId)) {
            echo json_encode(['message' => 'Friend added successfully']);
        } else {
            echo json_encode(['message' => 'Failed to add friend']);
        }
    }

    // Remove friend
    public function removeFriend() {
        $db = Database::getConnection();
        $friend = new Friend($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'];
        $friendId = $data['friend_id'];

        if ($friend->removeFriend($userId, $friendId)) {
            echo json_encode(['message' => 'Friend removed successfully']);
        } else {
            echo json_encode(['message' => 'Failed to remove friend']);
        }
    }
    
    // Get friends
    public function getFriends() {
        $db = Database::getConnection();
        $friend = new Friend($db);

        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $data['user_id'];

        $friends = $friend->getFriends($userId);
        echo json_encode($friends);
    }
}
