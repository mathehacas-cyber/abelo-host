<?php declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASSWORD);
            } catch (PDOException $e) {
                die('Errors databases: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
