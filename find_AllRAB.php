<?php
require_once 'library/config.php';
$sheetid		 	=  $_GET['sheetid'];
$RbnStr = "";
$select_sa_query 	= "select distinct rbn from mbookgenerate_staff where sheetid = '$sheetid' order by rbn desc";
$select_sa_sql 	= mysql_query($select_sa_query);
if($select_sa_sql == true)
{
	if(mysql_num_rows($select_sa_sql)>0)
	{
		while($SAList = mysql_fetch_object($select_sa_sql))
		{
			$rbn 	 = $SAList->rbn;
			$RbnStr .= $rbn."*";
		}
		$RbnStr = rtrim($RbnStr,"*");
	}
}
echo $RbnStr;
?>