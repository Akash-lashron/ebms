<?php
require_once 'library/config.php';
$sheetid	=  $_GET['workorderno'];
$type		=  $_GET['type'];
$select_bi_tca_query 	= "select * from base_index where sheetid = '$sheetid' and type = '$type' and active = 1";
$select_bi_tca_sql 		= mysql_query($select_bi_tca_query);
if($select_bi_tca_sql == true)
{
	if(mysql_num_rows($select_bi_tca_sql)>0)
	{
		while($BiTCAList = mysql_fetch_object($select_bi_tca_sql))
		{
			$bid 				= $BiTCAList->bid;
			$base_index_item  	= $BiTCAList->base_index_item;
			$base_index_code 	= $BiTCAList->base_index_code;
			$base_index_rate 	= $BiTCAList->base_index_rate;
			$base_breakup_code 	= $BiTCAList->base_breakup_code;
			$base_breakup_perc 	= $BiTCAList->base_breakup_perc;
			$base_price_code 	= $BiTCAList->base_price_code;
			$base_price_rate 	= $BiTCAList->base_price_rate;
			$base_index_str    .= $bid."*@*".$base_index_item."*@*".$base_index_code."*@*".$base_index_rate."*@*".$base_breakup_code."*@*".$base_breakup_perc."*@*".$base_price_code."*@*".$base_price_rate."*@*";
		}
		$base_index_str = rtrim($base_index_str,"*@*");
	}
	else
	{
		$base_index_str = "";
	}
}
else
{
	$base_index_str = "";
}
echo $base_index_str;
?>