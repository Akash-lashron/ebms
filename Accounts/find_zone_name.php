<?php
require_once 'library/config.php';
$sheetid = $_GET['workorderno'];
$zone_name_query 	= "select zone_id, zone_name from zone where sheetid = '$sheetid'";
$zone_name_sql 		= mysqli_query($dbConn,$zone_name_query);
if($zone_name_sql == true)
{
	while($ZoneList = mysqli_fetch_object($zone_name_sql))
	{
		$zone_id 	= $ZoneList->zone_id;
		$zone_name 	= $ZoneList->zone_name;
		$ZoneData 	.= $zone_id."*".$zone_name."*";
	}
	$ZoneData 		= rtrim($ZoneData,"*");
}
else
{
	$ZoneData = "";
}
echo $ZoneData;	
?>
