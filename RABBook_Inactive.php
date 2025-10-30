<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "library/common.php";
$msg = '';
$rbn=$_SESSION['RBN'];
$sheetid=$_SESSION['Sheetid'];
if($_POST["Back"] == "Back") 
{
     header('Location: RunningbillView_Inactive.php');
}
$query = "SELECT    sheet_id    , sheet_name    , work_order_no  ,work_name , tech_sanction    , name_contractor    , agree_no    , rbn
FROM   sheet WHERE sheet_id ='$sheetid' ";
//echo $query;
$sqlquery = mysql_query($query);
if ($sqlquery == true) {
    $List = mysql_fetch_object($sqlquery);
    $work_name = $List->work_name;    $tech_sanction = $List->tech_sanction;   
    $name_contractor = $List->name_contractor;    $agree_no = $List->agree_no;
$work_order_no = $List->work_order_no;$runn_acc_bill_no=$rbn;
}
$fromdate_query = "select min(date(fromdate)) from measurementbook WHERE sheetid ='$sheetid' AND rbn <= '$rbn'";
$fromdate_sql = mysql_query($fromdate_query);
$todate_query = "select max(date(todate)) from measurementbook WHERE sheetid ='$sheetid' AND rbn <= '$rbn'";
$todate_sql = mysql_query($todate_query);
$rbnfromdate = @mysql_result($fromdate_sql,'fromdate');
$rbntodate = @mysql_result($todate_sql,'todate');
$fromdatesplit = explode("-",$rbnfromdate);
$fromdate = $fromdatesplit[2]."-".$fromdatesplit[1]."-".$fromdatesplit[0];
$todatesplit = explode("-",$rbntodate);
$todate = $todatesplit[2]."-".$todatesplit[1]."-".$todatesplit[0];
//$time=strtotime($fromdate);
/*$fromdate = "30-10-2015";
$next_date = date('d-m-Y', strtotime($fromdate .' +1 day'));
echo $next_date;exit;*/
/*$rbnmonth=date("F",$time);
$rbnyear=date("Y",$time);*/
/*echo $rbndate."<br/>";*/
/*echo $rbnfromdate."<br/>";
echo $rbntodate;exit;*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Running Account Bill View</title>
        <link rel="stylesheet" href="font.css" />
		 <link rel="stylesheet" href="script/font.css" />
		<link rel="stylesheet" href="css/button_style.css"></link>
        <script type="text/javascript">
   function showpage(textvalue,txtvalue)
   {
       //alert("text   " +textvalue +"  value   " +txtvalue)
       document.getElementById(textvalue).value = "B/f from P"+txtvalue +" TMS"; 
       
   }
   </script>
<style>
.right 
	{
		position: absolute;
		right: 0px;
		width: 300px;
		background-color: #b0e0e6;
	}
table
{ border-collapse: collapse; }
td 
{ border: 1px solid #CACACA; }
.headcolor{
/*color:white;
*/}
@media screen 
{
	div.divFooter 
	{
	display: none;
	}
}
@media print 
{
	div.divFooter 
	{
		position: fixed;
		bottom: 0;
	} 
}
</style>
</head>
<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript" language="javascript">
function pnr()
{
  x = confirm("NOTE: Set Paper Size A4, and 0.5 inch Margin to TOP,BOTTOM,LEFT,RIGHT.\n\nReady to Print-out ?")
  if (x == true)
  {
    document.getElementById("btn_print").style.display = 'none'
    window.print();
  }
}
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>

<body bgcolor="#000000" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<table width='1123px' border='0' cellpadding='0' cellspacing='0' height="60px" style="position:fixed; left:70px;" align='center' bgcolor='#20b2aa'>
<tr>
<td align="center" style="color:#FFFFFF; border:none; font-weight:bold; font-size:20px;">RUNNING ACCOUNT BILL VIEW</td>
</tr>
</table>
<br/><br/><br/>
<form name="form" method="post" style="">
<table width='90%' border='0' cellpadding='0' cellspacing='0' align='center' bgcolor='#c91622'>
<tr>
<td align="center" style="color:#FFFFFF; border:none; font-weight:bold; font-size:25px;">&nbsp;</td>
</tr>
</table>
<?php
$table = $table . "<table width='90%' class='headcolor labelcenter'  bgcolor='#B7E6FD' border='0' cellpadding='0' cellspacing='0' align='center' >";
$table = $table . "<tr>";
$table = $table . "<td width='17%' class='labelbold' align='left'>Name of work:</td>";
$table = $table . "<td width='43%' style='word-wrap:break-word' align='left'>" .$work_name."</td>";
$table = $table . "<td width='18%' class='labelbold' align='left'>Name of the contractor</td>";
$table = $table . "<td width='22%' align='left'>" . $name_contractor . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold' align='left'>Technical Sanction No.</td>";
$table = $table . "<td align='left'>" . $tech_sanction . "</td>";
$table = $table . "<td class='labelbold' align='left'>Agreement No.</td>";
$table = $table . "<td align='left'>" . $agree_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td class='labelbold' align='left'>Work order No.</td>";
$table = $table . "<td align='left'>" . $work_order_no . "</td>";
$table = $table . "<td class='labelbold' align='left'>Running Account bill No.</td>";
$table = $table . "<td align='left'>" . $runn_acc_bill_no . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Abstract Cost for Engineering Hall-IV for the period of ".$fromdate." to ".$todate."</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='90%' class='headcolor'  bgcolor='#ccffcc' border='1' cellpadding='0' cellspacing='0' align='center' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelcenter' width='7%' rowspan='2'>Item No.</td>";
$table = $table . "<td  align='center' class='labelcenter' width='20%' rowspan='2'>Description of work</td>";
$table = $table . "<td  align='center' class='labelcenter' width='8%' rowspan='2'>Contents or Area</td>";
$table = $table . "<td  align='center' class='labelcenter' width='7%' rowspan='2'>Rate<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelcenter' width='4%' rowspan='2'>Per</td>";
$table = $table . "<td  align='center' class='labelcenter' width='10%' rowspan='2'>Total value to Date<br />Rs.  P.</td>";
$table = $table . "<td  align='center' class='labelcenter' width='' colspan='3'>Deduct previous Measurements</td>";
$table = $table . "<td  align='center' class='labelcenter' width='' colspan='2'>Since Last Measurement</td>";
$table = $table . "<td  align='center' class='labelcenter' width='5%' rowspan='2'>Remarks</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td width='5%' align='center' class='labelcenter'>Page</td>";
$table = $table . "<td width='7%' align='center' class='labelcenter'>Quantity</td>";
$table = $table . "<td  width='10%'align='center' class='labelcenter'>Amount<br />Rs.  P.</td>";
$table = $table . "<td width='7%' align='center' class='labelcenter'>Quantity</td>";
$table = $table . "<td  width='10%' align='center' class='labelcenter'>Value<br />Rs.  P.</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
echo $table;
echo "<table width='90%' border='0' cellpadding='0' cellspacing='0' align='center' bgcolor='#FFFFFF'>";
$selectsubitemid_query = "select distinct subdivid from measurementbook WHERE rbn <= '$rbn' AND sheetid = '$sheetid' ORDER BY subdivid";
$selectsubitemid_sql = mysql_query($selectsubitemid_query);
while($Listsubdivid = mysql_fetch_array($selectsubitemid_sql)) { $subdivid_list .= $Listsubdivid['subdivid']."*"; }
$subdivisionlist = explode("*",rtrim($subdivid_list,"*"));
$overallslm_amt = 0;$overalldpm_amt = 0;$overall_amt = 0;
for($i=0;$i<count($subdivisionlist);$i++)
{
	$schduledetails = getschduledetails($sheetid,$subdivisionlist[$i]);
	$rateandremarks = explode('*',$schduledetails);
	/*SINCE LAST MEASUREMENT CHECKING STARTS HERE......*/
	$checkquery_slm = "select * from measurementbook WHERE sheetid = '$sheetid' AND subdivid = '$subdivisionlist[$i]' AND rbn = '$rbn'";
	$checksql_slm = mysql_query($checkquery_slm);
	if(mysql_num_rows($checksql_slm)>0) 
	{ 
		while($SLM_RBNList = mysql_fetch_array($checksql_slm))
		{
			$slm_qty = $SLM_RBNList['mbtotal']; $slm_mbno = $SLM_RBNList['mbno']; $slm_mbpage = $SLM_RBNList['mbpage'];
		} 
	}
	else 
	{ 
		$slm_qty = 0; $slm_abstmbno = ""; $slm_abstmbpage = ""; 
	}
	$x = 1; $dpm_qty = 0;
	/*DEDUCT PREVIOUS MEASUREMENT CHECKING STARTS HERE....*/
	while($x<$rbn)
	{
		$checkquery_dpm = "select * from measurementbook WHERE sheetid = '$sheetid' AND subdivid = '$subdivisionlist[$i]' AND rbn = '$x'";
		$checksql_dpm = mysql_query($checkquery_dpm);
		if(mysql_num_rows($checksql_dpm)>0)
		{
			while($DPM_RBNList = mysql_fetch_array($checksql_dpm))
			{
				$dpm_qty = $dpm_qty + $DPM_RBNList['mbtotal'];
				$dpm_abstmbno = $DPM_RBNList['abstmbookno']; 
				$dpm_abstmbpage = $DPM_RBNList['abstmbpage'];
			}
		}
		$x++;
	}
$total_qty = $slm_qty + $dpm_qty;
$total_amt = $total_qty * $rateandremarks[0];
$total_dpm_amt = $dpm_qty * $rateandremarks[0];
$total_slm_amt = $slm_qty * $rateandremarks[0];
?>	

<tr>
	<td width="7%" align="center" class='labelcenter'><?php echo getsubdivname($subdivisionlist[$i]);?></td>
	<td colspan="11" class='labelcenter' style="text-align:left"><?php echo getscheduledescription($subdivisionlist[$i]); ?></td>
</tr>
<?php
$mbooktype_query = "select DISTINCT flag from measurementbook WHERE subdivid = '$subdivisionlist[$i]' AND sheetid = '$sheetid'";
//echo "$mbooktype_query"."<br/>";
$mbooktype_sql = mysql_query($mbooktype_query);
$flagtype = @mysql_result($mbooktype_sql,0,'flag');
if($flagtype == 1) { $mbookdescription = "/General MB No."; }
if($flagtype == 2) { $mbookdescription = "/Steel MB No."; }
 ?>
<?php if($dpm_qty != 0) { ?>
<tr>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='20%' class='labelcenter'><?php   echo "Prev-Qty Vide Page ".$dpm_abstmbpage."/Abstract MB No.".$dpm_abstmbno; ?></td>
	<td  align='right' width='8%' class='labelcenter'><?php echo number_format($dpm_qty, 3, '.', ''); ?></td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='4%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
</tr>
<?php } ?>
<?php if($slm_qty != 0) { ?>
<tr>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='20%' class='labelcenter'><?php echo "Qty Vide Page ".$slm_mbpage.$mbookdescription.$slm_mbno; ?></td>
	<td  align='right' width='8%' class='labelcenter'><?php echo number_format($slm_qty, 3, '.', ''); ?></td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='4%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='right' width='10%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
</tr>
<?php } ?>
<tr>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='20%' class='labelcenter'><?php echo "Total"; ?></td>
	<td  align='right' width='8%' class='labelcenter'><?php echo number_format($total_qty, 3, '.', ''); ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php echo $rateandremarks[0]; ?></td>
	<td  align='center' width='4%' class='labelcenter'><?php echo $rateandremarks[1]; ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($total_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelcenter'><?php if($dpm_qty != 0) { echo "P".$dpm_abstmbpage."/MB".$dpm_abstmbno; } ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php echo number_format($dpm_qty, 3, '.', ''); ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($total_dpm_amt, 2, '.', ''); ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php echo number_format($slm_qty, 3, '.', ''); ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($total_slm_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
</tr>
<?php 
$overall_amt = $overall_amt + $total_amt;
$overalldpm_amt = $overalldpm_amt + $total_dpm_amt;
$overallslm_amt = $overallslm_amt + $total_slm_amt;
}
?>
<tr>
	<td  align='left' width='7%' class='labelcenter'>&nbsp;</td>
	<td  align='left' width='20%' class='labelcenter'><?php echo "Total Cost"; ?></td>
	<td  align='right' width='8%' class='labelcenter'><?php //echo number_format($total_qty, 3, '.', ''); ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php //echo $rateandremarks[0]; ?></td>
	<td  align='center' width='4%' class='labelcenter'><?php //echo $rateandremarks[1]; ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($overall_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelcenter'><?php //if($dpm_qty != 0) { echo "P".$dpm_abstmbpage."/MB".$dpm_abstmbno; } ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php //echo number_format($dpm_qty, 3, '.', ''); ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($overalldpm_amt, 2, '.', ''); ?></td>
	<td  align='right' width='7%' class='labelcenter'><?php //echo number_format($slm_qty, 3, '.', ''); ?></td>
	<td  align='right' width='10%' class='labelcenter'><?php echo number_format($overallslm_amt, 2, '.', ''); ?></td>
	<td  align='left' width='5%' class='labelcenter'>&nbsp;</td>
</tr>
</table>

<table border="0" width="90%" align="center" bgcolor="#FFFFFF" style="border-style:none;">
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none">&nbsp;
		</td>
	</tr>
	<tr border="0" style="border-style:none">
		<td border="0" style="border-style:none" align="center">
			<input type="hidden" class="text" name="submit" value="true" />
			<!--<input type="submit" class="btn" data-type="submit" value="Submit" name="Submit" id="Submit"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
			<input type="submit" class="btn"   data-type="submit" value="Back" name="Back" id="Back"   />
		</td>
	</tr>
</table>  

</form>
</body>
</html>