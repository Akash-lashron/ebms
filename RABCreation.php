<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "sysdate.php";
$msg = '';
$_SESSION["newmbookno"]='';
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
$popupwindow =0;
if($_POST["btn_generate"] == "Save") 
{
	$staffid 			= $_SESSION['sid'];
    $sheet_id 			= trim($_POST['wordorderno']);
    $rbn 				= trim($_POST['rbnno']);
	$RabForArr 			= $_POST['ch_rab_for'];
	$FromDate 			= dt_format($_POST['txt_from_date']);
	$ToDate 			= dt_format($_POST['txt_to_date']);
	$RabExist = 0; $RabStatus = ""; $FBillPrevStatus = "";
	$SelectQuery = "select * from abstractbook where sheetid = '$sheet_id' and rbn = '$rbn'";
	$SelectSql   = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			$RabExist = 1;
			$RabList = mysql_fetch_object($SelectSql);
			$RabStatus = $RabList->rab_status;
			$FBillPrevStatus = $RabList->is_final_bill;
		}
	}
	$IsRabStr = ""; $IsSecAdvStr = ""; $IsMobAdv = ""; $IsEscStr = ""; $IsFBStr = "";
	if(count($RabForArr)>0){
		foreach($RabForArr as $Key => $Value){
			if($Value == "MS"){ $IsRabStr = "Y"; }
			if($Value == "SA"){ $IsSecAdvStr = "Y"; }
			if($Value == "MA"){ $IsMobAdv = "Y"; }
			if($Value == "ES"){ $IsEscStr = "Y"; }
			if($Value == "FB"){ 
				$IsFBStr = "Y"; 
			}
		}
	}
	if($IsRabStr == ""){
		$RabFlag = "ZM";
	}else{
		$RabFlag = "";
	}
	
	if($RabExist == 0){
		$InsertQuery 	= "insert into abstractbook set abs_book_date = NOW(), sheetid = '$sheet_id', rbn = '$rbn', fromdate = '$FromDate', todate = '$ToDate', is_rab = '$IsRabStr', is_final_bill = '$IsFBStr', is_sec_adv = '$IsSecAdvStr', is_mob_adv = '$IsMobAdv', is_esc = '$IsEscStr', rab_status = 'P', rab_flag = '$RabFlag', staffid = '$staffid', active = 1";
		$InsertSql   	= mysql_query($InsertQuery);
	}else{
		if(($RabExist == 1)&&($RabStatus != 'C')){
			$InsertQuery 	= "update abstractbook set abs_book_date = NOW(), sheetid = '$sheet_id', rbn = '$rbn', fromdate = '$FromDate', todate = '$ToDate', is_rab = '$IsRabStr', is_final_bill = '$IsFBStr', is_sec_adv = '$IsSecAdvStr', is_mob_adv = '$IsMobAdv', is_esc = '$IsEscStr', rab_status = 'P', rab_flag = '$RabFlag', staffid = '$staffid', active = 1 where sheetid = '$sheet_id' and rbn = '$rbn'";
			$InsertSql   	= mysql_query($InsertQuery);
		}
	}
	//echo $InsertQuery;exit;
	//echo $_POST['txt_comp_cert_content'];exit;
	$ch_final_bill 		= trim($_POST['ch_final_bill']);
	if($IsFBStr != ""){
		$act_doc 	= trim($_POST['txt_act_doc']);
		$act_doc	= dt_format($act_doc);
		$CompCertContent = trim($_POST['txt_comp_cert_content']);
	}else{
		$act_doc	= "";
		$CompCertContent = "";
	}
	$Maxdate 		= "";
	$select_maxdate_query	= "SELECT DATE_FORMAT(max(todate),'%Y-%m-%d') as max_date FROM measurementbook WHERE sheetid = '$sheet_id'";
	$select_maxdate_sql 	= mysql_query($select_maxdate_query);
	if($select_maxdate_sql == true){
		if(mysql_num_rows($select_maxdate_sql)>0){
			$MaxDtList 	= mysql_fetch_object($select_maxdate_sql);
			$Maxdate 	= $MaxDtList->max_date;
		}
	}
	if(($IsFBStr == "Y")||($FBillPrevStatus == "Y")){
		$update_doc_query 	= "update sheet set act_doc = '$act_doc', comp_cert_desc = '$CompCertContent' where sheet_id = '$sheet_id'";
		$update_doc_sql 	= mysql_query($update_doc_query);
		$MesCount = 0;
		/*$select_count_query = "select mbheaderid from mbookheader where sheetid = '$sheetid' and date > = '$Maxdate'";
		$select_count_sql 	= mysql_num_rows($select_count_query);
		if($select_count_sql == true){
			$MesCount = mysql_num_rows($select_count_sql);
		}
		
		if($MesCount == 0){
			$MeasExist = "NO";
		}else{
			$MeasExist = "YES";
		}*/
	}
	//echo $update_doc_query." = ".$FBillPrevStatus;
	/*$RABTransacSatus = "RAB - ".$rbn." Created";
	//UpdateWorkTransaction($sheet_id,$rbn,"R",$RABTransacSatus,"");
	//echo $Maxdate;exit;
	//$res1 = @mysql_result($rs1,'fromdate');
	$_SESSION["sheet_id"] 	= $sheet_id;  
	$_SESSION["rbn"] 		= $rbn;
	$_SESSION["MaxdateDB"] 	= $Maxdate;
	$_SESSION["MaxdateDPL"] = dt_display($Maxdate);
	$_SESSION["final_bill"] = $ch_final_bill; 
	header('Location: MBookGenerateSection1.php?varid=1');    
	*/    
	//header('Location: MBook_Staff_Wise.php?varid=1');
	if($InsertSql == true){
		$msg = "RAB created successfully";
		$success = 1;
	}else{
		$msg = "Error : RAB not created";
		$success = 0;
	}
	//exit;
} 
?>
<?php require_once "Header.html"; ?>
<style type="text/css">
	.ui-datepicker-header {
		background-color: #20b2aa;
		color: #e0e0e0;
		border-width: 1px 1px 1px 1px;
		border-style: solid;
		border-color: #990000;
	}
	.ui-datepicker-calendar .ui-state-active {
		background: #6eafbf;
	}
	.hidden{
    	visibility: hidden;
	}
	.shown{
		visibility: visible;
	}
	/*.hide{
		display:none;
	}*/
</style>
<script>
$(function () {
	$( ".datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: '-100:+0',
		maxDate: new Date,
		defaultDate: new Date,
	});
	/*$.fn.validateworkorder = function(event) { 
		if($("#wordorderno").val()==""){ 
			var a="Please select the work order number";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}*/
	/*$.fn.validaterbn = function(event) { 
		if($("#rbnno").val()==""){ 
			var a="Please Enter RAB Number";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}else if($("#rbnno").val()==0){ 
			var a="Please Enter Valid RAB Number";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}else if($("#rbnno").val()< 0){ 
			var a="Please Enter Valid RAB Number";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else
		{
			var a = '';
			$('#rbn_error').text(a);
			var CurrRbn 	= $("#rbnno").val();
			var MaxRbn 		= $("#txt_rbn_list").val();
			var RunnRbnList = $("#txt_rbn_list2").val();
			if(Number(CurrRbn) <= Number(MaxRbn)){
				//// Entered RAB Already CLosed
				a = 'Entered RAB Already CLosed';
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var i,j; 
				if(RunnRbnList != ''){
					var SplitRunnRbnList = RunnRbnList.split(","); 
					for(i=0; i<SplitRunnRbnList.length; i++){
						if(CurrRbn < SplitRunnRbnList[i]){
							//// Pass Order Not Confirmed
							a = "RAB "+SplitRunnRbnList[i]+" Already Generated. You can't generate RAB "+CurrRbn;
							BootstrapDialog.alert(a);
							event.preventDefault();
							event.returnValue = false;
						}else if(CurrRbn > SplitRunnRbnList[i]){
							var Diff = CurrRbn - SplitRunnRbnList[i];
							a = 'Previous RAB Pass Order Not Confirm';
							BootstrapDialog.alert(a);
							event.preventDefault();
							event.returnValue = false;
						}
					}
				}else{
					var Diff = CurrRbn - MaxRbn;
					if(Diff > 1){
						swal({
							title: "",
							text: "You have not generated your RAB No : "+(CurrRbn-1)+". Are you sure want to continue ?",
							type: "",
							showCancelButton: true,
							confirmButtonClass: "btn-danger",
							confirmButtonText: " Yes ",
							cancelButtonText: " Cancel ",
							closeOnConfirm: true,
							closeOnCancel: true
						},
						function(isConfirm) {
							if (isConfirm) {
								return true;
							} else {
								event.preventDefault();
								event.returnValue = false;
							}
						});
					}
				}
			}
		}
	}*/
	/*$.fn.validateActDoc = function(event) { 
		if($('#ch_final_bill').is(":checked")){
			if($("#txt_act_doc").val()==""){ 
				var a="Please enter actual date of completion";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_act_doc').text(a);
			}
		}else{ 
			$("#txt_act_doc").val();
			var a="";
			$('#val_act_doc').text(a);
		}
	}*/
	/*$.fn.validateRabFor = function(event) { 
		if($("[name='ch_confirm[]']:checked").length == 0){ 
			var a="Please select atleast one option for RAB";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}
	}*/
	/*$("#wordorderno").change(function(event){
		$(this).validateworkorder(event);
	});
	$("#rbnno").change(function(event){
		$(this).validaterbn(event);
	});
	$("#txt_act_doc").change(function(event){
		$(this).validateActDoc(event);
	});
	$(".RabFor").click(function(event){
		//$(this).validateRabFor(event);
	});*/
	
	
	$("#ch_final_bill").click(function(event){
		if($(this).is(":checked")){
			$(".fbrow").removeClass("hide");
		}else{
			$(".fbrow").addClass("hide");
		}
	});
	$("#ch_rab_for_meas").click(function(event){
		if($(this).is(":checked")){
			$(".msrow").removeClass("hide");
		}else{
			$(".msrow").addClass("hide");
		}
	});
	
	
	
	//$("#top").submit(function(event){
		//$(this).validateworkorder(event);
		//$(this).validaterbn(event);
		//$(this).validateActDoc(event);
		//$(this).validateRabFor(event);
	//});
});

function func_find_rbn()
{
	var xmlHttp;
	var data;
	document.form.txt_rbn_list.value = "";
	document.form.txt_rbn_list2.value = "";
	if (window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) // For Internet Explorer
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL = "find_checkrbn.php?workordernumber=" + document.form.wordorderno.value;
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function ()
	{
		if(xmlHttp.readyState == 4)
		{
			data = xmlHttp.responseText; //alert(data);
			var SplitData = data.split("@@");
			if ((SplitData[0] != "")||(SplitData[1] != "")){
				document.form.txt_rbn_list.value = SplitData[0];
				document.form.txt_rbn_list2.value = SplitData[1];
			}else{
				document.form.txt_rbn_list.value = "";
				document.form.txt_rbn_list2.value = "";
			}
		}
	}
	xmlHttp.send(strURL);
}



function goBack()
{
	url = "MyView.php";
	window.location.replace(url);
}
window.history.forward();
function noBack(){ 
	window.history.forward(); 
}
</script>
<style>
	.paddlr2{
		padding-left:2px; padding-right:2px;
	}
	.inputGroup label::after {
		width: 7px;
		height: 10px;
		right: 8px;
	}
	.IpGr{
		background:#F2F5F6;
	}
	.IpGr label{
		background:#F2F5F6;
		color:#E81A66;
	}
	.IpGr label::after {
		width: 0px;
		height: 0px;
		right: 0px;
		border: 0px solid #C4C4C4;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <?php include "Menu.php"; ?>
       
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">New RAB Creation </div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                            <div class="container">
								<div class="row clearrow"></div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead" align="center">Running Account Bill Details</div>
										<div class="row innerdiv" align="center">
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;Work Short Name</div>
												<div class="div8">
													<select name="wordorderno" id="wordorderno"  class="tboxsmclass DateCheck" tabindex="1" onChange="func_find_rbn();">
                                        				<option value=""> --------------- Select --------------- </option>
														<?php echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
                                            		</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;Work Order No.</div>
												<div class="div8">
													<input type="text" name="txt_workorder_no" id="txt_workorder_no" tabindex="2" class="tboxsmclass" readonly="">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;RAB No.</div>
												<div class="div2">
													<input type="number" name="rbnno" id="rbnno" readonly="" class="tboxsmclass" tabindex="4"/>
												</div>
												<div class="div6 lboxlabel" id="RabStatus"></div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<!--<div class="div4 lboxlabel" align="left">&nbsp;RAB For</div>-->
												<div class="div12" align="left" style="box-sizing:border-box;">
													<!--<div class="div1 lboxlabel" align="left">&nbsp;RAB For</div>-->
													<div class="div1">
														<div class="inputGroup IpGr">
															<input id="rab_for" type="checkbox" value="1" disabled="disabled"/>
															<label for="rab_for" style="padding:3px 0px; width:99%"> &nbsp;RAB For</label>
														</div>
													</div>
													<div class="div2">
														<div class="inputGroup paddlr2">
															<input id="ch_rab_for_meas" name="ch_rab_for[]" type="checkbox" value="MS"/>
															<label for="ch_rab_for_meas" style="padding:3px 0px; width:99%"> &nbsp;Measurements</label>
														</div>
													</div>
													<div class="div2">
														<div class="inputGroup paddlr2">
															<input id="ch_rab_for_sec" name="ch_rab_for[]" type="checkbox" value="SA"/>
															<label for="ch_rab_for_sec" style="padding:3px 0px; width:99%"> &nbsp;Secured Adv.</label>
														</div>
													</div>
													<div class="div3">
														<div class="inputGroup paddlr2">
															<input id="ch_mob_adv" name="ch_rab_for[]" type="checkbox" value="MA"/>
															<label for="ch_mob_adv" style="padding:3px 0px; width:99%"> &nbsp;Mobilization Advance</label>
														</div>
													</div>
													<div class="div2">
														<div class="inputGroup paddlr2">
															<input id="ch_rab_for_esc" name="ch_rab_for[]" type="checkbox" value="ES"/>
															<label for="ch_rab_for_esc" style="padding:3px 0px; width:99%"> &nbsp;Escalation</label>
														</div>
													</div>
													<div class="div2">
														<div class="inputGroup paddlr2">
															<input id="ch_final_bill" name="ch_rab_for[]" type="checkbox" value="FB"/>
															<label for="ch_final_bill" style="padding:3px 0px; width:99%"> &nbsp;Final Bill</label>
														</div>
													</div>
													
												</div>
											</div>
											<div class="row clearrow hide msrow"></div>
											<div class="row hide msrow">
												<div class="div4 lboxlabel" align="left">&nbsp;Measurements From Date</div>
												<div class="div2">
													<input type="text" name="txt_from_date" id="txt_from_date" readonly="" class="tboxsmclass datepicker MDates DateCheck" tabindex="5"/>
												</div>
												<div class="div2 rboxlabel">
													To Date&nbsp;&nbsp;
												</div>
												<div class="div2">
													<input type="text" name="txt_to_date" id="txt_to_date" readonly="" class="tboxsmclass datepicker MDates DateCheck" tabindex="5"/>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row hide fbrow">
												<div class="div4 lboxlabel" align="left">&nbsp;Actual Completion Date</div>
												<div class="div2">
													<input type="text" name="txt_act_doc" id="txt_act_doc" class="tboxsmclass datepicker" tabindex="5"/>
													<input type="hidden" name="txt_max_dt_str" id="txt_max_dt_str" class="tboxsmclass" tabindex="5"/>
													<input type="hidden" name="txt_max_dt_dp" id="txt_max_dt_dp" class="tboxsmclass" tabindex="5"/>
													<input type="hidden" name="txt_max_dt_flag" id="txt_max_dt_flag" class="tboxsmclass" tabindex="5"/>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row hide fbrow">
												<div class="div12 lboxlabel" align="left">&nbsp;Completion Certificate</div>
												<div class="div12 lboxlabel one-mar-top">
													<?php $Content = " Certified that the work has been physically completed within the date due according to the contract i.e. <span id='compCertDate' style='font-weight:bold'></span> and that no defects are apparent and the contractor has removed from the premises on which the work was being executed all the scaffolding, surplus materials and rubbish and cleaned all the dirt from all woodwork, doors, windows, walls floors or other parts of the building, in upon or about which the work was to be executed or of which he had possession for the purpose of execution thereof. This is however, subject to the measurement being recorded and quality being checked by the competent authority."; ?>					
													<div contenteditable="true" style="white-space:normal; text-align:justify; width:100%; border:2px solid #465666; border-radius:5px; padding:5px; font-weight:500;box-sizing: border-box;" id="CompCertContent"><?php echo $Content; ?></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div2" align="center">&nbsp;</div>
                            </div>
							<input type="hidden" name="txt_rbn_list" id="txt_rbn_list">
							<input type="hidden" name="txt_rbn_list2" id="txt_rbn_list2">
							<input type="hidden" name="txt_comp_cert_content" id="txt_comp_cert_content">
							<input type="hidden" name="txt_comp_cert_content_static" id="txt_comp_cert_content_static" value="<?php echo $Content; ?>">
							<input type="hidden" name="txt_work_commence_dt" id="txt_work_commence_dt">
							<div class="row div12" align="center">
								<!--<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>-->
								<div class="buttonsection" style="width:110px">
								<input type="submit" class="btn" data-type="submit" value="Save" name="btn_generate" id="btn_generate"/>
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
$("#wordorderno").chosen();
$(function () {
	
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	var RabStatus = 0;
	function DisplayRBNDetails() {
		var wordordernovalue = $("#wordorderno option:selected").attr('value');
		$.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) { 
			 var workname = data.split("*");
			 //$("#workname").text(workname[0]);
			 $("#txt_workorder_no").val(workname[2]);
			 $("#txt_work_commence_dt").val(workname[3]);
		});
	}
	 function DisplayComplCertDesc() {
		var wordordernovalue = $("#wordorderno option:selected").attr('value');
		$.ajax({
			type: 'POST', 
			url: 'find_comp_cert_desc.php', 
			data: { sheetid: wordordernovalue }, 
			dataType: 'json',
			success: function (data) { //alert(data);
				if(data != null){
					$.each(data, function(index, element) {
						$("#txt_comp_cert_content").val(element.comp_cert_desc);
						if(element.comp_cert_desc == ""){
							var CompCertContent = $("#txt_comp_cert_content_static").val();
							$("#CompCertContent").html(CompCertContent);
						}else{
							$("#CompCertContent").html(element.comp_cert_desc);
						}
					});
				}
			}
		});
	}
	
	function WorksData(){ 
		var WorkId   = $("#wordorderno").val();
		$("#RabStatus").html("");
		$("#rbnno").attr("readonly", true); 
		RabStatus = 0;
		if(WorkId != ''){
			$.ajax({
				type: 'POST', 
				url: 'FindRabStatusForCreate.php', 
				data: { WorkId: WorkId }, 
				dataType: 'json',
				success: function (data) { //alert(data);
					if(data != null){
						if((data['LastestRbn'] != null)&&(data['LastestRbn'] != '')){
							var LatestRbn = data['LastestRbn'];
						}
						if((data['RbnStatus'] != null)&&(data['RbnStatus'] != '')){
							var RbnStatus = data['RbnStatus'];
							if(RbnStatus == "C"){
								// Allow to create new RAB
								$("#RabStatus").html("<div style='color:green'>&nbsp;Prevoius Completed RAB is : "+LatestRbn+"</div>");
								$("#rbnno").val(Number(LatestRbn)+1);
								
								RabStatus = 1;
							}else if(RbnStatus == "PA"){
								// Don't allow to create new RAB because RAB under process in accounts section
								$("#RabStatus").html("<div style='color:red'>&nbsp;RAB : "+LatestRbn+" is Under Process in Accounts Section</div>");
								RabStatus = 3;
							}else if(RbnStatus == "PU"){
								// Allow for only LatestRbn and Don't allow to create new RAB because RAB under process in user section
								$("#rbnno").val(LatestRbn);
								RabStatus = 2;
							}else{
								if(LatestRbn == "N"){
									// Allow to create new RAB
									$("#rbnno").attr("readonly", false); 
									$("#RabStatus").html("<div style='color:green'>&nbsp;No Previous RAB Found</div>");
									RabStatus = 1;
								}
							}
						}
					}
				}
			});
		}
	}
	
	
	$("#wordorderno").bind("change", function () {  
		$('#rbn_error').text(""); 
		$("#rbnno").val("");
		$("#txt_rbn_list2").val("");
		$("#txt_rbn_list2").val("");
		DisplayRBNDetails();
		DisplayComplCertDesc();
		WorksData();
	});
	$("#txt_act_doc").bind("change", function () { 
		var actDoc = $(this).val();
		$("#compCertDate").html(actDoc);
		if(actDoc != ''){
			var d1 = FromDate.split("/");
			var ActCmpDtStr = new Date(d1[2], d1[1]-1, d1[0]);
			var MaxDateStr = $("#txt_max_dt_str").val();
			var DateFlag = $("#txt_max_dt_flag").val();
			var MaxDateStrDp = $("#txt_max_dt_dp").val();
			if(ActCmpDtStr < MaxDateStr){
				if(DateFlag == "A"){
					BootstrapDialog.alert("Invalid Date. Actual completion date should be greater than Previous Measurement Date ("+MaxDateStrDp+")");
				}else if(DateFlag == "W"){
					BootstrapDialog.alert("Invalid Date. Actual completion date should be greater than Work Order Date ("+MaxDateStrDp+")");
				}
			}
		}
	});
	//$(".MDates").bind("change", function () {
	$('.MDates').on('change', function(event){ 
		var FromDate = $("#txt_from_date").val();
		var ToDate 	 = $("#txt_to_date").val();
		if((FromDate != '')&&(ToDate != '')){
			var d1 = FromDate.split("/");
			var d2 = ToDate.split("/");
			var FromDateStr = new Date(d1[2], d1[1]-1, d1[0]);
			var ToDateStr 	= new Date(d2[2], d2[1]-1, d2[0]);
			if(FromDateStr > ToDateStr){
				$(this).val('');
				BootstrapDialog.alert("From Date should be greater than To Date");
				event.preventDefault();
				event.returnValue = false;
			}
		}
	});
		
	//$("#cmb_shortname").bind("change", function(){ 
	$('body').on("change",".DateCheck", function(event){ 
		var WorkId   = $("#wordorderno").val();
		var FromDate = $("#txt_from_date").val();
		var ToDate 	 = $("#txt_to_date").val();
		$("#txt_max_dt_str").val('');
		$("#txt_max_dt_flag").val('');
		$("#txt_max_dt_dp").val('');
		if(((FromDate != '')||(ToDate != ''))&&(WorkId != '')){
			$.ajax({
				type: 'POST', 
				url: 'FindMaxMeasDate.php', 
				data: { WorkId: WorkId, FromDate:FromDate, ToDate:ToDate }, 
				dataType: 'json',
				success: function (data) { //alert(data);
					if(data != null){
						if((data['max_date'] != null)&&(data['max_date'] != '')){
							var MaxDate  = data['max_date'];
							var DateFlag = data['date_flag'];
							var d1 = FromDate.split("/");
							var d2 = ToDate.split("/");
							var d3 = MaxDate.split("-");
							var FromDateStr = new Date(d1[2], d1[1]-1, d1[0]);
							var ToDateStr 	= new Date(d2[2], d2[1]-1, d2[0]);
							var MaxDateStr 	= new Date(d3[0], d3[1]-1, d3[2]);
							var MaxDateStrDp = d3[2]+"/"+d3[1]+"/"+d3[0];
							$("#txt_max_dt_str").val(MaxDateStr);
							$("#txt_max_dt_flag").val(DateFlag);
							$("#txt_max_dt_dp").val(MaxDateStrDp);
							if(FromDateStr < MaxDateStr){
								if(DateFlag == "A"){
									BootstrapDialog.alert("From Date should be greater than Previous Measurement Date ("+MaxDateStrDp+")");
								}else if(DateFlag == "W"){
									BootstrapDialog.alert("From Date should be greater than Work Order Date ("+MaxDateStrDp+")");
								}
								$("#txt_from_date").val('')
								
							}else if(ToDateStr < MaxDateStr){
								if(DateFlag == "A"){
									BootstrapDialog.alert("To Date should be greater than Previous Measurement Date ("+MaxDateStrDp+")");
								}else if(DateFlag == "W"){
									BootstrapDialog.alert("To Date should be greater than Work Order Date ("+MaxDateStrDp+")");
								}
								$("#txt_to_date").val('')
							}
						}
					}
				}
			});
		}
	});
	
	/*$('body').on("change","#rbnno", function(event){ 
		var Rbn  = $(this).val();
		var WorkId   = $("#wordorderno").val();
		if(Rbn != ''){
			$.ajax({
				type: 'POST', 
				url: 'FindRabStatusForCreate.php', 
				data: { WorkId: WorkId, Rbn: Rbn }, 
				dataType: 'json',
				success: function (data) { //alert(data);
					if(data != null){
						var Data1 = data['data1'];
						var Data2 = data['data2'];
						
						var Err = 0;
						if(Data1 != null){
							if(Data1['acc_status'] == "P"){ 
								BootstrapDialog.alert("RAB ("+Rbn+") is under process in Accounts");
								Err++;
								$("#rbnno").val('');
							}else if(Data1['acc_status'] == "C"){ 
								BootstrapDialog.alert("RAB ("+Rbn+") process is completed in Accounts");
								Err++;
								$("#rbnno").val('');
							}else if((Data1['civil_status'] == "C")&&(Data1['acc_status'] == "")){ 
								BootstrapDialog.alert("RAB ("+Rbn+") is under process in Accounts");
								Err++;
								$("#rbnno").val('');
							}
						}
						if(Err == 0){
							if(Data2 != null){
								if(Data2['acc_status'] == "P"){
									var AccRbn = Data2['rbn'];
									BootstrapDialog.alert("RAB ("+AccRbn+") is under process in Accounts");
									$("#rbnno").val('');
								}else if((Data2['civil_status'] == "C")&&(Data2['acc_status'] == "")){
									var AccRbn = Data2['rbn'];
									BootstrapDialog.alert("RAB ("+AccRbn+") is under process in Accounts");
									Err++;
									$("#rbnno").val('');
								}
							}
						}
					}
				}
			});
		}
	});*/
	
	var KillEvent = 0;
	$('#btn_generate').on('click', function(event){ 
		if(KillEvent == 0){
			var WorkName = $("#wordorderno").val();
			var WorkOrderNo = $("#txt_workorder_no").val();
			var RabNo = $("#rbnno").val();
			var CurrRbn 	= $("#rbnno").val();
			var MaxRbn 		= $("#txt_rbn_list").val();
			var RunnRbnList = $("#txt_rbn_list2").val();
			var Diff = CurrRbn - MaxRbn;
			var PrevRbnErr = 0; var PassOrderErr = 0; var RabDupErr = 0;
			if(RunnRbnList != ''){
				var i,j; 
				var SplitRunnRbnList = RunnRbnList.split(","); 
				for(i=0; i<SplitRunnRbnList.length; i++){
					if(Number(CurrRbn) < Number(SplitRunnRbnList[i])){
						PrevRbnErr++;
					}else if(Number(CurrRbn) > Number(SplitRunnRbnList[i])){
						PassOrderErr++;
					}else if(Number(CurrRbn) == Number(SplitRunnRbnList[i])){
						RabDupErr++;
					}
				}
			}
			var FbErr = 0;
			if($('#ch_final_bill').is(':checked')){
				if($("#txt_act_doc").val() == ""){
					FbErr = 1;
				}
			}
			var FromDtErr = 0; var ToDtErr = 0;
			if($('#ch_rab_for_meas').is(':checked')){
				if($("#txt_from_date").val() == ''){
					FromDtErr = 1;
				}
				if($("#txt_to_date").val() == ''){
					ToDtErr = 1;
				}
			}
			
			if(WorkName == ""){
				BootstrapDialog.alert("Please select work short name");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderNo == ""){
				BootstrapDialog.alert("Work order number should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(RabNo == ""){
				BootstrapDialog.alert("Please enter RAB number");
				event.preventDefault();
				event.returnValue = false;
			}else if(Number(CurrRbn) <= Number(MaxRbn)){
				BootstrapDialog.alert("Entered RAB already closed");
				event.preventDefault();
				event.returnValue = false;
			}else if(PrevRbnErr > 0){
				BootstrapDialog.alert("Entered RAB already closed");
				event.preventDefault();
				event.returnValue = false;
			}else if(PassOrderErr > 0){
				BootstrapDialog.alert("Previous RAB Pass Order Not Confirm");
				event.preventDefault();
				event.returnValue = false;
			}else if(FromDtErr == 1){
				BootstrapDialog.alert("From date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(ToDtErr == 1){
				BootstrapDialog.alert("To date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}/*else if(RabDupErr > 0){
				BootstrapDialog.alert("RAB "+CurrRbn+" already created and under process ");
				event.preventDefault();
				event.returnValue = false;
			}*/else if($("[name='ch_rab_for[]']:checked").length == 0){
				BootstrapDialog.alert("Please select atleast one option for RAB");
				event.preventDefault();
				event.returnValue = false;
			}else if((Diff > 1)&&(MaxRbn != CurrRbn)&&(MaxRbn != '')&&(CurrRbn != '')){
				BootstrapDialog.alert("You have not generated your RAB No : "+(CurrRbn-1));
				event.preventDefault();
				event.returnValue = false;
			}else if(FbErr == 1){
				BootstrapDialog.alert("This is final bill and you must enter actual date of completion date");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to create new RAB ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							var CompCertContent = $("#CompCertContent").html();
							$("#txt_comp_cert_content").val(CompCertContent);
							KillEvent = 1;
							$("#btn_generate").trigger( "click" );
						}else {
							//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}
				/*else{
				event.preventDefault();
				var Diff = CurrRbn - MaxRbn;
				var IsSubmit = 1;
				if(Diff > 1){
					IsSubmit = 0;
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'You have not generated your RAB No : '+(CurrRbn-1)+'. Are you sure want to continue ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
							if(result){
								//KillEvent = 1;
								
								$("#Deposit").trigger( "click" );
							}else {
								//alert('Nope.');
								IsSubmit = 0;
								KillEvent = 0;
							}
						}
					});
				}
				if(IsSubmit == 1){
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to create new RAB ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
							if(result){
								KillEvent = 1;
								$("#btn_generate").trigger( "click" );
							}else {
								//alert('Nope.');
								KillEvent = 0;
							}
						}
					});
				}
			}*/
		}
	});
	
});
</script>
</body>
</html>

