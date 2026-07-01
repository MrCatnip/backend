<?php

namespace App;

use App\Exceptions\NotFoundException;

class Router
{
    /**
     * All registered routes, structured as:
     *   $routes[ httpMethod ][ urlPath ] = [ ControllerClass, methodName ]
     *   e.g. $routes['GET']['/tasks'] = [TaskController::class, 'index']
     *
     * @var array<string, array<string, array{0: class-string, 1: string}>>
     */
    private array $routes = [];

    /**
     * @param array{0: class-string, 1: string} $handler  [ControllerClass, methodName]
     */
    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * @param array{0: class-string, 1: string} $handler  [ControllerClass, methodName]
     */
    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * @param array{0: class-string, 1: string} $handler  [ControllerClass, methodName]
     */
    public function put(string $path, array $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    /**
     * @param array{0: class-string, 1: string} $handler  [ControllerClass, methodName]
     */
    public function delete(string $path, array $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            throw new NotFoundException("No route for {$path}");
        }

        [$class, $action] = $handler;
        (new $class())->$action();
    }
}
