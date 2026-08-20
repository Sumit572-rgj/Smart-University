<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$oldLogin = <<<'PHP'
            if ($user) {
                JWT::setToken([
                    'id' => $user['id'],
                    'role' => $user['role'],
                    'username' => $user['username']
                ]);
                $this->redirect('/dashboard');
            } else {
PHP;

$newLogin = <<<'PHP'
            if ($user) {
                if (!empty($user['two_factor_enabled'])) {
                    $otp = sprintf("%06d", mt_rand(100000, 999999));
                    $userModel->createResetToken($user['email'], $otp);
                    
                    require_once '../app/Core/Mail.php';
                    $msg = "Your 2-Factor Authentication (2FA) code for login is:<br><br><span style='font-size:32px; font-weight:bold; letter-spacing:4px; color:#f97316; display:inline-block; padding:10px 20px; background:#fff3ed; border-radius:8px;'>{$otp}</span><br><br>Please enter this code to complete your login. It expires in 1 hour.";
                    Mail::sendTemplate($user['email'], 'Your Login 2FA Code', 'Login Verification', $msg);
                    
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['2fa_pending_user'] = $user;
                    $this->redirect('/auth/verify2fa');
                } else {
                    JWT::setToken([
                        'id' => $user['id'],
                        'role' => $user['role'],
                        'username' => $user['username']
                    ]);
                    $this->redirect('/dashboard');
                }
            } else {
PHP;

$c = str_replace($oldLogin, $newLogin, $c);

$verify2faMethod = <<<'PHP'
    public function verify2fa() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['2fa_pending_user'])) {
            $this->redirect('/auth/login');
        }

        $data = ['error' => ''];
        $user = $_SESSION['2fa_pending_user'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = $_POST['otp'];
            $userModel = $this->model('User');
            $verifiedEmail = $userModel->verifyResetToken($otp);

            if ($verifiedEmail && $verifiedEmail === $user['email']) {
                JWT::setToken([
                    'id' => $user['id'],
                    'role' => $user['role'],
                    'username' => $user['username']
                ]);
                unset($_SESSION['2fa_pending_user']);
                $this->redirect('/dashboard');
            } else {
                $data['error'] = 'Invalid or expired 2FA code.';
            }
        }
        $this->view('auth/verify2fa', $data);
    }
PHP;

$c = preg_replace('/public function logout\(\) \{/', $verify2faMethod . "\n\n    public function logout() {", $c);

// Also add a toggle route
$toggleMethod = <<<'PHP'
    public function toggle2fa() {
        $user = JWT::getToken();
        if (!$user) $this->redirect('/auth/login');
        
        $db = (new Model())->db;
        $stmt = $db->prepare("UPDATE users SET two_factor_enabled = NOT two_factor_enabled WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
    }
PHP;

$c = preg_replace('/public function reset\(\) \{/', $toggleMethod . "\n\n    public function reset() {", $c);

file_put_contents($f, $c);
echo "Injected 2FA logic.\n";
