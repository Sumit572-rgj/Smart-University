<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
try { $db->exec('ALTER TABLE fees ADD COLUMN discount_amount DECIMAL(10, 2) DEFAULT 0.00'); } catch(Exception $e) {}
try { $db->exec('ALTER TABLE fees ADD COLUMN fine_amount DECIMAL(10, 2) DEFAULT 0.00'); } catch(Exception $e) {}
echo 'Updated fees table.';
