<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';

function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$RowCount = 0;
if(isset($_POST["next"]) == " Next "){
	$SheetId 		= $_POST['cmb_shortname'];
}

if(isset($_POST["save"]) == " SAVE "){
	$SheetId 		= $_POST['txt_sheetid'];
	$IsEscArr 		= $_POST['ch_is_esc'];
	$MatTypeArr 	= $_POST['cmb_material_type'];
	$BaseRateArr 	= $_POST['txt_base_rate'];
	$CoEfficientArr = $_POST['txt_co_effient'];
	$ConvertToArr 	= $_POST['cmb_convert_to_unit'];
	$Execute = 0;
	
	if(count($IsEscArr)>0){
		foreach($IsEscArr as $key => $Value){
			$MatType 		= $MatTypeArr[$key];
			$BaseRate 		= $BaseRateArr[$key];
			$Coefficient 	= $CoEfficientArr[$key];
			$CovertTo 		= $ConvertToArr[$key];
			
			$SelectQuery1 	= "update schdule set mat_code = '$MatType', tc_unit = '$Coefficient', co_efficient = '$Coefficient', covert_to_unit = '$CovertTo', base_rate = '$BaseRate', escalation_flag = 'Y' where sch_id = '$Value'";
			$SelectSql1 	= mysql_query($SelectQuery1);
			if($SelectSql1 == true){
				$Execute++;
			}
		}
	}
	if($Execute > 0){
		$msg = "Escalation data updated successfully";
	}else{
		$msg = "Escalation data not updated. Please try again.";
	}
}
if(isset($SheetId) != ""){
	$SelectQuery1 	= "select * from schdule where sheet_id = '$SheetId' and sno != ''";
	$SelectSql1 	= mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1)>0){
			$RowCount = 1;
		}
	}
}
//echo $SelectQuery1;exit;
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript">
	window.history.forward();
	function noBack(){ window.history.forward(); }
	function goBack(){
	   	url = "EscalationSettingsGenerate.php";
		window.location.replace(url);
	}
</script>
<style>
.HideDesc{
	max-width : 508px; 
	white-space : nowrap;
	overflow : hidden;
	text-overflow: ellipsis;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Escalation Configuration</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="EscalationSettings.php">
                            <div class="container">
								<div class="row ">
									<div class="div12">
										<div class="row">
											<div class="row clearrow"></div>
											<table class="DispTable" width="100%">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Item Description</th>
														<th nowrap="nowrap">Material Type</th>
														<th>Item Base Rate <br/> ( &#8377; )</th>
														<th>Co-efficient <br/> ( in kg )</th>
														<th>Convert To</th>
														<th nowrap="nowrap">Is Escalation ?<br/> <input type="checkbox" name="ch_all" id="check_all"></th>
													</tr>
												</thead>
												<tbody>
												<?php if($RowCount == 1){ while($List = mysql_fetch_object($SelectSql1)){ 
													if(($List->total_quantity != 0)&&($List->rate != 0)){
														$Disp = 1;
													}else{
														$Disp = 0;
													}
												?>
													<tr>
														<td align="center" valign="middle" nowrap="nowrap"><?php echo $List->sno; ?></td>
														<td align="justify" class="HideDesc" valign="middle"><?php echo $List->description; ?></td>
														<td align="center">
															<?php if($Disp == 1){ ?>
															<select name="cmb_material_type[]" class="DispSelectBox ChosenMatType">
																<option value="">----- Select -----</option>
																<?php 
																if(($List->measure_type == 's')||($List->measure_type == 'S')){ 
																 	echo $objBind->BindEscMaterial($List->mat_code,'10CA','S'); 
																}else{ 
																	echo $objBind->BindEscMaterial($List->mat_code,'10CA','G'); 
																} 
																?>
																
															</select>
															<? } ?>
														</td>
														<td align="center" valign="middle"><?php if($Disp == 1){ ?><input type="text" name="txt_base_rate[]" class="textboxdisplay" value="<?php echo $List->base_rate; ?>" ><? } ?></td>
														<td align="center" valign="middle"><?php if($Disp == 1){ ?><input type="text" name="txt_co_effient[]" class="textboxdisplay" value="<?php echo $List->co_efficient; ?>" ><? } ?></td>
														<td align="center" valign="middle">
															<?php if($Disp == 1){ ?>
															<select name="cmb_convert_to_unit[]" id="cmb_convert_to_unit" class="DispSelectBox ChosenUnits">
																<option value="">--Sel--</option>
																<?php echo $objBind->BindAllUnits($List->covert_to_unit); ?>
															</select>
															<? } ?>
														</td>
														<td align="center" valign="middle"><?php if($Disp == 1){ ?><input type="checkbox" name="ch_is_esc[]" class="EscCheck" <?php if($List->escalation_flag == "Y"){ echo 'checked="checked"'; } ?> value="<?php echo $List->sch_id; ?>"><? } ?></td>
													</tr>
												<?php } } ?>
												</tbody>
											</table>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
								</div>
								<div class="row">
									<div class="div12" align="center">
										<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php if(isset($SheetId)){ echo $SheetId; } ?>"/>
										<input type="button" class="backbutton" name="back" id="back" value=" BACK " onClick="goBack();"/>
										<input type="submit" class="backbutton" name="save" id="save" value=" SAVE "/>
									</div>
								</div>  
								<div class="row">&nbsp;</div>                         
                            </div>
						</form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		$("#cmb_shortname").chosen();
		//$(".ChosenUnits").chosen();
		//$(".ChosenMatType").chosen();
		$(document).ready(function(){ 
			var msg = "<?php echo $msg; ?>";
			var success = "<?php echo $success; ?>";
			if(msg != ""){
				BootstrapDialog.show({
					title: 'Information',
					closable: false,
					message: msg,
					buttons: [{
						label: ' OK ',
						cssClass: 'btn-primary',
						action: function(dialogRef) {
							dialogRef.close();
						}
					}]
				});
			}
			$("#check_all").click(function(){
				$('.EscCheck').not(this).prop('checked', this.checked);
			});
			$("body").on("click","#next", function(event){
				var ShortName 	= $("#cmb_shortname").val();
				var WorkOrderNo = $("#txt_workorder").val();
				var WorkName 	= $("#txt_workname").val();
				if(ShortName == ""){
					BootstrapDialog.alert("Please select work short name");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkOrderNo == ""){
					BootstrapDialog.alert("Work order no. should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkName == ""){
					BootstrapDialog.alert("Name of work should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}
			});
		});
	</script>
    </body>
</html>

