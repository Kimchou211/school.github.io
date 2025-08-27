<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS teach_computer_school");
    $pdo->exec("USE teach_computer_school");
    
    // Create tables
    $pdo->exec("
        CREATE TABLE students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(100) NOT NULL,
            dob DATE NOT NULL,
            phone VARCHAR(20) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            address TEXT NOT NULL,
            course VARCHAR(50) NOT NULL,
            message TEXT,
            registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'
        )
    ");
    
    $pdo->exec("
        CREATE TABLE courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_code VARCHAR(10) NOT NULL UNIQUE,
            course_name VARCHAR(100) NOT NULL,
            description TEXT,
            duration VARCHAR(50),
            price DECIMAL(10, 2),
            is_active BOOLEAN DEFAULT TRUE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            fullname VARCHAR(100) NOT NULL,
            role ENUM('admin', 'instructor', 'student') DEFAULT 'student',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Insert sample data
    $pdo->exec("
        INSERT INTO courses (course_code, course_name, description, duration, price) VALUES
        ('WORD', 'Microsoft Word', 'Learn how to use Microsoft Word for document creation and formatting', '4 weeks', 50.00),
        ('EXCEL', 'Microsoft Excel', 'Learn how to use Microsoft Excel for data analysis and calculations', '6 weeks', 75.00),
        ('POWER', 'Microsoft PowerPoint', 'Learn how to create professional presentations with Microsoft PowerPoint', '4 weeks', 60.00)
    ");
    
    // Insert admin user (password: admin123)
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (username, password, email, fullname, role) VALUES
        ('admin', '$hashedPassword', 'admin@teachcomputerschool.com', 'System Administrator', 'admin')
    ");
    
    echo "Database setup completed successfully!";
    
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>