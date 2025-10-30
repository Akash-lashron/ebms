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
									
									<div class="row justify-content-center">
					<div class="bd-example bd-example-tabs" style="width: 835px;" id="JTab1">
						<div class="row">
							<div class="col-3 tab-menu-sec">
								<div class="nav flex-column flex-grow-1 flex-fill nav-pills" role="tablist" style="height: 335px; pointer-events:none" aria-orientation="vertical">
									<a class="nav-link active BSNavTab" id="v-pills-application-type-tab" data-toggle="pill" href="#v-pills-application-type" role="tab" aria-controls="v-pills-application-type" aria-selected="true"><img src="images/Application.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Apply For</a>
									<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false"><img src="images/Applicant.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Applicant Detail</a>
									<a class="nav-link" id="v-pills-home2-tab" data-toggle="pill" href="#v-pills-home2" role="tab" aria-controls="v-pills-home2" aria-selected="false"><img src="images/Employment.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Employment Detail</a>
									<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><img src="images/Pay.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Pay Matrix</a>
									<a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><img src="images/Family.png" width="23" height="23" class="tab-img" />&nbsp;&nbsp;Spouse / Family Info</a>
								</div>
							</div>
							<div class="col-9 BSMagic" style="padding-left:30px;" id="test">
								<div class="tab-content" id="v-pills-tabContent">
									<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
										<div class="div-tab-head">Apply For</div>
										<div class="div-tab-body">
											<!--<div class="erow"></div>-->
											<div class="row">
												<div class="col-sm-12">
													<!--<input type="radio" class="form-text rad-ip-sm" name="rad_application_type" id="rad_application_type_n" value="N">&nbsp; Priority Application &emsp;
													<input type="radio" class="form-text rad-ip-sm" name="rad_application_type" id="rad_application_type_t" value="T">&nbsp; Change Application &emsp;-->
													<div class="Btn-3Check">
														<input name="PriorAppln" id="PriorAppln" type="checkbox" class="3dCheck ApplnType" style="display:none" value="PA" <?php if($PriorAppln == "PA"){ ?> checked="checked" <?php } ?>/>
														<label class="ChLable" for="PriorAppln">Priority Application <span style='font-size:15px;' class="<?php if($PriorAppln != "PA"){ ?>hide<?php } ?>" id="PriorApplnTk">&#10004;</span></label>
													</div>
													<?php if(($AppEnableType == "OA")||($AppEnableType == "NWL")){ ?>
													<div class="Btn-3Check">
														<input name="ChangAppln" id="ChangAppln" type="checkbox" class="3dCheck ApplnType" style="display:none" value="CA" <?php if($ChangAppln == "CA"){ ?> checked="checked" <?php } ?>/>
														<label class="ChLable" for="ChangAppln">Change Application <span style='font-size:15px;' class="<?php if($ChangAppln != "CA"){ ?>hide<?php } ?>" id="ChangApplnTk">&#10004;</span></label>
													</div>
													<?php } ?>
													<div style="font-size:11px; color:red; font-weight:bold; text-align:left">
														<div>* This is the common Application for both Priority and Change</div>
														<div>&emsp;1) If you want to apply only Priority You have to click Priority Application</div>
														<div>&emsp;2) If you want to apply only Change You have to click Change Application</div>
														<div>&emsp;3) If you want to apply for both Priority and Change You have to click both Priority and Change 
														<br/>&emsp;&emsp;Application at a time.</div>
													</div>
												</div>
											</div>
											<!--<div class="erow"></div>-->
										</div>
									</div>
									<div class="tab-pane fade tab-body-sec" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
										<div class="div-tab-head">Applicant Details Entry</div>
										<div class="div-tab-body">
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-3">
													<div class="form-label" align="left">Unit</div>
													<div class="input-group">
														<select class="form-control form-text" name="cmb_unit" id="cmb_unit">
															<option value=""> --- Select --- </option>
															<?php
																$SelectQuery 	= "select * from gso_unit WHERE active = 1" ;
																$SelectSql		= mysql_query($SelectQuery);
																if($SelectSql == true){
																	if(mysql_num_rows($SelectSql)>0){
																		while($SList = mysql_fetch_object($SelectSql)){
																			if($Unit == $SList->UNITCODE){
																				$sel = "selected";
																			}else{
																				$sel = "";
																			}
																			echo '<option value="'.$SList->UNITCODE.'"'.$sel.'>'.$SList->UNITNAME.'</option>';
																		}
																	}
																}
															?>
														</select>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">ICNO</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_icno" id="txt_icno" value="<?php echo $Icno; ?>" readonly="" onKeyPress="return isIntegerValue(event,this);">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label" align="left">Name</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_name" id="txt_name" value="<?php echo $Name; ?>" maxlength="50">
													</div>
												</div>
											</div>
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-3">
													<div class="form-label" align="left">Grade</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_grade" id="txt_grade" value="<?php echo $Grade; ?>" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">Group</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_group" id="txt_group" value="<?php echo $Group; ?>" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">Division</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_division" id="txt_division" value="<?php echo $Division; ?>" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">Section</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_section" id="txt_section" value="<?php echo $Section; ?>" maxlength="20">
													</div>
												</div>
											</div>
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-3">
													<div class="form-label" align="left">Date of Birth</div>
													<div class="input-group">
														<input type="text" class="form-control form-text DoBJ DatePick DateCheck" data-field="DB" name="txt_dob" id="txt_dob" value="<?php if($Dob != '00/00/0000'){ echo $Dob; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">Caste</div>
													<div class="input-group">
														<select class="form-control form-text" name="cmb_caste" id="cmb_caste" value="<?php echo $Caste; ?>">
															<option value=""> --- Select --- </option>
															<?php 
															foreach($CasteArr as $CasteKey => $CasteVal){ 
																if($CasteKey == $Caste){
																	echo '<option value="'.$CasteKey.'" selected="selected">'.$CasteVal.'</option>';
																}else{
																	echo '<option value="'.$CasteKey.'">'.$CasteVal.'</option>';
																}
															} 
															?>
														</select>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">DOJ in Kalpakkam</div>
													<div class="input-group">
														<input type="text" class="form-control form-text DoBJ DatePick1 DateCheck" data-field="DJ" name="txt_doj_dae" id="txt_doj_dae" value="<?php if($DojDae != '00/00/0000'){ echo $DojDae; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label" align="left">Marital Status </div>
													<div class="input-group">
														<select class="form-control form-text" name="cmb_marry_status" id="cmb_marry_status">
															<option value=""> --- Select --- </option>
															<option value="S" <?php if($MarryStatus == "S"){ ?>selected="selected"<?php } ?>>SINGLE</option>
															<option value="M" <?php if($MarryStatus == "M"){ ?>selected="selected"<?php } ?>>MARRIED</option>
															<option value="D" <?php if($MarryStatus == "D"){ ?>selected="selected"<?php } ?>>DIVORCEE</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade tab-body-sec" id="v-pills-home2" role="tabpanel" aria-labelledby="v-pills-home2-tab" style="padding-bottom:0px;">
										<div class="div-tab-head">Employment Details Entry</div>
										<div class="div-tab-body" style="padding-bottom:0px;">
											<div class="row">
												<div class="col-sm-12">
													<div class="input-group">
														<span class="form-label">Whether Date of Joining as Trainee in DAE &nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip JoinTrain" name="rad_doj_trainee" id="rad_doj_trainee_y" value="Y" <?php if($IsTrainee == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip JoinTrain" name="rad_doj_trainee" id="rad_doj_trainee_n" value="N" <?php if($IsTrainee == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text JoinTrainDt DateCheck DatePick1 <?php if($IsTrainee != "Y"){ ?> hide <?php } ?>" data-field="DJTR" name="txt_trainee_doj" id="txt_trainee_doj" placeholder="Enter DOJ Date" value="<?php if($DojTraineeDt != '00/00/0000'){ echo $DojTraineeDt; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-12">
													<div class="form-label" align="left" style="height:15px"></div>
													<div class="input-group">
														<span class="form-label">DOJ of other DAE Unit prior to joining kalpakkam&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:1px">:</span> &emsp;</span>
														<input type="radio" class="form-text rad-ip DaeOtherUnit" name="rad_doj_dae_unit" id="rad_doj_dae_unit_y" value="Y" <?php if($IsDaeOthUnit == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip DaeOtherUnit" name="rad_doj_dae_unit" id="rad_doj_dae_unit_n" value="N" <?php if($IsDaeOthUnit == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text DaeOtherUnitDt DateCheck DatePick1 <?php if($IsDaeOthUnit != "Y"){ ?> hide <?php } ?>" data-field="DJOD" name="txt_doj_dae_unit" id="txt_doj_dae_unit" placeholder="Enter DOJ Date" value="<?php if($DaeOthUnitDt != '00/00/0000'){ echo $DaeOthUnitDt; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-12">
													<div class="form-label" align="left" style="height:15px"></div>
													<div class="input-group">
														<span class="form-label">DOJ in any other Org. counting for accommodation&nbsp;&nbsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip OtherOrg" name="rad_doj_oth_org" id="rad_doj_oth_org_y" value="Y" <?php if($IsOthOrg == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip OtherOrg" name="rad_doj_oth_org" id="rad_doj_oth_org_n" value="N" <?php if($IsOthOrg == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text OtherOrgDt DateCheck DatePick1 <?php if($IsOthOrg != "Y"){ ?> hide <?php } ?>" data-field="DJOO" name="txt_doj_oth_org" id="txt_doj_oth_org" placeholder="Enter DOJ Date" value="<?php if($OthOrgDt != '00/00/0000'){ echo $OthOrgDt; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-12">
													<div class="form-label" align="left" style="height:15px"></div>
													<div class="input-group">
														<span class="form-label">Is Ex-Service Any ?&nbsp;&nbsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip ExService" name="rad_is_ex_serv" id="rad_is_ex_serv_y" value="Y" <?php if($IsExService == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip ExService" name="rad_is_ex_serv" id="rad_is_ex_serv_n" value="N" <?php if($IsExService == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<span class="form-label">From : &nbsp;</span>
														<input type="text" class="form-control form-text ExServiceDt DateCheck" name="txt_serv_st_dt" id="txt_serv_st_dt" data-field="XSSD" <?php if($IsExService != "Y"){ ?> disabled="disabled" <?php } ?> placeholder="Service Start Date" value="<?php if($ServiceFDate != '00/00/0000'){ echo $ServiceFDate; } ?>" readonly="" style="background:#fff">
														<span class="form-label">&nbsp;To : &nbsp;</span>
														<input type="text" class="form-control form-text ExServiceDt DateCheck" name="txt_serv_end_dt" id="txt_serv_end_dt" data-field="XSED" <?php if($IsExService != "Y"){ ?> disabled="disabled" <?php } ?> placeholder="Service End Date" value="<?php if($ServiceTDate != '00/00/0000'){ echo $ServiceTDate; } ?>" readonly="" style="background:#fff">
													</div>
												</div>
												<div class="col-sm-12 erow"></div>
												
												<div class="col-sm-6">
													<div class="input-group">
														<span class="form-label">Eligible Cadre&nbsp;&nbsp;: &emsp;</span>
														<select name="cmb_cadre" id="cmb_cadre" class="form-control form-text" style="width:250px">
															<option value=""> --- Select ---</option>
															<?php 
															if(count($GlobCadreArr)>0){
																foreach($GlobCadreArr as $GlobCadreKey => $GlobCadreValue){
																	if($Cadre == $GlobCadreKey){
																		$Sel = 'selected="selected"';
																	}else{
																		$Sel = '';
																	}
																	echo '<option value="'.$GlobCadreKey.'" '.$Sel.'>'.$GlobCadreValue.'</option>';
																}
															}
															?>
														</select>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="form-label">&emsp;&emsp;&nbsp;Eligible Date&nbsp;&nbsp;&nbsp;: &emsp;&emsp;</span>
														<input type="text" class="form-control form-text DateCheck DatePick1" data-field="SED" data-Level="12" name="txt_scint_elig_dt" id="txt_scint_elig_dt" value="<?php if($EligScientistDt != '00/00/0000'){ echo $EligScientistDt; } ?>" <?php if(($Cadre == 'SO')||($Cadre == 'TO')){ }else{ ?> disabled="disabled" <?php } ?> readonly="" style="margin-left:1px">
													</div>
												</div>
												<div class="col-sm-12 DirRecRow <?php if(($Cadre != 'SO')&&($Cadre != 'TO')){ ?>hide<?php } ?>">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Directly Recruited / Trainee ?&nbsp; <sup style="color:#F8012D; font-size:11px;">#</sup>: &nbsp;</span>
														<input type="radio" class="form-text rad-ip RadDirRec" name="rad_dir_recruit" id="rad_dir_recruit_y" value="Y" <?php if($DirRecruit == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &nbsp;&nbsp;</span>
														<input type="radio" class="form-text rad-ip RadDirRec" name="rad_dir_recruit" id="rad_dir_recruit_n" value="N" <?php if($DirRecruit == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No</span>
														<!--&nbsp;&emsp;&emsp;&emsp;&nbsp;<span class="form-label">Level at the time of <br/>Direct Recruitment</span>&nbsp;&nbsp;&nbsp;: &emsp;&nbsp;&nbsp;
														<select class="form-control form-text" name="cmb_dir_rec_level" id="cmb_dir_rec_level" disabled="disabled">
															<option value=""> --- Select ---</option>
															<?php 
															foreach($GlobEmployeeLevelArr as $GlobEmpLevelKey => $GlobEmpLevelVal){ 
															if(($Level == "")||($Level == 0)||($GlobEmpLevelKey >= 9)){
															?>
															<option value="<?php echo $GlobEmpLevelKey; ?>" <?php if($GlobEmpLevelKey == $Level){ ?>selected="selected"<?php } ?>><?php echo $GlobEmpLevelVal; ?></option>
															<?php } } ?>
														</select>-->
													</div>
													<div class="col-sm-12" style="font-size:11px; padding:0px;"><span style="color:#FE070E; font-weight:bold;">( # )</span> Opt 'Yes' if directly recruited as 'Scientific / Technical Officer'</div>
												</div>
												
												
												
												<!--<div class="erow"></div>
												<div class="col-sm-12 SciRow <?php if(($Level == '')||($Level <= 10)){ echo 'hide'; } ?>">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Is eligible as Scientist ? &emsp;&nbsp;&nbsp;&nbsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip ScientElig" name="rad_elig_scientist" id="rad_elig_scientist_y" value="Y"<?php if($EligScientist == "N"){ ?> checked="checked" <?php } ?>/><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip ScientElig" name="rad_elig_scientist" id="rad_elig_scientist_n" value="N" <?php if($EligScientist == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text ScientEligDt hide DatePick1" name="txt_scint_elig_dt" id="txt_scint_elig_dt" placeholder="Please Enter Scientist Eligible Date" value="<?php echo $EligScientistDt; ?>" readonly="">
													</div>
												</div>
												<div class="erow SciRow <?php if(($Level == '')||($Level <= 10)||($EligScientist != "Y")){ echo 'hide'; } ?>"></div>-->
												<!--<div class="col-sm-12 DirRow <?php if(($Level == '')||($Level <= 10)||($EligScientist != "Y")){ echo 'hide'; } ?>">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Whether Directly Recruited&nbsp;&nbsp;: &nbsp; &nbsp;</span>
														<input type="radio" class="form-text rad-ip" name="rad_dir_recruit" id="rad_dir_recruit_y" value="Y" <?php if($DirRecruit == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip" name="rad_dir_recruit" id="rad_dir_recruit_n" value="N" <?php if($DirRecruit == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No</span>
													</div>
												</div>-->
												<!--<div class="col-sm-12 erow"></div>
												<div class="col-sm-12">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Date of Joining as Trainee&nbsp;&nbsp;&nbsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip JoinTrain" name="rad_doj_trainee" id="rad_doj_trainee_y" value="Y" <?php if($IsTrainee == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip JoinTrain" name="rad_doj_trainee" id="rad_doj_trainee_n" value="N" <?php if($IsTrainee == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text JoinTrainDt hide DatePick1" name="txt_trainee_doj" id="txt_trainee_doj" placeholder="Please Enter DOJ Trainee Date" value="<?php echo $DojTraineeDt; ?>" readonly="">
													</div>
												</div>
												<div class="erow"></div>
												<div class="col-sm-12 SciRow <?php if(($Level == '')||($Level <= 10)){ echo 'hide'; } ?>">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Is eligible as Scientist ? &emsp;&nbsp;&nbsp;&nbsp;: &emsp;</span>
														<input type="radio" class="form-text rad-ip ScientElig" name="rad_elig_scientist" id="rad_elig_scientist_y" value="Y"<?php if($EligScientist == "N"){ ?> checked="checked" <?php } ?>/><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip ScientElig" name="rad_elig_scientist" id="rad_elig_scientist_n" value="N" <?php if($EligScientist == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No &emsp;</span>
														<input type="text" class="form-control form-text ScientEligDt hide DatePick1" name="txt_scint_elig_dt" id="txt_scint_elig_dt" placeholder="Please Enter Scientist Eligible Date" value="<?php echo $EligScientistDt; ?>" readonly="">
													</div>
												</div>
												<div class="erow SciRow <?php if(($Level == '')||($Level <= 10)||($EligScientist != "Y")){ echo 'hide'; } ?>"></div>
												<div class="col-sm-12 DirRow <?php if(($Level == '')||($Level <= 10)||($EligScientist != "Y")){ echo 'hide'; } ?>">
													<div class="form-label" align="left">&nbsp;</div>
													<div class="input-group">
														<span class="form-label">Whether Directly Recruited&nbsp;&nbsp;: &nbsp; &nbsp;</span>
														<input type="radio" class="form-text rad-ip" name="rad_dir_recruit" id="rad_dir_recruit_y" value="Y" <?php if($DirRecruit == "Y"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;Yes &emsp;</span>
														<input type="radio" class="form-text rad-ip" name="rad_dir_recruit" id="rad_dir_recruit_n" value="N" <?php if($DirRecruit == "N"){ ?> checked="checked" <?php } ?>><span class="form-label">&nbsp;No</span>
													</div>
												</div>-->
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade tab-body-sec" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
										<div class="div-tab-head">Level in Pay Matrix Details Entry</div>
										<div class="div-tab-body">
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-4">
													<div class="form-label" align="left">Present Level</div>
													<div class="input-group">
														<!--<input type="text" class="form-control form-text" name="txt_level" id="txt_level" value="<?php if(($Level != '')&&($Level != 0)){ echo $Level; } ?>" maxlength="3">-->
														<select class="form-control form-text" name="txt_level" id="txt_level">
															<option value=""> --- Select ---</option>
															<?php 
															foreach($GlobEmployeeLevelArr as $GlobEmpLevelKey => $GlobEmpLevelVal){ 
															//if(($Level == "")||($Level == 0)||($GlobEmpLevelKey >= $PrevLevel)){
															if($GlobEmpLevelKey >= $MinPayLevel){
															?>
															<option value="<?php echo $GlobEmpLevelKey; ?>" <?php if($GlobEmpLevelKey == $DisplayLevel){ ?>selected="selected"<?php } ?>><?php echo $GlobEmpLevelVal; ?></option>
															<?php } } ?>
														</select>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label" align="left">Level Attaining Date</div>
													<div class="input-group">
														<input type="text" class="form-control form-text DateCheck DatePick1" data-field="LAD" name="txt_level_att_date" id="txt_level_att_date" value="<?php if($LevelAttDt != '00/00/0000'){ echo $LevelAttDt; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label" align="left">Pay as on 1<sup>st</sup> April <?php echo date("Y"); ?></div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_pay_level" id="txt_pay_level" value="<?php if(($PayLevel != '')&&($PayLevel != 0)){ echo $PayLevel; } ?>" maxlength="7" onKeyPress="return isIntegerValue(event,this);">
													</div>
												</div>
											</div>
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-4">
													<div class="form-label <?php if(($DisplayLevel >= 9)&&($DirScientTemp != 1)){ }else{ ?>dis-label<?php } ?>" align="left" id="L9-Label">Level 9 Eligibility Date <i class="fa fa-question-circle InfoFaicon" data-Level="9" style="font-size:14px"></i></div>
													<div class="input-group">
														<input type="text" class="form-control form-text LDate DateCheck DatePick1" data-field="LAD9" data-Level="9" name="txt_level_att_date_9" id="txt_level_att_date_9" <?php if(($DisplayLevel >= 9)&&($DirScientTemp != 1)){ }else{ ?>disabled="disabled"<?php } ?> value="<?php if(($LevelAttDt9 != '00/00/0000')&&($DirScientTemp != 1)){ echo $LevelAttDt9; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label <?php if($DisplayLevel >= 10){ }else{ ?>dis-label<?php } ?>" align="left" id="L10-Label">Level 10 Eligibility Date <i class="fa fa-question-circle InfoFaicon" data-Level="10" style="font-size:14px"></i></div>
													<div class="input-group">
														<input type="text" class="form-control form-text LDate DateCheck DatePick1" data-field="LAD10" data-Level="10" name="txt_level_att_date_10" id="txt_level_att_date_10" <?php if($DisplayLevel >= 10){ }else{ ?>disabled="disabled"<?php } ?> value="<?php if($LevelAttDt10 != '00/00/0000'){ echo $LevelAttDt10; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label <?php if($DisplayLevel >= 11){ }else{ ?>dis-label<?php } ?>" align="left" id="L11-Label">Level 11 Eligibility Date <i class="fa fa-question-circle InfoFaicon" data-Level="11" style="font-size:14px"></i></div>
													<div class="input-group">
														<input type="text" class="form-control form-text LDate DateCheck DatePick1" data-field="LAD11" data-Level="11" name="txt_level_att_date_11" id="txt_level_att_date_11" <?php if($DisplayLevel >= 11){ }else{ ?>disabled="disabled"<?php } ?> value="<?php if($LevelAttDt11 != '00/00/0000'){ echo $LevelAttDt11; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-12 erow"></div>
												<div class="col-sm-4">
													<div class="form-label <?php if($DisplayLevel >= 12){ }else{ ?>dis-label<?php } ?>" align="left" id="L12-Label">Level 12 Eligibility Date <i class="fa fa-question-circle InfoFaicon" data-Level="12" style="font-size:14px"></i></div>
													<div class="input-group">
														<input type="text" class="form-control form-text LDate DateCheck DatePick1" data-field="LAD12" data-Level="12" name="txt_level_att_date_12" id="txt_level_att_date_12" <?php if($DisplayLevel >= 12){ }else{ ?>disabled="disabled"<?php } ?> value="<?php if($LevelAttDt12 != '00/00/0000'){ echo $LevelAttDt12; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label" align="left">Present Quarters</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_quarters" id="txt_quarters" value="<?php if($HouseStayed != ""){ echo $Qtrs; } ?>" readonly="" maxlength="50">
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-label" align="left">Present Category</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_category" id="txt_category" value="<?php if($HouseStayed != ""){ echo $Category; } ?>" readonly="" maxlength="10" onKeyPress="return onlyAlphabets(event,this);">
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="tab-pane fade tab-body-sec" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
										<div class="div-tab-head">Spouse Details Entry</div>
										<div class="div-tab-body" style="padding: 8px 15px 2px 15px;">
											<div class="row">
												<div class="col-sm-12">
													<div class="input-group form-label">
														Is Spouse Employed at DAE, Kalpakkam ? &emsp;&nbsp;&nbsp;&nbsp;: &emsp;
														<input type="radio" class="form-text rad-ip SpoEmp" name="rad_spouse_employed" id="rad_spouse_employed_y" value="Y" <?php if($SpEmployed == "Y"){ ?> checked="checked" <?php } ?> <?php if($MarryStatus == "S"){ ?> disabled="disabled" <?php } ?>>&nbsp;Yes &emsp;
														<input type="radio" class="form-text rad-ip SpoEmp" name="rad_spouse_employed" id="rad_spouse_employed_n" value="N" <?php if(($SpEmployed == "N")||($MarryStatus == "S")){ ?> checked="checked" <?php } ?>>&nbsp;No &emsp;
														<input type="hidden" name="txt_sp_employ" id="txt_sp_employ" value="<?php echo $SpEmployed; ?>" />
													</div>
												</div>
											</div>
											<div class="erow" style="height:10px;"></div>
											<div class="row">
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Unit</div>
													<div class="input-group">
														<select class="form-control form-text Spouse SP-Input" name="cmb_unit_sp" id="cmb_unit_sp" <?php if(($SpEmployed != "Y")||($MarryStatus == "S")){ ?> disabled="disabled" <?php } ?>>
															<option value=""> --- Select --- </option>
															<?php
																$SelectQuery 	= "select * from gso_unit WHERE active = 1" ;
																$SelectSql		= mysql_query($SelectQuery);
																if($SelectSql == true){
																	if(mysql_num_rows($SelectSql)>0){
																		while($SList = mysql_fetch_object($SelectSql)){
																			if(($SpUnit == $SList->UNITCODE)&&($SpEmployed == "Y")){
																				$sel = "selected";
																			}else{
																				$sel = "";
																			}
																			echo '<option value="'.$SList->UNITCODE.'"'.$sel.'>'.$SList->UNITNAME.'</option>';
																		}
																	}
																}
															?>
															<!--<option value="BH">BHAVINI</option>-->
														</select>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's ICNO</div>
													<div class="input-group">
														<input type="text" class="form-control form-text Spouse SP-Input" name="txt_icno_sp" id="txt_icno_sp" <?php if(($SpEmployed != "Y")||($MarryStatus == "S")){ ?> disabled="disabled" <?php } ?> value="<?php if($SpEmployed == "Y"){ echo $SpIcno; } ?>" onKeyPress="return isIntegerValue(event,this);" maxlength="10">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Name</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_name_sp" id="txt_name_sp" <?php if(($SpEmployed != "Y")||($MarryStatus == "S")){ ?> disabled="disabled" <?php } ?> onKeyPress="return onlyAlphabets(event,this);" maxlength="50" value="<?php if($SpEmployed == "Y"){ echo $SpName; } ?>">
													</div>
												</div>
											</div>
											<div class="erow" style="height:13px;"></div>
											<!--<div class="row">
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Grade</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_grade_sp" id="txt_grade_sp" disabled="disabled" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Group</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_group_sp" id="txt_group_sp" disabled="disabled" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Division</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_division_sp" id="txt_division_sp" disabled="disabled" maxlength="10">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Section</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_section_sp" id="txt_section_sp" disabled="disabled" maxlength="20">
													</div>
												</div>
											</div>
											<div class="erow"></div>-->
											<div class="row">
												<div class="col-sm-6">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Quarters</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_quarters_sp" id="txt_quarters_sp" <?php if(($SpEmployed != "Y")||($MarryStatus == "S")){ ?> disabled="disabled" <?php } ?> maxlength="50" value="<?php if($SpEmployed == "Y"){ echo $SpStayingHouse; } ?>" readonly="">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label dis-label SP-Label" align="left">Spouse's Type</div>
													<div class="input-group">
														<input type="text" class="form-control form-text SP-Input" name="txt_category_sp" id="txt_category_sp" <?php if(($SpEmployed != "Y")||($MarryStatus == "S")){ ?> disabled="disabled" <?php } ?> maxlength="10" onKeyPress="return onlyAlphabets(event,this);" value="<?php if($SpEmployed == "Y"){ echo $SpStayingCata; } ?>" readonly="">
													</div>
												</div>
											</div>
											<div class="erow" style="height:10px;"></div>
											<div class="row">
												<div class="col-sm-12">
													<table class="table table-bordered" id="Family">	
														<thead>
															<tr>
																<th>Family Member's Name</th>
																<th>Relationship</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td><input type="text" class="form-control form-text" name="txt_fmember_name[]" id="txt_fmember_name" /></td>
																<td><input type="text" class="form-control form-text" name="txt_fmember_relation[]" id="txt_fmember_relation" /></td>
																<td align="center"><input type="button" class="btn btn-primary" name="btn_add" id="btn_add" value=" + " /></td>
															</tr>
															<?php 
															$SelectQuery3 = "select * from family_member where icno = '$Icno' and unit = '$Unit'";
															$SelectSql3 = mysql_query($SelectQuery3,$conn);
															if($SelectSql3 == true){
																if(mysql_num_rows($SelectSql3)>0){
																	while($FMList = mysql_fetch_object($SelectSql3)){
															?>
															<tr>
																<td><input type="text" class="form-control form-text" name="txt_fmember_name[]" value="<?php echo $FMList->member_name; ?>" /></td>
																<td><input type="text" class="form-control form-text" name="txt_fmember_relation[]" value="<?php echo $FMList->member_relship; ?>" /></td>
																<td align="center"><input type="button" class="btn btn-danger Delete" name="btn_del" id="btn_del" value=" X " /></td>
															</tr>
															<?php
																	}
																}
															}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade tab-body-sec" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
										<div class="div-tab-head">Contact Details Entry</div>
										<div class="div-tab-body">
											<div class="erow"></div>
											<!--<div class="row">
												<div class="col-sm-6">
													<div class="form-label" align="left">Present Quarters</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_quarters" id="txt_quarters" value="<?php if($HouseStayed != ""){ echo $Qtrs; } ?>" maxlength="50">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label" align="left">Present Category</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_category" id="txt_category" value="<?php if($HouseStayed != ""){ echo $Category; } ?>" maxlength="10" onKeyPress="return onlyAlphabets(event,this);">
													</div>
												</div>
											</div>
											<div class="erow"></div>-->
											<div class="row">
												<div class="col-sm-6">
													<div class="form-label" align="left">E-Mail ID</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_email" id="txt_email" value="<?php echo $Email; ?>" maxlength="50">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label" align="left">Mobile No.</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_mobile_no" id="txt_mobile_no" value="<?php echo $MobileNo; ?>" maxlength="10" onKeyPress="return isIntegerValue(event,this);">
													</div>
												</div>
											</div>
											<div class="erow"></div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-label" align="left">Intercom (Office)</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_inter_offc" id="txt_inter_offc" value="<?php echo $InterOffc; ?>" maxlength="10" onKeyPress="return isIntegerValue(event,this);">
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-label" align="left">Intercom (Residence)</div>
													<div class="input-group">
														<input type="text" class="form-control form-text" name="txt_inter_res" id="txt_inter_res" value="<?php echo $InterRes; ?>" maxlength="10" onKeyPress="return isIntegerValue(event,this);">
													</div>
												</div>
											</div>
											
										</div>
									</div>
									<div class="tab-pane fade tab-body-sec" id="v-pills-submit" role="tabpanel" aria-labelledby="v-pills-submit-tab">
										<div class="div-tab-head">Submission</div>
										<div class="div-tab-body">
											<div class="row">
												<div class="col-sm-12">
													<div class="input-group form-label">
														<span style="text-align:justify">Whether interested in allotment of one step<br/> below type accomodation also, if eligible</span> : &emsp;
														<input type="radio" class="form-text rad-ip" name="rad_onestep_below_acc" id="rad_onestep_below_acc_y" value="Y" <?php if($OneStepBelowAllow == "Y"){ ?> checked="checked" <?php } ?>>&nbsp;Yes &emsp;
														<input type="radio" class="form-text rad-ip" name="rad_onestep_below_acc" id="rad_onestep_below_acc_n" value="N" <?php if($OneStepBelowAllow == "N"){ ?> checked="checked" <?php } ?>>&nbsp;No &emsp;
														<input type="hidden" name="txt_one_step_below" id="txt_one_step_below" value="<?php if($OneStepBelowAllow != ""){ echo $OneStepBelowAllow; } ?>" />
													</div>
												</div>
											</div>
											<div class="erow"></div>
											<div>
												<span class="dec-text">
													I hereby declare that the information furnished above is correct to the best of my knowledge and in case 
													it is found to be wrong/false at a later date the quarters allotted to me is liable to be cancelled. 
													I agree that I shall abide the Allottment of Government Residences (DAE-Kalpakkam) Rules 1980 and such 
													other rules and conditions as may be prescribed by the Competent Authority from time to time.
												</span>
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

