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
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script src="handsontable/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="handsontable/handsontable/dist/handsontable.full.min.css">
<link href="css/CustomFancyStyle.css" rel="stylesheet">
<!--<link rel='stylesheet' href='Step-Wizard/bootstrap.min.css'/>-->
<link rel='stylesheet' href='Step-Wizard/BSMagic-min.css'/>
<script src='Library/Step-Wizard/BSMagic-min.js'></script>
<script src='Library/Step-Wizard/gsap.min.js'></script>

<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title-new"></div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="PartPaymentPageView.php">
                            <div class="div12">
								
								
								<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div3 tab-menu-sec flex-child magenta">
												<div class="nav flex-column flex-grow-1 flex-fill nav-pills" role="tablist" aria-orientation="vertical">
													<div class="div-tab-head">Work Creation</div>
													<a class="nav-link active" id="v-pills-application-type-tab" data-toggle="pill" href="#v-pills-application-type" role="tab" aria-controls="v-pills-application-type" aria-selected="true"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Technical Sanction</a>
													<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;NIT</a>
													<a class="nav-link" id="v-pills-home2-tab" data-toggle="pill" href="#v-pills-home2" role="tab" aria-controls="v-pills-home2" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Department Estimate</a>
													<a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Bidders's Price Bid</a>
													<a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Negotiation</a>
													<a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;LOA</a>
													<a class="nav-link" id="v-pills-submit-tab" data-toggle="pill" href="#v-pills-submit" role="tab" aria-controls="v-pills-submit" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Work Order</a>
												</div>
											</div>
											<div class="div9 BSMagic flex-child green" style="margin-right:10px;" id="test">
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
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
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
													</div>-->
													
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-home2" role="tabpanel" aria-labelledby="v-pills-home2-tab" style="padding-bottom:0px;">
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
																		
																	</div>
																	<div class="col-sm-12" style="font-size:11px; padding:0px;"><span style="color:#FE070E; font-weight:bold;">( # )</span> Opt 'Yes' if directly recruited as 'Scientific / Technical Officer'</div>
																</div>
																
																
																
																
																
															</div>
														</div>
													</div>-->
													
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
														<div class="div-tab-head">Level in Pay Matrix Details Entry</div>
														<div class="div-tab-body">
															<div class="erow"></div>
															<div class="row">
																<div class="col-sm-4">
																	<div class="form-label" align="left">Present Level</div>
																	<div class="input-group">
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
													</div>-->
													
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
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
													</div>-->
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
														<div class="div-tab-head">Contact Details Entry</div>
														<div class="div-tab-body">
															<div class="erow"></div>
															
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
													</div>-->
													<!--<div class="tab-pane fade tab-body-sec" id="v-pills-submit" role="tabpanel" aria-labelledby="v-pills-submit-tab">
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
													</div>-->
													
												</div>
											</div>
										</div>
									</div>
				</div>
								
								
							</div>
							<div style="text-align:center">
								<div class="buttonsection" style="display:inline-table">
									<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
								</div>
								<div class="buttonsection" style="display:inline-table">
									<input type="submit" class="btn" data-type="submit" value=" Save " name="submit" id="submit"   />
								</div>
							</div>
							
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
		
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
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
</script>
<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.nav-link {
  display: block;
  padding: 12px 15px;
  color: #062BF7;
  border: 1px solid #F2F4F8;
  font-weight:600;
}
.nav-pills .nav-link {
  border-radius: .25rem;
}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
  color: #0343BB;
  background-color: #fff;
}
.BSMagic .nav-link.active {
  background-color: transparent !important;
}
</style>
</body>
</html>

