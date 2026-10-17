
<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/StockController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';

try {
    $db = (new Database())->connect();

    $page = $_GET['page'] ?? 'dashboard';

    switch ($page) {
        case 'dashboard':
            (new DashboardController($db))->index();
            break;

        case 'products':
            (new ProductController($db))->index();
            break;

        case 'products-create':
            (new ProductController($db))->create();
            break;

        case 'products-store':
            (new ProductController($db))->store();
            break;

        case 'products-edit':
            (new ProductController($db))->edit();
            break;

        case 'products-update':
            (new ProductController($db))->update();
            break;

        case 'products-delete':
            (new ProductController($db))->delete();
            break;

        case 'stock':
            (new StockController($db))->index();
            break;

        case 'stock-store':
            (new StockController($db))->store();
            break;

        case 'categories':
            (new CategoryController($db))->index();
            break;

        case 'categories-store':
            (new CategoryController($db))->store();
            break;

        case 'categories-edit':
            (new CategoryController($db))->edit();
            break;

        case 'categories-update':
            (new CategoryController($db))->update();
            break;

        case 'categories-delete':
            (new CategoryController($db))->delete();
            break;
            
        default:
            http_response_code(404);
            echo 'Halaman tidak ditemukan.';
            break;
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);

    echo 'Terjadi masalah pada database. Periksa konfigurasi dan log server.';
}