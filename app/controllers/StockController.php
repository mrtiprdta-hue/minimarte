
<?php

require_once __DIR__ . '/../models/StockMovement.php';
require_once __DIR__ . '/../models/Product.php';

class StockController
{
    private StockMovement $stockModel;
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->stockModel = new StockMovement($db);
        $this->productModel = new Product($db);
    }

    public function index(): void
    {
        $movements = $this->stockModel->getAllMovements();
        $products = $this->productModel->getAllProducts();
        $pageTitle = 'Manajemen Stok';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/stock/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=stock');
            exit;
        }

        $productId = filter_input(
            INPUT_POST,
            'product_id',
            FILTER_VALIDATE_INT
        );

        $quantity = filter_input(
            INPUT_POST,
            'quantity',
            FILTER_VALIDATE_INT
        );

        $type = $_POST['type'] ?? '';
        $notes = trim($_POST['notes'] ?? '');

        if (
            !$productId ||
            $productId < 1 ||
            !$quantity ||
            $quantity < 1 ||
            !in_array($type, ['IN', 'OUT'], true)
        ) {
            header('Location: index.php?page=stock&error=invalid');
            exit;
        }

        try {
            $this->stockModel->recordMovement(
                $productId,
                $type,
                $quantity,
                $notes
            );

            header('Location: index.php?page=stock&success=1');
            exit;
        } catch (InvalidArgumentException $e) {
            header('Location: index.php?page=stock&error=invalid');
            exit;
        } catch (RuntimeException $e) {
            header('Location: index.php?page=stock&error=failed');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header('Location: index.php?page=stock&error=failed');
            exit;
        }
    }
}
