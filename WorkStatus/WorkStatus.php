<?php
//session_start();
@ob_start();
require_once '../Accounts/library/config.php';
require_once '../Accounts/library/functions.php';
checkUser();
include "../Accounts/library/common.php";
$staffid = $_SESSION['sid'];
$userid  = $_SESSION['userid'];
function IndianMoneyFormat($amount){
	$amt1 = number_format($amount, 2, '.', '');
	$amt2 = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amt1);
	return $amt2;
}
/*function dt_display($ddmmyyyy){
	$dt	= explode('-',$ddmmyyyy);
	$dd	= $dt[2];
	$mm	= $dt[1];
	$yy	= $dt[0];
	return $dd.'/'.$mm.'/'.$yy;
}*/
$View = "W";
if(isset($_POST['BtnWorkData'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "W";
}
if(isset($_POST['BtnCompRab'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "R";
}
if(isset($_POST['BtnMbDt'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "M";
}
if(isset($_POST['BtnIQDt'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "I";
}

if(isset($_POST['EMDData'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "EMD";
}
if(isset($_POST['CSTData'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "CST";
}
if(isset($_POST['PGData'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "PG";
}
if(isset($_POST['SDData'])){
	$sheetid = $_POST['txt_sheetid'];
	$View = "SD";
}


if(isset($_GET['work']) != ""){
	$sheetid = $_GET['work'];
}
//echo $View;exit;
$RowCnt = 0; $UnderCivilIdArr = array(); $UtilizQtyArr = array(); $HoaArr = array(); $HoaStr = ''; 
$MopArr = array(); $GMBArr = array(); $SMBArr = array(); $AMBArr = array(); $EMBArr = array(); 
$EmDDataArr = array(); $RowSpanArr = array(); $PGDataArr=array();


if(($sheetid != '')&&($sheetid != NULL)){
	$SelectQuery = "SELECT * FROM sheet WHERE sheet_id = '$sheetid'";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCnt = 1;
			$SheetList 		= mysqli_fetch_object($SelectSql);
			$WorkName 		= $SheetList->work_name;
			$WorkCcno 		= $SheetList->computer_code_no;
			$WorkTsNo 		= $SheetList->tech_sanction;
			$WorkOrderNo 	= $SheetList->work_order_no;
			$WorkAgmtNo 	= $SheetList->agree_no;
			$WorkAgmtDt 	= dt_display($SheetList->agree_date);
			$WorkOrderDt 	= dt_display($SheetList->work_order_date);
			$WorkOrderAmt 	= $SheetList->work_order_cost;
			$WorkCommDt 	= dt_display($SheetList->work_commence_date);
			$WorkDoc 		= $SheetList->date_of_completion;
			$WorkDuration 	= $SheetList->work_duration;
			$WorkHoaId 		= $SheetList->hoaid;
			$WorkEic 		= $SheetList->eic;
			$WorkGlobId 	= $SheetList->globid;
			$WorkTrId 		= $SheetList->tr_id;
			$WorkEicName	= "";
			$SelectQuery1A = "SELECT * FROM staff WHERE staffid = '$WorkEic'";
			$SelectSql1A = mysqli_query($dbConn,$SelectQuery1A);
			if($SelectSql1A == true){
				if(mysqli_num_rows($SelectSql1A)>0){
					$List1A = mysqli_fetch_object($SelectSql1A);
					$WorkEicName = $List1A->staffname;
				}
			}
			
			$SelectQuery1 = "SELECT * FROM sheet WHERE under_civil_sheetid = '$sheetid'";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						array_push($UnderCivilIdArr,$List1->sheet_id);
					}
				}
			}
			if(count($UnderCivilIdArr)>0){
				$UnderCivilIdStr = implode(",",$UnderCivilIdArr);
			}else{
				$UnderCivilIdStr = "";
			}
			if(($WorkHoaId != '')&&($WorkHoaId != NULL)){
				$SelectQuery1B = "select * from hoa_master where hoamast_id IN ($WorkHoaId)";
				$SelectSql1B = mysqli_query($dbConn,$SelectQuery1B);
				if($SelectSql1B == true){
					if(mysqli_num_rows($SelectSql1B)>0){
						while($List1B = mysqli_fetch_object($SelectSql1B)){
							$HoaNo = $List1B->new_hoa_no;
							array_push($HoaArr,$HoaNo);
						}
					}
				}
			}
		}
	}
	if(($WorkHoaId != '')&&($WorkHoaId != NULL)){
		$HoaStr = implode("] , [",$HoaArr);
	}
	if($View == "R"){
		$SelectQueryA = "SELECT * FROM abstractbook WHERE sheetid = '$sheetid'";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA);
		if($SelectSqlA == true){
			if(mysqli_num_rows($SelectSqlA)>0){
				$RowCnt = 1;
			}
		}
		if($UnderCivilIdStr != ''){
			$UndCivWhCla = " OR sheetid IN($UnderCivilIdStr))";
		}else{
			$UndCivWhCla = ")";
		}
		$SelectQueryA = "SELECT rbn, 
		SUM(slm_total_amount) AS slm_total_amount, SUM(dpm_total_amount) AS dpm_total_amount, SUM(upto_date_total_amount) AS upto_date_total_amount, 
		SUM(secured_adv_amt) AS secured_adv_amt, SUM(slm_total_amount_esc) AS slm_total_amount_esc, SUM(mob_adv_amt) AS mob_adv_amt, 
		SUM(pl_mac_adv_amt) AS pl_mac_adv_amt, SUM(hire_charges) AS hire_charges  
		FROM abstractbook WHERE (sheetid = '$sheetid' ".$UndCivWhCla." GROUP BY rbn ORDER BY rbn ASC";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA); //echo $SelectQueryA;exit;
		if($SelectSqlA == true){
			$RowCnt = 1;
		}
		
		$SelectQueryA1 = "SELECT * FROM memo_payment_accounts_edit WHERE sheetid = '$sheetid'";
		$SelectSqlA1   = mysqli_query($dbConn,$SelectQueryA1);
		if($SelectSqlA1 == true){
			if(mysqli_num_rows($SelectSqlA1)>0){
				while($ListA1 = mysqli_fetch_object($SelectSqlA1)){
					$MopArr[$ListA1->rbn]['CGST'] 	= $ListA1->cgst_tds_amt;
					$MopArr[$ListA1->rbn]['SGST'] 	= $ListA1->sgst_tds_amt;
					$MopArr[$ListA1->rbn]['IGST'] 	= $ListA1->igst_tds_amt;
					$MopArr[$ListA1->rbn]['IT'] 	= $ListA1->incometax_amt;
					$MopArr[$ListA1->rbn]['LCESS'] 	= $ListA1->lw_cess_amt;
					$MopArr[$ListA1->rbn]['SD'] 	= $ListA1->sd_amt;
					$MopArr[$ListA1->rbn]['WC'] 	= $ListA1->water_cost;
					$MopArr[$ListA1->rbn]['EC'] 	= $ListA1->electricity_cost;
					$MopArr[$ListA1->rbn]['MOB'] 	= $ListA1->mob_adv_amt_rec;
					$MopArr[$ListA1->rbn]['MOBINT'] = $ListA1->mob_adv_int_amt;
					$MopArr[$ListA1->rbn]['PM'] 	= $ListA1->pl_mac_adv_rec;
					$MopArr[$ListA1->rbn]['PMINT'] 	= $ListA1->pl_mac_adv_int_amt;
					$MopArr[$ListA1->rbn]['HIRE'] 	= $ListA1->hire_charges;
					$MopArr[$ListA1->rbn]['OTH'] 	= $ListA1->other_recovery_1_amt+$ListA1->other_recovery_2_amt+$ListA1->other_recovery_3_amt;
				}
			}
		}
	}
	if($View == "I"){
		$SelectQueryA = "SELECT * FROM schdule WHERE sheet_id = '$sheetid' AND total_quantity != '' AND total_quantity != '0' AND total_quantity != 0 AND rate != 0";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA);
		if($SelectSqlA == true){
			if(mysqli_num_rows($SelectSqlA)>0){
				$RowCnt = 1;
			}
		}
		
		$SelectQueryB = "SELECT subdivid, SUM(mbtotal) AS used_qty FROM measurementbook WHERE sheetid = '$sheetid' AND subdivid != 0 AND (part_pay_flag = '0' OR part_pay_flag = '1') GROUP BY subdivid";
		$SelectSqlB   = mysqli_query($dbConn,$SelectQueryB); //echo $SelectQueryA;exit;
		if($SelectSqlB == true){
			if(mysqli_num_rows($SelectSqlB)>0){
				while($ListB = mysqli_fetch_object($SelectSqlB)){
					$UtilizQtyArr[$ListB->subdivid] = $ListB->used_qty;
				}
			}
		}
	}
	
	if($View == "M"){
		$SelectQueryA = "SELECT a.*,b.mbooktype FROM mbookallotment a INNER JOIN agreementmbookallotment b ON (a.allotmentid = b.allotmentid) WHERE a.sheetid = '$sheetid' ORDER BY mbno";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA); //echo $SelectQueryA;exit;
		if($SelectSqlA == true){
			if(mysqli_num_rows($SelectSqlA)>0){
				while($ListA = mysqli_fetch_object($SelectSqlA)){
					if($ListA->mbooktype == "G"){
						array_push($GMBArr,$ListA->mbno);
					}
					if($ListA->mbooktype == "S"){
						array_push($SMBArr,$ListA->mbno);
					}
					if($ListA->mbooktype == "A"){
						array_push($AMBArr,$ListA->mbno);
					}
					if($ListA->mbooktype == "E"){
						array_push($EMBArr,$ListA->mbno);
					}
				}
			}
		}
	}
	
	if($View == "EMD"){
		$MasterQuery = "SELECT * FROM emd_master 
				INNER JOIN emd_detail ON emd_master.emid = emd_detail.emid
				INNER JOIN tender_register ON emd_master.tr_id = tender_register.tr_id 
				INNER JOIN contractor ON emd_detail.contid = contractor.contid 
				WHERE tender_register.globid = '$WorkGlobId' AND emd_master.globid = '$WorkGlobId' 
				ORDER BY emd_master.tr_id ASC, emd_detail.contid ASC";
		$MasterResult = mysqli_query($dbConn,$MasterQuery);
		if($MasterResult == true){
			if(mysqli_num_rows($MasterResult)>0){
				$RowCnt = 1;
				while($List = mysqli_fetch_object($MasterResult)){
					if(isset($RowSpanArr[$List->tr_id][$List->contid])){
						$RowSpanArr[$List->tr_id][$List->contid] = $RowSpanArr[$List->tr_id][$List->contid] + 1;
					}else{
						$RowSpanArr[$List->tr_id][$List->contid] = 1;
					}
					$EmDDataArr[] = $List;
				}
			}
		}
	}
	
	if($View == "PG"){
		$MasterQuery = "SELECT a.*, b.*, c.tr_no, c.work_name, d.name_contractor FROM loi_entry a 
		INNER JOIN bg_fdr_details b ON (a.loa_pg_id = b.master_id) 
		INNER JOIN tender_register c ON (a.tr_id = c.tr_id) 
		INNER JOIN contractor d ON (a.contid = d.contid) WHERE 
		c.globid = '$WorkGlobId' AND a.globid = '$WorkGlobId' AND b.inst_purpose='PG'";
		$MasterResult = mysqli_query($dbConn,$MasterQuery);
		if($MasterResult == true){
			if(mysqli_num_rows($MasterResult)>0){
				$RowCnt = 1;
				while($List = mysqli_fetch_object($MasterResult)){
					if(isset($RowSpanArr[$List->tr_id][$List->contid])){
						$RowSpanArr[$List->tr_id][$List->contid] = $RowSpanArr[$List->tr_id][$List->contid] + 1;
					}else{
						$RowSpanArr[$List->tr_id][$List->contid] = 1;
					}
					$PGDataArr[] = $List;
				}
			}
		}
	}
	
	if($View == "SD"){
		$MasterQuery = "SELECT a.*, b.name_contractor, c.* FROM sheet a 
		INNER JOIN contractor b ON (a.contid = b.contid)  
		INNER JOIN bg_fdr_details c ON (a.sheet_id = c.master_id) WHERE 
		a.sheet_id = '$sheetid' AND c.globid = '$WorkGlobId' AND c.inst_purpose='SD'";
		$MasterResult = mysqli_query($dbConn,$MasterQuery);
		if($MasterResult == true){
			if(mysqli_num_rows($MasterResult)>0){
				$RowCnt = 1;
				while($List = mysqli_fetch_object($MasterResult)){
					if(isset($RowSpanArr[$List->tr_id][$List->contid])){
						$RowSpanArr[$List->tr_id][$List->contid] = $RowSpanArr[$List->tr_id][$List->contid] + 1;
					}else{
						$RowSpanArr[$List->tr_id][$List->contid] = 1;
					}
					$PGDataArr[] = $List;
				}
			}
		}
	}
	
	if($View == "CST"){
		$ContArr  	 		=  array();
		$ContNameArr 		= array();
		$RebatePercArr 		= array();
		$NegoRebatePercArr 	= array();
		$TRId = $WorkTrId;
		$GlobIDQuery = "SELECT globid, ccno, nego_status, work_name FROM tender_register WHERE tr_id = '$TRId'";
		$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
		if($GlobIDSql == true){
			if(mysqli_num_rows($GlobIDSql)>0){
				$List = mysqli_fetch_object($GlobIDSql);
				$GlobID = $List->globid;
				$CCno = $List->ccno;
				$WorkName = $List->work_name;
				$Negost = $List->nego_status;
			}
		}
		$NegoContArr = array();
		$SelectQuery = "SELECT * FROM bidder_bid_master where tr_id = '$TRId' ORDER BY quoted_amt_af_reb ASC";
		// /echo $SelectQuery;
		$ResultQuery = mysqli_query($dbConn,$SelectQuery);
		if($ResultQuery==true){
			if(mysqli_num_rows($ResultQuery)>0){
				while($Result = mysqli_fetch_object($ResultQuery)){
					$ContId = $Result->contid;
					$GlobId = $Result->globid;
					$ISNego = $Result->is_negotiate;
					$RebatePer= $Result->rebate_perc;
					$NegoRebatePer= $Result->negotiate_rebate_perc;
					array_push($ContArr,$ContId);
					if($Result->is_negotiate == 'Y'){
						array_push($NegoContArr,$ContId);
					}
					$SeclectQuery1 = "SELECT name_contractor FROM contractor where contid = '$ContId'";
					$ResultQuery1  = mysqli_query($dbConn,$SeclectQuery1);
					if($ResultQuery1 == true){
						if(mysqli_num_rows($ResultQuery1)>0){
							$Result1 = mysqli_fetch_object($ResultQuery1);
							$ContName= $Result1->name_contractor;
							$ContNameArr[$ContId] = $ContName;
							$RebatePercArr[$ContId] = $RebatePer;
							$NegoRebatePercArr[$ContId] = $NegoRebatePer;
						}
					}
				}
			}
		}
		
		$BidderRateArr  = array();
		$NegoBidderRateArr  = array();
		//$SelectSqlQuery = "SELECT * FROM bidder_bid_details WHERE globid = '$GlobID' AND tr_id = '$TRId'";
		$SelectSqlQuery = "SELECT * FROM bidder_bid_details where tr_id = '$TRId' order by bdid asc";
		//echo $SelectSqlQuery;
		$ResultSqlQuery = mysqli_query($dbConn,$SelectSqlQuery);
		if($ResultSqlQuery == true){
			if(mysqli_num_rows($ResultSqlQuery)>0){
				while($CList=mysqli_fetch_object($ResultSqlQuery)){
					$ContractId 	= $CList->contid;
					$ItemNo     	= $CList->item_no;
					$ItemRate   	= $CList->item_rate;
					$NegoItemRate	= $CList->negotiate_rate;
					$BidderRateArr[$ContractId][$ItemNo]= $ItemRate;
					if(in_array($ContractId, $NegoContArr)){
						$NegoBidderRateArr[$ContractId][$ItemNo] = $NegoItemRate;
					}
				}
			}
		}
	}
	
}



if(isset($_POST['btn_back']) == " Back "){
	header('Location: ../Accounts/MyWorks.php?page=WORK');
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include("CSSLibrary.php"); ?>
</head>
<style>
	.f1{
		font-weight:500 !important;
		vertical-align:middle !important;
	}
	.tableb th{
		border:1px solid #B3B8BD !important;
		border-collapse:collapse !important;
		vertical-align:middle !important;
	}
</style>
<body class="mini-navbar">
<form style="height:100%;" method="post" action="">

    <div class="left-sidebar-pro">
        <nav id="sidebar" class="active">
            <div class="sidebar-header" style="padding-top:2px;">
               <strong><a href="index.html"><img src="../images/igcar_logo_1.png" alt="" style="height:57px;"/></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
              <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1" style="margin-bottom:80px;">
						 
						<li>
							<a title="Deduct Previous Measurement" style="padding: 5px 2px;">
								<button type="submit" name="EMDData" class="btn btn-custon-four btn-default colorA2" style="color:#0551E5;">EMD</button>
							</a>
						</li>
						<li>
							<a title="Deduct Previous Measurement" style="padding: 5px 2px;">
								<button type="submit" name="CSTData" class="btn btn-custon-four btn-default colorA1" style="color:#0551E5;">CST&nbsp;</button>
							</a>
						</li>
						<li>
							<a title="Deduct Previous Measurement" style="padding: 5px 2px;">
								<button type="submit" name="PGData" class="btn btn-custon-four btn-default colorA3" style="color:#0551E5;">&nbsp;PG&nbsp;&nbsp;</button>
							</a>
						</li>
						<li>
							<a title="Deduct Previous Measurement" style="padding: 5px 2px;">
								<button type="submit" name="SDData" class="btn btn-custon-four btn-default colorA4" style="color:#0551E5;">&nbsp;SD&nbsp;&nbsp;</button>
							</a>
						</li>
                    </ul>
					<ul>&nbsp;</ul>
                </nav>
            </div>
        </nav>
    </div>

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
														<span class="badge col-md-12 desc-title" style="text-align:left">
															<span class="badge" style="background:#F5F8FA; color:#000;">&nbsp;CCNO : <?php echo $WorkCcno; ?>&nbsp;</span> <?php echo $WorkName; ?>
														</span>
														<!--<span class="badge col-md-1" style="background:none; margin-top:-1px;">
															<span class="badge desc-title" style="background:#F5F8FA; color:#000; padding:0px 5px;">
																<font style="margin-top:1px; font-size:12px;">Item & Qty Details <?php //echo $SLMItemQty; ?> <?php //echo $ItemUnit; ?>&nbsp; </font>
																<i class="fa fa-info-circle title-info" id="QtyInfo" data-id="<?php echo $sheetid; ?>"></i>
															</span> 
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
                                        <button type="submit" name="BtnWorkData" class="btn btn-custon-four btn-default colorG<?php if($View == "W"){ ?> active <?php } ?>">Click Here to View Work Details</button>
										<button type="submit" name="BtnCompRab" class="btn btn-custon-four btn-default colorG<?php if($View == "R"){ ?> active <?php } ?>">Click Here to View Completed RAB Details</button>
										<button type="submit" name="BtnMbDt" class="btn btn-custon-four btn-default colorG<?php if($View == "M"){ ?> active <?php } ?>">Click Here to View MBook Details</button>
										<button type="submit" name="BtnIQDt" class="btn btn-custon-four btn-default colorG<?php if($View == "I"){ ?> active <?php } ?>">Click Here to View Item Qty. Details</button>
                                    </div>
                                </div>
                            </div>
							<div style="height:5px;"></div>
							<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>">
							
                        </div>
                    </div>
					<div class="col-md-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;">
						<div class="hpanel">
							<div class="panel-body no-padding">
								<div class="row">
									<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
										<div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15 col-md-12" style="padding-top:0px;">
											<div class="ib-tb BalanceDetails" style="overflow-x:auto;">
												<?php if(($RowCnt == 1)&&($View == "W")){ ?>
												<table class="table">
													<thead>
														<tr>
															<th nowrap="nowrap" style="vertical-align:middle">Name of Work</th>
															<th colspan="5" class="f1">: <?php echo $WorkName; ?></th>
														</tr>
														<tr>
															<th style="vertical-align:middle">TS No.</th>
															<th class="f1">: <?php echo $WorkTsNo; ?></th>
															<th style="vertical-align:middle">Agreement No.</th>
															<th class="f1">: <?php echo $WorkAgmtNo; ?></th>
															<th style="vertical-align:middle">Agreement Date</th>
															<th class="f1">: <?php echo $WorkAgmtDt; ?></th>
														</tr>
														<tr>
															<th style="vertical-align:middle" nowrap="nowrap">Work Order No.</th>
															<th class="f1">: <?php echo $WorkOrderNo; ?></th>
															<th style="vertical-align:middle">Work Order Date</th>
															<th class="f1">: <?php echo $WorkOrderDt; ?></th>
															<th style="vertical-align:middle">Work Order Amount</th>
															<th class="f1" nowrap="nowrap">: <?php echo $WorkOrderAmt; ?></th>
														</tr>
														<tr>
															<th style="vertical-align:middle" nowrap="nowrap">Work Duration</th>
															<th class="f1">: <?php echo $WorkDuration; ?></th>
															<th style="vertical-align:middle" nowrap="nowrap">Work Commence Date</th>
															<th class="f1">: <?php echo $WorkCommDt; ?></th>
															<th style="vertical-align:middle">Scheduled Completion Date</th>
															<th class="f1">: <?php echo $WorkDoc; ?></th>
														</tr>
														<tr>
															<th style="vertical-align:middle">EIC</th>
															<th class="f1">: <?php echo $WorkEicName; ?></th>
															<th style="vertical-align:middle">HOA No.</th>
															<th class="f1" colspan="3">: <?php if($HoaStr != ''){ echo "[".$HoaStr."]"; } ?></th>
															
															<th></th>
															<th></th>
														</tr>
													</thead>
												</table>
												<?php } ?>
												<?php if($View == "R"){ ?>
												<table class="table tableb">
													<thead>
														<tr>
															<th rowspan="2" style="text-align:center">RAB</th>
															<th rowspan="2" style="text-align:center">Upto Date Value</th>
															<th rowspan="2" style="text-align:center">Deduct Previous Value</th>
															<th rowspan="2" style="text-align:center">Sincle Last Bill Value</th>
															<th rowspan="2" style="text-align:center">Secured Advance</th>
															<th rowspan="2" style="text-align:center">Escalation</th>
															<th rowspan="2" style="text-align:center">Mobilization Advance</th>
															<th rowspan="2" style="text-align:center">P&M Advance</th>
															<th rowspan="2" style="text-align:center">Hire Charges</th>
															<th colspan="14" style="text-align:center">Recoveries</th>
														</tr>
														<tr>
															<th style="text-align:center">CGST</th>
															<th style="text-align:center">SGST</th>
															<th style="text-align:center">IGST</th>
															<th style="text-align:center">IT</th>
															<th style="text-align:center">LCESS</th>
															<th style="text-align:center">SD</th>
															<th style="text-align:center">Water Charges</th>
															<th style="text-align:center">Elect. Charges</th>
															<th style="text-align:center">Mob. Adv. Rec.</th>
															<th style="text-align:center">Mob. Adv. Int.</th>
															<th style="text-align:center">P&M Adv. Rec.</th>
															<th style="text-align:center">P&M. Adv. Int.</th>
															<th style="text-align:center">Hire Charges</th>
															<th style="text-align:center">Other Rec.</th>
														</tr>
														<?php if($RowCnt == 1){ while($ListA = mysqli_fetch_object($SelectSqlA)){ ?>
														<tr>
															<th class="f1" style="text-align:center"><?php echo $ListA->rbn; ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->upto_date_total_amount != 0){ echo $ListA->upto_date_total_amount; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->dpm_total_amount != 0){ echo $ListA->dpm_total_amount; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->slm_total_amount != 0){ echo $ListA->slm_total_amount; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->secured_adv_amt != 0){ echo $ListA->secured_adv_amt; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->slm_total_amount_esc != 0){ echo $ListA->slm_total_amount_esc; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->mob_adv_amt != 0){ echo $ListA->mob_adv_amt; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->pl_mac_adv_amt != 0){ echo $ListA->pl_mac_adv_amt; } ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->hire_charges != 0){ echo $ListA->hire_charges; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['CGST'] != 0){ echo $MopArr[$ListA->rbn]['CGST']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['SGST'] != 0){ echo $MopArr[$ListA->rbn]['SGST']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['IGST'] != 0){ echo $MopArr[$ListA->rbn]['IGST']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['IT'] != 0){ echo $MopArr[$ListA->rbn]['IT']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['LCESS'] != 0){ echo $MopArr[$ListA->rbn]['LCESS']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['SD'] != 0){ echo $MopArr[$ListA->rbn]['SD']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['WC'] != 0){ echo $MopArr[$ListA->rbn]['WC']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['EC'] != 0){ echo $MopArr[$ListA->rbn]['EC']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['MOB'] != 0){ echo $MopArr[$ListA->rbn]['MOB']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['MOBINT'] != 0){ echo $MopArr[$ListA->rbn]['MOBINT']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['PM'] != 0){ echo $MopArr[$ListA->rbn]['PM']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['PMINT'] != 0){ echo $MopArr[$ListA->rbn]['PMINT']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['HIRE'] != 0){ echo $MopArr[$ListA->rbn]['HIRE']; } ?></th>
															<th class="f1" style="text-align:right"><?php if($MopArr[$ListA->rbn]['OTH'] != 0){ echo $MopArr[$ListA->rbn]['OTH']; } ?></th>
														</tr>
														<?php } } ?>
													</thead>
												</table>
												<?php } ?>
												
												<?php if($View == "I"){ ?>
												<table class="table tableb">
													<thead>
														<tr>
															<th style="text-align:center">SNo.</th>
															<th style="text-align:center">Item No.</th>
															<th style="text-align:center">Item Description</th>
															<th style="text-align:center">Agreement Qty.</th>
															<th style="text-align:center">Deviation Qty.</th>
															<th style="text-align:center">Total Qty.</th>
															<th style="text-align:center">Utilized Qty.</th>
															<th style="text-align:center">Balance Qty.</th>
														</tr>
														<?php $Sno = 1; if($RowCnt == 1){ while($ListA = mysqli_fetch_object($SelectSqlA)){ ?>
														<tr>
															<th class="f1" style="text-align:center"><?php echo $Sno; ?></th>
															<th class="f1" style="text-align:center"><?php echo $ListA->sno; ?></th>
															<th class="f1" align="center"><?php echo $ListA->description; ?></th>
															<th class="f1" style="text-align:right"><?php if($ListA->total_quantity != 0){ echo $ListA->total_quantity; } ?></th>
															<th class="f1" style="text-align:right">
															<?php 
															$DevQty = round(($ListA->total_quantity * $ListA->deviate_qty_percent / 100),$ListA->decimal_placed);
															if($DevQty != 0){ echo $DevQty;  }
															$TotalQty =  round(($ListA->total_quantity + $DevQty),$ListA->decimal_placed);
															$UtilizedQty = $UtilizQtyArr[$ListA->subdiv_id];
															$BalanceQty = round(($TotalQty - $UtilizedQty),$ListA->decimal_placed);
															?>
															</th>
															<th class="f1" style="text-align:right"><?php if($TotalQty != 0){ echo $TotalQty; } ?></th>
															<th class="f1" style="text-align:right"><?php echo $UtilizedQty; ?></th>
															<th class="f1" style="text-align:right"><?php if($BalanceQty != 0){ echo $BalanceQty; } ?></th>
														</tr>
														<?php $Sno++; } } ?>
													</thead>
												</table>
												<?php } ?>
												
												<?php if($View == "M"){ ?>
												<div class="row col-md-12">
													<div class="col-md-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">
														<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
															<div class="card card-pricing text-center px-3 mb-4">
																<span class="center-header head-color1">General MBooks</span>
																<div class="card-body pb-4">
																	<table class="table table-bordered dettab table-hover">
																		<thead>
																			<tr>
																				<th style="text-align:center">Sno.</th>
																				<th style="text-align:center">MBook No.</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php $GSno = 1; if(count($GMBArr)>0){ foreach($GMBArr as $GMB){ ?>
																			<tr>
																				<td><?php echo $GSno; ?></td>
																				<td><?php echo $GMB; ?></td>
																			</tr>
																		<?php $GSno++; } } ?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">
														<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
															<div class="card card-pricing text-center px-3 mb-4">
																<span class="center-header head-color2">Steel MBooks</span>
																<div class="card-body pb-4">
																	<table class="table table-bordered dettab table-hover">
																		<thead>
																			<tr>
																				<th style="text-align:center">Sno.</th>
																				<th style="text-align:center">MBook No.</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php $SSno = 1; if(count($SMBArr)>0){ foreach($SMBArr as $SMB){ ?>
																			<tr>
																				<td><?php echo $SSno; ?></td>
																				<td><?php echo $SMB; ?></td>
																			</tr>
																		<?php $SSno++; } } ?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">
														<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
															<div class="card card-pricing text-center px-3 mb-4">
																<span class="center-header head-color3">Abstract MBooks</span>
																<div class="card-body pb-4">
																	<table class="table table-bordered dettab table-hover">
																		<thead>
																			<tr>
																				<th style="text-align:center">Sno.</th>
																				<th style="text-align:center">MBook No.</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php $ASno = 1; if(count($AMBArr)>0){ foreach($AMBArr as $AMB){ ?>
																			<tr>
																				<td><?php echo $ASno; ?></td>
																				<td><?php echo $AMB; ?></td>
																			</tr>
																		<?php $ASno++; } } ?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">
														<div class="pricing card-deck flex-column flex-md-row mb-3" style="margin-bottom:75px;">
															<div class="card card-pricing text-center px-3 mb-4">
																<span class="center-header head-color4">Escalation MBooks</span>
																<div class="card-body pb-4">
																	<table class="table table-bordered dettab table-hover">
																		<thead>
																			<tr>
																				<th style="text-align:center">Sno.</th>
																				<th style="text-align:center">MBook No.</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php $ESno = 1; if(count($EMBArr)>0){ foreach($EMBArr as $EMB){ ?>
																			<tr>
																				<td><?php echo $ESno; ?></td>
																				<td><?php echo $EMB; ?></td>
																			</tr>
																		<?php $ESno++; } } ?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
												
												<?php if(($RowCnt == 1)&&($View == "EMD")){ ?>
												<table class="table table-bordered dettab table-hover">
													<thead>
														<tr>
															<th rowspan="2" valign="middle">SNo.</th>
															<th rowspan="2" valign="middle">Tender No.</th>
															<th rowspan="2" valign="middle">Name of Work</th>
															<th rowspan="2" valign="middle">EMD Amount</br>( &#8377; )</th>
															<th rowspan="2" valign="middle">Contractor Name</th>
															<th colspan="5" valign="middle">EMD Details</th>
															<th rowspan="2" valign="middle">EMD Status</br>(Released or Not)</th>
														</tr>
														<tr>
															<th valign="middle">Instrument Type</th>
															<th valign="middle">Instrument No.</th>
															<th valign="middle">Date of Issue</th>
															<th valign="middle">Date of Expiry</th>
															<th valign="middle">Amount</br>( &#8377; )</th>
														</tr>
													</thead>
													<tbody>
														<?php
														
														$SNO = 1; $PrevTrId = ""; $PrevCont = "";
														if($RowCnt == 1){ foreach($EmDDataArr as $Emdkey => $EmdValue){ 
															
															$ContRowSpan = $RowSpanArr[$EmdValue->tr_id][$EmdValue->contid];
															$RowSpanArr1 = $RowSpanArr[$EmdValue->tr_id];
															$TrRowspan = array_sum($RowSpanArr1);
															if($PrevTrId != $EmdValue->tr_id){
																$x = 0; $PrevCont = ""; $y = 0;
															}
															if($PrevCont != $EmdValue->contid){
																$y = 0;
															}
															if($x == 0){
															?>
																<tr class='labeldisplay'>
																	<td rowspan="<?php echo $TrRowspan; ?>" class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																	<td rowspan="<?php echo $TrRowspan; ?>" valign='middle' class='tdrow' align = 'justify'><?php echo $EmdValue->tr_no; ?></td>
																	<td rowspan="<?php echo $TrRowspan; ?>" valign='middle' class='tdrow' align = 'justify'><?php echo $EmdValue->work_name; ?></td>
																	<td rowspan="<?php echo $TrRowspan; ?>" class='tdrow' align='right' valign='middle'><?php echo $EmdValue->emd_lot_amt; ?></td>
																	<td rowspan="<?php echo $ContRowSpan; ?>" class='tdrow' align='left' valign='middle'><?php echo $EmdValue->name_contractor; ?></td>
																	<td class='tdrow' align='center' valign='middle'><?php echo $EmdValue->inst_type; ?></td>
																	<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->inst_no; ?></td>
																	<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->issue_dt); ?></td>
																	<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->valid_dt); ?></td>
																	<td class='tdrow' align='right' valign='middle'><?php echo $EmdValue->emd_amt; ?></td>
																	<?php if($EmdValue->status == 'R'){ ?>
																		<td class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i> <?php echo "Released"; ?>&nbsp;</td>
																	<?php }else{ ?>
																		<td class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i> <?php echo "Not-Released"; ?>&nbsp;</td>
																	<?php } ?>
																</tr>
															<?php 
																$x++; $y++;  $SNO++;
															}else{
																
															?>
																<tr class='labeldisplay'>
																	<?php if($y == 0){ ?>
																	<td rowspan="<?php echo $ContRowSpan; ?>" class='tdrow' align='left' valign='middle'><?php echo $EmdValue->name_contractor; ?></td>
																	<?php } ?>
																	<td class='tdrow' align='center' valign='middle'><?php echo $EmdValue->inst_type; ?></td>
																	<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->inst_no; ?></td>
																	<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->issue_dt); ?></td>
																	<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->valid_dt); ?></td>
																	<td class='tdrow' align='right' valign='middle'><?php echo $EmdValue->emd_amt; ?></td>
																	<?php if($EmdValue->status == 'R'){ ?>
																		<td class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i> <?php echo "Released"; ?>&nbsp;</td>
																	<?php }else{ ?>
																		<td class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i> <?php echo "Not-Released"; ?>&nbsp;</td>
																	<?php } ?>
																</tr>
															<?php 
																$x++; $y++;
															}
														?>
													<?php $PrevTrId = $EmdValue->tr_id; $PrevCont = $EmdValue->contid; } } ?>
													</tbody>
												</table>
												<?php } ?>
												
												
												<?php if(($RowCnt == 1)&&($View == "PG")){ ?>
													<table class="table table-bordered dettab table-hover">
														<thead>
															<tr>
																<th rowspan="2" valign="middle">SNo.</th>
																<th rowspan="2" valign="middle">CCNo.</th>
																<th rowspan="2" valign="middle">Work Order No.</th>
																<th rowspan="2" valign="middle">Name of Work</th>
																<th rowspan="2" valign="middle">Contractor Name</th>
																<th colspan="5" valign="middle" style="text-align:center">PG Details</th>
																<th rowspan="2" valign="middle">Status</th>
															</tr>
															<tr>
																<th valign="middle">Instrument Type</th>
																<th valign="middle">Instrument No.</th>
																<th valign="middle">Date of Issue</th>
																<th valign="middle">Date of Expiry</th>
																<th valign="middle">Amount</br>( &#8377; )</th>
															</tr>
														</thead>
														<tbody>
															<?php $SNO = 1; $PrevTrId=""; $PrevContId="";
															//$EMDCountArr = array_count_values(array_column($MasterResult, 'inst_type'));
															if($RowCnt == 1){ foreach($PGDataArr as $PGkey => $PGValue){ 
																$ContRowSpan = $RowSpanArr[$PGValue->tr_id][$PGValue->contid];
																$RowSpanArr1 = $RowSpanArr[$PGValue->tr_id];
																$TrRowspan = array_sum($RowSpanArr1);
																if($PrevTrId != $PGValue->tr_id){
																	$x = 0; $PrevContId = ""; $y = 0;
																}
																if($PrevContId != $PGValue->contid){
																	$y = 0;
																}
																if($x == 0){ 
															?>
															<tr class='labeldisplay'>
																<td rowspan= <?php echo $TrRowspan ?> class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'center'><?php echo $WorksCCNoDataArr[$PGValue->globid]; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'left'><?php echo $WorksWONoDataArr[$PGValue->globid]; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_name; ?></td>
																<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																<td class='tdrow' align='right' valign='middle'><?php echo $PGValue->inst_amt; ?></td>
																<?php if($PGValue->inst_status == 'R'){ ?>
																	<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i><br><?php echo "Released"; ?></td>
																<?php }else{ ?>
																	<td align="center" rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i><br><?php echo "Not-Released"; ?></td>
																<?php } ?>
															</tr>
															<?php 
																	$x++; $y++;  $SNO++;
																}else{
															?>
															<tr class='labeldisplay'>
																<?php if($y == 0){ ?>
																	<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																<?php } ?>
																<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																<td class='tdrow' align='right' valign='middle'><?php echo $PGValue->inst_amt; ?></td>
															</tr>
																<?php 
																	$x++; $y++;
																}
															?>
															<?php $PrevTrId = $PGValue->tr_id; $PrevContId = $PGValue->contid; } } ?>
														</tbody>
													</table>
												<?php } ?>
												
												<?php if(($RowCnt == 1)&&($View == "SD")){ ?>
												
													<table class="table table-bordered dettab table-hover">
														<thead>
															<tr>
																<th rowspan="2" valign="middle">SNo.</th>
																<th rowspan="2" valign="middle">CCNo.</th>
																<th rowspan="2" valign="middle">Work Order No.</th>
																<th rowspan="2" valign="middle">Name of Work</th>
																<th rowspan="2" valign="middle">Contractor Name</th>
																<th colspan="5" valign="middle" style="text-align:center">SD Details</th>
																<th rowspan="2" valign="middle">Status</th>
															</tr>
															<tr>
																<th valign="middle">Instrument Type</th>
																<th valign="middle">Instrument No.</th>
																<th valign="middle">Date of Issue</th>
																<th valign="middle">Date of Expiry</th>
																<th valign="middle">Amount</br>( &#8377; )</th>
															</tr>
														</thead>
														<tbody>
															<?php $SNO = 1; $PrevTrId=""; $PrevContId="";
															//$EMDCountArr = array_count_values(array_column($MasterResult, 'inst_type'));
															if($RowCnt == 1){ foreach($PGDataArr as $PGkey => $PGValue){ 
																$ContRowSpan = $RowSpanArr[$PGValue->tr_id][$PGValue->contid];
																$RowSpanArr1 = $RowSpanArr[$PGValue->tr_id];
																$TrRowspan = array_sum($RowSpanArr1);
																if($PrevTrId != $PGValue->tr_id){
																	$x = 0; $PrevContId = ""; $y = 0;
																}
																if($PrevContId != $PGValue->contid){
																	$y = 0;
																}
																if($x == 0){ 
															?>
															<tr class='labeldisplay'>
																<td rowspan= <?php echo $TrRowspan ?> class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'center'><?php echo $PGValue->computer_code_no; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_order_no; ?></td>
																<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_name; ?></td>
																<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->inst_amt); ?></td>
																<?php if($PGValue->inst_status == 'R'){ ?>
																	<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i><br><?php echo "Released"; ?></td>
																<?php }else{ ?>
																	<td align='center' rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='right' valign='middle' nowrap='nowrap'><i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i><br><?php echo "Not-Released"; ?></td>
																<?php } ?>
															</tr>

															<?php 
																	$x++; $y++;  $SNO++;
																}else{
															?>
															<tr class='labeldisplay'>
																<?php if($y == 0){ ?>
																	<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																<?php } ?>
																<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->inst_amt); ?></td>
															</tr>
																<?php 
																	$x++; $y++;
																}
															?>
															<?php $PrevTrId = $PGValue->tr_id; $PrevContId = $PGValue->contid; } } ?>
														</tbody>
													</table>
												<?php } ?>
												
												
												<?php if($View == "CST"){ ?>
													<table class="table table-bordered dettab table-hover">
															<thead>
																<tr>
																	<th rowspan="2">Item No.</th>
																	<th rowspan="2" style="width:650px">Item Description</th>
																	<th rowspan="2">Item Qty</th>
																	<th rowspan="2">Item Unit</th>
																	<th colspan="2">Department Estimate</th>
																	<input type="hidden" name="txt_page_id" id="txt_page_id" value = "<?php if(isset($PageId)){ echo $PageId; } ?>" class="">
																	<input type="hidden" name="hid_txt_tr_id" id="hid_txt_tr_id" value = "<?php if(isset($TRId)){ echo $TRId; } ?>" class="">
																	<input type="hidden" name="txt_cst_nego_id" id="txt_cst_nego_id" value = "<?php if(isset($Negost)){ echo $Negost; } ?>" class="">
																	<?php 
																		//$NegoCont = null;
																		$DeptTotalAmount = 0;
																		foreach($ContArr as $ContractId){
																			$ContractName = $ContNameArr[$ContractId];
																			echo'<th colspan="3">'.$ContractName.'</th>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoCont = $NegoContArr[$ContractId];
																				echo'<th colspan="4">'.$ContractName.'<br/> <----- After Negotiation -----></th>';
																			}
																		}
																	?>
																</tr>
																<tr>
																	<th nowrap="nowrap">Rate <br/>( &#8377; )</th>
																	<th nowrap="nowrap">Amount <br/>( &#8377; )</th>
																	<?php 
																		$TotalAmount = array();
																		$NegoTotalAmount = array();
																		foreach($ContArr as $ContractId){
																			echo '<th>Rate <br/>( &#8377; )</th>';
																			echo '<th>Amount <br/>( &#8377; )</th>';
																			echo '<th>Variation <br/>( % )</th>';
																			$TotalAmount[$ContractId] = 0;
																			/*if(isset($NegoContArr[$ContractId])){
																				$NegoCont1 = $NegoContArr[$ContractId];
																			}
																			echo'<th colspan="3">'.$ContractName.'</th>';*/
																			if(in_array($ContractId, $NegoContArr)){
																				echo '<th>Rate <br/>( &#8377; )</th>';
																				echo '<th>Amount <br/>( &#8377; )</th>';
																				echo '<th>Variation <br/>( % )</th>';
																				echo '<th>Variation <br/>Level</th>';
																				$NegoTotalAmount[$NegoContractId] = 0;
																			}
																		}
																		//echo $NegoTotalAmount[$NegoContractId];
																	?>
																</tr>
															</thead>
															<tbody>
																<?php
																	$SelectQuery2 = "SELECT * FROM parta_details where globid='$GlobID' order by detid asc";
																	//echo $TRId;exit;
																	$ResultQuery2 = mysqli_query($dbConn,$SelectQuery2);
																	if($ResultQuery2 == true){
																		if(mysqli_num_rows($ResultQuery2)>0){
																			while($Result2 = mysqli_fetch_object($ResultQuery2)){
																				$ItemNo  		= $Result2->sno;
																				$ItemDesc   	= $Result2->description;
																				$ItemQty    	= $Result2->quantity;
																				$ItemUnit    	= $Result2->unit;
																				$DeptItemRate   = $Result2->supply;
																				$DeptAmount 	= round(($ItemQty * $DeptItemRate),2);
																				$DeptTotalAmount = $DeptTotalAmount + $DeptAmount;
																				if($DeptItemRate != 0){
																					$DeptItemRateStr = IndianMoneyFormat($DeptItemRate);
																				}else{
																					$DeptItemRateStr = "";
																				}
																				if($DeptAmount != 0){
																					$DeptAmountStr = IndianMoneyFormat($DeptAmount);
																				}else{
																					$DeptAmountStr = "";
																				}
																				if($ItemQty != 0){
																					$ItemQtyStr = $ItemQty;
																				}else{
																					$ItemQtyStr = "";
																				}

																				echo '<tr>';
																				echo '<td align="center">'.$ItemNo.'</td>';
																				echo '<td align="justify" style="width:650px">'.$ItemDesc.'</td>';
																				echo '<td align="right">'.$ItemQtyStr.'</td>';
																				echo '<td align="center">'.$ItemUnit.'</td>';
																				echo '<td align="right">'.$DeptItemRateStr.'</td>';
																				echo '<td align="right">'.$DeptAmountStr.'</td>';
																				foreach($ContArr as $ContractId){
																					$ItemRate   = $BidderRateArr[$ContractId][$ItemNo];
																					$Amount 	= round(($ItemQty * $ItemRate),2);

																					if($DeptAmount != 0){
																						$VariationAmt 	= $Amount - $DeptAmount;
																						$VariationPerc 	= round(($VariationAmt * 100 / $DeptAmount),2);
																						$VariationStr 	= IndianMoneyFormat($VariationPerc,2);
																					}else{
																						$VariationPerc	= 0;
																						$VariationStr 	= "";
																					}
																					if($ItemRate != 0){
																						$ItemRateStr = IndianMoneyFormat($ItemRate);
																					}else{
																						$ItemRateStr = "";
																					}
																					if($Amount != 0){
																						$AmountStr = IndianMoneyFormat($Amount);
																					}else{
																						$AmountStr = "";
																					}
																					echo '<td align="right">'.$ItemRateStr.'</td>';
																					echo '<td align="right">'.$AmountStr.'</td>';
																					if($VariationPerc > 25){	
																						echo '<td style="background-color:#FF0000" align="right">'.$VariationStr.'</td>';
																					}elseif($VariationPerc < -25){
																						echo '<td style="background-color:#00FF00" align="right">'.$VariationStr.'</td>';
																					}elseif($VariationPerc = ''){
																						echo '<td style="background-color:#FFFFFF" align="right"></td>';	
																					}else{
																						echo '<td style="background-color:#FFFFFF" align="right">'.$VariationStr.'</td>';
																					
																					}
																					$TotalAmount[$ContractId] = $TotalAmount[$ContractId] + $Amount;

																					if(in_array($ContractId, $NegoContArr)){
																						$NegotiateItemRate   = $NegoBidderRateArr[$ContractId][$ItemNo];
																						$NegAmount 	= round(($ItemQty * $NegotiateItemRate),2);
																						if($DeptAmount != 0){
																							$VariationAmt 	= $NegAmount - $DeptAmount;
																							$NegoVariationPerc 	= round(($VariationAmt * 100 / $DeptAmount),2);
																							$NegoVariationStr 	= IndianMoneyFormat($NegoVariationPerc,2);
																						}else{
																							$NegoVariationPerc	= 0;
																							$NegoVariationStr 	= "";
																						}
																						if($NegotiateItemRate != 0){
																							$NegoItemRateStr = IndianMoneyFormat($NegotiateItemRate);
																						}else{
																							$NegoItemRateStr = "";
																						}
																						if($NegAmount != 0){
																							$NegAmountStr = IndianMoneyFormat($NegAmount);
																						}else{
																							$NegAmountStr = "";
																						}
																						echo '<td align="right">'.$NegoItemRateStr.'</td>';
																						echo '<td align="right">'.$NegAmountStr.'</td>';
																						if($NegoVariationPerc > 25){	
																							echo '<td style="background-color:#FF0000" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(H)';
																							echo '<td style="background-color:#FF0000" align="center" >'.$NegoLevel.'</td>';
																						}elseif($NegoVariationPerc < -25){
																							echo '<td style="background-color:#00FF00" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(L)';
																							echo '<td style="background-color:#00FF00" align="center" >'.$NegoLevel.'</td>';
																						}else{
																							echo '<td style="background-color:#FFFFFF" align="right">'.$NegoVariationStr.'</td>';
																							$NegoLevel = '(N)';
																							echo '<td style="background-color:#FFFFFF" align="center">'.$NegoLevel.'</td>';
																						}
																						$NegoTotalAmount[$ContractId] = $NegoTotalAmount[$ContractId] + $NegAmount;
																					}


																				}
																				
																				echo'</tr>';
																			} 
																		}
																	}
																?>
																<tr>
																	<td>&nbsp;</td>
																	<td align="right" nowrap="nowrap"><b>Total Amount ( &#8377; )</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right"><b><?php echo IndianMoneyFormat($DeptTotalAmount); ?></b></td>
																	<?php
																		foreach($ContArr as $ContractId){
																			echo '<td>&nbsp;</td>';
																			echo '<td align="right"><b>'.IndianMoneyFormat($TotalAmount[$ContractId]).'</b></td>';
																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				echo '<td>&nbsp;</td>';
																				echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalAmount[$ContractId]).'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																		
																	?>
																</tr>
																<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate ( % )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($RebatePercArr[$ContractId]).'</b></td>';
																					echo '<td>&nbsp;</td>';
																					if(in_array($ContractId, $NegoContArr)){
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($NegoRebatePercArr[$ContractId]).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						echo '<td>&nbsp;</td>';
																					}
																					}
																				}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Rebate Value ( &#8377 )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																			if(!empty($ContArr)){
																				foreach($ContArr as $ContractId){
																					$RebatePerc = $RebatePercArr[$ContractId];
																					$TotalAmt = $TotalAmount[$ContractId];
																					$RebateAmt = $TotalAmt * $RebatePerc / 100;
																					$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																					$NegoRebatePerc = $NegoRebatePercArr[$ContractId];
																					$TotalNegoAmt = $NegoTotalAmount[$ContractId];
																					$RebateNegoAmt = $TotalNegoAmt * $NegoRebatePerc / 100;
																					$TotNegoAmtAftRebate = round(($TotalNegoAmt - $RebateNegoAmt),2);
																					echo '<td>&nbsp;</td>';
																					echo '<td align="right"><b>'.IndianMoneyFormat($RebateAmt).'</b></td>';
																					echo '<td>&nbsp;</td>';
																					if(in_array($ContractId, $NegoContArr)){
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($RebateNegoAmt).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						echo '<td>&nbsp;</td>';
																					}
																				}
																			}
																		?>
																	</tr>
																	<tr>
																		<td>&nbsp;</td>
																		<td align="right" nowrap="nowrap"><b>Total Amount After Rebate ( &#8377; )</b></td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td align="right"></td>
																		<?php
																				if(!empty($ContArr)){
																					foreach($ContArr as $ContractId){
																						$RebatePerc = $RebatePercArr[$ContractId];
																						$TotalAmt = $TotalAmount[$ContractId];
																						$RebateAmt = $TotalAmt * $RebatePerc / 100;
																						$TotAmtAftRebate = round(($TotalAmt - $RebateAmt),2);
																						$NegoRebatePerc = $NegoRebatePercArr[$ContractId];
																						$TotalNegoAmt = $NegoTotalAmount[$ContractId];
																						$RebateNegoAmt = $TotalNegoAmt * $NegoRebatePerc / 100;
																						$TotNegoAmtAftRebate = round(($TotalNegoAmt - $RebateNegoAmt),2);
																						echo '<td>&nbsp;</td>';
																						echo '<td align="right"><b>'.IndianMoneyFormat($TotAmtAftRebate).'</b></td>';
																						echo '<td>&nbsp;</td>';
																						if(in_array($ContractId, $NegoContArr)){
																							echo '<td>&nbsp;</td>';
																							echo '<td align="right"><b>'.IndianMoneyFormat($TotNegoAmtAftRebate).'</b></td>';
																							echo '<td>&nbsp;</td>';
																							echo '<td>&nbsp;</td>';
																						}
																						$TotalAmount[$ContractId] = $TotAmtAftRebate;
																						$NegoTotalAmount[$ContractId] = $TotNegoAmtAftRebate;
																					}
																						//[$ContractId];
																				}
																			?>
																	</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td align="right" nowrap="nowrap"><b>Variation Amount ( &#8377; )</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			echo '<td>&nbsp;</td>';
																			echo '<td align="right"><b>'.IndianMoneyFormat($TotalVariateAmt).'</b></td>';
																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				echo '<td>&nbsp;</td>';
																				echo '<td align="right"><b>'.IndianMoneyFormat($NegoTotalVariateAmt).'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																	?>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right" nowrap="nowrap"><b>Variation ( % )</b></td>																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt	= round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																			echo '<td>&nbsp;</td>';
																			echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																			text-transform: none;line-height: 17px;" align="right"><b>'.IndianMoneyFormat($TotalVariatePerc).'</b></td>';																			echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
																				echo '<td>&nbsp;</td>';
																				echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																				text-transform: none;line-height: 17px;" align="right"><b>'.IndianMoneyFormat($NegoTotalVariatePerc).'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																		
																	?>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td align="right"><b>Excess / Less</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		$StatusArr = array();
																		$NegoStatusArr = array();
																		foreach($ContArr as $ContractId){
																			$TotalVariateAmt = round(($TotalAmount[$ContractId] - $DeptTotalAmount),2);
																			$TotalVariatePerc = round(($TotalVariateAmt * 100 / $DeptTotalAmount),2);
																			if($TotalVariatePerc > 0){
																				$ExcessLess = "EXCESS";
																			}else if($TotalVariatePerc< 0){
																				$ExcessLess = "LESS";
																			}else{
																				$ExcessLess = "";
																			}
																			echo '<td>&nbsp;</td>';
																			echo '<td align="center"><b>'.$ExcessLess.'</b></td>';
																			echo '<td>&nbsp;</td>';
																			$StatusArr[$ContractId] = $TotalVariateAmt;
																			
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoTotalVariateAmt = round(($NegoTotalAmount[$ContractId] - $DeptTotalAmount),2);
																				$NegoTotalVariatePerc = round(($NegoTotalVariateAmt * 100 / $DeptTotalAmount),2);
																				if($NegoTotalVariatePerc > 0){
																					$NegoExcessLess = "EXCESS";
																				}else if($NegoTotalVariatePerc < 0){
																					$NegoExcessLess = "LESS";
																				}else{
																					$NegoExcessLess = "";
																				}
																				echo '<td>&nbsp;</td>';
																				echo '<td align="center"><b>'.$NegoExcessLess.'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																				$NegoStatusArr[$NegoContractId] = $NegoTotalVariateAmt;
																			}
																		}
																		
																	?>
																</tr>
																<tr>
																	<td>&nbsp;</td>
																	<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																						text-transform: none;line-height: 17px;" align="right"><b>Status</b></td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td>&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<?php
																		asort($StatusArr);
																		asort($NegoStatusArr);
																		foreach($ContArr as $ContractId){
																			$StatusPosition = array_search($ContractId, array_keys($StatusArr));
																			$StatusPosition = $StatusPosition + 1;
																			echo '<td>&nbsp;</td>';
																			echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																			      text-transform: none;line-height: 17px;" align="center"><b>L'.$StatusPosition.'</b></td>';
																		    echo '<td>&nbsp;</td>';
																			if(in_array($ContractId, $NegoContArr)){
																				$NegoStatusPosition = array_search($ContractId, array_keys($NegoStatusArr));
																				$NegoStatusPosition = $NegoStatusPosition + 1;
																				echo '<td>&nbsp;</td>';
																				echo '<td style ="line-height: 16px !important;font-weight: 600 !important;	color: #74048C; font-size: 13px; border-collapse: collapse; text-shadow: none;
																				text-transform: none;line-height: 17px;" align="center"><b>L'.$NegoStatusPosition.'</b></td>';
																				echo '<td>&nbsp;</td>';
																				echo '<td>&nbsp;</td>';
																			}
																		}
																	?>
																</tr>
															</tbody>
														</table>
												<?php } ?>
                                			</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:75px;"></div>
						</div>
					</div>
					
					<!--<div class="col-md-4 col-md-4 col-sm-4 col-xs-4" style="padding-left:0px; padding-right:3px">
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
											</tr>
										</thead>
										
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
										
									</table>
								</div>
							</div>
						</div>-->
						
					</div>
					<input type="hidden" name="txt_result_2" id="txt_result_2" value="<?php echo $PPrbn; ?>">
					<input type="hidden" name="txt_slm_payable_amt" id="txt_slm_payable_amt" value="<?php echo number_format(round($SLMTotalPayableAmt,2), 2, '.', ','); ?>">
					<input type="hidden" name="txt_dpm_payable_amt" id="txt_dpm_payable_amt" value="<?php echo number_format(round($DPMTotalPayableAmt,2), 2, '.', ','); ?>">
					<input type="hidden" name="txt_upto_payable_amt" id="txt_upto_payable_amt" value="<?php echo number_format(round(($SLMTotalPayableAmt + $DPMTotalPayableAmt),2), 2, '.', ','); ?>">
					<div class="loaddiv pageload" id="pageload"><div class="cssload-loader"></div></div>
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
		   	<div class="col-md-12" align="center">
				<input type="submit" name="btn_back" id="btn_back" value=" Back " class="btn btn-danger">
			</div>
        </div>
	</form>
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
		

		$("#QtyInfo").click(function(event){
			var $QtyDetData = $('<table class="table table-bordered modal-table"></table>');
        		$QtyDetData.append('<tr><td align="center" bgcolor="#E6E9EC">Item No.</td><td align="center" bgcolor="#E6E9EC">Item Desc.</td><td align="center" bgcolor="#E6E9EC">Agreement Qty.</td><td align="center" bgcolor="#E6E9EC">Deviation Qty.</td><td align="center" bgcolor="#E6E9EC">Total Qty.</td><td align="center" bgcolor="#E6E9EC">Utilized Qty.</td><td align="center" bgcolor="#E6E9EC">Balance Qty.</td></tr>');
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
	.tbox-rb{ text-align:right; padding-right:3px; border:1px solid #006CD9; }
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
		width: 90%;
	}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
<script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		var dataTab = $('.example').DataTable({ "paging":false, "ordering":false, "info":false, "autoWidth": false, "dom": '<"toolbar">frtip', "filter" : 'applied' });
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
