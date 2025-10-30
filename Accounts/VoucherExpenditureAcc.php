<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Voucher Expenditure';
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
								
								
								
								
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Voucher Expenditure Statement <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<!--<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">Unit</div>
															<div>
																<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" >
																	<option value="ALL">All Units</option>
																	<?php //echo $objBind->BindAllDaeUnits(0); ?>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Financial Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																	<?php //echo $objBind->BindFinancialYear(0); ?>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Nature of Work</div>
															<div>
																<select class="group selectlgbox" name="cmb_discipline" id="cmb_discipline">
																	<option value="ALL">All Major Works</option>
																	<option value="ALL">Civil</option>
																	<option value="ALL">Electrical</option>
																	<option value="ALL">Mechanical</option>
																	<option value="ALL">Instrumentaion</option>
																	<option value="ALL">ACV</option>
																</select>
															</div>
														</div>-->
														<!--<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Head of Account</div>
															<div>
																<select class="group selectlgbox" name="cmb_hoa" id="cmb_hoa">
																	<option value="ALL">All Head of Accounts</option>
																	<option value="">4861 03 212 06 00 01</option>
																	<option value="">4861 03 212 06 00 11</option>
																	<option value="">4861 03 212 06 00 12</option>
																	<option value="">4861 03 212 06 00 13</option>
																	<option value="">4861 03 212 06 00 21</option>
																	<option value="">4861 03 212 06 00 28</option>
																	<option value="">4861 03 212 06 00 51</option>
																	<option value="">4861 03 212 06 00 52</option>
																	<option value="">4861 03 212 06 00 53</option>
																	<option value="">4861 03 212 06 00 60</option>
																</select>
															</div>
														</div>-->
														<!--<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">Mode</div>
															<div>
																<select class="group selectlgbox" name="cmb_mode" id="cmb_mode">
																	<option value="MO">Monthly</option>
																	<option value="QU">Quarterly</option>
																	<option value="HY">Half Yearly</option>
																	<option value="TQ">3 Quarters</option>
																	<option value="YE">Yearly</option>
																	<option value="PE">Periodic</option>
																</select>
															</div>
														</div>-->
														
														
														<div class="div1 pd-lr-1" id="PE1">
															<div class="lboxlabel-sm">From Date</div>
															<div>
																<input type="text" name="txt_from_date" id="txt_from_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="div1 pd-lr-1" id="PE2">
															<div class="lboxlabel-sm">To Date</div>
															<div>
																<input type="text" name="txt_to_date" id="txt_to_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<!--<div class="div2 pd-lr-1" id="MO">
															<div class="lboxlabel-sm">Month</div>
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
														</div>-->
														<!--<div class="div2 pd-lr-1 hide" id="QU">
															<div class="lboxlabel-sm">Quarter</div>
															<div>
																<select class="group selectlgbox" name="cmb_quarter" id="cmb_quarter">
																	<option value="Q1" selected="selected">Quarter - 1</option>
																	<option value="Q2">Quarter - 2</option>
																	<option value="Q3">Quarter - 3</option>
																	<option value="Q4">Quarter - 4</option>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1 hide" id="HY">
															<div class="lboxlabel-sm">Half Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_half_year" id="cmb_half_year">
																	<option value="H1" selected="selected">First Half (Apr - Sep)</option>
																	<option value="H2">First Half (Oct - Mar)</option>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1 hide" id="TQ">
															<div class="lboxlabel-sm">Three Quarter</div>
															<div>
																<select class="group selectlgbox" name="cmb_three_quarter" id="cmb_three_quarter">
																	<option value="TQ1" selected="selected">Apr - Dec (Q1, Q2 & Q3)</option>
																</select>
															</div>
														</div>
														<div class="div2 pd-lr-1 hide" id="YE">
															<div class="lboxlabel-sm">Year</div>
															<div>
																<input type="text" name="txt_year" id="txt_year" readonly="" class="tboxclass tbox-sm" required />
															</div>
														</div>-->
														<!--<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Rupees &#x20b9; In</div>
															<div>
																<select class="group selectlgbox" name="cmb_rupees" id="cmb_rupees">
																	<option value="L" selected="selected">Lakhs</option>
																	<option value="C">Crores</option>
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
												<div class="card-header inkblue-card" align="left">&nbsp;Voucher Expenditure Statement <span id="CourseChartDuration"></span> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i> </span></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive" id="table-stmt">
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
	$('body').on("click","#btnView", function(event){
		var BudDiscipline 	= $("#cmb_discipline").val();
		//var BudMode 	 	= $("#cmb_mode").val();
		//var BudMonth 	 	= $("#cmb_month").val();
		
		var TitleFinYear 	= $("#cmb_fy option:selected").text();
		var TitleDiscipline = $("#cmb_discipline option:selected").text();
		var TitleHoa 		= $("#cmb_hoa option:selected").text();
		var TitleMode 		= $("#cmb_mode option:selected").text();

		$("#table-stmt").html('');
		//var Month 		= $("#cmb_month option:selected").text();
		var FromDate 	= $("#txt_from_date").val(); 
		var ToDate 		= $("#txt_to_date").val();
		if($("#cmb_hoa").val() == "ALL"){
			TitleHoa = "All the Head of Accounts";
		}else{
			TitleHoa = "Head of Account Number - "+TitleHoa;
		}
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		//var TitleStr = "Voucher Wise Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa+ " ("+FromDate+" - "+ToDate+")";
		var TitleStr = "Voucher Wise Expenditure Statement for period of ("+FromDate+" - "+ToDate+")";
		var TableStr = '<table class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="8" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>SNo.</th><th>Voucher No.</th><th>Voucher Date</th><th>Description of the work</th><th>Name of the Contractor</th><th class="sum">Voucher Amount<br/>'+RupeesStr+'</th><th>CC No./PO NO.</th><th>HOA</th></tr></thead><tbody></tbody>';
			TableStr += '<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot></table>';
		$("#table-stmt").html(TableStr);
		BootstrapDialog.show({
			title: 'Voucher Expenditure Statement',
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
			
	});
	
	function VoucherExpenditure(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val(); 
		var BudFinYear 	 	= $("#cmb_fy").val(); 
		var BudDiscipline 	= $("#cmb_discipline").val(); 
		var BudHoa 	 		= $("#cmb_hoa").val();
		//var BudMode 	 	= $("#cmb_mode").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var FromDate 		= $("#txt_from_date").val();
		var ToDate 			= $("#txt_to_date").val();	
		$('.example').DataTable({	
			"ajax": {
				"url": "../Budget/ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPVOU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= "";
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.FromDate 		= FromDate;
					d.ToDate 		= ToDate;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},	
				{ "data": "vr_no", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "vr_dt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "item", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "indentor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "vr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "ccno", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "hoa", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			
			
			
			//dom: "<'row'<'col-sm-12'<'toolbar'>>>lBfrtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html(' LIST OF ENTREPRENEUR WITH BUSINESS PLAN');
			 },
			 buttons: [
				/*'copy', 
				'csv', */
				'excel', 
				{
					extend: 'pdf',
				 	text: 'PDF',
				 	title: TitleStr,
				 	download: 'download',
				 	header: true
			   	}, 
			   /*	'print', */
			],
			"iDisplayLength": -1,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}
	
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
	.modal-dialog {
		width: 100%;
		margin: 3px;
	}
	.modal{
		box-sizing:border-box;
		padding-right: 12px !important;
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
	.modal-header{
		padding: 6px;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		font-size: 13px;
	}
	.close{
		font-size: 16px;
	}
	th.tabtitle{
		text-align:left !important;
	}
</style>
