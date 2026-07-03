<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\UsersController;
use App\ErrorHandler;
use App\Router;

$config = require dirname(__DIR__) . '/config/config.php';

// Register global error/exception handling before anything else runs.
(new ErrorHandler($config['debug']))->register();

// CORS: allow the separately-served frontend to call the API from the browser.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Answer the browser's preflight before routing (the router has no OPTIONS routes).
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$router = new Router();

$router->get('/', [HomeController::class, 'get']);
$router->get('/users', [UsersController::class, 'get']);
$router->get('/register', [UsersController::class, 'register']);
$router->post('/register', [UsersController::class, 'store']);
$router->get('/update', [UsersController::class, 'edit']);
$router->put('/update', [UsersController::class, 'update']);
$router->delete('/delete', [UsersController::class, 'delete']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
