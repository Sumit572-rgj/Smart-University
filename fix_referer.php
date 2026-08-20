<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$oldToggle = <<<'PHP'
    public function toggle2fa() {
        $user = JWT::getToken();
        if (!$user) $this->redirect('/auth/login');
        
        $db = (new Model())->db;
        $stmt = $db->prepare("UPDATE users SET two_factor_enabled = NOT two_factor_enabled WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
    }
PHP;

$newToggle = <<<'PHP'
    public function toggle2fa() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }
        
        $db = (new Model())->db;
        $stmt = $db->prepare("UPDATE users SET two_factor_enabled = NOT two_factor_enabled WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $this->redirect('/dashboard');
        }
    }
PHP;

$c = str_replace($oldToggle, $newToggle, $c);
file_put_contents($f, $c);
echo "Fixed redirect.\n";
