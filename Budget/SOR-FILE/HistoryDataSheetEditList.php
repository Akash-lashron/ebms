<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";

include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0;
$staffid = $_SESSION['sid'];
$RowCount = 0; $PageTile = "";

$DsUrl = "";
if(isset($_GET['dsid'])){
	$DsId 	= $_GET['dsid'];
	$Level 	= $_GET['Level'];
	$Pruid 	= $_SESSION['PeriodicalId'];
	$DsUrl 	= $GlobPageUrlArr[$DsId];
	$ParIdArr = array();
	$SelectParIdQuery = "SELECT id, type, par_id FROM (SELECT * FROM pds_detail where puid = '$Pruid' ORDER BY par_id, id) products_sorted, (SELECT @pv := '$DsId') initialisation 
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

//$_SESSION['ViewDSUrl'] = basename($_SERVER['REQUEST_URI']);
$PageUrl1 = basename($_SERVER['REQUEST_URI']);
$PageUrl2 = str_replace('.php', '', $PageUrl1);
$_SESSION['ViewDSUrl'] = $PageUrl2;//basename($_SERVER['REQUEST_URI']);
$PageName = $PTPart1.$PTIcon.'History'.$PTIcon.'Schedule of Rate'.$PTIcon.$PageTile;

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
										<th align="center" colspan="8" style="background:#136BCA; color:#ffffff; border-color:#136BCA">History Data Sheet List - <?php echo $PageTile; ?></th>
									</tr>
									<tr>
										<th>&nbsp;SNo.&nbsp;&nbsp;</th>
										<th nowrap="nowrap">&nbsp;Item Code&nbsp;</th>
										<th>Item Desc.</th>
										<th nowrap="nowrap">&nbsp;Item Qty.&nbsp;</th>
										<th nowrap="nowrap">&nbsp;Item Unit&nbsp;</th>
										<th nowrap="nowrap">&nbsp;View&nbsp;</th>
									</tr>
								</thead>
								<tbody>
								<?php $Slno = 1; if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
									<tr>
										<td align="center"><?php echo $Slno; ?></td>
										<td align="center">
											<?php echo $List->type; ?>
											<?php 
												/*if($Level == 1){ 
													$EdtUrl = "DataSheetEditLevel1?refid=".$List->ref_id;
										 		}else{ 
													$EdtUrl = "DataSheetEditLevel2?refid=".$List->ref_id;
										 		} */
												if($List->new_merge == "M"){ 
													$ViewUrl = "HistoryDataSheetViewMerge?dsid=".$DsId."&refid=".$List->ref_id;
										 		}else{ 
													$ViewUrl = "HistoryDataSheetViewNew?dsid=".$DsId."&refid=".$List->ref_id;
										 		}
										 	?>
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
											<button type="button" title="View" class="btn fa-btn-v gView" data-id="<?php echo $List->ref_id; ?>" data-url="<?php echo $ViewUrl; ?>"><i class="fa fa-folder-o"></i></button>
										</td>
									</tr>
								<?php $Slno++; } } ?>
								</tbody>
							</table>
							<div class="div12">&nbsp;</div>
							<div class="div12" align="center"><a data-url="HistoryScheduleRates" class="btn btn-info">Back</a></div>
						</blockquote>
						<div align="right" class="users-icon-part">&nbsp;</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="CreateUrl" id="CreateUrl" value="<?php echo $DsUrl; ?>">
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
	/*$(window).load(function() {
		var AddNewUrl = $("#CreateUrl").val();
		$("#DataTables_Table_0_wrapper").prepend('<button type="button" data-url="'+AddNewUrl+'" class="AddNewBtn" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</button>');
	});
	$('body').on("click","#AddNewBtn", function(event){ 
		var DatUrl = $(this).attr("data-url");
		$(location).attr("href",DatUrl+".php");
		event.preventDefault();
		return false;
	});*/
	$('body').on("click",".gEdit", function(event){ 
		var EditUrl = $(this).attr("data-url");
		var SplitUrl = EditUrl.split("?");
		var Len = SplitUrl.length;
		if(Len > 0){
			if(Len == 1){
				var Url = SplitUrl[0]+".php";
			}else{
				var Url = SplitUrl[0]+".php?"+SplitUrl[1];
			}
			window.location.href = Url;
		}
		event.preventDefault();
		return false;
	});
	$('body').on("click",".gView", function(event){ 
		var ViewUrl = $(this).attr("data-url");
		var SplitUrl = ViewUrl.split("?");
		var Len = SplitUrl.length;
		if(Len > 0){
			if(Len == 1){
				var Url = SplitUrl[0]+".php";
			}else{
				var Url = SplitUrl[0]+".php?"+SplitUrl[1];
			}
			window.location.href = Url;
		}
		event.preventDefault();
		return false;
	});
});

</script>