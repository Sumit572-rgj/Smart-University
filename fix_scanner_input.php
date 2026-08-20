<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/gate/index.php';
$c = file_get_contents($f);

// Change type="number" to type="text"
$oldInput = '<input type="number" id="outpass_input" name="outpass_id"';
$newInput = '<input type="text" id="outpass_input" name="outpass_id"';

$c = str_replace($oldInput, $newInput, $c);
file_put_contents($f, $c);
echo "Input type changed from number to text.";
