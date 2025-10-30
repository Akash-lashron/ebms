<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';
require_once 'library/functions.php';
checkUser();
$msg = '';
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
if (isset($_POST["btn_save"])){
	$globeId            = 	trim($_POST['txt_globid']);
	$sheetId            = 	trim($_POST['text_sheetid']);
	$Loipgid           = 	trim($_POST['txt_loiid']);
	$computercodeno 	= 	trim($_POST['txt_ccode']);
    $workname 			= 	trim($_POST['txt_workname']);
    $workorderno 		= 	trim($_POST['txt_workorderno']);
	$workvalue 		    = 	trim($_POST['txt_work_value']);
	$workorderdate 		= 	dt_format(trim($_POST['txt_workorderdate']));
	$workduration 		= 	trim($_POST['txt_workduration']);
    $techsanctionno 	= 	trim($_POST['text_techsanctionno']);
	$schcompledate 		= 	dt_format(trim($_POST['txt_dateofcompletion']));
    $agreementdt        = 	dt_format(trim($_POST['txt_agreementdate']));
	$hoa		        = 	$_POST['cmb_hoa'];
	$hoaStr             =   implode(",",$hoa);
	$staffname      	= 	trim($_POST['txt_staffid']);
	$cont_id	        = 	($_POST['cmb_contractorname']);
	$SelectQuery        =  "select name_contractor from contractor where contid='$cont_id' ";
	$SelectSql 		    =  mysqli_query($dbConn,$SelectQuery);

	if($SelectSql == true){
		$List1 			= mysqli_fetch_object($SelectSql);
		$contractorname = 	$List1->name_contractor;
	}
	$contbid            = 	($_POST['bank_checkbox']);
    $Contidstr          =   implode(",",$contbid);
	$gstrate            = 	trim($_POST['txt_gst_value']);
	$isacces            = 	trim($_POST['lcess_app']);
	$SDper 	            = 	trim($_POST['txt_sd_per']);
	$SDValue            = 	trim($_POST['txt_sd_value']);
	if($globeId == null){
				$sheet_sql 			=  "insert into works set ccno='$computercodeno',work_name='$workname', ts_no='$techsanctionno',wo_no='$workorderno',
									wo_amount = '$workvalue',wo_date = '$workorderdate', hoaid='$hoaStr', name_contractor='$contractorname',contid='$cont_id',
									sd_perc = '$SDper',sd_amt = '$SDValue', active='1'";
				$insert_sql = mysqli_query($dbConn,$sheet_sql);	

				$LastInsertglobid = mysqli_insert_id($dbConn);	
				$insert_query1  =  "insert into sheet set globid='$LastInsertglobid',work_name='$workname', tech_sanction='$techsanctionno',work_order_no='$workorderno',
								work_order_cost = '$workvalue',work_order_date = '$workorderdate',work_duration = '$workduration',date_of_completion = '$schcompledate',  
								computer_code_no = '$computercodeno',  agree_date = '$agreementdt', hoaid = '$hoaStr',  assigned_staff = '$staffname', gst_perc_rate='$gstrate',is_less_appl='$isacces',
								name_contractor='$contractorname',contid='$cont_id',cbdtid='$Contidstr', active='x'";
				$sheetinsert_query 	= 	mysqli_query($dbConn,$insert_query1);
				$InsertedSheetId    = mysqli_insert_id($dbConn);
				$UpdateGlobQuery = "update  works a set a.sheetid = '$InsertedSheetId' where a.globid = '$LastInsertglobid'";
				$UpdateGlobSql = mysqli_query($dbConn,$UpdateGlobQuery);

				$PBGper 	        = 	trim($_POST['txt_pg_per']);
				$PBGvalue 	        = 	trim($_POST['txt_pg_value']);
				$PBGdate	        = 	dt_format(trim($_POST['txt_pg_valdidate']));
				$Emdinstypestr	    =   $_POST["cmd_instype"];
				$Emdinstnumstr	    =   $_POST["instrunum"];
				$Emdbnamestr	    =   $_POST["txt_bankname_pg"];
				$Emddatestr		    =   $_POST["txt_date_pg"];
				$Emdexdatestr	    =   $_POST["txt_expir_date_pg"];
				$AmountListstr	    =   $_POST["txt_part_amt"];
				$insert_query2		=  "insert into loi_entry set globid='$LastInsertglobid', sheetid='$InsertedSheetId',pg_per='$PBGper', contid='$cont_id', pg_amt='$PBGvalue',pg_validity='$PBGdate',
										userid = '$UserId', createddate = NOW()";
				$Loiinsert_query    = mysqli_query($dbConn,$insert_query2);	
				$InsertedloipgId    = mysqli_insert_id($dbConn);
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
					$insert_query3		=  "insert into loi_pg_detail set globid='$LastInsertglobid', loa_pg_id='$InsertedloipgId',bg_type='$Emdinstype', contid='$cont_id', bg_amt='$TrimAmount',
										bg_serial_no='$Emdinstnum',bg_bank_name='$Emdbname', bg_date='$Insertdate',  bg_exp_date='$InsertExpdate', userid = '$UserId', active ='1'";
					$Loidetailinsert_query    = mysqli_query($dbConn,$insert_query3);	
	           }

    if($UpdateGlobSql == true){
        $msg = "Agreement Details Stored Successfully ";
		$success = 1;
    }else{
		$msg = " Agreement Details Not Saved. Error...!!! ";
	}
}else{
		$Updatework_sql 	=  "update  works set ccno='$computercodeno', sheetid = '$sheetId', work_name='$workname', ts_no='$techsanctionno',wo_no='$workorderno',
								wo_amount = '$workvalue',wo_date = '$workorderdate', hoaid='$hoaStr', name_contractor='$contractorname',contid='$cont_id',
								sd_perc = '$SDper',sd_amt = '$SDValue', active='1' where globid='$globeId'";
	  
		$updatework_sql = mysqli_query($dbConn,$Updatework_sql);	

		$Updatesheet_sql1  =  "update  sheet set globid='$globeId',work_name='$workname', tech_sanction='$techsanctionno',work_order_no='$workorderno',
								work_order_cost = '$workvalue',work_order_date = '$workorderdate',work_duration = '$workduration',date_of_completion = '$schcompledate',  
								computer_code_no = '$computercodeno',  agree_date = '$agreementdt', hoaid = '$hoaStr',  assigned_staff = '$staffname', gst_perc_rate='$gstrate',is_less_appl='$isacces',
								name_contractor='$contractorname',contid='$cont_id',cbdtid='$Contidstr', active='x' where globid='$globeId'";
				
		$updatesheet_query 	= 	mysqli_query($dbConn,$Updatesheet_sql1);
				$PBGper 	        = 	trim($_POST['txt_pg_per']);
				$PBGvalue 	        = 	trim($_POST['txt_pg_value']);
				$PBGdate	        = 	dt_format(trim($_POST['txt_pg_valdidate']));
				$Emdinstypestr	    =   $_POST["cmd_instype"];
				$Emdinstnumstr	    =   $_POST["instrunum"];
				$Emdbnamestr	    =   $_POST["txt_bankname_pg"];
				$Emddatestr		    =   $_POST["txt_date_pg"];
				$Emdexdatestr	    =   $_POST["txt_expir_date_pg"];
				$AmountListstr	    =   $_POST["txt_part_amt"];
				$insert_query2		=  "update loi_entry set globid='$globeId', sheetid='$sheetId',pg_per='$PBGper', contid='$cont_id', pg_amt='$PBGvalue',pg_validity='$PBGdate',
										userid = '$UserId', createddate = NOW() where globid='$globeId'";
				$Loiinsert_query    = mysqli_query($dbConn,$insert_query2);	

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
					$insert_query3		=  "update loi_pg_detail set globid='$globeId', loa_pg_id='$Loipgid',bg_type='$Emdinstype', contid='$cont_id', bg_amt='$TrimAmount',
										bg_serial_no='$Emdinstnum',bg_bank_name='$Emdbname', bg_date='$Insertdate',  bg_exp_date='$InsertExpdate', userid = '$UserId', active ='1' where globid='$globeId'";
					$Loidetailinsert_query    = mysqli_query($dbConn,$insert_query3);	
	           }

    if($Loidetailinsert_query == true){
        $msg = "Agreement Details Updated Successfully ";
		$success = 1;
    }else{
		$msg = " Agreement Details Not Saved. Error...!!! ";
	}

}  
} 
if($_GET['sheet_id'] != "")
{
	$select_sheet_query 	= 	"select * from sheet WHERE sheet_id = ".$_GET['sheet_id'];
	$select_sheet_sql 		= 	mysqli_query($select_sheet_query);
	if($select_sheet_sql == true) 
	{
		$List = mysqli_fetch_object($select_sheet_sql);
		$work_order_no 		= 	$List->work_order_no;
		$work_name 			= 	$List->work_name; 
		$short_name 		= 	$List->short_name; 
		$tech_sanction 		= 	$List->tech_sanction;
		$name_contractor 	= 	$List->name_contractor;
		$agree_no 			= 	$List->agree_no;
		$computer_code_no 	= 	$List->computer_code_no;
		$worktype 			= 	$List->worktype;
		$rebatepercent 		= 	$List->rebate_percent;
		$work_order_date 	= 	dt_display($List->work_order_date);
		$work_commence_date = 	dt_display($List->work_commence_date);
		$date_of_completion = 	dt_display($List->date_of_completion);
		$work_duration 		= 	$List->work_duration;
		$section 			= 	$List->section_type;
		$sectionCode 		= 	$List->section_abcd;
		$civil_sheetid 		= 	$List->under_civil_sheetid;
		//echo $sectionCode;exit;
	}
}
if(isset($_POST['update']))
{
	$sheetid 			= 	trim($_POST['hid_sheetid']);
    $workname 			= 	trim($_POST['workname']);
	$shortname 			= 	trim($_POST['shortname']);
	$section 			= 	trim($_POST['section']);
	$sectCode 			= 	trim($_POST['section_code']);
	$sectionabcd 		= 	$sectCode;
	$under_civil 		= 	trim($_POST['under_civil']);
	if($under_civil == 'Y'){
		$civil_workorderno 	= 	trim($_POST['civil_workorderno']);
	}else{
		$civil_workorderno = '';
	}
    $workorderno 		= 	trim($_POST['workorderno']);
    $techsanctionno 	= 	trim($_POST['techsanctionno']);
    $agreementno 		= 	trim($_POST['agreementno']);
    $contractorname 	= 	trim($_POST['contractorname']);
    $computercodeno 	= 	trim($_POST['computercodeno']);
    $workorderdate 		= 	dt_format(trim($_POST['workorderdate']));
	$commencedate 		= 	dt_format(trim($_POST['workcommencedate']));
	$workduration 		= 	trim($_POST['workduration']);
	$rebatepercent 		= 	trim($_POST['rebatepercent']);
	$schcompledate 		= 	dt_format(trim($_POST['dateofcompletion']));
	$worktype 			= 	trim($_POST['worktype']);
	/*if($section == "I"){ $sectionabcd = "SECTION - A"; }//CIVIL
	if($section == "II"){ $sectionabcd = "SECTION - B"; }//ELECTRICAL
	if($section == "III"){ $sectionabcd = "SECTION - C"; }//MECHANICAL
	if($section == "IV"){ $sectionabcd = "SECTION - D"; }//MHE
	if($section == "V"){ $sectionabcd = "SECTION - E"; }//ACV*/
    $sheet_sql 			= 	"UPDATE sheet set "
                            . "work_name = '$workname', "
							. "short_name = '$shortname', "
							. "section_abcd = '$sectionabcd', "
							. "under_civil_sheetid = '$civil_workorderno', "
                            . "work_order_no = '$workorderno', "
                            . "tech_sanction = '$techsanctionno', "
                            . "agree_no = '$agreementno', "
                            . "name_contractor = '$contractorname', "
                            . "computer_code_no = '$computercodeno', "
							. "work_order_date = '$workorderdate', "
							. "work_commence_date = '$commencedate', "
							. "work_duration = '$workduration', "
							. "rebate_percent = '$rebatepercent', "
							. "date_of_completion = '$schcompledate', "
							. "worktype = '$worktype', "
							. "section_type = '$section' "
                            . "where sheet_id = '$sheetid'";
							//echo $sheet_sql;exit;
    $sheet_query 	= 	mysqli_query($sheet_sql);
	if($sheet_query == true)
	{
		$msg = "Agreement Details Updated Sucessfully..!!";
		$success = 1;

	}
	else
	{
		$msg = "Agreement Details Not Updated";
	}
}
?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
   	$(function(){
		$("#txt_workorderdate").datepicker({
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
		$("#txt_dateofcompletion").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            //maxDate: new Date,
            defaultDate: new Date,
        });
		$(".date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date,
            defaultDate: new Date,
        });
		$(".expdate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            //maxDate: new Date,
            defaultDate: new Date,
        });
		$('#section').change(function() {
			var section = $(this).val();
			if((section != 'I')&&(section != '')){
				$('.under_civil').show();
			}else{
				$('.under_civil').hide();
			}
			$("#under_civil_no").attr('checked', true);
			$("#civil_workorderno").chosen("destroy");
			$("#civil_workorderno").val('');
			$("#civil_workorderno").chosen();
		});
		$('input[type=radio][name=under_civil]').change(function() {
			var under_civil = $(this).val(); 
			$("#civil_workorderno").chosen("destroy");
			var WoWidth = $("#civil_workorderno").width(); //alert(WoWidth);
			$("#civil_workorderno_chosen").css("width", WoWidth);
			$("#civil_workorderno").val('');
			if(under_civil == 'Y'){
				$("#civil_workorderno").prop('disabled', false);
			}else{
				$("#civil_workorderno").prop('disabled', true);
			}
			$("#civil_workorderno").chosen();
		});
		$(".save").click(function(event){
			var section = $('#section').val();
			var sectionCode = $('#section_code').val();
			var worktype = $('input[type=radio][name=worktype]:checked').val();
			if(section == ''){
				BootstrapDialog.alert("Please Select Section Name");
				event.preventDefault();
				return false;
			}else if(sectionCode == ''){
				BootstrapDialog.alert("Please Select Section Code");
				event.preventDefault();
				return false;
			}else if(worktype == undefined){
				BootstrapDialog.alert("Please Select Work Type");
				event.preventDefault();
				return false;
			}
			if((section != '')&&(section != 'I')){
				//var under_civil = $('input[type=radio][name=under_civil]:checked').val();
				var civil_workorder = $("#civil_workorderno").val();
				if(civil_workorder == ''){
					BootstrapDialog.alert("Please Select Civil Work Name");
					event.preventDefault();
					return false;
				}
				/*if(under_civil == 'Y'){
					var civil_workorder = $("#civil_workorderno").val();
					if(civil_workorder == ''){
						BootstrapDialog.alert("Please Select Civil Work Name");
						event.preventDefault();
						return false;
					}
				}*/
			}
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
			var dateofcompletion = $("#dateofcompletion").val();
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
			var dateofcompletion = $("#dateofcompletion").val();
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
			var workduration = $("#txt_workduration").val();
			//var workorderdate = $("#workorderdate").val();
			var workcommencedate = $("#workcommencedate").val();
			$("#dateofcompletion").val("");
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
				$("#dateofcompletion").val(SchDOC);
			}
		}
		$.fn.CheckRebatePercentage = function(event) {
			var rebate = $(this).val();
			if(Number(rebate)>100){
				BootstrapDialog.alert("Rebate percentage should be less than 100");
				$(this).val('0.00');
				event.preventDefault();
				event.returnValue = false;
			}else{
				var num = toFixed2DecimalNoRound(rebate,2);
				$(this).val(num);
			}
		}
		$("#workcommencedate").change(function(event){
			$(this).FindSchduleDOC(event);
		});	
		$("#workduration").keyup(function(event){
			$(this).FindSchduleDOC(event);
		});	
		$("#rebatepercent").change(function(event){
			$(this).CheckRebatePercentage(event);
		});	
		$("#workduration").keydown(function(e) {
			var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67; //alert(e.keyCode);
			if (ctrlDown || e.keyCode == vKey || e.keyCode == cKey){
				return false;
			}else{
				return true;
			}
		});
		$("#rebatepercent").keydown(function(e) {
			var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67; //alert(e.keyCode);
			if (ctrlDown || e.keyCode == vKey || e.keyCode == cKey){
				return false;
			}else{
				return true;
			}
		});
		// $.fn.validatepgamount = function(event) { 
		// 	var pgamt = $("#txt_pg_value").val(); alert(pgamt);
		// 	var totalamt = $("#text_totalamt").val(); alert(totalamt);
	
		// 		if(pgamt!=totalamt){
		// 			var a="PG Amount is not Equal to the Total BG/FDR Amout";
		// 			BootstrapDialog.alert(a);
		// 			event.preventDefault();
		// 			event.returnValue = false;
				
		// 		}
		// 	}
			
	});
		$("#top").submit(function(event){
			$(this).checkDate(event);
			$(this).checkDate2(event);
			$(this).validateworkorderdateformat(event);
			$(this).validatedateofcompletionformat(event);
			$(this).validatecommencementformat(event);
			$(this).validatepgamount(event);
		});
		function goBack(){
			url = "AgreementSheetEntry.php";
			window.location.replace(url);
		}

</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.chosen-container-single .chosen-single{
	height:30px !important;
	line-height: 25px;
}
.inputGroup label::after {
    width: 10px;
    height: 12px;
	top: 49%;
	right:20px;
}
.chosen-container{
	/*width:99% !important;*/
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <?php include "MainMenu.php"; ?>
                <div class="container_12">
                    <div class="grid_12">
						<blockquote class="bq1" style="overflow:auto">
							<div class="row">
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead head-b" align="left">Work Details Entry Form</div>
											<div class="row innerdiv group-div" align="center">
											   <div class="row clearrow"></div>
											    <div class="div2 lboxlabel">CCODE</div>
										       <div class="div4">
											        <input type="text" class="tboxsmclass" name='txt_ccode' required id='txt_ccode' value="">
													<input type="hidden" class="tboxsmclass" name='txt_globid' required id='txt_globid' value="">
													<input type="hidden" class="tboxsmclass" name='text_sheetid' required id='text_sheetid' value="">
													<input type="hidden" class="tboxsmclass" name='txt_loiid' required id='txt_loiid' value="">
												</div>
											   <div class="div1">
											        <input type="button"  class="buttonstyle" name="Go" id="Go" value="Go" onClick=""/>
											   </div>
											   <div class="row clearrow"></div>
										       <div class="div2 lboxlabel" style="line-height:45px;">Name of Work</div>
											   <div class="div10">
												    <textarea name='txt_workname' class="tboxsmclass" id='txt_workname' required rows="2"></textarea>
											   </div>
											   <div class="row clearrow"></div>
											   <div class="div2 lboxlabel">Work Order No.</div>
												 <div class="div3">
													    <input type="text" class="tboxsmclass" name='txt_workorderno' required id='txt_workorderno' value="">
												   </div>
												   <div class="div3 lboxlabel">&nbsp;&nbsp;Work Order Value (&#8377;)</div>
												     <div class="div4">
													    <input type="text" class="tboxsmclass" name='txt_work_value' required id='txt_work_value' value="">
												     </div>
												   <div class="row clearrow"></div>
												   <div class="div2 lboxlabel" align="center">Work Order Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
												       <div class="div3">
													      <input type="text" class="tboxsmclass datepicker" name='txt_workorderdate' required id='txt_workorderdate' value="">
												   </div>
												   <div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Duration of Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
												       <div class="div2">
													      <input type="text" class="tboxsmclass" name='txt_workduration' id='txt_workduration' required onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="">
												     </div>
												     <div class="div2" align="left"><span style="font-size:10px">&nbsp;Months</span> <span style="font-size:10px">(Max. 3 digit)</span></div>
												     <div class="row clearrow"></div>
													 <div class="div2 lboxlabel">Technical Sanction No.</div>
												     <div class="div3">
													     <input type="text" class="tboxsmclass" name='text_techsanctionno' required id='text_techsanctionno' value="">
												     </div>
												     <div class="div3 lboxlabel" align="center">&nbsp;&nbsp;HOA Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
												     <div class="div3">
													  	<select name='cmb_hoa[]' id='cmb_hoa' class="tboxsmclass"   multiple="multiple">
													      <!--<option value="">--------------- Select ---------------</option>-->
														  <?php echo $objBind->BindHOA(0); ?>														  
														</select>
													 </div>
													 <div class="row clearrow"></div>
													 <div class="div2 lboxlabel" align="center">Agreement Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
													<div class="div3">
														<input type="text" class="tboxsmclass datepicker" name='txt_agreementdate' id='txt_agreementdate' required  value="">
													</div>
													<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Scheduled Date of Completion &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
													<div class="div4">
														<input type="text" class="tboxsmclass datepicker" name='txt_dateofcompletion' id='txt_dateofcompletion' required  value="">
													</div>
													<div class="row clearrow"></div>
												</div> 
												<div class="row divhead head-b" align="left">Engineer Details Entry Form</div>
													<div class="row innerdiv group-div" align="center">
														<div class="row clearrow"></div>
														<div class="div2 lboxlabel">Enggineer IC No.</div>
														<div class="div4">
														  <input type="text" class="tboxsmclass" name='txt_ICNO' required id='txt_ICNO'  value="">
														  <input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="" >
														</div>
														<div class="div2 lboxlabel">&nbsp;&nbsp;Enggineer Name</div>
														<div class="div4">
														    <input type="text" class="tboxsmclass" name='txt_enggname' required id='txt_enggname' readonly  value="">
														</div>
														<div class="row clearrow"></div>
														<div class="div2 lboxlabel">Enggineer Designation</div>
															<div class="div4">
															<input type="text" class="tboxsmclass" name='txt_enggdesig' required id='txt_enggdesig' readonly  value="">
														</div>
														<div class="div2 lboxlabel">&nbsp;&nbsp;Enggineer Group</div>
															<div class="div4">
															<input type="text" class="tboxsmclass" name='txt_enggroup' required id='txt_enggroup' readonly  value="">
														</div>
														<div class="row clearrow"></div>
													</div>
												    <div class="row divhead head-b" align="left">Contractor Details Entry Form</div>
													<div class="row innerdiv group-div" align="center">
													<div class="row clearrow"></div>
													<div class="row">
												       <div class="div4 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Name of the Contractor</div>
														<div class="div4">
														   <select name='cmb_contractorname' id='cmb_contractorname' class="tboxsmclass"  >
																<option value="">--------------- Select ---------------</option>
																<?php echo $objBind->BindCont($contid); ?>
															</select>
														</div>
														<div class="div1">
														   <input type="button" name="add_new_cont" id="add_new_cont" class="buttonstyle" value=" + New ">
														</div>
											        </div>
										            <div class="row clearrow"></div>
												    <div class="row">
												       <div class="div4 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contractor Address</div>
														<div class="div4">
														  <input type="text" class="tboxsmclass" name='txt_contadd' required id='txt_contadd' readonly  value="">
														</div>
											      	</div>
												  	<div class="row clearrow"></div>
														<div class="row">
												       		<div class="div4 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contractor State</div>
															<div class="div4">
																<input type="text" class="tboxsmclass" name='txt_state' required id='txt_state' readonly  value="">
															</div>
														<div class="row clearrow hidden Details" id="Cont_Bank"></div>
														<div class="row clearrow"></div>
													</div>
												</div>  
												<div class="row divhead head-b" align="left">PG Entry Form</div>
												  <div class="row innerdiv group-div" align="center">
												  <div class="row clearrow"></div>
														<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;PBG%</div>
															<div class="div1">
																	<input type="text" class="tboxsmclass" name='txt_pg_per' required id='txt_pg_per' value="">
															</div>
															<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PBG Value (&#8377;)</div>
															<div class="div2">
																	<input type="text" class="tboxsmclass" name='txt_pg_value' required id='txt_pg_value' value="">
															</div>
															<div class="div3 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PBG Validity Date</div>
															<div class="div2">
															  <input type="text" class="tboxsmclass datepicker" name='txt_pg_valdidate' id='txt_pg_valdidate' required  value="">
															</div>
															<div class="row clearrow"></div>
															<table class="itemtable etable" align="center" width="100%" id="PGTable"> 
																<tr class="lboxlabel" style="background-color:#EAEAEA">
																	<td align="center">PG Type</td>
																	<td align="center">BG/FDR Serial No.</td>
																	<td align="center">Bank Name</td>
																	<td align="center">BG/FDR Date</td>
																	<td align="center">Expiry Date</td>
																	<td align="center">PG Amount ( &#8377; )</td>
																	<td align="center">Action</td>
																</tr>
																<tr class ="lboxlabel">
																	<td align="center">
																		<select name="cmd_instype_0" id ="cmd_instype_0" class="textbox-new">  
																			<option value="">---- Select ---- </option>
																			<option value="BG">Bank Guarantee</option>
																			<option value="FDR">Fixed Deposit Receipt</option>
																		</select>
																	</td>
																	<td align="center"><input type="text" name="instrunum_0" id ="instrunum_0" class="textbox-new" style="width:110px;"></td>
																	<td align="center"><input type="text" class="lboxlabel" style="width:100px;" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																	<td align="center"><input type="text"  placeholder="DD/MM/YYYY"  class="tboxsmclass datepicker date"style="width:100px;" name="txt_date_pg_0" id="txt_date_pg_0"></td>
																	<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass datepicker expdate" style="width:100px;" name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																	<td align="center"><input type="text"class="lboxlabel" style="width:100px;" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																	<td align="center"><input type="button"  class="lboxlabel" name="pg_add" id="pg_add"  value="ADD" class="fa btn btn-info"></td>
																</tr>
																<input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new" style="width:110px;">
															</table>
															
															<div class="row clearrow"></div>
												        </div>
												    </div>
													<div class="row divhead head-b" align="left">Other Recovery Entry</div>
												    <div class="row innerdiv group-div" align="center">
													<div class="row clearrow"></div>
														<div class="row clearrow"></div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
															<div class="div3 lboxlabel">GST Rate on Work Order %</div>
															<div class="div2">
																	<input type="text" class="tboxsmclass" name='txt_gst_value' required id='txt_gst_value' value="">
															</div>
															<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LCESS Applicable</div>
															<div class="div2">
															   <input type="radio" class="radio lcess_app" name="lcess_app" value="Y" id="lcess_app_y" />
																<label for="y">YES</label>
																<input type="radio" class="radio lcess_app" name="lcess_app" value="N" id="lcess_app_n" />
																<label for="z">NO</label>
															</div>
															<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="row clearrow"></div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
															<div class="div3 lboxlabel">&nbsp;Security Deposit %</div>
															<div class="div2">
																	<input type="text" class="tboxsmclass" name='txt_sd_per' required id='txt_sd_per' value="">
															</div>
															<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total SD Value(&#8377;)</div>
															<div class="div2">
															   <input type="text" class="tboxsmclass" name='txt_sd_value' required id='txt_sd_value' value="">
															</div>
															<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="row clearrow"></div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="div3 lboxlabel">Upto Date Security Deposit</div>
														<div class="div5">
																<input type="text" class="tboxsmclass" name='txt_securitydepoe'  id='txt_securitydepoe' value="">
														</div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="row clearrow"></div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="div3 lboxlabel">Upto Date Value of Work</div>
														<div class="div5">
																<input type="text" class="tboxsmclass" name='txt_valuework'  id='txt_valuework' value="">
														</div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="row clearrow"></div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														 <div class="div3 lboxlabel">Last Payment Date</div>
														<div class="div5">
																<input type="text" class="tboxsmclass datepicker" name='txt_paymentdate'  id='txt_paymentdate' value="">
														</div>
														<div class="div1 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
														<div class="row clearrow"></div> 
 											      </div> 
												   <div class="row">
												<div class="div12" align="center">
													<a data-url="WorkList" class="btn btn-info" name="back" id="back">Back</a>
													<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value=" Save " />
												</div>
												<div class="row clearrow"></div>
											</div> 


 										</div>
										<div class="div2" align="center">&nbsp;</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php include "footer/footer.html"; ?>
		   <script>
			  $("#cmb_hoa").chosen();
				$("#civil_workorderno").chosen();
				$("#section").chosen();
				$("#section_code").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
				$("#add_new_cont").click(function(){ 
					BootstrapDialog.show({
						title: 'Contractor Entry Form',
						message: $('<div></div>').load('load/page/Contractor.php'),
						buttons: [{
							label: ' Save ',
							cssClass: 'modal-button',
							action: function(dialogItself){
								var form = $('form')[1]; // You need to use standart javascript object here
								var formData = new FormData(form);
								
								var ErrCount = 0;
								var ContName	= $('#txt_modal_entry_cont_name').val(); 
								var ContAddr 	= $('#txt_modal_entry_cont_addr').val();
								var ContState    =$('#txt_modal_entry_cont_state').val();
								var AccNo 		= $('#txt_modal_entry_acc_no').val(); 
								var BankName 	= $('#txt_modal_entry_bank_name').val();
								var BrName 		= $('#txt_modal_entry_br_name').val();
								var PANNo 		= $('#txt_modal_entry_pan_no').val();
								var GSTNo 		= $('#txt_modal_entry_gst_no').val(); 
								var Ifsce		= $('#txt_modal_entry_ifsc').val(); 
								
								if(ContName == ""){ ErrCount++; $('#txt_modal_entry_cont_name').addClass('errorClass'); }else{ $('#txt_modal_entry_cont_name').removeClass('errorClass'); }
								if(ContAddr == ""){ ErrCount++; $('#txt_modal_entry_cont_addr').addClass('errorClass'); }else{ $('#txt_modal_entry_cont_addr').removeClass('errorClass'); }
								if(ContState == ""){ ErrCount++; $('#txt_modal_entry_cont_state').addClass('errorClass'); }else{ $('#txt_modal_entry_cont_state').removeClass('errorClass'); }
								if(AccNo 	== ""){ ErrCount++; $('#txt_modal_entry_acc_no').addClass('errorClass'); 	}else{ $('#txt_modal_entry_acc_no').removeClass('errorClass'); }
								if(BankName == ""){ ErrCount++; $('#txt_modal_entry_bank_name').addClass('errorClass'); }else{ $('#txt_modal_entry_bank_name').removeClass('errorClass'); }
								if(BrName 	== ""){ ErrCount++; $('#txt_modal_entry_br_name').addClass('errorClass'); 	}else{ $('#txt_modal_entry_br_name').removeClass('errorClass'); }
								if(PANNo 	== ""){ ErrCount++; $('#txt_modal_entry_pan_no').addClass('errorClass'); 	}else{ $('#txt_modal_entry_pan_no').removeClass('errorClass'); }
								if(GSTNo 	== ""){ ErrCount++; $('#txt_modal_entry_gst_no').addClass('errorClass'); 	}else{ $('#txt_modal_entry_gst_no').removeClass('errorClass'); }
								if(Ifsce  	== ""){ ErrCount++; $('#txt_modal_entry_ifsc').addClass('errorClass'); 		}else{ $('#txt_modal_entry_ifsc').removeClass('errorClass'); }
								if(ErrCount == 0){
									$.ajax({ 
										type      	: 'POST', 
										url       	: 'load/ajax/ContractorSave.php',
										data	  	:  formData,
										contentType	:  false,       // The content type used when sending data to the server.
										cache		:  false,             // To unable request pages to be cached
										processData	:  false,        // To send DOMDocument or non processed data file it is set to false
										success   	: function(data){//alert(data);
											if(data == "A"){
												//BootstrapDialog.alert('This Contractor Already Exists');
												BootstrapDialog.alert({ title: 'Error !',message: '<i class="fa fa-times-circle" style="font-size:20px; color:red"></i> This Contractor Already Exists !'});
											}else if(data > 0){
												$('#cmb_contractorname').chosen('destroy');
												$("#cmb_contractorname").append('<option selected="selected" value="'+data+'">'+ContName+'</option>');
												$('#cmb_contractorname').chosen();
												$('#txt_contadd').val('+ContName+');
												
												//BootstrapDialog.alert('Contractor Data Saved Successfully');
												BootstrapDialog.alert({ title: 'Success !',message: '<i class="fa fa-check-circle" style="font-size:20px; color:green"></i> Contractor Data Saved Successfully'});
											}else{
												//BootstrapDialog.alert('Contractor Data Not Saved. Please Try Again.');
												BootstrapDialog.alert({ title: 'Error !',message: '<i class="fa fa-times-circle" style="font-size:20px; color:red"></i> Contractor Data Not Saved. Please Try Again !'});
											}
										}
									});
									dialogItself.close();
								}
							}
						},{
							label: ' Cancel ',
							cssClass: 'modal-button',
							action: function(dialogItself){
								dialogItself.close();
							}
						}]
					});
				});
				$("body").on("click", "#pg_add", function(event){ 
					var InstType 	 = $("#cmd_instype_0").val();
					var InstNum 	 = $("#instrunum_0").val();
					var BankName   	 = $("#txt_bankname_pg_0").val();
					var DateofIssue  = $("#txt_date_pg_0").val();
					var DateofExpiry = $("#txt_expir_date_pg_0").val();
					var AmtDetail	 = $("#txt_part_amt_0").val(); //alert(AmtDetail);
					var RowStr = '<tr><td><input type="text" name="cmd_instype[]" class="textbox-new" style="width:100px;" value="'+InstType+'"></td><td><input type="text" name="instrunum[]" class="textbox-new" style="width:100px;" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td><input type="text" name="txt_date_pg[]" class="textbox-new" style="width:100px;" value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]" class="textbox-new" style="width:100px;" value="'+DateofExpiry+'"></td><td><input type="text" name="txt_part_amt[]" class="textbox-new EmAmt" style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
					if(InstType == 0){
						alert("Please select a instrument type");
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
					}else{
						$("#PGTable").append(RowStr);
						$("#cmd_instype_0").val('');
						$("#instrunum_0").val('');
						$("#txt_bankname_pg_0").val('');
						// $("#txt_sno_pg_0").val('');
						$("#txt_date_pg_0").val('');
						$("#txt_expir_date_pg_0").val('');
						$("#txt_part_amt_0").val('');
					}
					TotalUnitAmountCalc();
				});
				$("body").on("click", ".delete", function(){
					$(this).closest("tr").remove();
					TotalUnitAmountCalc();
				});
				function TotalUnitAmountCalc(){
					var TotalAmt = 0;
					$(".EmAmt").each(function(){
						var Amt = $(this).val();
						TotalAmt = parseFloat(TotalAmt) + parseFloat(Amt);
						$("#text_totalamt").val(TotalAmt);
					});
				}

			   $('body').on("change","#txt_ICNO", function(event){ 
						var StaffCode = $(this).val();
						$("#txt_enggname").val('');
						$("#txt_enggdesig").val('');
						$("#txt_enggroup").val('');
						$("#txt_staffid").val('');
						$.ajax({ 
							type: 'POST', 
							url: 'ajax/GetEngineerDetail.php', 
							data: { StaffCode: StaffCode}, 
							dataType: 'json',
							success: function (data) { //alert(data);
								if(data != null){
									$.each(data, function(index, element) {
										$("#txt_enggname").val(element.staffname);
										$("#txt_enggdesig").val(element.designationname);
										$("#txt_enggroup").val(element.section_name);
										$("#txt_staffid").val(element.staffid);
				 			    });
				           }else{
							BootstrapDialog.alert("Sorry!..Staff is available with this IC No.");
						}
			       }
		      })
	      });
			$("body").on("change","#cmb_contractorname", function(event){ 
				$(".Details").removeClass("hidden");
				$("#Cont_Bank").html(''); 
				var ContID = $(this).val(); //alert(ContID);
				$("#txt_contadd").val('');
				$("#txt_state").val('');
				$("#txt_bank_accno").val('');
				$("#txt_bank_name").val('');
				$("#txt_bank_branch").val('');
				$("#txt_bank_ifsc").val('');
				$.ajax({ 
					type: 'POST', 
					url: 'ajax/GetContractorDetail.php',  
					data: { ContID: ContID}, 
					dataType: 'json',
					success: function (data) {  //alert(data);
						 if(data != null){ 
						var	BankStr  = "<table  class='itemtable etable'  width='100%'>";
						    BankStr += "<tr style'background-color:#EAEAEA'class ='lboxlabe'><th >Select</th>";
							BankStr += "<th>Account No.</th>";
							BankStr += "<th>Bank Name</th>";
							BankStr += "<th>Branch Name</th>";
							BankStr += "<th>Ifsc Code</th></tr>";
							$.each(data, function(index, element) {
							var ConAdress = $("#txt_contadd").val(element.addr_contractor);
						    var ConState  =	$("#txt_state").val(element.state_contractor);
								BankStr += "<tr>";
								BankStr += "<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox[]' id='bank_checkbox' value="+element.cbdtid+"></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_accno[]' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)'  value="+element.bank_acc_no+" ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_name[]' id='txt_bank_name'  value="+element.bank_name+" ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_branch[]' id='txt_bank_branch'  value="+element.branch_address+" ></td>";
								BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_ifsc[]' id='txt_bank_ifsc'  value="+element.ifsc_code+" ></td></tr>";
							
						  });
						 BankStr += "</table>";
				        $("#Cont_Bank").html(BankStr);
						 }
					}
				});
			});
			$('#txt_pg_per').change(function() {
				var Pgper= $(this).val();
				var Workvalue = $("#txt_work_value").val(); 
				$("#txt_pg_value").val('');
					var PGBvalue= (Number(Pgper) / 100) *Number(Workvalue); 
					$("#txt_pg_value").val(PGBvalue); 
            });
			$('#txt_sd_per').change(function() {
				var SDper= $(this).val();
				var Workvalue = $("#txt_work_value").val(); 
				$("#txt_sd_value").val('');
					var SDvalue= (Number(SDper) / 100) *Number(Workvalue); 
					$("#txt_sd_value").val(SDvalue); 
            });
			/*$("#btn_save").click(function(){ //alert(1);
				var pgamt = $("#txt_pg_value").val(); //alert(pgamt);
			    var totalamt = $("#text_totalamt").val();  //alert(totalamt);
				if(pgamt!=totalamt){
					var a="PG Amount is not Equal to the Total BG/FDR Amout";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
					//$('#val_date').text(a);
				}
			});*/

				$('body').on("click","#Go", function(event){ 
					var ccno = $("#txt_ccode").val(); 
					$(".Details").removeClass("hidden");
					$("#txt_globid").val('');
					$("#text_sheetid").val('');
					$("#txt_workname").val('');
					$("#txt_workorderno").val('');
					$("#txt_work_value").val('');
					$("#txt_workorderdate").val('');
					$("#txt_workduration").val('');
					$("#text_techsanctionno").val('');
					$("#txt_agreementdate").val('');
					$("#txt_dateofcompletion").val('');
					$("#cmb_hoa").chosen("destroy");
					$("#cmb_hoa").chosen();
					$("#txt_enggname").val('');
					$("#txt_ICNO").val('');
					$("#txt_enggdesig").val('');
					$("#txt_enggroup").val('');
					$("#txt_staffid").val('');
					$('#cmb_contractorname').chosen('destroy');
					$('#cmb_contractorname').val('');
					$("#txt_contadd").val('');
					$("#txt_state").val('');
					$("#txt_loiid").val('');
					$("#txt_pg_per").val('');
					$("#txt_pg_value").val('');
					$("#txt_pg_valdidate").val('');
					$("#txt_gst_value").val('');
					$("#txt_sd_per").val('');
					$("#txt_sd_value").val('');
					$("#lcess_app_y").val('');
					$("#lcess_app_y").val('');
					$("#cmd_instype_0").val('');
					$("#instrunum_0").val('');
					$("#txt_bankname_pg_0").val('');
					$("#txt_date_pg_0").val('');
					$("#txt_expir_date_pg_0").val('');
					$("#txt_part_amt_0").val('');
					$("#lcess_app_y").prop("checked",false);
					$("#lcess_app_n").prop("checked",false);
					$("#Cont_Bank").html('');
					$("#text_totalamt").val('');
					// $("#cmd_instype[]").val('');
					// $("#instrunum[]").val('');
					// $("#txt_bankname_pg[]").val('');
					// $("#txt_date_pg[]").val('');
					// $("#txt_expir_date_pg[]").val('');
					// $("#txt_part_amt[]").val('');
					$("#PGTable").find("tr:gt(1)").remove();
					$.ajax({ 
						type: 'POST', 
						url: 'ajax/GetWorkMasterDetail.php', 
						data: { ccno: ccno}, 
						dataType: 'json',
						success: function (data) { 
							var Result1 = data['row1']; 
							var Result2 = data['row2']; //alert(Result2);
							var Result3 = data['row3'];
							
							var	BankStr  = "<table  class='itemtable etable'  width='100%'>";
								BankStr += "<tr style'background-color:#EAEAEA'class ='lboxlabe'><th >Select</th>";
								BankStr += "<th>Account No.</th>";
								BankStr += "<th>Bank Name</th>";
								BankStr += "<th>Branch Name</th>";
								BankStr += "<th>Ifsc Code</th></tr>";
							if(data != null){
								$.each(Result1, function(index, element) {
									var hoaid 	    = 	element.hoaid;
									var ContName	= element.name_contractor 
									var Contid	    = element.contid 
									$("#txt_globid").val(element.globid );
									$("#text_sheetid").val(element.sheetid );
									$("#txt_loiid").val(element.loa_pg_id);
									$("#txt_workname").val(element.work_name);
									$("#txt_workorderno").val(element.work_order_no);
									$("#txt_work_value").val(element.work_order_cost);
									$("#txt_workorderdate").val(element.work_order_date);
									$("#txt_workduration").val(element.work_duration);
									$("#text_techsanctionno").val(element.ts_no);
									$("#txt_agreementdate").val(element.agree_date);
									$("#txt_dateofcompletion").val(element.date_of_completion);
									$("#cmb_hoa").chosen("destroy");
									var SplitHoa = element.hoaid.split(",");
									for(var i=0; i<SplitHoa.length; i++){
										var Hoa = SplitHoa[i];
										$("#cmb_hoa").find("option[value="+Hoa+"]").prop("selected", "selected");
									}
									$("#cmb_hoa").chosen();
									$("#txt_ICNO").val(element.staffcode);
									$("#txt_enggname").val(element.staffname);
									$("#txt_enggdesig").val(element.designationname);
									$("#txt_enggroup").val(element.section_name);
									$("#txt_staffid").val(element.staffid);
									$('#cmb_contractorname').chosen('destroy');
									$("#cmb_contractorname").append('<option selected="selected" value="'+Contid+'">'+ContName+'</option>');
									$('#cmb_contractorname').chosen();
									$("#txt_contadd").val(element.addr_contractor);
									$("#txt_state").val(element.state_contractor);
									$("#txt_pg_per").val(element.pg_per);
									$("#txt_pg_value").val(element.pg_amt);
									$("#txt_pg_valdidate").val(element.pg_validity);
									$("#txt_gst_value").val(element.gst_perc_rate);
									$("#txt_sd_per").val(element.sd_perc);
									$("#txt_sd_value").val(element.sd_amt);
									if(element.is_less_appl == "Y"){
										$("#lcess_app_y").prop("checked",true);
									}else{
										$("#lcess_app_n").prop("checked",true);
									}

								});
								$.each(Result2, function(index, element) {
										BankStr += "<tr>";
										BankStr += "<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox_0' id='bank_checkbox' checked='checked' value="+element.cbdtid+"></td>";
										BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_accno_0' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)'  value="+element.bank_acc_no+" ></td>";
										BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_name_0' id='txt_bank_name'  value="+element.bank_name+" ></td>";
										BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_branch_0' id='txt_bank_branch'  value="+element.branch_address+" ></td>";
										BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_ifsc_0' id='txt_bank_ifsc'  value="+element.ifsc_code+" ></td></tr>";

								});
								BankStr += "</table>";
								$("#Cont_Bank").html(BankStr);
								$.each(Result3, function(index, element) {
									var InstType 	 = element.bg_type;
									var InstNum 	 = element.bg_serial_no;
									var BankName   	 = element.bg_bank_name;
									var DateofIssue  = element.bg_date;
									var DateofExpiry = element.bg_exp_date; 
									var AmtDetail	 = element.bg_amt;  //alert(AmtDetail);
									var RowStr = '<tr><td><input type="text" name="cmd_instype[]" id="cmd_instype" class="textbox-new" style="width:100px;" value="'+InstType+'"></td><td><input type="text" name="instrunum[]" id="instrunum" class="textbox-new" style="width:100px;" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg" class="textbox-new" style="width:100px;" value="'+BankName+'"></td><td><input type="text" name="txt_date_pg[]" id="txt_date_pg" class="textbox-new" style="width:100px;" value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]" id="txt_expir_date_pg" class="textbox-new" style="width:100px;" value="'+DateofExpiry+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt" class="textbox-new EmAmt" style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
									
									$("#PGTable").append(RowStr);
									$("#cmd_instype_0").val('');
									$("#instrunum_0").val('');
									$("#txt_bankname_pg_0").val('');
									$("#txt_date_pg_0").val('');
									$("#txt_expir_date_pg_0").val('');
									$("#txt_part_amt_0").val('');
									TotalUnitAmountCalc();								
								});
							}else{
								BootstrapDialog.alert("CCNo is not available");
							}
						}
					});
				});
	
			</script>
        </form>
    </body>
</html>
