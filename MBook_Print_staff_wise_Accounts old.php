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
$selectmbook_detail = " select date(max(fromdate)) as fromdate, date(max(todate)) as todate, abstmbookno, is_finalbill FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '1' AND rbn = '$rbn' AND zone_id = '$zone_id' group by sheetid";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno;  $is_finalbill = $Listmbdetail->is_finalbill;
}

/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////
/*$selectmbookno = "select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid' AND staffid = '$staffid'";
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


$select_new_mbook_no_query1 = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '1' AND rbn = '$rbn' AND mtype = 'G' AND  zone_id = '$zone_id' and genlevel = 'staff'";
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



$select_new_mbook_no_query = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '2' AND rbn = '$rbn' AND mtype = 'G' AND  zone_id = '$zone_id' and genlevel = 'staff'";
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
}
$mpage = $mbookpageno;*/
/////////////////////////// COMMENTED ON 22.07.2019 FOR MULTIPLE MB SELECTION ////////////////////////////////////////


//$mbookpageno = $objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw');
//$mbookpageno = $mbookpageno+1;

/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/

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
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'G' and zone_id = '$zone_id' and genlevel = 'staff' order by mbookorder asc";
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

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class='label'>
<?php echo $table1; ?>
<?php

eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
                //echo $query ;exit;
$sqlquery = mysql_query($query);
 if ($sqlquery == true) 
{
	while ($List = mysql_fetch_object($sqlquery)) 
	{
		$decimal = get_decimal_placed($List->subdivid,$sheetid);
		$measurement_contentarea = round($List->measurement_contentarea,$decimal);
		/*if($page > 100){
			$currentline = $start_line + 7;
			$prevpage = 100;
			$page = $newmbookpageno;
			//$prevpage = $mpage;
			//$oldmbookno = $mbookno;
			$mbookno = $newmbookno;
		}*/
		if($currentline>40)
		{ 
		
		?>
		<tr height="" class="labelbold">
				<td width="81" align="center"></td>
				<td width="48" align="center"></td>
				<td colspan="5" align="right">
				<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
				C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
				</td>
				<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$prev_decimal,".",","); } ?></td>
				<td width="32"><?php echo "&nbsp"; ?></td>
		</tr>
	<?php
		echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1);
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
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
							echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); 
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
								echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id);  
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
		//if($page == 100) { $mbookno = $newmbookno; }
			echo '<table width="875" style="border-style:none;" cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" class="label">';
			echo '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">Page '.$page.'&nbsp;&nbsp</td></tr>';
			echo '</table>';
			echo "<p  style='page-break-after:always;'></p>";
			$MbookDP = ''; if($page >= 100){ $MbookDP = $NextMBList[$NextMbIncr]; }else{ $MbookDP = $mbookno; }
			echo '<table width="875" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
				<tr style="border:none;"><td align="right" style="border:none;">General M.Book No. '.$MbookDP.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
				</table>';
			echo $table;
			$currentline = $start_line + 8;$page++;
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
		}
	
	/*if($page > 100){
		$page = $newmbookpageno;
		$mbookno = $newmbookno;
	}*/
	
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
	<td colspan="5" align="right">
	<?php /*if($page == 100){ echo "C/o to page ".(0+1)." /General MB No.".$newmbookno; }else { echo "C/o to page ".($page+1)." /General MB No.".$mbookno; }*/ ?>
	C/o to page <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/General MB No.<?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/General MB No.<?php echo $mbookno; } ?>
	</td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1); ?>
<tr height="" class="labelbold">
	<td width="81" align="center"></td>
	<td width="48" align="center"></td>
	<td colspan="5" align="right"><?php echo "B/f from page ".$page." /General MB No.".$mbookno; ?></td>
	<td width="65" align="right"><?php if($contentarea != 0) { echo number_format($contentarea,$pre_decimal,".",","); } ?></td>
	<td width="32"><?php echo "&nbsp"; ?></td>
</tr>
<?php 
			$currentline = $start_line + 8;
			//if($page == 100){ $page = $newmbookpageno;  $mbookno = $newmbookno; }else{ $page++; }
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			$page++;
			if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
			//$page++;
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
				<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); ?></td>
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
			<td width="390" align="right"><?php echo getcompositepage($sheetid,$prev_subdivid,$rbn,$zone_id); ?></td>
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
<script type="text/javascript">
   $(function(){ 
   var getstr = document.getElementById("txt_boxid_str").value;
   var splitval = getstr.split(","); //alert(splitval.length);
   var x=0;
   for(x=0;x<splitval.length;x+=3)
   {
   		document.getElementById("txt_page"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No. "+"<?php echo $mbookno; ?>"; 
   }
   });
</script>
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

		jQuery(function ($) 
		{
			
			$('input[name="check"]').click(function (e) 
			{
				var row_val = this.value;
				var split_val = row_val.split("@#*#@");
				var ac1;
				for(ac1 = 0; ac1<split_val.length; ac1++)
				{
					var mbdetail_id 	= split_val[0];
					var item_no 		= split_val[1];
					var descwork 		= split_val[2];
					var measurement_no 	= Number(split_val[3]);
					var measurement_l 	= Number(split_val[4]);
					var measurement_b 	= Number(split_val[5]);
					var measurement_d 	= Number(split_val[6]);
					var contentarea 	= Number(split_val[7]);
					var item_unit 		= split_val[8];
					var decimal 		= split_val[9];
					var remarks_acc 	= split_val[10];
					var mbook_no 		= split_val[11];
					$('#txt_item_no_acc').val(item_no);
					$('#txt_work_desc_acc').val(descwork);
					$('#txt_no_acc').val(measurement_no);
					$('#txt_length_acc').val(measurement_l.toFixed(decimal));
					$('#txt_breadth_acc').val(measurement_b.toFixed(decimal));
					$('#txt_depth_acc').val(measurement_d.toFixed(decimal));
					$('#txt_contents_area_acc').val(contentarea.toFixed(decimal)+" "+item_unit);
					//$('#txt_item_unit_acc').val(item_unit);
					$('#txt_mbdetail_id_acc').val(mbdetail_id);
					$('#txt_accounts_remarks').val(remarks_acc);
					$('#txt_mbook_no_acc').val(mbook_no);
				}
				//$('#txt_item_name_modal').val(val);
				$('#basic-modal-content').modal();
			});
			
			/*$('#Back').click(function (e) 
			{
				MBookLockReleased();
			});
			$('#accept').click(function (e) 
			{
				MBookLockReleased();
			});
			$('#send_to_civil').click(function (e) 
			{
				MBookLockReleased();
			});*/
		});
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