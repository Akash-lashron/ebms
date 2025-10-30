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

if(isset($_POST['btn_save']) == " Save "){
	
	$Workname	                 = $_POST["cmb_shortname"];
	$SDPEr	                     = $_POST["txt_sd_per"];
	$Contractorid	             = $_POST["txt_contid"];
	$EmdPurstr   	             = $_POST["cmd_purposes"];
	$Emdinstypestr               = $_POST["cmd_instype"];
	$Emdinstnumstr	             = $_POST["instrunum"];
	$Emdbnamestr	             = $_POST["txt_bankname_pg"];
	$Emddatestr		             = $_POST["txt_date_pg"];
	$Emdexdatestr	             = $_POST["txt_expir_date_pg"];
	$Emdextensiondatestr	     = $_POST["txt_exten_date_pg"];
	$AmountListstr	             = $_POST["txt_part_amt"];
	$LOISDID	                 = $_POST["txt_LOIdid"];
	$EmdCreatedbystr	         = $_POST["txt_createdby"];
	$EmdCreatedSesstr	         = $_POST["txt_createdsess"];
	$Emdcreatedonstr	         = ($_POST['txt_createdon']);
	
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
		   if ($LOISDID != null){
				$Deletequery    = "DELETE FROM bg_fdr_details WHERE globid='$GlobID' AND master_id='$Workname'";
				$BFDeletequery    = mysqli_query($dbConn,$Deletequery);	
				foreach($Emdinstnumstr as $Key => $Value){
					$Emdinstype         	= $Emdinstypestr[$Key];
					$Emdinstnum             = $Emdinstnumstr[$Key];
					$Emdbname           	= $Emdbnamestr[$Key];
					$Emddate            	= $Emddatestr[$Key];
					$Emdexdate     	        = $Emdexdatestr[$Key];
					$Emdextendate           = $Emdextensiondatestr[$Key];
					$AmountList             = $AmountListstr[$Key];
					$Createdby     	        = $EmdCreatedbystr[$Key];
					$CreatedSes             = $EmdCreatedSesstr[$Key];
					$Createdon              = $Emdcreatedonstr[$Key];
					$TrimAmount 	        = trim($AmountList);
					$Insertdate 	        = dt_format($Emddate);
					$InsertExpdate 	        = dt_format($Emdexdate);
					$Insertextendate	    = dt_format($Emdextendate);
					$InsertCreatedon 	    = dt_format($Createdon);
				if($InQueryCon == 1){
				
					$insert_query3		= "insert into bg_fdr_details set master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='SD',  inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_bank_name='$Emdbname',
										inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='ACC', approved_by='$staffid', approved_session='ACC', approvedon = NOW(), createdby='$Createdby',  
										created_section='$CreatedSes',  createdon= '$InsertCreatedon', active='1'";
										//echo $insert_query3."<br/>";
					$Loidetailinsert_query    = mysqli_query($dbConn,$insert_query3);
					
					if($Loidetailinsert_query == true){
						$msg = "SD Details Updated Successfully ";
						$success = 1;
					}else{
						$msg = " SD Details Details Not Updated. Error...!!! ";
						$success = 0;
						}
		   }else { 
				if($Emdinstnumstr != null){
					foreach($Emdinstnumstr as $Key => $Value){
						$EmdPur      	= $EmdPurstr[$Key];
						$Emdinstype    	= $Emdinstypestr[$Key];
						$Emdinstnum    	= $Emdinstnumstr[$Key];
						$Emdbname      	= $Emdbnamestr[$Key];
						$Emddate       	= $Emddatestr[$Key];
						$Emdexdate     	= $Emdexdatestr[$Key];
						$Emdextendate   = $Emdextensiondatestr[$Key];
						$AmountList     = $AmountListstr[$Key];

						$TrimAmount 	= trim($AmountList);
						$Insertdate 	= dt_format($Emddate);
						$InsertExpdate 	= dt_format($Emdexdate);
						$Insertextendate	= dt_format($Emdextendate);
							if($InQueryCon == 1){
								$insert_query1	= "insert into bg_fdr_details set master_id='$Workname',globid='$GlobID', contid='$Contractorid', inst_purpose='SD',  inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_bank_name='$Emdbname',
								inst_date='$Insertdate', inst_exp_date='$InsertExpdate', inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='ACC' ,createdby='$staffid',  created_section='ACC',  createdon= NOW() , active='1'";
								$insert_sql1 = mysqli_query($dbConn,$insert_query1);
								//echo $insert_query1."<br/>"; 
								$update_query1	="UPDATE works SET  sd_perc='$SDPEr' WHERE globid = '$GlobID' AND  sheetid = '$Workname'";
								$insert_sql1 = mysqli_query($dbConn,$update_query1);

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
	  }
   }
	//exit;
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
			url = "SDRegister.php";
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
						<!--<div align="right" class="users-icon-part">&nbsp;</div>-->
						<blockquote class="bq1 stable" style="overflow:auto">
							<div class="row">
								<div class="box-container box-container-lg" align="center">
								<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="center">SD Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">
																				Work Short Name
																		</div>
																		<div class="div8">
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
																		<div class="div8">
																		<textarea name='txt_order_num' id='txt_order_num' class="tboxsmclass" readonly=""></textarea>
																		</div>
																	</div>
											             			<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">Work Order Cost (&#8377;)</div>
																		<div class="div3" align="left">
																		<input type="text" name="txt_Wo_Cost" id="txt_Wo_Cost" readonly class="tboxsmclass">
																	</div>
																	<div class="div2 lboxlabel"> &emsp;&emsp;Contractor Name</div>
																	<div class="div3" align="left">
																		<input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass" value=""></td>
																		<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxsmclass" value=""></td>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel" >SD %</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_sd_per" id="txt_sd_per"  class="tboxsmclass">
																		</div>
																		<div class="div2 lboxlabel"> &emsp;&emsp;SD Value (&#8377;)</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_sd_value" onKeyPress="return isPercentageValue(event,this);"  id="txt_sd_value" readonly class="tboxsmclass">
																		</div>
																	</div>
																	<div class="row clearrow isappcheck" style="display-none"></div>
																	<div class="row clearrow"></div>														

																	<div class="card-header inkblue-card" align="left">&nbsp;Bank Guarantee Details</div>
																	<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																		<tr class="label" style="background-color:#FFF">
																			<th align="center">Instrument Type</th>
																			<th align="center">Bank Name</th>
																			<th align="center">BG/FDR Serial No.</th>
	
																			<th align="center">BG/FDR Date</th>
																			<th align="center">Expiry Date</th>
																			<th align="center">Extension Date</td>
																			<th align="center">Instrument Amount ( &#8377; )</th>
																			<th align="center" colspan="2">Action</th>
																		</tr>
																		<tr>
																			<td align="center" style="width:50px;">
																				<select name="cmd_instype_0" id ="cmd_instype_0"  class="tboxsmclass">  
																					<option value="">-Select- </option>
																					<option value="BG">BG</option>
																					<option value="FDR">FDR</option>
																					<option value="DD">DD</option>
																				</select>
																			</td>
																			<td align="center"  style="width:250px;"><input type="text" class="tboxsmclass"  name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																			<td align="center" style="width:150px;">
																				<input type="text" name="instrunum_0" id ="instrunum_0" class="tboxsmclass" >
																			</td>
																			<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY"  class="tboxsmclass date" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																			<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate"  name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																			<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate"  name="txt_exten_date_pg_0" id="txt_exten_date_pg_0"></td>
																			<td align="center" yle="width:80px;"><input type="number" class="tboxsmclass" onKeyPress="return isPercentageValue(event,this);"  name="txt_part_amt_0" id="txt_part_amt_0"></td>
																			<td align="center" st><input type="button"  name="emp_add" id="emp_add"  value="ADD" class="btn btn-info" style="margin-top:0px;"></td>
																			<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																		</tr>
																		<input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new" style="width:110px;">
																	</table>      
																</div>
															</div>
														</div>
													
												<div class="row clearrow"></div>												
												<div class="div12" align="center">
													<div class="row">
														<div class="div12" align="center">
															<input type="button" class="btn btn-info" name="back" id="back" value="Back" onClick="goBack();"/>
															<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save And Confirm " />
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
	$("#cmb_engineer").chosen();
	$("#cmb_bidder").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	var KillEvent = 0;	
	$(document).ready(function(){ 
		$("body").on("click","#btn_save", function(event){
			if(KillEvent == 0){
			var ShortName   	= $("#cmb_shortname").val();
			var SDPErc	       = $("#txt_sd_per").val();
			var pgamt          =  $("#txt_sd_value").val();
			var pgamt2          =Math.round( pgamt ); 
			var totalamt       = $("#text_totalamt").val();   
			var rowCount      = $('#pgtable1 tr').length; 
			var pgamt1 =Number(pgamt2);// alert(pgamt);
			var totalamt1 = Number(totalamt); //alert(totalamt);
			if(ShortName == ""){
				BootstrapDialog.alert("Please select Name of Work..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(SDPErc == ""){
				BootstrapDialog.alert("Please Enter SD Percentage..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(rowCount <= 2 ) {
				BootstrapDialog.alert(" Please Add Atleast One BG/FDR/DD  Detail..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(totalamt1 < pgamt2){
					BootstrapDialog.alert(" Total BG/FDR/DD Amount  is not Equal to the SD Amount");
					event.preventDefault();
					event.returnValue = false;
				}else{
				   event.preventDefault();
				    BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save this SD  ?',
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

		$("body").on("change","#cmb_shortname", function(event){
			var Id = $(this).val();
			var MastId = $(this).val();// alert(SheetId);remove
			//$("#pgtable1").append('');
			$("#pgtable1 tr:gt(1)").remove();
			$("#text_totalamt").val('');
			$("#txt_order_num").val('');
			$("#txt_Wo_Cost").val('');
			$("#txt_sd_per").val('');
			$("#txt_bidder").val('');
			$("#txt_contid").val('');
			$("#txt_sd_value").val('');
			$("#cmd_purposes_0").val('');
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			// $("#txt_sno_pg_0").val('');
			$("#txt_date_pg_0").val('');
			$("#txt_expir_date_pg_0").val('');
			$("#txt_part_amt_0").val('');
			$("#txt_exten_date_pg_0").val(''); 
			$("#text_totalamt").val('');
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
				$.ajax({ 
					type: 'POST', 
					url: 'GetSDDetails.php', 
					data: { MastId: MastId}, 
					dataType: 'json',
					success: function (data) { 
						var Result1 = data['Result1']; 
						$.each(data, function(index, element) { 
									var Bgid	         = element.bfdid; 
									var LoIID            = element.master_id; 
									var Createdby        = element.createdby; 
									var Createdsess      = element.created_section; 
									var Createdon        = element.createdon; 
									var InstType 	     = element.inst_type;
									var InstNum 	     = element.inst_serial_no;
									var BankName   	     = element.inst_bank_name;
									var DateofIssue      = element.inst_date;
									var DateofExpiry     = element.inst_exp_date; 
									var DateofExtension  = element.inst_ext_date; 
									var AmtDetail	     = element.inst_amt; // alert(AmtDetail);
									var RowStr = '<tr><td><input type="hidden" name="txt_createdby[]" id="txt_createdby[]" readonly class="tboxsmclass" value="'+Createdby+'"><input type="hidden" name="txt_createdsess[]" id="txt_createdsess[]" readonly class="tboxsmclass" value="'+Createdsess+'"><input type="hidden" name="txt_createdon[]" id="txt_createdon[]" readonly class="tboxsmclass" value="'+Createdon+'"><input type="hidden" name="txt_LOIdid[]" id="txt_LOIdid[]" readonly class="tboxsmclass" value="'+LoIID+'"><input type="hidden" name="txt_Bfdid[]" id="txt_Bfdid[]" readonly class="tboxsmclass" value="'+Bgid+'"><input type="text" name="cmd_instype[]" readonly id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg[]" readonly class="tboxsmclass" value="'+BankName+'"></td><td><input type="text" readonly name="instrunum[]" id="instrunum[]" class="tboxsmclass" readonly  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg[]" class="tboxsmclass"  readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_exten_date_pg[]"  id="txt_exten_date_pg[]" class="tboxsmclass"  readonly value="'+DateofExtension+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt[]" class="tboxsmclass EmAmt" readonly   value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
									
									$("#pgtable1").append(RowStr);
									$("#txt_Bfdid_0").val('');
									$("#cmd_instype_0").val('');
									$("#instrunum_0").val('');
									$("#txt_bankname_0").val('');
									$("#txt_date_pg_0").val('');
									$("#txt_expir_date_pg_0").val('');
									$("#txt_part_amt_0").val('');
									TotalUnitAmountCalc();								
								});
							}
					});
				});
				
	
	
	$("body").on("click", "#emp_add", function(event){ 
		var CheckVal = 0;
		var InstType 	 = $("#cmd_instype_0").val();
		var BankName   	 = $("#txt_bankname_pg_0").val();
		var InstNum 	 = $("#instrunum_0").val();
		var DateofIssue  = $("#txt_date_pg_0").val();
		var DateofExpiry = $("#txt_expir_date_pg_0").val();
		var AmtDetail	 = $("#txt_part_amt_0").val(); 
		var DateofExtension = $("#txt_exten_date_pg_0").val();//alert(AmtDetail);
		var RowStr = '<tr><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg[]" class="tboxsmclass" value="'+BankName+'"></td></td><td><input type="text" readonly name="instrunum[]"  id="instrunum[]" class="tboxsmclass"  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass"  value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg[]" class="tboxsmclass"  value="'+DateofExpiry+'"></td><td><input type="text"  readonly name="txt_exten_date_pg_[]" id="txt_exten_date_pg_[]" class="tboxsmclass"  value="'+DateofExtension+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt[]" readonly class="tboxsmclass EmAmt"  value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete btn btn-info" name="emp_delete" id="emp_delete" value="DELETE" style="margin-top:0px;"></td></tr>'; 
		
		if((DateofIssue != "") && (DateofExpiry != "") ){  
			var d1 = DateofExpiry.split("/");
			var d2 = DateofIssue.split("/");
			var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
			var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
			if(emdexpdate<emddate){ 
				//var a="EMD Expiry date  should be greater than EMD  Date";
				//BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
				CheckVal = 1;
				//$("#txt_date_pg").val(''); 
				//$("#txt_expir_date_pg").val(''); 
			}else{
				var a="";
				CheckVal = 0;
				//$('#val_date').text(a);
			}
		}
		if(InstType == 0){
			alert("Instrument Type should not be empty");
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
		}else if(CheckVal ==  1){
			BootstrapDialog.alert("BG/FDR/DD Expiry date is lesser than BG/FDR/DD Date..Please Change..!!");
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
			$("#txt_exten_date_pg_0").val(''); 
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

	$('#txt_sd_per').change(function() {
		var SDper= $(this).val(); //alert(SDper);
		var Workvalue = $("#txt_Wo_Cost").val(); 
		$("#txt_sd_value").val('');
			var SDvalue= ((Number(SDper) / 100) *Number(Workvalue)).toFixed(2);
			//var Round = round((SDvalue),0); alert(Round);
			$("#txt_sd_value").val(SDvalue); 
	});

</script>





