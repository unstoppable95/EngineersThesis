<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

	
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

class MyMailer {
    public function sendMail($to, $subject, $message, $attach = '')
    {
        $mail = new PHPMailer(true);                            // Passing `true` enables exceptions                            
        $mail->CharSet = "UTF-8";
    
        $mail->setFrom('info@skarbnikklasowy.pl');
        //Recipients
        $mail->addAddress($to);              
        //Attachments
        if ($attach != '')
        {
            $mail->addAttachment($attach);  
        }

        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    }
}

?>