<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$resetOld = '/public function reset\(\) \{[\s\S]*?public function changePassword\(\) \{/';

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

    public function changePassword() {
PHP;

$c = preg_replace($resetOld, $resetNew, $c);
file_put_contents($f, $c);
echo "Reset OTP properly forced.\n";
