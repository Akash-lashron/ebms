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
function getIndianCurrencyWord($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
}
//if(isset($_POST['btn_view'])){
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
			$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE incometax_amt != 0 AND payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
			//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
			//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
			$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
		}
		if($YearMonthVal == 2){
			if($month == 'ALL'){
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE incometax_amt != 0 AND YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";
				//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - '. $year;
			}else{
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE incometax_amt != 0 AND MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $year ." ORDER BY payment_dt ASC;";
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

	/* $year = $_POST['txt_year'];
	$month = $_POST['cmb_mon'];
	$sql_date = "select * from memo_payment_accounts_edit where MONTH(payment_dt)=" . $month . " and YEAR(payment_dt) =" . $year;
	$rs_date_sql = mysqli_query($dbConn,$sql_date);
	$report = true;
	$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
	$heading='FOR - ' . $mon . '/' . $year; */
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
						
						<blockquote class="bq1" style="overflow:auto">
							<!--<div class="row">
								<input type="hidden" name="max_group" id="max_group" value="1" />
									<div class="row">
										<div class="box-container box-container-lg" align="center">
											<div class="div12">
												<div class="card cabox">
													<div class="face-static">
														<div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div>
														<div class="card-header inkblue-card" align="center">INCOME TAX RECOVERY DETAILS IN RESPECT OF CONTRACTORS <?php// echo $heading; ?></div>
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
																				$BillValIndForm = IndianMoneyFormat($BillVal);
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
																						$ItaxValIndFormat = IndianMoneyFormat($ItaxVal);
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
																			$TotSumValIndFormat = IndianMoneyFormat($TotSumBillVal);

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
											<div class="card-header inkblue-card" align="left">&nbsp;IT Recovery Statement <span id="CourseChartDuration"></span></div>
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
													
													<div class="div2 pd-lr-1" id="YearRow">
														<div class="lboxlabel-sm">&nbsp;Select Year</div>
														<div>
															<select name='txt_year' id='txt_year' class='tboxsmclass'>
																<option value="">---Select---</option>
																<?php echo $objBind->BindYear(0); ?>
															</select>
														</div>
													</div>
													
													<div class="div2 pd-lr-1" id="MonthRow">
														<div class="lboxlabel-sm">&nbsp;Select Month</div>
														<div>
															<select name="cmb_mon" id="cmb_mon" class="tboxsmclass">
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
															<input type="text" value="" readonly="" name="txt_fromdt" id="txt_fromdt" class="tboxsmclass datepicker" />
														</div>
													</div>
													<div class="div2 pd-lr-1" id="PeriodRow2">
														<div class="lboxlabel-sm">&nbsp;To Date</div>
														<div>
															<input type="text" value="" readonly="" name="txt_todt" id="txt_todt" class="tboxsmclass datepicker" />
														</div>
													</div>
													
													<div class="div1 pd-lr-1">
														<div class="lboxlabel-sm">&nbsp;</div>
														<div>
															<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value="View" style="margin-top:0px;">
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
														<!-- <div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div> -->
														<div class="card-header inkblue-card" align="center">INCOME TAX RECOVERY DETAILS IN RESPECT OF CONTRACTORS <?php echo $heading; ?> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel">Download Excel <i class="fa fa-download"></i> </span></span></div>
														<div class="card-body padding-1 ChartCard" id="CourseChart">
															<div class="divrowbox pt-2">
																<div class="table-responsive dt-responsive ResultTable">
																	<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																		<div class="div12">&nbsp;</div>
																		<?php if($report==true){ ?>
																			<table class="table dataTable rtable table2excel example" border="1" width="100%" align="center">
																				<tr>
																					<td class="colhead">S.No</td>
																					<td class="colhead">PAN</td>
																					<td class="colhead">NAME OF CONTRACTOR</td>
																					<td class="colhead">BILL VALUE</td>
																					<td class="colhead">BILL VALUE IT</td>
																					<td class="colhead">DATE OF PAYMENT</td>
																					<td class="colhead">LDC CERTI. NO.</td>
																					<td class="colhead">LDC VALIDITY</td>
																					<td class="colhead">PERCENT (%)</td>
																					<td class="colhead">IT-TAX AMOUNT</td>
																					<td class="colhead" style="font-weight:bold;">REMARKS</td>
																				</tr>
																			<?php
																			$TotSumBillVal = 0;
																			$TotSumBillValIT = 0;
																			if($rs_date_sql == true){
																				$sno=1;
																				$TotSumBillVal = 0;
																				$TotSumBillValIT = 0;
																				while($List = mysqli_fetch_object($rs_date_sql)){
																					$SheetId 	= $List->sheetid;
																					$BillVal 	= $List->abstract_net_amt;
																					$ITBillVal 	= $List->bill_amt_it;
																					$BillValIndForm 	= round($BillVal);		//IndianMoneyFormat($BillVal);
																					$ITBillValIndForm = round($ITBillVal);	//IndianMoneyFormat($ITBillVal);
																					$SGSTPERC 	= $List->sgst_tds_perc;
																					$SGSTAMT 	= $List->sgst_tds_amt;
																					$CGSTPERC 	= $List->cgst_tds_perc;
																					$CGSTAMT 	= $List->cgst_tds_amt;
																					$IGSTPERC 	= $List->igst_tds_perc;
																					$IGSTAMT 	= $List->igst_tds_amt;
																					$VAmt 		= $List->vat_amt;
																					$DatePay		= dt_display($List->payment_dt);
																					if(($List->ldc_validity != '0000-00-00')&&($List->ldc_validity != NULL)){
																						$LdcValidity = dt_display($List->ldc_validity);
																					}else{
																						$LdcValidity = '';
																					}
																					//echo $SheetId;
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
																							$ItaxVal = $List->incometax_amt;//($PanPerc / 100)* $BillVal;
																							$ItaxValIndFormat = round($ItaxVal);		//IndianMoneyFormat($ItaxVal);
																							echo "<tr>";
																							echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'>" .  $PANNum . "</td>";
																							echo "<td class='labelleft' nowrap='nowrap'>" .  $ContName . "</td>";
																							echo "<td class='labelright'>" . $BillValIndForm . "</td>";
																							echo "<td class='labelright'>" . $ITBillValIndForm . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'>" .  $DatePay . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'>" .  $List->ldc_certi_no . "</td>";
																							echo "<td class='labelcenter' nowrap='nowrap'>" .  $LdcValidity . "</td>";
																							echo "<td class='labelright' nowrap>". $PanPerc ."</td>";
																							echo "<td class='labelright' nowrap='nowrap'>" . $ItaxValIndFormat . "</td>";
																							echo "<td class='labelright' nowrap='nowrap'></td>";
																							echo "</tr>";
																						}
																					}
																					$TotSumBillVal = $TotSumBillVal+$BillVal;
																					$TotSumBillValIT = $TotSumBillValIT+$ItaxVal;
																					$sno++;
																				}
																				//echo $TotSumBillVal;exit;
																				$TotSumValIndFormat 	 = round($TotSumBillVal);		//IndianMoneyFormat($TotSumBillVal);
																				$TotSumValIndFormatWord 	 = getIndianCurrencyWord($TotSumBillVal);
																				$ITTotSumValIndFormat = round($TotSumBillValIT);		//IndianMoneyFormat($TotSumBillValIT);
																				$ITTotSumValIndFormatWord = getIndianCurrencyWord($TotSumBillValIT);

																				echo "<tr>";

																				echo "<td colspan='3' class='labelright'> TOTAL</td>";
																				echo "<td class='labelright' nowrap='nowrap'>" . $TotSumValIndFormat . "</td>";
																				echo "<td colspan='5' class='labelright'> </td>";
																				
																				echo "<td class='labelright' nowrap='nowrap'>" . $ITTotSumValIndFormat . "</td>";
																				echo "<td class='labelcenter'></td>";
																				echo "</tr>";
																			}	?>
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
	$('#month').hide();
	$('#year').hide();
	$('#day').hide();
	$('#day1').hide();
	$("#txt_year").chosen();
	$("#cmb_mon").chosen();
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
