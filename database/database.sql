-- Create database
CREATE DATABASE property_rental;

-- Use the database
USE property_rental;

-- Create table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'landlord', 'warden', 'admin') NOT NULL
);

-- Create table for properties
CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    landlord_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    address VARCHAR(255) NOT NULL,
    rent DECIMAL(10, 2) NOT NULL,
    is_approved BOOLEAN NOT NULL DEFAULT 0,
    latitude DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL,
    FOREIGN KEY (landlord_id) REFERENCES users(id)
);

-- Create table for requests
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    property_id INT NOT NULL,
    is_approved INT NOT NULL,
    request_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

-- Alter table properties to upload images
ALTER TABLE properties ADD COLUMN image_path VARCHAR(255);
