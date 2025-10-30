<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'GST Statement';
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
$msg = ""; $RowCount = 0;
$IsEdit = 0; $report = false;
//if(isset(['btn_view'])){
	$MobAdvAmt = $_GET["mobadvamt"];
	$SheetVal = $_GET["sheetid"];
	//$SheetVal = 8;
	//echo $SheetVal;exit;
	//echo 1;exit;
	$SheetSelQuery= "SELECT a.*,b.name_contractor FROM sheet a INNER JOIN contractor b ON (a.contid=b.contid) WHERE a.sheet_id='$SheetVal'";
	$SheetSelQuerySql= mysqli_query($dbConn,$SheetSelQuery);

//}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	//function noBack() { window.history.forward(); }

	function printPage()
	{
		var printContents = document.getElementById('printSection').innerHTML;
		//document.getElementById("hide_print_row1").className = "hide";
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		//document.getElementById("hide_print_row1").className = "";
		document.body.innerHTML = originalContents;
	}
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
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<!-- <div class="div2">&nbsp;</div> -->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">INTEREST CALCULATION SHEET - MOB. ADVANCE</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv group-div" id="table-stmt">
																<div class="row clearrow"></div>
																
																<?php if($SheetSelQuerySql == true){ 
																	if(mysqli_num_rows($SheetSelQuerySql)>0){ 
																		$List = mysqli_fetch_object($SheetSelQuerySql); ?>
																		<div class="dataTable" style="width:100%; text-align:left;">
																			<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">CCNo. <?php echo $List->computer_code_no; ?></font> &nbsp; &nbsp;<b>Name of Work</b> : <?php echo $List->work_name; ?>
																		</div>
																		<div class="row clearrow"></div>
																		<!--<table width="100%" class="dataTable head1 tables table-bordered">
																			<thead>
																				<tr class="head"><th style="text-align:left !important" colspan="5">&nbsp;Personal Details</th></tr>
																			</thead>
																			<tbody>	
																				<tr>
																					<th colspan="6" class="" style=" word-wrap:break-word; text-align:left !important">&nbsp;Name Of Work &emsp;&emsp;&emsp;&emsp;&emsp; : &emsp;<?php echo $List->work_name; ?></th>
																				</tr>
																				<tr>
																					<th style="text-align:left !important">&nbsp;Work Order Number &emsp;&emsp; : &emsp;<?php echo $List->work_order_no; ?></th>
																					<th colspan="3" style="text-align:left !important">&nbsp;Aggrement Number &emsp;&emsp;&emsp; : &emsp;<?php echo $List->agree_no; ?></th>
																				</tr>
																				<tr>
																					<th colspan="6" class="" style=" word-wrap:break-word; text-align:left !important">&nbsp;Contractor Name &emsp;&emsp;&emsp;&emsp; : &emsp;<?php echo $List->name_contractor; ?></th>
																				</tr>
																				<tr>
																					<th style="text-align:left !important">&nbsp;CCNO.&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp; : &emsp;<span class="badge badge-danger"><?php echo $List->computer_code_no; ?></span></th>
																					<th colspan="3" style="text-align:left !important">&nbsp;RAB Number &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: &emsp;<?php echo $List->rbn; ?></th>
																				</tr>
																			</tbody>
																		</table>-->
																		<!--<table width="100%" class="dataTable test tables table-bordered">
																			<thead>
																				<tr class="head"><th style="color: #ffffff; background: #10478A; !important" colspan="5">&nbsp;Work Order Cost Details</th></tr>
																			</thead>
																			<tbody>	
																				<tr>
																				<?php $WOCost =$List->work_order_cost;
																				$LimitCost = $WOCost*70/100 ?>
																					<th style="text-align:left !important" >&nbsp;Work Order Value  &nbsp;&nbsp;&emsp;&emsp;&emsp;:  &emsp;<?php echo IndianMoneyFormat($List->work_order_cost); ?></th>
																					<th style="text-align:left !important">&nbsp;70% of Work Order Value &nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: &emsp;<?php echo IndianMoneyFormat($LimitCost); ?></th>
																				</tr>																		
																			</tbody>
																		</table>-->
																	<?php 
																	$SelDetailQuery= "SELECT a.*,b.*,c.* FROM mob_master a INNER JOIN bg_fdr_details b ON (a.mob_adv_no = b.mob_adv_no) 
																	INNER JOIN mob_adv_rec c ON (a.mobmid = c.mobmid) WHERE b.inst_purpose='MOB' AND a.sheetid ='$SheetVal' AND b.contid='$List->contid'";
																	//echo $SelDetailQuery;exit;
																	$SelDetailQuerySql= mysqli_query($dbConn,$SelDetailQuery);
																	} 	
																} ?>

																<table width="100%" class="dataTable test tables table-bordered">
																	<thead>
																		<tr class="head"><th style="color: #ffffff; background: #10478A; !important" colspan="11">RECOVERY DETAILS OF MOBILIZATION ADVANCE & INTEREST</th></tr>
																	</thead>
																	<tbody>	
																		<tr>
																			<th rowspan="2" style="text-align:center">&nbsp;Bill No.</th>
																			<th colspan="2" style="text-align:center">&nbsp;Period</th>
																			<th rowspan="2" style="text-align:center">&nbsp;MOB Adv.</br>Outstanding</br>( &#8377; )</th>
																			<th rowspan="2" style="text-align:center">&nbsp;Bill Value</br>( &#8377; )</th>
																			<th rowspan="2" style="text-align:center">&nbsp;Factor Value</th>
																			<th rowspan="2" style="text-align:center">&nbsp;Amount Recovered</br>( &#8377; )</th>
																			<th rowspan="2" style="text-align:center">&nbsp;MOB Adv.</br>Outstanding</br>( &#8377; )</th>
																			<th rowspan="2" style="text-align:center">&nbsp;Rate of</br>Interest</br>(%)</th>
																			<th rowspan="2" style="text-align:center">&nbsp;No. of Days</th>
																			<th rowspan="2" style="text-align:center">&nbsp;Interest Amount</br>( &#8377; )</th>
																		</tr>
																		<tr>
																			<th style="text-align:center">&nbsp;From</th>
																			<th style="text-align:center">&nbsp;To</th>
																		</tr>

																	<?php
																		$PrevOutAmt = ""; $PrevToDate=""; $TotIntrestAmt=0;//$slno = 1; $PrevRbn = 0;
																		$MastQuery = "SELECT * FROM mob_master WHERE sheetid='$SheetVal'";
																		$MastQuerySql = mysqli_query($dbConn,$MastQuery);
																		if($MastQuerySql == true){ 
																			if(mysqli_num_rows($MastQuerySql)>0){ 
																				while($MastList = mysqli_fetch_object($MastQuerySql)){
																					/*$BillAmtSelQuery= "SELECT slm_total_amount,slm_total_amount_esc FROM abstractbook WHERE sheetid='$SheetVal' AND rbn='$DetList->rbn'";
																					//echo $BillAmtSelQuery;exit;
																					$BillAmtSelQuerySql= mysqli_query($dbConn,$BillAmtSelQuery);
																					if($BillAmtSelQuerySql == true){
																						if(mysqli_num_rows($BillAmtSelQuerySql)>0){
																							$ListVal = mysqli_fetch_object($BillAmtSelQuerySql);
																							$BillAmt = $ListVal->slm_total_amount + $ListVal->slm_total_amount_esc;
																						}
																					}*/
																					?>		
																					<tr>
																					<?php 
																					//if($PrevRbn == 0){
																						$BillNo = "MOB.Adv</br>(".$MastList->rbn-RAB.")";
																					//}else{
																					//	$BillNo = "$DetList->rbn-RAB";
																					//}
																					/*if($DetList->amt_rec != null){

																					}*/
																					$MastMobAdv = $MastList->mob_adv_amt;	
																					$IntPerc = 10;	
																					$NoofDays = "-";
																					?>
																					<th style="text-align:center">&nbsp;<?php echo $BillNo; ?></th>
																					<th style="text-align:right"><?php echo dt_display($MastList->amt_issused_dt); ?>&nbsp;</th>
																					<th  style="text-align:center">&nbsp;-</th>
																					<!--<input type="text" name="txt_days" id="txt_days" class="validate[required] form-control" value="" style="text-align:right">-->
																					</th>
																					<?php 
																					$FDate = strtotime($MastList->mob_fdate); // or your date as well
																					$TDate =strtotime($MastList->mob_tddate);// $DetList->amt_issused_dt;
																					$datediff = $TDate - $FDate;
																					//echo round($datediff / (60 * 60 * 24));
																					//echo $diff = $TDate->diff($FDate)->format("%a");
																					$MobAdv = $MastList->mob_adv_amt;
																					$IntPerc = 10;
																					//$NoofDays = round($datediff / (60 * 60 * 24));
																					//$RecAmt = $MastList->amt_rec*$IntPerc/100;
																					$RecAmt = "-";
																					$MobAdvAftRecAmt = "-";
																					?>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($MobAdv); ?></th>
																					<th style="text-align:center !important"><?php echo "-"; ?></th>
																					<th style="text-align:right"><?php echo '-'; ?></th>
																					<th style="text-align:right !important"><?php echo "-";//IndianMoneyFormat($RecAmt); ?></th>
																					<th style="text-align:right !important"><?php echo "-";//IndianMoneyFormat($MobAdvAftRecAmt); ?></th>
																					<th style="text-align:center"><?php echo $IntPerc; ?>&nbsp;</th>
																					<th style="text-align:center"><?php echo $NoofDays; ?>&nbsp;</th>
																					<?php $IntrAmt = "-";//round($MobAdv*$IntPerc/100*$NoofDays/365,2); ?>
																					<th style="text-align:right !important"><?php echo $IntrAmt; ?></th>
																					</tr>
																					<?php //$PrevRbn++; 
																				} 
																			} 
																		}   
																	?>
																	<?php
																		$slno = 1; //$PrevRbn = 0;
																		if($SelDetailQuerySql == true){ 
																			if(mysqli_num_rows($SelDetailQuerySql)>0){ 
																				while($DetList = mysqli_fetch_object($SelDetailQuerySql)){
																					$BillAmtSelQuery= "SELECT slm_total_amount,slm_total_amount_esc FROM abstractbook WHERE sheetid='$SheetVal' AND rbn='$DetList->rbn'";
																					//echo $BillAmtSelQuery;exit;
																					$BillAmtSelQuerySql= mysqli_query($dbConn,$BillAmtSelQuery);
																					if($BillAmtSelQuerySql == true){
																						if(mysqli_num_rows($BillAmtSelQuerySql)>0){
																							$ListVal = mysqli_fetch_object($BillAmtSelQuerySql);
																							$BillAmt = $ListVal->slm_total_amount + $ListVal->slm_total_amount_esc;
																						}
																					}
																					?>		
																					<tr>
																					<?php 
																					/*if($PrevRbn == 0){
																						$BillNo = "MOB.Adv</br>($DetList->rbn-RAB)";
																					}else{
																						$BillNo = "$DetList->rbn-RAB";
																					}*/
																					$BillNo = $DetList->rec_rbn."-RAB";
																						/*if($DetList->amt_rec != null){

																					}*/
																					$MobAdv = $DetList->mob_adv_amt;
																					$IntPerc = 10;
																					$NoofDays = 30;
																					?>
																					<th style="text-align:center">&nbsp;<?php echo $BillNo; ?></th>
																					<th style="text-align:right"><?php echo dt_display($DetList->mob_fdate); ?>&nbsp;</th>
																					<th  style="text-align:right"><?php echo dt_display($DetList->mob_tddate); ?><!--<input type="text" name="txt_to_date" id="txt_to_date" class="textboxclass" value="<?php echo dt_display($DetList->mob_tddate); ?>">--></th>
																					<!--<input type="text" name="txt_days" id="txt_days" class="validate[required] form-control" value="" style="text-align:right">-->
																					</th>
																					<?php 
																					$FDate = strtotime($DetList->mob_fdate); // or your date as well
																					$TDate =strtotime($DetList->mob_tddate);// $DetList->amt_issused_dt;
																					$datediff = $TDate - $FDate;
																					//echo round($datediff / (60 * 60 * 24));
																					//echo $diff = $TDate->diff($FDate)->format("%a");
																					
																					$IntPerc = 10;
																					$NoofDays = round($datediff / (60 * 60 * 24));
																					$RecAmt = $DetList->amt_rec;//*$IntPerc/100;
																					//$RecAmt = $DetList->amt_rec*$IntPerc/100;
																					
																					if($PrevOutAmt == ""){
																						$MobAdv = $DetList->mob_adv_amt;
																					}else{
																						$MobAdv = $PrevOutAmt;
																					}
																					$MobAdvAftRecAmt = $MobAdv-$RecAmt;
																					?>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($MobAdv); ?></th>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($BillAmt); ?></th>
																					<th style="text-align:right"><?php echo '-'; ?></th>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($RecAmt); ?></th>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($MobAdvAftRecAmt); ?></th>
																					<th style="text-align:center"><?php echo $IntPerc; ?>&nbsp;</th>
																					<th style="text-align:center"><?php echo $NoofDays; ?>&nbsp;</th>
																					<?php $IntrAmt =round($MobAdv*$IntPerc/100*$NoofDays/365,2); 
																					$TotIntrestAmt = $TotIntrestAmt+$IntrAmt;
																					$TotIntrestAmtForJQ = $TotIntrestAmt;
																					//echo $TotIntrestAmtForJQ;
																					?>
																					<th style="text-align:right !important"><?php echo IndianMoneyFormat($IntrAmt); ?></th>
																					</tr>
																					<?php $PrevToDate = $DetList->mob_tddate; $PrevOutAmt = $MobAdvAftRecAmt; $PrevRbn++; 
																				} 
																			} 
																		}   
																	?>
																	<!--  //////////////////////   Interest Calculation Part   ////////////////////////////  -->
																					<tr>
																						<?php 
																						
																						$BillNo = "-";
																						$MobAdv = $DetList->mob_adv_amt;
																						$IntPerc = 10;
																						$NoofDays = 30;
																						$FromDateL = date($PrevToDate);																					
																						$FromDateOut = date( "Y-m-d", strtotime( "$FromDateL +1 day" ) ); //strtotime("+1 day", $FromDateL);
																						$CurrDate = date("Y-m-d");
																						?>
																						<th style="text-align:center">&nbsp;<?php echo $BillNo; ?></th>
																						<th style="text-align:right"><?php echo dt_display($FromDateOut); ?>&nbsp;</th>
																						<input type="hidden" class="tboxclass" readonly="" id="txt_from_date_entry" name="txt_from_date_entry" value="<?php echo $FromDateOut; ?>">
																						<th  style="text-align:right"><input type="text" class="datepicker tboxclass" readonly="" id="txt_to_date_entry" name="txt_to_date_entry" value="<?php echo dt_display($CurrDate); ?>"></th>
																						<?php 
																						$FDate = strtotime($FromDateOut); // or your date as well
																						$TDate = strtotime($CurrDate);// $DetList->amt_issused_dt;
																						$datediff = $TDate - $FDate;																					
																						$IntPerc = 10;
																						$NoofDays = round($datediff / (60 * 60 * 24));
																						$RecAmt = $DetList->amt_rec;//*$IntPerc/100;
																						
																						if($PrevOutAmt == ""){
																							$MobAdv = $DetList->mob_adv_amt;
																						}else{
																							$MobAdv = $PrevOutAmt;
																						}
																						$MobAdvAftRecAmt = $MobAdv-$MobAdvAmt;
																						?>
																						<th style="text-align:right !important"><?php echo IndianMoneyFormat($MobAdv); ?></th>
																						<th style="text-align:center">&nbsp;<?php echo '-'; ?></th>
																						<th style="text-align:right"><?php echo '-'; ?></th>
																						<th style="text-align:right; !important"><font style="color:red;"><?php echo IndianMoneyFormat($MobAdvAmt); ?></font></th>
																						<input type="hidden" readonly="" calss="tboxclass" name="txt_entered_rec_amt" id="txt_entered_rec_amt" value="<?php echo $MobAdvAmt; ?>">
																						<th style="text-align:right !important"><?php echo IndianMoneyFormat($MobAdvAftRecAmt); ?></th>
																						<th style="text-align:center"><?php echo $IntPerc; ?>&nbsp;</th>
																						<th style="text-align:center"><input type="text" style="text-align:center !important" calss="tboxclass" id="txt_no_days" readonly="" name="txt_no_days" value="<?php echo $NoofDays; ?>"></th>
																						<?php $IntrAmt =round($MobAdv*$IntPerc/100*$NoofDays/365,2); 
																						$TotIntrestAmt = $TotIntrestAmt+$IntrAmt;
																						?>
																						<th><input type="text" style="text-align:right !important" calss="tboxclass" readonly="" id="txt_int_amt" name="txt_int_amt" value="<?php echo IndianMoneyFormat($IntrAmt); ?>"></th>
																						<input type="hidden" name="txt_hid_tot_int" id="txt_hid_tot_int" value ="<?php echo $TotIntrestAmtForJQ; ?>">
																					
																					</tr>
																					<?php $PrevOutAmt = $MobAdvAftRecAmt; ?>

																			<tr>
																				<th colspan="10" style="text-align:right !important">Total Interest Amount : &nbsp;</th>
																				<th style="text-align:right !important"><input type="text" style="text-align:right !important" calss="tboxclass" readonly="" id="txt__tot_int_amt" name="txt__tot_int_amt" value="<?php echo IndianMoneyFormat($TotIntrestAmt); ?>"></th>
																			</tr>
																			<!--<tr class="printSection">
																				<td colspan="11" align="center">
																					<br/>
																					<input type="button" class="btn btn-info" name="btn_back" id="btn_back" value=" Back " onclick="history.back(-1)">
																					<br/>&nbsp;
																				</td>
																			</tr>-->
																	</tbody>
																</table>

															</div>
														</div>
														<div class="row div12">
															<input type="button" class="btn btn-info" name="btn_back" id="btn_back" value=" Back " onClick="history.back(-1)">
														</div>
													</div>
												</div>
											</div>														
										</div>
									</div>												
									<!-- <div class="div2">&nbsp;</div> -->
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
	$('#month').hide();
	$('#year').hide();
	$('#day').hide();
	$('#day1').hide();
$(function(){
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","VouchersList.php");
					}
				}]
			});
		}
	};
	$("#check_all").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
});

$("body").on("change","#txt_to_date_entry", function(event){
	var TotIntBillsHid = $("#txt_hid_tot_int").val();
	var EntRecAmount = $("#txt_entered_rec_amt").val();
	var FrDate 		 = $("#txt_from_date_entry").val();
	var ChangedDate = $("#txt_to_date_entry").val();
	var IntPerc = 10;
	var dt = ChangedDate.split("/") //explode('/', ChangedDate);
	var dd = dt[0];
	var mm = dt[1];
	var yy = dt[2];
	var ToDate = ""+yy+"-"+mm+"-"+dd+"";
	var NoOfDaysDiff = Math.floor((Date.parse(ToDate) - Date.parse(FrDate)) / 86400000);
	var DiffDays = $("#txt_no_days").val(NoOfDaysDiff);
	var IntAmt = parseFloat(Number(EntRecAmount)*Number(IntPerc)/100*Number(NoOfDaysDiff)/365).toFixed(2);
	var IntCalcAmt = $("#txt_int_amt").val(IntAmt);
	var TotIntCalc = Number(TotIntBillsHid)+Number(IntAmt);
	var TotIntCalcAmt = $("#txt__tot_int_amt").val(TotIntCalc);
	// /alert(TotIntBillsHid);
});

$("#exportToExcel").click(function(e){ 
	var table = $('body').find('.table2excel');
	if(table.length){ 
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: "GSTStatement-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
			//preserveColors: preserveColors
		});
	}
});
/*$("#txt_todt").datepicker({
	dateFormat: "dd/mm/yy",
	changeMonth: true,
	changeYear: true,
});*/
$("#txt_fromdt").datepicker({
	dateFormat: "dd/mm/yy",
	changeMonth: true,
	changeYear: true,
	onSelect: function (selectedDate) {                                           
		$('#txt_todt').datepicker('option', 'minDate', selectedDate);
	}
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	th.tabtitle{
		text-align:left !important;
	}
	.mgtb-8 td{
		padding:2px !important;
		font-size:10px !important;
		font-weight:500;
	}
	.mgtb-8 th{
		background-color:#F2F3F4 !important;
		font-size:10px !important;
		padding:2px !important;
	}
</style>
