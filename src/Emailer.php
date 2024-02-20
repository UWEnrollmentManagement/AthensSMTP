<?php

namespace Athens\SMTPMailer;

use Athens\Core\Emailer\AbstractEmailer;
use Athens\Core\Email\EmailInterface;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use SendGrid;
use SendGrid\Response;
use SendGrid\Email as SendGridEmail;

/**
 * Class Emailer
 *
 * @package Athens\SendGrid\Emailer
 */
class Emailer extends AbstractEmailer
{

    /** @var SendGrid */
    // protected static $sendgrid;

    /**
     * @param string         $body
     * @param EmailInterface $email
     * @return boolean
     */
    protected function doSend($body, EmailInterface $email)
    {
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);
		$result = false;

		try {
			
			//Server settings
			$mail->SMTPDebug = SMTP_DEBUG;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.uw.edu';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = SMTP_USER;                     //SMTP username
			// $mail->Password   = EMAILNETIDPASSWORD;                               //SMTP password
			$mail->Password   = SMTP_PASSWORD;                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
			$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

			
			foreach (explode(';', $email->getTo()) as $to) {
				$mail->addAddress(trim($to));
			}

			foreach (explode(';', $email->getCc()) as $cc) {
				$cc = trim($cc);
				if (!empty($cc)) { // Stupid Sexy NULL
					$mail->addCC($cc);
				}
				
			}

			foreach (explode(';', $email->getBcc()) as $bcc) {
				$bcc = trim($bcc);
				if (!empty($bcc)) {
					$mail->addBCC($bcc);
				}
			}

			if (((string)$email->getReplyTo()) !== "") {
				$mail->addReplyTo($email->getReplyTo());
			} 
				
			
			$mail->setFrom($email->getFrom());
			

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $email->getSubject();
			$mail->Body    = $body;

			$result - $mail->send();
			
			
			// echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
		
		return $result;
	}

	
	
}
