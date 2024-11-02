<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

abstract class BaseAuthenticableController {

    protected function getTokenFromHeaders() {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            return str_replace('Bearer ', '', $headers['Authorization']);
        }
        throw new Exception('Token not provided');
    }

    protected function decodeToken($token) {
        try {
            return JWT::decode($token, new Key($_ENV['SECRET_KEY'], 'HS256'));
        } catch (Exception $e) {
            throw new Exception('Invalid token');
        }
    }

    protected function getUserId() {
        $token = $this->getTokenFromHeaders();
        $decoded = $this->decodeToken($token);
        return $decoded->data->id ?? null;
    }
}

?>