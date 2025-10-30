<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();

$MonthsArr = array('04'=>'APRIL','05'=>'MAY','06'=>'JUNE','07'=>'JULY','08'=>'AUGUST','09'=>'SEPTEMBER','10'=>'OCTOBER','11'=>'NOVEMBER','12'=>'DECEMBER','01'=>'JANUARY','02'=>'FEBRUARY','03'=>'MARCH');
$CountMonthsArr = count($MonthsArr);
//echo $CHMStatArr;
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
function moneyFormat($amt)
{
	/* 
	1. IMPORTANT NOTES: Plz use this result of this funtion for only print output in following format. 
	2. DONT USE addtion, subtraction, multiplication and division function using this result. 
	3. Because it gives the result in string - data type. If we use this output will be wrong.
	*/
	$result = "";
	$amount = number_format($amt, 2, '.', '');
	$explodeRes = explode(".",$amount);
	$ratePart = $explodeRes[0];
	$decimalPart = $explodeRes[1];
	$length = strlen($ratePart);
	if($length>3)
	{
		$getArray = str_split($ratePart);
		$count = count($getArray);
		if(($count%2) == 0)
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else if($i == 0)
				{
					$result .= $getArray[$i].",";
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		else
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		$result = $result.".".$decimalPart;
	}
	else
	{
		$result = $amount;
	}
	return $result;
}


//echo $_POST['type'];exit;
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
		$sql_date= "select * from memo_payment_accounts_edit where payment_dt between '" . $fromdt . "' and '" . $todt . "'";
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

	$report=true;*/
	if(isset($_POST['btn_view'])){
		$FromToDateVal = 0;
		$YearMonthVal 	= 0;
		$NoRecVal = 0;
		$sql_date = null;
		$year = $_POST['txt_year'];
		if($year == null){
			$msg = "Please Select Year of SD Broadsheet..!!";
		}
	
		if(isset($_POST['txt_year'])){
			if($_POST['txt_year'] != null){
				$year = $_POST['txt_year'];
				$Splityear = explode("-",$year);
				$YearMonthVal = $YearMonthVal + 1;
			}
		}
		if($year != null){
			$month1 	= 04;
			$month2 	= 03;
			$day1 	= 01;
			$day2 	= 31;
			//WHERE date BETWEEN '2013-06-15' AND '2013-06-18'
			$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE ((mop_type = 'RAB' AND sd_amt != 0) OR (mop_type = 'SDR' AND net_payable_amt != 0)) AND payment_dt BETWEEN '" . $Splityear[0] . "-" . $month1 . "-" . $day1 . "' AND '" . $Splityear[1] . "-" . $month2 . "-" . $day2 . "' ORDER BY payment_dt ASC;";
			//echo $sql_date;
			//$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";

			$heading='FOR THE YEAR '. $year;
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
	}
	//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));

	/*	if(isset($_POST['cmb_mon'])){
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
			$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
			//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
			//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
			$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
		}
		if($YearMonthVal == 2){
			if($month == 'ALL'){
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";
				//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - '. $year;	
			}else{
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $year ." ORDER BY payment_dt ASC;";
				$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - ' . $mon . '/' . $year;	
			}
		}	
		
	}
}*/

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
							<!--<div class="row">
								<input type="hidden" name="max_group" id="max_group" value="1" />
									<div class="row">
										<div class="box-container box-container-lg" align="center">
											<div class="div12">
												<div class="card cabox">
													<div class="face-static">
														<div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div>
														<div class="card-header inkblue-card" align="center">INCOME TAX RECOVERY DETAILS IN RESPECT OF CONTRACTORS <?php //echo $heading; ?></div>
														<div class="card-body padding-1 ChartCard" id="CourseChart">
															<div class="divrowbox pt-2">
																<div class="table-responsive dt-responsive ResultTable">
																	<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																		<div class="div12">&nbsp;</div>
																		<table class="table dataTable rtable table2excel" border="1" width="100%" align="center">
																			<tr>
																				<td class="colhead">S.No</td>
																				<td class="colhead">PAN</td>
																				<td class="colhead">NAME OF CONTRACTOR</td>
																				<td class="colhead">BILL VALUE</td>
																				<td class="colhead">DATE OF PAYMENT</td>
																				<td class="colhead">PERCENT (%)</td>
																				<td class="colhead">I TAX</td>
																				<td class="colhead" style="font-weight:bold;">REMARKS</td>
																			</tr>
																		<?php
																		/*$TotSumBillVal = 0;
																		if($rs_date_sql == true){
																			$sno=1;
																			$TotSumBillVal = 0;
																			while($List = mysqli_fetch_object($rs_date_sql)){
																				$SheetId 	= $List->sheetid;
																				$BillVal 	= $List->abstract_net_amt;
																				$BillValIndForm = moneyFormat($BillVal);
																				$SGSTPERC 	= $List->sgst_tds_perc;
																				$SGSTAMT 	= $List->sgst_tds_amt;
																				$CGSTPERC 	= $List->cgst_tds_perc;
																				$CGSTAMT 	= $List->cgst_tds_amt;
																				$IGSTPERC 	= $List->igst_tds_perc;
																				$IGSTAMT 	= $List->igst_tds_amt;
																				$VAmt 		= $List->vat_amt;
																				$DatePay	= dt_display($List->payment_dt);
																				
																				$sql_select="select contid from sheet where sheet_id ='" . $SheetId . "'";
																				//echo $sql_select;exit;
																				$sql_selectSql = mysqli_query($dbConn,$sql_select);
																				while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																					$ContId	= $List1['contid'];
																					$sql_select1 ="select * from contractor where contid ='" . $ContId . "'";
																					$sql_selectSql1 = mysqli_query($dbConn,$sql_select1);	
																					while($List2 = mysqli_fetch_object($sql_selectSql1)){
																						$PANNum	  = $List2->pan_no;
																						$GSTNum	  = $List2->gst_no;
																						$ContName = $List2->name_contractor;
																						$PanType  = $List2->pan_type;
																						if($PanType == 'P'){
																							$PanPerc = 1;
																						}else{
																							$PanPerc = 2;
																						}
																						$ItaxVal = ($PanPerc / 100)* $BillVal;
																						$ItaxValIndFormat = moneyFormat($ItaxVal);
																						echo "<tr>";
																						echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																						echo "<td class='labelleft' nowrap='nowrap'>" .  $PANNum . "</td>";
																						echo "<td class='labelleft' nowrap='nowrap'>" .  $ContName . "</td>";
																						echo "<td class='labelright'>" . $BillValIndForm . "</td>";
																						echo "<td class='labelcenter' nowrap='nowrap'>" .  $DatePay . "</td>";
																						echo "<td class='labelright' nowrap>". $PanPerc ."</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $ItaxValIndFormat . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'></td>";
																						echo "</tr>";
																					}
																				}
																				$TotSumBillVal = $TotSumBillVal+$ItaxVal;
																				$sno++;
																			}
																			$TotSumValIndFormat = moneyFormat($TotSumBillVal);

																			echo "<tr>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelleft'>TOTAL</td>";
																			echo "<td class='labelright' nowrap='nowrap'></td>";
																			echo "<td class='labelright' nowrap='nowrap'></td>";
																			echo "<td class='labelright' nowrap='nowrap'>" . $TotSumValIndFormat . "</td>";
																			echo "<td class='labelcenter'></td>";
																			echo "</tr>";
																		}
																		else
																		{
																			echo"<tr>";
																			echo"<td class='labelcenter' colspan='9'>No Records Found</td>";
																			echo"</tr>";
																		}*/
																		?>
																		</table>
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
									<br />

									<center>
										<input type="image" class="btn btn-info" name="btn_back" id="btn_back" value="Back" onClick="func_back()" />
									</center>
									<br />-->
									<div class="row">
							
							<div class="box-container box-container-lg">
								<div class="div12">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;SECURITY DEPOSIT ANNUAL BROADSHEET <span id="CourseChartDuration"></span></div>
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
													
													<div class="div6 pd-lr-1 row" id="YearRow">
														<div class="div3 lboxlabel-sm" style="vertical-align:middle; padding-top: 3px;">&nbsp;Select Financial Year</div>
														<div class="div4">
															<select name='txt_year' id='txt_year' class='tboxclass'>
																<option value="">----- Select Period -----</option>
																<?php echo $objBind->BindFinancialYear(0); ?>
															</select>
														</div>
														<!-- <div class="div1">&nbsp;</div> -->
														<div class="div1" style="vertical-align:middle; padding-left:5px;">
															<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value="View">
														</div>
													</div>
													
													<!--	<div class="div2 pd-lr-1" id="MonthRow">
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
													</div>-->
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
														<!-- <div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div> -->
														<div class="card-header inkblue-card" align="center">SECURITY DEPOSIT BROADSHEET <?php echo $heading; ?> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i></span></span></div>
														<div class="card-body padding-1 ChartCard" id="CourseChart">
															<div class="divrowbox pt-2">
																<div class="table-responsive dt-responsive ResultTable">
																	<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																		<div class="div12">&nbsp;</div>
																		<?php if($report==true){ ?>
																			<table class="table dataTable rtable table2excel example" border="1" width="100%" align="center">
																				<tr>
																					<td rowspan ="2" class="colhead">S.No</td>
																					<td rowspan ="2" class="colhead">CCNO.</td>
																					<td rowspan ="2" class="colhead">NAME OF THE</br>CONTRACTOR</td>
																					<td rowspan ="2" nowrap="nowrap" class="colhead">&emsp; NAME OF THE WORK &emsp;</td>
																					<td rowspan ="2" class="colhead">OPENING BALANCE</td>
																					<?php foreach($MonthsArr as $key => $value){ ?>
																					<td colspan ="2" class="colhead"><?php echo $value; ?></td>
																					<?php } ?>
																					<td colspan ="2" class="colhead">TOTAL OF</td>
																					<!--<td rowspan ="2" class="colhead">PTA IN</td>
																					<td rowspan ="2" class="colhead">PTA OUT</td>-->
																					<td rowspan ="2" class="colhead">CLOSING BALANCE</td>
																					<td rowspan ="2" class="colhead" style="font-weight:bold;">REMARKS</td>
																				</tr>
																				<tr>
																					<?php foreach($MonthsArr as $key => $value){ ?>
																						<td class="colhead">CREDIT</td>
																						<td class="colhead">DEBIT</td>
																					<?php } ?>
																					<td class="colhead">CREDIT</td>
																					<td class="colhead">DEBIT</td>
																				</tr>
																			<?php
																			$TotSumBillVal = 0;
																			$TotAmtPerYr	= 0;
																			$MonthwiseArr = array(); $MonthwiseArrDbt = array();
																			if($rs_date_sql == true){
																				$sno=1;
																				$TotSumBillVal = 0;
																				while($List = mysqli_fetch_object($rs_date_sql)){
																					$SheetId 	= $List->sheetid;
																					$BillVal 	= $List->abstract_net_amt;
																					
																					//echo $SDAmt;
																					$BillValIndForm = IndianMoneyFormat($BillVal);
																					$SGSTPERC 	= $List->sgst_tds_perc;
																					$SGSTAMT 	= $List->sgst_tds_amt;
																					$CGSTPERC 	= $List->cgst_tds_perc;
																					$CGSTAMT 	= $List->cgst_tds_amt;
																					$IGSTPERC 	= $List->igst_tds_perc;
																					$IGSTAMT 	= $List->igst_tds_amt;
																					$VAmt 		= $List->vat_amt;
																					$MopType 	= $List->mop_type;
																					if($MopType == "RAB"){
																						$SDAmt 	= $List->sd_amt;
																					}else if($MopType == "SDR"){
																						$SDAmt 	= $List->net_payable_amt;
																					}else{
																						$SDAmt  = 0;
																					}
																					list($splityear, $splitmonth, $splitday) = explode("-", $List->payment_dt);
																					$DatePayDBVer	= $List->payment_dt;
																					//echo $splitmonth;

																					$DatePay			= dt_display($DatePayDBVer);
																					$BillValue 		= $List->abstract_net_amt;
																					//echo $SheetId;
																					$sql_select="SELECT contid,computer_code_no,work_name,short_name FROM sheet WHERE sheet_id ='" . $SheetId . "'";
																					//echo $sql_select;exit;
																					$sql_selectSql = mysqli_query($dbConn,$sql_select);
																					while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																						$ContId	= $List1['contid'];
																						$CCNum	 = $List1['computer_code_no'];
																						$ShortName = $List1['short_name'];
																						$WorkFullName = $List1['work_name'];
																						if(($ShortName == null) || ($ShortName == "")){
																							$WorkName = $WorkFullName;
																						}else{
																							$WorkName = $ShortName;
																						}
																						$sql_select1 ="SELECT * FROM contractor WHERE contid ='" . $ContId . "'";
																						$sql_selectSql1 = mysqli_query($dbConn,$sql_select1);	
																						while($List2 = mysqli_fetch_object($sql_selectSql1)){
																							$PANNum	  = $List2->pan_no;
																							$GSTNum	  = $List2->gst_no;
																							$ContName = $List2->name_contractor;
																							$PanType  = $List2->pan_type;
																							if($PanType == 'P'){
																								$PanPerc = 1;
																							}else{
																								$PanPerc = 2;
																							}
																							$ItaxVal = ($PanPerc / 100)* $BillValue;
																							$ItaxValIndFormat = IndianMoneyFormat($ItaxVal);
																							echo "<tr>";
																							echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'>" .  $CCNum . "</td>";
																							echo "<td class='labelleft'>" .  $ContName . "</td>";
																							echo "<td class='labelleft'>" . $WorkName . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'></td>";
																							$TotAmtPerRecord = 0;
																							foreach($MonthsArr as $key => $value){
																								if($splitmonth == $key){
																									$EchoVal = $SDAmt;
																									$EchoValFormated = IndianMoneyFormat($SDAmt);
																								}else{
																									$EchoVal = "";
																									$EchoValFormated = "";
																								}
																								if($MopType == "RAB"){
																									$CreditAmt = $EchoValFormated;
																								}else{
																									$CreditAmt  = "";
																								}
																								if($MopType == "SDR"){
																									$DebitAmt  = $EchoValFormated;
																								}else{
																									$DebitAmt = "";
																								}
																								echo "<td class='labelright' nowrap='nowrap'>" . $CreditAmt . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $DebitAmt . "</td>";
																								if($EchoVal == ""){
																									$EchoVal = 0;
																								}else{
																									$EchoVal = $EchoVal;
																								}
																								if($MopType == "RAB"){
																									$MonthwiseArr[$key] = $MonthwiseArr[$key] + $EchoVal;
																									$TotAmtPerRecord = $TotAmtPerRecord+$EchoVal;
																									$TotAmtPerYr = $TotAmtPerYr+$EchoVal;
																									$TotAmtPerRecordIndFor = IndianMoneyFormat($TotAmtPerRecord);
																								}
																								if($MopType == "SDR"){
																									$MonthwiseArrDbt[$key] = $MonthwiseArrDbt[$key] + $EchoVal;
																									$TotAmtPerRecordDbt = $TotAmtPerRecordDbt+$EchoVal;
																									$TotAmtPerYrDbt = $TotAmtPerYrDbt+$EchoVal;
																									$TotAmtPerRecordIndForDbt = IndianMoneyFormat($TotAmtPerRecordDbt);
																								}
																							}
																							echo "<td class='labelright' nowrap='nowrap'>" . $TotAmtPerRecordIndFor . "</td>";
																							echo "<td class='labelright' nowrap='nowrap'>" . $TotAmtPerRecordIndForDbt . "</td>";
																							// echo "<td class='labelright' nowrap='nowrap'></td>";
																							// echo "<td class='labelright' nowrap='nowrap'></td>";
																							echo "<td class='labelright' nowrap='nowrap'></td>";
																							echo "<td class='labelright' nowrap='nowrap'></td>";
																							echo "</tr>";
																							$TotAmtPerRecordIndFor = '';  $TotAmtPerRecordIndForDbt = '';
																						}
																					}
																					$sno++;
																				}
																				//print_r($MonthwiseArr);
																				$TotSumValIndFormat = IndianMoneyFormat($TotAmtPerYr);
																				$TotSumValIndFormatDbt = IndianMoneyFormat($TotAmtPerYrDbt);
																				if($TotAmtPerYr != 0){
																					echo "<tr>";
																					echo "<td class='labelcenter'></td>";
																					echo "<td class='labelcenter'></td>";
																					echo "<td class='labelcenter'></td>";
																					echo "<td class='labelleft'>TOTAL</td>";
																					echo "<td class='labelright' nowrap='nowrap'></td>";
																					foreach($MonthsArr as $key => $value){
																						if($MonthwiseArr[$key] != 0){
																							$MonthWiseIndFormat = IndianMoneyFormat($MonthwiseArr[$key]);
																						}else{
																							$MonthWiseIndFormat = '';
																						}
																						echo "<td class='labelright' nowrap='nowrap'>" . $MonthWiseIndFormat . "</td>";
																						if($MonthwiseArrDbt[$key] != 0){
																							$MonthWiseIndFormatDbt = IndianMoneyFormat($MonthwiseArrDbt[$key]);
																						}else{
																							$MonthWiseIndFormatDbt = "";
																						}
																						echo "<td class='labelright' nowrap='nowrap'>" . $MonthWiseIndFormatDbt . "</td>";
																					}
																					
																					echo "<td class='labelright' nowrap='nowrap'>" . $TotSumValIndFormat . "</td>";
																					echo "<td class='labelcenter'>" . $TotSumValIndFormatDbt . "</td>";
																					echo "<td class='labelcenter'></td>";
																					//echo "<td class='labelcenter'></td>";
																					//echo "<td class='labelcenter'></td>";
																					echo "<td class='labelcenter'></td>";
																					echo "</tr>";
																				}
																			} ?>
																			</table>
																		<?php } 
																		if($NoRecVal == 1){
																						echo"<div>";
																						echo"<div class='labelcenter' colspan='9'>No Records Found</div>";
																						echo"</div>";
																					}  ?>
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
						$(location).attr("href","SDAnnualBroadsheet.php");
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

$("body").on("click","#btn_view", function(event){
	var SelYear		= $("#txt_year").val();
	if(SelYear == ""){
		BootstrapDialog.alert("Please select atleast one period..!!");
		event.preventDefault();
		event.returnValue = false;
	}
});


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
