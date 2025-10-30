<?php
require_once 'library/config.php';
$sheetid = $_GET['workorderno'];
$select_rbn_old_query 	= 	"select distinct rbn from measurementbook where sheetid='$sheetid' ORDER BY rbn ASC";
$select_rbn_old_sql		=	mysql_query($select_rbn_old_query);
while($OldRbnList = mysql_fetch_object($select_rbn_old_sql))
{
	$OldRAB = $OldRbnList->rbn;
	$OldRABText = "RAB";
	$OldRABStr .= $OldRAB."*".$OldRABText."*";
}

$select_rbn_new_query 	= 	"select distinct rbn from measurementbook_temp where sheetid='$sheetid' ORDER BY rbn ASC";
$select_rbn_new_sql		=	mysql_query($select_rbn_new_query);
while($NewRbnList = mysql_fetch_object($select_rbn_new_sql))
{
	$NewRAB = $NewRbnList->rbn;
	$NewRABText = "RAB(New)";
	$NewRABStr .= $NewRAB."*".$NewRABText."*";
}
$RBNStr = rtrim($OldRABStr.$NewRABStr,"*");
echo $RBNStr;
//echo $select_rbn_old_query;
?>
