<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'Vouchers List';
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
$msg = ""; $RowCount = 0;
if(isset($_POST['btnDelete']) == " Delete "){
	$DelExe = 0;
	$DeleteList = $_POST['ch_vouch'];
	if(count($DeleteList) > 0){
		foreach($DeleteList as $Key => $Value){
			$DeleteQuery = "DELETE FROM voucher_upt WHERE vuid = '$Value'";
			$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
			if($DeleteSql == true){
				$DelExe++;
			}
		}
	}
	if($DelExe > 0){
		$msg = "Voucher data successfully deleted";
	}else{
		$msg = "Sorry unable to delete. Please try again later ";
	}
}
$SelectHoaQuery1 = "(SELECT old_hoa_no, new_hoa_no FROM hoa_master WHERE active = 1) UNION (SELECT old_hoa_no, new_hoa_no FROM hoa_detail WHERE active = 1)";
$SelectHoaSql1 	 = mysqli_query($dbConn,$SelectHoaQuery1);
if($SelectHoaSql1 == true){
	if(mysqli_num_rows($SelectHoaSql1)>0){
		while($HoaList = mysqli_fetch_object($SelectHoaSql1)){
			$OldHoaNo = $HoaList->old_hoa_no;
			$NewHoaNo = $HoaList->new_hoa_no;
			//$HoaId = $HoaList->hoamast_id;
			$OldHoaNo = str_replace(' ','',$OldHoaNo);
			$NewHoaNo = str_replace(' ','',$NewHoaNo);
			if($OldHoaNo != ''){
				$HoaArr[$OldHoaNo] = $OldHoaNo;
			}
			if($NewHoaNo != ''){
				$HoaArr[$NewHoaNo] = $NewHoaNo;
			}
		}
	}
}
$UnitArr = array();
$SelectUnitQuery = "SELECT * FROM dae_units WHERE active = 1";
$SelectUnitSql 	 = mysqli_query($dbConn,$SelectUnitQuery);
if($SelectUnitSql == true){
	if(mysqli_num_rows($SelectUnitSql)>0){
		while($UnitList = mysqli_fetch_object($SelectUnitSql)){
			$UnitArr[$UnitList->unitid] = $UnitList->unit_name;
		}
	}
}
$DiscArr = array();
$SelectDiscipQuery1  = "select * from discipline where active = 1";
$SelectDiscipSql1 	 = mysqli_query($dbConn,$SelectDiscipQuery1);
if($SelectDiscipSql1 == true){
	if(mysqli_num_rows($SelectDiscipSql1)>0){
		while($DiscList = mysqli_fetch_object($SelectDiscipSql1)){
			$DiscipName = $DiscList->discipline_name;
			$DiscipId   = $DiscList->disciplineid;
			$DiscipCode = $DiscList->discipline_code;
			$DiscArr[$DiscipId] = $DiscipCode;
		}
	}
}
//print_r($UnitArr);exit;
if(isset($_POST['btnUpdate']) == " Update "){
	$EditVuidList 		= $_POST['txt_vuid'];
	$EditUnitIdList 	= $_POST['txt_unitid'];
	$EditDiscIdList 	= $_POST['txt_disciplineid'];
	$EditWoList 		= $_POST['txt_wo'];
	$EditItemList 		= $_POST['txt_item'];
	$EditContList 		= $_POST['txt_name_contractor'];
	$EditWoAmtList 		= $_POST['txt_wo_amt'];
	$EditVrNoList 		= $_POST['txt_vr_no'];
	$EditVrDtList 		= $_POST['txt_vr_dt'];
	$EditVrAmtList 		= $_POST['txt_vr_amt'];
	$EditWoDtList 		= $_POST['txt_wo_dt'];
	$EditWoCompDtList 	= $_POST['txt_wo_comp_dt'];
	$EditFPayDtList 	= $_POST['txt_final_pay_dt'];
	$EditWoStatusList 	= $_POST['txt_work_status'];
	$EditOPinList 		= $_POST['txt_o_pin'];
	$EditNPinList 		= $_POST['txt_n_pin'];
	$EditCcnoList 		= $_POST['txt_ccno'];
	$EditPaidAmtList 	= $_POST['txt_paid_amt'];
	$EditHosList 		= $_POST['txt_hoa'];
	$EditNewHoaList 	= $_POST['txt_new_hoa'];
	$EditIndentList 	= $_POST['txt_indentor'];
	$EditGrpDivSecList 	= $_POST['txt_grp_div_sec'];
	$EditPlantServList 	= $_POST['txt_plant_serv'];
	$EditSanctOList 	= $_POST['txt_sanct_om_act_sno'];
	$EditSanctNwmeList 	= $_POST['txt_sanct_om_nwme_sno'];
	if(count($EditVuidList)>0){
		foreach($EditVuidList as $Key => $Value){
			$SaveEditUnitId 	= $EditUnitIdList[$Key];
			$SaveEditDiscId 	= $EditDiscIdList[$Key];
			$SaveEditWo 		= $EditWoList[$Key];
			$SaveEditItem 		= $EditItemList[$Key];
			$SaveEditCont 		= $EditContList[$Key];
			$SaveEditWoAmt 		= $EditWoAmtList[$Key];
			$SaveEditVrNo 		= $EditVrNoList[$Key];
			$SaveEditVrDt 		= dt_format($EditVrDtList[$Key]);
			$SaveEditVrAmt 		= $EditVrAmtList[$Key];
			$SaveEditWoDt 		= dt_format($EditWoDtList[$Key]);
			$SaveEditWoCompDt 	= dt_format($EditWoCompDtList[$Key]);
			$SaveEditFPayDt 	= dt_format($EditFPayDtList[$Key]);
			$SaveEditWoStatus 	= $EditWoStatusList[$Key];
			$SaveEditOPin 		= $EditOPinList[$Key];
			$SaveEditNPin 		= $EditNPinList[$Key];
			$SaveEditCcno 		= $EditCcnoList[$Key];
			$SaveEditPaidAmt 	= $EditPaidAmtList[$Key];
			$SaveEditHos 		= $EditHosList[$Key];
			$SaveEditHoaId 		= $HoaArr[$SaveEditHos];
			$SaveEditNewHoa 	= $EditNewHoaList[$Key];
			$SaveEditNewHoaId	= $HoaArr[$SaveEditNewHoa];
			$SaveEditIndent 	= $EditIndentList[$Key];
			$SaveEditGrpDivSec 	= $EditGrpDivSecList[$Key];
			$SaveEditPlantServ 	= $EditPlantServList[$Key];
			$SaveEditSanctO 	= $EditSanctOList[$Key];
			$SaveEditSanctNwme 	= $EditSanctNwmeList[$Key];
			$InsertQuery = "update voucher_upt set unitid = '$SaveEditUnitId', disciplineid = '$SaveEditDiscId', wo = '$SaveEditWo', item = '$SaveEditItem', name_contractor = '$SaveEditCont', contid = '', 
							wo_amt = '$SaveEditWoAmt', vr_no = '$SaveEditVrNo', vr_dt = '$SaveEditVrDt', vr_amt = '$SaveEditVrAmt', wo_dt = '$SaveEditWoDt', wo_comp_dt = '$SaveEditWoCompDt', 
							final_pay_dt = '$SaveEditFPayDt', work_status = '$SaveEditWoStatus', o_pin = '$SaveEditOPin', n_pin = '$SaveEditNPin', ccno = '$SaveEditCcno', code= '$SaveEditCcno', 
							paid_amt = '$SaveEditPaidAmt', hoa = '$SaveEditHos', hoa_id = '$SaveEditHoaId', new_hoa = '$SaveEditNewHoa', new_hoa_id = '$SaveEditNewHoaId', 
							indentor = '$SaveEditIndent', grp_div_sec = '$SaveEditGrpDivSec', plant_serv = '$SaveEditPlantServ', sanct_om_act_sno = '$SaveEditSanctO', 
							sanct_om_nwme_sno = '$SaveEditSanctNwme', createdon = NOW(), staffid = '$staffid', userid = '$userid', entry_flag = 'XL' where vuid = '$Value'";
			$InsertSql 	 = 	mysqli_query($dbConn,$InsertQuery);
		}
	}
}
$IsEdit = 0;
if(isset($_POST['btnEdit']) == " Edit "){
	$IsEdit = 1;
	$EditList = $_POST['ch_vouch'];
	$EditRows = "";
	if(count($EditList) > 0){
		$EditRows = implode(",",$EditList);
	}
	$SelectVouchQuery1  = "select * from voucher_upt where vuid IN($EditRows) order by vr_dt asc";
	$SelectVouchSql1 	 = mysqli_query($dbConn,$SelectVouchQuery1);
	if($SelectVouchSql1 == true){
		if(mysqli_num_rows($SelectVouchSql1)>0){
			$RowCount = 1;
		}
	}
}
else{
	if(isset($_POST['btnView']) == " VIEW "){
		$VouchUnit 	= $_POST['cmb_unit'];
		$VouchHoa 	= $_POST['cmb_hoa'];
		$VouchFDate = dt_format($_POST['txt_from_date']);
		$VouchTDate = dt_format($_POST['txt_to_date']);
		
		$VoucchHoaArr = array();
		$SelectVouchQuery2  = "select hoa, new_hoa from voucher_upt where vr_dt >= '$VouchFDate' and vr_dt <= '$VouchTDate' AND (hoa = '$VouchHoa' OR new_hoa = '$VouchHoa')";
		$SelectVouchSql2 	 = mysqli_query($dbConn,$SelectVouchQuery2); //echo $SelectVouchQuery2;exit;
		if($SelectVouchSql2 == true){
			if(mysqli_num_rows($SelectVouchSql2)>0){
				while($List1 = mysqli_fetch_object($SelectVouchSql2)){
					$Ohoa = "'".$List1->hoa."'";
					$Nhoa = "'".$List1->new_hoa."'";
					if(in_array($Ohoa, $VoucchHoaArr)){
						
					}else{
						array_push($VoucchHoaArr,$Ohoa);
					}
					if(in_array($Nhoa, $VoucchHoaArr)){
						
					}else{
						array_push($VoucchHoaArr,$Nhoa);
					}
				}
			}
		}
		if(count($VoucchHoaArr)>0){
			$HoaStr = implode(",",$VoucchHoaArr);
		}else{
			$HoaStr = '';
		}
		
		$WhereClause = "";
		if($VouchUnit != 'ALL'){
			$WhereClause .= " and unitid = '$VouchUnit'";	
		}
		if($VouchHoa != 'ALL'){
			//$WhereClause .= " and (hoa = '$VouchHoa' OR new_hoa = '$VouchHoa')";	
			$WhereClause .= " and (hoa IN ($HoaStr) OR new_hoa IN ($HoaStr))";	
		}
		$SelectVouchQuery1  = "select * from voucher_upt where vr_dt >= '$VouchFDate' and vr_dt <= '$VouchTDate' ".$WhereClause." order by vr_dt asc";
		$SelectVouchSql1 	 = mysqli_query($dbConn,$SelectVouchQuery1);
		//echo $SelectVouchQuery1;exit;
		if($SelectVouchSql1 == true){
			if(mysqli_num_rows($SelectVouchSql1)>0){
				$RowCount = 1;
			}
		}
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
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
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<input type="hidden" name="max_group" id="max_group" value="1" />
								
								<!--<div class="box-container box-container-lg">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Financial and Physical Progress <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Unit</div>
															<div>
																<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" >
																	<option value="FRFCF">FRFCF</option>
																	<?php ///echo $objBind->BindAllDaeUnits(0); ?>
																</select>
															</div>
														</div>
														<div class="div3 pd-lr-1">
															<div class="lboxlabel-sm">Financial Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																	<option value="2021-2022">2021-2022</option>
	
																</select>
															</div>
														</div>
														
														<div class="div3 pd-lr-1" id="MO">
															<div class="lboxlabel-sm">Upto Month</div>
															<div>
																<select class="group selectlgbox" name="cmb_month" id="cmb_month">
																	<option value="1" selected="selected">January</option>
																	<option value="2">February</option>
																	<option value="3">March</option>
																	<option value="4">April</option>
																	<option value="5">May</option>
																	<option value="6">June</option>
																	<option value="7">July</option>
																	<option value="8">August</option>
																	<option value="9">September</option>
																	<option value="10">October</option>
																	<option value="11">November</option>
																	<option value="12">December</option>
																</select>
															</div>
														</div>
														
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Rupees &#x20b9; In</div>
															<div>
																<select class="group selectlgbox" name="cmb_rupees" id="cmb_rupees">
																	<option value="L" selected="selected">Lakhs</option>
																	<option value="C">Crores</option>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="button" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>-->
								<!--<div class="row clearrow"></div>-->
								
								<div class="box-container box-container-lg" align="center">
									<!--<div class="div1">&nbsp;</div>-->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Vouchers List</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
															<?php if($IsEdit == 1){ ?>
																<table id="example" class="display rtable mgtb-8">
																	<thead>
																		<tr>
																			<th>SNo.</th>
																			<th>Unit</th>
																			<th>Discipline</th>
																			<th>File/PO</th>
																			<th style="width:150px;">Item</th>
																			<th>FIRM Name</th>
																			<th>PO Value</th>
																			<th>Vr. no</th>
																			<th>Vr. Date</th>
																			<th>VrAmt</th>
																			<th>PO/Rel Dt</th>
																			<th>PO/WO Completion Date</th>
																			<th>Final Payment completed Date</th>
																			<th>Status</th>
																			<th>O PIN</th>
																			<th>N PIN</th>
																			<th>CC NO</th>
																			<th>Paid[L]</th>
																			<th>HOA</th>
																			<th>New HOA</th>
																			<th>Indentor</th>
																			<th>GrpDivSec</th>
																			<th>Plant/Service</th>
																			<th>Sanction OM activity Sl.No</th>
																			<th>Sanction OM MW/ME Sl.No</th>
																			<th>Uploaded On</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php $Sno = 1; if($RowCount == 1){ while($VouchList = mysqli_fetch_object($SelectVouchSql1)){ ?>
																		<tr>
																			<td align="center">
																			<?php echo $Sno; ?>
																			<input type="hidden" name="txt_vuid[]" id="txt_vuid" value="<?php echo $VouchList->vuid; ?>" >
																			<input type="hidden" name="txt_unitid[]" id="txt_unitid" value="<?php echo $VouchList->unitid; ?>" >
																			<input type="hidden" name="txt_disciplineid[]" id="txt_disciplineid" value="<?php echo $VouchList->disciplineid; ?>" >
																			</td>
																			<td align="center"><?php echo $UnitArr[$VouchList->unitid]; ?></td>
																			<td align="center"><?php echo $DiscArr[$VouchList->disciplineid]; ?></td>
																			<td align="center"><input type="text" name="txt_wo[]" id="txt_wo" value="<?php echo $VouchList->wo; ?>" ></td>
																			<td align="justify" style="width:150px;"><input type="text" name="txt_item[]" id="txt_item" value="<?php echo $VouchList->item; ?>" ></td>
																			<td><input type="text" name="txt_name_contractor[]" id="txt_name_contractor" value="<?php echo $VouchList->name_contractor; ?>" ></td>
																			<td align="right"><input type="text" name="txt_wo_amt[]" id="txt_wo_amt" value="<?php echo $VouchList->wo_amt; ?>" ></td>
																			<td align="center"><input type="text" name="txt_vr_no[]" id="txt_vr_no" value="<?php echo $VouchList->vr_no; ?>" ></td>
																			<td align="center"><input type="text" class="datepicker" name="txt_vr_dt[]" id="txt_vr_dt" value="<?php if(($VouchList->vr_dt != NULL)&&($VouchList->vr_dt != "0000-00-00")){ echo dt_display($VouchList->vr_dt); } ?>" ></td>
																			<td align="right"><input type="text" name="txt_vr_amt[]" id="txt_vr_amt" value="<?php echo $VouchList->vr_amt; ?>" ></td>
																			<td align="center"><input type="text" class="datepicker" name="txt_wo_dt[]" id="txt_wo_dt" value="<?php if(($VouchList->wo_dt != NULL)&&($VouchList->wo_dt != "0000-00-00")){ echo dt_display($VouchList->wo_dt); } ?>" ></td>
																			<td align="center"><input type="text" class="datepicker" name="txt_wo_comp_dt[]" id="txt_wo_comp_dt" value="<?php if(($VouchList->wo_comp_dt != NULL)&&($VouchList->wo_comp_dt != "0000-00-00")){ echo dt_display($VouchList->wo_comp_dt); } ?>" ></td>
																			<td align="center"><input type="text" class="datepicker" name="txt_final_pay_dt[]" id="txt_final_pay_dt" value="<?php if(($VouchList->final_pay_dt != NULL)&&($VouchList->final_pay_dt != "0000-00-00")){ echo dt_display($VouchList->final_pay_dt); } ?>" ></td>
																			<td align="center"><input type="text" name="txt_work_status[]" id="txt_work_status" value="<?php echo $VouchList->work_status; ?>" ></td>
																			<td align="center"><input type="text" name="txt_o_pin[]" id="txt_o_pin" value="<?php echo $VouchList->o_pin; ?>" ></td>
																			<td align="center"><input type="text" name="txt_n_pin[]" id="txt_n_pin" value="<?php echo $VouchList->n_pin; ?>" ></td>
																			<td align="center"><input type="text" name="txt_ccno[]" id="txt_ccno" value="<?php echo $VouchList->ccno; ?>" ></td>
																			<td align="right"><input type="text" name="txt_paid_amt[]" id="txt_paid_amt" value="<?php echo $VouchList->paid_amt; ?>" ></td>
																			<td align="center"><input type="text" name="txt_hoa[]" id="txt_hoa" value="<?php echo $VouchList->hoa; ?>" ></td>
																			<td align="center"><input type="text" name="txt_new_hoa[]" id="txt_new_hoa" value="<?php echo $VouchList->new_hoa; ?>" ></td>
																			<td align="center"><input type="text" name="txt_indentor[]" id="txt_indentor" value="<?php echo $VouchList->indentor; ?>" ></td>
																			<td align="center"><input type="text" name="txt_grp_div_sec[]" id="txt_grp_div_sec" value="<?php echo $VouchList->grp_div_sec; ?>" ></td>
																			<td align="center"><input type="text" name="txt_plant_serv[]" id="txt_plant_serv" value="<?php echo $VouchList->plant_serv; ?>" ></td>
																			<td align="center"><input type="text" name="txt_sanct_om_act_sno[]" id="txt_sanct_om_act_sno" value="<?php echo $VouchList->sanct_om_act_sno; ?>" ></td>
																			<td align="center"><input type="text" name="txt_sanct_om_nwme_sno[]" id="txt_sanct_om_nwme_sno" value="<?php echo $VouchList->sanct_om_nwme_sno; ?>" ></td>
																			<td align="center"><?php echo date("d/m/Y h:i:s",strtotime($VouchList->createdon)); ?></td>
																		</tr>
																	<?php $Sno++; } } ?>	
																	</tbody>
																</table>
																<div style="text-align:center" id="buttonSection" class="">
																	<div class="buttonsection" id="BackbtnSec" style="display:inline-table">
																		<a data-url="VouchersView" class="btn btn-info">Back</a>
																	</div>
																	<div class="buttonsection" id="SaveBtnSec" style="display:inline-table">
																		<input type="submit" class="btn btn-info" value=" Update " name="btnUpdate" id="btnUpdate"   />
																	</div>
																</div>
															<?php }else{ ?>
																<table id="example" class="display rtable mgtb-8">
																	<thead>
																		<tr>
																			<th><input type="checkbox" name="check_all" id="check_all" value="ALL"></th>
																			<th>SNo.</th>
																			<th>File/PO</th>
																			<th style="width:150px;">Item</th>
																			<th>FIRM Name</th>
																			<th>PO Value</th>
																			<th>Vr. no</th>
																			<th>Vr. Date</th>
																			<th>VrAmt</th>
																			<th>PO/Rel Dt</th>
																			<th>PO/WO Completion Date</th>
																			<th>Final Payment completed Date</th>
																			<th>Status</th>
																			<th>O PIN</th>
																			<th>N PIN</th>
																			<th>CC NO</th>
																			<th>Paid[L]</th>
																			<th>HOA</th>
																			<th>New HOA</th>
																			<th>Indentor</th>
																			<th>GrpDivSec</th>
																			<th>Plant/Service</th>
																			<th>Sanction OM activity Sl.No</th>
																			<th>Sanction OM MW/ME Sl.No</th>
																			<th>Uploaded On</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php $Sno = 1; if($RowCount == 1){ while($VouchList = mysqli_fetch_object($SelectVouchSql1)){ ?>
																		<tr>
																			<th><input type="checkbox" name="ch_vouch[]" id="ch_vouch" value="<?php echo $VouchList->vuid; ?>"></th>
																			<td align="center"><?php echo $Sno; ?></td>
																			<td align="center"><?php echo $VouchList->wo; ?></td>
																			<td align="justify" style="width:150px;"><?php echo $VouchList->item; ?></td>
																			<td><?php echo $VouchList->name_contractor; ?></td>
																			<td align="right"><?php echo $VouchList->wo_amt; ?></td>
																			<td align="center"><?php echo $VouchList->vr_no; ?></td>
																			<td align="center"><?php if(($VouchList->vr_dt != NULL)&&($VouchList->vr_dt != "0000-00-00")){ echo dt_display($VouchList->vr_dt); } ?></td>
																			<td align="right"><?php echo $VouchList->vr_amt; ?></td>
																			<td align="center"><?php if(($VouchList->wo_dt != NULL)&&($VouchList->wo_dt != "0000-00-00")){ echo dt_display($VouchList->wo_dt); } ?></td>
																			<td align="center"><?php if(($VouchList->wo_comp_dt != NULL)&&($VouchList->wo_comp_dt != "0000-00-00")){ echo dt_display($VouchList->wo_comp_dt); } ?></td>
																			<td align="center"><?php if(($VouchList->final_pay_dt != NULL)&&($VouchList->final_pay_dt != "0000-00-00")){ echo dt_display($VouchList->final_pay_dt); } ?></td>
																			<td align="center"><?php echo $VouchList->work_status; ?></td>
																			<td align="center"><?php echo $VouchList->o_pin; ?></td>
																			<td align="center"><?php echo $VouchList->n_pin; ?></td>
																			<td align="center"><?php echo $VouchList->ccno; ?></td>
																			<td align="right"><?php echo $VouchList->paid_amt; ?></td>
																			<td align="center"><?php echo $VouchList->hoa; ?></td>
																			<td align="center"><?php echo $VouchList->new_hoa; ?></td>
																			<td align="center"><?php echo $VouchList->indentor; ?></td>
																			<td align="center"><?php echo $VouchList->grp_div_sec; ?></td>
																			<td align="center"><?php echo $VouchList->plant_serv; ?></td>
																			<td align="center"><?php echo $VouchList->sanct_om_act_sno; ?></td>
																			<td align="center"><?php echo $VouchList->sanct_om_nwme_sno; ?></td>
																			<td align="center"><?php echo date("d/m/Y h:i:s",strtotime($VouchList->createdon)); ?></td>
																		</tr>
																	<?php $Sno++; } } ?>	
																	</tbody>
																</table>
																<div style="text-align:center" id="buttonSection" class="">
																	<div class="buttonsection" id="BackbtnSec" style="display:inline-table">
																		<a data-url="VouchersView" class="btn btn-info">Back</a>
																	</div>
																	<div class="buttonsection" id="SaveBtnSec" style="display:inline-table">
																		<input type="submit" class="btn btn-info" value=" Delete " name="btnDelete" id="btnDelete"   />
																	</div>
																	<div class="buttonsection" id="SaveBtnSec" style="display:inline-table">
																		<input type="submit" class="btn btn-info" value=" Edit " name="btnEdit" id="btnEdit"   />
																	</div>
																</div>
																<?php } ?>
																<div class="row clearrow"></div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--<div class="div1">&nbsp;</div>-->
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
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","VouchersList.php");
					}
				}]
			});
		}
	};
	$("#check_all").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	th.tabtitle{
		text-align:left !important;
	}
	.mgtb-8 td{
		padding:2px !important;
		font-size:10px !important;
		font-weight:500;
	}
	.mgtb-8 th{
		background-color:#F2F3F4 !important;
		font-size:10px !important;
		padding:2px !important;
	}
</style>
