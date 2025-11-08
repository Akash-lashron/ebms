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
function GroupBy($Data, $Key) {
    $result = array();
    while($row = mysql_fetch_assoc($Data)) {
        $GrpKey = $row[$Key];
        $result[$GrpKey][] = $row; 
    }
    return $result;
}
function KeyBy($Data, $Key) {
    $Result = array();
    while($row = mysql_fetch_assoc($Data)) {
        $NewKey = $row[$Key];
        $Result[$NewKey] = $row;
    }
    return $Result;
}
function ArrayToString($Data) {
    $Str = '';
    if (!empty($Data)) {
        $Str = "'" . implode("','", array_map('mysql_real_escape_string', $Data)) . "'";
    }
    return $Str;

}
$sheetid  		= $_SESSION['escal_sheetid'];
$cc_esc_rbn 	= $_SESSION['escal_rbn'];
$page 	        = $_SESSION['page'];
if($sheetid != ""){
    $EscBill     = "SELECT * FROM escalation WHERE active = 1 AND sheetid = '$sheetid' AND rbn = '$cc_esc_rbn' ORDER BY quarter ASC";
    $EscBillData = mysql_query($EscBill);
    $EscBillBreakUp = "SELECT * FROM esc_qtr_bill_breakup WHERE active = 1 AND sheetid = '$sheetid' AND rbn = '$cc_esc_rbn' ORDER BY quarter ASC";
    $EscBreakUpData = mysql_query($EscBillBreakUp);
    $SecAdvGrpData = $SecAdvDtGrpData = $WaterRecGrpData = $ElectRecGrpData = $AbstRbnGrpData = $FullAssessSecAdvData = NULL;
    if (mysql_num_rows($EscBillData) > 0) {
        $QtrGrpEscBillData = GroupBy($EscBillData, 'quarter');
    } else {
        $QtrGrpEscBillData = NULL;
    }
    if (mysql_num_rows($EscBreakUpData) > 0) {
        $EscBreakUpGrpData = GroupBy($EscBreakUpData, 'quarter');
        $BreakUpRbnArr = array();
        $EscBreakUpData = mysql_query($EscBillBreakUp); 
        while($row = mysql_fetch_assoc($EscBreakUpData)) {
            $BreakUpRbnArr[] = $row['breakup_rbn'];  
        }
        $AbstQuery = "SELECT * FROM abstractbook WHERE active = 1 AND sheetid = '$sheetid' AND rbn = '$cc_esc_rbn'";
        $AbstData = mysql_query($AbstQuery);
        $AbstRbnData = mysql_fetch_object($AbstData);
        $IsFinalBill = "N"; 
        if (!empty($AbstRbnData)) {
            $IsFinalBill = $AbstRbnData->is_final_bill; 
        }

        if (!empty($BreakUpRbnArr)) {
            $BreakUpRbnStr = ArrayToString($BreakUpRbnArr);
            $AbstAllRbnQuery = "SELECT * FROM abstractbook WHERE active = 1 AND sheetid = '$sheetid' AND rbn IN ($BreakUpRbnStr)";
            $AbstAllRbnData = mysql_query($AbstAllRbnQuery); 
            if($AbstAllRbnData != '' && mysql_num_rows($AbstAllRbnData) > 0) {
                $AbstRbnGrpData = KeyBy($AbstAllRbnData, 'rbn');
            }
            
            $TenCACoveredItemQuery = "SELECT * FROM schdule WHERE active = 1 AND sheetid = '$sheetid' AND is_cover_under_tca = 'Y'"; // is_cover_under_tca field is not in schdule table
            $TenCACoveredItemData = mysql_query($TenCACoveredItemQuery);
            $IsFullAssessedSecAdv = 'Y';  
            if ($TenCACoveredItemData != '' && mysql_num_rows($TenCACoveredItemData) > 0) {
                while($row = mysql_fetch_assoc($TenCACoveredItemData)) {
                    $TenCACoveredItemList[] = $row['breakup_rbn'];
                }
                $TenCACoveredItemListStr = ArrayToString($TenCACoveredItemList);
                $TenCACoveredSAItemQuery = "SELECT * FROM secured_advance_dt WHERE active = 1 AND sheetid = '$sheetid' AND rbn IN ($BreakUpRbnStr) AND subdivid IN ($TenCACoveredItemListStr)";
                $TenCACoveredSAItemData = mysql_query($TenCACoveredSAItemQuery);
                if (mysql_num_rows($TenCACoveredSAItemData) == 0) {
                    $IsFullAssessedSecAdv = 'N';
                }
            } else {
                $TenCACoveredItemList = array();
            }
            
            if ($IsFullAssessedSecAdv == 'Y') {
                // $SecAdvData = ShowSecuredAdvanceByRbnArr($conn, $SheetId, $BreakUpRbnArr);
                $SecAdvQuery = "SELECT * FROM secured_advance WHERE active = 1 AND sheetid = '$sheetid' AND rbn IN ($BreakUpRbnStr) ORDER BY said ASC";
                $SecAdvData = mysql_query($SecAdvQuery);  
                if (!empty($SecAdvData)) {
                    $SecAdvGrpData = GroupBy($SecAdvData, 'rbn');
                }
                
                $SecAdvDtQuery = "SELECT * FROM secured_advance_dt WHERE active = 1 AND sheetid = '$sheetid' ORDER BY sadtid ASC";
                $SecAdvDtData = mysql_query($SecAdvDtQuery);
                if (!empty($SecAdvDtData)) {
                    $SecAdvDtGrpData = GroupBy($SecAdvDtData, 'rbn');
                    $ParamArr = array(
                        'EscBillData' => $EscBillData,
                        'EscBreakUpData' => $EscBreakUpData,    
                        'SecAdvDtData' => $SecAdvDtData,
                        'EscSecAdvGrpData' => $SecAdvDtGrpData,
                        'EscTenCaCoveredItem' => $TenCACoveredItemList,
                        'EscBreakupRbnAbstData' => $AbstRbnGrpData
                    );
                    
                    if(!empty($ParamArr)){
	                                           
                    }
                    $FullAssessSecAdvData = FullAssesSecAdvForEscalationWithPaidRecovered();
                }
            }
            // Check if current bill lies within any quarter
            $CurrBillWithInQtr = 'N';
            if (!empty($QtrGrpEscBillData) && !empty($AbstAllRbnData)) {
                $ThisBillFromDt = $AbstAllRbnData[0]['fromdate'];
                $ThisBillToDt = $AbstAllRbnData[0]['todate'];

                foreach ($QtrGrpEscBillData as $Qtr => $QtrData) {
                    $QtrFromDate = $QtrData[0]['quarter_from_date'];
                    $QtrToDate = $QtrData[0]['quarter_to_date'];
                    if (($ThisBillFromDt >= $QtrFromDate) && ($ThisBillFromDt <= $QtrToDate)) {
                        $CurrBillWithInQtr = 'Y';
                        break;
                    }
                }
            }

            $FBillWcCost = 0;
            $FBillEcCost = 0;
            // $RbnArrForFBillRecCalc = [$Rbn];

            if ($IsFinalBill == "Y" || $CurrBillWithInQtr == "Y") {
                $FBillWaterRecData = ShowWaterBillByRbnArr($conn, $SheetId, $RbnArrForFBillRecCalc);
                if (!empty($FBillWaterRecData)) {
                    $FBillWcCost = array_sum(array_column($FBillWaterRecData, 'water_cost'));
                }

                $FBillElectRecData = ShowElectricityBillByRbnArr($conn, $SheetId, $RbnArrForFBillRecCalc);
                if (!empty($FBillElectRecData)) {
                    $FBillEcCost = array_sum(array_column($FBillElectRecData, 'water_cost'));
                }
            }

            $WaterRecData = ShowMopRecByRecCode($conn, $SheetId, 'WC');
            if (!empty($WaterRecData)) {
                $WaterRecGrpData = groupBy($WaterRecData, 'rbn');
            }

            $ElectRecData = ShowMopRecByRecCode($conn, $SheetId, 'EC');
            if (!empty($ElectRecData)) {
                $ElectRecGrpData = groupBy($ElectRecData, 'rbn');
            }
        }
    } else {
        $EscBreakUpGrpData = NULL;
    }

    $WorkDoneMastDtData = ShowWorkDoneDetailsWithMaster($conn, $SheetId, $Rbn);
    $CheckAdditionQtyExtraItemData = CheckAdditionQtyExtraItem($conn, $SheetId, $Rbn, $EscBreakUpData, $EscManualBillData);
    $AddnlExtraDeptIssueData = ShowAddnlExtraDeptIssueItemDt($conn, NULL, $SheetId, $Rbn, NULL, NULL);
    $WorkOrderData = ShowSheet($conn, NULL, $SheetId);

}
function FullAssesSecAdvForEscalationWithPaidRecovered() {

    $SchduleArr = array();
    $SchduleQuery = "SELECT * FROM schdule WHERE sheetid = '$SheetId' AND subdiv_id != 0 AND subdiv_id IS NOT NULL";
    $res = mysql_query($SchduleQuery);
    while ($row = mysql_fetch_assoc($res)) {
        $SchduleArr[$r['subdiv_id']] = $row;
    }

    $EscQtrBreakupData = include('EscQtrWiseRabBreakUpCheck.php');

    if (!empty($EscBillData)) {
        $EscQtrToDateArr = array();
        foreach ($QtrGrpEscBillData as $EscBillData) {
            $EscQtrToDateArr[] = $EscBillData['quarter_to_date'];
        }
        $EscBillGrpData = array();
        $EscBillGrpData = KeyBy($EscBillData, 'quarter');
        $QtrMaxToDate   = max($EscQtrToDateArr);
    } else {
        $EscBillGrpData = array();
        $QtrMaxToDate = null;
    }

    $QuarterRabDataArr = isset($EscQtrBreakupData['QuarterRabDataArr']) ? $EscQtrBreakupData['QuarterRabDataArr'] : array();
    $RabQuarterMapData = isset($EscQtrBreakupData['RabQuarterMapArr']) ? $EscQtrBreakupData['RabQuarterMapArr'] : array();

    $MasterSecAdvArr = array();
    if (!empty($SecAdvDtData)) {
        foreach ($SecAdvDtData as $row) {
            if (!empty($row['add_qty_this_bill']) && $row['add_qty_this_bill'] != 0) {
                $id = $row['subdiv_id'];
                $qty = $row['add_qty_this_bill'];
                if (!isset($MasterSecAdvArr[$id])) {
                    $MasterSecAdvArr[$id] = array('RECEIVED' => 0, 'BALANCE' => 0);
                }
                $MasterSecAdvArr[$id]['RECEIVED'] += $qty;
                $MasterSecAdvArr[$id]['BALANCE'] += $qty;
            }
        }
    }

    $FullAssesdedSecAdvFinalArr = array();
    if (!empty($QuarterRabDataArr)) {
        foreach ($QuarterRabDataArr as $Quarter => $QuarterRabDataArrValue) {

            $EscQuarterData = isset($EscBillGrpData[$Quarter]) ? $EscBillGrpData[$Quarter] : null;

            foreach ($QuarterRabDataArrValue as $QuarterRbn => $QuarterRabDataValue) {

                $MeasFromDate = $QuarterRabDataValue['breakup_from_date'];
                $MeasToDate   = $QuarterRabDataValue['breakup_to_date'];

                $RbnLastQuarter = null;
                if (!empty($RabQuarterMapData[$QuarterRbn])) {
                    $map = $RabQuarterMapData[$QuarterRbn];
                    $RbnLastQuarter = end($map);
                }

                if (!empty($EscBreakupRbnAbstData[$QuarterRbn])) {
                    $RbnData = $EscBreakupRbnAbstData[$QuarterRbn];
                    $RbnFromDate = isset($RbnData['fromdate']) ? $RbnData['fromdate'] : null;
                    $RbnToDate   = isset($RbnData['todate']) ? $RbnData['todate'] : null;

                    if ($RbnFromDate && $RbnToDate && $RbnToDate <= $QtrMaxToDate) {

                        $BreakupQtrRbnData = array();
                        foreach ($EscBreakUpData as $item) {
                            if ($item['quarter'] == $Quarter && $item['breakup_rbn'] == $QuarterRbn) {
                                $BreakupQtrRbnData[] = $item;
                            }
                        }

                        $BreakupOrEntire = '';
                        $BreakupPointFlag = '';
                        $BreakupRbnAmt = 0;
                        foreach ($BreakupQtrRbnData as $item) {
                            $BreakupOrEntire = $item['is_breakup_or_entire_bill'];
                            $BreakupPointFlag = $item['breakup_point'];
                            $BreakupRbnAmt = $item['breakup_rbn_amt'];
                            break;
                        }

                        if (!empty($SecAdvDtGrpData[$QuarterRbn])) {
                            $SecAdvRbnData = $SecAdvDtGrpData[$QuarterRbn];

                            $FullAssessedSecAdvAmt = 0;
                            $FullAssessedSecAdvPayAmt = 0;
                            $FullAssessedSecAdvRecAmt = 0;
                            $TempArr1 = array();

                            foreach ($SecAdvRbnData as $SecAdvRbnDataValue) {
                                $RbnFullAssessedItemId = $SecAdvRbnDataValue['s_itemid'];
                                $RbnFullAssessedItemDesc = $SecAdvRbnDataValue['description'];
                                $RbnFullAssessedItemQty = isset($SecAdvRbnDataValue['ots_qty_since_bill']) ? $SecAdvRbnDataValue['ots_qty_since_bill'] : 0;
                                $RbnFullAssessedItemQtyPrevOts = isset($SecAdvRbnDataValue['ots_qty_prev_bill']) ? $SecAdvRbnDataValue['ots_qty_prev_bill'] : 0;
                                $RbnFullAssessedItemQtyBrought = isset($SecAdvRbnDataValue['add_qty_this_bill']) ? $SecAdvRbnDataValue['add_qty_this_bill'] : 0;
                                $UtilizedQty = isset($SecAdvRbnDataValue['utz_qty_this_bill']) ? $SecAdvRbnDataValue['utz_qty_this_bill'] : 0;
                                $Wastage = isset($SecAdvRbnDataValue['wastage']) ? $SecAdvRbnDataValue['wastage'] : 0;

                                $ItemDecimal = 3;
                                $ItemNo = "";
                                if (!empty($Soq[$RbnFullAssessedItemId])) {
                                    $ItemDecimal = !empty($Soq[$RbnFullAssessedItemId]['decimal_placed']) ? $Soq[$RbnFullAssessedItemId]['decimal_placed'] : 3;
                                    $ItemNo = $Soq[$RbnFullAssessedItemId]['s_itemno'];
                                }

                                $RbnFullAssessedItemQtyUsed = 0;
                                $RbnFullAssessedItemQtyWastage = 0;

                                $EscManualSplitUpItemQty = 0;
                                foreach ($EscManualBillData as $i) {
                                    if ($i['s_itemid'] == $RbnFullAssessedItemId && $i['quarter'] == $Quarter && $i['split_rbn'] == $QuarterRbn) {
                                        $EscManualSplitUpItemQty += $i['qty'];
                                    }
                                }

                                if (!empty($EscManualSplitUpItemQty)) {
                                    $RbnFullAssessedItemQtyUsed = $EscManualSplitUpItemQty;
                                    $WastageForSplitQty = ($UtilizedQty != 0) ? round(($Wastage * $EscManualSplitUpItemQty / $UtilizedQty), $ItemDecimal) : 0;
                                    $RbnFullAssessedItemQtyWastage = $WastageForSplitQty;
                                } else {
                                    $RbnFullAssessedItemQtyUsed = $UtilizedQty;
                                    $RbnFullAssessedItemQtyWastage = $Wastage;
                                }

                                $RbnFullAssessedItemPayQty = $RbnFullAssessedItemQtyBrought;
                                $RbnFullAssessedItemRecQty = $RbnFullAssessedItemQtyUsed + $RbnFullAssessedItemQtyWastage;

                                if (isset($MasterSecAdvArr[$RbnFullAssessedItemId])) {
                                    $BalanceQty = $MasterSecAdvArr[$RbnFullAssessedItemId]['BALANCE'];
                                    if ($BalanceQty < $RbnFullAssessedItemRecQty) {
                                        $RbnFullAssessedItemRecQty = ($BalanceQty <= 0) ? 0 : $BalanceQty;
                                    }
                                    $MasterSecAdvArr[$RbnFullAssessedItemId]['BALANCE'] -= $RbnFullAssessedItemRecQty;
                                }

                                $RbnFullAssessedItemRate = isset($SecAdvRbnDataValue['full_asses_rate']) ? $SecAdvRbnDataValue['full_asses_rate'] : 0;
                                $RbnFullAssessedItemAmt = round(($RbnFullAssessedItemQty * $RbnFullAssessedItemRate), 2);
                                $RbnFullAssessedItemPayAmt = round(($RbnFullAssessedItemPayQty * $RbnFullAssessedItemRate), 2);
                                $RbnFullAssessedItemRecAmt = round(($RbnFullAssessedItemRecQty * $RbnFullAssessedItemRate), 2);

                                $FullAssessedSecAdvAmt += $RbnFullAssessedItemAmt;
                                if (!empty($RbnLastQuarter) && $RbnLastQuarter == $Quarter) {
                                    $FullAssessedSecAdvPayAmt += $RbnFullAssessedItemPayAmt;
                                }
                                $FullAssessedSecAdvRecAmt += $RbnFullAssessedItemRecAmt;

                                $TempArr2 = array(
                                    'ITEMID' => $RbnFullAssessedItemId,
                                    'ITEMNO' => $ItemNo,
                                    'ITEMDESC' => $RbnFullAssessedItemDesc,
                                    'FULLRATE' => $RbnFullAssessedItemRate,
                                    'PREVOTSQTY' => $RbnFullAssessedItemQtyPrevOts,
                                    'ACTUALUSEDQTY' => $UtilizedQty,
                                    'ACTUALWASTAGE' => $Wastage,
                                    'NEWPAYQTY' => (!empty($RbnLastQuarter) && $RbnLastQuarter == $Quarter) ? $RbnFullAssessedItemPayQty : 0,
                                    'NEWPAYAMT' => (!empty($RbnLastQuarter) && $RbnLastQuarter == $Quarter) ? $RbnFullAssessedItemPayAmt : 0,
                                    'NEWTOTRECQTY' => $RbnFullAssessedItemRecQty,
                                    'NEWTOTRECAMT' => $FullAssessedSecAdvRecAmt,
                                    'NEWRECQTY' => $RbnFullAssessedItemQtyUsed,
                                    'NEWWASTAGE' => $RbnFullAssessedItemQtyWastage
                                );
                                $TempArr1[] = $TempArr2;
                            }

                            $FullAssesdedSecAdvFinalArr[$Quarter][$QuarterRbn] = array(
                                'SADATA' => $TempArr1,
                                'PAY' => $FullAssessedSecAdvPayAmt,
                                'REC' => $FullAssessedSecAdvRecAmt
                            );
                        }
                    }
                }
            }
        }
    }
    return $FullAssesdedSecAdvFinalArr;
}
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
        <div class="title">Escalation - Quarter Wise Work Done Value</div>
       	<div class="container_12">
           	<div class="grid_12">
			<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                 <blockquote class="bq1" style="overflow:auto">
                </blockquote>
            </div>
        </div>
	</div>
</form>
        <!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>