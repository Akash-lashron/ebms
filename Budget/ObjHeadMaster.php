<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Object Head-Entry';
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
	$PinId 	= $_POST["cmb_pin_no"];
	//$HOANo	= $_POST["txt_hoa_no"];
	$ObjHead = $_POST["txt_obj_head"];
	$SelDisc	= $_POST["cmb_disc"];

	$ImplodeDisc = implode(",",$SelDisc);
	if($PinNo == NULL){
		$msg = "Please Select PIN Number..!!";
	}else if($ObjHead == NULL){
		$msg = "Please Enter Object Head..!!";
	}else if($SelDisc == NULL){
		$msg = "Please Select Discipline..!!";
	}else{
		$InQueryCon = 1;
	}

	if($InQueryCon == 1){

		$insert_query = "insert into object_head set pin_id='$PinId', obj_head='$ObjHead', discid='$ImplodeDisc', active ='1'";
		$insert_sql = mysqli_query($dbConn,$insert_query);
		if($insert_sql == true){
			$msg = "Object Head Saved Successfully..!!";
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
	   	url = "Administrator.php";
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
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							
						<!----------===================================== HIDDEN FIELDS =========================================--------->
							<input type="hidden" name="staffid" id="staffid" value="<?php echo $_GET['staffid']; ?>"> 
								<div class="row">
									<div class="box-container box-container-lg" align="center">
										<div class="div2">&nbsp;</div>
										<div class="div8">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="center">Object Head - Create</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="row">


																<div class="div12">&nbsp;</div>
																<div class="div12">
																	<div class="div4">
																	<label for="name" class="card-label">PIN No. </label>
																	</div>
																	<div class="div8">
																		<select name="cmb_pin_no" id="cmb_pin_no" class="card-label-selectbox-lg">
																			<option value="<?php echo $PinId ?>"><?php echo $PinNo ?></option>
																		</select>
																	</div>
																</div>
																<div class="div12">&nbsp;</div>
																<!-- <div class="div12">
																	<div class="div6">
																	<label for="name" class="card-label">Head of Account No. </label>
																	</div>
																	<div class="div6">
																		<input type="text" name="txt_hoa_no" id="txt_hoa_no" class="card-label-tbox-lg">
																	</div>
																</div> -->
																<div class="div12">
																	<div class="div4">
																	<label for="name" class="card-label">Object Head</label>
																	</div>
																	<div class="div8">
																		<input type="text" name="txt_obj_head" id="txt_obj_head" class="card-label-tbox-lg">
																	</div>
																</div>
																<div class="div12">&nbsp;</div>
																<div class="div12">
																	<div class="div4">
																	<label for="name" class="card-label">Discipline</label>
																	</div>
																	<div class="div8">
																		<select name="cmb_disc[]" id="cmb_disc" class="card-label-selectbox-lg discsel">
																			<option value="">--- Select ---</option>
																			<option value="ALL">ALL DISCIPLINE</option>
																			<?php echo $objBind->BindDiscipline('');?>
																		</select>
																	</div>
																</div>
																<div class="div12">&nbsp;</div>
																<div style="text-align:center">
																	<div class="buttonsection" style="display:inline-table">
																		<input type="button" onClick="goBack()" class="btn btn-info" name="back" id="back" value="Back">
																	</div>
																	<div class="buttonsection" style="display:inline-table">
																		<input type="submit" class="btn btn-info" data-type="submit" value=" Save " name="btn_save" id="btn_save"/>
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
						</blockquote>
					</div>
				</div>
			</div>
		</form>
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.show({
			message: msg,
			buttons: [{
				label: ' OK ',
				action: function(dialog) {
					dialog.close();
					window.location.replace('ObjHeadMaster.php');
				}
			}]
		});
	}

var KillEvent = 0;
$("body").on("click","#btn_save", function(event){
	if(KillEvent == 0){
		var cmb_pin_no	    = $("#cmb_pin_no").val();
		var txt_obj_head 	= $("#txt_obj_head").val();
		var cmb_disc	= $("#cmb_disc").val();

		
		if(cmb_pin_no == ""){
			BootstrapDialog.alert("Please Select PIN Number..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(txt_obj_head == ""){
			BootstrapDialog.alert("Object Head Should Not Be Empty..!!");
			event.preventDefault();
			event.returnValue = false;
		}else if(cmb_disc == ""){
			BootstrapDialog.alert("Please Select Discipline..!!");
			event.preventDefault();
			event.returnValue = false;
		}else{
			event.preventDefault();
			BootstrapDialog.confirm({
				title: 'Confirmation Message',
				message: 'Are you sure want to save this Object Head ?',
				closable: false, // <-- Default value is false
				draggable: false, // <-- Default value is false
				btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
				btnOKLabel: 'Ok', // <-- Default value is 'OK',
				callback: function(result) {
					if(result){
						KillEvent = 1;
						$("#btn_save").trigger( "click" );
					}else {
						KillEvent = 0;
					}
				}
			});
		}
	}
});
</script>
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

