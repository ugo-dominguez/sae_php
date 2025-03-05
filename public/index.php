<?php
declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . '/vendor/autoload.php';

use App\Config\Provider;
use App\Config\Database;
use App\Config\Router;
use App\Controllers\HomeController;

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');


try {
    // Init
    $controller = new HomeController();
    $router = new Router();

    // Urls
    $router->get('', [$controller, 'home']);
    $router->dispatch($path);
    
    // Provider
    $jsonFilePath = __DIR__ . "/assets/restaurants_orleans.json";
    $provider = new Provider($jsonFilePath);
    $data = $provider->getData();

    // Database
    $dbPath = ROOT_DIR . '/baratie.db';
    if (!file_exists($dbPath)) {
        $db = new Database($dbPath);
        $db->createTables();
        $db->insertRestaurants($data);
    }

} catch (\Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo "Erreur : " . $e->getMessage();
}
