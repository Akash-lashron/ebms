<?php
require_once 'library/config.php';
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}

$sheetid	=  $_GET['sheetid'];
$type		=  $_GET['type'];
$bid		=  $_GET['bid'];
$from_date	=  $_GET['fromdate'];
$to_date	=  $_GET['todate'];
$quarter	=  $_GET['quarter'];
$code		=  $_GET['code'];
$fromdate 	= dt_format($from_date);
$todate 	= dt_format($to_date);
$month_count = 0;
/*$select_tca_query 	= 	"select esc_consumption_10ca.item_code, esc_consumption_10ca.item_qty, esc_consumption_10ca.tc_unit, base_index.bid, 
						esc_consumption_10ca.esc_month, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
						base_index.base_price_code, base_index.base_price_rate, price_index.pi_from_date, price_index.pi_to_date, price_index.avg_pi_code,
						price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid, esc_consumption_10ca.subdivid, esc_consumption_10ca.esc_item_type, 
						schdule.per, schdule.decimal_placed
						from esc_consumption_10ca
						INNER JOIN base_index ON (esc_consumption_10ca.item_code = base_index.base_index_code)
						INNER JOIN price_index ON (price_index.bid = base_index.bid)
						INNER JOIN price_index_detail ON (price_index_detail.pi_month = esc_consumption_10ca.esc_month)
						INNER JOIN schdule ON (schdule.subdiv_id = esc_consumption_10ca.subdivid)
						WHERE esc_consumption_10ca.sheetid = '$sheetid' AND base_index.active=1 AND price_index_detail.pid = price_index.pid
						AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
						AND base_index.bid = '$bid' AND price_index.bid = '$bid'
						ORDER BY esc_consumption_10ca.mdate ASC";*/
if($code == "CsIo"){
	$month_count = 0;
	$InvoiceYrArr = array(); $IndexArr = array();
	$SelectQuery1 	= "select a.*, b.* from esc_consumption_10ca_site a inner join base_index b on (a.item_code = b.base_index_code) 
					  where a.sheetid = '$sheetid' and a.item_code = 'CsIo' and a.quarter = '$quarter' and b.active = 1"; //echo $SelectQuery1;exit;
	$SelectSql1 	= mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1)>0){
			while($List = mysql_fetch_object($SelectSql1)){
				$InvoiceMonYr 	= $List->invoice_mon;
				$InvoiceYear 	= date("Y",strtotime($InvoiceMonYr));
				//echo $InvoiceYear;exit;
				$InvoiceMonth 	= date("m",strtotime($InvoiceMonYr));
				if(in_array($InvoiceYear,$InvoiceYrArr)){
					
				}else{
					$SelectQuey2 = "select * from monthly_index where year = '$InvoiceYear' and mat_code = 'STE' and mat_category = '10CA'";
					$SelectSql2 	= mysql_query($SelectQuey2);
					if($SelectSql2 == true){
						if(mysql_num_rows($SelectSql2)>0){
							$List2 = mysql_fetch_object($SelectSql2);
							$Jan = $List2->jan; $Feb = $List2->feb; $Mar = $List2->mar;
							$Apr = $List2->apr; $May = $List2->may; $Jun = $List2->jun;
							$Jul = $List2->jul; $Aug = $List2->aug; $Sep = $List2->sep;
							$Oct = $List2->oct; $Nov = $List2->nov; $Dec = $List2->dece;
							$IndexArr['Jan-'.$InvoiceYear] = $Jan; $IndexArr['Feb-'.$InvoiceYear] = $Feb; $IndexArr['Mar-'.$InvoiceYear] = $Mar;
							$IndexArr['Apr-'.$InvoiceYear] = $Apr; $IndexArr['May-'.$InvoiceYear] = $May; $IndexArr['Jun-'.$InvoiceYear] = $Jun;
							$IndexArr['Jul-'.$InvoiceYear] = $Jul; $IndexArr['Aug-'.$InvoiceYear] = $Aug; $IndexArr['Sep-'.$InvoiceYear] = $Sep; 
							$IndexArr['Oct-'.$InvoiceYear] = $Oct; $IndexArr['Nov-'.$InvoiceYear] = $Nov; $IndexArr['Dec-'.$InvoiceYear] = $Dec;
						}
					}
					array_push($InvoiceYrArr,InvoiceYear);
				}
				$PriceIndexRate = $IndexArr[$InvoiceMonYr];
				$Decimal = 3;
				$base_index_str .= $List->bid."*@*".''."*@*".$List->base_index_item
					."*@*".$InvoiceMonYr."*@*".$List->base_index_rate."*@*".$List->base_index_code
					."*@*".$PriceIndexRate."*@*".'CsI'."*@*".$List->base_price_rate
					."*@*".$List->base_price_code."*@*".$List->eligible_qty."*@*".$Decimal
					."*@*".$List->eligible_qty."*@*".'CEM'."*@*";
					
				$month_count++;
			}
		}
	}
	$TCADataStr = $base_index_str."@@##@@".$month_count;
}else if($code == "SsIo"){
	$month_count = 0;
	$InvoiceYrArr = array(); $IndexArr = array();
	$SelectQuery1 	= "select a.*, b.* from esc_consumption_10ca_site a inner join base_index b on (a.item_code = b.base_index_code) 
					  where a.sheetid = '$sheetid' and a.item_code = 'SsIo' and a.quarter = '$quarter' and b.active = 1"; //echo $SelectQuery1;exit;
	$SelectSql1 	= mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1)>0){
			while($List = mysql_fetch_object($SelectSql1)){
				$InvoiceMonYr 	= $List->invoice_mon;
				$InvoiceYear 	= date("Y",strtotime($InvoiceMonYr));
				//echo $InvoiceYear;exit;
				$InvoiceMonth 	= date("m",strtotime($InvoiceMonYr));
				if(in_array($InvoiceYear,$InvoiceYrArr)){
					
				}else{
					$SelectQuey2 = "select * from monthly_index where year = '$InvoiceYear' and mat_code = 'SSTE' and mat_category = '10CA'";
					$SelectSql2 	= mysql_query($SelectQuey2);
					if($SelectSql2 == true){
						if(mysql_num_rows($SelectSql2)>0){
							$List2 = mysql_fetch_object($SelectSql2);
							$Jan = $List2->jan; $Feb = $List2->feb; $Mar = $List2->mar;
							$Apr = $List2->apr; $May = $List2->may; $Jun = $List2->jun;
							$Jul = $List2->jul; $Aug = $List2->aug; $Sep = $List2->sep;
							$Oct = $List2->oct; $Nov = $List2->nov; $Dec = $List2->dece;
							$IndexArr['Jan-'.$InvoiceYear] = $Jan; $IndexArr['Feb-'.$InvoiceYear] = $Feb; $IndexArr['Mar-'.$InvoiceYear] = $Mar;
							$IndexArr['Apr-'.$InvoiceYear] = $Apr; $IndexArr['May-'.$InvoiceYear] = $May; $IndexArr['Jun-'.$InvoiceYear] = $Jun;
							$IndexArr['Jul-'.$InvoiceYear] = $Jul; $IndexArr['Aug-'.$InvoiceYear] = $Aug; $IndexArr['Sep-'.$InvoiceYear] = $Sep; 
							$IndexArr['Oct-'.$InvoiceYear] = $Oct; $IndexArr['Nov-'.$InvoiceYear] = $Nov; $IndexArr['Dec-'.$InvoiceYear] = $Dec;
						}
					}
					array_push($InvoiceYrArr,InvoiceYear);
				}
				$PriceIndexRate = $IndexArr[$InvoiceMonYr];
				$Decimal = 3;
				$base_index_str .= $List->bid."*@*".''."*@*".$List->base_index_item
					."*@*".$InvoiceMonYr."*@*".$List->base_index_rate."*@*".$List->base_index_code
					."*@*".$PriceIndexRate."*@*".'SsI'."*@*".$List->base_price_rate
					."*@*".$List->base_price_code."*@*".$List->eligible_qty."*@*".$Decimal
					."*@*".$List->eligible_qty."*@*".'STL'."*@*";
					
				$month_count++;
			}
		}
	}
	$TCADataStr = $base_index_str."@@##@@".$month_count;
}else{
	$select_tca_query 	= 	"select esc_consumption_10ca.item_code, esc_consumption_10ca.item_qty, esc_consumption_10ca.tc_unit, base_index.bid, 
							esc_consumption_10ca.esc_month, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
							base_index.base_price_code, base_index.base_price_rate, price_index.pi_from_date, price_index.pi_to_date, price_index.avg_pi_code,
							price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid, esc_consumption_10ca.subdivid, esc_consumption_10ca.esc_item_type, 
							schdule.per, schdule.decimal_placed, esc_consumption_10ca_master.ec_mas_id, esc_consumption_10ca_master.esc_rbn, esc_consumption_10ca_master.quarter
							from esc_consumption_10ca_master
							INNER JOIN esc_consumption_10ca ON (esc_consumption_10ca.ec_mas_id = esc_consumption_10ca_master.ec_mas_id)
							INNER JOIN base_index ON (esc_consumption_10ca.item_code = base_index.base_index_code)
							INNER JOIN price_index ON (price_index.bid = base_index.bid)
							INNER JOIN price_index_detail ON (price_index_detail.pi_month = esc_consumption_10ca.esc_month)
							INNER JOIN schdule ON (schdule.subdiv_id = esc_consumption_10ca.subdivid)
							WHERE esc_consumption_10ca.sheetid = '$sheetid' AND base_index.active=1 AND price_index_detail.pid = price_index.pid
							AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
							AND base_index.bid = '$bid' AND price_index.bid = '$bid' AND esc_consumption_10ca_master.quarter = '$quarter'
							ORDER BY esc_consumption_10ca.mdate ASC";						
							//echo $select_tca_query;exit;
	$select_tca_sql 	= mysql_query($select_tca_query);
	
	if($select_tca_sql == true)
	{
		if(mysql_num_rows($select_tca_sql)>0)
		{
			$prev_esc_month = ""; $esc_qty_month_wise = 0;
			while($TCAList = mysql_fetch_object($select_tca_sql))
			{
				// From base_index table
				$bid 				= $TCAList->bid;
				$base_index_item  	= $TCAList->base_index_item;
				$base_index_code 	= $TCAList->base_index_code;
				$base_index_rate 	= $TCAList->base_index_rate;
				$base_price_code 	= $TCAList->base_price_code;
				$base_price_rate 	= $TCAList->base_price_rate;
				
				// From price_index table
				$pid 			= $TCAList->pid;
				$pi_from_date 	= $TCAList->pi_from_date;
				$pi_to_date 	= $TCAList->pi_to_date;
				$avg_pi_code 	= $TCAList->avg_pi_code;
				
				// From price_index_details table
				$pi_month 		= $TCAList->pi_month;
				$pi_rate 		= $TCAList->pi_rate;
				
				// From esc_consumption_10ca table
				$item_qty 		= $TCAList->item_qty; 
				$tc_unit 		= $TCAList->tc_unit; 
				$esc_month 		= $TCAList->esc_month;
				$esc_item_type 	= $TCAList->esc_item_type;
				
				// From schedule Table
				$decimal_placed = $TCAList->decimal_placed;
				
				if(($prev_esc_month != "") && ($prev_esc_month != $esc_month))
				{
					$esc_qty_month_wise = round($esc_qty_month_wise,$prev_decimal_placed);
					if($esc_item_type == 'STL')
					{
						$esc_qty_month_wise_mt = $esc_qty_month_wise;
					}
					else
					{
						$esc_qty_month_wise_mt = round(($esc_qty_month_wise/1000),$prev_decimal_placed);
					}
					
					$base_index_str1    .= $prev_bid."*@*".$prev_pid."*@*".$prev_base_index_item
					."*@*".$prev_esc_month."*@*".$prev_base_index_rate."*@*".$prev_base_index_code
					."*@*".$prev_pi_rate."*@*".$prev_avg_pi_code."*@*".$prev_base_price_rate
					."*@*".$prev_base_price_code."*@*".$esc_qty_month_wise."*@*".$prev_decimal_placed
					."*@*".$esc_qty_month_wise_mt."*@*".$prev_esc_item_type."*@*";
					
					$esc_qty_month_wise	 = 0; $esc_qty_month_wise_mt = 0;
					$month_count++;
				}
				if($tc_unit == 0)
				{
					$tc_unit_temp1 = 1;
					$tc_unit_temp2 = "";
				}
				else
				{
					$tc_unit_temp1 = $tc_unit;
					$tc_unit_temp2 = $tc_unit;
				}
				//$esc_qty 				= 	round(($item_qty*$tc_unit_temp1),$decimal_placed);
				$esc_qty 				= 	$item_qty*$tc_unit_temp1;
				$esc_qty_month_wise		= 	$esc_qty_month_wise + $esc_qty;
				//$str1 .= $esc_qty."*".$tc_unit."@@";
				
				$prev_bid 				= $bid;
				$prev_base_index_item  	= $base_index_item;
				$prev_base_index_code 	= $base_index_code;
				$prev_base_index_rate 	= $base_index_rate;
				$prev_base_price_code 	= $base_price_code;
				$prev_base_price_rate 	= $base_price_rate;
				
				$prev_pid 				= $pid;
				$prev_pi_from_date 		= $pi_from_date;
				$prev_pi_to_date 		= $pi_to_date;
				$prev_avg_pi_code 		= $avg_pi_code;
				$prev_pi_month 			= $pi_month;
				$prev_pi_rate 			= $pi_rate;
				
				$prev_item_qty 			= $item_qty; 
				$prev_tc_unit 			= $tc_unit; 
				$prev_esc_month 		= $esc_month;
				$prev_esc_item_type 	= $esc_item_type;
				
				$prev_decimal_placed 	= $decimal_placed;
				
			}
			if($esc_qty_month_wise != 0)
			{
				$month_count++;
			}
			$esc_qty_month_wise = round($esc_qty_month_wise,$prev_decimal_placed);
			//$str2 .= $esc_qty."*".$prev_tc_unit;
			//$str = $str1.$str2;
			if($prev_esc_item_type == 'STL')
			{
				$esc_qty_month_wise_mt = $esc_qty_month_wise;
			}
			else
			{
				$esc_qty_month_wise_mt = round(($esc_qty_month_wise/1000),$prev_decimal_placed);
			}
			
			$base_index_str2    .= $prev_bid."*@*".$prev_pid."*@*".$prev_base_index_item
			."*@*".$prev_esc_month."*@*".$prev_base_index_rate."*@*".$prev_base_index_code
			."*@*".$prev_pi_rate."*@*".$prev_avg_pi_code."*@*".$prev_base_price_rate
			."*@*".$prev_base_price_code."*@*".$esc_qty_month_wise."*@*".$prev_decimal_placed
			."*@*".$esc_qty_month_wise_mt."*@*".$prev_esc_item_type;
			
			$base_index_str = $base_index_str1.$base_index_str2;
			
			$TCADataStr = $base_index_str."@@##@@".$month_count;
		}
	}
}
echo $TCADataStr;
//print_r($IndexArr);
//echo $select_tca_query;
?>