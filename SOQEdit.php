<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/functions.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
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
if (isset($_POST["update"]) == " Update "){
    $PostSchId 		= trim($_POST['hid_sch_id']);
	$PostSheetId 	= trim($_POST['hid_sheetid']);
	$PostItemNo 	= trim($_POST['txt_item_no']);
	$PostItemDesc 	= trim($_POST['txt_item_desc']);
	$PostItemQty 	= trim($_POST['txt_item_qty']);
	$PostItemUnit 	= trim($_POST['txt_item_unit']);
	$PostItemRate 	= trim($_POST['txt_item_rate']);
	$PostItemType 	= trim($_POST['cmb_item_type']);
	$PostItemSubType= trim($_POST['cmb_item_sub_type']);
	$PostItemId 	= trim($_POST['hid_subdivid']);
	
	if($PostItemType == 'g'){ $PostItemType = ''; }
    $UpdateQuery 	= "UPDATE schdule set sno = '$PostItemNo', description = '$PostItemDesc', total_quantity = '$PostItemQty', rate = '$PostItemRate', per = '$PostItemUnit', measure_type = '$PostItemType', sub_type = '$PostItemSubType' where sch_id = '$PostSchId'";
    $UpdateSql 		= mysql_query($UpdateQuery);
    $UpdateQuery2 	= "UPDATE subdivision set subdiv_name = '$PostItemNo' where subdiv_id = '$PostItemId'";
    $UpdateSql2 	= mysql_query($UpdateQuery2);
	if($UpdateSql == true){
        $msg = "Item Details Updated Successfully ";
		$success = 1;
    }else{
		$msg = "Item Details Not Updated. Error...!!! ";
	}
} 
$EditView = 0; $MCount = 0;
if($_GET['id'] != ""){
	$SchId 		= $_GET['id'];
	$hash 		= $_GET['hash'];
	$salt 		= "Dae@Nrb@Frfcf@Ebms-Lashron@Kalpakkam";
	$hashed 	= md5($salt.$SchId);
	if($hash === $hashed){
		$SelectQuery1 	=  "select * from schdule WHERE sch_id = '$SchId'";
		$SelectSql1 	=  mysql_query($SelectQuery1);
		if($SelectSql1 == true){
			$List1 		= 	mysql_fetch_object($SelectSql1);
			$SheetId 	= 	$List1->sheet_id;
			$ItemNo 	= 	$List1->sno;
			$ItemDesc 	= 	$List1->description; 
			$ItemQty 	= 	$List1->total_quantity; 
			$ItemUnit 	= 	$List1->per;
			$ItemRate 	= 	$List1->rate;
			$ItemType 	= 	$List1->measure_type;
			$ItemSubType= 	$List1->sub_type;
			$ItemId 	= 	$List1->subdiv_id;
			if($ItemType == ''){ $ItemType = 'g'; }
			$SelectQuery2 	=  "select * from sheet WHERE sheet_id = '$SheetId'";
			$SelectSql2 	=  mysql_query($SelectQuery2);
			if($SelectSql2 == true){
				$List2 		= 	mysql_fetch_object($SelectSql2);
				$WorkName 	= 	$List2->work_name;
				$ShortName 	= 	$List2->short_name;
			}
			$SelectQuery3 	=  "select mbheaderid from mbookheader WHERE sheetid = '$SheetId' and subdivid = '$ItemId' limit 1";
			$SelectSql3 	=  mysql_query($SelectQuery3);
			if($SelectSql3 == true){
				$MList 		=  mysql_fetch_object($SelectSql3);
				$MCount 	=  $MList->mbheaderid;
			}
		}
		$EditView = 1;
	}
}
?>

<?php require_once "Header.html"; ?>
<script>
	function goBack(){
		url = "ViewAgreementSheet.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.chosen-container-single .chosen-single{
	height:30px !important;
	line-height: 25px;
}
.inputGroup label::after {
    width: 10px;
    height: 12px;
	top: 49%;
	right:20px;
}
.chosen-container{
	/*width:99% !important;*/
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">SOQ Item Update</div>
                <div class="container_12">
                    <div class="grid_12">

						<div align="right">&nbsp;&nbsp;&nbsp;</div>
                        <blockquote class="bq1" style="overflow:auto">
							
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">SOQ Item Details</div>
										<div class="row innerdiv group-div" align="center">
										
											<div class="row">
												<?php if($EditView == 1){ ?>
												<div class="div2 lboxlabel" style="line-height:35px;">Name of Work</div>
												<div class="div10">
												<textarea name='workname' class="divtarea" id='workname' readonly="readonly" required rows="2"><?php if($_GET['id'] != ''){ echo $WorkName; } ?></textarea>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Short Name</div>
												<div class="div10">
												<input type="text" class="divtbox" name='shortname' readonly="readonly" required id='shortname' value="<?php if($_GET['id'] != ''){ echo $ShortName; } ?>">
												</div>
												
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Item No.</div>
												<div class="div4">
													<input type="text" class="divtbox" name='txt_item_no' required  id='txt_item_no' value="<?php if($_GET['id'] != ''){ echo $ItemNo; } ?>">
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Description</div>
												<div class="div10">
													<textarea name='txt_item_desc' class="divtarea" id='txt_item_desc' required rows="2"><?php if($_GET['id'] != ''){ echo $ItemDesc; } ?></textarea>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Item Qty.</div>
												<div class="div4">
													<input type="text" class="divtbox" name='txt_item_qty' required  id='txt_item_qty' value="<?php if($_GET['id'] != ''){ echo $ItemQty; } ?>">
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Item Unit</div>
												<div class="div4">
													<input type="text" class="divtbox" name='txt_item_unit' required  id='txt_item_unit' value="<?php if($_GET['id'] != ''){ echo $ItemUnit; } ?>">
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Item Rate</div>
												<div class="div4">
													<input type="text" class="divtbox" name='txt_item_rate' required  id='txt_item_rate' value="<?php if($_GET['id'] != ''){ echo $ItemRate; } ?>">
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Item Type</div>
												<div class="div4">
													
													<select class="divtbox" name='cmb_item_type' id='cmb_item_type' required>
														<option value="">-- Select --</option>
														<?php
														$ItemTypeArr = array("g"=>"General","s"=>"Steel","st"=>"Structural Steel");
														foreach($ItemTypeArr as $Key => $Value){
															if($Key == $ItemType){
																$Sel = 'selected="selected"';
															}else{
																$Sel = '';
															}
															echo '<option value="'.$Key.'" '.$Sel.'>'.$Value.'</option>';
														}
														?>
													</select>
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<div class="div12 grid-empty"></div>
												<div class="div2 lboxlabel">Sub Type</div>
												<div class="div4">
													
													<select class="divtbox" name='cmb_item_sub_type' id='cmb_item_sub_type'>
														<option value="">None</option>
														<option value="c" <?php if($ItemSubType == 'c'){ ?>selected="selected" <?php } ?>>Coupler</option>
													</select>
												</div>
												<div class="div6 lboxlabel">&nbsp;</div>
												<?php } ?>
												<div class="div12 grid-empty"></div>
												
												
											</div>
										</div>
									</div>
								</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
						
							<div style="text-align:center;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<?php if($EditView == 1){ if($MCount == 0){ ?>
								<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['id'] != ''){ echo $SheetId; } ?>">
								<input type="hidden" name="hid_sch_id" id="hid_sch_id" value="<?php if($_GET['id'] != ''){ echo $_GET['id']; } ?>">
								<input type="hidden" name="hid_subdivid" id="hid_subdivid" value="<?php if($_GET['id'] != ''){ echo $ItemId; } ?>">
								<div class="buttonsection">
									<input type="submit" name="update" id="update" value=" Update " class="save"/>
								</div>
								<?php }else{ ?>
									<div class="div12 cboxlabel" style="color:red">
										<i class="fa fa-times-circle" style="font-size:18px;color:red"></i> Unable to edit. Measurements already uploaded.
									</div>
								<?php } } ?>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
		   <script>
				$("#cmb_item_type").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
