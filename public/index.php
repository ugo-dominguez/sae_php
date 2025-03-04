<?php
declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . '/vendor/autoload.php';

use App\Config\Provider;
use App\Config\Database;
use \App\Controllers\HomeController;

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

// Provider
$jsonFilePath = __DIR__ . "/assets/restaurants_orleans.json";
$provider = new Provider($jsonFilePath);
$data = $provider->getData();

// Database
$db = new Database();
$db->createTables();
if (!($db->restaurantsLoaded())) {
    $db->insertRestaurants($data);
}

// Controllers
$controller = new HomeController();

if (empty($path)) {
    $controller->index();
} else {
    if (method_exists($controller, $path)) {
        $controller->$path();
    } else {
        throw new \Exception("Page not found", 404);
    }
}
