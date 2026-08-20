<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);

// The exact duplicated string I added
$dup = <<<PHP
    public function getStudentByUserId(\$user_id) {
        \$stmt = \$this->db->prepare("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
        \$stmt->execute([\$user_id]);
        return \$stmt->fetch();
    }
PHP;

// Find first occurrence
$pos = strpos($c, $dup);
if ($pos !== false) {
    // Replace the first occurrence with empty string (it was originally right before deleteStudent, but since I injected it above deleteStudent, the injected one comes right after updateStudent, and the original one might be somewhere else. Wait, let's just delete the exact block I injected. Wait, my injection block was exactly adjacent. Let's just remove one instance of it).
    
    // Safer way: just preg_replace with limit 1
    $c = preg_replace('/' . preg_quote($dup, '/') . '/', '', $c, 1);
    file_put_contents($f, $c);
    echo "Duplicate getStudentByUserId removed.";
} else {
    echo "Duplicate not found exactly as expected.";
}
