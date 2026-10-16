
<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Daftar Produk</h2>
            <p>Kelola inventaris produk minimarket kamu.</p>
        </div>

        <a class="btn-primary" href="index.php?page=products-create">
            + Tambah Produk
        </a>
    </div>

    <section class="panel">

        <form method="GET" action="index.php" class="search-form">
            <input type="hidden" name="page" value="products">

            <input
                type="search"
                name="search"
                placeholder="Cari nama, SKU, atau kategori..."
                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
            >

            <button type="submit" class="btn-primary">Cari</button>

            <?php if ($keyword !== ''): ?>
                <a class="reset-link" href="index.php?page=products">
                    Reset
                </a>
            <?php endif; ?>
        </form>

        <div class="panel-heading">
            <div>
                <h2>Inventaris Produk</h2>
                <p><?= count($products) ?> produk ditemukan</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Kedaluwarsa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="empty-state">
                            Produk tidak ditemukan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($product['sku'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                Rp <?= number_format((float) $product['purchase_price'], 0, ',', '.') ?>
                            </td>

                            <td>
                                Rp <?= number_format((float) $product['selling_price'], 0, ',', '.') ?>
                            </td>

                            <td>
                                <?= number_format((int) $product['stock']) ?>
                                <?= htmlspecialchars($product['unit'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <?php if ($product['expiry_date']): ?>
                                    <?= htmlspecialchars($product['expiry_date'], ENT_QUOTES, 'UTF-8') ?>

                            <td>
                                <a class="btn-secondary"
                                href="index.php?page=products-edit&id=<?= (int) $product['id'] ?>">
                                    Edit
                                </a>
                            </td>
                                <?php else: ?>
                                    <span class="sku">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</section>