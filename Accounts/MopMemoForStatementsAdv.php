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
if(isset($_POST["btn_save"]) == " Save "){/*
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	$abstract_net_amt 	= 	trim($_POST['txt_abstract_amt']);
	$sec_adv_amount 	= 	trim($_POST['txt_sec_adv']);
    $sd_percent 		= 	trim($_POST['txt_sd_perc']);
    $sd_amt 			= 	trim($_POST['txt_sd']);
    $wct_percent 		= 	trim($_POST['txt_wct_perc']);
    $wct_amt 			= 	trim($_POST['txt_wct']);
	$vat_percent 		= 	trim($_POST['txt_vat_perc']);
    $vat_amt 			= 	trim($_POST['txt_vat']);
	$mob_adv_percent 	= 	trim($_POST['txt_mob_adv_perc']);
	$mob_adv_amt 		= 	trim($_POST['txt_mob_adv']);
	$lw_cess_percent 	= 	trim($_POST['txt_lw_cess_perc']);
	$lw_cess_amt 		= 	trim($_POST['txt_lw_cess']);
	$incometax_percent 	= 	trim($_POST['txt_incometax_perc']);
	$incometax_amt 		= 	trim($_POST['txt_incometax']);
	$it_cess_percent 	= 	trim($_POST['txt_ITcess_perc']);
	$it_cess_amt 		= 	trim($_POST['txt_ITcess']);
	$it_edu_percent 	= 	trim($_POST['txt_ITEcess_perc']);
	$it_edu_amt 		= 	trim($_POST['txt_ITEcess']);
	$land_rent 			= 	trim($_POST['txt_rent_land']);
	$liquid_damage 		= 	trim($_POST['txt_liquid_damage']);
	$other_recovery_1 	= 	trim($_POST['txt_other_recovery_1']);
	$other_recovery_2 	= 	trim($_POST['txt_other_recovery_2']);
	$other_recovery_1_desc 	= 	trim($_POST['txt_other_recovery_1_desc']);
	$other_recovery_2_desc	= 	trim($_POST['txt_other_recovery_2_desc']);
	$nodep_machine 		= 	trim($_POST['txt_nodep_machine']);
	$nodep_mp 			= 	trim($_POST['txt_nodep_mp']);
	$nonsubmission_qa	=	trim($_POST['txt_nonsubmission_qa']);
	
    $cgst_percent 			= 	trim($_POST['txt_cgst_perc']);
    $cgst_amt 				= 	trim($_POST['txt_cgst']);
    $sgst_percent 			= 	trim($_POST['txt_sgst_perc']);
    $sgst_amt 				= 	trim($_POST['txt_sgst']);
	
	$DeleteQuery = "delete from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
	$DeleteSql = mysqli_query($dbConn,$DeleteQuery);
	
    $InsertQuery 		= 	"insert into memo_payment_accounts_edit set "
											. "sheetid = '$sheetid',  rbn = '$rbn', "
                                            . "abstract_net_amt = '$abstract_net_amt', "
											. "sec_adv_amount = '$sec_adv_amount', "
											. "cgst_percent = '$cgst_percent', "
                                            . "cgst_amt = '$cgst_amt', "
                                            . "sgst_percent = '$sgst_percent', "
                                            . "sgst_amt = '$sgst_amt', "
                                            . "sd_percent = '$sd_percent', "
                                            . "sd_amt = '$sd_amt', "
                                            . "wct_percent = '$wct_percent', "
                                            . "wct_amt = '$wct_amt', "
											. "vat_percent = '$vat_percent', "
                                            . "vat_amt = '$vat_amt', "
											. "mob_adv_percent = '$mob_adv_percent', "
											. "mob_adv_amt = '$mob_adv_amt', "
											. "lw_cess_percent = '$lw_cess_percent', "
											. "lw_cess_amt = '$lw_cess_amt', "
											. "incometax_percent = '$incometax_percent', "
											. "incometax_amt = '$incometax_amt', "
											. "it_cess_percent = '$it_cess_percent', "
											. "it_cess_amt = '$it_cess_amt', "
											. "it_edu_percent = '$it_edu_percent', "
											. "it_edu_amt = '$it_edu_amt', "
											. "land_rent = '$land_rent', "
											. "liquid_damage = '$liquid_damage', "
											. "other_recovery_1_amt = '$other_recovery_1', "
											. "other_recovery_1_desc = '$other_recovery_1_desc', "
											. "other_recovery_2_amt = '$other_recovery_2', "
											. "other_recovery_2_desc = '$other_recovery_2_desc', "
											. "non_dep_machine_equip = '$nodep_machine', "
											. "non_dep_man_power = '$nodep_mp', "
											. "nonsubmission_qa = '$nonsubmission_qa', "
											. "staffid = '$staffid', "
											. "userid = '$userid', "
											. "modifieddate = NOW(), "
                                            . "active = 1";
											//echo $InsertQuery;
    $InsertSql 	= 	mysqli_query($dbConn,$InsertQuery);
    if($InsertSql == true){
        $msg = "Recovery Details Saved Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Recovery Details Not Saved...!!! ";
	}
*/} 
if(isset($_POST["btn_skip"]) == " Skip "){
    $sheetid 			= 	trim($_POST['txt_sheetid']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
	$paytype			=   trim($_POST['txt_pay_type']);
}
if(($_GET["sheetid"] != "")&&($_GET["rbn"] != "")){ 
    $sheetid 			= 	$_GET['sheetid'];
    $rbn 				= 	$_GET['rbn'];
	$HOA 				= 	$_GET['hoa'];
	$paytype			=   $_GET['paytype'];
}
if(isset($_POST["btn_go"]) ==  'GO '){
    $sheetid 			= 	trim($_POST['cmb_work_no']);
    $rbn 				= 	trim($_POST['cmb_rbn']);
	$CCNo 				= 	trim($_POST['txt_ccno']);
	//$HOA 				= 	trim($_POST['txt_hoa']);
	$paytype			=   trim($_POST['txt_pay_type']);
	
}
//echo $rbn;exit;
if($sheetid != ""){
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
		$work_order_no 			= 	$List->work_order_no; /*  if($List->rbn == 0){$runn_acc_bill_no =1;  } else { $runn_acc_bill_no=$List->rbn +1;}*/
		$work_order_date 		= 	$List->work_order_date;
		$work_commence_date 	= 	$List->work_commence_date;
		$sch_doc 				= 	$List->date_of_completion;
		$act_doc 				= 	$List->act_doc;
		$workcost 				= 	$List->work_order_cost;
		$workcomplete 		    = 	$List->date_of_completion;
		$workextndate 		    = 	$List->work_orders_ext;
		//$rbn 		            = 	$List->rbn;
		$contid 		        = 	$List->contid;
		$hoa 		            = 	$List->hoa;
		$HoaArr = array();
		$GlobId = $List->globid;
		$HoaId  = $List->hoaid; //echo $hoa; exit;
		if(($List->hoaid != '')&&($List->hoaid != NULL)){ 
			$SelectQuery1 = "select * from hoa_master where hoamast_id IN ($HoaId)";
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
							}
						}
						array_push($HoaArr,$HoaNo." ".$ShortCode);
					}
				}
			}
		}/*else{
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
		}*/
		if(count($HoaArr)>0){
			$HoaStr = implode(",<br/> ",$HoaArr);
			$hoa = $HoaStr;
		}
	}//echo $query;exit;
}
//$IsAdvPayFlag = "";
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
			/*if(($AbstList->is_adv_pay == 'Y')&&(($AbstList->pass_order_dt == '0000-00-00')||($AbstList->pass_order_dt == NULL))){
				$SelectAbstQuery2 = "select * from abstractbook_dt where sheetid = '$sheetid' and rbn = '$rbn' and is_adv_pay = 'Y'";
				$SelectAbstSql2 = mysqli_query($dbConn,$SelectAbstQuery2);
				if($SelectAbstSql2 == true){
					if(mysqli_num_rows($SelectAbstSql2)>0){
						$IsAdvPayFlag = "Y";
					}
				}
			}*/
		}
	}
}

$IsAdvPayFlag = "";
if($paytype == "APAY"){
	$IsAdvPayFlag = "Y";
}
if($IsAdvPayFlag == "Y"){
	$WhereClause = " and is_adv_pay = 'Y'";
}else{
	$WhereClause = "";
}
if(($sheetid != "")&&($rbn != "")){
	$Acc = 0;
	$AccSelectQuery = "select * from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'".$WhereClause;
	$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
	if($AccSelectSql == true && mysqli_num_rows($AccSelectSql)>0){
		$AccList = mysqli_fetch_object($AccSelectSql);
		$abstract_net_amt = $AccList->abstract_net_amt;
		
		$upto_date_total_amount = $AccList->cmb_uptodt_amt;
		$dpm_total_amount = $AccList->cmb_ded_prev_amt;
		$slm_total_amount = $AccList->abstract_net_amt;
		
		
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
		
		$edit_flag = $AccList->edit_flag;
		$pass_order_dt = dt_display($AccList->pass_order_dt);
		$pay_order_dt = dt_display($AccList->pay_order_dt);
		$voucher_dt = dt_display($AccList->payment_dt);
		
		$is_adv_pay = $AccList->is_adv_pay;
		$adv_perc = $AccList->adv_perc;
		$adv_amt = $AccList->adv_amt;
		
		$mob_adv_amt_rec = $AccList->mob_adv_amt_rec;
		$pl_mac_adv_rec = $AccList->pl_mac_adv_rec;
		$mob_adv_int_amt = $AccList->mob_adv_int_amt;
		$pl_mac_adv_int_amt = $AccList->pl_mac_adv_int_amt;
		$hire_charges = $AccList->hire_charges;
		
		$bill_amt_gst = round($AccList->bill_amt_gst);
		$bill_amt_it  = round($AccList->bill_amt_it);
		
		$mop_date = $AccList->mop_date;
		if($mop_date == "0000-00-00"){
			$mop_date = date("Y-m-d",strtotime($AccList->modifieddate));
		}
		
		$Acc = 1;
	}else{
		header('Location: AccountsStatementStepsAdvance.php?m=1');
	}
	$BillRegNo = '';
	$SelectBillRegQuery = "select * from bill_register where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectBillRegSql 	= mysqli_query($dbConn,$SelectBillRegQuery);
	if($SelectBillRegSql == true){
		if(mysqli_num_rows($SelectBillRegSql)>0){
			$BillRegList = mysqli_fetch_object($SelectBillRegSql);
			$BillRegNo = $BillRegList->br_no;
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
										<a data-url="MopMemoForStatementsAdv?sheetid=<?php echo $sheetid; ?>&rbn=<?php echo $rbn; ?>&paytype=<?php echo $paytype; ?>">
										<div class="face-static tabbtn tabbtn-active">
											<div class="card-body padding-1">
												<div class="row">
													<i class="fa fa-check-square-o" style="font-size:14px"></i>&nbsp;&nbsp;Memo For Payments
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="MopAbtractAdv?sheetid=<?php echo $sheetid; ?>&rbn=<?php echo $rbn; ?>&paytype=<?php echo $paytype; ?>">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Abstract
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="MopCodingSheetAdv?sheetid=<?php echo $sheetid; ?>&rbn=<?php echo $rbn; ?>&paytype=<?php echo $paytype; ?>">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Coding Sheet
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="MopRecoveryParticularsAdv?sheetid=<?php echo $sheetid; ?>&rbn=<?php echo $rbn; ?>&paytype=<?php echo $paytype; ?>">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Recovery Particulars
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
													<div class="card-header inkblue-card" align="left">&nbsp;MOP Statements</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox" style="padding-top:0px; padding-bottom:0px;">
															<div class="row smclearrow"></div>
															<div class="row smclearrow"></div>
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2" style="padding-top:0px;">
																		<div class="row" align="center">
																			<div class="steps-content" align="center" id="printSection" style="width:875px">
																				<style>
																					@media print {
																						.printbutton{
																							display:none;
																						}
																						body
																						{
																						  	font-size:15px !important;
																						}
																						
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
																						.prdiv{
																							font-size:15px !important;
																						}
																					} 
																				</style>
																				<div class="TablePart" align="center" style="width:875px;">
																					<table width="100%" class="tableM" align="center">
																						<tr class="">
																							<td colspan="4" align="center" class="cboxlabel">
																								<span style="float:center"><b>GOVERNMENT OF INDIA</b></span><br/>
																								<span style="float:center"><b>DEPARTMENT OF ATOMIC ENERGY</b></span><br/>
																								<span style="float:center"><b>BARC, NRB, FRFCF - ACCOUNTS</b></span><br/><br/>
																							   	<span style="float:center"><b>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;MEMO FOR PAYMENT <?php if($IsAdvPayFlag == "Y"){ echo " ( 75% ADVANCE PAYMENT )"; } ?></b></span>
																							   	<span style="float:right">C.CODE &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; <?php echo $ccno; ?></span>
																							</td>
																						</tr>
																						<tr>
																							<td colspan="4" class="cboxlabel">
																								<span style="float:left"><b>Bill No.</b> : RAB -  <?php echo $rbn; ?></span>
																								<span style="float:right"><b>SL. No.</b>&nbsp;&nbsp;:&nbsp; <?php echo $BillRegNo;?> &nbsp;&nbsp;<b>Date</b> :&nbsp;&nbsp; <?php echo dt_display($mop_date);//$work_order_date; ?></span>
																							</td>
																						</tr>
																						
																						
																						
																						<tr>
																							<td align="left" width="250px" class="blabel">Name of Contractor</td>
																							<td colspan="3"><?php echo $name_contractor; ?> </td>
																						</tr>
																						<tr class="label">
																							<td align="left" class="blabel">Name of Work</td>
																							<td colspan="3" style="white-space:normal"><?php echo $work_name; ?> </td>
																						</tr>
																						<tr class="label">
																							<td align="left" class="blabel">Contract Value</td>
																							<td><?php echo IND_money_format($workcost); ?> </td>
																							<td align="left" class="blabel">Contract Valid Upto</td>
																							<td><?php echo dt_display($workcomplete); ?> </td>
																						</tr>
																						<!--<tr class="label">
																							<td align="left" class="blabel">Agreement No.</td>
																							<td colspan="3"><?php echo $agree_no; ?> </td>
																						</tr>-->
																						<tr class="label">
																							<td align="left" class="blabel">Agreement No.</td>
																							<td><?php echo $agree_no; ?> </td>
																							<td align="left" class="blabel">Extended Valid Upto</td>
																							<td><?php if($workextndate != '0000-00-00'){ echo dt_display($workextndate); }else{ echo ' - '; } ?> </td>
																						</tr>
																						<tr class="label">
																							<td align="left" class="blabel">Work Order No.</td>
																							<td colspan="3"><?php echo $work_order_no; ?> </td>
																						</tr>
																						<tr class="label">
																							<td align="left" class="blabel">Technical Sanction No.</td>
																							<td style="white-space:normal"><?php echo $tech_sanction; ?> </td>
																							<td align="left" colspan="2"><b>HOA</b> : <?php echo $hoa; ?> </td>
																						</tr>
																						<!--<tr class="label">
																							<td align="left" colspan="8">
																							A. Upto Date Value of Work Done - Page No. <?php echo $Uptombookpage; ?> MB No. <?php echo $Uptombookno; ?> : 
																							<?php echo $upto_date_total_amount; ?>
																							</td>
																						</tr>-->
																						<!--<tr class="label">
																							<td align="left" colspan="8">
																							B. ADD/DEDUCT Secure Advance : 
																							<?php //echo $sec_adv_amount; ?>
																							</td>
																						</tr>-->
																					</table>
																					<table width="100%" class="tableM" align="center">
																						<tr>
																							<td align="left" colspan="4" style="border-right:none;" nowrap="nowrap" class="blabel">A. Upto Date Value of Work Done - Page No. <?php echo $Uptombookpage; ?> MB No. <?php echo $Uptombookno; ?> </td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($upto_date_total_amount); ?></td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						
																						<tr>
																							<td align="left" colspan="4" style="border-right:none;" nowrap="nowrap" class="blabel">B. ADD Secured Advance  </td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($sec_adv_amount); ?></td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<!--<tr class="label">
																							<td align="left" colspan="8">B.ADD: Secure Advance &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; Rs.&nbsp;<?php echo $SecuredAdvance; ?></td>
																						</tr>-->
																						<?php $GrandTotal = round(($upto_date_total_amount + $sec_adv_amount),2); ?>
																						<tr>
																							<td align="left" colspan="4" style="border-right:none;" nowrap="nowrap" class="blabel">C. Grand Total  </td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($GrandTotal); ?></td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						
																						<tr>
																							<td align="left" colspan="4" style="border-right:none;" nowrap="nowrap" class="blabel">D. Less: Previous PMT Page No. <?php echo $Uptombookpage; ?> MB No.<?php echo $Uptombookno; ?> (-)  </td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($dpm_total_amount); ?></td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						
																						
																						<?php $NetTotal = round(($GrandTotal - $dpm_total_amount),2); $CodeAmount = $NetTotal; ?>
																						<tr>
																							<td align="left" colspan="4" style="border-right:none;" nowrap="nowrap" class="blabel">Net Total &nbsp;&nbsp;[C-D]</td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($NetTotal); ?></td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
														
													
																						<tr>
																							<td align="left" colspan="8" class="blabel">8. Recoveries :</td>
																							
																						</tr>
																					<!--</table>
																					<table width="100%" class="tableM" align="center">-->
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;[a]</td>
																							<td align="left" width="330px" class="blabel nobdrlr">Recoveries Creditable Under Works</td>
																							<td align="left" width="30px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="30px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php $rca = 1; $TotalRecovery = 0; if($lw_cess_amt > 0){ $TotalRecovery  = $TotalRecovery + $lw_cess_amt; $CodeAmount = $CodeAmount - $lw_cess_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  C.L.CESS @ <?php echo $lw_cess_percent; ?> % On (<?php echo IND_money_format($abstract_net_amt); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($lw_cess_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($mob_adv_amt_rec > 0){ $TotalRecovery  = $TotalRecovery + $mob_adv_amt_rec; $CodeAmount = $CodeAmount - $mob_adv_amt_rec; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  Mobilisation Advance (Rec.)</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($mob_adv_amt_rec); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($pl_mac_adv_rec > 0){ $TotalRecovery  = $TotalRecovery + $pl_mac_adv_rec; $CodeAmount = $CodeAmount - $pl_mac_adv_rec; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  Plant & Machinery Advance (Rec.)</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($pl_mac_adv_rec); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if(($adv_amt > 0)&&($IsAdvPayFlag != 'Y')){ $TotalRecovery  = $TotalRecovery + $adv_amt; $CodeAmount = $CodeAmount - $adv_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  75% Advance (Rec.)</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($adv_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($other_recovery_1_amt != 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_1_amt; $CodeAmount = $CodeAmount - $other_recovery_1_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  Other Recovery </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($other_recovery_1_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($other_recovery_2_amt > 0){ $TotalRecovery  = $TotalRecovery + $other_recovery_2_amt; $CodeAmount = $CodeAmount - $other_recovery_2_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  Other Recovery 2</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($other_recovery_2_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;[b]</td>
																							<td align="left" width="330px" class="blabel nobdrlr">Recoveries Creditable To Other Head of Account</td>
																							<td align="left" width="30px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="30px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="blabel nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="blabel" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php $rcb = 1; if($incometax_amt > 0){  $TotalRecovery  = $TotalRecovery + $incometax_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  INCOME TAX @ <?php echo $incometax_percent; ?> % On (<?php echo IND_money_format($bill_amt_it); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($incometax_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($it_cess_amt > 0){  $TotalRecovery  = $TotalRecovery + $it_cess_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  IT CESS @ <?php echo $it_cess_percent; ?> % On (<?php echo IND_money_format($NetTotal); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($it_cess_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($it_edu_amt > 0){  $TotalRecovery  = $TotalRecovery + $it_edu_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  IT EDUCATION @ <?php echo $it_edu_percent; ?> % On (<?php echo IND_money_format($NetTotal); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($it_edu_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($cgst_amt > 0){  $TotalRecovery  = $TotalRecovery + $cgst_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  TDS On CGST @ <?php echo $cgst_percent; ?> % On (<?php echo IND_money_format($bill_amt_gst); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($cgst_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($sgst_amt > 0){  $TotalRecovery  = $TotalRecovery + $sgst_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  TDS On SGST @ <?php echo $sgst_percent; ?> % On (<?php echo IND_money_format($bill_amt_gst); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($sgst_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($igst_amt > 0){  $TotalRecovery  = $TotalRecovery + $igst_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  TDS On IGST @ <?php echo $igst_percent; ?> % On (<?php echo IND_money_format($bill_amt_gst); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($igst_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($sd_amt > 0){  $TotalRecovery  = $TotalRecovery + $sd_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  S.D. @ <?php echo $sd_percent; ?> % On (<?php echo IND_money_format($abstract_net_amt); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($sd_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($wct_amt > 0){  $TotalRecovery  = $TotalRecovery + $wct_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  WCT @ <?php echo $wct_percent; ?> % On (<?php echo IND_money_format($NetTotal); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($wct_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($vat_amt > 0){  $TotalRecovery  = $TotalRecovery + $vat_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  VAT @ <?php echo $vat_percent; ?> % On (<?php echo IND_money_format($NetTotal); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($vat_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php /*if($lw_cess_amt > 0){  $TotalRecovery  = $TotalRecovery + $lw_cess_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  L.W CESS AMOUNT @ <?php echo $lw_cess_percent; ?> % On (<?php echo IND_money_format($NetTotal); ?> )</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($lw_cess_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php }*/ ?>
																						
																						<?php if($land_rent > 0){ $TotalRecovery  = $TotalRecovery + $land_rent; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>]  LAND RENT</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($land_rent); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($liquid_damage > 0){ $TotalRecovery  = $TotalRecovery + $liquid_damage; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]  LIQUID DAMAGE</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($liquid_damage); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						
																						<?php if($non_dep_machine_equip > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_machine_equip; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>] NON DEP MACHINE EUIP</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($non_dep_machine_equip); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($non_dep_man_power > 0){ $TotalRecovery  = $TotalRecovery + $non_dep_man_power; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>] Non Dep. of Man Power</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($non_dep_man_power); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($nonsubmission_qa > 0){ $TotalRecovery  = $TotalRecovery + $nonsubmission_qa; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rca; $rca++; ?>]Non Submission of QA Doc. </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($nonsubmission_qa); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($electricity_cost > 0){ $TotalRecovery  = $TotalRecovery + $electricity_cost; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>] Electricity Recovery </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($electricity_cost); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($water_cost > 0){ $TotalRecovery  = $TotalRecovery + $water_cost; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>] Water Recovery </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($water_cost); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($mob_adv_int_amt > 0){ $TotalRecovery  = $TotalRecovery + $mob_adv_int_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>] Mob. Adv. Interest </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($mob_adv_int_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($pl_mac_adv_int_amt > 0){ $TotalRecovery  = $TotalRecovery + $pl_mac_adv_int_amt; ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="labelmedium nobdrlr">[<?php echo $rcb; $rcb++; ?>] P&M Adv. Interest </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr"><?php echo IND_money_format($pl_mac_adv_int_amt); ?></td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php if($TotalRecovery > 0){ ?>
																						<tr class="label">
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="330px" class="blabel nobdrlr">Total Recovery </td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($TotalRecovery); ?></td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						<?php } ?>
																						<?php $ChequeAmount = round($NetTotal - $TotalRecovery); ?>
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;[c]</td>
																							<td align="left" width="330px" class="blabel nobdrlr">By Cheque</td>
																							<td align="left" width="30px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="right" width="130px" class="labelmedium nobdrlr">&nbsp;</td>
																							<td align="left" width="30px" class="blabel nobdrlr">: Rs.</td>
																							<td align="right" width="130px" class="blabel nobdrlr"><?php echo IND_money_format($ChequeAmount); ?></td>
																							<td align="left" width="100px" class="labelmedium" style="border-left:none;">&nbsp;</td>
																						</tr>
																						
																						<?php if($IsAdvPayFlag == "Y"){ ?>
																						<tr class="label">
																							<td align="left" colspan="7" class="blabel">
																								&nbsp;<?php echo $adv_perc; ?>% of <?php 
																								$ActAdvAmt = round($ChequeAmount * $adv_perc / 100);
																								echo IND_money_format($ChequeAmount); 
																								?> = <?php echo IND_money_format($ActAdvAmt); ?>
																								&emsp;&emsp; Say = <?php echo IND_money_format($adv_amt); ?>
																							</td>
																						</tr>	
																						<?php $CodeAmount = $adv_amt; } ?>
																						
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="25px" class="labelmedium nobdrlr" style="border-right:none;">Document Prepared</td>
																							<td align="left" colspan="5" width="130px" class="labelmedium" style="border-left:none;">Register Entries</td>
																						</tr>
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="25px" class="labelmedium nobdrlr" style="border-right:none;">1. Coding Amount</td>
																							<td align="left" colspan="5" width="130px" class="labelmedium" style="border-left:none;">: Rs. <?php $CodeAmount = $CodeAmount; echo IND_money_format($CodeAmount); ?></td>
																						</tr>
																						<tr class="label">
																							<td align="left" width="25px" class="blabel" style="border-right:none;">&nbsp;</td>
																							<td align="left" width="25px" class="labelmedium" style="border-right:none;">2. I.T. Register Sl.No.</td>
																							<td align="left" colspan="5" width="130px" class="labelmedium" style="border-left:none;">: ____________________</td>
																						</tr>
																						
																						<!--<td colspan="8" align="center">
																							<span style="float:center"> Cod. Amt. ................. <?php //echo $rbn; ?></span>
																						</td>-->
																						<!--<tr class="label">
																							<td align ="center">Assitantsp[I]<br> 10.4.2018</td>
																							<td align ="center">Ducument Prepared</td>
																							<td align ="center">Registed Entries</td>
																						</tr>
																						<tr class="label">
																							<td align ="center"></td>
																							<td align ="center">1. MB P.No. ___________</td>
																							<td align ="center">1. C.L.  P.No</td>
																						</tr>
																						<tr class="label">
																							<td align ="center">Assitantsp[II]</td>
																							<td align ="center">2. Cod. Amt. 3,81,64,816</td>
																							<td align ="center">2. B.C  P.No</td>
																						</tr>
																						<tr class="label">
																							<td align ="center"></td>
																							<td align ="center">3. I.T.C  ______________</td>
																							<td align ="center">3. S.D  P.No</td>
																						</tr>
																						<tr class="label">
																							<td align ="center">A.A.O</td>
																							<td align ="center">4. W.C.T _____________</td>
																							<td align ="center">4. MRR  P.No</td>
																						</tr>
																						<tr class="label">
																							<td align ="center"></td>
																							<td align ="center">5. Recovery St  ________</td>
																							<td align ="center">5. S.C P .No</td>
																						</tr>
																						<tr class="label">
																							<td align ="center">A.O</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																						</tr>
																						<tr class="label">
																							<td align ="center">D.C.A</td>
																							<td align ="center"></td>
																							<td align ="center"></td>
																						</tr>-->
																					</table>
																					
																					<!--<p style='page-break-after:always; background-color:#f1f1f1; text-align:center' align="center"></p>-->
																					<br/>
																					<div class="prdiv" style="min-height:20px;">
																						<br/><br/><br/><br/>
																						<div style="width:20%; float:left" class="labelmedium blabel">DA</div>
																						<div style="width:20%; float:left" class="labelmedium blabel">AA</div>
																						<div style="width:20%; float:left" class="labelmedium blabel">AAO</div>
																						<div style="width:20%; float:left" class="labelmedium blabel">SAO</div>
																						<div style="width:20%; float:left" class="labelmedium blabel">DCA</div>
																						<br/>
																					</div>
																					
																					<!--<table width="100%" class="tableM" align="center">
																						<tr class="label">
																							<td colspan="3" align="center">Government Of India<br>Deportment Of Atomic Energy<br>FRFCF, NRB, BARCF</td>
																						</tr>
																						<tr class="label" align="center">
																							<td colspan="3">
																								<span style="float:center"><b>ACCOUNTS (Works)</b></span>
																							</td>
																						</tr>
																						<tr class="label">
																							<td colspan="3">
																								<span style="float:right">&nbsp;&nbsp;Date :&nbsp;&nbsp; <?php echo date('d/m/Y'); ?></span>
																							</td>
																						</tr>
																						<tr class="label">
																							<td align="left">&nbsp;</td>
																							<td align="left"class="labelmedium">Name Of Payee</td>
																							<td align="left"> :&nbsp;&nbsp;<?php echo $name_contractor; ?></td>
																						</tr>
																						<tr class="label">
																							<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																							<td align="left"class="labelmedium">Account Number </td>
																							<td align="left">:&nbsp;&nbsp;<?php echo $ContAccNo; ?></td>
																						</tr>
																						
																						<tr class="label">
																							<td align="left">A.A.O.</td>
																							<td align="left">Bank/Branch/Code </td>
																							<td align="left">:&nbsp;&nbsp;<?php echo $ContBankName; ?> / <?php echo $ContBankBrAddr; ?></td>
																						</tr>
																						
																						<tr class="label">
																							<td align="left"></td>
																							<td align="left">Mode Of Payment </td>
																							<td align="left">:&nbsp;&nbsp;<?php echo "CHEQUE"; ?></td>
																						</tr>
																						<tr class="label">
																							<td align="left">A.O.</td>
																							<td align="left">IFSC Code </td>
																							<td align="left">:&nbsp;&nbsp;<?php echo $ContBankIfsc; ?></td>
																						</tr>
																						
																						<tr class="label">
																							<td align="left">&nbsp;</td>
																							<td align="left">Amount (Rs.)</td>
																							<td align="left">:&nbsp;&nbsp;<?php echo IND_money_format($ChequeAmount); ?></td>
																						</tr>
																						
																						<tr class="label">
																							<td align="left">D.C.A.</td>
																							<td align="left">Payment Passed On</td>
																							<td align="left">:&nbsp;&nbsp;<?php echo $pass_order_dt; ?></td>
																						</tr>
																						<tr class="label">
																							<td colspan="3">
																								<span style="float:right"><br><br>&nbsp;&nbsp;<br>Assistant Accounts Officer<br><br></span>
																							</td>
																						</tr>
																					</table>-->
																					<!--<p style='page-break-after:always; background-color:#f1f1f1; text-align:center' align="center"></p>-->
																					<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"> 
																					<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>"> 
																					<input type="hidden" name="txt_hoa" id="txt_hoa" value="<?php echo $HOA; ?>">
																					<input type="hidden" name="txt_pay_type" id="txt_pay_type" class="textboxdisplay" value="<?php echo $paytype; ?>">
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

