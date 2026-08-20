<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$loginOld = '/public function login\(\) \{[\s\S]*?public function verify2fa\(\) \{/';

$loginNew = <<<'PHP'
public function login() {
        if (JWT::getToken()) {
            $this->redirect('/dashboard');
        }

        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->login($username, $password);

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
                $data['error'] = 'Invalid username or password.';
            }
        }

        $this->view('auth/login', $data);
    }

    public function verify2fa() {
PHP;

$c = preg_replace($loginOld, $loginNew, $c);
file_put_contents($f, $c);
echo "Forced login 2FA patch.\n";
