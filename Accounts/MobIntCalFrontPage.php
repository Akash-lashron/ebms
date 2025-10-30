<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
///require('php-excel-reader/excel_reader2.php');
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

if($_POST["btn_interest"] == "Interest Calculate") {
	$sheetid         = trim($_POST['cmb_shortname']);
	$mobadvno        = trim($_POST['txt_mob_no']);
	$rbn             = trim($_POST['txt_rab']);
	$recoveryamt     = trim($_POST['txt_amt_rec']);
	//echo $sheetid;exit;
	if($sheetid != null){
		header('Location: MobIntCalView.php?sheetid='.$sheetid.'&mobadvamt='.$recoveryamt.'');
		//header('Location: MobIntCalView.php?sheetid='.$sheetid.'');
	}
} 

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function ViewBidder(){
		url = "BiddersList.php";
		window.location.replace(url);
	}
	function goBack(){
			url = "Home.php";
			window.location.replace(url);
		}
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

<script type="text/javascript" language="javascript">
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
							  <div class="box-container box-container-lg" align="center">
								<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="centre">Mobilization Advance Interest Calculation Form</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																    <div class="row clearrow"></div>
																    <div class="row">
																       <div class="div3 dataFont">
																		    Work Short Name
																       </div>
																       <div class="div9">
																            <select id="cmb_shortname" name="cmb_shortname" class="tboxclass" style="width:99%;">
																		       <option value="">--------------- Select --------------- </option>
																		       <?php echo $objBind->BindWorkOrderNoListAccounts(0);?>
																	        </select>
																        </div>
															        </div>
															        <div class="row clearrow"></div>
															        <div class="row">
												                       <div class="div3 dataFont">
													                      Work Order No.
												                      </div>
												                      <div class="div9">
													                     <input type ="text" name='txt_order_num' id='txt_order_num' class="tboxclass" readonly=""></input>
												                     </div>
											                     </div>
											                     <div class="row clearrow"></div>
											                     <div class="row">
											                         <div class="div3 dataFont">Work Order Cost (&#8377;)</div>
												                     <div class="div3" align="left">
													                    <input type="text" name="txt_Wo_Cost" id="txt_Wo_Cost" readonly class="tboxclass">
												                     </div>
														             <div class="div3 dataFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contractor's Name</div>
												                     <div class="div3" align="left">
																          <input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxclass" value=""></td>
																          <input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxclass" value=""></td>
												                     </div>
														         </div>
														         <div class="row clearrow"></div>
																<!--<div class="row">
																		<div class="div3 dataFont">Mobilization No.</div>
																		<div class="div3" align="left">
																				<input type="text" name="txt_mob_no" id="txt_mob_no" readonly class="tboxclass">
																		</div>
														                <div class="div3 dataFont">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RAB.</div>
												                        <div class="div3" align="left">
																		   <input type="text" name='txt_rab' id='txt_rab' readonly class="tboxclass" value=""></td>
																		   <input type="hidden" name='txt_mobmid' id='txt_mobmid' readonly class="tboxclass" value=""></td>
																		</div>
														            </div>-->
														            <div class="row clearrow"></div>
														            <div class="face-static">
															            <div class="card-header inkblue-card hidden Details" align="left">&nbsp;Mobilization Advance Recieved / Recovered Details</div>
															            <div class="card-body padding-1">
																           <div class="row clearrow hidden Details" id="MOBDetails"></div>
															            </div>
														            </div>
														            <div class="row clearrow isappcheck" style="display-none"></div>
															             <!--    2nd Div Starts Here   -->
														            <div class="face-static">
														                <div class="card-header inkblue-card" align="left">&nbsp;Mobilization Advance Amount Recovery Entry</div>
														                    <div class="card-body padding-1">
														                        <div class="row clearrow"></div>
															                    <div class="row">
															                        <input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new" style="width:110px;">
																                    <div class="div3 dataFont" >&nbsp;Amount Recovered in this Bill (&#8377;)</div>
																                    <div class="div2" align="left">
																	                    <input type="text" name="txt_amt_rec" id="txt_amt_rec"  class="tboxclass">
																                    </div>
															                    </div>	
															                    <div class="row clearrow"></div>	
																															
																		            <!-- <table class="dataTable etable " align="center" width="100%" id="pgtable1">
																		                <tr class="label" style="background-color:#FFF">
																							<td align="center">Instrument Type</td>
																							<td align="center">Bank Name</td>
																							<td align="center">BG/FDR Serial No.</td>
																							<td align="center">BG/FDR Date</td>
																							<td align="center">Expiry Date</td>
																							<td align="center">Instrument Amount ( &#8377; )</td>
																							<td align="center" colspan="2">Action</td>
																		               </tr>
																		               <tr>
																							<td align="center">
																								<select name="cmd_instype_0" id ="cmd_instype_0" style="width:155px" class="tboxclass">  
																									<option value="BG">Bank Guarantee</option>
																								</select>
																							</td>
																							<td align="center"><input type="text" class="tboxclass"  name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																							<td align="center">
																							<input type="text" name="instrunum_0" id ="instrunum_0" class="tboxclass" style="width:110px;">
																							</td>
																							<td align="center"><input type="text" placeholder="DD/MM/YYYY"  class="tboxclass date" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																							<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxclass expdate"  name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																							<td align="center"><input type="number" class="tboxclass" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																							<td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="fa btn btn-info"></td>
																					     <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																                      </tr>
																			              <input type="hidden" name="text_Intrutotalamt" id ="text_Intrutotalamt" class="textbox-new" style="width:110px;">
																		           </table>   -->
																	           </div>    
																            </div>
																				<div class="row clearrow"></div>	
																				<div class="div12" align="center">
																					<input type="submit" class="btn btn-info" name="btn_interest" id="btn_interest" value="Interest Calculate" />
																				</div>
																				<div class="row clearrow"></div>	
															            </div>
														            </div>
														        </div>
													        </div>
												        </div>
												       <div class="row clearrow"></div>												
												       <div class="div12" align="center">
												            <!-- <div class="row">
														        <div class="div12" align="center">
																	<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																	<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
														       </div>
													        </div>  -->
												        </div>
												        <div class="row clearrow"></div>										
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
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
	$("#cmb_shortname").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	
	$(document).ready(function(){ 
		$("body").on("click","#btn_interest", function(event){

			var ShortName   	= $("#cmb_shortname").val();
			var WorkName 	   = $("#txt_order_num").val();
			var EnginnerName 	= $("#txt_Wo_Cost").val();
			var BidderName 	= $("#txt_bidder").val();
			var MobAmount 	   = $("#txt_amt_rec").val();
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Name of work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Please Enter Amount..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnginnerName == ""){
				BootstrapDialog.alert("Please Enter Engineer Name..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BidderName == ""){
				BootstrapDialog.alert("Please Enter Contractor Name..!!");
				event.preventDefault();
				event.returnValue = false;
			}
			else if(MobAmount == ""){
				BootstrapDialog.alert("Please Enter Amount to be Recovered..!!");
				event.preventDefault();
				event.returnValue = false;
			}
		});

		$("body").on("click","#btn_save", function(event){
			var ShortName   	= $("#cmb_shortname").val();
			var WorkName 	   = $("#txt_workname").val();
			var EnginnerName 	= $("#cmb_engineer").val();
			var BidderName 	= $("#txt_bidder").val();
			var MobAmount 	   = $("#txt_Mob_value").val();
		
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Name of work..!!");
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
			}else if(MobAmount == ""){
				BootstrapDialog.alert("Please Enter MOB Amount..!!");
				event.preventDefault();
				event.returnValue = false;
		   }else if(InstType == ""){
				BootstrapDialog.alert("Please Select One Type..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(InstNum == ""){
				BootstrapDialog.alert("Please Enter Serial No..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankName == ""){
				BootstrapDialog.alert("Please Enter Bank Name..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(DateofIssue == ""){
				BootstrapDialog.alert("Please Enter Date of Issue..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(DateofExpiry == ""){
				BootstrapDialog.alert("Please Enter Expiry Date..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(AmtDetail == ""){
				BootstrapDialog.alert("Please Enter Amount..!!");
				event.preventDefault();
				event.returnValue = false;
			}
		});
		$("body").on("change","#cmb_shortname", function(event){
			var Id = $(this).val(); //alert(Id);

			//$(".Details").removeClass("hidden");
			$("#txt_Mob_value").val('');
			$("#txt_pg_amt").val('');
			$("#txt_pg_value").val('');
			$("#txt_work_name").val('');
			$("#txt_order_num").val('');
			$("#txt_Wo_Cost").val('');
			$("#txt_sd_per").val('');
			$("#txt_bidder").val('');
			$("#txt_contid").val('');
			$(".Details").removeClass("hidden");
			$("#MOBDetails").html(''); 
			$("#txt_mobno").val('');
			$("#txt_mob_amt").val('');
			$("#txt_mob_date").val('');
			$("#txt_recovered_date").val('');
			
			$.ajax({ 
				type: 'POST', 
				url: 'FindEstTsTrName.php', 
				data: { Id: Id, Page: 'MOB'}, 
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
			$.ajax({ 
				type: 'POST', 
				url: 'FindMOBNo.php', 
				data: { Id: Id}, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){ 
						//$("#txt_mob_no").val(data.mob_adv_no);
						//$("#txt_rab").val(data.rbn);
						$("#txt_mobmid").val(data.mobmid);
					}
				}
				
			});  
			$.ajax({ 
				type: 'POST', 
				url: 'GetMOBDetails.php',  
				data: { Id: Id}, 
				dataType: 'json',
				success: function (data) {  
					var Result1 = data['row1']; //alert(JSON.stringify(data));
					var EmptyStr = ""; 
					var BankStr  = "<table class='dataTable etable' align='center' >";
						BankStr += "<tr class='label' style='background-color:#FFF'>";
						BankStr += "<th>Mobilization Advance No.</th>";
						BankStr += "<th>Mobilization Amount</th>";
						BankStr += "<th>Dated</th>";
						BankStr += "<th>BG Serial No.</th>";
						BankStr += "<th>BG Validity Upto</th>";
						BankStr += "<th>BG Value</th></tr>";
				 if (Result1 != null){
					$.each(Result1, function(index, element){ 
						BankStr += "<tr>";
						BankStr +="<td align='center'><input type='text' readonly align='centre' class='tboxclass' name='txt_mobno' id='txt_mobno' value="+element.mob_adv_no+" ></td>";
						BankStr +="<td style='text-align:right; !important'><input type='text' readonly class='tboxclass EmAmt' name='txt_mob_amt' id='txt_mob_amt'  value="+element.mob_adv_amt+" ></td>";
						BankStr +="<td align='left'><input type='text' readonly class='tboxclass' name='txt_mob_date' id='txt_mob_date'  value="+element.amt_issused_dt+" ></td>";
						BankStr +="<td align='left'><input type='text' readonly class='tboxclass' name='txt_mob_date' id='txt_mob_date'  value="+element.inst_serial_no+" ></td>";
						BankStr +="<td align='left'><input type='text' readonly class='tboxclass' name='txt_mob_date' id='txt_mob_date'  value="+element.inst_exp_date+" ></td>";
						BankStr +="<td align='left'><input type='text' readonly class='tboxclass' name='txt_recovered_date' id='txt_recovered_date'  value="+element.inst_amt+" ></td></tr>";
					});
					BankStr += "</table>";
					$("#MOBDetails").html(BankStr);
				   TotalAmountCalc();
				 }else{
					EmptyStr +="<div style='text-align:center'>Mobilization Details Not Available</div>";
					$("#MOBDetails").html(EmptyStr);
				}
			  }
			});
		});
	});
	function TotalAmountCalc(){
		var TotalAmt = 0;
		$(".EmAmt").each(function(){
			var Amt = $(this).val(); ///alert(Amt);
			TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
			$("#text_totalamt").val(TotalAmt);
		});
	}

	$("body").on("change","#txt_Mob_value", function(event){
		var Mobval= $(this).val();
		var Mobper= 10; 
		var Workvalue = $("#txt_Wo_Cost").val(); 
		var Totalamt = $("#text_totalamt").val();
		$("#txt_pg_value").val('');
			var PGBvalue= (Number(Mobper)/100) *Number(Workvalue); 
			var Mobamnt = PGBvalue-Totalamt;
		if(Mobval > PGBvalue){ 
			var a="Mobilisation Amount is Greater than 10% of Work Order Cost  "+PGBvalue+"";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}else if(Mobval > Mobamnt){
			var a="Mobilisation Amount is Greater than Eligible Amount of  "+Mobamnt+"";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
			$("#txt_pg_value").val('');
		}else{
			var a="";
			$('#val_date').text(a);
	
		}
	});
	$("body").on("click", "#emp_add", function(event){ 
		var Purpose 	 = $("#cmd_purposes_0").val();
		var InstType 	 = $("#cmd_instype_0").val();
		var InstNum 	 = $("#instrunum_0").val();
		var BankName   	 = $("#txt_bankname_pg_0").val();
		var DateofIssue  = $("#txt_date_pg_0").val();
		var DateofExpiry = $("#txt_expir_date_pg_0").val();
		var AmtDetail	 = $("#txt_part_amt_0").val(); //alert(AmtDetail);
		var RowStr = '<tr><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype[]" class="tboxclass"  value="'+InstType+'"></td><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg[]" class="tboxclass"  value="'+BankName+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum[]" class="tboxclass"  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxclass"  value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg[]" class="tboxclass"  value="'+DateofExpiry+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt[]" readonly class="tboxclass InAmt" width:200px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
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
			$("#text_Intrutotalamt").val('');
		}
		TotalUnitAmountCalc();

	});
	$("body").on("click", ".delete", function(){
		$(this).closest("tr").remove();
		TotalUnitAmountCalc();
		$("#text_Intrutotalamt").val('');
	});
	function TotalUnitAmountCalc(){
		var TotalAmt = 0;
		$(".InAmt").each(function(){
			var Amt = $(this).val(); 
			TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
			$("#text_Intrutotalamt").val(TotalAmt);
		});
	}


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
	$("#btn_save").click(function(){ 
		var pgamt = $("#txt_Mob_value").val(); 
			var totalamt = $("#text_Intrutotalamt").val();  

		if(pgamt!=totalamt){
			var a="Mob Amount is not Equal to the Total BG/FDR Amout";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}else{
			var a="";
			//$('#val_date').text(a);
		}
	});
	

</script>





