<?php
require __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Friend.php';
require_once __DIR__ . '/BaseAuthenticableController.php';

use App\Models\Friend;
use App\Controllers\BaseAuthenticableController;

class FriendController extends BaseAuthenticableController {
    private $friendModel;

    public function __construct() {
        parent::__construct(); 
        $this->friendModel = new Friend(Database::getConnection());
    }

    public function addFriend() {
        $data = json_decode(file_get_contents('php://input'), true);
        $authUserId = $this->getUserId(); 
        $friendId = filter_var($data['friend_id'], FILTER_VALIDATE_INT);
    
        
        if (!$friendId) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid friend ID']);
            return;
        }
    
        
        if ($authUserId === $friendId) {
            http_response_code(400);
            echo json_encode(['message' => 'You cannot add yourself as a friend']);
            return;
        }
    
        
        if (!$this->friendModel->doesUserExist($friendId)) {
            http_response_code(404);
            echo json_encode(['message' => 'Friend does not exist']);
            return;
        }
    
        // Aggiungi l'amico
        if ($this->friendModel->addFriend($authUserId, $friendId)) {
            echo json_encode(['message' => 'Friend added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to add friend']);
        }
    }
    

    public function removeFriend() {
        $data = json_decode(file_get_contents('php://input'), true);
        $authUserId = $this->getUserId(); 
        $friendId = filter_var($data['friend_id'], FILTER_VALIDATE_INT);

        if (!$friendId) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid friend ID']);
            return;
        }

        if ($this->friendModel->removeFriend($authUserId, $friendId)) {
            echo json_encode(['message' => 'Friend removed successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to remove friend']);
        }
    }

    public function getFriends() {
        $authUserId = $this->getUserId(); 
        $friends = $this->friendModel->getFriends($authUserId);

        if ($friends) {
            echo json_encode($friends);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No friends found']);
        }
    }
}
?>
