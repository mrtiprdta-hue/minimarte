
<section class="dashboard-content">

    <div class="welcome-row">
        <div>
            <h2>Edit Kategori</h2>
            <p>Perbarui informasi kategori produk.</p>
        </div>

        <a class="btn-primary" href="index.php?page=categories">
            ← Kembali
        </a>
    </div>

    <section class="panel">
        <div class="panel-heading">
            <div>
                <h2>Informasi Kategori</h2>
                <p>Ubah nama kategori di bawah ini.</p>
            </div>
        </div>

        <form action="index.php?page=categories-update"
              method="POST"
              class="stock-form">

            <input
                type="hidden"
                name="id"
                value="<?= (int) $category['id'] ?>"
            >

            <label for="name">Nama Kategori</label>
            <input
                type="text"
                id="name"
                name="name"
                maxlength="100"
                value="<?= htmlspecialchars($category['name']) ?>"
                required
            >

            <button type="submit" class="btn-primary">
                Simpan Perubahan
            </button>
        </form>
    </section>

</section>
