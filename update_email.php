<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AuthController.php';
$c = file_get_contents($f);

$oldBody = "\$body = \"<h3>CIT UMS Password Reset</h3><p>Click <a href='{\$resetLink}'>here</a> to reset your password. The link expires in 1 hour.</p>\";";

$newBody = <<<'PHP'
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
PHP;

$c = str_replace($oldBody, $newBody, $c);
file_put_contents($f, $c);
echo "Updated email template.\n";
