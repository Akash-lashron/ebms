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
<link href="css/CustomFancyStyle.css" rel="stylesheet">
<!--<link rel='stylesheet' href='Step-Wizard/bootstrap.min.css'/>-->
<link rel='stylesheet' href='Step-Wizard/BSMagic-min.css'/>
<script src='Library/Step-Wizard/BSMagic-min.js'></script>
<script src='Library/Step-Wizard/gsap.min.js'></script>

<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title-new"></div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="">
                            <div class="div12">
								
								<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div2"></div>
											<div class="div8 BSMagic flex-child green" style="margin-right:10px;" id="test">
												<div class="tab-content" id="v-pills-tabContent">
													<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
														<div class="div-tab-head">NIT Entry Form</div>
														<div class="div-tab-body">
															<!--<div class="erow"></div>-->
															
															
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<div class="row">
																				
																				<div class="div3 lboxlabel">Technical Sanction No.</div>
																				<div class="div9" align="left">
																					<select name="cmb_tender_no" id="cmb_tender_no" class="tboxsmclass">
																					<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Amount</div>
																				<div class="div3" align="left">
																					<input type="text" name="txt_entry_date" id="txt_entry_date" class="tboxsmclass">
																				</div>
																				<div class="div2 lboxlabel">TS Date</div>
																				<div class="div3" align="left">&nbsp;&nbsp;&nbsp;
																					<input type="date" name="txt_entry_date" id="txt_entry_date" class="tboxsmclass">
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Tender No.</div>
																				<div class="div9" align="left"><input type="text" name="txt_ts_number" id="txt_ts_number" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Name Of Work</div>
																				<div class="div9" align="left">
																					<textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass"></textarea>
																				</div>
																				
																				
																			</div>
																		</div>
								
																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">Tender Estimate (Rs.)</div>
																			<div class="div3" align="left"><input type="text" name="txt_tech_est" id="txt_tech_est" class="tboxsmclass"></div>
																			<div class="div3 lboxlabel">Cost of Tender</div>
																			<div class="div3">
																				<input type="text" name="txt_tender_cost" id="txt_tender_cost" class="tboxsmclass">
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">EMD (Rs.)</div>
																			<div class="div3" align="left"><input type="text" name="txt_emd_amt" id="txt_emd_amt" class="tboxsmclass"></div>
																			<div class="div3 lboxlabel">SD (% of Tender Value)</div>
																			<div class="div3">
																				<input type="text" name="txt_tender_value" id="txt_tender_value" class="tboxsmclass">
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">PBG (% of Tender Value)</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_tender_cost" id="txt_tender_cost" class="tboxsmclass">
																			</div>
																			<div class="div3 lboxlabel">Approving Authority</div>
																			<div class="div3">
																				<select name="cmb_app_auth" id="cmb_app_auth" class="tboxsmclass"  >
																					<option value=""> --- Select ---</option>
																				</select>
																			</div>
																			<div class="row clearrow"></div>
																			<div class="div3 lboxlabel">Time Allowed (Months)</div>
																			<div class="div3" align="left">
																				<select name="cmb_time_month" id="cmb_time_month" class="tboxsmclass"  >
																					<option value=""> --- Select ---</option>
																				</select>
																			</div>
																			<div class="row clearrow"></div>
																			
																													
																		<div class="row" align="center">
																			<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
																		</div>
																	</div>
																	</div>
																</div>
															</div>
															
															
															<!--<div class="erow"></div>-->
														</div>
													</div>
													
												</div>
											</div>
											<div class="div2"></div>
										</div>
									</div>
								</div>
								
								
							</div>
							<!--<div style="text-align:center">
								<div class="buttonsection" style="display:inline-table">
									<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
								</div>
								<div class="buttonsection" style="display:inline-table">
									<input type="submit" class="btn" data-type="submit" value=" Save " name="submit" id="submit"   />
								</div>
							</div>-->
							
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
		
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	BSMagic({
	  id: "JTab1",
	  addButtons: true,
	  navShape: "square",
	  navBackground: "white",
	  navFontColor: "blue",
	  navUnderline: true,
	  navShadow: true
	});
</script>
<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.nav-link {
  display: block;
  padding: 12px 15px;
  color: #062BF7;
  border: 1px solid #F2F4F8;
  font-weight:600;
}
.nav-pills .nav-link {
  border-radius: .25rem;
}
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
  color: #0343BB;
  background-color: #fff;
}
.BSMagic .nav-link.active {
  background-color: transparent !important;
}
</style>
</body>
</html>

