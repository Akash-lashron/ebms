<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Bank Aquitances';
$msg = "";
if(isset($_POST['btn_save']) == " Save "){
	$ParGroupArr 	= $_POST['cmb_group'];
	$NewGroupArr 	= $_POST['new_group'];
	$GroupType 		= $_POST['txt_type'];
	$CheckDisplay 	= $_POST['ch_display'];
	$ParCount 	 	= count($ParGroupArr);
	$ChiCount 	 	= count($NewGroupArr);
	$ParentId 	 	= $ParGroupArr[$ParCount-1];
	
	if($ParentId == "NEW"){
		if($ParCount == 1){
			$ParentId = 0;
		}else{
			$ParentId = $ParGroupArr[$ParCount-2];
		}
	}
	
	$NewGroup  	 	= $NewGroupArr[$ChiCount-1];
	$InsertQuery 	= "insert into group_datasheet set group_desc = '$NewGroup', type = '$GroupType', par_id = '$ParentId', disp = '$CheckDisplay', active = 1";
	$InsertSql 	 	= mysqli_query($dbConn,$InsertQuery);
	if($InsertSql == true){
		$msg = "New Group Created Successfully";
	}else{
		$msg = "Error : Group not created. Please try again.";
	}
}
//print_r($ChildArr);exit;
/*$SelectSheetQuery = "select * from sheet";// where sheet_id = '$SheetId'";
$SelectSheetSql = mysqli_query($dbConn, $SelectSheetQuery);
if($SelectSheetSql == true){
	if(mysqli_num_rows($SelectSheetSql)>0){
		while($List = mysqli_fetch_object($SelectSheetSql)){
			$WorkName 	= $List->short_name;
			$ContName 	= $List->name_contractor;
			$CCNo 		= $List->computer_code_no;
			$WoNo 		= $List->work_order_no;
			$WoValue 	= $List->work_order_cost;
			$IsData		= 1;
			$Rows['item'] 				= $WorkName;
			$Rows['name_contractor'] 	= $ContName;
			$Rows['ccno_wono'] 			= $CCNo."/".$WoNo;
			$Rows['wo_amt'] 			= $WoValue;
			$InsertQuery = "insert into works set sheetid = '$List->sheet_id', ccno = '$List->computer_code_no', work_name = '$List->work_name', ts_no = '$List->tech_sanction', 
			wo_no = '$List->work_order_no', wo_amount = '$List->work_order_cost', wo_date = '$List->work_order_date', agmt_no = '$List->agree_no', name_contractor = '$List->name_contractor'";
			$InsertSql = mysqli_query($dbConn, $InsertQuery);
		}
	}
}*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	function PrintBook(){
	   var printContents 		= document.getElementById('printSection').innerHTML;
		var originalContents 	= document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	@page{
			size: A4 portrait;
			margin: 6mm 6mm 6mm 6mm;
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
								
								
								
								
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Bank Acquittance<span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div1 pd-lr-1" id="PE1">
															<div class="lboxlabel-sm">From Date</div>
															<div>
																<input type="text" readonly="" name="txt_from_date" id="txt_from_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="div1 pd-lr-1" id="PE2">
															<div class="lboxlabel-sm">To Date</div>
															<div>
																<input type="text" readonly="" name="txt_to_date" id="txt_to_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<!--<div class="div1 pd-lr-1" id="PE2">
															<div class="lboxlabel-sm">Bank</div>
															<div>
																<select name="cmb_bnk_det" id="cmb_bnk_det" class="tboxclass tbox-sm" required >
																	<option value="">--Select--</option>
																	<option value="SBI">SBI</option>
																	<option value="OTB">Other Bank</option>
																</select>
															</div>
														</div>-->
														<div class="div1 pd-lr-1">
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

								</div>
								
								
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Bank Acquittances<span id="CourseChartDuration"></span> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i></u> </span></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div align="center" id="printSection">
																<style>
																	@media print {
																		.printbutton{
																			display:none;
																		}
																		body
																		{
																			font-size:15px !important;
																		}
																		
																		.SpanPart{
																			font-size:15px;
																			line-height:25px !important;
																			border:none !important;
																		}
																		.SpanPart1{
																			font-size:13px;
																			line-height:25px !important;
																			border:none !important;
																		}
																		.TablePart{
																			margin:90px 60px 60px 60px;
																			font-size:18px;
																			line-height:25px !important;
																			/* border:none !important; */
																		}
																		table, th, td, .tableM tr td, .tableM tr th {
																			/* border:none !important; */
																			font-size:15px !important;
																		}
																		td, .tableM tr td{
																			padding:10px !important;
																			font-size:15px !important;
																			line-height:25px !important;
																		}
																		th, .tableM tr th{
																			padding:10px !important;
																			font-size:15px !important;
																			line-height:25px !important;
																		}
																	} 
																</style>
																<div class="TablePart" align="center">
																	<div class="dataTable" id="table-stmt">
																		
																		<!--<table id="example" class="display" style="width:100%">
																			<thead>
																				<tr>
																					<th rowspan="2">No.</th>
																					<th rowspan="2">Description of the work</th>
																					<th rowspan="2">Name of the Contractor</th>
																					<th rowspan="2">CC No. /WO No.</th>
																					<th rowspan="2">WO Value</th>
																					<th rowspan="2">Paid up to previous FY</th>
																					<th colspan="4">Q1</th>
																					<th colspan="4">Q2</th>
																					<th colspan="4">Q3</th>
																					<th colspan="4">Q4</th>
																					<th rowspan="2">Total during Current FY</th>
																					<th rowspan="2">Exp. Up to date</th>
																				</tr>
																				<tr>
																					<th>Apr</th>
																					<th>May</th>
																					<th>Jun</th>
																					<th>Total</th>
																					<th>Jul</th>
																					<th>Aug</th>
																					<th>Sep</th>
																					<th>Total</th>
																					<th>Oct</th>
																					<th>Nov</th>
																					<th>Dec</th>
																					<th>Total</th>
																					<th>Jan</th>
																					<th>Feb</th>
																					<th>Mar</th>
																					<th>Total</th>
																				</tr>
																			</thead>
																			<tbody>

																			</tbody>
																		</table>-->
																	</div>
																	<div class="dataTable" id="table-stmt1">
																		
																		<!--<table id="example" class="display" style="width:100%">
																			<thead>
																				<tr>
																					<th rowspan="2">No.</th>
																					<th rowspan="2">Description of the work</th>
																					<th rowspan="2">Name of the Contractor</th>
																					<th rowspan="2">CC No. /WO No.</th>
																					<th rowspan="2">WO Value</th>
																					<th rowspan="2">Paid up to previous FY</th>
																					<th colspan="4">Q1</th>
																					<th colspan="4">Q2</th>
																					<th colspan="4">Q3</th>
																					<th colspan="4">Q4</th>
																					<th rowspan="2">Total during Current FY</th>
																					<th rowspan="2">Exp. Up to date</th>
																				</tr>
																				<tr>
																					<th>Apr</th>
																					<th>May</th>
																					<th>Jun</th>
																					<th>Total</th>
																					<th>Jul</th>
																					<th>Aug</th>
																					<th>Sep</th>
																					<th>Total</th>
																					<th>Oct</th>
																					<th>Nov</th>
																					<th>Dec</th>
																					<th>Total</th>
																					<th>Jan</th>
																					<th>Feb</th>
																					<th>Mar</th>
																					<th>Total</th>
																				</tr>
																			</thead>
																			<tbody>

																			</tbody>
																		</table>-->
																	</div>
																	<div id="printappendid" class="row"> </div>
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
						$(location).attr("href","DescriptionGroupCreate.php");
					}
				}]
			});
		}
	};
	$("#cmb_unit").chosen();
	$("#cmb_fy").chosen();
	$("#cmb_hoa").chosen();
	$("#cmb_discipline").chosen();
	$("#cmb_rupees").chosen();
	var ErrCount = 0;
	var ErrMsg = "";
	$('body').on("click","#btnView", function(event){
		var frdateval = $("#txt_from_date").val();
		var todateval = $("#txt_to_date").val();
		//var BankDetval = $("#cmb_bnk_det").val();
		$("#table-stmt").html('');
		$("#table-stmt1").html('');
		$("#printappendid").html('');

		if(frdateval == ""){
			ErrCount++;
			ErrMsg = "Please Select From Date..!!";
		}else if(todateval == ""){
			ErrCount++;
			ErrMsg = "Please Select To Date..!!";
		}/*else if(BankDetval == ""){
			ErrCount++;
			ErrMsg = "Please Select SBI or Other Bank..!!";
		}*/else{
			ErrCount = 0;
		}
		if(ErrCount == 0){
			$.ajax({ 
				type: 'POST', 
				dataType:'json',
				url: 'ajax/GetBankAquienceDet.php', 
				data: ({ pagecode: "BAQQ", frdateval: frdateval, todateval: todateval}), 
				success: function (data) {
					//alert(JSON.stringify(data));
					if(data != null){
						var SBIData = data['row1'];
						var OtherData = data['row2'];
						var Currdate = data['currdate'];
						
						//var getdate = getDate();
						//TitleStr = 'Bank acquaintance of Financial Year for the and ('+frdateval+' - '+todateval+')';
						//TitleStr +=	'<span style="float:left"><b>Ref No. :</b> </span>';
						//TitleStr =	'<span style="float:right"> '+getdate+' </span>';
						var PrintExe = 0;
						//switch(BankDetval){
							//case "SBI": 
								if((SBIData != "")||(SBIData != NULL)){ 
									var slno = 1;
									var TitleStr = '<span class="SpanPart" style="float:center"><b>GOVERNMENT OF INDIA</b></span><br/>';
										TitleStr +=	'<span class="SpanPart" style="float:center"><b>DEPARTMENT OF ATOMIC ENERGY</b></span><br/>';
										TitleStr +=	'<span class="SpanPart" style="float:center"><b>BHABHA ATOMIC RESEARCH CENTRE FACILITIES</b></span><br/>';
										TitleStr +=	'<span class="SpanPart" style="float:center"><b>NUCLEAR RECYCLE GROUP</b></span><br/>';
										TitleStr +=	'<span class="SpanPart" style="float:center"><b>KALPAKKAM - 603 102</b></span><br/><br/><br/><br/>';
										TitleStr +=	'<span style="float:center"><b>BANK ACQUITTANCE</b></span><br/>';
										TitleStr +=	'<span style="float:right">'+Currdate+'&emsp;</span><br/><br/><br/>';
									var TableStr = '<table width="100%" class="display tableM" style="width:100%">';
										TableStr += '<thead><tr><th class="cboxlabel" colspan="4" style="text-align:left;">'+TitleStr+'</th></tr>';
										TableStr += '<tr><th>SNo.</th><th>Name of the Payeee</th><th>Bank A/c No.</th><th class="sum">Amount ( &#8377; )<br/></th></tr>';
										TableStr += '</thead>';
									$.each(SBIData, function(index, element) { //alert(element.contid);
										var payamt1 = element.pay_order_amt;
										payamt1Formt = Number(payamt1).toFixed(2);
										payamt1Disp = (payamt1Formt).toLocaleString('en-IN');
										TableStr += '<tbody><tr>';
										TableStr += '<td align="center">'+slno+'</td>';
										TableStr += '<td align="left">'+element.name_contractor+'</td>';
										TableStr += '<td align="center" class="">'+element.bank_acc_no+'</td>';
										TableStr += '<td align="right" class="">'+payamt1Disp+'</td>';
										TableStr += '</tr></tbody>';
										slno++; PrintExe++;
									});
										TableStr += '</table><br/><br/><br/><br/><br/>';
										//TableStr +=	'<span class="SpanPart1" style="float:right">Asst.Account Officer (W)</span><br/>';
										//TableStr +=	'<span class="SpanPart1" style="float:right">FRFCF, Kalpakkam&emsp;&emsp;</span>';
										TableStr +=	'<span class="SpanPart1" style="float:left">&emsp;To</span><span class="SpanPart1" style="float:right">Asst.Account Officer (W)</span><br/>';
										TableStr +=	'<span class="SpanPart1" style="float:left">&emsp;Chief Manager</span><span class="SpanPart1" style="float:right">FRFCF, Kalpakkam&emsp;&emsp;</span><br/>';
										TableStr +=	'<span class="SpanPart1" style="float:left">&emsp;State Bank Of India,</span><br/>';
										TableStr +=	'<span class="SpanPart1" style="float:left">&emsp;<b>KALPAKKAM - 603 102</b></span><br/><br/>';
										TableStr +=	'<span class="SpanPart1" style="page-break-after:always;"></span>';
										
									$("#table-stmt").html(TableStr);
								}
							//break;
							//case "OTB":
								if((OtherData != "")||(OtherData != NULL)){ 
									//alert(OtherData);
									//$("table-stmt1").print("size,landscape");
									var slno1 = 1;
									var Totamt = 0;
									var TableStr1 = "";
									// if(SbiExe > 0){
									// 	TableStr1 += TableStr1+"<p style='page-break-after:always;'></p><div>&nbsp;<br/>&nbsp;&nbsp;<br/>&nbsp;</div>";
									// }
									var TitleStr1 = '<span class="SpanPart" style="float:center"><b>GOVERNMENT OF INDIA</b></span><br/>';
										TitleStr1 += '<span class="SpanPart" style="float:center"><b>DEPARTMENT OF ATOMIC ENERGY</b></span><br/>';
										TitleStr1 += '<span class="SpanPart" style="float:center"><b>BHABHA ATOMIC RESEARCH CENTRE FACILITIES</b></span><br/>';
										TitleStr1 += '<span class="SpanPart" style="float:center"><b>NUCLEAR RECYCLE GROUP</b></span><br/>';
										TitleStr1 += '<span class="SpanPart" style="float:center"><b>KALPAKKAM - 603 102</b></span><br/><br/><br/><br/>';
										TitleStr1 += '<span style="float:center"><b>BANK ACQUITTANCE</b></span><br/>';
										TitleStr1 += '<span style="float:right">'+Currdate+'&emsp;</span><br/><br/><br/>';
										TableStr1 += '<table width="100%" class="display tableM" style="width:100%">';
										TableStr1 += '<thead><tr><th class="cboxlabel" colspan="6" style="text-align:left;">'+TitleStr1+'</th></tr>';
										TableStr1 += '<tr><th>SNo.</th><th>Ifsc Code</th><th>NEFT Amount ( &#8377; )</th><th>Beneficiary A/c No.</th><th>Beneficiary Name</th><th class="sum">Address<br/></th></tr>';
										TableStr1 += '</thead>';
									$.each(OtherData, function(index, element1) { //alert(element.contid);
										var payamt = element1.pay_order_amt;
										payamtFormt = Number(payamt).toFixed(2);
										payamtDisp = (payamtFormt).toLocaleString('en-IN');
										TableStr1 += '<tbody><tr>';
										TableStr1 += '<td align="center">'+slno1+'</td>';
										TableStr1 += '<td align="left">'+element1.ifsc_code+'</td>';
										TableStr1 += '<td align="right" class="">'+payamtDisp+'</td>';
										TableStr1 += '<td align="center" class="">'+element1.bank_acc_no+'</td>';
										TableStr1 += '<td align="left" class="">'+element1.name_contractor+'</td>';
										TableStr1 += '<td align="left" class="">'+element1.branch_address+'</td>';
										Totamt = Number(Totamt)+Number(payamt);
										TotamtFormt = Number(Totamt).toFixed(2);
										TotamtDisp = (TotamtFormt).toLocaleString('en-IN');
										slno1++; PrintExe++;
									});
										TableStr1 += '</tr><tr><td colspan="2" align="right">Total Amount  :</td><td align="right">'+TotamtDisp+'</td><td colspan="3"></td></tr></tbody>';
										TableStr1 += '</table><br/><br/><br/><br/>';
										TableStr1 +=	'<span class="SpanPart1" style="float:right">Asst.Account Officer (W)</span><br/>';
										TableStr1 +=	'<span class="SpanPart1" style="float:right">FRFCF, Kalpakkam&emsp;&emsp;</span><br/>';
									$("#table-stmt1").html(TableStr1);
								}
							//break;
						//}
						//alert(PrintExe);
						if(PrintExe > 0){
							var PrintStr1 = '<div class="smclearrow"></div><div class="row" align="center"><input type="button" class="btn btn-sm btn-info printbutton" onClick="PrintBook();" value=" Print "></div>';					
							$("#printappendid").html(PrintStr1);
						}
					}else{
						BootstrapDialog.alert("Sorry..No data found...!!");
						return false;
					}
				}
			});
		}else if(ErrCount > 0){
			BootstrapDialog.alert(ErrMsg);
			event.preventDefault();
			event.returnValue = false;
		}
		/*
		var TitleStr = "Bank Acquitance of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa+ " ("+FromDate+" - "+ToDate+")";
		var TableStr = '<table class="display example" style="width:100%">';
		TableStr += '<thead><tr><th class="tabtitle" colspan="8" style="text-align:left;">'+TitleStr+'</th></tr>';
		TableStr += '<tr><th>SNo.</th><th>Name of the Payeee</th><th>Bank A/c No.</th><th class="sum">Amount Rs.<br/>'+RupeesStr+'</th></tr>';
		TableStr += '</thead><tbody></tbody>';
		TableStr += '<tfoot><tr><th></th><th></th><th></th><th></th></tr></tfoot></table>';
		$("#table-stmt").html(TableStr);
		BootstrapDialog.show({
			title: 'Bank Acquitance Report',
			message: TableStr,
			closable: false,
			buttons: [{
				label: 'CLOSE',
				action: function(dialog) {
					dialog.close();
				}
			}],
			onshown: function(dialogRef){ 
				VoucherExpenditure(TitleStr);
			},
		});
		*/
			
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
	.chosen-container-single .chosen-single{
		padding: 3px 4px;
	}
	
	
	div.dt-buttons{
		padding-left: 5px;
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
</style>
