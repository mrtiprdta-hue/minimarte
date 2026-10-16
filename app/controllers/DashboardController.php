
<?php

require_once __DIR__ . '/../models/Product.php';

class DashboardController
{
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->productModel = new Product($db);
    }

    public function index(): void
    {
        $stats = $this->productModel->getDashboardStats();
        $recentProducts = $this->productModel->getRecentProducts();

        $pageTitle = 'Dashboard';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/dashboard/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}