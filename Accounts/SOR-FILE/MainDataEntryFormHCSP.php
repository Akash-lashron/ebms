<table align="center" id="MT" class="itemtable new <?php echo $NewHideClass; ?> DataSheetTable">
	<tr>
		<th colspan="7" style="text-align:left; background:#00C4CD; border:1px solid #00C4CD; color:#fff; padding:4px 3px">&nbsp;Material</th>
	</tr>
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
			<input type="text" class="tboxsmclass icode ClrInput" list="ItemCodeListNew0" style="width:100%" name="txt_code[]" id="txt_code0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListNew0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeMatHC(0); ?>
				<?php //echo $objBind->BindDataSheetMasterType(0); ?>
			</datalist>
		</td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass idesc ClrInput" list="ItemDescListNew0" style="width:90%;" name="txt_desc[]" id="txt_desc0" data-index="0" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemDescListNew0" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeDescMatHC(0); ?>
			</datalist><i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox0" data-index="0"></i>
			<input type="hidden" class="tboxsmclass ClrInput" style="width:100%" name="txt_item_id[]" id="txt_item_id0" value="" readonly="" />
			<textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc0" style="display:none;" class="ClrInput"></textarea>
			<textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt0" style="display:none;" class="ClrInput"></textarea>
			<textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt0" style="display:none;" class="ClrInput"></textarea>
			<input type="hidden" name="txt_curr_action[]" id="txt_curr_action0" class="ClrInput">
			<input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor0" class="ClrInput">
			<input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index0" class="ClrInput">
			<input type="hidden" name="hid_rate[]" id="hid_rate0" class="ClrInput">
			<input type="hidden" name="hid_calc_type[]" id="hid_calc_type0" class="ClrInput">
			<input type="hidden" name="hid_amt_type[]" id="hid_amt_type0" class="ClrInput">
			<input type="hidden" name="hid_ref_id[]" id="hid_ref_id0" class="ClrInput">
			<input type="hidden" name="txt_curr_title[]" id="txt_curr_title0" class="ClrInput">
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext ClrInput" style="width:100%" name="txt_rate[]" id="txt_rate0" value="" readonly="" /></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext ClrInput" style="width:100%" name="txt_unit[]" id="txt_unit0" value="" readonly="" /></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass rtext Qty ClrInput" style="width:100%" data-index="0" name="txt_quantity[]" id="txt_quantity0" value="" />
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt ClrInput" style="width:100%" name="txt_amount[]" id="txt_amount0" value="" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
			<i style="font-size:21px" class="fa faicon-add AddIcon" data-index="0" name="btn_add" id="btn_add" onClick="addrow()">&#xf01a;</i>
			<i style="font-size:21px" class="fa faicon-clr ClrIcon" data-index="0" name="btn_clear" id="btn_clear" onClick="cleartxt()">&#xf05c;</i>
		</td>
	</tr>
	<tr>
		<td colspan="5" class="labelboldright rboxlabel" valign="middle">Sub-Total<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="labelfieldright disable" style="width:100%" name="txt_sub_total_amount" id="txt_sub_total_amount" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
	<tr>
		<td class="labelcenter" nowrap="nowrap" valign="middle"></td>
		<td class="lcelllabel" nowrap="nowrap" valign="middle" colspan="2">
		<?php echo $DefValNameArr[7]; ?> - <?php echo $DefValPercArr[7]; ?> ( % )
		<input type="hidden" name="txt_trans_desc" id="txt_trans_desc" value="<?php echo $DefValNameArr[7]; ?>">
		<input type="hidden" name="txt_trans_perc" id="txt_trans_perc" value="<?php echo $DefValPercArr[7]; ?>">
		</td>
		<td class="labelcenter" valign="middle"></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle"></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt" style="width:100%" name="txt_trans_amount" id="txt_trans_amount" value="" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
		</td>
	</tr>
	<tr>
		<td class="labelcenter" nowrap="nowrap" valign="middle"></td>
		<td class="lcelllabel" nowrap="nowrap" valign="middle" colspan="2">
		<?php echo $DefValNameArr[8]; ?> - <?php echo $DefValPercArr[8]; ?> ( % )
		<input type="hidden" name="txt_psp_desc" id="txt_psp_desc" value="<?php echo $DefValNameArr[8]; ?>">
		<input type="hidden" name="txt_psp_perc" id="txt_psp_perc" value="<?php echo $DefValPercArr[8]; ?>">
		</td>
		<td class="labelcenter" valign="middle"></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle"></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt" style="width:100%" name="txt_psp_amount" id="txt_psp_amount" value="" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
		</td>
	</tr>
	<tr>
		<td colspan="5" class="labelboldright rboxlabel" valign="middle">Total (W1)<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="labelfieldright disable" style="width:100%" name="txt_mat_total_amount" id="txt_mat_total_amount" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
</table>
<div class="erow"></div>


<table align="center" id="LT" class="itemtable new <?php echo $NewHideClass; ?> DataSheetTable">
	<tr>
		<th colspan="7" style="text-align:left; background:#00C4CD; border:1px solid #00C4CD; color:#fff; padding:4px 3px">&nbsp;Labour</th>
	</tr>
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
			<input type="text" class="tboxsmclass icode ClrInput" list="ItemCodeListNew00" style="width:100%" name="txt_code[]" id="txt_code00" data-index="00" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemCodeListNew00" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeLabHC(0); ?>
				<?php //echo $objBind->BindDataSheetMasterType(0); ?>
			</datalist>
		</td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass idesc ClrInput" list="ItemDescListNew00" style="width:90%;" name="txt_desc[]" id="txt_desc00" data-index="00" value="" autocomplete="off" placeholder=" &#61442; Search "/>
			<datalist id="ItemDescListNew00" style="color:#C80B5B; font-size:16px">
				<?php echo $objBind->BindItemCodeDescLabHC(0); ?>
			</datalist><i class="fa fa fa-paperclip cmd-box cmd-box-m" id="CmdBox00" data-index="00"></i>
			<input type="hidden" class="tboxsmclass ClrInput" style="width:100%" name="txt_item_id[]" id="txt_item_id00" value="" readonly="" />
			<textarea name="txt_curr_calc_desc[]" id="txt_curr_calc_desc00" style="display:none;" class="ClrInput"></textarea>
			<textarea name="txt_curr_qty_desc_alt[]" id="txt_curr_qty_desc_alt00" style="display:none;" class="ClrInput"></textarea>
			<textarea name="txt_curr_item_desc_alt[]" id="txt_curr_item_desc_alt00" style="display:none;" class="ClrInput"></textarea>
			<input type="hidden" name="txt_curr_action[]" id="txt_curr_action00" class="ClrInput">
			<input type="hidden" name="txt_curr_factor[]" id="txt_curr_factor00" class="ClrInput">
			<input type="hidden" name="txt_curr_calc_index[]" id="txt_curr_calc_index00" class="ClrInput">
			<input type="hidden" name="hid_rate[]" id="hid_rate00" class="ClrInput">
			<input type="hidden" name="hid_calc_type[]" id="hid_calc_type00" class="ClrInput">
			<input type="hidden" name="hid_amt_type[]" id="hid_amt_type00" class="ClrInput">
			<input type="hidden" name="hid_ref_id[]" id="hid_ref_id00" class="ClrInput">
			<input type="hidden" name="txt_curr_title[]" id="txt_curr_title00" class="ClrInput">
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext ClrInput" style="width:100%" name="txt_rate[]" id="txt_rate00" value="" readonly="" /></td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass ctext ClrInput" style="width:100%" name="txt_unit[]" id="txt_unit00" value="" readonly="" /></td>
		<td class="labelcenter" nowrap="nowrap" valign="middle">
			<input type="text" class="tboxsmclass rtext Qty ClrInput" style="width:100%" data-index="00" name="txt_quantity[]" id="txt_quantity00" value="" />
		</td>
		<td class="labelcenter" valign="middle"><input type="text" class="tboxsmclass rtext NewAmt ClrInput" style="width:100%" name="txt_amount[]" id="txt_amount00" value="" readonly="" /></td>
		<td class="labelcenter" align='center' valign="middle" nowrap="nowrap">
			<i style="font-size:21px" class="fa faicon-add AddIcon" data-index="00" name="btn_add" id="btn_add" onClick="addrow()">&#xf01a;</i>
			<i style="font-size:21px" class="fa faicon-clr ClrIcon" data-index="00" name="btn_clear" id="btn_clear" onClick="cleartxt()">&#xf05c;</i>
		</td>
	</tr>
	<tr>
		<td colspan="5" class="labelboldright rboxlabel" valign="middle">Total (W2)<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="labelfieldright disable" style="width:100%" name="txt_lab_total_amount" id="txt_lab_total_amount" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
</table>
<div class="erow"></div>
<table align="center" id="tab_a1_material" class="itemtable new <?php echo $NewHideClass; ?>" width="100%">
	<tr>
		<th colspan="6" style="text-align:left; background:#00C4CD; border:1px solid #00C4CD; color:#fff; padding:1px 1px">&nbsp;Total (W = W1 + W2)&nbsp;&nbsp;</th>
		<th style="padding:0px 0px; width:25%">
			<input type="text" class="tboxsmclass ctext Qty disable" style="width:100%" data-index="00" name="txt_total_amount" id="txt_total_amount" value="" />
		</th>
	</tr>
</table>
