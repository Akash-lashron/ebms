<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$success = "";
$PageName = $PTPart1.$PTIcon.'Department Estimate Upload';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
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
//$sheetquery = "SELECT * FROM tender_register   WHERE cst_status != 'A' OR cst_status IS NULL  ORDER BY tr_id ASC";
//echo $sheetquery; exit;
if(isset($_GET['id'])){   
	$MastId 	 	= $_GET['id'];
	$SelectQuery 	= "SELECT * FROM partab_master WHERE mastid = '$MastId'";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$List = mysqli_fetch_object($SelectSql);
			$GlobId 	= $List->globid;
			$RefNo 	= $List->ref_no;
			$WorkName = $List->work_name;
			$EstAmt 	= $List->partA_amount;
		}
	}
}	
/*for($GLX = 'A'; $GLX < 'ZK'; $GLX++){
	echo $GLX."<br/>";
}
exit;*/
?>
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
	/* .lboxlabel {
  color: #04498E;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
} */
	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
	}
</style>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
		url = "MyView.php";
		window.location.replace(url);
	}
	function OpenInNewTabWinBrowser(url) 
	{
	  	var win = window.open(url, '_blank');
	  	win.focus();
	}
	function View_page()
	{
		url = "AgreementDetailsView.php";
		window.location.replace(url);
	}

	$(function () {
		$("#sheet_name_info").click(function(event){
			BootstrapDialog.show({
				title: 'Sample Sheet Format',
				closable: false,
				message: $('<img src="images/sheet_name.png">'),
				buttons: [{
					label: ' Close ',
					cssClass: 'btn-default',
					action: function(dialog) {
						dialog.close();
					}
				}]
			});
		});
	});
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="DeptEstimateUpload.php" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
						<div align="right" class="users-icon-part">&nbsp;</div> 
                        <blockquote class="bq1" style="overflow:auto">
							<!--<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('AgreementUpload_File_Sample.php');"><u>Agreement Sheet</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>-->
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
									           <div class="card-header inkblue-card" align="center">Department Estimate - Upload</div>
										       <div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																		<!--<div class="row">
																			<div class="div3 lboxlabel">
																				Tender Number
																			</div>
																			<div class="div9">
																				<select id="cmb_tr_no" name="cmb_tr_no" class="tboxsmclass" style="width:100%;">
																					<option value="">--------------- Select --------------- </option>
																					<?php // echo $objBind->BindDepEstTrNo('');?>
																				</select>
																			</div>
																		</div>-->
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Name of Work
																			</div>
																			<div class="div9">
																				<textarea name='txt_work_name' id='txt_work_name' class="tboxsmclass" rows="3" style="width:100%;"><?php if((isset($WorkName))&&($WorkName != '')){ echo $WorkName; } ?></textarea>
																			</div>
																		</div>
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Estimate Amount ( &#8377; )
																			</div>
																			<div class="div4">
																				<input type="text" name='txt_est_amt' onKeyPress="return isIntegerValue(event,this);" maxlength="15" id='txt_est_amt' class="tboxsmclass" value="<?php if((isset($EstAmt))&&($EstAmt != '')){ echo $EstAmt; } ?>">
																			</div>
																		</div>
																		<div class="row smclearrow"></div>
																		
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Sheet Name
																			</div>
																			<div class="div4">
																				<input type="text" name='txt_sheetname' maxlength="25" id='txt_sheetname' class="tboxsmclass">
																			</div>
																			<div class="div5" align="left">
																				&nbsp;&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:0px; color:#0078F0; cursor:pointer; font-size:25px" id="sheet_name_info" title="Click here to View Sample"></i>
																			</div>
																		</div>
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Starting Row
																			</div>
																			<div class="div4">
																				<input type="number" name='txt_start_row' maxlength="4" onKeyPress="return isIntegerValue(event,this);" id='txt_start_row' class="tboxsmclass">
																			</div>
																			<div class="div5" align="left">&nbsp;</div>
																		</div>
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Ending Row
																			</div>
																			<div class="div4">
																				<input type="number" name='txt_end_row'  maxlength="4" onKeyPress="return isIntegerValue(event,this);" id='txt_end_row' class="tboxsmclass">
																			</div>
																			<div class="div5" align="left">&nbsp;</div>
																		</div>
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">
																				Upload File
																			</div>
																			<div class="div3">
																				<input type="file" required class="text" name="file" id="file" size="44" style="height:23px;" />
																			</div>
																		</div>
																		<div class="row smclearrow"></div>
																		<div class="row">
																			<div class="div3">&nbsp;</div>
																			<div class="div9 lboxlabel">
																				<span style="color:red;"> File should be in the formats of : .xls , .xlsx </span>
																			</div>
																		</div>
																			<div class="row smclearrow"></div>		
																			<div class="div12" align="center">
																				<input type="hidden" name="txt_mastid" id="txt_mastid" value="<?php echo $MastId; ?>">
																				<input type="hidden" name="txt_globid" id="txt_globid" value="<?php echo $GlobId; ?>">
																				<input type="hidden" name="txt_refno" id="txt_refno" value="<?php echo $RefNo; ?>">
																				<input type="submit" class="btn btn-info" data-type="submit" name="upload" id="upload" value="Upload File" />
																			   <a data-url="Home" class="btn btn-info" name="Back" id="Back">Back</a>
																				<!-- <a data-url="DepEstViewEdit"  class="btn btn-info" name="view" id="view">View - Uploaded Files</a> -->
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
	$("#cmb_tr_no").chosen();
	$("#cmb_bidder").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	$('body').on("change","#cmb_tr_no", function(e){ 
		var Id = $(this).val();$("#txt_work_name").val('');
		$("#cmb_bidder").val('');
		$("#txt_workorder").val('');
	    $("#cmb_bidder").val('');
	    $("#txt_sheetname").val('');
	    $("#txt_start_row").val('');
	    $("#txt_end_row").val('');
	    $("#hid_status").val('');

		$("#txt_work_name").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'FindEstTsTrName.php', 
			data: { Id: Id, Page: 'TR'}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$("#txt_work_name").val(data.work_name);
				}
			}
		});
	});
	$(document).ready(function(){ 
		var KillEvent = 0;
		$("body").on("click","#upload", function(event){
			// var ShortName	 = $("#cmb_tr_no").val();
			if(KillEvent == 0){
				var ShortName = $("#txt_work_name").val();
				var txt_est_amt = $("#txt_est_amt").val();
				var BidderName  = $("#cmb_bidder").val();
				var SheetName 	 = $("#txt_sheetname").val();
				var StartRow 	 = $("#txt_start_row").val();
				var Strtrow     = parseFloat(StartRow);
				var EndRow 		 = $("#txt_end_row").val();
				var Erow        = parseFloat(EndRow); 
				var Files       = $('#file').val();
				if(ShortName.trim() == ""){
					BootstrapDialog.alert("Name of Work should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(txt_est_amt == ""){
					BootstrapDialog.alert("Estimate Amount should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}/*else if(WorkOrderNo.trim() == ""){
					BootstrapDialog.alert("Tender no. should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(BidderName == ""){
					BootstrapDialog.alert("Bidder name should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}*/else if(SheetName.trim() == ""){
					BootstrapDialog.alert("Sheet name should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(StartRow.trim() == ""){
					BootstrapDialog.alert("Starting row should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(EndRow.trim() == ""){
					BootstrapDialog.alert("Ending row should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(Strtrow > Erow ){
					BootstrapDialog.alert("End row is lesser than start row value.. Please check the Row number..!");
					event.preventDefault();
					event.returnValue = false;
					$("#txt_end_row").val('');
					$("#txt_start_row").val(''); 
				}else if(Files == ""){
					BootstrapDialog.alert("Please Select a file to Upload");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to View & Upload this Department Estimate ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							if(result){
								KillEvent = 1;
								$("#upload").trigger( "click" );
							}else {
								KillEvent = 0;
							}
						}
					});
				}

			}
		});
		$('#file').on('change', function(event){
		//alert(1);
        	var fileExtension = ['xls', 'xlsx'];
            if($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                BootstrapDialog.alert("Only formats allowed are : "+fileExtension.join(', '));
                 	$(this).val('');
                   	event.preventDefault();
                   	event.returnValue = false;
            }else if(this.files[0].size > 5048576){  //5242880
               $(this).val('');
				BootstrapDialog.alert("Upload file size should be less than 5MB!");
				event.preventDefault();
				event.returnValue = false;
         	}
        });

		$("body").on("change","#cmb_shortname", function(event){
			$("#txt_workorder").val('')
			var TenderNo = $("#cmb_shortname option:selected").attr('data-tr');
			if(TenderNo != ""){
				$("#txt_workorder").val(TenderNo);
			}
		});
		// $("body").on("change","#txt_end_row", function(event){  
		
		// 	var Endrow = $(this).val(); 	
		// 	var Startrow= $("#txt_start_row").val(); 	
		// 	if(Startrow > Endrow ){
		// 		BootstrapDialog.alert("End row is lesser than start row value.. Please check the Row number..!");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	  $("#txt_end_row").val('')
		// 	}
		// });
		
	});
</script>