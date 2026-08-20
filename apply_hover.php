<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/about.php';
$c = file_get_contents($f);

// 1. Add CSS rules to the <style> block
$cssInjection = <<<'CSS'
        .about-container { margin-top: 100px; padding: 4rem 2rem; min-height: 80vh; }

        .dev-profile-wrapper {
            width: 150px; 
            height: 150px; 
            border-radius: 50%; 
            overflow: hidden; 
            margin-bottom: 1.5rem; 
            border: 4px solid var(--cit-orange); 
            box-shadow: 0 4px 10px rgba(249,115,22,0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }
        
        .dev-profile-wrapper:hover {
            transform: scale(1.1) translateY(-5px);
            box-shadow: 0 15px 30px rgba(249,115,22,0.6), 0 0 20px rgba(249,115,22,0.4);
            border-color: #ff983f;
        }

        .dev-profile-wrapper img {
            width: 100%; 
            height: 100%; 
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .dev-profile-wrapper:hover img {
            transform: scale(1.05);
        }
CSS;

$c = str_replace(
    '.about-container { margin-top: 100px; padding: 4rem 2rem; min-height: 80vh; }',
    $cssInjection,
    $c
);

// 2. Replace the inline styles on the div with the class
$oldDiv = '<div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 4px solid var(--cit-orange); box-shadow: 0 4px 10px rgba(249,115,22,0.2);">';
$newDiv = '<div class="dev-profile-wrapper">';

$c = str_replace($oldDiv, $newDiv, $c);

file_put_contents($f, $c);
echo "Hover effects applied.\n";
