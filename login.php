<?php
require_once 'library/config.php';
require_once 'library/declaration.php';
require_once 'library/functions.php';
$errormessage = '&nbsp;';
 if (isset($_GET['logout'])) {	 checkUser();}
if (isset($_POST["submit"])) {	$result = Login();	
    if ($result != '') {	$errormessage = $result;}
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  	<title><?php echo $pagetitle; ?> </title>
	<link href="<?php echo WEB_ROOT;?>css/font-awesome.css" rel="stylesheet" type="text/css">
  	<script type="text/javascript">
    	window.history.forward();
    	function HandleBackButton() { window.history.forward(); }
	</script>
</head>

<!--<link href="assets/css/CheckBoxStyle.css" rel="stylesheet" type="text/css" media="all" />
<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/bar.css">
<link rel="stylesheet" type="text/css" href="assets/css/pignose.calender.css" />
<link href="assets/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/style4.css">
<link href="assets/css/fontawesome-all.css" rel="stylesheet">
<link href="assets/fonts/fonts.css" rel="stylesheet">
<link href="assets/fonts/font-family.css" rel="stylesheet">
<link href="assets/bootstrap-dialog/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet">
<link href="assets/css/chosen.min.css" rel="stylesheet" type="text/css" media="all" />
<link href="assets/Stepwizard/BSMagic-min.css" rel="stylesheet">
<link href="assets/css/ipInput.css" rel="stylesheet">
<link href="assets/css/alert-modal-style.css" type="text/css" rel="stylesheet" media="all">-->

<link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="assets/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="assets/css/style4.css">
<link href="assets/css/fontawesome-all.css" rel="stylesheet">
<link href="assets/fonts/fonts.css" rel="stylesheet">
<link href="assets/bootstrap-dialog/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet">
<link href="assets/Stepwizard/BSMagic-min.css" rel="stylesheet">


<!--<link rel="stylesheet" type="text/css" href="assets/GanttChart/jquery-ui-1.8.4.css" /> -->


<script src="assets/js/jquery-2.2.3.min.js"></script>
<script src="assets/bootstrap.min-4.1.3.jsjs/"></script>
<script src="assets/js/chosen.jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/bootstrap-dialog/js/bootstrap-dialog.min.js"></script>


<style>
html 
{
    height: 98%;
	margin:1px;
}
.bodystyle 
{
    /*background-image:url("images/login_background_ps6.png");*/   /*  option 2  */
	/*background-image:url("images/login_blue_ps1.png"); Existing*/   /*  selected option 1  */
	/*background-image:url("images/login_with_pink_img.png");*/
	/*background-image:url("images/login_with_pink.png");*/   /* option 3  */
	background-image:url("images/login_blue_ps1.png");
    background-repeat: no-repeat;
    background-size: 100% 100%;
	background-position:center;
}
</style>
<BODY onLoad="HandleBackButton();"   onpageshow="if (event.persisted) HandleBackButton();" onUnload="" bgcolor="#000000">
<div class="bodystyle">
	<div class="loginsection" align="center" style="display:block;">
		<!--<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="form" name="form">
			<div class="titlehead">Electronic Billing Measurement System</div>
			<div class="loginboxhead">&nbsp;</div>
			<div class="loginboxhead"><i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp;Login</div>
			<div class="errorMessage" align="center"><?php echo $errormessage; ?></div>
			<div class="loginboxhead"><input type="text" name="username" id="username" placeholder="Enter your username"></div>
			<div class="loginboxhead"><input type="password" name="password" id ="password" placeholder="Enter your password"></div>
			<div class="loginboxhead" style="height:3px;">&nbsp;</div>
			<div class="loginboxhead"><input type="submit" name="submit" id="submit" data-type="submit" value="Submit" /></div>
		</form>-->
		
	</div>
	<!--<div class="loginboxfooter">
		Lashron Technologies :: Copyright of FRFCF, IGCAR, Department of Atmoic Energy, Kalpakkam. Unauthorized distribution and/or duplication is prohibited.
	</div>-->
</div>
<style>
#myModal{
    box-sizing:border-box;
    padding:0px !important;
}
.login-modal{
    width:400px !important;
    border-radius:5px;
}
.login-bradius{
    border-radius:0px 0px 0px 0px;
}
.modal-backdrop.in {
  filter: alpha(opacity=50);
  opacity: 0.8;
}


.login-wrap {
  position: relative;
  background: #000232;
  border-radius: 5px;
  padding-left: 30px;
  padding-right: 30px;
  padding-top: 20px !important;
  -webkit-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
  -moz-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
  box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
}
.login-wrap .img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  margin: 0 auto;
    margin-bottom: 0px;
  margin-bottom: 20px;
  background-size: cover;
background-repeat: no-repeat;
background-position: center center;
margin-top:12px;

}

.form-control {
  height: 35px;
  background: rgba(0, 0, 0, 0.05);
  color: #fff !important;
  font-size: 16px;
  -webkit-box-shadow: none;
  box-shadow: none;
  border-radius: 0;
  border: none;
    border-bottom-color: currentcolor;
    border-bottom-style: none;
    border-bottom-width: medium;
  border-bottom: 1px solid #00bcd4;
  padding-left: 30px;
  padding-right: 0;
  letter-spacing: 1px;
  -webkit-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
  font-family: 'Poiret One', cursive;
}

.form-control:focus{
	color:#000;
	border-top:none !important;
	border-left:none !important;
	border-right:none !important;
	outline:0px;
	border-bottom: 1px solid #00bcd4;
}

.form-group {
  position: relative;
}

.form-group .icon span {
  color: #fff;
}
*, ::before, ::after {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}
.d-flex {
  display: -webkit-box !important;
  display: -ms-flexbox !important;
  display: flex !important;
}
.justify-content-center {
  -webkit-box-pack: center !important;
  -ms-flex-pack: center !important;
  justify-content: center !important;
}
.align-items-center {
  -webkit-box-align: center !important;
  -ms-flex-align: center !important;
  align-items: center !important;
}
.form-group .icon {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 20px;
  height: 48px;
  background: transparent;
  font-size: 18px;
}
.login-wrap h3 {
  font-weight: 600;
  font-size: 28px;
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 1px;
}
.login-wrap p {
  color: #fff;
  font-family: "Lato", Arial, sans-serif;
  font-size: 12px;
  margin-top:4px;
  margin-bottom:20px;
  font-style:italic;
}
.text-center {
  text-align: center !important;
}
.ibtn{
    color:#000232 !important;
}
.modal-header{
	display:none;
}
.modal-dialog {
  width: 400px;
  margin-top:20px;
}
.modal-body{
	min-height:410px;
	padding:0px;
	overflow:hidden;
	
}
.login-modal{
	-webkit-box-shadow: 0 0px 114px rgba(0, 0, 0,1);
	box-shadow: 0 0px 114px rgba(0, 0, 0,1);
	height:412px;
	width: 400px;
  margin-top:0px;
  margin-bottom:0px;
  margin-left:0px;
  margin-left:0px;
  top:0px;
  overflow:hidden;
}
.logo-title{
	color:#fff;
	letter-spacing: 0.4px;
	/*letter-spacing: 1px;
	font-size: 18px;*/
	/*font-family:"Roboto Condensed";
	font-size:20px;*/
	/*font-family:Georgia, "Times New Roman", Times, serif;
	font-size:18px;*/
	/*font-family:*/
	font-weight:200;
	font-family:Roboto;
	font-size:18px;
	font-family:Georgia, verdana;
}
h7{
	text-align:center;
	font-size:12px;
}
</style>


<script>
	BootstrapDialog.show({
		message: $('<div></div>').load('assets/Header.html'),
		closable: true,
		closeByBackdrop: false,
		closeByKeyboard: false,
	});
	function autoResizeDiv(){
		var x = document.getElementsByClassName("bodystyle");
			x[0].style.height = (window.innerHeight*97/100)+"px";
		var z = document.getElementsByClassName("loginsection");
			z[0].style.paddingTop = (window.innerHeight*94/275)+"px";
		var ht1 = document.getElementsByClassName("loginsection");
		var h1 = ht1[0].style.height
	}
	window.onresize = autoResizeDiv;
	autoResizeDiv();
</script>
</body>
</html>
