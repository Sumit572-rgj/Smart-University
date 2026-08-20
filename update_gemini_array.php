<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

$old = "['gemini-2.5-flash', 'gemini-flash-latest', 'gemini-pro-latest', 'gemini-2.5-flash-lite']";
$new = "['gemini-3.7-flash', 'gemini-3.6-flash', 'gemini-3.5-flash', 'gemini-3.5-flash-lite', 'gemini-flash-latest']";

$c = str_replace($old, $new, $c);
file_put_contents($f, $c);
echo "Updated Models Array.\n";
