<?php
require_once 'library/config.php';
$sheetid = $_GET['workorderno'];
$temp = $_GET['temp'];
if($temp == 2)
{
	$select_recovery_query 	= 	"SELECT * FROM water_recovery WHERE sheetid = '$sheetid'";
	$select_recovery_sql	=	mysql_query($select_recovery_query);
	if($select_recovery_sql == true) 
	{
		while($List = mysql_fetch_object($select_recovery_sql))
		{
			$meterno 		= 	$List->meter_no;
			$menterrent		= 	$List->meter_rent;
			$imr			= 	$List->imr;
			$imrdate		= 	$List->imr_date;
			$rate			= 	$List->rate;
			$w_limit		= 	$List->w_limit;
			$meter_data 	.= 	$meterno."*".$imr."*".$imrdate."*".$rate."*".$menterrent."*";
		}
	}
	echo rtrim($meter_data,"*");
	//echo "if = ".$select_recovery_query;
}
else
{
	$select_recovery_query 	= 	"SELECT * FROM water_recovery WHERE wrecoverid = (SELECT MAX(wrecoverid) FROM water_recovery WHERE sheetid = '$sheetid') AND  sheetid = '$sheetid'";
	$select_recovery_sql	=	mysql_query($select_recovery_query);
	if($select_recovery_sql == true) 
	{
		$List = mysql_fetch_object($select_recovery_sql);
		$meterno 		= 	$List->meter_no;
		$menterrent		= 	$List->meter_rent;
		$waterlimit		= 	$List->w_limit;
		$meter_data 	= 	$meterno."*".$menterrent."*".$waterlimit; 
	}
	echo $meter_data;
}	
?>
