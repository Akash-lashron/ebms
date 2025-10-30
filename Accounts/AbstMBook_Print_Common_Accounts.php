<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
checkUser();
include "library/common.php";
include "spellnumber.php";
$msg = ''; $Line = 0;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
$staffid_acc 			= $_SESSION['sid_acc'];
$acc_levelid 			= $_SESSION['levelid'];
$staff_level_str 		= getstafflevel($staffid_acc);
$exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
$staff_roleid 			= $exp_staff_level_str[0];
$staff_levelid 			= $exp_staff_level_str[1];
	 
$minmax_level_str 		= getstaff_minmax_level();
$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
$min_levelid 			= $exp_minmax_level_str[0];
$max_levelid 			= $exp_minmax_level_str[1];


function checkPartpayment($DpmArrMbidList,$Key)
{
	$InitKey = $Key;
	while($perc = current($DpmArrMbidList)) 
	{
		if ($perc == $InitKey) 
		{
			//echo key($DpmArrPayPercentList).'<br />';
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
	$update_pageno_query = mysqli_query($dbConn,$update_pageno_sql);
}
$staffid_acco	= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
$abstsheetid    = 	$_GET['workno'];
$_SESSION["abstsheetid"] = 	$_GET['workno'];
$abstsheetid    = 	$_SESSION["abstsheetid"];
$linkid = $_GET['linkid'];
//$rbn    		= 	$_SESSION["rbn"]; 
//$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
//$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$selectmbook_detail = " select DISTINCT fromdate, todate, rbn, abstmbookno, staffid FROM mbookgenerate WHERE sheetid = '$abstsheetid'";
//echo $selectmbook_detail;
$selectmbook_detail_sql = mysqli_query($dbConn,$selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysqli_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; 
	//$abstmbno = $Listmbdetail->abstmbookno;
	$staffid 			= $Listmbdetail->staffid;
	//$abstmbpage_query 	= 	"select mbpage, allotmentid from mbookallotment WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstmbno'";
	//$abstmbpage_sql 	= 	mysqli_query($dbConn,$abstmbpage_query);
	//$Listmbook 			= 	mysqli_fetch_object($abstmbpage_sql);
	//$abstmbpage 		= 	$Listmbook->mbpage+1; $abs_mbno_id = $Listmbook->allotmentid;
}
//echo $abstmbpage_query;
$paymentpercent = 	$_SESSION["paymentpercent"];	$emptypage 	= $_SESSION['emptypage'];

if($emptypage == "")
{
	$emptypage = 0;
}
$empty_page_update_sql = "update mymbook set emptypage = '$emptypage' where sheetid = '$abstsheetid' and mbno = '$abstmbno' and  mtype = 'A' and rbn = '$rbn' and genlevel = 'abstract'";
$empty_page_update_query = mysqli_query($dbConn,$empty_page_update_sql);

$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$rbn' and mtype = 'A' and genlevel = 'abstract' order by mbookorder asc";
$SelectMBookSql = mysqli_query($dbConn,$SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysqli_num_rows($SelectMBookSql)>0){
		while($MBList = mysqli_fetch_object($SelectMBookSql)){
			if($MBList->mbookorder == 1){ 
				$abstmbno = $MBList->mbno; //echo "1 = ".$abstmbno."<br/>";
				$abstmbpage = $MBList->startpage;
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
/*if($_POST["Submit"] == "Confirm")
{	
	
	
	$AbstractStr 			= 	$_POST['txt_abstractstr'];
	$SubdividSlmStr 		= 	$_POST['txt_subdivid_slmstr'];
	$runningbillno 			= 	$_POST['txt_rbn_no'];
	
	$select_mymbook_sql = "SELECT MAX(endpage) as maxpage, mbookorder, mbno FROM mymbook WHERE sheetid = '$abstsheetid' and rbn = '$runningbillno' GROUP BY mbno ORDER BY mbookorder ASC";
	$select_mymbook_query = mysql_query($select_mymbook_sql);
	//echo $select_mymbook_sql."<br/>";
	if(mysql_num_rows($select_mymbook_query)>0)
	{
		while($MBKList = mysql_fetch_object($select_mymbook_query))
		{
			$maxpage 	= $MBKList->maxpage;
			$mbook 		= $MBKList->mbno;
			if($maxpage == 100)
			{
				$update_mbookpage_sql_2 = "update agreementmbookallotment set active = 0 WHERE sheetid = '$abstsheetid' and mbno = '$mbook'";
				$update_mbookpage_query_2 = mysql_query($update_mbookpage_sql_2);
				
				$update_mbookpage_sql = "update mbookallotment set mbpage = '$maxpage', active = 0 WHERE sheetid = '$abstsheetid' and mbno = '$mbook'";
			}
			else
			{
				$update_mbookpage_sql = "update mbookallotment set mbpage = '$maxpage' WHERE sheetid = '$abstsheetid' and mbno = '$mbook'";
				//echo $update_mbookpage_sql."<br/>";
			}
			$update_mbookpage_query = mysql_query($update_mbookpage_sql);
		}
	}
	//exit;
	//echo $select_mymbook_sql;exit;
	if($SubdividSlmStr != "")
	{
		$explodeSubdividSlmStr	=	explode("*",rtrim($SubdividSlmStr,"*"));
		$explodeAbstractStr		=	explode("*",rtrim($AbstractStr,"*"));
		for($x7=0; $x7<count($explodeAbstractStr); $x7+=8)
		{
			$Divid_dmy			=	$explodeAbstractStr[$x7+0];
			$Subdivid_dmy		=	$explodeAbstractStr[$x7+1];
			$FromDate_dmy		=	$explodeAbstractStr[$x7+2];
			$ToDate_dmy			=	$explodeAbstractStr[$x7+3];
			$RbnNo_dmy			=	$explodeAbstractStr[$x7+4];
			$Sheetid_dmy		=	$explodeAbstractStr[$x7+5];
			$AMbookNo_dmy		=	$explodeAbstractStr[$x7+6];
			$AMbookPage_dmy		=	$explodeAbstractStr[$x7+7];
			$partpay_flag_dmy	=	"DMY";
			if(!in_array($Subdivid_dmy, $explodeSubdividSlmStr))
			{
				$insert_mbook_dummy_sql 	= 	"insert into 
												measurementbook (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, abstmbookno, abstmbpage,  part_pay_flag, rbn, active, userid) 
												values (NOW(), '$staffid', '$Sheetid_dmy', '$Divid_dmy', '$Subdivid_dmy', '$FromDate_dmy', '$ToDate_dmy', '$AMbookNo_dmy', '$AMbookPage_dmy', '$partpay_flag_dmy', '$RbnNo_dmy', '1', '$userid')";
				$insert_mbook_dummy_query 	= 	mysql_query($insert_mbook_dummy_sql);
				//echo $insert_mbook_dummy_sql."<br/>";
			}
		}
	}

	$max_page_abs 			= 	$_POST['txt_maxpage'];
	$abstmbno 				= 	$_POST['txt_abstmbno'];

    $currentquantity 			= 	trim($_POST['currentquantity']);
	$mbookquery					=	"INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks) SELECT  now(), staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks FROM measurementbook_temp WHERE sheetid = '$abstsheetid'";// WHERE flag =1 OR flag = 2";
   	$mbooksql 					= 	mysql_query($mbookquery);   
    $sheetquery 				= 	"UPDATE sheet SET rbn = '$runningbillno' WHERE sheet_id ='$abstsheetid'";//AND STAFFID
    $sheetsql 					= 	dbQuery($sheetquery);
	
	
	$newmbooksql 				= 	"DELETE FROM oldmbook WHERE sheetid = '$abstsheetid'";// DELETE NEW MBOOK TABLE
	$result1 					= 	dbQuery($newmbooksql);
	$mbookgeneratedelsql		= 	"DELETE FROM mbookgenerate WHERE sheetid ='$abstsheetid'"; //DELETE MBOOK GENERATE TABLE
    $result2 					= 	dbQuery($mbookgeneratedelsql);
	$mbooktempdelsql 			= 	"DELETE FROM measurementbook_temp WHERE sheetid ='$abstsheetid'"; //DELETE MBOOK TEMP TABLE
    $result3 					= 	dbQuery($mbooktempdelsql);
	if($is_finalbill == "Y")
	{
		$deactivate_sheet_query = 	"update sheet set active = '0' WHERE sheet_id = '$abstsheetid'";
		$deactivate_sheet_sql 	= 	mysql_query($deactivate_sheet_query);
	}
	header('Location: AbsGenerate_Partpay.php');
}*/

if($_POST["send_to_civil"] == " Return to EIC ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
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
	 
	 $Status = AccountsLevelAction($sc_sheetid,$sc_rbnno,$_SESSION['levelid'],"BW");
	 
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 
	 /*$update_query 	= "update acc_log set AC_status = 'R', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END  
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";*/
	 
	 $update_query 	= "update acc_log set status = 'SC', AC_status = 'R', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '$Status',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 $update_alas_query = "update al_as set ret_status = 'Y' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 $update_alas_sql 	= mysqli_query($dbConn,$update_alas_query);
	 
	 $update_query = "update send_accounts_and_civil set ab_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql = mysqli_query($dbConn,$update_query);
	 
	 //$update_query = "update acc_log set AC_status = '', levelid = '$Status' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 //$update_sql = mysql_query($update_query);
	/*if($Status == "C"){
		 $update_query = "update send_accounts_and_civil set ab_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and ab_ac != ''";
		 $update_sql = mysql_query($update_query);
	 
		 $update_query = "update send_accounts_and_civil set sa_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and sa_ac != ''";
		 $update_sql = mysql_query($update_query);

		 $update_query = "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mb_ac != ''";
		 $update_sql = mysql_query($update_query);

	 
	 }else*/ 
	 if(($Status != "C")&&($Status != "")){
		 $update_query = "update send_accounts_and_civil set ab_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and ab_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);
	
		 $update_query = "update send_accounts_and_civil set sa_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and sa_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);

		 $update_query = "update send_accounts_and_civil set mb_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mb_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);
	 }	 
	 
	 if($update_sql == true)
	 {
		$msg = "Abstract Returned to Civil Section";
		$success = 1;
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Abstract - ".$sc_mbook_no." rejected to Civil in ".$RABTranFWRoleName." Level";
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
						zone_id = '$sc_zone_id', mtype = 'A', genlevel = 'abstract', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysql_query($linsert_log_query);*/
	 
}

if($_POST["accept"] == " Accept Abstract ")
{
	//echo "hai";exit;$_SESSION['levelid']
     $sc_sheetid 		= $_POST['txt_sheetid'];
	 $sc_zone_id 		= $_POST['txt_zone_id'];
	 $sc_rbnno 			= $_POST['txt_rbn_no'];
	 $acc_remarks_count = $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 		= $_POST['txt_mbook_no'];
	 $view 				= $_POST['txt_view'];
	 /*$select_al_as_query 	= "select * from al_as where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 $select_al_as_sql 		= mysqli_query($dbConn,$select_al_as_query);
	 if($select_al_as_sql == true){
	 	$ALASList 	= mysqli_fetch_object($select_al_as_sql);
		$AlLevel 	= $ALASList->al_level;
		$AlStatus 	= $ALASList->status;
		$AlAsid 	= $ALASList->alasid;
	 }
	 $AlStatus = 3;
	 $expAlLevel 	= explode(",",$AlLevel);
	 $MinLevel 		= min($expAlLevel); 
	 $MaxLevel 		= max($expAlLevel);
	 
	 $index = array_search($AlStatus,$expAlLevel);
	 if($index !== FALSE)
	 {
	 	$NextLevel 		= $expAlLevel[$index + 1];
	  	$PrevLevel 	= $expAlLevel[$index - 1];
	 }
	 
	 if($NextLevel == ""){
	 	$Status = "C";
	 }else{
	 	$Status = $NextLevel;
	 }
	 $update_al_as_query 	= "update al_as set status = '$Status', createddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and alasid = '$AlAsid'";
	 echo $update_al_as_query;
	 $update_al_as_sql 		= mysqli_query($dbConn,$update_al_as_query);*/
	// echo $PrevLevel;
	 $Status = AccountsLevelAction($sc_sheetid,$sc_rbnno,$_SESSION['levelid'],"FW");
	// echo $Status;
	 //exit;
	 
	 
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
	 /// here am changed AC_status = '' to 'A'
	 $update_query 	= "update acc_log set status = 'AC', AC_status = 'A', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 $Status = 'C'; /// Because Accept Abstract means All the mbook will be accepted so that I here declare $Status  = 'C' which means complete the process. It may be happened in AAO/AO/DCA any level
	 $update_query = "update acc_log set AC_status = '', levelid = '$Status' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 $update_sql = mysqli_query($dbConn,$update_query);
	 
	 if($Status == "C"){
		 $update_query = "update send_accounts_and_civil set ab_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and ab_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);
	 
		 $update_query = "update send_accounts_and_civil set sa_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and sa_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);

		 $update_query = "update send_accounts_and_civil set mb_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '".$_SESSION['levelid']."', level_status = '$level_status', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mb_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);

	 
	 }else if(($Status != "C")&&($Status != "")){
		 $update_query = "update send_accounts_and_civil set ab_ac = 'SA', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and ab_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);
	
		 $update_query = "update send_accounts_and_civil set sa_ac = 'SA', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and sa_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);

		 $update_query = "update send_accounts_and_civil set mb_ac = 'SA', accounts_comment ='$acc_comment_log', locked_status = '', level = '$Status', level_status = 'P', acc_staffid = '$staffid_acc', modifieddate = NOW() where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mb_ac != ''";
		 $update_sql = mysqli_query($dbConn,$update_query);
	 }
	 
	 if(($update_sql == true)||($Status != ""))
	 {
		$msg = "Abstract Verified & Accepted in Final Level";
		$success = 1;
		$RABTranFWRoleName = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTransActDetStr = "Abstract - ".$sc_mbook_no." verified and final level accepted in ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 /*$linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'A', genlevel = 'abstract', status = 'AC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '".$_SESSION['levelid']."', sectionid = ".$_SESSION['staff_section'];
	 $linsert_log_sql = mysqli_query($dbConn,$linsert_log_query);*/
	 
}

if($_POST["Back"] == " Back ")
{
	$sheetid 	= $_POST['txt_sheetid'];
	$zone_id 	= $_POST['txt_zone_id'];
	$rbn 		= $_POST['txt_rbn_no'];
	$view 		= $_POST['txt_view'];
	$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and mtype = 'A' and genlevel = 'abstract'";
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

if(isset($_POST["forward"])){
	 $staffid_acc 			= $_SESSION['sid_acc'];
	 $sc_sheetid 			= $_POST['txt_sheetid'];
	 $sc_zone_id 			= $_POST['txt_zone_id'];
	 $sc_rbnno 				= $_POST['txt_rbn_no'];
	 $acc_remarks_count 	= $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 			= $_POST['txt_mbook_no'];
	 $view 					= $_POST['txt_view'];
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $Status = AccountsLevelAction($sc_sheetid,$sc_rbnno,$_SESSION['levelid'],"FW");
	 
	 $update_query 	= "update acc_log set AC_status = 'A', comment ='$acc_comment_log', staffid = '$staffid_acc', levelid = '".$_SESSION['levelid']."',
	 				  staff_levelids= CASE WHEN (staff_levelids = '') THEN '".$_SESSION['levelid']."' ELSE CONCAT(staff_levelids, ',', '".$_SESSION['levelid']."') END , 
					  staff_ids= CASE WHEN (staff_ids = '') THEN '".$_SESSION['sid_acc']."' ELSE CONCAT(staff_ids, ',', '".$_SESSION['sid_acc']."') END ,
					  rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ,
					  comp_dt_list = CASE WHEN (comp_dt_list = '') THEN NOW() ELSE CONCAT(comp_dt_list, ',', NOW()) END   
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);

	 //$update_query = "update acc_log set AC_status = '', levelid = '$Status' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 $update_query = "update acc_log set 
	 AC_status = CASE WHEN (levelid = '".$_SESSION['levelid']."') THEN '' ELSE 'A' END,  
	 levelid = '$Status' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	 
	 //echo $update_query;exit;
	 $update_sql = mysqli_query($dbConn,$update_query);
	 
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Forwarded to Next Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName1 = GetRoleName($_SESSION['levelid'],$_SESSION['staff_section']);
		$RABTranFWRoleName2 = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Abstract - ".$sc_mbook_no." accepted in ".$RABTranFWRoleName1." Level and forwarded to ".$RABTranFWRoleName2." Level";
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
					  where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	
	 $update_query 	= "update send_accounts_and_civil set locked_status = '', acc_staffid = '".$_SESSION['sid_acc']."' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and  zone_id = '$sc_zone_id' and mtype = 'A' and genlevel = 'abstract'";
	 $update_sql 	= mysqli_query($dbConn,$update_query);
	
	 //$update_query = "update acc_log set AC_status = '', levelid = '$Status' where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno'";
	// $update_sql = mysqli_query($dbConn,$update_query);
	 
	 if($update_sql == true)
	 {
		$msg 		= "This MBook Returned to Previous Level";
		$success 	= 1;
		$_SESSION['lock'] = "";
		$RABTranFWRoleName = GetRoleName($Status,$_SESSION['staff_section']);
		$RABTransActDetStr = "Abstract - ".$sc_mbook_no." returned back to ".$RABTranFWRoleName." Level";
		//UpdateWorkTransaction($sc_sheetid,$sc_rbnno,"R",$RABTransActDetStr,"");
	 }
	 else
	 {
		$msg 		= "Error";
	 }
}

// Commented on 29.12.2016 by Prabasingh for Double time stored in mesaurement book table

/*$checkPartpay_sql 	= 	"select * from measurementbook_temp where sheetid = '$abstsheetid'";
$checkPartpay_query = 	mysqli_query($dbConn,$checkPartpay_sql);
if(mysqli_num_rows($checkPartpay_query)>0)
{
	$check = 1;
}
else
{
	$check = 0;
	$insermbook_temp_sql 	= 	"INSERT INTO measurementbook_temp (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill)
SELECT mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  '100', flag, 0, rbn, active, userid, is_finalbill FROM mbookgenerate where mbookgenerate.sheetid = '$abstsheetid'";
//$insermbook_temp_query 		= 	mysqli_query($dbConn,$insermbook_temp_sql);
}*/

//  View and hide Accept and Return to EIC button
$Abst_check_view = 1;
/*if($staff_levelid == $min_levelid)
{
	//$check_abstract_query = "select * from send_accounts_and_civil where (mb_ac = 'SA' OR mb_ac = 'SC') AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
	$check_abstract_query = "select * from send_accounts_and_civil where (sa_ac = 'SA' OR sa_ac = 'SC') AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
}
else
{
	$check_abstract_query = "select * from send_accounts_and_civil where sa_ac = 'AC' AND level = '$staff_levelid' AND level_status = 'P' AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
}*/
$check_abstract_query = "select * from acc_log where mtype = 'A' AND levelid = '".$_SESSION['levelid']."' AND (AC_status = '' OR AC_status = 'R') AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
$check_abstract_sql = mysqli_query($dbConn,$check_abstract_query);
if($check_abstract_sql == true)
{
	if(mysqli_num_rows($check_abstract_sql)>0)
	{
		$Abst_check_view = 0;
	}
}
//echo $Abst_check_view;exit;
//echo $check_abstract_query;

$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$abstsheetid' ";
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
	$rebate_profit 			= 	$List->rebate_profit;
	$overall_rebate_perc 	= 	$List->rebate_percent; 
	$GstPercRate  			= 	$List->gst_perc_rate;
	$IsLessApplic 			= 	$List->is_less_appl;
	$ContractorId 			= 	$List->contid;
	$GstIncExc 	  			= 	$List->gst_inc_exc;
	$UnderCivilSheetId 	  	= 	$List->under_civil_sheetid;
	$runn_acc_bill_no 		= 	$rbn;
	$work_order_no 			= 	$List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length1 				= 	strlen($work_name);
 	$start_line1 			= 	ceil($length1/70); 
	$length2 				= 	strlen($agree_no);
	$start_line2 			= 	ceil($length2/27);  
	$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
	if($rebate_profit == "PR"){
		$rebate_profit_str1 = "Add";
		$rebate_profit_str2 = "Profit";
	}else{
		$rebate_profit_str1 = "Less";
		$rebate_profit_str2 = "Rebate";
	}
}
$Line = $Line + $LineIncr;
//echo $LineIncr;

$select_new_mbook_no_query1 = "select gen_version from mymbook where sheetid = '$abstsheetid' AND rbn = '$rbn' AND mbookorder = '1' AND mtype = 'A' AND  genlevel = 'abstract' and mbno = '$abstmbno'";
$select_new_mbook_no_sql1 = mysqli_query($dbConn,$select_new_mbook_no_query1);
if($select_new_mbook_no_sql1 == true)
{
	if(mysqli_num_rows($select_new_mbook_no_sql1)>0)
	{
		$NMBList1 = mysqli_fetch_object($select_new_mbook_no_sql1);
		$gen_version = $NMBList1->gen_version;
	}
}


$SelectQuery2 = "SELECT state_contractor, pan_type, gst_type, is_ldc_appl, ldc_rate FROM contractor WHERE contid = '$ContractorId'";
$SelectSql2   = mysqli_query($dbConn,$SelectQuery2);
if($SelectSql2 == true){
	if(mysqli_num_rows($SelectSql2)>0){
		$List2 = mysqli_fetch_object($SelectSql2);
		$PanType  	= $List2->pan_type;
		$GstType  	= $List2->gst_type;
		$ContState  = $List2->state_contractor;
		$IsLdcAppl  = $List2->is_ldc_appl;
		$LdcRate  	= $List2->ldc_rate;
	}
}
$SelectQuery3 = "SELECT * FROM gst_rate_master";
$SelectSql3   = mysqli_query($dbConn,$SelectQuery3);
if($SelectSql3 == true){
	if(mysqli_num_rows($SelectSql3)>0){
		while($List3 = mysqli_fetch_object($SelectSql3)){
			$GstDesc  	= $List3->gst_desc;
			if($GstDesc == "CGST"){
				$Cgst 	= $List3->gst_rate;
			}
			if($GstDesc == "SGST"){
				$Sgst 	= $List3->gst_rate;
			}
			if($GstDesc == "CGST"){
				$Igst 	= $List3->gst_rate;
			}
			$GstType  	= $List2->gst_type;
			$ContState  = $List2->state_contractor;
		}
	}
}

$SelectQuery4 = "SELECT * FROM it_rate_master";
$SelectSql4   = mysqli_query($dbConn,$SelectQuery4);
if($SelectSql4 == true){
	if(mysqli_num_rows($SelectSql4)>0){
		while($List4 = mysqli_fetch_object($SelectSql4)){
			if($List4->pan_type == "I"){
				$IndItRate 	= $List4->it_rate;
			}
			if($List4->pan_type == "O"){
				$OthItRate 	= $List4->it_rate;
			}
		}
	}
}
if($IsLdcAppl == 'Y'){
	$ITaxPerc = $LdcRate;
}else{
	if(isset($PanType)){
		if($PanType == "I"){
			$ITaxPerc = $IndItRate;
		}else{
			$ITaxPerc = $OthItRate;
		}
	}else{
		$ITaxPerc = 0;
	}
}
if(isset($ContState)){
	if($ContState != "TN"){
		$IsIGst = "Y";
	}else{
		$IsIGst = "N";
	}
}else{
	$IsIGst = "N";
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
	
	<!--<script src="../jquery.modal.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" href="../jquery.modal.css" type="text/css" media="screen" />-->
	
<script src="memo_payment_modal/dialog_js.js"></script>
	<link rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		url = "AbstractBookPrint_Common_Accounts.php";
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
		strURL="../find_dpm_details.php?sheetid="+sheetid+"&itemid="+itemid;
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
								txt_box6.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
							txt_box8.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
		strURL="../find_slm_details.php?sheetid="+sheetid+"&itemid="+itemid;
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
					var Splitdata 		= data.split("@@");
					var AccountsRemarks = Splitdata[2];
					var SlmRemarks 		= Splitdata[1];
					var SlmDetails 		= Splitdata[0];
					var details 		= SlmDetails.split("*");
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
							txt_box1.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
							txt_box2.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
							txt_box3.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
								addbtn.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
								delbtn.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
							txt_box5.style.pointerEvents = "none"; ///888888888888888888888888888888888888888888888888888888888888888888
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
					if(AccountsRemarks != "")
					{
						document.getElementById("txt_accounts_remarks").value = AccountsRemarks;
					}
					else
					{
						document.getElementById("txt_accounts_remarks").value = "";
					}
				}totalAmountCalculation("slm");
					//DpmPayableAmount = document.getElementById("txt_partpay_total_payableamt_dpm").value;
				//var OverAllAmount = Number(SlmTotalAmount)+Number(DpmPayableAmount);
				//document.getElementById("txt_overall_total").value = Number(OverAllAmount).toFixed(2);

			}
		}
		xmlHttp.send(strURL);	
	}
	
	function saveDataDetails_Accounts()
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
		var result = result1 + "###" + result2;
		var itemid = document.getElementById("txt_item_id").value;
		//var itemStr = document.getElementById("hid_item_str"+itemid).value;
		//var SlmRemarks = document.getElementById("txt_slm_remarks").value;
		//var DpmRemarks = document.getElementById("txt_dpm_remarks").value;
		//var RemarksStr = SlmRemarks + "*" + DpmRemarks;
		var sheetid = document.getElementById("txt_sheet_id").value;
		var remarks = document.getElementById("txt_accounts_remarks").value;
		var rbn 	= document.getElementById("txt_rbn_no").value;
		var linkid = document.getElementById("txt_linkid").value;
		var mbookno = document.getElementById("txt_mbook_no").value;
		var staffid_acc = document.getElementById("txt_staffid_acc").value;
		var staff_levelid_acc = document.getElementById("txt_staff_levelid_acc").value;
		//alert(mbookno)
		//alert(itemid);
		$.post("../Accounts_Comments_Update.php", {sheetid: sheetid, itemid: itemid, remarks: remarks, mbookno: mbookno, rbn:rbn, linkid: linkid, staffid: staffid_acc, levelid: staff_levelid_acc }, function (data) {
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
	
	/*function SaveData()
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
				saveDataDetails();  
			} 
			else 
			{     
				swal("Cancelled", "Your data not updated:)", "");   
			} 
		});
	}*/
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
	
	/*function CancelData_Accounts()
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
				  
				$("#check_memo_payment").bPopup().close();
       			return false;
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
	}*/
	
	/*function CalculateMemoPayment()
	{
		var remarks = document.getElementById("txt_accounts_remarks").value;
	}*/
	function SaveData_Accounts_Memo()
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
				saveDataDetails_Accounts_Memo();  
			} 
			else 
			{     
				swal("Cancelled", "Your data not updated:)", "");   
			} 
		});
	}

	function saveDataDetails_Accounts_Memo()
	{
		var sheetid 		= document.getElementById("txt_sheet_id").value;
		var rbnno 			= document.getElementById("txt_rbn_no").value;
		//var slm_net_amt 	= document.getElementById("txt_SlmNetAmount").value;
		var slm_net_amt 	= document.getElementById("txt_slm_paid_amount").value;
		
		var sa_amount 				= document.getElementById("txt_sa_amount").value;
		var wct_perc 				= document.getElementById("txt_wct_perc").value;
		var wct 					= document.getElementById("txt_wct").value;
		var vat_perc 				= document.getElementById("txt_vat_perc").value;
		var vat 					= document.getElementById("txt_vat").value;
		var lw_cess_perc 			= document.getElementById("txt_lw_cess_perc").value;
		var lw_cess 				= document.getElementById("txt_lw_cess").value;
		//var mob_adv_perc 			= document.getElementById("txt_mob_adv_perc").value;
		//var mob_adv 				= document.getElementById("txt_mob_adv").value;
		var incometax_perc 			= document.getElementById("txt_incometax_perc").value;
		var incometax 				= document.getElementById("txt_incometax").value;
		var ITcess_perc 			= document.getElementById("txt_ITcess_perc").value;
		var ITcess 					= document.getElementById("txt_ITcess").value;
		var ITEcess_perc 			= document.getElementById("txt_ITEcess_perc").value;
		var ITEcess 				= document.getElementById("txt_ITEcess").value;
		var elect_charge 			= document.getElementById("txt_elect_charge").value;
		var water_charge 			= document.getElementById("txt_water_charge").value;
		var non_dep_me 				= document.getElementById("txt_non_dep_me").value;
		var non_dep_tm 				= document.getElementById("txt_non_dep_tm").value;
		var rent_land 				= document.getElementById("txt_rent_land").value;
		var liquid_damage 			= document.getElementById("txt_liquid_damage").value;
		var other_recovery_1 		= document.getElementById("txt_other_recovery_1").value;
		var other_recovery_2 		= document.getElementById("txt_other_recovery_2").value;
		var sd_perc 				= document.getElementById("txt_sd_perc").value;
		var sd 						= document.getElementById("txt_sd").value;
		var net_payable_amt			= document.getElementById("txt_net_payable_amt").value;
		var other_recovery_1_desc 	= document.getElementById("txt_other_recovery_1_desc").value;
		var other_recovery_2_desc 	= document.getElementById("txt_other_recovery_2_desc").value;
		var nonsubmission_qa 		= document.getElementById("txt_nonsubmission_qa").value;
		var sgst_perc 				= document.getElementById("txt_sgst_perc").value;
		var sgst 					= document.getElementById("txt_sgst").value;
		var cgst_perc 				= document.getElementById("txt_cgst_perc").value;
		var cgst 					= document.getElementById("txt_cgst").value;
		var igst_perc 				= document.getElementById("txt_igst_perc").value;
		var igst 					= document.getElementById("txt_igst").value;
		var mob_adv 				= document.getElementById("txt_mobadv_amount").value;
		var mob_adv_perc 			= 0;//document.getElementById("txt_igst").value;
		
		var sa_amount_civil 		= document.getElementById("hid_sa_amount").value;
		var wct_perc_civil			= document.getElementById("hid_wct_perc").value;
		var wct_civil 				= document.getElementById("hid_wct").value;
		var vat_perc_civil 			= document.getElementById("hid_vat_perc").value;
		var vat_civil 				= document.getElementById("hid_vat").value;
		var lw_cess_perc_civil 		= document.getElementById("hid_lw_cess_perc").value;
		var lw_cess_civil 			= document.getElementById("hid_lw_cess").value;
		var mob_adv_perc_civil 		= document.getElementById("hid_mob_adv_perc").value;
		var mob_adv_civil 			= document.getElementById("hid_mob_adv").value;
		var incometax_perc_civil 	= document.getElementById("hid_incometax_perc").value;
		var incometax_civil 		= document.getElementById("hid_incometax").value;
		var ITcess_perc_civil 		= document.getElementById("hid_ITcess_perc").value;
		var ITcess_civil 			= document.getElementById("hid_ITcess").value;
		var ITEcess_perc_civil 		= document.getElementById("hid_ITEcess_perc").value;
		var ITEcess_civil 			= document.getElementById("hid_ITEcess").value;
		var elect_charge_civil 		= document.getElementById("hid_elect_charge").value;
		var water_charge_civil 		= document.getElementById("hid_water_charge").value;
		var non_dep_me_civil 		= document.getElementById("hid_non_dep_me").value;
		var non_dep_tm_civil 		= document.getElementById("hid_non_dep_tm").value;
		var rent_land_civil 		= document.getElementById("hid_rent_land").value;
		var liquid_damage_civil 	= document.getElementById("hid_liquid_damage").value;
		var other_recovery_1_civil 	= document.getElementById("hid_other_recovery_1").value;
		var other_recovery_2_civil 	= document.getElementById("hid_other_recovery_2").value;
		var sd_perc_civil 			= document.getElementById("hid_sd_perc").value;
		var sd_civil 				= document.getElementById("hid_sd").value;
		var net_payable_amt_civil	= document.getElementById("hid_net_payable_amt").value;
		var nonsubmission_qa_civil 	= document.getElementById("hid_nonsubmission_qa").value;
		var sgst_perc_civil 		= document.getElementById("hid_sgst_perc").value;
		var sgst_civil 				= document.getElementById("hid_sgst").value;
		var cgst_perc_civil 		= document.getElementById("hid_cgst_perc").value;
		var cgst_civil 				= document.getElementById("hid_cgst").value;
		var igst_perc_civil 		= document.getElementById("hid_igst_perc").value;
		var igst_civil 				= document.getElementById("hid_igst").value;
		
		var rec_rel_cnt = document.getElementById("txt_rec_rel_cnt").value;
		var gst_rate 	= document.getElementById("txt_gst_rate").value;
		var gst_amt 	= document.getElementById("txt_gst_amt").value;
		var pan_type 	= document.getElementById("txt_pan_type").value;
		var is_ldc 		= document.getElementById("txt_is_ldc").value;
		
		var rrc; var recRelData = "";
		if(rec_rel_cnt > 0)
		{
//alert("hh"+rec_rel_cnt)
			for(rrc=0; rrc<rec_rel_cnt; rrc++)
			{
				var rec_rel_amt_civil 	= document.getElementById("txt_rec_rel_amt_civil"+rrc).value;
				var rec_rel_amt 		= document.getElementById("txt_rec_rel_amt"+rrc).value;
				var rec_rel_desc 		= document.getElementById("txt_rec_rel_desc"+rrc).value;
				var reid 				= document.getElementById("txt_reid"+rrc).value;
				var recRelData_1 = rec_rel_amt_civil+"@*@"+rec_rel_amt+"@*@"+rec_rel_desc+"@*@"+reid;
				var recRelData = recRelData +"@#*#@"+ recRelData_1;
				//alert("hh"+recRelData_1)
			}
		}
		else
		{
			//alert("zz"+rec_rel_cnt)
			var recRelData = "";
		}
		//var recRelData = rec_rel_amt_civil+"@*@"+rec_rel_amt+"@*@"+rec_rel_desc;
		//alert(recRelData)
		var edit_count = check_memo_payment_edit();
		//alert("Edit Count = "+edit_count);
		//exit();
		//return false;
		//alert("g");
		var dataStr_Acco = sa_amount+"*"+wct_perc+"*"+wct+"*"+vat_perc+"*"+vat+"*"+lw_cess_perc+"*"+lw_cess+"*"+mob_adv_perc+"*"+mob_adv+"*"+incometax_perc+"*"+incometax+"*"+ITcess_perc+"*"+ITcess+"*"+ITEcess_perc+"*"+ITEcess+"*"+elect_charge+"*"+water_charge+"*"+non_dep_me+"*"+non_dep_tm+"*"+rent_land+"*"+liquid_damage+"*"+other_recovery_1+"*"+other_recovery_2+"*"+sd_perc+"*"+sd+"*"+slm_net_amt+"*"+net_payable_amt+"*"+other_recovery_1_desc+"*"+other_recovery_2_desc+"*"+nonsubmission_qa+"*"+sgst_perc+"*"+sgst+"*"+cgst_perc+"*"+cgst+"*"+igst_perc+"*"+igst+"*"+gst_rate+"*"+gst_amt+"*"+pan_type+"*"+is_ldc;
		var dataStr_Civil = sa_amount_civil+"*"+wct_perc_civil+"*"+wct_civil+"*"+vat_perc_civil+"*"+vat_civil+"*"+lw_cess_perc_civil+"*"+lw_cess_civil+"*"+mob_adv_perc_civil+"*"+mob_adv_civil+"*"+incometax_perc_civil+"*"+incometax_civil+"*"+ITcess_perc_civil+"*"+ITcess_civil+"*"+ITEcess_perc_civil+"*"+ITEcess_civil+"*"+elect_charge_civil+"*"+water_charge_civil+"*"+non_dep_me_civil+"*"+non_dep_tm_civil+"*"+rent_land_civil+"*"+liquid_damage_civil+"*"+other_recovery_1_civil+"*"+other_recovery_2_civil+"*"+sd_perc_civil+"*"+sd_civil+"*"+slm_net_amt+"*"+net_payable_amt_civil+"*"+nonsubmission_qa_civil+"*"+sgst_perc_civil+"*"+sgst_civil+"*"+cgst_perc_civil+"*"+cgst_civil+"*"+igst_perc_civil+"*"+igst_civil+"*"+gst_rate+"*"+gst_amt+"*"+pan_type+"*"+is_ldc;
		//alert(dataStr);
		$.post("Accounts_Memo_Payment_Update.php", {sheetid: sheetid, rbnno: rbnno, dataStr_Acco: dataStr_Acco, dataStr_Civil: dataStr_Civil, recRelData: recRelData, edit_count: edit_count}, function (data) {
		//alert(data)
			if(data == 1)
			{
				location.reload();
			}
        });
		
	}

	function Recovery_Change_Percent(obj)
	{
		var id 			= obj.id;
		var value 		= obj.value;
		//var SlmAmount 	= document.getElementById("txt_SlmNetAmount").value;
		var SlmAmount 	= document.getElementById("txt_net_amount").value;
		if(id == 'txt_wct_perc')
		{
			var wct = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_wct").value = Math.round(wct).toFixed(2);
		
		}
		else if(id == 'txt_vat_perc')
		{
			var vat = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_vat").value = Math.round(vat).toFixed(2);
		
		}
		else if(id == 'txt_mob_adv_perc')
		{
			var mob_adv = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_mob_adv").value = Math.round(mob_adv).toFixed(2);
		
		}
		else if(id == 'txt_lw_cess_perc')
		{
			var lw_cess = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_lw_cess").value = Math.round(lw_cess).toFixed(2);
		
		}
		else if(id == 'txt_incometax_perc')
		{
			var itax = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_incometax").value = Math.round(itax).toFixed(2);
			
			var itcess_perc = document.getElementById("txt_ITcess_perc").value;
			var itEcess_perc = document.getElementById("txt_ITEcess_perc").value;
			var itcess = Number(itax)*Number(itcess_perc)/100;
			var itecess = Number(itax)*Number(itEcess_perc)/100;
			document.getElementById("txt_ITcess").value = Math.round(itcess).toFixed(2);
			document.getElementById("txt_ITEcess").value = Math.round(itecess).toFixed(2);
		
		}
		else if(id == 'txt_ITcess_perc')
		{
			var itax = document.getElementById("txt_incometax").value;
			var itcess = Number(itax)*Number(value)/100;
			//var itcess = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_ITcess").value = Math.round(itcess).toFixed(2);
		
		}
		else if(id == 'txt_ITEcess_perc')
		{
			var itax = document.getElementById("txt_incometax").value;
			var itecess = Number(itax)*Number(value)/100;
			//var itecess = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_ITEcess").value = Math.round(itecess).toFixed(2);
		
		}
		else if(id == 'txt_sd_perc')
		{
			var sd = Number(SlmAmount)*Number(value)/100;
			document.getElementById("txt_sd").value = Math.round(sd).toFixed(2);
		
		}
		else if(id == 'txt_sgst_perc')
		{
			var AmountForGst = document.getElementById("txt_amt_for_gst").value;
			var sgst = Number(AmountForGst)*Number(value)/100;
			document.getElementById("txt_sgst").value = Math.round(sgst).toFixed(2);
		
		}
		else if(id == 'txt_cgst_perc')
		{
			var AmountForGst = document.getElementById("txt_amt_for_gst").value;
			var cgst = Number(AmountForGst)*Number(value)/100;
			document.getElementById("txt_cgst").value = Math.round(cgst).toFixed(2);
		
		}
		else if(id == 'txt_igst_perc')
		{
			var AmountForGst = document.getElementById("txt_amt_for_gst").value;
			var igst = Number(AmountForGst)*Number(value)/100;
			document.getElementById("txt_igst").value = Math.round(igst).toFixed(2);
		
		}
		else
		{
			var x1 = SlmAmount;
		}
		Recovery_Change_Amount();
		//alert(id)
	}
	function Recovery_Change_Amount()
	{
		var sec_adv_amt		= document.getElementById("txt_sa_amount").value;
		var wct 			= document.getElementById("txt_wct").value;
		var vat 			= document.getElementById("txt_vat").value;
		var mob_adv 		= document.getElementById("txt_mob_adv").value;
		var lw_cess 		= document.getElementById("txt_lw_cess").value;
		var itax 			= document.getElementById("txt_incometax").value;
		var itcess 			= document.getElementById("txt_ITcess").value;
		var itecess 		= document.getElementById("txt_ITEcess").value;
		var ebill 			= document.getElementById("txt_elect_charge").value;
		var waterbill 		= document.getElementById("txt_water_charge").value;
		var non_dep_me 		= document.getElementById("txt_non_dep_me").value;
		var non_dep_tm 		= document.getElementById("txt_non_dep_tm").value;
		var rent_land 		= document.getElementById("txt_rent_land").value;
		var liquid_damage 	= document.getElementById("txt_liquid_damage").value;
		var sd 				= document.getElementById("txt_sd").value;
		var other_recovery1	= document.getElementById("txt_other_recovery_1").value;
		var other_recovery2	= document.getElementById("txt_other_recovery_2").value;
		var nonsubmission_qa = document.getElementById("txt_nonsubmission_qa").value;
		var net_amount 		= document.getElementById("txt_net_amount").value;
		
		var rec_rel_cnt = document.getElementById("txt_rec_rel_cnt").value;
		var rrc; var total_rec_rel_amt = 0;
		if(rec_rel_cnt > 0)
		{
//alert("hh"+rec_rel_cnt)
			for(rrc=0; rrc<rec_rel_cnt; rrc++)
			{
				var rec_rel_amt 		= document.getElementById("txt_rec_rel_amt"+rrc).value;
				total_rec_rel_amt = Number(total_rec_rel_amt) + Number(rec_rel_amt);
			}
		}
		//alert(net_amount)
		var total_recovery 	= Number(wct)+Number(vat)+Number(mob_adv)+Number(lw_cess)+Number(itax)+Number(itcess)+Number(itecess)+Number(ebill)+Number(waterbill)+Number(non_dep_me)+Number(non_dep_tm)+Number(rent_land)+Number(liquid_damage)+Number(sd)+Number(other_recovery1)+Number(other_recovery2)+Number(nonsubmission_qa);
		var net_payable_amt = Number(net_amount)+Number(sec_adv_amt)+Number(total_rec_rel_amt)-Number(total_recovery);
		
		//alert(net_amount)
		//alert(total_recovery)
		//alert(net_payable_amt)
		document.getElementById("txt_net_payable_amt").value = Math.round(net_payable_amt).toFixed(2);;
		//alert(total_amt)
	}
	function SecAdvance_Change_Amount()
	{
		var upto_date_amt	= document.getElementById("txt_uptodate_amount").value;
		var sec_adv_amt		= document.getElementById("txt_sa_amount").value;
		var dpm_paid_amt	= document.getElementById("txt_dpm_paid_amount").value;
		
		var wct 			= document.getElementById("txt_wct").value;
		var vat 			= document.getElementById("txt_vat").value;
		var mob_adv 		= document.getElementById("txt_mob_adv").value;
		var lw_cess 		= document.getElementById("txt_lw_cess").value;
		var itax 			= document.getElementById("txt_incometax").value;
		var itcess 			= document.getElementById("txt_ITcess").value;
		var itecess 		= document.getElementById("txt_ITEcess").value;
		var ebill 			= document.getElementById("txt_elect_charge").value;
		var waterbill 		= document.getElementById("txt_water_charge").value;
		var non_dep_me 		= document.getElementById("txt_non_dep_me").value;
		var non_dep_tm 		= document.getElementById("txt_non_dep_tm").value;
		var rent_land 		= document.getElementById("txt_rent_land").value;
		var liquid_damage 	= document.getElementById("txt_liquid_damage").value;
		var sd 				= document.getElementById("txt_sd").value;
		var other_recovery1	= document.getElementById("txt_other_recovery_1").value;
		var other_recovery2	= document.getElementById("txt_other_recovery_2").value;
		var nonsubmission_qa = document.getElementById("txt_nonsubmission_qa").value;
		var net_amount 		= document.getElementById("txt_net_amount").value;
		
		var rec_rel_cnt = document.getElementById("txt_rec_rel_cnt").value;
		var rrc; var total_rec_rel_amt = 0;
		if(rec_rel_cnt > 0)
		{
//alert("hh"+rec_rel_cnt)
			for(rrc=0; rrc<rec_rel_cnt; rrc++)
			{
				var rec_rel_amt 		= document.getElementById("txt_rec_rel_amt"+rrc).value;
				total_rec_rel_amt = Number(total_rec_rel_amt) + Number(rec_rel_amt);
			}
		}
		
		var total_recovery 	= Number(wct)+Number(vat)+Number(mob_adv)+Number(lw_cess)+Number(itax)+Number(itcess)+Number(itecess)+Number(ebill)+Number(waterbill)+Number(non_dep_me)+Number(non_dep_tm)+Number(rent_land)+Number(liquid_damage)+Number(sd)+Number(other_recovery1)+Number(other_recovery2)+Number(nonsubmission_qa);
		
		var net_payable_amt = Number(upto_date_amt)+Number(sec_adv_amt)+Number(total_rec_rel_amt)-Number(dpm_paid_amt)-Number(total_recovery);
		document.getElementById("txt_net_payable_amt").value = Math.round(net_payable_amt).toFixed(2);;
		
	}
	function check_memo_payment_edit()
	{
		var edit_count=0;
		var upto_date_amt			= document.getElementById("txt_uptodate_amount").value;
		var civil_upto_date_amt		= document.getElementById("hid_uptodate_amount").value;
		if(Number(upto_date_amt) != Number(civil_upto_date_amt)){ edit_count++; }
		
		var sec_adv_amt				= document.getElementById("txt_sa_amount").value;
		var civil_sec_adv_amt		= document.getElementById("hid_sa_amount").value;
		if(Number(sec_adv_amt) != Number(civil_sec_adv_amt)){ edit_count++; }
		
		var dpm_paid_amt			= document.getElementById("txt_dpm_paid_amount").value;
		var civil_dpm_paid_amt		= document.getElementById("hid_dpm_paid_amount").value;
		if(Number(dpm_paid_amt) != Number(civil_dpm_paid_amt)){ edit_count++; }
		
		var wct 					= document.getElementById("txt_wct").value;
		var civil_wct 				= document.getElementById("hid_wct").value;
		if(Number(wct) != Number(civil_wct)){ edit_count++; }
		
		var vat 					= document.getElementById("txt_vat").value;
		var civil_vat 				= document.getElementById("hid_vat").value;
		if(Number(vat) != Number(civil_vat)){ edit_count++; }
		
		var mob_adv 				= document.getElementById("txt_mob_adv").value;
		var civil_mob_adv 			= document.getElementById("hid_mob_adv").value;
		if(Number(mob_adv) != Number(civil_mob_adv)){ edit_count++; }
		
		var lw_cess 				= document.getElementById("txt_lw_cess").value;
		var civil_lw_cess 			= document.getElementById("hid_lw_cess").value;
		if(Number(lw_cess) != Number(civil_lw_cess)){ edit_count++; }
		
		var itax 					= document.getElementById("txt_incometax").value;
		var civil_itax 				= document.getElementById("hid_incometax").value;
		if(Number(itax) != Number(civil_itax)){ edit_count++; }
		
		var itcess 					= document.getElementById("txt_ITcess").value;
		var civil_itcess 			= document.getElementById("hid_ITcess").value;
		if(Number(itcess) != Number(civil_itcess)){ edit_count++; }
		
		var itecess 				= document.getElementById("txt_ITEcess").value;
		var civil_itecess 			= document.getElementById("hid_ITEcess").value;
		if(Number(itecess) != Number(civil_itecess)){ edit_count++; }
		
		var ebill 					= document.getElementById("txt_elect_charge").value;
		var civil_ebill 			= document.getElementById("hid_elect_charge").value;
		if(Number(ebill) != Number(civil_ebill)){ edit_count++; }
		
		var waterbill 				= document.getElementById("txt_water_charge").value;
		var civil_waterbill 		= document.getElementById("hid_water_charge").value;
		if(Number(waterbill) != Number(civil_waterbill)){ edit_count++; }
		
		var non_dep_me 				= document.getElementById("txt_non_dep_me").value;
		var civil_non_dep_me 		= document.getElementById("hid_non_dep_me").value;
		if(Number(non_dep_me) != Number(civil_non_dep_me)){ edit_count++; }
		
		var non_dep_tm 				= document.getElementById("txt_non_dep_tm").value;
		var civil_non_dep_tm 		= document.getElementById("hid_non_dep_tm").value;
		if(Number(non_dep_tm) != Number(civil_non_dep_tm)){ edit_count++; }
		
		var rent_land 				= document.getElementById("txt_rent_land").value;
		var civil_rent_land 		= document.getElementById("hid_rent_land").value;
		if(Number(rent_land) != Number(civil_rent_land)){ edit_count++; }
		
		var liquid_damage 			= document.getElementById("txt_liquid_damage").value;
		var civil_liquid_damage 	= document.getElementById("hid_liquid_damage").value;
		if(Number(liquid_damage) != Number(civil_liquid_damage)){ edit_count++; }
		
		var sd 						= document.getElementById("txt_sd").value;
		var civil_sd 				= document.getElementById("hid_sd").value;
		if(Number(sd) != Number(civil_sd)){ edit_count++; }
		
		var other_recovery1			= document.getElementById("txt_other_recovery_1").value;
		var civil_other_recovery1	= document.getElementById("hid_other_recovery_1").value;
		if(Number(other_recovery1) != Number(civil_other_recovery1)){ edit_count++; }
		
		var other_recovery2			= document.getElementById("txt_other_recovery_2").value;
		var civil_other_recovery2	= document.getElementById("hid_other_recovery_2").value;
		if(Number(other_recovery2) != Number(civil_other_recovery2)){ edit_count++; }
		
		var net_amount 				= document.getElementById("txt_net_amount").value;
		var civil_net_amount 		= document.getElementById("hid_net_amount").value;
		if(Number(net_amount) != Number(civil_net_amount)){ edit_count++; }
		
		var nonsubmission_qa 		= document.getElementById("txt_nonsubmission_qa").value;
		var civil_nonsubmission_qa 	= document.getElementById("hid_nonsubmission_qa").value;
		if(Number(nonsubmission_qa) != Number(civil_nonsubmission_qa)){ edit_count++; }
		
		return edit_count;
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
				//alert(itemdetails)
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
		/*$('input[name="check_memo_payment"]').click(function (e) 
		{
			if($(this).is(':checked'))
			{
			$('#basic-modal-content_memo_payment').modal();
			}
		});*/
            /*$('#check_memo_payment').bind('click', function(e) {
                e.preventDefault();
                $('#element_to_pop_up').bPopup(
				{
                    modalClose: false
                }
				);
            });*/
		
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
.labelprinterror
{
	font-weight:normal;
	color:#F00000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
.remarksstyle
{
	font-size:12px;
	color:#0B3599;
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
#element_to_pop_up { 
    background-color:#fff;
    /*border-radius:15px;*/
    color:#000;
    display:none; 
    /*padding:20px;*/
	padding:1px;
    width:80%;
    min-height: 480px;
	/*position:fixed;
	height:98%;
	overflow-y: auto;*/
	margin-top:5px;
}

.b-close{
    cursor:pointer;
    position:absolute;
    /*right:10px;
    top:5px;*/
	right:-18px;
    top:-12px;
}
.memo_label
{
	font-size:11px;
}
.memo_textbox
{
	font-size:11px;
	text-align:right;
	border:1px solid #09A5C6;
	height:20px;
}
.memo_pecrcenttextbox
{
	width:45px;
	font-size:11px;
	text-align:right;
	border:1px solid #09A5C6;
	height:20px;
}
.memo_table
{
	border:1px solid #C7C7C7;
	border-collapse:collapse;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
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
		cursor:pointer;
		
	}
	.spanbtn:hover{
		background:#D00843;
		color:#fff;
	}
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body id="top" bgcolor="" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<!--<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
	</tr>
</table>-->
<form name="form" method="post">
<?php
//echo "<div align='center'><div align='right' style='width:1087px;'><a href='ViewSheet.php?sheetid=".$abstsheetid."' class='spanbtn' target='_blank'><span>SOQ<span></a> &nbsp;<a href='ComparativeStatement.php?sheetid=".$sheetid."' class='spanbtn' target='_blank'><span>CST<span></a>&nbsp;<a href='#' class='spanbtn' target='_blank'><span>Previous RAB<span></a></div></div>";
echo "<div align='center'><div align='right' style='width:1087px; padding-top:5px;'><a href='ViewSheet.php?sheetid=".$abstsheetid."' class='spanbtn' target='_blank'><span>SOQ<span></a> </div>";
//eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

$page = $abstmbpage;
/*$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.' (Print version : '.$gen_version.')&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';*/
$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
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
$table = $table . "<td class=''>" . $runn_acc_bill_no . "</td>";
$table = $table . "<td class='' align='right'>CC No. </td>";
$table = $table . "<td class=''>" . $ccno . "</td>";
$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td colspan ='4' class='labelprint' align='center'>Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
//$table = $table . "</tr>";
$table = $table . "</table>";
//$Line = $Line+6;
//$tablehead = $tablehead . "<table width='1087px' frame=''  bgcolor='#0A9CC5' border='1' cellpadding='3' cellspacing='3' align='center' style='color:#ffffff;' id='mbookdetail' class='label table1'>";
$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
$tablehead = $tablehead . "<td  align='center' class='labelsmall labelheadblue' width='12px' style='' rowspan='2'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='44px' rowspan='2'>Item No.</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='130px' rowspan='2'>Description of work</td>";
$tablehead = $tablehead . "<td  align='center'  width='40px' rowspan='2'>Contents of Area</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Rate&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Per</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Total value to Date&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
$tablehead = $tablehead . "<td  align='center' class='' width='100px' colspan='3'>Deduct previous Measurements</td>";
$tablehead = $tablehead . "<td  align='center' class='' width='120px' colspan='3'>Since Last Measurement</td>";
$tablehead = $tablehead . "</tr>";
$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
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
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 
//$Line = $Line+2;

include('CementVariationAmt.php');
include('SupplementAgmtRebate.php');

$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = ""; $RebateCalcFlag = 0;
$acc_remarks_count = 0;

$QSPPMasterArr = array(); $QSPPMasterMbIdArr = array();
$QSPPSLMMasterArr = array(); $QSPPSLMMasterMbIdArr = array();
$QSPPDPMMasterArr = array(); $QSPPDPMMasterMbIdArr = array();
$QSPPRefMBPageArr = array();
$SelectQtySplitQuery = "select * from pp_qty_splt where sheetid = '$abstsheetid'";
$SelectQtySplitSql = mysqli_query($dbConn,$SelectQtySplitQuery);
if($SelectQtySplitSql == true){
	if(mysqli_num_rows($SelectQtySplitSql)>0){
		while($QSPPList = mysqli_fetch_object($SelectQtySplitSql)){
			$QSPPQty 	= $QSPPList->qty;
			$QSPPPerc 	= $QSPPList->percent;
			$QSPPRate 	= $QSPPList->rate;
			$QSPPMBId 	= $QSPPList->gr_par_id;//gpmbid;
			$QSPPRbn 	= $QSPPList->rbn;
			$QSPPAmt = round(($QSPPQty*$QSPPRate*$QSPPPerc/100),2); //echo $QSPPAmt."<br/>";
			
			if($QSPPRbn == $rbn){
				if(in_array($QSPPMBId, $QSPPSLMMasterMbIdArr)){
					$QSPPSLMMasterArr[$QSPPMBId] = $QSPPSLMMasterArr[$QSPPMBId] + $QSPPAmt;
				}else{
					array_push($QSPPSLMMasterMbIdArr,$QSPPMBId);
					$QSPPSLMMasterArr[$QSPPMBId] = $QSPPAmt;
				}
			}else{
				if(in_array($QSPPMBId, $QSPPDPMMasterMbIdArr)){
					$QSPPDPMMasterArr[$QSPPMBId] = $QSPPDPMMasterArr[$QSPPMBId] + $QSPPAmt;
				}else{
					array_push($QSPPDPMMasterMbIdArr,$QSPPMBId);
					$QSPPDPMMasterArr[$QSPPMBId] = $QSPPAmt;
				}
			}
			$QSPPRefMBPageArr[$QSPPMBId][0] = $QSPPList->mbookno;
			$QSPPRefMBPageArr[$QSPPMBId][1] = $QSPPList->page;
		}
	}
}
$PPayRefArr = array();

/*$unionqur = "(SELECT subdivid  FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$unionsql = mysqli_query($dbConn,$unionqur);
while($Listsubdivid = mysqli_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
natsort($subdivisionlist_1);*/
$MastSuppSheetIdArr = array();
$MasterItemArrNI = array(); $MasterItemArrDI = array(); $MasterItemArrEI = array(); $MasterItemArrSI = array(); $MasterItemFlagArr = array(); $DIHead = 0; $EIHead = 0; $no_of_supp_agg = 1; $DI_Amount_EI_Amount_Str = ""; $txtbox_id_di_ei = 0;
$unionqur = "(SELECT a.subdivid, b.subdiv_name, c.item_flag, c.supp_sheet_id FROM mbookgenerate a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' and b.sheet_id = '$abstsheetid' ORDER BY b.supp_sheet_id asc) UNION (SELECT a.subdivid, b.subdiv_name, c.item_flag, c.supp_sheet_id FROM measurementbook a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' AND b.sheet_id = '$abstsheetid' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') ORDER BY b.supp_sheet_id asc)";
$unionsql = mysqli_query($dbConn,$unionqur);
while($Listsubdivid = mysqli_fetch_array($unionsql)) 
{ 
	$subdivid_list .= $Listsubdivid['subdivid']."*";
	
	$MasterItemId 	= $Listsubdivid['subdivid'];
	$MasterItemName = $Listsubdivid['subdiv_name'];
	$MasterItemFlag = $Listsubdivid['item_flag'];
	$MasterSupplId  = $Listsubdivid['supp_sheet_id'];
	if($MasterItemFlag == "NI")
	{
		$MasterItemArrNI[$MasterSupplId][$MasterItemId] = $MasterItemName;
	}
	if($MasterItemFlag == "DI")
	{
		$MasterItemArrDI[$MasterSupplId][$MasterItemId] = $MasterItemName;
	}
	if($MasterItemFlag == "EI")
	{
		$MasterItemArrEI[$MasterSupplId][$MasterItemId] = $MasterItemName;
	}
	if($MasterItemFlag == "SI")
	{
		$MasterItemArrSI[$MasterSupplId][$MasterItemId] = $MasterItemName;
	}
	$MasterItemArr[$MasterItemId] = $MasterItemName;
	$MasterItemFlagArr[$MasterItemId] = $MasterItemFlag;
	if (in_array($MasterSupplId, $MastSuppSheetIdArr)){
		
	}else{
		array_push($MastSuppSheetIdArr,$MasterSupplId);
	}
}
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
natsort($subdivisionlist_1);


/*foreach($subdivisionlist_1 as $key => $summ_1)
{
   if($summ_1 != "")
   {
      $subdivisionlist_2 .= $summ_1.",";
   }
}
*/

/*natsort($MasterItemArrNI);
natsort($MasterItemArrDI);
natsort($MasterItemArrEI);
ksort($MasterItemArrSI);*/
foreach($MastSuppSheetIdArr as $MastSuppSheetId){ 
	$MasterItemArrNI1 = array(); $MasterItemArrDI1 = array(); $MasterItemArrEI1 = array(); $MasterItemArrSI1 = array();
	if($MasterItemArrNI[$MastSuppSheetId] != ""){
		$MasterItemArrNI1 = $MasterItemArrNI[$MastSuppSheetId];
		natsort($MasterItemArrNI1);
	}
	if($MasterItemArrDI[$MastSuppSheetId] != ""){
		$MasterItemArrDI1 = $MasterItemArrDI[$MastSuppSheetId];
		natsort($MasterItemArrDI1);
	}
	if($MasterItemArrEI[$MastSuppSheetId] != ""){
		$MasterItemArrEI1 = $MasterItemArrEI[$MastSuppSheetId];
		natsort($MasterItemArrEI1);
	}
	if($MasterItemArrSI[$MastSuppSheetId] != ""){
		$MasterItemArrSI1 = $MasterItemArrSI[$MastSuppSheetId];
		ksort($MasterItemArrSI1);
	}
	foreach($MasterItemArrNI1 as $keyNI => $summ_1NI)
	{
	   if($summ_1NI != "")
	   {
		  $subdivisionlist_2 .= $keyNI.",";
	   }
	}
	
	foreach($MasterItemArrDI1 as $keyDI => $summ_1DI)
	{
	   if($summ_1DI != "")
	   {
		  $subdivisionlist_2 .= $keyDI.",";
	   }
	}
	
	foreach($MasterItemArrEI1 as $keyEI => $summ_1EI)
	{
	   if($summ_1EI != "")
	   {
		  $subdivisionlist_2 .= $keyEI.",";
	   }
	}
	foreach($MasterItemArrSI1 as $keySI => $summ_1SI)
	{
	   if($summ_1SI != "")
	   {
		  $subdivisionlist_2 .= $keySI.",";
	   }
	}
}
$subdivisionlist = explode(',',rtrim($subdivisionlist_2,",")); $prev_supp_sheetid = ""; $prev_supp_sheetid_temp = "";
for($i=0;$i<count($subdivisionlist);$i++)
{
	//eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

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
	
	$DPMQtySplitArr1 = array(); $SLMQtySplitArr1 = array(); $SLDPMQtySplitArr1 = array();
	$DPMQtySplitArr2 = array(); $SLMQtySplitArr2 = array(); $SLDPMQtySplitArr2 = array();
	
	//$SLMPPayRefArr = array(); $DPMPPayRefArr = array();
	
	$slm_mesurementbook_details = ""; $dpm_mesurementbook_details = "";
	$slm_measurement_qty = 0; $dpm_measurement_qty = 0; $slm_cnt = 0; $dpm_cnt = 0;  $rowcount = 0; $slm_amount_item = 0; $dpm_amount_item = 0;
	$schduledetails = 	getschduledetails($abstsheetid,$subdivisionlist[$i]);
	$rateandunit 	= 	explode('*',$schduledetails);
	$rate 			= 	$rateandunit[0];
	$unit 			= 	$rateandunit[1];
	$item_flag 		= 	$rateandunit[4];
	$supp_sheetid 	= 	$rateandunit[5];
	$supp_sheetid_temp = $supp_sheetid;
	$decimal 		= 	get_decimal_placed($subdivisionlist[$i],$abstsheetid);
	if($supp_sheetid != $prev_supp_sheetid)
	{
		$DIHead = 0;
	}
	$prev_supp_sheetid = $supp_sheetid;
//*************THIS PART IS FOR SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//

	$mbookslmquery = "SELECT * FROM measurementbook_temp WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";// AND  (part_pay_flag = '0' OR  part_pay_flag = '1')";
	$mbookslmquery_sql = mysqli_query($dbConn,$mbookslmquery);
	if(mysqli_num_rows($mbookslmquery_sql)>0)
	{
		$SubdividSlmStr .= $subdivisionlist[$i]."*";
		while($SLMList = mysqli_fetch_array($mbookslmquery_sql))
		{
			$PPayAbstMBNo = $SLMList['ppay_abst_mb_no'];
			$PPayAbstMBPg = $SLMList['ppay_abst_mb_pg'];
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
				$PartPayremarks		=	$SLMList['remarks'];
				$slm_cnt++;
				if($SLMList['qty_split'] == 'Y'){
					if(in_array($SLMList['measurementbookid'], $SLMQtySplitArr1)){
						// Already Exist
					}else{
						array_push($SLMQtySplitArr1,$SLMList['measurementbookid']);
						//array_push($PPayRefArr,$SLMList['subdivid']);
						$PPayRefArr[$SLMList['subdivid']][0] = $subdivname;
						$PPayRefArr[$SLMList['subdivid']][1] = $rate;
						$PPayRefArr[$SLMList['subdivid']][2] = $unit;
						$PPayRefArr[$SLMList['subdivid']][3] = $decimal;
					}
				}
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
					
					if($SLMList['qty_split'] == 'Y'){
						if(in_array($bmid_dpm_slm, $SLDPMQtySplitArr1)){
							// Already Exist
						}else{
							array_push($SLDPMQtySplitArr1,$bmid_dpm_slm); /// This Array is for if Previous RAB Balance paid in Current RAB // For New Created on 19/02/2019 for Mechanical
							//array_push($PPayRefArr,$SLMList['subdivid']);
							$PPayRefArr[$SLMList['subdivid']][0] = $subdivname;
							$PPayRefArr[$SLMList['subdivid']][1] = $rate;
							$PPayRefArr[$SLMList['subdivid']][2] = $unit;
							$PPayRefArr[$SLMList['subdivid']][3] = $decimal;
							
							
							
						}
					}else{
					
						array_push($SlmArrMbidList,$bmid_dpm_slm);
						array_push($SlmArrQuantityList,$qty_dpm_slm);
						array_push($SlmArrPayPercentList,$percent_dpm_slm);
					}
				}
			}
			$Accounts_Remarks	=	$SLMList['accounts_remarks'];
		}
	}
	else
	{
		$slm_measurement_qty = 0;
		$slm_cnt = 0;
	}
	//echo "A = ".$slm_cnt."<br/>";
//*************THIS PART IS FOR DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*******************//
	$TempDpmQty = 0; $dpm_mesurementbook_details_2 = "";  $dpm_mesurementbook_details_1 = "";
	$mbookdpmquery = "SELECT * FROM measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' ORDER BY rbn ASC ";// AND  part_pay_flag = '0'";
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
				//echo $dpm_mesurementbook_details_1."<br/>";
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
				
				// This Array is for if Previous RAB Balance paid in Previous RAB // For New Created on 19/02/2019 for Mechanical
				
				if(in_array($PartpayMbid, $DPMQtySplitArr1)){
					// Already Exist
				}else{
					array_push($DPMQtySplitArr1,$PartpayMbid);
				}
				
			}
			$AbstractMbookNoDpm 		= $DPMList['abstmbookno'];
			$AbstractMbookPageNoDpm		= $DPMList['abstmbpage'];
		}
		if($dpm_mesurementbook_details_1 != ""){ $dpm_cnt++; }
		$dpm_mesurementbook_details = $dpm_mesurementbook_details_1.$dpm_mesurementbook_details_2;
	}
	else
	{
		$dpm_measurement_qty = 0;
		$dpm_cnt = 0;
	}
//echo "C = ".$dpm_cnt."<br/>";	
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
/*************************************** THIS PART IS FOR DISPLAY SUPPLEMENTARY AGREEMNT TITLE SECTION   - STARTS HERE ****************************************/
//echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>";
/*************************************** THIS PART IS FOR DISPLAY SUPPLEMENTARY AGREEMNT TITLE SECTION   - STARTS HERE ****************************************/
if($DIHead == 0) /// For the very first time 
{
	if($item_flag != "NI")
	{ 
		//$overall_rebate_perc = 2;
		$SlmRebateAmount 		=  round(($OverAllSlmAmount 	* 	$overall_rebate_perc /100),2);
		$DpmRebateAmount 		=  round(($OverAllDpmAmount 	* 	$overall_rebate_perc /100),2);
		$SlmDpmRebateAmount 	=  round(($OverAllSlmDpmAmount * 	$overall_rebate_perc /100),2);
		if($rebate_profit == "PR"){
			$SlmNetAmount 			=  round(($OverAllSlmAmount + $SlmRebateAmount),2); 
			$DpmNetAmount 			=  round(($OverAllDpmAmount + $DpmRebateAmount),2); 
			$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount + $SlmDpmRebateAmount),2);
		}else{
			$SlmNetAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
			$DpmNetAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
			$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
		}

		
		$RebateCalcFlag = 1;
?>
		<tr>
			<td colspan='4' align='left' class='labelbold'><input type="text" name="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" id="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" style="width:98%; border:none;" readonly="" class="labelbold"/></td>
			<td colspan="2" align="right" nowrap="nowrap">TOTAL AMOUNT</td>
			<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
			<td></td>
			<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
			<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
		</tr>
<?php if($prev_item_flag == "NI"){ ?>
		<tr class="labelprint">
			<td colspan="3" align="right"><?php echo $rebate_profit_str1; ?> Over All <?php echo $rebate_profit_str2; ?> : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
		<tr class="labelbold" bgcolor="#F0F0F0">
			<td colspan="3" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SlmDpmNetAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DpmNetAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SlmNetAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php }else{  
			if(($SuppRebateArr[$prev_supp_sheetid_temp] != 0)&&($SuppRebateArr[$prev_supp_sheetid_temp] != "")){
				$SuppRebateperc = $SuppRebateArr[$prev_supp_sheetid_temp];
				$SuppRebateProfit = $SuppRebateProfitArr[$prev_supp_sheetid_temp];
				$SuppAgmtSlmRebateAmount 		=  round(($SLMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtDpmRebateAmount 		=  round(($DPMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtSlmDpmRebateAmount 	=  round(($UPTOAmountNI_DI_EI * $SuppRebateperc /100),2);
				
				if($SuppRebateProfit == "PR"){
					$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI + $SuppAgmtSlmRebateAmount),2); 
					$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI + $SuppAgmtDpmRebateAmount),2); 
					$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI + $SuppAgmtSlmDpmRebateAmount),2);
					$SuppRebateProfitStr1 = "Add";
					$SuppRebateProfitStr2 = "Profit";
				}else{
					$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI - $SuppAgmtSlmRebateAmount),2); 
					$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI - $SuppAgmtDpmRebateAmount),2); 
					$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI - $SuppAgmtSlmDpmRebateAmount),2);
					$SuppRebateProfitStr1 = "Less";
					$SuppRebateProfitStr2 = "Rebate";
				}
		?>
		<tr class="labelprint">
			<td colspan="3" align="right"><?php echo $SuppRebateProfitStr1; ?> Over All <?php echo $SuppRebateProfitStr2; ?> : <?php echo $SuppRebateperc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtSlmDpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtDpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtSlmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr class="labelbold" bgcolor="#F0F0F0">
			<td colspan="3" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php		
			} 
		 } ?>
		<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
		</table>
		<p style='page-break-after:always;'></p>
		<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
		</table>
<?php
		if($SUPAG == ""){
		$SUPAG1 = "Part Agreement - 1";	
		}else{
		$SUPAG1 = "Part Agreement - 2";
		}
		$SUPAG = "x";
		echo "<div width='100%' align='center' class='labelbold'><u>".$SUPAG1."</u></div>";
		$table_supp = GetSupplementaryWorkTitle($supp_sheetid,$runn_acc_bill_no);
		echo $table_supp;
		echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>";
		echo $tablehead;
		//$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."@@";
		
		if($prev_item_flag == "NI")
		{
			$AggTitleFlag = "Main Agreement - ";
			//$no_of_supp_agg++;
			$DI_Amount_EI_Amount_Str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		else
		{
			$AggTitleFlag = "Part Agreement - ".$no_of_supp_agg; $no_of_supp_agg++;	
			$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		
		$DIHead = 1; $Line = $LineIncr+$Linecheck; $page++; $LineTemp = 0;
		$SLMAmountNI_DI_EI = 0;
		$DPMAmountNI_DI_EI = 0;
		$UPTOAmountNI_DI_EI = 0;
		$txtbox_id_di_ei++;
	}
	//$Line = $LineIncr+$Linecheck; $page++;
	//echo $Line;
}
/*if($EIHead == 0)
{
	if($item_flag == "EI")
	{
?>
		<tr>
			<td colspan='3' align='right' class='labelbold'>C/o Page No / Abstract MB No </td>
			<td></td>
			<td></td>
			<td align='right' class='labelbold'></td>
			<td></td>
			<td></td>
			<td align='right' class='labelbold'></td>
			<td></td>
			<td align='right' class='labelbold'></td>
			<td></td>
		</tr>
		<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
		</table>
		<p style='page-break-after:always;'></p>
		<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
		</table>
<?php 
		echo "<div width='100%' align='center' class='labelbold'><u>Supplementary Agreement for <i>Extra Item</i></u></div>";
		$table_supp = GetSupplementaryWorkTitle($supp_sheetid,$runn_acc_bill_no);
		echo $table_supp;
		echo "<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>";
		echo $tablehead;
		$EIHead = 1;
	}
	//$Line = $LineIncr+$Linecheck; $page++;
}*/
/*************************************** THIS PART IS FOR DISPLAY SUPPLEMENTARY AGREEMNT TITLE SECTION   - ENDS HERE ****************************************/
if($LineTemp >= 34){ $Line = 34; $LineTemp = 0; }
if($Line >= 34)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td><?php //echo $LineTemp; ?></td>
</tr>
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php 
if($item_flag == "NI"){
	echo $table;
}else{
	$table_supp = GetSupplementaryWorkTitle($supp_sheetid,$runn_acc_bill_no);
	//if($item_flag == "DI"){
	//echo "<div width='100%' align='center' class='labelbold'><u>Supplementary Agreement for <i>Deviated Item</i></u></div>";
	//}else if($item_flag == "EI"){
	//echo "<div width='100%' align='center' class='labelbold'><u>Supplementary Agreement for <i>Extra Item</i></u></div>";
	//}else{
	//echo "";
	//}
	echo $table_supp;
}
?>
<table width='1087px' cellpadding='4' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
<tr>
	<td colspan='4' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td><?php //echo $LineIncr."*".$Linecheck; ?></td>
</tr>
<?php
$Line = $LineIncr+$Linecheck; $page++;
}
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
//--*************THIS PART IS FOR " PRINT " Item Name, Description and Check Box  SECTION********************//
?>
<input type="hidden" name="hid_item_str" id="hid_item_str<?php echo $subdivid; ?>" value="<?php echo $item_str; ?>" />
<tr border='1' bgcolor="" class="labelprint">
	<!--<td  align='center' width='' class='labelsmall' style=" border-top-color:#666666; border-bottom-color:#0A9CC5; background-color:#0A9CC5" id="td_popupbutton<?php echo $table_group_row; ?>">
		<input type="checkbox" name="check" id="ch_item<?php //echo $table_group_row; ?>" value="<?php //echo $checkbox_str; ?>"  />
	</td>-->
	<td  align='left' width='' class=''>
		<?php if($Abst_check_view == 0){ ?>
		<input type="checkbox" name="check" id="ch_item<?php echo $table_group_row; ?>" value="<?php echo $checkbox_str; ?>"  />
		<?php } else { ?>
		<input type="checkbox" disabled="disabled"  />
		<?php } ?>
	</td>
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
//--*************THIS PART IS FOR " PRINT " DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*****************//
	$QtyDpmSlm_4 = 0;	$PercDpmSlm_4 = 0;	$Dpm_Slm_Amount_4 = 0;	$total_percent_dpm_slm_4 = 0;
	$QtyDpmSlm_3 = 0;	$PercDpmSlm_3 = 0;	$Dpm_Slm_Amount_3 = 0;	$total_percent_dpm_slm_3 = 0;
	$QtyDpmSlm_2 = 0;	$PercDpmSlm_2 = 0;	$Dpm_Slm_Amount_2 = 0;	$total_percent_dpm_slm_2 = 0;
	$QtyDpmSlm_1 = 0;	$PercDpmSlm_1 = 0;	$Dpm_Slm_Amount_1 = 0;	$total_percent_dpm_slm_1 = 0;
//echo $dpm_mesurementbook_details."<br/>";
	if($dpm_cnt > 0)
	{
		$eplodedpm = explode("*", rtrim($dpm_mesurementbook_details,"*"));
		//echo "D = ".count($eplodedpm)."<br/>";
		 $DpmTemp = 0;
		for($x4=0; $x4<count($eplodedpm); $x4+=13)
		{
			$dpmqty 				= $eplodedpm[$x4+1];
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
				//while($x6<=$UniqueCount)
				foreach($ArrUniqueVal as $StartKey)
				{
					//$StartKey = $ArrUniqueVal[$x6];
					$PaidDpmPerc = $DpmArrPercent[$StartKey];
					$rowspancnt = $dpm_cnt;//$UniqueCount+$DpmTemp;
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
						<td  align='left' width='180px' colspan="2" class='' rowspan="<?php echo $rowspancnt; ?>" style="font-size:10px;"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
						<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"></td>
						<td  align='right' width='' class=''><?php echo $DpmQuantityty_1;//$QtyDpmSlm_1; ?></td>
						<td  align='right' width='' class=''>
							<?php
							//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
							if(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0)){
								//echo number_format($QSPPDPMMasterArr[$StartKey], 2, '.', '');
								$DpmAmount_1 = $DpmAmount_1 + $QSPPDPMMasterArr[$StartKey];
							}
							echo number_format($DpmAmount_1, 2, '.', '');
							$dpm_amount_item 		= $dpm_amount_item + $DpmAmount_1;
							?>
						</td>
						<td  align='right' width='6%' class='' rowspan=""></td>
						<td  align='right' width='3%' class='' rowspan="">
						<?php
						if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
							echo number_format($QSPPSLMMasterArr[$StartKey], 2, '.', '');
							$slm_amount_item = $slm_amount_item + $QSPPSLMMasterArr[$StartKey];
						}else{	
							if(in_array($StartKey, $SlmArrMbidList))
							{
								echo number_format($Dpm_Slm_Amount_1, 2, '.', ''); 
								$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_1;
							} 
						}
						?>
						</td>
						<td  align='center' width='40px' class='' rowspan="" style="font-size:9px;">
						<?php 
						//if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
						if((($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0))||(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0))){
							//echo "P-".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
							echo "P-".$PPayAbstMBPg."/".$PPayAbstMBNo;
						}else{
							if(in_array($StartKey, $SlmArrMbidList))
							{
								echo $total_percent_dpm_slm_1."% Paid"; 
							}
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
							if(in_array($StartKey, $DpmArrMbidList))
							{ 
								$Arrkey = array_search($StartKey, $DpmArrMbidList);
								$QtyDpmSlm_22 = $DpmArrQuantityList[$Arrkey];
								$PercDpmSlm_22 = $DpmPercSum;//$DpmArrPayPercentList[$Arrkey];
								$Dpm_Slm_Amount_22 = $QtyDpmSlm_22 * $PercDpmSlm_22 * $rate/100;
								$total_percent_dpm_slm_22 = $DpmPercSum+$PercDpmSlm_22;
								
							}
							/*else
							{
								$QtyDpm_5 = $DpmArrQuantityList[$key];
								$PercDpmSlm_5 = $DpmArrPayPercentList[$key];
								//$Dpm_Slm_Amount_2 = $QtyDpm_5 * 100 * $rate/100;
								$Dpm_Slm_Amount_2 = $QtyDpm_5 * $DpmPercSum * $rate/100;
							}*/
							//$total_percent_dpm_slm_1 = $DpmPercSum+$PercDpmSlm_1;
?>
							<tr border='1' bgcolor="#FFFFFF" class="labelprint">
								<td  align='right' width='' class=''><?php echo $DpmArrQuantityList[$key]; ?></td>
								<td  align='right' width='' class=''>
								<?php 
									//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
									if(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0)){
										//echo number_format($QSPPDPMMasterArr[$StartKey], 2, '.', '');
										$Dpm_Slm_Amount_22 = $Dpm_Slm_Amount_22 + $QSPPDPMMasterArr[$StartKey];
									}
									echo number_format($Dpm_Slm_Amount_22, 2, '.', ''); 
									$dpm_amount_item = $dpm_amount_item + $Dpm_Slm_Amount_22; 
								?>
								</td>
								<td  align='right' width='' class=''></td>
								<td  align='right' width='' class=''>
								<?php
								if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
									echo number_format($QSPPSLMMasterArr[$StartKey], 2, '.', '');
									$slm_amount_item = $slm_amount_item + $QSPPSLMMasterArr[$StartKey];
								}else{
									if(in_array($StartKey, $SlmArrMbidList))
									{
										echo number_format($Dpm_Slm_Amount_2, 2, '.', ''); 
										$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_2;
									}
								} 
								?>
								</td>
								<td  align='center' width='40px' class='' rowspan="" style="font-size:9px;">
								<?php 
								//if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
								if((($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0))||(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0))){
									//echo "P-".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
									echo "P-".$PPayAbstMBPg."/".$PPayAbstMBNo;
								}else{
									if(in_array($StartKey, $SlmArrMbidList))
									{
										echo $total_percent_dpm_slm_2."% Paid"; 
									}
									else{
										echo $DpmPercSum."% Paid";
									}
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
						<td  align='left' width='' colspan="2" class='' style="font-size:10px;" rowspan="<?php echo $dpm_cnt; ?>"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
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
								//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
								if(($QSPPDPMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPDPMMasterArr[$MeasurementbookidDpm] != 0)){
									//echo number_format($QSPPDPMMasterArr[$MeasurementbookidDpm], 2, '.', '');
									$dpmamt = $dpmamt + $QSPPDPMMasterArr[$MeasurementbookidDpm];
								}
								
								echo number_format($dpmamt, 2, '.', ''); 
								$dpm_amount_item 		= $dpm_amount_item + $dpmamt;
							?>
						</td>
						<td  align='right' width='' class='' rowspan="<?php if($dummy == 1) { echo $dpm_cnt; } ?>"></td>
						<td  align='right' width='' class='' rowspan="<?php if($dummy == 1) { echo $dpm_cnt; } ?>">
							<?php 
							if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
								echo number_format($QSPPSLMMasterArr[$MeasurementbookidDpm], 2, '.', '');
								$slm_amount_item = $slm_amount_item + $QSPPSLMMasterArr[$MeasurementbookidDpm];
							}else{
								if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
								{
									echo number_format($Dpm_Slm_Amount_3, 2, '.', '');
									$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_3;
								}
							}
							?>
						</td>
						<td  align='center' width='' class='' rowspan="" style="font-size:9px;">
						<?php 
							//if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
							if((($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0))||(($QSPPDPMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPDPMMasterArr[$MeasurementbookidDpm] != 0))){
								//echo "P-".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
								echo "P-".$PPayAbstMBPg."/".$PPayAbstMBNo;
							}else{
								echo $total_percent_dpm_slm_3."% Paid";
							} 
							?>
						</td>
					</tr>	
<?php 			$rowcount++;
				}
				if(($dpm_cnt > 1) && ($x4 != 0))
				{
					$PaidDpmPerc2 = 0;
					$PaidDpmPerc2 = $paymentpercent_dpm;
					if(in_array($MeasurementbookidDpm, $DpmArrMbidList)){
						$ArrUniqueVal2 	= array_unique($DpmArrMbidList); 
						$UniqueCount2 	= count($ArrUniqueVal2); 
						foreach($ArrUniqueVal2 as $StartKey2=>$StartKey2Val){
							$PaidDpmPerc2 	= $PaidDpmPerc2+$DpmArrPayPercentList[$StartKey2]; 
							$DpmKeyresult2 	= checkPartpayment($DpmArrMbidList,$StartKey2);
							if($DpmKeyresult2 != ""){
								$explodeDpmKeyresult2 = explode("*",$DpmKeyresult2); 
								for($z7=0; $z7<count($explodeDpmKeyresult2); $z7++){
									$key2 		= $explodeDpmKeyresult2[$z7];
									$PaidDpmPerc2 = $PaidDpmPerc2 + $DpmArrPayPercentList[$key2]; 
								}
							}
						}
					}
					$paymentpercent_dpmA = 0; $PercDpmSlm_4 = 0;
					$paymentpercent_dpmA = $PaidDpmPerc2;
					$dpmamtA 				= $dpmqty * $rate * $PaidDpmPerc2 / 100;
					if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
					{
						$Arrkey2 = array_search($MeasurementbookidDpm, $SlmArrMbidList);
						$QtyDpmSlm_4 = $SlmArrQuantityList[$Arrkey2];
						$PercDpmSlm_4 = $SlmArrPayPercentList[$Arrkey2];
						$Dpm_Slm_Amount_4 = $QtyDpmSlm_4 * $PercDpmSlm_4 * $rate /100;
					}
					$total_percent_dpm_slm_4 = $paymentpercent_dpmA + $PercDpmSlm_4;
?>
				<tr border='1' bgcolor="#FFFFFF" class="labelprint">
					<td  align='right' width='' class=''><?php echo number_format($dpmqty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''>
					<?php 
						//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
						if(($QSPPDPMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPDPMMasterArr[$MeasurementbookidDpm] != 0)){
							//echo number_format($QSPPDPMMasterArr[$MeasurementbookidDpm], 2, '.', '');
							$dpmamtA = $dpmamtA + $QSPPDPMMasterArr[$MeasurementbookidDpm];
						}
						echo number_format($dpmamtA, 2, '.', ''); 
						$dpm_amount_item  = $dpm_amount_item + $dpmamtA; 
					?>
					</td>
					<?php 
					if($dummy == 0) 
					{
					?>
						<td  align='right' width='' class=''></td>
						<td  align='right' width='' class=''>
							<?php 
							if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
								echo number_format($QSPPSLMMasterArr[$MeasurementbookidDpm], 2, '.', '');
								$slm_amount_item = $slm_amount_item + $QSPPSLMMasterArr[$MeasurementbookidDpm];
							}else{
								if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
								{
									echo number_format($Dpm_Slm_Amount_4, 2, '.', '');
									$slm_amount_item = $slm_amount_item + $Dpm_Slm_Amount_4;
								}
							}
							?>
						</td>
						<td  align='center' width='' class='' rowspan="" style="font-size:9px;">
							<?php
							//if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
							if((($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0))||(($QSPPDPMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPDPMMasterArr[$MeasurementbookidDpm] != 0))){
								//echo "P-".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
								echo "P-".$PPayAbstMBPg."/".$PPayAbstMBNo;
							}else{
								//if(in_array($MeasurementbookidDpm, $SlmArrMbidList))
								//{
									echo $total_percent_dpm_slm_4."% Paid";
								//}
							}
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
//*************THIS PART IS FOR " PRINT " ---- SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//
?>
<?php
	$slm_dpm_str = $slm_measurement_qty."*".$dpm_measurement_qty;
	$mbooktype_query = "select flag from mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
	$mbooktype_sql = mysqli_query($dbConn,$mbooktype_query);
	$ResList1 	   = mysqli_fetch_object($mbooktype_sql);
	$flagtype = $ResList1->flag;
	if($flagtype == 1) { $mbookdescription = "/MBook No. "; }
	if($flagtype == 2) { $mbookdescription = "/MBook No. "; }

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
			$slm_amount_item = round(($slm_amount_item + $slmamt),2);
			if($x3 == 0)
			{
?>
		<tr border='1' bgcolor="#FFFFFF" class="labelprint">
			<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='left' width='' colspan="2" class='' style="font-size:10px;" rowspan="<?php echo $slm_cnt; ?>"><?php echo "Qty Vide P ".$mbpageno_slm.$mbookdescription.$mbookno_slm; ?></td>
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
					$SlmTTTitle = "<div><table><tr>"; 
					$SlmTTTitle .= "<td>Qty</td><td>Rate</td><td>( % )</td><td>Amount</td>";
					$SlmTTTitle .= "<td>".number_format($slm_measurement_qty, $decimal, '.', '')."</td><td>".$rate."</td><td>".$paymentpercent."</td><td>".number_format($slmamt, 2, '.', '')."</td>";
					$SlmTTTitle .= "</tr></table></div>";
				?>
				<span style="" class=""><?php echo number_format($slmamt, 2, '.', ''); ?></span>
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
				$SlmTTTitle  = "<div><table><tr>"; 
				$SlmTTTitle .= "<td>Qty</td><td>Rate</td><td>( % )</td><td>Amount</td>";
				$SlmTTTitle .= "<td>".number_format($slmqty, $decimal, '.', '')."</td><td>".$rate."</td><td>".$paymentpercent."</td><td>".number_format($slmamt, 2, '.', '')."</td>";
				$SlmTTTitle .= "</tr></table></div>";
?>
		<tr border='1' bgcolor="#FFFFFF" class="labelprint">
			<td  align='right' width='' class=''><?php echo number_format($slmqty, $decimal, '.', ''); ?></td>
			<td  align='right' width='' class=''><span style="color:#0444D9" class=""><?php echo number_format($slmamt, 2, '.', ''); ?></span></td>
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
			<td colspan="13" align="left" bgcolor="">Remarks &nbsp; :&nbsp;&nbsp;&nbsp;  <?php echo $PartPayremarks; ?></td>
		</tr>
<?php	
		$rowcount++; $Line++;//echo "E = ".$Line."<br/>";
		// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";}
	}	
	if($Accounts_Remarks != "")
	{
?>
		<tr border='1' class="labelprint" style="font-size:11px; color:#F00000">
			<td colspan="13" align="left" bgcolor=""><b>Accounts Remarks &nbsp; :&nbsp;&nbsp;&nbsp;</b>  <?php echo $Accounts_Remarks; ?></td>
		</tr>
<?php
		$Accounts_Remarks = "";
		$rowcount++;
		$acc_remarks_count++;
	}
//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
$total_amt_item = round(($slm_amount_item + $dpm_amount_item),2);
?>
	<tr border='1' class="labelprint" bgcolor="">
		<!--<td  align='left' width='3%' class=' label' style="border-bottom-color:#666666">&nbsp;</td>-->
		<td  align='left' width='' class=''>&nbsp;</td>
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
	<?php UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page); ?>
	<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
	
<!--------------------------------------- Cement Variation Starts------------------->
	
<?php
				
				if(in_array($subdivid,$CVItemArr)){
					$CVRate 		= $CemVarMasterArr[$subdivid][0];
					$CvDiffence 	= $CemVarMasterArr[$subdivid][1];
					
					$SLMCvVarQty	= round($SLMCemVarQtyArr[$subdivid],$decimal);
					$DPMCvVarQty 	= round($DPMCemVarQtyArr[$subdivid],$decimal);
					$UPTOCvVarQty	= round(($SLMCvVarQty + $DPMCvVarQty),$decimal);
					
					$SLMCvVar		= round(($SLMCvVarQty * $CvDiffence),$decimal);
					$DPMCvVar 		= round(($DPMCvVarQty * $CvDiffence),$decimal);
					$UPTOCvVar		= round(($SLMCvVar + $DPMCvVar),$decimal);
					
					$SLMCvVarAmt 	= round($SLMCemVarArr[$subdivid],2);
					$DPMCvVarAmt 	= round($DPMCemVarArr[$subdivid],2);
					$UPTOCvVarAmt 	= round(($SLMCvVarAmt + $DPMCvVarAmt),2);
					
					$slm_amount_item = $slm_amount_item + $SLMCvVarAmt;
					$dpm_amount_item = $dpm_amount_item + $DPMCvVarAmt;
					$total_amt_item = $total_amt_item + $UPTOCvVarAmt;
					$rowcount++; $Line++;
					
?>

				<tr border='1' class="labelprint" bgcolor="">
					<td  align='left' width='' class=''>&nbsp;</td>
					<td  align='center' width='' class=''>CV<?php echo $subdivname; ?></td>
					<td  align='right' width='' class=''> Cement Variation (<?php echo $CvDiffence; ?>)</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($UPTOCvVar, $decimal, '.', ''); ?>
					</td>
					<td  align='right' width='' class=''>
					<?php echo $CVRate; ?>
					</td>
					<td  align='left' width='' class=''>
					kg<?php //echo $unit; ?>
					</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($UPTOCvVarAmt, 2, '.', ''); ?>
					</td>
					<td  align='left' width='' class=''>&nbsp;</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($DPMCvVar, $decimal, '.', ''); ?>
					</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($DPMCvVarAmt, 2, '.', ''); ?>
					</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($SLMCvVar, $decimal, '.', ''); ?>
					</td>
					<td  align='right' width='' class=''>
					<?php echo number_format($SLMCvVarAmt, 2, '.', ''); ?>
					</td>
					<td  align='right' width='' class=''><?php //echo $Line; ?>&nbsp;</td>
				</tr>	
<?php
				}
?>	
	
<!--------------------------------------- Cement Variation Ends------------------->
	
	
	<tr bgcolor=""><td colspan="13">&nbsp;</td></tr>
	<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
	<!--<tr bgcolor="#d4d8d8" style="height:10px"><td colspan="13" style="border-top-color:#0A9CC5; border-bottom-color:#0A9CC5;"></td></tr>-->
	<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
	<?php //echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>"; ?>
	<?php
	$color_var++; $table_group_row++;
	$AbstractStr			.= $divid."*".$subdivid."*".$fromdate."*".$todate."*".$runn_acc_bill_no."*".$abstsheetid."*".$abstmbno."*".$page."*";
	$OverAllSlmAmount 		=  round(($OverAllSlmAmount	+	$slm_amount_item),2); 
	$OverAllDpmAmount 		=  round(($OverAllDpmAmount	+	$dpm_amount_item),2); 
	$OverAllSlmDpmAmount 	=  round(($OverAllSlmDpmAmount	+	$total_amt_item),2);
	
	
	$SLMAmountNI_DI_EI 		=  round(($SLMAmountNI_DI_EI	+	$slm_amount_item),2); 
	$DPMAmountNI_DI_EI 		=  round(($DPMAmountNI_DI_EI	+	$dpm_amount_item),2); 
	$UPTOAmountNI_DI_EI 	=  round(($UPTOAmountNI_DI_EI	+	$total_amt_item),2);
	$prev_item_flag = $item_flag;
	
	$prev_supp_sheetid_temp = $supp_sheetid_temp;
	
}
if(($item_flag != "")&&($item_flag != "NI"))
{
	$select_supp_agg_query = "select agree_no from sheet_supplementary  where supp_sheet_id = '$supp_sheetid'";
	$select_supp_agg_sql = mysqli_query($dbConn,$select_supp_agg_query);
	if($select_supp_agg_sql == true)
	{
		if(mysqli_num_rows($select_supp_agg_sql)>0)
		{
			while($SubSheet = mysqli_fetch_object($select_supp_agg_sql))
			{
				$sub_agg_no = $SubSheet->agree_no;
				$AggTitleFlag = "Part Agreement - ".$no_of_supp_agg;//2";//.$sub_agg_no;
			}
		}
	}
	if(($SuppRebateArr[$prev_supp_sheetid_temp] == 0)||($SuppRebateArr[$prev_supp_sheetid_temp] == "")){ //// ELSE IT WILL GOT AFTER REBATE STRING COME DOWN TO 2808Line Series
		$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
	}
}
//echo $Line;	
if($RebateCalcFlag == 0)
{
	$SlmRebateAmount 		=  round(($OverAllSlmAmount 	* 	$overall_rebate_perc /100),2);
	$DpmRebateAmount 		=  round(($OverAllDpmAmount 	* 	$overall_rebate_perc /100),2);
	$SlmDpmRebateAmount 	=  round(($OverAllSlmDpmAmount * 	$overall_rebate_perc /100),2);
	
	if($rebate_profit == "PR"){
		$SlmNetAmount 			=  round(($OverAllSlmAmount	+ $SlmRebateAmount),2); 
		$DpmNetAmount 			=  round(($OverAllDpmAmount	+ $DpmRebateAmount),2); 
		$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount	+ $SlmDpmRebateAmount),2);
	}else{
		$SlmNetAmount 			=  round(($OverAllSlmAmount	- $SlmRebateAmount),2); 
		$DpmNetAmount 			=  round(($OverAllDpmAmount	- $DpmRebateAmount),2); 
		$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount	- $SlmDpmRebateAmount),2);
	}
	
	/*$OverAllSlmAmount 			=  round($OverAllSlmAmount - $SlmRebateAmount); 
	$OverAllDpmAmount 			=  round($OverAllDpmAmount - $DpmRebateAmount); 
	$OverAllSlmDpmAmount 		=  round($OverAllSlmDpmAmount - $SlmDpmRebateAmount);*/
}
else
{
	if($rebate_profit == "PR"){
		$SlmNetAmount 			=  round(($OverAllSlmAmount + $SlmRebateAmount),2); 
		$DpmNetAmount 			=  round(($OverAllDpmAmount + $DpmRebateAmount),2); 
		$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount + $SlmDpmRebateAmount),2);
		
		$OverAllSlmAmount 			=  round(($OverAllSlmAmount + $SlmRebateAmount),2); 
		$OverAllDpmAmount 			=  round(($OverAllDpmAmount + $DpmRebateAmount),2); 
		$OverAllSlmDpmAmount 		=  round(($OverAllSlmDpmAmount + $SlmDpmRebateAmount),2);
	}else{
		$SlmNetAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
		$DpmNetAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
		$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
		
		$OverAllSlmAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
		$OverAllDpmAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
		$OverAllSlmDpmAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
	}
}
$Linecheck = 3;
$LineTemp = $Line + $Linecheck;
if($LineTemp >= 30){ $Line = 30; } 
if($Line >= 30)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td><?php //echo $Line; ?></td>
	<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td><?php //echo $LineTemp; ?></td>
</tr>
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
<tr>
	<td colspan='4' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
	<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
	<td></td>
</tr>
<?php
$Line = $LineIncr; $page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
}
?>

<?php
if($DI_Amount_EI_Amount_Str != "")
{	
?>
		<tr>
			<td colspan='4' align='left' class='labelbold'><input type="text" name="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" id="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" style="width:98%; border:none;" readonly="" class="labelbold"/></td>
			<td colspan="2" align="right" nowrap="nowrap">TOTAL AMOUNT</td>
			<td align='right' class='labelbold'><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
			<td></td>
			<td align='right' class='labelbold'><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
			<td align='right' class='labelbold'><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td></td>
		</tr>
		
		<?php if(($SuppRebateArr[$prev_supp_sheetid_temp] != 0)&&($SuppRebateArr[$prev_supp_sheetid_temp] != "")){
				$SuppRebateperc = $SuppRebateArr[$prev_supp_sheetid_temp];
				$SuppRebateProfit = $SuppRebateProfitArr[$prev_supp_sheetid_temp];
				$SuppAgmtSlmRebateAmount 		=  round(($SLMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtDpmRebateAmount 		=  round(($DPMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtSlmDpmRebateAmount 	=  round(($UPTOAmountNI_DI_EI * $SuppRebateperc /100),2);
				
				if($SuppRebateProfit == "PR"){
					$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI + $SuppAgmtSlmRebateAmount),2); 
					$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI + $SuppAgmtDpmRebateAmount),2); 
					$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI + $SuppAgmtSlmDpmRebateAmount),2);
					$SuppRebateProfitStr1 = "Add";
					$SuppRebateProfitStr2 = "Profit";
				}else{
					$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI - $SuppAgmtSlmRebateAmount),2); 
					$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI - $SuppAgmtDpmRebateAmount),2); 
					$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI - $SuppAgmtSlmDpmRebateAmount),2);
					$SuppRebateProfitStr1 = "Less";
					$SuppRebateProfitStr2 = "Rebate";
				}
	$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		?>
		<tr class="labelprint">
			<td colspan="3" align="right"><?php echo $SuppRebateProfitStr1; ?> Over All <?php echo $SuppRebateProfitStr2; ?> : <?php echo $SuppRebateperc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtSlmDpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtDpmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SuppAgmtSlmRebateAmount, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr class="labelbold" bgcolor="#F0F0F0">
			<td colspan="3" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($UPTOAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DPMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($SLMAmountNI_DI_EI, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php } ?>
		
		
		<!--<tr><td colspan="12" align="center" class="labelbold">Summary</td></tr>-->
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<?php
$Line = $LineIncr; $page++;	
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
?>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
	<tr><td colspan="13" align="center" class="labelbold">Summary of Agreement wise Total Cost</td></tr>
<?
	//$Line = $LineIncr; $page++;	
	$DI_Amount_EI_Amount_Str = rtrim($DI_Amount_EI_Amount_Str,"@@");
	$expDIEIStr = explode("@@",$DI_Amount_EI_Amount_Str);
	$DIEICount = count($expDIEIStr);
	$SlmNetAmount = 0;
	$DpmNetAmount = 0;
	$SlmDpmNetAmount = 0;
	$DIEITextBoxStr = "";
	//echo $DIEICount;//exit;
	for($d1=0; $d1<$DIEICount; $d1++)
	{
		$DIEIAmtSTr = $expDIEIStr[$d1];
		$DIEIStr 	= explode("*",$DIEIAmtSTr);
		$DIEITotalSLMAmt 	= $DIEIStr[0];
		$DIEITotalDPMAmt 	= $DIEIStr[1];
		$DIEITotalUPTOAmt 	= $DIEIStr[2];
		$DIEIPage 			= $DIEIStr[3];
		$DIEIMbook 			= $DIEIStr[4];
		$DIEITextboxId 		= $DIEIStr[5];
		$DIEIAggNo 			= $DIEIStr[6];
		$SlmNetAmount = $SlmNetAmount+$DIEITotalSLMAmt;
		$DpmNetAmount = $DpmNetAmount+$DIEITotalDPMAmt;
		$SlmDpmNetAmount = $SlmDpmNetAmount+$DIEITotalUPTOAmt;
		$DIEITextBoxStr .= $DIEITextboxId."*".$page."*".$abstmbno."*";
?>
		<tr class="labelprint" bgcolor="#F0F0F0">
			<td colspan="4" align="right" class=""><?php echo $DIEIAggNo; ?> Total B/f P-<?php echo $DIEIPage; ?>/MB <?php echo $DIEIMbook; ?>&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DIEITotalUPTOAmt, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DIEITotalDPMAmt, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
			<td align="right"><?php echo number_format($DIEITotalSLMAmt, 2, '.', ''); ?></td>
			<td>&nbsp;</td>
		</tr>

<?php
	}
	$DIEITextBoxStr = rtrim($DIEITextBoxStr,"*");
}
?>
<?php if($RebateCalcFlag == 0){ ?>
	<tr class="labelprint" bgcolor="#F0F0F0">
		<td colspan="3" align="right">Total Cost&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
		<td colspan="3" align="right"><?php echo $rebate_profit_str1; ?>: Over All <?php echo $rebate_profit_str2; ?> : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
<?php } ?>	
	<tr class="labelbold" bgcolor="#F0F0F0">
		<td colspan="3" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right"><?php echo number_format($SlmDpmNetAmount, 2, '.', ''); ?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right"><?php echo number_format($DpmNetAmount, 2, '.', ''); ?></td>
		<td>&nbsp;</td>
		<td align="right"><?php echo number_format($SlmNetAmount, 2, '.', ''); ?></td>
		<td>&nbsp;</td>
	</tr>
<?php 
$Line++; //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} 
if($Line >= 30)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
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
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
<tr>
	<td colspan='4' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
}
else
{
?>
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>
<?php
/*while($Line<30)
{
	echo "<br/>";
	$Line++;
}*/
?>
Page <?php echo $page; ?></td></tr>
<?php	
}
?>
</table>
<p style='page-break-after:always;'></p>
<?php 
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
$esc_cnt = 0;
$Esc_Total_Amt = 0;
?>

<?php

////////////////////// MEMO OF PAYMENTS STARTS HERE /////////////////////

$ThisBillValueMop = $SlmNetAmount;


/*$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();

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
			$esc_qtr_amt = round(($esc_tcc_amount+$esc_tca_amount),2);//$EscList->esc_total_amt;
			$Esc_Total_Amt = $Esc_Total_Amt+$esc_qtr_amt;//+$esc_tca_amount;
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_qtr_amt);
		}
	}
}
$Esc_Total_Amt = round($Esc_Total_Amt);
$SlmNetAmount = round(($SlmNetAmount+$Esc_Total_Amt),2);

$RevEscQtrArray = array();
$RevEscTccAmtArray = array();
$RevEscTcaAmtArray = array();
$rev_esc_cnt = 0;
$RevEsc_Total_Amt = 0;
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rev_esc_total_amt != 0 ORDER BY quarter ASC";
$select_rev_esc_rbn_sql = mysqli_query($dbConn,$select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysqli_num_rows($select_rev_esc_rbn_sql)>0)
	{
		$rev_esc_cnt = 1;
		while($RevEscList = mysqli_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			
			$total_rev_esc_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);
			
			$paid_esc_tcc_amount = $RevEscList->tcc_amt;
			$paid_esc_tca_amount = $RevEscList->tca_amt;
			
			$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
			
			//// Second or more than two time revised
			$select_esc_paid_query = "select rev_tcc_mbook, rev_tcc_mbpage, rev_esc_total_amt from escalation_revised where sheetid = '$abstsheetid' and quarter = '$rev_quarter' ORDER BY rev_esc_id  DESC";
			$select_esc_paid_sql = mysqli_query($dbConn,$select_esc_paid_query);
			if($select_esc_paid_sql == true)
			{
				$PaidEAbaMB = mysqli_fetch_object($select_esc_paid_sql);
				$PaidEsc_Abs_MBook = $PaidEAbaMB->rev_tcc_mbook;
				$PaidEsc_Abs_MBPage = $PaidEAbaMB->rev_tcc_mbpage;
				$PaidEsc_Abs_tot_amt = $PaidEAbaMB->rev_esc_total_amt;
			}
			if($PaidEsc_Abs_tot_amt>0)
			{
				$paid_esc_tcc_amount = $PaidEsc_Abs_MBook;
				$paid_esc_tca_amount = $PaidEsc_Abs_MBPage;
				$total_paid_esc_amt = $PaidEsc_Abs_tot_amt;
			}
			$rev_esc_qtr_amt = round(($total_rev_esc_amt-$total_paid_esc_amt),2);
			$RevEsc_Total_Amt = $RevEsc_Total_Amt+$rev_esc_qtr_amt;//+$esc_tca_amount;
			
			array_push($RevEscQtrArray,$rev_quarter);
			array_push($RevEscTccAmtArray,$rev_esc_qtr_amt);
		}
	}
}
$RevEsc_Total_Amt = round($RevEsc_Total_Amt);
$SlmNetAmount = round(($SlmNetAmount+$RevEsc_Total_Amt),2);


$total_recovery_civil = 0; $total_recovery = 0; $edit_count = 0;
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysqli_query($dbConn,$secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysqli_fetch_object($secured_advance_sql);
	$sec_adv_amount_civil	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount_civil = 0;
}

$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysqli_query($dbConn,$water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysqli_fetch_object($water_recovery_sql))
	{
		$water_charge_civil 	= 	$water_charge_civil+$WRList->water_cost;
	}
}
else
{
	$water_charge_civil = 0;
}
$total_recovery_civil = $total_recovery_civil + $water_charge_civil;
$electricity_recovery_query = "select electricity_cost from generate_electricitybill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$electricity_recovery_sql = mysqli_query($dbConn,$electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysqli_fetch_object($electricity_recovery_sql))
	{
		$electricity_charge_civil 	= 	$electricity_charge_civil+$ERList->electricity_cost;
	}
}
else
{
	$electricity_charge_civil = 0;
}
$total_recovery_civil = $total_recovery_civil + $electricity_charge_civil;


$rrcount = 0;  $total_rec_rel_amt_civil = 0;  $total_rec_rel_amt_acc = 0;
$RRDescCivArr = array(); $RRAmtCivArr = array(); $RRDescAccArr = array(); 
$RRAmtAccArr = array(); $RRDescArr = array(); $RRDescAmt = array();  $RRIdArr = array();

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
			$reid = $RecRelList->reid;
			if($rec_rel_desc_acc != "")
			{
				$rec_rel_desc = $rec_rel_desc_acc;
			}
			else
			{
				$rec_rel_desc = $rec_rel_desc_civil;
			}
			
			if($rec_rel_amt_acc != 0)
			{
				$rec_rel_amt = $rec_rel_amt_acc;
			}
			else
			{
				$rec_rel_amt = $rec_rel_amt_civil;
			}
			array_push($RRDescCivArr,$rec_rel_desc_civil);
			array_push($RRAmtCivArr,$rec_rel_amt_civil);
			array_push($RRDescAccArr,$rec_rel_desc_acc);
			array_push($RRAmtAccArr,$rec_rel_amt_acc);
			array_push($RRIdArr,$reid);
			
			array_push($RRDescArr,$rec_rel_desc);
			array_push($RRDescAmt,$rec_rel_amt);
			$total_rec_rel_amt_civil  	= $total_rec_rel_amt_civil + $rec_rel_amt_civil;
			$total_rec_rel_amt_acc  	= $total_rec_rel_amt_acc + $rec_rel_amt_acc;
			$rrcount++;
		}
	}
}
if($total_rec_rel_amt_acc != 0)
{
	$total_rec_rel_amt = $total_rec_rel_amt_acc;
}
else
{
	$total_rec_rel_amt = $total_rec_rel_amt_civil;
}


$general_recovery_query = "select * from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
$general_recovery_sql = mysqli_query($dbConn,$general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 			= 	mysqli_fetch_object($general_recovery_sql);
	$bill_amt_gst_civil 		= 	round($GRList->bill_amt_gst);
	$sd_amt_civil 				= 	round($GRList->sd_amt);
	$sd_percent_civil 			= 	$GRList->sd_percent;
	$sgst_amt_civil 			= 	round($GRList->sgst_tds_amt);
	$sgst_percent_civil 		= 	$GRList->sgst_tds_perc;
	$cgst_amt_civil 			= 	round($GRList->cgst_tds_amt);
	$cgst_percent_civil 		= 	$GRList->cgst_tds_perc;
	$igst_amt_civil 			= 	round($GRList->igst_tds_amt);
	$igst_percent_civil 		= 	$GRList->igst_tds_perc;
	$wct_amt_civil 				= 	round($GRList->wct_amt);
	$wct_percent_civil 			= 	$GRList->wct_percent;
	$vat_amt_civil 				= 	round($GRList->vat_amt);
	$vat_percent_civil 			= 	$GRList->vat_percent;
	$mob_adv_amt_civil 			= 	round($GRList->mob_adv_amt);
	$mob_adv_percent_civil 		= 	$GRList->mob_adv_percent;
	$lw_cess_amt_civil 			= 	round($GRList->lw_cess_amt);
	$lw_cess_percent_civil 		= 	$GRList->lw_cess_percent;
	$incometax_amt_civil 		= 	round($GRList->incometax_amt);
	$incometax_percent_civil 	= 	$GRList->incometax_percent;
	$it_cess_amt_civil 			= 	round($GRList->it_cess_amt);
	$it_cess_percent_civil 		= 	$GRList->it_cess_percent;
	$it_edu_amt_civil 			= 	round($GRList->it_edu_amt);
	$it_edu_percent_civil 		= 	$GRList->it_edu_percent;
	$land_rent_civil 			= 	round($GRList->land_rent);
	$liquid_damage_civil 		= 	round($GRList->liquid_damage);
	
	$other_recovery_1_civil 	= 	round($GRList->other_recovery_1);
	$other_recovery_2_civil		= 	round($GRList->other_recovery_2);
	$other_recovery_3_civil		= 	round($GRList->other_recovery_3);
	
	$other_recovery_1_desc_civil= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc_civil= 	$GRList->other_recovery_2_desc;
	$other_recovery_3_desc_civil= 	$GRList->other_recovery_3_desc;
	
	$non_dep_machine_equip_civil= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power_civil 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa_civil 	= 	round($GRList->nonsubmission_qa);
}
$total_recovery_civil = $total_recovery_civil + $sd_amt_civil + $sgst_amt_civil + $cgst_amt_civil + $igst_amt_civil + $wct_amt_civil + $vat_amt_civil+$mob_adv_amt_civil + $lw_cess_amt_civil+$incometax_amt_civil + $it_cess_amt_civil+$it_edu_amt_civil + $land_rent_civil+$liquid_damage_civil + $other_recovery_1_civil + $other_recovery_2_civil + $other_recovery_3_civil + $non_dep_machine_equip_civil + $non_dep_man_power_civil + $nonsubmission_qa_civil;
$OverAllSlmAmount_civil = $OverAllSlmAmount + $sec_adv_amount_civil;
$Overall_net_amt_final_civil = round(($OverAllSlmAmount_civil - $total_recovery_civil),2);
$Overall_net_amt_final_civil = round($Overall_net_amt_final_civil);

$accounts_edit_query = "select * from memo_payment_accounts_edit where sheetid = '$abstsheetid' and rbn = '$rbn'";// and edit_flag = 'EDITED'";
//echo $accounts_edit_query;
$accounts_edit_sql = mysqli_query($dbConn,$accounts_edit_query);
if($accounts_edit_sql == true)
{
	if(mysqli_num_rows($accounts_edit_sql)>0)
	{
		$edit_count = 1;
	}
	else
	{
		$edit_count = 1;
	}
}

if($edit_count == 1)
{
	$MEMOList 				= 	mysqli_fetch_object($accounts_edit_sql);
	$bill_amt_gst 			= 	round($MEMOList->bill_amt_gst);
	$sd_amt 				= 	round($MEMOList->sd_amt);
	$sd_percent 			= 	$MEMOList->sd_percent;
	$sgst_amt 				= 	round($MEMOList->sgst_tds_amt);
	$sgst_percent 			= 	$MEMOList->sgst_tds_perc;
	$cgst_amt 				= 	round($MEMOList->cgst_tds_amt);
	$cgst_percent 			= 	$MEMOList->cgst_tds_perc;
	$igst_amt 				= 	round($MEMOList->igst_tds_amt);
	$igst_percent 			= 	$MEMOList->igst_tds_perc;
	$wct_amt				= 	round($MEMOList->wct_amt);
	$wct_percent 			= 	$MEMOList->wct_percent;
	$vat_amt 				= 	round($MEMOList->vat_amt);
	$vat_percent 			= 	$MEMOList->vat_percent;
	$mob_adv_amt 			= 	round($MEMOList->mob_adv_amt);
	$mob_adv_percent 		= 	$MEMOList->mob_adv_percent;
	$lw_cess_amt 			= 	round($MEMOList->lw_cess_amt);
	$lw_cess_percent 		= 	$MEMOList->lw_cess_percent;
	$incometax_amt 			= 	round($MEMOList->incometax_amt);
	$incometax_percent 		= 	$MEMOList->incometax_percent;
	$it_cess_amt 			= 	round($MEMOList->it_cess_amt);
	$it_cess_percent 		= 	$MEMOList->it_cess_percent;
	$it_edu_amt 			= 	round($MEMOList->it_edu_amt);
	$it_edu_percent 		= 	$MEMOList->it_edu_percent;
	$land_rent 				= 	round($MEMOList->land_rent);
	$liquid_damage 			= 	round($MEMOList->liquid_damage);
	$other_recovery_1 		= 	round($MEMOList->other_recovery_1_amt);
	$other_recovery_2		= 	round($MEMOList->other_recovery_2_amt);
	$other_recovery_1_desc	= 	$MEMOList->other_recovery_1_desc;
	$other_recovery_2_desc	= 	$MEMOList->other_recovery_2_desc;
	$non_dep_machine_equip 	= 	round($MEMOList->non_dep_machine_equip);
	$non_dep_man_power 		= 	round($MEMOList->non_dep_man_power);
	$sec_adv_amount 		= 	$MEMOList->sec_adv_amount;
	$water_charge 			= 	$MEMOList->water_cost;
	$electricity_charge		= 	$MEMOList->electricity_cost;
	$nonsubmission_qa		= 	$MEMOList->nonsubmission_qa;
	
	$mob_adv_amt_rec		= 	$MEMOList->mob_adv_amt_rec;
	$mob_adv_int_amt		= 	$MEMOList->mob_adv_int_amt;
	$pl_mac_adv_rec			= 	$MEMOList->pl_mac_adv_rec;
	$pl_mac_adv_int_amt		= 	$MEMOList->pl_mac_adv_int_amt;
	$hire_charges			= 	$MEMOList->hire_charges;
}
else
{
	$bill_amt_gst			= 	$bill_amt_gst_civil;
	$sd_amt 				= 	$sd_amt_civil;
	$sd_percent 			= 	$sd_percent_civil;
	$sgst_amt 				= 	$sgst_amt_civil;
	$sgst_percent 			= 	$sgst_percent_civil;
	$cgst_amt 				= 	$cgst_amt_civil;
	$cgst_percent 			= 	$cgst_percent_civil;
	$igst_amt 				= 	$igst_amt_civil;
	$igst_percent 			= 	$igst_percent_civil;
	$wct_amt				= 	$wct_amt_civil;
	$wct_percent 			= 	$wct_percent_civil;
	$vat_amt				= 	$vat_amt_civil;
	$vat_percent 			= 	$vat_percent_civil;
	$mob_adv_amt			= 	$mob_adv_amt_civil;
	$mob_adv_percent 		= 	$mob_adv_percent_civil;
	$lw_cess_amt 			= 	$lw_cess_amt_civil;
	$lw_cess_percent 		= 	$lw_cess_percent_civil;
	$incometax_amt 			= 	$incometax_amt_civil;
	$incometax_percent 		= 	$incometax_percent_civil;
	$it_cess_amt 			= 	$it_cess_amt_civil;
	$it_cess_percent 		= 	$it_cess_percent_civil;
	$it_edu_amt 			= 	$it_edu_amt_civil;
	$it_edu_percent 		= 	$it_edu_percent_civil;
	$land_rent 				= 	$land_rent_civil;
	$liquid_damage 			= 	$liquid_damage_civil;
	$other_recovery_1 		= 	$other_recovery_1_civil;
	$other_recovery_2		= 	$other_recovery_2_civil;
	$other_recovery_3		= 	$other_recovery_3_civil;
	$other_recovery_1_desc	= 	$other_recovery_1_desc_civil;
	$other_recovery_2_desc	= 	$other_recovery_2_desc_civil;
	$other_recovery_3_desc	= 	$other_recovery_3_desc_civil;
	$non_dep_machine_equip 	= 	$non_dep_machine_equip_civil;
	$non_dep_man_power 		= 	$non_dep_man_power_civil;
	$sec_adv_amount 		= 	$sec_adv_amount_civil;
	$water_charge 			= 	$water_charge_civil;
	$electricity_charge		= 	$electricity_charge_civil;
	$nonsubmission_qa		= 	$nonsubmission_qa_civil;
}
if($sd_amt != $sd_amt_civil)							  { $fclass1  = "labelprinterror"; } else { $fclass1  = "labelprint"; }
if($sd_percent != $sd_percent_civil)					  { $fclass2  = "labelprinterror"; } else { $fclass2  = "labelprint"; }
if($wct_amt != $wct_amt_civil)							  { $fclass3  = "labelprinterror"; } else { $fclass3  = "labelprint"; } //echo "sf".$fclass3."<br/>";
if($wct_percent != $wct_percent_civil)					  { $fclass4  = "labelprinterror"; } else { $fclass4  = "labelprint"; } //echo "hg".$fclass4."<br/>";
if($vat_percent != $vat_percent_civil)					  { $fclass5  = "labelprinterror"; } else { $fclass5  = "labelprint"; }
if($mob_adv_amt != $mob_adv_amt_civil)					  { $fclass6  = "labelprinterror"; } else { $fclass6  = "labelprint"; }
if($mob_adv_percent != $mob_adv_percent_civil)			  { $fclass7  = "labelprinterror"; } else { $fclass7  = "labelprint"; }
if($lw_cess_amt != $lw_cess_amt_civil)					  { $fclass8  = "labelprinterror"; } else { $fclass8  = "labelprint"; }
if($lw_cess_percent != $lw_cess_percent_civil)			  { $fclass9  = "labelprinterror"; } else { $fclass9  = "labelprint"; }
if($incometax_amt != $incometax_amt_civil)				  { $fclass10 = "labelprinterror"; } else { $fclass10 = "labelprint"; }
if($incometax_percent != $incometax_percent_civil)		  { $fclass11 = "labelprinterror"; } else { $fclass11 = "labelprint"; }
if($it_cess_amt != $it_cess_amt_civil)					  { $fclass12 = "labelprinterror"; } else { $fclass12 = "labelprint"; }
if($it_cess_percent != $it_cess_percent_civil)			  { $fclass13 = "labelprinterror"; } else { $fclass13 = "labelprint"; }
if($it_edu_amt != $it_edu_amt_civil)					  { $fclass14 = "labelprinterror"; } else { $fclass14 = "labelprint"; }
if($it_edu_percent != $it_edu_percent_civil)			  { $fclass15 = "labelprinterror"; } else { $fclass15 = "labelprint"; }
if($land_rent != $land_rent_civil)						  { $fclass16 = "labelprinterror"; } else { $fclass16 = "labelprint"; }
if($liquid_damage != $liquid_damage_civil)				  { $fclass17 = "labelprinterror"; } else { $fclass17 = "labelprint"; }
if($other_recovery_1 != $other_recovery_1_civil)		  { $fclass18 = "labelprinterror"; } else { $fclass18 = "labelprint"; }
if($other_recovery_2 != $other_recovery_2_civil)		  { $fclass19 = "labelprinterror"; } else { $fclass19 = "labelprint"; }
if($other_recovery_1_desc != $other_recovery_1_desc_civil){ $fclass20 = "labelprinterror"; } else { $fclass20 = "labelprint"; }
if($other_recovery_2_desc != $other_recovery_2_desc_civil){ $fclass21 = "labelprinterror"; } else { $fclass21 = "labelprint"; }
if($non_dep_machine_equip != $non_dep_machine_equip_civil){ $fclass22 = "labelprinterror"; } else { $fclass22 = "labelprint"; }
if($non_dep_man_power != $non_dep_man_power_civil)		  { $fclass23 = "labelprinterror"; } else { $fclass23 = "labelprint"; }
if($sec_adv_amount != $sec_adv_amount_civil)			  { $fclass24 = "labelprinterror"; } else { $fclass24 = "labelprint"; }
if($water_charge != $water_charge_civil)				  { $fclass25 = "labelprinterror"; } else { $fclass25 = "labelprint"; }
if($electricity_charge != $electricity_charge_civil)	  { $fclass26 = "labelprinterror"; } else { $fclass26 = "labelprint"; }
if($nonsubmission_qa != $nonsubmission_qa_civil)		  { $fclass27 = "labelprinterror"; } else { $fclass27 = "labelprint"; }
if($sgst_amt != $sgst_amt_civil)					  	  { $fclass28 = "labelprinterror"; } else { $fclass28 = "labelprint"; }
if($sgst_percent != $sgst_percent_civil)				  { $fclass29 = "labelprinterror"; } else { $fclass29 = "labelprint"; }
if($cgst_amt != $cgst_amt_civil)					  	  { $fclass30 = "labelprinterror"; } else { $fclass30 = "labelprint"; }
if($cgst_percent != $cgst_percent_civil)				  { $fclass31 = "labelprinterror"; } else { $fclass31 = "labelprint"; }
if($igst_amt != $igst_amt_civil)					  	  { $fclass32 = "labelprinterror"; } else { $fclass32 = "labelprint"; }
if($igst_percent != $igst_percent_civil)				  { $fclass33 = "labelprinterror"; } else { $fclass33 = "labelprint"; }


$total_recovery = $total_recovery + $water_charge;
$total_recovery = $total_recovery + $electricity_charge;
$total_recovery = $total_recovery + $sgst_amt + $cgst_amt + $igst_amt + $sd_amt + $wct_amt + $vat_amt + $mob_adv_amt_rec + $mob_adv_int_amt + $pl_mac_adv_rec + $pl_mac_adv_int_amt + $hire_charges + $lw_cess_amt + $incometax_amt + $it_cess_amt + $it_edu_amt + $land_rent + $liquid_damage + $other_recovery_1 + $other_recovery_2 + $other_recovery_3 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;


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

$OverAllSlmDpmAmount = round($SlmDpmNetAmount);
$OverAllSlmAmount = round($SlmNetAmount);
$OverAllDpmAmount = round($DpmNetAmount);
*/
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
?>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
if($UnderCivilSheetId == 0){
	echo "<tr style='border:none'>
	<td style='border:none' class='labelbold' align='left' colspan='5'><a style='text-decoration:none' href='MemoOfPaymentCreate.php?sheetid=".$abstsheetid."&linkid=".$linkid."&ccno=".$ccno."'><span class='spanbtn' name='check_memo_payment' id='check_memo_payment'>Click here to edit MOP</span></a></td>
	<td style='border:none' class='labelbold' align='left' colspan='8'><u>Memo of Payment</u></td>
	</tr>";
}else{
	echo "<tr style='border:none'>
	<td style='border:none' class='labelbold' align='left' colspan='5'></td>
	<td style='border:none' class='labelbold' align='left' colspan='8'><u>Memo of Payment</u></td>
	</tr>";
}


$SelectAbstQuery = "select * from abstractbook where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectAbstSql = mysqli_query($dbConn,$SelectAbstQuery);
if($SelectAbstSql == true){
	if(mysqli_num_rows($SelectAbstSql)>0){
		$AbstList = mysqli_fetch_object($SelectAbstSql);
		$upto_date_total_amount = $AbstList->upto_date_total_amount;
		$dpm_total_amount = $AbstList->dpm_total_amount;
		$slm_total_amount = $AbstList->slm_total_amount;
		$Uptombookno = $AbstList->mbookno;
		$Uptombookpage = $AbstList->mbookpage;
		if(($AbstList->is_adv_pay == 'Y')&&(($AbstList->pass_order_dt == '0000-00-00')||($AbstList->pass_order_dt == NULL))){
			$SelectAbstQuery2 = "select * from abstractbook_dt where sheetid = '$sheetid' and rbn = '$rbn' and is_adv_pay = 'Y'";
			$SelectAbstSql2 = mysqli_query($dbConn,$SelectAbstQuery2);
			if($SelectAbstSql2 == true){
				if(mysqli_num_rows($SelectAbstSql2)>0){
					$IsAdvPayFlag = "Y";
				}
			}
		}
	}
}
	
$AccSelectQuery = "select * from memo_payment_accounts_edit where sheetid = '$abstsheetid' and rbn = '$rbn' ORDER BY memoid DESC LIMIT 1 ";
$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
if($AccSelectSql == true){
	if(mysqli_num_rows($AccSelectSql)>0){
		$AccList = mysqli_fetch_object($AccSelectSql);
		$abstract_net_amt = $AccList->abstract_net_amt;
		
		$upto_date_total_amount = $AccList->cmb_uptodt_amt;
		$dpm_total_amount = $AccList->cmb_ded_prev_amt;
		$slm_total_amount = $AccList->abstract_net_amt;
		
		$cgst_percent = $AccList->cgst_tds_perc;
		$cgst_amt = $AccList->cgst_tds_amt;
		$sgst_percent = $AccList->sgst_tds_perc;
		$sgst_amt = $AccList->sgst_tds_amt;
		$igst_percent = $AccList->igst_tds_perc;
		$igst_amt = $AccList->igst_tds_amt;
		$sd_percent = $AccList->sd_percent;
		$sd_amt = $AccList->sd_amt;
		$wct_percent = $AccList->wct_percent;
		$wct_amt = $AccList->wct_amt;
		$vat_percent = $AccList->vat_percent;
		$vat_amt = $AccList->vat_amt;
		$mob_adv_percent = $AccList->mob_adv_percent;
		$mob_adv_amt = $AccList->mob_adv_amt;
		$lw_cess_percent = $AccList->lw_cess_percent;
		$lw_cess_amt = $AccList->lw_cess_amt;
		$incometax_percent = $AccList->incometax_percent;
		$incometax_amt = $AccList->incometax_amt;
		$it_cess_percent = $AccList->it_cess_percent;
		$it_cess_amt = $AccList->it_cess_amt;
		$it_edu_percent = $AccList->it_edu_percent;
		$it_edu_amt = $AccList->it_edu_amt;
		$land_rent = $AccList->land_rent;
		$liquid_damage = $AccList->liquid_damage;
		$other_recovery_1_desc = $AccList->other_recovery_1_desc;
		$other_recovery_1_amt = $AccList->other_recovery_1_amt;
		$other_recovery_2_desc = $AccList->other_recovery_2_desc;
		$other_recovery_2_amt = $AccList->other_recovery_2_amt;
		$non_dep_machine_equip = $AccList->non_dep_machine_equip;
		$non_dep_man_power = $AccList->non_dep_man_power;
		$nonsubmission_qa = $AccList->nonsubmission_qa;
		
		$sec_adv_amount = $AccList->sec_adv_amount;
		$pl_mac_adv_amt = $AccList->pl_mac_adv_amt;
		$esc_amt = $AccList->esc_amt;
		$mob_adv_amt = $AccList->mob_adv_amt;
		
		$electricity_cost = $AccList->electricity_cost;
		$water_cost = $AccList->water_cost;
		
		$edit_flag = $AccList->edit_flag;
		$pass_order_dt = dt_display($AccList->pass_order_dt);
		$pay_order_dt = dt_display($AccList->pay_order_dt);
		$voucher_dt = dt_display($AccList->payment_dt);
		
		$is_adv_pay = $AccList->is_adv_pay;
		$adv_perc = $AccList->adv_perc;
		$adv_amt = $AccList->adv_amt;
		
		$mob_adv_amt_rec = $AccList->mob_adv_amt_rec;
		$pl_mac_adv_rec = $AccList->pl_mac_adv_rec;
		$mob_adv_int_amt = $AccList->mob_adv_int_amt;
		$pl_mac_adv_int_amt = $AccList->pl_mac_adv_int_amt;
		$hire_charges = $AccList->hire_charges;
		
		$bill_amt_gst = round($AccList->bill_amt_gst);
		$bill_amt_it  = round($AccList->bill_amt_it);
		
		$mop_date = $AccList->mop_date;
		if($mop_date == "0000-00-00"){
			$mop_date = date("Y-m-d",strtotime($AccList->modifieddate));
		}
		
		$Acc = 1;
	}
}
$GrandTotal = round(($upto_date_total_amount + $sec_adv_amount + $pl_mac_adv_amt + $esc_amt + $mob_adv_amt),2);
$NetTotal = round(($GrandTotal - $dpm_total_amount),2); 
$CodeAmount = $NetTotal;

$TotalRecovery = 0; 
$TotalRecovery  = $TotalRecovery + $lw_cess_amt; 
$CodeAmount = $CodeAmount - $lw_cess_amt;
$TotalRecovery  = $TotalRecovery + $mob_adv_amt_rec; 
$CodeAmount = $CodeAmount - $mob_adv_amt_rec;
$TotalRecovery  = $TotalRecovery + $pl_mac_adv_rec; 
$CodeAmount = $CodeAmount - $pl_mac_adv_rec;
$TotalRecovery  = $TotalRecovery + $adv_amt;
$TotalRecovery  = $TotalRecovery + $incometax_amt;
$TotalRecovery  = $TotalRecovery + $it_cess_amt;
$TotalRecovery  = $TotalRecovery + $it_edu_amt;
$TotalRecovery  = $TotalRecovery + $cgst_amt;
$TotalRecovery  = $TotalRecovery + $sgst_amt;
$TotalRecovery  = $TotalRecovery + $igst_amt;
$TotalRecovery  = $TotalRecovery + $sd_amt;
$TotalRecovery  = $TotalRecovery + $wct_amt;
$TotalRecovery  = $TotalRecovery + $vat_amt;
$TotalRecovery  = $TotalRecovery + $land_rent;
$TotalRecovery  = $TotalRecovery + $liquid_damage;
$TotalRecovery  = $TotalRecovery + $other_recovery_1_amt; 
$CodeAmount = $CodeAmount - $other_recovery_1_amt;
$TotalRecovery  = $TotalRecovery + $other_recovery_2_amt; 
$CodeAmount = $CodeAmount - $other_recovery_2_amt;
$TotalRecovery  = $TotalRecovery + $non_dep_machine_equip;
$TotalRecovery  = $TotalRecovery + $non_dep_man_power;
$TotalRecovery  = $TotalRecovery + $nonsubmission_qa;
$TotalRecovery  = $TotalRecovery + $electricity_cost;
$TotalRecovery  = $TotalRecovery + $water_cost;
$TotalRecovery  = $TotalRecovery + $mob_adv_int_amt;
$TotalRecovery  = $TotalRecovery + $pl_mac_adv_int_amt;
$ChequeAmount = round($NetTotal - $TotalRecovery);


echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($upto_date_total_amount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";

if($mob_adv_amt != 0){
	echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Mob. Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
}
if($pl_mac_adv_amt != 0){
	echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>P&M Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($pl_mac_adv_amt, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
}
if($esc_amt != 0){
	echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Escalation : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($esc_amt, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
}
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>GRAND TOTAL : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelbold' align='right' colspan='5'>".number_format($GrandTotal, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Less Previous Payment : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($dpm_total_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>Net Total : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelbold' align='right' colspan='5'>".number_format($NetTotal, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";

$ea = 1; $eb = 1; $ed = 1; 
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";  $ed_text = "<b><u>With hold Amount</u></b>";

if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none;' class='labelprint' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none;' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($lw_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}



if($mob_adv_amt_rec != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance (Rec.)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt_rec, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($pl_mac_adv_rec != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>P&M Advance (Rec.)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($pl_mac_adv_rec, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($adv_amt != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>75% Advance Rec.  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($other_recovery_1_amt != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Other Recovery  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_1_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}





if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($sgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>SGST @ ".number_format($sgst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($sgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($cgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>CGST @ ".number_format($cgst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($cgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($igst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IGST @ ".number_format($igst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($igst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}


if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($water_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($electricity_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_3 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_3_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_3, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_machine_equip != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_man_power != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($nonsubmission_qa != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($sd_amt != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Security Deposit @ ".$sd_percent."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($mob_adv_int_amt != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mob. Adv. Interest  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_int_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($pl_mac_adv_int_amt != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>P&M Adv. Interest : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($pl_mac_adv_int_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($TotalRecovery != 0)
{
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='3'></td><td style='border:none' class='labelbold' align='left' colspan='4'>Total Recovery</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelbold'>".number_format($TotalRecovery, 2, '.', '')."</td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'></td><td style='border:none' class='labelbold' align='right' colspan='4'>Total Recovery : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelbold'>&nbsp;&nbsp;".number_format($TotalRecovery, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
}

if($ChequeAmount != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'></td><td style='border:none' class='labelbold' align='right' colspan='4'>Net Payable Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelbold'>&nbsp;&nbsp;".number_format($ChequeAmount, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
}

$split_amt = explode(".",$ChequeAmount);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];
$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
echo "<tr style='border:none'><td style='border:none'>&nbsp;</td><td style='border:none'>&nbsp;</td><td style='border:none' class='labelprint' align='left' colspan='11'>Amount: (Rupees ".$amount_in_words.")</td></tr>";


/*$UpoDtSecAdvAmtMop = 0; $DeductPrevBillSecAdvAmtMop = 0; $ThisBillSecAdvAmtMop = 0; 
$SelectSecAdvMopQuery = "SELECT * from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
//echo $SelectSecAdvMopQuery;exit;
$SelectSecAdvMopSql   = mysqli_query($dbConn,$SelectSecAdvMopQuery);
if($SelectSecAdvMopSql == true){
	if(mysqli_num_rows($SelectSecAdvMopSql)>0){
		while($SecAdvMobList = mysqli_fetch_object($SelectSecAdvMopSql)){
			$UpoDtSecAdvAmtMop = $SecAdvMobList->upto_dt_ots_amt;
			$DeductPrevBillSecAdvAmtMop = $SecAdvMobList->ded_prev_ots_amt;
			$ThisBillSecAdvAmtMop = $SecAdvMobList->sec_adv_amount;
		}
	}
}
$UpoDtSecAdvAmtMop = round($UpoDtSecAdvAmtMop);
$DeductPrevBillSecAdvAmtMop = round($DeductPrevBillSecAdvAmtMop);
$ThisBillSecAdvAmtMop = round($ThisBillSecAdvAmtMop);
$ThisBillMobAdvAmtMop = 0; 
$SelectMobAdvMopQuery = "SELECT rbn, mob_adv_amount from mobilization_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectMobAdvMopSql   = mysqli_query($dbConn,$SelectMobAdvMopQuery);
if($SelectMobAdvMopSql == true){
	if(mysqli_num_rows($SelectMobAdvMopSql)>0){
		$MobAdvMobList = mysqli_fetch_object($SelectMobAdvMopSql);
		$ThisBillMobAdvAmtMop = $MobAdvMobList->mob_adv_amount;
	}
}
$ThisBillMobAdvAmtMop = round($ThisBillMobAdvAmtMop);

$SelectUptoDtQuery = "SELECT * from abstractbook where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectUptoDtSql   = mysqli_query($dbConn,$SelectUptoDtQuery);
if($SelectUptoDtSql == true){
	if(mysqli_num_rows($SelectUptoDtSql)>0){
		$UptoDtList = mysqli_fetch_object($SelectUptoDtSql);
		$UptoDtBillValue 		= $UptoDtList->upto_date_total_amount;
		$DeductPrevBillValue 	= $UptoDtList->dpm_total_amount;
		$SinceLastBillValue 	= $UptoDtList->slm_total_amount;
	}
}
$GrandTotal = $OverAllSlmDpmAmount + $ThisBillMobAdvAmtMop + $UpoDtSecAdvAmtMop;
$NetAmount = $OverAllSlmAmount + $sec_adv_amount_civil + $ThisBillMobAdvAmtMop;
$GrandTotal = round($GrandTotal);
$NetAmount = round($NetAmount);
//echo $sec_adv_amount_civil;exit;
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Mobilization Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($ThisBillMobAdvAmtMop, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto Date Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($UpoDtSecAdvAmtMop, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>GRAND TOTAL : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelbold' align='right' colspan='5'>".number_format($GrandTotal, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Since Last Bill Value : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
//// This is for Printing Escalation
$OverAllSlmAmount = $NetAmount;
if(count($EscQtrArray)>0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round(($OverAllSlmAmount+$Esc_Total_Amt),2);

//// This is for Printing Revised Escalation
if(count($RevEscQtrArray)>0)
{
	for($q2=0; $q2<count($RevEscQtrArray); $q2++)
	{
		$RevEQtr = $RevEscQtrArray[$q2];
		$RevETccAmt = $RevEscTccAmtArray[$q2];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Revised Escalation for Quarter - ".$RevEQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($RevETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round(($OverAllSlmAmount+$RevEsc_Total_Amt),2);



$Overall_net_amt_final = round(($OverAllSlmAmount + $total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = $Overall_net_amt_final;

echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none; border-top:1px dashed #000000' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none; border-top:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='3' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";



$ea = 1; $eb = 1; $ed = 1; 
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";  $ed_text = "<b><u>With hold Amount</u></b>";
if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none;' class='".$fclass3."' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none;' colspan='5' align='right' class='".$fclass3."'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass5."' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass5."'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($lw_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass8."' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass8."'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($mob_adv_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($sgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass28."' align='right' colspan='4'>SGST @ ".number_format($sgst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass28."'>&nbsp;&nbsp;".number_format($sgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($cgst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass30."' align='right' colspan='4'>CGST @ ".number_format($cgst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass30."'>&nbsp;&nbsp;".number_format($cgst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($igst_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass32."' align='right' colspan='4'>IGST @ ".number_format($igst_percent, 2, '.', '')."%  On Rs. ".$bill_amt_gst.": <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass32."'>&nbsp;&nbsp;".number_format($igst_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass10."' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass10."'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass12."' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass12."'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass14."' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass14."'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($water_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass25."' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass25."'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($electricity_charge != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass26."' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass26."'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass16."' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass16."'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass17."' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass17."'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass18."' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass18."'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass19."' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass19."'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_3 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass19."' align='right' colspan='4'>".$other_recovery_3_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass19."'>&nbsp;&nbsp;".number_format($other_recovery_3, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_machine_equip != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass22."' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass22."'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_man_power != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass23."' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass23."'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($nonsubmission_qa != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass27."' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass27."'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($sd_amt != 0)
{
$eb = 1;
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ec_text." (".$eb.")</td><td style='border:none' class='".$fclass1."' align='right' colspan='4'>Security Deposit @ ".$sd_percent."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass1."'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

if($rrcount>0)
{
	for($rrc=0; $rrc<$rrcount; $rrc++)
	{
	echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ed_text." (".$ed.")</td><td style='border:none' class='".$fclass1."' align='right' colspan='4'>".$RRDescArr[$rrc]." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass1."'>&nbsp;&nbsp;".number_format($RRDescAmt[$rrc], 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
	$ed++; $ed_text = "";
	}
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

$Overall_net_amt_final = round($Overall_net_amt_final);
if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
}

$split_amt = explode(".",$Overall_net_amt_final);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];
$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
echo "<tr style='border:none'><td style='border:none'>&nbsp;</td><td style='border:none'>&nbsp;</td><td style='border:none' class='labelprint' align='left' colspan='12'>Amount: (Rupees ".$amount_in_words.")</td></tr>";


*/



echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>page ".$page."</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";

//////////////////// MEMO OF PAYMENT ENDS HERE ////////////////////

if($is_finalbill == "Y"){
$CertCodeArr = array(); $CertDescArr = array(); $CertMBArr = array(); $CertPageArr = array();
echo "<p style='page-break-after:always;'></p>";
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
include("FinalBillInspectionCertificate.php");
echo "<p style='page-break-after:always;'></p>";
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
include("FinalBillNoClaimCertificate.php");
echo "<p style='page-break-after:always;'></p>";
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
include("FinalBillFinalCertificates.php");
echo "<p style='page-break-after:always;'></p>";
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
include("FinalBillFinalNotes.php");
echo "<p style='page-break-after:always;'></p>";
}
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
//echo "<p  style='page-break-after:always;'></p>";

for($x=0;$x<$emptypage;$x++)
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
	<td  align='left' class='labelsmall'>&nbsp;</td>
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
echo "<tr class='labelprint'><td colspan='13' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'> </td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}

//$staffid_acc 		= $_SESSION['sid_acc'];
//$staff_level_str 	= getstafflevel($staffid_acc);
//$exp_staff_level_str = explode("@#*#@",$staff_level_str);
//$staff_roleid 		= $exp_staff_level_str[0];
//$staff_levelid 		= $exp_staff_level_str[1];
$AccVerification = AccVerificationCheck($sheetid,$rbn,$mbookno,'abstract',$staff_levelid,'AB');
$AlStatusRes 	= AccountsLevelStatus($abstsheetid,$rbn,$abstmbno,0,'A','abstract');//($sheetid,$rbn);
$AcLevel 	= $AlStatusRes[0];
$AcStatus 	= $AlStatusRes[1];
$EndLevel 	= $AlStatusRes[2];
$ABSCheck 	= $AlStatusRes[4];

$DialogBtnView = 0;
?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo $page; ?>" />
<input type="hidden" name="txt_abstractstr" id="txt_abstractstr" value="<?php echo $AbstractStr; ?>" />
<input type="hidden" name="txt_subdivid_slmstr" id="txt_subdivid_slmstr" value="<?php echo $SubdividSlmStr; ?>" />

<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />

<input type="hidden" name="txt_rbn_no" id="txt_rbn_no" value="<?php echo $runn_acc_bill_no; ?>" />
<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $abstsheetid; ?>"/>
<input type="hidden" name="txt_zone_id" id="txt_zone_id" value="<?php echo $zone_id; ?>"/>
<input type="hidden" name="txt_linkid" id="txt_linkid" value="<?php echo $linkid; ?>"/>
<input type="hidden" name="txt_mbook_no" id="txt_mbook_no" value="<?php echo $abstmbno; ?>"/>
<input type="hidden" name="txt_acc_remarks_count" id="txt_acc_remarks_count" value="<?php echo $acc_remarks_count; ?>"/>
<input type="hidden" name="txt_staffid_acc" id="txt_staffid_acc" value="<?php echo $staffid_acc; ?>"/>
<input type="hidden" name="txt_staff_levelid_acc" id="txt_staff_levelid_acc" value="<?php echo $staff_levelid; ?>"/>


<input type="hidden" name="txt_OverAllSlmDpmAmount" id="txt_OverAllSlmDpmAmount" value="<?php echo $OverAllSlmDpmAmount; ?>"/>
<input type="hidden" name="txt_OverAllDpmAmount" id="txt_OverAllDpmAmount" value="<?php echo $OverAllDpmAmount; ?>"/>
<input type="hidden" name="txt_OverAllSlmAmount" id="txt_OverAllSlmAmount" value="<?php echo $OverAllSlmAmount; ?>"/>

<input type="hidden" name="txt_SlmDpmNetAmount" id="txt_SlmDpmNetAmount" value="<?php echo $SlmDpmNetAmount; ?>"/>
<input type="hidden" name="txt_DpmNetAmount" id="txt_DpmNetAmount" value="<?php echo $DpmNetAmount; ?>"/>
<input type="hidden" name="txt_SlmNetAmount" id="txt_SlmNetAmount" value="<?php echo $SlmNetAmount; ?>"/>

<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>"/>
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


		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="submit" class="backbutton" name="Back" value=" Back " /> 
			</div>
			<!--<div class="buttonsection">
			<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
			</div>-->
			<?php
			//if($Abst_check_view == 0)
			//{
			//echo $AcLevel;exit;
				$TranRes = AccountsLevelTransaction($abstsheetid,$rbn,$_SESSION['levelid']);
				$FWRoleName = GetRoleName($TranRes['Next'],$_SESSION['staff_section']);
				$BWRoleName = GetRoleName($TranRes['Prev'],$_SESSION['staff_section']);
				if(($AccVerification == 0)&&($AcLevel == $_SESSION['levelid']) && ($AcStatus != 'A')){// && ($EndLevel == $AcLevel)){ 
					//echo $ABSCheck;exit;
					if(($TranRes['Check'] == 1)&&($TranRes['Curr'] == $_SESSION['levelid'])&&($ABSCheck == 0)){
						$DialogBtnView = 1;
			?>
					<input type="hidden" name="txt_fw_level" id="txt_fw_level" value="<?php echo $TranRes['Next']; ?>" />
					<input type="hidden" name="txt_bw_level" id="txt_bw_level" value="<?php echo $TranRes['Prev']; ?>" />
					<input type="hidden" name="txt_min_level" id="txt_min_level" value="<?php echo $TranRes['Min']; ?>" />
					<input type="hidden" name="txt_max_level" id="txt_max_level" value="<?php echo $TranRes['Max']; ?>" />
			
					<?php if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] != $_SESSION['levelid'])){ ?>
						<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
						<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
					<?php }else if(($TranRes['Max'] == $_SESSION['levelid'])&&($TranRes['Min'] != $_SESSION['levelid'])){ ?>
						<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Abstract " /></div>-->
						<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
					<?php }else if(($_SESSION['levelid'] > $TranRes['Min'])&&($_SESSION['levelid'] < $TranRes['Max'])){ ?>
						<div class="btn_inside_sect"><input type="submit" class="backbutton" name="backward" id="backward" value=" Return to  <?php echo $BWRoleName; ?>" /></div>
						<div class="btn_inside_sect"><input type="submit" class="backbutton" name="forward" id="forward" value=" Forward to <?php echo $FWRoleName; ?>" /></div>
					<?php }else if(($TranRes['Min'] == $_SESSION['levelid'])&&($TranRes['Max'] == $_SESSION['levelid'])){ ?>
						<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Abstract " /></div>-->
						<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
			<?php 		  }else{
							// Nothing will be displayed here. So it will be Empty
						  }
			
					}
				} 
			//}
				if(($AccVerification == 0)&&($ABSCheck == 0)&&($_SESSION['levelid'] >= $DecMinHighLevelRet)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
					<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>
					<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Abstract " /></div>-->
				
		  <?php }
				if(($AccVerification == 0)&&($ABSCheck == 0)&&($_SESSION['levelid'] >= $DecMinHighLevel)&&($_SESSION['levelid'] >= $TranRes['Curr'])){ ?>
					<!--<div class="btn_inside_sect"><input type="submit" class="backbutton" name="send_to_civil" id="send_to_civil" value=" Return to EIC " /></div>-->
					<div class="btn_inside_sect"><input type="submit" class="backbutton" name="accept" id="accept" value=" Accept Abstract " /></div>
				
		  <?php } ?>
		</div>

		<!-- modal content -->
		<div id="basic-modal-content">
			<div align="center" class="popuptitle gradientbg">Accounts Comment Section</div>
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
						<input type="text" name="txt_from_date" id="txt_from_date" size="12" class="popuptextbox" value="<?php echo date("d/m/Y", strtotime($fromdate)); ?>" />
					To :
						<input type="text" name="txt_to_date" id="txt_to_date" size="12" class="popuptextbox" value="<?php echo date("d/m/Y", strtotime($todate)); ?>" />
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
			<div style="padding-top:10px; height:222px;">
				<div style="float:left; width:567px; height:220px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="94%" id="table3">
					<tr bgcolor="#0A9CC5" style="color:#FFFFFF">
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
						<td align="left"><input type="text" name="txt_partpay_total_paidamt_dpm" readonly="" id="txt_partpay_total_paidamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
						<td colspan=""></td>
						<td colspan=""><input type="text" name="txt_partpay_total_payableamt_dpm" readonly="" id="txt_partpay_total_payableamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
					</tr>
					<tr>
						<td colspan="7">Remarks:<br/><textarea name="txt_dpm_remarks" readonly="readonly" id="txt_dpm_remarks" rows="3" style="width:519px;"></textarea>
						</td>
					</tr>
				</table>
				</div>
				<div style="float:right;  width:427px; height:220px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="93%" id="table4">
						<tr bgcolor="#0A9CC5" style="color:#FFFFFF">
							<td align="center" colspan="5">Since Last Measurement</td>
						</tr>
						<tr>
							<td align="left" colspan="5" bgcolor="#f2efef">
							Since Last Measurement Quantity&nbsp;:&nbsp;
							<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="13" readonly="" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
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
							<input type="text" name="txt_partpay_qty_slm[]" id="txt_partpay_qty_slm0" readonly="" class="dynamictextbox" style="text-align:right; width:93px; border: 1px solid #2aade4;" onblur="ValidateSlm(); calculateAmount(this,0,'qty','slm');" />
							</td>
							<td width="63px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_item_rate_slm" readonly="" id="txt_item_rate_slm0" readonly="" class="dynamictextbox" style="text-align:right; width:80px;" onblur="calculateAmount(this,0,'rate','slm');" />
							</td>
							<td width="23px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_percent_slm" id="txt_partpay_percent_slm0" readonly="" class="dynamictextbox" style="text-align:right; width:40px; border: 1px solid #2aade4;" onblur="ValidatePercent(this,'slm',0); calculateAmount(this,0,'percent','slm');" />
							</td>
							<td width="50px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_amt_slm[]" id="txt_partpay_amt_slm0" readonly="" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center" class="dynamicrowcell" style="text-align:center;">
							<input type="button" name="btn_add_row_slm" id="btn_add_row_slm" disabled="disabled" class="editbtnstyle" value=" + " style="width:32px; text-align:center; font-weight:bold; border-radius: 0px;" onclick="addRow();" />
							<input type="hidden" name="hid_slm_result[]" id="hid_slm_result0" class="dynamictextbox" />
							</td>
						</tr>
						<tr>
							<td width="147px" colspan="3" align="right">Total Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
							<td width="50px" align="right"  class="dynamicrowcell">
							<input type="text" name="txt_partpay_total_amt_slm" id="txt_partpay_total_amt_slm"  readonly="" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="5">Remarks:<br/><textarea name="txt_slm_remarks" readonly="readonly" id="txt_slm_remarks" rows="3" style="width:375px;"></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div align="right" style="border:1px solid #DFDFDF">
				<table width="100%" height="37" class="label" cellpadding="" cellspacing="">
					<tr>
					<td align="center" width="440px">
					<label style="background:#EAEAEA; padding:6px;">Over All Total Amount</label>&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;
					<input type="text" name="txt_overall_total" id="txt_overall_total" readonly="" size="20" class="dynamictextbox dynamictextbox2" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					</tr>
				</table>
			</div>
			<div align="right" style="border-bottom:1px solid #DFDFDF;">
				<table width="100%" height="87" class="label" cellpadding="" cellspacing="">
					<tr>
					<td align="left" width="440px" style="">
						Accounts Comments:<br/>
						<textarea name="txt_accounts_remarks" id="txt_accounts_remarks" placeholder="Enter your comment here..." class="dynamictextbox2 label" rows="3" style="width:983px;"></textarea>					</td>
					</tr>
				</table>
			</div>
			<div class="bottomsection" align="center">
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="buttonstyle" onclick="SaveData_Accounts()" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="buttonstyle" onclick="CancelData()" /></div>
			</div>
		</div>
		
		<!-- preload the images -->
		<!--<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>     -->
		<!--<div id="basic-modal-content_memo_payment">
			<div align="center" class="popuptitle gradientbg">Accounts Remarks Section</div>
			<div align="center" style="border-bottom:1px solid #DFDFDF; line-height:25px;" class="label">
				Memo of Payment Remarks - Section
			</div>
			<table id="table_memo_payment" cellpadding="5" cellspacing="5">
				<tr>
					<td>Upto date value of work done</td>
					<td>29932626.00</td>
					<td><input type="text" name="txt_uptodate_amount" id="txt_uptodate_amount"  /></td>
				</tr>
				<tr>
					<td>Secured Advance</td>
					<td>37163656.00</td>
					<td><input type="text" name="txt_sa_amount" id="txt_sa_amount"  /></td>
				</tr>
				<tr>
					<td>Deduct Previous Paid</td>
					<td>0.00</td>
					<td><input type="text" name="txt_dpm_paid_amount" id="txt_dpm_paid_amount"  /></td>
				</tr>
			</table>
		</div>-->
		<!--<input type="button" name="my-button"id="my-button" value="click">-->
<!-- Element to pop up -->

<?php 
/*$UpoDtSecAdvAmtMop = 0; $DeductPrevBillSecAdvAmtMop = 0; $ThisBillSecAdvAmtMop = 0; 
$SelectSecAdvMopQuery = "SELECT rbn, sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn <= '$rbn'";
$SelectSecAdvMopSql   = mysqli_query($dbConn,$SelectSecAdvMopQuery);
if($SelectSecAdvMopSql == true){
	if(mysqli_num_rows($SelectSecAdvMopSql)>0){
		while($SecAdvMobList = mysqli_fetch_object($SelectSecAdvMopSql)){
			$UpoDtSecAdvAmtMop = $UpoDtSecAdvAmtMop + $SecAdvMobList->sec_adv_amount;
			if($rbn == $SecAdvMobList->rbn){
				$ThisBillSecAdvAmtMop = $SecAdvMobList->sec_adv_amount;
			}else{
				$DeductPrevBillSecAdvAmtMop = $DeductPrevBillSecAdvAmtMop + $SecAdvMobList->sec_adv_amount;
			}
		}
	}
}
//echo $SelectSecAdvMopQuery;exit;
$ThisBillMobAdvAmtMop = 0; 
$SelectMobAdvMopQuery = "SELECT rbn, mob_adv_amount from mobilization_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectMobAdvMopSql   = mysqli_query($dbConn,$SelectMobAdvMopQuery);
if($SelectMobAdvMopSql == true){
	if(mysqli_num_rows($SelectMobAdvMopSql)>0){
		$MobAdvMobList = mysqli_fetch_object($SelectMobAdvMopSql);
		$ThisBillMobAdvAmtMop = $MobAdvMobList->mob_adv_amount;
	}
}

$SelectUptoDtQuery = "SELECT * from abstractbook where sheetid = '$abstsheetid' and rbn = '$rbn'";
$SelectUptoDtSql   = mysqli_query($dbConn,$SelectUptoDtQuery);
if($SelectUptoDtSql == true){
	if(mysqli_num_rows($SelectUptoDtSql)>0){
		$UptoDtList = mysqli_fetch_object($SelectUptoDtSql);
		$UptoDtBillValue 		= $UptoDtList->upto_date_total_amount;
		$DeductPrevBillValue 	= $UptoDtList->dpm_total_amount;
		$SinceLastBillValue 	= $UptoDtList->slm_total_amount;
	}
}
$GrandTotal = $OverAllSlmDpmAmount + $ThisBillMobAdvAmtMop + $UpoDtSecAdvAmtMop;
$NetAmount = $OverAllSlmAmount + $sec_adv_amount_civil + $ThisBillMobAdvAmtMop;*/
?>
		<div id="element_to_pop_up">
			<div class="popup-content">
    			<a class="b-close"><img src="images/fancy_close.png" /></a>
				<div align="center" class="popuptitle gradientbg">Memo of Payment (Accounts Section - Edit)</div>
				<!--<div align="center" style="border-bottom:1px solid #DFDFDF; line-height:25px;" class="label">
					Memo of Payment Remarks - Section
				</div>-->
				<table id="table_memo_payment" width="95%" cellpadding="3" cellspacing="3" class="label memo_label table2" align="center">
					<!--<tr class="gradientbg" style="height:20px; color:#FFFFFF">-->
					<tr bgcolor="#CDCDCD">
						<td align="center">Recovery Description<br/></td>
						<td align="center">Civil Value ( <i class='fa fa-inr' style=' width:4px; height:5px;'></i> )</td>
						<td align="center">Edited Value ( <i class='fa fa-inr' style=' width:4px; height:5px;'></i> )</td>
					</tr>
					<tr>
						<td>Upto date value of work done</td>
						<td align="right">
						<input type="text" name="hid_uptodate_amount" id="hid_uptodate_amount" readonly="" value="<?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_uptodate_amount" id="txt_uptodate_amount" readonly="" value="<?php echo number_format($OverAllSlmDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<tr>
						<td>Mobilization Advance</td>
						<td align="right">
						<input type="text" name="hid_mobadv_amount" id="hid_mobadv_amount" readonly="" value="<?php echo number_format($ThisBillMobAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_mobadv_amount" id="txt_mobadv_amount" readonly="" value="<?php echo number_format($ThisBillMobAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<tr>
						<td>Upto Date Secured Advance</td>
						<td align="right">
						<input type="text" name="hid_uptodt_secadv_amount" id="hid_uptodt_secadv_amount" readonly="" value="<?php echo number_format($UpoDtSecAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_uptodt_secadv_amount" id="txt_uptodt_secadv_amount" readonly="" value="<?php echo number_format($UpoDtSecAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					
					<tr>
						<td><u>GRAND TOTAL</u></td>
						<td align="right">
						<input type="text" name="hid_uptodt_grand_tot" id="hid_uptodt_grand_tot" readonly="" value="<?php echo number_format($GrandTotal, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_uptodt_grand_tot" id="txt_uptodt_grand_tot" readonly="" value="<?php echo number_format($GrandTotal, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					
					
					<tr>
						<td>Deduct Previous Bill Value</td>
						<td align="right">
						<input type="text" name="hid_dpm_paid_amount" readonly="" id="hid_dpm_paid_amount" value="<?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_dpm_paid_amount" readonly="" id="txt_dpm_paid_amount" value="<?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<!--<tr>
						<td>Deduct Previous Secured Advance</td>
						<td align="right">
						<input type="text" name="hid_dpm_paid_amount" readonly="" id="hid_dpm_paid_amount" value="<?php echo number_format($DeductPrevBillSecAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_dpm_paid_amount" readonly="" id="txt_dpm_paid_amount" value="<?php echo number_format($DeductPrevBillSecAdvAmtMop, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>-->
					<tr>
						<td>Since Last Bill Value</td>
						<td align="right">
						<input type="text" name="hid_slm_paid_amount" readonly="" id="hid_slm_paid_amount" value="<?php echo number_format($SlmNetAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_slm_paid_amount" readonly="" id="txt_slm_paid_amount" value="<?php echo number_format($SlmNetAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<tr>
						<td>Since Last Secured Advance</td>
						<td align="right">
						<input type="text" name="hid_sa_amount" readonly="" id="hid_sa_amount" value="<?php echo number_format($sec_adv_amount_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_sa_amount" readonly="" id="txt_sa_amount" value="<?php echo number_format($sec_adv_amount_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<tr>
						<td><u>NET AMOUNT</u></td>
						<td align="right">
						<input type="text" name="hid_net_amount" readonly="" id="hid_net_amount" value="<?php echo number_format($NetAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_net_amount" readonly="" id="txt_net_amount" value="<?php echo number_format($NetAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
					</tr>
					<!--<tr>
						<td>Secured Advance</td>
						<td align="right">
						<input type="text" name="hid_sa_amount" id="hid_sa_amount"  readonly="" value="<?php echo number_format($sec_adv_amount_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_sa_amount" id="txt_sa_amount" onblur="SecAdvance_Change_Amount();" value="<?php echo number_format($sec_adv_amount, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>-->
					<tr>
						<td colspan="2" bgcolor="#CCCCCC">Recoveries</td><td></td>
					</tr>
					
					<?php 
					if($GstIncExe == "E"){
						$AmtForGstCalc 	= $NetAmount;
						$GstAmount 		= round(($AmtForGstCalc*$GstPercRate/100),2);
					}else{
						$AmtForGstCalc 	= round(($NetAmount*100/($GstPercRate+100)),2);
						$GstAmount 		= round((($AmtForGstCalc*$GstPercRate)/100),2);
					}
					?>
					<tr>
						<td>SGST @ 
						<input type="text" id="hid_sgst_perc" name="hid_sgst_perc" readonly="" value="<?php echo number_format($sgst_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						%:</td>
						<td align="right">
						<input type="text" name="hid_sgst" id="hid_sgst" readonly="" value="<?php echo number_format($sgst_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_sgst_perc" name="txt_sgst_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($sgst_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_sgst" id="txt_sgst" value="<?php echo number_format($sgst_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						
						<input type="hidden" name='txt_gst_rate' id='txt_gst_rate' readonly="" class="textboxdisplay textright" value="<?php echo $GstPercRate; ?>" style="width: 120px;">
						<input type="hidden" name='txt_gst_amt' id='txt_gst_amt' readonly="" class="textboxdisplay textright" value="<?php echo $GstAmount; ?>" style="width: 120px;">
						<input type="hidden" name='txt_pan_type' id='txt_pan_type' readonly="" class="textboxdisplay textright" value="<?php echo $PanType; ?>" style="width: 120px;">
						<input type="hidden" name='txt_is_ldc' id='txt_is_ldc' readonly="" class="textboxdisplay textright" value="<?php echo $IsLdcAppl; ?>" style="width: 120px;">
						<input type="hidden" name='txt_amt_for_gst' id='txt_amt_for_gst' readonly="" class="textboxdisplay textright" value="<?php echo $AmtForGstCalc; ?>" style="width: 120px;">
						</td>
					</tr>
					<tr>
						<td>CGST @ 
						<input type="text" id="hid_cgst_perc" name="hid_cgst_perc" readonly="" value="<?php echo number_format($cgst_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						%:</td>
						<td align="right">
						<input type="text" name="hid_cgst" id="hid_cgst" readonly="" value="<?php echo number_format($cgst_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_cgst_perc" name="txt_cgst_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($cgst_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_cgst" id="txt_cgst" value="<?php echo number_format($cgst_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>IGST @ 
						<input type="text" id="hid_igst_perc" name="hid_igst_perc" readonly="" value="<?php echo number_format($igst_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						%:</td>
						<td align="right">
						<input type="text" name="hid_igst" id="hid_igst" readonly="" value="<?php echo number_format($igst_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_igst_perc" name="txt_igst_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($igst_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_igst" id="txt_igst" value="<?php echo number_format($igst_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>W.C.T @ 
						<input type="text" id="hid_wct_perc" name="hid_wct_perc" readonly="" value="<?php echo number_format($wct_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						%:</td>
						<td align="right">
						<input type="text" name="hid_wct" id="hid_wct" readonly="" value="<?php echo number_format($wct_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_wct_perc" name="txt_wct_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($wct_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_wct" id="txt_wct" value="<?php echo number_format($wct_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>VAT @ 
						<input type="text" id="hid_vat_perc" name="hid_vat_perc" readonly="" value="<?php echo number_format($vat_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						%:</td>
						<td align="right">
						<input type="text" name="hid_vat" id="hid_vat" readonly="" value="<?php echo number_format($vat_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_vat_perc" name="txt_vat_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($vat_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_vat" id="txt_vat" value="<?php echo number_format($vat_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Labour Welfare CESS @ 
						<input type="text" id="hid_lw_cess_perc" name="hid_lw_cess_perc" readonly="" value="<?php echo number_format($lw_cess_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% : </td>
						<td align="right">
						<input type="text" name="hid_lw_cess" id="hid_lw_cess" readonly="" value="<?php echo number_format($lw_cess_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_lw_cess_perc" name="txt_lw_cess_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($lw_cess_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_lw_cess" id="txt_lw_cess" value="<?php echo number_format($lw_cess_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Mobilization Advance @ 
						<input type="text" id="hid_mob_adv_perc" name="hid_mob_adv_perc" readonly="" value="<?php echo number_format($mob_adv_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% :</td>
						<td align="right">
						<input type="text" name="hid_mob_adv" id="hid_mob_adv" readonly="" value="<?php echo number_format($mob_adv_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_mob_adv_perc" name="txt_mob_adv_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($mob_adv_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_mob_adv" id="txt_mob_adv" value="<?php echo number_format($mob_adv_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Income Tax @ 
						<input type="text" id="hid_incometax_perc" name="hid_incometax_perc" readonly="" value="<?php echo number_format($incometax_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% :</td>
						<td align="right">
						<input type="text" name="hid_incometax" id="hid_incometax" readonly="" value="<?php echo number_format($incometax_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_incometax_perc" name="txt_incometax_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($incometax_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_incometax" id="txt_incometax" value="<?php echo number_format($incometax_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>IT Cess @ 
						<input type="text" id="hid_ITcess_perc" name="hid_ITcess_perc" readonly="" value="<?php echo number_format($it_cess_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% :</td>
						<td align="right">
						<input type="text" name="hid_ITcess" id="hid_ITcess" readonly="" value="<?php echo number_format($it_cess_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_ITcess_perc" name="txt_ITcess_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($it_cess_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_ITcess" id="txt_ITcess" value="<?php echo number_format($it_cess_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>IT Education CESS @ 
						<input type="text" id="hid_ITEcess_perc" name="hid_ITEcess_perc" readonly="" value="<?php echo number_format($it_edu_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% : </td>
						<td align="right">
						<input type="text" name="hid_ITEcess" id="hid_ITEcess" readonly="" value="<?php echo number_format($it_edu_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_ITEcess_perc" name="txt_ITEcess_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($it_edu_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_ITEcess" id="txt_ITEcess" value="<?php echo number_format($it_edu_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Electricity Charges (as per Bill enclosed) :</td>
						<td align="right">
						<input type="text" name="hid_elect_charge" id="hid_elect_charge" readonly="" value="<?php echo number_format($electricity_charge_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_elect_charge" id="txt_elect_charge" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($electricity_charge, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Water Charges (as per Bill enclosed) :</td>
						<td align="right">
						<input type="text" name="hid_water_charge" id="hid_water_charge" readonly="" value="<?php echo number_format($water_charge_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_water_charge" id="txt_water_charge" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($water_charge, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
	
					<tr>
						<td>Non Deployment of machineries & equipment<br/> as (per clause 18) :</td>
						<td align="right">
						<input type="text" name="hid_non_dep_me" id="hid_non_dep_me" readonly="" value="<?php echo number_format($non_dep_machine_equip_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_non_dep_me" id="txt_non_dep_me" onblur="Recovery_Change_Amount();" value="<?php echo number_format($non_dep_machine_equip, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Non Deployment of Technical manpower<br/> (as per clause 36(i)) :</td>
						<td align="right">
						<input type="text" name="hid_non_dep_tm" id="hid_non_dep_tm" readonly="" value="<?php echo number_format($non_dep_man_power_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_non_dep_tm" id="txt_non_dep_tm" onblur="Recovery_Change_Amount();" value="<?php echo number_format($non_dep_man_power, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Non Submission of QA related document :</td>
						<td align="right">
						<input type="text" name="hid_nonsubmission_qa" id="hid_nonsubmission_qa" readonly="" value="<?php echo number_format($nonsubmission_qa_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_nonsubmission_qa" id="txt_nonsubmission_qa" onblur="Recovery_Change_Amount();" value="<?php echo number_format($nonsubmission_qa, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
	
					<tr>
						<td>Rent for Land : </td>
						<td align="right">
						<input type="text" name="hid_rent_land" id="hid_rent_land" readonly="" value="<?php echo number_format($land_rent_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_rent_land" id="txt_rent_land" onblur="Recovery_Change_Amount();" value="<?php echo number_format($land_rent, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Liquidated Damages :</td>
						<td align="right">
						<input type="text" name="hid_liquid_damage" id="hid_liquid_damage" readonly="" value="<?php echo number_format($liquid_damage_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_liquid_damage" id="txt_liquid_damage" onblur="Recovery_Change_Amount();" value="<?php echo number_format($liquid_damage, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>
						<input type="text" name="txt_other_recovery_1_desc" id="txt_other_recovery_1_desc" class="label memo_pecrcenttextbox" value="<?php echo $other_recovery_1_desc; ?>" style="width:98%; text-align:left" />
						</td>
						<td align="right">
						<input type="text" name="hid_other_recovery_1" id="hid_other_recovery_1" readonly="" value="<?php echo number_format($other_recovery_1_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_other_recovery_1" id="txt_other_recovery_1" onblur="Recovery_Change_Amount();" value="<?php echo number_format($other_recovery_1, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>
						<input type="text" name="txt_other_recovery_2_desc" id="txt_other_recovery_2_desc" class="label memo_pecrcenttextbox" value="<?php echo $other_recovery_2_desc; ?>" style="width:98%; text-align:left" />
						</td>
						<td align="right">
						<input type="text" name="hid_other_recovery_2" id="hid_other_recovery_2" readonly="" value="<?php echo number_format($other_recovery_2_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_other_recovery_2" id="txt_other_recovery_2" onblur="Recovery_Change_Amount();" value="<?php echo number_format($other_recovery_2, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					<tr>
						<td>Security Deposit @ 
						<input type="text" id="hid_sd_perc" name="hid_sd_perc" readonly="" value="<?php echo number_format($sd_percent_civil, 2, '.', ''); ?>" class="label hiddenpercentbox" />
						% :</td>
						<td align="right">
						<input type="text" name="hid_sd" id="hid_sd" readonly="" value="<?php echo number_format($sd_amt_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" id="txt_sd_perc" name="txt_sd_perc" onblur="Recovery_Change_Percent(this)" value="<?php echo number_format($sd_percent, 2, '.', ''); ?>" class="label memo_pecrcenttextbox" />
						<input type="text" name="txt_sd" id="txt_sd" value="<?php echo number_format($sd_amt, 2, '.', ''); ?>" class="label memo_textbox"/>
						</td>
					</tr>
					
			<?php
			if($rrcount>0)
			{
				for($rrc=0; $rrc<$rrcount; $rrc++)
				{
			?>	
					<tr>
						<td>
						<input type="text" name="txt_rec_rel_desc<?php echo $$rrc; ?>" id="txt_rec_rel_desc<?php echo $rrc; ?>" class="label memo_pecrcenttextbox" value="<?php echo $RRDescCivArr[$rrc]; ?>" style="width:98%; text-align:left" />
						</td>
						<td align="right">
						<input type="text" name="txt_rec_rel_amt_civil<?php echo $rrc; ?>" id="txt_rec_rel_amt_civil<?php echo $rrc; ?>" readonly="" value="<?php echo number_format($RRAmtCivArr[$rrc], 2, '.', ''); ?>" class="label hiddenbox"/>
						</td>
						<td align="right">
						<input type="text" name="txt_rec_rel_amt<?php echo $rrc; ?>" id="txt_rec_rel_amt<?php echo $rrc; ?>" onblur="Recovery_Change_Amount();" value="<?php echo number_format($RRAmtAccArr[$rrc], 2, '.', ''); ?>" class="label memo_textbox"/>
						<input type="hidden" name="txt_reid<?php echo $rrc; ?>" id="txt_reid<?php echo $rrc; ?>" value="<?php echo $RRIdArr[$rrc]; ?>" />
						</td>
					</tr>
			<?php
				}
			}
			?>	
			<input type="hidden" name="txt_rec_rel_cnt" id="txt_rec_rel_cnt" value="<?php echo $rrcount; ?>" />
					
					<tr bgcolor="#E6E6E6" class="label">
						<td>Net Payable Amount</td>
						<td align="right">
						<input type="text" name="hid_net_payable_amt" id="hid_net_payable_amt" readonly="" value="<?php echo number_format($Overall_net_amt_final_civil, 2, '.', ''); ?>" class="label hiddenbox" style="text-align:right"/>
						</td>
						<td align="right">
						<input type="text" name="txt_net_payable_amt" id="txt_net_payable_amt" readonly="" value="<?php echo number_format($Overall_net_amt_final, 2, '.', ''); ?>" class="label" style="text-align:right"/>
						</td>
					</tr>
					<tr bgcolor="#E6E6E6" class="label">
						<td align="center" colspan="3">
							<input type="button" name="btn_save_accounts_memo" id="btn_save_accounts_memo" class="buttonstyle" value="Save" onclick="SaveData_Accounts_Memo();" />
						</td>
						<!--<td colspan="2" align="left">
							<input type="button" name="btn_cancel_accounts_memo" id="btn_cancel_accounts_memo" class="buttonstyle" value="Cancel" />
						</td>-->
					</tr>
				</table>
			</div>
		</div>
<?php //echo number_format($incometax_amt, 2, '.', '');; ?>
		
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
<style>
.hiddenbox
{
	border:none;
	width:99%;
	text-align:right;
	cursor:default;
	font-size:11px;
	height:20px;
}
.hiddenpercentbox
{
	text-align:center;
	height:20px;
	font-size:11px;
	cursor:default;
	width:40px;;
	border:none;
	color:#C40500;
}
</style>
<script type="text/javascript">
	$(function(){ 
		var textBoxStr = "<?php echo $DIEITextBoxStr; ?>";
		if(textBoxStr != "")
		{
			var splitval = textBoxStr.split("*"); //alert(splitval.length);
			var x=0;
			for(x=0;x<splitval.length;x+=3)
			{
				document.getElementById("txt_co_di_ei"+splitval[x]).value = "C/o to page "+splitval[x+1]+"/General MB No. "+splitval[x+2]; 
			}
		}
   });
</script>
</html>