<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Financial Bid Upload';
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
$PriceBidLocation = 'PriceBid/';	//$_SERVER['DOCUMENT_ROOT'].'/Budget/PriceBid/';//
$RowCount = 0;

if(isset($_POST["btn_upload"]) == "Upload File"){
	$TrId 			= $_POST['cmb_tr_no'];
	$WorkName		= $_POST['txt_work_name'];
	$SheetName 		= $_POST['txt_sheetname'];
	$BidderId 		= $_POST['cmb_bidder'];
	$StartRow 		= $_POST['txt_start_row'];
	$EndRow 		= $_POST['txt_end_row'];
	$UploadFile 	= $_FILES['file']['name'];

	$SelectContNameQuery = "SELECT contid,name_contractor FROM contractor WHERE contid = '$BidderId' AND active = 1 ORDER BY contid ASC";
	$SelectContNameQuerySql 	= mysqli_query($dbConn,$SelectContNameQuery);
	if($SelectContNameQuerySql == true){
		if(mysqli_num_rows($SelectContNameQuerySql) > 0){
			$ContListA = mysqli_fetch_object($SelectContNameQuerySql);
			$SelContName = $ContListA->name_contractor;
			//$ContNameId = $ContListA->contid;
			//$ContNameArr[$ContNameId] = $ContNameA;
		}
	}

	if($_FILES['file']['name'] != ""){
        $target_dir 		= $PriceBidLocation;	//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//
		//echo $target_dir; exit;
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir.$TrId.basename($_FILES["file"]["name"]);
        $currentfilename 	= $TrId.basename($_FILES["file"]["name"]);
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
}
if(isset($_POST["confirm"]) == " CONFIRM "){
	$TrId 			= $_POST['txt_TrId'];
	$BidderId 		= $_POST['txt_bidderid'];
	$RebatePerc 	= $_POST['txt_hid_rebate_perc'];
	$ItemNoArr 		= $_POST['txt_item_no'];
	$ItemDescArr 	= $_POST['txt_item_desc'];
	$ItemQtyArr 	= $_POST['txt_item_qty'];
	$ItemRateArr 	= $_POST['txt_item_rate'];
	$ItemUnitArr 	= $_POST['txt_item_unit'];
	$ItemAmountArr = $_POST['txt_item_amt'];
	$TotAmount 		= $_POST['txt_total_amt'];
	$TotAmountAfReb = $_POST['txt_hid_total_with_rebate'];
	$Execute = 0;

	//echo $TotAmountAfReb;exit;
	//echo "-";echo $SelContName;
	if(count($ItemDescArr)>0){
		$DeleteQuery1 	= "delete from bidder_bid_master where tr_id = '$TrId' and contid = '$BidderId'";
		$DeleteSql1 	= mysqli_query($dbConn,$DeleteQuery1);
		
		$DeleteQuery2 	= "delete from bidder_bid_details where tr_id = '$TrId' and contid = '$BidderId'";
		$DeleteSql2 	= mysqli_query($dbConn,$DeleteQuery2);
		
		$EstId = 0;
		// $SelectQuery2 	= "select est_id from tender_register where tr_id = '$TrId'";
		// $SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
		// if($SelectSql2 == true){
		// 	if(mysqli_num_rows($SelectSql2)>0){
		// 		$List2 = mysqli_fetch_object($SelectSql2);
		// 		$EstId = $List2->est_id;
		// 	}
		// }
		$GlobID= '';
		$GlobIDQuery = "SELECT globid FROM tender_register WHERE tr_id = '$TrId'";
		$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
		if($GlobIDSql == true){
			if(mysqli_num_rows($GlobIDSql)>0){
				$List = mysqli_fetch_object($GlobIDSql);
				$GlobID = $List->globid;
			}
		}

		$update_query	= "UPDATE works SET est_amount='$TotAmt', work_status='FBU' WHERE globid = '$GlobID'";
		$update_query_sql = mysqli_query($dbConn,$update_query);

		$InsertQuery1 	= "INSERT INTO bidder_bid_master SET tr_id = '$TrId', globid='$GlobID', contid = '$BidderId',quoted_amt = '$TotAmount',
		rebate_perc = '$RebatePerc', quoted_amt_af_reb = '$TotAmountAfReb', status = '', price_bid_file = '',
		createdby ='".$_SESSION['userid']."', createdon = NOW()";
		//echo $InsertQuery1;exit;
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
		$BidderMastId	= mysqli_insert_id($dbConn);

		foreach($ItemDescArr as $ArrKey => $ArrValue){
			$ItemNo 		= $ItemNoArr[$ArrKey];
			$ItemDesc 		= $ItemDescArr[$ArrKey];
			$ItemQty 		= $ItemQtyArr[$ArrKey];
			$ItemRate 		= $ItemRateArr[$ArrKey];
			$ItemUnit 		= $ItemUnitArr[$ArrKey];
			$ItemAmt 		= $ItemAmountArr[$ArrKey];
			$InsertQuery 	= "insert into bidder_bid_details set bmid = '$BidderMastId', globid='$GlobID', tr_id = '$TrId', est_id = '$EstId', contid = '$BidderId', item_no = '$ItemNo', item_desc = '$ItemDesc', item_unit = '$ItemUnit', 
							   item_qty = '$ItemQty', item_rate = '$ItemRate', createdon = NOW(), createdby = '".$_SESSION['userid']."'";
			$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
			if($InsertSql == true){
				$Execute++;
			}
		}
	}
	//print_r($ItemDescArr);
	//exit;
	if($Execute > 0){
		$msg = "Financial Bid Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Financial Bid Details Not Saved. Please Try Again.";
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
	   	url = "PriceBidUploadGenerate.php";
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
	.HideDesc{
		max-width : 768px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
</style>
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
					   <div class="row">
							<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Financial Bid - Upload</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12" align="left">
																	<b>
																		<div class="div12 namebox">
																			<table class="nborder">
																				<tr>
																					<td nowrap="nowrap">Name Of Work : </td>
																					<td><?php if(isset($WorkName)){ echo $WorkName; } ?></td>
																				</tr>
																				<tr>
																					<td nowrap="nowrap">Bidder Name &emsp;: </td>
																					<td><?php if(isset($SelContName)){ echo $SelContName; } ?></td>
																				</tr>
																			</table>
																		</div>
																		<div class="row smclearrow"></div>
																	</b> 
																</div>
															   <!--	<div class="div12 dataTable" align="left">
																	<b>
																		<input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>">
																	</b>
																</div>	-->
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr class='labeldisplay'>
																			<th valign="middle" nowrap="nowrap">Item No.</th>
																			<th valign="middle">Item Description</th>
																			<th valign="middle">Quantity</th>
																			<th valign="middle" width="50px;" nowrap="nowrap">Rate<br> ( &#8377; )</th>
																			<th valign="middle">Unit</th>
																			<th valign="right" width="150px;">Amount ( &#8377; )</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php  //echo $currentfilename;exit;
																		$AllItemArr = array(); $TotalAmount = 0;
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
																								$ItemNo 		= trim($Row[0]);
																								$ItemDesc 		= trim($Row[1]);
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
																								$ItemQty 		= trim($Row[2]);
																								$ItemQty 		= floatval($ItemQty);
																								$ItemUnit 		= strtolower(preg_replace('/[.,-]/', '', trim($Row[4])));
																								//echo $ItemUnit;exit;
																								$ItemRate 		= trim($Row[3]);
																								$ItemRate		= str_replace(",","",$ItemRate);
																								$ItemRate 		= floatval($ItemRate);
																								$ItemAmtFile 	= trim($Row[5]);
																								$ItemAmtFile 	= floatval($ItemAmtFile);
																								$ItemAmtCalc 	= round($ItemQty*$ItemRate,2);
																								if($ItemUnit == " "){
																									$ItemQty 	= "";
																									$ItemRate	= "";
																									$ItemAmtCalc = "";
																								}
																								$ItemType 		= strtolower(trim($Row[6]));
																								$ItemType1 		= $ItemType;
																								if(in_array($ItemNo,$AllItemArr)){
																									$DuplicateItem 	= 1;
																									$DupTdStyle 	= "background-color:#DA0532; color:#ffffff; font-weight:bold;";
																								}else{
																									$DuplicateItem 	= 0;
																									$DupTdStyle 	= "";
																								}
																							?>
																							<tr class='labeldisplay'>
																								<td valign='middle' class='tdrow' align="center" style=" <?php echo $DupTdStyle; ?> "><?php echo $ItemNo; ?><input type="hidden" name="txt_item_no[]" value="<?php echo $ItemNo; ?>"></td>
																								<td valign='middle' class='tdrow' align="justify"><?php echo $ItemDesc; ?><input type="hidden" name="txt_item_desc[]" value="<?php echo $ItemDesc; ?>"></td>
																								<td valign='middle' class='tdrow' align="right"><?php echo $ItemQty; ?><input type="hidden" name="txt_item_qty[]" value="<?php echo $ItemQty; ?>"></td>
																								<td valign='middle' class='tdrow' align="right"><?php echo $ItemRate; ?><input type="hidden" name="txt_item_rate[]" value="<?php echo $ItemRate; ?>"></td>
																								<td valign='middle' class='tdrow' align="center"><?php echo $ItemUnit; ?><input type="hidden" name="txt_item_unit[]" value="<?php echo $ItemUnit; ?>"></td>
																								<td valign='middle' class='tdrow' align="right"><?php if(($ItemAmtCalc != 0)&&($ItemAmtCalc != NULL)&&($ItemAmtCalc != '')){ echo IndianMoneyFormat($ItemAmtCalc); } ?><input type="hidden" name="txt_item_amt[]" value="<?php echo $ItemAmtCalc; ?>"></td>
																							</tr>
																							<?php
																								if(($ItemAmtCalc != 0)&&($ItemAmtCalc != NULL)&&($ItemAmtCalc != '')){
																									$TotalAmount = $TotalAmount + $ItemAmtCalc;
																								}
																								if($ItemNo != ''){
																									array_push($AllItemArr,$ItemNo);
																								}
																							}
																						}
																					} 
																				}
																			}
																		?>
																		<tr class='labeldisplay'>
																			<td align="center">&nbsp;</td>
																			<td align="right">TOTAL AMOUNT ( &#8377; )&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="right"><?php echo IndianMoneyFormat($TotalAmount); ?><input type="hidden" name="txt_total_amt" id="txt_total_amt" value="<?php echo $TotalAmount; ?>"></td>
																		</tr>
																		<tr class='labeldisplay'>
																			<td align="center">&nbsp;</td>
																			<td align="right" valign="middle">REBATE ( % )&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="right"><input type="text" name="txt_rebate_perc" id="txt_rebate_perc" class="tboxclass" value="0.00" style="border:2px solid #7D2C9E; text-align:right; font-weight:bold; padding-left:1px;; padding-right:1px;" maxlength="4"></td>
																			<input type="hidden" name="txt_hid_rebate_perc" id="txt_hid_rebate_perc" class="tboxclass" value="0.00" >
																		</tr>
																		
																		<tr class='labeldisplay'>
																			<td align="center">&nbsp;</td>
																			<td align="right" valign="middle"> REBATE VALUE ( &#8377; )&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="right"><input type="text" name="txt_rebateval" id="txt_rebateval" disabled="disabled" class="tboxclass" value=""  style="border:2px solid #7D2C9E; text-align:right; font-weight:bold; padding-left:1px; padding-right:1px;"></td>
																			<input type="hidden" name="txt_hid_rebateval" id="txt_hid_rebateval" class="tboxclass" value="<?php echo $TotalAmount; ?>">
																		</tr>
																		<tr class='labeldisplay'>
																			<td align="center">&nbsp;</td>
																			<td align="right" valign="middle">TOTAL AMOUNT AFTER REBATE ( &#8377; )&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="center">&nbsp;</td>
																			<td align="right"><input type="text" name="txt_total_with_rebate" id="txt_total_with_rebate" disabled="disabled" class="tboxclass" value="<?php echo IndianMoneyFormat($TotalAmount); ?>"  style="border:2px solid #7D2C9E; text-align:right; font-weight:bold; padding-left:1px; padding-right:1px;"></td>
																			<input type="hidden" name="txt_hid_total_with_rebate" id="txt_hid_total_with_rebate" class="tboxclass" value="<?php echo $TotalAmount; ?>">
																		</tr>
																		<?php } ?>
																	</tbody>
														 		</table>
														 </div>
												<!-- <div class="div1">&nbsp;</div> -->
											</div>
											<div class="smediv">&nbsp;</div>
									</div>
								</div>
								<div class="row">
									<div class="div12" align="center">
										<input type="hidden" name="txt_TrId" id="txt_TrId" value="<?php echo $TrId; ?>">
										<input type="hidden" name="txt_bidderid" id="txt_bidderid" value="<?php echo $BidderId; ?>">
										<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
										<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
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
						window.location.replace('PriceBidUploadGenerate.php');
					}
				}]
			});
		}

		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#confirm", function(event){
				if(KillEvent == 0){
					var TrId 		= $("#txt_TrId").val();
					var BidderId 	= $("#txt_bidderid").val();
					var RebatePerc = $("#txt_rebate_perc").val();
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
						BootstrapDialog.confirm('Are you sure want to confirm with '+RebatePerc+' % Rebate ?', function(result){
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
				$("#txt_hid_rebate_perc").val(RebatePerc);
				var TotalAmount  = $("#txt_total_amt").val();
				var RebateAmount = Number(TotalAmount)*Number(RebatePerc) / 100;
				var RebateAmountval = RebateAmount.toFixed(2);
				var TotAmtWithRebate = Number(TotalAmount)-Number(RebateAmount);
					TotAmtWithRebate = TotAmtWithRebate.toFixed(2);
					TotAmtWithRebateForHid = TotAmtWithRebate;
				if(TotAmtWithRebate != ""){
					var FinalAmount = FormatNumberToINR(TotAmtWithRebate);
					var FinalAmountForHid = TotAmtWithRebateForHid;
				}else{
					var FinalAmount = "";
					var FinalAmountForHid = "";
				}
				var RebatePerc1 = Number(RebatePerc).toFixed(2);
				var RebatePerc2 = FormatNumberToINR(RebatePerc1);
				$("#txt_rebateval").val(RebateAmountval); 
				$("#txt_rebate_perc").val(RebatePerc2);   
				$("#txt_total_with_rebate").val(FinalAmount);
				$("#txt_hid_total_with_rebate").val(FinalAmountForHid);
			});
		});
	</script>
    </body>
</html>

