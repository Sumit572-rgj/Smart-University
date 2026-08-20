<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$db->exec("ALTER TABLE users ADD COLUMN two_factor_enabled TINYINT(1) DEFAULT 0");
echo "Added two_factor_enabled to users table.\n";
