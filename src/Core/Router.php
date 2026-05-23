<?php declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * @param string $path
     * @param array $handler
     * @param string $method
     * @return void
     */
    public function get(string $path, array $handler, string $method = 'GET'): void
    {
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        try {
            foreach ($this->routes as $route) {
                $params = $this->match($route['path'], $uri);

                if ($route['method'] === $_SERVER['REQUEST_METHOD'] && $params !== null) {
                    [$class, $action] = $route['handler'];
                    (new $class())->$action($params);
                    return;
                }
            }

            $this->terminate(404);
        } catch (\Exception $e) {
            $this->terminate(500, $e->getMessage());
        }
    }

    /**
     * @param string $routePath
     * @param string $uri
     * @return array|null
     */
    private function match(string $routePath, string $uri): ?array
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routePath);
        if (preg_match('@^' . $pattern . '$@', $uri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return null;
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @return void
     */
    protected function terminate(int $statusCode = 500, string $message = ''): void
    {
        http_response_code($statusCode);

        $view = new View();
        echo $view->render(
            'errors/' . $statusCode . '.tpl',
            [
                'message' => $message,
                'code' => $statusCode,
            ]
        );
    }
}
