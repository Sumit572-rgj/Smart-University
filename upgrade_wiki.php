<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ChatbotController.php';
$c = file_get_contents($f);

$oldLogic = <<<'PHP'
        // External Knowledge Base Fallback (Wikipedia API - Full Extract)
        $query = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $msg));
        
        $searchUrl = "https://en.wikipedia.org/w/api.php?action=opensearch&search=" . urlencode($query) . "&limit=1&format=json";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: CIT_UMS_Bot/1.0 (admin@citums.com)']]);
        $searchRes = @file_get_contents($searchUrl, false, $context);
        
        if ($searchRes) {
            $searchData = json_decode($searchRes, true);
            if (!empty($searchData[1][0])) {
                $title = $searchData[1][0];
PHP;

$newLogic = <<<'PHP'
        // External Knowledge Base Fallback (Wikipedia API - Full Extract)
        // Clean query to remove filler question words
        $query = preg_replace('/\b(what is|what are|who is|who are|tell me about|explain|define|a|an|the)\b/i', '', $msg);
        $query = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $query));
        
        // Use full text search instead of strict opensearch
        $searchUrl = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($query) . "&utf8=&format=json&srlimit=1";
        $context = stream_context_create(['http' => ['header' => 'User-Agent: CIT_UMS_Bot/1.0 (admin@citums.com)']]);
        $searchRes = @file_get_contents($searchUrl, false, $context);
        
        if ($searchRes) {
            $searchData = json_decode($searchRes, true);
            if (!empty($searchData['query']['search'][0]['title'])) {
                $title = $searchData['query']['search'][0]['title'];
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Wikipedia API upgraded to natural language search.\n";
