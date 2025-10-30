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
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
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
$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
$abstsheetid    = 	$_GET['workno'];
$_SESSION["abstsheetid"] = 	$_GET['workno'];
$abstsheetid    = 	$_SESSION["abstsheetid"];
//$rbn    		= 	$_SESSION["rbn"]; 
//$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
//$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$selectmbook_detail = " select DISTINCT fromdate, todate, rbn, abstmbookno FROM mbookgenerate WHERE sheetid = '$abstsheetid'";
//echo $selectmbook_detail;
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; $abstmbno = $Listmbdetail->abstmbookno;
	$abstmbpage_query 	= 	"select mbpage, allotmentid from mbookallotment WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstmbno'";
	$abstmbpage_sql 	= 	mysql_query($abstmbpage_query);
	$Listmbook 			= 	mysql_fetch_object($abstmbpage_sql);
	$abstmbpage 		= 	$Listmbook->mbpage+1; $abs_mbno_id = $Listmbook->allotmentid;
}
$paymentpercent = $_SESSION["paymentpercent"];	$emptypage 	= $_SESSION['emptypage'];
$abs_last_page = $_SESSION['abs_last_page'];

if($_POST["Submit"] == "Confirm")
{	
	
	//echo $abs_last_page; exit;
	$AbstractStr 			= 	$_POST['txt_abstractstr'];
	$SubdividSlmStr 		= 	$_POST['txt_subdivid_slmstr'];
	$runningbillno 			= 	$_POST['txt_rbn_no'];
	
	//$select_mymbook_sql = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$runningbillno' ORDER BY mtype, mbookorder ASC";
	//$select_mymbook_sql = "select distinct(mbno) as mbookno from mymbook a INNER JOIN (SELECT MAX(endpage), mbno AS maxpage
   // FROM mymbook) b ON where a.mbno = b.mbno and a.rbn = '$runningbillno' and a.sheetid = '$abstsheetid'";
	//$select_mymbook_sql = "select * from (SELECT distinct(mbno) FROM mymbook a where sheetid = '$abstsheetid' and rbn = '$runningbillno') mymbook";
	$select_mymbook_sql = "SELECT MAX(endpage) as maxpage, emptypage, genlevel, mbookorder, mbno FROM mymbook WHERE sheetid = '$abstsheetid' and rbn = '$runningbillno' GROUP BY mbno ORDER BY mbookorder ASC";
	$select_mymbook_query = mysql_query($select_mymbook_sql);
	//echo $select_mymbook_sql."<br/>";
	if(mysql_num_rows($select_mymbook_query)>0)
	{
		while($MBKList = mysql_fetch_object($select_mymbook_query))
		{
			//$maxpage 	= $MBKList->maxpage;
			$emptypage 	= $MBKList->emptypage;
			$genlevel 	= $MBKList->genlevel;
			if($genlevel == 'abstract')
			{
				$maxpage 	= $abs_last_page;
			}
			else
			{
				$maxpage 	= $MBKList->maxpage;
			}
			//$maxpage 	= $maxpage+$emptypage;
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
	
	
	//$mbookquery					=	"INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks) SELECT  now(), staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks FROM measurementbook_temp WHERE sheetid = '$abstsheetid'";// WHERE flag =1 OR flag = 2";
   	//$mbooksql 					= 	mysql_query($mbookquery);  
	
	////// This is for QTY SPLIT PART PAYMENT instead of above to insert Query /////
	$SelectSheetTypeQuery 	= "SELECT section_type FROM sheet WHERE sheet_id ='$abstsheetid'";
	$SelectSheetTypeSql 	= mysql_query($SelectSheetTypeQuery);
	if($SelectSheetTypeSql == true){
		$STList 		= 	mysql_fetch_object($SelectSheetTypeSql);
    	$SectionType 	= 	$STList->section_type; 
	}
	
	
	$SelectMBTempQuery = "select * FROM measurementbook_temp WHERE sheetid = '$abstsheetid'";
	$SelectMBTempSql = mysql_query($SelectMBTempQuery);
	if($SelectMBTempSql == true){
		if(mysql_num_rows($SelectMBTempSql)>0){
			while($MBTempList = mysql_fetch_object($SelectMBTempSql)){
				$MBTempId 				= $MBTempList->measurementbookid; 	$MBTempStaffid 		= $MBTempList->staffid;
				$MBTempSheetid 			= $MBTempList->sheetid; 			$MBTempDivid 		= $MBTempList->divid;
				$MBTempSubdivid 		= $MBTempList->subdivid; 			$MBTempFromdate 	= $MBTempList->fromdate;
				$MBTempTodate 			= $MBTempList->todate; 				$MBTempMbno 		= $MBTempList->mbno;
				$MBTempMbnopages 		= $MBTempList->mbnopages; 			$MBTempMbpage 		= $MBTempList->mbpage;
				$MBTempMbremainpage 	= $MBTempList->mbremainpage; 		$MBTempMbtotalpages = $MBTempList->mbtotalpages;
				$MBTempMbquantity 		= $MBTempList->mbquantity; 			$MBTempMbtotal 		= $MBTempList->mbtotal;
				$MBTempAbstmbookno 		= $MBTempList->abstmbookno; 		$MBTempAbstmbpage 	= $MBTempList->abstmbpage;
				$MBTempAbstquantity 	= $MBTempList->abstquantity; 		$MBTempAbsttotal 	= $MBTempList->absttotal;
				$MBTempPay_percent 		= $MBTempList->pay_percent; 		$MBTempFlag 		= $MBTempList->flag;
				$MBTempPart_pay_flag 	= $MBTempList->part_pay_flag; 		$MBTempRbn 			= $MBTempList->rbn;
				$MBTempActive 			= $MBTempList->active; 				$MBTempUserid 		= $MBTempList->userid;
				$MBTempIs_finalbill 	= $MBTempList->is_finalbill; 		$MBTempRemarks 		= $MBTempList->remarks;
				$MBTempQty_split 		= $MBTempList->qty_split;
				$InsertMBPermQuery		= "INSERT INTO measurementbook set measurementbookdate = NOW(), staffid = '$MBTempStaffid', sheetid = '$MBTempSheetid', divid = '$MBTempDivid', subdivid = '$MBTempSubdivid', fromdate = '$MBTempFromdate', todate = '$MBTempTodate', mbno = '$MBTempMbno', mbnopages = '$MBTempMbnopages', mbpage = '$MBTempMbpage', mbremainpage = '$MBTempMbremainpage', mbtotalpages = '$MBTempMbtotalpages', mbquantity = '$MBTempMbquantity', mbtotal = '$MBTempMbtotal', abstmbookno = '$MBTempAbstmbookno', abstmbpage = '$MBTempAbstmbpage', abstquantity = '$MBTempAbstquantity', absttotal = '$MBTempAbsttotal', pay_percent = '$MBTempPay_percent', flag = '$MBTempFlag', part_pay_flag = '$MBTempPart_pay_flag', qty_split = '$MBTempQty_split', rbn = '$MBTempRbn', active = '$MBTempActive', userid = '$MBTempUserid', is_finalbill = '$MBTempIs_finalbill', remarks = '$MBTempRemarks'";// WHERE flag =1 OR flag = 2";
				//echo $InsertMBPermQuery."<br/>";
				$InsertMBPermSql 		= mysql_query($InsertMBPermQuery); 
				$MBPermId				= mysql_insert_id();
				
				if($SectionType == 'III'){
					$UpdateQtySplitQuery 	= "update pp_qty_splt set mbid = '$MBPermId', rpmbid = '$MBPermId' where sheetid = '$abstsheetid' and rbn = '$runningbillno' and mbid = '$MBTempId' and rpmbid = '$MBTempId'";
					$UpdateQtySplitSql 		= mysql_query($UpdateQtySplitQuery);
					//echo $UpdateQtySplitQuery."<br/>";
					
					$UpdateMBDetailQuery 	= "update mbookdetail set 
					prev_paid_perc = CASE WHEN (prev_paid_perc = '') THEN curr_paid_perc ELSE CONCAT(prev_paid_perc, ',', curr_paid_perc) END ,  
					prev_paid_rbn  = CASE WHEN (prev_paid_rbn  = '') THEN curr_paid_rbn ELSE CONCAT(prev_paid_rbn , ',', curr_paid_rbn) END , 
					prev_parent_id = CASE WHEN (prev_parent_id = '') THEN '$MBPermId' ELSE CONCAT(prev_parent_id, ',', '$MBPermId') END ,
					gr_par_id = CASE WHEN (gr_par_id = 0) THEN '$MBPermId' ELSE gr_par_id END ,
					curr_paid_perc = '', curr_paid_rbn = '', curr_parent_id = '' 
					where curr_parent_id = '$MBTempId' and curr_parent_id != '0' and subdivid = '$MBTempSubdivid'";
					
					$UpdateMBDetailSql 		= mysql_query($UpdateMBDetailQuery);
					//echo $UpdateMBDetailQuery."<br/>";
				}
				
			}
		}
	}
	//exit;
	
	 
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
	
	$update_mbremark_query = "update mbookgenerate_staff set accounts_remarks = '' where sheetid ='$abstsheetid'";
	$update_mbremark_sql = mysql_query($update_mbremark_query);
	
	$update_saremark_query = "update measurementbook_temp set accounts_remarks = '' where sheetid ='$abstsheetid'";
	$update_saremark_sql = mysql_query($update_saremark_query);
	
	$update_absremark_query = "update mbookdetail as mbd INNER JOIN mbookheader as mbh ON mbd.mbheaderid = mbh.mbheaderid set mbd.accounts_remarks = '' where mbh.sheetid ='$abstsheetid'";
	$update_absremark_sql = mysql_query($update_absremark_query);
	
	$update_accounts_query = "update send_accounts_and_civil set mb_ac = '', sa_ac = '', ab_ac = '' where sheetid = '$abstsheetid'";// and rbn = ''";
	$update_accounts_sql = mysql_query($update_accounts_query);
	
	$update_escalation_query = "update escalation set flag = 1 where sheetid = '$abstsheetid'";// and rbn = ''";
	$update_escalation_sql = mysql_query($update_escalation_query);
	
	$PODate = dt_format($_SESSION['PassOrderDate']);
	//$update_podate_query = "update abstractbook set pass_order_date = '$PODate', pass_order_dt = '$PODate', rab_status = 'C' where sheetid = '$abstsheetid' and rbn = '$runningbillno'";// and rbn = ''";
	$update_podate_query = "update abstractbook set rab_status = 'C' where sheetid = '$abstsheetid' and rbn = '$runningbillno'";// and rbn = ''";
	$update_podate_sql = mysql_query($update_podate_query);
	
	$update_br_query = "update bill_register set acc_status = 'C' where sheetid = '$abstsheetid' and rbn = '$runningbillno'";// and rbn = ''";
	$update_br_sql = mysql_query($update_br_query);
	
	unset($_SESSION['PassOrderDate']);
	unset($_SESSION['emptypage']);
	header('Location: AbstractBookBill_Confirm.php');
}

$checkPartpay_sql 	= 	"select * from measurementbook_temp where sheetid = '$abstsheetid'";
$checkPartpay_query = 	mysql_query($checkPartpay_sql);
if(mysql_num_rows($checkPartpay_query)>0)
{
	$check = 1;
}
else
{
	$check = 0;
	$insermbook_temp_sql 	= 	"INSERT INTO measurementbook_temp (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill)
SELECT mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  '100', flag, 0, rbn, active, userid, is_finalbill FROM mbookgenerate";
//$insermbook_temp_query 		= 	mysql_query($insermbook_temp_sql);
}


$query 		= 	"SELECT    sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 					= 	mysql_fetch_object($sqlquery);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor;    
	$agree_no 				= 	$List->agree_no; 
	$ccno 					= 	$List->computer_code_no;    
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
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		url = "AbstractBookBill_Confirm.php";
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
.labelprinterror
{
	font-weight:normal;
	color:#F00000;
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
<body bgcolor="" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<!--<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
	</tr>
</table>-->
<form name="form" method="post" onsubmit="return confirm('Do you really want to submit the Book?');">
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

?>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php echo $tablehead; ?>
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 
//$Line = $Line+2;
$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = "";
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
if($LineTemp >= 35){ $Line = 35; $LineTemp = 0; }
if($Line >= 35)
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
						<td  align='left' width='180px' class='' rowspan="<?php echo $rowspancnt; ?>" style="font-size:10px;"><?php echo "Prev-Qty Vide P ".$AbstractMbookPageNoDpm."/Abstract MB No.".$AbstractMbookNoDpm; ?></td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
						<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='left' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"></td>
						<td  align='right' width='' class=''><?php echo $DpmQuantityty_1;//$QtyDpmSlm_1; ?></td>
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
								//$Dpm_Slm_Amount_2 = $QtyDpm_5 * 100 * $rate/100;
								$Dpm_Slm_Amount_2 = $QtyDpm_5 * $DpmPercSum * $rate/100;
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
									else{
										echo $DpmPercSum."% Paid";
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
					<td  align='right' width='' class=''><?php echo number_format($dpmamtA, 2, '.', ''); $dpm_amount_item  = $dpm_amount_item + $dpmamtA; ?></td>
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
	if($flagtype == 1) { $mbookdescription = "/General MB No. "; }
	if($flagtype == 2) { $mbookdescription = "/Steel MB No. "; }

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
					echo $paymentpercent."% Paid"; 
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
	if($Accounts_Remarks != "")
	{
?>
		<tr border='1' class="labelprint" style="font-size:11px; color:#F00000">
			<td colspan="12" align="left" bgcolor=""><b>Accounts Remarks &nbsp; :&nbsp;&nbsp;&nbsp;</b>  <?php echo $Accounts_Remarks; ?></td>
		</tr>
<?php	
		$rowcount++;
	}
//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
$total_amt_item = $slm_amount_item + $dpm_amount_item;
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
	<?php UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page); ?>
	<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
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
		<td colspan="2" align="right">Less Over All Rebate : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
<?php
while($Line<30)
{
	echo "<br/>";
	$Line++;
}
?>
Page <?php echo $page; ?></td></tr>
<?php	
}
?>
</table>
<p style='page-break-after:always;'></p>
<?php 
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
	while($WRList = mysql_fetch_object($water_recovery_sql))
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
	while($ERList = mysql_fetch_object($electricity_recovery_sql))
	{
		$electricity_charge_civil 	= 	$electricity_charge_civil+$ERList->electricity_cost; 
	}
}
else
{
	$electricity_charge_civil = 0;
}
$total_recovery_civil = $total_recovery_civil + $electricity_charge_civil;
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
	$other_recovery_1_civil 	= 	round($GRList->other_recovery_1_amt);
	$other_recovery_2_civil		= 	round($GRList->other_recovery_2_amt);
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
//$total_recovery = $total_recovery + $sd_amt+$wct_amt + $vat_amt+$mob_adv_amt + $lw_cess_amt+$incometax_amt + $it_cess_amt+$it_edu_amt + $land_rent+$liquid_damage + $other_recovery_1 + $other_recovery_2 + $non_dep_machine_equip + $non_dep_man_power;

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
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none; border-bottom:1px dashed #000000'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";

//$OverAllSlmAmount = $OverAllSlmAmount + $sec_adv_amount;
$Overall_net_amt_final = round(($OverAllSlmAmount + $sec_adv_amount - $total_recovery),2);
$Overall_net_amt_final = round($Overall_net_amt_final);

echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='".$fclass24."' align='right' colspan='6'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='".$fclass24."' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='2' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";
$ea = 1; $eb = 1;
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";
if($wct_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass3."' align='right' colspan='4'>W.C.T @ ".number_format($wct_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass3."'>&nbsp;&nbsp;".number_format($wct_amt, 2, '.', '')."</td><td style='border:none' colspan=''>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($vat_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass5."' align='right' colspan='4'>VAT @  ".number_format($vat_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass5."'>&nbsp;&nbsp;".number_format($vat_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($lw_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass8."' align='right' colspan='4'>Labour Welfare CESS @ ".number_format($lw_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass8."'>&nbsp;&nbsp;".number_format($lw_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($mob_adv_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='".$fclass6."' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass6."'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$ea++; $ea_text = "";
}
if($incometax_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass10."' align='right' colspan='4'>Income Tax @ ".number_format($incometax_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass10."'>&nbsp;&nbsp;".number_format($incometax_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_cess_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass12."' align='right' colspan='4'>IT Cess @ ".number_format($it_cess_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass12."'>&nbsp;&nbsp;".number_format($it_cess_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($it_edu_percent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass14."' align='right' colspan='4'>IT Education CESS @ ".number_format($it_edu_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass14."'>&nbsp;&nbsp;".number_format($it_edu_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
//if($water_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass25."' align='right' colspan='4'>Water Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass25."'>".$water_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
//if($electricity_charge != 0)
//{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass26."' align='right' colspan='4'>Electricity Charges (as per Bill enclosed) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass26."'>&nbsp;&nbsp;".$electricity_charge_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
//}
if($land_rent != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass16."' align='right' colspan='4'>Rent for Land : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass16."'>&nbsp;&nbsp;".number_format($land_rent, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($liquid_damage != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass17."' align='right' colspan='4'>Liquidated Damages : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass17."'>&nbsp;&nbsp;".number_format($liquid_damage, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_1 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass18."' align='right' colspan='4'>".$other_recovery_1_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass18."'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass19."' align='right' colspan='4'>".$other_recovery_2_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass19."'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass22."' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass22."'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass23."' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass23."'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

//echo "<tr style='border:none'><td style='border:none' colspan='3' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='".$fclass27."' align='right' colspan='4'>Non-Submission of QA related document : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass27."'>".number_format($nonsubmission_qa, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";

if($sd_amt != 0)
{
$eb = 1;
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ec_text." (".$eb.")</td><td style='border:none' class='".$fclass1."' align='right' colspan='4'>Security Deposit @ ".$sd_percent."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='".$fclass1."'>&nbsp;&nbsp;".number_format($sd_amt, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}


echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
if($total_recovery != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'></td><td style='border:none' class='labelprint' align='right' colspan='4'>&nbsp;</td><td colspan='2' align='right' style='border:none; border-bottom:1px dashed #000000' class='labelprint'></td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
}

if($Overall_net_amt_final != 0)
{
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='5'><b>Net Payable Amount :</b> <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='6'><b>".number_format($Overall_net_amt_final, 2, '.', '')."</b></td><td style='border:none'>&nbsp;</td></tr>";
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
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>page ".$page."</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
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
echo "<tr class='labelprint'><td colspan='12' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'>Page ".$page."</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}
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

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> 
			</div>
			<div class="buttonsection">
			<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
			</div>
			<div class="buttonsection">
			<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
			</div>
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

</html>