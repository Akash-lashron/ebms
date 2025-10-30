<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'NIT View';
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
//$result = mysqli_query($dbConn," SELECT a.*,b.* FROM technical_sanction a INNER JOIN hoa b ON a.hoaid = b.hoa_id  ORDER BY a.ts_id ASC");
if($_SESSION['isadmin'] == 1){
	$result = "SELECT a.*, b.staffname, c.ts_no FROM tender_register a INNER JOIN staff b ON ( a.eic = b.staffid ) INNER JOIN technical_sanction c ON ( a.ts_id = c.ts_id )  ORDER BY tr_id ASC";
}else{
	$result = "SELECT a.*, b.staffname, c.ts_no FROM tender_register a INNER JOIN staff b ON ( a.eic = b.staffid ) INNER JOIN technical_sanction c ON ( a.ts_id = c.ts_id ) WHERE (a.eic = '$staffid' OR a.created_by = '$UserId') ORDER BY tr_id ASC";
}
$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);

//print_r($HoaArr);
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);
$Finacid  = array();
$FinaQuery = "SELECT tr_id FROM emd_master";
$FinaResult = mysqli_query($dbConn,$FinaQuery);
if(mysqli_num_rows($FinaResult)>0){
	while($List = mysqli_fetch_array($FinaResult)){
		array_push($Finacid,$List['tr_id']);
	}
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
		function goBack(){
		url = "Home.php";
		window.location.replace(url);
	}
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
												<div class="card-header inkblue-card" align="center">NIT - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
								                                  <thead>
																	<tr>
																		<th valign="middle">SNo.</th>
																		<th valign="middle">Tenchical Sanction Number</th>
																		<th valign="middle">Tender Number</th>
																		<th valign="middle">Name of Work</th>
																		<th valign="middle">Tender Estimate<br>Amount<br>(&#x20B9;)</th>
																		<th valign="middle">Tender Cost<br>(&#x20B9;)</th>
																		<th valign="middle">EMD Amount<br>(&#x20B9;)</th>
																		<th valign="middle">PG (%)</th>
																		<th valign="middle">SD (%)</th>
																		<th valign="middle">Duration<br>in Months</th>
																		<th valign="middle">Engineer In charge</th>
																		<th valign="middle">Action</th>
																	
																	</tr>
																</thead>
																<tbody>
																	<tr class='labeldisplay'>
																		<?php
																			$SNO = 1; 
																			while($List = mysqli_fetch_object($result_sql)){
																		?>
																		<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																		<td valign='middle' class='tdrow' align = 'left'><?php echo $List->ts_no; ?></td>
																		<td valign='middle' class='tdrow' align = 'left'><?php echo $List->tr_no; ?></td>
																		<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->work_name; ?></td>
																		<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($List->tr_est); ?></td>
    																	<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($List->tr_cost); ?></td>
																		<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($List->emd); ?></td>
																		<td valign='middle' class='tdrow' align = 'right'><?php echo $List->pg_per; ?></td>
																		<td valign='middle' class='tdrow' align = 'right'><?php echo $List->sd_per; ?></td>
																		<td valign='middle' class='tdrow' align = 'middle'><?php echo $List->time_month; ?></td>
																		<td valign='middle' class='tdrow' align = 'left'><?php echo $List->staffname; ?></td>
																		<td valign='middle' class='tdrow' >
																		<?php if (in_array($List->tr_id, $Finacid)){ $BtnName = " View "; ?>
																		<?php }else{ $BtnName = " View & Edit"; ?>
																		<?php } ?><a data-url="NIT?id=<?php echo $List->tr_id; ?>" class=" BtnHref btn btn-info" name="View" id="View"><?php echo $BtnName; ?></a></td>
																	</tr>
								                                   <?php  $SNO++; }  ?>
																   </tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
															<input type="button" class="btn btn-info" name="Back" id="Back" value="Back" onClick="goBack();"/>

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
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "NIT-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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