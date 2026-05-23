<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;

class IndexController extends BaseController
{
    public function index(array $params = []):void
    {
        $this->view('index', [
            'categories' => Category::getWithLastArticles(),
        ]);
    }
}