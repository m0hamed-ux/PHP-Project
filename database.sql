CREATE DATABASE IF NOT EXISTS forsa_store;
USE forsa_store;

-- les stores et les produits :
CREATE TABLE IF NOT EXISTS stores (
  id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  name varchar(100) NOT NULL,
  description text,
  logo_url varchar(1000) DEFAULT 'https://cdn.iconscout.com/icon/free/png-256/free-shopify-logo-icon-download-in-svg-png-gif-file-formats--online-shopping-brand-logos-pack-icons-226579.png?f=webp&w=256',
  domain varchar(100) DEFAULT NULL,
  status enum('active','inactive','suspended') DEFAULT 'inactive',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  visits decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY domain (domain),
  KEY idx_store_user (user_id),
  KEY idx_store_domain (domain),
  CONSTRAINT stores_user_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS store_infos (
  id int NOT NULL AUTO_INCREMENT,
  store_id int NOT NULL,
  address text,
  phone varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  heading_text varchar(100) DEFAULT 'Welcome to our store',
  sub_heading_text varchar(100) DEFAULT 'Discover our amazing products',
  theme enum('theme-1','theme-2','theme-3','theme-4') DEFAULT 'theme-1',
  banner varchar(1000) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY store_id (store_id),
  CONSTRAINT store_infos_ibfk_1 FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
  id int NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  description text,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS products (
  id int NOT NULL AUTO_INCREMENT,
  store_id int NOT NULL,
  title varchar(100) NOT NULL,
  description text,
  price decimal(10,2) NOT NULL,
  stock int NOT NULL DEFAULT '0',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  category_id int NOT NULL,
  PRIMARY KEY (id),
  KEY store_id (store_id),
  KEY category_id (category_id),
  CONSTRAINT products_ibfk_1 FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE,
  CONSTRAINT products_ibfk_2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
);

-- les comptes :
CREATE TABLE IF NOT EXISTS users (
  id int NOT NULL AUTO_INCREMENT,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  password varchar(255) NOT NULL,
  session_ID varchar(100) NOT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY idx_email (email)
);

CREATE TABLE IF NOT EXISTS customers (
  id int NOT NULL AUTO_INCREMENT,
  store_id int NOT NULL,
  first_name varchar(50) NOT NULL,
  last_name varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  phone varchar(20),
  address text,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY store_id (store_id),
  CONSTRAINT customers_ibfk_1 FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
  id int NOT NULL AUTO_INCREMENT,
  store_id int NOT NULL,
  customer_id int NOT NULL,
  total_amount decimal(10,2) NOT NULL,
  status enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY store_id (store_id),
  KEY customer_id (customer_id),
  CONSTRAINT orders_ibfk_1 FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE,
  CONSTRAINT orders_ibfk_2 FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
  id int NOT NULL AUTO_INCREMENT,
  order_id int NOT NULL,
  product_id int NOT NULL,
  quantity int NOT NULL,
  price decimal(10,2) NOT NULL,
  PRIMARY KEY (id),
  KEY order_id (order_id),
  KEY product_id (product_id),
  CONSTRAINT order_items_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
  CONSTRAINT order_items_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS payments (
  id int NOT NULL AUTO_INCREMENT,
  order_id int NOT NULL,
  amount decimal(10,2) NOT NULL,
  payment_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  payment_method varchar(100) NOT NULL,
  status enum('pending','completed','failed','refunded') DEFAULT 'pending',
  PRIMARY KEY (id),
  KEY order_id (order_id),
  CONSTRAINT payments_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notifications (
  id int NOT NULL AUTO_INCREMENT,
  user_id int NOT NULL,
  title varchar(255) NOT NULL,
  message text NOT NULL,
  type enum('order','promotion','system','price_alert') NOT NULL,
  is_read boolean DEFAULT FALSE,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  CONSTRAINT notifications_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);