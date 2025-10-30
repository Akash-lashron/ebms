<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
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
$CurrYear = "2022";
$NextYear = "2023";
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script src="handsontable/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="handsontable/handsontable/dist/handsontable.full.min.css">
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<link href="css/CustomFancyStyle.css" rel="stylesheet">
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">Committed Expenditure Update - Financial Year</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="PartPaymentPageView.php">
							<div class="div12">&nbsp;</div>
                            <!--<div class="div12">
								<div class="div12 card-div-body">
									<div class="top-card">
										<div class="top-card-container">
											<div class="div12">
												<div class="div1">
													<label for="name" class="card-label">Work Name </label>
												</div>
												<div class="div5">
													<select name="cmb_unit" id="cmb_unit" class="card-label-selectbox-lg">
														<option value=""> -- Select --</option>
													</select>
												</div>
												<div class="div1">
													<label for="name" class="card-label">PIN No. </label>
												</div>
												<div class="div2">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div1" align="center">
													<label for="name" class="card-label">HOA </label>
												</div>
												<div class="div2">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>-->
							
							
							<!--<div class="div12">
								<div class="div3 card-div-body">
									<div class="top-card">
										<div class="top-card-container">
											<div class="div12">
												<div class="div4">
													<label for="name" class="card-label-s">January</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">February</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">March</label>
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div4">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
											</div>
										</div>
									</div>
									<div class="div12">&nbsp;</div>
								</div>
								<div class="div3 card-div-body">
									<div class="top-card">
										<div class="top-card-container">
											<div class="div12">
												<div class="div4">
													<label for="name" class="card-label-s">April</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">May</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">June</label>
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div4">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
											</div>
										</div>
									</div>
									<div class="div12">&nbsp;</div>
								</div>
								<div class="div3 card-div-body">
									<div class="top-card">
										<div class="top-card-container">
											<div class="div12">
												<div class="div4">
													<label for="name" class="card-label-s">July</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">August</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">September</label>
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div4">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
											</div>
										</div>
									</div>
									<div class="div12">&nbsp;</div>
								</div>
								<div class="div3 card-div-body">
									<div class="top-card">
										<div class="top-card-container">
											<div class="div12">
												<div class="div4">
													<label for="name" class="card-label-s">October</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">November</label>
												</div>
												<div class="div4" align="center">
													<label for="name" class="card-label-s">December</label>
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div4">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
												<div class="div4" align="center">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" disabled="disabled">
												</div>
											</div>
										</div>
									</div>
									<div class="div12">&nbsp;</div>
								</div>
							</div>-->
							
							<div class="div12 no-padding" id="new-data">
								<div id="<?php echo $CCNO; ?>">
								<div class="well well-sm lboxlabel">
									<div class="row">
										<div class="div2">
										Name of Work :
										</div> 
										<div class="div6">
										<select name="cmb_tender_no" id="cmb_tender_no" class="form-control tboxsmclass"  style="width:60%">
											<option value=""> ------------------- Select ----------------</option>
										</select>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q1</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<!--<th></th>-->
															<th id="APR" nowrap="nowrap">APR-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="MAY" nowrap="nowrap">MAY-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">JUNE-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <!--<td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>-->
															<td><input type="text" class="form-control rtext Q1CE1" id="Q1CE1" name="Q1CE1"></td>
															<td><input type="text" class="form-control rtext Q1CE2" id="Q1CE2" name="Q1CE2"></td>
															<td><input type="text" class="form-control rtext Q1CE3" id="Q1CE3" name="Q1CE3"></td>
															<td><input type="text" class="form-control rtext Q1CET" id="Q1CET" name="Q1CET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q1AE1" id="Q1AE1" name="Q1AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AE2" id="Q1AE2" name="Q1AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AE3" id="Q1AE3" name="Q1AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1AET" id="Q1AET" name="Q1AET" readonly=""></td>
														</tr>-->
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q1BAL1" id="Q1BAL1" name="Q1BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1BAL2" id="Q1BAL2" name="Q1BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q1BAL3" id="Q1BAL2" name="Q1BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q2</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<!--<th></th>-->
															<th id="JULY" nowrap="nowrap">JULY-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="AUG" nowrap="nowrap">AUG-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="SEP" nowrap="nowrap">SEP-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr >
															<!--<td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>-->
															<td><input type="text" class="form-control rtext Q2CE1" id="Q2CE1" name="Q2CE1"></td>
															<td><input type="text" class="form-control rtext Q2CE2" id="Q2CE2" name="Q2CE2"></td>
															<td><input type="text" class="form-control rtext Q2CE3" id="Q2CE3" name="Q2CE3"></td>
															<td><input type="text" class="form-control rtext Q2CET" id="Q2CET" name="Q2CET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q2AE1" id="Q2AE1" name="Q2AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AE2" id="Q2AE2" name="Q2AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AE3" id="Q2AE3" name="Q2AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2AET" id="Q2AET" name="Q2AET" readonly=""></td>
														</tr>-->
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q2BAL1" id="Q2BAL1" name="Q2BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2BAL2" id="Q2BAL2" name="Q2BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q2BAL3" id="Q2BAL2" name="Q2BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q3</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<!--<th></th>-->
															<th id="OCT" nowrap="nowrap">OCT-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="NOV" nowrap="nowrap">NOV-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="DEC" nowrap="nowrap">DEC-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL-<?php echo $CurrYear; ?><br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <!--<td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>-->
															<td><input type="text" class="form-control rtext Q3CE1" id="Q3CE1" name="Q3CE1"></td>
															<td><input type="text" class="form-control rtext Q3CE2" id="Q3CE2" name="Q3CE2"></td>
															<td><input type="text" class="form-control rtext Q3CE3" id="Q3CE3" name="Q3CE3"></td>
															<td><input type="text" class="form-control rtext Q3CET" id="Q3CET" name="Q3CET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q3AE1" id="Q3AE1" name="Q3AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AE2" id="Q3AE2" name="Q3AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AE3" id="Q3AE3" name="Q3AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3AET" id="Q3AET" name="Q3AET" readonly=""></td>
														</tr>-->
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q3BAL1" id="Q3BAL1" name="Q3BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3BAL2" id="Q3BAL2" name="Q3BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q3BAL3" id="Q3BAL2" name="Q3BAL2" readonly=""></td>
														</tr>-->
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="div6" align="center">
										<div class="innerdiv2">
											<div class="row divhead" align="center">Q4</div>
											<div class="row innerdiv" align="center">
												<table border="1" class="table1 btable table table-striped" id="fixTable">
													<thead>
														<tr class="sticky-header">
															<!--<th></th>-->
															<th id="JAN" nowrap="nowrap">JAN-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="FEB" nowrap="nowrap">FEB-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="MAR" nowrap="nowrap">MAR-<?php echo $NextYear; ?><br/>(&#8377 in Lakhs)</th>
															<th id="JUNE" nowrap="nowrap">TOTAL<br/>(&#8377 in Lakhs)</th>
														</tr>
														<tr>
														    <!--<td valign="middle" class="sticky-cell sticky-cell9" align="center" style="color:#E51863">C.E.</td>-->
															<td><input type="text" class="form-control rtext Q4CE1" id="Q4CE1" name="Q4CE1"></td>
															<td><input type="text" class="form-control rtext Q4CE2" id="Q4CE2" name="Q4CE2"></td>
															<td><input type="text" class="form-control rtext Q4CE3" id="Q4CE3" name="Q4CE3"></td>
															<td><input type="text" class="form-control rtext Q4CET" id="Q4CET" name="Q4CET" readonly=""></td>
														</tr>
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">A.E.</td>
															<td><input type="text" class="form-control rtext Q4AE1" id="Q4AE1" name="Q4AE1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AE2" id="Q4AE2" name="Q4AE2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AE3" id="Q4AE3" name="Q4AE3" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4AET" id="Q4AET" name="Q4AET" readonly=""></td>
														</tr>-->
														<!--<tr>
															<td valign="middle" align="center" class="sticky-cell sticky-cell9" style="color:#017B0A">( - )</td>
															<td><input type="text" class="form-control rtext Q4BAL1" id="Q4BAL1" name="Q4BAL1" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4BAL2" id="Q4BAL2" name="Q4BAL2" readonly=""></td>
															<td><input type="text" class="form-control rtext Q4BAL3" id="Q4BAL2" name="Q4BAL2" readonly=""></td>
														</tr>-->
													</thead>
													
												</table>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="row">
									<div class="div12" align="center" style="margin-top:3px">
										<textarea class="form-control remarks" placeholder="Enter your remarks here" name="txt_remarks_modal" id="txt_remarks_modal"></textarea>
										<input type="hidden" name="txt_fin_yr_modal" id="txt_fin_yr_modal" value="<?php echo $FinaYears; ?>">
										<input type="hidden" name="txt_globid_modal" id="txt_globid_modal" class="txt_globid_modal">
										<input type="hidden" name="txt_pinid_modal" id="txt_pinid_modal" class="txt_pinid_modal">
									</div>
								</div>-->
								<div class="div12">&nbsp;</div>
							</div>
							</div>
							
							<div style="text-align:center">
								<div class="buttonsection" style="display:inline-table">
									<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
								</div>
								<div class="buttonsection" style="display:inline-table">
									<input type="submit" class="btn" data-type="submit" value=" Save " name="submit" id="submit"   />
								</div>
							</div>
							
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
		
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>

<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.form-control{
	width:95%;
}
</style>
</body>
</html>

