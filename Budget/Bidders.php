<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Bidders Entry';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0; $success = "";
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];


function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}


////////////////////////

//$result = mysqli_query($dbConn, "SELECT * FROM contractor ORDER BY contid asc");
//$result = mysqli_query($dbConn,"SELECT * FROM contractor ORDER BY ts_date asc");// ORDER BY type asc, group_id asc");
//echo $result;exit;

////////////////////////

if(isset($_POST['btn_save']) == ' Submit '){
	$SuccInsert = 0;
	$ContTitle   	= $_POST['cmb_cont_tittle'];
	$ContName   	= $_POST['txt_cont_name'];
	$ContAddr  		= $_POST['txt_cont_addr'];
	$ContState 		= $_POST['cmb_state'];
	$ContGST 		= $_POST['txt_gst_no'];
	$ContPan		= $_POST['txt_pan_no'];
	$ContPanType	= $_POST['pantype'];
	$ContGSTType	= $_POST['gsttype'];
	$ContFirstCode 	= substr($ContName, 0, 1);
	
	$MaxContCode = 0; $TempCodeStr = $ContFirstCode."-";
	$SelectCodeQuery = "SELECT MAX(REPLACE(cont_code_frfcf,'$TempCodeStr','')) as cont_code FROM contractor WHERE cont_code_frfcf LIKE '".$ContFirstCode."%'";
	$SelectCodeSql 	 = mysqli_query($dbConn,$SelectCodeQuery);
	if($SelectCodeSql == true){
		if(mysqli_num_rows($SelectCodeSql)>0){
			$List = mysqli_fetch_object($SelectCodeSql);
			$MaxContCode = $List->cont_code;
		}
	}
	$MaxContCode++;
	if(strlen($MaxContCode) == 1){
		$MaxContCode = "0".$MaxContCode;
	}
	$ContCode = $ContFirstCode."-".$MaxContCode;
	//echo $ContCode; exit;
	/*$ContLDCChk		= $_POST['ldccheck'];
	$ContLdcCerNum	= $_POST['txt_cert_num'];
	$ContLdcCerDt	= $_POST['txt_cert_valid_date'];
	$ContLdcITPerc	= $_POST['txt_itperc'];*/
	//echo $ContLdcCerDt;exit;
	$ContLDCChk		 = $_POST['ldccheck'];
	$ContLdcCerNum	 = $_POST['txt_cert_num'];
	$ContLdcMaxAmt	 = $_POST['txt_cert_max_amt'];
	$ContLdcCerFrDt  = '';
	$ContLdcCerToDt  = '';
	if($ContLDCChk != 'Y'){
		if($_POST['txt_cert_valid_from_date'] != ''){
			$ContLdcCerFrDt  = dt_format($_POST['txt_cert_valid_from_date']);
		}
		if($_POST['txt_cert_valid_date'] != ''){
			$ContLdcCerToDt  = dt_format($_POST['txt_cert_valid_date']);
		}
	}
	$ContLdcITPerc	 = $_POST['txt_itperc'];

	$ContAccHoldNameStr = $_POST["txt_acc_hold_name"];
	$ContAccHoldNumStr  = $_POST["txt_acc_hold_num"];
	$ContBankNameStr	  = $_POST["txt_bankname"];
	$ContBranchStr		  = $_POST["txt_branch_addr"];
	$ContIfscStr		  = $_POST["txt_ifsc_code"];
	$ContProofUploadStr = $_FILES['txt_file_upload']['name'];


	if($ContName == NULL){
		$msg = "Please Enter Contractor Name..!!";
	}else if($ContAddr == NULL){
		$msg = "Please Enter Contractor Address..!!";
	}else if($ContState == NULL){
		$msg = "Please Select State..!!";
	}else if($ContGST == NULL){
		$msg = "Please Enter Contractor GST Number..!!";
	}else if($ContPan == NULL){
		$msg = "Please Enter Contractor PAN Number..!!";
	}else if($ContPanType == NULL){
		$msg = "Please Select PAN Type..!!";
	}else if($ContLDCChk == NULL){
		$msg = "Please LDC Certificate Yes/No..!!";
	}else{
		$SuccInsert = 1;
	}
	/*if($ContLdcCerNum == NULL){
		$msg = "Please LDC Certificate Number..!!";
	}else if($ContLdcCerDt == NULL){
		$msg = "Please LDC Certificate Date..!!";
	}else if($ContLdcITPerc == NULL){
		$msg = "Please LDC IT Percantage..!!";
	}*/
	// /echo $SuccInsert;exit;
	//echo $ContGSTType;exit;

	// else if($ContGST != NULL){
	// 	echo $ContGST;exit;
	// 	$ConGSTQuery = mysqli_query($dbConn, "SELECT name_contractor FROM contractor WHERE gst_no = '$ContGST'");
	// 	if($ConGSTQuery == true){
	// 		while ($row = $ConGSTQuery->fetch_assoc()) {
	// 			$msg = "Sorry..Pan Numer Aldready Entered for ".$row['name_contractor']."..!!";
	// 		}
	// 	}else{
	// 		return false;
	// 	}
	// }
	//$result = mysqli_query($dbConn,"SELECT * FROM contractor ORDER BY contid asc");// ORDER BY type asc, group_id asc"); ldc_max_amt  ldc_validty_from
	if($SuccInsert == 1){
		$InsertQuery = "INSERT INTO contractor SET  cont_code_frfcf = '$ContCode', contractor_title = '$ContTitle', name_contractor = '$ContName', addr_contractor = '$ContAddr', state_contractor ='$ContState',
		pan_no = '$ContPan', gst_no = '$ContGST', pan_type = '$ContPanType', gst_type = '$ContGSTType', is_ldc_appl = '$ContLDCChk', ldc_certi_no = '$ContLdcCerNum',
		ldc_max_amt = '$ContLdcMaxAmt', ldc_validty_from = '$ContLdcCerFrDt', ldc_validity = '$ContLdcCerToDt', ldc_rate = '$ContLdcITPerc',
		active = 1, createddate = NOW(), userid = '$UserId'";

		$InsertQuery = mysqli_query($dbConn,$InsertQuery);

		$LastInsertid = mysqli_insert_id($dbConn);
		/*if(count($ContAccHoldNameStr)>0){
			foreach($ContAccHoldNameStr as $Key => $Value){
				$ContAccHoldName 		= $ContAccHoldNameStr[$Key];
				$ContAccHoldNum  		= $ContAccHoldNumStr[$Key];
				$ContBankName    		= $ContBankNameStr[$Key];
				$ContBranch      		= $ContBranchStr[$Key];
				$ContIfsc        		= $ContIfscStr[$Key];
				$ContProofUpload 		= $ContProofUploadStr[$Key];
	
				$insert_query	= "INSERT INTO contractor_bank_detail SET contid='$LastInsertid', bank_acc_hold_name='$ContAccHoldName',bank_acc_no='$ContAccHoldNum',
				bank_name='$ContBankName', branch_address='$ContBranch', ifsc_code='$ContIfsc', bk_dt_cr_by = 'EIC', active='1'";
				$insert_sql = mysqli_query($dbConn,$insert_query);
			}
		}*/
		if($InsertQuery == true){
			$msg = "Contractor Details Saved Successfully..!!";
			UpdateWorkTransaction($GlobID,0,0,"W","Contractor details Added by ".$UserId."","");
			$success = 1;
		}else{
			$msg = "Error : Contractor Details Not Saved.. Please Try Again.";
			UpdateWorkTransaction($GlobID,0,0,"W","Contractor details tried to Add by ".$UserId." but not Added","");
			$success = 0;
		}
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function preview() {
		thumb.src=URL.createObjectURL(event.target.files[0]);
		//window.open(thumb.src,'Image','width=largeImage.stylewidth,height=largeImage.style.height,resizable=1');
	}
	function noBack() { window.history.forward(); }
	function ViewBidder(){
		url = "BiddersList.php";
		window.location.replace(url);
	}
</script>
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
	/* .lboxlabel {
  color: #04498E;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
} */
	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
</style>

<script type="text/javascript" language="javascript">
</script>
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
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Bidder's / Contractor's Details Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
																	<div class="row clearrow"></div>												
																	<div class="row">
																		<div class="div4 lboxlabel">
																			Contractor Title & Name
																		</div>
																		<div class="div1 pd-lr-1">
																			<select name="cmb_cont_tittle" id="cmb_cont_tittle" class="tboxsmclass">
																				<option value=""> ---Sel--- </option>
																				<option value="SHRI">SHRI</option>
																				<option value="M/S">M/S</option>
																			</select>
																			<input type="hidden" name='txt_hid_cont_id' id='txt_hid_cont_id' class='tboxsmclass' value='<?php if(isset($ContID)){ echo $ContID; } ?>'>
																		</div>
																		<div class="div5">
																			<input type="text" name='txt_cont_name' id='txt_cont_name' class="tboxsmclass" >
																		</div>
																		<div class="div1 rboxlabel">
																			Code&emsp;
																		</div>
																		<div class="div1">
																			<input type="text" maxlength="15" name='txt_cont_code_frfcf' id='txt_cont_code_frfcf' class='tboxsmclass' value='<?php if(isset($ContCodeEdit)){ echo $ContCodeEdit; } ?>' readonly="">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			Contractor Address
																		</div>
																		<div class="div8">
																			<textarea class="tboxsmclass" name="txt_cont_addr" id="txt_cont_addr" cols="24" rows="2"></textarea>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			State
																		</div>
																		<div class="div8">
																			<select name="cmb_state" id="cmb_state" class="tboxsmclass">
																				<option value=""> ------ Select ------ </option>
																				 <?php echo $objBind->BindStates(''); ?>
																			</select>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			GST Type
																		</div>
																		<div class="div4">
																			<div class="inputGroup">
																				<input type="radio" class="cgstcheck readolyclass" name='gsttype' id='gst_type_1' Value="C">
																				<label for="gst_type_1" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; CGST</label>
																			</div>
																		</div>
																		<div class="div4" style="padding-left:10px;">
																			<div class="inputGroup">
																				<input type="radio" class="igstcheck readolyclass" name='gsttype' id='gst_type_2' Value="I">
																				<label for="gst_type_2" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; IGST</label>
																			</div>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			GST No.
																		</div>
																		<div class="div8" >
																			<input type="text" name='txt_gst_no' id='txt_gst_no' class="tboxsmclass" >
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			PAN No.
																		</div>
																		<div class="div8">
																			<input type="text" name='txt_pan_no' id='txt_pan_no' class="tboxsmclass" >
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			PAN Type
																		</div>
																		<div class="div4">
																			<div class="inputGroup">
																				<input type="radio" name='pantype' id='pan_type_1' Value="I">
																				<label for="pan_type_1" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Individual</label>
																			</div>
																		</div>
																		<div class="div4" style="padding-left:10px;">
																			<div class="inputGroup">
																				<input type="radio" name='pantype' id='pan_type_2' Value="O">
																				<label for="pan_type_2" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Others</label>
																			</div>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div4 lboxlabel">
																			Do You have LDC Certificate</br>(for Income Tax Recovery)
																		</div>
																		<div class="div4">
																			<div class="inputGroup">
																				<input type="radio" class="isappcheck" name='ldccheck' id='ldc_yes' Value="Y">
																				<label for="ldc_yes" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; YES</label>
																			</div>
																		</div>
																		<div class="div4" style="padding-left:10px;">
																			<div class="inputGroup">
																				<input type="radio" class="isappcheck" name='ldccheck' id='ldc_no' Value="N">
																				<label for="ldc_no" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; NO</label>
																			</div>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	
																	<div class="card-header isappcheck inkblue-card" align="left">&nbsp;LDC Certificate Details Entry</div>
																		<table class="dataTable isappcheck" align="center" width="100%" id="table0">
																			<tr class="label" style="background-color:#FFF">
																				<td rowspan="2" align="center">Certificate No.</td>
																				<td rowspan="2" align="center">Maximum Amount</br>(Current Financial Year)</td>
																				<td colspan="2" align="center">LDC Certificate Validity Period</td>
																				<td rowspan="2" align="center">IT Percentage</td>
																			</tr>
																			<tr class="label" style="background-color:#FFF">
																				<td align="center">From Date</td>
																				<td align="center">To Date</td>
																			</tr>
																			<tr>
																				<td align="center"><input type="text" class="tboxclass" name="txt_cert_num" id="txt_cert_num"></td>
																				<td align="center"><input type="text" class="tboxclass" name="txt_cert_max_amt" id="txt_cert_max_amt" onKeyPress="return event.charCode >= 48 && event.charCode <= 57"></td>
																				<td align="center"><input type="text" class="tboxclass datepicker" name="txt_cert_valid_from_date" id="txt_cert_valid_from_date" readonly=""></td>
																				<td align="center"><input type="text" class="tboxclass datepicker" name="txt_cert_valid_date" id="txt_cert_valid_date" readonly=""></td>
																				<td align="center"><input type="text" class="tboxclass" name="txt_itperc" id="txt_itperc"></td>
																			</tr>
																		</table>
																	</div>

																	<!--<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row isappcheck">
																		<div class="div4 dataFont">
																			Certificate No.
																		</div>
																		<div class="div8">
																			<input type="text" class="tboxsmclass" name='txt_cert_num' id='txt_cert_num'>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row isappcheck">
																		<div class="div4 dataFont">
																			Maximum Amount For Current Financial Year
																		</div>
																		<div class="div3">
																			<input type="text" class="tboxsmclass" name='txt_cert_num' id='txt_cert_num'>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row isappcheck">
																		<div class="div4 dataFont">
																			Certificate Validity From
																		</div>
																		<div class="div3">
																			<input type="text" readonly = "" class="tboxsmclass datepicker" name='txt_cert_valid_from_date' id='txt_cert_valid_from_date'>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row isappcheck">
																		<div class="div4 dataFont">
																			Certificate Validity Upto
																		</div>
																		<div class="div3">
																			<input type="text" readonly = "" class="tboxsmclass datepicker" name='txt_cert_valid_date' id='txt_cert_valid_date'>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row isappcheck">
																		
																		<div class="div4 dataFont">
																			IT Percentage
																		</div>
																		<div class="div3">
																			<input type="text" class="tboxsmclass" name='txt_itperc' id='txt_itperc'>
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row clearrow"></div>														
																	<div class="card-header inkblue-card" align="left">&nbsp;Bank Details Entry</div>
																	<table class="dataTable etable " align="center" width="100%" id="table1">
																		<tr class="label" style="background-color:#FFF">
																			<td align="center">A/c Holder Name </td>
																			<td align="center">A/c Number</td>
																			<td align="center">Bank Name</td>
																			<td align="center">Branch Address</td>
																			<td align="center">IFSC Code</td>
																			<td align="center">Proof</td>
																			<td align="center">Action</td>
																		</tr>
																		<tr>
																			<td align="center"><input type="text" class="tboxclass"  name="txt_acc_hold_name_0" id="txt_acc_hold_name_0"></td>
																			<td align="center"><input type="text" class="tboxclass"  name="txt_acc_hold_num_0"  id="txt_acc_hold_num_0"></td>
																			<td align="center"><input type="text" class="tboxclass"  name="txt_bankname_0" 	  id="txt_bankname_0"></td>
																			<td align="center"><input type="text" class="tboxclass"  name="txt_branch_addr_0"   id="txt_branch_addr_0"></td>
																			<td align="center"><input type="text" class="tboxclass"  name="txt_ifsc_code_0" 	  id="txt_ifsc_code_0"></td>
																			<td align="center" id="uploadviewbtn">
																			<img id="uploadPreview" style="width: 100px; height: 100px;" />
																				<input id="uploadImage" type="file" name="myPhoto" onchange="PreviewImage();" />
																				<input type="file" class="text" name="txt_file_upload_0" id="txt_file_upload_0">
																				<img id="idimage" name="uploaded_file" src="" width="150px "/>
																			</td>
																			<td align="center" style="vertical-align:middle;"><input type="button" name="emp_add" id="emp_add" value="ADD" class="fa btn btn-info"></td>
																			<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																		</tr>
																	</table>-->
																</div>
															</div>
															<div class="div12" align="center">
													         <input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="submit" value=" Submit "/>
												        	<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewBidder();"/> -->
												        </div>
												        <div class="row clearrow"></div>	
												</div>
												<div class="row clearrow"></div>												
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
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
	$('#cmb_state').chosen();
	$(document).ready(function() {
		var msg = "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		var titletext = "";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				if(success == 1){
					BootstrapDialog.alert(msg);
				}else{
					BootstrapDialog.alert(msg);
				}
					
			}
		}
		
		$("body").on("change","#txt_cont_name", function(event){
			var ContName = $(this).val(); 
			var ContId = $("#txt_hid_cont_id").val();
			if(ContId == ''){
				$("#txt_cont_code_frfcf").val('');
				$.ajax({ 
					type: 'POST', 
					url: '../Accounts/ajax/GenerateContCode.php', 
					data: ({ ContName: ContName }), 
					success: function (data) {
						//alert(data);			
						if(data != null){
							$("#txt_cont_code_frfcf").val(data);
						}		
					}
				});
			}
		});
		
		$("body").on("change","#cmb_state", function(event){
			var stateval = $(this).val(); //alert(stateval);
			if(stateval == 'TN'){
				$('.igstcheck').prop('checked', false);
				$('.cgstcheck').prop('checked', true);
			}else{
				$('.igstcheck').prop('checked', true);
				$('.cgstcheck').prop('checked', false);
			}
			/*$.ajax({
				type: 'POST',
				url: 'ajax/GetDetails.php',
				data: {stateval:stateval, page: 'GSTTYPE'},
				dataType: 'json',
				success: function (data) {
					//alert(data);
					if(data != null){
						if(data == 1){
							$('.igstcheck').prop('checked', false);
							$('.cgstcheck').prop('checked', true);
						}else{
							$('.igstcheck').prop('checked', true);
							$('.cgstcheck').prop('checked', false);
						}
					}
				}
			});*/
		});
		$('.readolyclass').click(function(){
			return false;
		});
		$("body").on("change","#txt_pan_no", function(event){
			//BootstrapDialog.alert(1);
			//var ContLDCCertCkaaa = $(".isappcheck:checked").val(); alert(ContLDCCertCkaaa);
			var contpanval = $("#txt_pan_no").val();
			$.ajax({ 
				type: 'POST', 
				dataType:'json',
				url: 'ajax/GetPanVerification.php', 
				data: ({ contpanval: contpanval}), 
				success: function (data) {
					//alert(data);			
					if(data != null){
						BootstrapDialog.alert("Sorry..PAN Numer Aldready Entered for "+data+"..!!");
						$("#txt_pan_no").val('');
					}else{
						return false;
					}		
				}
			});
		});
		/*$("body").on("change","#txt_file_upload", function(event){
			//alert(1);
			var fileval = $("#txt_file_upload").val();
			if(fileval != ""){
				var Row1Str = '<input type="button" class="fa btn btn-info" name="img_view" id="img_view" value="VIEW" onclick="newTabImage()">'; 
			$("#uploadviewbtn").append(Row1Str);
				
			}
		});*/

		$("body").on("change","#txt_gst_no", function(event){
			//BootstrapDialog.alert(1);
			var contgstval = $("#txt_gst_no").val();
			$.ajax({ 
				type: 'POST', 
				dataType:'json',
				url: 'ajax/GetGstVerification.php', 
				data: ({ contgstval: contgstval}), 
				success: function (data) {
					//alert(data);				
					if(data != null){
						BootstrapDialog.alert("Sorry..GST Numer Aldready Entered for "+data+"..!!");
						$("#txt_gst_no").val('');
					}else{
						return false;
					}
				}
			});
		});
		$(".isappcheck").hide();
		$("body").on("click",".isappcheck", function(event){
			var radval = $(this).val(); //alert(radval);
			if(radval == 'Y'){
				$(".isappcheck").show();
			}else if(radval == 'N'){
				$("#txt_itperc").val('');
				$("#txt_cert_valid_date").val('');
				$("#txt_cert_num").val('');
				$(".isappcheck").hide();
			}
		});
		$("body").on("click","#btn_save", function(event){

			var ContNameVal  	= $("#txt_cont_name").val();
			var ContAddrVal  	= $("#txt_cont_addr").val();
			var ContStateVal  = $("#cmb_state").val();
			var ContGSTVal   	= $("#txt_gst_no").val();
			var ContPanVal   	= $("#txt_pan_no").val();
			var ContLDCCertCk = $(".isappcheck:checked").val(); //alert(ContLDCCertCk);
			//var ContLDCCertCk	= $("#ldccheck").val();
			var ContLDCNum  	= $("#txt_cert_num").val();
			var ContLDCValidDt = $("#txt_cert_valid_date").val();
			var ContLDCIT  = $("#txt_itperc").val();

			if(ContNameVal == ""){
				BootstrapDialog.alert("Contractor name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContAddrVal == ""){
				BootstrapDialog.alert("Contractor address should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContStateVal == ""){
				BootstrapDialog.alert("Please Select State Type..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContGSTVal == ""){
				BootstrapDialog.alert("GST Number should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContPanVal == ""){
				BootstrapDialog.alert("PAN Number should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if ($('input[name="pantype"]:checked').length == 0){
				BootstrapDialog.alert("Please Select PAN Type..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if ($('input[name="ldccheck"]:checked').length == 0){
				BootstrapDialog.alert("Please Select LDC Certificate Yes/No..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContLDCCertCk == "Y"){
				if(ContLDCNum == ""){
					BootstrapDialog.alert("LDC Certificate Number should not be empty..!!");
					event.preventDefault();
					event.returnValue = false;
				}else if(ContLDCValidDt == ""){
					BootstrapDialog.alert("Certificate Validity Upto should not be empty..!!");
					event.preventDefault();
					event.returnValue = false;
				}else if(ContLDCIT == ""){
					BootstrapDialog.alert("IT Percentage should not be empty..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}/*else if($('#txt_acc_hold_name').length == 0){
				if ($('#txt_acc_hold_name_0').length == 0){
					BootstrapDialog.alert("Please Enter Account Holder Name..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}else if ($('#txt_acc_hold_num').length == 0){
				if ($('#txt_acc_hold_num_0').length == 0){
					BootstrapDialog.alert("Please Enter Account Number..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}else if ($('#txt_bankname').length == 0){
				if ($('#txt_bankname_0').length == 0){
					BootstrapDialog.alert("Please Enter Bank Name..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}else if ($('#txt_branch_addr').length == 0){
				if ($('#txt_branch_addr_0').length == 0){
					BootstrapDialog.alert("Please Enter Bank Branch Address..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}else if ($('#txt_ifsc_code').length == 0){
				if ($('#txt_ifsc_code_0').length == 0){
					BootstrapDialog.alert("Please Enter Bank IFSC..!!");
					event.preventDefault();
					event.returnValue = false;
				}
			}*/
		});
	});
	
	$('input[type="file"]').change(function(e){
		var fileName = e.target.files[0].name;
		//alert('The file "' + fileName +  '" has been selected.');
	});
	$("body").on("click", "#emp_add", function(event){ 
		var AccHoldName 	= $("#txt_acc_hold_name_0").val();
		var AccHoldNum	 	= $("#txt_acc_hold_num_0").val();
		var BankName   	= $("#txt_bankname_0").val();
		var BranchAddress = $("#txt_branch_addr_0").val();
		var IfscCode  		= $("#txt_ifsc_code_0").val();
		var UploadFile		= $("#txt_file_upload_0").val();
		alert(UploadFile);
		var RowStr = '<tr><td align="center"><input type="text" name="txt_acc_hold_name[]" class="textbox-new" style="width:130px;" value="'+AccHoldName+'"></td><td align="center"><input type="text" name="txt_acc_hold_num[]" class="textbox-new" style="width:100px;" value="'+AccHoldNum+'"></td><td align="center"><input type="text" name="txt_bankname[]" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td align="center"><input type="text" name="txt_branch_addr[]" class="textbox-new" style="width:150px;" value="'+BranchAddress+'"></td><td align="center"><input type="text" name="txt_ifsc_code[]" class="textbox-new" style="width:100px;" value="'+IfscCode+'"></td><td align="center" style="vertical-align:middle;"><input type="file" class="text" name="txt_file_upload[]" id="txt_file_upload" value="'+UploadFile+'"></td><td align="center" style="vertical-align:middle;"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		if(AccHoldName == 0){
			BootstrapDialog.alert("Account Holder Name should not be empty");
			return false;
		}else if(AccHoldNum == 0){
			BootstrapDialog.alert("Account Holder Number should not be empty");
			return false;
		}else if(BankName == 0){
			BootstrapDialog.alert("Bank Name should not be empty");
			return false;
		}else if(BranchAddress == 0){
			BootstrapDialog.alert("Branch Address should not be empty");
			return false;
		}else if(IfscCode == 0){
			BootstrapDialog.alert("IFSC Code should not be empty");
			return false;
		}else{
			$("#table1").append(RowStr);
			$("#txt_acc_hold_name_0").val('');
			$("#txt_acc_hold_num_0").val('');
			$("#txt_bankname_0").val('');
			$("#txt_branch_addr_0").val('');
			$("#txt_ifsc_code_0").val('');
		}
	});
	$("body").on("click", ".delete", function(){
		$(this).closest("tr").remove();
	});
</script>
<style>
table.dataTable > thead > tr > th{
	padding:2px !important;
	font-size:11px !important;
}
</style>
