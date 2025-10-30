<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
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
/*if(isset($_POST['btnSave'])){
	$SaveUnit 		= $GlobUnitId;
	$SaveSheetId 	= $_POST['txt_sheetid'];
	
	$SaveRefNo 		= $_POST['txt_ref_no'];
	$SaveNatClaimId = $_POST['cmb_nature_claim'];
	$SaveNatClaim 	= $_POST['txt_nature_claim'];
	$SaveContId 	= $_POST['cmb_contractor'];
	$SaveContName 	= $_POST['txt_contractor'];
	$SaveVouchDate 	= dt_format($_POST['txt_vouch_dt']);
	$SaveVouchNo 	= $_POST['txt_vouch_no'];
	$SaveVouchAmt 	= $_POST['txt_vouch_amt'];
	$SavePinNo 		= $_POST['txt_pinno'];
	$SaveHoa 		= $_POST['txt_vouch_hoa'];
	$SaveFromDate	= dt_format($_POST['txt_from_date']);
	$SaveToDate		= dt_format($_POST['txt_to_date']);
	
	$SaveBankAccNo 	= $_POST['txt_bank_acc'];
	$SaveBankName 	= $_POST['txt_bank_name'];
	$SaveBankId 	= $_POST['txt_bank_id'];
	$SaveBranch 	= $_POST['txt_branch'];
	$SaveIfscCode 	= $_POST['txt_ifsc'];
	$SaveNetAmt 	= $_POST['txt_net_amt'];
	$ResStr = ""; $QueryArr = array();
	
	
	$VrExist = 0;
	$CheckVrDate = date('Y-m',strtotime($SaveVouchDate));
	$SelectQuery1 = "SELECT vuid FROM voucher_upt WHERE unitid = '$GlobUnitId' AND globid = '$GlobUnitId' AND ((vr_no = '$SaveVouchNo' AND vr_amt = '$SaveVouchAmt' AND DATE_FORMAT(vr_dt,'%Y-%m') = '$CheckVrDate') OR (voucher_for = 'LCESS' AND lcess_fdate = '$SaveFromDate' AND lcess_tdate = '$SaveToDate'))";
	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$VrExist = 1;
		}
	}
	if($VrExist == 0){
		$InsertQuery = "insert into voucher_upt set globid = '$GlobMiscellId', sheetid = '', unitid = '$GlobUnitId', wo = '$SaveRefNo', item_id = '$SaveNatClaimId', 
		item = '$SaveNatClaim', name_contractor = '$SaveContName', contid = '$SaveContId', wo_amt = '$WorkOrderCost', vr_no = '$SaveVouchNo', 
		vr_dt = '$SaveVouchDate', vr_amt = '$SaveVouchAmt', wo_dt = '$WorkOrderDt', o_pin = '$SavePinNo', n_pin = '$SavePinNo', code = '',
		paid_amt = '', hoa = '$SaveHoa', new_hoa = '$SaveHoa', indentor = '', eic = '', grp = '', sec = '', grp_div_sec = '', plant_serv = '', sanct_om_act_sno = '', 
		sanct_om_nwme_sno = '', sanct_act_id = '', createdon = NOW(), staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', entry_flag = 'MAN',
		voucher_for = 'LCESS', lcess_fdate = '$SaveFromDate', lcess_tdate = '$SaveToDate', creator_flag = 'ACC'";
		//echo $InsertQuery;exit;
		$InsertSql 	= mysqli_query($dbConn,$InsertQuery);
		
		if($InsertSql == true){
			$msg = "LCESS Voucher data saved successfully";
			$success = 1;
		}else{
			$msg = "Error : LCESS Voucher data not saved";
			$success = 0;
		}
	}else if($VrExist == 1){
		$msg = "Duplicate Error : Voucher No. ".$SaveVouchNo." for LCESS already created for this period ";
		$success = 0;
	}else{
		$msg = "Error : Invalid data / Invalid attempt";
		$success = 0;
	}
}*/
$MastContArr = array();
$ContSelectSAQuery = "select * from contractor";
$ContSelectSASql = mysqli_query($dbConn,$ContSelectSAQuery);
if($ContSelectSASql == true){
	if(mysqli_num_rows($ContSelectSASql)>0){
		while($ContAList = mysqli_fetch_object($ContSelectSASql)){
			$MastContId   = $ContAList->contid;
			$MastContName = $ContAList->name_contractor;
			$MastContArr[$MastContId] = $MastContName;
		}
	}
}
$WhereClause = ""; $FDate = ""; $TDate = "";
if(isset($_POST['btnView'])){
	$FDate = $_POST['txt_from_date'];
	$TDate = $_POST['txt_to_date'];
	$FromDate = dt_format($_POST['txt_from_date']);
	$ToDate = dt_format($_POST['txt_to_date']);
	$WhereClause = " AND vr_dt >= '$FromDate' AND vr_dt <= '$ToDate'";
}


$RowCnt = 0;
$SelectQueryA = "select * from memo_payment_accounts_edit where vr_no != '' AND vr_dt != '0000-00-00'".$WhereClause;// AND pass_order_dt != '0000-00-00' AND pass_order_dt IS NOT NULL AND pay_order_dt != '0000-00-00' AND pay_order_dt IS NOT NULL";
$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA); //echo $SelectQueryA;exit;
if($SelectSqlA == true){
	if(mysqli_num_rows($SelectSqlA)>0){
		$RowCnt = 1;
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
/*#mySidenav a{
	left:0px !important;
}
#mySidenav a:hover {
  width: 200px !important;
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
								
								<div class="div12">
									<div class="box-container box-container-lg lg-box" align="center">
										<div class="div12">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="left">&nbsp;Voucher Expenditure Statement <span id="CourseChartDuration"></span></div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															
															<div class="div2 pd-lr-1" id="PE1">
																<div class="lboxlabel-sm">From Date</div>
																<div>
																	<input type="text" name="txt_from_date" id="txt_from_date" class="tboxclass tbox-sm datepicker" value="<?php echo $FDate; ?>" required />
																</div>
															</div>
															<div class="div2 pd-lr-1" id="PE2">
																<div class="lboxlabel-sm">To Date</div>
																<div>
																	<input type="text" name="txt_to_date" id="txt_to_date" class="tboxclass tbox-sm datepicker" value="<?php echo $TDate; ?>" required />
																</div>
															</div>
															
															
															
															<div class="div1 pd-lr-1">
																<div class="lboxlabel-sm">&nbsp;</div>
																<div>
																	<input type="submit" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
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
													<div class="card-header inkblue-card" align="left">&nbsp;View - Voucher List</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<table class="table dataTable rtable table2excel" id="StmtTable" border="1" width="100%" align="center">
																				<thead>
																					<tr>
																						<th>SNo</th>
																						<th>Works/ <br/>Misc.</th>
																						<th>CCNO.</th>
																						<th>Work Name / Description</th>
																						<th>Contractor Name</th>
																						<th nowrap="nowrap">Bill Amt.</th>
																						<th nowrap="nowrap">Pay Order / <br/>Bill Dt.</th>
																						<th>HOA - Short Code</th>
																						<th width="70px">Vr. No.</th>
																						<th width="100px">Vr. Date.</th>
																						<th width="120px">Vr./Coding Amt</th>
																						<th nowrap="nowrap">Cheque Amt.</th>
																					</tr>
																				</thead>
																				<tbody>
																			<?php 
																			$Sno = 1;
																			if($RowCnt == 1){ while($ListA = mysqli_fetch_object($SelectSqlA)){ 
																				$CCNo = ''; $WorkName = '';
																				//if($ListA->mop_type == "RAB"){
																				if(($ListA->sheetid != "")&&($ListA->sheetid != "0")){
																					$SelectQueryB 	= "select * from sheet where sheet_id = '$ListA->sheetid'";
																					$SelectSqlB 	= mysqli_query($dbConn,$SelectQueryB);
																					if($SelectSqlB == true){
																						if(mysqli_num_rows($SelectSqlB)>0){
																							$ListB 		= mysqli_fetch_object($SelectSqlB);
																							$WorkName 	= $ListB->work_name;
																							$CCNo 		= $ListB->computer_code_no;
																						}
																					}
																				}
																				if(($ListA->mis_item_id != "")&&($ListA->mis_item_id != "0")){
																					$SelectQueryC 	= "select * from miscell_items where mis_item_id = '$ListA->mis_item_id'";
																					$SelectSqlC 	= mysqli_query($dbConn,$SelectQueryC);
																					if($SelectSqlC == true){
																						if(mysqli_num_rows($SelectSqlC)>0){
																							$ListC = mysqli_fetch_object($SelectSqlC);
																							$WorkName = $ListC->mis_item_desc;
																						}
																					}
																				}
																				$NetAmt = $ListA->abstract_net_amt + $ListA->sec_adv_amt + $ListA->esc_amt + $ListA->pl_mac_adv_amt + $ListA->mob_adv_amt;
																				if($ListA->is_adv_pay == "Y"){
																					$ChequeAmt = $ListA->adv_amt;
																				}else{
																					if(($ListA->net_payable_amt == NULL)||($ListA->net_payable_amt == 0)){
																						$ChequeAmt = $ListA->abstract_net_amt;
																					}else{
																						$ChequeAmt = $ListA->net_payable_amt;
																					}
																				}
																			?>
																					<tr>
																						<td align="center"><?php echo $Sno; ?></td>
																						<td align="center">
																						<?php 
																						if($ListA->mop_type == "MISC"){ 
																							echo "Misc."; 
																						}else if($ListA->mop_type == "RAB"){
																							echo "RAB - ".$ListA->rbn; 
																						}else{
																							echo "";
																						}
																						?>
																						</td>
																						<td align="justify"><?php echo $CCNo; ?></td>
																						<td align="justify"><?php echo $WorkName; if($ListA->is_adv_pay == "Y"){ echo " <span class='efont ptr'>(".$ListA->adv_perc."% Advance)</span>"; } ?></td>
																						<td align="justify"><?php echo $MastContArr[$ListA->contid]; ?></td>
																						<td align="right"><?php echo IndianMoneyFormat($NetAmt); ?></td>
																						<td align="right">
																						<?php 
																						if($ListA->pay_order_dt != '0000-00-00'){ 
																							echo dt_display($ListA->pay_order_dt); 
																						}else if($ListA->bill_dt != '0000-00-00'){ 
																							echo dt_display($ListA->bill_dt); 
																						}
																						?>
																						</td>
																						<td></td>
																						<td><?php echo $ListA->vr_no; ?></td>
																						<td><?php echo dt_display($ListA->vr_dt); ?></td>
																						<td align="right"><?php echo IndianMoneyFormat($ListA->vr_amt); ?></td>
																						<td align="right"><?php echo IndianMoneyFormat($ChequeAmt); ?></td>
																					</tr>
																			<?php $Sno++; } } ?>
																				</tbody>
																			</table>
																			
																			<div class="row clearrow"></div>
																			<!--<div class="row" align="center">
																				<div class="div12 pd-lr-1" align="center">
																					<input type="submit" name="btnSave" id="btnSave" value=" Submit " class="btn btn-info">
																					<input type="reset" name="btnReset" id="btnReset" value=" Reset " class="btn btn-info">
																				</div>
																			</div>-->
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
$("#cmb_nature_claim").chosen();
$(function() {
	var RowIndex = 1;
	var KillEvent = 0;
	/*$("body").on("change",".Vr", function(event){
		var DataId = $(this).attr("data-id");
		var VrNo = $("#txt_vr_no_"+RecAmt).val();
		var VrDt = $("#txt_vr_dt_"+RecAmt).val();
		if((VrNo != '')&&(VrDt != '')){
			$('.Vouch').each(function(){ 
				var VrDtSplit = VrDt.split(",");
				var DateStr = VrDtSplit[1]+"/"+VrDtSplit[2];
			});
		}
	});*/
	
	$("body").on("click","#btnSave", function(event){
		if(KillEvent == 0){
			var Temp1 = 0;  var Temp2 = 0;
			$('.Vouch').each(function(){ 
				var RecAmt 		= $(this).attr("data-id");
				var VrNo 		= $(this).val();
				var VrDt 		= $("#txt_vr_dt_"+RecAmt).val();
				var VrAmt 		= $("#txt_vr_amt_"+RecAmt).val();
				var VrHoaCode 	= $("#cmb_hoa_scode_"+RecAmt).val();
				if((VrNo != '')||(VrDt != '')||(VrAmt != '')||(VrHoaCode != '')){ 
					if(VrNo == ''){
						Temp2++;
					}
					if(VrDt == ''){
						Temp2++;
					}
					if(VrAmt == ''){
						Temp2++;
					}
					if(VrHoaCode == ''){
						Temp2++;
					}
					Temp1++;
				}
			});
			if(Temp1 == 0){
				BootstrapDialog.alert("Please enter atleast one voucher data");
				event.preventDefault();
				event.returnValue = false;
			}else if(Temp2 > 0){
				BootstrapDialog.alert("Voucher No. / Voucher Date / Voucher Amount / Hoa Short Code should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save voucher data ?',
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
	
});
</script>
</body>
</html>

