<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Department Estimate Upload';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
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
$PriceBidLocation = 'PriceBid/';//"PriceBid/";
$RowCount = 0;

if(isset($_POST["upload"]) == "Upload File"){
	$MastId 	  = $_POST['txt_mastid'];
	$GlobId 	  = $_POST['txt_globid'];

	$Qty_colm  = $_POST['cmd_qty_colm'];
	$Rate_colm = $_POST['cmd_rate_colm'];
	$Unit_colm = $_POST['cmd_unit_colm'];
	$Totamt_colm = $_POST['cmd_totamt_colm'];
	
	if(($MastId != '')&&($MastId != 0)&&($MastId != NULL)){
		$RefNo 	= $_POST['txt_refno'];
		$RefNo2 = str_replace("/","-",$RefNo);
	}else{
		$DtTimeStr = date("YmdHms");
		$RefNo = "BRAC/NRB/FRFCF/".$DtTimeStr;
		$RefNo2 = "BRAC-NRB-FRFCF-".$DtTimeStr;
	}
	
	
	$WorkName	= $_POST['txt_work_name'];
	$EstAmount	= $_POST['txt_est_amt'];
	$SheetName	= $_POST['txt_sheetname'];
	$StartRow	= $_POST['txt_start_row'];
	$EndRow 		= $_POST['txt_end_row'];
	$UploadFile	= $_FILES['file']['name'];
	
	$ItmNoColmn	= $_POST['cmd_itmno_colm'];
	$DescColmn	= $_POST['cmd_desc_colm'];
	$QtyColmn 	= $_POST['cmd_qty_colm'];
	$UnitColmn	= $_POST['cmd_unit_colm'];
	$RateColmn	= $_POST['cmd_rate_colm'];
	$AmtColmn	= $_POST['cmd_amt_colm'];

	/*$GlobID= ''; $TrCost = 0;
	$GlobIDQuery = "SELECT globid, tr_est FROM tender_register WHERE tr_id = '$TrId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
			$TrCost = $List->tr_est;
		}
	}*/

	if($_FILES['file']['name'] != ""){
        $target_dir 		= $PriceBidLocation;//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//"PriceBid/";
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir."Dept".$RefNo2.basename($_FILES["file"]["name"]);
        $currentfilename 	= "Dept".$RefNo2.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType 		= pathinfo($target_file, PATHINFO_EXTENSION);
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
}
if(isset($_POST["confirm"]) == " CONFIRM "){
	$MastID 		= $_POST['txt_mastid'];
	$RefNo 			= $_POST['txt_refno'];
	$NameWork 		= $_POST['txt_workname'];
	$RebatePerc 	= $_POST['txt_rebate_perc'];
	$ItemNoArr 		= $_POST['txt_item_no'];
	$ItemDescArr 	= $_POST['txt_item_desc'];
	$ItemQtyArr 	= $_POST['txt_item_qty'];
	$ItemRateArr 	= $_POST['txt_item_rate'];
	$ItemUnitArr 	= $_POST['txt_item_unit'];
	$ItemAmountArr  = $_POST['txt_item_amt'];
	$TotAmt		 	= $_POST['txt_total_amt'];
	$Execute = 0;
	
	

	if(count($ItemDescArr)>0){
		$GlobID = '';
		if(($MastID != '')&&($MastID != NULL)&&($MastID != 0)){
			$DeleteQuery1 	= "delete from partab_master where mastid = '$MastID'";
			$DeleteSql1 	= mysqli_query($dbConn,$DeleteQuery1);
			
			$DeleteQuery2 	= "delete from parta_details where mastid = '$MastID'";
			$DeleteSql2 	= mysqli_query($dbConn,$DeleteQuery2);
			$GlobIDQuery = "SELECT * FROM partab_master WHERE mastid = '$MastID'";
			$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
			if($GlobIDSql == true){
				if(mysqli_num_rows($GlobIDSql)>0){
					$List = mysqli_fetch_object($GlobIDSql);
					$GlobID = $List->globid;
				}
			}
		}
		
			
		if(($GlobID != '')&&($GlobID != 0)&&($GlobID != NULL)){
			$update_query		= "UPDATE works SET ref_no = '$RefNo', work_name = '$NameWork', est_amount = '$TotAmt', work_status = 'DEU' WHERE globid = '$GlobID'";
			$update_query_sql 	= mysqli_query($dbConn,$update_query);
		}else{
			$update_query		= "INSERT INTO works SET ref_no = '$RefNo', work_name = '$NameWork', est_amount = '$TotAmt', active = '1', work_status = 'DEU'";
			$update_query_sql 	= mysqli_query($dbConn,$update_query);
			$GlobID				= mysqli_insert_id($dbConn);
		}
		//echo $update_query;exit;
		$InsertQuery1 	= "insert into partab_master set ref_no = '$RefNo', tr_id = '', globid = '$GlobID',  work_name = '$NameWork', partA_amount = '$TotAmt', created_date = NOW(), created_by = '$staffid', is_confirmed = 'Y', confirmed_by = '$staffid', confirmed_on = NOW()";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
		$TrMastId		= mysqli_insert_id($dbConn);
		
		foreach($ItemDescArr as $ArrKey => $ArrValue){
			$ItemNo 		= $ItemNoArr[$ArrKey];
			$ItemDesc 		= $ItemDescArr[$ArrKey];
			$ItemQty 		= $ItemQtyArr[$ArrKey];
			$ItemRate 		= $ItemRateArr[$ArrKey];
			$ItemUnit 		= $ItemUnitArr[$ArrKey];
			$ItemAmt 		= $ItemAmountArr[$ArrKey];
			$InsertQuery 	= "insert into parta_details set mastid = '$TrMastId', globid = '$GlobID', sno = '$ItemNo', unit = '$ItemUnit', description = '$ItemDesc', quantity = '$ItemQty',
			supply = '$ItemRate', amount = '$ItemAmt'";
			$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
			if($InsertSql == true){
				$Execute++;
			}
		}
	}
	//print_r($ItemDescArr);
	//exit;
	if($Execute > 0){
		$msg = "Department Estimate Details Saved Successfully";
		UpdateWorkTransaction($GlobID,0,0,"W","Department Estimate Details Uploaded by ".$UserId."","");
		$success = 1;
	}else{
		$msg = "Error :Department Estimate Details Not Saved. Please Try Again.";
		UpdateWorkTransaction($GlobID,0,0,"W","Department Estimate Details Tried to Upload by ".$UserId." but not Uploaded","");
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
		url = "DeptEstimateUploadGenerate.php";
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
	.accordionTitle::before{
    	float: right !important;
	}
	.accordionTitle.is-expanded, dd.is-expanded{
		border:1px solid #035a85;
	}
	.dataTable {
        line-height: 16px !important;
        font-weight: 700 !important;
        color: #74048C;
       font-size: 12px;
	   border-collapse: collapse;
       text-shadow: none;
       text-transform: none;
       font-family: Verdana, Arial, Helvetica, sans-serif;
       line-height: 17px;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" enctype="multipart/form-data" action="">
						<div class="row">
						   <div class="box-container box-container-lg" align="center">
							 <div class="div12">
								  <div class="card cabox">
									   <div class="face-static">
									    	<div class="card-header inkblue-card" align="center">Department Estimate</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
											    <div class="divrowbox pt-2">
													<div class="table-responsive dt-responsive ResultTable">
														<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
														   <div class="div12 dataTable" align="left"><b><input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>"></b> </div>
															 <table width="100%" align="center" class="dataTable table2excel mgtb-8">
															    <thead>
																	<tr class='labeldisplay'>
																		<th valign="middle" nowrap="nowrap">Item No.</th>
																		<th valign="middle">Item Description</th>
																		<th valign="middle">Quantity </th>
																		<th valign="middle"nowrap="nowrap">Rate ( &#8377; )</th>
																		<th valign="middle">Unit</th>
																		<th valign="middle" nowrap="nowrap">Amount ( &#8377; )</th>
																	</tr>
																</thead>
																<tbody>
																<?php 
																//echo $PriceBidLocation.$currentfilename;exit;
																$AllItemArr = array(); $TotalAmount = 0; $DuplicateItemErr = 0; $DuplicateItemArr = array();
																if($checkupload == 1) {
																	$Spreadsheet = new SpreadsheetReader($PriceBidLocation.$currentfilename);
																	$Sheets = $Spreadsheet -> Sheets();
																	foreach ($Sheets as $Index => $Name){ 	// Loop to get all sheets in a file.
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
																				//echo $Row[$AmtColmn];
																				if(($Key >= $StartRow)&&($Key < $EndRow)){
																					if(trim($Row[$DescColmn]) != ''){
																						$ItemNo 		= trim($Row[$ItmNoColmn]);
																						$ItemDesc 		= trim($Row[$DescColmn]);
																						$Pattern 		= array();
																						$Pattern[0] 	= '/"/';
																						$Pattern[1] 	= "/'/";
																						$Pattern[2] 	= '/�/';
																						$Replacement 	= array();
																						$Replacement[0] = '"';
																						$Replacement[1] = "\'";
																						$Replacement[2] = '�';
																						$ItemDesc 		= preg_replace($Pattern, $Replacement, $ItemDesc);
																						$ItemDesc 		= str_replace("'", "'", $ItemDesc);
																						$ItemQty 		= trim($Row[$QtyColmn]);
																						$ItemUnit 		= strtolower(preg_replace('/[.,-]/', '', trim($Row[$UnitColmn])));
																						$ItemRate 		= trim($Row[$RateColmn]);
																						$ItemRate 		= floatval($ItemRate);
																						$ItemRate		= str_replace(",","",$ItemRate);
																						$ItemRate		= str_replace(" ","",$ItemRate);
																						$ItemAmtFile 	= trim($Row[$AmtColmn]);
																						$ItemAmtFile 	= str_replace(" ","",$ItemAmtFile);
																						$ItemAmtFile 	= floatval($ItemAmtFile);
																						$ItemQtyFloat  = floatval($ItemQty);
																						$ItemRateFloat = floatval($ItemRate);
																						$ItemAmtFile1	= $ItemQtyFloat*$ItemRateFloat;
																						//echo $ItemAmtFile1;
																						$ItemType 		= ""; //strtolower(trim($Row[6]));
																						$ItemType1 		= $ItemType;
																						//$ItemNo			= (string)$ItemNo;
																						if(in_array("'$ItemNo'",$AllItemArr)){ // This single quotes and double quotes are here to differentiate single quote and double quote 
																							$DuplicateItem 	= 1;
																							$DupTdStyle 	= "background-color:#DA0532; color:#ffffff; font-weight:bold;";
																							$DuplicateItemErr++;
																						}else{
																							$DuplicateItem 	= 0;
																							$DupTdStyle 	= "";
																						}
																					?>
																				<tr class='labeldisplay'>
																					<td valign='middle' class='tdrow' align="center" style=" <?php echo $DupTdStyle; ?> "><?php echo $ItemNo; ?><input type="hidden" name="txt_item_no[]" value="<?php echo $ItemNo; ?>"></td>
																					<td valign='middle' class='tdrow'align="justify"><?php echo $ItemDesc; ?><input type="hidden" name="txt_item_desc[]" value="<?php echo $ItemDesc; ?>"></td>
																					<td valign='middle' class='tdrow'align="right"><?php echo $ItemQty; ?><input type="hidden" name="txt_item_qty[]" value="<?php echo $ItemQty; ?>"></td>
																					<td valign='middle' class='tdrow' align="right"><?php if(($ItemRate != 0)&&($ItemRate != NULL)&&($ItemRate != '')){ echo IndianMoneyFormat($ItemRate); } ?><input type="hidden" name="txt_item_rate[]" value="<?php echo $ItemRate; ?>"></td>
																					<td valign='middle' class='tdrow' align="center"><?php echo $ItemUnit; ?><input type="hidden" name="txt_item_unit[]" value="<?php echo $ItemUnit; ?>"></td>
																					<td valign='middle' class='tdrow' align="right"><?php if(($ItemAmtFile1 != 0)&&($ItemAmtFile1 != NULL)&&($ItemAmtFile1 != '')){ echo IndianMoneyFormat($ItemAmtFile1); } ?><input type="hidden" name="txt_item_amt[]" value="<?php echo $ItemAmtFile1; ?>"></td>
																			   </tr>
																				<?php
																					if(($ItemAmtFile1 != 0)&&($ItemAmtFile1 != NULL)&&($ItemAmtFile1 != '')){
																						$TotalAmount = $TotalAmount + $ItemAmtFile1;
																					}
																					if($ItemNo != ''){
																						array_push($AllItemArr,"'$ItemNo'");
																						array_push($DuplicateItemArr,$ItemNo);
																					}
																					//print_r($AllItemArr);echo "<br/>";
																				}
																			}
																		} 
																	}
																}
															?>
													<tr class="label">
														<td align="center">&nbsp;</td>
														<td align="right"><b>TOTAL AMOUNT ( &#8377; )&nbsp;</b></td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="right"><b><?php echo IndianMoneyFormat($TotalAmount); ?><input type="hidden" name="txt_total_amt" id="txt_total_amt" value="<?php echo $TotalAmount; ?>"></b></td>
													</tr>
													<!-- <tr class="label">
														<td align="center">&nbsp;</td>
														<td align="right" valign="middle">REBATE ( % )&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="right"><input type="text" name="txt_rebate_perc" id="txt_rebate_perc" class="tboxclass" value="0.00" style="border:2px solid #7D2C9E; text-align:right; font-weight:bold; padding-left:1px;; padding-right:1px;" maxlength="4"></td>
													</tr>
													<tr class="label">
														<td align="center">&nbsp;</td>
														<td align="right" valign="middle">TOTAL AMOUNT AFTER REBATE ( &#8377; )&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="center">&nbsp;</td>
														<td align="right"><input type="text" name="txt_total_with_rebate" id="txt_total_with_rebate" disabled="disabled" class="tboxclass" value="<?php //echo IndianMoneyFormat($TotalAmount); ?>" style="border:2px solid #7D2C9E; text-align:right; font-weight:bold; padding-left:1px; padding-right:1px;"></td>
													</tr> -->
												<?php } ?>
													</tbody>
												</table>
												</div>
												<!-- <div class="div1">&nbsp;</div> -->
											</div>
											<div class="smediv">&nbsp;</div>
									</div>
								</div>
								<?php 
								if($TrCost == $TotalAmount){
									$Valid = 1;
								}else{
									$Valid = 0;
								}
								?>
								<div class="row">
									<?php if($DuplicateItemErr > 0){ ?>
									<div class="div12" align="center" style="color:red;">Error : Duplicate Items exists <?php if(count($DuplicateItemArr)>0){ echo "(".implode(", ",$DuplicateItemArr).")"; } ?> </div>
									<?php } ?>
									<div class="div12" align="center">
										<input type="hidden" name="txt_mastid" id="txt_mastid" value="<?php echo $MastId; ?>">
										<input type="hidden" name="txt_workname" id="txt_workname" value="<?php echo $WorkName; ?>">
										<input type="hidden" name="txt_refno" id="txt_refno" value="<?php echo $RefNo; ?>">
										<input type="hidden" name="txt_globid" id="txt_globid" value="<?php echo $GlobId; ?>">
										<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
										<?php if($DuplicateItemErr == 0){ ?>
										<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
										<?php } ?>
										<input type="hidden" name='hid_status' id='hid_status' class="tboxsmclass" value="<?php echo $Valid; ?>">
									</div>
								</div>  
								<div class="row">&nbsp;</div>                         
							</div>
						</form>
                    </blockquote>
                </div>

            </div>
        </div>
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
						window.location.replace('DeptEstimateUploadGenerate.php');
					}
				}]
			});
		}

		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#confirm", function(event){
				if(KillEvent == 0){
					var WorkName 	= $("#txt_workname").val(); //alert(TenNo);
					var TotAmt  	= $("#txt_total_amt").val();   
					if(WorkName == ""){
						BootstrapDialog.alert("Invalid Work. Unable to Save.");
						event.preventDefault();
						event.returnValue = false;
					}else if(Number(TotAmt) <= 0){ 
						BootstrapDialog.alert("Estimate amount should be greater than 0. Please Check it and Re upload again..!");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure want to Upload this Department Estimate', function(result){
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

