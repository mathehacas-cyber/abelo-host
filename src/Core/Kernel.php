<?php declare(strict_types=1);

namespace App\Core;

class Kernel
{
    private static ?self $instance = null;
    private Container $container;
    private Router $router;

    public function __construct() {
        $this->container = new Container();
        $this->router    = new Router($this->container);
        $this->registerBindings();
        $this->registerRoutes();
    }

    public static function getInstance(): static
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function container(): Container
    {
        return self::getInstance()->container;
    }

    private function registerBindings(): void
    {
        $this->container->bind(Db::class, function () {
            Db::configure([
                'host'     => $_ENV['DB_HOST']     ?? 'mysql',
                'name'     => $_ENV['DB_NAME']     ?? 'blog',
                'user'     => $_ENV['DB_USER']     ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ]);
            return Db::getInstance();
        });

        $this->container->bind(View::class, fn() => new View(new SmartyRenderer()));
    }

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

    public function run(): void
    {
        $this->router->dispatch();
    }
}