<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$count = 0; $SecuredAdvance = 0;
if($_POST["btn_view"] == " View "){
	$sheetid 	= $_POST['cmb_shortname'];
	$is_finalbill= $_POST['ch_finalbill'];
	if($is_finalbill == "Y"){
		$RabText = " & Final Bill";
	}else{
		$RabText = "";
	}
	$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$sqlquery 	= 	mysql_query($query);
	if($sqlquery == true){
		$List 					= 	mysql_fetch_object($sqlquery);
		$work_name 				= 	$List->work_name; 
		$short_name 			= 	$List->short_name;   
		$tech_sanction 			= 	$List->tech_sanction;  
		$name_contractor 		= 	$List->name_contractor; 
		$ccno 					= 	$List->computer_code_no;    
		$agree_no 				= 	$List->agree_no; 
		$overall_rebate_perc 	= 	$List->rebate_percent; 
		$work_order_no 			= 	$List->work_order_no; /*  if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
		$work_order_date 		= 	$List->work_order_date;
		$work_commence_date 	= 	$List->work_commence_date;
		$sch_doc 				= 	$List->date_of_completion;
		$act_doc 				= 	$List->act_doc;
	}
	$selectmbook_detail 		= 	"select DISTINCT fromdate, todate, rbn, abstmbookno, is_finalbill FROM mbookgenerate WHERE sheetid = '$sheetid'";
	$selectmbook_detail_sql 	= 	mysql_query($selectmbook_detail);
	if ($selectmbook_detail_sql == true){
		$Listmbdetail 			= 	mysql_fetch_object($selectmbook_detail_sql);
		$fromdate 				= 	$Listmbdetail->fromdate; 
		$todate 				= 	$Listmbdetail->todate; 
		$rbn 					= 	$Listmbdetail->rbn; 
		$abstmbno 				= 	$Listmbdetail->abstmbookno;
		$is_finalbill 			= 	$Listmbdetail->is_finalbill;
	}
	
	$select_sec_adv_query 	= "select sec_adv_amount, mbookno, page from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sec_adv_sql 	= mysql_query($select_sec_adv_query);
	if($select_sec_adv_sql == true){
		if(mysql_num_rows($select_sec_adv_sql)>0){
			$SAList = mysql_fetch_object($select_sec_adv_sql);
			$SecuredAdvance = $SAList->sec_adv_amount;
		}
	}
	
	$select_mbno_query 	= "select  GROUP_CONCAT(distinct mbno) as mbook from mymbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_mbno_sql 	= mysql_query($select_mbno_query);
	if($select_mbno_sql == true){
		if(mysql_num_rows($select_mbno_sql)>0){
			$MBList = mysql_fetch_object($select_mbno_sql);
			$mbook = $MBList->mbook;
		}
	}
}
$BFpage = 1;
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "BillFormGenerate.php";
		window.location.replace(url);
	}
	function PrintBook(){
	   var printContents 		= document.getElementById('printSection').innerHTML;
		var originalContents 	= document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.table1{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.label{
		font-size:13px;
		font-weight:normal;
	}
	.table1 td{
	padding:3px;
	}
	.labellarge{
		font-size:18px;
	}
	.labelmedium{
		font-size:13px;
	}
	@media print {
		#printSection{
			padding-top:2px;
			text-align:center;
		}
	} 
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
		<div class="content">
        				<div class="title printbutton">Bill Form</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto" id="printSection">
                        <form name="form" method="post" action="SecuredAdvancePrintView.php">
                            <div class="container" align="center">
								<br/>
								<style>
									@media print {
										#printSection{
											padding-top:2px;
											align-content: center;
										}
										@page {
										  size: A4 portrait;
										   margin: 10mm 10mm 10mm 10mm;
										   font-size:14px;
										}
										.printbutton{
											display:none;
										}
									} 
								</style>
								<!--<table width="875"  bgcolor="#E8E8E8" class="table1 printSection" align="center">
									<tr>
										<td class="label">Name of Work</td>
										<td><?php echo $work_name; ?></td>
									</tr>
									<tr>
										<td class="label">Work Oredr No.</td>
										<td><?php echo $work_order_no; ?></td>
									</tr>
									<tr>
										<td class="label">RAB No.</td>
										<td><?php echo $rbn; ?></td>
									</tr>
								</table>
								<br/>-->
								<table width="875" class="table1" align="center">
									<tr>
										<td class="label" colspan="8" align="center"> Bill Form for RAB - <?php echo $rbn; ?> <?php echo $RabText; ?></td>
									</tr>
									<tr class="label">
										<td colspan="8">
											<span style="float:left">CC NO : <?php echo $ccno; ?></span>
											<span style="float:right">C.P.W.A.26 (Revised)</span>
										</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="center">RUNNING ACCOUNT BILL</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="center">(Referred to in paragraph 10-2-10 and 10-2-12)</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="center">
										(Final payments must invariably be made on forms printed on yellow paper which should not be used for intermediate payments)
										</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="center">
										(For Contractors - This form provides for (1) Advance payments and (2) payments for Measured Works.  
										The form of Accounts secured advances, which has been printed separately should be attached where necessary)										
										</td>
									</tr>
										<tr class="label">
											<td colspan="4" align="left">Division :  FRFCF </td>
											<td colspan="4" align="left"> Sub-Division : CIVIL</td>
										</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="left">Cash Book Voucher No : .......................................... Dated ........................... </td>
									</tr>
									<tr class="label">
										<td align="left">Name of Contractor : </td>
										<td colspan="7" align="left"><?php echo $name_contractor; ?></td>
									</tr>
									<tr class="label">
										<td align="left">Name of Work : </td>
										<td colspan="7" align="left"><?php echo $work_name; ?></td>
									</tr>
									<tr class="label">
										<td align="left">Serial No. of this bill : </td>
										<td colspan="7" align="left">RAB - <?php echo $rbn; ?><?php echo $RabText; ?></td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">No. and date of this previous bill for this work : </td>
									</tr>
									<tr class="label">
										<td align="left">Reference to Agreement No : </td>
										<td colspan="7" align="left"><?php echo $agree_no; ?></td>
									</tr>
									<tr class="label">
										<td align="left">Date of written order to commence work : </td>
										<td colspan="7" align="left"><?php echo $work_order_no; ?> dated <?php echo $work_order_date; ?></td>
									</tr>
									<?php //if($is_finalbill == "Y"){ ?>
									<tr class="label">
										<td align="left">Date of actual completion of work : </td>
										<td colspan="7" align="left"><?php if($act_doc != "0000-00-00"){ echo dt_display($act_doc); } ?></td>
									</tr>
									<?php //} ?>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
								</table>
								<table width="875" class="table1" align="center">
									<tr class="label">
										<td align="center" colspan="8" class="labelmedium">I. ACCOUNT OF WORK EXECUTED</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle" rowspan="2">Item No</td>
										<td align="center" valign="middle" rowspan="2">Description of work  </td>
										<td align="center" valign="middle" rowspan="2">Unit</td>
										<td align="center" valign="middle" rowspan="2">Rate</td>
										<td align="center" valign="middle" rowspan="2">Quantity executed up to date as per measurement book</td>
										<td align="center" colspan="2">Payment on the basis of actual measurements</td>
										<td align="center" valign="middle" rowspan="2">Remarks</td>
									</tr>
									<tr class="label">
										<td align="left">Up-to-date</td>
										<td align="left">Since previous bill</td>
									</tr>
									<tr class="label">
										<td align="center">&nbsp;</td>
										<td align="center" width="400px;">1</td>
										<td align="center">2</td>
										<td align="center">3</td>
										<td align="center">4</td>
										<td align="center">5</td>
										<td align="center">6</td>
										<td align="center">7</td>
									</tr>
									
									
									
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<?php include("BillFormAbstract.php"); ?>
									<!--<tr class="label">
										<td align="left" colspan="8">
											
										</td>
									</tr>-->
								</table>	
								<table width="875" class="table1" align="center">	
									
									
									<tr class="label">
										<td align="left" colspan="5">Total value of work done to date (A) </td>
										<td align="right"><?php echo number_format($SlmDpmNetAmount, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Add/Deduct Service Tax </td>
										<td align="right"></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Total Value of work done to date </td>
										<td align="right"><?php echo number_format($SlmDpmNetAmount, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Deduct value of work shown on previous bill</td>
										<td align="right"><?php echo number_format($DpmNetAmount, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Net value of work since previous bill (B)</td>
										<td align="right"><?php echo number_format($SlmNetAmount, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Add/Deduct Secured Advance</td>
										<td align="right"><?php echo number_format($SecuredAdvance, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<?php $ThisBillNetAmt = round(($SlmNetAmount + $SecuredAdvance),2); ?>
									<tr class="label">
										<td align="left" colspan="5">Total value in this bill</td>
										<td align="right"><?php echo number_format($ThisBillNetAmt, 2, '.', ''); ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<?php
									$split_amt = explode(".",$ThisBillNetAmt);
									$rupees_part = $split_amt[0];
									$paise_part = $split_amt[1];
									$rupee_part_word = number_to_words($rupees_part);
									
									if($paise_part != 0)
									{
										$paise_part_word = " and Paise ".number_to_words($paise_part)."";
									}
									$amount_in_words = $rupee_part_word.$paise_part_word;
									?>
									<tr class="label">
										<td align="left" colspan="8">In words : Rupees <?php echo $amount_in_words; ?></td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											N.B. - When there are two or more entries in column 6 relating to each sub-head of estimate they 
											should in the cash of works the accounts which are kept by sub-heads, be totaled and the total 
											record in column 7 for posting the Works Abstract.
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$BFpage; $BFpage++; ?></p>
								<table width="875" class="table1" align="center">
									<tr class="label">
										<td align="center" colspan="8" class="labelmedium">
											II. CERTIFICATES AND SIGNATURES
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											&nbsp;&nbsp;1. The measurements on which are based the entries in column 1 to 6 of Account.  
											I, were made by  me  on different dates  and are recorded at different pages of 
											Measurement Book No: <?php echo $mbook; ?>								
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											*2. Certified that in addition to and quite apart from the quantities of work 
											actually executed as shown in column 7 of Account I, some work has actually 
											been done in connection with several items and the value of such work 
											(after deducting there from the proportionate amount of secured advances, 
											if any, ultimately recoverable on account of the quantities of materials used therein) 
											is in no case, less than the advance payments as per item 2 of the memorandom 
											if payments made or proposed to be made, for the convenience of the contractor 
											in anticipation of and subject to the results of, detailed measurements, 
											which wil be made as soon as possible.										
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="3" rowspan="2">
										<?php if($is_finalbill == "Y"){ ?>
											Final Bill is Accecpted  in full & final Settlement of all claims and demands.
										<?php } ?>
										</td>
										<td align="left" colspan="3">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="3">Dated signature of officer preparing the Bill</td>
										<td align="center" colspan="2">(Rank)</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="3">Dated Signature of Contractor</td>
										<td align="center" colspan="3">+ Dated signature of officer authorising payment</td>
										<td align="center" colspan="2">(Rank)</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle">*</td>
										<td align="left" colspan="7" valign="middle">This certificate must be signed by Sub-divisional or Divisional officer.</td>
									</tr>
									<tr class="label">
										<td align="center" valign="middle">+</td>
										<td align="left" colspan="7" valign="middle">This signature is necessary only when the officer who prepares the bill is not the officer who authorises the payment in such a case the two signatures are essential.</td>
									</tr>
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$BFpage; $BFpage++; ?></p>
								<table width="875" class="table1" align="center">	
									<tr class="label">
										<td align="center" colspan="8" class="labelmedium">
											III. MEMORANDUM OF PAYMENTS
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="center" colspan="2">Rs.</td>
									</tr>
									<tr class="label">
										<td align="center">1</td>
										<td align="left" colspan="5">Total value of work actually measured as per Acct. 1. Col 5, entry (A)</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center">2</td>
										<td align="left" colspan="5">Total "up-to-date" advances payments for work not yet measured, as per details given below:-</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" colspan="4">(a) Total as per previous bill .......................................               (B)</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" colspan="4">(b) Since previous bill ........................ as per page ............ of M.B. No. ...............</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center">3</td>
										<td align="left" colspan="5">Total "up-to-date" secured advances on security of materials as per annexure (form 26A)</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left"></td>
										<td align="left" colspan="5">Col. 8, Entry (C).............................................. </td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center">4</td>
										<td align="left" colspan="5">Total (Items 1+2+3) ........................................</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center">5</td>
										<td align="left" colspan="5">Deduct amount withheld - </td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" rowspan="4" width="135">
											<table style="border:none">
												<tr style="border:none">
													<td style="width:45px; border:1px solid #000000; height:120px;">Rs.</td>
													<td style="width:45px; border:1px solid #000000; height:120px;">P</td>
													<td style="width:45px; height:120px; border:none" valign="middle" align="center">(5)</td>
												</tr>
											</table>
										</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									
									<tr class="label">
										<td align="center" colspan="3">Figures for Works Abstract</td>
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">(a) From previous bill as per last Running Account Bill</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">(b) From this bill</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">6. balance. i.e. "up-to-date" payments (items 4,5)........................(K)*</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">7. Total amount of payments already made as per entry (K) of last Running Account BIll No. ------------ of ----------------- forwarded with accounts for ----------------- 20----.</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="2">Payments now to be made as detailed below:-</td>
										<td align="left" rowspan="7">
											<table style="border:none">
												<tr style="border:none">
													<td style="width:45px; border:1px solid #000000; height:120px;">Rs.</td>
													<td style="width:45px; border:1px solid #000000; height:120px;">P</td>
													<td style="width:45px; height:285px; border:none" valign="middle" align="center">(8)</td>
												</tr>
											</table>
										</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="2">
										(a) &emsp;By recovery of amount creditable to this work
										<br/> &emsp;&emsp;&emsp;................................................................
										<br/> &emsp;&emsp;&emsp;................................................................
										</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="2">Total 5(b) + 8(a) <span style="text-align:right; float:right">(H)</span></td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">
										(b) By recovery of amounts creditable to other works of heads of accounts ----- 
										<span style="text-align:right; float:right">(b)'</span>
										<br/>.......................................
										<br/>.......................................
										</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">(c)' By cheque .......................</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">Total 8(b) + &copy;<span style="text-align:right; float:right">(H)</span></td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="2">&nbsp;</td>
										<td align="left" width="30">&nbsp;</td>
										<td align="left" colspan="3">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>
									
									
									
									
									
									
									
									
									
									
									
									<tr class="label">
										<td align="center" colspan="8">
											Pay Rs. (...................)  (................................................) ( By Cheque )
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="right" colspan="6">Dated initials of Disbursing Officer</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="8">
											Received Rs. X (...................)  (................................................) As per above memorandum, on account of this work.
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<!--<td align="left">&nbsp;</td>-->
										<td align="center" colspan="4">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;(Amount in vernacular)</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" rowspan="4" width="100px">&nbsp;</td>
										<td align="left" width="100px">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="4">Dated the ...............................</td>
										<td align="center" colspan="4">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Signature of contractor</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="5">Witness &nbsp;&nbsp;&nbsp;...............................</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">Paid by me, vide cheque No ............................... Dated ....................</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="4">&nbsp;</td>
										<td align="center" colspan="4">Dated Initials of person actually making the payment.</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											This figure should be tested to see that it agrees with the total of items 7 and 8.
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											If the net amount to be paid is less than Rs. 10 and it cannot be included in a cheque 
											the payment should be made in cash, this entry being altered suitably and the alteration 
											attested by dated initials. 													
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											 Here specify the net amount payable, vide Item 8 ('c)
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											The payee's acknowledgement should be for the gross amount paid as per item 8 (I.e. a+b+c)
										</td>
									</tr>
									<tr class="label">
										<td align="left" colspan="8">
											X Payment should be attested by some known person when the payees acknowledgement is given by a mark, seal or thumb impression.
										</td>
									</tr>
									<!--<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>-->
								</table>
								<p style='page-break-after:always;'><?php echo "Page - ".$BFpage; $BFpage++; ?></p>
								<table width="875" class="table1" align="center">	
									<tr class="label">
										<td align="center" colspan="8" class="labelmedium">
											IV. Remarks
										</td>
									</tr>
									<tr class="label">
										<td align="center" colspan="8">
											(This space is reserved for any remarks which the Disbursing Officer or the 
											Divisional Officer may wish to record in respect of execution of the Work, 
											check of measurements or the state of contractor's amount)
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="8" class="labellarge">
											FOR USE IN DIVISIONAL OFFICE
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="8">
											Checked
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="4">Accounts Clerk</td>
										<td align="center" colspan="4">Divisional Accountant</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="8" class="labellarge">
											FOR USE IN ACCOUNTANT GENERAL'S OFFICE
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="3">Computed</td>
										<td align="center" colspan="3">Classification</td>
										<td align="center" colspan="2">Reviewed</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="8">
											Checked
										</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="3">Checked with Schedule    of rates / checked with rates as per agreement</td>
										<td align="center" colspan="3">Reviewed</td>
										<td align="center" colspan="2">Reviewed</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label">
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
									</tr>
									<tr class="label labelbold">
										<td align="center" colspan="3">Auditor</td>
										<td align="center" colspan="3">Superintendent</td>
										<td align="center" colspan="2">Gazetted Officer</td>
									</tr>
								</table>
								<p><?php echo "Page - ".$BFpage; $BFpage++; ?></p>
							</div>
       					 </form>
      				</blockquote>
					<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
						</div>
						<div class="buttonsection" id="view_btn_section">
							<input type="button" name="btn_print" value="Print" id="btn_print" class="backbutton" onClick="PrintBook();" />
						</div>
					</div>
    			</div>	
   			</div>
		</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
	$("#cmb_shortname").chosen();
</script>
</body>
</html>

