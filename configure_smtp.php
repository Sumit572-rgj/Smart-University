<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Core/Mail.php';
$c = file_get_contents($f);

$c = str_replace("'demo.cit.ums@gmail.com'", "'sumitchaurasiya98450@gmail.com'", $c);
$c = str_replace("'dummy_app_password'", "'btyl kglt wack cmga'", $c);
$c = str_replace("'noreply@cit.edu.in'", "'sumitchaurasiya98450@gmail.com'", $c);

file_put_contents($f, $c);
echo "SMTP configured.\n";
