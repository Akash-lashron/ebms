<?php
require_once 'library/config.php';
$sheetid		 =  $_GET['sheetid'];
$type			 =  $_GET['type'];
//$base_index_code =  $_GET['base_index_code'];
//$bid 			 =  $_GET['bid'];
/*if(($base_index_code != "") && ($bid == ""))
{
	$select_bi_query 	= 	"select bid from base_index where sheetid = '$sheetid' and type = '$type' and active = 1 and base_index_code = '$base_index_code'";
	$select_bi_sql 		= mysql_query($select_bi_query);
	if($select_bi_sql == true)
	{
		$BidList 	= mysql_fetch_object($select_bi_sql);
		$bid 		= $BidList->bid;
	}
}*/
$select_pi_query 	= 	"select * from price_index where sheetid = '$sheetid' and type = '$type' and active = '1' ORDER BY pid DESC LIMIT 1";
$select_pi_sql 		= mysql_query($select_pi_query);
if($select_pi_sql == true)
{
	if(mysql_num_rows($select_pi_sql)>0)
	{
		$PiList = mysql_fetch_object($select_pi_sql);
		$pid 			= $PiList->pid;
		$bid 			= $PiList->bid;
		
		$date1			= date_create($PiList->pi_from_date);
		$date2			= date_create($PiList->pi_to_date);
		
		$pi_from_date   = date_format($date1,"d-m-Y");
		$pi_to_date 	= date_format($date2,"d-m-Y");
		
		$avg_pi_code 	= $PiList->avg_pi_code;
		$avg_pi_rate 	= $PiList->avg_pi_rate;
		$type 			= $PiList->type;
		$price_index_str    .= $pid."*@*".$bid."*@*".$pi_from_date."*@*".$pi_to_date."*@*".$avg_pi_code."*@*".$avg_pi_rate."*@*".$type;;
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