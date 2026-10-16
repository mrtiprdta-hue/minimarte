
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> | MINIMARTÉ</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <div>
                <span class="breadcrumb">MINIMARTÉ / INVENTARIS</span>
                <h1><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
            </div>

            <div class="topbar-profile">
                <div class="profile-avatar">A</div>
                <div>
                    <strong>Administrator</strong>
                    <span>Admin</span>
                </div>
            </div>
        </header>