<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
$staffid	=	$_POST[staffid];
$message	=	$_POST[message];

/*if($_FILES['attachment']['name'] != ""){ 
	$target_dir 		= "mail/attachment/";
	$target_file 		= $target_dir . basename($_FILES["attachment"]["name"]);
	$checkupload 		= 1;
	$imageFileType 		= pathinfo($target_file, PATHINFO_EXTENSION);
	if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)){
		$checkupload = 0;
		$fp = fopen($target_file, "r");
	}
}	*/
//echo $message;exit;
$mail = new PHPMailer;
	
	$mail->IsSMTP();
	//$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = 'ssl';
	$mail->Host = "10.1.1.2";//"smtp.gmail.com";
	$mail->Port = 25;//465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "nkl@igcar.gov.in";
	$mail->Password = "nkfeb19";

	$mail->setFrom('nkl@igcar.gov.in', 'Associate Director');
	$mail->addAddress('hari@igcar.gov.in', 'TTS');
	$mail->addReplyTo('hari@igcar.gov.in', 'TTS Administrator');
	$mail->isHTML(true);

	$mail->Subject = 'Birthday Wishes';
	
	
	//$message = "<html><head></head><body>";
	//$message .= "<img src='http://192.168.1.4/wcms/images/img1.jpg' alt='' />fgfsgfsgfs</body></html>";
	
	$mail->Body = $message;//'New mail Checking';
	
	$mail->AltBody = $message;//'Dear Prabasingh, Thank you for your interest.';
	
	
	//$file_to_attach = 'PATH_OF_YOUR_FILE_HERE';
	//$mail->AddAttachment( $file_to_attach , 'NameOfFile.pdf' );

	if(!$mail->send()) {
		//echo "Opps! For some technical reasons we couldn't able to sent you an email. We will shortly get back to you with download details.";	
		//echo "Mailer Error: " . $mail->ErrorInfo;
		echo 0;
	} else {
		//echo "Message has been sent";
		echo 1;
	}
?>