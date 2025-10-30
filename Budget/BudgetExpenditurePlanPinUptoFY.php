<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Monthly Expenditure Plan for FRFCF';
$msg = "";
$CurrYear = date('Y');
$NextYear = date('Y', strtotime('+1 year'));
$PrevYear = date('Y', strtotime('-1 year'));
$CurrMonth = date('n');
if($CurrMonth > 3){
	$BudFinYear = $CurrYear."-".$NextYear;
}else{
	$BudFinYear = $PrevYear."-".$CurrYear;
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
								
								
								
								
								
								
								<div class="row clearrow"></div>
								<div class="box-container box-container-lg" align="center">
									<!--<div class="div2">&nbsp;</div>-->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Monthly Expenditure Plan Statement for FRFCF Project <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>PIN</th>
																			<th>Project Title</th>
																			<th>Proposed BE <?php echo $BudFinYear; ?></th>
																			<th>Approved BE <?php echo $BudFinYear; ?></th>
																			<th>Proposed RE <?php echo $BudFinYear; ?></th>
																			<th>Approved RE <?php echo $BudFinYear; ?></th>
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
																		
																		<!--<tr>
																			<td>712</td>
																			<td>Fast Reactor Fuel Cycle Facility</td>
																			<td id="PBE" align="right" class="Exp"></td>
																			<td id="PBE" align="right" class="Exp"></td>
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
																		</tr>-->
																	</tbody>
																</table>
																<div class="div12"><a data-url="BudgetReports" class="btn btn-ifo">Back</a></div>
																<div class="div12">&nbsp;</div>
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
	/*$("#cmb_unit").chosen();
	$("#cmb_fy").chosen();
	$("#cmb_rupees").chosen();
	$('body').on("click","#btnView", function(event){
		var TitleFinYear 	= $("#cmb_fy option:selected").text();
		$(".Exp").html('');
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		BudgetExpenditurePlanCurrFY();
			
	});*/
	BudgetExpenditurePlanCurrFY();
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
