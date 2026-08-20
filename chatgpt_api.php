<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

// We want to replace the Wikipedia fallback block with the ChatGPT API logic.
$wikipediaStart = '// External Knowledge Base Fallback (Wikipedia API)';
$wikipediaEnd = '// Absolute Default';

$pattern = '/\/\/ External Knowledge Base Fallback \(Wikipedia API\)[\s\S]*?\/\/ Absolute Default/m';

$chatgptLogic = <<<'PHP'
        // ChatGPT API Integration
        // Ensure you paste your actual OpenAI API Key below
        $openAiKey = "YOUR_OPENAI_API_KEY_HERE"; 
        
        if ($openAiKey !== "YOUR_OPENAI_API_KEY_HERE") {
            $postData = [
                "model" => "gpt-3.5-turbo",
                "messages" => [
                    [
                        "role" => "system", 
                        "content" => "You are a helpful, professional AI assistant for the CIT University Management System. You assist students, faculty, and wardens with their tasks. Keep your answers concise, formatted in HTML where appropriate, and friendly."
                    ],
                    [
                        "role" => "user", 
                        "content" => $msg
                    ]
                ],
                "temperature" => 0.7
            ];

            $ch = curl_init('https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $openAiKey
            ]);
            
            $apiResponse = curl_exec($ch);
            curl_close($ch);
            
            if ($apiResponse) {
                $data = json_decode($apiResponse, true);
                if (isset($data['choices'][0]['message']['content'])) {
                    $aiAnswer = $data['choices'][0]['message']['content'];
                    // Format response beautifully
                    return "<div style='color:#334155; line-height:1.6;'>" . nl2br($aiAnswer) . "</div><br><small style='color:#94a3b8;'><i>Powered by ChatGPT</i></small>";
                }
            }
        }

        // Absolute Default
PHP;

$c = preg_replace($pattern, $chatgptLogic, $c);

file_put_contents($f, $c);
echo "ChatGPT API integrated.\n";
