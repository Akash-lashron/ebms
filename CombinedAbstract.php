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
function checkPartpayment($DpmArrMbidList,$Key)
{
	$InitKey = $Key;
	while($perc = current($DpmArrMbidList)) 
	{
		if ($perc == $InitKey) 
		{
			//echo key($DpmArrPayPercentList).'<br />';
			$res .= key($DpmArrMbidList)."*";
		}
		next($DpmArrMbidList);
	}
	return rtrim($res,"*");
}

function removeArray($res,$array)
{
	$explodeRes = explode("*",rtrim($res,"*"));
	for($i=0; $i<count($explodeRes);$i++)
	{
		$RemKey = $explodeRes[$i];
		unset($array[$RemKey]);
	}
	return $array;
}
function CheckPageBreak($tablehead,$abstmbno,$table,$page)
{
	$nextpage = $page+1;
	$Output .= "<tr>
					<td colspan='3' align='right' class='labelbold'>C/o Page No ".$nextpage."/ Abstract MB No ".$abstmbno."</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
				</tr>";
	$Output .=  "<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page ".$page."</td></tr>";
	$Output .= "</table>";
	$Output .= "<p  style='page-break-after:always;'></p>";
	$Output .= '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
				<tr style="border:none;"><td align="right" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;</td></tr>
				</table>';
	$Output .= $table;
	$Output .= "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>";
	$Output .= $tablehead;
	$Output .= "<tr>
					<td colspan='3' align='right' class='labelbold'>B/f from Page No ".$page."/ Abstract MB No ".$abstmbno."</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
				</tr>";
	echo $Output;
}

function UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page)
{
	$update_pageno_sql = "update measurementbook_temp set abstmbookno = '$abstmbno', abstmbpage = '$page' where sheetid	= '$abstsheetid' AND subdivid = '$subdivid'";
	$update_pageno_query = mysql_query($update_pageno_sql);
}

function GetEscalationCombAbsAmt($sheetid,$rbn){
	$tcc_amt = 0; $tca_amt = 0; $SLMtotalAmt = 0; $SLMtotalAmt = 0; $UptototalAmt = 0; $OutArr = array();
	$select_query3 	= "select rbn, tcc_amt, tca_amt, rev_tcc_amt, rev_tca_amt from escalation where sheetid = '$sheetid' and rbn <= '$rbn'";
	$select_sql3 	= mysql_query($select_query3);
	if($select_sql3 == true){
		while($List3 = mysql_fetch_object($select_sql3)){
			$tcc_amt = $List3->tcc_amt; 
			$tca_amt = $List3->tca_amt; 
			$rev_tcc_amt = $List3->rev_tcc_amt; 
			$rev_tca_amt = $List3->rev_tca_amt;
			if($rev_tcc_amt > 0){ $tcc_amt = $rev_tcc_amt; }
			if($rev_tca_amt > 0){ $tca_amt = $rev_tca_amt; }
			if($List3->rbn == $rbn){
				$SLMtotalAmt = round(($SLMtotalAmt + $tcc_amt + $tca_amt),2);
			}else{
				$DPMtotalAmt = round(($DPMtotalAmt + $tcc_amt + $tca_amt),2);
			}
		}
	}
		
	$select_query4 	= "select rev_rbn, rev_tcc_amt, rev_tca_amt from escalation_revised where sheetid = '$sheetid' and rbn <= '$rbn'";
	$select_sql4 	= mysql_query($select_query4);
	if($select_sql4 == true){
		while($List4 = mysql_fetch_object($select_sql4)){
			$tcc_amt = $List4->rev_tcc_amt; $tca_amt = $List4->rev_tca_amt;
			if($List4->rev_rbn == $rbn){
				$SLMtotalAmt =  round(($SLMtotalAmt + $tcc_amt + $tca_amt),2);
			}else{
				$DPMtotalAmt =  round(($DPMtotalAmt + $tcc_amt + $tca_amt),2);
			}
		}
	}
	$OutArr['SLM'] = $SLMtotalAmt;
	$OutArr['DPM'] = $DPMtotalAmt;
	$OutArr['UPTO'] = round(($SLMtotalAmt+$DPMtotalAmt),2);
	return $OutArr;//."**".$DPMtotalAmt;
}

$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
//$abstsheetid    = 	$_GET['workno'];
$_SESSION["abstsheetid"] = 	$_GET['workno'];
$abstsheetid    = 	$_SESSION["abstsheetid"];

if($_POST["btn_view"] == " View ") 
{
	$workno = $_POST['cmb_work_no']; //echo "hi";
	$_SESSION["abstsheetid"] = 	$workno;
	$abstsheetid    = 	$_SESSION["abstsheetid"];
}



//$rbn    		= 	$_SESSION["rbn"]; 
//$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
//$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$selectmbook_detail = " select DISTINCT fromdate, todate, rbn, abstmbookno FROM mbookgenerate WHERE sheetid = '$abstsheetid'";
//echo $selectmbook_detail;
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; //$abstmbno = $Listmbdetail->abstmbookno;
	
	$abstmbpage_query 	= 	"select mbno, endpage from mymbook WHERE sheetid = '$abstsheetid' AND rbn = '$rbn' AND active = '1' AND mtype = 'A' AND genlevel = 'abstract' order by mbookorder desc ";
	$abstmbpage_sql 	= 	mysql_query($abstmbpage_query);
	$Listmbook 			= 	mysql_fetch_object($abstmbpage_sql);
	$abstmbno 			= 	$Listmbook->mbno;
	$abstmbpage 		= 	$Listmbook->endpage; //$abs_mbno_id = $Listmbook->allotmentid;
}
$paymentpercent = 	$_SESSION["paymentpercent"];	$emptypage 	= $_SESSION['emptypage'];

if($emptypage == "")
{
	$emptypage = 0;
}
//$empty_page_update_sql = "update mymbook set emptypage = '$emptypage' where sheetid = '$abstsheetid' and mbno = '$abstmbno' and  mtype = 'A' and rbn = '$rbn' and genlevel = 'abstract'";
//$empty_page_update_query = mysql_query($empty_page_update_sql);





$query 		= 	"SELECT    sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
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
		url = "CombinedAbstractGenerate.php";
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
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body bgcolor="" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<!--<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
	</tr>
</table>-->
<form name="form" method="post" onsubmit="return confirm('Do you really want to submit the Book?');">
<?php
$page = $abstmbpage;
$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
$table = $table . "<tr>";
$table = $table . "<td width='17%' class=''>Name of work</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
$table = $table . "<td width='18%' class=''>Name of the contractor</td>";
$table = $table . "<td width='22%' class='' colspan='3'>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>Technical Sanction No.</td>";
$table = $table . "<td class=''>" . $tech_sanction . "</td>";
$table = $table . "<td class=''>Agreement No.</td>";
$table = $table . "<td class='' colspan='3'>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class=''>Work order No.</td>";
$table = $table . "<td class=''>" . $work_order_no . "</td>";
$table = $table . "<td class=''>Running Account bill No. </td>";
$table = $table . "<td class=''>" . $runn_acc_bill_no . "</td>";
$table = $table . "<td class='' align='right'>CC No. </td>";
$table = $table . "<td class=''>" . $ccno . "</td>";
$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td colspan ='4' class='labelprint' align='center'>Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
//$table = $table . "</tr>";
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
//$tablehead = $tablehead . "</table>";
?>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php echo $tablehead; ?>
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 
//$Line = $Line+2;
/*$SheetArr = array();
array_push($SheetArr,);
$select_comb_work_query = "select sheet_id, section_abcd, section_type, under_civil_sheetid from sheet where under_civil_sheetid = '$abstsheetid'";
$select_comb_work_sql = mysql_query($select_comb_work_query);
if($select_comb_work_sql == true){
	if(mysql_num_rows($select_comb_work_sql)>0){
		
	}
}
*/
$ArrayA1 = array(); $ArrayA2 = array(); $ArrayA3 = array(); $ArrayA4 = array(); $ArrayA5 = array(); $ArrayA6 = array(); $ArrayA7 = array(); $ArrayA8 = array();
$ArrayA9 = array(); $ArrayA10 = array(); $ArrayA11 = array(); $ArrayA12 = array(); $ArrayA13 = array();
$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = ""; $RebateCalcFlag = 0;
$TotalCombinedNetAmt = 0;
$Comb_slno = 1; 



$select_comb_work_query = "(select sheet_id, section_abcd, section_type, under_civil_sheetid from sheet where sheet_id = '$abstsheetid') UNION (select sheet_id, section_abcd, section_type, under_civil_sheetid from sheet where under_civil_sheetid = '$abstsheetid' order by section_abcd asc)";
//echo $select_comb_work_query;
$select_comb_work_sql = mysql_query($select_comb_work_query);
if($select_comb_work_sql == true){
	if(mysql_num_rows($select_comb_work_sql)>0){
						?>
						
						<!--<tr><td colspan="12">
						<br/>
						<table width='950px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
							<tr style='background-color:#EEEEEE;' class='labelprint'>
								<td align='center' class=''>S.No</td>
								<td align='center' class=''>Description</td>
								<td align='center' class=''>Reference</td>
								<td align='right' class=''>Amount (in Rs.) </td>
							</tr>-->
						<?php
		while($CWorkList = mysql_fetch_object($select_comb_work_sql)){
			$comb_sheetid = $CWorkList->sheet_id;
			$section_abcd = $CWorkList->section_abcd;
			$section_type = $CWorkList->section_type;
			if($section_type == "I"){ array_push($ArrayA8,"CIVIL"); } 
			else if($section_type == "II"){ array_push($ArrayA8,"ELECTRICAL"); } 
			else if($section_type == "III"){ array_push($ArrayA8,"MECHANICAL"); } 
			else if($section_type == "IV"){ array_push($ArrayA8,"MHE"); } 
			else if($section_type == "V"){ array_push($ArrayA8,"ACV"); } 
			else{ array_push($ArrayA8,"CIVIL"); } 
			
			//echo $comb_sheetid."<br/>";
			$Comb_escal_amt = GetEscalationCombAbsAmt($comb_sheetid,$rbn); //echo $Comb_escal_amt."<br/>";
			array_push($ArrayA11,$Comb_escal_amt['SLM']);
			array_push($ArrayA12,$Comb_escal_amt['DPM']);
			array_push($ArrayA13,$Comb_escal_amt['UPTO']);
			
			$select_comb_rbn_query = "select distinct rbn from measurementbook_temp where sheetid = '$comb_sheetid'";
			//$select_comb_rbn_query = "select distinct rbn from measurementbook_temp where sheetid = '$comb_sheetid' and rbn = '$rbn'";
			$select_comb_rbn_sql = mysql_query($select_comb_rbn_query);
			if($select_comb_rbn_sql == true){
				if(mysql_num_rows($select_comb_rbn_sql)>0){
					$CRbnList = mysql_fetch_object($select_comb_rbn_sql);
					$comb_rbn = $CRbnList->rbn;
					//echo $comb_sheetid." = SLM"."<br/>";
					//$Comb_escal_amt = GetEscalationCombAbsAmt($comb_sheetid,$comb_rbn); //echo $Comb_escal_amt."<br/>";
					
					$select_comb_amt_query = "select * from abstractbook where sheetid = '$comb_sheetid' and rbn = '$comb_rbn'";
					//echo $select_comb_amt_query;
					$select_comb_amt_sql = mysql_query($select_comb_amt_query);
					if($select_comb_amt_sql == true){
						if(mysql_num_rows($select_comb_amt_sql)>0){
							while($CAmtList = mysql_fetch_object($select_comb_amt_sql)){
								$Comb_upto_date_amt = $CAmtList->upto_date_total_amount;
								$Comb_dpm_amt = $CAmtList->dpm_total_amount;
								$Comb_slm_amt = $CAmtList->slm_total_amount;
								$Comb_sec_adv_amt = $CAmtList->secured_adv_amt;
								$Comb_mbookno = $CAmtList->mbookno;
								$Comb_mbookpage = $CAmtList->mbookpage;
								
								array_push($ArrayA1,$comb_sheetid);
								array_push($ArrayA2,$Comb_slm_amt);
								array_push($ArrayA3,$Comb_sec_adv_amt);
								
								//array_push($ArrayA4,$Comb_escal_amt);
								
								
								array_push($ArrayA5,$Comb_mbookno);
								array_push($ArrayA6,$Comb_mbookpage);
								array_push($ArrayA7,$section_abcd);
								
								array_push($ArrayA9,$Comb_upto_date_amt);
								array_push($ArrayA10,$Comb_dpm_amt);
							}
						}
					}
				}
				else{
					$select_comb_rbn_query1 = "select max(rbn) as maxrbn from measurementbook where sheetid = '$comb_sheetid' group by sheetid";
					//echo $select_comb_rbn_query1;exit;
					//$select_comb_rbn_query = "select distinct rbn from measurementbook_temp where sheetid = '$comb_sheetid' and rbn = '$rbn'";
					$select_comb_rbn_sql1 = mysql_query($select_comb_rbn_query1);
					if($select_comb_rbn_sql1 == true){
						if(mysql_num_rows($select_comb_rbn_sql1)>0){
							$CAmtList1 = mysql_fetch_object($select_comb_rbn_sql1);
							$comb_rbn1 = $CAmtList1->maxrbn;
							//echo $comb_sheetid." = DPM"."<br/>";
							$Comb_escal_amt = GetEscalationCombAbsAmt($comb_sheetid,$comb_rbn1); //echo $comb_sheetid."<br/>";
							
							$select_comb_amt_query1 = "select * from abstractbook where sheetid = '$comb_sheetid' and rbn = '$comb_rbn1'";
							//echo $select_comb_amt_query1."<br/>";
							$select_comb_amt_sql1 = mysql_query($select_comb_amt_query1);
							if($select_comb_amt_sql1 == true){
								if(mysql_num_rows($select_comb_amt_sql1)>0){
									while($CAmtList2 = mysql_fetch_object($select_comb_amt_sql1)){
										$Comb_upto_date_amt = $CAmtList2->upto_date_total_amount;
										$Comb_dpm_amt = $CAmtList2->slm_total_amount;
										$Comb_slm_amt = 0;//$CAmtList2->slm_total_amount;
										$Comb_sec_adv_amt = 0;//$CAmtList2->secured_adv_amt;
										$Comb_mbookno = $CAmtList2->mbookno;
										$Comb_mbookpage = $CAmtList2->mbookpage;
										//echo $Comb_upto_date_amt;exit;
										array_push($ArrayA1,$comb_sheetid);
										array_push($ArrayA2,$Comb_slm_amt);
										array_push($ArrayA3,$Comb_sec_adv_amt);
										
										
										//array_push($ArrayA4,$Comb_escal_amt);
										
										
										array_push($ArrayA5,$Comb_mbookno);
										array_push($ArrayA6,$Comb_mbookpage);
										array_push($ArrayA7,$section_abcd);
										
										array_push($ArrayA9,$Comb_upto_date_amt);
										//array_push($ArrayA10,$Comb_dpm_amt);
										array_push($ArrayA10,$Comb_upto_date_amt);
									}
								}
							}
						}
					}
				}
				
				
				
				
				
			}
			
		}
	}
						?>
							<!--<tr style='background-color:#EEEEEE;' class='labelbold'>
								<td align='center' class='' colspan="3">Gross Amount</td>
								<td align='right' class=''><?php// echo number_format($TotalCombinedNetAmt, 2, '.', ''); ?> </td>
							</tr>
							<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='4'>page <?php// echo $page; ?></td></tr>
						</table><br/></td></tr>-->
						<?php
					//if(($OverAllSlmAmount != "")&&($OverAllSlmAmount > 0)){	
						//$update_recovery_query = "update generate_otherrecovery set abstract_net_amt = '$OverAllSlmAmount' where sheetid = '$abstsheetid' and rbn = '$rbn'";
						//$update_recovery_sql = mysql_query($update_recovery_query);	
					//}					
						
}
//print_r($ArrayA10);echo "<br/>";
//echo 
$GrandTotal = 0;
if(count($ArrayA1)>0){
?>
<tr>
	<td colspan="12">
		<br/>
		<table width='950px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
			<tr style='background-color:#EEEEEE;' class='labelprint'>
				<td align='center' class=''>S.No</td>
				<td align='center' class=''>Description</td>
				<!--<td align='center' class=''>Reference</td>-->
				<td align='right' class=''>Upto Date Amount (in Rs.) </td>
				<td align='right' class=''>Deduct Previous Amount (in Rs.) </td>
				<td align='right' class=''>Since Last Amount (in Rs.) </td>
			</tr>
			<tr class="labelbold">
				<td>&nbsp;</td>
				<td align='left' class='' colspan="4">Net Amount</td>
			</tr>
		<?php $Comb_slno = 1; $Tot_Comb_NetAmt = 0; $Tot_Comb_DPM_NetAmt = 0; $Tot_Comb_UPTO_NetAmt = 0; for($x1=0; $x1<count($ArrayA1); $x1++){ ?>	
			<tr class='labelprint'>
				<td align='center' class=''><?php echo $Comb_slno; ?></td>
				<td align='left' class=''><?php echo $ArrayA7[$x1]." - ".$ArrayA8[$x1]; ?></td>
				<!--<td align='center' class=''>MB-<?php// echo $ArrayA5[$x1]; ?> / P-<?php// echo $ArrayA6[$x1]; ?></td>-->
				<td align='right' class=''><?php echo number_format($ArrayA9[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($ArrayA10[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($ArrayA2[$x1], 2, '.', ''); $GrandTotal = $GrandTotal + $ArrayA2[$x1]; ?></td>
			</tr>
		<?php 
		$Comb_slno++; 
		$Tot_Comb_NetAmt = $Tot_Comb_NetAmt + $ArrayA2[$x1]; 
		$Tot_Comb_DPM_NetAmt = $Tot_Comb_DPM_NetAmt + $ArrayA10[$x1]; 
		$Tot_Comb_UPTO_NetAmt = $Tot_Comb_UPTO_NetAmt + $ArrayA9[$x1]; 
		} ?>
			<tr class="labelbold">
				<td colspan="2" align='right'>Net Amount Total&nbsp;</td>
				<td align='right' class=''><?php echo number_format($Tot_Comb_UPTO_NetAmt, 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($Tot_Comb_DPM_NetAmt, 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($Tot_Comb_NetAmt, 2, '.', ''); ?></td>
			</tr>
			
			<!--<tr class="labelbold">
				<td>&nbsp;</td>
				<td align='left' class='' colspan="4">Secured Advance</td>
			</tr>
		<?php //$Comb_slno = 1; $Tot_Comb_SecAdv = 0;  for($x2=0; $x2<count($ArrayA1); $x2++){ ?>	
			<tr class='labelprint'>
				<td align='center' class=''><?php echo $Comb_slno; ?></td>
				<td align='left' class=''><?php echo $ArrayA7[$x2]." - ".$ArrayA8[$x2]; ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA2[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA2[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($ArrayA3[$x2], 2, '.', ''); //$GrandTotal = $GrandTotal + $ArrayA3[$x2]; ?></td>
			</tr>
		<?php //$Comb_slno++; $Tot_Comb_SecAdv = $Tot_Comb_SecAdv + $ArrayA3[$x2]; } ?>
			<tr class="labelbold">
				<td colspan="2" align='right'>Total&nbsp;</td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
			</tr>-->
			
			<tr class="labelbold">
				<td>&nbsp;</td>
				<td align='left' class='' colspan="4">Escalation</td>
			</tr>
		<?php $Comb_slno = 1; $Tot_Comb_EscAmt = 0;$Tot_Comb_DPM_EscAmt = 0;$Tot_Comb_UPTO_EscAmt = 0; for($x3=0; $x3<count($ArrayA1); $x3++){ ?>	
			<tr class='labelprint'>
				<td align='center' class=''><?php echo $Comb_slno; ?></td>
				<td align='left' class=''><?php echo $ArrayA7[$x3]." - ".$ArrayA8[$x3]; ?></td>
				<!--<td align='center' class=''><?php// if($ArrayA4[$x3] != 0){ ?> MB-<?php// echo $ArrayA5[$x3]; ?> / P-<?php// echo $ArrayA6[$x2]; } else{ echo "--"; }  ?></td>-->
				<td align='right' class=''><?php //echo number_format($ArrayA13[$x3], 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA12[$x3], 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA11[$x3], 2, '.', ''); $GrandTotal = $GrandTotal + $ArrayA11[$x3]; //echo number_format($ArrayA4[$x3], 2, '.', ''); $GrandTotal = $GrandTotal + $ArrayA4[$x3]; ?></td>
			</tr>
		<?php 
		$Comb_slno++; 
		//$Tot_Comb_EscAmt = $Tot_Comb_EscAmt + $ArrayA11[$x3]; 
		//$Tot_Comb_DPM_EscAmt = $Tot_Comb_DPM_EscAmt + $ArrayA12[$x3];
		//$Tot_Comb_UPTO_EscAmt = $Tot_Comb_UPTO_EscAmt + $ArrayA13[$x3];
		} ?>
			<tr class="labelbold">
				<td colspan="2" align='right'>Escalation Total&nbsp;</td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_UPTO_EscAmt, 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_DPM_EscAmt, 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_EscAmt, 2, '.', ''); ?></td>
			</tr>
			
			<tr class="labelbold">
				<td colspan="2" align='right'>Service Tax&nbsp;</td>
				<td align='right' class=''></td>
				<td align='right' class=''></td>
				<td align='right' class=''></td>
			</tr>
			<tr class="labelbold">
				<td colspan="2" align='right'>Total&nbsp;</td>
				<td align='right' class=''></td>
				<td align='right' class=''></td>
				<td align='right' class=''></td>
			</tr>
			
			<tr class="labelbold">
				<td>&nbsp;</td>
				<td align='left' class='' colspan="4">Secured Advance</td>
			</tr>
		<?php $Comb_slno = 1; $Tot_Comb_SecAdv = 0;  for($x2=0; $x2<count($ArrayA1); $x2++){ ?>	
			<tr class='labelprint'>
				<td align='center' class=''><?php echo $Comb_slno; ?></td>
				<td align='left' class=''><?php echo $ArrayA7[$x2]." - ".$ArrayA8[$x2]; ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA2[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($ArrayA2[$x1], 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($ArrayA3[$x2], 2, '.', ''); $GrandTotal = $GrandTotal + $ArrayA3[$x2]; ?></td>
			</tr>
		<?php $Comb_slno++; $Tot_Comb_SecAdv = $Tot_Comb_SecAdv + $ArrayA3[$x2]; } ?>
			<tr class="labelbold">
				<td colspan="2" align='right'>Secured Advance Total&nbsp;</td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($Tot_Comb_SecAdv, 2, '.', ''); ?></td>
			</tr>	
			
			<tr class='labelbold'>
				<td align='right' class='' colspan="2">GRAND TOTAL&nbsp;</td>
				<td align='right' class=''><?php //echo number_format($GrandTotal, 2, '.', ''); ?></td>
				<td align='right' class=''><?php //echo number_format($GrandTotal, 2, '.', ''); ?></td>
				<td align='right' class=''><?php echo number_format($GrandTotal, 2, '.', ''); ?></td>
			</tr>
			
		</table>
		<br/>
	</td>
</tr>
<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>page <?php echo $page; ?></td></tr>
<?php
}
?>

</table>
<p style='page-break-after:always;'></p>
<?php 
$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();
$esc_cnt = 0;
$Esc_Total_Amt = 0;
$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($EscList = mysql_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt = round(($esc_tcc_amount+$esc_tca_amount),2);//$EscList->esc_total_amt;
			
			//$Esc_Total_Amt = $Esc_Total_Amt+$esc_tcc_amount+$esc_tca_amount;
			$Esc_Total_Amt = $Esc_Total_Amt+$esc_qtr_amt;//+$esc_tca_amount;
			
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
		}
	}
}
$Esc_Total_Amt = round($Esc_Total_Amt);

$RevEscQtrArray = array();
$RevEscTccAmtArray = array();
$RevEscTcaAmtArray = array();
$rev_esc_cnt = 0;
$RevEsc_Total_Amt = 0;
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rev_esc_total_amt != 0 ORDER BY quarter ASC";
//echo $select_rev_esc_rbn_query;
$select_rev_esc_rbn_sql = mysql_query($select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_rev_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($RevEscList = mysql_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			
			$total_rev_esc_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);
			
			$paid_esc_tcc_amount = $RevEscList->tcc_amt;
			$paid_esc_tca_amount = $RevEscList->tca_amt;
			
			$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
			
			//// Second or more than two time revised
			$select_esc_paid_query = "select rev_tcc_mbook, rev_tcc_mbpage, rev_esc_total_amt from escalation_revised where sheetid = '$abstsheetid' and quarter = '$rev_quarter' ORDER BY rev_esc_id  DESC";
			$select_esc_paid_sql = mysql_query($select_esc_paid_query);
			if($select_esc_paid_sql == true)
			{
				$PaidEAbaMB = mysql_fetch_object($select_esc_paid_sql);
				$PaidEsc_Abs_MBook = $PaidEAbaMB->rev_tcc_mbook;
				$PaidEsc_Abs_MBPage = $PaidEAbaMB->rev_tcc_mbpage;
				$PaidEsc_Abs_tot_amt = $PaidEAbaMB->rev_esc_total_amt;
				//echo $PaidEsc_Abs_tot_amt;
			}
			if($PaidEsc_Abs_tot_amt>0)
			{
				$paid_esc_tcc_amount = $PaidEsc_Abs_MBook;
				$paid_esc_tca_amount = $PaidEsc_Abs_MBPage;
				//$Esc_Abs_tot_amt = $PaidEsc_Abs_tot_amt;
				//$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
				$total_paid_esc_amt = $PaidEsc_Abs_tot_amt;
			}
			
			
			//echo $total_paid_esc_amt;
			
			//$rev_esc_qtr_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);//$EscList->esc_total_amt;
			$rev_esc_qtr_amt = round(($total_rev_esc_amt-$total_paid_esc_amt),2);
			
			
			//$Esc_Total_Amt = $Esc_Total_Amt+$esc_tcc_amount+$esc_tca_amount;
			$RevEsc_Total_Amt = $RevEsc_Total_Amt+$rev_esc_qtr_amt;//+$esc_tca_amount;
			
			array_push($RevEscQtrArray,$rev_quarter);
			array_push($RevEscTccAmtArray,$rev_esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
		}
	}
}
$RevEsc_Total_Amt = round($RevEsc_Total_Amt);


//print_r($RevEscTccAmtArray);exit;
//print_r($EscAmtArray);
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysql_query($secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysql_fetch_object($secured_advance_sql);
	$sec_adv_amount	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount = 0;
}

$total_recovery = 0;
$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysql_query($water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysql_fetch_object($water_recovery_sql))
	{
		$water_charge 	=  $water_charge+$WRList->water_cost; 
	}
}
else
{
	$water_charge = 0;
}
$total_recovery = $total_recovery + $water_charge;
$electricity_recovery_query = "select electricity_cost from generate_electricitybill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$electricity_recovery_sql = mysql_query($electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysql_fetch_object($electricity_recovery_sql))
	{
		$electricity_charge  = 	$electricity_charge+$ERList->electricity_cost;
	}
}
else
{
	$electricity_charge = 0;
}
$total_recovery = $total_recovery + $electricity_charge;
$general_recovery_query = "select * from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
//echo $general_recovery_query;
$general_recovery_sql = mysql_query($general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 			= 	mysql_fetch_object($general_recovery_sql);
	$sd_amt 			= 	round($GRList->sd_amt);
	$sd_percent 		= 	$GRList->sd_percent;
	$wct_amt 			= 	round($GRList->wct_amt);
	$wct_percent 		= 	$GRList->wct_percent;
	$vat_amt 			= 	round($GRList->vat_amt);
	$vat_percent 		= 	$GRList->vat_percent;
	$mob_adv_amt 		= 	round($GRList->mob_adv_amt);
	$mob_adv_percent 	= 	$GRList->mob_adv_percent;
	$lw_cess_amt 		= 	round($GRList->lw_cess_amt);
	$lw_cess_percent 	= 	$GRList->lw_cess_percent;
	$incometax_amt 		= 	round($GRList->incometax_amt);
	$incometax_percent 	= 	$GRList->incometax_percent;
	$it_cess_amt 		= 	round($GRList->it_cess_amt);
	$it_cess_percent 	= 	$GRList->it_cess_percent;
	$it_edu_amt 		= 	round($GRList->it_edu_amt);
	$it_edu_percent 	= 	$GRList->it_edu_percent;
	$land_rent 			= 	round($GRList->land_rent);
	$liquid_damage 		= 	round($GRList->liquid_damage);
	//$other_recovery_1 	= 	round($GRList->other_recovery_1_amt);
	//$other_recovery_2	= 	round($GRList->other_recovery_2_amt);
	$other_recovery_1 	= 	round($GRList->other_recovery_1);
	$other_recovery_2	= 	round($GRList->other_recovery_2);
	$other_recovery_3	= 	round($GRList->other_recovery_3);
	$other_recovery_1_desc 	= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc	= 	$GRList->other_recovery_2_desc;
	$other_recovery_3_desc	= 	$GRList->other_recovery_3_desc;
	if($other_recovery_1_desc == "")
	{
		$other_recovery_1_desc = "Other Recovery 1 ";
	}
	if($other_recovery_2_desc == "")
	{
		$other_recovery_2_desc = "Other Recovery 2 ";
	}
	if($other_recovery_3_desc == "")
	{
		$other_recovery_3_desc = "Other Recovery 3 ";
	}
	$non_dep_machine_equip 	= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa 	= 	round($GRList->nonsubmission_qa);
}
if($non_dep_machine_equip != 0)
{
	$non_dep_machine_equip_print = number_format($non_dep_machine_equip, 2, '.', '');
}
else
{
	$non_dep_machine_equip_print = "NIL";
}

if($non_dep_man_power != 0)
{
	$non_dep_man_power_print = number_format($non_dep_man_power, 2, '.', '');
}
else
{
	$non_dep_man_power_print = "NIL";
}

if($electricity_charge != 0)
{
	$electricity_charge_print = number_format($electricity_charge, 2, '.', '');
}
else
{
	$electricity_charge_print = "NIL";
}

if($water_charge != 0)
{
	$water_charge_print = number_format($water_charge, 2, '.', '');
}
else
{
	$water_charge_print = "NIL";
}
$total_recovery = $total_recovery + $sd_amt+$wct_amt + $vat_amt+$mob_adv_amt + $lw_cess_amt+$incometax_amt + $it_cess_amt+$it_edu_amt + $land_rent+$liquid_damage + $other_recovery_1 + $other_recovery_2 + $other_recovery_3 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;
$rrcount = 0;  $total_rec_rel_amt = 0;
$RRDescCivArr = array(); $RRAmtCivArr = array(); $RRDescAccArr = array(); $RRAmtAccArr = array();

$recov_release_query = "select * from recovery_release where sheetid = '$abstsheetid' and rbn = '$rbn'";
$recov_release_sql = mysql_query($recov_release_query);
//echo $recov_release_query;
if($recov_release_sql == true)
{
	if(mysql_num_rows($recov_release_sql)>0)
	{
		while($RecRelList = mysql_fetch_object($recov_release_sql))
		{
			$rec_rel_desc_civil = $RecRelList->description_civil;
			$rec_rel_amt_civil 	= $RecRelList->amount_civil;
			$rec_rel_desc_acc 	= $RecRelList->description_acc;
			$rec_rel_amt_acc 	= $RecRelList->amount_acc;
			array_push($RRDescCivArr,$rec_rel_desc_civil);
			array_push($RRAmtCivArr,$rec_rel_amt_civil);
			array_push($RRDescAccArr,$rec_rel_desc_acc);
			array_push($RRAmtAccArr,$rec_rel_amt_acc);
			$total_rec_rel_amt  = $total_rec_rel_amt+$rec_rel_amt_civil;
			$rrcount++;
		}
	}
}

$page++;
/*$OverAllSlmDpmAmount = round($OverAllSlmDpmAmount);
$OverAllSlmAmount = round($OverAllSlmAmount);*/

$OverAllSlmDpmAmount = round($OverAllSlmDpmAmount);
$OverAllSlmAmount = round($OverAllSlmAmount);
$OverAllDpmAmount = round($OverAllDpmAmount);
$OverAllSlmAmount = $GrandTotal;

//echo "<p style='page-break-after:always;'></p>";
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
//echo "<tr><td class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

////  This is for print Escalation
if(count($EscQtrArray)>0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round($OverAllSlmAmount+$Esc_Total_Amt);

////  This is for print Revised Escalation
//print_r($RevEscTccAmtArray);
if(count($RevEscQtrArray)>0)
{
	for($q2=0; $q2<count($RevEscQtrArray); $q2++)
	{
		$RevEQtr = $RevEscQtrArray[$q2];
		$RevETccAmt = $RevEscTccAmtArray[$q2];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Revised Escalation for Quarter - ".$RevEQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($RevETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round($OverAllSlmAmount+$RevEsc_Total_Amt);



//echo '<hr style="border-top: dotted 1px;" />';
//$OverAllSlmAmount = $OverAllSlmAmount + $sec_adv_amount;
$Overall_net_amt_final = round(($OverAllSlmAmount + $total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = round($Overall_net_amt_final);

$OverAllSlmAmount = $GrandTotal;

//echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='6'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none; border-top:1px dashed #000000' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none; border-top:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='6'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none;' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='2' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";
$ea = 1; $eb = 1; $ed = 1; 
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";  $ed_text = "<b><u>With hold Amount</u></b>";
if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";


}
if($lw_cess_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";




}
if($mob_adv_percent != 0)
{
//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
//if($water_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
//if($electricity_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_3 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_3_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_3, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_machine_equip != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_man_power != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";


if($sd_amt != 0)
{
$eb = 1;
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ec_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Security Deposit @ 5% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

// This row is for Recovery Release
if($rrcount>0)
{
	for($rrc=0; $rrc<$rrcount; $rrc++)
	{
	echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ed_text." (".$ed.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$RRDescCivArr[$rrc]." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($RRAmtCivArr[$rrc], 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
	$ed++; $ed_text = "";
	}
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
}

//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//$Overall_net_amt_final = "18767031.35";
$split_amt = explode(".",$Overall_net_amt_final);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];
$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
echo "<tr style='border:none'><td style='border:none'>&nbsp;</td><td style='border:none'>&nbsp;</td><td style='border:none' class='labelprint' align='left' colspan='12'>Amount: (Rupees ".$amount_in_words.")</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>page ".$page."</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
?>
<?php 

//echo "<p  style='page-break-after:always;'></p>";
for($x=0;$x<$emptypage;$x++)
{
$page++;
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
$y=1;
while($y<22)
{
?>
	<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='20%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='8%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
	<?php
	$y++;		
}
echo "<tr class='labelprint'><td colspan='12' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'> </td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}
?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo $page; ?>" />
<input type="hidden" name="txt_abstractstr" id="txt_abstractstr" value="<?php echo $AbstractStr; ?>" />
<input type="hidden" name="txt_subdivid_slmstr" id="txt_subdivid_slmstr" value="<?php echo $SubdividSlmStr; ?>" />
<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $runn_acc_bill_no; ?>" />

<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />
<!--<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect">
		<input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> 
	</div>
	<div class="btn_inside_sect">
		<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
	</div>
	<div class="btn_inside_sect">
		<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
	</div>
</div> -->

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> 
			</div>
			<!--<div class="buttonsection">
			<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
			</div>-->
			<div class="buttonsection">
			<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
			</div>
		</div>

		<!-- modal content -->
		<!--<div id="basic-modal-content">
			<div align="center" class="popuptitle">Part Payment Work Sheet</div>
			<div align="center" style="padding-top:10px;">
			<table class="label table2" width="100%" cellpadding="3" cellspacing="3" id="table2">
				<tr bgcolor="">
					<td width="60px" align="left">Item No.</td>
					<td width="">
						<input type="text" readonly="" name="txt_item_no" id="txt_item_no" size="8" class="popuptextbox" />
						<input type="hidden" name="txt_item_id" id="txt_item_id" size="8" class="popuptextbox" />
					</td>
					<td width="60px" align="center">RAB No.</td>
					<td width="">
						<input type="text" name="txt_rab_no" id="txt_rab_no" size="6" class="popuptextbox" value="<?php echo $rbn; ?>" />
					</td>
					<td  align="left" colspan="4">Measurement Date - From &nbsp; :
						<input type="text" name="txt_from_date" id="txt_from_date" size="12" class="popuptextbox" value="<?php echo dt_display($fromdate); ?>" />
					To :
						<input type="text" name="txt_to_date" id="txt_to_date" size="12" class="popuptextbox" value="<?php echo dt_display($todate); ?>" />
					</td>
				</tr>
				<tr bgcolor="">
				<td width="135px" align="left">Item Description</td>
					<td width="700px" align="left" colspan="7">
						<textarea name="txt_item_desc" id="txt_item_desc" class="popuptextbox" rows="2" style="text-align:left; width:820px; height:34px;"></textarea>
					</td>
				</tr>

			</table>
			</div>
			<div style="padding-top:10px; height:325px;">
				<div style="float:left; width:567px; height:320px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="94%" id="table3">
					<tr bgcolor="#0080ff" style="color:#FFFFFF">
						<td align="center" colspan="7">Deduct Previous Measurement</td>
					</tr>
					<tr>
						<td align="left" colspan="7" bgcolor="#f2efef">
						Deduct Previous Measurement Total Quantity&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
						<input type="text" name="txt_dpm_qty" id="txt_dpm_qty" size="17" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
						</td>
					</tr>
					<tr>
						<td width="10px" rowspan="2" align="center">RBN.</td>
						<td width="61px" rowspan="2" align="center">Item Qty.</td>
						<td width="63px" rowspan="2" align="center">Rate&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Paid Details</td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Payable Details</td>
					</tr>
					<tr>
						<td width="23px" align="center">(%)</td>
						<td width="110px" align="center">Amount&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td width="23px" align="center">(%)</td>
						<td style='width:110px' align="center">Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
					</tr>
					<tr>
						<td colspan="4" align="right">Total Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
						<td align="left"><input type="text" name="txt_partpay_total_paidamt_dpm" id="txt_partpay_total_paidamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
						<td colspan=""></td>
						<td colspan=""><input type="text" name="txt_partpay_total_payableamt_dpm" id="txt_partpay_total_payableamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
					</tr>
					<tr>
						<td colspan="7">Remarks:<br/><textarea name="txt_dpm_remarks" id="txt_dpm_remarks" rows="3" style="width:519px;"></textarea>
						</td>
					</tr>
				</table>
				</div>
				<div style="float:right;  width:427px; height:320px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="93%" id="table4">
						<tr bgcolor="#0080ff" style="color:#FFFFFF">
							<td align="center" colspan="5">Since Last Measurement</td>
						</tr>
						<tr>
							<td align="left" colspan="5" bgcolor="#f2efef">
							Since Last Measurement Quantity&nbsp;:&nbsp;
							<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							<input type="hidden" name="hid_slm_qty" id="hid_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							</td>
						</tr>
						<tr>
							<td width="61px" align="center">Item Qty.</td>
							<td width="63px" align="center">Rate&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i></td>
							<td width="23px" align="center">(%)</td>
							<td width="50px" align="center">Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i></td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr id='rowid0'>
							<td width="61px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_qty_slm[]" id="txt_partpay_qty_slm0" class="dynamictextbox" style="text-align:right; width:93px; border: 1px solid #2aade4;" onblur="ValidateSlm(); calculateAmount(this,0,'qty','slm');" />
							</td>
							<td width="63px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_item_rate_slm" readonly="" id="txt_item_rate_slm0" class="dynamictextbox" style="text-align:right; width:80px;" onblur="calculateAmount(this,0,'rate','slm');" />
							</td>
							<td width="23px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_percent_slm" id="txt_partpay_percent_slm0" class="dynamictextbox" style="text-align:right; width:40px; border: 1px solid #2aade4;" onblur="ValidatePercent(this,'slm',0); calculateAmount(this,0,'percent','slm');" />
							</td>
							<td width="50px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_amt_slm[]" id="txt_partpay_amt_slm0" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center" class="dynamicrowcell" style="text-align:center;">
							<input type="button" name="btn_add_row_slm" id="btn_add_row_slm" class="editbtnstyle" value=" + " style="width:32px; text-align:center; font-weight:bold; border-radius: 0px;" onclick="addRow();" />
							<input type="hidden" name="hid_slm_result[]" id="hid_slm_result0" class="dynamictextbox" />
							</td>
						</tr>
						<tr>
							<td width="147px" colspan="3" align="right">Total Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
							<td width="50px" align="right"  class="dynamicrowcell">
							<input type="text" name="txt_partpay_total_amt_slm" id="txt_partpay_total_amt_slm" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="5">Remarks:<br/><textarea name="txt_slm_remarks" id="txt_slm_remarks" rows="3" style="width:375px;"></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div align="right">
				<table width="100%" height="65" class="label" cellpadding="3" cellspacing="3">
					<tr>
					<td align="right" width="440px">
					<label style="background:#EAEAEA; padding:6px;">Over All Total Amount</label>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;
					<input type="text" name="txt_overall_total" id="txt_overall_total" size="20" class="dynamictextbox dynamictextbox2" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					</tr>
				</table>
			</div>
			<div class="bottomsection" align="center">
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="buttonstyle" onclick="SaveData()" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="buttonstyle" onclick="CancelData()" /></div>
			</div>
		</div>
		
		<!-- preload the images -->
		<!--<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>     -->
</form>
<script type="text/javascript">
	$(function(){ 
		var textBoxStr = "<?php echo $DIEITextBoxStr; ?>";
		if(textBoxStr != "")
		{
			var splitval = textBoxStr.split("*"); //alert(splitval.length);
			var x=0;
			for(x=0;x<splitval.length;x+=3)
			{
				document.getElementById("txt_co_di_ei"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No. "+splitval[x+2]; 
			}
		}
   });
</script>
</body>

</html>