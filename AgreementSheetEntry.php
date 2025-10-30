<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/functions.php';
checkUser();
$msg = '';
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
if (isset($_POST["submit"])){
    $workname 			= 	trim($_POST['workname']);
	$shortname 			= 	trim($_POST['shortname']);
	$section 			= 	trim($_POST['section']);
	$sectCode 			= 	trim($_POST['section_code']);
	$sectionabcd 		= 	$sectCode;
	$under_civil 		= 	trim($_POST['under_civil']);
	if($under_civil == 'Y'){
		$civil_workorderno 	= 	trim($_POST['civil_workorderno']);
	}else{
		$civil_workorderno = '';
	}
    $workorderno 		= 	trim($_POST['workorderno']);
    $techsanctionno 	= 	trim($_POST['techsanctionno']);
    $agreementno 		= 	trim($_POST['agreementno']);
    $contractorname 	= 	trim($_POST['contractorname']);
    $computercodeno 	= 	trim($_POST['computercodeno']);
    $workorderdate 		= 	dt_format(trim($_POST['workorderdate']));
	$commencedate 		= 	dt_format(trim($_POST['workcommencedate']));
	$workduration 		= 	trim($_POST['workduration']);
	$rebatepercent 		= 	trim($_POST['rebatepercent']);
	$schcompledate 		= 	dt_format(trim($_POST['dateofcompletion']));
	$worktype 			= 	trim($_POST['worktype']);
	/*if($section == "I"){ $sectionabcd = "SECTION - A"; }//CIVIL
	if($section == "II"){ $sectionabcd = "SECTION - B"; }//ELECTRICAL
	if($section == "III"){ $sectionabcd = "SECTION - C"; }//MECHANICAL
	if($section == "IV"){ $sectionabcd = "SECTION - D"; }//MHE
	if($section == "V"){ $sectionabcd = "SECTION - E"; }//ACV*/
    $sheet_sql 			= 	"INSERT INTO sheet set "
                            . "work_name = '$workname', "
							. "short_name = '$shortname', "
							. "section_abcd = '$sectionabcd', "
							. "under_civil_sheetid = '$civil_workorderno', "
                            . "work_order_no = '$workorderno', "
                            . "tech_sanction = '$techsanctionno', "
                            . "agree_no = '$agreementno', "
                            . "name_contractor = '$contractorname', "
                            . "computer_code_no = '$computercodeno', "
							. "work_order_date = '$workorderdate', "
							. "work_commence_date = '$commencedate', "
							. "work_duration = '$workduration', "
							. "rebate_percent = '$rebatepercent', "
							. "date_of_completion = '$schcompledate', "
							. "worktype = '$worktype', "
							. "section_type = '$section', "
                            . "active = 'x'";
    $sheet_query 	= 	mysql_query($sheet_sql);
	//echo $sheet_sql;exit;
    if($sheet_query == true){
        $msg = "Agreement Details Stored Successfully ";
		$success = 1;
    }else{
		$msg = " Agreement Details Not Saved. Error...!!! ";
	}
} 
if($_GET['sheet_id'] != "")
{
	$select_sheet_query 	= 	"select * from sheet WHERE sheet_id = ".$_GET['sheet_id'];
	$select_sheet_sql 		= 	mysql_query($select_sheet_query);
	if($select_sheet_sql == true) 
	{
		$List = mysql_fetch_object($select_sheet_sql);
		$work_order_no 		= 	$List->work_order_no;
		$work_name 			= 	$List->work_name; 
		$short_name 		= 	$List->short_name; 
		$tech_sanction 		= 	$List->tech_sanction;
		$name_contractor 	= 	$List->name_contractor;
		$agree_no 			= 	$List->agree_no;
		$computer_code_no 	= 	$List->computer_code_no;
		$worktype 			= 	$List->worktype;
		$rebatepercent 		= 	$List->rebate_percent;
		$work_order_date 	= 	dt_display($List->work_order_date);
		$work_commence_date = 	dt_display($List->work_commence_date);
		$date_of_completion = 	dt_display($List->date_of_completion);
		$work_duration 		= 	$List->work_duration;
		$section 			= 	$List->section_type;
		$sectionCode 		= 	$List->section_abcd;
		$civil_sheetid 		= 	$List->under_civil_sheetid;
		//echo $sectionCode;exit;
	}
}
if(isset($_POST['update']))
{
	$sheetid 			= 	trim($_POST['hid_sheetid']);
	
    $workname 			= 	trim($_POST['workname']);
	$shortname 			= 	trim($_POST['shortname']);
	$section 			= 	trim($_POST['section']);
	$sectCode 			= 	trim($_POST['section_code']);
	$sectionabcd 		= 	$sectCode;
	$under_civil 		= 	trim($_POST['under_civil']);
	if($under_civil == 'Y'){
		$civil_workorderno 	= 	trim($_POST['civil_workorderno']);
	}else{
		$civil_workorderno = '';
	}
    $workorderno 		= 	trim($_POST['workorderno']);
    $techsanctionno 	= 	trim($_POST['techsanctionno']);
    $agreementno 		= 	trim($_POST['agreementno']);
    $contractorname 	= 	trim($_POST['contractorname']);
    $computercodeno 	= 	trim($_POST['computercodeno']);
    $workorderdate 		= 	dt_format(trim($_POST['workorderdate']));
	$commencedate 		= 	dt_format(trim($_POST['workcommencedate']));
	$workduration 		= 	trim($_POST['workduration']);
	$rebatepercent 		= 	trim($_POST['rebatepercent']);
	$schcompledate 		= 	dt_format(trim($_POST['dateofcompletion']));
	$worktype 			= 	trim($_POST['worktype']);
	/*if($section == "I"){ $sectionabcd = "SECTION - A"; }//CIVIL
	if($section == "II"){ $sectionabcd = "SECTION - B"; }//ELECTRICAL
	if($section == "III"){ $sectionabcd = "SECTION - C"; }//MECHANICAL
	if($section == "IV"){ $sectionabcd = "SECTION - D"; }//MHE
	if($section == "V"){ $sectionabcd = "SECTION - E"; }//ACV*/
    $sheet_sql 			= 	"UPDATE sheet set "
                            . "work_name = '$workname', "
							. "short_name = '$shortname', "
							. "section_abcd = '$sectionabcd', "
							. "under_civil_sheetid = '$civil_workorderno', "
                            . "work_order_no = '$workorderno', "
                            . "tech_sanction = '$techsanctionno', "
                            . "agree_no = '$agreementno', "
                            . "name_contractor = '$contractorname', "
                            . "computer_code_no = '$computercodeno', "
							. "work_order_date = '$workorderdate', "
							. "work_commence_date = '$commencedate', "
							. "work_duration = '$workduration', "
							. "rebate_percent = '$rebatepercent', "
							. "date_of_completion = '$schcompledate', "
							. "worktype = '$worktype', "
							. "section_type = '$section' "
                            . "where sheet_id = '$sheetid'";
							//echo $sheet_sql;exit;
    $sheet_query 	= 	mysql_query($sheet_sql);
	if($sheet_query == true)
	{
		$msg = "Agreement Details Updated Sucessfully..!!";
		$success = 1;

	}
	else
	{
		$msg = "Agreement Details Not Updated";
	}
}
?>

<?php require_once "Header.html"; ?>
<script>
   	$(function(){
		$("#workorderdate").datepicker({
    		changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date,
            defaultDate: new Date,
		});
		$("#workcommencedate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            //maxDate: new Date,
            defaultDate: new Date,
        });
		$('#section').change(function() {
			var section = $(this).val();
			if((section != 'I')&&(section != '')){
				$('.under_civil').show();
			}else{
				$('.under_civil').hide();
			}
			$("#under_civil_no").attr('checked', true);
			$("#civil_workorderno").chosen("destroy");
			$("#civil_workorderno").val('');
			$("#civil_workorderno").chosen();
		});
		$('input[type=radio][name=under_civil]').change(function() {
			var under_civil = $(this).val(); 
			$("#civil_workorderno").chosen("destroy");
			var WoWidth = $("#civil_workorderno").width(); //alert(WoWidth);
			$("#civil_workorderno_chosen").css("width", WoWidth);
			$("#civil_workorderno").val('');
			if(under_civil == 'Y'){
				$("#civil_workorderno").prop('disabled', false);
			}else{
				$("#civil_workorderno").prop('disabled', true);
			}
			$("#civil_workorderno").chosen();
		});
		$(".save").click(function(event){
			var section = $('#section').val();
			var sectionCode = $('#section_code').val();
			var worktype = $('input[type=radio][name=worktype]:checked').val();
			if(section == ''){
				BootstrapDialog.alert("Please Select Section Name");
				event.preventDefault();
				return false;
			}else if(sectionCode == ''){
				BootstrapDialog.alert("Please Select Section Code");
				event.preventDefault();
				return false;
			}else if(worktype == undefined){
				BootstrapDialog.alert("Please Select Work Type");
				event.preventDefault();
				return false;
			}
			if((section != '')&&(section != 'I')){
				//var under_civil = $('input[type=radio][name=under_civil]:checked').val();
				var civil_workorder = $("#civil_workorderno").val();
				if(civil_workorder == ''){
					BootstrapDialog.alert("Please Select Civil Work Name");
					event.preventDefault();
					return false;
				}
				/*if(under_civil == 'Y'){
					var civil_workorder = $("#civil_workorderno").val();
					if(civil_workorder == ''){
						BootstrapDialog.alert("Please Select Civil Work Name");
						event.preventDefault();
						return false;
					}
				}*/
			}
		});
		
		$.fn.validateworkorderdateformat = function(event) {
			var wodate = $("#workorderdate").val(); 
			if(wodate !=""){ 
				if(isDate(wodate)==false){
					var a="Work Order Date format should be dd/mm/yyyy";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}
				if(isDate(wodate)==true){
					var a="";
					//$('#workorderdate_format').text(a);
				}
			}else{
				var a="";
				$('#workorderdate_format').text(a);
			}
		}
		$.fn.validatedateofcompletionformat = function(event) {
			var doc = $("#dateofcompletion").val(); 
			if(doc !=""){ 
				if(isDate(doc)==false){
					var a="Scheduled Completion Date format should be dd/mm/yyyy";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}
				if(isDate(doc)==true){
					var a="";
					//$('#dateofcompletion_format').text(a);
				}
			}else{
				var a="";
				//$('#dateofcompletion_format').text(a);
			}
		}
		$.fn.validatecommencementformat = function(event) {
			var wod = $("#workcommencedate").val(); 
			if(wod !=""){ 
				if(isDate(wod)==false){
					var a="Work Commence Date format should be dd/mm/yyyy";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}
				if(isDate(wod)==true){
					var a="";
					//$('#workcommencedate_format').text(a);
				}
			}else{
				var a="";
				//$('#workcommencedate_format').text(a);
			}
		}
		
		$.fn.checkDate = function(event) { 
			var dateofcompletion = $("#dateofcompletion").val();
			var workorderdate = $("#workorderdate").val();
			if((dateofcompletion != "") && (workorderdate != "")){  
				var d1 = workorderdate.split("/");
				var d2 = dateofcompletion.split("/");
				var woddate = new Date(d1[2], d1[1]-1, d1[0]);
				var docdate = new Date(d2[2], d2[1]-1, d2[0]);
				if(woddate>docdate){
					var a="Date of Completion should be greater than Work Order Date";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
					//$('#val_date').text(a);
				}
			}
		}
				
		$.fn.checkDate2 = function(event) { 
			var dateofcompletion = $("#dateofcompletion").val();
			var workorderdate = $("#workorderdate").val();
			var workcommencedate = $("#workcommencedate").val();
			if((dateofcompletion != "") && (workorderdate != "") && (workcommencedate != "")){  
				var d1 = workorderdate.split("/");
				var d2 = dateofcompletion.split("/");
				var d3 = workcommencedate.split("/");
				var woddate = new Date(d1[2], d1[1]-1, d1[0]);
				var docdate = new Date(d2[2], d2[1]-1, d2[0]);
				var dcmdate = new Date(d3[2], d3[1]-1, d3[0]);
				if(dcmdate<woddate){
					var a="Date of Commencement should be greater than or equal to Work Order Date";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else if(dcmdate > docdate){
					var a="Date of Commencement should be less than Completion Date";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
					//$('#val_date').text(a);
				}
			}
		}
				
		$.fn.FindSchduleDOC = function(event) { 
			var workduration = $("#workduration").val();
			//var workorderdate = $("#workorderdate").val();
			var workcommencedate = $("#workcommencedate").val();
			$("#dateofcompletion").val("");
			if((workduration != "") && (workcommencedate != "")){  
				var d1 = workcommencedate.split("/");
				workduration = Number(workduration);
				var woddate = new Date(d1[2], d1[1]-1+workduration, d1[0]-1);
				//var SchDOC = woddate.getDate() + '/' + (woddate.getMonth() + 1) + '/' +  woddate.getFullYear();
				var sDate 	= woddate.getDate();
				var sMonth 	= woddate.getMonth()+1;
				var sYear 	= woddate.getFullYear();
				if (sDate < 10){ sDate = '0' + sDate; }
    			if (sMonth < 10){ sMonth = '0' + sMonth; }
				var SchDOC = sDate + '/' + sMonth + '/' +  sYear;
				$("#dateofcompletion").val(SchDOC);
			}
		}
		$.fn.CheckRebatePercentage = function(event) {
			var rebate = $(this).val();
			if(Number(rebate)>100){
				BootstrapDialog.alert("Rebate percentage should be less than 100");
				$(this).val('0.00');
				event.preventDefault();
				event.returnValue = false;
			}else{
				var num = toFixed2DecimalNoRound(rebate,2);
				$(this).val(num);
			}
		}
		$("#workcommencedate").change(function(event){
			$(this).FindSchduleDOC(event);
		});	
		$("#workduration").keyup(function(event){
			$(this).FindSchduleDOC(event);
		});	
		$("#rebatepercent").change(function(event){
			$(this).CheckRebatePercentage(event);
		});	
		$("#workduration").keydown(function(e) {
			var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67; //alert(e.keyCode);
			if (ctrlDown || e.keyCode == vKey || e.keyCode == cKey){
				return false;
			}else{
				return true;
			}
		});
		$("#rebatepercent").keydown(function(e) {
			var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67; //alert(e.keyCode);
			if (ctrlDown || e.keyCode == vKey || e.keyCode == cKey){
				return false;
			}else{
				return true;
			}
		});
		$("#top").submit(function(event){
			$(this).checkDate(event);
			$(this).checkDate2(event);
			$(this).validateworkorderdateformat(event);
			$(this).validatedateofcompletionformat(event);
			$(this).validatecommencementformat(event);
		});
		/*$('input[name="section"]').click(function(){
			var section = $(this).val();
			$(".chosen-container").css("width", "465");
			if(section != ""){
				if(section != 'I'){
					$("#civil_workorderno").val('');
					$('#row1').show();
					$('#row2').show();
				}else{
					$("#civil_workorderno").val('');
					$('#row1').hide();
					$('#row2').hide();
				}
			}
		});
		$( "#workorderdate" ).datepicker({
        	changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            maxDate: new Date,
            defaultDate: new Date,
    	});	
		$("#shortname").keyup(function(event){
			$(this).validateshortname(event);
		});
		$("#top").submit(function(event){
			$(this).validateworkname(event);
		});*/
   	});
	function goBack(){
		url = "dashboard.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.chosen-container-single .chosen-single{
	height:30px !important;
	line-height: 25px;
}
.inputGroup label::after {
    width: 10px;
    height: 12px;
	top: 49%;
	right:20px;
}
.chosen-container{
	/*width:99% !important;*/
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Agreement Sheet Entry</div>
                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>
                        	<blockquote class="bq1" style="overflow:auto">
							
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Work Details Entry Form</div>
										<div class="row innerdiv group-div" align="center">
										
											<?php 
											$UnderCivilCheck = '';
											if($_GET['sheet_id'] != ''){ 
												if(($section != '')&&($section != 'I')){
													$HideClass1 = '';
													if($civil_sheetid != 0){
														$HideClass2 = '';
														$DisableClass = '';
														$UnderCivilCheck = 'Y';
													}else{
														$HideClass2 = '';
														$DisableClass = 'disabled="disabled"';
														$UnderCivilCheck = 'N';
													}
												}else{
													$HideClass1 = 'hide';
													$HideClass2 = 'hide';
													$DisableClass = 'disabled="disabled"';
												}
												
											}else{
												$HideClass1 = 'hide';
												$HideClass2 = 'hide';
												$DisableClass = 'disabled="disabled"';
											}
											
											//echo $section;exit;
											?>
											<div class="row">
												<div class="div2 lboxlabel" style="line-height:35px;">Name of Work</div>
												<div class="div10">
												<textarea name='workname' class="divtarea" id='workname' required rows="2"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Short Name</div>
												<div class="div10">
												<input type="text" class="divtbox" name='shortname' required id='shortname' value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>">
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel" style="line-height:35px;">Select Section</div>
												<div class="div3">
													<select name='section' id='section'>
														<option value="">-- Select Section --</option>
														<?php echo $objBind->BindSectionName($section);?>
													</select>
												</div>
												<div class="div3 lboxlabel" align="right">&nbsp;&nbsp;Select Section Code</div>
												<div class="div4">
													<select name='section_code' id='section_code'>
														<option value="">-- Select Section Code --</option>
														<?php echo $objBind->BindSectionCode($sectionCode);?>
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
														<?php echo $objBind->BindWorkOrderNoListStaff($civil_sheetid);?>
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
								</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
							
							
							
								<!--<div class="main-content">
									
									<?php 
									$UnderCivilCheck = '';
									if($_GET['sheet_id'] != ''){ 
										if(($section != '')&&($section != 'I')){
											$HideClass1 = '';
											if($civil_sheetid != 0){
												$HideClass2 = '';
												$DisableClass = '';
												$UnderCivilCheck = 'Y';
											}else{
												$HideClass2 = 'hide';
												$DisableClass = 'disabled="disabled"';
												$UnderCivilCheck = 'N';
											}
										}else{
											$HideClass1 = 'hide';
											$HideClass2 = 'hide';
											$DisableClass = 'disabled="disabled"';
										}
										
									}else{
										$HideClass1 = 'hide';
										$HideClass2 = 'hide';
										$DisableClass = 'disabled="disabled"';
									}
									
									//echo $section;exit;
									?>
									<div class="row">
										<div class="div2"></div>
										<div class="main-content div8 main-content-head">Work Details Entry Form</div>
										<div class="div2"></div>
										<div class="div2"></div>
										<div class="main-content div8 main-content-body">
										
										
									
										<div class="div2" style="line-height:35px;">Name of Work</div>
										<div class="div10">
										<textarea name='workname' id='workname' required rows="2" class="grid-textarea"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea>
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">Short Name</div>
										<div class="div10">
										<input type="text" name='shortname' required id='shortname' value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>">
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2" style="line-height:35px;">Select Section</div>
										<div class="div2">
											<div class="inputGroup">
												<input id="section_civil" name="section" type="radio" value="I" <?php if($section == 'I'){ echo 'checked="checked"'; } ?>/>
												<label for="section_civil" style="padding:3px 0px; width:95%;" > &nbsp;CIVIL </label>
											</div>
										</div>
										<div class="div3">
											<div class="inputGroup">
												<input id="section_elect" name="section" type="radio" value="II" <?php if($section == 'II'){ echo 'checked="checked"'; } ?>/>
												<label for="section_elect" style="padding:3px 0px; width:95%"> &nbsp;ELECTRICAL </label>
											</div>
										</div>
										<div class="div3">
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
										<div class="div12 grid-empty"></div>
										
										<div class="div2 <?php echo $HideClass1; ?> under_civil" style="line-height:35px;">Work Under Civil</div>
										<div class="div2 <?php echo $HideClass1; ?> under_civil">
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
										</div>
										<div class="div6 <?php echo $HideClass2; ?> under_civil">
											<select name='civil_workorderno' id='civil_workorderno' <?php echo $DisableClass; ?> style="width:99%">
												<option value="">-- Select Civil Work --</option>
												<?php echo $objBind->BindWorkOrderNoListStaff($civil_sheetid);?>
											</select>
										</div>
										<div class="div12 grid-empty <?php echo $HideClass1; ?> under_civil"></div>
										
										<div class="div2">Work Order No.</div>
										<div class="div3">
											<input type="text"  name='workorderno' required id='workorderno' value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_no; } ?>">
										</div>
										<div class="div3" align="center">Technical Sanction No.</div>
										<div class="div4">
											<input type="text" name='techsanctionno' required id='techsanctionno' value="<?php if($_GET['sheet_id'] != ''){ echo $tech_sanction; } ?>">
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">Agreement No.</div>
										<div class="div3">
											<input type="text" name='agreementno' required id='agreementno' value="<?php if($_GET['sheet_id'] != ''){ echo $agree_no; } ?>">
										</div>
										<div class="div3" align="center">Contractor Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp; </div>
										<div class="div4">
											<input type="text" name='contractorname' required id='contractorname' value="<?php if($_GET['sheet_id'] != ''){ echo $name_contractor; } ?>">
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">CC No.</div>
										<div class="div3">
											<input type="text" name='computercodeno' required id='computercodeno' value="<?php if($_GET['sheet_id'] != ''){ echo $computer_code_no; } ?>">
										</div>
										<div class="div3" align="center">Work Order Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
										<div class="div4">
											<input type="text" name='workorderdate' required id='workorderdate' value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_date; } ?>">
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">Date of Commence.</div>
										<div class="div3">
											<input type="text" name='workcommencedate' required id='workcommencedate' value="<?php if($_GET['sheet_id'] != ''){ echo $work_commence_date; } ?>">
										</div>
										<div class="div3" align="center">Duration of Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
										<div class="div1">
											<input type="text" name='workduration' id='workduration' required onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="<?php if($_GET['sheet_id'] != ''){ echo $work_duration; } ?>">
										</div>
										<div class="div3" align="center"><span style="font-size:11px">Months</span> <span style="font-size:10px">(Max. 3 digit)</span></div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">Rebate ( % )</div>
										<div class="div3">
											<input type="text" name='rebatepercent' max="100" required onKeyPress="return isPercentageValue(event,this);" id='rebatepercent' value="<?php if($_GET['sheet_id'] != ''){ echo $rebatepercent; } else { echo '0.00'; } ?>">
										</div>
										<div class="div3" align="center">Scheduled Comp. Date</div>
										<div class="div4">
											<input type="text" name='dateofcompletion' required id='dateofcompletion' readonly="" value="<?php if($_GET['sheet_id'] != ''){ echo $date_of_completion; } ?>">
										</div>
										<div class="div12 grid-empty"></div>
										
										<div class="div2">Work Type</div>
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
										<div class="div2"></div>
									</div>
									
								</div>-->
						
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <!--<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Choose Section</td> 
                                    <td class="label">
									<input type="radio"  name='section' id='section_civil' class="textboxdisplay" value="I">&nbsp;CIVIL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_elect' class="textboxdisplay" value="II">&nbsp;ELECTRICAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_mech' class="textboxdisplay" value="III">&nbsp;MECHANICAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_mhe' class="textboxdisplay" value="IV">&nbsp;MHE
									
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_section" style="color:red" colspan="">&nbsp;</td></tr>
								<tr id="row1" style="display:none">
                                    <td>&nbsp;</td>
                                    <td class="label">Works Under</td> 
                                    <td>
									<select name='civil_workorderno' id='civil_workorderno' class="textboxdisplay" style="width: 465px;">
										<option value="">-------------------- Select Work Short Name -----------------------</option>
										<?php echo $objBind->BindWorkOrderNoListStaff(0);?>
									</select>
									</td>
                                </tr>
                                <tr id="row2" style="display:none"><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_civil_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td> 
                                    <td><input type="text"  name='workorderno' id='workorderno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='workname' id='workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Short Name of Work</td>
                                    <td><input type="text" name='shortname' id='shortname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Technical Sanction No. </td>
                                    <td><input type="text" name='techsanctionno' id='techsanctionno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $tech_sanction; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_techsno" style="color:red" colspan="">&nbsp;</td></tr>
								 <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Agreement No.</td>
                                    <td> <input type="text" name='agreementno' id='agreementno' class="textboxdisplay"  value="<?php if($_GET['sheet_id'] != ''){ echo $agree_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr> 
                                    <td>&nbsp;</td>
                                    <td class="label">Name of the contractor</td>
                                    <td><input type="text" name='contractorname' id='contractorname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $name_contractor; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_conname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Computer Code No. </td>
                                    <td><input type="text" name='computercodeno' id='computercodeno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $computer_code_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Type </td>
                                    <td class="label">
									<?php 
									if($_GET['sheet_id'] != '')
									{ 	
										if($worktype == 1) 
										{ 
											$check1 = 'checked="checked"'; 
											$check2 = "";
										} 
										else
										{
											$check2 = 'checked="checked"'; 
											$check1 = "";
										}
									} 
									else
									{
										$check2 = 'checked="checked"'; 
										$check1 = "";
									}
									?>
										<input type="radio" name="worktype" id="worktype" value="1" <?php echo $check1; ?>>Major Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="worktype" id="worktype" value="2" <?php echo $check2; ?>>Minor Work
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_worktype" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order Date </td>
                                    <td><input type="text" name='workorderdate' id='workorderdate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_date; } ?>" size="15"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                             	<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rebate Percentage </td>
                                    <td><input type="text" name='rebatepercent' id='rebatepercent' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $rebatepercent; } else { echo 0; } ?>" size="5">&nbsp;&nbsp;( % )</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_rebatepercent" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
                            
                            </table>-->
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php 
										if($_GET['sheet_id'] != '')
										{ 
										?>
											<input type="submit" name="update" id="update" value=" Update " class="save"/>
										<?php
										}
										else
										{
										?>
											<input type="submit" name="submit" id="submit" value=" Submit " class="save"/>
										<?php
										}
										?>
										</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
		   <script>
		    	//$("#civil_workorderno").chosen();
				$("#civil_workorderno").chosen();
				$("#section").chosen();
				$("#section_code").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
