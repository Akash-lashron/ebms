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
$newmbookno='';
$staffid_acco 	= $_SESSION['sid'];
$userid 		= $_SESSION['userid'];
$staff_levelid  = $_SESSION['levelid'];
$mbooktype = "G";
$staffid_acc 			= $_SESSION['sid_acc'];
$staff_level_str 		= getstafflevel($staffid_acc);
$exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
$staff_roleid 			= $exp_staff_level_str[0];
$staff_levelid 			= $exp_staff_level_str[1];
	 
$minmax_level_str 		= getstaff_minmax_level();
$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
$min_levelid 			= $exp_minmax_level_str[0];
$max_levelid 			= $exp_minmax_level_str[1];

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
function check_line($title,$table,$page,$mbookno,$newmbookno,$table1,$gen_version)
{
	if($page == 100) { $mbookno = $newmbookno; }
	$row = '<tr style="border-style:none;"><td style="border-style:none;" colspan="9" align="center">&nbsp;<br/>Page '.$page.'&nbsp;&nbsp</td></tr>';
	$row = $row."</table>";
	$row = $row."<p  style='page-break-after:always;'></p>";
	$row = $row.'<table width="875" border="0"  cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="center" style="border:none;">General M.Book No. '.$mbookno.' (Print version : '.$gen_version.')&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
	$row = $row.$table;
	$row = $row.'<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">';
	$row = $row.$table1;
	echo $row;
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
	$view 		= $_POST['txt_view'];
	$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and mtype = 'G' and genlevel = 'composite'";
	$lock_release_sql = mysqli_query($dbConn,$lock_release_query);
	//echo $lock_release_query;exit;
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
		$accurl = "MeasurementBookPrint_staff_Accounts.php?view=".$view;
	}
	else
	{
		//$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
		$accurl = "MeasurementBookPrint_staff_Accounts.php?view=".$view;
	}
    header('Location: '.$accurl);
}
if($_POST["send_to_civil"] == " Return to EIC ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= 0;//$_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $view 					= $_POST['txt_view'];
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_alas_query = "update al_as set ret_status = 'Y' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 $update_alas_sql 	= mysqli_query($dbConn,$update_alas_query);
	 
	 $update_query = "update send_accounts_and_civil set sa_ac = 'SC', accounts_comment ='$acc_comment_log', accounts_comment ='$acc_comment_log', locked_status = '', level_status = 'F', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql = mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg = "Sub-Abstract Returned to Civil Section";
		$success = 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Sub-Abstract - ".$sc_mbook_no." rejected to Civil in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
		$InsertLogQuery = "INSERT INTO acc_log_detail SET sheetid = '$sheetid', rbn = '$rbn', mbookno = '$sc_mbook_no', log_date = NOW(), staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."', sent_by = 'ACC'";
		$InsertLogSql   = mysqli_query($dbConn,$InsertLogQuery);
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 UpdateCivilViewlevel($sc_sheetid, $sc_rbnno);
	/* $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'composite', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysqli_query($dbConn,$linsert_log_query);*/
}
if($_POST["accept"] == " Accept Sub-Abstract ")
{
	//echo "hai";exit;
    //header('Location: MeasurementBookPrint_staff_Accounts.php');
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
	 	$level_status = "P";
		$staff_levelid = $staff_levelid + 1;
		$staff_clause = " acc_staffid_L1 = '".$staffid_acc."' ";
	 }
	 else
	 {
	 	$level_status = "F";
		$staff_levelid = $staff_levelid;
		$staff_clause = " acc_staffid_L2 = '".$staffid_acc."' ";
	 }*/
	 $view 					= $_POST['txt_view'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= 0;// $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 
	 $update_query = "update send_accounts_and_civil set sa_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql = mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg = "Sub-Abstract Verified & Accepted in Final Level";
		$success = 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Sub-Abstract - ".$sc_mbook_no." verified and final level accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 /*$linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'composite', status = 'AC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysqli_query($dbConn,$linsert_log_query);*/
	 
}
if(isset($_POST["forward"])){
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $fw_level 				= $_POST['txt_fw_level'];
	 $view 					= $_POST['txt_view'];
	 
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 /*$update_query 	= "update acc_log set AC_status = 'A', comment ='$acc_comment_log', staffid = '$staffid_acc', 
	 				  levelid= CASE WHEN (levelid = 'R') THEN '$fw_level' ELSE '".$_SESSION['levelid']."' END ,
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END  
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";*/
	 $update_query 	= "update acc_log set comment ='$acc_comment_log', staffid = '$staffid_acc', 
	 				  levelid= CASE WHEN (AC_status = 'R') THEN '$fw_level' ELSE '".$_SESSION['levelid']."' END , 
					  AC_status= CASE WHEN (AC_status = 'R') THEN '' ELSE 'A' END , 
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
					  
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 
	 $RejCnt = 0;
	 $select_reject_query 	= "select logid from acc_log where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and (AC_status = 'R' OR AC_status = '') and levelid = '".$_SESSION['levelid']."'";
	 $select_reject_sql 	= mysqli_query($dbConn,$select_reject_query);
	 if($select_reject_sql == true){
	 	$RejCnt = mysqli_num_rows($select_reject_sql);
	 }
	 //echo $select_reject_query;exit;
	 if($RejCnt == 0){
	 
		$update_query = "update acc_log set 
		AC_status = '',  
		levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 
	 	$update_sql = mysqli_query($dbConn,$update_query);
	 
	 	$update_level_query = "update al_as set status = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
		$update_level_sql = mysqli_query($dbConn,$update_level_query);
	 }
	 
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Forwarded to Next level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Sub-Abstract - ".$sc_mbook_no." accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'composite'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Previous Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Sub-Abstract - ".$sc_mbook_no." returned back to ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
}



$selectmbook_detail 	= 	"select DISTINCT fromdate, todate, rbn, abstmbookno, staffid, is_finalbill FROM mbookgenerate WHERE sheetid = '$sheetid'";// AND flag = '1'";
$selectmbook_detail_sql = 	mysqli_query($dbConn,$selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysqli_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbookno = $Listmbdetail->abstmbookno;
	$is_finalbill 		= 	$Listmbdetail->is_finalbill;
	$staffid 			= 	$Listmbdetail->staffid;
}
$selectmbookno 			= 	"select mbname, old_id from oldmbook WHERE mbook_type = 'G' AND sheetid = '$sheetid'";
$selectmbookno_sql 		= 	mysqli_query($dbConn,$selectmbookno);
if(mysqli_num_rows($selectmbookno_sql)>0)
{
	$Listmbookno 		= 	mysqli_fetch_object($selectmbookno_sql);
	$mbookno 			= 	$Listmbookno->mbname; 	$oldmbookid 	= 	$Listmbookno->old_id;
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	$mbookpage_sql 		= 	mysqli_query($dbConn,$mbookpage);
	$ResList1 			=   mysqli_fetch_object($mbookpage_sql);
	$mbookpageno 		= 	$ResList1->mbpage+1;
	
	$selectnewmbookno 	= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1' AND mbno != '$mbookno'";
	$selectnewmbookno_sql 	= 	mysqli_query($dbConn,$selectnewmbookno);
	$ResList2 			=   mysqli_fetch_object($selectnewmbookno_sql);
	$newmbookno 		= 	$ResList2->mbno;
	
	$newmbookpage 		= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$newmbookno'";
	$newmbookpage_sql 	= 	mysqli_query($dbConn,$newmbookpage);
	$ResList3 			=   mysqli_fetch_object($newmbookpage_sql);
	$newmbookpageno 	= 	$ResList3->mbpage+1;//@mysqli_result($newmbookpage_sql,'mbpage')+1;
	
$newmbookpageno 		= 	$objBind->DisplayPageDetails($newmbookno,$newmbookno,$sheetid,'cw',$rbn,$staffid);
$newmbookpageno 		= 	$newmbookpageno +1;	
}
else
{
	$selectmbookno 		= 	"select DISTINCT mbno from mbookgenerate WHERE sheetid = '$sheetid' AND flag = '1'";
	$selectmbookno_sql 	= 	mysqli_query($dbConn,$selectmbookno);
	$ResList4 			=   mysqli_fetch_object($selectmbookno_sql);
	$mbookno 			= 	$ResList4->mbno;
	//$mbookno 			= 	@mysqli_result($selectmbookno_sql,'mbno');
	
	$mbookpage 			= 	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$mbookno'";
	//echo $mbookpage;
	$mbookpage_sql 		= 	mysqli_query($dbConn,$mbookpage);
	$ResList5 			=   mysqli_fetch_object($mbookpage_sql);
	$mbookpageno 		= 	$ResList5->mbpage+1;
	//$mbookpageno 		= 	@mysqli_result($mbookpage_sql,'mbpage')+1;
}
$mbookpageno 			= 	$objBind->DisplayPageDetails($mbookno,$mbookno,$sheetid,'cw',$rbn,$staffid);
//echo "Page = ".$mbookpageno;
$mbookpageno 			= 	$mbookpageno+1;
/*echo "NEW MB ".$newmbookno."<br/>";
echo "NEW MB PAGE ".$newmbookpageno."<br/>";
echo "OLD MB ".$mbookno."<br/>";
echo "OLD MB PAGE ".$mbookpageno."<br/>";
exit;*/

$mbookpageNew 			= 	"select startpage, gen_version from mymbook WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND mbno = '$mbookno' and genlevel = 'composite' and mtype = 'G'";
$mbookpageNew_sql 		= 	mysqli_query($dbConn,$mbookpageNew);
$ResList6 				=   mysqli_fetch_object($mbookpageNew_sql);
$mbookpageno 			= 	$ResList6->mbpage;
$gen_version 			= 	$ResList6->gen_version;
//$mbookpageno 			= 	@mysqli_result($mbookpageNew_sql,'mbpage');//+1;
//$gen_version 			= 	@mysqli_result($mbookpageNew_sql,'gen_version');//+1;

$mpage = $mbookpageno;
//echo $mpage;
//$sheetid=$_SESSION["sheet_id"]; 
//$fromdate = $_SESSION['fromdate'];
//$todate = $_SESSION['todate'];
//$mbookno = $_SESSION["mb_no"];    
//$mpage = $_SESSION["mb_page"];
//$mbno_id = $_SESSION["mbno_id"];
//$rbn = $_SESSION["rbn"];
//$abstmbookno = $_SESSION["abs_mbno"];
$query 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, tech_sanction, name_contractor, computer_code_no, agree_no, rbn FROM sheet WHERE sheet_id ='$sheetid' ";
$sqlquery 	= 	mysqli_query($dbConn,$query);
if ($sqlquery == true) 
{
    $List 				= 	mysqli_fetch_object($sqlquery);
    $work_name 			= 	$List->work_name;    
	$tech_sanction 		= 	$List->tech_sanction;
    $name_contractor 	= 	$List->name_contractor;    
	$agree_no 			= 	$List->agree_no; 
	$work_order_no 		= 	$List->work_order_no; 
	$ccno 				= 	$List->computer_code_no;
    //if($List->rbn  ==0) { $runn_acc_bill_no =1;  } else { $runn_acc_bill_no =$List->rbn + 1;}
	$runn_acc_bill_no = $rbn;
    //$_SESSION["currentrbn"]=$runn_acc_bill_no;
}

$length 	= 	strlen($work_name);
//echo $length."<br/>";
$start_line = 	ceil($length/87);
//echo $start_line;
/*$mbookgeneratedelsql = "DELETE FROM mbookgenerate WHERE flag =1";
$result = dbQuery($mbookgeneratedelsql);
function mbookgenerateinsert($staffid,$sheetid,$prev_divid,$prev_subdivid,$fromdate,$todate,$mbookno,$mpage,$contentarea,$abstmbookno,$rbn,$userid)
{ 
   $querys="INSERT INTO mbookgenerate set sheetid='$sheetid',divid='$prev_divid',subdivid='$prev_subdivid',
       fromdate ='$fromdate',todate ='$todate' ,mbno='$mbookno',flag=1,rbn='$rbn', abstmbookno = '$abstmbookno',
            mbgeneratedate=NOW(), staffid='$staffid', mbpage='$mpage', mbtotal='$contentarea', active=1, userid='$userid'";
 //echo $querys."<br>";
   $sqlquerys = mysqli_query($dbConn,$querys);
}*/
function getabstractpage($sheetid,$subdivid)
{
	global $dbConn;
	$select_abs_page_query 	= 	"select abstmbookno, abstmbpage from measurementbook_temp WHERE sheetid = '$sheetid' AND subdivid = '$subdivid'";
	$select_abs_page_sql 	= 	mysqli_query($dbConn,$select_abs_page_query);
	$ResList6 				=   mysqli_fetch_object($select_abs_page_sql);
	$abstmbookno 			= 	$ResList6->abstmbookno;
	$abstractpage 			= 	$ResList6->abstmbpage;
	//$abstmbookno 			= 	@mysqli_result($select_abs_page_sql,0,'abstmbookno');
	//$abstractpage 			= 	@mysqli_result($select_abs_page_sql,0,'abstmbpage');
	echo "C/o to Page ".$abstractpage." /Abstract MB No. ".$abstmbookno;
}
function stafflist($subdivid,$date,$sheetid)
{
	global $dbConn;
	$date = dt_format($date);
	$staff_design_sql = "select  DISTINCT staff.staffname, designation.designationname, mbookheader.date from staff 
	INNER JOIN designation ON (designation.designationid = staff.designationid) 
	INNER JOIN mbookheader ON (mbookheader.staffid = staff.staffid)
	WHERE staff.staffid = mbookheader.staffid AND staff.active = 1 AND designation.active = 1 AND mbookheader.date = '$date' AND mbookheader.sheetid = '$sheetid' AND mbookheader.subdivid = '$subdivid'";
	$staff_design_query = mysqli_query($dbConn,$staff_design_sql);
	while($staffList = mysqli_fetch_object($staff_design_query))
	{
		$staffname 		= 	$staffList->staffname;
		$designation 	= 	$staffList->designationname;
		$result 	   .= 	$staffname."*".$designation."*";
	}
	return rtrim($result,"*");
	//echo $staff_design_sql."<br/";
}

$Abst_check_view = 0;
if($staff_levelid == $min_levelid)
{
	$check_abstract_query = "select * from send_accounts_and_civil where (mb_ac = 'SA' OR mb_ac = 'SC') AND sheetid = '$sheetid' AND rbn = '$rbn'";
}
else
{
	$check_abstract_query = "select * from send_accounts_and_civil where mb_ac = 'AC' AND level = '$staff_levelid' AND level_status = 'P' AND sheetid = '$sheetid' AND rbn = '$rbn'";
}
$check_abstract_sql = mysqli_query($dbConn,$check_abstract_query);
if($check_abstract_sql == true)
{
	if(mysqli_num_rows($check_abstract_sql)>0)
	{
		$Abst_check_view = 1;
	}
	else
	{
		$Abst_check_view = 0;
	}
}
else
{
	$Abst_check_view = 0;
}
//echo $check_abstract_query;
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'G' and genlevel = 'composite' order by mbookorder asc";
$SelectMBookSql = mysqli_query($dbConn,$SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysqli_num_rows($SelectMBookSql)>0){
		while($MBList = mysqli_fetch_object($SelectMBookSql)){
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
	table{ 
		border-collapse: collapse; 
	}
	td{ 
		border: 1px solid #A0A0A0; 
		padding-top:5px;
		padding-bottom:5px;
	}
	@media screen 
	{
        div.divFooter{
            display: none;
        }
    }
    @media print 
	{
        div.divFooter{
            position: fixed;
            bottom: 0;
        } 
		.header{
			display: none !important;
		}
		.printbutton{
			display: none !important;
		}
	}
	.ui-dialog > .ui-widget-header {background: #20b2aa; font-size:12px;}
	.breakAfter{
		page-break-before: always;
	}
	.labelcontent{
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
	.cobffont{
		font-size:11px;
	}
	.label, .labelcenter, .labelheadblue{
		font-size:13px;
	}
	.spanbtn{
		padding:2px 8px;
		border:2px solid #D00843;
		color:#05478F;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:600;
		font-size:11px;
		text-decoration:none;
		border-radius:25px;
		
	}
	.spanbtn:hover{
		background:#D00843;
		color:#fff;
	}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
	function goBack(){
		url = "MeasurementBookPrint_composite_Accounts.php";
		window.location.replace(url);
	}
</script>
<body id="top" bgcolor="" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--<table width="875" style="position:fixed; text-align:center; left:194px;" height="60px" align="center" bgcolor="#20b2aa" class='header'>
<tr>
<td style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">GENERAL MEASUREMENT BOOK</td>
</tr>
</table><br/><br/><br/>-->
<form name="form" id="form" method="post">
			<?php
			//echo "<div align='center'><div align='right' style='width:875px;'><a href='ViewSheet.php?sheetid=".$sheetid."' class='spanbtn' target='_blank'><span>SOQ<span></a> &nbsp;<a href='ComparativeStatement.php?sheetid=".$sheetid."' class='spanbtn' target='_blank'><span>CST<span></a>&nbsp;<a href='#' class='spanbtn' target='_blank'><span>Previous RAB<span></a></div></div>";
			echo "<div align='center'><div align='right' style='width:875px;'><a href='ViewSheet.php?sheetid=".$sheetid."' class='spanbtn' target='_blank'><span>SOQ<span></a> </div></div>";
			eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

            ?>
            <?php echo $table2; ?>
<input type="hidden" name="txt_mbno_id" value="<?php echo $mbno_id."*".$mbookno."*"."G"."*".$staffid."*".$sheetid; ?>" id="txt_mbno_id" />
<table width="875" border="0" cellpadding="3" cellspacing="3" align="center" bgcolor="#FFFFFF" class="label">

<?php echo $table1; ?>
<!--<tr height="" bgcolor="" class="label">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center"></td>
					<td width="230" align="left" colspan="" class="">Sub Abstract</td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>-->
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

$mbook_compo_query 	= 	mysqli_query($dbConn,$mbook_compo_sql);
if($mbook_compo_query == true)
{
	while($CompoList = mysqli_fetch_object($mbook_compo_query))
	{
		//$subdivname 	= 	getsubdivname($CompoList->subdivid);
		//$decimal		=	get_decimal_placed($CompoList->subdivid,$sheetid);
		$ItemData		=	getItemDetails($sheetid,$CompoList->subdivid);
		$ExplodeData	=	explode("##@**@##",$ItemData);
		$subdivname		=	$ExplodeData[0];
		$ItemUnit		=	$ExplodeData[2];
		$decimal		=	$ExplodeData[3];
		$fromdate 		=	$CompoList->fromdate;
		$todate 		=	$CompoList->todate;
		$zone_id		=	$CompoList->zone_id;
		$createDate 	= 	new DateTime($todate);
				$description1 = getscheduledescription_new($CompoList->subdivid);
				$snotes = $description1;
				$degcelsius = "&#8451";
				$description = str_replace("DEGCEL","$degcelsius",$snotes);
		$zonename = getzonename($sheetid,$zone_id);
		if($zonename != ""){ $zonename = "( ".$zonename." )"; }
		$todate 		= 	$createDate->format('Y-m-d');
		//echo $todate."<br/>";
		
		if(($pre_subdivid != "") && ($pre_staffid != ""))
		{
			if($pre_subdivid != $CompoList->subdivid)
			{
				
				$temp = 1;
			}
			/*if($pre_subdivid == $CompoList->subdivid)
			{
				if($pre_staffid != $CompoList->staffid)
				{
					$temp = 1;
				}
			}*/
			if($currentline>35)
			{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1,$gen_version); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
<?php	
				$currentline = $start_line + 8; $page++;
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
			}
			if($temp == 1)
			{
?>
				<tr height="" bgcolor="" class="labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right" nowrap="nowrap" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>	
<?php
				$OutPutStr1 .=  $pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum."@";
				$QtySum = 0; $temp = 0; $currentline++;
			}
		}
		if($currentline>35)
		{
?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "C/o to page ".($page+1)." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
				<?php echo check_line($title,$table,$page,$mbookno,$NextMBList[$NextMbIncr],$table1,$gen_version); ?>
				<tr height="" bgcolor="" class="labelheadblue">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">&nbsp;</td>
					<td width="230" align="right" colspan="4" class=""><?php echo "B/f from page ".$page." /General MB No ".$mbookno; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
<?php	
				$currentline = $start_line + 8; $page++;
				/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
				if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$mbookno][1] = $page-1; $UsedMBArr[$mbookno][2] = 0; $mbookno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
		}
		if($pre_subdivid != $CompoList->subdivid)
		{
			$WrapReturn1 = getWordWrapCount($description,80);
			$description = $WrapReturn1[0];
			$wrap_cnt1 = $WrapReturn1[1];
			$currentline = $currentline + $wrap_cnt1;
			?>
				<tr height="" bgcolor="" class="">
					<td width="81" 	align="center">&nbsp;</td>
					<td width="48" 	align="center"><?php echo $subdivname; ?></td>
					<td width="230" align="left" colspan="5" class=""><?php echo $description; ?></td>
					<td width="65" 	align="right">&nbsp;</td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>
			<?php
		}
		/*if($CompoList->mbno == 1000)
		{
			$zone = "(Zone-II)";
		}
		if($CompoList->mbno == 1001)
		{
			$zone = "(SWB)";
		}
		if($CompoList->mbno == 1003)
		{
			$zone = "(Zone-V)";
		}
		if($CompoList->mbno == 1004)
		{
			$zone = "(Zone-III)";
		}
		if($CompoList->mbno == 1021)
		{
			$zone = "(Zone-II)";
		}
		if($CompoList->mbno == 1022)
		{
			$zone = "(SWB)";
		}*/
?>
		<?php if($CompoList->accounts_remarks != ""){ $fcolor = "color:#F00000"; $acc_remarks_count++; } else{ $fcolor = ""; }?>	
				<tr height="" bgcolor="" style=" <?php echo $fcolor; ?>">
					<td width="81" 	align="center"></td>
					<td width="48" 	align="center">
					<?php 
					$accounts_str =  $CompoList->mbgenerateid."@#*#@".$subdivname."@#*#@".$zonename."@#*#@".$CompoList->mbno."@#*#@".$CompoList->mbpage."@#*#@".$CompoList->mbtotal."@#*#@".$decimal."@#*#@".$ItemUnit."@#*#@".$CompoList->accounts_remarks;
					?>
					<input type="checkbox" name="check" id="ch_item" value="<?php echo $accounts_str; ?>" />
					</td>
					<td width="390" align="center"><?php echo "B/f ".$zonename." from page no ".$CompoList->mbpage." Mbook No.".$CompoList->mbno; ?></td>
					<td width="35" 	align="center">&nbsp;<?php //echo $currentline; ?></td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="center">&nbsp;</td>
					<td width="65" 	align="right"><?php echo number_format($CompoList->mbtotal, $decimal, '.', ''); ?></td>
					<td width="32" 	align="center">&nbsp;</td>
				</tr>	
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
	}
				$OutPutStr2 	=  	$pre_divid."*".$pre_subdivid."*".$pre_fromdate."*".$pre_todate."*".$page."*".$mbookno."*".$QtySum;
				$OutPutStr		=	$OutPutStr1.$OutPutStr2;
?>
				<tr height="" bgcolor="" class="labelbold">
					<td width="81" 	align="center"><?php //echo $Line; ?></td>
					<td width="48" 	align="center">&nbsp;</td>
					<td width="390" align="right">Total&nbsp;</td>
					<td width="230" align="right" colspan="4" class="cobffont"><?php echo getabstractpage($sheetid,$pre_subdivid); ?></td>
					<td width="65" 	align="right"><?php echo number_format($QtySum, $decimal, '.', ''); ?></td>
					<td width="32" 	align="left"><?php echo $pre_ItemUnit; ?></td>
				</tr>
<?php
	$currentline++;
	$lineTemp = 35-$currentline;
?>
				<tr style="border-style:none">
					<td colspan="9" style="border-style:none" align="center">
					<?php 
						for($x2=4; $x2<12; $x2++)
						{
							//echo "<br/>";
						}
					?>
					<?php echo "Page ".$page; ?>
					</td>
				</tr>
<?php 

}
?>
</table>

<?php 
//$staffid_acc 		= $_SESSION['sid_acc'];
//$staff_level_str 	= getstafflevel($staffid_acc);
//$exp_staff_level_str = explode("@#*#@",$staff_level_str);
//$staff_roleid 		= $exp_staff_level_str[0];
//$staff_levelid 		= $exp_staff_level_str[1];
$AccVerification = AccVerificationCheck($sheetid,$rbn,$mbookno,'composite',$staff_levelid,'SA');
$AlStatusRes 		= AccountsLevelStatus($sheetid,$rbn,$mbookno,0,'G','composite');//($sheetid,$rbn);
$AcLevel 	= $AlStatusRes[0];
$AcStatus 	= $AlStatusRes[1];
$EndLevel 	= $AlStatusRes[2];
$SABCheck 	= $AlStatusRes[3];
//print_r($AlStatusRes);exit;
?>

<input type="hidden" name="hid_result" id="hid_result" value="<?php echo $OutPutStr; ?>" />
<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"/>
<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="<?php echo 0;//$zone_id; ?>"/>
<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $rbn; ?>"/>
<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $mbookno; ?>"/>
<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="submit" class="backbutton" name="Back" value=" Back " />
			</div>
<?php 
	$TranRes = AccountsLevelTransaction($sheetid,$rbn,$_SESSION['levelid']);
	$FWRoleName = GetRoleName($TranRes['Next'],$_SESSION['staff_section']);
	$BWRoleName = GetRoleName($TranRes['Prev'],$_SESSION['staff_section']); 
	//print_r($TranRes);exit;
	//echo $AccVerification." = ".$AcLevel." = ".$_SESSION['levelid']." = ".$AcStatus;exit;
	if(($AccVerification == 0)&&($AcLevel == $_SESSION['levelid']) && ($AcStatus != 'A')){// && ($EndLevel != $AcLevel)){ 
	
		if(($TranRes['Check'] == 1)&&($TranRes['Curr'] == $_SESSION['levelid'])&&($SABCheck == 0)){
?>

		<input type="hidden" name="txt_fw_level" id="txt_fw_level" value="<?php echo $TranRes['Next']; ?>" />
		<input type="hidden" name="txt_bw_level" id="txt_bw_level" value="<?php echo $TranRes['Prev']; ?>" />
		<input type="hidden" name="txt_min_level" id="txt_min_level" value="<?php echo $TranRes['Min']; ?>" />
		<input type="hidden" name="txt_max_level" id="txt_max_level" value="<?php echo $TranRes['Max']; ?>" />

		<?php if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] != $_SESSION['levelid'])){ ?>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
		<?php }else if(($TranRes['Max'] == $_SESSION['levelid'])&&($TranRes['Min'] != $_SESSION['levelid'])){ ?>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Sub-Abstract " /></div>-->
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
		<?php }else if(($_SESSION['levelid'] > $TranRes['Min'])&&($_SESSION['levelid'] < $TranRes['Max'])){ ?>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
		<?php }else if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] == $_SESSION['levelid'])){ ?>
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Sub-Abstract " /></div>-->
			<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
<?php 		  }else{
				// Nothing will be displayed here. So it will be Empty
			  }
		}
	} 
	if(($AccVerification == 0)&&($SABCheck == 0)&&($_SESSION['levelid'] >= $DecMinHighLevelRet)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
		 	<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>
		
<?php }
	if(($AccVerification == 0)&&($SABCheck == 0)&&($_SESSION['levelid'] >= $DecMinHighLevel)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
		 	<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
			<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Sub-Abstract " /></div>
		
<?php } ?>
		</div>

 
<?php
/*$DeleteSql		=	"DELETE FROM mbookgenerate WHERE sheetid = '$sheetid' AND flag = 1";
$DeleteQuery	=	mysqli_query($dbConn,$DeleteSql);
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
	$insertMbgenerate_sql 	= 	"insert into mbookgenerate (mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, pay_percent, flag, rbn, active, userid) 
													values (NOW(), '$staffid', '$sheetid', '$divid', '$subdivid', '$fromdate', '$todate', '$mbookno', '$mbookpage', '$ItemQty', '0', '1', '$rbn', '1', '$userid')";
	$insertMbgenerate_query	=	mysqli_query($dbConn,$insertMbgenerate_sql);
}*/
?>
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
						<td>Zone Name</td>
						<td><input type="text" name="txt_zone_name_acc" id="txt_zone_name_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>B/F - MBook No.</td>
						<td><input type="text" name="txt_mbook_no_acc" id="txt_mbook_no_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>B/F - MBook Page</td>
						<td><input type="text" name="txt_mbook_page_acc" id="txt_mbook_page_acc" class="textbox_modal label" readonly="" /></td>
					</tr>
					<tr>
						<td>Contents of Area</td>
						<td>
						<input type="text" name="txt_contents_area_acc" id="txt_contents_area_acc" class="textbox_modal label" readonly="" style="width:460px" />
						<!--<input type="text" name="txt_item_unit_acc" id="txt_item_unit_acc" class="textbox_modal label" readonly="" style="text-align:left" />-->
						<input type="hidden" name="txt_mbgenerateid_acc" id="txt_mbgenerateid_acc" class="textbox_modal" readonly="" />
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
		//$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
		$accurl = "MeasurementBookPrint_staff_Accounts.php";
	}
    //header('Location: '.$accurl);
}
?>
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
			var mbgenerateid = document.getElementById("txt_mbgenerateid_acc").value;
			var remarks_acco = document.getElementById("txt_accounts_remarks").value;
			var mbookno = document.getElementById("txt_mbook_no_acc").value;
			var sheetid = document.getElementById("txt_sheetid").value;
			var zone_id = document.getElementById("txt_zone_id").value;
			var rbn = document.getElementById("txt_rbn_no").value;
			var linkid = document.getElementById("txt_linkid").value;
			var staffid_acc = document.getElementById("txt_staffid_acc").value;
			var staff_levelid_acc = document.getElementById("txt_staff_levelid_acc").value;
			var mtype = "G";
			
			$.post("../Accounts_Comments_Update_SubAbstract.php", {mbgenerateid: mbgenerateid, remarks: remarks_acco, mbookno: mbookno, sheetid: sheetid, zone_id: zone_id, rbn: rbn, mtype: mtype, linkid: linkid, staffid: staffid_acc, levelid: staff_levelid_acc }, function (data) {
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
					var mbgenerateid 	= split_val[0];
					var item_no 		= split_val[1];
					var zonename 		= split_val[2];
					var mbno 			= Number(split_val[3]);
					var mbpage 			= Number(split_val[4]);
					var mbtotal 		= Number(split_val[5]);
					var decimal 		= Number(split_val[6]);
					var item_unit 		= split_val[7];
					var remarks_acc 	= split_val[8];
					$('#txt_item_no_acc').val(item_no);
					$('#txt_zone_name_acc').val(zonename);
					$('#txt_mbook_no_acc').val(mbno);
					$('#txt_mbook_page_acc').val(mbpage);
					$('#txt_contents_area_acc').val(mbtotal.toFixed(decimal)+" "+item_unit);
					$('#txt_mbgenerateid_acc').val(mbgenerateid);
					$('#txt_accounts_remarks').val(remarks_acc);
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