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
	<tr>
		<td colspan="5" class="labelboldright rboxlabel" valign="middle">Total<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="labelfieldright" style="width:100%" name="txt_total_amount" id="txt_total_amount" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
</table>
