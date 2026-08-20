<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$resetOld = '/public function reset\(\) \{[\s\S]*?public function toggle2fa\(\) \{/';
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
            
            // Verify OTP logic (using the exact OTP they entered instead of token)
            $verifiedEmail = $userModel->verifyResetToken($otp);

            if ($verifiedEmail && $verifiedEmail === $email) {
                if ($password === $confirm) {
                    if ($userModel->updatePassword($email, $password)) {
                        $this->redirect('/auth/login?reset=success');
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
PHP;

$c = preg_replace($resetOld, $resetNew, $c);
file_put_contents($f, $c);
echo "OTP reset verified.\n";
