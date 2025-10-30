<table align="center" id="tab_a1_material" class="itemtable new <?php echo $NewHideClass; ?>">
	<tr>
		<th nowrap="nowrap">Item Code</th>
		<th nowrap="nowrap">Main-Data / Sub-Data Description</th>
		<th nowrap="nowrap">Rate (&#8377;)</th>
		<th>Unit</th>
		<th>Qty</th>
		<th nowrap="nowrap">Amount (&#8377;)</th>
		<th>&nbsp;</th>
	</tr>
	<tr>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass icode" list="ItemCodeListNew0" style="width:100%" name="txt_code[]" id="txt_code0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListNew0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCode(0); ?>
				<?php echo $objBind->BindDataSheetMasterType(0); ?>
			</datalist>
		</td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass idesc" list="ItemDescListNew0" style="width:90%;" name="txt_desc[]" id="txt_desc0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemDescListNew0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeDesc(0); ?>
			</datalist><i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox0" data-index="0"></i>
			<input type="hidden" class="tboxsmclass" style="width:100%" name="txt_item_id[]" id="txt_item_id0" value="" readonly="" />
			<textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc0" style="display:none;"></textarea>
			<textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt0" style="display:none;"></textarea>
			<textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt0" style="display:none;"></textarea>
			<input type="hidden" name="txt_curr_action[]" id="txt_curr_action0">
			<input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor0">
			<input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index0">
			<input type="hidden" name="hid_rate[]" id="hid_rate0">
			<input type="hidden" name="hid_calc_type[]" id="hid_calc_type0">
			<input type="hidden" name="hid_amt_type[]" id="hid_amt_type0">
			<input type="hidden" name="hid_ref_id[]" id="hid_ref_id0">
			<input type="hidden" name="txt_curr_title[]" id="txt_curr_title0">
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_rate[]" id="txt_rate0" value="" readonly="" /></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext" style="width:100%" name="txt_unit[]" id="txt_unit0" value="" readonly="" /></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass rtext Qty" style="width:100%" data-index="0" name="txt_quantity[]" id="txt_quantity0" value="" />
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt" style="width:100%" name="txt_amount[]" id="txt_amount0" value="" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
			<!--<input type="button" name="btn_add"   id="btn_add" value="Add" style="width:45%" onClick="addrow()" />-->
			<i style="font-size:21px" class="fa faicon-add" name="btn_add" id="btn_add" onClick="addrow()">&#xf01a;</i>
			<i style="font-size:21px" class="fa faicon-clr" name="btn_clear" id="btn_clear" onClick="cleartxt()">&#xf05c;</i>
			<!--<input type="button" name="btn_clear" id="btn_clear" value="  Clear  " style="width:45%" onClick="cleartxt()"/>	-->
		</td>
	</tr>
<?php 
	$TotalItemAmount = 0; $index1 = 1; $CalcIndex = 1;
	if(($DSDtRows == 1)&&($DMNewMerge == "N")){ while($List3 = mysqli_fetch_object($SelectSql3)){ 
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
		$Title 			= $List3->title;
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
			$MRGCalcType 	= $ExpretVal[5];
			$MRGNewMerge 	= $ExpretVal[6];
			$MRGAmtType 	= $ExpretVal[7];
			$ItemCode 		= $MergeItemCode;
			$ItemRate = '';
			if($MRGCalcType == "WOC"){ 
				$ItemRate = $GrossAmount;
			}else{
				if($AmtType == "GAMT"){
					$ItemRate = $GrossAmount;
				}else{
					// Based On Selection
				}
			}
			//echo " == ".$AmtType;exit;
		}
		$BfCalcItemRate = $ItemRate;
		$CalcIndexArr 	= array();
													
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
					array_push($CalcIndexArr,$CalcIndex);
					$CalcIndex++;
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
		$CalcIndexStr = implode(",",$CalcIndexArr);
	?>													
	
	<tr>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass icode" list="ItemCodeListNew<?php echo $index1; ?>" style="width:100%" name="txt_code[]" id="txt_code<?php echo $index1; ?>" data-index="<?php echo $index1; ?>" value="<?php echo $ItemCode; ?>" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListNew<?php echo $index1; ?>" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCode($ItemCode); ?>
				<?php echo $objBind->BindDataSheetMasterType($ItemCode); ?>
			</datalist>
		</td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass idesc" list="ItemDescListNew<?php echo $index1; ?>" style="width:90%;" name="txt_desc[]" id="txt_desc<?php echo $index1; ?>" data-index="<?php echo $index1; ?>" value="<?php echo $ItemDesc; ?>" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemDescListNew<?php echo $index1; ?>" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeDesc(0); ?>
			</datalist>
			<i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox<?php echo $index1; ?>" data-index="<?php echo $index1; ?>"></i>
			<input type="hidden" class="tboxsmclass" style="width:100%" name="txt_item_id[]" id="txt_item_id<?php echo $index1; ?>" value="<?php echo $ItemId; ?>" readonly="" />
			<textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc<?php echo $index1; ?>" style="display:none;"><?php echo $CalcDesc; ?></textarea>
			<textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt<?php echo $index1; ?>" style="display:none;"></textarea>
			<textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt<?php echo $index1; ?>" style="display:none;"><?php echo $ItemAltDesc; ?></textarea>
			<input type="hidden" name="txt_curr_action[]" id="txt_curr_action<?php echo $index1; ?>" value="<?php echo $CalcAction; ?>">
			<input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor<?php echo $index1; ?>" value="<?php echo $ActionFactor; ?>">
			<input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index<?php echo $index1; ?>" value="<?php echo $CalcIndexStr; ?>">
			<input type="hidden" name="hid_rate[]" id="hid_rate<?php echo $index1; ?>" value="<?php echo $BfCalcItemRate; ?>">
			<input type="hidden" name="hid_calc_type[]" id="hid_calc_type<?php echo $index1; ?>" value="<?php echo $CalcType; ?>">
			<input type="hidden" name="hid_amt_type[]" id="hid_amt_type<?php echo $index1; ?>" value="<?php echo $AmtType; ?>">
			<input type="hidden" name="hid_ref_id[]" id="hid_ref_id<?php echo $index1; ?>" value="<?php echo $MergeRefId; ?>">
			<input type="hidden" name="txt_curr_title[]" id="txt_curr_title<?php echo $index1; ?>" value="<?php echo $Title; ?>">
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_rate[]" id="txt_rate<?php echo $index1; ?>" value="<?php echo $ItemRate; ?>" readonly="" /></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext" style="width:100%" name="txt_unit[]" id="txt_unit<?php echo $index1; ?>" value="<?php echo $ItemUnit; ?>" readonly="" /></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass rtext Qty" style="width:100%" data-index="<?php echo $index1; ?>" name="txt_quantity[]" id="txt_quantity<?php echo $index1; ?>" value="<?php echo $ItemQty; ?>" />
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt" style="width:100%" name="txt_amount[]" id="txt_amount<?php echo $index1; ?>" value="<?php echo $ItemAmount; ?>" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
			<i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete<?php echo $index1; ?>">&#xf057;</i>
		</td>
	</tr>
	
	
	
	<?php $index1++; } $TotalItemAmount = round($TotalItemAmount,2); ?>
	<tr>
		<td colspan="5" class="labelboldright rboxlabel" valign="middle">Total<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="labelfieldright" style="width:100%" name="txt_total_amount" id="txt_total_amount" value="<?php echo $TotalItemAmount; ?>" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
	<?php } ?>
</table>
<?php 
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
			$UCFormula = $F.$Symb.$UCFactor."/".$DMQty;
		}
	}
	$IGCAR = round(($ForOneUnit+$G),2);
}
?>
