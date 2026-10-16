
<?php

class Product
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getDashboardStats(): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total_products,
                COALESCE(SUM(stock), 0) AS total_stock,
                COALESCE(SUM(
                    CASE WHEN stock <= 5 THEN 1 ELSE 0 END
                ), 0) AS low_stock,
                COALESCE(SUM(
                    CASE
                        WHEN expiry_date BETWEEN CURDATE()
                        AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                        THEN 1 ELSE 0
                    END
                ), 0) AS expiring_soon
            FROM products
        ";

        return $this->db->query($sql)->fetch();
    }

    public function getRecentProducts(): array
    {
        $sql = "
            SELECT
                p.id,
                p.sku,
                p.name,
                p.stock,
                p.unit,
                p.selling_price,
                p.expiry_date,
                c.name AS category_name
            FROM products p
            INNER JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC, p.id DESC
            LIMIT 5
        ";

        return $this->db->query($sql)->fetchAll();
    }
}