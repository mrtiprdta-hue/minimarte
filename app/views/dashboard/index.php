
<?php
$money = static fn($value) =>
    'Rp ' . number_format((float) $value, 0, ',', '.');
?>

<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Selamat datang kembali!</h2>
            <p>Berikut ringkasan inventaris minimarket kamu.</p>
        </div>
        <span class="today-date">
            <?= date('d M Y') ?>
        </span>
    </div>

    <div class="stats-grid">

        <article class="stat-card">
            <div class="stat-top">
                <span>Total Produk</span>
                <span class="stat-icon">▦</span>
            </div>
            <strong><?= number_format($stats['total_products']) ?></strong>
            <small>Produk terdaftar</small>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <span>Total Stok</span>
                <span class="stat-icon">□</span>
            </div>
            <strong><?= number_format($stats['total_stock']) ?></strong>
            <small>Unit persediaan</small>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <span>Stok Menipis</span>
                <span class="stat-icon warning-icon">!</span>
            </div>
            <strong><?= number_format($stats['low_stock']) ?></strong>
            <small>Produk perlu diperiksa</small>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <span>Segera Kedaluwarsa</span>
                <span class="stat-icon danger-icon">◷</span>
            </div>
            <strong><?= number_format($stats['expiring_soon']) ?></strong>
            <small>Dalam 30 hari ke depan</small>
        </article>

    </div>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Produk Terbaru</h2>
                <p>Daftar produk yang terakhir ditambahkan.</p>
            </div>

            <a class="btn-primary" href="index.php?page=products">
                Lihat Produk →
            </a>
        </div>

        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recentProducts)): ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            Belum ada produk.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentProducts as $product): ?>
                        <?php
                        $stock = (int) $product['stock'];
                        $status = $stock <= 5 ? 'Menipis' : 'Tersedia';
                        $statusClass = $stock <= 5 ? 'badge-warning' : 'badge-success';
                        ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= htmlspecialchars($product['name']) ?>
                                </strong>
                                <small class="sku">
                                    <?= htmlspecialchars($product['sku']) ?>
                                </small>
                            </td>
                            <td><?= htmlspecialchars($product['category_name']) ?></td>
                            <td><?= $money($product['selling_price']) ?></td>
                            <td>
                                <?= number_format($stock) ?>
                                <?= htmlspecialchars($product['unit']) ?>
                            </td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</section>