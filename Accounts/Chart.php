<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
//echo $_SESSION['login_return_url'];
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_display($ddmmyyyy) 
{
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}
if($_GET['content'] != "")
{
	$content = $_GET['content'];
}
//$minmax_level_str 		= getstaff_minmax_level();
//$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
$min_levelid 			= 0;//$exp_minmax_level_str[0];
$max_levelid 			= 0;//$exp_minmax_level_str[1];
	
	
$staffid_acc 			= $_SESSION['sid_acc'];
$staff_level_str 		= getstafflevel($staffid_acc);
$exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
$staff_roleid 			= $exp_staff_level_str[0];
$staff_levelid 			= $exp_staff_level_str[1];

if($_GET['sheetid'] != ""){
	$sheetid = $_GET['sheetid'];
}

if($sheetid != ""){
	$SelectWorkDtQuery 	= "select * from sheet where sheet_id = '$sheetid'";
	$SelectWorkDtSql 	= mysqli_query($dbConn,$SelectWorkDtQuery);
	if($SelectWorkDtSql == true){
		if(mysqli_num_rows($SelectWorkDtSql)>0){
			$WorkDtList 		= mysqli_fetch_object($SelectWorkDtSql);
			$WorkShortName 		= $WorkDtList->short_name;
			$NameOfWork			= $WorkDtList->work_name;
			if($WorkShortName != ""){
				$NameOfWork = $WorkShortName;
			}
			$WorkOrderNo 		= $WorkDtList->work_order_no;
			$WorkTechSanction 	= $WorkDtList->tech_sanction;
			$WorkContractName 	= $WorkDtList->name_contractor;
			$WorkAgreementNo 	= $WorkDtList->agree_no;
			$WorkCcno 			= $WorkDtList->computer_code_no;
			$WorkRebatePerc 	= $WorkDtList->rebate_percent;
			$WorkOrderDate 		= $WorkDtList->work_order_date;
			$WorkOrderCost 		= $WorkDtList->work_order_cost;
			$WorkCommenceDate 	= $WorkDtList->work_commence_date;
			$WorkDuration 		= $WorkDtList->work_duration;
			$WorkSchCompDate 	= $WorkDtList->date_of_completion;
			$WorkAssignedStaff 	= $WorkDtList->assigned_staff;
		}
	}
	$AllItemIdArr 	= array(); $AllItemNameArr 	= array(); $GItemIdArr 	= array(); $GItemNameArr 	= array(); 
	$SItemIdArr 	= array(); $SItemNameArr 	= array(); $SSItemIdArr = array(); $SSItemNameArr 	= array();
	$ItemAgmtQtyArr = array(); $ItemDescArr 	= array(); $ItemUnitArr = array(); $ItemTypeArr		= array();
	$ItemRateArr	= array(); $ItemDecimalArr	= array(); $DonutDataArr = array();
	$SelectItemQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id != '0' and sno != ''";
	//echo $SelectItemQuery;exit;
	$SelectItemSql 	 = mysqli_query($dbConn,$SelectItemQuery);
	if($SelectItemSql == true){
		if(mysqli_num_rows($SelectItemSql)>0){ 
			while($ItemList = mysqli_fetch_object($SelectItemSql)){ 
				array_push($AllItemIdArr,$ItemList->subdiv_id);
				$AllItemNameArr[$ItemList->subdiv_id] = $ItemList->sno;
				if($ItemList->measure_type == "s"){
					array_push($SItemIdArr,$ItemList->subdiv_id);
					$SItemNameArr[$ItemList->subdiv_id] = $ItemList->sno;
					$ItemTypeArr[$ItemList->subdiv_id] = 'S';
				}else if($ItemList->measure_type == "st"){
					array_push($SSItemIdArr,$ItemList->subdiv_id);
					$SSItemNameArr[$ItemList->subdiv_id] = $ItemList->sno;
					$ItemTypeArr[$ItemList->subdiv_id] = 'ST';
				}else{
					array_push($GItemIdArr,$ItemList->subdiv_id);
					$GItemNameArr[$ItemList->subdiv_id] = $ItemList->sno;
					$ItemTypeArr[$ItemList->subdiv_id] = 'G';
					
				}
				$AgmtItemQty 	= $ItemList->total_quantity;
				$ItemDecimal 	= $ItemList->decimal_placed;
				$DeviatePercent = $ItemList->deviate_qty_percent;
				$DeviateQty 	= $AgmtItemQty * $DeviatePercent / 100;
				$DeviateQty		= round($DeviateQty,$ItemDecimal);
				$TotalAgmtQty 	= round(($AgmtItemQty + $DeviateQty),$ItemDecimal);
				$ItemAgmtQtyArr[$ItemList->subdiv_id] = $TotalAgmtQty;
				
				$Description1 	= $ItemList->description;
				$Shortnotes1 	= $ItemList->shortnotes;
				$Description 	= str_replace("'", "", $Description1);
				$Shortnotes 	= str_replace("'", "", $Shortnotes1);
				if($Shortnotes != ""){
					$ItemDesc 	= $Shortnotes;
				}else{
					$ItemDesc 	= $Description;
				}
				$ItemUnit 		= $ItemList->per;
				$AgmtItemRate 	= $ItemList->rate;
				$UnitFactor 	= findNumericFromString($ItemUnit);
				$ItemRate 		= $AgmtItemRate / $UnitFactor;
				$ItemDescArr[$ItemList->subdiv_id] = $ItemDesc;
				$ItemUnitArr[$ItemList->subdiv_id] = $ItemUnit;
				$ItemRateArr[$ItemList->subdiv_id] = $ItemRate;
				$ItemDecimalArr[$ItemList->subdiv_id] = $ItemDecimal;
			}
		}
	}
	//print_r($AllItemIdArr);exit;
	$RABAmountArr = array(); $WorkRABArr = array(); $WorkBillDateArr = array(); $RABArr = array();
	$SelectPaidAmtQuery = "select * from abstractbook where sheetid = '$sheetid' order by rbn asc";
	$SelectPaidAmtSql 	= mysqli_query($dbConn,$SelectPaidAmtQuery);
	if($SelectPaidAmtSql == true){
		if(mysqli_num_rows($SelectPaidAmtSql)>0){
			while($AbstAmtList 	= mysqli_fetch_object($SelectPaidAmtSql)){
				$WorkTotalUptoDateAmt 	= $AbstAmtList->upto_date_total_amount;
				$WorkTotalSlmDateAmt 	= $AbstAmtList->slm_total_amount;
				$WorkTotalDpmDateAmt 	= $AbstAmtList->dpm_total_amount;
				$WorkTotalRebateAmt 	= $AbstAmtList->slm_rebate_amt;
				$RABPassOrderDate 		= $AbstAmtList->pass_order_date;
				$RABGenratedDate 		= date('d/m/Y', strtotime($AbstAmtList->abs_book_date));
				if($RABPassOrderDate != "0000-00-00"){
					$BillDate = dt_display($RABPassOrderDate);
				}else{
					$BillDate = $RABGenratedDate;
				}
				$RABAmountArr[$AbstAmtList->rbn] 	= $WorkTotalSlmDateAmt;
				$WorkBillDateArr[$AbstAmtList->rbn] = $BillDate;
				array_push($RABArr,$AbstAmtList->rbn);
			}
		}
	}
	$GeneralPaidAmt = array(); $SteelPaidAmt = array(); $StructSteelPaidAmt = array(); $PaidItemQtyArr = array();
	$SelectPaidAmtQuery = "select * from measurementbook where sheetid = '$sheetid' and part_pay_flag != 'DMY' order by rbn asc";
	$SelectPaidAmtSql 	= mysqli_query($dbConn,$SelectPaidAmtQuery);
	if($SelectPaidAmtSql == true){
		if(mysqli_num_rows($SelectPaidAmtSql)>0){
			while($PaidAmtList 	= mysqli_fetch_object($SelectPaidAmtSql)){
				$PaidItemId 		= $PaidAmtList->subdivid;
				$PaidItemQty 		= $PaidAmtList->mbtotal;
				$PaidPercent 		= $PaidAmtList->pay_percent;
				$PartPaymentFlag 	= $PaidAmtList->part_pay_flag;
				$PaidItemRate		= $ItemRateArr[$PaidItemId];
				$PaidAmount 		= round(($PaidItemQty * $PaidItemRate * $PaidPercent / 100),2);
				if(($PartPaymentFlag == 0)||($PartPaymentFlag == 1)){
					if(($PaidItemQtyArr[$PaidItemId] == "")||($PaidItemQtyArr[$PaidItemId] == "")){
						$PaidItemQtyArr[$PaidItemId] = $PaidItemQty;
					}else{
						$PaidItemQtyArr[$PaidItemId] = $PaidItemQtyArr[$PaidItemId] + $PaidItemQty;
					}
				}
				if($ItemTypeArr[$PaidItemId] == "S"){
					$SteelPaidAmt[$PaidAmtList->rbn] 		= $SteelPaidAmt[$PaidAmtList->rbn] + $PaidAmount;
				}else if($ItemTypeArr[$PaidItemId] == "ST"){
					$StructSteelPaidAmt[$PaidAmtList->rbn] 	= $StructSteelPaidAmt[$PaidAmtList->rbn]+ $PaidAmount;
				}else{
					$GeneralPaidAmt[$PaidAmtList->rbn] 		= $GeneralPaidAmt[$PaidAmtList->rbn] + $PaidAmount;
				}
			}
		}
	}
	$SelectPaidAmtQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and part_pay_flag != 'DMY' order by rbn asc";
	$SelectPaidAmtSql 	= mysqli_query($dbConn,$SelectPaidAmtQuery);
	if($SelectPaidAmtSql == true){
		if(mysqli_num_rows($SelectPaidAmtSql)>0){
			while($PaidAmtList 	= mysqli_fetch_object($SelectPaidAmtSql)){
				$PaidItemId 		= $PaidAmtList->subdivid;
				$PaidItemQty 		= $PaidAmtList->mbtotal;
				$PaidPercent 		= $PaidAmtList->pay_percent;
				$PartPaymentFlag 	= $PaidAmtList->part_pay_flag;
				$PaidItemRate		= $ItemRateArr[$PaidItemId];
				$PaidAmount 		= round(($PaidItemQty * $PaidItemRate * $PaidPercent / 100),2);
				if(($PartPaymentFlag == 0)||($PartPaymentFlag == 1)){
					if(($PaidItemQtyArr[$PaidItemId] == "")||($PaidItemQtyArr[$PaidItemId] == "")){
						$PaidItemQtyArr[$PaidItemId] = $PaidItemQty;
					}else{
						$PaidItemQtyArr[$PaidItemId] = $PaidItemQtyArr[$PaidItemId] + $PaidItemQty;
					}
				}
				
				if($ItemTypeArr[$PaidItemId] == "S"){
					$SteelPaidAmt[$PaidAmtList->rbn] 		= $SteelPaidAmt[$PaidAmtList->rbn] + $PaidAmount;
				}else if($ItemTypeArr[$PaidItemId] == "ST"){
					$StructSteelPaidAmt[$PaidAmtList->rbn] 	= $StructSteelPaidAmt[$PaidAmtList->rbn]+ $PaidAmount;
				}else{
					$GeneralPaidAmt[$PaidAmtList->rbn] 		= $GeneralPaidAmt[$PaidAmtList->rbn] + $PaidAmount;
				}
			}
		}
	}
	$MaxBillDate = "0000-00-00";
	$SelectMaxDateQuery = "select max(todate) as maxdate from abstractbook where sheetid = '$sheetid'";
	$SelectMaxDateSql 	= mysqli_query($dbConn,$SelectMaxDateQuery);
	if($SelectMaxDateSql == true){
		if(mysqli_num_rows($SelectMaxDateSql)>0){
			$MaxDtList 	= mysqli_fetch_object($SelectMaxDateSql);
			$MaxBillDate 	= $MaxDtList->maxdate;
		}
	}
	
	$SelectDateWiseGenQuery = "select * from date_wise_gen_meas where sheetid = '$sheetid' and date > '$MaxBillDate'";
	$SelectDateWiseGenSql 	= mysqli_query($dbConn,$SelectDateWiseGenQuery);
	if($SelectDateWiseGenSql == true){
		if(mysqli_num_rows($SelectDateWiseGenSql)>0){
			while($MaxDtList = mysqli_fetch_object($SelectDateWiseGenSql)){
				$MaxDateItemDate 	= $MaxDtList->date;
				$MaxDateItemId		= $MaxDtList->subdivid;
				$MaxDateItemQty 	= $MaxDtList->item_qty;
				$MaxDateMeasType 	= $MaxDtList->measure_type;
				$MaxDateItemDecimal = $ItemDecimalArr[$MaxDateItemId];//$MaxDtList->decimal_placed;
				if($MaxDateMeasType == 'st'){
					$MaxDateItemQty = round(($MaxDateItemQty / 1000),$MaxDateItemDecimal);
				}
				if(($PaidItemQtyArr[$MaxDateItemId] == "")||($PaidItemQtyArr[$MaxDateItemId] == "")){
					$PaidItemQtyArr[$MaxDateItemId] = $MaxDateItemQty;
				}else{
					$PaidItemQtyArr[$MaxDateItemId] = $PaidItemQtyArr[$MaxDateItemId] + $MaxDateItemQty;
				}
			}
		}
	}
	$DiaQty = 0; $TempStlQtyArr = array();
	$SelectDateWiseStlQuery = "select date, subdivid, measurement_dia, measure_type, sum(item_qty) as stl_item_qty from date_wise_stl_meas where sheetid = '$sheetid' and date > '$MaxBillDate' group by subdivid, measurement_dia";
	$SelectDateWiseStlSql 	= mysqli_query($dbConn,$SelectDateWiseStlQuery);
	if($SelectDateWiseStlSql == true){
		if(mysqli_num_rows($SelectDateWiseStlSql)>0){
			while($MaxDtList = mysqli_fetch_object($SelectDateWiseStlSql)){
				$MaxDateItemDate 	= $MaxDtList->date;
				$MaxDateItemId		= $MaxDtList->subdivid;
				$MaxDateItemQty 	= $MaxDtList->stl_item_qty;
				$MaxDateMeasType 	= $MaxDtList->measure_type;
				$MaxDateMeasDia 	= $MaxDtList->measurement_dia;
				$MaxDateItemDecimal = $ItemDecimalArr[$MaxDateItemId];
				if($MaxDateMeasDia == 8){
					$DiaQty = round(($MaxDateItemQty * 0.395),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 10){
					$DiaQty = round(($MaxDateItemQty * 0.617),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 12){
					$DiaQty = round(($MaxDateItemQty * 0.888),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 16){
					$DiaQty = round(($MaxDateItemQty * 1.58),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 20){
					$DiaQty = round(($MaxDateItemQty * 2.47),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 25){
					$DiaQty = round(($MaxDateItemQty * 3.85),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 28){
					$DiaQty = round(($MaxDateItemQty * 4.83),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 32){
					$DiaQty = round(($MaxDateItemQty * 6.31),$MaxDateItemDecimal);
				}else if($MaxDateMeasDia == 36){
					$DiaQty = round(($MaxDateItemQty * 7.990),$MaxDateItemDecimal);
				}
				if(($TempStlQtyArr[$MaxDateItemId] == "")||($TempStlQtyArr[$MaxDateItemId] == "")){
					$TempStlQtyArr[$MaxDateItemId] = $DiaQty;
				}else{
					$TempStlQtyArr[$MaxDateItemId] = $TempStlQtyArr[$MaxDateItemId] + $DiaQty;
				}
			}
			if(count($TempStlQtyArr)>0){
				foreach($TempStlQtyArr as $TempStlItemId => $TempStlItemQty){
					$TempStlItemDecimal 	= $ItemDecimalArr[$TempStlItemId];
					$TempStlItemQtyInTonne 	= round(($TempStlItemQty / 1000),$TempStlItemDecimal);
					if(($PaidItemQtyArr[$TempStlItemId] == "")||($PaidItemQtyArr[$TempStlItemId] == "")){
						$PaidItemQtyArr[$TempStlItemId] = $TempStlItemQtyInTonne;
					}else{
						$PaidItemQtyArr[$TempStlItemId] = $PaidItemQtyArr[$TempStlItemId] + $TempStlItemQtyInTonne;
					}
				}
			}
		}
	}
	
	$RbnData = ""; $GeneralAmtData = ""; $SteelAmtData = ""; $StructAmtData = ""; $DonutJsonData = ""; $TotalPaidCost = 0; $DeviatedPaidCost = 0; 
	$DevCostData = ""; $TotalCostData = ""; 
	if(count($RABArr)>0){
		foreach($RABArr as $RABValue){
			$RABStr 	   .= "'RAB-".$RABValue."',";
			
			$GPaidAmt 		= $GeneralPaidAmt[$RABValue];
			$TotalPaidCost	= $TotalPaidCost + $GPaidAmt;
			$GPaidAmt 		= round(($GPaidAmt / 100000),2);
			$GeneralAmtData.= $GPaidAmt.",";
			
			$SPaidAmt 		= $SteelPaidAmt[$RABValue];
			$TotalPaidCost	= $TotalPaidCost + $SPaidAmt;
			$SPaidAmt 		= round(($SPaidAmt / 100000),2);
			$SteelAmtData  .= $SPaidAmt.",";
			
			$SSPaidAmt 		= $StructSteelPaidAmt[$RABValue];
			$TotalPaidCost	= $TotalPaidCost + $SSPaidAmt;
			$SSPaidAmt 		= round(($SSPaidAmt / 100000),2);
			$StructAmtData .= $SSPaidAmt.",";
			
			
			$BillDate 		= $WorkBillDateArr[$RABValue];
			$RABPaidAmount  = $RABAmountArr[$RABValue];
			$DonutJsonData .= '{ "RAB":"RAB - '.$RABValue.'","Amount":"'.$RABPaidAmount.'" },';
			$DonutDataArr[] = array('RAB' => 'RAB-'."$RABValue" ,'Amount' => "$RABPaidAmount",'Paid Date' => $BillDate);
		}
		$RemainCost = $WorkOrderCost - $TotalPaidCost;
		$DonutDataArr[] = array('RAB' => 'Balance Amount'."" ,'Amount' => "$RemainCost",'Paid Date' => '');
		$DonutJsonData =  json_encode($DonutDataArr);
		$RABStr = rtrim($RABStr,",");
		$RbnData = "[".$RABStr."]";
		$GeneralAmtData = rtrim($GeneralAmtData,",");
		$GeneralAmtData = "[".$GeneralAmtData."]";
		$SteelAmtData 	= rtrim($SteelAmtData,",");
		$SteelAmtData 	= "[".$SteelAmtData."]";
		$StructAmtData 	= rtrim($StructAmtData,",");
		$StructAmtData 	= "[".$StructAmtData."]";
		//$DonutJsonData 	= rtrim($DonutJsonData,",");
		//$DonutJsonData 	= "[".$DonutJsonData."]";
		if($WorkOrderCost < $TotalPaidCost){
			$DeviatedPaidCost = round(($TotalPaidCost - $WorkOrderCost),2);
		} 
		$DevCostData 	= "Deviated Cost is Rs. ".number_format($DeviatedPaidCost,2);
		$TotalCostData 	= "Total Cost is Rs. ".number_format($TotalPaidCost,2);
	}
	$mContent = "no";  $PieBarChat = "no";
	$ItemNoStr = ""; $AggreQtyStr = ""; $UsedQtyStr = ""; $BalanceQtyStr = ""; $PieChartdata = ""; $PieChartdataDt = "";
	if(count($AllItemIdArr)>0){
		$mContent = "yes";
		foreach($AllItemIdArr as $ItemIdValue){
			$ItemName = $AllItemNameArr[$ItemIdValue];
			$ItemNoStr .= "'".$ItemName."',";
			//$ItemNoStr .= "'a',";
			
			$AgreementQty 	= $ItemAgmtQtyArr[$ItemIdValue];
			$ExecutedQty  	= $PaidItemQtyArr[$ItemIdValue];
			if($ExecutedQty == ""){
				$ExecutedQty = 0;
			}
			$ItemNoDecimal  = $ItemDecimalArr[$ItemIdValue];
			$ItemNoUnit  	= $ItemUnitArr[$ItemIdValue];
			$ItemNoDesc  	= $ItemDescArr[$ItemIdValue];
			$BalanceQty   	= round(($AgreementQty - $ExecutedQty),$ItemNoDecimal);
			$UsedQtyPercent = round(($ExecutedQty * 100 / $AgreementQty),2);
			$AggreQtyStr 	.= $AgreementQty.",";
			$UsedQtyStr 	.= $ExecutedQty.",";
			$BalanceQtyStr 	.= $BalanceQty.",";
			
			$myOtherData1 = "Agreement Qty : ".$AgreementQty." ".$ItemNoUnit;
			$myOtherData2 = "Executed Qty  : ".$ExecutedQty." ".$ItemNoUnit;
			$myOtherData3 = "Balance Qty   : ".$BalanceQty." ".$ItemNoUnit;
			
			$AggreQtyStrDt   .= "{ y : ".$AgreementQty.", myData:'".$ItemNoDesc."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
			$UsedQtyStrDt 	 .= "{ y : ".$ExecutedQty.", myData:'".$ItemNoDesc."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
			$BalanceQtyStrDt .= "{ y : ".$BalanceQty.", myData:'".$ItemNoDesc."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
			
			//if($UsedQtyPercent > 0){
			$PieChartdata 	.= "['".$ItemName." - ".$UsedQtyPercent."%',".$UsedQtyPercent."],";
			$PieChartdataDt .= "{ name:'".$ItemName."', y:".$UsedQtyPercent.",myData:'".$ItemNoDesc."' },";
			//}
		}
		$ItemNoStr 		 = rtrim($ItemNoStr,",");
		//$ItemNoStr = "[".$ItemNoStr."]";
		
		$AggreQtyStr 	 = rtrim($AggreQtyStr,",");
		$UsedQtyStr 	 = rtrim($UsedQtyStr,",");
		$BalanceQtyStr 	 = rtrim($BalanceQtyStr,",");
		
		$AggreQtyStrDt 	 = rtrim($AggreQtyStrDt,",");
		$UsedQtyStrDt 	 = rtrim($UsedQtyStrDt,",");
		$BalanceQtyStrDt = rtrim($BalanceQtyStrDt,",");
		
		$PieChartdata 	 = rtrim($PieChartdata,",");
		$PieChartdataDt  = rtrim($PieChartdataDt,",");
		$PieChartdata  	 = "[".$PieChartdata."]";
		$PieChartdataDt  = "[".$PieChartdataDt."]";
	}
	if(($content == "yes") && ($mContent == "yes")){
		$PieBarChat = "yes";
	}
}
//echo $PieChartdata;exit;
/*echo $AggreQtyStrDt;
//print_r($AllItemIdArr);
exit;

function getRABBillDate($sheetid,$rbn){
	$BillDate = "";
	$select_query 	= "select DATE_FORMAT(modifieddate,'%d/%m/%Y') as billdate from send_accounts_and_civil where mtype = 'A' and genlevel = 'abstract' and sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sql 	= mysqli_query($dbConn,$select_query);
	if($select_sql == true){
		if(mysqli_num_rows($select_sql)>0){
			$BDList 	= mysqli_fetch_object($select_sql);
			$BillDate 	= $BDList->billdate;
		}
	}
	return $BillDate;
}

$itemcheck = 0;
if($_GET['sheetid'] != ""){
	$sheetid = $_GET['sheetid'];
	$WorkOrderQuery = "select * from sheet where active = '1' and sheet_id = '$sheetid'";
	$WorkOrderSql 	= mysqli_query($dbConn,$WorkOrderQuery);
	if($WorkOrderSql == true){
		if(mysqli_num_rows($WorkOrderSql)>0){
			while($WList = mysqli_fetch_object($WorkOrderSql)){
				$work_order_no 		= $WList->work_order_no;
				$work_name 			= $WList->work_name;
				$short_name 		= $WList->short_name;
				$tech_sanction 		= $WList->tech_sanction;
				$agree_no 			= $WList->agree_no;
				$name_contractor 	= $WList->name_contractor;
				$worktype 			= $WList->worktype;
				if($short_name == ""){
					$short_name = $work_name;
				}
				$TotalWoCost 		= $WList->work_order_cost;
				$RebatePercent 		= $WList->rebate_percent;
				$RebateAmt 			= $TotalWoCost * $RebatePercent/100;
				$TotalWoCost 		= $TotalWoCost - $RebateAmt;
			}
		}
	}
}
$LastMeasureDate = "0000-00-00";
$sheetid = 488;
$SelectMaxDateQuery = "select max(todate) as mdate from abstractbook where sheetid = '$sheetid'";
$SelectMaxDateSql 	= mysqli_query($dbConn,$SelectMaxDateQuery);
if($SelectMaxDateSql == true){
	if(mysqli_num_rows($SelectMaxDateSql)>0){
		$MDList 		 = mysqli_fetch_object($SelectMaxDateSql);
		$LastMeasureDate = $MDList->mdate;
	}
}
echo $MaxMbHeadId;exit;

$UsedQtyStr = ""; $AggreQtyStr = ""; $BalanceQtyStr = ""; $ItemNoStr = "";
$ItemNoQuery 	= "select sno, subdiv_id, total_quantity, description, shortnotes, deviate_qty_percent, per, measure_type, decimal_placed from schdule where sheet_id = '$sheetid' and subdiv_id != '0'";	
$ItemNoSql 		= mysqli_query($dbConn,$ItemNoQuery);
if($ItemNoSql == true)
{
	$itemcheck = 1;
	if(mysqli_num_rows($ItemNoSql)>0)
	{
		
		while($ItemList = mysqli_fetch_object($ItemNoSql))
		{
			$ItemId 	= $ItemList->subdiv_id;
			$ItemNo 	= $ItemList->sno;
			$Mtype  	= $ItemList->measure_type;
			$AggQty 	= $ItemList->total_quantity;
			$DevQtyPerc = $ItemList->deviate_qty_percent;
			$decimal	= $ItemList->decimal_placed;
			$AggDevQty  = round(($AggQty * $DevQtyPerc/100),$decimal);
			$TotalAggQty = round(($AggQty + $AggDevQty),$decimal);
			
			$description1 = $ItemList->description;
			$shortnotes1 = $ItemList->shortnotes;
			$description1 = str_replace("'", "", $description1);
			$shortnotes1 = str_replace("'", "", $shortnotes1);
			if($shortnotes1 != "")
			{
				$Item_Desc1 = $shortnotes1;
			}
			else
			{
				$Item_Desc1 = $description1;
			}
			$Item_Desc1 = str_replace("'", "", $Item_Desc1);
			$ItemUnit 	= $ItemList->per;
			
			$PrevUsedQty = 0; $CurrUsedQty = 0; $TotalUsedQty = 0;
			$select_measure_query = "select sum(mbtotal) as prev_used_qty from measurementbook where sheetid = '$sheetid' and subdivid = '$ItemId' group by subdivid";
			$select_measure_sql = mysqli_query($dbConn,$select_measure_query);
			if($select_measure_sql == true)
			{
				$PQtyList = mysqli_fetch_object($select_measure_sql);
				$PrevUsedQty = $PQtyList->prev_used_qty;
			}
			if($PrevUsedQty == "")
			{
				$PrevUsedQty = 0;
			}
			
			if($Mtype != "s")
			{
				$CurrUsedQty = getGeneralItemQtyPercent($ItemId,$LastMeasureDate);
			}
			if($Mtype == "s")
			{
				$CurrUsedQty = getSteelItemQtyPercent($ItemId,$LastMeasureDate);
			}
			
			if($CurrUsedQty == "")
			{
				$CurrUsedQty = 0;
			}
			$TotalUsedQty = $PrevUsedQty + $CurrUsedQty;
			
			$BalanceQty = $TotalAggQty - $TotalUsedQty;
			if($TotalUsedQty > 0)
			{
				$ItemNoStr   .= "'".$ItemNo."',";
				$AggreQtyStr .= $TotalAggQty.",";
				$UsedQtyStr .= $TotalUsedQty.",";
				$BalanceQtyStr .= $BalanceQty.",";
				
				$myOtherData1 = "Agreement Qty : ".$TotalAggQty." ".$ItemUnit;
				$myOtherData2 = "Executed Qty  : ".$TotalUsedQty." ".$ItemUnit;
				$myOtherData3 = "Balance Qty   : ".$BalanceQty." ".$ItemUnit;
				
				$ItemDescription = $itemDescArr[$ItemId];
				
				$AggreQtyStrDt .= "{ y : ".$TotalAggQty.", myData:'".$Item_Desc1."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
				$UsedQtyStrDt .= "{ y : ".$TotalUsedQty.", myData:'".$Item_Desc1."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
				$BalanceQtyStrDt .= "{ y : ".$BalanceQty.", myData:'".$Item_Desc1."', myData1:'".$myOtherData1."', myData2:'".$myOtherData2."', myData3:'".$myOtherData3."'},";
			}
		}
	}
}
$ItemNoStr 		= rtrim($ItemNoStr,",");
$AggreQtyStr 	= rtrim($AggreQtyStr,",");
$UsedQtyStr 	= rtrim($UsedQtyStr,",");
$BalanceQtyStr 	= rtrim($BalanceQtyStr,",");

$AggreQtyStrDt 	= rtrim($AggreQtyStrDt,",");
$UsedQtyStrDt 	= rtrim($UsedQtyStrDt,",");
$BalanceQtyStrDt 	= rtrim($BalanceQtyStrDt,",");

$RbnAmtQuery = "select measurementbook.subdivid, measurementbook.mbtotal, measurementbook.pay_percent, measurementbook.rbn, schdule.rate, schdule.sno, 
schdule.total_quantity, schdule.measure_type from measurementbook 
INNER JOIN schdule ON (schdule.subdiv_id = measurementbook.subdivid) where measurementbook.sheetid = '$sheetid' and  schdule.sheet_id = '$sheetid' and ((measurementbook.part_pay_flag = 0) OR (measurementbook.part_pay_flag = 1))  
ORDER BY measurementbook.rbn ASC, measurementbook.subdivid ASC";
$RbnAmtSql 		= mysqli_query($dbConn,$RbnAmtQuery);
$RbnSteelAmt = 0; $RbnStructAmt = 0; $RbnGeneralAmt = 0; $PrevRbn = ""; $PrevType = ""; $Prevamount = 0; $DonutAmount = 0; $UsedWoCost = 0;
$RbnData 		= "[";
$SteelAmtData 	= "[";
$StructAmtData 	= "[";
$GeneralAmtData = "[";
$subdividList 	= "";
$DonutData 		= "";
$DonutDataArr = array();
$itemDescArr = array();
if($RbnAmtSql == true)
{
	if(mysqli_num_rows($RbnAmtSql)>0)
	{
		while($RbnAmtList = mysqli_fetch_object($RbnAmtSql))
		{
			$amount = ($RbnAmtList->mbtotal) * ($RbnAmtList->rate) * ($RbnAmtList->pay_percent)/100;
			$CurrRbn = $RbnAmtList->rbn;
			$CurrType = $RbnAmtList->measure_type;
			$description = $RbnAmtList->description;
			$shortnotes = $RbnAmtList->shortnotes;
			if($shortnotes != "")
			{
				$Item_Desc = $shortnotes;
			}
			else
			{
				$Item_Desc = $description;
			}
			$itemDescArr[$RbnAmtList->subdivid] = $Item_Desc;
			if(($RbnAmtList->part_pay_flag == 0) || ($RbnAmtList->part_pay_flag == 1))
			{
				$subdividList .= $RbnAmtList->subdivid."*".$RbnAmtList->sno."*".$RbnAmtList->mbtotal."*".$RbnAmtList->total_quantity."@";
			}
			
			if($PrevRbn != "")
			{
				if($CurrRbn != $PrevRbn)
				{
					$BillDate = getRABBillDate($sheetid,$PrevRbn); //echo $BillDate;exit;
					$DonutDataArr[] = array('RAB' => 'RAB'."$PrevRbn",'Amount' => "$DonutAmount",'Paid Date' => $BillDate);
					$UsedWoCost = $UsedWoCost+$DonutAmount;
					$DonutAmount = 0;
					
					$SteelAmtData = $SteelAmtData.(round($RbnSteelAmt/100000,2)).",";
					$RbnSteelAmt = 0;
					$StructAmtData = $StructAmtData.(round($RbnStructAmt/100000,2)).",";
					$RbnStructAmt = 0;
					$GeneralAmtData = $GeneralAmtData.(round($RbnGeneralAmt/100000,2)).",";
					$RbnGeneralAmt = 0;
					
				}
			}
			$DonutAmount = $DonutAmount + $amount;
			
			if(($PrevRbn != "") && ($PrevRbn != $CurrRbn))
			{
				$RbnData = $RbnData."'RAB-".$PrevRbn."',";
			}
			
			
			if($CurrType == 's')
			{
				$RbnSteelAmt = ($RbnSteelAmt + $amount);
			}
			else if($CurrType == 'st')
			{
				$RbnStructAmt = ($RbnStructAmt + $amount);
			}
			else
			{
				$RbnGeneralAmt = ($RbnGeneralAmt + $amount);
			}
			$PrevRbn = $CurrRbn;
			$Prevamount = $amount;
			$PrevType = $CurrType;
			$PrevDonutAmount = $DonutAmount;
		}
		$RbnData = $RbnData."'RAB-".$PrevRbn."',";
		$BillDate = getRABBillDate($sheetid,$PrevRbn);
		$DonutDataArr[] = array('RAB' => 'RAB'."$PrevRbn" ,'Amount' => "$PrevDonutAmount",'Paid Date' => $BillDate);
		$UsedWoCost = $UsedWoCost+$DonutAmount;

		$SteelAmtData = $SteelAmtData.(round($RbnSteelAmt/100000,2)).",";
		$StructAmtData = $StructAmtData.(round($RbnStructAmt/100000,2)).",";
		$GeneralAmtData = $GeneralAmtData.(round($RbnGeneralAmt/100000,2)).",";
			
		$RbnData = rtrim($RbnData,',')."]";

		$SteelAmtData = rtrim($SteelAmtData,',')."]";
		$StructAmtData = rtrim($StructAmtData,',')."]";
		$GeneralAmtData = rtrim($GeneralAmtData,',')."]";
	}
	else
	{
		$RbnData = rtrim($RbnData,',')."0]";
		$SteelAmtData = rtrim($SteelAmtData,',')."0]";
		$StructAmtData = rtrim($StructAmtData,',')."0]";
		$GeneralAmtData = rtrim($GeneralAmtData,',')."0]";
	}
}

/// very very important for Pie and bar chart 
if($subdividList == "")
{
	$mContent = "no";
}
else
{
	$mContent = "yes";
}
if(($content == "yes") && ($mContent == "yes"))
{
	$PieBarChat = "yes";
}
else
{
	$PieBarChat = "no";
}
if($subdividList != "")
{
	$Previtemid = "";
	$PieChartdata = "[";
	$PieChartdataDt = "[";
	$explodeSubdivid = explode("@",$subdividList);
	natsort($explodeSubdivid);
	$implodeSubdivid = implode("*",$explodeSubdivid);
	$PieChartList = explode("*",trim($implodeSubdivid,"*"));
	for($x1=0; $x1<count($PieChartList); $x1+=4)
	{
		$itemid = $PieChartList[$x1+0];
		$itemname = $PieChartList[$x1+1];
		$itemqty = $PieChartList[$x1+2];
		$itemOverallQty = $PieChartList[$x1+3];
		
		$ItemDescription = $itemDescArr[$itemid];
		
		if($Previtemid != "")
		{
			if($itemid != $Previtemid)
			{
				$usedPercent = round(($itmqtyTotal*100/$PrevitemOverallqty),2);
				$PieChartdata = $PieChartdata."['".$Previtemname." - ".$usedPercent."%',".$usedPercent."],";
				$PieChartdataDt = $PieChartdataDt."{ name:'".$Previtemname."', y:".$usedPercent.",myData:'".$PrecItemDescription."' } ,";
				$itmqtyTotal = 0;
			}
			
		}
		$itmqtyTotal = $itmqtyTotal + $itemqty;
		$Previtemid = $itemid;
		$Previtemname = $itemname;
		$Previtemqty = $itemqty;
		$PrecItemDescription = $ItemDescription;
		$PrevitemOverallqty = $itemOverallQty;
	}
	$usedPercent = round(($itmqtyTotal*100/$PrevitemOverallqty),2);
	$PieChartdata = $PieChartdata."['".$Previtemname." - ".$usedPercent."%',".$usedPercent."],";
	$PieChartdataDt = $PieChartdataDt."{ name:'".$Previtemname."', y:".$usedPercent.", myData:'".$PrecItemDescription."' } ]";
	$itmqtyTotal = 0;
	$PieChartdata = rtrim($PieChartdata,',')."]";
}
else
{
	$PieChartdata = "[]";
	$PieChartdataDt = "[]";
}

$RemainCost = $TotalWoCost - $UsedWoCost;
if($UsedWoCost>$TotalWoCost)
{
	$Overall_dev_qty_cost = $UsedWoCost - $TotalWoCost;
	$Dev_qty_data = "Deviated Cost is Rs. ".number_format($Overall_dev_qty_cost,2);
	$tot_cost_data = "Total Cost is Rs. ".number_format($UsedWoCost,2);
}
else
{
	$Dev_qty_data = "";
	$tot_cost_data = "";
}
$DonutDataArr[] = array('RAB' => 'Balance Amount'."" ,'Amount' => "$RemainCost",'Paid Date' => '');
$DonutJsonData =  json_encode($DonutDataArr);
function getGeneralItemQtyPercent($subdivid,$lastdate)
{
	$qty = 0;
	if($lastdate == ""){ $lastdate = "0000-00-00"; }
	
	$QtyQuery = "select sum(measurement_contentarea) from mbookdetail a inner join mbookheader b on (a.mbheaderid = b.mbheaderid) where a.subdivid = '$subdivid' and a.mbdetail_flag != 'd' and b.date > '$lastdate'";
	$QtySql = mysqli_query($dbConn,$QtyQuery);
	if($QtySql == true)
	{
		if(mysqli_num_rows($QtySql)>0)
		{
			while($QtyList = mysqli_fetch_object($QtySql))
			{
				$qty = $qty+$QtyList->measurement_contentarea;
			}
		}
	}
	return $qty;
}
function getSteelItemQtyPercent($subdivid,$lastdate)
{
	$qty = 0; $totalweight = 0;
	if($lastdate == ""){ $lastdate = "0000-00-00"; }
	$total_8 = 0;$total_10 = 0;$total_12 = 0;$total_16 = 0;$total_20 = 0;$total_25 = 0;$total_28 = 0;$total_32 = 0;$total_36 = 0;
	$totalweight_8 = 0;$totalweight_10 = 0;$totalweight_12 = 0;$totalweight_16 = 0;$totalweight_20 = 0;$totalweight_25 = 0;$totalweight_28 = 0;$totalweight_32 = 0;$totalweight_36 = 0;
	$QtyQuery = "select a.measurement_contentarea, a.measurement_dia, b.date from mbookdetail a inner join mbookheader b on (a.mbheaderid = b.mbheaderid) where a.subdivid = '$subdivid' and a.mbdetail_flag != 'd' and b.date > '$lastdate'";
	$QtySql = mysqli_query($dbConn,$QtyQuery);
	if($QtySql == true)
	{
		if(mysqli_num_rows($QtySql)>0)
		{
			while($QtyList = mysqli_fetch_object($QtySql))
			{
				$CArea = $QtyList->measurement_contentarea;
				$dia = $QtyList->measurement_dia;
						if($dia == 8){ $total_8 = $total_8 + $CArea; }
						if($dia == 10){ $total_10 = $total_10 + $CArea; }
						if($dia == 12){ $total_12 = $total_12 + $CArea; }
						if($dia == 16){ $total_16 = $total_16 + $CArea; }
						if($dia == 20){ $total_20 = $total_20 + $CArea; }
						if($dia == 25){ $total_25 = $total_25 + $CArea; }
						if($dia == 28){ $total_28 = $total_28 + $CArea; }
						if($dia == 32){ $total_32 = $total_32 + $CArea; }
						if($dia == 36){ $total_36 = $total_36 + $CArea; }
			}
			$totalweight_8 = round(($total_8 * 0.395),3);
			$totalweight_10 = round(($total_10 * 0.617),3);
			$totalweight_12 = round(($total_12 * 0.888),3);
			$totalweight_16 = round(($total_16 * 1.58),3);
			$totalweight_20 = round(($total_20 * 2.47),3);
			$totalweight_25 = round(($total_25 * 3.85),3);
			$totalweight_28 = round(($total_28 * 4.83),3);
			$totalweight_32 = round(($total_32 * 6.31),3);
			$totalweight_36 = round(($total_36 * 7.990),3);
			$totalweight = $totalweight+round(($totalweight_8+$totalweight_10+$totalweight_12+$totalweight_16+$totalweight_20+$totalweight_25+$totalweight_28+$totalweight_32+$totalweight_36),3);
			$TotQty_mt = round(($totalweight/1000),2);
			$qty = $TotQty_mt;
		}
	}
	return $qty;
}*/
?>
<link rel="stylesheet" href="dashboard/css/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/js/bootstrap.min.js"></script>
<script src="dashboard/js/jquery.min.js"></script>
<style>
	.dashboardheader
	{
		height:25px;
		background-color:#fcfcfc;
		border:1px solid #F7F7F7;
		color:#5B1BDF;
		vertical-align:middle;
		line-height:25px;
	}
	.leftsection
	{
		/*height:350px;*/
		height:300px;
		width:20%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
		border-right:1px solid #CCCCCC;
	}
	.contenttsection
	{
		/*height:350px;*/
		height:300px;
		width:49%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-left:1px;
		margin-top:2px;
		margin-right:1px;
		border-right:1px solid #CCCCCC;
	}
	.rightsection
	{
		/*height:350px;*/
		height:300px;
		width:30%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.topcontentarea
	{
		/*height:320px;*/
		height:270px;
		width:98%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.leftsectionheader
	{
		height:25px;
		background-color:#03aa9f;
		border:1px solid #03aa9f;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.contenttopheader
	{
		height:25px;
		background-color:#ef535e;
		border:1px solid #ef535e;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.rightsectionheader
	{
		height:25px;
		background-color:#f39c12;
		border:1px solid #f39c12;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.contentbottompheader
	{
		height:25px;
		width:99%;
		background-color:#008dd5;/*#39b5b9;*/
		/*background:url(images/head_bg.png);
		background-repeat:repeat-x;
		background-size:2%;*/
		border:1px solid #008dd5;
		float:left;
		color:#ffffff;
		vertical-align:middle;
		line-height:25px;
		text-align:center;
	}
	.bottomcontentarea
	{
		/*height:320px;*/
		height:270px;
		width:100%;
		color:#03a9f4;
		background-color:#F7F7F7;
		vertical-align:middle;
		line-height:25px;
		float:left;
		margin-top:2px;
	}
	.leftdivmenuhead
	{
		height:47px;
		background-color:#3198db;
		margin-top:0px;
		line-height:47px;
		text-align:center;
		color:white;
	}
	.leftdivmenuhead1
	{
		height:35px;
		background-color:#3598db;/*#63a8eb;*/
		margin-top:1px;
		line-height:35px;
		text-align:center;
		color:#ffffff;
	}
	.leftdivmenu
	{
		/*height:47px;*/
		min-height:35px;
		background-color:#FFFFFF;
		border-bottom:1px solid #E4E4E4;
		vertical-align:middle;
		/*line-height:35px;*/
		line-height:25px;
		text-align:center;
		cursor:pointer;
		color:#0E02EA;
		font-weight:bold;
		font-size:11px;
	}
	.leftdivmenu:hover
	{
		background-color:#EFEFEF;
		color:#062086;
	}
	.stackbarchart
	{
		/*height:240px;*/
		/*height:330px;*/
		height:273px;
		overflow:scroll;
	}
	
	
	.stackbarchart-modal-section
	{
		/*height:240px;*/
		height:90%;
		width:100%;
	}
	.stackbarchart-modal
	{
		/*height:240px;*/
		height:90%;
		width:99%;
	}
	.stackbarchartHead
	{
		background-color:#0B79B5;
		padding-top:5px;
		padding-bottom:5px;
		padding-left:5px;
		width:99%;
		border:1px solid #FFFFFF;
		font-size:15px;
		font-weight:bold;
		color:#FFFFFF;
	}
	.contentbottom
	{
		width:100%;
		height:250px;
		padding-top:330px;
	}
	.stacked-barchart
	{
		width:99%;
		height:250px;
	}
	.workname-title
	{
		font-weight:bold;
		color:#C70360;
		width:99%;
		padding-left:3px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		
	}
</style>
<link type='text/css' href='css/basic-dashboard.css' rel='stylesheet' media='screen' />
<script type='text/javascript' src='js/basic_model_jquery.js'></script>
<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<script>
	function changeData(obj)
	{
		var sheetid = obj.id;
		//var workname = document.getElementById("txt_shortname_"+sheetid).value ;
		var url = "dashboard.php?sheetid="+sheetid;//+"&workname="+workname;
		window.location.replace(url);
	}
	function goBack(){
	   	url = "MyViewWorks.php";
		window.location.replace(url);
	}
	function goHome(){
	   	url = "WorkStatusList.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript"> 
$(function () { 
    $('#barchart').highcharts({
		chart: {
			backgroundColor: '#ffffff',
            options3d: {
                enabled: true,
                alpha: 0
            }
        },
		exporting: {
         enabled: false
		},
        title: {
            text: ''
        },
		subtitle: {
            text: '',
        },
		yAxis: {
			title: {
				text: 'Billed Values in Lakhs ( Rs ) ',
				style: {
                   color: '#221F1F',
					fontSize: "12px",
					fontWeight: "bold"
                }
			}
		},
        xAxis: {
            categories: ['RAB-2','RAB-3','RAB-4','RAB-5','RAB-6','RAB-7','RAB-8','RAB-9','RAB-10','RAB-11','RAB-13','RAB-14'] ,
        },
		
        labels: {
            items: [{
                html: 'Total fruit consumption',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
                }
            }]
        },
        series: [{
            type: 'column',
            name: 'General',
			color: '#2874C2',
            data: [240.78,419.15,546.78,609.43,580.53,377.03,342.52,304.26,202.16,208.8,269.52,244.64] 
        }, {
            type: 'column',
            name: 'Steel',
			color: '#4CAC2A',
            data: [58.54,106.93,276.41,398.08,547.63,371.61,360.09,364.38,459.76,343.21,371.03,337.17] 
        }, {
            type: 'column',
            name: 'Structural Steel',
			color: '#F73731',
            data: [78.54,16.53,76.41,98.08,47.63,71.61,60.09,564.38,159.76,143.21,271.03,237.17] 
        }, 
		]
    });
	
	$('#horiz-barchart-modal').click(function (e){
		$('#topcontentarea-modal-section').modal(); 
		$('#barchart-modal').highcharts({
			chart: {
				backgroundColor: 'none',
				options3d: {
					enabled: true,
					alpha: 0
				}
			},
			exporting: {
			 enabled: false
			},
			title: {
				text: ''
			},
			subtitle: {
				text: '',
			},
			xAxis: {
				categories: ['RAB-2','RAB-3','RAB-4','RAB-5','RAB-6','RAB-7','RAB-8','RAB-9','RAB-10','RAB-11','RAB-13','RAB-14'] ,
				labels: {
					style: {
							color: '#000',
							fontSize: "11px",
							fontWeight: "bold"
						}
				}

			},
			yAxis: {
				labels: {
					style: {
							color: '#000',
							fontSize: "11px",
							fontWeight: "bold"
						}
				},
				title: {
					text: 'Billed Values in Lakhs ( Rs ) ',
					style: {
					   color: '#221F1F',
						fontSize: "12px",
						fontWeight: "bold"
					}
				}
			},
			
			labels: {
				items: [{
					html: 'Total fruit consumption',
					style: {
						color: (Highcharts.theme && Highcharts.theme.textColor) || 'red'
					}
				}]
			},
			series: [{
				type: 'column',
				name: 'General',
				color: '#2874C2',
				data: [240.78,419.15,546.78,609.43,580.53,377.03,342.52,304.26,202.16,208.8,269.52,244.64] 
			}, {
				type: 'column',
				name: 'Steel',
				color: '#4CAC2A',
				data: [58.54,106.93,276.41,398.08,547.63,371.61,360.09,364.38,459.76,343.21,371.03,337.17] 
			}, {
				type: 'column',
				name: 'Structural Steel',
				color: '#F73731',
				data: [78.54,16.53,76.41,98.08,47.63,71.61,60.09,564.38,159.76,143.21,271.03,237.17] 
			}, 
			]
		});
	});
	
	$('#piechart').highcharts({
        chart: {
            type: 'pie',
			backgroundColor: 'none',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
		exporting: {
			 enabled: false
		},
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        plotOptions: {
            pie: {
                innerSize: 0,
                depth: 45
            }
        },
        series: [{
            name: 'Paid %',
			data:[['5.3 - 59.49%',59.49],['5.7.1 - 87.76%',87.76],['5.9.1 - 5.17%',5.17],['5.10.1 - 21.36%',21.36],['7.1.1 - 26.81%',26.81],['7.1.2 - 20.95%',20.95],['8.1.2 - 42.52%',42.52],['8.2.3 - 78.6%',78.6],['8.2.4 - 75.21%',75.21],['25.19.1 - 75.8%',75.8]]
        }]
    });
	
	$('#horiz-piechart-modal').click(function (e){
		$('#bottomcontentarea-modal-section').modal();
		$('#piechart-modal').highcharts({
			chart: {
				type: 'pie',
				backgroundColor: 'none',
				options3d: {
					enabled: true,
					alpha: 45
				}
			},
			tooltip: {
           // headerFormat: '<b>{point.x}</b><br/>',
		   	style: {
				color: '#000',
				fontSize: "12px",
				fontWeight: "bold"
			},
            pointFormat: '<b>{point.myData}</b><br/>{series.name}: {point.y}'
			},
			title: {
				text: ''
			},
			exporting: {
			 enabled: false
			},
			subtitle: {
				text: 'PIE Chart',
				style: {
							color: '#000',
							fontSize: "12px",
							fontWeight: "bold"
						}
			},
			plotOptions: {
				pie: {
					innerSize: 0,
					depth: 45
				}
			},
			series: [{
				name: 'Paid %',
				data:[['5.3 - 59.49%',59.49],['5.7.1 - 87.76%',87.76],['5.9.1 - 5.17%',5.17],['5.10.1 - 21.36%',21.36],['7.1.1 - 26.81%',26.81],['7.1.2 - 20.95%',20.95],['8.1.2 - 42.52%',42.52],['8.2.3 - 78.6%',78.6],['8.2.4 - 75.21%',75.21],['25.19.1 - 75.8%',75.8]]
			}]
		});
	});
	
	Highcharts.chart('stacked-barchart', {
		chart: {
			type: 'column'
		},
		title: {
			text: ''
		},
		exporting: {
			 enabled: false
		},
		xAxis: {
			title: {
				text: 'Item No.',
				style: {
					color: '#000',
					fontSize: "12px",
					fontWeight: "bold"
				}
			},
			categories: ['1.1', '1.2', '2', '3', '4', '5', '5.1', '5.2', '5.3', '6', '7.1', '7.2', '8', '9', '10', '11', '12', '13', '14', '15']
		},
		yAxis: {
			min: 0,
			title: {
				text: ''
			},
			labels:{enabled: false}
		},
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 25,
			floating: true,
			backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false
		},
		tooltip: {
			pointFormat: '{series.name}: {point.y}'
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#313132',
					style: {
                   	 textShadow: false 
                	}
				}
			}
		},
			series: [{
				name: 'Aggrement Qty',
				data: [50, 100, 40, 75, 28, 50, 100, 40, 75, 28, 50, 100, 40, 75, 28, 50, 100, 40, 75, 28],
				color: '#1A87F7'
			}, {
				name: 'Executed Qty',
				data: [20, 20, 30, 20, 10, 20, 20, 30, 20, 10, 20, 20, 30, 20, 10, 20, 20, 30, 20, 10],
				color: '#994FFC'
			}, {
				name: 'Balance Qty',
				data: [30, 80, 10, 55, 18, 30, 80, 10, 55, 18, 30, 80, 10, 55, 18, 30, 80, 10, 55, 18],
				color: '#FC3D66'
			}]
	});
	
	
	$('#horiz-stacked-barchart-modal').click(function (e){
		$('#stacked-barchart-modal-section').modal();
		Highcharts.chart('stacked-barchart-modal', {
			chart: {
				type: 'column',
				backgroundColor: 'none'
			},
			title: {
				text: ''
			},
			exporting: {
			 enabled: false
			},
			xAxis: {
				labels: {
					style: {
							color: '#565656',
							fontSize: "12px",
							fontWeight: "bold"
						}
				},
				title: {
					text: 'Item No.',
					style: {
							color: '#000',
							fontSize: "12px",
							fontWeight: "bold"
						}
				},
				categories: ['1.1', '1.2', '2', '3', '4', '5', '5.1', '5.2', '5.3', '6', '7.1', '7.2', '8', '9', '10', '11', '12', '13', '14', '15']
			},
			yAxis: {
				min: 0,
				title: {
					text: ''
				},
				labels:{enabled: false}
			},
			legend: {
				align: 'right',
				x: -30,
				verticalAlign: 'top',
				y: 25,
				floating: true,
				backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				borderColor: '#CCC',
				borderWidth: 1,
				shadow: false
			},
			tooltip: {
				style:{
					fontSize: "12px",
					fontWeight: "bold"
				},
            	pointFormat: '{point.myData}<br/><font style="color:#0386C8">{point.myData1}</font><br/><font style="color:#01905C">{point.myData2}</font><br/><font style="color:#C9042F">{point.myData3}</font><br/>'
			},
			plotOptions: {
				column: {
					stacking: 'normal',
					dataLabels: {
						enabled: true,
						color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#313132',
						style: {
						 textShadow: false 
						}
					}
				}
			},
			series: [{
				name: 'Aggrement Qty',
				data: [50, 100, 40, 75, 28, 50, 100, 40, 75, 28, 50, 100, 40, 75, 28, 50, 100, 40, 75, 28],
				color: '#1A87F7'
			}, {
				name: 'Executed Qty',
				data: [20, 20, 30, 20, 10, 20, 20, 30, 20, 10, 20, 20, 30, 20, 10, 20, 20, 30, 20, 10],
				color: '#994FFC'
			}, {
				name: 'Balance Qty',
				data: [30, 80, 10, 55, 18, 30, 80, 10, 55, 18, 30, 80, 10, 55, 18, 30, 80, 10, 55, 18],
				color: '#FC3D66'
			}]
		});
	});
	
});
</script>
<script src="dashboard-wcms/highcharts.js"></script>
<script src="dashboard-wcms/highcharts-3d.js"></script>
<script src="dashboard-wcms/modules/exporting.js"></script>
<script src="dashboard-wcms/lib/amcharts.js"></script>
<script src="dashboard-wcms/lib/pie.js"></script>
<script>
var chartData = <?php echo $DonutJsonData; ?>; 
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "pie",
  "theme": "none",
  "titles": [ {
    "text": "<?php echo "Work Order Cost is Rs. ".number_format($WorkOrderCost,2); ?>",
    "size": 11
  },{
    "text": "<?php echo $DevCostData; ?>",
    "size": 11,
	"color": "red",
  },{
    "text": "<?php echo $TotalCostData; ?>",
    "size": 11,
	"color": "darkgreen",
  }],
  "dataProvider": chartData,
  "valueField": "Amount",
  "titleField": "RAB",
  "startEffect": "elastic",
  "startDuration": 2,
  "labelRadius": 15,
  "innerRadius": "50%",
  "depth3D": 10,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "labelText": "",
  "angle": 15,
  "export": {
    "enabled": true
  }
} );

$(function () { 
	$('#donut-chart-modal').click(function (e) 
	{ 
			$('#chartdiv-modal-section').modal();
			var chart = AmCharts.makeChart( "chartdiv-modal", {
			  "type": "pie",
			  "theme": "none",
			  "titles": [ {
				"text": "<?php echo "Work Order Cost is Rs. ".number_format($WorkOrderCost,2); ?>",
				"size": 11
			  },{
				"text": "<?php echo $DevCostData; ?>",
				"size": 11,
				"color": "red",
			  },{
				"text": "<?php echo $TotalCostData; ?>",
				"size": 11,
				"color": "darkgreen",
			  }],
			  "dataProvider": chartData,
			  "valueField": "Amount",
			  "titleField": "RAB",
			  "startEffect": "elastic",
			  "startDuration": 2,
			  "labelRadius": 15,
			  "innerRadius": "50%",
			  "depth3D": 10,
			  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%) <br/>Paid Date - [[Paid Date]]</span>",
			  "labelText": "[[title]] : Rs. [[value]]",
			  "labelColorField":"#000000",
			  "fontSize":13,
			  "angle": 15,
			  "export": {
				"enabled": true
			  }
			} );	
	
	
	
	});
});
</script>
<?php //echo "HI".$DonutJsonData; exit; ?>
<style>
.popuptitle
{
	background-color:#0A9CC5;
	font-weight:bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	line-height:25px;
	border:1px solid #9b9da0;
}
.transparent_class {
  /* IE 8 */
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";

  /* IE 5-7 */
  filter: alpha(opacity=50);

  /* Netscape */
  -moz-opacity: 0.5;

  /* Safari 1.x */
  -khtml-opacity: 0.5;

  /* Good browsers */
  opacity: 0.5;
}
.alert-table tr th{
	font-weight:bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	line-height:20px;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#035a85), to(#1f80ad));
	background: -webkit-linear-gradient(top, #1f80ad, #035a85);
	background: -moz-linear-gradient(top, #1f80ad, #035a85);
	background: -ms-linear-gradient(top, #1f80ad, #035a85);
	background: -o-linear-gradient(top, #1f80ad, #035a85);
	padding:5px;
	text-align:center;
}
.alert-table tr td {
	padding-left:2px;
	border:1px solid #DBDBDB;
	border-collapse:collapse;
	font-size:11px;
	padding:8px;
	font-weight:bold; 
	background:#ffffff;
}
.alert-table table{
	background:#00FF66;
}
.table-head{
}
</style>
<body class="page1" id="top">
 <!--==============================header=================================-->
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
  <?php include_once("Menu.php"); ?>
  <!--==============================Content=================================-->
  <div class="content">
  	<?php include "MainMenu.php"; ?>
    <div class="container_12">
       <div class="grid_12">
         <blockquote id="bq1" class="bq1 message" style="border:1px solid #FFFFFF; background-color:#FFFFFF; min-height:auto; padding-left:36px;">
		 	<div class="dashboardheader">
				&nbsp;Name of Work : <?php echo $NameOfWork; ?>
			</div>
<input type="hidden" name="txt_ac_count" id="txt_ac_count" value="<?php echo $A_C_Count; ?>">
<?php 
//echo "ferwgrg".$_SESSION['staff_section'];
$_SESSION['staff_section'] = 1;
if($_SESSION['staff_section'] == 2)
{
?>

	<!--<div style="text-align:right; font-weight:normal;">&nbsp;<a href="ChangePassword.php"><u>Change Password</u></a>&nbsp;&nbsp;&nbsp;</div>-->
	<div class="label" style="text-align:center">Welcome Account Section User - Shri. / Smt. <?php echo $_SESSION['staffname']; ?></div>
	<div style="width:100%; height:80%" class="">
		<img src="images/accounts_bg.jpg" height="100%" width="100%">
	</div>
<?php
	$count_sa = 0;  $alet_msg_acc = ""; $ac2 = 1;
	$alet_msg_acc .= "<table bgcolor='red' style='border: 1px solid #A9A9A9;' class='label alert-table'>";
	echo "<table bgcolor='red' style='border: 1px solid #A9A9A9; width:100%; display:none;' class='label alert-table' id='AccModal'>";
	echo "<tr><th colspan='4' class='table-head' align='center'>Notification : MBooks Waiting for Approval</th></tr>";
	//$select_sheet_sa_query = "select distinct(sheetid), rbn from measurementbook_temp";
	
	$select_sheet_sa_query = "select distinct(a.sheetid), a.rbn from measurementbook_temp a INNER JOIN al_as b ON (b.sheetid = a.sheetid) where a.rbn = b.rbn and b.status = ".$_SESSION['levelid'];
	//echo $select_sheet_sa_query;exit;
	$select_sheet_sa_sql = mysqli_query($dbConn,$select_sheet_sa_query);
	if($select_sheet_sa_sql == true)
	{
		if(mysqli_num_rows($select_sheet_sa_sql)>0)
		{
			while($SaSheetList = mysqli_fetch_object($select_sheet_sa_sql))
			{
				$sheetid_sa = $SaSheetList->sheetid;
				$rbn_sa 	= $SaSheetList->rbn;
				if($staff_levelid == $min_levelid)
				{
					//$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where (mb_ac = 'SA' OR sa_ac = 'SA' OR ab_ac = 'SA') and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";// and level = ".$_SESSION['levelid'];
					$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where (mb_ac = 'SA' OR sa_ac = 'SA' OR ab_ac = 'SA') and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";// and level = ".$_SESSION['levelid'];
				}
				else
				{
					//$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where level_status = 'P' and level = '$staff_levelid' and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";// and level = ".$_SESSION['levelid'];
					$select_send_account_query = "select COUNT(*) as count_sa from send_accounts_and_civil where (mb_ac = 'SA' OR sa_ac = 'SA' OR ab_ac = 'SA') and sheetid = '$sheetid_sa' and rbn = '$rbn_sa'";// and level = ".$_SESSION['levelid'];
				}
				//echo $select_send_account_query."<br/>";
				$select_send_account_sql = mysqli_query($dbConn,$select_send_account_query );
				if($select_send_account_sql == true)
				{
					$SaList = mysqli_fetch_object($select_send_account_sql);
					$count_sa_temp = $SaList->count_sa;
					$count_sa = $count_sa+$count_sa_temp;
					if($count_sa_temp>0)
					{
						$sheet_data_sa 		= 	getsheetdata($sheetid_sa);
						$exp_sheet_data_sa 	= 	explode("@#*#@",$sheet_data_sa);
						$short_name_sa 		= 	$exp_sheet_data_sa[0];
						$tech_sanct_sa 		= 	$exp_sheet_data_sa[1];
						$aggre_no_sa 		= 	$exp_sheet_data_sa[3];
//$alet_msg_acc .= "<tr><td colspan='4' align='center' style='background-color:#E6E6FA;'>".($ac2).") ".$short_name_sa."</td></tr>";
//$alet_msg_acc .= "<tr><td colspan='4' align='center'>".$short_name_sa." (".$aggre_no_sa.") ".$count_sa." - MBooks are waiting for Accounts Approval </td></tr>";
$alet_msg_acc .= "<tr><td colspan='4' align='left'>".$short_name_sa." : <font style='color:#238FFC;'><span class='round-red'>".$count_sa_temp."</span> - MBooks are waiting for Accounts Approval <font></td></tr>";
echo "<tr><td colspan='4' align='left'>".$short_name_sa." : <font style='color:#238FFC;'><span class='round-red'>".$count_sa_temp."</span> - MBooks are waiting for Accounts Approval <font></td></tr>";
						$ac2++;
					}
				}
				//echo $select_send_account_query;
			}
		}
	}
	$alet_msg_acc .= "</table>";
	echo "</table>";
	//echo $count_sa;
}
else
{
?>
			<div class="leftsection" <?php if($WorkOrderCost >0 ) { ?> id="donut-chart-modal" <?php } ?>>
				<div class="leftsectionheader">
					Analysis of RAB's Amount & %
				</div>
				<!--<div class="leftdivmenuhead1">&nbsp;Major Works</div>-->
				<?php if($WorkOrderCost == 0) { ?>
					<div class="stackbarchart">
						<img src="images/donutchart-nodata.png" width="100%" height="90%">
					</div>
				<?php } else { ?>
					<div class="stackbarchart" id="chartdiv">
					
					</div>
				<?php } ?>
				<div class="stackbarchart-modal-section" id="chartdiv-modal-section" style="display:none;">
					<div class="stackbarchartHead">Analysis of RAB's Amount & %</div>
					<div class="workname-title" align="center">
						Name of Work : <?php echo $NameOfWork; ?>
						
					</div>
					<div class="stackbarchart-modal" id="chartdiv-modal" style="height:500px;">
					</div>
				</div>
				
			</div>
			<div class="contenttsection" <?php if($PieBarChat == 'yes'){ ?> id="horiz-barchart-modal" <?php } ?>>
				<div class="contenttopheader">
					<?php //echo $ItemNoStr; ?> Analysis of General / Steel / Structural Steel
				</div>
				<div class="topcontentarea" <?php if($PieBarChat == 'yes'){ ?> id="barchart" <?php } ?>>
					<?php if($PieBarChat != 'yes'){ echo '<img src="images/barchart-nodata.png" width="100%" height="100%">'; } ?>
				</div>
				
				<div class="topcontentarea-modal-section" <?php if($PieBarChat == 'yes'){ ?> id="topcontentarea-modal-section" <?php } ?> style="display:none">
					<div class="stackbarchartHead"><?php //echo $short_name; ?>Analysis of General / Steel / Structural Steel</div>
					<div class="workname-title" align="center">
						Name of Work : <?php echo $NameOfWork; ?>
						
					</div>
					<div class="topcontentarea-modal" <?php if($PieBarChat == 'yes'){ ?> id="barchart-modal" <?php } ?>>
				
					</div>
				</div>
			</div>
			
			
			<div class="rightsection" <?php if($PieBarChat == 'yes'){ ?> id="horiz-piechart-modal" <?php } ?>>
				<div class="rightsectionheader">
					Analysis of Item Wise Paid Qty %
				</div>
				<div class="bottomcontentarea" <?php if($PieBarChat == 'yes'){ ?> id="piechart" <?php } ?>>
					<?php if($PieBarChat != 'yes'){ echo '<img src="images/piechart-nodata.png" width="100%" height="100%">'; } ?>
				</div>
				<div class="bottomcontentarea-modal-section" <?php if($PieBarChat == 'yes'){ ?> id="bottomcontentarea-modal-section" <?php } ?> style="display:none">
					<div class="stackbarchartHead">Analysis of Item Wise Paid Qty % </div>
					<div class="workname-title" align="center">
						Name of Work : <?php echo $NameOfWork; ?>
						
					</div>
					<div class="bottomcontentarea-modal" <?php if($PieBarChat == 'yes'){ ?> id="piechart-modal" <?php } ?>>
				
					</div>
				</div>
			</div>
			
			
			<div class="contentbottompheader">
				Analysis of Item Wise Qty - As of Now Executed
			</div>
			<div class="contentbottom" <?php if($content == 'yes'){ ?> id="horiz-stacked-barchart-modal" <?php } ?>>
				<div class="stacked-barchart" <?php if($content == 'yes'){ ?> id="stacked-barchart" <?php } ?>>
					<?php if($content != 'yes'){ echo '<img src="images/stackedbarchart-nodata.png" width="100%" height="100%">'; } ?>
				</div>
				
				<div class="stacked-barchart-modal-section" <?php if($content == 'yes'){ ?> id="stacked-barchart-modal-section" <?php } ?> style="display:none">
					<div class="stackbarchartHead">Analysis of Item Wise Qty - As of Now Executed</div>
					<div class="workname-title" align="center">
						Name of Work : <?php echo $NameOfWork; ?>
						
					</div>
					<div class="stacked-barchart-modal" <?php if($content == 'yes'){ ?> id="stacked-barchart-modal" <?php } ?>>
				
					</div>
				</div>
				
			</div>
			
			
		
		<?php
		/*$prev_sheetid_acc = ""; $RCount = 0;  $acc_remark_cnt = 0;// $prev_fromdate_acc = ""; $prev_todate_acc = "";
		$Acc_Sheet = array(); $Acc_FromDate = array(); $Acc_ToDate = array(); $Acc_Rbn = array(); $Acc_RConut = array();
		$alet_msg = ""; 
		$alet_msg .= "<table width='650px' style='border: 1px solid #A9A9A9' class='label'>";
		$select_sheet_acc_query = "select a.sheetid, DATE_FORMAT(a.fromdate,'%Y-%m-%d') as fromdate, DATE_FORMAT(a.todate,'%Y-%m-%d') as todate, a.rbn, a.accounts_remarks,
									b.assigned_staff from measurementbook_temp a inner join sheet b on (a.sheetid = b.sheet_id)";
		$select_sheet_acc_sql = mysqli_query($dbConn,$select_sheet_acc_query);
		if($select_sheet_acc_sql == true)
		{
			if(mysqli_num_rows($select_sheet_acc_sql)>0)
			{
				while($AccSheetList = mysqli_fetch_object($select_sheet_acc_sql))
				{
					$assigned_staff = $AccSheetList->assigned_staff;
					$sheetid_acc 	= $AccSheetList->sheetid;
					$fromdate_acc 	= $AccSheetList->fromdate;
					$todate_acc 	= $AccSheetList->todate;
					$rbn_acc 		= $AccSheetList->rbn;
					$remarks_acc 	= $AccSheetList->accounts_remarks;
					if($sheetid_acc != $prev_sheetid_acc)
					{
						$AssignStaff = explode(",",$assigned_staff);
						//print_r($AssignStaff);exit;
						if(in_array($_SESSION['sid'],$AssignStaff)){
							array_push($Acc_Sheet,$sheetid_acc);
						}
						$Acc_FromDate[$sheetid_acc] = $fromdate_acc;
						$Acc_ToDate[$sheetid_acc] 	= $todate_acc;
						$Acc_Rbn[$sheetid_acc] 		= $rbn_acc;
					}
					if(($sheetid_acc != $prev_sheetid_acc) && ($prev_sheetid_acc != ""))
					{
						$Acc_RConut[$prev_sheetid_acc]	= $RCount;
						$RCount = 0;
					}
					if($remarks_acc != "")
					{
						$RCount++; 
						$acc_remark_cnt++; 		//=================> This is very very important for open dialog window
					}
					$prev_sheetid_acc 	= $sheetid_acc;
					//$prev_fromdate_acc 	= $fromdate_acc;
					//$prev_todate_acc 	= $todate_acc;
				}
				$Acc_RConut[$prev_sheetid_acc]	= $RCount;
				$RCount = 0;
				//print_r($Acc_FromDate);
				//print_r($Acc_ToDate);
				$sht_c = 1;
				for($ac1 = 0; $ac1<count($Acc_Sheet); $ac1++)
				{
					$sheetid_status 	= 	$Acc_Sheet[$ac1];
					$rbn_status 		= 	$Acc_Rbn[$sheetid_status];
					$sheet_data 		= 	getsheetdata($sheetid_status);
					$exp_sheet_data 	= 	explode("@#*#@",$sheet_data);
					$short_name_status 	= 	$exp_sheet_data[0];
					$memo_pay_edit = 0;
					$select_edit_memopayment_query = "select * from memo_payment_accounts_edit where edit_flag = 'EDITED' and sheetid = '$sheetid_status' and rbn = '$rbn_status'";
					$select_edit_memopayment_sql = mysqli_query($dbConn,$select_edit_memopayment_query);
					if($select_edit_memopayment_sql == true)
					{
						if(mysqli_num_rows($select_edit_memopayment_sql)>0)
						{
							$memo_pay_edit = 1;
						}
					}
					$select_date_query = "Select min(DATE_FORMAT(fromdate,'%Y-%m-%d')) as fromdate, max(DATE_FORMAT(todate,'%Y-%m-%d')) as todate from measurementbook_temp where sheetid = '$sheetid_status'";
					$select_date_sql = mysqli_query($dbConn,$select_date_query);
					if($select_date_sql == true)
					{
						if(mysqli_num_rows($select_date_sql)>0)
						{
							$DateList = mysqli_fetch_object($select_date_sql);
							$min_fromdate = $DateList->fromdate;
							$max_todate = $DateList->todate;
							$select_mbremark_query = "select mbookdetail.accounts_remarks from mbookdetail 
							INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid) where mbookheader.sheetid = '$sheetid_status' 
							and mbookheader.date  >= '$min_fromdate' AND mbookheader.date  <= '$max_todate' and mbookdetail.accounts_remarks != ''";
							$select_mbremark_sql = mysqli_query($dbConn,$select_mbremark_query);
							$MBRArr = array();
							if($select_mbremark_sql == true)
							{
								if(mysqli_num_rows($select_mbremark_sql)>0)
								{
									while($MbRemark = mysqli_fetch_object($select_mbremark_sql))
									{
										$MbRemark_St 		= $MbRemark->accounts_remarks;			
										$Exp_acc_remarks 	= explode("@R@",$MbRemark_St);
										$mbookremarks 		= $Exp_acc_remarks[0];
										$mbookremarks_mbook = $Exp_acc_remarks[1];
										array_push($MBRArr,$mbookremarks_mbook);
										$acc_remark_cnt++; $mb_c++;						//=================> This is very very important for open dialog window
									}
								}
							}
						}
					}
					// print_r($MBRArr);//echo "hai";
					$select_subabstract_query = "select COUNT(accounts_remarks) as sacount from mbookgenerate_staff where sheetid = '$sheetid_status' and rbn = '$rbn_status' and accounts_remarks != ''";
					$select_subabstract_sql = mysqli_query($dbConn,$select_subabstract_query);
					if($select_subabstract_sql == true)
					{
						$SaRemark = mysqli_fetch_object($select_subabstract_sql);
						$SaRemark_Count = $SaRemark->sacount;					//=================> This is very very important for open dialog window
						$acc_remark_cnt = $acc_remark_cnt+$SaRemark_Count;
					}
					

					$select_status_mbook_query 	= "select mbookno, zone_id, mtype, genlevel, mb_ac, sa_ac, ab_ac from send_accounts_and_civil 
													where sheetid = '$sheetid_status' and rbn = '$rbn_status' ORDER BY mbookno";
					$select_status_mbook_sql 	= mysqli_query($dbConn,$select_status_mbook_query);
					if($select_status_mbook_sql == true)
					{
						if(mysqli_num_rows($select_status_mbook_sql)>0)
						{
$alet_msg .= "<tr><td colspan='3' align='left' style='background-color:#ECECEC; font-size:13px;'>&nbsp;&nbsp;".$sht_c.") ".$short_name_status."</td></tr>";
							if($memo_pay_edit == 1)
							{
$alet_msg .= "<tr><td colspan='3' align='center' style='color:red; font-size:13px;'> Memo of Payment has been edited by Accounts. Please check your Pass Order. </td></tr>";
							}
							$sht_c++;
							while($StatusList = mysqli_fetch_object($select_status_mbook_sql))
							{
								$mb_ac = $StatusList->mb_ac;
								$sa_ac = $StatusList->sa_ac;
								$ab_ac = $StatusList->ab_ac;
								$mbookno_status 	= $StatusList->mbookno;
								$mtype_status 		= $StatusList->mtype;
								$genlevel_status 	= $StatusList->genlevel;
								$zone_id_status 	= $StatusList->zone_id;
								
								//$MBremark_count = $MBRArr
								
								if($mtype_status == "A")
								{
									$mtype_status_print = " Abstract MBook";
								}
								else if($mtype_status == "S")
								{
									$mtype_status_print = " - Steel MBook ";
								}
								else if($mtype_status == "G")
								{
									$mbook_uniq_count = array_count_values($MBRArr);  //   This is used to get count remarks for each mbook
									//print_r($mbook_uniq_count); echo "<br/>";
									if($genlevel_status == "staff")
									{
										$mtype_status_print = " - General MBook ";
									}
									if($genlevel_status == "composite")
									{
										$mtype_status_print = " Sub-Abstract MBook";
									}
								}
								else
								{
									$mtype_status_print = "";
								}
								
								$Mb_Remark_Count = $mbook_uniq_count[$mbookno_status];
								if(($Mb_Remark_Count != 0) && ($Mb_Remark_Count != ""))
								{
									$Mbook_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$Mb_Remark_Count." Comment/s </u></a>";
								}
								else
								{
									$Mbook_comment_print = "";
								}
								
								if(($SaRemark_Count != 0) && ($SaRemark_Count != ""))
								{
									$SubAbs_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$SaRemark_Count." Comment/s </u></a>";
								}
								else
								{
									$SubAbs_comment_print = "";
								}
								
								
								$Abs_Remarks_Count = $Acc_RConut[$sheetid_status];
								if(($Abs_Remarks_Count != 0) && ($Abs_Remarks_Count != ""))
								{
									$Abstract_comment_print = "<a href='AccountsComments_View.php?workno=".$sheetid_status."'><u>".$Abs_Remarks_Count." Comment/s </u></a>";
								}
								else
								{
									$Abstract_comment_print = "";
								}
								
								
									if($mb_ac == 'AC')
									{ 
										if($Mbook_comment_print != "")
										{
											$mb_status = "Accepted with ";
										}
										else
										{
											$mb_status = "Accepted";
										}
										
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$mb_status.$Mbook_comment_print."</td></tr>";
										$acc_remark_cnt++; 
									}
									else if($mb_ac == 'SC')
									{  
										if($Mbook_comment_print != "")
										{
											$mb_status = "Accounts Returned ";
										}
										else
										{
											$mb_status = "Accounts Returned";
										}
										//$mb_status = "";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$mb_status.$Mbook_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$mb_status = "";
									}
									
									if($sa_ac == 'AC')
									{ 
									
										if($SubAbs_comment_print != "")
										{
											$sa_status = "Accepted with ";
										}
										else
										{
											$sa_status = "Accepted";
										}
										
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$sa_status.$SubAbs_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else if($sa_ac == 'SC')
									{  
										$sa_status = "Accounts Returned";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$sa_status.$SubAbs_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$sa_status = "";
									}
									
									if($ab_ac == 'AC')
									{ 
										if($Abstract_comment_print != "")
										{
											$ab_status = "Accepted with ";
										}
										else
										{
											$ab_status = "Accepted";
										}
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:green;'>".$ab_status.$Abstract_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else if($ab_ac == 'SC')
									{  
										$ab_status = "Accounts Returned";
$alet_msg .= "<tr><td align='center'> MBook No. ".$mbookno_status."</td><td align='left'>".getzonename($sheetid_status,$zone_id_status).$mtype_status_print."</td><td style='color:red;'>".$ab_status.$Abstract_comment_print."</td></tr>";									
										$acc_remark_cnt++; 
									}
									else
									{  
										$ab_status = "";
									}
							}
						}
					}
					
				}
			}
		}
		$alet_msg .= "</table>";*/
 } ?>		
 
 			<div class="div12" align="center">
				<!--<input type="button" class="backbutton" name="back" id="back" value=" View All Works " onClick="goBack()"/>-->
				<input type="button" class="backbutton" name="home" id="home" value=" Back " onClick="goHome()"/>
				<div class="div12" align="center">&nbsp;</div>
			</div>
         </blockquote>
       </div>
    </div>
  </div>
 <!--==============================footer=================================-->
<footer>
	<div class="container_12" style="background:#035a85">
		<div class="grid_12">
			<div class="copy">
				 <a rel="nofollow" style="color:#C6C7C7; font-size:11px; font-weight:600; padding:2px 0px;">&copy; Designed & Developed by Lashron Technologies</a>
			</div>
		</div>
	</div>
</footer>
 
 <script>
	/*var htmlval_for_civil = "<?php echo $alet_msg; ?>";
	var remark_count_for_civil = "<?php echo $acc_remark_cnt; ?>";
	if(remark_count_for_civil>0)
	{
		swal({
			title: "<b>Accounts Comment Notification</b>",
			text: "<small><div style='height:400px; overflow:scroll'>"+htmlval_for_civil+"</div></small>",
			html: true
		});
	}*/
		
	/*var htmlval_for_accounts = "<?php echo $alet_msg_acc; ?>";
	var send_to_acco = "<?php echo $count_sa; ?>";
	//alert(send_to_acco)
	if(send_to_acco>0)
	{
		$('#AccModal').modal();
	}*/
	
 </script>
 
 <!--<style>
 	.sweet-alert
	{
		width:70%;
		left:32%;
		/*margin-left:-380px;*/
		top:850px;;
		padding:5px;
		/*height:400px;*/
		overflow-y:scroll;
		height:500px;
	}
	.sweet-alert h2
	{
		font-weight:bold;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:14px;
		color:#FFFFFF;
		line-height:30px;
		/* Safari 4-5, Chrome 1-9 */
		background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#035a85), to(#1f80ad));
		/* Safari 5.1, Chrome 10+ */
		background: -webkit-linear-gradient(top, #1f80ad, #035a85);
		/* Firefox 3.6+ */
		background: -moz-linear-gradient(top, #1f80ad, #035a85);
		/* IE 10 */
		background: -ms-linear-gradient(top, #1f80ad, #035a85);
		/* Opera 11.10+ */
		background: -o-linear-gradient(top, #1f80ad, #035a85);
	}
	div.sweet-alert tr, div.sweet-alert td
	{
		background-color:#F8F8FF;
		/*height:30px;*/
		padding:7px;
		border:1px solid #C1C1C1;
		vertical-align:middle;
		font-size:12px;
	}

 </style>-->
 </form>
</body>
</html>
<style>
	html{
		scrollbar-width: thin;
		scrollbar-color:#383939 #000;
	}
	html::-webkit-scrollbar-track{
		background-color: #000;
	}
	html::-webkit-scrollbar{
		width: 1px;
		background-color: #000;
	}
	html::-webkit-scrollbar-thumb{
		background-color:#383939;
	}
</style>
