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
$PageId = 1;
if(isset($_GET['ncsid'])){
	$PageId = $_GET['ncsid'];
	//echo $PageId;exit;	
}
$PageName = $PTPart1.$PTIcon.'CCNO. Generate';

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
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "MyView.php";
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
        <form name="form" method="post" action="CSTccnoEntry.php">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1">
								<div class="row ">
									<div class="row clearrow"></div>
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead head-b" align="center"><?php if(isset($PageId)){ if($PageId == 2){ echo "Negotiated Comparative Statement - CCNO. Generate"; }else{ echo "Comparative Statement - CCNO. Generate"; } } ?></div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div4">
													<label for="fname">Tender No.</label>
												</div>
												<div class="div8">
													 <select name="cmb_shortname" id="cmb_shortname" class="tboxclass">
														<option value="">--------------- Select ---------------</option>
														<!-- <option disabled="disabled" default="true">Choose Tagging</option> -->
														 <?php echo $objBind->BindCstTrNo(0,"CCNOGEN"); ?>
													  </select>
												</div>
											</div>
											
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Name of Work</label>
												</div>
												<div class="div8">
													<textarea name="txt_work_name" id="txt_work_name" readonly="" class="tboxclass"></textarea>
												</div>
											</div>
											<input type="hidden" class="btn btn-info" name="txt_pageid" id="txt_pageid" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
											<div class="smediv">&nbsp;</div>
											<div class="row">
												<div class="div12" align="center">
													<input type="submit" class="btn btn-info" name="View" id="View" value=" Generate " />
												</div>
											</div>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>							
                    </blockquote>
                </div>

            </div>
        </div>
      </form>
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
		$("#View").click(function(event){ 
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

