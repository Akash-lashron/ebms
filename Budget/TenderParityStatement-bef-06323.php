<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';			// 11-11-2022 COMMENTED LINE
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'Tender Parity Statement';
$msg = '';
$staffid  = $_SESSION['sid'];
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
$RowCount = 0; $IsDeptEst = 0; $View = 0; $Pricebid =0;
if(isset($_POST['btnView'])){   

	$txt_ccno = $_POST['txt_ccno'];
	
	if(($txt_ccno != "")||($txt_ccno != NULL)){
		$SelectSheetIdQuery	= "SELECT sheet_id,globid,tr_id,computer_code_no FROM sheet WHERE computer_code_no = '$txt_ccno'";
		$SelectSheetIdQuerySql 	= mysqli_query($dbConn,$SelectSheetIdQuery);
		if($SelectSheetIdQuerySql == true){
			$ShList = mysqli_fetch_object($SelectSheetIdQuerySql);
			$sheet_id = $ShList->sheet_id;
			$tr_id 	 = $ShList->tr_id;
			$globid 	 = $ShList->globid;
			$computer_code_no = $ShList->computer_code_no;
		}
	} 
	// 	mbquantity
	if(($sheet_id != 0)||($sheet_id != "")&&($sheet_id != NULL)){
		$UsedQtyArr = array(); 
		$MBQtyArr = array(); 
		$SelectMbookQuery = "SELECT * FROM measurementbook WHERE sheetid = '$sheet_id' AND (part_pay_flag = '0' OR part_pay_flag = '1') ORDER BY measurementbookid ASC";
		$SelectMbookQuerySql = mysqli_query($dbConn,$SelectMbookQuery);
		if(($SelectMbookQuerySql == true)&&(mysqli_num_rows($SelectMbookQuerySql)>0)){
			while($MBList=mysqli_fetch_object($SelectMbookQuerySql)){ 
				$MBsheetid = $MBList->sheetid;
				$subdivid 	= $MBList->subdivid;
				$mbquantity   = $MBList->mbquantity;
				//$DeptQuantity  = $MBList->quantity;
				$MBQtyArr[$subdivid] = $mbquantity;
				//$UsedDeptRateArr[$sheet_id][$DeptItemNo]= $DeptRate;
				//$DeptQuantityArr[$sheet_id][$DeptItemNo]= $DeptQuantity;
			}
		}
	
	
		//echo $SelectScheduleQuery;exit;
		$SelectScheduleQuery = "SELECT * FROM schdule WHERE sheet_id = '$sheet_id' ORDER BY sch_id ASC"; 
		$SelectScheduleQuerySql = mysqli_query($dbConn,$SelectScheduleQuery); 
		/* 
		if($SelectScheduleQuerySql == true){ 
			if(mysqli_num_rows($SelectScheduleQuerySql)>0){ 
				while($SchList=mysqli_fetch_object($SelectScheduleQuerySql)){ 
					$SchItemNo = $SchList->sno; 
					$ItemNo     = $SchList->total_quantity; 
					$ItemRate   = $SchList->per; 
					$UsedQtyArr[$ContractId][$SchItemNo]= $ItemRate; 
				}
			}
		} 
		*/
		//$SelectScheduleQuery = "SELECT * FROM measurementbook WHERE part_pay_flag = '0' OR part_pay_flag = '1' ORDER BY measurementbookid ASC";
		//$SelectScheduleQuerySql = mysqli_query($dbConn,$SelectScheduleQuery);
	
		$UsedDeptItmDesc = array();
		$UsedDeptRateArr = array();
		$DeptQuantityArr = array();
		$SelectQuery 	= "SELECT * FROM parta_details WHERE globid = '$globid' ORDER BY sno ASC";
		$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true){
			if(mysqli_num_rows($SelectSql)>0){
				while($DeptList=mysqli_fetch_object($SelectSql)){ 
					$DeptItemNo = $DeptList->sno;
					$DeptDesc 	= $DeptList->description;
					$DeptRate   = $DeptList->supply;
					$DeptQuantity   = $DeptList->quantity;
					$UsedDeptItmDesc[$sheet_id][$DeptItemNo]= $DeptDesc;
					$UsedDeptRateArr[$sheet_id][$DeptItemNo]= $DeptRate;
					$DeptQuantityArr[$sheet_id][$DeptItemNo]= $DeptQuantity;
				}
			}
		}
		//print_r($DeptQuantityArr);exit;
		$SelectQuery1 = "SELECT contid FROM bidder_bid_master where tr_id = '$tr_id' ORDER BY tr_id ASC";
		$SelectSql1	  = mysqli_query($dbConn,$SelectQuery1);
		if(($SelectSql == true) || ($SelectSql1 == true)){ 
			if($SelectSql == true) { 
				if(mysqli_num_rows($SelectSql)>0){
					$IsDeptEst = 1;
				}
			}
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){ 
					$Pricebid = 1;
				}
			}
		}
		if($IsDeptEst == 0){ 
			$msg = "Dept Estimate Is not Uploaded Yet";
			$View = 0;
	
		}/*else if($Pricebid == 0){
			$msg = "Finacial Bid Is not Uploaded Yet";
			$View = 0;
		}*/else{
			$msg = '';
			$View = 1;
		}
		$ContArr = array();
		$ContNameArr = array();
		$RebatePercArr = array();
		$SelectQuery = "SELECT * FROM bidder_bid_master where tr_id = '$tr_id' ORDER BY quoted_amt_af_reb ASC";
		$ResultQuery = mysqli_query($dbConn,$SelectQuery);
		if($ResultQuery==true){
			if(mysqli_num_rows($ResultQuery)>0){
				while($Result = mysqli_fetch_object($ResultQuery)){
					$ContId 		= $Result->contid;
					$GlobId 		= $Result->globid;
					$RebatePer 	= $Result->rebate_perc;
					$partA_amount	= $Result->partA_amount;
					array_push($ContArr,$ContId);
					$SeclectQuery1 = "SELECT name_contractor FROM contractor where contid = '$ContId'";
					$ResultQuery1  = mysqli_query($dbConn,$SeclectQuery1);
					if($ResultQuery1 == true){
						if(mysqli_num_rows($ResultQuery1)>0){
							$Result1 = mysqli_fetch_object($ResultQuery1);
							$ContName= $Result1->name_contractor;
							$Totalaftreb= $Result1->quoted_amt_af_reb;
							$ContNameArr[$ContId] = $ContName;
							$RebatePercArr[$ContId] = $RebatePer;
						}
					}
				}
			}
		}
		$BidderRateArr  = array();
		$SelectSqlQuery = "SELECT * FROM bidder_bid_details where tr_id = '$tr_id' order by bdid asc";
		$ResultSqlQuery = mysqli_query($dbConn,$SelectSqlQuery);
		if($ResultSqlQuery == true){
			if(mysqli_num_rows($ResultSqlQuery)>0){
				while($CList=mysqli_fetch_object($ResultSqlQuery)){
					$ContractId = $CList->contid;
					$ItemNo     = $CList->item_no;
					$ItemRate   = $CList->item_rate;
					$BidderRateArr[$ContractId][$ItemNo]= $ItemRate;
				}
			}
		}	
	}
	//echo $tr_id;exit;
	// /print_r($BidderRateArr);exit;
	//echo $SeclectQuery1;exit;
}
/*if(isset($_POST['View']) == " View "){   
	$MastId 	 = $_POST['cmb_shortname'];
	$PageId 	 = $_POST['txt_pageid'];
	//echo $PageId;exit;
	$HiddenClass = 'hide';
	$ContArr  	 = array();
	$ContNameArr = array();
	$SelectQuery = "SELECT DISTINCT contid FROM bidder_bid_details where tr_id = '$MastId' ";
	$ResultQuery = mysqli_query($dbConn,$SelectQuery);
	if($ResultQuery==true){
		if(mysqli_num_rows($ResultQuery)>0){
			while($Result = mysqli_fetch_object($ResultQuery)){
				$ContId = $Result->contid;
				array_push($ContArr,$ContId);
				$SeclectQuery1 = "SELECT name_contractor FROM contractor where contid = '$ContId'";
				$ResultQuery1  = mysqli_query($dbConn,$SeclectQuery1);
				if($ResultQuery1 == true){
					if(mysqli_num_rows($ResultQuery1)>0){
						$Result1 = mysqli_fetch_object($ResultQuery1);
						$ContName= $Result1->name_contractor;
						$ContNameArr[$ContId] = $ContName;
					}
				}
			}
		}
	}
//print_r($ContArr);
	$BidderRateArr  = array();
	$SelectSqlQuery = "SELECT * FROM bidder_bid_details where tr_id = '$MastId'";
	$ResultSqlQuery = mysqli_query($dbConn,$SelectSqlQuery);
	if($ResultSqlQuery == true){
		if(mysqli_num_rows($ResultSqlQuery)>0){
			while($CList=mysqli_fetch_object($ResultSqlQuery)){
				$ContractId 						= $CList->contid;
				$ItemNo     						= $CList->item_no;
				$ItemRate   						= $CList->item_rate;
				$BidderRateArr[$ContractId][$ItemNo]= $ItemRate;
			}
		}
	}
	
	$MastABId = '';
	$SelectEstQuery = "SELECT mastid FROM partab_master where tr_id = '$MastId'";
	$SelectEstSql 	= mysqli_query($dbConn,$SelectEstQuery);
	if($SelectEstSql == true){
		if(mysqli_num_rows($SelectEstSql)>0){
			$CList = mysqli_fetch_object($SelectEstSql);
			$MastABId = $CList->mastid;
		}
	}
}*/
if(isset($_POST['back'])){
		header('Location: TenderParityStatement.php');	
}
/*
if(isset($_POST['sendtoacc'])){
	$msg = '';
	$TenderId = $_POST['txt_tr_id']; 
	$PageId 	 = $_POST['txt_page_id'];
	if(isset($TenderId)){
		$SelectEstQuery = "UPDATE tender_register SET cst_status = 'A', cst_sent_acc_by ='$staffid', cst_sent_acc_on = NOW() WHERE tr_id = '$TenderId'";
		$SelectEstSql 	= mysqli_query($dbConn,$SelectEstQuery);
		if($SelectEstSql == true){ 
			$msg = "Comparative Statement Forwarded to Accounts Successfully..!!";
		}else{
			$msg = "Error: Comparative Statement Not Forwarded to Accounts..!!";
		}
	}
}
*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
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
		line-height:18px;
	}
	.HideDesc{
		max-width : 868px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
	.well-sd {
          padding: 1px;
          padding-top: 1px;
          padding-right: 1px;
          padding-bottom: 1px;
          padding-left: 1px;
          border-radius: 1px;
          border-top-left-radius: 1px;
          border-top-right-radius: 1px;
         border-bottom-right-radius: 1px;
         border-bottom-left-radius: 1px;
          margin-bottom: 1px;
}


.well {

    min-height: 2;
    padding: 1px;
    margin-bottom: 1px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 2px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);

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

	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                <?php include "MainMenu.php"; ?>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
							<div class="row">
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Tender Parity Statement<span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div2 pd-lr-1" id="PE1">
															<div class="lboxlabel-sm">CC No.</div>
															<div>
																<input type="text" name="txt_ccno" id="txt_ccno" class="tboxclass tbox-sm" required />
															</div>
														</div>
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="submit" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php 
									if(($SelectScheduleQuerySql == true)&&(mysqli_num_rows($SelectScheduleQuerySql) > 0)){
								?>
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Tender Parity Statement</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
															    <div class="div12 dataTable" align="left"><b><input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>"></b> </div>
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																<thead>
																<input type="hidden" name="txt_page_id" id="txt_page_id" value = "<?php if(isset($PageId)){ echo $PageId; } ?>" class="">
																<?php 
																if($View == 1){
																	?>
																	<tr>
																		<th rowspan="2">Item No.</th>
																		<th rowspan="2">Item Description</th>
																		<th rowspan="2">Item Unit</th>
																		<th rowspan="2" nowrap="">Qty</br>as</br>per</br>WO</th>
																		<th rowspan="2" nowrap="">Used</br>Item</br>Qty.</th>
																		<th colspan="2">Department Estimate</th>
																		<!-- <th rowspan="2" nowrap="">Used Qty.</th> -->
																		<input type="hidden" name="txt_tr_id" id="txt_tr_id" value = "<?php if(isset($MastId)){ echo $MastId; } ?>" class="">
																		<?php  
																			$DeptTotalAmount = 0;	//print_r($ContArr);exit;
																			if(!empty($ContArr)){ 
																				//asort($ContArr);
																				foreach($ContArr as $ContractId){
																					$ContractName = $ContNameArr[$ContractId];
																					echo'<th colspan="3">'.$ContractName.'</th>';
																				}
																			}
																		?>
																		
																	</tr>
																	<tr>
																		<th nowrap="nowrap">Rate <br/>( &#8377; )</th>
																		<th nowrap="nowrap">Amount <br/>( &#8377; )</th>
																		<?php 
																			$TotalAmount = array();
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					echo '<th>Rate <br/>( &#8377; )</th>';
																					echo '<th>Amount <br/>( &#8377; )</th>';
																					echo '<th>Variation <br/>( % )</th>';
																					$TotalAmount[$ContractId] = 0;
																				}
																			}

																		?>
																	</tr>
																</thead>
																<tbody>
																	<?php
																		/*
																		$SelectQuery2 = "SELECT * FROM parta_details where mastid='$MastABId' order by detid asc";
																		$ResultQuery2 = mysqli_query($dbConn,$SelectQuery2);
																		if($ResultQuery2 == true){ 
																			if(mysqli_num_rows($ResultQuery2)>0){ 
																		*/
																				while($Result2 = mysqli_fetch_object($SelectScheduleQuerySql)){ 
																					$SheetID		= $Result2->sheet_id;
																					$ItemNo		= $Result2->sno;
																					$ItemDesc	= $Result2->description;
																					$ItemQty		= $Result2->total_quantity;
																					$ItemUnit	= $Result2->per;
																					$DeptItemRate = $Result2->rate;
																					$subdiv_id	= $Result2->subdiv_id;
																					//print_r($MBQtyArr);exit;
																					//echo $subdiv_id;exit;
																					$MBQty	= $MBQtyArr[$subdiv_id];
																					$ItemDescDept 		= $UsedDeptItmDesc[$SheetID][$ItemNo];
																					$DeptEstRate 		= $UsedDeptRateArr[$SheetID][$ItemNo];
																					$ItemQtyDept 		= $DeptQuantityArr[$SheetID][$ItemNo];
																					/*if(($ItemQtyDept == "")||($ItemQtyDept == NULL)||($ItemQtyDept == 0)){
																						$ItemQtyDept = $ItemQty;
																					}*/
																					$DeptAmount 	= round(($MBQty * $DeptItemRate),2);
																					$DeptTotalAmount = $DeptTotalAmount + $DeptAmount;

																					if($DeptEstRate != 0){
																						$DeptEstRateStr = IndianMoneyFormat($DeptEstRate);
																					}else{
																						$DeptEstRateStr = "";
																					}
																					if($DeptAmount != 0){
																						$DeptAmountStr = IndianMoneyFormat($DeptAmount);
																					}else{
																						$DeptAmountStr = "";
																					}
																					if($ItemQty != 0){
																						$ItemQtyStr = $ItemQty;
																					}else{
																						$ItemQtyStr = "";
																					}
																					
																					echo '<tr>';
																					echo '<td align="center">'.$ItemNo.'</td>';
																					echo '<td align="justify">'.$ItemDesc.'</td>';
																					echo '<td align="center">'.$ItemUnit.'</td>';
																					echo '<td align="right">'.$ItemQtyDept.'</td>';
																					echo '<td align="right">'.$MBQty.'</td>';
																					echo '<td align="right">'.$DeptEstRateStr.'</td>';
																					echo '<td align="right">'.$DeptAmountStr.'</td>';
																					// echo '<td align="right">'.$DeptAmountStr.'</td>';
																					if(!empty($ContArr)){
																						foreach($ContArr as $ContractId){
																							$ItemRate   = $BidderRateArr[$ContractId][$ItemNo];
																							$Amount 	= round(($ItemQty * $ItemRate),2);
																							if($DeptAmount != 0){
																								$VariationAmt 	= $Amount - $DeptAmount;
																								$VariationPerc 	= round(($VariationAmt * 100 / $DeptAmount),2);
																								$VariationStr 	= number_format($VariationPerc,2);
																							}else{
																								$VariationPerc	= 0;
																								$VariationStr 	= "";
																							}
																							if($ItemRate != 0){
																								$ItemRateStr = IndianMoneyFormat($ItemRate);
																							}else{
																								$ItemRateStr = "";
																							}
																							if($Amount != 0){
																								$AmountStr = IndianMoneyFormat($Amount);
																							}else{
																								$AmountStr = "";
																							}
																							echo '<td align="right">'.$ItemRateStr.'</td>';
																							echo '<td align="right">'.$AmountStr.'</td>';
																							if($VariationPerc > 25){	
																								echo '<td style="background-color:#FF0000" align="right">'.$VariationStr.'</td>';
																							}elseif($VariationPerc < -25){
																								echo '<td style="background-color:#00FF00" align="right">'.$VariationStr.'</td>';
																							}elseif($VariationPerc = ''){
																								echo '<td style="background-color:#FFFFFF" align="right"></td>';	
																							}else{
																								echo '<td style="background-color:#FFFFFF" align="right">'.$VariationStr.'</td>';
																							
																							}
																							$TotalAmount[$ContractId] = $TotalAmount[$ContractId] + $Amount;
																						}
																					}
																					echo'</tr>';
																				} 
																			//}
																		//}
																	?>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Total Amount ( &#8377; )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"><b><?php echo IndianMoneyFormat($DeptTotalAmount); ?></b></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($TotalAmount[$ContractId]).'</b></td>';
																					echo '<td>&nbsp;</td>';
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate ( % )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($RebatePercArr[$ContractId]).'</b></td>';
																					echo '<td>&nbsp;</td>';
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate Value ( &#8377 )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$RebatePerc = $RebatePercArr[$ContractId];
																					$TotalAmt = $TotalAmount[$ContractId];
																					$RebateAmt = $TotalAmt * $RebatePerc / 100;
																					$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($RebateAmt).'</b></td>';
																					echo '<td>&nbsp;</td>';
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Total Amount After Rebate ( &#8377; )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$RebatePerc = $RebatePercArr[$ContractId];
																					$TotalAmt = $TotalAmount[$ContractId];
																					$RebateAmt = $TotalAmt * $RebatePerc / 100;
																					$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($TotAmtAftRebate).'</b></td>';
																					echo '<td>&nbsp;</td>';
																					$TotalAmount[$ContractId] = $TotAmtAftRebate;//[$ContractId];
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Variation Amount ( &#8377; )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right">&nbsp;</td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($TotalVariateAmt).'</b></td>';
																					echo '<td>&nbsp;</td>';
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right" nowrap="nowrap"><b>Variation ( % )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right">&nbsp;</td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																					$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																					echo '<td>&nbsp;</td>';
																					echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																					text-transform: none;line-height: 17px;" align="right"><b>'.IndianMoneyFormat($TotalVariatePerc).'</b></td>';
																					echo '<td>&nbsp;</td>';
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right"><b>Excess / Less</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right">&nbsp;</td>
																		<?php
																			$StatusArr = array();
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																					$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																					//echo $TotalVariatePerc;
																					if($TotalVariatePerc > 0){
																						$ExcessLess = "EXCESS";
																					}else if($TotalVariatePerc < 0){
																						$ExcessLess = "LESS";
																					}else{
																						$ExcessLess = "";
																					}
																					echo '<td>&nbsp;</td>';
																					echo '<td align="center"><b>'.$ExcessLess.'</b></td>';
																					echo '<td>&nbsp;</td>';
																					$StatusArr[$ContractId] = $TotalVariatePerc;
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right">
																			<b>
																				Status
																			</b>
																		</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right">&nbsp;</td>
																		<?php
																			//print_r($StatusArr); echo "<br/>";
																			asort($StatusArr);
																			//print_r($StatusArr);
																			//print_r($ContArr);
																			foreach($ContArr as $ContractId){
																				$StatusPosition = array_search($ContractId, array_keys($StatusArr));
																				//echo $StatusPosition;
																				$StatusPosition = $StatusPosition + 1;
																				
																				echo '<td>&nbsp;</td>';
																				echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																				text-transform: none;line-height: 17px;" align="center"><b>L'.$StatusPosition.'</b></td>';
																				echo '<td>&nbsp;</td>';
																			}
																		?>
																	</tr>
																	<?php } else{ ?>
																	<tr  class='tboxclass' align="middle"  valign='middle'><label class ="box-label">No Records Found</label></tr>
																	<?php } ?>
																	</tbody>
																	</table>
																</div>
															</div>
															<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
																<div class="buttonsection">
																	<input type="submit" class="btn btn-info" id="back" name="back" value="Back">
																</div>
																<!-- <div class="buttonsection" <?php //if(isset($PageId)){ if($PageId == 1){ echo 'style="display:none;"'; } } ?>>
																	<input type="submit" name="sendtoacc" id="sendtoacc" value="Forward To Accounts" class="btn btn-info">
																</div> -->
																<div class="buttonsection">
																	<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
							             <!-- <div class="div1">&nbsp;</div> -->
									</div>
								</div>
								<?php
									}
								?>
										<div align="center">&nbsp;</div>
								</blockquote>
							</div>
					</div>
				</div>
            
             <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
        </form>
    </body>
	<script>
		$(document).ready(function(){ 
			$("#exportToExcel").click(function(e){ 
				var table = $('body').find('.table2excel');
				if(table.length){ 
					$(table).table2excel({
						exclude: ".xlTable",
						name: "CST",
						filename: "CST -" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
						//preserveColors: preserveColors
					});
				}
			});
		});
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('TenderParityStatement.php');
					}
				}]
			});
		}
		/*
		var msg = "<?php //echo $msg; ?>";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				//BootstrapDialog.alert();
				event.preventDefault();
				BootstrapDialog.alert(msg, function(result){
					if(result) {
						$("#back").trigger( "click" );
					}
				});
			}
		};
		*/
		var KillEvent = 0;
		$(document).ready(function(){ 
			$("body").on("click","#sendtoacc", function(event){
				if(KillEvent == 0){
						event.preventDefault();
						BootstrapDialog.confirm('Are you sure you want to send this CST to Accounts?', function(result){
							if(result) {
								KillEvent = 1;
								$("#sendtoacc").trigger( "click" );
							}
						});
					}
			});
		});
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
