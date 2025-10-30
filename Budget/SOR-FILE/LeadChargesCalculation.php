<?php
$LCItemDiesel 		= $GlobLCItemArr[0];
$LCItemEngineOil	= $GlobLCItemArr[1];
$LCItemBeldars 		= $GlobLCItemArr[2];
$LCItemTruckHC 		= $GlobLCItemArr[3];
$LCItemCoolie 		= $GlobLCItemArr[4];
$LCItemRateArr = array();
foreach($GlobLCItemArr as $LCItemId){
	$SelectQuery1 	= "select * from item_master where item_id = '$LCItemId'";
	$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			while($List1 = mysqli_fetch_object($SelectSql1)){
				$LCItemRateArr[$LCItemId][0] = $List1->item_code;
				$LCItemRateArr[$LCItemId][1] = $List1->price;
				$LCItemRateArr[$LCItemId][2] = $List1->unit;
			}
		}
	}
}
$DieselCost 		= $LCItemRateArr[$LCItemDiesel][1];
$EngineOilCost 		= $LCItemRateArr[$LCItemEngineOil][1];
$BeldersCost 		= $LCItemRateArr[$LCItemBeldars][1];
$TruckHireCharge 	= $LCItemRateArr[$LCItemTruckHC][1];
$CoolieCost 		= $LCItemRateArr[$LCItemCoolie][1];

$Kms = 1; $KmHr = 16; $PrevCostTrip = ""; $TotalCostTripForAvg = 0; $Cnt = 0; $AvgCnt = 1;
$CostTripArr = array(); $AvgCostArr = array();
for($i=1; $i<=30; $i++){ 
	$NoOftrip      	=	8/((2*($Kms/$KmHr))+1);
	$NoOftrip      	=	round($NoOftrip,2);
	$KmsDone        =	(2*$NoOftrip*$Kms)+6;
	$KmsDone        =	round($KmsDone,2);
	$QtyDiesel   	=	$KmsDone/5;
	$CostDiesel  	=	$QtyDiesel*$DieselCost;
	$CostDiesel  	=	round($CostDiesel,2);
	$MobOilQty   	=	$KmsDone/140;
	$MobOilQty   	=	round($MobOilQty,2);
	$CostMobOil  	=	$MobOilQty*$EngineOilCost;
	$CostMObOil  	=	round($CostMobOil,2);
	$CostMazClass	=	($BeldersCost*6);
	$CostMazClass	=	round($CostMazClass,2);
	$HireCharTruck	=	($TruckHireCharge*1);
	$HireCharTruck	=	round($HireCharTruck,2);
	$TotCost      	=	($CostDiesel+$CostMObOil+$CostMazClass+$HireCharTruck);
	$TotCost      	=	round($TotCost,2);
	$CostPerTrip  	=	($TotCost/$NoOftrip);
	$CostPerTrip  	=	round($CostPerTrip,2);
	$IncrCostOverPrev = 0;
	if($PrevCostTrip != ""){
	$IncrCostOverPrev = round(($CostPerTrip - $PrevCostTrip),2);
	}
	if($Kms > 5){
	$TotalCostTripForAvg = $TotalCostTripForAvg + $IncrCostOverPrev;
	$Cnt++;
	}
	if($Kms % 10 == 0){
	$AverageCost = round(($TotalCostTripForAvg / $Cnt),2);
	$AvgCostArr[$AvgCnt] = $AverageCost;
	$TotalCostTripForAvg = 0; $Cnt = 0; $AvgCnt++;
	}else{
	$AverageCost = "";
	}
	$CostTripArr[$Kms] = $CostPerTrip;
	$Kms  = $Kms+1;
	$KmHr = $KmHr + 0.5; 
	$PrevCostTrip = $CostPerTrip;
} 
$CostTrip1Km 		= round(($CostTripArr[1] + ($CostTripArr[1] * 15/100)),2);
$CostTrip2Km 		= round(($CostTripArr[2] + ($CostTripArr[2] * 15/100)),2);
$CostTrip3Km 		= round(($CostTripArr[3] + ($CostTripArr[3] * 15/100)),2);
$CostTrip4Km 		= round(($CostTripArr[4] + ($CostTripArr[4] * 15/100)),2);
$CostTrip5Km 		= round(($CostTripArr[5] + ($CostTripArr[5] * 15/100)),2);
$AvgCostBeyond5Km 	= round(($AvgCostArr[1] + ($AvgCostArr[1] * 15/100)),2);
$AvgCostBeyond10Km 	= round(($AvgCostArr[2] + ($AvgCostArr[2] * 15/100)),2);
$AvgCostBeyond20Km 	= round(($AvgCostArr[3] + ($AvgCostArr[3] * 15/100)),2);
														
$TWComRateFor1Km 		= round(($CostTrip1Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateFor2Km 		= round(($CostTrip2Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateFor3Km 		= round(($CostTrip3Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateFor4Km 		= round(($CostTrip4Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateFor5Km 		= round(($CostTrip5Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateForBeyond5Km 	= round(($AvgCostBeyond5Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateForBeyond10Km = round(($AvgCostBeyond10Km / $GlobNetPayableAfterVoidDed),2);
$TWComRateForBeyond20Km	= round(($AvgCostBeyond20Km / $GlobNetPayableAfterVoidDed),2);
														
$PSComRateFor1Km 		= round(($TWComRateFor1Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateFor2Km 		= round(($TWComRateFor2Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateFor3Km 		= round(($TWComRateFor3Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateFor4Km 		= round(($TWComRateFor4Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateFor5Km 		= round(($TWComRateFor5Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateForBeyond5Km 	= round(($TWComRateForBeyond5Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateForBeyond10Km = round(($TWComRateForBeyond10Km * 1.2 / (1 + 1 * 15/100)),2);
$PSComRateForBeyond20Km	= round(($TWComRateForBeyond20Km * 1.2 / (1 + 1 * 15/100)),2);
														
$FBComRateFor1Km 		= round(($TWComRateFor1Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateFor2Km 		= round(($TWComRateFor2Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateFor3Km 		= round(($TWComRateFor3Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateFor4Km 		= round(($TWComRateFor4Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateFor5Km 		= round(($TWComRateFor5Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateForBeyond5Km 	= round(($TWComRateForBeyond5Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateForBeyond10Km = round(($TWComRateForBeyond10Km * 1.22 / (1 + 1 * 15/100)),2);
$FBComRateForBeyond20Km	= round(($TWComRateForBeyond20Km * 1.22 / (1 + 1 * 15/100)),2);

$TWComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
$TWComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														
$PSComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.2),2);
$PSComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * 1.2),2);
														
$FBComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.22),2);
$FBComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * 1.22),2);

$TWLeadChrgeFor1Km = round(($TWComRateFor1Km + $TWComRateFor1KmAftCoolie),2);
$TWLeadChrgeFor2Km = round(($TWComRateFor2Km + $TWComRateFor2KmAftCoolie),2);
$TWLeadChrgeFor3Km = round(($TWComRateFor3Km + $TWComRateFor3KmAftCoolie),2);
$TWLeadChrgeFor4Km = round(($TWComRateFor4Km + $TWComRateFor4KmAftCoolie),2);
$TWLeadChrgeFor5Km = round(($TWComRateFor5Km + $TWComRateFor5KmAftCoolie),2);
														
$PSLeadChrgeFor1Km = round(($PSComRateFor1Km + $PSComRateFor1KmAftCoolie),2);
$PSLeadChrgeFor2Km = round(($PSComRateFor2Km + $PSComRateFor2KmAftCoolie),2);
$PSLeadChrgeFor3Km = round(($PSComRateFor3Km + $PSomRateFor3KmAftCoolie),2);
$PSLeadChrgeFor4Km = round(($PSComRateFor4Km + $PSComRateFor4KmAftCoolie),2);
$PSLeadChrgeFor5Km = round(($PSComRateFor5Km + $PSComRateFor5KmAftCoolie),2);
														
$FBLeadChrgeFor1Km = round(($FBComRateFor1Km + $FBComRateFor1KmAftCoolie),2);
$FBLeadChrgeFor2Km = round(($FBComRateFor2Km + $FBComRateFor2KmAftCoolie),2);
$FBLeadChrgeFor3Km = round(($FBComRateFor3Km + $FBComRateFor3KmAftCoolie),2);
$FBLeadChrgeFor4Km = round(($FBComRateFor4Km + $FBComRateFor4KmAftCoolie),2);
$FBLeadChrgeFor5Km = round(($FBComRateFor5Km + $FBComRateFor5KmAftCoolie),2);

$AbstractArr 	= array();
$AbstractArr['EW45a'][0] = $TWLeadChrgeFor1Km;
$AbstractArr['EW45a'][1] = $PSLeadChrgeFor1Km;

$AbstractArr['EW45b'][0] = $TWLeadChrgeFor2Km;
$AbstractArr['EW45b'][1] = $PSLeadChrgeFor2Km;

$AbstractArr['EW45c'][0] = $TWLeadChrgeFor3Km;
$AbstractArr['EW45c'][1] = $PSLeadChrgeFor3Km;

$AbstractArr['EW45d'][0] = $TWLeadChrgeFor4Km;
$AbstractArr['EW45d'][1] = $PSLeadChrgeFor4Km;

$AbstractArr['EW45e'][0] = $TWLeadChrgeFor5Km;
$AbstractArr['EW45e'][1] = $PSLeadChrgeFor5Km;
?>
