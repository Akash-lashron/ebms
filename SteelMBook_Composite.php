<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];//echo $staffid;
$userid = $_SESSION['userid'];
$mbooktype = "S";
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
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
    return $dd . '/' . $mm . '/' . $yy;
}

/*if($_POST['back'])
{
    header('Location: Generate_Composite.php');
}*/

$sheetid		=	$_SESSION["sheet_id"]; 
$fromdate 		= 	$_SESSION['fromdate'];
$todate 		= 	$_SESSION['todate'];
$mbookno 		= 	$_SESSION["mb_no"];  
$mpage 			= 	$_SESSION["mb_page"]; 
$rbn 			= 	$_SESSION["rbn"];
$steelmbno_id 	= 	$_SESSION["mbno_id"];
$abstmbookno 	= 	$_SESSION["abs_mbno"];
$oldmbookno 	=	$mbookno;
$oldmbookpage 	=	$mpage;
//$temp_sql = "DELETE FROM temp WHERE flag =3 OR flag =2 AND usersid = '$userid'";
//echo $temp_sql;exit;
         //$res_query = dbQuery($temp_sql);
if($_GET['varid'] == 1)
{
	$deletequery=mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'S'");
}
if($_GET['newmbook'] != "")
{
$newmbookno = $_GET['newmbook'];
$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql = mysql_query($newmbookpage_query);
$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage');
}

$Mbsteelgeneratedelsql		= 	"DELETE FROM mbookgenerate WHERE flag =2 AND sheetid = '$sheetid'";
$Mbsteelgeneratedelsql_qry 	= 	mysql_query($Mbsteelgeneratedelsql);
$MbsteelmbookTempdelsql 	= 	"DELETE FROM measurementbook_temp WHERE flag =2 AND sheetid = '$sheetid'";
$MbsteelmbookTempdelsql_qry = 	mysql_query($MbsteelmbookTempdelsql);

function MeasurementSteelinsert($fromdate,$todate,$sheetid,$mbookno,$mpage,$totalweight_MT,$rbn,$userid,$subdivid,$divid,$staffid,$abstmbookno)
{  
   
   $querys="INSERT INTO mbookgenerate set staffid = '$staffid', sheetid='$sheetid',divid='$divid',subdivid='$subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=2,rbn='$rbn',
            mbgeneratedate=NOW(), mbpage='$mpage', abstmbookno='$abstmbookno', mbtotal='$totalweight_MT', active=1, userid='$userid'";
 //echo $querys."<br/>";
   $sqlquerys = mysql_query($querys);
}
function check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1)
{
		if($mpage == 100) { $mbookno = $newmbookno; }
		$x1 = "<tr>";
		$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
		$x1 = $x1."</tr>";
		$x1 = $x1."</table>";
		$x1 = $x1."<p  style='page-break-after:always;'></p>";
		$x1 = $x1.'<table width="1087px" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
				<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
				</table>';
		$x1 = $x1.$tablehead; 
		$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF">';
		$x1 = $x1.$table1;
		echo $x1;
}
function display_carry($sumst,$mbookno,$mpage,$newmbookno,$decimal)
{
	if($mpage == 100) { $page = 0; $mbookno = $newmbookno;} else { $page = $mpage; }
	$tmb2 = $sumst;
	$explodedval = explode("@",$tmb2); 
	for($i=0;$i<count($explodedval);$i++)
	{
	   if($explodedval[$i] != "")
	   {
		  $expval = explode("*",$explodedval[$i]); 
		  if($expval[0] == 8){ $tot8 = $tot8 + $expval[1]; }
		  if($expval[0] == 10){ $tot10 = $tot10 + $expval[1]; }
		  if($expval[0] == 12){ $tot12 = $tot12 + $expval[1]; }
		  if($expval[0] == 16){ $tot16 = $tot16 + $expval[1]; }
		  if($expval[0] == 20){ $tot20 = $tot20 + $expval[1]; }
		  if($expval[0] == 25){ $tot25 = $tot25 + $expval[1]; }
		  if($expval[0] == 28){ $tot28 = $tot28 + $expval[1]; }
		  if($expval[0] == 32){ $tot32 = $tot32 + $expval[1]; }
		  if($expval[0] == 36){ $tot36 = $tot36 + $expval[1]; }
		}
	} 
	if($tot8 == 0) { $tot8 = ""; } else { $tot8 = number_format($tot8,$decimal,".",","); }
	if($tot10 == 0) { $tot10 = ""; } else { $tot10 = number_format($tot10,$decimal,".",","); }
	if($tot12 == 0) { $tot12 = ""; } else { $tot12 = number_format($tot12,$decimal,".",","); }
	if($tot16 == 0) { $tot16 = ""; } else { $tot16 = number_format($tot16,$decimal,".",","); }
	if($tot20 == 0) { $tot20 = ""; } else { $tot20 = number_format($tot20,$decimal,".",","); }
	if($tot25 == 0) { $tot25 = ""; } else { $tot25 = number_format($tot25,$decimal,".",","); }
	if($tot28 == 0) { $tot28 = ""; } else { $tot28 = number_format($tot28,$decimal,".",","); }
	if($tot32 == 0) { $tot32 = ""; } else { $tot32 = number_format($tot32,$decimal,".",","); }
	if($tot36 == 0) { $tot36 = ""; } else { $tot36 = number_format($tot36,$decimal,".",","); }
	$row_co = "<tr height=''>";
	$row_co = $row_co."<td width='' colspan='6' class='labelcenter labelheadblue' style='text-align:right'>"."C/o to Page ".($page+1)."/ Steel MB No. ".$mbookno."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot8."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot10."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot12."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot16."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot20."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot25."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot28."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot32."</td>";
	$row_co = $row_co."<td width='6%' class='labelcenter' style='text-align:right'>".$tot36."</td>";
	$row_co = $row_co."<td width='2%' class='labelcenter'></td>";
	$row_co = $row_co."</tr>";
	//$row_co = $row_co."<tr height='' style='text-align:center;border-style:none'>";
	//$row_co = $row_co."<td width='100%' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
	//$row_co = $row_co."</tr>";
	echo $row_co;
}
$wodataquery = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn FROM sheet WHERE sheet_id = '$sheetid' ";
$wodataquerysql = mysql_query($wodataquery);
if ($wodataquerysql == true) 
    {
    $Res = mysql_fetch_object($wodataquerysql);
    $work_name = $Res->work_name;    $tech_sanction = $Res->tech_sanction;
    $name_contractor = $Res->name_contractor;    $agree_no = $Res->agree_no; $work_order_no = $Res->work_order_no; 
	$ccno = $Res->computer_code_no;
   // if($Res->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$Res->rbn + 1;}
   $runn_acc_bill_no = $rbn;
    }
	
$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/130);
//echo $start_line;
function stafflist($subdivid,$date,$sheetid)
{
	$date = dt_format($date);
	$staff_design_sql = "select  DISTINCT staff.staffname, designation.designationname, mbookheader.date from staff 
	INNER JOIN designation ON (designation.designationid = staff.designationid) 
	INNER JOIN mbookheader ON (mbookheader.staffid = staff.staffid)
	WHERE staff.staffid = mbookheader.staffid AND staff.active = 1 AND designation.active = 1 AND mbookheader.date = '$date' AND mbookheader.sheetid = '$sheetid' AND mbookheader.subdivid = '$subdivid'";
	$staff_design_query = mysql_query($staff_design_sql);
	while($staffList = mysql_fetch_object($staff_design_query))
	{
		$staffname = $staffList->staffname;
		$designation = $staffList->designationname;
		$result .= $staffname."*".$designation."*";
	}
	return rtrim($result,"*");
	//echo $staff_design_sql."<br/";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Steel M.Book</title>
        <link rel="stylesheet" href="script/font.css" />
        
    </head>
		<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
		<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
		<link rel="stylesheet" href="css/button_style.css"></link>
	 	<link rel="stylesheet" href="js/jquery-ui.css">
	  	<script src="js/jquery-1.10.2.js"></script>
	  	<script src="js/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  	function goBack()
	{
	   	url = "Generate_Composite.php";
		window.location.replace(url);
	}
  $(function() {
   $("#dialog").dialog({ autoOpen: false,
        minHeight: 200,
        maxHeight:200,
        minWidth: 300,
        maxWidth: 300,
        modal: true,});
        $("#dialog").dialog("open");
		//$("body").css({ overflow: 'hidden' });
        $( "#dialog" ).dialog( "option", "draggable", false );
       	 $('#btn_cancel').click(function(){
		 $("#dialog").dialog("close");
		 window.location.href="Generate.php";
		 });
        $('#btn').click(function(){
		var x = $('#newmbooklist option:selected').val();
			if(x == "")
			{
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				$("#dialog").dialog("close"); 
				//$("body").css({ overflow: 'scroll' });      
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_steelmbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
				window.location.href="SteelMbook.php?newmbook="+newmbookvalue;
				return false; // avoid to execute the actual submit of the form.
				});
			 }
         });
				$.fn.validatenewmbook = function(event) 
				{ 
					if($('#newmbooklist option:selected').val()=="")
					{ 
						var a="Please select Next Mbook number";
						$('#error_msg').text(a);
						event.preventDefault();
						event.returnValue = false;
						//return false;
					}
					else
					{
						var a="";
						$('#error_msg').text(a);
					}
				}
				$("#newmbooklist").change(function(event){
				   $(this).validatenewmbook(event);
				 });		 
		 
		 
  });
  </script>
<style type="text/css" media="print,screen" >
table
{ 
	border-collapse: collapse; 
}
td 
{ 
	border: 1px solid #CACACA; 
}
@media screen 
{
    div.divFooter 
	{
        display: none;
    }
}
@media print 
{
   	div.divFooter 
   	{
       	position: fixed;
       	bottom: 0;
		size: landscape;
   	} 
	.header
	{
		display: none !important;
	}
	.printbutton
	{
		display: none !important;
	}
}
.ui-dialog > .ui-widget-header 
{
	background: #20b2aa; font-size:12px;
}
.labelcontent
{
	font-family:Microsoft New Tai Lue;
	font-size:12pt;
	color:#000000;
}
.label, .labelcenter
{
	color:#0000CD;
}
.ui-dialog-titlebar-close 
{
  	visibility: hidden;
}
.submit_btn
{
	position:absolute;
	border:none;
	top:110px;
	left:80px;
	font-weight:bold;
	font-size:12px;
}
.cancel_btn
{
	position:absolute;
	border:none;
	top:110px;
	left:160px;
	font-weight:bold;
	font-size:12px;
}
.submit_btn:hover 
{
    color:#000000;
    -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	padding: 0.3em 1em;
}
.cancel_btn:hover 
{
    color:#000000;
    -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	padding: 0.3em 1em;
}
.labelheadblue
{
	color:#0000CD;
	font-weight:bold;
	font-size:12px;
}
.labelcontentblue
{
	color:#0000CD;
	font-weight:bold;
	font-size:12pt;	
}
.textboxcobf
{
  	width:223px; 
	border:none; 
	text-align:right;
	font-weight:bold;
	color:#0000CD;
}
.title
{
	font-family:Verdana, Arial, Helvetica, sans-serif;
	color:#FFFFFF; 
	border:none; 
	font-size:16px;
	font-weight:bold;
}

</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
</script>
<body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" style=" text-align:center; left:92px;" height="56px" align="center" bgcolor="#1babd3" class=''>
	<tr style="position:fixed;">
		<td class="title"  width="1086px"  height="56px" align="center" bgcolor="#1babd3">Steel Sub-Abstract</td>
	</tr>
</table>
<form name="form" method="post" style="">
<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>" />
<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" />
<input type="hidden" name="hid_userid" id="hid_userid" value="<?php echo $userid; ?>" />
<input type="hidden" name="txt_steelmbno_id" value="<?php echo $steelmbno_id."*".$mbookno."*"."S"."*".$staffid."*".$sheetid; ?>" id="txt_steelmbno_id" />
<?php
$title = '<table width="1087px" border="0"  cellpadding="2" class="label" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;">
		<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
		</table>';
echo $title;
$table = "<table width='1087px' border='0'  cellpadding='3' cellspacing='3' align='center' bgcolor='#FFFFFF'>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='20%' nowrap='nowrap' class='labelbold labelheadblue' bgcolor=''>Name of work:</td>";
$table = $table . "<td width='80%' class='label labelheadblue' colspan='2'  style='word-wrap:break-word;'>" . $work_name . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='' nowrap='nowrap' class='labelbold labelheadblue' valign='top' bgcolor=''>Technical Sanction No.</td>";
$table = $table . "<td class='label labelheadblue' colspan='2'> " . $tech_sanction . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='' nowrap='nowrap' class='labelbold labelheadblue' valign='top' bgcolor=''>Name of the contractor</td>";
$table = $table . "<td class='label labelheadblue' colspan='2'>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='' nowrap='nowrap' class='labelbold labelheadblue' valign='top' bgcolor=''>Agreement No.</td>";
$table = $table . "<td class='label labelheadblue' colspan='2'>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='' nowrap='nowrap' class='labelbold labelheadblue' valign='top' bgcolor=''>Work Order No.</td>";
$table = $table . "<td class='label labelheadblue' colspan='2'>" . $work_order_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr height=''>";
$table = $table . "<td width='' nowrap='nowrap' class='labelbold labelheadblue' valign='top' bgcolor=''>Running Account bill No.</td>";
$table = $table . "<td class='labelbold labelheadblue' width = '50px'>" . $runn_acc_bill_no . "</td>";
$table = $table . "<td class='labelbold labelheadblue'>CC No. " . $ccno . "</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
			
//$table1 = $table1 . "<table width='1087px' border='0' bgcolor='#FFFFFF' cellpadding='2' cellspacing='2' align='center'>";
$table1 = $table1 . "<tr height='' bgcolor='#e5e3e3'>";
$table1 = $table1 . "<td width='8%' rowspan='2' class='labelcenter labelheadblue'>Date of Measurment</td>";
$table1 = $table1 . "<td width='4%' rowspan='2' class='labelcenter labelheadblue'>Item No.</td>";
$table1 = $table1 . "<td width='15%' rowspan='2' class='labelcenter labelheadblue'>Description of work</td>";
$table1 = $table1 . "<td width='3%' rowspan='2' class='labelcenter labelheadblue'>Dia of Rod [mm]</td>";
$table1 = $table1 . "<td width='3%' rowspan='2' class='labelcenter labelheadblue'>Nos</td>";
$table1 = $table1 . "<td width='4%' rowspan='2' class='labelcenter labelheadblue' style='word-wrap:break-all'>Length<br/>[m]</td>";
$table1 = $table1 . "<td colspan='9' width='54%' class='labelcenter labelheadblue'>Total Length in Meters</td>";
$table1 = $table1 . "<td width='2%' rowspan='2' class='labelcenter labelheadblue' style='font-size:9px;'>Remark</td>";  //Remarks Field changed into Per.
$table1 = $table1 . "</tr>";
$table1 = $table1 . "<tr height='' bgcolor='#e5e3e3'>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>8</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>10</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>12</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>16</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>20</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>25</td>";
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>28</td>";            
$table1 = $table1 . "<td width='7%' class='labelcenter labelheadblue'>32</td>"; 
$table1 = $table1 . "<td width='6%' class='labelcenter labelheadblue'>36</td>";  
$table1 = $table1 . "</tr>";
// $table1 = $table1 . "</table>";
?>
<?php echo $table; $tablehead = $table;?>
<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF'>
<?php echo $table1; ?>
<?php

$currentline 		= 	$start_line + 8; 	$page 			= 	$mpage;		$pre_subdivid 		= 	""; 
$pre_staffid 		= 	""; 				$QtySum 		= 	0; 			$temp 				= 	0;
$OutPutStr			=	"";					$OutPutStr1 	=	"";			$OutPutStr2			=	"";

$DiaSum_8_item 		= 	0;					$DiaSum_10_item = 0;			$DiaSum_12_item 	= 	0;			$DiaSum_16_item 	= 	0;
$DiaSum_20_item 	= 	0;					$DiaSum_25_item = 0;			$DiaSum_28_item 	= 	0;			$DiaSum_32_item 	= 	0;
$DiaSum_36_item		=	0;

$mbook_compo_sql 	= 	"select * from mbookgenerate_staff where sheetid = '$sheetid' AND rbn = '$rbn' AND flag = 2 ORDER BY subdivid ASC, staffid ASC";
$mbook_compo_query	=	mysql_query($mbook_compo_sql);
if($mbook_compo_query == true)
{
	while($CompoList = mysql_fetch_object($mbook_compo_query))
	{
					if($page > 100)
					{
						if($_GET['varid'] == 1)
						{
							?>
							<div id="dialog" title="Choose MBook No." style="background-color:#f9f8f6;font-size: 12px;">
							<p style="font-size:12px; font-weight:bold; color:#911200;">Select Next MBook Number</p>
							<select id="newmbooklist" name="mb" style="width:275px;">
							<option value="">---------------------Select--------------------</option>
							<?php echo $objBind->BindMBookList($mbookno,$sheetid,$staffid,$mbooktype); ?>
							</select>
							<br/>
							<span id="error_msg" style="color:#FF0000; font-weight:bold;"></span>
							<input type="button" class="submit_btn" id="btn" style="color:#FFFFFF;background-color:#9c27b0;border:none;" name="btn" value="Submit"/>
							<input type="button" class="cancel_btn" id="btn_cancel" style="color:#FFFFFF;background-color:#e51c23;border:none;" name="btn_cancel" value="Cancel"/>
							</div>
							<?php
						}
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpage;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;
						
					}
///////////////////////=======================This part is for get all dia======================/////////////////////////////
$DiaAll = array(); $DiaAllStr = "";
$select_dia_sql 	= 	"select DISTINCT mbookdetail.measurement_dia from mbookdetail 
						INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
						INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
						WHERE mbookheader.sheetid = '$sheetid' 
						AND mbookheader.date  >= '$fromdate' 
						AND mbookheader.date  <= '$todate' 
						AND schdule.measure_type = 's'
						AND schdule.sheet_id = '$sheetid'
						AND mbookheader.mbheader_flag != 'd'
						AND mbookdetail.mbdetail_flag != 'd'
						AND mbookdetail.subdivid = '$CompoList->subdivid'
						AND mbookheader.staffid = '$CompoList->staffid'
						AND mbookdetail.measurement_dia != '' ORDER BY mbookdetail.measurement_dia ASC";
						//echo $select_dia_sql."<br/>";
$select_dia_query	=	mysql_query($select_dia_sql);
while($DiaList = mysql_fetch_object($select_dia_query))
{
	$Dia = $DiaList->measurement_dia;
	if($Dia != "")
	{
		$DiaAllStr	.=	$Dia."*";
		array_push($DiaAll,$Dia);
	}
}
///////////////////////===========This part is for get sum of contents area of each dia============//////////////////////////
$DiaSum_8 = "";	$DiaSum_10 = "";	$DiaSum_12 = "";	$DiaSum_16 = "";
$DiaSum_20 = "";	$DiaSum_25 = "";	$DiaSum_28 = "";	$DiaSum_32 = "";	$DiaSum_36 = "";
for($x1=0; $x1<count($DiaAll); $x1++)
{
	$select_diasum_sql 	= 	"select SUM(mbookdetail.measurement_contentarea) as diasum from mbookdetail 
						INNER JOIN mbookheader ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
						INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
						WHERE mbookheader.sheetid = '$sheetid' 
						AND mbookheader.date  >= '$fromdate' 
						AND mbookheader.date  <= '$todate' 
						AND schdule.measure_type = 's'
						AND schdule.sheet_id = '$sheetid'
						AND mbookdetail.measurement_dia = '$DiaAll[$x1]'
						AND mbookdetail.measurement_contentarea != ''
						AND mbookheader.mbheader_flag != 'd'
						AND mbookdetail.mbdetail_flag != 'd'
						AND mbookdetail.subdivid = '$CompoList->subdivid'
						AND mbookheader.staffid = '$CompoList->staffid'
						AND mbookdetail.measurement_dia != '' ORDER BY mbookdetail.measurement_dia ASC";
	$select_diasum_query	=	mysql_query($select_diasum_sql);
	//echo $select_diasum_sql."<br/>";
	$DiaSumList = mysql_fetch_object($select_diasum_query);
	$DiaSum	=	$DiaSumList->diasum;
	//echo $DiaAll[$x1]."<br/>";
	if($DiaAll[$x1] == 8)	{ $DiaSum_8 	= 	$DiaSum; }
	if($DiaAll[$x1] == 10)	{ $DiaSum_10 	= 	$DiaSum; }
	if($DiaAll[$x1] == 12)	{ $DiaSum_12 	= 	$DiaSum; }
	if($DiaAll[$x1] == 16)	{ $DiaSum_16 	= 	$DiaSum; }
	if($DiaAll[$x1] == 20)	{ $DiaSum_20 	= 	$DiaSum; }
	if($DiaAll[$x1] == 25)	{ $DiaSum_25 	= 	$DiaSum; }
	if($DiaAll[$x1] == 28)	{ $DiaSum_28	= 	$DiaSum; }
	if($DiaAll[$x1] == 32)	{ $DiaSum_32	= 	$DiaSum; }
	if($DiaAll[$x1] == 36)	{ $DiaSum_36 	= 	$DiaSum; }
}


		$ItemData		=	getItemDetails($sheetid,$CompoList->subdivid);
		$ExplodeData	=	explode("##@**@##",$ItemData);
		$subdivname		=	$ExplodeData[0];
		$ItemUnit		=	$ExplodeData[2];
		$decimal		=	$ExplodeData[3];
		$fromdate 		=	$CompoList->fromdate;
		$todate 		=	$CompoList->todate;
		$createDate 	= 	new DateTime($todate);
		$todate 		= 	$createDate->format('Y-m-d');
		if(($pre_subdivid != "") && ($pre_staffid != ""))
		{
			if($pre_subdivid != $CompoList->subdivid)
			{
				$temp 	= 	1;
			}
			if($currentline>25)
			{
			
			$x1 = "<tr>";
			$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$page."</td>";
			$x1 = $x1."</tr>";
			$x1 = $x1."</table>";
			$x1 = $x1."<p  style='page-break-after:always;'></p>";
			$x1 = $x1.'<table width="1087px" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
					<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
					</table>';
			$x1 = $x1.$tablehead; 
			$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF">';
			$x1 = $x1.$table1;
			echo $x1;
?>
			<!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "C/o to Page "." /MBook No. "; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   	</tr>-->
			<!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "B/f from Page "." /MBook No. "; ?></td>
				<td width='7%'><?php echo $DiaSum_8; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
<?php		
				$currentline = $start_line + 8; $page++;	
			}
			if($temp == 1)
			{
			
		if($DiaSum_8_item != "") { $total_weight_8  = round(($DiaSum_8_item  * 0.395),$pre_decimal); }
		if($DiaSum_10_item != ""){ $total_weight_10 = round(($DiaSum_10_item * 0.617),$pre_decimal); }
		if($DiaSum_12_item != ""){ $total_weight_12 = round(($DiaSum_12_item * 0.888),$pre_decimal); }
		if($DiaSum_16_item != ""){ $total_weight_16 = round(($DiaSum_16_item * 1.578),$pre_decimal); }
		if($DiaSum_20_item != ""){ $total_weight_20 = round(($DiaSum_20_item * 2.466),$pre_decimal); }
		if($DiaSum_25_item != ""){ $total_weight_25 = round(($DiaSum_25_item * 3.853),$pre_decimal); }
		if($DiaSum_28_item != ""){ $total_weight_28 = round(($DiaSum_28_item * 4.834),$pre_decimal); }
		if($DiaSum_32_item != ""){ $total_weight_32 = round(($DiaSum_32_item * 6.313),$pre_decimal); }
		if($DiaSum_36_item != ""){ $total_weight_36 = round(($DiaSum_36_item * 7.990),$pre_decimal); }
		$totalweight_KGS 	= 	round(($total_weight_8+$total_weight_10+$total_weight_12+$total_weight_16+$total_weight_20+$total_weight_25+$total_weight_28+$total_weight_32+$total_weight_36),$pre_decimal);
		$totalweight_MT 	= 	round(($totalweight_KGS/1000),$pre_decimal);
?>

			<!--<tr height=''>
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='15%'>&nbsp;</td>
				<td width='3%'>&nbsp;</td>
				<td width='3%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
		   <tr height='' class="label">
				<td width='8%'><?php //echo dt_display($todate); ?></td>
				<td width='4%'><?php //echo $subdivname; ?></td>
				<td width='25%' colspan="4" align="right"><?php echo "Sub Total";//echo "B/f from page ".$CompoList->mbpage." Steel MBook No.".$CompoList->mbno; ?>&nbsp;</td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_8_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_10_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_12_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_16_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_20_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_25_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_28_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_32_item,$decimal,".",","); ?></td>
				<td width='6%' align="right"><?php echo number_format($DiaSum_36_item,$decimal,".",","); ?></td>
				<td width='2%'>&nbsp;</td>
		   	</tr>
			<tr height='' class="label">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' align="right" colspan="4">Unit Weight&nbsp;</td>
				<td width='7%' align="right">0.395</td>
				<td width='7%' align="right">0.617</td>
				<td width='7%' align="right">0.888</td>
				<td width='7%' align="right">1.578</td>
				<td width='7%' align="right">2.466</td>
				<td width='7%' align="right">3.853</td>
				<td width='7%' align="right">4.834</td>
				<td width='7%' align="right">6.313</td>
				<td width='6%' align="right">7.990</td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>
			<tr height='' class="label">
				<td width='8%' align="right">&nbsp;</td>
				<td width='4%' align="right">&nbsp;</td>
				<td width='25%' align="right" colspan="4">Total Weight&nbsp;</td>
				<td width='7%' align="right"><?php echo $total_weight_8; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_10; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_12; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_16; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_20; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_25; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_28; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_32; ?></td>
				<td width='6%' align="right"><?php echo $total_weight_36; ?></td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>
			<tr height='' bgcolor="" class="label labelbold">
                <td width=''></td>
                <td width=''></td>
                <td width='' colspan="4" align="right">Total ( in kg )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_KGS,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="left">&nbsp;</td>
                <td width='' align="left"></td>
          	</tr>
			<tr height='' bgcolor="" class="label labelbold">
                <td width='' class='labelcenter'></td>
                <td width='' class='labelcenter'></td>
                <td width='' colspan="4" align="right">Total ( in mt )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_MT,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="right"><?php //echo "C/o to page 1"." /Abstract MBook No. 1006"; ?></td>
                <td width='' align="left"></td>
         	</tr>
			
<?php 
				$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$totalweight_MT."@";
				//echo $OutPutStr1;
				$QtySum = 0; $temp = 0; $currentline = $currentline + 5;
				$DiaSum_8_item 		= 	0;					$DiaSum_10_item = 0;			$DiaSum_12_item 	= 	0;			$DiaSum_16_item 	= 	0;
				$DiaSum_20_item 	= 	0;					$DiaSum_25_item = 0;			$DiaSum_28_item 	= 	0;			$DiaSum_32_item 	= 	0;
				$DiaSum_36_item		=	0;
			}
		}
		if($currentline>25)
		{
			$x1 = "<tr>";
			$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$page."</td>";
			$x1 = $x1."</tr>";
			$x1 = $x1."</table>";
			$x1 = $x1."<p  style='page-break-after:always;'></p>";
			$x1 = $x1.'<table width="1087px" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
					<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
					</table>';
			$x1 = $x1.$tablehead; 
			$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF">';
			$x1 = $x1.$table1;
			echo $x1;
		
?>
			<!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "C/o to Page "." /MBook No. "; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
		   <!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "B/f from Page "." /MBook No. "; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
<?php
			$currentline = $start_line + 8; $page++;
		}
		/*if($DiaSum_8 != "") { $total_weight_8  = round(($DiaSum_8  * 0.395),$decimal); }
		if($DiaSum_10 != ""){ $total_weight_10 = round(($DiaSum_10 * 0.617),$decimal); }
		if($DiaSum_12 != ""){ $total_weight_12 = round(($DiaSum_12 * 0.888),$decimal); }
		if($DiaSum_16 != ""){ $total_weight_16 = round(($DiaSum_16 * 1.578),$decimal); }
		if($DiaSum_20 != ""){ $total_weight_20 = round(($DiaSum_20 * 2.466),$decimal); }
		if($DiaSum_25 != ""){ $total_weight_25 = round(($DiaSum_25 * 3.853),$decimal); }
		if($DiaSum_28 != ""){ $total_weight_28 = round(($DiaSum_28 * 4.834),$decimal); }
		if($DiaSum_32 != ""){ $total_weight_32 = round(($DiaSum_32 * 6.313),$decimal); }
		if($DiaSum_36 != ""){ $total_weight_36 = round(($DiaSum_36 * 7.990),$decimal); }
		$totalweight_KGS 	= 	round(($total_weight_8+$total_weight_10+$total_weight_12+$total_weight_16+$total_weight_20+$total_weight_25+$total_weight_28+$total_weight_32+$total_weight_36),$decimal);
		$totalweight_MT 	= 	round(($totalweight_KGS/1000),$decimal);*/
?>
			<tr height='' class="label">
				<td width='8%'><?php echo dt_display($todate); ?></td>
				<td width='4%'><?php echo $subdivname; ?></td>
				<td width='25%' colspan="4"><?php echo "B/f from page ".$CompoList->mbpage." Steel MBook No.".$CompoList->mbno; ?>&nbsp;</td>
				<td width='7%' align="right"><?php if(($DiaSum_8 != 0) &&($DiaSum_8 != "")) { echo number_format($DiaSum_8,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_10 != 0)&&($DiaSum_10 != "")){ echo number_format($DiaSum_10,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_12 != 0)&&($DiaSum_12 != "")){ echo number_format($DiaSum_12,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_16 != 0)&&($DiaSum_16 != "")){ echo number_format($DiaSum_16,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_20 != 0)&&($DiaSum_20 != "")){ echo number_format($DiaSum_20,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_25 != 0)&&($DiaSum_25 != "")){ echo number_format($DiaSum_25,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_28 != 0)&&($DiaSum_28 != "")){ echo number_format($DiaSum_28,$decimal,".",","); } ?></td>
				<td width='7%' align="right"><?php if(($DiaSum_32 != 0)&&($DiaSum_32 != "")){ echo number_format($DiaSum_32,$decimal,".",","); } ?></td>
				<td width='6%' align="right"><?php if(($DiaSum_36 != 0)&&($DiaSum_36 != "")){ echo number_format($DiaSum_36,$decimal,".",","); } ?></td>
				<td width='2%'>&nbsp;</td>
		   	</tr>
<?php

if(($DiaSum_8 != 0)  && ($DiaSum_8 != ""))  { $DiaSum_8_item  = $DiaSum_8_item  + $DiaSum_8; }
if(($DiaSum_10 != 0) && ($DiaSum_10 != "")) { $DiaSum_10_item = $DiaSum_10_item + $DiaSum_10; }
if(($DiaSum_12 != 0) && ($DiaSum_12 != "")) { $DiaSum_12_item = $DiaSum_12_item + $DiaSum_12; }
if(($DiaSum_16 != 0) && ($DiaSum_16 != "")) { $DiaSum_16_item = $DiaSum_16_item + $DiaSum_16; }
if(($DiaSum_20 != 0) && ($DiaSum_20 != "")) { $DiaSum_20_item = $DiaSum_20_item + $DiaSum_20; }
if(($DiaSum_25 != 0) && ($DiaSum_25 != "")) { $DiaSum_25_item = $DiaSum_25_item + $DiaSum_25; }
if(($DiaSum_28 != 0) && ($DiaSum_28 != "")) { $DiaSum_28_item = $DiaSum_28_item + $DiaSum_28; }
if(($DiaSum_32 != 0) && ($DiaSum_32 != "")) { $DiaSum_32_item = $DiaSum_32_item + $DiaSum_32; }
if(($DiaSum_36 != 0) && ($DiaSum_36 != "")) { $DiaSum_36_item = $DiaSum_36_item + $DiaSum_36; }
//$DiaSum_8 = "";	$DiaSum_10 = "";	$DiaSum_12 = "";	$DiaSum_16 = "";
//$DiaSum_20 = "";	$DiaSum_25 = "";	$DiaSum_28 = "";	$DiaSum_32 = "";	$DiaSum_36 = "";
?>
		   	<!--<tr height='' class="label">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' align="right" colspan="4">Unit Weight&nbsp;</td>
				<td width='7%' align="right">0.395</td>
				<td width='7%' align="right">0.617</td>
				<td width='7%' align="right">0.888</td>
				<td width='7%' align="right">1.578</td>
				<td width='7%' align="right">2.466</td>
				<td width='7%' align="right">3.853</td>
				<td width='7%' align="right">4.834</td>
				<td width='7%' align="right">6.313</td>
				<td width='6%' align="right">7.990</td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>-->
		   <!--	<tr height='' class="label">
				<td width='8%' align="right">&nbsp;</td>
				<td width='4%' align="right">&nbsp;</td>
				<td width='25%' align="right" colspan="4">Total Weight&nbsp;</td>
				<td width='7%' align="right"><?php echo $total_weight_8; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_10; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_12; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_16; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_20; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_25; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_28; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_32; ?></td>
				<td width='6%' align="right"><?php echo $total_weight_36; ?></td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>-->
		   	<!--<tr height='' bgcolor="" class="label labelbold">
                <td width=''></td>
                <td width=''></td>
                <td width='' colspan="4" align="right">Total ( in kg )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_KGS,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="left">&nbsp;</td>
                <td width='' align="left"></td>
          	</tr>-->
		  	<!--<tr height='' bgcolor="" class="label labelbold">
                <td width='' class='labelcenter'></td>
                <td width='' class='labelcenter'></td>
                <td width='' colspan="4" align="right">Total ( in mt )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_MT,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="right"><?php echo "C/o to page 1"." /Abstract MBook No. 1006"; ?></td>
                <td width='' align="left"></td>
         	</tr>-->
<?php
		$currentline++;
		$pre_divid 		= 	$CompoList->divid;
		$pre_subdivid 	= 	$CompoList->subdivid;
		$pre_staffid 	= 	$CompoList->staffid;
		$pre_mbpage 	= 	$CompoList->mbpage;
		$pre_mbno 		= 	$CompoList->mbno;
		$pre_mbtotal 	= 	$CompoList->mbtotal;
		$pre_fromdate 	= 	$CompoList->fromdate;
		$pre_todate 	= 	$CompoList->todate;
		$pre_ItemUnit	=	$ItemUnit;
		$QtySum			=	$QtySum + $CompoList->mbtotal;
		$pre_QtySum 	= 	$QtySum;
		$pre_decimal	=	$decimal;
	}
		if($DiaSum_8_item != "") { $total_weight_8  = round(($DiaSum_8_item  * 0.395),$pre_decimal); }else { $total_weight_8 = ""; } 
		if($DiaSum_10_item != ""){ $total_weight_10 = round(($DiaSum_10_item * 0.617),$pre_decimal); }else { $total_weight_10 = ""; }
		if($DiaSum_12_item != ""){ $total_weight_12 = round(($DiaSum_12_item * 0.888),$pre_decimal); }else { $total_weight_12 = ""; }
		if($DiaSum_16_item != ""){ $total_weight_16 = round(($DiaSum_16_item * 1.578),$pre_decimal); }else { $total_weight_16 = ""; }
		if($DiaSum_20_item != ""){ $total_weight_20 = round(($DiaSum_20_item * 2.466),$pre_decimal); }else { $total_weight_20 = ""; }
		if($DiaSum_25_item != ""){ $total_weight_25 = round(($DiaSum_25_item * 3.853),$pre_decimal); }else { $total_weight_25 = ""; }
		if($DiaSum_28_item != ""){ $total_weight_28 = round(($DiaSum_28_item * 4.834),$pre_decimal); }else { $total_weight_28 = ""; }
		if($DiaSum_32_item != ""){ $total_weight_32 = round(($DiaSum_32_item * 6.313),$pre_decimal); }else { $total_weight_32 = ""; }
		if($DiaSum_36_item != ""){ $total_weight_36 = round(($DiaSum_36_item * 7.990),$pre_decimal); }else { $total_weight_36 = ""; }
		$totalweight_KGS 	= 	round(($total_weight_8+$total_weight_10+$total_weight_12+$total_weight_16+$total_weight_20+$total_weight_25+$total_weight_28+$total_weight_32+$total_weight_36),$pre_decimal);
		$totalweight_MT 	= 	round(($totalweight_KGS/1000),$pre_decimal);
if($currentline>25)
{

			$x1 = "<tr>";
			$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$page."</td>";
			$x1 = $x1."</tr>";
			$x1 = $x1."</table>";
			$x1 = $x1."<p  style='page-break-after:always;'></p>";
			$x1 = $x1.'<table width="1087px" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
					<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
					</table>';
			$x1 = $x1.$tablehead; 
			$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF">';
			$x1 = $x1.$table1;
			echo $x1;
?>
			<!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "C/o to Page "." /MBook No. "; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
		   <!--<tr height='' class="label labelheadblue">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' colspan="4"><?php echo "B/f from Page "." /MBook No. "; ?></td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='7%'>&nbsp;</td>
				<td width='6%'>&nbsp;</td>
				<td width='2%'>&nbsp;</td>
		   </tr>-->
<?php
	$currentline = $start_line + 8; $page++;
}
?>
			<tr height='' class="label">
				<td width='8%'><?php //echo dt_display($todate); ?></td>
				<td width='4%'><?php //echo $subdivname; ?></td>
				<td width='25%' colspan="4" align="right"><?php echo "Sub Total";//echo "B/f from page ".$CompoList->mbpage." Steel MBook No.".$CompoList->mbno; ?>&nbsp;</td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_8_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_10_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_12_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_16_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_20_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_25_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_28_item,$decimal,".",","); ?></td>
				<td width='7%' align="right"><?php echo number_format($DiaSum_32_item,$decimal,".",","); ?></td>
				<td width='6%' align="right"><?php echo number_format($DiaSum_36_item,$decimal,".",","); ?></td>
				<td width='2%'>&nbsp;</td>
		   	</tr>
			<tr height='' class="label">
				<td width='8%'>&nbsp;</td>
				<td width='4%'>&nbsp;</td>
				<td width='25%' align="right" colspan="4">Unit Weight&nbsp;</td>
				<td width='7%' align="right">0.395</td>
				<td width='7%' align="right">0.617</td>
				<td width='7%' align="right">0.888</td>
				<td width='7%' align="right">1.578</td>
				<td width='7%' align="right">2.466</td>
				<td width='7%' align="right">3.853</td>
				<td width='7%' align="right">4.834</td>
				<td width='7%' align="right">6.313</td>
				<td width='6%' align="right">7.990</td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>
			<tr height='' class="label">
				<td width='8%' align="right">&nbsp;</td>
				<td width='4%' align="right">&nbsp;</td>
				<td width='25%' align="right" colspan="4">Total Weight&nbsp;</td>
				<td width='7%' align="right"><?php echo $total_weight_8; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_10; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_12; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_16; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_20; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_25; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_28; ?></td>
				<td width='7%' align="right"><?php echo $total_weight_32; ?></td>
				<td width='6%' align="right"><?php echo $total_weight_36; ?></td>
				<td width='2%' align="right">&nbsp;</td>
		   	</tr>
			<tr height='' bgcolor="" class="label labelbold">
                <td width=''></td>
                <td width=''></td>
                <td width='' colspan="4" align="right">Total ( in kg )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_KGS,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="left">&nbsp;</td>
                <td width='' align="left"></td>
          	</tr>
			<tr height='' bgcolor="" class="label labelbold">
                <td width='' class='labelcenter'></td>
                <td width='' class='labelcenter'></td>
                <td width='' colspan="4" align="right">Total ( in mt )&nbsp;</td>
                <td width='' colspan="3" align="right"><?php echo number_format($totalweight_MT,$decimal,".",","); ?></td>
				<td width='' colspan="6" align="right"><?php //echo "C/o to page 1"." /Abstract MBook No. 1006"; ?></td>
                <td width='' align="left"></td>
         	</tr>
			
<?php	
	$currentline = $currentline+5;
	//echo $currentline;
	$lineTemp = 25-$currentline;
	//echo "H".$lineTemp;
?>
		<tr style="border-style:none" class="label">
					<td colspan="16" style="border-style:none" align="center">
					<?php 
						for($x2=$currentline; $x2<25; $x2++)
						{
							echo "<br/>";
						}
					?>
					<?php echo "Page ".$page; ?>
					</td>
				</tr>
<?php
	//$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$totalweight_MT."@";
	$OutPutStr2 	=  	$pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$totalweight_MT;
	$OutPutStr		=	$OutPutStr1.$OutPutStr2;	
	//echo $OutPutStr;
	$DeleteSql		=	"DELETE FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = 2";
	$DeleteQuery	=	mysql_query($DeleteSql);
	$ExplodeResult	=	explode("@",$OutPutStr);
	for($x1=0; $x1<count($ExplodeResult); $x1++)
	{
		$Res1		=	$ExplodeResult[$x1];
		$ExpRes1	=	explode("*",$Res1);
		$divid 		= 	$ExpRes1[0];
		$subdivid 	= 	$ExpRes1[1];
		$fromdate 	= 	$ExpRes1[2];
		$todate 	= 	$ExpRes1[3];
		$mbookpage 	= 	$ExpRes1[4];
		$mbookno 	= 	$ExpRes1[5];
		$ItemQty 	= 	$ExpRes1[6];
		$insertMbgenerate_sql 	= 	"insert into mbookgenerate (mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, abstmbookno, mbtotal, pay_percent, flag, rbn, active, userid) 
														values (NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate', '$todate', '$mbookno', '$mbookpage', '$abstmbookno', '$ItemQty', '0', '2', '$rbn', '1', '$userid')";
		//echo $insertMbgenerate_sql."<br/>";
		$insertMbgenerate_query	=	mysql_query($insertMbgenerate_sql);
	}
}
$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$sheetid' and staffid = '$staffid' and mtype = 'S' and genlevel = 'composite'";
$delete_mymbook_query = mysql_query($delete_mymbook_sql);
if($newmbookno == "")
{
	$insert_mymbook_sql = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '$page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'composite', mbookorder = 1, active = 1";
	$insert_mymbook_query = mysql_query($insert_mymbook_sql);
}
else
{
	$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '100', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'composite', mbookorder = 1, active = 1";
	$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
	$insert_mymbook_sql2 = "insert into mymbook set mbno = '$newmbookno', startpage = '$newmbookpage', endpage = '$page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'composite', mbookorder = 2, active = 1";
	$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
}
?>
</table>
<table align="center" style="border:none;" class="printbutton">
                <tr style="border:none">
                   <td align="center" colspan="15" style="border:none;"><br/>
				   <input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></td>
                </tr>
            </table>	
 </form>
</body>
</html>