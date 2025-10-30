<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Administrator'.$PTIcon.'Taxes & Overheads';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
$SelectQuery = "select * from default_master order by de_name asc"; 
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
								<div class="div3" align="center">&nbsp;</div>
								<div class="div6" align="center">
									<table align="center" class="table itemtable formtable" width="100%">
										<thead>
											<tr>
												<th colspan="4" class="fhead">Taxes and Over Heads List</th>
											</tr>
											<tr>
												<th>SNo.</th>
												<th width="60%">Description</th>
												<th>Percentage</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php $SNo = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
											<tr>
												<td class="cboxlabel"><?php echo $SNo; ?></td>
												<td nowrap="nowrap" class="lboxlabel"><?php echo $List->de_name; ?></td>
												<td class="cboxlabel"><?php echo $List->de_perc; ?> %</td>
												<td class="cboxlabel">
													<button type="button" title="Edit" class="btn fa-btn-e gEdit" id="EBtn<?php echo $List->de_id; ?>" data-id="<?php echo $List->de_id; ?>"><i class="fa fa-edit"></i></button>
												</td>
											</tr>
										<?php $SNo++; } } ?>	
										</tbody>
									</table>
								</div>
								<div class="div3" align="center">&nbsp;</div>
								<div class="div12" align="center">
									<a data-url="Administrator" class="btn btn-info">Back</a>
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
	$('.gEdit').click(function(event){ 
		var id = $(this).attr("data-id");
  		$(location).attr("href","TaxesOverHeadsEdit.php?id="+id);
		event.preventDefault();
		return false;
  	});
});
</script>
