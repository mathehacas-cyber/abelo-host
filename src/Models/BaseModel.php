<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Db;
use App\Core\Kernel;
use PDO;

abstract class BaseModel
{
    /**
     * @return PDO
     * @throws \Exception
     */
    protected static function db(): PDO
    {
        return Kernel::container()->make(Db::class);
    }
}