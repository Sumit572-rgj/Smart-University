<?php
// Create root index.php for TinkerHost compatibility
$indexContent = <<<'PHP'
<?php
// Forward request to public directory seamlessly
require_once __DIR__ . '/public/index.php';
PHP;
file_put_contents('C:/xampp/htdocs/cit_ums/index.php', $indexContent);

// Update .htaccess for maximum compatibility
$htaccessContent = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Send all requests to public/index.php unless it's a real file in public
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
HTACCESS;
file_put_contents('C:/xampp/htdocs/cit_ums/.htaccess', $htaccessContent);

echo "TinkerHost 403 patches applied.\n";
