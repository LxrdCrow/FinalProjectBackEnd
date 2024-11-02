<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BaseAuthenticableController.php';

use App\Models\User; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Controllers\BaseAuthenticableController;

class AuthController extends BaseAuthenticableController {
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['SECRET_KEY'];
    }

    // Register route
    public function register() {
        $db = Database::getConnection();
        $user = new User($db);

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['username'], $data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
            return;
        }

        if (strlen($data['password']) < 8) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Password too short']);
            return;
        }

        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password']; 

        if ($user->create($username, $email, $password)) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'User registration failed']);
        }
    }

    // Login route 
    public function login() {
        $db = Database::getConnection();
        $user = new User($db);

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['email'], $data['password'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        $userInfo = $user->findByEmail($email);

        if ($userInfo && password_verify($password, $userInfo['password'])) {
            // JWT creation
            $payload = [
                'iss' => "http://localhost",
                'aud' => "http://localhost",
                'iat' => time(),
                'exp' => time() + (60 * 60),  
                'data' => [
                    'id' => $userInfo['id'],
                    'username' => $userInfo['username'],
                    'email' => $userInfo['email']
                ]
            ];

            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
            http_response_code(200);
            echo json_encode(['status' => 'success', 'token' => $jwt]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Login failed']);
        }
    }

    // Protected route
    public function protectedRoute() {
        try {
            $userId = $this->getUserId();  
            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Access granted', 'userId' => $userId]);
        } catch (Exception $e) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Access denied', 'error' => $e->getMessage()]);
        }
    }

    // Update profile route
    public function updateProfile() {
        try {
            $userId = $this->getUserId();  
            
            $db = Database::getConnection();
            $user = new User($db);

            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['username'], $data['email'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
                return;
            }

            $username = $data['username'];
            $email = $data['email'];

            $existingUser = $user->findById($userId);
            if (!$existingUser) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
                return;
            }

            $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : $existingUser['password'];

            if ($user->update($userId, $username, $email, $password)) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Profile update failed']);
            }
        } catch (Exception $e) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Access denied', 'error' => $e->getMessage()]);
        }
    }
}
?>


