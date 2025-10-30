<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
$msg = '';
$staffid 	= $_SESSION['sid'];
$userid 	= $_SESSION['userid'];
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
function indian_money_format($amount){
	if(round($amount) == 0){
		return '';
	}else{
		$amt1 = number_format($amount, 2, '.', '');
		$amt2 = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amt1);
		return $amt2;
	}
}

if(isset($_POST['btn_view']) == ' View '){
	$sheetid 		= $_POST['cmb_work_no'];
	$rbn 			= $_POST['txt_rbn'];
	$mbookno 		= $_POST['txt_mbookno'];
	$mbookpageno 	= $_POST['txt_mbook_page_no'];
	$SelectSheetQuery 	= "SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$SelectSheetSql 	= mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		$SheetList 				= 	mysql_fetch_object($SelectSheetSql);
		$work_name 				= 	$SheetList->work_name; 
		$short_name 			= 	$SheetList->short_name;   
		$tech_sanction 			= 	$SheetList->tech_sanction;  
		$name_contractor 		= 	$SheetList->name_contractor; 
		$ccno 					= 	$SheetList->computer_code_no;    
		$agree_no 				= 	$SheetList->agree_no; 
		$overall_rebate_perc 	= 	$SheetList->rebate_percent; 
		$runn_acc_bill_no 		= 	$rbn;
		$work_order_no 			= 	$SheetList->work_order_no; /* if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
		$length1 				= 	strlen($work_name);
		$start_line1 			= 	ceil($length1/70); 
		$length2 				= 	strlen($agree_no);
		$start_line2 			= 	ceil($length2/27);  
		$LineIncr 				= 	$start_line1 + $start_line2;// + 2 + 2; 
	}
	//echo $mbookpageno;exit;
	$SelectMBookPageQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'PA' and genlevel = 'ppayabs' and mbookorder = 1";
	$SelectMBookPageSql = mysql_query($SelectMBookPageQuery);
	if($SelectMBookPageSql == true){
		if(mysql_num_rows($SelectMBookPageSql)>0){
			$MBPageList 	= mysql_fetch_object($SelectMBookPageSql);
			$mbookno 		= $MBPageList->mbno;
			$mbookpageno	= $MBPageList->startpage;
		}
	}
}
$Line = $Line + $LineIncr;
//echo $start_line2;//exit;
/*$abstmbno 	= $mbookno;
$Page 		= $mbookpageno;
$Startpage 	= $Page;*/
//echo $Page;
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array(); $NextMBFlag = 1;
$SelectMBookQuery = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'PA' and genlevel = 'ppayabs' order by mbookorder asc";
$SelectMBookSql = mysql_query($SelectMBookQuery);
if($SelectMBookSql == true){
	if(mysql_num_rows($SelectMBookSql)>0){
		while($MBList = mysql_fetch_object($SelectMBookSql)){
			if($MBList->mbookorder == 1){ 
				$abstmbno = $MBList->mbno; //echo "1 = ".$abstmbno."<br/>";
				$Page = $MBList->startpage;
			}else{
				$SelectMB 		= $MBList->mbno; 
				$SelectMBPage 	= $MBList->startpage;
				if($SelectMBPage != ''){
					array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
					array_push($NextMBPageList,$SelectMBPage);
				}
			}
		}
	}
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
	<link rel="stylesheet" href="css/tooltip.css" />
<script type="text/javascript" language="javascript">
	function printBook(){
		window.print();
	}
	function goBack(){
		url = "PartpaymentAbstractPrintGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.label{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
}
.labelprint{
	font-size:11px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
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
	/*color:#BF0602;*/
	/*color:#921601;*/
	border: 1px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
	padding:4px 5px 4px 3px;
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
.label{
	color:#000000;
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
	<?php 
	//$Line=0;
	$title = '<table width="1058px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
			<tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Part - Payment Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
			</table>';
	echo $title;
	$table = $table . "<table width='1058px' bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
	$table = $table . "<tr>";
	$table = $table . "<td width='17%' class='' valign='middle'>Name of work</td>";
	$table = $table . "<td width='43%' style='word-wrap:break-word' class='' valign='middle'>" .$work_name."</td>";
	$table = $table . "<td width='18%' class='' valign='middle'>Name of the contractor</td>";
	$table = $table . "<td width='22%' class='' colspan='3' valign='middle'>" . $name_contractor . "</td>";
	$table = $table . "</tr>";
	$table = $table . "<tr>";
	$table = $table . "<td class='' valign='middle'>Technical Sanction No.</td>";
	$table = $table . "<td class='' valign='middle'>" . $tech_sanction . "</td>";
	$table = $table . "<td class='' valign='middle'>Agreement No.</td>";
	$table = $table . "<td class='' colspan='3' valign='middle'>" . $agree_no . "</td>";
	$table = $table . "</tr>";
	$table = $table . "<tr>";
	$table = $table . "<td class='' valign='middle'>Work order No.</td>";
	$table = $table . "<td class='' valign='middle'>" . $work_order_no . "</td>";
	$table = $table . "<td class='' valign='middle'>Running Account bill No. </td>";
	$table = $table . "<td class='' valign='middle'>" . $runn_acc_bill_no . $RabText. "</td>";
	$table = $table . "<td class='' align='right' valign='middle'>CC No. </td>";
	$table = $table . "<td class='' valign='middle'>" . $ccno . "</td>";
	$table = $table . "</tr>";
	$table = $table . "</table>";
							
	$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
	$tablehead = $tablehead . "<td align='center' class='' rowspan='2' valign='middle'>Item No.</td>";
	$tablehead = $tablehead . "<td align='center' class='' rowspan='2' valign='middle'>Rate</td>";
	$tablehead = $tablehead . "<td align='center' class='' rowspan='2' valign='middle'>Page / MB</td>";
	$tablehead = $tablehead . "<td align='center' class='' rowspan='2' valign='middle'>From RAB</td>";
	$tablehead = $tablehead . "<td align='center' class='' rowspan='2' valign='middle'>Qty</td>";
	$tablehead = $tablehead . "<td align='center' class='' colspan='3' valign='middle'>Previous  Payment Details</td>";
	$tablehead = $tablehead . "<td align='center' class='' colspan='3' valign='middle'>Current  Payment Details</td>";
	$tablehead = $tablehead . "</tr>";
	$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Quantity</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>( % )</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
	//$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Paid RAB</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Quantity</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>( % )</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
	//$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Paid RAB</td>";
	$tablehead = $tablehead . "</tr>";
	echo $table;
	$Line = $Line+2;
?>
<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1 labeldisplay' bgcolor="#FFFFFF" id="table1">
<?php 
	echo $tablehead; 
	/// GET ALL ITEM ID INVOLVED IN QTY SPLIT PART PAYMENT
	$PPayItemIdArr = array();
	$SelectPartpaymentQuery = "select distinct subdivid from pp_qty_splt where sheetid = '$sheetid' and percent != '0' order by subdivid asc";
	$SelectPartpaymentSql 	= mysql_query($SelectPartpaymentQuery);
	if($SelectPartpaymentSql == true){
		if(mysql_num_rows($SelectPartpaymentSql)>0){
			while($PPayItemIdList = mysql_fetch_object($SelectPartpaymentSql)){
				$PPayItemId = $PPayItemIdList->subdivid;
				array_push($PPayItemIdArr,$PPayItemId);
			}
		}
	}
	/// EXECUTE THE PROCESS OF EACH ITEM 
	foreach($PPayItemIdArr as $PPayArrKey => $PPayArrValue){
		$PPayItemId 	= $PPayArrValue; $ExCnt = 1; $ParentArr = array(); $ChildArr = array(); $OnlySLmPaidArr = array();
		$MasterRowSpanCount = 0;
		$DPMRowSpanCount = 0;
		$SLMRowSpanCount = 0;
		$Temp1 = 0;
		/// GET THE MASTER DATA OF ITEM
		$SelectSoqQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$PPayItemId'";
		$SelectSoqSql   = mysql_query($SelectSoqQuery);
		if($SelectSoqSql == true){
			if(mysql_num_rows($SelectSoqSql)>0){ 
				$SOQList 		= mysql_fetch_object($SelectSoqSql);
				$SOQDecimal 	= $SOQList->decimal_placed;
				$SOQRate 		= $SOQList->rate;
				$SOQUnit 		= $SOQList->per;
				$SOQItemNo 		= $SOQList->sno;
			}
		}
		$SelectAbstBookQuery = ""; $SelectAbstBookQuery2 = ""; $MainAbstMbNo = ""; $MainAbstMbpage = "";
		$SelectAbstBookQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId' and rbn = '$rbn'";
		$SelectAbstBookSql   = mysql_query($SelectAbstBookQuery);
		if($SelectAbstBookSql == true){
			if(mysql_num_rows($SelectAbstBookSql)>0){ 
				$ABSTList 		= mysql_fetch_object($SelectAbstBookSql);
				$MainAbstMbNo 	= $ABSTList->abstmbookno;
				$MainAbstMbpage	= $ABSTList->abstmbpage;
			}
		}
		if(($MainAbstMbNo == "")&&($MainAbstMbpage == "")){
			$SelectAbstBookQuery2 = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$PPayItemId' order by measurementbookid desc limit 1";
			$SelectAbstBookSql2   = mysql_query($SelectAbstBookQuery2);
			if($SelectAbstBookSql2 == true){
				if(mysql_num_rows($SelectAbstBookSql2)>0){ 
					$ABSTList2 		= mysql_fetch_object($SelectAbstBookSql2);
					$MainAbstMbNo 	= $ABSTList2->abstmbookno;
					$MainAbstMbpage	= $ABSTList2->abstmbpage;
				}
			}
		}
		//echo $SelectAbstBookQuery;echo "<br/>";
		
		/// GET ALL GRAND PARENT ID INVOLVED IN RESPECTIVE ITEM ID
		$GRParIdArr = array(); //$GRParRowSpanArr = array(); $DPMRowSpanArr = array(); $SLMRowSpanArr = array();
		$SelectParMBIdQuery = "select distinct gr_par_id from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId' order by ppid asc";
		$SelectParMBIdSql = mysql_query($SelectParMBIdQuery);
		if($SelectParMBIdSql == true){
			if(mysql_num_rows($SelectParMBIdSql)>0){
				while($GPIDList = mysql_fetch_object($SelectParMBIdSql)){
					$GPID = $GPIDList->gr_par_id;
					array_push($GRParIdArr,$GPID);
					
				}
			}
		}
		//// EXUCUTE THE PROCESS OF EACH PARENT ID 
		$IsparentFirst = 0; $DPMPaidArr = array(); $SLMPaidArr = array(); $ParChildArr = array();
		foreach($GRParIdArr as $GRParIdKey=>$GRParIdValue){
			// GET THE MASTER DATA OF PARENT ID FOR CLOSED RAB
			$MasterExist = 0;  $ParentRowSpanCount = 0;
			$SelectMasterQuery = "select * from measurementbook where measurementbookid = '$GRParIdValue'";
			$SelectMasterSql = mysql_query($SelectMasterQuery);
			if($SelectMasterSql == true){
				if(mysql_num_rows($SelectMasterSql)>0){
					$MasterExist = 1;
					$MasterList = mysql_fetch_object($SelectMasterSql);
					$MasterQty = $MasterList->mbtotal;
					$MasterPerc = $MasterList->pay_percent;
					$MasterRbn = $MasterList->rbn;
					$ParentArr[$GRParIdValue][0] = $MasterQty;
					$ParentArr[$GRParIdValue][1] = $MasterPerc; /// $ParentArr[$GRParIdValue][2] is Assigned for Rowspan kindly check upcoming lines
					$ParentArr[$GRParIdValue][3] = $MasterRbn;
				}
			}
			
			$ALLParIDArr = array(); $ALLChildIDArr = array(); $ONLYChildIDArr = array(); $AllDetailSArr = array();
			//// GET All the Part rate released deatils of the Grand Parent Id
			$DPMPPayExist = 0;
			$SelectALLIDQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId' and gr_par_id = '$GRParIdValue' and rbn < '$rbn' order by ppid asc";
			$SelectALLIDSql = mysql_query($SelectALLIDQuery);
			if($SelectALLIDSql == true){
				if(mysql_num_rows($SelectALLIDSql)>0){
					while($ALLIDList = mysql_fetch_object($SelectALLIDSql)){
						array_push($ALLParIDArr,$ALLIDList->gpmbid);
						array_push($ALLChildIDArr,$ALLIDList->rpmbid);
						array_push($AllDetailSArr,$ALLIDList->gpmbid);
						array_push($AllDetailSArr,$ALLIDList->ppid); 
						array_push($AllDetailSArr,$ALLIDList->rpmbid); 
						array_push($AllDetailSArr,$ALLIDList->qty); 
						array_push($AllDetailSArr,$ALLIDList->percent); 
						$DPMPPayExist++;
					}
				}
			}
			
			$SLMPPayExist = 0;
			if($DPMPPayExist == 0){
				$SelectALLIDQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId' and gr_par_id = '$GRParIdValue' and rbn = '$rbn' order by ppid asc";
				$SelectALLIDSql = mysql_query($SelectALLIDQuery);
				if($SelectALLIDSql == true){
					if(mysql_num_rows($SelectALLIDSql)>0){
						while($ALLIDList = mysql_fetch_object($SelectALLIDSql)){
							array_push($ALLParIDArr,$ALLIDList->gpmbid);
							array_push($ALLChildIDArr,$ALLIDList->rpmbid);
							array_push($AllDetailSArr,$ALLIDList->gpmbid);
							array_push($AllDetailSArr,$ALLIDList->ppid); 
							array_push($AllDetailSArr,$ALLIDList->rpmbid); 
							array_push($AllDetailSArr,$ALLIDList->qty); 
							array_push($AllDetailSArr,$ALLIDList->percent); 
							$DPMPPayExist++; $SLMPPayExist++; 
						}
					}
				}
			}
			$ParentArr[$GRParIdValue][4] = $SLMPPayExist;
			if($DPMPPayExist > 0){
				$ONLYChildIDArr = array_diff($ALLChildIDArr, $ALLParIDArr);
				//// NEED TO GET DPM ROWSPAN HERE
				$OnlyChildCnt 		= count($ONLYChildIDArr);
				foreach($ONLYChildIDArr as $OCKey => $OCValue){
					$Temp2 = 0;
					$PercArr 	= array(); $QtyArr = array();
					$Child 		= $OCValue;
					//// BELOW PERCENTAGE ASSIGN NEED TO CHANGE FOR ONLY SLM
					$PercArr[$OCValue] = $MasterPerc;//$GrParPercent;
					$z = 1; $temp = 0;
					if($SLMPPayExist > 0){ 
						$QtyArr[$OCValue] = $MasterQty;
					}else{
						while($z > 0){
							$z = 0;
							for($i=0; $i<count($AllDetailSArr); $i+=5){
								$LoopChild 	=  $AllDetailSArr[$i+2];
								$LoopQty 	=  $AllDetailSArr[$i+3];
								$LoopPerc 	=  $AllDetailSArr[$i+4];
								if($Child == $LoopChild){
									$PercArr[$OCValue] = $PercArr[$OCValue] + $LoopPerc;
									$Child 	=  $AllDetailSArr[$i+0];
									$z++;
									if($temp == 0){
										$QtyArr[$OCValue] = $LoopQty;
									}
									$temp++;
								}
							}
						}
					}
					$ParChildArr[$GRParIdValue][] = $OCValue;
					$DPMPaidArr[$GRParIdValue][$OCValue][0] = $QtyArr[$OCValue];
					$DPMPaidArr[$GRParIdValue][$OCValue][1] = $PercArr[$OCValue];
					
					$CurrPPayCount2 = 0;
					//// QUERY NEED TO CHANGE FOR ONLY SLM
					if($SLMPPayExist > 0){ //exit;
						$SelectCurrPPayQuery2 = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId' and gr_par_id = '$GRParIdValue' and rpmbid = '$OCValue' and rbn = '$rbn' order by ppid asc";
					}else{
						$SelectCurrPPayQuery2 = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId' and gr_par_id = '$GRParIdValue' and gpmbid = '$OCValue' and rbn = '$rbn' order by ppid asc";
					}
					$SelectCurrPPaySql2 = mysql_query($SelectCurrPPayQuery2);
					if($SelectCurrPPaySql2 == true){
						$CurrPPayCount2 = mysql_num_rows($SelectCurrPPaySql2);
					}
					if($CurrPPayCount2 == 0){
						$MasterRowSpanCount = $MasterRowSpanCount + 1;
						$ParentRowSpanCount = $ParentRowSpanCount + 1;
						$DPMPaidArr[$GRParIdValue][$OCValue][2] = 1;
					}else{
						$MasterRowSpanCount = $MasterRowSpanCount + $CurrPPayCount2;
						$ParentRowSpanCount = $ParentRowSpanCount + $CurrPPayCount2;
						$DPMPaidArr[$GRParIdValue][$OCValue][2] = $CurrPPayCount2;
					}
					
					if($CurrPPayCount2 > 0){
						while($CurrPPayList2 = mysql_fetch_object($SelectCurrPPaySql2)){ 
							$CurrPPayAmt = round(($CurrPPayList2->qty * $CurrPPayList2->percent * $ItemRate / 100),2); 
							$GPMBId2 = $CurrPPayList2->gpmbid;
							$RPMBId2 = $CurrPPayList2->rpmbid;
							$SLMPaidArr[$OCValue][] = $CurrPPayList2->qty;
							$SLMPaidArr[$OCValue][] = $CurrPPayList2->percent;
							$SLMRowSpanCount++;
						}
					}
				}
				$ParentArr[$GRParIdValue][2] = $ParentRowSpanCount;
			}
		}
		$Temp1 = 0; 
		
		///CHECK LINE AND PAGE BREAK HERE
		$LineTemp = $Line + $MasterRowSpanCount;
		if(($Line >= 25)||($LineTemp >= 25)){
			echo "</table>";
			echo "<div class='labelprint' align='center'>Page - ".$Page."</div>";
			$Page++;
			
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			if($Page > 100){ if($NextMBFlag == 0){ $NextMBOption = $NextMBOption + 1; $Page = 1; }else{ $UsedMBArr[$abstmbno][1] = $Page-1; $UsedMBArr[$abstmbno][2] = 0; $abstmbno = $NextMBList[$NextMbIncr]; $Page = $NextMBPageList[$NextMbIncr]; $NextMbIncr++; } }
			echo "<p style='page-break-after:always;padding:0px;'></p>";
			echo '<table width="1058px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
					<tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Part - Payment Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
				  </table>';
			echo $table;
			echo "<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1 labeldisplay' bgcolor='#FFFFFF' id='table1'>";
			echo $tablehead;
			$Line = $LineIncr + 2;
		}
		$Line = $Line + $MasterRowSpanCount;
		$LineTemp = 0;
		foreach($GRParIdArr as $PrintGRParIdKey=>$PrintGRParIdValue){
			$PrintGrParQty 		= $ParentArr[$PrintGRParIdValue][0];
			$PrintGrParPerc 	= $ParentArr[$PrintGRParIdValue][1];
			$PrintGrParRowSpan 	= $ParentArr[$PrintGRParIdValue][2];
			$PrintGrParRbn 		= $ParentArr[$PrintGRParIdValue][3];
			$PrintSLMOnlyExist 	= $ParentArr[$PrintGRParIdValue][4];
			$PrintDPmParArr 	= $ParChildArr[$PrintGRParIdValue];
			$Temp2 = 0;  $Temp3= 0;
			if(count($PrintDPmParArr)>0){
				foreach($PrintDPmParArr as $PrintDPmParKey=>$PrintDPmParValue){
					$PrintDPMChiQty 	= $DPMPaidArr[$PrintGRParIdValue][$PrintDPmParValue][0];
					$PrintDPMChiPerc 	= $DPMPaidArr[$PrintGRParIdValue][$PrintDPmParValue][1];
					$PrintDPMChiRowSpan = $DPMPaidArr[$PrintGRParIdValue][$PrintDPmParValue][2];
					if($Temp1 == 0){
						$TDStr1 = "<td align='center' valign='middle' rowspan='".$MasterRowSpanCount."'>".$SOQItemNo."</td><td align='right' valign='middle' rowspan='".$MasterRowSpanCount."'>".$SOQRate."</td><td align='center' valign='middle' rowspan='".$MasterRowSpanCount."'>".$MainAbstMbpage."/".$MainAbstMbNo."</td>";
					}else{
						$TDStr1 = "";
					}
					$Temp1++;
					
					if($Temp2 == 0){
						$TDStr2 = "<td align='center' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintGrParRbn."</td><td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintGrParQty."</td>";
					}else{
						$TDStr2 = "";
					}
					$Temp2++;
					
					$PrintSLMpaidArr 	= $SLMPaidArr[$PrintDPmParValue];
					$PrintSLMChiRowSpan = count($PrintSLMpaidArr)/2;
					$TDStr3 = ""; $TDStr4=""; 
					if($PrintSLMChiRowSpan <= 1){
						if($PrintSLMOnlyExist > 0){
							if($Temp3 == 0){
								$Amount1 = round(($PrintDPMChiQty*$PrintDPMChiPerc*$SOQRate/100),2);
								$TDStr3 = "<td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintDPMChiQty."</td><td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintDPMChiPerc."</td><td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".indian_money_format($Amount1)."</td>";
							}else{
								$TDStr3 = "";
							}
							$Temp3++;
							$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][0]*$SLMPaidArr[$PrintDPmParValue][1]*$SOQRate/100),2);
							$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][1]."</td><td align='right' valign='middle'>".indian_money_format($Amount2)."</td>";
						}else{
							$Amount1 = round(($PrintDPMChiQty*$PrintDPMChiPerc*$SOQRate/100),2);
							$TDStr3 = "<td align='right' valign='middle'>".$PrintDPMChiQty."</td><td align='right' valign='middle'>".$PrintDPMChiPerc."</td><td align='right' valign='middle'>".indian_money_format($Amount1)."</td>";
							$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][0]*$SLMPaidArr[$PrintDPmParValue][1]*$SOQRate/100),2);
							$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][1]."</td><td align='right' valign='middle'>".indian_money_format($Amount2)."</td>";
						}
						
						echo "<tr>".$TDStr1.$TDStr2.$TDStr3.$TDStr4."</tr>";
					}else{
						$i=0;
						for($z1=0; $z1<count($PrintSLMpaidArr); $z1+=2){
							if($i == 0){
								$Amount1 = round(($PrintDPMChiQty*$PrintDPMChiPerc*$SOQRate/100),2);
								$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][$z1+0]*$SLMPaidArr[$PrintDPmParValue][$z1+1]*$SOQRate/100),2);
								$TDStr3 = "<td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$PrintDPMChiQty."</td><td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$PrintDPMChiPerc."</td><td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".indian_money_format($Amount1)."</td>";
								$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+1]."</td><td align='right' valign='middle'>".indian_money_format($Amount2)."</td>";
							}else{
								$Amount = round(($SLMPaidArr[$PrintDPmParValue][$z1+0]*$SLMPaidArr[$PrintDPmParValue][$z1+1]*$SOQRate/100),2);
								$TDStr1 = "";
								$TDStr2 = "";
								$TDStr3 = "";
								$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+1]."</td><td align='right' valign='middle'>".indian_money_format($Amount)."</td>";
							}
							echo "<tr>".$TDStr1.$TDStr2.$TDStr3.$TDStr4."</tr>";
							$i++;
						}
					}
				}
			}
		}
		//$UpdateMbookRefQuery = "update pp_qty_splt set mbookno = '$abstmbno', page = '$Page' where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$PPayItemId'";
		//$UpdateMbookRefSql = mysql_query($UpdateMbookRefQuery);
		//echo $UpdateMbookRefQuery."<br/>";
	}
	/*if(count($PPayItemIdArr) > 0){
		$DeleteMyMbookQuery = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and genlevel = 'ppayabs' and mtype = 'PA'";
		$DeleteMyMbookSql 	= mysql_query($DeleteMyMbookQuery);
		if(($Startpage>0)&&($abstmbno != '')){
			$InsertMyMbookQuery = "insert into mymbook set mbno = '$abstmbno', startpage = '$Startpage', endpage = '$Page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'PA', zone_id = '$zone_id', genlevel = 'ppayabs', mbookorder = 1, active = 1, generatedate = NOW()";
			$InsertMyMbookSql 	= mysql_query($InsertMyMbookQuery);
		}
	}*/
	?>
	</table>
	<div class='labelprint' align='center'>Page - <?php echo $Page; ?></div>
	</br>
	</br>
	<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
		<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
		</div>
		<div class="buttonsection">
			<input type="button" name="print" value="Print" id="print" class="backbutton" onClick="printBook();" /> 
		</div>
	</div>
<!--==============================footer=================================-->
</body>
</html>

