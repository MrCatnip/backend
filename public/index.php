<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Router;

$router = new Router();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
