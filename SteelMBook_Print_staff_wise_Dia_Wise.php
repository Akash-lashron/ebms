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
$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;
$zonename = $_SESSION['zonename'];
if($_GET['workno'] != "")
{
	$sheetid = $_GET['workno'];
}
if($_POST['back'])
{
    header('Location: MeasurementBookPrint_staff.php');
}
$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '2'";
//echo $select_rbn_query;exit;
$select_rbn_sql = mysql_query($select_rbn_query);
$Rbnresult = mysql_fetch_object($select_rbn_sql);
$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '2' AND rbn = '$rbn'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'S' AND sheetid = '$sheetid' AND staffid = '$staffid'";
$selectmbookno_sql = mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn'";
	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql = mysql_query($newmbookpage);
	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
	
//$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
//$newmbookpageno = $newmbookpageno +1;
}
else
{
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND staffid = '$staffid' AND rbn = '$rbn'";
	//echo $selectmbookno;
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	//echo "hai";
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
}
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;
$mpage = $mbookpageno;
//echo "MBno".$mbookno;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];  
//$mpage = $_SESSION["mb_page"]; 
//$rbn = $_SESSION["rbn"];
//$steelmbno_id = $_SESSION["mbno_id"];
//$temp_sql = "DELETE FROM temp WHERE flag =3 OR flag =2 AND usersid = '$userid'";
//echo $temp_sql;exit;
         //$res_query = dbQuery($temp_sql);
//$Mbsteelgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =2 AND staffid = '$staffid'";
//$Mbsteelgeneratedelsql_qry = mysql_query($Mbsteelgeneratedelsql);
/*function MeasurementSteelinsert($fromdate,$todate,$sheetid,$mbookno,$mpage,$totalweight_MT,$rbn,$userid,$subdivid,$divid,$staffid)
{  
   
   $querys="INSERT INTO mbookgenerate set staffid = '$staffid', sheetid='$sheetid',divid='$divid',subdivid='$subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=2,rbn='$rbn',
            mbgeneratedate=NOW(), mbpage='$mpage', mbtotal='$totalweight_MT', active=1, userid='$userid'";
 //echo $querys."<br/>";
   $sqlquerys = mysql_query($querys);
}*/
function check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1)
{
		if($mpage == 100) { $mbookno = $newmbookno; }
		$x1 = "<tr>";
		$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
		$x1 = $x1."</tr>";
		$x1 = $x1."</table>";
		$x1 = $x1."<p  style='page-break-after:always;'></p>";
		$x1 = $x1.'<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
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
	$row_co = $row_co."<td width='' colspan='6' class='labelbold' style='text-align:right'>"."C/o to Page ".($page+1)."/ Steel MB No. ".$mbookno."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot8."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot10."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot12."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot16."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot20."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot25."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot28."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot32."</td>";
	$row_co = $row_co."<td width='6%' class='labelbold' style='text-align:right'>".$tot36."</td>";
	$row_co = $row_co."<td width='2%' class='labelbold'></td>";
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
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query = "select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid' AND staffid = '$staffid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$abstmbookno = @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage = @mysql_result($select_abs_page_sql,0,'abstmbpage');
	echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function getcompositepage($sheetid,$subdivid)
{
	$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
	$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	return "C/o to Page ".$mbookpageno_compo." /MBook No. ".$mbookno_compo;
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
		<script src="js/printPage.js"></script>
  <!--<script>
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
  </script>-->
	<style type="text/css" media="print,screen" >
	table{ border-collapse: collapse; }
	td { border: 1px solid #CACACA; }
	@media screen {
        div.divFooter {
            display: none;
        }
    }
    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
			size: landscape;
        } 
		.header{
		display: none !important;
		}
		.printbutton{
		display: none !important;
		}}
.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
.labelcontent
{
	font-family:Microsoft New Tai Lue;
	font-size:12pt;
	color:#000000;
}
.ui-dialog-titlebar-close {
  visibility: hidden;
}
.submit_btn{
position:absolute;
border:none;
top:110px;
left:80px;
font-weight:bold;
font-size:12px;
}
.cancel_btn{
position:absolute;
border:none;
top:110px;
left:160px;
font-weight:bold;
font-size:12px;
}
.submit_btn:hover {
     color:#000000;
     -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
     -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
      box-shadow:0px 1px 4px rgba(0,0,0,5);
	  padding: 0.3em 1em;
  }
  .cancel_btn:hover {
     color:#000000;
     -moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
     -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
      box-shadow:0px 1px 4px rgba(0,0,0,5);
	  padding: 0.3em 1em;
  }
  .headingfont{
 /*color:#FFFFFF;*/
  }
  .label, .labelcenter, .labelheadblue
{
	font-size:12px;
}
</style>
<!--<script type="text/javascript">
                            //var value = prompt("Enter MBook value", "");
var variable='';                           
   $(function(){
        $("#test").dialog({ autoOpen: false,
        minHeight: 200,
        maxHeight:200,
        minWidth: 300,
        maxWidth: 300,
        modal: true,});
        $("#test").dialog("open");
        $( "#test" ).dialog( "option", "draggable", false );
       
        $('#btn').click(function(){
        $("#test").dialog("close");       
        var newmbookvalue = $("#newmbooklist option:selected").text();      
        document.getElementById("newmbook").value =newmbookvalue;
         $.post("GetNewMbookNo.php", {currentmbook: newmbookvalue}, function (data) {
           //window.history.replaceState(null, null, "MBook.php");
           pageurl ="MBook.php";
             //window.history.back();
          location.reload();
           // window.history.back();

    return false; // avoid to execute the actual submit of the form.
          
         });
         });
});
  </script> -->
  <SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
		function goBack()
		{
			url = "MeasurementBookPrint_staff.php";
			window.location.replace(url);
		}
	</SCRIPT>
    <body bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="1087px" style="position:fixed; text-align:center; left:88px;" height="60px" align="center" bgcolor="#20b2aa" class='header'>
<tr>
<td style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">STEEL MEASUREMENT BOOK</td>
</tr>
</table><br/><br/><br/>-->
        <form name="form" method="post" style="">
		<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>" />
		<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" />
		<input type="hidden" name="hid_userid" id="hid_userid" value="<?php echo $userid; ?>" />
		<input type="hidden" name="txt_steelmbno_id" value="<?php echo $steelmbno_id."*".$mbookno."*"."S"."*".$staffid."*".$sheetid; ?>" id="txt_steelmbno_id" />
<?php
			$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td colspan="9" align="right" style="border:none;">Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
            echo $title;
            $table = "<table width='1087px' border='0'  cellpadding='2' cellspacing='2' align='center' bgcolor='#FFFFFF'>";
			$table = $table . "<tr height=''>";
            $table = $table . "<td width='20%' nowrap='nowrap' class='labelbold' bgcolor=''>Name of work:</td>";
            $table = $table . "<td width='80%' class='label' colspan='3'  style='word-wrap:break-word;'>" . $work_name . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr height=''>";
            $table = $table . "<td width='' nowrap='nowrap' class='labelbold' valign='top' bgcolor=''>Technical Sanction No.</td>";
            $table = $table . "<td class='label' colspan='3'> " . $tech_sanction . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr height=''>";
            $table = $table . "<td width='' nowrap='nowrap' class='labelbold' valign='top' bgcolor=''>Name of the contractor</td>";
            $table = $table . "<td class='label' colspan='3'>" . $name_contractor . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr height=''>";
            $table = $table . "<td width='' nowrap='nowrap' class='labelbold' valign='top' bgcolor=''>Agreement No.</td>";
            $table = $table . "<td class='label' colspan='3'>" . $agree_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr height=''>";
            $table = $table . "<td width='' nowrap='nowrap' class='labelbold' valign='top' bgcolor=''>Work Order No.</td>";
            $table = $table . "<td class='label' colspan='3'>" . $work_order_no . "</td>";
            $table = $table . "</tr>";
            $table = $table . "<tr height=''>";
            $table = $table . "<td width='' nowrap='nowrap' class='labelbold' valign='top' bgcolor=''>Running Account bill No.</td>";
            $table = $table . "<td class='label' width = '150px'>" . $runn_acc_bill_no . "&nbsp;&nbsp;(".$zonename.")&nbsp;&nbsp;"."</td>";
			$table = $table . "<td class='labelbold labelheadblue' align='right' width = '50px'>CC No.&nbsp;</td>";
			$table = $table . "<td class='label' width = '150px'>". $ccno . "</td>";
            $table = $table . "</tr>";
            $table = $table . "</table>";
			
            //$table1 = $table1 . "<table width='1087px' border='0' bgcolor='#FFFFFF' cellpadding='1' cellspacing='1' align='center'>";
            $table1 = $table1 . "<tr height='' bgcolor='#e5e3e3'>";
            $table1 = $table1 . "<td width='8%' rowspan='2' class='labelcenter labelbold'>Date of Measurment</td>";
            $table1 = $table1 . "<td width='4%' rowspan='2' class='labelcenter labelbold'>Item No.</td>";
            $table1 = $table1 . "<td width='25%' rowspan='2' class='labelcenter labelbold'>Description of work</td>";
            $table1 = $table1 . "<td width='3%' rowspan='2' class='labelcenter labelbold'>Dia of Rod [mm]</td>";
            $table1 = $table1 . "<td width='3%' rowspan='2' class='labelcenter labelbold'>Nos</td>";
            $table1 = $table1 . "<td width='4%' rowspan='2' class='labelcenter labelbold' style='word-wrap:break-all'>Length<br/>[m]</td>";
            $table1 = $table1 . "<td colspan='9' width='54%' class='labelcenter labelbold'>Total Length in Meters</td>";
            $table1 = $table1 . "<td width='2%' rowspan='2' class='labelcenter labelbold' style='font-size:9px;'>Remarks</td>";  //Remarks Field changed into Per.
            $table1 = $table1 . "</tr>";
            $table1 = $table1 . "<tr height='' bgcolor='#e5e3e3'>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>8</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>10</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>12</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>16</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>20</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>25</td>";
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>28</td>";            
            $table1 = $table1 . "<td width='7%' class='labelcenter labelbold'>32</td>"; 
			$table1 = $table1 . "<td width='6%' class='labelcenter labelbold'>36</td>";  
            $table1 = $table1 . "</tr>";
            //$table1 = $table1 . "</table>";
            ?>
            <?php echo $table; $tablehead = $table;?>

            <table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF'>
			<?php echo $table1; ?>
                <?php 
                $prevdate = "";
                $prevsubdiv_name = ""; 
                $tmb = ""; $temp = 0; $currentline = $start_line + 13; $txtboxid = 1;
              // $measurequery = "SELECT  * FROM mbookdetail WHERE subdivid = 7 AND remarks = 'MT'";
                $measurequery = "SELECT DATE_FORMAT( mbookheader.date , '%d/%m/%Y' ) AS date ,  mbookdetail.subdivid , mbookdetail.mbdetail_flag, subdivision.subdiv_name , subdivision.div_id , 
								mbookdetail.descwork, mbookdetail.measurement_no , mbookdetail.measurement_l ,
								mbookdetail.measurement_dia , mbookdetail.measurement_contentarea , schdule.shortnotes, schdule.description, schdule.measure_type, mbookdetail.remarks, mbookheader.sheetid 
								FROM mbookheader
								INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
								INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
								INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND schdule.measure_type = 's' AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' AND mbookheader.staffid = '$staffid' ORDER BY mbookheader.date, mbookheader.mbheaderid, mbookdetail.mbdetail_id ASC";
               //echo $measurequery;
			   $sqlmeasurequery = mysql_query($measurequery);    
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                { //echo $mpage;
					if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
					$decimal = get_decimal_placed($List->subdivid,$sheetid);
					
					
					$measurementdia=$List->measurement_dia;
                    $NOS=chop($List->measurement_no);
                    $LOM=chop($List->measurement_l);
                    $totaldia=trim($NOS*$LOM);
                    
                        if($prevsubdiv_name == "")
                        {
                        ?>
                            <tr height=''>
                                <td width='' class='labelcenter'><?php echo $List->date; ?></td>
                                <td width='' class='labelcenter'><?php echo "8.00"; ?></td>
                                <td width='' colspan="14" class='labelcenter' style="text-align:left;"><?php echo "Steel Reinforcement"; ?></td>
                            </tr>
                  <?php 
				  		$length1 = strlen($List->shortnotes);
						$linecnt1 = ceil($length1/145);
				  		$currentline = $currentline + $linecnt1;
                        }
                    ?>
				<?php
				if(($List->date != $prevdate) && ($prevdate != ""))
				{
				?>
                <tr style="border-style:none;">
					<td style="border-style:none;" colspan="3" align="right" class="label">&nbsp;</td>
					<td style="border-style:none;" colspan="5" align="left" class="label"><?php echo "<br/><br/>";//echo "Approved By"; ?></td>
					<td style="border-style:none;" colspan="5" align="left" class="label"><?php echo "<br/><br/>";echo "Checked By"; ?></td>
					<td style="border-style:none;" colspan="4" align="center" class="label"><?php echo "<br/><br/>";echo "Prepared By"; ?></td>
				</tr>
				<?php
				$currentline = $currentline +1;
				}
				?>
                <tr height=''>
                    <td width='8%' class='labelcenter'><?php if(($List->date != $prevdate) && ($prevdate != "")){ echo $List->date; } ?></td>
                    <td width='4%' class='labelcenter'><?php //echo $List->subdiv_name;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } ?></td>
                    <td width='15%' class='labelcenter' style="text-align:left;word-wrap:break-word;"><?php echo $List->descwork; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_dia != 0) { echo $List->measurement_dia; } ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
                    <td width='4%' class='labelcenter' style="text-align:right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,3,".",","); } ?></td>
                    <?php
        if($measurementdia == 8){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 8; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaeight+=$totaldia; }
                else { ?><td width='7%' class='labelcenter'></td> <?php }
        if($measurementdia == 10){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 10; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaten+=$totaldia; }    
                else { ?><td width='7%' class='labelcenter'></td> <?php }           
        if($measurementdia == 12){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 12; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwelve+=$totaldia; }                
                else { ?><td width='7%' class='labelcenter'></td> <?php }         
        if($measurementdia == 16){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 16; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiasixteen+=$totaldia; }  
                else { ?><td width='7%' class='labelcenter'></td> <?php }    
        if($measurementdia == 20){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 20; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwenty+=$totaldia; }      
                else { ?><td width='7%' class='labelcenter'></td> <?php }      
        if($measurementdia == 25){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 25; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyfive+=$totaldia; }     
                else { ?><td width='7%' class='labelcenter'></td> <?php }  
        if($measurementdia == 28){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 28; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyeight+=$totaldia; }     
                else { ?><td width='7%' class='labelcenter'></td> <?php }   
        if($measurementdia == 32){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 32; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtytwo+=$totaldia; }             
                else { ?><td width='7%' class='labelcenter'></td> <?php }
		if($measurementdia == 36){ ?><td width='6%' class='labelcenter' style="text-align:right"><?php $dia = 36; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtysix+=$totaldia; }             
                else { ?><td width='6%' class='labelcenter'></td> <?php }		                
                  ?> 
                     <td width='2%' class='labelcenter'><?php echo "&nbsp";//$List->remarks; ?></td>
                </tr>
                <?php
               
			   if($dia == 8) { $total_dia_8 =  $total_dia_8 + $totaldia; }
			   if($dia == 10) { $total_dia_10 =  $total_dia_10 + $totaldia; }
			   if($dia == 12) { $total_dia_12 =  $total_dia_12 + $totaldia; }
			   if($dia == 16) { $total_dia_16 =  $total_dia_16 + $totaldia; }
			   if($dia == 20) { $total_dia_20 =  $total_dia_20 + $totaldia; }
			   if($dia == 25) { $total_dia_25 =  $total_dia_25 + $totaldia; }
			   if($dia == 28) { $total_dia_28 =  $total_dia_28 + $totaldia; }
			   if($dia == 32) { $total_dia_32 =  $total_dia_32 + $totaldia; }
			   if($dia == 36) { $total_dia_36 =  $total_dia_36 + $totaldia; }
			   
			   
                $prevdate = $List->date;
				$prevpage = $mpage; $prevmbookno = $mbookno;
                $sumst .= $dia."*".$totaldia."@";
                $temp = 0;
				$length3 = strlen($List->descwork);
				$linecnt3 = ceil($length3/20); //echo $linecnt3;
				$currentline = $currentline + $linecnt3;
				if($currentline>36)
				{ 
					?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'><b>C/o to page <?php echo ($mpage+1); ?> / MBook No. <?php echo $mbookno; ?></b></td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_8 != 0) { echo number_format($total_dia_8,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_10 != 0) { echo number_format($total_dia_10,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_12 != 0) { echo number_format($total_dia_12,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_16 != 0) { echo number_format($total_dia_16,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_20 != 0) { echo number_format($total_dia_20,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_25 != 0) { echo number_format($total_dia_25,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_28 != 0) { echo number_format($total_dia_28,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_32 != 0) { echo number_format($total_dia_32,3,".",","); } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_36 != 0) { echo number_format($total_dia_36,3,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr style="border-style:none;">
					<td style="border-style:none;" colspan="16" align="center" class="label"><?php echo ""; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
				</tr>
				</table>
				<p  style='page-break-after:always;'></p>
				<?php echo $title; ?> 
				<?php echo $table; ?>
				<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF'>
				<?php echo $table1; ?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'><b>B/f from page <?php echo $mpage; ?> / MBook No. <?php echo $mbookno; ?></b></td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_8 != 0) { echo number_format($total_dia_8,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_10 != 0) { echo number_format($total_dia_10,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_12 != 0) { echo number_format($total_dia_12,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_16 != 0) { echo number_format($total_dia_16,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_20 != 0) { echo number_format($total_dia_20,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_25 != 0) { echo number_format($total_dia_25,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_28 != 0) { echo number_format($total_dia_28,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_32 != 0) { echo number_format($total_dia_32,3,".",","); } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_36 != 0) { echo number_format($total_dia_36,3,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
					<?php
					
					$currentline = $start_line + 13; 
					$mpage++;
				}
				 $prevsubdiv_name = $List->subdiv_name;
                $prevsubdivid = $List->subdivid;
				$prevdivid = $List->div_id; $prev_decimal = $decimal;
				$tot_8 = "";$tot_10 = "";$tot_12 = "";$tot_16 = "";$tot_20 = "";$tot_25 = "";$tot_28 = "";$tot_32 = "";$tot_36 = "";
                $tot8 = "";$tot10 = "";$tot12 = ""; $tot16 = ""; $tot20 = ""; $tot25 = ""; $tot28 = ""; $tot32 = "";$tot36 = "";
				$txtboxid++;
                } //echo $currentline;
				}
                ?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'>Total Length</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_8 != 0) { echo number_format($total_dia_8,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_10 != 0) { echo number_format($total_dia_10,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_12 != 0) { echo number_format($total_dia_12,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_16 != 0) { echo number_format($total_dia_16,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_20 != 0) { echo number_format($total_dia_20,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_25 != 0) { echo number_format($total_dia_25,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_28 != 0) { echo number_format($total_dia_28,3,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_32 != 0) { echo number_format($total_dia_32,3,".",","); } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_dia_36 != 0) { echo number_format($total_dia_36,3,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'>Unit Weight</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">0.395</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">0.617</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">0.888</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">1.578</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">2.466</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">3.853</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">4.834</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">6.313</td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right">7.990</td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<?php
					$total_weight_8 = round(($total_dia_8 * 0.395),3);
					$total_weight_10 = round(($total_dia_10 * 0.617),3);
					$total_weight_12 = round(($total_dia_12 * 0.888),3);
					$total_weight_16 = round(($total_dia_16 * 1.578),3);
					$total_weight_20 = round(($total_dia_20 * 2.466),3);
					$total_weight_25 = round(($total_dia_25 * 3.853),3);
					$total_weight_28 = round(($total_dia_28 * 4.834),3);
					$total_weight_32 = round(($total_dia_32 * 6.313),3);
					$total_weight_36 = round(($total_dia_36 * 7.990),3);
					$below_20_dia = $total_weight_8+$total_weight_10+$total_weight_12+$total_weight_16+$total_weight_20;
					$below_20_dia_mt = round(($below_20_dia/1000),3);
					$above_20_dia = $total_weight_25+$total_weight_28+$total_weight_32+$total_weight_36;
					$above_20_dia_mt = round(($above_20_dia/1000),3);
					
					
					   
					
					
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'>Total Weight</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_8 != 0) { echo $total_weight_8; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_10 != 0) { echo $total_weight_10; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_12 != 0) { echo $total_weight_12; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_16 != 0) { echo $total_weight_16; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_20 != 0) { echo $total_weight_20; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_25 != 0) { echo $total_weight_25; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_28 != 0) { echo $total_weight_28; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_32 != 0) { echo $total_weight_32; } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($total_weight_36 != 0) { echo $total_weight_36; } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'>8.1.1</td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'>Reinforcement - from foundation to unfinished floor level & Dia of Bars 20 mm & below</td>
                    <td width='' class='labelcenter labelheadblue'><?php echo $below_20_dia; ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">kg</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue'><?php echo number_format($below_20_dia_mt,3,".",","); ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">mt</td>
                    <td width='' class='labelcenter labelheadblue' colspan="4" style="text-align:right"><b><?php echo getcompositepage($sheetid,63); ?></b></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<?php
					/*$below_querys="INSERT INTO mbookgenerate_staff set staffid = '$staffid', sheetid='$sheetid',divid='8',subdivid='63',
						   fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=2,rbn='$rbn',
								mbgeneratedate=NOW(), mbpage='$mpage', abstmbookno='$abstmbookno', mbtotal='$below_20_dia_mt', active=1, userid='$userid'";
					 //echo $querys."<br/>";
					   $below_sqlquerys = mysql_query($below_querys);*/
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'>8.1.2</td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'>Reinforcement - from foundation to unfinished floor level & Dia of Bars above 20 mm</td>
                    <td width='' class='labelcenter labelheadblue'><?php echo $above_20_dia; ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">kg</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue'><?php echo number_format($above_20_dia_mt,3,".",","); ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">mt</td>
                    <td width='' class='labelcenter labelheadblue' colspan="4" style="text-align:right"><b><?php echo getcompositepage($sheetid,64); ?></b></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr style="border-style:none;">
					<td style="border-style:none;" colspan="16" align="center" class="label"><?php echo ""; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
				</tr>
				<?php
					/*$above_querys="INSERT INTO mbookgenerate_staff set staffid = '$staffid', sheetid='$sheetid',divid='8',subdivid='64',
						   fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=2,rbn='$rbn',
								mbgeneratedate=NOW(), mbpage='$mpage', abstmbookno='$abstmbookno', mbtotal='$above_20_dia_mt', active=1, userid='$userid'";
					 //echo $querys."<br/>";
					   $above_sqlquerys = mysql_query($above_querys);*/
				?>
                <!---   THIS IS FOR LAST ROW TOTAL IN WHILE LOOP -->
               <!-- <tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' bgcolor=""></td>
                    <td width='' colspan="4" class='labelcenter' style='text-align:right'>
					<input type="text" style="width:100%" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" class="textboxcobf" />
					</td>
                    <!--<td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>-->
				<?php 
/*				$tot_8 = round(($tot8 * 0.395),$prev_decimal);
				$tot_10 = round(($tot10 * 0.617),$prev_decimal);
				$tot_12 = round(($tot12 * 0.888),$prev_decimal);
				$tot_16 = round(($tot16 * 1.578),$prev_decimal);
				$tot_20 = round(($tot20 * 2.466),$prev_decimal);
				$tot_25 = round(($tot25 * 3.853),$prev_decimal);
				$tot_28 = round(($tot28 * 4.834),$prev_decimal);
				$tot_32 = round(($tot32 * 6.313),$prev_decimal);
				$tot_36 = round(($tot36 * 8),$prev_decimal);
				$totalweight_KGS = round(($tot_8+$tot_10+$tot_12+$tot_16+$tot_20+$tot_25+$tot_28+$tot_32+$tot_36),$prev_decimal);
				$totalweight_MT = round(($totalweight_KGS/1000),$prev_decimal);
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal."@";
				$currentline++;
				if($currentline>39){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
*/				?>
				
				<!--<tr height='' style="border:none;" class="label" align="right"><td colspan="16" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
				<tr height='' style="border:none;" class="label" align="right"><td colspan="16" style="border:none;">Prepared By&nbsp;&nbsp;</td></tr>
				
                </tr>-->
                <?php
				//$currentline+=4;
				/*if($currentline>32)
				{
					//echo check_line($currentline,$tablehead);
					$currentline = 0;
					$currentline = $start_line + 10;
					$mpage++;
				}*/
                
//if($currentline>39){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
?>
	<!--<tr height='25px' bgcolor=""><td colspan="16" align="center" class="labelbold labelheadblue" ><?php echo "Summary"; ?></td></tr>-->
<?php
                /*$summary = $summary1.$summary2;
              // echo $summary."<br/>";
                $explodsummary = explode("@",$summary);
                natsort($explodsummary);
                foreach($explodsummary as $key => $summ)
                {
                    if($summ != "")
                    {
                        $res_summ .= $summ.",";
                    }
                }*/
               //echo $res_summ."<br/>";
                //$result_summary = explode(",",$res_summ);
               //echo $result_summary."<br/>";
                //$preVal = "";$x = 0;
               // while($x < count($result_summary))
			   //$subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;$count = 0;
			   //$pre_subdivname = ""; $temp_var = "";$pre_subdivid = "";$summary_total = 0;
				//if($currentline>39){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 9;$mpage++;}
				?>
				<!--<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" align="right" class='label labelheadblue'>Sub Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$pre_decimal,".",","); } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>-->
				<!--<tr>
					<td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'>C/o to page /MBook No.</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter'></td>
				</tr>-->
				<!--<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Unit Weight</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right">0.395</td>
                    <td width='' class='labelcenter' style="text-align:right">0.617</td>
                    <td width='' class='labelcenter' style="text-align:right">0.888</td>
                    <td width='' class='labelcenter' style="text-align:right">1.578</td>
                    <td width='' class='labelcenter' style="text-align:right">2.466</td>
                    <td width='' class='labelcenter' style="text-align:right">3.853</td>
                    <td width='' class='labelcenter' style="text-align:right">4.834</td>
                    <td width='' class='labelcenter' style="text-align:right">6.313</td>
					<td width='' class='labelcenter' style="text-align:right">7.990</td>
                    <td width='' class='labelcenter'></td>
                </tr>--->	
				<?php
				/*$total_8 = round(($subtotal_8 * 0.395),$pre_decimal);
				$total_10 = round(($subtotal_10 * 0.617),$pre_decimal);
				$total_12 = round(($subtotal_12 * 0.888),$pre_decimal);
				$total_16 = round(($subtotal_16 * 1.578),$pre_decimal);

				$total_20 = round(($subtotal_20 * 2.466),$pre_decimal);
				$total_25 = round(($subtotal_25 * 3.853),$pre_decimal);
				$total_28 = round(($subtotal_28 * 4.834),$pre_decimal);
				$total_32 = round(($subtotal_32 * 6.313),$pre_decimal);
				$total_36 = round(($subtotal_36 * 7.990),$pre_decimal);
				$totalweight_KGS = round(($total_8+$total_10+$total_12+$total_16+$total_20+$total_25+$total_28+$total_32+$total_36),$pre_decimal);
				$totalweight_MT = round(($totalweight_KGS/1000),$pre_decimal);*/
				
				
				//echo $summary_str;
				
				//echo count($summary); 
				//$textbox_str2 .= $pre_textboxid."*".$mpage."*".$pre_mbookno."*"; echo $textbox_str2;
				//$textbox_str = $textbox_str1.$textbox_str2; //echo $textbox_str;
				?>
				<!--<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Total Weight</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_8 != 0) { echo number_format($total_8,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_10 != 0) { echo number_format($total_10,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_12 != 0) { echo number_format($total_12,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_16 != 0) { echo number_format($total_16,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_20 != 0) { echo number_format($total_20,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_25 != 0) { echo number_format($total_25,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_28 != 0) { echo number_format($total_28,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_32 != 0) { echo number_format($total_32,$pre_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($total_36 != 0) { echo number_format($total_36,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter'>Total in kg</td>
                   <td width='' colspan="10" class='labelcenter'><?php echo number_format($totalweight_KGS,$pre_decimal,".",",")." kg"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter labelheadblue'>Total in mt</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo number_format($totalweight_MT,$pre_decimal,".",",")." mt"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>--->
<!--<tr style="border-style:none;">
<td style="border-style:none;" colspan="9" align="right" class="label"><?php echo "<br/><br/>"; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
<td style="border-style:none;" colspan="7" align="center" class="label"><?php echo "<br/><br/>"; //echo "Prepared By"; ?></td>
</tr>-->
				<?php //echo "COUNT = ".$count;
				/*$summary_str2 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage;
				$summary_str = $summary_str1.$summary_str2;
				$summary = explode(",",$summary_str);
				if($count>0)
				{
					for($y=0;$y<count($summary);$y+=6)
					{
						$y1 = $y; $y2 = $y+1; $y3 = $y+2; $y4 = $y+3; $y5 = $y+4; $y6 = $y+5;
						$pre_page = $summary[$y6];
						//MeasurementSteelinsert_staff($fromdate,$todate,$sheetid,$summary[$y5],$summary[$y6],$summary[$y3],$rbn,$userid,$summary[$y2],$summary[$y4],$staffid,$abstmbookno);
					}
				}
				else
				{
				$pre_page = 1;
						//MeasurementSteelinsert_staff($fromdate,$todate,$sheetid,$pre_mbookno,$mpage,$totalweight_MT,$rbn,$userid,$pre_subdivid,$pre_divid,$staffid,$abstmbookno);
				}
               }*/
               ?>
			   </table>
			   <input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($textbox_str1,"*"); ?>"  />
			  <!-- <div class="divFooter">UNCLASSIFIED</div>-->
			 <!--<hr />-->
            <table align="center" style="border:none;" class="printbutton">
                <tr style="border:none">
                   <td align="center" colspan="15" style="border:none;"><br/>
				   <input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></td>
                </tr>
            </table>
 </form>
 <?php
 	/*$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$sheetid' and staffid = '$staffid' and mtype = 'S' and genlevel = 'staff'";
	$delete_mymbook_query = mysql_query($delete_mymbook_sql);
	if($newmbookno == "")
	{
		$insert_mymbook_sql = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '$mpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'staff', mbookorder = 1, active = 1";
		$insert_mymbook_query = mysql_query($insert_mymbook_sql);
	}
	else
	{
		$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '100', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'staff', mbookorder = 1, active = 1";
		$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
		$insert_mymbook_sql2 = "insert into mymbook set mbno = '$newmbookno', startpage = '$newmbookpage', endpage = '$mpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'S', genlevel = 'staff', mbookorder = 2, active = 1";
		$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
	}*/
 ?>
    </body>
</html>