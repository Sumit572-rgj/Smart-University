<?php
$jsFile = 'C:/xampp/htdocs/cit_ums/public/js/sidebar.js';
$c = file_get_contents($jsFile);

// Replace the event listener with an immediate execution that checks readyState
$oldJs = <<<JS
document.addEventListener("DOMContentLoaded", function() {
    if (!document.querySelector('.blur-orb')) {
        let orbsHtml = '<div class="blur-orb orb-1"></div><div class="blur-orb orb-2"></div><div class="blur-orb orb-3"></div>';
        document.body.insertAdjacentHTML('afterbegin', orbsHtml);
    }
});
JS;

$newJs = <<<JS
function injectOrbs() {
    if (!document.querySelector('.blur-orb')) {
        let orbsHtml = '<div class="blur-orb orb-1"></div><div class="blur-orb orb-2"></div><div class="blur-orb orb-3"></div>';
        document.body.insertAdjacentHTML('afterbegin', orbsHtml);
    }
}
if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", injectOrbs);
} else {
    injectOrbs();
}
JS;

$c = str_replace($oldJs, $newJs, $c);
file_put_contents($jsFile, $c);
echo "Fixed JS orb injection.";
