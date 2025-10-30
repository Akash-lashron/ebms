<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
$staffid 	= $_SESSION['sid'];
$userid 	= $_SESSION['userid'];
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMbIncr = 0; $UsedMBArr = array();
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
	$mbookno 		= $_POST['txt_mbookno'];
	$mbookpageno 	= $_POST['txt_mbook_page_no'];
	$UsedMBArr[$mbookno][0] = $mbookpageno;
}
/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
$NextMBFlag = 0; $NextMBList = array(); $NextMBPageList = array();
if($_POST["modal_btn_next_mb"] == " NEXT "){
	$sheetid 		= $_POST['cmb_work_no'];
	$rbn 			= $_POST['txt_rbn'];
	$mbookno 		= $_POST['txt_mbookno'];
	$mbookpageno 	= $_POST['txt_mbook_page_no'];
	$NextMBFlag 	= 1;
	$TotalNoList 	= $_POST['txt_no']; 
	$UsedMBArr[$mbookno][0] = $mbookpageno;
	rsort($TotalNoList);
	foreach($TotalNoList as $NoKey => $NoValue){ 
		//$UsedMBArr[$MBStartVal][0] = $NextMBPageList[$MBStartKey];
		$SelectMB 		= $_POST['txt_next_mb'.$NoValue]; 
		$SelectMBPage 	= $_POST['txt_next_mbpage'.$NoValue];
		if($SelectMBPage != ''){
			array_push($NextMBList,$SelectMB); //echo $SelectMBPage."SS<br/>";
			array_push($NextMBPageList,$SelectMBPage);
			$UsedMBArr[$SelectMB][0] = $SelectMBPage;
		}
		
	}
}
//echo $start_line2;//exit;
$abstmbno 	= $mbookno;
$Page 		= $mbookpageno;
$Startpage 	= $Page;
//echo $Page;
if($sheetid != ''){
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
}
$Line = $Line + $LineIncr;
/*echo $sheetid;
print_r($NextMBList);
print_r($NextMBPageList);
exit;*/
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
		url = "PartpaymentAbstractGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.label{
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#0B29B9;
	}
	.labelprint{
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#0B29B9;
	}
	.table1 td{
		padding:1px 3px 1px 3px;
		font-size:12px;
	}
	.modal-body {
		font-size: 12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		color:#002D95;
		font-weight:600;
	}
	.mbpgdiv{
		padding:5px;
		border:1px solid #EBEBEB;
	}
	.mbtxt{
		padding:1px 5px 1px 5px;
		width:30px;
		border:1px solid #4096FF;
		text-align:right
	}
	.modal-btn-n{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-n:hover{
		background:#0048CE;
		border:1px solid #0048CE;
	}
	.modal-btn-c{
		padding:8px;
		border:1px solid #F1015B;
		background:#F1015B;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-btn-c:hover{
		background:#D5004A;
		border:1px solid #D5004A;
	}
	.bootstrap-dialog-footer-buttons > .btn-default{
		padding:8px;
		border:1px solid #0156E4;
		background:#0156E4;
		color:#FFFFFF;
		cursor:pointer;
		letter-spacing:1px;
		font-weight:bold;
		font-size:12px;
	}
	.modal-header h4{
		padding-top:0px;
		color:#FFFFFF;
	}
	.modal-btn-n{
		background:#006AD5 !important;
		border:1px solid #006AD5 !important;
		padding: 8px !important;
		color: #FFFFFF !important;
		cursor: pointer !important;
		letter-spacing: 1px !important;
		font-weight: bold !important;
		font-size: 12px !important;
		-moz-box-shadow: rgba(0,0,0,0) 0 0px 0 !important;
		box-shadow: rgba(0,0,0,0) 0 0px 0 !important;
		text-shadow: rgba(0,0,0,0) 0 0px 0 !important;
		border-radius: 0px !important;
	}
	.modal-btn-n:hover{
		background:#0057AE !important;
		border:1px solid #0057AE !important;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<!--==============================header=================================-->
	<?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
	<div class="content">
    	<div class="title">Part-Payment Generate</div>
        <div class="container_12">
        	<div class="grid_12">
            	<blockquote class="bq1" style="overflow-y:auto">
                	<form name="form" method="post" action="PartpaymentAbstract.php">
						<div class="container" align="center">
							<br/>
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
	$tablehead = $tablehead . "<td align='center' class='' colspan='4' valign='middle'>Previous  Payment Details</td>";
	$tablehead = $tablehead . "<td align='center' class='' colspan='4' valign='middle'>Current  Payment Details</td>";
	$tablehead = $tablehead . "</tr>";
	$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Quantity</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>( % )</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Paid RAB</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Quantity</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>( % )</td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px; padding-top:7px;'></td>";
	$tablehead = $tablehead . "<td align='center' class='' valign='middle'>Paid RAB</td>";
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
			//print_r($UsedMBArr); echo "HI";exit;
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
								$TDStr3 = "<td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintDPMChiQty."</td><td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$PrintDPMChiPerc."</td><td align='right' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$Amount1."</td><td align='center' valign='middle' rowspan='".$PrintGrParRowSpan."'>".$LoopChildRbn."</td>";
							}else{
								$TDStr3 = "";
							}
							$Temp3++;
							$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][0]*$SLMPaidArr[$PrintDPmParValue][1]*$SOQRate/100),2);
							$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][1]."</td><td align='right' valign='middle'>".$Amount2."</td><td align='center' valign='middle'>".$LoopChildRbn."</td>";
						}else{
							$Amount1 = round(($PrintDPMChiQty*$PrintDPMChiPerc*$SOQRate/100),2);
							$TDStr3 = "<td align='right' valign='middle'>".$PrintDPMChiQty."</td><td align='right' valign='middle'>".$PrintDPMChiPerc."</td><td align='right' valign='middle'>".$Amount1."</td><td align='center' valign='middle'>".$LoopChildRbn."</td>";
							$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][0]*$SLMPaidArr[$PrintDPmParValue][1]*$SOQRate/100),2);
							$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][1]."</td><td align='right' valign='middle'>".$Amount2."</td><td align='center' valign='middle'>".$LoopChildRbn."</td>";
						}
						
						echo "<tr>".$TDStr1.$TDStr2.$TDStr3.$TDStr4."</tr>";
					}else{
						$i=0;
						for($z1=0; $z1<count($PrintSLMpaidArr); $z1+=2){
							if($i == 0){
								$Amount1 = round(($PrintDPMChiQty*$PrintDPMChiPerc*$SOQRate/100),2);
								$Amount2 = round(($SLMPaidArr[$PrintDPmParValue][$z1+0]*$SLMPaidArr[$PrintDPmParValue][$z1+1]*$SOQRate/100),2);
								$TDStr3 = "<td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$PrintDPMChiQty."</td><td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$PrintDPMChiPerc."</td><td align='right' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$Amount1."</td><td align='center' valign='middle' rowspan='".$PrintDPMChiRowSpan."'>".$LoopChildRbn."</td>";
								$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+1]."</td><td align='right' valign='middle'>".$Amount2."</td><td align='center' valign='middle'>".$LoopChildRbn."</td>";
							}else{
								$Amount = round(($SLMPaidArr[$PrintDPmParValue][$z1+0]*$SLMPaidArr[$PrintDPmParValue][$z1+1]*$SOQRate/100),2);
								$TDStr1 = "";
								$TDStr2 = "";
								$TDStr3 = "";
								$TDStr4 = "<td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+0]."</td><td align='right' valign='middle'>".$SLMPaidArr[$PrintDPmParValue][$z1+1]."</td><td align='right' valign='middle'>".$Amount."</td><td align='center' valign='middle'>".$LoopChildRbn."</td>";
							}
							echo "<tr>".$TDStr1.$TDStr2.$TDStr3.$TDStr4."</tr>";
							$i++;
						}
					}
				}
			}
		}
		$UpdateMbookRefQuery = "update pp_qty_splt set mbookno = '$abstmbno', page = '$Page' where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$PPayItemId'";
		$UpdateMbookRefSql = mysql_query($UpdateMbookRefQuery);
		
		$UpdatePPayMbookRefQuery = "update measurementbook_temp set ppay_abst_mb_no = '$abstmbno', ppay_abst_mb_pg = '$Page' where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$PPayItemId'";
		$UpdatePPayMbookRefSql   = mysql_query($UpdatePPayMbookRefQuery);
		//echo $UpdateMbookRefQuery."<br/>";
	}
	$UsedMBArr[$abstmbno][1] = $Page;
	$UsedMBArr[$abstmbno][2] = 1;
	//print_r($UsedMBArr);exit;
	if(count($PPayItemIdArr) > 0){
		$DeleteMyMbookQuery = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and genlevel = 'ppayabs' and mtype = 'PA'";
		$DeleteMyMbookSql 	= mysql_query($DeleteMyMbookQuery);
		if(($Startpage>0)&&($abstmbno != '')){
		
			//$InsertMyMbookQuery = "insert into mymbook set mbno = '$abstmbno', startpage = '$Startpage', endpage = '$Page', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'PA', zone_id = '$zone_id', genlevel = 'ppayabs', mbookorder = 1, active = 1, generatedate = NOW()";
			//$InsertMyMbookSql 	= mysql_query($InsertMyMbookQuery);
			/////////// THIS IS FOR MULTIPLE MB SELECT CHANGE ON JUNE 2019 //////////
			$MBord = 1;
			//print_r($UsedMBArr);
			foreach($UsedMBArr as $UsedMB => $UsedMbDet){
				$UsedMBStartpage = $UsedMbDet[0];
				$UsedMBEndpage 	 = $UsedMbDet[1];
				$UsedMBStatus 	 = $UsedMbDet[2];
				if(($UsedMBStartpage != '')&&($UsedMBEndpage != '')){
					//echo $UsedMB." = ".$UsedMBStartpage." = ".$UsedMBEndpage." = ".$UsedMBStatus."<br/>";
					$insert_mymbook_sql2 = "insert into mymbook set mbno = '$UsedMB', startpage = '$UsedMBStartpage', endpage = '$UsedMBEndpage', rbn = '$rbn', sheetid = '$sheetid', staffid = '$staffid', mtype = 'PA', genlevel = 'ppayabs', mbookorder = '$MBord', active = 1, gen_version = '$GenVersion', generatedate = NOW()";
					$insert_mymbook_query2 = mysql_query($insert_mymbook_sql2);
					//echo $insert_mymbook_sql2."<br/>";
					$MBord++;
				}
			}
		}
	}
	?>
	</table>
	<div class='labelprint' align='center'>Page - <?php echo $Page; ?></div>
	</br>
	
	<div class="modal fade" id="myModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title">Part Payment Abstract MBook List</h4>
		  </div>
		  <div class="modal-body" style="min-height:150px">
		   <div style="width:55%; display:inline; float:left;">
			 <div style="height:20px">
				Click below box to select multiple Mbooks :
			 </div>
			 <div>
				<select id="NextMB" class="NextMB" multiple="multiple" style="width:400px;">
				   <option value=""> ------ Select Next MBook Nos ------</option>
				   <?php echo $objBind->BindNextMBlist($sheetid,'PA',$abstmbno); ?>
				</select>
				<br/><br/><br/>
				<div style="color:#FB133C; font-size:12px; font-weight:normal">* To select more than one mbook click again the above text box</div>
				<div style="color:#FB133C" align="center"><br />OR</div>
				<div style="color:#FB133C; font-size:12px; font-weight:normal"><br />* Press [Ctrl key] and Click the above text box </div>
			 </div>
			</div>
			<div style="width:30%; display:inline; float:right">
				<div class="mbpgdiv" style="height:18px; color:#009ED2">&nbsp;Selected MBook No. & Page No.</div>
				<div id="MBPageRefSec"></div>
			</div>
		  </div>
	
		  <div class="modal-footer" style="text-align:center !important;">
			<button type="button" class="modal-btn-c" data-dismiss="modal">CLOSE</button>
			<input type="submit" name="modal_btn_next_mb" id="modal_btn_next_mb" class="modal-btn-n" value=" NEXT " />
		  </div>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	
     					</div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
							<input type="hidden" name="cmb_work_no" id="cmb_work_no" value="<?php echo $sheetid; ?>">
							<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>">
							<input type="hidden" name="txt_mbookno" id="txt_mbookno" value="<?php echo $mbookno; ?>">
							<input type="hidden" name="txt_mbook_page_no" id="txt_mbook_page_no" value="<?php echo $mbookpageno; ?>">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
							</div>
						</div>
     				</form>
    			</blockquote>
   			</div>
  		</div>
 	</div>
<?php if($NextMBOption > 0){ ?>
	<script>
		var NoOfMB = "<?php echo $NextMBOption; ?>";
		BootstrapDialog.alert("You need to select next "+NoOfMB+" MBook to generate Abstract");
		$('#myModal').modal({backdrop:'static', keyboard:true, show:true});
		$(function(){
			$('#NextMB').change(function(event){ 
				var sheetid 		= 	$("#txt_sheet_id").val();
				var staffid			=	$("#txt_staffid").val();
				var rbn				=	$("#txt_rbn").val();
				var generatetype 	= 	"cw";
				$("#MBPageRefSec").html('');
				var x = 1;
				$.each($("#NextMB option:selected"), function(){   //alert($(this).text())
					var mbid 			= 	 $(this).val();//$("#NextMB option:selected").attr('value');//alert(currentmbooknovalue);
					var mbno 			= 	 $(this).text();//$("#NextMB option:selected").text();
					if(mbid != ""){
						$.post("MBookNoService.php", {currentmbook: mbid, currentbmookname: mbno, sheetid: sheetid, generatetype: generatetype, staffid: staffid, currentrbn: rbn}, function (data) { //alert(data);
							var pageno = data;
							var OutStr = "<div class='mbpgdiv'>MBook No. <input type='text' name='txt_next_mb"+x+"' class='mbtxt' value='"+mbno+"' readonly=''> &nbsp;Page : <input type='text' name='txt_next_mbpage"+x+"' class='mbtxt' value='"+pageno+"' readonly=''><input type='hidden' name='txt_no[]' class='mbtxt' value='"+x+"' readonly=''></div>";
							$("#MBPageRefSec").append(OutStr);
							x++;
						});
					}
				});
				
			});
		});
	</script>
<?php } ?>
<script>
	$(".NextMB").chosen();
</script>
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
</body>
</html>

