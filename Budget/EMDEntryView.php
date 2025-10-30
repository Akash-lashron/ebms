<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'EMD View';
checkUser();
$success = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
$RowCount = 0; 
$EmDDataArr = array(); 
$RowSpanArr = array();
$Finacid  = array();
if($_SESSION['isadmin'] == 1){
	$WhereClause = "";
}else{
	$WhereClause = " WHERE (tender_register.eic = '$staffid' OR tender_register.created_by = '$UserId') ";
}
$MasterQuery = "SELECT * FROM emd_master INNER JOIN emd_detail ON emd_master.emid = emd_detail.emid 
				INNER JOIN tender_register ON emd_master.tr_id = tender_register.tr_id INNER JOIN contractor ON emd_detail.contid = contractor.contid ".$WhereClause."
				ORDER BY emd_master.tr_id ASC, emd_detail.contid ASC";
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
			$EmDDataArr[] = $List;
		}
	}
}
$FinaQuery = "SELECT tr_id FROM bidder_bid_master";
$FinaResult = mysqli_query($dbConn,$FinaQuery);
if(mysqli_num_rows($FinaResult)>0){
	while($List = mysqli_fetch_array($FinaResult)){
		array_push($Finacid,$List['tr_id']);
	}
}


if(isset($_POST['back']))
{
	header('Location: EMDEntry.php');
}
//print_r($EmDDataArr);exit;
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
												<div class="card-header inkblue-card" align="center">&nbsp;EMD Details - View</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th rowspan="2" valign="middle">SNo.</th>
																			<th rowspan="2" valign="middle">Tender Number</th>
																			<th rowspan="2" valign="middle">Name of Work</th>
																			<th rowspan="2" valign="middle">EMD Amount<br>( &#8377; )</th>
																			<th rowspan="2" valign="middle">Bidder Name</th>
																			<th colspan="8" valign="middle">EMD Details</th>
																			<th rowspan="2" valign="middle">Action</th>
																		</tr>
																		<tr>
																			<th valign="middle">Instrument Type</th>
																			<th valign="middle">Instrument No.</th>
																			<th valign="middle">Date of Issue</th>
																			<th valign="middle">Date of Expiry</th>
																			<th valign="middle">Amount<br>( &#8377; )</th>
																			<th valign="middle">Challan<br>No.</th>
																			<th valign="middle">Challan<br>Date.</th>
																			<th valign="middle">Drawee<br>Bank</th>

																		</tr>
																	</thead>
																		<tbody>
																			<?php
																			$SNO = 1; $PrevTrId = ""; $PrevCont = "";
																			if($RowCount == 1){ foreach($EmDDataArr as $Emdkey => $EmdValue){ 
																				$ContRowSpan = $RowSpanArr[$EmdValue->tr_id][$EmdValue->contid];
																				$RowSpanArr1 = $RowSpanArr[$EmdValue->tr_id];
																				$TrRowspan = array_sum($RowSpanArr1);
																				if($PrevTrId != $EmdValue->tr_id){
																					$x = 0; $PrevCont = ""; $y = 0;
																				}
																				if($PrevCont != $EmdValue->contid){
																					$y = 0;
																				}
																				if($x == 0){
																				?>
																					<tr class='labeldisplay'>
																						<td rowspan="<?php echo $TrRowspan; ?>" class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																						<td rowspan="<?php echo $TrRowspan; ?>" valign='middle' class='tdrow' align = 'justify'><?php echo $EmdValue->tr_no; ?></td>
																						<td rowspan="<?php echo $TrRowspan; ?>" valign='middle' class='tdrow' align = 'justify'><?php echo $EmdValue->work_name; ?></td>
																						<td rowspan="<?php echo $TrRowspan; ?>" class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($EmdValue->emd_lot_amt); ?></td>
																						<td rowspan="<?php echo $ContRowSpan; ?>" class='tdrow' align='left' valign='middle'><?php echo $EmdValue->name_contractor; ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php echo $EmdValue->inst_type; ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->inst_no; ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->issue_dt); ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->valid_dt); ?></td>
																						<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($EmdValue->emd_amt); ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->ga_challan_no; ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php if($EmdValue->ga_challan_date != NULL){ echo dt_display($EmdValue->ga_challan_date); }else{ echo ""; }  ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->ga_drawee_bank; ?></td>
																						<td rowspan="<?php echo $TrRowspan; ?>" valign='middle' class='tdrow' >
																						<?php if (in_array($EmdValue->tr_id, $Finacid)){ $BtnName = " View "; ?>
																					    <?php }else{ $BtnName = " View & Edit"; ?>
																					   <?php } ?><a data-url="EMDEntry?id=<?php echo $EmdValue->tr_id; ?>" class=" BtnHref btn btn-info" name="View" id="View"><?php echo $BtnName; ?></a></td>
																					</tr>
																				<?php 
																					$x++; $y++;  $SNO++;
																				}else{
																				?>
																					<tr class='labeldisplay'>
																						<?php if($y == 0){ ?>
																						<td rowspan="<?php echo $ContRowSpan; ?>" class='tdrow' align='left' valign='middle'><?php echo $EmdValue->name_contractor; ?></td>
																						<?php } ?>
																						<td class='tdrow' align='center' valign='middle'><?php echo $EmdValue->inst_type; ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->inst_no; ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->issue_dt); ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php echo dt_display($EmdValue->valid_dt); ?></td>
																						<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($EmdValue->emd_amt); ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->ga_challan_no; ?></td>
																						<td class='tdrow' align='center' valign='middle'><?php if($EmdValue->ga_challan_date != NULL){ echo dt_display($EmdValue->ga_challan_date); }else{ echo ""; }  ?></td>
																						<td class='tdrow' align='left' valign='middle'><?php echo $EmdValue->ga_drawee_bank; ?></td>
																					</tr>
																				<?php 
																					$x++; $y++;
																				}
																			?>
																		<?php $PrevTrId = $EmdValue->tr_id; $PrevCont = $EmdValue->contid; } } ?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
												<div align="center">
												<input type="submit" class="btn btn-info" name="back" value="Back">
													<!--<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" />-->
												</div>
												<div class="clearrowsm">&nbsp;</div>
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
$(document).ready(function(){ 
	$('#example').DataTable({"paging":false,"ordering": false});
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