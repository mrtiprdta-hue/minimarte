
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

    
public function create(): void
{
    $categories = $this->productModel->getAllCategories();
    $pageTitle = 'Tambah Produk';
    $errors = [];
    $form = [
        'sku' => '',
        'name' => '',
        'category_id' => '',
        'purchase_price' => '',
        'selling_price' => '',
        'stock' => '0',
        'unit' => 'pcs',
        'expiry_date' => '',
    ];

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/products/create.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

public function store(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=products-create');
        exit;
    }

    $form = [
        'sku' => trim($_POST['sku'] ?? ''),
        'name' => trim($_POST['name'] ?? ''),
        'category_id' => trim($_POST['category_id'] ?? ''),
        'purchase_price' => trim($_POST['purchase_price'] ?? ''),
        'selling_price' => trim($_POST['selling_price'] ?? ''),
        'stock' => trim($_POST['stock'] ?? ''),
        'unit' => trim($_POST['unit'] ?? ''),
        'expiry_date' => trim($_POST['expiry_date'] ?? ''),
    ];

    $errors = [];

    if ($form['sku'] === '') {
        $errors[] = 'SKU wajib diisi.';
    }

    if ($form['name'] === '') {
        $errors[] = 'Nama produk wajib diisi.';
    }

    if (
        $form['category_id'] === '' ||
        filter_var($form['category_id'], FILTER_VALIDATE_INT) === false
    ) {
        $errors[] = 'Pilih kategori yang valid.';
    } else {
        $validCategoryIds = array_column(
            $this->productModel->getAllCategories(),
            'id'
        );

        if (!in_array((int) $form['category_id'], array_map('intval', $validCategoryIds), true)) {
            $errors[] = 'Kategori tidak ditemukan.';
        }
    }

    foreach (['purchase_price', 'selling_price', 'stock'] as $field) {
        if (
            $form[$field] === '' ||
            !is_numeric($form[$field]) ||
            (float) $form[$field] < 0
        ) {
            $errors[] = 'Harga dan stok harus berupa angka nol atau lebih.';
            break;
        }
    }

    if (
        filter_var($form['stock'], FILTER_VALIDATE_INT) === false ||
        (int) $form['stock'] < 0
    ) {
        $errors[] = 'Stok harus berupa bilangan bulat nol atau lebih.';
    }

    if ($form['unit'] === '') {
        $errors[] = 'Satuan wajib diisi.';
    }

    if (
        $form['expiry_date'] !== '' &&
        (
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $form['expiry_date']) ||
            !checkdate(
                (int) substr($form['expiry_date'], 5, 2),
                (int) substr($form['expiry_date'], 8, 2),
                (int) substr($form['expiry_date'], 0, 4)
            )
        )
    ) {
        $errors[] = 'Tanggal kedaluwarsa tidak valid.';
    }

    if (!$errors) {
        try {
            $this->productModel->createProduct([
                'sku' => $form['sku'],
                'name' => $form['name'],
                'category_id' => (int) $form['category_id'],
                'purchase_price' => $form['purchase_price'],
                'selling_price' => $form['selling_price'],
                'stock' => (int) $form['stock'],
                'unit' => $form['unit'],
                'expiry_date' => $form['expiry_date'],
            ]);

            header('Location: index.php?page=products&created=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'SKU sudah digunakan atau data terkait tidak valid.';
            } else {
                error_log($e->getMessage());
                $errors[] = 'Produk gagal disimpan. Silakan coba lagi.';
            }
        }
    }

    $categories = $this->productModel->getAllCategories();
    $pageTitle = 'Tambah Produk';

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/products/create.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

}