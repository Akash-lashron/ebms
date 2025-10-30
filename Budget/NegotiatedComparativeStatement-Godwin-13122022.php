<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';

if(isset($_POST['View'])){   
	$MastId 	 = $_POST['cmb_shortname']; 
	$PageId 	 = $_POST['txt_pageid'];

	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$GlobIDQuery = "SELECT globid FROM tender_register WHERE tr_id = '$MastId'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobID = $List->globid;
		}
	}
	$SelectQuery = "SELECT DISTINCT contid,globid FROM bidder_bid_master where globid = '$GlobID' ORDER BY quoted_amt_af_reb ASC";
	//echo $SelectQuery;
	$ResultQuery = mysqli_query($dbConn,$SelectQuery);
	if($ResultQuery==true){
		if(mysqli_num_rows($ResultQuery)>0){
			while($Result = mysqli_fetch_object($ResultQuery)){
				$ContId = $Result->contid;
				$GlobId = $Result->globid;
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
	$NegoContArr = array();
	$SelectNegoQuery = "SELECT DISTINCT contid,bmid FROM bidder_bid_master WHERE globid = '$GlobID' AND tr_id = '$MastId' AND is_negotiate = 'Y' ORDER BY quoted_amt_af_neg ASC";
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
	}
	//PRINT_R($NegoContArr);
	$BidderRateArr  = array();
	$NegoBidderRateArr  = array();
	$SelectSqlQuery = "SELECT * FROM bidder_bid_details WHERE globid = '$GlobID' AND tr_id = '$MastId'";
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
				if($NegoBmidId == $CList->bmid){
					//echo $CList->bmid;
					$NegoBidderRateArr[$ContractId][$ItemNo]= $NegoItemRate;
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
if(isset($_POST['back'])){
	$PageId 	 = $_POST['txt_page_id'];

	if($PageId == '2'){
		header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=2');
	}else if($PageId == '3'){
		header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=3');
	}
}
if(isset($_POST['sendtoacc'])){
	$TenderId = $_POST['txt_tr_id']; 
	$PageId 	 = $_POST['txt_page_id'];
	if(isset($TenderId)){
		$SelectEstQuery = "UPDATE tender_register SET status = 'ACC' WHERE tr_id = '$TenderId'";
		$SelectEstSql 	= mysqli_query($dbConn,$SelectEstQuery);
		if($PageId == '2'){
			header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=2');
		}else if($PageId == '3'){
			header('Location: NegotiatedComparativeStatementGenerate.php?ncsid=3');
		}
	}
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
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
                            <div class="container" align="center">
								<div class="smediv">&nbsp;</div>
								<table class="table itemtable rtable table2excel" width="100%">
									<thead>
										<tr>
											<th rowspan="2">Item No.</th>
											<th rowspan="2" style="width:650px">Item Description</th>
											<th rowspan="2">Item Qty</th>
											<th rowspan="2">Item Unit</th>
											<th colspan="2">Department Estimate</th>
											<input type="hidden" name="txt_page_id" id="txt_page_id" value = "<?php if(isset($PageId)){ echo $PageId; } ?>" class="">
											<?php 
												//$NegoCont = null;
												$DeptTotalAmount = 0;
												foreach($NegoContArr as $NegoContractId){
													$ContractName = $ContNameArr[$NegoContractId];
													echo'<th colspan="4">'.$ContractName.'<br/> <----- After Negotiation -----></th>';
												}
												foreach($ContArr as $ContractId){
													$ContractName = $ContNameArr[$ContractId];
													echo'<th colspan="3">'.$ContractName.'</th>';
													/*if(isset($NegoContArr[$ContractId])){
														$NegoCont = $NegoContArr[$ContractId];
														echo'<th colspan="4">'.$ContractName.'<br/> <----- After Negotiation -----></th>';
													}*/
												}
												
											?>
											
										</tr>
										<tr>
											<th nowrap="nowrap">Rate <br/>( &#8377; )</th>
											<th nowrap="nowrap">Amount <br/>( &#8377; )</th>
											<?php 
												$TotalAmount = array();
												$NegoTotalAmount = array();
												foreach($NegoContArr as $NegoContractId){
													echo '<th>Rate <br/>( &#8377; )</th>';
													echo '<th>Amount <br/>( &#8377; )</th>';
													echo '<th>Variation <br/>( % )</th>';
													echo '<th>Variation <br/>Level</th>';
													$NegoTotalAmount[$NegoContractId] = 0;
												}
												foreach($ContArr as $ContractId){
													echo '<th>Rate <br/>( &#8377; )</th>';
													echo '<th>Amount <br/>( &#8377; )</th>';
													echo '<th>Variation <br/>( % )</th>';
													$TotalAmount[$ContractId] = 0;
													/*if(isset($NegoContArr[$ContractId])){
														$NegoCont1 = $NegoContArr[$ContractId];
													}
													echo'<th colspan="3">'.$ContractName.'</th>';
													if(isset($NegoCont1)){
														echo '<th>Rate <br/>( &#8377; )</th>';
														echo '<th>Amount <br/>( &#8377; )</th>';
														echo '<th>Variation <br/>( % )</th>';
														echo '<th>Variation <br/>Level</th>';
														$NegoTotalAmount[$NegoContractId] = 0;
													}*/
												}
												
												//echo $NegoTotalAmount[$NegoContractId];
											?>
										</tr>
									</thead>
									<tbody>
									<?php
											$SelectQuery2 = "SELECT * FROM parta_details where globid='$GlobID' order by detid asc";
											//echo $SelectQuery2;exit;
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
														foreach($NegoContArr as $NegoContractId){
															$NegotiateItemRate   = $NegoBidderRateArr[$NegoContractId][$ItemNo]; 
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
															if($NegoVariationStr > 25){	
																echo '<td style="background-color:#FF0000" align="right">'.$NegoVariationStr.'</td>';
																$NegoLevel = '(H)';
																echo '<td style="background-color:#FF0000" align="center" >'.$NegoLevel.'</td>';
															}elseif($NegoVariationStr < -25){
																echo '<td style="background-color:#00FF00" align="right">'.$NegoVariationStr.'</td>';
																$NegoLevel = '(L)';
																echo '<td style="background-color:#00FF00" align="center" >'.$NegoLevel.'</td>';
															}else{
																echo '<td style="background-color:#FFFFFF" align="right">'.$NegoVariationStr.'</td>';
																$NegoLevel = '(N)';
																echo '<td style="background-color:#FFFFFF" align="center">'.$NegoLevel.'</td>';
															}
															$NegoTotalAmount[$NegoContractId] = $NegoTotalAmount[$NegoContractId] + $NegAmount;
														}
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
															echo '<td align="right">'.$VariationStr.'</td>';
															$TotalAmount[$ContractId] = $TotalAmount[$ContractId] + $Amount;

															/*if(isset($NegoContArr[$ContractId])){
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
																if($NegoVariationStr > 25){	
																	echo '<td style="background-color:#FF0000" align="right">'.$NegoVariationStr.'</td>';
																	$NegoLevel = '(H)';
																	echo '<td style="background-color:#FF0000" align="center" >'.$NegoLevel.'</td>';
																}elseif($NegoVariationStr < -25){
																	echo '<td style="background-color:#00FF00" align="right">'.$NegoVariationStr.'</td>';
																	$NegoLevel = '(L)';
																	echo '<td style="background-color:#00FF00" align="center" >'.$NegoLevel.'</td>';
																}else{
																	echo '<td style="background-color:#FFFFFF" align="right">'.$NegoVariationStr.'</td>';
																	$NegoLevel = '(N)';
																	echo '<td style="background-color:#FFFFFF" align="center">'.$NegoLevel.'</td>';
																}
																$NegoTotalAmount[$ContractId] = $NegoTotalAmount[$ContractId] + $NegAmount;
															}*/


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
												foreach($NegoContArr as $NegoContractId){
													//print_r($NegoTotalAmount);
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalAmount[$NegoContractId]).'</b></td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
												}
												foreach($ContArr as $ContractId){
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($TotalAmount[$ContractId]).'</b></td>';
													echo '<td>&nbsp;</td>';
													/*if(isset($NegoContArr[$ContractId])){
														echo '<td>&nbsp;</td>';
														echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalAmount[$ContractId]).'</b></td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
													}*/
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
												foreach($NegoContArr as $NegoContractId){
													$NegoTotalVariateAmt = round(($NegoTotalAmount[$NegoContractId] - $DeptTotalAmount),2);
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalVariateAmt).'</b></td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
												}
												foreach($ContArr as $ContractId){
													$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($TotalVariateAmt).'</b></td>';
													echo '<td>&nbsp;</td>';
													/*if(isset($NegoContArr[$ContractId])){
														$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
														echo '<td>&nbsp;</td>';
														echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalVariateAmt).'</b></td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
													}*/
												}
												
											?>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td align="right" nowrap="nowrap"><b>Variation ( % )</b></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td align="right">&nbsp;</td>
											<?php
												foreach($NegoContArr as $NegoContractId){
													$NegoTotalVariateAmt = round(($NegoTotalAmount[$NegoContractId] - $DeptTotalAmount),2);
													$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalVariatePerc).'</b></td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
												}
												foreach($ContArr as $ContractId){
													$TotalVariateAmt	= round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
													$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
													echo '<td>&nbsp;</td>';
													echo '<td align="right"><b>'.IndianMoneyFormat($TotalVariatePerc).'</b></td>';
													echo '<td>&nbsp;</td>';
													/*if(isset($NegoContArr[$ContractId])){
														$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
														$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
														echo '<td>&nbsp;</td>';
														echo '<td align="right"><b>'.moneyFormat($NegoTotalVariatePerc).'</b></td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
													}*/
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
												foreach($NegoContArr as $NegoContractId){
													$NegoTotalVariateAmt = round(($NegoTotalAmount[$NegoContractId] - $DeptTotalAmount),2);
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
													$NegoStatusArr[$NegoContractId] = $NegoTotalVariatePerc;
												}
												foreach($ContArr as $ContractId){
													$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
													$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
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
													
													/*if(isset($NegoContArr[$ContractId])){
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
														$NegoStatusArr[$NegoContractId] = $NegoTotalVariatePerc;
													}*/
												}
												
											?>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td align="right"><b>Status</b></td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td align="right">&nbsp;</td>
											<?php
												//print_r($StatusArr); echo "<br/>";
												asort($StatusArr);
												//print_r($StatusArr);
												foreach($NegoContArr as $NegoContractId){
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;</td>';
												}
												foreach($ContArr as $ContractId){
													$StatusPosition = array_search($ContractId, array_keys($StatusArr));
													$StatusPosition = $StatusPosition + 1;
													echo '<td>&nbsp;</td>';
													echo '<td align="center"><b>L'.$StatusPosition.'</b></td>';
													echo '<td>&nbsp;</td>';
													/*if(isset($NegoContArr[$ContractId])){
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;</td>';
													}*/
												}
												
											?>
										</tr>
									</tbody>
								</table>
							</div>
							<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<input type="submit" class="btn btn-info" name="back" value="Back">
								</div>
								<div class="buttonsection" <?php if(isset($PageId)){ if($PageId == 2){ echo 'style="display:none;"'; } } ?>>
									<input type="submit" name="sendtoacc" id="sendtoacc" value="Send To Accounts" class="btn btn-info">
								</div>
								<!--<div class="buttonsection">
									<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
								</div>-->
							</div>
                        </blockquote>
						</div> 
 					</div> 
				</div> 
				<!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
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
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
