<?php
require_once 'library/config.php';
$sheetid = $_GET['sheetid'];
$recoverydata = "";
$abstAmount = 0;
$Rbn = "";
$esc_total_amt = 0;
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
			$esc_total_amt 	= $esc_total_amt + $esc_tcc_amount + $esc_tca_amount;
		}
	}
}
$esc_total_amt = round($esc_total_amt);
$netAmount = round(($abstAmount+$esc_total_amt),2);
$recoverydata = $netAmount."***".$Rbn;
echo $recoverydata;
	
?>
