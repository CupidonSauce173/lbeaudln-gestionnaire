<?php

namespace Lbeaudln\Gestionnaire\Core;

class Router
{
    private array $routes = [];

    public function post(string $uri, string $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function get(string $uri, string $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function redirect(string $uri, string $requestMethod): void
    {
        $this->dispatch($uri, $requestMethod);
    }

    public function dispatch(string $uri, string $requestMethod): void
    {
        $action = $this->routes[$requestMethod][$uri] ?? null;

        if (!$action) {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
            exit;
        }

        if (is_callable($action)) {
            call_user_func($action);
        } elseif (str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action);
            if (class_exists($controller) && method_exists($controller, $method)) {
                call_user_func_array([new $controller, $method], []);
            } else {
                header("HTTP/1.0 500 Internal Server Error");
                echo "Controller or method not found";
            }
        } else {
            header("HTTP/1.0 500 Internal Server Error");
            echo "Invalid action";
        }
    }
}
