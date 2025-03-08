<?php
declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require_once ROOT_DIR . '/vendor/autoload.php';

use App\Config\Provider;
use App\Config\Database;
use App\Config\Router;
use App\Config\Requests;
use App\Controllers\HomeController;
use App\Controllers\SearchController;
use App\Controllers\RestaurantController;

session_start();

$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');


try {
    // Init
    $router = new Router();

    // Urls
    $router->get('/', [new HomeController(), 'home']);
    $router->get('/search', [new SearchController(), 'search']);
    $router->get('/restaurant/{id}', function ($id) {
        (new RestaurantController())->show($id);
    });

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $router->dispatch($path);

} catch (\Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo "Erreur : " . $e->getMessage();
}
