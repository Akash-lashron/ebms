<?php
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'mail/PHPMailerAutoload.php';
require_once 'common.php';
$errormessage = '';
if(isset($_GET['logout'])){	
	checkUser();
}
$OTPSent = 0;
if(isset($_POST["GenerateOTP"])){
	$Email 	= $_POST['txt_username'];
	$AdmRow = 0; $UsrRow = 0;
	$UserId	= "";
	$SelectAdminQuery = "select a.*, b.designationname, c.userid from ".$dbName2.".staff a inner join ".$dbName2.".designation b on (a.designationid = b.designationid) inner join ".$dbName.".users c on (a.staffid = c.staffid) where a.active = 1 and c.active = 1 and a.sectionid != 2 and c.isadmin = 1";
	$SelectAdminSql   = mysqli_query($dbConn,$SelectAdminQuery,$conn2);
	if($SelectAdminSql == true){
		if(mysqli_num_rows($SelectAdminSql)>0){
			$AdmRow 		= 1;
			$AdmList 		= mysqli_fetch_object($SelectAdminSql);
			$AdmStaffName 	= $AdmList->staffname;
			$AdmDesignation = $AdmList->designationname;
			$AdmInterCom 	= $AdmList->intercom;
		}
	}
	$SelectUserQuery = "select a.*, b.designationname, c.userid from ".$dbName2.".staff a inner join ".$dbName2.".designation b on (a.designationid = b.designationid) left join ".$dbName.".users c on (a.staffid = c.staffid) where a.active = 1 and c.active = 1 and a.sectionid != 2 and a.email = '$Email'";
	$SelectUserSql   = mysqli_query($dbConn,$SelectUserQuery,$conn2);
	if($SelectUserSql == true){ 
		if(mysqli_num_rows($SelectUserSql)>0){
			$UsrRow 		= 1;
			$UsrList 		= mysqli_fetch_object($SelectUserSql);
			$UsrStaffName 	= $UsrList->staffname;
			$UsrDesignation = $UsrList->designationname;
			$UserId 		= $UsrList->userid;
		}
	}
	//echo $SelectUserQuery;
	//exit;
	if($UserId != ""){
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
		
		/*$mail->Host = "10.1.1.2";//"smtp.gmail.com";
		$mail->Port = 25;//465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "jeyaraman@igcar.gov.in";//"nkl@igcar.gov.in";
		$mail->Password = "Mar04@ayej";//"bhk#1989$";//"hk@5023jp";//"nkfeb19";
		$mail->setFrom('hari@igcar.gov.in', 'Admin - Civil SOR');*/
		
		
		$mail->ClearAllRecipients();	
		//$mail->AddCC('hari@igcar.gov.in');
		
		$mail->addAddress($Email, $UsrStaffName);
		//$mail->addReplyTo('kprabasingh@gmail.com', 'Support');
		$mail->isHTML(true);
		$OTP = rand(100000,999999);
		$mail->Subject = 'Civil SOR - One Time Password';
		$mail->Body = "Dear ".$UsrStaffName.", <br/>Your one time login password for Civil SOR is <b>".$OTP."</b><br/>Note : This password will be valid for <b>180 seconds</b> (3 Minutes)<br/><b style='color:red'>Please do not reply this mail.</b>";//'New mail Checking';
		$mail->AltBody = $OTP;//'Dear Prabasingh, Thank you for your interest.';
		if(!$mail->send()) {
			$msg = "Error : OTP not generated. Please try again";
		}else{
			$OtpGenTime =  strtotime(date("Y-m-d H:i:s"));
			$UpdateQuery = "update users set otp = '$OTP', otp_sent_time = '$OtpGenTime' where userid = '$UserId'";
			$UpdateSql 	 = mysqli_query($dbConn,$UpdateQuery);
			$msg = "OTP has generated and successfully sent. Please check your E-Mail.";
			$OTPSent = 1;
		}
	}else{
		$msg = "Sorry, Your are not a authorized user. Please contact Administrator : Shri ".$AdmStaffName." - ".$AdmDesignation." (".$AdmInterCom.")";
	}
}
if(isset($_POST["submit"])){	
	$result = Login();
	if ($result != ''){	
		$msg = $result;
	}
	//echo $errormessage;exit;
}
$menuid = 1;
?>
<link href="login/bootstrap.min-4.1.1.css" rel="stylesheet" id="bootstrap-css">
<script src="login/bootstrap.min-4.1.1.js"></script>
<script src="login/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link href="login/bootstrap.min-3.3.0.css" rel="stylesheet" id="bootstrap-css">
<script src="login/bootstrap.min-3.3.0.js"></script>
<script src="login/jquery-1.11.1.min.js"></script>
<!-- Include the above in your HEAD tag -->
<link rel="stylesheet" href="login/font-awesome.min.css">
<link rel="stylesheet" href="login/login.css">
<div class="main">
    <div class="container">
		<center>
			<!--<div align="left" class="title">Civil Estimator</div>-->
			<div class="middle">
      			<div id="login">
					<div align="center" class="title"><img src="images/login-title.png" height="52" /><br/><span style="font-size:12px">Civil Engineering Group, IGCAR</span></div>
					
        			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          				<fieldset class="clearfix">
							<?php /*if($OTPSent == 1){ ?>
							<p><span class="fa fa-eye-slash" style="color:#08D1E5"></span><input type="password" name="txt_otp" autocomplete="off" id="txt_otp" Placeholder="One Time Password"></p> <!-- JS because of IE support; better: placeholder="Password" -->
          					<div class="msg-sec">Password will expire in <span id="second" class="second">90</span> seconds</div>
							<div>&nbsp;</div>
							<div><span class="btn-span2"><input type="submit" name="submit" id="submit" value="Sign In"></span></div>
							<input type="hidden" name="txt_userid" id="txt_userid" value="<?php echo $UserId; ?>" />
							<?php }else{ ?>
							<p><span class="fa fa-envelope" style="color:#08D1E5"></span><input type="email" name="txt_username" autocomplete="off" style="margin-bottom: 1em;" id="txt_username" Placeholder="Enter igcar email" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
             				<div>&nbsp;</div>
							<div><span class="btn-span1"><input type="submit" name="GenerateOTP" id="GenerateOTP" value="SEND OTP"></span></div>
							<?php }*/ ?>
							<p><span class="fa fa-envelope" style="color:#08D1E5; font-size:18px"></span><input type="text" name="txt_username" autocomplete="off" style="margin-bottom: 0.2em;" id="txt_username" Placeholder="Enter Your Email" required></p> <!-- JS because of IE support; better: placeholder="Username" -->
             				<div>&nbsp;</div>
							<p><span class="fa fa-lock" style="color:#08D1E5"></span><input type="password" name="txt_password" autocomplete="off" style="margin-bottom: 0.2em;" id="txt_password" Placeholder="Enter Your Password" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
             				<div>&nbsp;</div>
							<div><span class="btn-span2"><input type="submit" name="submit" id="submit" value="Sign In"></span></div>
						</fieldset>
						<div class="clearfix"></div>
        			</form>
        			<div class="clearfix"></div>
      			</div> <!-- end login login-logo -->
      			<div class="logo">
					<img src="images/igcar_logo_1.png" width="150" height="150" />
          			<div class="clearfix"></div>
      			</div>
      		</div>
			<div class="foot">Designed and Developed by Lashron Technologies</div>
		</center>
	</div>
</div>
<script>
var msg = "<?php echo $msg; ?>";
//document.querySelector('.main').onload = function(){
	if(msg != ""){
		alert(msg);//BootstrapDialog.alert(msg);
	}
//};
$(function(){
	/*$("#second").on("load",function(){
        var Expire = $(this).html();
		if(Expire != ""){
			while
		}
    });*/
	var incr = 0;
	var timer = setInterval(function () {
		$("#second").each(function() {
			var ExistValue = $(this).text(); 
			if(ExistValue != ""){
				var newValue = parseInt($(this).text(), 10) - 1;
				$(this).text(newValue);
				if(newValue == 0) {
				   $(".msg-sec").html("Password has expired <span id='second'></span>");
				   incr++;
				   clearInterval();
				}
			}
			if(incr > 0){
				setTimeout(function(){
					window.location.href = "login.php";
				}, 5000);
			}
		});
	}, 1000);
	
	/*if(incr > 0){
		setTimeout(function(){
			window.location.href = "login.php";
		}, 5000);
	}*/
});
</script>
<script>
	//history.replaceState( {} , 'CIVIL-SOR', '/CIVIL-SOR/' );
</script>
