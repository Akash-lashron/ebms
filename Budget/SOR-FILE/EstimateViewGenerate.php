<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$GlobGr1Id = 2; $GlobGr2Id = 3;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0;
$staffid = $_SESSION['sid'];
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
        <form action="EstimateView.php" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="div2" align="center">
									&nbsp;
								</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Estimate View - Generate</div>
										<div class="row innerdiv" align="center">
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div3 cboxlabel">Name of Work</div>
												<div class="div8">
													<select name="cmb_work_name" id="cmb_work_name" class="tboxsmclass" style="width:100%;">
														<option value=""> ------------------- Select ----------------</option>
														<?php echo $objBind->BindEstimateName(0); ?>
													</select>
												</div>
												<div class="div1 cboxlabel">&nbsp;</div>
											</div>
											<div class="row clearrow">&nbsp;</div>
											<div class="row" align="center">
												<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value=" View ">
											</div>
										</div>
									</div>
								</div>
								<div class="div2" align="center">
									
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
	$("#cmb_work_name").chosen();
	$("#btn_view").click(function(event){ 
		var WorkName 		= $("#cmb_work_name").val(); 
		if(WorkName == ""){ 
			BootstrapDialog.alert("Please Select Name of Work.");
			event.preventDefault();
			event.returnValue = false;
		}
	});
});

</script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>