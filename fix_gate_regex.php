<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

$oldCode = <<<PHP
            // Allow scanning QR which might be the ID directly or the formatted string
            \$scanned_id = trim(\$outpass_id);
            if (preg_match('/^OUTPASS_ID:(\d+)_VERIFIED\$/', \$scanned_id, \$matches)) {
                \$scanned_id = \$matches[1];
            }
            
            if (is_numeric(\$scanned_id)) {
PHP;

$newCode = <<<PHP
            // Allow scanning QR which might be the ID directly, a URL encoded string, or a formatted string
            \$scanned_id = trim(urldecode(\$outpass_id));
            
            // Flexible regex to catch OUTPASS_ID:1_VERIFIED or OUTPASS_ID%3A1_VERIFIED or any variation
            if (preg_match('/OUTPASS_ID.*?(\d+)_VERIFIED/i', \$scanned_id, \$matches)) {
                \$scanned_id = \$matches[1];
            } else if (preg_match('/(\d+)/', \$scanned_id, \$matches)) {
                // Fallback: if it's just a number or a weird string with a number, grab the first number
                \$scanned_id = \$matches[1];
            }
            
            if (is_numeric(\$scanned_id)) {
PHP;

$c = str_replace($oldCode, $newCode, $c);
file_put_contents($f, $c);
echo "GateController regex made fully flexible.";
