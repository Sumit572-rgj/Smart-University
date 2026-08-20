<?php
$msg = "what is black holes";

// Remove common question phrases
$query = preg_replace('/\b(what is|what are|who is|who are|tell me about|explain|define|a|an|the)\b/i', '', $msg);
$query = trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $query));

echo "Clean query: " . $query . "\n";

$searchUrl = "https://en.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . urlencode($query) . "&utf8=&format=json&srlimit=1";
$context = stream_context_create(['http' => ['header' => 'User-Agent: CIT_UMS_Bot/1.0 (admin@citums.com)']]);
$searchRes = @file_get_contents($searchUrl, false, $context);

if ($searchRes) {
    $searchData = json_decode($searchRes, true);
    if (!empty($searchData['query']['search'][0]['title'])) {
        $title = $searchData['query']['search'][0]['title'];
        echo "Found title: " . $title . "\n";
        
        $extractUrl = "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=extracts&explaintext=1&titles=" . urlencode($title);
        $extractRes = @file_get_contents($extractUrl, false, $context);
        
        if ($extractRes) {
            $extractData = json_decode($extractRes, true);
            $pages = $extractData['query']['pages'];
            $firstPage = reset($pages);
            if (!empty($firstPage['extract'])) {
                echo "Extract length: " . strlen($firstPage['extract']) . "\n";
                echo substr($firstPage['extract'], 0, 100) . "...\n";
            }
        }
    } else {
        echo "No search results found.\n";
    }
} else {
    echo "API request failed.\n";
}
