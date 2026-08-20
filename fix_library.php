<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("CREATE TABLE IF NOT EXISTS library_books (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        author VARCHAR(255) NOT NULL,
        isbn VARCHAR(50),
        category VARCHAR(100),
        total_copies INT DEFAULT 1,
        available_copies INT DEFAULT 1,
        added_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS library_transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        book_id INT NOT NULL,
        user_id INT NOT NULL,
        issued_date DATE NOT NULL,
        due_date DATE NOT NULL,
        returned_date DATE DEFAULT NULL,
        fine_amount DECIMAL(10,2) DEFAULT 0.00,
        status ENUM('issued', 'returned', 'overdue') DEFAULT 'issued',
        FOREIGN KEY (book_id) REFERENCES library_books(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "Library tables created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
