<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/about.php';
$c = file_get_contents($f);

// We want to replace the current image src and subtitle with the ones requested by the user.
// Current: <img src="https://avatars.githubusercontent.com/u/9919?s=200&v=4" alt="Developer Profile"
// Current: >Full Stack AI Engineer</p>

$c = str_replace(
    '<img src="https://avatars.githubusercontent.com/u/9919?s=200&v=4" alt="Developer Profile"', 
    '<img src="developer.jpg" alt="Developer Profile"', 
    $c
);

$c = str_replace(
    '>Full Stack AI Engineer</p>', 
    '>Meet The Developer</p>', 
    $c
);

file_put_contents($f, $c);
echo "Updated developer profile photo and subtitle.\n";
