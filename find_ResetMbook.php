<?php
require_once("library/binddata.php");
$sheetid	=	$_POST['sheetid'];
$rbn		=	$_POST['rbn'];
$zoneid		=	$_POST['zoneid'];
$mbno		=	$_POST['mbno'];
$mbtype		=	$_POST['mbtype'];
$flag		=	$_POST['flag'];
$Count1 = 0; $Count2 = 0; $Count3 = 0;
/// For Reset MBook
if($mbtype == "MB"){
	$select_mb_query = "select * from mbookgenerate_staff where sheetid='$sheetid' and flag = '$flag' and rbn = '$rbn' and zone_id = '$zoneid'";
	$select_mb_sql = mysql_query($select_mb_query);
	if($select_mb_sql == true)
	{
		if(mysql_num_rows($select_mb_sql)>0)
		{
			$Count1 = 1;
		}
	}
	if($Count1 == 1){
		$delete_query = "delete from mbookgenerate_staff where sheetid='$sheetid' and flag = '$flag' and rbn = '$rbn' and zone_id = '$zoneid'";
		//$delete_sql = mysql_query($delete_query);
	}
}
/// For Reset Sub - Abstract
if($mbtype == "SA"){
	$select_sa_query = "select * from mbookgenerate where sheetid='$sheetid' and rbn = '$rbn'";
	$select_sa_sql = mysql_query($select_sa_query);
	if($select_sa_sql == true)
	{
		if(mysql_num_rows($select_sa_sql)>0)
		{
			$Count2 = 1;
		}
	}
	if($Count2 == 1){
		$delete_query = "delete from mbookgenerate where sheetid='$sheetid' and rbn = '$rbn'";//.$zone_clause;
		$delete_sql = mysql_query($delete_query);
	}
}
/// For Reset Abstract
if($mbtype == "AB"){
	$select_ab_query = "select * from measurementbook_temp where sheetid='$sheetid' and rbn = '$rbn'";
	$select_ab_sql = mysql_query($select_ab_query);
	if($select_ab_sql == true)
	{
		if(mysql_num_rows($select_ab_sql)>0)
		{
			$Count3 = 1;
		}
	}
	if($Count3 == 1){
		$delete_query = "delete from measurementbook_temp where sheetid='$sheetid' and rbn = '$rbn'";//.$zone_clause;
		$delete_sql = mysql_query($delete_query);
	}
}
if(mysql_affected_rows() > 0) 
{
	echo "1";	
}
else
{
	echo "0";
}
//echo 1;
?>