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
            
        //Server settings
        //$mail->SMTPDebug = 0;                                 // Enable verbose debug output
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Host = 'serwer1907769.home.pl';                // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        //$mail->Username = 'info@skarbnikklasowy.pl';          // SMTP username
        //$mail->Password = 'skarbnik123';                      // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 465;                                    // TCP port to connect to

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