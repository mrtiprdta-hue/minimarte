
<?php

class StockMovement
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllMovements(): array
    {
        $sql = "
            SELECT
                sm.id,
                sm.product_id,
                p.sku,
                p.name AS product_name,
                sm.type,
                sm.quantity,
                sm.notes,
                sm.created_at
            FROM stock_movements sm
            INNER JOIN products p ON p.id = sm.product_id
            ORDER BY sm.created_at DESC, sm.id DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function recordMovement(
        int $productId,
        string $type,
        int $quantity,
        string $notes = ''
    ): bool {
        if (!in_array($type, ['IN', 'OUT'], true)) {
            throw new InvalidArgumentException(
                'Jenis pergerakan stok tidak valid.'
            );
        }

        if ($productId < 1 || $quantity < 1) {
            throw new InvalidArgumentException(
                'Produk dan jumlah stok harus valid.'
            );
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "SELECT stock FROM products WHERE id = :id FOR UPDATE"
            );
            $stmt->execute(['id' => $productId]);

            $product = $stmt->fetch();

            if (!$product) {
                throw new RuntimeException('Produk tidak ditemukan.');
            }

            $currentStock = (int) $product['stock'];

            if ($type === 'OUT' && $quantity > $currentStock) {
                throw new RuntimeException(
                    'Stok tidak mencukupi untuk pengeluaran barang.'
                );
            }

            $newStock = $type === 'IN'
                ? $currentStock + $quantity
                : $currentStock - $quantity;

            $update = $this->db->prepare(
                "UPDATE products SET stock = :stock WHERE id = :id"
            );
            $update->execute([
                'stock' => $newStock,
                'id' => $productId,
            ]);

            $insert = $this->db->prepare(
                "INSERT INTO stock_movements
                    (product_id, type, quantity, notes)
                 VALUES
                    (:product_id, :type, :quantity, :notes)"
            );

            $insert->execute([
                'product_id' => $productId,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => trim($notes) !== '' ? trim($notes) : null,
            ]);

            $this->db->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }
}
