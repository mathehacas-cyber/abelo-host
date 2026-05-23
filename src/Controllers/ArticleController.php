<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;

class ArticleController extends BaseController
{
    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function index(array $params = []): void
    {
        $slug    = $params['slug'] ?? '';
        $article = Article::getBySlug($slug);

        if (!$article) {
            $this->terminate(404);
        }

        Article::incrementViews($article['id']);

        $this->view('article', [
            'article' => $article,
            'related' => Article::getRelated($article['id'], $article['category_ids']),
        ]);
    }
}