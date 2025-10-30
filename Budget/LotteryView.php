<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';			// 11-11-2022 COMMENTED LINE
include "common.php";
checkUser();
$msg = '';  $PageId = 0;
$PageName = $PTPart1.$PTIcon.'Lottery - View';


function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}

//		Part To Show Works - STARTS HERE		//
$sheet = '';
$TrArr = array();
$sheetquery1 = "SELECT bmid,tr_id,quoted_pos FROM bidder_bid_master WHERE is_lottery='Y' GROUP BY tr_id ASC";
$sheetsqlquery1 = mysqli_query($dbConn,$sheetquery1);
if ($sheetsqlquery1 == true )
{
	while($row1 = mysqli_fetch_array($sheetsqlquery1))
	{
		$TrId1 = $row1['tr_id'];
		array_push($TrArr,$TrId1);
	}
	$ImplodeTrArr = implode(',',$TrArr);
	if($ImplodeTrArr != null ){
		if($_SESSION['isadmin'] == 1){
			$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") ORDER BY tr_id ASC";
		}else{
			$sheetquery = "SELECT * FROM tender_register WHERE tr_id IN(".$ImplodeTrArr.") AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."') ORDER BY tr_id ASC";
		}
		$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
	}
}
//		Part To Show Works - ENDS HERE		//
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "MyView.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form name="form" method="post" action="LotteryGenerate.php">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div12">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">Lottery - View</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<table class="table table-striped table-bordered" id="example">
														<thead>
															<tr>
																<th rowspan="2" class="table-plus datatable-nosort" style="text-align:center">SNo.</th>
																<th rowspan="2">Tender No.</th>
																<th rowspan="2">Name of Work</th>
																<th rowspan="2">Tender Estimate</th>
																<th colspan="3">Bidder Quoted Details</th>
																<!-- <th rowspan="2">CC No.</th> -->
																<!--<th rowspan="2">CST Status</th>-->
																<!-- <th rowspan="2">Action</th> -->
															</tr>
															<tr>
																<th>Status</th>
																<th>Bidder Name</th>
																<th>Quoted Amount (&#x20b9;)</th>
															</tr>
														</thead>
														<tbody>
															<?php 
															$SNO = 1;
															if($sheetsqlquery == true){
																if(mysqli_num_rows($sheetsqlquery)>0){
																	while($List=mysqli_fetch_object($sheetsqlquery)){
																		$TendrId = $List->tr_id;
																		$RowSpan = 1; $x = 0; $ContDataArr = array(); $ContPosiArr = array();
																		$DeptEstQuery = "SELECT * FROM partab_master WHERE tr_id =$TendrId";
																		$DeptEstQuerySql = mysqli_query($dbConn,$DeptEstQuery);
																		$DeptList = mysqli_fetch_object($DeptEstQuerySql);
																		$SelectBiddersQuery = "SELECT a.*, b.name_contractor FROM bidder_bid_master a INNER JOIN contractor b ON (a.contid = b.contid) WHERE a.tr_id=$TendrId ORDER BY a.quoted_pos ASC";
																		$SelectBiddersSql = mysqli_query($dbConn,$SelectBiddersQuery);
																		if($SelectBiddersSql == true){
																			$RowSpan = mysqli_num_rows($SelectBiddersSql);
																			if(mysqli_num_rows($SelectBiddersSql)>0){
																				while($BidList = mysqli_fetch_object($SelectBiddersSql)){
																					$ContDataArr[$TendrId][$BidList->contid][0] = $BidList->name_contractor;
																					$ContDataArr[$TendrId][$BidList->contid][1] = $BidList->is_negotiate;
																					$ContDataArr[$TendrId][$BidList->contid][2] = $BidList->quoted_amt_af_reb;
																					$ContDataArr[$TendrId][$BidList->contid][3] = $BidList->quoted_amt_af_neg;
																					$ContDataArr[$TendrId][$BidList->contid][4] = $BidList->quoted_pos;
																					if($BidList->quoted_amt_af_neg == 'Y'){
																						$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_neg;
																					}else{
																						$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_reb;
																					}
																				}
																			}
																		} 
																		//	asort($ContPosiArr); 	$L = 1;
																		foreach($ContPosiArr as $ContKey => $ContValue){
																			if($x == 0){ ?>
																				<tr>
																					<td rowspan="<?php echo $RowSpan; ?>" class="table-plus" align="center"><?php echo $SNO; ?></td>
																					<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->tr_no; ?></td>
																					<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->work_name; ?></td>
																					<td rowspan="<?php echo $RowSpan; ?>" align="right"><?php echo IndianMoneyFormat($DeptList->partA_amount); ?></td>
																					<td style="text-align:center"><?php echo "L".$ContDataArr[$TendrId][$ContKey][4]; ?></td>
																					<td><?php echo $ContDataArr[$TendrId][$ContKey][0]; ?></td>
																					<td style="text-align:right"><?php if($ContDataArr[$TendrId][$ContKey][1] == 'Y'){ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][3]); }else{ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][2]); } ?></td>
																					
																					<!-- <td rowspan="<?php echo $RowSpan; ?>"><input type="text" class="tboxclass" id="txt_ccno_ent<?php echo $List->tr_id; ?>" name="txt_ccno_ent[]"></td> -->
																					<input type="hidden" class="tboxclass" id="txt_hid_tr_id<?php echo $List->tr_id; ?>" name="txt_hid_tr_id[]" value="<?php echo $List->tr_id; ?>">
																					<input type="hidden" class="tboxclass" id="txt_cst_nego_id<?php echo $List->tr_id; ?>" name="txt_cst_nego_id[]" value="<?php echo $$ContDataArr[$TendrId][$ContKey][1]; ?>">
																					
																					<!--<td rowspan="<?php echo $RowSpan; ?>"><span class="badge badge-success"><?php //if((($List->cst_status) || ($List->nego_status)) == 'A'){ echo "WAITING FOR CONFIRMATION"; } ?></span></td>-->
																					<!-- <td rowspan="<?php echo $RowSpan; ?>" align="center"><input type="button" data-url="CSTView?id=<?php echo $List->tr_id; ?>" class="BtnHref btn btn-info" Value="<?php echo "View CST"; ?> "></td> -->
																					</tr>
																				<?php $x++; $SNO++; 
																			}else{ ?>
																				<tr>
																				<td style="text-align:center"><?php echo "L".$ContDataArr[$TendrId][$ContKey][4]; ?></td>
																					<td><?php echo $ContDataArr[$TendrId][$ContKey][0]; ?></td>
																					<td style="text-align:right"><?php if($ContDataArr[$TendrId][$ContKey][1] == 'Y'){ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][3]); }else{ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][2]); } ?></td>
																				</tr>
																				<?php $x++; 
																			}
																			//	$L++;
																		}
																		?>
																<?php 
																	} 
																} 																			
															} ?>
														</tbody>
													</table>
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
		<!--==============================footer========================-->
    <?php include "footer/footer.html"; ?>
	<script>
		$("#cmb_shortname").chosen();
		$('#cmb_bidder').chosen();
		$(document).ready(function(){ 
			$("body").on("change","#cmb_shortname", function(event){
				var Id = $(this).val();
				$("#txt_work_name").val('');
				$('#cmb_bidder').chosen('destroy');
				$('#cmb_bidder').children('option:not(:first)').remove();
				$("#cmb_bidder").chosen();
				$.ajax({
					type: 'POST',
					url: 'GetLotteryBidders.php',
					data: { MastId: Id, Page: 'LOTBID'},
					dataType: 'json',
					success: function (data) { 				//alert(JSON.stringify(data['ContArr']));
						var ContArr = data['ContArr']; 		// alert(ContArr);
						var ContL1  = data['ContL1']; 
						$('#cmb_bidder').chosen('destroy');
						var MyArr = {};
						$.each(ContArr, function(index, value) {
							$("#cmb_bidder").append('<option value="'+value.contid+'">'+value.contname+'</option>');
							MyArr[value.contid] = value.contname;
						});
							//alert(JSON.stringify(MyArr));
						$('#txt_all_bid').val(JSON.stringify(MyArr));
						$("#cmb_bidder").chosen();
					}
				});
			});
		});
	</script>
    </body>
</html>

