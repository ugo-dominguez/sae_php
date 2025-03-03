<?php
declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . '/vendor/autoload.php';

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

$db = new \App\Config\Database();
$controller = new \App\Controllers\HomeController();

if (empty($path)) {
    $controller->index();
} else {
    if (method_exists($controller, $path)) {
        $controller->$path();
    } else {
        throw new \Exception("Page not found", 404);
    }
}
