<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require('php-excel-reader/excel_reader2.php');
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'PG Release';
$msg = ''; $success = '';
$userid = $_SESSION['userid'];
$staffid  = $_SESSION['sid'];
$InQueryCon =0;

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


	if(isset($_POST['btn_return']) == " Release "){
		//echo 1;exit;
		$ReurnEmdIDStr	       = $_POST["txt_emd_detailid"];
		$ReurnEContIDStr	   = $_POST["txt_emd_condid"];
		$Returndatestr	 	   = $_POST["txt_return_date"];
	
		foreach($Returndatestr as $Key => $Value){
			$ReurnEmdID         =$ReurnEmdIDStr[$Key];
			$ReurnEContID        =$ReurnEContIDStr[$Key];
			$Returndate     	= $Returndatestr[$Key];
			//$Return             =dt_format($Returndate);
			$update_query1	="update  bg_fdr_details a set a.inst_status = 'R', a.released_date = '$Returndate' where a.master_id  = '$ReurnEmdID' and a.contid  = '$ReurnEContID' ";
			$insert_sql1 = mysqli_query($dbConn,$update_query1);
	
			if($insert_sql1 == true){
				$msg = "PG Return Updated Successfully ..!!";
			}else{
				$msg = "PG Return Not Updated..!!";
			}
		}
	}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<style>
	.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}

	.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
</style>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
		url = "Home.php";
		window.location.replace(url);
	}

	window.history.forward();
	function noBack() { window.history.forward(); }
</script>

<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<!--==============================header=================================-->
	<form action="" method="post" enctype="multipart/form-data" name="form">
		<?php include "Menu.php"; ?>
		<!--==============================Content=================================-->
		<div class="content">
			<?php include "MainMenu.php"; ?>
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1" style="overflow:auto">
						<!--<div align="right">
							<font style="font-size:12px; font-weight:bold; color:#0066FF">
								Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="" onClick="OpenInNewTabWinBrowser('AgreementUpload_File_Sample.php');"><u>Agreement Sheet</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
							</font>
						</div>-->
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<div class="div1">&nbsp;</div>
								<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">Performance Guarantee (BG/FDR) Release Entry</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="row" id="table-stmt">
															<div class="row clearrow"></div>									
															<div class="row">
																<div class="div3 lboxlabel">
																	Tender No.
																</div>
																<div class="div7">
																	<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxsmclass">
																		<option value="">--------------- Select --------------- </option>
																		<?php echo $objBind->BindPGReturnTrNo('');?>
																	</select>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="div3 lboxlabel">
																	Name of Work
																</div>
																<div class="div7">
																	<textarea name='txt_work_name' id='txt_work_name' class="tboxsmclass" readonly=""></textarea>
																</div>
															</div>
															<div class="row clearrow"></div>	
															<div class="row">
																<div class="div3 lboxlabel">
																	Contractor Name
																</div>
																<div class="div7">
																<input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass" value=""></td>
																<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxsmclass" value=""></td>
																</div>
															</div>
															<div class="row clearrow"></div>
															<!-- <div class="div3 lboxlabel">	LOI No.</div>
															<div class="div3" align="left">
																<input type="text" name="txt_loi_no" id="txt_loi_no" readonly class="tboxsmclass">
															</div>
															<div class="div2 lboxlabel">&emsp;&emsp;&emsp;LOI Date</div>
															<div class="div3" align="left">
																<input type="text" name="txt_loi_date" id="txt_loi_date"  readonly class="tboxsmclass">
															</div>
															<div class="row clearrow"></div> -->
															<div class="row">
																<div class="div3 lboxlabel" >PG ( % )</div>
																<div class="div3" align="left">
																	<input type="text" name="txt_pg_per" id="txt_pg_per" readonly class="tboxsmclass">
																</div>
																<div class="div2 lboxlabel">&emsp;&emsp;&emsp;PG Value ( &#8377; )</div>
																<div class="div2" align="left">
																	<input type="text" name="txt_pg_value" id="txt_pg_value" readonly class="tboxsmclass">
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row">
																<div class="card-header inkblue-card hidden Details" align="left">&nbsp;FDR/Bank Guarantee Details</div>
																<div class="div12 hidden Details" id="Cont_Bank"></div>
																<!-- <div class="smediv hidden Details">&nbsp;</div> -->
																<div class="row clearrow hidden Details"></div>
																<div class="row">
																	<div class="div12" align="center">
																		<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																	</div>												
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
												
										</div>
									</div>
								</div>
								<div class="div1">&nbsp;</div>
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
	//$("#cmb_shortname").chosen();
	//$("#cmb_engineer").chosen();
	//$("#cmb_bidder").chosen();
	$('#cmb_tnder_no').chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	$(document).ready(function(){ 
		$("body").on("change","#cmb_tnder_no", function(event){
			var MastId = $(this).val();
			var Id = $(this).val();
			$(".Details").removeClass("hidden");
			$("#txt_work_name").val('');
		    $("#txt_full_emd_amt").val('');
			$("#cmb_bidder_0").val('');
			$("#cmd_pur_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_sno_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt").val('');
			$("#txt_work_name").val('');
			$("#Cont_Bank").html(''); 
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/GetPGReturnDetails.php', 
				data: { MastId: MastId}, 
				dataType: 'json',
				success: function (data) {  
					var Result1 = data['row1']; 
					var Result2 = data['row2']; 
					var RowSpanContArr = data['row3']; 
					var x2=0;
				//alert(JSON.stringify(RowSpanContArr));
					var	BankStr  = "<table class='dataTable etable' align='center' width='100%' id='emdtable1'>";
						BankStr += "<tr style'background-color:#EAEAEA'>";
						// BankStr += "<td align='center'>Instrument Purpose</td>";
						BankStr += "<td align='center'>Instrument Type</td>";
						BankStr += "<td align='center'>Instrument Number</td>";
						BankStr += "<td align='center'> Bank Name</td>";
						BankStr += "<td align='center'>Date <br>Issued</td>";
						BankStr += "<td align='center'>Expiry <br> Date</td>";
						BankStr += "<td align='center'>Extension <br> Date</td>";
						BankStr += "<td align='center'>Amount<br> ( &#8377; )</td>";
						BankStr += "<td align='center'>Release<br>Date</td>";
						BankStr += "<td align='center'>Action</td>";
					if(data != null){ 
							$.each(Result1, function(index, element) {
								var PGAmout = Math.round(element.pg_amt);
								$("#txt_work_name").val(element.work_name);
								$("#txt_contid").val(element.contid);
								$("#txt_bidder").val(element.name_contractor);
								$("#txt_loi_no").val(element.loa_no);
								$("#txt_loi_date").val(element.loa_dt);
								$("#txt_pg_per").val(element.pg_per);
								$("#txt_pg_value").val(PGAmout);
						});
						 $.each(Result2, function(index, element) {
							    var InstPurID 	 = element.inst_purpose;
										var RowSpan  = RowSpanContArr.PG;
										var InstDateDisp = element.inst_date;
										if(element.inst_date == "00/00/0000"){
											InstDateDisp = "";
										}
										var ExpirDateDisp = element.inst_exp_date;
										if(element.inst_exp_date == "00/00/0000"){
											ExpirDateDisp = "";
										}
										var ExtDateDisp = element.inst_ext_date;
										if(element.inst_ext_date == "00/00/0000"){
											ExtDateDisp = "";
										}
								//alert(RowSpan);
								if(x2 == 0){
								BankStr += "<tr>";
								// BankStr +="<td align='left' rowspan='"+RowSpan+"' ><input type='text'  class='tboxsmclass' readonly name='cmd_pur_0' id='cmd_pur_0'  value='"+InstPurID+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='cmd_instype_0' id='cmd_instype_0'  value='"+element.inst_type+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='instrunum_0' id='instrunum_0'  value='"+element.inst_serial_no+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_bankname_pg_0' id='txt_bankname_pg_0'  value='"+element.inst_bank_name+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_date_pg_0' id='txt_date_pg_0'  value='"+InstDateDisp+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonlyname='txt_expir_date_pg_0'  width='200%' id='txt_expir_date_pg_0'  value='"+ExpirDateDisp+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonlyname='txt_expir_date_pg_0'  width='200%' id='txt_expir_date_pg_0'  value='"+ExtDateDisp+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_exten_date_pg_0' id='txt_exten_date_pg_0'  value='"+element.inst_amt+"' ></td>";
								BankStr +="<td align='left' rowspan='"+RowSpan+"' ><input type='date'class='tboxsmclass date datepicker' required name='txt_return_date[]' id='txt_return_date[]' ></td>";
								BankStr +="<td align='left' rowspan='"+RowSpan+"' ><input type='submit'  name='btn_return' id='btn_return' class='btn btn-info'  value='Release' ><input type='hidden'  name='txt_emd_condid[]' id='txt_emd_condid'value='"+element.contid+"' ><input type='hidden'  name='txt_emd_detailid[]' id='txt_emd_detailid'value='"+element.master_id+"' ></td></tr>";
								x2++;
							   }else{
								BankStr += "<tr>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='cmd_instype_0' id='cmd_instype_0'  value='"+element.inst_type+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='instrunum_0' id='instrunum_0'  value='"+element.inst_serial_no+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_bankname_pg_0' id='txt_bankname_pg_0'  value='"+element.inst_bank_name+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_date_pg_0' id='txt_date_pg_0'  value='"+InstDateDisp+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonlyname='txt_expir_date_pg_0' id='txt_expir_date_pg_0'  value='"+ExpirDateDisp+"'></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_exten_date_pg_0' id='txt_exten_date_pg_0'  value='"+ExtDateDisp+"' ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_part_amt_0' id='txt_part_amt_0'  value='"+element.inst_amt+"' ></td></tr>";
								}x2++;
							});
						BankStr += "</table>";
						 $("#Cont_Bank").html(BankStr);
				  }
				}
			});
			
		});
	});


	var KillEvent = 0;	
		$(document).ready(function(){ 
		$("body").on("click","#btn_return", function(event){
			if(KillEvent == 0){
		    var Returndt 	= $('.date').val(); 
			if(Returndt == ""){
				BootstrapDialog.alert("Please select Release date..!!");
				event.preventDefault();
				event.returnValue = false;
		
			}else{
				   event.preventDefault();
				    BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to Release this PG  ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btn_return").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
			   }
		    }
	     });
	});



	$( ".date" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+15",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$( ".expdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+25",
		defaultDate: new Date,
	});


</script>


