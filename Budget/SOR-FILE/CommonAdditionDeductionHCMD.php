<div class="innerdiv2">
	<div class="row divhead head-g" align="center">Addition / Deduction </div>
	<div class="row innerdiv add-ded" align="center">
	<?php $W = $total_a1_amount; $TotalCost = $total_a1_amount; ?>
		<div class="div7 lboxlabel color-1" style="text-align:right">TOTAL</div>
		<div class="div2 lboxlabel rtext">W</div>
		<div class="div3" align="center">
			<input type="text" name="txt_w" id="txt_w" value="<?php echo IndianMoneyFormat($total_a1_amount); ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		<?php $TpAmt = round(($W * $DefValPercArr[1] / 100),2); $TotalCost = $TotalCost + $TpAmt; ?>
		<div class="div6 lboxlabel"><?php echo $DefValNameArr[1]; ?><br/>&nbsp;</div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_tp_perc" id="txt_tp_perc" value="<?php echo $DefValPercArr[1]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div3" align="center">
			<input type="text" name="txt_tp_amt" id="txt_tp_amt" value="<?php if(isset($TpAmt)){ echo IndianMoneyFormat($TP); } ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row">&nbsp;</div>
		
		<?php $SafetyAmt = round(($W * $DefValPercArr[2] / 100),2); $TotalCost = $TotalCost + $SafetyAmt; ?>
		<div class="div6 lboxlabel"><?php echo $DefValNameArr[2]; ?></div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_safety_perc" id="txt_safety_perc" value="<?php echo $DefValPercArr[2]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div3" align="center">
			<input type="text" name="txt_safety_amt" id="txt_safety_amt" value="<?php if(isset($SafetyAmt)){ echo IndianMoneyFormat($SafetyAmt); } ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		
		<?php $ContPrfAmt = round(($W * $DefValPercArr[3] / 100),2); $TotalCost = $TotalCost + $ContPrfAmt; ?>
		<div class="div6 lboxlabel"><?php echo $DefValNameArr[3]; ?></div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_cont_prf_perc" id="txt_cont_prf_perc" value="<?php echo $DefValPercArr[3]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div3" align="center">
			<input type="text" name="txt_cont_prf_amt" id="txt_cont_prf_amt" value="<?php if(isset($ContPrfAmt)){ echo IndianMoneyFormat($ContPrfAmt); } ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		
		<?php $TotalCost = round($TotalCost,2); ?>
		<div class="div6 lboxlabel">Total Cost</div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div1 lboxlabel ltext">&nbsp;</div>
		<div class="div3" align="center">
			<input type="text" name="txt_total_cost" id="txt_total_cost" value="<?php if(isset($TotalCost)){ echo IndianMoneyFormat($TotalCost); } ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		<?php //$TotalCost = round($TotalCost,2); ?>
		<div class="div6 lboxlabel">Cost for 1 <span id="one_unit"></span></div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div1 lboxlabel ltext">&nbsp;</div>
		<div class="div3" align="center">
			<input type="text" name="txt_one_unit_cost" id="txt_one_unit_cost" value="" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel ltext color-1">TW Rate  </div>
		<div class="div3" align="center">
			<input type="text" name="txt_tw_amt" id="txt_tw_amt" value="" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		<div class="div6 lboxlabel"><?php echo $DefValNameArr[5]; ?></div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_sec_res_perc" id="txt_sec_res_perc" value="<?php echo $DefValPercArr[5]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div3" align="center">
			<input type="text" name="txt_sec_res_amt" id="txt_sec_res_amt" value="" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row" style="height:35px">&nbsp;</div>
		
		
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel ltext color-1">IGCAR Rate</div>
		<div class="div3" align="center">
			<input type="text" name="txt_igcar_amt" id="txt_igcar_amt" value="" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row">&nbsp;</div>
		
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel">&nbsp;</div>
		<div class="div3" align="center">
			<select name="cmb_final_unit" id="cmb_final_unit" class="tboxs2mclass">
				<option value=""> - Sel -</option>
				<?php echo $objBind->BindUnit(''); ?>
			</select>
		</div>
		<div class="row clearrow"></div>
	</div>
</div>

<div class="modal fade" id="myModalUC" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<span class="modal-title">Unit Conversion Work Sheet</span>
			</div>
			<div class="modal-body">
				<div class="row clearrow"></div>
				<div><u>Calculation Sheet</u></div>
				<div class="row clearrow"></div>
				<div class="row ModalCalcRowUC NR-ROW-UC" id="ModalCalcRowUC0">
					<div class="div2" align="center"><input type="text" name="txt_modal_from_val_UC" id="txt_modal_from_val_UC" class="tboxsmclass rtext" readonly=""/></div>
					<div class="div2" align="center">
						<select name="cmb_modal_from_unit_UC" id="cmb_modal_from_unit_UC" class="tboxsmclass">
							<option value="">--- Select ---</option>
							<?php echo $objBind->BindUnit(''); ?>
						</select>
					</div>
					<div class="div1" align="center"><i class="fa fa-long-arrow-right" style="font-size:20px"></i></div>
					<div class="div2" align="center"><input type="text" name="txt_modal_to_val_UC" id="txt_modal_to_val_UC" class="tboxsmclass rtext" value="1" readonly="" style="text-align:center"/></div>
					<div class="div2" align="center">
						<select name="cmb_modal_to_unit_UC" id="cmb_modal_to_unit_UC" class="tboxsmclass">
							<option value="">--- Select ---</option>
							<?php echo $objBind->BindUnit(''); ?>
						</select>
					</div>
					<div class="div1" align="center"><i class='fa fa-equals' style='font-size:20px'>=</i></div>
					<div class="div2" align="center"><input type="text" name="txt_new_final_rate_UC" id="txt_new_final_rate_UC" class="tboxsmclass rtext" readonly=""/></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_save_UC">Save</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_cancel_UC">Close</button>
			</div>
		</div>
	</div>
</div>
<?php 
if($UCRows == 1){
	while($UCMList   = mysqli_fetch_object($SelectUnitConvSql)){
		$UCMFromUnit = $UCMList->from_unit;
		$UCMToUnit 	 = $UCMList->to_unit;
		$UCMFactor 	 = $UCMList->factor;
		$UCMAction 	 = $UCMList->action ;
		echo "<input type='hidden' name='txt_".$UCMFromUnit.$UCMToUnit."_factor' id='txt_".$UCMFromUnit.$UCMToUnit."_factor' value='".$UCMFactor."'/>";
		echo "<input type='hidden' name='txt_".$UCMFromUnit.$UCMToUnit."_action' id='txt_".$UCMFromUnit.$UCMToUnit."_action' value='".$UCMAction."'/>";
	}
}
?>