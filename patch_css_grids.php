<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$c .= "\n\n/* --- UNIVERSAL INLINE GRID OVERRIDES --- */\n";
$c .= "@media (max-width: 768px) {\n";
$c .= "    .grid, [style*='grid-template-columns'] { grid-template-columns: 1fr !important; display: flex !important; flex-direction: column !important; }\n";
$c .= "}\n";

file_put_contents($f, $c);
echo "CSS patched heavily.\n";
