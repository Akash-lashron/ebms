<?php
require_once 'library/config.php';
$sheetid		=  $_POST[sheetid];
$SelectRABQuery = "select distinct rbn from measurementbook_temp where sheetid = '$sheetid'";
$SelectRABSql = mysql_query($SelectRABQuery);
if($SelectRABSql == true){
	if(mysql_num_rows($SelectRABSql)>0){
		$RABList = mysql_fetch_object($SelectRABSql);
		$rbn = $RABList->rbn;
	}
}
if(($sheetid != "")&&($rbn != "")){
	$SelectMBQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectMBSql = mysql_query($SelectMBQuery);
	if($SelectMBSql == true){
		if(mysql_num_rows($SelectMBSql)>0){
			while($List = mysql_fetch_assoc($SelectMBSql)){
				$rows[] = $List;
			}
		}
	}
}
echo json_encode($rows);
?>