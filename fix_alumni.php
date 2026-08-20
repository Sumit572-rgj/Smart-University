<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("CREATE TABLE IF NOT EXISTS alumni_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        graduation_year INT,
        degree VARCHAR(100),
        company VARCHAR(100),
        job_title VARCHAR(100),
        linkedin_url VARCHAR(255),
        bio TEXT,
        mentor_opt_in TINYINT(1) DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS alumni_donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        alumni_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        purpose VARCHAR(100),
        donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (alumni_id) REFERENCES alumni_profiles(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS alumni_jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        alumni_id INT NOT NULL,
        company VARCHAR(100),
        position VARCHAR(100),
        description TEXT,
        link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (alumni_id) REFERENCES alumni_profiles(id) ON DELETE CASCADE
    )");
    
    echo "Alumni schemas fixed.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
