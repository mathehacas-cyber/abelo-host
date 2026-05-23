<?php declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $instance = null;
    private static array $config = [];

    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . self::$config['host'] . ';dbname=' . self::$config['name'] . ';charset=utf8mb4',
                    self::$config['user'],
                    self::$config['password']
                );
            } catch (PDOException $e) {
                die('Errors databases: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
