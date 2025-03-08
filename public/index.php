<?php
declare(strict_types=1);
define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . '/vendor/autoload.php';

use App\Config\Provider;
use App\Config\Database;
use App\Config\Router;
use App\Config\Requests;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

try {
    $dbPath = ROOT_DIR . '/baratie.db';
    if (!file_exists($dbPath)) {
        Database::setConnection();
        Database::createTables();
        $jsonFilePath = __DIR__ . "/assets/restaurants_orleans.json";
        $provider = new Provider($jsonFilePath);
        $data = $provider->getData();
        Database::insertRestaurants($data);
    }
    Database::setConnection();
    Requests::setConnexion();

    $router = new Router();
    $router->get('', [HomeController::class, 'home']);
    $router->get('register', [AuthController::class, 'registerForm']);
    $router->post('register', [AuthController::class, 'registerSubmit']);
    $router->get('login', [AuthController::class, 'showLoginForm']);
    $router->post('login', [AuthController::class, 'login']);
    $router->get('logout', [AuthController::class, 'logout']);
    $router->get('profile', [AuthController::class, 'profile']);
    $router->dispatch($path);
} catch (\Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo "Erreur : " . $e->getMessage();
}
