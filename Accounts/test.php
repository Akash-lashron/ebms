<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName 	= $PTPart1.$PTIcon.'Home';
$msg 		= ""; $del = 0;
$RowCount 	= 0;
$staffid 	= $_SESSION['sid'];

$UnconfirmSor = 0; $UnconfirmRate = 0;
if(isset($_POST['submit1'])){
	$SaveCcno 		= $_POST['txt_ccno'];
	$SaveSrNo 		= $_POST['txt_sr_no'];
	$SaveRab 		= $_POST['txt_rab'];
	$SaveChRab 		= $_POST['ch_rab'];
	$SaveChFbill 	= $_POST['ch_finalbill'];
	$SaveSecAdv 	= $_POST['ch_sec_adv'];
	$SaveEsc 	 	= $_POST['ch_esc'];
	$SaveMobAdv 	= $_POST['ch_mob_adv'];
	$SaveSentBy 	= $_POST['cmb_sent_by'];
	$SaveSentOn 	= $_POST['txt_sent_on'];
	$SaveRecOn 		= $_POST['txt_rec_on'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	$SaveMbRec 		= $_POST['txt_mb_received'];
	$WorkExist 		= 0;
	$SelectQuery = "SELECT * FROM bill_register WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRab' ORDER BY br_no ASC";
	$SelectSql   = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$WorkExist = 1;
		}
	}
	$BillAccStatus = ""; $BillAccRegStatus = "";
	$SelectQuery2 = "SELECT * FROM bill_register WHERE sheetid = '$SaveSheetId' ORDER BY br_no DESC LIMIT 1";
	$SelectSql2   = mysqli_query($dbConn,$SelectQuery2);
	if($SelectSql2 == true){
		if(mysqli_num_rows($SelectSql2)>0){
			$List2 = mysqli_fetch_object($SelectSql2);
			$BillAccStatus = $List2->acc_status;
			$BillAccRegStatus = $List2->reg_status;
		}
	}
	if(($BillAccRegStatus == "R")&&($BillAccStatus == "P")){
		$msg = "Error : Previous bill process is in pending, unable to register.";
	}else{
		if($WorkExist == 0){
			$SaveBillRegQuery = "insert into bill_register set sheetid = '$SaveSheetId', rbn = '$SaveRab', br_no = '$SaveSrNo', is_rab = '$SaveChRab', is_final_bill = '$SaveChFbill', is_esc = '$SaveEsc', is_sec_adv = '$SaveSecAdv', is_mob_adv = '$SaveMobAdv', received_by = '".$_SESSION['sid']."', received_on = NOW(), mb_received = '$SaveMbRec', civil_status = 'C', acc_status = 'P', reg_status = 'R', active = 1";
			$SaveBillRegSql   = mysqli_query($dbConn,$SaveBillRegQuery);
			/*$AbsExist = 0;
			$SelectAbsQuery = "SELECT absbookid FROM abstractbook WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRab'";
			$SelectAbsSql   = mysqli_query($dbConn,$SelectAbsQuery);
			if($SelectAbsSql == true){
				if(mysqli_num_rows($SelectAbsSql)>0){
					$AbsExist = 1;
				}
			}
			if($AbsExist == 0){
				
			}*/
			
			
		}else{
			$SaveBillRegQuery = "update bill_register set sheetid = '$SaveSheetId', rbn = '$SaveRab', br_no = '$SaveSrNo', is_rab = '$SaveChRab', is_final_bill = '$SaveChFbill', is_esc = '$SaveEsc', is_sec_adv = '$SaveSecAdv', is_mob_adv = '$SaveMobAdv', received_by = '".$_SESSION['sid']."', received_on = NOW(), mb_received = '$SaveMbRec', civil_status = 'C', acc_status = 'P', reg_status = 'R', active = 1 WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRab'";
			$SaveBillRegSql   = mysqli_query($dbConn,$SaveBillRegQuery);
		}
		if($SaveBillRegSql == true){
			$msg = "Bill registration done successfully";
		}else{
			$msg = "Sorry, Bill register not saved. Please try again.";
		}
	}
}

$BRCount = 0;
$SelectQuery = "SELECT a.*, b.short_name, b.computer_code_no, b.under_civil_sheetid FROM bill_register a INNER JOIN sheet b ON (a.sheetid = b.sheet_id) WHERE a.reg_status = ''";
$SelectSql   = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$BRCount = 1;
	}
}

$PendingBillArr = array(); $PendingBillRabArr = array();
$SelectQuery2 = "SELECT a.*, b.short_name, b.computer_code_no FROM bill_register a INNER JOIN sheet b ON (a.sheetid = b.sheet_id) WHERE a.acc_status = 'P' AND a.reg_status = 'R'";// AND b.under_civil_sheetid = 0";
$SelectSql2   = mysqli_query($dbConn,$SelectQuery2);
if($SelectSql2 == true){
	if(mysqli_num_rows($SelectSql2)>0){
		while($PrevBRList = mysqli_fetch_object($SelectSql2)){
			array_push($PendingBillArr,$PrevBRList->sheetid);
			$PendingBillRabArr[$PrevBRList->sheetid] = $PrevBRList->rbn;
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
						<blockquote class="bq1" style="overflow:auto">
							
							<div class="box-container box-container-lg">
								<div class="div5">
									<div class="card">
										<div class="face-static">
											<div class="card-header inkblue-card">Works Bill Waiting for Registration <span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1 BillBox">
												<?php 
												if($BRCount > 0){ while($BRList = mysqli_fetch_object($SelectSql)){ 
													$IsPending = "N";
													if(in_array($BRList->sheetid, $PendingBillArr)){
														$IsPending = "Y";
													}
													if($BRList->under_civil_sheetid != 0){
														if(in_array($BRList->under_civil_sheetid, $PendingBillArr)){
															if($PendingBillRabArr[$BRList->under_civil_sheetid] != $BRList->rbn){
																$IsPending = "Y";
															}
														}
													}
												?>
													<div class="billrow BillData" id="<?php echo $BRList->sheetid; ?>" data-rab="<?php echo $BRList->rbn; ?>" data-ccno="<?php echo $BRList->computer_code_no; ?>" data-pending="<?php echo $IsPending; ?>"><i class="fa fa-check-square-o" style="font-size:15px; font-weight:normal; padding-top:3px;"></i>&nbsp;&nbsp;<?php echo $BRList->computer_code_no; ?>-<?php echo $BRList->short_name; ?> <span class="clickbtn"><i class="fa fa-hand-o-left blink_me" aria-hidden="true" style="padding-top:4px;"></i> Click here</span></div>
												<?php } } ?>  
												<?php if($BRCount == 0){ ?>
													<div class="face-static disfont">
														<div class="row smclearrow"></div>
														No bill waiting for registration
														<div class="row smclearrow"></div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								
								<div class="div7">
									<div class="card">
										<div class="face-static">
											<div class="card-header inkblue-card">Bill Registration Form <span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1 ChartCard billrowform">
												<div class="row">
													<div class="div12" align="center">
														<div class="innerdiv2">
															<div class="row" align="center">
																<div class="row">
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">CCNO.</div>
																	<div class="div2" align="left"><input type="text" name="txt_ccno" id="txt_ccno" class="tboxsmclass" value=""></div>
																	<div class="div2 cboxlabel">BR. No.</div>
																	<div class="div2" align="left"><input type="text" name="txt_sr_no" id="txt_sr_no" class="tboxsmclass" readonly=""></div>
																	
																	<!--<div class="div4 lboxlabel">Work Order / P.O. No.</div>
																	<div class="div8" align="left">
																		<select name="cmb_work_no" id="cmb_work_no" class="tboxsmclass">
																			<option value=""> -------------- Select -------------</option>
																			<option value="BARC/NRB/FRFCF/P&C/PO-002">BARC/NRB/FRFCF/P&C/PO-002</option>
																		</select>
																	</div>-->
																	
																	
																	
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Name of Work</div>
																	<div class="div10" align="left"><textarea name="txt_work_name" id="txt_work_name" class="tboxsmclass"></textarea></div>
																	
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">RAB No.</div>
																	<div class="div1" align="left"><input type="text" name="txt_rab" id="txt_rab" class="tboxsmclass"></div>
																
																	<!--<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">RAB For</div>-->
																	<div class="div9 lboxlabel" align="left">
																		&nbsp;
																		<input class="RabFor" type="checkbox" name="ch_rab" id="ch_rab" value="Y">&nbsp;Measurements&nbsp;&nbsp;&nbsp;&nbsp;
																		<input class="RabFor" type="checkbox" name="ch_finalbill" id="ch_finalbill" value="Y">&nbsp;Final Bill&nbsp;&nbsp;&nbsp;
																		<input class="RabFor" type="checkbox" name="ch_sec_adv" id="ch_sec_adv" value="Y">&nbsp;Sec. Adv.&nbsp;&nbsp;&nbsp;
																		<input class="RabFor" type="checkbox" name="ch_esc" id="ch_esc" value="Y">&nbsp;Escal.&nbsp;&nbsp;&nbsp;
																		<input class="RabFor" type="checkbox" name="ch_mob_adv" id="ch_mob_adv" value="Y">&nbsp;Mob. Adv.
																		
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">MB Received<sup style="color:#37566F">*</sup></div>
																	<div class="div10" align="left">
																		<textarea name="txt_mb_received" id="txt_mb_received" class="tboxsmclass"></textarea>
																		<div style="height:10px; font-size:11px; color:#37566F">* Enter MB No. seperated with comma ( , ). Eg: 101,102,103</div>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Sent By</div>
																	<div class="div6" align="left">
																		<select name="cmb_sent_by" id="cmb_sent_by" class="tboxsmclass">
																			<option value=""> --- Select ---</option>
																			<?php echo $objBind->BindAllStaff(0); ?>
																		</select>
																	</div>
																	<div class="div2 cboxlabel">Sent On</div>
																	<div class="div2" align="left"><input type="text" name="txt_sent_on" id="txt_sent_on" class="tboxsmclass datepicker"></div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">Received On</div>
																	<div class="div2" align="left"><input type="text" name="txt_rec_on" id="txt_rec_on" class="tboxsmclass datepicker" readonly="" value="<?php echo date("d/m/Y"); ?>"></div>
																	<div class="row clearrow"></div>
																	<div class="div12">
																		<input type="text" name="txt_sheetid" id="txt_sheetid" value="">
																		<input type="button" class="btn btn-info" data-type="submit" value=" REGISTER " name="submit" id="submit"   />
																	</div>
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
	$('.notBtn').click(function(event){ 
		var PageUrl = $(this).attr("data-url");
  		$(location).attr("href",PageUrl+".php");
		event.preventDefault();
		return false;
  	});
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			BootstrapDialog.alert(msg);
		}
	};
	var KillEvent = 0;
	$('body').on("click","#submit", function(event){ 
		if(KillEvent == 0){
			var Ccno 	 = $("#txt_ccno").val();
			var SrNo 	 = $("#txt_sr_no").val();
			var WorkName = $("#txt_work_name").val();
			var RabNo 	 = $("#txt_rab").val();
			var SentBy 	 = $("#cmb_sent_by").val();
			var SentOn 	 = $("#txt_sent_on").val();
			var RabForLen = $('.RabFor:checkbox:checked').length;
			//alert(RabForLen);
			if(Ccno == ""){
				BootstrapDialog.alert("CCNO should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(SrNo == ""){
				BootstrapDialog.alert("Serial No. should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkName == ""){
				BootstrapDialog.alert("Work name should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(RabNo == ""){
				BootstrapDialog.alert("RAB No. should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(SentBy == ""){
				BootstrapDialog.alert("Please select Sent By");
				event.preventDefault();
				event.returnValue = false;
			}else if(SentBy == ""){
				BootstrapDialog.alert("Sent on should not be empty");
				event.preventDefault();
				event.returnValue = false;
			}else if(RabForLen <= 0){
				BootstrapDialog.alert("Please select atleast one option for RAB");
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to Register Bill ?',
					closable: false, // <-- Default value is false
					draggable: false, // <-- Default value is false
					btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
					btnOKLabel: 'Ok', // <-- Default value is 'OK',
					callback: function(result) {
						// result will be true if button was click, while it will be false if users close the dialog directly.
						if(result){
							KillEvent = 1;
							$("#submit").trigger( "click" );
						}else {
							//alert('Nope.');
							KillEvent = 0;
						}
					}
				});
			}
		}
	});
	function WorkDetails(Work,Rab,Ccno,Type){
		
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindWorksData.php', 
			data: { Work: Work, Rab: Rab, Ccno: Ccno, Type: Type }, 
			dataType: 'json',
			success: function (data) {  // alert(data['computer_code_no']);
				if(data != null){
					$.each(data, function(index, element) { 
						$("#txt_ccno").val(element.computer_code_no);
						$("#txt_sr_no").val(element.br_no);
						if((element.short_name != '')&&(element.short_name != null)){
							$("#txt_work_name").val(element.short_name);
						}else{
							$("#txt_work_name").val(element.work_name);
						}
						$("#txt_rab").val(element.rbn);
						$("#cmb_sent_by").val(element.sent_by);
						$("#txt_sent_on").val(element.sent_on);
						$("#txt_sheetid").val(element.sheet_id);
						$("#txt_mb_received").val(element.mb_received);
						
						if(element.is_rab == "Y"){
							$('#ch_rab').attr('checked','checked');
						}
						if(element.is_final_bill == "Y"){
							$('#ch_finalbill').attr('checked','checked');
						}
						if(element.is_sec_adv == "Y"){
							$('#ch_sec_adv').attr('checked','checked');
						}
						if(element.is_esc == "Y"){
							$('#ch_esc').attr('checked','checked');
						}
						if(element.is_mob_adv == "Y"){
							$('#ch_mob_adv').attr('checked','checked');
						}
					});
				}
			}
		});
	}
	$('.BillData').click(function(event){ 
		$("#txt_ccno").val('');
		$("#txt_sr_no").val('');
		$("#txt_work_name").val('');
		$("#txt_rab").val('');
		$("#cmb_sent_by").val('');
		$("#txt_sent_on").val('');
		$("#txt_sheetid").val('');
		$("#txt_mb_received").val('');
		$('.RabFor').removeAttr('checked'); //alert(Ccno);
		var Work  = $(this).attr("id");
		var Rab   = $(this).attr("data-rab");
		var Ccno  = $(this).attr("data-ccno");
		var IsPending  = $(this).attr("data-pending"); alert(IsPending);
		$(".BillData").removeClass("billrow-active");
		$(this).addClass("billrow-active");
		if(IsPending == "Y"){
			BootstrapDialog.alert("Previous bill process is in pending, unable to register.");
			event.preventDefault();
			event.returnValue = false;
		}else{
			WorkDetails(Work,Rab,Ccno,'A');
		}
  	});
	$('#txt_ccno').change(function(event){ //alert(1);
		var Work  = ''; //alert(2);
		var Rab   = ''; //alert(3);
		var Ccno  = $(this).val(); //alert(4);
		WorkDetails(Work,Rab,Ccno,'M'); //alert(5);
  	});
	
});
</script>
<link rel="stylesheet" href="css/notyBox.css">

