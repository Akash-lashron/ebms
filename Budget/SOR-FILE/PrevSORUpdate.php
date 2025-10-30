<?php
$result = mysqli_query($dbConn,"SELECT * FROM group_datasheet ORDER BY par_id asc");// ORDER BY type asc, group_id asc");
$category = array(
	'categories' => array(),
	'parent_cats' => array()
);
//build the array lists with data from the category table
while($row = mysqli_fetch_assoc($result)) {
	$category['categories'][$row['id']] = $row;
	$category['parent_cats'][$row['par_id']][] = $row['id'];
}
//print_r($category);exit;
function buildCategory($parent, $category, $conn, $PuId) {
	global $dbConn, $dbConn;
	include "DefaultMaster.php";
	$html = ""; //$xyz = 1;
	if (isset($category['parent_cats'][$parent])) {  //$xyz++;
		foreach ($category['parent_cats'][$parent] as $cat_id) { //$html .= $xyz."<br/>";
			if (!isset($category['parent_cats'][$cat_id])) {
				/// Here have to take the datasheet
				$Type = $category['categories'][$cat_id]['type'];
				$ID = $category['categories'][$cat_id]['id'];
				
				$MasterRefId = '';
				
				$InsertId = $ID;
				$InsertParId 	= $category['categories'][$cat_id]['par_id'];
				$InsertMdSd 	= $category['categories'][$cat_id]['MD_SD'];
				$InsertDisp 	= $category['categories'][$cat_id]['disp'];
				
				
				$DMGroupCode = ''; $DMGroupDesc = ''; $DMQty = ''; $DMUnit = ''; $DMGId = ''; $DMParId = ''; $DMNewMerge = ''; $DMCalcType = ''; $DMCostDtDesc = ''; $DMRefId = ''; $DMDisposQty = 0;
				$SelectQuery1 	= "select * from datasheet_master where id = '$ID'";
				//echo $SelectQuery1."<br/>";
				$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
				if($SelectSql1 == true){
					if(mysqli_num_rows($SelectSql1)>0){
						$List1 	= mysqli_fetch_object($SelectSql1);
						$DMRefId 	= $List1->ref_id;
						$DMGroupCode 	= $List1->type;
						$DMGroupDesc 	= $List1->group3_description;
						$DMQty 			= $List1->quantity;
						$DMUnit 		= $List1->unit;
						$DMGId 			= $List1->id;
						$DMParId 		= $List1->par_id;
						$DMNewMerge 	= $List1->new_merge;
						$DMCalcType 	= $List1->calc_type;
						$DMCostDtDesc 	= $List1->cost_dt;
						$DMDisposQty 	= $List1->disp_qty_perc;
						$DMToUnit 		= $List1->to_unit;
						$DMFinalUnit 	= $List1->final_unit;
						$DSUnit = '';
						if($DMToUnit != ""){
							$DSUnit = $DMToUnit;
						}else{
							if($DMFinalUnit != ""){
								$DSUnit = $DMFinalUnit;
							}else{
								$DSUnit = $DMUnit;
							}
						}
					}
				}
				$GroupDesc = '';
				if($DMGroupCode != ""){
					$SelectQuery2 	= "select * from group_datasheet where type = '$DMGroupCode'";
					$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
					if($SelectSql2 == true){
						if(mysqli_num_rows($SelectSql2)>0){
							$List2 	= mysqli_fetch_object($SelectSql2);
							$GroupDesc 	= $List2->group_desc;
						}
					}
				}
				$DSDtRows = 0;
				if($DMRefId != ""){
					$SelectQuery3 	= "select * from datasheet_a1_details where ref_id = '$DMRefId'";
					$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$DSDtRows = 1;
						}
					}
				}
				
				$ItemId = ''; $Type = ''; $MergeItemCode = ''; $MergeRefId = ''; $ItemQty = ''; $ItemAltDesc = ''; $CalcDesc = ''; $QtyDesc = ''; $CalcAction = ''; $ActionFactor = '';
				$CalcType = ''; $AmtType = ''; $MergeItemCode = ''; $MergeRefId = ''; $NewOrMerge = ''; $ItemDesc = ''; $ItemCode = ''; $ItemUnit = ''; $ItemRate = 0; $ItemAmount = 0;
				$TotalItemAmount = 0; $TotalItemAmount2 = 0; $TSRate = ''; $IGCAR = '';
				if($DSDtRows == 1){ while($List3 = mysqli_fetch_object($SelectSql3)){ 
					$ItemId 		= $List3->item_id;
					$Type 			= $List3->item_ds_type;
					$MergeItemCode 	= $List3->merge_item_code;
					$MergeRefId 	= $List3->merge_ref_id;
					$ItemQty 		= $List3->quantity;
					$ItemAltDesc 	= $List3->item_alt_desc;
					$CalcDesc 		= $List3->calc_desc;
					$QtyDesc 		= $List3->qty_desc;
					$CalcAction 	= $List3->calc_actions;
					$ActionFactor 	= $List3->actions_factors;
					$CalcType 		= $List3->calc_type;
					$AmtType 		= $List3->amt_type;
					$MergeItemCode 	= $List3->merge_item_code;
					$MergeRefId 	= $List3->merge_ref_id;
					$NewOrMerge 	= $List3->new_merge;
					$ItemDesc 		= $List3->item_desc;
					$ItemRate 		= '';
					
					if($Type == "I"){
						$SelectQuery4 	= "select * from item_master where item_id = '$List3->item_id'";
						$SelectSql4 	= mysqli_query($dbConn,$SelectQuery4);
						if($SelectSql4 == true){
							if(mysqli_num_rows($SelectSql4)>0){
								$List4 	= mysqli_fetch_object($SelectSql4);
								$ItemCode 	= $List4->item_code;
								$ItemUnit 	= $List4->unit;
								$ItemRate 	= $List4->price;
							}
						}
					}else{
						$retVal = CalculateTSandIGCARRateMergeSubData($MergeRefId,$conn);
						$ExpretVal 		= explode("@**@",$retVal);
						$ForOneUnitRate = $ExpretVal[0];
						$IGCARRate2  	= $ExpretVal[1];
						$IGCARRate1  	= $ExpretVal[2];
						$GrossAmount 	= $ExpretVal[3];
						$ItemUnit 		= $ExpretVal[4];
						$ItemCode 		= $MergeItemCode;
						$ItemRate = '';
						if($CalcType == "WOC"){
							$ItemRate = $GrossAmount;
						}else{
							if($AmtType == "GAMT"){
								$ItemRate = $GrossAmount;
							}else{
								// Based On Selection
								$TSRate 	= $ForOneUnitRate;
								$IGCARRate	= $IGCARRate1;
								//$ItemRate 	= $IGCARRate1;
								$ItemRate1 	= $TSRate;
								$ItemRate2 	= $IGCARRate;
							}
							/*$TSRate 	= $ForOneUnitRate;
							$IGCARRate	= $IGCARRate1;
							//$ItemRate 	= $IGCARRate1;
							$ItemRate1 	= $TSRate;
							$ItemRate2 	= $IGCARRate;*/
						}
					}
					if($DMNewMerge == "N"){								
						if($CalcAction != ""){
							$ExpCalcAction 	 = explode(",",$CalcAction);
							$ExpActionFactor = explode(",",$ActionFactor);
							if(count($ExpCalcAction)>0){
								$TempAmount = $ItemRate;
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
								$ItemRate = $TempAmount;
							}
						}
													
						if(($ItemQty == 0)||($ItemQty == '')){
							$ItemAmount 	= 0;//round($ItemRate,2);
						}else{
							$ItemAmount 	= round(($ItemRate * $ItemQty),2);
						}
						$TotalItemAmount = $TotalItemAmount + $ItemAmount;
					}else{
						if($CalcAction != ""){
							$ExpCalcAction 	 = explode(",",$CalcAction);
							$ExpActionFactor = explode(",",$ActionFactor);
							if(count($ExpCalcAction)>0){
								$TempAmount1 = $ItemRate1; 
								$TempAmount2 = $ItemRate2; 
								foreach($ExpCalcAction as $key => $Value){ 
									$TempRate1 = $TempAmount1;  
									$TempRate2 = $TempAmount2;
									$Action = $Value;
									$Factor = $ExpActionFactor[$key];
									if($Action == "A"){
										$TempAmount1 = round(($TempRate1 + $Factor),2); 
										$TempAmount2 = round(($TempRate2 + $Factor),2); 
									}
									if($Action == "S"){
										$TempAmount1 = round(($TempRate1 - $Factor),2);
										$TempAmount2 = round(($TempRate2 - $Factor),2);
									}
									if($Action == "M"){
										$TempAmount1 = round(($TempRate1 * $Factor),2);
										$TempAmount2 = round(($TempRate2 * $Factor),2);
									}
									if($Action == "D"){
										$TempAmount1 = round(($TempRate1 / $Factor),2);
										$TempAmount2 = round(($TempRate2 / $Factor),2);
									}
									if($Action == "P"){
										$TempAmount1 = round(($TempRate1 * $Factor  / 100),2);
										$TempAmount2 = round(($TempRate2 * $Factor  / 100),2);
									}
								}
								$ItemRate1 = $TempAmount1;
								$ItemRate2 = $TempAmount2;
							}
						}
													
						/*if(($ItemQty == 0)||($ItemQty == '')){
							$ItemAmount 	= round($ItemRate,2);
						}else{
							$ItemAmount 	= round(($ItemRate * $ItemQty),2);
						}
						$TotalItemAmount = $TotalItemAmount + $ItemAmount;*/
						$TotalItemAmount = $TotalItemAmount + $ItemRate1;
						$TotalItemAmount2 = $TotalItemAmount2 + $ItemRate2;
					}
					
				} }
				
				if($DMCalcType == 'WC'){ 
					$W 	= $TotalItemAmount;
					$A 	= round(($TotalItemAmount * $DefValPercArr[1] / 100),2);
					$WC = round(($W + $A),2);
					$B 	= round(($DefValPercArr[6] * $WC),2);
					$X 	= round(($B + $WC),2);
					$C 	= round(($X * $DefValPercArr[2] / 100),2);
					$Y 	= round(($X + $C),2);
					$D 	= round(($Y * $DefValPercArr[3] / 100),2);
					$E 	= round(($W * $DefValPercArr[4] / 100),2);
					$F 	= round(($Y+$D+$E),2);
													
					if($DMQty != ''){ 
						$ForOneUnit = round(($F/$DMQty),2); 
					}else{ 
						$ForOneUnit = round(($F/1),2); 
					}
														
					if($DMQty != ''){ 
						$G = round(($W*$DefValPercArr[5] / (100 * $DMQty)),2); 
					}else{ 
						$G = round(($W*$DefValPercArr[5] / 100),2); 
					}
					
					
					$UCFormula = "";
					$SelectUnitConvQuery = "select * from unit_conversion where from_unit = '$DMUnit' and to_unit = '$DMToUnit'";
					$SelectUnitConvSql 	 = mysqli_query($dbConn,$SelectUnitConvQuery);
					if($SelectUnitConvSql == true){
						if(mysqli_num_rows($SelectUnitConvSql)>0){
							$UCList = mysqli_fetch_object($SelectUnitConvSql);
							$UCFactor = $UCList->factor;
							$UCAction = $UCList->action;
							if($UCAction == "A"){ $ForOneUnit = $ForOneUnit + $UCFactor; $Symb = "+"; }
							if($UCAction == "S"){ $ForOneUnit = $ForOneUnit - $UCFactor; $Symb = "-"; }
							if($UCAction == "M"){ $ForOneUnit = $ForOneUnit * $UCFactor; $Symb = "x"; }
							if($UCAction == "D"){ $ForOneUnit = $ForOneUnit / $UCFactor; $Symb = "/"; }
							if($UCAction == "P"){ $ForOneUnit = $ForOneUnit * $UCFactor / 100; $Symb = "%"; }
							$ForOneUnit = round($ForOneUnit,2);
																//echo $UCAction;exit;
							if($DMQty != ''){ 
								$G = round(($W*$DefValPercArr[5]*$UCFactor/ (100 * $DMQty)),2); 
							}else{ 
								$G = round(($W*$DefValPercArr[5]*$UCFactor / 100),2); 
							}
							//echo $G;//exit;
							$UCFormula = $F.$Symb.$UCFactor."/".$DMQty;
						}
					}
					
					$TSRate = $ForOneUnit;
					
					$IGCAR = round(($ForOneUnit+$G),2);
					if($DMDisposQty != 0){ 
						$TSRate  = round(($TSRate * $DMDisposQty / 100),2);
						$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
					}
				}else{
					$TSRate = round($TotalItemAmount,2);
					$IGCAR = round($TotalItemAmount2,2);
				}
				$InsertType = $category['categories'][$cat_id]['type'];
				$InsertQuery = "insert into pds_detail set puid = '$PuId', id = '$InsertId', par_id = '$InsertParId', type = '$InsertType', MD_SD = '$InsertMdSd', disp = '$InsertDisp', ps_rate = '$IGCAR', tw_rate = '$TSRate', active = 1";
				$InsertSql = mysqli_query($dbConn,$InsertQuery);
				//$html .= $SelectQuery1;
				//$html .= "<tr class='labeldisplay'><td class='tdrowbold' valign='middle' align='center'>". $category['categories'][$cat_id]['type'] ."</td><td valign='middle' class='tdrow'>". $category['categories'][$cat_id]['group_desc'] . "</td><td class='tdrow' align='center' valign='middle'>".$DSUnit."</td><td class='tdrow' align='right' valign='middle'>".IndianMoneyFormat($TSRate)."</td><td class='tdrow' align='right' valign='middle'>".IndianMoneyFormat($IGCAR)."</td></tr>";
			}
			if (isset($category['parent_cats'][$cat_id])) {
				//$html .= "<tr class='labeldisplay'><td class='tdrowbold' valign='middle' align='center'>" . $category['categories'][$cat_id]['type'] ."</td><td valign='middle' class='tdrow'>". $category['categories'][$cat_id]['group_desc'] . "</td><td class='tdrow' valign='middle'>&nbsp;</td><td class='tdrow' valign='middle'>&nbsp;</td><td class='tdrow' valign='middle'>&nbsp;</td></tr>";
				$html .= buildCategory($cat_id, $category, $conn, $PuId);
				$InsertType = $category['categories'][$cat_id]['type'];
				$InsertId = $ID;
				$InsertParId 	= $category['categories'][$cat_id]['par_id'];
				$InsertMdSd 	= $category['categories'][$cat_id]['MD_SD'];
				$InsertDisp 	= $category['categories'][$cat_id]['disp'];
				$InsertQuery = "insert into pds_detail set puid = '$PuId', id = '$InsertId', par_id = '$InsertParId', type = '$InsertType', MD_SD = '$InsertMdSd', disp = '$InsertDisp', active = 1";
				$InsertSql = mysqli_query($dbConn,$InsertQuery);
				//$html .= $InsertQuery;
			}
		}
	}
	//echo $html;
}
buildCategory(0, $category, $conn, $PuId);
?>