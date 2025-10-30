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

if(isset($_POST['btn_save']) == " Save "){

	$MobID	        = $_POST["txt_mobmid"];
	$Workname	    = $_POST["cmb_shortname"];
	$Contractorid	= $_POST["txt_contid"];
	$WorkCost	    = $_POST["txt_Wo_Cost"];
	$MobAmtid	    = $_POST["txt_Mob_value"];
	$MobNo	        = $_POST["txt_mob_no"];
	$MobRba	        = $_POST["txt_rab"];
	$Emdinstypestr	= $_POST["cmd_instype"];
	$Emdinstnumstr	= $_POST["instrunum"];
	$Emdbnamestr	= $_POST["txt_bankname_pg"];
	$Emddatestr		= $_POST["txt_date_pg"];
	$Emdexdatestr	= $_POST["txt_expir_date_pg"];
	$AmountListstr	= $_POST["txt_part_amt"];

	if($Workname == null){
		$msg = 'Error : Please Select Work Short Name..!!!';
	}else if($Emdinstnumstr == null ){ 
		$msg = 'Error : Please Add Atleast One PG Type';
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
		   $RBN= '';
			$SelectTSQuery = "SELECT  max(CAST(rbn AS UNSIGNED)) as max_rbn FROM abstractbook where sheetid = '$Workname'";
			//echo $SelectTSQuery; exit;
			$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
			if($SelectTSSql == true){
				if(mysqli_num_rows($SelectTSSql)>0){
					$CList = mysqli_fetch_object($SelectTSSql);
					$RBN = $CList->max_rbn;
		      }
	       }
		   $insert_query1		=  "insert into mob_master set   sheetid='$Workname', mob_adv_no='$MobNo', rbn='$MobRba', mob_adv_amt='$MobAmtid',
		                            createdby = '$UserId', createdon = NOW()";
			$MObinsert_query    = mysqli_query($dbConn,$insert_query1);	
			$InsertedMoipgId    = mysqli_insert_id($dbConn);
		
		  if($Emdinstnumstr != null){
			foreach($Emdinstnumstr as $Key => $Value){
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
					$insert_query1	= "insert into bg_fdr_details set master_id='$InsertedMoipgId',globid='$GlobID', contid='$Contractorid', mob_adv_no='$MobNo', inst_purpose='MOB',  inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_bank_name='$Emdbname',
					inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_amt='$TrimAmount', userid='$userid', createdby='$userid',  created_section='$userid',  createdon= NOW() , active='1'";
					$insert_sql1 = mysqli_query($dbConn,$insert_query1);
					if($insert_sql1 == true){
					   $msg = "Mobilization Details Saved Successfully ";
					   $success = 1;
				   }else{
					   $msg = " Mobilization Details Details Not Saved. Error...!!! ";
		
				//echo trim($AmountList);exit;
				   }
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
												<div class="card-header inkblue-card" align="centre">Mobilization Advance Entry Form</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																    <div class="row clearrow"></div>
																    <div class="row">
																       <div class="div3 lboxlabel">
																		    Work Short Name
																       </div>
																       <div class="div9">
																            <select id="cmb_shortname" name="cmb_shortname" class="tboxsmclass">
																		       <option value="">--------------- Select --------------- </option>
																		       <?php echo $objBind->BindWorkOrderNoListAccounts(0);?>
																	        </select>
																        </div>
															        </div>
																	
															        <div class="row clearrow"></div>
															        <div class="row">
												                       	<div class="div3 lboxlabel">
													                      	Work Order No.
												                      	</div>
												                      	<div class="div9">
													                     	<input type ="text" name='txt_order_num' id='txt_order_num' class="tboxsmclass" readonly="">
												                     	</div>
											                     	</div>
											                     	<div class="row clearrow"></div>
											                     	<div class="row">
											                        	<div class="div3 lboxlabel">Work Order Cost (&#8377;)</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_Wo_Cost" id="txt_Wo_Cost" readonly class="tboxsmclass">
																		</div>
																		<div class="div2 cboxlabel">Bidder's Name</div>
																		<div class="div4" align="left">
																			<input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass" value="">
																			<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxclass" value="">
																		</div>
														         	</div>
														         	<!--<div class="row clearrow"></div>
														         	<div class="row">
											                        	<div class="div3 lboxlabel">&nbsp;</div>
												                      	<div class="div9 lboxlabel" align="left">
													                       	<input type="radio" name="secadv_type" id="with_meas" value="WM" > With Measurements 
																			&nbsp;&nbsp;&nbsp;&nbsp;
																			<input type="radio" name="secadv_type" id="zero_meas" value="ZM" > Zero Measurements 
												                       	</div>
														            </div>-->
														            <div class="row clearrow"></div>
																	<div class="card cabox hidden Details">	
																		<div class="face-static">
																			<div class="card-header inkblue-card" align="left">&nbsp;Mobilization Recieved Details</div>
																			<div class="card-body padding-1">
																			   <div class="row clearrow" id="MOBDetails"></div>
																			</div>
																		</div>
																	</div>
														            <!--<div class="row clearrow isappcheck  hidden Details"></div>-->
															             <!--    2nd Div Starts Here   -->
																	<div class="card cabox">	 
																		 
																		<div class="face-static">
																			<div class="card-header inkblue-card" align="left">&nbsp;Mobilization Entry</div>
																			<div class="card-body pd4">
																				<div class="row clearrow"></div>
																				<div class="row">
																					<!--<div class="div1 lboxlabel" >&nbsp; RAB </div>
																					<div class="div1" align="left">
																						<input type="text" name='txt_rab' id='txt_rab' readonly class="tboxsmclass" value="">
																						<input type="hidden" name='txt_mobmid' id='txt_mobmid' readonly class="tboxsmclass" value="">
																					</div>-->
																					<div class="div3 lboxlabel" >&nbsp; Mobilization Advance No.</div>
																					<div class="div1" align="left">
																						<input type="text" name="txt_mob_no" id="txt_mob_no" readonly class="tboxsmclass">
																					</div>
																					<div class="div2 cboxlabel" >&nbsp; Amount Request (&#8377;)</div>
																					<div class="div2" align="left">
																						<input type="text" name="txt_Mob_value" id="txt_Mob_value"  class="tboxsmclass">
																						<input type="hidden" name="text_totalamt" id ="text_totalamt" class="tboxsmclass" style="width:110px;">
																					</div>
																				</div>	
																				<div class="row clearrow"></div>
																				<div class="div12 ">	
																					<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																						<tr class="label" style="background-color:#FFF">
																							<!-- <td align="center" >Purpose</td> -->
																							<td align="center">Instrument Type</td>
																							<td align="center">Bank Name</td>
																							<td align="center">BG/FDR Serial No.</td>
																							<!--<td align="center">Branch Address</td>-->
																							<td align="center">BG/FDR Date</td>
																							<td align="center">Expiry Date</td>
																							<td align="center">Instrument Amount ( &#8377; )</td>
																							<td align="center" colspan="2">Action</td>
																					   </tr>
																					   <tr>
																						<!-- <td align="center">
																							<input type=hidden name="cmd_purposes_0" id ="cmd_purposes_0"  class="tboxclass" value="MOB"></input>
																											
																							</td> -->
																							<td align="center">
																								<select name="cmd_instype_0" id ="cmd_instype_0" style="width:155px" class="tboxsmclass">  
																									<option value="BG">Bank Guarantee</option>
																								</select>
																							</td>
																							<td align="center"><input type="text" class="tboxsmclass"  name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																							<td align="center">
																							<input type="text" name="instrunum_0" id ="instrunum_0" class="tboxsmclass" style="width:110px;">
																							</td>
																							<td align="center"><input type="text" placeholder="DD/MM/YYYY"  class="tboxsmclass date" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																							<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate"  name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																							<td align="center"><input type="number" class="tboxsmclass" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																							<td align="center"><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="fa btn btn-info"></td>
																							<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																						</tr>
																							<input type="hidden" name="text_Intrutotalamt" id ="text_Intrutotalamt" class="textbox-new" style="width:110px;">
																					</table>
																					<div class="row clearrow"></div>
																					</div>
																				</div>    
																			</div>
																		</div>
															 		</div>
														    	</div>
															</div>
														</div>
													</div>
												    <div class="row clearrow"></div>												
												    <div class="div12" align="center">
														<div class="row">
															<div class="div12" align="center">
																<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
																<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
														   </div>
														</div> 
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
	function getrbn(){
		var WorkId = $("#cmb_shortname").val();
		$.ajax({ 
			type: 'POST', 
			url: 'FindWorkData.php', 
			data: { WorkId: WorkId }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$("#txt_rbn").val(data['rbn']);
				}
			}
		});
	}
	$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			var ShortName   	= $("#cmb_shortname").val();
			var WorkName 	    = $("#txt_workname").val();
			var EnginnerName 	= $("#cmb_engineer").val();
			var BidderName 	    = $("#txt_bidder").val();
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
			var Id = $(this).val();// alert(SheetId);

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
						$("#txt_mob_no").val(data.mob_adv_no);
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
					var Result1 = data['row1']; 
					var EmptyStr = ""; 
					var	BankStr  = "<table class='dataTable etable' align='center' >";
						BankStr += "<tr class='label' style='background-color:#FFF'>";
						BankStr += "<th>Mobilization Advance No.</th>";
						BankStr += "<th>Mobilization Amount</th>";
						BankStr += "<th>Dated</th>";
						BankStr += "<th>Amount Recovered</th></tr>";
				 if (Result1 != null){
					$.each(Result1, function(index, element){
					BankStr += "<tr>";
					BankStr +="<td align='center'><input type='text' readonly align='centre' class='tboxsmclass' name='txt_mobno' id='txt_mobno' value="+element.mob_adv_no+" ></td>";
					BankStr +="<td align='left'><input type='text' readonly class='tboxsmclass EmAmt' name='txt_mob_amt' id='txt_mob_amt'  value="+element.mob_adv_amt+" ></td>";
				 	BankStr +="<td align='left'><input type='text' readonly class='tboxsmclass' name='txt_mob_date' id='txt_mob_date'  value="+element.createdon+" ></td>";
				 	BankStr +="<td align='left'><input type='text' readonly class='tboxsmclass' name='txt_recovered_date' id='txt_recovered_date'  value= ></td></tr>";
				  });
					BankStr += "</table>";
		           $("#MOBDetails").html(BankStr);
				   TotalAmountCalc();
					
				 }else{
					EmptyStr +="<div style='text-align:center' class='cboxlabel'>Mobilization Advance Received Details Not Available</div>";
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
		var RowStr = '<tr><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg[]" class="tboxsmclass"  value="'+BankName+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum[]" class="tboxsmclass"  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass"  value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg[]" class="tboxsmclass"  value="'+DateofExpiry+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt[]" readonly class="tboxsmclass InAmt" width:200px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
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
	$("#with_meas").click(function(){
		$("#txt_rbn").val("");
		$("#txt_rbn").addClass("DisableInput");
		$("#txt_rbn").attr("readonly", true);
		getrbn();
		
	});
	$("#zero_meas").click(function(){
		$("#txt_rbn").val("");
		$("#txt_rbn").removeClass("DisableInput");
		$("#txt_rbn").attr("readonly", false); 
		//$("#cmb_mbook_no").chosen('destroy');
		//$("#cmb_mbook_no").chosen();
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
			var a="Total BG/FDR amount should be greater than or equal to Mobilization Advance requested amount";
			BootstrapDialog.alert(a);
			event.preventDefault();
			event.returnValue = false;
		}else{
			var a="";
		}
	});
	

</script>





