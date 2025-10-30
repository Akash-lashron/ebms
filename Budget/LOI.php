<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'EMD Entry';
//checkUser(); 
$msg = ""; $del = 0;
$RowCount = 0; 
$UserId  = $_SESSION['userid'];
$staffid  = $_SESSION['sid'];

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

	$TenderNum 		= $_POST["cmb_tnder_no"];
	$WorkName	 	= $_POST["txt_work_name"];
	//$EnginnerName	= $_POST["cmb_engineer"];
	$ContractorName	= $_POST["cmb_bidder"];
	$Emdinstypestr	= $_POST["txt_loi_no"];
	$Emdinstnumstr	= $_POST["txt_loi_date"];
	
	//$TotEmdAmount	= trim($_POST["txt_full_emd_amt"]);


	if($TenderNum == null){
		$message = 'Error : Tender Number should not be empty..!!!';
	}else if($WorkName == null){
		$message = 'Error : Work Name should not be empty..!!!';
	}else if($TotEmdAmount == null){
		$message = 'Error : Please Enter EMD Amount..!!!';
	}else if($ContractorName == null){
		$message = 'Error : Please Select Contractor Name..!!!';
	}else if($Emdinstnumstr == null ){
		$message = 'Error : Please Add Atleast One Type';
	}else if(count($Emdinstnumstr) <= 0 ){
		$message = 'Error : Please Add Atleast One Type';
	}else{
		$SuccInsert = 1;
	}
		// if($request->input('hid_emdid') != null){ 
		// 	EmdDetail::where('emdid', $request->input('hid_emdid'))->delete();
		// 	Emd::where('emdid', $request->input('hid_emdid'))->delete();
		// } 
		// 	$SheetId = Emd::create([ 
		// 		'emd_amt'         => $request->input('txt_emdamount'),
		// 		'tr_id'           => $request->input('cmb_tr_no'),
		// 		'ts_id'           => $request->input('hid_tsid'),
		// 		'active'          => 1,
		// 		'sheet_id'        => $request->input('cmb_work_sname'),
		// 		'createdon'       => Now(),
		// 		'userid'          => session('WcmsUserId')
		// 	]);
		// 	$Division = $SheetId->bmid;
		// if(count($request->input('cmd_contid')) <= 0 ){
		// $message = 'Error : Please Add Atleast One Meter';
		// }
		if($SuccInsert == 1){
			$insert_query	= "insert into emd_master set tr_id='$TenderNum', contid='$ContractorName',emd_lot_amt='$TotEmdAmount',
			active='1', created_by = '$UserId', created_on = NOW()";
			$insert_sql = mysqli_query($dbConn,$insert_query);

			$LastInsertid = mysqli_insert_id($dbConn);
			foreach($Emdinstnumstr as $Key => $Value){
				$Emdinstype    	= $Emdinstypestr[$Key];
				$Emdinstnum    	= $Emdinstnumstr[$Key];
				$Emdbname      	= $Emdbnamestr[$Key];
				$Emdbadd       	= $Emdbaddstr[$Key];
				$Emddate       	= $Emddatestr[$Key];
				$Emdexdate     	= $Emdexdatestr[$Key];
				$AmountList     = $AmountListstr[$Key];

				$TrimAmount 	= trim($AmountList);
				$Insertdate 	= dt_format($Emddate);
				$InsertExpdate 	= dt_format($Emdexdate);
				//echo trim($AmountList);exit;

				

				$insert_query1	= "insert into emd_detail set emid='$LastInsertid', inst_type='$Emdinstype',inst_no='$Emdinstnum', bank_name='$Emdbname',
				branch_addr='$Emdbadd', issue_dt='$Insertdate', valid_dt='$InsertExpdate', emd_amt='$TrimAmount', active='1'";
				$insert_sql1 = mysqli_query($dbConn,$insert_query1);

				if($insert_sql == true){
					$msg = "EMD Entry Successfully Saved..!!";
				}
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

	function ViewTSList(){
		url = "NITView.php";
		window.location.replace(url);
	}
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="" method="post" enctype="multipart/form-data" name="form" id="form1">
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
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">LOI- Entry</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div2">
													&nbsp;
												</div>
												<div class="div4 lboxlabel">
													Tender Number
												</div>
												<div class="div4">
													<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
														<?php echo $objBind->BindPriceBidTrNo('');?>
													</select>
												</div>
												<div class="div2">
													&nbsp;
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">
													&nbsp;
												</div>
												<div class="div4 lboxlabel">
													Name of Work
												</div>
												<div class="div4">
													<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""></textarea>
												</div>
												<div class="div2">
													&nbsp;
												</div>
											</div>
											<!--<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Engineer Name</label>
												</div>
												<div class="div6">
													<select id="cmb_engineer" name="cmb_engineer" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
														<option value="1">Engineer 1</option>
														<option value="2">Engineer 2</option>
														<option value="3">Engineer 3</option>
													</select>
												</div>
											</div>-->
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">
													&nbsp;
												</div>
												<div class="div4 lboxlabel">
													Bidder's Name
												</div>
												<div class="div4">
													<select id="cmb_bidder" name="cmb_bidder" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
													</select>
												</div>
												<div class="div2">
													&nbsp;
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">
													&nbsp;
												</div>
												<div class="div4 lboxlabel">
													LOI Number
												</div>
												<div class="div4">
													<input type='text' name='txt_loi_no' id='txt_loi_no' class="tboxclass">
												</div>
												<div class="div2">
													&nbsp;
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div2">
													&nbsp;
												</div>
												<div class="div4 lboxlabel">
													LOI Date
												</div>
												<div class="div4">
													<input type='text' readonly="" Placeholder="DD/MM/YYYY" name='txt_loi_date' id='txt_loi_date' class="tboxclass date">
												</div>
												<div class="div2">
													&nbsp;
												</div>
											</div>
											<div class="smediv">&nbsp;</div>
											<div class="row">
												<div class="div12" align="center">
													<table class="itemtable etable" align="center" width="100%" id="table1">
														<tr class="label" style="background-color:#EAEAEA">
															<td align="center">PG Type</td>
															<td align="center">Bank Name</td>
															<td align="center">BG/FDR Serial No.</td>
															<td align="center">PG Amount</td>
															<td align="center">BG/FDR Date</td>
															<td align="center">Expiry Date</td>
															<td align="center">Action</td>
														</tr>
														<tr>
															<td align="center">
																<select name="cmd_pg_type_0" id ="cmd_pg_type_0" class="textbox-new">  
																	<option value="">---- Select ---- </option>
																	<option value="DD">DD</option>
																	<option value="PG">BG</option>
																	<option value="FDR">FDR</option>
																</select>
															</td>
															<td align="center"><input type="text" class="textbox-new" style="width:100px;" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
															<td align="center"><input type="text" class="textbox-new" style="width:120px;" name="txt_pg_sno_0" id="txt_pg_sno_0"></td>
															<td align="center"><input type="text" class="textbox-new" style="width:110px;" name="pg_amt_0" id ="pg_amt_0"></td>
															<td align="center"><input type="text" class="tboxsmclass date"style="width:100px;" name="txt_date_pg_0" id="txt_date_pg_0" placeholder="DD/MM/YYYY" readonly=""></td>
															<td align="center"><input type="text" class="tboxsmclass expdate" style="width:100px;" name="txt_expir_date_pg_0" id="txt_expir_date_pg_0" placeholder="DD/MM/YYYY" readonly=""></td>
															<td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="fa btn btn-info"></td>
															<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
														</tr>
														<!-- For Update Function -->
														<!--<tr>
															<td align="center">
																<select name="cmd_instype[]" id ="cmd_instype" class="textbox-new">  
																	<option value="">---- Select ---- </option>
																	<option value="DD">DD</option>
																	<option value="PG">PG</option>
																	<option value="FDR">FDR</option>
																</select>
															</td>
															<td align="center"> 
																<input type="text" name="instrunum[]" id ="instrunum" value="" class="textbox-new">
															</td>
															<td align="center"><input type="text" class="textbox-new" name="txt_bankname_pg[]" id="txt_bankname_pg" value=""></td>
															<td align="center"><input type="text" class="textbox-new" name="txt_sno_pg[]" id="txt_sno_pg" value=""></td>
															<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass date" name="txt_date_pg[]" id="txt_date_pg" value=""></td>
															<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate" name="txt_expir_date_pg[]" id="txt_expir_date_pg" value=""></td>
															<td><input type="button"  class="backbutton delete" name="b_delete" id="b_delete" value="DELETE" />
														</tr>-->
														<!-- End for Update Function -->
													</table>      
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div12" align="center">
													<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
													<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
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
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
	$("#cmb_tnder_no").chosen();
	$("#cmb_bidder").chosen();
	$("#btn_view").click(function(event){ 
		var WorkName = $("#cmb_work_name").val(); 
		if(WorkName == ""){ 
			BootstrapDialog.alert("Please Select Name of Work.");
			event.preventDefault();
			event.returnValue = false;
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

	$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			var ShortName 	= $("#cmb_tnder_no").val();
			var WorkName 	= $("#txt_work_name").val();
			var BidderName 	= $("#cmb_bidder").val();
			var LoiNum 		= $("#txt_loi_no").val();
			var LoiDate 	= $("#txt_loi_date").val();
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
			}else if(LoiNum == ""){
				BootstrapDialog.alert("Please Enter LOI Number..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(LoiDate == ""){
				BootstrapDialog.alert("Please Select LOI Date..!!");
				event.preventDefault();
				event.returnValue = false;
			}
		});
		$("body").on("change","#cmb_tnder_no", function(event){
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
					url: 'FindBiddersName.php', 
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

	$("body").on("click", "#emp_add", function(event){ 
		var PgType 	 	 = $("#cmd_pg_type_0").val();
		var BankName   	 = $("#txt_bankname_pg_0").val();
		var PgSerNum  	 = $("#txt_pg_sno_0").val();
		var PgAmt 	 	 = $("#pg_amt_0").val();
		var PgDate  	 = $("#txt_date_pg_0").val();
		var DateofExpiry = $("#txt_expir_date_pg_0").val();
		var RowStr = '<tr><td align="center"><input type="text" name="cmd_pg_type[]" class="textbox-new" style="width:110px;" value="'+PgType+'"></td><td align="center"><input type="text" name="txt_bankname_pg[]" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td align="center"><input type="text" name="txt_pg_sno[]" class="textbox-new" style="width:120px;" value="'+PgSerNum+'"></td><td align="center"><input type="text" name="pg_amt[]" class="textbox-new" style="width:110px;" value="'+PgAmt+'"></td><td align="center"><input type="text" readonly="" name="txt_date_pg[]" class="textbox-new date" style="width:100px;" value="'+PgDate+'"></td><td align="center"><input type="text" readonly="" name="txt_expir_date_pg[]" class="textbox-new expdate" style="width:100px;" value="'+DateofExpiry+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		if(PgType == 0){
			BootstrapDialog.alert("Please Select PG Type");
			return false;
		}else if(BankName == 0){
			BootstrapDialog.alert("Bank Name should not be empty");
			return false;
		}else if(PgSerNum == 0){
			BootstrapDialog.alert("PG Serial Number should not be empty");
			return false;
		}else if(PgAmt == 0){
			BootstrapDialog.alert("PG Amount should not be empty");
			return false;
		}else if(PgDate == 0){
			BootstrapDialog.alert("PG Date should not be empty");
			return false;
		}else if(DateofExpiry == 0){
			BootstrapDialog.alert("Date of Expiry should not be empty");
			return false;
		}else{
			$("#table1").append(RowStr);
			$("#cmd_pg_type_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_pg_sno_0").val('');
			$("#pg_amt_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
		}
	});
	$("body").on("click", ".delete", function(){
		$(this).closest("tr").remove();
	});
	$('#cmb_tr_no').chosen();

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

</script>