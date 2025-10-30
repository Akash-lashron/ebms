<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
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
	$POSTItemIdArr		=	$_POST['txt_item_id'];
	$POSTItemRateArr	=	$_POST['txt_item_rate'];
	$POSTWefDate 		= 	dt_format($_POST['txt_wef_date']);
	if(count($POSTItemIdArr)>0){
		foreach($POSTItemIdArr as $Key => $Value){
			$POSTItemRate 	= $POSTItemRateArr[$Key];
			$UpdateQuery 	= "update item_master_hc set price = '$POSTItemRate', valid_from = '$POSTWefDate' where item_id = '$Value'";
			$UpdateSql 		= mysqli_query($dbConn,$UpdateQuery);
		}
	}
}
$SelectQuery = "select * from item_master_hc where par_id != 0 order by item_desc asc"; 
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
								<div class="div12" align="center">&nbsp;</div>
							</div>
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<th colspan="2" class="cboxlabel fhead">Horticulture Item Rate With Effects From </th>
												<th colspan="2" class="fhead"><input type="text" name="txt_wef_date" id="txt_wef_date" class="tboxclass datapicker" disabled="disabled" value="<?php //echo $List->price; ?>"></th>
											</tr>
											<tr>
												<th nowrap="nowrap" width="2%">Item Code</th>
												<th width="60%">Item Description</th>
												<th>Item Rate &#8377</th>
												<th>Item Unit</th>
											</tr>
										</thead>
										<tbody>
										<?php if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<tr>
												<td nowrap="nowrap" class="cboxlabel">
													<?php echo $List->item_code; ?>
												</td>
												<td width="60%" class="lboxlabel"><?php echo $List->item_desc; ?></td>
												<td class="cboxlabel">
													<?php echo $List->price; ?>
													<input type="hidden" name="txt_item_id[]" id="txt_item_id" class="tboxclass" value="<?php echo $List->item_id; ?>">
												</td>
												<td class="cboxlabel"><?php echo $List->unit; ?></td>
											</tr>
										<?php $WithEffectFrom = $List->valid_from; } } ?>	
										</tbody>
									</table>
								</div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div12" align="center">
									<input type="hidden" name="txt_valid_date" id="txt_valid_date" value="<?php if($WithEffectFrom != "0000-00-00"){ echo dt_display($WithEffectFrom); } ?>">
								</div>
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
});
</script>
