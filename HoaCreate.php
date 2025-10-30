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
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">Head of Account Create</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="PartPaymentPageView.php">
							<div class="div12">&nbsp;</div>
                            <div class="div12">
								<div class="div4">&nbsp;</div>
								<div class="div4 card-div-body">
									<div class="top-card">
										<!--<div class="top-card-header">sdsdf</div>-->
										<div class="top-card-container">
											<div class="div12">
												<div class="div6">
												<label for="name" class="card-label">PIN No. </label>
												</div>
												<div class="div6">
													<select name="cmb_unit" id="cmb_unit" class="card-label-selectbox-lg">
														<option value="712">712</option>
													</select>
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div6">
												<label for="name" class="card-label">Head of Account No. </label>
												</div>
												<div class="div6">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div4"></div>
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
</style>
</body>
</html>

