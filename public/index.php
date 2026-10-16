
<?php

require_once __DIR__ . '/../config/Database.php';

try {
    $db = (new Database())->connect();
    echo 'MINIMARTÉ berhasil terhubung ke database!';
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Koneksi database gagal. Periksa konfigurasi database.';
}
