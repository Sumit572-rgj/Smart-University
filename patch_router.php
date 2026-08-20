<?php
$routerFile = 'app/Core/Router.php';
$content = file_get_contents($routerFile);

$oldLogic = "if (isset(\$url[0]) && file_exists(__DIR__ . '/../../app/Controllers/' . ucfirst(\$url[0]) . 'Controller.php')) {
            \$this->controller = ucfirst(\$url[0]) . 'Controller';
            unset(\$url[0]);
        }";

$newLogic = "if (isset(\$url[0])) {
            \$requested = strtolower(\$url[0]) . 'controller.php';
            \$dir = __DIR__ . '/../../app/Controllers/';
            \$files = scandir(\$dir);
            foreach (\$files as \$file) {
                if (strtolower(\$file) === \$requested) {
                    \$this->controller = str_replace('.php', '', \$file);
                    unset(\$url[0]);
                    break;
                }
            }
        }";

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($routerFile, $content);
echo "Router updated.";
