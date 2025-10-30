<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
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
if(isset($_POST["btn_view"]) == " View ")
{
	$sheetid = $_POST["cmb_shortname"];
	$rbn 	 = $_POST["cmb_rbn"];
	$select_mb_page_query = "select mbookno, page from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_mb_page_sql = mysql_query($select_mb_page_query);
	if($select_mb_page_sql == true){
		$MBPGList = mysql_fetch_object($select_mb_page_sql);
		$mbno = $MBPGList->mbookno;
		$page = $MBPGList->page;
	}
}

$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
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
//$selectRbnQuery 	= "select max(rbn) as prev_rbn from secured_advance where sheetid = '$sheetid' and rbn < '$rbn'";
$selectRbnQuery 	= "select * from secured_advance where sheetid = '$sheetid' and rbn = (select max(rbn) from secured_advance where sheetid = '$sheetid' and rbn < '$rbn')";
$selectRbnSql 		= mysql_query($selectRbnQuery);
if($selectRbnSql == true){
	$RabList 		= mysql_fetch_object($selectRbnSql);
	$PrevRbn 		= $RabList->rbn;
	//$PrevSaAmountTmp 	= $RabList->sec_adv_amount;
	$PrevSaAmountTmp 	= $RabList->upto_dt_ots_amt;
	
	$PrevSaMBno 	= $RabList->mbookno;
	$PrevSaPage 	= $RabList->page;
	$PrevSaAmount = 0; $Exe = 0;
	$selectPrevAmtQuery 	= "select upto_dt_amt from secured_advance_dt where sheetid = '$sheetid' and rbn = '$PrevRbn'";
	$selectPrevAmtSql 		= mysql_query($selectPrevAmtQuery);
	if($selectPrevAmtSql == true){
		while($PrevAmtList 	= mysql_fetch_object($selectPrevAmtSql)){
			$PrevSaAmount 	= $PrevSaAmount + $PrevAmtList->upto_dt_amt;
			$Exe++;
		}
	}
	if($Exe == 0){
		//$PrevSaAmount = round($PrevSaAmountTmp,2);
	}else{
		//$PrevSaAmount = round($PrevSaAmount,2);
	}
	$PrevSaAmount = round($PrevSaAmountTmp,2);
	//$PrevSaAmount = round($PrevSaAmount,2);
}
$select_mb_page_query = "select mbookno, mbookpage from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
$select_mb_page_sql = mysql_query($select_mb_page_query);
if($select_mb_page_sql == true){
	if(mysql_num_rows($select_mb_page_sql)>0){
		$MBPgList = mysql_fetch_object($select_mb_page_sql);
		$co_mbook = $MBPgList->mbookno;
		$co_mbpage = $MBPgList->mbookpage;
	}
}
if(($co_mbook != "") && ($co_mbpage != "")){
	$carry_over_str = "C/O MB-".$co_mbook."/ Pg-".($co_mbpage+1);
}else{
	$carry_over_str = "";
}

/*$selectRbnQuery 	= "select max(rbn) as prev_rbn, sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn < '$rbn' group by sheetid";
$selectRbnSql 		= mysql_query($selectRbnQuery);
if($selectRbnSql == true){
	$RabList 		= mysql_fetch_object($selectRbnSql);
	$PrevRbn 		= $RabList->prev_rbn;
	$PrevSaAmount 	= $RabList->sec_adv_amount;
}*/
//echo $PrevRbn;exit;
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript" language="javascript">
	function printBook()
	{
		window.print();
	}
	function goBack()
	{
		url = "SecuredAdvanceViewGenerate.php";
		window.location.replace(url);
	}
</script>
<style>
body{
}
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
	border: 1px solid #49494A;
	border-collapse: collapse;
	line-height:14px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
table td{
	padding:4px;
	vertical-align:middle;
}
.table1 td
{ 
	border: 1px solid #49494A;
	border-collapse: collapse;
	padding:4px;
	vertical-align:middle;
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
	border:1px solid #49494A;
	border-collapse: collapse;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.table2 td
{
	border:1px solid #49494A;
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
	border:1px solid #2aade4;
	box-shadow: 0 0 7px #2aade4;
	color:#DE0117;
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
	font-size:9pt;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}

/*td div {
  transform: rotate(-90deg);
  display:block;
    padding-top:40px;
	 padding-bottom:40px;
}*/

/*div.vertical
{
 margin-left: 0px;
 
 transform: rotate(-90deg);
 -webkit-transform: rotate(-90deg); 
 -moz-transform: rotate(-90deg); 
 -o-transform: rotate(-90deg); 
 -ms-transform: rotate(-90deg); 
}

td.vertical
{
 height: 94px;
 line-height: 14px;
 text-align: left;
}*/
.col-md{
	width:80%;
	text-align:left;
	font-size:11px;
}
</style>		
<body class="page1" id="top" oncontextmenu="return false" onLoad="setRowSpan();noBack();" onpageshow="if (event.persisted) noBack();">
<?php include "Menu.php"; ?>
<!--<table width="1087px" height="56px" align="center" class='label' bgcolor="#0A9CC5">
	<tr bgcolor="#0A9CC5" style="position:fixed;">
		<td style="color:#FFFFFF; border:none; font-size:16px;" width="1077px"  height="48px" class="pagetitle" align="center">ABSTRACT MEASUREMENT BOOK - PART PAYMENT</td>
	</tr>
</table>-->
  <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Secured Advance View</div>
            <div class="container_12">
                <div class="grid_12" align="center">
                    <blockquote class="bq1" style="overflow:auto;">
						<div class="smediv"></div>
<form name="form" method="post" onSubmit="return confirm('Do you really want to submit the Book?');" style=" border:2px solid #B0B1B1">
	<div class="smediv"></div>
<?php
//$page = $abstmbpage;
$satitle1 = '<table width="90%" border="0" align="center" bgcolor="#FFFFFF" style="border:none; line-height:12px" class="labelprint">
			<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$co_mbook.'&nbsp;&nbsp;&nbsp;</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">FORM 26 A</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;" class="labelbold">ACCOUNT OF SECURED ADVANCES</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">(<i>Referred to in paragraphs 10.2.14</i>)</td></tr>
			<tr style="border:none;"><td align="center" style="border:none;">(<i>To be annexed to Form 26 where necessary</i>)</td></tr>
		 </table>';
echo $satitle1;

$satitle2 = $satitle2 . "<table width='90%'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >";
$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td width='17%' class=''>Division </td>";
$satitle2 = $satitle2 . "<td width='43%' style='word-wrap:break-word' class=''>:&nbsp;FRFCF</td>";
$satitle2 = $satitle2 . "<td width='18%' class=''>Name of the work</td>";
$satitle2 = $satitle2 . "<td width='22%' class=''>:&nbsp;" . $work_name . "</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Name of the Contractor</td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;" . $name_contractor . "</td>";
$satitle2 = $satitle2 . "<td class=''>Technical Sanction No.</td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;".$tech_sanction."</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Cash Book Voucher No.</td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;------------------- Dated:</td>";
$satitle2 = $satitle2 . "<td class=''>Work order No. </td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;".$work_order_no."</td>";
$satitle2 = $satitle2 . "</tr>";

$satitle2 = $satitle2 . "<tr>";
$satitle2 = $satitle2 . "<td class=''>Running Account bill No.</td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;" . $rbn . "</td>";
$satitle2 = $satitle2 . "<td class=''>Agreement No.</td>";
$satitle2 = $satitle2 . "<td class=''>:&nbsp;" . $agree_no . "</td>";
$satitle2 = $satitle2 . "</tr>";
$satitle2 = $satitle2 . "</table>";
echo $satitle2;
?>
<table width='90%'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >
	<tr>
		<td class="labelbold">Civil Secured Advance</td>
		<td class="labelbold"> Account of Secured Advance allowed on the Security Materials Brought to Site</td>
	</tr>
</table>
<table width='90%'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >
	<tr>
		<td align="center">Sl No.</td>
		<td align="center">Item No.</td>
		<td align="justify">Quantity outstanding from previous bill</td>
		<td align="justify">Deduct quantity utilized in work measured since previous bill</td>
		<td align="justify">Add qty brought to site</td>
		<td align="justify">Outstanding including quantity brought to site since previous bill</td>
		<td align="justify">Full rate assessed by the Divisional Officer</td>
		<td align="center">Description of Item</td>
		<td align="center">Unit</td>
		<td align="justify">Reduced rate at which rate is made</td>
		<td align="justify">Up-to-date amount of advance</td>
		<td align="justify">Reference to Divisional Officers written orders authorizing the advance</td>
		<td align="justify">Reason for non - clearance of advance when outstanding more than three months</td>
	</tr>
<?php
$slno = 1; $total_ots_amt = 0;
$SecuredAdvQuery = "select a.*, b.*, c.per, c.decimal_placed from secured_advance a 
inner join secured_advance_dt b on (a.said = b.said) 
inner join schdule c on (b.subdivid = c.subdiv_id) 
where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.rbn = '$rbn' and b.rbn = '$rbn' and c.sheet_id = '$sheetid'";
$SecuredAdvSql = mysql_query($SecuredAdvQuery);
if($SecuredAdvSql == true){
	while($SList = mysql_fetch_object($SecuredAdvSql)){
	$desc 			= $SList->description;
	$snotes 		= $SList->shortnotes;
	$decimal 		= $SList->decimal_placed;
	$itemUnit 		= $SList->per;
	$total_ots_amt 	= $total_ots_amt + $SList->upto_dt_amt;
	if($snotes != ""){
		$description = $snotes;
	}else{
		$description = $desc;
	}
?>
	<tr>
		<td align="center" width="50px"><?php echo $slno; ?></td>
		<td align="center" width="50px"><?php echo $SList->itemno; ?></td>
		<td align="right" width="50px"><?php echo $SList->ots_qty_prev_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->utz_qty_this_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->add_qty_this_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->ots_qty_since_bill; ?></td>
		<td align="right" width="50px"><?php echo $SList->full_asses_rate; ?></td>
		<td align="left"><?php echo $description; ?></td>
		<td align="center"><?php echo $itemUnit; ?></td>
		<td align="right" width="50px"><?php echo $SList->red_rate; ?></td>
		<td align="right" width="50px"><?php echo number_format($SList->upto_dt_amt,2,".",","); ?></td>
		<td align="center" width="50px"><?php echo $SList->div_off_ref; ?></td>
		<td align="center" width="50px"><?php echo $SList->reason_non_clear; ?></td>
	</tr>
<?php
		$slno++;		
	}
	$total_ots_amt = round($total_ots_amt,2);
?>
	<tr class="labelbold">
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center" colspan="3">Total Amount</td>
		<td align="right"><?php echo number_format($total_ots_amt,2,".",","); ?></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
<?php
$net_amount = round(($total_ots_amt - $PrevSaAmount),2);
$split_amt = explode(".",$net_amount);
$rupees_part = $split_amt[0];
$paise_part = $split_amt[1];

$rupee_part_word = number_to_words($rupees_part);

if($paise_part != 0)
{
	$paise_part_word = " and Paise ".number_to_words($paise_part)."";
}
$amount_in_words = $rupee_part_word.$paise_part_word;
//echo $amount_in_words;
}
?>
</table>
<table width='90%'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='labelprint' >
	<tr>
		<td>Total amount outstanding as per this account  : </td>
		<td align="right"><b><?php echo number_format($total_ots_amt,2,".",","); ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Deduct-Amount outstanding as per entry (C) of Annexure to the previous bill  : <?php if(($PrevSaMBno != 0)&&($PrevSaPage != 0)){ echo "MB-".$PrevSaMBno."/P-".$PrevSaPage; }  ?></td>
		<td align="right"><b><?php echo number_format($PrevSaAmount,2,".",","); ?></b></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="labelbold" style="font-size:12px">Net Amount since previous bill (in words : Rupees <?php echo $amount_in_words; ?>) </td>
		<td align="right"><b><?php echo number_format($net_amount,2,".",","); ?></b></td>
		<td width="15%" align="right"><?php echo $carry_over_str; ?></td>
	</tr>
	<tr>
		<td colspan="3">
			Certified (1) that the plus quantities of materials shown in column 3 of the Account above have actually been brought by the Contractor to the site 
		of the work and the contractor had not previously received any advance on their security (2) that these materials are of an imperishable nature and 
		all are required by the Contractor for use on the work in connection with the items for which rates for finished work have been agreed upon and (3) 
		that a format agreement in Form 31 signed and executed by the Contractor in accordance with Paragraphs 10.2.24 (a) of the Central Public Works 
		Account Code in the Divisional Office.
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><span class='badge'>Page <?php echo $page; $page++; ?></span></td>
	</tr>
</table>
<!--<div style="width:100%;" align="center">
	<div class="col-md" align="center"> 
		&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Certified (1) that the plus quantities of materials shown in column 3 of the Account above have actually been brought by the Contractor to the site 
		of the work and the contractor had not previously received any advance on their security (2) that these materials are of an imperishable nature and 
		all are required by the Contractor for use on the work in connection with the items for which rates for finished work have been agreed upon and (3) 
		that a format agreement in Form 31 signed and executed by the Contractor in accordance with Paragraphs 10.2.24 (a) of the Central Public Works 
		Account Code in the Divisional Office.
	</div>
</div>-->

		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
				<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
			</div>
			<!--<div class="buttonsection" id="view_btn_section">
				<input type="button" class="backbutton" name="print" value=" Print " onclick="printBook();" />
			</div>-->
		</div>
		</form>
		<div class="smediv"></div>
      </blockquote>
    </div>
   </div>
</div>
<?php include "footer/footer.html"; ?>
</body>
</html>