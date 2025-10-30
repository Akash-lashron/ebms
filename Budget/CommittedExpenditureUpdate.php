<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Committed Expenditure Plan';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
//echo $staffid;
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
$CurrYear = "2022";
$NextYear = "2023";

if(isset($_POST['btn_save']) == " SAVE "){

	$EditGlobId     = $_POST['cmb_work'];
	//echo $EditGlobId;exit;
	// $SelectSheetQuery = "Select sheet_id from sheet where globid = '$EditGlobId'";
	// $SelectSheetSql = mysqli_query($dbConn,$SelectSheetQuery);
	// if($SelectSheetSql == true){
	// 	if(mysqli_num_rows($SelectSheetSql)>0){
	// 		$EditList 		= mysqli_fetch_object($SelectSheetSql);
	// 		$EditCSheetid 	= $EditList->sheetid;
	// 	}
	// }
	//$EditPinId     	= $_POST['txt_pinid_modal']; 
	//echo $EditPinId;exit;
	
	$FinaYears 		= $_POST['cmb_fin_year'];
	$ExpFinaYears 	= explode("-",$FinaYears);
	$StartYr 		= $ExpFinaYears[0];
	$EndYr 			= $ExpFinaYears[1];
	$FinaFDate 		= $StartYr."-04-01";
	$FinaTDate 		= $EndYr."-03-31";
	//$EditRemarks    = $_POST['txt_remarks_modal'];
	$EditApr = $_POST['Q1CE1']; $EditMay = $_POST['Q1CE2']; $EditJun = $_POST['Q1CE3']; 
	$EditJul = $_POST['Q2CE1']; $EditAug = $_POST['Q2CE2']; $EditSep = $_POST['Q2CE3']; 
	$EditOct = $_POST['Q3CE1']; $EditNov = $_POST['Q3CE2']; $EditDec = $_POST['Q3CE3']; 
	$EditJan = $_POST['Q4CE1']; $EditFeb = $_POST['Q4CE2']; $EditMar = $_POST['Q4CE3'];

	$DeleteQuery = "delete from budget_expenditure where globid = '$EditGlobId' and fin_year = '$FinaYears'";
	$DeleteSql = mysqli_query($dbConn,$DeleteQuery);
	$SelectQuery = "Select * from works where globid = '$EditGlobId'";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$EditList 		= mysqli_fetch_object($SelectSql);
			$EditSheetid 	= $EditList->sheetid;
			$EditCcno 		= $EditList->ccno;
			$EditPinid 		= $EditList->pinid;
		}
	}
	//echo $EditSheetid;exit;
	$InsertQuery = "insert into budget_expenditure set globid='$EditGlobId', pinid='$EditPinid', sheetid='$EditSheetid', cc_no='$EditCcno', exp_type = 'CE', 
	april='$EditApr', may='$EditMay', june='$EditJun', july='$EditJul', aug='$EditAug', sep='$EditSep', oct='$EditOct', nov='$EditNov', dece='$EditDec', 
	jan='$EditJan', feb='$EditFeb', march='$EditMar', next_fin_yr_amt = '$nextfinyear', remarks = '$EditRemarks', fin_year='$FinaYears', 
	active=1, createddate = NOW(), userid = '".$_SESSION['sid']."' ";
	$InsertSql = mysqli_query($dbConn,$InsertQuery);
	if($InsertSql == true){
		$msg = "Budget Expenditure Updated Successfully";
		$success = 1;
	}else{
		$msg = "Not Updated. Please Try Again.";
	}
}

if(isset($_GET['id'])){   
	$GLId 	= $_GET['id'];
	$FYear	= $_GET['fyr'];
	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$BudExpQuery = "SELECT * FROM budget_expenditure WHERE globid = '$GLId' AND fin_year = '$FYear'";
	$BudExpQuerySql 	= mysqli_query($dbConn,$BudExpQuery);
	if($BudExpQuerySql == true){
		if(mysqli_num_rows($BudExpQuerySql)>0){
			$BudList = mysqli_fetch_object($BudExpQuerySql);
			$GlobIdVal	= $BudList->globid;
			$SHIdVal 	= $BudList->sheetid;
			$FinYearVal	= $BudList->fin_year;
			$CCnoVal 	= $BudList->cc_no;

			$aprilVal	= $BudList->april;
			$mayVal 		= $BudList->may;
			$juneVal 	= $BudList->june;
			$Q1Total		= $aprilVal+$mayVal+$juneVal;
			$julyVal 	= $BudList->july;
			$augVal 		= $BudList->aug;
			$sepVal 		= $BudList->sep;
			$Q2Total		= $julyVal+$augVal+$sepVal;
			$octVal 		= $BudList->oct;
			$novVal 		= $BudList->nov;
			$deceVal 	= $BudList->dece;
			$Q3Total		= $octVal+$novVal+$deceVal;
			$janVal 		= $BudList->jan;
			$febVal 		= $BudList->feb;
			$marchVal	= $BudList->march;
			$Q4Total		= $janVal+$febVal+$marchVal;
		}
	}
	$Degignation ='';
	$SelectQuery 	= "select staff.*, designation.designationname from staff JOIN designation ON staff.designationid = designation.designationid where staff.staffid = '$EICid'";
	$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$List = mysqli_fetch_object($SelectSql);
			$Desgnation = $List->designationname;
			$Email = $List->email;
		}
	}
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
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
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Committed Expenditure Plan</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="well well-sm" style="background:#fff">
															<div class="row">
																<div class="div2 lboxlabel">
																	Name of Work :
																</div> 
																<div class="div4">
																<select name="cmb_work" id="cmb_work" class="form-control tboxsmclass Eplan">
																	<option value="">--------------- Select ---------------</option>
																	 <?php echo $objBind->BindLiveWorks($GlobIdVal,$staffid ); ?>
																</select>
																</div>
																<div class="div2 cboxlabel">
																	Financial Year :
																</div> 
																<div class="div4">
																<select name="cmb_fin_year" id="cmb_fin_year" class="form-control tboxsmclass Eplan">
																	<option value="">--------------- Select ---------------</option>
																	 <?php echo $objBind->BindFinancialYear($FinYearVal); ?>
																</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="div6" align="center">
																<div class="innerdiv2">
																	<div class="row divhead" align="center">Q1</div>
																	<div class="row innerdiv" align="center">
																		<table border="1" class="formTable" id="fixTable">
																			<thead>
																				<tr class="">
																					<th id="APR" nowrap="nowrap" class="cboxlabel">APR<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="MAY" nowrap="nowrap" class="cboxlabel">MAY<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="JUNE" nowrap="nowrap" class="cboxlabel">JUNE<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="JUNE" nowrap="nowrap" class="cboxlabel">TOTAL<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																				</tr>
																				<tr>
																					<td><input type="text" class="tboxsmclass Qtr Q1CE1 Q1Calc" id="Q1CE1" name="Q1CE1" value="<?php if(isset($aprilVal)){ echo $aprilVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q1CE2 Q1Calc" id="Q1CE2" name="Q1CE2" value="<?php if(isset($mayVal)){ echo $mayVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q1CE3 Q1Calc" id="Q1CE3" name="Q1CE3" value="<?php if(isset($juneVal)){ echo $juneVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass TQtr Q1CET" id="Q1CET" name="Q1CET" value="<?php if(isset($Q1Total)){ echo $Q1Total; } ?>" readonly=""></td>
																				</tr>
																			</thead>
																			<tbody>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
															<div class="div6" align="center">
																<div class="innerdiv2">
																	<div class="row divhead" align="center">Q2</div>
																	<div class="row innerdiv" align="center">
																		<table border="1" class="formTable" id="fixTable">
																			<thead>
																				<tr class="sticky-header">
																					<th id="JULY" nowrap="nowrap" class="cboxlabel">JULY<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="AUG" nowrap="nowrap" class="cboxlabel">AUG<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="SEP" nowrap="nowrap" class="cboxlabel">SEP<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="JUNE" nowrap="nowrap" class="cboxlabel">TOTAL<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																				</tr>
																				<tr >
																					<td><input type="text" class="tboxsmclass Qtr Q2CE1 Q2Calc" id="Q2CE1" name="Q2CE1" value="<?php if(isset($julyVal)){ echo $julyVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q2CE2 Q2Calc" id="Q2CE2" name="Q2CE2" value="<?php if(isset($augVal)){ echo $augVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q2CE3 Q2Calc" id="Q2CE3" name="Q2CE3" value="<?php if(isset($sepVal)){ echo $sepVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass TQtr Q2CET" id="Q2CET" name="Q2CET" value="<?php if(isset($Q2Total)){ echo $Q2Total; } ?>" readonly=""></td>
																				</tr>
																			</thead>
																			<tbody>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
															<div class="div6" align="center">
																<div class="innerdiv2">
																	<div class="row divhead" align="center">Q3</div>
																	<div class="row innerdiv" align="center">
																		<table border="1" class="formTable" id="fixTable">
																			<thead>
																				<tr class="sticky-header">
																					<th id="OCT" nowrap="nowrap" class="cboxlabel">OCT<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="NOV" nowrap="nowrap" class="cboxlabel">NOV<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="DEC" nowrap="nowrap" class="cboxlabel">DEC<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="JUNE" nowrap="nowrap" class="cboxlabel">TOTAL<span class="I"></span><br/>(&#8377 in Lakhs)</th>
																				</tr>
																				<tr>
																					<td><input type="text" class="tboxsmclass Qtr Q3CE1 Q3Calc" id="Q3CE1" name="Q3CE1" value="<?php if(isset($octVal)){ echo $octVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q3CE2 Q3Calc" id="Q3CE2" name="Q3CE2" value="<?php if(isset($novVal)){ echo $novVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q3CE3 Q3Calc" id="Q3CE3" name="Q3CE3" value="<?php if(isset($deceVal)){ echo $deceVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass TQtr Q3CET" id="Q3CET" name="Q3CET" value="<?php if(isset($Q3Total)){ echo $Q3Total; } ?>" readonly=""></td>
																				</tr>
																			</thead>
																			<tbody>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
															<div class="div6" align="center">
																<div class="innerdiv2">
																	<div class="row divhead" align="center">Q4</div>
																	<div class="row innerdiv" align="center">
																		<table border="1" class="formTable" id="fixTable">
																			<thead>
																				<tr class="sticky-header">
																					<th id="JAN" nowrap="nowrap" class="cboxlabel">JAN<span class="II"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="FEB" nowrap="nowrap" class="cboxlabel">FEB<span class="II"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="MAR" nowrap="nowrap" class="cboxlabel">MAR<span class="II"></span><br/>(&#8377 in Lakhs)</th>
																					<th id="JUNE" nowrap="nowrap" class="cboxlabel">TOTAL<br/>(&#8377 in Lakhs)</th>
																				</tr>
																				<tr>
																					<td><input type="text" class="tboxsmclass Qtr Q4CE1 Q4Calc" id="Q4CE1" name="Q4CE1" value="<?php if(isset($janVal)){ echo $janVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q4CE2 Q4Calc" id="Q4CE2" name="Q4CE2" value="<?php if(isset($febVal)){ echo $febVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass Qtr Q4CE3 Q4Calc" id="Q4CE3" name="Q4CE3" value="<?php if(isset($marchVal)){ echo $marchVal; } ?>" onKeyPress="return isNumberWithTwoDecimal(event,this);"></td>
																					<td><input type="text" class="tboxsmclass TQtr Q4CET" id="Q4CET" name="Q4CET" value="<?php if(isset($Q4Total)){ echo $Q4Total; } ?>" readonly=""></td>
																				</tr>
																			</thead>
																		</table>
																	</div>
																</div>
															</div>
													
															<div class="row" align="center">
																<div class="div12" align="center">
																	<a data-url="Home" class="btn btn-info" name="btn_back" id="btn_back">Back</a>
																	<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save ">
																	<!-- <a data-url="CommitedExpViewEdit" class="btn btn-info" name="view" id="view"> View / Edit </a> -->
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
						</blockquote>
					</div>
				</div>
        	</div>
		</form>
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	$("#cmb_work").chosen();
	$("#cmb_fin_year").chosen();

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
	
	var KillEvent = 0;
	$("body").on("click","#btn_save", function(event){ 
		if(KillEvent == 0){
			var ValidData = 0;
			$(".Qtr").each(function() {
				if(($(this).val() != "")&&($(this).val() != 0)){
					ValidData++;
				}
			});
			if($("#cmb_work").val() == ""){
				BootstrapDialog.alert("Please select work name");
				event.preventDefault();
				event.returnValue = false;
			}else if($("#cmb_fin_year").val() == ""){
				BootstrapDialog.alert("Please select financial year");
				event.preventDefault();
				event.returnValue = false;
			}else if(ValidData == 0){
				BootstrapDialog.alert("Please enter the valid expenditure amount for atleast one month");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to save expenditure ?',
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
	
	$("body").on("change","#cmb_fin_year", function(event){
		var FinYear	 		= $(this).val();
		var SplitFinaYears 	= FinYear.split("-");
		var StartYr 		= SplitFinaYears[0];
		var EndYr 			= SplitFinaYears[1];
		$(".I").text("-"+StartYr);
		$(".II").text("-"+EndYr);
	});
	$("body").on("change",".Eplan", function(event){
		var WorkId = $("#cmb_work").val();
		var FinYear = $("#cmb_fin_year").val();
		if((WorkId != '')&&(FinYear != '')){
			$(".Qtr").val('');
			$(".TQtr").val('');
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/GetCommitExpData.php', 
				contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				data: { WorkId: WorkId, FinYear: FinYear }, 
				dataType: 'json',
				success: function (data) {  
					if(data != null){  
						var M1 = 0;
						if((data.april !== null)&&(typeof data.april !== 'undefined')){ $("#Q1CE1").val(data.april); var Mon1 = data.april; }else{ var Mon1 = 0; }
						if((data.may !== null)&&(typeof data.may !== 'undefined')){ $("#Q1CE2").val(data.may); var Mon2 = data.may; }else{ var Mon2 = 0; }
						if((data.june !== null)&&(typeof data.june !== 'undefined')){ $("#Q1CE3").val(data.june); var Mon3 = data.june; }else{ var Mon3 = 0; }
						var Q1Total = Number(Mon1) + Number(Mon2) + Number(Mon3); 
						if((Mon1 > 0)||(Mon2 > 0)||(Mon3 > 0)){
							$("#Q1CET").val(Q1Total.toFixed(2));
						}
						if((data.july !== null)&&(typeof data.july !== 'undefined')){ $("#Q2CE1").val(data.july); var Mon4 = data.july; }else{ var Mon4 = 0; }
						if((data.aug !== null)&&(typeof data.aug !== 'undefined')){ $("#Q2CE2").val(data.aug); var Mon5 = data.aug; }else{ var Mon5 = 0; }
						if((data.sep !== null)&&(typeof data.sep !== 'undefined')){ $("#Q2CE3").val(data.sep); var Mon6 = data.sep; }else{ var Mon6 = 0; }
						var Q2Total = Number(Mon4) + Number(Mon5) + Number(Mon6);
						if((Mon4 > 0)||(Mon5 > 0)||(Mon6 > 0)){
							$("#Q2CET").val(Q2Total.toFixed(2));
						}
						if((data.oct !== null)&&(typeof data.oct !== 'undefined')){ $("#Q3CE1").val(data.oct); var Mon7 = data.oct; }else{ var Mon7 = 0; }
						if((data.nov !== null)&&(typeof data.nov !== 'undefined')){ $("#Q3CE2").val(data.nov); var Mon8 = data.nov; }else{ var Mon8 = 0; }
						if((data.dece !== null)&&(typeof data.dece !== 'undefined')){ $("#Q3CE3").val(data.dece); var Mon9 = data.dece; }else{ var Mon9 = 0; }
						var Q3Total = Number(Mon7) + Number(Mon8) + Number(Mon9);
						if((Mon7 > 0)||(Mon8 > 0)||(Mon9 > 0)){
							$("#Q3CET").val(Q3Total.toFixed(2));
						}
						if((data.jan !== null)&&(typeof data.jan !== 'undefined')){ $("#Q4CE1").val(data.jan); var Mon10 = data.jan; }else{ var Mon10 = 0; }
						if((data.feb !== null)&&(typeof data.feb !== 'undefined')){ $("#Q4CE2").val(data.feb); var Mon11 = data.feb; }else{ var Mon11 = 0; }
						if((data.march !== null)&&(typeof data.march !== 'undefined')){ $("#Q4CE3").val(data.march); var Mon12 = data.march; }else{ var Mon12 = 0; }
						var Q4Total = Number(Mon10) + Number(Mon11) + Number(Mon12);
						if((Mon10 > 0)||(Mon11 > 0)||(Mon12 > 0)){
							$("#Q4CET").val(Q4Total.toFixed(2));
						}
					}
				}
			});
		}
	});

	$("body").on("change",".Q1Calc", function(event){
		var TotalQ1 = 0;
		$(".Q1Calc").each(function() {
			var Amount = $(this).val();
			TotalQ1 = Number(TotalQ1) + Number(Amount);
		});
		$("#Q1CET").val(TotalQ1);
	});
	$("body").on("change",".Q2Calc", function(event){
		var TotalQ2 = 0;
		$(".Q2Calc").each(function() {
			var Amount = $(this).val();
			TotalQ2 = Number(TotalQ2) + Number(Amount);
		});
		$("#Q2CET").val(TotalQ2);
	});
	$("body").on("change",".Q3Calc", function(event){
		var TotalQ3 = 0;
		$(".Q3Calc").each(function() {
			var Amount = $(this).val();
			TotalQ3 = Number(TotalQ3) + Number(Amount);
		});
		$("#Q3CET").val(TotalQ3);
	});
	$("body").on("change",".Q4Calc", function(event){
		var TotalQ4 = 0;
		$(".Q4Calc").each(function() {
			var Amount = $(this).val();
			TotalQ4 = Number(TotalQ4) + Number(Amount);
		});
		$("#Q4CET").val(TotalQ4);
	});


</script>
<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.form-control{
	width:95%;
}
</style>
</body>
</html>

