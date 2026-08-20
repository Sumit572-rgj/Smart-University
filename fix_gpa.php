<?php
require_once 'C:/xampp/htdocs/cit_ums/config/database.php';
require_once 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';

try {
    $db = (new Model())->db;
    $db->exec("ALTER TABLE results MODIFY COLUMN gpa DECIMAL(4,2)");
    $db->exec("DELETE FROM results"); // clear any partial seeds
    $firstStudent = $db->query("SELECT id FROM students LIMIT 1")->fetchColumn();
    if ($firstStudent) {
        $db->exec("INSERT INTO results (student_id, exam_name, subject, marks, grade, gpa) VALUES 
            ($firstStudent, 'Semester 4 Finals', 'Data Structures', 85, 'A', 9.0),
            ($firstStudent, 'Semester 4 Finals', 'Operating Systems', 78, 'B+', 8.0),
            ($firstStudent, 'Semester 4 Finals', 'Computer Networks', 92, 'A+', 10.0),
            ($firstStudent, 'Semester 4 Finals', 'Database Systems', 88, 'A', 9.0)
        ");
        $db->exec("UPDATE students SET dob = '2002-05-15' WHERE id = $firstStudent");
        echo "Seeded successfully for student $firstStudent.\n";
    }
} catch (Exception $e) { echo $e->getMessage(); }
