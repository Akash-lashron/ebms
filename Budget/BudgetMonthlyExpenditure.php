<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Monthly Expenditure';
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
									<div class="div3">&nbsp;</div>
									<div class="div6">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card">Daily Expenditure <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="row clearrow"></div>
													<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">Unit</label></div>
														<div class="div8">
															<select class="group selectlgbox" name="cmb_unit" id="cmb_unit" >
																<option value=""> ------------------------ Select ------------------------ </option>
																<option value="ALL">All Units</option>
																<?php echo $objBind->BindAllDaeUnits(0); ?>
															</select>
														</div>
													</div>
													<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">Discipline</label></div>
														<div class="div8">
															<select class="group selectlgbox" name="cmb_discipline" id="cmb_discipline" required>
																<option value=""> ------------------------ Select ------------------------ </option>
																<option value="ALL">All Discipline</option>
																
															</select>
														</div>
													</div>
													<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">Head of Account</label></div>
														<div class="div8">
															<select class="group selectlgbox" name="cmb_hoa" id="cmb_hoa" required>
																<option value=""> ------------------------ Select ------------------------ </option>
															</select>
														</div>
													</div>
													<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">Financial Year</label></div>
														<div class="div8">
															<select class="group selectlgbox" name="cmb_fy" id="cmb_fy" required>
																<option value=""> ------------------------ Select ------------------------ </option>
																<option value="ALL">All HOA</option>
																<?php echo $objBind->BindAllHOABudget(0); ?>
															</select>
														</div>
													</div>
													<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">Month</label></div>
														<div class="div8">
															<input type="text" name="txt_from_month" id="txt_from_month" class="tboxclass" required />
														</div>
													</div>
													<!--<div class="divrowbox">
														<div class="div4" align="left"><lable class="divlable">To Date</label></div>
														<div class="div8">
															<input type="text" name="txt_to_date" id="txt_to_date" class="tboxclass" required />
														</div>
													</div>-->
													<div class="divrowbox">
														<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value=" View ">
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
	$("#cmb_fy").chosen();
	$("#cmb_discipline").chosen();
	$('body').on("change",".group", function(e){
		
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
		padding: 7px 4px;
	}
</style>
