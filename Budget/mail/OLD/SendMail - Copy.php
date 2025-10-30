<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
//$staffid	=	$_POST[staffid];
//$message	=	$_POST[message];
//$subject	=	$_POST[subject];
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
/*$select_bday_query 	= "select a.* from ceg_staff a where NOT EXISTS 
					  (select b.to_icno from sent_mail b where b.to_icno = a.staff_icno AND b.mail_subject = 'BDAY' and date(b.sent_date) = '$ToDayBdayCheck' and ) 
					  AND DATE_FORMAT(a.staff_dob,'%m-%d') = '$BdayCheck'";//staff_dob = CURDATE()";//'$bday'";


$ToSelectStaffQuery = "select * from ceg_staff where staff_icno = '$staffid'";
$ToSelectStaffSql = mysqli_query($dbConn,$ToSelectStaffQuery);
if($ToSelectStaffSql == true){
	if(mysqli_num_rows($ToSelectStaffSql)>0){
		$ToList = mysqli_fetch_object($ToSelectStaffSql);
		$ToMailStaffName  = $ToList->staff_name;
		$ToMailStaffDesig = $ToList->staff_design;
		$ToMailStaffEmail = $ToList->staff_email;
	}
}*/
$BDayCnt = 0;
$ToDayBdayCheck = date("Y-m-d"); $BdayCheck = date("m-d");
$select_bday_query 	= "select a.* from ceg_staff a DATE_FORMAT(a.staff_dob,'%m-%d') = '$BdayCheck'";//staff_dob = CURDATE()";//'$bday'";
$select_bday_sql = mysqli_query($dbConn,$select_bday_query);
if($select_bday_sql == true){
	if(mysqli_num_rows($select_bday_sql)>0){
		$BDayCnt = 1;
	}
}
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
	$mail->isHTML(true);
	if($BDayCnt == 1){
		while($BDList = mysqli_fetch_object($select_bday_sql)){
			$BDIcNo = $BDList->staff_icno;
			$BDDob = $BDList->staff_dob;
			$BDEmail = $BDList->staff_email;
			$Send = 0;
			$message = 'Wish u a many more happy returns of the day';
			$SelectSentMailQuery = "select * from sent_mail where to_icno = '$BDIcNo' and act_send_date = '$ToDayBdayCheck'";
			$SelectSentMailSql = mysqli_query($dbConn,$SelectSentMailQuery);
			if($SelectSentMailSql == true){
				$SentCnt = mysqli_num_rows($SelectSentMailSql);
				if($SentCnt == 0){
					$Send = 1; // Insert and Send
					$Action = "I";
				}else{
					$SendList = mysqli_fetch_object($SelectSentMailSql);
					$Status = $SendList->send_status;
					$id = $SendList->id;
					if($Status != 'S'){
						$Send = 1; /// Send and Update
						$Action = "U";
					}
				}
			}
			if($Send == 1){
				$mail->addAddress($ToMailStaffEmail, $ToMailStaffName);
				$mail->Subject = $subject;
				$mail->Body = $message;//'New mail Checking';
				$mail->AltBody = $message;//'Dear Prabasingh, Thank you for your interest.';
				if(!$mail->send()) {
					//echo 0;
				} else {
					$FromMailIcno 	= $_SESSION['tts_userid'];
					$ToMailIcno 	= $staffid;
					if($Action == "I"){
						$InsertSetMailQuery = "insert into sent_mail set from_email = '$FromMailStaffEmail', to_email = '$BDEmail', from_icno = '$FromMailIcno', to_icno = '$BDIcNo', mail_subject = 'BDAY', mail_desc = '$message',  mail_attach = '', act_send_date = NOW(), to_be_send_on = NOW(), send_status = 'S', sent_date = NOW()";
						$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
					}
					if($Action == "U"){
						$InsertSetMailQuery = "update sent_mail set send_status = 'S', sent_date = NOW() where id = '$id'";
						$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
					}
					//echo 1;
				}
			}
		}
	}
	
?>