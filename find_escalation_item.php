<?php
require_once 'library/config.php';
$sheetid	=  $_GET['sheetid'];
$type		=  $_GET['type'];
$select_bi_tca_query 	= "select distinct base_index_code, base_index_item, bid from base_index where sheetid = '$sheetid' and type = '$type' and active = 1";
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
			$base_index_str    .= $bid."*@*".$base_index_item."*@*".$base_index_code."*@*";
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