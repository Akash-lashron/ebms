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
if(isset($_POST['btn_view']) == ' View '){
	$MasterId 			= $_POST['cmb_work_name'];
	$SelectMasterQuery 	= "select * from partab_master where mastid = '$MasterId'";
	$SelectMasterSql 	= mysqli_query($dbConn,$SelectMasterQuery);
	if($SelectMasterSql == true){
		if(mysqli_num_rows($SelectMasterSql) > 0){
			$MasterList = mysqli_fetch_object($SelectMasterSql);
			$WorkName 	= $MasterList->work_name;
			$WorkAmt 	= $MasterList->partA_amount;
			
			$SelectDetailQuery  = "select a.*, b.group_desc, b.short_desc from parta_details a inner join group_datasheet b on (a.id = b.id) where a.mastid = '$MasterId'";
			$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
			if($SelectDetailSql == true){
				if(mysqli_num_rows($SelectDetailSql)>0){
					$RowCount = 1;
				}
			}
		}
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
								<div class="div1" align="center">
									&nbsp;
								</div>
								<div class="div10" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="center">Estimate View</div>
										<div class="row innerdiv" align="center">
											<div class="row lboxlabel">Name of Work : <?php echo $WorkName; ?></div>
											<div class="row clearrow"></div>
											<div class="row">
												<table align="center" class="table itemtable" width="100%">
													<thead>
														<tr>
															<th nowrap="nowrap" width="2%">Item No</th>
															<th nowrap="nowrap" width="60%">Item Description</th>
															<th>Unit</th>
															<th>Qty</th>
														</tr>
													</thead>
													<tbody>
													<?php if($RowCount == 1){ while($DtList = mysqli_fetch_object($SelectDetailSql)){ ?>
														<tr>
															<td nowrap="nowrap" class="cboxlabel"><?php echo $DtList->sno; ?></td>
															<td width="60%" class="lboxlabel"><?php echo $DtList->group_desc; ?></td>
															<td class="cboxlabel"><?php if($DtList->quantity > 0){ echo $DtList->unit; } ?></td>
															<td class="cboxlabel"><?php if($DtList->quantity > 0){ echo $DtList->quantity; } ?></td>
														</tr>
													<?php } }else{ ?>
														<tr><td colspan="4" align="center"> No Records Found</td></tr>
													<?php } ?>
												</tbody>
												</table>
											</div>
											<div class="row clearrow"><input type="button" name="btn_back" id="btn_back" class="btn btn-primary" value=" Back "></div>
										</div>
									</div>
								</div>
								<div class="div1" align="center">
									
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
<style>
	.itemtable td {
		font-size: 11px;
	}
</style>