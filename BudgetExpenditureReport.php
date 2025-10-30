<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include 'commonBudget.php';
checkUser();
$report=0;
$msg = "";
$RowCount =0;
$staffid = $_SESSION['sid'];
$SheetCount = 0;
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if(isset($_POST['view']) == " View "){
	$FinaYears 		= $_POST['cmb_finyear'];
}else if($_GET['finyear'] != ''){
	$FinaYears 		= $_GET['finyear'];
}
$ViewWhereClause = '';
if($_GET['GlobId'] != ''){
	$GlobIdView 	= $_GET['GlobId'];
	if($GlobIdView != ''){
		$ViewWhereClause = " AND a.globid = ".$GlobIdView;
	}
}
if($FinaYears != ''){	
	$ExpFinaYears 	= explode("-",$FinaYears);
	$StartYr 		= $ExpFinaYears[0];
	$EndYr 			= $ExpFinaYears[1];
	$FinaFDate 		= $StartYr."-04-01";
	$FinaTDate 		= $EndYr."-03-31";
}
if(isset($_POST['btn_save']) == " SAVE "){
	$EditGlobId     = $_POST['txt_globid_modal'];
	$EditPinId     	= $_POST['txt_pinid_modal']; //echo $EditPinId;exit;
	$FinaYears 		= $_POST['txt_fin_yr_modal'];
	$ExpFinaYears 	= explode("-",$FinaYears);
	$StartYr 		= $ExpFinaYears[0];
	$EndYr 			= $ExpFinaYears[1];
	$FinaFDate 		= $StartYr."-04-01";
	$FinaTDate 		= $EndYr."-03-31";
	$EditRemarks    = $_POST['txt_remarks_modal'];
	$EditApr = $_POST['Q1CE1']; $EditMay = $_POST['Q1CE2']; $EditJun = $_POST['Q1CE3']; 
	$EditJul = $_POST['Q2CE1']; $EditAug = $_POST['Q2CE2']; $EditSep = $_POST['Q2CE3']; 
	$EditOct = $_POST['Q3CE1']; $EditNov = $_POST['Q3CE2']; $EditDec = $_POST['Q3CE3']; 
	$EditJan = $_POST['Q4CE1']; $EditFeb = $_POST['Q4CE2']; $EditMar = $_POST['Q4CE3'];
	$DeleteQuery = "delete from budget_expenditure where globid = '$EditGlobId' and fin_year = '$FinaYears' and pinid = '$EditPinId'";
	$DeleteSql = mysql_query($DeleteQuery);
	$SelectQuery = "Select * from work_status where globid = '$EditGlobId'";
	$SelectSql = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			$EditList 		= mysql_fetch_object($SelectSql);
			$EditSheetid 	= $EditList->sheetid;
			$EditCcno 		= $EditList->ccno;
			$EditPinid 		= $EditList->pinid;
		}
	}
	$InsertQuery = "insert into budget_expenditure set globid='$EditGlobId', pinid='$EditPinid', sheetid='$EditSheetid', cc_no='$EditCcno', exp_type = 'CE', april='$EditApr', may='$EditMay', june='$EditJun', july='$EditJul', aug='$EditAug',
		            sep='$EditSep', oct='$EditOct', nov='$EditNov', dece='$EditDec', jan='$EditJan', feb='$EditFeb', march='$EditMar', next_fin_yr_amt = '$nextfinyear', remarks = '$EditRemarks', fin_year='$FinaYears', active=1, createddate = NOW(), userid = '".$_SESSION['sid']."' ";
	$InsertSql = mysql_query($InsertQuery);
	if($InsertSql == true){
		$msg = "Budget Expenditure Updated Successfully";
		$success = 1;
	}else{
		$msg = "Not Updated. Please Try Again.";
	}
}
$PINNoArr = array();
$PinNoListQuery = "select a.pinid, a.pin_no from ".$dbName.".pin_entry a where a.active = 1";
$PinNoListSql = mysql_query($PinNoListQuery);
if($PinNoListSql == true){
	if(mysql_num_rows($PinNoListSql)>0){
		while($PinNoList = mysql_fetch_object($PinNoListSql)){
			$PINNoArr[$PinNoList->pinid] = $PinNoList->pin_no;
			//echo $RowspanArr[$RowSpanList->pinid];echo "<br/>";
		}
	}
}



//print_r($RowspanArr);exit;
//$select_sheet_query = "select a.*, b.pin_no from ".$dbName.".sheet a inner join ".$dbName.".pin_entry b on (a.pinid = b.pinid) where a.active = 1 and a.pinid != '' and (a.act_doc = '0000-00-00' OR (a.act_doc >= '$FinaFDate' and a.act_doc <= '$FinaTDate')) ORDER BY b.pinid ASC";
//$select_sheet_query = "select a.globid, a.sheetid, a.ccno, a.work_name as proposed_work, a.project_amount, a.project_sanct_dt, b.pin_no, c.*, a.pinid as pinid, a.globid, a.sheetid, a.ccno from ".$dbName.".work_status a inner join ".$dbName.".pin_entry b on (a.pinid = b.pinid) left join ".$dbName.".sheet c on (a.globid = c.globid) where a.active = 1 and a.pinid != '' and (c.date_of_completion > '2019-01-01' OR c.date_of_completion = '0000-00-00' OR c.date_of_completion IS NULL) and (a.act_comp_date = '0000-00-00' OR (a.act_comp_date >= '$FinaFDate' and a.act_comp_date <= '$FinaTDate')) ORDER BY b.pinid ASC";


/*$select_sheet_query = "SELECT a.globid, a.sheetid, a.ccno, a.work_name as proposed_work, a.project_amount, a.project_sanct_dt, b.pin_no, c.*, a.pinid as pinid, a.globid, a.sheetid, a.ccno FROM budget.work_status a 
INNER JOIN wmbook.pin_entry b ON (b.pinid = a.pinid) 
LEFT JOIN wmbook.sheet c ON (c.sheet_id = a.sheetid) 
LEFT JOIN wmbook.abstractbook d ON (a.sheetid = d.sheetid
AND 
d.pass_order_date = (SELECT MAX(e.pass_order_date) FROM wmbook.abstractbook e WHERE e.sheetid = a.sheetid 
AND 
(d.pass_order_date != '0000-00-00' OR d.pass_order_date IS NULL OR d.pass_order_date >= '$FinaFDate')))
AND 
d.absbookid = (SELECT MAX(e.absbookid) FROM wmbook.abstractbook e WHERE e.sheetid = a.sheetid)
WHERE b.pinid != '' 
AND  
(c.date_of_completion > '2019-01-01' OR c.date_of_completion = '0000-00-00' OR c.date_of_completion IS NULL OR d.pass_order_date >= '$FinaFDate') 
AND 
(a.act_comp_date = '0000-00-00' OR (a.act_comp_date >= '$FinaFDate' and a.act_comp_date <= '$FinaTDate') OR d.pass_order_date >= '$FinaFDate' OR d.pass_order_date IS 
NULL) ORDER BY b.pinid ASC";*/

/*$select_sheet_query = "SELECT a.globid, a.sheetid, a.ccno, a.work_name as proposed_work, a.project_amount, a.project_sanct_dt, b.pin_no, c.*, a.pinid as pinid, a.globid, a.sheetid, a.ccno, a.visible_to_budget, a.budget_type FROM ".$dbName.".work_status a 
INNER JOIN ".$dbName.".pin_entry b ON (b.pinid = a.pinid OR FIND_IN_SET(b.pinid,a.pinid)) 
LEFT JOIN ".$dbName.".sheet c ON (c.sheet_id = a.sheetid) 
LEFT JOIN ".$dbName.".abstractbook d ON (a.sheetid = d.sheetid
AND 
d.pass_order_date = (SELECT MAX(e.pass_order_date) FROM ".$dbName.".abstractbook e WHERE e.sheetid = a.sheetid 
AND 
(d.pass_order_date != '0000-00-00' OR d.pass_order_date IS NULL OR d.pass_order_date >= '$FinaFDate')))
AND 
d.absbookid = (SELECT MAX(e.absbookid) FROM ".$dbName.".abstractbook e WHERE e.sheetid = a.sheetid)
WHERE a.active = 1".$ViewWhereClause." AND (b.pinid != '' OR b.pinid = 0) AND a.visible_to_budget ='Y' AND a.budget_type ='CAP'
AND  
(c.date_of_completion > '2019-01-01' OR c.date_of_completion = '0000-00-00' OR c.date_of_completion IS NULL OR d.pass_order_date >= '$FinaFDate') 
AND 
(a.act_comp_date = '0000-00-00' OR (a.act_comp_date >= '$FinaFDate' and a.act_comp_date <= '$FinaTDate') OR d.pass_order_date >= '$FinaFDate' OR d.pass_order_date IS 
NULL) ORDER BY b.pinid ASC limit 4"; */


$select_sheet_query = "select a.*, d.* from sheet a LEFT JOIN abstractbook d ON (a.sheet_id = d.sheetid
AND d.pass_order_date = (SELECT MAX(e.pass_order_date) FROM abstractbook e WHERE e.sheetid = a.sheet_id 
AND (d.pass_order_date != '0000-00-00' OR d.pass_order_date IS NULL OR d.pass_order_date >= '$FinaFDate')))
AND d.absbookid = (SELECT MAX(e.absbookid) FROM abstractbook e WHERE e.sheetid = a.sheet_id)
where (a.date_of_completion > '$FinaFDate' OR a.date_of_completion = '0000-00-00' OR a.date_of_completion IS NULL OR d.pass_order_date >= '$FinaFDate')
AND (a.act_doc = '0000-00-00' OR (a.act_doc >= '$FinaFDate' and a.act_doc <= '$FinaTDate') OR d.pass_order_date >= '$FinaFDate' 
OR d.pass_order_date IS NULL) LIMIT 1";


$select_sheet_sql 	= mysql_query($select_sheet_query);
if($select_sheet_sql == true){
	if(mysql_num_rows($select_sheet_sql) > 0){
		$SheetCount = 1;
	}
}
$CurrYear = $StartYr;//date("Y");
$NextYear = $EndYr;//date('Y', strtotime('+1 year'));

//$MinDate = "2017-03-31";
$select_mindate_query = "select min(date) as mindate from mbookheader";
$select_mindate_Sql 	= mysql_query($select_mindate_query);
if($select_mindate_Sql == true){	
	if(mysql_num_rows($select_mindate_Sql) > 0){
		$MinDateList = mysql_fetch_object($select_mindate_Sql);
		$MinDate = $MinDateList->mindate;
	}
}
$PrevFY = ($CurrYear-1)."-".($NextYear-1); 
$CurrFY = $CurrYear."-".$NextYear; 
$NextFY = ($CurrYear+1)."-".($NextYear+1); 

$PrevFYDp = substr(($CurrYear-1),-2)."-".substr(($NextYear-1),-2); 
$CurrFYDp = substr($CurrYear,-2)."-".substr($NextYear,-2); 
$NextFYDp = substr(($CurrYear+1),-2)."-".substr(($NextYear+1),-2);
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="js/image_enlarge_style_js.js"></script>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
	table{
		margin-top:0px;
	}
	.table1 th{
		vertical-align:middle !important;
	}
	/*.table1 tr td {
		padding:5px;
		border:1px solid #E6E6E6;
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		background-color:#FFF;
		color:#0134BA;
		color: #1844C4;
		font-weight: 600;
		line-height: 1.5;
		
	}
	.table1 th {
		background-color:#E8E8E8;
		color:#0134BA;
		padding:5px;
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		border:1px solid #D4D4D4;
		vertical-align:middle;
		text-align:center;
		color: #1844C4;
		font-weight: 600 !important;
		line-height: 1.5;
	}*/
	.red {
	    background-color:#FF0000;
	}
	.green {
	    background-color:#00FFFF;
	}
	.Details{
		cursor:pointer;
	}
	.Details:hover{
		color:#DC0A53;
	}
	@media print {
		#printSection{
			padding-top:2px;
			text-align:center;
		}
	} 
	
	.wlable{
 	font-weight:600;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	color:#fff;
	font-size:11px;
 }
 .btable tr td{
	padding:5px;
	border:1px solid #D6D6D6;/*#E6E6E6;*/
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	background-color:#FFF;
	color:#0134BA;
	color: #1844C4;
	font-weight: 600;
	line-height: 1.5;
 }
 .btable th {
	background-color:#E6E6E6;
	color:#0134BA;
	padding:5px;
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	border:1px solid #D6D6D6;
	vertical-align:middle;
	text-align:center;
	/*font-family: 'Open Sans', sans-serif;*/
	color: #1844C4;
	font-weight: 600 !important;
	line-height: 1.5;
 }
 html{
	scrollbar-width: none;
	scrollbar-color:#C3C3C3 #fff;
 }
 html::-webkit-scrollbar-track{
	background-color: #fff;
 }
 html::-webkit-scrollbar{
	width: 0px;
	background-color: #fff;
 }
 html::-webkit-scrollbar-thumb{
	background-color:#383939;
 }
 .rotate {
  	transform: rotate(-30deg);
  /* Legacy vendor prefixes that you probably don't need... */
  /* Safari */
  	-webkit-transform: rotate(-30deg);
  /* Firefox */
  	-moz-transform: rotate(-30deg);
  /* IE */
  	-ms-transform: rotate(-30deg);
  /* Opera */
  	-o-transform: rotate(-30deg);
 }
 .Search{
 	background-image: url('/css/searchicon.png');
  	background-position: 10px 10px;
  	background-repeat: no-repeat;
  	width: 100%;
  	font-size: 13px;
  	border: 1px solid #0093FF;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	padding:2px 2px 2px 5px;
	border-radius:4px !important;
 }
 .newpro .inputGroup label{
 	padding: 3px 0px;
	width:90%;
	background-color:#FFFFFF;
 }
 .newpro .inputGroup{
 	background:none;
 }
 .newpro .inputGroup label::after {
    width: 7px;
    height: 10px;
    content: '';
    padding-left: 3px;
	right: 16px;
}
.tot-row1{
	background:#F9ECED !important;
}
.tot-row2{
	background:#EAFBF1 !important;
}


	</style>
    </head>
<script type="text/javascript">
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
    <link rel="stylesheet" href="Freeze/jquery.stickytable.css">

    <script src="Freeze/libs/jquery-3.2.0.min.js" type="text/javascript"></script>
    <script src="Freeze/jquery.stickytable.js" type="text/javascript"></script>
    <body dir="ltr" class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="BudgetExpenditureReportPrintCapital.php" method="post" enctype="multipart/form-data" name="form" target="_blank">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<!--<div class="title">Budget Expenditure Report</div>-->
				<div class="title" style="line-height:23px;padding:4px 0;">
					Budget Expenditure for the FY : <i><u><?php echo $FinaYears; ?></u></i>
					<span style="float:right; line-height:13px; padding-right:5px;"><input type="text" name="Search" id="Search" class="Search" placeholder="Work Name or CCNO" title="Type in a name"></span>
				</div>
				<div class="container_12">
					<div class="grid_12" align="center">
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
						<blockquote class="bq1" id="printSection">
							<div align="center" class="sticky-table sticky-ltr-cells">
								<style>
									@media print {
										#printSection{
											padding-top:2px;
											align-content: center;
										}
										@page {
										 /* size: A4 landscape;*/
										   /*margin: 10mm 10mm 10mm 10mm;*/
										   font-size:14px;
										}
										.printbutton{
											display:none;
										}
									} 
								</style>
								<table border="1" class="table1 btable table table-striped" id="fixTable">
									<thead>
										<tr class="sticky-header">
											<th rowspan="2" id="h1" class="sticky-cell">HOA</th>
											<th rowspan="2" id="h2" class="sticky-cell sticky-cell1">TS NO</th>
											<th rowspan="2" id="h3" class="sticky-cell sticky-cell3" nowrap="nowrap">NAME OF WORK</th>
											<th rowspan="2" id="h4" class="sticky-cell sticky-cell4" nowrap="nowrap">CC NO</th>
											<th rowspan="2" id="h5" class="sticky-cell sticky-cell5">WO VALUE /<br/> WO DATE / S.DOC</th>
											<th rowspan="2" id="h6" class="sticky-cell sticky-cell7">CONTRACTOR <br/>NAME/PERIOD</th>
											<th rowspan="2" id="h7" class="sticky-cell sticky-cell9"></th>
											<th rowspan="2">BE <br/> - <br/> <?php echo $PrevFYDp; ?> </th>
											<th colspan="4" id="Q1" align="center">Q1 (&#8377 in Lakhs)</th>
											<th colspan="4" id="Q2" align="center">Q2 (&#8377 in Lakhs)</th>
											<th colspan="4" id="Q3" align="center">Q3 (&#8377 in Lakhs)</th>
											<th colspan="4" id="Q4" align="center">Q4 (&#8377 in Lakhs)</th>
											<th rowspan="2">TOTAL </th>
											<th rowspan="2">BE <br/> FOR <br/><?php echo $NextFYDp ?></th>
											<th rowspan="2">REMARKS</th>
										</tr>
										<tr class="sticky-header">
											<th id="Q1C1" nowrap="nowrap">APR<br/><?php echo $CurrYear; ?></th>
											<th id="Q1C2" nowrap="nowrap">MAY<br/><?php echo $CurrYear; ?></th>
											<th id="Q1C3" nowrap="nowrap">JUN<br/><?php echo $CurrYear; ?></th>
											<th id="Q1C4" nowrap="nowrap">Q1<br/>TOT</th>
											<th id="Q2C1" nowrap="nowrap">JUL<br/><?php echo $CurrYear; ?></th>
											<th id="Q2C2" nowrap="nowrap">AUG<br/><?php echo $CurrYear; ?></th>
											<th id="Q2C3" nowrap="nowrap">SEP<br/><?php echo $CurrYear; ?></th>
											<th id="Q2C4" nowrap="nowrap">Q2<br/>TOT</th>
											<th id="Q3C1" nowrap="nowrap">OCT<br/><?php echo $CurrYear; ?></th>
											<th id="Q3C2" nowrap="nowrap">NOV<br/><?php echo $CurrYear; ?></th>
											<th id="Q3C3" nowrap="nowrap">DEC<br/><?php echo $CurrYear; ?></th>
											<th id="Q3C4" nowrap="nowrap">Q3<br/>TOT</th>
											<th id="Q4C1" nowrap="nowrap">JAN<br/><?php echo $NextYear; ?></th>
											<th id="Q4C2" nowrap="nowrap">FEB<br/><?php echo $NextYear; ?></th>
											<th id="Q4C3" nowrap="nowrap">MAR<br/><?php echo $NextYear; ?></th>
											<th id="Q4C4" nowrap="nowrap">Q4<br/>TOT</th>
										</tr>
									</thead>
									<tbody>
		    			  <?php $Previd = ""; $TotalWorkOrderCost = 0; $TotalQ1Amt = 0; $TotalQ2Amt = 0; $TotalQ3Amt = 0; $TotalAmtAE = 0; $PrevPinIdArr = array(); $TitleGlobIDArr = array();
								$Q1TOTAL = 0; $Q2TOTAL = 0; $Q3TOTAL = 0; $Q4TOTAL = 0; 
								
								$CEAprTotal = 0; $CEMayTotal = 0; $CEJunTotal = 0; $CEJulTotal = 0;$CEAugTotal = 0; $CESepTotal = 0;
								$CEOctTotal = 0; $CENovTotal = 0; $CEDecTotal = 0; $CEJanTotal = 0;$CEFebTotal = 0; $CEMarTotal = 0;
								
								$AEAprTotal = 0; $AEMayTotal = 0; $AEJunTotal = 0; $AEJulTotal = 0;$AEAugTotal = 0; $AESepTotal = 0;
								$AEOctTotal = 0; $AENovTotal = 0; $AEDecTotal = 0; $AEJanTotal = 0;$AEFebTotal = 0; $AEMarTotal = 0;
								
								$CEQ1Total = 0; $CEQ2Total = 0; $CEQ3Total = 0; $CEQ4Total = 0;
								$AEQ1Total = 0; $AEQ2Total = 0; $AEQ3Total = 0; $AEQ4Total = 0;
								
								$CEOverAllTotalAmt = 0;
								$AEOverAllTotalAmt = 0;
								$NxtFinYrTotalAmt_Total = 0;  $PrevFinYrTotalAmt_Total = 0;
								
								if($SheetCount == 1){ while($SheetList = mysql_fetch_object($select_sheet_sql)){
								$GlobId 	= $SheetList->globid;
								$sheetid 	= $SheetList->sheet_id;
								$CurrId 	= $SheetList->pinid;
								$PinId 		= $SheetList->pinid;
								$cc_no    	= $SheetList->computer_code_no;
								$Rebate     = $SheetList->rebate_percent;
								$PinIdArr 	= explode(",",$PinId);
								
								$ExpArr = array(); $BalanceArr = array(); $ExpIndRABArr = array(); $ExpIndAMTArr = array();
								$Q1M1 = array(); $Q1M2 = array(); $Q1M3 = array();  
								$Q2M1 = array(); $Q2M2 = array(); $Q2M3 = array();  
								$Q3M1 = array(); $Q3M2 = array(); $Q3M3 = array();
								$Q4M1 = array(); $Q4M2 = array(); $Q4M3 = array();
								
								$SelectAbstBookQuery = "select * from abstractbook where sheetid = '$sheetid' and pass_order_date >= '$FinaFDate' and pass_order_date <= '$FinaTDate'";
								$SelectAbstBookSql 	= mysql_query($SelectAbstBookQuery);
								if($SelectAbstBookSql == true){
									if(mysql_num_rows($SelectAbstBookSql)>0){
										while($AList = mysql_fetch_object($SelectAbstBookSql)){
											$todate 	= $AList->todate;//pass_order_date;
											if($AList->pass_order_date == "0000-00-00"){
												$todate 	= $AList->todate;
											}else{
												$todate 	= $AList->pass_order_date;
											}
											$rbn 		= $AList->rbn;
											$amountA 	= $AList->slm_total_amount;// * $MList->rate * $MList->pay_percent / 100;
											$amountB 	= $AList->slm_total_amount_esc;
											$SAAmount 	= 0;
											$SelectSecuredAdvAmtQuery = "select sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
											$SelectSecuredAdvAmtSql = mysql_query($SelectSecuredAdvAmtQuery);
											if($SelectSecuredAdvAmtSql == true){
												if(mysql_num_rows($SelectSecuredAdvAmtSql)>0){
													$SAList = mysql_fetch_object($SelectSecuredAdvAmtSql);
													$SAAmount = $SAList->sec_adv_amount;
												}
											}
											
											$amount 	= round(($amountA + $amountB + $SAAmount),2);
											$Year 		= date('Y', strtotime($todate));
											$Month 		= date('m', strtotime($todate));
											$Day 		= date('d', strtotime($todate));
											if($Month != ''){
												if($Month == 4){ array_push($Q1M1,$amount); }  if($Month == 5){ array_push($Q1M2,$amount); }  if($Month == 6){ array_push($Q1M3,$amount); } 
												if($Month == 7){ array_push($Q2M1,$amount); }  if($Month == 8){ array_push($Q2M2,$amount); }  if($Month == 9){ array_push($Q2M3,$amount); }
												if($Month == 10){ array_push($Q3M1,$amount); } if($Month == 11){ array_push($Q3M2,$amount); } if($Month == 12){ array_push($Q3M3,$amount); } 
												if($Month == 1){ array_push($Q4M1,$amount); }  if($Month == 2){ array_push($Q4M2,$amount); }  if($Month == 3){ array_push($Q4M3,$amount); } 
											}else{
												 array_push($ExpArr,$amount);
												$ExpIndAMTArr[$rbn] = $ExpIndAMTArr[$rbn] + $amount;
												if(in_array($rbn, $ExpIndRABArr)){
													// Already Exists
												}else{
													array_push($ExpIndRABArr,$rbn);
												}
											}
										}
									}
								}
								
								
								
								$ExpAmt 	= round(array_sum($ExpArr),2);
								$Balance1 	= round(($SheetList->work_order_cost - $ExpAmt),2);
								$Q1M1Amt 	= round((array_sum($Q1M1)/100000),2); $Q1M2Amt = round((array_sum($Q1M2)/100000),2); $Q1M3Amt = round((array_sum($Q1M3)/100000),2); 
								$Q2M1Amt 	= round((array_sum($Q2M1)/100000),2); $Q2M2Amt = round((array_sum($Q2M2)/100000),2); $Q2M3Amt = round((array_sum($Q2M3)/100000),2);
								$Q3M1Amt 	= round((array_sum($Q3M1)/100000),2); $Q3M2Amt = round((array_sum($Q3M2)/100000),2); $Q3M3Amt = round((array_sum($Q3M3)/100000),2);
								$Q4M1Amt 	= round((array_sum($Q4M1)/100000),2); $Q4M2Amt = round((array_sum($Q4M2)/100000),2); $Q4M3Amt = round((array_sum($Q4M3)/100000),2);
								
								$Q1TOTAL 	= round(($Q1M1Amt+$Q1M2Amt+$Q1M3Amt),2);
								$Q2TOTAL 	= round(($Q2M1Amt+$Q2M2Amt+$Q2M3Amt),2);
								$Q3TOTAL 	= round(($Q3M1Amt+$Q3M2Amt+$Q3M3Amt),2);
								$Q4TOTAL 	= round(($Q4M1Amt+$Q4M2Amt+$Q4M3Amt),2);
								
								$BalanceQ1 	= round(($Balance1-$Q1M1Amt-$Q1M2Amt-$Q1M3Amt),2);
								$BalanceQ2 	= round(($BalanceQ1-$Q2M1Amt-$Q2M2Amt-$Q2M3Amt),2);
								$BalanceQ3 	= round(($BalanceQ2-$Q3M1Amt-$Q3M2Amt-$Q3M3Amt),2);
								$BalanceQ4 	= round(($BalanceQ3-$Q4M1Amt-$Q4M2Amt-$Q4M3Amt),2);
								$TotalAmtAE = round(($Q1M1Amt+$Q1M2Amt+$Q1M3Amt+$Q2M1Amt+$Q2M2Amt+$Q2M3Amt+$Q3M1Amt+$Q3M2Amt+$Q3M3Amt+$Q4M1Amt+$Q4M2Amt+$Q4M3Amt),2);
								
							?>
							<?php 
								$April = 0; $May = 0; $June = 0; $July = 0; $Aug = 0; $Sep = 0; 
								$Oct = 0; $Nov = 0; $Decs = 0; $Jan = 0; $Feb = 0; $March = 0; $Budid = ""; $NxtFinYrAmt = 0; $TotalAmtCE = 0; $Remarks = "";
							  	$Q1TOTALCE = 0; $Q2TOTALCE = 0; $Q3TOTALCE = 0; $Q4TOTALCE = 0;
								
								$SelectBUQuery 	= "select * from budget_expenditure where globid = '$GlobId' and fin_year='$FinaYears' and exp_type = 'CE'";
								$SelectBUSql 	= mysql_query($SelectBUQuery);
								if($SelectBUSql == true){
									if(mysql_num_rows($SelectBUSql)>0){
										while($BUList = mysql_fetch_object($SelectBUSql)){
											$April	= $BUList->april; 
											$May    = $BUList->may;
											$June   = $BUList->june;
											$July   = $BUList->july;
											$Aug    = $BUList->aug;
											$Sep    = $BUList->sep;
											$Oct    = $BUList->oct;
											$Nov    = $BUList->nov;
											$Decs   = $BUList->dece;
											$Jan    = $BUList->jan;
											$Feb    = $BUList->feb;
											$March  = $BUList->march;
											$Budid  = $BUList->budid;
											$NxtFinYrAmt  	= $BUList->next_fin_yr_amt;
											$Remarks  		= $BUList->remarks;
											$TotalAmtCE     = $April+$May+$June+$July+$Aug+$Sep+$Oct+$Nov+$Decs+$Jan+$Feb+$March;
											$Q1TOTALCE  	= $April+$May+$June;
											$Q2TOTALCE  	= $July+$Aug+$Sep;
											$Q3TOTALCE  	= $Oct+$Nov+$Decs;
											$Q4TOTALCE  	= $Jan+$Feb+$March;
											
										    
											
										}
									}
								}
								
								$TitleRow = 0;
								$PrevPinArrCnt 	= count($PrevPinIdArr);
								$PinArrCnt 		= count($PinIdArr);
								if($Previd != ''){
									if(($PrevPinArrCnt == 1)&&($PinArrCnt == 1)){
										if($CurrId != $Previd){
											$TitleRow = 1;
										}else{
											$TitleRow = 0;
										}
										$SS =1;
									}else if(($PrevPinArrCnt == 1)&&($PinArrCnt > 1)){
										if(in_array($Previd, $PinIdArr)){
											$TitleRow = 0;
										}else{
											$TitleRow = 1;
										}
										$SS =2;
									}else if(($PrevPinArrCnt > 1)&&($PinArrCnt == 1)){
										if(in_array($CurrId, $PrevPinIdArr)){
											$TitleRow = 0;
										}else{
											$TitleRow = 1;
										}
										$SS =3;
									}else if(($PrevPinArrCnt != 1)&&($PinArrCnt != 1)&&($PrevPinArrCnt == $PinArrCnt)&&($CurrId == $Previd)){
										$TitleRow = 1;
										$SS =4;
									}
								}
								if(in_array($GlobId, $TitleGlobIDArr)){
									$TitleRow = 1;
								}
								
								//if(($CurrId != $Previd) || (in_array($rbn, $ExpIndRABArr))){ $slno++; 
								if($TitleRow == 1){ $slno++;
									if($Previd != ''){
							?>
									<tr class="PIN-TOTAL">
										<td valign="middle" class="sticky-cell" style="background:#EFF0F1 !important" align="right" colspan="3" rowspan="2">TOTAL</td>
										<td valign="middle" class="sticky-cell sticky-cell3" style="background:#EFF0F1 !important" rowspan="2">&nbsp;</td>
										<td valign="middle" class="sticky-cell sticky-cell4" style="background:#EFF0F1 !important" rowspan="2" align="right"><?php echo IndianMoneyFormat($TotalWorkOrderCost); ?></td>
										
										<td valign="middle" class="sticky-cell sticky-cell5 tot-row1" colspan="2" align="right" style="color:#E51863">TOTAL ( C.E. )</td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right">&nbsp;</td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEAprTotal != 0){ echo IndianMoneyFormat($CEAprTotal); } $CEAprTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEMayTotal != 0){ echo IndianMoneyFormat($CEMayTotal); } $CEMayTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJunTotal != 0){ echo IndianMoneyFormat($CEJunTotal); } $CEJunTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ1Total != 0){ echo IndianMoneyFormat($CEQ1Total);} $CEQ1Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJulTotal != 0){ echo IndianMoneyFormat($CEJulTotal); } $CEJulTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEAugTotal != 0){ echo IndianMoneyFormat($CEAugTotal); } $CEAugTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CESepTotal != 0){ echo IndianMoneyFormat($CESepTotal); } $CESepTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ2Total != 0){ echo IndianMoneyFormat($CEQ2Total); } $CEQ2Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEOctTotal != 0){ echo IndianMoneyFormat($CEOctTotal); } $CEOctTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CENovTotal != 0){ echo IndianMoneyFormat($CENovTotal); } $CENovTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEDecTotal != 0){ echo IndianMoneyFormat($CEDecTotal); } $CEDecTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ3Total != 0){ echo IndianMoneyFormat($CEQ3Total); } $CEQ3Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJanTotal != 0){ echo IndianMoneyFormat($CEJanTotal); } $CEJanTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEFebTotal != 0){ echo IndianMoneyFormat($CEFebTotal); } $CEFebTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEMarTotal != 0){ echo IndianMoneyFormat($CEMarTotal); } $CEMarTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ4Total != 0){ echo IndianMoneyFormat($CEQ4Total); } $CEQ4Total = 0; ?></td>
										
										
										<td valign="middle" style="background:#EFF0F1 !important" align="right"><?php if($CEOverAllTotalAmt != 0){ echo IndianMoneyFormat($CEOverAllTotalAmt);} $CEOverAllTotalAmt = 0; ?></td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right" rowspan="2"><?php if($NxtFinYrTotalAmt_Total != 0){ echo IndianMoneyFormat($NxtFinYrTotalAmt_Total); } $NxtFinYrTotalAmt_Total = 0; ?>
										<td valign="middle" style="background:#EFF0F1 !important" rowspan="2">&nbsp;</td>
									</tr>
									
									<tr class="PIN-TOTAL">
										<td valign="middle" class="sticky-cell sticky-cell5 tot-row2" colspan="2" align="right" style="color:#017B0A">TOTAL ( A.E. )</td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right">&nbsp;</td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEAprTotal != 0){ echo IndianMoneyFormat($AEAprTotal); } $AEAprTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEMayTotal != 0){ echo IndianMoneyFormat($AEMayTotal); } $AEMayTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJunTotal != 0){ echo IndianMoneyFormat($AEJunTotal); } $AEJunTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ1Total != 0){ echo IndianMoneyFormat($AEQ1Total); } $AEQ1Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJulTotal != 0){ echo IndianMoneyFormat($AEJulTotal); } $AEJulTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEAugTotal != 0){ echo IndianMoneyFormat($AEAugTotal); } $AEAugTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AESepTotal != 0){ echo IndianMoneyFormat($AESepTotal); } $AESepTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ2Total != 0){ echo IndianMoneyFormat($AEQ2Total); } $AEQ2Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEOctTotal != 0){ echo IndianMoneyFormat($AEOctTotal); } $AEOctTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AENovTotal != 0){ echo IndianMoneyFormat($AENovTotal); } $AENovTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEDecTotal != 0){ echo IndianMoneyFormat($AEDecTotal); } $AEDecTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ3Total != 0){ echo IndianMoneyFormat($AEQ3Total); } $AEQ3Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJanTotal != 0){ echo IndianMoneyFormat($AEJanTotal); } $AEJanTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEFebTotal != 0){ echo IndianMoneyFormat($AEFebTotal); } $AEFebTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEMarTotal != 0){ echo IndianMoneyFormat($AEMarTotal); } $AEMarTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ4Total != 0){ echo IndianMoneyFormat($AEQ4Total); }else{ echo "&nbsp;"; } $AEQ4Total = 0; ?></td>
										
										
										<td valign="middle" style="background:#EFF0F1 !important" align="right"><?php if($AEOverAllTotalAmt != 0){ echo IndianMoneyFormat($AEOverAllTotalAmt); } $AEOverAllTotalAmt = 0; ?></td>
									</tr>
							<?php
									$TotalWorkOrderCost = 0;
									}
								}
								$ExpTSNO 	= explode("/",$SheetList->tech_sanction);
								$TSNO 		= implode("/\n",$ExpTSNO);
								$ExpWONO 	= explode("/",$SheetList->work_order_no);
								$WONO 		= implode("/\n",$ExpWONO);
								if(in_array($GlobId, $TitleGlobIDArr)){
									
								}else{
									array_push($TitleGlobIDArr,$GlobId);
								}
								$PINNoList = '';
								if($PinArrCnt > 1){
									foreach($PinIdArr as $PinIdValue){
										$PINNoList .= $PINNoArr[$PinIdValue].",";
									}
									$PINNoList = rtrim($PINNoList,",");
									$PinNoDisplay = $PINNoList;
								}else{
									$PinNoDisplay = $SheetList->pin_no;
								}
								
								
							?>
									<tr id="C<?php echo $GlobId;?>">
										<td valign="middle" class="sticky-cell" align="center" rowspan="2"><?php echo $PinNoDisplay; ?></td>
										
										<td valign="middle" class="sticky-cell sticky-cell1" rowspan="2"><?php echo $TSNO; ?></td>
										<!--<td valign="middle" class="sticky-cell sticky-cell2" rowspan="2"><?php echo $WONO; ?></td>-->
										<td valign="middle" class="sticky-cell sticky-cell2" rowspan="2" style="text-align: justify; text-justify: inter-word;" data-pinid="<?php echo $PinId;?>" data-id="<?php echo $GlobId;?>">
										<?php if($SheetList->work_name != ""){ $WorkName = $SheetList->work_name; }else{ $WorkName = $SheetList->proposed_work; } ?>
										<span class="" data-toggle="tooltip" title="Click !" id="<?php echo $GlobId;?>" data-id ='<?php echo $GlobId;?>' data-pinid ='<?php echo $PinId;?>' data-work_name ='<?php echo $WorkName;?>' data-cc_no='<?php echo $SheetList->computer_code_no; ?>' data-work_value='<?php if($SheetList->work_order_cost != ""){ echo IND_money_format($SheetList->work_order_cost); }else{ echo IND_money_format($SheetList->project_amount); } ?>'
										data-work_order_date ='<?php if($SheetList->work_order_date != '' && $SheetList->work_order_date != '0000-00-00'){ echo dt_display($SheetList->work_order_date); } ?> '
										data-work_duration ='<?php if($SheetList->work_duration > 0){ echo $SheetList->work_duration; ?> Month<?php if($SheetList->work_duration > 1){ echo "s"; } } ?>' 
										data-cont_name ='<?php if( $SheetList->name_contractor != ""){ echo $SheetList->name_contractor; }else{ echo ""; } ?>'
										data-wo_no ='<?php echo $WONO; ?>'
										data-work_sdc ='<?php if($SheetList->date_of_completion != '' && $SheetList->date_of_completion != '0000-00-00'){ echo dt_display($SheetList->date_of_completion); } ?>' data-remarks="<?php echo $Remarks; ?>">
										<i style="font-size:14px;"></i><?php echo $WorkName; ?></span>
										</td>
									
										<td valign="middle" class="sticky-cell sticky-cell3" rowspan="2" align="center"><?php echo $SheetList->computer_code_no; ?></td>
										<td valign="middle" class="sticky-cell sticky-cell4" rowspan="2"  align="center" style="padding:0px;">
											<!--<table>-->
										<?php
										 if($SheetList->work_order_cost != ""){ 
										 	//echo '<span class="rspan-pink">'.IND_money_format($SheetList->work_order_cost).'</span>'; 
											//echo '<tr><td style="color:#e51863" align="right">'.IND_money_format($SheetList->work_order_cost).'</td></tr>'; 
											echo '<div style="color:#e51863; margin-bottom:8px; padding-right:2px;" align="right">'.IND_money_format($SheetList->work_order_cost).'</div>'; 
										 }else{ 
										 	//echo '<span class="rspan-pink">'.IND_money_format($SheetList->project_amount).'</span>';
											//echo '<tr><td style="color:#e51863" align="right">'.IND_money_format($SheetList->project_amount).'</td></tr>'; 
											echo '<div style="color:#e51863; margin-bottom:8px; padding-right:2px;" align="right">'.IND_money_format($SheetList->project_amount).'<br/></div>'; 
										 } 
										 if($SheetList->work_order_date != '' && $SheetList->work_order_date != '0000-00-00'){ 
										 	echo '<div style="margin-bottom:10px; padding-right:2px;" align="right"><span class="rspan-pink" style="color:505251">'.dt_display($SheetList->work_order_date).'</span></div>';
										 } 
										 if($SheetList->date_of_completion != '' && $SheetList->date_of_completion != '0000-00-00'){ 
										 	echo '<div style="margin-bottom:8px; padding-right:2px;" align="right"><span class="rspan-pink" style="color:505251">'.dt_display($SheetList->date_of_completion).'</span></div>'; 
										 } 
										 ?>
										 	<!--</table>-->
									    </td>
										<!--<td valign="middle" class="sticky-cell sticky-cell6" rowspan="2" align="right"><?php if($SheetList->work_order_date != '' && $SheetList->work_order_date != '0000-00-00'){ echo dt_display($SheetList->work_order_date); } ?></td>-->
										<td valign="middle" class="sticky-cell sticky-cell5" rowspan="2" align="left"><?php echo $SheetList->name_contractor; ?><br/><br/><?php if($SheetList->work_duration > 0) { ?><span class="rspan-pink" style="color:505251">Period - <?php  echo $SheetList->work_duration; ?> Month<?php if($SheetList->work_duration > 1){ echo "s"; } } ?></span></td>
										<!--<td valign="middle" class="sticky-cell sticky-cell8" rowspan="2" align="right"><?php if($SheetList->date_of_completion != '' && $SheetList->date_of_completion != '0000-00-00'){ echo dt_display($SheetList->date_of_completion); } ?></td>-->
										<td valign="middle" class="sticky-cell sticky-cell6" align="center" style="color:#E51863">C.E.</td>
										
										<td valign="middle" align="right">
										<?php 
										$PrevFinYrTotalAmt = GetNextFinYearWorkTotalCommitted($GlobId,$PinId,$PrevFY,$dbConn2,$dbName);
										//if($PrevFinYrTotalAmt != 0){
											echo "<a title='Click here to view Previous FY' >".IndianMoneyFormat($PrevFinYrTotalAmt)."</a>";
										//}
										?>
										</td>
										<td valign="middle" align="right"><?php if($April != 0){ echo "<span class='rspan-pink' id='ce_row1".$GlobId."'>".IndianMoneyFormat($April)."</span>"; } $CEAprTotal = $CEAprTotal + $April; ?></td>
										<td valign="middle" align="right"><?php if($May != 0){ echo "<span class='rspan-pink' id='ce_row2".$GlobId."'>".IndianMoneyFormat($May)."</span>"; }  $CEMayTotal = $CEMayTotal + $May;  ?></td>
										<td valign="middle" align="right"><?php if($June != 0){ echo "<span class='rspan-pink' id='ce_row3".$GlobId."'>".IndianMoneyFormat($June)."</span>"; }  $CEJunTotal = $CEJunTotal + $June; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q1TOTALCE != 0){ echo IndianMoneyFormat($Q1TOTALCE); } $CEQ1Total = $CEQ1Total + $Q1TOTALCE; ?></td>
										
										<td valign="middle" align="right"><?php if($July != 0){ echo "<span class='rspan-pink' id='ce_row4".$GlobId."'>".IndianMoneyFormat($July)."</span>"; }  $CEJulTotal = $CEJulTotal + $July; ?></td>
										<td valign="middle" align="right"><?php if($Aug != 0){ echo "<span class='rspan-pink' id='ce_row5".$GlobId."'>".IndianMoneyFormat($Aug)."</span>"; }  $CEAugTotal = $CEAugTotal + $Aug; ?></td>
										<td valign="middle" align="right"><?php if($Sep != 0){ echo "<span class='rspan-pink' id='ce_row6".$GlobId."'>".IndianMoneyFormat($Sep)."</span>"; }  $CESepTotal = $CESepTotal + $Sep; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q2TOTALCE != 0){ echo IndianMoneyFormat($Q2TOTALCE); } $CEQ2Total = $CEQ2Total + $Q2TOTALCE; ?></td>
										
										<td valign="middle" align="right"><?php if($Oct != 0){ echo "<span class='rspan-pink' id='ce_row7".$GlobId."'>".IndianMoneyFormat($Oct)."</span>"; } $CEOctTotal = $CEOctTotal + $Oct; ?></td>
										<td valign="middle" align="right"><?php if($Nov != 0){ echo "<span class='rspan-pink' id='ce_row8".$GlobId."'>".IndianMoneyFormat($Nov)."</span>"; } $CENovTotal = $CENovTotal + $Nov; ?></td>
										<td valign="middle" align="right"><?php if($Decs != 0){ echo "<span class='rspan-pink' id='ce_row9".$GlobId."'>".IndianMoneyFormat($Decs)."</span>"; } $CEDecTotal = $CEDecTotal + $Decs; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q3TOTALCE != 0){ echo IndianMoneyFormat($Q3TOTALCE); } $CEQ3Total = $CEQ3Total + $Q3TOTALCE; ?></td>
										
										<td valign="middle" align="right"><?php if($Jan != 0){ echo "<span class='rspan-pink' id='ce_row10".$GlobId."'>".IndianMoneyFormat($Jan)."</span>"; } $CEJanTotal = $CEJanTotal + $Jan; ?></td>
										<td valign="middle" align="right"><?php if($Feb != 0){ echo "<span class='rspan-pink' id='ce_row11".$GlobId."'>".IndianMoneyFormat($Feb)."</span>"; } $CEFebTotal = $CEFebTotal + $Feb; ?></td>
										<td valign="middle" align="right"><?php if($March != 0){ echo "<span class='rspan-pink' id='ce_row12".$GlobId."'>".IndianMoneyFormat($March)."</span>"; } $CEMarTotal = $CEMarTotal + $March; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q4TOTALCE != 0){ echo IndianMoneyFormat($Q4TOTALCE); } $CEQ4Total = $CEQ4Total + $Q4TOTALCE; ?></td>
										<td valign="middle" align="right"><?php if($TotalAmtCE != 0){ echo IndianMoneyFormat($TotalAmtCE); } $CEOverAllTotalAmt = $CEOverAllTotalAmt + $TotalAmtCE; ?></td>
										<td valign="middle" rowspan="2" align="right">
										<?php 
										$NxtFinYrTotalAmt = GetNextFinYearWorkTotalCommitted($GlobId,$PinId,$NextFY,$dbConn2,$dbName);
										//if($NxtFinYrTotalAmt != 0){
											echo "<a title='Click here to view Next FY' >".IndianMoneyFormat($NxtFinYrTotalAmt)."</u>";
										//}
										$NxtFinYrTotalAmt_Total = $NxtFinYrTotalAmt_Total + $NxtFinYrTotalAmt;  
										?>
										</td>
										<td valign="middle" rowspan="2" align="justify"><?php echo $Remarks; ?></td>
									</tr>
									<?php /*}else{ ?>
									<tr id="C<?php echo $sheetid;?>">
										<td valign="middle" rowspan="2"><?php echo $SheetList->tech_sanction; ?></td>
										<td valign="middle" rowspan="2" data-id="<?php echo $sheetid;?>"><?php echo $SheetList->work_order_no; ?></td>
										<td valign="middle" rowspan="2" style="text-align: justify; text-justify: inter-word;" data-id="<?php echo $sheetid;?>"><?php echo $SheetList->work_name; ?></td>
									
										<td valign="middle" rowspan="2" align="center" data-id="<?php echo $sheetid;?>"><?php echo $SheetList->computer_code_no; ?></td>
										<td valign="middle" rowspan="2" align="right"><?php echo IND_money_format($SheetList->work_order_cost); ?></td>
										<td valign="middle" rowspan="2" align="right"><?php echo dt_display($SheetList->work_order_date); ?></td>
										<td valign="middle" rowspan="2" align="center"><?php echo $SheetList->work_duration; ?> Month<?php if($SheetList->work_duration > 1){ echo "s"; } ?></td>
										<td valign="middle" rowspan="2" align="right"><?php echo dt_display($SheetList->date_of_completion); ?></td>
										<td valign="middle" align="left" style="color:#E51863">Committed <br/> Expenditure</td>
										
										
										<td valign="middle" align="right"><?php if($April != 0){ echo "<span class='rspan-pink'>".$April."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($May != 0){ echo "<span class='rspan-pink'>".$May."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($June != 0){ echo "<span class='rspan-pink'>".$June."</span>"; } ?></td>
										
										<td valign="middle" align="right"><?php if($July != 0){ echo "<span class='rspan-pink'>".$July."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($Aug != 0){ echo "<span class='rspan-pink'>".$Aug."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($Sep != 0){ echo "<span class='rspan-pink'>".$Sep."</span>"; } ?></td>
										
										<td valign="middle" align="right"><?php if($Oct != 0){ echo "<span class='rspan-pink'>".$Oct."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($Nov != 0){ echo "<span class='rspan-pink'>".$Nov."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($Decs != 0){ echo "<span class='rspan-pink'>".$Decs."</span>"; } ?></td>
										
										<td valign="middle" align="right"><?php if($Jan != 0){ echo "<span class='rspan-pink'>".$Jan."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($Feb != 0){ echo "<span class='rspan-pink'>".$Feb."</span>"; } ?></td>
										<td valign="middle" align="right"><?php if($March != 0){ echo "<span class='rspan-pink'>".$March."</span>"; } ?></td>
									</tr>
									<?php }*/ ?>
									<tr id="A<?php echo $GlobId;?>">
									    <td valign="middle" align="center" class="sticky-cell sticky-cell6" style="color:#017B0A">A.E.</td>
										<td valign="middle" align="right">
										<?php 
										$PrevFinYrTotalAmt = GetNextFinYearWorkTotalActual($sheetid,$PrevFY);
										//echo $NxtFinYrAmt; 
										//if($PrevFinYrTotalAmt != 0){
											echo "<a title='Click here to view Previous FY' >".IndianMoneyFormat($PrevFinYrTotalAmt)."</a>";
										    
										//}
										$PrevFinYrTotalAmt_Total = $PrevFinYrTotalAmt_Total + $PrevFinYrTotalAmt;  
										?>
										<td valign="middle" align="right"><?php if($Q1M1Amt != 0){ echo "<span class='rspan-green' id='ae_row1".$GlobId."'>".IndianMoneyFormat($Q1M1Amt)."</span>"; } $AEAprTotal = $AEAprTotal + $Q1M1Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q1M2Amt != 0){ echo "<span class='rspan-green' id='ae_row2".$GlobId."'>".IndianMoneyFormat($Q1M2Amt)."</span>"; } $AEMayTotal = $AEMayTotal + $Q1M2Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q1M3Amt != 0){ echo "<span class='rspan-green' id='ae_row3".$GlobId."'>".IndianMoneyFormat($Q1M3Amt)."</span>"; } $AEJunTotal = $AEJunTotal + $Q1M3Amt; ?></td>
										<td valign="middle" align="right" class="tot-row2"><?php if($Q1TOTAL != 0){ echo IndianMoneyFormat($Q1TOTAL); } $AEQ1Total = $AEQ1Total + $Q1TOTAL; ?></td>
										
										<td valign="middle" align="right"><?php if($Q2M1Amt != 0){ echo "<span class='rspan-green' id='ae_row4".$GlobId."'>".IndianMoneyFormat($Q2M1Amt)."</span>"; } $AEJulTotal = $AEJulTotal + $Q2M1Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q2M2Amt != 0){ echo "<span class='rspan-green' id='ae_row5".$GlobId."'>".IndianMoneyFormat($Q2M2Amt)."</span>"; } $AEAugTotal = $AEAugTotal + $Q2M2Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q2M3Amt != 0){ echo "<span class='rspan-green' id='ae_row6".$GlobId."'>".IndianMoneyFormat($Q2M3Amt)."</span>"; } $AESepTotal = $AESepTotal + $Q2M3Amt; ?></td>
										<td valign="middle" align="right" class="tot-row2"><?php if($Q2TOTAL != 0){ echo IndianMoneyFormat($Q2TOTAL); } $AEQ2Total = $AEQ2Total + $Q2TOTAL; ?></td>
										
										<td valign="middle" align="right"><?php if($Q3M1Amt != 0){ echo "<span class='rspan-green' id='ae_row7".$GlobId."'>".IndianMoneyFormat($Q3M1Amt)."</span>"; } $AEOctTotal = $AEOctTotal + $Q3M1Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q3M2Amt != 0){ echo "<span class='rspan-green' id='ae_row8".$GlobId."'>".IndianMoneyFormat($Q3M2Amt)."</span>"; } $AENovTotal = $AENovTotal + $Q3M2Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q3M3Amt != 0){ echo "<span class='rspan-green' id='ae_row9".$GlobId."'>".IndianMoneyFormat($Q3M3Amt)."</span>"; } $AEDecTotal = $AEDecTotal + $Q3M3Amt; ?></td>
										<td valign="middle" align="right" class="tot-row2"><?php if($Q3TOTAL != 0){ echo IndianMoneyFormat($Q3TOTAL); } $AEQ3Total = $AEQ3Total + $Q3TOTAL; ?></td>
										
										<td valign="middle" align="right"><?php if($Q4M1Amt != 0){ echo "<span class='rspan-green' id='ae_row10".$GlobId."'>".IndianMoneyFormat($Q4M1Amt)."</span>"; } $AEJanTotal = $AEJanTotal + $Q4M1Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q4M2Amt != 0){ echo "<span class='rspan-green' id='ae_row11".$GlobId."'>".IndianMoneyFormat($Q4M2Amt)."</span>"; } $AEFebTotal = $AEFebTotal + $Q4M2Amt; ?></td>
										<td valign="middle" align="right"><?php if($Q4M3Amt != 0){ echo "<span class='rspan-green' id='ae_row12".$GlobId."'>".IndianMoneyFormat($Q4M3Amt)."</span>"; } $AEMarTotal = $AEMarTotal + $Q4M3Amt; ?></td>
										<td valign="middle" align="right" class="tot-row2"><?php if($Q4TOTAL != 0){ echo IndianMoneyFormat($Q4TOTAL); } $AEQ4Total = $AEQ4Total + $Q4TOTAL; ?></td>
										<td valign="middle" align="right"><?php if($TotalAmtAE != 0){ echo IndianMoneyFormat($TotalAmtAE); } $AEOverAllTotalAmt = $AEOverAllTotalAmt + $TotalAmtAE; ?></td>
										
									</tr>
								<?php $Previd = $CurrId; $PrevPinIdArr = $PinIdArr; $TotalWorkOrderCost = $TotalWorkOrderCost + $SheetList->work_order_cost; } ?>
								
								<?php 
									//$Q1TOTALCE = 0; $Q2TOTALCE = 0; $Q3TOTALCE = 0; $Q4TOTALCE = 0;
									$SelectQueryVr1 = "select * from voucher_upt where vr_dt >= '$FinaFDate' and vr_dt <= '$FinaTDate'";
									$SelectSqlVr1   = mysql_query($SelectQueryVr1);
									if($SelectSqlVr1 == true){
										if(mysql_num_rows($SelectSqlVr1) > 0){
											while($VrList = mysql_fetch_object($SelectSqlVr1)){
											$April = 0; $May = 0; $June = 0; $July = 0; $Aug = 0; $Sep = 0; 
											$Oct = 0; $Nov = 0; $Decs = 0; $Jan = 0; $Feb = 0; $March = 0; $Budid = ""; $NxtFinYrAmt = 0; $TotalAmtAE = 0; $Remarks = "";
											$VoucherAmt = $VrList->vr_amt;
											$VoucherDate = $VrList->vr_dt;
											$ExpVoucherDate = explode("-",$VoucherDate);
											$VoucherMonth = $ExpVoucherDate[1];
											if(($VoucherMonth == 1)||($VoucherMonth == '01')){
												$Jan = round($VoucherAmt/100000,2);
												$Q4TOTALAE = $Jan;
												$TotalAmtAE = $TotalAmtAE + $Jan;
											}
											if(($VoucherMonth == 2)||($VoucherMonth == '02')){
												$Feb = round($VoucherAmt/100000,2);
												$Q4TOTALAE = $Feb;
												$TotalAmtAE = $TotalAmtAE + $Feb;
											}
											if(($VoucherMonth == 3)||($VoucherMonth == '03')){
												$March = round($VoucherAmt/100000,2);
												$Q4TOTALAE = $March;
												$TotalAmtAE = $TotalAmtAE + $March;
											}
											if(($VoucherMonth == 4)||($VoucherMonth == '04')){
												$April = round($VoucherAmt/100000,2);
												$Q1TOTALAE = $April;
												$TotalAmtAE = $TotalAmtAE + $April;
											}
											if(($VoucherMonth == 5)||($VoucherMonth == '05')){
												$May = round($VoucherAmt/100000,2);
												$Q1TOTALAE = $May;
												$TotalAmtAE = $TotalAmtAE + $May;
											}
											if(($VoucherMonth == 6)||($VoucherMonth == '06')){
												$June = round($VoucherAmt/100000,2);
												$Q1TOTALAE = $June;
												$TotalAmtAE = $TotalAmtAE + $June;
											}
											if(($VoucherMonth == 7)||($VoucherMonth == '07')){
												$July = round($VoucherAmt/100000,2);
												$Q2TOTALAE = $July;
												$TotalAmtAE = $TotalAmtAE + $July;
											}
											if(($VoucherMonth == 8)||($VoucherMonth == '08')){
												$Aug = round($VoucherAmt/100000,2);
												$Q2TOTALAE = $Aug;
												$TotalAmtAE = $TotalAmtAE + $Aug;
											}
											if(($VoucherMonth == 9)||($VoucherMonth == '09')){
												$Sep = round($VoucherAmt/100000,2);
												$Q2TOTALAE = $Sep;
												$TotalAmtAE = $TotalAmtAE + $Sep;
											}
											if(($VoucherMonth == 10)||($VoucherMonth == '10')){
												$Oct = round($VoucherAmt/100000,2);
												$Q3TOTALAE = $Oct;
												$TotalAmtAE = $TotalAmtAE + $Oct;
											}
											if(($VoucherMonth == 11)||($VoucherMonth == '11')){
												$Nov = round($VoucherAmt/100000,2);
												$Q3TOTALAE = $Nov;
												$TotalAmtAE = $TotalAmtAE + $Nov;
											}
											if(($VoucherMonth == 12)||($VoucherMonth == '12')){
												$Decs = round($VoucherAmt/100000,2);
												$Q3TOTALAE = $Decs;
												$TotalAmtAE = $TotalAmtAE + $Decs;
											}
								?>
									
									
									<tr id="C<?php echo $GlobId;?>">
										<td valign="middle" class="sticky-cell" align="center"><?php echo $VrList->hoa; ?></td>
										<td valign="middle" class="sticky-cell sticky-cell1"><?php //echo $TSNO; ?></td>
										<td valign="middle" class="sticky-cell sticky-cell2" style="text-align: justify; text-justify: inter-word;" data-pinid="<?php echo $PinId;?>" data-id="<?php echo $GlobId;?>">
											<?php echo $VrList->item; ?>
										</td>
										<td valign="middle" class="sticky-cell sticky-cell3" align="center"><?php //echo $SheetList->computer_code_no; ?></td>
										<td valign="middle" class="sticky-cell sticky-cell4"  align="center" style="padding:0px;">
											<?php
											 if($VrList->wo_amt != ""){ 
												//echo '<span class="rspan-pink">'.IND_money_format($SheetList->work_order_cost).'</span>'; 
												//echo '<tr><td style="color:#e51863" align="right">'.IND_money_format($SheetList->work_order_cost).'</td></tr>'; 
												echo '<div style="color:#e51863; margin-bottom:8px; padding-right:2px;" align="right">'.IND_money_format($VrList->wo_amt).'</div>'; 
											 }else{ 
												//echo '<span class="rspan-pink">'.IND_money_format($SheetList->project_amount).'</span>';
												//echo '<tr><td style="color:#e51863" align="right">'.IND_money_format($SheetList->project_amount).'</td></tr>'; 
												echo '<div style="color:#e51863; margin-bottom:8px; padding-right:2px;" align="right"></div>'; 
											 } 
											 if($VrList->vr_dt != '' && $VrList->vr_dt != '0000-00-00'){ 
												echo '<div style="margin-bottom:10px; padding-right:2px;" align="right"><span class="rspan-pink" style="color:505251">'.dt_display($VrList->vr_dt).'</span></div>';
											 } 
											 
											 ?>
										</td>
										<td valign="middle" class="sticky-cell sticky-cell5" align="left"><?php echo $VrList->name_contractor; ?><br/><br/><?php if($VrList->work_duration > 0) { ?><span class="rspan-pink" style="color:505251">Period - <?php  echo $VrList->work_duration; ?> Month<?php if($VrList->work_duration > 1){ echo "s"; } } ?></span></td>
										<td valign="middle" class="sticky-cell sticky-cell6" align="center" style="color:#017B0A">A.E.</td>
										<td valign="middle" align="right">
										
										</td>
										<td valign="middle" align="right"><?php if($April != 0){ echo "<span class='rspan-pink' id='ce_row1".$GlobId."'>".IndianMoneyFormat($April)."</span>"; } $AEAprTotal = $AEAprTotal + $April; ?></td>
										<td valign="middle" align="right"><?php if($May != 0){ echo "<span class='rspan-pink' id='ce_row2".$GlobId."'>".IndianMoneyFormat($May)."</span>"; }  $AEMayTotal = $AEMayTotal + $May;  ?></td>
										<td valign="middle" align="right"><?php if($June != 0){ echo "<span class='rspan-pink' id='ce_row3".$GlobId."'>".IndianMoneyFormat($June)."</span>"; }  $AEJunTotal = $AEJunTotal + $June; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q1TOTALAE != 0){ echo IndianMoneyFormat($Q1TOTALAE); } $AEQ1Total = $AEQ1Total + $Q1TOTALAE; ?></td>
										<td valign="middle" align="right"><?php if($July != 0){ echo "<span class='rspan-pink' id='ce_row4".$GlobId."'>".IndianMoneyFormat($July)."</span>"; }  $AEJulTotal = $AEJulTotal + $July; ?></td>
										<td valign="middle" align="right"><?php if($Aug != 0){ echo "<span class='rspan-pink' id='ce_row5".$GlobId."'>".IndianMoneyFormat($Aug)."</span>"; }  $AEAugTotal = $AEAugTotal + $Aug; ?></td>
										<td valign="middle" align="right"><?php if($Sep != 0){ echo "<span class='rspan-pink' id='ce_row6".$GlobId."'>".IndianMoneyFormat($Sep)."</span>"; }  $AESepTotal = $AESepTotal + $Sep; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q2TOTALAE != 0){ echo IndianMoneyFormat($Q2TOTALAE); } $AEQ2Total = $AEQ2Total + $Q2TOTALAE; ?></td>
										<td valign="middle" align="right"><?php if($Oct != 0){ echo "<span class='rspan-pink' id='ce_row7".$GlobId."'>".IndianMoneyFormat($Oct)."</span>"; } $AEOctTotal = $AEOctTotal + $Oct; ?></td>
										<td valign="middle" align="right"><?php if($Nov != 0){ echo "<span class='rspan-pink' id='ce_row8".$GlobId."'>".IndianMoneyFormat($Nov)."</span>"; } $AENovTotal = $AENovTotal + $Nov; ?></td>
										<td valign="middle" align="right"><?php if($Decs != 0){ echo "<span class='rspan-pink' id='ce_row9".$GlobId."'>".IndianMoneyFormat($Decs)."</span>"; } $AEDecTotal = $AEDecTotal + $Decs; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q3TOTALAE != 0){ echo IndianMoneyFormat($Q3TOTALAE); } $AEQ3Total = $AEQ3Total + $Q3TOTALAE; ?></td>
										<td valign="middle" align="right"><?php if($Jan != 0){ echo "<span class='rspan-pink' id='ce_row10".$GlobId."'>".IndianMoneyFormat($Jan)."</span>"; } $AEJanTotal = $AEJanTotal + $Jan; ?></td>
										<td valign="middle" align="right"><?php if($Feb != 0){ echo "<span class='rspan-pink' id='ce_row11".$GlobId."'>".IndianMoneyFormat($Feb)."</span>"; } $AEFebTotal = $AEFebTotal + $Feb; ?></td>
										<td valign="middle" align="right"><?php if($March != 0){ echo "<span class='rspan-pink' id='ce_row12".$GlobId."'>".IndianMoneyFormat($March)."</span>"; } $AEMarTotal = $AEMarTotal + $March; ?></td>
										<td valign="middle" align="right" class="tot-row1"><?php if($Q4TOTALAE != 0){ echo IndianMoneyFormat($Q4TOTALAE); } $AEQ4Total = $AEQ4Total + $Q4TOTALAE; ?></td>
										<td valign="middle" align="right"><?php if($TotalAmtAE != 0){ echo IndianMoneyFormat($TotalAmtAE); } $AEOverAllTotalAmt = $AEOverAllTotalAmt + $TotalAmtAE; ?></td>
										<td valign="middle" align="right">
										
										</td>
										<td valign="middle" align="justify"><?php echo $Remarks; ?></td>
									</tr>
								<?php } } } ?>	
								
								
									<tr class="PIN-TOTAL">
										<td valign="middle" class="sticky-cell" style="background:#EFF0F1 !important" align="right" colspan="3" rowspan="2">TOTAL</td>
										<td valign="middle" class="sticky-cell sticky-cell3" style="background:#EFF0F1 !important" rowspan="2">&nbsp;</td>
										<td valign="middle" class="sticky-cell sticky-cell4" style="background:#EFF0F1 !important" rowspan="2" align="right"><?php //echo IndianMoneyFormat($TotalWorkOrderCost); ?></td>
										
										<td valign="middle" class="sticky-cell sticky-cell5 tot-row1" colspan="2" align="right" style="color:#E51863">TOTAL ( C.E. )</td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right">&nbsp;</td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEAprTotal != 0){ echo IndianMoneyFormat($CEAprTotal); } $CEAprTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEMayTotal != 0){ echo IndianMoneyFormat($CEMayTotal); } $CEMayTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJunTotal != 0){ echo IndianMoneyFormat($CEJunTotal); } $CEJunTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ1Total != 0){ echo IndianMoneyFormat($CEQ1Total);} $CEQ1Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJulTotal != 0){ echo IndianMoneyFormat($CEJulTotal); } $CEJulTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEAugTotal != 0){ echo IndianMoneyFormat($CEAugTotal); } $CEAugTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CESepTotal != 0){ echo IndianMoneyFormat($CESepTotal); } $CESepTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ2Total != 0){ echo IndianMoneyFormat($CEQ2Total); } $CEQ2Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEOctTotal != 0){ echo IndianMoneyFormat($CEOctTotal); } $CEOctTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CENovTotal != 0){ echo IndianMoneyFormat($CENovTotal); } $CENovTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEDecTotal != 0){ echo IndianMoneyFormat($CEDecTotal); } $CEDecTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ3Total != 0){ echo IndianMoneyFormat($CEQ3Total); } $CEQ3Total = 0; ?></td>
										
										<td valign="middle" class="tot-row1" align="right"><?php if($CEJanTotal != 0){ echo IndianMoneyFormat($CEJanTotal); } $CEJanTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEFebTotal != 0){ echo IndianMoneyFormat($CEFebTotal); } $CEFebTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEMarTotal != 0){ echo IndianMoneyFormat($CEMarTotal); } $CEMarTotal = 0; ?></td>
										<td valign="middle" class="tot-row1" align="right"><?php if($CEQ4Total != 0){ echo IndianMoneyFormat($CEQ4Total); } $CEQ4Total = 0; ?></td>
										
										
										<td valign="middle" style="background:#EFF0F1 !important" align="right"><?php if($CEOverAllTotalAmt != 0){ echo IndianMoneyFormat($CEOverAllTotalAmt);} $CEOverAllTotalAmt = 0; ?></td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right" rowspan="2"><?php if($NxtFinYrTotalAmt_Total != 0){ echo IndianMoneyFormat($NxtFinYrTotalAmt_Total); } $NxtFinYrTotalAmt_Total = 0; ?>
										<td valign="middle" style="background:#EFF0F1 !important" rowspan="2">&nbsp;</td>
									</tr>
									
									<tr class="PIN-TOTAL">
										<td valign="middle" class="sticky-cell sticky-cell5 tot-row2" colspan="2" align="right" style="color:#017B0A">TOTAL ( A.E. )</td>
										<td valign="middle" style="background:#EFF0F1 !important" align="right">&nbsp;</td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEAprTotal != 0){ echo IndianMoneyFormat($AEAprTotal); } $AEAprTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEMayTotal != 0){ echo IndianMoneyFormat($AEMayTotal); } $AEMayTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJunTotal != 0){ echo IndianMoneyFormat($AEJunTotal); } $AEJunTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ1Total != 0){ echo IndianMoneyFormat($AEQ1Total); } $AEQ1Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJulTotal != 0){ echo IndianMoneyFormat($AEJulTotal); } $AEJulTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEAugTotal != 0){ echo IndianMoneyFormat($AEAugTotal); } $AEAugTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AESepTotal != 0){ echo IndianMoneyFormat($AESepTotal); } $AESepTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ2Total != 0){ echo IndianMoneyFormat($AEQ2Total); } $AEQ2Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEOctTotal != 0){ echo IndianMoneyFormat($AEOctTotal); } $AEOctTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AENovTotal != 0){ echo IndianMoneyFormat($AENovTotal); } $AENovTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEDecTotal != 0){ echo IndianMoneyFormat($AEDecTotal); } $AEDecTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ3Total != 0){ echo IndianMoneyFormat($AEQ3Total); } $AEQ3Total = 0; ?></td>
										
										<td valign="middle" class="tot-row2" align="right"><?php if($AEJanTotal != 0){ echo IndianMoneyFormat($AEJanTotal); } $AEJanTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEFebTotal != 0){ echo IndianMoneyFormat($AEFebTotal); } $AEFebTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEMarTotal != 0){ echo IndianMoneyFormat($AEMarTotal); } $AEMarTotal = 0; ?></td>
										<td valign="middle" class="tot-row2" align="right"><?php if($AEQ4Total != 0){ echo IndianMoneyFormat($AEQ4Total); }else{ echo "&nbsp;"; } $AEQ4Total = 0; ?></td>
										
										
										<td valign="middle" style="background:#EFF0F1 !important" align="right"><?php if($AEOverAllTotalAmt != 0){ echo IndianMoneyFormat($AEOverAllTotalAmt); } $AEOverAllTotalAmt = 0; ?></td>
									</tr>
								<?php } ?>
									</tbody>		   
								</table>
							</div>
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
							<div class="col-md-12 no-padding printbutton">
								<input type="hidden" name="cmb_finyear" id="cmb_finyear" value="<?php echo $FinaYears; ?>">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();"/>
								<!--<input type="submit" name="printview" value="Print View" id="printview" class="backbutton"/>-->
								<!--<input type="button" name="print" value="Print" id="print" class="backbutton" onClick="PrintBook();" />-->
								<!--<input type="submit" data-type="submit" value=" Save " name="submit" id="submit"/>-->
							</div>
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
							<div class="col-md-12 no-padding hide" id="new-data" style=" width:1500px;">
								<div id="<?php echo $CCNO; ?>">
								<div class="well well-sm">Name of Work : <span id="work_name"></span></div>
								<div class="well well-sm">
								    <span class="rlable-pink">CC No :<span id="ccno"></span></span>&nbsp;
									<span class="rlable-pink">W.O. Value : <span id="work_order_val"></span></span>&nbsp;
									<span class="rlable-pink">W.O. Date : <span id="work_order_date"></span> </span>&nbsp;
									<span class="rlable-pink">Contract Name  : <span id="con_name"></span> </span>&nbsp;
									<span class="rlable-pink">Period : <span id="work_duration"></span> </span>&nbsp;
									<span class="rlable-pink">SDC : <span id="sch_date_com"></span> </span>&nbsp;
									<span class="rlable-pink">W.O. No. : <span id="work_order_no"></span></span>&nbsp;
								</div>
								<div class="row">
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q1</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<th></th>
															<th id="APR" nowrap="nowrap">APR-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="MAY" nowrap="nowrap">MAY-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">JUNE-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>
															<td><input type="text" class="form-control rtext Q1CE1" id="Q1CE1" name="Q1CE1"></td>
															<td><input type="text" class="form-control rtext Q1CE2" id="Q1CE2" name="Q1CE2"></td>
															<td><input type="text" class="form-control rtext Q1CE3" id="Q1CE3" name="Q1CE3"></td>
															<td><input type="text" class="form-control rtext Q1CET" id="Q1CET" name="Q1CET" readonly=""></td>
														</tr>
														<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q1AE1" id="Q1AE1" name="Q1AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AE2" id="Q1AE2" name="Q1AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AE3" id="Q1AE3" name="Q1AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AET" id="Q1AET" name="Q1AET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q1BAL1" id="Q1BAL1" name="Q1BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1BAL2" id="Q1BAL2" name="Q1BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1BAL3" id="Q1BAL2" name="Q1BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q2</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<th></th>
															<th id="JULY" nowrap="nowrap">JULY-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="AUG" nowrap="nowrap">AUG-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="SEP" nowrap="nowrap">SEP-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr >
															<td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>
															<td><input type="text" class="form-control rtext Q2CE1" id="Q2CE1" name="Q2CE1"></td>
															<td><input type="text" class="form-control rtext Q2CE2" id="Q2CE2" name="Q2CE2"></td>
															<td><input type="text" class="form-control rtext Q2CE3" id="Q2CE3" name="Q2CE3"></td>
															<td><input type="text" class="form-control rtext Q2CET" id="Q2CET" name="Q2CET" readonly=""></td>
														</tr>
														<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q2AE1" id="Q2AE1" name="Q2AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AE2" id="Q2AE2" name="Q2AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AE3" id="Q2AE3" name="Q2AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AET" id="Q2AET" name="Q2AET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q2BAL1" id="Q2BAL1" name="Q2BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2BAL2" id="Q2BAL2" name="Q2BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2BAL3" id="Q2BAL2" name="Q2BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q3</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<th></th>
															<th id="OCT" nowrap="nowrap">OCT-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="NOV" nowrap="nowrap">NOV-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="DEC" nowrap="nowrap">DEC-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>
															<td><input type="text" class="form-control rtext Q3CE1" id="Q3CE1" name="Q3CE1"></td>
															<td><input type="text" class="form-control rtext Q3CE2" id="Q3CE2" name="Q3CE2"></td>
															<td><input type="text" class="form-control rtext Q3CE3" id="Q3CE3" name="Q3CE3"></td>
															<td><input type="text" class="form-control rtext Q3CET" id="Q3CET" name="Q3CET" readonly=""></td>
														</tr>
														<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q3AE1" id="Q3AE1" name="Q3AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AE2" id="Q3AE2" name="Q3AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AE3" id="Q3AE3" name="Q3AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AET" id="Q3AET" name="Q3AET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q3BAL1" id="Q3BAL1" name="Q3BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3BAL2" id="Q3BAL2" name="Q3BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3BAL3" id="Q3BAL2" name="Q3BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q4</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<th></th>
															<th id="JAN" nowrap="nowrap">JAN-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="FEB" nowrap="nowrap">FEB-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="MAR" nowrap="nowrap">MAR-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>
															<td><input type="text" class="form-control rtext Q4CE1" id="Q4CE1" name="Q4CE1"></td>
															<td><input type="text" class="form-control rtext Q4CE2" id="Q4CE2" name="Q4CE2"></td>
															<td><input type="text" class="form-control rtext Q4CE3" id="Q4CE3" name="Q4CE3"></td>
															<td><input type="text" class="form-control rtext Q4CET" id="Q4CET" name="Q4CET" readonly=""></td>
														</tr>
														<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q4AE1" id="Q4AE1" name="Q4AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AE2" id="Q4AE2" name="Q4AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AE3" id="Q4AE3" name="Q4AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AET" id="Q4AET" name="Q4AET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q4BAL1" id="Q4BAL1" name="Q4BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4BAL2" id="Q4BAL2" name="Q4BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4BAL3" id="Q4BAL2" name="Q4BAL2" readonly=""></td>
														</tr>-->
													</thead>
													
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="div11" align="center" style="margin-top:3px">
										<textarea class="form-control remarks" placeholder="Enter your remarks here" name="txt_remarks_modal" id="txt_remarks_modal"></textarea>
										<input type="hidden" name="txt_fin_yr_modal" id="txt_fin_yr_modal" value="<?php echo $FinaYears; ?>">
										<input type="hidden" name="txt_globid_modal" id="txt_globid_modal" class="txt_globid_modal">
										<input type="hidden" name="txt_pinid_modal" id="txt_pinid_modal" class="txt_pinid_modal">
									</div>
									<div class="div1 cls" align="center" style="margin-top:0px">
										<input type="submit" class="btn btn-primary div10" name="btn_save" id="btn_save" value=" SAVE " style="float:none; margin-top:3px">
										<input type="button" class="btn btn-info div10" name="btn_close" id="btn_close" data-dismiss="modal" value=" CLOSE " style="float:none; margin-top:3px">
									</div>
								</div>
								<div class="div12">fd&nbsp;</div>
							</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<link rel="stylesheet" href="css/style-3.css">

<script src="notificationAlert/jquery.min-3.2.1.js"></script>
<script src="notificationAlert/bootstrap-notify.js"></script>
<script src="notificationAlert/bootstrap-notify.min.js"></script>
<script>
	var old_count = 0; 
	function goBack(){
		url = "BudgetExpenditureReportGenerate.php";
	  	window.location.replace(url);
	}
	/*function PrintPDF(){
		document.form.method="post";
		document.form.target = "_blank";
		url = "BudgetExpenditureReportPDF.php";
	  	window.open(url)
	}*/
  	var msg = "<?php echo $msg; ?>";
  	var success = "<?php echo $success; ?>";
  	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			if(success == 1){
				swal("", msg, "success");
			}else{
				swal(msg, "", "");
			}
		}
	};
	
	$(document).ready(function() {
		function FreezeRowColumn(){
			var h1	= $('#h1').width();
			var h2 	= $('#h2').width();
			var h3 	= $('#h3').width();
			var h4 	= $('#h4').width();
			var h5 	= $('#h5').width();
			var h6 	= $('#h6').width();
			var h7 	= $('#h7').width();
			var h8 	= $('#h8').width();
			var h9 	= $('#h9').width();
			var h10 = $('#h10').width();
			var a 	= h1+12;
			var b 	= a+h2+12;
			var c 	= b+h3+12;
			var d 	= c+h4+12;
			var e 	= d+h5+11;
			var f 	= e+h6+11;
			var g 	= f+h7+11;
			var h 	= g+h8+11;
			var i 	= h+h9+11;
			var j 	= i+h10+11;
			//alert("H1 = "+h1+" : H2 = "+h2+" : H3 = "+h3+" : H4 = "+h4+" : H5 = "+h5);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell1').css('left', a);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell2').css('left', b);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell3').css('left', c);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell4').css('left', d);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell5').css('left', e);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell6').css('left', f);
			/*$('.sticky-table.sticky-ltr-cells table td.sticky-cell7').css('left', g);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell8').css('left', h);
			$('.sticky-table.sticky-ltr-cells table td.sticky-cell9').css('left', i);*/
			$('#h2').css('left', a);
			$('#h3').css('left', b);
			$('#h4').css('left', c);
			$('#h5').css('left', d);
			$('#h6').css('left', e);
			$('#h7').css('left', f);
			$('#h8').css('left', g);
			$('#h9').css('left', h);
			$('#h10').css('left', i);
			
			var ht	= $('#Q1').height();
			var Q 	= ht + 11
			$('#Q1C1').css('top', Q);
			$('#Q1C2').css('top', Q);
			$('#Q1C3').css('top', Q);
			$('#Q1C4').css('top', Q);
			$('#Q2C1').css('top', Q);
			$('#Q2C2').css('top', Q);
			$('#Q2C3').css('top', Q);
			$('#Q2C4').css('top', Q);
			$('#Q3C1').css('top', Q);
			$('#Q3C2').css('top', Q);
			$('#Q3C3').css('top', Q);
			$('#Q3C4').css('top', Q);
			$('#Q4C1').css('top', Q);
			$('#Q4C2').css('top', Q);
			$('#Q4C3').css('top', Q);
			$('#Q4C4').css('top', Q);
		}
		FreezeRowColumn();
		$("#Search").keyup(function(){
			mySearch();
			FreezeRowColumn();
		});
	});
	
	function mySearch() {
		var input, filter, table, tr, td, i, txtValue;
		var hideRow = 0;
	  	input = document.getElementById("Search");
	  	filter = input.value.toUpperCase();
	  	table = document.getElementById("fixTable");
	  	tr = table.getElementsByTagName("tr");
	  	for (i = 0; i < tr.length; i++) {
			var start = 2, end = 3, temp = 0, j;
			var tds = tr[i].getElementsByTagName("td")[2]; 
			if(tds){//alert(tds);
				var sheetid = tds.getAttribute('data-id');
				var pinid = tds.getAttribute('data-pinid');
				for(j = start; j <= end; j++){
					td = tr[i].getElementsByTagName("td")[j];
					if (td) {
						txtValue = td.textContent || td.innerText; 
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							temp++;
						}
					}  
				} 
				if(temp > 0){
					if(sheetid != null){
						document.getElementById("C"+sheetid).style.display = "";
						document.getElementById("A"+sheetid).style.display = "";
					}
				}else{
					if(sheetid != null){
						document.getElementById("C"+sheetid).style.display = "none";
						document.getElementById("A"+sheetid).style.display = "none";
						hideRow++;
					}
				}   
			}
	  	}
		if(hideRow > 0){
			$(".PIN-TOTAL").hide();
		}else{
			$(".PIN-TOTAL").show();
		}
	}
</script>

<script type="text/javascript">
  //$(".Details").on('click',function() {
  $('body').on("click",".Details", function(e){ 
  	  ClearCell();
      var GlobId           = $(this).attr('data-id');
      var Name_of_work     = $(this).attr('data-work_name'); 
      var CCNO             = $(this).attr('data-cc_no');
	  var Work_Order_Value = $(this).attr('data-work_value');
	  var Work_Order_Date  = $(this).attr('data-work_order_date');
	  var Cont_Name        = $(this).attr('data-cont_name');
	  var work_duration    = $(this).attr('data-work_duration');
	  var work_sdc         = $(this).attr('data-work_sdc');
	  var work_order_no    = $(this).attr('data-wo_no');
	  var Remarks          = $(this).attr('data-remarks'); 
	  var PinId            = $(this).attr('data-pinid');
      $("#work_name").text(Name_of_work); 
	  $("#ccno").text(CCNO);
	  $("#work_order_val").text(Work_Order_Value); 
	  $("#work_order_date").text(Work_Order_Date);
	  $("#con_name").text(Cont_Name); 
	  $("#work_duration").text(work_duration); 
	  $("#sch_date_com").text(work_sdc);  
	  $("#work_order_no").text(work_order_no);  
	 
	  var ce_row1 	= $("#ce_row1"+GlobId).text().replace(/,/g , '');  var x1 = ce_row1; 	if(x1 == ""){ x1 = 0; }
	  var ce_row2 	= $("#ce_row2"+GlobId).text().replace(/,/g , '');  var x2 = ce_row2; 	if(x2 == ""){ x2 = 0; }
	  var ce_row3 	= $("#ce_row3"+GlobId).text().replace(/,/g , '');  var x3 = ce_row3; 	if(x3 == ""){ x3 = 0; }
	  var ce_row4 	= $("#ce_row4"+GlobId).text().replace(/,/g , '');  var x4 = ce_row4; 	if(x4 == ""){ x4 = 0; }
	  var ce_row5 	= $("#ce_row5"+GlobId).text().replace(/,/g , '');  var x5 = ce_row5; 	if(x5 == ""){ x5 = 0; }
	  var ce_row6 	= $("#ce_row6"+GlobId).text().replace(/,/g , '');  var x6 = ce_row6; 	if(x6 == ""){ x6 = 0; }
	  var ce_row7 	= $("#ce_row7"+GlobId).text().replace(/,/g , '');  var x7 = ce_row7; 	if(x7 == ""){ x7 = 0; }
	  var ce_row8 	= $("#ce_row8"+GlobId).text().replace(/,/g , '');  var x8 = ce_row8; 	if(x8 == ""){ x8 = 0; }
	  var ce_row9 	= $("#ce_row9"+GlobId).text().replace(/,/g , '');  var x9 = ce_row9; 	if(x9 == ""){ x9 = 0; }
	  var ce_row10 	= $("#ce_row10"+GlobId).text().replace(/,/g , ''); var x10 = ce_row10; if(x10 == ""){ x10 = 0; }
	  var ce_row11 	= $("#ce_row11"+GlobId).text().replace(/,/g , ''); var x11 = ce_row11; if(x11 == ""){ x11 = 0; }
	  var ce_row12 	= $("#ce_row12"+GlobId).text().replace(/,/g , ''); var x12 = ce_row12; if(x12 == ""){ x12 = 0; }
	  
	  var ae_row1 	= $("#ae_row1"+GlobId).text().replace(/,/g , '');  var y1 = ae_row1; 	if(y1 == ""){ y1 = 0; }
	  var ae_row2 	= $("#ae_row2"+GlobId).text().replace(/,/g , '');  var y2 = ae_row2; 	if(y2 == ""){ y2 = 0; }
	  var ae_row3 	= $("#ae_row3"+GlobId).text().replace(/,/g , '');  var y3 = ae_row3; 	if(y3 == ""){ y3 = 0; }
	  var ae_row4 	= $("#ae_row4"+GlobId).text().replace(/,/g , '');  var y4 = ae_row4; 	if(y4 == ""){ y4 = 0; }
	  var ae_row5 	= $("#ae_row5"+GlobId).text().replace(/,/g , '');  var y5 = ae_row5; 	if(y5 == ""){ y5 = 0; }
	  var ae_row6 	= $("#ae_row6"+GlobId).text().replace(/,/g , '');  var y6 = ae_row6; 	if(y6 == ""){ y6 = 0; }
	  var ae_row7 	= $("#ae_row7"+GlobId).text().replace(/,/g , '');  var y7 = ae_row7; 	if(y7 == ""){ y7 = 0; }
	  var ae_row8 	= $("#ae_row8"+GlobId).text().replace(/,/g , '');  var y8 = ae_row8; 	if(y8 == ""){ y8 = 0; }
	  var ae_row9 	= $("#ae_row9"+GlobId).text().replace(/,/g , '');  var y9 = ae_row9; 	if(y9 == ""){ y9 = 0; }
	  var ae_row10 	= $("#ae_row10"+GlobId).text(); var y10 = ae_row10; if(y10 == ""){ y10 = 0; }
	  var ae_row11 	= $("#ae_row11"+GlobId).text(); var y11 = ae_row11; if(y11 == ""){ y11 = 0; }
	  var ae_row12 	= $("#ae_row12"+GlobId).text(); var y12 = ae_row12; if(y12 == ""){ y12 = 0; }
	  var xy1 	= Number(x1)-Number(y1); 	var xy2 	= Number(x2)-Number(y2); 	var xy3 	= Number(x3)-Number(y3);
	  var xy4 	= Number(x4)-Number(y4); 	var xy5 	= Number(x5)-Number(y5); 	var xy6 	= Number(x6)-Number(y6);
	  var xy7 	= Number(x7)-Number(y7); 	var xy8 	= Number(x8)-Number(y8); 	var xy9 	= Number(x9)-Number(y9);
	  var xy10 	= Number(x10)-Number(y10); 	var xy11 	= Number(x11)-Number(y11); 	var xy12 	= Number(x12)-Number(y12);
	  var TotQ1X = Number(x1)+Number(x2)+Number(x3); 
	  var TotQ2X = Number(x4)+Number(x5)+Number(x6);
	  var TotQ3X = Number(x7)+Number(x8)+Number(x9);
	  var TotQ4X = Number(x10)+Number(x11)+Number(x12);
	  
	  var TotQ1Y = Number(y1)+Number(y2)+Number(y3); 
	  var TotQ2Y = Number(y4)+Number(y5)+Number(y6);
	  var TotQ3Y = Number(y7)+Number(y8)+Number(y9);
	  var TotQ4Y = Number(y10)+Number(y11)+Number(y12);
	  
      var $NewContent = $('<form id="modal-form" method="post" action="BudgetExpenditureReportCapital.php"></form>');
	  $NewContent.append($('#new-data').html());
	  BootstrapDialog.show({
			title: 'Capital Budget Expenditure Reports Work Details <i><u><?php echo $FinaYears; ?></u></i>',
			message: $NewContent,
			closable: false
			/*buttons: [{
				label: ' Close ',
				action: function(dialog) {
					dialog.close();
				}
			}]*/
	  });
	  $(".Q1CE1").val(ce_row1);
	  $(".Q1CE2").val(ce_row2);
	  $(".Q1CE3").val(ce_row3);
	  $(".Q1CET").val(TotQ1X.toFixed(2));
	  
	  $(".Q2CE1").val(ce_row4);
	  $(".Q2CE2").val(ce_row5);
	  $(".Q2CE3").val(ce_row6);
	  $(".Q2CET").val(TotQ2X.toFixed(2));
	  
	  $(".Q3CE1").val(ce_row7);
	  $(".Q3CE2").val(ce_row8);
	  $(".Q3CE3").val(ce_row9);
	  $(".Q3CET").val(TotQ3X.toFixed(2));
	  
	  $(".Q4CE1").val(ce_row10);
	  $(".Q4CE2").val(ce_row11); 
	  $(".Q4CE3").val(ce_row12);
	  $(".Q4CET").val(TotQ4X.toFixed(2));
	  
	  $(".Q1AE1").val(ae_row1);
	  $(".Q1AE2").val(ae_row2);
	  $(".Q1AE3").val(ae_row3);
	  $(".Q1AET").val(TotQ1Y.toFixed(2));
	  
	  $(".Q2AE1").val(ae_row4);
	  $(".Q2AE2").val(ae_row5);
	  $(".Q2AE3").val(ae_row6);
	  $(".Q2AET").val(TotQ2Y.toFixed(2));
	  
	  $(".Q3AE1").val(ae_row7);
	  $(".Q3AE2").val(ae_row8);
	  $(".Q3AE3").val(ae_row9);
	  $(".Q3AET").val(TotQ3Y.toFixed(2));
	  
	  $(".Q4AE1").val(ae_row10);
	  $(".Q4AE2").val(ae_row11); 
	  $(".Q4AE3").val(ae_row12); 
	  $(".Q4AET").val(TotQ4Y.toFixed(2));
	  
	  /*$(".Q1BAL1").val(xy1.toFixed(2));
	  $(".Q1BAL2").val(xy2.toFixed(2));
	  $(".Q1BAL3").val(xy3.toFixed(2));
	  $(".Q2BAL1").val(xy4.toFixed(2));
	  $(".Q2BAL2").val(xy5.toFixed(2));
	  $(".Q2BAL3").val(xy6.toFixed(2));
	  $(".Q3BAL1").val(xy7.toFixed(2));
	  $(".Q3BAL2").val(xy8.toFixed(2));
	  $(".Q3BAL3").val(xy9.toFixed(2));
	  $(".Q4BAL1").val(xy10.toFixed(2));
	  $(".Q4BAL2").val(xy11.toFixed(2)); 
	  $(".Q4BAL3").val(xy12.toFixed(2));*/
	  $(".txt_globid_modal").val(GlobId);
	  $(".txt_pinid_modal").val(PinId);
	  $(".remarks").val(Remarks)             
	/*$(document).on('shown.bs.modal', function (e) {
		  $('#modal-form .dataTab').DataTable();
	});*/
});
function ClearCell(){
	$(".Q1CE1").val('');
	$(".Q1CE2").val('');
	$(".Q1CE3").val('');
	$(".Q2CE1").val('');
	$(".Q2CE2").val('');
	$(".Q2CE3").val('');
	$(".Q3CE1").val('');
	$(".Q3CE2").val('');
	$(".Q3CE3").val('');
	$(".Q4CE1").val('');
	$(".Q4CE2").val(''); 
	$(".Q4CE3").val('');
	  
	$(".Q1AE1").val('');
	$(".Q1AE2").val('');
	$(".Q1AE3").val('');
	$(".Q2AE1").val('');
	$(".Q2AE2").val('');
	$(".Q2AE3").val('');
	$(".Q3AE1").val('');
	$(".Q3AE2").val('');
	$(".Q3AE3").val('');
	$(".Q4AE1").val('');
	$(".Q4AE2").val(''); 
	$(".Q4AE3").val('');    
	
	$(".Q1CET").val('');
	$(".Q2CET").val('');
	$(".Q3CET").val('');
	$(".Q4CET").val(''); 
	
	$(".Q1AET").val('');
	$(".Q2AET").val('');
	$(".Q3AET").val('');
	$(".Q4AET").val('');            
}
/*$('body').on("click","#btn_close", function(e){ 
	$("#new-data").modal("hide");
});*/

</script>
</script>
