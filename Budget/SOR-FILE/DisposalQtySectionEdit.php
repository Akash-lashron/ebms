<div class="row clearrow  new <?php echo $NewHideClass; ?>"></div>
<div class="row rboxlabel  new <?php echo $NewHideClass; ?>">
	TW Rate <input type="text" name="txt_ts_rate_view1" id="txt_ts_rate_view1" value="<?php echo $ForOneUnit; ?>" class="tboxsmclass rtext" style="width:100px;">&emsp;
	IGCAR Rate <input type="text" name="txt_igc_rate_view1" id="txt_igc_rate_view1" value="<?php echo $IGCAR; ?>" class="tboxsmclass rtext" style="width:100px;">
</div>
<div class="row clearrow new disprow  <?php echo $NewHideClass; ?> <?php echo $DQHideClass; ?>"></div>
<div class="row lboxlabel new disprow  <?php echo $NewHideClass; ?> <?php echo $DQHideClass; ?>">
	&emsp; <input type="checkbox" name="disposal_qty" id="disposal_qty" class="disposal_qty" value="Y" <?php if(($DMDispQtyPerc != 0)&&($DMDispQtyPerc != '')){ echo 'checked="checked"'; } ?>>&nbsp; Is disposal qty. ? 
	<input type="text" name="txt_disp_qty_prec" id="txt_disp_qty_prec" value="<?php echo $DMDispQtyPerc; ?>" class="tboxsmclass rtext disposal <?php if(($DMDispQtyPerc == 0)||($DMDispQtyPerc == '')){ ?> hide <?php } ?>" style="width:50px;"> <span class="disposal <?php if(($DMDispQtyPerc == 0)||($DMDispQtyPerc == '')){ ?> hide <?php } ?>">( % )</span>
</div>
<?php 
if(($DMDispQtyPerc != 0)&&($DMDispQtyPerc != '')){ 
	$DispTsRate = round(($ForOneUnit * $DMDispQtyPerc / 100),2);
	$DispIGCARRate = round(($IGCAR * $DMDispQtyPerc / 100),2);
}
?>
<div class="row clearrow disposal disprow <?php echo $DQHideClass; ?>"></div>
	<div class="row rboxlabel disposal disprow <?php echo $DQHideClass; ?>">
	TW Rate <input type="text" name="txt_ts_rate_view2" id="txt_ts_rate_view2" value="<?php echo $DispTsRate; ?>" class="tboxsmclass rtext" style="width:100px;">&emsp;
	IGCAR Rate <input type="text" name="txt_igc_rate_view2" id="txt_igc_rate_view2" value="<?php echo $DispIGCARRate; ?>" class="tboxsmclass rtext" style="width:100px;">
</div>