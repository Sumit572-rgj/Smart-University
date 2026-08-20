<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelController.php';
$c = file_get_contents($f);

$pattern = '/\$stmt->execute\(\[\$student_id\]\);\s*\$data\[\'allocation\'\] = \$stmt->fetch\(\);/s';
$replacement = <<<PHP
            \$stmt->execute([\$student_id]);
            \$data['allocation'] = \$stmt->fetch();
            
            // Fetch maintenance records for the student
            \$mStmt = \$db->prepare("SELECT * FROM hostel_maintenance WHERE reported_by = ? ORDER BY created_at DESC");
            \$mStmt->execute([\$user['id']]);
            \$data['maintenance'] = \$mStmt->fetchAll();
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "HostelController student fetch added.\n";
