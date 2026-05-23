<?php declare(strict_types=1);

namespace App\Models;

class Category extends BaseModel
{

    /**
     * @return array
     */
    public static function getWithLastArticles(): array
    {
        $stmt = self::db()->query('
            SELECT c.*
            FROM categories c
            WHERE EXISTS (
                SELECT 1 FROM article_category ac
                WHERE ac.category_id = c.id
            )
            ORDER BY c.title
        ');

        $categories = $stmt->fetchAll();

        foreach ($categories as &$category) {
            $category['articles'] = self::getLastArticles((int)$category['id']);
        }

        return $categories;
    }

    /**
     * @param int $categoryId
     * @return array
     */
    private static function getLastArticles(int $categoryId): array
    {
        $stmt = self::db()->prepare('
            SELECT a.*
            FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY a.public_at DESC
            LIMIT 3
        ');
        $stmt->execute(['category_id' => $categoryId]);

        return $stmt->fetchAll();
    }
}