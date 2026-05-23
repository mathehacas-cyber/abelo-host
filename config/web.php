<?php declare(strict_types=1);

define('DB_HOST', $_ENV['DB_HOST'] ?? 'mysql');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'blog');
define('DB_USER', $_ENV['DB_USER'] ?? 'user');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');