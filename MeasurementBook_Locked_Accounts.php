<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$staffid_acc = $_SESSION['sid_acc'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$locked_staff = $_GET['locked_staff'];
$select_staff_query = "SELECT staffname FROM staff where staffid ='$locked_staff' and active = 1";
//echo $select_staff_query;exit;
$select_staff_sql = mysql_query($select_staff_query);
if ($select_staff_sql == true)
{
	$Staff = mysql_fetch_object($select_staff_sql);
	$locked_staffname = $Staff->staffname;
}
//echo $select_staff_query;exit;
?>
<?php require_once "Header.html"; ?>
	<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
	<script type='text/javascript' src='js/basic_model_jquery.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
	function ViewMBook(obj)
	{
		var id = obj.id;
		$("#txt_post_id").val(id);
		$("#form_mbook").submit();
	}
	/*jQuery(function ($) 
	{
		$('#basic-modal-content').modal();
	});*/
</script>
<style>
	.gradientbg 
	{
	  background-color: #014D62;
	  background: url(images/linear_bg_2.png);
	  background-repeat: repeat-x;
	  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#EDEEF1), to(#F8F9FB));
	  background: -webkit-linear-gradient(top, #EDEEF1, #F8F9FB);
	  background: -moz-linear-gradient(top, #EDEEF1, #F8F9FB);
	  background: -ms-linear-gradient(top, #EDEEF1, #F8F9FB);
	  background: -o-linear-gradient(top, #EDEEF1, #F8F9FB);
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
   <div class="container_12">
       <div class="grid_12">
            <blockquote class="bq1" style="background-color:#FFFFFF;">
                <div class="title">Measurement Book Locked</i></div>
					<br/>
                     <form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="container">
							<input type="hidden" name="txt_locked_staff" id="txt_locked_staff" value="<?php echo $locked_staffname; ?>">
							<!--<div id="basic-modal-content" style="height:200px;">
								<div align="center" class="popuptitle gradientbg">Accounts Section - Comment </div>
								<div style="float:left; padding-top:4px; width:267px;">
								</div>
							</div>-->
							<script>
								/*swal({
									  title: "Locked",
									  text: "This MBook is Locked by",
									  imageUrl: "images/thumbs-up.jpg"
									  },
										function(){
										  swal("Deleted!", "Your imaginary file has been deleted.", "success");
									});*/
									var locked_staffname = "<?php echo $locked_staffname; ?>";
									swal({
										  title: "Locked !",
										  text: "This Measurement Book is Locked by "+locked_staffname,
										  type: "",
										  imageUrl: "images/lock_1.png",
										  //showCancelButton: true,
										  //confirmButtonColor: "#DD6B55",
										  confirmButtonText: " Ok ",
										  closeOnConfirm: false
										},
										function(){
										  //swal("Deleted!", "Your imaginary file has been deleted.", "success");
										  	url = "MeasurementBookPrint_staff_Accounts.php";
											window.location.replace(url);
										});
							</script>
						</div>
	 					<!--<div style="text-align:center; height:45px; line-height:45px; color:#07DCED" class="printbutton">
							<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
						</div>-->
     				</form>
   			</blockquote>
  		</div>
  	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
</html>
