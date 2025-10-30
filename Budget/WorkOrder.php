<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Work Order';
checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
$UserId  = $_SESSION['userid'];

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
if(isset($_POST['btn_confirm']) == ' Save '){
	$msg = "Data Inserted Successfully";
}
$SelectQuery2 	= "SELECT ccno,globid,sheetid,hoaid,cbdtid,contid FROM works WHERE globid = '$GlobID'";
			$SelectSql2 	= mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List = mysqli_fetch_object($SelectSql2);
					$ContBankDtId	= $List->cbdtid;
					$ContractorId	= $List->contid;        
				}
			}
// if(isset($_POST['btn_save']) == ' Save '){
// 	echo 1;exit;
// 	$CheckPt 	= 0;
// 	$InQueryCon = 0;
// 	$TrNo			= $_POST["cmb_tr_no"];
// 	$WrkName		= $_POST["txt_workname"];
// 	$WrkShortName	= $_POST["txt_shortname"];
// 	$WrkOrderNo		= $_POST["txt_workorderno"];
// 	$ContName		= $_POST["txt_cont_name"];
// 	$ContID		    = $_POST["txt_cont_id"];
// 	$WrkOrderAmt	= $_POST["txt_hid_woamt"];
// 	$AggrementNo	= $_POST["txt_agreementno"];
// 	$AggrementDt	= dt_format($_POST["txt_aggrementdate"]);
// 	$HoaIdStr		= $_POST["cmb_hoa"];
// 	$HoaId   	    = implode(",",$HoaIdStr);
// 	$contbid		= $_POST["bank_checkbox"];
// 	$BankDetChkBox  = implode(",",$contbid);
// 	$GlobID			= $_POST["txt_hid_glob_id"];
// 	$GSTRateonWO	= $_POST["txt_gst_value"];
// 	$LCESSAppRad	= $_POST["lcesapp"];
// 	$GSTIncExRad	= $_POST["gstincexc"];
// 	$IsGSTRad		= $_POST["gstapplicable"];
// 	$EnggId	        = trim($_POST['txt_staffid']);
// 	$CheckPt		= $_POST["txt_hid_checkpt"];
// 	$CompCodeNo		= $_POST["txt_computercodeno"];
// 	$WrkOrderDate	= dt_format($_POST["workorderdate"]);
// 	$WrkCommDate	= dt_format($_POST["workcommencedate"]);
// 	$WrkDuration	= $_POST["workduration"];
// 	$RebPercentage	= $_POST["txt_rebatepercent"];
// 	$DOCompletion	= dt_format($_POST["txt_dateofcompletion"]);
// 	$SDper 	      = trim($_POST['txt_sd_per']);
// 	$SDValue       = trim($_POST['txt_hid_sd_value']);

// 	$WorkTypeRad	= $_POST["worktype"];

// 	if($TrNo == NULL){
// 		$msg = "Please Select Project Title..!!";
// 	}else if($WrkName == NULL){
// 		$msg = "Please Enter Work Name..!!";
// 	}else if($WrkShortName == NULL){
// 		$msg = "Please Enter Technical Sanction Number..!!";
// 	}else if($WrkOrderNo == NULL){
// 		$msg = "Please Enter Technical Sanction Amount..!!";
// 	}else if($WrkOrderAmt == NULL){
// 		$msg = "Please Enter Work Order Amount..!!";
// 	}else if($CompCodeNo == NULL){
// 		$msg = "Please Enter Computer Code Number..!!";
// 	}else if($AggrementNo == NULL){
// 		$msg = "Please Enter Agreement Number..!!";
// 	}else if($WrkOrderDate == NULL){
// 		$msg = "Please Enter Work Order Date..!!";
// 	}//else if($WorkTypeRad == NULL){
// 		//$msg = "Please Select Work Type..!!";
// 	//}
// 	else if($GSTRateonWO == NULL){
// 		$msg = "Please Enter GST Rate Percentage on Work Order..!!";
// 	}else if($GSTIncExRad == NULL){
// 		$msg = "Please Select GST Incusive/Exclusive..!!";
// 	}else if($WrkDuration == NULL){
// 		$msg = "Please Enter Work Duration..!!";
// 	}else if($LCESSAppRad == NULL){
// 		$msg = "Please Select LCESS Applicable Yes/No..!!";
// 	}else if($WrkCommDate == NULL){
// 		$msg = "Please Enter Work Commencement Date..!!";
// 	}else if($DOCompletion == NULL){
// 		$msg = "Please Enter Scheduled Work Commencement Date..!!";
// 	}//else if($RebPercentage == NULL){
// 		//$msg = "Please Enter Rebate Percentage..!!";
// 	//}
// 	else{
// 		$InQueryCon = 1;
// 	}

// 	if($InQueryCon == 1){
// 		if($CheckPt == 1){echo 1;exit;
// 			$SheetQuery	= "UPDATE sheet SET tr_id='$TrNo', globid ='$GlobID', work_name='$WrkName', short_name='$WrkShortName', work_order_no='$WrkOrderNo', 
// 			agree_no='$AggrementNo', agree_date='$AggrementDt', computer_code_no='$CompCodeNo', work_order_date='$WrkOrderDate', work_order_cost='$WrkOrderAmt',
// 			work_commence_date='$WrkCommDate',hoaid='$HoaId', work_duration='$WrkDuration', date_of_completion='$DOCompletion', cbdtid='$BankDetChkBox',  contid='$ContID',
// 			assigned_staff = '$EnggId', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', gst_inc_exc='$GSTIncExRad',
// 			name_contractor='$ContName', active = '1', userid = '$userid', created_date = NOW() WHERE globid = '$GlobID'";
// 			$SheetQuery_sql = mysqli_query($dbConn,$SheetQuery);
// 			$UpdateWorkQuery	= "UPDATE works SET work_name='$WrkName', wo_no='$WrkOrderNo', agmt_no='$AggrementNo',agmt_date='$AggrementDt', 
// 			wo_amount='$WrkOrderAmt', wo_date='$WrkOrderDate', work_commence_date='$WrkCommDate', work_status='WO', work_duration='$WrkDuration', 
// 			date_of_completion='$DOCompletion', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', sd_perc = '$SDper',sd_amt = '$SDValue',
// 			gst_inc_exc='$GSTIncExRad', is_wo_rel='Y', active = '1' WHERE globid = '$GlobID'";
// 		}else{ echo 2;exit;
// 			$SheetQuery	= "INSERT INTO sheet SET tr_id = '$TrNo', globid = '$GlobID', work_name = '$WrkName', short_name = '$WrkShortName', work_order_no = '$WrkOrderNo', 
// 			agree_no = '$AggrementNo', agree_date = '$AggrementDt', computer_code_no = '$CompCodeNo', work_order_date = '$WrkOrderDate', work_order_cost = '$WrkOrderAmt',
// 			work_commence_date = '$WrkCommDate',hoaid = '$HoaId', work_duration = '$WrkDuration', date_of_completion = '$DOCompletion', cbdtid = '$BankDetChkBox', contid='$ContID',
// 			assigned_staff = '$EnggId', is_gst_appl = '$IsGSTRad', gst_perc_rate = '$GSTRateonWO', is_less_appl = '$LCESSAppRad', gst_inc_exc = '$GSTIncExRad',active = '1',
// 			userid = '$userid', created_date = NOW()";
// 			$SheetQuery_sql = mysqli_query($dbConn,$SheetQuery);
// 			$LastInsertId 	 = mysqli_insert_id($dbConn);
// 			$UpdateWorkQuery	= "UPDATE works SET sheetid='$LastInsertId', work_name='$WrkName', wo_no='$WrkOrderNo', agmt_no='$AggrementNo',agmt_date='$AggrementDt', 
// 			wo_amount='$WrkOrderAmt', wo_date='$WrkOrderDate', work_commence_date='$WrkCommDate', work_status='WO', work_dsch_comp_dateuration='$WrkDuration', 
// 			date_of_completion='$DOCompletion', is_gst_appl='$IsGSTRad', gst_perc_rate='$GSTRateonWO', is_less_appl='$LCESSAppRad', sd_perc = '$SDper',
// 			sd_amt = '$SDValue', gst_inc_exc='$GSTIncExRad', is_wo_rel='Y', active = '1' WHERE globid = '$GlobID'";
// 				$UpdateWorkQuery_sql = mysqli_query($dbConn,$UpdateWorkQuery);
// 		}
// 		//echo $SheetQuery;exit;
	
// 		if($insert_sql == true){
// 			UpdateWorkTransaction($GlobID,0,0,"W","Work Order Created by ".$UserId,"");
// 			$msg = "Work Order Successfully Created..!!";
// 		}
// 	}
// }
if(isset($_GET['sheet_id'])){   
	$SheetID 	 = $_GET['sheet_id'];
	$ContArr  	 =  array();
	$ContNameArr = array();
	$GlobID= '';
	$GlobIDQuery = "SELECT * FROM sheet WHERE sheet_id = '$SheetID'";
	$GlobIDSql 	= mysqli_query($dbConn,$GlobIDQuery);
	if($GlobIDSql == true){
		if(mysqli_num_rows($GlobIDSql)>0){
			$List = mysqli_fetch_object($GlobIDSql);
			$GlobTsID    = $List->globid;
			$TrId        = $List->tr_id;
			$Tennum      = $List->ts_no;
			$CCnum       = $List->computer_code_no;
			$WorkNo      = $List->work_order_no;
			$WorkName    = $List->work_name;
			$ShortName   = $List->short_name;
			$Workcost    = $List->work_order_cost;
			$WorkDate    =dt_display($List->work_order_date);
			$hoaid       =$List->hoaid; 
			$EICid       =$List->eic; 
			$Contid      =$List->contid; 
			$Contbkid    =$List->cbdtid; 
			$AgreeNo     = $List->agree_no;
			$AgrDate     =dt_display($List->agree_date);
			$WorkDura    =$List->work_duration; 
			$WrokCommd   = dt_display($List->work_commence_date);
			$Schecduledt =dt_display($List->date_of_completion);
			$Isgstapp      =$List->is_gst_appl; 
			$gstinc       =$List->gst_inc_exc; 
			$gst_perc_rate  =$List->gst_perc_rate; 
			$sd_perc        =$List->sd_perc; 
			$is_less_appl    =$List->is_less_appl;
			
		}
	}
	$SelectQuery 	= "SELECT a.staffid,a.staffname,a.staffcode,b.designationname,c.section_name FROM staff a INNER JOIN designation b ON (a.designationid = b.designationid)
	INNER JOIN section_name c on (a.sectionid = c.secid) WHERE a.staffid = '$EICid'";
	 $SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
	 if($SelectSql == true){
	  if(mysqli_num_rows($SelectSql)>0){
		  $List = mysqli_fetch_object($SelectSql);
		  $staffid = $List->staffid;
		  $staffcode = $List->staffcode;
		  $staffname = $List->staffname;
		  $designationname = $List->designationname;
		  $section_name = $List->section_name;
		  
	  }
  }
  $SelectQuery1 	= "SELECT a.*,b.name_contractor  FROM contractor_bank_detail a 
						INNER JOIN contractor b ON (a.contid = b.contid) 
						WHERE a.contid='$Contid' AND a.cbdtid='$Contbkid' ";
	 $SelectSql 	 	= mysqli_query($dbConn,$SelectQuery1);
	 if($SelectSql == true){
	  if(mysqli_num_rows($SelectSql)>0){
		  $List = mysqli_fetch_object($SelectSql);
		  $Contname = $List->name_contractor;
		  $cbdtid = $List->cbdtid;
		  $bank_name = $List->bank_name;
		  $bank_acc_no = $List->bank_acc_no;
		  $branch_address = $List->branch_address;
		  $ifsc_code = $List->ifsc_code;
		  
	  }
   }		//print_r($HoaArr);exit;
	
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">

<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function ViewTSList(){
		url = "WorkOrderList.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }


	function blink_text() {
		$('.blink').fadeOut(500);
		$('.blink').fadeIn(500);
	}
	$("#workorderdate").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		maxDate: new Date,
		defaultDate: new Date,
	});
	$("#workcommencedate").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		//maxDate: new Date,
		defaultDate: new Date,
	});

	$.fn.validateworkorderdateformat = function(event) {
		var wodate = $("#workorderdate").val(); 
		if(wodate !=""){ 
			if(isDate(wodate)==false){
				var a="Work Order Date format should be dd/mm/yyyy";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
			if(isDate(wodate)==true){
				var a="";
				//$('#workorderdate_format').text(a);
			}
		}else{
			var a="";
			$('#workorderdate_format').text(a);
		}
	}
	$.fn.validatedateofcompletionformat = function(event) {
		var doc = $("#txt_dateofcompletion").val(); 
		if(doc !=""){ 
			if(isDate(doc)==false){
				var a="Scheduled Completion Date format should be dd/mm/yyyy";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
			if(isDate(doc)==true){
				var a="";
				//$('#dateofcompletion_format').text(a);
			}
		}else{
			var a="";
			//$('#dateofcompletion_format').text(a);
		}
	}
	$.fn.validatecommencementformat = function(event) {
		var wod = $("#workcommencedate").val(); 
		if(wod !=""){ 
			if(isDate(wod)==false){
				var a="Work Commence Date format should be dd/mm/yyyy";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
			if(isDate(wod)==true){
				var a="";
				//$('#workcommencedate_format').text(a);
			}
		}else{
			var a="";
			//$('#workcommencedate_format').text(a);
		}
	}
	
	$.fn.checkDate = function(event) { 
		var dateofcompletion = $("#txt_dateofcompletion").val();
		var workorderdate = $("#workorderdate").val();
		if((dateofcompletion != "") && (workorderdate != "")){  
			var d1 = workorderdate.split("/");
			var d2 = dateofcompletion.split("/");
			var woddate = new Date(d1[2], d1[1]-1, d1[0]);
			var docdate = new Date(d2[2], d2[1]-1, d2[0]);
			if(woddate>docdate){
				var a="Date of Completion should be greater than Work Order Date";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				//$('#val_date').text(a);
			}
		}
	}
			
	$.fn.checkDate2 = function(event) { 
		var dateofcompletion = $("#txt_dateofcompletion").val();
		var workorderdate = $("#workorderdate").val();
		var workcommencedate = $("#workcommencedate").val();
		if((dateofcompletion != "") && (workorderdate != "") && (workcommencedate != "")){  
			var d1 = workorderdate.split("/");
			var d2 = dateofcompletion.split("/");
			var d3 = workcommencedate.split("/");
			var woddate = new Date(d1[2], d1[1]-1, d1[0]);
			var docdate = new Date(d2[2], d2[1]-1, d2[0]);
			var dcmdate = new Date(d3[2], d3[1]-1, d3[0]);
			if(dcmdate<woddate){
				var a="Date of Commencement should be greater than or equal to Work Order Date";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}else if(dcmdate > docdate){
				var a="Date of Commencement should be less than Completion Date";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				//$('#val_date').text(a);
			}
		}
	}
			
	$.fn.FindSchduleDOC = function(event) { 
		var workduration = $("#workduration").val();
		//var workorderdate = $("#workorderdate").val();
		var workcommencedate = $("#workcommencedate").val();
		$("#txt_dateofcompletion").val("");
		if((workduration != "") && (workcommencedate != "")){  
			var d1 = workcommencedate.split("/");
			workduration = Number(workduration);
			var woddate = new Date(d1[2], d1[1]-1+workduration, d1[0]-1);
			//var SchDOC = woddate.getDate() + '/' + (woddate.getMonth() + 1) + '/' +  woddate.getFullYear();
			var sDate 	= woddate.getDate();
			var sMonth 	= woddate.getMonth()+1;
			var sYear 	= woddate.getFullYear();
			if (sDate < 10){ sDate = '0' + sDate; }
			if (sMonth < 10){ sMonth = '0' + sMonth; }
			var SchDOC = sDate + '/' + sMonth + '/' +  sYear;
			$("#txt_dateofcompletion").val(SchDOC);
		}
	}
	$("#workcommencedate").change(function(event){
		$(this).FindSchduleDOC(event);
	});	
	$("#workduration").keyup(function(event){
		$(this).FindSchduleDOC(event);
	});	
	$("#workduration").keydown(function(e) {
		var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67; //alert(e.keyCode);
		if (ctrlDown || e.keyCode == vKey || e.keyCode == cKey){
			return false;
		}else{
			return true;
		}
	});
	$("#top").submit(function(event){
		$(this).checkDate(event);
		$(this).checkDate2(event);
		$(this).validateworkorderdateformat(event);
		$(this).validatedateofcompletionformat(event);
		$(this).validatecommencementformat(event);
	});
	setInterval(blink_text, 1000);
</script>	
<style>
	.input-disabled{
		background-color:#DCDCE3;
	}
	.btn:hover {
	color: #b8b1d1ed;
	text-decoration: none;
		text-decoration-line: none;
		text-decoration-style: solid;
		text-decoration-color: currentcolor;
}
	
</style>
	<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="WorkOrderConfirmation.php" method="post" enctype="multipart/form-data" name="form" id="form1">
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
									<div class="div1">&nbsp;</div>
									<div class="div10">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card"  style="text-align:center; line-height:2.3">
													Work Order Details Entry
													<!-- <a data-url="WorkStatusList" class="btn" id="AddNewBtn" align="right" style="float:right;background-color: #631C89; border: 2px solid #51027B;padding: 3px 4px; font-size: 13px;cursor: pointer;border: 2px solid #8346A3;  font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;!important"> 
														View Work List
													</a> -->
												</div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														
															
															
															
															
																<div class="row clearrow"></div>												
																<div class="row">
																	<div class="div3 lboxlabel">Tender No.</div>
																	<div class="div9">
																		<select id="cmb_tr_no" name="cmb_tr_no" class="tboxclass">
																			<option value="">--------------- Select --------------- </option>
																			<?php echo $objBind->BindWorkOderNo($TrId); ?>
																		</select>
																	</div>
																	<div class="row clearrow"></div>
																	
																	<div class="div3 lboxlabel">Name of Work</div>
																	<div class="div9">
																		<textarea name='txt_workname' readonly="" maxlength="2500" class="tboxsmclass" id='txt_workname' required rows="2"><?php if (isset($_GET['sheet_id'])){ echo $WorkName; } ?></textarea>
																	</div>
																	<div class="row clearrow"></div>
																	
																	<div class="div3 lboxlabel">Short Name</div>
																	<div class="div9">
																		<input type="text" class="tboxsmclass" maxlength="2000" name='txt_shortname' required id='txt_shortname' value="<?php if (isset($_GET['sheet_id'])){ echo $ShortName; } ?>">
																		<input type="hidden" class="tboxsmclass" name='hidd_sheetID' required id='hidd_sheetID' value="<?php if (isset($_GET['sheet_id'])){ echo $SheetID; } ?>">
																		<input type="hidden" class="tboxsmclass"  name='hidd_GlobID' required id='hidd_GlobID' value="<?php if (isset($_GET['sheet_id'])){ echo $GlobTsID; } ?>">
																	</div>
																	<div class="row clearrow"></div>
																	
																	<div class="div3 lboxlabel">Contractor Name</div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass" maxlength="150" name='txt_cont_name' required id='txt_cont_name' value="<?php if (isset($_GET['sheet_id'])){ echo $Contname; } ?>">
																		<input type="hidden" class="tboxsmclass" maxlength="150" name='txt_cont_id' required id='txt_cont_id' value="<?php if (isset($_GET['sheet_id'])){ echo $Contid; } ?>">
																	</div>
																	<div class="div2 cboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HOA Code</div>
																	<?php
																		if($SheetID!=null){
																	?>
																	<div class="div3">
																		<select name='cmb_hoa[]' id='cmb_hoa' class="tboxsmclass" readonly multiple="multiple" >
																			<?php
																			$Exphoa = explode(",",$hoaid);
																			$selectquery 	= "SELECT new_hoa_no,hoamast_id FROM hoa_master WHERE hoamast_id IN ($hoaid) AND active = 1";
																			$selectsql 		= mysqli_query($dbConn,$selectquery);
																			while($row = mysqli_fetch_array($selectsql)){
																				if (in_array($row["hoamast_id"], $Exphoa))
																					$sel = "selected";
																				else
																						$sel = "";
																				?>
																			<option value="<?php echo $row['hoamast_id'];?>" <?php echo $sel; ?>><?php echo $row['new_hoa_no']; ?></option>
																			<?php
																			}
																			?>
																		</select>
																	</div>
																	<?php
																		}else{
																	?>
																	<div class="div3">
																		<select name='cmb_hoa[]' id='cmb_hoa' class="tboxsmclass" multiple="multiple" >
																		</select>
																	</div>
																	<?php
																	 }
																	?>
																	<div class="row clearrow"></div>
																	
																	
																	
																	<div class="card-header isappcheck inkblue-card" align="left">&nbsp;Work Order Details</div>
																	<table class="dataTable isappcheck" align="center" width="100%" id="table1">
																		<tr class="label" style="background-color:#FFF">
																			<td align="center">Work Order No.</td>
																			<td align="center">Work Order</br>Date</td>
																			<td align="center">Work Order Amount</td>
																			<td align="center">&nbsp;&nbsp; CCNO. &nbsp;&nbsp;</td>
																			<td align="center">Work Duration</br>(Months)</td>
																			<td align="center">Date of</br>Commencement</td>
																			<td align="center">Scheduled Date of</br>Completion</td>
																		</tr>
																		<tr>
																			<td align="center" style="width:70%"><input type="text" maxlength="100"  class="tboxsmclass" name="txt_workorderno" id="txt_workorderno" value="<?php if (isset($_GET['sheet_id'])){ echo $WorkNo; } ?>"></td>
																			<td align="center" style="width:50%"><input type="text" readonly="" class="tboxsmclass datepicker" style="text-align:center;" name="workorderdate" id="workorderdate"  value="<?php if (isset($_GET['sheet_id'])){echo$WorkDate;} ?>"></td>
																			<td align="center" style="width:50%"><input type="text" maxlength="12" class="tboxsmclass" style="text-align:right;" readonly="" name="txt_workorderamt" id="txt_workorderamt" onKeyPress="return event.charCode >= 48 && event.charCode <= 57" value="<?php if (isset($_GET['sheet_id'])){ echo$Workcost; } ?>"></td>
																			<td align="center" style="width:100%"><input type="text"readonly="" maxlength="50" class="tboxsmclass" style="text-align:center;"  name="txt_computercodeno" id="txt_computercodeno" value="<?php if (isset($_GET['sheet_id'])){ echo$CCnum; } ?>"></td>
																			
																			<td align="center"><input type="text" class="tboxsmclass" maxlength="11" readonly="" style="text-align:center;" name="workduration" id="workduration" onKeyPress="return isIntegerValueWithLimit(event,this,2);"  value="<?php if (isset($_GET['sheet_id'])){echo$WorkDura;} ?>"></td>
																			<td align="center"><input type="text" class="tboxsmclass datepicker" readonly="" style="text-align:center;" name="workcommencedate" id="workcommencedate"  value="<?php if (isset($_GET['sheet_id'])){ echo$WrokCommd;} ?>"></td>
																			<td align="center"><input type="text" class="tboxsmclass datepicker" readonly="" style="text-align:center;" name="txt_dateofcompletion" id="txt_dateofcompletion" value="<?php if (isset($_GET['sheet_id'])){ echo$Schecduledt;} ?>"></td>
																		</tr>
																	</table>
																</div>
																
																
																<div class="row clearrow"></div>
																<div class="card-header inkblue-card" align="left">Agreement Details Entry </div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel" align="center">Agreement No. </div>
																<div class="div4">
																	<input type="text" <?php if (!isset($_GET['sheet_id'])) { echo "readonly"; } ?>  class="tboxsmclass" maxlength="50" name='txt_agreementno' required id='txt_agreementno' value="<?php if (isset($_GET['sheet_id'])){ echo $AgreeNo; } ?>">
																</div>
																<div class="div2 cboxlabel" align="center">&nbsp;&nbsp;Agreement Date &nbsp;&nbsp;</div>
																<div class="div4">
																  <input type="text" readonly="" class="tboxsmclass datepicker" name='txt_aggrementdate' required id='txt_aggrementdate' value="<?php if (isset($_GET['sheet_id'])){ echo $AgrDate; } ?>">
																</div>
																<div class="row clearrow"></div>
																<div class="card-header inkblue-card" align="left">Engineer Details Entry </div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Engineer IC No.</div>
																<div class="div4">
																	<input type="text" readonly="" maxlength="100" class="tboxsmclass" name='txt_ICNO' required id='txt_ICNO'  value="<?php if (isset($_GET['sheet_id'])){ echo $staffcode; } ?>">
																	<input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="<?php if (isset($_GET['sheet_id'])){ echo $staffid; } ?>" >
																	<input type="hidden" class="tboxsmclass" name='txt_hid_checkpt' id='txt_hid_checkpt' value="" >
															   </div>
																<div class="div2 cboxlabel">Engineer Name</div>
																<div class="div4">
																	<input type="text" class="tboxsmclass" name='txt_enggname' required id='txt_enggname' readonly  value="<?php if (isset($_GET['sheet_id'])){ echo $staffname; } ?>">
																</div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Engineer Designation</div>
																<div class="div4">
																	<input type="text" class="tboxsmclass" name='txt_enggdesig' required id='txt_enggdesig' readonly  value="<?php if (isset($_GET['sheet_id'])){ echo $designationname; } ?>">
																</div>
																<div class="div2 cboxlabel">Engineer Group</div>
																 <div class="div4">
																	 <input type="text" class="tboxsmclass" name='txt_enggroup' required id='txt_enggroup' readonly  value="<?php if (isset($_GET['sheet_id'])){ echo $section_name; } ?>">
																</div>
																<div class="row clearrow"></div>
																	
																<div class="Details" id="ContDet">
																	<div class="card-header inkblue-card" align="left">&nbsp;Contractor Bank Details</div>
																	<table class="dataTable" align="center" width="100%" id="table0">
																		<tr class="label" style="background-color:#FFF">
																			<td align="center">&nbsp;</td>
																			<!--<td align="center">Account Holder Name</td>-->
																			<td align="center">Bank Name</td>
																			<td align="center">Branch Address</td>
																			<td align="center">Account Number</td>
																			<td align="center">IFSC Code</td>
																		</tr>
																		<?php
																		if($SheetID!=null){
																		?>
																		<tr>
																			<td align="center"><input type="checkbox" class="tboxsmclass" checked="checked" name="bank_checkbox[]" id="bank_checkbox" value="<?php if (isset($_GET['sheet_id'])){ echo $cbdtid; } ?>"></td>
																			<!--<td align="center"><input type="text" class="tboxsmclass" name="txt_acc_hold_name_0" id="txt_acc_hold_name" readonly="" value="<?php if (isset($_GET['sheet_id'])){ echo $Contname; } ?>"></td>-->
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_name_0" id="txt_bank_name" onKeyPress="return event.charCode >= 48 && event.charCode <= 57"value="<?php if (isset($_GET['sheet_id'])){ echo $bank_name; } ?>"></td>
																			<td align="center"><input type="text" class="tboxsmclass" name="txt_bank_branch_0" id="txt_bank_branch" readonly=""value="<?php if (isset($_GET['sheet_id'])){ echo $branch_address; } ?>"></td>
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_accno_0" id="txt_bank_accno"value="<?php if (isset($_GET['sheet_id'])){ echo $bank_acc_no; } ?>"></td>
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_ifsc_0" id="txt_bank_ifsc"value="<?php if (isset($_GET['sheet_id'])){ echo $ifsc_code; } ?>"></td>
																		</tr>
																	<?php }else{ ?>
																		<tr>
																			<td align="center"><input type="checkbox" class="tboxsmclass"  name="bank_checkbox[]" id="bank_checkbox" value=""></td>
																			<!--<td align="center"><input type="text" class="tboxsmclass" name="txt_acc_hold_name_0" id="txt_acc_hold_name" readonly="" value=""></td>-->
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_name_0" id="txt_bank_name" onKeyPress="return event.charCode >= 48 && event.charCode <= 57"value=""></td>
																			<td align="center"><input type="text" class="tboxsmclass" name="txt_bank_branch_0" id="txt_bank_branch" readonly=""value=""></td>
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_accno_0" id="txt_bank_accno"value=""></td>
																			<td align="center"><input type="text" class="tboxsmclass" readonly="" name="txt_bank_ifsc_0" id="txt_bank_ifsc"value=""></td>
																		</tr>
																	
																	<?php } ?>
																 	</table>
																</div>
															
																<div class="row clearrow"></div>
																<div class="card-header inkblue-card" align="left">GST Details Entry </div>
																<div class="row clearrow"></div>
																<?php 
																if(isset($_GET['sheet_id']) && isset($Isgstapp)){ 
																	if($Isgstapp == 'Y'){
																		$YStr    = 'checked="checked"';
																		$NStr  = '';
																	}else if($Isgstapp == 'N'){
																		$NStr  = 'checked="checked"';
																		$YStr    = '';
																	}else{ 
																		$YStr    = '';
																		$NStr  = '';
																	}
																}  
																?>
																<div class="div2 lboxlabel">GST applicaple ?</div>
																<div class="div2 no-padding-lr gstselapp">
																   <div class="inputGroup" style="width:90%">
																	<input id="gst_app_yes" name="gstapplicable" type="radio" value="Y" <?php if(isset($YStr)){ echo $YStr; } ?>/>
																	<label for="gst_app_yes" style="padding:1px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; YES</label>
																  </div>
																</div>
																<div class="div2 gstselapp" style="padding-left:5px;">
																	<div class="inputGroup" style="width:90%">
																		<input id="gst_app_no" name="gstapplicable" type="radio" value="N" <?php if(isset($NStr)){ echo $NStr; } ?>/>
																		<label for="gst_app_no" style="padding:1px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; NO</label>
																   </div>
																</div>
																<div class="div2 cboxlabel" style="text-align:right;">L.CESS Applicable</div>
																<?php 
																if(isset($_GET['sheet_id']) && isset($is_less_appl)){ 
																	if($is_less_appl == 'Y'){
																		$YStr    = 'checked="checked"';
																		$NStr  = '';
																	}else if($is_less_appl == 'N'){
																		$NStr  = 'checked="checked"';
																		$YStr    = '';
																	}else{ 
																		$YStr    = '';
																		$NStr  = '';
																	}
																}  
																 ?>
																<div class="div2 no-padding-lr"  style="padding-left:10px;">
																	<div class="inputGroup" style="width:90%">
																		<input id="lcesapp_y" name="lcesapp" type="radio" value="Y"<?php if(isset($YStr)){ echo $YStr; } ?>/>
																		<label for="lcesapp_y" style="padding:1px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; YES</label>
																	</div>
																</div>
																<div class="div2" style="padding-left:5px;">
																	<div class="inputGroup" style="width:90%">
																		<input id="lcesapp_n" name="lcesapp" type="radio" value="N"<?php if(isset($NStr)){ echo $NStr; } ?>/>
																		<label for="lcesapp_n" style="padding:1px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; NO</label>
																	</div>
																 </div>
																<?php 
																if($is_less_appl == 'Y'){ 
																?>
																<div class="row ">
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel ">GST Rate %</div>
																	<div class="div2 ">
																	<input type="text" class="tboxsmclass" maxlength="10" onKeyPress="return event.charCode >= 48 && event.charCode <= 57" name='txt_gst_value' id='txt_gst_value' value="<?php if(isset($_GET['sheet_id'])){ echo $gst_perc_rate; } ?>">
																	</div>
																	<?php 
																	if(isset($_GET['sheet_id']) && isset($gstinc)){ 
																		if($gstinc == 'I'){
																			$YStr    = 'checked="checked"';
																			$NStr  = '';
																		}else if($gstinc == 'E'){
																			$NStr  = 'checked="checked"';
																			$YStr    = '';
																		}else{ 
																			$YStr    = '';
																			$NStr  = '';
																		}
																	}  
																	?>
																	<div class="div3 lboxlabel"> &nbsp;&nbsp;GST Inclusive/Exclusive </div>
																	<div class="div2 no-padding-lr ">
																		<div class="inputGroup">
																		<input id="gst_inc" name="gstincexc" type="radio" value="I"<?php if(isset($YStr)){ echo $YStr; } ?>/>
																			<label for="gst_inc" style="padding:1px 0px 0px 10px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; INCLUSIVE</label>
																		</div>
																	</div>
																	<div class="div2" style="width:15%;">
																		<div class="inputGroup">
																		<input id="gst_exc" name="gstincexc" type="radio" value="E"<?php if(isset($NStr)){ echo $NStr; } ?>/>
																			<label for="gst_exc" style="padding:1px 15px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; EXCLUSIVE</label>
																		</div>
																	</div>
																</div>
																<?php 
																	}else{
																?>
																<div class="row ">
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel gstapplicab">GST Rate %</div>
																	<div class="div2 gstapplicab">
																		<input type="text" class="tboxsmclass" maxlength="10" onKeyPress="return event.charCode >= 48 && event.charCode <= 57" name='txt_gst_value' id='txt_gst_value' value="<?php if(isset($_GET['sheet_id'])){ echo $gst_perc_rate; } ?>">
																	</div>
															
																	<div class="div2 cboxlabel gstapplicab">GST Incl. / Excl. ? </div>
																	<div class="div3 no-padding-lr gstapplicab">
																		<div class="inputGroup">
																			<input id="gst_inc" name="gstincexc" type="radio" value="I"<?php if(isset($YStr)){ echo $YStr; } ?>/>
																			<label for="gst_inc" style="padding:1px 0px 0px 10px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; INCLUSIVE</label>
																		</div>
																	</div>
																	<div class="div3 gstapplicab">
																		<div class="inputGroup">
																			<input id="gst_exc" name="gstincexc" type="radio" value="E"<?php if(isset($NStr)){ echo $NStr; } ?>/>
																			<label for="gst_exc" style="padding:1px 15px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; EXCLUSIVE</label>
																		</div>
																	</div>
																</div>
																<?php 
																}
																?>
																<input type="hidden" class="tboxsmclass" name='txt_hid_glob_id' id='txt_hid_glob_id'>
																<div class="row clearrow "></div>

																<div class="card-header isappcheck inkblue-card" align="left">Other Recovery Details Entry </div>
																<div class="row clearrow"></div>
																<div class="div2 lboxlabel">Security Deposit (%)</div>
																<div class="div1">
																	<input type="text" readonly="" maxlength="10" class="tboxsmclass" name='txt_sd_per' required id='txt_sd_per' onKeyPress="return isPercentageValue(event,this);" value="<?php if (isset($_GET['sheet_id'])){ echo $sd_perc; } ?>">
																	<input type="hidden" readonly="" class="tboxsmclass" name='txt_hid_sd_value' required id='txt_hid_sd_value' value="">
																</div>
																<div class="div1">&nbsp;</div>

																<!-- <div class="div2 cboxlabel">Total SD Value(&#8377;)</div>
																<div class="div2">
																	<input type="text" style="text-align:right;" maxlength="50" readonly="" class="tboxsmclass" name='txt_sd_value' required id='txt_sd_value' value="">
																	<input type="hidden" readonly="" class="tboxsmclass" name='txt_hid_sd_value' required id='txt_hid_sd_value' value="">
																</div> -->
																<!-- <div class="div1">&nbsp;</div>
																<div class="div1 cboxlabel">Upto Date SD</div>
																<div class="div2">
																		<input type="text" readonly="" style="text-align:right;" class="tboxsmclass" name='txt_securitydepoe'  id='txt_securitydepoe' value="">
																</div>
<!-- 																		 -->
																
																<!-- <div class="row clearrow"></div>
																<div class="div2 lboxlabel">Last Payment Date</div>
																<div class="div3">
																	<input type="text" readonly="" class="tboxsmclass datepicker" name='txt_paymentdate'  id='txt_paymentdate' value="">
																</div>
																<div class="div1">&nbsp;</div>

																<div class="div2 lboxlabel">Upto Date Value of Work</div>
																<div class="div4">
																	<input type="text" readonly="" style="text-align:right;" class="tboxsmclass" name='txt_valuework'  id='txt_valuework' value="">
																</div>
															 -->
																<!--<div class="div2 lboxlabel">Work Order No.</div>
																<div class="div3">
																	<input type="text" class="tboxsmclass" name='txt_workorderno' required id='txt_workorderno' value="<?php //if (isset($_GET['sheet_id'])){ echo $work_order_no; } ?>">
																</div>

																<div class="div3 lboxlabel">&nbsp;&nbsp;Work Order Amount</div>
																<div class="div4">
																	<input type="text" readonly="" class="tboxsmclass" name='txt_workorderamt' required id='txt_workorderamt' value="<?php //if (isset($_GET['sheet_id'])){ echo $work_order_no; } ?>">
																</div>
																
																<div class="row clearrow"></div>


																<div class="div2 lboxlabel">CC No.</div>
																<div class="div3">
																	<input type="text" readonly="" class="tboxsmclass" name='txt_computercodeno' required id='txt_computercodeno' value="<?php //if (isset($_GET['sheet_id'])){ echo $computer_code_no; } ?>">
																</div>
																<div class="div3 lboxlabel">&nbsp;&nbsp;Rebate ( % )</div>
																<div class="div4">
																	<input type="text" class="tboxsmclass" name='txt_rebatepercent' max="100" required onKeyPress="return isPercentageValue(event,this);" id='txt_rebatepercent' value="<?php //if (isset($_GET['sheet_id'])){ echo $rebatepercent; } else { echo '0.00'; } ?>">
																	<input type="hidden" class="tboxsmclass" name='txt_hid_glob_id' id='txt_hid_glob_id'>
																</div>
																
																<div class="row clearrow"></div>

																<div class="div2 lboxlabel" align="center">Agreement No. </div>
																<div class="div3">
																	<input type="text" class="tboxsmclass" name='txt_agreementno' required id='txt_agreementno' value="<?php //if (isset($_GET['sheet_id'])){ echo $agree_no; } ?>">
																</div>
																<div class="div2 lboxlabel" align="center">&nbsp;&nbsp;Aggrement Date </div>
																<div class="div1">&nbsp;</div>
																<div class="div3">
																	<input type="text" readonly="" class="tboxsmclass datepicker" name='txt_aggrementdate' required id='txt_aggrementdate' value="<?php //if (isset($_GET['sheet_id'])){ echo $work_order_date; } ?>">
																</div>
																<div class="row clearrow"></div>

																<div class="div2 lboxlabel" align="center">Work Order Date </div>
																<div class="div3">
																	<input type="text" readonly="" class="tboxsmclass datepicker" name='workorderdate' required id='workorderdate' value="<?php //if (isset($_GET['sheet_id'])){ echo $work_order_date; } ?>">
																</div>
																
																<div class="div2 lboxlabel">&nbsp;&nbsp;Work Type</div>
																<div class="div1">&nbsp;</div>
																<div class="div2 no-padding-lr">
																	<div class="inputGroup">
																		<input id="worktype_major" name="worktype" type="radio" value="1" <?php //if(isset($worktype)){ if($worktype == '1') {echo 'checked="checked"'; }} ?>/>
																		<label for="worktype_major" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel"> &nbsp;MAJOR WORKS</label>
																	</div>
																</div>
																<div class="div2" style="padding-left:10px;">
																	<div class="inputGroup">
																		<input id="worktype_minor" name="worktype" type="radio" value="2" <?php //if(isset($worktype)){if($worktype == '2'){ echo 'checked="checked"'; }} ?>/>
																		<label for="worktype_minor" style="padding:3px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp;MINOR WORKS</label>
																	</div>
																</div>
																<div class="row clearrow"></div>


																<div class="div2 lboxlabel">GST Rate on Work Order %</div>
																<div class="div3">
																	<input type="text" class="tboxsmclass" name='txt_gst_value' required id='txt_gst_value' value="">
																</div>
																<div class="div3 lboxlabel">&nbsp;&nbsp;whether GST Inclusive/Exclusive </div>
																<div class="div2 no-padding-lr">
																	<div class="inputGroup">
																		<input id="gst_inc" name="gstincexc" type="radio" value="I"/>
																		<label for="gst_inc" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel"> &nbsp; INCLUSIVE</label>
																	</div>
																</div>
																<div class="div2" style="padding-left:10px;">
																	<div class="inputGroup">
																		<input id="gst_exc" name="gstincexc" type="radio" value="E"/>
																		<label for="gst_exc" style="padding:3px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; EXCLUSIVE</label>
																	</div>
																</div>
																<div class="row clearrow"></div>

																
																<div class="div2 lboxlabel" align="center">Duration of Work</div>
																<div class="div1">
																	<input type="text" readonly="" class="tboxsmclass" name='workduration' id='workduration' required onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="<?php if (isset($_GET['sheet_id'])){ echo $work_duration; } ?>">
																</div>
																<div class="div2" align="left"><span style="font-size:10px">&nbsp;Months</span> <span style="font-size:10px">(Max. 3 digit)</span></div>
																
																<div class="div2 lboxlabel">&nbsp;&nbsp;LCESS Applicable </div>
																<div class="div1">&nbsp;</div>
																<div class="div2 no-padding-lr">
																	<div class="inputGroup">
																		<input id="lcesapp_y" name="lcesapp" type="radio" value="Y"/>
																		<label for="lcesapp_y" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel"> &nbsp; YES</label>
																	</div>
																</div>
																<div class="div2" style="padding-left:10px;">
																	<div class="inputGroup">
																		<input id="lcesapp_n" name="lcesapp" type="radio" value="N"/>
																		<label for="lcesapp_n" style="padding:3px 0px; width:100%; font-size:11px;" class="cboxlabel"> &nbsp; NO</label>
																	</div>
																</div>
																<div class="row clearrow"></div>
																
																<div class="div2 lboxlabel">Date of commencement</div>
																<div class="div3">
																	<input type="text" readonly="" class="tboxsmclass datepicker" name='workcommencedate' required id='workcommencedate' value="<?php if (isset($_GET['sheet_id'])){ echo $work_commence_date; } ?>">
																</div>
																<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Scheduled commencement Date</div>
																<div class="div4">
																	<input type="text" class="tboxsmclass datepicker" name='txt_dateofcompletion' required id='txt_dateofcompletion' readonly="" value="<?php if (isset($_GET['sheet_id'])){ echo $date_of_completion; } ?>">
																</div>
																<div class="row clearrow"></div>-->


																<div class="div12 lboxlabel" style="text-align:left;" id="notediv"><span style="color:red;" class="blink">Note : Measurement's has been started For this Tender.</span></div>
																<div class="row clearrow" id="notediv"></div>

																<div class="row" align="center">
																	<input type="submit" name="btn_save" id="btn_save" class="btn btn-info" value=" Save And Next ">
																	<!-- <input type="button" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="ViewTSList();"/> -->

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
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           	<?php include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script type="text/javascript" language="javascript">
	$(".gstapplicab").hide();

	$('body').on("change","#txt_ts_amount", function(e){ 
		var tsamount = $(this).val();
		//alert (tsamount);
		$.ajax({ 
			type: 'POST', 
			url: 'find_ts_amount.php', 
			data: { tsamount: tsamount}, 
			success: function (data) {  //alert(data);
				$("#cmb_approve_auth").chosen("destroy");
				$("#cmb_approve_auth").val(data);
				$("#cmb_approve_auth").chosen();
				//alert(data);
			}
		});
	});
	$('body').on("change",".gstselapp", function(e){ 
		var checkval = $('input[name="gstapplicable"]:checked').val();
		if(checkval == 'Y'){
			$(".gstapplicab").show();
		}else if(checkval == 'N'){
			$(".gstapplicab").hide();
		}
	});
	/*$('body').on("change","#workorderdate", function(e){ 
		var wodate  = $(this).val(); alert(wodate);
		var splityr = wodate.split("/",3);
		var finyear = splityr[2]; //alert(splityr);
		$.ajax({ 
			type: 'POST',
			url: 'GetCurrYearHoa.php',
			data: { finyear: finyear },
			success: function (data) {
				
				alert(data);
			}
		});
	});*/
	var KillEvent = 0;	
	$("body").on("click","#btn_save", function(event){
		if(KillEvent == 0){
			var TrnoVal 			= $("#cmb_tr_no").val();
			var WorkNameVal 		= $("#txt_workname").val();
			var ShortNameVal 		= $("#txt_shortname").val();
			var ContNameVal 		= $("#txt_cont_name").val(); 
			var ContID 		        = $("#txt_cont_id").val();               
			var HoaNumVal  		    = $('#cmb_hoa > option:selected');//$("#cmb_hoa").val();                    
			var WorkOrderNoVal   	= $("#txt_workorderno").val();
           var WorkOrderDateVal     = $("#workorderdate").val();
			var WorkOrderAmtVal 	= $("#txt_workorderamt").val();
			var CompCodeNoVal 	    = $("#txt_computercodeno").val();
			var WorkDurVal 		    = $("#workduration").val();
			var WorkCommDateVal 	= $("#workcommencedate").val();
			var DateOfCompVal 	    = $("#txt_dateofcompletion").val();
			var AggreNoVal 		    = $("#txt_agreementno").val();
			var AggreDateVal 		= $("#txt_aggrementdate").val();
			var EnggIcnoVal 		= $("#txt_ICNO").val();
			var EnggNameVal 		= $("#txt_enggname").val();
			var EnggDesigVal 		= $("#txt_enggdesig").val();
			var EnggGrpVal 		    = $("#txt_enggroup").val();

			var BKAcHoldNameVal  	= $("#txt_acc_hold_name").val();             
			var BankNameVal  		= $("#txt_bank_name").val();                 
			var BankBranchVal 	    = $("#txt_bank_branch").val();               
			var BankAccNoVal 		= $("#txt_bank_accno").val();                
			var BankIfscVal 		= $("#txt_bank_ifsc").val();                
            var IsGstApply          = $("[name='gstapplicable']:checked").length;
            var IsLcessApply        = $("[name='lcesapp']:checked").length;
             var IsGstIncExc        = $("[name='gstincexc']:checked").length;
			var GstPercVal 		    = $("#txt_gst_value").val();
			var SDPercVal   		= $("#txt_sd_per").val();
			var TotalSDVal 		    = $("#txt_sd_value").val();

			var RebPercVal 		    = $("#txt_rebatepercent").val();
			var WorktypeVal 		= $("#worktype").val();
			var checksavupt		    = $("#txt_hid_checkpt").val();
			var UptoDtSD			= $("#txt_securitydepoe").val();
			var LastDtPmtDt		    = $("#txt_paymentdate").val();
			var UptoDtWrkVal		= $("#txt_valuework").val();                  
           var GstApplCheck 		= $('input[name="gstapplicable"]:checked').val();//length;

			if(checksavupt == 1){
				var msgstr = "Update";
			}else{
				var msgstr = "Save";
			}
			var GstErr = 0; var GstMsg = "";
			if(GstApplCheck == 'Y'){
				if(GstPercVal == ""){
					GstErr++;
					GstMsg = "GST Rate on Work Order should not be empty..!!";
				}else if($('input[name="gstincexc"]:checked').length == 0){
					GstErr++
					GstMsg = "Please Select GST Incusive/Exclusive..!!";
				}
			}

			if(TrnoVal == ""){
				BootstrapDialog.alert("Please select Tender No..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkNameVal == ""){
				BootstrapDialog.alert("Name of Work should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ShortNameVal == ""){
				BootstrapDialog.alert("Short Name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(ContNameVal == ""){
				BootstrapDialog.alert("Contractor Name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(HoaNumVal.length == ""){
				BootstrapDialog.alert("Hoa Number should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderNoVal == ""){
				BootstrapDialog.alert("Work Order No. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderDateVal == ""){
				BootstrapDialog.alert("Work Order Date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkOrderAmtVal == ""){
				BootstrapDialog.alert("Work Order Amount should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(CompCodeNoVal == ""){
				BootstrapDialog.alert("CC No. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkDurVal == ""){
				BootstrapDialog.alert("Duration of Work should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkCommDateVal == ""){
				BootstrapDialog.alert("Date of Commence should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(DateOfCompVal == ""){
				BootstrapDialog.alert(" Scheduled Completion Date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(AggreNoVal == ""){
				BootstrapDialog.alert("Agreement No. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(AggreDateVal == ""){
				BootstrapDialog.alert("Agreement Date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnggIcnoVal == ""){
				BootstrapDialog.alert("Engineer IC Number should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnggNameVal == ""){
				BootstrapDialog.alert("Engineer Name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnggDesigVal == ""){
				BootstrapDialog.alert("Engineer Designation should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(EnggGrpVal == ""){
				BootstrapDialog.alert("Engineer Group should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}/*else if($('input[name="worktype"]:checked').length == 0){
				BootstrapDialog.alert("Please Select Work Type..!!");
				event.preventDefault();
				event.returnValue = false;
			}*/
			/* else if(BKAcHoldNameVal == ""){
				BootstrapDialog.alert("Bank Account Holder Name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankNameVal == ""){
				BootstrapDialog.alert("Bank Name should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankBranchVal == ""){
				BootstrapDialog.alert("Bank Branch should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankAccNoVal == ""){
				BootstrapDialog.alert("Bank Account Holder No. should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(BankIfscVal == ""){
				BootstrapDialog.alert("Bank IFSC should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}*/else if ($('input[name="lcesapp"]:checked').length == 0){
				BootstrapDialog.alert("Please Select LCESS Applicable or Not Applicable..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if($('input[name="gstapplicable"]:checked').length == 0){
				BootstrapDialog.alert("Please Select GST Applicable or Not Applicable..!!");
				event.preventDefault();
				event.returnValue = false;
			}/*else if($('input[name="gstapplicable"]:checked').length > 0){
				var GstApplCheck = 1;
				event.returnValue = true;
			}*/else if(SDPercVal == ""){	
				BootstrapDialog.alert(" SD Percentage Should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(TotalSDVal == ""){	
				BootstrapDialog.alert(" Total SD Value should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(UptoDtSD == ""){	
				BootstrapDialog.alert(" Upto Date SD should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(LastDtPmtDt == ""){
				BootstrapDialog.alert(" Last Payment Date should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(UptoDtWrkVal == ""){
				BootstrapDialog.alert(" Upto Date Value of Work should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(GstErr > 0){
				BootstrapDialog.alert(GstMsg);
				event.preventDefault();
				event.returnValue = false;
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to '+msgstr+' this WorkOrder ?',
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
			}/*else if(RebPercVal == ""){
				BootstrapDialog.alert("Rebate ( % ) should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}*/
		}
	});

	// $("body").on("click","#btn_save", function(event){
	// 	if(KillEvent == 0){
	// 		var TrnoVal 			= $("#cmb_tr_no").val();
	// 		var WorkNameVal 		= $("#txt_workname").val();
	// 		var ShortNameVal 		= $("#txt_shortname").val();
	// 		var WorkOrderNoVal 	= $("#txt_workorderno").val();
	// 		var AggreNoVal 		= $("#txt_agreementno").val();
	// 		var GstPercVal 		= $("#txt_gst_value").val();
	// 		var CompCodeNoVal 	= $("#txt_computercodeno").val();
	// 		var WorkOrderDateVal = $("#workorderdate").val();
	// 		var WorkOrderAmtVal 	= $("#txt_workorderamt").val();
	// 		var WorkCommDateVal 	= $("#workcommencedate").val();
	// 		var WorkDurVal 		= $("#workduration").val();
	// 		var RebPercVal 		= $("#txt_rebatepercent").val();
	// 		var DateOfCompVal 	= $("#txt_dateofcompletion").val();
	// 		var WorktypeVal 		= $("#worktype").val();
	// 		var checksavupt		= $("#txt_hid_checkpt").val();
	// 		var UptoDtSD			= $("#txt_securitydepoe").val();
	// 		var LastDtPmtDt		= $("#txt_paymentdate").val();
	// 		var UptoDtWrkVal		= $("#txt_valuework").val();
	// 		if(checksavupt == 1){
	// 			var msgstr = "Update";
	// 		}else{
	// 			var msgstr = "Save";
	// 		}
	// 		if(TrnoVal == ""){
	// 			BootstrapDialog.alert("Please select Tender No..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkNameVal == ""){
	// 			BootstrapDialog.alert("Name of Work should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(ShortNameVal == ""){
	// 			BootstrapDialog.alert("Short Name should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkOrderNoVal == ""){
	// 			BootstrapDialog.alert("Work Order No. should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkOrderAmtVal == ""){
	// 			BootstrapDialog.alert("Work Order Amount should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(CompCodeNoVal == ""){
	// 			BootstrapDialog.alert("CC No. should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(AggreNoVal == ""){
	// 			BootstrapDialog.alert("Agreement No. should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkOrderDateVal == ""){
	// 			BootstrapDialog.alert("Work Order Date should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}/*else if($('input[name="worktype"]:checked').length == 0){
	// 			BootstrapDialog.alert("Please Select Work Type..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}*/
	// 		else if(GstPercVal == ""){
	// 			BootstrapDialog.alert("GST Rate on Work Order should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if ($('input[name="gstincexc"]:checked').length == 0){
	// 			BootstrapDialog.alert("Please Select GST Incusive/Exclusive..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkDurVal == ""){
	// 			BootstrapDialog.alert("Duration of Work should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if ($('input[name="lcesapp"]:checked').length == 0){
	// 			BootstrapDialog.alert("Please Select LCESS Applicable..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(WorkCommDateVal == ""){
	// 			BootstrapDialog.alert("Date of Commence should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(DateOfCompVal == ""){
	// 			BootstrapDialog.alert(" Scheduled Completion Date should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(UptoDtSD == ""){	
	// 			BootstrapDialog.alert(" Upto Date SD should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(LastDtPmtDt == ""){
	// 			BootstrapDialog.alert(" Last Payment Date should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else if(UptoDtWrkVal == ""){
	// 			BootstrapDialog.alert(" Upto Date Value of Work should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}else{
	// 			event.preventDefault();
	// 			BootstrapDialog.confirm({
	// 				title: 'Confirmation Message',
	// 				message: 'Are you sure want to '+msgstr+' this WorkOrder ?',
	// 				closable: false, // <-- Default value is false
	// 				draggable: false, // <-- Default value is false
	// 				btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
	// 				btnOKLabel: 'Ok', // <-- Default value is 'OK',
	// 				callback: function(result) {
	// 					if(result){
	// 						KillEvent = 1;
	// 						$("#btn_save").trigger( "click" );
	// 					}else {
	// 						KillEvent = 0;
	// 					}
	// 				}
	// 			});
	// 		}/*else if(RebPercVal == ""){
	// 			BootstrapDialog.alert("Rebate ( % ) should not be empty..!!");
	// 			event.preventDefault();
	// 			event.returnValue = false;
	// 		}*/
	// 	}
	// });
</script>
<script>
$("#cmb_tr_no").chosen();
$("#cmb_sh_hoa").chosen();
$("#cmb_hoa").chosen();
$("#cmb_worktype").chosen();
$("#notediv").hide();

$(document).ready(function(){
	$("body").on("change","#cmb_tr_no", function(event){		
		var Id = $(this).val(); //alert(Id);
		$("#ContDet").html('');
		$("#btn_save").show();
		$("#notediv").hide();
		$(".gstapplicab").hide();
		$("#txt_cont_name").val('');
		$("#txt_cont_id").val('');
		$("#txt_workname").val('');
		$("#txt_shortname").val('');
		//$("#cmb_hoa").chosen("destroy");
		$("#cmb_hoa").val('').trigger("chosen:updated");
		//$("#cmb_hoa").val('');
		$("#txt_computercodeno").val('');
		$("#txt_workorderamt").val('');
		$("#txt_aggrementdate").val('');
		$("#txt_agreementno").val('');
		$("#txt_dateofcompletion").val('');
		$("#workcommencedate").val('');
		$("#txt_gst_value").val('');
		$("#workduration").val('');
		$("#txt_workorderno").val('');
		$("#workorderdate").val('');
		$("#txt_hid_checkpt").val('');
		$("#txt_ICNO").val('');
		$("#txt_enggname").val('');
		$("#txt_enggdesig").val('');
		$("#txt_enggroup").val('');
		$("#txt_sd_per").val('');
		$("#txt_sd_value").val('');
		$("#txt_hid_sd_value").val('');
		$("#lcesapp_n").prop('checked', false);
		$("#lcesapp_y").prop('checked', false);
		$("#gst_app_yes").prop('checked', false);
		$("#gst_app_no").prop('checked', false);
		$("#gst_inc").prop('checked', false);
		$("#gst_exc").prop('checked', false);
		$('#txt_rebatepercent').attr('readonly', false);
		$('#txt_shortname').attr('readonly', false);
		$('#txt_workorderamt').attr('readonly', false);
		$('#txt_workorderno').attr('readonly', false);
		$('#txt_agreementno').attr('readonly', false);
		$('#txt_gst_value').attr('readonly', false);
		$('input[name=worktype]').attr("disabled",false);
		$('input[name=gstincexc]').attr("disabled",false);
		$('input[name=lcesapp]').attr("disabled",false);
		$('#txt_rebatepercent').removeClass('input-disabled');						
		$('#workduration').removeClass('input-disabled');
		$('#txt_workorderno').removeClass('input-disabled');
		$('#txt_agreementno').removeClass('input-disabled');
		$('#txt_gst_value').removeClass('input-disabled');
		$('#txt_workname').removeClass('input-disabled');
		$('#txt_workorderamt').removeClass('input-disabled');
		$('#txt_computercodeno').removeClass('input-disabled');
		$('#txt_aggrementdate').removeClass('input-disabled');								
		$('#workorderdate').removeClass('input-disabled');
		$('#workcommencedate').removeClass('input-disabled');
		$('#txt_dateofcompletion').removeClass('input-disabled');
		$('#txt_dateofcompletion').removeClass('input-disabled');
		$('#txt_ICNO').removeClass('input-disabled');
		$('#txt_enggdesig').removeClass('input-disabled');
		$('#txt_enggname').removeClass('input-disabled');
		$('#txt_enggroup').removeClass('input-disabled');
		$('#txt_acc_hold_name').removeClass('input-disabled');
		$('#txt_bank_name').removeClass('input-disabled');
		$('#txt_bank_branch').removeClass('input-disabled');
		$('#txt_bank_accno').removeClass('input-disabled');
		$('#txt_bank_ifsc').removeClass('input-disabled');
		$('#txt_cont_name').removeClass('input-disabled');
		$('#txt_cont_id').removeClass('input-disabled');
		$("#txt_sd_per").removeClass('input-disabled');
		$("#txt_sd_value").removeClass('input-disabled');
		//$("#txt_aggrementdate").datepicker( "option", "disabled", false );
		//$("#workorderdate").datepicker( "option", "disabled", false );
		//$("#workcommencedate").datepicker( "option", "disabled", false );
		//$("#txt_dateofcompletion").datepicker( "option", "disabled", false );
		$("#txt_work_name").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'FindEstTsTrName.php', 
			data: { Id: Id, Page: 'WOENT'}, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){
					var CheckValue		= data['CHECKVAL'];
					//alert(CheckValue);
					var TrRegData 		= data['TrRegData'];
					var WorkFullName	= data['WORKFULLNAME'];
					var CcNo		    = data['CCNUM'];
					var ContID 		     = data['contid']; 
					var ContName  		= data['CONTNAME']; 
					var EnggId  		= data['ENGGID'];
					var EnggName  		= data['ENGGNAME'];
					var EnggICno  		= data['ENGGICNO'];
					var EnggDesig 		= data['ENGGDESIG'];
					var EnggSec  		= data['ENGGSEC'];
					var ConBankId 		= data['CONTBANKDETID'];
					var ContBankDets  = data['CONTBANKDET'];

					if(CheckValue == 1){ 
						var WorkShName		= data['WORKSHNAME'];
						var AggreNum		= data['AGGRENUM'];
						var AggrDt			= data['AGGREDATE'];
						var WODt		    = data['WORKORDDATE'];
						var WrkOrderNum	    = data['WORKORDNO'];
						var WrkComDt		= data['WORKCOMMENCDATE'];
						var WCompDt 		= data['WORKCOMPDATE'];
						var IsLcess  		= data['ISLCESS'];
						var IncExc  		= data['INCEXC'];
						var GSTPerRate		= data['GSTPERRATE'];
						var IsGst  			= data['ISGST'];
						var AgmtDate 		= moment(AggrDt).format('DD/MM/YYYY');
						var WrkOrderDt 	    = moment(WODt).format('DD/MM/YYYY');
						var WrkCommenceDt   = moment(WrkComDt).format('DD/MM/YYYY');
						var WrkCompDt 		= moment(WCompDt).format('DD/MM/YYYY');
						$("#txt_shortname").val(WorkShName);
						$("#workorderdate").val(WrkOrderDt);
						$("#txt_workorderno").val(WrkOrderNum);
						$("#txt_aggrementdate").val(AgmtDate);
						$("#txt_agreementno").val(AggreNum);
						$("#txt_dateofcompletion").val(WrkCompDt);
						$("#workcommencedate").val(WrkCommenceDt);
						if(IsGst == 'Y'){
							$(".gstapplicab").show();
							$("#txt_gst_value").val(GSTPerRate);
							$("#gst_app_yes").prop('checked', true);
							$("#gst_app_no").prop('checked', false);
							if(IncExc == 'I'){
								$("#gst_inc").prop('checked', true);
								$("#gst_exc").prop('checked', false);
							}else if(IncExc == 'E'){
								$("#gst_exc").prop('checked', true);
								$("#gst_inc").prop('checked', false);
							}
						}if(IsGst == 'N'){
							$(".gstapplicab").hide();
							$("#txt_gst_value").val('');
							$("#gst_app_no").prop('checked', true);
							$("#gst_app_yes").prop('checked', false);
							$("#gst_exc").prop('checked', false);
							$("#gst_inc").prop('checked', false);
						}
						if(IsLcess == "Y"){
							$("#lcesapp_y").prop('checked', true);
							$("#lcesapp_n").prop('checked', false);
						}
						if(IsLcess == "N"){
							$("#lcesapp_n").prop('checked', true);
							$("#lcesapp_y").prop('checked', false);
						}
					}
					var WorkData  		= data['WorkData'];
					var WOAmount  		= data['WOAmount'];
					var WORebate  		= data['WOReb'];
					//var WorkProc  		= 1;
					var WorkProc  		= data['WORKPROCESS']; //alert(WorkProc);
					var WorkDur  		= data['WORKDURATION'];
					var HoaNum  		= data['HOANUMBER']; //alert(HoaNum);
					var HoaIdSel  		= data['SELHOAID']; //alert(HoaIdSel);
					var WoAmtFormated   = Intl.NumberFormat('en-IN').format(WOAmount);
					$("#txt_staffid").val(EnggId);
					$("#txt_hid_woamt").val(WOAmount);
					$("#txt_workorderamt").val(WOAmount);
					$("#txt_hid_glob_id").val(TrRegData.globid);
					$("#txt_enggname").val(EnggName);
					$("#txt_ICNO").val(EnggICno);
					$("#txt_enggdesig").val(EnggDesig);
					$("#txt_enggroup").val(EnggSec);
					$("#txt_cont_id").val(ContID);
					$("#txt_cont_name").val(ContName);
					$("#txt_computercodeno").val(CcNo);
					$("#txt_workname").val(WorkFullName);
					$("#workduration").val(TrRegData.time_month);
					$("#txt_rebatepercent").val(WORebate);
					$("#txt_sd_per").val(TrRegData.sd_per);
					if(TrRegData.sd_per != null){
						if(WOAmount != null){
							var sdperc = TrRegData.sd_per;
							var sdvaluecalc = WOAmount*sdperc/100;
							var sdvalueFormated = Intl.NumberFormat('en-IN').format(sdvaluecalc);
						}
					}
					$("#txt_sd_value").val(sdvalueFormated);
					$("#txt_hid_sd_value").val(sdvaluecalc);
					$("#txt_hid_checkpt").val(CheckValue);
					if(ContBankDets != null){
						var BankStr ="<div class='card-header inkblue-card' align='left'>&nbsp;Contractor Bank Details</div>";
						BankStr += "<table  class='dataTable etable' width='100%'>";
						BankStr += "<tr style'background-color:#EAEAEA'class ='lboxlabe'><th>Select</th>";
						//BankStr += "<th>Account Name</th>";
						BankStr += "<th>Bank Name</th>";
						BankStr += "<th>Bank Address</th>";
						BankStr += "<th>Account Number</th>";
						BankStr += "<th>Ifsc Code</th></tr>";
						$.each(ContBankDets, function(index, element) {
							BankStr += "<tr>";
							if(ConBankId == element.cbdtid){
								BankStr +="<td align='center'><input type='checkbox' checked class='tboxsmclass' name='bank_checkbox[] id='bank_checkbox' value='"+element.cbdtid+"'></td>";
							}else{
								BankStr +="<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox[] id='bank_checkbox' value='"+element.cbdtid+"'></td>";
							}
							//BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_acc_hold_name_0' id='txt_acc_hold_name' value='"+element.name_contractor+"' ></td>";
							// BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_acc_hold_name_0' id='txt_acc_hold_name' value='"+element.bank_acc_hold_name+"' ></td>";
							BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_name_0' id='txt_bank_name' value='"+element.bank_name+"'></td>";
							BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_branch_0' id='txt_bank_branch' value='"+element.branch_address+"'></td>";
							BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_accno_0' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)' value='"+element.bank_acc_no+"'></td>";
							BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='' id='txt_bank_ifsc' value='"+element.ifsc_code+"'></td></tr>";
						});
						BankStr += "</table>";
						$("#ContDet").html(BankStr);
					}
					
					$.each(HoaNum, function(index, element) {
						$("#cmb_hoa").append('<option selected value="'+element.hoamast_id+'">'+element.new_hoa_no+'</option>');
						$("#cmb_hoa").trigger('chosen:updated');
						//if($.inArray(element.hoamast_id,HoaIdSel) > -1 ){
						//}else{
							//$("#cmb_hoa").append('<option value="'+element.hoamast_id+'">'+element.new_hoa_no+'</option>');
						//}
						// var SplitHoa = HoaIdSel.split(","); alert(SplitHoa);
						// for(var i=0; i<SplitHoa.length; i++){
						// 	var Hoa = SplitHoa[i];
						// 	$("#cmb_hoa").find("option[value="+Hoa+"]").prop("selected", "selected");
						// }
					});

					if(WorkProc == 1){
						$("#btn_save").hide();
						$("#notediv").show();
						$('#txt_rebatepercent').attr('readonly', true);
						$('#txt_shortname').attr('readonly', true);
						$('#txt_workorderamt').attr('readonly', true);
						$('#txt_workorderno').attr('readonly', true);
						$('#txt_agreementno').attr('readonly', true);
						$('#txt_gst_value').attr('readonly', true);
						$('input[name=worktype]').attr("disabled",true);
						$('input[name=gstincexc]').attr("disabled",true);
						$('input[name=lcesapp]').attr("disabled",true);
						$('input[name=gstapplicable]').attr("disabled",true);
						$('#bank_checkbox').attr("disabled",true);
						$('#txt_workname').addClass('input-disabled');
						$('#txt_workorderamt').addClass('input-disabled');
						$('#txt_computercodeno').addClass('input-disabled');
						$('#txt_cont_name').addClass('input-disabled');
						$('#workduration').addClass('input-disabled');
						$('#txt_aggrementdate').addClass('input-disabled');
						$('#workorderdate').addClass('input-disabled');
						$('#workcommencedate').addClass('input-disabled');
						$('#txt_dateofcompletion').addClass('input-disabled');
						$('#txt_rebatepercent').addClass('input-disabled');
						$('#workduration').addClass('input-disabled');
						$('#txt_shortname').addClass('input-disabled');
						$('#txt_workorderno').addClass('input-disabled');
						$('#txt_agreementno').addClass('input-disabled');
						$('#txt_gst_value').addClass('input-disabled');
						$('#txt_agreementno').addClass('input-disabled');
						$('#txt_ICNO').addClass('input-disabled');
						$('#txt_enggdesig').addClass('input-disabled');
						$('#txt_enggname').addClass('input-disabled');
						$('#txt_enggroup').addClass('input-disabled');
						//$('#txt_acc_hold_name').addClass('input-disabled');
						$('#txt_bank_name').addClass('input-disabled');
						$('#txt_bank_branch').addClass('input-disabled');
						$('#txt_bank_accno').addClass('input-disabled');
						$('#txt_bank_ifsc').addClass('input-disabled');
						$("#txt_sd_per").addClass('input-disabled');
						$("#txt_sd_value").addClass('input-disabled');
						//$( "#txt_aggrementdate" ).datepicker( "option", "disabled", true );
						//$( "#workorderdate" ).datepicker( "option", "disabled", true );
						//$( "#workcommencedate" ).datepicker( "option", "disabled", true );
						//$( "#txt_dateofcompletion" ).datepicker( "option", "disabled", true );
					}
				}
			}
		});
	});
});
$('#txt_sd_per').change(function() {
	var SDper= $(this).val();
	var Workvalue = $("#txt_hid_woamt").val(); 
	$("#txt_sd_value").val('');
	var SDvalue= (Number(SDper) / 100) *Number(Workvalue); 
	var sdvalueFormated = Intl.NumberFormat('en-IN').format(SDvalue);
	$("#txt_sd_value").val(sdvalueFormated); 
	$("#txt_hid_sd_value").val(SDvalue); 
});
$('#workcommencedate').change(function() {
var workduration = $("#workduration").val();
			//var workorderdate = $("#workorderdate").val();
			var workcommencedate = $("#workcommencedate").val();
			$("#txt_dateofcompletion").val("");
			if((workduration != "") && (workcommencedate != "")){  
				var d1 = workcommencedate.split("/");
				workduration = Number(workduration);
				var woddate = new Date(d1[2], d1[1]-1+workduration, d1[0]-1);
				//var SchDOC = woddate.getDate() + '/' + (woddate.getMonth() + 1) + '/' +  woddate.getFullYear();
				var sDate 	= woddate.getDate();
				var sMonth 	= woddate.getMonth()+1;
				var sYear 	= woddate.getFullYear();
				if (sDate < 10){ sDate = '0' + sDate; }
    			if (sMonth < 10){ sMonth = '0' + sMonth; }
				var SchDOC = sDate + '/' + sMonth + '/' +  sYear;
				$("#txt_dateofcompletion").val(SchDOC);
			}
});
</script>
<script>
var msg = "<?php echo $msg; ?>";
document.querySelector('#top').onload = function(){
	if(msg != ""){
		BootstrapDialog.alert(msg);
	}
};
</script>
<style>
.row label{
	color:#04498E;
}
</style>

