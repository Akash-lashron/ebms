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

if($_POST['back'])
{
    header('Location: Generate.php');
}

$sheetid=$_SESSION["sheet_id"]; 
$fromdate = $_SESSION['fromdate'];
$todate = $_SESSION['todate'];
$mbookno = $_SESSION["mb_no"];  
$mpage = $_SESSION["mb_page"]; 
$rbn = $_SESSION["rbn"];
$steelmbno_id = $_SESSION["mbno_id"];
$abstmbookno = $_SESSION["abs_mbno"];
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
.label, .labelcenter
{
	color:#0000CD;
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
.labelheadblue{
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
   <body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" style=" text-align:center; left:92px;" height="56px" align="center" bgcolor="#20b2aa" class='header label'>
<tr style="position:fixed;">
<td style="color:#FFFFFF; border:none; font-size:18px;"  width="1086px"  height="56px" align="center" bgcolor="#20b2aa">STEEL MEASUREMENT BOOK - COMPOSITE</td>
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
            $table = "<table width='1087px' border='0'  cellpadding='2' cellspacing='2' align='center' bgcolor='#FFFFFF'>";
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
            $table1 = $table1 . "<td width='2%' rowspan='2' class='labelcenter labelheadblue'></td>";  //Remarks Field changed into Per.
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
				INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND schdule.measure_type = 's' AND mbookdetail.mbdetail_flag != 'd' AND mbookheader.sheetid = '$sheetid' ORDER BY mbookheader.date, mbookdetail.subdivid ASC";
               $sqlmeasurequery = mysql_query($measurequery);     
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                { //echo $mpage;
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				$decimal = get_decimal_placed($List->subdivid,$sheetid);
					if($mpage > 100)
					{
						if($_GET['varid'] == 1)
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
						}
						$currentline = $start_line + 13;
						$prevpage = 100;
						$mpage = $newmbookpage;
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
                        }
                     } 
					?>
						
					<tr height=''>
                    <td width='' colspan="6" class='labelcenter labelheadblue' style='text-align:right'><?php echo "B/f from Page ".$prevpage."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter'><?php //echo $mpage; ?></td>
                	</tr>
				<?php 
				//echo $currentline;
				$currentline++;
				if($currentline>29)
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
                                <td width='' colspan="14" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
                            </tr>
                  <?php 
				  		$length1 = strlen($List->shortnotes);
						$linecnt1 = ceil($length1/145);
				  		$currentline = $currentline + $linecnt1;
							if($currentline>29)
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
                        }
                     }
                        ?>
                <tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' bgcolor=""></td>
                    <td width='' colspan="3" class='labelcenter labelheadblue' style='text-align:right'>
					<input type="text" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" class="textboxcobf" />
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
                    <td width='' class='labelcenter'></td>
                </tr>
                	
                <?php 
				
				$tot_8 = round(($tot8 * 0.395),$prev_decimal);
				$tot_10 = round(($tot10 * 0.617),$prev_decimal);
				$tot_12 = round(($tot12 * 0.888),$prev_decimal);
				$tot_16 = round(($tot16 * 1.580),$prev_decimal);
				$tot_20 = round(($tot20 * 2.470),$prev_decimal);
				$tot_25 = round(($tot25 * 3.860),$prev_decimal);
				$tot_28 = round(($tot28 * 4.830),$prev_decimal);
				$tot_32 = round(($tot32 * 6.313),$prev_decimal);
				$tot_36 = round(($tot36 * 8),$prev_decimal);
				$totalweight_KGS = round(($tot_8+$tot_10+$tot_12+$tot_16+$tot_20+$tot_25+$tot_28+$tot_32),$prev_decimal);
				$totalweight_MT = round(($totalweight_KGS/1000),$prev_decimal);
                $summary1 .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal."@";//echo $summary1;
				$currentline++;
				if($currentline>29)
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
						$stafflist = stafflist($prevsubdivid,$prevdate,$sheetid);
						$explode_stafflist = explode("*",$stafflist);
                        ?>
                       <tr style='border:none' class="label">
						<td style='border:none' colspan='16' align="right"><br/>
						<?php
						for($x2 = 0; $x2<count($explode_stafflist); $x2+=2)
						{
							echo $explode_stafflist[$x2]." - ".$explode_stafflist[$x2+1]."&emsp;";
							//echo "&emsp;";
						}
						?>
						</td>
						</tr>
						<tr style='border:none' class="label"><td style='border:none' colspan='16' align="right">Prepared by&emsp;&emsp;</td></tr>
                <?php
				$currentline = $currentline+3;
					if($currentline>29)
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
                            <td width='' colspan="14" class='labelcenter' style="text-align:left;"><?php echo $List->shortnotes; ?></td>
                 </tr>
                        <?php 
                         $sumst = "";  
					   	$length2 = strlen($List->shortnotes);
						$linecnt2 = ceil($length2/145);
				  		$currentline = $currentline + $linecnt2;
						if($currentline>29)
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
                    <td width='8%' class='labelcenter'><?php //echo $List->subdivid; ?></td>
                    <td width='4%' class='labelcenter'><?php //echo $List->subdiv_name;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } ?></td>
                    <td width='15%' class='labelcenter' style="text-align:left;word-wrap:break-word;"><?php echo $List->descwork; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php echo $List->measurement_dia; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php echo $List->measurement_no; ?></td>
                    <td width='4%' class='labelcenter' style="text-align:right"><?php echo $List->measurement_l; ?></td>
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
                     <td width='2%' class='labelcenter'><?php //echo $List->remarks; ?></td>
                </tr>
                <?php
               
                $prevdate = $List->date;
				$prevpage = $mpage; $prevmbookno = $mbookno;
                $sumst .= $dia."*".$totaldia."@";
                $temp = 0;
				$length3 = strlen($List->descwork);
				$linecnt3 = ceil($length3/20); //echo $linecnt3;
				$currentline = $currentline + $linecnt3;
				if($currentline>29)
				{ 
					if(($prevsubdivid == $List->subdivid) && ($prevdate == $List->date))
					{
					echo display_carry($sumst,$mbookno,$mpage,$newmbookno,$decimal);
					}
					echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;	
				}
				 $prevsubdiv_name = $List->subdiv_name;
                $prevsubdivid = $List->subdivid;
				$prevdivid = $List->div_id; $prev_decimal = $decimal;
				$tot_8 = "";$tot_10 = "";$tot_12 = "";$tot_16 = "";$tot_20 = "";$tot_25 = "";$tot_28 = "";$tot_32 = "";$tot_36 = "";
                $tot8 = "";$tot10 = "";$tot12 = ""; $tot16 = ""; $tot20 = ""; $tot25 = ""; $tot28 = ""; $tot32 = "";$tot36 = "";
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
                <tr height=''>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' bgcolor=""></td>
                    <td width='' colspan="4" class='labelcenter labelheadblue' style='text-align:right'>
					<input type="text" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>"  class="textboxcobf" />
					</td>
                    <!--<td width='' class='labelcenter'></td>-->
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
                </tr>
				<?php 
				$tot_8 = round(($tot8 * 0.395),$prev_decimal);
				$tot_10 = round(($tot10 * 0.617),$prev_decimal);
				$tot_12 = round(($tot12 * 0.888),$prev_decimal);
				$tot_16 = round(($tot16 * 1.580),$prev_decimal);
				$tot_20 = round(($tot20 * 2.470),$prev_decimal);
				$tot_25 = round(($tot25 * 3.860),$prev_decimal);
				$tot_28 = round(($tot28 * 4.830),$prev_decimal);
				$tot_32 = round(($tot32 * 6.313),$prev_decimal);
				$tot_32 = round(($tot36 * 8),$prev_decimal);
				$totalweight_KGS = round(($tot_8+$tot_10+$tot_12+$tot_16+$tot_20+$tot_25+$tot_28+$tot_32),$prev_decimal);
				$totalweight_MT = round(($totalweight_KGS/1000),$prev_decimal);
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal."@";
				$currentline++;
				if($currentline>29){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}
				
				$stafflist = stafflist($prevsubdivid,$prevdate,$sheetid);
				$explode_stafflist = explode("*",$stafflist);
				?>
				<tr style='border:none' class="label">
					<td style='border:none' colspan='16' align="right"><br/>
					<?php
					for($x2 = 0; $x2<count($explode_stafflist); $x2+=2)
					{
						echo $explode_stafflist[$x2]." - ".$explode_stafflist[$x2+1]."&emsp;";
							//echo "&emsp;";
					}
					?>
					</td>
				</tr>
				<tr style='border:none'><td style='border:none' colspan='16' align="right" class="label">Prepared by&emsp;&emsp;</td></tr>
				<tr height='25px' bgcolor=""><td colspan="16" align="center" class="labelbold labelheadblue" ><?php echo "Summary"; ?></td></tr>
                </tr>
                <?php
				$currentline+=4;
				/*if($currentline>32)
				{
					//echo check_line($currentline,$tablehead);
					$currentline = 0;
					$currentline = $start_line + 10;
					$mpage++;
				}*/
                
if($currentline>29){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 13;$mpage++;}

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
				
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter labelheadblue'>Sub Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_8 != 0) { echo $subtotal_8; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_10 != 0) { echo $subtotal_10; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_12 != 0) { echo $subtotal_12; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_16 != 0) { echo $subtotal_16; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_20 != 0) { echo $subtotal_20; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_25 != 0) { echo $subtotal_25; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_28 != 0) { echo $subtotal_28; } ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_32 != 0) { echo $subtotal_32; } ?></td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right"><?php if($subtotal_36 != 0) { echo $subtotal_36; } ?></td>
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Unit Weight</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right">0.395</td>
                    <td width='' class='labelcenter' style="text-align:right">0.617</td>
                    <td width='' class='labelcenter' style="text-align:right">0.888</td>
                    <td width='' class='labelcenter' style="text-align:right">1.580</td>
                    <td width='' class='labelcenter' style="text-align:right">2.470</td>
                    <td width='' class='labelcenter' style="text-align:right">3.860</td>
                    <td width='' class='labelcenter' style="text-align:right">4.830</td>
                    <td width='' class='labelcenter' style="text-align:right">6.313</td>
					<td width='' class='labelcenter' style="text-align:right">8.000</td>
                    <td width='' class='labelcenter'></td>
                </tr>	
							<?php
				$total_8 = round(($subtotal_8 * 0.395),3);
				$total_10 = round(($subtotal_10 * 0.617),3);
				$total_12 = round(($subtotal_12 * 0.888),3);
				$total_16 = round(($subtotal_16 * 1.580),3);
				$total_20 = round(($subtotal_20 * 2.470),3);
				$total_25 = round(($subtotal_25 * 3.860),3);
				$total_28 = round(($subtotal_28 * 4.830),3);
				$total_32 = round(($subtotal_32 * 6.313),3);
				$total_36 = round(($subtotal_36 * 8.000),3);
				$totalweight_KGS = round(($total_8+$total_10+$total_12+$total_16+$total_20+$total_25+$total_28+$total_32+$total_36),3);
				$totalweight_MT = round(($totalweight_KGS/1000),3);
				
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
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter'>Total in kg</td>
                   <td width='' colspan="10" class='labelcenter'><?php echo $totalweight_KGS." kg"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="3" class='labelcenter labelheadblue'>Total in mt</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo $totalweight_MT." mt"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>
				<?php
				
				$currentline = $currentline+5;
				if($currentline>29){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 9;$mpage++;}
				$summary_str1 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage.",";
				$subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;
							}
							 //$subtotal_8 = 0;
						}
						?>
							
				<tr height=''>
                    <td width='8%' class='labelcenter'><?php echo $result_summary[$x1]; ?></td>
                    <td width='4%' class='labelcenter' bgcolor=""><?php echo $result_summary[$x]; ?></td>
                    <td width='15%' class='labelcenter'><?php echo "Quantity vide P-".$result_summary[$x2];  ?></td>
					<td width='3%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
					<td width='3%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
                    <td width='4%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x7],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x8],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x9],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x10],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x11],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x12],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x13],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x14],$result_summary[$x17],".",","); ?></td>
					<td width='6%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x15],$result_summary[$x17],".",","); ?></td>
                    <td width='2%' class='labelcenter'></td>
                </tr>

                                    <?php
					$textbox_str1 .= $result_summary[$x16]."*".$mpage."*".$mbookno."*"; //echo $textbox_str1;
					$currentline++;
					if($currentline>29)
					{ 
?>
<tr height='' bgcolor="">
 <td width='' colspan="6" class='labelcenter labelheadblue'><?php if($mpage==100){ echo "C/o to Page ".(0+1);  } else { echo "C/o to Page ".($mpage+1); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
 <td width='6%' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
 <td width='' class='labelcenter'></td>
</tr>

<?php					
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 9;$mpage++;
?>
<tr height='' bgcolor="">
  <td width='' colspan="6" class='labelcenter labelheadblue'><?php if($mpage==1){ echo "B/f from Page 100"; } else { echo "B/f from Page ".($mpage-1); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
  <td width='6%' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
  <td width='' class='labelcenter'>&nbsp;</td>
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
				if($currentline>29){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 9;$mpage++;}
				?>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter labelheadblue'>Sub Total</td>
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
                </tr>
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="3" class='labelcenter'>Unit Weight</td>
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter' style="text-align:right">0.395</td>
                    <td width='' class='labelcenter' style="text-align:right">0.617</td>
                    <td width='' class='labelcenter' style="text-align:right">0.888</td>
                    <td width='' class='labelcenter' style="text-align:right">1.580</td>
                    <td width='' class='labelcenter' style="text-align:right">2.470</td>
                    <td width='' class='labelcenter' style="text-align:right">3.860</td>
                    <td width='' class='labelcenter' style="text-align:right">4.830</td>
                    <td width='' class='labelcenter' style="text-align:right">6.313</td>
					<td width='' class='labelcenter' style="text-align:right">8.000</td>
                    <td width='' class='labelcenter'></td>
                </tr>	
				<?php
				$total_8 = round(($subtotal_8 * 0.395),$pre_decimal);
				$total_10 = round(($subtotal_10 * 0.617),$pre_decimal);
				$total_12 = round(($subtotal_12 * 0.888),$pre_decimal);
				$total_16 = round(($subtotal_16 * 1.580),$pre_decimal);
				$total_20 = round(($subtotal_20 * 2.470),$pre_decimal);
				$total_25 = round(($subtotal_25 * 3.860),$pre_decimal);
				$total_28 = round(($subtotal_28 * 4.830),$pre_decimal);
				$total_32 = round(($subtotal_32 * 6.313),$pre_decimal);
				$total_36 = round(($subtotal_36 * 8.000),$pre_decimal);
				$totalweight_KGS = round(($total_8+$total_10+$total_12+$total_16+$total_20+$total_25+$total_28+$total_32+$total_36),$pre_decimal);
				$totalweight_MT = round(($totalweight_KGS/1000),$pre_decimal);
				
				
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
                    <td width='' class='labelcenter'></td>
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter'>Total in kg</td>
                   <td width='' colspan="10" class='labelcenter'><?php echo number_format($totalweight_KGS,$pre_decimal,".",",")." kg"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>
				<tr height='' bgcolor=""><!--A8FDAC-->
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="3" class='labelcenter labelheadblue'>Total in mt</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo number_format($totalweight_MT,$pre_decimal,".",",")." mt"; ?></td>
                   <td width='' class='labelcenter'></td>
				   
                </tr>
<tr style="border-style:none;">
<td style="border-style:none;" colspan="9" align="right" class="label"><?php echo "<br/><br/>"; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
<td style="border-style:none;" colspan="7" align="center" class="label"><?php echo "<br/><br/>"; //echo "Prepared By"; ?></td>
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
						MeasurementSteelinsert($fromdate,$todate,$sheetid,$summary[$y5],$summary[$y6],$summary[$y3],$rbn,$userid,$summary[$y2],$summary[$y4],$staffid,$abstmbookno);
					}
				}
				else
				{
				$pre_page = 1;
					MeasurementSteelinsert($fromdate,$todate,$sheetid,$pre_mbookno,$mpage,$totalweight_MT,$rbn,$userid,$pre_subdivid,$pre_divid,$staffid,$abstmbookno);
				}
               }
               ?>
			   </table>
			   <input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($textbox_str1,"*"); ?>"  />
			  <!-- <div class="divFooter">UNCLASSIFIED</div>-->
			 <!--<hr />-->
            <table align="center" style="border:none;" class="printbutton">
                <tr style="border:none">
                   <td align="center" colspan="15" style="border:none;"><br/><input type="submit" name="back" value=" Back "/></td>
                </tr>
            </table>
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