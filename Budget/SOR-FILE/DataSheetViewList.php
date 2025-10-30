<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0;
$staffid = $_SESSION['sid'];
if(isset($_POST['btn_save_full_x'])){
	include("DataSheetSave.php");
}
$RowCount = 0; $PageTile = "";


if(isset($_GET['dsid'])){
	$DsId = $_GET['dsid'];
	$ParIdArr = array();
	$SelectParIdQuery = "SELECT id, type, par_id FROM (SELECT * FROM group_datasheet ORDER BY par_id, id) products_sorted, (SELECT @pv := '$DsId') initialisation 
						WHERE FIND_IN_SET(par_id, @pv) > 0 AND @pv := CONCAT(@pv, ',', id)";
	$SelectParIdSql = mysqli_query($dbConn,$SelectParIdQuery);
	if($SelectParIdSql == true){
		if(mysqli_num_rows($SelectParIdSql)>0){
			while($ParIdList = mysqli_fetch_object($SelectParIdSql)){
				array_push($ParIdArr,$ParIdList->par_id);
			}
		}
	}
	//echo $SelectParIdQuery;exit;
	$ParIdStr = implode(",",$ParIdArr);
	$PageTile = $GlobPageTitleArr[$DsId];
	$SelectQuery = "select * from datasheet_master where par_id IN ($ParIdStr)";//= '$DsId'";
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
}
$PageUrl1 = basename($_SERVER['REQUEST_URI']);
$PageUrl2 = str_replace('.php', '', $PageUrl1);
$_SESSION['ViewDSUrl'] = $PageUrl2;//basename($_SERVER['REQUEST_URI']);
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
						<blockquote class="bq1 stable" style="overflow:auto">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1 dataTable">
								<thead>
									<tr>
										<th align="center" colspan="6" style="background:#136BCA; color:#ffffff; border-color:#136BCA">Data Sheet List View - <?php echo $PageTile; ?></th>
									</tr>
									<tr>
										<th>&nbsp;SNo.&nbsp;&nbsp;</th>
										<th nowrap="nowrap">&nbsp;Item Code&nbsp;</th>
										<th>Item Desc.</th>
										<th nowrap="nowrap">&nbsp;Item Qty.&nbsp;</th>
										<th nowrap="nowrap">&nbsp;Item Unit&nbsp;</th>
										<th nowrap="nowrap">&nbsp;Status&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								<?php $Slno = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
									<tr>
										<td align="center"><?php echo $Slno; ?></td>
										<td align="center">
										<?php if($List->new_merge == "M"){ ?>
										<a data-url="DataSheetViewMerge?refid=<?php echo $List->ref_id; ?>"><u><?php echo $List->type; ?></u></a>
										<?php }else{ ?>
										<a data-url="DataSheetViewNew?refid=<?php echo $List->ref_id; ?>"><u><?php echo $List->type; ?></u></a>
										<?php } ?>
										</td>
										<td align="justify"><?php echo $List->group3_description; ?></td>
										<td align="right"><?php echo $List->quantity; ?></td>
										<td align="center">
										<?php 
										$Unit 	 	= $List->unit; 
										$ToUnit 	= $List->to_unit;
										$FinalUnit 	= $List->final_unit;
										$DSUnit		= '';
										if($ToUnit != ""){
											$DSUnit = $ToUnit;
										}else{
											if($FinalUnit != ""){
												$DSUnit = $FinalUnit;
											}else{
												$DSUnit = $Unit;
											}
										}
										echo $DSUnit;
										?>
										</td>
										<td align="center">
										<?php 
											if($List->ds_release == "Y"){
												echo "<span>Confirmed</span>";
											}else{
												echo "<span style='color:#C80D34'>Waiting for Confirmation</span>";
											}
										?>
										</td>
									</tr>
								<?php $Slno++; } }else{ ?>
									<tr>
										<!--<td colspan="6"> No Records Found </td>-->
										<td align="center" style="color:#CDCDCD"> -- </td>
										<td align="center" style="color:#CDCDCD"> -- </td>
										<td align="center" style="color:#CDCDCD"> -- </td>
										<td align="left"  style="color:#A0A0A0"> No Records Found </td>
										<td align="center" style="color:#CDCDCD"> -- </td>
										<td align="center" style="color:#CDCDCD"> -- </td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						
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
<script src="js/CommonJSLibrary.js"></script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false});
});

</script>