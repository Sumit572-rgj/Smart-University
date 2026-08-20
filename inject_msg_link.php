<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        // Remove existing Internal Messaging links to avoid duplicates
        $content = preg_replace('/<a href="\/cit_ums\/message".*?<\/a>/is', '', $content);
        
        // Insert Internal Messaging right after Dashboard link
        $replacement = '<a href="/cit_ums/dashboard" class="nav-link">Dashboard</a>' . "\n" . '            <a href="/cit_ums/message" class="nav-link">Messages & Forums</a>';
        
        $content = preg_replace('/<a href="\/cit_ums\/dashboard" class="nav-link[^>]*>Dashboard<\/a>/i', $replacement, $content);
        
        file_put_contents($file->getRealPath(), $content);
    }
}
echo "Done injecting messaging link globally.";
