<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Periodical Rate Update';
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
	$POSTItemIdArr			=  $_POST['txt_item_id'];
	$POSTItemRateArr		=  $_POST['txt_item_rate'];
	$POSTPrevItemRateArr	=  $_POST['hid_item_rate'];
	$POSTWefDate 			=  dt_format($_POST['txt_wef_date']);
	
	$SelectQuery1 			= "select distinct valid_from from item_master where valid_from != '0000-00-00'";
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
		$InsertQuery1 	= "insert into pu_master set rate_wef = '$ExistWefDate', rate_valid_upto = '$ValidUptoDate', is_rate_changed = 'Y', updated_by = '$staffid', updated_on = NOW()";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}else{
		$InsertQuery1 	= "update pu_master set rate_wef = '$ExistWefDate', rate_valid_upto = '$ValidUptoDate', is_rate_changed = 'Y', updated_by = '$staffid', updated_on = NOW() where puid = '$MaxPuId'";
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
	}
	
	
	$DeleteQuery 		=  "TRUNCATE TABLE item_master_temp";
	$DeleteSql 			=  mysqli_query($dbConn,$DeleteQuery);
	$InsertQuery 		=  "INSERT INTO item_master_temp SELECT * FROM item_master";
	$InsertSql 			=  mysqli_query($dbConn,$InsertQuery);
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
			$UpdateQuery 		= "update item_master_temp set price = '$POSTItemRate', valid_from = '$POSTWefDate', is_edited = '$IsEdited' where item_id = '$Value'";
			$UpdateSql 			= mysqli_query($dbConn,$UpdateQuery);
			$UpdateCnt++;
		}
		$UpdateQuery2 			= "update item_master_temp set valid_from = '$POSTWefDate' where par_id = 0";
		$UpdateSql2 			= mysqli_query($dbConn,$UpdateQuery2);
		if(count($POSTItemIdArr) == $UpdateCnt){
			$msg = "Item Rate Updated Successfully";
		}
	
	}
}
$SelectQuery = "select a.*, b.price as irate, b.valid_from as wef_date, b.is_edited from item_master a left join item_master_temp b on (a.item_id = b.item_id) where a.par_id != 0 and a.item_code != '' order by a.item_code asc"; 
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
								<div class="div8 lboxlabel">( <font color="#DE0122">*</font> ) Modified</div>
								<div class="div2" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<td colspan="3" class="cboxlabel fhead">Item Rate Master With Effects From </td>
												<td colspan="2" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass datapicker" value="<?php //echo $List->price; ?>" required></td>
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
										<?php $SNo = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<?php
												$IsEditedItem = $List->is_edited;
												if($IsEditedItem == "Y"){
													$RowStyle = '';//'style="background:#0D3B79;"';
													$Cls = "overlay";
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
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass itemId" value="<?php echo $List->item_id; ?>">
													<input type="text" name="txt_item_rate[]" id="txt_item_rate" class="tboxclass itemRate" value="<?php echo $ItemRate; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" autocomplete="off">
													<input type="hidden" name="hid_item_rate[]" id="hid_item_rate" class="tboxclass itemRate" value="<?php echo $List->price; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" autocomplete="off">
													<input type="hidden" name="txt_valid_date" id="txt_valid_date" value="<?php if(($WithEffectFrom != "0000-00-00")&&(!empty($WithEffectFrom))){ echo dt_display($WithEffectFrom); } ?>">
												</td>
												<td class="cboxlabel"><?php echo $List->unit; ?></td>
											</tr>
										<?php $SNo++; } } ?>	
										</tbody>
									</table>
								</div>
								<div class="div12" align="center">
									<a data-url="Home" class="btn btn-info">Back</a>
									<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
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
			BootstrapDialog.alert("Error : Item Rate field should not be empty.");
			event.preventDefault();
			event.returnValue = false;
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
		rgba(51, 170, 51, .3);
	}
	.overlay td{
		color:#fff;
		border:1px solid #657382 !important;
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
		background-color: rgba(87, 90, 97, 0.8);
		-moz-opacity: 1;
		opacity: 1;
		-webkit-transform: translate3d(0,0,0);
		-moz-transform: translate3d(0,0,0);
		-o-transform: translate3d(0,0,0);
		-ms-transform: translate3d(0,0,0);
		transform: translate3d(0,0,0);
		color:#fff;
		box-shadow: 0px 1px 2px -2px #333;
	}
</style>
