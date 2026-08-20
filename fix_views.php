<?php
// Fix Admin view
$f = 'C:/xampp/htdocs/cit_ums/app/Views/hostel/admin.php';
$c = file_get_contents($f);
$c = str_replace("\$m['description']", "\$m['issue_description']", $c);
$c = str_replace("\$m['issue_type']", "\$m['issue_type'] ?? 'General'", $c); // Because issue_type doesn't exist in DB either!
file_put_contents($f, $c);

// Fix Student view
$f2 = 'C:/xampp/htdocs/cit_ums/app/Views/hostel/student.php';
$c2 = file_get_contents($f2);
$c2 = str_replace("\$m['description']", "\$m['issue_description']", $c2);
$c2 = str_replace("\$m['reported_date']", "\$m['created_at']", $c2);
$c2 = str_replace("\$m['resolved_date']", "\$m['updated_at'] ?? null", $c2); // resolved_date doesn't exist
file_put_contents($f2, $c2);

echo "Views fixed.\n";
