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
$staffid 		= 	$_SESSION['sid'];
$userid 		= 	$_SESSION['userid'];
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
if(($_GET['rbnView'] != "") && ($_GET['SheetidView'] != ""))
{
	$rbn 			= 	$_GET['rbnView'];
	$abstsheetid 	= 	$_GET['SheetidView'];
}
$select_rbndetail_sql		=	"select DISTINCT abstmbookno from measurementbook WHERE sheetid ='$abstsheetid' AND rbn = '$rbn'";
$select_rbndetail_query		=	mysql_query($select_rbndetail_sql);
$abstmbno 					= 	@mysql_result($select_rbndetail_query,'abstmbookno');

//$select_rbndetail_sql_1		=	"select DISTINCT min(abstmbpage) from measurementbook WHERE sheetid ='$abstsheetid' AND rbn = '$rbn'";
$select_rbndetail_sql_1		=	"select startpage from mymbook where sheetid ='$abstsheetid' AND rbn = '$rbn' AND mtype = 'A' AND genlevel = 'abstract'";
$select_rbndetail_query_1	=	mysql_query($select_rbndetail_sql_1);
//$abstmbpage 				= 	@mysql_result($select_rbndetail_query_1,'abstmbpage');
$abstmbpage 				= 	@mysql_result($select_rbndetail_query_1,'startpage');


$fromdate_query = 	"select min(date(fromdate)) from measurementbook WHERE sheetid ='$abstsheetid' AND rbn <= '$rbn'";
$fromdate_sql 	= 	mysql_query($fromdate_query);
$rbnfromdate 	= 	@mysql_result($fromdate_sql,'fromdate');
$fromdatesplit 	= 	explode("-",$rbnfromdate);
$fromdate 		= 	$fromdatesplit[2]."-".$fromdatesplit[1]."-".$fromdatesplit[0];

$todate_query 	= 	"select max(date(todate)) from measurementbook WHERE sheetid ='$abstsheetid' AND rbn <= '$rbn'";
$todate_sql 	= 	mysql_query($todate_query);
$rbntodate 		= 	@mysql_result($todate_sql,'todate');
$todatesplit 	= 	explode("-",$rbntodate);
$todate 		= 	$todatesplit[2]."-".$todatesplit[1]."-".$todatesplit[0];

/*
if($_POST["Submit"] == "Submit")
{	

	$max_page_abs 			= 	$_POST['txt_maxpage'];
	$abstmbno 				= 	$_POST['txt_abstmbno'];
	$mbook_start_page_abs 	= 	get_mbook_startpage($abstmbno,$abstsheetid);
	$start_page_abs 		= 	explode('*', $mbook_start_page_abs);
	$insert_mybmook_sql_3 	= 	"insert into mymbook set allotmentid = '$start_page_abs[1]', mbno = '$abstmbno', startpage = '$start_page_abs[0]', endpage = '$max_page_abs', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = 'A'";
	$insert_mybmook_query_3 = 	mysql_query($insert_mybmook_sql_3);
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
			$mbook_start_page_old 				= 	get_mbook_startpage($mbno,$abstsheetid);
			$start_page_old 					= 	explode('*', $mbook_start_page_old);
			$insert_mybmook_sql 				= 	"insert into mymbook set allotmentid = '$start_page_old[1]', mbno = '$mbno', startpage = '$start_page_old[0]', endpage = '100', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = '$mbooktype'";	
			$insert_mybmook_query 				= 	mysql_query($insert_mybmook_sql);
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
		$mbook_start_page 		= 	get_mbook_startpage($mbno,$abstsheetid);
		$strat_page 			= 	explode('*', $mbook_start_page);
		$insert_mybmook_sql_2 	= 	"insert into mymbook set allotmentid = '$strat_page[1]', mbno = '$mbno', startpage = '$strat_page[0]', endpage = '$mbookmaxpage', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 1, flag = '$mbooktype'";
		$insert_mybmook_query_2 = 	mysql_query($insert_mybmook_sql_2);
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
	//header('Location: AbsGenerate_Partpay.php');
}*/

//$deletembook_temp_sql = "DELETE FROM measurementbook_temp where sheetid = '$abstsheetid'";
//$deletembook_temp_query = mysql_query($deletembook_temp_sql);
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
SELECT mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  '100', flag, 0, rbn, active, userid, is_finalbill FROM mbookgenerate";
$insermbook_temp_query 		= 	mysql_query($insermbook_temp_sql);
}*/


$query 		= 	"SELECT    sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
$sqlquery 	= 	mysql_query($query);
if ($sqlquery == true) 
{
    $List 					= 	mysql_fetch_object($sqlquery);
    $work_name 				= 	$List->work_name; 
	$short_name 			= 	$List->short_name;   
	$tech_sanction 			= 	$List->tech_sanction;  
    $name_contractor 		= 	$List->name_contractor;    
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
	border: 1px solid #A3E1EF;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #A3E1EF;
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
<body bgcolor="#000000" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="" align="center">Abstract History - View </td>
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
$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividDpmStr = "";
//$unionqur = "(SELECT subdivid  FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND (part_pay_flag = '0' OR part_pay_flag = '1'))";
$unionqur = "select DISTINCT subdivid from measurementbook where sheetid = '$abstsheetid' AND rbn<='$rbn' AND (part_pay_flag = '0' OR part_pay_flag = '1')";
//echo $unionqur."<br/>";
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
<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:1px solid white;border-right:1px solid white;'>Page <?php echo $page; ?></td></tr>
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
						<td  align='right' width='' class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo number_format($dpm_measurement_qty, 3, '.', ''); ?></td>
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
						<td  align='right' width='' class='' rowspan="<?php echo $dpm_cnt; ?>"><?php echo number_format($dpm_measurement_qty, 3, '.', ''); ?></td>
						<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='right' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='left' width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='right' width='' class=''>
							<?php 
								echo number_format($dpmqty, 3, '.', ''); 
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
					<td  align='right' width='' class=''><?php echo number_format($dpmqty, 3, '.', ''); ?></td>
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
	$mbooktype_query = "select flag from measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' AND rbn = '$rbn'";
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
			<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>"><?php echo number_format($slm_measurement_qty, 3, '.', ''); ?></td>
			<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='left' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' width='' class=''>
				<?php 
					echo number_format($slmqty, 3, '.', ''); 
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
			<td  align='right' width='' class=''><?php echo number_format($slmqty, 3, '.', ''); ?></td>
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
		<?php echo number_format($total_qty_item, 3, '.', ''); ?>
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
		<?php echo number_format($dpm_measurement_qty, 3, '.', ''); ?>
		</td>
		<td  align='right' width='' class=''>
		<?php echo number_format($dpm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class=''>
		<?php echo number_format($slm_measurement_qty, 3, '.', ''); ?>
		</td>
		<td  align='right' width='' class=''>
		<?php echo number_format($slm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class=''><?php //echo $Line; ?>&nbsp;</td>
	</tr>
	<?php //UpdateItemAbstractPageNo($abstsheetid,$abstmbno,$subdivid,$page); ?>
	<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
	<tr bgcolor=""><td colspan="12">&nbsp;</td></tr>
	<?php  $rowcount++; $Line++;/*echo "F = ".$Line."<br/>";*/ //if($Line >= 28) { CheckPageBreak($tablehead,$abstmbno,$table,$page); $Line = $LineIncr; $page++;  echo $slm_amount_item."<br/>";} ?>
	<!--<tr bgcolor="#d4d8d8" style="height:10px"><td colspan="13" style="border-top-color:#0A9CC5; border-bottom-color:#0A9CC5;"></td></tr>-->
	<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
	<?php //echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>"; ?>
	<?php
	$color_var++; $table_group_row++;
	$OverAllSlmAmount 		=  $OverAllSlmAmount	+	$slm_amount_item; 
	$OverAllDpmAmount 		=  $OverAllDpmAmount	+	$dpm_amount_item; 
	$OverAllSlmDpmAmount 	=  $OverAllSlmDpmAmount	+	$total_amt_item;
}
//echo $Line;	
	$SlmRebateAmount 		=  $OverAllSlmAmount 	* 	$overall_rebate_perc /100;
	$DpmRebateAmount 		=  $OverAllDpmAmount 	* 	$overall_rebate_perc /100;
	$SlmDpmRebateAmount 	=  $OverAllSlmDpmAmount * 	$overall_rebate_perc /100;
	
	$SlmNetAmount 			=  $OverAllSlmAmount	-	$SlmRebateAmount; 
	$DpmNetAmount 			=  $OverAllDpmAmount	-	$DpmRebateAmount; 
	$SlmDpmNetAmount 		=  $OverAllSlmDpmAmount	-	$SlmDpmRebateAmount;
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
	<td style="border-bottom:1px dashed #CCCCCC;"></td>
</tr>
<?php
$Line = $LineIncr; $page++;
}
else
{
?>
<tr class='labelprint'><td colspan='12' align='center' style='border-bottom:2px solid white;border-left:1px solid white;border-right:1px solid white;'>
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
	$WRList 		= 	mysql_fetch_object($water_recovery_sql);
	$water_charge 	= 	$WRList->water_cost; 
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
	$ERList 				= 	mysql_fetch_object($electricity_recovery_sql);
	$electricity_charge 	= 	$ERList->electricity_cost; 
}
else
{
	$electricity_charge = 0;
}
$total_recovery = $total_recovery + $electricity_charge;
$general_recovery_query = "select * from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
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
	$other_recovery_1 	= 	round($GRList->other_recovery);
	$other_recovery_2	= 	round($GRList->other_recovery_2);
	$non_dep_machine_equip 	= 	round($GRList->non_dep_machine_equip);
	$non_dep_man_power 	= 	round($GRList->non_dep_man_power);
	$nonsubmission_qa 	= 	round($GRList->nonsubmission_qa);
}
//*========================================================================================================*//
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

//*========================================================================================================*//
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
echo "<tr style='border:none'><td style='border:none' class='labelprint' align='right' colspan='6'>Secured Advance : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' class='labelprint' align='right' colspan='3'>&nbsp;</td><td colspan='2' align='right' class='labelprint' style='border:none;'>".number_format($sec_adv_amount, 2, '.', '')."</td><td style='border:none;'>&nbsp;</td></tr>";
echo "<tr style='border:none'><td colspan='2' class='labelbold' align='right' style='border:none'>&nbsp;<u>Recoveries</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td style='border:none' class='labelbold' align='left' colspan='10'></td></tr>";
$ea = 1; $eb = 1;
$ea_text = "<b>Under 8[a]</b>"; $eb_text = "<b>Under 8[b]</b>";  $ec_text = "<b>Under 8[c]</b>";
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
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$ea_text." (".$ea.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Mobilization Advance @ ".number_format($mob_adv_percent, 2, '.', '')."% : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($mob_adv_amt, 2, '.', '')."</td><td style='border:none' colspan='1'>&nbsp;</td></tr>";
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
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Other Recoveries : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_1, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
$eb++; $eb_text = "";
}
if($other_recovery_2 != 0)
{
echo "<tr style='border:none'><td style='border:none' colspan='2' align='right' class='labelprint'>".$eb_text." (".$eb.")</td><td style='border:none' class='labelprint' align='right' colspan='4'>Other Recoveries : <i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'> </td><td style='border:none' colspan='5' align='right' class='labelprint'>&nbsp;&nbsp;".number_format($other_recovery_2, 2, '.', '')."</td><td colspan='1' style='border:none'>&nbsp;</td></tr>";
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
echo "<tr class='labelprint'><td colspan='12' style='border-bottom:2px solid white;border-left:2px solid white;border-right:2px solid white;border-top:2px solid #cacaca;' align='center'> </td></tr>";
echo "</table>";
echo "<p  style='page-break-after:always;'></p>";
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
}
?>
<input type="hidden" name="txt_abstmbno" id="txt_abstmbno" value="<?php echo $abstmbno; ?>" />
<input type="hidden" name="txt_maxpage" id="txt_maxpage" value="<?php echo $page; ?>" />

<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />
<div align="center" class="btn_outside_sect printbutton">
	<!--<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Submit" id="Submit" /> </div>-->
	<!--<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>-->
	<div class="btn_inside_sect"><input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
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