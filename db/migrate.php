<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Db;

$env = parse_ini_file(__DIR__ . '/../.env');
foreach ($env as $key => $value) {
    $_ENV[$key] = $value;
}

Db::configure([
    'host'     => $_ENV['DB_HOST']     ?? getenv('DB_HOST')     ?: 'mysql',
    'name'     => $_ENV['DB_NAME']     ?? getenv('DB_NAME')     ?: 'blog',
    'user'     => $_ENV['DB_USER']     ?? getenv('DB_USER')     ?: 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '',
]);

$pdo = Db::getInstance();
$sql = file_get_contents(__DIR__ . '/schema.sql');

try {
    $pdo->exec($sql);
    echo "Migration success.\n";
} catch (\PDOException $e) {
    echo "Migration fail: " . $e->getMessage() . "\n";
}