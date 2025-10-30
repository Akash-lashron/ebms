

<?php
$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 					= 	mysql_fetch_object($sqlquery);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor; 
	$ccno 					= 	$List->computer_code_no;    
	$agree_no 				= 	$List->agree_no; 
	$overall_rebate_perc 	= 	$List->rebate_percent; 
	$runn_acc_bill_no 		= 	$rbn;
	$work_order_no 			= 	$List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length1 				= 	strlen($work_name);
 	$start_line1 			= 	ceil($length1/70); 
	$length2 				= 	strlen($agree_no);
	$start_line2 			= 	ceil($length2/27);  
	$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
}
//$selectRbnQuery 	= "select max(rbn) as prev_rbn from secured_advance where sheetid = '$sheetid' and rbn < '$rbn'";
$selectRbnQuery 	= "select * from secured_advance where sheetid = '$sheetid' and rbn = (select max(rbn) from secured_advance where sheetid = '$sheetid' and rbn < '$rbn')";
$selectRbnSql 		= mysql_query($selectRbnQuery);
if($selectRbnSql == true){
	$RabList 		= mysql_fetch_object($selectRbnSql);
	$PrevRbn 		= $RabList->rbn;
	//$PrevSaAmountTmp 	= $RabList->sec_adv_amount;
	$PrevSaAmountTmp 	= $RabList->upto_dt_ots_amt;
	
	$PrevSaMBno 	= $RabList->mbookno;
	$PrevSaPage 	= $RabList->page;
	$PrevSaAmount = 0; $Exe = 0;
	$selectPrevAmtQuery 	= "select upto_dt_amt from secured_advance_dt where sheetid = '$sheetid' and rbn = '$PrevRbn'";
	$selectPrevAmtSql 		= mysql_query($selectPrevAmtQuery);
	if($selectPrevAmtSql == true){
		while($PrevAmtList 	= mysql_fetch_object($selectPrevAmtSql)){
			$PrevSaAmount 	= $PrevSaAmount + $PrevAmtList->upto_dt_amt;
			$Exe++;
		}
	}
	if($Exe == 0){
		//$PrevSaAmount = round($PrevSaAmountTmp,2);
	}else{
		//$PrevSaAmount = round($PrevSaAmount,2);
	}
	$PrevSaAmount = round($PrevSaAmountTmp,2);
	//$PrevSaAmount = round($PrevSaAmount,2);
}

/*$selectRbnQuery 	= "select max(rbn) as prev_rbn, sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn < '$rbn' group by sheetid";
$selectRbnSql 		= mysql_query($selectRbnQuery);
if($selectRbnSql == true){
	$RabList 		= mysql_fetch_object($selectRbnSql);
	$PrevRbn 		= $RabList->prev_rbn;
	$PrevSaAmount 	= $RabList->sec_adv_amount;
}*/
//echo $PrevRbn;exit;
//$page = $abstmbpage;
$satitle1 = '<table width="1087px" border="0" align="center" bgcolor="#FFFFFF" style="border:none; line-height:12px" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$mbno.'&nbsp;&nbsp;&nbsp;</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">FORM 26 A</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;" class="labelbold">ACCOUNT OF SECURED ADVANCES</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">(<i>Referred to in paragraphs 10.2.14</i>)</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">(<i>To be annexed to Form 26 where necessary</i>)</td></tr>
		 </table>';
echo $satitle1;

$satitle2 = $satitle2 . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >";
$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td width='17%' class=''>Division : </td>";
$satitle2 = $satitle2 . "<td width='23%' style='word-wrap:break-word' class=''>FRFCF</td>";
$satitle2 = $satitle2 . "<td width='18%' class=''>Name of the work : </td>";
$satitle2 = $satitle2 . "<td width='42%' class=''>" . $work_name . "</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Name of the Contractor : </td>";
$satitle2 = $satitle2 . "<td class=''>" . $name_contractor . "</td>";
$satitle2 = $satitle2 . "<td class=''>Technical Sanction No. : </td>";
$satitle2 = $satitle2 . "<td class=''>".$tech_sanction."</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Cash Book Voucher No. : </td>";
$satitle2 = $satitle2 . "<td class=''>------------------- Dated:</td>";
$satitle2 = $satitle2 . "<td class=''>Work order No. : </td>";
$satitle2 = $satitle2 . "<td class=''>".$work_order_no."</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Running Account bill No. :</td>";
$satitle2 = $satitle2 . "<td class=''>" . $rbn . "</td>";
$satitle2 = $satitle2 . "<td class=''>Agreement No. :</td>";
$satitle2 = $satitle2 . "<td class=''>" . $agree_no . "</td>";
$satitle2 = $satitle2 . "</tr>";
$satitle2 = $satitle2 . "</table>";
echo $satitle2;
?>
<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >
	<tr>
		<td class="labelbold">Civil Secured Advance</td>
		<td class="labelbold"> Account of Secured Advance allowed on the Security Materials Brought to Site</td>
	</tr>
</table>
<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >
	<tr>
		<td align="center">Sl No.</td>
		<td align="center">Item No.</td>
		<td align="center">Quantity Outstanding from previous bill</td>
		<td align="center">Deduct Quantity utilized in work measured since previous bill</td>
		<td align="center">Add Qty brought to site</td>
		<td align="center">outstanding including quantity brought to site since previous bill</td>
		<td align="center">Full rate assessed by the Divisional Officer</td>
		<td align="center">Description of Item</td>
		<td align="center">Unit</td>
		<td align="center">Reduced rate at which rate is made</td>
		<td align="center">Up-to-date amount of advance</td>
		<td align="center">Reference to Divisional officers written orders authorizing the advance</td>
		<td align="center">Reason for non - clearance of advance when outstanding more than three months</td>
	</tr>
<?php
$slno = 1; $total_ots_amt = 0;
$SecuredAdvQuery = "select a.*, b.*, c.per, c.decimal_placed from secured_advance a 
inner join secured_advance_dt b on (a.said = b.said) 
inner join schdule c on (b.subdivid = c.subdiv_id) 
where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.rbn = '$rbn' and b.rbn = '$rbn' and c.sheet_id = '$sheetid'";
$SecuredAdvSql = mysql_query($SecuredAdvQuery);
if($SecuredAdvSql == true){
	while($SList = mysql_fetch_object($SecuredAdvSql)){
	$desc 			= $SList->description;
	$snotes 		= $SList->shortnotes;
	$decimal 		= $SList->decimal_placed;
	$itemUnit 		= $SList->per;
	$total_ots_amt 	= $total_ots_amt + $SList->upto_dt_amt;
	if($snotes != ""){
		$description = $snotes;
	}else{
		$description = $desc;
	}
?>
	<tr>
		<td align="center" width="50px"><?php echo $slno; ?></td>
		<td align="center" width="50px"><?php echo $SList->itemno; ?></td>
		<td align="right" width="50px"><?php echo $SList->ots_qty_prev_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->utz_qty_this_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->add_qty_this_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->ots_qty_since_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->full_asses_rate; ?></td>
		<td align="left"><?php echo $description; ?></td>
		<td align="center"><?php echo $itemUnit; ?></td>
		<td align="right" width="50px"><?php echo $SList->red_rate; ?></td>
		<td align="right" width="50px"><?php echo number_format($SList->upto_dt_amt,2,".",","); ?></td>
		<td align="center" width="50px"><?php echo $SList->div_off_ref; ?></td>
		<td align="center" width="50px"><?php echo $SList->reason_non_clear; ?></td>
	</tr>
<?php
		$slno++;		
	}
	$total_ots_amt = round($total_ots_amt,2);
?>
	<tr class="labelbold">
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center" colspan="3">Total Amount</td>
		<td align="right"><?php echo number_format($total_ots_amt,2,".",","); ?></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
<?php
$net_amount = round(($total_ots_amt - $PrevSaAmount),2);
$split_amt = explode(".",$net_amount);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];

$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
//echo $amount_in_words;
}
$select_mb_page_query = "select mbookno, mbookpage from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
$select_mb_page_sql = mysql_query($select_mb_page_query);
if($select_mb_page_sql == true){
	if(mysql_num_rows($select_mb_page_sql)>0){
		$MBPgList = mysql_fetch_object($select_mb_page_sql);
		$co_mbook = $MBPgList->mbookno;
		$co_mbpage = $MBPgList->mbookpage;
	}
}
if(($co_mbook != "") && ($co_mbpage != "")){
	$carry_over_str = "C/O MB-".$co_mbook."/ Pg-".($co_mbpage+1);
}else{
	$carry_over_str = "";
}
?>
</table>
<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >
	<tr>
		<td>Total amount outstanding as per this account  : </td>
		<td align="right"><b><?php echo number_format($total_ots_amt,2,".",","); ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Deduct-Amount outstanding as per entry (C) of Annexure to the previous bill  : <?php if(($PrevSaMBno != 0)&&($PrevSaPage != 0)){ echo "MB-".$PrevSaMBno."/P-".$PrevSaPage; }  ?></td>
		<td align="right"><b><?php echo number_format($PrevSaAmount,2,".",","); ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="labelbold" style="font-size:12px">Net Amount since previous bill (in words : Rupees <?php echo $amount_in_words; ?>) </td>
		<td align="right"><b><?php echo number_format($net_amount,2,".",","); ?></b></td>
		<td width="15%" align="right"><?php echo $carry_over_str; ?></td>
	</tr>
	<tr>
		<td colspan="3">
			Certified (1) that the plus quantities of materials shown in column 3 of the Account above have actually been brought by the Contractor to the site 
		of the work and the contractor had not previously received any advance on their security (2) that these materials are of an imperishable nature and 
		all are required by the Contractor for use on the work in connection with the items for which rates for finished work have been agreed upon and (3) 
		that a format agreement in Form 31 signed and executed by the Contractor in accordance with Paragraphs 10.2.24 (a) of the Central Public Works 
		Account Code in the Divisional Office.
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><br/><br/><br/><span style="float:left">Dated sign of Contractor</span><span>Dated sign of Officer preparing the bill</span><span style="float:right">Dated sign of Officer authorizing payment</span></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><span class='badge'>Page <?php echo $page; $page++; ?></span></td>
	</tr>
</table>
<!--<div style="width:100%;" align="center">
	<div class="col-md" align="center"> 
		&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Certified (1) that the plus quantities of materials shown in column 3 of the Account above have actually been brought by the Contractor to the site 
		of the work and the contractor had not previously received any advance on their security (2) that these materials are of an imperishable nature and 
		all are required by the Contractor for use on the work in connection with the items for which rates for finished work have been agreed upon and (3) 
		that a format agreement in Form 31 signed and executed by the Contractor in accordance with Paragraphs 10.2.24 (a) of the Central Public Works 
		Account Code in the Divisional Office.
	</div>
</div>-->

