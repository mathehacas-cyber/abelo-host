<?php declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/web.php";


App\Core\Kernel::getInstance()->run();