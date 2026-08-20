<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Core/Mail.php';
$c = file_get_contents($f);

// Fix the missing closing brace for send()
$c = str_replace(
    "} catch (Exception \$e) {
            // Log error
            error_log(\"Message could not be sent. Mailer Error: {\$mail->ErrorInfo}\");
            return false;
        }
        public static function sendTemplate",
    "} catch (Exception \$e) {
            // Log error
            error_log(\"Message could not be sent. Mailer Error: {\$mail->ErrorInfo}\");
            return false;
        }
    }

    public static function sendTemplate",
    $c
);

file_put_contents($f, $c);
echo "Syntax error fixed.\n";
