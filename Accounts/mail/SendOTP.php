<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
$staffid	=	$_POST[staffid];
$message	=	$_POST[message];
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "prabasinghkathirvel@gmail.com";
$mail->Password = "akpsingh@2070";
$mail->setFrom('praba@lashron.com', 'support');
$mail->ClearAllRecipients();	
//$mail->AddCC('hari@igcar.gov.in');

$mail->addAddress('kprabasingh@gmail.com', 'Praba');
$mail->addReplyTo('kprabasingh@gmail.com', 'Support');
$mail->isHTML(true);
$mail->Subject = 'Subject';
$mail->Body = $message;//'New mail Checking';
$mail->AltBody = $message;//'Dear Prabasingh, Thank you for your interest.';
if(!$mail->send()) {
	echo 0;
}else{
	echo 1;
}
?>