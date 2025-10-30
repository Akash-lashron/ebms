<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Price Bid Upload';
checkUser();
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
$PriceBidLocation = $_SERVER['DOCUMENT_ROOT'].'/ebms/Budget/uploads/';//"PriceBid/";
$RowCount = 0;
function moneyFormat($amt)
{
	/* 
	1. IMPORTANT NOTES: Plz use this result of this funtion for only print output in following format. 
	2. DONT USE addtion, subtraction, multiplication and division function using this result. 
	3. Because it gives the result in string - data type. If we use this output will be wrong.
	*/
	$amount = number_format($amt, 2, '.', '');
	$explodeRes = explode(".",$amount);
	$ratePart = $explodeRes[0];
	$decimalPart = $explodeRes[1];
	$length = strlen($ratePart);
	if($length>3)
	{
		$getArray = str_split($ratePart);
		$count = count($getArray);
		if(($count%2) == 0)
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else if($i == 0)
				{
					$result .= $getArray[$i].",";
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		else
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		$result = $result.".".$decimalPart;
	}
	else
	{
		$result = $amount;
	}
	return $result;
}
$MastCcnoArr = array();
if(isset($_POST["upload"]) == "Upload File"){
	$SheetName 		= $_POST['txt_sheetname'];
	$StartRow 		= $_POST['txt_start_row'];
	$EndRow 		= $_POST['txt_end_row'];
	$UploadFile 	= $_FILES['file']['name'];
	$WorkStatus 	= $_POST['cmb_status'];

	if($_FILES['file']['name'] != ""){
        $target_dir 		= $PriceBidLocation;	//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//"PriceBid/";
		//echo $target_dir; exit;
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir.$UploadDate.basename($_FILES["file"]["name"]);
        $currentfilename 	= $UploadDate.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if(file_exists($target_file)){
			unlink($target_file); 
        }
        if($_FILES["file"]["size"] > 500000) {
            $msg = $msg." Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
        if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg." Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
        if($checkupload == 0) {
            $msg = $msg." Sorry, your file was not uploaded." . "<br/>";
        }else{
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            	$checkupload = 1;
            }else{
                $checkupload = 0;
                $msg = $msg."Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
	$work_order_cost = 0;
    $first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
    $slno = '';
	$SelectQuery1 = "select distinct ccno from works";
	$SelectSql 	  = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			while($CcNoList = mysqli_fetch_object($SelectSql)){
				if(($CcNoList->ccno != '')&&($CcNoList->ccno != 0)&&($CcNoList->ccno != '0')){
					array_push($MastCcnoArr,$CcNoList->ccno);
				}
			}
		}
	}
}
if(isset($_POST["confirm"]) == " CONFIRM "){
	$CcnoArr 		= $_POST['txt_ccno'];
	$ContNameArr 	= $_POST['txt_cont_name'];
	$WorkNameArr 	= $_POST['txt_work_name'];
	$WorkOrderArr 	= $_POST['txt_work_order_no'];
	$PostWorkStatus = $_POST['txt_work_status'];
	$PostWorkDtArr	= $_POST['txt_work_order_dt'];
	$Execute = 0;
//print_r($PostWorkDtArr);exit;

	if(count($CcnoArr)>0){
		foreach($CcnoArr as $ArrKey => $ArrValue){
			$ContName 		= $ContNameArr[$ArrKey];
			$WorkName 		= $WorkNameArr[$ArrKey];
			$WorkOrderNo 	= $WorkOrderArr[$ArrKey];
			$WorkOrderDt 	= dt_format($PostWorkDtArr[$ArrKey]);
			
			$SheetId = 0;
			$SelectSheetQuery = "select * from sheet where computer_code_no = '$ArrValue'";
			$SelectSheetSql   = mysqli_query($dbConn,$SelectSheetQuery);
			if($SelectSheetSql == true){
				if(mysqli_num_rows($SelectSheetSql)>0){
					$SheetList = mysqli_fetch_object($SelectSheetSql);
					$SheetId   = $SheetList->sheet_id;
				}
			}
			
			$InsertQuery 	= "insert into works set sheetid = '$SheetId', ccno = '$ArrValue', work_name = '$WorkName', wo_no = '$WorkOrderNo', wo_date = '$WorkOrderDt', name_contractor = '$ContName', work_status = '$PostWorkStatus', active = 1, createddate = NOW(), createduserid = '".$_SESSION['userid']."'";
			$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
			$GlobId			= mysqli_insert_id($dbConn);
			
			if($SheetId != 0){
				$UpdateQuery1 	= "update sheet set globid = '$GlobId' where sheet_id = '$SheetId'";
				$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
			}
			
			$InsertQuery 	= "insert into budget_action_taken set globid = '$GlobId', ccno = '$ArrValue', work_name = '$WorkName', wo_no = '$WorkOrderNo', wo_date = '$WorkOrderDt', name_contractor = '$ContName', active = 1, createddate = NOW(), createduserid = '".$_SESSION['userid']."'";
			$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
			
			if($InsertSql == true){
				$Execute++;
			}
		}
	}
	//print_r($CcnoArr);
	//exit;
	if($Execute > 0){
		$msg = "Works Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Works Details Not Saved. Please Try Again.";
		$success = 0;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack()
	{
	   	url = "UploadWorks.php";
		window.location.replace(url);
	}
</script>
<style>
	.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 768px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
                            <div class="container">
								<div class="row ">
									<div class="div12">
										<div class="row">
											<div class="div1">&nbsp;</div>
											<div class="div10">
											<table class="DispTable" width="100%">
												<thead>
													<tr>
														<th>SNo.</th>
														<th nowrap="nowrap">CCNo.</th>
														<th>Contractor Name</th>
														<th>Name of Work</th>
														<th>Name Order No.</th>
														<th>Name Order Date</th>
														<th>Remarks</th>
													</tr>
												</thead>
												<tbody>
												<?php 
												$AllCCnoArr = array(); $TotalError = 0; $Sno = 1; 
												if($checkupload == 1) {
													$Spreadsheet = new SpreadsheetReader($PriceBidLocation.$currentfilename);
													$Sheets = $Spreadsheet -> Sheets();
													foreach ($Sheets as $Index => $Name){ // Loop to get all sheets in a file.
														$Spreadsheet -> ChangeSheet($Index);
														$ExcelSheetName = $Name;
														if($SheetName == $ExcelSheetName)
														{
															if(strtolower($imageFileType) == "xls"){
																$StartRow = $StartRow - 1;
															}
															if(strtolower($imageFileType) == "xlsx"){
																$StartRow = $StartRow - 1;
															}
															foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
																if(($Key >= $StartRow)&&($Key <= $EndRow)){
																	if(trim($Row[1]) != ''){
																		$CCNo 			= trim($Row[0]);
																		$ContName 		= trim($Row[1]);
																		$WorkName 		= trim($Row[2]);
																		$WorkOrderNo 	= trim($Row[3]);
																		$WorkOrderDt 	= trim($Row[4]);
																		$DateError		= 0;
																		if(strpos($WorkOrderDt, "/") !== false){
																			$WoDateStr = $WorkOrderDt;
																			//echo $WoDateStr;exit;
																		}else if(strlen($WorkOrderDt) == 8){
																			$WoYear 	= substr($WorkOrderDt, 0, 4);
																			$WoMonth 	= substr($WorkOrderDt, 4, 2);
																			$WoDay 		= substr($WorkOrderDt, 6, 2);
																			$WoDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																		}else{
																			$DateError  = 1;
																			$WoDateStr = $WorkOrderDt;
																		}
																		
																		
																		if(in_array($CCNo,$AllCCnoArr)){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Duplicate CCNO - already exists in Excel File";
																		}else if(in_array($CCNo,$MastCcnoArr)){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Duplicate CCNO - already exists in Database";
																		}else if($CCNo == ''){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "CCNO should not be empty";
																		}else if($CCNo == 0){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "CCNO should not be 0";
																		}else if($CCNo == '0'){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "CCNO should not be 0";
																		}else if($ContName == ''){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Contractor Name should not be empty";
																		}else if($WorkName == ''){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Work Name should not be empty";
																		}else if($WorkOrderNo == ''){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Work Order No. should not be empty";
																		}else if($WorkOrderDt == ''){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Work Order Date should not be empty";
																		}else if($DateError == 1){
																			$Error 	= 1; $TotalError++;
																			$DupTdStyle 	= "style='background-color:#DA0532; color:#ffffff; font-weight:bold;'";
																			$Remarks = "Work Order Date is invalid";
																		}else{
																			$Error 	= 0;
																			$DupTdStyle 	= "";
																			$Remarks = "";
																		}
																	?>
																	<tr>
																		<td align="center" <?php echo $DupTdStyle; ?>><?php echo $Sno; ?></td>
																		<td align="center" <?php echo $DupTdStyle; ?>><?php echo $CCNo; ?><input type="hidden" name="txt_ccno[]" value="<?php echo $CCNo; ?>"></td>
																		<td align="left" <?php echo $DupTdStyle; ?>><?php echo $ContName; ?><input type="hidden" name="txt_cont_name[]" value="<?php echo $ContName; ?>"></td>
																		<td align="left" <?php echo $DupTdStyle; ?>><?php echo $WorkName; ?><input type="hidden" name="txt_work_name[]" value="<?php echo $WorkName; ?>"></td>
																		<td align="left" <?php echo $DupTdStyle; ?>><?php echo $WorkOrderNo; ?><input type="hidden" name="txt_work_order_no[]" value="<?php echo $WorkOrderNo; ?>"></td>
																		<td align="left" <?php echo $DupTdStyle; ?>><?php echo $WoDateStr; ?><input type="hidden" name="txt_work_order_dt[]" value="<?php echo $WoDateStr; ?>"></td>
																		<td align="left"><?php echo $Remarks; ?><input type="hidden" name="txt_remarks[]" value="<?php echo $Remarks; ?>"></td>
																	</tr>
																	<?php
																		if(($CCNo != '')&&($CCNo != '')){
																			array_push($AllCCnoArr,$CCNo);
																		}
																		$Sno++;
																	}
																}
															} 
														}
													}
												 } 
												 ?>
													</tbody>
												</table>
												</div>
												<div class="div1">&nbsp;</div>
											</div>
											<div class="smediv">&nbsp;</div>
									</div>
								</div>
								<?php if($TotalError > 0){ ?>
								<div class="row">
									<div class="div1">&nbsp;</div>
									<div class="div10" style="background-color:#FFFFFF; color:#DA0532; font-weight:bold;">
										Total Error/s in Excel File : <?php echo $TotalError; ?>
									</div>
									<div class="div1">&nbsp;</div>
								</div>
								<?php } ?>
								<div class="row">
									<div class="div12" align="center">
									
										<input type="hidden" name="txt_work_status" id="txt_work_status" value="<?php echo $WorkStatus; ?>"/>
										<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
										<?php if($Error == 0){ ?>
										<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
										<?php }else{ ?>
										<input type="submit" class="btn btn-info" name="cancel" id="cancel" value=" CANCEL "/>
										<?php } ?>
									</div>
								</div>  
								<div class="row">&nbsp;</div>                         
                            </div>
                    </blockquote>
                </div>

            </div>
        </div>
	</form>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('UploadWorks.php');
					}
				}]
			});
		}

		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#confirm", function(event){
				if(KillEvent == 0){
					var TrId 	= $("#txt_TrId").val();
					var BidderId 	= $("#txt_bidderid").val();
					var RebatePerc 	= $("#txt_rebate_perc").val();
					if(TrId == ""){
						BootstrapDialog.alert("Invalid Work. Unable to Save.");
						event.preventDefault();
						event.returnValue = false;
					}else if(BidderId == ""){
						BootstrapDialog.alert("Invalid Bidder. Unable to Save");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure want to confirm ?', function(result){
							if(result) {
								KillEvent = 1;
								$("#confirm").trigger( "click" );
							}
						});
					}
				}
			});
			function FormatNumberToINR(num) {
				input = num;
				var n1, n2;
				num = num + '' || '';
				// works for integer and floating as well
				n1 = num.split('.');
				n2 = n1[1] || null;
				n1 = n1[0].replace(/(\d)(?=(\d\d)+\d$)/g, "$1,");
				num = n2 ? n1 + '.' + n2 : n1;
				console.log("Input:",input)
				console.log("Output:",num)
				return num;
			}
			$("body").on("change","#txt_rebate_perc", function(event){
				var RebatePerc 	 = $("#txt_rebate_perc").val();
				var TotalAmount  = $("#txt_total_amt").val();
				var RebateAmount = Number(TotalAmount)*Number(RebatePerc) / 100;
				var TotAmtWithRebate = Number(TotalAmount)-Number(RebateAmount);
					TotAmtWithRebate = TotAmtWithRebate.toFixed(2);
				if(TotAmtWithRebate != ""){
					var FinalAmount = FormatNumberToINR(TotAmtWithRebate);
				}else{
					var FinalAmount = "";
				}
				var RebatePerc1 = Number(RebatePerc).toFixed(2);
				var RebatePerc2 = FormatNumberToINR(RebatePerc1);
				$("#txt_rebate_perc").val(RebatePerc2);
				$("#txt_total_with_rebate").val(FinalAmount);
			});
		});
	</script>
    </body>
</html>

