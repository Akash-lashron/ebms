<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'EMD Register';


checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}
$RowSpanArr = array();
$CurrDate = date('Y-m-d');

$WorkTRID =array();
$WorkQuery = "SELECT date_of_completion,sheet_id FROM sheet";
$WorkQuerysql = mysqli_query($dbConn,$WorkQuery);
if($WorkQuerysql == true){
	if(mysqli_num_rows($WorkQuerysql)>0){
		while($List2 = mysqli_fetch_object($WorkQuerysql)){
			$SchDate = $List2->date_of_completion;
			$GlobID = $List2 ->globid;
			$Newdate =   date('Y-m-d', strtotime($SchDate. ' + 8 months'));
			if($Newdate < $CurrDate){
				$TrId1 	   = $List2->sheet_id;
				array_push($WorkTRID,$TrId1);
			}
			
		  }
			
		}
	}
	
$ImpDetIds = implode(',',$WorkTRID); 
// $PgRetMasterQuery = "SELECT a.*, b.*,  c.name_contractor FROM sheet a 
// 					INNER JOIN bg_fdr_details b ON (a.sheet_id = b.master_id) 
// 					INNER JOIN contractor c ON (b.contid = c.contid) 
// 					WHERE b.master_id IN ($ImpDetIds) and b.inst_status != 'R'";

$PgRetMasterQuery =   "SELECT sheet.*,bg_fdr_details.*, contractor.name_contractor FROM sheet 
						JOIN contractor ON sheet.contid = contractor.contid 
						JOIN bg_fdr_details ON sheet.sheet_id = bg_fdr_details.master_id 
						WHERE bg_fdr_details.inst_purpose='SD' AND bg_fdr_details.inst_status != 'R' AND bg_fdr_details.master_id IN ($ImpDetIds)
						order by bg_fdr_details.contid";
$MasterResult1 = mysqli_query($dbConn,$PgRetMasterQuery);
if($MasterResult1 == true){
	if(mysqli_num_rows($MasterResult1)>0){
		$RowCount = 1;
		while($List = mysqli_fetch_object($MasterResult1)){
			if(isset($RowSpanArr[$List->tr_id][$List->contid])){
				$RowSpanArr[$List->tr_id][$List->contid] = $RowSpanArr[$List->tr_id][$List->contid] + 1;
			}else{
				$RowSpanArr[$List->tr_id][$List->contid] = 1;
			}
			$PGDataArr[] = $List;
		}
	}else {
		$RowCount = 0;
	}
}
if(isset($_POST['back']))
{
	header('Location: Home.php');
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
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<!-- <div class="div1">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">SD Release Reminder <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i> </span></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">

																<table width="100%" align="center" class="dataTable table2excel mgtb-8 example">
																	<thead>
																		<tr>
																			<th rowspan="2" valign="middle">SNo.</th>
																			<th rowspan="2" valign="middle">CCNo.</th>
																			<th rowspan="2" valign="middle">Work Order No.</th>
																			<th rowspan="2" valign="middle">Name of Work</th>
																			<th rowspan="2" valign="middle">Contractor Name</th>
																			<!-- <th rowspan="2" valign="middle">SD Amount</br>( &#8377; )</th> -->
																			<th colspan="5" valign="middle">SD Details</th>
																		</tr>
																		<tr>
																			<th valign="middle">Instrument Type</th>
																			<th valign="middle">Instrument No.</th>
																			<th valign="middle">Date of Issue</th>
																			<th valign="middle">Date of Expiry</th>
																			<th valign="middle">Amount</br>( &#8377; )</th>
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
																			<td rowspan= <?php echo $TrRowspan ?> class='tdrowbold' valign='middle' align='center'><?php echo $PGValue->computer_code_no; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_order_no; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_name; ?></td>
																			<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																			<!-- <td rowspan= <?php// echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php// echo $PGValue->sd_amt; ?></td> -->
																			<td class='tdrow' align='center' valign='middle'><?php echo $PGValue->inst_type; ?></td>
																			<td class='tdrow' align='left' valign='middle'><?php echo $PGValue->inst_serial_no; ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_date); ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($PGValue->inst_exp_date); ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->inst_amt); ?></td>
																		</tr>
																		<?php 
																				$x++; $y++;  $SNO++;
																			}else{
																		?>
																		<tr class='labeldisplay'>
																			<?php if($y == 0){ ?>
																				<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																				<!-- <td rowspan= <?php //echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php //echo $PGValue->sd_amt; ?></td> -->
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
																		<?php $PrevTrId = $PGValue->tr_id; $PrevContId = $PGValue->contid; } }else{ ?>
																			<tr class='labeldisplay'>
																				<td colspan='10' class='tdrow' align='center' valign='middle'>No Records Found</td>
																			</tr>
																			<?php } ?>
																	</tbody>
																</table>

															</div>
														</div>
														<div align="center">
															<input type="submit" class="btn btn-info" name="back" value="Back">
															<!--<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" />-->
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="div1">&nbsp;</div> -->
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
	$('.dataTable').DataTable({"paging":true,"ordering": true});

	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
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