-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    category_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Điện thoại', 'Các loại điện thoại di động'),
('Laptop', 'Máy tính xách tay'),
('Phụ kiện', 'Phụ kiện điện tử');

-- Insert sample products
INSERT INTO products (name, description, price, quantity, category_id) VALUES
('iPhone 13', 'Điện thoại iPhone 13 128GB', 24990000, 10, 1),
('Samsung Galaxy S21', 'Điện thoại Samsung Galaxy S21 5G', 19990000, 15, 1),
('MacBook Pro M1', 'Laptop Apple MacBook Pro M1 13 inch', 32990000, 5, 2),
('Dell XPS 13', 'Laptop Dell XPS 13 2021', 29990000, 8, 2),
('Tai nghe AirPods Pro', 'Tai nghe không dây Apple AirPods Pro', 5990000, 20, 3); 