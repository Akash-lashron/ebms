<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Technical Sanction View';
checkUser();
$success = 0;
$staffid = $_SESSION['sid'];
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'-'. $mm .'-'.$yy;
}                       
$SheetRABArr = array(); $SheetCCNoArr = array(); $SheetDataArr = array();
//$SelectWorkQuery 	= "select a.*, b.* from sheet a left join abstractbook b on (a.sheet_id = b.sheetid) where a.active = 1 ".$WhereClause." order by a.computer_code_no asc";
if($_SESSION['isadmin'] == 1){
	$SelectWorkQuery 	= "SELECT a.*, b.name_contractor FROM sheet a LEFT JOIN contractor b ON (a.contid = b.contid) ORDER BY computer_code_no DESC";
}else{
	$SelectWorkQuery 	= "SELECT a.*, b.name_contractor FROM sheet a LEFT JOIN contractor b ON (a.contid = b.contid) WHERE (a.eic = '$staffid' OR FIND_IN_SET($staffid,a.assigned_staff)) ORDER BY CAST(a.computer_code_no AS UNSIGNED INTEGER) DESC";
}
$SelectWorkSql 	 = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		$RowCount = 1;
	}
}
if(isset($_POST['btn_back'])){
	header("Location:WorkOrder.php");
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
                <?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">List of Works</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<table width="100%" border="0" align="center" class="table1 table2" id="dataTable">
																		<thead>
																	 <tr class='labeldisplay'>
																	    <th align="center" valign="middle">SNo.</th>
																		<th align="center" valign="middle">C.C.No.</th>
																		<th align="center" valign="middle">Work Order No.</th>
																		<th align="center" valign="middle">Work ShortName</th>
																		<th align="center" valign="middle">Name of Contractor</th>
																		<th align="center" valign="middle">Agreement No.</th>
																		<th align="center" valign="middle">W.O.Date</th>
																		<th align="center" valign="middle">Status</th>
																		<th align="center" valign="middle">Action</th>
																     </tr>
																  </thead>
																  <tbody>
																		<?php if($RowCount == 0){ ?>
																			<tr>
																				<td colspan="8">No Records Found</td>
																			</tr>
																		<?php }else{ $sno = 1; while($List = mysqli_fetch_object($SelectWorkSql)){ 
																		?>
																			<tr>
																				<td><?php echo $sno; ?></td>
																				<td align="center"><?php echo $List->computer_code_no; ?></td>
																				<td><?php echo $List->work_order_no; ?></td>
																				<td><?php echo $List->short_name; ?></td>
																				<td><?php echo $List->name_contractor; ?></td>
																				<td><?php echo $List->agree_no; ?></td>
																				<td><?php echo dt_display($List->work_order_date); ?></td>
																				<td class="cboxlabel" nowrap="nowrap">
																					<?php if($List->active == 2){ $BtnName = " View "; ?>
																						Work Completed
																					<?php }else{ $BtnName = " View & Edit"; ?>
																						Work in progress
																					<?php } ?>
																				</td>
																				<td align="center">
																				<a data-url="WorkOrder?sheet_id=<?php echo $List->sheet_id; ?>" class="btn btn-info" name="btnView" id="btnView"><?php echo $BtnName; ?></a>
																				</td>
																			</tr>
																			<?php $sno++; } } ?>
																		</tbody>
																		</table>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
							<div align="center">
								<!-- <input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" /> -->
								<a data-url="WorkOrder" class="btn btn-info" name="view" id="view">Back</a>
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
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
		document.querySelector('#top').onload = function(){
		if(msg != "")
		{
			//swal(msg, "");
			swal({
				 title: "",
				 text: msg,
				 confirmButtonColor: "#3dae38",
				 type:"success",
				 confirmButtonText: " OK ",
				 closeOnConfirm: false,
			},
			function(isConfirm){
				 if (isConfirm) {
					url = "ShortDescCreate.php";
					window.location.replace(url);
				 }
			});
		}
	};
</script>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			paging: true, 
		});
		$(window).load(function() {
			$("#dataTable_wrapper").prepend('<button type="button" data-url="WorkOrder" class="AddNewBtn BtnHref" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</button>');
		});
	});
</script>
<style>
	.dataTables_wrapper{
		width:98% !important;
	}
</style>