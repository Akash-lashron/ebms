<?php
//session_start();
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
checkUser();
include "../library/common.php";
$staffid = $_SESSION['sid'];
$userid  = $_SESSION['userid'];

function dt_display($ddmmyyyy){
 $dt	= explode('-',$ddmmyyyy);
 $dd	= $dt[2];
 $mm	= $dt[1];
 $yy	= $dt[0];
 return $dd.'/'.$mm.'/'.$yy;
}

if((isset($_POST['btn_save']) == " Save ")||(isset($_POST['BalanceBtn']) != "")){ 
	$subdivid 	= $_POST['txt_subdivid'];
	$sheetid 	= $_POST['txt_sheetid'];
	$rbn 		= $_POST['txt_rbn'];
	$rate 		= $_POST['txt_rate'];
	$ResultRbn  = $_POST['txt_result'];
	$Remarks = $_POST['txt_remarks'];
	//$PPrbn 		= key($_POST['BalanceBtn']);
	//echo $_POST['BalanceBtn'];exit;
	include('PartPaymentSave.php');//exit;
	if(!isset($_POST['BalanceBtn'])){
		header('Location: ../AbstMBook_Partpay.php');
	}else{
		$PPrbn 	= key($_POST['BalanceBtn']);
	}
	//exit;
	//header('Location: ../AbstMBook_Partpay.php');
	//echo $PPrbn;exit;
	/*$SelectMeasDateQuery = "select DATE(min(fromdate)) as mindate, DATE(max(todate)) as maxdate from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' group by sheetid";
	$SelectMeasDateSql 	 = mysql_query($SelectMeasDateQuery);
	if($SelectMeasDateSql == true){
		if(mysql_num_rows($SelectMeasDateSql)>0){
			$MeasDateList = mysql_fetch_object($SelectMeasDateSql);
			$fromdate 	= $MeasDateList->mindate;
			$todate 	= $MeasDateList->maxdate;
		}
	}
	
	
	$DeleteSlmPartPayQuery1 = "DELETE FROM measurementbook_temp WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND subdivid = '$subdivid' AND part_pay_flag != '0' AND part_pay_flag != '1'";
	//echo $DeleteSlmPartPayQuery1;
	$DeleteDpmPartPaySql1 = mysql_query($DeleteSlmPartPayQuery1);
	
	$DeleteSlmPartPayQuery2 = "DELETE FROM pp_qty_splt WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND subdivid = '$subdivid'";
	//echo $DeleteSlmPartPayQuery2;
	$DeleteDpmPartPaySql2 = mysql_query($DeleteSlmPartPayQuery2);
	if($ResultRbn != ""){
		$ExpResRbn = explode("*",$ResultRbn);
		foreach($ExpResRbn as $RabValue){
			$CurrRbn = $RabValue;
			
			if($CurrRbn == $rbn){
				$SelectSLMQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid'";
				$SelectSLMSql 	 = mysql_query($SelectSLMQuery);
				if($SelectSLMSql == true){
					if(mysql_num_rows($SelectSLMSql)>0){
						$SLMList = mysql_fetch_object($SelectSLMSql);
					}
				}
				$DeleteSlmPartPayQuery = "DELETE FROM measurementbook_temp WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND subdivid = '$subdivid' AND (part_pay_flag = '0' OR part_pay_flag = '1')";
				$DeleteSlmPartPaySql = mysql_query($DeleteSlmPartPayQuery);
				
				
				$CurrParSlmQtyArr 	= $_POST['ParSlmAddQty'.$CurrRbn];
				$CurrParSlmPercArr 	= $_POST['ParSlmAddPerc'.$CurrRbn];
				$CurrParSlmAmtArr 	= $_POST['ParSlmAddAmount'.$CurrRbn];
				$CurrParSlmMbIdArr 	= $_POST['ParSlmMbId'.$CurrRbn];
				
				$CurrChiSlmQtyArr 	= $_POST['ChiSlmAddQty'.$CurrRbn];
				$CurrChiSlmPercArr 	= $_POST['ChiSlmAddPerc'.$CurrRbn];
				$CurrChiSlmAmtArr 	= $_POST['ChiSlmAddAmount'.$CurrRbn];
				$CurrChiSlmMBidListArr 	= $_POST['ChiSlmMbIdList'.$CurrRbn];
				
				$CurrChiParSlmMbIdArr 	= $_POST['ChiParSlmMbId'.$CurrRbn];
				
				$ChildSlmQtyRow = count($CurrChiSlmQtyArr);
				if($ChildSlmQtyRow > 0){
					// Get and insert child Textbox
					foreach($CurrChiSlmQtyArr as $CurrChiSlmQtyKey => $CurrChiSlmQtyValue){ 
						$CurrChiSlmPerc 	= $CurrChiSlmPercArr[$CurrChiSlmQtyKey];
						$CurrChiSlmMBidList = $CurrChiSlmMBidListArr[$CurrChiSlmQtyKey];
						
						if($CurrChiSlmPerc == 100){ $PPaySlmflag = 0; }else{ $PPaySlmflag = 1; }
						$InsertPPaySlmChiQuery = "insert into measurementbook_temp set measurementbookdate = NOW(), staffid = '$staffid', sheetid = '$sheetid', divid = '$SLMList->divid', subdivid = '$subdivid', fromdate = '$fromdate', todate = '$todate', mbno = '$SLMList->mbno', mbpage = '$SLMList->mbpage', mbtotal = '$CurrChiSlmQtyValue', abstmbookno = '$SLMList->abstmbookno', abstmbpage = '$SLMList->abstmbpage', pay_percent = '$CurrChiSlmPerc', flag = '$SLMList->flag', part_pay_flag = '$PPaySlmflag', qty_split = '', rbn = '$rbn', active = 1, userid = '$userid', remarks = '$Remarks'";
						$InsertPPaySlmChiSql 	= mysql_query($InsertPPaySlmChiQuery);
						$SlmRunnParMbId			= mysql_insert_id();
						$ExpCurrChiSlmMBidList 	= explode(",",$CurrChiSlmMBidList);
						if(count($ExpCurrChiSlmMBidList)>0){
							foreach($ExpCurrChiSlmMBidList as $MBDtKey => $MBDtValue){
								$UpdatePercQuery 	= "update mbookdetail set curr_paid_perc = '$CurrChiSlmPerc', curr_parent_id = '$SlmRunnParMbId', curr_paid_rbn = '$rbn' where mbdetail_id  = '$MBDtValue'";
								$UpdatePercSql 		= mysql_query($UpdatePercQuery);
								//echo "SL = ".$UpdatePercQuery."<br/>";
							}
						}
						//echo $InsertPPaySlmChiQuery."<br/>";
					}
					//exit;
				}else{
					// Get and insert parent Textbox
					foreach($CurrParSlmQtyArr as $CurrParSlmQtyKey => $CurrParSlmQtyValue){
						$CurrParSlmPerc = $CurrParSlmPercArr[$CurrParSlmQtyKey];
						if($CurrParSlmPerc == 100){ $PPaySlmflag = 0; }else{ $PPaySlmflag = 1; }
						$InsertPPaySlmParQuery = "insert into measurementbook_temp set measurementbookdate = NOW(), staffid = '$staffid', sheetid = '$sheetid', divid = '$SLMList->divid', subdivid = '$subdivid', fromdate = '$fromdate', todate = '$todate', mbno = '$SLMList->mbno', mbpage = '$SLMList->mbpage', mbtotal = '$CurrParSlmQtyValue', abstmbookno = '$SLMList->abstmbookno', abstmbpage = '$SLMList->abstmbpage', pay_percent = '$CurrParSlmPerc', flag = '$SLMList->flag', part_pay_flag = '$PPaySlmflag', qty_split = '', rbn = '$rbn', active = 1, userid = '$userid', remarks = '$Remarks'";
						$InsertPPaySlmParSql 	= mysql_query($InsertPPaySlmParQuery);
						$SlmRunnParMbId			= mysql_insert_id();
						$UpdatePercQuery 	= "update mbookdetail a, mbookheader b set a.curr_paid_perc = '$CurrParSlmPerc', a.curr_parent_id = '$SlmRunnParMbId', a.curr_paid_rbn = '$rbn' where a.mbheaderid  = b.mbheaderid and b.date >= '$fromdate' and b.date <= '$todate' and b.sheetid = '$sheetid' and b.subdivid = '$subdivid'";
						$UpdatePercSql 		= mysql_query($UpdatePercQuery);
						//echo $UpdatePercQuery."<br/>";
						//echo $CurrParSlmPerc."<br/>";exit;
					}
				}
				//print_r($CurrChiSlmQtyArr);
				//exit;
			}else{ 
				////******************    Deduct Previous Measurements Partpayments Process Starts Here **************** /////
				$CurrParDpmQtyArr 	= $_POST['ParDpmAddQty'.$CurrRbn];
				$CurrParDpmPercArr 	= $_POST['ParDpmAddPerc'.$CurrRbn];
				$CurrParDpmAmtArr 	= $_POST['ParDpmAddAmount'.$CurrRbn];
				$CurrParDpmMbIdArr 	= $_POST['ParDpmMbId'.$CurrRbn];
				
				$CurrChiDpmQtyArr 	= $_POST['ChiDpmAddQty'.$CurrRbn];
				$CurrChiDpmPercArr 	= $_POST['ChiDpmAddPerc'.$CurrRbn];
				$CurrChiDpmAmtArr 	= $_POST['ChiDpmAddAmount'.$CurrRbn];
				$CurrChiParDpmMbIdArr 	= $_POST['ChiParDpmMbId'.$CurrRbn];
				
					$RunnParMbIdArr = array();
					foreach($CurrParDpmMbIdArr as $ParDpmMbIdKey => $ParDpmMbIdKeyValue){ 
						$CurrParDpmMbIdValue 	= $CurrParDpmMbIdArr[$ParDpmMbIdKey];
						$CurrParDpmPrecValue 	= $CurrParDpmPercArr[$ParDpmMbIdKey];
						$CurrChiDpmQtyArr 		= $_POST['ChiDpmAddQty'.$CurrRbn.'PID'.$CurrParDpmMbIdValue];
						$CurrChiDpmPercArr 		= $_POST['ChiDpmAddPerc'.$CurrRbn.'PID'.$CurrParDpmMbIdValue];
						$CurrChiDpmAmtArr 		= $_POST['ChiDpmAddAmount'.$CurrRbn.'PID'.$CurrParDpmMbIdValue];
						$CurrChiParDpmMbIdArr 	= $_POST['ChiParDpmMbId'.$CurrRbn.'PID'.$CurrParDpmMbIdValue];
						$CurrChiParDpmMbIdListArr 	= $_POST['ChiParDpmMbIdList'.$CurrRbn.'PID'.$CurrParDpmMbIdValue];
						$ChildQtyRow 			= count($CurrChiDpmQtyArr);
						
						$SelectPPayParQuery = "select * from measurementbook where sheetid = '$sheetid' and measurementbookid = '$CurrParDpmMbIdValue'";
						$SelectPPayParSql = mysql_query($SelectPPayParQuery);
						if($SelectPPayParSql == true){
							if(mysql_num_rows($SelectPPayParSql)>0){
								$MBIdList = mysql_fetch_object($SelectPPayParSql);
							}
						}
						if($ChildQtyRow == 0){
							$pay_percent = $CurrParDpmPrecValue;
						}else{
							$pay_percent = '';
						}
						if(($MBIdList->part_pay_flag == 1) || ($MBIdList->part_pay_flag == 0)){
							$PartPayFlag = $subdivid."*".$CurrRbn."*".$CurrParDpmMbIdValue;//$MBIdList->measurementbookid;
							$GrandparentId 	= $CurrParDpmMbIdValue;
						}else{
							$PartPayFlag 	= $MBIdList->part_pay_flag;//$subdivid."*".$CurrRbn."*".$CurrParDpmMbIdValue; //echo $PartPayFlag;exit;
							$ExpPartPayFlag = explode("*",$MBIdList->part_pay_flag);
							$GrandparentId 	= end($ExpPartPayFlag);
						}
						//$PartPayFlag 	= $MBIdList->part_pay_flag;//$subdivid."*".$CurrRbn."*".$CurrParDpmMbIdValue; //echo $PartPayFlag;exit;
						$InsertPPayParQuery = "insert into measurementbook_temp set measurementbookdate = NOW(), staffid = '$staffid', sheetid = '$sheetid', divid = '$MBIdList->divid', subdivid = '$subdivid', fromdate = '$fromdate', todate = '$todate', mbno = '$MBIdList->mbno', mbpage = '$MBIdList->mbpage', mbtotal = '$MBIdList->mbtotal', abstmbookno = '$MBIdList->abstmbookno', abstmbpage = '$MBIdList->abstmbpage', pay_percent = '$pay_percent', flag = '$MBIdList->flag', part_pay_flag = '$PartPayFlag', qty_split = 'Y', rbn = '$rbn', active = 1, userid = '$userid', remarks = '$Remarks'";
						$InsertPPayParSql 	= mysql_query($InsertPPayParQuery);
						//echo $InsertPPayParQuery."<br/>";
						$RunnParMbId		= mysql_insert_id();
						
						$RunnParMbIdArr[$CurrParDpmMbIdValue] = $RunnParMbId;
						if($ChildQtyRow > 0){
							foreach($CurrChiDpmQtyArr as $ChiDpmQtyKey => $ChiDpmQtyValue){
								$CurrChiDpmQtyValue 		= $ChiDpmQtyValue;
								$CurrChiDpmPercValue 		= $CurrChiDpmPercArr[$ChiDpmQtyKey];
								$CurrChiDpmAmtValue 		= $CurrChiDpmAmtArr[$ChiDpmQtyKey];
								$CurrChiParDpmMbId 			= $CurrChiParDpmMbIdArr[$ChiDpmQtyKey];
								$CurrChiParDpmMbIdStr 		= $CurrChiParDpmMbIdListArr[$ChiDpmQtyKey]; echo $CurrChiParDpmMbIdStr."<br/>";
								$CurrRunnParMbId 			= $RunnParMbId;//$RunnParMbIdArr[$CurrChiParDpmMbId];
								if($CurrChiDpmPercValue == 0){ $rpmbid = $CurrParDpmMbIdValue; }else{ $rpmbid = $RunnParMbId; } 
								//echo "HI".$rpmbid."<br/>";
								$InsertPPayQtySplitQuery 	= "insert into pp_qty_splt set mbid = '$CurrRunnParMbId', sheetid = '$sheetid', rbn = '$rbn', subdivid = '$subdivid', qty = '$CurrChiDpmQtyValue', percent = '$CurrChiDpmPercValue', rate = '$rate', gr_par_id = '$GrandparentId', gpmbid = '$CurrChiParDpmMbId', rpmbid = '$rpmbid', gppayid = '$gppayid', rppayid = '$rppayid', createddate = NOW(), staffid = '$staffid', userid = '$userid' ";
								$InsertPPayQtySplitSql 		= mysql_query($InsertPPayQtySplitQuery);
								$ExpCurrChiParDpmMbIdStr 	= explode(",",$CurrChiParDpmMbIdStr);
								if(count($ExpCurrChiParDpmMbIdStr)>0){
									foreach($ExpCurrChiParDpmMbIdStr as $MBDtKey => $MBDtValue){
										$UpdatePercQuery 	= "update mbookdetail set curr_paid_perc = '$CurrChiDpmPercValue', curr_parent_id = '$rpmbid', curr_paid_rbn = '$rbn' where mbdetail_id  = '$MBDtValue'";
										$UpdatePercSql 		= mysql_query($UpdatePercQuery);
										//echo "DP = ".$UpdatePercQuery."<br/>";
									}
								}
								//echo $InsertPPayQtySplitQuery."<br/>";
							}
						}
						
					}
				////Deduct Previous Measurements Partpayments Process Ends Here/////
			}
		}
	}*/
	//exit;
	//header('Location: ../AbstMBook_Partpay.php');
}

/*if(isset($_POST['BalanceBtn']) != ""){ 
	$subdivid 	= $_POST['txt_subdivid'];
	$sheetid 	= $_POST['txt_sheetid'];
	$rbn 		= $_POST['txt_rbn'];
	$PPrbn 		= key($_POST['BalanceBtn']);
	//echo $rbn;exit;
}*/
if((isset($_GET['subdivid'])!="")&&(isset($_GET['sheetid'])!="")&&(isset($_GET['rbn'])!="")){
	$subdivid 	= $_GET['subdivid'];
	$sheetid 	= $_GET['sheetid'];
	$rbn 		= $_GET['rbn'];
	$PPrbn = $rbn;
}
//echo $PPrbn;exit;

if(($subdivid != '')&&($sheetid != '')&&($rbn != '')&&($PPrbn != '')){
	$SLMRbn = $rbn; 
	$RABArr = array(); $RABDPMArr = array(); $RABDateArr = array();
	$RABCount = 0; $RABSLMCount = 0; $RABDPMCount = 0; 
	$SlmRowCnt1 = 0; $DpmRowCnt1 = 0; $SlmRowCnt2 = 0; $DpmRowCnt2 = 0; 
	$SelectDateQuery1 	= "select DATE(min(fromdate)) as mindate, DATE(max(todate)) as maxdate from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' group by sheetid";
	$SelectDateSql1 	= mysql_query($SelectDateQuery1);
	if($SelectDateSql1 == true){
		if(mysql_num_rows($SelectDateSql1)>0){ $SlmRowCnt1 = 1;
			$DateList1 = mysql_fetch_object($SelectDateSql1);
			array_push($RABArr,$rbn);
			$SlmFromDate = $DateList1->mindate;
			$SlmToDate = $DateList1->maxdate;
			$RABCount++; $RABSLMCount++;
		}
	}
	
	//$RABCount = 0; 
	$SelectRABQuery2 = "select distinct rbn, DATE(fromdate) as mindate, DATE(todate) as maxdate from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and part_pay_flag != 'DMY' order by rbn desc";// and rbn = '$rbn'";
	$SelectRABSql2 = mysql_query($SelectRABQuery2);
	if($SelectRABSql2 == true){
		if(mysql_num_rows($SelectRABSql2)>0){ $DpmRowCnt1 = 1;
			while($DateList2 = mysql_fetch_object($SelectRABSql2)){
				array_push($RABArr,$DateList2->rbn);
				array_push($RABDPMArr,$DateList2->rbn);
				$RABDateArr[$DateList2->rbn][0] = $DateList2->mindate;
				$RABDateArr[$DateList2->rbn][1] = $DateList2->maxdate;
				$RABCount++; $RABDPMCount++;
			}
		}
	}
	
	if($PPrbn == $rbn){
		$fromdate 	= $SlmFromDate;
		$todate 	= $SlmToDate;
	}else{
		$fromdate 	= $RABDateArr[$PPrbn][0];
		$todate 	= $RABDateArr[$PPrbn][1];
	}

	$SelectItemDescQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";// and rbn = '$rbn'";
	$SelectItemDescSql = mysql_query($SelectItemDescQuery);
	if($SelectItemDescSql == true){
		if(mysql_num_rows($SelectItemDescSql)>0){
			$ItemDescList = mysql_fetch_object($SelectItemDescSql);
			$Description = $ItemDescList->description;
			if($ItemDescList->shortnotes != ""){
				$Description = $ItemDescList->shortnotes;
			}
			$ItemNo		= $ItemDescList->sno;
			$ItemUnit 	= $ItemDescList->per;
			$Decimal 	= $ItemDescList->decimal_placed;
			$ItemRate 	= $ItemDescList->rate;
			$ItemType 	= $ItemDescList->measure_type;
			
			$AggrQty 	= $ItemDescList->total_quantity;
			$DevPrec 	= $ItemDescList->deviate_qty_percent;
			$DevQty 	= round(($AggrQty * $DevPrec / 100),$Decimal);
			$TotalAggrQty = round(($AggrQty + $DevQty),$Decimal);
		}
	}
	
	//$SelectDetailsQuery = "select a.*, b.* from ";
	$RABCount = count($RABArr);
	$PaidQty = 0;
	if($DpmRowCnt1 == 1){
		$SelectDpmPaidQuery = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and part_pay_flag != 'DMY' and pay_percent != '100'";
		$SelectDpmPaidSql 	= mysql_query($SelectDpmPaidQuery);
		if($SelectDpmPaidSql == true){
			if(mysql_num_rows($SelectDpmPaidSql)>0){
				$DpmRowCnt2 = 1;
			}
		}
		$SelectDpmPaidQtyQuery = "select sum(mbtotal) as PaidQty from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and (part_pay_flag = '0' OR part_pay_flag = '1')";
		$SelectDpmPaidQtySql   = mysql_query($SelectDpmPaidQtyQuery);
		if($SelectDpmPaidQtySql == true){
			if(mysql_num_rows($SelectDpmPaidQtySql)>0){
				$paidQtyList = mysql_fetch_object($SelectDpmPaidQtySql);
				$PaidQty = $paidQtyList->PaidQty;
			}
		}
		
	}
	//echo $PaidQty; exit;
	
	/*$SLMQtyArr = array(); $SLMItemQty = 0;
	$SelectSlmPaidQuery = "select * from mbookgenerate where sheetid = '$sheetid' and subdivid = '$subdivid'";
	$SelectSlmPaidSql 	= mysql_query($SelectSlmPaidQuery);
	if($SelectSlmPaidSql == true){
		if(mysql_num_rows($SelectSlmPaidSql)>0){
			$SlmRowCnt2 = 1;
			while($SlmQtyList = mysql_fetch_object($SelectSlmPaidSql)){
				array_push($SLMQtyArr,$SlmQtyList->mbtotal);
				$SLMItemQty = $SLMItemQty + $SlmQtyList->mbtotal;
			}
		}
	}*/
	$SLMQtyArr = array(); $SLMItemQty = 0; $SLMPercArr =array(); $SLMItemRowCnt = 0;
	$SelectSlmPaidQuery = "select * from measurementbook_temp where sheetid = '$sheetid' and subdivid = '$subdivid'";
	$SelectSlmPaidSql 	= mysql_query($SelectSlmPaidQuery);
	if($SelectSlmPaidSql == true){
		if(mysql_num_rows($SelectSlmPaidSql)>0){
			$SlmRowCnt2 = 1;
			while($SlmQtyList = mysql_fetch_object($SelectSlmPaidSql)){
				if(($SlmQtyList->part_pay_flag == 0)||($SlmQtyList->part_pay_flag == 1)){
					array_push($SLMQtyArr,$SlmQtyList->mbtotal);
					array_push($SLMPercArr,$SlmQtyList->pay_percent);
					$SLMItemQty = $SLMItemQty + $SlmQtyList->mbtotal;
					$SLMItemRowCnt++;
				}
			}
		}
	}
	if($SLMItemQty > 0){
		$SLMItemQty = round($SLMItemQty,$Decimal);
	}
	//$SLMTotalAmt = round(($SLMItemQty * $ItemRate),2);
}
if(isset($_POST['btn_back']) == " Back "){
	header('Location: ../AbstMBook_Partpay.php');
}
$TotalUsedQty = round(($SLMItemQty  + $PaidQty),$Decimal);
if($TotalUsedQty > $TotalAggrQty){
	$BalanceQty = 0;
	$AddQtyBeyDevLt = round(($TotalUsedQty - $TotalAggrQty),$Decimal);
}else{
	$AddQtyBeyDevLt = 0;
	$BalanceQty = round(($TotalAggrQty - $TotalUsedQty),$Decimal);
}

//echo $SLMItemRowCnt;exit;
$SlmRowCnt3 = 0;
if($SLMItemRowCnt > 0){
	$SelectSlmPaidQuery = "select * from mbookgenerate where sheetid = '$sheetid' and subdivid = '$subdivid'";
	$SelectSlmPaidSql 	= mysql_query($SelectSlmPaidQuery);
	if($SelectSlmPaidSql == true){
		if(mysql_num_rows($SelectSlmPaidSql)>0){
			$SlmRowCnt3 = 1;
		}
	}
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include("CSSLibrary.php"); ?>
</head>

<body class="mini-navbar">
<form style="height:100%;" method="post" action="PartPayment.php">
    <!-- Start Left menu area -->
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
               <strong><a href="index.html"><img src="img/logo/logosn.png" alt="" style="height:39px;"/></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
              <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1" style="margin-bottom:80px;">
						 <li>
							<a title="Deduct Previous Measurement" style="padding: 5px 2px;">
								<button type="button" class="btn btn-custon-four btn-default colorA1" style="color:#0551E5;">DPM</button>
							</a>
						</li>
						<?php if($RABDPMCount > 0){ $x1= 2; foreach($RABDPMArr as $RABKey=>$RABValue){ $ColorCls = "colorA".$x1; if($x1 == 10){ $x1 = 0; } $x1++; if($SLMRbn == $RABValue){ $ClsA = "active"; }else{ $ClsA = ""; } ?>
                        <li>
							<a title="RAB - <?= $RABValue; ?>">
								<button type="button" class="btn btn-custon-four btn-default <?php echo $ColorCls; ?> ClickDetails" style="color:#0551E5;" data-id="<?= $RABValue; ?>"><?= $RABValue; ?></button>
							</a>
							<div class="ViewDetails message scroll_container" id="View<?= $RABValue; ?>" style="border:none;">
								<div class="scroll_content" style="padding-top:10px">
								<i class="fa fa-times-circle CloseDetail" style="font-size:24px; float:right; margin-bottom:10px; cursor:pointer;"></i>
								<table class="table table-bordered tborder1" style="border:none;" id="MeasDetTable<?= $RABValue; ?>">
									<thead>
										<tr>
											<td style="vertical-align:middle">Date</td>
											<td style="vertical-align:middle">Item No</td>
											<td style="vertical-align:middle">Contents <br/>of Area</td>
											<td style="vertical-align:middle">Unit</td>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
								</div>
							</div>
						</li>
						<?php } } ?>
                    </ul>
					<ul>&nbsp;</ul>
                </nav>
            </div>
        </nav>
    </div>
    <!-- End Left menu area -->
    <!-- Start Welcome area -->
    <div class="all-content-wrapper" style="margin-right:80px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="img/logo/logo1.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-l">
                                        <div class="header-top-menu tabl-d-n col-md-12 padding-l">
                                            <ul class="nav navbar-nav mai-top-nav col-md-12 no-padding padding-l">
                                                <li class="nav-item col-md-12 no-padding-lr padding-l">
													<a class="nav-link" style="padding:14px 5px 20px 5px;">
														<!--Item No. -->
														<span class="badge col-md-11 desc-title" style="text-align:left">
															<span class="badge" style="background:#F5F8FA; color:#000;">&nbsp;Item No : <?php echo $ItemNo; ?>&nbsp;</span> <?php echo $Description; ?>
														</span>
														<span class="badge col-md-1" style="background:none; margin-top:-1px;">
															<span class="badge desc-title" style="background:#F5F8FA; color:#000; padding:0px 5px;">
																<font style="margin-top:1px; font-size:12px;">Qty Details <?php //echo $SLMItemQty; ?> <?php //echo $ItemUnit; ?>&nbsp; </font>
																<i class="fa fa-info-circle title-info" id="QtyInfo" data-AggrQty="<?= $AggrQty; ?>" data-DevPrec="<?= $DevPrec; ?>" data-DevQty="<?= $DevQty; ?>" data-TotalAggrQty="<?= $TotalAggrQty; ?>" data-PaidQty="<?= $PaidQty; ?>" data-SLMItemQty="<?= $SLMItemQty; ?>" data-TotalUsedQty = "<?= $TotalUsedQty; ?>" data-BalanceQty = "<?= $BalanceQty; ?>" data-AddQtyBeyDevLt = "<?= $AddQtyBeyDevLt; ?>"></i>
															</span> 
														</span>
														<!--<span class="badge" style="color:#013D85; background:#fff; font-size:13px;">
														<?php echo $SLMItemQty; ?> 
														<?php echo $ItemUnit; ?>
														<?php echo $Description; ?>
														</span>-->
													</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav">
                                        <li><a data-toggle="collapse" data-target="#Charts" href="#">Home <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                            <ul class="collapse dropdown-header-top">
                                                <li><a href="index.html">Dashboard v.1</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu end -->
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            &nbsp;
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
		
		
		
		
		
        <div class="mailbox-area mg-b-15" style="margin-bottom: 0px; padding-bottom:10px;">
            <div class="container-fluid">
                <div class="row">
					<div class="col-md-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px; padding-right:3px">
                        <div class="hpanel">
                            <div class="panel-body no-padding">
                                <div class="row">
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
                                            <button type="button" class="btn btn-default btn-sm active">Pending Payment in RAB's</button>
											<?php //if($RABCount > 0){ foreach($RABArr as $RABKey=>$RABValue){ if($RABValue == $rbn){ $BDtClass = ' active'; }else{ $BDtClass = ''; } ?>
											<?php $BDtSlno = 1;  $BDtFirstRAB = $rbn; if($RABCount > 0){ foreach($RABArr as $RABKey=>$RABValue){ if($PPrbn == $RABValue){ $BDtClass = ' active'; }else{ $BDtClass = ''; }  $BDtSlno++; ?>
											<button type="submit" name="BalanceBtn[<?= $RABValue; ?>]" class="btn btn-default btn-sm BalanceBtn BalanceBtn<?= $BDtClass; ?>" id="BalanceBtn<?= $RABValue; ?>" data-id="<?= $RABValue; ?>" value="<?= $RABValue; ?>"><?= $RABValue; ?></button>
											<?php } } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div style="height:5px;"></div>
							<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>">
							<input type="hidden" name="txt_bal1st_rbn" id="txt_bal1st_rbn" value="<?php echo $BDtFirstRAB; ?>">
							<input type="hidden" name="txt_decimal" id="txt_decimal" value="<?php echo $Decimal; ?>">
							<input type="hidden" name="txt_rate" id="txt_rate" value="<?php echo $ItemRate; ?>">
							<input type="hidden" name="txt_subdivid" id="txt_subdivid" value="<?php echo $subdivid; ?>">
							<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>">
							<input type="hidden" name="txt_item_type" id="txt_item_type" value="<?php echo $ItemType; ?>">
							
                        </div>
                    </div>
					<div class="col-md-8 col-md-8 col-sm-8 col-xs-8" style="padding-left:0px;">
						<div class="hpanel">
							<div class="panel-body no-padding">
								<div class="row">
									<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
										<div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15 col-md-12" style="padding-top:0px;">
											<div class="ib-tb BalanceDetails" id="BalanceDetails<?= $rbn; ?>">
											<?php 
											$RABValue = $PPrbn;
											$SelectDetailsQuery = "select a.date, b.mbdetail_id, b.descwork, b.measurement_contentarea, b.prev_parent_id, b.prev_paid_perc, b.prev_paid_rbn, b.curr_paid_perc, b.curr_parent_id, b.curr_paid_rbn from mbookheader a inner join mbookdetail b on (a.mbheaderid = b.mbheaderid) where a.sheetid = '$sheetid' and a.subdivid = '$subdivid' and a.date >= '$fromdate' and a.date <= '$todate' order by a.date asc, b.mbheaderid asc, b.mbdetail_id asc";
											//echo $SelectDetailsQuery;//exit;
											$SelectDetailsSql 	= mysql_query($SelectDetailsQuery);
											if($SelectDetailsSql == true){
												if(mysql_num_rows($SelectDetailsSql)>0){ $slno = 1;
											?>
												<table class="table table-bordered dettab table-hover example TabF" id="BalTable<?= $RABValue; ?>">
													<thead>
														<tr>
															<th style="text-align:center">Slno</th>
															<th style="text-align:center">Date</th>
															<th>Description</th>
															<th style="text-align:center" nowrap="nowrap">Contents of Area</th>
															<th nowrap="nowrap">&nbsp; Paid % &nbsp;</th>
															<th nowrap="nowrap" style="padding: 4px 4px;" align="center">
																<div align="center">( % )</div>
																<div>
																<input type="text" name="fixAll<?php echo $RABValue; ?>[]" data-id="<?php echo $RABValue; ?>" class="form-control small-tbox tbox-r fixAll" data-type='A'>
																</div>
															</th>
															<!--<th style="text-align:center">&nbsp;</th>-->
														</tr>
													</thead>
													<tbody>
													<?php $PrevParIdArr = array(); $CurrParIdArr = array(); while($DetList = mysql_fetch_object($SelectDetailsSql)){ 
														if($DetList->measurement_contentarea == ""){ 
															$SingleRowQty = 0; 
														}else{ 
															$SingleRowQty = $DetList->measurement_contentarea; 
														} 
														$ExpPrevParIDStr 	= explode(",",$DetList->prev_parent_id);
														$ExpPrevPaidpercStr = explode(",",$DetList->prev_paid_perc);
														$ExpPrevPaidRbnStr 	= explode(",",$DetList->prev_paid_rbn);
														$TotalpaidPerc 		= array_sum($ExpPrevPaidpercStr);
														//$prev_parent_id = $DetList->prev_parent_id;
														$prev_parent_id = end($ExpPrevParIDStr);
														$GrParId = $ExpPrevParIDStr[0];
														if(in_array($GrParId , $PrevParIdArr)){
															// Already Exist so no need
														}else{
															//if($prev_parent_id != 0){
																array_push($PrevParIdArr,$GrParId);
															//}
														}
														//array_push($CurrParIdArr[$curr_parent_id],$DetList->mbdetail_id);
														if($DetList->curr_paid_rbn == $rbn){
															if($CurrParIdArr[$DetList->curr_parent_id][$DetList->curr_paid_perc] == ''){
																$CurrParIdArr[$DetList->curr_parent_id][$DetList->curr_paid_perc] = $DetList->mbdetail_id;
															}else{
																$CurrParIdArr[$DetList->curr_parent_id][$DetList->curr_paid_perc] = $CurrParIdArr[$DetList->curr_parent_id][$DetList->curr_paid_perc].",".$DetList->mbdetail_id;
															}
														}
														$HistoryStr = ""; $BreakStr = ""; $Br = 0;
														foreach($ExpPrevPaidRbnStr as $PaidRbnKey => $PaidRbnValue){
															if($ExpPrevPaidpercStr[$PaidRbnKey] != 0){
																$PaidPercValue = $ExpPrevPaidpercStr[$PaidRbnKey];
																$ExpPaidPercValue = explode(".",$PaidPercValue);
																if(end($ExpPaidPercValue) > 0){
																	$PaidPercValue = $PaidPercValue;
																}else{
																	$PaidPercValue = round($PaidPercValue);
																}
																if($Br > 0){ $BreakStr = "</br><div style='height:4px;'>&nbsp;</div>"; }else{ $BreakStr = ""; }
																$HistoryStr .= $BreakStr."RAB-".$PaidRbnValue." : ".$PaidPercValue." %";
																$Br++;
															}
														}
														
													?>
														<tr class="unread">
															<td align="center">
															<?php echo $slno; ?><?php //echo $DetList->mbdetail_id; //echo $prev_parent_id; ?>
															<input type="hidden" name="txt_mbd_id<?php echo $RABValue; ?>[]" value="<?php echo $DetList->mbdetail_id; ?>">
															</td>
															<td align="center"><?php if($PrevDate != $DetList->date){ echo dt_display($DetList->date); } $PrevDate = $DetList->date; ?></td>
															<td align="left" class="DescCell" data-id="<?php echo $DetList->mbdetail_id; ?>"><?php echo $DetList->descwork; ?></td>
															<td align="right" class="CArea<?php echo $RABValue; ?>" data-id="<?php echo $DetList->mbdetail_id; ?>" data-qty="<?php echo $SingleRowQty; ?>"><?php if($DetList->measurement_contentarea != 0){ echo number_format($DetList->measurement_contentarea,$Decimal,'.',''); } ?></td>
															<td align="center" nowrap="nowrap">
															<?php //echo $TotalpaidPerc;//."*".$prev_parent_id; ?>
															<?php if($HistoryStr != ''){ echo "<div class='span-info'>".$HistoryStr."</div>"; } ?>
															</td>
															<td nowrap="nowrap" width="40px" class="PercBox" data-id="<?php echo $DetList->mbdetail_id; ?>">
															<?php if($TotalpaidPerc < 100){ ?>
																<input type="text" name="txt_curr_perc<?php echo $RABValue; ?>[]"  data-type='S' data-rbn="<?php echo $RABValue; ?>" id="txt_curr_perc<?php echo $DetList->mbdetail_id; ?>" data-id="<?php echo $DetList->mbdetail_id; ?>" data-qty="<?php echo $DetList->measurement_contentarea; ?>" data-prev_par_id = "<?php echo $prev_parent_id;//$prev_parent_id; ?>" class="form-control small-tbox tbox-r FixCurrPerc<?php echo $RABValue; ?> fixAll PreParId<?php echo $prev_parent_id;//$prev_parent_id; ?>" value="<?php if($DetList->curr_paid_perc != 0){ echo $DetList->curr_paid_perc; } ?>">
															<?php } ?>
															</td>
															<!--<td align="center" nowrap="nowrap"><?php echo $HistoryStr; ?></td>-->
														</tr>
												<?php $slno++; } ?>
													</tbody>
												</table>
												<div class="panel-footer ib-ml-ft">
													<input type="hidden" name="txt_prev_par_id" id="txt_prev_par_id<?php echo $RABValue; ?>" value="<?php $Test = implode(",",$PrevParIdArr); echo $Test; ?>">
													&nbsp;Total no. of rows selected : &nbsp; <span class="badge" style="color:#292929; font-size:13px; background:#FFFFFF" id="TotalSelected<?php echo $RABValue; ?>">0</span>
												</div>
											<?php } } ?>	
                                			</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:75px;"></div>
						</div>
					</div>
					<div class="col-md-4 col-md-4 col-sm-4 col-xs-4" style="padding-left:0px; padding-right:3px">
						<div class="pricing card-deck flex-column flex-md-row mb-3">
							<div class="card card-pricing text-center px-3 mb-4">
								<span class="center-header head-color1">RAB <?php echo "- ".$rbn; ?>&nbsp; Payable Amount</span>
								<div class="card-body pt-0">
									<table class="table mtab SlmTab table2 TabF">
										<thead>
											<tr>
												<td align="left">Since Last Amount &#8377;</td>
												<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="SLMTotalAmt"></span></td>
											</tr>
											<tr>
												<td align="left">Deduct Previous Amount &#8377;</td>
												<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="DPMTotalAmt"></span></td>
											</tr>
											<tr>
												<td align="left">Total payable Amount &#8377;</td>
												<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="UPTOTotalAmt"></span></td>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
						<div class="pricing card-deck flex-column flex-md-row mb-3">
							<div class="card card-pricing text-center px-3 mb-4">
								<span class="center-header head-color4">Since Last Measurement</span>
								<div class="card-body pt-0">
									<table class="table mtab SlmTab TabF" id="SLMaddrTab<?php echo $rbn; ?>" style="margin-bottom:6px">
										<thead>
											<tr>
												<td class="text-center" width="40px">RAB</td>
												<td class="text-center" width="75px">Qty</td>
												<td align="right" width="75px">Rate &#8377;</td>
												<td class="text-center" width="45px">(%)</td>
												<td align="center">Amount &#8377;</td>
												<!--<td align="center">&nbsp;</td>-->
											</tr>
										</thead>
										<tbody>
											<?php if($SlmRowCnt3 == 1){ while($MBGList = mysql_fetch_object($SelectSlmPaidSql)){ ?>
											<tr id='SlmAddR0' class="SLMROW<?php echo $rbn; ?>">
												<td><input type="text" name='ParSlmRAB' class="form-control small-tbox tbox-c ronly" readonly="" value="<?php echo $rbn; ?>"/></td>
												<td><input type="text" name='ParSlmAddQty' class="form-control small-tbox tbox-r ronly" readonly="" data-rbn="<?php echo $rbn; ?>" value="<?php echo $MBGList->mbtotal; ?>"/></td>
												<td><input type="text" name='ParSlmAddRate' class="form-control small-tbox tbox-r ronly" readonly="" value="<?php echo $ItemRate; ?>"/></td>
												<td><input type="text" name='ParSlmAddPerc' class="form-control small-tbox tbox-r" readonly='' data-rbn="<?php echo $rbn; ?>" value=""/></td>
												<td><input type="text" name='ParSlmAddAmount' class="form-control small-tbox tbox-r ronly" readonly="" value=""/></td>
											</tr>
											<?php } } ?>
											
											
											<?php 
											$SLMTotalPayableAmt = 0;  if($PPrbn == $rbn){ $SlmLock = 0; }else{ $SlmLock = 1; }
											$ResRbnArr = array(); if($SlmRowCnt2 == 1){ $SlmPaidSlno = 1; foreach($SLMQtyArr as $QtyKey=>$QtyValue){ 
											$SLMItemAmount = round(($QtyValue*$ItemRate*$SLMPercArr[$QtyKey]/100),2);
											$SLMTotalPayableAmt = $SLMTotalPayableAmt + $SLMItemAmount;
											?>
											<tr id='SlmAddR0' class="SLMROW<?php echo $rbn; ?>">
												<td><input type="text" name='ParSlmRAB<?php echo $rbn; ?>[]' class="form-control small-tbox tbox-c ronly" readonly="" value="<?php echo $rbn; ?>"/></td>
												<td><input type="text" name='ParSlmAddQty<?php echo $rbn; ?>[]' class="form-control small-tbox ResCls tbox-r SlmQty" data-rbn="<?php echo $rbn; ?>" value="<?php echo round($QtyValue,$Decimal); ?>"/></td>
												<td><input type="text" name='ParSlmAddRate<?php echo $rbn; ?>[]' class="form-control small-tbox tbox-r SlmRate ronly" readonly="" value="<?php echo $ItemRate; ?>"/></td>
												<td><input type="text" name='ParSlmAddPerc<?php echo $rbn; ?>[]' class="form-control small-tbox tbox-r ResCls SlmPerc" readonly='' data-rbn="<?php echo $rbn; ?>" value="<?php echo $SLMPercArr[$QtyKey]; ?>"/></td>
												<td>
													<input type="text" name='ParSlmAddAmount<?php echo $rbn; ?>[]' class="form-control small-tbox tbox-r SlmAmt ronly" readonly="" value="<?php echo $SLMItemAmount; ?>"/>
													<!--<i class="fa faAdd SlmAddRow" data-id="<?php echo $rbn; ?>" data-rbn="<?php echo $rbn; ?>">&#xf055;</i>-->
													<input type="hidden" name="ParSlmMbIdList<?php echo $rbn; ?>[]">
												</td>
												<td><i class='fa faOk SlmDelRow' data-rbn='<?php echo $rbn; ?>'>&#xf058;</i></td>
												<!--<td></td>-->
											</tr>
											<?php $DpmPaidSlno++; } }else{ ?>
											<tr id='SlmAddR0' style="border:1px solid #E9E9E9; color:#999999">
												<td colspan="5"> No Measurements Found </td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
							<div class="card card-pricing text-center px-3 mb-4">
								<span class="center-header head-color2">Deduct Previous Measurement</span>
								<div class="card-body pt-0">
									<table class="table mtab SlmTab TabF" id="DPMaddrTab<?php echo $rbn; ?>" style="margin-bottom:6px">
										<thead>
											<tr>
												<td class="text-center" width="40px">RAB</td>
												<td class="text-center" width="70px">Qty</td>
												<td align="right" width="80px">Rate &#8377;</td>
												<td class="text-center" width="45px">(%)</td>
												<td align="right" width="90px">Amount &#8377;</td>
											</tr>
										</thead>
										<tbody>
										<?php 
										/// Get ALl the List With Part Rate Released from Previous Paid RAB's
										$SelectGrParDetailQuery = "select * from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' and part_pay_flag = '1'";
										$SelectGrParDetailSql = mysql_query($SelectGrParDetailQuery);
										if($SelectGrParDetailSql == true){
											if(mysql_num_rows($SelectGrParDetailSql)>0){
												while($GrParDtList = mysql_fetch_object($SelectGrParDetailSql)){
													$GrParQty 	    = $GrParDtList->mbtotal;
													$GrParPercent   = $GrParDtList->pay_percent;
													$GrParRbn 		= $GrParDtList->rbn;
													$DistGrParIdArr = array();
													$DistGrParId 	= $GrParDtList->measurementbookid;
													$PPayExist = 0;
													$ALLParIDArr = array(); $ALLChildIDArr = array(); $ONLYChildIDArr = array(); $AllDetailSArr = array();
													//// GET All the Part rate released deatils of the Grand Parent Id
													$SelectALLIDQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$subdivid' and gr_par_id = '$DistGrParId' and rbn < '$rbn' order by ppid asc";
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
																$PPayExist++;
															}
														}
													}
													///Ger Only the Child element which has no parent
													$ONLYChildIDArr = array_diff($ALLChildIDArr, $ALLParIDArr);
													//// If Already Qty split part rate NOT released for that Parent ID
													if($PPayExist == 0){
														$CurrPPayAmt = round(($GrParQty * $GrParPercent * $ItemRate / 100),2);
														
														$CurrPPayCount = 0;
														$SelectCurrPPayQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and subdivid = '$subdivid' and gr_par_id = '$DistGrParId' and rbn = '$rbn' order by ppid asc";
														$SelectCurrPPaySql = mysql_query($SelectCurrPPayQuery);
														if($SelectCurrPPaySql == true){
															if(mysql_num_rows($SelectCurrPPaySql)>0){
																$CurrPPayCount = 1;
																while($CurrPPayList = mysql_fetch_object($SelectCurrPPaySql)){
																	$CurrPPayList
																}
															}
														}
														
													?>
														<tr class='DpmAddR<?php echo $GrParRbn; ?>'>
															<td><input type='text' name='ChiDpmRAB<?php echo $GrParRbn; ?>' class='form-control small-tbox ronly tbox-c' readonly='' value='<?php echo $GrParRbn; ?>'/></td>
															<td><input name='ChiDpmAddQty<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty<?php echo $GrParRbn; ?>' data-rbn='<?php echo $GrParRbn; ?>' value='<?php echo $GrParQty; ?>' /></td>
															<td><input name='ChiDpmAddRate<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='<?php echo $ItemRate; ?>' /> </td>
															<td><input name='ChiDpmAddPerc<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' readonly='' data-rbn='<?php echo $GrParRbn; ?>' value='<?php echo $GrParPercent; ?>'></td>
															<td><input name='ChiDpmAddAmount<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='<?php echo $CurrPPayAmt; ?>'></td>
															<td>
																<i class='fa faOk DpmDelRow DpmDelRow<?php echo $GrParRbn; ?>' data-rbn='<?php echo $GrParRbn; ?>'>&#xf058;</i>
																<input type='hidden' name='ChiParDpmMbId<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' value='<?php echo $DistGrParId; ?>'>
																<input type='hidden' name='ChiParDpmMbIdList<?php echo $GrParRbn; ?>PID<?php echo $DistGrParId; ?>[]' value='<?php echo $CurrParIdArr[$DistGrParId][$PercArr[$DistGrParId]]; ?>'>
															</td>
														</tr>
													<?php
													}else{ //// If Already Qty split part rate released for that Parent ID
														foreach($ONLYChildIDArr as $OCKey => $OCValue){
															$PercArr 	= array(); $QtyArr = array();
															$Child 		= $OCValue;
															$PercArr[$OCValue] = $GrParPercent;
															$z = 1; $temp = 0;
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
															$CurrPPayAmt = 0;
															$CurrPPayAmt = round(($QtyArr[$OCValue] * $PercArr[$OCValue] * $ItemRate / 100),2);
														?>
														<tr class='DpmAddR<?php echo $GrParRbn; ?>'>
															<td><input type='text' name='ChiDpmRAB<?php echo $GrParRbn; ?>' class='form-control small-tbox ronly tbox-c' readonly='' value='<?php echo $GrParRbn; ?>'/></td>
															<td><input name='ChiDpmAddQty<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty<?php echo $GrParRbn; ?>' data-rbn='<?php echo $GrParRbn; ?>' value='<?php echo $QtyArr[$OCValue]; ?>' /></td>
															<td><input name='ChiDpmAddRate<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='<?php echo $ItemRate; ?>' /> </td>
															<td><input name='ChiDpmAddPerc<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' readonly='' data-rbn='<?php echo $GrParRbn; ?>' value='<?php echo $PercArr[$OCValue]; ?>'></td>
															<td><input name='ChiDpmAddAmount<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='<?php echo $CurrPPayAmt; ?>'></td>
															<td>
																<i class='fa faOk DpmDelRow DpmDelRow<?php echo $GrParRbn; ?>' data-rbn='<?php echo $GrParRbn; ?>'>&#xf058;</i>
																<input type='hidden' name='ChiParDpmMbId<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' value='<?php echo $OCValue; ?>'>
																<input type='hidden' name='ChiParDpmMbIdList<?php echo $GrParRbn; ?>PID<?php echo $OCValue; ?>[]' value='<?php echo $CurrParIdArr[$OCValue][$PercArr[$OCValue]]; ?>'>
															</td>
														</tr>
														<?php			
														}
													}
												}
											}
										}
										?>
										
										
										
										<?php 
										/*$DPMTotalPayableAmt = 0; 
										if($DpmRowCnt2 == 1){ $DpmPaidSlno = 1; while($DpmPaidList = mysql_fetch_object($SelectDpmPaidSql)){ 
										$ToBeAddPercent = $DpmPaidList->pay_percent;
										if($DpmPaidList->part_pay_flag == '1'){
											$PartPayQSPExist = 0; $RonlyClass = '';
											$SelectPPQtyQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' and gr_par_id = '$DpmPaidList->measurementbookid'";
											$SelectPPQtySql = mysql_query($SelectPPQtyQuery); 
											if($SelectPPQtySql == true){
												if(mysql_num_rows($SelectPPQtySql)>0){
													$PartPayQSPExist = 1; 
													$RonlyClass = 'ronly';
												}
											}
											if($DpmPaidList->rbn == $rbn){ $DpmLock = 0; }else{ $DpmLock = 1; }
										?>
											<tr id='DpmAddR<?php echo $DpmPaidSlno; ?>' class="DpmAddR<?php echo $DpmPaidSlno; ?> DPMROW<?php echo $DpmPaidList->rbn; ?> DpmPId<?php echo $DpmPaidList->measurementbookid; ?>">
												<td><input type="text" name='ParDpmRAB<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-c ronly" readonly="" value="<?php echo $DpmPaidList->rbn; ?>"/></td>
												<td><input type="text" name='ParDpmAddQty<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly ResCls DpmQty DpmQty<?php echo $DpmPaidList->rbn; ?>" readonly="" data-rbn="<?php echo $DpmPaidList->rbn; ?>" value="<?php echo $DpmPaidList->mbtotal; ?>"/></td>
												<td><input type="text" name='ParDpmAddRate<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly DpmRate" readonly="" value="<?php echo $ItemRate; ?>"/></td>
												<td><input type="text" name='ParDpmAddPerc<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ResCls DpmPerc <?php echo $RonlyClass; ?>" readonly='' data-rbn="<?php echo $DpmPaidList->rbn; ?>" value=""/></td>
												<td>
													<input type="text" name='ParDpmAddAmount<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly DpmAmt" readonly="" value="<?php echo "0.00";//$DpmPaidList->measurementbookid; ?>"/>
													<input type="hidden" name="ParDpmMbId<?php echo $DpmPaidList->rbn; ?>[]" value="<?php echo $DpmPaidList->measurementbookid; ?>">
													<input type="hidden" name="ParDpmMbIdList<?php echo $DpmPaidList->rbn; ?>[]">
													<input type="hidden" class="DpmAddRow" data-mbid="<?php echo $DpmPaidList->measurementbookid; ?>">
												</td>
											</tr>
										<?php 
											if($PartPayQSPExist == 1){
												if(in_array($DpmPaidList->rbn,$ResRbnArr)){
													// Already Exist so no need
												}else{
													array_push($ResRbnArr,$DpmPaidList->rbn);
												}
												while($PPList = mysql_fetch_object($SelectPPQtySql)){
													$CurrPPayAmt = round(($PPList->qty * $PPList->percent * $ItemRate / 100),2);
													$DPMTotalPayableAmt = $DPMTotalPayableAmt + $CurrPPayAmt;
										?>
											<tr class='LDPMROW<?php echo $DpmPaidList->rbn; ?>'>
												<td><input type='text' name='ChiDpmRAB<?php echo $DpmPaidList->rbn; ?>' class='form-control small-tbox ronly tbox-c' readonly='' value='<?php echo $DpmPaidList->rbn; ?>'/></td>
												<td><input name='ChiDpmAddQty<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty<?php echo $DpmPaidList->rbn; ?>' data-rbn='<?php echo $DpmPaidList->rbn; ?>' value='<?php echo $PPList->qty; ?>' /></td>
												<td><input name='ChiDpmAddRate<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='<?php echo $ItemRate; ?>' /> </td>
												<td><input name='ChiDpmAddPerc<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' readonly='' data-rbn='<?php echo $DpmPaidList->rbn; ?>' value='<?php echo round($PPList->percent); ?>'></td>
												<td><input name='ChiDpmAddAmount<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='<?php echo $CurrPPayAmt; ?>'></td>
												<td>
												<i class='fa faOk DpmDelRow DpmDelRow<?php echo $DpmPaidList->rbn; ?>' data-rbn='<?php echo $DpmPaidList->rbn; ?>'>&#xf058;</i>
												<input type='hidden' name='ChiParDpmMbId<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' value='<?php echo $DpmPaidList->measurementbookid; ?>'>
												<input type='hidden' name='ChiParDpmMbIdList<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' value='<?php echo $CurrParIdArr[$PPList->mbid][$PPList->percent]; ?>'></td>
											</tr>
										<?php			
												}
											}
										?>
										<?php $DpmPaidSlno++; } } }else{ ?>
											<tr id='DpmAddR1' style="border:1px solid #E9E9E9; color:#999999">
												<td colspan="6"> No Measurements Found </td>
											</tr>
										<?php }*/ ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php /*$iiiiiStart; ?>
						<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
							<div class="card card-pricing text-center px-3 mb-4">
								<span class="center-header head-color2">Deduct Previous Measurement</span>
								<div class="card-body pt-0">
									<table class="table mtab SlmTab TabF" id="DPMaddrTab<?php echo $rbn; ?>" style="margin-bottom:6px">
										<thead>
											<tr>
												<td class="text-center" width="40px">RAB</td>
												<td class="text-center" width="70px">Qty</td>
												<td align="right" width="80px">Rate &#8377;</td>
												<!--<td class="text-center" width="25px" style="line-height:9px;">%<span style="font-size:10px;"><br/>(Paid)</span></td>-->
												<td class="text-center" width="45px">(%)</td>
												<td align="right" width="90px">Amount &#8377;</td>
												<!--<td class="text-center"></td>-->
											</tr>
										</thead>
										<tbody>
										<?php 
										$DPMTotalPayableAmt = 0; 
										if($DpmRowCnt2 == 1){ $DpmPaidSlno = 1; while($DpmPaidList = mysql_fetch_object($SelectDpmPaidSql)){ 
										$ToBeAddPercent = $DpmPaidList->pay_percent;
										if($DpmPaidList->part_pay_flag == '1'){
											$PartPayQSPExist = 0; $RonlyClass = '';
											$SelectPPQtyQuery = "select * from pp_qty_splt where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' and gr_par_id = '$DpmPaidList->measurementbookid'";
											$SelectPPQtySql = mysql_query($SelectPPQtyQuery); 
											if($SelectPPQtySql == true){
												if(mysql_num_rows($SelectPPQtySql)>0){
													$PartPayQSPExist = 1; 
													$RonlyClass = 'ronly';
												}
											}
											if($DpmPaidList->rbn == $rbn){ $DpmLock = 0; }else{ $DpmLock = 1; }
										?>
											<tr id='DpmAddR<?php echo $DpmPaidSlno; ?>' class="DpmAddR<?php echo $DpmPaidSlno; ?> DPMROW<?php echo $DpmPaidList->rbn; ?> DpmPId<?php echo $DpmPaidList->measurementbookid; ?>">
												<td><input type="text" name='ParDpmRAB<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-c ronly" readonly="" value="<?php echo $DpmPaidList->rbn; ?>"/></td>
												<td><input type="text" name='ParDpmAddQty<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly ResCls DpmQty DpmQty<?php echo $DpmPaidList->rbn; ?>" readonly="" data-rbn="<?php echo $DpmPaidList->rbn; ?>" value="<?php echo $DpmPaidList->mbtotal; ?>"/></td>
												<td><input type="text" name='ParDpmAddRate<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly DpmRate" readonly="" value="<?php echo $ItemRate; ?>"/></td>
												<!--<td><input type="text" name='ParDpmPaidPerc<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly DpmPaidPerc" value="<?php echo $DpmPaidList->pay_percent; ?>"/></td>-->
												<td><input type="text" name='ParDpmAddPerc<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ResCls DpmPerc <?php echo $RonlyClass; ?>" readonly='' data-rbn="<?php echo $DpmPaidList->rbn; ?>" value=""/></td>
												<td>
													<input type="text" name='ParDpmAddAmount<?php echo $DpmPaidList->rbn; ?>[]' class="form-control small-tbox tbox-r ronly DpmAmt" readonly="" value="<?php echo "0.00";//$DpmPaidList->measurementbookid; ?>"/>
													<input type="hidden" name="ParDpmMbId<?php echo $DpmPaidList->rbn; ?>[]" value="<?php echo $DpmPaidList->measurementbookid; ?>">
													<input type="hidden" name="ParDpmMbIdList<?php echo $DpmPaidList->rbn; ?>[]">
													<input type="hidden" class="DpmAddRow" data-mbid="<?php echo $DpmPaidList->measurementbookid; ?>">
												</td>
											</tr>
										<?php 
											if($PartPayQSPExist == 1){
												if(in_array($DpmPaidList->rbn,$ResRbnArr)){
													// Already Exist so no need
												}else{
													array_push($ResRbnArr,$DpmPaidList->rbn);
												}
												while($PPList = mysql_fetch_object($SelectPPQtySql)){
													$CurrPPayAmt = round(($PPList->qty * $PPList->percent * $ItemRate / 100),2);
													$DPMTotalPayableAmt = $DPMTotalPayableAmt + $CurrPPayAmt;
										?>
											<tr class='LDPMROW<?php echo $DpmPaidList->rbn; ?>'>
												<td><input type='text' name='ChiDpmRAB<?php echo $DpmPaidList->rbn; ?>' class='form-control small-tbox ronly tbox-c' readonly='' value='<?php echo $DpmPaidList->rbn; ?>'/></td>
												<td><input name='ChiDpmAddQty<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty<?php echo $DpmPaidList->rbn; ?>' data-rbn='<?php echo $DpmPaidList->rbn; ?>' value='<?php echo $PPList->qty; ?>' /></td>
												<td><input name='ChiDpmAddRate<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='<?php echo $ItemRate; ?>' /> </td>
												<!--<td><input name='ChiDpmPaidPerc<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ronly ResCls' readonly='' value='<?php echo $DpmPaidList->pay_percent; ?>'></td>-->
												<td><input name='ChiDpmAddPerc<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' readonly='' data-rbn='<?php echo $DpmPaidList->rbn; ?>' value='<?php echo round($PPList->percent); ?>'></td>
												<td><input name='ChiDpmAddAmount<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='<?php echo $CurrPPayAmt; ?>'></td>
												<td>
												<i class='fa faOk DpmDelRow DpmDelRow<?php echo $DpmPaidList->rbn; ?>' data-rbn='<?php echo $DpmPaidList->rbn; ?>'>&#xf058;</i>
												<input type='hidden' name='ChiParDpmMbId<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' value='<?php echo $DpmPaidList->measurementbookid; ?>'>
												<input type='hidden' name='ChiParDpmMbIdList<?php echo $DpmPaidList->rbn; ?>PID<?php echo $DpmPaidList->measurementbookid; ?>[]' value='<?php echo $CurrParIdArr[$PPList->mbid][$PPList->percent]; ?>'></td>
											</tr>
										<?php			
												}
											}
										?>
										<?php $DpmPaidSlno++; } } }else{ ?>
											<tr id='DpmAddR1' style="border:1px solid #E9E9E9; color:#999999">
												<td colspan="6"> No Measurements Found </td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php $iiiiiEnd;*/ ?>
					</div>
					<input type="hidden" name="txt_result" id="txt_result" value="<?php if(count($ResRbnArr)>0){ echo implode('*',$ResRbnArr); } ?>">
					<input type="hidden" name="txt_slm_payable_amt" id="txt_slm_payable_amt" value="<?php echo number_format(round($SLMTotalPayableAmt,2), 2, '.', ','); ?>">
					<input type="hidden" name="txt_dpm_payable_amt" id="txt_dpm_payable_amt" value="<?php echo number_format(round($DPMTotalPayableAmt,2), 2, '.', ','); ?>">
					<input type="hidden" name="txt_upto_payable_amt" id="txt_upto_payable_amt" value="<?php echo number_format(round(($SLMTotalPayableAmt + $DPMTotalPayableAmt),2), 2, '.', ','); ?>">
					<div class="loaddiv pageload" id="pageload"><div class="cssload-loader"></div></div>
					<!--<div class="loaddiv" id="pageload1">
						<div class="cssload-container">
							<ul class="cssload-flex-container">
								<li>
									<span class="cssload-loading"></span>
								</li>
							</ul>
						</div>	
					</div>-->
                    <!--<div class="col-md-4 col-md-4 col-sm-4 col-xs-12" style="padding:0px;padding-left:5px">
						
                        <div class="hpanel responsive-mg-b-30">
									<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
										<div class="card card-pricing text-center px-3 mb-4">
											<span class="center-header head-color3">Total Payable Amount</span>
											<div class="card-body pt-0">
												<table class="table mtab SlmTab">
													<thead>
														<tr>
															<td align="left">Since Last Amount &#8377;</td>
															<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="SLMTotalAmt"> <?php echo $SLMTotalAmt; ?> </span></td>
														</tr>
														<tr>
															<td align="left">Deduct Previous Amount &#8377;</td>
															<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="DPMTotalAmt"> 0.00 </span></td>
														</tr>
														<tr>
															<td align="left">Total payable Amount &#8377;</td>
															<td align="right"><span class="badge" style="color:#333; background:#D6D6D7; font-size:13px;" id="UPTOTotalAmt"> <?php echo $SLMTotalAmt; ?> </span></td>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
                        </div>
                    </div>-->
                    
                </div>
            </div>
        </div>
    </div>
    <?php include("JSLibrary.php"); ?>
	<script>
		$(".loaddiv").show();
	</script>
		<link href="../bootstrap-dialog/css/bootstrap-min.css" rel="stylesheet" type="text/css" />
		<script src="../bootstrap-dialog/js/bootstrap.min.js"></script> <!---IMP-->
		<link href="../bootstrap-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
		<script src="../bootstrap-dialog/js/bootstrap-dialog.min.js"></script>
        <div class="fixed-bottom" style="text-align:center">
			<div class="col-md-1" align="left">
				<button type="button" class="btn" name="left_top" id="left_top" onClick="topFunction2()">
					<i class="fa fa-arrow-circle-o-up" style="font-size:30px; color:#03AECA;"></i>
				</button>
			</div>
			<div class="col-md-4"><textarea name="txt_comment" id="txt_comment" class="form-control" style="height:37px; border-color:#2593FB" placeholder="Enter your remarks here"></textarea></div>
			<div class="col-md-3"><button type="button" name="PayableAmt" id="btn_info" class="btn btn-success col-md-12" disabled="disabled" style="opacity:1; text-align:left">Total Amount : &nbsp; &#8377 <span class="badge" style="color:#000000; font-size:13px;" id="TotalPayableAmt"> <?php echo number_format(round(($SLMTotalPayableAmt + $DPMTotalPayableAmt),2), 2, '.', ','); ?> </span> </button></div>
           	<div class="col-md-1"><input type="submit" name="btn_save" id="btn_save" value=" Save " class="btn btn-primary col-md-12"></div>
		   	<div class="col-md-1"><input type="submit" name="btn_cancel" id="btn_cancel" value=" Cancel " class="btn btn-danger col-md-12"></div>
		   	<div class="col-md-1"><input type="submit" name="btn_back" id="btn_back" value=" Back " class="btn btn-warning col-md-12"></div>
			<div class="col-md-1" align="right">
				<!--<input type="button" name="set" id="set" value="Set" class="btn btn-danger">-->
				<button type="button" class="btn btn-top" name="right_top" id="right_top" onClick="topFunction()">
					<i class="fa fa-arrow-circle-o-up" style="font-size:30px; color:#03AECA;"></i>
				</button>
			</div>
        </div>
	</form>
	<div style="display:none" id="QtyDtModalContent" >
		<table class="table table-bordered">
			<tr><td colspan="2">Agreement Qty. Details</td></tr>
			<tr><td>Agreement Qty.</td><td></td></tr>
			<tr><td>Deviation Qty.(As per Agreement)</td><td></td></tr>
			<tr><td>Total Qty.(As per Agreement)</td><td></td></tr>
			<tr><td colspan="2">Utilized Qty. Details</td></tr>
			<tr><td>Agreement Qty Utilized</td><td></td></tr>
			<tr><td>Deviated Qty Utilized</td><td></td></tr>
			<tr><td>Balance Qty.</td><td></td></tr>
		</table>
	</div>
</body>

</html>
<style>
</style>
<script>
$(".loaddiv").show();
var myMessages = ['info']; // define the messages types    	 
	function hideAllMessages(){
		$(".ViewDetails").each(function() {
			var id = $(this).attr('id');
			var height = $(this).outerHeight();
			$(this).css('top', -height);
		});
	}
	$(document).ready(function(){
		
		hideAllMessages();
		$('.ClickDetails').click(function(){
			hideAllMessages();
			var id = $(this).attr('data-id');
			var subdivid = $('#txt_subdivid').val();
			var sheetid = $('#txt_sheetid').val();
			$('#View'+id).animate({top:"60"}, 500);
			//$("#MeasDetTable tbody"+id).empty();
			$('#MeasDetTable'+id+' >tbody').html('');
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/GetAllMeasurements.php', 
				data: { rbn: id, subdivid: subdivid, sheetid: sheetid }, 
				dataType: 'json',
				success: function (data) {   //alert(data);
					if(data != null){
						$.each(data, function(index, element) {
							var TrRow = "<tr><td>"+element.date+"</td><td>"+element.descwork+"</td><td>"+element.measurement_contentarea+"</td><td>"+element.remarks+"</td></tr>"
							$("#MeasDetTable"+id+" tbody").append(TrRow);
						});
					}
				}
			});
			
		});
		/*$('.message').click(function(){			  
			$(this).animate({top: -$(this).outerHeight()}, 500);
		});*/	
		$('.CloseDetail').click(function(){			  
			$(this).parents	(".message").animate({top: -$(this).parents	(".message").outerHeight()}, 500);
		});	
		
		$(".BalanceDetails").each(function() {
			$(this).hide();
		});	
		//var rbn = $('#txt_rbn').val();
		//$('#BalanceDetails'+rbn).show();
		var rbn = $('#txt_bal1st_rbn').val();
		$('#BalanceDetails'+rbn).show();
		//SetTBoxHeight();
		$('.BalanceBtn').click(function(){	
			$(".BalanceBtn").each(function() {
				$(this).css("background-color", "#fff");
				$(this).css("border-color", "#ccc");
				$(this).css("color", "#303030");
			});	
			$(this).css("background-color", "#006DF0");
			$(this).css("border-color", "#006DF0");
			$(this).css("color", "#fff");
				  
			var id = $(this).attr('data-id');
			$(".BalanceDetails").each(function() {
				$(this).hide();
			});
			$('#BalanceDetails'+id).show();
			//$('#BalTable'+id).DataTable({ "paging":false, "ordering":false, "info":false });
			//SetTBoxHeight();
			$('body').css('min-height', '100%');
			$('body').attr('style','min-height:100%;');
		});
		
		var i=1;
		
		function SetTBoxHeight(){
			$(".PercBox").each(function() { 
				var mbdid 	= $(this).attr("data-id"); 
				var CellHt 	= $(this).height(); 
				$('#txt_curr_perc'+mbdid).height(CellHt);
			});
		}
		function SetFixedWidth(){
			var bodyWid = $('body').width();
			var BottCont = bodyWid - 194
			$(".fixed-bottom").width(BottCont);
		}
		SetFixedWidth();
		$(window).resize(function(){
			SetFixedWidth();
		});
		
		window.onscroll = function() {scrollFunction1()};

		function scrollFunction1(){
			if(document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				document.getElementById("right_top").style.display = "block";
		  	}else{
				document.getElementById("right_top").style.display = "none";
		  	}
		}
		
		$('body').on('change','.SlmQty',function(){
			var Decimal = Number($('#txt_decimal').val());
			var Rate = Number($('#txt_rate').val());
			var CurrPerc = $(this).closest('tr').find('.SlmPerc').val();
			var CurrQty = $(this).val();
			var CurrAmt = Number(CurrQty)*Number(Rate)*Number(CurrPerc)/100;
				CurrAmt = CurrAmt.toFixed(2);
			$(this).closest('tr').find('.SlmAmt').val(CurrAmt);
		
			var rbn = $(this).attr('data-rbn');
			/*$(".BalanceDetails").each(function() {
				$(this).hide();
			});
			$('#BalanceDetails'+rbn).show();
			//$('#BalanceBtn'+rbn).focus();
			$(".BalanceBtn").each(function() {
				$(this).css("background-color", "#fff");
				$(this).css("border-color", "#ccc");
				$(this).css("color", "#303030");
			});
			$('#BalanceBtn'+rbn).css("background-color", "#006DF0");
			$('#BalanceBtn'+rbn).css("border-color", "#006DF0");
			$('#BalanceBtn'+rbn).css("color", "#fff");
			
			SetTBoxHeight();
			$('body').css('min-height', '100%');
			$('body').attr('style','min-height:100%;');*/
			
			//// For Calculating rows for Changing Qty 
			var Qty = $(this).val();
			var TotalSlmQty = 0;
			$(".SlmQty").each(function() {
				var SlmQty = $(this).val();
				if((SlmQty != "")&&(SlmQty != 0)){
					SlmQty = Number(SlmQty).toFixed(Decimal); 
				}
				TotalSlmQty = TotalSlmQty + Number(SlmQty);
			});
			if(TotalSlmQty != 0){
				TotalSlmQty = TotalSlmQty.toFixed(Decimal);
			}
			
			var CAreaQtyTotal = 0;
			var TotalRowSel = 0;
			$(".CArea"+rbn).each(function() {
				var mbdid = $(this).attr("data-id");
				var qty = Number($(this).attr('data-qty'));
				if(qty != 0){ qty = qty.toFixed(Decimal); }
				//alert(qty);
				CAreaQtyTotal = Number(CAreaQtyTotal)+Number(qty);
				if(CAreaQtyTotal != 0){ CAreaQtyTotal = CAreaQtyTotal.toFixed(Decimal); }
				if(CAreaQtyTotal <= TotalSlmQty){
					$("#SRow"+mbdid).addClass("fa-active");
					$("#SRow"+mbdid).removeClass("fa-default");
					TotalRowSel++;
				}else{
					$("#SRow"+mbdid).addClass("fa-default");
					$("#SRow"+mbdid).removeClass("fa-active");
				}
			});
			$('#TotalSelected'+rbn).html(TotalRowSel);
			CalculateTotalAmounts();
			
	 	});

		$('body').on('change','.DpmQty',function(){
			var Decimal  = Number($('#txt_decimal').val());
			var Rate 	 = Number($('#txt_rate').val());
			var CurrPerc = $(this).closest('tr').find('.DpmPerc').val();
			var CurrQty  = $(this).val();
			var CurrAmt  = Number(CurrQty)*Number(Rate)*Number(CurrPerc)/100;
				CurrAmt  = CurrAmt.toFixed(2);
			$(this).closest('tr').find('.DpmAmt').val(CurrAmt);
			var rbn = $(this).attr('data-rbn');
			var Qty = $(this).val();
			var TotalDpmQty = 0;
			$(".DpmQty"+rbn).each(function() {
				var DpmQty = $(this).val();
				if((DpmQty != "")&&(DpmQty != 0)){
					DpmQty = Number(DpmQty).toFixed(Decimal); 
				}
				TotalDpmQty = TotalDpmQty + Number(DpmQty);
			});
			if(TotalDpmQty != 0){
				TotalDpmQty = TotalDpmQty.toFixed(Decimal);
			}
			//alert(TotalDpmQty);
			var CAreaQtyTotal = 0;
			var TotalRowSel = 0;
			$(".CArea"+rbn).each(function() {
				var mbdid = $(this).attr("data-id");
				var qty = Number($(this).attr('data-qty'));
				if(qty != 0){ qty = qty.toFixed(Decimal); }
				
				CAreaQtyTotal = Number(CAreaQtyTotal)+Number(qty);
				if(CAreaQtyTotal != 0){ CAreaQtyTotal = CAreaQtyTotal.toFixed(Decimal); }
				//alert(qty+" == "+CAreaQtyTotal+" == "+TotalDpmQty);
				if(Number(CAreaQtyTotal) <= Number(TotalDpmQty)){ 
					$("#SRow"+mbdid).addClass("fa-active");
					$("#SRow"+mbdid).removeClass("fa-default");
					TotalRowSel++;
				}else{
					$("#SRow"+mbdid).addClass("fa-default");
					$("#SRow"+mbdid).removeClass("fa-active");
				}
			});
			$('#TotalSelected'+rbn).html(TotalRowSel);
			CalculateTotalAmounts();
	 	});




		$("#QtyInfo").click(function(event){
			var Decimal = $('#txt_decimal').val(); 
			var AggrQty 		= Number($(this).attr("data-AggrQty")).toFixed(Decimal);
			var DevPrec 		= Number($(this).attr("data-DevPrec")).toFixed(Decimal);
			var DevQty 			= Number($(this).attr("data-DevQty")).toFixed(Decimal);
			var TotalAggrQty 	= Number($(this).attr("data-TotalAggrQty")).toFixed(Decimal);
			var PaidQty 		= Number($(this).attr("data-PaidQty")).toFixed(Decimal);
			var SLMItemQty 		= Number($(this).attr("data-SLMItemQty")).toFixed(Decimal);
			var TotalUsedQty 	= Number($(this).attr("data-TotalUsedQty")).toFixed(Decimal);
			var BalanceQty 		= Number($(this).attr("data-BalanceQty")).toFixed(Decimal);
			var AddQtyBeyDevLt 	= Number($(this).attr("data-AddQtyBeyDevLt")).toFixed(Decimal);
			var $QtyDetData = $('<table class="table table-bordered modal-table"></table>');
        		$QtyDetData.append('<tr><td colspan="2" align="center" bgcolor="#E6E9EC">Agreement Qty. Details</td></tr>');
				$QtyDetData.append('<tr><td>Agreement Qty.(As per Agreement)</td><td align="right">'+AggrQty+'</td></tr>');
				$QtyDetData.append('<tr><td>Deviation Qty.(As per Agreement)</td><td align="right">'+DevQty+'</td></tr>');
				$QtyDetData.append('<tr><td>Total Qty.(As per Agreement)</td><td align="right">'+TotalAggrQty+'</td></tr>');
				$QtyDetData.append('<tr><td colspan="2" align="center" bgcolor="#E6E9EC">Utilized Qty. Details</td></tr>');
				$QtyDetData.append('<tr><td>Agreement Qty Utilized</td><td align="right">'+TotalUsedQty+'</td></tr>');
				$QtyDetData.append('<tr><td>Deviated Qty Utilized</td><td align="right">'+AddQtyBeyDevLt+'</td></tr>');
				$QtyDetData.append('<tr><td>Balance Qty.</td><td align="right">'+BalanceQty+'</td></tr>');
			BootstrapDialog.show({
				title: "Quantity Details",
				message: $QtyDetData,
				cssClass: 'login-dialog',
				buttons: [{
					label: 'Close',
					cssClass: 'btn-primary',
					action: function(dialog){
						dialog.close();
					}
				}]
			});
		});
		//$(".loaddiv").hide();
		
	}); 
	
	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
	
	function topFunction2() {
		document.getElementById("mCSB_1_container").style.top = 0+"px";
	}
</script>
<style>
	.mtb > thead > tr > td{
		padding:3px;
	}
	.table > tbody + tbody {
    	border-top: 0px solid #ddd;
	}
	.tbox-c{ text-align:center; }
	.tbox-l{ text-align:left; }
	.tbox-r{ text-align:right; padding-right:3px; }
	.fixed-bottom {
		position: fixed; 
		bottom: 0px; 
		padding: 15px; 
		background-color:#E4EAEF;
		z-index:9999;
		margin-left:82px;
		border-top:1px solid #A8D3FF;
	}
	.btn-top{
		display:none
	}
	.modal.fade {
	  z-index: 10000000 !important;
	}
	.bootstrap-dialog .bootstrap-dialog-message {
    	font-size: 12px;
	}
	.modal-table > tbody > tr > td{
		padding:6px;
		color:#013EA5;
		font-weight:600;
	}
	.bootstrap-dialog.type-primary .modal-header{
		background-color: #DE3D5F;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		color:#F2F6F6;
		font-size: 14px;
		font-weight:600;
	}
	.modal-dialog {
		width: 35%;
	}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
<script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		var dataTab = $('.example').DataTable({ "paging":false, "ordering":false, "info":false, "autoWidth": false, "dom": '<"toolbar">frtip', "filter" : 'applied' });
		$("#set").click(function(event){
			dataTab.column( 3 ).data()
			//.rows( { filter : 'applied'} )
			.each( function ( value, index ) {
				alert( 'Data in index: '+index+' is: '+value );
			});
		});
		
		$(".fixAll").change(function(event){ 
			var input = Number($(this).val());
			$(this).val(input.toFixed(2))
			var PType = $(this).attr("data-type");
			if(PType == 'A'){
				var rbn = $(this).attr("data-id");
			}else{
				var rbn = $(this).attr("data-rbn");
			}
			var FixPerc = Number($(this).val());
			var TotalQty = 0;
			var Decimal = $('#txt_decimal').val(); 
			var ItemType = $('#txt_item_type').val(); 
			var MbDetIdArr = [];
			
			
			
			
			
			
			$(".FixCurrPerc"+rbn).each(function(){ 
				if(PType == 'A'){
					$(this).val(FixPerc.toFixed(2)); 
				}//alert();
				var Qty = Number($(this).attr("data-qty")); 
				if(ItemType == 'st'){
					Qty = (Qty / 1000);
					Qty = Qty.toFixed(Decimal); 
				}
				if(ItemType == 's'){
					
				}
				TotalQty = TotalQty + Qty; 
				var MbDetId = $(this).attr("data-id"); 
				MbDetIdArr.push(MbDetId);
			});//alert(rbn);
			var MbDetIdStr = MbDetIdArr.join(",");
			if((TotalQty != 0)&&(TotalQty != '')&&(TotalQty !== undefined)){ 
				TotalQty = Number(TotalQty).toFixed(Decimal); 
			}
			//alert(MbDetIdArr.join("*"));
			//alert(TotalQty);
			
			var CurrRbn = $('#txt_rbn').val();
			//$(".loaddiv").hide();
			//$(".loaddiv").hide(1000,'linear');
			
			if(CurrRbn == rbn){ 
				var sed = 0; var RowArr = []; var MBDetIdStrArr = [];
				dataTab.column( 3 ).data().each( function ( value, index ) {
					var Perc = dataTab.cell(sed,5).nodes().to$().find('input').val();
					
					var MBDtId = dataTab.cell(sed,5).nodes().to$().find('input').attr('data-id'); //alert(PrevParId);
					
					if(Perc == ""){ Perc = 0; }else{ Perc = Number(Perc); }
					if(value == ""){ value = 0; }else{ value = Number(value); }
					
					if(RowArr[Perc] !== undefined){
						var temp1 = Number(RowArr[Perc]);
						var temp2 = Number(value);
						var temp  = temp1 + temp2
						RowArr[Perc] = temp;
						
						var ExistStr = MBDetIdStrArr[Perc];
						var NewStr = MBDetIdStrArr[Perc]+","+MBDtId;
						MBDetIdStrArr[Perc] = NewStr;
					}else{
						RowArr[Perc] = Number(value)
						MBDetIdStrArr[Perc] = MBDtId;
					}
					sed++;
				});
			
				$(".SlmDelRow").each(function(){
					$(this).parents("tr").remove();
				});
				var ParChilRowCnt = $('.SLMROW'+rbn).length;
				$('.SLMROW'+rbn).find(".SlmPerc").prop("readonly", true);
				$('.SLMROW'+rbn).find(".SlmPerc").addClass("ronly");
				$('.SLMROW'+rbn).find(".SlmQty").prop("readonly", true);
				$('.SLMROW'+rbn).find(".SlmQty").addClass("ronly");
				$('.SLMROW'+rbn).find(".SlmPerc").val("");
				$('.SLMROW'+rbn).find(".SlmAmt").val("");
				var dataid = rbn;//$(this).attr("data-id");
				var rbn = rbn;//$(this).attr("data-id");
				var Rate 	 = Number($('#txt_rate').val());
					Rate = Rate.toFixed(2);
					
				//alert(RowArr.join("*"));
					
				RowArr.forEach(function(elementQty, keyPercent) { 
					if(ItemType == 'st'){
						elementQty = (elementQty / 1000);
						elementQty = elementQty.toFixed(Decimal);
					}
					if(ItemType == 's'){
						
					}
					var SlmAmt = Number(elementQty)*Number(Rate)*Number(keyPercent)/100;
						SlmAmt = SlmAmt.toFixed(2);
						
					var SelectedMBDid = MBDetIdStrArr[keyPercent]; //alert(SelectedMBDid);
					
					$('#SLMaddrTab'+dataid+' tr:last').after("<tr class='SLMROW"+rbn+"'><td><input type='text' name='ChiSlmRAB"+rbn+"[]' class='form-control small-tbox ronly tbox-c' readonly='' value='"+rbn+"'/></td><td><input name='ChiSlmAddQty"+rbn+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls SlmQty' data-rbn='"+rbn+"' value='"+elementQty+"' /></td><td><input name='ChiSlmAddRate"+rbn+"[]' type='text' class='form-control input-md small-tbox tbox-r ronly SlmRate' readonly='' value='"+Rate+"' /> </td><td><input name='ChiSlmAddPerc"+rbn+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls SlmPerc' readonly='' data-rbn='"+rbn+"' value='"+keyPercent+"'></td><td><input name='ChiSlmAddAmount"+rbn+"[]' type='text' class='form-control input-md small-tbox tbox-r ronly SlmAmt' readonly='' value='"+SlmAmt+"'><input type='hidden' name='ChiSlmMbIdList"+rbn+"[]' value='"+SelectedMBDid+"'></td><td><i class='fa faOk SlmDelRow' data-rbn='"+rbn+"'>&#xf058;</i></td></tr>");
				});
			}else{  
				var PrevParId = $("#txt_prev_par_id"+rbn).val();
				var PrevParIdSpit = PrevParId.split(",");
				var PrevParIdCnt = PrevParIdSpit.length;
				
				//alert("X="+PrevParId);
				$(".DpmDelRow"+rbn).each(function(){
					$(this).parents("tr").remove();
				});
					
				PrevParIdSpit.forEach(function(ParIdVal, ParIdKey) { 
					var sed = 0; var RowArr = []; var FirstVal = 1;
					var CurrParIdVal = ParIdVal; var MBDetIdStrArr = [];
					$('#BalTable'+rbn).DataTable().column( 3 ).data().each( function ( value, index ) { 
						var Perc = $('#BalTable'+rbn).DataTable().cell(sed,5).nodes().to$().find('input').val(); 
						var PrevParId = $('#BalTable'+rbn).DataTable().cell(sed,5).nodes().to$().find('input').attr('data-prev_par_id'); //alert(PrevParId);
						
						var MBDtId = $('#BalTable'+rbn).DataTable().cell(sed,5).nodes().to$().find('input').attr('data-id'); //alert(PrevParId);
						
						
						if(Perc == ""){ Perc = 0; }else{ Perc = Number(Perc); }
						if(value == ""){ value = 0; }else{ value = Number(value); }
						if(CurrParIdVal == PrevParId){
							if(RowArr[Perc] !== undefined){
								var temp1 = Number(RowArr[Perc]);
								var temp2 = Number(value);
								var temp  = temp1 + temp2
								RowArr[Perc] = temp;
								var ExistStr = MBDetIdStrArr[Perc];
								var NewStr = MBDetIdStrArr[Perc]+","+MBDtId;
								MBDetIdStrArr[Perc] = NewStr;
							}else{
								RowArr[Perc] = Number(value)
								MBDetIdStrArr[Perc] = MBDtId;
							}
						}
						sed++; FirstVal = Perc;
					});
					//alert(CurrParIdVal);
					//alert(RowArr.join("*"));
					var RowAction = 1; //alert("Y="+FirstVal);
					var PercArrLen = RowArr.length; //alert(PercArrLen);
					if(PercArrLen == 1){ //alert(123);
						//var FirstVal = Perc; //alert(124);
						if(FirstVal == 0){	 
							RowAction = 0; 
						}
					}
					
					if(RowAction == 1){
						var ParChilRowCnt = $('.DpmPId'+CurrParIdVal).length; 
						$('.DpmPId'+CurrParIdVal).find(".DpmPerc").prop("readonly", true); 
						$('.DpmPId'+CurrParIdVal).find(".DpmPerc").addClass("ronly"); 
						$('.DpmPId'+CurrParIdVal).find(".DpmQty").prop("readonly", true); 
						$('.DpmPId'+CurrParIdVal).find(".DpmQty").addClass("ronly");
						$('.DpmPId'+CurrParIdVal).find(".DpmPerc").val("");
						$('.DpmPId'+CurrParIdVal).find(".DpmAmt").val("");  
						var dataid = rbn;//$(this).attr("data-id");
						//var rbn = rbn;//$(this).attr("data-id");
						//alert(rbn);
						var Rate = Number($('#txt_rate').val()); 
							Rate = Rate.toFixed(2);
						var PaidPerc = $('.DpmPId'+CurrParIdVal).find(".DpmPaidPerc").val();  
						var ParMbId = $('.DpmPId'+CurrParIdVal).find(".DpmAddRow").attr('data-mbid');   
						RowArr.forEach(function(elementQty, keyPercent) {  
							//if(keyPercent != 0){
								if(ItemType == 'st'){ 
									elementQty = (elementQty / 1000);
									elementQty = elementQty.toFixed(Decimal);
								}
								if(ItemType == 's'){ 
									
								}
								
								var SelectedMBDid = MBDetIdStrArr[keyPercent]; //alert(SelectedMBDid);
								
								var SlmAmt = Number(elementQty)*Number(Rate)*Number(keyPercent)/100;  
									SlmAmt = SlmAmt.toFixed(2); //alert("A = "+SlmAmt+" :: R = "+Rate+" :: % = "+keyPercent+" :: Q = "+elementQty);
								//$('.DpmPId'+CurrParIdVal).closest( "tr" ).after("<tr class='LDPMROW"+rbn+"'><td><input type='text' name='ChiDpmRAB"+rbn+"[]' class='form-control small-tbox ronly tbox-c' readonly='' value='"+rbn+"'/></td><td><input name='ChiDpmAddQty"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty"+rbn+"' data-rbn='"+rbn+"' value='"+elementQty+"' /></td><td><input name='ChiDpmAddRate"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='"+Rate+"' /> </td><td><input name='ChiDpmPaidPerc"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ronly ResCls' readonly='' value='"+PaidPerc+"'></td><td><input name='ChiDpmAddPerc"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' data-rbn='"+rbn+"' value='"+keyPercent+"'></td><td><input name='ChiDpmAddAmount"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='"+SlmAmt+"'></td><td><i class='fa faOk DpmDelRow DpmDelRow"+rbn+"' data-rbn='"+rbn+"'>&#xf058;</i><input type='hidden' name='ChiParDpmMbId"+rbn+"PID"+CurrParIdVal+"[]' value='"+ParMbId+"'><input type='hidden' name='ChiParDpmMbIdList"+rbn+"PID"+CurrParIdVal+"[]' value='"+SelectedMBDid+"'></td></tr>");
								$('.DpmPId'+CurrParIdVal).closest( "tr" ).after("<tr class='LDPMROW"+rbn+"'><td><input type='text' name='ChiDpmRAB"+rbn+"[]' class='form-control small-tbox ronly tbox-c' readonly='' value='"+rbn+"'/></td><td><input name='ChiDpmAddQty"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmQty DpmQty"+rbn+"' data-rbn='"+rbn+"' value='"+elementQty+"' /></td><td><input name='ChiDpmAddRate"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox ronly tbox-r DpmRate' readonly='' value='"+Rate+"' /> </td><td><input name='ChiDpmAddPerc"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ResCls DpmPerc' readonly='' data-rbn='"+rbn+"' value='"+keyPercent+"'></td><td><input name='ChiDpmAddAmount"+rbn+"PID"+CurrParIdVal+"[]' type='text' class='form-control input-md small-tbox tbox-r ronly DpmAmt' readonly='' value='"+SlmAmt+"'></td><td><i class='fa faOk DpmDelRow DpmDelRow"+rbn+"' data-rbn='"+rbn+"'>&#xf058;</i><input type='hidden' name='ChiParDpmMbId"+rbn+"PID"+CurrParIdVal+"[]' value='"+ParMbId+"'><input type='hidden' name='ChiParDpmMbIdList"+rbn+"PID"+CurrParIdVal+"[]' value='"+SelectedMBDid+"'></td></tr>");
							
							//}
							
						});
					}
					
				});	//alert(3);
				
				
				
				
			}

			var ResArr = [];
			var Res = $('#txt_result').val(); 
			var ResSplit = Res.split("*");
				ResSplit.push(rbn);
			for(var k=0; k<ResSplit.length; k++){
				var CurrRbn = ResSplit[k];
				if(CurrRbn != ""){
					if(ResArr.indexOf(CurrRbn) === -1){ ResArr.push(CurrRbn); }
				}
			}
			//alert(ResArr.join("*"));
			$('#txt_result').val(ResArr.join("*"));
			//$(".loaddiv").hide();
			CalculateTotalAmounts();	
	  		i++;
			//CalculateTotalAmounts();
			//$("#pageload").addClass("pageload");
			
		});

		$('.BalanceBtn').click(function(event) {
			//BootstrapDialog.confirm("Are you sure wnat to save");
			$(".loaddiv").show();
		});
		function CalculateTotalAmounts(){
			var TotalSlmAmount = 0;
			$(".SlmAmt").each(function(){
				var Amount = $(this).val();
				TotalSlmAmount = Number(TotalSlmAmount) + Number(Amount);
			});
			TotalSlmAmount = TotalSlmAmount.toFixed(2);
			$('#SLMTotalAmt').html(TotalSlmAmount);
			
			var TotalDpmAmount = 0;
			$(".DpmAmt").each(function(){
				var Amount = $(this).val();
				TotalDpmAmount = Number(TotalDpmAmount) + Number(Amount);
			});
			TotalDpmAmount = TotalDpmAmount.toFixed(2);
			$('#DPMTotalAmt').html(TotalDpmAmount);
			
			var TotalUptoAmount = Number(TotalSlmAmount) + Number(TotalDpmAmount);
			TotalUptoAmount = TotalUptoAmount.toFixed(2);
			$('#UPTOTotalAmt').html(TotalUptoAmount);
			$('#TotalPayableAmt').html(TotalUptoAmount);
			
		}
		
		/*$('#example tbody').on('click', 'tr', function() {
		  	var $row = table.row(this).nodes().to$(),currentInputValue = $row.find('td:eq(0) input').val()
		  	alert(currentInputValue)
		})*/
		
		//$(".loaddiv").hide();
	});
	
	
     $(window).load(function() { //alert();
	 	var SLMTotalPayAmt = $('#txt_slm_payable_amt').val();
		var DPMTotalPayAmt = $('#txt_dpm_payable_amt').val();
		var UPTOTotalPayAmt = $('#txt_upto_payable_amt').val();
		$('#SLMTotalAmt').text(SLMTotalPayAmt);
		$('#DPMTotalAmt').text(DPMTotalPayAmt);
		$('#UPTOTotalAmt').text(UPTOTotalPayAmt);
		$(".loaddiv").hide();
	 });
	
</script>
<style>
	table.dataTable tbody td {
    	padding: 2px 2px;
		color:#0234B7;
		font-size:12px;
		font-weight:600;
		font-family:Cambria;
	}
</style>
