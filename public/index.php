<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


if ($_ENV['APP_ENV'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

if ($_ENV['APP_ENV'] === 'production') {
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../logs/error.log');  
}



require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../core/router.php';

require_once __DIR__ . '/../routes/api.php';

$router->dispatch($_SERVER['REQUEST_URI']);


