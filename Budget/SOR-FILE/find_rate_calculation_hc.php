<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$GroupId 	= $_POST['groupid'];
$Id 		= $_POST['id'];
$ParId 		= $_POST['parid'];
$RefId 		= $_POST['refid'];
//echo $GroupId;
$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DisQtyPerc = 0;
$SelectMasterQuery 	= "select * from datasheet_master_hc where ref_id = '$RefId' and group_id = '$GroupId' and id = '$Id' and par_id = '$ParId'";
$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
if($SelectMasterSql == true){
	if(mysqli_num_rows($SelectMasterSql)>0){
		$ListM = mysqli_fetch_object($SelectMasterSql);
		$MasterQty  = $ListM->quantity;
		$MasterDesc = $ListM->group3_description;
		$MasterUnit = $ListM->unit;
		$DisQtyPerc = $ListM->disp_qty_perc;
		$DMDisposQty = $ListM->disp_qty_perc;
	}
}
$TotalAmount = 0;
//$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a inner join item_master b on (a.item_id = b.item_id) where a.ref_id = '$RefId'";
$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a left join item_master_hc b on (a.item_id = b.item_id) where a.ref_id = '$RefId'";
$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
if($SelectDetailSql == true){
	if(mysqli_num_rows($SelectDetailSql)>0){
		while($ListD = mysqli_fetch_object($SelectDetailSql)){
			if($ListD->item_id != ''){
				$Qty 	= $ListD->quantity;
				$ItemId = $ListD->item_id;
				$Price = $ListD->price;
				$Amount = round(($Qty * $Price),2);
				$TotalAmount = $TotalAmount + $Amount;
			}else{
				$retVal = CalculateTSandIGCARRateMergeSubDataHC($ListD->merge_ref_id,$conn);
				$ExpretVal 		= explode("@**@",$retVal);
				$ForOneUnitRate = $ExpretVal[0];
				$IGCARRate2  	= $ExpretVal[1];
				$IGCARRate1  	= $ExpretVal[2];
				$GrossAmount 	= $ExpretVal[3];
				$ItemUnit 		= $ExpretVal[4];
				$MRGCalcType 	= $ExpretVal[5];
				$MRGNewMerge 	= $ExpretVal[6];
				$MRGAmtType 	= $ExpretVal[7];
				if($MRGCalcType == "WC"){
					if($ListD->amt_type == "GAMT"){
						$SDAmount = $GrossAmount;
					}
					
				}else{
					$SDAmount = $GrossAmount;
				} 
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ""){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $SDAmount; 
						foreach($ExpCalcAction as $key => $Value){ 
							$TempRate = $TempAmount;  
							$Action = $Value;
							$Factor = $ExpActionFactor[$key];
							if($Action == "A"){
								$TempAmount = round(($TempRate + $Factor),2); 
							}
							if($Action == "S"){
								$TempAmount = round(($TempRate - $Factor),2);
							}
							if($Action == "M"){
								$TempAmount = round(($TempRate * $Factor),2);
							}
							if($Action == "D"){
								$TempAmount = round(($TempRate / $Factor),2);
							}
							if($Action == "P"){
								$TempAmount = round(($TempRate * $Factor  / 100),2);
							}
						}
						$SDAmount = $TempAmount; 
					}
														
				}
				if($ListD->quantity != 0){
					$SDAmount = round(($SDAmount * $ListD->quantity),2);
				}
				$TotalAmount = $TotalAmount + $SDAmount; //echo $SDAmount;exit;
			}
		}
	}
}
$Output 	= CalculateTSandIGCARRateHC($MasterQty,$TotalAmount,$conn);
$ExpOutput 	= explode("@**@",$Output);
$TSRate 	= $ExpOutput[0];
$IGCARRate 	= $ExpOutput[1];
$IGCARRate1 = $ExpOutput[2];
if($DMDisposQty != 0){ 
	$TSRate  = round(($TSRate * $DMDisposQty / 100),2);
	$IGCARRate = round(($IGCARRate * $DMDisposQty / 100),2);
	$IGCARRate1 = round(($IGCARRate1 * $DMDisposQty / 100),2);
}
$Result 	= array('TSRate'=>$TSRate,'IGCARRate'=>$IGCARRate,'IGCARRate1'=>$IGCARRate1,'MasterDesc'=>$MasterDesc,'TotalAmount'=>$TotalAmount,'MasterUnit'=>$MasterUnit,'DisQtyPerc'=>$DisQtyPerc);
//echo $Output;
echo json_encode($Result);
?>