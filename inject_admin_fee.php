<?php
$dir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views/');
$iterator = new RecursiveIteratorIterator($dir);

$injection = <<<HTML
                  <a href="/cit_ums/fee" class="nav-link"><span style="margin-right:8px; font-size:1.1rem;">💸</span>Finance & Fees</a>
                  <a href="/cit_ums/library" class="nav-link">
HTML;

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        // Prevent double injection
        if (strpos($content, '<a href="/cit_ums/fee" class="nav-link">') === false) {
            $newContent = str_replace('<a href="/cit_ums/library" class="nav-link">', $injection, $content);
            if ($newContent !== null && $newContent !== $content) {
                file_put_contents($path, $newContent);
                $count++;
            }
        }
    }
}
echo "Injected Admin Fee link into $count view files.\n";
