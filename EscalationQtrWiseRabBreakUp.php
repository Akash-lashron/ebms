<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid         = $_SESSION['userid'];
$staffid        = $_SESSION['sid'];
$sheetid  		= $_SESSION['escal_sheetid'];
$cc_esc_rbn 	= $_SESSION['escal_rbn'];
$Page 	        = $_SESSION['page'];
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
if($_POST["submit"] == ' save ') 
{    
    $SheetId = $_POST["hid_sheetid"];
    $EscRbn = $_POST["hid_esc_rbn"];
    $DeleteQuery = "DELETE FROM esc_qtr_bill_breakup WHERE sheetid = '$SheetId' AND rbn = '$EscRbn'";
    $DeleteQuery = mysql_query($DeleteQuery); 
    $BreakUpValue = $_POST['txt_breakup_data']; 
    foreach($BreakUpValue as $BreakUpDataValue){ 
        $BrUpData = explode('*',$BreakUpDataValue);   
        // if(isset($BrUpData['BRKUPITEMARR'])){
        //     $BreakUpItemArr = $BrUpData['BRKUPITEMARR'];
        // }else{
        //     $BreakUpItemArr = array();
        // }
        $BreakUpQtr     = $BrUpData[0];
        $BreakUpRbn     = $BrUpData[1];
        $BreakUpFDate   = $BrUpData[2];
        $BreakUpTDate   = $BrUpData[3];
        $BreakUpAmount  = $BrUpData[4];
        $BreakUpMode    = $BrUpData[5];
        $BreakUpMBNo    = $BrUpData[6];
        $BreakUpMBPage  = $BrUpData[7];
        $BreakUpPoint   = $BrUpData[8]; 
        $InsertQuery = "INSERT INTO esc_qtr_bill_breakup (sheetid,rbn,quarter,breakup_rbn,breakup_period_fromdate,breakup_period_todate,breakup_rbn_amt,is_breakup_or_entire_bill,breakup_mbook_no,breakup_mbook_page,active,created_at,userid,staffid,breakup_point) VALUES
        ('$SheetId','$EscRbn','$BreakUpQtr','$BreakUpRbn','$BreakUpFDate','$BreakUpTDate','$BreakUpAmount','$BreakUpMode','$BreakUpMBNo','$BreakUpMBPage',1,NOW(),'$userid','$staffid','$BreakUpPoint')";
        $InsertQuery = mysql_query($InsertQuery); 

    }
    if($InsertQuery == true){
        $msg = "Quarter Wise RA Bill Breakup Saved Successfully."; 
    }
}
$ReturnData = include('EscQtrWiseRabBreakUpCheck.php'); 
$WorkOrderData = $ReturnData['WorkOrderData'];
$RABDataArr = $ReturnData['RABDataArr'];
$QuarterRabDataArr = $ReturnData['QuarterRabDataArr'];
$RabQuarterMapArr = $ReturnData['RabQuarterMapArr'];
$RABBreakupArr = $ReturnData['RABBreakupArr'];
$RABNoBreakupArr = $ReturnData['RABNoBreakupArr'];
$EscBillData = $ReturnData['EscBillData'];
$RowSpanQtrArr = $ReturnData['RowSpanQtrArr'];
$RowSpanRabArr = $ReturnData['RowSpanRabArr'];
$RebatePerc    = $WorkOrderData->rebate_percent; 
$Sno = 1; 
$PrevQtr = "";
$QtrTotalAmount = 0; $QtrTotalAmountWithRebate = 0;
?>

<?php require_once "Header.html"; ?>
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
                                                    <th colspan="6" style="text-align:right"></th>
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
                                                            <td align="right"><?php echo number_format($ItemRate, 2, '.', ''); ?></td>
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
                                                            <td align="center" rowspan="<?php echo $RowSpanQtr; ?>" nowrap="nowrap" valign="middle"><?php echo $Sno; ?></td>
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
                                                $TemDataArr = $QuarterRabDataKey.'*'.$QuarterDataKey.'*'.$BreakupFDate.'*'.$BreakupTDate.'*'.$QuarterDataValue->total_rab_amount.'*'.$BreakupMode.'* * *'.$BreakUpFlagStr; 
                                                // if (!empty($BreakupItemAmtArr)) {
                                                //     $TemDataArr['BRKUPITEMARR'] = $BreakupItemAmtArr;
                                                // }
                                            
                                                $BreakUpData = $TemDataArr;
                                                ?>
                                                <input type="hidden" class="BreakUpData" name="txt_breakup_data[]" value="<?php echo $BreakUpData; ?>">
                                                <?php
                                                $PrevQtr = $QuarterRabDataKey;
                                                $CurrQuarter = $QuarterRabDataKey;
                                            }
                                        }
                                    }
                                
                                    if (!empty($PrevQtr)) { ?>
                                        <tr id="<?php echo $PrevQtr; ?>QtrTotRow">
                                            <th colspan="6" style="text-align:right"></th>
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
                        <input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($sheetid != ''){ echo $sheetid; } ?>">
					    <input type="hidden" name="hid_esc_rbn" id="hid_esc_rbn" value="<?php if($cc_esc_rbn != ''){ echo $cc_esc_rbn; } ?>">
						<div class="buttonsection">
							<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
						</div>
						<div class="buttonsection">
							<input type="submit" name="submit" id="submit" value=" save "/>
						</div>
					</div>
                  </blockquote>
              </div>
        </div>
	</div>
</form>
        <!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
<script>
   var msg = "<?php echo $msg; ?>";
   if(msg != ""){
		BootstrapDialog.show({
			title: 'Information',
			closable: false,
			message: msg,
			buttons: [{
				label: ' OK ',
				cssClass: 'btn-primary',
				action: function(dialogRef) {
					dialogRef.close();
                    url = "EscalationQtrWiseRabBreakUpGenerate.php";
	                window.location.replace(url);
				}
			}]
		});
	}
     var KillEvent = 0;
    $('body').on("click","#submit", function(event){
	    if(KillEvent == 0){          
		    event.preventDefault();
		    BootstrapDialog.confirm('Are you sure want to Save ?', function(result){
		    	if(result) {
		    		KillEvent = 1;
		    		$("#submit").trigger( "click" );
		    	}
		    });
        }
    });
   function goBack()
	{
		url = "EscalationQtrWiseRabBreakUpGenerate.php";
		window.location.replace(url);
	}
</script>
</html>
