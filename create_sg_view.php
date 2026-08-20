<?php
mkdir('C:/xampp/htdocs/cit_ums/app/Views/security_guard');
$f = 'C:/xampp/htdocs/cit_ums/app/Views/security_guard/index.php';
$src = 'C:/xampp/htdocs/cit_ums/app/Views/warden/index.php';
$c = file_get_contents($src);

$c = str_replace('cit_ums/warden', 'cit_ums/securityguard', $c);
$c = str_replace('Warden Management', 'Security Guard Management', $c);
$c = str_replace('Add New Warden', 'Add New Security Guard', $c);
$c = str_replace('Warden List', 'Security Guard List', $c);
$c = str_replace('Add Warden', 'Add Security Guard', $c);
$c = str_replace('$warden[\'id\']', '$guard[\'id\']', $c);
$c = str_replace('$warden[\'username\']', '$guard[\'username\']', $c);
$c = str_replace('$warden[\'email\']', '$guard[\'email\']', $c);
$c = str_replace('$warden[\'created_at\']', '$guard[\'created_at\']', $c);
$c = str_replace('$wardens as $warden', '$guards as $guard', $c);
$c = str_replace('warden_id', 'guard_id', $c);
$c = str_replace('Delete this warden', 'Delete this security guard', $c);

file_put_contents($f, $c);
echo "Security guard view created.";
