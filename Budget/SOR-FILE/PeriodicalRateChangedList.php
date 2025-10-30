<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Home'.$PTIcon.'Periodical Rate Updated List';
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

/*if(isset($_POST['btn_save']) == " Save "){
	$POSTItemIdArr			=  $_POST['txt_item_id'];
	$POSTItemRateArr		=  $_POST['txt_item_rate'];
	$POSTPrevItemRateArr	=  $_POST['hid_item_rate'];
	$POSTWefDate 			=  dt_format($_POST['txt_wef_date']);
	$DeleteQuery 			=  "TRUNCATE TABLE item_master_temp";
	$DeleteSql 				=  mysqli_query($dbConn,$DeleteQuery);
	$InsertQuery 			=  "INSERT INTO item_master_temp SELECT * FROM item_master";
	$InsertSql 				=  mysqli_query($dbConn,$InsertQuery);
	if(count($POSTItemIdArr)>0){
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
		}
		$UpdateQuery2 			= "update item_master_temp set valid_from = '$POSTWefDate' where par_id = 0";
		$UpdateSql2 			= mysqli_query($dbConn,$UpdateQuery2);
	
	}
}*/
//$SelectQuery = "select a.*, b.price as irate, b.valid_from as wef_date, b.is_edited from item_master a left join item_master_temp b on (a.item_id = b.item_id) where a.par_id != 0 order by a.item_code asc"; 
$SelectQuery = "select a.*, b.price as orig_rate from item_master_temp a inner join item_master b on (a.item_id = b.item_id) where a.is_edited = 'Y' order by a.item_code asc"; 
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
        <form action="PeriodicalRateConfirmAccess.php" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<!--<div class="row">
								<div class="div12" align="center">&nbsp;</div>
							</div>-->
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<td colspan="3" class="cboxlabel fhead">Updated Item List With Effects From </td>
												<td colspan="2" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass datapicker" value="<?php //echo $List->price; ?>" required></td>
											</tr>
											<tr>
												<th nowrap="nowrap">S No.</th>
												<th nowrap="nowrap" width="2%">Item Code</th>
												<th width="60%">Item Description</th>
												<th nowrap="nowrap">Item Rate</th>
												<th nowrap="nowrap">Item Unit</th>
											</tr>
										</thead>
										<tbody>
										<?php $SNo = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<tr>
												<td nowrap="nowrap" class="cboxlabel"><?php echo $SNo; ?></td>
												<td nowrap="nowrap" class="cboxlabel">
													<?php echo $List->item_code; ?>
												</td>
												<td width="60%" class="lboxlabel"><?php echo $List->item_desc; ?></td>
												<td class="cboxlabel">
													<?php
													/*if($List->irate == ""){
														$ItemRate = $List->price;
													}else{
														$ItemRate = $List->price;
													}*/
													if(($List->wef_date == "0000-00-00")||(is_null($List->wef_date))){
														$WithEffectFrom = $List->valid_from;
													}else{
														$WithEffectFrom = $List->valid_from;
													}
													?>
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass itemId" value="<?php echo $List->item_id; ?>">
													<input type="text" name="txt_item_rate[]" id="txt_item_rate" class="tboxclass itemRate" value="<?php echo $List->price; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" autocomplete="off">
													<input type="hidden" name="hid_item_rate[]" id="hid_item_rate" class="tboxclass itemRate" value="<?php echo $List->orig_rate; ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);" maxlength="11" autocomplete="off">
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
									<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Next ">
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
$(document).ready(function(){
	/*var WithEffDate = $("#txt_valid_date").val();
	$("#txt_wef_date").val(WithEffDate);*/
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
			BootstrapDialog.alert("Error : Please enter rate with effect from field.");
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
</style>
