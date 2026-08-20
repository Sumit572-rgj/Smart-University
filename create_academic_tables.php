<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    // 1. Courses Table
    $db->exec("CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_code VARCHAR(50) UNIQUE NOT NULL,
        course_title VARCHAR(255) NOT NULL,
        credits INT DEFAULT 3,
        syllabus_path VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Course Faculty Assignment
    $db->exec("CREATE TABLE IF NOT EXISTS course_faculty (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        faculty_id INT NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE,
        UNIQUE KEY(course_id, faculty_id)
    )");

    // 3. Course Student Enrollment
    $db->exec("CREATE TABLE IF NOT EXISTS course_students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        student_id INT NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        UNIQUE KEY(course_id, student_id)
    )");

    // 4. Timetables
    $db->exec("CREATE TABLE IF NOT EXISTS timetables (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        day_of_week VARCHAR(20) NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        room_number VARCHAR(50) NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )");

    // Modify Exams Table to link to course_id instead of string course_code
    // Add column if it doesn't exist
    $colCheck = $db->query("SHOW COLUMNS FROM exams LIKE 'course_id'");
    if ($colCheck->rowCount() == 0) {
        $db->exec("ALTER TABLE exams ADD COLUMN course_id INT NULL");
        $db->exec("ALTER TABLE exams ADD FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE");
    }

    echo "Academic Core tables created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
