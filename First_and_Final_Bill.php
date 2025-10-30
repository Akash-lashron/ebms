<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "spellnumber.php";
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
		$work_commence_date 	= 	$List->work_order_date;
		$sch_doc 				= 	$List->date_of_completion;
		$act_doc 				= 	$List->act_doc;
		$date_of_completion 	= 	$List->date_of_completion;
	}
	$selectmbook_detail 		= 	"select DISTINCT rbn, fromdate, todate, abstmbookno, is_finalbill FROM mbookgenerate WHERE sheetid = '$sheetid'";
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
    $select_pageno_query = "select a.*, b.*, c.* from measurementbook_temp a INNER JOIN schdule b INNER JOIN subdivision c WHERE a.sheetid = '$sheetid' and a.rbn = '$rbn' and a.subdivid = b.subdiv_id and a.subdivid=c.subdiv_id";
	$select_pageno_sql 	= mysql_query($select_pageno_query);
	//echo $select_pageno_query;exit;
	//echo $mbno;exit;
	if($select_pageno_sql == true){
		$count = mysql_num_rows($select_pageno_sql);
	}
$BFpage = 1;

?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "FirstandFinalBillGenerate.php";
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
        				<div class="title printbutton">First And Final Bill Form</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto" id="printSection">
                        <form name="form" method="post" action="FirstandFinalBillGenerate.php">
                            <div class="container" align="center">
								<br/>
								<style>
									@media print {
										#printSection{
											padding-top:2px;
											align-content: center;
										}
										@page {
										  size: A4 landscape;
										   margin: 10mm 10mm 10mm 10mm;
										   font-size:14px;
										}
										.printbutton{
											display:none;
										}
										/*table { page-break-inside:auto }
   										tr  { page-break-inside:avoid; page-break-after:auto }
										table, tr, td, th, tbody, thead, tfoot {
											page-break-inside: avoid !important;
										}*/
									} 
								</style>
								<table width="100%" class="table1" align="center">
									<tr class="label">
										<td colspan="16" align="center">First And Final Bill</td>
									</tr>
									<tr class="label">
										<td colspan="16" align="center">(Central P.W.A Code Paragraphs 10_2_10 & 10_2_11)</td>
									</tr>
									<tr class="label">
										<td colspan="16">
											<span style="float:left">Division ....................  C C No. : <?php echo $ccno; ?></span>
											<span style="float:right">Sub Division.............</span>
										</td>
									</tr>
									<tr class="label">
										<td colspan="16" align="center">
										(For Contractors & Suppliers To be used when a single payment is made for a job or contract i.e.
										only on its completion. A single form may be used for making payment to several contractors or suppliers 
										if they relate to the same work or to the same head of account in the case of suppliers and are billed for the same time.)										
										</td>
									</tr>
									<tr class="label">
										<td colspan="8" align="left">Name Of work (in the case of bills for work done) : <b><?php echo $work_name; ?></b></td>
										<td colspan="8" align="left"> Cash Book Voucher No : .............&nbsp; Date ............ </td>
									</tr>
									<tr class="label">
										<td colspan="16" align="center">&nbsp;</td>
									</tr>
									</table>	
								    <table width="100%" class="table1" align="center">
									  <tr class="label">
										<td align="center" valign="middle" rowspan="2">Name Of Contractor Or Suppliers & Reference To Agreement</td>
										<td align="center" valign="middle" rowspan="2">Items Or Work Or Supplies (Grouped Under Sub Heads And Sub Works Of Estimate)*</td>
										<td align="center" valign="middle" colspan="3" rowspan="2">Reference : To Recorded Measurements And Date</td>
										<td align="center" valign="middle" colspan="2">Date</td>
										<td align="center" valign="middle" rowspan="2">Quantity</td>
										<td align="center" valign="middle" rowspan="2">Rate</td>
										<td align="center" colspan="3" >Total Amount Payable To Contractor/Supplier</td>
										<td align="center" valign="middle" rowspan="2">Payee's Dated Signature In Token Of (1)Acceptance Of Bill And (2) Acknowledgement Of Payment</td>
										<td align="center" valign="middle" rowspan="2">Date Signature Of Witness</td>
										<td align="center" colspan="2">Dated Certificates Of Disbursement</td>
									 </tr>
									 <tr class="label">
										<td align="center" valign="middle">Written order to commence work</td>
										<td align="center" valign="middle">Actual Completion Of Work</td>
										<td align="center" valign="middle">In Figures</td>
										<td align="center" colspan="2" valign="middle">In Words</td>
										<td align="center" valign="middle">Mode Pf Payment Cash Of Cheque (No.and date)</td>
										<td align="center" valign="middle">Paid by me</td>
									</tr>
									<tr class="label">
									    <td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="center" valign="middle">Book No</td>
										<td align="center" valign="middle">Page No</td>
										<td align="center" valign="middle">Date</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left"colspan="2">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left"colspan="2">&nbsp;</td>
									</tr>
								<?php 
									$ThisBillNetAmt = 0;
									if($count > 0){ $tmp = 1; while($List = mysql_fetch_object($select_pageno_sql)){ 
									$mbqua    = $List->mbtotal;
									$mbrate   = $List->rate;
									$mbperc   = $List->pay_percent;
									if($tmp == 1){
								?>
 									<tr class="label">
									    <td align="left" rowspan="<?php echo $count; ?>" style="vertical-align:top"><?php echo $name_contractor; ?></td>
										<td align="center"><?php echo $List->subdiv_name; ?></td>
										<td align="center"><?php echo $List->abstmbookno; ?></td>
										<td align="center"><?php echo $List->abstmbpage ; ?></td>
										<td align="left">&nbsp;</td>
										<td align="left" rowspan="<?php echo $count; ?>" style="vertical-align:top;"><?php echo dt_display($work_commence_date) ; ?></td>
										<td align="left" rowspan="<?php echo $count; ?>" style="vertical-align:top;"><?php echo dt_display($date_of_completion) ; ?></td>
										<td align="right"><?php echo $List->mbtotal ; ?></td>
										<td align="right"><?php echo $List->rate ;?></td>
										<?php $totalAmt = round(($mbqua * $mbrate * $mbperc / 100),2); ?>
										<td align="right"><?php echo number_format($totalAmt,2,".",","); ?></td>
										<?php
											$ThisBillNetAmt = $ThisBillNetAmt + $totalAmt;
											$split_amt = explode(".",$totalAmt);
											$rupees_part = $split_amt[0];
											$paise_part = $split_amt[1];
											$rupee_part_word = number_to_words($rupees_part);
											if($paise_part != 0)
											{
												$paise_part_word = " and Paise ".number_to_words($paise_part)."";
											}
											$amount_in_words = $rupee_part_word.$paise_part_word;
										?>
										<td align="justify"colspan="2"><?php echo $amount_in_words ; ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left"colspan="2">&nbsp;</td>
									</tr>
									<?php }else{ ?>
									<tr class="label">
										<td align="center"><?php echo $List->subdiv_name; ?></td>
										<td align="center"><?php echo $List->abstmbookno; ?></td>
										<td align="center"><?php echo $List->abstmbpage ; ?></td>
										<td align="left">&nbsp;</td>
										<td align="right"><?php echo $List->mbtotal ; ?></td>
										<td align="right"><?php echo $List->rate ;?></td>
										<?php $totalAmt = round(($mbqua * $mbrate),2); ?>
										<td align="right"><?php echo number_format($totalAmt,2,".",","); ?></td>
										<?php
											$ThisBillNetAmt = $ThisBillNetAmt + $totalAmt;
											$split_amt = explode(".",$totalAmt);
											$rupees_part = $split_amt[0];
											$paise_part = $split_amt[1];
											$rupee_part_word = number_to_words($rupees_part);
											if($paise_part != 0)
											{
												$paise_part_word = " and Paise ".number_to_words($paise_part)."";
											}
											$amount_in_words = $rupee_part_word.$paise_part_word;
										?>
										<td align="justify"colspan="2"><?php echo $amount_in_words ; ?></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left"colspan="2">&nbsp;</td>
									</tr>
									<?php } $tmp++; } }?>	
									<tr>
										<td colspan="9" align="right"><b>Total Amount (Rs.) </b></td>
										<td>
										<b>
										<?php 
										$ThisBillNetAmt = round($ThisBillNetAmt,2); 
										echo number_format($ThisBillNetAmt,2,".",","); 
										$split_amt2 = explode(".",$ThisBillNetAmt);
										$rupees_part2 = $split_amt2[0];
										$paise_part2 = $split_amt2[1];
										$rupee_part_word2 = number_to_words($rupees_part2);
										if($paise_part2 != 0){
											$paise_part_word2 = " and Paise ".number_to_words($paise_part2)."";
										}
										$amount_in_words2 = $rupee_part_word2.$paise_part_word2;
										?>
										</b>
										</td>
										<td align="left" colspan="2"><b><?php echo $amount_in_words2 ; ?></b></td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left" colspan="2">&nbsp;</td>
									</tr>	
								</table>	
								<table width="100%" class="table1" align="center">
									<tr>
								       <td><br><br><br><br>
								         <span style="float:left">Date .......... <?php //echo $ccno; ?></span>
								         <span style="float:right"><b>Signature of officer preparing the bill</b></span><br><br>
										 <span style="float:left">pay Rs(...........................) in cash and Rs ..........................................................</span>
										 <span style="float:right"><b><br/><br/>Signature of officer authorizing payment</b></span><br><br>
										 ...................................................................by cheque<br><br>
										 <span style="float:left">Date ................................. <?php //echo $ccno; ?></span><br><br>
										 in case of payments to suppliers a red link entry should be made across the page above 
											&nbsp;
											the entries relating thereto , in one of the following forms, applicable to the case:-<br>
											(1) Stock<br>
											(2) Purchases For Stock<br>
											(3) purchase of the directissue to work.....................
											(4) purchase for the work...................................
											For issue to contractor................................................<br><br>
											not required in case of works done or supplies made under a piece work agreement .<br><br>
											in case of works the accounts of which are kept by sub heads the amounts relating 
											to all items of work failing under the same sub heads should be titaled in red ink.<br><br>
											Payment should be attested by some known person when the payees Acknowledgement is given be a mark, seal or thumb impression.<br><br>
											This signature is necessary only when the officer authorizing payment is not the officer who prepares the bill.<br><br>
										    (for use in Divisional Office)
								       </td>
								     </tr>
							</table>
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
<?php include "footer/footer.html"; ?>
<style>
	.table1 td{
		line-height: 18px;
		vertical-align:middle;
		font-size:11px;
		padding:3px;
	}
	/*table { page-break-inside:auto }
   	tr  { page-break-inside:avoid; page-break-after:auto }
	table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;*/
}
</style>
<script>
	$("#cmb_shortname").chosen();
</script>
</body>
</html>

