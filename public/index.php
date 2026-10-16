
<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

try {
    $db = (new Database())->connect();

    $page = $_GET['page'] ?? 'dashboard';

    switch ($page) {
        case 'dashboard':
            (new DashboardController($db))->index();
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