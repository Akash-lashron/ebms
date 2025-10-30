<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
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
$AbstractMopCCno = "";
if(isset($_GET['ccno'])){
	if($_GET['ccno'] != ''){
		$AbstractMopCCno = $_GET['ccno'];
		$AbstractMopSheetId = $_GET['sheetid'];
		$AbstractMopLinkId = $_GET['linkid'];
	}
}
/*  

//	THIS IS BEFORE 27/06/2025
$GlobPartARecArr = array("LCESS"=>"LCess","MOB"=>"Mob.Adv. Rec.","PM"=>"P&M.Adv. Rec.","HIRE"=>"Hire Charges","OTH"=>"Other Recoveries");
$GlobPartBRecArr = array("CGST"=>"CGST","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"Water Charges","EC"=>"Electricity Charges","MOBINT"=>"Mob. Adv. Interest","PMINT"=>"P&M Adv. Interest");

*/
$GlobPartARecArr = array();
$GlobPartBRecArr = array();
$SelectPayRecMaster = "SELECT * FROM pay_rec_master WHERE active = 1 AND pr_type = 'R'";
$SelectPayRecMasterSql = mysqli_query($dbConn,$SelectPayRecMaster);
if($SelectPayRecMasterSql == true){
	if(mysqli_num_rows($SelectPayRecMasterSql)>0){
		while($PayRecMasterList = mysqli_fetch_object($SelectPayRecMasterSql)){
			if($PayRecMasterList->rec_type == 'A'){
				$GlobPartARecArr[$PayRecMasterList->prcode] = $PayRecMasterList->pr_desc;
			}
			if($PayRecMasterList->rec_type == 'B'){
				$GlobPartBRecArr[$PayRecMasterList->prcode] = $PayRecMasterList->pr_desc;
			}
		}
	}
}
//$MopRecArr = array("LCESS"=>"LCESS","MOB"=>"MOB","PM"=>"PM","HIRE"=>"HIRE","OTH"=>"OTH","CSGT"=>"CSGT","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"WC","EC"=>"EC","MOBINT"=>"MOBINT","PMINT"=>"PMINT");
if(isset($_POST['btnSave']) == " Submit "){
	$SaveCCno 		= $_POST['txt_ccno'];
	$SaveSrNo 		= $_POST['txt_sr_no'];
	$SaveRbn 		= $_POST['txt_rbn'];
	$SaveWorkName 	= $_POST['txt_work_name'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	$SaveGlobId 	= $_POST['txt_globid'];
	$SaveUnitId 	= $_POST['txt_unitid'];
	$SavePinNo 		= $_POST['txt_pinno'];
	$SaveContId 	= $_POST['cmb_contractor'];
	$SavePanNo 		= $_POST['txt_pan_no'];
	$SaveItPerc 	= $_POST['txt_it_perc'];
	$SaveGstNo 		= $_POST['txt_gst_no'];
	$SaveGstRate 	= $_POST['txt_gst_rate'];
	$SaveBankAccNo 	= $_POST['txt_bank_acc'];
	$SaveBankName 	= $_POST['txt_bank_name'];
	$SaveBankId 	= $_POST['txt_bank_id'];
	$SaveBranch 	= $_POST['txt_branch'];
	$SaveIfscCode 	= $_POST['txt_ifsc'];
	
	$SaveLdcStatus 	= $_POST['txt_ldc_status'];
	$SaveLdcCertNo 	= $_POST['txt_ldc_cert_no'];
	$SaveLDcAmt 	= $_POST['txt_ldc_amt'];
	$SaveLdcValidTo = $_POST['txt_ldc_valid_to'];
	
	$SaveUptoDtAmt 	= $_POST['txt_upto_dt_amt'];
	$SaveDedPrevAmt = $_POST['txt_ded_prev_amt'];
	$SaveThisBillAmt= $_POST['txt_bill_value'];
	
	$SaveSecAdvAmt 	= $_POST['txt_sec_adv_amt'];
	$SaveMobAdvAmt 	= $_POST['txt_mob_adv_amt'];
	$SaveEscAmt 	= $_POST['txt_esc_amt'];
	$SavePmAdvAmt 	= $_POST['txt_pm_adv_amt'];
	$SaveNetAmt 	= $_POST['txt_net_amt'];
	$SaveBillAmtGst = $_POST['txt_bill_amt_gst'];
	$SaveBillAmtIt 	= $_POST['txt_bill_amt_it'];
	$SaveGstAmount  = round(($SaveBillAmtGst * $SaveGstRate / 100),2);
	
	$SaveRecDescArr 		= $_POST['txt_rec_desc'];
	$SaveRecDescIdArr 		= $_POST['hid_rec_desc'];
	$SaveRecPercArr 		= $_POST['txt_rec_perc'];
	$SaveRecAmtArr 			= $_POST['txt_rec_amt'];
	$SaveRecHoaScodeDescArr = $_POST['txt_rec_hoa_shcode'];
	$SaveRecHoaScodeRecArr 	= $_POST['hid_rec_hoa_rec_code'];
	$SaveRecHoaScodeIdArr 	= $_POST['hid_rec_hoa_shcode_id'];
	$SaveRecTypeArr 		= $_POST['txt_rec_type'];
	
	$SaveTotRecAmt 		= $_POST['txt_tot_rec_amt'];
	$SaveTotNetPayAmt 	= $_POST['txt_net_pay_amt'];
	$SaveIsAdvance 		= $_POST['ch_is_advance'];
	$SaveAdvPerc 		= $_POST['txt_adv_perc'];
	$SavePayableAmt 	= $_POST['txt_net_pay_adv_amt'];
	$SaveWorkHoa 		= $_POST['txt_work_hoa'];
	$SaveWorkHoaId 		= $_POST['txt_work_hoaid'];
	$SaveBillMode 		= $_POST['txt_bill_mode'];
	
	$MopDate 		= dt_format($_POST['txt_mopdate']);
	
	$SelectContQuery1 = "SELECT * FROM contractor WHERE contid = '$SaveContId'";
	$SelectContSql1   = mysqli_query($dbConn,$SelectContQuery1);
	if($SelectContSql1 == true){
		if(mysqli_num_rows($SelectContSql1)>0){
			$ContList1 = mysqli_fetch_object($SelectContSql1);
			$SaveLdcCertNo 		= $ContList1->txt_ldc_cert_no;
			$SaveLDcValidFrom 	= $ContList1->ldc_validty_from;
			$SaveLDcAmt 		= $ContList1->txt_ldc_amt;
			$SaveLdcValidTo 	= $ContList1->txt_ldc_valid_to;
		}
	}
	if($SaveLdcStatus != 'V'){
		$IsLdcApplicable 	= '';
		$SaveLdcCertNo 		= '';
		$SaveLDcValidFrom 	= '';
		$SaveLDcAmt 		= '';
		$SaveLdcValidTo 	= '';
	}else{
		$IsLdcApplicable 	= 'Y';
	}
	
	if($SaveBillMode == "OFF"){
		$OffRowExist = 0;
		$SelectCheckQuery1 = "SELECT * FROM abstractbook WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
		$SelectCheckSql1   = mysqli_query($dbConn,$SelectCheckQuery1);
		if($SelectCheckSql1 == true){
			if(mysqli_num_rows($SelectCheckSql1)>0){
				$CHList1 = mysqli_fetch_object($SelectCheckSql1);
				$OffRowExist = 1;
			}
		}
		if($OffRowExist == 0){
			$SelectCheckQuery2 = "SELECT * FROM bill_register WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
			$SelectCheckSql2   = mysqli_query($dbConn,$SelectCheckQuery2);
			if($SelectCheckSql2 == true){
				if(mysqli_num_rows($SelectCheckSql2)>0){
					$CHList2 = mysqli_fetch_object($SelectCheckSql1);
				}
			}
			$InsertOffModeQuery = "INSERT INTO abstractbook SET abs_book_date = NOW(), sheetid = '$SaveSheetId', rbn = '$SaveRbn', fromdate = NOW(), todate = NOW(), 
			upto_date_total_amount = '$SaveUptoDtAmt', dpm_total_amount = '$SaveDedPrevAmt', slm_total_amount = '$SaveThisBillAmt', secured_adv_amt = '$SaveSecAdvAmt', 
			mob_adv_amt = '$SaveMobAdvAmt', pl_mac_adv_amt = '$SavePmAdvAmt', hire_charges = '', is_rab = '$CHList2->is_rab', is_final_bill = '$CHList2->is_final_bill', 
			is_sec_adv = '$CHList2->is_sec_adv', is_mob_adv = '$CHList2->is_mob_adv', is_esc = '$CHList2->is_esc', rab_status = 'P', active = 1";
			$InsertOffModeSql   = mysqli_query($dbConn,$InsertOffModeQuery);
		}else{
			$InsertOffModeQuery = "UPDATE abstractbook SET abs_book_date = NOW(), sheetid = '$SaveSheetId', rbn = '$SaveRbn', fromdate = NOW(), todate = NOW(), 
			upto_date_total_amount = '$SaveUptoDtAmt', dpm_total_amount = '$SaveDedPrevAmt', slm_total_amount = '$SaveThisBillAmt', secured_adv_amt = '$SaveSecAdvAmt', 
			mob_adv_amt = '$SaveMobAdvAmt', pl_mac_adv_amt = '$SavePmAdvAmt', hire_charges = '', is_rab = '$CHList2->is_rab', is_final_bill = '$CHList2->is_final_bill', 
			is_sec_adv = '$CHList2->is_sec_adv', is_mob_adv = '$CHList2->is_mob_adv', is_esc = '$CHList2->is_esc', rab_status = 'P', active = 1
			WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
			$InsertOffModeSql   = mysqli_query($dbConn,$InsertOffModeQuery);
		}
	}
	
	if($SaveAdvPerc == "Y"){
		$ChequeAmount = $SavePayableAmt;
	}else{
		$ChequeAmount = $SaveTotNetPayAmt;
	}
	$MopId = ""; $IsAdv = ""; //$MopDate = date("Y-m-d");
	$SelectQuery = "SELECT * FROM memo_payment_accounts_edit WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$MopList = mysqli_fetch_object($SelectSql);
			$MopId = $MopList->memoid;
			if($MopList->is_adv_pay == "Y"){
				$IsAdv = "Y";
			}
			$MopDate = $MopList->mop_date;
		}
	}
	if($IsAdv != "Y"){
		$DeleteQuery 	= "DELETE FROM memo_payment_accounts_edit WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
		$DeleteSql 		= mysqli_query($dbConn,$DeleteQuery);
	}
	$DeleteQuery 	= "DELETE FROM mop_rec_dt WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn' AND is_adv_pay != 'Y'";
	$DeleteSql 		= mysqli_query($dbConn,$DeleteQuery);

	$SavePoDate  = dt_format($_POST['txt_podate']);
	$ResStr = ""; $QueryArr = array();
	if(count($SaveRecDescArr)>0){
		foreach($SaveRecDescArr as $Key => $Value){
			$SaveRecDesc   			= $SaveRecDescArr[$Key];
			$SaveRecDescId 			= $SaveRecDescIdArr[$Key];
			$SaveRecPerc 			= $SaveRecPercArr[$Key];
			$SaveRecAmt 			= $SaveRecAmtArr[$Key];
			$SaveRecHoaScodeDesc 	= $SaveRecHoaScodeDescArr[$Key];
			$SaveRecHoaScodeRec 	= $SaveRecHoaScodeRecArr[$Key];
			$SaveRecHoaScodeId 		= $SaveRecHoaScodeIdArr[$Key];
			$SaveRecType 			= $SaveRecTypeArr[$Key];
			if($SaveRecDescId == "LCESS"){ $ResStr .= ", lw_cess_percent = '".$SaveRecPerc."', lw_cess_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "MOB"){ $ResStr .= ", mob_adv_amt_rec = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "PM"){ $ResStr .= ", pl_mac_adv_rec = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "HIRE"){ $ResStr .= ", hire_charges = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "OTH"){ $ResStr .= ", other_recovery_1_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "CGST"){ $ResStr .= ", cgst_tds_perc = '".$SaveRecPerc."', cgst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "SGST"){ $ResStr .= ", sgst_tds_perc = '".$SaveRecPerc."', sgst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "IGST"){ $ResStr .= ", igst_tds_perc = '".$SaveRecPerc."', igst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "IT"){ $ResStr .= ", incometax_percent = '".$SaveRecPerc."', incometax_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "SD"){ $ResStr .= ", sd_percent = '".$SaveRecPerc."', sd_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "WC"){ $ResStr .= ", water_cost = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "EC"){ $ResStr .= ", electricity_cost = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "MOBINT"){ $ResStr .= ", mob_adv_int_perc = '".$SaveRecPerc."', mob_adv_int_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "PMINT"){ $ResStr .= ", pl_mac_adv_int_perc = '".$SaveRecPerc."', pl_mac_adv_int_amt = '".$SaveRecAmt."'"; }
			$InsertHoaQuery = ", sheetid = '$SaveSheetId', globid = '', rbn = '$SaveRbn', rec_type = '$SaveRecType', rec_code = '$SaveRecHoaScodeRec', 
			rec_perc = '$SaveRecPerc', rec_amt = '$SaveRecAmt', shortcode_id = '$SaveRecHoaScodeId', createdby = '".$_SESSION['sid']."', createdon = NOW()";
			array_push($QueryArr,$InsertHoaQuery);
		}
	}
	if($IsAdv == "Y"){
		$InsertQuery 	= "UPDATE memo_payment_accounts_edit SET mop_date = '$MopDate', sheetid = '$SaveSheetId', rbn = '$SaveRbn', contid = '$SaveContId', cbdtid = '$SaveBankId', 
						  cmb_uptodt_amt_civil = cmb_uptodt_amt, cmb_ded_prev_amt_civil = cmb_ded_prev_amt, 
						  cmb_uptodt_amt = '$SaveUptoDtAmt', cmb_ded_prev_amt = '$SaveDedPrevAmt', abstract_net_amt = '$SaveThisBillAmt', 
						  sec_adv_amt = '$SaveSecAdvAmt', sec_adv_amount = '$SaveSecAdvAmt', esc_amt = '$SaveEscAmt', pl_mac_adv_amt = '$SavePmAdvAmt', mob_adv_amt = '$SaveMobAdvAmt', 
						  bill_amt_gst = '$SaveBillAmtGst', bill_amt_it = '$SaveBillAmtIt', 
						  gst_rate = '$SaveGstRate', gst_amount = '$SaveGstAmount', is_ldc_appl = '$IsLdcApplicable', ldc_certi_no = '$SaveLdcCertNo', ldc_validty_from = '$SaveLDcValidFrom', ldc_max_amt = '$SaveLDcAmt', ldc_validity = '$SaveLdcValidTo', 
						  pan_type = '' ".$ResStr.", net_payable_amt = '$ChequeAmount',  
						  edit_flag = 'EDIT', mop_type = 'RAB', hoa = '$SaveWorkHoa', hoaid = '$SaveWorkHoaId', bill_mode = '$SaveBillMode', staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', modifieddate = NOW() WHERE memoid = '$MopId'";
		$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
	}else{
		$InsertQuery 	= "INSERT INTO memo_payment_accounts_edit SET mop_date = '$MopDate', sheetid = '$SaveSheetId', rbn = '$SaveRbn', contid = '$SaveContId', cbdtid = '$SaveBankId', 
						  cmb_uptodt_amt_civil = cmb_uptodt_amt, cmb_ded_prev_amt_civil = cmb_ded_prev_amt, 
						  cmb_uptodt_amt = '$SaveUptoDtAmt', cmb_ded_prev_amt = '$SaveDedPrevAmt', abstract_net_amt = '$SaveThisBillAmt', 
						  sec_adv_amt = '$SaveSecAdvAmt', sec_adv_amount = '$SaveSecAdvAmt', esc_amt = '$SaveEscAmt', pl_mac_adv_amt = '$SavePmAdvAmt', mob_adv_amt = '$SaveMobAdvAmt', 
						  bill_amt_gst = '$SaveBillAmtGst', bill_amt_it = '$SaveBillAmtIt', 
						  gst_rate = '$SaveGstRate', gst_amount = '$SaveGstAmount', is_ldc_appl = '$IsLdcApplicable', ldc_certi_no = '$SaveLdcCertNo', ldc_validty_from = '$SaveLDcValidFrom', ldc_max_amt = '$SaveLDcAmt', ldc_validity = '$SaveLdcValidTo', 
						  pan_type = '' ".$ResStr.", net_payable_amt = '$ChequeAmount',  
						  edit_flag = 'EDIT', mop_type = 'RAB', hoa = '$SaveWorkHoa', hoaid = '$SaveWorkHoaId', bill_mode = '$SaveBillMode', staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', modifieddate = NOW()";
		$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
	}
	$MopId = mysqli_insert_id($dbConn);
	if(count($QueryArr)>0){
		foreach($QueryArr as $Query){
			$RecQuery 		= "INSERT INTO mop_rec_dt SET mopid = '$MopId'".$Query;
			//echo $RecQuery."<br/>";
			$RecQueryExe	= mysqli_query($dbConn,$RecQuery);
		}
	}
	if($InsertSql == true){
		$msg = "Memo of Payment data saved successfully";
	}else{
		$msg = "Error : Memo of Payment data not saved. Please try again.";
	}
	$SaveAbstCcno 	= $_POST['txt_abstract_ccno'];
	$SaveAbstLinkId 	= $_POST['txt_abstract_linkid'];
	$SaveAbstSheetId 	= $_POST['txt_abstract_sheetid'];
	if(($SaveAbstCcno != '')&&($SaveAbstLinkId != '')&&($SaveAbstSheetId)){
		header('Location: AbstMBook_Print_Common_Accounts.php?workno='.$SaveAbstSheetId.'&linkid='.$SaveAbstLinkId.'&view=');
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
	function find_workname()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "AccountsStatementSteps.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
</script>
<style>
.chosen-container-single .chosen-single{
	border:1px solid #3D6CBE !important;
	padding:3px 4px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	width:100% !important;
	border-radius:8px !important;
	font-size:11px;
	font-weight:500;
}
.chosen-container .chosen-results{
	font-weight:500;
	font-size:11px;
}
.chosen-container .chosen-results li.active-result {
  	display: list-item;
  	cursor: pointer;
  	font-size: 11px !important;
}
.chosen-container .chosen-results li{
	padding: 4px 6px;
	line-height: 11px;
}
.chosen-container .chosen-results li.group-result {
  	display: list-item;
  	font-weight: 600;
  	cursor: default;
  	font-size: 11px;
}
.chosen-container-single .chosen-single span{
	font-weight: 500;
}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
						<!--<div class="row smclearrow"></div>-->
                        <form name="form" method="post" action="">
						<div class="row">
							<div class="box-container box-container-lg lg-box" align="center">
								<div class="div12">
									<div class="card cabox" style="margin-bottom:1px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Memo of Payment</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox" style="padding-top:0px; padding-bottom:0px;">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2" style="padding-top:0px;">
																<div class="row" align="center">
																	
																	<!--<div class="div12 smlboxlabel">
																		<div class="div2">
																			<span class="lboxlabel">CCNO</span>
																			<input type="text" name="txt_ccno" id="txt_ccno" class="statictboxsm bordd2" value="">
																			<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
																		</div>
																		<div class="div3">
																			<span class="lboxlabel">SNo.</span>
																			<input type="text" name="txt_sr_no" id="txt_sr_no" class="statictboxsm">
																			&emsp;
																			<span class="lboxlabel">RAB</span>
																			<input type="text" name="txt_rbn" id="txt_rbn" class="statictboxsm">
																		</div>
																		<div class="div1">
																			<span class="lboxlabel">Work Name</span>
																		</div>
																		<div class="div6">
																			<input type="text" name="txt_rbn" id="txt_rbn" class="dynamicboxlg">
																		</div>
																	</div>-->
																	
																	<div class="div12 smlboxlabel">
																		<!--<div class="div2">
																			<span class="lboxlabel">CCNO</span>
																			<input type="text" name="txt_ccno" id="txt_ccno" class="statictboxsm bordd2" value="">
																			<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
																		</div>-->
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;CCNO</div>
																			<!--<div>
																				<input list="VehicleNoList" type="text" name="txt_ccno" id="txt_ccno" class="dynamicboxlg" autocomplete="off" required />
																			</div>
																			<datalist id="VehicleNoList" style="color:#C80B5B; font-size:16px">
																				<?php //echo $objBind->BindCont(0); ?>
																				<option value="I">Inclusive</option>
																				<option value="E">Exclusive</option>
																			</datalist>-->
																			<div class="custom-search-input">
																				<div class="input-group">
																					<input list="VehicleNoList" type="text" name="txt_ccno" id="txt_ccno" class="dynamicboxlg" autocomplete="off" placeholder="Search" required/>
																					<!--<span class="input-group-btn">
																						<button type="button" disabled>
																							<span class="fa fa-search"></span>
																						</button>
																					</span>-->
																					<datalist id="VehicleNoList" style="color:#C80B5B; font-size:16px">
																						<?php echo $objBind->BindAllCCno(0); ?>
																					</datalist>
																				</div>
																			</div>
																		</div>
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;</div>
																			<div>
																				<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
																			</div>
																		</div>
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;B.R.No.</div>
																			<div>
																				<input type="text" name="txt_sr_no" id="txt_sr_no" class="dynamicboxlg inp disable" required />
																			</div>
																		</div>
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;RAB</div>
																			<div>
																				<input type="text" name="txt_rbn" id="txt_rbn" class="dynamicboxlg inp disable" required />
																			</div>
																		</div>
																		<div class="div5 pd-lr-1">
																			<div class="lboxlabel">&nbsp;Name of the Work &nbsp;<a data-url="WorkMaster"><font class="efont ptr BankData">[ <i class="fa fa-folder-o" style="font-size:13px; top:1px; position:relative"></i> Work Master ]</font></a></div>
																			<div>
																				<input type="text" name="txt_work_name" id="txt_work_name" class="dynamicboxlg inp disable" required />
																				<input type="hidden" name="txt_sheetid" id="txt_sheetid" class="tboxsmclass bordd1" readonly="" />
																				<input type="hidden" name="txt_globid" id="txt_globid" class="tboxsmclass bordd1" readonly="" />
																				<input type="hidden" name="txt_unitid" id="txt_unitid" class="tboxsmclass bordd1" readonly="" value="6" />
																				<input type="hidden" name="txt_pinno" id="txt_pinno" class="tboxsmclass bordd1" readonly="" value="712" />
																			</div>
																		</div>
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;SD ( % )</div>
																			<div>
																				<input type="text" name="txt_work_sd_perc" id="txt_work_sd_perc" class="dynamicboxlg inp inp2 disable" required />
																			</div>
																		</div>
																		<div class="div2 pd-lr-1">
																			<div class="lboxlabel">&nbsp;SD Amount &#8377; <font class="efont ptr SDBGData">[ <i class="fa fa-folder-open-o" style="font-size:13px; top:1px; position:relative"></i> BG ]</font></div>
																			<div>
																				<input type="text" name="txt_work_sd_amt" id="txt_work_sd_amt" class="dynamicboxlg inp inp2 disable" required />
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
									</div>
								</div>
								<!--<div class="row smclearrow"></div>-->
								<div class="div4">
									<div class="card cabox" style="margin-top:0px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Contractor & Bank Details</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Contractor Name</div>
																		<div>
																			<!--<input type="text" name="cmb_contractor" id="cmb_contractor" class="dynamicboxlg inp disable" required />-->
																			<select name="cmb_contractor" id="cmb_contractor" class="dynamicboxlg inp disable" required>
																				<option value=""> -- Select --</option>
																				<?php echo $objBind->BindCont(0); ?>
																			</select>
																		</div>
																	</div>
																	<div class="div9 pd-lr-1">
																		<div class="lboxlabel">PAN No.</div>
																		<div>
																			<input type="text" name="txt_pan_no" id="txt_pan_no" readonly="" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">IT (%)</div>
																		<div>
																			<input type="text" name="txt_it_perc" id="txt_it_perc" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">LDC Certifi.</div>
																		<div>
																			<input type="text" name="txt_ldc_cert_no" id="txt_ldc_cert_no" readonly="" class="dynamicboxlg inp disable" />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">LDC Amt.</div>
																		<div>
																			<input type="text" name="txt_ldc_amt" id="txt_ldc_amt" readonly="" class="dynamicboxlg inp disable" />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">Valid To</div>
																		<div>
																			<input type="text" name="txt_ldc_valid_to" id="txt_ldc_valid_to" readonly="" class="dynamicboxlg inp disable" />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel"><span class="Ldc LdcStatus">LDC Status</span>&nbsp;</div>
																		<div class="lboxlabel Ldc LdcStatus">
																			<input type="text" name="txt_ldc_status" id="txt_ldc_status" class="dynamicboxlg inp disable" />
																		</div>
																		<div id="LdcValid" class="lboxlabel Ldc hide" align="left">
																			<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i> <span style="color:046929; top:-4px; position:relative;">Valid</span>
																		</div>
																		<div id="LdcInValid" class="lboxlabel Ldc hide" align="left">
																			<i class="fa fa-times-circle" style="font-size:20px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">Expired</span>
																		</div>
																	</div>
																	<div class="div6 pd-lr-1">
																		<div class="lboxlabel">GST No.</div>
																		<div>
																			<input type="text" name="txt_gst_no" id="txt_gst_no" readonly="" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">GST Rate</div>
																		<div>
																			<input type="text" name="txt_gst_rate" id="txt_gst_rate" class="dynamicboxlg BillGst inp disable" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">Inc./ Exc.</div>
																		<div>
																			<select name="cmb_gst_inc_exc" id="cmb_gst_inc_exc" class="dynamicboxlg inp BillGst disable" required>
																				<option value=""> --  Select --</option>
																				<option value="I">Inclusive</option>
																				<option value="E">Exclusive</option>
																			</select>
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Bank Account No. <font class="efont ptr BankData">(<i class="fa fa-edit" style="font-size:16px; top:2px; position:relative"></i> Click here to change)</font></div>
																		<div>
																			<input type="text" name="txt_bank_acc" id="txt_bank_acc" readonly="" class="dynamicboxlg inp disable" required />
																			<input type="hidden" name="txt_bank_id" id="txt_bank_id" class="dynamicboxlg inp disable" />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Bank Name</div>
																		<div>
																			<input type="text" name="txt_bank_name" id="txt_bank_name" readonly="" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Branch Name</div>
																		<div>
																			<input type="text" name="txt_branch" id="txt_branch" readonly="" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">IFSC Code</div>
																		<div>
																			<input type="text" name="txt_ifsc" id="txt_ifsc" readonly="" class="dynamicboxlg inp disable" required />
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
								</div>
								<div class="div3">
									<div class="card cabox" style="margin-top:0px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Bill Details</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Upto Date Value &#8377;</div>
																		<div>
																			<input type="text" name="txt_upto_dt_amt" id="txt_upto_dt_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Deduct Previous Value &#8377;</div>
																		<div>
																			<input type="text" name="txt_ded_prev_amt" id="txt_ded_prev_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">This Bill Value &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_value" id="txt_bill_value" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Add/Deduct Secured Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_sec_adv_amt" id="txt_sec_adv_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Mobilization Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_mob_adv_amt" id="txt_mob_adv_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Escalation &#8377;</div>
																		<div>
																			<input type="text" name="txt_esc_amt" id="txt_esc_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">P&M Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_pm_adv_amt" id="txt_pm_adv_amt" class="dynamicboxlg BillDtAmt inp disable" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Net Total (Pass Order Amount) &#8377;</div>
																		<div>
																			<input type="text" name="txt_net_amt" id="txt_net_amt" class="dynamicboxlg BillDtAmt inp disable" required />
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
								</div>
								<div class="div5">
									<div class="card cabox" style="margin-top:0px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Recovery Details</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="pt-2">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Bill Amount for GST &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="dynamicboxlg BillGst inp disable" required />
																		</div>
																	</div>
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Bill Amount for IT &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_amt_it" id="txt_bill_amt_it" class="dynamicboxlg inp disable" required />
																		</div>
																	</div>
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Upto Dt. SD Rec. &#8377; <span class="efont ptr SDData">[<i class="fa fa-folder-open-o" style="font-size:13px; top:1px; position:relative"></i>]</span></div>
																		<div>
																			<input type="text" name="txt_upto_dt_sd" id="txt_upto_dt_sd" class="dynamicboxlg inp disable" readonly="" required />
																		</div>
																	</div>
																	<div class="row smclearrow"></div>
																	<table class="dynamicTable" align="center" width="100%" id="RecTable">
																		<tr style="background-color:#FFF">
																			<th align="center" class="lboxlabel" nowrap="nowrap">Recovery. Description</th>
																			<th align="center" class="cboxlabel" nowrap="nowrap">( % )</th>
																			<th align="center" class="lboxlabel" nowrap="nowrap">Rec. Amount &#8377;</th>
																			<th align="center" class="lboxlabel" nowrap="nowrap">Head of Account</th>
																			<th align="center">&nbsp;</th>
																	   </tr>
																	   <tr style="background-color:#FFF">
																			<td align="center">
																				<select name="cmb_rec_desc_0" id="cmb_rec_desc_0" class="dynamicboxlg inp disable">
																					<option value="">---- Select ----</option>
																					<optgroup label="Part A:">
																					<?php if(count($GlobPartARecArr)>0){
																						foreach($GlobPartARecArr as $PARecKey => $PARecValue){
																							echo '<option value="'.$PARecKey.'" data-type="A">'.$PARecValue.'</option>';
																						}
																					}
																					?>
																					</optgroup>
																					<optgroup label="Part B:">
																					<?php if(count($GlobPartBRecArr)>0){
																						foreach($GlobPartBRecArr as $PBRecKey => $PBRecValue){
																							echo '<option value="'.$PBRecKey.'" data-type="B">'.$PBRecValue.'</option>';
																						}
																					}
																					?>
																					</optgroup>
																				</select>
																			</td>
																			<td align="center"><input type="text" name="txt_rec_perc_0" id="txt_rec_perc_0" class="dynamicboxlg inp disable" /></td>
																			<td align="center"><input type="text" name="txt_rec_amt_0" id="txt_rec_amt_0" class="dynamicboxlg inp disable" /></td>
																			<td align="center">
																				<select name="cmb_rec_hoa_scode_0" id="cmb_rec_hoa_scode_0" class="dynamicboxlg inp disable">
																					<option value="">---- Select ----</option>
																					<?php echo $objBind->BindShortCodeForMop(''); ?>
																				</select>
																			</td>
																	   		<td align="center"><i class="fa fa-plus-square sqadd ptr inp disable" id="AddRec" style="font-size:24px"></i></td>
																	   	</tr>
																	   	<tr style="background-color:#FFF">
																	   		<td colspan="2" align="right" class="rboxlabel">Total Recovery Amount &#8377; &nbsp;</td>
																			<td><input type="text" name="txt_tot_rec_amt" id="txt_tot_rec_amt" class="dynamicboxlg inp disable" required /></td>
																			<td></td>
																			<td></td>
																	   	</tr>
																	</table>
																</div>
																<div class="div4 pd-lr-1">
																	<div class="lboxlabel">Payable Amount &#8377;</div>
																	<div>
																		<input type="text" name="txt_net_pay_amt" id="txt_net_pay_amt" class="dynamicboxlg inp disable" required />
																		<input type="hidden" name="txt_work_hoa" id="txt_work_hoa" class="dynamicboxlg inp disable" />
																		<input type="hidden" name="txt_work_hoaid" id="txt_work_hoaid" class="dynamicboxlg inp disable" />
																		<input type="hidden" name="txt_bill_mode" class="inp disable" id="txt_bill_mode">
																	</div>
																</div>
																<div class="div3 pd-lr-1">
																	<div class="lboxlabel">MOP Date</div>
																	<div>
																		<input type="text" name="txt_mopdate" id="txt_mopdate" class="dynamicboxlg inp datepicker" value="<?php echo date("d/m/Y"); ?>" required />
																	</div>
																</div>
																<!--<div class="div1 pd-lr-1">
																	<div class="cboxlabel">&nbsp;</div>
																	<div>
																		<input type="checkbox" name="ch_is_advance" id="ch_is_advance" class="dynamicboxlg inp disable" required />
																	</div>
																</div>-->
																<!--<div class="div3 pd-lr-1">
																	<div class="cboxlabel"><input type="checkbox" name="ch_is_advance" id="ch_is_advance" class="inp disable" /> Adv. Pay ? </div>
																	<div class="div8">
																		<input type="text" name="txt_adv_perc" id="txt_adv_perc" class="dynamicboxlg disable" />
																	</div>
																	<div class="div4 lboxlabel">(%)</div>
																</div>
																<div class="div5 pd-lr-1">
																	<div class="lboxlabel">&nbsp;Advance Payment &#8377;</div>
																	<div>
																		<input type="text" name="txt_net_pay_adv_amt" id="txt_net_pay_adv_amt" class="dynamicboxlg disable" />
																	</div>
																</div>-->
																<div class="row" align="center">
																	<div class="div12 pd-lr-1" align="center">
																		<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="btn btn-info">
																		<input type="reset" name="btnReset" id="btnReset" value=" Reset " class="btn btn-info">
																		<input type="reset" name="btnBack" id="btnBack" value=" Back " class="btn btn-info hide">
																		<input type="hidden" name="txt_abstract_ccno" id="txt_abstract_ccno" value="<?php echo $AbstractMopCCno; ?>">
																		<input type="hidden" name="txt_abstract_linkid" id="txt_abstract_linkid" value="<?php echo $AbstractMopLinkId; ?>">
																		<input type="hidden" name="txt_abstract_sheetid" id="txt_abstract_sheetid" value="<?php echo $AbstractMopSheetId; ?>">
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
								
							</div>
						</div>
       				</form>
      			</blockquote>
    		</div>
   		</div>
	</div>
	<link rel="stylesheet" href="css/timeline.css">
<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>

var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$(function() {
	var RowIndex = 1;
	$(window).load(function() {
		var AbstCcno = $("#txt_abstract_ccno").val();
		if(AbstCcno != ''){
			$("#txt_ccno").val(AbstCcno);
			$("#btnGo").trigger( "click" );
			$("#btnBack").removeClass("hide");
		}
	});
	$("body").on("click","#btnBack", function(event){
		var AbstCcno = $("#txt_abstract_ccno").val();
		var AbstSheetId = $("#txt_abstract_sheetid").val();
		var AbstLinkId = $("#txt_abstract_linkid").val();
		if((AbstCcno != '')&&(AbstSheetId != '')&&(AbstLinkId != '')){
			url = "AbstMBook_Print_Common_Accounts.php?workno="+AbstSheetId+"&linkid="+AbstLinkId+"&view=";
			window.location.replace(url);
		}
	});
	$("body").on("click",".BankData", function(event){
		var ContId = $("#cmb_contractor").val();
		var BankId = $("#txt_bank_id").val();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindBankData.php', 
			data: { ContId: ContId }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
					TableStr += '<thead><tr><th class="lboxlabel"></th><th class="lboxlabel">SNo.</th><th class="lboxlabel">Account No.</th><th class="lboxlabel">Bank Name</th><th class="lboxlabel">Branch</th><th class="lboxlabel">IFSC Code</th></tr></thead>';
					TableStr += '<tbody>';
					var Sno = 1;
					$.each(data, function(index, element) {
						if(BankId == element.cbdtid){
							var RowCls = "active-tr";
							var CheckedStr = "checked = 'checked'";
						}else{
							var RowCls = "";
							var CheckedStr = "";
						}
						TableStr += '<tr class="BankRow '+RowCls+'"><td class="cboxlabel"><input type="radio" class="ChBank" '+CheckedStr+' name="ch_bank_name" id="'+element.cbdtid+'" value="'+element.cbdtid+'"/></td><td class="lboxlabel">'+Sno+'</td><td class="lboxlabel">'+element.bank_acc_no+'<input type="hidden" id="modal_bank_acc'+element.cbdtid+'" value="'+element.bank_acc_no+'"/></td><td class="lboxlabel">'+element.bank_name+'<input type="hidden" id="modal_bank_name'+element.cbdtid+'" value="'+element.bank_name+'"/></td><td class="lboxlabel">'+element.branch_address+'<input type="hidden" id="modal_br_add'+element.cbdtid+'" value="'+element.branch_address+'"/></td><td class="lboxlabel">'+element.ifsc_code+'<input type="hidden" id="modal_ifsc'+element.cbdtid+'" value="'+element.ifsc_code+'"/></td></tr>';
						Sno++;
					});
					TableStr += '</tbody>';
					TableStr += '</table>';
					BootstrapDialog.show({
						title: 'Bank Information',
						message: TableStr,
						onshown: function(dialogRef){
							/*var fruit = dialogRef.getModalBody().find('input').val();
							if($.trim(fruit.toLowerCase()) !== 'banana') {
								alert('Need banana!');
								return false;
							}*/
						},
						buttons: [{
							label: 'Change Bank',
							cssClass: 'btn btn-info',
							action: function(dialog) {
								var ChBank 		= $("input[name='ch_bank_name']:checked").val(); console.log(ChBank);
								var BankAcc  	= $("#modal_bank_acc"+ChBank).val(); console.log(BankAcc);
								var BankName 	= $("#modal_bank_name"+ChBank).val(); console.log(BankName);
								var BankBrAddr 	= $("#modal_br_add"+ChBank).val(); console.log(BankBrAddr);
								var BankIfsc 	= $("#modal_ifsc"+ChBank).val(); console.log(BankIfsc);
								$("#txt_bank_acc").val(BankAcc);
								$("#txt_bank_id").val(ChBank);
								$("#txt_bank_name").val(BankName);
								$("#txt_branch").val(BankBrAddr);
								$("#txt_ifsc").val(BankIfsc);
								dialog.close();
							}
						},{
							label: 'Close',
							cssClass: 'btn btn-info',
							action: function(dialog) {
								dialog.close();
							}
						}]
					});
				}
			}
		});
	});
	
	
	$("body").on("change",".BillDtAmt", function(event){
		var UptoDtAmt 	= $("#txt_upto_dt_amt").val();
		var DedPrevAmt 	= $("#txt_ded_prev_amt").val();
		var ThisBillAmt = $("#txt_bill_value").val();
		var SecAdvAmt 	= $("#txt_sec_adv_amt").val();
		var MobAdvAmt 	= $("#txt_mob_adv_amt").val();
		var EscAmt 		= $("#txt_esc_amt").val();
		var PmAdvAmt 	= $("#txt_pm_adv_amt").val();
		var ThisBillAmt = Number(UptoDtAmt)-Number(DedPrevAmt);
			ThisBillAmt = Number(ThisBillAmt).toFixed(2);
		//$("#txt_bill_value").val(ThisBillAmt);
		var NetAmt = Number(ThisBillAmt)+Number(SecAdvAmt)+Number(MobAdvAmt)+Number(EscAmt)+Number(PmAdvAmt);
			NetAmt		= Number(NetAmt).toFixed(2);
		$("#txt_net_amt").val(NetAmt);
		CalcITRecAmount();
		CalcGSTRecAmount();
		CalcLCESSRecAmount();
		CalcSDRecAmount();
	});
	
	$("body").on("click",".SDData", function(event){
		var SheetId = $("#txt_sheetid").val();
		if(SheetId != ''){
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/FindUptoDtRecData.php', 
				data: { SheetId: SheetId, RecType: 'SD' }, 
				dataType: 'json',
				success: function (data) {   //alert(data['computer_code_no']);
					if(data != null){
						var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
						TableStr += '<thead><tr><th class="cboxlabel">SNo.</th><th class="cboxlabel">Date</th><th class="cboxlabel">RAB</th><th class="cboxlabel">SD % Recovered</th><th class="rboxlabel">SD Amount Recovered</th></tr></thead>';
						TableStr += '<tbody>';
						var Sno = 1; var TotalSDAmt = 0;
						$.each(data, function(index, element) {
							var RowCls = "";
							TableStr += '<tr class="BankRow '+RowCls+'"><td class="cboxlabel">'+Sno+'</td><td class="cboxlabel">'+element.mop_date+'</td><td class="cboxlabel">'+element.rbn+'</td><td class="cboxlabel">'+element.sd_percent+'</td><td class="rboxlabel">'+element.sd_amt+'</td></tr>';
							Sno++;
							TotalSDAmt = Number(TotalSDAmt) + Number(element.sd_amt);
						});
						if(TotalSDAmt != 0){
							TotalSDAmt = TotalSDAmt.toFixed();
						}
						TableStr += '<tr class="BankRow"><td class="rboxlabel" colspan="4">Upto date SD amount recovered</td><td class="rboxlabel">'+TotalSDAmt+'</td></tr>';
						TableStr += '</tbody>';
						TableStr += '</table>';
						BootstrapDialog.show({
							title: 'SD Recovered in Previous Bill Information',
							message: TableStr,
							onshown: function(dialogRef){
							},
							buttons: [{
								label: 'Close',
								cssClass: 'btn btn-info',
								action: function(dialog) {
									dialog.close();
								}
							}]
						});
					}
				}
			});
		}else{
			BootstrapDialog.alert("Please enter valid CCNo. and then click here to view SD Details");
		}
	});
	$("body").on("click",".SDBGData", function(event){
		var SheetId = $("#txt_sheetid").val();
		if(SheetId != ''){
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/FindWorkSDData.php', 
				data: { SheetId: SheetId, RecType: 'SD' }, 
				dataType: 'json',
				success: function (data) {   //alert(data['computer_code_no']);
					if(data != null){
						var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
						TableStr += '<thead><tr><th class="cboxlabel">SNo.</th><th class="lboxlabel">Instrument Type</th><th class="rboxlabel">Amount</th><th class="cboxlabel">Serial No.</th><th class="cboxlabel">Bank Name</th><th class="cboxlabel">Branch Name</th><th class="cboxlabel">Instrument Date</th><th class="cboxlabel">Expiry Date</th></tr></thead>';
						TableStr += '<tbody>';
						var Sno = 1;
						$.each(data, function(index, element) {
							var RowCls = "";
							var CheckedStr = "";
							TableStr += '<tr class="BankRow '+RowCls+'"><td class="lboxlabel">'+Sno+'</td><td class="lboxlabel">'+element.inst_type+'</td><td class="rboxlabel">'+element.inst_amt+'</td><td class="cboxlabel">'+element.inst_serial_no+'</td><td class="lboxlabel">'+element.inst_bank_name+'</td><td class="cboxlabel">'+element.inst_branch_name+'</td><td class="cboxlabel">'+element.inst_date+'</td><td class="cboxlabel">'+element.inst_exp_date+'</td></tr>';
							Sno++;
						});
						TableStr += '</tbody>';
						TableStr += '</table>';
						BootstrapDialog.show({
							title: 'SD Bank Guarantee Information',
							message: TableStr,
							onshown: function(dialogRef){
							},
							buttons: [{
								label: 'Change Bank',
								cssClass: 'btn btn-info',
								action: function(dialog) {
									dialog.close();
								}
							},{
								label: 'Close',
								cssClass: 'btn btn-info',
								action: function(dialog) {
									dialog.close();
								}
							}]
						});
					}
				}
			});
		}else{
			BootstrapDialog.alert("Please enter valid CCNo. and then click here to view SD Details");
		}
	});
	
	$("body").on("click",".ChBank", function(event){
		if($(this).is(':checked')){
			$(".BankRow").removeClass("active-tr");
			$(this).closest('tr').addClass("active-tr");
		}
	});
	function CalcRecAmt(RecCode){
		var NetAmt = $("#txt_net_amt").val();
		var RecDescType = $("#cmb_rec_desc_0 option:selected").attr("data-type"); 
		if(RecCode == "LCESS"){
			NetAmt = $("#txt_bill_value").val();
			var RecPerc = $("#txt_rec_perc_0").val(); 
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "IT"){
			var NetAmtForIt = $("#txt_bill_amt_it").val();
			var RecPerc = $("#txt_it_perc").val();
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForIt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();//alert(RecAmt);
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "IGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = iGst;
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = Number(RecAmt).toFixed(2);
				var ExpRecAmt = RecAmt.split(".");
				if(Number(ExpRecAmt[1]) > 0){
					RecAmt = Number(ExpRecAmt[0])+1;
				}
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "CGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = cGst;
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100; 
				RecAmt = Number(RecAmt).toFixed(2); 
				var ExpRecAmt = RecAmt.split("."); 
				if(Number(ExpRecAmt[1]) > 0){
					var RecAmt = Number(ExpRecAmt[0])+1;
				} 
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = sGst;
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = Number(RecAmt).toFixed(2);
				var ExpRecAmt = RecAmt.split(".");
				if(Number(ExpRecAmt[1]) > 0){
					RecAmt = Number(ExpRecAmt[0])+1;
				}
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SD"){
			if(IsBGExistForSD == 1){
				BootstrapDialog.alert("SD - Bank Guarantee / FDR available for this work");
				$("#cmb_rec_desc_0").chosen('destroy');
				$("#cmb_rec_desc_0").val('');
				$("#cmb_rec_desc_0").chosen();
				$("#txt_rec_perc_0").val('');
				$("#txt_rec_amt_0").val('');
			}else{
				NetAmt = $("#txt_bill_value").val();
				var RecPerc = Number(SDRecPerc).toFixed(2);//$("#txt_rec_perc_0").val();
					$("#txt_rec_perc_0").val(RecPerc);
				var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
					RecAmt = RecAmt.toFixed();
				if(FinalSDRecFlag == 1){
					BootstrapDialog.alert("SD "+RecPerc+" % of Bill Value is &#8377; "+RecAmt+". But balance SD amount to be recovered in this bill is &#8377; "+FinalSDRecAmt);
					$("#txt_rec_amt_0").val(FinalSDRecAmt.toFixed());
				}else{
					$("#txt_rec_amt_0").val(RecAmt);
				}
			}
		}
		if(RecDescType == "B"){
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val(RecCode);
			$("#cmb_rec_hoa_scode_0").chosen();
		}else{
			ShowWorkHoaShortCode();
		}
	}
	$("body").on("change","#cmb_rec_desc_0", function(event){
		var RecCode = $(this).val();
		var RecDesc = $("#cmb_rec_desc_0 option:selected").text();
		if($("#"+RecCode).length){
			BootstrapDialog.alert("Duplicate Error : "+RecDesc+" recovery already added ");
			$("#cmb_rec_desc_0").chosen('destroy');
			$("#cmb_rec_desc_0").val('');
			$("#cmb_rec_desc_0").chosen();
			event.preventDefault();
			event.returnValue = false;
		}else{
			$("#txt_rec_perc_0").val('');
			$("#txt_rec_amt_0").val('');
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val('');
			$("#cmb_rec_hoa_scode_0").chosen();
			CalcRecAmt(RecCode);
		}
	});
	$("body").on("change","#txt_rec_perc_0", function(event){
		var RecCode = $("#cmb_rec_desc_0").val();
		CalcRecAmt(RecCode);
	});
	function CalcITRecAmount(){
		if($("#IT").length){
			var RowIndex = $("#IT").attr("data-id");
			var NetAmtForIt = $("#txt_bill_amt_it").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(NetAmtForIt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
		}
	}
	function CalcSDRecAmount(){
		if($("#SD").length){
			var RowIndex = $("#SD").attr("data-id");
			var NetAmt = $("#txt_bill_value").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
		}
	}
	function CalcLCESSRecAmount(){
		if($("#LCESS").length){
			var RowIndex = $("#LCESS").attr("data-id");
			var NetAmt = $("#txt_bill_value").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
		}
	}
	
	$("body").on("change","#txt_bill_amt_it", function(event){
		CalcITRecAmount();
	});
	function CalcGSTRecAmount(){
		var GstRate 	= $("#txt_gst_rate").val();
		var GstType 	= $("#cmb_gst_inc_exc").val();
		var NetAmt  	= $("#txt_net_amt").val();
		//var BillAmtGst  = $("#txt_bill_amt_gst").val();
		if((GstRate != '')&&(GstType != '')&&(NetAmt != '')){
			if(GstType == "E"){
				$("#txt_bill_amt_gst").val(NetAmt);
			}else{
				var BillAmtForGst 	= Number(NetAmt) * 100 / (Number(GstRate)+100);//((NetAmt*100/($WorkArr['gst_perc_rate']+100)),2);
				BillAmtForGst = BillAmtForGst.toFixed(2);
				$("#txt_bill_amt_gst").val(BillAmtForGst);
			}
		}
		if($("#CGST").length){
			var RowIndex = $("#CGST").attr("data-id");
			var BillAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(BillAmtForGst) * Number(RecPerc) / 100; 
				RecAmt = Number(RecAmt).toFixed(2); 
				var ExpRecAmt = RecAmt.split("."); 
				if(Number(ExpRecAmt[1]) > 0){
					RecAmt = Number(ExpRecAmt[0])+1;
				} 
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
			CalcTotalRec();
		}
		if($("#SGST").length){
			var RowIndex = $("#SGST").attr("data-id");
			var BillAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(BillAmtForGst) * Number(RecPerc) / 100;
				RecAmt = Number(RecAmt).toFixed(2); 
				var ExpRecAmt = RecAmt.split("."); 
				if(Number(ExpRecAmt[1]) > 0){
					RecAmt = Number(ExpRecAmt[0])+1;
				} 
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
			CalcTotalRec();
		}
		if($("#IGST").length){
			var RowIndex = $("#IGST").attr("data-id");
			var BillAmtForGst = $("#txt_bill_amt_gst").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(BillAmtForGst) * Number(RecPerc) / 100;
				RecAmt = Number(RecAmt).toFixed(2); 
				var ExpRecAmt = RecAmt.split("."); 
				if(Number(ExpRecAmt[1]) > 0){
					RecAmt = Number(ExpRecAmt[0])+1;
				} 
				RecAmt = Math.floor(RecAmt);//RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
			CalcTotalRec();
		}
	}
	$("body").on("change",".BillGst", function(event){
		CalcGSTRecAmount();
	});
	$("body").on("click","#ch_is_advance", function(event){
		if($(this).is(':checked')){
			$("#txt_adv_perc").removeClass("disable");
			$("#txt_net_pay_adv_amt").removeClass("disable");
		}else{
			$("#txt_adv_perc").val('');
			$("#txt_adv_perc").addClass("disable");
			$("#txt_net_pay_adv_amt").addClass("disable");
			var NetPay = $("#txt_net_pay_amt").val();
			$("#txt_net_pay_adv_amt").val('');
		}
	});
	$("body").on("change","#txt_adv_perc", function(event){
		var AdvPerc = $(this).val();
		if((Number(AdvPerc) > 1)&&(Number(AdvPerc) < 100)){
			var NetPay = $("#txt_net_pay_amt").val();
			var AdvanceAmt = Number(NetPay)*Number(AdvPerc)/100;
				AdvanceAmt = AdvanceAmt.toFixed();
			$("#txt_net_pay_adv_amt").val(AdvanceAmt);
		}else{
			BootstrapDialog.alert("Invalid Advance payment %. It should be 1 to 100");
			$(this).val('');
		}
	});
	
	
	
	$("body").on("click","#AddRec", function(event){
		var RecDesc 		= $("#cmb_rec_desc_0 option:selected").text();
		var RecId 			= $("#cmb_rec_desc_0").val();
		var RecIntPerc 		= $("#txt_rec_perc_0").val();
		var RecAmt 			= $("#txt_rec_amt_0").val();
		var RecHoaShCode 	= $("#cmb_rec_hoa_scode_0 option:selected").text();
		var RecHoaRecCode 	= $("#cmb_rec_hoa_scode_0").val();
		var RecHoaScodeId	= $("#cmb_rec_hoa_scode_0 option:selected").attr("data-id");
		var RecType			= $("#cmb_rec_desc_0 option:selected").attr("data-type");
		if(RecId == ""){
			BootstrapDialog.alert("Please Select Recovery Description");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecAmt == ""){
			BootstrapDialog.alert("Please Enter Valid Amount");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecAmt == 0){
			BootstrapDialog.alert("Please Enter Valid Amount");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecHoaRecCode == ""){
			BootstrapDialog.alert("Please Select HOA Short Code ");
			event.preventDefault();
			event.returnValue = false;
		}else if($("#"+RecId).length){
			BootstrapDialog.alert("Duplicate Error : "+RecDesc+" recovery already added ");
			event.preventDefault();
			event.returnValue = false;
		}else{
			var RowStr = '<tr class="recRow" data-id="'+RowIndex+'" id="'+RecId+'" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecDesc+'" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecIntPerc+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecAmt+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
			$("#RecTable").find('tr:last').prev().after(RowStr);
			$("#txt_rec_perc_0").val('');
			$("#txt_rec_amt_0").val('');
			$("#cmb_rec_desc_0").chosen('destroy');
			$("#cmb_rec_desc_0").val('');
			$("#cmb_rec_desc_0").chosen();
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val('');
			$("#cmb_rec_hoa_scode_0 option").attr('disabled', false);
			$("#cmb_rec_hoa_scode_0").chosen();
			CalcTotalRec();
			RowIndex++;
		}
	});
	$("body").on("click",".DelRec", function(event){
		$(this).closest('tr').remove();
		CalcTotalRec();
	});
	function CalcTotalRec(){
		var TotalRecAmt = 0;
		var NetTotal = $("#txt_net_amt").val();
		$('input[name="txt_rec_amt[]"]').each(function(){ 
			var RecAmt = $(this).val();
			if(RecAmt != ''){
				TotalRecAmt = Number(TotalRecAmt) + Number(RecAmt);
			}
		});
		$("#txt_tot_rec_amt").val(TotalRecAmt.toFixed());
		var PayableAmt = Number(NetTotal) - Number(TotalRecAmt);
		$("#txt_net_pay_amt").val(PayableAmt.toFixed());
	}
	
	function ShowWorkHoaShortCode(){
		var HoaNoSplit 		= WorkHoaNo.split(',');
		var HoaIdSplit 		= WorkHoaId.split(',');
		var HoaScodeIdSplit = WorkHoaScodeId.split(',');
		var HoaScodeSplit 	= WorkHoaScode.split(',');
		$("#cmb_rec_hoa_scode_0").chosen("destroy"); 
		//$("#cmb_rec_hoa_scode_0 option").attr('disabled', true);
		if(HoaNoSplit.length > 1){ 
			// More than One Code
			for(var i=0; i<HoaNoSplit.length; i++){
				var HoaScode = HoaScodeSplit[i];  
				var HoaScodeId = HoaScodeIdSplit[i];  
				//$("#cmb_rec_hoa_scode_0 option:contains('"+HoaScode+"')").attr('disabled', false);
			}
		}else{  
			// Only One Code
			//$("#cmb_rec_hoa_scode_0 option:contains('"+HoaScode+"')").attr('disabled', false);
			$("#cmb_rec_hoa_scode_0 option:contains('"+HoaScode+"')").attr("selected","selected");//attr('disabled', false);
		}  
		$("#cmb_rec_hoa_scode_0").chosen();
	}
	
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			$("#btnGo").trigger( "click" );
		}
	});
	
	var cGst = 0; var sGst = 0; var iGst = 0; var IsBGExistForSD = 0; var SDRecPerc = 0;  var FinalSDRecAmt = 0; var FinalSDRecFlag = 0;
	var WorkHoaNo = ''; var HoaId = ''; var HoaScodeId = ''; var HoaScode = '';
	$("body").on("click","#btnGo", function(event){
		cGst = 0; sGst = 0; iGst = 0; SDRecPerc = 0; RowIndex = 1; FinalSDRecAmt = 0; FinalSDRecFlag = 0; IsBGExistForSD = 0;
		WorkHoaNo = ''; WorkHoaId = ''; WorkHoaScodeId = ''; WorkHoaScode = '';
		$("#cmb_contractor").chosen('destroy');
		$("#cmb_rec_desc_0").chosen('destroy');
		$("#cmb_rec_hoa_scode_0").chosen('destroy');
		$(".inp").removeClass("disable");
		$(".inp").val('');
		$("#cmb_contractor option").attr('disabled', false);
		$(".Ldc").addClass('hide');
		$(".LdcStatus").removeClass('hide');
		$("#cmb_rec_desc_0").chosen();
		$("#cmb_rec_hoa_scode_0").chosen();
		$("#cmb_contractor").chosen();
		$(".recRow").closest('tr').remove();
		$("#btnSave").show();
		$("#btnReset").show();
		var Ccno = $("#txt_ccno").val();
		var AbstCcno = $("#txt_abstract_ccno").val(); 
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindVoucherData.php', 
			data: { Ccno: Ccno, PageCode: 'ACC', PayType:'FPAY' }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var WData 	 	= data['WData'];
					var RABData  	= data['RABData'];
					var RECData  	= data['RECData'];
					var CONTData 	= data['CONTData'];
					var BKData   	= data['BKData'];
					var RecHoaData  = data['RecHoaData'];
					var StatusData  = data['StatusData'];
					var BillErr = 0; var IsBillReg = 0; var BillMode = "";
					if(StatusData != null){
						if(StatusData['bill_reg_status'] == "N"){
							BootstrapDialog.alert("Bill not yet registered / Invalid attempt.");
							BillErr = 1;
						}else if(StatusData['bill_reg_status'] == "Y"){
							IsBillReg = 1;
						}
						BillMode = StatusData['bill_mode'];
					}
					$("#txt_bill_mode").val(BillMode);
					if((StatusData != null)&&(BillErr == 0)){
						if(StatusData['bill_comp_status'] == "C"){
							BootstrapDialog.alert("Bill process is already completed. You can't edit further.");
							BillErr = 1;
						}else if(StatusData['bill_vouch_status'] == "Y"){
							BootstrapDialog.alert("Voucher process is already completed. You can't edit further.");
							BillErr = 1;
						}else if(StatusData['bill_payord_status'] == "Y"){
							BootstrapDialog.alert("Pay Order process is already completed. You can't edit further.");
							BillErr = 1;
						}else if(StatusData['bill_pasord_status'] == "Y"){
							BootstrapDialog.alert("Pass Order process is already completed. You can't edit further.");
							BillErr = 1;
						}else if((StatusData['bill_ret_status'] == "Y")&&(BillMode == "ON")){
							BootstrapDialog.alert("Bill is returned back to EIC. You can't edit further.");
							BillErr = 1;
						}else if((StatusData['bill_level_flag'] == "H")&&(BillMode == "ON")){
							BootstrapDialog.alert("Bill is forwarded to next checking level. You can't edit further.");
							BillErr = 1;
						}else if((StatusData['bill_level_flag'] == "L")&&(BillMode == "ON")){
							BootstrapDialog.alert("Bill is still waiting in previous checking level. You can't edit further.");
							BillErr = 1;
						}else if((AbstCcno == '')&&(StatusData['bill_curr_level'] == StatusData['bill_level_flag'])&&(StatusData['bill_curr_level'] != "C")&&(BillMode == "ON")){
							BootstrapDialog.alert("Bill verification not yet completed. You can't edit further.");
							BillErr = 1;
						}
					}
					
					if(BillErr == 1){
						$("#btnSave").hide();
						$("#btnReset").hide();
					}
					if(IsBillReg == 1){
						//var TableStr = '<table class="table table-bordered rectable">';
						if(WData != null){
							$("#txt_work_name").val(WData['work_name']);
							$("#txt_sheetid").val(WData['sheet_id']);
							$("#txt_globid").val(WData['globid']);
							$("#txt_bank_id").val(WData['cbdtid']);
							$("#cmb_gst_inc_exc").val(WData['gst_inc_exc']);
							$("#txt_gst_rate").val(WData['gst_perc_rate']);
							$("#cmb_rec_desc_0").chosen("destroy");
							$("#txt_sr_no").val(WData['br_no']);
							$("#txt_work_hoa").val(WData['hoa']);
							$("#txt_work_hoaid").val(WData['hoaid']);
							WorkHoaNo 		= WData['hoa_no'];
							WorkHoaId 		= WData['hoaid'];
							WorkHoaScodeId 	= WData['hoascodeid'];
							WorkHoaScode 	= WData['hoascode'];
							if(WData['is_gst_appl'] != 'Y'){
								$("#txt_gst_rate").val("0.00");
								$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', true);
								$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', true); 
								$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', true); 
								if(WData['CGST'] != null){ cGst = WData['CGST']; }
								if(WData['SGST'] != null){ sGst = WData['SGST']; }
								if(WData['IGST'] != null){ iGst = WData['IGST']; }
							}else{
								$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', false); 
								$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', false);
								$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', false);
							}
							if(WData['is_less_appl'] != 'Y'){
								$("#cmb_rec_desc_0 option[value='LCESS']").attr('disabled', true); 
							}else{
								$("#cmb_rec_desc_0 option[value='LCESS']").attr('disabled', false); 
							}
							$("#cmb_rec_desc_0").chosen();
							$("#txt_work_sd_perc").val(WData['sd_perc']);
							$("#txt_work_sd_amt").val(WData['work_sd_amt']);
							if(WData['work_sd_bg_exist'] == "Y"){
								IsBGExistForSD = 1;
								if(WData['work_sd_bg_valid'] == 0){
									BootstrapDialog.alert("Bank Guarantee / FDR for SD has expired on "+WData['work_sd_bg_valid_dt']);
								}
							}
							if(WData['sd_rec_perc'] != null){
								SDRecPerc = WData['sd_rec_perc']; //alert(SDRecPerc); Here SD Percentage is assigned to SD Global Variable
							}
						}
						if(CONTData != null){
							$("#txt_pan_no").val(CONTData['pan_no']);
							$("#txt_gst_no").val(CONTData['gst_no']);
							$("#cmb_contractor").chosen('destroy');
							$("#cmb_contractor").val(CONTData['contid']);
							$("#cmb_contractor option:not(:selected)").attr('disabled', true);
							$("#cmb_contractor").chosen();
							$("#txt_it_perc").val(CONTData['it_rate']);
							$(".Ldc").addClass("hide");
							if(CONTData['is_ldc_appl'] == "Y"){
								$("#txt_ldc_cert_no").val(CONTData['ldc_certi_no']);
								$("#txt_ldc_amt").val(CONTData['ldc_max_amt']);
								var LdcValidStr = CONTData['ldc_validity'];
								var LdcValidStrSplit = LdcValidStr.split("-");
								var LdcValid = LdcValidStrSplit[2]+"/"+LdcValidStrSplit[1]+"/"+LdcValidStrSplit[0];//moment(CONTData['ldc_validity']).format('DD/MM/YYYY');
								$("#txt_ldc_valid_to").val(LdcValid);
								
								
								var todayDt		= new Date();//new Date(today[2], today[1]-1, today[0]); //==============================( 2 )
								var todayDays 	= todayDt.getDate();
								var todayMonth 	= ("0" + (todayDt.getMonth()+1)).slice(-2);//pvcdate.getMonth();
								var todayYears 	= todayDt.getFullYear();
								var TodayDate 	= todayDays+"/"+todayMonth+"/"+todayYears;
								
								var dt1 = LdcValid.split("/");
								var dt2 = TodayDate.split("/");
								var LdcValidStr 	= new Date(dt1[2], dt1[1]-1, dt1[0]);  // -1 because months are from 0 to 11
								var TodayDateStr   	= new Date(dt2[2], dt2[1]-1, dt2[0]);
								if(LdcValidStr > TodayDateStr){
									if(CONTData['ldc_bal_valid'] == 1){
										$("#LdcValid").removeClass("hide");
										$("#txt_ldc_status").val("V");
										$("#txt_it_perc").val(CONTData['ldc_rate']);
									}else{
										$("#LdcInValid").removeClass("hide");
										$("#txt_ldc_status").val("NV");
										$("#txt_it_perc").val(CONTData['it_rate']);
										BootstrapDialog.alert("Not sufficient LDC amount to deduct "+CONTData['ldc_rate']+" % IT rate");
									}
								}else{
									$("#LdcInValid").removeClass("hide");
									$("#txt_ldc_status").val("NV");
									$("#txt_it_perc").val(CONTData['it_rate']);
								}
							}
							if(CONTData['gst_type'] != null){
								$("#cmb_rec_desc_0").chosen("destroy");
								if(CONTData['gst_type'] == "I"){
									if(WData['CGST'] != null){ cGst = 0; }
									if(WData['SGST'] != null){ sGst = 0; }
									if(WData['IGST'] != null){ iGst = WData['IGST']; }
									$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', true);
									$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', true); 
									$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', false); 
								}else{
									if(WData['CGST'] != null){ cGst = WData['CGST']; }
									if(WData['SGST'] != null){ sGst = WData['SGST']; }
									if(WData['IGST'] != null){ iGst = 0; }
									$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', false);
									$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', false); 
									$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', true);
								}
								$("#cmb_rec_desc_0").chosen();
							}
						}
						if(BKData != null){
							$.each(BKData, function(index, element) { 
								if(element.active_status == 1){
									$("#txt_bank_acc").val(element.bank_acc_no);
									$("#txt_bank_name").val(element.bank_name);
									$("#txt_branch").val(element.branch_address);
									$("#txt_ifsc").val(element.ifsc_code);
								}
							});
						}
						if(RABData != null){
							$("#txt_rbn").val(RABData['rbn']);
							if((RABData['upto_date_total_amount'] == null)||(RABData['upto_date_total_amount'] == "")){
								RABData['upto_date_total_amount'] = 0;
							}
							if((RABData['dpm_total_amount'] == null)||(RABData['dpm_total_amount'] == "")){
								RABData['dpm_total_amount'] = 0;
							}
							if((RABData['slm_total_amount'] == null)||(RABData['slm_total_amount'] == "")){
								RABData['slm_total_amount'] = 0;
							}
							if((RABData['secured_adv_amt'] == null)||(RABData['secured_adv_amt'] == "")){
								RABData['secured_adv_amt'] = 0;
							}
							if((RABData['mob_adv_amt'] == null)||(RABData['mob_adv_amt'] == "")){
								RABData['mob_adv_amt'] = 0;
							}
							if((RABData['slm_total_amount_esc'] == null)||(RABData['slm_total_amount_esc'] == "")){
								RABData['slm_total_amount_esc'] = 0;
							}
							if((RABData['pl_mac_adv_amt'] == null)||(RABData['pl_mac_adv_amt'] == "")){
								RABData['pl_mac_adv_amt'] = 0;
							}
							if((RABData['upto_dt_sd_rec_amt'] == null)||(RABData['upto_dt_sd_rec_amt'] == "")){
								RABData['upto_dt_sd_rec_amt'] = 0;
							}
							
							$("#txt_upto_dt_amt").val(RABData['upto_date_total_amount']); 
							$("#txt_ded_prev_amt").val(RABData['dpm_total_amount']);
							$("#txt_bill_value").val(RABData['slm_total_amount']);
							$("#txt_sec_adv_amt").val(RABData['secured_adv_amt']);
							$("#txt_mob_adv_amt").val(RABData['mob_adv_amt']);
							$("#txt_esc_amt").val(RABData['slm_total_amount_esc']);
							$("#txt_pm_adv_amt").val(RABData['pl_mac_adv_amt']);
							$("#txt_upto_dt_sd").val(RABData['upto_dt_sd_rec_amt']);
							/*if(RABData['is_rab'] == "Y"){ $("#rad_rab_for_rab").prop("checked",true); }
							if(RABData['is_final_bill'] == "Y"){ $("#rad_rab_for_fbill").prop("checked",true); }
							if(RABData['is_sec_adv'] == "Y"){ $("#rad_rab_for_sa").prop("checked",true); }
							if(RABData['is_mob_adv'] == "Y"){ $("#rad_rab_for_mob").prop("checked",true); }
							if(RABData['is_esc'] == "Y"){ $("#rad_rab_for_esc").prop("checked",true); }*/
							//var NetAmount = Number(RABData['slm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc']);
							var NetAmount = Number(RABData['upto_date_total_amount']) - Number(RABData['dpm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc'])+ Number(RABData['pl_mac_adv_amt']);
							NetAmount = NetAmount.toFixed(2);
							$("#txt_net_amt").val(NetAmount);
							$("#txt_net_pay_amt").val(NetAmount);
							$("#txt_tot_rec_amt").val('0.00');
							$("#txt_bill_amt_gst").val(RABData['bill_amt_for_gst']);
							$("#txt_bill_amt_it").val(RABData['bill_amt_it']);
							if(RABData['balance_sd_full_rec'] != null){
								if(RABData['balance_sd_full_rec'] == 1){
									FinalSDRecFlag = RABData['balance_sd_full_rec'];
									FinalSDRecAmt = RABData['curr_bill_sd_rec_amt'];
								}
							}
						}
						if(RECData != null){
							var RowStr = ''; var RecHoaShCode = ''; var RecHoaRecCode = ''; var RecHoaScodeId = '';
							
							if((RECData['is_adv_pay'] == 'Y')&&(RECData['adv_amt'] != null)&&(RECData['adv_amt'] != 0)){
								var AdvPerc = RECData['adv_perc'];
								if(WData['hoascode'] != null){
									RecHoaShCode  = WData['hoascode'];
									RecHoaRecCode = "";
									RecHoaScodeId = WData['hoascodeid'];
									var RecType   = "";
								}else{
									RecHoaShCode  = "";
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="75ADV" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+AdvPerc+'% Advance" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="75ADV"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['adv_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['adv_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							
							if((RECData['lw_cess_amt'] != 0)&&(RECData['lw_cess_amt'] != null)){
								if(RecHoaData['LCESS'] != null){
									var RecHoaDt  = RecHoaData['LCESS'];
									RecHoaShCode  =  RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="LCESS" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCess" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCESS"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['lw_cess_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['lw_cess_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['mob_adv_amt_rec'] != 0)&&(RECData['mob_adv_amt_rec'] != null)){
								if(RecHoaData['MOB'] != null){
									var RecHoaDt  = RecHoaData['MOB'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="MOB" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOB"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_amt_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['pl_mac_adv_rec'] != 0)&&(RECData['pl_mac_adv_rec'] != null)){
								if(RecHoaData['PM'] != null){
									var RecHoaDt  = RecHoaData['PM'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="PM" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PM"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['hire_charges'] != 0)&&(RECData['hire_charges'] != null)){
								if(RecHoaData['HIRE'] != null){
									var RecHoaDt  = RecHoaData['HIRE'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="HIRE" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Hire Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="HC"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['hire_charges']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							var OthRecAmt = 0;
							if((RECData['other_recovery_1_amt'] != 0)&&(RECData['other_recovery_1_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_1_amt']);
							}
							if((RECData['other_recovery_2_amt'] != 0)&&(RECData['other_recovery_2_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_2_amt']);
							}
							if((RECData['other_recovery_3_amt'] != 0)&&(RECData['other_recovery_3_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_3_amt']);
							}
							
							if(OthRecAmt != 0){
								if(RecHoaData['OTH'] != null){
									var RecHoaDt  = RecHoaData['OTH'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="OTH" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Other Recoveries" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="OTH"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+OthRecAmt+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['sgst_tds_amt'] != 0)&&(RECData['sgst_tds_amt'] != null)){
								if(RecHoaData['SGST'] != null){
									var RecHoaDt  = RecHoaData['SGST'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="SGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['cgst_tds_amt'] != 0)&&(RECData['cgst_tds_amt'] != null)){
								if(RecHoaData['CGST'] != null){
									var RecHoaDt  = RecHoaData['CGST'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="CGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['cgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['cgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['igst_tds_amt'] != 0)&&(RECData['igst_tds_amt'] != null)){
								if(RecHoaData['IGST'] != null){
									var RecHoaDt  = RecHoaData['IGST'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="IGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['igst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['igst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['incometax_amt'] != 0)&&(RECData['incometax_amt'] != null)){
								if(RecHoaData['IT'] != null){
									var RecHoaDt  = RecHoaData['IT'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="IT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['incometax_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['incometax_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['sd_amt'] != 0)&&(RECData['sd_amt'] != null)){
								if(RecHoaData['SD'] != null){
									var RecHoaDt  = RecHoaData['SD'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="SD" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sd_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sd_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['water_cost'] != 0)&&(RECData['water_cost'] != null)){
								if(RecHoaData['WC'] != null){
									var RecHoaDt  = RecHoaData['WC'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="WC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Water Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="WC"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['water_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['electricity_cost'] != 0)&&(RECData['electricity_cost'] != null)){
								if(RecHoaData['EC'] != null){
									var RecHoaDt  = RecHoaData['EC'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="EC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Electricity Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="EC"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['electricity_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['mob_adv_int_amt'] != 0)&&(RECData['mob_adv_int_amt'] != null)){
								if(RecHoaData['MOBINT'] != null){
									var RecHoaDt  = RecHoaData['MOBINT'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="MOBINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOBINT"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['mob_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							if((RECData['pl_mac_adv_int_amt'] != 0)&&(RECData['pl_mac_adv_int_amt'] != null)){
								if(RecHoaData['PMINT'] != null){
									var RecHoaDt  = RecHoaData['PMINT'];
									RecHoaShCode  = RecHoaDt['shortcode'];
									RecHoaRecCode = RecHoaDt['rec_code'];
									RecHoaScodeId = RecHoaDt['shortcode_id'];
									var RecType   = RecHoaDt['rec_type'];
								}else{
									RecHoaShCode  = "";//RecHoaDt['rec_perc'];
									RecHoaRecCode = "";
									RecHoaScodeId = "";
									var RecType   = "";
								}
								RowStr += '<tr class="recRow" data-id="'+RowIndex+'" id="PMINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PMINT"><input type="hidden" name="txt_rec_type[]" id="txt_rec_type'+RowIndex+'" class="dynamicboxlg disable" value="'+RecType+'" /></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['pl_mac_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
								RowIndex++
							}
							$("#RecTable").find('tr:last').prev().after(RowStr);
							CalcTotalRec();
						}
					}
				}
			}
		});
	});
	var KillEvent = 0;
	$("body").on("click","#btnSave", function(event){
		if(KillEvent == 0){
			var WorkId  = $("#cmb_work_name").val(); 
			var ContId  = $("#cmb_contractor").val();
			var NetAmt  = $("#txt_net_amt").val();
			var RowLen  = $('#RecTable tr').length;
			if(WorkId == ""){
				BootstrapDialog.alert("Please select work name");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContId == ""){
				BootstrapDialog.alert("Contractor name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if((NetAmt == "")||(NetAmt == 0)||(NetAmt == "0")){
				BootstrapDialog.alert("Invalid net amount");
				event.preventDefault();
				event.returnValue = false;
			}else if(RowLen <= 3){
				BootstrapDialog.alert("Please add atleast one recovery");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure save Memo of Payments ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							KillEvent = 1;
							$("#btnSave").trigger( "click" );
						}else {
							//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}
		}
	});
	
});
</script>

</body>
</html>

