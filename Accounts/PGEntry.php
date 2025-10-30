<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'PG Entry';
$msg = ''; $success = '';
$staffid  = $_SESSION['sid'];
$userId  = $_SESSION['userid'];
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
	$TenderNum 		= $_POST["txt_hid_tr_no"];
	//echo $TenderNum;exit;
	$Contractorid	= $_POST["txt_contid"];
	$Emdinstypestr	= $_POST["cmd_instype"];
	//$EmdPurstr   	= $_POST["cmd_purposes"];
	$Emdinstnumstr	= $_POST["instrunum"];
	$Emdbnamestr	= $_POST["txt_bankname_pg"];
	$EmdBanchstr   	= $_POST["txt_branch_pg"];
	$Emddatestr		= $_POST["txt_date_pg"];
	$Emdexdatestr	= $_POST["txt_expir_date_pg"]; 
	$Emdextensiondatestr = $_POST["txt_exten_date_pg"];
	$AmountListstr		= $_POST["txt_part_amt"];
	$LOIPGID	        = $_POST["txt_LOIdid"];
	$EmdCreatedbystr	= $_POST["txt_createdby"];
	$EmdCreatedSesstr	= $_POST["txt_createdsess"];
	$Emdcreatedonstr	= ($_POST['txt_createdon']);

	if($TenderNum == null){
		$msg = 'Error : Tender Number should not be empty..!!!';
	}else if($Emdinstnumstr == null ){
		$msg = 'Error : Please Add Atleast One Type';
	}else if(count($Emdinstnumstr) <= 0 ){
		$msg = 'Error : Please Add Atleast One Type';
	}else if(count($AmountListstr) <= 0 ){
		$msg = 'Error : Please Enter amount';
	}else{
		$InQueryCon = 1;
	}

	$GlobID= '';
	$SelectTSQuery = "SELECT globid FROM tender_register where tr_id = '$TenderNum'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$GlobID = $CList->globid;
		}
	}
	$LOiID= '';
	$SelectTSQuery = "SELECT loa_pg_id FROM loi_entry where tr_id = '$TenderNum'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$LOiID = $CList->loa_pg_id;
		}
	}
	if($LOIPGID != null){ 
		$Deletequery   = "DELETE FROM bg_fdr_details WHERE globid='$GlobID' AND  master_id='$LOiID'";
		$BFDeletequery	= mysqli_query($dbConn,$Deletequery);	
		foreach($Emdinstnumstr as $Key => $Value){
			$Emdinstype    	= $Emdinstypestr[$Key];
			$Emdinstnum    	= $Emdinstnumstr[$Key];
			$Emdbname      	= $Emdbnamestr[$Key];
			$EmdBanch      	= $EmdBanchstr[$Key];
			$Emddate       	= $Emddatestr[$Key];
			$Emdexdate     	= $Emdexdatestr[$Key];
			$Emdextendate    = $Emdextensiondatestr[$Key];
			$AmountList      = $AmountListstr[$Key];
			$Createdby     	= $EmdCreatedbystr[$Key];
			$CreatedSes    	= $EmdCreatedSesstr[$Key];
			$Createdon     	= $Emdcreatedonstr[$Key];
			$TrimBankname 	= trim($Emdbname);
			$TrimBranc 	    = trim($EmdBanch);
			$TrimAmount      = trim($AmountList);
			$Insertdate      = dt_format($Emddate);
			$InsertExpdate 	 = dt_format($Emdexdate);
			$Insertextendate = dt_format($Emdextendate);
			//echo $Insertextendate;exit;

			$InsertCreatedon 	= dt_format($Createdon);
			if($InQueryCon == 1){
				$insert_query3	= "INSERT INTO bg_fdr_details SET master_id='$LOiID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG', 
										inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_branch_name='$TrimBranc',  inst_bank_name='$TrimBankname', inst_date='$Insertdate', 
										inst_exp_date='$InsertExpdate', inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid',
										inst_status='ACC', approved_by='$staffid', approved_session='ACC', approvedon = NOW(), createdby='$staffid',  
										created_section='ACC',  createdon= NOW(), active='1'";
										//echo $insert_query3;exit;
				$Loidetailinsert_query	= mysqli_query($dbConn,$insert_query3);	
		   }
	   	}
		if($Loidetailinsert_query == true){
			$msg = "PG Details Updated Successfully ";
			$success = 1;
		}else{
			$msg = " PG Details Details Not Updated. Error...!!! ";
			$success = 0;
		}
	}else{
		if($Emdinstnumstr != null){
			foreach($Emdinstnumstr as $Key => $Value){
				//	$EmdPur     = $EmdPurstr[$Key];
				$Emdinstype    = $Emdinstypestr[$Key];
				$Emdinstnum    = $Emdinstnumstr[$Key];
				$Emdbname      = $Emdbnamestr[$Key];
				$EmdBanch      	= $EmdBanchstr[$Key];
				$Emddate       = $Emddatestr[$Key];
				$Emdexdate     = $Emdexdatestr[$Key];
				$Emdextendate  = $Emdextensiondatestr[$Key];
				$AmountList    = $AmountListstr[$Key];
				$TrimBankname 	= trim($Emdbname);
				$TrimBranc 	    = trim($EmdBanch);
				$TrimAmount 	= trim($AmountList);
				$Insertdate 	= dt_format($Emddate);
				$InsertExpdate = dt_format($Emdexdate);
				$Insertextendate = dt_format($Emdextendate);
				if($InQueryCon == 1){
					$insert_query1	= "INSERT INTO bg_fdr_details SET master_id='$LOiID',globid='$GlobID', contid='$Contractorid', inst_purpose='PG',
					inst_type='$Emdinstype',inst_serial_no='$Emdinstnum', inst_branch_name='$TrimBranc',inst_date='$Insertdate', inst_bank_name='$TrimBankname', inst_exp_date='$InsertExpdate', 
					inst_ext_date='$Insertextendate', inst_amt='$TrimAmount', userid='$userid', inst_status='ACC', approved_by='$staffid', 
					approved_session='ACC', approvedon = NOW(), createdby='$staffid', created_section='ACC', createdon= NOW(), active='1'";
					$insert_sql1 = mysqli_query($dbConn,$insert_query1);
				}
			}
			if($insert_sql1 == true){
				$msg = "PG Details Saved Successfully ";
				$success = 1;
			}else{
				$msg = " PG Details Details Not Saved. Error...!!! ";
				$success = 0;
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
												<div class="card-header inkblue-card" align="center">Performance Guarantee (BG/FDR/DD ) Entry</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																<div class="row clearrow"></div>
																<div class="row"> 		
																   <div class="div3 lboxlabel">
																		CCNo.
																   </div>
																   <!-- <div class="div7 lboxlabel">
																		<select id="cmb_tnder_no" name="cmb_tnder_no" class="tboxsmclass">
																			<option value="">--------------- Select --------------- </option>
																			<?php //echo $objBind->BindPGTrNo('');?>
																	   </select>
																   </div> -->
																	<div class="div2"> 
																		<input type="number" name='txt_ccno' id='txt_ccno' class="tboxsmclass" value="" onKeyPress="return isIntegerValueWithLimit(event,this,5);">
																	</div>
																	<div class="div1" style="vertical-align:middle;" align="center"> 
																		<input type="button" name='btn_ccno_go' id='btn_ccno_go' class="buttonstyle" value="GO">
																	</div>
															   </div>
															   <div class="row clearrow"></div>
															   <div class="row">
																	<div class="div3 lboxlabel">
																		Name of Work
																	</div>
																	<div class="div8">
																		<textarea name='txt_work_name' id='txt_work_name' class="tboxsmclass gochangeval" readonly=""></textarea>
																	</div>
															   </div>
															   <div class="row clearrow"></div>
															   <div class="row">
																   <div class="div3 lboxlabel">
																	  	Contractor Name
																   </div>
																   <div class="div8">
																      <input type="text" name='txt_bidder' id='txt_bidder' readonly class="tboxsmclass gochangeval" value="">
																		<input type="hidden" name='txt_contid' id='txt_contid' readonly class="tboxsmclass gochangeval" value="">
																   </div>
															   </div>
															   <div class="row clearrow"></div>
															   <div class="row">
																	<div class="div3 lboxlabel" >LOI No.</div>
																	<div class="div3 lboxlabel" align="left">
																		<input type="text" name="txt_loi_no" id="txt_loi_no" readonly class="tboxsmclass">
																	</div>
																	<div class="div2 lboxlabel"> &emsp;&emsp;&emsp;LOI Date</div>
																	<div class="div3 lboxlabel" align="left">
																		<input type="text" name="txt_loi_date" id="txt_loi_date" readonly class="tboxsmclass">
																		<input type="hidden" name="txt_hid_tr_no" id="txt_hid_tr_no" readonly class="tboxsmclass">
																		<input type="hidden" name="txt_LOIdid" id="txt_LOIdid" readonly class="tboxsmclass" value="">
																	</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div3 lboxlabel">PG %</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_pg_per" id="txt_pg_per" readonly class="tboxsmclass gochangeval">
																		</div>
																		<div class="div2 lboxlabel"> &emsp;&emsp;&emsp;PG Value</div>
																		<div class="div3" align="left">
																			<input type="text" name="txt_pg_value" id="txt_pg_value" readonly class="tboxsmclass gochangeval">
																		</div>
																		<div class="row clearrow isappcheck" style="display-none"></div>
																											
																			<!--    2nd Div Starts Here   -->
																		<div class="face-static">
																			<div class="card-header inkblue-card" align="left">&nbsp;BG/FDR/DD Details</div>
																			<div class="card-body padding-1">
																				<div class="row clearrow"></div>		
																				<table class="dataTable etable " align="center" width="100%" id="pgtable1">
																					<tr class="label" style="background-color:#FFF">
																						<!-- <td align="center" >Purpose</td> -->
																						<th align="center">Instrument <br>Type</th>
																						<th align="center">Bank Name</th>
																						<th align="center">Branch Address</th>
																						<th align="center">BG/FDR Serial No.</th>
																						<th align="center">BG/FDR Date</th>
																						<th align="center">Expiry Date</th>
																						<th align="center">Extension Date</th>
																						<th align="center">Amount ( &#8377; )</th>
																						<th align="center">Action</th>
																					</tr>
																					<tr>
																						<!-- <td align="center">
																							<input type=text name="cmd_purposes_0" id ="cmd_purposes_0"  class="tboxsmclass gochangeval" value="PG"></input>
																						</td> -->
																						<td align="center" style="width:50px;">
																							<select name="cmd_instype_0" id ="cmd_instype_0" class="tboxsmclass gochangeval">  
																								<option value="">-Select- </option>
																								<option value="BG">BG</option>
																								<option value="FDR">FDR</option>
																								<option value="DD">DD</option>
																							</select>
																						</td>
																						<td align="center" style="width:300px;"><input type="text" class="tboxsmclass gochangeval"  name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																						<td align="center" style="width:150px;"><input type="text" class="tboxsmclass"  name="txt_branch_pg_0" id="txt_branch_pg_0"></td>
																						<td align="center" style="width:300px;">
																							<input type="text" name="instrunum_0" id ="instrunum_0" class="tboxsmclass gochangeval">
																						</td>
																						<td align="center" style="width:100px;" ><input type="text" placeholder="DD/MM/YYYY"  class="tboxsmclass date  EmdDt gochangeval" name="txt_date_pg_0" id="txt_date_pg_0" readonly=""></td>
																						<td align="center" style="width:100px;"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate ExpDt ValDate gochangeval"  name="txt_expir_date_pg_0" id="txt_expir_date_pg_0" readonly=""></td>
																						<td align="center" style="width:100px;"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass expdate Extndt ExvaDate gochangeval"  name="txt_exten_date_pg_0" id="txt_exten_date_pg_0" readonly=""></td>
																						<td align="center"  style="width:90px;"><input type="number" class="tboxsmclass gochangeval" name="txt_part_amt_0" onKeyPress="return isPercentageValue(event,this);" id="txt_part_amt_0"></td>
																						<td align="center"><input type="button" name="emp_add" id="emp_add" value="ADD" class="fa btn btn-info"></td>
																						<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
																					</tr>
																							<input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new gochangeval" style="width:110px;">
																				</table>
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
																<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save And Confirm" />
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
	function goBack(){
		url = "PGRegister.php";
		window.location.replace(url);
	}
	$("#cmb_tnder_no").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	 $("body").on("change", ".ValDDDate", function(event){ 
			var DateDDIndex = $(this).attr("data-DDindex"); 
			var DateofIssue  = $("#txt_date_pg_DD_"+DateDDIndex).val(); 
			var DateofExpiry = $("#txt_expir_date_pg_DD_"+DateDDIndex).val(); //alert(DateofExpiry);
			if((DateofIssue != "") && (DateofExpiry != "") ){  
				var d1 = DateofExpiry.split("/");
				var d2 = DateofIssue.split("/");
				var emdexpdate = new Date(d1[2], d1[1]-1, d1[0]); //alert(emdexpdate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(emdexpdate<emddate){
					var a="DD Expiry date should be greater than DD Date";
					BootstrapDialog.alert(a);
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
					//CheckVal = 1;
				}
			}
		});
		$("body").on("change", ".chdatval", function(event){ 
			var DateDDIndex = $(this).attr("data-DDindex"); //alert(DateDDIndex);
			var DateofIssue  = $("#txt_date_pg_DD_"+DateDDIndex).val(); 
			var DateofCheallan= $("#txt_challandate_pg_DD_"+DateDDIndex).val();
			if((DateofIssue != "") && (DateofCheallan != "") ){  
				var d1 = DateofCheallan.split("/");
				var d2 = DateofIssue.split("/");
				var challandate = new Date(d1[2], d1[1]-1, d1[0]); //alert(challandate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(challandate<emddate){
					var a="DD Challan date should be greater than  Date";
					BootstrapDialog.alert(a);
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
					//CheckVal = 1;
				}
			}
		});
		$("body").on("change", ".readatval", function(event){ 
			var DateDDIndex = $(this).attr("data-DDindex"); //alert(DateDDIndex);
			var DateofIssue  = $("#txt_challandate_pg_DD_"+DateDDIndex).val();  //alert(DateofIssue);
			var DateofCheallan= $("#txt_Challanrealdate_pg_DD_"+DateDDIndex).val(); //alert(DateofCheallan);
			if((DateofIssue != "") && (DateofCheallan != "") ){  
				var d1 = DateofCheallan.split("/");
				var d2 = DateofIssue.split("/");
				var challandate = new Date(d1[2], d1[1]-1, d1[0]); //alert(challandate);
				var emddate = new Date(d2[2], d2[1]-1, d2[0]); //alert(emddate);
				if(challandate<emddate){
					var a="DD Realisation date should be greater than or equal DD Challan Date";
					BootstrapDialog.alert(a);
					$(this).val('');
					event.preventDefault();
					event.returnValue = false;
					//CheckVal = 1;
				}
			}
		});
	var KillEvent = 0;	
	$(document).ready(function(){ 
		$("body").on("click","#btn_ccno_go", function(event){
			var CCnoVal	= $("#txt_ccno").val();
			var Id 		= $(this).val();
			$(".gochangeval").val('');
			$("#pgtable1").find("tr:gt(1)").remove();
			if(CCnoVal != ""){
				$.ajax({
					type: 'POST',
					url: 'FindBiddersNamePG.php',
					data: { CCnoVal: CCnoVal },
					dataType: 'json',
					success: function (data){
						if(data != null){
							//var Totalamt = data.Totalamt;
							var TenderId	= data['TenderID'];
							var ContId 		= data['contid'];
							var ContName 	= data['name_contractor'];
							var  LoaID 	    = data['master_id']; //alert(LoaID);
							var LoaNumber 	= data['loa_no'];
							var LoaDate 	= data['loa_dt'];
							var PgPer 		= data['pg_per'];
							var PgAmt 		= data['pg_amt'];
							var WorkName 	= data['WorkName'];
							var BankData 	= data['BankData'];
							var ApprovStat = data['ApprovStat'];
							if(ApprovStat == 1){
								$("#btn_save").hide();
							}else{
								$("#btn_save").show();
							}
							$("#txt_hid_tr_no").val(TenderId);
							$("#txt_LOIdid").val(LoaID);
							$("#txt_contid").val(ContId);
							$("#txt_bidder").val(ContName);
							$("#txt_loi_no").val(LoaNumber);
							$("#txt_loi_date").val(LoaDate);
							$("#txt_pg_per").val(PgPer);
							$("#txt_work_name").val(WorkName);
							$("#txt_pg_value").val(PgAmt);
							$.each(BankData, function(index, element) { //alert(element);
								var Bgid	        = element.bfdid; 
								var LoIID           = element.master_id; 
								var Createdby       = element.createdby; 
								var Createdsess     = element.created_section; 
								var Createdon       = element.createdon; 
								var InstType 	     = element.inst_type;
								var InstNum 	     = element.inst_serial_no;
								var BankName   	     = element.inst_bank_name;
								var BranchName   	  = element.inst_branch_name;
								var DateofIssue     = element.inst_date;
								var DateofExpiry    = element.inst_exp_date; 
								var DateofExtension = element.inst_ext_date; 
								var AmtDetail	     = element.inst_amt; 
								var RowStr = '<tr><td><input type="hidden" name="txt_createdby[]" id="txt_createdby[]" readonly class="tboxsmclass" value="'+Createdby+'"><input type="hidden" name="txt_createdsess[]" id="txt_createdsess[]" readonly class="tboxsmclass" value="'+Createdsess+'"><input type="hidden" name="txt_createdon[]" id="txt_createdon[]" readonly class="tboxsmclass" value="'+Createdon+'"><input type="hidden" name="txt_Bfdid[]" id="txt_Bfdid[]" readonly class="tboxsmclass" value="'+Bgid+'"><input type="text" name="cmd_instype[]" readonly id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg[]" readonly class="tboxsmclass" value="'+BankName+'"></td><td><input type="text" name="txt_branch_pg[]" id="txt_branch_pg[]" readonly class="tboxsmclass" value="'+BranchName+'"></td><td><input type="text" readonly name="instrunum[]" id="instrunum[]" class="tboxsmclass" readonly  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg[]" class="tboxsmclass"  readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_exten_date_pg[]"  id="txt_exten_date_pg[]" class="tboxsmclass" readonly value="'+DateofExtension+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt[]" class="tboxsmclass EmAmt" readonly   value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
								$("#pgtable1").append(RowStr);
								$("#txt_Bfdid_0").val('');
								$("#cmd_instype_0").val('');
								$("#instrunum_0").val('');
								$("#txt_bankname_0").val('');
								$("#txt_date_pg_0").val('');
								$("#txt_expir_date_pg_0").val('');
								$("#txt_exten_date_pg_0").val('');
								$("#txt_part_amt_0").val('');
								//TotalUnitAmountCalc();								
							});
						}
					}
				});
			}
			/*	$.ajax({
				type: 'POST',
				url: 'GetPGDetail.php',
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
						var RowStr = '<tr><td><input type="hidden" name="txt_createdby[]" id="txt_createdby[]" readonly class="tboxsmclass" value="'+Createdby+'"><input type="hidden" name="txt_createdsess[]" id="txt_createdsess[]" readonly class="tboxsmclass" value="'+Createdsess+'"><input type="hidden" name="txt_createdon[]" id="txt_createdon[]" readonly class="tboxsmclass" value="'+Createdon+'"><input type="hidden" name="txt_LOIdid[]" id="txt_LOIdid[]" readonly class="tboxsmclass" value="'+LoIID+'"><input type="hidden" name="txt_Bfdid[]" id="txt_Bfdid[]" readonly class="tboxsmclass" value="'+Bgid+'"><input type="text" name="cmd_instype[]" readonly id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg[]" readonly class="tboxsmclass" value="'+BankName+'"></td><td><input type="text" readonly name="instrunum[]" id="instrunum[]" class="tboxsmclass" readonly  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg[]" class="tboxsmclass"  readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg[]" class="tboxsmclass"  readonly value="'+DateofExtension+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt[]" class="tboxsmclass EmAmt" readonly   value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
						
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
			}); */
		});
	});

	$("body").on("click", "#emp_add", function(event){ 
		var CheckVal = 0;
		var Purpose 	        = $("#cmd_purposes_0").val();
		var InstType 	        = $("#cmd_instype_0").val();
		var BankName            = $("#txt_bankname_pg_0").val(); 
		var BranchName          = $("#txt_branch_pg_0").val();
		var InstNum 	        = $("#instrunum_0").val();
		var DateofIssue         = $("#txt_date_pg_0").val();
		var DateofExpiry 	    = $("#txt_expir_date_pg_0").val();
		var DateofExtension     = $("#txt_exten_date_pg_0").val();
		var AmtDetail	 	    = $("#txt_part_amt_0").val();//alert(AmtDetail);
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
		var RowStr = '<tr><td><input type="text" name="cmd_instype[]" readonly  id="cmd_instype[]" class="tboxsmclass"  value="'+InstType+'"></td><td><input type="text" readonly name="txt_bankname_pg[]" id="txt_bankname_pg[]" class="tboxsmclass"  value="'+BankName+'"></td><td><input type="text" readonly name="txt_branch_pg[]" id="txt_branch_pg"   class="tboxsmclass"  value="'+BranchName+'"></td><td><input type="text" readonly name="instrunum[]"  id="instrunum[]" class="tboxsmclass"  value="'+InstNum+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg[]" class="tboxsmclass"  value="'+DateofIssue+'"></td><td><input type="text"  readonly name="txt_expir_date_pg[]" id="txt_expir_date_pg[]" class="tboxsmclass"  value="'+DateofExpiry+'"></td><td><input type="text"  readonly name="txt_exten_date_pg_[]" id="txt_exten_date_pg_[]" class="tboxsmclass"  value="'+DateofExtension+'"></td><td><input type="number" name="txt_part_amt[]" id="txt_part_amt[]" readonly class="tboxsmclass EmAmt" width:200px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
		if(InstType == 0){
			BootstrapDialog.alert("Please Select Instrument type..!!");
			return false;
		}else if(InstNum == 0){
			BootstrapDialog.alert("Instrument Number should not be empty");
			return false;
		}else if(BankName == 0){
			BootstrapDialog.alert("Bank Name should not be empty");
			return false;
		}else if(DateofIssue == 0){
			BootstrapDialog.alert("Date of Issue should not be empty");
			return false;
		}else if(DateofExpiry == 0){
			BootstrapDialog.alert("Date of Expiry should not be empty");
			return false;
		}else if(AmtDetail == 0){
			BootstrapDialog.alert("Amount should not be empty");
			return false;
		}else if(CheckVal ==  1){
			BootstrapDialog.alert("BG/FDR/DD Expiry date is lesser than BG/FDR/DD Date..Please Change..!!");
			return false;
		}else{
			$("#pgtable1").append(RowStr);
			$("#cmd_instype_0").val('');
			$("#instrunum_0").val('');
			$("#txt_bankname_pg_0").val('');
			$("#txt_branch_pg_0").val(''); 
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
		//TotalUnitAmountCalc();
		$("#text_totalamt").val('');
	});
	/*function TotalUnitAmountCalc(){
		var TotalAmt = 0;
		$(".EmAmt").each(function(){
			var Amt = $(this).val(); 
			TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
			$("#text_totalamt").val(TotalAmt);
		
		});
	}*/
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
		minDate: "+1D",
	});
	$( ".chdate" ).datepicker({  
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		yearRange: "2000:+25",
		defaultDate: new Date,
	});

	$("body").on("click","#btn_save", function(event){
	    var TotalAmt =0;
		var TotalEmdAmt =0;
		var TotalDDAmt =0;
		$(".EmAmt").each(function(){
			var Amt = $(this).val();  //alert(Amt);
				TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt); //alert(TotalAmt);
		});
		// $(".DDAmt").each(function(){
		// 	var DDAmt = $(this).val(); 
		// 	TotalDDAmt = parseFloat(TotalAmt) + parseFloat(DDAmt); alert(TotalDDAmt);
		// });
		// TotalAmt  =TotalEmdAmt + TotalDDAmt; alert(TotalAmt);
		$("#text_totalamt").val(TotalAmt); 
		if(KillEvent == 0){
			//var ShortName 	= $("#cmb_tnder_no").val();	alert(ShortName);
			var TrNum	 	= $("#txt_hid_tr_no").val();	//alert(TrNum);
			var ShortName 	= $("#txt_ccno").val();			//alert(ShortName);
			var WorkName 	= $("#txt_work_name").val();	//alert(WorkName);
			var LoINum	 	= $("#txt_loi_no").val();		//alert(LoINum);
			var LoIDate 	= $("#txt_loi_date").val();	//alert(LoIDate);
			var rowCount	= $('#pgtable1 tr').length;  	//alert(rowCount);
			var pgamt  		= $("#txt_pg_value").val(); 	//alert(pgamt);
			var totalamt	= $("#text_totalamt").val();  //alert(totalamt);
			var pgamt1    	= Number(pgamt); 					//alert(pgamt1);
			var totalamt1 	= Number(totalamt); 				//alert(totalamt1);
			if(ShortName == ""){
				BootstrapDialog.alert("Please Enter CCNO.");
				event.preventDefault();
				event.returnValue = false;
			}else if(rowCount <= 1) {
				BootstrapDialog.alert("Please Add Atleast One BG/FDR/DD Detail..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(totalamt1 < pgamt1){
				BootstrapDialog.alert("Total BG/FDR/DD Amount is not Equal to the PG Amount");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				$.ajax({ 
					type: 'POST', 
					url: 'GetEMDReturndetails.php', 
					data: { TrNum: TrNum }, 
					dataType: 'json',
					success: function (data) {
						var Result1 = data['row1'];
						var Result2 = data['row2'];
						var RowSpanContArr = data['row3'];
						if(Result2 == null){
							event.preventDefault();
							BootstrapDialog.confirm({
								title: 'Confirmation Message',
								message: 'Are you sure want to save this PG Details ?',
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
						}else{
							var PrevContId = "";						
							var BankStr = "<table class='dataTable etable' align='center' width='200%' id='emdtable1'>";
							    // BankStr += "<tr><<th> class='label' style ='background-color:#FFF'></tr><</th> ";
								BankStr += "<tr class='label' style ='background-color:#FFF'>";
								BankStr += "<th align='center'>Bidder's Name</th>";
								BankStr += "<th align='center'>Instrument<br> Type</th>";
								BankStr += "<th align='center'>Instrument <br>Number</th>";
								BankStr += "<th align='center'> Bank Name</th>";
								BankStr += "<th align='center'>Branch</th>";
								BankStr += "<th align='center'>Date of <br>Issue</th>";
								BankStr += "<th align='center'>Expiry <br> Date</th>";
								BankStr += "<th align='center'>Amount<br> ( &#8377; )</th>";
								BankStr += "<th align='center'>Status</th>";
								var x2 = 0;
							
							if(data != null){ 
								$.each(Result2, function(index, element) {
									var ContID = element.contid;
									var contname = element.name_contractor;
									var RowSpan  = RowSpanContArr[ContID];
									if(PrevContId != ContID){
										x2 = 0;
									}
									if(x2 == 0){
										//alert (RowSpan);
										BankStr += "<tr>";
										BankStr +="<td align='left'  rowspan='"+RowSpan+"'>"+contname+"</td>";
										BankStr +="<td align='left'>"+element.inst_type+"</td>";
										BankStr +="<td align='left'>"+element.inst_no+"</td>";
										BankStr +="<td align='left'>"+element.bank_name+"</td>";
										BankStr +="<td align='left'>"+element.branch_addr+"</td>";
										BankStr +="<td align='left'>"+element.issue_dt+"</td>";
										BankStr +="<td align='left'>"+element.valid_dt+"</td>";
										BankStr +="<td align='left'>"+element.emd_amt+"</td>";
										BankStr +="<td align='left' rowspan='"+RowSpan+"'>Not Returned</td>";
										x2++;
									}else{
										BankStr += "<tr>";
										BankStr +="<td align='left'>"+element.inst_type+"</td>";
										BankStr +="<td align='left'>"+element.inst_no+"</td>";
										BankStr +="<td align='left'>"+element.bank_name+"</td>";
										BankStr +="<td align='left'>"+element.branch_addr+"</td>";
										BankStr +="<td align='left'>"+element.issue_dt+"</td>";
										BankStr +="<td align='left'>"+element.valid_dt+"</td>";
										BankStr +="<td align='left'>"+element.emd_amt+"</td>";
										x2++;
									}
									PrevContId = ContID; 
								});
								BankStr += "</table>";
								BootstrapDialog.confirm({
									title: 'EMD Not Retuned Details',
									message: BankStr,
									closable: false, // <-- Default value is false
									draggable: false, // <-- Default value is false
									btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
									btnOKLabel: 'Ok',
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
					}
				});
			}
		}
	});
</script>


