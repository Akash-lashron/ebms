<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require_once 'mail/PHPMailerAutoload.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'SOR Rate Confirm';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
//echo $_SESSION['sid'];exit;
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
$ViewBox = 1; $Start = 0;
if(isset($_POST['btn_generate']) == " Generate OTP "){
	$AdmRow = 0; $UsrRow = 0;
	$AdmEmailId	= "";
	$SelectAdminQuery = "select a.*, b.designationname, c.userid from ".$dbName2.".staff a inner join ".$dbName2.".designation b on (a.designationid = b.designationid) inner join ".$dbName.".users c on (a.staffid = c.staffid) where a.active = 1 and c.active = 1 and a.sectionid != 2 and c.staffid = ".$_SESSION['sid'];
	$SelectAdminSql   = mysqli_query($dbConn,$SelectAdminQuery);
	if($SelectAdminSql == true){
		if(mysqli_num_rows($SelectAdminSql)>0){
			$AdmRow 		= 1;
			$AdmList 		= mysqli_fetch_object($SelectAdminSql);
			$AdmStaffName 	= $AdmList->staffname;
			$AdmDesignation = $AdmList->designationname;
			$AdmInterCom 	= $AdmList->intercom;
			$AdmEmailId 	= $AdmList->email;
		}
	}
	//echo $AdmEmailId;
	//exit;
	if($AdmEmailId != ""){
		$message = $Email;
		$mail 	= new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "prabasinghkathirvel@gmail.com";
		$mail->Password = "akpsingh@2070";
		$mail->setFrom('prabasinghkathirvel@gmail.com', 'Admin - Civil SOR');
		//$mail->ClearAllRecipients();	
		//$mail->AddCC('hari@igcar.gov.in');
		
		//$mail->addAddress($AdmEmailId, $AdmStaffName);
		
		/*$mail->Host = "10.1.1.2";//"smtp.gmail.com";
		$mail->Port = 25;//465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "jeyaraman@igcar.gov.in";//"nkl@igcar.gov.in";
		$mail->Password = "Mar04@ayej";//"bhk#1989$";//"hk@5023jp";//"nkfeb19";
		$mail->setFrom('hari@igcar.gov.in', 'Admin - Civil SOR');*/

		$mail->ClearAllRecipients();	
		$mail->addAddress($AdmEmailId, $AdmStaffName);
		//$mail->addReplyTo('kprabasingh@gmail.com', 'Support');
		$mail->isHTML(true);
		$OTP = rand(100000,999999);
		$mail->Subject = 'Civil SOR - One Time Password';
		$mail->Body = "Dear ".$AdmStaffName.", <br/>Your one time password for Civil SOR - Rate Confirmation Access is <b>".$OTP."</b><br/>Note : This password will be valid for <b>180 seconds</b> (3 Minutes)<br/><b style='color:red'>Please do not reply this mail.</b>";//'New mail Checking';
		$mail->AltBody = $OTP;//'Dear Prabasingh, Thank you for your interest.';
		if(!$mail->send()) {
			//$msg = "Error : OTP not generated. Please try again";
			$OtpGenTime =  strtotime(date("Y-m-d H:i:s"));
			$InsertQuery = "insert into sor_acc set otp = '$OTP', access_type = 'SRCA', access_email = '$AdmEmailId', access_date = NOW(), staffid = ".$_SESSION['sid'];
			$InsertSql 	 = mysqli_query($dbConn,$InsertQuery);
			$msg = "OTP has generated and successfully sent. Please check your E-Mail.";
			$ViewBox = 2;
			$Start = 1;
		}else{
			$OtpGenTime =  strtotime(date("Y-m-d H:i:s"));
			$InsertQuery = "insert into sor_acc set otp = '$OTP', access_type = 'SRCA', access_email = '$AdmEmailId', access_date = NOW(), staffid = ".$_SESSION['sid'];
			$InsertSql 	 = mysqli_query($dbConn,$InsertQuery);
			$msg = "OTP has generated and successfully sent. Please check your E-Mail.";
			$ViewBox = 2;
			$Start = 1;
		}
	}else{
		$msg = "Sorry, Your are not a authorized user. Please contact Administrator : Shri ".$AdmStaffName." - ".$AdmDesignation." (".$AdmInterCom.")";
	}
}else if(isset($_POST['btn_next']) == " Next "){
	$OTP 		= $_POST['txt_otp'];
	if($OTP != ""){
		$SelectQuery = "select * from sor_acc where access_type = 'SRCA' and otp = '$OTP'";
		$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true){
			if(mysqli_num_rows($SelectSql)>0){
				$DeleteQuery = "delete from sor_acc where access_type = 'SRCA' and staffid = ".$_SESSION['sid'];
				$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
				header("Location: DataSheetConfirmWaitingList.php");
			}
		}
	}
	$DeleteQuery = "delete from sor_acc where access_type = 'SRCA' and staffid = ".$_SESSION['sid'];
	$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
	$ViewBox = 1;
}else{
	$DeleteQuery = "delete from sor_acc where access_type = 'SRCA' and staffid = ".$_SESSION['sid'];
	$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
}
?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
	<script src="dashboard/MyView/bootstrap.min.js"></script>
	<script type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</script>
	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row <?php if($ViewBox == 2){ echo "hide"; } ?>">
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-sb" align="center"> Schedule of Rate Confirm - Access</div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div12 cboxlabel" align="center">
												Click below button to generate One Time Password (OTP)<br/>
												<span style="color:#DC0541; font-size:10px">( OTP will be sent to your Email-id )</span>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row" align="center">
												<input type="submit" name="btn_generate" id="btn_generate" class="btn btn-info" value=" Generate OTP ">
											</div>
											<div class="row clearrow"></div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">&nbsp;</div>
							</div>
							<div class="row <?php if($ViewBox == 1){ echo "hide"; } ?>">
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-sb" align="center"> Schedule of Rate Confirm - Access</div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div6 lgboxlabel">Enter Your One Time Password (OTP <?php echo $OTP; ?>)</div>
												<div class="div5">
													<input type="text" name="txt_otp" id="txt_otp" class="tboxclass" value="">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div12 cboxlabel" align="center">
												<span style="color:#DC0541; font-size:10px">( Your One Time Password will be valid for only <span id="timer" style="color:red; font-weight:bold; font-size:14px; color:#000"></span> Mins. )</span>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row" align="center">
												<input type="submit" name="btn_next" id="btn_next" class="btn btn-info" value=" Next ">
											</div>
											<div class="row clearrow"></div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">&nbsp;</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
			<input type="hidden" name="txt_time_start" id="txt_time_start" value="<?php echo $Start; ?>">
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
	$('body').on("click","#btn_next", function(event){ 
		var OneTimePassword = $("#txt_otp").val();
		if(OneTimePassword == ""){
			BootstrapDialog.alert("Error : Please enter one time password");
			event.preventDefault();
			event.returnValue = false;
		}
	});
	var TimeStart = $("#txt_time_start").val();
	if(TimeStart == 1){
		timer(180);
	}
});
var timerOn = true;
function timer(remaining) {
  var m = Math.floor(remaining / 60);
  var s = remaining % 60;
  m = m < 10 ? '0' + m : m;
  s = s < 10 ? '0' + s : s;
  document.getElementById('timer').innerHTML = m + ':' + s;
  remaining -= 1;
  if(remaining >= 0 && timerOn) {
    setTimeout(function() {
        timer(remaining);
    }, 1000);
    return;
  }
  if(!timerOn) {
    // Do validate stuff here
    return;
  }
  BootstrapDialog.alert('Timeout for otp');
}
</script>
