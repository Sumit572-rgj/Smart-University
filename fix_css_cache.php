<?php
$viewsDir = 'C:/xampp/htdocs/cit_ums/app/Views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$count = 0;
foreach ($phpFiles as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace hardcoded version strings with a PHP timestamp
    $newContent = preg_replace('/style\.css\?v=\d+/', 'style.css?v=<?= time() ?>', $content);
    
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        $count++;
    }
}

echo "Updated cache-busting timestamp in $count view files.";
