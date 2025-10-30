<table align="center" id="tab_sd_material" class="itemtable merge <?php echo $SDHideClass; ?>">
	<tr height="25" valign="middle">
		<th nowrap="nowrap" style="width:10%">&nbsp;Item Code&nbsp;</th>
		<th valign="middle" style="width:50%">Sub Data Description</th>
		<th nowrap="nowrap" style="width:15%">&nbsp;TS Rate&nbsp;</th>
		<th nowrap="nowrap" style="width:15%">&nbsp;IGCAR Rate&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	<tr style="vertical-align:middle">
		<td class="labelcenter" nowrap="nowrap">
			<input type="text" class="tboxsmclass icodesd" list="ItemCodeListSD0" style="width:100%" name="txt_item_id_sd[]" id="txt_item_id_sd0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListSD0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindDataSheetMasterType(0); ?>
			</datalist>
			<input type="hidden" name="txt_refid_sd[]" id="txt_refid_sd0" value="">
		</td>
		<td class="labelcenter">
			<input type="text" class="tboxsmclass" style="width:91%" name="txt_desc_sd[]" id="txt_desc_sd0" value="" readonly="" />&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-sd" id="CmdBox0" data-index="0"></i>
			<textarea name="txt_curr_calc_desc_sd[]" id="txt_curr_calc_desc_sd0" style="display:none;"></textarea>
			<textarea name="txt_curr_qty_desc_sd[]" id="txt_curr_qty_desc_sd0" style="display:none;"></textarea>
			<textarea name="txt_curr_item_desc_alt_sd[]" id="txt_curr_item_desc_alt_sd0" style="display:none;"></textarea>
			<input type="hidden" name="txt_curr_action_sd[]" id="txt_curr_action_sd0">
			<input type="hidden" name="txt_curr_factor_sd[]" id="txt_curr_factor_sd0">
			<input type="hidden" name="txt_curr_calc_index_sd[]" id="txt_curr_calc_index_sd0">
			<input type="hidden" name="hid_igc_rate_sd[]" id="hid_igc_rate_sd0">
			<input type="hidden" name="hid_ts_rate_sd[]" id="hid_ts_rate_sd0">
			<input type="hidden" name="hid_calc_type_sd[]" id="hid_calc_type_sd0">
			<input type="hidden" name="hid_amt_type_sd[]" id="hid_amt_type_sd0">
			<input type="hidden" name="hid_ref_id_sd[]" id="hid_ref_id_sd0">
			<input type="hidden" name="txt_curr_title_sd[]" id="txt_curr_title_sd0">
		</td>
		<td class="labelcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_ts_rate_sd[]" id="txt_ts_rate_sd0" value="" readonly="" /></td>
		<td class="labelcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_igc_rate_sd[]" id="txt_igc_rate_sd0" value="" readonly="" /></td>
		<td class="labelcenter" align='center' nowrap="nowrap">
			<!--<input type="button" name="btn_add_sd"   id="btn_add_sd" value="Add" style="width:45%" onClick="addrowSD()" />
			<input type="button" name="btn_clear_sd" id="btn_clear_sd" value="  Clear  " style="width:45%" onClick="cleartxtSD()"/>-->
			<i style="font-size:21px" class="fa faicon-add" name="btn_add_sd" id="btn_add_sd" onClick="addrowSD()">&#xf01a;</i>
			<i style="font-size:21px" class="fa faicon-clr" name="btn_clear_sd" id="btn_clear_sd" onClick="cleartxtSD()">&#xf05c;</i>
		</td>
	</tr>
<?php 
$TotalTSAmount = 0; $TotalIGCAmount = 0; $index2 = 1; $CalcIndex = 1;
if(($DSDtRows == 1)&&($DMNewMerge == "M")){ while($List3 = mysqli_fetch_object($SelectSql3)){ 
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
													
	$retVal = CalculateTSandIGCARRateMergeSubData($MergeRefId,$conn);
	$ExpretVal 		= explode("@**@",$retVal);
	$ForOneUnitRate = $ExpretVal[0];
	$IGCARRate2  	= $ExpretVal[1];
	$IGCARRate1  	= $ExpretVal[2];
	$GrossAmount 	= $ExpretVal[3];
	$ItemUnit 		= $ExpretVal[4];
	$ItemCode 		= $MergeItemCode;
													
	$TSRate 		= $ForOneUnitRate;
	$IGCARRate 		= $IGCARRate1;
	
	$BfCalcTsRate 	= $TSRate;
	$BfCalcIGRate 	= $IGCARRate;
													
	$ItemRate1 		= $TSRate;
	$ItemRate2 		= $IGCARRate;
	
	$CalcIndexArr = array();
													
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
				array_push($CalcIndexArr,$CalcIndex);
				$CalcIndex++;
			}
			$ItemRate1 = $TempAmount1;
			$ItemRate2 = $TempAmount2;
		}
														
	}
	$TSRate 	= $ItemRate1;
	$IGCARRate 	= $ItemRate2;
	$TotalTSAmount = $TotalTSAmount + $TSRate;
	$TotalIGCAmount = $TotalIGCAmount + $IGCARRate;
	$CalcIndexStr = implode(",",$CalcIndexArr);
	?>
	<tr style="vertical-align:middle">
		<td class="labelcenter" nowrap="nowrap">
			<input type="text" class="tboxsmclass icodesd" list="ItemCodeListSD0" style="width:100%" name="txt_item_id_sd[]" id="txt_item_id_sd<?php echo $index2; ?>" data-index="<?php echo $index2; ?>" value="<?php echo $ItemCode; ?>" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListSD0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindDataSheetMasterType($ItemCode); ?>
			</datalist>
			<input type="hidden" name="txt_refid_sd[]" id="txt_refid_sd<?php echo $index2; ?>" value="<?php echo $MergeRefId; ?>">
		</td>
		<td class="labelcenter">
			<input type="text" class="tboxsmclass" style="width:91%" name="txt_desc_sd[]" id="txt_desc_sd<?php echo $index2; ?>" value="<?php echo $ItemDesc; ?>" readonly="" />&nbsp;<i class="fa fa fa-paperclip cmd-box cmd-box-sd" id="CmdBox<?php echo $index2; ?>" data-index="<?php echo $index2; ?>"></i>
			<textarea name="txt_curr_calc_desc_sd[]" id="txt_curr_calc_desc_sd<?php echo $index2; ?>" style="display:none;"><?php echo $CalcDesc; ?></textarea>
			<textarea name="txt_curr_qty_desc_sd[]" id="txt_curr_qty_desc_sd<?php echo $index2; ?>" style="display:none;"></textarea>
			<textarea name="txt_curr_item_desc_alt_sd[]" id="txt_curr_item_desc_alt_sd<?php echo $index2; ?>" style="display:none;"><?php echo $ItemAltDesc; ?></textarea>
			<input type="hidden" name="txt_curr_action_sd[]" id="txt_curr_action_sd<?php echo $index2; ?>" value="<?php echo $CalcAction; ?>">
			<input type="hidden" name="txt_curr_factor_sd[]" id="txt_curr_factor_sd<?php echo $index2; ?>" value="<?php echo $ActionFactor; ?>">
			<input type="hidden" name="txt_curr_calc_index_sd[]" id="txt_curr_calc_index_sd<?php echo $index2; ?>" value="<?php echo $CalcIndexStr; ?>">
			<input type="hidden" name="hid_igc_rate_sd[]" id="hid_igc_rate_sd<?php echo $index2; ?>" value="<?php echo $BfCalcIGRate; ?>">
			<input type="hidden" name="hid_ts_rate_sd[]" id="hid_ts_rate_sd<?php echo $index2; ?>" value="<?php echo $BfCalcTsRate; ?>">
			<input type="hidden" name="hid_calc_type_sd[]" id="hid_calc_type_sd<?php echo $index2; ?>" value="<?php echo $CalcType; ?>">
			<input type="hidden" name="hid_amt_type_sd[]" id="hid_amt_type_sd<?php echo $index2; ?>" value="<?php echo $AmtType; ?>">
			<input type="hidden" name="hid_ref_id_sd[]" id="hid_ref_id_sd<?php echo $index2; ?>" value="<?php echo $MergeRefId; ?>">
			<input type="hidden" name="txt_curr_title_sd[]" id="txt_curr_title_sd<?php echo $index2; ?>" value="<?php echo $Title; ?>">
		</td>
		<td class="labelcenter"><input type="text" class="tboxsmclass rtext tsAmt" style="width:100%" name="txt_ts_rate_sd[]" id="txt_ts_rate_sd<?php echo $index2; ?>" value="<?php echo $TSRate; ?>" readonly="" /></td>
		<td class="labelcenter"><input type="text" class="tboxsmclass rtext igcAmt" style="width:100%" name="txt_igc_rate_sd[]" id="txt_igc_rate_sd<?php echo $index2; ?>" value="<?php echo $IGCARRate; ?>" readonly="" /></td>
		<td class="labelcenter" align='center' nowrap="nowrap">
			<i style="font-size:21px" class="fa faicon-del delete" name="btn_delete" id="btn_delete<?php echo $index2; ?>">&#xf057;</i>
		</td>
	</tr>
	<?php $index2++; } 
	$TotalTSAmount = round($TotalTSAmount,2); 
	$TotalIGCAmount = round($TotalIGCAmount,2); 
	}
	?>
	
	
	<tr>
		<td colspan="2" class="labelboldright rboxlabel" valign="middle">Total<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_total_amount_is" id="txt_total_amount_is" value="<?php  echo $TotalTSAmount; ?>" readonly="" /></td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_total_amount_igc" id="txt_total_amount_igc" value="<?php  echo $TotalIGCAmount; ?>" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
	<tr>
		<td colspan="2" class="labelboldright lboxlabel" valign="middle">
		<div class="div8">
			&nbsp;<input type="checkbox" name="is_average" id="is_average" value="Y" style="margin:0px" <?php if($DMIsAverage == "Y"){ echo ' checked="checked"'; } ?>>&emsp;Is Average required&nbsp;
		</div>
		<div class="div4 rboxlabel">
			Average&nbsp;&nbsp;
		</div>
		<?php 
		if($DMIsAverage == "Y"){
			$AvgCnt = $index2 - 1;
			$AvgTSAmount = round(($TotalTSAmount / $AvgCnt),2);
			$AvgIGCAmount = round(($TotalIGCAmount / $AvgCnt),2);
		}
		?>
		</td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass disable rtext" style="width:100%" name="txt_total_amount_is_avg" id="txt_total_amount_is_avg" value="<?php echo $AvgTSAmount; ?>" readonly="" /></td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass disable rtext" style="width:100%" name="txt_total_amount_igc_avg" id="txt_total_amount_igc_avg" value="<?php echo $AvgIGCAmount; ?>" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
</table>
