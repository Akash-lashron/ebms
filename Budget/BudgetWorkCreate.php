<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'TS / NIT / WORK Order';
$msg = "";
if(isset($_POST['btn_save']) == " Save "){
	$ParGroupArr 	= $_POST['cmb_group'];
	$NewGroupArr 	= $_POST['new_group'];
	$GroupType 		= $_POST['txt_type'];
	$CheckDisplay 	= $_POST['ch_display'];
	$ParCount 	 	= count($ParGroupArr);
	$ChiCount 	 	= count($NewGroupArr);
	$ParentId 	 	= $ParGroupArr[$ParCount-1];
	
	if($ParentId == "NEW"){
		if($ParCount == 1){
			$ParentId = 0;
		}else{
			$ParentId = $ParGroupArr[$ParCount-2];
		}
	}
	
	$NewGroup  	 	= $NewGroupArr[$ChiCount-1];
	$InsertQuery 	= "insert into group_datasheet set group_desc = '$NewGroup', type = '$GroupType', par_id = '$ParentId', disp = '$CheckDisplay', active = 1";
	$InsertSql 	 	= mysqli_query($dbConn,$InsertQuery);
	if($InsertSql == true){
		$msg = "New Group Created Successfully";
	}else{
		$msg = "Error : Group not created. Please try again.";
	}
}
//print_r($ChildArr);exit;
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<link rel='stylesheet' href='../Step-Wizard/BSMagic-min.css'/>
<script src='../Library/Step-Wizard/BSMagic-min.js'></script>
<script src='../Library/Step-Wizard/gsap.min.js'></script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="box-container">
							<div>&nbsp;</div>
								<div class="div12">
									<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div3 tab-menu-sec flex-child magenta">
												<div class="nav flex-column flex-grow-1 flex-fill nav-pills" role="tablist" aria-orientation="vertical">
													<div class="div-tab-head">Work Creation</div>
													<a class="nav-link active" id="v-pills-ts-tab" data-toggle="pill" href="#v-pills-ts" role="tab" aria-controls="v-pills-ts" aria-selected="true"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Technical Sanction</a>
													<a class="nav-link" id="v-pills-nit-tab" data-toggle="pill" href="#v-pills-nit" role="tab" aria-controls="v-pills-nit" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;NIT</a>
													<a class="nav-link" id="v-pills-wo-tab" data-toggle="pill" href="#v-pills-wo" role="tab" aria-controls="v-pills-wo" aria-selected="false"><i class="fa fa-caret-right" style="font-size:20px; font-weight:200;"></i>&nbsp;&nbsp;Work Order</a>
												</div>
											</div>
											<div class="div9 BSMagic flex-child green" style="margin-right:10px;" id="test">
												<div class="tab-content" id="v-pills-tabContent">
													<div class="tab-pane fade active tab-body-sec" id="v-pills-ts" role="tabpanel" aria-labelledby="v-pills-ts-tab">
														<div class="div-tab-head">Technical Sanction</div>
														<div class="div-tab-body">
															<!--<div class="erow"></div>-->
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			
																				<!--<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Project Title</div>
																				<div class="div9">
																					<select name="cmb_work_name" id="cmb_work_name" class="tboxsmclass" style="width:100%;">
																						<option value=""> ------------------- Select ----------------</option>
																					</select>
																				</div>-->
																				<div class="div3 lboxlabel">Name of Work</div>
																				<div class="div9"><textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass" style="width:100%" ></textarea></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS No.</div>
																				<div class="div6" align="left"><input type="text" name="txt_ts_no" id="txt_ts_no" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Amount (&#x20B9;)</div>
																				<div class="div6" align="left"><input type="text" name="txt_ts_amount" id="txt_ts_amount" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Date</div>
																				<div class="div6" align="left"><input type="date" name="txt_ts_date" id="txt_ts_date" class="tboxsmclass"></div>
																				
																				
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Approving Authority</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Discipline</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Plant</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																			</div>
																			<div class="row clearrow"></div>
																			<div class="row" align="center">
																				<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
																			</div>
																		</div>
																	
																</div>
															</div>
															<!--<div class="erow"></div>-->
														</div>
													</div>
													<div class="tab-pane fade tab-body-sec" id="v-pills-nit" role="tabpanel" aria-labelledby="v-pills-nit-tab">
														<div class="div-tab-head">NIT</div>
														<div class="div-tab-body">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<div class="row">
																				
																				<div class="div3 lboxlabel">Technical Sanction No.</div>
																				<div class="div9" align="left">
																					<select name="cmb_tender_no" id="cmb_tender_no" class="tboxsmclass">
																					<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Amount</div>
																				<div class="div3" align="left">
																					<input type="text" name="txt_entry_date" id="txt_entry_date" class="tboxsmclass">
																				</div>
																				<div class="div2 lboxlabel">TS Date</div>
																				<div class="div3" align="left">&nbsp;&nbsp;&nbsp;
																					<input type="date" name="txt_entry_date" id="txt_entry_date" class="tboxsmclass">
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Tender No.</div>
																				<div class="div9" align="left"><input type="text" name="txt_ts_number" id="txt_ts_number" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Name Of Work</div>
																				<div class="div9" align="left">
																					<textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass"></textarea>
																				</div>
																				
																				
																			</div>
																		</div>
								
																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">Tender Estimate (Rs.)</div>
																			<div class="div3" align="left"><input type="text" name="txt_tech_est" id="txt_tech_est" class="tboxsmclass"></div>
																			<div class="div3 lboxlabel">Cost of Tender</div>
																			<div class="div3">
																				<input type="text" name="txt_tender_cost" id="txt_tender_cost" class="tboxsmclass">
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">EMD (Rs.)</div>
																			<div class="div3" align="left"><input type="text" name="txt_emd_amt" id="txt_emd_amt" class="tboxsmclass"></div>
																			<div class="div3 lboxlabel">SD (% of Tender Value)</div>
																			<div class="div3">
																				<input type="text" name="txt_tender_value" id="txt_tender_value" class="tboxsmclass">
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">PBG (% of Tender Value)</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_tender_cost" id="txt_tender_cost" class="tboxsmclass">
																			</div>
																			<div class="div3 lboxlabel">Approving Authority</div>
																			<div class="div3">
																				<select name="cmb_app_auth" id="cmb_app_auth" class="tboxsmclass"  >
																					<option value=""> --- Select ---</option>
																				</select>
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">Time Allowed (Months)</div>
																			<div class="div3" align="left">
																				<select name="cmb_time_month" id="cmb_time_month" class="tboxsmclass"  >
																					<option value=""> --- Select ---</option>
																				</select>
																			</div>
																			<div class="row clearrow"></div>
																			
																													
																		<div class="row" align="center">
																			<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
																		</div>
																	</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="tab-pane fade tab-body-sec" id="v-pills-wo" role="tabpanel" aria-labelledby="v-pills-wo-tab" style="padding-bottom:0px;">
														<div class="div-tab-head">Work Order</div>
														<div class="div-tab-body" style="padding-bottom:0px;">
															
															<div class="row">
												<div class="div2 lboxlabel">Short Name</div>
												<div class="div10">
												<input type="text" class="divtbox" name='shortname' required id='shortname' value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel" style="line-height:35px;">Select Section</div>
												<div class="div3">
													<select name='section' id='section'>
														<option value="">-- Select Section --</option>
													</select>
												</div>
												<div class="div3 lboxlabel" align="right">&nbsp;&nbsp;Select Section Code</div>
												<div class="div4">
													<select name='section_code' id='section_code'>
														<option value="">-- Select Section Code --</option>
													</select>
												</div>
												<!--<div class="div2">
													<div class="inputGroup">
														<input id="section_civil" name="section" type="radio" value="I" <?php if($section == 'I'){ echo 'checked="checked"'; } ?>/>
														<label for="section_civil" style="padding:3px 0px; width:95%;" > &nbsp;CIVIL </label>
													</div>
												</div>
												<div class="div2">
													<div class="inputGroup">
														<input id="section_elect" name="section" type="radio" value="II" <?php if($section == 'II'){ echo 'checked="checked"'; } ?>/>
														<label for="section_elect" style="padding:3px 0px; width:95%"> &nbsp;ELECTRICAL </label>
													</div>
												</div>
												<div class="div2">
													<div class="inputGroup">
														<input id="section_mech" name="section" type="radio" value="III" <?php if($section == 'III'){ echo 'checked="checked"'; } ?>/>
														<label for="section_mech" style="padding:3px 0px; width:95%"> &nbsp;MECHANICAL </label>
													</div>
												</div>
												<div class="div2">
													<div class="inputGroup">
														<input id="section_mhe" name="section" type="radio" value="IV" <?php if($section == 'IV'){ echo 'checked="checked"'; } ?>/>
														<label for="section_mhe" style="padding:3px 0px; width:95%"> &nbsp;MHE </label>
													</div>
												</div>
												<div class="div2">
													<div class="inputGroup">
														<input id="section_acv" name="section" type="radio" value="V" <?php if($section == 'V'){ echo 'checked="checked"'; } ?>/>
														<label for="section_acv" style="padding:3px 0px; width:95%"> &nbsp;ACV </label>
													</div>
												</div>-->
												
												<div class="div12 grid-empty under_civil <?php echo $HideClass1; ?>"></div>
												<div class="div2 <?php echo $HideClass1; ?> under_civil lboxlabel" style="line-height:35px;">Civil Work Name</div>
												<!--<div class="div2 <?php echo $HideClass1; ?> under_civil">
													<div class="inputGroup">
														<input id="under_civil_yes" name="under_civil" type="radio" value="Y" <?php if($UnderCivilCheck == 'Y'){ echo 'checked="checked"'; } ?>/>
														<label for="under_civil_yes" style="padding:3px 0px; width:95%"> &nbsp;YES </label>
													</div>
												</div>
												<div class="div2 <?php echo $HideClass1; ?> under_civil">
													<div class="inputGroup">
														<input id="under_civil_no" name="under_civil" type="radio" value="N" <?php if($UnderCivilCheck == 'N'){ echo 'checked="checked"'; } ?>/>
														<label for="under_civil_no" style="padding:3px 0px; width:95%"> &nbsp;NO </label>
													</div>
												</div>-->
												<div class="div10 <?php echo $HideClass2; ?> under_civil">
													<!--<select name='civil_workorderno' id='civil_workorderno' <?php echo $DisableClass; ?>>-->
													<select name='civil_workorderno' id='civil_workorderno'>
														<option value="">-- Select Civil Work --</option>
													</select>
												</div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">Work Order No.</div>
												<div class="div3">
													<input type="text" class="divtbox" name='workorderno' required id='workorderno' value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_no; } ?>">
												</div>
												<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Technical Sanction No.</div>
												<div class="div4">
													<input type="text" class="divtbox" name='techsanctionno' required id='techsanctionno' value="<?php if($_GET['sheet_id'] != ''){ echo $tech_sanction; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">Agreement No.</div>
												<div class="div3">
													<input type="text" class="divtbox" name='agreementno' required id='agreementno' value="<?php if($_GET['sheet_id'] != ''){ echo $agree_no; } ?>">
												</div>
												<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Contractor Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp; </div>
												<div class="div4">
													<input type="text" class="divtbox" name='contractorname' required id='contractorname' value="<?php if($_GET['sheet_id'] != ''){ echo $name_contractor; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">CC No.</div>
												<div class="div3">
													<input type="text" class="divtbox" name='computercodeno' required id='computercodeno' value="<?php if($_GET['sheet_id'] != ''){ echo $computer_code_no; } ?>">
												</div>
												<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Work Order Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
												<div class="div4">
													<input type="text" class="divtbox" name='workorderdate' required id='workorderdate' value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_date; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">Date of Commence.</div>
												<div class="div3">
													<input type="text" class="divtbox" name='workcommencedate' required id='workcommencedate' value="<?php if($_GET['sheet_id'] != ''){ echo $work_commence_date; } ?>">
												</div>
												<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Duration of Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
												<div class="div1">
													<input type="text" class="divtbox" name='workduration' id='workduration' required onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="<?php if($_GET['sheet_id'] != ''){ echo $work_duration; } ?>">
												</div>
												<div class="div3" align="center"><span style="font-size:11px">Months</span> <span style="font-size:10px">(Max. 3 digit)</span></div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">Rebate ( % )</div>
												<div class="div3">
													<input type="text" class="divtbox" name='rebatepercent' max="100" required onKeyPress="return isPercentageValue(event,this);" id='rebatepercent' value="<?php if($_GET['sheet_id'] != ''){ echo $rebatepercent; } else { echo '0.00'; } ?>">
												</div>
												<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Scheduled Comp. Date</div>
												<div class="div4">
													<input type="text" class="divtbox" name='dateofcompletion' required id='dateofcompletion' readonly="" value="<?php if($_GET['sheet_id'] != ''){ echo $date_of_completion; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												
												<div class="div2 lboxlabel">Work Type</div>
												<div class="div3 no-padding-lr">
													<div class="inputGroup">
														<input id="worktype_major" name="worktype" type="radio" value="1" <?php if($worktype == '1'){ echo 'checked="checked"'; } ?>/>
														<label for="worktype_major" style="padding:3px 0px; width:99%"> &nbsp;MAJOR WORKS</label>
													</div>
												</div>
												<div class="div3" style="padding-left:10px;">
													<div class="inputGroup">
														<input id="worktype_minor" name="worktype" type="radio" value="2" <?php if($worktype == '2'){ echo 'checked="checked"'; } ?>/>
														<label for="worktype_minor" style="padding:3px 0px; width:95%"> &nbsp;MINOR WORKS</label>
													</div>
												</div>
												<div class="div12 grid-empty"></div>
												
												
												
												
											</div>
														</div>
													</div>
													
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
								
							</div>
							</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
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
.fade {
  opacity: 1;
 }
</style>
</html>
<style>
	.tboxclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 7px 4px;
	}
</style>
