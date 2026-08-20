<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

$oldDefault = "        // Default\n        return \"I'm still learning! 🎓<br><br>Try asking me about <strong>Fees</strong>, <strong>Outpasses</strong>, <strong>Events</strong>, or <strong>Security</strong>.\";";

$newDefault = <<<'PHP'
        // External Knowledge Base Fallback (Wikipedia API)
        // If the query isn't about the university, we query the live internet!
        $query = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $msg));
        
        $searchUrl = "https://en.wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($query) . "&limit=1&format=json";
        
        // Use stream context to avoid some server blocks
        $context = stream_context_create(['http' => ['header' => 'User-Agent: CIT_UMS_Bot/1.0 (admin@citums.com)']]);
        $searchRes = @file_get_contents($searchUrl, false, $context);
        
        if ($searchRes) {
            $searchData = json_decode($searchRes, true);
            if (!empty($searchData[1][0])) {
                $title = $searchData[1][0];
                $url = $searchData[3][0] ?? '#';
                
                $extractUrl = "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&exsentences=3&exlimit=1&explaintext=1&titles=" . urlencode($title);
                $extractRes = @file_get_contents($extractUrl, false, $context);
                
                if ($extractRes) {
                    $extractData = json_decode($extractRes, true);
                    if (isset($extractData['query']['pages'])) {
                        $pages = $extractData['query']['pages'];
                        $firstPage = reset($pages);
                        if (!empty($firstPage['extract'])) {
                            return "Here is what I found on the web: <br><br><i style='color:#475569; line-height:1.5; display:block; padding:10px; background:#f1f5f9; border-left:3px solid #f97316;'>" . htmlspecialchars($firstPage['extract']) . "</i><br><br><a href='" . $url . "' target='_blank' style='color:#f97316; font-weight:bold; text-decoration:underline;'>Read more</a>";
                        }
                    }
                }
            }
        }

        // Absolute Default
        return "I'm sorry, I don't have an answer for that yet! 🎓<br><br>I specialize in <strong>Fees</strong>, <strong>Outpasses</strong>, and <strong>Campus Events</strong>.";
PHP;

$c = str_replace($oldDefault, $newDefault, $c);

file_put_contents($f, $c);
echo "Chatbot upgraded to answer anything.\n";
