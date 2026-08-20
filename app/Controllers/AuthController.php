<?php
// app/Controllers/AuthController.php

class AuthController extends Controller {
    public function index() {
        $this->login();
    }

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

    public function logout() {
        JWT::logout();
        $this->redirect('/auth/login');
    }

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

    public function changePassword() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            $userModel = $this->model('User');
            // Re-authenticate to verify current password
            $verified = $userModel->login($user['username'], $current_password);

            if ($verified) {
                if ($new_password === $confirm_password) {
                    $email = $verified['email'];
                    if ($userModel->updatePassword($email, $new_password)) {
                        // Success, redirect back with query param
                        $this->redirect('/dashboard?pwd_success=1');
                    } else {
                        $this->redirect('/dashboard?pwd_error=update_failed');
                    }
                } else {
                    $this->redirect('/dashboard?pwd_error=mismatch');
                }
            } else {
                $this->redirect('/dashboard?pwd_error=invalid_current');
            }
        }
    }
}
