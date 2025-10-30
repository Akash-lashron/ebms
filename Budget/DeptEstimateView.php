<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Department Estimate View';
checkUser();
$msg = ''; $success = '';
$userid = $_SESSION['userid'];

$RowCount = 0; $IsDeptEst = 0; $View = 0;
if(isset($_GET['id'])){   
	$TrId 	 	   = $_GET['id'];
	//echo $TrId;exit;
	$SelectQuery 	= "SELECT * FROM partab_master WHERE tr_id = '$TrId' ORDER BY tr_id ASC";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$IsDeptEst = 1;
		}
	}
	if($IsDeptEst == 0){ 
		$msg = "Dept Estimate Is not Uploaded Yet";
		$View = 0;
	
	}else{
		$msg = '';
		$View = 1;
	}

	/*$SelectWorkNameQuery = "SELECT work_name FROM tender_register WHERE tr_id = '$TrId' ORDER BY tr_id ASC";
	$SelectMastIdQuerySql = mysqli_query($dbConn,$SelectWorkNameQuery);
	if($SelectMastIdQuerySql == true){
		while($List = mysqli_fetch_object($SelectMastIdQuerySql)){
			$WorkName = $List->work_name;
		}
	}*/
	$SelectMastIdQuery 		= "SELECT mastid, work_name FROM partab_master WHERE tr_id = '$TrId' ORDER BY mastid ASC";
	$SelectMastIdQuerySql 	= mysqli_query($dbConn,$SelectMastIdQuery);
	if($SelectMastIdQuerySql == true){
		while($List = mysqli_fetch_object($SelectMastIdQuerySql)){
			$MastId = $List->mastid;
			$WorkName = $List->work_name;
		}
	}
	$SelectQuery 	= "SELECT * FROM parta_details WHERE mastid = '$MastId' ORDER BY detid ASC";
	$SelectSql 		= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$RowCount = 1;
		}
	}
}
if(isset($_POST['back'])){
     header('Location: DepEstViewEdit.php');
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 868px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
	.dataTable {
        line-height: 16px !important;
        font-weight: 700 !important;
        color: #74048C;
       font-size: 12px;
	   border-collapse: collapse;
       text-shadow: none;
       text-transform: none;
       font-family: Verdana, Arial, Helvetica, sans-serif;
       line-height: 17px;
}
	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
	}
</style>
<script type="text/javascript">
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
					<div class="grid_12" align="center"> 
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" id="bq1" style="overflow:auto;">
						  <div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Department Estimate - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12 dataTable" align="left">
																	<b>
																		<input type="text" readonly="" class=" dataTable tboxclass" value="Name Of Work : <?php if(isset($WorkName)){ echo $WorkName; } ?>">
																	</b>
																</div>
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																  	<thead>
																		<tr class='labeldisplay'>
																			<th valign="middle" nowrap="nowrap">Item No.</th>
																			<th valign="middle">Description</th>
																			<th valign="middle">Qty</th>
																			<th valign="middle">Unit</th>
																			<th valign="middle" nowrap="nowrap">Rate ( &#8377 )</th>
																			<th  valign="middle" nowrap="nowrap">Amount ( &#8377 )</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			if($View == 1){
																			$TotalAmount = 0;
																			if($RowCount == 1){ while($List = mysqli_fetch_object($SelectSql)){ ?>
																				<tr class='labeldisplay'>
																					<td class='tdrowbold' valign='middle'align="center"><?php echo $List->sno; ?></td>
																					<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->description; ?></td>
																					<td class='tdrow' align="right"  valign='middle'><?php if($List->quantity != 0){ echo $List->quantity; } ?></td>
																					<td class='tdrow' align="center"  valign='middle'><?php echo $List->unit; ?></td>
																					<td class='tdrow' align="right"  valign='middle'><?php if($List->supply != 0){ echo IndianMoneyFormat($List->supply); } ?></td>
																					<td class='tdrow' align="right"  valign='middle'>
																					<?php 
																					$Amount = round(($List->quantity * $List->supply),2);
																					$TotalAmount = $TotalAmount + $Amount;
																					if($Amount != 0){
																						echo IndianMoneyFormat($Amount); 
																					}
																					?>
																					</td>
																				</tr>
																		<?php } ?> 
																		<tr class='labeldisplay'>
																			<td class='tdrowbold' valign='middle' align="right">&nbsp;</td>
																			<td  class='tdrowbold'align="right"><b>TOTAL AMOUNT ( &#8377 ) &nbsp;</b></td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td align="right">&nbsp;</td>
																			<td   class='tdrowbold'align="right">
																				<b>
																					<?php  
																					$Amount = round($TotalAmount,2);
																					if($TotalAmount != 0){
																						echo '<b>'.IndianMoneyFormat($TotalAmount).'</b>'; 
																					}
																					?>
																				</b>
																			</td>
										                    		</tr>
																		<?php } } else{ ?>
																			<tr><td colspan="6"  class='tdrow' align="middle"  valign='middle'>No Reocrds Found</td></tr>
																		<?php } ?>
																	</tbody>
															  	</table>
															</div>
														</div>
														<div align="center">
															<div class="buttonsection">
																<a data-url="DepEstViewEdit" class="btn btn-info" name="view" id="view">Back</a>
															</div>
															<!--	<div class="buttonsection">
																<input type="button" name="exportToExcel" id="exportToExcel" value="Export To Excel" class="btn btn-info">
															</div>	-->
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
		</form>
	</body>
	<script>
		$(document).ready(function(){ 
			$("#exportToExcel").click(function(e){ 
				var table = $('body').find('.table2excel');
				if(table.length){ 
					$(table).table2excel({
						exclude: ".xlTable",
						name: "SOQ",
						filename: "DepartmentEstimate -" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
						fileext: ".xls",
						exclude_img: true,
						exclude_links: true,
						exclude_inputs: true
						//preserveColors: preserveColors
					});
				}
			});
		});
		var msg = "<?php echo $msg; ?>";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				BootstrapDialog.alert(msg);
			}
		};
	</script>
</html>
<style>
.table1 td{
	background:#fff;
}
</style>
