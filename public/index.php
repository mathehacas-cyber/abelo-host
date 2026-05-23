<?php declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/web.php";


$router = new App\Core\Router();

require_once __DIR__ . "/../config/routes.php";

$router->dispatch();