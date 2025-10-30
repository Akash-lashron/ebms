<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Periodical Rate Confirm';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}

if(isset($_POST['btn_save']) == " Confirm "){ 
	$POSTItemIdArr		= $_POST['txt_item_id'];
	$POSTItemRateArr	= $_POST['txt_item_rate'];
	$POSTWefDate 		= dt_format($_POST['txt_wef_date']);
	
	$SelectQuery1 		= "select distinct valid_from from item_master where valid_from != '0000-00-00' and par_id != 0";
	$SelectSql1 		= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 		= mysqli_fetch_object($SelectSql1);
			$ExistWefDate = $List1->valid_from;
		}
	}
	
	$SelectQuery2 		= "select distinct valid_from from item_master_temp where valid_from != '0000-00-00' and par_id != 0";
	$SelectSql2 		= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 		 = mysqli_fetch_object($SelectSql2);
			$CurrWefDate = $List2->valid_from;
			$ValidUptoDate = date('Y-m-d', strtotime('-1 day', strtotime($CurrWefDate)));
		}
	}
	
	$InsertQuery1 	= "insert into pru_master set with_effect_from = '$ExistWefDate', valid_upto = '$ValidUptoDate', confirmed_by = '$staffid', confirmed_on = NOW()";
	$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	$MasterPruId 	= mysqli_insert_id($dbConn);
	include "PrevSORUpdate.php";
	
	$SelectQuery3 	= "select * from item_master where active = 1";
	$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
	if($SelectSql3 == true){
		if(mysqli_num_rows($SelectSql3)>0){
			while($List3 = mysqli_fetch_object($SelectSql3)){
				$ItemDesc 	 	= mysqli_real_escape_string($dbConn,$List3->item_desc);
				$Description 	= mysqli_real_escape_string($dbConn,$List3->description);
				$InsertQuery3 	= "insert into pru_detail set pruid = '$MasterPruId', item_id = '$List3->item_id', item_id_1 = '$List3->item_id_1', item_code = '$List3->item_code', item_desc = '$ItemDesc', description = '$Description', par_id = '$List3->par_id', item_type = '$List3->item_type', unit = '$List3->unit', price = '$List3->price', valid_from = '$List3->valid_from', valid_to = '$List3->valid_to', active = 1";
				$InsertSql3 	= mysqli_query($dbConn,$InsertQuery3);
			}
		}
	}
	if(count($POSTItemIdArr)>0){
		foreach($POSTItemIdArr as $Key => $Value){
			$POSTItemRate 	= $POSTItemRateArr[$Key];
			$UpdateQuery 	= "update item_master set price = '$POSTItemRate', valid_from = '$POSTWefDate' where item_id = '$Value'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
		}
		$UpdateQuery2 		= "update item_master set valid_from = '$POSTWefDate' where par_id = 0";
		$UpdateSql2 		= mysqli_query($dbConn,$UpdateQuery2);
		$DeleteQuery 		=  "TRUNCATE TABLE item_master_temp";
		$DeleteSql 			=  mysqli_query($dbConn,$DeleteQuery);
	}
}
//$SelectQuery = "select a.*, b.price as irate, b.valid_from as wef_date from item_master a left join item_master_temp b on (a.item_id = b.item_id) where a.par_id != 0 order by a.item_code asc"; 
$SelectQuery = "select * from item_master_temp where par_id != 0 and item_code != '' order by item_code asc"; 
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$RowCount = 1;
	}
}
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
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8 lboxlabel">( <font color="#DE0122">*</font> ) <span id="ModCnt" style="color:#F0155E"></span> Modified</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<td colspan="3" class="cboxlabel fhead">Item Rate Master With Effects From </td>
												<td colspan="2" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass disable" value="<?php //echo $List->price; ?>" readonly="" required></td>
											</tr>
											<tr>
												<th nowrap="nowrap">S No.</th>
												<th nowrap="nowrap" width="2%">Item Code</th>
												<th width="60%">Item Description</th>
												<th nowrap="nowrap">Item Rate ( &#x20B9 )</th>
												<th nowrap="nowrap">Item Unit</th>
											</tr>
										</thead>
										<tbody>
										<?php $SNo = 1; $ModCnt = 0; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<?php
												$IsEditedItem = $List->is_edited;
												if($IsEditedItem == "Y"){
													$RowStyle = '';//'style="background:#FCD6D6;"';
													$Cls = "overlay";
													$ModCnt++;
												}else{
													$RowStyle = "";
													$Cls = "";
												}
											?>
											<tr <?php echo $RowStyle; ?> class="<?php echo $Cls; ?>">
												<td nowrap="nowrap" class="cboxlabel"><?php echo $SNo; ?></td>
												<td nowrap="nowrap" class="cboxlabel">
													<?php echo $List->item_code; if($IsEditedItem == "Y"){ ?><sup class="supClass">*</sup> <?php } ?>
												</td>
												<td align="justify" width="60%" class="lboxlabel"><?php echo $List->item_desc; ?></td>
												<td class="cboxlabel">
													<?php
													if($List->irate == ""){
														$ItemRate = $List->price;
													}else{
														$ItemRate = $List->irate;
													}
													if(($List->wef_date == "0000-00-00")||(is_null($List->wef_date))){
														$WithEffectFrom = $List->valid_from;
													}else{
														$WithEffectFrom = $List->wef_date;
													}
													?>
													<input type="text" name="txt_item_rate[]" id="txt_item_rate" class="tboxclass itemRate disable" value="<?php echo $ItemRate; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" readonly="">
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass" value="<?php echo $List->item_id; ?>">
												</td>
												<td class="cboxlabel"><?php echo $List->unit; ?></td>
											</tr>
										<?php $SNo++; } }else{ ?>
											<tr>
												<td colspan="5" align="center" style="height:35px; font-size:12px !important; font-weight:bold;color:#F0155E;">No modifications in the Item Rate</td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div>
								<div class="div12" align="center">
									<a data-url="PeriodicalRateConfirmPart1" class="btn btn-info">Back</a>
									<a data-url="PeriodicalRateConfirm" class="btn btn-info">Confirm & Next</a>
									<!--<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Confirm ">-->
									<input type="hidden" name="txt_mod_cnt" id="txt_mod_cnt" value="<?php echo $ModCnt; ?>">
									<input type="hidden" name="txt_valid_date" id="txt_valid_date" value="<?php if(($WithEffectFrom != "0000-00-00")&&(!empty($WithEffectFrom))){ echo dt_display($WithEffectFrom); } ?>">
								</div>
								<div class="div2" align="center">&nbsp;</div>
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
<script>
$(document).ready(function(){
	var WithEffDate = $("#txt_valid_date").val();
	$("#txt_wef_date").val(WithEffDate);
	$("#txt_wef_date").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
    });
	var ModCnt = $("#txt_mod_cnt").val();
	if((ModCnt != '')&&(ModCnt > 0)){
		if(ModCnt == 1){
			$("#ModCnt").text(ModCnt+" Item ");
		}else{
			$("#ModCnt").text(ModCnt+" Items ");
		}
	}
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
	$('body').on("keyup",".itemRate", function(event){ 
		var iRate = $(this).val();
		if(iRate == ""){
			$(this).css("background-color", "#E8506E");
		}else{
			$(this).css("background-color", "#FFFFFF");
		}
	});
	var KillEvent = 0;
	$('body').on("click","#btn_save", function(event){ 
		if(KillEvent == 0){
			var WithEffectFromDate = $("#txt_wef_date").val();
			var iRateErrCnt = 0;
			$(".itemRate").each(function() {
				var iRate = $(this).val();
				if(iRate == ""){
					iRateErrCnt++;
					$(this).css("background-color", "#E8506E");
				}else{
					$(this).css("background-color", "#FFFFFF");
				}
			}); 
			if(WithEffectFromDate == ""){
				BootstrapDialog.alert("Error : Please enter rate with effect from field.");
				event.preventDefault();
				event.returnValue = false;
			}else if(iRateErrCnt > 0){
				BootstrapDialog.alert("Error : Item Rate field should not be empty.");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.show({
					title: 'Authentication',
					message: "Click below '<span>OTP Generate</span>' button to generate One Time Password (OTP)",
					closable: false,
					buttons: [{
						label: '&nbsp; Cancel &nbsp;',
						action: function(dialog) {
							dialog.close();
						}
					}, {
						label: '&nbsp; OTP Generate &nbsp;',
						action: function(dialog) {
							$.ajax({ 
								type: 'POST', 
								url: 'ajax/OTPGenerate.php', 
								data: { Page: 'PRCA' }, 
								success: function (data) {  
									if(data != 0){
										dialog.close();
										BootstrapDialog.show({
											message: '<div style="padding:20px 10px 40px 10px"><span style="float:left;">Enter Your One Time Password : &nbsp;</span> <span style="float:left; padding: 0px 5px;"><input type="text" class="form-control" style="width:150px; border:2px solid #171B20; border-radius:8px;"></span></div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 10px 10px">* Please check your email for OTP. </div><div style="color:#E81645; font-size:11px; font-weight:bold; padding:2px 10px 40px 10px">** If you click Cancel button again you need to generate OTP </div>',
											closable: false,
											buttons: [{
													label: '&nbsp; Cancel &nbsp;',
													action: function(dialogRef) {
														dialogRef.close();
													}
												}, {
												label: '&nbsp; Next &nbsp;',
												action: function(dialogRef) {
													var otp = dialogRef.getModalBody().find('input').val();
													if($.trim(otp) !== $.trim(data)) {
														BootstrapDialog.alert('Invalid OTP. Please try again !');
														dialogRef.close();
														return false;
													}else{
														KillEvent = 1;
														$("#btn_save").trigger( "click" );
														dialogRef.close();
													}
												}
											}]
										});
									}else{
										BootstrapDialog.alert('Sorry, OTP Not Generated please try again !');
									}
								}
							});
						}
					}]
				});
			}
		}
		
	});
});
</script>
<style>
	#txt_wef_date{
		border:2px solid #09090D;
	}
	.table > tbody > tr > td{
		padding:2px 6px;
	}
	.tboxclass{
		padding: 2px 5px;
	}
	.itemRate{
		text-align:right;
	}
	.overlay {
		/*-moz-opacity: 0;
		opacity: 0;*/
		/*rgba(51, 170, 51, .3) */
	}
	.overlay td{
		color:#fff;
		border:1px solid #D3D3D4 !important;
		-moz-opacity: 0;
		opacity: 0;
		-webkit-transition: opacity 0.35s, transform 0.35s;
		-moz-transition: opacity 0.35s, transform 0.35s;
		transition: opacity 0.35s, transform 0.35s;
		-webkit-transform: translate3d(50%,50%,0);
		-moz-transform: translate3d(50%,50%,0);
		-o-transform: translate3d(50%,50%,0);
		transform: translate3d(50%,50%,0);
		-ms-transform: translate3d(50%,50%,0);
		/*background-color: rgba(1, 23, 46, 0.8);*/
		/*background-color: rgba(87, 90, 97, 0.6);*/
		background-color: rgba(0, 80, 159, 0.6);
		-moz-opacity: 1;
		opacity: 1;
		-webkit-transform: translate3d(0,0,0);
		-moz-transform: translate3d(0,0,0);
		-o-transform: translate3d(0,0,0);
		-ms-transform: translate3d(0,0,0);
		transform: translate3d(0,0,0);
		color:#0E2B57;
		box-shadow: 0px 1px 1px -2px #D3D3D4;
	}
</style>
