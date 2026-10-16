
CREATE DATABASE IF NOT EXISTS minimarte_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE minimarte_db;

-- 1. Tabel kategori produk
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabel produk
CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    sku VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    purchase_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    selling_price DECIMAL(12,2) NOT NULL DEFAULT 0,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    unit VARCHAR(30) NOT NULL DEFAULT 'pcs',
    expiry_date DATE NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_products_name (name),
    INDEX idx_products_category (category_id),
    INDEX idx_products_expiry (expiry_date)
) ENGINE=InnoDB;

-- 3. Tabel riwayat stok masuk dan keluar
CREATE TABLE IF NOT EXISTS stock_movements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    type ENUM('IN', 'OUT') NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    notes VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_movements_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_movements_product (product_id),
    INDEX idx_movements_created (created_at)
) ENGINE=InnoDB;

-- 4. Data kategori awal
INSERT IGNORE INTO categories (name, description) VALUES
('Makanan', 'Makanan instan dan makanan kemasan'),
('Minuman', 'Air mineral, susu, dan minuman kemasan'),
('Snack', 'Keripik, biskuit, dan makanan ringan'),
('Sembako', 'Beras, minyak goreng, gula, dan kebutuhan pokok'),
('Kebersihan', 'Sabun, deterjen, dan pembersih rumah'),
('Perawatan Diri', 'Sampo, pasta gigi, dan kebutuhan pribadi');

-- 5. Contoh produk awal
INSERT IGNORE INTO products
    (category_id, sku, name, purchase_price, selling_price,
     stock, unit, expiry_date, description)
SELECT c.id, 'MKN-001', 'Mi Instan Goreng',
       2800, 3500, 120, 'pcs', '2027-06-30',
       'Mi instan kemasan'
FROM categories c WHERE c.name = 'Makanan';

INSERT IGNORE INTO products
    (category_id, sku, name, purchase_price, selling_price,
     stock, unit, expiry_date, description)
SELECT c.id, 'MNM-001', 'Air Mineral 600 ml',
       2000, 3000, 80, 'botol', '2027-04-30',
       'Air mineral botol'
FROM categories c WHERE c.name = 'Minuman';

INSERT IGNORE INTO products
    (category_id, sku, name, purchase_price, selling_price,
     stock, unit, expiry_date, description)
SELECT c.id, 'SNK-001', 'Keripik Kentang',
       7500, 10000, 25, 'pcs', '2027-02-28',
       'Snack kemasan'
FROM categories c WHERE c.name = 'Snack';

INSERT IGNORE INTO products
    (category_id, sku, name, purchase_price, selling_price,
     stock, unit, expiry_date, description)
SELECT c.id, 'SMB-001', 'Minyak Goreng 1 Liter',
       16000, 18000, 15, 'pouch', NULL,
       'Minyak goreng kemasan'
FROM categories c WHERE c.name = 'Sembako';

INSERT IGNORE INTO products
    (category_id, sku, name, purchase_price, selling_price,
     stock, unit, expiry_date, description)
SELECT c.id, 'KBR-001', 'Deterjen 800 Gram',
       18000, 22000, 4, 'pcs', NULL,
       'Deterjen untuk mencuci pakaian'
FROM categories c WHERE c.name = 'Kebersihan';