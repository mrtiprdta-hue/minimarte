
<?php

require_once __DIR__ . '/../config/Database.php';

try {
    $db = (new Database())->connect();

    echo 'MINIMARTE: Koneksi database berhasil!';
} catch (PDOException $e) {
    http_response_code(500);

    echo 'Koneksi database gagal. Periksa konfigurasi.';
}