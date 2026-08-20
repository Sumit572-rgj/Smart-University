<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

$oldCode = <<<'PHP'
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix XAMPP local SSL issues
        
        $response = curl_exec($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);
        
        if ($curl_err) {
            return "cURL Error: " . htmlspecialchars($curl_err);
        }
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['error'])) {
                return "Gemini API Error: " . htmlspecialchars($data['error']['message']);
            }
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $aiText = $data['candidates'][0]['content']['parts'][0]['text'];
                // Clean up any potential markdown code blocks Gemini might accidentally output
                $aiText = preg_replace('/```html/i', '', $aiText);
                $aiText = preg_replace('/```/', '', $aiText);
                return "<div style='color:#334155; line-height:1.6; font-size: 0.9rem;'>" . trim($aiText) . "</div><br><small style='color:#94a3b8;'><i>Powered by Gemini AI ✨</i></small>";
            }
        }
PHP;

$newCode = <<<'PHP'
        $modelsToTry = ['gemini-2.5-flash', 'gemini-flash-latest', 'gemini-pro-latest', 'gemini-2.5-flash-lite'];
        $lastError = '';

        foreach ($modelsToTry as $model) {
            $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $apiKey);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $curl_err = curl_error($ch);
            curl_close($ch);
            
            if ($curl_err) {
                $lastError = "cURL Error: " . htmlspecialchars($curl_err);
                continue;
            }
            
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['error'])) {
                    $lastError = "Gemini API Error ({$model}): " . htmlspecialchars($data['error']['message']);
                    continue; // Model overloaded or not found, try the next one in the array
                }
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiText = $data['candidates'][0]['content']['parts'][0]['text'];
                    $aiText = preg_replace('/```html/i', '', $aiText);
                    $aiText = preg_replace('/```/', '', $aiText);
                    return "<div style='color:#334155; line-height:1.6; font-size: 0.9rem;'>" . trim($aiText) . "</div><br><small style='color:#94a3b8;'><i>Powered by Gemini AI ✨ ({$model})</i></small>";
                }
            }
        }
        
        // If all models failed, return the last error
        return $lastError;
PHP;

$c = str_replace($oldCode, $newCode, $c);
file_put_contents($f, $c);
echo "Added Multi-Model Failover Logic.\n";
