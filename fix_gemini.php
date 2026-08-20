<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

// Inject SSL Verify Peer false and error logging for debugging
$old = <<<'PHP'
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        $response = curl_exec($ch);
PHP;

$new = <<<'PHP'
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix XAMPP local SSL issues
        
        $response = curl_exec($ch);
        $curl_err = curl_error($ch);
PHP;

$c = str_replace($old, $new, $c);

// Inject error returning
$old2 = <<<'PHP'
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
PHP;

$new2 = <<<'PHP'
        if ($curl_err) {
            return "cURL Error: " . htmlspecialchars($curl_err);
        }
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['error'])) {
                return "Gemini API Error: " . htmlspecialchars($data['error']['message']);
            }
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
PHP;

$c = str_replace($old2, $new2, $c);

// Also add 'hy' and 'hii' to the greetings regex
$oldRegex = "/\\b(hi|hello|hey|greetings|morning|afternoon)\\b/";
$newRegex = "/\\b(hi|hello|hey|hy|hii|greetings|morning|afternoon)\\b/";
$c = str_replace($oldRegex, $newRegex, $c);

file_put_contents($f, $c);
echo "Debug and fixes applied.\n";
