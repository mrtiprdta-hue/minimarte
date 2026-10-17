
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

    
public function getAllProducts(string $keyword = ''): array
{
    $sql = "
        SELECT
            p.id,
            p.sku,
            p.name,
            p.purchase_price,
            p.selling_price,
            p.stock,
            p.unit,
            p.expiry_date,
            c.name AS category_name
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
    ";

    if ($keyword !== '') {
        $sql .= "
            WHERE p.name LIKE :keyword
               OR p.sku LIKE :keyword
               OR c.name LIKE :keyword
        ";
    }

    $sql .= " ORDER BY p.id DESC";

    $stmt = $this->db->prepare($sql);

    if ($keyword !== '') {
        $stmt->execute([
            'keyword' => '%' . $keyword . '%'
        ]);
    } else {
        $stmt->execute();
    }

    return $stmt->fetchAll();
}

public function getProductById(int $id): ?array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM products WHERE id = :id"
    );

    $stmt->execute(['id' => $id]);

    $product = $stmt->fetch();

    return $product ?: null;
}


public function getAllCategories(): array
{
    $stmt = $this->db->query(
        "SELECT id, name FROM categories ORDER BY name ASC"
    );

    return $stmt->fetchAll();
}

public function createProduct(array $data): bool
{
    $sql = "
        INSERT INTO products (
            sku,
            name,
            category_id,
            purchase_price,
            selling_price,
            stock,
            unit,
            expiry_date
        ) VALUES (
            :sku,
            :name,
            :category_id,
            :purchase_price,
            :selling_price,
            :stock,
            :unit,
            :expiry_date
        )
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'sku' => $data['sku'],
        'name' => $data['name'],
        'category_id' => $data['category_id'],
        'purchase_price' => $data['purchase_price'],
        'selling_price' => $data['selling_price'],
        'stock' => $data['stock'],
        'unit' => $data['unit'],
        'expiry_date' => $data['expiry_date'] ?: null,
    ]);
}


public function updateProduct(int $id, array $data): bool
{
    $sql = "
        UPDATE products
        SET
            sku = :sku,
            name = :name,
            category_id = :category_id,
            purchase_price = :purchase_price,
            selling_price = :selling_price,
            stock = :stock,
            unit = :unit,
            expiry_date = :expiry_date
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id' => $id,
        'sku' => $data['sku'],
        'name' => $data['name'],
        'category_id' => $data['category_id'],
        'purchase_price' => $data['purchase_price'],
        'selling_price' => $data['selling_price'],
        'stock' => $data['stock'],
        'unit' => $data['unit'],
        'expiry_date' => $data['expiry_date'] ?: null,
    ]);
}


public function deleteProduct(int $id): bool
{
    $stmt = $this->db->prepare(
        "DELETE FROM products WHERE id = :id"
    );

    $stmt->execute(['id' => $id]);

    return $stmt->rowCount() > 0;
}

public function getLowStockProducts(): array
{
    $sql = "
        SELECT id, sku, name, stock, unit
        FROM products
        WHERE stock <= 5
        ORDER BY stock ASC, name ASC
    ";

    return $this->db->query($sql)->fetchAll();
}

public function getExpiringSoonProducts(): array
{
    $sql = "
        SELECT
            id,
            sku,
            name,
            stock,
            unit,
            expiry_date
        FROM products
        WHERE expiry_date IS NOT NULL
          AND expiry_date >= CURDATE()
          AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ORDER BY expiry_date ASC, name ASC
    ";

    return $this->db->query($sql)->fetchAll();
}

}