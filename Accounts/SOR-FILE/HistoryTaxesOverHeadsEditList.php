<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'History'.$PTIcon.'Default Master';
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
if(isset($_POST['btn_save']) == " View "){
	$Dpuid = $_POST['cmb_history_year'];
	$SelectQuery = "select * from pdm_detail where puid = '$Dpuid' order by de_code asc, de_name asc"; 
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
	$RowCount 	 = 0;
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
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
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<td colspan="3" class="cboxlabel fhead">History Default Percentage Master With Effects From </td>
												<td colspan="1" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass datapicker" value="<?php //echo $List->price; ?>" required></td>
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
											<tr>
												<td nowrap="nowrap" class="cboxlabel"><?php echo $SNo; ?></td>
												<td nowrap="nowrap" class="cboxlabel">
													<?php echo $List->de_code; ?>
												</td>
												<td width="60%" class="lboxlabel"><?php echo $List->de_name; ?></td>
												<td class="cboxlabel">
													<?php echo $List->de_perc; ?>
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass" value="<?php echo $List->de_id; ?>">
												</td>
											</tr>
										<?php $WithEffectFrom = $List->valid_from; $SNo++; } } ?>	
										</tbody>
									</table>
								</div>
								<div class="div12" align="center">
									<a data-url="HistoryDefaultMasterGenerate" class="btn btn-info">Back</a>
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
			BootstrapDialog.alert("Error : Please percentage with effect from field.");
			event.preventDefault();
			event.returnValue = false;
		}else if(iRateErrCnt > 0){
			BootstrapDialog.alert("Error : Percentage field should not be empty.");
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
	.tboxclass{
		padding: 6px 5px;
	}
</style>
