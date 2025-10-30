<?php
require_once 'library/config.php';
$Rec_desc		=	array("Secured Advance","WCT Percent","WCT Amount","VAT Percent","VAT Amount","LW Cess Percent","LW Cess Amount","Mob Adv Percent",
					"Mob Adv Amount","Income Tax Percent","Income Tax Amount","IT Cess Percent","IT Cess Amount","IT ECess Percent","IT ECess Amount",
					"Electricity Charge","Water Charge","Non Dep Machine","Non Dep Manpower","Land Rent","Liquid Damage","Other Recovery 1","Other Recovery 2",
					"SD Percent","SD Amount");
$sheetid 		= $_POST['sheetid'];
$rbn 			= $_POST['rbnno'];
$dataStr_Acco 	= $_POST['dataStr_Acco'];
$dataStr_Civil 	= $_POST['dataStr_Civil'];
$dataStr_RecRel = $_POST['recRelData'];

$edit_count 	= 0;
$ExpdataStr_Acco 	= explode("*",$dataStr_Acco);
$ExpdataStr_Civil 	= explode("*",$dataStr_Civil);
$ExpdataStr_RecRel 	= explode("@#*#@",$dataStr_RecRel);
if($dataStr_RecRel != "")
{
	for($i=0; $i<count($ExpdataStr_RecRel); $i++)
	{
		if($ExpdataStr_RecRel[$i] != "")
		{
			$RecRelStr = $ExpdataStr_RecRel[$i];
			$expRecRelStr = explode("@*@",$RecRelStr);
			$rec_rel_amt_civil 		= $expRecRelStr[0];
			$rec_rel_amt 			= $expRecRelStr[1];
			$rec_rel_desc 			= $expRecRelStr[2];
			$reid 					= $expRecRelStr[3];
			if($rec_rel_amt_civil != $rec_rel_amt)
			{
				$edit_count++;
			}
			if(($rec_rel_amt == 0) || ($rec_rel_amt == ""))
			{
				$rec_rel_amt == $rec_rel_amt_civil;
			}
			$update_rec_rel_query = "update recovery_release set description_acc = '$rec_rel_desc', amount_acc = '$rec_rel_amt' where reid = '$reid' and sheetid = '$sheetid ' and rbn = '$rbn'";
			$update_rec_rel_sql = mysqli_query($dbConn,$update_rec_rel_query);
			//$dd .= $update_rec_rel_query."<br/>";
		}
	}
}


$sa_amount_acco 		= $ExpdataStr_Acco[0];
$wct_perc_acco 			= $ExpdataStr_Acco[1];
$wct_acco 				= $ExpdataStr_Acco[2];
$vat_perc_acco 			= $ExpdataStr_Acco[3];
$vat_acco 				= $ExpdataStr_Acco[4];
$lw_cess_perc_acco 		= $ExpdataStr_Acco[5];
$lw_cess_acco 			= $ExpdataStr_Acco[6];
$mob_adv_perc_acco 		= $ExpdataStr_Acco[7];
$mob_adv_acco 			= $ExpdataStr_Acco[8];
$incometax_perc_acco 	= $ExpdataStr_Acco[9];
$incometax_acco 		= $ExpdataStr_Acco[10];
$ITcess_perc_acco 		= $ExpdataStr_Acco[11];
$ITcess_acco 			= $ExpdataStr_Acco[12];
$ITEcess_perc_acco 		= $ExpdataStr_Acco[13];
$ITEcess_acco 			= $ExpdataStr_Acco[14];
$elect_charge_acco 		= $ExpdataStr_Acco[15];
$water_charge_acco 		= $ExpdataStr_Acco[16];
$non_dep_me_acco 		= $ExpdataStr_Acco[17];
$non_dep_tm_acco 		= $ExpdataStr_Acco[18];
$rent_land_acco 		= $ExpdataStr_Acco[19];
$liquid_damage_acco 	= $ExpdataStr_Acco[20];
$other_recovery_1_acco 	= $ExpdataStr_Acco[21];
$other_recovery_2_acco 	= $ExpdataStr_Acco[22];
$sd_perc_acco 			= $ExpdataStr_Acco[23];
$sd_acco 				= $ExpdataStr_Acco[24];
$slm_net_amt_acco		= $ExpdataStr_Acco[25];
$net_payable_amt_acco	= $ExpdataStr_Acco[26];
$other_recovery_1_desc	= $ExpdataStr_Acco[27];
$other_recovery_2_desc	= $ExpdataStr_Acco[28];
$nonsubmission_qa_acco	= $ExpdataStr_Acco[29];
$sgst_perc_acco			= $ExpdataStr_Acco[30];
$sgst_acco				= $ExpdataStr_Acco[31];
$cgst_perc_acco			= $ExpdataStr_Acco[32];
$cgst_acco				= $ExpdataStr_Acco[33];
$igst_perc_acco			= $ExpdataStr_Acco[34];
$igst_acco				= $ExpdataStr_Acco[35];
$gst_rate_acco			= $ExpdataStr_Acco[36];
$gst_amt_acco			= $ExpdataStr_Acco[37];
$pan_type_acco			= $ExpdataStr_Acco[38];
$is_ldc_acco			= $ExpdataStr_Acco[39];

$sa_amount_civil 		= $ExpdataStr_Civil[0];
$wct_perc_civil 		= $ExpdataStr_Civil[1];
$wct_civil 				= $ExpdataStr_Civil[2];
$vat_perc_civil 		= $ExpdataStr_Civil[3];
$vat_civil 				= $ExpdataStr_Civil[4];
$lw_cess_perc_civil 	= $ExpdataStr_Civil[5];
$lw_cess_civil 			= $ExpdataStr_Civil[6];
$mob_adv_perc_civil 	= $ExpdataStr_Civil[7];
$mob_adv_civil 			= $ExpdataStr_Civil[8];
$incometax_perc_civil 	= $ExpdataStr_Civil[9];
$incometax_civil 		= $ExpdataStr_Civil[10];
$ITcess_perc_civil 		= $ExpdataStr_Civil[11];
$ITcess_civil 			= $ExpdataStr_Civil[12];
$ITEcess_perc_civil 	= $ExpdataStr_Civil[13];
$ITEcess_civil 			= $ExpdataStr_Civil[14];
$elect_charge_civil 	= $ExpdataStr_Civil[15];
$water_charge_civil 	= $ExpdataStr_Civil[16];
$non_dep_me_civil 		= $ExpdataStr_Civil[17];
$non_dep_tm_civil 		= $ExpdataStr_Civil[18];
$rent_land_civil 		= $ExpdataStr_Civil[19];
$liquid_damage_civil 	= $ExpdataStr_Civil[20];
$other_recovery_1_civil = $ExpdataStr_Civil[21];
$other_recovery_2_civil = $ExpdataStr_Civil[22];
$sd_perc_civil 			= $ExpdataStr_Civil[23];
$sd_civil 				= $ExpdataStr_Civil[24];
$slm_net_amt_civil		= $ExpdataStr_Civil[25];
$net_payable_amt_civil	= $ExpdataStr_Civil[26];
$nonsubmission_qa_civil	= $ExpdataStr_Civil[27];
$sgst_perc_civil		= $ExpdataStr_Civil[28];
$sgst_civil				= $ExpdataStr_Civil[29];
$cgst_perc_civil		= $ExpdataStr_Civil[30];
$cgst_civil				= $ExpdataStr_Civil[31];
$igst_perc_civil		= $ExpdataStr_Civil[32];
$igst_civil				= $ExpdataStr_Civil[33];
$gst_rate_civil			= $ExpdataStr_Civil[34];
$gst_amt_civil			= $ExpdataStr_Civil[35];
$pan_type_civil			= $ExpdataStr_Civil[36];
$is_ldc_civil			= $ExpdataStr_Civil[37];

if($sa_amount_acco != $sa_amount_civil)			{ $edit_count++; }
if($wct_perc_acco != $wct_perc_civil)			{ $edit_count++; }
if($wct_acco != $wct_civil)						{ $edit_count++; }
if($vat_perc_acco != $vat_perc_civil)			{ $edit_count++; }
if($vat_acco != $vat_civil)						{ $edit_count++; }
if($lw_cess_perc_acco != $lw_cess_perc_civil)	{ $edit_count++; }
if($lw_cess_acco != $lw_cess_civil)				{ $edit_count++; }
if($mob_adv_perc_acco != $mob_adv_perc_civil)	{ $edit_count++; }
if($mob_adv_acco != $mob_adv_civil)				{ $edit_count++; }
if($incometax_perc_acco != $incometax_perc_civil){ $edit_count++; }
if($incometax_acco != $incometax_civil)			{ $edit_count++; }
if($ITcess_perc_acco != $ITcess_perc_civil)		{ $edit_count++; }
if($ITcess_acco != $ITcess_civil)				{ $edit_count++; }
if($ITEcess_perc_acco != $ITEcess_perc_civil)	{ $edit_count++; }
if($ITEcess_acco != $ITEcess_civil)				{ $edit_count++; }
if($elect_charge_acco != $elect_charge_civil)	{ $edit_count++; }
if($water_charge_acco != $water_charge_civil)	{ $edit_count++; }
if($non_dep_me_acco != $non_dep_me_civil)		{ $edit_count++; }
if($non_dep_tm_acco != $non_dep_tm_civil)		{ $edit_count++; }
if($rent_land_acco != $rent_land_civil)			{ $edit_count++; }
if($liquid_damage_acco != $liquid_damage_civil)	{ $edit_count++; }
if($other_recovery_1_acco != $other_recovery_1_civil){ $edit_count++; }
if($other_recovery_2_acco != $other_recovery_2_civil){ $edit_count++; }
if($sd_perc_acco != $sd_perc_civil)				{ $edit_count++; }
if($sd_acco != $sd_civil)						{ $edit_count++; }
if($slm_net_amt_acco != $slm_net_amt_civil)		{ $edit_count++; }
if($net_payable_amt_acco != $net_payable_amt_civil){ $edit_count++; }
if($nonsubmission_qa_acco != $nonsubmission_qa_civil){ $edit_count++; }

if($sgst_perc_acco != $sgst_perc_civil){ $edit_count++; }
if($sgst_acco != $sgst_civil){ $edit_count++; }
if($cgst_perc_acco != $cgst_perc_civil){ $edit_count++; }
if($cgst_acco != $cgst_civil){ $edit_count++; }
if($igst_perc_acco != $igst_perc_civil){ $edit_count++; }
if($igst_acco != $igst_civil){ $edit_count++; }
if($pan_type_acco != $pan_type_civil){ $edit_count++; }
if($is_ldc_acco != $is_ldc_civil){ $edit_count++; }

if($edit_count>0)
{
	$edit_flag = "EDITED";
}
else
{
	$edit_flag = "";
}

$delete_memo_payment_query = "delete from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
$delete_memo_payment_sql = mysqli_query($dbConn,$delete_memo_payment_query);


$recovery_query = "insert into memo_payment_accounts_edit set 
sheetid  = '$sheetid', rbn = '$rbn', 
abstract_net_amt = '$slm_net_amt_acco', 
sec_adv_amt = '$sa_amount_acco', 
esc_amt = '', 
pl_mac_adv_amt = '', 
mob_adv_percent = '$mob_adv_perc_acco', 
mob_adv_amt = '$mob_adv_acco', 
mob_adv_int_perc = '', 
mob_adv_int_amt = '', 
gst_rate = '$gst_rate_acco', 
gst_amount = '$gst_amt_acco', 
sgst_tds_perc = '$sgst_perc_acco', 
sgst_tds_amt = '$sgst_acco', 
cgst_tds_perc = '$cgst_perc_acco', 
cgst_tds_amt = '$cgst_acco', 
igst_tds_perc = '$igst_perc_acco', 
igst_tds_amt = '$igst_acco', 
wct_percent = '$wct_perc_acco', 
wct_amt = '$wct_acco', 
vat_percent = '$vat_perc_acco', 
vat_amt = '$vat_acco', 
lw_cess_percent = '$lw_cess_perc_acco', 
lw_cess_amt = '$lw_cess_acco', 
is_ldc_appl = '$is_ldc_acco', 
pan_type = '$pan_type_acco', 
incometax_percent = '$incometax_perc_acco', 
incometax_amt = '$incometax_acco', 
it_cess_percent = '$ITcess_perc_acco', 
it_cess_amt = '$ITcess_acco', 
it_edu_percent = '$ITEcess_perc_acco', 
it_edu_amt = '$ITEcess_acco',
non_dep_machine_equip = '$non_dep_me_acco', 
non_dep_man_power = '$non_dep_tm_acco', 
land_rent = '$rent_land_acco', 
liquid_damage = '$liquid_damage_acco', 
other_recovery_1_amt = '$other_recovery_1_acco', 
other_recovery_2_amt = '$other_recovery_2_acco', 
other_recovery_1_desc = '$other_recovery_1_desc', 
other_recovery_2_desc = '$other_recovery_2_desc',
sd_percent = '$sd_perc_acco', sd_amt = '$sd_acco',
sec_adv_amount = '$sa_amount_acco', 
electricity_cost  = '$elect_charge_acco', 
water_cost = '$water_charge_acco', 
net_payable_amt = '$net_payable_amt_acco', 
nonsubmission_qa = '$nonsubmission_qa_acco', 
edit_flag = '$edit_flag'";
$recovery_sql = mysqli_query($dbConn,$recovery_query);

//echo $recovery_query;exit;
if($recovery_sql == true)
{
	$data = 1;
}
else
{
	$data = 0;
}
echo $data;
//echo $recovery_query;
?>
