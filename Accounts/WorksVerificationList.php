<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "library/common.php";
checkUser();
$msg = '';
$staffid 		= $_SESSION['sid'];
$staffid_acc 	= $_SESSION['sid_acc'];
$userid 		= $_SESSION['userid'];
$acc_levelid 	= $_SESSION['levelid'];
$section 		= $_SESSION['staff_section'];

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>

<link rel="stylesheet" href="dashboard/css/verticalTab.css">
<script src="dashboard/js/verticalTab.js"></script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
    <?php include "MainMenu.php"; ?>
   <div class="container_12">
       <div class="grid_12">
            <blockquote class="bq1" style="background-color:#FFFFFF; overflow:auto">
            	<form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="txt_test" id="txt_test" value="hai">
					<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>">
					<div class="div12" style="padding-left:40px;">
						<div class="accordion">
							<dl>
								<dt>
									<a href="#accordion<?php echo $sheetid; ?>" id="sheet-<?php echo $sheetid; ?>" aria-expanded="false" aria-controls="accordion<?php echo $TabId; ?>" class="accordion-title accordionTitle js-accordionTrigger blue-bg <?php if((isset($_SESSION['selected_sheet']))&&($_SESSION['selected_sheet'] == $sheetid)){ ?> is-collapsed is-expanded <?php } ?>">
										&nbsp;
										<font style="color:#DF0979; font-weight:bold; background:#fff; border:1px solid #DCDFE3; border-radius:7px; padding:2px;">
											<?php echo $SheetList->work_order_no; ?>
										</font>&nbsp;
										<font style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px;">
											C.C NO : <?php echo $SheetList->computer_code_no; ?>
										</font>
										&nbsp;&nbsp; 
										<?php echo " : "; ?> 
										<?php echo $SheetList->work_name; ?> 
										<font style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px;">
											Received Date : <?php echo $SheetList->rec_date; ?>
										</font>
										<font class="test" style="color:#F4003E; background:#edeaea; border-radius:5px; padding:2px; animation: blinker 1s linear infinite;"><i class="fa fa-hand-o-left blink_me" aria-hidden="true" style="padding-top:4px;"></i> Click Here</font>											 
									</a>
								</dt>
							</dl>
						</div>
						<div>&nbsp;</div>
	 					<div style="text-align:center; height:45px; line-height:45px; color:#07DCED" class="printbutton">
							<div class="buttonsection">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
						</div>
					</div>
     			</form>
   			</blockquote>
  		</div>
  	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
</html>