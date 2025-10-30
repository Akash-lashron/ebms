<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';
include "common.php";
checkUser();
$msg = '';
if(isset($_GET['ncsid'])){
	$PageId = $_GET['ncsid'];
	//echo $PageId;exit;	
}
if($PageId == 2){
	$PageName = $PTPart1.$PTIcon.'Negotiated Statement - Generate';
}else{
	$PageName = $PTPart1.$PTIcon.'Negotiated Comparative Statement - Forward to Accounts';
}
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
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
	/* .lboxlabel {
  color: #04498E;
  text-align: left;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
} */
	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
</style>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "Tendering.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form name="form" method="post" action="NegotiatedComparativeStatement.php">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center"  <?php if(isset($PageId)){ if($PageId == 3){ echo 'style="display:none;"'; } } ?>>Negotiated Comparative Statement - Generate</div>
											<div class="card-header inkblue-card" align="center" <?php if(isset($PageId)){ if($PageId == 2){ echo 'style="display:none;"'; } } ?>>Negotiated Comparative Statement - Forward to Accounts</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">
														<div class="row clearrow"></div>
														<div class="row">
															<div class="div3 dataFont">
																Tender No.
															</div>
														<div class="div9">
															<select name="cmb_shortname" id="cmb_shortname" class="tboxclass">
																<option value="">--------------- Select ---------------</option>
																<!-- <option disabled="disabled" default="true">Choose Tagging</option> -->
																<?php if(isset($PageId)){ if($PageId == 2){ echo $objBind->BindCstTrNo(0,"NEGOTOUSERVIEW"); }else{ echo $objBind->BindCstTrNo(0,"NEGOACC"); } } ?>
															</select>
														</div>
														<div class="row clearrow"></div>
														<div class="row">
															<div class="div3 dataFont">
																Name of Work
															</div>
															<div class="div9">
																<textarea name="txt_work_name" id="txt_work_name" readonly="" class="tboxclass"></textarea>
															</div>
														</div>
														<input type="hidden" class="btn btn-info" name="txt_pageid" id="txt_pageid" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
														<div class="smediv">&nbsp;</div>
														<div class="row">
															<div class="div12" align="center" <?php if(isset($PageId)){ if($PageId == 2){ echo 'style="display:none;"'; } } ?>>
																<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																<input type="submit" class="btn btn-info" name="View" id="View" value=" View & Forward" />
																
															</div>
															<div class="div12" align="center" <?php if(isset($PageId)){ if($PageId == 3){ echo 'style="display:none;"'; } } ?>>
																<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																<input type="submit" class="btn btn-info" name="View" id="View" value=" Generate " />
																	
															</div>												
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div2">&nbsp;</div>
							</div>
						</div>
					</blockquote>
				</div>
			</div>
		</div>
         <!--==============================footer=================================-->
    <?php include "footer/footer.html"; ?>
	<script>
		$("#cmb_shortname").chosen();
		$(document).ready(function(){ 
			$("body").on("change","#cmb_shortname", function(event){
				var Id = $(this).val();
				$("#txt_work_name").val('');
				$.ajax({ 
					type: 'POST', 
					url: 'FindEstTsTrName.php', 
					data: { Id: Id, Page: 'TR'}, 
					dataType: 'json',
					success: function (data) {  
						if(data != null){ 
							$("#txt_work_name").val(data.work_name);
						}
					}
				});
			});
		});
		$("body").on("click","#View", function(event){
			var TendNum 	= $("#cmb_shortname").val();
			var WorkName 	= $("#txt_work_name").val();

			if(TendNum == ""){ 
				BootstrapDialog.alert("Please Select Tender Number.");
				event.preventDefault();
				return false;
			}else if(WorkName == ""){ 
				BootstrapDialog.alert("Please Enter Name of Work.");
				event.preventDefault();
				return false;
			}
		});
		
	</script>
    </body>
</html>

