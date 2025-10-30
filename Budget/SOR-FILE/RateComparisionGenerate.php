<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Abstract Comparision';
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
        <form action="RateComparision.php" method="post" enctype="multipart/form-data" name="form" id="form1">
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
										<div class="row divhead head-sb" align="center">Rate comparision with previous years rate<!-- - Horticulture--></div>
										<div class="row innerdiv group-div" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div6 lgboxlabel">Current Year (With Effects From)</div>
												<div class="div6 bbox">
													<select class="selectlgbox" name="cmb_curr_year" id="cmb_curr_year">
														<?php echo $objBind->BindCurrentYear(''); ?>
													</select>
												</div>
											</div>
											<div class="row clearrow">&nbsp;</div>
											
											<div class="row">
												<div class="div6 lgboxlabel">Previous Year (With Effects From)</div>
												<div class="div6 bbox">
													<select class="selectlgbox" name="cmb_prev_year" id="cmb_prev_year">
														<option value=""> --------- Select Previous Year --------- </option>
														<?php echo $objBind->BindPreviousYear(''); ?>
													</select>
													<input type="hidden" name="txt_prev_year" id="txt_prev_year">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row" align="center">
												<a data-url="Administrator" class="btn btn-info">Back</a>
												<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" View ">
											</div>
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
	$("#cmb_curr_year").chosen();
	$("#cmb_prev_year").chosen();
	/*$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
	
	});*/
	$('body').on("change","#cmb_prev_year", function(event){  
		$("#txt_prev_year").val('');
		var PrevYear = $('option:selected', this).attr('data-wefdate');
		$("#txt_prev_year").val(PrevYear);
	});
	$('body').on("click","#btn_save", function(event){ 
		var CurrYear = $("#cmb_curr_year").val();
		var PrevYear = $("#cmb_prev_year").val();
		if(CurrYear == ""){
			BootstrapDialog.alert("Error : Please select current year.");
			event.preventDefault();
			event.returnValue = false;
		}else if(PrevYear == ""){
			BootstrapDialog.alert("Error : Please select previous year.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});
</script>
