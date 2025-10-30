<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator';
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
										<div class="face face1 face-card-1">
											<div class="box-content">
												<i class="fa fa-group UserBox" style="font-size:7em"></i>
												<h3 class="box-head user-box-head">User Management</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="EngineerList" class="div11">Staff Creation<br/></a>
												<a data-url="UserList" class="div11">User Creation</a>
												<a data-url="LogList" class="div11">User Monitor</a>
												<!-- <a data-url="EICAssignStaff" class="div11">EIC Creation</a> -->
												<!--<a data-url="EMDEntry" class="div11">EMD Entry</a>
												<a data-url="EMDRegister" class="div11">EMD Register</a>--> 
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>
								
								<!--<div class="div3">
									<div class="card">
										<div class="face face1 face-card-2">
											<div class="box-content">
												<i class="fa fa-list ItemBox" style="font-size:7em"></i>
												<h3 class="box-head item-box-head">BE & RE</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="BEProposeApprove" class="div11">BE Proposal & Approval<br/></a>
												<a data-url="REProposeApprove" class="div11">RE Proposal & Approval<br/></a>
												<a data-url="HoaBudgetValue" class="div11">HOA BE & RE<br/></a>
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>-->
								
								<div class="div4">
									<div class="card">
										<div class="face face1 face-card-3">
											<div class="box-content">
												<i class="fa fa-cogs RateBox" style="font-size:7em"></i>
												<h3 class="box-head rate-box-head">Master</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="ObjHeadMaster" class="div11">Object Head </a>
												<a data-url="HoaBudgetValue" class="div11">HOA BE & RE<br/></a>
												<!-- <a data-url="Hoa" class="div11">Head of Account </a> -->
												<!--<a data-url="HoaNew" class="div11">Head of Account </a>-->
												<!--<a data-url="Bidders" class="div11">Bidders </a>-->
												<!--<a data-url="UploadWorks" class="div11">Upload Works </a>-->
												<a data-url="ViewBudgetWorks" class="div11">View Budget Works </a>
												<div class="div12">&nbsp;</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="div4">
									<div class="card">
										<div class="face face1 face-card-4">
											<div class="box-content">
												<i class="fa fa-plus-square TaxBox" style="font-size:7em"></i>
												<h3 class="box-head tax-box-head">TS, NIT <!--LOI,--> & Work Order</h3>
											</div>
										</div>
										<div class="face face2">
											<div class="box-content">
												<a data-url="TechnicalSanction" class="div11">Technical Sanction</a>
												<a data-url="NIT" class="div11">NIT</a>
												<!--<a data-url="LOI" class="div11">LOI</a>-->
												<a data-url="WorkOrder" class="div11">Work Order</a>
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
