<?php
require_once __DIR__ . '/../vendor/autoload.php'; 
use Dotenv\Dotenv;

class Database {
    private static $pdo = null;
    
    // Get database connection
    public static function getConnection() {
        if (self::$pdo === null) {
            
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();

            
            $host = $_ENV['DB_HOST'] ?? null;
            $db = $_ENV['DB_NAME'] ?? null;
            $user = $_ENV['DB_USER'] ?? null;
            $pass = $_ENV['DB_PASS'] ?? null;

            if (!$host || !$db || !$user || !$pass) {
                die(json_encode(['message' => 'Database configuration missing in .env']));
            }

            // DSN for connection to the database
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $user, $pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                http_response_code(500); 
                echo json_encode(['message' => 'Connection failed: ' . $e->getMessage()]);
                exit;
            }
        }

        return self::$pdo;
    }
}
?>

