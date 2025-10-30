<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "common.php";
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
if(isset($_POST['btn_save']) == " SAVE & NEXT "){
	$SaveSheetId 	= $_POST['cmb_shortname'];
	$SaveRbn 		= $_POST['txt_rbn'];
	$SavePoDate 	= dt_format($_POST['txt_podate']);
	$PassOrderAmt 	= $_POST['txt_bill_amt'];
	$BillMode 		= $_POST['txt_bill_mode'];
	$UpdateQuery1 	= "UPDATE memo_payment_accounts_edit SET pass_order_dt_adv_pay = '$SavePoDate', pass_order_amt_adv_pay = '$PassOrderAmt', pass_order_cr_by_adv_pay = '$staffid', pass_order_cr_level_adv_pay = '".$_SESSION['levelid']."' where sheetid = '$SaveSheetId' and rbn = '$SaveRbn'";
	$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
	$UpdateQuery2 	= "UPDATE abstractbook_dt SET pass_order_amt = '$PassOrderAmt', pass_order_cr_by = '$staffid', pass_order_cr_level = '".$_SESSION['levelid']."' where sheetid = '$SaveSheetId' and rbn = '$SaveRbn'";
	$UpdateSql2 	= mysqli_query($dbConn,$UpdateQuery2);
	if($UpdateQuery1 == true){
		$msg = "Pass order data for 75% advance saved successfully";
	}else{
		$msg = "Error : Pass order data for 75% advance not saved. Please try again.";
	}
	//echo $UpdateQuery1;exit;
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
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script>
	function find_workname()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "AccountsStatementSteps.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
						<div class="row clearrow"></div>
                        <form name="form" method="post" action="">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">&nbsp;Pass Order</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
												
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="row">
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">CCNO. / Name of Work</div>
																		<div class="div8" align="left">
																			<select name="cmb_shortname" id="cmb_shortname" class="tboxsmclass">
																				<option value="">-------------- Select Work Name/CCNo. ----------------</option>
																				<?php echo $objBind->BindCCNoListAccountsPo(0); ?>
																		   	</select>
																		</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div8 errtext" id="val_work" align="left">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="div4 lboxlabel">Work Order No.</div>
																		<div class="div8" align="left">
																			<input type="text" name="txt_workorder_no" id="txt_workorder_no" readonly="" class="tboxsmclass">
																		</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div8 errtext" id="val_workorder" align="left">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="div4 lboxlabel">RAB No.</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_rbn" id="txt_rbn" readonly="" class="tboxsmclass">
																		</div>
																		<div class="div4 lboxlabel">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div4 errtext" id="val_rbn" align="left">&nbsp;</div>
																		<div class="div4">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="div4 lboxlabel">Pass Order Amount (Rs.)</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_bill_amt" id="txt_bill_amt" readonly="" class="tboxsmclass">
																		</div>
																		<div class="div4 lboxlabel">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div4 errtext" id="val_bill_amt" align="left">&nbsp;</div>
																		<div class="div4">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="div4 lboxlabel">Pass Order Date</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_podate" id="txt_podate" class="tboxsmclass datepicker" readonly="">
																		</div>
																		<div class="div4 lboxlabel">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div4 errtext" id="val_podate" align="left">&nbsp;</div>
																		<div class="div4">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="row clearrow"></div>
																		<div class="div12">
																			<input type="hidden" name="txt_bill_mode" id="txt_bill_mode" class="tboxsmclass">
																			<input type="submit" class="btn btn-info" value=" SAVE & NEXT " name="btn_save" id="btn_save"/>
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
									<div class="div2">&nbsp;</div>
								</div>
							</div>
						</div>
       				</form>
      			</blockquote>
    		</div>
   		</div>
	</div>
	<link rel="stylesheet" href="css/timeline.css">
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
$("#cmb_shortname").chosen();
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$(function() {
	$.fn.validaterbnno = function(event) {	
		if($("#txt_rbn").val()==0){ 
			var a="RAB should not be empty";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_rbn').text(a);
		}
	}
	$.fn.validateworkorder = function(event) {	
		if($("#txt_workorder_no").val()==0){ 
			var a="Work order should not be empty";
			$('#val_workorder').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_workorder').text(a);
		}
	}
	$.fn.validateworkccno = function(event) { 
		if($("#cmb_shortname").val()==""){ 
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
	$.fn.validatebillamt = function(event) { 
		if($("#txt_bill_amt").val()==""){ 
			var a="Bill amount should not be empty";
			$('#val_bill_amt').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_bill_amt').text(a);
		}
	}
	$.fn.validatepodate = function(event) { 
		if($("#txt_podate").val()==""){ 
			var a="Pass order date should not be empty";
			$('#val_podate').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_podate').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validaterbnno(event);
		$(this).validateworkccno(event);
		$(this).validateworkorder(event);
		$(this).validatebillamt(event);
		$(this).validatepodate(event);
	});
	$("#cmb_shortname").change(function(event){
    	$(this).validateworkccno(event);
    });
    $("#cmb_rbn").change(function(event){
    	$(this).validaterbnno(event);
    });
	$("#txt_workorder_no").change(function(event){
    	$(this).validateworkorder(event);
    });
	$("#txt_bill_amt").change(function(event){
    	$(this).validatebillamt(event);
    });
	$("#txt_podate").change(function(event){
    	$(this).validatepodate(event);
    });
	$("body").on("change","#cmb_shortname", function(event){
		GetWorkDetails();
	});
	function GetWorkDetails(){
		var WorkId = $("#cmb_shortname").val();
		$("#txt_workorder_no").val('');
		$("#txt_rbn").val('');
		$("#txt_podate").val('');
		$("#txt_bill_amt").val('');
		$("#txt_bill_mode").val('');
		$.ajax({ 
			type: 'POST', 
			dataType:'json',
			url: 'ajax/FindWorkDetails.php', 
			data: ({ WorkId: WorkId, PageType:'PSO', PayFlag:'PPAY'}), 
			success: function (data) {
				if(data != null){ 
					$.each(data, function(index, element) {
						if(element.is_adv_pay_flag == "Y"){
							$("#txt_workorder_no").val(element.work_order_no);
							$("#txt_rbn").val(element.rbn);
							$("#txt_bill_amt").val(element.pass_order_amt);
							$("#txt_podate").val(element.pass_order_date_dp);
						}else{
							if(element.bill_reg_status == "N"){
								BootstrapDialog.alert("Bill not yet registered / Invalid attempt.");
								BillErr = 1;
							}/*else if(element.bill_comp_status == "C"){
								BootstrapDialog.alert("Bill process is already completed. You can't create Pass Order again.");
								BillErr = 1;
							}*/else if(element.bill_vouch_status == "Y"){
								BootstrapDialog.alert("Voucher process is already completed. You can't create Pass Order again.");
								BillErr = 1;
							}else if(element.bill_payord_status == "Y"){
								BootstrapDialog.alert("Pay Order process is already completed. You can't create Pass Order again.");
								BillErr = 1;
							}else if(element.bill_pasord_status == "Y"){
								BootstrapDialog.alert("Pass Order process is already completed. You can't edit further.");
								BillErr = 1;
							}else if((element.bill_ret_status == "Y")&&(element.bill_mode == "ON")){
								BootstrapDialog.alert("Bill is returned back to EIC. You can't create Pass Order.");
								BillErr = 1;
							}else if((element.bill_level_flag == "H")&&(element.bill_mode == "ON")){
								BootstrapDialog.alert("Bill is forwarded to next checking level. You can't create Pass Order.");
								BillErr = 1;
							}else if((element.bill_level_flag == "L")&&(element.bill_mode == "ON")){
								BootstrapDialog.alert("Bill is still waiting in previous checking level. You can't create Pass Order.");
								BillErr = 1;
							}else if((element.bill_curr_level == element.bill_level_flag)&&(element.bill_curr_level != "C")&&(element.bill_mode == "ON")){
								BootstrapDialog.alert("Bill verification not yet completed. You can't create Pass Order.");
								BillErr = 1;
							}else{
								$("#txt_workorder_no").val(element.work_order_no);
								$("#txt_rbn").val(element.rbn);
								$("#txt_bill_amt").val(element.pass_order_amt);
								$("#txt_podate").val(element.pass_order_date_dp);
								$("#txt_bill_mode").val(element.bill_mode);
							}
						}
					});
				}
			}
		});
	}
});
</script>

</body>
</html>

