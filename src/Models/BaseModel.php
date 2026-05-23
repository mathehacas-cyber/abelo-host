<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Cache;
use App\Core\Db;
use App\Core\Kernel;
use App\Core\Logger;
use PDO;

abstract class BaseModel
{
    protected static function db(): PDO
    {
        return Kernel::container()->make(Db::class);
    }

    protected static function cache(): Cache
    {
        return Kernel::container()->make(Cache::class);
    }

    protected static function logger(): Logger
    {
        return Kernel::container()->make(Logger::class);
    }
}
