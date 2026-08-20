<?php
require_once 'C:/xampp/htdocs/cit_ums/config/database.php';
require_once 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';

try {
    $db = (new Model())->db;
    
    // 1. Add 'dob' to students if it doesn't exist
    $checkDob = $db->query("SHOW COLUMNS FROM students LIKE 'dob'");
    if ($checkDob->rowCount() == 0) {
        $db->exec("ALTER TABLE students ADD COLUMN dob DATE DEFAULT '2000-01-01'");
        echo "Added dob to students.\n";
    }

    // 2. Create results table
    $db->exec("CREATE TABLE IF NOT EXISTS results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        exam_name VARCHAR(100) NOT NULL,
        subject VARCHAR(100) NOT NULL,
        marks INT NOT NULL,
        max_marks INT DEFAULT 100,
        grade VARCHAR(5) NOT NULL,
        gpa DECIMAL(3,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )");
    echo "Created results table.\n";
    
    // 3. Seed some dummy results for the first student if it's empty
    $resCount = $db->query("SELECT COUNT(*) FROM results")->fetchColumn();
    if ($resCount == 0) {
        $firstStudent = $db->query("SELECT id FROM students LIMIT 1")->fetchColumn();
        if ($firstStudent) {
            $db->exec("INSERT INTO results (student_id, exam_name, subject, marks, grade, gpa) VALUES 
                ($firstStudent, 'Semester 4 Finals', 'Data Structures', 85, 'A', 9.0),
                ($firstStudent, 'Semester 4 Finals', 'Operating Systems', 78, 'B+', 8.0),
                ($firstStudent, 'Semester 4 Finals', 'Computer Networks', 92, 'A+', 10.0),
                ($firstStudent, 'Semester 4 Finals', 'Database Systems', 88, 'A', 9.0)
            ");
            // Set DOB for this student to test
            $db->exec("UPDATE students SET dob = '2002-05-15' WHERE id = $firstStudent");
            echo "Seeded dummy results for student_id $firstStudent with DOB 2002-05-15.\n";
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
