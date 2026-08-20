<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

// 1. Remove the button HTML
$btnHtml = <<<HTML
            <div class="text-center mt-8">
                <button id="danger-btn" onclick="initiateChaos()">Do Not Click (Danger)</button>
            </div>
HTML;
$c = str_replace($btnHtml, '', $c);

// 2. Remove the CSS
// To avoid strict string matching issues, I'll use preg_replace to remove the entire CSS block related to chaos.
$c = preg_replace('/\/\* The chaos button \*\/.*?@keyframes colorChaos \{.*?\}/s', '', $c);

// 3. Remove the JS
$c = preg_replace('/function initiateChaos\(\).*?\}\s*\}\s*\}/s', '', $c);

// Clean up any remaining script tags that might be empty or just contain the script tag now
$c = preg_replace('/<script>\s*<\/script>/', '', $c);

file_put_contents($f, $c);
echo "Chaos button completely removed!";
