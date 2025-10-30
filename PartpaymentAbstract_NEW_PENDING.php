<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$staffid 	= $_SESSION['sid'];
$userid 	= $_SESSION['userid'];
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
if(isset($_POST['btn_view']) == ' View '){
	$sheetid 		= $_POST['cmb_work_no'];
	$rbn 			= $_POST['txt_rbn'];
	$mbookno 		= $_POST['txt_mbookno'];
	$mbookpageno 	= $_POST['txt_mbook_page_no'];
	$SelectSheetQuery 	= "SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$SelectSheetSql 	= mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		$SheetList 				= 	mysql_fetch_object($SelectSheetSql);
		$work_name 				= 	$SheetList->work_name; 
		$short_name 			= 	$SheetList->short_name;   
		$tech_sanction 			= 	$SheetList->tech_sanction;  
		$name_contractor 		= 	$SheetList->name_contractor; 
		$ccno 					= 	$SheetList->computer_code_no;    
		$agree_no 				= 	$SheetList->agree_no; 
		$overall_rebate_perc 	= 	$SheetList->rebate_percent; 
		$runn_acc_bill_no 		= 	$rbn;
		$work_order_no 			= 	$SheetList->work_order_no; /* if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
		$length1 				= 	strlen($work_name);
		$start_line1 			= 	ceil($length1/70); 
		$length2 				= 	strlen($agree_no);
		$start_line2 			= 	ceil($length2/27);  
		$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
	}
	//echo $mbookpageno;exit;
}
$Line = $Line + $LineIncr;

$abstmbno 	 = $mbookno;
$Page = $mbookpageno;
$Startpage = $Page;

function GetPrevPaymentDetails($sheetid,$subdivid,$rate){
	$MasMBIdPArr1 = array(); $MasMBIdPArr2 = array(); $MasMBIdPArr2 = array(); $CurrPayDetailStr = "";
	$CurrPayDetailStr  = '<table class="table1" width="100%">';
	$BalPayDetailStr   = '<table class="table1" width="100%">';
	$SelectMasterQuery = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid'";
	$SelectMasterSql   = mysql_query($SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysql_num_rows($SelectMasterSql)>0){ 
			while($MasterList = mysql_fetch_object($SelectMasterSql)){
				$MasMBid 		= $MasterList->measurementbookid;
				$MasQty 		= $MasterList->mbtotal;
				$MasRbn 		= $MasterList->rbn;
				$MasPayPerc 	= $MasterList->pay_percent;
				$MasPPayFlag 	= $MasterList->part_pay_flag;
				$MasQtySplit 	= $MasterList->qty_split;
				$ExpMasPPayFlag = explode("*",$MasPPayFlag);
				$PartPayParId   = $ExpMasPPayFlag[2];
				/*if(($MasPPayFlag == 0)||($MasPPayFlag == 1)){
					$MasMBIdPArr1[$MasMBid] = $MasQty;
					$MasMBIdPArr2[$MasMBid] = $MasPayPerc;
				}else{
					if($MasQtySplit != 'Y'){
						$MasMBIdPArr2[$PartPayParId] = $MasMBIdPArr2[$PartPayParId] + $MasPayPerc;
					}
				}*/
				if(($MasPPayFlag == 0)||($MasPPayFlag == 1)){
					$MasMBIdPArr1[$MasMBid] = $MasQty;
					$MasMBIdPArr2[$MasMBid] = $MasPayPerc;
					$MasMBIdPArr3[$MasMBid] = $MasRbn;
				}else{
					if($MasQtySplit != 'Y'){
						$MasMBIdPArr2[$PartPayParId] = $MasMBIdPArr2[$PartPayParId] + $MasPayPerc;
						$MasMBIdPArr3[$MasMBid] = $MasRbn;
					}
				}
			}
		}
	}
	$MasMBIdPArr4 = array(); $MasMBIdPArr5 = array();
	foreach($MasMBIdPArr1 as $key => $value){
		$id 	= $key;
		$Qty 	= $value;
		$Perc 	= $MasMBIdPArr2[$id];
		$Rbn 	= $MasMBIdPArr3[$id];
		//if(in_array($Perc, $MasMBIdPArr4)){
			$MasMBIdPArr4[$Perc] = $MasMBIdPArr4[$Perc] + $Qty;
			$MasMBIdPArr5[$Perc] = $MasRbn;
		//}else{
			//$MasMBIdPArr4[$Perc] = $Qty;
			//$MasMBIdPArr5[$Perc] = $MasRbn;
		//}
		//$Test2 .= $id."##".$Qty."##".$Perc."##".$Rbn."##";
	}
	$Line = 0;
	foreach($MasMBIdPArr4 as $key1 => $value1){
		$Perc1 = $key1;
		$Qty1 = $value1;
		$Rbn1 = $MasMBIdPArr5[$key1];
		$Amount1 =  round(($Qty1 * $rate * $Perc1 / 100),2);
		$CurrPayDetailStr .= '<tr><td width="60px" align="center">'.$Rbn1.'</td><td width="60px" align="right">'.$Qty1.'</td><td width="60px" align="right">'.$Perc1.'</td><td width="60px" align="right">'.$Amount1.'</td></tr>';
		$BalancePerc = 100 - $Perc1;
		$BalanceAmount1 =  round(($Qty1 * $rate * $BalancePerc / 100),2);
		if($BalancePerc > 0){
			$BalPayDetailStr .= '<tr><td width="60px" align="right">'.$Qty1.'</td><td width="60px" align="right">'.$BalancePerc.'</td><td width="60px" align="right">'.$BalanceAmount1.'</td></tr>';
			$Line++;
		}
	
	}
	$BalPayDetailStr  .= '</table>';
	$CurrPayDetailStr .= '</table>';
	//$test = implode(",",$MasMBIdPArr1);
	$OutPut = $BalPayDetailStr."@*@".$CurrPayDetailStr."@*@".$Line;//."@*@".$test;
	//print_r($MasMBIdPArr4);
	return $OutPut;
}
?>
<?php require_once "Header.html"; ?>
	<script>
		function goBack(){
			url = "PartpaymentAbstractGenerate.php";
			window.location.replace(url);
		}
		window.history.forward();
		function noBack() { window.history.forward(); }
	</script>
	<style>
		.label{
			font-size:11px;
			font-family:Verdana, Arial, Helvetica, sans-serif;
			color:#0B29B9;
		}
		.labelprint{
			font-size:11px;
			font-family:Verdana, Arial, Helvetica, sans-serif;
			color:#0B29B9;
		}
	</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow-y:auto">
                        <div class="title">Part-Payment Generate</div>
                        <form name="form" method="post" action="PartpaymentAbstract.php">
							<div class="container">
							<br/>
							<?php
							//$Line=0;
							$title = '<table width="1058px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
										<tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
										</table>';
							echo $title;
							$table = $table . "<table width='1058px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
							$table = $table . "<tr>";
							$table = $table . "<td width='17%' class='' valign='middle'>Name of work</td>";
							$table = $table . "<td width='43%' style='word-wrap:break-word' class='' valign='middle'>" .$work_name."</td>";
							$table = $table . "<td width='18%' class='' valign='middle'>Name of the contractor</td>";
							$table = $table . "<td width='22%' class='' colspan='3' valign='middle'>" . $name_contractor . "</td>";
							$table = $table . "</tr>";
							$table = $table . "<tr>";
							$table = $table . "<td class='' valign='middle'>Technical Sanction No.</td>";
							$table = $table . "<td class='' valign='middle'>" . $tech_sanction . "</td>";
							$table = $table . "<td class='' valign='middle'>Agreement No.</td>";
							$table = $table . "<td class='' colspan='3' valign='middle'>" . $agree_no . "</td>";
							$table = $table . "</tr>";
							$table = $table . "<tr>";
							$table = $table . "<td class='' valign='middle'>Work order No.</td>";
							$table = $table . "<td class='' valign='middle'>" . $work_order_no . "</td>";
							$table = $table . "<td class='' valign='middle'>Running Account bill No. </td>";
							$table = $table . "<td class='' valign='middle'>" . $runn_acc_bill_no . $RabText. "</td>";
							$table = $table . "<td class='' align='right' valign='middle'>CC No. </td>";
							$table = $table . "<td class='' valign='middle'>" . $ccno . "</td>";
							$table = $table . "</tr>";
							$table = $table . "</table>";
							
							$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
							$tablehead = $tablehead . "<td  align='center' class='' width='70px' rowspan='2' valign='middle'>Item No.</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='80px' rowspan='2' valign='middle'>Rate</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='80px' rowspan='2' valign='middle'>Page / MB</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='4' valign='middle'>Current  Payment Details</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='4' valign='middle'>Previous Paid Details</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='3' valign='middle'>Balance Details</td>";
							$tablehead = $tablehead . "</tr>";
							$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>RAB</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>RAB</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "</tr>";
							//$Line = $Line + 2;
							$PPayItemIdArr = array();
							$SelectPartpaymentQuery = "select distinct subdivid from pp_qty_splt where sheetid = '$sheetid'";
							$SelectPartpaymentSql = mysql_query($SelectPartpaymentQuery);
							if($SelectPartpaymentSql == true){
								if(mysql_num_rows($SelectPartpaymentSql)>0){
									while($PPayItemIdList = mysql_fetch_object($SelectPartpaymentSql)){
										$PPayItemId = $PPayItemIdList->subdivid;
										array_push($PPayItemIdArr,$PPayItemId);
									}
								}
							}
							//print_r($PPayItemIdArr);exit;
							?>
							
							<?php echo $table; ?>
							<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor="#FFFFFF" id="table1">
							<?php echo $tablehead; ?>
							<?php 
							foreach($PPayItemIdArr as $PPayArrKey => $PPayArrValue){
								if($Line>20){
									echo "<tr><td colspan='14' style='border-bottom:1px solid white;border-left:1px solid white;border-right:1px solid white;' class='labelprint' align='center'>Page ".$Page."</td></tr>";
									echo "</table>";
									$Page++;
									echo "<p style='page-break-after:always;padding:0px;'></p>";
									echo '<table width="1058px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
										 <tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
										 </table>';
									echo $table;
									echo "<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor='#FFFFFF' id='table1'>";
									echo $tablehead;
									$Line = $LineIncr;
								}
								
								$PPayItemId = $PPayArrValue; $ExCnt = 1;
								$SelectSoqQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$PPayItemId'";
								$SelectSoqSql   = mysql_query($SelectSoqQuery);
								if($SelectSoqSql == true){
									if(mysql_num_rows($SelectSoqSql)>0){ 
										$SOQList = mysql_fetch_object($SelectSoqSql);
										$SOQDecimal 	= $SOQList->decimal_placed;
										$SOQRate 		= $SOQList->rate;
										$SOQUnit 		= $SOQList->per;
										$SOQItemNo 		= $SOQList->sno;
									}
								}
								//echo $SOQItemNo."<br/>";
								/*$SelectMasterQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId' and rbn = '$rbn'";
								$SelectMasterSql   = mysql_query($SelectMasterQuery);
								if($SelectMasterSql == true){
									if(mysql_num_rows($SelectMasterSql)>0){ 
										while($MasterList = mysql_fetch_object($SelectMasterSql)){
											$MasMBid 		= $MasterList->measurementbookid;
											$MasAbstMbNo 	= $MasterList->abstmbookno;
											$MasAbstPage 	= $MasterList->abstmbpage;
											$MasQty 		= $MasterList->mbtotal;
											$MasPayPerc 	= $MasterList->pay_percent;
											$MasPPayFlag 	= $MasterList->part_pay_flag;
											$ExpMasPPayFlag = explode("*",$MasPPayFlag);
											$PartPayParId   = $ExpMasPPayFlag[2];
											
										}
									}
								}*/
								
								//$PaidQtyPercArr = array(); $PrePaidperArrMast = array(); $TotalQtyToBePaidAsAgmt = 0; $TotalPrevQty = 0; $PrevMBidArrList = array(); $AllMBidListArr = array();
								
								$PrevMBidQtyArr = array(); $PrevMBidPercArr = array(); $AllMbidArr = array();
								$SelectPreviousPaidQuery = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$PPayItemId'";// and rbn = '$rbn'";
								//echo $SelectPreviousPaidQuery."<br/>";
								$SelectPreviousPaidSql   = mysql_query($SelectPreviousPaidQuery);
								if($SelectPreviousPaidSql == true){
									if(mysql_num_rows($SelectPreviousPaidSql)>0){ 
										while($PrePaidList = mysql_fetch_object($SelectPreviousPaidSql)){
											$PrePaidMbid 	= $PrePaidList->measurementbookid;
											$PrePaidQty 	= $PrePaidList->mbtotal;
											$PrePaidPerc 	= $PrePaidList->pay_percent;
											$PrePaidPpFlag 	= $PrePaidList->part_pay_flag;
											$PrePaidPpSplit = $PrePaidList->qty_split; //echo $PrePaidQty."<br/>";
											array_push($AllMbidArr,$PrePaidMbid);
											if(($PrePaidPpFlag == 1)||($PrePaidPpFlag == 0)){
												$PrevMBidQtyArr[$PrePaidMbid] = $PrePaidQty;
												$PrevMBidPercArr[$PrePaidMbid] = $PrePaidPerc;
											}else{
												$ExpPrePaidPpFlag = explode("*",$PrePaidPpFlag);
												$ExpPrePaidParId = $ExpPrePaidPpFlag[2];
												if($PrePaidPpSplit != "Y"){ 
													$PrevMBidPercArr[$ExpPrePaidParId] = $PrevMBidPercArr[$ExpPrePaidParId] + $PrePaidPerc;
												}
											}
											
											/*array_push($AllMBidListArr,$PrePaidList->measurementbookid);
											if(($PrePaidPpFlag == 1)||($PrePaidPpFlag == 0)){
												$PrevMBidArrList[$PrePaidList->measurementbookid] = $PrePaidPerc; 
												$TotalQtyToBePaidAsAgmt = $TotalQtyToBePaidAsAgmt + $PrePaidQty;
												$TotalPrevQty = $TotalPrevQty + $PrePaidQty;
												if(in_array($PrePaidPerc,$PrePaidperArrMast)){
													$PaidQtyPercArr[$PrePaidPerc] = $PaidQtyPercArr[$PrePaidPerc] + $PrePaidQty;
												}else{
													array_push($PrePaidperArrMast,$PrePaidPerc);
													$PaidQtyPercArr[$PrePaidPerc] = $PrePaidQty;
												}
											}else{
												$ExpPrePaidPpFlag = explode("*",$PrePaidPpFlag);
												$ExpPrePaidParId = $ExpPrePaidPpFlag[2];
												if($PrePaidPpSplit != "Y"){
													$PrevMBidArrList[$ExpPrePaidParId] = $PrevMBidArrList[$ExpPrePaidParId] + $PrePaidPerc;
												}
											}*/
										}
									}
								}
								print_r($PrevMBidQtyArr);echo "<br/>";
								print_r($PrevMBidPercArr);
								exit;
								$CurrQtyPercArr = array(); $CurrPaidperArrMast = array(); $TotalCurrQty = 0;
								$SelectCurrPaidQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId'";// and rbn = '$rbn'";
								$SelectCurrPaidSql   = mysql_query($SelectCurrPaidQuery);
								if($SelectCurrPaidSql == true){
									if(mysql_num_rows($SelectCurrPaidSql)>0){ 
										while($CurrPaidList 	= mysql_fetch_object($SelectCurrPaidSql)){
											$CurrPaidQty 		= $CurrPaidList->mbtotal;
											$CurrPaidPerc 		= $CurrPaidList->pay_percent;
											$CurrPaidPpFlag 	= $CurrPaidList->part_pay_flag;
											$CurrPaidPpSplit 	= $CurrPaidList->qty_split; //echo $PrePaidQty."<br/>";
											/*if(($CurrPaidPpFlag == 1)||($CurrPaidPpFlag == 0)){
												$TotalQtyToBePaidAsAgmt = $TotalQtyToBePaidAsAgmt + $CurrPaidQty;
												$TotalCurrQty = $TotalCurrQty + $CurrPaidQty;
												if(in_array($CurrPaidPerc,$CurrPaidperArrMast)){
													$CurrQtyPercArr[$CurrPaidPerc] = $CurrQtyPercArr[$CurrPaidPerc] + $CurrPaidQty;
												}else{
													array_push($CurrPaidperArrMast,$CurrPaidPerc);
													$CurrQtyPercArr[$CurrPaidPerc] = $CurrPaidQty;
												}
											}else{
												$ExpCurrPaidPpFlag = explode("*",$CurrPaidPpFlag);
												$ExpCurrPaidParId = $ExpCurrPaidPpFlag[2];
												if($CurrPaidPpSplit != "Y"){
													$PrevMBidArrList[$ExpCurrPaidParId] = $PrevMBidArrList[$ExpCurrPaidParId] + $PrePaidPerc;
												}
											}*/
										}
									}
								}
								
								
								
								
								
								
								print_r($PaidQtyPercArr);
								//echo $TotalPrevQty."<br/>";
								print_r($CurrQtyPercArr);
								//echo $TotalCurrQty."<br/>";
								exit;
								
								$SelectMasterQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId' and rbn = '$rbn'";
								$SelectMasterSql   = mysql_query($SelectMasterQuery);
								if($SelectMasterSql == true){
									if(mysql_num_rows($SelectMasterSql)>0){ 
										$MasterList = mysql_fetch_object($SelectMasterSql);
										$MasMBid 		= $MasterList->measurementbookid;
										$MasAbstMbNo 	= $MasterList->abstmbookno;
										$MasAbstPage 	= $MasterList->abstmbpage;
										$MasQty 		= $MasterList->mbtotal;
										$MasPayPerc 	= $MasterList->pay_percent;
										$MasPPayFlag 	= $MasterList->part_pay_flag;
										$ExpMasPPayFlag = explode("*",$MasPPayFlag);
										$PartPayParId   = $ExpMasPPayFlag[2];
									}
								}
								$DetailStr = ""; $TotalItemAmt = 0; $LineTemp1 = 0; $LineTemp2 = 0;  $MasPPIdArr = array();
								$SelectDetailQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId'";// and rbn = '$rbn'";
								$SelectDetailSql   = mysql_query($SelectDetailQuery);
								if($SelectDetailSql == true){
									if(mysql_num_rows($SelectDetailSql)>0){ 
										$CurrPayDetailStr = '<table class="table1" width="100%">';
										$PrevPayDetailStr = '<table class="table1" width="100%">';
										while($DetailList = mysql_fetch_object($SelectDetailSql)){
											$MasPPId 	= $DetailList->ppid;
											$MasMBid 	= $DetailList->mbid;
											$MasRbn 	= $DetailList->rbn;
											$MasQty 	= $DetailList->qty;
											$MasPercent = $DetailList->percent;
											$Masgpmbid 	= $DetailList->gpmbid;
											$Masrpmbid 	= $DetailList->rpmbid;
											$MasMbookno = $DetailList->mbookno;
											$MasPage 	= $DetailList->page;
											$MasAmount = round(($MasQty * $SOQRate * $MasPercent / 100),2);
											if($MasPercent != 0){
												if($MasRbn == $rbn){
													$CurrPayDetailStr .= '<tr><td width="60px" align="center">'.$rbn.'</td><td width="60px" align="right">'.$MasQty.'</td><td width="60px" align="right">'.$MasPercent.'</td><td width="60px" align="right">'.$MasAmount.'</td></tr>';
													$LineTemp1++;
												}else{
													$PrevPayDetailStr .= '<tr><td width="60px" align="center">'.$rbn.'</td><td width="60px" align="right">'.$MasQty.'</td><td width="60px" align="right">'.$MasPercent.'</td><td width="60px" align="right">'.$MasAmount.'</td></tr>';
													$LineTemp2++;
												}
											}
											$TotalItemAmt = $TotalItemAmt + $MasAmount; 
											if($MasRbn == $rbn){
												array_push($MasPPIdArr,$MasPPId);
											}
										}
										
										$CurrPayDetailStr.= '</table>';
										$PrevPayDetailStr.= '</table>';
									}
								}
								$PaymentStr = GetPrevPaymentDetails($sheetid,$PPayItemId,$SOQRate);
								$ExpPaymentStr = explode("@*@",$PaymentStr);
								$BalancePaymentStr 	= $ExpPaymentStr[0];
								$PrevPaymentStr 	= $ExpPaymentStr[1];
								$LineTemp3 			= $ExpPaymentStr[2];
								
								if(($LineTemp1 > $LineTemp2)&&($LineTemp1 > $LineTemp3)){
									$Line = $Line + $LineTemp1; //echo $Line;
								}else if(($LineTemp2 > $LineTemp1)&&($LineTemp2 > $LineTemp3)){
									$Line = $Line + $LineTemp2; //echo $Line;
								}else{
									$Line = $Line + $LineTemp3; //echo $Line;
								}
								
								foreach($MasPPIdArr as $PPIdKey => $PPIdValue){
									$UpdateQuery = "update pp_qty_splt set mbookno = '$abstmbno', page = '$Page' where ppid = '$PPIdValue' and sheetid = '$sheetid'";
									$UpdateSql = mysql_query($UpdateQuery);
								}
							?>
								<tr border='1' class="labelprint" bgcolor="">
									<td  align='center' valign='middle'><?php echo $SOQItemNo;  ?></td>
									<td  align='right' valign='middle'><?php echo $SOQRate; ?></td>
									<td  align='center' valign='middle'><?php if($MasAbstMbNo != ''){ echo $MasAbstPage."/".$MasAbstMbNo; } ?></td>
									<td  align='center' colspan="4" style="padding:0px;">
									<?php echo $CurrPayDetailStr; ?>
									</td>
									<td  align='center' colspan="4" style="padding:0px;">
									<?php echo $PrevPaymentStr; ?>
									</td>
									<td  align='right' colspan="3" style="padding:0px;">
									<?php echo $BalancePaymentStr; ?>
									</td>
								</tr>
								<?php //$Line++; ?>
								<tr border='1' class="label" bgcolor="">
									<td  align='center' valign='middle'><?php //echo $Line; ?></td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle' colspan="3">TOTAL</td>
									<td  align='right' valign='middle'><?php echo $TotalItemAmt; ?></td>
									<td  align='center' valign='middle' colspan="3">&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle' colspan="2">&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
								</tr>
								<?php $Line++; ?>
							<?php
							} 
							echo "<tr><td colspan='14' style='border-bottom:1px solid white;border-left:1px solid white;border-right:1px solid white;' class='labelprint' align='center'>Page ".$Page."</td></tr>";
							$Engpage = $Page;
							
							$DeleteMyMbookQuery = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and genlevel = 'ppayabs' and mtype = 'PA'";
							$DeleteMyMbookSql 	= mysql_query($DeleteMyMbookQuery);
							if(($Startpage>0)&&($abstmbno != '')){
								$InsertMyMbookQuery = "insert into mymbook set mbno = '$abstmbno', startpage = '$Startpage', endpage = '$Engpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'PA', zone_id = '$zone_id', genlevel = 'ppayabs', mbookorder = 1, active = 1, generatedate = NOW()";
								$InsertMyMbookSql 	= mysql_query($InsertMyMbookQuery);
							}
							?>
							</table>
							</br>
     						</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
								</div>
								<!--<div class="buttonsection">
								<input type="submit" class="btn" value=" View " name="btn_view" id="btn_view"   />
								</div>-->
							</div>
     					</form>
    				</blockquote>
   				</div>
  			</div>
 		</div>
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
   /* $(function(){
	
	});*/
</script>
</body>
</html>

