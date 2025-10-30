<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'PG Waiting For Confirmation';


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

//$MasterQuery = "SELECT a.*, b.*, c.name_contractor,d.* FROM loi_entry a INNER JOIN tender_register b ON (a.tr_id = b.tr_id) INNER JOIN contractor c ON (a.contid = c.contid) INNER JOIN bg_fdr_details d ON (a.loa_pg_id = d.master_id)";
$RowSpanArr=array(); $PGDataArr=array();
$MasterQuery = "SELECT a.*, b.*, c.tr_no, c.work_name, d.name_contractor FROM loi_entry a 
INNER JOIN bg_fdr_details b ON (a.loa_pg_id = b.master_id) 
INNER JOIN tender_register c ON (a.tr_id = c.tr_id) 
INNER JOIN contractor d ON (a.contid = d.contid) WHERE b.inst_purpose='PG' AND  b.inst_status='EIC' AND  b.approved_session !='ACC'";
// /echo $MasterQuery;exit;
$MasterResult = mysqli_query($dbConn,$MasterQuery);
if($MasterResult == true){
	if(mysqli_num_rows($MasterResult)>0){
		$RowCount = 1;
		while($List = mysqli_fetch_object($MasterResult)){
			if(isset($RowSpanArr[$List->tr_id][$List->contid])){
				$RowSpanArr[$List->tr_id][$List->contid] = $RowSpanArr[$List->tr_id][$List->contid] + 1;
			}else{
				$RowSpanArr[$List->tr_id][$List->contid] = 1;
			}
			$PGDataArr[] = $List;
		}
	}
}

if(isset($_POST['back']))
{
	header('Location: Home.php');
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js">
	
</script>
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
												<div class="card-header inkblue-card" align="center">PG Waiting For Confirmation
													<!-- <button type="button" data-url="PGEntry" class="AddNewBtn BtnHref" id="AddNewBtn" style="">
														<i class="fa fa-plus" style="font-size:13px; padding-top:2px;">
														</i> Create New
													</button> -->
												</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">

																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																	
																		<tr>
																			<th rowspan="2" valign="middle">SNo.</th>
																			<th rowspan="2" valign="middle">Tender No.</th>
																			<th rowspan="2" valign="middle">Name of Work</th>
																			<th rowspan="2" valign="middle">Contractor Name</th>
																			<th colspan="5" valign="middle">PG Detail</th>
																			<th rowspan="2" valign="middle">Action</th>
																		</tr>
																		<tr>
																			<th valign="middle">Instrument Type</th>
																			<th valign="middle">Instrument No.</th>
																			<th valign="middle">Date of Issue</th>
																			<th valign="middle">Date of Expiry</th>
																			<th valign="middle">Amount</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php $SNO = 1; $PrevTrId=""; $PrevContId="";
																		//$EMDCountArr = array_count_values(array_column($MasterResult, 'inst_type'));
																		if($RowCount == 1){ foreach($PGDataArr as $PGkey => $PGValue){ 
																			$ContRowSpan = $RowSpanArr[$PGValue->tr_id][$PGValue->contid];
																			$RowSpanArr1 = $RowSpanArr[$PGValue->tr_id];
																			$TrRowspan = array_sum($RowSpanArr1);
																			if($PrevTrId != $PGValue->tr_id){
																				$x = 0; $PrevContId = ""; $y = 0;
																			}
																			if($PrevContId != $PGValue->contid){
																				$y = 0;
																			}
																			if($x == 0){ 
																		?>
																		<tr class='labeldisplay'>
																			<td rowspan= <?php echo $TrRowspan ?> class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->tr_no; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_name; ?></td>
																			<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																			<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->inst_amt); ?></td>
																			<td class='tdrow' align='center' rowspan="<?php echo $TrRowspan; ?>"><input type="button" data-url="PGConfirmation?id=<?php echo $PGValue->tr_id; ?>" class="BtnHref btn btn-info" Value="View Details "></td>
																		</tr>
																		<?php 
																				$x++; $y++;  $SNO++;
																			}else{
																		?>
																		<tr class='labeldisplay'>
																			<?php if($y == 0){ ?>
																				<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																			<?php } ?>
																			<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																			<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->inst_amt); ?></td>
																		</tr>
																			<?php 
																				$x++; $y++;
																			}
																		?>
																		<?php $PrevTrId = $PGValue->tr_id; $PrevContId = $PGValue->contid; } } ?>
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
							</div>
							<div align="center">
							<input type="submit" name="back" value="Back">
								<!--<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" />-->
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
	$('.dataTable').DataTable({"paging":true,"ordering": true});
	//$(window).load(function() {
	//	$("#DataTables_Table_0").prepend('<button type="button" data-url="PGEntry" class="AddNewBtn BtnHref" id="AddNewBtn" style=""><i class="fa fa-plus" style="font-size:13px; padding-top:2px;"></i> Create New</button>');
	//});
	
	

});
</script>
<script>
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
		document.querySelector('#top').onload = function(){
		if(msg != "")
		{
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