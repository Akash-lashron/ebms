<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'EMD Register';


checkUser();
$success = 0;
if(isset($_POST['btn_back'])){
	header("Location:Home.php");
}
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'/'. $mm .'/'.$yy;
}
$ExpArr1 = array();
$TrArr1 = array();
$DetIdArr1 = array();
$RowSpanArr = array();
$PGDataArr = array();
$CurrDate = date('Y-m-d');
$MasterQuery1 = "SELECT a.tr_id, b.bfdid, b.inst_exp_date,b.inst_type, b.inst_purpose FROM loi_entry a INNER JOIN bg_fdr_details b ON ( a.loa_pg_id = b.master_id ) 
WHERE b.inst_purpose = 'PG'AND b.inst_type = 'FDR' AND b.inst_status != 'R'  AND b.inst_exp_date < '$CurrDate'";
//echo $MasterQuery1;exit;
$MasterQuery1sql = mysqli_query($dbConn,$MasterQuery1);
if($MasterQuery1sql == true){
	if(mysqli_num_rows($MasterQuery1sql)>0){
		while($List10 = mysqli_fetch_object($MasterQuery1sql)){
			$TrId 	  = $List10->tr_id;
			$insdetId = $List10->bfdid;
			$ExpDate = $List10->inst_exp_date;
			// /echo $ExpDate;
			if($CurrDate > $ExpDate){
				$ExpArr1[$insdetId] = $ExpDate;
				array_push($TrArr1,$TrId);
		   }	
	   }
	}
}
$ImpTrIds = implode(',',$TrArr1);
$WorkQuery = "SELECT sch_comp_date,work_ext_date FROM works WHERE tr_id IN ($ImpTrIds) AND '$CurrDate' <= sch_comp_date + INTERVAL 8 MONTH OR  '$CurrDate' <= work_ext_date + INTERVAL 8 MONTH";
//echo $WorkQuery;exit;
$WorkQuerysql = mysqli_query($dbConn,$WorkQuery);
if($WorkQuerysql == true){
	if(mysqli_num_rows($WorkQuerysql)>0){
		while($List2 = mysqli_fetch_object($WorkQuerysql)){
			$TrId1 	   = $List2->tr_id;
			$SchCompDt = $List2->sch_comp_date;
			$ExtenDt   = $List2->work_ext_date;
			if ($ExtenDt == '' ){	
				$NewSchComdate =   date('Y-m-d', strtotime($SchCompDt. ' + 8 months'));
				foreach($ExpArr1 as $key=>$ExpDtVal){
					if($NewSchComdate > $ExpDtVal){
						array_push($DetIdArr1,$key);
					}
				 }
			}else{
				$NewSchComdate =   date('Y-m-d', strtotime($ExtenDt. ' + 8 months'));
				foreach($ExpArr1 as $key=>$ExpDtVal){
					if($NewSchComdate > $ExpDtVal){
						array_push($DetIdArr1,$key);
						
					}
				}
			}
		
			
		}
	}
}
$ImpDetIds = implode(',',$DetIdArr1);
if($ImpTrIds != ''){
	$PgRetMasterQuery = "SELECT a.*, b.*, c.tr_no, c.work_name, d.name_contractor, e.ccno, e.sch_comp_date, e.work_ext_date, e.eic, f.staffid, f.staffname FROM loi_entry a 
	INNER JOIN bg_fdr_details b ON (a.loa_pg_id = b.master_id) 
	INNER JOIN tender_register c ON (a.tr_id = c.tr_id) 
	INNER JOIN contractor d ON (b.contid = d.contid)
	INNER JOIN works e ON (a.globid = e.globid)
	INNER JOIN staff f ON (e.eic = f.staffid)
	WHERE b.bfdid IN ($ImpDetIds) AND b.inst_type = 'FDR' AND b.inst_purpose = 'PG'  AND b.inst_exp_date < '$CurrDate'	ORDER BY c.tr_id  ASC";

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
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">PG-FDR Expired Reminder</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">

																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																	
																	<tr>
																			<th rowspan="2" valign="middle">SNo.</th>
																			<th rowspan="2" valign="middle">Tender No.</th>
																			<th rowspan="2" valign="middle">CC NO.</th>
																			<th rowspan="2" valign="middle">Name of Work</th>
																			<th rowspan="2" valign="middle">Scheduled Date <br>of Completion</th>
																			<th rowspan="2" valign="middle"> Extended Date <br>of Completion</th>
																			<th rowspan="2" valign="middle">Engineer Incharge</th>
																			<th rowspan="2" valign="middle">Contractor Name</th>
																			<th rowspan="2" valign="middle">PG Amount</th>
																			<th colspan="8" valign="middle">PG Detail</th>
																			
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
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->ccno; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo $PGValue->work_name; ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo dt_display ($PGValue->sch_comp_date); ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow' align = 'justify'><?php echo dt_display($PGValue->work_ext_date); ?></td>
																			<td rowspan= <?php echo $TrRowspan ?> valign='middle' class='tdrow'><?php echo $PGValue->staffname; ?></td>
																			<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='left' valign='middle'><?php echo $PGValue->name_contractor; ?></td>
																			<td rowspan= <?php echo $ContRowSpan ?> class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGValue->pg_amt); ?></td>
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
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div align="center">
								<input type="submit" class="btn btn-info" name="btn_back" id="btn_back" value="Back" />
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