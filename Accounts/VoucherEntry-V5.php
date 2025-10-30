<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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
if(isset($_POST['submit'])){
	$SaveUnit 		= $_POST['cmb_unit'];
	$SaveSheetId 	= $_POST['cmb_work_no'];
	$SaveVouchDate 	= dt_format($_POST['txt_vr_date']);
	$SaveVouchNo 	= $_POST['txt_vr_no'];
	$SaveVouchAmt 	= $_POST['txt_vr_amt'];
	$SavePinNo 		= $_POST['cmb_pin_no'];
	$SaveHoa 		= $_POST['cmb_hoa'];
	$InsertQuery = "insert into voucher_upt set sheetid = '$SaveSheetId', unitid = '$SaveUnit', wo = '', item = '', wo_amt = '', vr_no = '$SaveVouchNo', vr_dt = '$SaveVouchDate', 
	vr_amt = '$SaveVouchAmt', wo_dt = '', o_pin = '$SavePinNo', n_pin = '$SavePinNo', code = '',
	paid_amt = '', hoa = '$SaveHoa', new_hoa = '$SaveHoa', indentor = '', eic = '', grp = '', div = '', sec = '', grp_div_sec = '', plant_serv = '', sanct_om_act_sno = '', 
	sanct_om_nwme_sno = '', sanct_act_id = '', createdon = NOW(), staffid = '', userid = '', entry_flag = 'MAN'";
	$InsertSql 	= mysqli_query($dbConn,$InsertQuery);
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
	height:20px;
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
													Miscellaneous
												</div>                         
											</div>
										</div>
										</a>
										<div class="face-static tabbtn">
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
										</div>
										<div class="face-static tabbtn">
											<div class="card-body padding-1">
												<div class="row">
													Labour Cess	
												</div>                         
											</div>
										</div>
									</div>
								</div>
								
								
								<div class="div10">
									<div class="card" style="margin-top:2px;">
										<div class="face-static2">
											<div class="card-header sgreen-card">Works Voucher Entry<span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1">
												<div class="taxrow row">
													<div class="row smclearrow"></div>
													<div class="div12 smlboxlabel">
														<span class="lboxlabel">CCNO</span>
														<input type="text" name="txt_ccno" id="txt_ccno" class="statictboxsm" value="">
														<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
														&emsp;
														<span class="lboxlabel">SNo.</span>
														<input type="text" name="txt_sr_no" id="txt_sr_no" class="statictboxsm" readonly="">
														&emsp;
														<span class="lboxlabel">RAB</span>
														<input type="text" name="txt_rbn" id="txt_rbn" class="statictboxsm" readonly="">
													</div>
													<div class="row smclearrow"></div>
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Work & Contractor Details</span></div>
																<div class="div2 lboxlabel">Work Name</div>
																<div class="div10" align="left"><input type="text" name="txt_work_name" id="txt_work_name" class="tboxsmclass" value=""></div>
																
																<div class="row smclearrow"></div>
																<div class="div2 lboxlabel">Contractor Name</div>
																<div class="div4" align="left">
																	<select name="cmb_contractor" id="cmb_contractor" class="tboxsmclass">
																		<option value=""> -- Select --</option>
																		<?php echo $objBind->BindCont(0); ?>
																	</select>
																</div>
																<div class="div1 rboxlabel">PAN No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_pan_no" id="txt_pan_no" class="tboxsmclass" required />
																</div>
																<div class="div1 rboxlabel">GST No.&nbsp;</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_gst_no" id="txt_gst_no" class="tboxsmclass" required />
																</div>
																<div class="row smclearrow"></div>
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
																<div class="smheadclearrow"><span class="spanhead">Bill Details</span> <i class="fa fa-search ptr DtData" id="BD" style="font-size:16px"></i></div>
																<span class="lboxlabel">Net Total</span>
																<input type="text" name="txt_bill_value" id="txt_bill_value" class="statictboxn" required />
																&emsp;
																<span class="lboxlabel">Bill Amt. for GST</span>
																<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="statictboxn" required />
																&emsp;
																<!--<span class="lboxlabel">Bill For</span>-->
																<span class="spanhead">Bill For</span>
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_rab" class="" value=""> 
																<span class="lboxlabel">RAB</span> &nbsp;&nbsp;
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_fbill" class="" value=""> 
																<span class="lboxlabel">Final Bill </span> &nbsp;
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_sa" class="" value=""> 
																<span class="lboxlabel">Sec. Adv.</span> &nbsp;
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_mob" class="" value=""> 
																<span class="lboxlabel">Mob. Adv.</span> &nbsp;
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_esc" class="" value="">  
																<span class="lboxlabel">Escalation</span> &nbsp;
																<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_oth" class="" value=""> 
																<span class="lboxlabel">Others</span>
															</div>
														</div>
													</div>
													
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Recovery Details</span> <i class="fa fa-search ptr DtData" id="RD" style="font-size:16px"></i></div>
																<div class="div12" id="RecData"></div>
															</div>
														</div>
													</div>
													<div class="div12">
														<div class="card" style="margin:2px 0px 0px 2px;">
															<div class="face-static pd4">
																<div class="smheadclearrow"><span class="spanhead">Voucher Details</span></div>
																<div class="row smclearrow"></div>
																<div class="div12 smlboxlabel">
																	<span class="lboxlabel">Voucher No. </span>
																	<input type="text" name="txt_vouch_no" id="txt_vouch_no" class="statictboxsm" value="">
																	&emsp;
																	<span class="lboxlabel">Voucher Date</span>
																	<input type="text" name="txt_vouch_dt" id="txt_vouch_dt" class="statictbox" readonly="">
																	&emsp;
																	<span class="lboxlabel">Voucher Amount</span>
																	<input type="text" name="txt_vouch_amt" id="txt_vouch_amt" class="statictbox" readonly="">
																	&emsp;
																	<span class="lboxlabel">Head of Account</span>
																	<input type="text" name="txt_vouch_hoa" id="txt_vouch_hoa" class="statictbox" readonly="">
																</div>
																<div class="row smclearrow"></div>
																<div class="row smclearrow"></div>
																<div class="div12 cboxlabel"><input type="button" name="btnSave" id="btnSave" class="gbtn" value=" Submit "></div>
																<div class="row smclearrow"></div>
															</div>
														</div>
													</div>
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
	$('body').on("click","#btnGo", function(event){
		var Ccno = $("#txt_ccno").val();
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
					//var TableStr = '<table class="table table-bordered rectable">';
					if(WData != null){
						$("#txt_work_name").val(WData['work_name']);
						$("#txt_vouch_hoa").val('[72-6001]');//(WData['hoa_no']);
					}
					if(CONTData != null){
						$("#txt_pan_no").val(CONTData['pan_no']);
						$("#txt_gst_no").val(CONTData['gst_no']);
						$("#cmb_contractor").chosen('destroy');
						$("#cmb_contractor").val(CONTData['contid']);
						$("#cmb_contractor").chosen();
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
						if(RABData['is_rab'] == "Y"){ $("#rad_rab_for_rab").prop("checked",true); }
						if(RABData['is_final_bill'] == "Y"){ $("#rad_rab_for_fbill").prop("checked",true); }
						if(RABData['is_sec_adv'] == "Y"){ $("#rad_rab_for_sa").prop("checked",true); }
						if(RABData['is_mob_adc'] == "Y"){ $("#rad_rab_for_mob").prop("checked",true); }
						if(RABData['is_esc'] == "Y"){ $("#rad_rab_for_esc").prop("checked",true); }
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
						var PATableStr = '<span class="spanhead">Part A </span>&emsp;';
						var PBTableStr = '<span class="spanhead">Part B </span>&emsp;';
						$("#txt_lcess_perc").val(RECData['lw_cess_percent']);
						$("#txt_lcess_amt").val(RECData['lw_cess_amt']);
						$("#txt_mobadv_amt").val(RECData['mob_adv_amt']);
						$("#txt_plmach_amt").val(RECData['pl_mac_adv_amt']); 
						if(RECData['lw_cess_amt'] != 0){
							//TableStr += '<tr><td class="smlboxlabel">LCess</td><td class="smrboxlabel" align="right">'+RECData['lw_cess_amt']+'</td><td>&nbsp;</td></tr>';
							PATableStr += '<span class="lboxlabel">LCess &emsp;&emsp;&nbsp;</span><input type="text" name="txt_lw_cess_amt" id="txt_lw_cess_amt" class="statictboxn" value="'+RECData['lw_cess_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['lw_cess_amt']);
						}
						if(RECData['mob_adv_amt'] != 0){
							//TableStr += '<tr><td class="smlboxlabel">Mobiliztion Advance</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_amt']+'</td><td>&nbsp;</td></tr>';
							PATableStr += '<span class="lboxlabel">Mob. Adv. &emsp;&nbsp;</span><input type="text" name="txt_mob_adv_amt" id="txt_mob_adv_amt" class="statictboxn" value="'+RECData['mob_adv_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['mob_adv_amt']);
						}
						if(RECData['pl_mac_adv_amt'] != 0){
							//TableStr += '<tr><td class="smlboxlabel">P&M Advance</td><td class="smrboxlabel" align="right">'+RECData['pl_mac_adv_amt']+'</td><td>&nbsp;</td></tr>';
							PATableStr += '<span class="lboxlabel">P&M Adv. </span><input type="text" name="txt_pl_mac_adv_amt" id="txt_pl_mac_adv_amt" class="statictboxn" value="'+RECData['pl_mac_adv_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['pl_mac_adv_amt']);
						}
						var HireCharge = 0;
						if(HireCharge != 0){
							//TableStr += '<tr><td class="smlboxlabel">Hire Charges</td><td class="smrboxlabel" align="right">'+HireCharge+'</td></tr>';
							//PATableStr += '<span class="lboxlabel">Hire Charges</span><input type="text" name="txt_hire_charge" id="txt_hire_charge" class="statictboxn" value="'+HireCharge+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(HireCharge);
						}
						//$("#txt_hcharge_amt").val(''); 
						var OthRecAmt = Number(RECData['other_recovery_1_amt']) + Number(RECData['other_recovery_2_amt'])+ Number(RECData['other_recovery_3_amt']);
						if(OthRecAmt != 0){
							$("#txt_oth_rec_amt").val(OthRecAmt); 
							//TableStr += '<tr><td class="smlboxlabel">Other Recoveries</td><td class="smrboxlabel" align="right">'+OthRecAmt+'</td><td>&nbsp;</td></tr>';
							PATableStr += '<span class="lboxlabel">Oth. Rec. </span><input type="text" name="txt_oth_rec" id="txt_oth_rec" class="statictboxn" value="'+OthRecAmt+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(OthRecAmt);
						}
						//TableStr += '<tr><td class="smlboxlabel" colspan="3"><span class="spanhead">Recoveries : Part B</span></td></tr>';
						//$("#txt_it_perc").val(RECData['incometax_percent']); 
						if((RECData['incometax_amt'] != 0)&&(RECData['incometax_amt'] != null)&&(RECData['incometax_amt'] != '')){
							$("#ch_is_it").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">IT</td><td class="smrboxlabel" align="right">'+RECData['incometax_amt']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">IT </span><input type="text" name="txt_it_perc" id="txt_it_perc" class="statictminibox" value="2" /> (%) <input type="text" name="txt_incometax_amt" id="txt_incometax_amt" class="statictboxn" value="'+RECData['incometax_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['incometax_amt']);
						}
						//$("#txt_it_amt").val(RECData['incometax_amt']); 
						//$("#txt_cgst_tds_perc").val(RECData['cgst_tds_perc']); 
						//$("#txt_cgst_tds_amt").val(RECData['cgst_tds_amt']); 
						if((RECData['cgst_tds_amt'] != 0)&&(RECData['cgst_tds_amt'] != null)&&(RECData['cgst_tds_amt'] != '')){
							$("#ch_is_cgst").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">CGST</td><td class="smrboxlabel" align="right">'+RECData['cgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">CGST </span><input type="text" name="txt_cgst_tds_perc" id="txt_cgst_tds_perc" class="statictminibox" value="1" /> (%)<input type="text" name="txt_cgst_tds_amt" id="txt_cgst_tds_amt" class="statictboxn" value="'+RECData['cgst_tds_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['cgst_tds_amt']);
						}
						//$("#txt_sgst_tds_perc").val(RECData['sgst_tds_perc']); 
						//$("#txt_sgst_tds_amt").val(RECData['sgst_tds_amt']); 
						if((RECData['sgst_tds_amt'] != 0)&&(RECData['sgst_tds_amt'] != null)&&(RECData['sgst_tds_amt'] != '')){
							$("#ch_is_sgst").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">SGST</td><td class="smrboxlabel" align="right">'+RECData['sgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">SGST </span><input type="text" name="txt_sgst_tds_perc" id="txt_sgst_tds_perc" class="statictminibox" value="1" /> (%)<input type="text" name="txt_sgst_tds_amt" id="txt_sgst_tds_amt" class="statictboxn" value="'+RECData['sgst_tds_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['sgst_tds_amt']);
						}
						//$("#txt_sd_perc").val(RECData['sd_percent']); 
						//$("#txt_sd_amt").val(RECData['sd_amt']); 
						if((RECData['sd_amt'] != 0)&&(RECData['sd_amt'] != null)&&(RECData['sd_amt'] != '')){
							$("#ch_is_sd").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">SD</td><td class="smrboxlabel" align="right">'+RECData['sd_amt']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">SD </span><input type="text" name="txt_sd_amt" id="txt_sd_amt" class="statictboxn" value="'+RECData['sd_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['sd_amt']);
						}
						
						//$("#txt_elec_charge").val(RECData['electricity_cost']); 
						if((RECData['electricity_cost'] != 0)&&(RECData['electricity_cost'] != null)&&(RECData['electricity_cost'] != '')){
							$("#ch_is_ec").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">Electricity Cost</td><td class="smrboxlabel" align="right">'+RECData['electricity_cost']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">Electricity Cost </span><input type="text" name="txt_elec_charge" id="txt_elec_charge" class="statictboxn" value="'+RECData['electricity_cost']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['electricity_cost']);
						}
						//$("#txt_water_ch_amt").val(RECData['water_cost']); 
						if((RECData['water_cost'] != 0)&&(RECData['water_cost'] != null)&&(RECData['water_cost'] != '')){
							$("#ch_is_wc").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">Water Cost</td><td class="smrboxlabel" align="right">'+RECData['water_cost']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">Water Cost </span><input type="text" name="txt_water_ch_amt" id="txt_water_ch_amt" class="statictboxn" value="'+RECData['water_cost']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['water_cost']);
						}
						//$("#txt_mob_adv_int_amt").val(RECData['mob_adv_int_amt']); 
						if((RECData['mob_adv_int_amt'] != 0)&&(RECData['mob_adv_int_amt'] != null)&&(RECData['mob_adv_int_amt'] != '')){
							$("#ch_is_mob_int").prop("checked",true);
							//TableStr += '<tr><td class="smlboxlabel">Mob.Adv. Interest</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_int_amt']+'</td><td>&nbsp;</td></tr>';
							PBTableStr += '<span class="lboxlabel">Mob. Adv. </span><input type="text" name="txt_mob_adv_int_amt" id="txt_mob_adv_int_amt" class="statictboxn" value="'+RECData['mob_adv_int_amt']+'" />&emsp;';
							TotalRec = Number(TotalRec) + Number(RECData['mob_adv_int_amt']);
						}
						//$("#txt_plmach_int_amt").val(''); 
						//TableStr += '<tr><td class="rboxlabel"c colspan="2">Total Recovery</td><td class="rboxlabel">'+TotalRec+'</td></tr>';
						//if((RECData['txt_plmach_int_amt'] != 0)&&(RECData['txt_plmach_int_amt'] != null)&&(RECData['txt_plmach_int_amt'] != '')){
							//$("#ch_is_pm_int").prop("checked",true);
						//}
					}
					var NetPayable = Number(NetAmount) - Number(TotalRec);
					//TableStr += '<tr><td class="rboxlabel"c colspan="2">Net payable</td><td class="rboxlabel">'+NetPayable.toFixed(2)+'</td></tr>';
					//TableStr += '</table>';
					$('#PartARec').html(PATableStr);
					$('#PartBRec').html(PBTableStr);
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

