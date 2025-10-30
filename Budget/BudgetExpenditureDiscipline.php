<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'All Major Works Budget Expenditure';
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
												<div class="card-header inkblue-card" align="left">&nbsp;All Major Works Budget Expenditure Statement <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">Unit</div>
															<div>
																<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" >
																	<option value="ALL">All Units</option>
																	<?php echo $objBind->BindAllDaeUnits(0); ?>
																</select>
															</div>
														</div>
														
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Nature of Work</div>
															<div>
																<select class="group selectlgbox" name="cmb_discipline" id="cmb_discipline">
																	<option value="ALL">All Major Works</option>
																	<?php echo $objBind->BindDiscipline(0); ?>
																</select>
															</div>
														</div>
														<!--<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Head of Account</div>
															<div>
																<select class="group selectlgbox" name="cmb_hoa" id="cmb_hoa">
																	<option value="ALL">All Head of Accounts</option>
																	<?php echo $objBind->BindAllHOABudget(0); ?>
																</select>
															</div>
														</div>
														<div class="div1 pd-lr-1">
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
														<div class="div2 pd-lr-1" id="FY">
															<div class="lboxlabel-sm">Financial Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																	<?php echo $objBind->BindFinancialYear(''); ?>
																</select>
															</div>
														</div>
														
														<div class="div2 pd-lr-1 hide" id="PE1">
															<div class="lboxlabel-sm">From Date</div>
															<div>
																<input type="text" name="txt_from_date" id="txt_from_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="div2 pd-lr-1 hide" id="PE2">
															<div class="lboxlabel-sm">To Date</div>
															<div>
																<input type="text" name="txt_to_date" id="txt_to_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="div2 pd-lr-1" id="MO">
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
														</div>
														<div class="div2 pd-lr-1 hide" id="QU">
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
																	<option value="H2">Second Half (Oct - Mar)</option>
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
														</div>
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">Rupees &#x20b9; In</div>
															<div>
																<select class="group selectlgbox" name="cmb_rupees" id="cmb_rupees">
																	<option value="L" selected="selected">Lakhs</option>
																	<option value="C">Crores</option>
																</select>
															</div>
														</div>
														<div class="div3 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="button" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
																<a data-url="BudgetReports" class="btn btn-sm btn-ifo">BACK</a>
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
												<div class="card-header inkblue-card" align="left">&nbsp;All Major Works Budget Expenditure Statement <span id="CourseChartDuration"></span></div>
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
	/*$('#example').DataTable( {
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
		lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    } );*/
	/*var table = $('#example').DataTable( {
		scrollY:        "300px",
		scrollX:        true,
		scrollCollapse: true,
		paging:         false
	} );
	new $.fn.dataTable.FixedColumns( table );*/
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
	//$("#cmb_hoa").chosen();
	$("#cmb_discipline").chosen();
	//$("#cmb_mode").chosen();
	$("#cmb_month").chosen();
	$("#cmb_rupees").chosen();
	/*$('body').on("change","#cmb_mode", function(e){
		if($(this).val() == "MO"){
			$("#cmb_month").chosen('destroy');
			$("#cmb_month").chosen();
			$("#cmb_month_chosen").css("width","100%");
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").addClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").addClass("hide");
			$("#MO").removeClass("hide");
			$("#FY").removeClass("hide");
		}else if($(this).val() == "QU"){
			$("#cmb_quarter").chosen('destroy');
			$("#cmb_quarter").chosen();
			$("#cmb_quarter_chosen").css("width","100%");
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").removeClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").addClass("hide");
			$("#MO").addClass("hide");
			$("#FY").removeClass("hide");
		}else if($(this).val() == "HY"){
			$("#cmb_half_year").chosen('destroy');
			$("#cmb_half_year").chosen();
			$("#cmb_half_year_chosen").css("width","100%");
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").addClass("hide");
			$("#HY").removeClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").addClass("hide");
			$("#MO").addClass("hide");
			$("#FY").removeClass("hide");
		}else if($(this).val() == "TQ"){
			$("#cmb_three_quarter").chosen('destroy');
			$("#cmb_three_quarter").chosen();
			$("#cmb_three_quarter_chosen").css("width","100%");
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").addClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").removeClass("hide");
			$("#YE").addClass("hide");
			$("#MO").addClass("hide");
			$("#FY").removeClass("hide");
		}else if($(this).val() == "YE"){
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").addClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").removeClass("hide");
			$("#MO").addClass("hide");
			$("#FY").removeClass("hide");
			var FinYear = $("#cmb_fy").val();
			$("#txt_year").val(FinYear);
		}else if($(this).val() == "PE"){
			$("#PE1").removeClass("hide");
			$("#PE2").removeClass("hide");
			$("#QU").addClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").addClass("hide");
			$("#MO").addClass("hide");
			$("#FY").addClass("hide");
		}else{
			$("#PE1").addClass("hide");
			$("#PE2").addClass("hide");
			$("#QU").addClass("hide");
			$("#HY").addClass("hide");
			$("#TQ").addClass("hide");
			$("#YE").addClass("hide");
			$("#MO").removeClass("hide");
			$("#FY").removeClass("hide");
		}
	});*/
	/*$('body').on("change","#cmb_fy", function(e){
		$("#txt_year").val('');
		var FinYear = $(this).val();
		$("#txt_year").val(FinYear);
	});*/
	$('body').on("click","#btnView", function(event){
		
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudMode 	 	= "MO";//$("#cmb_mode").val();
		var BudMonth 	 	= $("#cmb_month").val();
		
		var TitleFinYear 	= $("#cmb_fy option:selected").text();
		var TitleDiscipline = $("#cmb_discipline option:selected").text();
		var TitleHoa 		= "";//$("#cmb_hoa option:selected").text();
		var TitleMode 		= "Monthly";//$("#cmb_mode option:selected").text();
		/*if(BudMode == "YE"){
			var TitleYear 	= $("#txt_year").val();
			if($("#cmb_hoa").val() == "ALL"){
				TitleHoa = "All the Head of Accounts";
			}else{
				TitleHoa = "Head of Account Number - "+TitleHoa;
			}
			var TitleStr = "Yearly Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
			$("#table-stmt").html('');
			var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="25" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">No.</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q1</th><th colspan="4">Q2</th><th colspan="4">Q3</th><th colspan="4">Q4</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Apr</th><th class="sum">May</th><th class="sum">Jun</th><th class="sum">Total</th><th class="sum">Jul</th><th class="sum">Aug</th><th class="sum">Sep</th><th class="sum">Total</th><th class="sum">Oct</th><th class="sum">Nov</th><th class="sum">Dec</th><th class="sum">Total</th><th class="sum">Jan</th><th class="sum">Feb</th><th class="sum">Mar</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
			$("#table-stmt").html(TableStr);
			YearlyExpenditure();
		}
		if(BudMode == "TQ"){
			var TitleQtr 	= $("#cmb_three_quarter option:selected").text();
			$("#table-stmt").html('');
			if($("#cmb_hoa").val() == "ALL"){
				TitleHoa = "All the Head of Accounts";
			}else{
				TitleHoa = "Head of Account Number - "+TitleHoa;
			}
			var TitleStr = "Three Quarter (Q1, Q2 and Q3) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
			var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="21" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q1</th><th colspan="4">Q2</th><th colspan="4">Q3</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Apr</th><th class="sum">May</th><th class="sum">Jun</th><th class="sum">Total</th><th class="sum">Jul</th><th class="sum">Aug</th><th class="sum">Sep</th><th class="sum">Total</th><th class="sum">Oct</th><th class="sum">Nov</th><th class="sum">Dec</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
			$("#table-stmt").html(TableStr);
			ThreeQtrExpenditure(); 
		}
		if(BudMode == "HY"){
			$("#table-stmt").html('');
			var HalfYear = $("#cmb_half_year").val();
			var TitleHalfYear = $("#cmb_half_year option:selected").text();
			if($("#cmb_hoa").val() == "ALL"){
				TitleHoa = "All the Head of Accounts";
			}else{
				TitleHoa = "Head of Account Number - "+TitleHoa;
			}
			if(HalfYear == "H1"){
				var TitleStr = "First Half Yearly (Q1 & Q2) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="17" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q1</th><th colspan="4">Q2</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Apr</th><th class="sum">May</th><th class="sum">Jun</th><th class="sum">Total</th><th class="sum">Jul</th><th class="sum">Aug</th><th class="sum">Sep</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
			}
			if(HalfYear == "H2"){
				var TitleStr = "Second Half Yearly (Q3 & Q4) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="17" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q3</th><th colspan="4">Q4</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Oct</th><th class="sum">Nov</th><th class="sum">Dec</th><th class="sum">Total</th><th class="sum">Jan</th><th class="sum">Feb</th><th class="sum">Mar</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
				HalfYearlyExpenditure();
			}
		}
		if(BudMode == "QU"){
			$("#table-stmt").html('');
			var Quarter = $("#cmb_quarter").val();
			var TitleQtr = $("#cmb_quarter option:selected").text();
			if($("#cmb_hoa").val() == "ALL"){
				TitleHoa = "All the Head of Accounts";
			}else{
				TitleHoa = "Head of Account Number - "+TitleHoa;
			}
			if(Quarter == "Q1"){
				var TitleStr = "Quarterly (Q1) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="13" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q1</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Apr</th><th class="sum">May</th><th class="sum">Jun</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
				QuarterExpenditure();
			}
			if(Quarter == "Q2"){
				var TitleStr = "Quarterly (Q2) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="12" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q2</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Jul</th><th class="sum">Aug</th><th class="sum">Sep</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
				QuarterExpenditure();
			}
			if(Quarter == "Q3"){
				var TitleStr = "Quarterly (Q3) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="12" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q3</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Oct</th><th class="sum">Nov</th><th class="sum">Dec</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
				QuarterExpenditure();
			}
			if(Quarter == "Q4"){
				var TitleStr = "Quarterly (Q4) Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
				var TableStr = '<table id="example" class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="12" style="text-align:left;">'+TitleStr+'</th></tr><tr><th rowspan="2">No.</th><th rowspan="2">HOA</th><th rowspan="2">Description of the work</th><th rowspan="2">Name of the Contractor</th><th rowspan="2">CC No. /WO No.</th><th rowspan="2">WO Value</th><th rowspan="2">Paid up to previous FY</th><th colspan="4">Q4</th><th rowspan="2">Total during Current FY</th><th rowspan="2">Exp. Up to date</th></tr><tr><th class="sum">Jan</th><th class="sum">Feb</th><th class="sum">Mar</th><th class="sum">Total</th></tr></thead><tbody></tbody>';
				$("#table-stmt").html(TableStr);
				QuarterExpenditure();
			}
		}*/
		if(BudMode == "MO"){
			$("#table-stmt").html('');
			var Month = $("#cmb_month option:selected").text();
			if($("#cmb_hoa").val() == "ALL"){
				TitleHoa = "";//"All the Head of Accounts";
			}else{
				TitleHoa = "";//"Head of Account Number - "+TitleHoa;
			}
			if($("#cmb_rupees").val() == "C"){
				var RupeesStr = "(&#x20b9; in Crores)";
			}else{
				var RupeesStr = "(&#x20b9; in Lakhs)";
			}
			var TitleStr = "Monthly ("+Month+") Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline;
			var TableStr = '<table class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="10" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>No.</th><th>HOA</th><th>Description of the work</th><th>Name of the Contractor</th><th>CC No. /WO No.</th><th class="sum">WO Value<br/>'+RupeesStr+'</th><th class="sum">Paid up to previous FY<br/>'+RupeesStr+'</th><th class="sum">'+Month+'<br/>'+RupeesStr+'</th><th class="sum">Total during Current FY<br/>'+RupeesStr+'</th><th class="sum">Exp. Up to date<br/>'+RupeesStr+'</th></tr></thead><tbody></tbody>';
				TableStr += '<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot></table>';
			$("#table-stmt").html(TableStr);
			MonthlyExpenditureDisicp(TitleStr);
		}
		
		/*if(BudMode == "PE"){
			$("#table-stmt").html('');
			var Month 		= $("#cmb_month option:selected").text();
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
			var TitleStr = "Periodic ("+FromDate+" - "+ToDate+") Wise Expenditure Statement of Financial Year "+TitleFinYear+" for the "+TitleDiscipline+" and "+TitleHoa;
			var TableStr = '<table class="display example" style="width:100%"><thead><tr><th class="tabtitle" colspan="10" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>No.</th><th>HOA</th><th>Description of the work</th><th>Name of the Contractor</th><th>CC No. /WO No.</th><th class="sum">WO Value<br/>'+RupeesStr+'</th><th class="sum">Paid up to previous FY<br/>'+RupeesStr+'</th><th class="sum">Amount<br/>'+RupeesStr+'</th><th class="sum">Total during Current FY<br/>'+RupeesStr+'</th><th class="sum">Exp. Up to date<br/>'+RupeesStr+'</th></tr></thead><tbody></tbody>';
				TableStr += '<tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot></table>';
			$("#table-stmt").html(TableStr);
			PeriodicExpenditure(TitleStr);
		}*/
			
	});
	
	
	
	
	
	
	
	$('body').on("click",".ExpModalData", function(event){
		var BudUnit 		= $(this).attr('data-unit');
		var BudDiscipline 	= $(this).attr('data-disc');
		var BudHoa 			= $(this).attr('data-hoa');
		var BudMode 	 	= $("#cmb_mode").val();
		var BudGlobId 		= $(this).attr('data-id');
		var BudMonth 		= $(this).attr('data-month');
		var BudView 		= $(this).attr('data-view');
		var Rupees 			= $("#cmb_rupees").val();
		var code 	 		= "MODMIS";
		if(BudMode == "PE"){
			var BudFinYear 	= "";
			var FromDate 	= $("#txt_from_date").val();
			var ToDate 		= $("#txt_to_date").val();	
		}else{
			var BudFinYear 	= $(this).attr('data-fy');
			var FromDate 	= "";
			var ToDate 		= "";	
		}
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudUnit: BudUnit, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, BudGlobId: BudGlobId, FromDate: FromDate, ToDate: ToDate, BudView: BudView }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TitleStr = "Miscellaneous works voucher details";
					var TableStr = '<table class="display rtable" style="width:100%"><thead><tr><th class="tabtitle" colspan="7" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>No.</th><th>Description</th><th>Voucher No.</th><th>Voucher Date</th><th>Voucher Amount</th><th>HOA</th><th>Discipline</th></tr></thead>';
					var Sno = 1;
						TableStr += '<tbody>';
					var TotalAmt = 0;
					$.each(Result, function(index, element) {
						var VouchAmt = element.vr_amt;
						TotalAmt = Number(TotalAmt) + Number(VouchAmt);
							VouchAmt = Number(VouchAmt).toFixed(2);
						TableStr += '<tr><td>'+Sno+'</td><td>'+element.item_desc+'</td><td>'+element.vr_no+'</td><td>'+element.vr_dt+'</td><td align="right">'+VouchAmt+'</td><td>'+element.hoa+'</td><td>'+element.grp_div_sec+'</td></tr>';
						Sno++;
					});
					if(Rupees =="C"){
						TotalAmt = TotalAmt.toFixed(2);//(TotalAmt / 10000000).toFixed(2);
					}else if(Rupees == "L"){
						TotalAmt = TotalAmt.toFixed(2);//(TotalAmt / 100000).toFixed(2);
					}else{
						TotalAmt = TotalAmt.toFixed(2);
					}
					
					TableStr += '<tr><td></td><td align="right">TOTAL</td><td></td><td></td><td align="right">'+TotalAmt+'</td><td></td><td></td></tr>';
					TableStr += '</tbody>';
					TableStr += '</table>';
					BootstrapDialog.show({
						title: 'Miscellaneous works voucher details',
						message: TableStr,
						closable: false,
						buttons: [{
							label: 'CLOSE',
							action: function(dialog) {
								dialog.close();
							}
						}]
					});
				}
			}
		});
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
