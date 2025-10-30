<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require_once 'ExcelReader/excel_reader2.php';
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
if(isset($_POST['btnSave']) == " Submit "){
	$SaveUnit 		= $GlobUnitId;//$_POST['txt_unitid'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	$SaveRbn 		= $_POST['txt_rbn'];
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
	$VrExist = 0;
	$CheckVrDate = date('Y-m',strtotime($SaveVouchDate));
	$SelectQuery1 = "SELECT vuid FROM voucher_upt WHERE unitid = '$GlobUnitId' AND globid = '$GlobId' AND sheetid = '$SaveSheetId' AND vr_no = '$SaveVouchNo' AND vr_amt = '$SaveVouchAmt' AND DATE_FORMAT(vr_dt,'%Y-%m') = '$CheckVrDate'";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$VrExist = 1;
		}
	}
	//echo $SelectQuery1;exit;
	if($VrExist == 0){
		$InsertQuery = "insert into voucher_upt set globid = '$GlobId', sheetid = '$SaveSheetId', unitid = '$GlobUnitId', wo = '$WorkOrder', item = '$WorkName', 
		name_contractor = '$ContName', contid = '$ContId', wo_amt = '$WorkOrderCost', vr_no = '$SaveVouchNo', 
		vr_dt = '$SaveVouchDate', vr_amt = '$SaveVouchAmt', wo_dt = '$WorkOrderDt', o_pin = '$SavePinNo', n_pin = '$SavePinNo', code = '', ccno = '$Ccno', 
		paid_amt = '', hoa = '$SaveHoa', new_hoa = '$SaveHoa', indentor = '', eic = '', grp = '', sec = '', grp_div_sec = '', plant_serv = '', sanct_om_act_sno = '', 
		sanct_om_nwme_sno = '', sanct_act_id = '', createdon = NOW(), staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', entry_flag = 'MAN',
		voucher_for = 'WO', creator_flag = 'ACC'";
		$InsertSql 	= mysqli_query($dbConn,$InsertQuery);
		
		$UpdateQuery1 	= "UPDATE memo_payment_accounts_edit SET payment_dt = '$SaveVouchDate' WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";
		$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
		
		$UpdateQuery2 = "UPDATE abstractbook SET payment_dt = '$SaveVouchDate', payment_dt_cr_by = '$staffid', payment_cr_level = '".$_SESSION['levelid']."' WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRbn'";// and rbn = ''";
		$UpdateSql2 = mysqli_query($dbConn,$UpdateQuery2);
		if($InsertSql == true){
			$msg = "Voucher data saved successfully";
			$success = 1;
		}else{
			$msg = "Error : Voucher data not saved";
			$success = 0;
		}
	}else if($VrExist == 1){
		$msg = "Duplicate Error : Voucher No. ".$SaveVouchNo." already created for this month";
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
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.tboxsmclass{
	/*height:20px;*/
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
.rboxlabel,.lboxlabel,.cboxlabel{
	font-weight:500;
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
										<div class="face-static tabbtn tabbtn-active">
											<div class="card-body padding-1">
												<div class="row">
													<span><i class="fa fa-check-square-o" style="font-size:14px"></i>&nbsp;&nbsp;Works Voucher</span>
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
													Labour Cess	Voucher
												</div>                         
											</div>
										</div>
										</a>
										<a data-url="VoucherEntrySD">
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													SD Release Voucher
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
									<div class="card" style="margin-top:2px;">
										<div class="face-static2">
											<div class="card-header inkblue-card">Works Voucher Entry<span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1">
												<div class="taxrow row">
													<div class="row smclearrow"></div>
													<div class="div12 smlboxlabel">
														<span class="lboxlabel">CCNO</span>
														<input type="text" name="txt_ccno" id="txt_ccno" class="statictboxsm bordd2" value="">
														<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
														&emsp;
														<span class="lboxlabel">SNo.</span>
														<input type="text" name="txt_sr_no" id="txt_sr_no" class="statictboxsm">
														&emsp;
														<span class="lboxlabel">RAB</span>
														<input type="text" name="txt_rbn" id="txt_rbn" class="statictboxsm" readonly="">
														
														<!--<span class="spanhead">Bill For</span>
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_rab" class="" value="" readonly=""> 
														<span class="lboxlabel">RAB</span> &nbsp;&nbsp;
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_fbill" class="" value="" readonly=""> 
														<span class="lboxlabel">Final Bill </span> &nbsp;
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_sa" class="" value="" readonly=""> 
														<span class="lboxlabel">Sec. Adv.</span> &nbsp;
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_mob" class="" value="" readonly=""> 
														<span class="lboxlabel">Mob. Adv.</span> &nbsp;
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_esc" class="" value="" readonly="">  
														<span class="lboxlabel">Escalation</span> &nbsp;
														<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_oth" class="" value="" readonly=""> 
														<span class="lboxlabel">Others</span>-->
																
													</div>
													<div class="row clearrow"></div>
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Work & Contractor Details</span></div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Work Name</div>
																<div class="div10" align="left"><input type="text" name="txt_work_name" id="txt_work_name" class="tboxsmclass bordd1" value=""></div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Contractor Name</div>
																<div class="div4" align="left">
																	<!--<select name="cmb_contractor" id="cmb_contractor" class="tboxsmclass bordd2">
																		<option value=""> -- Select --</option>
																		<?php echo $objBind->BindCont(0); ?>
																	</select>-->
																	<input type="text" name="txt_contractor" id="txt_contractor" class="tboxsmclass bordd1" readonly="" required />
																	<input type="hidden" name="txt_contid" id="txt_contid" class="tboxsmclass bordd1" readonly="" />
																	<input type="hidden" name="txt_sheetid" id="txt_sheetid" class="tboxsmclass bordd1" readonly="" />
																	<input type="hidden" name="txt_globid" id="txt_globid" class="tboxsmclass bordd1" readonly="" />
																	<input type="hidden" name="txt_unitid" id="txt_unitid" class="tboxsmclass bordd1" readonly="" value="6" />
																	<input type="hidden" name="txt_pinno" id="txt_pinno" class="tboxsmclass bordd1" readonly="" value="712" />
																</div>
																<div class="div1 rboxlabel">PAN No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_pan_no" id="txt_pan_no" class="tboxsmclass bordd1" readonly="" required />
																</div>
																<div class="div1 rboxlabel">GST No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_gst_no" id="txt_gst_no" class="tboxsmclass bordd1" readonly="" required />
																</div>
																<div class="row clearrow"></div>
																<!--<div class="div2 lboxlabel">Account No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_bank_acc" id="txt_bank_acc" class="tboxsmclass" required />
																</div>
																<div class="div1 rboxlabel">Bank Name&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass" required />
																</div>
																<div class="div1 rboxlabel">Branch &nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_branch" id="txt_branch" class="tboxsmclass" required />
																</div>
																<div class="div1 rboxlabel">IFSC Code&nbsp;</div>
																<div class="div1" align="left">
																	<input type="text" name="txt_ifsc" id="txt_ifsc" class="tboxsmclass" required />
																</div>
																<div class="row smclearrow"></div>-->
															</div>
														</div>
													</div>  
													
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead-o">Bill Details</span> <i class="fa fa-search ptr DtData" id="BD" style="font-size:16px"></i></div>
																<div class="row clearrow"></div>
																<div class="div3">
																	<span class="lboxlabel">Net Total Amt.</span>
																	<input type="text" name="txt_bill_value" id="txt_bill_value" class="statictboxmd" readonly="" required />
																</div>
																<div class="div3">
																	<span class="lboxlabel">Total Recovery</span>
																	<input type="text" name="txt_total_rec" id="txt_total_rec" class="statictboxmd" readonly="" required />
																</div>
																<div class="div3">
																	<span class="lboxlabel">Bill Amt. for GST</span>
																	<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="statictboxmd" readonly="" required />
																</div>
																<div class="div3">
																	<span class="lboxlabel">Bill Amt. for IT&emsp;</span>
																	<input type="text" name="txt_bill_amt_it" id="txt_bill_amt_it" class="statictboxmd" readonly="" required />
																</div>
																<!--<span class="lboxlabel">Bill For</span>-->
																<div class="row clearrow"></div>
															</div>
														</div>
													</div>
													
													<!--<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Recovery Details</span> <i class="fa fa-search ptr DtData" id="RD" style="font-size:16px"></i></div>
																<div class="div12" id="RecData"></div>
															</div>
														</div>
													</div>-->
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4 bsh2">
																<div class="smheadclearrow"><span class="spanhead-o">Voucher Details</span></div>
																<div class="row clearrow"></div>
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
																<div class="row clearrow"></div>
															</div>
														</div>
													</div>
													<div class="row clearrow"></div>
													<div class="row clearrow"></div>
													<div class="div12 cboxlabel"><input type="submit" name="btnSave" id="btnSave" class="gbtn" value=" Submit "></div>
													<div class="row smclearrow"></div>
													<!--<div class="div6">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Payment Details</span></div>
																<div class="div12" id="VochData"></div>
															</div>
														</div>
													</div>
													<div class="div6">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Voucher Details</span></div>
																
															</div>
														</div>
													</div>-->
													                       
												</div>
											</div>
										</div>
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
<script>

$('#cmb_miscell').chosen();
$('#cmb_contractor').chosen();
$(document).ready(function(){
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			if(success == 1){
				BootstrapDialog.alert(msg);
			}else{
				BootstrapDialog.alert(msg);
			}
				
		}
	}
	var KillEvent = 0;
	$("body").on("click","#btnSave", function(event){
		if(KillEvent == 0){
			var VouchCcno   = $("#txt_ccno").val();
			var VouchRbn    = $("#txt_rbn").val();
			var VouchSno   	= $("#txt_sr_no").val();
			var VouchNo 	= $("#txt_vouch_no").val();
			var VouchDate	= $("#txt_vouch_dt").val();
			var VouchAmt	= $("#txt_vouch_amt").val();
			var VouchHoa	= $("#txt_vouch_hoa").val();
			if(VouchCcno == ""){
				BootstrapDialog.alert("Please enter CCNo.");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchRbn == ""){
				BootstrapDialog.alert("RAB should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchSno == ""){
				BootstrapDialog.alert("Serial number should not be empty");
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
	$('body').on("click","#btnGo", function(event){
		var Ccno = $("#txt_ccno").val();
		//$("#btnSave").show();
		//$("#btnReset").show();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindVoucherData.php', 
			data: { Ccno: Ccno, PageCode: 'ACC' }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var WData 	 = data['WData'];
					var RABData  = data['RABData'];
					var RECData  = data['RECData'];
					var CONTData = data['CONTData'];
					var BKData   = data['BKData'];
					var StatusData  = data['StatusData'];
					
					var BillErr = 0;
					if((RABData != null)&&((RABData['rbn'] == null) || (RABData['rbn'] == ''))){
						BootstrapDialog.alert("Voucher process is already completed / No RAB to create voucher");
						BillErr = 1;
					}
					if((StatusData != null)&&(BillErr == 0)){
						if(StatusData['bill_vouch_status'] == "Y"){
							BootstrapDialog.alert("Voucher process is already completed.");
							BillErr = 1;
						}else if(StatusData['bill_payord_status'] != "Y"){
							BootstrapDialog.alert("Pay Order process is not yet completed. You can't create voucher.");
							BillErr = 1;
						}else if(StatusData['bill_pasord_status'] != "Y"){
							BootstrapDialog.alert("Pass Order process is not yet completed. You can't create voucher.");
							BillErr = 1;
						}else if(StatusData['bill_ret_status'] == "Y"){
							BootstrapDialog.alert("Bill is returned back to EIC. You can't create voucher.");
							BillErr = 1;
						}else if(StatusData['bill_level_flag'] == "H"){
							BootstrapDialog.alert("Bill is forwarded to next checking level. You can't create voucher.");
							BillErr = 1;
						}else if(StatusData['bill_level_flag'] == "L"){
							BootstrapDialog.alert("Bill is still waiting in previous checking level. You can't create voucher.");
							BillErr = 1;
						}
					}

					if(BillErr == 1){
						//$("#btnSave").hide();
						//$("#btnReset").hide();
					}else{
						//var TableStr = '<table class="table table-bordered rectable">';
						if(WData != null){
							$("#txt_work_name").val(WData['work_name']);
							$("#txt_vouch_hoa").val(WData['hoa_no']);
							$("#txt_sheetid").val(WData['sheet_id']);
							$("#txt_globid").val(WData['globid']);
						}
						if(CONTData != null){
							$("#txt_pan_no").val(CONTData['pan_no']);
							$("#txt_gst_no").val(CONTData['gst_no']);
							//$("#cmb_contractor").chosen('destroy');
							//$("#cmb_contractor").val(CONTData['contid']);
							//$("#cmb_contractor").chosen();
							$("#txt_contractor").val(CONTData['name_contractor']);
							$("#txt_contid").val(CONTData['contid']);
						}
						if(BKData != null){
							$.each(BKData, function(index, element) { 
								$("#txt_bank_acc").val(element.bank_acc_no);
								$("#txt_bank_name").val(element.bank_name);
								$("#txt_branch").val(element.branch_address);
								$("#txt_ifsc").val(element.ifsc_code);
							});
						}
						if(RABData != null){
							$("#txt_rbn").val(RABData['rbn']);
							$("#txt_bill_amt_gst").val(RABData['bill_amt_for_gst']);
							$("#txt_bill_amt_it").val(RABData['this_bill_val']);
							$("#txt_bill_value").val(RABData['this_bill_val']); 
							/*if(RABData['is_rab'] == "Y"){ $("#rad_rab_for_rab").prop("checked",true); }
							if(RABData['is_final_bill'] == "Y"){ $("#rad_rab_for_fbill").prop("checked",true); }
							if(RABData['is_sec_adv'] == "Y"){ $("#rad_rab_for_sa").prop("checked",true); }
							if(RABData['is_mob_adc'] == "Y"){ $("#rad_rab_for_mob").prop("checked",true); }
							if(RABData['is_esc'] == "Y"){ $("#rad_rab_for_esc").prop("checked",true); }*/
							//TableStr += '<tr><td class="smlboxlabel" colspan="2">Upto Date Value</td><td class="smrboxlabel" align="right">'+RABData['upto_date_total_amount']+'</td></tr>';
							//TableStr += '<tr><td class="smlboxlabel" colspan="2">Deduct Previous Payment</td><td class="smrboxlabel" align="right">'+RABData['dpm_total_amount']+'</td></tr>';
							//TableStr += '<tr><td class="smlboxlabel" colspan="2">This Bill Value</td><td class="smrboxlabel" align="right">'+RABData['slm_total_amount']+'</td></tr>';
							if(RABData['secured_adv_amt'] != 0){
								//TableStr += '<tr><td class="smlboxlabel" colspan="2">Add/Deduct Secured Advance</td><td class="smrboxlabel" align="right">'+RABData['secured_adv_amt']+'</td></tr>';
							}
							if(RABData['mob_adv_amt'] != 0){
								//TableStr += '<tr><td class="smlboxlabel" colspan="2">Mobilization Advance</td><td class="smrboxlabel" align="right">'+RABData['mob_adv_amt']+'</td></tr>';
							}
							if(RABData['slm_total_amount_esc'] > 0){
								//TableStr += '<tr><td class="smlboxlabel" colspan="2">Escalation</td><td class="smrboxlabel" align="right">'+RABData['slm_total_amount_esc']+'</td></tr>';
							}
							var NetAmount = Number(RABData['slm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc']);
							NetAmount = NetAmount.toFixed(2);
							//TableStr += '<tr><td class="rboxlabel" colspan="2">Net Total</td><td class="rboxlabel" align="right">'+NetAmount+'</td></tr>';
						}
						var TotalRec = 0;
						if(RECData != null){
							//var PATableStr = '<span class="spanhead">Part A </span>&emsp;';
							//var PBTableStr = '<span class="spanhead">Part B </span>&emsp;';
							$("#txt_lcess_perc").val(RECData['lw_cess_percent']);
							$("#txt_lcess_amt").val(RECData['lw_cess_amt']);
							$("#txt_mobadv_amt").val(RECData['mob_adv_amt']);
							$("#txt_plmach_amt").val(RECData['pl_mac_adv_amt']); 
							if(RECData['lw_cess_amt'] != 0){
								//TableStr += '<tr><td class="smlboxlabel">LCess</td><td class="smrboxlabel" align="right">'+RECData['lw_cess_amt']+'</td><td>&nbsp;</td></tr>';
								//PATableStr += '<span class="lboxlabel">LCess &emsp;&emsp;&nbsp;</span><input type="text" name="txt_lw_cess_amt" id="txt_lw_cess_amt" class="statictboxn" value="'+RECData['lw_cess_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['lw_cess_amt']);
								//console.log("A = "+TotalRec);
							}
							if(RECData['mob_adv_amt'] != 0){
								//TableStr += '<tr><td class="smlboxlabel">Mobiliztion Advance</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_amt']+'</td><td>&nbsp;</td></tr>';
								//PATableStr += '<span class="lboxlabel">Mob. Adv. &emsp;&nbsp;</span><input type="text" name="txt_mob_adv_amt" id="txt_mob_adv_amt" class="statictboxn" value="'+RECData['mob_adv_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['mob_adv_amt']);
								//console.log("B = "+TotalRec);
							}
							if(RECData['pl_mac_adv_amt'] != 0){
								//TableStr += '<tr><td class="smlboxlabel">P&M Advance</td><td class="smrboxlabel" align="right">'+RECData['pl_mac_adv_amt']+'</td><td>&nbsp;</td></tr>';
								//PATableStr += '<span class="lboxlabel">P&M Adv. </span><input type="text" name="txt_pl_mac_adv_amt" id="txt_pl_mac_adv_amt" class="statictboxn" value="'+RECData['pl_mac_adv_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['pl_mac_adv_amt']);
								//console.log("C = "+TotalRec);
							}
							var HireCharge = 0;
							if(HireCharge != 0){
								//TableStr += '<tr><td class="smlboxlabel">Hire Charges</td><td class="smrboxlabel" align="right">'+HireCharge+'</td></tr>';
								//PATableStr += '<span class="lboxlabel">Hire Charges</span><input type="text" name="txt_hire_charge" id="txt_hire_charge" class="statictboxn" value="'+HireCharge+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(HireCharge);
								//console.log("D = "+TotalRec);
							}
							//$("#txt_hcharge_amt").val(''); 
							var OthRecAmt = 0;
							if((RECData['other_recovery_1_amt'] != 0)&&(RECData['other_recovery_1_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_1_amt'])
							}
							if((RECData['other_recovery_2_amt'] != 0)&&(RECData['other_recovery_2_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_2_amt'])
							}
							if((RECData['other_recovery_3_amt'] != 0)&&(RECData['other_recovery_3_amt'] != null)){
								OthRecAmt = Number(OthRecAmt) + Number(RECData['other_recovery_3_amt'])
							}
							//var OthRecAmt = Number(RECData['other_recovery_1_amt']) + Number(RECData['other_recovery_2_amt'])+ Number(RECData['other_recovery_3_amt']);
							//console.log("E1 = "+OthRecAmt);
							if(OthRecAmt != 0){
								$("#txt_oth_rec_amt").val(OthRecAmt); 
								//TableStr += '<tr><td class="smlboxlabel">Other Recoveries</td><td class="smrboxlabel" align="right">'+OthRecAmt+'</td><td>&nbsp;</td></tr>';
								//PATableStr += '<span class="lboxlabel">Oth. Rec. </span><input type="text" name="txt_oth_rec" id="txt_oth_rec" class="statictboxn" value="'+OthRecAmt+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(OthRecAmt);
								//console.log("E = "+TotalRec);
							}
							//TableStr += '<tr><td class="smlboxlabel" colspan="3"><span class="spanhead">Recoveries : Part B</span></td></tr>';
							//$("#txt_it_perc").val(RECData['incometax_percent']); 
							if((RECData['incometax_amt'] != 0)&&(RECData['incometax_amt'] != null)&&(RECData['incometax_amt'] != '')){
								$("#ch_is_it").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">IT</td><td class="smrboxlabel" align="right">'+RECData['incometax_amt']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">IT </span><input type="text" name="txt_it_perc" id="txt_it_perc" class="statictminibox" value="2" /> (%) <input type="text" name="txt_incometax_amt" id="txt_incometax_amt" class="statictboxn" value="'+RECData['incometax_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['incometax_amt']);
								//console.log("F = "+TotalRec);
							}
							//$("#txt_it_amt").val(RECData['incometax_amt']); 
							//$("#txt_cgst_tds_perc").val(RECData['cgst_tds_perc']); 
							//$("#txt_cgst_tds_amt").val(RECData['cgst_tds_amt']); 
							if((RECData['cgst_tds_amt'] != 0)&&(RECData['cgst_tds_amt'] != null)&&(RECData['cgst_tds_amt'] != '')){
								$("#ch_is_cgst").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">CGST</td><td class="smrboxlabel" align="right">'+RECData['cgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">CGST </span><input type="text" name="txt_cgst_tds_perc" id="txt_cgst_tds_perc" class="statictminibox" value="1" /> (%)<input type="text" name="txt_cgst_tds_amt" id="txt_cgst_tds_amt" class="statictboxn" value="'+RECData['cgst_tds_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['cgst_tds_amt']);
								//console.log("G = "+TotalRec);
							}
							//$("#txt_sgst_tds_perc").val(RECData['sgst_tds_perc']); 
							//$("#txt_sgst_tds_amt").val(RECData['sgst_tds_amt']); 
							if((RECData['sgst_tds_amt'] != 0)&&(RECData['sgst_tds_amt'] != null)&&(RECData['sgst_tds_amt'] != '')){
								$("#ch_is_sgst").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">SGST</td><td class="smrboxlabel" align="right">'+RECData['sgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">SGST </span><input type="text" name="txt_sgst_tds_perc" id="txt_sgst_tds_perc" class="statictminibox" value="1" /> (%)<input type="text" name="txt_sgst_tds_amt" id="txt_sgst_tds_amt" class="statictboxn" value="'+RECData['sgst_tds_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['sgst_tds_amt']);
								//console.log("H = "+TotalRec);
							}
							//$("#txt_sd_perc").val(RECData['sd_percent']); 
							//$("#txt_sd_amt").val(RECData['sd_amt']); 
							if((RECData['sd_amt'] != 0)&&(RECData['sd_amt'] != null)&&(RECData['sd_amt'] != '')){
								$("#ch_is_sd").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">SD</td><td class="smrboxlabel" align="right">'+RECData['sd_amt']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">SD </span><input type="text" name="txt_sd_amt" id="txt_sd_amt" class="statictboxn" value="'+RECData['sd_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['sd_amt']);
								//console.log("I = "+TotalRec);
							}
							
							//$("#txt_elec_charge").val(RECData['electricity_cost']); 
							if((RECData['electricity_cost'] != 0)&&(RECData['electricity_cost'] != null)&&(RECData['electricity_cost'] != '')){
								$("#ch_is_ec").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">Electricity Cost</td><td class="smrboxlabel" align="right">'+RECData['electricity_cost']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">Electricity Cost </span><input type="text" name="txt_elec_charge" id="txt_elec_charge" class="statictboxn" value="'+RECData['electricity_cost']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['electricity_cost']);
								//console.log("J = "+TotalRec);
							}
							//$("#txt_water_ch_amt").val(RECData['water_cost']); 
							if((RECData['water_cost'] != 0)&&(RECData['water_cost'] != null)&&(RECData['water_cost'] != '')){
								$("#ch_is_wc").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">Water Cost</td><td class="smrboxlabel" align="right">'+RECData['water_cost']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">Water Cost </span><input type="text" name="txt_water_ch_amt" id="txt_water_ch_amt" class="statictboxn" value="'+RECData['water_cost']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['water_cost']);
								//console.log("K = "+TotalRec);
							}
							//$("#txt_mob_adv_int_amt").val(RECData['mob_adv_int_amt']); 
							if((RECData['mob_adv_int_amt'] != 0)&&(RECData['mob_adv_int_amt'] != null)&&(RECData['mob_adv_int_amt'] != '')){
								$("#ch_is_mob_int").prop("checked",true);
								//TableStr += '<tr><td class="smlboxlabel">Mob.Adv. Interest</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_int_amt']+'</td><td>&nbsp;</td></tr>';
								//PBTableStr += '<span class="lboxlabel">Mob. Adv. </span><input type="text" name="txt_mob_adv_int_amt" id="txt_mob_adv_int_amt" class="statictboxn" value="'+RECData['mob_adv_int_amt']+'" />&emsp;';
								TotalRec = Number(TotalRec) + Number(RECData['mob_adv_int_amt']);
								//console.log("L = "+TotalRec);
							}
							//$("#txt_plmach_int_amt").val(''); 
							//TableStr += '<tr><td class="rboxlabel"c colspan="2">Total Recovery</td><td class="rboxlabel">'+TotalRec+'</td></tr>';
							//if((RECData['txt_plmach_int_amt'] != 0)&&(RECData['txt_plmach_int_amt'] != null)&&(RECData['txt_plmach_int_amt'] != '')){
								//$("#ch_is_pm_int").prop("checked",true);
							//}
						}
						$("#txt_total_rec").val(TotalRec);
						//console.log("M = "+TotalRec);
						var NetPayable = Number(NetAmount) - Number(TotalRec);
						//TableStr += '<tr><td class="rboxlabel"c colspan="2">Net payable</td><td class="rboxlabel">'+NetPayable.toFixed(2)+'</td></tr>';
						//TableStr += '</table>';
						//$('#PartARec').html(PATableStr);
						//$('#PartBRec').html(PBTableStr);
						/*$.each(data, function(index, element) { 
							$("#txt_ccno").val(element.computer_code_no);
							$("#txt_sr_no").val(element.bill_serial_no);
							$("#txt_work_name").val(element.short_name);
							$("#txt_rab").val(element.rbn);
							$("#cmb_sent_by").val(element.sent_by);
							$("#txt_sent_on").val(element.sent_on);
							$("#txt_sheetid").val(element.sheet_id);
						});*/
					}
				}
			}
		});
	});
	
	$('body').on("click",".DtData", function(event){
		var Ccno = $("#txt_ccno").val();
		var Id = $(this).attr('id');
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindVoucherData.php', 
			data: { Ccno: Ccno }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var WData 	 = data['WData'];
					var RABData  = data['RABData'];
					var RECData  = data['RECData'];
					var CONTData = data['CONTData'];
					var BKData   = data['BKData'];
					var TableStr = '<table class="table table-bordered rectable">';
					if(RABData != null){
						TableStr += '<tr><td class="smlboxlabel" colspan="2">Upto Date Value</td><td class="smrboxlabel" align="right">'+RABData['upto_date_total_amount']+'</td></tr>';
						TableStr += '<tr><td class="smlboxlabel" colspan="2">Deduct Previous Payment</td><td class="smrboxlabel" align="right">'+RABData['dpm_total_amount']+'</td></tr>';
						TableStr += '<tr><td class="smlboxlabel" colspan="2">This Bill Value</td><td class="smrboxlabel" align="right">'+RABData['slm_total_amount']+'</td></tr>';
						if(RABData['secured_adv_amt'] != 0){
							TableStr += '<tr><td class="smlboxlabel" colspan="2">Add/Deduct Secured Advance</td><td class="smrboxlabel" align="right">'+RABData['secured_adv_amt']+'</td></tr>';
						}
						if(RABData['mob_adv_amt'] != 0){
							TableStr += '<tr><td class="smlboxlabel" colspan="2">Mobilization Advance</td><td class="smrboxlabel" align="right">'+RABData['mob_adv_amt']+'</td></tr>';
						}
						if(RABData['slm_total_amount_esc'] > 0){
							TableStr += '<tr><td class="smlboxlabel" colspan="2">Escalation</td><td class="smrboxlabel" align="right">'+RABData['slm_total_amount_esc']+'</td></tr>';
						}
						var NetAmount = Number(RABData['slm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc']);
						NetAmount = NetAmount.toFixed(2);
						TableStr += '<tr><td class="rboxlabel" colspan="2">Net Total</td><td class="rboxlabel" align="right">'+NetAmount+'</td></tr>';
					}
					var TotalRec = 0;
					if(RECData != null){
						TableStr += '<tr><td class="smlboxlabel" colspan="3"><span class="spanhead">Recoveries : Part A</span></td></tr>';
						if(RECData['lw_cess_amt'] != 0){
							TableStr += '<tr><td class="smlboxlabel">LCess</td><td class="smrboxlabel" align="right">'+RECData['lw_cess_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['lw_cess_amt']);
						}
						if(RECData['mob_adv_amt'] != 0){
							TableStr += '<tr><td class="smlboxlabel">Mobiliztion Advance</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['mob_adv_amt']);
						}
						if(RECData['pl_mac_adv_amt'] != 0){
							TableStr += '<tr><td class="smlboxlabel">P&M Advance</td><td class="smrboxlabel" align="right">'+RECData['pl_mac_adv_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['pl_mac_adv_amt']);
						}
						var HireCharge = 0;
						if(HireCharge != 0){
							//TableStr += '<tr><td class="smlboxlabel">Hire Charges</td><td class="smrboxlabel" align="right">'+HireCharge+'</td></tr>';
							TotalRec = Number(TotalRec) + Number(HireCharge);
						}
						var OthRecAmt = Number(RECData['other_recovery_1_amt']) + Number(RECData['other_recovery_2_amt'])+ Number(RECData['other_recovery_3_amt']);
						if(OthRecAmt != 0){
							TableStr += '<tr><td class="smlboxlabel">Other Recoveries</td><td class="smrboxlabel" align="right">'+OthRecAmt+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(OthRecAmt);
						}
						TableStr += '<tr><td class="smlboxlabel" colspan="3"><span class="spanhead">Recoveries : Part B</span></td></tr>';
						if((RECData['incometax_amt'] != 0)&&(RECData['incometax_amt'] != null)&&(RECData['incometax_amt'] != '')){
							TableStr += '<tr><td class="smlboxlabel">IT</td><td class="smrboxlabel" align="right">'+RECData['incometax_amt']+'</td><td>&nbsp;</td></tr>';
							//PBTableStr += '<span class="lboxlabel">IT </span><input type="text" name="txt_it_perc" id="txt_it_perc" class="statictminibox" value="2" /> (%) <input type="text" name="txt_incometax_amt" id="txt_incometax_amt" class="statictboxn" value="'+RECData['incometax_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['incometax_amt']);
						}
						if((RECData['cgst_tds_amt'] != 0)&&(RECData['cgst_tds_amt'] != null)&&(RECData['cgst_tds_amt'] != '')){
							TableStr += '<tr><td class="smlboxlabel">CGST</td><td class="smrboxlabel" align="right">'+RECData['cgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['cgst_tds_amt']);

						}
						if((RECData['sgst_tds_amt'] != 0)&&(RECData['sgst_tds_amt'] != null)&&(RECData['sgst_tds_amt'] != '')){
							TableStr += '<tr><td class="smlboxlabel">SGST</td><td class="smrboxlabel" align="right">'+RECData['sgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['sgst_tds_amt']);
						}
						if((RECData['sd_amt'] != 0)&&(RECData['sd_amt'] != null)&&(RECData['sd_amt'] != '')){
							TableStr += '<tr><td class="smlboxlabel">SD</td><td class="smrboxlabel" align="right">'+RECData['sd_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['sd_amt']);
						}
						
						if((RECData['electricity_cost'] != 0)&&(RECData['electricity_cost'] != null)&&(RECData['electricity_cost'] != '')){
							TableStr += '<tr><td class="smlboxlabel">Electricity Cost</td><td class="smrboxlabel" align="right">'+RECData['electricity_cost']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['electricity_cost']);
						}
						if((RECData['water_cost'] != 0)&&(RECData['water_cost'] != null)&&(RECData['water_cost'] != '')){
							$("#ch_is_wc").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">Water Cost</td><td class="smrboxlabel" align="right">'+RECData['water_cost']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['water_cost']);
						}
						if((RECData['mob_adv_int_amt'] != 0)&&(RECData['mob_adv_int_amt'] != null)&&(RECData['mob_adv_int_amt'] != '')){
							$("#ch_is_mob_int").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">Mob.Adv. Interest</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_int_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['mob_adv_int_amt']);
						}
						TableStr += '<tr><td class="rboxlabel"c colspan="2">Total Recovery</td><td class="rboxlabel">'+TotalRec+'</td></tr>';
					}
					var NetPayable = Number(NetAmount) - Number(TotalRec);
					TableStr += '<tr><td class="rboxlabel"c colspan="2">Net payable</td><td class="rboxlabel">'+NetPayable.toFixed(2)+'</td></tr>';
					TableStr += '</table>';
					//$('#PartARec').html(PATableStr);
					BootstrapDialog.show({
						title: 'Bill Details',
						message: TableStr
					});
				}
			}
		});
	});
	
	
});
</script>
</body>
</html>

