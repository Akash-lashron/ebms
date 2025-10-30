<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Abstract'.$PTIcon."Township";
$result = mysqli_query($dbConn,"SELECT * FROM group_datasheet ORDER BY par_id asc");// ORDER BY type asc, group_id asc");
//create a multidimensional array to hold a list of category and parent category
$category = array(
	'categories' => array(),
	'parent_cats' => array()
);
//build the array lists with data from the category table
while($row = mysqli_fetch_assoc($result)) {
	$category['categories'][$row['id']] = $row;
	$category['parent_cats'][$row['par_id']][] = $row['id'];
}
function buildCategory($parent, $category, $conn) {
	global $dbConn, $dbConn, $GlobLCItemArr, $GlobCapacity, $GlobNetPayableAfterVoidDed, $GlobLCItemCodeArr;
	include "DefaultMaster.php";
	$html = "";
	if (isset($category['parent_cats'][$parent])) {
		foreach ($category['parent_cats'][$parent] as $cat_id) {
			//if (!isset($category['parent_cats'][$cat_id])) {
				/// Here have to take the datasheet
				$Type = $category['categories'][$cat_id]['type'];
				$ID = $category['categories'][$cat_id]['id'];
				
				$MasterRefId = '';
				$IsDataSheet = 0;
				
				$DMGroupCode = ''; $DMGroupDesc = ''; $DMQty = ''; $DMUnit = ''; $DMGId = ''; $DMParId = ''; $DMNewMerge = ''; $DMCalcType = ''; $DMCostDtDesc = ''; $DMRefId = ''; $DMDisposQty = 0;
				$SelectQuery1 	= "select * from datasheet_master where id = '$ID'";
				//echo $SelectQuery1."<br/>";
				$SelectSql1 	= mysqli_query($dbConn,$SelectQuery1);
				if($SelectSql1 == true){
					if(mysqli_num_rows($SelectSql1)>0){
						$List1 	= mysqli_fetch_object($SelectSql1);
						$DMRefId 	= $List1->ref_id;
						$DMGroupCode 	= $List1->type;
						$DMGroupDesc 	= $List1->group3_description;
						$DMQty 			= $List1->quantity;
						$DMUnit 		= $List1->unit;
						$DMGId 			= $List1->id;
						$DMParId 		= $List1->par_id;
						$DMNewMerge 	= $List1->new_merge;
						$DMCalcType 	= $List1->calc_type;
						$DMCostDtDesc 	= $List1->cost_dt;
						$DMDisposQty 	= $List1->disp_qty_perc;
						$DMAverage 		= $List1->is_average;
						$IsDataSheet	= 1;
					}
				}
			if($IsDataSheet == 1){
				$GroupDesc = '';
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
				$DSDtRows = 0;
				if($DMRefId != ""){
					$SelectQuery3 	= "select * from datasheet_a1_details where ref_id = '$DMRefId'";
					$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							$DSDtRows = 1;
						}
					}
				}
				$AvgCnt = 0;
				$ItemId = ''; $Type = ''; $MergeItemCode = ''; $MergeRefId = ''; $ItemQty = ''; $ItemAltDesc = ''; $CalcDesc = ''; $QtyDesc = ''; $CalcAction = ''; $ActionFactor = '';
				$CalcType = ''; $AmtType = ''; $MergeItemCode = ''; $MergeRefId = ''; $NewOrMerge = ''; $ItemDesc = ''; $ItemCode = ''; $ItemUnit = ''; $ItemRate = 0; $ItemAmount = 0;
				$TotalItemAmount = 0; $TotalItemAmount2 = 0; $TSRate = ''; $IGCAR = '';
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
					$AvgCnt++;
					if($Type == "I"){
						$SelectQuery4 	= "select * from item_master where item_id = '$List3->item_id'";
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
						$retVal = CalculateTSandIGCARRateMergeSubData($MergeRefId,$conn);
						$ExpretVal 		= explode("@**@",$retVal);
						$ForOneUnitRate = $ExpretVal[0];
						$IGCARRate2  	= $ExpretVal[1];
						$IGCARRate1  	= $ExpretVal[2];
						$GrossAmount 	= $ExpretVal[3];
						$ItemUnit 		= $ExpretVal[4];
						$ItemCode 		= $MergeItemCode;
						$ItemRate = '';
						if($CalcType == "WOC"){
							$ItemRate = $GrossAmount;
						}else{
							/*if($AmtType == "GMT"){
								$ItemRate = $GrossAmount;
							}else{
								// Based On Selection
							}*/
							$TSRate 	= $ForOneUnitRate;
							$IGCARRate	= $IGCARRate1;
							//$ItemRate 	= $IGCARRate1;
							$ItemRate1 	= $TSRate;
							$ItemRate2 	= $IGCARRate;
						}
					}
					if($DMNewMerge == "N"){								
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
							$ItemAmount 	= round($ItemRate,2);
						}else{
							$ItemAmount 	= round(($ItemRate * $ItemQty),2);
						}
						$TotalItemAmount = $TotalItemAmount + $ItemAmount;
					}else{
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
													
						/*if(($ItemQty == 0)||($ItemQty == '')){
							$ItemAmount 	= round($ItemRate,2);
						}else{
							$ItemAmount 	= round(($ItemRate * $ItemQty),2);
						}
						$TotalItemAmount = $TotalItemAmount + $ItemAmount;*/
						$TotalItemAmount = $TotalItemAmount + $ItemRate1;
						$TotalItemAmount2 = $TotalItemAmount2 + $ItemRate2;
					}
					
				} }
				
				if($DMCalcType == 'WC'){ 
					$W 	= $TotalItemAmount;
					$A 	= round(($TotalItemAmount * $DefValPercArr[1] / 100),2);
					$WC = round(($W + $A),2);
					$B 	= round(($DefValPercArr[6] * $WC),2);
					$X 	= round(($B + $WC),2);
					$C 	= round(($X * $DefValPercArr[2] / 100),2);
					$Y 	= round(($X + $C),2);
					$D 	= round(($Y * $DefValPercArr[3] / 100),2);
					$E 	= round(($W * $DefValPercArr[4] / 100),2);
					$F 	= round(($Y+$D+$E),2);
													
					if($DMQty != ''){ 
						$ForOneUnit = round(($F/$DMQty),2); 
					}else{ 
						$ForOneUnit = round(($F/1),2); 
					}
														
					if($DMQty != ''){ 
						$G = round(($W*$DefValPercArr[5] / (100 * $DMQty)),2); 
					}else{ 
						$G = round(($W*$DefValPercArr[5] / 100),2); 
					}
					$TSRate = $ForOneUnit;
					$IGCAR = round(($ForOneUnit+$G),2);
					if($DMDisposQty != 0){ 
						$TSRate  = round(($TSRate * $DMDisposQty / 100),2);
						$IGCAR = round(($IGCAR * $DMDisposQty / 100),2);
					}
				}else{
					$TSRate = round($TotalItemAmount2,2);
					$IGCAR = round($TotalItemAmount,2);
				}
				if(in_array($category['categories'][$cat_id]['type'], $GlobLCItemCodeArr)){
					$LCCode = $category['categories'][$cat_id]['type'];
					include "LeadChargesCalculation.php";
					$TSRate = $AbstractArr[$LCCode][0];
					$IGCAR = $AbstractArr[$LCCode][1];
				}
				if($DMAverage == "Y"){ 
					if($AvgCnt > 0){
					$TSRate = round(($TSRate / $AvgCnt),2); 
					$IGCAR = round(($IGCAR / $AvgCnt),2); 
					}
				}
				$html .= "<tr class='labeldisplay'><td class='tdrowbold' valign='middle' align='center'>" . $category['categories'][$cat_id]['type'] ."</td><td valign='middle' class='tdrow'>". $category['categories'][$cat_id]['group_desc'] . "</td><td class='tdrow' align='center' valign='middle'>".$DMUnit."</td><td class='tdrow' align='right' valign='middle'>".IndianMoneyFormat($TSRate)."</td></tr>";
			}
			//if(isset($category['parent_cats'][$cat_id])){
			if($IsDataSheet == 0){
				$html .= "<tr class='labeldisplay'><td class='tdrowbold' valign='middle' align='center'>" . $category['categories'][$cat_id]['type'] ."</td><td valign='middle' class='tdrow'>". $category['categories'][$cat_id]['group_desc'] . "</td><td class='tdrow' valign='middle'>&nbsp;</td><td class='tdrow' valign='middle'>&nbsp;</td></tr>";
			}
			$html .= buildCategory($cat_id, $category, $conn);
		}
	}
	return $html;
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
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
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
						<blockquote class="bq1 stable" style="overflow:auto">
						
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1 dataTable">
								<thead>
									<tr>
										<th align="center" colspan="5" style="background:#136BCA; color:#ffffff; border-color:#136BCA">SOR View - Township</th>
									</tr>
									<tr>
										<th valign="middle">Code</th>
										<th valign="middle">Description of Item</th>
										<th valign="middle">Unit</th>
										<th valign="middle" nowrap="nowrap">Township (&#8377;)</th>
									</tr>
								</thead>
								<tbody>
								<?php echo buildCategory(0, $category, $conn); ?>
								</tbody>
							</table>
							<div align="center"><input type="submit" class="btn btn-info" name="btn_export" id="btn_export" value="Export" /></div>
							<div align="center">&nbsp;</div>
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
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
});
</script>