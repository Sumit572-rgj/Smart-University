<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

$c = str_replace('models/gemini-1.5-flash:generateContent', 'models/gemini-flash-latest:generateContent', $c);

file_put_contents($f, $c);
echo "Fixed Gemini Model Name.\n";
