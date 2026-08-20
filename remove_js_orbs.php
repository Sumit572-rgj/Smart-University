<?php
$jsFile = 'C:/xampp/htdocs/cit_ums/public/js/sidebar.js';
$c = file_get_contents($jsFile);

$c = preg_replace('/function injectOrbs\(\).*?injectOrbs\(\);\s*\}/s', '', $c);
// Also remove the old version if it was still there
$c = preg_replace('/document\.addEventListener\("DOMContentLoaded", function\(\).*?\}\);\s*/s', '', $c);

file_put_contents($jsFile, trim($c));
echo "JS orbs removed.";
