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
                        <form name="form" method="post" action="PartPaymentPageView.php">
                            <div class="div12">
								
								<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div2"></div>
											<div class="div8 BSMagic flex-child green" style="margin-right:10px;" id="test">
												<div class="tab-content" id="v-pills-tabContent">
													<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
														<div class="div-tab-head">Technical Sanction</div>
														<div class="div-tab-body">
															<!--<div class="erow"></div>-->
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			
																				<!--<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Project Title</div>
																				<div class="div9">
																					<select name="cmb_work_name" id="cmb_work_name" class="tboxsmclass" style="width:100%;">
																						<option value=""> ------------------- Select ----------------</option>
																					</select>
																				</div>-->
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Name of Work</div>
																				<div class="div9"><textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass" style="width:100%" ></textarea></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS No.</div>
																				<div class="div6" align="left"><input type="text" name="txt_ts_no" id="txt_ts_no" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Amount (&#x20B9;)</div>
																				<div class="div6" align="left"><input type="text" name="txt_ts_amount" id="txt_ts_amount" class="tboxsmclass"></div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">TS Date</div>
																				<div class="div6" align="left"><input type="date" name="txt_ts_date" id="txt_ts_date" class="tboxsmclass"></div>
																				
																				
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Approving Authority</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Discipline</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																				<div class="div3 lboxlabel">Plant</div>
																				<div class="div6" align="left">
																					<select name="cmb_approve_auth" id="cmb_approve_auth" class="tboxsmclass">
																						<option value=""> ------------------- Select ----------------</option>
																						
																					</select>
																				</div>
																				<div class="row clearrow"></div>
																			</div>
																			<div class="row clearrow"></div>
																			<div class="row" align="center">
																				<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
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

