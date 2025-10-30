<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Bidders List';
checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'-'. $mm .'-'.$yy;
}

$result = mysqli_query($dbConn,"SELECT contractor.*,contractor_bank_detail.* FROM contractor INNER JOIN contractor_bank_detail ON(contractor_bank_detail.contid = contractor.contid)");
// ORDER BY type asc, group_id asc");
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);


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
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center"> Bidder's / Contractor's Details - View </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel">
																	<thead>
																		<tr>
																			<th rowspan=2 valign="middle">SNo.</th>
																			<th rowspan=2 valign="middle">Contractor Name</th>
																			<th rowspan=2 valign="middle">Contractor Address</th>
																			<th rowspan=2 valign="middle">GST No.</th>
																			<th rowspan=2 valign="middle">PAN No.</th>
																			<th colspan=4 valign="middle">Bank Details</th>
																			
																		</tr>
																		<tr>
																			<!--<th valign="middle">A/c Holder Name</th>-->
																			<th valign="middle">A/c No.</th>
																			<th valign="middle" nowrap="nowrap">Bank Name</th>
																			<th valign="middle" nowrap="nowrap">Branch Name</th>
																			<th valign="middle">IFSC Code</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			$SNO = 1; $rowspanval = 0;
																			while($List = mysqli_fetch_object($result)){
																				if($rowspanval == $List->contid){
																					$rowspanval= $rowspanval + 1;
																				}
																				echo $LastInsertid;
																		?>
																	<tr class='labeldisplay'>
																		<input type="hidden" name='txt_cont_id' id='txt_cont_id' class="tboxsmclass" value=<?php echo $List->contid; ?>>
																		<td rowspan='$rowspanval' class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																		<td rowspan='$rowspanval' valign='middle' class='tdrow' align = 'justify'><?php echo $List->name_contractor; ?></td>
																		<td rowspan='$rowspanval' valign='middle' class='tdrow' align = 'justify' style="width:150px;"><?php echo $List->addr_contractor; ?></td>
																		<td rowspan='$rowspanval' class='tdrow' align='center' valign='middle'><?php echo $List->gst_no; ?></td>
																		<td rowspan='$rowspanval' class='tdrow' align='center' valign='middle'><?php echo $List->pan_no; ?></td>
																		<!--<td class='tdrow' align='left' valign='middle'><?php echo $List->bank_acc_hold_name; ?></td>-->
																		<td class='tdrow' align='left' valign='middle'><?php echo $List->bank_acc_no; ?></td>
																		<td class='tdrow' align='left' valign='middle'><?php echo $List->bank_name; ?></td>
																		<td class='tdrow' align='left' valign='middle'><?php echo $List->branch_address; ?></td>
																		<td class='tdrow' align='left' valign='middle'><?php echo $List->ifsc_code; ?></td>
																	</tr>
																	<?php  $SNO++; } ?>
																	</tbody>
																</table>
																<div align="center">
																	<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
																	<a data-url="Bidders" class="btn btn-info" name="btn_save" id="btn_save">Back</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div align="center">&nbsp;</div>
						</blockquote>
					</div>
				</div>
			</div>            
			<!--==============================footer=================================-->
			<?php include "footer/footer.html"; ?>
			<script src="js/jquery.hoverdir.js"></script>
		</form>
	</body>
</html>
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":true,"ordering": true});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Bidder's-Details",
				filename: "Bidder's-Details-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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
<style>
table.dataTable > thead > tr > th{
	padding:2px !important;
	font-size:11px !important;
}
</style>
