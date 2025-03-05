<?php
namespace App\Config;

class Router {
    private array $routes = [];
    
    public function get(string $path, callable $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function dispatch(string $path) {
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
        } else {
            http_response_code(404);
            echo "Page not found";
        }
    }
}
