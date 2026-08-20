<?php
$dir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views/');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        
        $newContent = str_replace(
            "<?php if (\$user['role'] === 'faculty'): ?>",
            "<?php if (isset(\$user['role']) && \$user['role'] === 'faculty'): ?>",
            $content
        );
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
        }
    }
}
echo "Safeguarded user role checks globally.\n";
