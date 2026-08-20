<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

// Replace `reset` to the end of `changePassword` or something, wait. Let's just use `str_replace`!
$resetOld = <<<'PHP'
    public function reset() {
        if (!isset($_GET['token']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->redirect('/auth/login');
        }

        $token = $_GET['token'] ?? $_POST['token'];
        $userModel = $this->model('User');
        $email = $userModel->verifyResetToken($token);

        if (!$email) {
            die("Invalid or expired token.");
        }

        $data = ['token' => $token, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            if ($password === $confirm) {
                if ($userModel->updatePassword($email, $password)) {
                    $this->redirect('/auth/login');
                } else {
                    $data['error'] = 'Failed to reset password.';
                }
            } else {
                $data['error'] = 'Passwords do not match.';
            }
        }

        $this->view('auth/reset', $data);
    }
PHP;

$resetNew = <<<'PHP'
    public function reset() {
        if (!isset($_GET['email']) && !isset($_POST['email'])) {
            $this->redirect('/auth/login');
        }

        $email = $_GET['email'] ?? $_POST['email'];
        $data = ['email' => $email, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $otp = $_POST['otp'];
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];

            $userModel = $this->model('User');
            $verifiedEmail = $userModel->verifyResetToken($otp);

            if ($verifiedEmail && $verifiedEmail === $email) {
                if ($password === $confirm) {
                    if ($userModel->updatePassword($email, $password)) {
                        $this->redirect('/auth/login');
                    } else {
                        $data['error'] = 'Failed to reset password.';
                    }
                } else {
                    $data['error'] = 'Passwords do not match.';
                }
            } else {
                $data['error'] = 'Invalid or expired OTP code.';
            }
        }

        $this->view('auth/reset', $data);
    }

    public function toggle2fa() {
        $user = JWT::getToken();
        if (!$user) $this->redirect('/auth/login');
        
        $db = (new Model())->db;
        $stmt = $db->prepare("UPDATE users SET two_factor_enabled = NOT two_factor_enabled WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
    }
PHP;

$c = str_replace($resetOld, $resetNew, $c);
file_put_contents($f, $c);
echo "Reset OTP forced, and toggle2fa added.\n";
