<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$staffid_acc 	= $_SESSION['sid_acc'];
$staff_levelid  = $_SESSION['levelid'];
if($_SESSION['sid_acc'] != "")
{
	$minmax_level_str 		= getstaff_minmax_level();
	$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	$min_levelid 			= $exp_minmax_level_str[0];
	$max_levelid 			= $exp_minmax_level_str[1];
}

function get_mbook_page_rbn($sheetid, $zone_id, $subdivid, $month, $year)
{
	global $dbConn;
	$select_mbook_data_query = "select mbno, mbpage, rbn from mbookgenerate_staff where sheetid = '$sheetid' and zone_id = '$zone_id' 
								and subdivid = '$subdivid' and YEAR(fromdate)<='$year' and MONTH(fromdate)<='$month' and 
								YEAR(todate)>='$year' and MONTH(todate)>='$month' ORDER BY fromdate LIMIT 1";
	$select_mbook_data_sql = mysqli_query($dbConn,$select_mbook_data_query);
	if($select_mbook_data_sql == true)
	{
		if(mysqli_num_rows($select_mbook_data_sql)>0)
		{
			$MBList = mysqli_fetch_object($select_mbook_data_sql);
			$mbookno = $MBList->mbno;
			$mbpage = $MBList->mbpage;
			$rbn = $MBList->rbn;
			$DataStr = $mbookno."*".$mbpage."*".$rbn;
		}
		else
		{
			$DataStr = "";
		}
	}
	else
	{
		$DataStr = "";
	}
	return $DataStr;
}



if($_POST["send_to_civil"] == " Return to EIC ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
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
	// echo $acc_remarks_count;exit;
	
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level_status = 'F', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Civil Section";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "General MBook - ".$sc_mbook_no." rejected to Civil in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
	 UpdateCivilViewlevel($sc_sheetid, $sc_rbnno);
	// exit;
	 /*$log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'staff', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysqli_query($dbConn,$linsert_log_query);*/
	 
}

if($_POST["accept"] == " Accept MBook ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 
	 /*$staff_level_str 		= getstafflevel($staffid_acc);
	 $exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	 $staff_roleid 			= $exp_staff_level_str[0];
	 $staff_levelid 		= $exp_staff_level_str[1];
	 
	 $minmax_level_str 		= getstaff_minmax_level();
	 $exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	 $min_levelid 			= $exp_minmax_level_str[0];
	 $max_levelid 			= $exp_minmax_level_str[1];*/
	 
	 /*if($staff_levelid == $min_levelid)
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
	 $view 				= $_POST['txt_view'];
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', acc_staffid = '$staffid_acc'
	 where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	//echo $update_query;exit;
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Verified & Accepted in Final Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Cement Consumption (Escalation) MBook - ".$sc_mbook_no." verified and final level accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
	 
	 /*$log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'G', genlevel = 'staff', status = 'AC', staffid = '$staffid_acc',
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
	 //AC_status= CASE WHEN (AC_status = 'R' AND levelid > '$end_level') THEN AC_status ELSE 'A' END ,
	 //AC_status= CASE WHEN (AC_status = 'R') THEN '' ELSE 'A' END ,
	 //AC_status= CASE WHEN (AC_status = 'R') THEN '' ELSE 'A' END , 
	 
	 ///// NEED TO CHECK SECOND LINE OF THIS QUERY ( including OR levelid = '$end_level' )
	  
	 $update_query 	= "update acc_log set comment ='$acc_comment_log', staffid = '$staffid_acc', 
	 				  levelid= CASE WHEN (AC_status = 'R') THEN '$fw_level' ELSE '".$_SESSION['levelid']."' END , 
					  AC_status= CASE WHEN (AC_status = 'R') THEN '' ELSE 'A' END ,  
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $RejCnt = 0;
	 $select_reject_query 	= "select logid from acc_log where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and (AC_status = 'R' OR AC_status = '') and levelid = '".$_SESSION['levelid']."'";
	 $select_reject_sql 	= mysqli_query($dbConn,$select_reject_query);
	 if($select_reject_sql == true){
	 	$RejCnt = mysqli_num_rows($select_reject_sql);
	 }
	 //echo $select_reject_query;exit;
	 if($RejCnt == 0){
	 
	 	/*$update_query = "update acc_log set 
		AC_status = CASE WHEN (levelid = '".$_SESSION['levelid']."') THEN '' ELSE 'A' END,  
		levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'G' and genlevel = 'staff'";*/
		$update_query = "update acc_log set 
		AC_status = '',  
		levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 //echo $update_query; exit;
	 	$update_sql = mysqli_query($dbConn,$update_query);
	 	$update_level_query = "update al_as set status = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
		$update_level_sql = mysqli_query($dbConn,$update_level_query);
	 }
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Forwarded to Next Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Cement Consumption (Escalation) MBook - ".$sc_mbook_no." accepted in ".$RABTranFWRoleName." Level";
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'CC' and genlevel = 'cem_consum'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Previous Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Cement Consumption (Escalation) MBook - ".$sc_mbook_no." returned back to ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
}


if($_GET['sheetid'] != "")
{
	$sheetid 				= $_GET['sheetid'];
	$quarter 				= $_GET['quarter'];
	$select_rbn_query = "select distinct(mbookgenerate.rbn), escalation.esc_id, escalation.tca_fromdate, escalation.tca_todate, escalation.quarter  
						from mbookgenerate INNER JOIN escalation ON (escalation.rbn = mbookgenerate.rbn) 
						where mbookgenerate.sheetid = '$sheetid' and escalation.flag = 0 and escalation.quarter = '$quarter'";
						//echo $select_rbn_query;
	$select_rbn_sql = mysqli_query($dbConn,$select_rbn_query);
	if($select_rbn_sql == true)
	{
		if(mysqli_num_rows($select_rbn_sql)>0)
		{
			$RbnList 	= mysqli_fetch_object($select_rbn_sql);
			$esc_id 	= $RbnList->esc_id;
			$esc_rbn 	= $RbnList->rbn;
			$fromdate 	= $RbnList->tca_fromdate;
			$todate 	= $RbnList->tca_todate;
		}
	}
	$select_mbook_query = "select * from mymbook where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and mtype = 'CC' and genlevel = 'cem_consum' and mbookorder = 1";
	$select_mbook_sql = mysqli_query($dbConn,$select_mbook_query);
	//echo $select_mbook_query;
	if($select_mbook_sql == true)
	{
		if(mysqli_num_rows($select_mbook_sql)>0)
		{
			$MBList = mysqli_fetch_object($select_mbook_sql);
			$cc_mbookno = $MBList->mbno;
			$cc_startpage = $MBList->startpage;
			$cc_endpage = $MBList->endpage;
			//$page = $cc_startpage;
		}
	}
	
	$select_mbook_query = "select * from mymbook where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and mtype = 'CC' and genlevel = 'cem_consum' and mbookorder = 2";
	$select_mbook_sql = mysqli_query($dbConn,$select_mbook_query);
	//echo $select_mbook_query;
	if($select_mbook_sql == true)
	{
		if(mysqli_num_rows($select_mbook_sql)>0)
		{
			$MBList = mysqli_fetch_object($select_mbook_sql);
			$newmbookno = $MBList->mbno;
			$newmbookpage = $MBList->startpage;
			//$cc_endpage = $MBList->endpage;
			//$page = $cc_startpage;
		}
	}
	/*$select_period_query 	= "select distinct MAX(esc_from_date) as fromdate, MAX(esc_to_date) as todate from escalation_10ca_details where 
	sheetid = '$sheetid' and active = 1 and esc_item_type = 'CEM'";
	$select_period_sql 		= mysqli_query($dbConn,$select_period_query);
	if($select_period_sql == true)
	{
		if(mysqli_num_rows($select_period_sql)>0)
		{
			$PeriodList = mysqli_fetch_object($select_period_sql);
			$fromdate 	= $PeriodList->fromdate;
			$todate 	= $PeriodList->todate;
		}
	}*/
	//echo $select_period_query;
}

$MonthList = array();
if(($fromdate != "") && ($todate != ""))
{
	$time1   = strtotime($fromdate);
	$last1   = date('F', strtotime($todate));
	while ($month1 != $last1) 
	{
		$month1 = date('F', $time1);
		$total1 = date('t', $time1);
		array_push($MonthList,$month1);
		$time1 = strtotime('+1 month', $time1);
	}
}

$MonthList2 = array();
if(($fromdate != "") && ($todate != ""))
{
	$time2   = strtotime($fromdate);
	$last2   = date('M-Y', strtotime($todate));
	while ($month2 != $last2) 
	{
		$month2 = date('M-Y', $time2);
		$total2 = date('t', $time2);
		array_push($MonthList2,$month2);
		$time2 = strtotime('+1 month', $time2);
	}
}

//print_r($MonthList2);
//echo $todate;
//print_r($MonthList);

$escal_measure_query = 	"SELECT mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
							mbookheader.subdivid, mbookheader.subdiv_name, mbookheader.zone_id, 
							mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.subdiv_name, mbookdetail.descwork, mbookdetail.measurement_no,
							mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_d, mbookdetail.measurement_contentarea, 
							mbookdetail.remarks, mbookdetail.zone_id,
							schdule.sno, schdule.tc_unit, schdule.total_quantity, schdule.deviate_qty_percent, schdule.item_flag,
							schdule.measure_type, schdule.subdiv_id, schdule.per, schdule.decimal_placed, schdule.description, schdule.shortnotes 
							FROM mbookheader
							INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
							INNER JOIN schdule ON (mbookheader.subdivid = schdule.subdiv_id)
							WHERE schdule.measure_type != 's' AND schdule.measure_type != 'st' AND mbookheader.sheetid = '$sheetid' 
							AND (mbookheader.date BETWEEN '$fromdate' AND '$todate') 
							AND schdule.sheet_id = '$sheetid' AND schdule.tc_unit != '0' AND (schdule.item_flag = 'NI' OR schdule.escalation_flag = 'Y') 
							AND NOT EXISTS 
							( SELECT esc_consumption_10ca.subdivid FROM esc_consumption_10ca WHERE esc_consumption_10ca.subdivid = mbookheader.subdivid 
							AND esc_consumption_10ca.mdate<'$fromdate' AND esc_consumption_10ca.sheetid = '$sheetid' AND esc_consumption_10ca.dev_flag = 'Y'
							AND esc_consumption_10ca.esc_item_type = 'CEM')
							ORDER BY mbookheader.subdivid ASC, mbookheader.date ASC, mbookheader.zone_id ASC";
//echo $escal_measure_query;							
$escal_measure_sql = mysqli_query($dbConn,$escal_measure_query);

function GetUsedQty($subdivid,$sheetid,$fromdate,$decimal_placed)
{
	global $dbConn;
	$usedQty = 0;
	$select_qty_query =  "select mbookheader.mbheaderid, DATE(mbookheader.date) as mdate, mbookheader.sheetid, 
						 mbookheader.subdivid, mbookdetail.mbheaderid, mbookdetail.subdivid, mbookdetail.measurement_contentarea
						 from mbookheader 
						 INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
						 where mbookheader.date<'$fromdate' and mbookheader.sheetid = '$sheetid' and mbookheader.subdivid = '$subdivid'
						 and mbookdetail.mbdetail_flag != 'd'";
	$select_qty_sql = mysqli_query($dbConn,$select_qty_query);
	if($select_qty_sql == true)
	{
		if(mysqli_num_rows($select_qty_sql)>0)
		{
			while($QtyList = mysqli_fetch_object($select_qty_sql))
			{
				$Qty = $QtyList->measurement_contentarea;
				$usedQty = $usedQty+$Qty;
			}
		}
	}
	$usedQty = round($usedQty,$decimal_placed);
	return $usedQty;
}

$select_sheet_query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
$select_sheet_sql 			= 	mysqli_query($dbConn,$select_sheet_query);
if ($select_sheet_sql == true) 
{
    $List 					= 	mysqli_fetch_object($select_sheet_sql);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor; 
	$ccno 					= 	$List->computer_code_no;    
	$agree_no 				= 	$List->agree_no; 
	$overall_rebate_perc 	= 	$List->rebate_percent; 
	$runn_acc_bill_no 		= 	$rbn;
	$work_order_no 			= 	$List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length1 				= 	strlen($work_name);
 	$start_line1 			= 	ceil($length1/70); 
	$length2 				= 	strlen($agree_no);
	$start_line2 			= 	ceil($length2/27);  
	$WrapReturn1 = getWordWrapCount($List->work_name,65);
	$work_name = $WrapReturn1[0];
	$wrap_cnt = $WrapReturn1[1];
	$LineIncr 				= 	$start_line1 + $wrap_cnt + 2 + 3;  
}
$line = 0;
$line = $line+$LineIncr;
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
function GetEscalationMBookPage($sheetid,$esc_id,$esc_rbn,$mon,$quarter)
{
	global $dbConn;
	$OutPut = "";
	$select_esc_mbook_query = "select esc_mbook, esc_page from escalation_10ca_details where sheetid = '$sheetid' and esc_rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter' and esc_month = '$mon' and esc_item_type = 'CEM'";
	$select_esc_mbook_sql = mysqli_query($dbConn,$select_esc_mbook_query);
	if($select_esc_mbook_sql == true)
	{
		if(mysqli_num_rows($select_esc_mbook_sql)>0)
		{
			$MBlist = mysqli_fetch_object($select_esc_mbook_sql);
			$esc_mb = $MBlist->esc_mbook;
			$esc_pg = $MBlist->esc_page;
			$OutPut = "C/o to MB-".$esc_mb."/Pg-".$esc_pg;
		}
	}
	return $OutPut;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Abstrack MBook</title>
    <link rel="stylesheet" href="script/font.css" />
</head>
	<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
	<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
	<link rel="stylesheet" href="css/button_style.css"></link>
	<link rel="stylesheet" href="js/jquery-ui.css">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css">
	<link rel="stylesheet" href="Font style/font.css" />
	<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
	<script type='text/javascript' src='js/basic_model_jquery.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
	<link rel="stylesheet" href="css/font-awesome.css" />
	<!--<script type='text/javascript' src='js/basic.js'></script>-->
	<script src="dist/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="dist/sweetalert.css">
<script type="text/javascript">
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
	function goBack()
	{
		//url = "Esc_Consump_10ca_Cement_Print.php";
		url = "MeasurementBookPrint_staff_Accounts.php";
		window.location.replace(url);
	}
</script>
<style>
.pagetitle
{
	text-shadow:
    -1px -1px 0 #7F7F7F,
    1px -1px 0 #7F7F7F,
    -1px 1px 0 #7F7F7F,
    1px 1px 0 #7F7F7F; 
}
.table1
{
	color:#BF0602;
	/*color:#921601;*/
	border: 0px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
	padding:3px;
}
.fontcolor1
{
	color:#FFFFFF;
}

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
.table2
{
	color:#071A98;
	border:0px solid #cacaca;
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.bottomsection
{
 	position: absolute;
    bottom: 0;
	width:100%;
	line-height:38px;
}
.buttonsection
{
	display: inline-block;
	line-height:38px;
}
.buttonstyle
{
	background-color:#0080FF;
	width:80px;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0080FF;
	-webkit-box-shadow: 0px 1px 0px 0px #0080FF;
	box-shadow: 0px 1px 0px 0px #0080FF;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0080FF));
	background:-moz-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0080FF 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0080FF 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0080FF',GradientType=0);
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
.popuptextbox
{
	border:none;
	font-family:Verdana;
	font-size:12px;
	font-weight:bold;
	color:#DE0117;
	text-align:center;
	pointer-events: none;
}
.dynamictextbox
{
	border:1px solid #ffffff;
	height:21px;
	color:#DE0117;
	font-weight:bold;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	/*outline: none;*/
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;
    /*border-color: #9ecaed;
    box-shadow: 0 0 10px #9ecaed;*/
}
.dynamictextbox2
{
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;	
}
.dynamicrowcell
{
	padding-bottom:0px;
	padding-top:0px; 
	padding-left:0px; 
	padding-right:0px;
	text-align:right;
	font:Verdana, Arial, Helvetica, sans-serif;
}
.hide
{
	display:none;
}
.labelprint
{
	font-weight:normal;
	color:#000000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
.hidtextbox
{
	border:none;
	width:98%;
	text-align:right;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
	font-weight:bold;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
		<div align="center" class="container_12">
            <!--==============================Content=================================-->
						<?php
						$page = $cc_startpage;
						$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
									<tr style="border:none;">
										<td align="center" style="border:none;">
											&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
										</td>
									</tr>
								 </table>';
						echo $title;
						$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
						$table = $table . "<tr>";
						$table = $table . "<td width='17%' class=''>Name of work</td>";
						$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
						$table = $table . "<td width='18%' class=''>Name of the contractor</td>";
						$table = $table . "<td width='22%' class='' colspan='3'>" . $name_contractor . "</td>";
						$table = $table . "</tr>";
						$table = $table . "<tr>";
						$table = $table . "<td class=''>Technical Sanction No.</td>";
						$table = $table . "<td class=''>" . $tech_sanction . "</td>";
						$table = $table . "<td class=''>Agreement No.</td>";
						$table = $table . "<td class='' colspan='3'>" . $agree_no . "</td>";
						$table = $table . "</tr>";
						$table = $table . "<tr>";
						$table = $table . "<td class=''>Work order No.</td>";
						$table = $table . "<td class=''>" . $work_order_no . "</td>";
						$table = $table . "<td class='' colspan='2'>CC No. </td>";
						$table = $table . "<td class='' colspan='2'>" . $ccno . "</td>";
						$table = $table . "</tr>";
						$table = $table . "</table>";
						
						$head = '<tr class="labelprint" style="height:35px;">';
						$head .= '<td align="center" valign="middle">Sl.No.</td>';
						$head .= '<td align="center" valign="middle">Date</td>';
						$head .= '<td align="center" valign="middle">page</td>';
						$head .= '<td align="center" valign="middle">MBook <br/>No</td>';
						$head .= '<td align="center" valign="middle">RAB <br/>No.</td>';
						$head .= '<td align="center" valign="middle">Zone</td>';
						$head .= '<td align="center" valign="middle">Item<br/> No.</td>';
						//$head .= '<td align="center" valign="middle">Description of Item No.</td>';
						$head .= '<td align="center" valign="middle">Qty </td>';
						$head .= '<td align="center" valign="middle">Unit </td>';
						$head .= '<td align="center" valign="middle">Theoritical <br/>Cement <br/>Consump. </td>';
						$head .= '<td align="center" valign="middle">Total <br/>Cement <br/>Consump. </td>';
						$head .= '<td align="center" valign="middle">&nbsp; </td>';
						$head .= '</tr>';
						?>
						<?php echo $table; ?>
						<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor='#FFFFFF' id='table1'>
						<?php echo $head; ?>
						<?php
						if($escal_measure_sql == true)
						{
							if(mysqli_num_rows($escal_measure_sql)>0)
							{
								$summary_arr = array(); $subdivid_arr = array(); $itemNo_arr = array(); $summary_ref_arr = array(); $summary_txtbox_arr = array();
								$usedQtyArr = array(); $WorkOrderQtyArr = array(); $DevItemArr = array();
								$slno = 1; $total_cem_consum = 0; $total_item_qty = 0; $total_item_qty_month = 0; $tbid = 0;
								$prev_mdate = ""; $prev_subdivid = ""; $prev_zone_id = ""; $prev_qty = ""; $prev_itemno = ""; $prev_month = "";
								$co_total_qty = 0;
								while($MList = mysqli_fetch_object($escal_measure_sql))
								{
								 if (in_array($MList->subdivid, $DevItemArr))
								 {
								 	//$xyxz = 0;
									$mdate 		 = "";//$mdate;
									//$prev_subdivid 		 = "";//$subdivid;
									$itemno 		 = "";//$itemno;
									$description 	 = "";//$description;
									$qty 			 = "";//$qty;
									$zone_id 		 = "";//$zone_id;
									$itemunit 		 = "";//$itemunit;
									$tc_unit 		 = "";//$tc_unit;
									$decimal_placed = "";//$decimal_placed;
									$month 		 = "";//$month;
									$month_num 	 = "";//$month_num;
									$year 		 	 = "";//$year;
									$total_item_qty = "";
									$item_cem_consum = "";
									
									
									$prev_mdate 		 = "";//$mdate;
									$prev_subdivid 		 = "";//$subdivid;
									$prev_itemno 		 = "";//$itemno;
									$prev_description 	 = "";//$description;
									$prev_qty 			 = "";//$qty;
									$prev_zone_id 		 = "";//$zone_id;
									$prev_itemunit 		 = "";//$itemunit;
									$prev_tc_unit 		 = "";//$tc_unit;
									$prev_decimal_placed = "";//$decimal_placed;
									$prev_month 		 = "";//$month;
									$prev_month_num 	 = "";//$month_num;
									$prev_year 		 	 = "";//$year;
									//$itemunit = "";
									//echo "hai"."<br/>";
								 }
								 else
								 {
								 	//print_r($DevItemArr);
									$mdate 			 = dt_display($MList->mdate);
									$month_ts		 = strtotime($MList->mdate);
									$month			 = date("F",$month_ts);
									$month_num		 = date("m",$month_ts);	
									$year			 = date("Y",$month_ts);	
									//echo $mdate."<br/>";					
									//$mbpage 		 = $MList->mbpage;
									//$mbno 		 = $MList->mbno;
									//$rbn 			 = $MList->rbn;
									$subdivid 		 = $MList->subdivid;
									$itemno 		 = $MList->subdiv_name;
									$description 	 = $MList->description;
									$shortnotes 	 = $MList->shortnotes;
									$qty 			 = $MList->measurement_contentarea;
									$itemunit 		 = $MList->remarks;
									$tc_unit 		 = $MList->tc_unit;
									$decimal_placed  = $MList->decimal_placed;
									$zone_id  		 = $MList->zone_id;
									
									if($page > 100)
									{ 
										$line = $start_line + 7;
										//$prevpage 	= 100;
										$page 		= $newmbookpage;
										$cc_mbookno 	= $newmbookno;
									}
									
									
									
									if($subdivid != $prev_subdivid)
									{
										//echo "Hi".$item_wise_curr_used_qty."<br/>";
										$usedQty = 0;
										$total_work_order_qty = 0;
										//$item_wise_curr_used_qty = 0;
										array_push($subdivid_arr,$subdivid);
										$itemNo_arr[$subdivid] = $itemno;
										$usedQty = GetUsedQty($subdivid,$sheetid,$fromdate,$decimal_placed);
										$usedQtyArr[$subdivid] = $usedQty;
										$deviate_qty_percent = $MList->deviate_qty_percent;
										//if(($deviate_qty_percent == "") || ($deviate_qty_percent == 0))
										//{
											//$deviate_qty_percent = 1;
										//}
										$work_order_qty = $MList->total_quantity;
										$total_work_order_qty = round(($work_order_qty+($work_order_qty*$deviate_qty_percent/100)),$decimal_placed);
										//echo "h".$work_order_qty."<br/>";
										$WorkOrderQtyArr[$subdivid] = $total_work_order_qty;
										//print_r($usedQtyArr);
										//echo $usedQty."<br/>";
									}
									
									if($line >= 25)
									{
										if($co_total_qty != 0){
										//echo "<tr class='labelbold'><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										if($page == 100){
											echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}else{
											echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										
										}
										echo "<tr style='border-style:none;' class='labelprint'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										
										//echo $title;
										if($page == 100){ $cc_mbookno = $newmbookno; }
										echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
										
										echo $table;
										echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
										echo $head;
										$line = 0;
										$line = $line+$LineIncr;
										if($co_total_qty != 0){
											echo "<tr class='labelbold'><td colspan='10' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										$page++;
										//$co_total_qty = 0;
									}
if($page > 100)
{ 
	$line = $start_line + 7;
	//$prevpage 	= 100;
	$page 		= $newmbookpage;
	$cc_mbookno 	= $newmbookno;
}
																		
									
									//$item_cem_consum = $tc_unit*$qty;
									if($shortnotes != "")
									{
										$description = $shortnotes;
									}
									if(($subdivid != $prev_subdivid) && ($prev_subdivid != ""))
									{
										$temp1 = 1;
									}
									else if(($mdate != $prev_mdate) && ($prev_mdate != ""))
									{
										$temp1 = 1;
									}
									else if(($zone_id != $prev_zone_id) && ($prev_zone_id != ""))
									{
										$temp1 = 1;
									}
									else
									{
										$temp1 = 0;
									}
									// This Row for dispaly every date wise total.
									if($temp1 == 1)
									{
										/// The Below round of is doubt which should be cleared with them.
										$total_item_qty 		= round($total_item_qty,$prev_decimal_placed);
										$item_cem_consum 		= round($prev_tc_unit*$total_item_qty,$prev_decimal_placed);
										$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
										$item_wise_curr_used_qty = $item_wise_curr_used_qty+$total_item_qty;
										//echo $total_item_qty." === ".$item_wise_curr_used_qty."<br/>";
										//if($subdivid != $prev_subdivid)
										//{
											//$item_wise_curr_used_qty = 0;
										//}
										
										$Datares = get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
										$ExpDatares = explode("*",$Datares);
										$mbookno 	= $ExpDatares[0];
										$mbpage 	= $ExpDatares[1];
										$rbn 		= $ExpDatares[2];
										
										$wrap_cnt2 = 0;
										//$WrapReturn2 = getWordWrapCount($description,120);
										//$shortnotes = $WrapReturn2[0];
										//$wrap_cnt2 = $WrapReturn2[1];
										//$line = $line+$wrap_cnt2;
										$shortnotes = $description;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											//echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											}
											echo "<tr style='border-style:none;' class='labelprint'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											//echo $title;
											if($page == 100){ $cc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='10' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
										
if($page > 100)
{ 
	$line = $start_line + 7;
	//$prevpage 	= 100;
	$page 		= $newmbookpage;
	$cc_mbookno 	= $newmbookno;
}
										
										//echo $res."<br/>";
										echo '<tr class="labelprint">';
										//echo '<td align="center" valign="middle">'.$slno.'</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
										//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">'.$itemunit.'</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										$co_total_qty = $co_total_qty + $item_cem_consum;
										// This is hidden box which is used to store date wise 'data' for each item.
										$date_wise_data1 = "";
										$date_wise_data1 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data1.'">';
										$total_item_qty = 0;
										$slno++;
									}
									
									if(($month != $prev_month)&&($prev_month != ""))
									{
										$end = 1;
									}
									else if(($subdivid != $prev_subdivid) && ($prev_subdivid != ""))
									{
										$end = 1;
									}
									else
									{
										$end = 0;
									}
									
									if($end == 1)
									//if(($month != $prev_month)&&($prev_month != ""))
									{
										//  This row is check Deviated Qty
										//echo $item_wise_curr_used_qty."<br/>";
										//print_r($usedQtyArr);
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//920;
										//echo "TWOQ = ".$TotalWorkOrderQty."<br/>";
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round($prev_tc_unit*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
											echo '<tr class="labelprint">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="5">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											//echo '<td align="right" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data1 = "";
										$dev_date_wise_data1 = $prev_mdate."@*@".$page."@*@".$cc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data1.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
										// This Row for dispaly every month wise total in kg.
										
										echo '<tr class="labelbold">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="left" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;kg&nbsp;</td>';
										echo '</tr>';
										$line++;
										// This Row for display Qty in Metric Tone for every Month
										$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
										echo '<tr class="labelbold">';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="left" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										//echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
										echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;mt&nbsp;</td>';
										echo '</tr>';
										
										$summary_arr[$prev_subdivid][$prev_month] = $total_item_qty_month_mt;
										$summary_ref_arr[$prev_subdivid][$prev_month] = "B/f MB-".$cc_mbookno."/Pg-".$page;
										$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
										$tbid++;
										
										$co_total_qty = 0;
										$month_wise_data1 = "";
										$month_wise_data1 = $prev_mdate."@*@".$cc_mbookno."@*@".$page."@*@".$total_item_qty_month_mt;
										echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data1.'">';
										$line++;
										$total_item_qty_month = 0;
										$slno = 1;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											//echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											if($page == 100){
												echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}else{
												echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											}
											echo "<tr style='border-style:none;' class='labelprint'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											
											//echo $title;
											if($page == 100){ $cc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='10' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
									}
									
if($page > 100)
{ 
	$line = $start_line + 7;
	//$prevpage 	= 100;
	$page 		= $newmbookpage;
	$cc_mbookno 	= $newmbookno;
}
									// Display Every Item Title
									if($subdivid != $prev_subdivid)
									{
										echo '<tr class="labelprint">';
										echo '<td align="center" valign="middle">&nbsp;'.$itemno.'&nbsp;</td>';
										echo '<td align="left" valign="middle" colspan="9">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="left" valign="middle" colspan="8">&nbsp;'.$shortnotes.'&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
									}
									// Display Every month Title
									if($month != $prev_month)
									{
										//print_r($DevItemArr);echo "<br/>";
										echo '<tr class="labelbold">';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;'.$month.' - '.$year.'&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										//echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="right" valign="middle">&nbsp;</td>';
										echo '<td align="center" valign="middle">&nbsp;</td>';
										echo '</tr>';
										$line++;
										if($line >= 25)
										{
											if($co_total_qty != 0){
											echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											echo "<tr style='border-style:none;' class='labelprint'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
											echo "</table>";
											echo "<p style='page-break-after:always;'>&nbsp;</p>";
											//echo $title;
											if($page == 100){ $cc_mbookno = $newmbookno; }
											echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
											
											
											echo $table;
											echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
											echo $head;
											$line = 0;
											$line = $line+$LineIncr;
											if($co_total_qty != 0){
												echo "<tr><td colspan='10' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
											}
											$page++;
											//$co_total_qty = 0;
										}
									}
									
if($page > 100)
{ 
	$line = $start_line + 7;
	//$prevpage 	= 100;
	$page 		= $newmbookpage;
	$cc_mbookno 	= $newmbookno;
}
									
									$total_item_qty = $total_item_qty + $qty;
									//$total_cem_consum = $total_cem_consum + $item_cem_consum;
									$prev_mdate 		 = $mdate;
									$prev_subdivid 		 = $subdivid;
									$prev_itemno 		 = $itemno;
									$prev_description 	 = $description;
									$prev_qty 			 = $qty;
									$prev_zone_id 		 = $zone_id;
									$prev_itemunit 		 = $itemunit;
									$prev_tc_unit 		 = $tc_unit;
									$prev_decimal_placed = $decimal_placed;
									$prev_month 		 = $month;
									$prev_month_num 	 = $month_num;
									$prev_year 		 	 = $year;
								 }
								}
								
								
								if (in_array($prev_subdivid, $DevItemArr))
								 {
								 	$xyxz = 0;
									$mdate 		 = "";//$mdate;
									//$prev_subdivid 		 = "";//$subdivid;
									$itemno 		 = "";//$itemno;
									$description 	 = "";//$description;
									$qty 			 = "";//$qty;
									$zone_id 		 = "";//$zone_id;
									$itemunit 		 = "";//$itemunit;
									$tc_unit 		 = "";//$tc_unit;
									$decimal_placed = "";//$decimal_placed;
									$month 		 = "";//$month;
									$month_num 	 = "";//$month_num;
									$year 		 	 = "";//$year;
									$total_item_qty = "";
									$item_cem_consum = "";
									
									
									$prev_mdate 		 = "";//$mdate;
									$prev_subdivid 		 = "";//$subdivid;
									$prev_itemno 		 = "";//$itemno;
									$prev_description 	 = "";//$description;
									$prev_qty 			 = "";//$qty;
									$prev_zone_id 		 = "";//$zone_id;
									$prev_itemunit 		 = "";//$itemunit;
									$prev_tc_unit 		 = "";//$tc_unit;
									$prev_decimal_placed = "";//$decimal_placed;
									$prev_month 		 = "";//$month;
									$prev_month_num 	 = "";//$month_num;
									$prev_year 		 	 = "";//$year;
									//$itemunit = "";
									//echo "hai"."<br/>";
								 }
								 else
								 {
								
								$item_wise_curr_used_qty = $item_wise_curr_used_qty+$total_item_qty;
								//echo "Hi".$item_wise_curr_used_qty."<br/>";
								// Last Row for dispaly Last row of date wise item qty.
								$total_item_qty = round($total_item_qty,$prev_decimal_placed);
								$item_cem_consum = round($prev_tc_unit*$total_item_qty,$prev_decimal_placed);
								//echo $total_item_qty_month."=".$item_cem_consum;
								$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
								$Datares 	= get_mbook_page_rbn($sheetid, $prev_zone_id, $prev_subdivid, $prev_month_num, $prev_year);
								$ExpDatares = explode("*",$Datares);
								$mbookno 	= $ExpDatares[0];
								$mbpage 	= $ExpDatares[1];
								$rbn 		= $ExpDatares[2];
								$wrap_cnt3 = 0;
								$WrapReturn3 = getWordWrapCount($prev_description,40);
								$shortnotes = $WrapReturn3[0];
								$wrap_cnt3 = $WrapReturn3[1];
								$line = $line+$wrap_cnt3;
								if($line >= 25)
									{
										if($co_total_qty != 0){
										//echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										if($page == 100){
											echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".$newmbookpage." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}else{
											echo "<tr><td colspan='10' align='right'>C/o to Mbook No / Page ".($page+1)." &nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										}
										echo "<tr style='border-style:none;'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
										echo "</table>";
										echo "<p style='page-break-after:always;'>&nbsp;</p>";
										
										//echo $title;
										if($page == 100){ $cc_mbookno = $newmbookno; }
										echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
										
										echo $table;
										echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
										echo $head;
										$line = 0;
										$line = $line+$LineIncr;
										if($co_total_qty != 0){
											echo "<tr><td colspan='10' align='right'>B/f from Mbook No / Page ".$page."&nbsp;&nbsp;&nbsp;</td><td align='right'> ".number_format($co_total_qty,$prev_decimal_placed,".",",")."&nbsp;</td><td></td></tr>";
										}
										$page++;
										//$co_total_qty = 0;
									}
								
if($page > 100)
{ 
	$line = $start_line + 7;
	//$prevpage 	= 100;
	$page 		= $newmbookpage;
	$cc_mbookno 	= $newmbookno;
}
								
								$total_item_qty = round($total_item_qty,$prev_decimal_placed);
								echo '<tr class="labeldisplay">';
								//echo '<td align="center" valign="middle">'.$slno.'</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_mdate.'</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbpage.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$mbookno.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.$rbn.'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;'.getzonename($sheetid,$prev_zone_id).'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$prev_itemno.'</td>';
								//echo '<td align="left" valign="middle">'.$shortnotes.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">'.$itemunit.'</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								$line++;
								$date_wise_data2 = "";
								$date_wise_data2 = $prev_mdate."@*@".$mbpage."@*@".$mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$total_item_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
								echo '<input type="hidden" name="txt_date_wise_data[]" id="txt_date_wise_data" value="'.$date_wise_data2.'">';
								$total_item_qty = 0;
								
								
								
										//  This row is check Deviated Qty
										//echo $item_wise_curr_used_qty."<br/>";
										//print_r($usedQtyArr);
										$TotalWorkOrderQty = $WorkOrderQtyArr[$prev_subdivid];//920;
										//echo "TWOQ = ".$TotalWorkOrderQty."<br/>";
										if($item_wise_curr_used_qty>$TotalWorkOrderQty)
										{
											array_push($DevItemArr,$prev_subdivid); 
											$Ded_dev_qty = $TotalWorkOrderQty-$item_wise_curr_used_qty;
											$item_wise_curr_used_qty = 0;
											$item_cem_consum 		= round($prev_tc_unit*$Ded_dev_qty,$prev_decimal_placed);
											$total_item_qty_month 	= $total_item_qty_month+$item_cem_consum;
										
											echo '<tr class="labeldisplay">';
											echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="right" valign="middle" colspan="5">&nbsp; Deviated Quantity&nbsp;&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											//echo '<td align="center" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;'.$prev_itemno.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.$Ded_dev_qty.'&nbsp;</td>';
											//echo '<td align="right" valign="middle">&nbsp;</td>';
											echo '<td align="center" valign="middle">'.$itemunit.'</td>';
											echo '<td align="right" valign="middle">&nbsp;'.number_format($prev_tc_unit,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="right" valign="middle">&nbsp;&nbsp;'.number_format($item_cem_consum,$prev_decimal_placed,".",",").'&nbsp;</td>';
											echo '<td align="center" valign="middle">&nbsp;&nbsp;</td>';
											echo '</tr>';
											
										$dev_date_wise_data2 = "";
										$dev_date_wise_data2 = $prev_mdate."@*@".$page."@*@".$cc_mbookno."@*@".$rbn."@*@".$prev_zone_id."@*@".$prev_subdivid."@*@".$prev_itemno."@*@".$Ded_dev_qty."@*@".$prev_tc_unit."@*@".$item_cem_consum;
										echo '<input type="hidden" name="txt_dev_date_wise_data[]" id="txt_dev_date_wise_data" value="'.$dev_date_wise_data2.'">';
											
										}
										if($subdivid != $prev_subdivid)
										{
											$item_wise_curr_used_qty = 0;
										}
								
								
								// Last Row for dispaly Last row of month wise total in kg.
								echo '<tr class="labelbold">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="left" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;</td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '</tr>';
								$line++;
								// Last Row for display Qty in Metric Tone
								$total_item_qty_month_mt = round(($total_item_qty_month/1000),$prev_decimal_placed);
								echo '<tr class="labelbold">';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								//echo '<td align="left" valign="middle">&nbsp;</td>';
								//echo '<td align="right" valign="middle">&nbsp;</td>';
								//echo '<td align="center" valign="middle">&nbsp;</td>';
								echo '<td align="center" valign="middle" colspan="4"><input type="text" name="txt_ref_'.$prev_subdivid.'" id="txt_ref_'.$prev_subdivid.'" class="hidtextbox"></td>';
								echo '<td align="right" valign="middle">&nbsp;'.number_format($total_item_qty_month_mt,$prev_decimal_placed,".",",").'&nbsp;</td>';
								echo '<td align="center" valign="middle">&nbsp;mt&nbsp;</td>';
								echo '</tr>';
								$summary_arr[$prev_subdivid][$prev_month] = $total_item_qty_month_mt;
								$summary_ref_arr[$prev_subdivid][$prev_month] = " B/f MB-".$cc_mbookno."/Pg-".$page;
								$summary_txtbox_arr[$prev_subdivid][$prev_month] = $tbid;
								
								$co_total_qty = 0;
								//echo "<tr style='border-style:none;'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
								
								$month_wise_data2 = "";
								$month_wise_data2 = $prev_mdate."@*@".$cc_mbookno."@*@".$page."@*@".$total_item_qty_month_mt;
								echo '<input type="hidden" name="txt_month_wise_data[]" id="txt_month_wise_data" value="'.$month_wise_data2.'">';
										
								$line++;
								$total_item_qty_month = 0;
							 }
							}
							$end_page = $page;
							//print_r($summary_arr);
						}
						
						echo "<tr style='border-style:none;' class='labelprint'><td colspan='12' align='center' style='border-style:none;'> page ".$page."</td></tr>";
						$page++;
						//print_r($DevItemArr);
						?>
								</table>
							<p style='page-break-after:always;'></p>
						<?php
						if(count($summary_arr)>0)
						{
							$mon1 = $MonthList[0];
							$mon2 = $MonthList[1];
							$mon3 = $MonthList[2];
							
							//echo $title;
							if($page == 100){ $cc_mbookno = $newmbookno; }
										echo '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="table1 labelprint">
												<tr style="border:none;">
													<td align="center" style="border:none;">
														&nbsp;&nbsp;&nbsp;MBook No. '.$cc_mbookno.'
													</td>
												</tr>
											 </table>';
							echo $table;
							echo "<br/>";
							echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='labelprint table1' bgcolor='#FFFFFF' id='table1'>";
							//echo $head;
							echo '<tr class="labelbold"><td colspan="7" align="center">Summary of Cement Consumption</td></tr>';
							echo '<tr class="labelbold">';
								echo '<td align="center">Item No</td>';
								//echo '<td align="center">Page</td>';
								//echo '<td align="center">MB</td>';
								echo '<td align="center" colspan="2">'.$MonthList[0].'</td>';
								echo '<td align="center" colspan="2">'.$MonthList[1].'</td>';
								echo '<td align="center" colspan="2">'.$MonthList[2].'</td>';
							echo '</tr>';
							$tbid_pg_str = "";
							//print_r($summary_txtbox_arr);
							$tot_qty1 = 0; $tot_qty2 = 0; $tot_qty3 = 0;
							for($s1=0; $s1<count($subdivid_arr); $s1++)
							{
								$summ_subdivid = $subdivid_arr[$s1];
								$qty1 = $summary_arr[$summ_subdivid][$mon1];
								$qty2 = $summary_arr[$summ_subdivid][$mon2];
								$qty3 = $summary_arr[$summ_subdivid][$mon3];
								$tot_qty1 = $tot_qty1+$qty1;
								$tot_qty2 = $tot_qty2+$qty2;
								$tot_qty3 = $tot_qty3+$qty3;
								?>
								<tr>
									<td align="center"><?php echo $itemNo_arr[$summ_subdivid]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon1]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon1]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon2]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon2]; ?></td>
									<td align="center"><?php echo $summary_ref_arr[$summ_subdivid][$mon3]; ?></td>
									<td align="center"><?php echo $summary_arr[$summ_subdivid][$mon3]; ?></td>
								</tr>
								<?php
								$tbid_pg_str .= $summ_subdivid."*".$cc_mbookno."*".$page."@";
							}
							$tot_qty1 = round($tot_qty1,3);
							$tot_qty2 = round($tot_qty2,3);
							$tot_qty3 = round($tot_qty3,3);
							?>
								<tr class="labelbold">
									<td align="center">Total Consumption.</td>
									<td align="center"><?php echo GetEscalationMBookPage($sheetid,$esc_id,$esc_rbn,$MonthList2[0],$quarter); ?></td>
									<td align="center"><?php echo $tot_qty1; ?></td>
									<td align="center"><?php echo GetEscalationMBookPage($sheetid,$esc_id,$esc_rbn,$MonthList2[1],$quarter); ?></td>
									<td align="center"><?php echo $tot_qty2; ?></td>
									<td align="center"><?php echo GetEscalationMBookPage($sheetid,$esc_id,$esc_rbn,$MonthList2[2],$quarter); ?></td>
									<td align="center"><?php echo $tot_qty3; ?></td>
								</tr>
								<input type="hidden" name="txt_consum_page" id="txt_consum_page" value="<?php echo $page; ?>">
								<input type="hidden" name="txt_consum_mbook" id="txt_consum_mbook" value="<?php echo $cc_mbookno; ?>">
							<?php
							echo "<tr style='border-style:none;'><td colspan='7' align='center' style='border-style:none;'> page ".$page."</td></tr>";
							echo "</table>";
						//echo $tbid_pg_str;
						}
						$staffid_acc 		= $_SESSION['sid_acc'];
						$staff_level_str 	= getstafflevel($staffid_acc);
						$exp_staff_level_str = explode("@#*#@",$staff_level_str);
						$staff_roleid 		= $exp_staff_level_str[0];
						$staff_levelid 		= $exp_staff_level_str[1];
						$AccVerification 	= AccVerificationCheck($sheetid,$esc_rbn,$cc_mbookno,'staff',$staff_levelid,'MB');
						$AlStatusRes 		= AccountsLevelStatus($sheetid,$esc_rbn,$cc_mbookno,0,'CC','cem_consum');//($sheetid,$rbn);
						$AcLevel 	= $AlStatusRes[0];
						$AcStatus 	= $AlStatusRes[1];
						$EndLevel 	= $AlStatusRes[2];
						?>
							<input type="hidden" name="txt_cc_esc_id" id="txt_cc_esc_id" value="<?php echo $cc_esc_id;?>">
							<input type="hidden" name="txt_cc_esc_rbn" id="txt_cc_esc_rbn" value="<?php echo $cc_esc_rbn;?>">
							<input type="hidden" name="txt_start_page" id="txt_start_page" value="<?php echo $start_page;?>">
							<input type="hidden" name="txt_end_page" id="txt_end_page" value="<?php echo $page;?>">
							<input type="hidden" name="txt_ccmbook" id="txt_ccmbook" value="<?php echo $cc_mbookno;?>">
							<input type="hidden" name="txt_cc_quarter" id="txt_cc_quarter" value="<?php echo $cc_quarter;?>">
							
							<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"/>
							<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="0"/>
							<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $esc_rbn; ?>"/>
							<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
							<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $cc_mbookno; ?>"/>
							<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
							<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
							<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>
							<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>"/>
							
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
									<div class="btn_inside_sect"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/> </div>
								<?php 
									$TranRes = AccountsLevelTransaction($sheetid,$esc_rbn,$_SESSION['levelid']);
									$FWRoleName = GetRoleName($TranRes['Next'],$_SESSION['staff_section']);
									$BWRoleName = GetRoleName($TranRes['Prev'],$_SESSION['staff_section']);
									if(($AccVerification == 0)&&($AcLevel == $_SESSION['levelid']) && ($AcStatus != 'A')){ // &&($EndLevel != $AcLevel)){ 
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
											<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
										<?php }else if(($TranRes['Max'] == $_SESSION['levelid'])&&($TranRes['Min'] != $_SESSION['levelid'])){ ?>
											<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>-->
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
										<?php }else if(($_SESSION['levelid'] > $TranRes['Min'])&&($_SESSION['levelid'] < $TranRes['Max'])){ ?>
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
										<?php }else if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] == $_SESSION['levelid'])){ ?>
											<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
								<?php 		  }else{
												// Nothing will be displayed here. So it will be Empty
											  }
										}
									} 
									if(($AccVerification == 0)&&($_SESSION['levelid'] >= $DecMinHighLevelRet)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>
											<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>-->
										
								<?php }
									if(($AccVerification == 0)&&($_SESSION['levelid'] >= $DecMinHighLevel)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
											<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
											<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept MBook " /></div>
										
								<?php } ?>
								
							</div>
						</div>
            <!--==============================footer=================================-->
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				//alert(success)
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal({
								  title: "",
								  text: msg,
								  type: "success",
								  confirmButtonText: "OK",
								  closeOnConfirm: false
								},
								function(){
								  window.location.href = "MeasurementBookPrint_staff_Accounts.php";
							});
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
	<script>
		var txtbox_str = "<?php echo $tbid_pg_str; ?>";
		if(txtbox_str != "")
		{
			var splitData = txtbox_str.split("@");
			for(var i=0; i<splitData.length; i++)
			{
		//alert(splitData[i])
				if(splitData[i] != "")
				{
					var resData = splitData[i].split("*");
					var id = resData[0];
					var mb = resData[1];
					var pg = resData[2];
					var len = document.getElementsByName('txt_ref_'+id).length;
					for(var j=0; j<len; j++)
					{
						var textbox = document.getElementsByName('txt_ref_'+id)[j];
						textbox.value = "C/o to MB - "+mb+" /Pg-"+pg;
					}
					
				}
			}
		}
	</script>
</html>
