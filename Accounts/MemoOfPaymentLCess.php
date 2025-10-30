<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
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
$GlobPartARecArr = array("LCESS"=>"LCess","MOB"=>"Mob.Adv. Rec.","PM"=>"P&M.Adv. Rec.","HIRE"=>"Hire Charges","OTH"=>"Other Recoveries");
$GlobPartBRecArr = array("CGST"=>"CGST","SGST"=>"SGST","IGST"=>"IGST","IT"=>"IT","SD"=>"SD","WC"=>"Water Charges","EC"=>"Electricity Charges","MOBINT"=>"Mob. Adv. Interest","PMINT"=>"P&M Adv. Interest");
if(isset($_POST['btnSave'])){
	$SaveUnit 		= $GlobUnitId;//$_POST['txt_unitid'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	$SaveRefNo 		= $_POST['txt_ref_no'];
	$SaveCcno 		= $_POST['txt_ccno'];
	$SaveGlobId 	= $_POST['txt_globid'];
	$SavePinNo 		= $_POST['txt_pinno'];
	$SaveContId 	= $_POST['cmb_contractor'];
	$SaveBankAcc 	= $_POST['txt_bank_acc'];
	$SaveBankId 	= $_POST['txt_bank_id'];
	$SaveBankName 	= $_POST['txt_bank_name'];
	$SaveBankBranch = $_POST['txt_branch'];
	$SaveBankIfsc 	= $_POST['txt_ifsc'];
	
	$SavePaymentFor = $_POST['cmb_payment_for'];
	$SaveNetAmt 	= $_POST['txt_net_amt'];
	$SaveOtherDesc 	= $_POST['txt_others'];
	$SaveRemarks 	= $_POST['txt_remarks'];
	
	$SaveMopType 	= $_POST['txt_mop_type'];
	$SaveMiscModule = $_POST['txt_misc_module'];
	
	$SaveFromDate 	= dt_format($_POST['txt_from_date']);
	$SaveToDate 	= dt_format($_POST['txt_to_date']);
	
	$SaveHoaId 		= $_POST['cmb_hoa'];
	$SaveHoaNo 		= $_POST['txt_hoa'];
	$SaveSCodeId 	= $_POST['txt_scode_id'];
	$SaveSCode 		= $_POST['txt_scode'];
	
	$MopDate 		= dt_format($_POST['txt_mopdate']);
	
	$DupExist = 0;
	$CheckVrDate = date('Y-m',strtotime($SaveVouchDate));
	$SelectQuery1 = "SELECT memoid FROM memo_payment_accounts_edit WHERE sheetid = '$SaveSheetId' AND contid = '$SaveContId' AND 
					net_payable_amt = '$SaveNetAmt' AND mop_type = '$SaveMopType'";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$DupExist = 1;
		}
	}
	if($DupExist == 0){
		$InsertQuery 	= "INSERT INTO memo_payment_accounts_edit SET mop_date = '$MopDate', mis_item_id = '$SavePaymentFor', misc_ref_no = '$SaveRefNo', contid = '$SaveContId', cbdtid = '$SaveBankId', 
						  abstract_net_amt = '$SaveNetAmt', net_payable_amt = '$SaveNetAmt', edit_flag = 'EDIT', mop_type = '$SaveMopType', hoa = '$SaveHoaNo', hoaid = '$SaveHoaId', shcode_id = '$SaveSCodeId', remarks = '$SaveRemarks', lcess_fdate = '$SaveFromDate', lcess_tdate = '$SaveToDate', 
						  staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', modifieddate = NOW(), active = 1";
		$InsertSql 		= mysqli_query($dbConn,$InsertQuery); //echo $InsertQuery;exit;
		$MopId = mysqli_insert_id($dbConn);
		
			
		if($InsertSql == true){
			$msg = "Memo of payment data saved successfully";
			$success = 1;
		}else{
			$msg = "Error : Memo of payment data not saved";
			$success = 0;
		}
	}else if($DupExist == 1){
		$msg = "Duplicate Error : Memo of payment already created for this data";
		$success = 0;
	}else{
		$msg = "Error : Invalid data / Invalid attempt";
		$success = 0;
	}
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
/*.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.tboxsmclass{
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
}*/
.chosen-container-single .chosen-single{
	border:1px solid #3D6CBE !important;
	padding:3px 4px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	width:100% !important;
	border-radius:8px !important;
	font-size:11px;
	font-weight:500;
}
.chosen-container .chosen-results{
	font-weight:500;
	font-size:11px;
}
.chosen-container .chosen-results li.active-result {
  	display: list-item;
  	cursor: pointer;
  	font-size: 11px !important;
}
.chosen-container .chosen-results li{
	padding: 4px 6px;
	line-height: 11px;
}
.chosen-container .chosen-results li.group-result {
  	display: list-item;
  	font-weight: 600;
  	cursor: default;
  	font-size: 11px;
}
.chosen-container-single .chosen-single span{
	font-weight: 500;
}
.box-container {
  padding: 0px 20px;
}
/*#mySidenav a {
	position:static !important;
}*/
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
								<div class="div1">&nbsp;</div>
								<div class="div10">
									<div class="box-container box-container-lg lg-box" align="center">
										<div class="div12">
											<div class="card cabox" style="margin-bottom:1px;">
												<div class="face-static">
													<div class="card-header inkblue-card" align="left">&nbsp;Memo of Payment - Labour Cess</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox" style="padding-top:0px; padding-bottom:0px;">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2" style="padding-top:0px;">
																		<div class="row" align="center">
																			
																			<div class="div12 smlboxlabel">
																				<div class="div4 pd-lr-1">
																					<div class="lboxlabel">&nbsp;Nature of Claim</div>
																					<div>
																						<select name="cmb_payment_for" id="cmb_payment_for" class="dynamicboxlg inp" required>
																							<?php echo $objBind->BindAllMiscellItems(0,'LCESS'); ?>
																						</select>
																						<input type="hidden" name="txt_mop_type" id="txt_mop_type" value="LCESS" />
																						<input type="hidden" name="txt_misc_module" id="txt_misc_module" value="LCESS" />
																					</div>
																				</div>
																				<div class="div3 pd-lr-1">
																					<div class="lboxlabel">&nbsp;Reference No.</div>
																					<div>
																						<input type="text" name="txt_ref_no" id="txt_ref_no" class="dynamicboxlg inp" required />
																						<input type="hidden" name="txt_sheetid" id="txt_sheetid" class="tboxsmclass bordd1" readonly="" />
																						<input type="hidden" name="txt_globid" id="txt_globid" class="tboxsmclass bordd1" readonly="" />
																						<input type="hidden" name="txt_unitid" id="txt_unitid" class="tboxsmclass bordd1" readonly="" value="6" />
																						<input type="hidden" name="txt_pinno" id="txt_pinno" class="tboxsmclass bordd1" readonly="" value="712" />
																					</div>
																				</div>
																				
																				<div class="div2 pd-lr-1">
																					<div class="lboxlabel">&nbsp;From Date</div>
																					<div>
																						<input type="text" name="txt_from_date" id="txt_from_date" class="dynamicboxlg inp datepicker" required />
																					</div>
																				</div>
																				<div class="div2 pd-lr-1">
																					<div class="lboxlabel">&nbsp;To Date</div>
																					<div>
																						<input type="text" name="txt_to_date" id="txt_to_date" class="dynamicboxlg inp datepicker" required />
																					</div>
																				</div>
																				<div class="div1 pd-lr-1">
																					<div class="lboxlabel">&nbsp;</div>
																					<div>
																						<input type="button" name="btnGo" id="btnGo" class="gbtn" value=" GO " style="margin-top:0px;">
																					</div>
																				</div>
																				
																				<div class="row clearrow"></div>
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
										<div class="row clearrow"></div>
										<div class="div12">
											<div class="card cabox" style="margin-top:0px;">
												<div class="face-static">
													<div class="card-header inkblue-card" align="left">&nbsp;Payee & Bank Details</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			
																			<div class="div4 pd-lr-1">
																				<div class="lboxlabel">Payee Name</div>
																				<div>
																					<input type="hidden" name="txt_contractor" id="txt_contractor" class="dynamicboxlg inp" />
																					<select name="cmb_contractor" id="cmb_contractor" class="dynamicboxlg inp" required>
																						<option value=""> -- Select --</option>
																						<?php echo $objBind->BindCont(0); ?>
																					</select>
																				</div>
																			</div>
																			
																			
																			<div class="div2 pd-lr-1">
																				<div class="lboxlabel">Bank Account No. <!--<font class="efont ptr BankData">(<i class="fa fa-edit" style="font-size:16px; top:2px; position:relative"></i> Click here to select)</font>--></div>
																				<div>
																					<input type="text" name="txt_bank_acc" id="txt_bank_acc" readonly="" class="dynamicboxlg inp" required />
																					<input type="hidden" name="txt_bank_id" id="txt_bank_id" class="dynamicboxlg inp" />
																				</div>
																			</div>
																			<div class="div2 pd-lr-1">
																				<div class="lboxlabel">Bank Name</div>
																				<div>
																					<input type="text" name="txt_bank_name" id="txt_bank_name" readonly="" class="dynamicboxlg inp" required />
																				</div>
																			</div>
																			<div class="div2 pd-lr-1">
																				<div class="lboxlabel">Branch Name</div>
																				<div>
																					<input type="text" name="txt_branch" id="txt_branch" readonly="" class="dynamicboxlg inp" required />
																				</div>
																			</div>
																			<div class="div2 pd-lr-1">
																				<div class="lboxlabel">IFSC Code</div>
																				<div>
																					<input type="text" name="txt_ifsc" id="txt_ifsc" readonly="" class="dynamicboxlg inp" required />
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
										
										<div class="row smclearrow"></div>
										<div class="div12">
											<div class="card cabox" style="margin-top:0px;">
												<div class="face-static">
													<div class="card-header inkblue-card" align="left">&nbsp;Payment Details</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<div class="div4 pd-lr-1">
																				<div class="lboxlabel">Remarks</div>
																				<div>
																					<input type="text" name="txt_remarks" id="txt_remarks" class="dynamicboxlg inp" />
																				</div>
																			</div>
																			<div class="div3 pd-lr-1 oth hide">
																				<div class="lboxlabel">Other Payment Description</div>
																				<div>
																					<input type="text" name="txt_others" id="txt_others" class="dynamicboxlg inp" />
																				</div>
																			</div>
																			
																			<div class="div2 pd-lr-1">
																				<div class="lboxlabel">Amount  &#8377;<font class="efont ptr InfoData"> (View)</font></div>
																				<div>
																					<input type="text" name="txt_net_amt" id="txt_net_amt" class="dynamicboxlg inp" required />
																				</div>
																			</div>
																			
																			<div class="div3 pd-lr-1">
																				<div class="lboxlabel">Head of Account</div>
																				<div>
																					<select name="cmb_hoa" id="cmb_hoa" class="dynamicboxlg inp" required>
																						<option value=""> -- Select --</option>
																						<?php echo $objBind->BindHoaWithSCode(0); ?>
																					</select>
																					<input type="hidden" name="txt_hoa" id="txt_hoa">
																					<input type="hidden" name="txt_scode_id" id="txt_scode_id">
																					<input type="hidden" name="txt_scode" id="txt_scode">
																				</div>
																			</div>
																			<div class="div3 pd-lr-1">
																				<div class="lboxlabel">MOP Date</div>
																				<div>
																					<input type="text" name="txt_mopdate" id="txt_mopdate" class="dynamicboxlg inp datepicker" value="<?php echo date("d/m/Y"); ?>" required />
																				</div>
																			</div>
																			
																			<div class="row clearrow"></div>
																			<div class="row" align="center">
																				<div class="div12 pd-lr-1" align="center">
																					<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="btn btn-info">
																					<input type="reset" name="btnReset" id="btnReset" value=" Reset " class="btn btn-info">
																					<a href="MopLcessList.php" class="btn btn-info" name="btn_view" id="btn_view"> View All </a>
																				</div>
																			</div>
																			<div class="row clearrow"></div>
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
								<div class="div1">&nbsp;</div>
								
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

var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$("#cmb_contractor").chosen();
$("#cmb_payment_for").chosen();
$("#cmb_hoa").chosen();
$(function() {
	var RowIndex = 1;
	
	
	var KillEvent = 0;
	$("body").on("click","#btnSave", function(event){
		if(KillEvent == 0){
			var RefNo 		= $("#txt_ref_no").val();
			var ClaimNat 	= $("#cmb_nature_claim").val();
			var ContId 		= $("#cmb_contractor").val();
			var NetAmt 		= $("#txt_net_amt").val();
			var FromDate 	= $("#txt_from_date").val();
			var ToDate 		= $("#txt_to_date").val();
			var VouchNo 	= $("#txt_vouch_no").val();
			var VouchDate	= $("#txt_vouch_dt").val();
			var VouchAmt	= $("#txt_vouch_amt").val();
			var VouchHoa	= $("#txt_vouch_hoa").val();
			if(RefNo == ""){
				BootstrapDialog.alert("Please enter Reference No.");
				event.preventDefault();
				event.returnValue = false;
			}else if(ClaimNat == ""){
				BootstrapDialog.alert("Please select Nature of claim");
				event.preventDefault();
				event.returnValue = false;
			}else if(FromDate == ""){
				BootstrapDialog.alert("From Date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(ToDate == ""){
				BootstrapDialog.alert("To Date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContId == ""){
				BootstrapDialog.alert("Please select contractor name");
				event.preventDefault();
				event.returnValue = false;
			}else if(NetAmt == ""){
				BootstrapDialog.alert("Net amount should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchNo == ""){
				BootstrapDialog.alert("Voucher no. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchDate == ""){
				BootstrapDialog.alert("Voucher date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchAmt == ""){
				BootstrapDialog.alert("Voucher amount should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(VouchHoa == ""){
				BootstrapDialog.alert("Voucher HOA should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save Memo of Payment data ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btnSave").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
			}
		}
	});
	
	
	$("body").on("click",".BankData", function(event){
		var ContId = $("#cmb_contractor").val();
		var BankId = $("#txt_bank_id").val();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindBankData.php', 
			data: { ContId: ContId }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
					TableStr += '<thead><tr><th class="lboxlabel"></th><th class="lboxlabel">SNo.</th><th class="lboxlabel">Account No.</th><th class="lboxlabel">Bank Name</th><th class="lboxlabel">Branch</th><th class="lboxlabel">IFSC Code</th></tr></thead>';
					TableStr += '<tbody>';
					var Sno = 1;
					$.each(data, function(index, element) {
						if(BankId == element.cbdtid){
							var RowCls = "active-tr";
							var CheckedStr = "checked = 'checked'";
						}else{
							var RowCls = "";
							var CheckedStr = "";
						}
						TableStr += '<tr class="BankRow '+RowCls+'"><td class="cboxlabel"><input type="radio" class="ChBank" '+CheckedStr+' name="ch_bank_name" id="'+element.cbdtid+'" value="'+element.cbdtid+'"/></td><td class="lboxlabel">'+Sno+'</td><td class="lboxlabel">'+element.bank_acc_no+'<input type="hidden" id="modal_bank_acc'+element.cbdtid+'" value="'+element.bank_acc_no+'"/></td><td class="lboxlabel">'+element.bank_name+'<input type="hidden" id="modal_bank_name'+element.cbdtid+'" value="'+element.bank_name+'"/></td><td class="lboxlabel">'+element.branch_address+'<input type="hidden" id="modal_br_add'+element.cbdtid+'" value="'+element.branch_address+'"/></td><td class="lboxlabel">'+element.ifsc_code+'<input type="hidden" id="modal_ifsc'+element.cbdtid+'" value="'+element.ifsc_code+'"/></td></tr>';
						Sno++;
					});
					TableStr += '</tbody>';
					TableStr += '</table>';
					BootstrapDialog.show({
						title: 'Bank Information',
						message: TableStr,
						onshown: function(dialogRef){
							/*var fruit = dialogRef.getModalBody().find('input').val();
							if($.trim(fruit.toLowerCase()) !== 'banana') {
								alert('Need banana!');
								return false;
							}*/
						},
						buttons: [{
							label: 'Change Bank',
							cssClass: 'btn btn-info',
							action: function(dialog) {
								var ChBank 		= $("input[name='ch_bank_name']:checked").val(); console.log(ChBank);
								var BankAcc  	= $("#modal_bank_acc"+ChBank).val(); console.log(BankAcc);
								var BankName 	= $("#modal_bank_name"+ChBank).val(); console.log(BankName);
								var BankBrAddr 	= $("#modal_br_add"+ChBank).val(); console.log(BankBrAddr);
								var BankIfsc 	= $("#modal_ifsc"+ChBank).val(); console.log(BankIfsc);
								$("#txt_bank_acc").val(BankAcc);
								$("#txt_bank_id").val(ChBank);
								$("#txt_bank_name").val(BankName);
								$("#txt_branch").val(BankBrAddr);
								$("#txt_ifsc").val(BankIfsc);
								dialog.close();
							}
						},{
							label: 'Close',
							cssClass: 'btn btn-info',
							action: function(dialog) {
								dialog.close();
							}
						}]
					});
				}
			}
		});
	});
	$("body").on("click",".ChBank", function(event){
		if($(this).is(':checked')){
			$(".BankRow").removeClass("active-tr");
			$(this).closest('tr').addClass("active-tr");
		}
	});
	function CalcRecAmt(RecCode){
		var NetAmt = $("#txt_net_amt").val();
		if(RecCode == "LCESS"){
			var RecPerc = $("#txt_rec_perc_0").val(); 
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "IT"){
			var NetAmtForIt = $("#txt_bill_amt_it").val();
			var RecPerc = $("#txt_it_perc").val();
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForIt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "IGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "CGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SGST"){
			var NetAmtForGst = $("#txt_bill_amt_gst").val();
			var GstRate = $("#txt_gst_rate").val();
			if((GstRate != 0)&&(GstRate != '')){
				var RecPerc = 1;
			}else{
				var RecPerc = 0;
			}
			$("#txt_rec_perc_0").val(RecPerc);
			var RecAmt = Number(NetAmtForGst) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		if(RecCode == "SD"){
			var RecPerc = $("#txt_rec_perc_0").val();
			var RecAmt = Number(NetAmt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt_0").val(RecAmt);
		}
		$("#cmb_rec_hoa_scode_0").chosen('destroy');
		$("#cmb_rec_hoa_scode_0").val(RecCode);
		$("#cmb_rec_hoa_scode_0").chosen();
	}
	$("body").on("change","#cmb_rec_desc_0", function(event){
		var RecCode = $(this).val();
		var RecDesc = $("#cmb_rec_desc_0 option:selected").text();
		if($("#"+RecCode).length){
			BootstrapDialog.alert("Duplicate Error : "+RecDesc+" recovery already added ");
			$("#cmb_rec_desc_0").chosen('destroy');
			$("#cmb_rec_desc_0").val('');
			$("#cmb_rec_desc_0").chosen();
			event.preventDefault();
			event.returnValue = false;
		}else{
			$("#txt_rec_perc_0").val('');
			$("#txt_rec_amt_0").val('');
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val('');
			$("#cmb_rec_hoa_scode_0").chosen();
			CalcRecAmt(RecCode);
		}
	});
	$("body").on("change","#txt_rec_perc_0", function(event){
		var RecCode = $("#cmb_rec_desc_0").val();
		CalcRecAmt(RecCode);
	});
	$("body").on("change","#txt_bill_amt_it", function(event){
		if($("#IT").length){
			var RowIndex = $("#IT").attr("data-id");
			var NetAmtForIt = $("#txt_bill_amt_it").val();
			var RecPerc = $("#txt_rec_perc"+RowIndex).val();
			var RecAmt = Number(NetAmtForIt) * Number(RecPerc) / 100;
				RecAmt = RecAmt.toFixed();
				$("#txt_rec_amt"+RowIndex).val(RecAmt);
		}
	});
	$("body").on("click","#ch_is_advance", function(event){
		if($(this).is(':checked')){
			$("#txt_adv_perc").removeClass("disable");
		}else{
			$("#txt_adv_perc").val('');
			$("#txt_adv_perc").addClass("disable");
			var NetPay = $("#txt_net_pay_amt").val();
			$("#txt_net_pay_adv_amt").val('');
		}
	});
	$("body").on("change","#txt_adv_perc", function(event){
		var AdvPerc = $(this).val();
		var NetPay = $("#txt_net_pay_amt").val();
		var AdvanceAmt = Number(NetPay)*Number(AdvPerc)/100;
			AdvanceAmt = AdvanceAmt.toFixed();
		$("#txt_net_pay_adv_amt").val(AdvanceAmt);
	});
	
	$("body").on("change","#cmb_hoa", function(event){
		var HoaId 	= $(this).val();
		$("#txt_hoa").val('');
		$("#txt_scode_id").val('');
		$("#txt_scode").val('');
		if(HoaId != ''){
			var HoaNo 	= $("#cmb_hoa option:selected").attr("data-hoa");
			var SCodeId = $("#cmb_hoa option:selected").attr("data-scodeid");
			var SCode 	= $("#cmb_hoa option:selected").text();
			$("#txt_hoa").val(HoaNo);
			$("#txt_scode_id").val(SCodeId);
			$("#txt_scode").val(SCode);
		}
	});
	
	
	
	$("body").on("click","#AddRec", function(event){
		var RecDesc 		= $("#cmb_rec_desc_0 option:selected").text();
		var RecId 			= $("#cmb_rec_desc_0").val();
		var RecIntPerc 		= $("#txt_rec_perc_0").val();
		var RecAmt 			= $("#txt_rec_amt_0").val();
		var RecHoaShCode 	= $("#cmb_rec_hoa_scode_0 option:selected").text();
		var RecHoaRecCode 	= $("#cmb_rec_hoa_scode_0").val();
		var RecHoaScodeId	= $("#cmb_rec_hoa_scode_0 option:selected").attr("data-id");
		if(RecId == ""){
			BootstrapDialog.alert("Please Select Recovery Description");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecAmt == ""){
			BootstrapDialog.alert("Please Enter Valid Amount");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecAmt == 0){
			BootstrapDialog.alert("Please Enter Valid Amount");
			event.preventDefault();
			event.returnValue = false;
		}else if(RecHoaRecCode == ""){
			BootstrapDialog.alert("Please Select HOA Short Code ");
			event.preventDefault();
			event.returnValue = false;
		}else if($("#"+RecId).length){
			BootstrapDialog.alert("Duplicate Error : "+RecDesc+" recovery already added ");
			event.preventDefault();
			event.returnValue = false;
		}else{
			var RowStr = '<tr data-id="'+RowIndex+'" id="'+RecId+'" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecDesc+'" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RecIntPerc+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecAmt+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecId+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
			$("#RecTable").find('tr:last').prev().after(RowStr);
			$("#txt_rec_perc_0").val('');
			$("#txt_rec_amt_0").val('');
			$("#cmb_rec_desc_0").chosen('destroy');
			$("#cmb_rec_desc_0").val('');
			$("#cmb_rec_desc_0").chosen();
			$("#cmb_rec_hoa_scode_0").chosen('destroy');
			$("#cmb_rec_hoa_scode_0").val('');
			$("#cmb_rec_hoa_scode_0").chosen();
			CalcTotalRec();
			RowIndex++;
		}
	});
	$("body").on("click",".DelRec", function(event){
		$(this).closest('tr').remove();
		CalcTotalRec();
	});
	function CalcTotalRec(){
		var TotalRecAmt = 0;
		var NetTotal = $("#txt_net_amt").val();
		$('input[name="txt_rec_amt[]"]').each(function(){ 
			var RecAmt = $(this).val();
			if(RecAmt != ''){
				TotalRecAmt = Number(TotalRecAmt) + Number(RecAmt);
			}
		});
		$("#txt_tot_rec_amt").val(TotalRecAmt.toFixed());
		var PayableAmt = Number(NetTotal) - Number(TotalRecAmt);
		$("#txt_net_pay_amt").val(PayableAmt.toFixed());
	}
	$("body").on("click","#btnGo", function(event){
		$("#txt_net_amt").val('');
		GetLCessSdInfo("LCESS","SUM");
	});
	$("body").on("click",".InfoData", function(event){
		GetLCessSdInfo("LCESS","ALL");
	});
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			$("#btnGo").trigger( "click" );
		}
	});
	
	function GetLCessSdInfo(Code,Type){
		var FromDate = $("#txt_from_date").val();
		var ToDate = $("#txt_to_date").val();
		if((FromDate != '')&&(FromDate != '')){
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/FindLCessSdInfo.php', 
				data: { FromDate: FromDate, ToDate: ToDate, Code: Code, Type: Type }, 
				dataType: 'json',
				success: function (data) {   //alert(data['computer_code_no']);
					if(data != null){
						if(Code == "LCESS"){
							if(Type == "SUM"){
								var LCessAmt = data['lcess_amt'];
								$("#txt_net_amt").val(LCessAmt);
							}
							if(Type == "ALL"){
								var LCessData = data;
								
								var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
								TableStr += '<thead><tr><th class="lboxlabel">S.No</th><th class="lboxlabel">CCODE.</th><th class="lboxlabel">RAB NO.</th><th class="lboxlabel">NAME OF CONTRACTOR</th><th class="lboxlabel">NAME OF WORK</th><th class="lboxlabel">DATE OF PAYMENT</th><th class="lboxlabel">BILL VALUE</th><th class="lboxlabel">LABOUR CESS AMOUNT</th></tr></thead>';
								TableStr += '<tbody>';
								var Sno = 1; var TotalAmt = 0;
								$.each(LCessData, function(index, element) {
									TotalAmt = Number(TotalAmt) + Number(element.lw_cess_amt);
									TableStr += '<tr><td class="lboxlabel">'+Sno+'</td><td class="lboxlabel">'+element.computer_code_no+'</td><td class="lboxlabel">'+element.bill_rbn+'</td><td class="lboxlabel">'+element.cont_name+'</td><td class="lboxlabel">'+element.work_name+'</td><td class="lboxlabel">'+element.abstract_net_amt+'</td><td class="lboxlabel">'+element.payment_dt+'</td><td class="rboxlabel">'+element.lw_cess_amt+'</td></tr>';
									Sno++;
								});
								TableStr += '<tr><td class="rboxlabel" colspan="7">Total LCess Amount (Rs.)&nbsp;</td><td class="rboxlabel">'+TotalAmt.toFixed(2)+'</td></tr>';
								TableStr += '</tbody>';
								TableStr += '</table>';
								BootstrapDialog.show({
									title: 'LCESS Information',
									message: TableStr,
									onshow: function(dialogRef){
										$(".modal-dialog").css("width","80%");
									},
									onhidden: function(dialogRef){
										$(".modal-dialog").css("width","60%");
									},
									buttons: [{
										label: 'OK',
										cssClass: 'btn btn-info',
										action: function(dialog) {
											dialog.close();
										}
									}]
								});
								
							}
						}
					}
				}
			});
		}
	}
	
	
	var cGst = 0; var sGst = 0; var iGst = 0;
	$("body").on("change","#cmb_contractor", function(event){
		cGst = 0; sGst = 0; iGst = 0; RowIndex = 1;
		$("#txt_contractor").val('');
		$("#txt_bank_acc").val('');
		$("#txt_bank_id").val('');
		$("#txt_bank_name").val('');
		$("#txt_branch").val('');
		$("#txt_ifsc").val('');
		
		if($(this).val() != ''){
			var ContName = $("#cmb_contractor option:selected").text();
			 $("#txt_contractor").val(ContName);
		}
		//$(".inp").val('');
		/*$("#cmb_contractor").chosen('destroy');
		$("#cmb_rec_desc_0").chosen('destroy');
		$("#cmb_rec_hoa_scode_0").chosen('destroy');
		$(".inp").removeClass("disable");
		$(".inp").val('');
		$(".Ldc").addClass('hide');
		$(".LdcStatus").removeClass('hide');
		$("#cmb_rec_desc_0").chosen();
		$("#cmb_rec_hoa_scode_0").chosen();
		$("#cmb_contractor").chosen();*/
		var ContId = $("#cmb_contractor").val();
		//var Ccno = $("#txt_ccno").val();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindVoucherDataMisc.php', 
			data: { ContId: ContId }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					var WData 	 	= data['WData'];
					var RABData  	= data['RABData'];
					var RECData  	= data['RECData'];
					var CONTData 	= data['CONTData'];
					var BKData   	= data['BKData'];
					var RecHoaData  = data['RecHoaData'];
					//var TableStr = '<table class="table table-bordered rectable">';
					//if(WData != null){
						/*$("#txt_work_name").val(WData['work_name']);
						$("#txt_sheetid").val(WData['sheet_id']);
						$("#txt_globid").val(WData['globid']);
						$("#txt_bank_id").val(WData['cbdtid']);
						$("#cmb_gst_inc_exc").val(WData['gst_inc_exc']);
						$("#txt_gst_rate").val(WData['gst_perc_rate']);
						$("#cmb_rec_desc_0").chosen("destroy");*/
						/*if(WData['is_gst_appl'] != 'Y'){
							$("#txt_gst_rate").val("0.00");
							$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', true);
							$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', true); 
							$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', true); 
							if(WData['CGST'] != null){ cGst = WData['CGST']; }
							if(WData['SGST'] != null){ sGst = WData['SGST']; }
							if(WData['IGST'] != null){ iGst = WData['IGST']; }
						}else{
							$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', false); 
							$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', false);
							$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', false);
						}
						if(WData['is_less_appl'] != 'Y'){
							$("#cmb_rec_desc_0 option[value='LCESS']").attr('disabled', true); 
						}else{
							$("#cmb_rec_desc_0 option[value='LCESS']").attr('disabled', false); 
						}
						$("#cmb_rec_desc_0").chosen();
					}*/
					if(CONTData != null){
						//$("#txt_pan_no").val(CONTData['pan_no']);
						//$("#txt_gst_no").val(CONTData['gst_no']);
						$("#cmb_contractor").chosen('destroy');
						$("#cmb_contractor").val(CONTData['contid']);
						$("#cmb_contractor").chosen();
						//$("#txt_it_perc").val(CONTData['it_rate']);
						//$(".Ldc").addClass("hide");
						/*if(CONTData['is_ldc_appl'] == "Y"){
							$("#txt_ldc_cert_no").val(CONTData['ldc_certi_no']);
							$("#txt_ldc_amt").val(CONTData['ldc_max_amt']);
							var LdcValid = moment(CONTData['ldc_validity']).format('DD/MM/YYYY');
							$("#txt_ldc_valid_to").val(LdcValid);
							
							var TodayDate = moment().format('DD/MM/YYYY');
							var dt1 = LdcValid.split("/");
							var dt2 = TodayDate.split("/");
							var LdcValidStr 	= new Date(dt1[2], dt1[1]-1, dt1[0]);  // -1 because months are from 0 to 11
							var TodayDateStr   	= new Date(dt2[2], dt2[1]-1, dt2[0]);
							if(LdcValidStr > TodayDateStr){
								$("#LdcValid").removeClass("hide");
								$("#txt_ldc_status").val("V");
								$("#txt_it_perc").val(CONTData['ldc_rate']);
							}else{
								$("#LdcInValid").removeClass("hide");
								$("#txt_ldc_status").val("NV");
								$("#txt_it_perc").val(CONTData['it_rate']);
							}
						}*/
						/*if(CONTData['gst_type'] != null){
							$("#cmb_rec_desc_0").chosen("destroy");
							if(CONTData['gst_type'] == "I"){
								if(WData['CGST'] != null){ cGst = 0; }
								if(WData['SGST'] != null){ sGst = 0; }
								if(WData['IGST'] != null){ iGst = WData['IGST']; }
								$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', true);
								$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', true); 
								$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', false); 
							}else{
								if(WData['CGST'] != null){ cGst = WData['CGST']; }
								if(WData['SGST'] != null){ sGst = WData['SGST']; }
								if(WData['IGST'] != null){ iGst = 0; }
								$("#cmb_rec_desc_0 option[value='SGST']").attr('disabled', false);
								$("#cmb_rec_desc_0 option[value='CGST']").attr('disabled', false); 
								$("#cmb_rec_desc_0 option[value='IGST']").attr('disabled', true);
							}
							$("#cmb_rec_desc_0").chosen();
						}*/
					}
					if(BKData != null){
						$.each(BKData, function(index, element) { 
							if(element.active_status == 1){
								$("#txt_bank_acc").val(element.bank_acc_no);
								$("#txt_bank_name").val(element.bank_name);
								$("#txt_branch").val(element.branch_address);
								$("#txt_ifsc").val(element.ifsc_code);
								$("#txt_bank_id").val(element.cbdtid);
							}
						});
					}
					/*if(RABData != null){
						$("#txt_rbn").val(RABData['rbn']);
						if((RABData['upto_date_total_amount'] == null)||(RABData['upto_date_total_amount'] == "")){
							RABData['upto_date_total_amount'] = 0;
						}
						if((RABData['dpm_total_amount'] == null)||(RABData['dpm_total_amount'] == "")){
							RABData['dpm_total_amount'] = 0;
						}
						if((RABData['slm_total_amount'] == null)||(RABData['slm_total_amount'] == "")){
							RABData['slm_total_amount'] = 0;
						}
						if((RABData['secured_adv_amt'] == null)||(RABData['secured_adv_amt'] == "")){
							RABData['secured_adv_amt'] = 0;
						}
						if((RABData['mob_adv_amt'] == null)||(RABData['mob_adv_amt'] == "")){
							RABData['mob_adv_amt'] = 0;
						}
						if((RABData['slm_total_amount_esc'] == null)||(RABData['slm_total_amount_esc'] == "")){
							RABData['slm_total_amount_esc'] = 0;
						}
						if((RABData['pl_mac_adv_amt'] == null)||(RABData['pl_mac_adv_amt'] == "")){
							RABData['pl_mac_adv_amt'] = 0;
						}
						$("#txt_upto_dt_amt").val(RABData['upto_date_total_amount']); 
						$("#txt_ded_prev_amt").val(RABData['dpm_total_amount']);
						$("#txt_bill_value").val(RABData['slm_total_amount']);
						$("#txt_sec_adv_amt").val(RABData['secured_adv_amt']);
						$("#txt_mob_adv_amt").val(RABData['mob_adv_amt']);
						$("#txt_esc_amt").val(RABData['slm_total_amount_esc']);
						$("#txt_pm_adv_amt").val(RABData['pl_mac_adv_amt']);
						var NetAmount = Number(RABData['slm_total_amount']) + Number(RABData['secured_adv_amt'])+ Number(RABData['mob_adv_amt'])+ Number(RABData['slm_total_amount_esc']);
						NetAmount = NetAmount.toFixed(2);
						$("#txt_net_amt").val(NetAmount);
						$("#txt_net_pay_amt").val(NetAmount);
						$("#txt_tot_rec_amt").val('0.00');
						$("#txt_bill_amt_gst").val(RABData['bill_amt_for_gst']);
						$("#txt_bill_amt_it").val(RABData['bill_amt_for_gst']);
					}*/
					if(RECData != null){
						/*var RowStr = ''; var RecHoaShCode = ''; var RecHoaRecCode = ''; var RecHoaScodeId = '';
						if(RECData['lw_cess_amt'] != 0){
							if(RecHoaData['LCESS'] != null){
								var RecHoaDt  = RecHoaData['LCESS'];
								RecHoaShCode  =  RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="LCESS" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCess" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="LCESS"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['lw_cess_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['lw_cess_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['mob_adv_amt_rec'] != 0){
							if(RecHoaData['MOB'] != null){
								var RecHoaDt  = RecHoaData['MOB'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="MOB" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOB"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_amt_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['pl_mac_adv_rec'] != 0){
							if(RecHoaData['PM'] != null){
								var RecHoaDt  = RecHoaData['PM'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="PM" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M.Adv. Rec." required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PM"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_rec']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['hire_charges'] != 0){
							if(RecHoaData['HIRE'] != null){
								var RecHoaDt  = RecHoaData['HIRE'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="HIRE" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Hire Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="HC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['hire_charges']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['sgst_tds_amt'] != 0){
							if(RecHoaData['SGST'] != null){
								var RecHoaDt  = RecHoaData['SGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="SGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['cgst_tds_amt'] != 0){
							if(RecHoaData['CGST'] != null){
								var RecHoaDt  = RecHoaData['CGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="CGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="CGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['cgst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['cgst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['igst_tds_amt'] != 0){
							if(RecHoaData['IGST'] != null){
								var RecHoaDt  = RecHoaData['IGST'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="IGST" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IGST"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['igst_tds_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['igst_tds_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['incometax_amt'] != 0){
							if(RecHoaData['IT'] != null){
								var RecHoaDt  = RecHoaData['IT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="IT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="IT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['incometax_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['incometax_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['sd_amt'] != 0){
							if(RecHoaData['SD'] != null){
								var RecHoaDt  = RecHoaData['SD'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="SD" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="SD"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['sd_percent']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['sd_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['water_cost'] != 0){
							if(RecHoaData['WC'] != null){
								var RecHoaDt  = RecHoaData['WC'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="WC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Water Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="WC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['water_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['electricity_cost'] != 0){
							if(RecHoaData['EC'] != null){
								var RecHoaDt  = RecHoaData['EC'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="EC" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Electricity Charges" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="EC"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value=""/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['electricity_cost']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['mob_adv_int_amt'] != 0){
							if(RecHoaData['MOBINT'] != null){
								var RecHoaDt  = RecHoaData['MOBINT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="MOBINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="Mob. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="MOBINT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['mob_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['mob_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						if(RECData['pl_mac_adv_int_amt'] != 0){
							if(RecHoaData['PMINT'] != null){
								var RecHoaDt  = RecHoaData['PMINT'];
								RecHoaShCode  = RecHoaDt['shortcode'];
								RecHoaRecCode = RecHoaDt['rec_code'];
								RecHoaScodeId = RecHoaDt['shortcode_id'];
							}else{
								RecHoaShCode  = "";//RecHoaDt['rec_perc'];
								RecHoaRecCode = "";
								RecHoaScodeId = "";
							}
							RowStr += '<tr data-id="'+RowIndex+'" id="PMINT" style="background-color:#FFF"><td align="center"><input type="text" name="txt_rec_desc[]" id="txt_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="P&M. Adv. Interest" required /><input type="hidden" name="hid_rec_desc[]" id="hid_rec_desc'+RowIndex+'" class="dynamicboxlg disable" value="PMINT"></td><td align="center"><input type="text" name="txt_rec_perc[]" id="txt_rec_perc'+RowIndex+'" class="dynamicboxlg disable" value="'+RECData['pl_mac_adv_int_perc']+'"/></td><td align="center"><input type="text" name="txt_rec_amt[]" id="txt_rec_amt'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RECData['pl_mac_adv_int_amt']+'"/></td><td align="center"><input type="text" name="txt_rec_hoa_shcode[]" id="txt_rec_hoa_shcode'+RowIndex+'" class="dynamicboxlg disable" required  value="'+RecHoaShCode+'"/><input type="hidden" name="hid_rec_hoa_rec_code[]" id="hid_rec_hoa_rec_code'+RowIndex+'" class="dynamicboxlg disable" value="'+RecHoaRecCode+'" /><input type="hidden" name="hid_rec_hoa_shcode_id[]" id="hid_rec_hoa_shcode_id'+RowIndex+'" class="dynamicboxlg disable"  value="'+RecHoaScodeId+'" /></td><td align="center"><i class="fa fa-times-circle sqdel ptr DelRec" style="font-size:24px"></i></td></tr>';
							RowIndex++
						}
						$("#RecTable").find('tr:last').prev().after(RowStr);
						CalcTotalRec();*/
					}
				}
			}
		});
	});
	
});
</script>
<style>
	.chosen-container .chosen-results{
		max-height:150px;
	}
</style>
</body>
</html>

