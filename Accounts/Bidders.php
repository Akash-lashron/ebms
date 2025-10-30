<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Contractor/Bidder Entry';
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
if((isset($_GET['coneditid']))||(isset($_GET['pageid']))){
	$ContID = $_GET['coneditid'];
	if(isset($_GET['pageid'])){
		$PageId = $_GET['pageid'];
  	}
	$SelectContMasterQuery = "SELECT * FROM contractor WHERE contid='$ContID' AND active = 1";
	$SelectContMasterQuerySql = mysqli_query($dbConn,$SelectContMasterQuery);
	$ContList = mysqli_fetch_object($SelectContMasterQuerySql);
	$ContCodeEdit 		= $ContList->cont_code_frfcf;
	$ContTittleEdit 	= $ContList->contractor_title;
	$ContNameEdit 		= $ContList->name_contractor;
	$ContAddrEdit 		= $ContList->addr_contractor;
	$ContStateEdit 		= $ContList->state_contractor;
	$ContPanNoEdit 		= $ContList->pan_no;
	$ContGstNoEdit 		= $ContList->gst_no;
	$ContPanTypeEdit 	= $ContList->pan_type;
	$ContGstTypeEdit  	= $ContList->gst_type;
	$ContIsLdcEdit    	= $ContList->is_ldc_appl;
	$ContLdcCertNoEdit 		= $ContList->ldc_certi_no;
	$ContLdcValidFromEdit	= $ContList->ldc_validty_from;
	$ContLdcMaxAmtEdit 		= $ContList->ldc_max_amt;
	$ContLdcValidToEdit 	= $ContList->ldc_validity;
	$ContLdcRateEdit 		= $ContList->ldc_rate;
	/*  if($PageId == 2){
		$select_Cont_BNK_DET_query = "SELECT * FROM contractor_bank_detail WHERE contid = '$contid' AND bk_dt_conf_status=''";
		$select_Cont_BNK_DET_query_sql = mysqli_query($dbConn,$select_Cont_BNK_DET_query);
	}   */

}
//echo $_POST['btn_save'];exit;
//if(isset($_POST['btn_save']) == 'Submit'){ 
if((isset($_POST['btn_save']))&&($_POST['btn_save'] == 'Submit')){ 
	$SuccInsert  = 0;
	$BankCheckPt = 0;
	$HidContId   	= $_POST['txt_hid_cont_id'];
	$HidContBkDtArr = $_POST['txt_hid_cont_bk_det_id'];
	$ContCodeFrfcf	= $_POST['txt_cont_code_frfcf'];
	$ContTittle		= $_POST['cmb_cont_tittle'];
	$ContName   	= $_POST['txt_cont_name'];
	$ContAddr  		= $_POST['txt_cont_addr'];
	$ContState 		= $_POST['cmb_state'];
	$ContGST 		= $_POST['txt_gst_no'];
	$ContPan			= $_POST['txt_pan_no'];
	$ContPanType	= $_POST['pantype'];
	$ContGSTType	= $_POST['gsttype'];
	/*$ContLDCChk		= $_POST['ldccheck'];
	$ContLdcCerNum	= $_POST['txt_cert_num'];
	$ContLdcCerDt	= $_POST['txt_cert_valid_date'];
	$ContLdcITPerc	= $_POST['txt_itperc'];*/
	//echo $ContLdcCerDt;exit;
	$ContLDCChk		 = $_POST['ldccheck'];
	if($ContLDCChk == 'Y'){
		$ContLdcCerNum	 = $_POST['txt_cert_num'];
		$ContLdcMaxAmt	 = $_POST['txt_cert_max_amt'];
		$ContLdcCerFrDt = dt_format($_POST['txt_cert_valid_from_date']);
		$ContLdcCerToDt = dt_format($_POST['txt_cert_valid_date']);
		$ContLdcITPerc	 = $_POST['txt_itperc'];
	}else{
		$ContLdcCerNum	 = "";
		$ContLdcMaxAmt	 = "";
		$ContLdcCerFrDt = "";
		$ContLdcCerToDt = "";
		$ContLdcITPerc	 = "";
	}
	$ContAccHoldNameStr = $_POST["txt_acc_hold_name"];
	$ContAccHoldNumStr  = $_POST["txt_acc_hold_num"];
	$ContBankNameStr	  = $_POST["txt_bankname"];
	$ContBranchStr		  = $_POST["txt_branch_addr"];
	$ContIfscStr		  = $_POST["txt_ifsc_code"];
	//$ContProofUploadStr = $_FILES['txt_file_upload']['name'];
	if($ContAccHoldNameStr == ""){
		//echo 1;
	}else{
		//echo 2;
	}


	if($ContCodeFrfcf == NULL){
		$msg = "Please Enter Contractor Code..!!";
	}else if($ContTittle == NULL){
		$msg = "Please Enter Contractor Tittle..!!";
	}else if($ContName == NULL){
		$msg = "Please Enter Contractor Name..!!";
	}else if($ContAddr == NULL){
		$msg = "Please Enter Contractor Address..!!";
	}else if($ContState == NULL){
		$msg = "Please Select State..!!";
	}/*else if($ContGST == NULL){
		$msg = "Please Enter Contractor GST Number..!!";
	}else if($ContPan == NULL){
		$msg = "Please Enter Contractor PAN Number..!!";
	}else if($ContPanType == NULL){
		$msg = "Please Select PAN Type..!!";
	}*/else if($ContLDCChk == NULL){
		$msg = "Please LDC Certificate Yes/No..!!";
	}else{
		$BankCheckPt = 1;
		//$SuccInsert = 1;
	}
	if($BankCheckPt == 1){
		/*if($ContAccHoldNameStr == NULL){
			$msg = "Account Holder Name Should not be empty..!!";
		}else */if($ContAccHoldNumStr == NULL){
			$msg = "Account Number Should not be empty..!!";
		}else if($ContBankNameStr == NULL){
			$msg = "Bank Name Should not be empty..!!";
		}else if($ContBranchStr == NULL){
			$msg = "Branch Address Should not be empty..!!";
		}else if($ContIfscStr == NULL){
			$msg = "IFSC Code Should not be empty..!!";
		}else{
			//$BankCheckPt = 1;
			$SuccInsert = 1;
		}
	}
	if($SuccInsert == 1){
		if(($HidContId == null)||($HidContId == "")){
			$ContMasterQuery = "INSERT INTO contractor SET contractor_title = '$ContTittle', cont_code_frfcf = '$ContCodeFrfcf', name_contractor = '$ContName', addr_contractor = '$ContAddr', state_contractor ='$ContState',
			pan_no = '$ContPan', gst_no = '$ContGST', pan_type = '$ContPanType', gst_type = '$ContGSTType', is_ldc_appl = '$ContLDCChk', ldc_certi_no = '$ContLdcCerNum',
			ldc_max_amt = '$ContLdcMaxAmt', ldc_validty_from = '$ContLdcCerFrDt', ldc_validity = '$ContLdcCerToDt', ldc_rate = '$ContLdcITPerc',
			active = 1, createddate = NOW(), userid = '$UserId'";
			$ContMasterQuerySql  = mysqli_query($dbConn,$ContMasterQuery);
			$LastInsertid 		 = mysqli_insert_id($dbConn); 
		}else{
			$ContMasterQuery = "UPDATE contractor SET contractor_title = '$ContTittle', cont_code_frfcf = '$ContCodeFrfcf', name_contractor = '$ContName', addr_contractor = '$ContAddr', state_contractor ='$ContState',
			pan_no = '$ContPan', gst_no = '$ContGST', pan_type = '$ContPanType', gst_type = '$ContGSTType', is_ldc_appl = '$ContLDCChk', ldc_certi_no = '$ContLdcCerNum',
			ldc_max_amt = '$ContLdcMaxAmt', ldc_validty_from = '$ContLdcCerFrDt', ldc_validity = '$ContLdcCerToDt', ldc_rate = '$ContLdcITPerc',
			active = 1, createddate = NOW(), userid = '$UserId' WHERE contid='$HidContId'";
			$ContMasterQuerySql  = mysqli_query($dbConn,$ContMasterQuery);
			$LastInsertid 		 = $HidContId; 
		}
		
		//echo $ContMasterQuery;exit;
		if($_SESSION['levelid'] >= $DecMinHighLevelAppr){ 
			$UserLevel = 'AAO';
		}else{
			$UserLevel = 'DA';
		}
		

		foreach($ContAccHoldNumStr as $Key => $Value){
			$ContBkDtStatus 		= $HidContBkDtStatArr[$Key];
			$ContAccHoldName 		= $ContAccHoldNameStr[$Key];
			$ContAccHoldNum  		= $ContAccHoldNumStr[$Key];
			$ContBankName    		= $ContBankNameStr[$Key];
			$ContBranch      		= $ContBranchStr[$Key];
			$ContIfsc        		= $ContIfscStr[$Key];
			$ContBkDtId        		= $HidContBkDtArr[$Key];

			if(($ContBkDtId != NULL)&&($ContBkDtId != '')&&($ContBkDtId != 0)){
				$insert_query	= "UPDATE contractor_bank_detail SET bank_acc_hold_name='$ContAccHoldName',bank_acc_no='$ContAccHoldNum',
				bank_name='$ContBankName', branch_address='$ContBranch', ifsc_code='$ContIfsc', bk_dt_cr_by='$staffid', bk_dt_status = '$UserLevel',
				active='1' WHERE cbdtid='$ContBkDtId'";
			}else{
				$insert_query	= "INSERT INTO contractor_bank_detail SET contid='$LastInsertid', bank_acc_hold_name='$ContAccHoldName',bank_acc_no='$ContAccHoldNum',
				bank_name='$ContBankName', branch_address='$ContBranch', ifsc_code='$ContIfsc', bk_dt_cr_by='$staffid', bk_dt_status = '$UserLevel', active='1'";
			}
			//echo $insert_query."<br/>";
			$insert_sql = mysqli_query($dbConn,$insert_query);
		}
		if($ContMasterQuerySql == true){
			$msg = "Contractor Details Saved Successfully..!!";
			$success = 1;
		}else{
			$msg = "Error : Contractor Details Not Saved.. Please Try Again.";
			$success = 0;
		}
	}
}
//exit;
//if(isset($_POST["btn_save"]) == "Approve"){ echo "APPROVE";exit;
if((isset($_POST['btn_save']))&&($_POST['btn_save'] == 'Approve')){ 
	$SuccInsert 	 = 0;
	$BankCheckPt 	 = 0;
	$HidContId   	 = $_POST['txt_hid_cont_id'];
	$HidContBkDtArr  = $_POST['txt_hid_cont_bk_det_id'];
	$HidContBkDtStatArr 	= $_POST['txt_hid_cont_bk_det_status'];
	$HidContBkDtConfIdArr   = $_POST['txt_hid_cont_bk_det_confirmed_id'];
	$HidContBkDtConfStatArr = $_POST['txt_hid_cont_bk_det_confirmed_status'];
	$ContCodeFrfcf	 = $_POST['txt_cont_code_frfcf'];
	$ContTittle		 = $_POST['cmb_cont_tittle'];
	$ContName   	 = $_POST['txt_cont_name'];
	$ContAddr  		 = $_POST['txt_cont_addr'];
	$ContState 		 = $_POST['cmb_state'];
	$ContGST 		 = $_POST['txt_gst_no'];
	$ContPan		 = $_POST['txt_pan_no'];
	$ContPanType	 = $_POST['pantype'];
	$ContGSTType	 = $_POST['gsttype'];

	/*$ContLDCChk		= $_POST['ldccheck'];
	$ContLdcCerNum	= $_POST['txt_cert_num'];
	$ContLdcCerDt	= $_POST['txt_cert_valid_date'];
	$ContLdcITPerc	= $_POST['txt_itperc'];*/
	//echo $ContLdcCerDt;exit;
	$ContLDCChk		 = $_POST['ldccheck'];
	$ContLdcCerNum	 = $_POST['txt_cert_num'];
	$ContLdcMaxAmt	 = $_POST['txt_cert_max_amt'];
	$ContLdcCerFrDt  = dt_format($_POST['txt_cert_valid_from_date']);
	$ContLdcCerToDt  = dt_format($_POST['txt_cert_valid_date']);
	$ContLdcITPerc	 = $_POST['txt_itperc'];
	
	$ContAccHoldNameStr = $_POST["txt_acc_hold_name"];
	$ContAccHoldNumStr  = $_POST["txt_acc_hold_num"];
	$ContBankNameStr	  = $_POST["txt_bankname"];
	$ContBranchStr		  = $_POST["txt_branch_addr"];
	$ContIfscStr		  = $_POST["txt_ifsc_code"];
	//$ContProofUploadStr = $_FILES['txt_file_upload']['name'];
	if($ContCodeFrfcf == NULL){
		$msg = "Please Enter Contractor Code..!!";
	}else if($ContTittle == NULL){
		$msg = "Please Enter Contractor Tittle..!!";
	}else if($ContName == NULL){
		$msg = "Please Enter Contractor Name..!!";
	}else if($ContAddr == NULL){
		$msg = "Please Enter Contractor Address..!!";
	}else if($ContState == NULL){
		$msg = "Please Select State..!!";
	}/*else if($ContGST == NULL){
		$msg = "Please Enter Contractor GST Number..!!";
	}else if($ContPan == NULL){
		$msg = "Please Enter Contractor PAN Number..!!";
	}else if($ContPanType == NULL){
		$msg = "Please Select PAN Type..!!";
	}*/else if($ContLDCChk == NULL){
		$msg = "Please LDC Certificate Yes/No..!!";
	}else{
		//$BankCheckPt = 1;
		$BankCheckPt = 1;
	}
	if($BankCheckPt == 1){
		/*if($ContAccHoldNameStr == NULL){
			$msg = "Account Holder Name Should not be empty..!!";
		}else */if($ContAccHoldNumStr == NULL){
			$msg = "Account Number Should not be empty..!!";
		}else if($ContBankNameStr == NULL){
			$msg = "Bank Name Should not be empty..!!";
		}else if($ContBranchStr == NULL){
			$msg = "Branch Address Should not be empty..!!";
		}else if($ContIfscStr == NULL){
			$msg = "IFSC Code Should not be empty..!!";
		}else{
			//$BankCheckPt = 1;
			$SuccInsert = 1;
		}
	}

	if($SuccInsert == 1){
		$InsertQuery = "UPDATE contractor SET  contractor_title = '$ContTittle', cont_code_frfcf = '$ContCodeFrfcf', name_contractor = '$ContName', addr_contractor = '$ContAddr', state_contractor ='$ContState',
		pan_no = '$ContPan', gst_no = '$ContGST', pan_type = '$ContPanType', gst_type = '$ContGSTType', is_ldc_appl = '$ContLDCChk', ldc_certi_no = '$ContLdcCerNum',
		ldc_max_amt = '$ContLdcMaxAmt', ldc_validty_from = '$ContLdcCerFrDt', ldc_validity = '$ContLdcCerToDt', ldc_rate = '$ContLdcITPerc',
		active = 1, createddate = NOW(), userid = '$UserId' WHERE contid = '$HidContId'";
		//echo $InsertQuery;exit;
		$InsertQuerySql = mysqli_query($dbConn,$InsertQuery);
		//$LastInsertid = mysqli_insert_id($dbConn);
		/*	$ImplodeConfBkDtId  = implode(',',$HidContBkDtConfIdArr); */
		//$DeleteBankDetQuery = "DELETE FROM contractor_bank_detail WHERE bk_dt_conf_status != 'AAO' AND contid = '$HidContId'";
		//$DeleteBankDetQuery = "DELETE FROM contractor_bank_detail WHERE contid = '$HidContId'";
		//$DeleteBankDetQuerySql = mysqli_query($dbConn,$DeleteBankDetQuery);	

		foreach($ContAccHoldNumStr as $Key => $Value){
			$ApproveBankDtId 	= $HidContBkDtArr[$Key];
			$ContBkDtConfId		= $HidContBkDtConfIdArr[$Key];
			if($ContBkDtConfId != null){
				$ContBkDtConfIdEcho = $ContBkDtConfId;
			}else{
				$ContBkDtConfIdEcho = 'AAO';
			}
			$ContBkDtStatus		= $HidContBkDtStatArr[$Key];
			if($ContBkDtStatus != null){
				$ContBkDtStatusecho =$ContBkDtStatus;
			}else{
				$ContBkDtStatusecho = 'AAO';
			}
			$ContBkDtConfStatus	= $HidContBkDtConfStatArr[$Key];
			$ContAccHoldName 		= $ContAccHoldNameStr[$Key];
			$ContAccHoldNum  		= $ContAccHoldNumStr[$Key];
			$ContBankName    		= $ContBankNameStr[$Key];
			$ContBranch      		= $ContBranchStr[$Key];
			$ContIfsc        		= $ContIfscStr[$Key];
			//$ContProofUpload 		= $ContProofUploadStr[$Key];
			//if($ContBkDtConfStatus != 'AAO'){
				if(($ContBkDtConfId == '')||($ContBkDtConfId == NULL)){
					$ContBkDtConfId = $staffid;
				}
				if(($ApproveBankDtId != '')&&($ApproveBankDtId != NULL)&&($ApproveBankDtId != 0)){
					$insert_query	= "UPDATE contractor_bank_detail SET contid='$HidContId', bank_acc_hold_name='$ContAccHoldName',bank_acc_no='$ContAccHoldNum', 
					bank_name='$ContBankName', branch_address='$ContBranch', ifsc_code='$ContIfsc', bk_dt_cr_by='$ContBkDtConfId', 
					bk_dt_conf_by='$staffid', bk_dt_status='$ContBkDtStatusecho', bk_dt_conf_status = 'AAO', active='1' WHERE cbdtid = '$ApproveBankDtId'";
				}else{
					$insert_query	= "INSERT INTO contractor_bank_detail SET contid='$HidContId', bank_acc_hold_name='$ContAccHoldName',bank_acc_no='$ContAccHoldNum', 
					bank_name='$ContBankName', branch_address='$ContBranch', ifsc_code='$ContIfsc', bk_dt_cr_by='$ContBkDtConfId', 
					bk_dt_conf_by='$staffid', bk_dt_status='$ContBkDtStatusecho', bk_dt_conf_status = 'AAO', active='1'";
				}
				$insert_sql = mysqli_query($dbConn,$insert_query);
			//}
		}
		if($InsertQuery == true){
			$msg = "Contractor Bank Details Approved Successfully..!!";
			$success = 1;
		}else{
			$msg = "Error : Contractor Bank Details Not Approved.. Please Try Again.";
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
	function goBack(){
	    var pageidval = $("#txt_hid_page_id").val();
	    if(pageidval == 2){
		    url = "BiddersBankWaitfrConfList.php";
	    }else if((pageidval == 1)){
            url = "BiddersList.php";
	    }else{
	        url = "Home.php";
	    }
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
														<div class="row">
															<div class="row clearrow"></div>
															<!--<div class="row">
																<div class="div2 lboxlabel">
																	Contractor Code
																</div>
																<div class="div4">
																	<input type="text" maxlength="15" name='txt_cont_code_frfcf' id='txt_cont_code_frfcf' class='tboxsmclass' value='<?php if(isset($ContCodeEdit)){ echo $ContCodeEdit; } ?>'>
																</div>
															</div>
															<div class="row clearrow"></div>-->							
															<div class="row">
																<input type="hidden" name='txt_hid_cont_id' id='txt_hid_cont_id' class='tboxsmclass' value='<?php if(isset($ContID)){ echo $ContID; } ?>'>
																<div class="div3 lboxlabel">
																	Contractor Title & Name
																</div>
																<div class="div1 pd-lr-1">
																	<select name='cmb_cont_tittle' id='cmb_cont_tittle' class='tboxsmclass' >
																		<option value="">--Sel--</option>
																		<option value="M/S" <?php if(isset($ContTittleEdit)){ if($ContTittleEdit == 'M/S'){ echo "selected='selected'"; } } ?>>M/S </option>
																		<option value="SHRI" <?php if(isset($ContTittleEdit)){ if($ContTittleEdit == 'SHRI'){ echo "selected='selected'"; } } ?>>SHRI </option>
																	</select>
																	<!-- <input type="text" maxlength="100" name='txt_cont_tittle' id='txt_cont_tittle' class='tboxsmclass' value='<?php if(isset($ContNameEdit)){ echo $ContNameEdit; } ?>'> -->
																</div>
																<div class="div6">
																	<input type="text" maxlength="100" name='txt_cont_name' id='txt_cont_name' class='tboxsmclass' value='<?php if(isset($ContNameEdit)){ echo $ContNameEdit; } ?>'>
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
																<div class="div3 lboxlabel">
																	Contractor Address
																</div>
																<div class="div9">
																	<textarea class="tboxsmclass" maxlength="500" name="txt_cont_addr" id="txt_cont_addr" cols="24" rows="2"><?php if(isset($ContAddrEdit)){ echo $ContAddrEdit; } ?></textarea>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	GST No.
																</div>
																<div class="div4" >
																	<input type="text" maxlength="50" name='txt_gst_no' id='txt_gst_no' class="tboxsmclass" value="<?php if(isset($ContGstNoEdit)){ echo $ContGstNoEdit; } ?>">
																</div>
																<div class="div1 lboxlabel">
																	&emsp;PAN No.
																</div>
																<div class="div4">
																	<input type="text" maxlength="50" name='txt_pan_no' id='txt_pan_no' class="tboxsmclass" value="<?php if(isset($ContPanNoEdit)){ echo $ContPanNoEdit; } ?>">
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel" align="left" style="text-align:left;">
																	State
																</div>
																<div class="div4">
																	<select name="cmb_state" id="cmb_state" class="tboxsmclass">
																		<option value=""> ------ Select ------ </option>
																			<?php echo $objBind->BindStates($ContStateEdit); ?>
																	</select>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel" style="text-align:left;">
																	GST Type
																</div>
																<div class="div2" align="left">
																	<div class="inputGroup">
																		<input type="radio" class="cgstcheck readolyclass" name='gsttype' id='gst_type_1' Value="C" <?php if(isset($ContGstTypeEdit)){if($ContGstTypeEdit == 'C'){ echo "checked=checked"; }} ?>>
																		<label for="gst_type_1" style="padding:3px 0px; width:100%; font-size:11px;" class="lboxlabel">&nbsp; CGST</label>
																	</div>
																</div>
																<div class="div2" align="left" style="padding-left:10px;">
																	<div class="inputGroup">
																		<input type="radio" class="igstcheck readolyclass" name='gsttype' id='gst_type_2' Value="I" <?php if(isset($ContGstTypeEdit)){if($ContGstTypeEdit == 'I'){ echo "checked=checked"; }} ?>>
																		<label for="gst_type_2" style="padding:3px 0px; width:100%; font-size:11px;" class="lboxlabel">&nbsp; IGST</label>
																	</div>
																</div>
																<div class="div1 lboxlabel">
																	&emsp;PAN
																</div>
																<div class="div2" align="left">
																	<div class="inputGroup">
																		<input type="radio" name='pantype' id='pan_type_1' Value="I"  <?php if(isset($ContPanTypeEdit)){if($ContPanTypeEdit == 'I'){ echo "checked=checked"; }} ?>>
																		<label for="pan_type_1" style="padding:3px 0px; width:98%; font-size:11px;" class="cboxlabel">&nbsp; Individual</label>
																	</div>
																</div>
																<div class="div2" align="left">
																	<div class="inputGroup">
																		<input type="radio" name='pantype' id='pan_type_2' Value="O"  <?php if(isset($ContPanTypeEdit)){if($ContPanTypeEdit == 'O'){ echo "checked=checked"; }} ?>>
																		<label for="pan_type_2" style="padding:3px 0px; width:98%; font-size:11px;" class="cboxlabel">&nbsp; Others</label>
																	</div>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	Do You have LDC Certificate</br>(for Income Tax Recovery)
																</div>
																<div class="div2" align="left">
																	<div class="inputGroup">
																		<input type="radio" class="isappcheck" name='ldccheck' id='ldc_yes' Value="Y" <?php if(isset($ContIsLdcEdit)){if($ContIsLdcEdit == 'Y'){ echo "checked=checked"; }} ?>>
																		<label for="ldc_yes" style="padding:3px 0px; width:100%; font-size:11px;" class="cboxlabel">&nbsp; YES</label>
																	</div>
																</div>
																<div class="div2" align="left" style="padding-left:10px;">
																	<div class="inputGroup">
																		<input type="radio" class="isappcheck" name='ldccheck' id='ldc_no' Value="N" <?php if(isset($ContIsLdcEdit)){if($ContIsLdcEdit == 'N'){ echo "checked=checked"; }} ?>>
																		<label for="ldc_no" style="padding:3px 0px; width:100%; font-size:11px;" class="cboxlabel">&nbsp; NO</label>
																	</div>
																</div>
															</div>
															<?php $LdcExist = 'N'; if(isset($ContIsLdcEdit)){ if($ContIsLdcEdit == 'Y'){ $LdcExist = 'Y'; } }?>
															<div class="row clearrow LdcData <?php if($LdcExist == "N"){ echo " hide"; } ?>"></div>
															
															<div class="card-header LdcData <?php if($LdcExist == "N"){ echo " hide"; } ?> inkblue-card" align="left">&nbsp;LDC Certificate Details Entry</div>
															<table class="dataTable  LdcData <?php if($LdcExist == "N"){ echo " hide"; } ?>" align="center" width="100%" id="table0">
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
																	<td align="center"><input type="text" class="tboxsmclass" maxlength="200" name="txt_cert_num" id="txt_cert_num" value="<?php if(isset($ContLdcCertNoEdit)){ echo $ContLdcCertNoEdit; } ?>"></td>
																	<td align="center"><input type="text" class="tboxsmclass" maxlength="12" name="txt_cert_max_amt" id="txt_cert_max_amt" onKeyPress="return event.charCode >= 48 && event.charCode <= 57" value="<?php if(isset($ContLdcMaxAmtEdit)){ echo $ContLdcMaxAmtEdit; } ?>"></td>
																	<td align="center"><input type="text" class="tboxsmclass datepicker" name="txt_cert_valid_from_date" id="txt_cert_valid_from_date" readonly="" value="<?php if(isset($ContLdcValidFromEdit)){ echo dt_display($ContLdcValidFromEdit); } ?>"></td>
																	<td align="center"><input type="text" class="tboxsmclass datepicker" name="txt_cert_valid_date" id="txt_cert_valid_date" readonly="" value="<?php if(isset($ContLdcValidToEdit)){ echo dt_display($ContLdcValidToEdit); } ?>"></td>
																	<td align="center"><input type="text" class="tboxsmclass" maxlength="8" name="txt_itperc" id="txt_itperc" value="<?php if(isset($ContLdcRateEdit)){ echo $ContLdcRateEdit; } ?>"></td>
																</tr>
															</table>
															</div>

															<!--<div class="row clearrow isappcheck" style="display-none"></div>
															<div class="row isappcheck">
																<div class="div4 lboxlabel">
																	Certificate No.
																</div>
																<div class="div8">
																	<input type="text" class="tboxsmclass" name='txt_cert_num' id='txt_cert_num'>
																</div>
															</div>
															<div class="row clearrow isappcheck" style="display-none"></div>
															<div class="row isappcheck">
																<div class="div4 lboxlabel">
																	Maximum Amount For Current Financial Year
																</div>
																<div class="div3">
																	<input type="text" class="tboxsmclass" name='txt_cert_num' id='txt_cert_num'>
																</div>
															</div>
															<div class="row clearrow isappcheck" style="display-none"></div>
															<div class="row isappcheck">
																<div class="div4 lboxlabel">
																	Certificate Validity From
																</div>
																<div class="div3">
																	<input type="text" readonly = "" class="tboxsmclass datepicker" name='txt_cert_valid_from_date' id='txt_cert_valid_from_date'>
																</div>
															</div>
															<div class="row clearrow isappcheck" style="display-none"></div>
															<div class="row isappcheck">
																<div class="div4 lboxlabel">
																	Certificate Validity Upto
																</div>
																<div class="div3">
																	<input type="text" readonly = "" class="tboxsmclass datepicker" name='txt_cert_valid_date' id='txt_cert_valid_date'>
																</div>
															</div>
															<div class="row clearrow isappcheck" style="display-none"></div>
															<div class="row isappcheck">
																
																<div class="div4 lboxlabel">
																	IT Percentage
																</div>
																<div class="div3">
																	<input type="text" class="tboxsmclass" name='txt_itperc' id='txt_itperc'>
																</div>
															</div>-->
															<div class="row clearrow LdcData <?php if($LdcExist == "N"){ echo " hide"; } ?>" style="display-none"></div>
															<div class="row clearrow"></div>														
															<!--    2nd Div Starts Here   -->
															<div class="card-header inkblue-card" align="left">&nbsp;Bank Details Entry</div>
															<table class="dataTable etable " align="center" width="100%" id="table1">
																<tr class="label" style="background-color:#FFF">
																	<!--<td align="center">A/c Holder Name </td>-->
																	<td align="center">A/c Number</td>
																	<td align="center">Bank Name</td>
																	<td align="center">Branch Address</td>
																	<td align="center">IFSC Code</td>
																	<!-- <td align="center">Proof</td> -->
																	<td align="center">Action</td>
																</tr>
																<tr>
																	<!--<td align="center"><input type="text" maxlength="50"  class="tboxsmclass"  name="txt_acc_hold_name_0" id="txt_acc_hold_name_0"></td>-->
																	<td align="center"><input type="text" maxlength="20"  class="tboxsmclass"  name="txt_acc_hold_num_0"  id="txt_acc_hold_num_0"></td>
																	<td align="center"><input type="text" maxlength="30"  class="tboxsmclass"  name="txt_bankname_0" 	  id="txt_bankname_0"></td>
																	<td align="center"><input type="text" maxlength="250" class="tboxsmclass"  name="txt_branch_addr_0"   id="txt_branch_addr_0"></td>
																	<td align="center"><input type="text" maxlength="20"  class="tboxsmclass"  name="txt_ifsc_code_0" 	  id="txt_ifsc_code_0"></td>
																	<!--<td align="center" id="uploadviewbtn">
																		<input type="file" class="text" name="txt_file_upload_0" id="txt_file_upload_0">
																		<img id="idimage" name="uploaded_file" src="" width="150px "/>
																	</td>-->
																	<td align="center" style="vertical-align:middle;"><input type="button" name="emp_add" id="emp_add" value="ADD" class="btn btn-info" style="margin-top:0px;"></td>
																	<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																</tr>
																<?php 
																
																if(isset($ContID)){ 
																	if($_SESSION['levelid'] >= $DecMinHighLevelAppr){ 
																		if($PageId == 2){
																			$ContBankSelQuery = "SELECT * FROM contractor_bank_detail WHERE contid = '$ContID' AND bk_dt_status = 'DA' AND (bk_dt_conf_status = '' OR bk_dt_conf_status IS NULL) AND active=1";
																	  	}else{
																		  	$ContBankSelQuery = "SELECT * FROM contractor_bank_detail WHERE contid = '$ContID' AND (bk_dt_status = 'DA' OR bk_dt_conf_status = 'AAO') AND active=1";
																	  	}
																 	}else{
																	  	if($PageId == 2){
																			$ContBankSelQuery = "SELECT * FROM contractor_bank_detail WHERE contid = '$ContID' AND bk_dt_status = 'DA' AND (bk_dt_conf_status = '' OR bk_dt_conf_status IS NULL) AND active=1";
																	  	}else{
																		  	$ContBankSelQuery = "SELECT * FROM contractor_bank_detail WHERE contid = '$ContID' AND (bk_dt_status = '' OR bk_dt_status IS NULL OR bk_dt_conf_status = 'AAO') AND active=1";
																	  	}	
																	}
																	//echo $PageId;
																	//exit;
																	//echo $ContBankSelQuery;exit;
																	$ContBankSelQuerySql = mysqli_query($dbConn,$ContBankSelQuery);
																	if($ContBankSelQuerySql == true){
																		if(mysqli_num_rows($ContBankSelQuerySql)>0){
																			while($ContBankDetList = mysqli_fetch_object($ContBankSelQuerySql)){
																				$ContBankDetId			= $ContBankDetList->cbdtid;
																				$ContBankAccHoldName 	= $ContBankDetList->bank_acc_hold_name;
																				$ContBankAccHoldNo 		= $ContBankDetList->bank_acc_no;
																				$ContBankAccBankName 	= $ContBankDetList->bank_name;
																				$ContBankAccBranchAddr 	= $ContBankDetList->branch_address;
																				$ContBankAccIfscCode 	= $ContBankDetList->ifsc_code;
																				$ContBankAccCrBy		= $ContBankDetList->bk_dt_cr_by;
																				$ContBankAccCrByStatus 	= $ContBankDetList->bk_dt_status;
																				$ContBankAccConfByStatus = $ContBankDetList->bk_dt_conf_status;
																?>
																					<input type="hidden" name='txt_hid_cont_bk_det_id[]' id='txt_hid_cont_bk_det_id' class='tboxsmclass' value='<?php if(isset($ContBankDetId)){ echo $ContBankDetId; } ?>'>
																					<input type="hidden" name='txt_hid_cont_bk_det_status[]' id='txt_hid_cont_bk_det_status' class='tboxsmclass' value='<?php if(isset($ContBankAccCrByStatus)){ echo $ContBankAccCrByStatus; } ?>'>
																					<input type="hidden" name='txt_hid_cont_bk_det_confirmed_id[]' id='txt_hid_cont_bk_det_confirmed_id' class='tboxsmclass' value='<?php if(isset($ContBankAccCrBy)){ echo $ContBankAccCrBy; } ?>'>
																					<input type="hidden" name='txt_hid_cont_bk_det_confirmed_status[]' id='txt_hid_cont_bk_det_confirmed_status' class='tboxsmclass' value='<?php if(isset($ContBankAccConfBy)){ echo $ContBankAccConfBy; } ?>'>
																					<tr>
																						<!--<td align="center"><input type="text" maxlength="50"  class="tboxsmclass" value="<?php //if(isset($ContBankAccHoldName)){ echo $ContBankAccHoldName; } ?>" name="txt_acc_hold_name[]" id="txt_acc_hold_name"></td>-->
																						<td align="center"><input type="text" maxlength="20"  class="tboxsmclass" value="<?php if(isset($ContBankAccHoldNo)){ echo $ContBankAccHoldNo; } ?>" name="txt_acc_hold_num[]"  id="txt_acc_hold_num"></td>
																						<td align="center"><input type="text" maxlength="30"  class="tboxsmclass" value="<?php if(isset($ContBankAccBankName)){ echo $ContBankAccBankName; } ?>" name="txt_bankname[]" 	  id="txt_bankname"></td>
																						<td align="center"><input type="text" maxlength="250" class="tboxsmclass" value="<?php if(isset($ContBankAccBranchAddr)){ echo $ContBankAccBranchAddr; } ?>" name="txt_branch_addr[]"   id="txt_branch_addr"></td>
																						<td align="center"><input type="text" maxlength="20"  class="tboxsmclass" value="<?php if(isset($ContBankAccIfscCode)){ echo $ContBankAccIfscCode; } ?>" name="txt_ifsc_code[]" 	  id="txt_ifsc_code"></td>
																						<td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px"></td>
																						
																						<!--<td align="center" id="uploadviewbtn">
																							<input type="file" class="text" name="txt_file_upload_0" id="txt_file_upload_0">
																							<img id="idimage" name="uploaded_file" src="" width="150px "/>
																						</td>
																						<td align="center" style="vertical-align:middle;"><input type="button" name="emp_add" id="emp_add" value="ADD" class="fa btn btn-info"></td>-->
																					</tr>
																<?php
																				}
																			}
																		}
																	}
																?>
																<input type="hidden" name='txt_hid_page_id' id='txt_hid_page_id' class='tboxsmclass' value='<?php if(isset($PageId)){ echo $PageId; } ?>'>
															</table>
															<div class="row smclearrow"></div>												
															<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
													    	    <div class="row">
    																<input type="button" class="btn btn-info" name="back" value="Back" onclick=goBack() >
																	<?php if($_SESSION['levelid'] >= $DecMinHighLevelAppr){ ?>
																		<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="A" value="Approve"/>
																	<?php }else{ ?>
																		<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="S" value="Submit"/>
																	<?php } ?>
																<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewBidder();"/> -->
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
			<!--==============================footer=================================-->
			<?php   include "footer/footer.html"; ?>
			<script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$('#cmb_state').chosen();
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.show({
			message: msg,
			buttons: [{
				label: ' OK ',
				action: function(dialog) {
					dialog.close();
					window.location.replace('Bidders.php');
				}
			}]
		});
	}
};

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
		$("body").on("change","#cmb_state", function(event){
			var stateval = $(this).val(); //alert(stateval);
			if(stateval == 'TN'){
				$('.igstcheck').prop('checked', false);
				$('.cgstcheck').prop('checked', true);
			}else{
				$('.igstcheck').prop('checked', true);
				$('.cgstcheck').prop('checked', false);
			}
			/*
			$.ajax({
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
			});
			*/
		});
		$('.readolyclass').click(function(){
			return false;
		});
		$("body").on("change","#txt_pan_no", function(event){
			//BootstrapDialog.alert(1);
			//var ContLDCCertCkaaa = $(".isappcheck:checked").val(); alert(ContLDCCertCkaaa);
			var contpanval = $("#txt_pan_no").val();
			var ContId = $("#txt_hid_cont_id").val();
			PanType = "O";
			if(contpanval != ''){
				var PanTypeChar = contpanval.substring(3,4);
				if(PanTypeChar == "P"){
					PanType = "I";
				}
			}
			if(PanType == "I"){
				$('#pan_type_1').prop('checked', true);
				$('#pan_type_2').prop('checked', false);
			}else{
				$('#pan_type_1').prop('checked', false);
				$('#pan_type_2').prop('checked', true);
			}
			$.ajax({ 
				type: 'POST', 
				dataType:'json',
				url: 'ajax/GetPanVerification.php', 
				data: ({ contpanval: contpanval, ContId:ContId }), 
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
		$("body").on("change","#txt_cont_name", function(event){
			var ContName = $(this).val(); 
			var ContId = $("#txt_hid_cont_id").val(); 
			//if(ContId == ''){ 
			$("#txt_cont_code_frfcf").val('');
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/GenerateContCode.php', 
				data: ({ ContName: ContName, ContId: ContId }), 
				success: function (data) {
					//alert(data);			
					if(data != null){
						$("#txt_cont_code_frfcf").val(data);
					}		
				}
			});
			//}
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
			var GstType = "O";
			var contgstval = $("#txt_gst_no").val();
			var ContId = $("#txt_hid_cont_id").val();
			if(contgstval != ''){
				var GstTypeChar = contgstval.substring(0,2); 
				if(GstTypeChar == "33"){
					GstType = "TN"; 
					$("#cmb_state").chosen("destroy");
					$("#cmb_state").val(GstType);
					$("#cmb_state").chosen();
					$('.igstcheck').prop('checked', false);
					$('.cgstcheck').prop('checked', true);
				}else{
					$("#cmb_state").chosen("destroy");
					$("#cmb_state").val('');
					$("#cmb_state").chosen();
					$('.igstcheck').prop('checked', true);
					$('.cgstcheck').prop('checked', false);
				}
			}

			$.ajax({ 
				type: 'POST', 
				dataType:'json',
				url: 'ajax/GetGstVerification.php', 
				data: ({ contgstval: contgstval, ContId:ContId }), 
				success: function (data) {
					//alert(data);				
					if(data != null){
						BootstrapDialog.alert("Sorry..GST Number Already Entered for "+data+"..!!");
						$("#txt_gst_no").val('');
					}else{
						return false;
					}
				}
			});
		});
		//$(".isappcheck").hide();
		$("body").on("click",".isappcheck", function(event){
			var radval = $(this).val(); //alert(radval);
			if(radval == 'Y'){
				$(".LdcData").removeClass('hide');
			}else{
				$(".LdcData").addClass('hide');
			}
		});
		
		var KillEvent = 0;
		
		
		$("body").on("click","#btn_save", function(event){
			if(KillEvent == 0){
				var BankCheckPtVar = 0;
				var ErrCount = 0;
				var ErrMsg = "";
				var ContNameVal  	= $("#txt_cont_name").val();
				var ContAddrVal  	= $("#txt_cont_addr").val();
				var ContStateVal    = $("#cmb_state").val();
				//var ContGSTVal   	= $("#txt_gst_no").val();
				//var ContPanVal   	= $("#txt_pan_no").val();
				var ContLDCCertCk = $(".isappcheck:checked").val(); //alert(ContLDCCertCk);
				//var ContLDCCertCk	= $("#ldccheck").val();
				var rowCount		= $('#table1 tr').length;  	//alert(rowCount);
				//var ContLDCNum  	= $("#txt_cert_num").val();
				//var ContLDCMaxAmt = $("#txt_cert_max_amt").val();
				//var ContLDCValidFrDt = $("#txt_cert_valid_from_date").val();
				//var ContLDCValidToDt = $("#txt_cert_valid_date").val();
				//var ContLDCIT  	= $("#txt_itperc").val();
				var BtnType = $(this).attr("data-type");
				
				if(ContNameVal == ""){
					ErrCount++;
					ErrMsg = "Contractor name should not be empty..!!";
				}else if(ContAddrVal == ""){
					ErrCount++;
					ErrMsg = "Contractor address should not be empty..!!";
				}else if(ContStateVal == ""){
					ErrCount++;
					ErrMsg = "Please Select State Type..!!";
				}/*else if(ContGSTVal == ""){
					ErrCount++;
					ErrMsg = "GST Number should not be empty..!!";
				}else if(ContPanVal == ""){
					ErrCount++;
					ErrMsg = "PAN Number should not be empty..!!";
				}else if ($('input[name="pantype"]:checked').length == 0){
					ErrCount++;
					ErrMsg = "Please Select PAN Type..!!";
				}*/else if ($('input[name="ldccheck"]:checked').length == 0){
					ErrCount++;
					ErrMsg = "Please Select LDC Certificate Yes/No..!!";
				}else if(rowCount <= 2){
					ErrCount++;
					ErrMsg = "Please add atleast one bank detail!!";
				}
				
				if(ErrCount > 0){
					BootstrapDialog.alert(ErrMsg);
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to Add this Contractor Detail ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							if(result){
								if(BtnType == "S"){
									KillEvent = 1;
									$("#btn_save").trigger( "click" );
								}
								if(BtnType == "A"){
									//KillEvent = 1;
									//$("#btn_save").trigger( "click" );
									BootstrapDialog.show({
										title: 'Password Authentication',
										message: $('<span>Enter Your Password for Bank Details Confirmation</span><input type="password" class="tboxclass" name="txt_appr_pwd" id="txt_appr_pwd">'),
										buttons: [{
											label: 'OK',
											cssClass: 'btn-primary',
											action: function() {
												var ApprPwd = $("#txt_appr_pwd").val();
												$.ajax({ 
													type: 'POST', 
													dataType: 'json',
													url: 'ajax/ApprovePwdVerification.php', 
													data: ({ ApprPwd: ApprPwd, Page: 'BANK' }), 
													success: function (data) {
														if(data == 1){
															KillEvent = 1;
															$("#btn_save").trigger( "click" );
														}else{
															BootstrapDialog.alert("Sorry..Invalid password. Please try again.");
														}		
														/*if(data != null){
															BootstrapDialog.alert("Sorry..GST Number Already Entered for "+data+"..!!");
															$("#txt_gst_no").val('');
														}else{
															return false;
														}*/
													}
												});
											}
										}]
									});
								}
							}else {
								KillEvent = 0;
							}
						}
					});
				}
			}
		});
		
	});
	
	$('input[type="file"]').change(function(e){
		var fileName = e.target.files[0].name;
		//alert('The file "' + fileName +  '" has been selected.');
	});
	$("body").on("click", "#emp_add", function(event){ 
		//var AccHoldName 	= $("#txt_acc_hold_name_0").val();
		var AccHoldNum	 	= $("#txt_acc_hold_num_0").val();
		var BankName   	= $("#txt_bankname_0").val();
		var BranchAddress = $("#txt_branch_addr_0").val();
		var IfscCode  		= $("#txt_ifsc_code_0").val();
		var UploadFile		= $("#txt_file_upload_0").val();
		//alert(UploadFile);
		//Comment File Upload//var RowStr = '<tr><td align="center"><input type="text" name="txt_acc_hold_name[]" class="textbox-new" style="width:130px;" value="'+AccHoldName+'"></td><td align="center"><input type="text" name="txt_acc_hold_num[]" class="textbox-new" style="width:100px;" value="'+AccHoldNum+'"></td><td align="center"><input type="text" name="txt_bankname[]" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td align="center"><input type="text" name="txt_branch_addr[]" class="textbox-new" style="width:150px;" value="'+BranchAddress+'"></td><td align="center"><input type="text" name="txt_ifsc_code[]" class="textbox-new" style="width:100px;" value="'+IfscCode+'"></td><td align="center" style="vertical-align:middle;"><input type="file" class="text" name="txt_file_upload[]" id="txt_file_upload" value="'+UploadFile+'"></td><td align="center" style="vertical-align:middle;"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		//var RowStr = '<tr><td align="center"><input type="text" maxlength="50" name="txt_acc_hold_name[]" class="tboxsmclass" value="'+AccHoldName+'"></td><td align="center"><input type="text" maxlength="20" name="txt_acc_hold_num[]" class="tboxsmclass" value="'+AccHoldNum+'"></td><td align="center"><input type="text" maxlength="30" name="txt_bankname[]" class="tboxsmclass" value="'+BankName+'"></td><td align="center"><input type="text" maxlength="250" name="txt_branch_addr[]" class="tboxsmclass" value="'+BranchAddress+'"></td><td align="center"><input type="text" maxlength="20" name="txt_ifsc_code[]" class="tboxsmclass" value="'+IfscCode+'"></td><td align="center" style="vertical-align:middle;"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		var RowStr = '<tr><td align="center"><input type="text" maxlength="20" name="txt_acc_hold_num[]" class="tboxsmclass" value="'+AccHoldNum+'"></td><td align="center"><input type="text" maxlength="30" name="txt_bankname[]" class="tboxsmclass" value="'+BankName+'"></td><td align="center"><input type="text" maxlength="250" name="txt_branch_addr[]" class="tboxsmclass" value="'+BranchAddress+'"></td><td align="center"><input type="text" maxlength="20" name="txt_ifsc_code[]" class="tboxsmclass" value="'+IfscCode+'"></td><td align="center" style="vertical-align:middle;"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
		/*if(AccHoldName == 0){
			BootstrapDialog.alert("Account Holder Name should not be empty");
			return false;
		}else*/ if(AccHoldNum == 0){
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
			//$("#txt_acc_hold_name_0").val('');
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
