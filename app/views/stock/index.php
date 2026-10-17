
<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Manajemen Stok</h2>
            <p>Catat barang masuk dan keluar serta pantau riwayat stok.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Pergerakan stok berhasil dicatat.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            Transaksi gagal. Periksa produk, jumlah stok, dan data yang dimasukkan.
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Catat Pergerakan Stok</h2>
                <p>Pilih produk dan jenis pergerakan stok.</p>
            </div>
        </div>

        <form method="POST" action="index.php?page=stock-store"
              class="stock-form">

            <label for="product_id">Produk</label>
            <select id="product_id" name="product_id" required>
                <option value="">Pilih produk</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= (int) $product['id'] ?>">
                        <?= htmlspecialchars(
                            $product['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        (Stok: <?= (int) $product['stock'] ?>
                        <?= htmlspecialchars(
                            $product['unit'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="type">Jenis Pergerakan</label>
            <select id="type" name="type" required>
                <option value="IN">Stok Masuk</option>
                <option value="OUT">Stok Keluar</option>
            </select>

            <label for="quantity">Jumlah</label>
            <input
                type="number"
                id="quantity"
                name="quantity"
                min="1"
                step="1"
                required
            >

            <label for="notes">Keterangan</label>
            <textarea
                id="notes"
                name="notes"
                rows="3"
                placeholder="Contoh: Barang datang dari pemasok"
            ></textarea>

            <button type="submit" class="btn-primary">
                Simpan Pergerakan Stok
            </button>
        </form>
    </section>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Riwayat Pergerakan Stok</h2>
                <p><?= count($movements) ?> catatan pergerakan stok</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (empty($movements)): ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            Belum ada riwayat pergerakan stok.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movements as $movement): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $movement['created_at'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $movement['sku'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $movement['product_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= $movement['type'] === 'IN'
                                    ? 'Stok Masuk'
                                    : 'Stok Keluar' ?>
                            </td>
                            <td>
                                <?= number_format(
                                    (int) $movement['quantity']
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $movement['notes'] ?? '—',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</section>
