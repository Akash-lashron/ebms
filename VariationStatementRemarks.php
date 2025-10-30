<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
checkUser();
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
	$_SESSION['VarStmtSheetid'] = $sheetid;
	$_SESSION['VarStmtRbn'] = $rbn;
}

if($_POST["btn_save"] == "Save & Next"){
	$sheetid 	 = $_SESSION['VarStmtSheetid'];
	$SubDivIdArr = $_POST['txt_subdivid'];
	$RemarksArr  = $_POST['txt_remarks'];
	$RemarksCom  = $_POST['txt_var_remark'];
	$SchIdArr    = $_POST['txt_sch_id'];
	foreach($SubDivIdArr as $SubDivIdKey => $SubDivId){
		$Remarks = trim($RemarksArr[$SubDivIdKey]);
		$SchId   = trim($SchIdArr[$SubDivIdKey]);
		$UpdateRemarksQuery = "update schdule set vari_stmt_rem = '$Remarks' where sheet_id = '$sheetid' and subdiv_id = '$SubDivId' and sch_id = '$SchId'";
		$UpdateRemarksQuery = "update sheet set var_remark = '$RemarksCom' where sheet_id = '$sheetid' ";
		//echo $UpdateRemarksQuery;exit;
		$UpdateRemarksSql = mysql_query($UpdateRemarksQuery);
	}
	header("Location:VariationStatement.php");
}

$sheetid = $_SESSION['VarStmtSheetid'];
$rbn 	 = $_SESSION['VarStmtRbn'];

$select_query 	= "select * from sheet where sheet_id = '$sheetid'";
$select_sql 	= mysql_query($select_query);
if($select_sql == true){
	if(mysql_num_rows($select_sql)>0){
		$SheetList 	= mysql_fetch_object($select_sql);
		$WorkName 	= $SheetList->work_name;
		$WorkOrder 	= $SheetList->work_order_no;
		$RebatePercent 	= $SheetList->rebate_percent;
		$VarRemark 	= $SheetList->var_remark;
	}
}
	
$select_finalbill_query = "select distinct is_finalbill from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
$select_finalbill_sql = mysql_query($select_finalbill_query);
if($select_finalbill_sql == true){
	$FList = mysql_fetch_object($select_finalbill_sql);
	$is_finalbill = $FList->is_finalbill;
}

if($is_finalbill == "Y"){
	$RabText = " & Final Bill";
}else{
	$RabText = "";
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
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "VariationStatementGenerate.php";
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
        	<div class="title">Variation Statement</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto" id="printSection">
                        <form name="form" method="post" action="">
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
								<table width=""  bgcolor="#E8E8E8" class="table1" align="center">
									<tr>
										<td class="label" colspan="2">Name of Work</td>
										<td colspan="12"><?php echo $WorkName; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="2">Work Oredr No.</td>
										<td colspan="12"><?php echo $WorkOrder; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="2">RAB No.</td>
										<td colspan="12"><?php echo $rbn; ?><?php echo $RabText; ?></td>
									</tr>
									<tr>
										<td class="label" colspan="14" align="center"> Variation Statement for RAB - <?php echo $rbn; ?><?php echo $RabText; ?></td>
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
										
									</tr>
									
						<?php
							$AgtAmtTotal = 0; $ExeAmtTotal = 0; $RowLine = 8; $VSpage = 1;
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
									//$TotalAmt 	= $List->total_amt;
									$DevPerc 	= $List->deviate_qty_percent;
									$ItemUnit 	= $List->per;
									$Decimal 	= $List->decimal_placed;
									
									
									$UnitFactor 		= findNumericFromString($ItemUnit);
									$rateWithUnitfactor = $ItemRate / $UnitFactor;
									$rateDisplay 		= $ItemRate;
									
									$ItemRate 			= $rateWithUnitfactor;
									$TotalAmt 	= round(($ItemQty * $ItemRate),2);
									
									$ExecQty	= 0;
									$ExcessQty 	= 0;
									$ExcessAmt 	= 0;
									$ExcessPerc = 0;
									$SavingQty	= 0;
									$SavingAmt	= 0;
									$SavingPerc = 0;
									$AgtAmtTotal = $AgtAmtTotal + $TotalAmt;
	
									if($ItemId != "" && $ItemId != 0){
										$DPMQty = 0;
										$select_qty_query1 	= "select sum(mbtotal) as exe_qty from measurementbook where sheetid = '$sheetid' and subdivid = '$ItemId' and rbn <= '$rbn' and (part_pay_flag = '1' OR part_pay_flag = '0') group by subdivid";
										$select_qty_sql1 	= mysql_query($select_qty_query1);
										if($select_qty_sql1 == true){
											if(mysql_num_rows($select_qty_sql1)>0){
												$QList1 	= mysql_fetch_object($select_qty_sql1);
												$DPMQty 	= $QList1->exe_qty;
											}
										}
										
										$SLMQty = 0;
										$select_qty_query2 	= "select sum(mbtotal) as exe_qty from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$ItemId' and rbn <= '$rbn' and (part_pay_flag = '1' OR part_pay_flag = '0') group by subdivid";
										$select_qty_sql2 	= mysql_query($select_qty_query2);
										if($select_qty_sql2 == true){
											if(mysql_num_rows($select_qty_sql2)>0){
												$QList2 	= mysql_fetch_object($select_qty_sql2);
												$SLMQty 	= $QList2->exe_qty;
											}
										}
										$ExecQty 	= round(($DPMQty + $SLMQty),$Decimal);
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
						?>
									<tr>
										<td align="center"><?php echo $ItemNo; ?></td>
										<td align="left" class="hideText"><?php echo $Descrip; ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($ItemQty,$Decimal,".",","); } ?></td>
										<td align="center"><?php if($ItemId != "" && $ItemId != 0){ echo $ItemUnit; } ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($rateDisplay,2,".",","); } ?></td>
										<td align="right"><?php if($ItemId != "" && $ItemId != 0){ echo number_format($TotalAmt,2,".",","); } ?></td>
										
										<td align="right"><?php if($ExecQty != "" && $ExecQty != 0){ echo number_format($ExecQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($ExecAmt != "" && $ExecAmt != 0){ echo number_format($ExecAmt,2,".",","); } ?></td>
										
										<td align="right"><?php if($ExcessQty != "" && $ExcessQty != 0){ echo number_format($ExcessQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($ExcessAmt != "" && $ExcessAmt != 0){ echo number_format($ExcessAmt,2,".",","); } ?></td>
										<td align="center"><?php if($ExcessPerc != "" && $ExcessPerc != 0){ echo number_format($ExcessPerc,2,".",","); } ?></td>
										<td align="right"><?php if($SavingQty != "" && $SavingQty != 0){ echo number_format($SavingQty,$Decimal,".",","); } ?></td>
										<td align="right"><?php if($SavingAmt != "" && $SavingAmt != 0){ echo number_format($SavingAmt,2,".",","); } ?></td>
										<td align="center"><?php if($SavingPerc != "" && $SavingPerc != 0){ echo number_format($SavingPerc,2,".",","); } ?></td>
									</tr>
									<tr>
										<td colspan="14" align="center">
											<textarea name="txt_remarks[]" id="txt_remarks<?php echo $ItemId; ?>" style="width:99%; height:35px; border:1px solid #419EFC; background:#fff" placeholder="Enter your remarks here" class="textboxdisplay"><?php echo $List->vari_stmt_rem; ?></textarea>
											<input type="hidden" name="txt_subdivid[]" id="txt_subdivid<?php echo $ItemId; ?>" class="textboxdisplay" value="<?php echo $ItemId; ?>">
											<input type="hidden" name="txt_sch_id[]" id="txt_sch_id<?php echo $ItemId; ?>" class="textboxdisplay" value="<?php echo $List->sch_id; ?>">
										</td>
										
									</tr>
									
						<?php
									/*if($RowLine > 25){
						?>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
								<table width="1087px"  bgcolor="#E8E8E8" class="table1" align="center">
									<tr class="label">
										<td align="center" valign="middle" nowrap="nowrap" rowspan="2">Item No.</td>
										<td align="left" valign="middle" rowspan="2">Description</td>
										<td align="center" valign="middle" colspan="4">As Per Agreement</td>
										<td align="center" valign="middle" colspan="2" nowrap="nowrap">As Per Execution</td>
										<td align="center" valign="middle" colspan="2">Excess</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Excess</td>
										<td align="center" valign="middle" colspan="2">Savings</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Savings</td>
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
									</tr>
						<?php		
										$RowLine = 4;		
									}
									$RowLine++;*/
								}
								$AgtAmtTotal = round($AgtAmtTotal,2);
								$ExeAmtTotal = round($ExeAmtTotal,2);
								
								$AgtVariAmt = round(($AgtAmtTotal - $ExeAmtTotal),2);
								$AgtVariAmt = abs($AgtVariAmt);
								
								$AgtVariperc = round(($AgtVariAmt * 100/$AgtAmtTotal),2);
								if($RowLine > 20){
						?>
						   
							</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
								<table width=""  bgcolor="#E8E8E8" class="table1" align="center">
									<tr class="label">
										<td align="center" valign="middle" nowrap="nowrap" rowspan="2">Item No.</td>
										<td align="left" valign="middle" rowspan="2">Description</td>
										<td align="center" valign="middle" colspan="4">As Per Agreement</td>
										<td align="center" valign="middle" colspan="2" nowrap="nowrap">As Per Execution</td>
										<td align="center" valign="middle" colspan="2">Excess</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Excess</td>
										<td align="center" valign="middle" colspan="2">Savings</td>
										<td align="center" valign="middle" rowspan="2" nowrap="nowrap">% of Savings</td>
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
									</tr>
						<?php
								}
						?>		
									<tr>
										<td colspan="14" align="left">Remarks </td>
									</tr>
									<tr>
										<td colspan="14" align="center">
											<textarea name="txt_var_remark" id="txt_var_remark<?php //echo $ItemId; ?>" style="width:99%; height:35px; border:1px solid #419EFC; background:#fff" placeholder="Enter your remarks here" class="textboxdisplay"><?php echo $VarRemark; ?></textarea>
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Total Amount</td>
										<td align="right"></td>
										<td align="right"><?php if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>
									<?php  
										$RebateAmtFotAgmtAmt = round(($AgtAmtTotal * $RebatePercent / 100),2);
										$RebateAmtFotExeAmt = round(($ExeAmtTotal * $RebatePercent / 100),2);
									?>
									<tr class="label">
										<td align="left" colspan="4">Rebate ( <?php echo $RebatePercent; ?> % )</td>
										<td align="right"></td>
										<td align="right"><?php echo number_format($RebateAmtFotAgmtAmt,2,".",","); ?></td>
										<td align="center"></td>
										<td align="right"><?php echo number_format($RebateAmtFotExeAmt,2,".",","); ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>
									<?php
										$AgtAmtTotal = round(($AgtAmtTotal - $RebateAmtFotAgmtAmt),2);
										$ExeAmtTotal = round(($ExeAmtTotal - $RebateAmtFotExeAmt),2);
										
									?>
									
									<tr class="label">
										<td align="left" colspan="4">Total Amount</td>
										<td align="right"></td>
										<td align="right"><?php if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="right"><?php if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>
									
									<tr class="label">
										<td align="left" colspan="4">Variation in Amount as per Agreement</td>
										<td align="right"><?php echo number_format($AgtVariAmt,2,".",",");//if($AgtVariAmt != "" && $AgtVariAmt != 0){ echo number_format($AgtVariAmt,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>
									<!--<tr class="label">
										<td align="left" colspan="4">Variation in Amount as per TS</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>-->
									<!--<tr class="label">
										<td align="left" colspan="4">Technical Sanction Amount</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>-->
									<tr class="label">
										<td align="left" colspan="4">% ge of overall Excess as per Agreement</td>
										<td align="right"><?php echo number_format($AgtVariperc,2,".",",");//if($AgtVariperc != "" && $AgtVariperc != 0){ echo number_format($AgtVariperc,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>
									<!--<tr class="label">
										<td align="left" colspan="4">% ge of overall Excess as per Technical Sanction</td>
										<td align="right"><?php //if($AgtAmtTotal != "" && $AgtAmtTotal != 0){ echo number_format($AgtAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="right"><?php //if($ExeAmtTotal != "" && $ExeAmtTotal != 0){ echo number_format($ExeAmtTotal,2,".",","); } ?></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
										<td align="center"></td>
									</tr>-->
						<?php		
							}else{
						?>
									<tr><td align="center" colspan="14"> No Records Found !</td></tr>
						<?php 
							} 
						?>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$VSpage; $VSpage++; ?></p>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
								</div>
								<div class="buttonsection" id="view_btn_section">
									<input type="submit" name="btn_save" value="Save & Next" id="btn_save" class="backbutton" />
								</div>
							</div>
       					</form>
      				</blockquote>
    			</div>
   			</div>
		</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
	$("#cmb_shortname").chosen();
	$('.rem').click(function(){
		var id = $(this).attr('id');
		console.log (id);
		BootstrapDialog.show({
			title: 'Add Remark',
			message: $('<div></div>').load('load/page/Remark.php'),
			buttons: [{
                label: ' Save ',
				cssClass: 'modal-button',
				action: function(dialogItself){
					var AcctName = $('#txt_remark').val();
					var form = $('form')[1]; // You need to use standart javascript object here
					var formData = new FormData(form);
					
					var ErrCount = 0;
					var REM	= $('#txt_remark').val();
					if(REM == ""){ ErrCount++; $('#txt_remark').addClass('errorClass'); }else{ $('#txt_remark').removeClass('errorClass'); }
					if(ErrCount == 0){
						$.ajax({ 
							type      	: 'POST', 
							url       	: 'load/ajax/Remark_Save.php',
							data        : { id: id ,AcctName: AcctName }, 
				            dataType    : 'json',
							success   	: function(data){ //alert(data);
							/*if(datas == 1){
									BootstrapDialog.alert({ title: 'Error !',message: '<i class="fa fa-times-circle" style="font-size:20px; color:red"></i> This Head of Account Already Exists !'});
								}else if(datas == 2){
									BootstrapDialog.alert({ title: 'Success !',message: '<i class="fa fa-check-circle" style="font-size:20px; color:green"></i> Head of Account Saved Successfully'});
								}else{
									BootstrapDialog.alert({ title: 'Error !',message: '<i class="fa fa-times-circle" style="font-size:20px; color:red"></i> Head of Account Not Saved. Please Try Again !'});
								}*/
						}
					  });
						dialogItself.close();
					}
                }
            },{
                label: ' Cancel ',
                cssClass: 'modal-button',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
		});
		
	});

</script>
<style>
	::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
	  color:#C0C0C0;
	  opacity: 1; /* Firefox */
	}
	
	:-ms-input-placeholder { /* Internet Explorer 10-11 */
	  color:#C0C0C0;
	}
	
	::-ms-input-placeholder { /* Microsoft Edge */
	  color:#C0C0C0;
	}
</style>
</body>
</html>

