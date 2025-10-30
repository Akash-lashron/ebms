<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'User Management'.$PTIcon."User Monitor";
//checkUser();
$staffid 	 = $_SESSION['sid'];
$RowCount 	 = 0;
$SelectQuery = "select * from log_list order by logid asc";
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
										<div class="row divhead head-b" align="center">Log List - View</div>
										<div class="row" align="center">
											<div class="row">
												<table class="dataTable">
													<thead>
														<tr>
															<th>SNo.</th>
															<th>User Name</th>
															<th>Login Date</th>
															<th>Login Time</th>
															<th>Logout Date</th>
															<th>Logout Time</th>
															<th>IP Address</th>
														</tr>
													</thead>
													<tbody>
													<?php $Sno = 1; if($RowCount == 1) { while($List = mysqli_fetch_object($SelectSql)){ ?>
														<tr>
															<td align="center"><?php echo $Sno; ?></td>
															<td align="center"><?php echo $List->username; ?></td>
															<td align="center"><?php if(!empty($List->login_date)){ $Temp1 = strtotime($List->login_date); $LoginDate = date("d/m/Y", $Temp1); echo $LoginDate; } ?></td>
															<td align="center"><?php echo $List->login_time; ?></td>
															<td align="center"><?php if(!empty($List->logout_date)){ $Temp2 = strtotime($List->logout_date); $LogoutDate = date("d/m/Y", $Temp2); echo $LogoutDate; } ?></td>
															<td align="center"><?php echo $List->logout_time; ?></td>
															<td align="center"><?php echo $List->ip_address; ?></td>
														</tr>
													<?php $Sno++; } } ?>
													</tbody>
												</table>
											</div>
											<div class="row" align="center"><a data-url="Administrator" class="btn btn-primary">Back</a></div>
										</div>
									</div>
								</div>
								<div class="div2" align="center"></div>
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
	$('.dataTable').DataTable({"paging":true,"ordering": true});
});
</script>
