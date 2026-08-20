<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

// Inject cleanup query after setting up $db
$cleanup = <<<PHP
        \$db = (new Model())->db;
        
        // AUTO-ERASE logic: Erase outpasses that were scanned successfully (checked_out or checked_in) and 24 hours have passed since they were last updated
        try {
            \$db->exec("DELETE FROM outpass WHERE status IN ('checked_out', 'checked_in') AND updated_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        } catch(Exception \$e) {}
PHP;

$c = str_replace("\$db = (new Model())->db;", $cleanup, $c);
file_put_contents($f, $c);
echo "Added cleanup to GateController.\n";
