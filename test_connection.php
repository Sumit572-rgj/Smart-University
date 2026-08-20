<?php
// Standalone Database Connection Tester
echo "<h2>InfinityFree Database Connection Tester</h2>";

// You can edit these directly in the InfinityFree File Manager to test different passwords!
$host = 'sql302.infinityfree.com';
$db   = 'if0_42707228_cit_ums';
$user = 'if0_42707228';
$pass = 'PUT_YOUR_REAL_PASSWORD_HERE'; 

echo "<b>Attempting to connect with:</b><br>";
echo "Host: $host<br>User: $user<br>Database: $db<br>Password: [HIDDEN]<br><br>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<h3 style='color:green;'>✅ SUCCESS! The database is connected!</h3>";
    echo "If you see this, copy these EXACT credentials into your config/database.php file and your website will work.";
} catch (PDOException $e) {
    echo "<h3 style='color:red;'>❌ FAILED! Connection Rejected by InfinityFree.</h3>";
    echo "<b>Error Message:</b> " . $e->getMessage() . "<br><br>";
    echo "<b>How to fix:</b><br>";
    echo "1. Double check that you actually created a database named '$db' in the MySQL Databases panel.<br>";
    echo "2. Double check that you are using the correct vPanel password.<br>";
    echo "3. Try changing the host to 'localhost'.";
}
