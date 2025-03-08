<?php
namespace App\Config;

class Router {
    private array $routes = [];
    
    public function get(string $path, callable $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function dispatch(string $path) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($path === '' || $path === '/') {
            $path = '/';
        }

        foreach ($this->routes[$method] as $route => $callback) {
            $pattern = preg_replace('/\{([^\/]+)\}/', '([^/]+)', $route);
            if (preg_match("#^$pattern$#", $path, $matches)) {
                array_shift($matches); // Remove full match
                call_user_func_array($callback, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "Page not found";
    }
}
