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
if($_POST["btn_generate"] == "Next") 
{
	$staffid 		= $_SESSION['sid'];
    $sheet_id 		= trim($_POST['cmb_shortname']);
    $rbn 			= trim($_POST['rbnno']);
	$ch_final_bill 	= trim($_POST['ch_final_bill']);
	/*if($ch_final_bill != ""){
		$act_doc 	= trim($_POST['txt_act_doc']);
		$act_doc	= dt_format($act_doc);
	}*/
	$Maxdate 		= "";
	$select_maxdate_query	= "SELECT DATE_FORMAT(max(todate),'%Y-%m-%d') as max_date FROM measurementbook WHERE sheetid = '$sheet_id'";
	$select_maxdate_sql 	= mysql_query($select_maxdate_query);
	if($select_maxdate_sql == true){
		if(mysql_num_rows($select_maxdate_sql)>0){
			$MaxDtList 	= mysql_fetch_object($select_maxdate_sql);
			$Maxdate 	= $MaxDtList->max_date;
		}
	}
	if($ch_final_bill == "Y"){
		//$update_doc_query 	= "update sheet set act_doc = '$act_doc' where sheet_id = '$sheet_id'";
		//$update_doc_sql 	= mysql_query($update_doc_query);
		$MesCount = 0;
		$select_count_query = "select mbheaderid from mbookheader where sheetid = '$sheetid' and date > = '$Maxdate'";
		$select_count_sql 	= mysql_num_rows($select_count_query);
		if($select_count_sql == true){
			$MesCount = mysql_num_rows($select_count_sql);
		}
		
		if($MesCount == 0){
			$MeasExist = "NO";
		}else{
			$MeasExist = "YES";
		}
	}
	
	//echo $Maxdate;exit;
	//$res1 = @mysql_result($rs1,'fromdate');
	$_SESSION["sheet_id"] 	= $sheet_id;  
	$_SESSION["rbn"] 		= $rbn;
	$_SESSION["MaxdateDB"] 	= $Maxdate;
	$_SESSION["MaxdateDPL"] = dt_display($Maxdate);
	$_SESSION["final_bill"] = $ch_final_bill; 
	header('Location: MBookGenerateSection1.php?varid=1');        
	//header('Location: MBook_Staff_Wise.php?varid=1');
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
</style>
<script>
/*$(function () {
	$( "#txt_act_doc" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2010:+15",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$.fn.validateworkorder = function(event) { 
		if($("#wordorderno").val()==""){ 
			var a="Please select the work order number";
			$('#val_work').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}
	$.fn.validaterbn = function(event) { 
		if($("#rbnno").val()==""){ 
			var a="Please Enter RAB Number";
			$('#rbn_error').text(a);
			event.preventDefault();
			event.returnValue = false;
		}else if($("#rbnno").val()==0){ 
			var a="Please Enter Valid RAB Number";
			$('#rbn_error').text(a);
			event.preventDefault();
			event.returnValue = false;
		}else if($("#rbnno").val()< 0){ 
			var a="Please Enter Valid RAB Number";
			$('#rbn_error').text(a);
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
				$('#rbn_error').text(a);
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
							$('#rbn_error').text(a);
							event.preventDefault();
							event.returnValue = false;
						}else if(CurrRbn > SplitRunnRbnList[i]){
							var Diff = CurrRbn - SplitRunnRbnList[i];
							a = 'Previous RAB Pass Order Not Confirm';
							$('#rbn_error').text(a);
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
	}
	$.fn.validateActDoc = function(event) { 
		if($('#ch_final_bill').is(":checked")){
			if($("#txt_act_doc").val()==""){ 
				var a="Please enter actual date of completion";
				$('#val_act_doc').text(a);
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
	}
	$("#wordorderno").change(function(event){
		$(this).validateworkorder(event);
	});
	$("#rbnno").change(function(event){
		$(this).validaterbn(event);
	});
	
	$("#txt_act_doc").change(function(event){
		$(this).validateActDoc(event);
	});
	
	$("#ch_final_bill").click(function(event){
		if($(this).is(":checked")){
			$("#hide_row1").removeClass("hidden").addClass("shown");
			$("#hide_row2").removeClass("hidden").addClass("shown");
		}else{
			$("#hide_row1").removeClass("shown").addClass("hidden");
			$("#hide_row2").removeClass("shown").addClass("hidden");
		}
	});
	
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
		$(this).validaterbn(event);
		$(this).validateActDoc(event);
	});
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
*/
		
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
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <?php include "Menu.php"; ?>
       
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">RAB Initiation </div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                            <div class="container">
								<div class="box-container box-container-lg">
									<div class="row clearrow"></div>
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">Measurements Book (General, Steel, Sub Abstract & Abstract) Generate</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row clearrow"></div>
														<div class="row clearrow"></div>
														<div class="div4 pd-lr-1">
															<div class="lboxlabel">Work Short Name</div>
														</div>
														<div class="div8 pd-lr-1">
															<div>
																<select name="cmb_shortname" id="cmb_shortname"  class="tboxsmclass" tabindex="1">
																	<option value=""> --------------- Select Work Order --------------- </option>
																	<?php echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
																</select>
															</div>
														</div>
														<div class="row clearrow"></div>
														
														
														<div class="div4 pd-lr-1 label">
															<div class="lboxlabel">Work Order No.</div>
														</div>
														<div class="div8 pd-lr-1 label">
															<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="tboxsmclass" readonly="">
														</div>
														<div class="row clearrow"></div>
														
														
														<div class="div4 pd-lr-1 label">
															<div class="lboxlabel">Name of the Work</div>
														</div>
														<div class="div8 pd-lr-1 label">
															<textarea name="workname" class="tboxsmclass" id="workname" rows="4" disabled="disabled"></textarea>
														</div>
														<div class="row clearrow"></div>
														<div class="div4 pd-lr-1 label">
															<div class="lboxlabel">Running Account Bill No.</div>
														</div>
														<div class="div3 pd-lr-1 label">
															<input type="number" name="rbnno" id="rbnno" class="tboxsmclass" readonly="" tabindex="5"/>
														</div>
														<div class="div5 pd-lr-1 label">
															<div class="label">&nbsp;</div>
														</div>
														<div class="row clearrow"></div>
														
														<div class="div4 pd-lr-1">
															<div class="lboxlabel">&nbsp;</div>
														</div>
														<div class="div3 pd-lr-1">
															<div>
																<input type="checkbox" name="ch_final_bill" id="ch_final_bill" onclick="return false" value="Y" >
																&nbsp; <span class="label">Is Final Bill ?</span>
															</div>
														</div>
														<div class="div5 pd-lr-1">
															<div class="label">&nbsp;</div>
														</div>
														
														<!--<div class="row clearrow hide Fb"></div>
														<div class="div4 pd-lr-1 hide Fb">
															<div class="lboxlabel">Actual Date of Completion</div>
														</div>
														<div class="div3 pd-lr-1 hide Fb">
															<input type="text" name="txt_act_doc" id="txt_act_doc" readonly="" class="tboxsmclass" tabindex="5"/>
														</div>
														<div class="div5 pd-lr-1 hide Fb">
															<div class="label">&nbsp;</div>
														</div>-->
														<div class="row clearrow"></div>
														<div class="div12 pd-lr-1" align="center">
															<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
															<input type="submit" class="btn" data-type="submit" value="Next" name="btn_generate" id="btn_generate" onMouseOver="checkmeasurement();"/>
															<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
															
														</div>
														<div class="row clearrow"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
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
	$("#cmb_shortname").chosen();
    $(function () {
        function DisplayRBNDetails() {
            var wordordernovalue = $("#cmb_shortname option:selected").attr('value');
            $.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) { 
                 var workname = data.split("*");
                 $("#workname").text(workname[0]);
				 $("#txt_workorder_no").val(workname[2]);
            });
        }
        $("#cmb_shortname").bind("change", function () {  
			$('#rbn_error').text(""); 
			$("#rbnno").val("");
			$("#txt_rbn_list2").val("");
			$("#txt_rbn_list2").val("");
			DisplayRBNDetails();
     	});
		var Zm = 0; 
		$("#cmb_shortname").bind("change", function(){ 
			Zm = 0; 
			var WorkId = $(this).val();
			$("#txt_rbn").val('');
			$('#ch_final_bill').prop('checked',false);
			$.ajax({
				type: 'POST', 
				url: 'FindBillForwardToAcc.php', 
				data: { WorkId: WorkId }, 
				dataType: 'json',
				success: function (data) { //alert(data);
					if(data != null){
						if((data['rbn'] != null)&&(data['rbn'] != '')){
							$("#rbnno").val(data['rbn']);
							if(data['is_rab'] != "Y"){ 
								Zm = 1;
							}
							if(data['is_final_bill'] == "Y"){ 
								$('#ch_final_bill').prop('checked',true);
							}
						}
					}
				}
			});
		});
		
		
		var KillEvent = 0;
		$('#btn_generate').on('click', function(event){ 
			if(KillEvent == 0){
				var WorkShortName = $("#cmb_shortname").val();
				var WorkOrderNo = $("#txt_workorder_no").val();
				var WorkName = $("#workname").val();
				var RabNo = $("#rbnno").val();
				if(WorkShortName == ""){
					BootstrapDialog.alert("Please select work short name");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkOrderNo == ""){
					BootstrapDialog.alert("Work order number should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkName == ""){
					BootstrapDialog.alert("Work name should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(RabNo == ""){
					BootstrapDialog.alert("Please enter RAB number");
					event.preventDefault();
					event.returnValue = false;
				}else if(Zm == 1){
					BootstrapDialog.alert("Access denied ! Measurements option not enabled for this RAB in RAB Create module");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to generate Measurement Book ?',
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
			}
		});
		
		
	});
</script>
</body>
</html>

