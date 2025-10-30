<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'History';
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
	$POSTItemIdArr		=  $_POST['txt_item_id'];
	$POSTItemRateArr	=  $_POST['txt_item_rate'];
	$POSTWefDate 		=  dt_format($_POST['txt_wef_date']);
	$DeleteQuery 		=  "TRUNCATE TABLE item_master_temp";
	$DeleteSql 			=  mysqli_query($dbConn,$DeleteQuery);
	$InsertQuery 		=  "INSERT INTO item_master_temp SELECT * FROM item_master";
	$InsertSql 			=  mysqli_query($dbConn,$InsertQuery);
	if(count($POSTItemIdArr)>0){
		foreach($POSTItemIdArr as $Key => $Value){
			$POSTItemRate 	= $POSTItemRateArr[$Key];
			$UpdateQuery 	= "update item_master_temp set price = '$POSTItemRate', valid_from = '$POSTWefDate' where item_id = '$Value'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
		}
		$UpdateQuery2 		= "update item_master_temp set valid_from = '$POSTWefDate' where par_id = 0";
		$UpdateSql2 		= mysqli_query($dbConn,$UpdateQuery2);
	
	}
}
$SelectQuery = "select a.*, b.price as irate, b.valid_from as wef_date from item_master a left join item_master_temp b on (a.item_id = b.item_id) where a.par_id != 0 order by a.item_code asc"; 
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
        <form action="HistoryScheduleRates.php" method="post" enctype="multipart/form-data" name="form" id="form1">
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
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-sb" align="center">Schedule of Rates - History<!-- - Horticulture--></div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div6 cboxlabel">Select Period (With Effects From)</div>
												<div class="div5 bbox">
													<select class="selectlgbox" name="cmb_history_year" id="cmb_history_year">
														<option value=""> -------- Select Period -------- </option>
														<?php echo $objBind->BindHistoryYear(); ?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row" align="center">
												<a data-url="History" class="btn btn-info">Back</a>
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" View ">
											</div>
											<div class="row clearrow"></div>
										</div>
									</div>
								</div>
								<div class="div3" align="center">&nbsp;</div>
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
	$("#cmb_history_year").chosen();
	$('body').on("click","#btn_save", function(event){ 
		var Period = $("#cmb_history_year").val();
		if(Period == ""){
			BootstrapDialog.alert("Error : Please select period.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});
</script>
