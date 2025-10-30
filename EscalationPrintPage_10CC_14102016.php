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
$fromdate 		= 	dt_format($esc_from_date);
$todate 		= 	dt_format($esc_to_date);
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
$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Escalation for 10CC&nbsp;&nbsp;&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
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
$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
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
?>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
	<tr class="labelbold">
		<td align="center" valign="middle" nowrap="nowrap">Desc.</td>
		<td align="center" valign="middle" nowrap="nowrap">Month</td>
		<td align="center" valign="middle" nowrap="nowrap"> RAB. </td>
		<td align="center" valign="middle" nowrap="nowrap"> MB No. </td>
		<td align="center" valign="middle" nowrap="nowrap"> Page </td>
		<td align="center" valign="middle">RAB <br/>Value</td>
		<td align="center" valign="middle">85 % of RAB <br/>Value</td>
		<td align="center" valign="middle">Total RAB<br/> Value (W)</td>
		<td align="center" valign="middle">Base <br/>Index</td>
		<td align="center" valign="middle">Esc <br/>Breakup</td>
		<td align="center" valign="middle">Price <br/>Index</td>
		<td align="center" valign="middle">Avg Price <br/>Index</td>
		<td align="center" valign="middle">Formula</td>
		<td align="center" valign="middle">Formula with Values</td>
		<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
	</tr>
<?php //echo $tablehead; ?>
<?php
	$month_count = count($MonthList);
	$bid = "";
	if($month_count>0)
	{
		$select_esc_tcc_query = "select escalation_tcc.esc_tcc_id, escalation_tcc.bid, escalation_tcc.esc_item, escalation_tcc.esc_item_code, 
		escalation_tcc.base_index, escalation_tcc.esc_breakup_perc, escalation_tcc.avg_pi_rate, escalation_tcc.total_85_perc_rab_value, 
		escalation_tcc.esc_amount, escalation_tcc.esc_from_date, escalation_tcc.esc_to_date, escalation_tcc_details.esc_month, 
		escalation_tcc_details.rbn, escalation_tcc_details.mbookno, escalation_tcc_details.mbpage, 
		escalation_tcc_details.rbn_value, escalation_tcc_details.rbn_85_perc_value, escalation_tcc_details.price_index,
		base_index.base_breakup_code, base_index.base_breakup_perc, base_index.type, price_index.avg_pi_code
		from escalation_tcc 
		INNER JOIN escalation_tcc_details ON (escalation_tcc_details.esc_tcc_id = escalation_tcc.esc_tcc_id)
		INNER JOIN base_index ON (base_index.bid = escalation_tcc.bid)
		INNER JOIN price_index ON (price_index.bid = escalation_tcc.bid)
		where escalation_tcc.sheetid = '$sheetid' and  escalation_tcc_details.sheetid = '$sheetid' 
		and escalation_tcc.esc_from_date = '$fromdate' and escalation_tcc.esc_to_date = '$todate'
		and base_index.type = 'TCC' and base_index.sheetid = '$sheetid' and price_index.type = 'TCC' and price_index.sheetid = '$sheetid'
		ORDER BY escalation_tcc.esc_from_date";
		$select_esc_tcc_sql = mysql_query($select_esc_tcc_query);
		//echo $select_esc_tcc_query; 
		if($select_esc_tcc_sql == true)
		{
			if(mysql_num_rows($select_esc_tcc_sql)>0)
			{
				while($EscList = mysql_fetch_object($select_esc_tcc_sql))
				{
					$bid 						= $EscList->bid;
					$esc_tcc_id 				= $EscList->esc_tcc_id;
					$esc_item 					= $EscList->esc_item;
					
					$esc_month 					= $EscList->esc_month;
					$rbn 						= $EscList->rbn;
					$mbookno 					= $EscList->mbookno;
					$mbpage 					= $EscList->mbpage;
					$rbn_value 					= $EscList->rbn_value;
					$rbn_85_perc_value 			= $EscList->rbn_85_perc_value;
					$total_85_perc_rab_value 	= $EscList->total_85_perc_rab_value;
					$base_index 				= $EscList->base_index;
					$esc_breakup_perc 			= $EscList->esc_breakup_perc;
					$price_index 				= $EscList->price_index;
					$avg_pi_rate 				= $EscList->avg_pi_rate;
					$esc_amount 				= $EscList->esc_amount;
					
					$base_breakup_code 			= $EscList->base_breakup_code;
					$avg_pi_code 				= $EscList->avg_pi_code;
					$base_index_code 			= $EscList->esc_item_code;
					
					$tcc_formula 			= "W x <br/>(".$base_breakup_code."/100) x<br/> (".$avg_pi_code."-".$base_index_code.")/<br/>".$base_index_code;
					$formula_with_values 	= $total_85_perc_rab_value." x <br/>(".$esc_breakup_perc."/100) x<br/> (".$avg_pi_rate."-".$base_index.")/<br/>".$base_index;
				}
			}
		}
	}
							
							
							
							
							
							
							
							
							
							/*if($rowcount == 1)
							{
								if($esc_month != $MonthList[0])
								{
									echo '<tr class="labelprint">';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_item.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$MonthList[$mc].'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$total_85_perc_rab_value.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$base_index.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_breakup_perc.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$avg_pi_rate.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$tcc_formula.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$formula_with_values.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_amount.'</td>';
									echo '</tr>';
									
									echo '<tr class="labelprint">';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$esc_month.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbookno.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbpage.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_value.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_85_perc_value.'</td>';
									echo '<td align="center" valign="middle" nowrap="nowrap">'.$price_index.'</td>';
									echo '</tr>';
								}
							}
							if($esc_month != $MonthList[0])
							{
								echo '<tr class="labelprint">';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_item.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$MonthList[$mc].'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$total_85_perc_rab_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$base_index.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_breakup_perc.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$avg_pi_rate.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$tcc_formula.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$formula_with_values.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_amount.'</td>';
								echo '</tr>';
								
								echo '<tr class="labelprint">';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$esc_month.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbookno.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbpage.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_85_perc_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$price_index.'</td>';
								echo '</tr>';
							}
							else
							{
								echo '<tr class="labelprint">';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_item.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$esc_month.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbookno.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbpage.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_85_perc_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$total_85_perc_rab_value.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$base_index.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_breakup_perc.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap">'.$price_index.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$avg_pi_rate.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$tcc_formula.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$formula_with_values.'</td>';
								echo '<td align="center" valign="middle" nowrap="nowrap" rowspan="'.$rowcount.'">'.$esc_amount.'</td>';
								echo '</tr>';
							//}
						}
					}
					else
					{
						echo '<tr class="labelprint">';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$esc_month.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbookno.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$mbpage.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_value.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$rbn_85_perc_value.'</td>';
						echo '<td align="center" valign="middle" nowrap="nowrap">'.$price_index.'</td>';
						echo '</tr>';
					}
					$prev_bid = $bid;
					$mc++;
				}
			}
		}
	}*/
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