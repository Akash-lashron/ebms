<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
checkUser();
include "sysdate.php";
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
$i=0;
/*if(isset($_POST["btn_skip"]) == " Skip "){
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
}
if(($_GET["sheetid"] != "")&&($_GET["rbn"] != "")){ 
    $sheetid 			= 	$_GET['sheetid'];
    $rbn 				= 	$_GET['rbn'];
	$HOA 				= 	$_GET['hoa'];
}
if(isset($_POST["btn_go"]) ==  'GO '){
    $sheetid 			= 	trim($_POST['cmb_work_no']);
    $rbn 				= 	trim($_POST['cmb_rbn']);
	$CCNo 				= 	trim($_POST['txt_ccno']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
	
}*/
//echo $rbn;exit;
/*$sheetid = $_GET['shid'];
if($sheetid != ""){
	$rbn = $_GET['rbn'];
	$query 		= 	"SELECT * FROM sheet WHERE sheet_id ='$sheetid' ";
	$sqlquery 	= 	mysqli_query($dbConn,$query);
	if($sqlquery == true){
		$List 					= 	mysqli_fetch_object($sqlquery);
		$work_name 				= 	$List->work_name; 
		$short_name 			= 	$List->short_name;   
		$tech_sanction 			= 	$List->tech_sanction;  
		$name_contractor 		= 	$List->name_contractor; 
		$ContId 				= 	$List->contid; 
		$ContBankId 			= 	$List->cbdtid; 
		if($ContId != 0){
			$SelectContQuery = "select * from contractor where contid = '$ContId'";
			$SelectContSql = mysqli_query($dbConn,$SelectContQuery);
			if($SelectContSql == true){
				if(mysqli_num_rows($SelectContSql)>0){
					$ContList = mysqli_fetch_object($SelectContSql);
					$name_contractor = $ContList->name_contractor;
					$pan_no = $ContList->pan_no;
					$gst_no = $ContList->gst_no;
				}
			}
		}
		if($ContBankId != 0){
			$SelectBankQuery = "select * from contractor_bank_detail where cbdtid = '$ContBankId'";
			$SelectBankSql = mysqli_query($dbConn,$SelectBankQuery);
			if($SelectBankSql == true){
				if(mysqli_num_rows($SelectBankSql)>0){
					$BankList = mysqli_fetch_object($SelectBankSql);
					$ContAccHolder = $BankList->bank_acc_hold_name;
					$ContAccNo = $BankList->bank_acc_no;
					$ContBankName = $BankList->bank_name;
					$ContBankBrAddr = $BankList->branch_address;
					$ContBankIfsc = $BankList->ifsc_code;
				}
			}
		}
		$ccno 					= 	$List->computer_code_no;    
		$agree_no 				= 	$List->agree_no; 
		$overall_rebate_perc 	= 	$List->rebate_percent; 
		$work_order_no 			= 	$List->work_order_no; 
		$work_order_date 		= 	$List->work_order_date;
		$work_commence_date 	= 	$List->work_commence_date;
		$sch_doc 				= 	$List->date_of_completion;
		$act_doc 				= 	$List->act_doc;
		$workcost 				= 	$List->work_order_cost;
		$workcomplete 		    = 	$List->date_of_completion;
		//$rbn 		            = 	$List->rbn;
		$contid 		        = 	$List->contid;
		$hoa 		            = 	$List->hoa;
		$HoaArr = array();
		$GlobId = $List->globid;
		$HoaId  = $List->hoaid;
		if(($List->hoaid != '')&&($List->hoaid != NULL)){
			$SelectQuery1 = "select * from hoa_master where hoamast_id IN ($HoaId)";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$HoaNo = $List1->new_hoa_no;
						array_push($HoaArr,$HoaNo);
					}
				}
			}
		}else{
			$SelectQuery2 = "select * from works where globid = '$GlobId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$HoaNo = $List2->hoa;
					if(($HoaNo != '')&&($HoaNo != NULL)){
						array_push($HoaArr,$HoaNo);
					}else{
						$SelectQuery3 = "select * from hoa_master where hoamast_id IN ($List2->hoaid)";
						$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								while($List3 = mysqli_fetch_object($SelectSql3)){
									$HoaNo = $List3->new_hoa_no;
									array_push($HoaArr,$HoaNo);
								}
							}
						}
					}
				}
			}
		}
		if(count($HoaArr)>0){
			$HoaStr = implode(",<br/> ",$HoaArr);
			$hoa = $HoaStr;
		}
	}//echo $query;exit;
}

$MemoId = "";
if((isset($_GET['view']))&&($_GET['view'] != '')){
	$MemoId  =  $_GET['view'];
	$_SESSION['MiscMopViewId'] = $MemoId;
}

if(($sheetid != "")&&($rbn != "")){
	$SelectAbstQuery = "select * from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectAbstSql = mysqli_query($dbConn,$SelectAbstQuery);
	if($SelectAbstSql == true){
		if(mysqli_num_rows($SelectAbstSql)>0){
			$AbstList = mysqli_fetch_object($SelectAbstSql);
			$upto_date_total_amount = $AbstList->upto_date_total_amount;
			$dpm_total_amount = $AbstList->dpm_total_amount;
			$slm_total_amount = $AbstList->slm_total_amount;
			$Uptombookno = $AbstList->mbookno;
			$Uptombookpage = $AbstList->mbookpage;
			if(($AbstList->is_adv_pay == 'Y')&&(($AbstList->pass_order_dt == '0000-00-00')||($AbstList->pass_order_dt == NULL))){
				$SelectAbstQuery2 = "select * from abstractbook_dt where sheetid = '$sheetid' and rbn = '$rbn' and is_adv_pay = 'Y'";
				$SelectAbstSql2 = mysqli_query($dbConn,$SelectAbstQuery2);
				if($SelectAbstSql2 == true){
					if(mysqli_num_rows($SelectAbstSql2)>0){
						$IsAdvPayFlag = "Y";
					}
				}
			}
		}
	}
}
if($IsAdvPayFlag == "Y"){
	$WhereClause = " and is_adv_pay = 'Y'";
}else{
	$WhereClause = "";
}
*/
if($_SESSION['MiscMopViewId'] != ""){ 
	$MemoId = $_SESSION['MiscMopViewId'];
	$Acc = 0;
	$AccSelectQuery = "select * from memo_payment_accounts_edit where memoid = '$MemoId'";// and rbn = '$rbn'".$WhereClause;
	$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
	if($AccSelectSql == true){
		if(mysqli_num_rows($AccSelectSql)>0){
			$AccList = mysqli_fetch_object($AccSelectSql);
			$abstract_net_amt = $AccList->abstract_net_amt;
			$pl_mac_adv_amt = $AccList->pl_mac_adv_amt;
			$cgst_percent = $AccList->cgst_tds_perc;
			$cgst_amt = $AccList->cgst_tds_amt;
			$sgst_percent = $AccList->sgst_tds_perc;
			$sgst_amt = $AccList->sgst_tds_amt;
			$igst_percent = $AccList->igst_tds_perc;
			$igst_amt = $AccList->igst_tds_amt;
			$sd_percent = $AccList->sd_percent;
			$sd_amt = $AccList->sd_amt;
			$wct_percent = $AccList->wct_percent;
			$wct_amt = $AccList->wct_amt;
			$vat_percent = $AccList->vat_percent;
			$vat_amt = $AccList->vat_amt;
			$mob_adv_percent = $AccList->mob_adv_percent;
			$mob_adv_amt = $AccList->mob_adv_amt;
			$lw_cess_percent = $AccList->lw_cess_percent;
			$lw_cess_amt = $AccList->lw_cess_amt;
			$incometax_percent = $AccList->incometax_percent;
			$incometax_amt = $AccList->incometax_amt;
			$it_cess_percent = $AccList->it_cess_percent;
			$it_cess_amt = $AccList->it_cess_amt;
			$it_edu_percent = $AccList->it_edu_percent;
			$it_edu_amt = $AccList->it_edu_amt;
			$land_rent = $AccList->land_rent;
			$liquid_damage = $AccList->liquid_damage;
			$other_recovery_1_desc = $AccList->other_recovery_1_desc;
			$other_recovery_1_amt = $AccList->other_recovery_1_amt;
			$other_recovery_2_desc = $AccList->other_recovery_2_desc;
			$other_recovery_2_amt = $AccList->other_recovery_2_amt;
			$non_dep_machine_equip = $AccList->non_dep_machine_equip;
			$non_dep_man_power = $AccList->non_dep_man_power;
			$nonsubmission_qa = $AccList->nonsubmission_qa;
			
			$sec_adv_amount = $AccList->sec_adv_amount;
			$electricity_cost = $AccList->electricity_cost;
			$water_cost = $AccList->water_cost;
			
			$MisItemId = $AccList->mis_item_id;
			$BillNum = $AccList->bill_no;
			$BillDate = $AccList->bill_dt;
			$RefNum = $AccList->misc_ref_no;

			$edit_flag = $AccList->edit_flag;
			$pass_order_dt = dt_display($AccList->pass_order_dt);
			$pay_order_dt = dt_display($AccList->pay_order_dt);
			$voucher_dt = dt_display($AccList->payment_dt);
			
			$is_adv_pay = $AccList->is_adv_pay;
			$adv_perc = $AccList->adv_perc;
			$adv_amt = $AccList->adv_amt;
			
			$bill_amt_gst = $AccList->bill_amt_gst;
			$bill_amt_it  = $AccList->bill_amt_it;
			
			$pl_mac_adv_int_amt  = $AccList->pl_mac_adv_int_amt;
			$mob_adv_int_amt  = $AccList->mob_adv_int_amt;
			
			$mop_date = $AccList->mop_date;
			if($mop_date == "0000-00-00"){
				$mop_date = date("Y-m-d",strtotime($AccList->modifieddate));
			}
			
			$Acc = 1;
			if(($MisItemId != 0)||($MisItemId != "")){
				$MisItemSelectQuery = "SELECT * FROM miscell_items WHERE mis_item_id = '$MisItemId'";
				$MisItemSelectQuerySql 	= mysqli_query($dbConn,$MisItemSelectQuery);
				if($MisItemSelectQuerySql == true){
					if(mysqli_num_rows($MisItemSelectQuerySql)>0){
						$MisItemList = mysqli_fetch_object($MisItemSelectQuerySql);
						$MisItemDesc = $MisItemList->mis_item_desc;
					}
				}
			}
			$MiscContId  = $AccList->contid;
			$MiscCbdtId  = $AccList->cbdtid;
			$MiscHoaId   = $AccList->hoaid;
			$MiscHoaNo   = $AccList->hoa;
			
			if($MiscContId != 0){
				$SelectContQuery = "select * from contractor where contid = '$MiscContId'";
				$SelectContSql = mysqli_query($dbConn,$SelectContQuery);
				if($SelectContSql == true){
					if(mysqli_num_rows($SelectContSql)>0){
						$ContList = mysqli_fetch_object($SelectContSql);
						$name_contractor = $ContList->name_contractor;
						$pan_no = $ContList->pan_no;
						$gst_no = $ContList->gst_no;
					}
				}
			}
			if($MiscCbdtId != 0){
				$SelectBankQuery = "select * from contractor_bank_detail where cbdtid = '$MiscCbdtId'";
				$SelectBankSql = mysqli_query($dbConn,$SelectBankQuery);
				if($SelectBankSql == true){
					if(mysqli_num_rows($SelectBankSql)>0){
						$BankList = mysqli_fetch_object($SelectBankSql);
						$ContAccHolder = $BankList->bank_acc_hold_name;
						$ContAccNo = $BankList->bank_acc_no;
						$ContBankName = $BankList->bank_name;
						$ContBankBrAddr = $BankList->branch_address;
						$ContBankIfsc = $BankList->ifsc_code;
					}
				}
			}
			$HoaArr = array(); $ScodeArr = array(); $Scode = '';
			if(($MiscHoaId != '')&&($MiscHoaId != NULL)){ 
				$SelectQuery1 = "select * from hoa_master where hoamast_id IN ($MiscHoaId)";
				$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
				if($SelectSql1 == true){
					if(mysqli_num_rows($SelectSql1)>0){
						while($List1 = mysqli_fetch_object($SelectSql1)){
							$HoaNo = $List1->new_hoa_no;
							$HoaSCodeId = $List1->shortcode_id;
							//$HoaScodeArr = array();
							$ShortCode = "";
							$SelectQuery1A = "select * from shortcode_master where shortcode_id IN ($HoaSCodeId)"; //echo $SelectQuery1A."<br/>";
							$SelectSql1A = mysqli_query($dbConn,$SelectQuery1A);
							if($SelectSql1A == true){
								if(mysqli_num_rows($SelectSql1A)>0){
									/*while($List1A = mysqli_fetch_object($SelectSql1A)){
										array_push($HoaScodeArr,$List1A->shortcode);
									}*/
									$List1A = mysqli_fetch_object($SelectSql1A);
									$ShortCode = $List1A->shortcode;
									array_push($ScodeArr,$ShortCode);
								}
							}
							array_push($HoaArr,$HoaNo);
						}
					}
				}
			}
			if(count($HoaArr)>0){
				$HoaStr = implode(",<br/> ",$HoaArr);
				$hoa = $HoaStr;
			}
			if(count($ScodeArr)>0){
				$ScodeStr = implode(",<br/> ",$ScodeArr);
				$Scode = $ScodeStr;
			}
		}
	}
	//echo $lw_cess_amt;exit;
	/*if($Acc == 0){
		$AccSelectQuery = "select * from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
		if($AccSelectSql == true){
			if(mysqli_num_rows($AccSelectSql)>0){
				$AccList = mysqli_fetch_object($AccSelectSql);
				$abstract_net_amt = $AccList->abstract_net_amt;
				$cgst_percent = $AccList->cgst_percent;
				$cgst_amt = $cgst_percent->cgst_amt;
				$sgst_percent = $AccList->sgst_percent;
				$sgst_amt = $AccList->sgst_amt;
				$sd_percent = $AccList->sd_percent;
				$sd_amt = $AccList->sd_amt;
				$wct_percent = $AccList->wct_percent;
				$wct_amt = $AccList->wct_amt;
				$vat_percent = $AccList->vat_percent;
				$vat_amt = $AccList->vat_amt;
				$mob_adv_percent = $AccList->mob_adv_percent;
				$mob_adv_amt = $AccList->mob_adv_amt;
				$lw_cess_percent = $AccList->lw_cess_percent;
				$lw_cess_amt = $AccList->lw_cess_amt;
				$incometax_percent = $AccList->incometax_percent;
				$incometax_amt = $AccList->incometax_amt;
				$it_cess_percent = $AccList->it_cess_percent;
				$it_cess_amt = $AccList->it_cess_amt;
				$it_edu_percent = $AccList->it_edu_percent;
				$it_edu_amt = $AccList->it_edu_amt;
				$land_rent = $AccList->land_rent;
				$liquid_damage = $AccList->liquid_damage;
				$other_recovery_1_desc = $AccList->other_recovery_1_desc;
				$other_recovery_1_amt = $AccList->other_recovery_1;
				$other_recovery_2_desc = $AccList->other_recovery_2_desc;
				$other_recovery_2_amt = $AccList->other_recovery_2;
				$non_dep_machine_equip = $AccList->non_dep_machine_equip;
				$non_dep_man_power = $AccList->non_dep_man_power;
				$nonsubmission_qa = $AccList->nonsubmission_qa;
				$edit_flag = $AccList->edit_flag;
				$Acc = 1;
			}
		}
		$AccSelectSAQuery = "select * from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectSASql = mysqli_query($dbConn,$AccSelectSAQuery);
		if($AccSelectSASql == true){
			if(mysqli_num_rows($AccSelectSASql)>0){
				$AccSAList = mysqli_fetch_object($AccSelectSASql);
				$sec_adv_amount = $AccSAList->sec_adv_amount;
			}
		}
		
		$electricity_cost = 0;
		$AccSelectEBQuery = "select electricity_cost from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectEBSql = mysqli_query($dbConn,$AccSelectEBQuery);
		if($AccSelectEBSql == true){
			if(mysqli_num_rows($AccSelectEBSql)>0){
				while($AccEBList = mysqli_fetch_object($AccSelectSASql)){
					$electricity_cost = $electricity_cost + $AccEBList->electricity_cost;
				}
			}
		}
		
		$water_cost = 0;
		$AccSelectWBQuery = "select water_cost from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
		$AccSelectWBSql = mysqli_query($dbConn,$AccSelectWBQuery);
		if($AccSelectWBSql == true){
			if(mysqli_num_rows($AccSelectWBSql)>0){
				while($AccWBList = mysqli_fetch_object($AccSelectWBSql)){
					$water_cost = $water_cost + $AccWBList->water_cost;
				}
			}
		}
		
	}*/
	$SCodeRecArr = array();
	$AccSelectSCodeQuery = "select a.*, b.shortcode from mop_rec_dt a inner join shortcode_master b on (a.shortcode_id = b.shortcode_id) where a.mopid = '$MemoId'";// and a.rbn = '$rbn'";
	$AccSelectSCodeSql = mysqli_query($dbConn,$AccSelectSCodeQuery);
	if($AccSelectSCodeSql == true){
		if(mysqli_num_rows($AccSelectSCodeSql)>0){
			while($AccSCList = mysqli_fetch_object($AccSelectSCodeSql)){
				$SCodeRecArr[$AccSCList->rec_code] = $AccSCList;
			}
		}
	}
}

    //$contid=0;
   /* $ContSelectSAQuery = "select * from contractor where contid='$contid' ";
    $ContSelectSASql = mysqli_query($dbConn,$ContSelectSAQuery);
	if($ContSelectSASql == true){
		if(mysqli_num_rows($ContSelectSASql)>0){
			$ContAList = mysqli_fetch_object($ContSelectSASql);
			//$contid         = $ContAList->contid;
			$Acc_Num        = $ContAList->bank_acc_no;
			$Bank_Name      = $ContAList->bank_name;
			$Branch_Name    = $ContAList->branch_name;
			$Pan_No         = $ContAList->pan_no;
			$Gst_No         = $ContAList->gst_no;
			$IFSC           = $ContAList->ifsc_code;
		}
	}//echo $IFSC;exit;*/

$BFpage = 1;
//echo $abstract_net_amt;exit;
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack(){
	   	url = "MemoPaymentView.php";
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
	@page{
			size: A4 portrait;
			margin: 6mm 6mm 6mm 6mm;
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

<!--<script src="stepWizard/jquery.wizard.js"></script>-->




<link href="stepWizard/jquery.wizard.css" rel="stylesheet">
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
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
		<div class="content">
			<?php include "MainMenu.php"; ?>
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
						<div class="row">
							<div class="box-container box-container-lg">
								<div class="row smclearrow"></div>
								<div class="div2">
									<div class="card" style="margin-top:2px;">
										<a data-url="MopMemoForStatementsMisc?view=<?php echo $MemoId; ?>">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Miscellaneous Statement
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="MopMemoForStatementsMisc?view=<?php echo $MemoId; ?>">
										<div class="face-static tabbtn tabbtn-active">
											<div class="card-body padding-1">
												<div class="row">
													<i class="fa fa-check-square-o" style="font-size:14px"></i>&nbsp;Miscellaneous Coding Sheet
												</div>                         
											</div>
										</div>
										</a>
									</div>
								</div>
								
								
								
								<div class="div10">
									<div class="box-container box-container-lg lg-box" align="center">
										<div class="div12">
											<div class="card cabox" style="margin-bottom:1px;">
												<div class="face-static">
													<div class="card-header inkblue-card" align="left">&nbsp;Miscellaneous Coding Sheet</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox" style="padding-top:0px; padding-bottom:0px;">
															<div class="row smclearrow"></div>
															<div class="row smclearrow"></div>
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2" style="padding-top:0px;">
																		<div class="row" align="center">
																			<div class="steps-content" align="center" id="printSection">
																				<style>
																					@media print {
																						.printbutton{
																							display:none;
																						}
																						body
																						{
																						  	font-size:15px !important;
																						}
																						/*.bobtm{
																							border-bottom:2px solid #52585E !important;
																						}*/
																						
																						.TablePart{
																							margin:90px 60px 60px 60px;
																							font-size:18px;
																							border:none !important;
																						}
																						table, td, .tableM tr td {
																							border:none !important;
																							font-size:15px !important;
																						}
																						td, .tableM tr td{
																							padding:7px !important;
																							font-size:15px !important;
																							line-height:20px !important;
																						}
																						/*.bobtm{
																							border:0px 0px 2px 0px !important;
																							border-bottom:2px solid #52585E !important;
																							border-style:solid !important;
																							border-color:#52585E !important;
																						}*/
																						.prdiv{
																							font-size:15px !important;
																						}
																					} 
																				</style>
																				<div class="TablePart" align="center">
																					<table width="100%" class="tableM" align="center">
																						<tr class="">
																							<td colspan="4" align="center" class="cboxlabel">
																								<span style="float:center"><b>GOVERNMENT OF INDIA</b></span><br/>
																								<span style="float:center"><b>DEPARTMENT OF ATOMIC ENERGY</b></span><br/>
																								<span style="float:center"><b>BARC, NRB, FRFCF - ACCOUNTS</b></span><br/><br/>
																							   	<span style="float:center"><b>CODING SHEET FOR MISCELLANEOUS PAYMENT </b></span>
																							   	<!--<span style="float:right">C.CODE &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; <?php echo $ccno; ?></span>-->
																								<span style="float:center"><b><br/>&nbsp;</b></span>
																							</td>
																						</tr>
																						<tr>
																							<td colspan="4" class="cboxlabel">
																								<span style="float:left"><b>Bill No.</b> : <?php echo $BillNum; ?></span>
																								<span style="float:center"><b>Month</b> : <?php echo date("F-Y",strtotime($mop_date)); ?> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;<b>Vr. No. & Date </b>&nbsp;&nbsp;:&nbsp;</span>
																							</td>
																						</tr>
																						
																						
																						
																						<tr>
																							<td align="left" width="250px" class="blabel">Name of Contractor</td>
																							<td colspan="3"><?php echo $name_contractor; ?> </td>
																						</tr>
																						
																					</table>
																						
																						<?php $GrandTotal = round($abstract_net_amt); ?>
																						<?php $NetTotal = round($abstract_net_amt); ?>
																						
																						
																						<?php $rca = 1; $TotalRecovery = 0; $CodeAmount = $NetTotal; if($lw_cess_amt > 0){ $TotalRecovery  = $TotalRecovery + $lw_cess_amt; $CodeAmount = $CodeAmount - $lw_cess_amt;  } ?>
																						
																						<?php if($mob_adv_amt > 0){ $TotalRecovery  = $TotalRecovery + $mob_adv_amt; $CodeAmount = $CodeAmount - $mob_adv_amt;  } ?>
																						
																						<?php if($pl_mac_adv_amt > 0){ $TotalRecovery  = $TotalRecovery + $pl_mac_adv_amt; $CodeAmount = $CodeAmount - $pl_mac_adv_amt;  } ?>
																						
																						<?php if($hire_charges > 0){ $TotalRecovery  = $TotalRecovery + $hire_charges; $CodeAmount = $CodeAmount - $hire_charges;  } ?>
																								
																						<?php if($other_recovery_1_amt > 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_1_amt; } ?>
																						
																						<?php if($other_recovery_2_amt > 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_2_amt; } ?>
																						
																						<?php if($non_dep_machine_equip > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_machine_equip; } ?>
																						
																						<?php if($non_dep_man_power > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_man_power; } ?>
																						
																						<?php if($nonsubmission_qa > 0){ $TotalRecovery  = $TotalRecovery + $nonsubmission_qa; } ?>
																						
																						<?php $ChequeAmount = round($NetTotal - $TotalRecovery); ?>
																						
																					</table>	
																						
																						
																					
																					
																					<table width="100%" class="tableM" align="center">
																						<tr class="label" style="border-top:2px solid #52585E;">
																							<td align="center" class="blabel">Particulars</td>
																							<td align="center" class="blabel">HOA</td>
																							<td align="center" class="blabel">Budget wise Item</td>
																							<td align="right" class="blabel">Amount (Rs.)</td>
																						</tr>
																						<tr class="label" style="border-top:2px solid #52585E;">
																							<td align="left">DEBITABLE TO</td>
																							<td align="center"><?php echo $hoa; ?></td>
																							<td align="center"><?php echo $Scode; ?></td>
																							<td align="right"><?php echo IND_money_format($CodeAmount); ?></td>
																						</tr>
																						<?php 
																						//echo " = ";print_r($SCodeRecArr);exit;
																						$CodSheetChequeAmt = $CodeAmount; 
																						if($IsAdvPayFlag != 'Y'){
																							if(count($SCodeRecArr)>0){ 
																								foreach($SCodeRecArr as $SCodeRecKey => $SCodeRecValue){ 
																									$SCodeId = $SCodeRecValue->shortcode_id;
																									$SCodeHoaNo = "";
																									$SelectQuery = "select new_hoa_no from hoa_master where (shortcode_id = '$SCodeId' OR shortcode_id LIKE '$SCodeId,%' OR shortcode_id LIKE '%,$SCodeId' OR shortcode_id LIKE '%,$SCodeId,%')";
																									//echo $SelectQuery."<br/>";
																									$SelectSql = mysqli_query($dbConn,$SelectQuery);
																									if($SelectSql == true){
																										if(mysqli_num_rows($SelectSql)>0){
																											$SCodeHoaList = mysqli_fetch_object($SelectSql);
																											$SCodeHoaNo = $SCodeHoaList->new_hoa_no;
																										}
																									}
																									if($SCodeRecKey == "IT"){
																										$SCodeRecKey = "INCOME TAX";
																									}
																									if($SCodeRecKey == "CGST"){
																										$SCodeRecKey = "TDS ON CGST";
																									}
																									if($SCodeRecKey == "SGST"){
																										$SCodeRecKey = "TDS ON SGST";
																									}
																									if($SCodeRecKey == "IGST"){
																										$SCodeRecKey = "TDS ON IGST";
																									}
																									if($SCodeRecKey == "EC"){
																										$SCodeRecKey = "ELECTRICITY REC.";
																									}
																									if($SCodeRecKey == "WC"){
																										$SCodeRecKey = "WATER REC.";
																									}
																						?>
																						<tr class="label">
																							<td align ="left"><?php echo $SCodeRecKey; ?></td>
																							<td align ="center"><?php echo $SCodeHoaNo; ?></td>
																							<td align ="center"><?php echo $SCodeRecValue->shortcode;  ?></td>
																							<td align ="right"><span class="" style="float:left">(-)</span><?php echo IND_money_format($SCodeRecValue->rec_amt);  ?></td>
																						</tr>
																						<?php 
																								$CodSheetChequeAmt = $CodSheetChequeAmt - $SCodeRecValue->rec_amt;
																								} 
																							}
																						}
																						?>
																						<tr class="label">
																							<td align="left" class="" style="border-right:none;"></td>
																							<td align="center" class="nobdrlr"></td>
																							<td align="center" class="blabel nobdrlr">BY CHEQUE</td>
																							<td align="right" class="blabel" style="border-left:none;"><span class="blabel" style="float:left">Rs.</span><?php echo IND_money_format($CodSheetChequeAmt); ?></td>
																						</tr>
																						<tr style="border-top:2px solid #52585E;"><td colspan="4">&nbsp;</td></tr>
																						<!--<tr class="label">
																							<td align ="left">SGST</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"><?php echo $sgst_amt  ?></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Income tax</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"><?php echo $incometax_amt  ?></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Surcharge on IT</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Pr. CESS on IT & SC</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Hr. CESS on IT & SC</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Int. ON Mob. Adv</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Elects. Charge</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"><?php echo $electricitycost  ?></td>
																						</tr>
																						<tr class="label">
																							<td align ="left">Security Deposit</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																							<td align ="right"><?php echo $sd_amt ?></td>
																						</tr>-->
																					</table>
																					<br/>
																					<div class="prdiv" style="min-height:20px;">
																						<br/><br/><br/><br/><br/>
																						<div style="width:33%; float:left" class="labelmedium blabel">DA</div>
																						<div style="width:33%; float:left" class="labelmedium blabel">AAO</div>
																						<div style="width:33%; float:left" class="labelmedium blabel">SAO / DCA</div>
																						<br/>
																					</div>
																					
																					
																					<!--<p style='page-break-after:always; background-color:#f1f1f1; text-align:center' align="center"></p>-->
																					<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"> 
																					<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>"> 
																					<input type="hidden" name="txt_hoa" id="txt_hoa" value="<?php echo $HOA; ?>">
																				</div>
																				<div>&nbsp;</div>
																			</div>
																			
																			
																			
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row clearrow"></div>
										<div class="row smclearrow"></div>
										<div class="row" align="center">
											<div class="div12 pd-lr-1" align="center">
												<input type="button" name="btn_print" value="Print" id="btn_print" class="btn btn-info printbutton" onClick="PrintBook();" />
											</div>
										</div>
										<div class="row smclearrow"></div>
										<div class="row smclearrow"></div>
									</div>
								</div>
							</div>
						</div>
                    </blockquote>
                </div>
            </div>
        </div>
    </form>
    <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>

</body>
</html>

