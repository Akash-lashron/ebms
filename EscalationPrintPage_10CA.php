<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
include "spellnumber.php";
$msg = ''; $Line = 0;
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '/' . $mm . '/' . $yy;
}
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
//$sheetid    	= 	$_GET['escal_sheetid'];
//$_SESSION["abstsheetid"] = 	$_GET['workno'];
$sheetid    	= 	$_SESSION["escal_sheetid"];
$esc_from_date  = 	$_SESSION["escal_from_date"];
$esc_to_date   	= 	$_SESSION["escal_to_date"];
//echo $esc_to_date;
$fromdate 		= 	dt_format($esc_from_date);
$todate 		= 	dt_format($esc_to_date);
//echo $todate;
$MonthList 		= 	array();
if(($esc_from_date != "") && ($esc_to_date != ""))
{
	$time   = strtotime($esc_from_date);
	$last   = date('M-Y', strtotime($esc_to_date));
	while ($month != $last) 
	{
		$month = date('M-Y', $time);
		$total = date('t', $time);
		array_push($MonthList,$month);
		$time = strtotime('+1 month', $time);
	}
}
//print_r($MonthList);
$moncnt = count($MonthList);
$fir_month 	= $MonthList[0];
$las_month 	= $MonthList[$moncnt-1];
$query 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 					= 	mysql_fetch_object($sqlquery);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor; 
	$ccno 					= 	$List->computer_code_no;    
	$agree_no 				= 	$List->agree_no; 
	$overall_rebate_perc 	= 	$List->rebate_percent; 
	$runn_acc_bill_no 		= 	$rbn;
	$work_order_no 			= 	$List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length1 				= 	strlen($work_name);
 	$start_line1 			= 	ceil($length1/70); 
	$length2 				= 	strlen($agree_no);
	$start_line2 			= 	ceil($length2/27);  
	$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
}
$Line = $Line + $LineIncr;
//echo $LineIncr;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Abstrack MBook</title>
    <link rel="stylesheet" href="script/font.css" />
</head>
	<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
	<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
	<link rel="stylesheet" href="css/button_style.css"></link>
	<link rel="stylesheet" href="js/jquery-ui.css">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css">
	<link rel="stylesheet" href="Font style/font.css" />
	<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
	<script type='text/javascript' src='js/basic_model_jquery.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
	<link rel="stylesheet" href="css/font-awesome.css" />
	<!--<script type='text/javascript' src='js/basic.js'></script>-->
	<script src="dist/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="dist/sweetalert.css">
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		url = "EscalationPrint_10CA.php";
		window.location.replace(url);
	}
</script>
<style>
.pagetitle
{
	text-shadow:
    -1px -1px 0 #7F7F7F,
    1px -1px 0 #7F7F7F,
    -1px 1px 0 #7F7F7F,
    1px 1px 0 #7F7F7F; 
}
.table1
{
	color:#BF0602;
	/*color:#921601;*/
	border: 1px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
	padding-top:4px;
	padding-bottom:4px;
	padding-left:4px;
	padding-right:4px;
}
.fontcolor1
{
	color:#FFFFFF;
}

.popuptitle
{
	background-color:#0080FF;
	font-weight:bold;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#FFFFFF;
	line-height:25px;
	border:1px solid #9b9da0;
}
.table2
{
	color:#071A98;
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.bottomsection
{
 	position: absolute;
    bottom: 0;
	width:100%;
	line-height:38px;
}
.buttonsection
{
	display: inline-block;
	line-height:38px;
}
.buttonstyle
{
	background-color:#0080FF;
	width:80px;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0080FF;
	-webkit-box-shadow: 0px 1px 0px 0px #0080FF;
	box-shadow: 0px 1px 0px 0px #0080FF;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0080FF));
	background:-moz-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0080FF 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0080FF',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;

}
.buttonstyle:hover
{
	font-size:14px;
	padding: 0.1em 1em;
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.popuptextbox
{
	border:none;
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	color:#DE0117;
	text-align:center;
	pointer-events: none;
}
.dynamictextbox
{
	border:1px solid #ffffff;
	height:21px;
	color:#DE0117;
	font-weight:bold;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	/*outline: none;*/
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;
    /*border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;*/
}
.dynamictextbox2
{
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;	
}
.dynamicrowcell
{
	padding-bottom:0px;
	padding-top:0px; 
	padding-left:0px; 
	padding-right:0px;
	text-align:right;
	font:Verdana, Arial, Helvetica, sans-serif;
}
.hide
{
	display:none;
}
.labelprint
{
	font-weight:normal;
	color:#000000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}
</style>		
<body bgcolor="" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<form name="form" method="post" onsubmit="return confirm('Do you really want to submit the Book?');">
<?php
$page = $abstmbpage;
$title = '<table width="875" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;"><br/><br/>Escalation for 10CA ('.$fir_month.' to '.$las_month.')&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
$table = $table . "<table width='875'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
$table = $table . "<tr>";
$table = $table . "<td width='10%' class=''>Name of work</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td width='10%' class=''>Agreement No.</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$agree_no."</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>Work order No.</td>";
$table = $table . "<td class=''>" . $work_order_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>Technical Sanction No.</td>";
$table = $table . "<td class=''>" . $tech_sanction . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>Name of the contractor</td>";
$table = $table . "<td class=''>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>CC No.</td>";
$table = $table . "<td class=''>" . $ccno . "</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
//$Line = $Line+6;
//$tablehead = $tablehead . "<table width='1087px' frame=''  bgcolor='#0A9CC5' border='1' cellpadding='3' cellspacing='3' align='center' style='color:#ffffff;' id='mbookdetail' class='label table1'>";
$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
//$tablehead = $tablehead . "<td  align='center' class='labelsmall labelheadblue' width='12px' style='background-color:#0A9CC5;' rowspan='2'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='44px' rowspan='2'>Item No.</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='130px' rowspan='2'>Description of work</td>";
$tablehead = $tablehead . "<td  align='center'  width='40px' rowspan='2'>Contents of Area</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Rate&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Per</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Total value to Date&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='100px' colspan='3'>Deduct previous Measurements</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='120px' colspan='3'>Since Last Measurement</td>";
$tablehead = $tablehead . "</tr>";
$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
$tablehead = $tablehead . "<td width='30px' align='center' class=''>Page</td>";
$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
$tablehead = $tablehead . "<td width='40px' align='center' class=''>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
$tablehead = $tablehead . "<td width='40px' align='center' class=''>Value&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td width='40px' align='center' class=''>Remark</td>";
$tablehead = $tablehead . "</tr>";
echo $table; 

//$sheetid	=  $_GET['sheetid'];
//$type		=  $_GET['type'];
//$bid		=  $_GET['bid'];
//$from_date	=  $_GET['fromdate'];
//$to_date	=  $_GET['todate'];
//$fromdate 	=  dt_format($from_date);
//$todate 	=  dt_format($to_date);

//$EscMonthArr 	=	array();
//$bid= 5;
?>
<table width='875' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr style=" height:35px;">
		<td align="center" valign="middle" nowrap="nowrap">Description</td>
		<td align="center" valign="middle" nowrap="nowrap">Month</td>
		<td align="center" valign="middle" nowrap="nowrap"> Qty.<br/>in mt. </td>
		<td align="center" valign="middle">Base <br/>Index</td>
		<td align="center" valign="middle">Base <br/>Price</td>
		<td align="center" valign="middle">Price <br/>Index</td>
		<td align="center" valign="middle">Formula</td>
		<td align="center" valign="middle">Formula with Values</td>
		<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
	</tr>
<?php
$BidStr = "";
$select_bid_query 	= "select distinct bid from escalation_10ca_details where sheetid = '$sheetid'";
$select_bid_sql 	= mysql_query($select_bid_query);
if($select_bid_sql == true)
{
	if(mysql_num_rows($select_bid_sql)>0)
	{
		while($BidList = mysql_fetch_object($select_bid_sql))
		{
			$bid = $BidList->bid;
			$BidStr .= $bid."*";
		}
	}
}
$BidStr = rtrim($BidStr,"*");
$expBidStr 	= explode("*",$BidStr);
$BidCnt 	= count($expBidStr);
//echo $BidStr;
for($x7=0; $x7<$BidCnt; $x7++)
{
$bid = $expBidStr[$x7];
$month_count = 0;
$base_index_str1 = "";
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

						
						
$select_tca_query 	= 	"select base_index.bid, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
						base_index.base_price_code, base_index.base_price_rate, price_index.pi_from_date, price_index.pi_to_date, price_index.avg_pi_code,
						price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid
						from base_index
						INNER JOIN price_index ON (price_index.bid = base_index.bid)
						INNER JOIN price_index_detail ON (price_index_detail.pid = price_index.pid)
						WHERE base_index.active=1 AND price_index_detail.pid = price_index.pid
						AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
						AND base_index.bid = '$bid' AND price_index.bid = '$bid'";						
$select_tca_sql 	= mysql_query($select_tca_query);
//echo $select_tca_query."<br/>";
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
			
			if($base_index_code == "SIo")
			{
				$where_clause = "SUM(esc_consumption_10ca.item_qty) as total_qty";
			}
			else
			{
				$where_clause = "SUM(esc_consumption_10ca.item_qty*esc_consumption_10ca.tc_unit) as total_qty";
			}
			
			$select_tca_consum_query = "select esc_consumption_10ca.item_code, ".$where_clause.", 
										esc_consumption_10ca.esc_month,esc_consumption_10ca.subdivid, esc_consumption_10ca.esc_item_type, 
										schdule.per, schdule.decimal_placed from esc_consumption_10ca
										INNER JOIN schdule ON (schdule.subdiv_id = esc_consumption_10ca.subdivid)
										WHERE esc_consumption_10ca.sheetid = '$sheetid' and esc_consumption_10ca.item_code = '$base_index_code' and 
										esc_consumption_10ca.esc_month = '$pi_month'"; 
			
			//echo $select_tca_consum_query."<br/>";
			$select_tca_consum_sql = mysql_query($select_tca_consum_query);
			if($select_tca_consum_sql == true )
			{
				if(mysql_num_rows($select_tca_consum_sql)>0)
				{
					$TCA = mysql_fetch_object($select_tca_consum_sql);
					$item_qty 		= $TCA->total_qty; 
					//$tc_unit 		= $TCA->tc_unit; 
					//$esc_month 		= $TCA->esc_month;
					//$esc_item_type 	= $TCA->esc_item_type;
					$decimal_placed = $TCA->decimal_placed;
				}
				else
				{
					$item_qty 		= 0; 
					//$tc_unit 		= "--"; 
					//$esc_month 		= $TCA->esc_month;
					//$esc_item_type 	= "--";
					$decimal_placed = 3;
				}
			}
			$esc_month = $pi_month;
			$qty_month_wise = $item_qty;
			$qty_month_wise_mt = round(($qty_month_wise/1000),$decimal_placed);
			if($base_index_code == "CIo")
			{
				$esc_item_type = "CEM";
			}
			if($base_index_code == "SIo")
			{
				$esc_item_type = "STL";
			}
			
			$base_index_str1    .= $bid."*@*".$pid."*@*".$base_index_item
				."*@*".$esc_month."*@*".$base_index_rate."*@*".$base_index_code
				."*@*".$pi_rate."*@*".$avg_pi_code."*@*".$base_price_rate
				."*@*".$base_price_code."*@*".$qty_month_wise."*@*".$decimal_placed
				."*@*".$qty_month_wise_mt."*@*".$esc_item_type."*@*";
				
				//echo $base_index_str1."<br/>";
			/*// From esc_consumption_10ca table
			$item_qty 		= $TCAList->item_qty; 
			$tc_unit 		= $TCAList->tc_unit; 
			$esc_month 		= $TCAList->esc_month;
			$esc_item_type 	= $TCAList->esc_item_type;
			
			// From schedule Table
			$decimal_placed = $TCAList->decimal_placed;*/
			
			/*if(($prev_esc_month != "") && ($prev_esc_month != $esc_month))
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
			}*/
			//$esc_qty 				= 	round(($item_qty*$tc_unit_temp1),$decimal_placed);
			/*$esc_qty 				= 	$item_qty*$tc_unit_temp1;
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
			
			$prev_decimal_placed 	= $decimal_placed;*/
			$month_count++;
		}
		/*if($esc_qty_month_wise != 0)
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
		
		$base_index_str = $base_index_str1.$base_index_str2;*/
		
		$base_index_str = trim($base_index_str1,"*@*");//.$base_index_str2;
		$TCADataStr = $base_index_str."@@##@@".$month_count;
	}
}
//echo $TCADataStr;

$expTCADataStr 	= explode("@@##@@",$TCADataStr);
$tca_data		= $expTCADataStr[0];
$PrintMonthCount	= $expTCADataStr[1];
//echo $PrintMonthCount;
$exptca_data = explode("*@*",$tca_data);
for($i = 0; $i<count($exptca_data); $i+=14)
{
	$Printbid 				= $exptca_data[$i+0];
	$Printpid 				= $exptca_data[$i+1];
	$Printbase_index_item 	= $exptca_data[$i+2];
	$Printesc_month 		= $exptca_data[$i+3];
	$Printbase_index_rate 	= $exptca_data[$i+4];
	$Printbase_index_code 	= $exptca_data[$i+5];
	$Printpi_rate 			= $exptca_data[$i+6];
	$Printpi_code 			= $exptca_data[$i+7];
	$Printbase_price_rate 	= $exptca_data[$i+8];
	$Printbase_price_code 	= $exptca_data[$i+9];
	$Printqty_month_wise 	= $exptca_data[$i+10];
	$Printdecimal_placed 	= $exptca_data[$i+11];
	$Printqty_month_wise_mt = $exptca_data[$i+12];
	$Printesc_item_type 	= $exptca_data[$i+13];
	$Printtca_formula 			= $Printbase_price_code." x "."Q"." x <br/>(".$Printpi_code." - ".$Printbase_index_code.")/".$Printbase_index_code;
	$Printtca_formula_with_val 	= $Printbase_price_rate." x ".$Printqty_month_wise_mt." x  <br/>(".$Printpi_rate." - ".$Printbase_index_rate.")/".$Printbase_index_rate;
	
	$Printesc_amount = $Printbase_price_rate*$Printqty_month_wise_mt*($Printpi_rate-$Printbase_index_rate)/$Printbase_index_rate;
	$Printesc_amount = round($Printesc_amount,2);
	if($i==0){?>
	<tr style=" height:35px;">
		<td align="center" valign="middle" nowrap="nowrap" rowspan="<?= $PrintMonthCount; ?>"><?= $Printbase_index_item; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printesc_month; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printqty_month_wise_mt; ?></td>
		<td align="center" valign="middle"><?= $Printbase_index_rate; ?></td>
		<td align="center" valign="middle"><?= $Printbase_price_rate; ?></td>
		<td align="center" valign="middle"><?= $Printpi_rate; ?></td>
		<td align="center" valign="middle"><?= $Printtca_formula; ?></td>
		<td align="center" valign="middle"><?= $Printtca_formula_with_val; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printesc_amount; ?></td>
	</tr>
<?php } else {?>
	<tr style=" height:35px;">
		<!--<td align="center" valign="middle" nowrap="nowrap"><?= $Printbase_index_item; ?></td>-->
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printesc_month; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printqty_month_wise_mt; ?></td>
		<td align="center" valign="middle"><?= $Printbase_index_rate; ?></td>
		<td align="center" valign="middle"><?= $Printbase_price_rate; ?></td>
		<td align="center" valign="middle"><?= $Printpi_rate; ?></td>
		<td align="center" valign="middle"><?= $Printtca_formula; ?></td>
		<td align="center" valign="middle"><?= $Printtca_formula_with_val; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $Printesc_amount; ?></td>
	</tr>	
<?php
}
}
}
?>
</table>
<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
	<div class="buttonsection">
		<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
	</div>
	<div class="buttonsection">
		<input type="submit" name="submit" id="submit" value=" View "/>
	</div>
</div>
</form>
</body>

</html>