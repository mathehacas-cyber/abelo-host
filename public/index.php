<?php declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/web.php";


$container = new App\Core\Container();

$container->bind(App\Core\View::class,
    fn() => new App\Core\View(new App\Core\SmartyRenderer())
);

$router = new App\Core\Router($container);

require_once __DIR__ . "/../config/routes.php";

$router->dispatch();