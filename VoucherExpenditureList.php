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
$RowCount = 0;
if(isset($_POST['submit'])){

	$Unit 		= $_POST['cmb_unit'];
	$FromDate 	= dt_format($_POST['txt_vr_fromdate']);
	$ToDate 	= dt_format($_POST['txt_vr_todate']);
	$SelectQuery1 = "select * from voucher_upt where unitid = '$Unit' and vr_dt >= '$FromDate' and vr_dt <= '$ToDate'";
	$SelectSql1   = mysql_query($SelectQuery1);
	if($SelectSql1 == true){
		if(mysql_num_rows($SelectSql1) > 0){
			$RowCount = 1;
		}
	}
	$SelectQuery2 = "select * from dae_units where unitid = '$Unit'";
	$SelectSql2   = mysql_query($SelectQuery2);
	if($SelectSql2 == true){
		if(mysql_num_rows($SelectSql2) > 0){
			$List2 = mysql_fetch_object($SelectSql2);
			$UnitName = $List2->unit_name;
		}
	}
}
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack(){
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script src="handsontable/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="handsontable/handsontable/dist/handsontable.full.min.css">
<link rel='stylesheet' href='Step-Wizard/BSMagic-min.css'/>
<script src='Library/Step-Wizard/BSMagic-min.js'></script>
<script src='Library/Step-Wizard/gsap.min.js'></script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">Voucher Expenditure </div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="VoucherExpenditureList.php">
                            <div class="div12">
								
								<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div12 BSMagic flex-child green" style="margin-right:10px;" id="test">
												<div class="tab-content" id="v-pills-tabContent">
													<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
														<div class="div-tab-head">Voucher Expenditure for the unit of <?php echo $UnitName; ?> </div>
														<div class="div-tab-body">
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<div class="row">
																				
																				
																				
																				<?php if($RowCount == 1){ ?>
																				<table class="table1 labeldisplay">
																					<thead>
																						<tr>
																							<th>SNo</th>
																							<th>File/PO</th>
																							<th>Item</th>
																							<th>PO-val[L]</th>
																							<th>Vr. No.</th>
																							<th>Vr. Date</th>
																							<th>Vr. Amt.</th>
																							<th>PO Rel Dt.</th>
																							<th>O PIN</th>
																							<th>N PIN</th>
																							<th>Code</th>
																							<th>Paid[L]</th>
																							<th>Head of Account</th>
																							<th>New HoA</th>
																							<th>Indentor</th>
																							<th>GrpDivSec</th>
																							<th>Plant/Service</th>
																						</tr>
																					</thead>
																					<tbody>
																					<?php $Slno = 1; while($List1 = mysql_fetch_object($SelectSql1)){ ?>
																						<tr>
																							<td><?php echo $Slno; ?></td>
																							<td><?php echo $List1->wo; ?></td>
																							<td><?php echo $List1->item; ?></td>
																							<td><?php echo $List1->wo_amt; ?></td>
																							<td><?php echo $List1->vr_no; ?></td>
																							<td><?php echo dt_display($List1->vr_dt); ?></td>
																							<td><?php echo $List1->vr_amt; ?></td>
																							<td><?php echo $List1->wo_dt; ?></td>
																							<td><?php echo $List1->o_pin; ?></td>
																							<td><?php echo $List1->n_pin; ?></td>
																							<td><?php echo $List1->code; ?></td>
																							<td><?php echo $List1->paid_amt; ?></td>
																							<td><?php echo $List1->hoa; ?></td>
																							<td><?php echo $List1->new_hoa; ?></td>
																							<td><?php echo $List1->indentor; ?></td>
																							<td><?php echo $List1->grp_div_sec; ?></td>
																							<td><?php echo $List1->plant_serv; ?></td>
																						</tr>
																					<?php $Slno++; } ?>
																					</tbody>
																				</table>
																				<?php } ?>
																			
																				
																			
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row" align="center">
																<input type="button" class="backbutton" data-type="submit" value=" Back " name="back" id="back"  />
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								
							</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
		
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>

$('#cmb_work_no').chosen();
$('#cmb_item_no').chosen();
$('#cmb_pin_no').chosen();
$('#cmb_hoa').chosen();

   //$(function() {
	/*$.fn.validaterbnno = function(event) {	
				if($("#cmb_rbn").val()==0){ 
					var a="Please select the Bill number";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_rbn').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
           	$(this).validaterbnno(event);
			$(this).validateworkorder(event);
			
         });
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
    $("#cmb_rbn").change(function(event){
		$(this).validaterbnno(event);
	});*/
	
	

//});
</script>
<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.tboxsmclass{
	height:23px;
}
</style>
</body>
</html>

