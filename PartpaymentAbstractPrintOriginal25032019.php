<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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
if(isset($_POST['btn_view']) == ' View '){
	$sheetid 		= $_POST['cmb_work_no'];
	$rbn 			= $_POST['txt_rbn'];
	/*$mbookno 		= $_POST['txt_mbookno'];
	$mbookpageno 	= $_POST['txt_mbook_page_no'];*/
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
		$LineIncr 				= 	$start_line1 + $start_line2 + 2 + 2; 
	}
	//echo $mbookpageno;exit;
	$SelectMBookQuery 	= "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and genlevel = 'ppayabs' and mtype = 'PA' and mbno != ''";
	$SelectMBookSql 	= mysql_query($SelectMBookQuery);
	if($SelectMBookSql == true){
		if(mysql_num_rows($SelectMBookSql)>0){
			$MBookList = mysql_fetch_object($SelectMBookSql);
			$mbookno = $MBookList->mbno;
			$mbookpageno = $MBookList->startpage;
		}
	}
}
$Line = $Line + $LineIncr;

$abstmbno 	 = $mbookno;
$Page = $mbookpageno;
$Startpage = $Page;

function GetPrevPaymentDetails($sheetid,$subdivid,$rate){
	$MasMBIdPArr1 = array(); $MasMBIdPArr2 = array(); $MasMBIdPArr2 = array(); $CurrPayDetailStr = "";
	$CurrPayDetailStr  = '<table class="table1" width="100%">';
	$BalPayDetailStr   = '<table class="table1" width="100%">';
	$SelectMasterQuery = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid'";
	$SelectMasterSql   = mysql_query($SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysql_num_rows($SelectMasterSql)>0){ 
			while($MasterList = mysql_fetch_object($SelectMasterSql)){
				$MasMBid 		= $MasterList->measurementbookid;
				$MasQty 		= $MasterList->mbtotal;
				$MasRbn 		= $MasterList->rbn;
				$MasPayPerc 	= $MasterList->pay_percent;
				$MasPPayFlag 	= $MasterList->part_pay_flag;
				$MasQtySplit 	= $MasterList->qty_split;
				$ExpMasPPayFlag = explode("*",$MasPPayFlag);
				$PartPayParId   = $ExpMasPPayFlag[2];
				/*if(($MasPPayFlag == 0)||($MasPPayFlag == 1)){
					$MasMBIdPArr1[$MasMBid] = $MasQty;
					$MasMBIdPArr2[$MasMBid] = $MasPayPerc;
				}else{
					if($MasQtySplit != 'Y'){
						$MasMBIdPArr2[$PartPayParId] = $MasMBIdPArr2[$PartPayParId] + $MasPayPerc;
					}
				}*/
				if(($MasPPayFlag == 0)||($MasPPayFlag == 1)){
					$MasMBIdPArr1[$MasMBid] = $MasQty;
					$MasMBIdPArr2[$MasMBid] = $MasPayPerc;
					$MasMBIdPArr3[$MasMBid] = $MasRbn;
				}else{
					if($MasQtySplit != 'Y'){
						$MasMBIdPArr2[$PartPayParId] = $MasMBIdPArr2[$PartPayParId] + $MasPayPerc;
						$MasMBIdPArr3[$MasMBid] = $MasRbn;
					}
				}
			}
		}
	}
	$MasMBIdPArr4 = array(); $MasMBIdPArr5 = array();
	foreach($MasMBIdPArr1 as $key => $value){
		$id 	= $key;
		$Qty 	= $value;
		$Perc 	= $MasMBIdPArr2[$id];
		$Rbn 	= $MasMBIdPArr3[$id];
		//if(in_array($Perc, $MasMBIdPArr4)){
			$MasMBIdPArr4[$Perc] = $MasMBIdPArr4[$Perc] + $Qty;
			$MasMBIdPArr5[$Perc] = $MasRbn;
		//}else{
			//$MasMBIdPArr4[$Perc] = $Qty;
			//$MasMBIdPArr5[$Perc] = $MasRbn;
		//}
		//$Test2 .= $id."##".$Qty."##".$Perc."##".$Rbn."##";
	}
	$Line = 0;
	foreach($MasMBIdPArr4 as $key1 => $value1){
		$Perc1 = $key1;
		$Qty1 = $value1;
		$Rbn1 = $MasMBIdPArr5[$key1];
		$Amount1 =  round(($Qty1 * $rate * $Perc1 / 100),2);
		$CurrPayDetailStr .= '<tr><td width="60px" align="center">'.$Rbn1.'</td><td width="60px" align="right">'.$Qty1.'</td><td width="60px" align="right">'.$Perc1.'</td><td width="60px" align="right">'.$Amount1.'</td></tr>';
		$BalancePerc = 100 - $Perc1;
		$BalanceAmount1 =  round(($Qty1 * $rate * $BalancePerc / 100),2);
		if($BalancePerc > 0){
			$BalPayDetailStr .= '<tr><td width="60px" align="right">'.$Qty1.'</td><td width="60px" align="right">'.$BalancePerc.'</td><td width="60px" align="right">'.$BalanceAmount1.'</td></tr>';
			$Line++;
		}
	
	}
	$BalPayDetailStr  .= '</table>';
	$CurrPayDetailStr .= '</table>';
	//$test = implode(",",$MasMBIdPArr1);
	$OutPut = $BalPayDetailStr."@*@".$CurrPayDetailStr."@*@".$Line;//."@*@".$test;
	//print_r($MasMBIdPArr4);
	return $OutPut;
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
	function printBook()
	{
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
	</style>
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
	/*color:#BF0602;*/
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
										<tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
										</table>';
							echo $title;
							$table = $table . "<table width='1058px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
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
							$tablehead = $tablehead . "<td  align='center' class='' width='70px' rowspan='2' valign='middle'>Item No.</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='80px' rowspan='2' valign='middle'>Rate</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='80px' rowspan='2' valign='middle'>Page / MB</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='4' valign='middle'>Current  Payment Details</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='4' valign='middle'>Previous Paid Details</td>";
							$tablehead = $tablehead . "<td  align='center' class='' width='180px' colspan='3' valign='middle'>Balance Details</td>";
							$tablehead = $tablehead . "</tr>";
							$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>RAB</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>RAB</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Quantity</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>( % )</td>";
							$tablehead = $tablehead . "<td width='60px' align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
							$tablehead = $tablehead . "</tr>";
							//$Line = $Line + 2;
							$PPayItemIdArr = array();
							$SelectPartpaymentQuery = "select distinct subdivid from pp_qty_splt where sheetid = '$sheetid'";
							$SelectPartpaymentSql = mysql_query($SelectPartpaymentQuery);
							if($SelectPartpaymentSql == true){
								if(mysql_num_rows($SelectPartpaymentSql)>0){
									while($PPayItemIdList = mysql_fetch_object($SelectPartpaymentSql)){
										$PPayItemId = $PPayItemIdList->subdivid;
										array_push($PPayItemIdArr,$PPayItemId);
									}
								}
							}
							//print_r($PPayItemIdArr);exit;
							?>
							
							<?php echo $table; ?>
							<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor="#FFFFFF" id="table1">
							<?php echo $tablehead; ?>
							<?php 
							foreach($PPayItemIdArr as $PPayArrKey => $PPayArrValue){
								if($Line>20){
									echo "<tr><td colspan='14' style='border-bottom:1px solid white;border-left:1px solid white;border-right:1px solid white;' class='labelprint' align='center'>Page ".$Page."</td></tr>";
									echo "</table>";
									$Page++;
									echo "<p style='page-break-after:always;padding:0px;'></p>";
									echo '<table width="1058px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
										 <tr style="border:none;" class="labelprint"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;&nbsp;</td></tr>
										 </table>';
									echo $table;
									echo "<table width='1058px' cellpadding='3' cellspacing='3' align='center' class='table1' bgcolor='#FFFFFF' id='table1'>";
									echo $tablehead;
									$Line = $LineIncr;
								}
								
								$PPayItemId = $PPayArrValue; $ExCnt = 1;
								$SelectSoqQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$PPayItemId'";
								$SelectSoqSql   = mysql_query($SelectSoqQuery);
								if($SelectSoqSql == true){
									if(mysql_num_rows($SelectSoqSql)>0){ 
										$SOQList = mysql_fetch_object($SelectSoqSql);
										$SOQDecimal 	= $SOQList->decimal_placed;
										$SOQRate 		= $SOQList->rate;
										$SOQUnit 		= $SOQList->per;
										$SOQItemNo 		= $SOQList->sno;
									}
								}
								//echo $PPayItemId."<br/>";
								/*$SelectMasterQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId' and rbn = '$rbn'";
								$SelectMasterSql   = mysql_query($SelectMasterQuery);
								if($SelectMasterSql == true){
									if(mysql_num_rows($SelectMasterSql)>0){ 
										while($MasterList = mysql_fetch_object($SelectMasterSql)){
											$MasMBid 		= $MasterList->measurementbookid;
											$MasAbstMbNo 	= $MasterList->abstmbookno;
											$MasAbstPage 	= $MasterList->abstmbpage;
											$MasQty 		= $MasterList->mbtotal;
											$MasPayPerc 	= $MasterList->pay_percent;
											$MasPPayFlag 	= $MasterList->part_pay_flag;
											$ExpMasPPayFlag = explode("*",$MasPPayFlag);
											$PartPayParId   = $ExpMasPPayFlag[2];
											
										}
									}
								}*/
								$SelectMasterQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$PPayItemId' and rbn = '$rbn'";
								$SelectMasterSql   = mysql_query($SelectMasterQuery);
								if($SelectMasterSql == true){
									if(mysql_num_rows($SelectMasterSql)>0){ 
										$MasterList = mysql_fetch_object($SelectMasterSql);
										$MasMBid 		= $MasterList->measurementbookid;
										$MasAbstMbNo 	= $MasterList->abstmbookno;
										$MasAbstPage 	= $MasterList->abstmbpage;
										$MasQty 		= $MasterList->mbtotal;
										$MasPayPerc 	= $MasterList->pay_percent;
										$MasPPayFlag 	= $MasterList->part_pay_flag;
										$ExpMasPPayFlag = explode("*",$MasPPayFlag);
										$PartPayParId   = $ExpMasPPayFlag[2];
									}
								}
								$DetailStr = ""; $TotalItemAmt = 0; $LineTemp1 = 0; $LineTemp2 = 0;  $MasPPIdArr = array();
								$SelectDetailQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$PPayItemId'";// and rbn = '$rbn'";
								$SelectDetailSql   = mysql_query($SelectDetailQuery);
								if($SelectDetailSql == true){
									if(mysql_num_rows($SelectDetailSql)>0){ 
										$CurrPayDetailStr = '<table class="table1" width="100%">';
										$PrevPayDetailStr = '<table class="table1" width="100%">';
										while($DetailList = mysql_fetch_object($SelectDetailSql)){
											$MasPPId 	= $DetailList->ppid;
											$MasMBid 	= $DetailList->mbid;
											$MasRbn 	= $DetailList->rbn;
											$MasQty 	= $DetailList->qty;
											$MasPercent = $DetailList->percent;
											$Masgpmbid 	= $DetailList->gpmbid;
											$Masrpmbid 	= $DetailList->rpmbid;
											$MasMbookno = $DetailList->mbookno;
											$MasPage 	= $DetailList->page;
											$MasAmount = round(($MasQty * $SOQRate * $MasPercent / 100),2);
											if($MasPercent != 0){
												if($MasRbn == $rbn){
													$CurrPayDetailStr .= '<tr><td width="60px" align="center">'.$rbn.'</td><td width="60px" align="right">'.$MasQty.'</td><td width="60px" align="right">'.$MasPercent.'</td><td width="60px" align="right">'.$MasAmount.'</td></tr>';
													$LineTemp1++;
												}else{
													$PrevPayDetailStr .= '<tr><td width="60px" align="center">'.$rbn.'</td><td width="60px" align="right">'.$MasQty.'</td><td width="60px" align="right">'.$MasPercent.'</td><td width="60px" align="right">'.$MasAmount.'</td></tr>';
													$LineTemp2++;
												}
											}
											$TotalItemAmt = $TotalItemAmt + $MasAmount; 
											if($MasRbn == $rbn){
												array_push($MasPPIdArr,$MasPPId);
											}
										}
										
										$CurrPayDetailStr.= '</table>';
										$PrevPayDetailStr.= '</table>';
									}
								}
								$PaymentStr = GetPrevPaymentDetails($sheetid,$PPayItemId,$SOQRate);
								$ExpPaymentStr = explode("@*@",$PaymentStr);
								$BalancePaymentStr 	= $ExpPaymentStr[0];
								$PrevPaymentStr 	= $ExpPaymentStr[1];
								$LineTemp3 			= $ExpPaymentStr[2];
								
								if(($LineTemp1 > $LineTemp2)&&($LineTemp1 > $LineTemp3)){
									$Line = $Line + $LineTemp1; //echo $Line;
								}else if(($LineTemp2 > $LineTemp1)&&($LineTemp2 > $LineTemp3)){
									$Line = $Line + $LineTemp2; //echo $Line;
								}else{
									$Line = $Line + $LineTemp3; //echo $Line;
								}
								
								/*foreach($MasPPIdArr as $PPIdKey => $PPIdValue){
									$UpdateQuery = "update pp_qty_splt set mbookno = '$abstmbno', page = '$Page' where ppid = '$PPIdValue' and sheetid = '$sheetid'";
									$UpdateSql = mysql_query($UpdateQuery);
								}*/
							?>
								<tr border='1' class="labelprint" bgcolor="">
									<td  align='center' valign='middle'><?php echo $SOQItemNo;  ?></td>
									<td  align='right' valign='middle'><?php echo $SOQRate; ?></td>
									<td  align='center' valign='middle'><?php if($MasAbstMbNo != ''){ echo $MasAbstPage."/".$MasAbstMbNo; } ?></td>
									<td  align='center' colspan="4" style="padding:0px;">
									<?php echo $CurrPayDetailStr; ?>
									</td>
									<td  align='center' colspan="4" style="padding:0px;">
									<?php echo $PrevPaymentStr; ?>
									</td>
									<td  align='right' colspan="3" style="padding:0px;">
									<?php echo $BalancePaymentStr; ?>
									</td>
								</tr>
								<?php //$Line++; ?>
								<tr border='1' class="label" bgcolor="">
									<td  align='center' valign='middle'><?php //echo $Line; ?></td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle' colspan="3">TOTAL</td>
									<td  align='right' valign='middle'><?php echo $TotalItemAmt; ?></td>
									<td  align='center' valign='middle' colspan="3">&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
									<td  align='center' valign='middle' colspan="2">&nbsp;</td>
									<td  align='center' valign='middle'>&nbsp;</td>
								</tr>
								<?php $Line++; ?>
							<?php
							} 
							echo "<tr><td colspan='14' style='border-bottom:1px solid white;border-left:1px solid white;border-right:1px solid white;' class='labelprint' align='center'>Page ".$Page."</td></tr>";
							$Engpage = $Page;
							
							/*$DeleteMyMbookQuery = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and genlevel = 'ppayabs' and mtype = 'PA'";
							$DeleteMyMbookSql 	= mysql_query($DeleteMyMbookQuery);
							if(($Startpage>0)&&($Engpage>0)&&($abstmbno != '')){
								$InsertMyMbookQuery = "insert into mymbook set mbno = '$abstmbno', startpage = '$Startpage', endpage = '$Engpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'PA', zone_id = '$zone_id', genlevel = 'ppayabs', mbookorder = 1, active = 1, generatedate = NOW()";
								$InsertMyMbookSql 	= mysql_query($InsertMyMbookQuery);
							}*/
							?>
							</table>
							</br>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
								</div>
								<!--<div class="buttonsection">
								<input type="submit" class="btn" value=" View " name="btn_view" id="btn_view"   />
								</div>-->
							</div>
<!--==============================footer=================================-->
</body>
</html>

