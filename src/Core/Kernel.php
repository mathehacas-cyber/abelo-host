<?php declare(strict_types=1);

namespace App\Core;

class Kernel
{
    private static ?self $instance = null;
    private Container $container;
    private Router $router;

    private function __construct() {
        $this->registerErrorHandlers();
        $this->loadEnv();
        $this->container = new Container();
        $this->router    = new Router($this->container);
        $this->registerBindings();
        $this->registerRoutes();
    }

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return void
     */
    private function loadEnv(): void
    {
        $env = parse_ini_file(__DIR__ . '/../../.env');
        if ($env === false) {
            throw new \RuntimeException('.env file not found or invalid');
        }
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }
    }

    /**
     * @return Container
     */
    public static function container(): Container
    {
        return self::getInstance()->container;
    }

    /**
     * @return void
     */
    private function registerBindings(): void
    {
        $storageDir = __DIR__ . '/../../storage';

        $this->container->bind(Cache::class, fn() => new Cache($storageDir . '/cache'));
        $this->container->bind(Logger::class, fn() => new Logger($storageDir . '/logs'));

        $this->container->bind(Db::class, function () {
            Db::configure([
                'host' => $_ENV['DB_HOST'] ?? 'mysql',
                'name' => $_ENV['DB_NAME'] ?? 'blog',
                'user' => $_ENV['DB_USER'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ]);
            return Db::getInstance();
        });

        $this->container->bind(View::class, fn() => new View(new SmartyRenderer()));
    }

    /**
     * @return void
     */
    private function registerRoutes(): void
    {
        $router = $this->router;
        require_once __DIR__ . '/../../config/routes.php';
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->router->dispatch();
    }

    /**
     * @return void
     */
    private function registerErrorHandlers(): void
    {
        set_exception_handler(function (\Throwable $e) {
            $this->renderError(500, $e->getMessage());
        });

        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                $this->renderError(500, $error['message']);
            }
        });
    }

    /**
     * @param int $code
     * @param string $message
     * @return void
     */
    private function renderError(int $code, string $message = ''): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($code);

        if ($this->container === null) {
            echo "<h1>{$code} Error</h1>";
            return;
        }

        try {
            $this->container->make(Logger::class)->error("HTTP {$code}", [
                'message' => $message,
                'uri'     => $_SERVER['REQUEST_URI'] ?? '',
            ]);
        } catch (\Throwable) {}

        try {
            $view = $this->container->make(View::class);
            $view->render('errors/' . $code, ['message' => $message, 'code' => $code]);
        } catch (\Throwable) {
            echo '<h1>' . $code . ' Internal Server Error</h1>';
        }
    }
}
