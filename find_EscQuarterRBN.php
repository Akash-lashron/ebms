<?php
require_once 'library/config.php';
$sheetid		 	=  $_GET['sheetid'];
$QtrStr = "";
$RbnQtrStr = "";
$select_rbn_query 	= "select distinct(mbookgenerate.rbn), escalation.esc_id, escalation.quarter
						from mbookgenerate INNER JOIN escalation ON (escalation.rbn = mbookgenerate.rbn) 
						where mbookgenerate.sheetid = '$sheetid' and escalation.flag = 0 ORDER BY escalation.quarter ASC";
$select_rbn_sql 	= mysql_query($select_rbn_query);
if($select_rbn_sql == true)
{
	if(mysql_num_rows($select_rbn_sql)>0)
	{
		while($RbnList = mysql_fetch_object($select_rbn_sql))
		{
			$rbn = $RbnList->rbn;
			$quarter = $RbnList->quarter;
			$QtrStr .= $quarter."*";
		}
		$RbnQtrStr = $rbn."@".rtrim($QtrStr,"*");
	}
}
echo $RbnQtrStr;
?>