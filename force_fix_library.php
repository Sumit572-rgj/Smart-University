<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Library.php';
$c = file_get_contents($f);

// Reliable Regex replacement
$pattern = '/if\s*\(\$query\)\s*\{\s*\$sql\s*\.\=\s*"[^"]+";\s*\$stmt\s*=\s*\$this->db->prepare\(\$sql\);\s*\$stmt->execute\(\[\'query\'\s*=>\s*"%\$query%"\]\);\s*return\s*\$stmt->fetchAll\(\);\s*\}/s';

$replacement = <<<PHP
if (\$query) {
            \$sql .= " WHERE title LIKE ? OR author LIKE ? OR subject LIKE ? OR isbn LIKE ?";
            \$stmt = \$this->db->prepare(\$sql);
            \$val = "%\$query%";
            \$stmt->execute([\$val, \$val, \$val, \$val]);
            return \$stmt->fetchAll();
        }
PHP;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents($f, $c);
echo "Library query forcefully fixed.";
