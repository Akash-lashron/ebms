<?php
@ob_start();
require_once 'library/config.php'; 
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser(); 
$msg 			= '';
$newmbookno		= '';
$staffid 		= $_SESSION['sid'];
$staffid_acc 	= $_SESSION['sid_acc'];
if($_SESSION['sid_acc'] != "")
{
	$minmax_level_str 		= getstaff_minmax_level();
	$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	$min_levelid 			= $exp_minmax_level_str[0];
	$max_levelid 			= $exp_minmax_level_str[1];
}
//echo $_SESSION['lock'];exit;
//echo $staffid_acc;exit;
$userid 	= $_SESSION['userid'];
$mbooktype 	= "G";
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
function check_line($title,$table,$page,$mbookno,$newmbookno,$table1)
{
	if($page == 100) { $mbookno = $newmbookno; }
	$row = '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">&nbsp;<br/>Page '.$page.'&nbsp;&nbsp</td></tr>';
	$row = $row."</table>";
	$row = $row."<p  style='page-break-after:always;'></p>";
	$row = $row.'<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
}
$staff_design_sql = "select staff.staffname, designation.designationname from staff INNER JOIN designation ON (designation.designationid = staff.designationid) WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
$staff_design_query = mysql_query($staff_design_sql);
$staffList = mysql_fetch_object($staff_design_query);
$staffname = $staffList->staffname;
$designation = $staffList->designationname;
$zone_id = $_SESSION['zone_id'];
if(($zone_id != "") && ($zone_id != "all"))
{
	$zone_clause = " AND mbookheader.zone_id = '".$zone_id."'";
}
else
{
	$zone_clause = "";
}
if($_GET['workno'] != "")
{
	$sheetid = $_GET['workno'];
	$linkid = $_GET['linkid'];
}


if($_POST["Back"] == " Back ")
{
	$sheetid 	= $_POST['txt_sheetid'];
	$zone_id 	= $_POST['txt_zone_id'];
	$rbn 		= $_POST['txt_rbn_no'];
	$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'G' and genlevel = 'staff'";
	$lock_release_sql = mysql_query($lock_release_query);
	//echo $lock_release_query;
	//echo $_SESSION['lock'];
	$_SESSION['lock'] = "";
	
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $staff_level_str 		= getstafflevel($staffid_acc);
	 $exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	 $staff_roleid 			= $exp_staff_level_str[0];
	 $staff_levelid 		= $exp_staff_level_str[1];
	 
	 $minmax_level_str 		= getstaff_minmax_level();
	 $exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	 $min_levelid 			= $exp_minmax_level_str[0];
	 $max_levelid 			= $exp_minmax_level_str[1];
	if($staff_levelid == $min_levelid)
	{
		$accurl = "MeasurementBookPrint_staff_Accounts.php";
	}
	else
	{
		$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
	}
    header('Location: '.$accurl);
}
 
if($_POST["send_to_civil"] == " Send to Civil ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $staff_level_str 		= getstafflevel($staffid_acc);
	 $exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	 $staff_roleid 			= $exp_staff_level_str[0];
	 $staff_levelid 		= $exp_staff_level_str[1];
	 
	 $minmax_level_str 		= getstaff_minmax_level();
	 $exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	 $min_levelid 			= $exp_minmax_level_str[0];
	 $max_levelid 			= $exp_minmax_level_str[1];
	 if($staff_levelid == $min_levelid)
	 {
	 	//$level_status = "P";
		//$staff_levelid = $staff_levelid + 1;
		$staff_clause = " acc_staffid_L1 = '".$staffid_acc."' ";
	 }
	 else
	 {
	 	//$level_status = "F";
		//$staff_levelid = $staff_levelid;
		$staff_clause = " acc_staffid_L2 = '".$staffid_acc."' ";
	 }
	// echo $acc_remarks_count;exit;
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level_status = 'F', ".$staff_clause." where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'staff'";
	 $update_sql 	= mysql_query($update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Sucessfully sent to Civil";
		$success 	= 1;
		$_SESSION['lock'] = "";
	 }
	 else
	 {
		$msg 		= "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'staff', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '$staff_levelid', sectionid = 2";
	 $linsert_log_sql = mysql_query($linsert_log_query);
	 
}

if($_POST["accept"] == " Accept MBook ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $staff_level_str 		= getstafflevel($staffid_acc);
	 $exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	 $staff_roleid 			= $exp_staff_level_str[0];
	 $staff_levelid 		= $exp_staff_level_str[1];
	 
	 $minmax_level_str 		= getstaff_minmax_level();
	 $exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	 $min_levelid 			= $exp_minmax_level_str[0];
	 $max_levelid 			= $exp_minmax_level_str[1];
	 if($staff_levelid == $min_levelid)
	 {
	 	$level_status = "P";
		$staff_levelid = $staff_levelid + 1;
		//$acc_staffid_L1 = $staffid_acc;
		$staff_clause = " acc_staffid_L1 = '".$staffid_acc."' ";
	 }
	 else
	 {
	 	$level_status = "F";
		$staff_levelid = $staff_levelid;
		$staff_clause = " acc_staffid_L2 = '".$staffid_acc."' ";
	 }
	 /*if($staff_levelid < $max_levelid)
	 {
	 	$staff_levelid = $staff_levelid + 1;
	 }
	 else
	 {
	 	$staff_levelid = $staff_levelid;
	 }*/
	 $sc_sheetid 		= $_POST['txt_sheetid'];
	 $sc_zone_id 		= $_POST['txt_zone_id'];
	 $sc_rbnno 			= $_POST['txt_rbn_no'];
	 $acc_remarks_count = $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 		= $_POST['txt_mbook_no'];
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '$staff_levelid', level_status = '$level_status', ".$staff_clause." 
	 where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'staff'";
	// echo $update_query;exit;
	 $update_sql 	= mysql_query($update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Accepted Sucessfully";
		$success 	= 1;
		$_SESSION['lock'] = "";
	 }
	 else
	 {
		$msg 		= "Error";
	 }
	 
	 $log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'staff', status = 'AC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '$staff_levelid', sectionid = 2";
	 $linsert_log_sql = mysql_query($linsert_log_query);
}
 
$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
//$select_rbn_query = "select DISTINCT rbn FROM mbookgenerate WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1'";
$select_rbn_sql = mysql_query($select_rbn_query);
$Rbnresult = mysql_fetch_object($select_rbn_sql);
$rbn = $Rbnresult->rbn;
$selectmbook_detail = " select DISTINCT fromdate, todate, abstmbookno FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn' AND zone_id = '$zone_id'";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;
}
$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
$selectmbookno_sql = mysql_query($selectmbookno);
if(mysql_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno = mysql_fetch_object($selectmbookno_sql);
	$mbookno = $Listmbookno->mbname; $oldmbookid = $Listmbookno->old_id;
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
	
	$selectnewmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
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
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '1' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
}
//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/
$mpage = $mbookpageno;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];    
//$mpage = $_SESSION["mb_page"];
//$mbno_id = $_SESSION["mbno_id"];
//$rbn = $_SESSION["rbn"];
//$abstmbookno = $_SESSION["abs_mbno"];
$query = "SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no; $work_order_no = $List->work_order_no; 
	$ccno = $List->computer_code_no;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no = $rbn;
    //$_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length = strlen($work_name);
//echo $length."<br/>";
$start_line = ceil($length/87);
//echo $start_line;
/*$mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =1";
$result = dbQuery($mbookgeneratedelsql);
function mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid)
{ 
   $querys="INSERT INTO mbookgenerate set sheetid='$sheetid',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid'";
 //echo $querys."<br>";
   $sqlquerys = mysql_query($querys);
}*/
/*$_SESSION['lock'] == 0;
if($_SESSION['lock'] == 0)
{
	$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'G' and genlevel = 'staff'";
	$update_locked_sql = mysql_query($update_locked_query);
	$_SESSION['lock'] = 1;
}
*/
function getabstractpage($sheetid,$subdivid)
{
	$select_abs_page_query = "select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$abstmbookno = @mysql_result($select_abs_page_sql,0,'abstmbookno');
	$abstractpage = @mysql_result($select_abs_page_sql,0,'abstmbpage');
	return "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function getcompositepage($sheetid,$subdivid)
{
	$select_abs_page_query = "select mbno, mbpage from mbookgenerate WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql = mysql_query($select_abs_page_query);
	$mbookno_compo = @mysql_result($select_abs_page_sql,0,'mbno');
	$mbookpageno_compo = @mysql_result($select_abs_page_sql,0,'mbpage');
	return "C/o to Page ".$mbookpageno_compo." /General MB No. ".$mbookno_compo;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>General M.Book</title>
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
		<link rel="stylesheet" href="dist/sweetalert.css">
		<script src="dist/sweetalert-dev.js"></script>
		<!--<script>
		
  $(function() {
   $("#dialog").dialog({ autoOpen: false,
        minHeight: 200,
        maxHeight:200,
        minWidth: 300,
        maxWidth: 300,
        modal: true,});
        $("#dialog").dialog("open");
        $( "#dialog" ).dialog( "option", "draggable", false );
		$('#btn_cancel').click(function(){
		$("#dialog").dialog("close");
		window.location.href="Generate.php";
		});
        $('#btn').click(function(){
		var x = $('#newmbooklist option:selected').val();
		//alert(x);
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
				var newmbookvalue = $("#newmbooklist option:selected").text(); //alert(newmbookvalue);
				var oldmbookdetails = document.form.txt_mbno_id.value;
				$.post("GetOldMbookNo.php", {oldmbook: oldmbookdetails}, function (data) {
				window.location.href="Mbook.php?newmbook="+newmbookvalue;
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
		padding-top:5px;
		padding-bottom:5px;
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
	.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
	.breakAfter 
	{
		page-break-before: always;
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
	.label, .labelcenter, .labelheadblue
	{
		font-size:13px;
	}
</style>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
<!--<body id="top" bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">-->
<body id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="875" style="position:fixed; text-align:center; left:194px;" height="60px" align="center" bgcolor="#20b2aa" class='header'>
<tr>
<td style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">GENERAL MEASUREMENT BOOK</td>
</tr>
</table><br/><br/><br/>-->
<form name="form" id="form" method="post">
			<?php
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            //$table = $table . "</table>";
            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class='label'>
<?php echo $table1; ?>
<?php

eval(str_rot13(gzinflate(str_rot13(base64_decode('LUrHDqxVEvya0cze8FN7wjbeu+aywnsPUsPXDzwtdwmorowyUVNxrc1r/bMPINmusU3/mcZlJbD/LeucLus/xdjWxfX/l79IfYXLRTF5if8LZFDqxm+G+VYV+Asx9VtC41u/6XdYYNkrxKyQk1jGszl++pYo30t4/zwJbcIJW8I9IXSdeSahFsuAPn8gDZ3Cn01wgDD78EkZSiaIQq+eDeDVwPS5dOTKemp0r3hiSrn8q66Fq+qpGVrYdz8IHzgMV2u8T/aHjBRMB4FK7YR9h3Y/ZBwotfAgS4S2f+UFazsVKwkRFsIVOGlu1fABa1NwUciGalRDD2tqp48oivPKMf2x+1fDlJWqczUtk4RL0aDi1o7nXX+71mdlUf9KyZw4houIuh+PDeflTswLqe3UlQEpUWCDjirUyMF6gj8jzYxiFPGQS369tDxzTDdZeFfV75HTYqEV/6JzmJzeb95M+Vx0Lhax6Z1QHF3eOyBMnOkHncsWMWkUxOqETJJnVnEP7fcTAXOMwsGv0mQ7VZGMpCQPYoZJnA8IOyvniIF28qhBdx+76zd8lOUNtIMsrvNPkpV9AUSF7oIuBMduBUV0Gq68SUU8T/MBD80WzpbgRl1Fm0YlCulxlPKO6UR1L9ZblO+YYWQkFpDYxnVFXMqcQTWaUw6GZTN8NPaWoVXyrT47NOR9OBmlbsBQafOkaewH+/Kk+Zg0rE6FYUYkgjShggDqdt5cdDlKbMokZnWczu2H2NN8FktpDUoOR+cYFuOcd8QoPSea45X4nSz+70tDO7vUWc9IEO/x/WRH4U7D2C2DnwVELCfnVrpwl8GzRIaqmHTM9a+XGQldgnuVP0u/OO3X8HOdsC9OU0vy60nvSUCu5/pcwTQG0PQDNED7Or05bWq1XIn6FTQl9RnY0GRtJKOYIASix/OP2mR48fIj/nVtC+57Hb9wk7NVsbtPKVf+re/2NCyrsqbFmhqlIK180ggHWAsjjzWx/gME4mB2OdyFGiiBkOVBxt4AjfUPiVABxbLtgz8FXMOaWx43CNxPTVH1Y45VgI6cJnn59bbdmOHHWmipyBq488uUV+XDx7o0Ggac7Dc7sKUnJlP+YGjrpVoR4D/TtjaCiMwBLJ1TFlY0mpu3bCr/ysJz5yPNFzJYOaSTyFqAHyVx4m1/N6mIivx1+zNjd3A4l5Nze7GdQCnKgLAIXwgauwsN2lyd6E7SYadeh0V75SU0mOYDWpk4NqaCSPtdeZSEFlu+kVVcdJ+nu5GqVAvECyGUbCqcWDdrkVvtfl0F5E4Y5RHeEjLnPT1rXu+vcVX4IRH06WGmfqDQookMJIgEFS6WqZp02vwQ66XiDySeUVA33esDX0akcOwPQJIA2U2sg4/90Bv4mRMwHfRtGYpfAWJUMz9CAoYrITuKxlgSonxvHjDRNop8MabB6CYQdKz55I6rBuhUkCjt0KV6jYkBMOlTMxNsbETPoNkTlvE374hu/jwq64qSgrwfDyBOKAd2usI3M3k2sFVS+UQkJTdwLMwFFFVmymzJExNghI4IUUoXnhW2XxZxjIeZ8sq2J8mQ1fzKJ3pMNOGXZQmlc4NSjlSUY3YLcfHxPWma8dgbJpLbALXT7ewsn2HVylAnkvEhPLxRSxb23MlkMRXl0RkJlZfWMNVaI7EvULkqz3mX4avmsF9zzHR8ttA9YwcRZlb8mMeteGP//TlCK95tttgGeMeGJU5H56c8E3Kttk3MXNYVD0eqYsySLeKQLnnfPKbvwfCpHgqYG5/i62ZiS4VsPXKWMbLHXbxL6Qwqz9ZtpNH1Pfr86smQ/1b9fckFPC/xs1qF7AyDpu3WfMKCvirC22QgWRPIJyglIYZQEW3RpykduN3jX9nAXn9P8d9YKC4k5HlRpmGc6e1UTp/i1rksTGBREcRDfzBtGgdlCTTMdH3PFD5KEf1Tm8/5tTQeVGO97Uy+GaynbYtqJcuR1f/M2qIPMKpotrlGrVY3SotRSk1GRMafi6qYSaKSyRgdthAse1jlRB9fMzUvyGG1yyOGezqZG2mQ02cIPfyx1QAqPkaoQKOt6B6Yvu6JUxciOnhG3ErmCCNgsglM6RKYTOtHJV2IoVBkmZimbmajyYm73KWZWt9R8wsj8YVT75anbp0jQTC9C+VpV3u9GxD7zLjWLoCZ2PqzwDKLWKAv0rss8tUCtwMBk7UZPFchtlHboExKVdSHJW89gsjXpS9Tes8WpZqOvlrfKHBscpSKhPRuJp3xFd0iw1UbWV+tO1I/BbTPbt4qvv17Bre/re/hBJ8P7tm7Jk93SluPFq93xBJkcQCr7uvDqaw5dVs5lG++GcWKOnVzweRF9+Tzo+HT+XYV4JEk/ySw+Xx412HhNndF/otLi2cmsQTLIRrUhaZ/9gg5K9Xdazymt/6XWBusECVptWTm6voajfb61hng5V4nod094EeFuA66jqdl70fHtwbHv2zO31pwi0IdFPGoBfy2y19KM+1lDGlMUvliUkDUJ8D9rnQoT5flUvoFd0R/G1ZbvQ7sRdEBzE8pkL/ag8aePSheyLMh77SLcuW2rsxuqCswr9vQlBqLD/2GStq2SjKcDOLIGc8qSDxTUX60uNBwdhlH2oh/bgeKnULO895iqGK+V+SwFO75+Ne45xFImgcAwmlQIydCMuqyD/lX1pAtNV4Z6Dspo3cjEOQ1W5qJ8/3juBeycSV8t9CmhMS1SLxFILc5r7nmlc3XeHDqjJEr3eLQF5BkDSaEAR9wvRiq7MqQCEwtozUOxFla5+GEFxnlbedk7d+3nIBEGgZKlzw6ltvA5EZQCemhkz7iLc3Iu96FjFXxLiuib0Moy2lYMaBDWmp1eMucf1HmEzYy8XMXqrnCLN9kuaqsVvVmeZ/Hp2KJcDese7Fy1pLPKCIHC117nQ19tdWKphbl1TJaeQ0CzHeXtuISZHfDKUhWsVIh7MsN4rgZpmBQ0K8GbrV0l5DaBqKiubCHAL8DNHREk0pNloyB8dDSfVKUtX3utQhm70XejJRXTRSJAKM9UJeJHUfINEagwYEDzK4FOM+dZPZF+ssiMUMvV3rnX0wFfW1rQngeU/s1TbOyJ/pG1gTUo2pTvxh1fYxPV/shDI8OGbuK49N6LqvMcP2VdMfxckqNdrIm7CJ5t+ojGXQ1m7MbyhfctCQl8stYhEyDRWuo9wxHog/HWpLPBNYjl0mfErdQuxwvWcdcRaQyIftazrKNzksjdlU+3zr4O2uPXBHWR/H2IuUQk7qtI/KFC/n94RKh/WzKbaCh21sygFmc5jBgfh/WYo3PVsyQPf8ON0bKGAhKZjdPV8Du8ai4e/kV7z+J6cL4vsTG64fXVtWyeyHNcgV3NWGJ/Tkr6z3COCOq11Whr0WkkUu8IWWlz+KZDgR3QSBsPwbI2Ar2vfuaDWQ9WmQXu3E5bCOfGzga2aeyNk1hIM3BzDrOltDqE5krbEruKk896FBPCtvpKurBJr7nmb5UqKcAp8e7gNapso8QoZlDWappmRdMIDNiEOAVbVLFdebk+90kdGMz9pWaC3zg7ZaTpc0m+TBnGXUDanyRKk0k0b2Jvn3yk2Ywmeeg8zpwIoVXIhZa6yfqJkv6a3/1XftmPm1/if9zN5m/BljZf//nuf77Lw==')))));
                //echo $query ;exit;
$sqlquery = mysql_query($query);
 if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
					$decimal = get_decimal_placed($List->subdivid,$sheetid);
					//if($sheetid == 2)
					//{
						$measurement_contentarea = round($List->measurement_contentarea,$decimal);
					//}
					//else
					//{
						//$measurement_contentarea = $List->measurement_contentarea;
					//}
					if($page > 100)
					{
						/*if($_GET['varid'] == 1)
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
						}*/
						$currentline = $start_line + 7;
						$prevpage = 100;
						$page = $newmbookpageno;
						//$prevpage = $mpage;
						//$oldmbookno = $mbookno;
						$mbookno = $newmbookno;
						
					}
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right">
				<?php 
				if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }
				else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }
				?>
				</td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
		</tr>
	<?php
		echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1);
?>
			<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
<?php 			
			$currentline = $start_line + 7; $page++;
		}
		if($List->subdivid != $prev_subdivid) // THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		//if(($List->subdivid != $prev_subdivid) || (($List->subdivid != $prev_subdivid) &&($prev_date != $List->date) && ($prev_date != "")))// THIS IS FOR PRINT DATE, SHORTNOTE AND ITEM NAME
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid' ".$zone_clause;
		//echo $querycount."<br/>";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
		?>
					<tr height="" class="labelbold">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							//echo $prev_subdivid;
							}
						}
						?>
						</td>
						<td width="35" align="center"><?php echo "&nbsp"; ?></td>
						<td width="65" colspan="3" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
						</td>
						<td width="65" align="right"><?php echo number_format($contentarea,$prev_decimal,".",","); ?></td>
						<td width="32" align="center">
						<?php 
							if($prev_measure_type != 'st')
							{
								echo $prev_remarks;
							}
							{
								echo $prev_struct_unit;
							}
						?>
						</td>
					</tr>
					<?php 
					if($prev_measure_type == 'st')
					{
						$contentarea = ($contentarea/1000);
					?>
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							}
							?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="labelcontentblue">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					if(($prev_date != $List->date) && ($prev_date != ""))
					{
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					}
					/*if($prev_rowcount == 1)
					{
						mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
					}*/
					$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
					$prev_contentarea = 0;
					$currentline++;	
				}
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				//$len1 = strlen($List->shortnotes);
				//echo $length."<br/>";
				//$line_cnt1 = ceil($len1/96);
				//echo $List->subdiv_name." = ".$line_cnt1."<br/>";
				$snotes = $List->shortnotes;
				$degcelsius = "&#8451";
				$shortnotes = str_replace("DEGCEL","$degcelsius",$snotes);
				
				//  find the number of lines in item description
				$wrap_cnt1 = 0;
				$WrapReturn1 = getWordWrapCount($shortnotes,90);
				$shortnotes = $WrapReturn1[0];
				$wrap_cnt1 = $WrapReturn1[1];
		?>
			<!--<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5"></td>
				<td width="65">&nbsp;</td>
				<td width="32">&nbsp;</td>
			</tr>-->
			<tr height="">
				<td width="81" align="center"><?php echo $List->date; ?></td>
				<td width="48" align="center"><?php echo $List->subdiv_name; ?></td>
				<td colspan="5"><?php echo $shortnotes; ?></td>
				<td width="65"><?php echo "&nbsp"; ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
		<?php
		$currentline = $currentline+$wrap_cnt1+1;
		}
		
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888//		
		if(($List->subdivid == $prev_subdivid) && ($prev_date != $List->date))
		{
		$querycount = "SELECT COUNT(DISTINCT date) FROM mbookheader WHERE mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate' AND subdivid = '$List->subdivid' AND mbookheader.staffid = '$staffid' ".$zone_clause;
		//echo $querycount."<br/>";
		$querycount_sql = mysql_query($querycount);
		$res = mysql_fetch_array($querycount_sql); 
		$rowcount = $res[0];
				if($prev_subdivid != "")
				{
		?>
					<tr height="" class="labelbold">
						<td width="81"><?php echo "&nbsp"; ?></td>
						<td width="48" align="center"><?php echo "&nbsp"; ?></td>
						<td width="390" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							//echo $prev_subdivid;
							}
						}
						?>
						</td>
						<td width="35" align="center"><?php echo "&nbsp"; ?></td>
						<td width="65" colspan="3" align="right">
						<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
						</td>
						<td width="65" align="right"><?php echo number_format($contentarea,$prev_decimal,".",","); ?></td>
						<td width="32" align="center">
						<?php 
							if($prev_measure_type != 'st')
							{
								echo $prev_remarks;
							}
							{
								echo $prev_struct_unit;
							}
						?>
						</td>
					</tr>
					<?php 
					if($prev_measure_type == 'st')
					{
						$contentarea = ($contentarea/1000);
					?>
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							}
							?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="center" class="labelcontentblue">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					if(($prev_date != $List->date) && ($prev_date != ""))
					{
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					}
					/*if($prev_rowcount == 1)
					{
						mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
					}*/
					$sum1 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$prev_rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
					$prev_contentarea = 0;
					$currentline++;	
				}
				if($List->shortnotes == ""){ $List->shortnotes = $List->description; }
				//$len1 = strlen($List->shortnotes);
				//echo $length."<br/>";
				//$line_cnt1 = ceil($len1/96);
				//echo $List->subdiv_name." = ".$line_cnt1."<br/>";
				$snotes = $List->shortnotes;
				$degcelsius = "&#8451";
				$shortnotes = str_replace("DEGCEL","$degcelsius",$snotes);
				
				//  find the number of lines in item description
				$wrap_cnt2 = 0;
				$WrapReturn2 = getWordWrapCount($shortnotes,90);
				$shortnotes = $WrapReturn2[0];
				$wrap_cnt2 = $WrapReturn2[1];
		?>
			<!--<tr height="">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5"></td>
				<td width="65">&nbsp;</td>
				<td width="32">&nbsp;</td>
			</tr>-->
			<tr height="">
				<td width="81" align="center"><?php echo $List->date; ?></td>
				<td width="48" align="center"><?php echo $List->subdiv_name; ?></td>
				<td colspan="5"><?php echo $shortnotes; ?></td>
				<td width="65"><?php echo "&nbsp"; ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
			</tr>
		<?php
		$currentline = $currentline+$wrap_cnt2+1;
		}
		
		
		
		
//88888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888//			
		
		
		
				//$len2 = strlen($List->descwork);
				//echo $length."<br/>";
				//$line_cnt2 = ceil($len2/55);
				//echo $List->subdiv_name." = ".$line_cnt2."<br/>";
				
				
				$wrap_cnt3 = 0;	
				$WrapReturn3 = getWordWrapCount($List->descwork,50);
				$descwork = $WrapReturn3[0];
				$wrap_cnt3 = $WrapReturn3[1];
				
				
				$acc_remarks_str = $List->accounts_remarks;
				//echo $acc_remarks_str."<br/>";
				$exp_acc_remark = explode("@R@",$acc_remarks_str);
				$acc_remarks = $exp_acc_remark[0];
				if($acc_remarks != "")
				{
					$fcolor = "color:#F00000";
					$acc_remarks_count++;
				}
				else
				{
					$fcolor = "";
				}
				$accounts_str = $List->mbdetail_id."@#*#@".$List->subdiv_name."@#*#@".$List->descwork."@#*#@".$List->measurement_no."@#*#@".$List->measurement_l."@#*#@".$List->measurement_b."@#*#@".$List->measurement_d."@#*#@".$measurement_contentarea."@#*#@".$List->remarks."@#*#@".$decimal."@#*#@".$acc_remarks."@#*#@".$mbookno;
		?>
		<!---  THE BELOW ROW IS FOR PRINT EACH RECORD ------>
			<tr height="" style=" <?php echo $fcolor; ?> ">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php //echo "&nbsp"; ?>
				<input type="checkbox" name="check" id="ch_item" value="<?php echo $accounts_str; ?>" />
				</td>
				<td width="390"><?php echo $List->descwork; ?></td>
				<td width="35" align="right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
				<td width="65" align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",","); } ?></td>
				<td width="65" align="right"><?php if($measurement_contentarea != 0) { echo number_format($measurement_contentarea,$decimal,".",","); } ?></td>
				<td width="32" align="center">
				<?php 
				 if($List->measurement_no != 0) 
				{ 
					if($List->measure_type == 'st')
					{
						//echo $List->structdepth_unit;
					}
					else
					{
						//echo $List->remarks; 
					}
				} 
				?>
				</td>
			</tr>
		<?php
		//$contentarea = round(($prev_contentarea + $measurement_contentarea),3);
		$contentarea = ($prev_contentarea + $measurement_contentarea);
		$prev_subdivid = $List->subdivid; $prev_subdivname = $List->subdiv_name; $prev_divid = $List->div_id; $prev_contentarea = $contentarea;
		$prev_date = $List->date; $prev_rowcount = $rowcount; $prevpage = $page; $prev_mbookno = $mbookno; $prev_struct_unit = $List->structdepth_unit;
		$currentline = $currentline+$wrap_cnt3; 
		$prev_measure_type = $List->measure_type; $prev_remarks = $List->remarks; $prev_decimal = $decimal;
		$txtboxid++; 
	} 
	?>
		<input type="hidden" name="txt_textboxcount" id="txt_textboxcount" value="<?php echo $txtboxid; ?>" />
		<!----  THIS ROW IS FOR PRINT TOTAL OF THE LAST ROW IN WHILE LOOP ----->
			<tr height="" class="labelbold">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right">
				<?php 
						if($prev_measure_type != 'st')
						{
							if($prev_rowcount>1)
							{ 
							?>
							<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
							echo getcompositepage($sheetid,$prev_subdivid); 
							//echo $prev_subdivid;
							}
						}
						?>
				</td>
				<td width="35" align="center"><?php echo "&nbsp"; ?></td>
				<td width="65" colspan="3" align="right">
				<?php 
						if($prev_measure_type != 'st')
						{
							echo "Total";
						}
						?>
				</td>
				<td width="65" align="right"><?php echo number_format($contentarea,$prev_decimal,".",","); ?></td>
				<td width="32" align="center">
				<?php 
					if($prev_measure_type != 'st')
					{
						echo $prev_remarks;
					}
					{
						echo $prev_struct_unit;
					}
				?>
				</td>
			</tr> 
			<?php 
			if($prev_measure_type == 'st')
			{
				$contentarea = ($contentarea/1000);
				
					?>
						<tr height="" class="labelbold">
							<td width="81"><?php echo "&nbsp"; ?></td>
							<td width="48" align="center"><?php echo "&nbsp"; ?></td>
							<td width="390" align="right">
							<?php 
							if($prev_rowcount>1)
							{ 
							?>
								<input type="text" class="labelbold" name="txt_page"  style="width:100%; border:none; text-align:right;" id="txt_page<?php echo $txtboxid; ?>" />
							<?php 
							} 
							else 
							{ 
								echo getcompositepage($sheetid,$prev_subdivid);  
							}
							?>
							</td>
							<td width="35" align="center"><?php echo "&nbsp"; ?></td>
							<td width="65" colspan="3" align="right" class="labelcontentblue">Total</td>
							<td width="65" align="right" class="labelcontentblue">
							<?php echo number_format($contentarea,$prev_decimal,".",","); ?>
							</td>
							<td width="32" align="center"><?php echo $prev_remarks; ?></td>
						</tr>
					<?php
					}
					
						echo "<tr style='border:none'><td style='border:none' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='right' colspan='9'>&nbsp</td></tr>";
						echo "<tr style='border:none'><td style='border:none' align='center' colspan='3'></td><td style='border:none' align='left' colspan='3'>Prepared By</td><td style='border:none' align='center' colspan='3'>Checked By</td></tr>";
						$currentline+=3;
					?>
</table> 
 
<?php  
		/*if($prev_rowcount == 1)
		{
			mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
		}*/
		$currentline+=3;
		$sum2 .= $prev_subdivname.",".$prev_date.",".$prev_subdivid.",".$prev_divid.",".$contentarea.",".$rowcount.",".$page.",".$txtboxid.",".$prev_decimal.",".$prev_remarks."@"; 
}
$sum = $sum1.$sum2;
$split_sum = explode('@',$sum);
natsort($split_sum);
// THIS "FOR EACH" STATEMENT IS FOR GENERATE STRING AFTER "SORTING"........
foreach($split_sum as $key => $summ)
{
   if($summ != "")
   {
      $summary .= $summ.",";
   }
}
//echo $summary;exit;
$summary1 = explode(',',rtrim($summary,","));
$prev_val = "";$count = 0;
// THIS IS FOR LOOP IS FOR CHECK WHETHER SUMMARY PART HAS TO BE PRINT OR NOT...
for($i=0;$i<count($summary1);$i+=10)
{
	if($summary1[$i+5]>1)
	{
		$count++;
	}
	$prev_val = $summary1[$i+5];
}
if($count>0)
{
	if($currentline>27)
	{
	if($page == 100) { $mbookno = $newmbookno; }
	echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
	echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Page '.$page.'&nbsp;&nbsp</td></tr>';
	echo '</table>';
	echo "<p  style='page-break-after:always;'></p>";
		echo '<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$mbookno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
		echo $table;
		$currentline = $start_line + 8;$page++;
	}
	echo '<table width="875" border="0" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF"  class="label" style="border-style:none;">';
	echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Summary</td></tr>';
	$contentarea = 0;$prev_subdivid = "";
	for($i=0;$i<count($summary1);$i+=10)
	{
		//if($sheetid == 2)
		//{
		//sum_qty = round(sum_qty,$summary1[$i+8]);
			$sum_qty = round($summary1[$i+4],$summary1[$i+8]);
		//}
		//else
		//{
			//$sum_qty = $summary1[$i+4];
		//}
		if($currentline>40)
		{
		//if($page == 100) { $mbookno = $newmbookno; }
?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "C/o to page ".($page+1)." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$newmbookno,$table1); ?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php 
			$currentline = $start_line + 8;$page++;
		}
		//echo "PRE ID".$prev_subdivid."<br/>";
		if($summary1[$i+5]>1)
		{
			if(($summary1[$i+2] != $prev_subdivid) && ($prev_subdivid != ""))
			{
?>
			<tr height="" class="labelbold">
				<td width="81"><?php echo "&nbsp"; ?></td>
				<td width="48" align="center"><?php echo "&nbsp"; ?></td>
				<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid); ?></td>
				<td width="35" align="center"><?php echo "&nbsp"; ?></td>
				<!--<td width="65" align="center"></td>
				<td width="65" align="center"></td>-->
				<td width="195" colspan="3" align="right"><?php echo "Total"; ?></td>
				<td width="65" align="right"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
				<td width="32" align="center"><?php echo $pre_remarks; ?></td>
			</tr>
<?php 		
	//$summary_b .= $summary1[$i+7].",".$page."*";echo getabstractpage($sheetid,$prev_subdivid);
	//mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
			$contentarea = 0;	$currentline++;		
			}
?>
		<tr height="">
			<td width="81"><?php echo $summary1[$i+1]; ?></td>
			<td width="48" align="center"><?php echo $summary1[$i]; ?></td>
			<td width="390"><?php echo "B/f from page no ".$summary1[$i+6]; ?></td>
			<td width="35" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="center"><?php echo "&nbsp"; ?></td>
			<td width="65" align="right"><?php echo number_format($sum_qty,$summary1[$i+8],".",","); ?></td>
			<td width="32" align="center"><?php echo "&nbsp"; ?></td>
		</tr>
<?php	
			$summary_b .= $summary1[$i+7].",".$page.",".$summary1[$i].",";
			$contentarea = $contentarea + $sum_qty;	$currentline++;
			$prev_subdivid = $summary1[$i+2]; $prev_subdivname = $summary1[$i]; $prev_divid = $summary1[$i+3];	$prev_textboxid = $summary1[$i+7];
			$pre_page = $page; $pre_decimal = $summary1[$i+8]; $pre_remarks = $summary1[$i+9];
		}
	}
?>
		<tr height="" border="1px" style="border-bottom:solid; border-bottom-color:#CACACA;" class="labelbold">
			<td width="81"><?php echo "&nbsp"; ?></td>
			<td width="48" align="center"><?php echo "&nbsp"; ?></td>
			<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid); ?></td>
			<td width="35" align="center"><?php echo "&nbsp"; ?></td>
<!--		<td width="65" align="center"></td>
			<td width="65" align="center"></td>-->
			<td width="195" colspan="3" align="right"><?php echo "Total"; ?></td>
			<td width="65" align="right"><?php echo number_format($contentarea,$pre_decimal,".",","); ?></td>
			<td width="32" align="center"><?php echo $pre_remarks; ?></td>
		</tr>
<?php 
//mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$page,$contentarea,$abstmbookno,$rbn,$userid);
echo '</table>';
echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">&nbsp;<br/><br/><br/><br/>Page '.$page.'<br/><br/><br/><br/>&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp</td>
		</tr>';
echo '</table>';
}
else
{
echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp;&nbsp</td>
		</tr>';
echo '<tr style="border-style:none;">
		<td style="border-style:none;" width="53%" align="right">Page '.$page.'&nbsp;&nbsp</td>
		<td style="border-style:none;" colspan="4" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>&nbsp;</td>
		</tr>';
echo '</table>';
}


$staffid_acc 		= $_SESSION['sid_acc'];
$staff_level_str 	= getstafflevel($staffid_acc);
$exp_staff_level_str = explode("@#*#@",$staff_level_str);
$staff_roleid 		= $exp_staff_level_str[0];
$staff_levelid 		= $exp_staff_level_str[1];
$AccVerification = AccVerificationCheck($sheetid,$rbn,$mbookno,'staff',$staff_levelid,'MB');
//echo $AccVerification;exit;
?>
<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($summary_b,","); ?>"  />
<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"/>
<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="<?php echo $zone_id; ?>"/>
<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $rbn; ?>"/>
<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $mbookno; ?>"/>
<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>


<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="submit" name="Back" id="Back" value=" Back " /> </div>
<?php if($AccVerification == 0){ ?>
	<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>
	<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Send to Civil " /></div>
<?php } ?>
	<!--<div class="btn_inside_sect">
		<a href="exportexcel.php?workno=<?php echo $sheetid;?>" style="text-decoration:none">
			<input type="button" class="backbutton" name="export_excel" value="Excel" />
		</a>
	</div>-->
</div>
<!--<table border="0" width="875" style="border-style:none" align="center" bgcolor="#000000" class='labelcontent printbutton'>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
		<td border="0" style="border-style:none">&nbsp;
		</td>
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none" height="35px;">
		<td border="0" style="border-style:none" align="right">
			<input type="submit" name="Back" value=" Back " /> 
		</td>
		<td border="0" style="border-style:none" width="20px">&nbsp;
		</td>
		<td border="0" style="border-style:none" align="left">
			<input type="button" class="backbutton" name="print" value=" Print " /> 
		</td>
	</tr>
</table>  -->
		<div id="basic-modal-content">
			<div align="center" class="popuptitle gradientbg">Accounts Section - Comment </div>
			<div style="float:left; padding-top:4px; width:267px; height:490px;">
				<img src="images/accounts_cmt_mb_bg_2.jpg" width="265" height="532" />
			</div>
			<div style="float:right; padding-top:50px; width:727px;" class="modal_content">
				<!--<input type="text" name="txt_item_name_modal" id="txt_item_name_modal"  />-->
				<table width="95%" bgcolor="#FFFFFF" class="label" align="center">
					<tr>	
						<td width="40%">Item No.</td>
						<td><input type="text" name="txt_item_no_acc" id="txt_item_no_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Work Description</td>
						<td><textarea name="txt_work_desc_acc" id="txt_work_desc_acc" rows="3" style="width:460px" class="textbox_modal label" readonly=""></textarea></td>
					</tr>
					<tr>
						<td>No.</td>
						<td><input type="text" name="txt_no_acc" id="txt_no_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Length</td>
						<td><input type="text" name="txt_length_acc" id="txt_length_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Breadth</td>
						<td><input type="text" name="txt_breadth_acc" id="txt_breadth_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Depth</td>
						<td><input type="text" name="txt_depth_acc" id="txt_depth_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Contents of Area</td>
						<td>
						<input type="text" name="txt_contents_area_acc" id="txt_contents_area_acc" class="textbox_modal label" readonly="" style="width:460px" />
						<!--<input type="text" name="txt_item_unit_acc" id="txt_item_unit_acc" class="textbox_modal label" readonly="" style="text-align:left" />-->
						<input type="hidden" name="txt_mbdetail_id_acc" id="txt_mbdetail_id_acc" class="textbox_modal" readonly="" />
						<input type="hidden" name="txt_mbook_no_acc" id="txt_mbook_no_acc" class="textbox_modal" readonly="" />
						</td>
					</tr>
				</table>
			</div>
			<div style="float:right; width:727px; height:145px;" align="center">
				<p style="text-align:left" class="label textbox_modal">&nbsp;&nbsp;&nbsp;&nbsp;Accounts Comment: </p>
				<textarea name="txt_accounts_remarks" id="txt_accounts_remarks" placeholder="Enter your comment here..." class="label" rows="4" style="width:684px"></textarea>
			</div>
			<div align="center" style="float:right; width:727px; height:80px;">
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="buttonstyle" onclick="SaveData_Accounts()" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="buttonstyle" onclick="CancelData()" /></div>
			</div>
			<!--<div align="left" class="label">Accounts Comments</div>
			<div align="left" class="label">
				<textarea name="txt_accounts_remarks" id="txt_accounts_remarks" rows="4" style="width:250px"></textarea>
			</div>-->
		</div>
</form>
<?php
$accurl = "";
if($msg != "")
{
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $staff_level_str 		= getstafflevel($staffid_acc);
	 $exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	 $staff_roleid 			= $exp_staff_level_str[0];
	 $staff_levelid 		= $exp_staff_level_str[1];
	 
	 $minmax_level_str 		= getstaff_minmax_level();
	 $exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	 $min_levelid 			= $exp_minmax_level_str[0];
	 $max_levelid 			= $exp_minmax_level_str[1];
	if($staff_levelid == $min_levelid)
	{
		$accurl = "MeasurementBookPrint_staff_Accounts.php";
	}
	else
	{
		$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
	}
    //header('Location: '.$accurl);
}
?>
<style>

</style>
    </body>
<script>

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
	if(msg != "")
	{
		if(success == 1)
		{
			/*swal("", msg, "success");*/
				swal({ 
				  	title: "",
				   	text: msg,
					type: "success" 
				  },
				  function(){
					window.location.href = '<?php echo $accurl; ?>';
				});
		}
		else
		{
			swal(msg, "", "");
		}
					
	}
	};
</script>
</html>
	<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
	<script type='text/javascript' src='js/basic_model_jquery.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
	<script>
		function saveDataDetails_Accounts()
		{
			var mbdetail_id = document.getElementById("txt_mbdetail_id_acc").value;
			var remarks_acco = document.getElementById("txt_accounts_remarks").value;
			var mbookno = document.getElementById("txt_mbook_no_acc").value;
			var sheetid = document.getElementById("txt_sheetid").value;
			var zone_id = document.getElementById("txt_zone_id").value;
			var rbn = document.getElementById("txt_rbn_no").value;
			var linkid = document.getElementById("txt_linkid").value;
			var staffid_acc = document.getElementById("txt_staffid_acc").value;
			var staff_levelid_acc = document.getElementById("txt_staff_levelid_acc").value;
			var mtype = "G";
			
			$.post("Accounts_Comments_Update_MBook.php", {mbdetail_id: mbdetail_id, remarks: remarks_acco, mbookno: mbookno, sheetid: sheetid, zone_id: zone_id, rbn: rbn, mtype: mtype, linkid: linkid, staffid: staffid_acc, levelid: staff_levelid_acc }, function (data) {
			//alert(data)
				if(data == 1)
				{
					location.reload();
				}
        	});
		}
		function SaveData_Accounts()
		{
			swal({   title: "Are you sure?",   
				text: "You want to update this data..?!",   
				type: "",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, Update!",   
				cancelButtonText: "No, Cancel!",   
				closeOnConfirm: false,   
				closeOnCancel: false }, 
				function(isConfirm){   
				if (isConfirm) 
				{     
					saveDataDetails_Accounts();  
				} 
				else 
				{     
					swal("Cancelled", "Your data not updated:)", "");   
				} 
			});
		}
		function CancelData()
		{
			swal({   title: "Are you sure?",   
				text: "You want to Cancel this operation..?!",   
				type: "",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, Cancel!",   
				cancelButtonText: "No, Stay on this!",   
				closeOnConfirm: true,   
				closeOnCancel: false }, 
				function(isConfirm){   
				if (isConfirm) 
				{   
					  
					$.modal.close();  
				} 
				else 
				{     
					swal({   
					title: "Please Wait!",   
					text: "Your Page will be redirected..",   
					timer: 2000,   showConfirmButton: false 
					});
				} 
			});
		}
		
		/*function MBookLockReleased()
		{
			var sheetid = document.getElementById("txt_sheetid").value;
			var zone_id = document.getElementById("txt_zone_id").value;
			var rbn = document.getElementById("txt_rbn_no").value;
			var mtype = "G";
			var genlevel = "staff";
			//alert();exit();
			$.post("MeasurementBook_Lock_Release.php", {sheetid: sheetid, zone_id: zone_id, rbn: rbn, mtype: mtype, genlevel: genlevel}, function (data) {
			alert(data)
				if(data == 1)
				{
					alert(data)
				}
        	});
		}*/

	</script>
<style>
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
.gradientbg {
  /* fallback */
  background-color: #014D62;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;

  /* Safari 4-5, Chrome 1-9 */
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));

  /* Safari 5.1, Chrome 10+ */
  background: -webkit-linear-gradient(top, #0A9CC5, #037595);

  /* Firefox 3.6+ */
  background: -moz-linear-gradient(top, #0A9CC5, #037595);

  /* IE 10 */
  background: -ms-linear-gradient(top, #0A9CC5, #037595);

  /* Opera 11.10+ */
  background: -o-linear-gradient(top, #0A9CC5, #037595);
}
div.modal_content tr, div.modal_content td
{
	padding-top:5px;
	padding-bottom:5px;
	color:#00008b;
	font-weight:bold;
	padding-left:3px;
}
.textbox_modal
{
	border:none;
	color:#00008b;
	font-weight:bold;
}
.buttonstyle
{
	background-color:#0A9CC5;
	width:80px;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
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

</style>