<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Category;

class IndexController extends BaseController
{
    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function index(array $params = []):void
    {
        $this->view('index', [
            'categories' => Category::getWithLastArticles(),
        ]);
    }
}