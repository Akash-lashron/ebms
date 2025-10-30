<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$GlobGr1Id = 162; $GlobGr2Id = 163;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount = 0; $PageTile = "";
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save'])){
	include("DataSheetSave.php");
}
$PageTile = $GlobPageTitleArr[$GlobGr1Id];
$PageName = $PTPart1.$PTIcon.'Schedule of Rate'.$PTIcon.$PageTile;
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
								<div class="div3" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Group Details</div>
										<div class="row innerdiv group-div" align="center">
											<div class="row">
												<div class="div3 lboxlabel">Cost for</div>
												<div class="div3">
													<input type="text" name="txt_cost" id="txt_cost" value="" class="tboxsmclass" />
												</div>
												<div class="div4" align="center">
													<select class="sboxsmclass" name="cmb_unit" ID="cmb_unit">
														<option value=""> -- Select -- </option>
														<?php echo $objBind->BindUnit(''); ?>
													</select>
												</div>
												<div class="div2" align="center">
													<i class="fa fa fa-paperclip cmd-box" id="CostDtBox"></i>
													<textarea name="txt_cost_det" id="txt_cost_det" style="display:none;"></textarea>
												</div>
											</div>
											<div class="row clearrow">
												<select class="text" style="width:648px;height:21px; display:none;" name="cmb_group[]" ID="cmb_group0" data-group="0">
													<?php echo $objBind->BindGroupI($GlobGr1Id); ?>
												</select>
											</div>
											<div class="row">
												<div class="div3 lboxlabel">Group 1</div>
												<div class="div9" align="center">
													<select class="group sboxsmclass" name="cmb_group[]" id="cmb_group1" data-group="1">
														<?php echo $objBind->BindGroup2($GlobGr1Id,$GlobGr2Id); ?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row" id="gr-append">
												<div class="div3 lboxlabel">Group 2</div>
												<div class="div9" align="center">
													<select name="cmb_group[]" id="cmb_group2" class="sboxsmclass group" data-group="2"> 
														<option data-id="" data-parid="" data-group="2" value="Select">----------- Select -----------</option>
														<?php echo $objBind->BindGroup3($GlobGr2Id,''); ?>
													</select>
													<input type="hidden" name="max_group" id="max_group" value="2">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-p" align="center">Main Data / Sub-Data - Item Detils</div>
										<div class="row innerdiv" align="center">
											<?php 
											if($new_merge == 'N'){
												$NewStr 		= 'checked="checked"';
												$SDStr 			= '';
												$NewHideClass 	= "";
												$SDHideClass 	= "hide";
											}elseif($new_merge == 'M'){ 
												$NewStr 		= '';
												$SDStr 			= 'checked="checked"';
												$NewHideClass 	= "hide";
												$SDHideClass 	= "";
											}else{
												$NewStr 		= 'checked="checked"';
												$SDStr 			= '';
												$NewHideClass 	= "";
												$SDHideClass 	= "hide";
											}
											?>
											<div class="row lboxlabel">
												&emsp;<input type="radio" name="rad_type" id="rad_new" <?php echo $NewStr; ?> value="N" class="rad_type" style="margin:0px;">&nbsp; New Main Data
												&emsp;&emsp;&emsp;
												<input type="radio" name="rad_type" id="rad_merge" <?php echo $SDStr; ?> value="M" class="rad_type" style="margin:0px;">&nbsp; Merge With Sub-Data
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<?php include("MainDataEntryForm.php"); ?>
												<?php include("SubDataEntryForm.php"); ?>
											</div>
											<div class="row clearrow new <?php echo $NewHideClass; ?>"></div>
											<div class="row lboxlabel new <?php echo $NewHideClass; ?>">
												&emsp;
												<input type="radio" name="calc_type" id="with_calc" class="calc_type" value="WC" checked="checked">&nbsp; With Calculation
												&emsp;&emsp;&emsp;
												<input type="radio" name="calc_type" id="without_calc" class="calc_type" value="WOC">&nbsp; Without Calculation
											</div>
											<?php include("DisposalQtySection.php"); ?>
											<div class="row clearrow">&nbsp;</div>
											<div class="row" align="center"><input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" /></div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">
									<?php include("CommonAdditionDeduction.php"); ?>
								</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
			<?php include("CalculationSheet.php"); ?> 
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script src="js/CommonJSLibrary.js"></script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>