<?php
require 'PHPMailerAutoload.php';
@ob_start();
require_once '../library/config.php';
if($_SESSION['tts_userid'] == '5023'){
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

$WcmsRoot = '';
$path = 'attachment/';
function getImagesFromDir($path) {
    $images = array();
    if ( $img_dir = @opendir($path) ) {
        while ( false !== ($img_file = readdir($img_dir)) ) {
            // checks for gif, jpg, png
            if ( preg_match("/(\.gif|\.jpg|\.png)$/", $img_file) ) {
                $images[] = $img_file;
            }
        }
        closedir($img_dir);
    }
    return $images;
}

function getRandomFromArray($ar) {
    //mt_srand( (double)microtime() * 1000000 ); // php 4.2+ not needed
    $num = array_rand($ar);
    return $ar[$num];
}
$imgList 	= getImagesFromDir($WcmsRoot.$path);
$Attachment = getRandomFromArray($imgList);

$subject = "Birthday Wishes From Assciate Director, CEG, IGCAR";
$message = "Wishing You a Happy Birthday";
$BDayCnt = 0; 
$PrevMonthStr 	= strtotime("-1 Months"); 
$PrevMonthYear 	= date("Y-m", $PrevMonthStr);
$CurrMonthYear 	= date("Y-m");
$PrevMonth 		= date("m", $PrevMonthStr);
$CurrMonth 		= date("m");
$PrevYear 		= date("Y", $PrevMonthStr);
$CurrYear 		= date("Y");
$Today 			= date("Y-m-d");
$BDAYListArr    = array();
$SelectBDayListQuery = "(select staff_icno as receiver from ceg_staff where 
						active = 1 AND (DATE_FORMAT(staff_dob,'%m') = '$PrevMonth' OR DATE_FORMAT(staff_dob,'%m') = '$CurrMonth'))
						UNION(select to_icno as receiver from sent_mail where mail_subject = 'BDAY' and to_be_send_on = '$Today')";	
//echo $SelectBDayListQuery;exit;
$SelectBDayListSql   = mysqli_query($dbConn,$SelectBDayListQuery);
if($SelectBDayListSql == true){
	if(mysqli_num_rows($SelectBDayListSql)>0){
		$BDayCnt = 1;
		while($BDayICNOList = mysqli_fetch_object($SelectBDayListSql)){
			if(in_array($BDayICNOList->receiver, $BDAYListArr)){
				//Already Exist
			}else{
				array_push($BDAYListArr,$BDayICNOList->receiver);
			}
		}
	}
}
$BDayCnt = count($BDAYListArr);

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
$mail->Password = $FromMailStaffEPass;//"akpsingh@2070";
$mail->setFrom('praba@lashron.com', 'PRABA');*/
/*================================================================================================= */
$Error = "";	
if($BDayCnt > 0){
	foreach($BDAYListArr as $Key => $Value){
		$BDayIcNo = $Value;
		$SelectDetailsQuery = "select * from ceg_staff where staff_icno = '$BDayIcNo'";
		$SelectDetailsSql = mysqli_query($dbConn,$SelectDetailsQuery);
		if($SelectDetailsSql == true){
			if(mysqli_num_rows($SelectDetailsSql)>0){
				$BDayList = mysqli_fetch_object($SelectDetailsSql);
				$DOB = $BDayList->staff_dob;
				$ICNO = $BDayList->staff_icno;
				$ToEmail = $BDayList->staff_email;
				$ToName = $BDayList->staff_name;
				$DOBMonthDay = date("m-d",strtotime($DOB));
				$DOBMonth = date("m",strtotime($DOB));
				if($DOBMonth == $PrevMonth){
					$CheckDate = $PrevYear."-".$DOBMonthDay;
					$CheckYr = $PrevYear;//."-".$DOBMonth;
				}else if($DOBMonth == $CurrMonth){
					$CheckDate = $CurrYear."-".$DOBMonthDay;
					$CheckYr = $CurrYear;//."-".$DOBMonth;
				}else{
					$CheckDate = "";  $CheckYr = "";
				}
				
				$CheckMailSentQuery = "select id, send_status, mail_desc, mail_attach from sent_mail where to_icno = '$ICNO' and mail_subject = 'BDAY' and (to_be_send_on = '$Today' OR DATE_FORMAT(to_be_send_on, '%Y') = '$CheckYr')";// and to_be_send_on = '$Today'";
				$CheckMailSentSql = mysqli_query($dbConn,$CheckMailSentQuery);
				if($CheckMailSentSql == true){
					if(mysqli_num_rows($CheckMailSentSql)>0){
						$CheckList = mysqli_fetch_object($CheckMailSentSql);
						$SendStatus = $CheckList->send_status;
						if($CheckList->mail_attach == ""){
							//$Attachment = "DefaultAttachment.jpg";
							$imgList 	= getImagesFromDir($WcmsRoot.$path);
							$Attachment = getRandomFromArray($imgList);
						}else{
							$Attachment = $CheckList->mail_attach;
						}
						$message = $CheckList->mail_desc; 
						if($SendStatus == 'P'){
							$Send = 1;
							$Action = "U";
							$id = $CheckList->id;
						}else if($SendStatus == 'S'){
							$Send = 0;
							$Action = "";
						}
					}else{
						if($CheckDate <= "$Today"){
							$Send = 1;
							$Action = "I";
						}else{
							$Send = 0;
							$Action = "";
						}
					}
				}
				//$OUT .= $ICNO."*".$Send."*".$Action;
				if($Send == 1){
					$mail->ClearAllRecipients();
					$mail->addAddress($ToEmail, $BDName);
					$mail->AddCC('hari@igcar.gov.in');
					//$mail->AddCC('prabasingh@rediffmail.com');
					$mail->AddAttachment("Attachment/".$Attachment);
					$mail->AddEmbeddedImage('Attachment/'.$Attachment,'wishes');
					$mail->Subject = $subject;
					$mail->Body = $message."<br/><img src='cid:wishes'>";//'New mail Checking';
					$mail->AltBody = $message;//'Dear Prabasingh, Thank you for your interest.';
					if(!$mail->send()) {
						////
						$Error = 1;
					}else{
						$FromMailIcno 	= $_SESSION['tts_userid'];
						$ToMailIcno 	= $staffid;
						if($Action == "I"){
							$InsertSetMailQuery = "insert into sent_mail set from_email = '$FromMailStaffEmail', to_email = '$ToEmail', from_icno = '$FromMailIcno', to_icno = '$ICNO', mail_subject = 'BDAY', mail_desc = '$message',  mail_attach = '', act_send_date = NOW(), to_be_send_on = NOW(), send_status = 'S', sent_date = NOW()";
							$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
						}
						if($Action == "U"){
							$InsertSetMailQuery = "update sent_mail set send_status = 'S', sent_date = NOW() where id = '$id'";
							$InsertSetMailSql   = mysqli_query($dbConn,$InsertSetMailQuery);
						}
					}
					$mail->clearAttachments();
				}
			}
		}
	}	
}
echo $Error;
}
?>