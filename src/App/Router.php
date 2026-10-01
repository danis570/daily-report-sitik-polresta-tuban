<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

class Router
{
    private array $routes = [];
    public function add(string $method, string $path, array $controller, array $middlewares = []): void
    {
        $regexPath = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $path);
        $regexPath = '#^' . $regexPath . '$#';

        $this->routes[strtoupper($method)][] = [
            'regex' => $regexPath,
            'controller' => $controller,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, array $controller, array $middlewares = []): void
    {
        $this->add('GET', $path, $controller, $middlewares);
    }

    public function post(string $path, array $controller, array $middlewares = []): void
    {
        $this->add('POST', $path, $controller, $middlewares);
    }

    public function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        if (!isset($this->routes[$requestMethod])) {
            $this->send404();
            return;
        }

        foreach ($this->routes[$requestMethod] as $route) {
            if (preg_match($route['regex'], $requestUri, $matches)) {
                array_shift($matches);

                foreach ($route['middlewares'] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        if (!$middlewareInstance->before()) {
                            return;
                        }
                    }
                }

                [$controllerClass, $method] = $route['controller'];
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if (method_exists($controllerInstance, $method)) {
                        call_user_func_array([$controllerInstance, $method], $matches);
                        return;
                    }
                }
            }
        }

        $this->send404();
    }

    private function send404(): void
    {
        http_response_code(404);
        echo json_encode(['error' => 'Halaman tidak ditemukan (404)']);
    }
}
