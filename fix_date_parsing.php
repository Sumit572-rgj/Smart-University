<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ExamController.php';
$c = file_get_contents($f);

$oldLogic = <<<'PHP'
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reg_no = $_POST['register_number'] ?? '';
            $dob = $_POST['dob'] ?? ''; 
            
            // Attempt to parse Date of Birth (handles both YYYY-MM-DD from HTML date picker and DD-MM-YYYY)
            $dob_sql = date('Y-m-d', strtotime($dob));
PHP;

$newLogic = <<<'PHP'
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reg_no = trim($_POST['register_number'] ?? '');
            $dob = trim($_POST['dob'] ?? ''); 
            
            // Clean up common typo characters like colons or slashes
            $dob = str_replace([':', '/'], '-', $dob);
            
            // Attempt to parse Date of Birth (handles YYYY-MM-DD, DD-MM-YYYY, etc.)
            $time = strtotime($dob);
            if (!$time) {
                // Fallback if parsing fails completely
                $dob_sql = '1970-01-01';
            } else {
                $dob_sql = date('Y-m-d', $time);
            }
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Results controller patched with robust date parsing.\n";
