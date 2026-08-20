<?php

// 1. Add Bell Animation to style.css
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$bellAnim = <<<CSS

/* Bell Swing Animation */
@keyframes bellSwing {
    20% { transform: rotate(15deg); }
    40% { transform: rotate(-10deg); }
    60% { transform: rotate(5deg); }
    80% { transform: rotate(-5deg); }
    100% { transform: rotate(0deg); }
}
.notification-bell > span:first-child {
    display: inline-block;
    transform-origin: top center;
}
.notification-bell:hover > span:first-child {
    animation: bellSwing 0.8s ease-in-out forwards;
}

CSS;

if (strpos($c, 'bellSwing') === false) {
    file_put_contents($f, $c . $bellAnim);
}

// 2. Mass Replace Sidebar Links in all View files
$viewsDir = 'C:/xampp/htdocs/cit_ums/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$phpFiles = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$emojis = [
    'Dashboard' => '🏠',
    'Messages & Forums' => '💬',
    'Student Management' => '🎓',
    'Faculty Management' => '👨‍🏫',
    'Warden Management' => '🛡️',
    'Security Guard Management' => '👮',
    'Hostel Management' => '🏢',
    'Library Management' => '📚',
    'Exam Management' => '📝',
    'Transport Mgmt' => '🚌',
    'Placement Center' => '💼',
    'Notice Board' => '📌',
    'Course Management' => '📘'
];

$count = 0;
foreach ($phpFiles as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // We look for: <a href="/cit_ums/some/path" class="nav-link...">Text</a>
    foreach ($emojis as $text => $emoji) {
        // Regex to safely inject the emoji if it's not already there
        $pattern = '/(<a[^>]+class="[^"]*nav-link[^"]*"[^>]*>)\s*(?!' . preg_quote($emoji, '/') . ')(' . preg_quote($text, '/') . ')\s*<\/a>/i';
        $replacement = '$1<span style="margin-right:8px; font-size:1.1rem;">' . $emoji . '</span>$2</a>';
        $content = preg_replace($pattern, $replacement, $content);
    }

    if ($content !== $original) {
        file_put_contents($path, $content);
        $count++;
    }
}

echo "Updated $count view files with sidebar emojis, and added bell swing animation!";
