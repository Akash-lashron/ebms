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
if(($_GET['sheetid'] != "") && ($_GET['rbn'] != ""))
{
	$sheetid = $_GET['sheetid'];
	$rbn = $_GET['rbn'];
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
/*if($_POST['back'])
{
    header('Location: MBookGenerate_History_Staff.php');
}*/
//$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '2'";
//echo $select_rbn_query;exit;
//$select_rbn_sql = mysql_query($select_rbn_query);
//$Rbnresult = mysql_fetch_object($select_rbn_sql);
//$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '2' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno_sql = "select mbno, startpage, endpage, mbookorder from mymbook WHERE mtype = 'S' AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND genlevel = 'staff' AND zone_id = '$zone_id'";
$selectmbookno_query = mysql_query($selectmbookno_sql);
$cnt = mysql_num_rows($selectmbookno_query);
if(mysql_num_rows($selectmbookno_query) == 1)
{
	$mbookno = @mysql_result($selectmbookno_query,0,'mbno');
	$mpage = @mysql_result($selectmbookno_query,0,'startpage');
}
if(mysql_num_rows($selectmbookno_query) == 2)
{
	while($MBookList = mysql_query($selectmbookno_query))
	{
		if($MBookList->mbookorder == 1)
		{
			$mbookno = $MBookList->mbno;
			$mpage = $MBookList->startpage;
		}
		if($MBookList->mbookorder == 2)
		{
			$newmbookno = $MBookList->mbno;
			$newmbookpageno = $MBookList->startpage;
		}
	}
}
//$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'S' AND sheetid = '$sheetid' AND staffid = '$staffid'";
//$selectmbookno_sql = mysql_query($selectmbookno);
//if(mysql_num_rows($selectmbookno_sql)>0)
//{
//	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
//	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
//	
//	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	$mbookpage_sql = mysql_query($mbookpage);
//	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
//	
//	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn'";
//	$selectnewmbookno_sql = mysql_query($selectnewmbookno);
//	$newmbookno = @mysql_result($selectnewmbookno_sql,'mbno');
//	
//	$newmbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
//	$newmbookpage_sql = mysql_query($newmbookpage);
//	$newmbookpageno = @mysql_result($newmbookpage_sql,'mbpage')+1;
//	
////$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
////$newmbookpageno = $newmbookpageno +1;
//}
//else
//{
//	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND staffid = '$staffid' AND rbn = '$rbn'";
//	//echo $selectmbookno;
//	$selectmbookno_sql = mysql_query($selectmbookno);
//	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
//	//echo "hai";
//	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
//	$mbookpage_sql = mysql_query($mbookpage);
//	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
//}
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;
//$mpage = $mbookpageno;
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
	$_SESSION['last_row_check'] = 1;
		if($mpage == 100) { $mbookno = $newmbookno; }
		$x1 = "<tr>";
		$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='15'>"."<br/>Page ".$mpage."</td>";
		$x1 = $x1."</tr>";
		$x1 = $x1."</table>";
		$x1 = $x1."<p  style='page-break-after:always;'></p>";
		$x1 = $x1.'<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
				<tr style="border:none;"><td colspan="9" align="center" style="border:none;"><br/>Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
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
		  
		  if($expval[0] == 'c'){ $totc = $totc + $expval[1]; }
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
	if($totc == 0) { $totc = ""; } else { $totc = $totc; }
	$row_co = "<tr height=''>";
	$row_co = $row_co."<td width='' colspan='3' class='labelbold' style='text-align:right'>"."C/o to Page ".($page+1)."/ Steel MB No. ".$mbookno."</td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'></td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'>".$totc."</td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'></td>";
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
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

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
  <script type="text/javascript">
		window.history.forward();
		function noBack() 
		{ 
			window.history.forward(); 
		}
		function goBack()
		{
			url = "MBookGenerate_History_Staff.php";
			window.location.replace(url);
		}
	</script>
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

            <table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF'>
			<?php echo $table1; ?>
                <?php 
				 $CouplerTotal = 0;
                eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

			   $sqlmeasurequery = mysql_query($measurequery);    
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                { //echo $mpage;
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				$decimal = get_decimal_placed($List->subdivid,$sheetid);
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
						$currentline = $start_line + 13;
						$prevpage = 100;
						$mpage = $newmbookpageno;
						//$prevpage = $mpage;
						$mbookno = $newmbookno;
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
						 if($expval[0] == 'c'){ $totc = $totc + $expval[1]; }
                        }
                     } 
					 if($tot8 != "" || $tot10 != "" || $tot12 != "" || $tot16 != "" || $tot20 != "" || $tot25 != "" || $tot28 != "" || $tot32 != "" || $tot36!= "" || $totc != "")
					 {
					 ?>
						
					<tr height=''>
                    <td width='' colspan="3" class='labelbold' style='text-align:right'><?php echo "B/f from Page ".$prevpage."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelbold' style="text-align:right"></td>
					<td width='' class='labelbold' style="text-align:right"><?php echo $totc; ?></td>
					<td width='' class='labelbold' style="text-align:right"></td>
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
					echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$prev_decimal);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
				}
				}
				
                    $measurementdia=$List->measurement_dia;
                    $NOS=chop($List->measurement_no);
                    $LOM=chop($List->measurement_l);
                    $totaldia=trim($NOS*$LOM);
                    
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
                                <td width='' colspan="13" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
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
								echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$prev_decimal);
								}
								echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
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
						 if($expval[0] == 'c'){ $totc = $totc + $expval[1]; }
                        }
                     }
                        ?>
                <tr height=''>
                    <td width='' colspan="3" class='labelcenter' style='text-align:right'>
					<input type="text" class="labelbold" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" style="width:100%; text-align:right; border:none;" />
					</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelbold' style="text-align:right"><?php echo $totc; ?></td>
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

				if($prev_sub_type == 'c'){
                	$summary1 .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.","."".",".$prevsubdivid.",".$prevdivid.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$prev_decimal.",".$CouplerTotal.",".$prev_sub_type."@";//echo $summary1;
					$CouplerTotal = 0;
				}else{
                	$summary1 .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal.","."".",".""."@";//echo $summary1;
				//echo $summary1."SSSS<br/>";
				}
				
				$currentline++;
				if($currentline>31)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$prev_decimal);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
				}
                //echo $summary1;
                //THIS PART IS FOR 2 LINE SPACE BETWEEN NEWDATE AND OLD DATE 
                if(($prevdate != $List->date) && ($prevdate !== ""))
                    {
					
                        ?>
                        <tr height='' style="border:none;" class="label" align="right"><td colspan="15" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
						<tr height='' style="border:none;" class="label" align="right">
						<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
						<td colspan="5" style="border:none;">Prepared By&nbsp;&nbsp;</td>
						<td colspan="5" style="border:none;">Checked By&nbsp;&nbsp;</td>
						</tr>
                <?php
				$currentline = $currentline+3;
					if($currentline>31)
					{ 
						if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
						{
						echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$prev_decimal);
						}
						echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
					}
                  }
                    ?>
                <tr height=''>
                            <td width='8%' class='labelcenter'><?php echo $List->date; ?></td>
                            <td width='' class='labelcenter'><?php echo $List->subdiv_name; ?></td>
                            <td width='' colspan="13" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
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
							echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$prev_decimal);
							}
							echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
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
				//$currentline = $currentline + $wwlcount;
                    ?>
                
                <tr height=''>
                    <td width='8%' class='labelcenter'><?php //echo $List->subdivid; ?></td>
                    <td width='4%' class='labelcenter'><?php //echo $List->subdiv_name;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } ?></td>
                    <td width='15%' class='labelcenter' style="text-align:left;" nowrap="nowrap"><?php echo $descwork; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php echo $List->measurement_dia; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
                    <td width='4%' class='labelcenter' style="text-align:right"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?></td>
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
                     <!--<td width='2%' class='labelcenter'><?php //echo $List->remarks; ?></td>-->
                </tr>
                <?php
               
                $prevdate = $List->date;
				$prevpage = $mpage; $prevmbookno = $mbookno;
                if(($sub_type == 'c') && ($meas_no != 0) && ($meas_no != "")){
					$sumst .= "c"."*".$meas_no."@";
				}else{
                	$sumst .= $dia."*".$totaldia."@";
				}
                $temp = 0;
				$length3 = strlen($List->descwork);
				$linecnt3 = ceil($length3/20); //echo $linecnt3;
				$currentline = $currentline + $linecnt3;
				if($currentline>31)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$decimal);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
				}
				$prevsubdiv_name = $List->subdiv_name;
				if(($sub_type == 'c') && ($meas_no != 0) && ($meas_no != ""))
				{
                //$summary1 .= $List->subdiv_name.",".$List->date.",".$mpage.",".$mbookno.","."".",".$List->subdivid.",".$List->div_id.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$decimal.",".$meas_no.",".$sub_type."@";//echo $summary1;
				// Coupler Item Comment on 04/04/2023 for coupler item carry over
				$CouplerTotal = $CouplerTotal + $meas_no;
				//echo $summary1."hghfgj<br/>";
				//echo $summary1."SSSS<br/>";
				}
                $prevsubdivid = $List->subdivid;
				$prevdivid = $List->div_id; $prev_decimal = $decimal;
				$tot_8 = "";$tot_10 = "";$tot_12 = "";$tot_16 = "";$tot_20 = "";$tot_25 = "";$tot_28 = "";$tot_32 = "";$tot_36 = "";
                $tot8 = "";$tot10 = "";$tot12 = ""; $tot16 = ""; $tot20 = ""; $tot25 = ""; $tot28 = ""; $tot32 = "";$tot36 = ""; $totc = "";
				$txtboxid++;
                } //echo $currentline;
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
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				if($prev_sub_type == 'c')
				{
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.","."".",".$prevsubdivid.",".$prevdivid.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$prev_decimal.",".$CouplerTotal.",".$prev_sub_type."@";
				}
				else
				{
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal.","."".",".""."@";
				}				$currentline++;
				//if($currentline>38){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 11;$mpage++;}
				if($_SESSION['last_row_check'] == 1)
				{
				?>
				<tr height=''>
                    <td width='' colspan="3" class='labelbold' style='text-align:right'><?php echo "B/f from Page ".$page_check_last_row."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelbold' style="text-align:right"></td>
					<td width='' class='labelbold' style="text-align:right"><?php echo $CouplerTotal; ?></td>
					<td width='' class='labelbold' style="text-align:right"></td>
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
                    <td width='' colspan="3" class='labelbold' style='text-align:right'>
					<input type="text" name="txt_pageid" class="labelbold" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" style="width:100%; text-align:right; border:none;" />
					</td>
                    <td width='' class='labelbold'></td>
					<td width='' class='labelbold' style="text-align:right"><?php echo $CouplerTotal; ?></td>
                    <td width='' class='labelbold' bgcolor=""></td>
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
				<tr height='' style="border:none;" class="label" align="right"><td colspan="15" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
				<tr height='' style="border:none;" class="label" align="right">
				<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
				<td colspan="5" style="border:none;">Prepared By&nbsp;&nbsp;</td>
				<td colspan="5" style="border:none;">Checked By&nbsp;&nbsp;</td>
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
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;
?>
	<tr height='25px'><td colspan="15" align="center" class="labelbold"><?php echo "Summary"; ?></td></tr>
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
			   $pre_subdivname = ""; $temp_var = "";$pre_subdivid = "";$summary_total = 0;
                for($x=0;$x < count($result_summary)-1;$x+=18)
                {
					/*if($currentline>32)
					{
						
						$currentline = 0;
						$currentline = $start_line + 10;
						$mpage++;
					}*/
                  $x1=$x+1; $x2=$x+2; $x3=$x+3; $x4=$x+4; $x5=$x+5; $x6=$x+6; $x7=$x+7; $x8=$x+8; $x9=$x+9; $x10=$x+10; $x11=$x+11; $x12=$x+12; $x13=$x+13; $x14=$x+14; $x15=$x+15;$x16=$x+16;$x17=$x+17;
	
						if($result_summary[$x] != $pre_subdivname)
						{
							if($pre_subdivname != "")
							{
							$count++;
							?>
				
				<tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Sub Total</td>
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
                    <td width='' colspan="7" align="right" class='label labelheadblue'><?php echo getcompositepage($sheetid,$pre_subdivid); ?></td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>	
							<?php
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Total Weight</td>
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
                   <td width='' colspan="3" class='labelcenter'>Total in kgs</td>
                   <td width='' colspan="5" class='labelcenter'><?php echo $totalweight_KGS." kgs"; ?></td>
                   <td width='' colspan="5" class='labelcenter'></td>
				   
                </tr>
				<tr height=''>
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="3" align="right" class='labelbold'>Total in MT</td>
                   <td width='' colspan="5" align="center" class='labelbold'><?php echo $totalweight_MT." MT"; ?></td>
                   <td width='' colspan="5" class='labelbold' style='text-align:right'><?php echo getcompositepage($sheetid,$pre_subdivid); ?></td>
				   
                </tr>
				<?php
				
				$currentline = $currentline+5;
				if($currentline>30){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
				$summary_str1 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage.",";
				$subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;
							}
							 //$subtotal_8 = 0;
						}
						?>
							
				<tr height=''>
                    <td width='8%' class='labelcenter'><?php echo $result_summary[$x1]; ?></td>
                    <td width='4%' class='labelcenter' bgcolor=""><?php echo $result_summary[$x]; ?></td>
                    <td width='15%' class='labelcenter' colspan="3"><?php echo "Quantity vide B/f Page-".$result_summary[$x2];  ?></td>
					<td width='3%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php if($result_summary[$x7] != 0){ echo number_format($result_summary[$x7],$result_summary[$x17],".",","); } ?></td>
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
					$currentline++;
					if($currentline>30)
					{ 
?>
<tr height='' bgcolor="">
 <td width='' colspan="6" class='labelcenter'><?php if($mpage==100){ echo "C/o to Page ".(0+1);  } else { echo "C/o to Page ".($mpage+1); } ?></td>
 <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } ?></td>
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
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;
?>
<tr height='' bgcolor="">
  <td width='' colspan="6" class='labelbold'><?php if($mpage==1){ echo "B/f from Page 100"; } else { echo "B/f from Page ".($mpage-1); } ?></td>
  <td width='7%' class='labelbold' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } ?></td>
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
					}
					$subtotal_8	= $subtotal_8 + $result_summary[$x7];
					$subtotal_10	= $subtotal_10 + $result_summary[$x8];
					$subtotal_12	= $subtotal_12 + $result_summary[$x9];
					$subtotal_16	= $subtotal_16 + $result_summary[$x10];
					$subtotal_20	= $subtotal_20 + $result_summary[$x11];
					$subtotal_25	= $subtotal_25 + $result_summary[$x12];
					$subtotal_28	= $subtotal_28 + $result_summary[$x13];
					$subtotal_32	= $subtotal_32 + $result_summary[$x14];
					$subtotal_36	= $subtotal_36 + $result_summary[$x15];
							
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
					//$textbox_str1 .= $result_summary[$x16]."*".$result_summary[$x2]."*".$result_summary[$x3]."*"; echo $textbox_str1;
//echo $result_summary[$x16]."<br/>";
                }
				if($currentline>30){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
				?>
				<tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Sub Total</td>
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
                    <td width='' colspan="7" align="right" class='label labelheadblue'><?php echo getcompositepage($sheetid,$pre_subdivid); ?></td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter'>Total in kgs</td>
                   <td width='' colspan="5" class='labelcenter'><?php echo number_format($totalweight_KGS,$pre_decimal,".",",")." kgs"; ?></td>
                   <td width='' colspan="5" class='labelcenter'></td>
				   
                </tr>
				<tr height=''>
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" align="right" class='labelbold'>Total in MT</td>
                   <td width='' colspan="5" align="center" class='labelbold'><?php echo number_format($totalweight_MT,$pre_decimal,".",",")." MT"; ?></td>
                   <td width='' colspan="5" class='labelbold' style='text-align:right'><?php echo getcompositepage($sheetid,$pre_subdivid); ?></td>
				   
                </tr>
<!--<tr style="border-style:none;">
<td style="border-style:none;" colspan="8" align="right" class="label"><?php //echo "<br/><br/>"; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
<td style="border-style:none;" colspan="7" align="center" class="label"><?php //echo "<br/><br/>"; //echo $staffname." - ".$designation; ?></td>
</tr>-->
<tr style="border-style:none;">
<td style="border-style:none;" colspan="8" align="right" class="label"><?php /*echo "<br/><br/>";*/ echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
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
				<div class="btn_inside_sect"><input type="button" class="backbutton" name="back" id="back" value=" Back " onclick="goBack();" /> </div>
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