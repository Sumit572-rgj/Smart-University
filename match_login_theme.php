<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';
$c = file_get_contents($f);

// Remove the hardcoded blur orbs
$c = preg_replace('/<div class="blur-orb orb-\d"><\/div>/', '', $c);

// Remove the CSS that defines the orbs in login.php
$c = preg_replace('/\/\* Animated Floating Background Orbs \*\/.*?\@keyframes float \{.*?\}/s', '', $c);

// Change background color of login body to match the fintech theme
$c = str_replace('background-color: #f4f7f9;', 'background-color: #f3f4f6;', $c);

// Change the glass card to a solid white card
$c = preg_replace('/\.glass-card\s*\{.*?\}/s', <<<CSS
        .glass-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border-radius: 1.5rem;
            padding: 3rem;
            max-width: 420px;
            width: 100%;
            position: relative;
            z-index: 10;
        }
CSS
, $c);

// Remove the translucent styling on the back-home button
$c = preg_replace('/\.back-home\s*\{.*?\}/s', <<<CSS
        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: inline-flex;
            align-items: center;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
            z-index: 20;
            background: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
CSS
, $c);

file_put_contents($f, $c);
echo "Login page orbs removed and theme matched.";
