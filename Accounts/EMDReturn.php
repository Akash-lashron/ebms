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

if(isset($_POST['btn_save']) == " Return "){
	
	$ReurnEmdDetIDstr  = $_POST["ch_cont"];
	//$ReurnEContID	   = $_POST["txt_emd_condid"];
	$Returndatestr	   =  $_POST["txt_return_date"];


	foreach($Returndatestr as $Key => $Value){
		$ReurnEmdDetID        =$ReurnEmdDetIDstr[$Key];
		//$ReurnEContID        =$ReurnEContIDStr[$Key];
		$Returndate     	= $Returndatestr[$Key];
		$Insertdate 	   = dt_format($Returndate);
		$update_query1	="update  emd_detail a set a.status = 'R', a.date_return = '$Returndate' where a.emdtid  = '$ReurnEmdDetID'  ";
		//echo $Insertdate; exit;
	
		$insert_sql1 = mysqli_query($dbConn,$update_query1);

		if($insert_sql1 == true){
			$msg = "EMD Return Updated Successfully ..!!";
		}else{
			$msg = "EMD Return Not Updated..!!";
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
								<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">EMD Return Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<div class="row clearrow"></div>									
																    <div class="row">
																		<div class="div3 lboxlabel">
																			Tender No.
																			</div>
																		<div class="div7">
																		<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxsmclass">
																		<option value="">--------------- Select --------------- </option>
																		<?php echo $objBind->BindEMDReturnTrNo('');?>
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
														    	<div class="row ">
																	<div class="div3 lboxlabel">
													              	EMD Amount ( &#8377; )
												               </div>
											                	<div class="div3">
													             	<input type='text' readonly="" name='txt_full_emd_amt' id='txt_full_emd_amt' class="tboxsmclass">
																	</div>
																</div>
																<div class="row clearrow hidden Details"></div>
																<div class="card-header inkblue-card hidden Details" align="left">&nbsp;EMD Details</div>
										                 	<div class="div12  hidden Details" id="Cont_Bank"></div>
																<!-- <div class="smediv hidden Details">&nbsp;</div> -->
										            	</div>
											          <div class="row clearrow"></div>
											          <div class="row">
												        <div class="div12" align="center">
															<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
															<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Return " /> 
													<!-- <a data-url="EMDEntryView" class="btn btn-info" name="btn_view" id="btn_view">View</a> -->
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
	$("#cmb_shortname").chosen();
	$("#cmb_engineer").chosen();
	$("#cmb_bidder").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	

	$( ".date" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
	});
	$( ".expdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		defaultDate: new Date,
	});

		$("body").on("change","#cmb_tnder_no", function(event){
			var MastId = $(this).val();
			var Id = $(this).val();
			$(".Details").removeClass("hidden");
			$("#txt_work_name").val('');
		    $("#txt_full_emd_amt").val('');
			$("#cmb_bidder_0").val('');
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
				url: 'ajax/GetReturnEmdDetails.php', 
				data: { MastId: MastId}, 
				dataType: 'json',
				success: function (data) {  
					var Result1 = data['row1']; 
					var Result2 = data['row2']; 
					var RowSpanContArr = data['row3']; 
					var PrevContId ="";
					
				
					var	BankStr  = "<table class='dataTable etable' align='center' width='100%' id='emdtable1'>";
						BankStr += "<tr style'background-color:#EAEAEA'>";
						BankStr += "<td align='center'>Bidder's Name</td>";
						BankStr += "<td align='center'>Instrument Type</td>";
						BankStr += "<td align='center'>Instrument Number</td>";
						BankStr += "<td align='center'> Bank Name</td>";
						BankStr += "<td align='center'>Branch</td>";
						BankStr += "<td align='center'>Date <br>Issued</td>";
						BankStr += "<td align='center'>Expiry <br> Date</td>";
						BankStr += "<td align='center'>Amount<br> ( &#8377; )</td>";
						BankStr += "<td align='center'>Select</td>";
						BankStr += "<td align='center'>Action<br>Date</td>";
						
					if(data != null){ 
							$.each(Result1, function(index, element) {
								var EmdAmout = Math.round(element.emd_lot_amt);
								$("#txt_work_name").val(element.work_name);
								$("#txt_full_emd_amt").val(EmdAmout);
							});
							var x2 = 0;
						 	$.each(Result2, function(index, element) {
								var ContID 	 = element.contid;
								var contname = element.name_contractor;
								var RowSpan  = RowSpanContArr[ContID];//Object.keys(RowSpanContArr).length;
								//alert(RowSpan);
								if(PrevContId != ContID){
									x2 = 0;
								}
								if(x2 == 0){
									BankStr += "<tr>";
									BankStr +="<td align='left' rowspan='"+RowSpan+"'><input type='text' class='tboxsmclass'  readonly name='cmb_bidder_0' id='cmb_bidder_0'  value='"+contname+"'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='cmd_instype_0' id='cmd_instype_0'  value='"+element.inst_type+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='instrunum_0' id='instrunum_0'  value='"+element.inst_no+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_bankname_pg_0' id='txt_bankname_pg_0'  value='"+element.bank_name+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_sno_pg_0' id='txt_sno_pg_0'  value='"+element.branch_addr+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_date_pg_0' id='txt_date_pg_0'  value='"+element.issue_dt+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_expir_date_pg_0' id='txt_expir_date_pg_0'  value='"+element.valid_dt+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_part_amt_0' id='txt_part_amt_0'  value='"+element.emd_amt+"' ></td>";
									BankStr +="<td align='center'><input type='checkbox' class='checkboxclaa' name='ch_cont[]' id='"+element.emdtid+"'  data-id='"+element.emdtid+"' value='"+element.emdtid+"'></td>";
									BankStr +="<td align='left' ><input type='date' placeholder='DD/MM/YYYY' disabled='disabled'  class='tboxsmclass date'   name='txt_return_date[]' id='txt_return_date"+element.emdtid+"' ></td></tr>";
									//BankStr +="<td align='left'  rowspan='"+RowSpan+"'><input type='submit'  name='btn_return' id='btn_return' class='btn btn-info return "+element.contid+"&"+element.emid+"'  value='Return' ><input type='hidden'  name='txt_emd_condid' id='txt_emd_condid'value='"+element.contid+"' ><input type='hidden'  name='txt_emd_detailid' id='txt_emd_detailid'value='"+element.emid+"' ></td></tr>";
									x2++;
								}else{
									BankStr += "<tr>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='cmd_instype_0' id='cmd_instype_0'  value='"+element.inst_type+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='instrunum_0' id='instrunum_0'  value='"+element.inst_no+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_bankname_pg_0' id='txt_bankname_pg_0'  value='"+element.bank_name+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_sno_pg_0' id='txt_sno_pg_0'  value='"+element.branch_addr+"' ></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass'  readonly name='txt_date_pg_0' id='txt_date_pg_0'  value='"+element.issue_dt+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_expir_date_pg_0' id='txt_expir_date_pg_0'  value='"+element.valid_dt+"'></td>";
									BankStr +="<td align='left'><input type='text' class='tboxsmclass' readonly name='txt_part_amt_0' id='txt_part_amt_0'  value='"+element.emd_amt+"' ></td>";
									BankStr +="<td align='center'><input type='checkbox' class='tboxsmclass checkboxclaa' name='ch_cont[]' data-id='"+element.emdtid+"' id='"+element.emdtid+"' value='"+element.emdtid+"'></td>";
									BankStr +="<td align='left' ><input type='date'  placeholder='DD/MM/YYYY' disabled='disabled' class='tboxsmclass date' name='txt_return_date[]' id='txt_return_date"+element.emdtid+"' ></td></tr>";
									x2++;
								}
								PrevContId = ContID; 	

							});
						BankStr += "</table>";
						 $("#Cont_Bank").html(BankStr);
				  	}
				}
			});
			
		});

		var KillEvent = 0;	
		$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			if(KillEvent == 0){

			var ShortName 	= $("#cmb_tnder_no").val(); 
			var WorkName 	= $("#txt_work_name").val();
			var DateErr = 0; var IsChecked = 0;
			$('input[name="ch_cont[]"]:checked').each(function(){
				var Id = $(this).val();
				var DateVal = $("#txt_return_date"+Id).val();
				if(DateVal == ""){
					DateErr++;
				}
				IsChecked++;
			});

			var BidderName 	= $("#cmb_bidder").val();
			var Returndt 	= $("#txt_return_date").val(); 
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Tender Number..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Please Enter Name of work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(IsChecked == 0){
				BootstrapDialog.alert("Please select atleast one row to release");
				event.preventDefault();
				event.returnValue = false;
			}else if(DateErr > 0){
				BootstrapDialog.alert("Date should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to Return this EMD  ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						if(result){
							KillEvent = 1;
							$("#btn_save").trigger( "click" );
						}else {
							KillEvent = 0;
						}
					}
				});
			}
		}
	  });
	});
	
	$(document).on('click','.checkboxclaa',function(){
     var prodId = $(this).attr('data-id'); //
    if($(this).is(':checked')) {
      $("#txt_return_date"+prodId).prop('disabled', false);
    } else {
       $("#txt_return_date"+prodId).prop('disabled', true);
    }
  });

</script>


