<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

// We want to replace from `$token = bin2hex...` all the way to the end of the `if ($userModel->getUserByEmail...` block.
// Using regex to wipe out the old forgot logic entirely and rebuild it.

$forgotOld = '/public function forgot\(\) \{[\s\S]*?public function reset\(\) \{/';
$forgotNew = <<<'PHP'
public function forgot() {
        $data = ['success' => '', 'error' => ''];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $userModel = $this->model('User');
            
            if ($userModel->getUserByEmail($email)) {
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
            } else {
                $data['error'] = 'Email not found.';
            }
        }
        $this->view('auth/forgot', $data);
    }

    public function reset() {
PHP;

$c = preg_replace($forgotOld, $forgotNew, $c);
file_put_contents($f, $c);
echo "OTP generated.\n";
