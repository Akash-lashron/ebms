<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$GlobGr1Id = 2; $GlobGr2Id = 7;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount = 0; $PageTile = "";
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save'])){
	include("DataSheetSave.php");
}
$PageTile = $GlobPageTitleArr[$GlobGr1Id];
$PageName = $PTPart1.$PTIcon.'Lead Charges';//.$PTIcon.$PageTile;
$LCItemDiesel 		= $GlobLCItemArr[0];
$LCItemEngineOil	= $GlobLCItemArr[1];
$LCItemBeldars 		= $GlobLCItemArr[2];
$LCItemTruckHC 		= $GlobLCItemArr[3];
$LCItemCoolie 		= $GlobLCItemArr[4];
$LCItemRateArr = array();
foreach($GlobLCItemArr as $LCItemId){
	$SelectQuery1 	= "select * from item_master where item_id = '$LCItemId'";
	$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			while($List1 = mysqli_fetch_object($SelectSql1)){
				$LCItemRateArr[$LCItemId][0] = $List1->item_code;
				$LCItemRateArr[$LCItemId][1] = $List1->price;
				$LCItemRateArr[$LCItemId][2] = $List1->unit;
			}
		}
	}
}
$DieselCost 		= $LCItemRateArr[$LCItemDiesel][1];
$EngineOilCost 		= $LCItemRateArr[$LCItemEngineOil][1];
$BeldersCost 		= $LCItemRateArr[$LCItemBeldars][1];
$TruckHireCharge 	= $LCItemRateArr[$LCItemTruckHC][1];
$CoolieCost 		= $LCItemRateArr[$LCItemCoolie][1];
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
								<div class="div9" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-p" align="center">Carriage of materials - by Mechanical Transport - I</div>
										<div class="row innerdiv" align="center">
											<div class="div12">
              									<div class="div3 lboxlabel">
													Cost of Diesel (&#x20B9;)
													<input type="text" class="tboxsmclass" id="txt_diesel_cost" name="txt_diesel_cost" value="<?php echo $DieselCost; ?>" required>
												</div>
												<div class="div3 cboxlabel">
													Mobile oil [Engine oil] Cost (&#x20B9;) 
													<input type="text" class="tboxsmclass" id="txt_engine_oil_cost" name="txt_engine_oil_cost" value="<?php echo $EngineOilCost; ?>" required>
												</div>
												<div class="div3 cboxlabel">
													Cost of Beldars (&#x20B9;)
													<input type="text" class="tboxsmclass" id="txt_beldars_cost" name="txt_beldars_cost" value="<?php echo $BeldersCost; ?>" required>
												</div>
												<div class="div3 cboxlabel">
													Hire Charge of Truck (&#x20B9;)
													<input type="text" class="tboxsmclass" id="txt_truck_hire_charge" name="txt_truck_hire_charge" value="<?php echo $TruckHireCharge; ?>" required>
												</div>
            								</div>
											<div class="row clearrow"></div>
											<div class="div12">
													<table align="center" id="tab_a1_material" class="itemtable">
														<thead>
															<tr>
																<th>1</th>
															 	<th>2</th>
															 	<th>3</th>
															 	<th>4</th>
															 	<th>5</th>
															 	<th>6</th>
															 	<th>7</th>
															 	<th>8</th>
															 	<th>9</th>
															 	<th>10</th>
															 	<th>11</th>
															 	<th>12</th>
															 	<th>13</th>
															 	<th>14</th>
														  	</tr>
														  	<tr>
																<th align="center" style="word-wrap: break-word;">Lead in kms (L)</th>
															 	<th align="center" style="word-wrap: break-word;">Avg speed km/hr (S)</th>
															 	<th align="center" style="word-wrap: break-word;">No of Trips 8/(2L/S)+1 (N)</th>
															 	<th>kms Done 2*NL+6 (K)</th>
															 	<th>Qty of Diesel [K/5]</th>
															 	<th>Cost of Diesel</th>
															 	<th>Qty of Mobile oil [K/140]</th>
															 	<th>Cost of Mobile oil</th>
															 	<th>Cost of 6 Mazdoor II Class</th>
															 	<th>Hire Charge of Truck</th>
															 	<th>Total Cost [6+8+9+10]</th>
															 	<th>Cost per Trip [C11/N]</th>
															 	<th>Increase in cost per km. over previous km.</th>
															 	<th>Average cost per additional km after first 5kms, 10 kms & 20 kms.</th>
														  	</tr>
														  	<tr>
																<th>km</th>
															 	<th>km/hr</th>
															 	<th>Nos</th>
															 	<th>km</th>
															 	<th>Ltr</th>
															 	<th>&#x20B9;</th>
															 	<th>Ltr</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
															 	<th>&#x20B9;</th>
														  	</tr>
														</thead>

														<?php  
														$Kms = 1; $KmHr = 16; $PrevCostTrip = ""; $TotalCostTripForAvg = 0; $Cnt = 0; $AvgCnt = 1;
														$CostTripArr = array(); $AvgCostArr = array();
														for($i=1; $i<=30; $i++){ 
															$NoOftrip      	=	8/((2*($Kms/$KmHr))+1);
														  	$NoOftrip      	=	round($NoOftrip,2);
														  	$KmsDone        =	(2*$NoOftrip*$Kms)+6;
														  	$KmsDone        =	round($KmsDone,2);
														  	$QtyDiesel   	=	$KmsDone/5;
														  	$CostDiesel  	=	$QtyDiesel*$DieselCost;
														  	$CostDiesel  	=	round($CostDiesel,2);
														  	$MobOilQty   	=	$KmsDone/140;
														  	$MobOilQty   	=	round($MobOilQty,2);
														  	$CostMobOil  	=	$MobOilQty*$EngineOilCost;
														 	$CostMObOil  	=	round($CostMobOil,2);
														  	$CostMazClass	=	($BeldersCost*6);
														  	$CostMazClass	=	round($CostMazClass,2);
														  	$HireCharTruck	=	($TruckHireCharge*1);
														  	$HireCharTruck	=	round($HireCharTruck,2);
														  	$TotCost      	=	($CostDiesel+$CostMObOil+$CostMazClass+$HireCharTruck);
														  	$TotCost      	=	round($TotCost,2);
														  	$CostPerTrip  	=	($TotCost/$NoOftrip);
														  	$CostPerTrip  	=	round($CostPerTrip,2);
															$IncrCostOverPrev = 0;
															if($PrevCostTrip != ""){
																$IncrCostOverPrev = round(($CostPerTrip - $PrevCostTrip),2);
															}
															if($Kms > 5){
																$TotalCostTripForAvg = $TotalCostTripForAvg + $IncrCostOverPrev;
																$Cnt++;
															}
															if($Kms % 10 == 0){
																$AverageCost = round(($TotalCostTripForAvg / $Cnt),2);
																$AvgCostArr[$AvgCnt] = $AverageCost;
																$TotalCostTripForAvg = 0; $Cnt = 0; $AvgCnt++;
															}else{
																$AverageCost = "";
															}
															$CostTripArr[$Kms] = $CostPerTrip;
														?>
															<tr>
																<td class="cboxlabel"><?php echo $Kms; ?></td>
																<td class="cboxlabel"><?php echo $KmHr; ?></td>
																<td class="cboxlabel"><?php echo $NoOftrip; ?></td>
																<td class="rboxlabel"><?php echo $KmsDone; ?></td>
																<td class="rboxlabel"><?php echo $QtyDiesel; ?></td>
																<td class="rboxlabel"><?php echo $CostDiesel; ?></td>
																<td class="rboxlabel"><?php echo $MobOilQty; ?></td>
																<td class="rboxlabel"><?php echo $CostMobOil; ?></td>
																<td class="rboxlabel"><?php echo $CostMazClass; ?></td>
																<td class="rboxlabel"><?php echo $HireCharTruck; ?></td>
																<td class="rboxlabel"><?php echo $TotCost; ?></td>
																<td class="rboxlabel"><?php echo $CostPerTrip; ?></td>
																<td class="rboxlabel"><?php echo $IncrCostOverPrev; ?></td>
																<td class="rboxlabel"><?php echo $AverageCost; ?></td>
															</tr>
														<?php 
															$Kms  = $Kms+1;
															$KmHr = $KmHr + 0.5; 
															$PrevCostTrip = $CostPerTrip;
														} 
														?>
													</table>
												</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="div3" align="center">
									<div class="innerdiv2">
										
									</div>
								</div>
								<div class="div9" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-p" align="center">Carriage of materials - by Mechanical Transport - II</div>
										<div class="row innerdiv" align="center">
											
											<div class="row clearrow"></div>
											<div class="div12">
													<table align="center" id="tab_a1_material" class="itemtable">
														<thead>
															
														  	<tr>
																<th rowspan="3">SNo.</th>
															 	<th rowspan="3">Materials</th>
															 	<th rowspan="3">Capacity per trip</th>
															 	<th rowspan="3" style="word-wrap: break-word;">Net Qty. payable after deduction of voids</th>
															 	<th rowspan="3">Unit of rates</th>
															 	<th>1 km</th>
															 	<th>2 km</th>
															 	<th>3 km</th>
															 	<th>4 km</th>
															 	<th>5 km</th>
															 	<th>Beyond 5km upto 10km per km</th>
															 	<th>Beyond 10km upto 20km per km</th>
															 	<th>Beyond 20km per Addl. km</th>
															 	<th rowspan="3">Remarks</th>
														  	</tr>
															<?php 
															$CostTrip1Km = round(($CostTripArr[1] + ($CostTripArr[1] * 15/100)),2);
															$CostTrip2Km = round(($CostTripArr[2] + ($CostTripArr[2] * 15/100)),2);
															$CostTrip3Km = round(($CostTripArr[3] + ($CostTripArr[3] * 15/100)),2);
															$CostTrip4Km = round(($CostTripArr[4] + ($CostTripArr[4] * 15/100)),2);
															$CostTrip5Km = round(($CostTripArr[5] + ($CostTripArr[5] * 15/100)),2);
															$AvgCostBeyond5Km = round(($AvgCostArr[1] + ($AvgCostArr[1] * 15/100)),2);
															$AvgCostBeyond10Km = round(($AvgCostArr[2] + ($AvgCostArr[2] * 15/100)),2);
															$AvgCostBeyond20Km = round(($AvgCostArr[3] + ($AvgCostArr[3] * 15/100)),2);
															?>
														  	<tr>
															 	<th><?php echo $CostTrip1Km; ?></th>
															 	<th><?php echo $CostTrip2Km; ?></th>
															 	<th><?php echo $CostTrip3Km; ?></th>
															 	<th><?php echo $CostTrip4Km; ?></th>
															 	<th><?php echo $CostTrip5Km; ?></th>
															 	<th><?php echo $AvgCostBeyond5Km; ?></th>
															 	<th><?php echo $AvgCostBeyond10Km; ?></th>
															 	<th><?php echo $AvgCostBeyond20Km; ?></th>
														  	</tr>
															<tr>
															 	<th colspan="8">[Cost per trip] (CP & OH 15% added)</th>
														  	</tr>
														</thead>

														<tr>
															<td class="cboxlabel">1(a)</td>
															<td class="jboxlabel">Lime, moorum, building rubbish, earth, manure or sludge and excavated rocks</td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
														</tr>
														<?php
														$TWComRateFor1Km 		= round(($CostTrip1Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateFor2Km 		= round(($CostTrip2Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateFor3Km 		= round(($CostTrip3Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateFor4Km 		= round(($CostTrip4Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateFor5Km 		= round(($CostTrip5Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateForBeyond5Km 	= round(($AvgCostBeyond5Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateForBeyond10Km = round(($AvgCostBeyond10Km / $GlobNetPayableAfterVoidDed),2);
														$TWComRateForBeyond20Km	= round(($AvgCostBeyond20Km / $GlobNetPayableAfterVoidDed),2);
														
														$PSComRateFor1Km 		= round(($TWComRateFor1Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateFor2Km 		= round(($TWComRateFor2Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateFor3Km 		= round(($TWComRateFor3Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateFor4Km 		= round(($TWComRateFor4Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateFor5Km 		= round(($TWComRateFor5Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateForBeyond5Km 	= round(($TWComRateForBeyond5Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateForBeyond10Km = round(($TWComRateForBeyond10Km * 1.2 / (1 + 1 * 15/100)),2);
														$PSComRateForBeyond20Km	= round(($TWComRateForBeyond20Km * 1.2 / (1 + 1 * 15/100)),2);
														
														$FBComRateFor1Km 		= round(($TWComRateFor1Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateFor2Km 		= round(($TWComRateFor2Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateFor3Km 		= round(($TWComRateFor3Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateFor4Km 		= round(($TWComRateFor4Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateFor5Km 		= round(($TWComRateFor5Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateForBeyond5Km 	= round(($TWComRateForBeyond5Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateForBeyond10Km = round(($TWComRateForBeyond10Km * 1.22 / (1 + 1 * 15/100)),2);
														$FBComRateForBeyond20Km	= round(($TWComRateForBeyond20Km * 1.22 / (1 + 1 * 15/100)),2);
														?>
														<tr>
															<td class="cboxlabel">A</td>
															<td class="jboxlabel">1.1.2 Earth</td>
															<td class="cboxlabel"><?php echo $GlobCapacity; ?></td>
															<td class="cboxlabel"><?php echo $GlobNetPayableAfterVoidDed; ?></td>
															<td class="cboxlabel">1 cum</td>
															<td class="cboxlabel"><?php echo $TWComRateFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor5Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateForBeyond5Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateForBeyond10Km; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateForBeyond20Km; ?></td>
															<td class="cboxlabel">Township</td>
														</tr>
														<tr>
															<td class="cboxlabel">B</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $PSComRateFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor5Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateForBeyond5Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateForBeyond10Km; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateForBeyond20Km; ?></td>
															<td class="cboxlabel" nowrap="nowrap">Plant Site</td>
														</tr>
														<tr>
															<td class="cboxlabel">C</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $FBComRateFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor5Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateForBeyond5Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateForBeyond10Km; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateForBeyond20Km; ?></td>
															<td class="cboxlabel">FBTR</td>
														</tr>
														<?php 
														$TWComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														$TWComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * (1 + 1 * 15/100)),2);
														
														$PSComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.2),2);
														$PSComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * 1.2),2);
														
														$FBComRateFor1KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateFor2KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateFor3KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateFor4KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateFor5KmAftCoolie 			= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateForBeyond5KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateForBeyond10KmAftCoolie 	= round(($CoolieCost * 0.18 * 1.22),2);
														$FBComRateForBeyond20KmAftCoolie	= round(($CoolieCost * 0.18 * 1.22),2);
														?>
														<tr>
															<td class="cboxlabel">D</td>
															<td class="jboxlabel">Add : 0.16 Coolie for levelling at yard</td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $TWComRateFor1KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor2KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor3KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor4KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $TWComRateFor5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $TWComRateForBeyond5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $TWComRateForBeyond10KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $TWComRateForBeyond20KmAftCoolie; ?></td>
															<td class="cboxlabel">Township</td>
														</tr>
														<tr>
															<td class="cboxlabel">E</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $PSComRateFor1KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor2KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor3KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor4KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $PSComRateFor5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $PSComRateForBeyond5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $PSComRateForBeyond10KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $PSComRateForBeyond20KmAftCoolie; ?></td>
															<td class="cboxlabel">Plant Site</td>
														</tr>
														<tr>
															<td class="cboxlabel">F</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $FBComRateFor1KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor2KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor3KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor4KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php echo $FBComRateFor5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $FBComRateForBeyond5KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $FBComRateForBeyond10KmAftCoolie; ?></td>
															<td class="cboxlabel"><?php //echo $FBComRateForBeyond20KmAftCoolie; ?></td>
															<td class="cboxlabel">FBTR</td>
														</tr>
														<tr class="itemRow">
															<td class="cboxlabel">&nbsp;</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel">EW45a</td>
															<td class="cboxlabel">EW45b</td>
															<td class="cboxlabel">EW45c</td>
															<td class="cboxlabel">EW45d</td>
															<td class="cboxlabel">EW45e</td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
														</tr>
														<?php 
														$TWLeadChrgeFor1Km = round(($TWComRateFor1Km + $TWComRateFor1KmAftCoolie),2);
														$TWLeadChrgeFor2Km = round(($TWComRateFor2Km + $TWComRateFor2KmAftCoolie),2);
														$TWLeadChrgeFor3Km = round(($TWComRateFor3Km + $TWComRateFor3KmAftCoolie),2);
														$TWLeadChrgeFor4Km = round(($TWComRateFor4Km + $TWComRateFor4KmAftCoolie),2);
														$TWLeadChrgeFor5Km = round(($TWComRateFor5Km + $TWComRateFor5KmAftCoolie),2);
														
														$PSLeadChrgeFor1Km = round(($PSComRateFor1Km + $PSComRateFor1KmAftCoolie),2);
														$PSLeadChrgeFor2Km = round(($PSComRateFor2Km + $PSComRateFor2KmAftCoolie),2);
														$PSLeadChrgeFor3Km = round(($PSComRateFor3Km + $PSomRateFor3KmAftCoolie),2);
														$PSLeadChrgeFor4Km = round(($PSComRateFor4Km + $PSComRateFor4KmAftCoolie),2);
														$PSLeadChrgeFor5Km = round(($PSComRateFor5Km + $PSComRateFor5KmAftCoolie),2);
														
														$FBLeadChrgeFor1Km = round(($FBComRateFor1Km + $FBComRateFor1KmAftCoolie),2);
														$FBLeadChrgeFor2Km = round(($FBComRateFor2Km + $FBComRateFor2KmAftCoolie),2);
														$FBLeadChrgeFor3Km = round(($FBComRateFor3Km + $FBComRateFor3KmAftCoolie),2);
														$FBLeadChrgeFor4Km = round(($FBComRateFor4Km + $FBComRateFor4KmAftCoolie),2);
														$FBLeadChrgeFor5Km = round(($FBComRateFor5Km + $FBComRateFor5KmAftCoolie),2);
														?>
														<tr>
															<td class="cboxlabel">A+D</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $TWLeadChrgeFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $TWLeadChrgeFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $TWLeadChrgeFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $TWLeadChrgeFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $TWLeadChrgeFor5Km; ?></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel">Township</td>
														</tr>
														<tr>
															<td class="cboxlabel">B+E</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $PSLeadChrgeFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $PSLeadChrgeFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $PSLeadChrgeFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $PSLeadChrgeFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $PSLeadChrgeFor5Km; ?></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel">Plant Site</td>
														</tr>
														<tr>
															<td class="cboxlabel">C+F</td>
															<td class="jboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"><?php echo $FBLeadChrgeFor1Km; ?></td>
															<td class="cboxlabel"><?php echo $FBLeadChrgeFor2Km; ?></td>
															<td class="cboxlabel"><?php echo $FBLeadChrgeFor3Km; ?></td>
															<td class="cboxlabel"><?php echo $FBLeadChrgeFor4Km; ?></td>
															<td class="cboxlabel"><?php echo $FBLeadChrgeFor5Km; ?></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel"></td>
															<td class="cboxlabel">FBTR</td>
														</tr>
													</table>
											</div>
											<div class="row clearrow"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row clearrow"></div>
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
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>
<style>
#mySidenav a {
	left:0px;
}
.itemtable td, .itemtable th{
	border:1px solid #898E92;
}
.itemtable th{
	font-size:11px;
	padding:4px 2px;
}
.itemtable td{
	font-size:11px;
	font-weight:600;
	color:#0A45B4;
	vertical-align:middle;
	padding:4px 2px;
}
.itemRow td{
	background:#039CD1; 
	color:#fff;
}
</style>