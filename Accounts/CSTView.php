<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'CCNO. Entry';
checkUser();
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$UserId  = $_SESSION['userid'];
$staffid  = $_SESSION['sid'];

if(isset($_GET['id'])){   
	$TRId = $_GET['id'];

	$ContArr = array();
	$ContNameArr = array();
	$RebatePercArr = array();
	$NegoRebatePercArr = array();
	$RebateProfitArr = array();
	$ContQuotedPosArr = array();
	$GlobID = '';
	$GlobIDQuery = "SELECT globid, ccno, nego_status, work_name FROM tender_register WHERE tr_id = '$TRId'";
	$GlobIDSql = mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
			$CCno = $List->ccno;
			$WorkName = $List->work_name;
			$Negost = $List->nego_status;
		}
	}
	$NegoContArr = array();
	$NegoBackValue = 0;
	$SelectQuery = "SELECT * FROM bidder_bid_master where tr_id = '$TRId' ORDER BY quoted_pos ASC, quoted_amt_af_reb ASC";
	// /echo $SelectQuery;
	$ResultQuery = mysqli_query($dbConn,$SelectQuery);
	if($ResultQuery==true){
		if(mysqli_num_rows($ResultQuery)>0){
			while($Result = mysqli_fetch_object($ResultQuery)){
				$ContId = $Result->contid;
				$GlobId = $Result->globid;
				$ISNego = $Result->is_negotiate;
				$RebatePer= $Result->rebate_perc;
				$NegoRebatePer= $Result->negotiate_rebate_perc;
				$ProfitRebate = $Result->rebate_profit;
				array_push($ContArr,$ContId);
				if($Result->quoted_pos != 0){
					array_push($ContQuotedPosArr,$Result->quoted_pos);
				}
				if($Result->is_negotiate == 'Y'){
					array_push($NegoContArr,$ContId);
					$NegoBackValue = 1;
				}
				$SeclectQuery1 = "SELECT name_contractor FROM contractor where contid = '$ContId'";
				$ResultQuery1  = mysqli_query($dbConn,$SeclectQuery1);
				if($ResultQuery1 == true){
					if(mysqli_num_rows($ResultQuery1)>0){
						$Result1 = mysqli_fetch_object($ResultQuery1);
						$ContName= $Result1->name_contractor;
						$ContNameArr[$ContId] = $ContName;
						$RebatePercArr[$ContId] = $RebatePer;
						$NegoRebatePercArr[$ContId] = $NegoRebatePer;
						$RebateProfitArr[$ContId] = $ProfitRebate;
					}
				}
			}
		}
	}
	//	print_r($ContQuotedPosArr);
	/*$SelectNegoQuery = "SELECT DISTINCT contid,bmid FROM bidder_bid_master WHERE globid = '$GlobID' AND tr_id = '$MastId' AND is_negotiate = 'Y' ORDER BY quoted_amt_af_neg ASC";
	//echo $SelectNegoQuery;
	$NegoResultQuery = mysqli_query($dbConn,$SelectNegoQuery);
	if($NegoResultQuery==true){
		if(mysqli_num_rows($NegoResultQuery)>0){
			while($Result1 = mysqli_fetch_object($NegoResultQuery)){
				$NegoContId = $Result1->contid;
				$NegoBmidId = $Result1->bmid;
				array_push($NegoContArr,$NegoContId);
			}
		}
	}*/
	//PRINT_R($NegoContArr);
	$BidderRateArr  = array();
	$NegoBidderRateArr  = array();
	//$SelectSqlQuery = "SELECT * FROM bidder_bid_details WHERE globid = '$GlobID' AND tr_id = '$TRId'";
	$SelectSqlQuery = "SELECT * FROM bidder_bid_details where tr_id = '$TRId' order by bdid asc";
	//echo $SelectSqlQuery;
	$ResultSqlQuery = mysqli_query($dbConn,$SelectSqlQuery);
	if($ResultSqlQuery == true){
		if(mysqli_num_rows($ResultSqlQuery)>0){
			while($CList=mysqli_fetch_object($ResultSqlQuery)){
				$ContractId 	= $CList->contid;
				$ItemNo     	= $CList->item_no;
				$ItemRate   	= $CList->item_rate;
				$NegoItemRate	= $CList->negotiate_rate;
				$BidderRateArr[$ContractId][$ItemNo]= $ItemRate;
				if(in_array($ContractId, $NegoContArr)){
					$NegoBidderRateArr[$ContractId][$ItemNo] = $NegoItemRate;
				}
			}
		}
	}
	//print_r($ContractIdNego);
	// /echo $ContractIdNego;
	/*$NegoBidderRateArr  = array();
	$SelectNegoSqlQuery = "SELECT * FROM bidder_bid_details where tr_id = '$MastId'";
	$NegoResultSqlQuery = mysqli_query($dbConn,$SelectNegoSqlQuery);
	if($NegoResultSqlQuery == true){
		if(mysqli_num_rows($NegoResultSqlQuery)>0){
			while($CList=mysqli_fetch_object($NegoResultSqlQuery)){
				$ContractIdNego = $CList->contid;
				$ItemNo       = $CList->item_no;
				$NegoItemRate = $CList->negotiate_rate;
				$NegoBidderRateArr[$ContractIdNego][$ItemNo]= $NegoItemRate;
			}
			//echo $NegoItemRate;
		}
	}*/
	//echo $EstId;exit;print_r($BidderRateArr);exit;
}
if(isset($_POST["btn_confirm"])){
	$CCNumb      =	trim($_POST['txt_ccno']);
	$HidTrId     =	$_POST['hid_txt_tr_id'];
	$IsNego      =	trim($_POST['txt_cst_nego_id']);
	
		
		if($IsNego == 'A'){ 
			$INSERTQuery 	= "UPDATE works SET ccno = '$CCNumb', work_status='NST' WHERE tr_id = $HidTrId";
		    $INSERTQuerysql = mysqli_query($dbConn,$INSERTQuery);
			$TenRegQuery = "UPDATE tender_register SET ccno = '$CCNumb',  nego_status ='C', ncst_acc_status='C', ncst_acc_conf_by='$staffid', ncst_acc_conf_on =NOW() WHERE tr_id = $HidTrId";
			$TenRegQuerysql = mysqli_query($dbConn,$TenRegQuery);
			
			if($TenRegQuerysql == true){
				$msg = "Negotiation Statement Confirmed Successfully";
				$success = 1;
			}else{
				$msg = "Error: Negotiation Statement Not Confirmed...!!! ";
			}
			
		}else{ 
			$INSERTQuery 	= "UPDATE works SET ccno = '$CCNumb', work_status='CST' WHERE tr_id = $HidTrId";
		    $INSERTQuerysql = mysqli_query($dbConn,$INSERTQuery);
			$TenRegQuery = "UPDATE tender_register SET ccno='$CCNumb', cst_status ='C' , cst_acc_status='C', cst_acc_conf_by='$staffid', cst_acc_conf_on =NOW() WHERE tr_id = $HidTrId";
			$TenRegQuerysql = mysqli_query($dbConn,$TenRegQuery);
			if($TenRegQuerysql == true){
				$msg = "CCNO. Assigned Successfully";
				$success = 1;
			}else{
				$msg = "Error: CCNO. Not Assigned...!!! ";
			}
		
		}
	
	
			
	//echo $value;exit;
}
if(isset($_POST['back'])){
	$BackVal = $_POST['txt_backvalue'];
	if($BackVal == 1){
		 header('Location: WorksNegoCSTList.php');
	}else{
		 header('Location: WorksCSTList.php');
	}
	/* $PageId 	 = $_POST['txt_page_id'];
	if($PageId == '2'){
		header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=2');
	}else if($PageId == '3'){
		header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=3');
	} */
}
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
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
						<blockquote class="bq1" id="bq1" style="overflow:auto;">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Comparative Statement</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
																<div class="div12 dataTable" align="left">
																	<b>
																		<input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>">
																	</b>
																</div>
														<?php if(($TRId != null)|| (($TRId != ''))){ ?>

														<table class="table itemtable rtable table2excel" width="100%">
															<thead>
																<tr>
																	<th rowspan="2">Item No.</th>
																	<th rowspan="2" style="width:650px">Item Description</th>
																	<th rowspan="2">Item Qty</th>
																	<th rowspan="2">Item Unit</th>
																	<th colspan="2">Department Estimate</th>
																	<input type="hidden" name="txt_page_id" id="txt_page_id" value = "<?php if(isset($PageId)){ echo $PageId; } ?>" class="">
																	<input type="hidden" name="hid_txt_tr_id" id="hid_txt_tr_id" value = "<?php if(isset($TRId)){ echo $TRId; } ?>" class="">
																	<input type="hidden" name="txt_cst_nego_id" id="txt_cst_nego_id" value = "<?php if(isset($Negost)){ echo $Negost; } ?>" class="">
																	<?php 
																		//$NegoCont = null;
																		$DeptTotalAmount = 0;
																		foreach($ContArr as $ContractId){
																			$ContractName = $ContNameArr[$ContractId];
																			echo'<th colspan="3">'.$ContractName.'</th>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoCont = $NegoContArr[$ContractId];
																				echo'<th colspan="4">'.$ContractName.'<br/> <----- After Negotiation -----></th>';
																			}
																		}
																	?>
																</tr>
																<tr>
																	<th nowrap="nowrap">Rate <br/>( &#8377; )</th>
																	<th nowrap="nowrap">Amount <br/>( &#8377; )</th>
																	<?php 
																		$TotalAmount = array();
																		$NegoTotalAmount = array();
																		foreach($ContArr as $ContractId){
																			echo '<th>Rate <br/>( &#8377; )</th>';
																			echo '<th>Amount <br/>( &#8377; )</th>';
																			echo '<th>Variation <br/>( % )</th>';
																			$TotalAmount[$ContractId] = 0;
																			/*if(isset($NegoContArr[$ContractId])){
																				$NegoCont1 = $NegoContArr[$ContractId];
																			}
																			echo'<th colspan="3">'.$ContractName.'</th>';*/
																			if(in_array($ContractId, $NegoContArr)){
																				echo '<th>Rate <br/>( &#8377; )</th>';
																				echo '<th>Amount <br/>( &#8377; )</th>';
																				echo '<th>Variation <br/>( % )</th>';
																				echo '<th>Variation <br/>Level</th>';
																				$NegoTotalAmount[$NegoContractId] = 0;
																			}
																		}
																		//echo $NegoTotalAmount[$NegoContractId];
																	?>
																</tr>
															</thead>
															<tbody>
																<?php
																	$SelectQuery2 = "SELECT * FROM parta_details where globid='$GlobID' order by detid asc";
																	//echo $TRId;exit;
																	$ResultQuery2 = mysqli_query($dbConn,$SelectQuery2);
																	if($ResultQuery2 == true){
																		if(mysqli_num_rows($ResultQuery2)>0){
																			while($Result2 = mysqli_fetch_object($ResultQuery2)){
																				$ItemNo  		= $Result2->sno;
																				$ItemDesc   	= $Result2->description;
																				$ItemQty    	= $Result2->quantity;
																				$ItemUnit    	= $Result2->unit;
																				$DeptItemRate   = $Result2->supply;
																				$DeptAmount 	= round(($ItemQty * $DeptItemRate),2);
																				$DeptTotalAmount = $DeptTotalAmount + $DeptAmount;
																				if($DeptItemRate != 0){
																					$DeptItemRateStr = IndianMoneyFormat($DeptItemRate);
																				}else{
																					$DeptItemRateStr = "";
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
																				echo '<td align="justify" style="width:650px">'.$ItemDesc.'</td>';
																				echo '<td align="right">'.$ItemQtyStr.'</td>';
																				echo '<td align="center">'.$ItemUnit.'</td>';
																				echo '<td align="right">'.$DeptItemRateStr.'</td>';
																				echo '<td align="right">'.$DeptAmountStr.'</td>';
																				foreach($ContArr as $ContractId){
																					$ItemRate   = $BidderRateArr[$ContractId][$ItemNo];
																					$Amount 	= round(($ItemQty * $ItemRate),2);

																					if($DeptAmount != 0){
																						$VariationAmt 	= $Amount - $DeptAmount;
																						$VariationPerc 	= round(($VariationAmt * 100 / $DeptAmount),2);
																						$VariationStr 	= IndianMoneyFormat($VariationPerc,2);
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

																					if(in_array($ContractId, $NegoContArr)){
																						$NegotiateItemRate   = $NegoBidderRateArr[$ContractId][$ItemNo];
																						$NegAmount 	= round(($ItemQty * $NegotiateItemRate),2);
																						if($DeptAmount != 0){
																							$VariationAmt 	= $NegAmount - $DeptAmount;
																							$NegoVariationPerc 	= round(($VariationAmt * 100 / $DeptAmount),2);
																							$NegoVariationStr 	= IndianMoneyFormat($NegoVariationPerc,2);
																						}else{
																							$NegoVariationPerc	= 0;
																							$NegoVariationStr 	= "";
																						}
																						if($NegotiateItemRate != 0){
																							$NegoItemRateStr = IndianMoneyFormat($NegotiateItemRate);
																						}else{
																							$NegoItemRateStr = "";
																						}
																						if($NegAmount != 0){
																							$NegAmountStr = IndianMoneyFormat($NegAmount);
																						}else{
																							$NegAmountStr = "";
																						}
																						echo '<td align="right">'.$NegoItemRateStr.'</td>';
																						echo '<td align="right">'.$NegAmountStr.'</td>';
																						if($NegoVariationPerc > 25){	
																							echo '<td style="background-color:#FF0000" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(H)';
																							echo '<td style="background-color:#FF0000" align="center" >'.$NegoLevel.'</td>';
																						}elseif($NegoVariationPerc < -25){
																							echo '<td style="background-color:#00FF00" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(L)';
																							echo '<td style="background-color:#00FF00" align="center" >'.$NegoLevel.'</td>';
																						}else{
																							echo '<td style="background-color:#FFFFFF" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(N)';
																							echo '<td style="background-color:#FFFFFF" align="center">'.$NegoLevel.'</td>';
																						}
																						$NegoTotalAmount[$ContractId] = $NegoTotalAmount[$ContractId] + $NegAmount;
																					}


																				}
																				
																				echo'</tr>';
																			} 
																		}
																	}
																?>
																<tr>
																	<td>&nbsp;</td>
																	<td align="right" nowrap="nowrap"><b>Total Amount ( &#8377; )</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right"><b><?php echo IndianMoneyFormat($DeptTotalAmount); ?></b></td>
																	<?php
																		foreach($ContArr as $ContractId){
																			echo '<td>&nbsp;</td>';
																			echo '<td align="right"><b>'.IndianMoneyFormat($TotalAmount[$ContractId]).'</b></td>';
																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				echo '<td>&nbsp;</td>';
																				echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalAmount[$ContractId]).'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																		
																	?>
																</tr>
																<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate / Profit ( % )</b></td>
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
																					if(in_array($ContractId, $NegoContArr)){
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($NegoRebatePercArr[$ContractId]).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						echo '<td>&nbsp;</td>';
																					}
																					}
																				}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate / Profit Value ( &#8377 )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$RebateProfit = $RebateProfitArr[$ContractId];
																					$RebatePerc = $RebatePercArr[$ContractId];
																					$TotalAmt = $TotalAmount[$ContractId];
																					$RebateAmt = $TotalAmt * $RebatePerc / 100;
																					if($RebateProfit == "PR"){
																						$TotAmtAftRebate = round(($TotalAmt + $RebateAmt),2);
																					}else{
																						$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																					}
																					if($RebateProfit == "PR"){
																						$Sign = "+";
																					}else if($RebateProfit == "RE"){
																						$Sign = "-";
																					}else{
																						$Sign = "";
																					}
																					$NegoRebatePerc = $NegoRebatePercArr[$ContractId];
																					$TotalNegoAmt = $NegoTotalAmount[$ContractId];
																					$RebateNegoAmt = $TotalNegoAmt * $NegoRebatePerc / 100;
																					if($RebateProfit == "PR"){
																						$TotNegoAmtAftRebate = round(($TotalNegoAmt + $RebateNegoAmt),2);
																					}else{
																						$TotNegoAmtAftRebate = round(($TotalNegoAmt - $RebateNegoAmt),2);
																					}
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right" nowrap=""><b>'.$Sign." ".IndianMoneyFormat($RebateAmt).'</b></td>';
																					echo '<td>&nbsp;</td>';
																					if(in_array($ContractId, $NegoContArr)){
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($RebateNegoAmt).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						echo '<td>&nbsp;</td>';
																					}
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Total Amount After Rebate / Profit ( &#8377; )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																				if(!empty($ContArr)){
																					foreach($ContArr as $ContractId){
																						$RebateProfit = $RebateProfitArr[$ContractId];
																						$RebatePerc = $RebatePercArr[$ContractId];
																						$TotalAmt = $TotalAmount[$ContractId];
																						$RebateAmt = $TotalAmt * $RebatePerc / 100;
																						if($RebateProfit == "PR"){
																							$TotAmtAftRebate = round(($TotalAmt + $RebateAmt),2);
																						}else{
																							$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																						}
																						$NegoRebatePerc = $NegoRebatePercArr[$ContractId];
																						$TotalNegoAmt = $NegoTotalAmount[$ContractId];
																						$RebateNegoAmt = $TotalNegoAmt * $NegoRebatePerc / 100;
																						if($RebateProfit == "PR"){
																							$TotNegoAmtAftRebate = round(($TotalNegoAmt + $RebateNegoAmt),2);
																						}else{
																							$TotNegoAmtAftRebate = round(($TotalNegoAmt - $RebateNegoAmt),2);
																						}
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($TotAmtAftRebate).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						if(in_array($ContractId, $NegoContArr)){
																							echo '<td>&nbsp;</td>';
																							echo '<td align="right"><b>'.IndianMoneyFormat($TotNegoAmtAftRebate).'</b></td>';
																							echo '<td>&nbsp;</td>';
																							echo '<td>&nbsp;</td>';
																						}
																						$TotalAmount[$ContractId] = $TotAmtAftRebate;
																						$NegoTotalAmount[$ContractId] = $TotNegoAmtAftRebate;
																					}
																						//[$ContractId];
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
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			echo '<td>&nbsp;</td>';
																			echo '<td align="right"><b>'.IndianMoneyFormat($TotalVariateAmt).'</b></td>';
																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				echo '<td>&nbsp;</td>';
																				echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalVariateAmt).'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																	?>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right" nowrap="nowrap"><b>Variation ( % )</b></td>																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt	= round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																			echo '<td>&nbsp;</td>';
																			echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																			text-transform: none;line-height: 17px;" align="right"><b>'.IndianMoneyFormat($TotalVariatePerc).'</b></td>';																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
																				echo '<td>&nbsp;</td>';
																				echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																				text-transform: none;line-height: 17px;" align="right"><b>'.IndianMoneyFormat($NegoTotalVariatePerc).'</b></td>';
																				echo '<td>&nbsp;</td>';
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
																		$NegoStatusArr = array();
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																			if($TotalVariatePerc > 0){
																				$ExcessLess = "EXCESS";
																			}else if($TotalVariatePerc< 0){
																				$ExcessLess = "LESS";
																			}else{
																				$ExcessLess = "";
																			}
																			echo '<td>&nbsp;</td>';
																			echo '<td align="center"><b>'.$ExcessLess.'</b></td>';
																			echo '<td>&nbsp;</td>';
																			$StatusArr[$ContractId] = $TotalVariateAmt;
																			
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
																				if($NegoTotalVariatePerc > 0){
																					$NegoExcessLess = "EXCESS";
																				}else if($NegoTotalVariatePerc < 0){
																					$NegoExcessLess = "LESS";
																				}else{
																					$NegoExcessLess = "";
																				}
																				echo '<td>&nbsp;</td>';
																				echo '<td align="center"><b>'.$NegoExcessLess.'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																				$NegoStatusArr[$NegoContractId] = $NegoTotalVariateAmt;
																			}
																		}
																		
																	?>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right"><b>Status</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		asort($StatusArr);
																		asort($NegoStatusArr);

																		$PrevTot = "";   $StatusPosition = 0;
																		foreach($ContArr as $key => $Value){
																			$TotalAmt = $TotalAmount[$ContractId];
																			if($TotalAmt == $PrevTot){
																				//	echo $PrevTot;exit;
																				if($StatusPosition == 0){
																					$StatusPosition = 1;
																				}else{
																					$StatusPosition = $StatusPosition;
																				}
																			}else{
																				$StatusPosition = $StatusPosition + 1;
																			}
																			?>
																			<input type="hidden" name="txt_status_pos[]" id="txt_status_pos" value = "<?php if(isset($StatusPosition)){ echo $StatusPosition; } ?>" class="">
																			<?php
																			echo '<td>&nbsp;</td>';
																			echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																			      text-transform: none;line-height: 17px;" align="center"><b>L'.$StatusPosition.'</b></td>';
																		    echo '<td>&nbsp;</td>';
																			if(in_array($Value, $NegoContArr)){
																				$NegoStatusPosition = array_search($Value, array_keys($NegoStatusArr));
																				$NegoStatusPosition = $NegoStatusPosition + 1;
																				echo '<td>&nbsp;</td>';
																				echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																				text-transform: none;line-height: 17px;" align="center"><b>L'.$NegoStatusPosition.'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																			$PrevTot = $TotalAmt;
																		}
																	?>
																</tr>
															</tbody>
														</table>
														<div class="row clearrow"></div>
														<div style="text-align:center; height:30px; line-height:30px;" class="lboxlabel">
															<div class="div3 lboxlabel">&nbsp;</div>
															<div class="div3 lboxlabel" style="text-align:center;">&nbsp; CCNO : &nbsp; </div>
															<div class="div4" align="left">
																<input type="text" name="txt_ccno" id="txt_ccno" class="tboxclass" style="width:50%" value="<?php if(isset($CCno)){ echo $CCno; } ?>" <?php if((isset($Negost))&&($Negost == "A")){ echo 'readonly=""'; } ?>>
															</div>
														</div>
														<input type="hidden" name="txt_backvalue" id="txt_backvalue" class="tboxclass" style="width:50%" value="<?php if(isset($NegoBackValue)){ echo $NegoBackValue; } ?>">
														<div class="row clearrow"></div>
														<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
															<div class="buttonsection">
																<input type="submit" name="btn_confirm" id="btn_confirm" class="btn btn-info" value="CONFIRM">
															</div>
															<div class="buttonsection">
																<input type="submit" class="btn btn-info" name="back" value="Back">
															</div>
														</div>
															<?php
														}
														?>
														
													</div>
										</div>
									</div>
									<!-- <div class="div1">&nbsp;</div> -->
								</div>
							</div>
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
				var table = $('body').find('.DispTable');
				if(table.length){ 
					$(table).table2excel({
						exclude: ".xlTable",
						name: "SOQ",
						filename: "SOQ -" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
						//preserveColors: preserveColors
					});
				}
			});
		});
		$("body").on("change","#txt_ccno", function(event){
			var ccnoval = $(this).val(); //alert(stateval);
			$.ajax({
				type: 'POST',
				url: 'ajax/GetDetails.php',
				data: {ccnoval:ccnoval, page: 'CCNUMVERIFY'},
				dataType: 'json',
				success: function (data) {
					//alert(data);
					if(data != null){
						if(data == 1){
							BootstrapDialog.alert("CCNo Aldready Entered..!!");
							$("#txt_ccno").val('');
						}else{
							
						}
					}
				}
			});
		});
		$("body").on("click","#btn_confirm", function(event){
			var ccnoval = $("#txt_ccno").val();
			if(ccnoval == ""){
				BootstrapDialog.alert("CCNo. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else{
				$.ajax({
					type: 'POST',
					url: 'ajax/GetDetails.php',
					data: {ccnoval:ccnoval, page: 'CCNUMVERIFY'},
					dataType: 'json',
					success: function (data) {
						//alert(data);
						if(data != null){
							if(data == 1){
								BootstrapDialog.alert("CCNo Aldready Entered..!!");
								$("#txt_ccno").val('');
								event.preventDefault();
								event.returnValue = false;
							}else{
								
							}
						}
					}
				});
			}
		});
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		//var pageidval = $("#txt_page_id").val();
		if(msg != ""){
			//if(pageidval == '2'){
				BootstrapDialog.show({
					message: msg,
					buttons: [{
						label: ' OK ',
						action: function(dialog) {
							dialog.close();
							window.location.replace('WorksCSTList.php');
						}
					}]
				});
			//}
		}
		/* $("body").on("click","#btn_saveas_draft", function(event){
			var ccnoval = $("#txt_ccno").val();
			if(ccnoval == ""){
				BootstrapDialog.alert("CCNo. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else{
				$.ajax({
					type: 'POST',
					url: 'ajax/GetDetails.php',
					data: {ccnoval:ccnoval, page: 'CCNUMVERIFY'},
					dataType: 'json',
					success: function (data) {
						//alert(data);
						if(data != null){
							if(data == 1){
								BootstrapDialog.alert("CCNo Aldready Entered..!!");
								$("#txt_ccno").val('');
								event.preventDefault();
								event.returnValue = false;
							}else{
								/////// RETURNS ///////
							}
						}
					}
				});
			}
		});*/

	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
