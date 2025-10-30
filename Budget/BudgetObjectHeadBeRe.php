<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Object Head Wise BE(Proposed & Approved) & RE(Proposed & Approved)';
$msg = "";
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
									<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Object Head Wise BE(Proposed & Approved) & RE(Proposed & Approved) <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														
														<div class="div4 pd-lr-1">
															<div class="lboxlabel-sm">Finacial Year</div>
															<div>
																<select class="group selectlgbox" name="cmb_fy" id="cmb_fy">
																	<?php echo $objBind->BindFinancialYear(''); ?>
																</select>
															</div>
														</div>
														
														<div class="div4 pd-lr-1" id="MO">
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
									<div class="div1">&nbsp;</div>
								</div>
								
								<div class="row clearrow"></div>
								<div class="box-container box-container-lg" align="center">
									<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Object Head Wise BE(Proposed & Approved) & RE(Proposed & Approved) Statement <span id="StmtDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>Object Heads</th>
																			<th>BE Proposed<div class="FyTitle"></div><div class="AmtTitle"></div></th>
																			<th>BE Approved<div class="FyTitle"></div><div class="AmtTitle"></div></th>
																			<th>RE Proposed<div class="FyTitle"></div><div class="AmtTitle"></div></th>
																			<th>RE Approved<div class="FyTitle"></div><div class="AmtTitle"></div></th>
																			<th>Expenditure upto date<div class="MonTitle"></div><div class="AmtTitle"></div></th>
																		</tr>
																	</thead>
																	<tbody>
																		
																	</tbody>
																</table>
																<div class="div12"><a data-url="BudgetReports" class="btn btn-ifo">Back</a></div>
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
	
	$("#cmb_fy").chosen();
	$("#cmb_month").chosen();
	$("#cmb_rupees").chosen();
	$('body').on("click","#btnView", function(event){
		ObjectHeadBeRe();
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
	th.tabtitle{
		text-align:left !important;
	}
</style>
