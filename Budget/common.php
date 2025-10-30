<?php
ob_start();
require_once 'library/config.php';
require_once 'library/declaration.php';
checkUser();
function getusername($staffid)
{
	global $dbConn;
	$Query = "SELECT username FROM users where staffid ='$staffid' and active = 1";
	$SQLQuery = mysqli_query($dbConn,$Query);
	if ($SQLQuery == true)
	{
		$result = mysqli_fetch_object($SQLQuery);
		$username = $result->username;
	}
	return $username;
}
function getstafflevel($staffid)
{
	global $dbConn;
	$select_staff_query 	= "select sroleid, levelid, staffname from staff where staffid = '$staffid' and active = 1";
	$select_staff_sql = mysqli_query($dbConn,$select_staff_query);
	$result1 = mysqli_fetch_object($select_staff_sql);
	$sroleid 		= $result1->sroleid;
	$levelid 		= $result1->levelid;
	$staffname 		= $result1->staffname;
	return $sroleid."@#*#@".$levelid."@#*#@".$staffname;
}
function IND_money_format($fullmoney){
	$expfullmoney = explode(".",$fullmoney);
	$money = $expfullmoney[0];
	$paise = $expfullmoney[1];
    $len = strlen($money);
    $m = '';
    $money = strrev($money);
    for($i=0;$i<$len;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$len){
            $m .=',';
        }
        $m .=$money[$i];
    }
	if($m == ""){ $m = 0; } if( $paise == ""){$paise = '00'; }
    return strrev($m).".".$paise;
}
function IndianMoneyFormat($amount){
	$amt1 = number_format($amount, 2, '.', '');
	$amt2 = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amt1);
	return $amt2;
}
function CalculateTSandIGCARRate($MasterQty,$Amt,$conn){
	global $dbConn;
	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master";// where de_name='WCT'";
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


function CalculateTSandIGCARRateMergeSubDataLevel2($MergeRefId,$conn){ //echo "RID = ".$MergeRefId;exit;
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a left join item_master b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
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
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel3($ListD->merge_ref_id,$conn);//CalculateTSandIGCARRateMergeSubData($ListD->merge_ref_id,$conn);
					//echo "QQQ1=".$PriceStr."<br/>";exit;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//$PriceStr = "P2 = ".$PriceStr;
					$ForOneUnit 	= $ExpPriceStr[0];
					$IGCAR2 		= $ExpPriceStr[1];
					$IGCAR 			= $ExpPriceStr[2];
					$TotalAmt 		= $ExpPriceStr[3];
					$MasterUnit 	= $ExpPriceStr[4];
					$MasterCalcType = $ExpPriceStr[5];
					$MasterNewMerge = $ExpPriceStr[6];
					$MasterAmtType 	= $ExpPriceStr[7];
					//$Q = $Q.$ExpPriceStr[8];
					if($MasterCalcType == "WOC"){
						$Price = $TotalAmt;
					}else if(($MasterCalcType == "WC")&&($ListD->amt_type == "GAMT")){
						$Price = $TotalAmt;
					}else{
						$Price = $IGCAR2;
					}
					//$Price = 1000000000;
					//echo "HI"."<br/>";
					
				}
				
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ''){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $Price;
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
						$Price = $TempAmount;
					}
				}
				//echo $Price."<br/>";
				//echo "QQQ1=".$Price."<br/>";
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				//echo $Amount."<br/>";
				//echo $ListD->item_code." = ".$Price." * ".$Qty." = ".$Amount."<br/>";
			}
		}
	}
	//echo $TotalAmount."<br/>";
	//exit;
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master";// where de_name='WCT'";
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
	//echo $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType."<br/>";
}

function CalculateTSandIGCARRateMergeSubDataLevel3($MergeRefId,$conn){ //echo "RID = ".$MergeRefId;exit;
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a left join item_master b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
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
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel2($ListD->merge_ref_id,$conn);//CalculateTSandIGCARRateMergeSubData($ListD->merge_ref_id,$conn);
					//echo "QQQ1=".$PriceStr."<br/>";exit;
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
					//$Price = 1000000000;
					//echo "HI"."<br/>";
					
				}
				
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ''){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $Price;
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
						$Price = $TempAmount;
					}
				}
				//echo $Price."<br/>";
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				//echo $ListD->item_code." = ".$Amount."<br/>";
			}
		}
	}
	//echo $TotalAmount."<br/>";
	//exit;
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master";// where de_name='WCT'";
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
	//echo $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType."<br/>";
}

function CalculateTSandIGCARRateMergeSubData($MergeRefId,$conn){
	global $dbConn;
	$MasterQty  = 0; $MasterDesc = ""; $MasterUnit = ""; $DMDisposQty = 0; $Q = "QRY-"; $IsAvgRequir = "";
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
			$IsAvgRequir	= $ListM->is_average;
		}
	}
	//echo $MergeRefId." = ".$IsAvgRequir;exit;
	//return $MergeRefId;
	$TotalAmount = 0; $PriceStr = ""; $Price = 0; $Amount = 0; $P = "";  $TotalAmount2a = 0; $TotalAmount2b = 0; $Str = ""; $AvgCnt = 0;
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a left join item_master b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
	//echo $SelectDetailQuery."<br/>";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				if($ListD->item_ds_type  == "I"){
					$Price 			= $ListD->price;
					$CalcAction = $ListD->calc_actions;
					$ActionFactor = $ListD->actions_factors;
					if($CalcAction != ''){
						$ExpCalcAction 	 = explode(",",$CalcAction);
						$ExpActionFactor = explode(",",$ActionFactor);
						if(count($ExpCalcAction)>0){
							$TempAmount = $Price;
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
							$Price = $TempAmount;
						}
					}
				}else{
					//echo "I-REF - ".$ListD->merge_ref_id;exit;
					$AvgCnt++;
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel2($ListD->merge_ref_id,$conn);
					//echo $ListD->merge_ref_id." = ".$PriceStr."<br/>";exit;
					$ExpPriceStr 	= explode("@**@",$PriceStr);
					//echo $ListD->merge_ref_id." = ".$PriceStr."<br/>";
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
						$Price2a = $ForOneUnit;
						$Price2b = $IGCAR;
					}
					//$XY1 .= "QQQ1=".$Price."<br/>";
					//// THIS PORTION IS MANDATOR FOR S-F11 & 12
					$CalcAction = $ListD->calc_actions;
					$ActionFactor = $ListD->actions_factors;
					if($CalcAction != ''){
						$ExpCalcAction 	 = explode(",",$CalcAction);
						$ExpActionFactor = explode(",",$ActionFactor);
						if(count($ExpCalcAction)>0){
							$TempAmount = $Price;
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
							$Price = $TempAmount;
						}
					}
					
					//$Price = 0;//1000000000;
				}
				$Amount = 0;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				$TotalAmount2a  = $TotalAmount2a + $Price2a;
				$TotalAmount2b  = $TotalAmount2b + $Price2b;
				$P .= $Amount." = ".$Qty." = ".$TotalAmount."<br/>";
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master";// where de_name='WCT'";
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
	if(($TotalAmount2a > 0)&&($TotalAmount2b > 0)){
		if(($IsAvgRequir == "Y")&&($AvgCnt > 0)){
			$ForOneUnit = round(($TotalAmount2a/$AvgCnt),2);
			$IGCAR 		= round(($TotalAmount2b/$AvgCnt),2);
			$IGCARRate1 = round(($TotalAmount2b/$AvgCnt),2);
		}else{
			$ForOneUnit = $TotalAmount2a;
			$IGCAR 		= $TotalAmount2b;
			$IGCAR2 	= $TotalAmount2b;
		}
	}
	//$XY2 .= $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	/// $IGCAR2 is IGCAR Rate
	return $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType;
	//echo $ForOneUnit."@**@".$IGCAR2."@**@".$IGCAR."@**@".$TotalAmount."@**@".$MasterUnit."@**@".$MasterCalcType."@**@".$MasterNewMerge."@**@".$MasterAmtType."<br/>";
	//return $PriceStr;
	//return $W."@**@".$IGCAR2;
	//echo $XY1;
}
function CalculateTSandIGCARRateSubData($ItemCode,$conn){
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a inner join item_master b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				$Price 			= $ListD->price;
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ''){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $Price;
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
						$Price = $TempAmount;
					}
				}
				$Unit 			= $ListD->unit;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master";// where de_name='WCT'";
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
function CalculateTSandIGCARRateHC($MasterQty,$Amt,$conn){
	global $dbConn;
	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master_hc";// where de_name='WCT'";
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


function CalculateTSandIGCARRateMergeSubDataLevel2HC($MergeRefId,$conn){ //echo "RID = ".$MergeRefId;exit;
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a left join item_master_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
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
					/*$PriceStr = CalculateTSandIGCARRateMergeSubData($ListD->merge_ref_id,$conn);
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
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ''){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $Price;
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
						$Price = $TempAmount;
					}
				}
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
				//echo $ListD->item_code." = ".$Price."<br/>";
			}
		}
	}
	//echo $MergeRefId."<br/>";
	//exit;
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master_hc";// where de_name='WCT'";
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

function CalculateTSandIGCARRateMergeSubDataHC($MergeRefId,$conn){
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a left join item_master_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
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
					$PriceStr = CalculateTSandIGCARRateMergeSubDataLevel2($ListD->merge_ref_id,$conn);
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
					$CalcAction = $ListD->calc_actions;
					$ActionFactor = $ListD->actions_factors;
					if($CalcAction != ''){
						$ExpCalcAction 	 = explode(",",$CalcAction);
						$ExpActionFactor = explode(",",$ActionFactor);
						if(count($ExpCalcAction)>0){
							$TempAmount = $Price;
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
							$Price = $TempAmount;
						}
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
	
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master_hc";// where de_name='WCT'";
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
function CalculateTSandIGCARRateSubDataHC($ItemCode,$conn){
	global $dbConn;
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
	$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details_hc a inner join item_master_hc b on (a.item_id = b.item_id) where a.ref_id = '$MergeRefId'";
	$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
	if($SelectDetailSql == true){
		if(mysqli_num_rows($SelectDetailSql)>0){
			while($ListD = mysqli_fetch_object($SelectDetailSql)){
				$Qty 			= $ListD->quantity;
				$ItemId 		= $ListD->item_id;
				$Price 			= $ListD->price;
				$CalcAction = $ListD->calc_actions;
				$ActionFactor = $ListD->actions_factors;
				if($CalcAction != ''){
					$ExpCalcAction 	 = explode(",",$CalcAction);
					$ExpActionFactor = explode(",",$ActionFactor);
					if(count($ExpCalcAction)>0){
						$TempAmount = $Price;
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
						$Price = $TempAmount;
					}
				}
				$Unit 			= $ListD->unit;
				$Amount 		= round(($Qty * $Price),2);
				$TotalAmount 	= $TotalAmount + $Amount;
			}
		}
	}
	
	//$Output 	= CalculateTSandIGCARRate($MasterQty,$TotalAmount,$conn);

	$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
	$sql_default = "select * from default_master_hc";// where de_name='WCT'";
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