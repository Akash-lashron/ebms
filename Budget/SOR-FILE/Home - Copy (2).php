<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName 	= $PTPart1.$PTIcon.'Home';
$msg 		= ""; $del = 0;
$RowCount 	= 0;
$staffid 	= $_SESSION['sid'];
$UnconfirmSor = 0; $UnconfirmRate = 0;
$SelectDatasheetQuery = "select count(ref_id) as UnconfirmSor from datasheet_master where (ds_release = '' OR ds_release IS NULL)";
$SelectDatasheetSql   = mysqli_query($dbConn,$SelectDatasheetQuery);
if($SelectDatasheetSql == true){
	if(mysqli_num_rows($SelectDatasheetSql)>0){
		$UCSList = mysqli_fetch_object($SelectDatasheetSql);
		$UnconfirmSor = $UCSList->UnconfirmSor;
	}
}
$SelectItemRateQuery = "select count(item_id) as UnconfirmRate from item_master_temp where is_edited = 'Y'";
$SelectItemRateSql   = mysqli_query($dbConn,$SelectItemRateQuery);
if($SelectItemRateSql == true){
	if(mysqli_num_rows($SelectItemRateSql)>0){
		$UCIList = mysqli_fetch_object($SelectItemRateSql);
		$UnconfirmRate = $UCIList->UnconfirmRate;
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
							<div class="box-container">
							
								<div class="div1"></div>
								<div class="div5">
									<div class="card">
										<div class="face face1 nBox1">
											<div class="box-content">
												<i class="fa fa-check-square-o ItemBox" style="font-size:6em"></i>
												<h3 class="box-head item-box-head">
													No. of item rates waiting for confirmation : <span class="notyCnt"><?php echo $UnconfirmRate; ?></span>
												</h3>
												<button type="button" class="notBtn" data-url="PeriodicalRateChangedList">Click here to view</button>
											</div>
										</div>
									</div>
								</div>
								
								<div class="div5">
									<div class="card">
										<div class="face face1 nBox2">
											<div class="box-content">
												<i class="fa fa-check-square-o ItemBox" style="font-size:6em"></i>
												<h3 class="box-head item-box-head">
													No. of SOR waiting for confirmation : <span class="notyCnt"><?php echo $UnconfirmSor; ?></span>
												</h3>
												<button type="button" class="notBtn" data-url="DataSheetConfirmWaitingList">Click here to view</button>
											</div>
										</div>
									</div>
								</div>
								<div class="div1"></div>
								
								
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
		<link rel="stylesheet" href="css/notyBox.css">
    </body>
</html>
<script>
$(document).ready(function(){
	$('.notBtn').click(function(event){ 
		var PageUrl = $(this).attr("data-url");
  		$(location).attr("href",PageUrl+".php");
		event.preventDefault();
		return false;
  	});
});
</script>
