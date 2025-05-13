-- Create database
CREATE DATABASE IF NOT EXISTS house_rental;
USE house_rental;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role ENUM('user', 'owner', 'admin') NOT NULL
);

-- Properties table (UPDATED to match PHP form)
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    owner_id INT,
    address VARCHAR(255),
    bathroom VARCHAR(10),
    bedroom INT,
    garage INT,
    rent DECIMAL(10,2),
    lease_duration INT,
    status ENUM('available', 'booked') DEFAULT 'available',
    FOREIGN KEY (owner_id) REFERENCES users(id)
);

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    property_id INT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

-- Insert default admin user (password in plain text: change this in production)
INSERT INTO users (name, email, phone, password, role)
VALUES ('Admin', 'admin@site.com', '0000000000', 'admin123', 'admin');
