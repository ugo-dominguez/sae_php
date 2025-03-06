<?php
namespace App\Config;

class Router {
    private array $routes = [
        'GET' => [],
        'POST' => []
    ];
    
    public function get(string $path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }
    
    public function post(string $path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }
    
    public function dispatch(string $path) {
        $method = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            if (is_array($callback) && is_string($callback[0])) {
                $controller = new $callback[0]();
                $action = $callback[1];
                call_user_func([$controller, $action]);
            } else {
                call_user_func($callback);
            }
        } else {
            http_response_code(404);
            echo "Page not found";
        }
    }
}
