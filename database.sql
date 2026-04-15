-- database.sql
--
-- SQL schema and sample data for the PHP e‑commerce site.  Import this
-- file into your MySQL or MariaDB database before running the application.
-- It creates the necessary tables in the correct order to avoid foreign‑key
-- constraint errors and inserts sample categories, brands, products, coupons,
-- rewards and birthday gifts.

SET NAMES utf8mb4;
SET time_zone = '+09:00';

-- Drop tables in reverse order of dependencies
DROP TABLE IF EXISTS coupon_redemptions;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS birthday_gifts;
DROP TABLE IF EXISTS user_rewards;
DROP TABLE IF EXISTS rewards;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS brands;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  birthday DATE NOT NULL,
  loyalty_points INT NOT NULL DEFAULT 0,
  last_gift_claim_year INT DEFAULT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create categories and brands tables
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE brands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create products table with foreign keys to categories and brands
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  description TEXT,
  price       DECIMAL(10,2) NOT NULL,
  category_id INT NOT NULL,
  brand_id    INT NOT NULL,
  image_path  VARCHAR(255) DEFAULT NULL,
  stock       INT NOT NULL DEFAULT 0,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (brand_id)    REFERENCES brands(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create coupons table
CREATE TABLE coupons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code          VARCHAR(50) NOT NULL UNIQUE,
  discount_type ENUM('percent','amount') NOT NULL,
  value         DECIMAL(10,2) NOT NULL,
  expiry_date   DATE NOT NULL,
  min_purchase  DECIMAL(10,2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create rewards table
CREATE TABLE rewards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(255) NOT NULL,
  description     TEXT,
  points_required INT NOT NULL,
  type            ENUM('coupon','product') NOT NULL,
  reward_value    VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table before coupon_redemptions; includes status and shipping address
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id         INT NOT NULL,
  total_price     DECIMAL(10,2) NOT NULL,
  discount_amount DECIMAL(10,2) NOT NULL,
  final_price     DECIMAL(10,2) NOT NULL,
  status          ENUM('received','shipped','cancelled') NOT NULL DEFAULT 'received',
  shipping_address TEXT NOT NULL,
  coupon_id       INT DEFAULT NULL,
  gift_product_id INT DEFAULT NULL,
  created_at      DATETIME NOT NULL,
  FOREIGN KEY (user_id)         REFERENCES users(id),
  FOREIGN KEY (coupon_id)       REFERENCES coupons(id),
  FOREIGN KEY (gift_product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create order_items table
CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id   INT NOT NULL,
  product_id INT NOT NULL,
  quantity   INT NOT NULL,
  price_each DECIMAL(10,2) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id)   REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create coupon_redemptions table after orders
CREATE TABLE coupon_redemptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  coupon_id INT NOT NULL,
  user_id   INT NOT NULL,
  order_id  INT NOT NULL,
  redeemed_at DATETIME NOT NULL,
  FOREIGN KEY (coupon_id) REFERENCES coupons(id),
  FOREIGN KEY (user_id)   REFERENCES users(id),
  FOREIGN KEY (order_id)  REFERENCES orders(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create user_rewards table
CREATE TABLE user_rewards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id   INT NOT NULL,
  reward_id INT NOT NULL,
  claimed_at DATETIME NOT NULL,
  FOREIGN KEY (user_id)   REFERENCES users(id),
  FOREIGN KEY (reward_id) REFERENCES rewards(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create birthday_gifts table
CREATE TABLE birthday_gifts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data: categories
INSERT INTO categories (name) VALUES
('Electronics'),
('Fashion'),
('Home & Kitchen'),
('Sports'),
('Toys');

-- Sample data: brands
INSERT INTO brands (name) VALUES
('Acme'),
('Globex'),
('Umbrella'),
('Soylent'),
('Initech');

-- Sample data: products
INSERT INTO products (name, description, price, category_id, brand_id, image_path, stock) VALUES
('Wireless Headphones','High quality and durable.',75000,1,1,'assets/images/product1.png',50),
('Running Shoes','A must-have for any enthusiast.',50000,2,2,'assets/images/product2.png',40),
('Blender','Sleek design with top performance.',60000,3,3,'assets/images/product3.png',30),
('Yoga Mat','Made from premium materials.',20000,4,4,'assets/images/product4.png',60),
('Building Blocks Set','Kids will love this.',15000,5,5,'assets/images/product5.png',80),
('Smartphone','Best in class.',90000,1,2,'assets/images/product1.png',20),
('Designer Handbag','Limited edition item.',120000,2,3,'assets/images/product2.png',15),
('Coffee Maker','An essential household item.',55000,3,4,'assets/images/product3.png',25),
('Basketball','Perfect for gift giving.',18000,4,5,'assets/images/product4.png',70),
('Doll House','Upgraded version with new features.',40000,5,1,'assets/images/product5.png',50),
('Tablet','Sleek design with top performance.',80000,1,3,'assets/images/product1.png',25),
('Jeans','High quality and durable.',35000,2,4,'assets/images/product2.png',35),
('Cookware Set','Made from premium materials.',65000,3,5,'assets/images/product3.png',45),
('Football','Perfect for gift giving.',22000,4,1,'assets/images/product4.png',55),
('Action Figure','Kids will love this.',30000,5,2,'assets/images/product5.png',90),
('Laptop','Best in class.',150000,1,5,'assets/images/product1.png',10),
('Sneakers','Limited edition item.',45000,2,1,'assets/images/product2.png',28),
('Microwave Oven','An essential household item.',70000,3,2,'assets/images/product3.png',18),
('Tennis Racket','A must-have for any enthusiast.',25000,4,3,'assets/images/product4.png',40),
('Stuffed Bear','Kids will love this.',20000,5,4,'assets/images/product5.png',75),
('Smartwatch','High quality and durable.',85000,1,4,'assets/images/product1.png',22),
('T-shirt','Made from premium materials.',20000,2,5,'assets/images/product2.png',60),
('Vacuum Cleaner','Sleek design with top performance.',95000,3,1,'assets/images/product3.png',12),
('Skipping Rope','Perfect for gift giving.',8000,4,2,'assets/images/product4.png',100),
('Puzzle','Kids will love this.',10000,5,3,'assets/images/product5.png',110),
('Camera','Best in class.',110000,1,5,'assets/images/product1.png',14),
('Jacket','Limited edition item.',55000,2,2,'assets/images/product2.png',33),
('Air Fryer','An essential household item.',80000,3,3,'assets/images/product3.png',20),
('Golf Clubs','A must-have for any enthusiast.',120000,4,4,'assets/images/product4.png',5),
('Train Set','Kids will love this.',35000,5,5,'assets/images/product5.png',60);

-- Sample data: coupons
INSERT INTO coupons (code, discount_type, value, expiry_date, min_purchase) VALUES
('WELCOME10','percent',10,'2025-12-31',0),
('FASHION5','amount',5000,'2026-03-31',20000),
('BDAY20','percent',20,'2027-12-31',50000);

-- Sample data: rewards
INSERT INTO rewards (name, description, points_required, type, reward_value) VALUES
('50% off your next purchase','Get half off a single future order.',500,'coupon','HALFOFF'),
('Free Yoga Mat','Redeem this reward to receive a free yoga mat.',300,'product','4'),
('Ks 10,000 voucher','Take Ks 10,000 off your next order.',200,'coupon','TENKOFF');

-- Sample data: birthday gifts (product IDs)
INSERT INTO birthday_gifts (product_id) VALUES (4),(9),(14);