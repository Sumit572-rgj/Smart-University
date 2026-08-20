<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

$pattern = '/} else if \(\$user\[\'role\'\] === \'warden\'\) \{.*?\$data\[\'recent_activities\'\] = \[\'Review outpasses approved by Admin\.\'\];/s';

$replacement = <<<PHP
        } else if (\$user['role'] === 'warden') {
            \$outpassCount = \$db->query("SELECT COUNT(*) FROM outpass WHERE status = 'pending_warden'")->fetchColumn();
            
            \$maintDb = \$db->query("SHOW TABLES LIKE 'mod_roommaintenance'")->rowCount() > 0;
            \$maintCount = 0;
            if (\$maintDb) {
                \$maintCount = \$db->query("SELECT COUNT(*) FROM mod_roommaintenance WHERE status = 'Pending'")->fetchColumn();
            }
            
            \$data['stats'] = [
                ['label' => 'Pending Warden Outpasses', 'value' => \$outpassCount, 'link' => '/cit_ums/outpass'],
                ['label' => 'Pending Room Maintenance', 'value' => \$maintCount, 'link' => '/cit_ums/roommaintenance']
            ];
            \$data['recent_activities'] = [
                'Review outpasses approved by Admin.',
                'Track and resolve student room maintenance issues.'
            ];
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "Dashboard updated.";
