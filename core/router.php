<?php

class Router {
    private $routes = [];

    // Define route for POST requests
    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    // Define route for GET requests
    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    // Dispatch route
    public function dispatch($uri) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        
        $uri = strtok($uri, '?');

        if (isset($this->routes[$requestMethod][$uri])) {
            $action = $this->routes[$requestMethod][$uri];
            $this->executeAction($action);
        } else {
            
            http_response_code(404);
            echo json_encode(['message' => 'Route not found']);
        }
    }

    // Execute action based on route
    private function executeAction($action) {
        list($controllerName, $method) = explode('@', $action);
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName;

            if (method_exists($controller, $method)) {
                $controller->{$method}();
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Method not found']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Controller not found']);
        }
    }
}

$router = new Router();


require_once __DIR__ . '/../routes/api.php';

?>
