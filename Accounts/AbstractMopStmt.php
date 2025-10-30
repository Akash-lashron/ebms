<?php

////////////////////// MEMO OF PAYMENTS STARTS HERE /////////////////////
$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();

$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysqli_query($dbConn,$select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysqli_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($EscList = mysqli_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt = round(($esc_tcc_amount+$esc_tca_amount),2);//$EscList->esc_total_amt;
			$Esc_Total_Amt = $Esc_Total_Amt+$esc_qtr_amt;//+$esc_tca_amount;
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_qtr_amt);
		}
	}
}
$Esc_Total_Amt = round($Esc_Total_Amt);
$SlmNetAmount = round(($SlmNetAmount+$Esc_Total_Amt),2);

$RevEscQtrArray = array();
$RevEscTccAmtArray = array();
$RevEscTcaAmtArray = array();
$rev_esc_cnt = 0;
$RevEsc_Total_Amt = 0;
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rev_esc_total_amt != 0 ORDER BY quarter ASC";
$select_rev_esc_rbn_sql = mysqli_query($dbConn,$select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysqli_num_rows($select_rev_esc_rbn_sql)>0)
	{
		$rev_esc_cnt = 1;
		while($RevEscList = mysqli_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			$total_rev_esc_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);
			$paid_esc_tcc_amount = $RevEscList->tcc_amt;
			$paid_esc_tca_amount = $RevEscList->tca_amt;
			$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
			$select_esc_paid_query = "select rev_tcc_mbook, rev_tcc_mbpage, rev_esc_total_amt from escalation_revised where sheetid = '$abstsheetid' and quarter = '$rev_quarter' ORDER BY rev_esc_id  DESC";
			$select_esc_paid_sql = mysqli_query($dbConn,$select_esc_paid_query);
			if($select_esc_paid_sql == true)
			{
				$PaidEAbaMB = mysqli_fetch_object($select_esc_paid_sql);
				$PaidEsc_Abs_MBook = $PaidEAbaMB->rev_tcc_mbook;
				$PaidEsc_Abs_MBPage = $PaidEAbaMB->rev_tcc_mbpage;
				$PaidEsc_Abs_tot_amt = $PaidEAbaMB->rev_esc_total_amt;
			}
			if($PaidEsc_Abs_tot_amt>0)
			{
				$paid_esc_tcc_amount = $PaidEsc_Abs_MBook;
				$paid_esc_tca_amount = $PaidEsc_Abs_MBPage;
				$total_paid_esc_amt = $PaidEsc_Abs_tot_amt;
			}
			$rev_esc_qtr_amt = round(($total_rev_esc_amt-$total_paid_esc_amt),2);
			$RevEsc_Total_Amt = $RevEsc_Total_Amt+$rev_esc_qtr_amt;//+$esc_tca_amount;
			array_push($RevEscQtrArray,$rev_quarter);
			array_push($RevEscTccAmtArray,$rev_esc_qtr_amt);
		}
	}
}
$RevEsc_Total_Amt = round($RevEsc_Total_Amt);
$SlmNetAmount = round(($SlmNetAmount+$RevEsc_Total_Amt),2);


$total_recovery_civil = 0; $total_recovery = 0; $edit_count = 0;
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysqli_query($dbConn,$secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysqli_fetch_object($secured_advance_sql);
	$sec_adv_amount_civil	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount_civil = 0;
}

$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysqli_query($dbConn,$water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysqli_fetch_object($water_recovery_sql))
	{
		$water_charge_civil 	= 	$water_charge_civil+$WRList->water_cost;
	}
}
else
{
	$water_charge_civil = 0;
}
$total_recovery_civil = $total_recovery_civil + $water_charge_civil;
$electricity_recovery_query = "select electricity_cost from generate_electricitybill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$electricity_recovery_sql = mysqli_query($dbConn,$electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysqli_fetch_object($electricity_recovery_sql))
	{
		$electricity_charge_civil 	= 	$electricity_charge_civil+$ERList->electricity_cost;
	}
}
else
{
	$electricity_charge_civil = 0;
}
$total_recovery_civil = $total_recovery_civil + $electricity_charge_civil;


$rrcount = 0;  $total_rec_rel_amt_civil = 0;  $total_rec_rel_amt_acc = 0;
$RRDescCivArr = array(); $RRAmtCivArr = array(); $RRDescAccArr = array(); 
$RRAmtAccArr = array(); $RRDescArr = array(); $RRDescAmt = array();  $RRIdArr = array();

$recov_release_query = "select * from recovery_release where sheetid = '$abstsheetid' and rbn = '$rbn'";
$recov_release_sql = mysqli_query($dbConn,$recov_release_query);
//echo $recov_release_query;
if($recov_release_sql == true)
{
	if(mysqli_num_rows($recov_release_sql)>0)
	{
		while($RecRelList = mysqli_fetch_object($recov_release_sql))
		{
			$rec_rel_desc_civil = $RecRelList->description_civil;
			$rec_rel_amt_civil 	= $RecRelList->amount_civil;
			$rec_rel_desc_acc 	= $RecRelList->description_acc;
			$rec_rel_amt_acc 	= $RecRelList->amount_acc;
			$reid = $RecRelList->reid;
			if($rec_rel_desc_acc != "")
			{
				$rec_rel_desc = $rec_rel_desc_acc;
			}
			else
			{
				$rec_rel_desc = $rec_rel_desc_civil;
			}
			
			if($rec_rel_amt_acc != 0)
			{
				$rec_rel_amt = $rec_rel_amt_acc;
			}
			else
			{
				$rec_rel_amt = $rec_rel_amt_civil;
			}
			array_push($RRDescCivArr,$rec_rel_desc_civil);
			array_push($RRAmtCivArr,$rec_rel_amt_civil);
			array_push($RRDescAccArr,$rec_rel_desc_acc);
			array_push($RRAmtAccArr,$rec_rel_amt_acc);
			array_push($RRIdArr,$reid);
			
			array_push($RRDescArr,$rec_rel_desc);
			array_push($RRDescAmt,$rec_rel_amt);
			$total_rec_rel_amt_civil  	= $total_rec_rel_amt_civil + $rec_rel_amt_civil;
			$total_rec_rel_amt_acc  	= $total_rec_rel_amt_acc + $rec_rel_amt_acc;
			$rrcount++;
		}
	}
}
if($total_rec_rel_amt_acc != 0)
{
	$total_rec_rel_amt = $total_rec_rel_amt_acc;
}
else
{
	$total_rec_rel_amt = $total_rec_rel_amt_civil;
}


$general_recovery_query = "select * from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
$general_recovery_sql = mysqli_query($dbConn,$general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 					= 	mysqli_fetch_object($general_recovery_sql);
	$sd_amt_civil 				= 	round($GRList->sd_amt);
	$sd_percent_civil 			= 	$GRList->sd_percent;
	$sgst_amt_civil 			= 	round($GRList->sgst_tds_amt);
	$sgst_percent_civil 		= 	$GRList->sgst_tds_perc;
	$cgst_amt_civil 			= 	round($GRList->cgst_tds_amt);
	$cgst_percent_civil 		= 	$GRList->cgst_tds_perc;
	$igst_amt_civil 			= 	round($GRList->igst_tds_amt);
	$igst_percent_civil 		= 	$GRList->igst_tds_perc;
	$wct_amt_civil 				= 	round($GRList->wct_amt);
	$wct_percent_civil 			= 	$GRList->wct_percent;
	$vat_amt_civil 				= 	round($GRList->vat_amt);
	$vat_percent_civil 			= 	$GRList->vat_percent;
	$mob_adv_amt_civil 			= 	round($GRList->mob_adv_amt);
	$mob_adv_percent_civil 		= 	$GRList->mob_adv_percent;
	$lw_cess_amt_civil 			= 	round($GRList->lw_cess_amt);
	$lw_cess_percent_civil 		= 	$GRList->lw_cess_percent;
	$incometax_amt_civil 		= 	round($GRList->incometax_amt);
	$incometax_percent_civil 	= 	$GRList->incometax_percent;
	$it_cess_amt_civil 			= 	round($GRList->it_cess_amt);
	$it_cess_percent_civil 		= 	$GRList->it_cess_percent;
	$it_edu_amt_civil 			= 	round($GRList->it_edu_amt);
	$it_edu_percent_civil 		= 	$GRList->it_edu_percent;
	$land_rent_civil 			= 	round($GRList->land_rent);
	$liquid_damage_civil 		= 	round($GRList->liquid_damage);
	
	$other_recovery_1_civil 	= 	round($GRList->other_recovery_1);
	$other_recovery_2_civil		= 	round($GRList->other_recovery_2);
	$other_recovery_3_civil		= 	round($GRList->other_recovery_3);
	
	$other_recovery_1_desc_civil= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc_civil= 	$GRList->other_recovery_2_desc;
	$other_recovery_3_desc_civil= 	$GRList->other_recovery_3_desc;
	
	$non_dep_machine_equip_civil= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power_civil 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa_civil 	= 	round($GRList->nonsubmission_qa);
}
$total_recovery_civil = $total_recovery_civil + $sd_amt_civil + $sgst_amt_civil + $cgst_amt_civil + $igst_amt_civil + $wct_amt_civil + $vat_amt_civil+$mob_adv_amt_civil + $lw_cess_amt_civil+$incometax_amt_civil + $it_cess_amt_civil+$it_edu_amt_civil + $land_rent_civil+$liquid_damage_civil + $other_recovery_1_civil + $other_recovery_2_civil + $other_recovery_3_civil + $non_dep_machine_equip_civil + $non_dep_man_power_civil + $nonsubmission_qa_civil;
$OverAllSlmAmount_civil = $OverAllSlmAmount + $sec_adv_amount_civil;
$Overall_net_amt_final_civil = round(($OverAllSlmAmount_civil - $total_recovery_civil),2);
$Overall_net_amt_final_civil = round($Overall_net_amt_final_civil);

$accounts_edit_query = "select * from memo_payment_accounts_edit where sheetid = '$abstsheetid' and rbn = '$rbn' and edit_flag = 'EDITED'";
$accounts_edit_sql = mysqli_query($dbConn,$accounts_edit_query);
if($accounts_edit_sql == true)
{
	if(mysqli_num_rows($accounts_edit_sql)>0)
	{
		$edit_count = 1;
	}
	else
	{
		$edit_count = 0;
	}
}

if($edit_count == 1)
{
	$MEMOList 				= 	mysqli_fetch_object($accounts_edit_sql);
	$sd_amt 				= 	round($MEMOList->sd_amt);
	$sd_percent 			= 	$MEMOList->sd_percent;
	$sgst_amt 				= 	round($MEMOList->sgst_tds_amt);
	$sgst_percent 			= 	$MEMOList->sgst_tds_perc;
	$cgst_amt 				= 	round($MEMOList->cgst_tds_amt);
	$cgst_percent 			= 	$MEMOList->cgst_tds_perc;
	$igst_amt 				= 	round($MEMOList->igst_tds_amt);
	$igst_percent 			= 	$MEMOList->igst_tds_perc;
	$wct_amt				= 	round($MEMOList->wct_amt);
	$wct_percent 			= 	$MEMOList->wct_percent;
	$vat_amt 				= 	round($MEMOList->vat_amt);
	$vat_percent 			= 	$MEMOList->vat_percent;
	$mob_adv_amt 			= 	round($MEMOList->mob_adv_amt);
	$mob_adv_percent 		= 	$MEMOList->mob_adv_percent;
	$lw_cess_amt 			= 	round($MEMOList->lw_cess_amt);
	$lw_cess_percent 		= 	$MEMOList->lw_cess_percent;
	$incometax_amt 			= 	round($MEMOList->incometax_amt);
	$incometax_percent 		= 	$MEMOList->incometax_percent;
	$it_cess_amt 			= 	round($MEMOList->it_cess_amt);
	$it_cess_percent 		= 	$MEMOList->it_cess_percent;
	$it_edu_amt 			= 	round($MEMOList->it_edu_amt);
	$it_edu_percent 		= 	$MEMOList->it_edu_percent;
	$land_rent 				= 	round($MEMOList->land_rent);
	$liquid_damage 			= 	round($MEMOList->liquid_damage);
	$other_recovery_1 		= 	round($MEMOList->other_recovery_1_amt);
	$other_recovery_2		= 	round($MEMOList->other_recovery_2_amt);
	$other_recovery_1_desc	= 	$MEMOList->other_recovery_1_desc;
	$other_recovery_2_desc	= 	$MEMOList->other_recovery_2_desc;
	$non_dep_machine_equip 	= 	round($MEMOList->non_dep_machine_equip);
	$non_dep_man_power 		= 	round($MEMOList->non_dep_man_power);
	$sec_adv_amount 		= 	$MEMOList->sec_adv_amount;
	$water_charge 			= 	$MEMOList->water_cost;
	$electricity_charge		= 	$MEMOList->electricity_cost;
	$nonsubmission_qa		= 	$MEMOList->nonsubmission_qa;
}
else
{
	$sd_amt 				= 	$sd_amt_civil;
	$sd_percent 			= 	$sd_percent_civil;
	$sgst_amt 				= 	$sgst_amt_civil;
	$sgst_percent 			= 	$sgst_percent_civil;
	$cgst_amt 				= 	$cgst_amt_civil;
	$cgst_percent 			= 	$cgst_percent_civil;
	$igst_amt 				= 	$igst_amt_civil;
	$igst_percent 			= 	$igst_percent_civil;
	$wct_amt				= 	$wct_amt_civil;
	$wct_percent 			= 	$wct_percent_civil;
	$vat_amt				= 	$vat_amt_civil;
	$vat_percent 			= 	$vat_percent_civil;
	$mob_adv_amt			= 	$mob_adv_amt_civil;
	$mob_adv_percent 		= 	$mob_adv_percent_civil;
	$lw_cess_amt 			= 	$lw_cess_amt_civil;
	$lw_cess_percent 		= 	$lw_cess_percent_civil;
	$incometax_amt 			= 	$incometax_amt_civil;
	$incometax_percent 		= 	$incometax_percent_civil;
	$it_cess_amt 			= 	$it_cess_amt_civil;
	$it_cess_percent 		= 	$it_cess_percent_civil;
	$it_edu_amt 			= 	$it_edu_amt_civil;
	$it_edu_percent 		= 	$it_edu_percent_civil;
	$land_rent 				= 	$land_rent_civil;
	$liquid_damage 			= 	$liquid_damage_civil;
	$other_recovery_1 		= 	$other_recovery_1_civil;
	$other_recovery_2		= 	$other_recovery_2_civil;
	$other_recovery_3		= 	$other_recovery_3_civil;
	$other_recovery_1_desc	= 	$other_recovery_1_desc_civil;
	$other_recovery_2_desc	= 	$other_recovery_2_desc_civil;
	$other_recovery_3_desc	= 	$other_recovery_3_desc_civil;
	$non_dep_machine_equip 	= 	$non_dep_machine_equip_civil;
	$non_dep_man_power 		= 	$non_dep_man_power_civil;
	$sec_adv_amount 		= 	$sec_adv_amount_civil;
	$water_charge 			= 	$water_charge_civil;
	$electricity_charge		= 	$electricity_charge_civil;
	$nonsubmission_qa		= 	$nonsubmission_qa_civil;
}
if($sd_amt != $sd_amt_civil)							  { $fclass1  = "labelprinterror"; } else { $fclass1  = "labelprint"; }
if($sd_percent != $sd_percent_civil)					  { $fclass2  = "labelprinterror"; } else { $fclass2  = "labelprint"; }
if($wct_amt != $wct_amt_civil)							  { $fclass3  = "labelprinterror"; } else { $fclass3  = "labelprint"; } //echo "sf".$fclass3."<br/>";
if($wct_percent != $wct_percent_civil)					  { $fclass4  = "labelprinterror"; } else { $fclass4  = "labelprint"; } //echo "hg".$fclass4."<br/>";
if($vat_percent != $vat_percent_civil)					  { $fclass5  = "labelprinterror"; } else { $fclass5  = "labelprint"; }
if($mob_adv_amt != $mob_adv_amt_civil)					  { $fclass6  = "labelprinterror"; } else { $fclass6  = "labelprint"; }
if($mob_adv_percent != $mob_adv_percent_civil)			  { $fclass7  = "labelprinterror"; } else { $fclass7  = "labelprint"; }
if($lw_cess_amt != $lw_cess_amt_civil)					  { $fclass8  = "labelprinterror"; } else { $fclass8  = "labelprint"; }
if($lw_cess_percent != $lw_cess_percent_civil)			  { $fclass9  = "labelprinterror"; } else { $fclass9  = "labelprint"; }
if($incometax_amt != $incometax_amt_civil)				  { $fclass10 = "labelprinterror"; } else { $fclass10 = "labelprint"; }
if($incometax_percent != $incometax_percent_civil)		  { $fclass11 = "labelprinterror"; } else { $fclass11 = "labelprint"; }
if($it_cess_amt != $it_cess_amt_civil)					  { $fclass12 = "labelprinterror"; } else { $fclass12 = "labelprint"; }
if($it_cess_percent != $it_cess_percent_civil)			  { $fclass13 = "labelprinterror"; } else { $fclass13 = "labelprint"; }
if($it_edu_amt != $it_edu_amt_civil)					  { $fclass14 = "labelprinterror"; } else { $fclass14 = "labelprint"; }
if($it_edu_percent != $it_edu_percent_civil)			  { $fclass15 = "labelprinterror"; } else { $fclass15 = "labelprint"; }
if($land_rent != $land_rent_civil)						  { $fclass16 = "labelprinterror"; } else { $fclass16 = "labelprint"; }
if($liquid_damage != $liquid_damage_civil)				  { $fclass17 = "labelprinterror"; } else { $fclass17 = "labelprint"; }
if($other_recovery_1 != $other_recovery_1_civil)		  { $fclass18 = "labelprinterror"; } else { $fclass18 = "labelprint"; }
if($other_recovery_2 != $other_recovery_2_civil)		  { $fclass19 = "labelprinterror"; } else { $fclass19 = "labelprint"; }
if($other_recovery_1_desc != $other_recovery_1_desc_civil){ $fclass20 = "labelprinterror"; } else { $fclass20 = "labelprint"; }
if($other_recovery_2_desc != $other_recovery_2_desc_civil){ $fclass21 = "labelprinterror"; } else { $fclass21 = "labelprint"; }
if($non_dep_machine_equip != $non_dep_machine_equip_civil){ $fclass22 = "labelprinterror"; } else { $fclass22 = "labelprint"; }
if($non_dep_man_power != $non_dep_man_power_civil)		  { $fclass23 = "labelprinterror"; } else { $fclass23 = "labelprint"; }
if($sec_adv_amount != $sec_adv_amount_civil)			  { $fclass24 = "labelprinterror"; } else { $fclass24 = "labelprint"; }
if($water_charge != $water_charge_civil)				  { $fclass25 = "labelprinterror"; } else { $fclass25 = "labelprint"; }
if($electricity_charge != $electricity_charge_civil)	  { $fclass26 = "labelprinterror"; } else { $fclass26 = "labelprint"; }
if($nonsubmission_qa != $nonsubmission_qa_civil)		  { $fclass27 = "labelprinterror"; } else { $fclass27 = "labelprint"; }
if($sgst_amt != $sgst_amt_civil)					  	  { $fclass28 = "labelprinterror"; } else { $fclass28 = "labelprint"; }
if($sgst_percent != $sgst_percent_civil)				  { $fclass29 = "labelprinterror"; } else { $fclass29 = "labelprint"; }
if($cgst_amt != $cgst_amt_civil)					  	  { $fclass30 = "labelprinterror"; } else { $fclass30 = "labelprint"; }
if($cgst_percent != $cgst_percent_civil)				  { $fclass31 = "labelprinterror"; } else { $fclass31 = "labelprint"; }
if($igst_amt != $igst_amt_civil)					  	  { $fclass32 = "labelprinterror"; } else { $fclass32 = "labelprint"; }
if($igst_percent != $igst_percent_civil)				  { $fclass33 = "labelprinterror"; } else { $fclass33 = "labelprint"; }


$total_recovery = $total_recovery + $water_charge;
$total_recovery = $total_recovery + $electricity_charge;
$total_recovery = $total_recovery + $sd_amt + $wct_amt + $vat_amt + $mob_adv_amt + $lw_cess_amt + $incometax_amt + $it_cess_amt + $it_edu_amt + $land_rent + $liquid_damage + $other_recovery_1 + $other_recovery_2 + $other_recovery_3 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;


if($non_dep_machine_equip != 0)
{
	$non_dep_machine_equip_print = number_format($non_dep_machine_equip, 2, '.', '');
}
else
{
	$non_dep_machine_equip_print = "NIL";
}

if($non_dep_man_power != 0)
{
	$non_dep_man_power_print = number_format($non_dep_man_power, 2, '.', '');
}
else
{
	$non_dep_man_power_print = "NIL";
}

if($electricity_charge != 0)
{
	$electricity_charge_print = number_format($electricity_charge, 2, '.', '');
}
else
{
	$electricity_charge_print = "NIL";
}

if($water_charge != 0)
{
	$water_charge_print = number_format($water_charge, 2, '.', '');
}
else
{
	$water_charge_print = "NIL";
}

$OverAllSlmDpmAmount = $SlmDpmNetAmount;
$OverAllSlmAmount = $SlmNetAmount;
$OverAllDpmAmount = $DpmNetAmount;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
?>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
echo "<tr style='border:none'>
<td style='border:none' class='labelbold' align='left' colspan='5'><span class='spanbtn' name='check_memo_payment' id='check_memo_payment'>Click here to edit MOP</span></td>
<td style='border:none' class='labelbold' align='left' colspan='8'><u>Memo of Payment</u></td>
</tr>";

$UpoDtSecAdvAmtMop = 0; $DeductPrevBillSecAdvAmtMop = 0; $ThisBillSecAdvAmtMop = 0; 
$SelectSecAdvMopQuery = "SELECT rbn, sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn <= '$rbn'";
$SelectSecAdvMopSql   = mysqli_query($dbConn,$SelectSecAdvMopQuery);
if($SelectSecAdvMopSql == true){
	if(mysqli_num_rows($SelectSecAdvMopSql)>0){
		while($SecAdvMobList = mysqli_fetch_object($SelectSecAdvMopSql)){
			$UpoDtSecAdvAmtMop = $UpoDtSecAdvAmtMop + $SecAdvMobList->sec_adv_amount;
			if($rbn == $SecAdvMobList->rbn){
				$ThisBillSecAdvAmtMop = $SecAdvMobList->sec_adv_amount;
			}else{
				$DeductPrevBillSecAdvAmtMop = $DeductPrevBillSecAdvAmtMop + $SecAdvMobList->sec_adv_amount;
			}
		}
	}
}
//echo $SelectSecAdvMopQuery;exit;
$ThisBillMobAdvAmtMop = 0; 
$SelectMobAdvMopQuery = "SELECT rbn, mob_adv_amount from mobilization_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectMobAdvMopSql   = mysqli_query($dbConn,$SelectMobAdvMopQuery);
if($SelectMobAdvMopSql == true){
	if(mysqli_num_rows($SelectMobAdvMopSql)>0){
		$MobAdvMobList = mysqli_fetch_object($SelectMobAdvMopSql);
		$ThisBillMobAdvAmtMop = $MobAdvMobList->mob_adv_amount;
	}
}

$SelectUptoDtQuery = "SELECT * from abstractbook where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectUptoDtSql   = mysqli_query($dbConn,$SelectUptoDtQuery);
if($SelectUptoDtSql == true){
	if(mysqli_num_rows($SelectUptoDtSql)>0){
		$UptoDtList = mysqli_fetch_object($SelectUptoDtSql);
		$UptoDtBillValue 		= $UptoDtList->upto_date_total_amount;
		$DeductPrevBillValue 	= $UptoDtList->dpm_total_amount;
		$SinceLastBillValue 	= $UptoDtList->slm_total_amount;
	}
}
$GrandTotal = $OverAllSlmDpmAmount + $ThisBillMobAdvAmtMop + $UpoDtSecAdvAmtMop;
$NetAmount = $OverAllSlmAmount + $sec_adv_amount_civil + $ThisBillMobAdvAmtMop;


echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Mobilization Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($ThisBillMobAdvAmtMop, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto Date Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($UpoDtSecAdvAmtMop, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>GRAND TOTAL : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelbold' align='right' colspan='5'>".number_format($GrandTotal, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Deduct Previous Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($DeductPrevBillSecAdvAmtMop, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Since Last Bill Value : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Since Last Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($sec_adv_amount_civil, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
//// This is for Printing Escalation
$OverAllSlmAmount = $NetAmount;
if(count($EscQtrArray)>0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round(($OverAllSlmAmount+$Esc_Total_Amt),2);

//// This is for Printing Revised Escalation
if(count($RevEscQtrArray)>0)
{
	for($q2=0; $q2<count($RevEscQtrArray); $q2++)
	{
		$RevEQtr = $RevEscQtrArray[$q2];
		$RevETccAmt = $RevEscTccAmtArray[$q2];
		echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Revised Escalation for Quarter - ".$RevEQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($RevETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round(($OverAllSlmAmount+$RevEsc_Total_Amt),2);



$Overall_net_amt_final = round(($OverAllSlmAmount + $sec_adv_amount +$total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = $Overall_net_amt_final;

echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none; border-top:1px dashed #000000' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none; border-top:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='3' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";



$ea = 1; $eb = 1; $ed = 1; 
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";  $ed_text = "<b><u>With hold Amount</u></b>";
if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none;' class='".$fclass3."' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none;' colspan='5' align='right' class='".$fclass3."'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass5."' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass5."'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";


}
if($lw_cess_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass8."' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass8."'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";




}
if($mob_adv_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($sgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass28."' align='right' colspan='4'>SGST @ ".number_format($sgst_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass28."'>&nbsp;&nbsp;".number_format($sgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($cgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass30."' align='right' colspan='4'>CGST @ ".number_format($cgst_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass30."'>&nbsp;&nbsp;".number_format($cgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($igst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass32."' align='right' colspan='4'>IGST @ ".number_format($igst_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass32."'>&nbsp;&nbsp;".number_format($igst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass10."' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass10."'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass12."' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass12."'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass14."' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass14."'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($water_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass25."' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass25."'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($electricity_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass26."' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass26."'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass16."' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass16."'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass17."' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass17."'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass18."' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass18."'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass19."' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass19."'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_3 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass19."' align='right' colspan='4'>".$other_recovery_3_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass19."'>&nbsp;&nbsp;".number_format($other_recovery_3, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_machine_equip != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass22."' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass22."'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_man_power != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass23."' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass23."'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($nonsubmission_qa != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass27."' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass27."'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($sd_amt != 0)
{
$eb = 1;
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ec_text." (".$eb.")</td><td style='border:none' class='".$fclass1."' align='right' colspan='4'>Security Deposit @ ".$sd_percent."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass1."'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($rrcount>0)
{
	for($rrc=0; $rrc<$rrcount; $rrc++)
	{
	echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ed_text." (".$ed.")</td><td style='border:none' class='".$fclass1."' align='right' colspan='4'>".$RRDescArr[$rrc]." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass1."'>&nbsp;&nbsp;".number_format($RRDescAmt[$rrc], 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
	$ed++; $ed_text = "";
	}
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
}

$split_amt = explode(".",$Overall_net_amt_final);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];
$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
echo "<tr style='border:none'><td style='border:none'>&nbsp;</td><td style='border:none'>&nbsp;</td><td style='border:none' class='labelprint' align='left' colspan='12'>Amount: (Rupees ".$amount_in_words.")</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>page ".$page."</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";

//////////////////// MEMO OF PAYMENT ENDS HERE ////////////////////
?>