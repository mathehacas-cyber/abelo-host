<?php declare(strict_types=1);

namespace App\Models;

class Category extends BaseModel
{
    /**
     * @param string $slug
     * @return array|null
     */
    public static function getBySlug(string $slug): ?array
    {
        return self::cache()->remember("category.slug.{$slug}", 600, function () use ($slug) {
            $stmt = self::db()->prepare('
                SELECT * FROM categories WHERE slug = :slug
            ');
            $stmt->execute(['slug' => $slug]);

            return $stmt->fetch() ?: null;
        });
    }

    /**
     * @return array
     */
    public static function getWithLastArticles(): array
    {
        return self::cache()->remember('categories.with_articles', 300, function () {
            $stmt = self::db()->query('
                SELECT
                    c.id AS cat_id,
                    c.title AS cat_title,
                    c.description AS cat_description,
                    c.slug AS cat_slug,
                    ranked.id AS art_id,
                    ranked.title AS art_title,
                    ranked.description AS art_description,
                    ranked.image AS art_image,
                    ranked.slug AS art_slug,
                    ranked.views AS art_views,
                    ranked.public_at AS art_public_at
                FROM categories c
                JOIN (
                    SELECT ac.category_id,
                           a.id, a.title, a.description, a.image, a.slug, a.views, a.public_at,
                           ROW_NUMBER() OVER (PARTITION BY ac.category_id ORDER BY a.public_at DESC) AS rn
                    FROM articles a
                    JOIN article_category ac ON ac.article_id = a.id
                ) ranked ON ranked.category_id = c.id AND ranked.rn <= 3
                ORDER BY c.title, ranked.public_at DESC
            ');

            $rows       = $stmt->fetchAll();
            $categories = [];

            foreach ($rows as $row) {
                $catId = $row['cat_id'];

                if (!isset($categories[$catId])) {
                    $categories[$catId] = [
                        'id' => $row['cat_id'],
                        'title' => $row['cat_title'],
                        'description' => $row['cat_description'],
                        'slug' => $row['cat_slug'],
                        'articles' => [],
                    ];
                }

                $categories[$catId]['articles'][] = [
                    'id' => $row['art_id'],
                    'title' => $row['art_title'],
                    'description' => $row['art_description'],
                    'image' => $row['art_image'],
                    'slug' => $row['art_slug'],
                    'views' => $row['art_views'],
                    'public_at' => $row['art_public_at'],
                ];
            }

            return array_values($categories);
        }) ?? [];
    }
}
