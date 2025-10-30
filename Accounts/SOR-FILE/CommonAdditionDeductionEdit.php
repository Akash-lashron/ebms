<div class="innerdiv2">
	<div class="row divhead head-g" align="center">Addition / Deduction </div>
	<div class="row innerdiv add-ded" align="center">
	<?php //$W = $total_a1_amount; ?>
		<div class="div7 lboxlabel color-1" style="text-align:right">TOTAL</div>
		<div class="div2 lboxlabel rtext">W</div>
		<div class="div3" align="center">
			<input type="text" name="txt_w" id="txt_w" value="<?php echo $W; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$A = round(($W * $DefValPercArr[1] / 100),2); ?>
		<div class="div5 lboxlabel"><?php echo $DefValNameArr[1]; ?></div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_a_per" id="txt_a_per" value="<?php echo $DefValPercArr[1]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div1 lboxlabel rtext"><?php echo $DefValCodeArr[1]; ?></div>
		<div class="div3" align="center">
			<input type="text" name="txt_a" id="txt_a" value="<?php echo $A; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$WC = round(($W + $A),2); ?>
		<div class="div5 lboxlabel">WC</div>
		<div class="div2 lboxlabel ltext">W+A</div>
		<div class="div1 lboxlabel rtext">&nbsp;</div>
		<div class="div1 lboxlabel rtext">&nbsp;</div>
		<div class="div3" align="center">
			<input type="text" name="txt_wc" id="txt_wc" value="<?php echo $WC; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$B = round(($DefValPercArr[6] * $WC),2); ?>
		<div class="div5 lboxlabel"><?php echo $DefValCodeArr[6]; ?> = (<?php echo $DefValPercArr[6]; ?> * WC)</div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div3" align="center">
			<input type="text" name="txt_b" id="txt_b" value="<?php echo $B; ?>" readonly="" class="tboxs2mclass rtext" />
			<input type="hidden" name="txt_b_per" id="txt_b_per" value="<?php echo $DefValPercArr[6]; ?>">
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$X = round(($B + $WC),2); ?>
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div2 lboxlabel rtext">X</div>
		<div class="div3" align="center">
			<input type="text" name="txt_x" id="txt_x" value="<?php echo $X; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$C = round(($X * $DefValPercArr[2] / 100),2); ?>
		<div class="div5 lboxlabel">Add <?php echo $DefValNameArr[2]; ?> on (X)</div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_c_per" id="txt_c_per" value="<?php echo $DefValPercArr[2]; ?>" size="2" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div1 lboxlabel rtext"><?php echo $DefValCodeArr[2]; ?></div>
		<div class="div3" align="center">
			<input type="text" name="txt_c" id="txt_c" value="<?php echo $C; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$Y = round(($X + $C),2); ?>
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div2 lboxlabel rtext">&nbsp;</div>
		<div class="div2 lboxlabel rtext">Y</div>
		<div class="div3" align="center">
			<input type="text" name="txt_y" id="txt_y" value="<?php echo $Y; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$D = round(($Y * $DefValPercArr[3] / 100),2); ?>
		<div class="div5 lboxlabel"><?php echo $DefValNameArr[3]; ?> on Y</div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_d_per" id="txt_d_per" value="<?php echo $DefValPercArr[3]; ?>" size="2" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div1 lboxlabel rtext"><?php echo $DefValCodeArr[3]; ?></div>
		<div class="div3" align="center">
			<input type="text" name="txt_d" id="txt_d" value="<?php echo $D; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //$E = round(($W * $DefValPercArr[4] / 100),2); ?>
		<div class="div5 lboxlabel"><?php echo $DefValNameArr[4]; ?> on W</div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_e_per" id="txt_e_per" value="<?php echo $DefValPercArr[4]; ?>" size="2" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div1 lboxlabel rtext"><?php echo $DefValCodeArr[4]; ?></div>
		<div class="div3" align="center">
			<input type="text" name="txt_e" id="txt_e" value="<?php echo $E; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
											
		<?php //$F = round(($Y+$D+$E),2); ?>
		<div class="div8 lboxlabel">Total Y+D+E Cost of <?php echo $DMQty; echo '&nbsp;'; echo $DMUnit; ?></div>
		<div class="div1 lboxlabel rtext">F</div>
		<div class="div3" align="center">
			<input type="text" name="txt_f" id="txt_f" value="<?php echo $F; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<?php if($DMToUnit != ""){ $DMUnitDp = $DMFinalUnit; }else{ $DMUnitDp = $DMUnit; } ?>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //if($DMQty != ''){ $ForOneUnit = round(($F/$DMQty),2); }else{ $ForOneUnit = round(($F/1),2); } ?>
		
		
		<div class="div9 lboxlabel">Cost of 1 <span id="one_unit"><?php echo $DMToUnit; ?></span> <i class="fa fa fa-paperclip cmd-box cmd-box-unit" id="CmdBox0" data-index="0"></i></div>
		<div class="div3" align="center">
			<input type="text" name="txt_cost_one" id="txt_cost_one" value="<?php echo $ForOneUnit; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<input type="hidden" name="hid_to_unit" id="hid_to_unit" value="<?php echo $DMToUnit; ?>"/>
		<div class="row clearrow"><hr align="left" width="100%"></div>
											
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel ltext color-1">TW Rate</div>
		<div class="div3" align="center">
			<input type="text" name="txt_township_amt" id="txt_township_amt" value="<?php echo $ForOneUnit; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<?php //if($DMQty != ''){ $G = round(($W*$DefValPercArr[5] / (100 * $DMQty)),2); }else{ $G = round(($W*$DefValPercArr[5] / 100),2); } ?>
		<div class="div5 lboxlabel">Add <?php echo $DefValNameArr[5]; ?> on W per 1 unit</div>
		<div class="div2 lboxlabel rtext"><input type="text" name="txt_g_per" id="txt_g_per" value="<?php echo $DefValPercArr[5]; ?>" class="tboxs2mclass rtext" readonly="" /></div>
		<div class="div1 lboxlabel ltext">%</div>
		<div class="div1 lboxlabel rtext">&nbsp;</div>
		<div class="div3" align="center">
			<input type="text" name="txt_g" id="txt_g" value="<?php echo $G; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		<div class="row clearrow"><hr align="left" width="100%"></div>
											
		<?php //$IGCAR = round(($ForOneUnit+$G),2); ?>
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel ltext color-1">IGCAR Rate  <?php //if($DMUnit != ''){ echo 'Per 1 '.$DMUnit; } ?></div>
		<div class="div3" align="center">
			<input type="text" name="txt_igcar1_amt" id="txt_igcar1_amt" value="<?php echo $IGCAR; ?>" readonly="" class="tboxs2mclass rtext" />
		</div>
		
		<div class="row clearrow"><hr align="left" width="100%"></div>
		<div class="div5 lboxlabel">&nbsp;</div>
		<div class="div4 lboxlabel">&nbsp;</div>
		<div class="div3" align="center">
			<?php if($DMToUnit != ""){ $DMUnit = $DMToUnit; }else{ if($DMFinalUnit != ""){ $DMUnit = $DMFinalUnit; }else{ $DMUnit = $DMUnit; } } ?>
			<select name="cmb_final_unit" id="cmb_final_unit" class="tboxs2mclass">
				<option value=""> - Sel -</option>
				<?php echo $objBind->BindUnit($DMUnit); ?>
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
	$SelectUnitConvQuery = "select * from unit_conversion";
	$SelectUnitConvSql 	 = mysqli_query($dbConn,$SelectUnitConvQuery);
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