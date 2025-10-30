<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require('php-excel-reader/excel_reader2.php');
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
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

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="NegotiationView.php" method="post" enctype="multipart/form-data" name="form">
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
							<div class="container">
								<div class="row ">
									<div class="row clearrow"></div>
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Bidder's Price Bid View</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div4">
													<label for="fname">Tender No.</label>
												</div>
												<div class="div8">
													<select id="cmb_shortname" name="cmb_shortname" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
														<?php echo $objBind->BindCstTrNo(0,"NEGOENT");?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<!-- <div class="row">
												<div class="div4">
													<label for="fname">Name of Work</label>
												</div>
												<div class="div8">
													<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>	-->
											<div class="row">
												<div class="div4">
													<label for="fname">Bidder's Name</label>
												</div>
												<div class="div8">
													<select id="cmb_bidder" name="cmb_bidder" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
													</select>
												</div>
											</div>
											<input type="hidden" class="btn btn-info" name="txt_pageid" id="txt_pageid" value="<?php if(isset($PageId)){ echo $PageId; } ?>" />
											<div class="smediv">&nbsp;</div>
											<div class="row">
												<div class="div12" align="center">
													<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
													<input type="submit" class="btn btn-info" name="View" id="View" value=" View " />
												</div>
											</div> 
										</div>
										<div class="smediv">&nbsp;</div>
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
			var WorkName = $("#txt_work_name").val();
			var BidderName 	= $("#cmb_bidder").val();
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Tender Number..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Please Enter Name of work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BidderName == ""){
				BootstrapDialog.alert("Please Select Bidder name..!!");
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
					url: 'FindL1BiddersName.php', 
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


