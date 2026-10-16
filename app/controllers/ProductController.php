
<?php

require_once __DIR__ . '/../models/Product.php';

class ProductController
{
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->productModel = new Product($db);
    }

    public function index(): void
    {
        $keyword = trim($_GET['search'] ?? '');

        $products = $this->productModel->getAllProducts($keyword);
        $pageTitle = 'Produk';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/products/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}