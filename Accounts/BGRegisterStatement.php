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
			$sql_date = "SELECT * FROM bg_fdr_details WHERE inst_type = 'BG' AND createdon BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY master_id ASC;";
			$LoiFrSheetQuery = "SELECT globid,loa_pg_id,sheetid FROM loi_entry WHERE createddate BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY tr_id ASC;";
			//$sql_date = "SELECT a.*,b.* FROM loi_entry a INNER JOIN bg_fdr_details b ON a.loa_pg_id=b.master_id WHERE 
			//a.sheetid = '" . $SheetId . "' AND a.globid = '" . $GlobId . "' AND a.contid = '" . $ContId . "' AND b.inst_type = 'FDR'";

			//$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
			//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
			//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
			$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
		}
		if($YearMonthVal == 2){
			if($month == 'ALL'){
				$sql_date = "SELECT * FROM bg_fdr_details WHERE inst_type = 'BG' AND YEAR(createdon) =" . $year . " ORDER BY master_id ASC;";
				$LoiFrSheetQuery = "SELECT globid,loa_pg_id,sheetid FROM loi_entry WHERE YEAR(createddate) =" . $year . " ORDER BY tr_id ASC;";
				//$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";
				//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - '. $year;
			}else{
				$sql_date = "SELECT * FROM bg_fdr_details WHERE inst_type = 'BG' AND MONTH(createdon)=" . $month . " AND YEAR(createdon) =" . $year . " ORDER BY master_id ASC;";
				$LoiFrSheetQuery = "SELECT globid,loa_pg_id,sheetid FROM loi_entry WHERE MONTH(createddate)=" . $month . " AND YEAR(createddate) =" . $year . " ORDER BY tr_id ASC;";

				//$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $year ." ORDER BY payment_dt ASC;";
				$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - ' . $mon . '/' . $year;
			}
		}
		if($sql_date != null){
			$rs_date_sql = mysqli_query($dbConn,$sql_date);
			if($rs_date_sql == true){
				if(mysqli_num_rows($rs_date_sql)>0){
					$report = true;
					$LoiFrSheetQuerySql = mysqli_query($dbConn,$LoiFrSheetQuery);
					// /echo $LoiFrSheetQuery;
					//echo $sql_date;exit;
				}else{
					$NoRecVal = 1;
					//echo 2;exit;
				}
			}
		}
		if($LoiFrSheetQuerySql == true){
			if(mysqli_num_rows($LoiFrSheetQuerySql)>0){
				$LoiShArr = array();
				while($ShIdList = mysqli_fetch_object($LoiFrSheetQuerySql)){
					$LoiShArr[$ShIdList->loa_pg_id] = $ShIdList->globid;
				}
			}
			//print_r($LoiShArr);
		}
	}
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
												<div class="card-header inkblue-card" align="left">&nbsp;BANK GUARANTEE REGISTER <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="div2 pd-lr-1" id="YearRow">
															<div class="lboxlabel-sm">&nbsp;Select Year</div>
															<div>
																<select name='txt_year' id='txt_year' class='tboxclass'>
																	<option value="">---Select---</option>
																	<?php echo $objBind->BindYear(0); ?>
																</select>
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
												<!-- <div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div> -->
												<div class="card-header inkblue-card" align="center">BANK GUARANTEE REGISTER <?php echo $heading; ?> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-download"></i> </span></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12">&nbsp;</div>
																<?php if($report==true){ ?>
																	<table id="example" class="dataTable rtable table2excel example" border="1" width="100%" align="center">
																		<tr>
																			<td class="colhead">S.No</td>
																			<td class="colhead">C.CODE NO.</td>
																			<td class="colhead">BG FOR</br>(PG/SD/</br>MOB.ADV/</br>P&M ADV.)</td>
																			<td class="colhead">NAME & ADDRESS OF</br>CONTRACTOR</td>
																			<td class="colhead">AGREEMENT NO.</td>
																			<td class="colhead">NAME OF THE ENGINEER,</br>DESIG, SECTION,</br>CONTACT NO.</td>
																			<td class="colhead" nowrap='nowrap'>NAME OF THE WORK</td>
																			<td class="colhead">DATE OF</br>COMPLETION</br>OF WORK</td>
																			<td class="colhead">AMOUNT</td>
																			<td class="colhead" nowrap='nowrap'>BG NO. &</br>DATE</td>
																			<td class="colhead">NAME OF</br>THE BANK &</br>ADDRESS</td>
																			<td class="colhead">VALIDITY</br>UPTO</td>
																			<td class="colhead">CLAIM</br>PERIOD</br>UPTO</td>
																			<td class="colhead">RELEASED</br>ON</td>
																			<td class="colhead">INT. OF</br>AAO</td>
																		</tr>

																		<?php
																		$TotSumBillVal = 0;
																		if($rs_date_sql == true){
																			$sno=1;
																			$TotSumBillVal = 0;
																			while($List = mysqli_fetch_object($rs_date_sql)){
																				//echo 4;
																				$MasterLoiId 	= $List->master_id;
																				$GlobId 	= $List->globid;
																				$ContId 	= $List->contid;
																				$InstPur = $List->inst_purpose;
																				$InstType = $List->inst_type;
																				$InstAmt = $List->inst_amt;
																				$InstSrNum  = $List->inst_serial_no;
																				$InstBkName = $List->inst_bank_name;
																				$InstSrNum 	= $List->inst_serial_no;
																				$InstDt 		= $List->inst_date;
																				$InstExpDt 	= $List->inst_exp_date;
																				if(($InstAmt != "")||($InstAmt != null)){
																					$InstAmtIndForm = IndianMoneyFormat($InstAmt);
																				}else{
																					$InstAmtIndForm = "";
																				}
																				if($InstDt != ""){
																					$DisplInstDt = dt_display($InstDt);
																				}
																				if($InstExpDt != ""){
																					$DisplInstExpDt = dt_display($InstExpDt);
																				}
																				$InstStatus	= $List->inst_status;
																				$InstRelDt	= $List->released_date;
																				$CreatedBy	= $List->createdby;
																				//echo 5;
																				$GlobId 	= $LoiShArr[$MasterLoiId];
																				$sql_select="SELECT globid,contid,work_name,computer_code_no,agree_no,date_of_completion,assigned_staff,work_orders_ext FROM sheet WHERE globid ='" . $GlobId . "'";
																				//echo $sql_select;exit;
																				$sql_selectSql = mysqli_query($dbConn,$sql_select);
																				if($sql_selectSql == true){
																					if(mysqli_num_rows($sql_selectSql)){
																						while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																							//echo 6;
																							$GlobId	= $List1['globid'];
																							$ContId	= $List1['contid'];
																							$WrkName	= $List1['work_name'];
																							$CCode	= $List1['computer_code_no'];
																							$AgreeNo	= $List1['agree_no'];
																							if(($List1['work_orders_ext'] != "")&&($List1['work_orders_ext'] != "0000-00-00")&&($List1['work_orders_ext'] != NULL)){
																								$ClaimperiodDt = date('Y-m-d', strtotime($List1['work_orders_ext']. ' +8 months'));
																								$ClaimperiodDtDisp = dt_display($ClaimperiodDt);
																							}else if(($List1['date_of_completion'] != "")&&($List1['date_of_completion'] != "0000-00-00")&&($List1['date_of_completion'] != NULL)){
																								$ClaimperiodDt = date('Y-m-d', strtotime($List1['date_of_completion'].' +8 months'));
																								$ClaimperiodDtDisp = dt_display($ClaimperiodDt);
																								
																							}else{
																								$ClaimperiodDtDisp = "";
																							}
																							
																							if(($List1['date_of_completion'] != "")&&($List1['date_of_completion'] != NULL)&&($List1['date_of_completion'] != "0000-00-00")){
																								$WrkCompDt = $List1['date_of_completion'];
																								$WrkCompDate = dt_display($List1['date_of_completion']);
																							}else{
																								$WrkCompDate = "";
																							}
																							$AssignStaff = $List1['assigned_staff'];
																							$AssignStaffList = explode(",",$AssignStaff);
																							//$AssignStaffImplod = implode(',',$AssignStaffList);
																							$StaffSelect ="SELECT a.*,b.section_name,c.designationname FROM staff a INNER JOIN staff_section b ON a.sectionid=b.sectionid INNER JOIN designation c ON a.designationid=c.designationid WHERE a.staffid IN(".$AssignStaff.")";
																							//echo $StaffSelect ."<br>";
																							$StaffSelectSql = mysqli_query($dbConn,$StaffSelect);
																							$List4 = mysqli_fetch_object($StaffSelectSql);
																							$EICStaffName	= $List4->staffname;
																							$EICMobNo	   = $List4->mobile;
																							$SectionName   = $List4->section_name;
																							$DesigName	   = $List4->designationname;
																									
																							$FDDt  	 	 = dt_display($List3->bg_date);
																							$FDExpDt  	 = dt_display($List3->bg_exp_date);
																							$FDSerNo  	 = $List3->bg_serial_no;
			
																							$sql_select1 ="SELECT name_contractor,addr_contractor FROM contractor WHERE contid ='" . $ContId . "'";
																							$sql_selectSql1 = mysqli_query($dbConn,$sql_select1);	
																							while($List2 = mysqli_fetch_object($sql_selectSql1)){
																								//echo 7;
																								$ContName = $List2->name_contractor;
																								$ContAddr = $List2->addr_contractor;
																								//	date('Y-m-d', strtotime($date. ' + 3 months'));
																								echo "<tr>";
																								echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																								echo "<td class='labelcenter' nowrap='nowrap'>" .  $CCode . "</td>";
																								echo "<td class='labelcenter'>" . $InstPur . "</td>";
																								echo "<td class='labelleft'>" . $ContName . "</br> " . $ContAddr . "</td>";
																								echo "<td class='labelleft'>" .  $AgreeNo . "</td>";
																								echo "<td class='labelleft'>". $EICStaffName ." , " . $DesigName . " , " . $SectionName . " , " . $EICMobNo . " </td>";
																								echo "<td class='labelleft'>" . $WrkName . "</td>";
																								echo "<td class='labelcenter' nowrap='nowrap'>" . $WrkCompDate . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $BGAmountIndFor . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $BGnoBgDt . "</td>";
																								echo "<td class='labelright'>" . $PGBankName . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $BGExpDt . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $ClaimperiodDtDisp . "</td>";
																								echo "<td class='labelright' nowrap='nowrap'>" . $BGReleaseDt . "</td>";
																								echo "<td class='labelright'> </td>";
																								echo "</tr>";
																							}
																						}
																					}
																				}
																				$TotSumBillVal = $TotSumBillVal+$ItaxVal;
																				$sno++;
																			}
																			$TotSumValIndFormat = IndianMoneyFormat($TotSumBillVal);
																		}
																		?>
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


							<!--<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div3">&nbsp;</div>
									<div class="div6">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">BANK GUARANTEE REGISTER </div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv group-div" id="table-stmt">
																
															
																<div class="div12">&nbsp;</div>																				
																<div class="div4 pl-2 pr-2">
																	<div class="div12 inputGroup">
																		<input type="radio" name="type" value="Year" id="type_year" onclick="show()" />
																		<label for="type_year" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Year</label>
																	</div>
																</div>
																<div class="row div8" id="year" >
																	<div class="lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year&nbsp;&nbsp;&nbsp;
																		<input type="text" style="width:32%;" name="txt_year" value="<?php //echo date('Y') ?>" class="textboxdisplay"  size="11" maxlength="4"/>
																	</div>
																</div>
																<div class="div12">&nbsp;</div>



																<div class="div4 pl-2 pr-2">
																	<div class="div12 inputGroup">
																		<input type="radio" name="type" value="Month" id="type_month" onclick="show()"/>
																		<label for="type_month" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Month</label>
																	</div>
																</div>
																<div class="row div8" id="month" >
																	<div class="lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Month&nbsp;&nbsp;
																		<select style="width:30%;" name="cmb_mon" id="cmb_mon" class="textboxdisplay">
																			<option value="Select">Select</option>
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
																<div class="div12">&nbsp;</div>


																
																<div class="div4 pl-2 pr-2">
																	<div class="div12 inputGroup">
																		<input type="radio" name="type" value="Day" id="type_day" onclick="show()"/>
																		<label for="type_day" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Period</label>
																	</div>
																</div>
																<div class=" div8" align="center" id="day">
																	<div class="div6 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From Date&nbsp;
																		<input type="text"  style="width:55%;" value="<?php //echo date('d/m/Y');?>" readonly="" name="txt_fromdt" id="txt_fromdt" size="11" maxlength="10" class="datepicker textboxdisplay" />
																	</div>
																	<div class="div6 lboxlabel" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																		<input type="text"  style="width:55%;" value="<?php //echo date('d/m/Y');?>" readonly="" name="txt_todt" id="txt_todt" size="11" maxlength="10" class="datepicker textboxdisplay" />
																	</div>
																</div>


															</div>
														</div>
													</div>
												</div>
												<div class="row div12">
													<input type="image" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="return validation()" />
												</div>
												<div class="div12">&nbsp;</div>
											</div>														
										</div>
									</div>												
									<div class="div3">&nbsp;</div>
								</div>
							</div>

							<div class="row">
								<div class="box-container box-container-lg" align="center">
									<div class="div12" id="appendrow">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div>
												<div class="card-header inkblue-card" align="center">BANK GUARANTEE REGISTER <?php //echo $heading; ?></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="div12">&nbsp;</div>
																<?php //if($report==true){ ?>
																	<table class="table dataTable rtable table2excel" border="1" width="100%" align="center">
																		<tr>
																			<td class="colhead">S.No</td>
																			<td class="colhead">CCODE</td>
																			<td class="colhead">BG FOR</br>(PG/SD/</br>MOB.ADV/</br>P&M ADV.)</td>
																			<td class="colhead">NAME & ADDRESS OF</br>CONTRACTOR</td>
																			<td class="colhead">AGREEMENT NO.</td>
																			<td class="colhead">NAME OF THE ENGINEER,</br>DESIG, SECTION,</br>CONTACT NO.</td>
																			<td class="colhead">NAME OF THE WORK</td>
																			<td class="colhead">DATE OF</br>COMPLETION</br>OF WORK</td>
																			<td class="colhead">AMOUNT</td>
																			<td class="colhead">BG NO. &</br>DATE</td>
																			<td class="colhead">NAME OF</br>THE BANK &</br>ADDRESS</td>
																			<td class="colhead">VALIDITY</br>UPTO</td>
																			<td class="colhead">CLAIM</br>PERIOD</br>UPTO</td>
																			<td class="colhead">RELEASED</br>ON</td>
																			<td class="colhead">INT. OF</br>AAO</td>
																		</tr>
																	<?php
																	/*	$TotSumBillVal = 0;
																	if($rs_date_sql == true){
																		$sno=1;
																		$TotSumBillVal = 0;
																		while($List = mysqli_fetch_object($rs_date_sql)){
																			$SheetId 	= $List->sheetid;
																			$BillVal 	= $List->abstract_net_amt;
																			$BillValIndForm = IndianMoneyFormat($BillVal);
																			$DatePay	= dt_display($List->payment_dt);
																			
																			$sql_select="select globid,contid,work_name,computer_code_no,agree_no,date_of_completion,assigned_staff from sheet where sheet_id ='" . $SheetId . "'";
																			$sql_selectSql = mysqli_query($dbConn,$sql_select);
																			while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																				$GlobId		= $List1['globid'];
																				$ContId		= $List1['contid'];
																				$WrkName	= $List1['work_name'];
																				$CCode		= $List1['computer_code_no'];
																				$AgreeNo	= $List1['agree_no'];
																				$WrkCompDate = dt_display($List1['date_of_completion']);
																				$AssignStaff = $List1['assigned_staff'];
																				$AssignStaffList = explode(",",$AssignStaff);
																				//print_r($AssignStaffList);exit;SELECT * FROM table WHERE id IN (1,2,3,4);
																				$StaffSelect ="SELECT a.*,b.section_name,c.designationname FROM staff a INNER JOIN staff_section b ON a.sectionid=b.sectionid INNER JOIN designation c ON a.designationid=c.designationid WHERE a.staffid IN(".implode(',',$AssignStaffList).") AND a.sroleid = '$EICRole'";
																				//echo $StaffSelect;
																				$StaffSelectSql = mysqli_query($dbConn,$StaffSelect);
																				$List4 = mysqli_fetch_object($StaffSelectSql);
																				$EICStaffName	= $List4->staffname;
																				$EICMobNo	 = $List4->mobile;
																				$SectionName = $List4->section_name;
																				$DesigName	 = $List4->designationname;

																				$sql_select2 = "SELECT a.*,b.* FROM loi_pg a INNER JOIN loi_pg_detail b ON a.loa_pg_id=b.loa_pg_id WHERE 
																				a.sheetid = '" . $SheetId . "' AND a.globid = '" . $GlobId . "' AND a.contid = '" . $ContId . "' AND b.bg_type = 'BG'";
																				
																				$sql_selectSql2 = mysqli_query($dbConn,$sql_select2);
																				while($List3 = mysqli_fetch_object($sql_selectSql2)){
																					$PGBankName	 = $List3->bank_name;
																					$BGAmount	 = $List3->bg_amt;
																					$BGDt  	 	 = dt_display($List3->bg_date);
																					$BGExpDt  	 = dt_display($List3->bg_exp_date);
																					$BGSerNo  	 = $List3->bg_serial_no;

																					$sql_select1 ="select * from contractor where contid ='" . $ContId . "'";
																					//echo $sql_select1;
																					$sql_selectSql1 = mysqli_query($dbConn,$sql_select1);	
																					while($List2 = mysqli_fetch_object($sql_selectSql1)){
																						$PANNum	  = $List2->pan_no;
																						$GSTNum	  = $List2->gst_no;
																						$ContName = $List2->name_contractor;
																						$ContAddr = $List2->addr_contractor;
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
																						echo "<td class='labelcenter' nowrap='nowrap'>" .  $CCode . "</td>";
																						echo "<td class='labelleft'> </td>";
																						echo "<td class='labelleft'>" . $ContName . ", " . $ContAddr . "</td>";
																						echo "<td class='labelcenter' nowrap='nowrap'>" .  $AgreeNo . "</td>";
																						echo "<td class='labelright'>". $EICStaffName ." , " . $DesigName . " , " . $SectionName . " , " . $EICMobNo . " </td>";
																						echo "<td class='labelright'>" . $WrkName . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $WrkCompDate . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $BGAmount . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $BGSerNo . " - " . $BGDt . "</td>";
																						echo "<td class='labelright'>" . $PGBankName . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $BGExpDt . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'> </td>";
																						echo "<td class='labelright' nowrap='nowrap'> </td>";
																						echo "<td class='labelright'> </td>";
																						echo "</tr>";
																					}
																				}
																			}
																			$TotSumBillVal = $TotSumBillVal+$ItaxVal;
																			$sno++;
																		}
																		$TotSumValIndFormat = IndianMoneyFormat($TotSumBillVal);

																	}
																	else
																	{
																		echo"<tr>";
																		echo"<td class='labelcenter' colspan='9'>No Records Found</td>";
																		echo"</tr>";
																	}
																	?>
																	</table>
																	<?php } */?>
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
								</div>-->

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
$('.dataTable').DataTable({"paging":true,"ordering": true});

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
	table.dataTable > thead > tr > th{
		padding:2px !important;
		font-size:11px !important;
	}
	/* table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	} */
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
