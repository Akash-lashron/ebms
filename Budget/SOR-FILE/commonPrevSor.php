<?php
ob_start();
require_once 'library/config.php';
require_once 'library/declaration.php';

function CalculateTSandIGCARRatePrev($MasterQty,$Amt,$conn){
	global $dbConn, $dbConn, $PruId;
	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $Amt; $total_a1_amount = $Amt;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR;
}


function CalculateTSandIGCARRateMergeSubDataLevel2Prev($MergeRefId,$conn){ //echo "RID = ".$MergeRefId;exit;
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DMDisposQty = 0; 
	$SelectMasterQuery 	= "select * from datasheet_master where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM 			= mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$MasterUnit 	= $ListM->unit;
			$MasterCalcType = $ListM->calc_type;
			$MasterNewMerge = $ListM->new_merge;
			$MasterAmtType 	= $ListM->amt_type;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0; $PriceStr = ""; $Price = 0; $Amount = 0; 
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a left join pru_detail b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	//echo $SelectDetailQuery."</br>";exit;
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				if($ListD->item_ds_type  == "I"){
					$Price 			= $ListD->price;
				}else{
					/*$PriceStr = CalculateTSandIGCARRateMergeSubDataPrev($ListD->merge_ref_id,$conn);
					//$XY1 .= "QQQ1=".$PriceStr;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//$PriceStr = "P2 = ".$PriceStr;
					$ForOneUnit 	= $ExpPriceStr[0];
					$IGCAR2 		= $ExpPriceStr[1];
					$IGCAR 			= $ExpPriceStr[2];
					$TotalAmount 	= $ExpPriceStr[3];
					$MasterUnit 	= $ExpPriceStr[4];
					$MasterCalcType = $ExpPriceStr[5];
					$MasterNewMerge = $ExpPriceStr[6];
					$MasterAmtType 	= $ExpPriceStr[7];
					//$Q = $Q.$ExpPriceStr[8];
					if($MasterCalcType == "WOC"){
						$Price = $TotalAmount;
					}else if(($MasterCalcType == "WC")&&($ListD->amt_type == "GAMT")){
						$Price = $TotalAmount;
					}else{
						$Price = $IGCAR2;
					}*/
					//$Price = 1000000000;
				}
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				//echo $ListD->item_code." = ".$Price."<br/>";
			}
		}
	}
	//echo $MergeRefId."<br/>";
	//exit;
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
		$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
	}
	//$XY2 .= $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	/// $IGCAR2 is IGCAR Rate
	//echo $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;;exit;
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	//return
}

function CalculateTSandIGCARRateMergeSubDataPrev($MergeRefId,$conn){
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DMDisposQty = 0; $Q = "QRY-";
	$SelectMasterQuery 	= "select * from datasheet_master where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM 			= mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$MasterUnit 	= $ListM->unit;
			$MasterCalcType = $ListM->calc_type;
			$MasterNewMerge = $ListM->new_merge;
			$MasterAmtType 	= $ListM->amt_type;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0; $PriceStr = ""; $Price = 0; $Amount = 0; $P = "";
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a left join pru_detail b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	//$Q = $Q.$SelectDetailQuery."</br>";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				if($ListD->item_ds_type  == "I"){
					$Price 			= $ListD->price;
				}else{
					//echo "I-REF - ".$ListD->merge_ref_id;exit;
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel2Prev($ListD->merge_ref_id,$conn);
					//$XY1 .= "QQQ1=".$PriceStr;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//$PriceStr = "P2 = ".$PriceStr;
					$ForOneUnit 	= $ExpPriceStr[0];
					$IGCAR2 		= $ExpPriceStr[1];
					$IGCAR 			= $ExpPriceStr[2];
					$TotAmount 		= $ExpPriceStr[3];
					$MasterUnit 	= $ExpPriceStr[4];
					$MasterCalcType = $ExpPriceStr[5];
					$MasterNewMerge = $ExpPriceStr[6];
					$MasterAmtType 	= $ExpPriceStr[7];
					//$Q = $Q.$ExpPriceStr[8];
					if($MasterCalcType == "WOC"){
						$Price = $TotAmount;
					}else if(($MasterCalcType == "WC")&&($ListD->amt_type == "GAMT")){
						$Price = $TotAmount;
					}else{
						$Price = $IGCAR2;
					}
					//$Price = 0;//1000000000;
				}
				$Amount = 0;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				$P .= $Amount." = ".$Qty." = ".$TotalAmount."<br/>";
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
		$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
	}
	//$XY2 .= $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	//return $PriceStr;
	//return $W."@**@".$IGCAR2;
	//echo $P;
}
function CalculateTSandIGCARRateSubDataPrev($ItemCode,$conn){
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $DMDisposQty = 0;
	
	$SelectRefIdQuery 	= "select * from datasheet_master where type = '$ItemCode'";
	$SelectRefIdSql 	= mysqli_query($dbConn,$SelectRefIdQuery);
	if($SelectRefIdSql == true){
		if(mysqli_num_rows($SelectRefIdSql)>0){
			$ListM = mysqli_fetch_object($SelectRefIdSql);
			$MergeRefId = $ListM->ref_id;
		}
	}
	
	$SelectMasterQuery 	= "select * from datasheet_master where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM = mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0;
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a inner join pru_detail b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				$Price 			= $ListD->price;
				$Unit 			= $ListD->unit;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
	}
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$W."@**@".$Unit;
	//return $W."@**@".$IGCAR2;
}

/********************************* *********************************/
function CalculateTSandIGCARRateHCPrev($MasterQty,$Amt,$conn){
	global $dbConn, $dbConn, $PruId;
	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $Amt; $total_a1_amount = $Amt;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR;
}


function CalculateTSandIGCARRateMergeSubDataLevel2HCPrev($MergeRefId,$conn){ //echo "RID = ".$MergeRefId;exit;
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DMDisposQty = 0; 
	$SelectMasterQuery 	= "select * from datasheet_master_hc where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM 			= mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$MasterUnit 	= $ListM->unit;
			$MasterCalcType = $ListM->calc_type;
			$MasterNewMerge = $ListM->new_merge;
			$MasterAmtType 	= $ListM->amt_type;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0; $PriceStr = ""; $Price = 0; $Amount = 0; 
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a left join pru_detail_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	//echo $SelectDetailQuery."</br>";exit;
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				if($ListD->item_ds_type  == "I"){
					$Price 			= $ListD->price;
				}else{
					/*$PriceStr = CalculateTSandIGCARRateMergeSubDataPrev($ListD->merge_ref_id,$conn);
					//$XY1 .= "QQQ1=".$PriceStr;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//$PriceStr = "P2 = ".$PriceStr;
					$ForOneUnit 	= $ExpPriceStr[0];
					$IGCAR2 		= $ExpPriceStr[1];
					$IGCAR 			= $ExpPriceStr[2];
					$TotalAmount 	= $ExpPriceStr[3];
					$MasterUnit 	= $ExpPriceStr[4];
					$MasterCalcType = $ExpPriceStr[5];
					$MasterNewMerge = $ExpPriceStr[6];
					$MasterAmtType 	= $ExpPriceStr[7];
					//$Q = $Q.$ExpPriceStr[8];
					if($MasterCalcType == "WOC"){
						$Price = $TotalAmount;
					}else if(($MasterCalcType == "WC")&&($ListD->amt_type == "GAMT")){
						$Price = $TotalAmount;
					}else{
						$Price = $IGCAR2;
					}*/
					//$Price = 1000000000;
				}
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				//echo $ListD->item_code." = ".$Price."<br/>";
			}
		}
	}
	//echo $MergeRefId."<br/>";
	//exit;
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
		$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
	}
	//$XY2 .= $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	/// $IGCAR2 is IGCAR Rate
	//echo $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;;exit;
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	//return
}

function CalculateTSandIGCARRateMergeSubDataHCPrev($MergeRefId,$conn){
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DMDisposQty = 0; $Q = "QRY-";
	$SelectMasterQuery 	= "select * from datasheet_master_hc where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM 			= mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$MasterUnit 	= $ListM->unit;
			$MasterCalcType = $ListM->calc_type;
			$MasterNewMerge = $ListM->new_merge;
			$MasterAmtType 	= $ListM->amt_type;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0; $PriceStr = ""; $Price = 0; $Amount = 0; $P = "";
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a left join pru_detail_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	//$Q = $Q.$SelectDetailQuery."</br>";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				if($ListD->item_ds_type  == "I"){
					$Price 			= $ListD->price;
				}else{
					//echo "I-REF - ".$ListD->merge_ref_id;exit;
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel2Prev($ListD->merge_ref_id,$conn);
					//$XY1 .= "QQQ1=".$PriceStr;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//$PriceStr = "P2 = ".$PriceStr;
					$ForOneUnit 	= $ExpPriceStr[0];
					$IGCAR2 		= $ExpPriceStr[1];
					$IGCAR 			= $ExpPriceStr[2];
					$TotAmount 		= $ExpPriceStr[3];
					$MasterUnit 	= $ExpPriceStr[4];
					$MasterCalcType = $ExpPriceStr[5];
					$MasterNewMerge = $ExpPriceStr[6];
					$MasterAmtType 	= $ExpPriceStr[7];
					//$Q = $Q.$ExpPriceStr[8];
					if($MasterCalcType == "WOC"){
						$Price = $TotAmount;
					}else if(($MasterCalcType == "WC")&&($ListD->amt_type == "GAMT")){
						$Price = $TotAmount;
					}else{
						$Price = $IGCAR2;
					}
					//$Price = 0;//1000000000;
				}
				$Amount = 0;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				$P .= $Amount." = ".$Qty." = ".$TotalAmount."<br/>";
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
		$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
	}
	//$XY2 .= $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	//return $PriceStr;
	//return $W."@**@".$IGCAR2;
	//echo $P;
}
function CalculateTSandIGCARRateSubDataHCPrev($ItemCode,$conn){
	global $dbConn, $dbConn, $PruId;
	$MasterQty  = 0; $MasterDesc = ""; $DMDisposQty = 0;
	
	$SelectRefIdQuery 	= "select * from datasheet_master_hc where type = '$ItemCode'";
	$SelectRefIdSql 	= mysqli_query($dbConn,$SelectRefIdQuery);
	if($SelectRefIdSql == true){
		if(mysqli_num_rows($SelectRefIdSql)>0){
			$ListM = mysqli_fetch_object($SelectRefIdSql);
			$MergeRefId = $ListM->ref_id;
		}
	}
	
	$SelectMasterQuery 	= "select * from datasheet_master_hc where ref_id = '$MergeRefId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql)>0){
			$ListM = mysqli_fetch_object($SelectMasterSql);
			$MasterQty 		= $ListM->quantity;
			$MasterDesc 	= $ListM->group3_description;
			$DMDisposQty 	= $ListM->disp_qty_perc;
		}
	}
	//return $MergeRefId;
	$TotalAmount = 0;
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a inner join pru_detail_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId' and b.puid = '$PruId'";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				$Price 			= $ListD->price;
				$Unit 			= $ListD->unit;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRatePrev($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
	$rs_default  = mysqli_query($dbConn,$sql_default,$conn);
	if($rs_default == true){
		while($List = mysqli_fetch_object($rs_default)){
			$DefID 	 = $List->de_id;
			$DefName = $List->de_name;
			$DefPerc = $List->de_perc;
			$DefCode = $List->de_code;
			$DefValNameArr[$DefID] = $DefName;
			$DefValPercArr[$DefID] = $DefPerc;
			$DefValCodeArr[$DefID] = $DefCode;
		}
	}
	$W 	= $TotalAmount; $total_a1_amount = $TotalAmount;
	$A 	= round(($total_a1_amount * $DefValPercArr[1] / 100),2);
	$WC = round(($W + $A),2);
	$B 	= round(($DefValPercArr[6] * $WC),2);
	$X 	= round(($B + $WC),2);
	$C 	= round(($X * $DefValPercArr[2] / 100),2);
	$Y 	= round(($X + $C),2);
	$D 	= round(($Y * $DefValPercArr[3] / 100),2);
	$E 	= round(($W * $DefValPercArr[4] / 100),2);
	$F 	= round(($Y+$D+$E),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$ForOneUnit = round(($F/$MasterQty),2);
	}else{
		$ForOneUnit = round(($F/1),2);
	}
	/// $ForOneUnit is TS Rate
	if(($MasterQty != '')&&($MasterQty != 0)){
		$G = round(($W*$DefValPercArr[5] / (100 * $MasterQty)),2);
	}else{
		$G = round(($W*$DefValPercArr[5] / 100),2);
	}
	$IGCAR 	= round(($ForOneUnit+$G),2);
	if(($MasterQty != '')&&($MasterQty != 0)){
		$IGCAR2 = round(($IGCAR*$MasterQty),2);
	}else{
		$IGCAR2 = $IGCAR;
	}
	if($DMDisposQty != 0){ 
		$ForOneUnit  = round(($ForOneUnit * $DMDisposQty / 100),2);
		$IGCAR2 = round(($IGCAR2 * $DMDisposQty / 100),2);
	}
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$W."@**@".$Unit;
	//return $W."@**@".$IGCAR2;
}
?>