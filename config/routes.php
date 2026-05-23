<?php declare(strict_types=1);


$router->get('/', [App\Controllers\IndexController::class, 'index']);
$router->get('/category/{slug}', [App\Controllers\CategoryController::class, 'index']);
$router->get('/article/{slug}', [App\Controllers\ArticleController::class, 'index']);