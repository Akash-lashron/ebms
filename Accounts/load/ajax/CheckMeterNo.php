<?php
@ob_start();
require_once '../../library/config.php';
$meter_no		= $_POST[meterno];
//$sheetid		= $_POST[sheetid];
$meter_no 		= str_replace('-','',$meter_no);
$Count = 0;
$select_query 	= "select meter_no from electricity_recovery where meter_no = '$meter_no'";
$select_sql = mysql_query($select_query);
if($select_sql == true){
	$Count = mysql_num_rows($select_sql);
}
echo $Count;
?> 