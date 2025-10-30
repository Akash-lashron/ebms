<?php
require_once 'library/config.php';
$sheetid		=  $_GET['sheetid'];
$type			=  $_GET['type'];
$pi_month1			=  $_GET['month1'];
$pi_month3			=  $_GET['month3'];
$from_period 		= 	new DateTime($pi_month1);
$to_period 			= 	new DateTime($pi_month3);
$pi_from_period 	=	date_format($from_period,'Y-m-d');
$pi_to_period 		=	date_format($to_period,'Y-m-t');
/// below query is for already price index exists for given period
$select_pi_query 	= "select * from price_index where sheetid = '$sheetid' and type = '$type' and active = 1 
						and (pi_from_period > '$pi_from_period' OR pi_from_period > '$pi_to_period' OR
						pi_to_period > '$pi_from_period' OR pi_to_period > '$pi_to_period')";
$select_pi_sql 		= mysql_query($select_pi_query);
if($select_pi_sql == true)
{
	if(mysql_num_rows($select_pi_sql)>0)
	{
		$pi_exist = 1;
	}
	else
	{
		$pi_exist = 0;
	}
}
else
{
	$pi_exist = 0;
}
echo $select_pi_query;
?>