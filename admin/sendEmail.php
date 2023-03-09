<?php
	require 'requires/PHP_Mailer.php';
	require 'requires/SMTP.php';
	require 'requires/Exception.php';

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
	
	function sendEmail($email, $message, $subject) {
		ob_start();
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPDebug = 2; 
			$mail->isSMTP();
			//Define smtp host
			$mail->Host = "smtp.gmail.com";
			//Enable smtp authentication
			$mail->SMTPAuth = true;
			//Set smtp encryption type (ssl/tls)
			$mail->SMTPSecure = "ssl";
			//Port to connect smtp
			$mail->Port = "465";
			//Set gmail username
			$mail->Username = "schoolworks192@gmail.com";
			//Set gmail password
			$mail->Password = "vgntpgueaxpxbyvi";
			//Email subject
			$mail->Subject = $subject;
			//Set sender email
			$mail->setFrom('schoolworks192@gmail.com');
			//Enable HTML
			$mail->isHTML(true);
			//Attachment
			//$mail->addAttachment('img/attachment.png');
			//Email body
			$mail->Body = $message;
			//Add recipient
			$mail->addAddress($email);
			//Finally send email
			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
		ob_end_clean();
	}
	
?>