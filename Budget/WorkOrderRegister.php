<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Works Register';
checkUser();
$success = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}                       
$SheetRABArr  = array();
$SheetCCNoArr = array();
$SheetDataArr = array();
if($_SESSION['isadmin'] == 1){
	$SelectWorkQuery 	= "SELECT a.*, b.name_contractor FROM sheet a LEFT JOIN contractor b ON (a.contid = b.contid) ORDER BY sheet_id DESC";
}else{
	$SelectWorkQuery 	= "SELECT a.*, b.name_contractor FROM sheet a LEFT JOIN contractor b ON (a.contid = b.contid) WHERE a.eic = '$staffid' ORDER BY sheet_id DESC";
}
//$SelectWorkQuery 	= "select a.*, b.* from sheet a left join abstractbook b on (a.sheet_id = b.sheetid) where a.active = 1 ".$WhereClause." order by a.computer_code_no asc";
$SelectWorkSql 	= mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		$RowCount = 1;
	}
}

if($RowCount == 1){
	$TRNumArr = array();
	$StaffNameArr = array();
	$TrRegquery = "SELECT globid,tr_no FROM tender_register ORDER BY tr_id ASC";
	//$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
	$TrRegquerysql = mysqli_query($dbConn,$TrRegquery);
	if($TrRegquerysql == true){
		if(mysqli_num_rows($TrRegquerysql) > 0){
			while($TsList = mysqli_fetch_object($TrRegquerysql)){
				$TRNo = $TsList->tr_no;
				$GlobID = $TsList->globid;
				$TRNumArr[$GlobID] = $TRNo;
			}
		}
	}
	$staffresult = " SELECT staffid,staffname FROM staff ORDER BY staffid ASC";
	$staffresultSql = mysqli_query($dbConn,$staffresult);
	if($staffresultSql == true){
		if(mysqli_num_rows($staffresultSql) > 0){
			//foreach($Hoaresult as $key => $value){
			while($ListStaff = mysqli_fetch_object($staffresultSql)){
				$StaffNameArr[$ListStaff->staffid] = $ListStaff->staffname;
			}
		}
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
												<div class="card-header inkblue-card" align="center"> Works - Register </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" border="0" align="center" class="table1 table2 table2excel" id="dataTable">
																	<thead>
																		<tr class='labeldisplay'>
																			<th align="center" valign="middle">SNo.</th>
																			<th align="center" valign="middle" nowrap="nowrap">CC No.</th>
																			<th align="center" valign="middle">Tender No.</th>
																			<th align="center" valign="middle">Work Order No.</th>
																			<th align="center" valign="middle">Work Short Name</th>
																			<th align="center" valign="middle">Name of Contractor</th>
																			<th align="center" valign="middle">Agreement No.</th>
																			<th align="center" valign="middle">Work Order Date</th>
																			<th align="center" valign="middle">Work Order Value<br>( &#x20B9; )</th>
																			<th align="center" valign="middle">Engineer In Charge</th>
																			<!-- <th align="center" valign="middle">Action</th> -->
																		</tr>
																  	</thead>
																  	<tbody>
																		<?php if($RowCount == 0){ ?>
																			<tr>
																				<td align="center" colspan="10"> No Records Found </td>
																			</tr>
																		<?php }else{ 
																					$sno = 1;
																					while($List = mysqli_fetch_object($SelectWorkSql)){
																						$TrIdVal = $List->tr_id;
																		?>
																			<tr>
																				<td align="center"><?php echo $sno; ?></td>
																				<td align="center"><?php echo $List->computer_code_no; ?></td>
																				<td><?php echo $TRNumArr[$List->globid]; ?></td>
																				<td><?php echo $List->work_order_no; ?></td>
																				<td><?php echo $List->short_name; ?></td>
																				<td><?php echo $List->name_contractor; ?></td>
																				<td><?php echo $List->agree_no; ?></td>
																				<td align="center"><?php echo dt_display($List->work_order_date); ?></td>
																				<td align="right"><?php echo IndianMoneyFormat($List->work_order_cost); ?></td>
																				<td align="left"><?php echo $StaffNameArr[$List->eic]; ?></td>
																				<!--	<td class="cboxlabel" nowrap="nowrap">
																					<?php //if($List->active == 2){ $BtnName = " View "; ?>
																						Work Completed
																					<?php //}else{ $BtnName = " View & Edit"; ?>
																						Work in progress
																					<?php //} ?>
																				</td>	-->
																				<!--	<td align="center">
																				<a data-url="WorkOrder?sheet_id=<?php //echo $List->sheet_id; ?>" class="btn btn-info" name="btnView" id="btnView"><?php //echo $BtnName; ?></a>
																				</td>	-->
																			</tr>
																			<?php 
																				$sno++; 
																					} 
																				} ?>
																	</tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
															<a data-url="WorkOrder" class="btn btn-info" name="view" id="view">Back</a>
														</div>
													</div>
												</div>
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
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "Work Order Register-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
				//preserveColors: preserveColors
			});
		}
	});
});
</script>
<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			paging: true, 
		});
	});
</script>
<style>
	.dataTables_wrapper{
		width:98% !important;
	}
</style>