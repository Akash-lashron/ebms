<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
$count = 0;
if($_POST["btn_view"] == " View "){
	$sheetid 	= $_POST['cmb_shortname'];
	$rbn 		= $_POST['cmb_rbn'];
	$is_finalbill= $_POST['ch_finalbill'];
	if($is_finalbill == "Y"){
		$RabText = " & Final Bill";
	}else{
		$RabText = "";
	}
	
	$select_query 	= "select * from sheet where sheet_id = '$sheetid'";
	$select_sql 	= mysql_query($select_query);
	if($select_sql == true){
		if(mysql_num_rows($select_sql)>0){
			$SheetList 		= mysql_fetch_object($select_sql);
			$WorkName 		= $SheetList->work_name;
			$WorkOrder 		= $SheetList->work_order_no;
			$CCNo 			= $SheetList->computer_code_no;
			$ContractName 	= $SheetList->name_contractor;
			$RebatePercent 	= $SheetList->rebate_percent;
		}
	}
	
	//$select_detail_query 	= "select a.*, b.*, sum(b.mbtotal) as exe_qty from schdule a inner join mbookgenerate_staff b on (a.subdiv_id = b.subdivid) where a.sheet_id = '$sheetid' and b.sheetid = '$sheetid' and b.rbn <= '$rbn' group by b.subdivid order by  sch_id asc";
	$select_sheet_query 	= "select * from schdule where sheet_id = '$sheetid' and subdiv_id != 0 order by  sch_id asc";
	$select_sheet_sql 		= mysql_query($select_sheet_query);
	if($select_sheet_sql == true){
		if(mysql_num_rows($select_sheet_sql)>0){
			$count = 1;
		}
	}
	//echo $select_detail_query;exit;
}
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "VairationStatementGenerate.php";
		window.location.replace(url);
	}
	function PrintBook(){
	   	var printContents 		= document.getElementById('printSection').innerHTML;
		var originalContents 	= document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.table1{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.label{
		font-size:11px;
	}
	.table1 td{
	padding:2px;
	}
	@media print {
		#printSection{
			padding-top:2px;
			text-align:center;
		}
	} 
	.hideText{
	  max-width : 150px; 
	  white-space : nowrap;
	  overflow : hidden;
	  text-overflow: ellipsis;
  	}
	.bq1 p {
		padding:2px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		margin-top: 5px;
		margin-bottom: 5px;
		font-size:12px;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
		<div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto" id="printSection">
        	<div class="title">Variation Statement</div>
                        <form name="form" method="post" action="SecuredAdvancePrintView.php">
                            <div class="container" align="center">
								<br/>
								<style>
									@media print {
										#printSection{
											padding-top:2px;
											align-content: center;
										}
										@page {
										  size: A4 landscape;
										   margin: 10mm 10mm 10mm 10mm;
										}
									} 
								</style>
								<!--<table width="1173px"  bgcolor="#E8E8E8" class="table1" align="center">
									
								</table>-->
								<!--<table width="1060px"  bgcolor="#E8E8E8" class="table1" align="center">
									<tr>
										<td class="label" colspan="2">Name of Work</td>
										<td colspan="13"><?php echo $WorkName; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="2">Work Oredr No.</td>
										<td colspan="13"><?php echo $WorkOrder; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="2">RAB No.</td>
										<td colspan="13"><?php echo $rbn; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="15" align="center"> Variation Statement for RAB - <?php echo $rbn; ?> <?php echo $RabText; ?></td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle" nowrap="nowrap" rowspan="2">Item No.</td>
										<td align="left" valign="middle" rowspan="2">Description</td>
										<td align="center" valign="middle" colspan="4">As Per Agreement</td>
										<td align="center" valign="middle" colspan="2" nowrap="nowrap">As Per Execution</td>
										<td align="center" valign="middle" colspan="2">Excess</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Excess</td>
										<td align="center" valign="middle" colspan="2">Savings</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Savings</td>
										<td align="center" valign="middle" rowspan="2">Remarks</td>
									</tr>
									<tr class="label">
										<td align="left" valign="middle">Qty</td>
										<td align="left" valign="middle">Unit</td>
										<td align="left" valign="middle">Rate</td>
										<td align="left" valign="middle">Amount</td>
										<td align="left" valign="middle">Qty</td>
										<td align="left" valign="middle">Amount</td>
										<td align="left" valign="middle">Qty</td>
										<td align="left" valign="middle">Amount</td>
										<td align="left" valign="middle">Qty</td>
										<td align="left" valign="middle">Amount</td>
									</tr>-->
								<table width="1060px"  bgcolor="#E8E8E8" class="table1" align="center">
									<tr class="label">
										<td class="label" colspan="2">Name of Work</td>
										<td colspan="9"><?php echo $WorkName; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2">Work Order No.</td>
										<td colspan="4" nowrap="nowrap"><?php echo $WorkOrder; ?></td>
										<td>RAB No.</td>
										<td align="center"><?php echo $rbn; ?></td>
										<td>&nbsp;</td>
										<td align="center">C.C. No</td>
										<td align="center"><?php echo $CCNo; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2" rowspan="2" align="center" valign="middle">Name of the Bidder</td>
										<td colspan="4" align="center">As Per Agreement Qty</td>
										<td colspan="4" align="center">As Per Executed Qty</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="label">
										<td colspan="4" align="center" valign="middle">As Per Agreement Amount</td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td align="center" valign="middle">Remarks</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle">Item No</td>
										<td align="center" valign="middle">Item Description</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Unit</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Remarks</td>
									</tr>	
									
									
						<?php
							$AgtAmtTotal = 0; $ExeAmtTotal = 0; $RowLine = 8; $VSpage = 1; $ExeAmtTotalL2 = 0;
							if($count == 1){
								while($List = mysql_fetch_object($select_sheet_sql)){
									$ItemNo 	= $List->sno;
									$ItemId 	= $List->subdiv_id;
									$description= $List->description;
									$shortnotes = $List->shortnotes;
									if($shortnotes != ""){
										$Descrip = $shortnotes;
									}else{
										$Descrip = $description;
									}
									$ItemQty 	= $List->total_quantity;
									$ItemRate 	= $List->rate;
									$TotalAmt 	= $List->total_amt;
									$DevPerc 	= $List->deviate_qty_percent;
									$ItemUnit 	= $List->per;
									$Decimal 	= $List->decimal_placed;
									
									$L2Rate 	= $List->l2_rate;
									$L2Amount 	= round(($ItemQty * $L2Rate),2);
									
									$ExecQty	= 0;
									$ExcessQty 	= 0;
									$ExcessAmt 	= 0;
									$ExcessPerc = 0;
									$SavingQty	= 0;
									$SavingAmt	= 0;
									$SavingPerc = 0;
									$AgtAmtTotal = $AgtAmtTotal + $TotalAmt;
	
									if($ItemId != "" && $ItemId != 0){
										$select_qty_query 	= "select sum(mbtotal) as exe_qty from mbookgenerate_staff where sheetid = '$sheetid' and subdivid = '$ItemId' and rbn <= '$rbn' group by subdivid";
										$select_qty_sql 	= mysql_query($select_qty_query);
										if($select_qty_sql == true){
											if(mysql_num_rows($select_qty_sql)>0){
												$QList 		= mysql_fetch_object($select_qty_sql);
												$ExecQty 	= $QList->exe_qty;
											}
										}
										$ExecAmt 	= round($ExecQty * $ItemRate,2);
										$DevQty 	= 0;//round(($ItemQty * $DevPerc / 100),$Decimal);
										$TotalQty 	= $ItemQty + $DevQty;
										$BalQty 	= round(($TotalQty - $ExecQty),$Decimal);
										
										
										if($BalQty >= 0){
											//Savings
											$SavingQty 	= $BalQty;
											$SavingAmt 	= round($SavingQty * $ItemRate,2);
											$SavingPerc = round(($SavingQty*100/$TotalQty),2);
											
											$ExcessQty	= 0;
											$ExcessAmt	= 0;
											$ExcessPerc = 0;
										}else{
											//Excess
											$ExcessQty 	= abs($BalQty);//abs is for to remove minus sign
											$ExcessAmt 	= round($ExcessQty * $ItemRate,2);
											$ExcessPerc = round(($ExcessQty*100/$TotalQty),2);
											
											$SavingQty	= 0;
											$SavingAmt	= 0;
											$SavingPerc = 0;
										}
										
										$ExecPerc 	= round(($ExecQty * 100/$TotalQty),2);
										$BalPerc 	= round(($BalQty * 100/$TotalQty),2);
									}
									$ExeAmtTotal = $ExeAmtTotal + $ExecAmt;
									$ExeAmtTotalL2 = $ExeAmtTotalL2 + $L2Amount;
						?>
									<!--<tr>
										<td align="center"><?php echo $ItemNo; ?></td>
										<td align="left" class="hideText"><?php echo $Descrip; ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($ItemQty,$Decimal,".",","); } ?></td>
										<td align="center"><?php if($ItemId != "" && $ItemId != 0){ echo $ItemUnit; } ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($ItemRate,2,".",","); } ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($TotalAmt,2,".",","); } ?></td>
										
										<td align="right"><?php if($ExecQty != "" && $ExecQty != 0){ echo number_format($ExecQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($ExecAmt != "" && $ExecAmt != 0){ echo number_format($ExecAmt,2,".",","); } ?></td>
										
										<td align="right"><?php if($ExcessQty != "" && $ExcessQty != 0){ echo number_format($ExcessQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($ExcessAmt != "" && $ExcessAmt != 0){ echo number_format($ExcessAmt,2,".",","); } ?></td>
										<td align="center"><?php if($ExcessPerc != "" && $ExcessPerc != 0){ echo number_format($ExcessPerc,2,".",","); } ?></td>
										<td align="right"><?php if($SavingQty != "" && $SavingQty != 0){ echo number_format($SavingQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($SavingAmt != "" && $SavingAmt != 0){ echo number_format($SavingAmt,2,".",","); } ?></td>
										<td align="center"><?php if($SavingPerc != "" && $SavingPerc != 0){ echo number_format($SavingPerc,2,".",","); } ?></td>
										<td align="center"><?php //if($ItemId != "" && $ItemId != 0){ echo $Variation; } ?></td>
									</tr>-->
									<tr class="label">
										<td align="center" valign="middle"><?php echo $ItemNo; ?></td>
										<td align="center" valign="middle" class="hideText"><?php echo $Descrip; ?></td>
										<td align="right" valign="middle"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($ItemQty,$Decimal,".",","); } ?></td>
										<td align="center" valign="middle"><?php if($ItemId != "" && $ItemId != 0){ echo $ItemUnit; } ?></td>
										<td align="right" valign="middle"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($ItemRate,2,".",","); } ?></td>
										<td align="right" valign="middle"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($TotalAmt,2,".",","); } ?></td>
										<td align="right" valign="middle"><?php if($ExecQty != "" && $ExecQty != 0){ echo number_format($ExecQty,$Decimal,".",","); } ?></td>
										<td align="right" valign="middle"><?php if($ExecAmt != "" && $ExecAmt != 0){ echo number_format($ExecAmt,2,".",","); } ?></td>
										<td align="right" valign="middle"><?php echo number_format($L2Rate,2,".",","); ?></td>
										<td align="right" valign="middle"><?php echo number_format($L2Amount,2,".",","); ?></td>
										<td align="center" valign="middle">Remarks</td>
									</tr>	
									
						<?php
									if($RowLine > 25){
						?>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
								<table width="1060px"  bgcolor="#E8E8E8" class="table1" align="center">
									<tr class="label">
										<td class="label" colspan="2">Name of Work</td>
										<td colspan="9"><?php echo $WorkName; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2">Work Order No.</td>
										<td colspan="4" nowrap="nowrap"><?php echo $WorkOrder; ?></td>
										<td>RAB No.</td>
										<td align="center"><?php echo $rbn; ?></td>
										<td>&nbsp;</td>
										<td align="center">C.C. No</td>
										<td align="center"><?php echo $CCNo; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2" rowspan="2" align="center" valign="middle">Name of the Bidder</td>
										<td colspan="4" align="center">As Per Agreement Qty</td>
										<td colspan="4" align="center">As Per Executed Qty</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="label">
										<td colspan="4" align="center" valign="middle">As Per Agreement Amount</td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td align="center" valign="middle">Remarks</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle">Item No</td>
										<td align="center" valign="middle">Item Description</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Unit</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Remarks</td>
									</tr>	
						<?php		
										$RowLine = 4;		
									}
									$RowLine++;
								}
								$AgtAmtTotal = round($AgtAmtTotal,2);
								$ExeAmtTotal = round($ExeAmtTotal,2);
								$ExeAmtTotalL2 = round($ExeAmtTotalL2,2);
								
								$AgtVariAmt = round(($AgtAmtTotal - $ExeAmtTotal),2);
								$AgtVariAmt = abs($AgtVariAmt);
								
								$AgtVariperc = round(($AgtVariAmt * 100/$AgtAmtTotal),2);
								if($RowLine > 20){
						?>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
								<table width="1087px"  bgcolor="#E8E8E8" class="table1" align="center">
									<tr class="label">
										<td class="label" colspan="2">Name of Work</td>
										<td colspan="9"><?php echo $WorkName; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2">Work Order No.</td>
										<td colspan="4" nowrap="nowrap"><?php echo $WorkOrder; ?></td>
										<td>RAB No.</td>
										<td align="center"><?php echo $rbn; ?></td>
										<td>&nbsp;</td>
										<td align="center">C.C. No</td>
										<td align="center"><?php echo $CCNo; ?></td>
									</tr>
									<tr class="label">
										<td class="label" colspan="2" rowspan="2" align="center" valign="middle">Name of the Bidder</td>
										<td colspan="4" align="center">As Per Agreement Qty</td>
										<td colspan="4" align="center">As Per Executed Qty</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="label">
										<td colspan="4" align="center" valign="middle">As Per Agreement Amount</td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td colspan="2" align="center" valign="middle">with L1 bidder of <?php echo $ContractName; ?></td>
										<td align="center" valign="middle">Remarks</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle">Item No</td>
										<td align="center" valign="middle">Item Description</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Unit</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Qty</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Rate</td>
										<td align="center" valign="middle">Amount</td>
										<td align="center" valign="middle">Remarks</td>
									</tr>
						<?php
								}
						?>		
									<tr class="label">
										<td align="left" colspan="4">Total Amount</td>
										<td align="right"><?php if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotalL2 != "" && $ExeAmtTotalL2 != 0){ echo number_format($ExeAmtTotalL2,2,".",","); } ?></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<?php 
									$RebateAmtAgmt = round(($AgtAmtTotal * $RebatePercent / 100),2);
									$RebateAmtExce = round(($ExeAmtTotal * $RebatePercent / 100),2);
									$TotalAmtAftRebateAgmt = round(($AgtAmtTotal - $RebateAmtAgmt),2);
									$TotalAmtAftRebateExce = round(($ExeAmtTotal - $RebateAmtExce),2);
									?>
									<tr class="label">
										<td align="left" colspan="4">Rebate Amount (<?php echo $RebatePercent; ?> %)</td>
										<td align="right"><?php if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($RebateAmtAgmt,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($RebateAmtExce,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"><?php //if($ExeAmtTotalL2 != "" && $ExeAmtTotalL2 != 0){ echo number_format($RebateAmtExce,2,".",","); } ?></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Total Amount after Rebate</td>
										<td align="right"><?php if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($TotalAmtAftRebateAgmt,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($TotalAmtAftRebateExce,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotalL2 != "" && $ExeAmtTotalL2 != 0){ echo number_format($ExeAmtTotalL2,2,".",","); } ?></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Variation in Amount as per Agreement</td>
										<td align="right"><?php echo number_format($AgtVariAmt,2,".",","); ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Variation in Amount as per TS</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Technical Sanction Amount</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">% ge of overall Excess as per Agreement</td>
										<td align="right"><?php if($AgtVariperc != "" && $AgtVariperc != 0){ echo number_format($AgtVariperc,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
									<tr class="label">
										<td align="left" colspan="4">% ge of overall Excess as per Technical Sanction</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<!--<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>-->
									</tr>
						<?php		
							}else{
						?>
									<tr><td align="center" colspan="15"> No Records Found !</td></tr>
						<?php 
							} 
						?>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
							</div>
       					</form>
      				</blockquote>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
						</div>
						<div class="buttonsection" id="view_btn_section">
							<input type="button" name="btn_print" value="Print" id="btn_print" class="backbutton" onClick="PrintBook();" />
						</div>
					</div>
    			</div>
   			</div>
		</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
	$("#cmb_shortname").chosen();
</script>
</body>
</html>

