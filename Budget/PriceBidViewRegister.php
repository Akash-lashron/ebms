<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Financial Bid Register';
$msg = "";
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
	header('Location: PriceBidUploadGenerate.php');
}
if($_SESSION['isadmin'] == 1){
	$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
}else{
	$sheetquery = "SELECT * FROM tender_register WHERE (eic = '$staffid' OR created_by = '$UserId') ORDER BY tr_id ASC";
}
//$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
$sheetsqlquery = mysqli_query($dbConn,$sheetquery);

$TsNumArr=array();
$TecSancquery = "SELECT ts_id,ts_no FROM technical_sanction ORDER BY ts_id ASC";
//$sheetquery = "SELECT * FROM tender_register ORDER BY tr_id ASC";
$TecSancquerysql = mysqli_query($dbConn,$TecSancquery);
if($TecSancquerysql == true){
	if(mysqli_num_rows($TecSancquerysql) > 0){
		while($TsList = mysqli_fetch_object($TecSancquerysql)){
			$TsNo = $TsList->ts_no;
			$TsNumArr[$TsList->ts_id] = $TsList->ts_no;
		}
	}
}
//print_r($TsNumArr);
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
												<div class="card-header inkblue-card" align="center"> Financial Bid - Register </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">																
																<table class="table table-striped table-bordered table2excel" id="example">
																	<thead>
																		<tr>
																			<th rowspan="2" class="table-plus datatable-nosort" style="text-align:center">SNo.</th>
																			<th rowspan="2">Technical Sanction Number</th>
																			<th rowspan="2">Tender Number</th>
																			<th rowspan="2">Name of Work</th>
																			<th rowspan="2">Tender Estimate Amount<br>( &#x20B9; )</th>
																			<th colspan="3">Bidder Quoted Details</th>
																			<!-- <th rowspan="2">CC No.</th> -->
																			<!--<th rowspan="2">CST Status</th>-->
																			<!-- <th rowspan="2">Action</th> -->
																		</tr>
																		<tr>
																			<th>Bid Status</th>
																			<th>Bidder Name</th>
																			<th>Quoted Amount<br>( &#x20b9; )</th>
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
																					$SelectBiddersQuery = "SELECT a.*, b.name_contractor FROM bidder_bid_master a INNER JOIN contractor b ON (a.contid = b.contid) WHERE a.tr_id=$TendrId";
																					$SelectBiddersSql = mysqli_query($dbConn,$SelectBiddersQuery);
																					if($SelectBiddersSql == true){
																						$RowSpan = mysqli_num_rows($SelectBiddersSql);
																						if(mysqli_num_rows($SelectBiddersSql)>0){
																							while($BidList = mysqli_fetch_object($SelectBiddersSql)){
																								$ContDataArr[$TendrId][$BidList->contid][0] = $BidList->name_contractor;
																								$ContDataArr[$TendrId][$BidList->contid][1] = $BidList->is_negotiate;
																								$ContDataArr[$TendrId][$BidList->contid][2] = $BidList->quoted_amt_af_reb;
																								$ContDataArr[$TendrId][$BidList->contid][3] = $BidList->quoted_amt_af_neg;
																								if($BidList->quoted_amt_af_neg == 'Y'){
																									$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_neg;
																								}else{
																									$ContPosiArr[$BidList->contid] = $BidList->quoted_amt_af_reb;
																								}
																							}
																						}
																					} 
																					asort($ContPosiArr); $L = 1;
																					foreach($ContPosiArr as $ContKey => $ContValue){
																						if($x == 0){ ?>
																							<tr>
																								<td rowspan="<?php echo $RowSpan; ?>" class="table-plus" align="center"><?php echo $SNO; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $TsNumArr[$List->ts_id]; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->tr_no; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>"><?php echo $List->work_name; ?></td>
																								<td rowspan="<?php echo $RowSpan; ?>" align="right"><?php echo IndianMoneyFormat($DeptList->partA_amount); ?></td>
																								<td style="text-align:center"><?php echo "L".$L; ?></td>
																								<td><?php echo $ContDataArr[$TendrId][$ContKey][0]; ?></td>
																								<td style="text-align:right">
																									<?php 
																										if($ContDataArr[$TendrId][$ContKey][1] == 'Y'){ 
																											echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][3]); 
																										}else{
																											echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][2]); 
																										} 
																									?>
																								</td>
																								<!-- <td rowspan="<?php echo $RowSpan; ?>"><input type="text" class="tboxclass" id="txt_ccno_ent<?php //echo $List->tr_id; ?>" name="txt_ccno_ent[]"></td> -->
																								<input type="hidden" class="tboxclass" id="txt_hid_tr_id<?php echo $List->tr_id; ?>" name="txt_hid_tr_id[]" value="<?php echo $List->tr_id; ?>">
																								<input type="hidden" class="tboxclass" id="txt_cst_nego_id<?php echo $List->tr_id; ?>" name="txt_cst_nego_id[]" value="<?php echo $$ContDataArr[$TendrId][$ContKey][1]; ?>">
																								
																								<!--<td rowspan="<?php //echo $RowSpan; ?>"><span class="badge badge-success"><?php //if((($List->cst_status) || ($List->nego_status)) == 'A'){ echo "WAITING FOR CONFIRMATION"; } ?></span></td>-->
																								<!-- <td valign='middle' align="center">
																									<input type="button" data-url="PriceBidViewRsp?id=<?php //echo $List->tr_id; ?>&contid=<?php //echo $ContKey; ?>" class="BtnHref btn btn-info" Value="View Bid Details">
																								</td>	-->
																							</tr>
																							<?php $x++; $SNO++; 
																						}else{ ?>
																							<tr>
																								<td style="text-align:center"><?php echo "L".$L; ?></td>
																								<td><?php echo $ContDataArr[$TendrId][$ContKey][0]; ?></td>
																								<td style="text-align:right"><?php if($ContDataArr[$TendrId][$ContKey][1] == 'Y'){ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][3]); }else{ echo IndianMoneyFormat($ContDataArr[$TendrId][$ContKey][2]); } ?></td>
																								<!-- <td valign='middle' align="center"><input type="button" data-url="PriceBidViewRsp?id=<?php //echo $List->tr_id; ?>&contid=<?php //echo $ContKey; ?>" class="BtnHref btn btn-info" Value="View Bid Details"></td> -->
																							</tr>
																							<?php $x++; 
																						}
																						$L++;
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
														<div align="center">
															<!-- <input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="SAVE" /> -->
															<!-- <input type="submit" class="btn btn-info" name="btn_back" id="btn_back" value="BACK" /> -->
															<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
															<a data-url="Home" class="btn btn-info" name="btn_back" id="btn_back"> Back </a>
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
$(document).on("click",".BtnHref", function(e){
	var DatUrl = $(this).attr("data-url");
	var SplitUrl = DatUrl.split("?");
	var Len = SplitUrl.length;
	if(Len > 0){
		if(Len == 1){
			var Url = SplitUrl[0]+".php";
		}else{
			var Url = SplitUrl[0]+".php?"+SplitUrl[1];
		}
		window.location.href = Url;
	}
});
$("#exportToExcel").click(function(e){ 
	var table = $('body').find('.table2excel');
	if(table.length){ 
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: "Financial Bid Register-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
		});
	}
});
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
