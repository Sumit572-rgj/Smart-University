<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

// 1. Remove the button HTML manually using exact string replacement
$btnHtml = <<<HTML
            <div class="text-center mt-8">
                <button id="danger-btn" onclick="initiateChaos()">Do Not Click (Danger)</button>
            </div>
HTML;
$c = str_replace($btnHtml, '', $c);

// 2. Remove the JS manually
$jsPattern = '/<script>\s*function initiateChaos\(\).*?<\/script>/s';
$c = preg_replace($jsPattern, '', $c);

file_put_contents($f, $c);
echo "Cleaned up.";
