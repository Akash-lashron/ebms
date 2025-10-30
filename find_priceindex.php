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

$select_pi_query 	= 	"select * from price_index where sheetid = '$sheetid' and type = '$type' and active = 1 and 
							pi_from_period = '$pi_from_period' and pi_to_period = '$pi_to_period'";
$select_pi_sql 		= mysql_query($select_pi_query);
if($select_pi_sql == true)
{
	if(mysql_num_rows($select_pi_sql)>0)
	{
		while($PiList = mysql_fetch_object($select_pi_sql))
		{
			$pid 			= $PiList->pid;
			$bid 			= $PiList->bid;
			$pi_from_period = $PiList->pi_from_period;
			$pi_to_period 	= $PiList->pi_to_period;
			$pi_rate1 		= $PiList->pi_rate1;
			$pi_rate2 		= $PiList->pi_rate2;
			$pi_rate3 		= $PiList->pi_rate3;
			$avg_pi_code 	= $PiList->avg_pi_code;
			$avg_pi_rate 	= $PiList->avg_pi_rate;
			$price_index_str    .= $pid."*@*".$bid."*@*".$pi_from_period."*@*".$pi_to_period."*@*".$pi_rate1."*@*".$pi_rate2."*@*".$pi_rate3."*@*".$avg_pi_code."*@*".$avg_pi_rate."*@*";
		}
		$price_index_str = rtrim($price_index_str,"*@*");
	}
	else
	{
		$price_index_str = "";
	}
}
else
{
	$price_index_str = "";
}
echo $price_index_str;
?>