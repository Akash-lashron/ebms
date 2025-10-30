<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'CST Confirm And CCNO. Creation';
$msg = "";


// if(isset($_POST["btn_save"])){
// 	$CCNOEntArr  =	$_POST['txt_ccno_ent'];
// 	$HidTrIdArr  =	$_POST['txt_hid_tr_id'];
// 	$IsNegoArr   =	$_POST['txt_cst_nego_id'];
// 	//echo !empty($CCNOEntArr);exit;
// 	/*if(!empty($CCNOEntArr)){
// 		print_r($CCNOEntArr);exit;
// 		//echo 1;exit;
// 	}else{
// 		echo 2;exit;
// 	}*/
// 	//echo count($CCNOEntArr);exit;
// 	//if(count($CCNOEntArr) != 0){
// 	foreach($HidTrIdArr as $key => $value){
// 		$CCNumb 		= $CCNOEntArr[$key];
// 		$IsNego 		= $IsNegoArr[$key];
// 		$INSERTQuery 	= "UPDATE works SET ccno = $CCNumb WHERE tr_id = $value";
// 		//echo $INSERTQuery;exit;
// 		$INSERTQuerysql = mysqli_query($dbConn,$INSERTQuery);
// 		if($IsNego == 'Y'){
// 			$TenRegQuery = "UPDATE tender_register SET ccno = $CCNumb, nego_status ='C' WHERE tr_id = $value";
// 		}else{
// 			$TenRegQuery = "UPDATE tender_register SET ccno=$CCNumb, cst_status ='C' , cst_acc_status='C' WHERE tr_id = $value";
// 		}
// 		$TenRegQuerysql = mysqli_query($dbConn,$TenRegQuery);
// 	}
// 	//}
	
// 	if($TenRegQuerysql == true){
//         $msg = "CCNO. Assigned Successfully";
// 		$success = 1;
//     }else{
// 		$msg = "Error: CCNO. Not Assigned...!!! ";
// 	}
// 	//echo $value;exit;
// }
if(isset($_POST['btn_back'])){
	header('Location: Home.php');
}
$sheetquery = "SELECT * FROM tender_register WHERE nego_status ='A' ORDER BY tr_id ASC";
//$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
$sheetsqlquery = mysqli_query($dbConn,$sheetquery);
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
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
						<!-- <div align="right" class="users-icon-part">&nbsp;</div> -->
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Negotiation CST Confirm</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<!--<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>Description</th>
																			<th>Amount (&#x20b9; in Lakhs)</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>Project Sanctioned Amount</td>
																			<td id="PsAmt" align="right"></td>
																		</tr>
																		<tr>
																			<td>Total Action Taken</td>
																			<td id="TotActTaken" align="right"></td>
																		</tr>
																		<tr>
																			<td>Total Committed amount</td>
																			<td id="TotCommAmt" align="right"></td>
																		</tr>
																		<tr>
																			<td>Actual Expenditure up to date</td>
																			<td id="ActExpUpDt" align="right"></td>
																		</tr>
																		<tr>
																			<td>Financial Progress %</td>
																			<td id="FinPro" align="right"></td>
																		</tr>
																		<tr>
																			<td>Physical progress %</td>
																			<td id="PhyPro" align="right"></td>
																		</tr>
																	</tbody>
																</table>-->
																
																<table class="table table-striped table-bordered" id="example">
																	<thead>
																		<tr>
																			<th rowspan="2" class="table-plus datatable-nosort" style="text-align:center">SNo.</th>
																			<th rowspan="2">CCNo.</th>
																			<th rowspan="2">Tender No.</th>
																			<th rowspan="2">Name of Work</th>
																			<th rowspan="2">Tender Estimate</th>
																			<th rowspan="2">Negotiated Estimate</th>
																			<th colspan="3">Bidder Quoted Details</th>
																			<!-- <th rowspan="2">CC No.</th> -->
																			<!--<th rowspan="2">CST Status</th>-->
																			<th rowspan="2">Action</th>
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
																								if($BidList->is_negotiate == 'Y'){
																									$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_neg;
																								}else{
																									$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_reb;
																								}
																							}
																						}
																					} 
																					//	asort($ContPosiArr); $L = 1;
																					foreach($ContPosiArr as $ContKey => $ContValue){
																						if($x == 0){ ?>
																							<tr>
																								<td rowspan="<?php echo $RowSpan; ?>" class="table-plus" align="center"><?php echo $SNO; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->ccno; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->tr_no; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->work_name; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>" align="right"><?php echo IndianMoneyFormat($DeptList->partA_amount); ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>" align="right"><?php echo IndianMoneyFormat($ContPosiArr[$ContKey]); ?></td>
																								<td style="text-align:center"><?php echo "L".$ContDataArr[$TendrId][$ContKey][4]; ?></td>
																								<td><?php echo $ContDataArr[$TendrId][$ContKey][0]; ?></td>
																								<td style="text-align:right"><?php if($ContDataArr[$TendrId][$ContKey][1] == 'Y'){ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][3]); }else{ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][2]); } ?></td>
																								
																								<!-- <td rowspan="<?php echo $RowSpan; ?>"><input type="text" class="tboxclass" id="txt_ccno_ent<?php echo $List->tr_id; ?>" name="txt_ccno_ent[]"></td> -->
																								<input type="hidden" class="tboxclass" id="txt_hid_tr_id<?php echo $List->tr_id; ?>" name="txt_hid_tr_id[]" value="<?php echo $List->tr_id; ?>">
																								<input type="hidden" class="tboxclass" id="txt_cst_nego_id<?php echo $List->tr_id; ?>" name="txt_cst_nego_id[]" value="<?php echo $$ContDataArr[$TendrId][$ContKey][1]; ?>">
																								
																								<!--<td rowspan="<?php echo $RowSpan; ?>"><span class="badge badge-success"><?php //if((($List->cst_status) || ($List->nego_status)) == 'A'){ echo "WAITING FOR CONFIRMATION"; } ?></span></td>-->
																								<td rowspan="<?php echo $RowSpan; ?>" align="center"><input type="button" data-url="CSTView?id=<?php echo $List->tr_id; ?>" class="BtnHref btn btn-info" Value="<?php if($List->nego_status == 'A'){ echo "View Negotiation-CST"; }?> "></td>
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
																<div align="center">
																		<!-- <input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="SAVE" /> -->
																		<input type="submit" class="btn btn-info" name="btn_back" id="btn_back" value="BACK" />
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
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != "") {
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
					url = "Home.php";
					window.location.replace(url);
					}
			});
		}
	};
	$('#example').DataTable();
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 3px 4px;
	}
	.modal-dialog {
		width: 100%;
		margin: 3px;
	}
	.modal{
		box-sizing:border-box;
		padding-right: 12px !important;
	}
	div.dt-buttons{
		padding-left: 5px;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	.modal-header{
		padding: 6px;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		font-size: 13px;
	}
	.close{
		font-size: 16px;
	}
	th.tabtitle{
		text-align:left !important;
	}
</style>
