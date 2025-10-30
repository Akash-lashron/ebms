<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Finanacial Bid Upload';
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

/*if (isset($_POST["submit"])) {
    $workname 		= trim($_POST['txt_workname']);
    $sheet_id 		= trim($_POST['txt_workshortname']);
	$xlsheetname 	= trim($_POST['txt_sheetname']);
	$selectworder_sql 	= "SELECT sheet_name FROM sheet WHERE sheet_id = '$sheet_id'";
    $selectworder_query = mysql_query($selectworder_sql);
    $sheetname 			= @mysql_result($selectworder_query,0,'sheet_name');
    if($_FILES['file']['name'] != "") {
        $target_dir 		= "uploads/";
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir .$UploadDate. basename($_FILES["file"]["name"]);
        $currentfilename 	= $UploadDate.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if(file_exists($target_file)) {
            $msg = $msg . " Sorry, file already exists." . "<br/>";
            $checkupload = 0;
        }
		// Check file size
        if($_FILES["file"]["size"] > 500000) {
            $msg = $msg . " Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
		// Allow certain file formats
        if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg . " Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
		// Check if $checkupload is set to 0 by an error
        if($checkupload == 0) {
            $msg = $msg . " Sorry, your file was not uploaded." . "<br/>";
		// if everything is ok, try to upload file
        }else{
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
			// echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
            	$checkupload = 1;
            }else{
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
			//echo $checkupload;exit;
	$work_order_cost = 0;
    $first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
    $slno = '';
    if($checkupload == 1) {
    	$Spreadsheet = new SpreadsheetReader("uploads/" . $currentfilename);
		$Sheets = $Spreadsheet -> Sheets();
		//echo $xlsheetname;exit;
        foreach ($Sheets as $Index => $Name){ // Loop to get all sheets in a file.
			$Spreadsheet -> ChangeSheet($Index);
			$sheetname = $Name;
			//echo $sheetname;exit;
			if($xlsheetname == $sheetname)
			{
         		$sheetquery = "UPDATE sheet set sheet_name='$currentfilename', date_upt=NOW() WHERE sheet_id = '$sheet_id'";
        		$sheetsql = mysql_query($sheetquery);
        		$last_id = mysql_insert_id();
				$sheetCnt = 1;
				if(strtolower($imageFileType) == "xls")
				{
					$startRow = 1;
				} 
				if(strtolower($imageFileType) == "xlsx")
				{
					$startRow = 0;
				}
                //if (count($data -> ChangeSheet($Index)) > 0) { // checking sheet not empty
                    foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
					if ($Key>$startRow){
						if(trim($Row[1]) != ''){
                        $item 			= trim($Row[0]);
                        $description 	= trim($Row[1]);
						$patterns = array();
						$patterns[0] = '/"/';
						$patterns[1] = "/'/";
						$patterns[2] = '/�/';
					//echo $description;exit;
						$replacements = array();
						$replacements[0] = '"';
						$replacements[1] = "\'";
						$replacements[2] = '�';
						$description 	= preg_replace($patterns, $replacements, $description);
						$description 	= str_replace("'", "'", $description);
                        $qty 			= trim($Row[2]);
                        $per 			= strtolower(preg_replace('/[.,-]/', '', trim($Row[3])));
						$rate 			= trim($Row[4]);
						$amt 			= trim($Row[5]);
						$mtype 			= strtolower(trim($Row[6]));
						$mtype1 		= $mtype;
						if(($per == "kg") || ($per == "kgs")){
							if($mtype == 'st'){
								$mtype = '';
							}
						}
						if(($amt > 0)&&($amt != "")){
							$work_order_cost = $work_order_cost + $amt;
						}
						
                        $prevsplit 		= $prev_item;
                        $prevfound 		= explode(".", $prevsplit);
                        if ($item != '' && $description != '') 
                        {
                            $found = explode(".", $item);
							if($found[0] != $prevfound[0])
							{
								$divname = $found[0];
								$sql_sheetdivision = "insert into division set sheet_id ='$sheet_id',userid ='$userid', div_name='$divname', active='1'";
								$rs_sheetdivision = mysql_query($sql_sheetdivision);
                                $divisionlast_id = mysql_insert_id();
								//if(count($found) == 1)
								//{
									if(($qty != "") && ($qty != 0))
									{
										$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', active='1'";
										$rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
										$subdivisionlast_id = mysql_insert_id();
									}
								//}
							}
							else
							{
								if(($qty != "") && ($qty != 0))
								{
									$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', active='1'";
                                    $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                   	$subdivisionlast_id = mysql_insert_id();
								}
							}

							$prev_item = $item;
							if(($qty == "") || ($qty == 0)) 
								{ $subdivisionlastid = 0; }
							else 
								{ $subdivisionlastid = $subdivisionlast_id; }
							$sql_schedule = "insert into schdule set sheet_id='$sheet_id', sno='$item', description='$description', total_quantity='$qty', rate='$rate', rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='$mtype', measure_type_1='$mtype1', item_flag = 'NI', escalation_flag = 'Y', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
                            $rs_schedule = mysql_query($sql_schedule);
							if($rs_schedule == true){
								$InsertTemp++;
							}
                        }
						$Exectemp++;
						}
					}//end of if (count($Spreadsheet)>4) condition
                    } //for  // loop used to get each row of the sheet----------------------
				}
				
               // } // checking sheet not empty
            } // Loop to get all sheets in a file.
        } //checkupload
		
		if($work_order_cost > 0){
			UpdateWorkTransaction($sheet_id,0,"W","Schedule of quantity uploaded","");
			$work_order_cost 		= round($work_order_cost,2);
			$update_wo_cost_query 	= "update sheet set work_order_cost = '$work_order_cost' where sheet_id='$sheet_id'";
			$update_wo_cost_sql 	= mysql_query($update_wo_cost_query);
		}
		//echo $Exectemp;exit;
    	if($sheetCnt == 0){
			$msg = " Invalid sheet name. Please enter valid sheet name.";
			$success = 0;
		}else if($Exectemp == 0){
			$msg .= " Excel Sheet Not Uploaded. Invalid / Empty Excel Sheet.";
		}else{
			if($rs_schedule == true){
				$sheetquery = "UPDATE sheet set active = 1 WHERE sheet_id = '$sheet_id'";
				$sheetsql = mysql_query($sheetquery);
				$msg = " Excel Sheet Uploaded. <br/>   Data Inserted Successfully";
				$success = 1;
			}
		}
} //submit 
*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
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
        <form action="PriceBidUpload.php" method="post" enctype="multipart/form-data" name="form">
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
									           <div class="card-header inkblue-card" align="center">Financial Bid - Upload</div>
										       <div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row">
														<div class="row smclearrow"></div>
														<div class="row">
															<div class="div3 lboxlabel">	
																Tender Number
															</div> 
															<div class="div9">
																<select id="cmb_tr_no" name="cmb_tr_no" class="tboxsmclass BidVlaid" style="width:100%;">
																	<option value="">--------------- Select --------------- </option>
																	<?php echo $objBind->BindPriceuploadTrNo('');?>
																</select>
															</div>
															<div class="row smclearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	Name of Work
																</div>
																<div class="div9">
																	<input type="text" name='txt_work_name' id='txt_work_name' readonly class="tboxsmclass" value="" style="width:100%;">
																</div>
															</div>
															<div class="row smclearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	Bidder Name
																</div>
																<div class="div9">
																	<select id="cmb_bidder" name="cmb_bidder" class="tboxsmclass BidVlaid" style="width:100%;">
																		<option value="">--------------- Select --------------- </option>
																	</select>
																</div>
															</div>
															<div class="row smclearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	Sheet Name
																</div>
																<div class="div4">
																	<input type="text" name='txt_sheetname' id='txt_sheetname' maxlength="25" class="tboxsmclass">
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
																	<input type="number" maxlength="4" onKeyPress="return isIntegerValue(event,this);" name='txt_end_row' id='txt_end_row' class="tboxsmclass">
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
															<!-- <div class="row smclearrow"></div> -->
															<div class="row">
																<div class="div3">&nbsp;</div>
																<div class="div9 lboxlabel">
																	<span style="color:red;"> * File should be in the formats of : .xls , .xlsx </span>
																</div>
															</div>
															<div class="row smclearrow"></div>
																<div class="row">
																	<div class="card-header inkblue-card " align="left">Upload File Column Mapping as on Excel Sheet</div>
																	<table class="dataTable etable " align="center" width="100%" id="emdtable1">
																		<tr class="label" style="background-color:#FFF">
																			<!-- <th align="center">Upload File</th> -->
																			<th align="center"> Item No. </th>
																			<th align="center"> Description </th>
																			<th align="center"> Quantity </th>
																			<th align="center"> Unit </th>
																			<th align="center"> Rate ( &#8377; )</th>
																			<th align="center"> Amount ( &#8377; )</th>
																		</tr>
																		<tr>
																			<!-- <td align="center">
																				<input type="file" required class="text" name="file" id="file" size="44" style="height:23px;" />
																			</td> -->
																			<td align="center">
																				<select name="cmd_itmno_colm" id ="cmd_itmno_colm" class="tboxsmclass ColMap"> 
																					<option value=""> -- Select -- </option> 
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>																							
																				</select>
																			</td>
																			<td align="center">
																				<select name="cmd_desc_colm" id ="cmd_desc_colm" class="tboxsmclass ColMap"> 
																					<option value=""> -- Select -- </option> 
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>																							
																				</select>
																			</td>
																			<td align="center">
																				<select name="cmd_qty_colm" id ="cmd_qty_colm" class="tboxsmclass ColMap"> 
																					<option value=""> -- Select -- </option> 
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>																							
																				</select>
																			</td>
																			<td align="center">
																				<select name="cmd_unit_colm" id ="cmd_unit_colm" class="tboxsmclass ColMap">  
																					<option value=""> -- Select -- </option>
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>	
																				</select>
																			</td>
																			<td align="center">
																				<select name="cmd_rate_colm" id ="cmd_rate_colm" class="tboxsmclass ColMap">  
																					<option value=""> -- Select -- </option>
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>	
																				</select>
																			</td>
																			<td align="center">
																				<select name="cmd_amt_colm" id ="cmd_amt_colm" class="tboxsmclass ColMap">  
																					<option value=""> -- Select -- </option>
																					<?php 
																						$x = 0;
																						for($i='A'; $i<'ZZ'; $i++){
																							echo '<option value="'.$x.'">'.$i.'</option>';
																							$x++;
																						}
																					?>	
																				</select>
																			</td>
																			<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																		</tr>
																	</table>
																</div>
																<div class="row smclearrow"></div>

															<div class="row smclearrow"></div>
															<div class="div12" align="center">
																<input type="submit" class="btn btn-info" data-type="submit" name="btn_upload" id="btn_upload" value="Upload File" />
																<a data-url="Home" class="btn btn-info" name="Back" id="Back">Back</a>
																<!-- <a data-url="PriceBidViewEdit" class="btn btn-info" name="view" id="view">View - Uploaded Files</a> -->
																<input type="hidden" name='hid_status' id='hid_status' class="tboxsmclass">
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
	$("#cmd_itmno_colm").chosen();
	$("#cmd_desc_colm").chosen();
	$("#cmd_qty_colm").chosen();
	$("#cmd_unit_colm").chosen();
	$("#cmd_rate_colm").chosen();
	$("#cmd_amt_colm").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	$('body').on("change","#cmb_tr_no", function(e){ 
		var Id = $(this).val();
		var MastId = $(this).val();
		$("#txt_work_name").val('');
		$("#cmb_bidder").val('');
		$("#txt_workorder").val('');
	    $("#cmb_bidder").val('');
	    $("#txt_sheetname").val('');
	    $("#txt_start_row").val('');
	    $("#txt_end_row").val('');
	    $("#hid_status").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'FindEstTsTrName.php', 
			data: { Id: Id, Page: 'TR'}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$("#txt_work_name").val(data.work_name);
					$(".ColMap").chosen("destroy");
					$("#cmd_itmno_colm").val(data.ExcelItemNoCol);
					$("#cmd_desc_colm").val(data.ExcelItemDescCol);
					$("#cmd_qty_colm").val(data.ExcelItemQtyCol);
					$("#cmd_unit_colm").val(data.ExcelItemUnitCol);
					$("#cmd_rate_colm").val(data.ExcelItemRateCol);
					$("#cmd_amt_colm").val(data.ExcelItemAmtCol);
					$(".ColMap").chosen();
				}
			}
		});
		$("#cmb_bidder").chosen('destroy'); 
			$('#cmb_bidder').children('option:not(:first)').remove();
			if(MastId != ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindPriceBiddersName.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) { 
						if(data != null){ 
							if(data != 0){
								$.each(data, function(index, element) {
									$("#cmb_bidder").append('<option value="'+element.contid+'">'+element.contname+'</option>');
								});
							}
						}
						$("#cmb_bidder").chosen();
					}
				});
			}
		});
		$(document).on('change','.BidVlaid',function(e){  
			$("#hid_status").val('');
			var TenNo = $("#cmb_tr_no").val(); //alert(TenNo);
			var Bidname = $("#cmb_bidder").val(); //alert(Bidname);
			if((TenNo != "")&&(Bidname != "")){
				$.ajax({
					type:'POST',
					url: 'GetTenderRecord.php', 
					dataType: 'json',
					data:{'TenNo':TenNo, 'Bidname':Bidname}, 
					success:function(data){  //alert(data);	
						var TenData = data['status'];
						if(TenData == 1){
							var ContNameData = data['ContName'];
							BootstrapDialog.alert('Note : Financial Bid For "'+ContNameData+'" is Aldready Uploaded, Proceeding further will replace existing record..!!');
						}
						$("#hid_status").val(TenData);
					}
				});
			}
      });
      $("body").on("change","#file", function(event){  
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
      
	   $(document).ready(function(){ 
		var KillEvent = 0;
	   $("body").on("click","#btn_upload", function(event){
			if(KillEvent == 0){
				var ShortName 	= $("#cmb_tr_no").val();
				var WorkOrderNo = $("#txt_workorder").val();
				var BidderName 	= $("#cmb_bidder").val();
				var SheetName 	= $("#txt_sheetname").val(); 
				var StartRow 	= $("#txt_start_row").val(); 
				var Strtrow     = parseFloat(StartRow);
				var EndRow 		= $("#txt_end_row").val();  
				var Erow        = parseFloat(EndRow); 
				var Hidstatus 	= $("#hid_status").val(); //alert(Hidstatus);
				var Files       = $('#file').val();
				var ItmNoColmn  = $("#cmd_itmno_colm").val();
				var DescColmn 	 = $("#cmd_desc_colm").val();
				var QtyColmn 	 = $("#cmd_qty_colm").val();
				var UnitColmn 	 = $("#cmd_unit_colm").val();
				var RateColmn 	 = $("#cmd_rate_colm").val();
				var AmtColmn 	 = $("#cmd_amt_colm").val();

				if(ShortName.trim() == ""){
					BootstrapDialog.alert("Please select Tender name");
					event.preventDefault();
				}else if(BidderName == ""){
					BootstrapDialog.alert("Bidder name should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(SheetName.trim() == ""){
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
					$("#txt_start_row").val(''); 
					$("#txt_end_row").val('');
				}else if(Files == ""){
					BootstrapDialog.alert("Please Select a file to Upload");
					event.preventDefault();
					event.returnValue = false;
				}else if(ItmNoColmn == ""){
					BootstrapDialog.alert("Item No. column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(DescColmn == ""){
					BootstrapDialog.alert("Item Description column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(QtyColmn == ""){
					BootstrapDialog.alert("Item Quantity column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(UnitColmn == ""){
					BootstrapDialog.alert("Unit column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(RateColmn == ""){
					BootstrapDialog.alert("Rate column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(AmtColmn == ""){
					BootstrapDialog.alert("Amount column should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(Hidstatus == 1){ 
					event.preventDefault();
						BootstrapDialog.confirm('Bidder Details for this Tender No. already exists, Are you sure want to Delete and replace ?', function(result){
							if(result) {
								KillEvent = 1;
								$("#btn_upload").trigger( "click" );
							}
						});
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to View & Upload this Financial Bid ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							if(result){
								KillEvent = 1;
								$("#btn_upload").trigger( "click" );  
							}else {
								KillEvent = 0;
							}
						}
					});
				}
			}
		});
	});
	// 	$(document).on("change","#txt_end_row", function(event){  
	// 		var checkrow = 0;
	// 		var Endrow = $(this).val(); 	alert(Endrow);	
	// 		var Startrow= $("#txt_start_row").val(); 	alert(Startrow);
	// 		if(Startrow > Endrow ){
	// 			BootstrapDialog.alert("End row is lesser than start row value.. Please check the Row number..!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		$("#txt_end_row").val('')
	// 		$("#txt_start_row").val(''); 
	// 		}
	//    });
		$("body").on("change","#cmb_shortname", function(event){
			$("#txt_workorder").val('')
			var TenderNo = $("#cmb_shortname option:selected").attr('data-tr');
			if(TenderNo != ""){
				$("#txt_workorder").val(TenderNo);
			}
		});
		
</script>


