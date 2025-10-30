<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
checkUser();
$GlobGr1Id = 2; $GlobGr2Id = 3;
include "DefaultMaster.php";
$msg = ""; $del = 0; $RowCount =0; $InQueryCon =0;
$staffid = $_SESSION['sid'];

//echo $List3;exit;

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

if(isset($_POST['btn_save']) == " Save "){

	$CmbTenderNo = $_POST["cmb_tnder_no"];
	$WorkName	 = $_POST["txt_work_name"];
	$BidderId	 = $_POST["cmb_bidder"];
	$PgPer	    = $_POST["txt_pg_per"];
	$Pgamt	    = $_POST["txt_pg_value"];
	$LoiNo		 = $_POST["txt_loi_no"];
	$LoiDate	 	 = $_POST["txt_loi_date"];
	// $PgType		 = $_POST["cmb_pg_type"];
	// $BankName	 = $_POST["txt_bank_name"];
	// $SerialNo	 = $_POST["txt_serial_no"];
	// $PgAmount	 = trim($_POST["txt_pg_amt"]);
	// $BgDate		 = $_POST["txt_bg_date"];
	// $ExpiryDate	 = $_POST["txt_exp_date"];

	if($CmbTenderNo == NULL){
		$msg = "Please Select Tender Number..!!";
	}else if($WorkName == NULL){
		$msg = "Please Enter Work Name..!!";
	}else if($BidderId == NULL){
		$msg = "Please Select Contractor Name..!!";
	}else if($LoiNo == NULL){
		$msg = "Please Enter LOI Number..!!";
	}else if($LoiDate == NULL){
		$msg = "Please Select LOI Date..!!";
	}else{
		$InQueryCon = 1;
	}
	//echo $LoiDate;exit;
	$TSID = '';
	$SelectTSQuery = "SELECT ts_id FROM tender_register WHERE tr_id = '$CmbTenderNo'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$TSID = $CList->ts_id;
		}
	}
	$GlobID= '';
	$SelectTSQuery = "SELECT globid FROM tender_register WHERE tr_id = '$CmbTenderNo'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$GlobID = $CList->globid;
		}
	}
	//echo $ExpiryDate;exit;
	if($InQueryCon == 1){

		$insert_query	= "INSERT INTO loi_entry SET ts_id='$TSID', tr_id='$CmbTenderNo', globid='$GlobID', contid='$BidderId',loa_no='$LoiNo',
		pg_per='$PgPer',  pg_amt='$Pgamt', loa_dt='$LoiDate', userid = '$UserId', createddate = NOW()";
		$insert_sql = mysqli_query($dbConn,$insert_query);
		$LastInsertloiid = mysqli_insert_id($dbConn);

		$InsertWorkQuery	= "UPDATE works SET work_status='LOI', contid='$BidderId', loa_no='$LoiNo', loa_dt='$LoiDate'";
		$InsertWorkQuerySql = mysqli_query($dbConn,$InsertWorkQuery);

		$TrRegInsertquery	= "UPDATE tender_register SET loi_issue='Y' WHERE tr_id='$CmbTenderNo'";
		$TrRegInsertquerysql = mysqli_query($dbConn,$TrRegInsertquery);
		if($insert_sql == true){
			$msg = "LOI Entry Saved Successfully..!!";
		}
	}
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }

	function ViewLOIPrint(){
		url = "LOIPrintForm.php";
		window.location.replace(url);
	}
</script>	
<style>
	.tclass {
		border: -1px;
		width :30px;
		text-align: center;
		color: blue;
	}
</style>

	<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
		<!--==============================header=================================-->
		<form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
			<?php include "Menu.php"; ?>
			<!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">LOI Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<div class="row clearrow"></div>


																	<div class="row">
																		<div class="div3 lboxlabel">
																			Tender No.
																		</div>
																		<div class="div9">
																			<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxclass">
																				<option value="">--------------- Select --------------- </option>
																				<?php echo $objBind->BindPriceBidTrNo('');?>
																			</select>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">
																			Name of Work
																		</div>
																		<div class="div9">
																			<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""></textarea>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">
																			Bidder's Name
																		</div>
																		<div class="div4">
																			<select id="cmb_bidder" name="cmb_bidder" class="tboxclass" value="">
																				<option value="">--------------- Select --------------- </option>
																			</select>
																		</div>
																		<div class="div2 lboxlabel">&emsp;&emsp;&emsp;Quoted Amount</div>
																		<div class="div3">
																			<input type="text" name="txt_quote_amt" id="txt_quote_amt" class="tboxsmclass">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">PG Value</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_pg_value" id="txt_pg_value" readonly class="tboxsmclass">
																		</div>
																		<div class="div2 lboxlabel">&emsp;&emsp;&emsp;PG %</div>
																		<div class="div3" align="left">
																				<input type="text" readonly name="txt_pg_per" id="txt_pg_per" class="tboxsmclass "> 
																		</div>
																	</div>
																	
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">LOI No.</div>
																		<div class="div4" align="left">
																			<input type="text" name="txt_loi_no" id="txt_loi_no" class="tboxsmclass">
																		</div>
																		<div class="div2 lboxlabel">&emsp;&emsp;&emsp;LOI Date</div>
																		<div class="div3"  align="left">
																			<input type="date" name="txt_loi_date" id="txt_loi_date" class="tboxsmclass">
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																		<!-- <div class="row">
																			<div class="div3 lboxlabel">PG Type</div>
																			<div class="div3" align="left">
																				<select id="cmb_pg_type" name="cmb_pg_type" class="tboxclass" style="width:205px">
																					<option value="">---- Select ---- </option>
																					<option value="BG">Bank Guarantee</option>
																					<option value="FDR">Fixed Deposit Receipt</option>
																				</select>
																			</div>
																			<div class="div3 lboxlabel">&emsp;Bank Name</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass">
																			</div>
																		</div>

																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">BG/FDR Serial No.</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_serial_no" id="txt_serial_no" class="tboxsmclass">
																			</div>
																			<div class="div3 lboxlabel">&emsp;PG Amount</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_pg_amt" id="txt_pg_amt" class="tboxsmclass">
																			</div>
																		</div>
																		
																		<div class="row clearrow"></div>
																		<div class="row">

																			<div class="div3 lboxlabel">BG/FDR Date</div>
																			<div class="div3" align="left">
																				<input type="date" name="txt_bg_date" id="txt_bg_date" class="tboxsmclass">
																			</div>

																			<div class="div3 lboxlabel">&emsp;Expiry Date</div>
																			<div class="div3" align="left">
																				<input type="date" name="txt_exp_date" id="txt_exp_date" class="tboxsmclass">
																			</div>											
																		</div>

																		<div class="row clearrow"></div>
																		<div class="row">
																			<div class="div3 lboxlabel">Authorized Signature</div>
																			<div class="div3" align="left">
																				<select id="cmb_auth_sign" name="cmb_auth_sign" class="tboxclass" style="width:205px">
																					<option value="">---- Select ---- </option>
																					<option value="1">Chief Engineer,CEG</option>
																				</select>
																			</div> -->
																			<!--<div class="div3 lboxlabel">&emsp;Bank Name</div>
																			<div class="div3" align="left">
																				<input type="text" name="txt_bank_name" id="txt_bank_name" class="tboxsmclass">
																			</div>-->
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div12" align="center">
																			<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" data-type="submit" value=" Save ">
																			<!-- <input type="button" name="btn_pr_view" id="btn_pr_view" class="btn btn-info" onClick="ViewLOIPrint();" value=" Print View "> -->
																		</div>
																	</div>
																	<div class="row clearrow"></div>


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
			<?php   include "footer/footer.html"; ?>
			<script src="js/jquery.hoverdir.js"></script>
		</form>
	</body>
</html>
<script>
	$("#cmb_tnder_no").chosen();
	$("#cmb_bidder").chosen();
	$("#cmb_pg_type").chosen();
	$("#cmb_auth_sign").chosen();

	$(document).ready(function(){ 
		// $("body").on("click","#View", function(event){
		// 	var ShortName 	= $("#cmb_tnder_no").val();
		// 	var WorkOrderNo = $("#txt_work_name").val();
		// 	var BidderName 	= $("#cmb_bidder").val();
		// 	if(ShortName.trim() == ""){
		// 		BootstrapDialog.alert("Please select work short name");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}else if(WorkOrderNo.trim() == ""){
		// 		BootstrapDialog.alert("Tender no. should not be empty");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}else if(BidderName == ""){
		// 		BootstrapDialog.alert("Bidder name should not be empty");
		// 		event.preventDefault();
		// 		event.returnValue = false;
		// 	}
		// });
		$("body").on("change","#cmb_tnder_no", function(event){
			var MastId = $(this).val();
			var Id = $(this).val();
			var TrId = $(this).val();
			
			$("#cmb_bidder").val('');
			$("#txt_pg_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_work_name").val('');
			$("#txt_pg_per").val('');
			$("#txt_quote_amt").val('');
			$("#txt_pg_value").val('');
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
					url: 'FindBiddersName.php', 
					data: { MastId: MastId }, 
					dataType: 'json',
					success: function (data) { 
						var ContArr      = data['ContArr']; 
						var ContL1       = data['ContL1']; 
						if(data != null){ 
							$('#cmb_bidder').chosen('destroy');
							$.each(ContArr, function(index, value) {
							if(ContL1 != value.contid){
								$("#cmb_bidder").append('<option disabled="disabled" value="'+value.contid+'">'+value.contname+'</option>');
							}else{
								$("#cmb_bidder").append('<option value="'+value.contid+'">'+value.contname+'</option>');
							}
						});
							
							//$("#cmb_bidder").val(ContL1); 
							$("#cmb_bidder").find("option[value="+ContL1+"]").prop("selected", "selected");
							$("#cmb_bidder").chosen();
							//$("#txt_quote_amt").val(QuotedAmt); 
							var BidderId = $("#cmb_bidder").val(); //alert(BidderId);

							$.ajax({ 
								type: 'POST', 
								url: 'FindEmdLoiValue.php', 
								data: { BidderId: BidderId, TrId: TrId, Page: 'LOI'}, 
								dataType: 'json',
								success: function (data) {  
									if(data != null){ 
										$("#txt_quote_amt").val(data['bid_amt']);
										$("#txt_pg_value").val(data['pg_amt']);
										$("#txt_pg_per").val(data['pg_per']); 
						      }
					        }
			             });

						 }
					}
				});
			
				
			}

		
			
		});
	});
	 	

</script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>