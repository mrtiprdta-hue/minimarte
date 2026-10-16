
<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Tambah Produk Baru</h2>
            <p>Lengkapi informasi produk minimarket.</p>
        </div>

        <a class="reset-link" href="index.php?page=products">
            ← Kembali ke Produk
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form-alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <section class="panel">
        <form method="POST" action="index.php?page=products-store">
            <div class="form-grid">

                <label class="form-field">
                    SKU
                    <input name="sku" required maxlength="100"
                        value="<?= htmlspecialchars($form['sku'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Contoh: MKN-001">
                </label>

                <label class="form-field">
                    Nama Produk
                    <input name="name" required maxlength="255"
                        value="<?= htmlspecialchars($form['name'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Contoh: Susu UHT 1 Liter">
                </label>

                <label class="form-field">
                    Kategori
                    <select name="category_id" required>
                        <option value="">Pilih kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>"
                                <?= (string) $form['category_id'] === (string) $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label class="form-field">
                    Satuan
                    <input name="unit" required maxlength="30"
                        value="<?= htmlspecialchars($form['unit'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="pcs, botol, pack">
                </label>

                <label class="form-field">
                    Harga Beli (Rp)
                    <input type="number" name="purchase_price" required
                        min="0" step="0.01"
                        value="<?= htmlspecialchars($form['purchase_price'], ENT_QUOTES, 'UTF-8') ?>">
                </label>

                <label class="form-field">
                    Harga Jual (Rp)
                    <input type="number" name="selling_price" required
                        min="0" step="0.01"
                        value="<?= htmlspecialchars($form['selling_price'], ENT_QUOTES, 'UTF-8') ?>">
                </label>

                <label class="form-field">
                    Stok Awal
                    <input type="number" name="stock" required
                        min="0" step="1"
                        value="<?= htmlspecialchars($form['stock'], ENT_QUOTES, 'UTF-8') ?>">
                </label>

                <label class="form-field">
                    Tanggal Kedaluwarsa
                    <input type="date" name="expiry_date"
                        value="<?= htmlspecialchars($form['expiry_date'], ENT_QUOTES, 'UTF-8') ?>">
                </label>

            </div>

            <div class="form-actions">
                <a href="index.php?page=products" class="btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    Simpan Produk
                </button>
            </div>
        </form>
    </section>
</section>