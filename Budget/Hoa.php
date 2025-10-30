<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator';
$msg = ""; $del = 0;
$RowCount =0; $InQueryCon = 0;
$staffid = $_SESSION['sid'];

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

$PinNo = '';
$PinId = '';
$SelectPinQuery = "SELECT * FROM pin";
$SelectPinSql 	= mysqli_query($dbConn,$SelectPinQuery);
if($SelectPinSql == true){
	if(mysqli_num_rows($SelectPinSql)>0){
		$CList = mysqli_fetch_object($SelectPinSql);
		$PinNo = $CList->pin_no;
		$PinId = $CList->pin_id;
	}
}


if(isset($_POST['btn_save']) == " Save "){

	$PinNo 	= $_POST["cmb_pin_no"];
	$HOANo	= $_POST["txt_hoa_no"];
	//$ObjHead = $_POST["txt_obj_head"];

	if($PinNo == NULL){
		$msg = "Please Select PIN Number..!!";
	}else if($HOANo == NULL){
		$msg = "Please Enter HOA Number..!!";
	}else{
		$InQueryCon = 1;
	}


	if($InQueryCon == 1){

		$insert_query = "insert into hoa set hoa_no='$HOANo', pin_id='$PinNo', active ='1'";
		$insert_sql = mysqli_query($dbConn,$insert_query);

		if($insert_sql == true){
			$msg = "Head Of Account Successfully Saved..!!";
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
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
						<div class="div12">&nbsp;</div>
						<div class="div12">
							<div class="div4">&nbsp;</div>
							<div class="div4 card-div-body">
								<div class="top-card">
									<!--<div class="top-card-header">sdsdf</div>-->
									<div class="top-card-container">
										<div class="div12">
											<div class="div6">
											<label for="name" class="card-label">PIN No. </label>
											</div>
											<div class="div6">
												<select name="cmb_pin_no" id="cmb_pin_no" class="card-label-selectbox-lg">
													<option value="">--- Select ---</option>
													<option value="<?php echo $PinId ?>"><?php echo $PinNo ?></option>
												</select>
											</div>
										</div>
										<div class="div12">&nbsp;</div>
										<div class="div12">
											<div class="div6">
											<label for="name" class="card-label">Head of Account No. </label>
											</div>
											<div class="div6">
												<input type="text" name="txt_hoa_no" id="txt_hoa_no" class="card-label-tbox-lg">
											</div>
										</div>
										<div class="div12">&nbsp;</div>
										<!-- <div class="div12">
											<div class="div6">
											<label for="name" class="card-label">Object Head</label>
											</div>
											<div class="div6">
												<input type="text" name="txt_obj_head" id="txt_obj_head" class="card-label-tbox-lg">
											</div>
										</div> -->
									</div>
								</div>
							</div>
							<div class="div4"></div>
						</div>
						<div style="text-align:center">
							<div class="buttonsection" style="display:inline-table">
								<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
							</div>
							<div class="buttonsection" style="display:inline-table">
								<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"/>
							</div>
						</div>
                    </blockquote>
                </div>
            </div>
        </div>
	</form>
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>

<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
</style>
</body>
</html>

