<?php declare(strict_types=1);

namespace App\Models;

use App\Core\Db;
use PDO;

abstract class BaseModel
{
    protected static function db(): PDO
    {
        return Kernel::container()->make(Db::class);
    }
}