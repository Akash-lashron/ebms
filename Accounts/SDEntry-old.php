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

if(isset($_POST['btn_save']) == " Save "){
	$Workname	    = $_POST["cmb_shortname"];
	$Contractorid	= $_POST["txt_contid"];
	$EmdPurstr   	= $_POST["cmd_purposes"];
	$Emdinstypestr	= $_POST["cmd_instype"];
	$Emdinstnumstr	= $_POST["instrunum"];
	$Emdbnamestr	= $_POST["txt_bankname_pg"];
	$Emddatestr		= $_POST["txt_date_pg"];
	$Emdexdatestr	= $_POST["txt_expir_date_pg"];
	$AmountListstr	= $_POST["txt_part_amt"];

	if($Workname == null){
		$message = 'Error : Please Select Work Short Name..!!!';
	}else if(count($Emdinstnumstr) <= 0 ){
		$message = 'Error : Please Add Atleast One Type';
	}else{
		$InQueryCon = 1;
	}
		$GlobID= '';
			$SelectTSQuery = "SELECT globid FROM sheet where sheet_id = '$Workname'";
			$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
			if($SelectTSSql == true){
				if(mysqli_num_rows($SelectTSSql)>0){
					$CList = mysqli_fetch_object($SelectTSSql);
					$GlobID = $CList->globid;
		      }
	       }
		
		  if($Emdinstnumstr != null){
			foreach($Emdinstnumstr as $Key => $Value){
				$EmdPur      	= $EmdPurstr[$Key];
				$Emdinstype    	= $Emdinstypestr[$Key];
				$Emdinstnum    	= $Emdinstnumstr[$Key];
				$Emdbname      	= $Emdbnamestr[$Key];
				$Emddate       	= $Emddatestr[$Key];
				$Emdexdate     	= $Emdexdatestr[$Key];
				$AmountList     = $AmountListstr[$Key];

				$TrimAmount 	= trim($AmountList);
				$Insertdate 	= dt_format($Emddate);
				$InsertExpdate 	= dt_format($Emdexdate);
				if($InQueryCon == 1){
					$insert_query1	= "insert into bg_fdr_details set master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='$EmdPur',  inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_bank_name='$Emdbname',
					inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_amt='$TrimAmount', userid='$userid', createdby='$userid',  created_section='$userid',  createdon= NOW() , active='1'";
					$insert_sql1 = mysqli_query($dbConn,$insert_query1);
					if($insert_sql1 == true){
					   $msg = "SD Details Saved Successfully ";
					   $success = 1;
				   }else{
					   $msg = " SD Details Details Not Saved. Error...!!! ";
		
				//echo trim($AmountList);exit;
				   }
			   }
			}
		   }
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
							<div class="container">
								<div class="row ">
									<div class="row clearrow"></div>
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row title " align="center">SD Entry</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div3">
													<label for="fname">	Work Short Name</label>
												</div>
												<div class="div7">
												   <select id="cmb_shortname" name="cmb_shortname" class="tboxclass">
														<option value="">--------------- Select --------------- </option>
														<?php echo $objBind->BindWorkOrderNoListAccounts(0);?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div3">
													<label for="fname">Name of Work</label>
												</div>
												<div class="div7">
													<textarea name='txt_work_name' id='txt_work_name' class="tboxclass" readonly=""></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div3">
													<label for="fname">Work Order No.</label>
												</div>
												<div class="div7">
													<textarea name='txt_order_num' id='txt_order_num' class="tboxclass" readonly=""></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
											   <div class="div3"><label for="fname">Work Order Cost (&#8377;)</label></div>
												<div class="div3" align="left">
													<input type="text" name="txt_Wo_Cost" id="txt_Wo_Cost" readonly class="tboxclass">
												</div>
												<div class="div2"> <label for="fname">&emsp;&emsp;Bidder's Name</label></div>
												<div class="div3" align="left">
													<input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxclass" value=""></td>
													<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxclass" value=""></td>
												</div>
											    <div class="row clearrow"></div>
											    <div class="row">
												<div class="div3" >	<label for="fname">SD %</label></div>
												<div class="div3" align="left">
													<input type="text" name="txt_sd_per" id="txt_sd_per" readonly class="tboxclass">
												</div>
												<div class="div2"> <label for="fname">&emsp;&emsp;&emsp;SD Value (&#8377;)</label></div>
												<div class="div3" align="left">
													<input type="text" name="txt_sd_value" id="txt_sd_value" readonly class="tboxclass">
												</div>
												<div class="row clearrow"></div>
												<div class="smediv">&nbsp;</div>
												  <div class="row">
													<div class="div12 gradientbg" align="left">&nbsp;Bank Guarantee Details</div>
													<div style="width:100%; height:auto;" align="center">
															<table class="itemtable etable" align="center" width="100%" id="pgtable1">
																<tr class="label" style="background-color:#EAEAEA">
																	<td align="center" >Purpose</td>
																	<td align="center">PG Type</td>
																	<td align="center">Bank Name</td>
																	<td align="center">BG/FDR Serial No.</td>
																	<!--<td align="center">Branch Address</td>-->
																	<td align="center">BG/FDR Date</td>
																	<td align="center">Expiry Date</td>
																	<td align="center">PG Amount ( &#8377; )</td>
																	<td align="center" colspan="2">Action</td>
																</tr>
																<tr>
																<td align="center">
																	<select name="cmd_purposes_0" id ="cmd_purposes_0" style="width:155px" class="tboxsmclass">
																					<option value="">--- Select BG Purpose--- </option>
																					<option value="PG">PG</option>
																					<option value="SD">SD</option>
																					<option value="SA">SA</option>
																					<option value="MOB1">MOB 1</option>
																					<option value="MOB2">MOB 2</option>
																					<option value="MOB3">MOB 3</option>
																					<option value="MOB4">MOB 4</option>
																					<option value="MOB5">MOB 5</option>
																					<option value="MOB6">MOB 6</option>
																					<option value="MOB7">MOB 7</option>
																					<option value="MOB8">MOB 8</option>
																					<option value="MOB9">MOB 9</option>
																				</select> 
																	</td>
																	<td align="center">
																		<select name="cmd_instype_0" id ="cmd_instype_0" style="width:155px" class="tboxsmclass">  
																			<option value="">---- Select ---- </option>
																			<option value="BG">Bank Guarantee</option>
																			<option value="FDR">Fixed Deposit Receipt</option>
																		</select>
																	</td>
																	<td align="center">
																		<input type="text" name="instrunum_0" id ="instrunum_0" class="textbox-new" style="width:110px;">
																	</td>
																	<td align="center"><input type="text" class="textbox-new" style="width:100px;" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																	<!--<td align="center"><input type="text" class="textbox-new" style="width:100px;" name="txt_sno_pg_0" id="txt_sno_pg_0"></td>-->
																	<td align="center"><input type="text" placeholder="DD/MM/YYYY"  class="tboxsmclass date"style="width:100px;" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																	<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate" style="width:100px;" name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																	<td align="center"><input type="number" class="textbox-new" style="width:100px;" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																	<td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="fa btn btn-info"></td>
																		<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																</tr>
																<input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new" style="width:110px;">
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
	
	$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			var ShortName   	= $("#cmb_shortname").val();
			var WorkName 	    = $("#txt_workname").val();
			var EnginnerName 	= $("#cmb_engineer").val();
			var BidderName 	    = $("#cmb_bidder").val();
			var LoINum	 	    = $("#txt_loi_no").val();
			var LoIDate 	   = $("#txt_loi_date").val();
			var EmdAmount 	  = $("#txt_full_emd_amt").val();
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Tender Number..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Please Enter Name of work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnginnerName == ""){
				BootstrapDialog.alert("Please Select Engineer name..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BidderName == ""){
				BootstrapDialog.alert("Please Select Bidder name..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(LoINum == ""){
				BootstrapDialog.alert("Please Enter LOI Number..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(LoIDate == ""){
				BootstrapDialog.alert("Please Select LOI Date..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EmdAmount == ""){
				BootstrapDialog.alert("Please Enter EMD Amount..!!");
				event.preventDefault();
				event.returnValue = false;
			}
		});
		$("body").on("change","#cmb_shortname", function(event){
			var Id = $(this).val();// alert(SheetId);

			
			$("#txt_pg_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_work_name").val('');
			$("#txt_order_num").val('');
			$("#txt_Wo_Cost").val('');
			$("#txt_sd_per").val('');
			$("#txt_bidder").val('');
			$("#txt_contid").val('');
			$("#txt_sd_value").val('');
			$.ajax({ 
				type: 'POST', 
				url: 'FindEstTsTrName.php', 
				data: { Id: Id, Page: 'SD'}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
			
						$("#txt_work_name").val(data.work_name);
						$("#txt_order_num").val(data.work_order_no);
						$("#txt_bidder").val(data.name_contractor);
						$("#txt_contid").val(data.contid);
						$("#txt_Wo_Cost").val(data.work_order_cost);
					}
				}
			});
		
			if(Id!= ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindBiddersNameSD.php', 
					data: { Id: Id }, 
					dataType: 'json',
					success: function (data) { 
					if(data != null){ 
						$("#txt_sd_per").val(data.sd_per);
						var sdper = $("#txt_sd_per").val(); 
						var workcost = $("#txt_Wo_Cost").val();
						var SDvalue= (Number(sdper) / 100) *Number(workcost);
						$("#txt_sd_value").val(SDvalue); 
					  }
					}
					
				});
			     	
			}
		
					
			
		});
	});

	$("body").on("click", "#emp_add", function(event){ 
		var Purpose 	 = $("#cmd_purposes_0").val();
		var InstType 	 = $("#cmd_instype_0").val();
		var InstNum 	 = $("#instrunum_0").val();
		var BankName   	 = $("#txt_bankname_pg_0").val();
		var DateofIssue  = $("#txt_date_pg_0").val();
		var DateofExpiry = $("#txt_expir_date_pg_0").val();
		var AmtDetail	 = $("#txt_part_amt_0").val(); //alert(AmtDetail);
		var RowStr = '<tr><td><input type="text" name="cmd_purposes[]" class="tboxsmclass" style="width:100px;" readonly value="'+Purpose+'"></td><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype[]" class="tboxsmclass" style="width:100px;" value="'+InstType+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum[]" class="textbox-new" style="width:100px;" value="'+InstNum+'"></td><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg[]" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="textbox-new" style="width:100px;" value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg[]" class="textbox-new" style="width:100px;" value="'+DateofExpiry+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt[]" readonly class="textbox-new EmAmt" style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		if(InstType == 0){
			alert("Bank Address should not be empty");
			return false;
		}else if(Purpose == 0){
			alert("Purpose should not be empty");
			return false;
		}else if(InstNum == 0){
			alert("Instrument Number should not be empty");
			return false;
		}else if(BankName == 0){
			alert("Bank Name should not be empty");
			return false;
		}else if(DateofIssue == 0){
			alert("Date of Issue should not be empty");
			return false;
		}else if(DateofExpiry == 0){
			alert("Date of Expiry should not be empty");
			return false;
		}else if(AmtDetail == 0){
			alert("Amount should not be empty");
			return false;
		}else{
			$("#pgtable1").append(RowStr);
			$("#cmd_purposes_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			// $("#txt_sno_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt_0").val('');
			$("#text_totalamt").val('');
		}
		TotalUnitAmountCalc();

	});
	$("body").on("click", ".delete", function(){
		$(this).closest("tr").remove();
		TotalUnitAmountCalc();
		$("#text_totalamt").val('');
	});
	function TotalUnitAmountCalc(){
					var TotalAmt = 0;
					$(".EmAmt").each(function(){
						var Amt = $(this).val(); 
						TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
						$("#text_totalamt").val(TotalAmt);
					
					});
				}
	$('#cmb_tr_no').chosen();


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
	$("#btn_save").click(function(){ //alert(1);
				var pgamt = $("#txt_sd_value").val(); 
			    var totalamt = $("#text_totalamt").val();  
	
				if(pgamt!=totalamt){
					var a="SD Amount is not Equal to the Total BG/FDR Amout";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
					//$('#val_date').text(a);
				}
				});

</script>


