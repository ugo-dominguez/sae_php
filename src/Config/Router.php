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

        if ($path === '' || $path === '/') {
            $path = '/';
        }

        foreach ($this->routes[$method] as $route => $callback) {
            $pattern = preg_replace('/\{([^\/]+)\}/', '([^/]+)', $route);
            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches);
                call_user_func_array($callback, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "Page not found";
    }
}
