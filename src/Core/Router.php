<?php declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * @param Container $container
     */
    public function __construct(
        private Container $container
    ) {}

    /**
     * @param string $path
     * @param array $handler
     * @return void
     */
    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * @param string $path
     * @param array $handler
     * @return void
     */
    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * @param string $path
     * @param array $handler
     * @return void
     */
    public function delete(string $path, array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $handler
     * @return void
     */
    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        try {
            foreach ($this->routes as $route) {
                $params = $this->match($route['path'], $uri);

                if ($route['method'] === $_SERVER['REQUEST_METHOD'] && $params !== null) {
                    [$class, $action] = $route['handler'];
                    (new $class($this->container))->$action($params);
                    return;
                }
            }

            $this->terminate(404);
        } catch (\Throwable $e) {
            \App\Core\Kernel::logger()->error($e->getMessage(), [
                'uri' => $uri,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
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
     * @throws \Exception
     */
    protected function terminate(int $statusCode = 500, string $message = ''): void
    {
        http_response_code($statusCode);

        $view = $this->container->make(View::class);
        $view->render(
            'errors/' . $statusCode,
            [
                'message' => $message,
                'code' => $statusCode,
            ]
        );
        exit;
    }
}
