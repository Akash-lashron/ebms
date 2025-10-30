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
function GetTCAconsumMbookNo($sheetid,$esc_rbn,$esc_id,$itemcode,$month,$type)
{
	$MBStr = " -- ";
	$select_tca_cons_mb_query 	= 	"select esc_cons_mbook, esc_cons_mbpage from esc_consumption_10ca_master 
									where sheetid = '$sheetid' and esc_rbn = '$esc_rbn' and esc_id = '$esc_id' and  
									 item_code = '$itemcode' and esc_month = '$month' and esc_item_type = '$type'";
	$select_tca_cons_mb_sql 	= 	mysql_query($select_tca_cons_mb_query);
	if($select_tca_cons_mb_sql == true)
	{
		if(mysql_num_rows($select_tca_cons_mb_sql)>0)
		{
			$MBList = mysql_fetch_object($select_tca_cons_mb_sql);
			$mbookno = $MBList->esc_cons_mbook;
			$mbookpage = $MBList->esc_cons_mbpage;
			if(($mbookno != 0)&&($mbookpage != ""))
			{
				$MBStr = $mbookno."/".$mbookpage;
			}
		}
	}
	return $MBStr;
}
// Below Part is common for both TCC and TCA

$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
//$sheetid    	= 	$_SESSION["escal_sheetid"];
if($_GET['sheetid'] != "")
{
	$sheetid 				= $_GET['sheetid'];
	$quarter 				= $_GET['quarter'];
	$select_rbn_query = "select distinct(mbookgenerate.rbn), escalation.esc_id, escalation.tca_fromdate, 
						escalation.tca_todate, escalation.tcc_fromdate, escalation.tcc_todate, escalation.quarter,
						escalation.tcc_absmbook, escalation.tcc_absmbpage, escalation.tca_absmbook, escalation.tca_absmbpage
						from mbookgenerate INNER JOIN escalation ON (escalation.rbn = mbookgenerate.rbn) 
						where mbookgenerate.sheetid = '$sheetid' and escalation.flag = 0 and escalation.quarter = '$quarter'";
						//echo $select_rbn_query;
	$select_rbn_sql = mysql_query($select_rbn_query);
	if($select_rbn_sql == true)
	{
		if(mysql_num_rows($select_rbn_sql)>0)
		{
			$RbnList 			= mysql_fetch_object($select_rbn_sql);
			$esc_id 			= $RbnList->esc_id;
			$esc_rbn 			= $RbnList->rbn;
			
			$tccfromdate 		= $RbnList->tcc_fromdate;
			$tcctodate 			= $RbnList->tcc_todate;
			
			$tccAbsmbook 		= $RbnList->tcc_absmbook;
			$tccAbsmbookpage 	= $RbnList->tcc_absmbpage;
			
			$tcafromdate 		= $RbnList->tca_fromdate;
			$tcatodate 			= $RbnList->tca_todate;
			
			$tcaAbsmbook 		= $RbnList->tca_absmbook;
			$tcaAbsmbookpage 	= $RbnList->tca_absmbpage;
		}
	}
}

$select_escmbook_query = "select * from mymbook where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and staffid = '$staffid' and mtype = 'E' and genlevel = 'escalation'";
$select_escmbook_sql = mysql_query($select_escmbook_query);
if($select_escmbook_sql == true)
{
	if(mysql_num_rows($select_escmbook_sql)>0)
	{
		$MBList = mysql_fetch_object($select_escmbook_sql);
		$esc_mbook 		= $MBList->mbno;
		$esc_mbookpage 	= $MBList->startpage;
	}
}
//$esc_rbn    	= 	$_SESSION["esc_rbn"];
//$esc_id    		= 	$_SESSION["esc_id"];
//$esc_mbook    	= 	$_SESSION["escal_mbook_no"];
//$esc_mbookpage  = 	$_SESSION["escal_mbook_pageno"];


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

// Below Part is common for TCA only
////*************************** TCA Starts Here *****************************////
			//$esc_tcafrom_date  	= 	$_SESSION["escal_tca_from_date"];
			//$esc_tcato_date   	= 	$_SESSION["escal_tca_to_date"];
$esc_tcafrom_date  	= 	$tcafromdate;
$esc_tcato_date   	= 	$tcatodate;
$tcafromdate 		= 	$esc_tcafrom_date;//dt_format($esc_tcafrom_date);
$tcatodate 			= 	$esc_tcato_date;//dt_format($esc_tcato_date);
$TCAMonthList 			= 	array();
if(($esc_tcafrom_date != "") && ($esc_tcato_date != ""))
{
	$TCAtime   = strtotime($esc_tcafrom_date);
	$TCAlast   = date('M-Y', strtotime($esc_tcato_date));
	while ($TCAmonth != $TCAlast) 
	{
		$TCAmonth = date('M-Y', $TCAtime);
		$total = date('t', $TCAtime);
		array_push($TCAMonthList,$TCAmonth);
		$TCAtime = strtotime('+1 month', $TCAtime);
	}
}
$TCAmoncnt = count($TCAMonthList);
$TCAfir_month 	= $TCAMonthList[0];
$TCAlas_month 	= $TCAMonthList[$TCAmoncnt-1];
////*************************** TCA Ends Here *****************************////

////*************************** TCC Starts Here *****************************////

				//$esc_from_date  = 	$_SESSION["escal_tcc_from_date"];
				//$esc_to_date   	= 	$_SESSION["escal_tcc_to_date"];
$esc_from_date  = 	$tccfromdate;
$esc_to_date   	= 	$tcctodate;				
$fromdate 		= 	$esc_from_date;//dt_format($esc_from_date);
$todate 		= 	$esc_to_date;//dt_format($esc_to_date);
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
////*************************** TCC Ends Here *****************************////
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
		url = "EscalationPrintGenerate.php";
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
	border: 0px solid #cacaca;
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
	border:0px solid #cacaca;
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
.hidtextbox
{
	border:none;
	width:98%;
	text-align:right;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
	font-weight:bold;
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

////*************************** TCA Starts Here *****************************////

eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));


?>
<table width='875' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr style=" height:35px;" class="labelbold">
		<td align="center" valign="middle" nowrap="nowrap">Description</td>
		<td align="center" valign="middle" nowrap="nowrap">Month</td>
		<td align="center" valign="middle" nowrap="nowrap"> Qty.<br/>in mt. </td>
		<td align="center" valign="middle" nowrap="nowrap"> MB/Page </td>
		<td align="center" valign="middle">Base <br/>Index</td>
		<td align="center" valign="middle">Base <br/>Price</td>
		<td align="center" valign="middle">Price <br/>Index</td>
		<td align="center" valign="middle">Formula</td>
		<td align="center" valign="middle">Formula with Values</td>
		<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
	</tr>
<?php
$TCATCABidStr = "";
$select_bid_query_tca 	= "select distinct bid from escalation_10ca_details where sheetid = '$sheetid' and quarter = '$quarter' and esc_rbn = '$esc_rbn'";
$select_bid_sql_tca 	= mysql_query($select_bid_query_tca);
if($select_bid_sql_tca == true)
{
	if(mysql_num_rows($select_bid_sql_tca)>0)
	{
		while($BidListTCA = mysql_fetch_object($select_bid_sql_tca))
		{
			$bidTCA = $BidListTCA->bid;
			$TCABidStr .= $bidTCA."*";
		}
	}
}
$TCABidStr 		= rtrim($TCABidStr,"*");
$expTCABidStr 	= explode("*",$TCABidStr);
$TCABidCnt 		= count($expTCABidStr);
//echo $TCABidStr;
$TCA_Total_Amt = 0;
for($x7=0; $x7<$TCABidCnt; $x7++)
{
$TCAbid = $expTCABidStr[$x7];
$TCAmonth_count = 0;
$TCAbase_index_str1 = "";
						
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

//echo $TCADataStr;

$expTCADataStr 	= explode("@@##@@",$TCADataStr);
$tca_data		= $expTCADataStr[0];
$TCAPrintMonthCount	= $expTCADataStr[1];
//echo $PrintMonthCount;
$TCAexptca_data = explode("*@*",$tca_data);
for($i = 0; $i<count($TCAexptca_data); $i+=14)
{
	$TCAPrintTCAbid 				= $TCAexptca_data[$i+0];
	$TCAPrintpid 					= $TCAexptca_data[$i+1];
	$TCAPrintbase_index_item 		= $TCAexptca_data[$i+2];
	$TCAPrintesc_month 				= $TCAexptca_data[$i+3];
	$TCAPrintbase_index_rate 		= $TCAexptca_data[$i+4];
	$TCAPrintbase_index_code 		= $TCAexptca_data[$i+5];
	$TCAPrintpi_rate 				= $TCAexptca_data[$i+6];
	$TCAPrintpi_code 				= $TCAexptca_data[$i+7];
	$TCAPrintbase_price_rate 		= $TCAexptca_data[$i+8];
	$TCAPrintbase_price_code 		= $TCAexptca_data[$i+9];
	$TCAPrintqty_month_wise 		= $TCAexptca_data[$i+10];
	$TCAPrintdecimal_placed 		= $TCAexptca_data[$i+11];
	$TCAPrintqty_month_wise_mt 		= $TCAexptca_data[$i+12];
	$TCAPrintesc_item_type 			= $TCAexptca_data[$i+13];
	$TCAPrinttca_formula 			= $TCAPrintbase_price_code." x "."Q"." x <br/>(".$TCAPrintpi_code." - ".$TCAPrintbase_index_code.")/".$TCAPrintbase_index_code;
	$TCAPrinttca_formula_with_val 	= $TCAPrintbase_price_rate." x ".$TCAPrintqty_month_wise_mt." x  <br/>(".$TCAPrintpi_rate." - ".$TCAPrintbase_index_rate.")/".$TCAPrintbase_index_rate;
	
	$TCAPrintesc_amount = $TCAPrintbase_price_rate*$TCAPrintqty_month_wise_mt*($TCAPrintpi_rate-$TCAPrintbase_index_rate)/$TCAPrintbase_index_rate;
	$TCAPrintesc_amount = round($TCAPrintesc_amount,2);
	$TCA_Total_Amt = $TCA_Total_Amt+$TCAPrintesc_amount;
	
	$TCAMbStr = GetTCAconsumMbookNo($sheetid,$esc_rbn,$esc_id,$TCAPrintbase_index_code,$TCAPrintesc_month,$TCAPrintesc_item_type);
	if($i==0){?>
	<tr style=" height:35px;">
		<td align="center" valign="middle" nowrap="nowrap" rowspan="<?= $TCAPrintMonthCount; ?>"><?= $TCAPrintbase_index_item; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAPrintesc_month; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAPrintqty_month_wise_mt; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAMbStr; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintbase_index_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintbase_price_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintpi_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrinttca_formula; ?></td>
		<td align="center" valign="middle"><?= $TCAPrinttca_formula_with_val; ?></td>
		<td align="right" valign="middle" nowrap="nowrap"><?= number_format($TCAPrintesc_amount,2,".",","); ?>&nbsp;</td>
	</tr>
<?php } else {?>
	<tr style=" height:35px;">
		<!--<td align="center" valign="middle" nowrap="nowrap"><?= $TCAPrintbase_index_item; ?></td>-->
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAPrintesc_month; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAPrintqty_month_wise_mt; ?></td>
		<td align="center" valign="middle" nowrap="nowrap"><?= $TCAMbStr; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintbase_index_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintbase_price_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrintpi_rate; ?></td>
		<td align="center" valign="middle"><?= $TCAPrinttca_formula; ?></td>
		<td align="center" valign="middle"><?= $TCAPrinttca_formula_with_val; ?></td>
		<td align="right" valign="middle" nowrap="nowrap"><?= number_format($TCAPrintesc_amount,2,".",","); ?>&nbsp;</td>
	</tr>	
<?php
	}
  }
}
  $TCA_Total_Amt = round($TCA_Total_Amt,2);
?>
  	<tr style=" height:35px;" class="labelbold">
		<!--<td align="right" valign="middle" colspan="6"><?php echo "C/o to Abstract MBook No.".$tcaAbsmbook." / Page ".$tcaAbsmbookpage; ?>&nbsp;</td>-->
		<td align="right" valign="middle" colspan="6"><input type="text" name="txt_tca_co" id="txt_tca_co" class="hidtextbox"></td>
		<td colspan="3" align="right" valign="middle">10-CA Escalation Amount&nbsp;&nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>&nbsp;</td>
		<td align="right" valign="middle"><?php $TCA_Total_Amt = round($TCA_Total_Amt); echo number_format($TCA_Total_Amt,2,".",","); ?>&nbsp;</td>
	</tr>
	<tr style='border-style:none;'><td colspan='10' align='center' style='border-style:none;'> page <?= $page; ?></td></tr>
</table>
<?php $tca_mbook_no = $esc_mbook; $tca_mbook_page = $page; ?>
<p style='page-break-after:always;'>&nbsp;</p>
<!--*************************** TCA Ends Here *****************************////-->

<!--*************************** TCC Ends Here *****************************////-->
<? if($moncnt>0){ ?>
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));


function GetAllEscMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}
function GetAllRbnMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}
function GetElecricityRecovery($sheetid,$rbn)
{
	$tot_amt = 0;
	$select_elec_rec_query = "select electricity_cost from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_elec_rec_sql = mysql_query($select_elec_rec_query);
	if($select_elec_rec_sql == true)
	{
		if(mysql_num_rows($select_elec_rec_sql)>0)
		{
			while($EList = mysql_fetch_object($select_elec_rec_sql))
			{
				$amt = $EList->electricity_cost;
				$tot_amt = $tot_amt+$amt;
			}
		}
		else
		{
			$tot_amt = 0;
		}
	}
	else
	{
		$tot_amt = 0;
	}
	return $tot_amt;
}
function GetWaterRecovery($sheetid,$rbn)
{
	$tot_amt = 0;
	$select_water_rec_query = "select water_cost from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_water_rec_sql = mysql_query($select_water_rec_query);
	if($select_water_rec_sql == true)
	{
		if(mysql_num_rows($select_water_rec_sql)>0)
		{
			while($WList = mysql_fetch_object($select_water_rec_sql))
			{
				$amt = $WList->water_cost;
				$tot_amt = $tot_amt+$amt;
			}
		}
		else
		{
			$tot_amt = 0;
		}
	}
	else
	{
		$tot_amt = 0;
	}
	return $tot_amt;
}

//$sheetid	=  $_GET['sheetid'];
//$type		=  $_GET['type'];
//$bid		=  $_GET['bid'];
//$from_date	=  $_GET['fromdate'];
//$to_date	=  $_GET['todate'];
//$fromdate 	=  dt_format($from_date);
//$todate 	=  dt_format($to_date);

//$EscMonthArr 	=	array();
$EscTestArr1		 = array();
$EscTestArr2		 = array();
$MonRowSpanArr		 = array();
$EscMonthRowSpanList = array();
$RbnRowSpanList 	 = array();
$MonRowSpanList 	 = array();

$RbnMonthList 	 	 = array();
$MbookMonthList 	 = array();
$MbPageMonthList 	 = array();
$RbnAmtMonthList 	 = array();

$SameFromToMonData 	 = array();
$DiffFromToMonData 	 = array();

// Extraxt all month from the escalaton period and store it in array
$EscMonthList		=	GetAllEscMonth($fromdate,$todate);
$EscMonthCnt		=	count($EscMonthList);
for($i=0; $i<$EscMonthCnt; $i++)
{
	$escMonth 		= $EscMonthList[$i];
	// Initially set the value of escalation month is to zero for checking which month is exist in "abstract rbn"
	$EscTestArr1[$escMonth] 		= 0;
	// Initially set the value of escalation month is to one for calculate the month column rowspan.
	$EscMonthRowSpanList[$escMonth] = 1;
}


$fomdate_temp	=	strtotime($fromdate);
$from_mon_yr	=	date("M-Y",$fomdate_temp);

$todate_temp	=	strtotime($todate);
$to_mon_yr		=	date("M-Y",$todate_temp);

$diff_month_cnt 	= 0;
$total_rbn_amount 	= 0;
$month_cnt 			= 0;
$PiStr 				= "";
$CntStr 			= "";
$AbsStr 			= "";
$ExistMonthList			= array();
$NonExistMonthList		= array();
$RbnAllFromToMonthList 	= array();
$AbsBookIDList 			= array();

$BidStr = "";
$select_bid_query 	= "select bid from escalation_tcc where sheetid = '$sheetid' and quarter = '$quarter' and esc_rbn = '$esc_rbn'";
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

$select_abs_month_query = 	"SELECT * FROM abstractbook WHERE sheetid = '$sheetid' 
							and ((DATE(fromdate) BETWEEN '$fromdate' AND '$todate') OR (DATE(todate) BETWEEN '$fromdate' AND '$todate'))";
$select_abs_month_sql 	= 	mysql_query($select_abs_month_query);
if($select_abs_month_sql == true)
{
	if(mysql_num_rows($select_abs_month_sql)>0)
	{
		while($AbsList = mysql_fetch_object($select_abs_month_sql))
		{
			$absbookid  		= $AbsList->absbookid;
			$rbn_fromdate 		= $AbsList->fromdate;
			$rbn_todate 		= $AbsList->todate;
			array_push($AbsBookIDList,$absbookid);
			// Change the Fromdate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp1			= strtotime($rbn_fromdate);
			$from_month_year	= date("M-Y",$date_temp1);
			// Change the Todate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp2			= strtotime($rbn_todate);
			$to_month_year		= date("M-Y",$date_temp2);
			// Check Month-Year of Fromdate and Todate is equal or not
			if($from_month_year == $to_month_year)
			{
				// If Fromdate date month and Two date month is same then assign any one one of them to the variable $rbn_month_year and store it in a array
				$rbn_month_year = $from_month_year;
				array_push($RbnAllFromToMonthList,$rbn_month_year);
			}
			else
			{
				// If Fromdate month and Todate month is different then extract all month between these two date in "Mon-yyyy" fromat
				$RbnMonthList	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
				// Using for loop get each month and store it in a array for future purpose
				for($x1=0; $x1<count($RbnMonthList); $x1++)
				{
					$rbn_month_year = $RbnMonthList[$x1];
					array_push($RbnAllFromToMonthList,$rbn_month_year);
				}
			}
			
		}
	}
}


$RbnAllMonCnt 		= count($RbnAllFromToMonthList);
$UniqRbnMonthList 	= array_values(array_unique($RbnAllFromToMonthList)); // remove the mutiple entry of same month and get unique value. ( Note: array_values used to set array key value as array[0], array[1]....)
$UniqRbnMonthCnt 	= count($UniqRbnMonthList);
for($x2=0; $x2<$UniqRbnMonthCnt; $x2++)
{
	$rbn_month 	 	= $UniqRbnMonthList[$x2];
	if(in_array($rbn_month, $EscMonthList))  // Check Whether Month is exist in Escalation Period
	{
		array_push($ExistMonthList,$rbn_month);
		// Set the month rowspan value of each month.
		//$EscMonthRowSpanList[$rbn_month] = $MonthCount[$rbn_month];
	}
	else // Check Whether Month is not exist in Escalation Period.
	{
		array_push($NonExistMonthList,$rbn_month);
	}
}
$ExistMonthCnt 		= count($ExistMonthList);
$NonExistMonthCnt 	= count($NonExistMonthList);
if($NonExistMonthCnt>0)
{
	// Some month which is not exist in the period of escalation so we need to generate rough MBook, Abstract etc.
	$Error_msg = "Need to generate rough MBook, Abstract etc";
}
else
{
	$Error_msg = "";
}
// This part is very very important  - To get all data of rbn, abstarct mbook, page, and price index details etc.
if($ExistMonthCnt>0)
{
	eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

}

//MONTH ROW SPAN Calculation Part
// Count the number of occurrance of each month in following array to fin out the MONTH row span count.
$MonthCount = array_count_values($MonRowSpanList);
for($x5=0; $x5<$EscMonthCnt; $x5++)
{
	$rowspan_rbn_month 	 	= $EscMonthList[$x5];
	if(in_array($rowspan_rbn_month, $MonRowSpanList))
	{
		$EscMonthRowSpanList[$rowspan_rbn_month] = $MonthCount[$rowspan_rbn_month];
	}
}
//RBN ROW SPAN Calculation Part
$RbnCount = array_count_values($RbnRowSpanList);

$overall_month_rowspan = array_sum($EscMonthRowSpanList);
$overall_rbn_rowspan = array_sum($EscMonthRowSpanList);
if($overall_month_rowspan > $overall_rbn_rowspan)
{
	$overall_rowspan = $overall_month_rowspan;
}
else
{
	$overall_rowspan = $overall_rbn_rowspan;
}
// Get Base Index and all Price Index details - Section


$BiStr = ""; $RbnOutStr = ""; $prev_bid = "";

$expBidStr 	= explode("*",$BidStr);
$BidCnt 	= count($expBidStr);
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

//$TCCStr = rtrim($RbnOutStr,"##")."@@##@@".rtrim($piStr,"##")."@@##@@".rtrim($BiStr,"##")."@@##@@".$overall_rowspan."@@##@@".$EscMonthCnt."@@##@@".$total_rbn_amount;

$RbnData 			= rtrim($RbnOutStr2,"##");
$piStr 				= rtrim($piStr,"##");
$BiStr 				= rtrim($BiStr,"##");
$overall_rowspan 	= $overall_rowspan;
$EscMonthCnt 		= $EscMonthCnt;
$total_rbn_amount 	= $total_rbn_amount;

$EscStrData = rtrim($EscStr,"@@*@@");
//echo $EscStrData;

// <!==========================RAB DETAILS PRINTING SECTION STARTS HERE===========================!>
$PrintMonthTextArr = array();


$PrintMonthStr = "";
$PrintMonthTextStr = "";

$month_text = 1;
$prev_abs_mon_yr = "";
$expRbnData = explode("##",$RbnData);
$RbnDataCnt = count($expRbnData);
for($k1=0; $k1<$RbnDataCnt; $k1++)
{
	$RbnDataSingleRow 		= $expRbnData[$k1];
	$expRbnDataSingleRow 	= explode("*",$RbnDataSingleRow);
	$abs_mon_yr 			= $expRbnDataSingleRow[0];
	$abs_rbn 				= $expRbnDataSingleRow[1];
	$abs_mbookno 			= $expRbnDataSingleRow[2];
	$abs_mbpage 			= $expRbnDataSingleRow[3];
	$abs_rbn_amt 			= $expRbnDataSingleRow[4];
	if($abs_rbn_amt == "X" || $abs_rbn_amt == ""){ $abs_rbn_amt = 0; }
	//var abs_rbn_amt_85perc 	= res5[5];
	$abs_sa_amt 			= $expRbnDataSingleRow[6];
	if(abs_sa_amt == "X" || $abs_sa_amt == ""){ $abs_sa_amt = 0; }
	
	$abs_er_amt 			= $expRbnDataSingleRow[7];
	if(abs_er_amt == "X" || $abs_er_amt == ""){ $abs_er_amt = 0; }
	
	$abs_wr_amt 			= $expRbnDataSingleRow[8];
	if(abs_wr_amt == "X" || $abs_wr_amt == ""){ $abs_wr_amt = 0; }
	
	$rbn_row_span 		= $expRbnDataSingleRow[9];
	$mon_row_span 		= $expRbnDataSingleRow[10];
	$abs_upto_date_amt	= $expRbnDataSingleRow[11];
	$abs_dpm_amt 		= $expRbnDataSingleRow[12];
	$total_rab_amt 		= $abs_rbn_amt+$abs_sa_amt;
	$total_rab_amt 		= round($total_rab_amt,2);
	$abs_rbn_amt_85perc = $total_rab_amt*85/100;
	$abs_rbn_amt_85perc	= round($abs_rbn_amt_85perc,2);
	$net_amt 			= $abs_rbn_amt_85perc-$abs_er_amt-$abs_wr_amt;
	$net_amt			= round($net_amt,2);
	//alert(netamt_for_esc);		

	$netamt_for_esc 		= $netamt_for_esc+$net_amt;
	if($abs_rbn == "")
	{
		$abs_rbn = " - ";
	}
	if($abs_mbookno == "")
	{
		$abs_mbookno = " - ";
	}
	if($abs_mbpage == "")
	{
		$abs_mbpage = " - ";
	}
	if($abs_sa_amt>0)
	{
		$abs_sa_amt_paid = $abs_sa_amt;  // ( D ) 
		$abs_sa_amt_recd = 0;    // ( E )
	}
	else if($abs_sa_amt<=0)
	{
		$abs_sa_amt_recd = $abs_sa_amt;   //( E )
		$abs_sa_amt_paid = 0;   // ( D ) 
	}
	else
	{
		$abs_sa_amt_recd = 0;   // ( E )
		$abs_sa_amt_paid = 0;   // ( D ) 
	}
					
	$abs_sa_amt_esc = $abs_sa_amt_paid-$abs_sa_amt_recd;   // ( F ) = (D-E) 
	$abs_sa_amt_esc = round($abs_sa_amt_esc,2);   // Round of to 2 digit of ( F )
//End

// Seperate the secured advance paid and recovered amount based on negative of positive value
	$adv_payment_made = 0;     // ( G ) 
	$adv_payment_recd = 0;     // ( H ) 
	$adv_payment_esc = $adv_payment_made-$adv_payment_recd;     // ( I ) = (G-H) 
	$adv_payment_esc = round($adv_payment_esc,2);   // Round of to 2 digit of ( I )
	
// <!========================== For Printing Month label Text e.g(Month 1 (m1), Month2 (m2) etc...) ===========================!>	
	$PrintMonthTextArr[$k1] = "Month ".$month_text." <br/>(m".$month_text.")";
	$month_text++;
	/*if($prev_abs_mon_yr != $abs_mon_yr)
	{
		$PrintMonthText = "Month ".$month_text." <br/>(m".$month_text.")";
		$PrintMonthTextStr .= $PrintMonthText."*".$mon_row_span."@@";
		$month_text++;
	}*/
	
// <!========================== For Printing Month Name ===========================!>
	if($prev_abs_mon_yr != $abs_mon_yr)
	{
		$PrintMonthStr .= $abs_mon_yr."*".$mon_row_span."@@";
	}
	$prev_abs_mon_yr = $abs_mon_yr;
// <!========================== For Printing RAB Name ===========================!>
	if($abs_rbn != "X")
	{
		$PrintRABStr .= $abs_rbn."*".$rbn_row_span."@@";
	}
// <!========================== For Printing MBOOK Name ===========================!>
	if($abs_rbn != "X")
	{
		$PrintMBStr .= $abs_mbookno."*".$rbn_row_span."@@";
	}
// <!========================== For Printing MBOOK Name ===========================!>
	if($abs_rbn != "X")
	{
		$PrintMBPgStr .= $abs_mbpage."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Upto this month workDone ===========================!>
	if($abs_rbn != "X")
	{
		$PrintUptoDtAmtStr .= $abs_upto_date_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing DPM Amount (Deduct Previous Amount) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintDpmAmtStr .= $abs_dpm_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing SLM Amount (Since Last Amount) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintSlmAmtStr .= $abs_rbn_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Secured Advance (Paid) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintSAPaidStr .= $abs_sa_amt_paid."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Secured Advance (Recovered) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintSARecStr .= $abs_sa_amt_recd."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Secured Advance (For Escalation) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintSAEscStr .= $abs_sa_amt_esc."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Advance (Paid) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintAdvPaidStr .= $adv_payment_made."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Advance (Recovered) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintAdvRecStr .= $adv_payment_recd."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Advance (For Escalation) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintAdvEscStr .= $adv_payment_esc."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Extra Item ===========================!>
	if($abs_rbn != "X")
	{
		$PrintExtItemStr .= ""."*".$rbn_row_span."@@";
	}
// <!========================== For Printing M Value ===========================!>
	if($abs_rbn != "X")
	{
		$PrintMValStr .= $total_rab_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing N Value (85% of Amt) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintNValStr .= $abs_rbn_amt_85perc."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Less cost of materials Value ===========================!>
	if($abs_rbn != "X")
	{
		$PrintLCMatStr .= ""."*".$rbn_row_span."@@";
	}
// <!========================== For Printing All recovery Water, Electricity (L) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintAllRecTitleStr .= ""."*".$rbn_row_span."@@";
	}
// <!========================== For Printing All recovery Water Recovery(L1) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintWRStr .= $abs_wr_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing All recovery Electricity Recovery (L2) ===========================!>
	if($abs_rbn != "X")
	{
		$PrintERStr .= $abs_er_amt."*".$rbn_row_span."@@";
	}
// <!========================== For Printing Net Amount for Each month ===========================!>
	if($abs_rbn != "X")
	{
		$PrintNetAmtStr .= $net_amt."*".$rbn_row_span."@@";
	}
	
}
$rabTemp = 0;
if($rabTemp == 0){
?>
<table width='875' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr class="labelbold" style="height:30px;" id="det_row0">
		<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
		<td align="center" valign="middle" nowrap="nowrap" width="30%">Description </td>
		<td align="center" valign="middle" nowrap="nowrap"> Formula </td>
	<?php
	$mcnt = count($PrintMonthTextArr);
	for($r1=0; $r1<$mcnt; $r1++)
	{
		echo '<td align="center" valign="middle" nowrap="nowrap">'.$PrintMonthTextArr[$r1].'</td>';
	}
	
	//$mcnt = count($PrintMonthTextArr);
	//for($r1=0; $r1<$mcnt; $r1++)
	//{
		//echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$mcnt.'"> Month </td>';
	//}
	/*$PrintMonthTextStr1 = rtrim($PrintMonthTextStr,"@@");
	//echo $PrintMonthTextStr1;
	$expPrintMonthTextStr = explode("@@",$PrintMonthTextStr1);
	for($r1=0; $r1<count($expPrintMonthTextStr); $r1++)
	{
		$Row1Str 			= $expPrintMonthTextStr[$r1];
		$expRow1Str 		= explode("*",$Row1Str);
		$PrintMonthText 		= $expRow1Str[0];
		$PrintMonthTextColspan 	= $expRow1Str[1];
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMonthTextColspan.'">'.$PrintMonthText.'</td>';
	}*/
	?>
	</tr>
	<tr style=" height:30px;" id="det_row1">
		<td align="center" valign="middle" nowrap="nowrap">1</td>
		<td align="left" valign="middle" width="30%">Name of the Month </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
	//echo $PrintMonthStr;
	$PrintMonthStr1 = rtrim($PrintMonthStr,"@@");
	$expPrintMonthStr = explode("@@",$PrintMonthStr1);
	for($r2=0; $r2<count($expPrintMonthStr); $r2++)
	{
		$Row2Str 			= $expPrintMonthStr[$r2];
		$expRow2Str 		= explode("*",$Row2Str);
		$PrintMonth 		= $expRow2Str[0];
		$PrintMonthColspan 	= $expRow2Str[1];
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMonthColspan.'">'.$PrintMonth.'</td>';
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row2">
		<td align="center" valign="middle" nowrap="nowrap">2</td>
		<td align="left" valign="middle" width="30%">RAB NO: </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
	$PrintRBNStr1 = rtrim($PrintRABStr,"@@");
	$expPrintRBNStr = explode("@@",$PrintRBNStr1);
	for($r3=0; $r3<count($expPrintRBNStr); $r3++)
	{
		$Row3Str 			= $expPrintRBNStr[$r3];
		$expRow3Str 		= explode("*",$Row3Str);
		$PrintRbn 			= $expRow3Str[0];
		$PrintRbnColspan 	= $expRow3Str[1];
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintRbnColspan.'">'.$PrintRbn.'</td>';
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row3">
		<td align="center" valign="middle" nowrap="nowrap">3</td>
		<td align="left" valign="middle" width="30%">MBook No: </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
	$PrintMBStr1 = rtrim($PrintMBStr,"@@");
	$expPrintMBStr = explode("@@",$PrintMBStr1);
	for($r4=0; $r4<count($expPrintMBStr); $r4++)
	{
		$Row4Str 			= $expPrintMBStr[$r4];
		$expRow4Str 		= explode("*",$Row4Str);
		$PrintMB 			= $expRow4Str[0];
		$PrintMBColspan 	= $expRow4Str[1];
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMBColspan.'">'.$PrintMB.'</td>';
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row4">
		<td align="center" valign="middle" nowrap="nowrap">4</td>
		<td align="left" valign="middle" width="30%">MBook Page No. </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
	$PrintMBPgStr1 = rtrim($PrintMBPgStr,"@@");
	$expPrintMBPgStr = explode("@@",$PrintMBPgStr1);
	for($r5=0; $r5<count($expPrintMBPgStr); $r5++)
	{
		$Row5Str 			= $expPrintMBPgStr[$r5];
		$expRow5Str 		= explode("*",$Row5Str);
		$PrintMBPg 			= $expRow5Str[0];
		$PrintMBPgColspan 	= $expRow5Str[1];
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMBPgColspan.'">'.$PrintMBPg.'</td>';
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row5">
		<td align="center" valign="middle" nowrap="nowrap">5</td>
		<td align="left" valign="middle" width="30%">gross value of work done upto <label class="pointout">this month.</label></td>
		<td align="center" valign="middle" nowrap="nowrap"> ( A ) </td>
	<?php
	$PrintUptoDtAmtStr1 = rtrim($PrintUptoDtAmtStr,"@@");
	$expPrintUptoDtAmtStr = explode("@@",$PrintUptoDtAmtStr1);
	for($r6=0; $r6<count($expPrintUptoDtAmtStr); $r6++)
	{
		$Row6Str 				= $expPrintUptoDtAmtStr[$r6];
		$expRow6Str 			= explode("*",$Row6Str);
		$PrintUptoDtAmt			= $expRow6Str[0];
		$PrintUptoDtAmtColspan 	= $expRow6Str[1];
		if($PrintUptoDtAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintUptoDtAmtColspan.'">'.number_format($PrintUptoDtAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintUptoDtAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row6">
		<td align="center" valign="middle" nowrap="nowrap">6</td>
		<td align="left" valign="middle" width="30%">gross value of work done upto <label class="pointout">last month</label>. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( B ) </td>
	<?php
	$PrintDpmAmtStr1 = rtrim($PrintDpmAmtStr,"@@");
	$expPrintDpmAmtStr = explode("@@",$PrintDpmAmtStr1);
	for($r7=0; $r7<count($expPrintDpmAmtStr); $r7++)
	{
		$Row7Str 				= $expPrintDpmAmtStr[$r7];
		$expRow7Str 			= explode("*",$Row7Str);
		$PrintDpmAmt			= $expRow7Str[0];
		$PrintDpmAmtColspan 	= $expRow7Str[1];
		if($PrintDpmAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintDpmAmtColspan.'">'.number_format($PrintDpmAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintDpmAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row7">
		<td align="center" valign="middle" nowrap="nowrap">7</td>
		<td align="left" valign="middle" width="30%">Gross value of work done since previous Month RAB. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( C ) = (A)-(B) </td>
	<?php
	$PrintSlmAmtStr1 = rtrim($PrintSlmAmtStr,"@@");
	$expPrintSlmAmtStr = explode("@@",$PrintSlmAmtStr1);
	for($r8=0; $r8<count($expPrintSlmAmtStr); $r8++)
	{
		$Row8Str 				= $expPrintSlmAmtStr[$r8];
		$expRow8Str 			= explode("*",$Row8Str);
		$PrintSlmAmt			= $expRow8Str[0];
		$PrintSlmAmtColspan 	= $expRow8Str[1];
		if($PrintSlmAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintSlmAmtColspan.'">'.number_format($PrintSlmAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSlmAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row8">
		<td align="center" valign="middle" nowrap="nowrap">8</td>
		<td align="left" valign="middle" width="30%">
			Full assessed value of <label class="pointout">secured advance</label> (excluding materials covered under Cluase 10CA) fresh <label class="pointout">paid</label> in this month RAB.
		</td>
		<td align="center" valign="middle" nowrap="nowrap"> ( D ) </td>
	<?php
	$PrintSAPaidStr1 = rtrim($PrintSAPaidStr,"@@");
	$expPrintSAPaidStr = explode("@@",$PrintSAPaidStr1);
	for($r9=0; $r9<count($expPrintSAPaidStr); $r9++)
	{
		$Row9Str 				= $expPrintSAPaidStr[$r9];
		$expRow9Str 			= explode("*",$Row9Str);
		$PrintSAPaidAmt			= $expRow9Str[0];
		$PrintSAPaidAmtColspan 	= $expRow9Str[1];
		if($PrintSAPaidAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintSAPaidAmtColspan.'">'.number_format($PrintSAPaidAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSAPaidAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row9">
		<td align="center" valign="middle" nowrap="nowrap">9</td>
		<td align="left" valign="middle" width="30%">
			Full assessed value of <label class="pointout">secured advance</label> (excluding materials covered under Cluase 10CA)<label class="pointout">recovered</label> in this  month RAB. 
		</td>
		<td align="center" valign="middle" nowrap="nowrap"> ( E ) </td>
	<?php
	$PrintSARecStr1 = rtrim($PrintSARecStr,"@@");
	$expPrintSARecStr = explode("@@",$PrintSARecStr1);
	for($r10=0; $r10<count($expPrintSARecStr); $r10++)
	{
		$Row10Str 				= $expPrintSARecStr[$r10];
		$expRow10Str 			= explode("*",$Row10Str);
		$PrintSARecAmt			= $expRow10Str[0];
		$PrintSARecAmtColspan 	= $expRow10Str[1];
		if($PrintSARecAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintSARecAmtColspan.'">'.number_format($PrintSARecAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSARecAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row10">
		<td align="center" valign="middle" nowrap="nowrap">10</td>
		<td align="left" valign="middle" width="30%">Full assessed value of <label class="pointout">secured advance</label> for which <label class="pointout">escalation payable</label> in this month RAB. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( F ) = (D-E) </td>
	<?php
	$PrintSAEscStr1 = rtrim($PrintSAEscStr,"@@");
	$expPrintSAEscStr = explode("@@",$PrintSAEscStr1);
	for($r11=0; $r11<count($expPrintSAEscStr); $r11++)
	{
		$Row11Str 				= $expPrintSAEscStr[$r11];
		$expRow11Str 			= explode("*",$Row11Str);
		$PrintSAEscAmt			= $expRow11Str[0];
		$PrintSAEscAmtColspan 	= $expRow11Str[1];
		if($PrintSAEscAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintSAEscAmtColspan.'">'.number_format($PrintSAEscAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSAEscAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row11">
		<td align="center" valign="middle" nowrap="nowrap">11</td>
		<td align="left" valign="middle" width="30%">Advance payment made during this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( G ) </td>
	<?php
	$PrintAdvPaidStr1 = rtrim($PrintAdvPaidStr,"@@");
	$expPrintAdvPaidStr = explode("@@",$PrintAdvPaidStr1);
	for($r12=0; $r12<count($expPrintAdvPaidStr); $r12++)
	{
		$Row12Str 				= $expPrintAdvPaidStr[$r12];
		$expRow12Str 			= explode("*",$Row12Str);
		$PrintAdvPaidAmt		= $expRow12Str[0];
		$PrintAdvPaidAmtColspan = $expRow12Str[1];
		if($PrintAdvPaidAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvPaidAmtColspan.'">'.number_format($PrintAdvPaidAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvPaidAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row12">
		<td align="center" valign="middle" nowrap="nowrap">12</td>
		<td align="left" valign="middle" width="30%">Advance payment recovered during this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( H ) </td>
	<?php
	$PrintAdvRecStr1 = rtrim($PrintAdvRecStr,"@@");
	$expPrintAdvRecStr = explode("@@",$PrintAdvRecStr1);
	for($r13=0; $r13<count($expPrintAdvRecStr); $r13++)
	{
		$Row13Str 				= $expPrintAdvRecStr[$r13];
		$expRow13Str 			= explode("*",$Row13Str);
		$PrintAdvRecAmt			= $expRow13Str[0];
		$PrintAdvRecAmtColspan 	= $expRow13Str[1];
		if($PrintAdvRecAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvRecAmtColspan.'">'.number_format($PrintAdvRecAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvRecAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row13">
		<td align="center" valign="middle" nowrap="nowrap">13</td>
		<td align="left" valign="middle" width="30%">Advance payment for which escalation is payable in this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( I ) = (G-H) </td>
	<?php
	$PrintAdvEscStr1 = rtrim($PrintAdvEscStr,"@@");
	$expPrintAdvEscStr = explode("@@",$PrintAdvEscStr1);
	for($r14=0; $r14<count($expPrintAdvEscStr); $r14++)
	{
		$Row14Str 				= $expPrintAdvEscStr[$r14];
		$expRow14Str 			= explode("*",$Row14Str);
		$PrintAdvEscAmt			= $expRow14Str[0];
		$PrintAdvEscAmtColspan 	= $expRow14Str[1];
		if($PrintAdvEscAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvEscAmtColspan.'">'.number_format($PrintAdvEscAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvEscAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style='border-style:none;'><td colspan='<?= ($mcnt+3); ?>' align='center' style='border-style:none;'> page <?= $page; ?></td></tr>
</table>
<p style='page-break-after:always;'>&nbsp;</p>
<?php echo $title; $page++; ?>
<?php echo $table;  ?>
<table width='875' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr class="labelbold" style="height:30px;" id="det_row0">
		<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
		<td align="center" valign="middle" nowrap="nowrap" width="30%">Description </td>
		<td align="center" valign="middle" nowrap="nowrap"> Formula </td>
		<?php
		$mcnt = count($PrintMonthTextArr);
		for($r1=0; $r1<$mcnt; $r1++)
		{
			echo '<td align="center" valign="middle" nowrap="nowrap">'.$PrintMonthTextArr[$r1].'</td>';
		}
		?>
	</tr>
	<tr style=" height:30px;" id="det_row14">
		<td align="center" valign="middle" nowrap="nowrap">14</td>
		<td align="left" valign="middle" width="30%">Extra items/deviated quantities paid as per Clause 12 based on prevailing market rates in this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( J ) </td>
	<?php
	$PrintExtItemStr1 = rtrim($PrintExtItemStr,"@@");
	$expPrintExtItemStr = explode("@@",$PrintExtItemStr1);
	for($r15=0; $r15<count($expPrintExtItemStr); $r15++)
	{

		$Row15Str 				= $expPrintExtItemStr[$r15];
		$expRow15Str 			= explode("*",$Row15Str);
		$PrintExtItemAmt		= $expRow15Str[0];
		$PrintExtItemAmtColspan = $expRow15Str[1];
		if($PrintExtItemAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintExtItemAmtColspan.'">'.number_format($PrintExtItemAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintExtItemAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row15">
		<td align="center" valign="middle" nowrap="nowrap">15</td>
		<td align="left" valign="middle" width="30%">M = (C+F+I-J) </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( M ) </td>
	<?php
	$PrintMValStr1 = rtrim($PrintMValStr,"@@");
	$expPrintMValStr = explode("@@",$PrintMValStr1);
	for($r16=0; $r16<count($expPrintMValStr); $r16++)
	{
		$Row16Str 			= $expPrintMValStr[$r16];
		$expRow16Str 		= explode("*",$Row16Str);
		$PrintMValAmt		= $expRow16Str[0];
		$PrintMValColspan 	= $expRow16Str[1];
		if($PrintMValAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintMValColspan.'">'.number_format($PrintMValAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMValColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row16">
		<td align="center" valign="middle" nowrap="nowrap">16</td>
		<td align="left" valign="middle" width="30%">N = 0.85*M </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( N ) </td>
	<?php
	$PrintNValStr1 = rtrim($PrintNValStr,"@@");
	$expPrintNValStr = explode("@@",$PrintNValStr1);
	for($r17=0; $r17<count($expPrintNValStr); $r17++)
	{
		$Row17Str 			= $expPrintNValStr[$r17];
		$expRow17Str 		= explode("*",$Row17Str);
		$PrintNValAmt		= $expRow17Str[0];
		$PrintNValColspan 	= $expRow17Str[1];
		if($PrintNValAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintNValColspan.'">'.number_format($PrintNValAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintNValColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row17">
		<td align="center" valign="middle" nowrap="nowrap">17</td>
		<td align="left" valign="middle" width="30%">Less cost of materials  supplied by the department as per Clause 10 and recovered during the month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( K ) </td>
	<?php
	$PrintLCMatStr1 = rtrim($PrintLCMatStr,"@@");
	$expPrintLCMatStr = explode("@@",$PrintLCMatStr1);
	for($r18=0; $r18<count($expPrintLCMatStr); $r18++)
	{
		$Row18Str 			= $expPrintLCMatStr[$r18];
		$expRow18Str 		= explode("*",$Row18Str);
		$PrintLCMatAmt		= $expRow18Str[0];
		$PrintLCMatColspan 	= $expRow18Str[1];
		if($PrintLCMatAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintLCMatColspan.'">'.number_format($PrintLCMatAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintLCMatColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row18">
		<td align="center" valign="middle" nowrap="nowrap">18</td>
		<td align="left" valign="middle" width="30%">Less cost if servuces rebdered at fixed charges as per Clause 34 and recovered during this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( L ) </td>
	<?php
	$PrintAllRecTitleStr1 = rtrim($PrintAllRecTitleStr,"@@");
	$expPrintAllRecTitleStr = explode("@@",$PrintAllRecTitleStr1);
	for($r19=0; $r19<count($expPrintAllRecTitleStr); $r19++)
	{
		$Row19Str 					= $expPrintAllRecTitleStr[$r19];
		$expRow19Str 				= explode("*",$Row19Str);
		$PrintAllRecTitle			= $expRow19Str[0];
		$PrintAllRecTitleColspan 	= $expRow19Str[1];
		if($PrintAllRecTitle != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintAllRecTitleColspan.'">'.number_format($PrintAllRecTitle,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAllRecTitleColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row19">
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
		<td align="left" valign="middle" width="30%">1) Water Charges </td>
		<td align="center" valign="middle" nowrap="nowrap"> ( L1 ) </td>
	<?php
	$PrintWRStr1 = rtrim($PrintWRStr,"@@");
	$expPrintWRStr = explode("@@",$PrintWRStr1);
	for($r20=0; $r20<count($expPrintWRStr); $r20++)
	{
		$Row20Str 			= $expPrintWRStr[$r20];
		$expRow20Str 		= explode("*",$Row20Str);
		$PrintWRAmt			= $expRow20Str[0];
		$PrintWRAmtColspan 	= $expRow20Str[1];
		if($PrintWRAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintWRAmtColspan.'">'.number_format($PrintWRAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintWRAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row20">
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
		<td align="left" valign="middle" width="30%">2) Electricity charges</td>
		<td align="center" valign="middle" nowrap="nowrap"> ( L2 ) </td>
	<?php
	$PrintERStr1 = rtrim($PrintERStr,"@@");
	$expPrintERStr = explode("@@",$PrintERStr1);
	for($r21=0; $r21<count($expPrintERStr); $r21++)
	{
		$Row21Str 			= $expPrintERStr[$r21];
		$expRow21Str 		= explode("*",$Row21Str);
		$PrintERAmt			= $expRow21Str[0];
		$PrintERAmtColspan 	= $expRow21Str[1];
		if($PrintERAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintERAmtColspan.'">'.number_format($PrintERAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintERAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row21">
		<td align="center" valign="middle" nowrap="nowrap">19</td>
		<td align="left" valign="middle" width="30%">Cost of work for which escalation is applicable for this month. </td>
		<td align="center" valign="middle" nowrap="nowrap"> W = N - (K+L1+L2) </td>
	<?php
	
	$PrintNetAmtStr1 = rtrim($PrintNetAmtStr,"@@");
	$expPrintNetAmtStr = explode("@@",$PrintNetAmtStr1);
	for($r22=0; $r22<count($expPrintNetAmtStr); $r22++)
	{
		$Row22Str 				= $expPrintNetAmtStr[$r22];
		$expRow22Str 			= explode("*",$Row22Str);
		$PrintNetAmt			= $expRow22Str[0];
		$PrintNetAmtColspan 	= $expRow22Str[1];
		if($PrintNetAmt != 0){
			echo '<td align="right" valign="middle" nowrap="nowrap" colspan="'.$PrintNetAmtColspan.'">'.number_format($PrintNetAmt,2,".",",").'</td>';
		}else{
			echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintNetAmtColspan.'">-</td>';
		}
	}
	?>
	</tr>
	<tr style=" height:30px;" id="det_row22">
		<td align="center" valign="middle" nowrap="nowrap">20</td>
		<td align="left" valign="middle" width="30%">Cost of work for which escalation is applicable for this quarter. </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$mcnt.'">'.$netamt_for_esc.'</td>';
	?>
	</tr>
<?php 
} 
$rabTemp = 1;
// <!==========================**************RAB DETAILS PRINTING SECTION ENDS HERE*************===========================!>

// <!==========================    ESCALATION DETAILS PRINTING SECTION STARTS HERE   ===========================!>
?>
<tr style='border-style:none;'><td colspan='<?= ($mcnt+3); ?>' align='center' style='border-style:none;'> page <?= $page; ?></td></tr>
</table>
<br/>

<p style='page-break-after:always;'>&nbsp;</p>
<?php echo $title; $page++; ?>
<?php echo $table;  ?>
<table width='875' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr class="labelbold" style="height:35px;">
		<td align="center" valign="middle" nowrap="nowrap">Desc.</td>
		<td align="center" valign="middle" nowrap="nowrap">Month</td>
		<td align="center" valign="middle">Total RAB<br/> Value (W)</td>
		<td align="center" valign="middle">Base <br/>Index</td>
		<td align="center" valign="middle">Esc <br/>Breakup</td>
		<td align="center" valign="middle">Price <br/>Index</td>
		<td align="center" valign="middle">Avg Price <br/>Index</td>
		<td align="center" valign="middle">Formula</td>
		<td align="center" valign="middle">Formula with Values</td>
		<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
	</tr>
<?php

$expEscStrData = explode("@@*@@",$EscStrData);
$EscDataCnt = count($expEscStrData);
$TCC_Total_Amt = 0;
for($x8=0; $x8<$EscDataCnt; $x8++)
{
	$EscStr1 = $expEscStrData[$x8];
	$expEscStr1 = explode("@#@",$EscStr1);
	
	$EscBIStr = $expEscStr1[0];
	$EscPIStr = $expEscStr1[1];
//  Base Index Data Details
	$expEscBIStr 		= explode("*",$EscBIStr);
	$bi_month 			= $expEscBIStr[0];
	$bid 				= $expEscBIStr[1];
	$base_index_item 	= $expEscBIStr[2];
	$base_index_code 	= $expEscBIStr[3];
	$base_index_rate 	= $expEscBIStr[4];
	$base_breakup_code 	= $expEscBIStr[5];
	$base_breakup_perc 	= $expEscBIStr[6];
	$avg_pi_code 		= $expEscBIStr[7];
	$avg_pi_rate 		= $expEscBIStr[8];
	$month_row_span 	= $expEscBIStr[9];
	
	//first_ltr_bi_item 		= base_index_item.charAt(0);
	//first_ltr_1 			= first_ltr_bi_item.toLowerCase()
	$tcc_formula 			= "W x <br/>(".$base_breakup_code."/100) x<br/> (".$avg_pi_code."-".$base_index_code.")/".$base_index_code;
	$tcc_formula_with_val 	= $netamt_for_esc." x <br/>(".$base_breakup_perc."/100) x<br/> (".$avg_pi_rate."-".$base_index_rate.")/".$base_index_rate;
	// Below condition is for Division by zero warning error 
	if($base_index_rate == 0)
	{ 
		$tcc_amt = 0; 
	}
	else
	{
		$tcc_amt 				= $netamt_for_esc * ($base_breakup_perc/100) * ($avg_pi_rate-$base_index_rate)/$base_index_rate;
	}
	$tcc_amt 			= round($tcc_amt,2);
	$TCC_Total_Amt 		= $TCC_Total_Amt+$tcc_amt;
	
//  Price Index Data Details
	$expEscPIStr 		= explode("##",$EscPIStr);
	for($x9=0; $x9<count($expEscPIStr); $x9++)
	{
		$EscPIRow = $expEscPIStr[$x9];
		
		$expEscPIRow = explode("*",$EscPIRow);
		$pi_month 			= $expEscPIRow[0];
		$base_index_item 	= $expEscPIRow[1];
		$base_index_code 	= $expEscPIRow[2];
		$pi_rate 			= $expEscPIRow[3];
		$pid 				= $expEscPIRow[4];
		$pi_month_row_span 	= $expEscPIRow[5];
		if($x9 == 0)
		{
		?>
		<tr style="height:35px;">
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle" nowrap="nowrap"><?php echo $base_index_item; ?></td>
			<td align="center" valign="middle" nowrap="nowrap"><?php echo $pi_month; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $netamt_for_esc; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $base_index_rate."<br/>( ".$base_index_code." )"; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $base_breakup_perc."<br/>( ".$base_breakup_code." )"; ?></td>
			<td align="center" valign="middle"><?php echo $pi_rate; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $avg_pi_rate."<br/>( ".$avg_pi_code." )"; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $tcc_formula; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle"><?php echo $tcc_formula_with_val; ?></td>
			<td align="center" rowspan="<?php echo $EscMonthCnt; ?>" valign="middle" nowrap="nowrap"><?php echo $tcc_amt; ?></td>
		</tr>
		<?php
		}
		else
		{
		?>
		<tr style="height:35px;">
			<td align="center" valign="middle" nowrap="nowrap"><?php echo $pi_month; ?></td>
			<td align="center" valign="middle"><?php echo $pi_rate; ?></td>
		</tr>
		<?php
		}
	}
}
?>
		<tr style="height:35px;" class="labelbold">
			<!--<td align="right" valign="middle" colspan="6"><?php echo "C/o to Abstract MBook No.".$tccAbsmbook." / Page ".$tccAbsmbookpage; ?>&nbsp;</td>-->
			<td align="right" valign="middle" colspan="6"><input type="text" name="txt_tcc_co" id="txt_tcc_co" class="hidtextbox"></td>
			<td align="right" valign="middle" colspan="3">10-CC Escalation amount for this Quarter&nbsp;&nbsp; &nbsp;<i class='fa fa-inr' style='font-weight:normal;'></i>&nbsp;&nbsp;&nbsp;</td>
			<td align="center" valign="middle"><?php $TCC_Total_Amt = round($TCC_Total_Amt); echo number_format($TCC_Total_Amt,2,".",","); ?></td>
		</tr>
</table>
<?php
	$total_qtr_escalation_amount = round(($TCA_Total_Amt+$TCC_Total_Amt),2);
	$tcc_mbook_no = $esc_mbook; 
	$tcc_mbook_page = $page; 
?>
<table width='675' cellpadding='3' cellspacing='3' align='center' class='label table1 labelprint' bgcolor="#FFFFFF" id="table1">
	<tr><td colspan="3" align="center">Escalation for Quarter - <?php echo $quarter; ?></td></tr>
	<tr>
		<td>10-CA Escalation amount for Quarter - <?php echo $quarter; ?></td>
		<td>B/f MB-<?php echo $tca_mbook_no; ?>/Pg-<?php echo $tca_mbook_page; ?></td>
		<td align="right"><?php echo number_format($TCA_Total_Amt,2,".",","); ?>&nbsp;</td>
	</tr>
	<tr>
		<td>10-CC Escalation amount for Quarter - <?php echo $quarter; ?></td>
		<td>B/f MB-<?php echo $tcc_mbook_no; ?>/Pg-<?php echo $tcc_mbook_page; ?></td>
		<td align="right"><?php echo number_format($TCC_Total_Amt,2,".",","); ?>&nbsp;</td>
	</tr>
	<tr>
		<td>Total Escalation amount for Quarter - <?php echo $quarter; ?></td>
		<td>C/o to MB-<?php echo $tccAbsmbook; ?>/Pg-<?php echo $tccAbsmbookpage; ?></td>
		<td align="right"><?php echo number_format($total_qtr_escalation_amount,2,".",","); ?>&nbsp;</td>
	</tr>
		<tr style='border-style:none;'><td colspan='3' align='center' style='border-style:none;'> <br/>page <?= $page; ?></td></tr>
</table>
<?php 
	$total_esc_amt = round(($TCC_Total_Amt+$TCA_Total_Amt),2);
	/*if(($total_esc_amt != "")&&($total_esc_amt != 0))
	{
		$update_tcc_query = "update escalation set tcc_amt = '$TCC_Total_Amt', tcc_mbook = '$esc_mbook', tcc_mbpage = '$page', 
							tca_amt = '$TCA_Total_Amt', tca_mbook = '$tca_mbook_no', tca_mbpage = '$tca_mbook_page', esc_total_amt = '$total_esc_amt', 
							modifieddate = NOW() where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and flag = 0";
							//echo $update_tcc_query;
		$update_tcc_sql = mysql_query($update_tcc_query);
	}*/
} 
?>
<!--*************************** TCC Ends Here *****************************////-->
<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
	<div class="buttonsection">
		<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
	</div>
	<!--<div class="buttonsection">
		<input type="submit" name="submit" id="submit" value=" View "/>
	</div>-->
</div>
</form>
</body>
</html>