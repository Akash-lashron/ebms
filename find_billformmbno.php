<?php
require_once 'library/config.php';
$sheetid 	= $_GET['sheetid'];
$staffid 	= $_GET['staffid'];
$type 		= $_GET['type']; $mbookno = '';
if(($sheetid != "") && ($staffid != "") && ($type != ""))
{
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
				$select_mbno_query = "select distinct mbno from mymbook where sheetid = '$sheetid' and staffid = '$staffid' and rbn = '$Rbn' and mtype = '$type'";
				$select_mbno_sql = mysql_query($select_mbno_query);
				if($select_mbno_sql == true)
				{
					while($MBNOList 	= 	mysql_fetch_object($select_mbno_sql)){
						$mbookno 	.= 	$MBNOList->mbno.",";
					}
					//$startpage 	= 	$MBNOList->startpage;
					//$endpage 	= 	$MBNOList->endpage;
				}
			}
		}
	}
}
$mbookno = rtrim($mbookno,",");
echo $mbookno;//."*".$startpage."*".$endpage;
//echo $select_mbno_query;
?>
