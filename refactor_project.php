<?php
// Script to completely refactor the project to be environment-agnostic (Local vs TinkerHost)

$dirIterator = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app');
$iterator = new RecursiveIteratorIterator($dirIterator);

$viewsModified = 0;
$controllersModified = 0;

// 1. Inject BASE_URL auto-detector into the core index.php
$indexFile = 'C:/xampp/htdocs/cit_ums/public/index.php';
$indexContent = file_get_contents($indexFile);
if (strpos($indexContent, 'define(\'BASE_URL\'') === false) {
    $autoDetect = <<<'PHP'
<?php
// Auto-detect environment to prevent broken links on TinkerHost vs Local XAMPP
$isLocal = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false || strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false);
define('BASE_URL', $isLocal ? '/cit_ums/' : '/');

PHP;
    $indexContent = preg_replace('/<\?php/', $autoDetect, $indexContent, 1);
    file_put_contents($indexFile, $indexContent);
}

// 2. Scan and replace all hardcoded '/cit_ums/' strings across the entire codebase
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $content = file_get_contents($path);
        $originalContent = $content;

        // If it's a View (HTML mix)
        if (strpos($path, 'Views') !== false) {
            $content = str_replace('="/cit_ums/', '="<?= BASE_URL ?>', $content);
            $content = str_replace("='/cit_ums/", "='<?= BASE_URL ?>", $content);
            $content = str_replace(' /cit_ums/', ' <?= BASE_URL ?>', $content);
            $content = str_replace('"/cit_ums/', '"<?= BASE_URL ?>', $content);
            if ($content !== $originalContent) $viewsModified++;
        }
        
        // If it's a Controller or Core file
        if (strpos($path, 'Controllers') !== false || strpos($path, 'Core') !== false) {
            $content = str_replace("'/cit_ums/", "BASE_URL . '", $content);
            $content = str_replace('"/cit_ums/', 'BASE_URL . "', $content);
            if ($content !== $originalContent) $controllersModified++;
        }
        
        file_put_contents($path, $content);
    }
}

// 3. Fix the base Controller redirect function
$controllerFile = 'C:/xampp/htdocs/cit_ums/app/Core/Controller.php';
$cContent = file_get_contents($controllerFile);
$cContent = str_replace("header('Location: /cit_ums' . \$url);", "header('Location: ' . rtrim(BASE_URL, '/') . \$url);", $cContent);
file_put_contents($controllerFile, $cContent);

// 4. Fix the Router parsing logic
$routerFile = 'C:/xampp/htdocs/cit_ums/app/Core/Router.php';
$rContent = file_get_contents($routerFile);
$rContent = str_replace("return explode('/', filter_var(rtrim(\$_GET['url'], '/'), FILTER_SANITIZE_URL));", 
<<<'PHP'
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            if (strpos($url, 'cit_ums/') === 0) {
                $url = substr($url, 8);
            }
            return explode('/', $url);
PHP
, $rContent);
file_put_contents($routerFile, $rContent);

echo "Project Structure Refactored! Views Modified: $viewsModified, Controllers Modified: $controllersModified\n";
