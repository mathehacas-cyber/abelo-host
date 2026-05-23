<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;

class CategoryController extends BaseController
{
    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function index(array $params = []): void
    {
        $slug = $params['slug'] ?? '';

        $category = Category::getBySlug($slug);

        if (!$category) {
            $this->terminate(404);
        }

        $sort    = in_array($_GET['sort'] ?? '', ['views', 'date']) ? $_GET['sort'] : 'date';
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 6;
        $total   = Article::countByCategorySlug($slug);

        $this->view('category', [
            'category'    => $category,
            'articles'    => Article::getByCategorySlug($slug, $sort, $page, $perPage),
            'sort'        => $sort,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage),
            'total'       => $total,
        ]);
    }
}