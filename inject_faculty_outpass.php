<?php
$dir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views/');
$iterator = new RecursiveIteratorIterator($dir);

$injection = <<<HTML
            <?php if (\$user['role'] === 'faculty'): ?>
                <a href="/cit_ums/outpass" class="nav-link">Outpass Approvals</a>
            <?php endif; ?>
        </nav>
HTML;

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        // Prevent double injection
        if (strpos($content, '<a href="/cit_ums/outpass" class="nav-link">Outpass Approvals</a>') === false) {
            $newContent = preg_replace('/<\/nav>/i', $injection, $content, 1);
            if ($newContent !== null && $newContent !== $content) {
                file_put_contents($path, $newContent);
                $count++;
            }
        }
    }
}
echo "Injected Faculty Outpass into $count view files.\n";
