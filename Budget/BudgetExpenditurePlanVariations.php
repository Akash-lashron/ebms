<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Expenditure Plan Variations';
$msg = "";
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
									<!--<div class="div1">&nbsp;</div>-->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Variation Between Monthly Expenditure Plan (MEP) and Actual Expenditure  <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div3 pd-lr-1">
															<div class="lboxlabel-sm">Unit</div>
															<div>
																<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" >
																	<option value="All">All Units</option>
																	<?php echo $objBind->BindAllDaeUnits(0); ?>
																</select>
															</div>
														</div>
														<div class="div3 pd-lr-1">
															<div class="lboxlabel-sm">Financial Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																	<?php echo $objBind->BindFinancialYear(0); ?>
																</select>
															</div>
														</div>
														
														<!--<div class="div3 pd-lr-1" id="MO">
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
														</div>-->
														
														<div class="div3 pd-lr-1">
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
									<!--<div class="div1">&nbsp;</div>-->
								</div>
								
								<div class="row clearrow"></div>
								<div class="box-container box-container-lg" align="center">
									<!--<div class="div2">&nbsp;</div>-->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Statement of Variation Between Monthly Expenditure Plan (MEP) and Actual Expenditure  <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>Project Title</th>
																			<th>Approved BE 2022-23</th>
																			<th>Proposed RE 2022-23</th>
																			<th>Apr</th>
																			<th>May</th>
																			<th>Jun</th>
																			<th>Jul</th>
																			<th>Aug</th>
																			<th>Sep</th>
																			<th>Oct</th>
																			<th>Nov</th>
																			<th>Dec</th>
																			<th>Jan</th>
																			<th>Feb</th>
																			<th>Mar</th>
																			<th>Total <br/>(&#x20b9; in Lakhs)</th>
																		</tr>
																	</thead>
																	<tbody>
																		
																		<tr>
																			<td>Proposed MEP</td>
																			<td id="PBE" align="right" class="Exp"></td>
																			<td id="PRE" align="right" class="Exp"></td>
																			<td id="PE4" align="right" class="Exp"></td>
																			<td id="PE5" align="right" class="Exp"></td>
																			<td id="PE6" align="right" class="Exp"></td>
																			<td id="PE7" align="right" class="Exp"></td>
																			<td id="PE8" align="right" class="Exp"></td>
																			<td id="PE9" align="right" class="Exp"></td>
																			<td id="PE10" align="right" class="Exp"></td>
																			<td id="PE11" align="right" class="Exp"></td>
																			<td id="PE12" align="right" class="Exp"></td>
																			<td id="PE1" align="right" class="Exp"></td>
																			<td id="PE2" align="right" class="Exp"></td>
																			<td id="PE3" align="right" class="Exp"></td>
																			<td id="PETOT" align="right" class="Exp"></td>
																		</tr>
																		<tr>
																			<td>Actual Expenditure</td>
																			<td id="ABE" align="right" class="Exp"></td>
																			<td id="ARE" align="right" class="Exp"></td>
																			<td id="AE4" align="right" class="Exp"></td>
																			<td id="AE5" align="right" class="Exp"></td>
																			<td id="AE6" align="right" class="Exp"></td>
																			<td id="AE7" align="right" class="Exp"></td>
																			<td id="AE8" align="right" class="Exp"></td>
																			<td id="AE9" align="right" class="Exp"></td>
																			<td id="AE10" align="right" class="Exp"></td>
																			<td id="AE11" align="right" class="Exp"></td>
																			<td id="AE12" align="right" class="Exp"></td>
																			<td id="AE1" align="right" class="Exp"></td>
																			<td id="AE2" align="right" class="Exp"></td>
																			<td id="AE3" align="right" class="Exp"></td>
																			<td id="AETOT" align="right" class="Exp"></td>
																		</tr>
																		<tr>
																			<td>Variation between MEP and Actual</td>
																			<td id="VBE" align="right" class="Exp"></td>
																			<td id="VRE" align="right" class="Exp"></td>
																			<td id="VE4" align="right" class="Exp"></td>
																			<td id="VE5" align="right" class="Exp"></td>
																			<td id="VE6" align="right" class="Exp"></td>
																			<td id="VE7" align="right" class="Exp"></td>
																			<td id="VE8" align="right" class="Exp"></td>
																			<td id="VE9" align="right" class="Exp"></td>
																			<td id="VE10" align="right" class="Exp"></td>
																			<td id="VE11" align="right" class="Exp"></td>
																			<td id="VE12" align="right" class="Exp"></td>
																			<td id="VE1" align="right" class="Exp"></td>
																			<td id="VE2" align="right" class="Exp"></td>
																			<td id="VE3" align="right" class="Exp"></td>
																			<td id="VETOT" align="right" class="Exp"></td>
																		</tr>
																	</tbody>
																</table>
																
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--<div class="div2">&nbsp;</div>-->
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
	$("#cmb_rupees").chosen();
	$('body').on("click","#btnView", function(event){
		var TitleFinYear 	= $("#cmb_fy option:selected").text();
		//$("#table-stmt").html('');
		$(".Exp").html('');
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		//var TitleStr  = "Financial and Physical Progress Statement for the Finanial Year - "+TitleFinYear+" up to Month "+TitleMonth;
		//var TableStr  = '<table class="example display rtable mgtb-8" style="width:100%"><thead><tr><th class="tabtitle" colspan="2" style="text-align:left;">'+TitleStr+'</th></tr><tr><th>Description</th><th class="sum">Amount'+RupeesStr+'</th></tr></thead><tbody></tbody>';
			//TableStr += '<tfoot><tr><th></th><th></th></tr></tfoot></table>';
		//$("#table-stmt").html(TableStr);
		BudgetExpenditureVariation();
			
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
