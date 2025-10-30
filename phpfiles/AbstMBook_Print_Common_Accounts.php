<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php'; 
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
include "spellnumber.php";
$msg = ''; $Line = 0;
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '/' . $mm . '/' . $yy;
}
$staffid_acc 			= $_SESSION['sid_acc'];
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
	$update_pageno_sql = "update measurementbook_temp set abstmbookno = '$abstmbno', abstmbpage = '$page' where sheetid	= '$abstsheetid' AND subdivid = '$subdivid'";
	$update_pageno_query = mysql_query($update_pageno_sql);
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
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbno = $Listmbdetail->abstmbookno;
	$staffid 			= $Listmbdetail->staffid;
	$abstmbpage_query 	= 	"select mbpage, allotmentid from mbookallotment WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstmbno'";
	$abstmbpage_sql 	= 	mysql_query($abstmbpage_query);
	$Listmbook 			= 	mysql_fetch_object($abstmbpage_sql);
	$abstmbpage 		= 	$Listmbook->mbpage+1; $abs_mbno_id = $Listmbook->allotmentid;
}
//echo $abstmbpage_query;
$paymentpercent = 	$_SESSION["paymentpercent"];	$emptypage 	= $_SESSION['emptypage'];

if($emptypage == "")
{
	$emptypage = 0;
}
$empty_page_update_sql = "update mymbook set emptypage = '$emptypage' where sheetid = '$abstsheetid' and mbno = '$abstmbno' and  mtype = 'A' and rbn = '$rbn' and genlevel = 'abstract'";
$empty_page_update_query = mysql_query($empty_page_update_sql);


if($_POST["Submit"] == "Confirm")
{	
	
	
	$AbstractStr 			= 	$_POST['txt_abstractstr'];
	$SubdividSlmStr 		= 	$_POST['txt_subdivid_slmstr'];
	$runningbillno 			= 	$_POST['txt_rbn_no'];
	
	//$select_mymbook_sql = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$runningbillno' ORDER BY mtype, mbookorder ASC";
	//$select_mymbook_sql = "select distinct(mbno) as mbookno from mymbook a INNER JOIN (SELECT MAX(endpage), mbno AS maxpage
   // FROM mymbook) b ON where a.mbno = b.mbno and a.rbn = '$runningbillno' and a.sheetid = '$abstsheetid'";
	//$select_mymbook_sql = "select * from (SELECT distinct(mbno) FROM mymbook a where sheetid = '$abstsheetid' and rbn = '$runningbillno') mymbook";
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
										//echo $insert_mbook_dummy_sql."<br/>";
										//echo $SubdividSlmStr."<br/>";
										//exit;

	$max_page_abs 			= 	$_POST['txt_maxpage'];
	$abstmbno 				= 	$_POST['txt_abstmbno'];
										//$mbook_start_page_abs 	= 	get_mbook_startpage($abstmbno,$abstsheetid);
										//$start_page_abs 		= 	explode('*', $mbook_start_page_abs);
										//$insert_mybmook_sql_3 	= 	"insert into mymbook set allotmentid = '$start_page_abs[1]', mbno = '$abstmbno', startpage = '$start_page_abs[0]', endpage = '$max_page_abs', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = 'A'";
										//$insert_mybmook_query_3 = 	mysql_query($insert_mybmook_sql_3);
	/*$update_asb_maxpage 	= 	"update mbookallotment set mbpage = '$max_page_abs' WHERE allotmentid	= '$abs_mbno_id' AND sheetid = '$abstsheetid'";
	$update_asb_maxpage_sql = 	mysql_query($update_asb_maxpage);
	$oldmbook_query 		= 	"SELECT * from oldmbook WHERE sheetid = '$abstsheetid'";
	$oldmbook_sql 			= 	mysql_query($oldmbook_query);
	if(mysql_num_rows($oldmbook_sql)>0)
	{
		while($res = mysql_fetch_array($oldmbook_sql))
		{
			$mbno 								= 	$res['mbname'];
			$mbooktype 							= 	$res['mbook_type'];
			$update_mbookallot_query 			= 	"UPDATE mbookallotment set active = '0' WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND allotmentid = '".$res['old_id']."'";
			$update_mbookallot_sql 	 			= 	mysql_query($update_mbookallot_query);
			$update_aggreement_mbookallot_query = 	"UPDATE agreementmbookallotment set active = '0' WHERE sheetid = '$abstsheetid' AND allotmentid = '".$res['old_id']."'";
			$update_aggreement_mbookallot_sql 	= 	mysql_query($update_aggreement_mbookallot_query); 
			$oldmbook  		   				   .= 	$res['mbname']."*"; 
										//$mbook_start_page_old 				= 	get_mbook_startpage($mbno,$abstsheetid);
										//$start_page_old 					= 	explode('*', $mbook_start_page_old);
										//$insert_mybmook_sql 				= 	"insert into mymbook set allotmentid = '$start_page_old[1]', mbno = '$mbno', startpage = '$start_page_old[0]', endpage = '100', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = '$mbooktype'";	
										//$insert_mybmook_query 				= 	mysql_query($insert_mybmook_sql);
		} 
	} */
    $currentquantity 			= 	trim($_POST['currentquantity']);
	$mbookquery					=	"INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks) SELECT  now(), staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks FROM measurementbook_temp WHERE sheetid = '$abstsheetid'";// WHERE flag =1 OR flag = 2";
   	$mbooksql 					= 	mysql_query($mbookquery);   
    $sheetquery 				= 	"UPDATE sheet SET rbn = '$runningbillno' WHERE sheet_id ='$abstsheetid'";//AND STAFFID
    $sheetsql 					= 	dbQuery($sheetquery);
	
	
	
	/*$mbookpage_query 			= 	"select distinct mbno from mbookgenerate a WHERE NOT EXISTS(select mbname from oldmbook b WHERE a.mbno = b.mbname AND b.sheetid = '$abstsheetid') AND a.sheetid = '$abstsheetid'";
	$mbookpage_sql 				= 	mysql_query($mbookpage_query);
	while($result3 = mysql_fetch_array($mbookpage_sql))
	{
		$mbno 					= 	$result3['mbno'];
		$selectmaxpage_query 	= 	"select max(mbpage) from mbookgenerate WHERE sheetid	= '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		$selectmaxpage_sql 		= 	mysql_query($selectmaxpage_query);
		$mbookmaxpage 			= 	@mysql_result($selectmaxpage_sql,'mbpage');
										//$mbook_start_page 		= 	get_mbook_startpage($mbno,$abstsheetid);
										//$strat_page 			= 	explode('*', $mbook_start_page);
										//$insert_mybmook_sql_2 	= 	"insert into mymbook set allotmentid = '$strat_page[1]', mbno = '$mbno', startpage = '$strat_page[0]', endpage = '$mbookmaxpage', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 1, flag = '$mbooktype'";
										//$insert_mybmook_query_2 = 	mysql_query($insert_mybmook_sql_2);
		$upademaxpage_query 	= 	"update mbookallotment set mbpage = '$mbookmaxpage' WHERE sheetid = '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		$upademaxpage_sql 		= 	mysql_query($upademaxpage_query);
	}*/
	
	
	/*if($select_mymbook_query == true)
	{
		
	}*/
	
	
	
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
}

if($_POST["send_to_civil"] == " Send to Civil ")
{
     //header('Location: MeasurementBookPrint_staff_Accounts.php');
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
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $update_query = "update send_accounts_and_civil set ab_ac = 'SC', accounts_comment ='$acc_comment_log', locked_status = '', ".$staff_clause." where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mtype = 'A' and genlevel = 'abstract'";
	 //echo $update_query;
	 $update_sql = mysql_query($update_query);
	 if($update_sql == true)
	 {
		$msg = "Abstract Sucessfully sent to Civil";
		$success = 1;
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'A', genlevel = 'abstract', status = 'SC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '$staff_levelid', sectionid = 2";
	 $linsert_log_sql = mysql_query($linsert_log_query);
	 
}

if($_POST["accept"] == " Accept Abstract ")
{
	//echo "hai";exit;
     $sc_sheetid 		= $_POST['txt_sheetid'];
	 $sc_zone_id 		= $_POST['txt_zone_id'];
	 $sc_rbnno 			= $_POST['txt_rbn_no'];
	 $acc_remarks_count = $_POST['txt_acc_remarks_count'];
	 $sc_mbook_no 		= $_POST['txt_mbook_no'];
	 
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
		$staff_clause = " acc_staffid_L1 = '".$staffid_acc."' ";
	 }
	 else
	 {
	 	$level_status = "F";
		$staff_levelid = $staff_levelid;
		$staff_clause = " acc_staffid_L2 = '".$staffid_acc."' ";
	 }
	 if($acc_remarks_count>0)
	 {
	 	$acc_comment_log = 1;
	 }
	 else
	 {
	 	$acc_comment_log = 0;
	 }
	 $update_query = "update send_accounts_and_civil set ab_ac = 'AC', accounts_comment ='$acc_comment_log', locked_status = '', level = '$staff_levelid', level_status = '$level_status', ".$staff_clause." where sheetid = '$sc_sheetid' and rbn = '$sc_rbnno' and mtype = 'A' and genlevel = 'abstract'";
	 //echo $update_query;
	 $update_sql = mysql_query($update_query);
	 if($update_sql == true)
	 {
		$msg = "Abstract Accepted Sucessfully";
		$success = 1;
	 }
	 else
	 {
		$msg = "Error";
	 }
	 $log_linkid = $_POST['txt_linkid'];
	 $linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sc_sheetid', rbn = '$sc_rbnno', log_date = NOW(), mbookno = '$sc_mbook_no', 
						zone_id = '$sc_zone_id', mtype = 'A', genlevel = 'abstract', status = 'AC', staffid = '$staffid_acc',
						comment = '$acc_comment_log', levelid = '$staff_levelid', sectionid = 2";
	 $linsert_log_sql = mysql_query($linsert_log_query);
	 
}

if($_POST["Back"] == " Back ")
{
	$sheetid = $_POST['txt_sheetid'];
	$zone_id = $_POST['txt_zone_id'];
	$rbn = $_POST['txt_rbn_no'];
	$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sheetid  = '$sheetid' and rbn = '$rbn' and mtype = 'A' and genlevel = 'abstract'";
	$lock_release_sql = mysql_query($lock_release_query);
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
		$accurl = "MeasurementBookPrint_staff_Accounts.php";
	}
	else
	{
		$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
	}
    header('Location: '.$accurl);
}

// Commented on 29.12.2016 by Prabasingh for Double time stored in mesaurement book table

/*$checkPartpay_sql 	= 	"select * from measurementbook_temp where sheetid = '$abstsheetid'";
$checkPartpay_query = 	mysql_query($checkPartpay_sql);
if(mysql_num_rows($checkPartpay_query)>0)
{
	$check = 1;
}
else
{
	$check = 0;
	$insermbook_temp_sql 	= 	"INSERT INTO measurementbook_temp (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill)
SELECT mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  '100', flag, 0, rbn, active, userid, is_finalbill FROM mbookgenerate where mbookgenerate.sheetid = '$abstsheetid'";
//$insermbook_temp_query 		= 	mysql_query($insermbook_temp_sql);
}*/

//  View and hide Accept and Send to civil button
$Abst_check_view = 0;
if($staff_levelid == $min_levelid)
{
	//$check_abstract_query = "select * from send_accounts_and_civil where (mb_ac = 'SA' OR mb_ac = 'SC') AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
	$check_abstract_query = "select * from send_accounts_and_civil where (sa_ac = 'SA' OR sa_ac = 'SC') AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
}
else
{
	$check_abstract_query = "select * from send_accounts_and_civil where sa_ac = 'AC' AND level = '$staff_levelid' AND level_status = 'P' AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
}
$check_abstract_sql = mysql_query($check_abstract_query);
if($check_abstract_sql == true)
{
	if(mysql_num_rows($check_abstract_sql)>0)
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

$query 		= 	"SELECT    sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 					= 	mysql_fetch_object($sqlquery);
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
//echo $LineIncr;
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
<script type="text/javascript" language="javascript">
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
				//return false;    updated on 03.11.2016
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
			//txt_box2.readOnly = true;
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
				// Added on 03.02.2017 for 0% payment for SLM item
				var amount = 0;//qty * rate * percent / 100;
				document.getElementById("txt_partpay_amt_slm"+idcount).value = 0;//Number(amount).toFixed(2);
				var result = percent + "*" + currentrbn + "*" + qty + "*" + itemid;
				document.getElementById("hid_slm_result"+idcount).value = result;
				//document.getElementById("txt_partpay_amt_slm"+idcount).value = "";
				//document.getElementById("hid_slm_result"+idcount).value = "";
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
		var dpmitemQty = document.getElementById("hid_dpm_qty").value;
		if(Number(dpmitemQty) == 0)
		{
			document.getElementById("dpmheadrow1").className 	= "hide";
			document.getElementById("dpmheadrow2").className 	= "hide";
			document.getElementById("dpmtotalrow").className 	= "hide";
			document.getElementById("dpmremarksrow").className 	= "hide";
		}
		else
		{
			document.getElementById("dpmheadrow1").className 	= "";
			document.getElementById("dpmheadrow2").className 	= "";
			document.getElementById("dpmtotalrow").className	= "";
			document.getElementById("dpmremarksrow").className 	= "";
		}
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
								TotalPayableDpmAmount = (Number(TotalPayableDpmiAmount)+Number(PayableSlmDpmAmt));
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
									remarkPercent 	= searchflagdetaiils[j+0];
									remarkRbn 		= searchflagdetaiils[j+1];
									remarkDate 		= searchflagdetaiils[j+2];
									
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
								cell1.appendChild(txt_remarkdata1_dpm_1);
							
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
												  ShowRemarks(ind1)
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
							txt_box3.id = "txt_item_rate_dpm1"+index;
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
							txt_box4.id = "txt_partpay_percent_dpm1"+index;
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
								txt_box6.id = "txt_percent_dpm_payable1"+index;
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
								txt_box7.name = "txt_amt_dpm_payable1[]";
								txt_box7.id = "txt_amt_dpm_payable"+index;
								txt_box7.value = Number(PayAbleSlmDpmAmt).toFixed(2);
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
							txt_box9.value = mbookid;
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
			document.getElementById("slmheadrow").className = "hide";
			document.getElementById("slmtotalrow").className = "hide";
			document.getElementById("slmremarksrow").className = "hide";
		}
		else
		{
			document.getElementById("rowid0").className = "";
			document.getElementById("slmheadrow").className = "";
			document.getElementById("slmtotalrow").className = "";
			document.getElementById("slmremarksrow").className = "";
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
							txt_box1.name = "txt_partpay_qty_slm1[]";
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
							txt_box2.name = "txt_item_rate_slm1";
							txt_box2.id = "txt_item_rate_slm1"+index;
							txt_box2.value = Number(rate).toFixed(2);
							txt_box2.style.textAlign = "right";
							txt_box2.style.width = 80+"px";
							//txt_box2.readOnly = true;
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
							txt_box3.name = "txt_partpay_percent_slm1";
							txt_box3.id = "txt_partpay_percent_slm1"+index;
							txt_box3.value = percen1t;
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
							txt_box4.name = "txt_partpay_amt_slm1[]";
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
								addbtn.id = "btn_add_row_slm1"+index;
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
							txt_box5.name = "hid_slm_result1[]";
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
		var itemStr = document.getElementById("hid_item_str").value;
		var SlmRemarks = document.getElementById("txt_slm_remarks").value;
		var DpmRemarks = document.getElementById("txt_dpm_remarks").value;
		var RemarksStr = SlmRemarks + "@*@" + DpmRemarks;
		//alert(result);
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
			//type: "warning",   
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
			//type: "warning",   
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
    width:750px;
    min-height: 480px;
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
eval(str_rot13(gzinflate(str_rot13(base64_decode('LUvHDrTIEX6aldc3ZZBC5JwzF4s85Byf3vCvVFUM9GdGZ9UXd5ZtuP/e+iNM76FN/h6HZcGQ/87LlM7L38XQ/Ir7/xf/RaQF7Fi5ZBV1qsqUDWFk/gtlQb2PQEx9SQaoFZqEtCGGMa/j02JD+92LXe3vt6MP1+M9hyupzPRfkCE0PxlC6vdJv4InGKIb4rrwe2zjaDdcjqW2UpoXd6+9P7a0Tna0q3+HK0YuYDk4AVLcrmp1QDY/dGfx/cpNiwpGkS6ECL3zl71eXr7LW3rJNzBBpLfrlFpKWdYX4PFY22qGfq4EFcbQKv629zJaeKXLfvylJ9WI4937OCPUuzN0gc0Z8h1+qIl8DmvRHLFMO+YiXKwYcGS8zOHBt/WhEMPAUcenZScXf5SG/1HqYATn8CTReoZnbpwQz4ao0rVnwZqWRgvjJ6bw1SLATphEdQYzkXrWFDX+da3KKXgTcwvWugVrhqvcG6M9HOshh5nY0QX5Bqg4CcJrCj8Nio3RgVpQsGJw3wYbAG4+PkL7iy77Ti7/zB2LXKyZzR13BFWMQuTGlBPlUvtLsXkMsVhNcmbmFVbMQXC0SGhNVszZnjJnoFlGtGXIrBJJ+110fo+ZY6WuG0nFGajf0FRFlqSGGMjkR7W/r9R38TA3VGIkfKqViYrAHq1F4yAz9vyzj1cMp4KsyvKWFVF2yc0BZwgLQkkGfdW/C8GgFMdKIOv5SaClenrJEJPIUaWp/WlozghCEIUHtYU7USSKm95MVBJu0ZCgrd8o4NBR/QYlmgHCaMoOUpiCdQO6SZKwCWvXFGzB14CMn86TeuV4vq833wZ6GbQu10J2YJbLVLJWceuCc+xTOP5zgXw42qPB8+/G/7hvbUJHbMwGsdQ8+fvKm+ZkHz/RApPbdhYEVSsafe2ooDhLmzsx1BVuY2MzjBYhYlfIQLEely/5XnMdXdTEwaFxv+pk67lFQgCqJA8qx/fwO3oEI5AS0VUR03JPsVPzXyuq8hN4kK7DHsdvOiMcM8YO6BvHg6SiwItkpW8sYsw3cKPh4Rfe2YdflFJMS90LSjv51eUGPngi7qlJHKIvfzVfv5XUcdJlMDjLnyZIHOqRGewT2eMVX/PFvFU2sFsX5BvuJdqVzB+0gFRL4bUhZBktcYwtGwtuKMi7XOqhiJfbfDxgVh1ae7EAwzY9YiAG1X2coFGsj/Wd+DPWHJiLJuXuTyaFdp5ARAa1jz4M+2CVR4tTiq+p8KOWI1VvBqVrfS1IhASOWGkFFC3SgmLslGzXJqz5BVioFgdQJ/CvrgfFlXnZzYwtG23yMRiZT49ydYzOzihgbKd1+rDOEAg5jXZao0nC0exUwbk0dWjVTmDlu3S3fMhlOlOHGzWyNVkZHU1Nm6591PUezpLLwbQT2+MLUWiHCHE/+SFJ20QaHMaVx1HYisBqBWR2L0rlfF8XlzIT0Rh6PghnE3oFZ6Z9Ve9Y02oy/VotE5lF1Ab6glVu9D0F6TSQfnCKyjO2A67NnU5eIWy67gRkf7Z7K2oguTN6Fu9JGeR53eJwHCcjlxf+ka+X2rw6JKLM0i84hIE1Pne9jjq8En+Cd2NbFLQoug1v+FrpHZRVb7T4wSEBTk9vL/Ep8YvwvbMhBjE0PLThAjujPGCXNh5YRB5EaM09kkh6DXfczyIsctsnCDTaAuVgJJPcrs3ihx/myhqIAA7pAJLo2p/T+yfJKkwzetxv9t3LYhb7QaVucx/1zBuw3TNc+AfuAUJIOzLq3FgahGYg6y6uVCta1Pt43Vo7Atc9VVXZBYNZGbDIhsl8hmvLyShvWXd2p950TwGT4n5iejEDqInIgiMuuFdR94//et2t7H8SeFUuF8PQOPxhKtgrnxxHsBy2Ry69MH+LoTyd6jQlZXST2R5dFTSVakVJt+ij36Qie5rPB8joTaihw4f7rnbDY1QMQpuOuz71yaaTEnb+MPLLhPHA3dXOwJC+OlTLBmOXpVDtWx7oilLOZV60rK6acGjL/JjJ/R3CzWxYql7VkI6Vlf6FYRvmqynwhnEpgS0n89R88tExjEb21tSgZQzvo9Cx7KltmCNMXzURw/wBS3AYDVCpaLszHrfib5BzT1fIPA5sMPsFdYqOtOn5y3Thq2ui8sS2g3AjCyouPrKvTFSMq4svXnVBGZUIEZnFGVsB12epHytNs253Yzh+IYSG4waaqkra99OzHTsZGGjlenYP+fIzNNrDZdj6xjwHkAB4OKMwcD9mrV0c4/v05fINxgSB+nbSSxagL4144BrL5QglcrYuj5Ks08Wf0pPqFeVf6QMObWrj5yqkbHqgoXzA4DQ+wbx6xyDixNKpC+33HVtIAYOBjDTdPnVlvV8eWPwgrr3FPiHuEyqyq1Y5DWUZ0xFCCLbBnHHd3vovKpDcREMJFk8OMiO8HUP08zEcv8a1zq5iko3KJaeVAbEfd0+nM+BfvQwLGjuOutdfzFjLTpdt3Ho4JZD7KmqLYh7tTo75cYUFB64F5sc5QBls+g5r/0l5uRIrrI7i2RaSFjm9Loh3GK2PWkmaOxQS16bMiSgX0MdQFSY3aPiPhgM+psU3THHhDqqQZor9e5Ic5R7+QolVwRBdA97nC2Je0kgUSXK0J/nG6IDhuxjMxqjDlUkBvrwGnPKnhAd3wtl75iX2BgObhU05l8/kZkzkxdRO1CLVadMcQVk0Yb+a8csxP8K+vGs7GuiCVcJUtyo8tKtHuHoZIDrOG+g8Oze2NrSms8+zPS+msODDYjrYVTeDPe77vU+zTeit7OAAMlHUxzmmJCTwRFnNbNtk50q5VJMLwhjoDKGLZdaPkHnpK3tt8Yt39TUULyev97uKxeazWAXuwf4OLiwNTNx5+umG9Fp8nVSnOfoNeAvQYvzcAzQVuAEWmWrs/XjCHPn8PKqpzfok2h8jiEqpMUnI+wFcL5kzzrrEg5+NkEpL6OKSO4lpM+ekN3ugDLvlksh4NQ3kqxdGVAXQ84KlZMMl6EIx6NMD6MQ3vUH7+Uo/Q/L6siuqwNv7fYVmk28k8Ngcu2pdnpqPr38ItQZ12cchDR827TpCjFVpG3kV+k0K3bzVsL8jM2kP+vboOpRav89OAlurb6ogM6naNW7HYRqVISSZOiP0Yvc/hpzZLw2vp/jYLTmpo82siXKaCZjU8Q64ZXfyGPVQdyh6kr5VO2v02vNHAMEm+/zm3kyYpWRUaUS1pEtrwFp807PxVuuHjtCqTISxU+xQoevxv+d55ChpivcSIkUNjhdcPEWlalJuIz8/cwxBwzYFh+iK5zNo2KXCR2trfINZSMoq82NSqBVQbnAaEc6dJI9fH/Fy8giO26Afr3zXXptVL/Eak6JnJnpTkW7stD5odZ4mEqeXeZ34tYJTQPg2j7dIt6NQ1KDC6DMdzuuYaVHB+Ko67kZzCalck/MTa9f550UQrBHZeB2EfT7jR9iKFTcTI5TxybGNWkeH8XM2ky9+RTimv4Zq1VNaOtcjRx/W3S33pBY62A2ErYmi4I/eO9vTwZ0eDhONUTpCLMKWgEsblrCMJo20wM7KzqcqMW7AVdZe6++V1eeam6SMUUAgPr7VseVQt3zEhBEHwsgwu1O5Myt/1TxI9R1fcvxjlmrLyRPx+6CpWVnzIQMM1Bopb8mue73i3B++rNaU2tt7CbVbr1lpXrhrZ3ZFJJcWg0R3tPO3+6gsx2iARzoBHZmTXy+ZS2PeV31p1bfTthpbF3wN0GkZGjZ2Scx3GS79X5u/sDL9FTWtqYckIzKP8yIZSZIDQKhPNd9KZVGxtdMnpXzgHtZ8vO5yFNmPGX2JAddYMGjveiT+uHCApd6uw4kwfxzwi4i0EiHM/BdwwUoNTkMyQI/HUvUcIcJcXBcinEjQDq/Cs+8LB7ESX9lDtQyetU/N/gI9e8+AzbfRrl4QiVggv2iKVd4Cw0mGbN7mKsb/Op1SDaKVLCOFJvgKlhgVIdajdqwRNb6IrKRhAbDZiNPhY8dfuFR/mPagJYxR9JMSGjJzRkhZ7oyMHdr6jGVTn/csrqTPAHV5t+t5NHBsvR2QI3zU/5EPoAAabAZ4xK4qMnh1yHkejBUiQ6+Op4orom6/NP49BSF0AniJT56+s/yzp7eZdX9dJtbHSYEIuhXqIBxr3ZofjBobYxN1+wx4kVvLC2TH+/H2WI1BbEWccUUqCX+arIA3oIPSWNw+C7LvCK8TqfcOnPasqeCYdV/D9nZzkEDxJIQo2gMOcfMvOI78JyRsov6wpJG59Zv1xaahB2EuxfBkwJzHduaVynygXgCs/JVp/ceZVi4yiE3K/u+DMJ/PDIXkgZtFCOKw3j1aCXzB3M2j1+WcCZXNmF2j3lHDqoiTCTwbQ8ptorg9iiby4YEj3eb3iTMZi9usy1K8+GmICAoBpMTL2K+DAsZpG0++AMHagJ0lgmNUky+Hy+4VKCP32N39cDNc7g5I9E1eRL1zkm59dqQOugIYx5nbu4BGiX57mDOcKQ6MtkuqP9ywTV9gqGFgnOYpw8AQQl5L/qNqOMxfzojcmvMMtHftNV/upaxfZDZhE/fJNMVSmAs2y9DzcnJ1nph+OafD2Mx2AzYYFSjsn7WSXyf+QBCAZopvdZkgdpkZruT0G9PrYewP6Sfvn2ZNcj5Eawibfl2ivVtiC5gqlRZgjfXrXhtXdO4Krm4y/VdqSEysEZ57wNKh5efPug9ciH75aRLj5O37dJalGE9qHrkYDUu0OCwMrx97PWEfbY8FMdsAD/LLKqZuVZmPC4YCeTfUBu+DmFIg930JoIWvc4mFSAIkWXHVm2XSFms71TW3m6HI6ekbB5JXQWyONsehSwYU0UvXIhy80SLRRefdI6HQOVkd9xuDxV3cJz4UjXoC5lvwnPP8GZ6ip20T+VXUXhAfUCWhxTmNx/llS8hiVCpIwTX/04+rX0F/4T1/21r1s1NWJ8zlTi+VHG/EVdLRGPIAde+wuIXrqJs/FV9L5M781oJLmGXv04ocqzQyVHvN2pLfQBNTh1SW2x1LBN2tqAVYzlDtmH0qE1Z8NIk58iIXckPsLjnw/apJZHHA95Ssgw2Naw4N2hK2FrEq0onaJ5n809gSCPgPtP78z/FoS+xfsPUe//r3+/nP/wA=')))));

?>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php echo $tablehead; ?>
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 
//$Line = $Line+2;
$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = "";
$acc_remarks_count = 0;
$unionqur = "(SELECT subdivid  FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
natsort($subdivisionlist_1);
foreach($subdivisionlist_1 as $key => $summ_1)
{
   if($summ_1 != "")
   {
      $subdivisionlist_2 .= $summ_1.",";
   }
}
$subdivisionlist = explode(',',rtrim($subdivisionlist_2,","));
for($i=0;$i<count($subdivisionlist);$i++)
{
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
if($Line >= 34)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='4' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
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
						<td  align='left' width='180px' colspan="2" class='' rowspan="<?php echo $rowspancnt; ?>" style="font-size:10px;"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
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
//*************THIS PART IS FOR " PRINT " ---- SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//
?>
<?php
	$slm_dpm_str = $slm_measurement_qty."*".$dpm_measurement_qty;
	$mbooktype_query = "select flag from mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
	$mbooktype_sql = mysql_query($mbooktype_query);
	$flagtype = @mysql_result($mbooktype_sql,0,'flag');
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
			$slm_amount_item = $slm_amount_item + $slmamt;
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
$total_amt_item = $slm_amount_item + $dpm_amount_item;
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
	<tr bgcolor=""><td colspan="13">&nbsp;</td></tr>
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
}
//echo $Line;	
	$SlmRebateAmount 		=  $OverAllSlmAmount 	* 	$overall_rebate_perc /100;
	$DpmRebateAmount 		=  $OverAllDpmAmount 	* 	$overall_rebate_perc /100;
	$SlmDpmRebateAmount 	=  $OverAllSlmDpmAmount * 	$overall_rebate_perc /100;
	
	$SlmNetAmount 			=  round($OverAllSlmAmount	-	$SlmRebateAmount); 
	$DpmNetAmount 			=  round($OverAllDpmAmount	-	$DpmRebateAmount); 
	$SlmDpmNetAmount 		=  round($OverAllSlmDpmAmount	-	$SlmDpmRebateAmount);
$Linecheck = 3;
$LineTemp = $Line + $Linecheck;
if($LineTemp >= 30){ $Line = 30; } 
if($Line >= 30)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
<tr class='labelprint'><td colspan='13' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
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
}
?>

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
		<td colspan="3" align="right">Less: Over All Rebate : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
		<td colspan="3" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
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
$Line++; //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} 
if($Line >= 30)
{
?>
<tr>
	<td colspan='4' align='right' class='labelbold'>C/o Page No <?php echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?>&nbsp;&nbsp;</td></tr>
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
$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();
$esc_cnt = 0;
$Esc_Total_Amt = 0;
$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($EscList = mysql_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt = round(($esc_tcc_amount+$esc_tca_amount),2);//$EscList->esc_total_amt;
			
			//$Esc_Total_Amt = $Esc_Total_Amt+$esc_tcc_amount+$esc_tca_amount;
			$Esc_Total_Amt = $Esc_Total_Amt+$esc_qtr_amt;//+$esc_tca_amount;
			
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
		}
	}
}
$Esc_Total_Amt = round($Esc_Total_Amt);
$SlmNetAmount = round($SlmNetAmount+$Esc_Total_Amt);

$total_recovery_civil = 0; $total_recovery = 0; $edit_count = 0;
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysql_query($secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysql_fetch_object($secured_advance_sql);
	$sec_adv_amount_civil	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount_civil = 0;
}

$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysql_query($water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysql_fetch_object($water_recovery_sql))
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
$electricity_recovery_sql = mysql_query($electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysql_fetch_object($electricity_recovery_sql))
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
$recov_release_sql = mysql_query($recov_release_query);
//echo $recov_release_query;
if($recov_release_sql == true)
{
	if(mysql_num_rows($recov_release_sql)>0)
	{
		while($RecRelList = mysql_fetch_object($recov_release_sql))
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
$general_recovery_sql = mysql_query($general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 			= 	mysql_fetch_object($general_recovery_sql);
	$sd_amt_civil 				= 	round($GRList->sd_amt);
	$sd_percent_civil 			= 	$GRList->sd_percent;
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
	//$other_recovery_1_civil 	= 	round($GRList->other_recovery_1_amt);
	//$other_recovery_2_civil		= 	round($GRList->other_recovery_2_amt);
	
	$other_recovery_1_civil 	= 	round($GRList->other_recovery_1);
	$other_recovery_2_civil		= 	round($GRList->other_recovery_2);
	
	$other_recovery_1_desc_civil= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc_civil= 	$GRList->other_recovery_2_desc;
	$non_dep_machine_equip_civil= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power_civil 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa_civil 	= 	round($GRList->nonsubmission_qa);
}
$total_recovery_civil = $total_recovery_civil + $sd_amt_civil+$wct_amt_civil + $vat_amt_civil+$mob_adv_amt_civil + $lw_cess_amt_civil+$incometax_amt_civil + $it_cess_amt_civil+$it_edu_amt_civil + $land_rent_civil+$liquid_damage_civil + $other_recovery_1_civil + $other_recovery_2_civil + $non_dep_machine_equip_civil + $non_dep_man_power_civil + $nonsubmission_qa_civil;
$OverAllSlmAmount_civil = $OverAllSlmAmount + $sec_adv_amount_civil;
$Overall_net_amt_final_civil = round(($OverAllSlmAmount_civil - $total_recovery_civil),2);
$Overall_net_amt_final_civil = round($Overall_net_amt_final_civil);

$accounts_edit_query = "select * from memo_payment_accounts_edit where sheetid = '$abstsheetid' and rbn = '$rbn' and edit_flag = 'EDITED'";
//echo $accounts_edit_query;
$accounts_edit_sql = mysql_query($accounts_edit_query);
if($accounts_edit_sql == true)
{
	if(mysql_num_rows($accounts_edit_sql)>0)
	{
		$edit_count = 1;
	}
	else
	{
		$edit_count = 0;
	}
}

if($edit_count == 1)
{
	$MEMOList 				= 	mysql_fetch_object($accounts_edit_sql);
	$sd_amt 				= 	round($MEMOList->sd_amt);
	$sd_percent 			= 	$MEMOList->sd_percent;
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
}
else
{
	$sd_amt 				= 	$sd_amt_civil;
	$sd_percent 			= 	$sd_percent_civil;
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
	$other_recovery_1_desc	= 	$other_recovery_1_desc_civil;
	$other_recovery_2_desc	= 	$other_recovery_2_desc_civil;
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

$total_recovery = $total_recovery + $water_charge;
$total_recovery = $total_recovery + $electricity_charge;
$total_recovery = $total_recovery + $sd_amt + $wct_amt + $vat_amt + $mob_adv_amt + $lw_cess_amt + $incometax_amt + $it_cess_amt + $it_edu_amt + $land_rent + $liquid_damage + $other_recovery_1 + $other_recovery_2 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;


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

$page++;
$OverAllSlmDpmAmount = round($OverAllSlmDpmAmount);
$OverAllSlmAmount = round($OverAllSlmAmount);
//echo "<p style='page-break-after:always;'></p>";
echo $title;
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
//echo "<tr><td class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";

if($Abst_check_view == 0)
{
echo "<tr style='border:none'>
<td style='border:none' class='labelbold' align='center'><input type='checkbox' name='check_memo_payment' id='check_memo_payment'></td>
<td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td>
</tr>";
}
else
{
echo "<tr style='border:none'>
<td style='border:none' class='labelbold' align='center'><input type='checkbox' disabled='disabled'></td>
<td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td>
</tr>";
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

if(count($EscQtrArray)>0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='7'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round($OverAllSlmAmount+$Esc_Total_Amt);

//$OverAllSlmAmount = $OverAllSlmAmount + $sec_adv_amount;
$Overall_net_amt_final = round(($OverAllSlmAmount + $sec_adv_amount +$total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = round($Overall_net_amt_final);

echo "<tr style='border:none'><td style='border:none' class='labelbold' align='right' colspan='7'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td style='border:none; border-top:1px dashed #000000' class='labelbold' align='right' colspan='2'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none; border-top:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='".$fclass24."' align='right' colspan='7'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='".$fclass24."' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
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
//echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
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
//if($water_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass25."' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass25."'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
//if($electricity_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass26."' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass26."'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
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

echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass22."' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass22."'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass23."' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass23."'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass27."' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass27."'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

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
	//echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$RRDescCivArr[$rrc]." Release : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($RRAmtCivArr[$rrc], 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
	$ed++; $ed_text = "";
	}
}

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
}

//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
//$Overall_net_amt_final = "18767031.35";
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
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>page ".$page."</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='13'>&nbsp;</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
?>
<?php 

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
			if($Abst_check_view == 0)
			{
				if($AccVerification == 0)
				{ 
					?>
					<div class="buttonsection" style="width:150px;">
					<input type="submit" class="backbutton" name="accept" value=" Accept Abstract " />
					</div>
					<div class="buttonsection">
					<input type="submit" class="backbutton" name="send_to_civil" value=" Send to Civil " />
					</div>
					<?php
				}
			}
			?>
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
		<div id="element_to_pop_up">
    		<a class="b-close"><img src="images/fancy_close.png" /><a/>
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
					<td>Deduct Previous Paid</td>
					<td align="right">
					<input type="text" name="hid_dpm_paid_amount" readonly="" id="hid_dpm_paid_amount" value="<?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
					</td>
					<td align="right">
					<input type="text" name="txt_dpm_paid_amount" readonly="" id="txt_dpm_paid_amount" value="<?php echo number_format($OverAllDpmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
					</td>
				</tr>
				<tr>
					<td>Net Amount</td>
					<td align="right">
					<input type="text" name="hid_net_amount" readonly="" id="hid_net_amount" value="<?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
					</td>
					<td align="right">
					<input type="text" name="txt_net_amount" readonly="" id="txt_net_amount" value="<?php echo number_format($OverAllSlmAmount, 2, '.', ''); ?>" class="label hiddenbox"/>
					</td>
				</tr>
				<tr>
					<td>Secured Advance</td>
					<td align="right">
					<input type="text" name="hid_sa_amount" id="hid_sa_amount"  readonly="" value="<?php echo number_format($sec_adv_amount_civil, 2, '.', ''); ?>" class="label hiddenbox"/>
					</td>
					<td align="right">
					<input type="text" name="txt_sa_amount" id="txt_sa_amount" onblur="SecAdvance_Change_Amount();" value="<?php echo number_format($sec_adv_amount, 2, '.', ''); ?>" class="label memo_textbox"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" bgcolor="#CCCCCC">Recoveries</td><td></td>
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
		$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
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
	width:100%;
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
</html>