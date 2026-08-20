<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';
$c = file_get_contents($f);

$cssToAdd = <<<CSS

        /* Mobile Responsiveness for Login */
        @media (max-width: 480px) {
            .glass-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
                width: calc(100% - 2rem);
                border-radius: 1rem;
            }
            .login-header h1 {
                font-size: 2rem;
            }
            .back-home {
                top: 1rem;
                left: 1rem;
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }
CSS;

if (strpos($c, 'Mobile Responsiveness for Login') === false) {
    // Inject right before </style>
    $c = str_replace('</style>', $cssToAdd . "\n    </style>", $c);
    file_put_contents($f, $c);
}
echo "Login mobile CSS fixed.";
