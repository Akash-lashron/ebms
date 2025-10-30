<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';//include "common.php";
checkUser();
$msg = ''; $Scount = 0;
$sheetid = $_SESSION['Sheetid'];
$staffid = $_SESSION['sid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$SheetId = '';
if(isset($_GET['id'])){
	$SheetId = $_GET['id'];
}
if(isset($_POST['btnSave']) == " Submit "){
    $SheetId		= $_POST['txt_sheetid'];
	$PostWorkExtDt 	= dt_format($_POST['txt_ext_date']);
	$InsertQuery 	= "insert into work_orders_ext set sheetid='$SheetId', work_orders_ext = '$PostWorkExtDt', createddate = NOW(), userid = ".$_SESSION['userid'];
	$InsertSql 		= mysql_query($InsertQuery);
	$UpdateQuery 	= "update sheet set work_orders_ext = '$PostWorkExtDt' where sheet_id = '$SheetId'";
	$UpdateSql 		= mysql_query($UpdateQuery);
	if($InsertSql == true){
		$msg = "Work Order Extension Date Saved Successfully !";
	}else{
		$msg = "Work Order Extension Date Not Saved. Please try again ! !";
	}
}
if($SheetId != ''){
	if(($_SESSION['isadmin'] == 1)||($_SESSION['staff_section'] == 2)){
		$SelectSheetQuery = "select * from sheet where sheet_id = '$SheetId'";
	}else{
		$SelectSheetQuery = "select * from sheet where sheet_id = '$SheetId' and CONCAT(',' ,assigned_staff, ',') LIKE '%,$staffid,%' ORDER BY short_name ASC";
	}
	$SelectSheetSql   = mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		if(mysql_num_rows($SelectSheetSql)>0){
			$List = mysql_fetch_object($SelectSheetSql);
			$Ccno 		= $List->computer_code_no;
			$WorkName 	= $List->work_name;
			$ShortName 	= $List->short_name;
			$SchCompDt 	= $List->date_of_completion;
			$WorkExtDt 	= $List->work_orders_ext;
			if($ShortName != ''){
				$WorkDesc = $ShortName;
			}else{
				$WorkDesc = $WorkName;
			}
	  	}
	}
	
	$ExtCount = 0;
	$SelectWoExtQuery = "select * from work_orders_ext where sheetid = '$SheetId'";
	$SelectWoExtSql = mysql_query($SelectWoExtQuery);
	if($SelectWoExtSql == true){
		if(mysql_num_rows($SelectWoExtSql)>0){
			$ExtCount = 1;
		}
	}
}
?>
<?php require_once "Header.html"; ?>
<style>
.container{
		width:100%;
		border-collapse: collapse;
	}
		
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
	}
.circle {
    background: #19bc8b ;
    color: #fff;
    display: block;
    padding: 3px 8px;
    text-align: center;
    text-decoration: none;
    border-radius: 10px;
}
.circle:hover {
    background: #EC2951;
	color:#fff;
}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack()
	{
	   	url = "WorkExtensionList.php";
		window.location.replace(url);
	}
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<!--==============================header=================================-->
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
		<?php include "Menu.php"; ?>
		<!--==============================Content=================================-->
		<div class="content">
			<div class="title">Works Extension</div>
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
						<div class="container" align="center">
							<div class="row clearrow"></div>
							<div class="div3">&nbsp;</div>
							<div class="div6">
								<div class="card cabox" style="margin-top:0px;">
									<div class="face-static">
										<div class="card-header inkblue-card" align="left">&nbsp;Work Extension</div>
										<div class="card-body padding-1 ChartCard" id="CourseChart">
											<div class="divrowbox pt-2">
												<div class="row">
													<div class="div12" align="center">
														<div class="innerdiv2">
															<div class="row" align="center">
																
																<div class="div12 pd-lr-1">
																	<div class="lboxlabel">Name of Work </div>
																	<div>
																		<textarea name="txt_work_name" id="txt_work_name" class="dynamicboxlg disable" readonly="" required><?php if(isset($WorkName)){ echo $WorkName; } ?></textarea>
																	</div>
																</div>
																<div class="row smclearrow"></div>
																<div class="div4 pd-lr-1">
																	<div class="lboxlabel">CCNO.</div>
																	<div>
																		<input type="text" name="txt_ccno" id="txt_ccno" class="dynamicboxlg disable" value="<?php if(isset($Ccno)){ echo $Ccno; } ?>" readonly="" required />
																	</div>
																</div>
																<div class="row smclearrow"></div>
																<div class="div4 pd-lr-1">
																	<div class="lboxlabel">Scheduled Comp. Date</div>
																	<div>
																		<input type="text" name="txt_sc_date" id="txt_sc_date" class="dynamicboxlg disable" value="<?php if(isset($SchCompDt)){ if(($SchCompDt != '0000-00-00')&&($SchCompDt != NULL)){ echo dt_display($SchCompDt); } } ?>" readonly="" required />
																	</div>
																</div>
																<?php
																if($ExtCount > 0){
																	$Slno = 1;
																	while($ExtList = mysql_fetch_object($SelectWoExtSql)){
																?>
																		<div class="row smclearrow"></div>
																		<div class="div4 pd-lr-1">
																			<div class="lboxlabel">Work Extension Date</div>
																			<div>
																				<input type="text" name="txt_old_ext_date" id="txt_old_ext_date" class="dynamicboxlg disable" readonly="" value="<?php echo dt_display($ExtList->work_orders_ext); ?>" required />
																			</div>
																		</div>
																<?php
																	}
																}
																?>
																<div class="row smclearrow"></div>
																<div class="div4 pd-lr-1">
																	<div class="lboxlabel">Work Extension Date</div>
																	<div>
																		<input type="text" name="txt_ext_date" id="txt_ext_date" class="dynamicboxlg datepicker" value="<?php if(isset($WorkExtDt)){ if(($WorkExtDt != '0000-00-00')&&($WorkExtDt != NULL)){ echo dt_display($WorkExtDt); } } ?>" required />
																		<input type="hidden" name="txt_sheetid" id="txt_sheetid" class="dynamicboxlg" value="<?php if(isset($SheetId)){ echo $SheetId; } ?>" required />
																	</div>
																</div>
																
																
															</div>
														</div>
													</div>
												</div>
												
												<div class="row" align="center">
													<div class="row clearrow"></div>
													<div class="div12 pd-lr-1" align="center">
														<input type="button" name="btnBack" id="btnBack" value=" Back " class="backbutton" onClick="goBack()">
														<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="backbutton">
													</div>
													<div class="row clearrow"></div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="div3">&nbsp;</div>
							
						</div>
						<!--<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection"><input type="button" name="back" id="back" value="Back" class="backbutton"></div>
						</div>-->
					</blockquote>
				</div>
			</div>
		</div>
		<!--==============================footer=================================-->
		<?php include "footer/footer.html"; ?>
	</form>
</body>
</html>
<script>
	$(document).ready(function() {
		var msg = "<?php echo $msg; ?>";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				BootstrapDialog.alert(msg);
			}
		};
		$('#dataTable').DataTable({
			responsive: true,
			paging: true,
		});
		$('#back').click(function(){
			$(location).attr('href', 'MyView.php')
		});
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy",
			yearRange: "1900:+15",
			defaultDate: new Date,
		});
		
		var KillEvent = 0;
		$("body").on("click","#btnSave", function(event){
			if(KillEvent == 0){
				var WorkName  = $("#txt_work_name").val(); 
				var WorkCcno  = $("#txt_ccno").val(); 
				var ExtDate   = $("#txt_ext_date").val(); 
				if(WorkName == ""){
					BootstrapDialog.alert("Please enter work extension date");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkCcno == ""){
					BootstrapDialog.alert("Ccno should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(ExtDate == ""){
					BootstrapDialog.alert("Please enter work extension date");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to save work extension ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
							if(result){
								KillEvent = 1;
								$("#btnSave").trigger( "click" );
							}else {
								//alert('Nope.');
								KillEvent = 0;
							}
						}
					});
				}
			}
		});
		
	});
</script>
<style>
	/*#dataTable_wrapper{
		width:75% !important;
	}*/
	table.table3.dataTable thead th{
	    text-align:left !important;
	}
	.dataTables_wrapper{
		width:95% !important;
	}
	#dataTable th, td{
		font-size:11px;
		line-height:18px;
		padding:3px;
	}
	#dataTable th{
		padding:5px;
	}
</style>
