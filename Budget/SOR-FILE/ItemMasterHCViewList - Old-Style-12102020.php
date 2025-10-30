<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'Rate Master';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
$SelectQuery = "select * from item_master_hc where par_id = 0 order by item_desc asc"; 
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
$RowCount 	 = 0;
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
												<th colspan="4" class="fhead">Horticulture Item List</th>
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
												<td colspan="4" class="lboxlabel" style="background:#D8EFFA"><?php echo $List->item_code; ?> <?php echo $List->item_desc; ?></td>
											</tr>
										<?php 
											$ParId = $List->item_id;
											$SelectChildQuery = "select * from item_master_hc where par_id = '$ParId' order by item_desc asc";
											$SelectChildSql = mysqli_query($dbConn,$SelectChildQuery);
											if($SelectChildSql == true){
												if(mysqli_num_rows($SelectChildSql)>0){
													while($CList = mysqli_fetch_object($SelectChildSql)){
											?>
														<tr>
															<td nowrap="nowrap" class="cboxlabel">
															<?php echo $CList->item_code; ?>
															</td>
															<td width="60%" class="lboxlabel"><?php echo $CList->item_desc; ?></td>
															<td class="cboxlabel"><?php echo $CList->price; ?></td>
															<td class="cboxlabel"><?php echo $CList->unit; ?></td>
														</tr>
											<?php 			
													}
												}else{
											?>
													<tr>
														<td colspan="4" class="cboxlabel">-- -- --</td>
													</tr>
											<?php 
												}
											}
										} } ?>	
										</tbody>
									</table>
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
