<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Committed Expenditure View';
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
$CCNumArr = array();
$WrkNameArr = array();
$WorksSqlResult = "SELECT globid,ccno,work_name FROM works WHERE active = 1";
$WorksSqlResultSql = mysqli_query($dbConn,$WorksSqlResult);
if($WorksSqlResultSql == true){
	if(mysqli_num_rows($WorksSqlResultSql)>0){
		while($WRList = mysqli_fetch_object($WorksSqlResultSql)){
			$CCNumArr[$WRList->globid] = $WRList->ccno;
			$WrkNameArr[$WRList->globid] = $WRList->work_name;
		}
	}
}
$ComExpResult = "SELECT * FROM budget_expenditure WHERE active = 1";
$ComExpResultSql = mysqli_query($dbConn,$ComExpResult);

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
//print_r($QuoteAmtArr);


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
												<div class="card-header inkblue-card" align="center"> Committed Expenditure - View </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle"> SNo. </th>
																			<th valign="middle"> CC Number - Name of Work </th>
																			<th valign="middle"> Financial Year </th>
																			<th valign="middle" nowrap=""> Total Committed Amount <br> ( &#8377; ) </th>
																			<th valign="middle"> Action </th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr class='labeldisplay'>
																			<?php
																				$SNO = 1; 
																				if($ComExpResultSql == true){
																					if(mysqli_num_rows($ComExpResultSql)>0){
																						while($DepList = mysqli_fetch_object($ComExpResultSql)){
																							$GlobIdVal	= $DepList->globid;
																							$SHIdVal 	= $DepList->sheetid;
																							$FinYearVal	= $DepList->fin_year;
																							$CCnoVal 	= $DepList->cc_no;

																							$aprilVal	= $DepList->april;
																							$mayVal 		= $DepList->may;
																							$juneVal 	= $DepList->june;
																							$julyVal 	= $DepList->july;
																							$augVal 		= $DepList->aug;
																							$sepVal 		= $DepList->sep;
																							$octVal 		= $DepList->oct;
																							$novVal 		= $DepList->nov;
																							$deceVal 	= $DepList->dece;
																							$janVal 		= $DepList->jan;
																							$febVal 		= $DepList->feb;
																							$marchVal	= $DepList->march;

																							$TotalVal 		 = $aprilVal+$mayVal+$juneVal+$julyVal+$augVal+$sepVal+$octVal+$novVal+$deceVal+$janVal+$febVal+$marchVal;
																							$ComEstWorkName = $WrkNameArr[$GlobIdVal];
																							$CCNumber 		 = $CCNumArr[$GlobIdVal];
																			?>
																			<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $CCNumber; echo " - "; echo $ComEstWorkName; ?></td>
																			<td valign='middle' class='tdrow' align = 'center'><?php echo $FinYearVal; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($TotalVal); ?></td>
																			<td align='center' valign='middle' class='tdrow' >
																				<a data-url="CommittedExpenditureUpdate?id=<?php echo $GlobIdVal; ?>&fyr=<?php echo $FinYearVal; ?>" class=" BtnHref btn btn-info" name="View" id="View">
																					Edit
																				</a>
																			</td>
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
															<!-- <input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" /> -->
															<a data-url="CommittedExpenditureUpdate" class="btn btn-info" name="view" id="view">Back</a>
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
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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