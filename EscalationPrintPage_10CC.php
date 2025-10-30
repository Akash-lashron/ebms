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
		url = "EscalationPrint_10CC.php";
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
			<tr style="border:none;"><td align="center" style="border:none;"><br/><br/>Escalation for 10CC ('.$fir_month.' to '.$las_month.')&nbsp;&nbsp;<br/>&nbsp;</td></tr>
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
$select_bid_query 	= "select bid from escalation_tcc where sheetid = '$sheetid'";
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
	//$ret = "Correct no need";
	$final_from 	= $ExistMonthList[0];
	$final_to 		= $ExistMonthList[$ExistMonthCnt-1];
	
	$final_from_temp 	= 	new DateTime($final_from);
	$final_to_temp 		= 	new DateTime($final_to);
	
	$final_from_date 	=	date_format($final_from_temp,'Y-m-d');
	$final_to_date 		=	date_format($final_to_temp,'Y-m-t');
		
	$select_rbndata_query = "select abstractbook.absbookid, abstractbook.sheetid, abstractbook.rbn, abstractbook.fromdate, abstractbook.todate, 
							abstractbook.mbookno,  abstractbook.mbookpage, abstractbook.slm_total_amount, abstractbook.secured_adv_amt,
							abstractbook.upto_date_total_amount, abstractbook.dpm_total_amount
							from abstractbook where 
							abstractbook.sheetid = '$sheetid' 
							and abstractbook.fromdate >= '$final_from_date' and abstractbook.todate <= '$final_to_date'";
	$select_rbndata_sql = mysql_query($select_rbndata_query);
	if($select_rbndata_sql == true)
	{
		if(mysql_num_rows($select_rbndata_sql)>0)
		{
			while($RList = mysql_fetch_object($select_rbndata_sql))
			{
				$absbookid  			= $RList->absbookid;
				$rbn  					= $RList->rbn;
				$fromdate 				= $RList->fromdate;
				$todate 				= $RList->todate;
				$mbookno 				= $RList->mbookno;
				$mbookpage 				= $RList->mbookpage;
				$secured_adv_amt 		= $RList->secured_adv_amt;
				$rbn_amount 			= $RList->slm_total_amount;
				$upto_date_total_amount = $RList->upto_date_total_amount;
				$dpm_total_amount 		= $RList->dpm_total_amount;
				$rbn_amonut_85_perc 	= round($rbn_amount*85/100,2);
				$total_rbn_amount 		= $total_rbn_amount + $rbn_amonut_85_perc;
				if($upto_date_total_amount == 0)
				{
					$upto_date_total_amount = "Z";
				}
				if($dpm_total_amount == 0)
				{
					$dpm_total_amount = "Z";
				}
				if($secured_adv_amt == 0)
				{
					$secured_adv_amt = "Z";
				}
				if($rbn_amount == 0)
				{
					$rbn_amount = "Z";
				}
				
				$elec_rec 	= 	GetElecricityRecovery($sheetid,$rbn);
				//$ret .= $rbn."<br/>";
				if($elec_rec == 0)
				{
					$elec_rec = "Z";
				}
				$water_rec 	= GetWaterRecovery($sheetid, $rbn);
				if($water_rec == 0)
				{
					$water_rec = "Z";
				}
				//$ret .= $water_rec."<br/>";
				$date_temp3			= strtotime($fromdate);
				$from_mon_yr		= date("M-Y",$date_temp3);
			
				$date_temp4			= strtotime($todate);
				$to_mth_yr			= date("M-Y",$date_temp4);
				if($from_mon_yr == $to_mth_yr)
				{
					$mon_yr = $from_mon_yr;
					// Change the below array value from 0 to 1, to find out the which month price index is calculated.
					$EscTestArr1[$mon_yr] = 1;
					//$MonRowSpanArr[$rbn] = 1 ///10 o clock comment
					$MonRowSpanArr[$mon_yr] = 1;
					// Get base index and price index for this month
					$RbnRowSpan = 1;
					// Following Two arrays are for calculating Rbn Rows Span and Month Rows Span Calculation Respectively
					array_push($RbnRowSpanList,$rbn);
					array_push($MonRowSpanList,$mon_yr);
					// Following arrays are to get rbn, mbookno, page, amount and 85% amt etc...
					$RbnMonthList[$mon_yr] 		= $rbn;
					$MbookMonthList[$mon_yr] 	= $mbookno;
					$MbPageMonthList[$mon_yr] 	= $mbookpage;
					$RbnAmtMonthList[$mon_yr] 	= $rbn_amount;
					
					array_push($SameFromToMonData,$mon_yr);
					array_push($SameFromToMonData,$rbn);
					array_push($SameFromToMonData,$mbookno);
					array_push($SameFromToMonData,$mbookpage);
					array_push($SameFromToMonData,$rbn_amount);
					array_push($SameFromToMonData,$secured_adv_amt);
					array_push($SameFromToMonData,$elec_rec);
					array_push($SameFromToMonData,$water_rec);
					array_push($SameFromToMonData,$upto_date_total_amount);
					array_push($SameFromToMonData,$dpm_total_amount);
					//$RbnAmtMonthList[$mon_yr] 	= $rbn;
				}
				else
				{
					//$RbnMonYrList	=	GetAllRbnMonth($from_mon_yr,$to_mth_yr); ///10 o clock comment
					$RbnMonYrList	=	GetAllRbnMonth($fromdate,$todate);
					$RbnMonYrCnt	=	count($RbnMonYrList);
					for($x3=0; $x3<$RbnMonYrCnt; $x3++)
					{
						$mon_yr = $RbnMonYrList[$x3];
						// Change the below array value from 0 to 1, to find out the which month price index is calculated.
						$EscTestArr1[$mon_yr] = 1;
						if($mon_yr == $from_mon_yr)
						{
							$MonRowSpanArr[$mon_yr] = $RbnMonYrCnt;
							array_push($SameFromToMonData,$mon_yr);
							array_push($SameFromToMonData,$rbn);
							array_push($SameFromToMonData,$mbookno);
							array_push($SameFromToMonData,$mbookpage);
							array_push($SameFromToMonData,$rbn_amount);
							array_push($SameFromToMonData,$secured_adv_amt);
							array_push($SameFromToMonData,$elec_rec);
							array_push($SameFromToMonData,$water_rec);
							array_push($SameFromToMonData,$upto_date_total_amount);
							array_push($SameFromToMonData,$dpm_total_amount);
						}
						else
						{
							$MonRowSpanArr[$mon_yr] = 0;
							array_push($SameFromToMonData,$mon_yr);
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
							array_push($SameFromToMonData,"X");
						}
						// Following Two arrays are for calculating Rbn Rows Span and Month Rows Span Calculation Respectively
						array_push($RbnRowSpanList,$rbn);
						array_push($MonRowSpanList,$mon_yr);
						// Following arrays are to get rbn, mbookno, page, amount and 85% amt etc...
						$RbnMonthList[$mon_yr] 		= $rbn;
						$MbookMonthList[$mon_yr] 	= $mbookno;
						$MbPageMonthList[$mon_yr] 	= $mbookpage;
						$RbnAmtMonthList[$mon_yr] 	= $rbn_amount;
						
						//$RbnAmtMonthList[$mon_yr] 	= $rbn;
						//Get base index and price index for each month
					}
					$RbnRowSpan = $RbnMonYrCnt;
					//$MonRowSpanArr[$rbn] = $RbnMonYrCnt."*".$from_mon_yr."*".$to_mth_yr; ///10 o clock comment
				}
				$RbnRowSpanStr .= $rbn."*".$RbnRowSpan."@@";
			}
		}
	}
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
for($x7=0; $x7<$BidCnt; $x7++)
{
	$RbnOutStr1 = "";
	$bid = $expBidStr[$x7];
	for($x4=0; $x4<$EscMonthCnt; $x4++)
	{
		$esc_Month 		= $EscMonthList[$x4];
		$select_tcc_query 	= 	"select base_index.bid, base_index.base_index_item, base_index.base_index_code, base_index.base_index_rate,
								base_index.base_breakup_code, base_index.base_breakup_perc, price_index.pi_from_date, price_index.pi_to_date, 
								price_index.avg_pi_code, price_index.avg_pi_rate,
								price_index_detail.pi_month, price_index_detail.pi_rate, price_index.pid
								from base_index
								INNER JOIN price_index ON (price_index.bid = base_index.bid)
								INNER JOIN price_index_detail ON (price_index_detail.pid = price_index.pid)
								WHERE base_index.active=1 AND price_index_detail.pid = price_index.pid
								AND price_index.sheetid = '$sheetid' AND base_index.sheetid = '$sheetid' 
								AND base_index.bid = '$bid' AND price_index.bid = '$bid'
								AND price_index_detail.pi_month = '$esc_Month'";
		$select_tcc_sql 	= 	mysql_query($select_tcc_query);
		if($select_tcc_sql == true)
		{
			$TCCList = mysql_fetch_object($select_tcc_sql);
			$base_index_item 	= $TCCList->base_index_item;
			$base_index_code 	= $TCCList->base_index_code;
			$base_index_rate 	= $TCCList->base_index_rate;
			$base_breakup_code 	= $TCCList->base_breakup_code;
			$base_breakup_perc 	= $TCCList->base_breakup_perc;
			$avg_pi_code 	 	= $TCCList->avg_pi_code;
			$avg_pi_rate 	 	= $TCCList->avg_pi_rate;
			$pi_rate 	 		= $TCCList->pi_rate;
			$pi_month 		 	= $TCCList->pi_month;
			$bid 		 	 	= $TCCList->bid;
			$pid 		 	 	= $TCCList->pid;
			$month_row_span 	= $EscMonthRowSpanList[$pi_month];
			if(($bid != "") && ($pid != "") && ($avg_pi_rate != ""))
			{
				$text = str_replace(' ', '*', $BiStr);
	
				//if(trim($text) == "")
				if($prev_bid != $bid)
				{
					$BiStr .= $pi_month."*".$bid."*".$base_index_item."*".$base_index_code."*".$base_index_rate."*".$base_breakup_code."*".$base_breakup_perc."*".$avg_pi_code."*".$avg_pi_rate."*".$month_row_span."##";
				}
			}
			if(in_array($pi_month, $SameFromToMonData))
			{
				$stre .= $pi_month."@";
				for($x6=0; $x6<count($SameFromToMonData); $x6+=10)
				{
					$abs_mon_yr 	= $SameFromToMonData[$x6+0];
					$abs_rbn 		= $SameFromToMonData[$x6+1];
					$abs_mbookno 	= $SameFromToMonData[$x6+2];
					$abs_mbpage 	= $SameFromToMonData[$x6+3];
					$abs_rbn_amt 	= $SameFromToMonData[$x6+4];
					$abs_sa_amt 	= $SameFromToMonData[$x6+5];
					$abs_er_amt 	= $SameFromToMonData[$x6+6];
					$abs_wr_amt 	= $SameFromToMonData[$x6+7];
					$abs_upto_date_amt 	= $SameFromToMonData[$x6+8];
					$abs_dpm_amt 	= $SameFromToMonData[$x6+9];
					if($abs_er_amt == "Z")
					{
						$abs_er_amt = 0;
					}
					if($abs_wr_amt == "Z")
					{
						$abs_wr_amt = 0;
					}
					if($abs_upto_date_amt == "Z")
					{
						$abs_upto_date_amt = 0;
					}
					if($abs_dpm_amt == "Z")
					{
						$abs_dpm_amt = 0;
					}
					if($abs_sa_amt == "Z")
					{
						$abs_sa_amt = 0;
					}
					if($abs_rbn_amt == "Z")
					{
						$abs_rbn_amt = 0;
					}
					
					$abs_rbn_amt_85perc = round(($abs_rbn_amt*85/100),2);
					if($RbnCount[$abs_rbn] != "")
					{
						$rbn_row_span 	= $RbnCount[$abs_rbn];
					}
					else
					{
						$rbn_row_span = 1;
					}
					if($abs_mon_yr == $pi_month)
					{
						$RbnOutStr .= $abs_mon_yr."*".$abs_rbn."*".$abs_mbookno."*".$abs_mbpage."*".$abs_rbn_amt."*".$abs_rbn_amt_85perc."*".$abs_sa_amt."*".$abs_er_amt."*".$abs_wr_amt."*".$rbn_row_span."*".$month_row_span."*".$abs_upto_date_amt."*".$abs_dpm_amt."##";
						$os++;
						$RbnOutStr1 .= $abs_mon_yr."*".$abs_rbn."*".$abs_mbookno."*".$abs_mbpage."*".$abs_rbn_amt."*".$abs_rbn_amt_85perc."*".$abs_sa_amt."*".$abs_er_amt."*".$abs_wr_amt."*".$rbn_row_span."*".$month_row_span."*".$abs_upto_date_amt."*".$abs_dpm_amt."##";
					}
				}
			}
			else
			{
				$RbnOutStr .= $pi_month."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*"."1"."*".$month_row_span."*".""."*".""."##";
				$RbnOutStr1 .= $pi_month."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*".""."*"."1"."*".$month_row_span."*".""."*".""."##";
			}
		$piStr .= $pi_month."*".$base_index_item."*".$base_index_code."*".$pi_rate."*".$pid."*".$month_row_span."##";
		}
		$prev_bid = $bid; // It must be placed in inside for loop for getting  //-> $BiStr <-//
	}
	$RbnOutStr2 = $RbnOutStr1;
	$EscStr .= rtrim($BiStr,"##")."@#@".rtrim($piStr,"##")."@@*@@";
	$BiStr = ""; $piStr = "";
	//$RbnOutStr1 = "";
}
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
	?>
	</tr>
	<tr style=" height:30px;" id="det_row1">
		<td align="center" valign="middle" nowrap="nowrap">1</td>
		<td align="left" valign="middle" width="30%">Name of the Month </td>
		<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
	<?php
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintUptoDtAmtColspan.'">'.$PrintUptoDtAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintDpmAmtColspan.'">'.$PrintDpmAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSlmAmtColspan.'">'.$PrintSlmAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSAPaidAmtColspan.'">'.$PrintSAPaidAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSARecAmtColspan.'">'.$PrintSARecAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintSAEscAmtColspan.'">'.$PrintSAEscAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvPaidAmtColspan.'">'.$PrintAdvPaidAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvRecAmtColspan.'">'.$PrintAdvRecAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAdvEscAmtColspan.'">'.$PrintAdvEscAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintExtItemAmtColspan.'">'.$PrintExtItemAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintMValColspan.'">'.$PrintMValAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintNValColspan.'">'.$PrintNValAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintLCMatColspan.'">'.$PrintLCMatAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintAllRecTitleColspan.'">'.$PrintAllRecTitle.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintWRAmtColspan.'">'.$PrintWRAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintERAmtColspan.'">'.$PrintERAmt.'</td>';
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
		echo '<td align="center" valign="middle" nowrap="nowrap" colspan="'.$PrintNetAmtColspan.'">'.$PrintNetAmt.'</td>';
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
</table>
<br/>
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
$overall_esc_amt = 0;
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
	$tcc_amt 				= $netamt_for_esc * ($base_breakup_perc/100) * ($avg_pi_rate-$base_index_rate)/$base_index_rate;
	$tcc_amt 				= round($tcc_amt,2);
	$overall_esc_amt 		= $overall_esc_amt+$tcc_amt;
	
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
		<tr style="height:35px;">
			<td align="right" valign="middle" colspan="9">Escalation amount for this Quarter&nbsp;&nbsp; &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>&nbsp;&nbsp;&nbsp;</td>
			<td align="center" valign="middle"><?php echo $overall_esc_amt; ?></td>
		</tr>
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