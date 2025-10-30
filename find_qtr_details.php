<?php
require_once 'library/config.php';
$sheetid = $_GET['sheetid'];
$quarter = $_GET['quarter'];
$type = $_GET['type'];
$month_count = 0;
$prev_pid = "";$prev_pi_code = "";$prev_pi_from_date = "";$prev_pi_to_date = "";$prev_avg_pi_rate = "";
$prev_esc_rbn = ""; $prev_esc_id = ""; $prev_quarter = "";
$Pi_Data1 = "";
$Pi_Data2 = "";
$PiData = "";
$quarter_query 		= "select price_index.pid, price_index.pi_from_date, price_index.pi_to_date, price_index.avg_pi_code, 
					   price_index.avg_pi_rate, price_index.type, price_index.esc_rbn, price_index.esc_id, price_index.quarter,
					   price_index_detail.pdtid, price_index_detail.pi_month, price_index_detail.pi_rate 
					   from price_index 
					   INNER JOIN price_index_detail ON (price_index.pid = price_index_detail.pid)
					   where price_index.sheetid = '$sheetid' and price_index.quarter = '$quarter' and price_index.type = '$type' 
					   order by price_index.avg_pi_code asc";
$quarter_sql 		= mysql_query($quarter_query);
if($quarter_sql == true)
{
	while($QtrList = mysql_fetch_object($quarter_sql))
	{
		//$Qtr 	= $QtrList->quarter;
		//$QtrData 	.= $Qtr."*";
		$pid 			= $QtrList->pid;
		$pi_code 		= $QtrList->avg_pi_code;
		$pi_from_date 	= $QtrList->pi_from_date;
		$pi_to_date 	= $QtrList->pi_to_date;
		$avg_pi_rate 	= $QtrList->avg_pi_rate;
		$esc_rbn 		= $QtrList->esc_rbn;
		$esc_id 		= $QtrList->esc_id;
		$quarter 		= $QtrList->quarter;
		
		$pdtid 			= $QtrList->pdtid;
		$pi_month 		= $QtrList->pi_month;
		$pi_rate 		= $QtrList->pi_rate;
		
		if(($prev_pi_code != $pi_code) && ($prev_pi_code != ""))
		{
			$Pi_Data1 = rtrim($Pi_Data1,"@");
			$Pi_Data2 = $prev_pid."*".$prev_pi_code."*".$prev_pi_from_date."*".$prev_pi_to_date."*".$prev_avg_pi_rate."*".$prev_esc_rbn."*".$prev_esc_id."*".$prev_quarter;
			$PiData .= $Pi_Data2."#".$Pi_Data1."#@#";
			$Pi_Data1 = "";
			$Pi_Data2 = "";
		}
		
		$Pi_Data1 .= $pdtid."*".$pi_month."*".$pi_rate."@";
		$prev_pid 			= $pid;
		$prev_pi_code 		= $pi_code;
		$prev_pi_from_date 	= $pi_from_date;
		$prev_pi_to_date 	= $pi_to_date;
		$prev_avg_pi_rate 	= $avg_pi_rate;
		$prev_esc_rbn 		= $esc_rbn;
		$prev_esc_id 		= $esc_id;
		$prev_quarter 		= $quarter;
	}
	if($prev_pi_code != "")
	{
		$Pi_Data1 = rtrim($Pi_Data1,"@");
		$Pi_Data2 = $pid."*".$pi_code."*".$pi_from_date."*".$pi_to_date."*".$avg_pi_rate."*".$esc_rbn."*".$esc_id."*".$quarter;
		$PiData .= $Pi_Data2."#".$Pi_Data1."#@#";
		$Pi_Data1 = "";
		$Pi_Data2 = "";
	}

	//$QtrData 		= rtrim($QtrData,"*");
	$PiData = rtrim($PiData,"#@#");
}
else
{
	$PiData = "";
}
echo $PiData;	
?>
