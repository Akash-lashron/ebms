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

if(isset($_POST['btn_view'])){

	/*if ($_POST['type']!="")
		$type=$_POST['type'];

	if ($_POST['txt_fromdt']!="")
		$fromdt=dt_format($_POST['txt_fromdt']);
		//echo $fromdt;exit;
	if ($_POST['txt_todt']!="")
		$todt=dt_format($_POST['txt_todt']);

	if ($_POST['cmb_mon']!="")
		$month=$_POST['cmb_mon'];
		
		
	if($type=='Month')
	{
		if ($_POST['txt_year_mon']!="")
			$year=$_POST['txt_year_mon'];
	}
	
	if($type=='Year')
	{
		if ($_POST['txt_year']!="")
			$year=$_POST['txt_year'];
	}
	//echo $year;exit;
	if($type=='Day') 
	{
		$sql_date= "select * from memo_payment_accounts_edit where payment_dt between '" . $fromdt . "' and '" . $todt . "';";
		//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
		//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
		$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
	}
	
	if($type=='Month') 
	{
		$sql_date="select * from memo_payment_accounts_edit where MONTH(payment_dt)=" . $month . " and YEAR(payment_dt) =" . $year;
		//echo $sql_date;exit;
		$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
		$heading='FOR - ' . $mon . '/' . $year;
	}
	
	if($type=='Year') 
	{
		$sql_date="select * from memo_payment_accounts_edit where YEAR(payment_dt) ='" . $year . "'";
		// SELECT * FROM memo_payment_accounts_edit WHERE YEAR(`modifieddate`) = '2022'
		$heading='FOR YEAR - ' . $year;
	}
	//echo $sql_date.'<br/>';
	$rs_date_sql=mysqli_query($dbConn,$sql_date);

	$report=true;
	*/
	$FromToDateVal = 0;
	$YearMonthVal 	= 0;
	$NoRecVal = 0;
	$sql_date		= null;
	if(isset($_POST['txt_year'])){
		if($_POST['txt_year'] != null){
			$year = $_POST['txt_year'];
			$YearMonthVal = $YearMonthVal + 1;
		}
	}
	if(isset($_POST['cmb_mon'])){
		if($_POST['cmb_mon'] != null){
			$month = $_POST['cmb_mon'];
			$YearMonthVal = $YearMonthVal + 1;
		}
	}
	if(isset($_POST['txt_fromdt'])){
		if($_POST['txt_fromdt'] != null){
			$fromdt=dt_format($_POST['txt_fromdt']);
			$FromToDateVal = $FromToDateVal + 1;
		}
	}
	if(isset($_POST['txt_todt'])){
		if($_POST['txt_todt'] != null){
			$todt=dt_format($_POST['txt_todt']);
			$FromToDateVal = $FromToDateVal + 1;
		}
	}
	//echo $YearMonthVal;exit;
	if(($FromToDateVal == 2) && ($YearMonthVal == 2)){
		$msg = "Please Select Only one Option Year and Month or Particular Period ..!!";
	}else{
		if($FromToDateVal == 2){
			$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE mop_type = 'RAB' AND payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
			//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
			//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
			$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
		}
		if($YearMonthVal == 2){
			if($month == 'ALL'){
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE mop_type = 'RAB' AND YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";
				//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - '. $year;	
			}else{
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE mop_type = 'RAB' AND MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $year ." ORDER BY payment_dt ASC;";
				$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - ' . $mon . '/' . $year;	
			}
		}
		if($sql_date != null){
			$rs_date_sql = mysqli_query($dbConn,$sql_date);
			if($rs_date_sql == true){
				if(mysqli_num_rows($rs_date_sql)>0){
					$report = true;
				}else{
					$NoRecVal = 1;
				}
			}
		}
	}
	//echo $heading;
}

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
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
							
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;GST Recovery Statement <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<!--<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Year</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Year" id="type_year" onClick="show()" />
																	<label for="type_year" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Year</label>
																</div>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Month" id="type_month" onClick="show()"/>
																	<label for="type_month" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Month</label>
																</div>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Day" id="type_day" onClick="show()"/>
																	<label for="type_day" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Period</label>
																</div>
															</div>
														</div>-->
														<?php //$CurrYr = date('Y'); ?>
														<div class="div2 pd-lr-1" id="YearRow">
															<div class="lboxlabel-sm">&nbsp;Select Year</div>
															<div>
																<select name='txt_year' id='txt_year' class='tboxclass'>
																	<option value="">---Select---</option>
																	<?php echo $objBind->BindYear(0); ?>
																</select>
																<!-- <input type="text" id="txt_year" name="txt_year" value="<?php //echo $CurrYr; ?>" class="tboxclass"/> -->
															</div>
														</div>
														
														<div class="div2 pd-lr-1" id="MonthRow">
															<div class="lboxlabel-sm">&nbsp;Select Month</div>
															<div>
																<select name="cmb_mon" id="cmb_mon" class="tboxclass">
																	<option value="">---Select---</option>
																	<option value="ALL">ALL Months</option>
																	<option value="01">JAN</option>
																	<option value="02">FEB</option>
																	<option value="03">MAR</option>
																	<option value="04">APR</option>
																	<option value="05">MAY</option>
																	<option value="06">JUN</option>
																	<option value="07">JUL</option>
																	<option value="08">AUG</option>
																	<option value="09">SEP</option>
																	<option value="10">OCT</option>
																	<option value="11">NOV</option>
																	<option value="12">DEC</option>
																</select>
															</div>
														</div>
														<div class="div2" id="PeriodRow1">
															<div class="cboxlabel-sm">&nbsp;</div>
															<div class="cboxlabel">
																(OR Select Period)
															</div>
														</div>
														<div class="div2 pd-lr-1" id="PeriodRow1">
															<div class="lboxlabel-sm">&nbsp;From Date</div>
															<div>
																<input type="text" value="" readonly="" name="txt_fromdt" id="txt_fromdt" class="tboxclass datepicker" />
															</div>
														</div>
														<div class="div2 pd-lr-1" id="PeriodRow2">
															<div class="lboxlabel-sm">&nbsp;To Date</div>
															<div>
																<input type="text" value="" readonly="" name="txt_todt" id="txt_todt" class="tboxclass datepicker" />
															</div>
														</div>
														
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value="View">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>




							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12" id="appendrow">
										<div class="card cabox">
											<div class="face-static">
												<!--<div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM </div>-->
												<div class="card-header inkblue-card" align="center">TDS on GST Recovery Statement <?php echo $heading; ?>  <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i> </span></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12">&nbsp;</div>
																<?php if($report==true){ ?>

																	<table class="table dataTable rtable example" border="1" width="100%" align="center">
																	<tr>
																			<td class="colhead">S.No</td>
																			<td class="colhead">CCNO.</td>
																			<td class="colhead">RAB NO.</td>
																			<td class="colhead">NAME OF CONTRACTOR</td>
																			<td class="colhead">PAN</td>
																			<td class="colhead">GST NO</td>
																			<td class="colhead">DATE OF</br>PAYMENT</td>
																			<td class="colhead">BILL VALUE TDS</td>
																			<td class="colhead">IGST TDS</td>
																			<td class="colhead">CGST TDS</td>
																			<td class="colhead">SGST TDS</td>
																			<td class="colhead">REMARKS</td>
																		</tr>
																	<?php
																	if($rs_date_sql == true){
																		$sno=1;
																		$TotalIGST = 0;
																		$TotalCGST = 0;
																		$TotalSGST = 0;
																		$TotalBillVal = 0;
																		while($List = mysqli_fetch_object($rs_date_sql)){	
																			$SheetId 	= $List->sheetid;
																			$RabNo 		= $List->rbn;
																			$BillAMT		= $List->bill_amt_gst;
																			$BillAMTFormated = round($BillAMT);		//IndianMoneyFormat($BillAMT);
																			$BillGSTAMT	= $List->gst_amount;
																			$SGSTPERC 	= $List->sgst_tds_perc;
																			$SGSTAMT 	= $List->sgst_tds_amt;
																			$CGSTPERC 	= $List->cgst_tds_perc;
																			$CGSTAMT 	= $List->cgst_tds_amt;
																			$IGSTPERC 	= $List->igst_tds_perc;
																			$IGSTAMT 	= $List->igst_tds_amt;
																			$SGSTAMTFormated 	= round($List->sgst_tds_amt);		//IndianMoneyFormat($List->sgst_tds_amt);
																			$CGSTAMTFormated 	= round($List->cgst_tds_amt);		//IndianMoneyFormat($List->cgst_tds_amt);
																			$IGSTAMTFormated 	= round($List->igst_tds_amt);		//IndianMoneyFormat($List->igst_tds_amt);
																			$VAmt 		= $List->vat_amt;
																			$DatePay	= dt_display($List->payment_dt);
																			
																			$sql_select="select contid,computer_code_no from sheet where sheet_id ='" . $SheetId . "'";
																			//echo $sql_select;exit;
																			$sql_selectSql 	= mysqli_query($dbConn,$sql_select);
																			while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																				$ContId	  	= $List1['contid'];
																				$CCNum		= $List1['computer_code_no'];
																				
																				$sql_select1="select * from contractor where contid ='" . $ContId . "'";
																				$sql_selectSql1 	= mysqli_query($dbConn,$sql_select1);
																				while($List2 = mysqli_fetch_object($sql_selectSql1)){
																					$PANNum	  = $List2->pan_no;
																					$GSTNum	  = $List2->gst_no;
																					$ContName = $List2->name_contractor;
																					echo "<tr>";
																					echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																					echo "<td class='labelcenter' nowrap='nowrap'>" .  $CCNum . "</td>";
																					echo "<td class='labelcenter' nowrap='nowrap'>" .  $RabNo . "</td>";
																					echo "<td class='labelleft' nowrap='nowrap'>" .  $ContName . "</td>";
																					echo "<td class='labelleft' nowrap='nowrap'>" .  $PANNum . "</td>";
																					echo "<td class='labelleft' nowrap='nowrap'>" . $GSTNum . "</td>";
																					echo "<td class='labelcenter' nowrap='nowrap'>" .  $DatePay . "</td>";
																					echo "<td class='labelright'>" . $BillAMTFormated . "</td>";
																					echo "<td class='labelright' nowrap='nowrap'>" . $IGSTAMTFormated . "</td>";
																					echo "<td class='labelright' nowrap>". $CGSTAMTFormated ."</td>";
																					echo "<td class='labelright' nowrap='nowrap'>" . $SGSTAMTFormated . "</td>";
																					echo "<td class='labelcenter'> </td>";
																					echo "</tr>";
																					$TotalBillVal = $TotalBillVal + $BillAMT;
																					$TotalIGST = $TotalIGST + $IGSTAMT;
																					$TotalCGST = $TotalCGST + $CGSTAMT;
																					$TotalSGST = $TotalSGST + $SGSTAMT;
																				}
																			}
																			$sno++;
																		}
																		$TotalBillValFormated = round($TotalBillVal);		//IndianMoneyFormat($TotalBillVal);
																		$TotalIGSTFormated = round($TotalIGST);		//IndianMoneyFormat($TotalIGST);
																		$TotalCGSTFormated = round($TotalCGST);		//IndianMoneyFormat($TotalCGST);
																		$TotalSGSTFormated = round($TotalSGST);		//IndianMoneyFormat($TotalSGST);
																		echo "<tr>";
																		echo "<td colspan='7' class='labelright'>TOTAL : </td>";
																		echo "<td class='labelright' nowrap='nowrap'>" . $TotalBillValFormated . "</td>";
																		echo "<td class='labelright' nowrap='nowrap'>" . $TotalIGSTFormated . "</td>";
																		echo "<td class='labelright' nowrap='nowrap'>" . $TotalCGSTFormated . "</td>";
																		echo "<td class='labelright' nowrap='nowrap'>" . $TotalSGSTFormated . "</td>";
																		echo "<td class='labelcenter'></td>";
																		echo "</tr>";
																	}
																	?>
																	</table>
																<?php } if($NoRecVal == 1){
																				echo"<div>";
																				echo"<div class='labelcenter' colspan='9'>No Records Found</div>";
																				echo"</div>";
																			} ?>
																	<div class="div12">&nbsp;</div>
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
						$(location).attr("href","GstStatement.php");
					}
				}]
			});
		}
	};
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
$("body").on("click","#btn_view", function(event){
	var SelYear		= $("#txt_year").val();
	var SelMonth	= $("#cmb_mon").val();                  
	var SelFrDate	= $("#txt_fromdt").val();
	var SelToDate	= $("#txt_todt").val();

	switch(true) {
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select atleast one period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth != "") && (SelFrDate != "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select (Year - Month) or (From Date - To Date) any one period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid From Date - To Date period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid From Date - To Date period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid Month..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;		
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please Enter Valid Year..!!");
			event.preventDefault();
			event.returnValue = false;
		break;		
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
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
