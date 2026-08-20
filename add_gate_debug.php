<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

// Change "Invalid Outpass ID format." to show exactly what was received.
$oldErr = "\$data['error'] = \"Invalid Outpass ID format.\";";
$newErr = "\$data['error'] = \"Invalid Outpass ID format. Scanned raw: [\" . htmlspecialchars(\$outpass_id) . \"] parsed as: [\" . htmlspecialchars(\$scanned_id) . \"]\";";

$c = str_replace($oldErr, $newErr, $c);
file_put_contents($f, $c);
echo "GateController debug error injected.";
