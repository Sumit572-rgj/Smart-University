<?php
// app/Core/Mail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

class Mail {
    public static function send($to, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // User can replace this with their SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sumitchaurasiya98450@gmail.com'; // Placeholder
            $mail->Password   = 'btyl kglt wack cmga'; // Placeholder
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('sumitchaurasiya98450@gmail.com', 'CIT UMS');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function sendTemplate($to, $subject, $title, $message, $actionText = null, $actionUrl = null) {
        $btnHtml = '';
        if ($actionText && $actionUrl) {
            $btnHtml = "<div style='text-align: center; margin: 30px 0;'>
                <a href='{$actionUrl}' style='background-color: #f97316; color: #ffffff; text-decoration: none; padding: 14px 28px; border-radius: 5px; font-weight: bold; display: inline-block; font-size: 16px;'>{$actionText}</a>
            </div>";
        }

        $html = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px; background-color: #f9f9f9;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #2c3e50; margin: 0;'>CIT UMS Alerts</h2>
            </div>
            <div style='background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
                <h3 style='color: #333333; margin-top: 0;'>{$title}</h3>
                <p style='color: #555555; line-height: 1.6; font-size: 16px;'>
                    " . nl2br($message) . "
                </p>
                {$btnHtml}
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='color: #999999; font-size: 12px; text-align: center;'>
                    &copy; " . date('Y') . " CIT University Management System.<br>This is an automated system alert.
                </p>
            </div>
        </div>";

        return self::send($to, $subject, $html);
    }
}
