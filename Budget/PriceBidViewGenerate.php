<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Financial Bid View';
checkUser();
$msg = ''; $success = '';
$userid = $_SESSION['userid'];

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

	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
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

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="PriceBidView.php" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
            	<?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
						<div align="right" class="users-icon-part">&nbsp;</div>
                        <blockquote class="bq1" style="overflow:auto">
							<!--<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('AgreementUpload_File_Sample.php');"><u>Agreement Sheet</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>-->
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
									           <div class="card-header inkblue-card" align="center">Financial Bid Uploads - View</div>
										       <div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="row">
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 dataFont">
																		Tender No.
																	</div>
																	<div class="div8">
																		<select id="cmb_shortname" name="cmb_shortname" class="tboxclass">
																			<option value="">--------------- Select --------------- </option>
																			<?php echo $objBind->BindPriceviewTrNo('');?>
																		</select>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 dataFont">
																			Name of Work
																		</div>
																		<div class="div8">
																			<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""></textarea>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 dataFont">
																			Bidder's Name
																		</div>
																		<div class="div8">
																			<select id="cmb_bidder" name="cmb_bidder" class="tboxclass">
																				<option value="">--------------- Select --------------- </option>
																			</select>
																		</div>
											                       	</div>
										                           	<div class="row clearrow"></div>
																    <div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row clearrow"></div>		
																	<div class="div12" align="center">
																		<a data-url="PriceBidUploadGenerate" class="btn btn-info" name="view" id="view">Back</a>
																		<input type="submit" class="btn btn-info" name="View" id="View" value=" View Bid " />
																	</div>												
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
        </form>
    </body>
</html>
<script>
	$("#cmb_shortname").chosen();
	$("#cmb_bidder").chosen();
	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	$(document).ready(function(){ 
		$("body").on("click","#View", function(event){
			var ShortName 	= $("#cmb_shortname").val();
			var WorkOrderNo = $("#txt_workorder").val();
			var BidderName 	= $("#cmb_bidder").val();
			if(ShortName.trim() == ""){
				BootstrapDialog.alert("Please select Tender no");
				event.preventDefault();
				event.returnValue = false;
			}else if(BidderName == ""){
				BootstrapDialog.alert("Please select Bidder name");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderNo.trim() == ""){
				BootstrapDialog.alert("Tender no. should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}
			
		});
		$("body").on("change","#cmb_shortname", function(event){
			var MastId = $(this).val();
			
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
			
			$("#cmb_bidder").chosen('destroy'); 
			$('#cmb_bidder').children('option:not(:first)').remove();
			if(MastId != ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindPriceViewBiddersName.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) { 
						if(data != null){ 
							if(data != 0){
								$.each(data, function(index, element) {
									$("#cmb_bidder").append('<option value="'+element.contid+'">'+element.contname+'</option>');
								});
							}
						}
						$("#cmb_bidder").chosen();
					}
				});
			}
		});
	});
</script>


