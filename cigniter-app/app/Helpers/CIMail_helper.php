<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('sendEmail')) {
    function sendEmail($mailConfig)
    {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('EMAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('EMAIL_USERNAME');
            $mail->Password = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = env('EMAIL_ENCRYPTION');
            $mail->Port = env('EMAIL_PORT');
            
            // Vérifiez que les adresses e-mail sont valides
            if (!filter_var($mailConfig['mail_from_email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid sender email address.');
            }
            if (!filter_var($mailConfig['mail_recipient_email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid recipient email address.');
            }

            $mail->setFrom($mailConfig['mail_from_email'], $mailConfig['mail_from_name']);
            $mail->addAddress($mailConfig['mail_recipient_email'], $mailConfig['mail_recipient_name']);
            
            $mail->isHTML(true);
            $mail->Subject = $mailConfig['mail_subject'];
            $mail->Body = $mailConfig['mail_body'];

            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Gérez les erreurs de PHPMailer
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        } catch (\Exception $e) {
            // Gérez les autres exceptions
            error_log("General Error: " . $e->getMessage());
            return false;
        }
    }
}
