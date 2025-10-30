<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
//require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$RowCount = 0; $WhereClause = "";
if(isset($_POST['btnView'])){
	if($_POST['btnView'] == " VIEW "){
		$FromAmt = $_POST['txt_from_cost'];
		$ToAmt = $_POST['txt_to_cost'];
		$WhereClause = " AND a.work_order_cost >= '$FromAmt' AND a.work_order_cost <= '$ToAmt'";
	}
}
$SelectWorkQuery = "SELECT a.*, b.name_contractor FROM sheet a LEFT JOIN contractor b ON (a.contid = b.contid) WHERE a.under_civil_sheetid = 0 ".$WhereClause." ORDER BY CAST(computer_code_no AS UNSIGNED INTEGER) DESC";
$SelectWorkSql 	 = mysqli_query($dbConn,$SelectWorkQuery);
if($SelectWorkSql == true){
	if(mysqli_num_rows($SelectWorkSql)>0){
		$RowCount = 1;
	}
}
//echo $SelectWorkQuery;exit;

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
 <style>
	.container{
		display:table;
		width:100%;
		border-collapse: collapse;
	}
	.table-row{  
		display:table-row;
		text-align: left;
	}
	.col{
		display:table-cell;
		border: 1px solid #CCC;
	}
</style>
<script type="text/javascript" language="javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
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
												<div class="card-header inkblue-card" align="center">Work Order Cost Wise Work Search</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div2 pd-lr-1" id="PE1">
															<div class="lboxlabel-sm">WO Cost From</div>
															<div>
																<input type="number" name="txt_from_cost" id="txt_from_cost" class="tboxclass tbox-sm" required />
															</div>
														</div>
														<div class="div2 pd-lr-1" id="PE2">
															<div class="lboxlabel-sm">WO Cost Upto</div>
															<div>
																<input type="number" name="txt_to_cost" id="txt_to_cost" class="tboxclass tbox-sm" required />
															</div>
														</div>
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="submit" name="btnView" id="btnView" class="btn btn-sm btn-info" value=" VIEW ">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">List of Works <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i> </span></span></div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
																	<table width="100%" border="0" align="center" class="table1 table2 example" id="dataTable">
																		<thead>
																			<tr class="note heading">
																				<th align="center" valign="middle">SNo.</th>
																				<th align="center" valign="middle">C.C.No.</th>
																				<th align="center" valign="middle">Work Order No.</th>
																				<th align="center" valign="middle">Work ShortName</th>
																				<!--<th align="center" valign="middle">T.S. No.</th>-->
																				<th align="center" valign="middle">Name of Contractor</th>
																				<th align="center" valign="middle">Agreement No.</th>
																				<th align="center" valign="middle">W.O.Date</th>
																				<th align="center" valign="middle">Status</th>
																				<th align="center" valign="middle">W.O.Cost</th>
																			</tr>
																		</thead>
																		<tbody>
																		<?php if($RowCount == 0){ ?>
																			<tr>
																				<td colspan="9">No Records Found</td>
																			</tr>
																		<?php }else{ $sno = 1; while($List = mysqli_fetch_object($SelectWorkSql)){ 
																		?>
																			<tr>
																				<td><?php echo $sno; ?></td>
																				<td align="center"><?php echo $List->computer_code_no; ?></td>
																				<td><?php echo $List->work_order_no; ?></td>
																				<td><?php echo $List->work_name; ?></td>
																				<!--<td><?php //echo $List->tech_sanction; ?></td>-->
																				<td><?php echo $List->name_contractor; ?></td>
																				<td><?php echo $List->agree_no; ?></td>
																				<td><?php echo dt_display($List->work_order_date); ?></td>
																				<td class="cboxlabel" nowrap="nowrap">
																					<?php if($List->active == 2){ ?>
																						Work Completed
																					<?php }else{ ?>
																						Work in progress
																					<?php } ?>
																				</td>
																				<td align="right"><?php echo $List->work_order_cost; ?></td>
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
									<div align="center">&nbsp;</div>
									<div class="div12" align="center">&nbsp;</div>
								</div>
                            </div>
                        </blockquote>
					</div>
				</div>	
			</div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != "")
					{
						if(msg == "1")
						{
							swal("", "Sucessfully Deleted...!!!", "success");
							/*swal({
								title: titletext,
								text: "Sucessfully Deleted...!!!",
								//timer: 4000,
								showConfirmButton: true
							});*/
						}
						if(msg == "0")
						{
							sweetAlert("Something Error...!!!", "", "");
						}
					}
				};
			</script>
        </form>
    </body>
</html>
<script>
	$(document).ready(function() {
		/*$('#dataTable').DataTable({
			responsive: true,
			paging: true, 
			buttons: [
				'excel', 
				{
					extend: 'pdf',
				 	text: 'PDF',
				 	title: '',
				 	download: 'download',
				 	header: true
			   	}, 
			]
		});*/
		/*$("#exportToExcel").click(function(e){ 
			var table = $('body').find('.table2excel');
			if(table.length){ 
				$(table).table2excel({
					exclude: ".noExl",
					name: "Excel Document Name",
					filename: "WorkList-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
					fileext: ".xls",
					exclude_img: true,
					exclude_links: true,
					exclude_inputs: true
				});
			}
		});*/
	});
</script>
<style>
	.dataTables_wrapper{
		width:98% !important;
	}
</style>
