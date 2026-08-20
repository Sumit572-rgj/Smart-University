<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$c .= "\n\n/* --- UNIVERSAL MOBILE FIXES --- */\n";
$c .= "@media (max-width: 768px) {\n";
$c .= "    .max-w-xl { max-width: 100% !important; }\n";
$c .= "    .mx-auto { margin-left: auto !important; margin-right: auto !important; }\n";
$c .= "    .p-6 { padding: 1rem !important; }\n";
$c .= "    .digital-id-details { grid-template-columns: 1fr !important; display: flex !important; flex-direction: column !important; }\n";
$c .= "    .digital-id-details > div { grid-column: span 1 !important; }\n";
$c .= "    .w-full { width: 100% !important; }\n";
$c .= "    form textarea { width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; }\n";
$c .= "    .content-wrapper { padding: 1rem !important; }\n";
$c .= "}\n";

file_put_contents($f, $c);
echo "CSS patched.\n";
