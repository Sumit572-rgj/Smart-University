<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

$pattern = "/} else {\\s+\\$data\\['stats'\\] = \\[\\];/";
$replacement = <<<PHP
        } else if (\$user['role'] === 'faculty') {
            \$outDb = \$db->query("SHOW TABLES LIKE 'outpass'")->rowCount() > 0;
            \$outpassCount = \$outDb ? \$db->query("SELECT COUNT(*) FROM outpass WHERE status = 'pending_faculty'")->fetchColumn() : 0;
            
            \$data['stats'] = [
                ['label' => 'Pending Faculty Outpasses', 'value' => \$outpassCount, 'link' => '/cit_ums/outpass']
            ];
            \$data['recent_activities'] = [
                'Review student outpass applications.',
                'Mark daily attendance for your classes.'
            ];
        } else {
             \$data['stats'] = [];
PHP;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents($f, $c);
echo "Added faculty stats to DashboardController.\n";
