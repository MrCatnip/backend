<?php

namespace App;

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

    public function dispatch(string $method, string $path): void
    {
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo "404 Not Found: $path";
            return;
        }

        [$class, $action] = $handler;
        (new $class())->$action();
    }
}
