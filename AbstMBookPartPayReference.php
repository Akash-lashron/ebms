<?php
//print_r($PPayRefArr);
$PrevPPItemId = ""; $PPayRefStr = "";
foreach($PPayRefArr as $PPItemId => $IPPtemIdStr){
	$SelectPartpayRefQuery = "select * from pp_qty_splt where rbn = '$rbn' and sheetid = '$abstsheetid' and subdivid = '$PPItemId'";
	$SelectPartpayRefSql = mysql_query($SelectPartpayRefQuery);
	if($SelectPartpayRefSql == true){
		$PPayRefCnt = mysql_num_rows($SelectPartpayRefSql);
		if($PPayRefCnt>0){ $PPRefAmt = 0; $PPRefTotAmt = 0; $PPRefQty = 0; $PPRefTotQty = 0;
?>
	<table bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' width="700px">
		<tr class="labelbold">
			<td align="center">Item No.</td>
			<td align="right">Rate</td>
			<td align="right">Qty.</td>
			<td align="right">Percent (%)</td>
			<td align="right">Amount</td>
		</tr>
<?php while($PPayRefList = mysql_fetch_object($SelectPartpayRefSql)){ 
		$PPRefAmt = round(($PPayRefList->qty * $PPayRefList->percent * $PPayRefList->rate / 100),2);
		$PPRefTotAmt = $PPRefTotAmt + $PPRefAmt; 
		$PPRefTotQty = $PPRefTotQty + $PPayRefList->qty;
		if($PrevPPItemId != $PPItemId){ ?>
		<tr>
			<td rowspan="<?php echo $PPayRefCnt; ?>" align="center"><?php echo $IPPtemIdStr[0]; ?></td>
			<td align="right" rowspan="<?php echo $PPayRefCnt; ?>"><?php echo $PPayRefList->rate; ?></td>
			<td align="right"><?php echo number_format($PPayRefList->qty, $IPPtemIdStr[3], '.', ''); ?></td>
			<td align="right"><?php echo $PPayRefList->percent; ?></td>
			<td align="right"><?php echo number_format($PPRefAmt, 2, '.', ''); ?></td>
		</tr>	
<?php }else{ ?>
		<tr>
			<td align="right"><?php echo number_format($PPayRefList->qty, $IPPtemIdStr[3], '.', ''); ?></td>
			<td align="right"><?php echo $PPayRefList->percent; ?></td>
			<td align="right"><?php echo number_format($PPRefAmt, 2, '.', ''); ?></td>
		</tr>	
<?php } $PrevPPItemId = $PPItemId; } $PPRefTotAmt = round($PPRefTotAmt,2); $PPRefTotQty = round($PPRefTotQty,$IPPtemIdStr[3]); ?>
		<tr class="labelbold">
			<td align="right" colspan="2">Reference : B/f Page <?php echo $IPPtemIdStr[5]; ?> / MB <?php echo $IPPtemIdStr[4]; ?> </td>
			<td align="right"><?php echo number_format($PPRefTotQty, $IPPtemIdStr[3], '.', ''); ?></td>
			<td align="right">&nbsp;</td>
			<td align="right"><?php echo number_format($PPRefTotAmt, 2, '.', ''); ?></td>
		</tr>	
	</table>
	<br/>
<?php $PPayRefStr .= $IPPtemIdStr[6]."@@".$abstmbno."@@".$page."@@"; } } } $PPayRefStr = rtrim($PPayRefStr,"@@"); ?>
<input type="hidden" name="txt_ppayStr" id="txt_ppayStr" value="<?php echo $PPayRefStr; ?>" />

