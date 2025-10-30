<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';

$WcmsRoot = '';
$path = 'attachment/';
$ChatToEmailList = $_POST['ChatToEmailList'];
$ChatCcEmailList = $_POST['ChatCcEmailList'];
$ChatMessage 	 = $_POST['ChatMessage'];
$ChatFromMail 	 = $_POST['ChatFromMail'];
$ChatFromPwd 	 = $_POST['ChatFromPwd'];
$Chatccno 	 	 = $_POST['Chatccno'];

$Temp1 			 = explode(",",$ChatToEmailList);
$Temp2 			 = explode(",",$ChatCcEmailList);
$Temp3 	 		 = array_merge($Temp1,$Temp2);
$ToList		 	 = array_unique($Temp3);

$Message = "<b>Reference : <span style='color:red;'>CCNO - ".$Chatccno."</span></b><br/>";
$Message = $Message.$ChatMessage;

$mail = new PHPMailer;

/*==================================     THIS IS FOR IGCAR MAIL SETTING =========================== */

/*$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "10.1.1.2";//"smtp.gmail.com";
$mail->Port = 25;//465; // or 587
$mail->IsHTML(true);
$mail->Username = $ChatFromMail;
$mail->Password = $ChatFromPwd;
$mail->setFrom($ChatFromMail, $_SESSION['staffname']);*/

/*================================================================================================= */

/*==================================     THIS IS FOR LASHRON MAIL SETTING ========================= */
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = $ChatFromMail;
$mail->Password = $ChatFromPwd;
$mail->setFrom($ChatFromMail, $_SESSION['staffname']);
//echo $ChatFromMail;exit;
/*================================================================================================= */
//echo $ChatFromMail; echo $ChatFromPwd;exit;
$Error = "";	
$Send = 0;
if(($ChatFromMail != '')&&($ChatFromPwd != '')){
	$Send = 1;
}			
				//$OUT .= $ICNO."*".$Send."*".$Action;
if($Send == 1){
	$mail->ClearAllRecipients();
	if(count($Temp1)>0){
		foreach($Temp1 as $ToKey1 => $ToValue1){
			$mail->addAddress($ToValue1, 'Lashron Technologies');
		}
	}
	if(count($Temp2)>0){
		foreach($Temp2 as $ToKey2 => $ToValue2){
			$mail->AddCC($ToValue2);
		}
	}

	$mail->Subject = 'Regarding Query Raising in EBMS (FRFCF,NRB,BARC)';
	$mail->Body = "<b>Description : </b>".$Message;//'New mail Checking';
	$mail->AltBody = "<b>Description : </b>".$Message;//'Dear Prabasingh, Thank you for your interest.';
	//print_r($mail);exit;
	if(!$mail->send()) {
		$Error = "Failure: Your mail not sent. Try again.";
	}else{
		$InsertSetMailQuery = "insert into query_raised set ccno = '$Chatccno', message = '$ChatMessage', mail_from = '$ChatFromMail', mail_to = '$ChatToEmailList', mail_to_cc = '$ChatCcEmailList', sent_on = NOW(), send_by = ".$_SESSION['sid'];
		$InsertSetMailSql   = mysql_query($InsertSetMailQuery);
		$Error = "Success: Your mail has been sent";
	}
	//$mail->clearAttachments();
}
if($Error == ""){
	$Error = "Failure: Your mail not sent";
}
echo $Error;
?>