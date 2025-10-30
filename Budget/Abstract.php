<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Abstract';
$msg = ""; $del = 0;
$RowCount =0;
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
							
								<div class="div4">
									<div class="card">
										<div class="face face1 face-card-2">
											<div class="box-content">
												<i class="fa fa-industry ItemBox" style="font-size:7em"></i>
												<h3 class="box-head item-box-head">Plant Site Abstract</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="SORViewPlantSite" class="div11">View Abstract</a>
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="div4">
									<div class="card">
										<div class="face face1 face-card-3">
											<div class="box-content">
												<i class="fa fa-road RateBox" style="font-size:7em"></i>
												<h3 class="box-head rate-box-head">Township Abstract</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="SORViewTownShip" class="div11">View Abstract</a>
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="div4">
									<div class="card">
										<div class="face face1 face-card-4">
											<div class="box-content">
												<i class="fa fa-random TaxBox" style="font-size:7em"></i>
												<h3 class="box-head tax-box-head">Plant Site & Township Abstract</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="SORViewAll" class="div11">View Abstract</a>
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
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
