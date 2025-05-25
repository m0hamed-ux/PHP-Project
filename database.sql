-- Create the database
CREATE DATABASE IF NOT EXISTS forsa_store;
USE forsa_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    logo_url VARCHAR(255),
    domain VARCHAR(100) UNIQUE,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS store_infos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT NOT NULL,
    address TEXT DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    heading_text VARCHAR(100) DEFAULT 'Welcome to our store',
    sub_heading_text VARCHAR(100) DEFAULT 'Discover our amazing products',
    theme ENUM('theme-1', 'theme-2', 'theme-3', 'theme-4') DEFAULT 'theme-1',
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE
);


