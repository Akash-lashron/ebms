<?php
include "spellnumber.php";
function getUptoDateQtyUsed($sheetid,$subdivid){
	$TotalUsedQty = 0; $DPMQty = 0; $SLMQty = 0;
	$SelectDPMQtyQuery 	= "select sum(mbtotal) as dpmqty from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and (part_pay_flag = '0' OR part_pay_flag = '1') and mbtotal IS NOT NULL";
	$SelectDPMQtySql 	= mysql_query($SelectDPMQtyQuery);
	if($SelectDPMQtySql == true){
		if(mysql_num_rows($SelectDPMQtySql)>0){
			$DQList = mysql_fetch_object($SelectDPMQtySql);
			$DPMQty = $DQList->dpmqty;
		}
	}
	$SelectSLMQtyQuery 	= "select sum(mbtotal) as slmqty from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$subdivid' and (part_pay_flag = '0' OR part_pay_flag = '1') and mbtotal IS NOT NULL";
	$SelectSLMQtySql 	= mysql_query($SelectSLMQtyQuery);
	if($SelectSLMQtySql == true){
		if(mysql_num_rows($SelectSLMQtySql)>0){
			$SQList = mysql_fetch_object($SelectSLMQtySql);
			$SLMQty = $SQList->slmqty;
		}
	}
	$TotalUsedQty = $DPMQty + $SLMQty;
	return $TotalUsedQty;
}

function getSLMItemAmount($sheetid,$subdivid,$ItemRate,$rbn){
	$TotalAmount = 0; $SlmAmount = 0;
	$SelectSLMAmtQuery1 = "select mbtotal, pay_percent from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$subdivid' and mbtotal IS NOT NULL";
	$SelectSLMAmtSql1 	= mysql_query($SelectSLMAmtQuery1);
	if($SelectSLMAmtSql1 == true){
		if(mysql_num_rows($SelectSLMAmtSql1)>0){
			while($SAList1 	 = mysql_fetch_object($SelectSLMAmtSql1)){
				$SLMQty1 	 = $SAList1->mbtotal;
				$SLMPerc1 	 = $SAList1->pay_percent;
				$SLMAmt1 	 = round(($SLMQty1 * $ItemRate * $SLMPerc1 / 100),2);
				$TotalAmount = round(($TotalAmount + $SLMAmt1),2);
			}
		}
	}
	
	$SelectSLMAmtQuery2 = "select qty, percent from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$subdivid' and rbn = '$rbn'";
	$SelectSLMAmtSql2 	= mysql_query($SelectSLMAmtQuery2);
	if($SelectSLMAmtSql2 == true){
		if(mysql_num_rows($SelectSLMAmtSql2)>0){
			while($SAList2 	 = mysql_fetch_object($SelectSLMAmtSql2)){
				$SLMQty2 	 = $SAList2->qty;
				$SLMPerc2 	 = $SAList2->percent;
				$SLMAmt2 	 = round(($SLMQty2 * $ItemRate * $SLMPerc2 / 100),2);
				$TotalAmount = round(($TotalAmount + $SLMAmt2),2);
			}
		}
	}
	return $TotalAmount;
}
function getDPMItemAmount($sheetid,$subdivid,$ItemRate,$rbn){
	$TotalAmount = 0; $SlmAmount = 0;
	$SelectSLMAmtQuery1 = "select mbtotal, pay_percent from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and mbtotal IS NOT NULL";
	$SelectSLMAmtSql1 	= mysql_query($SelectSLMAmtQuery1);
	if($SelectSLMAmtSql1 == true){
		if(mysql_num_rows($SelectSLMAmtSql1)>0){
			while($SAList1 	 = mysql_fetch_object($SelectSLMAmtSql1)){
				$SLMQty1 	 = $SAList1->mbtotal;
				$SLMPerc1 	 = $SAList1->pay_percent;
				$SLMAmt1 	 = $SLMQty1 * $ItemRate * $SLMPerc1 / 100;//round(($SLMQty1 * $ItemRate * $SLMPerc1 / 100),2);
				$TotalAmount = $TotalAmount + $SLMAmt1;//round(($TotalAmount + $SLMAmt1),2);
			}
		}
	}
	
	$SelectSLMAmtQuery2 = "select qty, percent from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$subdivid' and rbn < '$rbn'";
	$SelectSLMAmtSql2 	= mysql_query($SelectSLMAmtQuery2);
	if($SelectSLMAmtSql2 == true){
		if(mysql_num_rows($SelectSLMAmtSql2)>0){
			while($SAList2 	 = mysql_fetch_object($SelectSLMAmtSql2)){
				$SLMQty2 	 = $SAList2->qty;
				$SLMPerc2 	 = $SAList2->percent;
				$SLMAmt2 	 = $SLMQty2 * $ItemRate * $SLMPerc2 / 100;//round(($SLMQty2 * $ItemRate * $SLMPerc2 / 100),2);
				$TotalAmount = $TotalAmount + $SLMAmt2;//round($TotalAmount + $SLMAmt2),2);
			}
		}
	}
	return $TotalAmount;
}
$RebatePercArr = array();
$RebatePercArr[$sheetid] = $overall_rebate_perc;
$SelectPartAgmtQuery = "select supp_sheet_id, rebate_percent from sheet_supplementary where sheetid = '$sheetid'";
$SelectPartAgmtSql = mysql_query($SelectPartAgmtQuery);
if($SelectPartAgmtSql == true){
	if(mysql_num_rows($SelectPartAgmtSql)>0){
		while($PAList = mysql_fetch_object($SelectPartAgmtSql)){
			$RebatePercArr[$PAList->supp_sheet_id] = $PAList->rebate_percent;
		}
	}
}
//print_r($RebatePercArr);exit;
$ItemIdArr = array(); $SLMItemArr = array(); $DPMItemArr = array();
$SelectItemIdQuery 	= "(SELECT subdivid FROM mbookgenerate WHERE sheetid = '$sheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$sheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$SelectItemIdSql 	= mysql_query($SelectItemIdQuery);
if($SelectItemIdSql == true){
	if(mysql_num_rows($SelectItemIdSql)>0){
		while($IDList = mysql_fetch_object($SelectItemIdSql)){
			if($IDList->subdivid != 0){
				array_push($ItemIdArr,$IDList->subdivid);
			}
		}
	}
}
$SlmDpmNetAmount = 0; $DpmNetAmount = 0; $SlmNetAmount = 0;
if(count($ItemIdArr)>0){
	natsort($ItemIdArr);
	foreach($ItemIdArr as $subdivid){
		$SelectItemDtQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
		$SelectItemDtSql = mysql_query($SelectItemDtQuery);
		if($SelectItemDtSql == true){
			if(mysql_num_rows($SelectItemDtSql)>0){
				$IDDtList 		= mysql_fetch_object($SelectItemDtSql);
				$SubDivName 	= $IDDtList->sno;
				$Description 	= $IDDtList->description;
				$ShortNotes 	= $IDDtList->shortnotes;
				$ItemUnit 		= $IDDtList->per;
				$ItemRate 		= $IDDtList->rate;
				$ItemDecimal 	= $IDDtList->decimal_placed;
				$SupplSheetId 	= $IDDtList->supp_sheet_id;
				if($SupplSheetId == 0){
					$RebatePercent  = $RebatePercArr[$sheetid];
				}else{
					$RebatePercent  = $RebatePercArr[$SupplSheetId];
				}
				
				if($ShortNotes != ''){
					$ItemDesc = $ShortNotes;
				}else{
					$ItemDesc = $Description;
				}
				$UptoDateQty = getUptoDateQtyUsed($sheetid,$subdivid);
				$UptoDateQty = round($UptoDateQty,$ItemDecimal);
				
				$SLMItemAmount = getSLMItemAmount($sheetid,$subdivid,$ItemRate,$rbn);
				$DPMItemAmount = getDPMItemAmount($sheetid,$subdivid,$ItemRate,$rbn);
				
				$SLMItemRebateAmt = round(($SLMItemAmount * $RebatePercent / 100),2);
				$DPMItemRebateAmt = round(($DPMItemAmount * $RebatePercent / 100),2);
				
				$SLMItemAmount = $SLMItemAmount - $SLMItemRebateAmt;
				$DPMItemAmount = $DPMItemAmount - $DPMItemRebateAmt;
				
				$SlmNetAmount = $SlmNetAmount + $SLMItemAmount;
				$DpmNetAmount = $DpmNetAmount + $DPMItemAmount;
				
				$SLMItemAmount = round($SLMItemAmount,2);
				$DPMItemAmount = round($DPMItemAmount,2);
				$UptoDateAmount = round(($SLMItemAmount + $DPMItemAmount),2);
				
				?>
				<tr border='1' class="labelprint">
					<td align='center' class='labelbold' nowrap="nowrap"><?php echo $SubDivName; ?></td>
					<td align='left' class="hideText"><?php echo $ItemDesc; ?></td>
					<td align='center'><?php echo $ItemUnit; ?></td>
					<td align='right'><?php echo $ItemRate; ?></td>
					<td align='right'><?php echo number_format($UptoDateQty, $ItemDecimal, '.', ''); ?></td>
					<td align='right'><?php echo number_format($UptoDateAmount, 2, '.', ''); ?></td>
					<td align='right'><?php echo number_format($SLMItemAmount, 2, '.', ''); ?></td>
					<td align='right'>&nbsp;</td>
				</tr>
				<?php
			}
		}
	}
}
$SlmNetAmount 		= round($SlmNetAmount,2);
$DpmNetAmount 		= round($DpmNetAmount,2);
$SlmDpmNetAmount 	= round(($SlmNetAmount + $DpmNetAmount),2);

/*$SlmRebateAmount 	= round(($SlmNetAmount * $overall_rebate_perc / 100),2);
$DpmRebateAmount 	= round(($DpmNetAmount * $overall_rebate_perc / 100),2);

$SlmNetAmount 		= $SlmNetAmount - $SlmRebateAmount;
$DpmNetAmount 		= $DpmNetAmount - $DpmRebateAmount;
$SlmDpmNetAmount 	= round(($SlmNetAmount + $DpmNetAmount),2);*/
?>
<style>
.hideText{
	max-width : 250px; 
	white-space : nowrap;
	overflow : hidden;
	text-overflow: ellipsis;
}
</style>