<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Department Estimate Register';
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
$TenNumArr = array();
$WrkNameArr = array();
if($_SESSION['isadmin'] == 1){
	$TenRegResult = "SELECT * FROM tender_register WHERE active = 1";
}else{
	$TenRegResult = "SELECT * FROM tender_register WHERE active = 1 AND (eic = '".$_SESSION['sid']."' OR created_by = '".$_SESSION['userid']."')";
}
$TrIdArr = array();
$TsNumArr = array();
$TenRegResultSql = mysqli_query($dbConn,$TenRegResult);
if($TenRegResultSql == true){
	if(mysqli_num_rows($TenRegResultSql)>0){
		while($TRList = mysqli_fetch_object($TenRegResultSql)){
			$TenNumArr[$TRList->tr_id] = $TRList->tr_no;
			$WrkNameArr[$TRList->tr_id] = $TRList->work_name;
			$GlobID = $TRList->globid;
			array_push($TrIdArr,$TRList->tr_id);
			$TecSancquery = "SELECT globid,ts_no FROM technical_sanction WHERE globid='$GlobID'";
			$TecSancquerysql = mysqli_query($dbConn,$TecSancquery);
			if($TecSancquerysql == true){
				if(mysqli_num_rows($TecSancquerysql) > 0){
					$TsList = mysqli_fetch_object($TecSancquerysql);
					$TsNo = $TsList->ts_no;
					$TsNumArr[$TsList->globid] = $TsList->ts_no;
					$GlobIdArr = $TsList->globid;
				}
			}
		}
	}
}
if(count($TrIdArr)>0){
	$TrIdStr = implode(",",$TrIdArr);
}else{
	$TrIdStr = '';
}
$DepEstResult = "SELECT * FROM partab_master WHERE tr_id IN ($TrIdStr) ORDER BY created_date DESC";
$DepEstResultSql = mysqli_query($dbConn,$DepEstResult);
//echo $DepEstResult;

/*
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
*/
//$result = mysqli_query($dbConn," SELECT a.*,b.* FROM technical_sanction a INNER JOIN hoa b ON a.hoaid = b.hoa_id  ORDER BY a.ts_id ASC");
/*
$HoaArr = array();
$QuoteAmtArr = array();
$result = "SELECT a.*,  b.tr_no, b.work_name, b.ccno, c.name_contractor FROM loi_entry a 
			INNER JOIN tender_register b ON (a.tr_id = b.tr_id) 
			INNER JOIN contractor c ON (a.contid = c.contid) ORDER BY a.loa_pg_id ASC";
$MasterResult = mysqli_query($dbConn,$result);

$SelectQuery1 = "select * from bidder_bid_master";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($List = mysqli_fetch_object($SelectSql1)){
			$TrId = $List->tr_id;
			$ContId = $List->contid;
			$IsNego = $List->is_negotiate;
			$QuoteAmt = $List->quoted_amt;
			$RebatePerc = $List->rebate_perc;
			$NegoRebatePerc = $List->negotiate_rebate_perc;
			$RebateAmt = $QuoteAmt * $RebatePerc / 100;
			$TotalQuote = round(($QuoteAmt - $RebateAmt),0);
			$NegRebateAmt = $TotalQuote * $NegoRebatePerc / 100;
			$TotalamtafterNeg= round(($TotalQuote - $NegRebateAmt),0);
			if($IsNego == 'Y'){
				$QuoteAmtArr[$TrId][$ContId]=  $TotalamtafterNeg;
			}else{
				$QuoteAmtArr[$TrId][$ContId]=  $TotalQuote;
			}		
		}
	}
}
*/


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
												<div class="card-header inkblue-card" align="center"> Department Estimate - Register </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle">SNo.</th>
																			<th valign="middle">Technical Sanction Number</th>
																			<th valign="middle"> Tender Number </th>
																			<th valign="middle"> Name of Work </th>
																			<th valign="middle" nowrap=""> Estimate Amount <br> ( &#8377; ) </th>
																			<th valign="middle"> Estimate Upload Date </th>
																			<!-- <th valign="middle"> Action </th> -->
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																			$SNO = 1; 
																			if($DepEstResultSql == true){
																				if(mysqli_num_rows($DepEstResultSql)>0){
																					while($DepList = mysqli_fetch_object($DepEstResultSql)){
																						$TrIdVal 	 	= $DepList->tr_id;
																						$WorkNameVal 	= $DepList->work_name;
																						$DepWorkName 	= $WrkNameArr[$TrIdVal];
																						$DepTRNumber 	= $TenNumArr[$TrIdVal];
																						$CreateDateTime = $DepList->created_date;
																						//$splitCreateDateTimeStamp = explode(" ",$CreateDateTime);
																						//$CreateDate = $splitCreateDateTimeStamp[0];
																						//echo $CreateDate;
																						if($CreateDateTime != '0000-00-00 00:00:00'){
																							$splitCreateDateTimeStamp = explode(" ",$CreateDateTime);
																							$CreateDate = $splitCreateDateTimeStamp[0];
																							//echo $CreateDate;exit;
																							//$CreateDate 	= date('Y-m-d',strtotime($CreateDateTime)); //($CreateDateTime);
																							if(($CreateDate != NULL)&&($CreateDate != "")){
																								$EstCreateDate = dt_display($CreateDate);
																							}else{
																								$EstCreateDate = "";
																							}
																						}else {
																							$EstCreateDate = "";
																						}
																						//$EstCreateDate = dt_display($DepList->created_date);
																						$EstCreateBy	= $DepList->created_by;
																						$EstimateAmt 	= $DepList->partA_amount;
																		?>
																		<tr class='labeldisplay'>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td class='tdrowbold' valign='middle' align='left'><?php echo $TsNumArr[$DepList->globid]; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $DepTRNumber; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $DepWorkName; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($EstimateAmt); ?></td>
																			<td valign='middle' class='tdrow' align = 'center'><?php echo $EstCreateDate; ?></td>
																			<!--	<td align='center' valign='middle' class='tdrow' >
																				<a data-url="DeptEstimateView?id=<?php //echo $TrIdVal; ?>" class=" BtnHref btn btn-info" name="View" id="View">
																					View Details
																				</a>
																			</td>	-->
																		</tr>
																		<?php $SNO++; 
																						} 
																					} 
																				} 
																		?>
																   </tbody>
																</table>
															</div>
														</div>
														<div align="center">
															<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
															<a data-url="Home" class="btn btn-info" name="view" id="view">Back</a>
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
				filename: "DeptEst Register-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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