
<?php

class Category
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllCategories(): array
    {
        $sql = "
            SELECT
                c.id,
                c.name,
                c.description,
                COUNT(p.id) AS total_products
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id, c.name, c.description
            ORDER BY c.name ASC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT id, name, description
             FROM categories
             WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory(
        string $name,
        string $description = ''
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, description)
             VALUES (:name, :description)"
        );

        return $stmt->execute([
            'name' => trim($name),
            'description' => trim($description) !== ''
                ? trim($description)
                : null,
        ]);
    }

    public function updateCategory(
        int $id,
        string $name,
        string $description = ''
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE categories
             SET name = :name, description = :description
             WHERE id = :id"
        );

        $stmt->execute([
            'name' => trim($name),
            'description' => trim($description) !== ''
                ? trim($description)
                : null,
            'id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
