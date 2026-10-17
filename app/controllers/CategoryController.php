
<?php

require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    private Category $categoryModel;

    public function __construct(PDO $db)
    {
        $this->categoryModel = new Category($db);
    }

    public function index(): void
    {
        $categories = $this->categoryModel->getAllCategories();
        $pageTitle = 'Manajemen Kategori';

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/categories/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            header('Location: index.php?page=categories&error=empty');
            exit;
        }

        try {
            $this->categoryModel->createCategory($name);

            header('Location: index.php?page=categories&success=created');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());

            header('Location: index.php?page=categories&error=failed');
            exit;
        }
    }

    
public function edit(): void
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        header('Location: index.php?page=categories');
        exit;
    }

    $category = $this->categoryModel->getCategoryById($id);

    if (!$category) {
        header('Location: index.php?page=categories&error=not_found');
        exit;
    }

    $pageTitle = 'Edit Kategori';

    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/categories/edit.php';
    require __DIR__ . '/../views/layouts/footer.php';
}

public function update(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=categories');
        exit;
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');

    if (!$id || $id < 1 || $name === '') {
        header('Location: index.php?page=categories&error=empty');
        exit;
    }

    try {
        $this->categoryModel->updateCategory($id, $name);

        header('Location: index.php?page=categories&success=updated');
        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());

        $duplicate = $e->getCode() === '23000';
        $error = $duplicate ? 'duplicate' : 'failed';

        header('Location: index.php?page=categories&error=' . $error);
        exit;
    }
}


public function delete(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=categories');
        exit;
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        header('Location: index.php?page=categories&error=not_found');
        exit;
    }

    try {
        $deleted = $this->categoryModel->deleteCategory($id);

        if ($deleted) {
            header('Location: index.php?page=categories&success=deleted');
        } else {
            header('Location: index.php?page=categories&error=not_deleted');
        }

        exit;
    } catch (PDOException $e) {
        error_log($e->getMessage());

        header('Location: index.php?page=categories&error=in_use');
        exit;
    }
}

}
