<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
$view = 0;
if($_GET["id"]){
	$PostSheetid = $_GET['id'];
	$PostRbn = $_GET['rbn'];
	$sheetquery = "SELECT * FROM sheet WHERE sheet_id='$PostSheetid' AND active = 1 ORDER BY sheet_id ASC";
	$sheetsqlquery = mysql_query($sheetquery);
	if($sheetsqlquery == true ){
		$ShList  = mysql_fetch_object($sheetsqlquery);
		$SheetID = $ShList->sheet_id;
		$workName = $ShList->work_name;
		if($ShList->short_name != ''){
			$workName = $ShList->short_name;
		}
	}
	$view = 1;  $reset = 1;
	// $count1 = 0;  $count2 = 0;  $count3 = 0;  $count4 = 0;  $count5 = 0;  $count6 = 0;
	// $select_meas_book_query = "select distinct * from measurementbook where sheetid = '$SheetID' and rbn >= '$PostRbn'";
	// $select_meas_book_sql 	= mysql_query($select_meas_book_query);
	// if($select_meas_book_sql == true){
	// 	$count1 = mysql_num_rows($select_meas_book_sql);
	// }
	
	// $select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$SheetID' and rbn = '$PostRbn'";
	// $select_send_acc_sql 	= mysql_query($select_send_acc_query);
	// if($select_send_acc_sql == true){
	// 	$count2 = mysql_num_rows($select_send_acc_sql);
	// 	while($SAList = mysql_fetch_object($select_send_acc_sql)){
	// 		if($SAList->mb_ac == "SC"){
	// 			$count3++;
	// 		}
	// 		if($SAList->sa_ac == "SC"){
	// 			$count3++;
	// 		}
	// 		if($SAList->ab_ac == "SC"){
	// 			$count3++;
	// 		}	
	// 	}
	// }
	// if($count1 >0){
	// 	/// Do not allow to reset
	// 	$reset = 0;
	// }else{
	// 	if(($count2 >0)&&($count3 == 0)){
	// 		/// Do not allow to reset
	// 		$reset = 0;
	// 	}else{
	// 		/// Allow to reset
	// 		$reset = 1;
	// 	}
	// }
}
if($_POST["submit"] == " Validate "){
	$sheetid 	= $_POST['cmb_work_no'];
	$workName	= $_POST['workname'];
	$rbn 		= $_POST['txt_rbn'];
}

if($_POST["btn_reset"] == " Reset "){
	$sheetid 	= $_POST['cmb_work_no'];
	$workName	= $_POST['workname'];
	$rbn 		= $_POST['txt_rbn'];
	$view 		= 1; 
	$count1 = 0; $count2 = 0; $count3 = 0;

	$RabForArr  = $_POST['ch_rab_for'];
	$IsEntireRabStr = ""; $IsSecAdvStr = ""; $IsMobAdv = ""; $IsEscStr = "";
	if(count($RabForArr)>0){
		foreach($RabForArr as $Key => $Value){
			if($Value == "ERAB"){ $IsEntireRabStr = "Y"; }
			if($Value == "SA"){ $IsSecAdvStr = "Y"; }
			if($Value == "MA"){ $IsMobAdv = "Y"; }
			if($Value == "ES"){ $IsEscStr = "Y"; }
		}
	}
	if($IsEntireRabStr == ""){
		$RabFlag = "ZM";
	}else{
		$RabFlag = "";
	}
	
	// $select_meas_book_query = "select distinct * from measurementbook where sheetid = '$sheetid' and rbn >= '$rbn'";
	// $select_meas_book_sql 	= mysql_query($select_meas_book_query);
	// if($select_meas_book_sql == true){
	// 	$count1 	= mysql_num_rows($select_meas_book_sql);
	// }
	
	// $select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
	// $select_send_acc_sql 	= mysql_query($select_send_acc_query);
	// if($select_send_acc_sql == true){
	// 	$count2 	= mysql_num_rows($select_send_acc_sql);
	// 	while($SAList = mysql_fetch_object($select_send_acc_sql)){
	// 		if($SAList->mb_ac == "SC"){
	// 			$count3++;
	// 		}
	// 		if($SAList->sa_ac == "SC"){
	// 			$count3++;
	// 		}
	// 		if($SAList->ab_ac == "SC"){
	// 			$count3++;
	// 		}	
	// 	}
	// }
	// if($count1 >0){
	// 	/// Do not allow to reset
	// 	$reset = 0;
	// }else{
	// 	if(($count2 >0)&&($count3 == 0)){
	// 		/// Do not allow to reset
	// 		$reset = 0;
	// 		if($_SESSION['isadmin'] == 1) {
	// 			$reset = 2;		// FOR ADMIN TO DELETE AFTER RETURNED FROM ACCOUNTS
	// 		}
	// 	}else{
	// 		/// Allow to reset
	// 		$reset = 1;
	// 	}
	// }
	$reset = 1;
	if($_SESSION['isadmin'] == 1) {
		$reset = 2;		// FOR ADMIN TO DELETE AFTER RETURNED FROM ACCOUNTS
	}
	if($reset == 1 || $reset == 2){
		if($reset == 1){
			$SelBillRegQuery = "select * from bill_register where sheetid = '$sheetid' and rbn = '$rbn'";
			$SelBillRegSql = mysql_query($SelBillRegQuery);
			$BRCount = mysql_num_rows($SelBillRegSql);
			if($BRCount > 0){
				$success = 0;
				header('Location: RABResetList.php?suc='.$success);
			}
		}
		if($reset == 2){
			$AccDeleteQuery = "delete a.*, b.* from acc_log a, acc_log_detail b where a.esc_id = '$EscId' and a.esc_tcc_id = b.esc_tcc_id";
			$AccDeleteSql = mysql_query($AccDeleteQuery);
			$AccDeleteQuery1 = "delete from acc_log where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql1 = mysql_query($AccDeleteQuery1);
			$AccDeleteQuery2 = "delete from bill_register where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql2 = mysql_query($AccDeleteQuery2);
			$AccDeleteQuery3 = "delete from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql3 = mysql_query($AccDeleteQuery3);
			$AccDeleteQuery4 = "delete from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql4 = mysql_query($AccDeleteQuery4);
			$AccDeleteQuery5 = "delete from mop_rec_dt where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql5 = mysql_query($AccDeleteQuery5);
			$AccDeleteQuery6 = "delete from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql6 = mysql_query($AccDeleteQuery6);
			$AccDeleteQuery7 = "delete from send_acc_supp_doc where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql7 = mysql_query($AccDeleteQuery7);
			$AccDeleteQuery8 = "delete from voucher_upt where sheetid = '$sheetid' and rbn = '$rbn'";
			$AccDeleteSql8 = mysql_query($AccDeleteQuery8);
		}

		if($IsEntireRabStr == 'Y' || $IsSecAdvStr == 'Y'){
			$DeleteQuerySA = "delete a.*, b.* from secured_advance a, secured_advance_dt b where a.sheetid = '$sheetid' and a.rbn = '$rbn' and a.said = b.said";
			$DeleteSqlSA = mysql_query($DeleteQuerySA);
			$UpdateAbstSA = "update abstractbook set is_sec_adv = null, secured_adv_amt = null where sheetid = '$sheetid' and rbn = '$rbn'";
			$UpdateSqlSA = mysql_query($UpdateAbstSA);
			$msg = "RAB Reset Successfully";
			$success = 1;
		}
		if($IsEntireRabStr == 'Y' || $IsMobAdv == 'Y'){
			$DeleteQueryMA = "delete a.*, b.* from mob_master a, mob_adv_rec b where a.sheetid = '$sheetid' and a.rbn = '$rbn' and a.mobmid = b.mobmid";
			$DeleteSqlMA = mysql_query($DeleteQueryMA);
			$DeleteQueryMA1 = "delete from mobilization_advance where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSqlMA1 = mysql_query($DeleteQueryMA1);
			$UpdateAbstMA = "update abstractbook set is_mob_adv = null, mob_adv_amt = null where sheetid = '$sheetid' and rbn = '$rbn'";
			$UpdateSqlMA = mysql_query($UpdateAbstMA);
			$msg = "RAB Reset Successfully";
			$success = 1;
		}
		if($IsEntireRabStr == 'Y' || $IsEscStr == 'Y'){
			$SelEscQuery = "select * from escalation where a.sheetid = '$sheetid' and a.rbn = '$rbn'";
			$SelEscSql = mysql_query($SelEscQuery);
			if($SelEscSql == true){
				while($SelList = mysql_fetch_object($SelEscSql)){
					$EscId = $SelList->esc_id;
				}
				if($EscId != NULL && $EscId != ''){
					$EscDeleteQuery = "delete a.*, b.* from escalation_tcc a, escalation_tcc_details b where a.esc_id = '$EscId' and a.esc_tcc_id = b.esc_tcc_id";
					$EscDeleteSql = mysql_query($EscDeleteQuery);
					$EscDeleteQuery1 = "delete from esc_consumption_10ca where esc_id = '$EscId'";
					$EscDeleteSql1 = mysql_query($EscDeleteQuery1);
					$EscDeleteQuery2 = "delete from esc_consumption_10ca_site where esc_id = '$EscId'";
					$EscDeleteSql2 = mysql_query($EscDeleteQuery2);
					$EscDeleteQuery3 = "delete from esc_consumption_10ca_master where esc_id = '$EscId'";
					$EscDeleteSql3 = mysql_query($EscDeleteQuery3);
					$EscDeleteQuery4 = "delete from escalation_10ca_details where esc_id = '$EscId'";
					$EscDeleteSql4 = mysql_query($EscDeleteQuery4);
					$EscDeleteQuery5 = "delete from escalation_revised where esc_id = '$EscId'";
					$EscDeleteSql5 = mysql_query($EscDeleteQuery5);
					$EscDeleteQuery6 = "delete from escalation where esc_id = '$EscId'";
					$EscDeleteSql6 = mysql_query($EscDeleteQuery6);
					$EscDeleteQuery7 = "delete a.*, b.* from price_index a, price_index_detail b where a.esc_id = '$EscId' and a.pid = b.pid";
					$EscDeleteSql7 = mysql_query($EscDeleteQuery7);
					$UpdateAbstMA = "update abstractbook set is_esc = null, upto_date_total_amount_esc = '0.00', dpm_total_amount_esc = '0.00'
					, slm_total_amount_esc = '0.00', mbookno_esc = '0', mbookpage_esc = '0' where sheetid = '$sheetid' and rbn = '$rbn'";
					$UpdateSqlMA = mysql_query($UpdateAbstMA);
	
					$msg = "RAB Reset Successfully";
					$success = 1;

				}
			}
		}
		if($IsEntireRabStr == 'Y'){
			$DeleteQuery1 = "delete from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql1 = mysql_query($DeleteQuery1);
			
			$DeleteQuery2 = "delete from mbookgenerate where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql2 = mysql_query($DeleteQuery2);
			
			$DeleteQuery3 = "delete from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql3 = mysql_query($DeleteQuery3);
			
			$DeleteQuery4 = "delete from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql4 = mysql_query($DeleteQuery4);
			
			$DeleteQuery5 = "delete from mymbook where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql5 = mysql_query($DeleteQuery5);
			
			$DeleteQuery6 = "delete a.*, b.* from mbookheader_temp a, mbookdetail_temp b where a.sheetid = '$sheetid' and a.mbheaderid = b.mbheaderid";
			$DeleteSql6 = mysql_query($DeleteQuery6);
			
			$DeleteQuery7 = "delete from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql7 = mysql_query($DeleteQuery7);
			
			$DeleteQuery8 = "delete from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql8 = mysql_query($DeleteQuery8);
			
			$DeleteQuery9 = "delete from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql9 = mysql_query($DeleteQuery9);
			
			$DeleteQuery10 = "delete from recovery_release where sheetid = '$sheetid' and rbn = '$rbn'";
			$DeleteSql10 = mysql_query($DeleteQuery10);
			
			$msg = "RAB Reset Successfully";
			$success = 1;
		}
		if($success == 1){
			header('Location: RABResetList.php?suc='.$success);
		}
	}else{
		$msg = "Unable to Reset RAB";
		$success = 0;
	}
}

?>
<?php require_once "Header.html"; ?>
<script>
	function find_workname(){		
		var xmlHttp;
		var data;
		var i,j;
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value =	name[0].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function func_find_rbn()
	{
		var xmlHttp;
		var data;
		document.form.txt_rbn_list.value = "";
		document.form.txt_rbn_list2.value = "";
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_checkrbn.php?workordernumber=" + document.form.wordorderno.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if(xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText; //alert(data);
				var SplitData = data.split("@@");
				if ((SplitData[0] != "")||(SplitData[1] != "")){
					document.form.txt_rbn_list.value = SplitData[0];
					document.form.txt_rbn_list2.value = SplitData[1];
				}else{
					document.form.txt_rbn_list.value = "";
					document.form.txt_rbn_list2.value = "";
				}
			}
		}
		xmlHttp.send(strURL);
	}
	function goBack(){
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<style>
	.table1{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:13px;
	}
	.paddlr2{
		padding-left:2px; padding-right:2px;
	}
	.inputGroup label::after {
		width: 7px;
		height: 10px;
		right: 8px;
	}
	.IpGr{
		background:#F2F5F6;
	}
	.IpGr label{
		background:#F2F5F6;
		color:#E81A66;
	}
	.IpGr label::after {
		width: 0px;
		height: 0px;
		right: 0px;
		border: 0px solid #C4C4C4;
	}
</style>

<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
<!--==============================Content=================================-->


<div class="content">
	<div class="title">RAB Reset </div>
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:auto">
				<form name="form" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
					<div class="container">
						<div class="row clearrow"></div>
						<div class="div2" align="center">&nbsp;</div>
						<div class="div8" align="center">
							<div class="innerdiv2">
								<div class="row divhead" align="center">Running Account Bill Details</div>
								<div class="row innerdiv" align="center">
									<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#E8E8E8" border="0" align="center" >
										<tr>
											<td class="label">&nbsp;Work Short Name</td>
											<td class="labeldisplay">
												<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname()" class="textboxdisplay" style="width:800px;height:22px;" tabindex="7">
												<?php echo $objBind->BindWorkOrderNoForReset($PostSheetid); ?>
												</select>
											</td>
										</tr>
										<tr><td>&nbsp;</td><td id="val_work" style="color:red"></td></tr>
										<tr>
											<td class="label">&nbsp;Name of the Work </td>
											<td class="labeldisplay">
											<textarea name="workname" class="textboxdisplay txtarea_style" style="width:800px; pointer-events: none; background-color:#E8E8E8" rows="5" readonly="readonly"><?php echo $workName; ?></textarea>
											</td>
										</tr>
										<tr><td>&nbsp;</td><td id="val_work" style="color:red"></td></tr>
										<tr>
											<td class="label">&nbsp;RAB </td>
											<td class="labeldisplay">
											<input type="text" name="txt_rbn" id="txt_rbn" class="textboxdisplay" style="width:240px; height:25px;" value="<?php echo $PostRbn; ?>" readonly="">
											</td>
										</tr>
										<tr><td>&nbsp;</td><td id="val_rbn" style="color:red"></td></tr>
									</table>
									<div class="row clearrow"></div>
									<div class="row">
										<div class="div12" align="left" style="box-sizing:border-box;">
											<div class="div2">
												<div class="inputGroup IpGr">
													<input id="rab_for" type="checkbox" value="1" disabled="disabled"/>
													<label for="rab_for" style="padding:3px 0px; width:99%"> &nbsp;Reset For</label>
												</div>
											</div>
											<div class="div2">
												<div class="inputGroup paddlr2">
													<input id="ch_rab_for_meas" name="ch_rab_for[]" type="checkbox" value="ERAB"/>
													<label for="ch_rab_for_meas" style="padding:3px 0px; width:99%"> &nbsp;Entire RAB</label>
												</div>
											</div>
											<div class="div2">
												<div class="inputGroup paddlr2">
													<input id="ch_rab_for_sec" name="ch_rab_for[]" type="checkbox" value="SA"/>
													<label for="ch_rab_for_sec" style="padding:3px 0px; width:99%"> &nbsp;Secured Adv. only</label>
												</div>
											</div>
											<div class="div3">
												<div class="inputGroup paddlr2">
													<input id="ch_mob_adv" name="ch_rab_for[]" type="checkbox" value="MA"/>
													<label for="ch_mob_adv" style="padding:3px 0px; width:99%"> &nbsp;Mobilization Adv. only</label>
												</div>
											</div>
											<div class="div3">
												<div class="inputGroup paddlr2">
													<input id="ch_rab_for_esc" name="ch_rab_for[]" type="checkbox" value="ES"/>
													<label for="ch_rab_for_esc" style="padding:3px 0px; width:99%"> &nbsp;Escalation only</label>
												</div>
											</div>
											
										</div>
									</div>
									<div class="row clearrow"></div>
									<div class="div12" align="left" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
										<br/>
										<table width="100%"  bgcolor="#E8E8E8" class="table1" align="center">
											<tr class="label">
												<?php if($reset == 1){ ?>
													<td align="center" style="height:30px" valign="middle">Click Reset button to Reset RAB <span style="color:#F20024">(Please Confirm RAB before click Reset Button)</span></td>
													<td align="center" valign="middle" style="width:150px"><input type="submit" name="btn_reset" id="btn_reset" value=" Reset "></td>
												<?php }else{ ?>
													<td align="center" style="height:30px" valign="middle" colspan="2">This RAB already closed, unable to Reset.</td>
												<?php } ?>	
												<td align="center" valign="middle" style="width:150px"><input type="button" name="btn_back" id="btn_back" value=" Back " class="backbutton"></td>
											</tr>
										</table>
									</div>
								</div>
								<!-- <div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
									<div class="buttonsection">
										<input type="submit" data-type="submit" value=" Validate " name="submit" id="submit"   />
									</div>
								</div> -->

							</div>
						</div>
					</div>
				</form>
			</blockquote>
		</div>
	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>

	var msg = "<?php echo $msg; ?>";
	var success = "<?php echo $success; ?>";
	var titletext = "";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			if(success == 1){
				swal("", msg, "success");
			}else{
				swal(msg, "", "");
			}
		}
	};


	$(function() {
		
		$("#cmb_work_no").bind("change", function () {  
			var WorkId = $("#cmb_work_no").val();
			if(WorkId != ''){
				$.ajax({
					type: 'POST', 
					url: 'FindRabStatusForCreate.php', 
					data: { WorkId: WorkId }, 
					dataType: 'json',
					success: function (data) { //alert(data);
						if(data != null){
							if((data['LastestRbn'] != null)&&(data['LastestRbn'] != '')){
								var LatestRbn = data['LastestRbn'];
							}
							if((data['RbnStatus'] != null)&&(data['RbnStatus'] != '')){
								$("#txt_rbn").val(LatestRbn);
							}
						}
					}
				});
			}
		});

		var KillEvent = 0;
		$("#btn_reset").click(function(event){
			if(KillEvent == 0){
				if($("[name='ch_rab_for[]']:checked").length == 0){
					BootstrapDialog.alert("Please select atleast one option for RAB");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm({
						title: 'Confirmation Message',
						message: 'Are you sure want to Reset RAB ?',
						closable: false, // <-- Default value is false
						draggable: false, // <-- Default value is false
						btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
						btnOKLabel: 'Ok', // <-- Default value is 'OK',
						callback: function(result) {
							// result will be true if button was click, while it will be false if users close the dialog directly.
							if(result){
								KillEvent = 1;
								$("#btn_reset").trigger( "click" );
							}else {
								//alert('Nope.');
								KillEvent = 0;
							}
						}
					});
				}
			}
		});

		$("#btn_back").click(function(event){
			url = "RABResetList.php";
			window.location.replace(url);
		});





		$.fn.validateworkorder = function(event) { 
			if($("#cmb_work_no").val()==""){ 
				var a="Please select the work order number";
				$('#val_work').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_work').text(a);
			}
		}
		$.fn.validateRAB = function(event) { 
			if($("#txt_rbn").val()==""){ 
				var a="Please Enter your RAB Number";
				$('#val_rbn').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else if($("#txt_rbn").val()==0){ 
				var a="Please Enter valid RAB Number";
				$('#val_rbn').text(a);
				event.preventDefault();
				event.returnValue = false;
			}
			else{
				var a="";
				$('#val_rbn').text(a);
			}
		}
		$("#top").submit(function(event){
			$(this).validateworkorder(event);
			$(this).validateRAB(event);
		});
		$("#cmb_work_no").change(function(event){
			$(this).validateworkorder(event);
		});
		$("#txt_rbn").keyup(function(event){
			$(this).validateRAB(event);
		});
		
		/*$("#btn_reset").click(function(event){
			//var x = BootstrapDialog.confirm('I want banana!');
			//alert(x);
			BootstrapDialog.show({
				title: 'Reset Confirmation',
				message: 'Are you sure want to Reset RAB !',
				buttons: [{
					label: 'OK',
					action: function(dialog) {
						//dialog.close();
						$('#form1').submit();//event.returnValue = true;
					}
				},{
					label: 'Cancel',
					action: function(dialog) {
						dialog.close();
					}
				}]
			});
			event.preventDefault();
			event.returnValue = false;
		});*/
	});
</script>
</body>
</html>

