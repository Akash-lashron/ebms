<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Vouchers View & Edit';
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
        <form action="VouchersList.php" method="post" enctype="multipart/form-data" name="form" id="form1">
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
									<div class="div3">&nbsp;</div>
									<div class="div6">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Vouchers View & Edit <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row clearrow"></div>
														<div class="div12 pd-lr-1">
															<div class="lboxlabel-sm">Unit</div>
															<div>
																<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" required>
																	<option value="">---- Select ----</option>
																	<option value="ALL">ALL Units</option>
																	<?php echo $objBind->BindAllDaeUnits(0); ?>
																</select>
															</div>
														</div>
														<div class="row clearrow"></div>
														<div class="div12 pd-lr-1">
															<div class="lboxlabel-sm">Head of Accounts</div>
															<div>
																<select class="group selectlgbox" name="cmb_hoa" id="cmb_hoa" required>
																	<option value="">---- Select ----</option>
																	<option value="ALL">All Head of Accounts</option>
																	<?php echo $objBind->BindAllHOABudget(0); ?>
																</select>
															</div>
														</div>
														<div class="row clearrow"></div>
														<div class="div6 pd-lr-1" id="MO">
															<div class="lboxlabel-sm">From Date</div>
															<div>
																<input type="text" name="txt_from_date" id="txt_from_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="div6 pd-lr-1">
															<div class="lboxlabel-sm">To Date</div>
															<div>
																<input type="text" name="txt_to_date" id="txt_to_date" class="tboxclass tbox-sm datepicker" required />
															</div>
														</div>
														<div class="row clearrow"></div>
														<div class="div12 pd-lr-1" align="center">
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
									<div class="div3">&nbsp;</div>
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
	$("#cmb_hoa").chosen();
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
</style>
