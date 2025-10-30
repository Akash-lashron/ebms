<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/common.php';
require_once '../library/declaration.php';
checkUser();
$SecArr 	= array("ASED","ARS","QCSCL","CDS");

$FromSelectStaffQuery = "select * from ceg_staff where staff_icno = '5023'";//.$_SESSION['tts_userid'];
$FromSelectStaffSql = mysqli_query($dbConn,$FromSelectStaffQuery);
if($FromSelectStaffSql == true){
	if(mysqli_num_rows($FromSelectStaffSql)>0){
		$FromList = mysqli_fetch_object($FromSelectStaffSql);
		$FromMailStaffName  = $FromList->staff_name;
		$FromMailStaffDesig = $FromList->staff_design;
		$FromMailStaffEmail = $FromList->staff_email;
		$FromMailStaffEPass = $FromList->epass;
	}
}

$Today 		= date('Y-m-d'); $xy = 1;
$DueDate 	= date('Y-m-d', strtotime('-10 days', strtotime($Today)));
$LastSentDate = '0000-00-00'; $Exist = 0;
$SelectSentMailQuery = "select max(act_send_date) as maxdate from sent_mail where mail_subject = 'MUP' and send_status = 'S'";
$SelectSentMailSql 	 = mysqli_query($dbConn,$SelectSentMailQuery);
if($SelectSentMailSql == true){
	if(mysqli_num_rows($SelectSentMailSql)>0){
		$Exist = 1;
		$DateList = mysqli_fetch_object($SelectSentMailSql);
		$LastSentDate = $DateList->maxdate;
	}
}
$Send = 0;
if(($LastSentDate == '0000-00-00')&&($Exist == 0)){
	$Send = 1;
}else if(($DueDate > $LastSentDate)&&($Exist == 1)){
	$Send = 1;
}
//echo $Send;exit;


//if($_SESSION['tts_userid'] == '5023'){
if($Send == 1){
$mail = new PHPMailer;

/*==================================     THIS IS FOR IGCAR MAIL SETTING =========================== */

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "10.1.1.2";//"smtp.gmail.com";
$mail->Port = 25;//465; // or 587
$mail->IsHTML(true);
$mail->Username = "hari@igcar.gov.in";//"nkl@igcar.gov.in";
$mail->Password = $FromMailStaffEPass;//"bhk#1989$";//"hk@5023jp";//"nkfeb19";
$mail->setFrom($FromMailStaffEmail, $FromMailStaffName);

/*================================================================================================= */

/*==================================     THIS IS FOR LASHRON MAIL SETTING ========================= */
/*$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "prabasinghkathirvel@gmail.com";
$mail->Password = "akpsingh@2070";
$mail->setFrom('praba@lashron.com', 'PRABA');*/
/*================================================================================================= */

$PageTitle 	= "MarkUp List"; $menuid = "MUP";
$userid 	= $_SESSION['tts_userid'];
$SecArr 	= array("ASED","ARS","QCSCL","CDS");

$EmailArray = array(); $NameArray = array(); $SecHeadArray = array();
$SelectEmailQuery = "select staff_icno, staff_name, staff_email, sec_head, staff_section from ceg_staff a where active = 1";
$SelectEmailSql = mysqli_query($dbConn,$SelectEmailQuery);
if($SelectEmailSql == true){
	if(mysqli_num_rows($SelectEmailSql)>0){
		while($EmailList = mysqli_fetch_object($SelectEmailSql)){
			$EmailArray[$EmailList->staff_icno] = $EmailList->staff_email;
			$NameArray[$EmailList->staff_icno] = $EmailList->staff_name;
			if((in_array($EmailList->staff_section,$SecArr))&&($EmailList->sec_head == 'Y')){
				array_push($SecHeadArray,$EmailList->staff_icno);
			} 
		}
	}
}
$SelectRefNoQuery = "select a.* from ceg_task a where a.action = 'MARKUP' and a.marked_date < '$DueDate' and a.task_type != 'MBM' and a.entry_date = (select max(b.entry_date) from ceg_task b where b.refno = a.refno) order by a.entry_date asc";
//echo $SelectRefNoQuery;exit;
$SelectRefNoSql = mysqli_query($dbConn,$SelectRefNoQuery);
if($SelectRefNoSql == true){
	if(mysqli_num_rows($SelectRefNoSql)>0){
		while($RefNoList = mysqli_fetch_object($SelectRefNoSql)){
			$PaperType 		= $RefNoList->task_type;
			$MarkedFrom 	= $RefNoList->from_icno;
			$MarkedTo 		= $RefNoList->to_icno;
			$MarkedFromSec 	= $RefNoList->from_section;
			$MarkedToSec 	= $RefNoList->to_section;
			$ToSendIcno = array();
			$DrawReq = -1;
			if(($PaperType == 'EST')&&($MarkedFromSec = 'CMMWD' OR $MarkedFromSec = 'CMBPS')&&($MarkedToSec = 'CMMWD' OR $MarkedToSec = 'CMBPS')&&($RefNoList->dept_code != 'CEG')){
				$SelectDrawReqQuery = "select refno from ceg_task where ASED_view = 'NO' and refno = '$RefNoList->refno'";
				$SelectDrawReqSql = mysqli_query($dbConn,$SelectDrawReqQuery);
				if($SelectDrawReqSql == true){
					$DrawReq = 0;
					$DrawReq = mysqli_num_rows($SelectDrawReqSql);
				}
			}
			$ExpMarkedToStr = explode(",",$MarkedTo);
			foreach($ExpMarkedToStr as $ToKey => $ToValue){
				array_push($ToSendIcno,$ToValue);
			}
			if($DrawReq == 0){  echo $DrawReq; 
				foreach($SecHeadArray as $SHKey => $SHValue){
					if(in_array($SHValue,$ToSendIcno)){
						
					}else{
						array_push($ToSendIcno,$SHValue);
					}
				}	
			}
			// echo $xy." ) "; print_r($ToSendIcno); echo $RefNoList->refno."<br/>"; $xy++;
			/// Mail Send Part
			$message = "<br/>";
			$message .= "<br/>";
			$message .= "Awaiting your action for the following Reference No. in Task Tracking System";
			$message .= "<br/>";
			$message .= "<b>".$RefNoList->refno."</b>";
			$message .= "<br/>";
			$message .= "<br/>";
			$message .= "<br/>";
			$message .= "With regards,";
			$message .= "<br/>";
			$message .= "Associate Director, CEG, IGCAR.";
			if(count($ToSendIcno)>0){
				foreach($ToSendIcno as $MailSedKey => $MailSendValue){
					$ToEmail = $EmailArray[$MailSendValue];
					$ToName  = $NameArray[$MailSendValue];
					$mail->ClearAllRecipients();
					$mail->addAddress($ToEmail, $ToName);
					//$mail->addAddress('nkl@igcar.gov.in', 'CEG, IGCAR');
					$mail->//AddCC('hari@igcar.gov.in');
					//$mail->AddCC('prabasingh@rediffmail.com');
					$mail->Subject = "Reminder : Markup Pending in Task Tracking System";
					$mail->Body = "Dear ".$ToName.", ".$message;
					//$mail->AltBody = $message;//'Dear Prabasingh, Thank you for your interest.';
					if(!$mail->send()) {
						////
						$Error = 1;
					}else{
						$FromMailIcno 	= '5023';//$_SESSION['tts_userid'];
						$ToMailIcno 	= $MailSendValue;
						$InsertSetMailQuery = "insert into sent_mail set from_email = '$FromMailStaffEmail', to_email = '$ToEmail', from_icno = '$FromMailIcno', to_icno = '$MailSendValue', mail_subject = 'MUP', mail_desc = '$message',  mail_attach = '', act_send_date = NOW(), to_be_send_on = NOW(), send_status = 'S', sent_date = NOW()";
						$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
					}
				}
			}
		}
	}
}
echo $Error;
}
?>