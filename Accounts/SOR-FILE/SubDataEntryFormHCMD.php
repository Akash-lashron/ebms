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
	<tr>
		<td colspan="2" class="labelboldright rboxlabel" valign="middle">Total<!-- of A1-->&nbsp;&nbsp;</td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_total_amount_is" id="txt_total_amount_is" value="" readonly="" /></td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass rtext" style="width:100%" name="txt_total_amount_igc" id="txt_total_amount_igc" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
	<tr>
		<td colspan="2" class="labelboldright lboxlabel" valign="middle">
		<div class="div8">
			&nbsp;<input type="checkbox" name="is_average" id="is_average" value="Y" style="margin:0px">&emsp;Is Average required&nbsp;
		</div>
		<div class="div4 rboxlabel">
			Average&nbsp;&nbsp;
		</div>
		</td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass disable rtext" style="width:100%" name="txt_total_amount_is_avg" id="txt_total_amount_is_avg" value="" readonly="" /></td>
		<td class="labelboldcenter"><input type="text" class="tboxsmclass disable rtext" style="width:100%" name="txt_total_amount_igc_avg" id="txt_total_amount_igc_avg" value="" readonly="" /></td>
		<td class="labelboldcenter"></td>
	</tr>
</table>
