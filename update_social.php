<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/about.php';
$c = file_get_contents($f);

// Replace GitHub
$c = preg_replace(
    '/<a href="#" (style="background: #24292e;[^>]+>)\s*GitHub\s*<\/a>/i',
    '<a href="https://github.com/Sumit572-rgj" $1 GitHub </a>',
    $c
);

// Replace LinkedIn
$c = preg_replace(
    '/<a href="#" (style="background: #0a66c2;[^>]+>)\s*LinkedIn\s*<\/a>/i',
    '<a href="https://www.linkedin.com/in/sumit-kumar-chaurasiya-041a77429" $1 LinkedIn </a>',
    $c
);

// Replace Instagram
$c = preg_replace(
    '/<a href="#" (style="background: linear-gradient[^>]+>)\s*Instagram\s*<\/a>/i',
    '<a href="https://www.instagram.com/rhythm773_1/" $1 Instagram </a>',
    $c
);

// Replace LeetCode
$c = preg_replace(
    '/<a href="#" (style="background: #ffa116;[^>]+>)\s*LeetCode\s*<\/a>/i',
    '<a href="https://leetcode.com/u/SUMIT773/" $1 LeetCode </a>',
    $c
);

file_put_contents($f, $c);
echo "Social links updated.\n";
