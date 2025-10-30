<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
//$GlobGr1Id = 2; $GlobGr2Id = 5;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount = 0; $PageTile = "";
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save'])){
	include("DataSheetSaveEdit.php");
}
$Disable = 0;
if(isset($_GET['refid']) != ''){
	$RefId 			= $_GET['refid'];
	$SelectQuery1 	= "select * from datasheet_master where ref_id = '$RefId'";
	$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 	= mysqli_fetch_object($SelectSql1);
			$DMGroupCode 	= $List1->type;
			$DMGroupDesc 	= $List1->group3_description;
			$DMQty 			= $List1->quantity;
			$DMUnit 		= $List1->unit;
			$DMGId 			= $List1->id;
			$DMParId 		= $List1->par_id;
			$DMNewMerge 	= $List1->new_merge;
			$DMCalcType 	= $List1->calc_type;
			$DMCostDtDesc 	= $List1->cost_dt;
			$DMToUnit 		= $List1->to_unit;
			$DMFinalUnit 	= $List1->final_unit;
			$DMDispQtyPerc 	= $List1->disp_qty_perc;
			$DMIsAverage 	= $List1->is_average;
			if($DMNewMerge == "M"){
				$Disable = 1;
			}else if(($DMNewMerge == "N")&&($DMCalcType == "WOC")){
				$Disable = 1;
			}else{
				$Disable = 0;
			}
		}
	}
	if($DMGroupCode != ""){
		$SelectQuery2 	= "select * from group_datasheet where type = '$DMGroupCode'";
		$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
		if($SelectSql2 == true){
			if(mysqli_num_rows($SelectSql2)>0){
				$List2 	= mysqli_fetch_object($SelectSql2);
				$GroupDesc 	= $List2->group_desc;
			}
		}
	}
	
	if($RefId != ""){
		$SelectQuery3 	= "select * from datasheet_a1_details where ref_id = '$RefId' ORDER BY dsdtid ASC";
		$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
		if($SelectSql3 == true){
			if(mysqli_num_rows($SelectSql3)>0){
				$DSDtRows = 1;
			}
		}
	}
}
function GetParChildHier($DMGId,$DMParId,$GroupDesc){
	global $dbConn, $dbConn;
	$ChidIdArr 	= array(); $ParIdArr = array(); $DescArr = array();
	array_push($ChidIdArr,$DMGId);
	$ParId 				= $DMParId;
	$ParIdArr[$DMGId] 	= $ParId;
	$DescArr[$DMGId] 	= $GroupDesc;
	while($ParId > 0){
		$SelectQuery 	 = "select id, par_id, group_desc from group_datasheet where id = '$ParId'";
		$SelectSql 		 = mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true){
			if(mysqli_num_rows($SelectSql)>0){
				$List 	 = mysqli_fetch_object($SelectSql);
				$ParId 	 = $List->par_id;
				$Id 	 = $List->id;
				$Desc 	 = $List->group_desc;
				$ParIdArr[$Id] 	= $ParId;
				$DescArr[$Id] 	= $Desc;
			}
		}
		array_push($ChidIdArr,$Id);
		
	}
	$ResultArr = array("CID"=>$ChidIdArr,"PID"=>$ParIdArr,"DSC"=>$DescArr);
	return $ResultArr;
}
$IdArrCnt = 0;
if(($DMGId != "")&&($DMParId != "")){
	$AllArr 	= GetParChildHier($DMGId,$DMParId,$GroupDesc);
	$ChildArr 	= $AllArr["CID"];
	$ParentArr 	= $AllArr["PID"];
	$DescrpArr 	= $AllArr["DSC"];
	$IdArrCnt 	= count($ChildArr);
	if($IdArrCnt > 0){
		sort($ChildArr);
	}
	$GlobGr1Id = $ChildArr[0]; 
	$GlobGr2Id = $ChildArr[1];
	$GlobGr3Id = $ChildArr[2];
}
//$DMNewMerge = "M";
$PageTile = $GlobPageTitleArr[$GlobGr2Id];
$PageName = $PTPart1.$PTIcon.'Schedule of Rate'.$PTIcon.$PageTile.$PTIcon."Edit";
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
													<input type="text" name="txt_cost" id="txt_cost" value="<?php echo $DMQty; ?>" class="tboxsmclass" />
												</div>
												<div class="div4" align="center">
													<select class="sboxsmclass" name="cmb_unit" ID="cmb_unit">
														<option value=""> -- Select -- </option>
														<?php echo $objBind->BindUnit($DMUnit); ?>
													</select>
												</div>
												<div class="div2" align="center">
													<i class="fa fa fa-paperclip cmd-box" id="CostDtBox"></i>
													<textarea name="txt_cost_det" id="txt_cost_det" style="display:none;"><?php echo $DMCostDtDesc; ?></textarea>
												</div>
											</div>
											<div class="row clearrow">
												<select class="text" style="width:648px;height:25px; display:none;" name="cmb_group[]" ID="cmb_group0" data-group="0">
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
														<?php echo $objBind->BindGroup3($GlobGr2Id,$GlobGr3Id); $Incr++; ?>
													</select>
												</div>
											</div>
											
											<?php $PrevGroup = 2; $MaxGroup = 3; for($g=3; $g<$IdArrCnt; $g++){ $CurrChiId = $ChildArr[$g]; $CurrParId = $ParentArr[$CurrChiId]; ?>
											<div class="<?php echo $MaxGroup; ?> G row clearrow"></div>
											<div class="<?php echo $MaxGroup; ?> G row">
												<div class="<?php echo $MaxGroup; ?> G div3 lboxlabel">Group <?php echo $MaxGroup; ?></div>
												<div class="<?php echo $MaxGroup; ?> G div9" align="center">
													<select name="cmb_group[]" id="cmb_group<?php echo $MaxGroup; ?>" data-group="<?php echo $MaxGroup; ?>" class="sboxsmclass group">
														<option value="Select">----------- Select -----------</option>
														<?php echo $objBind->BindGroup3($CurrParId,$CurrChiId); ?>
													</select>
												</div>
											</div>
											<?php $PrevGroup = $MaxGroup; $MaxGroup++; } ?>
											<div class="<?php echo $MaxGroup; ?> G row clearrow"></div>
								 			<div class="<?php echo $MaxGroup; ?> G row">
								 				<div class="<?php echo $MaxGroup; ?> G div3 lboxlabel">Group <?php echo $PrevGroup; ?> Desc</div>
								 				<div class="<?php echo $MaxGroup; ?> G div9" align="center"><textarea name="txt_group_desc" id="txt_group_desc" class="tboxsmclass" rows="10" style="width:96%" readonly=""><?php echo $GroupDesc; ?></textarea></div>
								 			</div>
											
										</div>
									</div>
								</div>
								<input type="hidden" name="max_group" id="max_group" value="<?php echo $PrevGroup; ?>">
								<input type="hidden" name="add_ded_disable" id="add_ded_disable" value="<?php echo $Disable; ?>">
								<input type="hidden" name="txt_edit" id="txt_edit" value="<?php echo $_GET['refid']; ?>">
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-p" align="center">Main Data / Sub-Data - Item Detils <?php //echo $RefId; ?></div>
										<div class="row innerdiv" align="center">
											<?php 
											if($DMNewMerge == 'N'){
												$NewStr 		= 'checked="checked"';
												$SDStr 			= '';
												$NewHideClass 	= "";
												$SDHideClass 	= "hide";
											}elseif($DMNewMerge == 'M'){ 
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
												<?php include("MainDataEntryFormEdit.php"); ?>
												<?php include("SubDataEntryFormEdit.php"); ?>
											</div>
											<div class="row clearrow new <?php echo $NewHideClass; ?>"></div>
											<div class="row lboxlabel new <?php echo $NewHideClass; ?>">
												&emsp;
												<input type="radio" name="calc_type" id="with_calc" class="calc_type" value="WC" checked="checked">&nbsp; With Calculation
												&emsp;&emsp;&emsp;
												<input type="radio" name="calc_type" id="without_calc" class="calc_type" value="WOC">&nbsp; Without Calculation
											</div>
											<?php include("DisposalQtySectionEdit.php"); ?>
											<div class="row clearrow">&nbsp;</div>
											<div class="row" align="center">
												<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" />
												<a data-url="<?php echo $_SESSION['ViewDSUrl']; ?>" class="btn btn-info">Back</a>
											</div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">
									<?php include("CommonAdditionDeductionEdit.php"); ?>
								</div>
							</div>
						</blockquote>
					</div>
				</div>
			</div>
			<?php include("CalculationSheetEdit.php"); ?> 
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script src="js/CommonJSLibrary.js"></script>
<script>
//alert(index);
index 		= "<?php echo $index1; ?>";
index2 		= "<?php echo $index2; ?>";
cindex 		= "<?php echo $CalcIndex; ?>";
cindexSD 	= "<?php echo $CalcIndex; ?>";
index 		= Number(index);
index2 		= Number(index2);
index 		= Number(cindex);
index2 		= Number(cindexSD);

var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$('.group option:not(:selected)').attr('disabled', true);
</script>
<style>
#mySidenav a {
	left:0px;
}
</style>