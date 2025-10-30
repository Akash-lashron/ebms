<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<!--<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="js/image_enlarge_style_js.js"></script>-->
<script src="dashboard/MyView/bootstrap.min.js"></script> 
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="BudgetExpenditureReport.php" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<div class="title">Budget Expenditure Report </div>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
							<div align="center" class="col-md-6 no-padding">
								<div class="panel panel-primary">
								  <div class="panel-heading" align="left">Budget Expenditure Report Generate</div>
								  <div class="panel-body">
									<div class="col-md-12 no-padding" style="height:8px"></div>
								  	<div class="col-md-5 no-padding" align="left"> Financial  Year</div>
									<div class="col-md-6 no-padding">
									   <select name="cmb_finyear" id="cmb_finyear" class="form-control">
									   <option value=""> ------- Select Financial Year ------- </option>
										<?php //echo $objBind->BindYearBudget(0,$dbConn2); ?>
										<?php 
										$CurrYear = date("Y"); 
										$FirstYear = $CurrYear - 2; $PrevYear = $CurrYear - 1; $NextYear = $CurrYear + 1; $LastYear = $CurrYear + 2;
										$CurrMonth = date("n");
										if($CurrMonth < 4){
											$PrevFinanceYr = $FirstYear."-".$PrevYear;
											$CurrFinanceYr = $PrevYear."-".$CurrYear;
											$NextFinanceYr = $CurrYear."-".$NextYear;
										}else{
											$PrevFinanceYr = $PrevYear."-".$CurrYear;
											$CurrFinanceYr = $CurrYear."-".$NextYear;
											$NextFinanceYr = $NextYear."-".$LastYear;
										}
										//echo '<option value="'. $PrevFinanceYr.'"'.$sel.'>PREV FY - '.$PrevFinanceYr.'</option>';
										echo '<option value="'. $CurrFinanceYr.'"'.$sel.'>CURR FY - '.$CurrFinanceYr.'</option>';
										//echo '<option value="'. $NextFinanceYr.'"'.$sel.'>NEXT FY - '.$NextFinanceYr.'</option>';
										?>
										</select> 
										</div>
									<div class="col-md-12 no-padding">&nbsp;</div>
								  </div>
								</div>
								<div class="col-md-12 no-padding">
									<input type="submit" name="view" value=" View " id="view" class="backbutton"/>
								</div>
							</div>
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
			<script>
				$("#cmb_finyear").chosen();
				$(function () {
					 $( ".datepicker" ).datepicker({
						changeMonth: true,
						changeYear: true,
						dateFormat: "dd/mm/yy",
						maxDate: new Date,
						defaultDate: new Date,
					 });
				});
		 </script>
        </form>
    </body>
</html>
