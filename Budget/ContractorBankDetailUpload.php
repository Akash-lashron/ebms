<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Contractor Detail Upload';
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
$PriceBidLocation = 'uploads/';//"PriceBid/";
//echo $PriceBidLocation;exit;
$RowCount = 0;

if(isset($_POST["upload"]) == "Upload File"){
	$TrId 			= 1;
	$SheetName 		= $_POST['txt_sheetname'];
	$StartRow 		= $_POST['txt_start_row'];
	$EndRow 		= $_POST['txt_end_row'];
	$UploadFile 	= $_FILES['file']['name'];

	$ContMasterArr1 	= array();	
	$ContSelQuery 		= "SELECT cont_temp_id FROM cont_master_temp WHERE active ='1'";
	$ContSelQuerySql 	= mysqli_query($dbConn,$ContSelQuery);
	if($ContSelQuerySql == true){
		while($ContList 	= mysqli_fetch_object($ContSelQuerySql)){
			$ContTempId = $ContList->cont_temp_id;
			$ContMasterArr1[] = $ContTempId;
		}
	}
	if(count($ContMasterArr1) != 0){
		$DeleteMasterQuery 		="TRUNCATE TABLE cont_master_temp";
		$DeleteMasterQuerySql 	= mysqli_query($dbConn,$DeleteMasterQuery);
		$DeleteDetailQuery 		="TRUNCATE TABLE contractor_bank_detail_temp";
		$DeleteDetailQuerySql 	= mysqli_query($dbConn,$DeleteDetailQuery);
	}else{
		//echo 2;exit;
	}
	if($_FILES['file']['name'] != ""){
      $target_dir 		= $PriceBidLocation;	//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//"PriceBid/";
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
	//echo $target_file;exit;
	$work_order_cost = 0;
	$first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
	$slno = '';
}

if(isset($_POST["confirm"]) == " CONFIRM "){

	$ContMasterArr 	= array();	
	$ContSelQuery 		= "SELECT cont_temp_id,contractor_name FROM cont_master_temp WHERE active ='1'";
	$ContSelQuerySql 	= mysqli_query($dbConn,$ContSelQuery);
	if($ContSelQuerySql == true){
		while($ContList 	= mysqli_fetch_object($ContSelQuerySql)){
			$ContTempId = $ContList->cont_temp_id;
			$ContName 	= $ContList->contractor_name;
			$ContMasterArr[$ContName] = $ContTempId;
		}
  	}
	//print_r($ContMasterArr);exit;

	$TrId 			        	= 1;
	$ContIdArr 		       	= $_POST['txt_cont_id'];
	$ContNameArr 	        	= $_POST['txt_cont_name'];
	$ContAccHoldNameArr 	   = $_POST['txt_acc_hold_name'];
	$ContAccNoArr 	        	= $_POST['txt_acc_hold_num'];
	$ContBankNameArr 	    	= $_POST['txt_bank_name'];
	$ContBranchNameArr   	= $_POST['txt_branch_name'];
	$ContModeofPaymentArr	= $_POST['txt_pay_mode'];
	$ContAmountArr 			= $_POST['txt_cont_amt'];
	$ContIFSCArr 				= $_POST['txt_ifsc'];

	$ContAddrArr 	 	= $_POST['txt_cont_addr'];
	$PanNumArr 	        = $_POST['txt_pan_no'];
	$PanTypeArr 	    = $_POST['txt_pan_type'];
	$GstNumArr 	        = $_POST['txt_gst_no'];
	$ContStateNameArr 	= $_POST['txt_state_name'];
	$IsLdcArr 	       	= $_POST['txt_is_ldc'];
	$LdcCertNumArr 	   	= $_POST['txt_ldc_cert_num'];
	$LdcMaxAmtArr 	   	= $_POST['txt_ldc_max_amt'];
	$LdcFrDateArr 	   	= $_POST['txt_ldc_from_date'];
	$LdcToDateArr 	   	= $_POST['txt_ldc_to_date'];
	$ItPercArr 	        = $_POST['txt_it_perc'];

	$Execute = 0;

	//echo $TotAmountAfReb;exit;
	

	foreach($ContNameArr as $ArrKey => $ArrValue){
		$ContId 		    	 = $ContMasterArr[$ArrValue];
		$ContName 		    = $ContNameArr[$ArrKey];
		$ContAccHoldName   = $ContAccHoldNameArr[$ArrKey];
		$ContAccNo 		    = $ContAccNoArr[$ArrKey];
		$ContBankName 		 = $ContBankNameArr[$ArrKey];
		$ContBranchName    = $ContBranchNameArr[$ArrKey];
		$ContModeofPayment = $ContModeofPaymentArr[$ArrKey];
		$ContAmount 		 = $ContAmountArr[$ArrKey];
		$ContIFSC 		    = $ContIFSCArr[$ArrKey];


		$InsertQuery1 = "INSERT INTO contractor_bank_detail_temp SET contid = '$ContId',bank_acc_hold_name = '$ContAccHoldName',
		bank_acc_no = '$ContAccNo', bank_name = '$ContBankName', branch_address = '$ContBranchName', ifsc_code = '$ContIFSC', active = '1',
		pay_mode ='$ContModeofPayment', amount ='$ContAmount'";
	
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
		//$BidderMastId	= mysqli_insert_id($dbConn);

		if($InsertSql1 == true){
			$Execute++;
		}
	}
	//print_r($ItemDescArr);
	//exit;
	if($Execute > 0){
		$msg = "Contractor Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Contractor Details Not Saved.. Please Try Again.";
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
	   	url = "ContractorBankDetailGenerate.php";
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
        <form action="ContractorBankDetailUpload.php" method="post" enctype="multipart/form-data" name="form">
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
													<div class="card-header inkblue-card" align="center">Contractor Details- Upload</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<table class="DispTable" width="100%">
																		<thead>
																			<tr>
																				<th>CONTRACTOR CODE</th>
																				<th>NAME OF THE CONTRACTOR</th>
																				<th>ACCOUNT HOLDER NAME</th>
																				<th>ACCOUNT HOLDER</br>NUMBER</th>
																				<th>NAME OF THE BANK</th>
																				<th>NAME OF THE BRANCH</th>
																				<th>MODE OF PAYMENT</th>
																				<th>AMOUNT</th>
																				<th>IFSC CODE</th>
																				<th>CONTRACTOR ADDRESS</th>
																				<th>PAN NO</th>
																				<th>PAN TYPE</br>(Individual/Others)</th>
																				<th>GST NO</th>
																				<th>STATE NAME</th>
																				<th>LDC (YES/NO)</th>
																				<th>Certificate No.</th>
																				<th>Maximum Amount</br>(Current Financial Year)</th>
																				<th>From Date</th>
																				<th>To Date</th>
																				<th>IT Percentage</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php 
																		//echo $currentfilename;exit;
																		
																		$AllItemArr = array(); $TotalAmount = 0;
																		if($checkupload == 1) {	//echo $PriceBidLocation.$currentfilename;exit;
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
																								$ContCode		= trim($Row[0]);
																								$ContTittle		= trim($Row[1]);
																								$ContName 		= trim($Row[2]);
																								$AccHoldName	= trim($Row[3]);
																								$AccNumber		= trim($Row[4]);
																								$BankName	   = trim($Row[5]);
																								$BankBranch	   = trim($Row[6]);
																								$PayMode	   = trim($Row[7]);
																								$ContAmount	   = trim($Row[8]);
																								$IfscCode	   = trim($Row[9]);
																								$ContAddr	   = trim($Row[10]);
																								$PanNum	      = trim($Row[11]);
																								$PanType	      = trim($Row[12]);
																								$GstNum	     	= trim($Row[13]);
																								$StateName	   = trim($Row[14]);
																								$IsLDC 		   = trim($Row[15]);
																								$LDCCertNum 	= trim($Row[16]);
																								$LDCMaxAmt 	 	= trim($Row[17]);
																								$LDCFrDt 		= trim($Row[18]);
																								$LDCToDt 	   = trim($Row[19]);
																								$ITPerc 		   = trim($Row[20]);
																								
																								
																								$ContInsertQuery = "INSERT INTO cont_master_temp SET cont_code_frfcf='$ContCode', contractor_title='$ContTittle',contractor_name='$ContName',contractor_addr='$ContAddr', state_contractor='$StateName', pan_no='$PanNum',
																								gst_no='$GstNum', pan_type='$PanType', gst_type='', is_ldc_appl='$IsLDC', ldc_certi_no='$LDCCertNum', ldc_validty_from='$LDCFrDt',
																								ldc_max_amt='$LDCMaxAmt', ldc_validity='$LDCToDt', ldc_rate='$ITPerc', active='1'";
																								//echo $ContInsertQuery;exit;
																								$ContInsertQuerySql	= mysqli_query($dbConn,$ContInsertQuery);
																							?>

																							<tr>
																								<td align="left"><?php echo $ContCode; ?><input type="hidden" name="txt_cont_code[]" value="<?php echo $ContCode; ?>"></td>
																								<td align="left"><?php echo $ContName; ?><input type="hidden" name="txt_cont_name[]" value="<?php echo $ContName; ?>"></td>
																								<td align="left"><?php echo $AccHoldName; ?><input type="hidden" name="txt_acc_hold_name[]" value="<?php echo $AccHoldName; ?>"></td>
																								<td align="left"><?php echo $AccNumber; ?><input type="hidden" name="txt_acc_hold_num[]" value="<?php echo $AccNumber; ?>"></td>
																								<td align="left"><?php echo $BankName; ?><input type="hidden" name="txt_bank_name[]" value="<?php echo $BankName; ?>"></td>
																								<td align="left"><?php echo $BankBranch; ?><input type="hidden" name="txt_branch_name[]" value="<?php echo $BankBranch; ?>"></td>
																								<td align="left"><?php echo $PayMode; ?><input type="hidden" name="txt_pay_mode[]" value="<?php echo $PayMode; ?>"></td>
																								<td align="left"><?php echo $ContAmount; ?><input type="hidden" name="txt_cont_amt[]" value="<?php echo $ContAmount; ?>"></td>
																								<td align="right"><?php echo $IfscCode; ?><input type="hidden" name="txt_ifsc[]" value="<?php echo $IfscCode; ?>"></td>
																								<td align="right"><?php echo $ContAddr; ?><input type="hidden" name="txt_cont_addr[]" value="<?php echo $ContAddr; ?>"></td>
																								<td align="right"><?php echo $PanNum; ?><input type="hidden" name="txt_pan_no[]" value="<?php echo $PanNum; ?>"></td>
																								<td align="right"><?php echo $PanType; ?><input type="hidden" name="txt_pan_type[]" value="<?php echo $PanType; ?>"></td>
																								<td align="justify"><?php echo $GstNum; ?><input type="hidden" name="txt_gst_no[]" value="<?php echo $GstNum; ?>"></td>
																								<td align="justify"><?php echo $StateName; ?><input type="hidden" name="txt_state_name[]" value="<?php echo $StateName; ?>"></td>
																								<td align="justify"><?php echo $IsLDC; ?><input type="hidden" name="txt_is_ldc[]" value="<?php echo $IsLDC; ?>"></td>
																								<td align="justify"><?php echo $LDCCertNum; ?><input type="hidden" name="txt_ldc_cert_num[]" value="<?php echo $LDCCertNum; ?>"></td>
																								<td align="justify"><?php echo $LDCMaxAmt; ?><input type="hidden" name="txt_ldc_max_amt[]" value="<?php echo $LDCMaxAmt; ?>"></td>
																								<td align="justify"><?php echo $LDCFrDt; ?><input type="hidden" name="txt_ldc_from_date[]" value="<?php echo $LDCFrDt; ?>"></td>
																								<td align="justify"><?php echo $LDCToDt; ?><input type="hidden" name="txt_ldc_to_date[]" value="<?php echo $LDCToDt; ?>"></td>
																								<td align="justify"><?php echo $ITPerc; ?><input type="hidden" name="txt_it_perc[]" value="<?php echo $ITPerc; ?>"></td>
																							</tr>
																							<?php
																								if(($ItemAmtFile != 0)&&($ItemAmtFile != NULL)&&($ItemAmtFile != '')){
																									$TotalAmount = $TotalAmount + $ItemAmtFile;
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
																		<?php } ?>
																		</tbody>
																	</table>
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
							<div class="row">
								<div class="div12" align="center">
									<input type="hidden" name="txt_TrId" id="txt_TrId" value="<?php echo $TrId; ?>">
									<input type="hidden" name="txt_bidderid" id="txt_bidderid" value="<?php echo $BidderId; ?>">
									<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
									<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
								</div>
							</div>  
							<div class="row">&nbsp;</div>                         
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
						window.location.replace('ContractorBankDetailGenerate.php');
					}
				}]
			});
		}

		var KillEvent = 0;
	
		
	</script>
    </body>
</html>

