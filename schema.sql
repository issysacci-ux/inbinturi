CREATE DATABASE IF NOT EXISTS boostcafe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE boostcafe_db;

CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    qty INT NOT NULL DEFAULT 0,
    min_qty INT NOT NULL DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO inventory (name, category, price, qty, min_qty) VALUES
('Arabica Beans 1kg', 'Coffee Beans', 850.00, 12, 5),
('Fresh Milk 1L', 'Milk & Cream', 110.00, 24, 10),
('Vanilla Syrup 750ml', 'Syrups', 320.00, 3, 5),
('Croissant', 'Pastries', 95.00, 40, 10),
('Paper Cups 12oz (50s)', 'Packaging', 180.00, 0, 5);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash, name, role)
SELECT 'admin', '$2y$10$hHnf5j9v67KWqoR2i4u9yO6a5ypYig9DRU7ew7RRbgnh0Oly2HTiK', 'Administrator', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

CREATE TABLE IF NOT EXISTS stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    change_qty INT NOT NULL,
    note VARCHAR(255) DEFAULT '',
    username VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_item (item_id),
    INDEX idx_created (created_at)
);
