<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class IndexController
{
    public function index(array $params = []):void
    {
        $view = new View();
        $view->render('index');
    }
}