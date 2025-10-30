<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
$staffid	=	$_POST[staffid];
$message	=	$_POST[message];
$subject	=	$_POST[subject];
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

$FromSelectStaffQuery = "select * from ceg_staff where staff_icno = ".$_SESSION['tts_userid'];
$FromSelectStaffSql = mysqli_query($dbConn,$FromSelectStaffQuery);
if($FromSelectStaffSql == true){
	if(mysqli_num_rows($FromSelectStaffSql)>0){
		$FromList = mysqli_fetch_object($FromSelectStaffSql);
		$FromMailStaffName  = $FromList->staff_name;
		$FromMailStaffDesig = $FromList->staff_design;
		$FromMailStaffEmail = $FromList->staff_email;
	}
}

$ToSelectStaffQuery = "select * from ceg_staff where staff_icno = '$staffid'";
$ToSelectStaffSql = mysqli_query($dbConn,$ToSelectStaffQuery);
if($ToSelectStaffSql == true){
	if(mysqli_num_rows($ToSelectStaffSql)>0){
		$ToList = mysqli_fetch_object($ToSelectStaffSql);
		$ToMailStaffName  = $ToList->staff_name;
		$ToMailStaffDesig = $ToList->staff_design;
		$ToMailStaffEmail = $ToList->staff_email;
	}
}
//$message = $message." - ".$_SESSION['tts_userid']." - ".$ToMailStaffDesig." - ".$ToMailStaffEmail;
$mail = new PHPMailer;
	
	$mail->IsSMTP();
	//$mail->SMTPDebug = 1;
	$mail->SMTPAuth = true;
	//$mail->SMTPSecure = 'ssl';
	$mail->Host = "10.1.1.2";//"smtp.gmail.com";
	$mail->Port = 25;//465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "hari@igcar.gov.in";//"nkl@igcar.gov.in";
	$mail->Password = "hk@5023jp";//"nkfeb19";

	$mail->setFrom($FromMailStaffEmail, $FromMailStaffName);
	$mail->addAddress($ToMailStaffEmail, $ToMailStaffName);
	//$mail->addReplyTo('prabasinghkathirvel@gmail.com', 'REPLY MAIL');
	$mail->isHTML(true);

	$mail->Subject = $subject;
	
	
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
		$FromMailIcno 	= $_SESSION['tts_userid'];
		$ToMailIcno 	= $staffid;
		$InsertSetMailQuery = "insert into sent_mail set from_email = '$FromMailStaffEmail', to_email = '$ToMailStaffEmail', from_icno = '$FromMailIcno', to_icno = '$ToMailIcno', mail_subject = 'BDAY', mail_desc = '$message', mail_attach = '', sent_date = NOW()";
		$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
		echo 1;
	}
?>