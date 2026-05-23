<?php declare(strict_types=1);

namespace App\Models;

class Article extends BaseModel
{

    /**
     * @param string $slug
     * @return array|null
     */
    public static function getBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare('
            SELECT * FROM articles WHERE slug = :slug
        ');
        $stmt->execute(['slug' => $slug]);
        $article = $stmt->fetch();

        if (!$article) {
            return null;
        }

        $article['categories']    = self::getCategories((int)$article['id']);
        $article['category_ids']  = array_column($article['categories'], 'id');

        return $article;
    }

    /**
     * @param string $slug
     * @param string $sort
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public static function getByCategorySlug(
        string $slug,
        string $sort,
        int $page,
        int $perPage
    ): array {
        $order  = $sort === 'views' ? 'a.views DESC' : 'a.public_at DESC';
        $offset = ($page - 1) * $perPage;

        $stmt = self::db()->prepare("
            SELECT a.*
            FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            JOIN categories c ON c.id = ac.category_id
            WHERE c.slug = :slug
            ORDER BY {$order}
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':slug',   $slug,    \PDO::PARAM_STR);
        $stmt->bindValue(':limit',  $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $slug
     * @return int
     */
    public static function countByCategorySlug(string $slug): int
    {
        $stmt = self::db()->prepare('
            SELECT COUNT(*) FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            JOIN categories c ON c.id = ac.category_id
            WHERE c.slug = :slug
        ');
        $stmt->execute(['slug' => $slug]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * @param int $id
     * @return void
     */
    public static function incrementViews(int $id): void
    {
        $stmt = self::db()->prepare('
            UPDATE articles SET views = views + 1 WHERE id = :id
        ');
        $stmt->execute(['id' => $id]);
    }

    /**
     * @param int $articleId
     * @param array $categoryIds
     * @return array
     */
    public static function getRelated(int $articleId, array $categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $stmt = self::db()->prepare("
            SELECT DISTINCT a.*
            FROM articles a
            JOIN article_category ac ON ac.article_id = a.id
            WHERE ac.category_id IN ({$placeholders})
              AND a.id != ?
            ORDER BY a.public_at DESC
            LIMIT 3
        ");

        $stmt->execute([...$categoryIds, $articleId]);

        return $stmt->fetchAll();
    }

    /**
     * @param int $articleId
     * @return array
     */
    private static function getCategories(int $articleId): array
    {
        $stmt = self::db()->prepare('
            SELECT c.*
            FROM categories c
            JOIN article_category ac ON ac.category_id = c.id
            WHERE ac.article_id = :article_id
        ');
        $stmt->execute(['article_id' => $articleId]);

        return $stmt->fetchAll();
    }
}