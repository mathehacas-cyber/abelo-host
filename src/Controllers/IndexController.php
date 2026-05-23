<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class IndexController extends BaseController
{
    public function index(array $params = []):void
    {
        $this->view('index');
    }
}