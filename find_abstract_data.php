<?php
require_once 'library/config.php';
require_once 'library/common.php';
$sheetid = $_GET['sheetid'];
$recoverydata = "";
$abstAmount = 0;
$Rbn = "";
$esc_total_amt = 0;
$rev_esc_total_amt = 0;
$select_rbn_query = "select distinct rbn from mbookgenerate where sheetid = '$sheetid'";
$select_rbn_sql = mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	if(mysql_num_rows($select_rbn_sql) == 1)
	{
		$RbnList 	= 	mysql_fetch_object($select_rbn_sql);
		$Rbn 		= 	$RbnList->rbn;
		if($Rbn != "")
		{
			$select_netamount_query 	= 	"SELECT abstract_net_amt from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$Rbn'";
			$select_netamount_sql = mysql_query($select_netamount_query);
			if($select_netamount_sql == true)
			{
				if(mysql_num_rows($select_netamount_sql)>0)
				{
					$AmtList 		= 	mysql_fetch_object($select_netamount_sql);
					$abstAmount 		= 	$AmtList->abstract_net_amt;
					//$recoverydata = $netAmount."***".$Rbn;
				}
			}
		}
	}
}
// For Calculate Escalation Amount
$select_esc_rbn_query = "select * from escalation where sheetid = '$sheetid' and flag = 0 and rbn = '$Rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($EscList = mysql_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt 	= $EscList->esc_total_amt;
			$esc_total_amt 	= $esc_total_amt + $esc_qtr_amt;//esc_tcc_amount + $esc_tca_amount;
		}
	}
}

// For Calculate Revised Escalation Amount
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$sheetid' and flag = 0 and rev_esc_total_amt != 0 ORDER BY quarter ASC";
$select_rev_esc_rbn_sql = mysql_query($select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_rev_esc_rbn_sql)>0)
	{
		$rev_esc_cnt = 1;
		while($RevEscList = mysql_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			
			$rev_esc_qtr_amt 	= $RevEscList->rev_esc_total_amt;
			$paid_esc_qtr_amt 	= $RevEscList->esc_total_amt;
			
			//// Second or more than two time revised
			$select_esc_paid_query = "select rev_tcc_mbook, rev_tcc_mbpage, rev_esc_total_amt from escalation_revised where sheetid = '$sheetid' and quarter = '$rev_quarter' ORDER BY rev_esc_id  DESC";
			$select_esc_paid_sql = mysql_query($select_esc_paid_query);
			if($select_esc_paid_sql == true)
			{
				$PaidEAbaMB = mysql_fetch_object($select_esc_paid_sql);
				//$PaidEsc_Abs_MBook = $PaidEAbaMB->rev_tcc_mbook;
				//$PaidEsc_Abs_MBPage = $PaidEAbaMB->rev_tcc_mbpage;
				$paid_esc_qtr_amt = $PaidEAbaMB->rev_esc_total_amt;
				//echo $PaidEsc_Abs_tot_amt;
			}
			
			$bal_amt_to_be_paid = round(($rev_esc_qtr_amt-$paid_esc_qtr_amt),2);
			//echo $bal_amt_to_be_paid;exit;
			
			$rev_esc_total_amt 	= $rev_esc_total_amt + $bal_amt_to_be_paid;//$rev_esc_qtr_amt;//rev_esc_tcc_amount + $rev_esc_tca_amount;
		}
	}
}


$esc_total_amt = round($esc_total_amt);
$rev_esc_total_amt = round($rev_esc_total_amt);

$netAmount = round(($abstAmount+$esc_total_amt+$rev_esc_total_amt),2);

// For find Secured Advance
$sec_adv_amount = 0;
$select_sec_adv_query = "select sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn = '$Rbn'";
$select_sec_adv_sql = mysql_query($select_sec_adv_query);
if($select_sec_adv_sql == true)
{
	if(mysql_num_rows($select_sec_adv_sql)>0)
	{
		$SAList = mysql_fetch_object($select_sec_adv_sql);
		$sec_adv_amount = $SAList->sec_adv_amount;
	}
}


$select_section_query = "select sheet_id, section_abcd, section_type, under_civil_sheetid from sheet where under_civil_sheetid = '$sheetid'";
$select_section_sql = mysql_query($select_section_query);
if($select_section_sql == true){
	if(mysql_num_rows($select_section_sql)>0){
		while($SHList = mysql_fetch_object($select_section_sql)){
			$SectSheetid = $SHList->sheet_id;
			$other_sec_adv_amt = getSecuredAdvanceAmt($SectSheetid,$Rbn);
			$other_escal_amt = getEscalationAmt($SectSheetid,$Rbn);
			$other_section_net_amt = getOtheSectionRABAmt($SectSheetid,$Rbn);
			$sec_adv_amount = round(($sec_adv_amount+$other_sec_adv_amt),2);
			$netAmount = round(($netAmount+$other_escal_amt),2);
			$netAmount = round(($netAmount+$other_section_net_amt),2);
		}
	}
}

$recoverydata = $netAmount."***".$Rbn."***".$sec_adv_amount;


//echo 
echo $recoverydata;
	
?>
