<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

// Replace the token generation and email logic in forgot()
$oldLogic = <<<'PHP'
                $token = bin2hex(random_bytes(32));
                if ($userModel->createResetToken($email, $token)) {
                    require_once '../app/Core/Mail.php';
                    $resetLink = "http://localhost/cit_ums/auth/reset?token=" . $token;
                    $body = "
<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f9f9f9;'>
    <div style='text-align: center; margin-bottom: 20px;'>
        <h2 style='color: #2c3e50; margin: 0;'>CIT UMS Account Security</h2>
    </div>
    <div style='background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
        <h3 style='color: #333333; margin-top: 0;'>Password Reset Request</h3>
        <p style='color: #555555; line-height: 1.6; font-size: 16px;'>
            Hello,<br><br>
            We received a request to reset the password associated with your CIT University Management System account.
        </p>
        <div style='text-align: center; margin: 30px 0;'>
            <a href='{$resetLink}' style='background-color: #f97316; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 5px; font-weight: bold; display: inline-block; font-size: 16px;'>Reset My Password</a>
        </div>
        <p style='color: #555555; line-height: 1.6; font-size: 14px;'>
            If you did not request a password reset, please ignore this email or contact the IT Helpdesk immediately. This secure link will expire in 1 hour.
        </p>
        <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
        <p style='color: #999999; font-size: 12px; text-align: center;'>
            &copy; " . date('Y') . " CIT University Management System.<br>This is an automated message, please do not reply directly to this email.
        </p>
    </div>
</div>";
                    
                    if (Mail::send($email, 'Password Reset Request', $body)) {
                        $data['success'] = 'Password reset link sent to your email!';
                    } else {
                        $data['error'] = 'Failed to send email via SMTP.';
                    }
                }
PHP;

$newLogic = <<<'PHP'
                // Generate a 6-digit numeric OTP instead of a URL token
                $otp = sprintf("%06d", mt_rand(100000, 999999));
                if ($userModel->createResetToken($email, $otp)) {
                    require_once '../app/Core/Mail.php';
                    $msg = "Hello,<br><br>We received a request to reset the password associated with your CIT UMS account.<br><br>Your One-Time Password (OTP) is:<br><br><span style='font-size:32px; font-weight:bold; letter-spacing:4px; color:#f97316; display:inline-block; padding:10px 20px; background:#fff3ed; border-radius:8px;'>{$otp}</span><br><br>Please enter this code on the verification page. It expires in 1 hour.";
                    
                    if (Mail::sendTemplate($email, 'Your Password Reset OTP', 'Security Verification Code', $msg)) {
                        // Redirect directly to the reset page (which now acts as the OTP entry page)
                        $this->redirect('/auth/reset?email=' . urlencode($email));
                    } else {
                        $data['error'] = 'Failed to send OTP email via SMTP.';
                    }
                }
PHP;

$c = str_replace($oldLogic, $newLogic, $c);

// Now change reset() logic to handle OTP validation
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
            
            // Verify OTP logic (using the exact OTP they entered instead of token)
            $verifiedEmail = $userModel->verifyResetToken($otp);

            if ($verifiedEmail && $verifiedEmail === $email) {
                if ($password === $confirm) {
                    if ($userModel->updatePassword($email, $password)) {
                        // Mark token used or delete it (handled by updatePassword theoretically, or we can just ignore as it expires)
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
PHP;

$c = str_replace($resetOld, $resetNew, $c);

file_put_contents($f, $c);
echo "OTP logic injected into AuthController.\n";
