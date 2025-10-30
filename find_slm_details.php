<?php
require_once 'library/config.php';

$sheetid = $_GET['sheetid'];
$itemid = $_GET['itemid'];
$DPM = "";
$dpm_details_sql = "select mbtotal, pay_percent, part_pay_flag, rbn, remarks, accounts_remarks from measurementbook_temp where sheetid = '$sheetid' AND subdivid = '$itemid' AND part_pay_flag = '1' ORDER BY rbn ASC ";
$dpm_details_query = mysql_query($dpm_details_sql);
if(mysql_num_rows($dpm_details_query)>0)
{
	while($DPMList = mysql_fetch_object($dpm_details_query))
	{
		$SlmRemarks = $DPMList->remarks;
		$AccountsRemarks = $DPMList->accounts_remarks;
		$DPM .= $DPMList->rbn."*".$DPMList->mbtotal."*".$DPMList->pay_percent."*";
	}
	$outputStr = rtrim($DPM,"*")."@@".$SlmRemarks."@@".$AccountsRemarks;
	//echo rtrim($DPM,"*");
	echo $outputStr;
}
else
{
	echo "";
}
	
?>
