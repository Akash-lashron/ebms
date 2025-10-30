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
<link rel='stylesheet' href='TabVizard/bootstrap.min.css'/>
<link rel='stylesheet' href='TabVizard/BSMagic-min.css'/>
<!--<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">-->
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

</style>
<style>
#mySidenav a{
	left:0px !important;
	width: 41px !important;
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
								<!--<div class="div8">
									<div class="card" style="margin-top:2px;">
										<div class="face-static">
											<div class="card-header inkblue-card">Voucher Entry</div>
											<div class="card-body padding-1">
												
											</div>
										</div>
									</div>
								</div>-->
								<div class="div12">
									<div class="row smclearrow"></div>
									<div class="">
										<div class="bd-example bd-example-tabs" id="JTab1">
											<div class="row">
												<div class="div2 tab-menu-sec">
													<div class="nav flex-column flex-grow-1 flex-fill nav-pills" role="tablist" style="height: 335px; pointer-events:none" aria-orientation="vertical">
														<a class="nav-link active BSNavTab" id="v-pills-application-type-tab" data-toggle="pill" href="#v-pills-application-type" role="tab" aria-controls="v-pills-application-type" aria-selected="true"><img src="images/Application.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Apply For</a>
														<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false"><img src="images/Applicant.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Applicant Detail</a>
														<a class="nav-link" id="v-pills-home2-tab" data-toggle="pill" href="#v-pills-home2" role="tab" aria-controls="v-pills-home2" aria-selected="false"><img src="images/Employment.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Employment Detail</a>
														<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><img src="images/Pay.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Pay Matrix</a>
														<a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><img src="images/Family.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Spouse / Family Info</a>
													</div>
												</div>
												<div class="div10 BSMagic" style="padding-left:15px;" id="test">
													<div class="tab-content" id="v-pills-tabContent">
														<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
															<div class="div-tab-head">Apply For</div>
															<div class="div-tab-body">
																<!--<div class="erow"></div>-->
																<div class="row">
														<div class="div4 lboxlabel">
															<div class="div6"><input type="radio" name="rad_vouch_type" id="rad_vouch_works" checked="checked" class="" value=""> Works (CCNO)</div> 
															<div class="div6"><input type="text" name="txt_ccno" id="txt_ccno" class="tboxsmclass" placeholder="Enter CCNO" value=""></div>
														</div>
														<div class="div4 lboxlabel">
															<div class="div6 cboxlabel"><input type="radio" name="rad_vouch_type" id="rad_vouch_miscell" class="" value=""> Miscellaneous</div> 
															<div class="div6">
																<select name="cmb_miscell" id="cmb_miscell" class="tboxsmclass">
																	<option value=""> -- Select --</option>
																	<option value="SALY">Salary </option>
																	<option value="PBILL">Phone Bill </option>
																	<option value="TRVL">Travel Expenses </option>
																</select>
															</div>
														</div>
														
														<div class="div2 rboxlabel">
															<div class="div6">Sr. No. &nbsp;&nbsp;</div>
															<div class="div6"><input type="text" name="txt_sr_no" id="txt_sr_no" class="tboxsmclass" readonly=""></div>
														</div>
														<div class="div2 rboxlabel">
															<div class="div6">RAB &nbsp;&nbsp;</div>
															<div class="div6"><input type="text" name="txt_rbn" id="txt_rbn" class="tboxsmclass" readonly=""></div>
														</div>
														<div class="row smclearrow"></div>
														
														
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
														
														<div class="div1 lboxlabel">Acc. No.&nbsp;</div>
														<div class="div2" align="left">
															<input type="text" name="txt_bank_acc" id="txt_bank_acc" class="tboxsmclass" required />
														</div>
														<div class="div1 rboxlabel">Bank &nbsp;</div>
														<div class="div2" align="left">
															<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass" required />
														</div>
														<div class="div1 rboxlabel">Branch&nbsp;</div>
														<div class="div2" align="left">
															<input type="text" name="txt_branch" id="txt_branch" class="tboxsmclass" required />
														</div>
														<div class="div1 rboxlabel">IFSC&nbsp;</div>
														<div class="div2" align="left">
															<input type="text" name="txt_ifsc" id="txt_ifsc" class="tboxsmclass" required />
														</div>
														<div class="row smclearrow"></div>
														<div class="div5 pd-lr-1" align="left">
															<div class="div12 rectaxbox">
																<div class="card-header inkblue-card">Bill Details</div>
																<div class="card-body rectaxbox-body">
																	<div class="div5 lboxlabel">Bill Value</div>
																	<div class="div7" align="left">
																		<input type="text" name="txt_bill_value" id="txt_bill_value" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Bill Amt. for IT</div>
																	<div class="div7" align="left">
																		<input type="text" name="txt_bill_amt_it" id="txt_bill_amt_it" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Bill Amt. for GST</div>
																	<div class="div7" align="left">
																		<input type="text" name="txt_bill_amt_gst" id="txt_bill_amt_gst" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Is Advance Pay ?</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="checkbox" name="ch_is_advance" id="ch_is_advance" value="" style="margin-top:1px;">
																	</div>
																	<div class="div3" align="left">
																		<input type="text" name="txt_adv_amt" id="txt_adv_amt" class="tboxsmclass" required />
																	</div>
																	<div class="div2 cboxlabel" align="left">
																		( % )
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div4 lboxlabel" align="left">
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_rab" class="" value=""> RAB &nbsp;&nbsp;
																	</div>
																	<div class="div4 lboxlabel" align="left">
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_fbill" class="" value=""> Final Bill 
																	</div>
																	<div class="div4 lboxlabel" align="left">	
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_sa" class="" value=""> Sec. Adv.
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div4 lboxlabel" align="left">
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_mob" class="" value=""> Mob. Adv.
																	</div>
																	<div class="div4 lboxlabel" align="left">	
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_esc" class="" value=""> Escalation 
																	</div>
																	<div class="div4 lboxlabel" align="left">	
																		<input type="checkbox" name="rad_rab_for[]" id="rad_rab_for_oth" class="" value=""> Others
																	</div>
																	<div class="row smclearrow"></div>
																	
																</div>
															</div>
														</div>
														<!--<div class="div3 pd-lr-1" align="left">
															<div class="div12 rectaxbox">
																<div class="card-header inkblue-card">Part A Recovery</div>
																<div class="card-body rectaxbox-body">
																	<div class="div5 lboxlabel">LCess (%)</div>
																	<div class="div5 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">LCess Amt.</div>
																	<div class="div7 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Mob.Adv.</div>
																	<div class="div7 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">P&M Adv.</div>
																	<div class="div7 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Hire Ch.</div>
																	<div class="div7 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div5 lboxlabel">Oth. Rec.</div>
																	<div class="div7 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																</div>
															</div>
														</div>-->
														<div class="div7 pd-lr-1" align="left">
															<div class="div12 rectaxbox">
																<div class="card-header inkblue-card">Part A Recovery</div>
																<div class="card-body rectaxbox-body">
																	<div class="div2 lboxlabel">LCess</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="div2 rboxlabel">Mob.Adv.&nbsp;</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="div2 rboxlabel">P&M Adv.&nbsp;</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																	<div class="div2 lboxlabel">Hire Ch.</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="div2 lboxlabel">Oth. Rec.</div>
																	<div class="div2 lboxlabel" style="padding-right:2px;" align="left">
																		<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
																	</div>
																	<div class="row smclearrow"></div>
																</div>
															</div>
															<div class="div12 rectaxbox">
																<div class="card-header inkblue-card">Part B Recovery</div>
																<div class="card-body rectaxbox-body">
																	<div class="smheadclearrow"><span class="spanhead">Part A</span></div>
																</div>
															</div>
														</div>
														
														
														
														
														
														<div class="row smclearrow"><div class="smheadclearrow"><span class="spanhead">Part A - Recovery Details</span></div></div>
														
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">LCess (%)</div>
															<div>
																<input type="text" name="txt_lcess_perc" id="txt_lcess_perc" class="tboxsmclass" required />
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">LCess Amt.</div>
															<div>
																<input type="text" name="txt_lcess_amt" id="txt_lcess_amt" class="tboxsmclass" required />
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">Mob.Adv</div>
															<div>
																<input type="text" name="txt_mobadv_amt" id="txt_mobadv_amt" class="tboxsmclass" required />
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">P&M Adv.</div>
															<div>
																<input type="text" name="txt_plmach_amt" id="txt_plmach_amt" class="tboxsmclass" required />
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">Hire Charges</div>
															<div>
																<input type="text" name="txt_hcharge_amt" id="txt_hcharge_amt" class="tboxsmclass" required />
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel smlabel">Other Rec.</div>
															<div>
																<input type="text" name="txt_oth_rec_amt" id="txt_oth_rec_amt" class="tboxsmclass" required />
															</div>
														</div>
														
														<div class="row smclearrow"><div class="smheadclearrow"><span class="spanhead">Part B - Recovery Details</span></div></div>
														<div class="div12 lboxlabel">
															<table class="table table-bordered rectable">
																<tbody>
																	<tr>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_it" class="tboxsmclass"></td>
																		<td class="lboxlabel">IT</td>
																		<td class="lboxlabel" width="100"><input type="text" name="txt_it_perc" id="txt_it_perc" class="tboxsmclass div8" required />&nbsp;(%)</td>
																		<td class="lboxlabel"><input type="text" name="txt_it_amt" id="txt_it_amt" class="tboxsmclass" required /></td>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_ec" class="tboxsmclass"></td>
																		<td class="lboxlabel">Elect. Charges</td>
																		<td class="lboxlabel"><input type="text" name="txt_elec_charge" id="txt_elec_charge" class="tboxsmclass" required /></td>
																	</tr>
																	<tr>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_cgst" class="tboxsmclass"></td>
																		<td class="lboxlabel" nowrap="nowrap">TDS On CGST</td>
																		<td class="lboxlabel" width="100"><input type="text" name="txt_cgst_tds_perc" id="txt_cgst_tds_perc" class="tboxsmclass div8" required />&nbsp;(%)</td>
																		<td class="lboxlabel"><input type="text" name="txt_cgst_tds_amt" id="txt_cgst_tds_amt" class="tboxsmclass" required /></td>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_wc" class="tboxsmclass"></td>
																		<td class="lboxlabel">Water Charges</td>
																		<td class="lboxlabel"><input type="text" name="txt_water_ch_amt" id="txt_water_ch_amt" class="tboxsmclass" required /></td>
																	</tr>
																	<tr>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_sgst" class="tboxsmclass"></td>
																		<td class="lboxlabel" nowrap="nowrap">TDS On SGST</td>
																		<td class="lboxlabel" width="100"><input type="text" name="txt_sgst_tds_perc" id="txt_sgst_tds_perc" class="tboxsmclass div8" required />&nbsp;(%)</td>
																		<td class="lboxlabel"><input type="text" name="txt_sgst_tds_amt" id="txt_sgst_tds_amt" class="tboxsmclass" required /></td>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_mob_int" class="tboxsmclass"></td>
																		<td class="lboxlabel">Mob.Adv. Int.</td>
																		<td class="lboxlabel"><input type="text" name="txt_mob_adv_int_amt" id="txt_mob_adv_int_amt" class="tboxsmclass" required /></td>
																	</tr>
																	<tr>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_sd" class="tboxsmclass"></td>
																		<td class="lboxlabel">SD</td>
																		<td class="lboxlabel" width="100"><input type="text" name="txt_sd_perc" id="txt_sd_perc" class="tboxsmclass div8" required />&nbsp;(%)</td>
																		<td class="lboxlabel"><input type="text" name="txt_sd_amt" id="txt_sd_amt" class="tboxsmclass" required /></td>
																		<td class="lboxlabel"><input type="checkbox" id="ch_is_pm_int" class="tboxsmclass"></td>
																		<td class="lboxlabel">P&M Adv. Int.</td>
																		<td class="lboxlabel"><input type="text" name="txt_plmach_int_amt" id="txt_plmach_int_amt" class="tboxsmclass" required /></td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
																<!--<div class="erow"></div>-->
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
                    </blockquote>
                </div>
            </div>
        </div>
		
    </form>
    <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script src='TabVizard/BSMagic-min.js'></script>
<script src='TabVizard/gsap.min.js'></script>

<script>
BSMagic({
  id: "JTab1",
  addButtons: true,
  navShape: "square",
  navBackground: "white",
  navFontColor: "blue",
  navUnderline: true,
  navShadow: true
});
$('#cmb_miscell').chosen();
$('#cmb_contractor').chosen();
$(document).ready(function(){
	$('body').on("change","#txt_ccno", function(event){
		var Ccno = $(this).val();
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
					if(WData != null){
						$("#txt_work_name").val(WData['work_name']);
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
						$("#txt_lcess_perc").val(RECData['lw_cess_percent']);
						$("#txt_lcess_amt").val(RECData['lw_cess_amt']);
						$("#txt_mobadv_amt").val(RECData['mob_adv_amt']);
						$("#txt_plmach_amt").val(RECData['pl_mac_adv_amt']); 
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
							TableStr += '<tr><td class="smlboxlabel">Hire Charges</td><td class="smrboxlabel" align="right">'+HireCharge+'</td></tr>';
							TotalRec = Number(TotalRec) + Number(HireCharge);
						}
						$("#txt_hcharge_amt").val(''); 
						var OthRecAmt = Number(RECData['other_recovery_1_amt']) + Number(RECData['other_recovery_2_amt'])+ Number(RECData['other_recovery_3_amt']);
						if(OthRecAmt != 0){
							$("#txt_oth_rec_amt").val(OthRecAmt); 
							TableStr += '<tr><td class="smlboxlabel">Other Recoveries</td><td class="smrboxlabel" align="right">'+OthRecAmt+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(OthRecAmt);
						}
						TableStr += '<tr><td class="smlboxlabel" colspan="3"><span class="spanhead">Recoveries : Part B</span></td></tr>';
						$("#txt_it_perc").val(RECData['incometax_percent']); 
						if((RECData['incometax_amt'] != 0)&&(RECData['incometax_amt'] != null)&&(RECData['incometax_amt'] != '')){
							$("#ch_is_it").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">IT</td><td class="smrboxlabel" align="right">'+RECData['incometax_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['incometax_amt']);
						}
						$("#txt_it_amt").val(RECData['incometax_amt']); 
						$("#txt_cgst_tds_perc").val(RECData['cgst_tds_perc']); 
						$("#txt_cgst_tds_amt").val(RECData['cgst_tds_amt']); 
						if((RECData['cgst_tds_amt'] != 0)&&(RECData['cgst_tds_amt'] != null)&&(RECData['cgst_tds_amt'] != '')){
							$("#ch_is_cgst").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">CGST</td><td class="smrboxlabel" align="right">'+RECData['cgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['cgst_tds_amt']);
						}
						$("#txt_sgst_tds_perc").val(RECData['sgst_tds_perc']); 
						$("#txt_sgst_tds_amt").val(RECData['sgst_tds_amt']); 
						if((RECData['sgst_tds_amt'] != 0)&&(RECData['sgst_tds_amt'] != null)&&(RECData['sgst_tds_amt'] != '')){
							$("#ch_is_sgst").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">SGST</td><td class="smrboxlabel" align="right">'+RECData['sgst_tds_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['sgst_tds_amt']);
						}
						$("#txt_sd_perc").val(RECData['sd_percent']); 
						$("#txt_sd_amt").val(RECData['sd_amt']); 
						if((RECData['sd_amt'] != 0)&&(RECData['sd_amt'] != null)&&(RECData['sd_amt'] != '')){
							$("#ch_is_sd").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">SD</td><td class="smrboxlabel" align="right">'+RECData['sd_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['sd_amt']);
						}
						
						$("#txt_elec_charge").val(RECData['electricity_cost']); 
						if((RECData['electricity_cost'] != 0)&&(RECData['electricity_cost'] != null)&&(RECData['electricity_cost'] != '')){
							$("#ch_is_ec").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">Electricity Cost</td><td class="smrboxlabel" align="right">'+RECData['electricity_cost']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['electricity_cost']);
						}
						$("#txt_water_ch_amt").val(RECData['water_cost']); 
						if((RECData['water_cost'] != 0)&&(RECData['water_cost'] != null)&&(RECData['water_cost'] != '')){
							$("#ch_is_wc").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">Water Cost</td><td class="smrboxlabel" align="right">'+RECData['water_cost']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['water_cost']);
						}
						$("#txt_mob_adv_int_amt").val(RECData['mob_adv_int_amt']); 
						if((RECData['mob_adv_int_amt'] != 0)&&(RECData['mob_adv_int_amt'] != null)&&(RECData['mob_adv_int_amt'] != '')){
							$("#ch_is_mob_int").prop("checked",true);
							TableStr += '<tr><td class="smlboxlabel">Mob.Adv. Interest</td><td class="smrboxlabel" align="right">'+RECData['mob_adv_int_amt']+'</td><td>&nbsp;</td></tr>';
							TotalRec = Number(TotalRec) + Number(RECData['mob_adv_int_amt']);
						}
						$("#txt_plmach_int_amt").val(''); 
						TableStr += '<tr><td class="rboxlabel"c colspan="2">Total Recovery</td><td class="rboxlabel">'+TotalRec+'</td></tr>';
						//if((RECData['txt_plmach_int_amt'] != 0)&&(RECData['txt_plmach_int_amt'] != null)&&(RECData['txt_plmach_int_amt'] != '')){
							//$("#ch_is_pm_int").prop("checked",true);
						//}
					}
					var NetPayable = Number(NetAmount) - Number(TotalRec);
					TableStr += '<tr><td class="rboxlabel"c colspan="2">Net payable</td><td class="rboxlabel">'+NetPayable.toFixed(2)+'</td></tr>';
					TableStr += '</table>';
					$('#VochData').html(TableStr);
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
});
</script>
</body>
</html>

