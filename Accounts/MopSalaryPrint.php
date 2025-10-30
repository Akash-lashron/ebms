<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';
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
$count = 0; $SecuredAdvance = 0;
$i=0;


if($_GET['id'] != ""){ 
	$MemoId	= $_GET['id'];
}


/*if(($sheetid != "")&&($rbn != "")){
	$SelectAbstQuery = "select * from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
	$SelectAbstSql = mysqli_query($dbConn,$SelectAbstQuery);
	if($SelectAbstSql == true){
		if(mysqli_num_rows($SelectAbstSql)>0){
			$AbstList = mysqli_fetch_object($SelectAbstSql);
			$upto_date_total_amount = $AbstList->upto_date_total_amount;
			$dpm_total_amount = $AbstList->dpm_total_amount;
			$slm_total_amount = $AbstList->slm_total_amount;
			$Uptombookno = $AbstList->mbookno;
			$Uptombookpage = $AbstList->mbookpage;
		}
	}
}*/
$sheetid = '';
if($MemoId != ""){
	$AccSelectQuery = "select * from memo_payment_accounts_edit where memoid = '$MemoId'";
	$AccSelectSql 	= mysqli_query($dbConn,$AccSelectQuery);
	if($AccSelectSql == true){
		if(mysqli_num_rows($AccSelectSql)>0){
			$AccList = mysqli_fetch_object($AccSelectSql);
			$sheetid = $AccList->sheetid;
			$mop_date = $AccList->mop_date;
			$RefNumber = $AccList->misc_ref_no;
			$MisItemId = $AccList->mis_item_id;
			$abstract_net_amt = $AccList->abstract_net_amt;
			$cgst_percent = $AccList->cgst_percent;
			$cgst_amt = $AccList->cgst_amt;
			$sgst_percent = $AccList->sgst_percent;
			$sgst_amt = $AccList->sgst_amt;
			$sd_percent = $AccList->sd_percent;
			$sd_amt = $AccList->sd_amt;
			$wct_percent = $AccList->wct_percent;
			$wct_amt = $AccList->wct_amt;
			$vat_percent = $AccList->vat_percent;
			$vat_amt = $AccList->vat_amt;
			$mob_adv_percent = $AccList->mob_adv_percent;
			$mob_adv_amt = $AccList->mob_adv_amt;
			$lw_cess_percent = $AccList->lw_cess_percent;
			$lw_cess_amt = $AccList->lw_cess_amt;
			$incometax_percent = $AccList->incometax_percent;
			$incometax_amt = $AccList->incometax_amt;
			$it_cess_percent = $AccList->it_cess_percent;
			$it_cess_amt = $AccList->it_cess_amt;
			$it_edu_percent = $AccList->it_edu_percent;
			$it_edu_amt = $AccList->it_edu_amt;
			$land_rent = $AccList->land_rent;
			$liquid_damage = $AccList->liquid_damage;
			$other_recovery_1_desc = $AccList->other_recovery_1_desc;
			$other_recovery_1_amt = $AccList->other_recovery_1_amt;
			$other_recovery_2_desc = $AccList->other_recovery_2_desc;
			$other_recovery_2_amt = $AccList->other_recovery_2_amt;
			$non_dep_machine_equip = $AccList->non_dep_machine_equip;
			$non_dep_man_power = $AccList->non_dep_man_power;
			$nonsubmission_qa = $AccList->nonsubmission_qa;			
			$sec_adv_amount = $AccList->sec_adv_amount;
			$electricity_cost = $AccList->electricity_cost;
			$water_cost = $AccList->water_cost;
			$edit_flag = $AccList->edit_flag;	
			$lcess_fdate = $AccList->lcess_fdate;
			$lcess_tdate = $AccList->lcess_tdate;
			$net_payable_amt = $AccList->net_payable_amt;	
					
			$Acc = 1;

			if(($MisItemId != "")||($MisItemId != NULL)){
				$MisItemSelectQuery = "SELECT * FROM miscell_items WHERE mis_item_id = '$MisItemId'";
				//echo $MisItemSelectQuery;exit;
				$MisItemSelectQuerySql 	= mysqli_query($dbConn,$MisItemSelectQuery);
				if($MisItemSelectQuerySql == true){
					if(mysqli_num_rows($MisItemSelectQuerySql)>0){
						$MiscList = mysqli_fetch_object($MisItemSelectQuerySql);
						$MiscItName = $MiscList->mis_item_desc;
					}
				}
			}
			
			
			$ContId 		= $AccList->contid; 
			$ContBankId 	= $AccList->cbdtid; 
			if($ContId != 0){
				$SelectContQuery = "select * from contractor where contid = '$ContId'";
				$SelectContSql   = mysqli_query($dbConn,$SelectContQuery);
				if($SelectContSql == true){
					if(mysqli_num_rows($SelectContSql)>0){
						$ContList = mysqli_fetch_object($SelectContSql);
						$name_contractor 	= $ContList->name_contractor;
						$cont_address 		= $ContList->addr_contractor;
						$pan_no 			= $ContList->pan_no;
						$gst_no 			= $ContList->gst_no;
					}
				}
			}
			//echo $sheetid;exit;
			if($ContBankId != 0){
				$SelectBankQuery = "select * from contractor_bank_detail where cbdtid = '$ContBankId'";
				$SelectBankSql   = mysqli_query($dbConn,$SelectBankQuery);
				if($SelectBankSql == true){
					if(mysqli_num_rows($SelectBankSql)>0){
						$BankList 		= mysqli_fetch_object($SelectBankSql);
						$ContAccHolder 	= $BankList->bank_acc_hold_name;
						$ContAccNo 		= $BankList->bank_acc_no;
						$ContBankName 	= $BankList->bank_name;
						$ContBankBrAddr = $BankList->branch_address;
						$ContBankIfsc 	= $BankList->ifsc_code;
					}
				}
			}
			
			
			$HoaArr = array();
			$GlobId = $AccList->globid;
			$HoaId  = $AccList->hoaid; 
			if(($HoaId != '')&&($HoaId != NULL)){
				$SelectQuery1 = "SELECT * from hoa_master where hoamast_id IN ($HoaId)";
				$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
				if($SelectSql1 == true){
					if(mysqli_num_rows($SelectSql1)>0){
						while($List1 = mysqli_fetch_object($SelectSql1)){
							$HoaNo = $List1->new_hoa_no;
							array_push($HoaArr,$HoaNo);
						}
					}
				}
			}
			if(count($HoaArr)>0){
				$HoaStr = implode(",<br/> ",$HoaArr);
				$hoaNumber = $HoaStr;
			}
			
		}
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack(){
	   	url = "MopSalaryList.php";
		window.location.replace(url);
	}
	function PrintBook(){
	   var printContents 		= document.getElementById('printSection').innerHTML;
		var originalContents 	= document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<script src="stepWizard/bootstrap.min.js"></script>

<link href="stepWizard/jquery.wizard.css" rel="stylesheet">
<style>
	.table1{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.label{
		font-size:13px;
		font-weight:normal;
	}
	.table1 td{
	padding:3px;
	}
	.labellarge{
		font-size:18px;
	}
	.labelmedium{
		font-size:13px;
	}
	@media print {
		#printSection{
			padding-top:2px;
			text-align:center;
		}
	} 
</style>
<style type="text/css">
	
	.container-fluid{
	margin-top:10px;
	}
	p {
    margin-top: 20px;
    color: #072FEB;
    font-weight: normal;
	}
	.container{
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	table.label, .badge {
        padding: 0px 0px;
		background-color:#FFFFFF;
		font-weight: normal;
	}
	.table1 {
   	 border: 0px solid #D3D3D3;
	}
	span.label, .badge{
		background:#0099C6;
		padding-left:4px;
		padding-right:4px;
		padding-top:1px;
		padding-bottom:1px;
		font-weight:bold;
		color:#b5b5b5;
	}
	span.badge{
		background:#189de8;
		padding-left:6px;
		padding-right:6px;
		padding-top:3px;
		padding-bottom:3px;
		font-weight:bold;
		color:#FFFFFF;
	}
	.table1 td{
		vertical-align:middle;
	}
	.labelprint
	{
		font-weight:normal;
		color:#0B29B9;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:10pt;
	}
	.labelbold{
		font-weight:bold;
	}
	/*.btn-default, .btn-next{
		background:#B90250;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-default:hover, .btn-next:hover{
		background:#dd025d;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-prev{
		background:#0466B7;
		color:#FFFFFF;
		text-shadow:none;
	}
	.btn-prev:hover{
		background:#047ee2;
		color:#FFFFFF;
		text-shadow:none;
	}
	.final-step, .btn-success{
		background:#298E0D;
		color:#FFFFFF;
		text-shadow:none;
	}
	.final-step:hover, .btn-success:hover{
		background:#2ebc03;
		color:#FFFFFF;
		text-shadow:none;
	}*/
	.table1 td{
		background:#FFFFFF;
		/*color:#005BB7;*/
	}
	.labelbold{
		color:#00008B;
		font-size: 13px;
	}
	.labeldisplay{
		color:#0000CC;
		font-size: 13px;
	}
	.wiz-icon-arrow-right:before {
		padding-top:0px;
	}
	.wiz-icon-arrow-left::before {
		padding-top:0px;
	}
	.sweet-alert fieldset input[type="text"] {
		display: none;
	}
	.ui-datepicker select.ui-datepicker-month, .ui-datepicker select.ui-datepicker-year{
		z-index:99999999999999;
	}
	/*padding: 37px 20px 0 38px;*/
	/*.btn{
	 	padding: 0px 12px;
	 	height: 25px;
		border: 0px solid #ccc;
	}
	#btn_previous1, #btn_next1{
		font-size:12px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:normal;
	}*/
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <!--==============================header=================================-->
  <?php include "Menu.php"; ?>
  <link rel="stylesheet" href="css/timeline.css">
  <!--==============================Content=================================-->
	<div class="content">
    	<?php include "MainMenu.php"; ?>
		<div class="container_12">
        	<div class="grid_12">
				<blockquote class="bq1" style="overflow:auto">
					<form name="form" method="post" action="AccountsStatementSteps.php">
						<div class="container">
							<div class="div1">&nbsp;</div>
							<div class="div10">
								<div class="container-fluid">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="table-responsive dt-responsive ResultTable">
														<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
															<div class="card-header inkblue-card" align="center"> Memo Of Payment Salary </div>
															<!--	<ul class="steps">
																<li data-step="1">Memo Payment</li>
																<li data-step="2" >Abstract - B</li>
																<li data-step="3" >Recovery</li>
																<li data-step="4">Accounts Works</li>
																<li data-step="5"class="active">Bill Miscellaneous</li>
															</ul>-->
															<div class="steps-content" align="center" id="printSection">
																<style>
																	@media print {
																		.printbutton{
																			display:none;
																		}
																		body
																		{
																			font-size:15px !important;
																		}
																		
																		.TablePart{
																			margin:90px 60px 60px 60px;
																			font-size:18px;
																			border:none !important;
																		}
																		table, td, .tableM tr td {
																			border:none !important;
																			font-size:15px !important;
																		}
																		td, .tableM tr td{
																			padding:10px !important;
																			font-size:15px !important;
																			line-height:20px !important;
																		}
																	} 
																</style>
																<table width="100%" class="tableA" align="center">
																	<tr class="label">
																		<td colspan="2" align="center">Government Of India<br>Deportment Of Atomic Energy<br>BARC-NRB-FRFCF<br>Kalpakkam</td>
																	</tr>
																	<tr class="label">
																		<td colspan="2">
																			<span style="float:left">Ref. No : <?php echo $RefNumber; ?></span>
																			<span style="float:right">Date : <?php if(($mop_date != '0000-00-00')&&($mop_date != NULL)){ echo dt_display($mop_date); }else{ echo date("d/m/Y"); } ?>&nbsp;&nbsp;</span>
																		</td>
																	</tr>
																	<tr>
																		<td class="label" colspan="2" align="center">BILL FOR MISCELLANEOUS PAYMENTS - SALARY FOR THE PERIOD OF <?php echo dt_display($lcess_fdate); ?> - <?php echo dt_display($lcess_tdate); ?></td>
																	</tr>
																	<tr class="label">
																		<td align="left" width="250px" style="border-right:none;">Head Of Accounts </td>
																		<td align="left" style="border-left:none;">: &nbsp;<?php echo $hoaNumber; ?></td>
																	</tr>
																	<tr class="label">
																		<td align="left" style="border-right:none;">Name Of Payee </td>
																		<td align="left" style="border-left:none;">: &nbsp;<?php echo $name_contractor; ?></td>
																	</tr>
																	<!--	<tr class="label">
																		<td align="left">Address Of Payee : </td>
																		<td align="left"><?php //echo $cont_address; ?></td>
																	</tr>	-->
																	<tr class="label">
																		<td align="left" style="border-right:none;">Bill Amount </td>
																		<td align="left" style="border-left:none;">: &nbsp;Rs. <?php echo IND_money_format($net_payable_amt); echo "/-  ( "; echo number_to_words($net_payable_amt); echo " Rupees only )"; ?></td>
																	</tr>
																	<tr class="label">
																		<td align="left" style="border-right:none;">Nature Of Claim </td>
																		<td align="left" style="border-left:none;">: &nbsp;<?php echo strtoupper($MiscItName); ?></td>
																	</tr>
																	<tr class="label">
																		<td align="left" style="border-right:none;">Authority </td>
																		<td align="left" style="border-left:none;">: &nbsp;CA, FRFCF</td>
																	</tr>
																	<!--<tr class="label">
																		<td align="left">Bill No. & Date: </td>
																		<td align="left"><?php if($rbn != NULL){ ?> RAB : <?php echo $rbn; ?> <?php if($work_order_date != ""){ echo "&"; } ?><?php if($work_order_date != ""){ echo dt_display($work_order_date); } }?></td>
																	</tr>-->
																	<tr class="label">
																		<td align="left" style="border-right:none;">Amount Payable (Rs) </td>
																		<td align="left" style="border-left:none;">: &nbsp;Rs. <?php echo IND_money_format($net_payable_amt); echo "/-";?></td>
																	</tr>
																	<!--	<tr class="label">
																		<td align="left">INDUSTRIAL SAFE</td>
																		<td align="left">Rs</td>
																	</tr>
																	<tr class="label">
																		<td align="left">INCOME TAX</td>
																		<td align="left">Rs. <?php //echo IND_money_format($incometax_amt); ?></td>
																	</tr>
																	<tr class="label">
																		<td align="left">SD - ACCOUNTS</td>
																		<td align="left">Rs. <?php //echo IND_money_format($sd_amt); ?></td>
																	</tr>	-->
																	<tr class="label">
																		<td align="left" style="border-right:none;">Mode Of Payment </td>
																		<td align="left" style="border-left:none;">: &nbsp;NEFT</td>
																	</tr>
																	<!--	<tr class="label">
																		<td align="left" colspan="2"> Pay Rs in Words :<br><br><br></td>
																	</tr>	-->
																	<tr class="label">
																		<td colspan="2">
																			<span style="float:right"><br><br><br><br>AAO / SAO / DCA &emsp;</span>
																		</td>
																	</tr>
																</table>
																<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $sheetid; ?>"> 
																<input type="hidden" name="txt_rbn" id="txt_rbn" value="<?php echo $rbn; ?>"> 
														</div>
														<!-- <div class="row smclearrow">&nbsp;</div> -->
														<div align="center">
															<div class="row smclearrow">&nbsp;</div>
															<input type="button" name="btn_back" value=" Back " id="btn_back" class="btn btn-info" onClick="goBack();" />
															<input type="button" name="btn_print" value=" Print " id="btn_print" class="btn btn-info printbutton" onClick="PrintBook();" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="1">&nbsp;</div>
						</div>
					</form>
				</blockquote>
			</div>	
		</div>
	</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<style>
	.timeline__post {
		-webkit-box-shadow: 0 0px 0px 0 rgba(0, 0, 0, .12), 0 0px 1px 0 rgba(0, 0, 0, .24);
		box-shadow: 0 0px 0px 0 rgba(0, 0, 0, .12), 0 0px 1px 0 rgba(0, 0, 0, .24);
	}
	.timeline__post{
		margin-bottom:3px;
	}
	select, textarea, input[type="text"]{
		margin-bottom:1px;
		color:#0000CC;
	}
</style>
<script>
$(function() {
	$("#btn_previous1").click(function(){
		var sheetid = $('#txt_sheetid').val();
		var rbn = $('#txt_rbn').val();
		$(location).attr('href', "AccountsGenerateSection4.php?sheetid="+sheetid+"&rbn="+rbn)
	});
	$("#btn_previous2").click(function(){
		var sheetid = $('#txt_sheetid').val();
		var rbn = $('#txt_rbn').val();
		$(location).attr('href', "AccountsGenerateSection4.php?sheetid="+sheetid+"&rbn="+rbn)
	});	
});
</script>
<script>
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
</body>
</html>

