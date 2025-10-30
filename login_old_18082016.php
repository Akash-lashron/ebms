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
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
    <!--[if lt IE 8]>
		<div style=' clear: both; text-align:center; position: relative;'>
			<a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
			<img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
			</a>
		</div>
		<![endif]-->
		<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<link rel="stylesheet" media="screen" href="css/ie.css">
		<![endif]-->
<head>
  <meta charset="utf-8">
  <link href="css/piecemaker/style.css" rel="stylesheet" type="text/css"/>
   <!-- SLIDER FLASH -->                                                               
           <script type="text/javascript" src="js/swfobject.js"></script>      
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo $pagetitle; ?> </title>
  <link href="<?php echo WEB_ROOT;?>css/loginstyle.css" rel="stylesheet" type="text/css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <SCRIPT type="text/javascript">
    window.history.forward();
    function HandleBackButton() { window.history.forward(); }
</SCRIPT>
<style>

.title
{
	 	position: relative;
	  	margin: 0 auto;
	  	width: 354px;
	  	/*background: rgba(0, 0, 0, 0.08) none repeat scroll 0 0;*/
		background-color:#FBFBFB;
		padding:5px 5px 5px;
	  	border-radius: 3px;
	  	/*-webkit-box-shadow: 0 0 200px rgba(255, 255, 255, 0.5), 0 1px 2px rgba(0, 0, 0, 0.3);#F00232
	  	box-shadow: 0 0 200px rgba(255, 255, 255, 0.5), 0 1px 2px rgba(0, 0, 0, 0.3);*/
		color:#FFFFFF;	
		font-weight:bolder;
		bottom: -8px;
		top: -21px;
		font-family:Geneva, Arial, Helvetica, sans-serif;
		letter-spacing: 2px;
		height:34px;
}
.titlehead
{
	height:6px;
	font-family:Helvetica, sans-serif, Verdana, Arial;
}

</style>
</head>
<BODY onLoad="HandleBackButton();"   onpageshow="if (event.persisted) HandleBackButton();" onUnload="">
  <section class="container">
     <!--<img src="images/logo.png" ><br/> <br/>--> 
	 		<div class="title" align="center"><img src="images/ebms_3.png" height="32" >
				<div class="titlehead">
				<!--<img src="images/ebms_login_title.png" height="16" width="331" >-->
				<span style="text-align:center; font-family:Arial, Arial, Helvetica, sans-serif; color:#00008b; font-size:11px; font-style:bold; font-weight:bold; letter-spacing:1px">
				Electronic Billing Measurement System
				</span>
				</div>
			</div><br/>
    <div class="login">
		  <!--<div class="title" style=" border-bottom:none; height:50px;">yhr6u67yi7i</div>-->
      <h1>Login</h1>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="form" name="form">
          <div class="errorMessage" align="center"><?php echo $errormessage; ?></div>
          <input type="text" id="username" name="username" value="" placeholder="Username" class="inputcolor"> 
          <input type="password" id ="password" name="password" value="" placeholder="Password" maxlength="8" class="inputcolor">
               <p class="submit"><input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Submit" /></p>
 </form>
    </div>  </section>
       <!-- START SLIDER -->
      
<!--                <div id="slider" class="inner flash">
                    <div id="piecemaker"></div>
                </div>
                <script type="text/javascript">
                    var flashvars = {};
                    flashvars.cssSource = "css/piecemaker/piecemaker.css";
                    flashvars.xmlSource = "css/piecemaker/piecemaker.xml";
                    
                    var flash_params = {};
                    flash_params.play = "true";
                    flash_params.menu = "false";
                    flash_params.scale = "showall";
                    flash_params.wmode = "transparent";
                    flash_params.allowfullscreen = "true";
                    flash_params.allowscriptaccess = "always";
                    flash_params.allownetworking = "all";
                    
                    swfobject.embedSWF('css/piecemaker/piecemaker.swf', 'piecemaker', '920', '390', '10', null, flashvars, flash_params, null);
                    
                </script> -->
                <!-- END #slider -->    


</body>
</html>
