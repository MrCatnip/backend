<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\UsersController;
use App\Router;

$router = new Router();

$router->get('/', [HomeController::class, 'get']);
$router->get('/users', [UsersController::class, 'get']);
$router->get('/register', [UsersController::class, 'register']);
$router->post('/register', [UsersController::class, 'store']);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
