<?php declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $instance = null;
    private static array $config = [];

    /**
     * @param array $config
     * @return void
     */
    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    /**
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . self::$config['host'] . ';dbname=' . self::$config['name'] . ';charset=utf8mb4',
                    self::$config['user'],
                    self::$config['password'],
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                        \PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new \RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
            }
        }

        return self::$instance;
    }
}
