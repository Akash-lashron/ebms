<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "common.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
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
if(isset($_POST['btn_save']) == " SAVE & NEXT "){
	$SaveRecPayType = $_POST['cmb_payment_recovery'];
	$SaveRecPayCode = $_POST['txt_code'];
	$SaveRecPayDesc = $_POST['txt_desc'];
	$SaveRecPayRecoveryType = $_POST['cmb_rec_type'];
	$SavePrId = $_POST['txt_prid'];
	
	if($SavePrId != ''){
		$InsertQuery1 	= "UPDATE pay_rec_master SET pr_type = '$SaveRecPayType', prcode = '$SaveRecPayCode', pr_desc = '$SaveRecPayDesc', active = '1', rec_type = '$SaveRecPayRecoveryType' WHERE prid = '$SavePrId'";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}else{
		$InsertQuery1 	= "INSERT INTO pay_rec_master SET pr_type = '$SaveRecPayType', prcode = '$SaveRecPayCode', pr_desc = '$SaveRecPayDesc', active = '1', rec_type = '$SaveRecPayRecoveryType'";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}
	if($InsertSql1 == true){
		$msg = "Recovery / Payment description saved successfully";
	}else{
		$msg = "Error : Recovery / Payment description not saved. Please try again.";
	}
}
if(isset($_GET['id'])){
	$EditRow = $_GET['id'];
	$SelectQuery = "SELECT * FROM pay_rec_master WHERE prid = '$EditRow'";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$List = mysqli_fetch_object($SelectSql);
			$PrId = $List->prid;
			$PrType = $List->pr_type;
			$PrCode = $List->prcode;
			$PrDesc = $List->pr_desc;
			$PrRecType = $List->rec_type;
		}
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script>
	function goBack()
	{
	   	url = "AccountsStatementSteps.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
						<div class="row clearrow"></div>
                        <form name="form" method="post" action="">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">&nbsp;Payment / Recovery Description Creation</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
												
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="row">
																		<div class="row clearrow"></div>
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">Payment / Recovery</div>
																		<div class="div8" align="left">
																			<select name="cmb_payment_recovery" id="cmb_payment_recovery" class="tboxsmclass">
																				<option value="">----- Select -----</option>
																				<option value="P" <?php if(isset($_GET['id'])){ if($PrType == "P"){ echo 'selected="selected"'; } } ?>>PAYMENT</option>
																				<option value="R" <?php if(isset($_GET['id'])){ if($PrType == "R"){ echo 'selected="selected"'; } } ?>>RECOVERY</option>
																		   	</select>
																		</div>
																		<div class="div12"></div>
																		<div class="row clearrow"></div>
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">Description</div>
																		<div class="div8" align="left">
																			<input type="text" name="txt_desc" id="txt_desc" required class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrDesc != ""){ echo $PrDesc; } } ?>">
																		</div>
																		<div class="div12"></div>
																		<div class="row clearrow"></div>
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">Code</div>
																		<div class="div8" align="left">
																			<input type="text" name="txt_code" id="txt_code" required class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrCode != ""){ echo $PrCode; } } ?>">
																		</div>
																		<div class="div12"></div>
																		<div class="row clearrow"></div>
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">Recovery Type</div>
																		<div class="div8" align="left">
																			<select name="cmb_rec_type" id="cmb_rec_type" class="tboxsmclass">
																				<option value="">----- Select -----</option>
																				<option value="A" <?php if(isset($_GET['id'])){ if($PrRecType == "A"){ echo 'selected="selected"'; } } ?>>PART A</option>
																				<option value="B" <?php if(isset($_GET['id'])){ if($PrRecType == "B"){ echo 'selected="selected"'; } } ?>>PART B</option>
																		   	</select>
																		</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div8 errtext" id="val_work" align="left">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="row clearrow"></div>
																		<div class="div12">
																			<input type="hidden" name="txt_prid" id="txt_prid" class="tboxsmclass" value="<?php if(isset($_GET['id'])){ if($PrId != ""){ echo $PrId; } } ?>">
																			<input type="submit" class="btn btn-info" value=" SAVE " name="btn_save" id="btn_save"/>
																			<a href="RecoveryPaymentDescriptionList.php" class="btn btn-info" name="btn_view" id="btn_view"> View All </a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
							</div>
						</div>
       				</form>
      			</blockquote>
    		</div>
   		</div>
	</div>
	<link rel="stylesheet" href="css/timeline.css">
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
$("#cmb_payment_recovery").chosen();
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$(function() {

});
</script>

</body>
</html>

