<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "library/common.php";
include "sysdate.php";
checkUser();
include "spellnumber.php";
$msg = ''; $Line = 0;
function checkPartpayment($DpmArrMbidList,$Key)
{
	$InitKey = $Key;
	while($perc = current($DpmArrMbidList)) 
	{
		if ($perc == $InitKey) 
		{
			$res .= key($DpmArrMbidList)."*";
		}
		next($DpmArrMbidList);
	}
	return rtrim($res,"*");
}

function removeArray($res,$array)
{
	$explodeRes = explode("*",rtrim($res,"*"));
	for($i=0; $i<count($explodeRes);$i++)
	{
		$RemKey = $explodeRes[$i];
		unset($array[$RemKey]);
	}
	return $array;
}
function CheckPageBreak($tablehead,$abstmbno,$table,$page)
{
	$nextpage = $page+1;
	$Output .= "<tr>
					<td colspan='3' align='right' class='labelbold'>C/o Page No ".$nextpage."/ Abstract MB No ".$abstmbno."</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
				</tr>";
	$Output .=  "<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page ".$page."</td></tr>";
	$Output .= "</table>";
	$Output .= "<p  style='page-break-after:always;'></p>";
	$Output .= '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
				<tr style="border:none;"><td align="right" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;</td></tr>
				</table>';
	$Output .= $table;
	$Output .= "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>";
	$Output .= $tablehead;
	$Output .= "<tr>
					<td colspan='3' align='right' class='labelbold'>B/f from Page No ".$page."/ Abstract MB No ".$abstmbno."</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
					<td align='right' class='labelbold'>HEllo</td>
					<td></td>
				</tr>";
	echo $Output;
}

function UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page)
{
	global $dbConn;
	$update_pageno_sql = "update measurementbook_temp set abstmbookno = '$abstmbno', abstmbpage = '$page' where sheetid	= '$abstsheetid' AND subdivid = '$subdivid'";
	//$update_pageno_query = mysqli_query($dbConn,$update_pageno_sql);
}

function GetAllEscMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}
function GetAllRbnMonth($fromdate,$todate)
{
	$MonthList 	= array();
	$time   	= strtotime($fromdate);
	$last   	= date('M-Y', strtotime($todate));
	while ($month != $last) 
	{
		$month 	= date('M-Y', $time);
		$total 	= date('t', $time);
		array_push($MonthList,$month);
		$time 	= strtotime('+1 month', $time);
	}
	return $MonthList;
}

function CheckDeviatedQtyItem($sheetid,$subdivid,$used_qty)
{
	global $dbConn;
	$Over_all_Qty = 0; $temp = 0; $OutPut = "";
	$select_devQty_query = "select total_quantity, deviate_qty_percent, decimal_placed from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
	$select_devQty_sql = mysqli_query($dbConn,$select_devQty_query);
	if($select_devQty_sql == true)
	{
		if(mysqli_num_rows($select_devQty_sql)>0)
		{
			$List = mysqli_fetch_object($select_devQty_sql);
			$total_quantity 		= $List->total_quantity;
			$deviate_qty_percent 	= $List->deviate_qty_percent;
			$decimal_placed 		= $List->decimal_placed;
			$Over_all_Qty 			= $total_quantity + ($total_quantity*$deviate_qty_percent/100);
			$Over_all_Qty_with_Dev 	= round($Over_all_Qty,$decimal_placed);
			$used_quantity 			= $used_qty;
			if($used_quantity>$Over_all_Qty_with_Dev)
			{
				$used_deviat_qty = $used_quantity-$Over_all_Qty_with_Dev;
				$temp = 1;
			}
			else
			{
				$used_deviat_qty = 0;
				$temp = 0;
			}
			$OutPut = $Over_all_Qty_with_Dev."*".$used_deviat_qty."*".$temp;
		}
	}
	return $OutPut;
}

$staffid 			= 	$_SESSION['sid'];//
$userid 			= 	$_SESSION['userid'];//


//$abstsheetid    	= 	$_SESSION["escal_sheetid"]; //
//$abstmbno 			= 	$_SESSION["escal_mbook_no"];  
//$abstmbpage  		= 	$_SESSION["escal_mbook_pageno"];
//$fromdate       	= 	$_SESSION['escal_tcc_from_date']; //     
//$todate   			= 	$_SESSION['escal_tcc_to_date'];//    
//$abs_mbno_id 		= 	$_SESSION["abs_mbno_id"];
//$esc_abs_rbn 		= $_SESSION['esc_rbn'];//
//$quarter 			= $_SESSION['esc_quarter'];//
//$esc_id 			= $_SESSION['esc_id'];//

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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level_status = 'F', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Civil Section";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Escalation Abstract MBook - ".$sc_mbook_no." rejected to Civil in ".$RABTranFWRoleName." Level";
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', acc_staffid = '$staffid_acc'
	 where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	//echo $update_query;exit;
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Verified & Accepted in Final Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Escalation Abstract MBook - ".$sc_mbook_no." verified and final level accepted in ".$RABTranFWRoleName." Level";
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
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
		levelid = '$fw_level' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
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
		$RABTransActDetStr = "Escalation Abstract MBook - ".$sc_mbook_no." accepted in ".$RABTranFWRoleName." Level";
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'EA' and genlevel = 'esc_abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Previous Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Escalation Abstract MBook - ".$sc_mbook_no." returned back to ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
}




if($_GET['sheetid'] != "")
{
	$abstsheetid 			= $_GET['sheetid'];
	$quarter 				= $_GET['quarter'];
	$select_rbn_query = "select distinct(mbookgenerate.rbn), escalation.esc_id, escalation.tca_fromdate, 
						escalation.tca_todate, escalation.tcc_fromdate, escalation.tcc_todate, escalation.quarter,
						escalation.tcc_absmbook, escalation.tcc_absmbpage, escalation.tca_absmbook, escalation.tca_absmbpage
						from mbookgenerate INNER JOIN escalation ON (escalation.rbn = mbookgenerate.rbn) 
						where mbookgenerate.sheetid = '$abstsheetid' and escalation.flag = 0 and escalation.quarter = '$quarter'";
						//echo $select_rbn_query;
	$select_rbn_sql = mysqli_query($dbConn,$select_rbn_query);
	if($select_rbn_sql == true)
	{
		if(mysqli_num_rows($select_rbn_sql)>0)
		{
			$RbnList 			= mysqli_fetch_object($select_rbn_sql);
			$esc_id 			= $RbnList->esc_id;
			$esc_abs_rbn 		= $RbnList->rbn;
			$fromdate 			= $RbnList->tcc_fromdate;
			$todate 			= $RbnList->tcc_todate;
		}
	}
}

$select_escmbook_query = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$esc_abs_rbn' and esc_id = '$esc_id' and quarter = '$quarter' and staffid = '$staffid' and mtype = 'EA' and genlevel = 'esc_abstract'";
$select_escmbook_sql = mysqli_query($dbConn,$select_escmbook_query);
if($select_escmbook_sql == true)
{
	if(mysqli_num_rows($select_escmbook_sql)>0)
	{
		$MBList = mysqli_fetch_object($select_escmbook_sql);
		$abstmbno 		= $MBList->mbno;
		$abstmbpage 	= $MBList->startpage;
	}
}

//echo "VV".$fromdate;


$runn_acc_bill_no 	= 	$rbn;
$start_page = $abstmbpage;
//$abstsheetid = 2;
$query 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
$sqlquery 	= 	mysqli_query($dbConn,$query);
if ($sqlquery == true) 
{
    $List 					= 	mysqli_fetch_object($sqlquery);
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
	$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
}
$Line = $Line + $LineIncr; 


function CheckDeviatedQty($sheetid,$subdivid,$rbn)
{
	global $dbConn;
	$total_used_qty = 0;
	$total_wo_qty = 0;
	$select_used_qty_query = "select SUM(mbtotal) as total_qty from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and rbn < '$rbn'";
	$select_used_qty_sql = mysqli_query($dbConn,$select_used_qty_query);
	if($select_used_qty_sql == true)
	{
		if(mysqli_num_rows($select_used_qty_sql)>0)
		{
			$List = mysqli_fetch_object($select_used_qty_sql);
			
			$total_used_qty = $List->total_qty;
		}
	}
	
	$select_wo_qty_query = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
	$select_wo_qty_sql = mysqli_query($dbConn,$select_wo_qty_query);
	if($select_wo_qty_sql == true)
	{
		$SList = mysqli_fetch_object($select_wo_qty_sql);
		$decimal 				= $SList->decimal_placed;
		$wo_quantity 			= $SList->total_quantity;
		$deviate_qty_percent 	= $SList->deviate_qty_percent;
		$total_wo_qty  			= $wo_quantity + ($wo_quantity*$deviate_qty_percent/100);
		$total_wo_qty  = round($total_wo_qty,$decimal);
	}
	if($total_used_qty>$total_wo_qty)
	{
		$temp = 1;  // if deviated........
	}
	else
	{
		$temp = 0;
	}
	$res = $total_wo_qty."*".$temp;
	return $res;
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
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		//url = "EscalationAbstractPrintGenerate.php";
		url = "MeasurementBookPrint_staff_Accounts.php";
		window.location.replace(url);
	}
	function ValidatePercent(obj,section,idcount)
	{
		//alert(idcount);
		var value = obj.value;
		if(Number(value)>100)
		{
			swal("", "Entered % should be less than 100..!", "error"); 
			obj.value = "";
			document.getElementById("hid_slm_result"+idcount).value = "";
			totalAmountCalculation("slm");
			return false;
		}
		if(section == "dpm")
		{
			var paid_percent_dpm = document.getElementById("txt_partpay_percent_dpm"+idcount).value;
			var remain_percent_dpm = 100-Number(paid_percent_dpm);
			if(value > remain_percent_dpm)
			{
				swal("Entered Percentage should be less than: ", remain_percent_dpm+" %", "error");
				obj.value = "";
				document.getElementById("txt_amt_dpm_payable"+idcount).value = "";
				document.getElementById("hid_dpm_result"+idcount).value = "";
				totalAmountCalculation("dpm");
				return false;
			}
		}
	}
	function ValidateSlm()
	{
		var slmqty = document.getElementById("hid_slm_qty").value;
		var qty = 0;
		$('input[name="txt_partpay_qty_slm[]"]').each(function() {
			var currentqty = $(this).val();
			qty = (Number(qty)+Number(currentqty));
			if(qty>slmqty)
			{
				swal("", "Quantity Not Allowed..:)", "error"); 
				//$(this).val() = "";
				return false;
			}
		});
	}
	function setRowSpan() 
	{
		var i;
		var rowcount =  document.getElementById("table_group_count").value;
		for(i=0; i<rowcount; i++)
		{
			var row_span = document.getElementById("row_count"+i).value;
			document.getElementById("td_popupbutton"+i).rowSpan = row_span;
			var ht = document.getElementById("td_popupbutton"+i);
			 var checkbox_height = ht.offsetHeight;
			 //document.getElementById('ch_item'+i).offsetHeight = checkbox_height;
			 document.getElementById("ch_item"+i).style.height = checkbox_height+"px";
			 //document.getElementById("ch_item"+i).style.width = checkbox_height+"px";
			
		}
	}
	//var index = 1;
	function addRow()
	{
		var x = Number(document.getElementById("table4").rows.length);
		 	 index = x;
		//var arg = "X"+"*"+index;
		var rate = document.getElementById("txt_item_rate_slm0").value;
		var table=document.getElementById("table4");
        var row=table.insertRow(table.rows.length-2);
        	row.id = "rowid"+index;
			row.style.align = "center";
			
		var cell1=row.insertCell(0);
			cell1.setAttribute('class', "dynamicrowcell");
			cell1.style.padding = "0px 0px 0px 0px";
				
		var txt_box1 = document.createElement("input");
			txt_box1.name = "txt_partpay_qty_slm[]";
            txt_box1.id = "txt_partpay_qty_slm"+index;
			//txt_box1.value = row.id;
			txt_box1.style.width = 93+"px";
			txt_box1.style.border = "1px solid #2aade4";
			txt_box1.style.textAlign = "right";
			txt_box1.setAttribute('class', "dynamictextbox"); 
            cell1.appendChild(txt_box1);
			/*txt_box1.onblur= function () {
								ValidateSlm();
                        	  calculateAmount(this,index,"qty","slm")
                    		}*/
							txt_box1.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"qty","slm")
												ValidateSlm();
												};
											})(index); 
		var cell2=row.insertCell(1);
			cell2.setAttribute('class', "dynamicrowcell");	
			cell2.style.padding = "0px 0px 0px 0px";
		var txt_box2 = document.createElement("input");
			txt_box2.name = "txt_item_rate_slm";
            txt_box2.id = "txt_item_rate_slm"+index;
			txt_box2.value = Number(rate).toFixed(2);
			txt_box2.style.textAlign = "right";
			txt_box2.style.width = 80+"px";
			txt_box2.readOnly = true;
			txt_box2.setAttribute('class', "dynamictextbox"); 
            cell2.appendChild(txt_box2);
			/*txt_box2.onblur= function () {
                        	  calculateAmount(this,index,"rate","slm")
                    		}*/
							txt_box2.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"rate","slm")
												ValidateSlm();
												};
											})(index); 
		var cell3=row.insertCell(2);
			cell3.setAttribute('class', "dynamicrowcell");	
			cell3.style.padding = "0px 0px 0px 0px";
		var txt_box3 = document.createElement("input");
			txt_box3.name = "txt_partpay_percent_slm";
            txt_box3.id = "txt_partpay_percent_slm"+index;
			//txt_box3.value = txt_box3.id;
			txt_box3.style.width = 40+"px";
			txt_box3.style.textAlign = "right";
			txt_box3.style.border = "1px solid #2aade4";
			txt_box3.setAttribute('class', "dynamictextbox"); 
			/*txt_box3.onblur= function () {
                        	  calculateAmount(this,index,"percent","slm");
							  ValidatePercent(this)
                    		}*/
							txt_box3.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"percent","slm")
												ValidatePercent(this,"slm",ind);
												};
											})(index);
            cell3.appendChild(txt_box3);
							
		var cell4=row.insertCell(3);
			cell4.setAttribute('class', "dynamicrowcell");	
			cell4.style.padding = "0px 0px 0px 0px";
		var txt_box4 = document.createElement("input");
			txt_box4.name = "txt_partpay_amt_slm[]";
            txt_box4.id = "txt_partpay_amt_slm"+index;
			txt_box4.style.width = 130+"px";
			txt_box4.style.textAlign = "right";
			txt_box4.style.pointerEvents = "none";
			txt_box4.setAttribute('class', "dynamictextbox"); 
            cell4.appendChild(txt_box4);
		
		var cell5=row.insertCell(4);
			//cell5.style.width = 10+"px";
			cell5.style.textAlign = "center";
			cell5.style.padding = "0px 0px 0px 0px";
        var delbtn=document.createElement("input");
        	delbtn.type = "button";
        	delbtn.value = " X ";
        	delbtn.id = "btn_delete"+index;
			delbtn.name = "btn_delete";
			delbtn.setAttribute('class', "delbtnstyle");
			delbtn.style.width = 32+"px";
			delbtn.style.borderRadius = 0+"px";
        	delbtn.onclick = function () {
                        	  deleteRow(this);
                    		}
        	cell5.appendChild(delbtn);
			
			//var cell6=row.insertCell(5);
			//cell5.style.width = 10+"px";
			//cell6.style.textAlign = "center";
			//cell6.style.padding = "0px 0px 0px 0px";
			//cell6style.visibility = "hidden";	
			
	// BELOW FIELD IS HIDDEN BOX FIELD....SO APPEND IN ADD & DELETE BUTTON FIELD ITSELF..No seperate TD(cell) creation for this. check above ( index++ : line)
		var txt_box5 = document.createElement("input");
        	txt_box5.type = "hidden";
        	txt_box5.id = "hid_slm_result"+index;
			txt_box5.name = "hid_slm_result[]";
			txt_box5.setAttribute('class', "dynamictextbox");
			txt_box5.style.width = 70+"px";
			txt_box5.style.borderRadius = 0+"px";
			cell5.appendChild(txt_box5);
			index++;
	}
	function deleteRow(obj) 
	{
	   //var row = document.getElementById(id);
	   //row.parentNode.removeChild(row);
	   /*$('input[name = "btn_delete"]').click(function(){
		   $(this).closest('tr').remove()
		})*/
		var tr = $(obj).closest('tr');
		tr.remove();
	   totalAmountCalculation("slm");
	   return true;
	}
	function calculateAmount(obj,id,type,section)
	{
		var idcount = id;
		var currentvalue = obj.value;
		var itemid = document.getElementById("txt_item_id").value;
		var currentrbn = document.getElementById("txt_rab_no").value;
		if(section == "slm")
		{
			if(type == "qty")
			{
				var rate = document.getElementById("txt_item_rate_slm"+idcount).value;
				var percent = document.getElementById("txt_partpay_percent_slm"+idcount).value;
				var qty = currentvalue;
				//alert(percent)
			}
			if(type == "rate")
			{
				var qty = document.getElementById("txt_partpay_qty_slm"+idcount).value;
				var percent = document.getElementById("txt_partpay_percent_slm"+idcount).value;
				var rate = currentvalue;
			}
			if(type == "percent")
			{
				var rate = document.getElementById("txt_item_rate_slm"+idcount).value;
				var qty = document.getElementById("txt_partpay_qty_slm"+idcount).value;
				var percent = currentvalue;
			}
			qty = Number(qty);
			//alert(qty)
			rate = Number(rate);
			//alert(rate)
			percent = Number(percent);
			//alert(percent)
			if((qty != "") && (rate != "") && (percent != ""))
			{
				var amount = qty * rate * percent / 100;
				document.getElementById("txt_partpay_amt_slm"+idcount).value = Number(amount).toFixed(2);
				var result = percent + "*" + currentrbn + "*" + qty + "*" + itemid;
				document.getElementById("hid_slm_result"+idcount).value = result;
			}
			else
			{
				document.getElementById("txt_partpay_amt_slm"+idcount).value = "";
				document.getElementById("hid_slm_result"+idcount).value = "";
			}
		}
		if(section == "dpm")
		{
			var rate_dpm 	= document.getElementById("txt_item_rate_dpm"+idcount).value;
			var qty_dpm 	= document.getElementById("txt_partpay_qty_dpm"+idcount).value;
			var rbn_dpm 	= document.getElementById("txt_rbn_dpm"+idcount).value;
			var mbid_dpm 	= document.getElementById("hid_dpm_mbid"+idcount).value;
			var percent_dpm = currentvalue;
				qty_dpm 	= Number(qty_dpm);
				rate_dpm 	= Number(rate_dpm);
				percent_dpm = Number(percent_dpm);
			if((qty_dpm != "") && (rate_dpm != "") && (percent_dpm != ""))
			{
				var amount_dpm = qty_dpm * rate_dpm * percent_dpm / 100;
				document.getElementById("txt_amt_dpm_payable"+idcount).value = Number(amount_dpm).toFixed(2);
				var result = percent_dpm + "*" + currentrbn + "*" + qty_dpm + "*" + itemid + "*" + rbn_dpm + "*" + mbid_dpm;
				//alert(result)
				document.getElementById("hid_dpm_result"+idcount).value = result;
			}
			else
			{
				document.getElementById("txt_amt_dpm_payable"+idcount).value = "";
				document.getElementById("hid_dpm_result"+idcount).value = "";
			}
		}
		totalAmountCalculation(section);
		
		return true;
	}
	
	function totalAmountCalculation(section)
	{
		var amount = 0;
		if(section == "slm")
		{
			$('input[name="txt_partpay_amt_slm[]"]').each(function() {
				var amt = $(this).val();
				amount = (Number(amount)+Number(amt));
			});
			
			var DpmPayableAmount = document.getElementById("txt_partpay_total_payableamt_dpm").value;
			//if(DpmPayableAmount == ""){ DpmPayableAmount = 0; }
			var OverAllAmount = Number(amount)+Number(DpmPayableAmount);
			
			if(amount>0)
			{
				document.getElementById("txt_partpay_total_amt_slm").value = Number(amount).toFixed(2);
			}
			else
			{
				document.getElementById("txt_partpay_total_amt_slm").value = "";
			}
			document.getElementById("txt_overall_total").value = Number(OverAllAmount).toFixed(2);
		}
		if(section == "dpm")
		{
			$('input[name="txt_amt_dpm_payable[]"]').each(function() {
				var amt = $(this).val();
				amount = (Number(amount)+Number(amt));
			});
			
			var SlmTotalAmount = document.getElementById("txt_partpay_total_amt_slm").value;
			//if(SlmTotalAmount == ""){ SlmTotalAmount = 0; }
			var OverAllAmount = Number(amount)+Number(SlmTotalAmount);
			
			if(amount>0)
			{
				document.getElementById("txt_partpay_total_payableamt_dpm").value = Number(amount).toFixed(2);
			}
			else
			{
				document.getElementById("txt_partpay_total_payableamt_dpm").value = "";
			}
			document.getElementById("txt_overall_total").value = Number(OverAllAmount).toFixed(2);
		}
	}
	
	function getDPMdetaiils(sheetid,itemid,rate)
	{
		var xmlHttp;
		var data;
		var i, rbn, qty, percent, measurementbookid, searchflag, RemarkData ="", newrow = "",TotalPaidDpmAmount = 0,TotalPayableDpmAmount = 0; 
		var currentrbn = document.getElementById("txt_rab_no").value;
		var rate = Number(rate);	
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_dpm_details.php?sheetid="+sheetid+"&itemid="+itemid;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText;
				if(data != "")
				{
					var details = data.split("*");
					var index = 1;
					for(i=0; i<details.length; i+=6)
					{
						RemarkData = ""; 
						rbn 				= details[i];
						qty 				= details[i+1];
						percent 			= details[i+2];
						measurementbookid 	= details[i+3];
						searchflag 			= details[i+4];
						PayableDpmSlmPercent 		= details[i+5];
						if((PayableDpmSlmPercent != "") && (PayableDpmSlmPercent != "X"))
						{
							var PayableSlmDpmAmt = Number(qty)*Number(PayableDpmSlmPercent)*Number(rate)/100;
								TotalPayableDpmAmount = (Number(TotalPayableDpmAmount)+Number(PayableSlmDpmAmt));
							var result = PayableDpmSlmPercent + "*" + currentrbn + "*" + qty + "*" + itemid + "*" + rbn + "*" + measurementbookid;
						}
						else
						{
							var PayableSlmDpmAmt = 0;
							var result = "";
						}
						//alert(searchflag)
						
						
						if((searchflag != "") && (searchflag != "X"))
						{
							var searchflagdetails = searchflag.split("@");
							
								RemarkData  = "<table style='color:blue;font-family:verdana;font-size:13px;' class='table1' align='center' width='80%' bgcolor=''>";
								RemarkData += "<tr height='30px' style='color:white;font-family:verdana;font-size:13px;background-color:#078c9b;'><td colspan = '3'>Quantity : "+qty+"</td></tr>";
								RemarkData += "<tr height='27px' style='color:white;font-family:verdana;font-size:13px;background-color:#a5b23c;'><td>RBN No.</td><td>Date</td><td>Paid Percent ( % )</td></tr>";
								for(j=0; j<searchflagdetails.length; j+=3)
								{
									remarkPercent 	= searchflagdetails[j+0];
									remarkRbn 		= searchflagdetails[j+1];
									remarkDate 		= searchflagdetails[j+2];
									
									if((remarkPercent != "") && (remarkPercent != "X"))
									{
										//alert(remarkPercent)
										//alert(remarkRbn)
										RemarkData += "<tr id='trid"+j+"'><td>"+remarkRbn+"</td><td>"+remarkDate+"</td><td>"+remarkPercent+"</td></tr>";
									}
								}
								RemarkData += "</table>";
						}
						//var rate = document.getElementById("txt_item_rate_slm0").value;
						var amount = Number(qty)*Number(percent)*Number(rate)/100;
						 	TotalPaidDpmAmount = (Number(TotalPaidDpmAmount)+Number(amount));
						var table = document.getElementById("table3");
						var row = table.insertRow(table.rows.length-2);
							row.id = "rowid_dpm"+index;
							row.style.align = "center";
							
						var cell1=row.insertCell(0);
							cell1.setAttribute('class', "");
							cell1.style.textAlign = "center";	
							cell1.style.padding = "0px 0px 0px 0px";
						if(searchflag == "X")
						{	
							var txt_box1 = document.createElement("input");
							txt_box1.name = "txt_rbn_dpm";
							txt_box1.id = "txt_rbn_dpm"+index;
							txt_box1.value = rbn;
							txt_box1.style.width = 37+"px";
							txt_box1.style.textAlign = "center";
							txt_box1.style.pointerEvents = "none";
							txt_box1.setAttribute('class', "dynamictextbox"); 
							cell1.appendChild(txt_box1);
						}
						else
						{
							var txt_box1 = document.createElement("input");
							txt_box1.type = "hidden";
							txt_box1.name = "txt_rbn_dpm";
							txt_box1.id = "txt_rbn_dpm"+index;
							txt_box1.value = rbn;
							txt_box1.style.width = 37+"px";
							txt_box1.style.textAlign = "center";
							txt_box1.style.pointerEvents = "none";
							txt_box1.setAttribute('class', "dynamictextbox"); 
							cell1.appendChild(txt_box1);
							
							var srch_btn1 = document.createElement("input");
							srch_btn1.type = "image";
							srch_btn1.name = "srch_btn_dpm";
							srch_btn1.style.textAlign = "center";
							srch_btn1.id = "srch_btn_dpm"+index;
							srch_btn1.src = "images/search (10).png";
							srch_btn1.style.width = 25+"px";
							srch_btn1.style.height = 20+"px";
							
							var txt_remarkdata_dpm_1 = document.createElement("input");
								txt_remarkdata_dpm_1.type = "hidden";
								txt_remarkdata_dpm_1.id = "hid_dpm_remarkdata"+index;
								txt_remarkdata_dpm_1.name = "hid_dpm_remarkdata[]";
								txt_remarkdata_dpm_1.value = RemarkData;
								txt_remarkdata_dpm_1.setAttribute('class', "dynamictextbox");
								txt_remarkdata_dpm_1.style.width = 70+"px";
								txt_remarkdata_dpm_1.style.borderRadius = 0+"px";
								cell1.appendChild(txt_remarkdata_dpm_1);
							
							//srch_btn1.style.textAlign = "center";
							//srch_btn1.style.pointerEvents = "none";
							//srch_btn1.setAttribute('class', "dynamictextbox"); 
							/*txt_box6.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"percent","dpm");
												ValidatePercent(this,"dpm",ind);
												};
											})(index);*/
							cell1.appendChild(srch_btn1);
							srch_btn1.onclick = (function (ind) {
												return function() {
												  ShowRemarks(ind)
												  };
												})(index);
							/*srch_btn1.onclick = function () {
												  ShowRemarks(RemarkData)
												}*/
						}
								
							
						var cell2=row.insertCell(1);
							cell2.setAttribute('class', "dynamicrowcell");	
							cell2.style.padding = "0px 0px 0px 0px";
						var txt_box2 = document.createElement("input");
							txt_box2.name = "txt_partpay_qty_dpm";
							txt_box2.id = "txt_partpay_qty_dpm"+index;
							txt_box2.value = Number(qty).toFixed(3);
							txt_box2.style.width = 90+"px";
							txt_box2.style.textAlign = "right";
							txt_box2.style.pointerEvents = "none";
							txt_box2.setAttribute('class', "dynamictextbox"); 
							cell2.appendChild(txt_box2);
							
						var cell3=row.insertCell(2);
							cell3.setAttribute('class', "dynamicrowcell");	
							cell3.style.padding = "0px 0px 0px 0px";
						var txt_box3 = document.createElement("input");
							txt_box3.name = "txt_item_rate_dpm";
							txt_box3.id = "txt_item_rate_dpm"+index;
							txt_box3.value = Number(rate).toFixed(2);
							txt_box3.style.width = 80+"px";
							txt_box3.style.textAlign = "right";
							txt_box3.style.pointerEvents = "none";
							txt_box3.setAttribute('class', "dynamictextbox"); 
							cell3.appendChild(txt_box3);
							
						var cell4=row.insertCell(3);
							cell4.setAttribute('class', "dynamicrowcell");	
							cell4.style.padding = "0px 0px 0px 0px";
						var txt_box4 = document.createElement("input");
							txt_box4.name = "txt_partpay_percent_dpm";
							txt_box4.id = "txt_partpay_percent_dpm"+index;
							txt_box4.value = Number(percent);
							txt_box4.style.width = 35+"px";
							txt_box4.style.textAlign = "right";
							txt_box4.style.pointerEvents = "none";
							txt_box4.setAttribute('class', "dynamictextbox"); 
							cell4.appendChild(txt_box4);
						
						var cell5=row.insertCell(4);
							cell5.setAttribute('class', "dynamicrowcell");	
							cell5.style.padding = "0px 0px 0px 0px";
						var txt_box5 = document.createElement("input");
							txt_box5.name = "txt_partpay_amt_dpm";
							txt_box5.id = "txt_partpay_amt_dpm"+index;
							txt_box5.value = Number(amount).toFixed(2);
							txt_box5.style.width = 110+"px";
							txt_box5.style.textAlign = "right";
							txt_box5.style.pointerEvents = "none";
							txt_box5.setAttribute('class', "dynamictextbox"); 
							cell5.appendChild(txt_box5);
							
						var cell6=row.insertCell(5);
							cell6.setAttribute('class', "dynamicrowcell");	
							cell6.style.padding = "0px 0px 0px 0px";
						if(percent < 100)
						{
							var txt_box6 = document.createElement("input");
								txt_box6.name = "txt_percent_dpm_payable";
								txt_box6.id = "txt_percent_dpm_payable"+index;
								txt_box6.value = Number(PayableDpmSlmPercent);
								txt_box6.style.width = 35+"px";
								txt_box6.style.border = "1px solid #2aade4";
								txt_box6.style.backgroundColor = "#ffffff";
								txt_box6.style.textAlign = "right";
								txt_box6.setAttribute('class', "dynamictextbox");
								txt_box6.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"percent","dpm");
												ValidatePercent(this,"dpm",ind);
												};
											})(index); 
								cell6.appendChild(txt_box6);
						}
						else
						{
							cell6.innerHTML = "";
						}
							
						var cell7=row.insertCell(6);
							cell7.setAttribute('class', "dynamicrowcell");	
							cell7.style.padding = "0px 0px 0px 0px";
						if(percent < 100)
						{
							var txt_box7 = document.createElement("input");
								txt_box7.name = "txt_amt_dpm_payable[]";
								txt_box7.id = "txt_amt_dpm_payable"+index;
								txt_box7.value = Number(PayableSlmDpmAmt).toFixed(2);
								txt_box7.style.width = 110+"px";
								txt_box7.style.textAlign = "right";
								txt_box7.style.pointerEvents = "none";
								txt_box7.setAttribute('class', "dynamictextbox"); 
								cell7.appendChild(txt_box7);
						}
						else
						{
							cell7.innerHTML = "";
						}
						
						var txt_box8 = document.createElement("input");
							txt_box8.type = "hidden";
							txt_box8.id = "hid_dpm_result"+index;
							txt_box8.name = "hid_dpm_result[]";
							txt_box8.value = result;
							txt_box8.setAttribute('class', "dynamictextbox");
							txt_box8.style.width = 70+"px";
							txt_box8.style.borderRadius = 0+"px";
							cell7.appendChild(txt_box8);
							
						var txt_box9 = document.createElement("input");
							txt_box9.type = "hidden";
							txt_box9.id = "hid_dpm_mbid"+index;
							txt_box9.name = "hid_dpm_mbid";
							txt_box9.value = measurementbookid;
							txt_box9.setAttribute('class', "dynamictextbox");
							txt_box9.style.width = 70+"px";
							txt_box9.style.borderRadius = 0+"px";
							cell7.appendChild(txt_box9);
						index++;	
					}
					document.getElementById("txt_partpay_total_paidamt_dpm").value = Number(TotalPaidDpmAmount).toFixed(2);
					document.getElementById("txt_partpay_total_payableamt_dpm").value = Number(TotalPayableDpmAmount).toFixed(2);
				}
				
			}
		}
		xmlHttp.send(strURL);	
	}
	
	function getSLMdetaiils(sheetid,itemid,rate)
	{
		var xmlHttp; 
		var data;
		var i, rbn, qty, percent, newrow = "", amt;
		var slmitemQty = document.getElementById("hid_slm_qty").value;
		if(Number(slmitemQty) == 0)
		{
			document.getElementById("rowid0").className = "hide";
		}
		else
		{
			document.getElementById("rowid0").className = "";
		}
		var rate = Number(rate);	
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_slm_details.php?sheetid="+sheetid+"&itemid="+itemid;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText; 
				if(data != "")
				{
					
					var x = Number(document.getElementById("table4").rows.length);
					var index = x;
					var Splitdata = data.split("@@");
					var SlmRemarks = Splitdata[1];
					var SlmDetails = Splitdata[0];
					var details = SlmDetails.split("*");
					for(i=0; i<details.length; i+=3)
					{
						//var arr = index;
						var arg = "X"+"*"+index;
							rbn 	= details[i];
							qty 	= details[i+1];
							percent = details[i+2];
							var result = percent+"*"+rbn+"*"+qty+"*"+itemid;
							amt 	= Number(qty)*Number(rate)*Number(percent)/100;
						var table=document.getElementById("table4");
						var row=table.insertRow(table.rows.length-2);
							row.id = "rowid"+index;
							row.style.align = "center";
						var cell1=row.insertCell(0);
							cell1.setAttribute('class', "dynamicrowcell");
							cell1.style.padding = "0px 0px 0px 0px";
								
						var txt_box1 = document.createElement("input");
							txt_box1.name = "txt_partpay_qty_slm[]";
							txt_box1.id = "txt_partpay_qty_slm"+index;
							txt_box1.value = Number(qty);
							txt_box1.style.width = 93+"px";
							txt_box1.style.border = "1px solid #2aade4";
							txt_box1.style.textAlign = "right";
							txt_box1.setAttribute('class', "dynamictextbox"); 
							cell1.appendChild(txt_box1);
							txt_box1.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"qty","slm");
												};
											})(index);
						var cell2=row.insertCell(1);
							cell2.setAttribute('class', "dynamicrowcell");	
							cell2.style.padding = "0px 0px 0px 0px";
						var txt_box2 = document.createElement("input");
							txt_box2.name = "txt_item_rate_slm";
							txt_box2.id = "txt_item_rate_slm"+index;
							txt_box2.value = Number(rate).toFixed(2);
							txt_box2.style.textAlign = "right";
							txt_box2.style.width = 80+"px";
							txt_box2.readOnly = true;
							txt_box2.setAttribute('class', "dynamictextbox"); 
							cell2.appendChild(txt_box2);
							txt_box2.onblur = (function (ind) {
												return function() {
												calculateAmount(this,ind,"rate","slm");
												};
											})(index);
						var cell3=row.insertCell(2);
							cell3.setAttribute('class', "dynamicrowcell");	
							cell3.style.padding = "0px 0px 0px 0px";
						var txt_box3 = document.createElement("input");
							txt_box3.name = "txt_partpay_percent_slm";
							txt_box3.id = "txt_partpay_percent_slm"+index;
							txt_box3.value = percent;
							txt_box3.style.width = 40+"px";
							txt_box3.style.textAlign = "right";
							txt_box3.style.border = "1px solid #2aade4";
							txt_box3.setAttribute('class', "dynamictextbox"); 
							cell3.appendChild(txt_box3);
							txt_box3.onblur= (function (ind) {
												return function() {
												calculateAmount(this,ind,"percent","slm");
												ValidatePercent(this,"slm",ind);
												};
											})(index);
											
						var cell4=row.insertCell(3);
							cell4.setAttribute('class', "dynamicrowcell");	
							cell4.style.padding = "0px 0px 0px 0px";
						var txt_box4 = document.createElement("input");
							txt_box4.name = "txt_partpay_amt_slm[]";
							txt_box4.id = "txt_partpay_amt_slm"+index;
							txt_box4.value = Number(amt).toFixed(2);
							txt_box4.style.width = 130+"px";
							txt_box4.style.textAlign = "right";
							txt_box4.style.pointerEvents = "none";
							txt_box4.setAttribute('class', "dynamictextbox"); 
							cell4.appendChild(txt_box4);
						
						
						if(i == 0)
						{
							var cell5=row.insertCell(4);
								//cell5.style.width = 10+"px";
								cell5.style.textAlign = "center";
								cell5.style.padding = "0px 0px 0px 0px";
							var addbtn=document.createElement("input");
								addbtn.type = "button";
								addbtn.value = " + ";
								addbtn.id = "btn_add_row_slm"+index;
								addbtn.name = "btn_add_row_slm";
								addbtn.setAttribute('class', "delbtnstyle");
								addbtn.style.width = 32+"px";
								addbtn.style.borderRadius = 0+"px";
								addbtn.onclick = function () {
												  addRow()
												}
								cell5.appendChild(addbtn);
						
						
						}
						else
						{
							var cell5=row.insertCell(4);
								//cell5.style.width = 10+"px";
								cell5.style.textAlign = "center";
								cell5.style.padding = "0px 0px 0px 0px";
							var delbtn=document.createElement("input");
								delbtn.type = "button";
								delbtn.value = " X ";
								delbtn.id = "btn_delete"+index;
								delbtn.name = "btn_delete";
								delbtn.setAttribute('class', "delbtnstyle");
								delbtn.style.width = 32+"px";
								delbtn.style.borderRadius = 0+"px";
								delbtn.onclick = (function (ind) {
												  //deleteRow(row.id)
												  	return function() {
													deleteRow(this);
													};
												})(index);
								cell5.appendChild(delbtn);
						}	
						
							//var cell6=row.insertCell(5);
							//cell5.style.width = 10+"px";
							//cell6.style.textAlign = "center";
							//cell6.style.padding = "0px 0px 0px 0px";
							//cell6style.visibility = "hidden";	
							
					// BELOW FIELD IS HIDDEN BOX FIELD....SO APPEND IN ADD & DELETE BUTTON FIELD ITSELF..No seperate TD(cell) creation for this. check above ( index++ : line)
						var txt_box5 = document.createElement("input");
							txt_box5.type = "hidden";
							txt_box5.id = "hid_slm_result"+index;
							txt_box5.name = "hid_slm_result[]";
							txt_box5.value = result;
							txt_box5.setAttribute('class', "dynamictextbox");
							txt_box5.style.width = 70+"px";
							txt_box5.style.borderRadius = 0+"px";
							cell5.appendChild(txt_box5);
							index++;
						var elmt = document.getElementById("rowid0");
							elmt.style.display = "none";
							totalAmountCalculation("slm");
						//index++;
							result = "";
					}
					if(SlmRemarks != "")
					{
						document.getElementById("txt_slm_remarks").value = SlmRemarks;
					}
					else
					{
						document.getElementById("txt_slm_remarks").value = "";
					}
				}totalAmountCalculation("slm");
					//DpmPayableAmount = document.getElementById("txt_partpay_total_payableamt_dpm").value;
				//var OverAllAmount = Number(SlmTotalAmount)+Number(DpmPayableAmount);
				//document.getElementById("txt_overall_total").value = Number(OverAllAmount).toFixed(2);

			}
		}
		xmlHttp.send(strURL);	
	}
	
	function saveDataDetails()
	{
		var result1 = "X";
		$('input[name="hid_slm_result[]"]').each(function() {
			var res1 = $(this).val();
			if(res1 != "")
			{
				result1 = res1 + "@"+ result1;
			}
		});
		
		var result2 = "Y";
		$('input[name="hid_dpm_result[]"]').each(function() {
			var res2 = $(this).val();
			result2 = res2 + "@"+ result2;
		});
		//alert(result1)
		//alert(result2)
		var result = result1 + "###" + result2;
		//alert(result);
		var itemid = document.getElementById("txt_item_id").value;
		var itemStr = document.getElementById("hid_item_str"+itemid).value;
		var SlmRemarks = document.getElementById("txt_slm_remarks").value;
		var DpmRemarks = document.getElementById("txt_dpm_remarks").value;
		var RemarksStr = SlmRemarks + "*" + DpmRemarks;
		//alert(itemStr);
		var sheetid = document.getElementById("txt_sheet_id").value;
		$.post("Partpayment_Update.php", {resultdata: result, sheetid: sheetid, itemStr: itemStr, RemarksStr: RemarksStr}, function (data) {
		//alert(data);
			if(data == 1)
			{
				
				//swal("", "Sucessfully Updated...!", "success");
				location.reload();
				//$.modal.close();
				
			}
        });
		
	}
	
	function ShowRemarks(id)
	{
		var idcount = id;
		var RemarksData = document.getElementById("hid_dpm_remarkdata"+idcount).value;
		swal({
		title: "<small>Deduct Previous Measurement Remarks</small>",
		text: "<small>"+RemarksData+"</small>",
		html: true
	});
	}
	
	function SaveData()
	{
		swal({   title: "Are you sure?",   
			text: "You want to update this data..?!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, Update!",   
			cancelButtonText: "No, Cancel!",   
			closeOnConfirm: false,   
			closeOnCancel: false }, 
			function(isConfirm){   
			if (isConfirm) 
			{     
				saveDataDetails();  
			} 
			else 
			{     
				swal("Cancelled", "Your data not updated:)", "error");   
			} 
		});
	}
	function CancelData()
	{
		swal({   title: "Are you sure?",   
			text: "You want to Cancel this operation..?!",   
			type: "warning",   
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
	// Load dialog on click
		$('input[name="check"]').click(function (e) 
		{
			if($(this).is(':checked'))
			{
				// THIS PART IS FOR SINCE LAST MEASUREMENT SECTION //
				var SlmTotalAmount = 0, DpmPayableAmount = 0;
				var itemdetails = this.value;
				var split_itemdetails = itemdetails.split("*");
				var subdivid 	= split_itemdetails[0];
				var subdivname 	= split_itemdetails[1];
				var description = split_itemdetails[2];
				var slm_qty		= Number(split_itemdetails[3]);
				var dpm_qty		= Number(split_itemdetails[4]);
				var rate 		= Number(split_itemdetails[5]); 
				var itemunit	= split_itemdetails[6];
				var sheetid		= split_itemdetails[7];
					document.getElementById("txt_item_no").value = subdivname;
					document.getElementById("txt_item_id").value = subdivid;
					document.getElementById("txt_item_desc").value = description;
					document.getElementById("txt_slm_qty").value = slm_qty.toFixed(3)+" "+itemunit;
					document.getElementById("txt_dpm_qty").value = dpm_qty.toFixed(3)+" "+itemunit;
					document.getElementById("txt_item_rate_slm0").value = rate.toFixed(2);
					document.getElementById("hid_slm_qty").value = slm_qty.toFixed(3);
					//var tablerow;
					//tablerow = "<tr><td>"+subdivname+"</td><td>"+description+"</td></tr>";
					//$('#table2 tr:last').after(tablerow);
					
				// THIS PART IS FOR DEDUCT PREVIOUS MEASUREMENT SECTION //
				getDPMdetaiils(sheetid,subdivid,rate);
				getSLMdetaiils(sheetid,subdivid,rate);
				
				//SlmTotalAmount = document.getElementById("txt_partpay_total_amt_slm").value;
				//DpmPayableAmount = document.getElementById("txt_partpay_total_payableamt_dpm").value;
				//alert(SlmTotalAmount);
				//alert(DpmPayableAmount);
				//var OverAllAmount = Number(SlmTotalAmount)+Number(DpmPayableAmount);
				//document.getElementById("txt_overall_total").value = Number(OverAllAmount).toFixed(2);
				
				$('#basic-modal-content').modal();
				
			}
			//return false;
		});
		
		$('#btn_save').click(function (e) 
		{
			if($('#table2 tr').size()>1)
			{
				//$('#table2 tr:last-child').remove();
				var temp = 1;
			}
			/*if(temp == 1)
			{
				$.modal.close();
				}*/
			
		});
		
	});
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
	border: 1px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
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
	border:1px solid #cacaca;
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
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body bgcolor="" id="top" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<!--<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
	</tr>
</table>-->
<form name="form" method="post" onsubmit="return confirm('Do you really want to submit the Book?');">
<?php
$page = $abstmbpage;
$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
?>
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 

/*$sheetid	=  $_GET['sheetid'];
$type		=  $_GET['type'];
$bid		=  $_GET['bid'];
$from_date	=  $_SESSION['escal_tcc_from_date'];
$to_date	=  $_SESSION['escal_tcc_to_date'];
$quarter	=  $_SESSION['esc_quarter'];
$fromdate 	=  dt_format($from_date);
$todate 	=  dt_format($to_date);*/
//$fromdate = '2016-06-01';
//$todate = '2017-01-30';
//$EscMonthArr 	=	array();
$EscTestArr1		 = array();
$EscMonthRowSpanList = array();
$MonRowSpanList 	 = array();

$RbnMonthList 	 	 = array();
$EscAllRbnlist 		 = array();
$EscAllMonthRbnlist  = array();
$EscSelectedMonthRbnlist  = array();


// Extraxt all month from the escalaton period and store it in array
$EscMonthList		=	GetAllEscMonth($fromdate,$todate);
$EscMonthCnt		=	count($EscMonthList);
for($i=0; $i<$EscMonthCnt; $i++)
{
	$escMonth 		= $EscMonthList[$i];
	// Initially set the value of escalation month is to zero for checking which month is exist in "abstract rbn"
	$EscTestArr1[$escMonth] 		= 0;
	// Initially set the value of escalation month is to one for calculate the month column rowspan.
	$EscMonthRowSpanList[$escMonth] = 1;
}


$fomdate_temp	=	strtotime($fromdate);
$from_mon_yr	=	date("M-Y",$fomdate_temp);

$todate_temp	=	strtotime($todate);
$to_mon_yr		=	date("M-Y",$todate_temp);

$diff_month_cnt 	= 0;
$total_rbn_amount 	= 0;
$month_cnt 			= 0;
$PiStr 				= "";
$CntStr 			= "";
$AbsStr 			= "";
$ExistMonthList			= array();
$NonExistMonthList		= array();
$RbnAllFromToMonthList 	= array();
$AbsBookIDList 			= array();

$AllMonRbnStr = ""; 
$select_abs_month_query = 	"SELECT * FROM abstractbook WHERE sheetid = '$abstsheetid' 
							and ((DATE(fromdate) BETWEEN '$fromdate' AND '$todate') OR (DATE(todate) BETWEEN '$fromdate' AND '$todate'))";
$select_abs_month_sql 	= 	mysqli_query($dbConn,$select_abs_month_query);
//echo $select_abs_month_query;
if($select_abs_month_sql == true)
{
	if(mysqli_num_rows($select_abs_month_sql)>0)
	{
		while($AbsList = mysqli_fetch_object($select_abs_month_sql))
		{
			$absbookid  		= $AbsList->absbookid;
			$rbn_fromdate 		= $AbsList->fromdate;
			$rbn_todate 		= $AbsList->todate;
			array_push($AbsBookIDList,$absbookid);
			// Change the Fromdate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp1			= strtotime($rbn_fromdate);
			$from_month_year	= date("M-Y",$date_temp1);
			// Change the Todate format from "yyyy-mm-dd" to "Mon-yyyy"
			$date_temp2			= strtotime($rbn_todate);
			$to_month_year		= date("M-Y",$date_temp2);
			// Check Month-Year of Fromdate and Todate is equal or not
			if($from_month_year == $to_month_year)
			{
				// If Fromdate date month and Two date month is same then assign any one one of them to the variable $rbn_month_year and store it in a array
				$rbn_month_year = $from_month_year;
				array_push($RbnAllFromToMonthList,$rbn_month_year);
				array_push($EscAllMonthRbnlist,$AbsList->rbn);
				$AllMonRbnStr .= $AbsList->rbn."*".$rbn_month_year."@";
			}
			else
			{
				// If Fromdate month and Todate month is different then extract all month between these two date in "Mon-yyyy" fromat
				$RbnMonthList	=	GetAllRbnMonth($rbn_fromdate,$rbn_todate);
				// Using for loop get each month and store it in a array for future purpose
				for($x1=0; $x1<count($RbnMonthList); $x1++)
				{
					$rbn_month_year = $RbnMonthList[$x1];
					array_push($RbnAllFromToMonthList,$rbn_month_year);
					array_push($EscAllMonthRbnlist,$AbsList->rbn);
					$AllMonRbnStr .= $AbsList->rbn."*".$rbn_month_year."@";
				}
			}
			
		}
	}
}


$RbnAllMonCnt 		= count($RbnAllFromToMonthList);
$UniqRbnMonthList 	= array_values(array_unique($RbnAllFromToMonthList)); // remove the mutiple entry of same month and get unique value. ( Note: array_values used to set array key value as array[0], array[1]....)
$UniqRbnMonthCnt 	= count($UniqRbnMonthList);
//print_r($RbnAllFromToMonthList);
for($x2=0; $x2<$UniqRbnMonthCnt; $x2++)
{
	$rbn_month 	 	= $UniqRbnMonthList[$x2];
	if(in_array($rbn_month, $EscMonthList))  // Check Whether Month is exist in Escalation Period
	{
		array_push($ExistMonthList,$rbn_month);
		// Set the month rowspan value of each month.
		//$EscMonthRowSpanList[$rbn_month] = $MonthCount[$rbn_month];
	}
	else // Check Whether Month is not exist in Escalation Period.
	{
		array_push($NonExistMonthList,$rbn_month);
	}
}
$ExistMonthCnt 		= count($ExistMonthList);
$NonExistMonthCnt 	= count($NonExistMonthList);
if($NonExistMonthCnt>0)
{
	// Some month which is not exist in the period of escalation so we need to generate rough MBook, Abstract etc.
	$Error_msg = "Need to generate rough MBook, Abstract etc";
}
else
{
	$Error_msg = "";
}

if($AllMonRbnStr != "")
{
	$AllMonRbnStr = rtrim($AllMonRbnStr,"@");
}
$SelRbnArr = array();
$NotSelRbnArr = array();
$expAllMonRbnStr = explode("@",$AllMonRbnStr);
for($k1=0; $k1<count($expAllMonRbnStr); $k1++)
{
	$AllMonRbn 		= $expAllMonRbnStr[$k1];
	$expAllMonRbn 	= explode("*",$AllMonRbn);
	$expRbn 	= $expAllMonRbn[0];
	$expMonth 	= $expAllMonRbn[1];
	//echo $expMonth."<br/>";
	if(in_array($expMonth, $NonExistMonthList))
	{
		// So dont take this rbn because this rbn's period is out of escalation period
		array_push($NotSelRbnArr,$expRbn);
	}
	else
	{
		$SelRbnStr .= $expRbn."*";
		array_push($SelRbnArr,$expRbn);
	}
}
$UniqSelRbnArr 		= array_values(array_unique($SelRbnArr)); 
$UniqNotSelRbnArr 	= array_values(array_unique($NotSelRbnArr)); 
$RBNarr_1 = array_values(array_diff($UniqSelRbnArr,$UniqNotSelRbnArr));
// array_diff will remove the element which exist in both array and return first array
// array_values will set the array index key value as from beginning i.e a[0], a[1], a[2]...
sort($RBNarr_1, SORT_NUMERIC); 
$arr_1Cnt = count($RBNarr_1);
$slm_rbn = $arr_1[$arr_1Cnt-1];
//print_r($UniqNotSelRbnArr);
//echo $slm_rbn;
//$Line = $Line+2;
// $subdivisionlist_2 = "";
$DevItemArray = array();
$WoQtyItemArr = array();
for($k2=0; $k2<$arr_1Cnt; $k2++)
{
$Crbn = $RBNarr_1[$k2];
	$table = ""; $AbstractStr = ""; $OverAllSlmAmount = 0; $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $slm_amount_item = 0;
	$dpm_amount_item = 0; $total_amt_item = 0; $SlmRebateAmount = 0; $DpmRebateAmount = 0; $SlmDpmRebateAmount = 0; $SlmNetAmount = 0;
	$DpmNetAmount = 0; $SlmDpmNetAmount = 0;
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
	$table = $table . "<td class=''>Running Account bill No. </td>";
	$table = $table . "<td class=''>" . $Crbn . "</td>";
	$table = $table . "<td class='' align='right'>CC No. </td>";
	$table = $table . "<td class=''>" . $ccno . "</td>";
	$table = $table . "</tr>";
	//$table = $table . "<tr>";
	//$table = $table . "<td colspan ='4' class='labelprint' align='center'>Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
	//$table = $table . "</tr>";
	$table = $table . "</table>";
	//$Line = $Line+6;
	//$tablehead = $tablehead . "<table width='1087px' frame=''  bgcolor='#0A9CC5' border='1' cellpadding='3' cellspacing='3' align='center' style='color:#ffffff;' id='mbookdetail' class='label table1'>";
	$tablehead = "";
	$tablehead = $tablehead . "<tr style='' class='labelprint'>";
	//$tablehead = $tablehead . "<td  align='center' class='labelsmall labelheadblue' width='12px' style='background-color:#0A9CC5;' rowspan='2'></td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='44px' rowspan='2'>Item No.</td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='130px' rowspan='2'>Description of work</td>";
	$tablehead = $tablehead . "<td  align='center'  width='40px' rowspan='2'>Contents of Area</td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Rate&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Per</td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Total value to Date&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='100px' colspan='3'>Deduct previous Measurements</td>";
	$tablehead = $tablehead . "<td  align='center' class='' width='120px' colspan='3'>Since Last Measurement</td>";
	$tablehead = $tablehead . "</tr>";
	$tablehead = $tablehead . "<tr style='' class='labelprint'>";
	$tablehead = $tablehead . "<td width='30px' align='center' class=''>Page</td>";
	$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
	$tablehead = $tablehead . "<td width='40px' align='center' class=''>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
	$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
	$tablehead = $tablehead . "<td width='40px' align='center' class=''>Value&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
	$tablehead = $tablehead . "<td width='40px' align='center' class=''>Remark</td>";
	$tablehead = $tablehead . "</tr>";
	//$tablehead = $tablehead . "</table>";
	?>
	<?php echo $table; ?>
	<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
	<?php echo $tablehead; ?>
	<?php 
echo "<tr style='background-color:#EEEEEE;' class='labelprint'><td colspan='12' align='center' class='labelbold'>Escalation Abstract for RAB - ".$Crbn."</td></tr>";
	$subdivisionlist_2 = ""; $subdivid_list = "";
	$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = "";
	//$unionqur = "(SELECT subdivid FROM mbookgenerate WHERE sheetid = '$abstsheetid' and rbn <= '$Crbn') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1') and rbn <= '$Crbn')";
	$unionqur = "(SELECT mbookgenerate.subdivid, schdule.subdiv_id, schdule.item_flag FROM mbookgenerate 
				INNER JOIN schdule ON (mbookgenerate.subdivid = schdule.subdiv_id)
				WHERE mbookgenerate.sheetid = '$abstsheetid' and mbookgenerate.rbn <= '$Crbn'
				and schdule.item_flag = 'NI') 
				 UNION 
				(SELECT measurementbook.subdivid, schdule.subdiv_id, schdule.item_flag FROM measurementbook 
				INNER JOIN schdule ON (measurementbook.subdivid = schdule.subdiv_id)
				WHERE measurementbook.sheetid = '$abstsheetid' AND schdule.item_flag = 'NI' AND 
				(measurementbook.part_pay_flag = '0' OR measurementbook.part_pay_flag = '1') 
				and measurementbook.rbn <= '$Crbn')";
	//echo $unionqur;
	$unionsql = mysqli_query($dbConn,$unionqur);
	while($Listsubdivid = mysqli_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
	$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
	natsort($subdivisionlist_1);
	foreach($subdivisionlist_1 as $key => $summ_1)
	{
	   if($summ_1 != "")
	   {
		  $subdivisionlist_2 .= $summ_1.",";
		  $check_subdivid = $summ_1;
		  $DevQtyRes = CheckDeviatedQty($abstsheetid,$check_subdivid,$Crbn);
		  $expDevQtyRes = explode("*",$DevQtyRes);
		  $OrigWoQty = $expDevQtyRes[0];
		  $DevQtyChq = $expDevQtyRes[1];
		  //echo $DevQtyChq."<br/>";
		  if($DevQtyChq == 1)
		  {
		  	array_push($DevItemArray,$check_subdivid);
			$WoQtyItemArr[$check_subdivid] = $OrigWoQty;
		  }
		  
	   }
	}
	$subdivisionlist = explode(',',rtrim($subdivisionlist_2,","));
	
	//print_r($subdivisionlist);exit;
	
	for($i=0;$i<count($subdivisionlist);$i++)
	{
		$DpmArrPercent 			= array();
		$DpmArrPayPercentList 	= array();
		$DpmArrQuantityList 	= array();
		$DpmArrRbnList 			= array();
		$DpmArrAmbList			= array();
		$DpmArrAmbPgList		= array();
		$DpmArrMbidList			= array();
		$SlmArrMbidList			= array();
		$SlmArrQuantityList 	= array();
		$SlmArrPayPercentList 	= array();
		$slm_mesurementbook_details = ""; $dpm_mesurementbook_details = "";
		$slm_measurement_qty = 0; $dpm_measurement_qty = 0; $slm_cnt = 0; $dpm_cnt = 0;  $rowcount = 0; $slm_amount_item = 0; $dpm_amount_item = 0;
		$schduledetails = 	getschduledetails($abstsheetid,$subdivisionlist[$i]);
		$rateandunit 	= 	explode('*',$schduledetails);
		$rate 			= 	$rateandunit[0];
		$unit 			= 	$rateandunit[1];
		$decimal 		= 	get_decimal_placed($subdivisionlist[$i],$abstsheetid);
		
		
		$DevPartPayStr = "";
	//*************THIS PART IS FOR SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//
	if(in_array($subdivisionlist[$i],$DevItemArray))
	{
		$slm_measurement_qty = 0;
		$slm_cnt = 0;
	}
	else
	{
		$mbookslmquery = "SELECT * FROM measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' and rbn = '$Crbn'";// AND  (part_pay_flag = '0' OR  part_pay_flag = '1')";
		$mbookslmquery_sql = mysqli_query($dbConn,$mbookslmquery);
		if(mysqli_num_rows($mbookslmquery_sql)>0)
		{
			$SubdividSlmStr .= $subdivisionlist[$i]."*";
			while($SLMList = mysqli_fetch_array($mbookslmquery_sql))
			{
				if(($SLMList['part_pay_flag'] =='0') || ($SLMList['part_pay_flag'] == '1'))
				{
					$slm_mesurementbook_details .= $SLMList['subdivid']."*".$SLMList['mbtotal']."*".$SLMList['mbno']."*".$SLMList['mbpage']."*".$SLMList['divid']."*".$SLMList['abstmbookno']."*".$SLMList['abstmbpage']."*".$SLMList['pay_percent']."*".$SLMList['flag']."*".$SLMList['part_pay_flag']."*".$SLMList['remarks']."*".$SLMList['rbn']."*";
					$slm_measurement_qty = $slm_measurement_qty + $SLMList['mbtotal'];
					$mbookno_slm 		= 	$SLMList['mbno'];
					$mbpageno_slm 		= 	$SLMList['mbpage'];
					$absmbookno_slm 	= 	$SLMList['abstmbookno'];
					$absmbpageno_slm 	= 	$SLMList['abstmbpage'];
					$flag_slm			= 	$SLMList['flag'];
					$partpay_flag_slm 	= 	$SLMList['part_pay_flag'];
					$divid				= 	$SLMList['divid'];
					$payment_percent 	= 	$SLMList['pay_percent'];
					if($payment_percent < 100)
					{
						$DevPartPayStr .=  $SLMList['subdivid']."*".$SLMList['mbtotal']."*".$payment_percent."@";
					}
					$PartPayremarks		=	$SLMList['remarks'];
					$slm_cnt++;
				}
				else
				{
				
					$qty_dpm_slm 		= 	$SLMList['mbtotal'];
					$percent_dpm_slm 	= 	$SLMList['pay_percent'];
					if($SLMList['part_pay_flag'] != "")
					{
						$partpay_flag_slm = $SLMList['part_pay_flag'];
						$explode_partpayflag_dpm_slm = explode("*",$partpay_flag_slm);
						$rbn_dpm_slm 	= $explode_partpayflag_dpm_slm[1];
						$bmid_dpm_slm	= $explode_partpayflag_dpm_slm[2];
						array_push($SlmArrMbidList,$bmid_dpm_slm);
						array_push($SlmArrQuantityList,$qty_dpm_slm);
						array_push($SlmArrPayPercentList,$percent_dpm_slm);
					}
				}
			}
		}
		else
		{
			$slm_measurement_qty = 0;
			$slm_cnt = 0;
		}
	}
	//}
	//else
	//{
		//$slm_measurement_qty = 0;
		//$slm_cnt = 0;
	//}
		//echo "A = ".$slm_cnt."<br/>";
		//echo $slm_mesurementbook_details;exit;
		
	//*************THIS PART IS FOR DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*******************//
		$TempDpmQty = 0; $dpm_mesurementbook_details_2 = ""; $dpm_mesurementbook_details_1 = "";
		$mbookdpmquery = "SELECT * FROM measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' and rbn < '$Crbn' ORDER BY rbn ASC ";// AND  part_pay_flag = '0'";
		$mbookdpmquery_sql = mysqli_query($dbConn,$mbookdpmquery);
		if(mysqli_num_rows($mbookdpmquery_sql)>0)
		{
			//array_push($subdivid_array,$subdivisionlist[$i]);
			while($DPMList = mysqli_fetch_array($mbookdpmquery_sql))
			{
				if(($DPMList['part_pay_flag'] == '0') || ($DPMList['part_pay_flag'] == '1'))
				{
					if($DPMList['pay_percent'] == '100')
					{
						$TempDpmQty = $TempDpmQty + $DPMList['mbtotal'];
						$dpm_measurement_qty 	= 	$dpm_measurement_qty + $DPMList['mbtotal']; 
						$dpm_mesurementbook_details_1 = $DPMList['subdivid']."*".$TempDpmQty."*".$DPMList['mbno']."*".$DPMList['mbpage']."*".$DPMList['divid']."*".$DPMList['abstmbookno']."*".$DPMList['abstmbpage']."*".$DPMList['pay_percent']."*".$DPMList['flag']."*".$DPMList['part_pay_flag']."*".$DPMList['remarks']."*".$DPMList['rbn']."*".$DPMList['measurementbookid']."*";
					}
					else
					{
						$dpm_mesurementbook_details_2 .= $DPMList['subdivid']."*".$DPMList['mbtotal']."*".$DPMList['mbno']."*".$DPMList['mbpage']."*".$DPMList['divid']."*".$DPMList['abstmbookno']."*".$DPMList['abstmbpage']."*".$DPMList['pay_percent']."*".$DPMList['flag']."*".$DPMList['part_pay_flag']."*".$DPMList['remarks']."*".$DPMList['rbn']."*".$DPMList['measurementbookid']."*";
						$dpm_measurement_qty 	= 	$dpm_measurement_qty + $DPMList['mbtotal'];
						$mbookno_dpm 			= 	$DPMList['mbno'];
						$mbpageno_dpm 			= 	$DPMList['mbpage'];
						$absmbookno_dpm 		= 	$DPMList['abstmbookno'];
						$absmbpageno_dpm 		= 	$DPMList['abstmbpage'];
						$flag_dpm				= 	$DPMList['flag'];
						$partpay_flag_dpm 		= 	$DPMList['part_pay_flag'];
						$divid					= 	$DPMList['divid'];
						$paypercent_dpm_init	=	$DPMList['pay_percent'];
						$measurebookid_dpm_int	=	$DPMList['measurementbookid'];
						$DpmArrPercent[$measurebookid_dpm_int]	=	$paypercent_dpm_init;
						$dpm_cnt++;
					}
					//echo $dpm_measurement_qty."<br/>";
				}
				elseif($DPMList['part_pay_flag'] == 'DMY')
				{
					$absmbookno_dpm 	= 	$DPMList['abstmbookno'];
					$absmbpageno_dpm 	= 	$DPMList['abstmbpage'];
				}
				else
				{
					$paypercent_dpm		=	$DPMList['pay_percent'];
					$qty_dpm			=	$DPMList['mbtotal'];
					$partpay_flag_dpm 	= 	$DPMList['part_pay_flag'];
					$absmbookno_dpm 	= 	$DPMList['abstmbookno'];
					$absmbpageno_dpm 	= 	$DPMList['abstmbpage'];
					$divid				= 	$DPMList['divid'];
					$explode_partpay_flag	 = explode("*",$partpay_flag_dpm);
					
					$PartpayRbn 		= 	$explode_partpay_flag[1];
					$PartpayMbid 		= 	$explode_partpay_flag[2];
					array_push($DpmArrPayPercentList,$paypercent_dpm);
					array_push($DpmArrQuantityList,$qty_dpm);
					array_push($DpmArrRbnList,$PartpayRbn);
					array_push($DpmArrAmbList,$absmbookno_dpm);
					array_push($DpmArrAmbPgList,$absmbpageno_dpm);
					array_push($DpmArrMbidList,$PartpayMbid);
				}
				$AbstractMbookNoDpm 		= $DPMList['abstmbookno'];
				$AbstractMbookPageNoDpm		= $DPMList['abstmbpage'];
			}
			if($dpm_mesurementbook_details_1 != ""){ $dpm_cnt++; }
			$dpm_mesurementbook_details = $dpm_mesurementbook_details_1.$dpm_mesurementbook_details_2;
			
			//if($flag_dpm == 2)
			//{
				// check steel qty
			//}
			//else
			//{
				// check general qty
				//CheckDeviatedQty($fromdate,$abstsheetid,$subdivisionlist[$i],$Crbn);
			//}
			
			//echo $subdivisionlist[$i]." = ".$dpm_measurement_qty."<br/>";
		}
		else
		{
			$dpm_measurement_qty = 0;
			$dpm_cnt = 0;
		}
		
		if(in_array($subdivisionlist[$i],$DevItemArray))
		{
			//$dpm_measurement_qty = 10000;
			$dpm_measurement_qty = $WoQtyItemArr[$subdivisionlist[$i]];
			$dpm_cnt = 0;
		}
		
		
		
		
	//echo $dpm_mesurementbook_details."<br/>";	
	//echo "C = ".$dpm_measurement_qty."<br/>";	
	$subdivid = $subdivisionlist[$i];
	$subdivname = getsubdivname($subdivisionlist[$i]);
	$description1 = getscheduledescription_new($subdivisionlist[$i]);
					$snotes = $description1;
					$degcelsius = "&#8451";
					$description = str_replace("DEGCEL","$degcelsius",$snotes);
	//echo "D".$description;
	$slm_str = $subdivid."*@*".$subdivname."*@*".$divid."*@*".$description."*@*".$slm_measurement_qty."*@*".$mbookno_slm."*@*".$mbpageno_slm."*@*".$absmbookno_slm."*@*".$absmbpageno_slm."*@*".$flag_slm."*@*".$partpay_flag_slm."*@*".$staffid."*@*".$userid."*@*".$fromdate."*@*".$todate;
	$dpm_str = $subdivid."*@*".$subdivname."*@*".$divid."*@*".$description."*@*".$dpm_measurement_qty."*@*".$mbookno_dpm."*@*".$mbpageno_dpm."*@*".$absmbookno_dpm."*@*".$absmbpageno_dpm."*@*".$flag_dpm."*@*".$partpay_flag_dpm."*@*".$staffid."*@*".$userid."*@*".$fromdate."*@*".$todate;
	if($slm_cnt == 0)
	{
		$slm_str = "";
	}
	if($dpm_cnt == 0)
	{
		$dpm_str = "";
	}
	$item_str = $slm_str."@@@".$dpm_str;
	$slm_str = ""; $dpm_str = "";  $Linecheck = 3;// one row for item and desc, second for total cost row, third for new line row space between two item
	$checkbox_str = $subdivid."*".$subdivname."*".$description."*".$slm_measurement_qty."*".$dpm_measurement_qty."*".$rate."*".$unit."*".$abstsheetid;
	//--*************THIS PART IS FOR C/O , B/F and Page Break SECTION********************//
	if($slm_cnt == 1){ $Line = $Line + 2; $Linecheck = $Linecheck + 2; } else { $Line = $Line + $slm_cnt;  $Linecheck = $Linecheck + $slm_cnt;}
	if($dpm_cnt == 1){ $Line = $Line + 2; $Linecheck = $Linecheck + 2; } else { $Line = $Line + $dpm_cnt;  $Linecheck = $Linecheck + $dpm_cnt;}
	
	
	
	
	
	
	
	$LineTemp = $Line + $Linecheck;
	//echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>";
	if($LineTemp >= 30){ $Line = 30; $LineTemp = 0; }
	if($Line >= 30)
	{
	?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td><?php //echo $LineTemp; ?></td>
	</tr>
	<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
	</table>
	<p style='page-break-after:always;'></p>
	<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
		<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<?php echo $table; ?>
	<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
	<?php echo $tablehead; ?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td><?php //echo $LineIncr."*".$Linecheck; ?></td>
	</tr>
	<?php
	$Line = $LineIncr+$Linecheck; $page++;
	}
	//--*************THIS PART IS FOR " PRINT " Item Name, Description and Check Box  SECTION********************//
	?>
	<input type="hidden" name="hid_item_str" id="hid_item_str<?php echo $subdivid; ?>" value="<?php echo $item_str; ?>" />
	<tr border='1' bgcolor="" class="labelprint">
		<!--<td  align='center' width='' class='labelsmall' style=" border-top-color:#666666; border-bottom-color:#0A9CC5; background-color:#0A9CC5" id="td_popupbutton<?php echo $table_group_row; ?>">
			<input type="checkbox" name="check" id="ch_item<?php //echo $table_group_row; ?>" value="<?php //echo $checkbox_str; ?>"  />
		</td>-->
		<td width="61px" align="center" style="border-top-color:#666666;" class="">
			<?php echo $subdivname;?>
		</td>
		<td colspan="8" style="border-top-color:#666666;" class="">
			<?php echo $description; ?>
		</td>
		<td style="border-top-color:#666666;" width="40px"><?php //echo $slm_cnt."**".$dpm_cnt; ?>&nbsp;</td>
		<td style="border-top-color:#666666;" width="40px"><?php //echo $Line; ?>&nbsp;</td>
		<td style="border-top-color:#666666;" width="40px"><?php //echo $Line; ?>&nbsp;</td>
	</tr>
	<?php 
	$rowcount++; $Line++;//echo "A = ".$Line."<br/>";
	// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page);  $Line = $LineIncr; $page++; echo $slm_amount_item."<br/>"; }
	
	if(in_array($subdivisionlist[$i],$DevItemArray))
	{
		//$slm_measurement_qty = 0;
		//$slm_cnt = 0;
		$DpmAmount_1 = $dpm_measurement_qty * $rate;
		?>
		<tr border='1' bgcolor="#DF00FE" class="labelprint">
				<td  align='left' width='' class=''>&nbsp;</td>
				<td  align='left' width='' class='' style="font-size:10px;"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
				<td  align='right' width='' class=''><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
				<td  align='left' width='' class=''>&nbsp;</td>
				<td  align='left' width='' class=''>&nbsp;</td>
				<td  align='right' width='' class=''>&nbsp;</td>
				<td  align='left' width='' class=''>&nbsp;</td>
				<td  align='right' width='' class=''><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
				<td  align='right' width='' class=''><?php 
								echo number_format($DpmAmount_1, 2, '.', '');
								$dpm_amount_item 		= $dpm_amount_item + $DpmAmount_1;
								?></td>
				<td  align='right' width='' class=''></td>
				<td  align='right' width='' class=''></td>
				<td  align='center' width='' class='' style="font-size:9px;"></td>
			</tr>
			<?php
			$Line++;
	}
	else
	{
	
	
	
	
	
	//--*************THIS PART IS FOR " PRINT " DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*****************//
		$QtyDpmSlm_4 = 0;	$PercDpmSlm_4 = 0;	$Dpm_Slm_Amount_4 = 0;	$total_percent_dpm_slm_4 = 0;
		$QtyDpmSlm_3 = 0;	$PercDpmSlm_3 = 0;	$Dpm_Slm_Amount_3 = 0;	$total_percent_dpm_slm_3 = 0;
		$QtyDpmSlm_2 = 0;	$PercDpmSlm_2 = 0;	$Dpm_Slm_Amount_2 = 0;	$total_percent_dpm_slm_2 = 0;
		$QtyDpmSlm_1 = 0;	$PercDpmSlm_1 = 0;	$Dpm_Slm_Amount_1 = 0;	$total_percent_dpm_slm_1 = 0;
	
		if($dpm_cnt > 0)
		{
			$eplodedpm = explode("*", rtrim($dpm_mesurementbook_details,"*"));
			//echo "D = ".count($eplodedpm)."<br/>";
			 $DpmTemp = 0;
			for($x4=0; $x4<count($eplodedpm); $x4+=13)
			{
				$dpmqty 				= $eplodedpm[$x4+1];
				//echo $dpmqty."<br/>";
				$remarks 				= $eplodedpm[$x4+10];
				$rbnDpm					= $eplodedpm[$x4+11];
				$MeasurementbookidDpm	= $eplodedpm[$x4+12];
				$paymentpercent_dpm 	= $eplodedpm[$x4+7];
				$dpmamt 				= $dpmqty * $rate * $paymentpercent_dpm / 100;
				$dummy=0;
				if(in_array($MeasurementbookidDpm, $DpmArrMbidList)) 
				{
					$ArrUniqueVal 	= array_unique($DpmArrMbidList);
					$UniqueCount 	= count($ArrUniqueVal);
					$x6=0;
					$count_1 		= count($DpmArrAmbList);
					$count_2 		= count($DpmArrAmbPgList);
					$AMBookNo 		= $DpmArrAmbList[$count_1-1];
					$AMBookPage 	= $DpmArrAmbList[$count_2-1];
					while($x6<=$UniqueCount)
					{
						$StartKey = $ArrUniqueVal[$x6];
						$PaidDpmPerc = $DpmArrPercent[$StartKey];
						$rowspancnt = $UniqueCount+$DpmTemp;
						$DpmKeyresult = checkPartpayment($DpmArrMbidList,$StartKey);
						$DpmPercSum = $PaidDpmPerc;
						if($DpmKeyresult != "")
						{
							$explodeDpmKeyresult = explode("*",$DpmKeyresult);
							for($x7=0; $x7<count($explodeDpmKeyresult); $x7++)
							{
								$key = $explodeDpmKeyresult[$x7];
								$DpmPercSum = $DpmPercSum + $DpmArrPayPercentList[$key];
							}
							if(($x6 == 0)&&($DpmTemp == 0))
							{
							$DpmQuantityty_1 = $DpmArrQuantityList[$key];
							$DpmAmount_1 = $DpmQuantityty_1 * $rate * $DpmPercSum /100;
								if(in_array($StartKey, $SlmArrMbidList))
								{
									$Arrkey = array_search($StartKey, $SlmArrMbidList);
									$QtyDpmSlm_1 = $SlmArrQuantityList[$Arrkey];
									$PercDpmSlm_1 = $SlmArrPayPercentList[$Arrkey];
									$Dpm_Slm_Amount_1 = $QtyDpmSlm_1 * $PercDpmSlm_1 * $rate/100;
								}
							$total_percent_dpm_slm_1 = $DpmPercSum+$PercDpmSlm_1;
							
	?>
						<tr border='1' bgcolor="#FFFFFF" class="labelprint">
							<td  align='center' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
							<td  align='left' width='180px' class='' rowspan="<?php echo $rowspancnt; ?>" style="font-size:10px;"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
							<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
							<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
							<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
							<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
							<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"></td>
							<td  align='right' width='' class=''><?php echo $QtyDpmSlm_1; ?></td>
							<td  align='right' width='' class=''>
								<?php 
								echo number_format($DpmAmount_1, 2, '.', '');
								$dpm_amount_item 		= $dpm_amount_item + $DpmAmount_1;
								?>
							</td>
							<td  align='right' width='6%' class='' rowspan=""></td>
							<td  align='right' width='3%' class='' rowspan="">
								<?php
								if(in_array($StartKey, $SlmArrMbidList))
								{
									echo number_format($Dpm_Slm_Amount_1, 2, '.', ''); 
									$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_1;
								} 
								?>
							</td>
							<td  align='center' width='40px' class='' rowspan="" style="font-size:9px;">
								<?php 
								if(in_array($StartKey, $SlmArrMbidList))
								{
									echo $total_percent_dpm_slm_1."% Paid"; 
								}
								?>
							</td>
						</tr>
	
	<?php					$rowcount++;	
							}
							
							else
							{
								if(in_array($StartKey, $SlmArrMbidList))
								{
									$Arrkey = array_search($StartKey, $SlmArrMbidList);
									$QtyDpmSlm_2 = $SlmArrQuantityList[$Arrkey];
									$PercDpmSlm_2 = $SlmArrPayPercentList[$Arrkey];
									$Dpm_Slm_Amount_2 = $QtyDpmSlm_2 * $PercDpmSlm_2 * $rate/100;
									$total_percent_dpm_slm_2 = $DpmPercSum+$PercDpmSlm_2;
									
								}
								else
								{
									$QtyDpm_5 = $DpmArrQuantityList[$key];
									$Dpm_Slm_Amount_2 = $QtyDpm_5 * 100 * $rate/100;
								}
	?>
								<tr border='1' bgcolor="#FFFFFF" class="labelprint">
									<td  align='right' width='' class=''><?php echo $DpmArrQuantityList[$key]; ?></td>
									<td  align='right' width='' class=''><?php echo number_format($Dpm_Slm_Amount_2, 2, '.', ''); $dpm_amount_item = $dpm_amount_item + $Dpm_Slm_Amount_2; ?></td>
									<td  align='right' width='' class=''></td>
									<td  align='right' width='' class=''>
										<?php
										if(in_array($StartKey, $SlmArrMbidList))
										{
											echo number_format($Dpm_Slm_Amount_2, 2, '.', ''); 
											$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_2;
										} 
										?>
									</td>
									<td  align='center' width='40px' class='' rowspan="" style="font-size:9px;">
										<?php 
										if(in_array($StartKey, $SlmArrMbidList))
										{
											echo $total_percent_dpm_slm_2."% Paid"; 
										}
										?>
									</td>
								</tr>
			<?php				$rowcount++;		
							}
							
						}
						$DpmArrMbidList = removeArray($DpmKeyresult,$DpmArrMbidList);
						$x6++;	
						array_push($temp_array,$StartKey);
					}
					//$Line = $Line + $rowspancnt;//echo "B = ".$rowspancnt."<br/>";
					// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++; echo $slm_amount_item."<br/>";}
				}
								//********** THIS PART IS FOR NOW PAYING (SLM) - DEDUCT PREVIOUS MEASUREMENT **********//
				$PercDpmSlm_3 = 0; 	$Dpm_Slm_Amount_4 = 0;		
				if(in_array($MeasurementbookidDpm, $temp_array))
				{
					$dummy = 1;
				}
				else
				{
					if($x4 == 0)
					{ 
						if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
						{
							$Arrkey2 = array_search($MeasurementbookidDpm, $SlmArrMbidList);
							$QtyDpmSlm_3 = $SlmArrQuantityList[$Arrkey2];
							$PercDpmSlm_3 = $SlmArrPayPercentList[$Arrkey2];
							$Dpm_Slm_Amount_3 = $QtyDpmSlm_3 * $PercDpmSlm_3 * $rate /100;
						}
						$total_percent_dpm_slm_3 = $paymentpercent_dpm + $PercDpmSlm_3;
	?>
						<tr border='1' bgcolor="#FFFFFF" class="labelprint">
							<!--<td  align='left' width='3%' class=''>&nbsp;</td>-->
							<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
							<td  align='left' width='' class='' style="font-size:10px;" rowspan="<?php echo $dpm_cnt; ?>"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
							<td  align='right' width='' class='' rowspan="<?php echo $dpm_cnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
							<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
							<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
							<td  align='right' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
							<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
							<td  align='right' width='' class=''>
								<?php 
									echo number_format($dpmqty, $decimal, '.', ''); 
									
								?>
							</td>
							<td  align='right' width='' class=''>
								<?php 
									echo number_format($dpmamt, 2, '.', ''); 
									$dpm_amount_item 		= $dpm_amount_item + $dpmamt;
								?>
							</td>
							<td  align='right' width='' class='' rowspan="<?php if($dummy == 1) { echo $dpm_cnt; } ?>"></td>
							<td  align='right' width='' class='' rowspan="<?php if($dummy == 1) { echo $dpm_cnt; } ?>">
								<?php 
								if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
								{
									echo number_format($Dpm_Slm_Amount_3, 2, '.', '');
									$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_3;
								}
								?>
							</td>
							<td  align='center' width='' class='' rowspan="" style="font-size:9px;">
								<?php 
									echo $total_percent_dpm_slm_3."% Paid"; 
								?>
							</td>
						</tr>	
	<?php 			$rowcount++;
					}
					if(($dpm_cnt > 1) && ($x4 != 0))
					{
						if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
						{
							$Arrkey2 = array_search($MeasurementbookidDpm, $SlmArrMbidList);
							$QtyDpmSlm_4 = $SlmArrQuantityList[$Arrkey2];
							$PercDpmSlm_4 = $SlmArrPayPercentList[$Arrkey2];
							$Dpm_Slm_Amount_4 = $QtyDpmSlm_4 * $PercDpmSlm_4 * $rate /100;
						}
						$total_percent_dpm_slm_4 = $paymentpercent_dpm + $PercDpmSlm_4;
	?>
					<tr border='1' bgcolor="#FFFFFF" class="labelprint">
						<td  align='right' width='' class=''><?php echo number_format($dpmqty, $decimal, '.', ''); ?></td>
						<td  align='right' width='' class=''><?php echo number_format($dpmamt, 2, '.', ''); $dpm_amount_item  = $dpm_amount_item + $dpmamt; ?></td>
						<?php 
						if($dummy == 0) 
						{
						?>
							<td  align='right' width='' class=''></td>
							<td  align='right' width='' class=''>
								<?php 
									if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
									{
										echo number_format($Dpm_Slm_Amount_4, 2, '.', '');
										$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_4;
									}
								 ?>
							</td>
							<td  align='center' width='' class='' rowspan="" style="font-size:9px;">
								<?php
									//if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
									//{
										echo $total_percent_dpm_slm_4."% Paid";
									//}
								?>
							</td>
						<?php 
						} 
						?>
					</tr>
	<?php	
					$rowcount++;
					}
				}
				//$Line = $Line + $dpm_cnt;//echo "C = ".$dpm_cnt."<br/>";
				// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";}
				$DpmTemp++; 
			}
			//$rowcount++;
		}
		
		
	}	
		
		
		
		
		
		
		
		
	//*************THIS PART IS FOR " PRINT " ---- SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//
	?>
	<?php
	
	
	
		$slm_dpm_str = $slm_measurement_qty."*".$dpm_measurement_qty;
		$mbooktype_query = "select flag from mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
		$mbooktype_sql = mysqli_query($dbConn,$mbooktype_query);
		if(mysqli_num_rows($mbooktype_sql)>0)
		{
			$FlagList = mysqli_fetch_object($mbooktype_sql);
			$flagtype = $FlagList->flag;
			if($flagtype == 1) { $mbookdescription = "/MBook No. "; }
			if($flagtype == 2) { $mbookdescription = "/MBook No. "; }
		}
		else
		{
			$mbooktype_query1 = "select flag from measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
			$mbooktype_sql1 = mysqli_query($dbConn,$mbooktype_query1);
			//$flagtype = @mysqli_result($mbooktype_sql1,0,'flag');
			$FlagList = mysqli_fetch_object($mbooktype_sql);
			$flagtype = $FlagList->flag;
			if($flagtype == 1) { $mbookdescription = "/MBook No. "; }
			if($flagtype == 2) { $mbookdescription = "/MBook No. "; }
		}
	
		if($slm_cnt > 0)
		{
			$eplodeslm = explode("*", rtrim($slm_mesurementbook_details,"*"));
			//echo "B = ".count($eplodeslm)."<br/>";
			for($x3=0; $x3<count($eplodeslm); $x3+=12)
			{
				$slmqty = $eplodeslm[$x3+1];
				
				$remarks = $eplodeslm[$x3+10];
				$paymentpercent = $eplodeslm[$x3+7];
				$slmamt = $slmqty * $rate * $paymentpercent / 100;
				$slm_amount_item = $slm_amount_item + $slmamt;
				if($x3 == 0)
				{
	?>
			<tr border='1' bgcolor="#FFFFFF" class="labelprint">
				<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='left' width='' class='' style="font-size:10px;" rowspan="<?php echo $slm_cnt; ?>"><?php echo "Qty Vide P ".$mbpageno_slm.$mbookdescription.$mbookno_slm; ?></td>
				<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>"><?php echo number_format($slm_measurement_qty, $decimal, '.', ''); ?></td>
				<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
				<td  align='right' width='' class=''>
					<?php 
						echo number_format($slmqty, $decimal, '.', ''); 
					?>
				</td>
				<td  align='right' width='' class=''>
					<?php 
						echo number_format($slmamt, 2, '.', ''); 
					?>
				</td>
				<td  align='center' width='' class='' style="font-size:9px;">
					<?php 
					if($paymentpercent<100)
					{
						echo $paymentpercent."% Paid";
					} 
					?>
				</td>
			</tr>
	<?php
				}
				if(($slm_cnt > 1) && ($x3 != 0))
				{
				
	?>
			<tr border='1' bgcolor="#FFFFFF" class="labelprint">
				<td  align='right' width='' class=''><?php echo number_format($slmqty, $decimal, '.', ''); ?></td>
				<td  align='right' width='' class=''><?php echo number_format($slmamt, 2, '.', ''); ?></td>
				<td  align='center' width='' class='' style="font-size:9px;"><?php echo $paymentpercent."% Paid"; ?></td>
			</tr>
	<?php
				$rowcount++;
				}
			}
		$rowcount++; //$Line = $Line + $slm_cnt;//echo "C = ".$slm_cnt."<br/>";
		 //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";}
		}
		if($PartPayremarks != "")
		{
	?>
			<tr border='1' class="labelprint" style="font-size:10px;">
				<td colspan="12" align="left" bgcolor="">Remarks &nbsp; :&nbsp;&nbsp;&nbsp;  <?php echo $PartPayremarks; ?></td>
			</tr>
	<?php	
			$rowcount++; $Line++;//echo "E = ".$Line."<br/>";
			// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";}
		}	
	//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
	$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
	$total_amt_item = $slm_amount_item + $dpm_amount_item;
	
		if(in_array($subdivisionlist[$i],$DevItemArray))
		{
			//testing
		}
		else
		{
			$dev_qty_exceed = 0; 
			$CheckDevRes = CheckDeviatedQtyItem($abstsheetid,$subdivid,$total_qty_item);
			if($CheckDevRes != "")
			{
				$DevQtyflag = "";
				$expCheckDevRes = explode("*",$CheckDevRes);
				$wo_qty_with_dev 	= $expCheckDevRes[0];
				$dev_qty_exceed 	= $expCheckDevRes[1];
				$DevQtyflag 		= $expCheckDevRes[2];
				if($DevQtyflag == 1)
				{
					array_push($DevItemArray,$subdivid);
					$WoQtyItemArr[$subdivid] = $wo_qty_with_dev;
					
					
					if($DevPartPayStr != "")
					{
						$deviated_amt_tot_temp = 0;
						$dev_qty_exceed_temp = $dev_qty_exceed;
						$DevPartPayStr 		= rtrim($DevPartPayStr,"@");
						$expDevPartPayStr 	= explode("@",$DevPartPayStr);
						for($s2=0; $s2<count($expDevPartPayStr); $s2++)
						{
							$DevPartPayStr2 	= $expDevPartPayStr[$s2];
							$expDevPartPayStr2 	= explode("*",$DevPartPayStr2);
							$Ded_Dev_itemid 	= $expDevPartPayStr2[0];
							$Ded_Dev_qty 		= $expDevPartPayStr2[1];
							$Ded_Dev_percent 	= $expDevPartPayStr2[2];
							if($dev_qty_exceed_temp>0)
							{
								$deviated_amt_temp = round(($Ded_Dev_qty*$rate*$Ded_Dev_percent/100),2);
								$dev_qty_exceed_temp = $dev_qty_exceed_temp - $Ded_Dev_qty;
								$deviated_amt_tot_temp = $deviated_amt_tot_temp + $deviated_amt_temp;
							}
							//else
							//{
								//$deviated_amt_temp = round(($Ded_Dev_qty*$rate),2);
								//$deviated_amt_tot_temp = $deviated_amt_tot_temp + $deviated_amt_temp;
							//}
						}
					}
					//$deviated_amt = round(($dev_qty_exceed*$rate),2);
					$deviated_amt = round(($deviated_amt_tot_temp),2);
					
		?>
				<tr border='1' class="labelprint" bgcolor="#B3E4FF">
					<td  align='right' width='' class='labelbold' colspan="2">Deduct Deviated Qty.<?php //echo $deviated_amt_tot_temp; ?></td>
					<td  align='right' width='' class=''>-<?php echo number_format($dev_qty_exceed, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''><?php //echo $rate; ?></td>
					<td  align='left' width='' class=''><?php //echo $unit; ?></td>
					<td  align='right' width='' class=''><?php //echo number_format($deviated_amt, 2, '.', ''); ?></td>
					<td  align='right' width='' class=''>&nbsp;</td>
					<td  align='right' width='' class=''>&nbsp;</td>
					<td  align='right' width='' class=''>&nbsp;</td>
					<td  align='right' width='' class=''>-<?php echo number_format($dev_qty_exceed, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''>-<?php echo number_format($deviated_amt, 2, '.', ''); ?></td>
					<td  align='right' width='' class=''>&nbsp;</td>
				</tr>
				<?php
					$Line++;
					$DedResDpmQty = $dpm_measurement_qty;
					$DedResDpmAmt = $dpm_amount_item;
					$DedResSlmQty = round(($slm_measurement_qty-$dev_qty_exceed),$decimal);
					$DedResSlmAmt = round(($slm_amount_item-$deviated_amt),2);
					$DedResSlmDpmQty = round(($total_qty_item-$dev_qty_exceed),$decimal);
					$DedResSlmDpmAmt = round(($DedResDpmAmt+$DedResSlmAmt),2);
				?>
				<!--<tr border='1' class="labelprint" bgcolor="#B3E4FF">
					<td  align='right' width='' class='labelbold' colspan="2">Deduct - Result</td>
					<td  align='right' width='' class=''><?php echo number_format($DedResSlmDpmQty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''><?php echo $rate; ?></td>
					<td  align='left' width='' class=''><?php echo $unit; ?></td>
					<td  align='right' width='' class=''><?php echo number_format($DedResSlmDpmAmt, 2, '.', ''); ?></td>
					<td  align='right' width='' class=''>&nbsp;</td>
					<td  align='right' width='' class=''><?php echo number_format($DedResDpmQty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''><?php echo number_format($DedResDpmAmt, 2, '.', ''); ?></td>
					<td  align='right' width='' class=''><?php echo number_format($DedResSlmQty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''><?php echo number_format($DedResSlmAmt, 2, '.', ''); ?></td>
					<td  align='right' width='' class=''>&nbsp;</td>
				</tr>-->
		<?php	
					$Line++;
					
					$total_qty_item = $DedResSlmDpmQty; $total_amt_item = $DedResSlmDpmAmt; $dpm_measurement_qty = $DedResDpmQty;
					$dpm_amount_item = $DedResDpmAmt; $slm_measurement_qty = $DedResSlmQty; $slm_amount_item = $DedResSlmAmt;
				}
			}
		}
			//$total_qty_item = round(($total_qty_item-$dev_qty_exceed),$decimal);
	?>
		<tr border='1' class="labelprint" bgcolor="">
			<!--<td  align='left' width='3%' class=' label' style="border-bottom-color:#666666">&nbsp;</td>-->
			<td  align='left' width='' class=''>&nbsp;<?php //echo $Line; ?></td>
			<td  align='right' width='' class='labelbold'>TOTAL</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($total_qty_item, $decimal, '.', ''); ?>
			</td>
			<td  align='right' width='' class=''>
			<?php echo $rate; ?>
			</td>
			<td  align='left' width='' class=''>
			<?php echo $unit; ?>
			</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($total_amt_item, 2, '.', ''); ?>
			</td>
			<td  align='left' width='' class=''>&nbsp;</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?>
			</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($dpm_amount_item, 2, '.', ''); ?>
			</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($slm_measurement_qty, $decimal, '.', ''); ?>
			</td>
			<td  align='right' width='' class=''>
			<?php echo number_format($slm_amount_item, 2, '.', ''); ?>
			</td>
			<td  align='right' width='' class=''><?php //echo $Line; ?>&nbsp;</td>
		</tr>
		<?php 
			UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page); 
			$rowcount++; $Line++;
			
		?>
			<!--<tr style="background-color:#A0EAFA"><td colspan="12"><?php echo $subdivname."*".$CheckDevRes."<br/>"; ?></td></tr>-->
		<?php
			/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
		<tr bgcolor=""><td colspan="12">&nbsp;</td></tr>
		<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
		<!--<tr bgcolor="#d4d8d8" style="height:10px"><td colspan="13" style="border-top-color:#0A9CC5; border-bottom-color:#0A9CC5;"></td></tr>-->
		<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
		<?php //echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>"; ?>
		<?php
		$color_var++; $table_group_row++;
		$AbstractStr			.= $divid."*".$subdivid."*".$fromdate."*".$todate."*".$runn_acc_bill_no."*".$abstsheetid."*".$abstmbno."*".$page."*";
		$OverAllSlmAmount 		=  $OverAllSlmAmount	+	$slm_amount_item; 
		$OverAllDpmAmount 		=  $OverAllDpmAmount	+	$dpm_amount_item; 
		$OverAllSlmDpmAmount 	=  $OverAllSlmDpmAmount	+	$total_amt_item;
		//print_r($DevItemArray); echo "<br/>";
	}
	//echo $Line;	
		$SlmRebateAmount 		=  $OverAllSlmAmount 	* 	$overall_rebate_perc /100;
		$DpmRebateAmount 		=  $OverAllDpmAmount 	* 	$overall_rebate_perc /100;
		$SlmDpmRebateAmount 	=  $OverAllSlmDpmAmount * 	$overall_rebate_perc /100;
		
		$SlmNetAmount 			=  round($OverAllSlmAmount	-	$SlmRebateAmount); 
		$DpmNetAmount 			=  round($OverAllDpmAmount	-	$DpmRebateAmount); 
		$SlmDpmNetAmount 		=  round($OverAllSlmDpmAmount	-	$SlmDpmRebateAmount);
		
		$final_amount_str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$Crbn."@@";
		
	$Linecheck = 3;
	$LineTemp = $Line + $Linecheck;
	if($LineTemp >= 30){ $Line = 30; } 
	if($Line >= 30)
	{
	?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td><?php //echo $Line; ?></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td><?php //echo $LineTemp; ?></td>
	</tr>
	<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
	</table>
	<p style='page-break-after:always;'></p>
	<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
		<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<?php echo $table; ?>
	<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
	<?php echo $tablehead; ?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td></td>
	</tr>
	<?php
	$Line = $LineIncr; $page++;
	}
	?>
	
		<tr class="labelprint" bgcolor="#F0F0F0">
			<td colspan="2" align="right">Total Cost&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php $Line++; //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
		<tr class="labelprint">
			<td colspan="2" align="right">Less: Over All Rebate : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SlmDpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SlmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php $Line++; //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
		<tr class="labelbold" bgcolor="#F0F0F0">
			<td colspan="2" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format(round($SlmDpmNetAmount), 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DpmNetAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format(round($SlmNetAmount), 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php 
	
	
	//$Line++; //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} 
	/*if($Line >= 30)
	{
	?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td></td>
	</tr>
	<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
	</table>
	<p style='page-break-after:always;'></p>
	<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
		<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
	</table>
	<?php echo $table; ?>
	<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
	<?php echo $tablehead; ?>
	<tr>
		<td colspan='3' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?></td>
		<td></td>
		<td align='right' class='labelbold'><?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?></td>
		<td></td>
	</tr>
	<?php
	$Line = $LineIncr; $page++;
	}
	else
	{
	?>
	<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>
	Page <?php echo $page; ?></td></tr>
	<?php	
	}*/
	?>
	<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>
	Page <?php echo $page; ?></td></tr>
	</table>
	<?php 
	echo "<p style='page-break-after:always;'></p>";
	$Line = $LineIncr; 
	$page++;
}
//echo ;
$end_page = $page;

/*if($final_amount_str != "")
{
	$final_amount_str = rtrim($final_amount_str,"@@");
	$exp_final_amount_str = explode("@@",$final_amount_str);
	for($s1=0; $s1<count($exp_final_amount_str); $s1++)
	{
		$curr_str 			= $exp_final_amount_str[$s1];
		if($curr_str != "")
		{
			$exp_curr_str 		= explode("*",$curr_str);
			$esc_slm_amt 		= $exp_curr_str[0];
			$esc_dpm_amt 		= $exp_curr_str[1];
			$esc_upto_date_amt 	= $exp_curr_str[2];
			$esc_rbn 			= $exp_curr_str[3];
			//echo $esc_rbn."<br/>";
			$update_abstarct_query = "update abstractbook set upto_date_total_amount_esc = '$esc_upto_date_amt', dpm_total_amount_esc = '$esc_dpm_amt', 
									slm_total_amount_esc = '$esc_slm_amt' where sheetid = '$abstsheetid' and rbn = '$esc_rbn'";
			$update_abstarct_sql = mysqli_query($dbConn,$update_abstarct_query);
		}
	}
	
		$delete_mbook_query		= 	"delete from mymbook where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and 
									quarter = '$quarter' and mtype = 'EA' and genlevel = 'esc_abstract'";
		$delete_mbook_sql 		= 	mysqli_query($dbConn,$delete_mbook_query);						
		
		$insert_mbook_query  	= 	"insert into mymbook set mbno = '$abstmbno', startpage = '$start_page', endpage = '$end_page', sheetid = '$sheetid', 
									quarter = '$quarter', staffid = '$staffid', rbn = '$esc_abs_rbn', esc_id = '$esc_id', active =1, mtype = 'EA', genlevel = 'esc_abstract', mbookorder = 1";
		$insert_mbook_sql = mysqli_query($dbConn,$insert_mbook_query);
	
}*/
/*$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();
$esc_cnt = 0;
$Esc_Total_Amt = 0;
$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysqli_query($dbConn,$select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysqli_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($EscList = mysqli_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt = $EscList->esc_total_amt;
			
			$Esc_Total_Amt = $Esc_Total_Amt+$esc_tcc_amount+$esc_tca_amount;
			
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_tcc_amount);
			array_push($EscTcaAmtArray,$esc_tca_amount);
		}
	}
}
$Esc_Total_Amt = round($Esc_Total_Amt);
//print_r($EscQtrArray);print_r($EscAmtArray);
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysqli_query($dbConn,$secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysqli_fetch_object($secured_advance_sql);
	$sec_adv_amount	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount = 0;
}

$total_recovery = 0;
$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysqli_query($dbConn,$water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysqli_fetch_object($water_recovery_sql))
	{
		$water_charge 	=  $water_charge+$WRList->water_cost; 
	}
}
else
{
	$water_charge = 0;
}
$total_recovery = $total_recovery + $water_charge;
$electricity_recovery_query = "select electricity_cost from generate_electricitybill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$electricity_recovery_sql = mysqli_query($dbConn,$electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysqli_fetch_object($electricity_recovery_sql))
	{
		$electricity_charge  = 	$electricity_charge+$ERList->electricity_cost;
	}
}
else
{
	$electricity_charge = 0;
}
$total_recovery = $total_recovery + $electricity_charge;
$general_recovery_query = "select * from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
//echo $general_recovery_query;
$general_recovery_sql = mysqli_query($dbConn,$general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 			= 	mysqli_fetch_object($general_recovery_sql);
	$sd_amt 			= 	round($GRList->sd_amt);
	$sd_percent 		= 	$GRList->sd_percent;
	$wct_amt 			= 	round($GRList->wct_amt);
	$wct_percent 		= 	$GRList->wct_percent;
	$vat_amt 			= 	round($GRList->vat_amt);
	$vat_percent 		= 	$GRList->vat_percent;
	$mob_adv_amt 		= 	round($GRList->mob_adv_amt);
	$mob_adv_percent 	= 	$GRList->mob_adv_percent;
	$lw_cess_amt 		= 	round($GRList->lw_cess_amt);
	$lw_cess_percent 	= 	$GRList->lw_cess_percent;
	$incometax_amt 		= 	round($GRList->incometax_amt);
	$incometax_percent 	= 	$GRList->incometax_percent;
	$it_cess_amt 		= 	round($GRList->it_cess_amt);
	$it_cess_percent 	= 	$GRList->it_cess_percent;
	$it_edu_amt 		= 	round($GRList->it_edu_amt);
	$it_edu_percent 	= 	$GRList->it_edu_percent;
	$land_rent 			= 	round($GRList->land_rent);
	$liquid_damage 		= 	round($GRList->liquid_damage);
	$other_recovery_1 	= 	round($GRList->other_recovery_1_amt);
	$other_recovery_2	= 	round($GRList->other_recovery_2_amt);
	$other_recovery_1_desc 	= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc	= 	$GRList->other_recovery_2_desc;
	if($other_recovery_1_desc == "")
	{
		$other_recovery_1_desc = "Other Recovery 1 ";
	}
	if($other_recovery_2_desc == "")
	{
		$other_recovery_2_desc = "Other Recovery 2 ";
	}
	$non_dep_machine_equip 	= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa 	= 	round($GRList->nonsubmission_qa);
}
if($non_dep_machine_equip != 0)
{
	$non_dep_machine_equip_print = number_format($non_dep_machine_equip, 2, '.', '');
}
else
{
	$non_dep_machine_equip_print = "NIL";
}

if($non_dep_man_power != 0)
{
	$non_dep_man_power_print = number_format($non_dep_man_power, 2, '.', '');
}
else
{
	$non_dep_man_power_print = "NIL";
}

if($electricity_charge != 0)
{
	$electricity_charge_print = number_format($electricity_charge, 2, '.', '');
}
else
{
	$electricity_charge_print = "NIL";
}

if($water_charge != 0)
{
	$water_charge_print = number_format($water_charge, 2, '.', '');
}
else
{
	$water_charge_print = "NIL";
}
$total_recovery = $total_recovery + $sd_amt+$wct_amt + $vat_amt+$mob_adv_amt + $lw_cess_amt+$incometax_amt + $it_cess_amt+$it_edu_amt + $land_rent+$liquid_damage + $other_recovery_1 + $other_recovery_2 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;
$rrcount = 0;  $total_rec_rel_amt = 0;
$RRDescCivArr = array(); $RRAmtCivArr = array(); $RRDescAccArr = array(); $RRAmtAccArr = array();

$recov_release_query = "select * from recovery_release where sheetid = '$abstsheetid' and rbn = '$rbn'";
$recov_release_sql = mysqli_query($dbConn,$recov_release_query);
//echo $recov_release_query;
if($recov_release_sql == true)
{
	if(mysqli_num_rows($recov_release_sql)>0)
	{
		while($RecRelList = mysqli_fetch_object($recov_release_sql))
		{
			$rec_rel_desc_civil = $RecRelList->description_civil;
			$rec_rel_amt_civil 	= $RecRelList->amount_civil;
			$rec_rel_desc_acc 	= $RecRelList->description_acc;
			$rec_rel_amt_acc 	= $RecRelList->amount_acc;
			array_push($RRDescCivArr,$rec_rel_desc_civil);
			array_push($RRAmtCivArr,$rec_rel_amt_civil);
			array_push($RRDescAccArr,$rec_rel_desc_acc);
			array_push($RRAmtAccArr,$rec_rel_amt_acc);
			$total_rec_rel_amt  = $total_rec_rel_amt+$rec_rel_amt_civil;
			$rrcount++;
		}
	}
}

$page++;
$OverAllSlmDpmAmount = round($OverAllSlmDpmAmount);
$OverAllSlmAmount = round($OverAllSlmAmount);
//echo "<p style='page-break-after:always;'></p>";
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
//echo "<tr><td class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
if(count($EscQtrArray)>0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CC Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round($OverAllSlmAmount+$Esc_Total_Amt);

//echo '<hr style="border-top: dotted 1px;" />';
//$OverAllSlmAmount = $OverAllSlmAmount + $sec_adv_amount;
$Overall_net_amt_final = round(($OverAllSlmAmount + $sec_adv_amount + $total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = round($Overall_net_amt_final);

echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='6'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none; border-top:1px dashed #000000' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none; border-top:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='2' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";
$ea = 1; $eb = 1; $ed = 1; 
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";  $ed_text = "<b><u>With hold Amount</u></b>";
if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";


}
if($lw_cess_percent != 0)
{


echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";




}
if($mob_adv_percent != 0)
{
//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
//if($water_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
//if($electricity_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";


if($sd_amt != 0)
{
$eb = 1;
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ec_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Security Deposit @ 5% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

// This row is for Recovery Release
if($rrcount>0)
{
	for($rrc=0; $rrc<$rrcount; $rrc++)
	{
	echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ed_text." (".$ed.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$RRDescCivArr[$rrc]." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($RRAmtCivArr[$rrc], 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
	$ed++; $ed_text = "";
	}
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
}*/

//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//$Overall_net_amt_final = "18767031.35";
/*$split_amt = explode(".",$Overall_net_amt_final);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];
$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
echo "<tr style='border:none'><td style='border:none'>&nbsp;</td><td style='border:none'>&nbsp;</td><td style='border:none' class='labelprint' align='left' colspan='12'>Amount: (Rupees ".$amount_in_words.")</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>page ".$page."</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";*/
?>
<?php 

//echo "<p  style='page-break-after:always;'></p>";
/*for($x=0;$x<$emptypage;$x++)
{
$page++;
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
$y=1;
while($y<22)
{
?>
	<tr>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='20%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='8%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='4%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='7%' class='labelsmall'>&nbsp;</td>
	<td  align='right' width='10%' class='labelsmall'>&nbsp;</td>
	<td  align='left' width='5%' class='labelsmall'>&nbsp;</td>
</tr>
	<?php
	$y++;		
}
echo "<tr class='labelprint'><td colspan='12' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'> </td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}*/
$staffid_acc 		= $_SESSION['sid_acc'];
$staff_level_str 	= getstafflevel($staffid_acc);
$exp_staff_level_str = explode("@#*#@",$staff_level_str);
$staff_roleid 		= $exp_staff_level_str[0];
$staff_levelid 		= $exp_staff_level_str[1];
$AccVerification 	= AccVerificationCheck($abstsheetid,$esc_abs_rbn,$abstmbno,'staff',$staff_levelid,'MB');
$AlStatusRes 		= AccountsLevelStatus($abstsheetid,$esc_abs_rbn,$abstmbno,0,'EA','esc_abstract');//($sheetid,$rbn);
$AcLevel 	= $AlStatusRes[0];
$AcStatus 	= $AlStatusRes[1];
$EndLevel 	= $AlStatusRes[2];
?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo $page; ?>" />
<input type="hidden" name="txt_abstractstr" id="txt_abstractstr" value="<?php echo $AbstractStr; ?>" />
<input type="hidden" name="txt_subdivid_slmstr" id="txt_subdivid_slmstr" value="<?php echo $SubdividSlmStr; ?>" />
<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $runn_acc_bill_no; ?>" />

<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />
<!--<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect">
		<input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> 
	</div>
	<div class="btn_inside_sect">
		<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
	</div>
	<div class="btn_inside_sect">
		<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
	</div>
</div> -->

<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $abstsheetid; ?>"/>
<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="<?php echo $zone_id; ?>"/>
<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $esc_abs_rbn; ?>"/>
<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $abstmbno; ?>"/>
<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>
<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>"/>

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="btn_inside_sect"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/> </div>
			<!--<div class="buttonsection">
			<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
			</div>-->
			<?php 
				$TranRes = AccountsLevelTransaction($abstsheetid,$esc_abs_rbn,$_SESSION['levelid']);
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

		<!-- modal content -->
		<!--<div id="basic-modal-content">
			<div align="center" class="popuptitle">Part Payment Work Sheet</div>
			<div align="center" style="padding-top:10px;">
			<table class="label table2" width="100%" cellpadding="3" cellspacing="3" id="table2">
				<tr bgcolor="">
					<td width="60px" align="left">Item No.</td>
					<td width="">
						<input type="text" readonly="" name="txt_item_no" id="txt_item_no" size="8" class="popuptextbox" />
						<input type="hidden" name="txt_item_id" id="txt_item_id" size="8" class="popuptextbox" />
					</td>
					<td width="60px" align="center">RAB No.</td>
					<td width="">
						<input type="text" name="txt_rab_no" id="txt_rab_no" size="6" class="popuptextbox" value="<?php echo $rbn; ?>" />
					</td>
					<td  align="left" colspan="4">Measurement Date - From &nbsp; :
						<input type="text" name="txt_from_date" id="txt_from_date" size="12" class="popuptextbox" value="<?php echo dt_display($fromdate); ?>" />
					To :
						<input type="text" name="txt_to_date" id="txt_to_date" size="12" class="popuptextbox" value="<?php echo dt_display($todate); ?>" />
					</td>
				</tr>
				<tr bgcolor="">
				<td width="135px" align="left">Item Description</td>
					<td width="700px" align="left" colspan="7">
						<textarea name="txt_item_desc" id="txt_item_desc" class="popuptextbox" rows="2" style="text-align:left; width:820px; height:34px;"></textarea>
					</td>
				</tr>

			</table>
			</div>
			<div style="padding-top:10px; height:325px;">
				<div style="float:left; width:567px; height:320px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="94%" id="table3">
					<tr bgcolor="#0080ff" style="color:#FFFFFF">
						<td align="center" colspan="7">Deduct Previous Measurement</td>
					</tr>
					<tr>
						<td align="left" colspan="7" bgcolor="#f2efef">
						Deduct Previous Measurement Total Quantity&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
						<input type="text" name="txt_dpm_qty" id="txt_dpm_qty" size="17" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
						</td>
					</tr>
					<tr>
						<td width="10px" rowspan="2" align="center">RBN.</td>
						<td width="61px" rowspan="2" align="center">Item Qty.</td>
						<td width="63px" rowspan="2" align="center">Rate&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Paid Details</td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Payable Details</td>
					</tr>
					<tr>
						<td width="23px" align="center">(%)</td>
						<td width="110px" align="center">Amount&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td width="23px" align="center">(%)</td>
						<td style='width:110px' align="center">Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
					</tr>
					<tr>
						<td colspan="4" align="right">Total Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
						<td align="left"><input type="text" name="txt_partpay_total_paidamt_dpm" id="txt_partpay_total_paidamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
						<td colspan=""></td>
						<td colspan=""><input type="text" name="txt_partpay_total_payableamt_dpm" id="txt_partpay_total_payableamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
					</tr>
					<tr>
						<td colspan="7">Remarks:<br/><textarea name="txt_dpm_remarks" id="txt_dpm_remarks" rows="3" style="width:519px;"></textarea>
						</td>
					</tr>
				</table>
				</div>
				<div style="float:right;  width:427px; height:320px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="93%" id="table4">
						<tr bgcolor="#0080ff" style="color:#FFFFFF">
							<td align="center" colspan="5">Since Last Measurement</td>
						</tr>
						<tr>
							<td align="left" colspan="5" bgcolor="#f2efef">
							Since Last Measurement Quantity&nbsp;:&nbsp;
							<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							<input type="hidden" name="hid_slm_qty" id="hid_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							</td>
						</tr>
						<tr>
							<td width="61px" align="center">Item Qty.</td>
							<td width="63px" align="center">Rate&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i></td>
							<td width="23px" align="center">(%)</td>
							<td width="50px" align="center">Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i></td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr id='rowid0'>
							<td width="61px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_qty_slm[]" id="txt_partpay_qty_slm0" class="dynamictextbox" style="text-align:right; width:93px; border: 1px solid #2aade4;" onblur="ValidateSlm(); calculateAmount(this,0,'qty','slm');" />
							</td>
							<td width="63px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_item_rate_slm" readonly="" id="txt_item_rate_slm0" class="dynamictextbox" style="text-align:right; width:80px;" onblur="calculateAmount(this,0,'rate','slm');" />
							</td>
							<td width="23px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_percent_slm" id="txt_partpay_percent_slm0" class="dynamictextbox" style="text-align:right; width:40px; border: 1px solid #2aade4;" onblur="ValidatePercent(this,'slm',0); calculateAmount(this,0,'percent','slm');" />
							</td>
							<td width="50px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_amt_slm[]" id="txt_partpay_amt_slm0" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center" class="dynamicrowcell" style="text-align:center;">
							<input type="button" name="btn_add_row_slm" id="btn_add_row_slm" class="editbtnstyle" value=" + " style="width:32px; text-align:center; font-weight:bold; border-radius: 0px;" onclick="addRow();" />
							<input type="hidden" name="hid_slm_result[]" id="hid_slm_result0" class="dynamictextbox" />
							</td>
						</tr>
						<tr>
							<td width="147px" colspan="3" align="right">Total Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
							<td width="50px" align="right"  class="dynamicrowcell">
							<input type="text" name="txt_partpay_total_amt_slm" id="txt_partpay_total_amt_slm" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="5">Remarks:<br/><textarea name="txt_slm_remarks" id="txt_slm_remarks" rows="3" style="width:375px;"></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div align="right">
				<table width="100%" height="65" class="label" cellpadding="3" cellspacing="3">
					<tr>
					<td align="right" width="440px">
					<label style="background:#EAEAEA; padding:6px;">Over All Total Amount</label>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;
					<input type="text" name="txt_overall_total" id="txt_overall_total" size="20" class="dynamictextbox dynamictextbox2" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					</tr>
				</table>
			</div>
			<div class="bottomsection" align="center">
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="buttonstyle" onclick="SaveData()" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="buttonstyle" onclick="CancelData()" /></div>
			</div>
		</div>
		
		<!-- preload the images -->
		<!--<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>     -->
</form>
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
</html>