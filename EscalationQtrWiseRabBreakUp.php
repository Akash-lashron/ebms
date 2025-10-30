<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
} 
function RABillBreakupCalculation($QuarterRabDataValue){ 
    $MeasFromDate = $QuarterRabDataValue->breakup_from_date; 
    $MeasToDate = $QuarterRabDataValue->breakup_to_date;
    $BreakupRbn = $QuarterRabDataValue->rbn;
    $BreakupSheetId = $QuarterRabDataValue->sheetid;
    $BillBreakAmtWithItemData = "SELECT mbookdetail.subdivid,SUM(mbookdetail.measurement_contentarea) AS used_total_quantity,schdule.* FROM mbookdetail
    INNER JOIN mbookheader ON mbookdetail.mbheaderid = mbookheader.mbheaderid
    INNER JOIN schdule ON mbookdetail.subdivid = schdule.subdiv_id
    WHERE mbookheader.sheetid = '$BreakupSheetId'
    AND mbookheader.date >= '$MeasFromDate'
    AND mbookheader.date <= '$MeasToDate'
    GROUP BY mbookdetail.subdivid,schdule.subdiv_id";
    
    $BillBreakAmtWithItemData = mysql_query($BillBreakAmtWithItemData);
    $BillBreakAmtWithItem = array();
    while($BillBreakAmt = mysql_fetch_object($BillBreakAmtWithItemData)){
        $BillBreakAmtWithItem[] = $BillBreakAmt;
    }
    return $BillBreakAmtWithItem; 
};
function CheckPartRateForEscalation($SheetId,$Rbn,$ItemId){ 
    $PartRatePayData = "SELECT * FROM partpayment WHERE active = 1 AND sheetid = ".$SheetID." AND rbn = ".$Rbn." AND s_itemid = ".$ItemId." ORDER BY ppayid ASC";
    $PartRatePayData =  mysql_query($PartRatePayData);
    $ReturnArr = array('PARTRATEPAY'=>$PartRatePayData,'PARTRATEREL'=>NULL);
    return $ReturnArr;
}
$sheetid  		= $_SESSION['escal_sheetid'];
$cc_esc_rbn 	= $_SESSION['escal_rbn'];
$page 	        = $_SESSION['page'];
if($sheetid != ""){
    $WorkOrder     = "SELECT * FROM sheet WHERE active = 1 AND sheet_id = '$sheetid'";
    $WorkOrder     = mysql_query($WorkOrder); 
    $WorkOrderData = mysql_fetch_object($WorkOrder);
    $RebatePerc        = $WorkOrderData->rebate_percent; 
    $EscBill     = "SELECT * FROM escalation WHERE active = 1 AND sheetid = '$sheetid' AND rbn = '$cc_esc_rbn' ORDER BY quarter ASC";
    $EscBillData = mysql_query($EscBill);
    //$EscBillData     = $this->esclation->ShowEscalation($request,$SheetId,$Rbn);
    $RABBreakupArr   = array();
    $RABNoBreakupArr = array();
    $RABDataArr      = array();
    $QuarterRabDataArr  = array();
    $RabQuarterMapArr   = array();
    $RowSpanQtrArr = array();
    $RowSpanRabArr = array();
    $Units = "SELECT * from unit ";
    $AllUnits = mysql_query($Units);
    while ($row = mysql_fetch_assoc($AllUnits)) {
        $UnitCodeArr[$row['unit_name']] = $row['unit_name'];
    }
    if($EscBillData == true){
       while ($row = mysql_fetch_assoc($EscBillData)) {
            $quarter = $row['quarter'];
            if (!isset($QtrGrpEscBillData[$quarter])) {
                $QtrGrpEscBillData[$quarter] = array();
            }        
            $QtrGrpEscBillData[$quarter][] = $row;
        }
        if($QtrGrpEscBillData == true){
            foreach($QtrGrpEscBillData as $QtrGrpEscBillDataValue){   //dd($QtrGrpEscBillDataValue);
                $QuarterData        = $QtrGrpEscBillDataValue[0];
                $QuarterFromDate    = $QuarterData['quarter_from_date'];//"2024-01-01";//
                $QuarterToDate      = $QuarterData['quarter_to_date']; //"2024-02-07";//
                $Quarter            = $QuarterData['quarter'];
                $TempArr            = array(); 
                $QuarterRabDataQuery = "SELECT * FROM abstractbook WHERE active = 1 AND sheetid = '$sheetid' AND ( (fromdate BETWEEN '$QuarterFromDate' AND '$QuarterToDate') OR (todate BETWEEN '$QuarterFromDate' AND '$QuarterToDate')  OR (fromdate <= '$QuarterFromDate' AND todate >= '$QuarterToDate'))"; 
                $QuarterRabDataQuery = mysql_query($QuarterRabDataQuery); 
                if($QuarterRabDataQuery == true){
                    while($QuarterRabDataValue  = mysql_fetch_object($QuarterRabDataQuery)){
                        $RbnFromDate = $QuarterRabDataValue->fromdate;
                        $RbnToDate = $QuarterRabDataValue->todate; 
                        $BreakUpDateFlagArr = array();
                        $BreakUpFromDate = $RbnFromDate;
                        $BreakUpToDate = $RbnToDate; 
                        if(($RbnFromDate >= $QuarterFromDate)&&($RbnToDate <= $QuarterToDate)){  
                            $RABNoBreakupArr[] = $QuarterRabDataValue->rbn; 
                        }else{ 
                            $RABBreakupArr[] = $QuarterRabDataValue->rbn; 
                            if($RbnFromDate < $QuarterFromDate){ 
                                // Breakup By From Date
                                $BreakUpDateFlagArr[] = "FROMDATE";
                                $BreakUpFromDate = $QuarterFromDate;
                            }
                            if($RbnToDate > $QuarterToDate){
                                // Breakup By To Date
                                $BreakUpDateFlagArr[] = "TODATE";
                                $BreakUpToDate = $QuarterToDate;
                            }
                        }
                        
                        $QuarterRabDataValue->quarter           = $QuarterData['quarter'];
                        $QuarterRabDataValue->quarter_from_date = $QuarterFromDate;
                        $QuarterRabDataValue->quarter_to_date   = $QuarterToDate;
                        $QuarterRabDataValue->breakup_from_date = $BreakUpFromDate;
                        $QuarterRabDataValue->breakup_to_date   = $BreakUpToDate;
                        $QuarterRabDataValue->breakup_flag      = $BreakUpDateFlagArr;
                        $RowSpan = 1;
                        $RabAmount = 0; 
                        if(count($BreakUpDateFlagArr) > 0){
                            // If breakup condition meets then call breakup calculation function
                            $BillBreakAmtWithItemData = RABillBreakupCalculation($QuarterRabDataValue);   
                            if(!empty($BillBreakAmtWithItemData)){
                                foreach( $BillBreakAmtWithItemData as $BillBreakAmtWithItemDataValue){
                                    if(isset($BillBreakAmtWithItemDataValue->per)){
                                        $UnitCode = $UnitCodeArr[$BillBreakAmtWithItemDataValue->per];
                                    }else{
                                        $UnitCode = null;
                                    }
                                    if($BillBreakAmtWithItemDataValue->measure_type == "S"){
                                        if($UnitCode == "MT"){ 
                                            $ItemUsedQty = round(($BillBreakAmtWithItemDataValue->used_total_quantity / 1000),$BillBreakAmtWithItemDataValue->decimal_placed);
                                        }else if($UnitCode == "QUINTAL"){
                                            $ItemUsedQty = round(($BillBreakAmtWithItemDataValue->used_total_quantity / 100),$BillBreakAmtWithItemDataValue->decimal_placed);
                                        }else{
                                            $ItemUsedQty = round($BillBreakAmtWithItemDataValue->used_total_quantity,$BillBreakAmtWithItemDataValue->decimal_placed);
                                        }
                                    }else{
                                        $ItemUsedQty = round($BillBreakAmtWithItemDataValue->used_total_quantity,$BillBreakAmtWithItemDataValue->decimal_placed);
                                    }
                                    $BillBreakAmtWithItemDataValue->used_total_quantity = $ItemUsedQty;
                                    $ItemRate = $BillBreakAmtWithItemDataValue->rate;
                                    /// Here we have to check the part rate
                                    $PartRateData = CheckPartRateForEscalation($sheetid ,$QuarterRabDataValue->rbn,$BillBreakAmtWithItemDataValue->s_itemid);
                                    //print_r($PartRateData);
                                    $PartRatePaidData = $PartRateData['PARTRATEPAY'];
                                    $PartRateRelData  = $PartRateData['PARTRATEREL'];
                                    if(!empty($PartRatePaidData)){ 
                                        $ItemUsedQtyTemp = $ItemUsedQty;
                                        $ItemAmount = 0;
                                        while($PartRatePaidDataValue = mysql_fetch_object($PartRatePaidData)){
                                            if($ItemUsedQtyTemp != 0){ 
                                                if($PartRatePaidDataValue->rbn == $PartRatePaidDataValue->exe_rbn){
                                                    $PartRatePaidMode = $PartRatePaidDataValue->part_pay_mode;
                                                    $PartRatePaidQty = $PartRatePaidDataValue->qty;
                                                    $PartRatePaidPerc = $PartRatePaidDataValue->percent;
                                                    $PartRatePaidAmt = $PartRatePaidDataValue->part_pay_amount;
                                                    if($ItemUsedQtyTemp == $PartRatePaidQty){
                                                        $CalcQty = $PartRatePaidQty;
                                                    }else if($PartRatePaidQty > $ItemUsedQtyTemp){
                                                        $CalcQty = $ItemUsedQtyTemp;
                                                    }else if($PartRatePaidQty < $ItemUsedQtyTemp){
                                                        $CalcQty = $PartRatePaidQty;
                                                    }else{
                                                        $CalcQty = 0;
                                                    }
                                                    $ItemUsedQtyTemp = $ItemUsedQtyTemp - $CalcQty;
                                                    if($PartRatePaidMode == "AMT"){
                                                        $ItemAmount = $ItemAmount + $PartRatePaidAmt;
                                                    }else{
                                                        $PartRatePaidRate = round(($ItemRate * $PartRatePaidPerc / 100),2);
                                                        $PartPaidAmtWithPartRate = round(($CalcQty * $PartRatePaidRate),2);
                                                        $ItemAmount = $ItemAmount + $PartPaidAmtWithPartRate;
                                                    }
                                                }
                                            } 
                                           
                                        }
                                    }else{
                                        $ItemAmount = round(($ItemUsedQty * $ItemRate),2);
                                    }
                                    $BillBreakAmtWithItemDataValue->item_amt_for_esc = $ItemAmount;
                                    
                                    $RabAmount = $RabAmount + $ItemAmount;
                                }
                            }
                            $QuarterRabDataValue->total_rab_amount = $RabAmount;
                            $RowSpan = count($BillBreakAmtWithItemData);
                        }else{
                            $BillBreakAmtWithItemData = NULL; 
                            $QuarterRabDataValue->total_rab_amount  = $QuarterRabDataValue->slm_total_amount;//+$QuarterRabDataValue->secured_adv_amt;
                        }
                        if(isset($RowSpanQtrArr["QTR-".$QuarterData['quarter']])){
                            $RowSpanQtrArr["QTR-".$QuarterData['quarter']] = $RowSpanQtrArr["QTR-".$QuarterData['quarter']] + $RowSpan;
                        }else{
                            $RowSpanQtrArr["QTR-".$QuarterData['quarter']] = $RowSpan;
                        }
                        if(isset($RowSpanRabArr["QTR-".$QuarterData['quarter']]["RAB-".$QuarterRabDataValue->rbn])){
                            $RowSpanRabArr["QTR-".$QuarterData['quarter']]["RAB-".$QuarterRabDataValue->rbn] = $RowSpanRabArr["QTR-".$QuarterData['quarter']]["RAB-".$QuarterRabDataValue->rbn] + $RowSpan;
                        }else{
                            $RowSpanRabArr["QTR-".$QuarterData['quarter']]["RAB-".$QuarterRabDataValue->rbn] = $RowSpan;
                        }
                        
                        $QuarterRabDataValue->breakup_item_amt_data = $BillBreakAmtWithItemData;
                        $RABDataArr[$QuarterRabDataValue->rbn]  = $QuarterRabDataValue; 
                        $QuarterRabDataArr[$Quarter][$QuarterRabDataValue->rbn] = $QuarterRabDataValue; 
                        $RabQuarterMapArr[$QuarterRabDataValue->rbn][] = $Quarter;
                    }
                }
                //$QuarterRabArr[$Quarter] = $TempArr;
            }
        }
    }
    // if($Page == "CALC"){ 
    //     $EscManualBillData = $this->esc_manual_bill_split->ShowEscManualBillSplit(NULL,$SheetId,$Rbn,NULL,NULL);
    //     $WorkOrderData = $this->workorder->ShowSheet(NULL,$SheetId);
    //     return view('escalation.EscalationQtrWiseRabBreakUp')->with('data',compact('WorkOrderData','EscManualBillData','RABDataArr','QuarterRabDataArr','RabQuarterMapArr','RABBreakupArr','RABNoBreakupArr','EscBillData','RowSpanQtrArr','RowSpanRabArr','SheetId','Rbn'));
    // }
}
$Sno = 1; 
$PrevQtr = "";
$QtrTotalAmount = 0; $QtrTotalAmountWithRebate = 0;
?>

<?php require_once "Header.html"; ?>
<script>
   
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
    <?php include "Menu.php"; ?>
    <!--==============================Content=================================-->
    <div class="content">
        <div class="title">Quarter Wise RA Bill Break Up for Esacalation</div>
       	<div class="container_12">
           	<div class="grid_12">
			<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                 <blockquote class="bq1" style="overflow:auto">
					<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
					<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
                    <table class="DTable" align="center" id="dataTable" width="100%">
							<thead>
								<tr>
									<th nowrap="nowrap">SNo.</th>
									<th>Quarter</th>
									<th>RA Bill No.</th>
									<th nowrap="nowrap">Date of Measurements</th>
									<th nowrap="nowrap">Item No.</th>
									<th>Quantity</th>
									<th>Unit</th>
									<th>Rate (&#8377;)</th>
									<th>Amount (&#8377;)</th>
									<th>Total (&#8377;)</th>
									<th>Rebate <?php if($RebatePerc != ''){ echo '('.$RebatePerc.')'; } ?></th>
									<th>Total After Rebate (&#8377;)</th>
								</tr>
							</thead>
							<tbody>
                                <?php 
                                if (!empty($QuarterRabDataArr) && count($QuarterRabDataArr) > 0) {
                                
                                    foreach ($QuarterRabDataArr as $QuarterRabDataKey => $QuarterRabDataValue) { 
                                        if (count($QuarterRabDataValue) > 0) {
                                        
                                            $RowSpanQtr = $RowSpanQtrArr["QTR-" . $QuarterRabDataKey];
                                            $QtrX = 0;
                                        
                                            if (($PrevQtr != $QuarterRabDataKey) && ($PrevQtr != "")) {
                                                ?>
                                                <tr id="<?php echo $PrevQtr; ?>QtrTotRow">
                                                    <th colspan="6" style="text-align:right">
                                                        <button type="button" class="btn-ppay ppaysuccess ManualSplit" data-qtr="<?php echo $PrevQtr; ?>">
                                                            <i class="fa fa-inr"></i> Click here for Manual Bill Split for Quarter - <?php echo $PrevQtr; ?>
                                                        </button>
                                                        <button type="button" class="btn-ppay ppaydanger DelManualSplit" data-qtr="<?php echo $PrevQtr; ?>">
                                                            <i class="fa fa-times-circle"></i> Click here to Delete Manual Bill Split for Quarter - <?php echo $PrevQtr; ?>
                                                        </button>
                                                    </th>
                                                    <th colspan="3" style="text-align:right">Total Amount for Quarter - <?php echo $PrevQtr; ?></th>
                                                    <th style="text-align:right"></th>
                                                    <th style="text-align:right"></th>
                                                    <th style="text-align:right"><?php echo $QtrTotalAmountWithRebate; ?></th>
                                                </tr>
                                                <?php
                                                $QtrTotalAmount = 0;
                                                $QtrTotalAmountWithRebate = 0;
                                            }
                                           
                                            foreach ($QuarterRabDataValue as $QuarterDataKey => $QuarterDataValue) { 
                                                
                                                $RowSpanRab = $RowSpanRabArr["QTR-" . $QuarterRabDataKey]["RAB-" . $QuarterDataKey];
                                                $QtrRabX = 0;
                                                $BreakupFlagArr = !empty($QuarterDataValue->breakup_flag) ? $QuarterDataValue->breakup_flag : array();
                                                $BreakupItemAmtArr = !empty($QuarterDataValue->breakup_item_amt_data) ? $QuarterDataValue->breakup_item_amt_data     : array();
                                            
                                                $BreakupFlagCnt = count($BreakupFlagArr);
                                            
                                                if ($BreakupFlagCnt == 0) { 
                                                    $MeasurementPeriod = dt_display($QuarterDataValue->fromdate) . " - " . dt_display($QuarterDataValue->todate);
                                                    $RemarkStr = "Entire RA Bill Amount";
                                                    $BreakupFDate = $QuarterDataValue->fromdate;
                                                    $BreakupTDate = $QuarterDataValue->todate;
                                                } else {
                                                    $MeasurementPeriod = dt_display($QuarterDataValue->breakup_from_date) . " - " . dt_display($QuarterDataValue->breakup_to_date);
                                                    $RemarkStr = "";
                                                    $BreakupFDate = $QuarterDataValue->breakup_from_date;
                                                    $BreakupTDate = $QuarterDataValue->breakup_to_date;
                                                }
                                            
                                                $BreakupMode = (count($BreakupItemAmtArr) > 0) ? "BREAK" : "ENTIRE";
                                                $TotalAmount = 0;
                                                $ItemRowTemp = 0;
                                                $QtrTotalAmount += $QuarterDataValue->total_rab_amount;
                                            
                                                if ($BreakupFlagCnt == 0) {
                                                    $RebateAmt = 0;
                                                    $TotalRabAmtWithRebate = $QuarterDataValue->total_rab_amount - $RebateAmt;
                                                } else { 
                                                    $RebateAmt = round(($QuarterDataValue->total_rab_amount * $RebatePerc / 100), 2);
                                                    $TotalRabAmtWithRebate = $QuarterDataValue->total_rab_amount - $RebateAmt;
                                                }
                                            
                                                $QtrTotalAmountWithRebate += $TotalRabAmtWithRebate; 
                                            
                                                if ($BreakupFlagCnt > 0 && !empty($BreakupItemAmtArr)) {
                                                    foreach ($BreakupItemAmtArr as $BreakupItemAmtKey => $BreakupItemAmtValue) { 
                                                    
                                                        $ItemUsedQty = round($BreakupItemAmtValue->used_total_quantity, $BreakupItemAmtValue->decimal_placed);
                                                        $ItemRate = $BreakupItemAmtValue->rate;
                                                        $ItemAmount = $BreakupItemAmtValue->item_amt_for_esc;
                                                        $TotalAmount += $ItemAmount;
                                                        $ItemRowTemp++;
                                                        ?>
                                                        <tr>
                                                            <?php if ($QtrX == 0) { ?>
                                                                <td align="center" rowspan="<?php echo $RowSpanQtr; ?>" nowrap="nowrap" valign="middle"><?php echo $Sno; ?></td>
                                                                <td align="center" rowspan="<?php echo $RowSpanQtr; ?>" valign="middle">Quarter - <?php echo $QuarterRabDataKey; ?></td>
                                                                <?php $Sno++; } ?>
                                                            <?php if ($QtrRabX == 0) { ?>
                                                                <td align="center" rowspan="<?php echo $RowSpanRab; ?>" valign="middle">RA Bill - <?php echo $QuarterDataKey; ?></td>
                                                            <?php } ?>
                                                            <td align="center" nowrap="nowrap"><?php echo $MeasurementPeriod; ?></td>
                                                            <td align="center" nowrap="nowrap"><?php echo $BreakupItemAmtValue->sno; ?></td>
                                                            <td align="right"><?php echo $ItemUsedQty; ?></td>
                                                            <td align="center"><?php echo $Units[$BreakupItemAmtValue->per]; ?></td>
                                                            <td align="right"><?php echo $ItemRate; ?></td>
                                                            <td align="right"><?php echo $ItemAmount; ?></td>
                                                            <?php if ($QtrRabX == 0) { ?>
                                                                <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $QuarterDataValue->total_rab_amount; ?></td>
                                                                <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $RebateAmt; ?></td>
                                                                <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $TotalRabAmtWithRebate; ?></td>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php
                                                        $QtrX++;
                                                        $QtrRabX++;
                                                    }
                                                } else {
                                                    $ItemRowTemp++;
                                                    ?>
                                                    <tr>
                                                        <?php if ($QtrX == 0) { ?>
                                                            <td align="center" rowspan="<?php echo $RowSpanQtr; ?>" nowrap="nowrap"><?php echo $Sno; ?></td>
                                                            <td align="center" rowspan="<?php echo $RowSpanQtr; ?>" valign="middle">Quarter - <?php echo $QuarterRabDataKey; ?></td>
                                                            <?php $Sno++; } ?>
                                                        <?php if ($QtrRabX == 0) { ?>
                                                            <td align="center" rowspan="<?php echo $RowSpanRab; ?>" valign="middle">RA Bill - <?php echo $QuarterDataKey; ?></td>
                                                        <?php } ?>
                                                        <td align="center" nowrap="nowrap"><?php echo $MeasurementPeriod; ?></td>
                                                        <td align="center" nowrap="nowrap"><?php echo $RemarkStr; ?></td>
                                                        <td></td><td></td><td></td><td></td>
                                                        <?php if ($QtrRabX == 0) { ?>
                                                            <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $QuarterDataValue->total_rab_amount; ?></td>
                                                            <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $RebateAmt; ?></td>
                                                            <td align="right" rowspan="<?php echo $RowSpanRab; ?>" valign="middle"><?php echo $TotalRabAmtWithRebate; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                    $QtrX++;
                                                    $QtrRabX++;
                                                }
                                            
                                                // Hidden breakup data
                                                $BreakUpFlagStr = implode(",", $BreakupFlagArr);
                                                $TemDataArr = array(
                                                    'QTR' => $QuarterRabDataKey,
                                                    'RBN' => $QuarterDataKey,
                                                    'FDATE' => $BreakupFDate,
                                                    'TDATE' => $BreakupTDate,
                                                    'AMT' => $QuarterDataValue->total_rab_amount,
                                                    'MODE' => $BreakupMode,
                                                    'MBNO' => null,
                                                    'MBPAGE' => null,
                                                    'BREAKUPPOINT' => $BreakUpFlagStr
                                                );
                                                if (!empty($BreakupItemAmtArr)) {
                                                    $TemDataArr['BRKUPITEMARR'] = $BreakupItemAmtArr;
                                                }
                                            
                                                $BreakUpData = json_encode($TemDataArr);
                                                ?>
                                                <input type="hidden" class="BreakUpData" name="txt_breakup_data[]" value="<?php echo base64_encode($BreakUpData); ?>">
                                                <?php
                                                $PrevQtr = $QuarterRabDataKey;
                                                $CurrQuarter = $QuarterRabDataKey;
                                            }
                                        }
                                    }
                                
                                    if (!empty($PrevQtr)) { ?>
                                        <tr id="<?php echo $PrevQtr; ?>QtrTotRow">
                                            <th colspan="6" style="text-align:right">
                                                <button type="button" class="btn-ppay ppaysuccess ManualSplit" data-qtr="<?php echo $PrevQtr; ?>">
                                                    <i class="fa fa-inr"></i> Click here for Manual Bill Split for Quarter - <?php echo $PrevQtr; ?>
                                                </button>
                                                <button type="button" class="btn-ppay ppaydanger DelManualSplit" data-qtr="<?php echo $PrevQtr; ?>">
                                                    <i class="fa fa-times-circle"></i> Click here to Delete Manual Bill Split for Quarter - <?php echo $PrevQtr; ?>
                                                </button>
                                            </th>
                                            <th colspan="3" style="text-align:right">Total Amount for Quarter - <?php echo $PrevQtr; ?></th>
                                            <th style="text-align:right"></th>
                                            <th style="text-align:right"></th>
                                            <th style="text-align:right"><?php echo $QtrTotalAmountWithRebate; ?></th>
                                        </tr>
                                    <?php 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
						</div>
						<div class="buttonsection">
							<input type="submit" name="submit" id="submit" value=" View "/>
						</div>
					</div>
                  </blockquote>
              </div>
        </div>
	</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   		$("#cmb_shortname").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
