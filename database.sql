CREATE DATABASE IF NOT EXISTS frank_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE frank_pos;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  username VARCHAR(60) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  phone VARCHAR(30),
  password VARCHAR(255) NOT NULL,
  role ENUM('Admin','Cashier') NOT NULL DEFAULT 'Cashier',
  reset_token VARCHAR(100) NULL,
  reset_expires DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE settings (
  id INT PRIMARY KEY DEFAULT 1,
  business_name VARCHAR(150) NOT NULL DEFAULT 'FRANK POS',
  phone VARCHAR(30) NULL,
  address VARCHAR(255) NULL,
  logo VARCHAR(255) NULL,
  low_stock_level INT NOT NULL DEFAULT 5,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  category_id INT NULL,
  buying_price DECIMAL(12,2) NOT NULL DEFAULT 0,
  selling_price DECIMAL(12,2) NOT NULL DEFAULT 0,
  quantity INT NOT NULL DEFAULT 0,
  date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(30),
  address VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  receipt_no VARCHAR(40) NOT NULL UNIQUE,
  customer_id INT NULL,
  user_id INT NOT NULL,
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  profit DECIMAL(12,2) NOT NULL DEFAULT 0,
  sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sale_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  buying_price DECIMAL(12,2) NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO settings (id, business_name, low_stock_level) VALUES (1, 'FRANK POS', 5)
ON DUPLICATE KEY UPDATE business_name = business_name;

-- Default admin: username admin, password admin123
INSERT INTO users (full_name, username, email, phone, password, role)
VALUES ('System Admin', 'admin', 'admin@frankpos.local', '0000000000', '$2y$10$oxEynJkQxrtG0Yd5qHKoL.nKowhS6vfo.0eNiUmcGx.dS9yCQKedO', 'Admin')
ON DUPLICATE KEY UPDATE username = username;
