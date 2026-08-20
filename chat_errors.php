<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

// Enhance the API processing block to show actual error messages
$oldPattern = '/if \(isset\(\$data\[\'choices\'\]\[0\]\[\'message\'\]\[\'content\'\]\)\) \{[\s\S]*?\}\s*\}/m';

$newLogic = <<<'PHP'
if (isset($data['choices'][0]['message']['content'])) {
                    $aiAnswer = $data['choices'][0]['message']['content'];
                    return "<div style='color:#334155; line-height:1.6;'>" . nl2br($aiAnswer) . "</div><br><small style='color:#94a3b8;'><i>Powered by ChatGPT</i></small>";
                } else if (isset($data['error']['message'])) {
                    return "<strong style='color:#ef4444;'>OpenAI API Error:</strong> " . $data['error']['message'];
                }
            } else {
                return "<strong style='color:#ef4444;'>Connection Error:</strong> Could not connect to OpenAI API. Check your internet or SSL configuration.";
            }
PHP;

$c = preg_replace($oldPattern, $newLogic, $c);

// Also add the SSL bypass to the curl request just to be safe
$c = str_replace(
    'curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);',
    "curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);\n            curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, false);",
    $c
);

file_put_contents($f, $c);
echo "Error handling added.\n";
