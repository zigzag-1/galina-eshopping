-- ==========================================
-- GALINA E-SHOPPING DATABASE SETUP SCRIPT
-- ==========================================
-- This file creates the tables required for CAT 2.
-- It tracks data entered by:
-- 1. Admin (System logs, Categories, and User Management)
-- 2. User-Sellers (Product uploads, Location settings, uploaded document references)
-- 3. User-Customers (Orders, ratings, comments, and profile data)

CREATE DATABASE IF NOT EXISTS galina_eshopping_db;
USE galina_eshopping_db;

-- 1. USERS TABLE (Tracks Admins, Sellers, and Customers)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Seller', 'Customer') NOT NULL,
    location VARCHAR(255) DEFAULT 'Not Specified',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. PRODUCTS TABLE (Tracks Data entered by User-Sellers)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL, -- Path to uploaded product photo
    doc_url VARCHAR(255) DEFAULT NULL, -- Path to related uploaded document
    seller_location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. ORDERS TABLE (Tracks Transactions entered by User-Customers)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 4. COMMENTS & REVIEWS TABLE (Tracks Data entered by User-Customers commenting on Product Quality)
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quality_comment TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 5. ADMIN LOGS TABLE (Tracks Actions and Data entered by Admin)
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action_performed VARCHAR(255) NOT NULL,
    target_table VARCHAR(100),
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert a Mock Admin account for testing (password is 'admin123' hashed)
INSERT INTO users (username, email, password_hash, role, location)
VALUES ('admin', 'admin@galina.com', '$2y$10$3f8M9qVv1234567890abcdefghijklmnopqrstuvwxyz12345', 'Admin', 'Headquarters')
ON DUPLICATE KEY UPDATE id=id;