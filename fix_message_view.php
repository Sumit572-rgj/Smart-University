<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/message/index.php';
$c = file_get_contents($f);
$c = str_replace("['attachment']", "['attachment_path']", $c);
file_put_contents($f, $c);
echo "Fixed view.";
