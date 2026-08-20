<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';
$c = file_get_contents($f);

// Add the CSS for page load animation
$animCSS = <<<CSS
        /* --- Login Load Animation --- */
        @keyframes slideUpFadeIn {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .glass-card {
            animation: slideUpFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
CSS;

if (strpos($c, 'slideUpFadeIn') === false) {
    // Inject right before </style>
    $c = str_replace('</style>', $animCSS . "\n    </style>", $c);
    file_put_contents($f, $c);
}
echo "Login animation added.";
