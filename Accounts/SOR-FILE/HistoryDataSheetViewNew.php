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
			$DMToUnit 		= $List1->to_unit;
			$DMFinalUnit 	= $List1->final_unit;
			$DMDisposQty 	= $List1->disp_qty_perc;
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
											<table class="dstable table2excel" width="95%">
												<thead>
													<tr>
														<td class="div-tdcell ctext color-1"><?php echo $DMGroupCode; ?></td>
														<td class="div-tdcell" colspan="5" style="text-align:justify"><?php echo $GroupDesc; ?></td>
													</tr>
													<tr>
														<th class="ds-thcell ctext" nowrap="nowrap">Code</th>
														<th class="ds-thcell" width="50%">Description</th>
														<th class="ds-thcell rtext" nowrap="nowrap">Rate (&#8377;)</th>
														<th class="ds-thcell ctext">Unit</th>
														<th class="ds-thcell rtext" nowrap="nowrap">Qty.</th>
														<th class="ds-thcell rtext" nowrap="nowrap">Amount (&#8377;)</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">Details of cost for <span class="color-2"><?php echo $DMQty; ?> <?php echo $DMUnit; ?></span></td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
													</tr>
												<?php if($DMCostDtDesc != ""){ ?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell"><?php echo $DMCostDtDesc; ?></td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
													</tr>
												<?php } ?>
												<?php 
												$TotalItemAmount = 0;
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
													if($Type == "I"){
														$SelectQuery4 	= "select * from pru_detail where item_id = '$List3->item_id' and puid = '$PruId'";
														$SelectSql4 	= mysqli_query($dbConn,$SelectQuery4);
														if($SelectSql4 == true){
															if(mysqli_num_rows($SelectSql4)>0){
																$List4 	= mysqli_fetch_object($SelectSql4);
																$ItemCode 	= $List4->item_code;
																$ItemUnit 	= $List4->unit;
																$ItemRate 	= $List4->price;
															}
														}
													}else{ 
														$retVal = CalculateTSandIGCARRateMergeSubDataPrev($MergeRefId,$conn); 
														//echo $retVal."<br/>";
														$ExpretVal 		= explode("@**@",$retVal);
														$ForOneUnitRate = $ExpretVal[0];
														$IGCARRate2  	= $ExpretVal[1];
														$IGCARRate1  	= $ExpretVal[2];
														$GrossAmount 	= $ExpretVal[3];
														$ItemUnit 		= $ExpretVal[4];
														$MRGCalcType 	= $ExpretVal[5];
														$MRGNewMerge 	= $ExpretVal[6];
														$MRGAmtType 	= $ExpretVal[7];
														$ItemCode 		= $MergeItemCode;
														$ItemRate = '';
														if($MRGCalcType == "WOC"){ 
															$ItemRate = $GrossAmount;
														}else{
															if($AmtType == "GAMT"){
																$ItemRate = $GrossAmount;
															}else{
																// Based On Selection
																$TSRate 	= $ForOneUnitRate;
																$IGCARRate	= $IGCARRate1;
																//$ItemRate 	= $IGCARRate1;
																$ItemRate1 	= $TSRate;
																$ItemRate2 	= $IGCARRate;
															}
														}
														//echo " == ".$AmtType;exit;
													}
													//echo $ItemRate ."<br/>";
													if($CalcAction != ""){
														$ExpCalcAction 	 = explode(",",$CalcAction);
														$ExpActionFactor = explode(",",$ActionFactor);
														if(count($ExpCalcAction)>0){
															$TempAmount = $ItemRate; 
															foreach($ExpCalcAction as $key => $Value){ 
																$TempRate = $TempAmount;  
																$Action = $Value;
																$Factor = $ExpActionFactor[$key];
																if($Action == "A"){
																	$TempAmount = round(($TempRate + $Factor),2); 
																}
																if($Action == "S"){
																	$TempAmount = round(($TempRate - $Factor),2);
																}
																if($Action == "M"){
																	$TempAmount = round(($TempRate * $Factor),2);
																}
																if($Action == "D"){
																	$TempAmount = round(($TempRate / $Factor),2);
																}
																if($Action == "P"){
																	$TempAmount = round(($TempRate * $Factor  / 100),2);
																}
															}
															$ItemRate = $TempAmount;
														}
														
													}
													
													if(($ItemQty == 0)||($ItemQty == '')){
														$ItemAmount 	= round(($ItemRate * $ItemQty),2);//round($ItemRate,2);
													}else{
														$ItemAmount 	= round(($ItemRate * $ItemQty),2);
													}
													$TotalItemAmount = $TotalItemAmount + $ItemAmount;
													if($Title != ""){
												?>
													<tr>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell"><?php echo $Title; ?></td>
														<td class="div-tdcell rtext">&nbsp;</td>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell rtext">&nbsp;</td>
														<td class="div-tdcell rtext">&nbsp;</td>
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
														<?php } //echo "<br/>".$retVal; ?>
														</td>
														<td class="div-tdcell rtext"><?php echo IndianMoneyFormat($ItemRate); ?></td>
														<td class="div-tdcell ctext"><?php echo $ItemUnit; ?></td>
														<td class="div-tdcell rtext">
														<?php if($QtyDesc != ''){ ?>
															<span title="<?php echo $QtyDesc; ?>" class="tooltipwarning ttip"><u><?php echo $ItemQty; ?></u></span>
														<?php }else{ echo $ItemQty; } ?>
														</td>
														<td class="div-tdcell rtext"><?php echo IndianMoneyFormat($ItemAmount); ?></td>
													</tr>
													<?php } $TotalItemAmount = round($TotalItemAmount,2); ?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell rtext color-1" colspan="3">&nbsp;TOTAL</td>
														<td class="div-tdcell ctext color-1">W</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($TotalItemAmount); ?></td>
													</tr>
													<?php 
													if($DMCalcType == 'WC'){ 
														$W 	= $TotalItemAmount;
														$A 	= round(($TotalItemAmount * $DefValPercArrPrev[1] / 100),2);
														$WC = round(($W + $A),2);
														$B 	= round(($DefValPercArrPrev[6] * $WC),2);
														$X 	= round(($B + $WC),2);
														$C 	= round(($X * $DefValPercArrPrev[2] / 100),2);
														$Y 	= round(($X + $C),2);
														$D 	= round(($Y * $DefValPercArrPrev[3] / 100),2);
														$E 	= round(($W * $DefValPercArrPrev[4] / 100),2);
														$F 	= round(($Y+$D+$E),2);
														
														if($DMQty != ''){ 
															$ForOneUnit = round(($F/$DMQty),2); 
														}else{ 
															$ForOneUnit = round(($F/1),2); 
														}
														
														if($DMQty != ''){ 
															$G = round(($W*$DefValPercArrPrev[5] / (100 * $DMQty)),2); 
														}else{ 
															$G = round(($W*$DefValPercArrPrev[5] / 100),2); 
														}
														$UCFormula = "";
														$SelectUnitConvQuery = "select * from unit_conversion where from_unit = '$DMUnit' and to_unit = '$DMToUnit'";
														$SelectUnitConvSql 	 = mysqli_query($dbConn,$SelectUnitConvQuery);
														if($SelectUnitConvSql == true){
															if(mysqli_num_rows($SelectUnitConvSql)>0){
																$UCList = mysqli_fetch_object($SelectUnitConvSql);
																$UCFactor = $UCList->factor;
																$UCAction = $UCList->action;
																if($UCAction == "A"){ $ForOneUnit = $ForOneUnit + $UCFactor; $Symb = "+"; }
																if($UCAction == "S"){ $ForOneUnit = $ForOneUnit - $UCFactor; $Symb = "-"; }
																if($UCAction == "M"){ $ForOneUnit = $ForOneUnit * $UCFactor; $Symb = "x"; }
																if($UCAction == "D"){ $ForOneUnit = $ForOneUnit / $UCFactor; $Symb = "/"; }
																if($UCAction == "P"){ $ForOneUnit = $ForOneUnit * $UCFactor / 100; $Symb = "%"; }
																$ForOneUnit = round($ForOneUnit,2);
																//echo $UCAction;exit;
																if($DMQty != ''){ 
																	$G = round(($W*$DefValPercArrPrev[5]*$UCFactor/ (100 * $DMQty)),2); 
																}else{ 
																	$G = round(($W*$DefValPercArrPrev[5]*$UCFactor / 100),2); 
																}
																//echo $G;//exit;
																$UCFormula = $F.$Symb.$UCFactor."/".$DMQty;
															}
														}
														//echo $ForOneUnit;
														
														$IGCAR = round(($ForOneUnit+$G),2);
												?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;</td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">Add <?php echo IndianMoneyFormat($DefValPercArrPrev[1]); ?> % <?php echo $DefValNameArrPrev[1]; ?></td>
														<td class="div-tdcell ctext"><?php echo $DefValCodeArrPrev[1]; ?></td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($A); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">WC</td>
														<td class="div-tdcell ctext">W+A</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($WC); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3"><?php echo $DefValCodeArrPrev[6]; ?> = (<?php echo $DefValPercArrPrev[6]; ?> * WC)</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($B); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">&nbsp;</td>
														<td class="div-tdcell ctext">X</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($X); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">Add <?php echo $DefValPercArrPrev[2]; ?> % for <?php echo $DefValNameArrPrev[2]; ?> on (X)</td>
														<td class="div-tdcell ctext"><?php echo $DefValCodeArrPrev[2]; ?></td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($C); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">&nbsp;</td>
														<td class="div-tdcell ctext">Y</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($Y); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3"><?php echo $DefValPercArrPrev[3]; ?> % <?php echo $DefValNameArrPrev[3]; ?> on Y</td>
														<td class="div-tdcell ctext"><?php echo $DefValCodeArrPrev[3]; ?></td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($D); ?></td>
													</tr>
													
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3"><?php echo $DefValPercArrPrev[4]; ?> % <?php echo $DefValNameArrPrev[4]; ?> on W</td>
														<td class="div-tdcell ctext"><?php echo $DefValCodeArrPrev[4]; ?></td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($E); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">Total Y+D+E Cost of <span class="color-2"><?php echo $DMQty; echo '&nbsp;'; echo $DMUnit; ?></span></td>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($F); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">Cost of 1 <span class="color-2"><?php if($DMToUnit != ""){ echo $DMToUnit; }else{ echo $DMUnit; } ?></span></td>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell div-tdcellb rtext" title="<?php if($UCFormula != ""){ echo $UCFormula; } ?>">&nbsp;<?php echo IndianMoneyFormat($ForOneUnit); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="2">&nbsp;</td>
														<td class="div-tdcell rtext color-1" colspan="2">TW Rate</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($ForOneUnit); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="3">Add <?php echo $DefValPercArrPrev[5]; ?> % <?php echo $DefValNameArrPrev[5]; ?> on W per 1 unit</td>
														<td class="div-tdcell ctext">&nbsp;</td>
														<td class="div-tdcell div-tdcellb rtext">&nbsp;<?php echo IndianMoneyFormat($G); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="2">&nbsp;</td>
														<td class="div-tdcell rtext color-1" nowrap="nowrap" colspan="2">IGCAR Rate</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($IGCAR); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell rtext">
															<span class="color-2">
															<?php if($DMToUnit != ""){ echo $DMToUnit; }else{ if($DMFinalUnit != ""){ echo $DMFinalUnit; }else{ echo $DMUnit; } } ?>
															</span>
														</td>
													</tr>
												<?php 
												if($DMDisposQty != 0){ 
													$TSDisposQtyAmt  = round(($ForOneUnit * $DMDisposQty / 100),2);
													$IGCDisposQtyAmt = round(($IGCAR * $DMDisposQty / 100),2);
												?>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="2">For Disposal (<?php echo $DMDisposQty; ?>%) Qty.</td>
														<td class="div-tdcell rtext color-1" nowrap="nowrap" colspan="2">&nbsp;</td>
														<td class="div-tdcell rtext color-1">&nbsp;</td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="2">&nbsp;</td>
														<td class="div-tdcell rtext color-1" nowrap="nowrap" colspan="2">TW Rate</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($TSDisposQtyAmt); ?></td>
													</tr>
													<tr>
														<td class="div-tdcell">&nbsp;</td>
														<td class="div-tdcell ltext" colspan="2">&nbsp;</td>
														<td class="div-tdcell rtext color-1" nowrap="nowrap" colspan="2">IGCAR Rate</td>
														<td class="div-tdcell rtext color-1">&nbsp;<?php echo IndianMoneyFormat($IGCDisposQtyAmt); ?></td>
													</tr>
												<?php 
												} 
												?>
												<?php
													} 
												} ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div12" align="center">
									<a data-url="<?php echo $_SESSION['ViewDSUrl']; ?>" class="btn btn-info">Back</a>
									<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel">
									<input type="button" class="btn btn-info" name="exportToPdf" id="exportToPdf" value="Export - PDF" onClick="generate()">
								</div>
								<div class="div12" align="center">&nbsp;</div>
							</div>
						</blockquote>
						<div align="right" class="users-icon-part">&nbsp;</div>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           	<?php include "footer/footer.html"; ?>
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
	function generate() {
        var doc = new jsPDF();

        // Simple data example
        var head = [["ID", "Country", "Rank", "Capital"]];
        var body = [
            [1, "Denmark", 7.526, "Copenhagen"],
            [2, "Switzerland", 	7.509, "Bern"],
            [3, "Iceland", 7.501, "Reykjavík"]
        ];
        //doc.autoTable({head: head, body: body});

        // Simple html example
        doc.autoTable({html: '.table2excel'});

        doc.save('DataSheet.pdf');
    }
</script>