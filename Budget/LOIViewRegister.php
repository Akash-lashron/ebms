<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'LOI Register';
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
	
	return $dd .'-'. $mm .'-'.$yy;
} 
$RowCount = 0;    
$Finacid  = array();                   
//$result = mysqli_query($dbConn," SELECT a.*,b.* FROM technical_sanction a INNER JOIN hoa b ON a.hoaid = b.hoa_id  ORDER BY a.ts_id ASC");
$HoaArr = array();
$QuoteAmtArr = array();
if($_SESSION['isadmin'] == 1){
	$result = "SELECT a.*,b.tr_no,b.work_name,b.ccno,c.name_contractor FROM loi_entry a 
	INNER JOIN tender_register b ON (a.tr_id = b.tr_id) INNER JOIN contractor c ON (a.contid = c.contid) ORDER BY a.loa_pg_id ASC";
}else{
	$result = "SELECT a.*,b.tr_no,b.work_name,b.ccno,c.name_contractor FROM loi_entry a 
	INNER JOIN tender_register b ON (a.tr_id = b.tr_id) INNER JOIN contractor c ON (a.contid = c.contid) WHERE 
	(b.eic = '".$_SESSION['sid']."' OR b.created_by = '".$_SESSION['userid']."') ORDER BY a.loa_pg_id ASC";
}

$MasterResult = mysqli_query($dbConn,$result);
if($MasterResult == true){
	if(mysqli_num_rows($MasterResult)>0){
		$RowCount = 1;
	}
}
if($RowCount == 1){
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
	$FinaQuery = "SELECT tr_id FROM sheet ";
	$FinaResult = mysqli_query($dbConn,$FinaQuery);
	if(mysqli_num_rows($FinaResult)>0){
		while($List = mysqli_fetch_array($FinaResult)){
			array_push($Finacid,$List['tr_id']);
		}
	}
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
}

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
									<!-- <div class="div1">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">LOI - Register</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<table width="100%" align="center" class="dataTable table2excel mgtb-8">
																	<thead>
																		<tr>
																			<th valign="middle" nowrap="nowrap">SNo.</th>
																			<th valign="middle" nowrap="nowrap">Tenchical Sanction<br>Number</th>
																			<th valign="middle" nowrap="nowrap">Tender Number</th>
																			<th valign="middle" nowrap="nowrap">CC Number</th>
																			<th valign="middle" nowrap="nowrap">Name of Work</th>
																			<th valign="middle" nowrap="nowrap">Bidder Name</th>
																			<th valign="middle" nowrap="nowrap">Quoted Amount<br>( &#8377; )</th>
																			<th valign="middle" nowrap="nowrap">PG (%)</th>
																			<th valign="middle" nowrap="nowrap">PG Value<br>( &#8377; )</th>
																			<th valign="middle" nowrap="nowrap">LOI Number</th>
																			<th valign="middle" nowrap="nowrap">LOI Date</th>
																			<!-- <th valign="middle" nowrap="nowrap">Action</th> -->
																		</tr>
																	</thead>
																	<tbody>
																			<?php
																				$SNO = 1; 
																				if($RowCount == 1){
																					while($List = mysqli_fetch_object($MasterResult)){
																					$TrIdVal = $List->tr_id;
																					$ContIdVal = $List->contid;
																					$PGVal =round(($List->pg_amt),0);
																					$QuoteAmtEch = $QuoteAmtArr[$TrIdVal][$ContIdVal];
																			?>
																		<tr class='labeldisplay'>
																			<td valign='middle' class='tdrow' align = 'center'><?php echo $SNO; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $TsNumArr[$List->tr_id]; ?></td>  
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->tr_no; ?></td>  
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->ccno; ?></td> 
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->work_name; ?></td>
																			<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->name_contractor; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat( $QuoteAmtEch); ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo $List->pg_per; ?></td>
																			<td class='tdrow' align='right' valign='middle'><?php echo IndianMoneyFormat($PGVal); ?></td>
																			<td class='tdrow' align='left' valign='middle'><?php echo $List->loa_no; ?></td>
																			<td class='tdrow' align='center' valign='middle'><?php echo dt_display($List->loa_dt); ?></td>
																			<!--	<td valign='middle' class='tdrow' >
																				<?php //if (in_array($List->tr_id, $Finacid)){ $BtnName = " View "; ?>
																				<?php //}else{ $BtnName = " View & Edit"; ?>
																				<?php //} ?><a data-url="LOIEntry?id=<?php //echo $List->loa_pg_id; ?>" class=" BtnHref btn btn-info" name="View" id="View"><?php //echo $BtnName; ?></a>
																			</td>	-->
																		</tr>
																			<?php 
																			 		$SNO++; 
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
									<!-- <div class="div1">&nbsp;</div> -->
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
				filename: "LOI Register-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
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