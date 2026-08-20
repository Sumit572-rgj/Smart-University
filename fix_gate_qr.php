<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

// Replace is_numeric check with extraction logic
$newLogic = <<<PHP
            // Allow scanning QR which might be the ID directly or the formatted string
            \$scanned_id = trim(\$outpass_id);
            if (preg_match('/^OUTPASS_ID:(\d+)_VERIFIED\$/', \$scanned_id, \$matches)) {
                \$scanned_id = \$matches[1];
            }
            
            if (is_numeric(\$scanned_id)) {
                \$outpass_id = (int)\$scanned_id;
                \$stmt = \$db->prepare("SELECT o.*, s.first_name, s.last_name, s.enrollment_no, s.department 
PHP;

$c = preg_replace('/\/\/ Allow scanning QR which might be the ID directly\s*if \(is_numeric\(\$outpass_id\)\) \{\s*\$stmt = \$db->prepare\("SELECT o\.\*, s\.first_name, s\.last_name, s\.enrollment_no, s\.department/', $newLogic, $c);

file_put_contents($f, $c);
echo "GateController fixed for QR code format.";
