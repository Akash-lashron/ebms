<?php
require_once 'library/config.php';
$sheetid = $_GET['workorderno'];
$temp = $_GET['temp'];
if($temp == 2)
{
	$select_recovery_query 	= 	"SELECT * FROM electricity_recovery WHERE sheetid = '$sheetid'";
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
			$factor			= 	$List->factor;
			$meter_data 	.= 	$meterno."*".$imr."*".$imrdate."*".$rate."*".$menterrent."*".$factor."*";
		}
	}
	echo rtrim($meter_data,"*");
	//echo "if = ".$select_recovery_query;
}
else
{
	$select_recovery_query 	= 	"SELECT * FROM electricity_recovery WHERE erecoverid = (SELECT MAX(erecoverid) FROM electricity_recovery WHERE sheetid = '$sheetid') AND  sheetid = '$sheetid'";
	$select_recovery_sql	=	mysql_query($select_recovery_query);
	if($select_recovery_sql == true) 
	{
		$List = mysql_fetch_object($select_recovery_sql);
		$meterno 		= 	$List->meter_no;
		$menterrent		= 	$List->meter_rent;
		$meter_data 	= 	$meterno."*".$menterrent; 
	}
	echo $meter_data;
	//echo "else = ".$temp;
}
	
?>
