
<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Manajemen Kategori</h2>
            <p>Kelola kategori produk minimarket kamu.</p>
        </div>
    </div>

    <?php if (($_GET['success'] ?? '') === 'created'): ?>
        <div class="alert alert-success">
            Kategori berhasil ditambahkan.
        </div>
    <?php elseif (($_GET['success'] ?? '') === 'updated'): ?>
        <div class="alert alert-success">
            Kategori berhasil diperbarui.
        </div>
    <?php elseif (($_GET['success'] ?? '') === 'deleted'): ?>
        <div class="alert alert-success">
            Kategori berhasil dihapus.
        </div>
    <?php endif; ?>

    <?php if (($_GET['error'] ?? '') === 'empty'): ?>
        <div class="alert alert-error">
            Nama kategori wajib diisi.
        </div>
    <?php elseif (($_GET['error'] ?? '') === 'duplicate'): ?>
        <div class="alert alert-error">
            Nama kategori sudah digunakan.
        </div>
    <?php elseif (($_GET['error'] ?? '') === 'failed'): ?>
        <div class="alert alert-error">
            Kategori gagal disimpan.
        </div>
    <?php elseif (($_GET['error'] ?? '') === 'in_use'): ?>
        <div class="alert alert-error">
            Kategori tidak dapat dihapus karena masih digunakan produk.
        </div>
    <?php elseif (($_GET['error'] ?? '') === 'not_deleted'): ?>
        <div class="alert alert-error">
            Kategori tidak terhapus. Periksa ID atau relasi data kategori.
        </div>
    <?php elseif (($_GET['error'] ?? '') === 'not_found'): ?>
        <div class="alert alert-error">
            Kategori tidak ditemukan.
        </div>
    <?php endif; ?>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Tambah Kategori</h2>
                <p>Masukkan nama kategori produk baru.</p>
            </div>
        </div>

        <form action="index.php?page=categories-store"
              method="POST"
              class="stock-form">

            <label for="name">Nama Kategori</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                placeholder="Contoh: Makanan, Minuman, Kebersihan"
                required
            >

            <button type="submit" class="btn-primary">
                + Tambah Kategori
            </button>
        </form>
    </section>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Daftar Kategori</h2>
                <p><?= count($categories) ?> kategori terdaftar.</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                Belum ada kategori.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $index => $category): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= number_format(
                                        (int) $category['total_products']
                                    ) ?>
                                    produk
                                </td>

                                <td>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <a
                                            class="btn-primary"
                                            href="index.php?page=categories-edit&id=<?= (int) $category['id'] ?>"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="index.php?page=categories-delete"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori <?= htmlspecialchars(addslashes($category['name']), ENT_QUOTES, 'UTF-8') ?>?');"
                                            style="margin: 0;"
                                        >
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= (int) $category['id'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-danger"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</section>