<?php
// 1. Remove chatbot from dashboard/index.php and extract it
$dashboardFile = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$dashContent = file_get_contents($dashboardFile);

$startMarker = '<!-- AI Helpdesk Chatbot -->';
$endMarker = '</script>'; // The last script tag of the chatbot, wait, there are multiple.
// Let's use regex to grab from <!-- AI Helpdesk Chatbot --> up to the last </script> before <script src="/cit_ums/js/sidebar.js
$pattern = '/<!-- AI Helpdesk Chatbot -->[\s\S]*?(?=<script src="\/cit_ums\/js\/sidebar\.js)/';
preg_match($pattern, $dashContent, $matches);

if (!empty($matches[0])) {
    $chatbotUI = $matches[0];
    
    // Create partials dir and save
    if (!is_dir('C:/xampp/htdocs/cit_ums/app/Views/partials')) {
        mkdir('C:/xampp/htdocs/cit_ums/app/Views/partials');
    }
    file_put_contents('C:/xampp/htdocs/cit_ums/app/Views/partials/chatbot.php', $chatbotUI);
    
    // Remove from dashboard
    $dashContent = str_replace($chatbotUI, '', $dashContent);
    file_put_contents($dashboardFile, $dashContent);
}

// 2. Modify Controller.php to globally inject chatbot.php
$controllerFile = 'C:/xampp/htdocs/cit_ums/app/Core/Controller.php';
$ctrlContent = file_get_contents($controllerFile);
$newRender = <<<'PHP'
        if (file_exists($viewFile)) {
            require_once $viewFile;
            
            // Globally inject chatbot on all pages except receipt
            if ($view !== 'fee/receipt') {
                $chatbotFile = "../app/Views/partials/chatbot.php";
                if (file_exists($chatbotFile)) {
                    require_once $chatbotFile;
                }
            }
        }
PHP;
$ctrlContent = preg_replace('/if \(file_exists\(\$viewFile\)\) \{\s*require_once \$viewFile;\s*\}/', $newRender, $ctrlContent);
file_put_contents($controllerFile, $ctrlContent);

// 3. Modify ChatbotController.php for Guest access & Full Wikipedia
$chatbotCtrlFile = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$cbContent = file_get_contents($chatbotCtrlFile);

// Allow guests
$cbContent = str_replace(
    "if (!\$user) {\n            echo json_encode(['reply' => 'Please log in to use the assistant.']);\n            exit;\n        }",
    "if (!\$user) {\n            \$user = ['role' => 'guest', 'username' => 'Guest'];\n        }",
    $cbContent
);

// Replace the ChatGPT block with the new Wikipedia full block
$gptPattern = '/\/\/ ChatGPT API Integration[\s\S]*?\/\/ Absolute Default/m';

$newWikiLogic = <<<'PHP'
        // External Knowledge Base Fallback (Wikipedia API - Full Extract)
        $query = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $msg));
        
        $searchUrl = "https://en.wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($query) . "&limit=1&format=json";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: CIT_UMS_Bot/1.0 (admin@citums.com)']]);
        $searchRes = @file_get_contents($searchUrl, false, $context);
        
        if ($searchRes) {
            $searchData = json_decode($searchRes, true);
            if (!empty($searchData[1][0])) {
                $title = $searchData[1][0];
                
                // Fetch FULL extract (removed exsentences and exlimit)
                $extractUrl = "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&explaintext=1&titles=" . urlencode($title);
                $extractRes = @file_get_contents($extractUrl, false, $context);
                
                if ($extractRes) {
                    $extractData = json_decode($extractRes, true);
                    if (isset($extractData['query']['pages'])) {
                        $pages = $extractData['query']['pages'];
                        $firstPage = reset($pages);
                        if (!empty($firstPage['extract'])) {
                            // Split into paragraphs for better readability and return the full text
                            $fullText = nl2br(htmlspecialchars(trim($firstPage['extract'])));
                            return "<div style='color:#334155; line-height:1.6; max-height: 300px; overflow-y: auto; padding-right: 5px; font-size: 0.85rem;'>" . $fullText . "</div><br><small style='color:#94a3b8;'><i>Source: Wikipedia</i></small>";
                        }
                    }
                }
            }
        }

        // Absolute Default
PHP;

$cbContent = preg_replace($gptPattern, $newWikiLogic, $cbContent);
file_put_contents($chatbotCtrlFile, $cbContent);

echo "Global Chatbot & Full Wikipedia installed.\n";
