<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require_once 'mail/PHPMailerAutoload.php';
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
if(isset($_POST['btn_save']) == " Save "){
	$POSTItemIdArr			= $_POST['txt_item_id'];
	$POSTItemRateArr		= $_POST['txt_item_rate'];
	$POSTPrevItemRateArr	= $_POST['hid_item_rate'];
	$POSTWefDate 			= dt_format($_POST['txt_wef_date']);
	$SelectQuery1 			= "select distinct valid_from from default_master where valid_from != '0000-00-00'";
	$SelectSql1 			= mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			$List1 		= mysqli_fetch_object($SelectSql1);
			$ExistWefDate = $List1->valid_from;
		}
	}
	$ValidUptoDate 		= date('Y-m-d', strtotime('-1 day', strtotime($POSTWefDate)));
	
	$MaxPuId = "";
	$SelectQuery2 		= "select puid from pu_master where is_confirmed != 'Y' and puid = (select max(a.puid) from pu_master a)";
	$SelectSql2 		= mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 		= mysqli_fetch_object($SelectSql2);
			$MaxPuId 	= $List2->puid;
		}
	}
	if($MaxPuId == ""){
		$InsertQuery1 	= "insert into pu_master set dm_wef = '$ExistWefDate', dm_valid_upto = '$ValidUptoDate', is_dm_changed = 'Y', updated_by = '$staffid', updated_on = NOW()";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}else{
		$InsertQuery1 	= "update pu_master set dm_wef = '$ExistWefDate', dm_valid_upto = '$ValidUptoDate', is_dm_changed = 'Y', updated_by = '$staffid', updated_on = NOW() where puid = '$MaxPuId'";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}
	
	$DeleteQuery = "TRUNCATE TABLE default_master_temp";
	$DeleteSql 	 = mysqli_query($dbConn,$DeleteQuery);
	
	$SelectQuery3 	= "select * from default_master";
	$SelectSql3 	= mysqli_query($dbConn,$SelectQuery3);
	if($SelectSql3 == true){
		if(mysqli_num_rows($SelectSql3)>0){
			while($List3 = mysqli_fetch_object($SelectSql3)){
				$DeName 	 	= mysqli_real_escape_string($dbConn,$List3->de_name);
				$InsertQuery3 	= "insert into default_master_temp set de_id = '$List3->de_id', de_name = '$DeName', de_perc = '$List3->de_perc', de_code = '$List3->de_code', valid_from = '$List3->valid_from'";
				$InsertSql3 	= mysqli_query($dbConn,$InsertQuery3);
			}
		}
	}
	
	if(count($POSTItemIdArr)>0){
		$UpdateCnt = 0;
		foreach($POSTItemIdArr as $Key => $Value){
			$POSTItemRate 		= $POSTItemRateArr[$Key];
			$POSTPrevItemRate 	= $POSTPrevItemRateArr[$Key];
			if($POSTItemRate == $POSTPrevItemRate){
				$IsEdited = "";
			}else{
				$IsEdited = "Y";
			}
			$UpdateQuery 	= "update default_master_temp set de_perc = '$POSTItemRate', is_edited = '$IsEdited', valid_from = '$POSTWefDate' where de_id = '$Value'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
			$UpdateCnt++;
		}
		if(count($POSTItemIdArr) == $UpdateCnt){
			$msg = "Taxes & Overheads Percentage Updated Successfully";
		}
	}
	//echo $SelectQuery2;exit;
}
$SelectQuery = "select a.*, b.de_perc as dperc, b.is_edited as changed, b.valid_from as wef_date from default_master a left join default_master_temp b on (a.de_id = b.de_id) order by a.de_name asc"; 
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
$RowCount 	 = 0;
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
								<div class="div8 lboxlabel">( <font color="#DE0122">*</font> ) Modified</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<td colspan="3" class="cboxlabel fhead">Taxes & Overheads With Effects From </td>
												<td colspan="1" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass disable" readonly="" value="<?php //echo $List->price; ?>" required></td>
											</tr>
											<tr>
												<th nowrap="nowrap">S No.</th>
												<th nowrap="nowrap" width="2%">Code</th>
												<th width="60%">Description</th>
												<th nowrap="nowrap">( % )</th>
											</tr>
										</thead>
										<tbody>
										<?php $SNo = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<?php
												$IsEditedItem = $List->changed;
												if($IsEditedItem == "Y"){
													$RowStyle = '';//'style="background:url(images/RowBg.jpg)"';
													$Cls = "overlay";
												}else{
													$RowStyle = "";
													$Cls = "";
												}
											?>
											<tr <?php echo $RowStyle; ?> class="<?php echo $Cls; ?>">
												<td nowrap="nowrap" class="cboxlabel"><?php echo $SNo; ?></td>
												<td nowrap="nowrap" class="cboxlabel">
													<?php echo $List->de_code; if($IsEditedItem == "Y"){ ?><sup class="supClass">*</sup> <?php } ?>
												</td>
												<td width="60%" class="lboxlabel"><?php echo $List->de_name; ?></td>
												<td class="cboxlabel">
													<?php
													if($List->dperc == ""){
														$DefPerc = $List->de_perc;
													}else{
														$DefPerc = $List->dperc;
													}
													if(($List->wef_date == "0000-00-00")||(is_null($List->wef_date))){
														$WithEffectFrom = $List->valid_from;
													}else{
														$WithEffectFrom = $List->wef_date;
													}
													?>
													<input type="text" name="txt_item_rate[]" id="txt_item_rate" class="tboxclass itemRate disable" value="<?php echo $DefPerc; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" style="text-align:center" autocomplete="off" readonly="">
													<input type="hidden" name="hid_item_rate[]" id="hid_item_rate" class="tboxclass" value="<?php echo $List->de_perc; ?>" style="text-align:center">
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass" value="<?php echo $List->de_id; ?>">
												</td>
											</tr>
										<?php $SNo++; } }else{ ?>
											<tr>
												<td colspan="4" align="center" style="height:35px; font-size:12px !important; font-weight:bold;color:#F0155E;">No modifications in the Taxes & Overheads</td>
											</tr>
										<?php } ?>	
										</tbody>
									</table>
								</div>
								<div class="div12" align="center">
									<a data-url="PeriodicalRateConfirmAccess" class="btn btn-info">Back</a>
									<a data-url="PeriodicalRateConfirmPart2" class="btn btn-info">Confirm & Next</a>
									<!--<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">-->
									<input type="hidden" name="txt_valid_date" id="txt_valid_date" value="<?php if($WithEffectFrom != "0000-00-00"){ echo dt_display($WithEffectFrom); } ?>">
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
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
$(document).ready(function(){
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
	var WithEffDate = $("#txt_valid_date").val();
	$("#txt_wef_date").val(WithEffDate);
	$("#txt_wef_date").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
    });
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
	
	$('body').on("click","#btn_save", function(event){
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
			BootstrapDialog.alert("Error : Please select date with effect from field.");
			event.preventDefault();
			event.returnValue = false;
		}else if(iRateErrCnt > 0){
			BootstrapDialog.alert("Error : Percentage field should not be empty.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
	var KillEvent = 0;
	/*$('body').on("click","#btn_save", function(event){ 
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
				BootstrapDialog.alert("Error : Please select date with effect from field.");
				event.preventDefault();
				event.returnValue = false;
			}else if(iRateErrCnt > 0){
				BootstrapDialog.alert("Error : Percentage field should not be empty.");
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
	});*/
});
</script>
<style>
	#txt_wef_date{
		border:2px solid #09090D;
	}
	.tboxclass{
		padding: 6px 5px;
	}
	.overlay {
		/*-moz-opacity: 0;
		opacity: 0;*/
		rgba(51, 170, 51, .3); 
	}
	.overlay td{
		color:#fff;
		/*border:1px solid #979BA0 !important;*/
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
		background-color: rgba(87, 90, 97, 0.2);
		-moz-opacity: 1;
		opacity: 1;
		-webkit-transform: translate3d(0,0,0);
		-moz-transform: translate3d(0,0,0);
		-o-transform: translate3d(0,0,0);
		-ms-transform: translate3d(0,0,0);
		transform: translate3d(0,0,0);
		color:#05216C;
		/*box-shadow: 0px 1px 2px -2px #979BA0;*/
	}
	.disable{
		background:#F4F5F7;
	}
</style>
