<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
checkUser();
$msg = '';
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
$GlobPartARecArr = array("LCESS"=>"LCess","MOB"=>"Mob.Adv. Rec.","PM"=>"P&M.Adv. Rec.","HIRE"=>"Hire Charges","OTH"=>"Other Recoveries");
$GlobPartBRecArr = array("CGST"=>"CGST","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"Water Charges","EC"=>"Electricity Charges","MOBINT"=>"Mob. Adv. Interest","PMINT"=>"P&M Adv. Interest");
if(isset($_POST['btnSave'])){
	$SaveUnit 		= $GlobUnitId;//$_POST['txt_unitid'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	
	$SaveRefNo 		= $_POST['txt_ref_no'];
	$SaveNatClaimId = $_POST['cmb_nature_claim'];
	$SaveNatClaim 	= $_POST['txt_nature_claim'];
	$SaveContId 	= $_POST['cmb_contractor'];
	$SaveContName 	= $_POST['txt_contractor'];
	$SaveVouchDate 	= dt_format($_POST['txt_vouch_dt']);
	$SaveVouchNo 	= $_POST['txt_vouch_no'];
	$SaveVouchAmt 	= round($_POST['txt_vouch_amt']);
	$SavePinNo 		= $_POST['txt_pinno'];
	$SaveHoa 		= $_POST['txt_vouch_hoa'];
	$SelectQuery1 = "select * from sheet where sheet_id = '$SaveSheetId'";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List = mysqli_fetch_object($SelectSql1);
			$GlobId 		= $List->globid;
			$WorkOrder 		= $List->work_order_no;
			$WorkName 		= $List->work_name;
			$ContName 		= $List->name_contractor;
			$ContId 		= $List->contid;
			$Ccno 			= $List->computer_code_no;
			$WorkOrderDt 	= $List->work_order_date;
			$WorkOrderCost 	= $List->work_order_cost;
			$SchCompDt 		= $List->date_of_completion;
			
		}
	}
	
	//$SavePanNo 		= $_POST['txt_pan_no'];
	//$SaveItPerc 	= $_POST['txt_it_perc'];
	//$SaveGstNo 		= $_POST['txt_gst_no'];
	//$SaveGstRate 	= $_POST['txt_gst_rate'];
	$SaveBankAccNo 	= $_POST['txt_bank_acc'];
	$SaveBankName 	= $_POST['txt_bank_name'];
	$SaveBankId 	= $_POST['txt_bank_id'];
	$SaveBranch 	= $_POST['txt_branch'];
	$SaveIfscCode 	= $_POST['txt_ifsc'];
	//$SaveLdcCertNo 	= $_POST['txt_ldc_cert_no'];
	//$SaveLDcAmt 	= $_POST['txt_ldc_amt'];
	//$SaveLdcValidTo = $_POST['txt_ldc_valid_to'];

	$SaveNetAmt 	= $_POST['txt_net_amt'];
	//$SaveBillAmtGst = $_POST['txt_bill_amt_gst'];
	//$SaveBillAmtIt 	= $_POST['txt_bill_amt_it'];
	//$SaveGstAmount  = round(($SaveBillAmtGst * $SaveGstRate / 100),2);
	
	/*$SaveRecDescArr 		= $_POST['txt_rec_desc'];
	$SaveRecDescIdArr 		= $_POST['hid_rec_desc'];
	$SaveRecPercArr 		= $_POST['txt_rec_perc'];
	$SaveRecAmtArr 			= $_POST['txt_rec_amt'];
	$SaveRecHoaScodeDescArr = $_POST['txt_rec_hoa_shcode'];
	$SaveRecHoaScodeRecArr 	= $_POST['hid_rec_hoa_rec_code'];
	$SaveRecHoaScodeIdArr 	= $_POST['hid_rec_hoa_shcode_id'];*/
	
	//$SaveTotRecAmt 		= $_POST['txt_tot_rec_amt'];
	//$SaveTotNetPayAmt 	= $_POST['txt_net_pay_amt'];
	/*$SaveIsAdvance 		= $_POST['ch_is_advance'];
	$SaveAdvPerc 		= $_POST['txt_adv_perc'];
	$SavePayableAmt 	= $_POST['txt_net_pay_adv_amt'];*/
	/*if($SaveAdvPerc == "Y"){
		$ChequeAmount = $SavePayableAmt;
	}else{
		$ChequeAmount = $SaveTotNetPayAmt;
	}*/
	
	/*$DeleteQuery 	= "DELETE FROM memo_payment_accounts_edit WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
	$DeleteSql 		= mysqli_query($dbConn,$DeleteQuery);
	$DeleteQuery 	= "DELETE FROM mop_rec_dt WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
	$DeleteSql 		= mysqli_query($dbConn,$DeleteQuery);*/

	//$SavePoDate  = dt_format($_POST['txt_podate']);
	$ResStr = ""; $QueryArr = array();
	/*if(count($SaveRecDescArr)>0){
		foreach($SaveRecDescArr as $Key => $Value){
			$SaveRecDesc   			= $SaveRecDescArr[$Key];
			$SaveRecDescId 			= $SaveRecDescIdArr[$Key];
			$SaveRecPerc 			= $SaveRecPercArr[$Key];
			$SaveRecAmt 			= $SaveRecAmtArr[$Key];
			$SaveRecHoaScodeDesc 	= $SaveRecHoaScodeDescArr[$Key];
			$SaveRecHoaScodeRec 	= $SaveRecHoaScodeRecArr[$Key];
			$SaveRecHoaScodeId 		= $SaveRecHoaScodeIdArr[$Key];
			if($SaveRecDescId == "LCESS"){ $ResStr .= ", lw_cess_percent = '".$SaveRecPerc."', lw_cess_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "MOB"){ $ResStr .= ", mob_adv_amt_rec = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "PM"){ $ResStr .= ", pl_mac_adv_rec = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "HIRE"){ $ResStr .= ", hire_charges = '".$SaveRecAmt."'"; }
			//if($SaveRecDescId == "OTH"){ $ResStr .= ", lw_cess_percent = '".$SaveRecPerc."', lw_cess_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "CGST"){ $ResStr .= ", cgst_tds_perc = '".$SaveRecPerc."', cgst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "SGST"){ $ResStr .= ", sgst_tds_perc = '".$SaveRecPerc."', sgst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "IGST"){ $ResStr .= ", igst_tds_perc = '".$SaveRecPerc."', igst_tds_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "IT"){ $ResStr .= ", incometax_percent = '".$SaveRecPerc."', incometax_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "SD"){ $ResStr .= ", sd_percent = '".$SaveRecPerc."', sd_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "WC"){ $ResStr .= ", water_cost = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "EC"){ $ResStr .= ", electricity_cost = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "MOBINT"){ $ResStr .= ", mob_adv_int_perc = '".$SaveRecPerc."', mob_adv_int_amt = '".$SaveRecAmt."'"; }
			if($SaveRecDescId == "PMINT"){ $ResStr .= ", pl_mac_adv_int_perc = '".$SaveRecPerc."', pl_mac_adv_int_amt = '".$SaveRecAmt."'"; }
			$InsertHoaQuery = ", sheetid = '$SaveSheetId', globid = '', rbn = '$SaveRbn', rec_code = '$SaveRecHoaScodeRec', 
			rec_perc = '$SaveRecPerc', rec_amt = '$SaveRecAmt', shortcode_id = '$SaveRecHoaScodeId', createdby = '".$_SESSION['sid']."', createdon = NOW()";
			array_push($QueryArr,$InsertHoaQuery);
		}
	}*/
	
	/*$InsertQuery 	= "INSERT INTO memo_payment_accounts_edit SET abstract_net_amt = '$SaveNetAmt', 
					  gst_rate = '$SaveGstRate', gst_amount = '$SaveGstAmount', is_ldc_appl = '', pan_type = '' ".$ResStr.", net_payable_amt = '$ChequeAmount',  
					  edit_flag = 'EDIT', mop_type = 'MISC', staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', modifieddate = NOW()";
	$InsertSql 		= mysqli_query($dbConn,$InsertQuery);
	$MopId = mysqli_insert_id($dbConn);
	if(count($QueryArr)>0){
		foreach($QueryArr as $Query){
			$RecQuery 		= "INSERT INTO mop_rec_dt SET mopid = '$MopId'".$Query;
			//echo $RecQuery."<br/>";
			$RecQueryExe	= mysqli_query($dbConn,$RecQuery);
		}
	}*/
	$VrExist = 0;
	$CheckVrDate = date('Y-m',strtotime($SaveVouchDate));
	$SelectQuery1 = "SELECT vuid FROM voucher_upt WHERE unitid = '$GlobUnitId' AND globid = '$GlobId' AND sheetid = '$SaveSheetId' AND ((vr_no = '$SaveVouchNo' AND vr_amt = '$SaveVouchAmt' AND DATE_FORMAT(vr_dt,'%Y-%m') = '$CheckVrDate') OR (voucher_for = 'SDREL'))";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$VrExist = 1;
		}
	}
	if($VrExist == 0){
		$InsertQuery = "insert into voucher_upt set globid = '$GlobId', sheetid = '$SaveSheetId', unitid = '$GlobUnitId', wo = '$WorkOrder', item_id = '', 
		item = '$WorkName', name_contractor = '$SaveContName', contid = '$SaveContId', wo_amt = '$WorkOrderCost', vr_no = '$SaveVouchNo', 
		vr_dt = '$SaveVouchDate', vr_amt = '$SaveVouchAmt', wo_dt = '$WorkOrderDt', o_pin = '$SavePinNo', n_pin = '$SavePinNo', code = '',
		paid_amt = '', hoa = '$SaveHoa', new_hoa = '$SaveHoa', indentor = '', eic = '', grp = '', sec = '', grp_div_sec = '', plant_serv = '', sanct_om_act_sno = '', 
		sanct_om_nwme_sno = '', sanct_act_id = '', createdon = NOW(), staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', entry_flag = 'MAN',
		voucher_for = 'SDREL', creator_flag = 'ACC'";
		//echo $InsertQuery;exit;
		$InsertSql 	= mysqli_query($dbConn,$InsertQuery);
		
		if($InsertSql == true){
			$msg = "SD Voucher data saved successfully";
			$success = 1;
		}else{
			$msg = "Error : SD Voucher data not saved";
			$success = 0;
		}
	}else if($VrExist == 1){
		$msg = "Duplicate Error : Voucher No. ".$SaveVouchNo." already created for this month / SD already released for this work";
		$success = 0;
	}else{
		$msg = "Error : Invalid data / Invalid attempt";
		$success = 0;
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
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<style>
/*.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.tboxsmclass{
	font-size:11px;
}
.smlabel{
	line-height:10px;
	padding-bottom:2px;
}
.rectable{
	margin-bottom: 0px !important;
}
input[type="checkbox"], input[type="radio"] {
  margin: 0px;
}
.chosen-container-single .chosen-single{
	padding:2px 4px !important;
}*/
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
.box-container {
  padding: 0px 20px;
}
/*#mySidenav a {
	position:static !important;
}*/
#mySidenav a{
	left:0px !important;
}
#mySidenav a:hover {
  width: 200px !important;
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
				<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
                    <blockquote class="bq1" style="overflow:auto">
						<div class="row">
							<div class="box-container box-container-lg">
								
								<div class="row smclearrow"></div>
								<div class="div2">
									<div class="card" style="margin-top:2px;">
										<a data-url="VoucherEntry">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													<span>Works Voucher</span>
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="VoucherEntryMiscell">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Miscellaneous Voucher
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="VoucherEntryLCess">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Labour Cess Voucher	
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="VoucherEntrySD">
										<div class="face-static tabbtn tabbtn-active">
											<div class="card-body padding-1">
												<div class="row">
													<i class="fa fa-check-square-o" style="font-size:14px"></i>&nbsp;&nbsp;SD Release Voucher
												</div>                         
											</div>
										</div>
										</a>
										<!--<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Income Tax
												</div>                         
											</div>
										</div>
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													GST
												</div>                         
											</div>
										</div>-->
									</div>
								</div>
								
								
								
							<div class="div10">
							<div class="box-container box-container-lg lg-box" align="center">
								<div class="div12">
									<div class="card cabox" style="margin-bottom:1px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;SD Release Voucher</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox" style="padding-top:0px; padding-bottom:0px;">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2" style="padding-top:0px;">
																<div class="row" align="center">
																	
																	<div class="div12 smlboxlabel">
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;CCNO</div>
																			<div>
																				<input type="text" name="txt_ccno" id="txt_ccno" class="dynamicboxlg" autocomplete="off" required />
																			</div>
																		</div>
																		<div class="div1 pd-lr-1">
																			<div class="lboxlabel">&nbsp;</div>
																			<div>
																				<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
																			</div>
																		</div>
																		<div class="div10 pd-lr-1">
																			<div class="lboxlabel">&nbsp;Name of Work</div>
																			<div>
																				<input type="text" name="txt_work_name" id="txt_work_name" class="dynamicboxlg inp" required />
																				<input type="hidden" name="txt_sheetid" id="txt_sheetid" class="tboxsmclass bordd1" readonly="" />
																				<input type="hidden" name="txt_globid" id="txt_globid" class="tboxsmclass bordd1" readonly="" />
																				<input type="hidden" name="txt_unitid" id="txt_unitid" class="tboxsmclass bordd1" readonly="" value="6" />
																				<input type="hidden" name="txt_pinno" id="txt_pinno" class="tboxsmclass bordd1" readonly="" value="712" />
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
								<div class="row clearrow"></div>
								<div class="div12">
									<div class="card cabox" style="margin-top:0px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Contractor & Bank Details</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Contractor Name</div>
																		<div>
																			<input type="hidden" name="txt_contractor" id="txt_contractor" class="dynamicboxlg inp" />
																			<select name="cmb_contractor" id="cmb_contractor" class="dynamicboxlg inp" required>
																				<option value=""> -- Select --</option>
																				<?php echo $objBind->BindCont(0); ?>
																			</select>
																		</div>
																	</div>
																	<!--<div class="div9 pd-lr-1">
																		<div class="lboxlabel">PAN No.</div>
																		<div>
																			<input type="text" name="txt_pan_no" id="txt_pan_no" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">IT (%)</div>
																		<div>
																			<input type="text" name="txt_it_perc" id="txt_it_perc" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">LDC Certifi.</div>
																		<div>
																			<input type="text" name="txt_ldc_cert_no" id="txt_ldc_cert_no" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">LDC Amt.</div>
																		<div>
																			<input type="text" name="txt_ldc_amt" id="txt_ldc_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">Valid To</div>
																		<div>
																			<input type="text" name="txt_ldc_valid_to" id="txt_ldc_valid_to" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel"><span class="Ldc LdcStatus">LDC Status</span>&nbsp;</div>
																		<div class="lboxlabel Ldc LdcStatus">
																			<input type="text" name="txt_ldc_status" id="txt_ldc_status" class="dynamicboxlg inp" />
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
																			<input type="text" name="txt_gst_no" id="txt_gst_no" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">GST Rate</div>
																		<div>
																			<input type="text" name="txt_gst_rate" id="txt_gst_rate" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div3 pd-lr-1">
																		<div class="lboxlabel">Inc./ Exc.</div>
																		<div>
																			<select name="cmb_gst_inc_exc" id="cmb_gst_inc_exc" class="dynamicboxlg inp" required>
																				<option value=""> --  Select --</option>
																				<option value="I">Inclusive</option>
																				<option value="E">Exclusive</option>
																			</select>
																		</div>
																	</div>-->
																	<!--<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Bank Account No. <font class="efont ptr BankData">(<i class="fa fa-edit" style="font-size:16px; top:2px; position:relative"></i> Click here to select)</font></div>
																		<div>
																			<input type="text" name="txt_bank_acc" id="txt_bank_acc" class="dynamicboxlg inp" required />
																			<input type="hidden" name="txt_bank_id" id="txt_bank_id" class="dynamicboxlg inp" />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Bank Name</div>
																		<div>
																			<input type="text" name="txt_bank_name" id="txt_bank_name" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Branch Name</div>
																		<div>
																			<input type="text" name="txt_branch" id="txt_branch" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">IFSC Code</div>
																		<div>
																			<input type="text" name="txt_ifsc" id="txt_ifsc" class="dynamicboxlg inp" required />
																		</div>
																	</div>-->
																	
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Bank Account No. <!--<font class="efont ptr BankData">(<i class="fa fa-edit" style="font-size:16px; top:2px; position:relative"></i> Click here to select)</font>--></div>
																		<div>
																			<input type="text" name="txt_bank_acc" id="txt_bank_acc" class="dynamicboxlg inp" required />
																			<input type="hidden" name="txt_bank_id" id="txt_bank_id" class="dynamicboxlg inp" />
																		</div>
																	</div>
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Bank Name</div>
																		<div>
																			<input type="text" name="txt_bank_name" id="txt_bank_name" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Branch Name</div>
																		<div>
																			<input type="text" name="txt_branch" id="txt_branch" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">IFSC Code</div>
																		<div>
																			<input type="text" name="txt_ifsc" id="txt_ifsc" class="dynamicboxlg inp" required />
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
								<!--<div class="div3">
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
																			<input type="text" name="txt_upto_dt_amt" id="txt_upto_dt_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Deduct Previous Value &#8377;</div>
																		<div>
																			<input type="text" name="txt_ded_prev_amt" id="txt_ded_prev_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">This Bill Value &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_value" id="txt_bill_value" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Add/Deduct Secured Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_sec_adv_amt" id="txt_sec_adv_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Mobilization Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_mob_adv_amt" id="txt_mob_adv_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Escalation &#8377;</div>
																		<div>
																			<input type="text" name="txt_esc_amt" id="txt_esc_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">P&M Advance &#8377;</div>
																		<div>
																			<input type="text" name="txt_pm_adv_amt" id="txt_pm_adv_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div12 pd-lr-1">
																		<div class="lboxlabel">Net Total (Pass Order Amount) &#8377;</div>
																		<div>
																			<input type="text" name="txt_net_amt" id="txt_net_amt" class="dynamicboxlg inp" required />
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
								</div>-->
								<div class="row smclearrow"></div>
								<div class="div12">
									<div class="card cabox" style="margin-top:0px;">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Voucher Entry</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Net Amount  &#8377;<font class="efont ptr InfoData"> (View)</font></div>
																		<div>
																			<input type="text" name="txt_net_amt" id="txt_net_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Voucher No.</div>
																		<div>
																			<input type="text" name="txt_vouch_no" id="txt_vouch_no" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Voucher Date</div>
																		<div>
																			<input type="text" name="txt_vouch_dt" id="txt_vouch_dt" class="dynamicboxlg inp datepicker" required />
																		</div>
																	</div>
																	<div class="div2 pd-lr-1">
																		<div class="lboxlabel">Voucher Amount</div>
																		<div>
																			<input type="text" name="txt_vouch_amt" id="txt_vouch_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">&nbsp;HOA</div>
																		<div>
																			<input type="text" name="txt_vouch_hoa" id="txt_vouch_hoa" class="dynamicboxlg" />
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row" align="center">
																		<div class="div12 pd-lr-1" align="center">
																			<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="btn btn-info">
																			<input type="reset" name="btnReset" id="btnReset" value=" Reset " class="btn btn-info">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="div7">
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
																		<div class="lboxlabel">Net Amount  &#8377;<font class="efont ptr InfoData"> (View Info)</font></div>
																		<div>
																			<input type="text" name="txt_net_amt" id="txt_net_amt" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Bill Amount for GST &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="dynamicboxlg inp" required />
																		</div>
																	</div>
																	<div class="div4 pd-lr-1">
																		<div class="lboxlabel">Bill Amount for IT &#8377;</div>
																		<div>
																			<input type="text" name="txt_bill_amt_it" id="txt_bill_amt_it" class="dynamicboxlg inp" required />
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
																				<select name="cmb_rec_desc_0" id="cmb_rec_desc_0" class="dynamicboxlg inp">
																					<option value="">---- Select ----</option>
																					<optgroup label="Part A:">
																					<?php if(count($GlobPartARecArr)>0){
																						foreach($GlobPartARecArr as $PARecKey => $PARecValue){
																							echo '<option value="'.$PARecKey.'">'.$PARecValue.'</option>';
																						}
																					}
																					?>
																					</optgroup>
																					<optgroup label="Part B:">
																					<?php if(count($GlobPartBRecArr)>0){
																						foreach($GlobPartBRecArr as $PBRecKey => $PBRecValue){
																							echo '<option value="'.$PBRecKey.'">'.$PBRecValue.'</option>';
																						}
																					}
																					?>
																					</optgroup>
																				</select>
																			</td>
																			<td align="center"><input type="text" name="txt_rec_perc_0" id="txt_rec_perc_0" class="dynamicboxlg inp" /></td>
																			<td align="center"><input type="text" name="txt_rec_amt_0" id="txt_rec_amt_0" class="dynamicboxlg inp" /></td>
																			<td align="center">
																				<select name="cmb_rec_hoa_scode_0" id="cmb_rec_hoa_scode_0" class="dynamicboxlg inp">
																					<option value="">---- Select ----</option>
																					<?php echo $objBind->BindShortCodeForMop(''); ?>
																				</select>
																			</td>
																	   		<td align="center"><i class="fa fa-plus-square sqadd ptr inp" id="AddRec" style="font-size:24px"></i></td>
																	   	</tr>
																	   	<tr style="background-color:#FFF">
																	   		<td colspan="2" align="right" class="rboxlabel">Total Recovery Amount &#8377; &nbsp;</td>
																			<td><input type="text" name="txt_tot_rec_amt" id="txt_tot_rec_amt" class="dynamicboxlg inp" required /></td>
																			<td></td>
																			<td></td>
																	   	</tr>
																	</table>
																</div>
																<div class="row smclearrow"></div>
																<div class="row smclearrow"></div>
																<div class="card-header inkblue-card" align="left">&nbsp;Voucher Details</div>
																<div class="row smclearrow"></div>
																<div class="div2 pd-lr-1">
																	<div class="lboxlabel">Voucher No.</div>
																	<div>
																		<input type="text" name="txt_net_pay_amt" id="txt_net_pay_amt" class="dynamicboxlg inp" required />
																	</div>
																</div>
																<!--<div class="div1 pd-lr-1">
																	<div class="cboxlabel">&nbsp;</div>
																	<div>
																		<input type="checkbox" name="ch_is_advance" id="ch_is_advance" class="dynamicboxlg inp" required />
																	</div>
																</div>-->
																<!--<div class="div2 pd-lr-1">
																	<div class="lboxlabel">Voucher Date</div>
																	<div>
																		<input type="text" name="txt_net_pay_amt" id="txt_net_pay_amt" class="dynamicboxlg inp" required />
																	</div>
																</div>
																<div class="div3 pd-lr-1">
																	<div class="lboxlabel">Voucher Amount</div>
																	<div>
																		<input type="text" name="txt_net_pay_amt" id="txt_net_pay_amt" class="dynamicboxlg inp" required />
																	</div>
																</div>
																<div class="div5 pd-lr-1">
																	<div class="lboxlabel">&nbsp;HOA</div>
																	<div>
																		<input type="text" name="txt_net_pay_adv_amt" id="txt_net_pay_adv_amt" class="dynamicboxlg" />
																	</div>
																</div>
																<div class="row smclearrow"></div>
																<div class="row smclearrow"></div>
																<div class="row" align="center">
																	<div class="div12 pd-lr-1" align="center">
																		<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="btn btn-info">
																		<input type="reset" name="btnReset" id="btnReset" value=" Reset " class="btn btn-info">
																	</div>
																</div>
																<div class="row smclearrow"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>-->
								
							</div>
						</div>
								
								
								<!--<div class="div10">
									<div class="card" style="margin-top:2px;">
										<div class="face-static2">
											<div class="card-header inkblue-card">Miscellaneous Voucher Entry<span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1">
												<div class="taxrow row">
													<div class="row smclearrow"></div>
													<div class="div12 smlboxlabel">
														<div class="div2">
															<span class="lboxlabel">&emsp;Reference No.</span>
														</div>
														<div class="div6">
															<input type="text" name="txt_sr_no" id="txt_sr_no" class="tboxsmclass bordd1">
														</div>
														&emsp;
													</div>
													<div class="row smclearrow"></div>
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Nature of Claim & Payee Details</span></div>
																<div class="row smclearrow"></div>
																<div class="div2 lboxlabel">Nature of Claim</div>
																<div class="div10" align="left">
																	<select name="cmb_contractor" id="cmb_contractor" class="tboxsmclass">
																		<option value="">Deployment of Pvt. Security at FRFCF</option>
																	</select>
																</div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Payee Name</div>
																<div class="div4" align="left">
																	<select name="cmb_contractor" id="cmb_contractor" class="tboxsmclass">
																		<option value="">M/S . TAMILNADU EX-SERVICEMEN'S CORPORATION LIMITED (TEXCO)</option>
																		<?php echo $objBind->BindCont(0); ?>
																	</select>
																</div>
																<div class="div1 rboxlabel">PAN No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_pan_no" id="txt_pan_no" class="tboxsmclass bordd1" required />
																</div>
																<div class="div1 rboxlabel">GST No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_gst_no" id="txt_gst_no" class="tboxsmclass bordd1" required />
																</div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Account No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_bank_acc" id="txt_bank_acc" class="tboxsmclass bordd1" required />
																</div>
																<div class="div1 rboxlabel">Bank Name&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass bordd1" required />
																</div>
																<div class="div1 rboxlabel">Branch &nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_branch" id="txt_branch" class="tboxsmclass bordd1" required />
																</div>
																<div class="div1 rboxlabel">IFSC Code&nbsp;</div>
																<div class="div1" align="left">
																	<input type="text" name="txt_ifsc" id="txt_ifsc" class="tboxsmclass bordd1" required />
																</div>
																<div class="row clearrow"></div>
															</div>
														</div>
													</div>  
													
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Bill Details</span> <i class="fa fa-search ptr DtData" id="BD" style="font-size:16px"></i></div>
																<div class="row smclearrow"></div>
																<div class="div3">
																	<span class="lboxlabel">Net Total Amt.</span>
																	<input type="text" name="txt_bill_value" id="txt_bill_value" class="statictboxmd" readonly="" required />
																</div>
																<div class="div3">
																	<span class="lboxlabel">Bill Amt. for GST</span>
																	<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="statictboxmd" readonly="" required />
																</div>
																<div class="div3">
																	<span class="lboxlabel">Bill Amt. for IT&emsp;</span>
																	<input type="text" name="txt_bill_amt_it" id="txt_bill_amt_it" class="statictboxmd" readonly="" required />
																</div>
																<div class="row clearrow"></div>
															</div>
														</div>
													</div>
													
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Recovery Details</span> <i class="fa fa-edit ptr DtData" id="RD" style="font-size:18px; top:3px; position: relative"></i></div>
																<div class="div12 hide" id="PartARec"><span class="spanhead-o">Part A :</span></div>
																<div class="row smclearrow"></div>
																<div class="div12 hide" id="PartBRec"><span class="spanhead-o">Part B :</span></div>
															</div>
														</div>
													</div>
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Voucher Details</span></div>
																<div class="row smclearrow"></div>
																<div class="div12 smlboxlabel">
																	<div class="div3">
																		<span class="lboxlabel">Voucher No.&emsp; </span>
																		<input type="text" name="txt_vouch_no" id="txt_vouch_no" class="statictboxmd" value="">
																	</div>
																	<div class="div3">
																		<span class="lboxlabel">Voucher Date&emsp;</span>
																		<input type="text" name="txt_vouch_dt" id="txt_vouch_dt" class="statictboxmd datepicker">
																	</div>
																	<div class="div3">
																		<span class="lboxlabel">Voucher Amount</span>
																		<input type="text" name="txt_vouch_amt" id="txt_vouch_amt" class="statictboxmd">
																	</div>
																	<div class="div3">
																		<span class="lboxlabel">Head of Account</span>
																		<input type="text" name="txt_vouch_hoa" id="txt_vouch_hoa" class="statictboxmd">
																	</div>
																</div>
																<div class="row smclearrow"></div>
																<div class="row smclearrow"></div>
															</div>
														</div>
													</div>
													<div class="row smclearrow"></div>
													<div class="row smclearrow"></div>
													<div class="div12 cboxlabel"><input type="submit" name="btnSave" id="btnSave" class="gbtn" value=" Submit "></div>
													<div class="row smclearrow"></div>
													                       
												</div>
											</div>
										</div>
									</div>
								</div>-->
								
								
								
								
							</div>
						</div>
                    </blockquote>
                </div>
            </div>
        </div>
    </form>
    <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>

var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$("#cmb_contractor").chosen();
$("#cmb_nature_claim").chosen();
$(function() {
	var RowIndex = 1;
	
	var KillEvent = 0;
	$("body").on("click","#btnSave", function(event){
		if(KillEvent == 0){
			var Ccno 		= $("#txt_ccno").val();
			var WorkName 	= $("#txt_work_name").val();
			var ContId 		= $("#cmb_contractor").val();
			var BankAccNo 	= $("#txt_bank_acc").val();
			var BankName 	= $("#txt_bank_name").val();
			var Branch 		= $("#txt_branch").val();
			var IfscCode 	= $("#txt_ifsc").val();
			var NetAmt 		= $("#txt_net_amt").val();
			var VouchNo 	= $("#txt_vouch_no").val();
			var VouchDate	= $("#txt_vouch_dt").val();
			var VouchAmt	= $("#txt_vouch_amt").val();
			var VouchHoa	= $("#txt_vouch_hoa").val();
			if(Ccno == ""){
				BootstrapDialog.alert("Please enter CCNo.");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Work name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContId == ""){
				BootstrapDialog.alert("Contractor Name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankAccNo == ""){
				BootstrapDialog.alert("Bank account no. should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankName == ""){
				BootstrapDialog.alert("Bank name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(Branch == ""){
				BootstrapDialog.alert("Branch should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(IfscCode == ""){
				BootstrapDialog.alert("IFSC should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(NetAmt == ""){
				BootstrapDialog.alert("Net amount should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchNo == ""){
				BootstrapDialog.alert("Voucher no. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchDate == ""){
				BootstrapDialog.alert("Voucher date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchAmt == ""){
				BootstrapDialog.alert("Voucher amount should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchHoa == ""){
				BootstrapDialog.alert("Voucher HOA should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save voucher data ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btnSave").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
			}
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
	$("body").on("click",".ChBank", function(event){
		if($(this).is(':checked')){
			$(".BankRow").removeClass("active-tr");
			$(this).closest('tr').addClass("active-tr");
		}
	});
	function CalcRecAmt(RecCode){
		var NetAmt = $("#txt_net_amt").val();
		if(RecCode == "LCESS"){
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
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "IGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "CGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SD"){
			var RecPerc = $("#txt_rec_perc_0").val();
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		$("#cmb_rec_hoa_scode_0").chosen('destroy');
		$("#cmb_rec_hoa_scode_0").val(RecCode);
		$("#cmb_rec_hoa_scode_0").chosen();
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
	$("body").on("change","#txt_bill_amt_it", function(event){
		if($("#IT").length){
			var RowIndex = $("#IT").attr("data-id");
			var NetAmtForIt = $("#txt_bill_amt_it").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(NetAmtForIt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
		}
	});
	$("body").on("click","#ch_is_advance", function(event){
		if($(this).is(':checked')){
			$("#txt_adv_perc").removeClass("disable");
		}else{
			$("#txt_adv_perc").val('');
			$("#txt_adv_perc").addClass("disable");
			var NetPay = $("#txt_net_pay_amt").val();
			$("#txt_net_pay_adv_amt").val('');
		}
	});
	$("body").on("change","#txt_adv_perc", function(event){
		var AdvPerc = $(this).val();
		var NetPay = $("#txt_net_pay_amt").val();
		var AdvanceAmt = Number(NetPay)*Number(AdvPerc)/100;
			AdvanceAmt = AdvanceAmt.toFixed();
		$("#txt_net_pay_adv_amt").val(AdvanceAmt);
	});
	
	
	
	$("body").on("click","#AddRec", function(event){
		var RecDesc 		= $("#cmb_rec_desc_0 option:selected").text();
		var RecId 			= $("#cmb_rec_desc_0").val();
		var RecIntPerc 		= $("#txt_rec_perc_0").val();
		var RecAmt 			= $("#txt_rec_amt_0").val();
		var RecHoaShCode 	= $("#cmb_rec_hoa_scode_0 option:selected").text();
		var RecHoaRecCode 	= $("#cmb_rec_hoa_scode_0").val();
		var RecHoaScodeId	= $("#cmb_rec_hoa_scode_0 option:selected").attr("data-id");
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
			var RowStr = '<tr data-id="'+RowIndex+'" id="'+RecId+'" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecDesc+'" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecIntPerc+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecAmt+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
			$("#RecTable").find('tr:last').prev().after(RowStr);
			$("#txt_rec_perc_0").val('');
			$("#txt_rec_amt_0").val('');
			$("#cmb_rec_desc_0").chosen('destroy');
			$("#cmb_rec_desc_0").val('');
			$("#cmb_rec_desc_0").chosen();
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val('');
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
	/*$("body").on("click","#btnGo", function(event){
		$("#txt_net_amt").val('');
		GetLCessSdInfo("LCESS","SUM");
	});*/
	$("body").on("click",".InfoData", function(event){
		var WorkId = $("#txt_sheetid").val();
		GetLCessSdInfo("SD","ALL",WorkId);
	});
	
	function GetLCessSdInfo(Code,Type,WorkId){
		var Ccno = $("#txt_ccno").val();
		if((Ccno != '')&&(WorkId != '')){
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/FindLCessSdInfo.php', 
				data: { Ccno: Ccno, Code: Code, Type: Type, WorkId: WorkId }, 
				dataType: 'json',
				success: function (data) {   //alert(data['computer_code_no']);
					if(data != null){
						if(Code == "SD"){
							if(Type == "SUM"){
								var SDAmt = data['sd_amt'];
								$("#txt_net_amt").val(SDAmt);
							}
							if(Type == "ALL"){
								var SDData = data;
								
								var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
								TableStr += '<thead><tr><th class="lboxlabel">S.No</th><th class="lboxlabel">CCODE.</th><th class="lboxlabel">RAB NO.</th><th class="lboxlabel">NAME OF CONTRACTOR</th><th class="lboxlabel">NAME OF WORK</th><th class="lboxlabel">DATE OF PAYMENT</th><th class="lboxlabel">BILL VALUE</th><th class="lboxlabel">SD AMOUNT</th></tr></thead>';
								TableStr += '<tbody>';
								var Sno = 1; var TotalAmt = 0;
								$.each(SDData, function(index, element) {
									TotalAmt = Number(TotalAmt) + Number(element.sd_amt);
									TableStr += '<tr><td class="lboxlabel">'+Sno+'</td><td class="lboxlabel">'+element.computer_code_no+'</td><td class="lboxlabel">'+element.bill_rbn+'</td><td class="lboxlabel">'+element.cont_name+'</td><td class="lboxlabel">'+element.work_name+'</td><td class="lboxlabel">'+element.abstract_net_amt+'</td><td class="lboxlabel">'+element.payment_dt+'</td><td class="rboxlabel">'+element.sd_amt+'</td></tr>';
									Sno++;
								});
								TableStr += '<tr><td class="rboxlabel" colspan="7">Total SD Amount (Rs.)&nbsp;</td><td class="rboxlabel">'+TotalAmt.toFixed(2)+'</td></tr>';
								TableStr += '</tbody>';
								TableStr += '</table>';
								BootstrapDialog.show({
									title: 'SD Information',
									message: TableStr,
									onshow: function(dialogRef){
										$(".modal-dialog").css("width","80%");
									},
									onhidden: function(dialogRef){
										$(".modal-dialog").css("width","60%");
									},
									buttons: [{
										label: 'OK',
										cssClass: 'btn btn-info',
										action: function(dialog) {
											dialog.close();
										}
									}]
								});
								
							}
						}
					}
				}
			});
		}
	}
	var cGst = 0; var sGst = 0; var iGst = 0;
	$("body").on("click","#btnGo", function(event){
		cGst = 0; sGst = 0; iGst = 0; RowIndex = 1;
		$("#cmb_contractor").chosen('destroy');
		//$("#cmb_rec_desc_0").chosen('destroy');
		//$("#cmb_rec_hoa_scode_0").chosen('destroy');
		$(".inp").val('');
		//$(".Ldc").addClass('hide');
		//$(".LdcStatus").removeClass('hide');
		//$("#cmb_rec_desc_0").chosen();
		//$("#cmb_rec_hoa_scode_0").chosen();
		$("#cmb_contractor").chosen();
		var Ccno = $("#txt_ccno").val();
		$("#txt_contractor").val('');
		if($("#cmb_contractor").val() != ''){
			var ContName = $("#cmb_contractor option:selected").text();
			 $("#txt_contractor").val(ContName);
		}
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindVoucherData.php', 
			data: { Ccno: Ccno, PageCode: 'ACC' }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var WData 	 	= data['WData'];
					var RABData  	= data['RABData'];
					var RECData  	= data['RECData'];
					var CONTData 	= data['CONTData'];
					var BKData   	= data['BKData'];
					var RecHoaData  = data['RecHoaData'];
					//var TableStr = '<table class="table table-bordered rectable">';
					if(WData != null){
						$("#txt_work_name").val(WData['work_name']);
						$("#txt_sheetid").val(WData['sheet_id']);
						$("#txt_globid").val(WData['globid']);
						$("#txt_bank_id").val(WData['cbdtid']);
						/*$("#cmb_gst_inc_exc").val(WData['gst_inc_exc']);
						$("#txt_gst_rate").val(WData['gst_perc_rate']);
						$("#cmb_rec_desc_0").chosen("destroy");
						GetLCessSdInfo("SD","SUM",WData['sheet_id']);
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
						$("#cmb_rec_desc_0").chosen();*/
						
					}
					if(CONTData != null){
						$("#txt_pan_no").val(CONTData['pan_no']);
						$("#txt_gst_no").val(CONTData['gst_no']);
						$("#cmb_contractor").chosen('destroy');
						$("#cmb_contractor").val(CONTData['contid']);
						$("#cmb_contractor").chosen();
						$("#txt_it_perc").val(CONTData['it_rate']);
						$("#txt_contractor").val(CONTData['name_contractor']);
						/*$(".Ldc").addClass("hide");
						if(CONTData['is_ldc_appl'] == "Y"){
							$("#txt_ldc_cert_no").val(CONTData['ldc_certi_no']);
							$("#txt_ldc_amt").val(CONTData['ldc_max_amt']);
							var LdcValid = moment(CONTData['ldc_validity']).format('DD/MM/YYYY');
							$("#txt_ldc_valid_to").val(LdcValid);
							
							var TodayDate = moment().format('DD/MM/YYYY');
							var dt1 = LdcValid.split("/");
							var dt2 = TodayDate.split("/");
							var LdcValidStr 	= new Date(dt1[2], dt1[1]-1, dt1[0]);  // -1 because months are from 0 to 11
							var TodayDateStr   	= new Date(dt2[2], dt2[1]-1, dt2[0]);
							if(LdcValidStr > TodayDateStr){
								$("#LdcValid").removeClass("hide");
								$("#txt_ldc_status").val("V");
								$("#txt_it_perc").val(CONTData['ldc_rate']);
							}else{
								$("#LdcInValid").removeClass("hide");
								$("#txt_ldc_status").val("NV");
								$("#txt_it_perc").val(CONTData['it_rate']);
							}
						}*/
						/*if(CONTData['gst_type'] != null){
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
						}*/
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
					/*if(RABData != null){
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
						$("#txt_upto_dt_amt").val(RABData['upto_date_total_amount']); 
						$("#txt_ded_prev_amt").val(RABData['dpm_total_amount']);
						$("#txt_bill_value").val(RABData['slm_total_amount']);
						$("#txt_sec_adv_amt").val(RABData['secured_adv_amt']);
						$("#txt_mob_adv_amt").val(RABData['mob_adv_amt']);
						$("#txt_esc_amt").val(RABData['slm_total_amount_esc']);
						$("#txt_pm_adv_amt").val(RABData['pl_mac_adv_amt']);
						var NetAmount = Number(RABData['slm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc']);
						NetAmount = NetAmount.toFixed(2);
						$("#txt_net_amt").val(NetAmount);
						$("#txt_net_pay_amt").val(NetAmount);
						$("#txt_tot_rec_amt").val('0.00');
						$("#txt_bill_amt_gst").val(RABData['bill_amt_for_gst']);
						$("#txt_bill_amt_it").val(RABData['bill_amt_for_gst']);
					}*/
					if(RECData != null){
						/*var RowStr = ''; var RecHoaShCode = ''; var RecHoaRecCode = ''; var RecHoaScodeId = '';
						if(RECData['lw_cess_amt'] != 0){
							if(RecHoaData['LCESS'] != null){
								var RecHoaDt  = RecHoaData['LCESS'];
								RecHoaShCode  =  RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="LCESS" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCess" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCESS"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['lw_cess_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['lw_cess_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['mob_adv_amt_rec'] != 0){
							if(RecHoaData['MOB'] != null){
								var RecHoaDt  = RecHoaData['MOB'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="MOB" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOB"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_amt_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['pl_mac_adv_rec'] != 0){
							if(RecHoaData['PM'] != null){
								var RecHoaDt  = RecHoaData['PM'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="PM" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PM"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['hire_charges'] != 0){
							if(RecHoaData['HIRE'] != null){
								var RecHoaDt  = RecHoaData['HIRE'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="HIRE" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Hire Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="HC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['hire_charges']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['sgst_tds_amt'] != 0){
							if(RecHoaData['SGST'] != null){
								var RecHoaDt  = RecHoaData['SGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="SGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['cgst_tds_amt'] != 0){
							if(RecHoaData['CGST'] != null){
								var RecHoaDt  = RecHoaData['CGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="CGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['cgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['cgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['igst_tds_amt'] != 0){
							if(RecHoaData['IGST'] != null){
								var RecHoaDt  = RecHoaData['IGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="IGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['igst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['igst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['incometax_amt'] != 0){
							if(RecHoaData['IT'] != null){
								var RecHoaDt  = RecHoaData['IT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="IT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['incometax_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['incometax_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['sd_amt'] != 0){
							if(RecHoaData['SD'] != null){
								var RecHoaDt  = RecHoaData['SD'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="SD" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sd_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sd_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['water_cost'] != 0){
							if(RecHoaData['WC'] != null){
								var RecHoaDt  = RecHoaData['WC'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="WC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Water Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="WC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['water_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['electricity_cost'] != 0){
							if(RecHoaData['EC'] != null){
								var RecHoaDt  = RecHoaData['EC'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="EC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Electricity Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="EC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['electricity_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['mob_adv_int_amt'] != 0){
							if(RecHoaData['MOBINT'] != null){
								var RecHoaDt  = RecHoaData['MOBINT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="MOBINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOBINT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['mob_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['pl_mac_adv_int_amt'] != 0){
							if(RecHoaData['PMINT'] != null){
								var RecHoaDt  = RecHoaData['PMINT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="PMINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PMINT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['pl_mac_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						$("#RecTable").find('tr:last').prev().after(RowStr);
						CalcTotalRec();*/
					}
				}
			}
		});
	});
	
});
</script>
</body>
</html>

