-- Create database
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image VARCHAR(255) DEFAULT 'assets/images/placeholder.jpg',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    shipping_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample products
INSERT INTO products (name, description, price, stock_quantity, image, featured) VALUES
('Wireless Headphones', 'High-quality wireless headphones with noise cancellation and long battery life.', 99.99, 50, 'assets/images/headphones.jpg', TRUE),
('Smartphone', 'Latest model smartphone with advanced camera and fast processor.', 699.99, 25, 'assets/images/smartphone.jpg', TRUE),
('Laptop', 'Powerful laptop perfect for work and gaming with high-resolution display.', 1299.99, 15, 'assets/images/laptop.jpg', TRUE),
('Smart Watch', 'Feature-rich smartwatch with health monitoring and GPS tracking.', 249.99, 30, 'assets/images/smartwatch.jpg', FALSE),
('Bluetooth Speaker', 'Portable Bluetooth speaker with excellent sound quality and waterproof design.', 79.99, 40, 'assets/images/speaker.jpg', FALSE),
('Gaming Mouse', 'Precision gaming mouse with customizable buttons and RGB lighting.', 59.99, 60, 'assets/images/mouse.jpg', FALSE);

-- Insert sample admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@shopeasy.com');