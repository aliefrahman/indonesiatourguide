<?php
// File: app/Core/Router.php

namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $route, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'route' => $route,
            'handler' => $handler
        ];
    }

    public function get($route, $handler) {
        $this->add('GET', $route, $handler);
    }

    public function post($route, $handler) {
        $this->add('POST', $route, $handler);
    }

    public function dispatch($url, $method) {
        $path = parse_url($url, PHP_URL_PATH);
        $path = $path === '/' ? '/' : rtrim($path, '/');
        
        foreach ($this->routes as $routeInfo) {
            if ($routeInfo['method'] !== strtoupper($method)) {
                continue;
            }

            // Ubah placeholder {param} menjadi regex group named parameter
            $pattern = $routeInfo['route'];
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
            $pattern = '#^' . ($pattern === '/' ? '/' : rtrim($pattern, '/')) . '$#';

            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                $handler = $routeInfo['handler'];
                
                if (is_array($handler)) {
                    $controllerName = $handler[0];
                    $methodName = $handler[1];
                    
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $methodName)) {
                            return call_user_func_array([$controller, $methodName], $params);
                        }
                    }
                }

                // Jika handler adalah closure/fungsi langsung
                if (is_callable($handler)) {
                    return call_user_func_array($handler, $params);
                }
                
                http_response_code(500);
                die("Internal Server Error: Handler tidak valid untuk route " . htmlspecialchars($path));
            }
        }

        // 404 Not Found
        http_response_code(404);
        $errorView = __DIR__ . '/../../resources/views/errors/404.php';
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            echo "<h1>404 Halaman Tidak Ditemukan</h1>";
        }
        exit;
    }
}
