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
if(isset($_POST['btn_save']) == " SAVE "){
	$SaveMemoId 	= $_POST['cmb_shortname'];
	$SavePoDate 	= dt_format($_POST['txt_podate']);
	$PayOrderAmt 	= $_POST['txt_bill_amt'];
	
	$UpdateQuery1 	= "UPDATE memo_payment_accounts_edit SET pay_order_dt = '$SavePoDate', pay_order_amt = '$PayOrderAmt', pay_order_cr_by = '$staffid', pay_order_cr_level = '".$_SESSION['levelid']."' WHERE memoid = '$SaveMemoId'";
	$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
	if($UpdateQuery1 == true){
		$msg = "Pay order data saved successfully";
	}else{
		$msg = "Error : Pay order data not saved. Please try again.";
	}
}

$ViewTypeArr = array("'LCESS'","'SAL'"); $MopTypeArr = array('LCESS'=>'LABOUR CESS','SAL'=>'SALARY');
$SelectQuery = "SELECT * FROM miscell_items WHERE active = 1 and misc_module = 'CCNO' ORDER BY mis_item_desc ASC";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true ){
	while($List = mysqli_fetch_array($SelectSql)){
		array_push($ViewTypeArr,"'".$List['mop_type']."'");
		$MopType = $List['mop_type'];
		$MopTypeArr[$MopType] = $List['mis_item_desc'];
	}            
}
if(count($ViewTypeArr)>0){
	$ViewTypeArrStr = implode(",",$ViewTypeArr);
}else{
	$ViewTypeArrStr = "";	
}

$SelectWorkQuery = "SELECT a.*, a.rbn as mpac_rbn, b.*, c.mis_item_desc, d.contractor_title, d.name_contractor FROM memo_payment_accounts_edit a 
LEFT JOIN sheet b ON (a.sheetid = b.sheet_id) 
LEFT JOIN miscell_items c ON (c.mis_item_id = a.mis_item_id AND a.mis_item_id != 0)
LEFT JOIN contractor d ON (a.contid = d.contid) 
WHERE (a.mop_type = 'MISC' OR a.mop_type IN($ViewTypeArrStr)) AND (pay_order_dt = '' OR pay_order_dt = '0000-00-00' OR pay_order_dt IS NULL)";
$SelectWorkSql 	 = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		$RowCount = 1;
	}
}
//echo $SelectWorkQuery;exit;
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
											<div class="card-header inkblue-card" align="center">&nbsp;Pay Order - Miscellaneous</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
												
													<div class="row">
														<div class="div12" align="center">
															<div class="innerdiv2">
																<div class="row" align="center">
																	<div class="row">
																		<div class="row clearrow"></div>
																		<div class="div4 lboxlabel">Work / Misc. Description</div>
																		<div class="div8" align="left">
																			<select name="cmb_shortname" id="cmb_shortname" class="tboxsmclass">
																				<option value="">-------------- Select Work Name/CCNo. ----------------</option>
																				<?php 
																				if($RowCount == 1){ while($List = mysqli_fetch_object($SelectWorkSql)){ 
																					if(($List->sheetid == 0)||($List->sheetid == '')||($List->sheetid == NULL)){
																						if($List->mop_type == "SAL"){
																							$WorkDesc = $List->mis_item_desc." for the period of ".dt_display($List->lcess_fdate)." - ".dt_display($List->lcess_tdate);
																						}else if($List->mop_type == "LCESS"){
																							$WorkDesc = $List->mis_item_desc." for the period of ".dt_display($List->lcess_fdate)." - ".dt_display($List->lcess_tdate);
																						}else{
																							$WorkDesc = $List->mis_item_desc;
																						}
																						
																					}else{
																						$WorkDesc = $List->work_name;
																					}
																					if(($List->computer_code_no != '')&&($List->computer_code_no != NULL)){
																						if($List->mop_type == "RAB"){
																							$WorkDesc = $List->computer_code_no." - ".$WorkDesc;
																						}else{
																							$WorkDesc = $List->computer_code_no." - ".$List->mis_item_desc." - ".$WorkDesc;
																						}
																					}else{
																						$WorkDesc = $WorkDesc;
																					}
																					echo '<option value="'.$List->memoid.'" data-amount="'.$List->net_payable_amt.'">'.$WorkDesc.'</option>';
																				} } 
																				?>
																		   	</select>
																		</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div8 errtext" id="val_work" align="left">&nbsp;</div>
																		<div class="div12"></div>
																		
																																				
																		<div class="div4 lboxlabel">Pay Order Amount (Rs.)</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_bill_amt" id="txt_bill_amt" readonly="" class="tboxsmclass">
																		</div>
																		<div class="div4 lboxlabel">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div4 errtext" id="val_bill_amt" align="left">&nbsp;</div>
																		<div class="div4">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="div4 lboxlabel">Pay Order Date</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_podate" id="txt_podate" class="tboxsmclass datepicker">
																		</div>
																		<div class="div4 lboxlabel">&nbsp;</div>
																		<div class="div12"></div>
																		<div class="div4">&nbsp;</div>
																		<div class="div4 errtext" id="val_podate" align="left">&nbsp;</div>
																		<div class="div4">&nbsp;</div>
																		<div class="div12"></div>
																		
																		<div class="row clearrow"></div>
																		<div class="div12">
																			<input type="submit" class="btn btn-info" value=" SAVE " name="btn_save" id="btn_save"/>
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
	
	/*
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
			var a="Pay Order amount should not be empty";
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
			var a="Pay order date should not be empty";
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
		$(this).validateworkccno(event);
		$(this).validatebillamt(event);
		$(this).validatepodate(event);
	});
	$("#cmb_shortname").change(function(event){
    	$(this).validateworkccno(event);
    });
    
	$("#txt_bill_amt").change(function(event){
    	$(this).validatebillamt(event);
    });
	$("#txt_podate").change(function(event){
    	$(this).validatepodate(event);
    });
	*/
	var KillEvent = 0;
	$("body").on("click","#btn_save", function(event){
		if(KillEvent == 0){
			var WorkId  = $("#cmb_shortname").val(); 
			var BillAmt  = $("#txt_bill_amt").val();
			var PayOrderDate  = $("#txt_podate").val();
			if(WorkId == ""){
				BootstrapDialog.alert("Please select Work / Misc. description");
				event.preventDefault();
				event.returnValue = false;
			}else if(BillAmt == ""){
				BootstrapDialog.alert("Pay order amount should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(PayOrderDate == ""){
				BootstrapDialog.alert("Pay order date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure save Pay Order ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							KillEvent = 1;
							$("#btn_save").trigger( "click" );
						}else {
							//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}
		}
	});
	
	$("body").on("change","#cmb_shortname", function(event){
		$("#txt_bill_amt").val('');
		if($(this).val() != ''){
			var PayOrderAmt = $("#cmb_shortname option:selected").attr("data-amount"); 
			$("#txt_bill_amt").val(PayOrderAmt);
		}
	});
});
</script>

</body>
</html>

