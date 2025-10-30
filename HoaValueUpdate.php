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
$SelectQuery1 = "select a.*, b.*, c.* from hoa_budget_value a inner join hoa b on (a.hoa_id = b.hoa_id) inner join pin c on (a.pin_id = c.pin_id) where a.active = 1 and b.active = 1 and c.active = 1";
$SelectSql1   = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1) > 0){
		$RowCount = 1;
	}
}
//echo $SelectQuery1;exit;	
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
			<div class="title">Head of Account Value Update</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="PartPaymentPageView.php">
							<div class="div12">&nbsp;</div>
                            <div class="div12">
								<div class="div2">&nbsp;</div>
								<div class="div8 card-div-body">
									<div class="top-card">
										<!--<div class="top-card-header">sdsdf</div>-->
										<div class="top-card-container">
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div3">
												<label for="name" class="card-label">PIN No. </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="712">
												</div>
												<div class="div3" align="center">
												<label for="name" class="card-label">Year </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="2022">
												</div>
											</div>
											
											<?php $Slno = 1; while($List1 = mysql_fetch_object($SelectSql1)){ ?>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div3">
												<label for="name" class="card-label">Head of Account </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="<?php echo $List1->hoa_no; ?>">
												</div>
												<div class="div3" align="center">
												<label for="name" class="card-label">Budget Value (in Cr) </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="<?php echo $List1->budget_value; ?>">
												</div>
											</div>
											<?php } ?>
											<!--<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div3">
												<label for="name" class="card-label">Head of Account </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="4861 60 203 44 00 61">
												</div>
												<div class="div3" align="center">
												<label for="name" class="card-label">Budget Value (in Cr) </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg">
												</div>
											</div>
											<div class="div12">&nbsp;</div>
											<div class="div12">
												<div class="div3">
												<label for="name" class="card-label">Head of Account </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg" value="4861 60 203 44 00 62">
												</div>
												<div class="div3" align="center">
												<label for="name" class="card-label">Budget Value (in Cr) </label>
												</div>
												<div class="div3">
													<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox-lg">
												</div>
											</div>-->
											
											
											<div class="div12">&nbsp;</div>
										</div>
									</div>
								</div>
								<div class="div2"></div>
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

