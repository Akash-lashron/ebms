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
$staffid = $_SESSION['sid'];
$userid  = $_SESSION['userid'];
$rbn     = $_SESSION["rbn"]; 
$abstsheetid    = $_SESSION["abstsheetid"];   $abstmbno = $_SESSION["abs_mbno"];  $abstmbpage  = $_SESSION["abs_page"];	
$fromdate       = $_SESSION['fromdate'];      $todate   = $_SESSION['todate'];    $abs_mbno_id = $_SESSION["abs_mbno_id"];
$paymentpercent = $_SESSION["paymentpercent"];
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
<!--<script type='text/javascript' src='js/basic.js'></script>
--><script type="text/javascript" language="javascript">
	function goBack()
	{
		url = "AbstractBookPrint_FullPay.php";
		window.location.replace(url);
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
	var index = 1;
	function addRow()
	{
		var arg = "X"+"*"+index;
		var rate = document.getElementById("txt_item_rate_slm0").value;
		var table=document.getElementById("table4");
        var row=table.insertRow(table.rows.length-1);
        	row.id = "rowid"+index;
			row.style.align = "center";
			
		var cell1=row.insertCell(0);
			cell1.setAttribute('class', "dynamicrowcell");
			cell1.style.padding = "0px 0px 0px 0px";
				
		var txt_box1 = document.createElement("input");
			txt_box1.name = "txt_partpay_qty_slm";
            txt_box1.id = "txt_partpay_qty_slm"+index;
			txt_box1.style.width = 120+"px";
			txt_box1.style.textAlign = "right";
			txt_box1.setAttribute('class', "dynamictextbox"); 
            cell1.appendChild(txt_box1);
			txt_box1.onblur= function () {
                        	  calculateAmount(this,arg,"qty")
                    		}
		var cell2=row.insertCell(1);
			cell2.setAttribute('class', "dynamicrowcell");	
			cell2.style.padding = "0px 0px 0px 0px";
		var txt_box2 = document.createElement("input");
			txt_box2.name = "txt_item_rate_slm";
            txt_box2.id = "txt_item_rate_slm"+index;
			txt_box2.value = Number(rate).toFixed(2);
			txt_box2.style.textAlign = "right";
			txt_box2.style.width = 110+"px";
			txt_box2.readOnly = true;
			txt_box2.setAttribute('class', "dynamictextbox"); 
            cell2.appendChild(txt_box2);
			txt_box2.onblur= function () {
                        	  calculateAmount(this,arg,"rate")
                    		}
		var cell3=row.insertCell(2);
			cell3.setAttribute('class', "dynamicrowcell");	
			cell3.style.padding = "0px 0px 0px 0px";
		var txt_box3 = document.createElement("input");
			txt_box3.name = "txt_partpay_percent_slm";
            txt_box3.id = "txt_partpay_percent_slm"+index;
			txt_box3.style.width = 55+"px";
			txt_box3.style.textAlign = "right";
			txt_box3.setAttribute('class', "dynamictextbox"); 
            cell3.appendChild(txt_box3);
			txt_box3.onblur= function () {
                        	  calculateAmount(this,arg,"percent")
                    		}
							
		var cell4=row.insertCell(3);
			cell4.setAttribute('class', "dynamicrowcell");	
			cell4.style.padding = "0px 0px 0px 0px";
		var txt_box4 = document.createElement("input");
			txt_box4.name = "txt_partpay_amt_slm[]";
            txt_box4.id = "txt_partpay_amt_slm"+index;
			txt_box4.style.width = 147+"px";
			txt_box4.style.textAlign = "right";
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
                        	  deleteRow(row.id)
                    		}
        	cell5.appendChild(delbtn);
			index++;
	}
	function deleteRow(id) 
	{
	   var row = document.getElementById(id);
	   row.parentNode.removeChild(row);
	   totalAmountCalculation();
	}
	function calculateAmount(obj,id,type)
	{
		var tempvar = id;
		var split_tempvar = tempvar.split("*");
		var idcount = split_tempvar[1];
		var currentvalue = obj.value;
		
		if(type == "qty")
		{
			var rate = document.getElementById("txt_item_rate_slm"+idcount).value;
			var percent = document.getElementById("txt_partpay_percent_slm"+idcount).value;
			var qty = currentvalue;
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
		rate = Number(rate);
		percent = Number(percent);
		if((qty != "") && (rate != "") && (percent != ""))
		{
			var amount = qty * rate * percent / 100;
			document.getElementById("txt_partpay_amt_slm"+idcount).value = Number(amount).toFixed(2);
		}
		else
		{
			document.getElementById("txt_partpay_amt_slm"+idcount).value = "";
		}
		
		totalAmountCalculation();
	}
	
	function totalAmountCalculation()
	{
		var amount = 0;
		/*var amt_textbox_count = document.getElementsByName("txt_partpay_amt_slm");
		var i;
		var amount = 0;
		for(i=0; i<amt_textbox_count.length; i++)
		{
			var amt = document.getElementById("txt_partpay_amt_slm"+i).value;
			amount = (Number(amount)+Number(amt));
		}*/
		$('input[name="txt_partpay_amt_slm[]"]').each(function() {
			var amt = $(this).val();
			amount = (Number(amount)+Number(amt));
		});
		if(amount>0)
		{
			document.getElementById("txt_partpay_total_amt_slm").value = Number(amount).toFixed(2);
		}
		else
		{
			document.getElementById("txt_partpay_total_amt_slm").value = "";
		}
	}
	
	jQuery(function ($) 
	{
	// Load dialog on click
		$('input[name="check"]').click(function (e) 
		{
			if($(this).is(':checked'))
			{
				var itemdetails = this.value;
				var split_itemdetails = itemdetails.split("*");
				var subdivid 	= split_itemdetails[0];
				var subdivname 	= split_itemdetails[1];
				var description = split_itemdetails[2];
				var slm_qty		= Number(split_itemdetails[3]);
				var dpm_qty		= Number(split_itemdetails[4]);
				var rate 		= Number(split_itemdetails[5]);
				var itemunit	= split_itemdetails[6];
					document.getElementById("txt_item_no").value = subdivname;
					document.getElementById("txt_item_desc").value = description;
					document.getElementById("txt_slm_qty").value = slm_qty.toFixed(3)+" "+itemunit;
					document.getElementById("txt_dpm_qty").value = dpm_qty.toFixed(3)+" "+itemunit;
					document.getElementById("txt_item_rate_slm0").value = rate.toFixed(2);
				var tablerow;
					tablerow = "<tr><td>"+subdivname+"</td><td>"+description+"</td></tr>";
					//$('#table2 tr:last').after(tablerow);
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
.table1 td:hover
{
	color:#1013A0;
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
	border:1px solid #9BA2A0;
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #9BA2A0;
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
.dynamicrowcell
{
	padding-bottom:0px;
	padding-top:0px; 
	padding-left:0px; 
	padding-right:0px;
	text-align:right;
	font:Verdana, Arial, Helvetica, sans-serif;
}
/*.table1 tr:nth-child(even) {background: #CCC}
.table1 tr:nth-child(odd) {background: #FFF}*/
</style>		
<body bgcolor="black" onload="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<table width="1087px" height="56px" align="center" class='label' bgcolor="#00A19C">
<tr bgcolor="#00A19C" style="position:fixed;">
<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
</tr>
</table>
<form name="form" method="post">
<?php
$title = '<table width="1087px" border="0"  cellpadding="1" cellspacing="1" align="center" bgcolor="#FFFFFF" style="border:none;" class="label">
			<tr style="border:none;"><td align="right" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
			</table>';
echo $title;
/*$table = $table . "<table width='1087px'  bgcolor='#f2f5f9' border='0' cellpadding='1' cellspacing='1' align='center' class='label' >";
$table = $table . "<tr>";
$table = $table . "<td width='17%' class='labelbold labelheadblue'>Name of work:</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' class='label labelheadblue'>" .$work_name."</td>";
$table = $table . "<td width='18%' class='labelbold labelheadblue'>Name of the contractor</td>";
$table = $table . "<td width='22%' class='label labelheadblue'>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold labelheadblue'>Technical Sanction No.</td>";
$table = $table . "<td class='label labelheadblue'>" . $tech_sanction . "</td>";
$table = $table . "<td class='labelbold labelheadblue'>Agreement No.</td>";
$table = $table . "<td class='label labelheadblue'>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold labelheadblue'>Work order No.</td>";
$table = $table . "<td class='label labelheadblue'>" . $work_order_no . "</td>";
$table = $table . "<td class='labelbold labelheadblue'>Running Account bill No.</td>";
$table = $table . "<td class='label labelheadblue'>" . $runn_acc_bill_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' class='label labelheadblue' align='center'>Abstract Cost for Ware House for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='1087px'  bgcolor='#f2f5f9' border='1' cellpadding='1' cellspacing='1' align='center' id='mbookdetail' class='label'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='3%' rowspan='2'></td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='7%' rowspan='2'>Item No.</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='19%' rowspan='2'>Description of work</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='8%' rowspan='2'>Contents of Area</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='7%' rowspan='2'>Rate<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='4%' rowspan='2'>Per</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='9%' rowspan='2'>Total value to Date<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='' colspan='3'>Deduct previous Measurements</td>";
$table = $table . "<td  align='center' class='labelsmall labelheadblue' width='' colspan='3'>Since Last Measurement</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td width='5%' align='center' class='labelsmall labelheadblue'>Page</td>";
$table = $table . "<td width='7%' align='center' class='labelsmall labelheadblue'>Quantity</td>";
$table = $table . "<td  width='10%'align='center' class='labelsmall labelheadblue'>Amount<br />Rs.  P.</td>";
$table = $table . "<td width='6%' align='center' class='labelsmall labelheadblue'>Quantity</td>";
$table = $table . "<td width='3%' align='center' class='labelsmall labelheadblue'>Pay<br/>(%)</td>";
$table = $table . "<td  width='9%' align='center' class='labelsmall labelheadblue'>Value<br />Rs.  P.</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
?>
<?php echo $table; ?>*/
?>
<table width='1087px' cellpadding='3' cellspacing='3' align='center' class='label table1' bgcolor="#FFFFFF" id="table1">
<?php 
$color_var = 0; $table_group_row = 0;
$unionqur = "(SELECT subdivid  FROM mbookgenerate WHERE sheetid = '$abstsheetid') UNION (SELECT subdivid  FROM measurementbook WHERE sheetid = '$abstsheetid' AND part_pay_flag = '0')";
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
	/*if($color_var == 0) { $color = "#4169E1"; }
	if($color_var == 1) { $color = "#4169E1"; }
	if($color_var == 2) { $color = "#4169E1"; }
	if($color_var == 3) { $color = "#4169E1"; $color_var = -1; }*/
	/*if($color_var == 0) { $color = "#8DFBC6"; }
	if($color_var == 1) { $color = "#4AD2FE"; }
	if($color_var == 2) { $color = "#FB96C3"; }
	if($color_var == 3) { $color = "#B1A5FC"; $color_var = -1; } 02.11.2015*/
/*	if($color_var == 0) { $color = "#b4daf7"; }
	if($color_var == 1) { $color = "#f7d7a0"; }
	if($color_var == 2) { $color = "#f7bed7"; }
	if($color_var == 3) { $color = "#d8f7be"; $color_var = -1; }*/
	$slm_mesurementbook_details = ""; $dpm_mesurementbook_details = "";
	$slm_measurement_qty = 0; $dpm_measurement_qty = 0; $slm_cnt = 0; $dpm_cnt = 0;  $rowcount = 0;
	$schduledetails = getschduledetails($abstsheetid,$subdivisionlist[$i]);
	$rateandunit = explode('*',$schduledetails);
	$rate = $rateandunit[0];
	$unit = $rateandunit[1];
//*************THIS PART IS FOR SINCE LAST MEASUREMENT ( S.L.M. ) SECTION*******************//

	$mbookslmquery = "SELECT * FROM mbookgenerate WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid'";
	$mbookslmquery_sql = mysql_query($mbookslmquery);
	if(mysql_num_rows($mbookslmquery_sql)>0)
	{
		while($SLMList = mysql_fetch_array($mbookslmquery_sql))
		{
			$slm_mesurementbook_details .= $SLMList['subdivid']."*".$SLMList['mbtotal']."*".$SLMList['mbno']."*".$SLMList['mbpage']."*";
			$slm_cnt++;
		}
	}
	else
	{
		$slm_measurement_qty = 0;
		$slm_cnt = 0;
	}
//*************THIS PART IS FOR DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*******************//

	$mbookdpmquery = "SELECT * FROM measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$abstsheetid' AND  part_pay_flag = '0'";
	$mbookdpmquery_sql = mysql_query($mbookdpmquery);
	if(mysql_num_rows($mbookdpmquery_sql)>0)
	{
		while($DPMList = mysql_fetch_array($mbookdpmquery_sql))
		{
			$dpm_mesurementbook_details .= $DPMList['subdivid']."*".$DPMList['mbtotal']."*".$DPMList['abstmbookno']."*".$DPMList['abstmbpage']."*";
			$dpm_cnt++;
		}
	}
	else
	{
		$dpm_measurement_qty = 0;
		$dpm_cnt = 0;
	}
//--*************THIS PART IS FOR FIND SLM QUANTITY SECTION*****************//
	$slm_explodval = explode("*",rtrim($slm_mesurementbook_details,"*"));
	for($x1=0; $x1<count($slm_explodval); $x1+=4)
	{
		$slm_measurement_qty = $slm_measurement_qty + $slm_explodval[$x1+1];
		$mbookno_slm 	= 	$slm_explodval[$x1+2];
		$mbpageno_slm 	= 	$slm_explodval[$x1+3];
	}
//--*************THIS PART IS FOR FIND DPM QUANTITY SECTION*****************//
	$dpm_explodval = explode("*",rtrim($dpm_mesurementbook_details,"*"));
	for($x2=0; $x2<count($dpm_explodval); $x2+=4)
	{
		$dpm_measurement_qty = $dpm_measurement_qty + $dpm_explodval[$x2+1];
		$absmbookno_dpm 	= 	$dpm_explodval[$x2+2];
		$absmbpageno_dpm 	= 	$dpm_explodval[$x2+3];
	}
$subdivid = $subdivisionlist[$i];
$subdivname = getsubdivname($subdivisionlist[$i]);
$description = getscheduledescription($subdivisionlist[$i]);
$checkbox_str = $subdivid."*".$subdivname."*".$description."*".$slm_measurement_qty."*".$dpm_measurement_qty."*".$rate."*".$unit;

//--*************THIS PART IS FOR " PRINT " Item Name, Description and Check Box  SECTION********************//
?>
<tr border='1' bgcolor="#4169E1">
	<td  align='center' width='3%' class='labelsmall' style="border-top-color:#666666; border-bottom-color:#666666; background-color:#0e82b0" id="td_popupbutton<?php echo $table_group_row; ?>">
		<input type="checkbox" name="check" id="ch_item<?php echo $table_group_row; ?>" value="<?php echo $checkbox_str; ?>"  />
	</td>
	<td width="7%" align="center" style="border-top-color:#666666;" class="fontcolor1">
		<?php echo $subdivname;?>
	</td>
	<td colspan="8" style="border-top-color:#666666;" class="fontcolor1">
		<?php echo $description; ?>
	</td>
	<td style="border-top-color:#666666;">&nbsp;</td>
	<td style="border-top-color:#666666;">&nbsp;</td>
	<td style="border-top-color:#666666;">&nbsp;</td>
</tr>
<?php 
$rowcount++;
//--*************THIS PART IS FOR " PRINT " DEDUCT PREVIOUS MEASUREMENT ( D.P.M. ) SECTION*****************//
	
	if($dpm_cnt > 0)
	{
?>
		<tr border='1' bgcolor="#FFFFFF">
			<!--<td  align='left' width='3%' class=''>&nbsp;</td>-->
			<td  align='left' width='7%' class=''>&nbsp;</td>
			<td  align='left' width='19%' class=''>
			<?php echo "Prev-Qty Vide P ".$absmbpageno_dpm."/Abstract MB No.".$absmbookno_dpm; ?>
			</td>
			<td  align='right' width='8%' class=''>
			<?php echo $dpm_measurement_qty; ?>
			</td>
			<td  align='left' width='7%' class=''>&nbsp;</td>
			<td  align='left' width='4%' class=''>&nbsp;</td>
			<td  align='right' width='9%' class=''>&nbsp;</td>
			<td  align='left' width='5%' class=''>&nbsp;</td>
			<td  align='right' width='7%' class=''>&nbsp;</td>
			<td  align='right' width='10%' class=''>&nbsp;</td>
			<td  align='right' width='6%' class=''>&nbsp;</td>
			<td  align='right' width='3%' class=''>&nbsp;</td>
			<td  align='right' width='9%' class=''>&nbsp;</td>
		</tr>	
<?php 
	$rowcount++;
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
?>
		<tr border='1' bgcolor="#FFFFFF">
			<!--<td  align='left' width='3%' class=''>&nbsp;</td>-->
			<td  align='left' width='7%' class=''>&nbsp;</td>
			<td  align='left' width='19%' class=''>
			<?php echo "Qty Vide P ".$mbpageno_slm.$mbookdescription.$mbookno_slm; ?>
			</td>
			<td  align='right' width='8%' class=''>
			<?php echo $slm_measurement_qty; ?>
			</td>
			<td  align='left' width='7%' class=''>&nbsp;</td>
			<td  align='left' width='4%' class=''>&nbsp;</td>
			<td  align='right' width='9%' class=''>&nbsp;</td>
			<td  align='left' width='5%' class=''>&nbsp;</td>
			<td  align='right' width='7%' class=''>&nbsp;</td>
			<td  align='right' width='10%' class=''>&nbsp;</td>
			<td  align='right' width='6%' class=''>&nbsp;</td>
			<td  align='right' width='3%' class=''>&nbsp;</td>
			<td  align='right' width='9%' class=''>&nbsp;</td>
		</tr>
<?php
	$rowcount++;
	}
	
//*************THIS PART IS FOR " PRINT " ---- TOTAL PART ( S.L.M. + D.P.M ) SECTION*******************//	
$total_qty_item = $dpm_measurement_qty + $slm_measurement_qty;
$total_amt_item = $total_qty_item * $rate;
$slm_amt_item = $slm_measurement_qty * $rate;
$dpm_amt_item = $dpm_measurement_qty * $rate;
?>
	<tr border='1' class="label" bgcolor="#FFFFFF">
		<!--<td  align='left' width='3%' class=' label' style="border-bottom-color:#666666">&nbsp;</td>-->
		<td  align='left' width='7%' class='' style="border-bottom-color:#666666">&nbsp;</td>
		<td  align='right' width='19%' class='label' style="border-bottom-color:#666666">TOTAL</td>
		<td  align='right' width='8%' class='' style="border-bottom-color:#666666">
		<?php echo $total_qty_item; ?>
		</td>
		<td  align='right' width='7%' class='' style="border-bottom-color:#666666">
		<?php echo $rate; ?>
		</td>
		<td  align='left' width='4%' class='' style="border-bottom-color:#666666">
		<?php echo $unit; ?>
		</td>
		<td  align='right' width='9%' class='' style="border-bottom-color:#666666">
		<?php echo $total_amt_item; ?>
		</td>
		<td  align='left' width='5%' class='' style="border-bottom-color:#666666">&nbsp;</td>
		<td  align='right' width='7%' class='' style="border-bottom-color:#666666">
		<?php echo $dpm_measurement_qty; ?>
		</td>
		<td  align='right' width='10%' class='' style="border-bottom-color:#666666">
		<?php echo $dpm_amt_item; ?>
		</td>
		<td  align='right' width='6%' class='' style="border-bottom-color:#666666">
		<?php echo $slm_measurement_qty; ?>
		</td>
		<td  align='right' width='3%' class='' style="border-bottom-color:#666666">
		<?php echo $slm_amt_item; ?>
		</td>
		<td  align='right' width='9%' class='' style="border-bottom-color:#666666">&nbsp;</td>
	</tr>
	<?php  $rowcount++; ?>
	<tr bgcolor="#DBDBDB"><td colspan="13" style="border-top-color:#666666; border-bottom-color:#666666;">&nbsp;</td></tr>
	<input type="hidden" name="row_count" id="row_count<?php echo $table_group_row; ?>" value="<?php echo $rowcount; ?>" />
	<?php
	$color_var++; $table_group_row++;
}
?>
</table>
<input type="hidden" name="table_group_count" id="table_group_count" value="<?php echo $table_group_row; ?>" />

<div align="center" class="btn_outside_sect printbutton">
	<div class="btn_inside_sect"><input type="Submit" name="Submit" value="Next" id="Submit" /> </div>
	<div class="btn_inside_sect"><input type="button" name="Back" value="Back" id="back" class="backbutton" onclick="goBack();" /> </div>
</div> 

		<!-- modal content -->
		<div id="basic-modal-content">
			<div align="center" class="popuptitle">Part Payment Work Sheet</div>
			<div align="center" style="padding-top:10px;">
			<table class="label table2" width="100%" cellpadding="3" cellspacing="3" id="table2">
				<tr bgcolor="">
					<td width="68px" align="center">Item No.</td>
					<td width="">
						<input type="text" name="txt_item_no" id="txt_item_no" size="8" class="popuptextbox" />
					</td>
					<td width="135px" align="center">Item Description</td>
					<td width="700px" align="center" colspan="7">
						<textarea name="txt_item_desc" id="txt_item_desc" class="popuptextbox" rows="2" style="text-align:left; width:655px; height:34px;"></textarea>
					</td>
				</tr>
				<tr bgcolor="">
					<td width="" colspan="2">&nbsp;</td>
					<td width="" colspan="4" align="center">Measurement Date</td>
					<!--<td width="" colspan="3">Since Last Measurement Qty.</td>
					<td width="">
						<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="12" class="popuptextbox" style="text-align:right;" />
					</td>-->
				</tr>
				<tr bgcolor="">
					<td width="64px" align="center">RAB No.</td>
					<td width="">
						<input type="text" name="txt_rab_no" id="txt_rab_no" size="6" class="popuptextbox" value="<?php echo $rbn; ?>" />
					</td>
					<td width="50px" align="center">From Date</td>
					<td width="" align="center">
						<input type="text" name="txt_from_date" id="txt_from_date" size="9" class="popuptextbox" value="<?php echo dt_display($fromdate); ?>" />
					</td>
					<td width="" align="center">To Date</td>
					<td width="" align="center">
						<input type="text" name="txt_to_date" id="txt_to_date" size="9" class="popuptextbox" value="<?php echo dt_display($todate); ?>" />
					</td>
					<!--<td width="" align="center">SLM Qty.</td>-->
					<!--<td width="" align="center">
						<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="10" class="popuptextbox" />
					</td>-->
					<!--<td width="" colspan="3">Deduct Previous Measurement Qty.</td>
					<td width="" align="center">
						<input type="text" name="txt_dpm_qty" id="txt_dpm_qty" size="12" class="popuptextbox" style="text-align:right;" />
					</td>-->
				</tr>

			</table>
			</div>
			<!--<div align="center" style="padding-top:3px;">
			<table class="label table2" width="100%" cellpadding="2" cellspacing="2">
				<tr bgcolor="##007AAB" style="color:#FFFFFF">
					<td width="50%" align="center">Since Last Measurement</td>
					<td width="50%" align="center">Deduct Previous Measurement</td>
				</tr>
				<tr>
					<td width="50%" align="center">1</td>
					<td width="50%" align="center">1000</td>
				</tr>
			</table>
			</div>-->
			<div style="padding-top:10px;">
				<div style="float:left; width:486px;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="100%" id="table3">
					<tr bgcolor="#0080ff" style="color:#FFFFFF">
						<td align="center" colspan="5">Deduct Previous Measurement</td>
					</tr>
					<tr>
						<td align="center" colspan="3">
						Deduct Previous Measurement Qty.
						</td>
						<td align="center" colspan="2">
						<input type="text" name="txt_dpm_qty" id="txt_dpm_qty" size="12" class="popuptextbox" style="text-align:center;" />
						</td>
					</tr>
					<tr>
						<td width="61px" align="center">Item Qty.</td>
						<td width="63px" align="center">Rate</td>
						<td width="23px" align="center">( % )</td>
						<td width="50px" align="center">Amount</td>
						<td width="10px" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="61px" align="center" class="dynamicrowcell">
						<input type="text" name="txt_partpay_qty_dpm" id="txt_partpay_qty_dpm" class="dynamictextbox" style="text-align:center; width:120px;" />
						</td>
						<td width="63px" align="center" class="dynamicrowcell">
						<input type="text" name="txt_item_rate_dpm" id="txt_item_rate_dpm" class="dynamictextbox" style="text-align:right; width:110px;" />
						</td>
						<td width="23px" align="center" class="dynamicrowcell">
						<input type="text" name="txt_partpay_percent_dpm" id="txt_partpay_percent_dpm" class="dynamictextbox" style="text-align:center; width:55px;" />
						</td>
						<td width="50px" align="center" class="dynamicrowcell">
						<input type="text" name="txt_partpay_amt_dpm" id="txt_partpay_amt_dpm" class="dynamictextbox" style="text-align:center; width:147px;" />
						</td>
						<td width="10px" align="center" class="dynamicrowcell">
						<input type="button" name="btn_add_row_dpm" id="btn_add_row_dpm" value=" + " style="widows:10px;" />
						</td>
					</tr>
				</table>
				</div>
				<div style="float:right;  width:486px;">
					<table class="label table2" cellpadding="3" cellspacing="3" width="100%" id="table4">
						<tr bgcolor="#0080ff" style="color:#FFFFFF">
							<td align="center" colspan="5">Since Last Measurement</td>
						</tr>
						<tr>
							<td align="center" colspan="3">
							Since Last Measurement Qty.
							</td>
							<td align="center" colspan="2">
							<input type="text" name="txt_slm_qty" id="txt_slm_qty" size="12" class="popuptextbox" style="text-align:center;" />
							</td>
						</tr>
						<tr>
							<td width="61px" align="center">Item Qty.</td>
							<td width="63px" align="center">Rate&nbsp;( <i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i> )</td>
							<td width="23px" align="center">( % )</td>
							<td width="50px" align="center">Amount&nbsp;( <i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i> )</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td width="61px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_qty_slm" id="txt_partpay_qty_slm0" class="dynamictextbox" style="text-align:right; width:120px;" onblur="calculateAmount(this,'X*0','qty');" />
							</td>
							<td width="63px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_item_rate_slm" readonly="" id="txt_item_rate_slm0" class="dynamictextbox" style="text-align:right; width:110px;" onblur="calculateAmount(this,'X*0','rate');" />
							</td>
							<td width="23px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_percent_slm" id="txt_partpay_percent_slm0" class="dynamictextbox" style="text-align:right; width:55px;" onblur="calculateAmount(this,'X*0','percent');" />
							</td>
							<td width="50px" align="center" class="dynamicrowcell">
							<input type="text" name="txt_partpay_amt_slm[]" id="txt_partpay_amt_slm0" class="dynamictextbox" style="text-align:right; width:147px;" />
							</td>
							<td width="10px" align="center" class="dynamicrowcell">
							<input type="button" name="btn_add_row_slm" id="btn_add_row_slm" class="editbtnstyle" value=" + " style="width:32px; font-weight:bold; border-radius: 0px;" onclick="addRow();" />
							</td>
						</tr>
						<tr>
							<td width="147px" colspan="3" align="right">Total Amount&nbsp;<i class='fa fa-inr' style='line-height: 2; width:6px; height:5px;'></i></td>
							<td width="50px" align="right"  class="dynamicrowcell">
							<input type="text" name="txt_partpay_total_amt_slm" id="txt_partpay_total_amt_slm" class="dynamictextbox" style="text-align:right; width:147px;pointer-events: none;" />
						</td>
							<td width="10px" align="center">&nbsp;</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="bottomsection" align="center">
				<div class="buttonsection" align="center"><input type="button" name="btn_save" id="btn_save" value=" Save " class="buttonstyle" /></div>
				<div class="buttonsection" align="center"><input type="button" name="btn_cancel" id="btn_cancel" value=" Cancel " class="buttonstyle" /></div>
			</div>
		</div>
		
		<!-- preload the images -->
		<div style='display:none'>
			<img src='img/basic/x.png' alt='' />
		</div>     
        </form>
    </body>

</html>