<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
include "commonPrevSor.php";
$msg = ""; $del = 0; $RowCount =0; $PageTile = "";
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save_full_x'])){
	include("DataSheetSave.php");
}
$DSDtRows = 0;
if(isset($_GET['refid'])){
	$refid 			= $_GET['refid'];
	$DsId 			= $_GET['dsid'];
	$PruId 			= $_SESSION['PeriodicalId'];
	$PageTile 		= $GlobPageTitleArr[$DsId];
	$SelectQuery1 	= "select * from datasheet_master where ref_id = '$refid'";
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
			$DMFinalUnit 	= $List1->final_unit;
			$DMDisposQty 	= $List1->disp_qty_perc;
			$DMAverage 		= $List1->is_average;
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
	
	if($refid != ""){
		$SelectQuery3 	= "select * from datasheet_a1_details where ref_id = '$refid' ORDER BY dsdtid ASC";
		$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
		if($SelectSql3 == true){
			if(mysqli_num_rows($SelectSql3)>0){
				$DSDtRows = 1;
			}
		}
	}
}
include "DefaultMasterPrevSor.php";
$PageName = $PTPart1.$PTIcon.'History'.$PTIcon.'Schedule of Rate'.$PTIcon.$PageTile;
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
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="row clearrow"></div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Data Sheet View</div>
										<div class="row innerdiv group-div" align="center">
											<!--<div class="div2 lboxlabel color-1"><?php echo $DMGroupCode; ?></div>
											<div class="div10 lboxlabel" style="text-align:justify"><?php echo $GroupDesc; ?></div>-->
											<table class="dstable" width="95%">
												<thead>
													<tr>
														<td class="div-tdcell ctext color-1"><?php echo $DMGroupCode; ?></td>
														<td class="div-tdcell" colspan="3" style="text-align:justify"><?php echo $GroupDesc; ?></td>
													</tr>
													<tr>
														<th class="ds-thcell ctext" nowrap="nowrap">Code</th>
														<th class="ds-thcell" width="50%">Description</th>
														<th class="ds-thcell rtext" nowrap="nowrap">TS Rate (&#8377;)</th>
														<th class="ds-thcell rtext" nowrap="nowrap">IGCAR Rate (&#8377;)</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">Details of cost for <span class="color-2"><?php echo $DMQty; ?> <?php echo $DMUnit; ?></span></td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
													</tr>
												<?php if($DMCostDtDesc != ""){ ?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell"><?php echo $DMCostDtDesc; ?></td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
													</tr>
												<?php } ?>
												<?php 
												$TotalTSAmount = 0; $TotalIGCAmount = 0; $AvgCnt = 0;
												if($DSDtRows == 1){ while($List3 = mysqli_fetch_object($SelectSql3)){ 
													$ItemId 		= $List3->item_id;
													$Type 			= $List3->item_ds_type;
													$MergeItemCode 	= $List3->merge_item_code;
													$MergeRefId 	= $List3->merge_ref_id;
													$ItemQty 		= $List3->quantity;
													$ItemAltDesc 	= $List3->item_alt_desc;
													$CalcDesc 		= $List3->calc_desc;
													$QtyDesc 		= $List3->qty_desc;
													$CalcAction 	= $List3->calc_actions;
													$ActionFactor 	= $List3->actions_factors;
													$CalcType 		= $List3->calc_type;
													$AmtType 		= $List3->amt_type;
													$MergeItemCode 	= $List3->merge_item_code;
													$MergeRefId 	= $List3->merge_ref_id;
													$NewOrMerge 	= $List3->new_merge;
													$ItemDesc 		= $List3->item_desc;
													$Title 			= $List3->title;
													
													$retVal = CalculateTSandIGCARRateMergeSubDataPrev($MergeRefId,$conn);
													$ExpretVal 		= explode("@**@",$retVal);
													$ForOneUnitRate = $ExpretVal[0];
													$IGCARRate2  	= $ExpretVal[1];
													$IGCARRate1  	= $ExpretVal[2];
													$GrossAmount 	= $ExpretVal[3];
													$ItemUnit 		= $ExpretVal[4];
													$ItemCode 		= $MergeItemCode;
													//echo $retVal."<br/>";
													$TSRate 	= $ForOneUnitRate;
													$IGCARRate 	= $IGCARRate1;
													
													$ItemRate1 	= $TSRate;
													$ItemRate2 	= $IGCARRate;
													
													if($CalcAction != ""){
														$ExpCalcAction 	 = explode(",",$CalcAction);
														$ExpActionFactor = explode(",",$ActionFactor);
														if(count($ExpCalcAction)>0){
															$TempAmount1 = $ItemRate1; 
															$TempAmount2 = $ItemRate2; 
															foreach($ExpCalcAction as $key => $Value){ 
																$TempRate1 = $TempAmount1;  
																$TempRate2 = $TempAmount2;
																$Action = $Value;
																$Factor = $ExpActionFactor[$key];
																if($Action == "A"){
																	$TempAmount1 = round(($TempRate1 + $Factor),2); 
																	$TempAmount2 = round(($TempRate2 + $Factor),2); 
																}
																if($Action == "S"){
																	$TempAmount1 = round(($TempRate1 - $Factor),2);
																	$TempAmount2 = round(($TempRate2 - $Factor),2);
																}
																if($Action == "M"){
																	$TempAmount1 = round(($TempRate1 * $Factor),2);
																	$TempAmount2 = round(($TempRate2 * $Factor),2);
																}
																if($Action == "D"){
																	$TempAmount1 = round(($TempRate1 / $Factor),2);
																	$TempAmount2 = round(($TempRate2 / $Factor),2);
																}
																if($Action == "P"){
																	$TempAmount1 = round(($TempRate1 * $Factor  / 100),2);
																	$TempAmount2 = round(($TempRate2 * $Factor  / 100),2);
																}
															}
															$ItemRate1 = $TempAmount1;
															$ItemRate2 = $TempAmount2;
														}
														
													}
													$TSRate 	= $ItemRate1;
													$IGCARRate 	= $ItemRate2;
													if($DMDisposQty != 0){ 
														$TSRate  = round(($TSRate * $DMDisposQty / 100),2);
														$IGCARRate = round(($IGCARRate * $DMDisposQty / 100),2);
													}
													if($Title != ""){
												?>
													<tr>
														<tr>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell"><?php echo $Title; ?></td>
														<td class="div-tdcell rtext">&nbsp;</td>
														<td class="div-tdcell ctext">&nbsp;</td>
													</tr>
													</tr>
												<?php
													}
												?>
													<tr>
														<td class="div-tdcell ctext"><?php echo $ItemCode; ?></td>
														<td class="div-tdcell">
														<?php echo $ItemDesc; if($ItemAltDesc != ''){ echo "( ".$ItemAltDesc." )"; } ?>
														<?php if($CalcDesc != ''){ ?>
															<div class="div-tdcell-div"><?php echo $CalcDesc; ?></div>
														<?php } ?>
														</td>
														<td class="div-tdcell rtext"><?php echo IndianMoneyFormat($TSRate); $TotalTSAmount = $TotalTSAmount + $TSRate; ?></td>
														<td class="div-tdcell rtext"><?php echo IndianMoneyFormat($IGCARRate); $TotalIGCAmount = $TotalIGCAmount + $IGCARRate; ?></td>
													</tr>
													<?php $AvgCnt++; } $TotalTSAmount = round($TotalTSAmount,2); $TotalIGCAmount = round($TotalIGCAmount,2); ?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell rtext color-1">&nbsp;TOTAL</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($TotalTSAmount); ?></td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($TotalIGCAmount); ?></td>
													</tr>
													<?php if($DMAverage == "Y"){ 
													$AvgTSAmount = round(($TotalTSAmount / $AvgCnt),2); 
													$AvgIGCAmount = round(($TotalIGCAmount / $AvgCnt),2); 
													?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell rtext color-1">&nbsp;Average</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($AvgTSAmount); ?></td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($AvgIGCAmount); ?></td>
													</tr>
													<?php } ?>
													<!--<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell rtext color-1">
															<?php if($DMFinalUnit != ""){ echo $DMFinalUnit; }else{ echo $DMUnit; } ?>
														</td>
													</tr>-->
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div12" align="center"><a data-url="<?php echo $_SESSION['ViewDSUrl']; ?>" class="btn btn-info">Back</a></div>
							</div>
						</blockquote>
						<div align="right" class="users-icon-part">&nbsp;</div>
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
<script>
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
	$(document).ready(function(){ 
		$('.dataTable').DataTable({"paging":false});
	});
</script>