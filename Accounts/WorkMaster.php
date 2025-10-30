<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/binddata.php';
//require_once 'ExcelReader/excel_reader2.php';
require_once 'library/functions.php';
require_once 'library/declaration.php';
include "common.php";
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

function GetAllStaff($Staffid)
{
	global $dbConn; $Staffs = "";
	$DeptsQuery = "SELECT * FROM staff WHERE active = 1 AND sectionid = 1 AND staffid != 0 ORDER BY staffname ASC";
	$DeptsSql   = mysqli_query($dbConn,$DeptsQuery);
	if($DeptsSql == true ){
		while($StaffList = mysqli_fetch_array($DeptsSql)){
			if($Staffid == $StaffList['staffid']){
				$sel = "selected";
			}else{
				$sel = "";
			}
			$Staffs .=  '<option value="'.$StaffList['staffid'].'" data-name="'.$StaffList['staffname'].'"'.$sel.'>'.$StaffList['staffname'].' - '.$StaffList['staffcode'].'</option>'; 
		}            
	}
	return $Staffs; 
}

if (isset($_POST["btn_save"])){
	$globeId         = trim($_POST['txt_globid']);
	$sheetId         = trim($_POST['text_sheetid']);
	$Loipgid         = trim($_POST['txt_loiid']);
	$computercodeno  = trim($_POST['txt_ccode']);
	$workname 		 = trim($_POST['txt_workname']);
	$Shworkname 	 = trim($_POST['txt_shortname']);
	$workorderno 	 = trim($_POST['txt_workorderno']);
	$workvalue 		 = trim($_POST['txt_work_value']);
	$workorderdate   = dt_format(trim($_POST['txt_workorderdate']));
	$work_commence_date   = dt_format(trim($_POST['workcommencedate']));
	$workduration 	 = trim($_POST['workduration']);
	$techsanctionno  = trim($_POST['text_techsanctionno']);
	$schcompledate   = dt_format(trim($_POST['txt_dateofcompletion']));
	$agreementno	 = trim($_POST['txt_agreementno']);
	$agreementdt     = dt_format(trim($_POST['txt_agreementdate']));
	$hoa		     = $_POST['cmb_hoa'];
	$hoaStr          = implode(",",$hoa);
	$staffname       = trim($_POST['cmb_eic']);
	$enggname        = trim($_POST['txt_eic_name']);
	$cont_id	     = $_POST['cmb_contractorname'];
	$contractorname  = $_POST['hid_txt_contname'];
	$GstIncExc  	 = $_POST['gstincexc'];
	/* 
	$SelectQuery    	=  "SELECT name_contractor FROM contractor WHERE contid='$cont_id' ";
	$SelectSql 			=  mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		$List1 				= mysqli_fetch_object($SelectSql);
		$contractorname 	= $List1->name_contractor;
	}
	*/
	$contbid 	= $_POST['bank_checkbox'];
	if(count($contbid)>0){
		$Contidstr 	= implode(",",$contbid);
	}else{
		$Contidstr 	= "";
	}
	
	$isacces	= trim($_POST['lcesapp']);
	$isgst		= trim($_POST['gstapplicable']);
	if($isgst == "Y"){
		$gstrate	= trim($_POST['txt_gst_value']);
	}else{
		$gstrate	= 0;
	}
	$SDper		= trim($_POST['txt_sd_per']);
	$SDValue	= trim($_POST['txt_sd_value']);
	if($isacces == "Y"){
		$LCessPerc = 1;
	}else{
		$LCessPerc = 0;
	}
	//echo $Contidstr;exit;
	if($globeId == null){
		$sheet_sql = "INSERT INTO works SET ccno = '$computercodeno', work_name = '$workname', ts_no = '$techsanctionno', wo_no = '$workorderno', 
		wo_amount = '$workvalue', wo_date = '$workorderdate', work_commence_date = '$work_commence_date', work_duration = '$workduration', sch_comp_date = '$schcompledate', hoaid = '$hoaStr', 
		is_gst_appl = '$isgst', gst_inc_exc = '$GstIncExc', name_contractor = '$contractorname', contid = '$cont_id', cbdtid = '$Contidstr', 
		is_less_appl = '$isacces', lbcess_rate = '$LCessPerc',
		agmt_no = '$agreementno', agmt_date = '$agreementdt', eic = '$staffname', eic_name = '$enggname', sd_perc = '$SDper', sd_amt = '$SDValue', active = '1'";

		$insert_sql = mysqli_query($dbConn,$sheet_sql);	
		$LastInsertglobid = mysqli_insert_id($dbConn);	
		$insert_query1  =  "INSERT INTO sheet SET globid='$LastInsertglobid',work_name='$workname', short_name='$Shworkname', tech_sanction='$techsanctionno',work_order_no='$workorderno',
		work_order_cost = '$workvalue', work_commence_date = '$work_commence_date', work_order_date = '$workorderdate',work_duration = '$workduration',date_of_completion = '$schcompledate', 
		eic = '$staffname', eic_name = '$enggname', computer_code_no = '$computercodeno', agree_no='$agreementno', agree_date = '$agreementdt', 
		hoaid = '$hoaStr', assigned_staff = '$staffname', is_gst_appl = '$isgst', gst_perc_rate = '$gstrate', gst_inc_exc = '$GstIncExc', 
		is_less_appl = '$isacces', lbcess_rate = '$LCessPerc',
		name_contractor = '$contractorname', sd_perc = '$SDper', contid = '$cont_id', cbdtid = '$Contidstr', active = '1'";
		$sheetinsert_query = mysqli_query($dbConn,$insert_query1); 
		$InsertedSheetId  = mysqli_insert_id($dbConn); 
		$UpdateGlobQuery  = "UPDATE works SET sheetid = '$InsertedSheetId' WHERE globid = '$LastInsertglobid'"; 
		$updatework_sql   = mysqli_query($dbConn,$UpdateGlobQuery); 

		$PBGper 	  	= trim($_POST['txt_pg_per']);
		$PBGvalue 	   	= trim($_POST['txt_pg_value']);
		$PBGdate	    = dt_format(trim($_POST['txt_pg_valdidate']));
		$BGdIDStr      	= trim($_POST['txt_Bfdid']);
		$Emdinstypestr	= $_POST["cmd_instype"];
		$Emdinstnumstr	= $_POST["instrunum"];
		$Emdbnamestr	= $_POST["txt_bankname_pg"];
		$Emddatestr		= $_POST["txt_date_pg"];
		$Emdexdatestr	= $_POST["txt_expir_date_pg"];
		$AmountListstr	= $_POST["txt_part_amt"];
		$insert_query2	= "INSERT INTO loi_entry SET globid='$LastInsertglobid', sheetid='$InsertedSheetId',pg_per='$PBGper', contid='$cont_id', 
		pg_amt='$PBGvalue',pg_validity='$PBGdate', userid = '$UserId', createddate = NOW()";
		$Loiinsert_query    = mysqli_query($dbConn,$insert_query2);	
		$InsertedloipgId    = mysqli_insert_id($dbConn);
		if(count($Emdinstnumstr) > 0){
			foreach($Emdinstnumstr as $Key => $Value){
				$BGdID    	   = $BGdIDStr[$Key];
				$Emdinstype    = $Emdinstypestr[$Key];
				$Emdinstnum    = $Emdinstnumstr[$Key];
				$Emdbname      = $Emdbnamestr[$Key];
				$Emdbadd       = $Emdbaddstr[$Key];
				$Emddate       = $Emddatestr[$Key];
				$Emdexdate     = $Emdexdatestr[$Key];
				$AmountList    = $AmountListstr[$Key];
				$TrimAmount    = trim($AmountList);
				$Insertdate    = dt_format($Emddate);
				$InsertExpdate = dt_format($Emdexdate);
				$insert_query3 =  "INSERT INTO bg_fdr_details SET globid='$LastInsertglobid', master_id='$InsertedloipgId',inst_purpose='PG', inst_type='$Emdinstype', contid='$cont_id', inst_amt='$TrimAmount',
				inst_serial_no='$Emdinstnum',inst_bank_name='$Emdbname', inst_date='$Insertdate',  inst_exp_date='$InsertExpdate', userid='$userid', createdby='$userid',  created_section='$userid',  createdon= NOW() , active='1'";
				$Loidetailinsert_query	= mysqli_query($dbConn,$insert_query3);	
			}
		}
		if($updatework_sql == true){
			$msg = "Work Details Stored Successfully ";
			$success = 1;
		}else{
			$msg = " Work Details Not Saved. Error...!!! ";
		}
	}else{
		$Updatework = "UPDATE works SET ccno='$computercodeno', sheetid = '$sheetId', work_name='$workname', ts_no='$techsanctionno', wo_no='$workorderno',
		wo_amount = '$workvalue', wo_date = '$workorderdate', work_commence_date = '$work_commence_date', hoaid='$hoaStr', name_contractor='$contractorname', contid='$cont_id',
		cbdtid = '$Contidstr', eic = '$staffname', eic_name = '$enggname', is_gst_appl='$isgst', gst_perc_rate='$gstrate', gst_inc_exc = '$GstIncExc', 
		is_less_appl = '$isacces', lbcess_rate = '$LCessPerc',
		sd_perc = '$SDper', sd_amt = '$SDValue', active='1' WHERE globid='$globeId'";
		$updatework_sql = mysqli_query($dbConn,$Updatework);	

		$Updatesheet_sql1  =  "UPDATE sheet SET globid='$globeId',work_name='$workname', tech_sanction='$techsanctionno',
		eic = '$staffname', eic_name = '$enggname', work_order_no='$workorderno', work_commence_date = '$work_commence_date', work_order_cost = '$workvalue', work_order_date = '$workorderdate', 
		work_duration = '$workduration', date_of_completion = '$schcompledate', computer_code_no = '$computercodeno',  agree_date = '$agreementdt', hoaid = '$hoaStr', 
		is_gst_appl='$isgst', gst_perc_rate='$gstrate', gst_inc_exc = '$GstIncExc', is_less_appl='$isacces', lbcess_rate = '$LCessPerc', 
		name_contractor='$contractorname', contid='$cont_id', sd_perc = '$SDper', sd_amt = '$SDValue', 
		cbdtid='$Contidstr', active='1' WHERE globid='$globeId'";
		//echo $Updatesheet_sql1;exit;
		$sheetinsert_query = mysqli_query($dbConn,$Updatesheet_sql1);

		$PBGper 	    = trim($_POST['txt_pg_per']);
		$PBGvalue 	   	= trim($_POST['txt_pg_value']);
		$PBGdate	    = dt_format($_POST['txt_pg_valdidate']);
		$BGdIDStr      	= $_POST['txt_Bfdid'];
		$Emdinstypestr	= $_POST["cmd_instype"];
		$Emdinstnumstr	= $_POST["instrunum"];
		$Emdbnamestr	= $_POST["txt_bankname_pg"];
		$Emddatestr		= $_POST["txt_date_pg"];
		$Emdexdatestr	= $_POST["txt_expir_date_pg"];
		$AmountListstr	= $_POST["txt_part_amt"];

		$insert_query2	= "UPDATE loi_entry SET globid='$globeId', sheetid='$sheetId', pg_per='$PBGper', contid='$cont_id', pg_amt='$PBGvalue', 
		pg_validity='$PBGdate', userid = '$UserId', createddate = NOW() WHERE globid='$globeId'";
		$Loiinsert_query = mysqli_query($dbConn,$insert_query2);	
		
		if(count($Emdinstnumstr)>0){
			foreach($Emdinstnumstr as $Key => $Value){
				$BGdID    	   = $BGdIDStr[$Key];
				$Emdinstype    = $Emdinstypestr[$Key];
				$Emdinstnum    = $Emdinstnumstr[$Key];
				$Emdbname      = $Emdbnamestr[$Key];
				$Emdbadd       = $Emdbaddstr[$Key];
				$Emddate       = $Emddatestr[$Key];
				$Emdexdate     = $Emdexdatestr[$Key];
				$AmountList    = $AmountListstr[$Key];
				$TrimAmount    = trim($AmountList);
				$Insertdate    = dt_format($Emddate);
				$InsertExpdate = dt_format($Emdexdate);
				$Deletequery   = "DELETE FROM bg_fdr_details WHERE globid='$globeId' AND master_id='$Loipgid'";
				$BFDeletequery = mysqli_query($dbConn,$Deletequery);	
				$insert_query3	= "INSERT INTO bg_fdr_details SET globid='$globeId', master_id='$Loipgid',inst_purpose='PG', inst_type='$Emdinstype', contid='$cont_id', inst_amt='$TrimAmount',
				inst_serial_no ='$Emdinstnum',inst_bank_name='$Emdbname', inst_date='$Insertdate',  inst_exp_date='$InsertExpdate', userid='$userid', createdby='$userid',  created_section='$userid',  createdon= NOW() , active='1'";
				$Loidetailinsert_query    = mysqli_query($dbConn,$insert_query3);	
			}
		}
		if($sheetinsert_query == true){
			$msg = "Work Details Updated Successfully ";
			$success = 1;
		}else{
			$msg = " Work Details Not Saved. Error...!!! ";
		}
	}
	if(($sheetinsert_query == true) && ($updatework_sql == true)){
		$UpdateVar = null;
		if($globeId == null){
			$UpdateVar = "Created";
		}else{
			$UpdateVar = "Updated";
		}
		UpdateWorkTransaction($GlobID,0,0,"W","Work Order ".$UpdateVar." by ".$UserId,"");
	}  
	$ViewCcno = $computercodeno;
} 
//echo $ViewCcno;exit;
/*
if($_GET['sheet_id'] != "")
{
	$select_sheet_query 	= 	"SELECT * FROM sheet WHERE sheet_id = ".$_GET['sheet_id'];
	$select_sheet_sql 		= 	mysqli_query($dbConn,$select_sheet_query);
	if($select_sheet_sql == true) 
	{
		$List = mysqli_fetch_object($select_sheet_sql);
		$work_order_no 	= $List->work_order_no;
		$work_name 			= $List->work_name; 
		$short_name 		= $List->short_name; 
		$tech_sanction 	= $List->tech_sanction;
		$name_contractor 	= $List->name_contractor;
		$agree_no 			= $List->agree_no;
		$computer_code_no	= $List->computer_code_no;
		$worktype 			= $List->worktype;
		$rebatepercent 	= $List->rebate_percent;
		$work_order_date 	= dt_display($List->work_order_date);
		$work_commence_date = dt_display($List->work_commence_date);
		$date_of_completion = dt_display($List->date_of_completion);
		$work_duration 	= $List->work_duration;
		$section 			= $List->section_type;
		$sectionCode 		= $List->section_abcd;
		$civil_sheetid 	= $List->under_civil_sheetid;
		$sd_perc 			= $List->sd_perc;
		$sd_amt 				= $List->sd_amt;
		//echo $sectionCode;exit;
	}
}
*/
if(isset($_GET['ccno'])){
	$ViewCcno = $_GET['ccno'];
}
if(($ViewCcno != '')&&($ViewCcno != NULL)){
	//$ViewCcno = $_GET['ccno'];
	if($ViewCcno != ""){
		$select_sheet_query 	= 	"SELECT * FROM sheet WHERE computer_code_no = $ViewCcno";
		$select_sheet_sql		= 	mysqli_query($dbConn,$select_sheet_query);
		if(($select_sheet_sql == true)&&(mysqli_num_rows($select_sheet_sql) > 0)) {
			$List = mysqli_fetch_object($select_sheet_sql);
			$sheet_id			= $List->sheet_id;
			$computer_code_no	= $List->computer_code_no;
			$work_name 			= $List->work_name; 
			$short_name 		= $List->short_name; 
			$tech_sanction 	= $List->tech_sanction;
			$hoaid				= $List->hoaid;										//////////////////////////
			$work_order_no 	= $List->work_order_no;
			if(($List->work_order_date != "")||($List->work_order_date != NULL)){
				$work_order_date 	= dt_display($List->work_order_date);
				if($work_order_date == "00/00/0000"){
					$work_order_date 	= "";
				}
			}else{
				$work_order_date 	= "";
			}
			$work_order_cost 	= $List->work_order_cost;
			$work_duration 	= $List->work_duration;
			if(($List->work_commence_date != "")||($List->work_commence_date != NULL)){	
				$work_commence_date = dt_display($List->work_commence_date);
				if($work_commence_date == "00/00/0000"){
					$work_commence_date 	= "";
				}
			}else{
				$work_commence_date 	= "";
			}
			if(($List->date_of_completion != "")||($List->date_of_completion != NULL)){
				$date_of_completion = dt_display($List->date_of_completion);
				if($date_of_completion == "00/00/0000"){
					$date_of_completion 	= "";
				}
			}else{
				$date_of_completion 	= "";
			}
			
			if(($List->work_orders_ext != "")||($List->work_orders_ext != NULL)){
				$work_orders_ext = dt_display($List->work_orders_ext);
				if($work_orders_ext == "00/00/0000"){
					$work_orders_ext 	= "";
				}
			}else{
				$work_orders_ext 	= "";
			}
			
			$agree_no 			= $List->agree_no;
			if(($List->agree_date != "")||($List->agree_date != NULL)){
				$agree_date			= dt_display($List->agree_date);
				if($agree_date == "00/00/0000"){
					$agree_date	= "";
				}
			}else{
				$agree_date 	= "";
			}
			$eic					= $List->eic;
			$contid				= $List->contid;
			$cbdtid				= $List->cbdtid;
			if(($List->pbg_valid_date != "")||($List->pbg_valid_date != NULL)){
				$pbg_valid_date 	= dt_display($List->pbg_valid_date);
				if($pbg_valid_date == "00/00/0000"){
					$pbg_valid_date 	= "";
				}
			}else{
				$pbg_valid_date 	= "";
			}
			$globid				= $List->globid;
			$is_gst_appl		= $List->is_gst_appl;
			if($is_gst_appl == 'Y'){
				$gst_perc_rate = $List->gst_perc_rate;
				$gst_inc_exc = $List->gst_inc_exc;
			}
			$is_less_appl		= $List->is_less_appl;
			$sd_perc 			= $List->sd_perc;
			$sd_amt 				= $List->sd_amt;
			if(($sd_perc != NULL)||($sd_perc != "")){
				$sd_amt = ($work_order_cost*$sd_perc)/100;
			}
			// /echo $sd_amt;exit;
			if(($eic != NULL)||($eic != "")){
				$select_EIC_DET_query = "SELECT a.staffid,a.staffname,a.staffcode,b.designationid,b.designationname,c.sub_sec_id,c.sub_sec_name FROM staff a 
				LEFT JOIN designation b ON (a.designationid = b.designationid) 
				LEFT JOIN sub_section c ON (a.sub_sec_id = c.sub_sec_id) 
				WHERE a.staffid = '$eic'";
				$select_EIC_DET_query_sql = mysqli_query($dbConn,$select_EIC_DET_query);
				if(($select_EIC_DET_query_sql == true)&&(mysqli_num_rows($select_EIC_DET_query_sql) > 0)) {
					$EicList = mysqli_fetch_object($select_EIC_DET_query_sql);
					$staffid				= $EicList->staffid;
					$staffname			= $EicList->staffname;
					$staffcode 			= $EicList->staffcode; 
					$designationname	= $EicList->designationname; 
					$sub_sec_name 		= $EicList->sub_sec_name; 
					//echo $select_EIC_DET_query;exit;
				}
			}
			if(($contid != NULL)||($contid != "")){
				$select_Cont_DET_query = "SELECT name_contractor,addr_contractor,state_contractor FROM contractor WHERE contid = '$contid'";
				$select_Cont_DET_query_sql = mysqli_query($dbConn,$select_Cont_DET_query);
				if(($select_Cont_DET_query_sql == true)&&(mysqli_num_rows($select_Cont_DET_query_sql) > 0)) {
					$ContList = mysqli_fetch_object($select_Cont_DET_query_sql);
					$ContName			= $ContList->name_contractor;
					$ContAddr 			= $ContList->addr_contractor; 
					$state_contractor	= $ContList->state_contractor;
					$select_ST_Mast_DET_query = "SELECT state_code,state_name FROM state_master WHERE state_code = '$state_contractor'";
					$select_ST_Mast_DET_query_sql = mysqli_query($dbConn,$select_ST_Mast_DET_query);
					if(($select_ST_Mast_DET_query_sql == true)&&(mysqli_num_rows($select_ST_Mast_DET_query_sql) > 0)) {
						$StList = mysqli_fetch_object($select_ST_Mast_DET_query_sql);
						$state_name	= $StList->state_name; 
					}
				}
				$select_Cont_BNK_DET_query = "SELECT * FROM contractor_bank_detail WHERE contid = '$contid' AND bk_dt_conf_status='AAO'";
				$select_Cont_BNK_DET_query_sql = mysqli_query($dbConn,$select_Cont_BNK_DET_query);
			}//echo $ContAddr;exit;	$cbdtid
			if(($globid != NULL)||($globid != "")){
				$select_TReg_DET_query = "SELECT pg,pg_per FROM tender_register WHERE globid = '$globid'";
				$select_TReg_DET_query_sql = mysqli_query($dbConn,$select_TReg_DET_query);
				if(($select_TReg_DET_query_sql == true)&&(mysqli_num_rows($select_TReg_DET_query_sql) > 0)) {
					$TrRegList = mysqli_fetch_object($select_TReg_DET_query_sql);
					$pg_value	= $TrRegList->pg; 
					$pg_per 		= $TrRegList->pg_per; 
				}
				if(($pg_value == NULL)||($pg_value == "")){
					$pg_value = ($work_order_cost*$pg_per)/100;
				}
				//echo $pg_value;

				$select_bg_fdr_DET_query = "SELECT bfdid,inst_type,inst_serial_no,inst_bank_name,inst_date,inst_exp_date,inst_amt FROM bg_fdr_details WHERE inst_purpose='PG' AND globid = '$globid'";
				$select_bg_fdr_DET_query_sql = mysqli_query($dbConn,$select_bg_fdr_DET_query);
				if(($select_bg_fdr_DET_query_sql == true)&&(mysqli_num_rows($select_bg_fdr_DET_query_sql) > 0)) {
					$bg_fdrList = mysqli_fetch_object($select_bg_fdr_DET_query_sql);
					$bfdid				= $bg_fdrList->bfdid;
					$master_id			= $bg_fdrList->master_id;
					$inst_type			= $bg_fdrList->inst_type;
					$inst_serial_no	= $bg_fdrList->inst_serial_no; 
					$inst_bank_name	= $bg_fdrList->inst_bank_name; 
					if(($bg_fdrList->inst_date != "")||($bg_fdrList->inst_date != NULL)){
						$inst_date	= dt_display($bg_fdrList->inst_date); 
						if($inst_date == "00/00/0000"){
							$inst_date	= "";
						}
					}else{
						$inst_date 	= "";
					}
					if(($bg_fdrList->inst_exp_date != "")||($bg_fdrList->inst_exp_date != NULL)){
						$inst_exp_date	= dt_display($bg_fdrList->inst_exp_date); 
						if($inst_exp_date == "00/00/0000"){
							$inst_exp_date	= "";
						}
					}else{
						$inst_exp_date = "";
					}
					$inst_amt			= $bg_fdrList->inst_amt; 
				}
			}
			if(($sheet_id != NULL)||($sheet_id != "")){
				$select_ABS_DET_query = "SELECT upto_date_total_amount,upto_dt_sd_rec_amt,pass_order_dt,rbn FROM abstractbook WHERE sheetid = '$sheet_id' ORDER BY rbn DESC LIMIT 1";
				$select_ABS_DET_query_sql = mysqli_query($dbConn,$select_ABS_DET_query);
				if(($select_ABS_DET_query_sql == true)&&(mysqli_num_rows($select_ABS_DET_query_sql) > 0)) {
					$AbsBookList = mysqli_fetch_object($select_ABS_DET_query_sql);
					$upto_date_total_amount	= $AbsBookList->upto_date_total_amount;
					$upto_dt_sd_rec_amt		= $AbsBookList->upto_dt_sd_rec_amt; 
					if(($AbsBookList->pass_order_dt != "")||($AbsBookList->pass_order_dt != NULL)){
						$pass_order_dt	= dt_display($AbsBookList->pass_order_dt);
						if($pass_order_dt == "00/00/0000"){
							$pass_order_dt	= "";
						}
					}else{
						$pass_order_dt = "";
					}
				}
			}
			
			//echo $select_EIC_DET_query;exit;
			/*
			$name_contractor 	= $List->name_contractor;
			$worktype 			= $List->worktype;
			$rebatepercent 	= $List->rebate_percent;
			$section 			= $List->section_type;
			$sectionCode 		= $List->section_abcd;
			$civil_sheetid 	= $List->under_civil_sheetid;
			//echo $sectionCode;exit;
			*/
		}
		//echo $select_sheet_query;exit;
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
			url = "WorkList.php";
			window.location.replace(url);
		}

</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.chosen-container-single .chosen-single{
	height:23px !important;
	line-height: 16px;
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
.dataFont {
		font-weight: bold;
		color: #001BC6;
		font-size: 12px;
		text-align: left;
}
.head-b {
		background: #136BCA;
		border-color: #136BCA;
	}
table.dataTable > thead > tr > th{
	padding:2px !important;
	font-size:11px !important;
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
				<div class="grid_12" align="center">
					<div align="right" class="users-icon-part">&nbsp;</div>
					<blockquote class="bq1 stable" style="overflow:auto">
						<div class="row">
							<div class="box-container box-container-lg" align="center">
								<!--<div class="div1">&nbsp;</div>-->
								<div class="div12">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="center">Work Details Entry Form</div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row">


																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel">CCODE</div>
																	<div class="div4">
																		<input type="text" maxlength="50" class="tboxsmclass" name='txt_ccode' id='txt_ccode' value="<?php if(isset($ViewCcno)){ echo $ViewCcno; } ?>">
																		<input type="hidden" class="tboxsmclass" name='txt_globid' id='txt_globid' value="<?php if(isset($globid)){ echo $globid; } ?>">
																		<input type="hidden" class="tboxsmclass" name='text_sheetid' id='text_sheetid' value="<?php if(isset($sheet_id)){ echo $sheet_id; } ?>">
																		<input type="hidden" class="tboxsmclass" name='txt_loiid' id='txt_loiid' value="<?php if(isset($master_id)){ echo $master_id; } ?>">
																	</div>
																	<div class="div4 lboxlabel hide" id="complete">
																		&emsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#EA253C;"></i> <span style="color:EA253C; top:-4px; position:relative;">Work Completed</span>
																	</div>
																	<div class="div4 lboxlabel hide" id="live">
																		&emsp;<i class="fa fa-check-circle-o" style="font-size:20px; color:#046929;"></i> <span style="color:046929; top:-4px; position:relative;">Work in progress</span>
																	</div>
																	<div class="div1 lboxlabel hide">
																		&nbsp;&nbsp;&nbsp;<input type="button"  class="btn btn-info" name="Go" id="Go" value="GO" style="margin-top:0px;" onClick=""/>
																	</div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel" style="line-height:45px;">Name of Work</div>
																	<div class="div10">
																		<textarea name='txt_workname' maxlength="5000" class="tboxsmclass" id='txt_workname' rows="2"><?php if (isset($work_name)){ echo $work_name; } ?></textarea>
																	</div>
																	<div class="row clearrow"></div>


																	<div class="div2 lboxlabel">Short Name</div>
																	<div class="div10">
																		<input type="text" class="tboxsmclass" maxlength="2000" name='txt_shortname'   id='txt_shortname' value="<?php if (isset($short_name)){ echo $short_name; } ?>">
																	</div>
																	<div class="row clearrow"></div>


																	<div class="div2 lboxlabel">Technical Sanction No.</div>
																	<div class="div4">
																		<input type="text" maxlength="30" class="tboxsmclass" name='text_techsanctionno' id='text_techsanctionno' value="<?php if (isset($tech_sanction)){ echo $tech_sanction; } ?>">
																	</div>
																	<div class="div2 cboxlabel">&nbsp;&nbsp;&nbsp;HOA Code</div>
																	<div class="div4">
																		<select name='cmb_hoa[]' id='cmb_hoa' class="tboxsmclass" multiple="multiple">
																		<?php echo $objBind->BindHoaWithSCode($hoaid); ?>														  
																	</select>
																	</div>
																	<div class="row clearrow"></div>


																	<div class="card-header isappcheck inkblue-card" align="left">&nbsp;Work Order Details</div>
																		<table class="dataTable isappcheck" align="center" width="100%" id="table1">
																			<tr class="label" style="background-color:#FFF">
																				<td align="center">Work Order No.</td>
																				<td align="center">Work Order Date</td>
																				<td align="center">Work Order Amount</td>
																				<!-- <td align="center">&nbsp;&nbsp; CCNO. &nbsp;&nbsp;</td> -->
																				<!-- <td align="center">Work Type</td> -->
																				<td align="center">Work Duration</br>(Months)</td>
																				<td align="center">Date of</br>Commencement</td>
																				<td align="center">Scheduled Date of</br>Completion</td>
																				<td align="center">Work Extension</br><span id="ExtenData" class="efont ptr SDBGData" style="font-weight:bold;">[ <i class="fa fa-folder-open-o" style="font-size:13px; top:1px; position:relative"></i> View All ]</span></td>
																			</tr>
																			
																			<tr>
																				<td align="center" style="width:70%"><input type="text" maxlength="100" class="tboxsmclass" name="txt_workorderno" id="txt_workorderno" value="<?php if (isset($work_order_no)){ echo $work_order_no; } ?>"></td>
																				<td align="center" style="width:50%"><input type="text" class="tboxsmclass datepicker" style="text-align:center;" name="txt_workorderdate" id="txt_workorderdate" value="<?php if (isset($work_order_date)){ echo $work_order_date; } ?>"></td>
																				<td align="center" style="width:50%"><input type="text" maxlength="12" class="tboxsmclass" style="text-align:right;" name="txt_work_value" id="txt_work_value" onKeyPress="return event.charCode >= 48 && event.charCode <= 57" value="<?php if (isset($work_order_cost)){ echo $work_order_cost; } ?>"></td>
																				<!-- <td align="center" style="width:100%"><input type="text" maxlength="50" class="tboxsmclass" style="text-align:center;" name="txt_computercodeno" id="txt_computercodeno" readonly=""></td> -->
																				<!--<td align="center">
																					<select name="cmb_worktype" id="cmb_worktype">
																						<option value="">---- Select ----</option>
																						<option value="1">MAJOR WORKS</option>
																						<option value="2">MINOR WORKS</option>
																					</select>
																				</td>-->
																				<td align="center"><input type="text" class="tboxsmclass" maxlength="11" style="text-align:center;" name="workduration" id="workduration" onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="<?php if (isset($work_duration)){ echo $work_duration; } ?>"></td>
																				<td align="center"><input type="text" class="tboxsmclass datepicker" style="text-align:center;" name="workcommencedate" id="workcommencedate" value="<?php if (isset($work_commence_date)){ echo $work_commence_date; } ?>"></td>
																				<td align="center"><input type="text" class="tboxsmclass datepicker" style="text-align:center;" name="txt_dateofcompletion" id="txt_dateofcompletion" value="<?php if (isset($date_of_completion)){ echo $date_of_completion; } ?>"></td>
																				<td align="center"><input type="text" class="tboxsmclass" readonly="" style="text-align:center;" name="txt_work_ext" id="txt_work_ext" value="<?php if (isset($work_orders_ext)){ echo $work_orders_ext; } ?>"></td>
																			</tr>
																		</table>
																	</div>
																	<div class="row clearrow"></div>


																	<!--<div class="div2 lboxlabel">Work Order No.</div>
																	<div class="div3">
																		<input type="text" class="tboxsmclass" name='txt_workorderno'   id='txt_workorderno' value="">
																	</div>
																	<div class="div3 lboxlabel">&nbsp;&nbsp;Work Order Value (&#8377;)</div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass" name='txt_work_value'   id='txt_work_value' value="">
																	</div>
																	<div class="row clearrow"></div>

																	<div class="div2 lboxlabel" align="center">Work Order Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
																	<div class="div3">
																		<input type="text" class="tboxsmclass datepicker" name='txt_workorderdate'   id='txt_workorderdate' value="">
																	</div>
																	<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Duration of Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
																	<div class="div2">
																		<input type="text" class="tboxsmclass" name='txt_workduration' id='txt_workduration'   onKeyPress="return isIntegerValueWithLimit(event,this,2);" value="">
																	</div>
																	<div class="div2" align="left"><span style="font-size:10px">&nbsp;Months</span> <span style="font-size:10px">(Max. 3 digit)</span></div>
																	<div class="row clearrow"></div>	-->

																	
																	<div class="card-header inkblue-card" align="left">Agreement Details Entry </div>
																	<div class="row clearrow"></div>
																	<div class="div2 lboxlabel" align="center">Agreement No. </div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass" maxlength="50" name='txt_agreementno'   id='txt_agreementno' value="<?php if (isset($agree_no)){ echo $agree_no; } ?>">
																	</div>

																	<div class="div2 cboxlabel" style="text-align:left;">&nbsp;&nbsp;Agreement Date</div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass datepicker" readonly="" name='txt_agreementdate' id='txt_agreementdate' value="<?php if (isset($agree_date)){ echo $agree_date; } ?>">
																	</div>
																	<!--<div class="div3 lboxlabel" align="center">&nbsp;&nbsp;Scheduled Date of Completion &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;</div>
																	<div class="div4">
																		<input type="text" class="tboxsmclass datepicker" name='txt_dateofcompletion' id='txt_dateofcompletion'    value="">
																	</div>-->
																	<div class="row clearrow"></div>


																	<!--<div class="card-header inkblue-card" align="left">Engineer Details Entry </div>
																	<div class="row clearrow"></div>

																	<div class="div2 lboxlabel">Engineer IC No.</div>
																	<div class="div4">
																		<input type="text" maxlength="100" class="tboxsmclass" name='txt_ICNO'   id='txt_ICNO' value="<?php if (isset($staffcode)){ echo $staffcode; } ?>">
																		<input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="<?php if (isset($staffid)){ echo $staffid; } ?>" >
																	</div>
																	<div class="div2 lboxlabel">&nbsp;&nbsp;Engineer Name</div>
																	<div class="div4">
																		<input type="text" maxlength="250" class="tboxsmclass" name='txt_enggname'   id='txt_enggname' value="<?php if (isset($staffname)){ echo $staffname; } ?>">
																	</div>
																	<div class="row clearrow"></div>


																	<div class="div2 lboxlabel">Engineer Designation</div>
																	<div class="div4">
																		<input type="text" maxlength="50" class="tboxsmclass" name='txt_enggdesig' id='txt_enggdesig' value="<?php if (isset($designationname)){ echo $designationname; } ?>"> 
																	</div>
																	<div class="div2 lboxlabel">&nbsp;&nbsp;Engineer Section</div>
																	<div class="div4">
																		<input type="text" maxlength="100" class="tboxsmclass" name='txt_enggroup' id='txt_enggroup' value="<?php if (isset($sub_sec_name)){ echo $sub_sec_name; } ?>">
																	</div>
																	<div class="row clearrow"></div>-->


																	<div class="card-header inkblue-card" align="left">EIC & Contractor & Bank Details Entry</div>
																	<div class="row clearrow"></div>
																	<div class="row">
																		<div class="div2 lboxlabel">EIC Name</div>
																		<div class="div4">
																			<select name='cmb_eic' id='cmb_eic' class="tboxsmclass"  >
																				<option value="">--------------- Select ---------------</option>
																				<?php echo GetAllStaff($staffid); ?>
																			</select>
																			<input type="hidden" class="tboxsmclass" name='txt_eic_name' id='txt_eic_name' value="<?php if (isset($staffname)){ echo $staffname; } ?>" >
																			
																			<input type="hidden" maxlength="100" class="tboxsmclass" name='txt_ICNO'   id='txt_ICNO' value="<?php if (isset($staffcode)){ echo $staffcode; } ?>">
																			<input type="hidden" class="tboxsmclass" name='txt_staffid' id='txt_staffid' value="<?php if (isset($staffid)){ echo $staffid; } ?>" >
																		</div>
																		<div class="div2 lboxlabel">&nbsp;Contractor Name</div>
																		<div class="div4">
																			<select name='cmb_contractorname' id='cmb_contractorname' class="tboxsmclass"  >
																				<option value="">--------------- Select ---------------</option>
																				<?php echo $objBind->BindCont($contid); ?>
																			</select>
																		</div>
																		<!--<div class="div1">
																			<input type="button" name="add_new_cont" id="add_new_cont" class="buttonstyle" value=" + New ">
																		</div>-->
																	</div>
																	<!--<div class="row clearrow"></div>-->


																	<!--<div class="row">
																		<div class="div3 lboxlabel">&nbsp;Contractor Address</div>
																		<div class="div4">
																			<textarea maxlength="500" class="tboxsmclass" name='txt_contadd'   id='txt_contadd' readonly ><?php if(isset($ContAddr)){ echo $ContAddr; } ?></textarea>
																		</div>
																	</div>
																	<div class="row clearrow"></div>

																	<div class="row">
																		<div class="div3 lboxlabel">&nbsp;Contractor State</div>
																		<div class="div4">
																			<input type="text" maxlength="50" class="tboxsmclass" name='txt_state'   id='txt_state' readonly  value="<?php if (isset($state_name)){ echo $state_name; } ?>">
																		</div>
																	</div>-->
																	<div class="row clearrow"></div>
																	<input type="hidden" class="tboxsmclass" name='hid_txt_contname' id='hid_txt_contname' readonly  value="<?php if (isset($ContName)){ echo $ContName; } ?>">
																	
																	<?php 
																	if(($select_Cont_BNK_DET_query_sql == true)&&(mysqli_num_rows($select_Cont_BNK_DET_query_sql) > 0)) {
																	?>
																	<div class="row" id="Cont_Bank">
																		<!--<div class="card-header inkblue-card" align="left">Bank Detail</div>-->
																		<table  class='itemtable etable'  width='100%'>
																			<tr style='background-color:#EAEAEA'class ='lboxlabe'>
																				<th >Select</th>
																				<th>Account No.</th>
																				<th>Bank Name</th>
																				<th>Branch Name</th>
																				<th>Ifsc Code</th>
																			</tr>
																			<tr>
																				<?php 
																				while($ContBnkList = mysqli_fetch_object($select_Cont_BNK_DET_query_sql)){
																					$CheckedStr = '';
																					if(isset($cbdtid)){
																						if($cbdtid == $ContBnkList->cbdtid){
																							$CheckedStr = ' checked="checked"';
																						}
																					}
																					$cbdtid	= $ContBnkList->cbdtid; 
																					$bank_acc_hold_name	= $ContBnkList->bank_acc_hold_name; 
																					$bank_acc_no		= $ContBnkList->bank_acc_no; 
																					$bank_name			= $ContBnkList->bank_name; 
																					$branch_address		= $ContBnkList->branch_address; 
																					$ifsc_code			= $ContBnkList->ifsc_code; 
																				
																			?>
																				<td align='center'><input type='checkbox' <?php echo $CheckedStr; ?> class='tboxsmclass' name='bank_checkbox[]' id='bank_checkbox' value="<?php if (isset($cbdtid)){ echo $cbdtid; } ?>"></td>
																				<td align='left'><input type='text' class='tboxsmclass' readonly='' name='txt_bank_accno[]' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)' value="<?php if (isset($bank_acc_no)){ echo $bank_acc_no; } ?>"></td>
																				<td align='left'><input type='text' class='tboxsmclass' readonly='' name='txt_bank_name[]' id='txt_bank_name' value="<?php if (isset($bank_name)){ echo $bank_name; } ?>"></td>
																				<td align='left'><input type='text' class='tboxsmclass' readonly='' name='txt_bank_branch[]' id='txt_bank_branch' value="<?php if (isset($branch_address)){ echo $branch_address; } ?>"></td>
																				<td align='left'><input type='text' class='tboxsmclass' readonly='' name='txt_bank_ifsc[]' id='txt_bank_ifsc' value="<?php if (isset($ifsc_code)){ echo $ifsc_code; } ?>"></td>
																			</tr>
																			<?php } ?>
																		</table>
																	</div>
																	<div class="row clearrow"></div>
																	<?php } ?>
																	<div class="card-header inkblue-card" align="left">PG Entry</div>
																	<div class="row clearrow"></div>

																	<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;PBG ( % )</div>
																	<div class="div1">
																		<input type="text" maxlength="10" class="tboxsmclass" name='txt_pg_per' id='txt_pg_per' value="<?php if (isset($pg_per)){ echo $pg_per; } ?>">
																	</div>
																	<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PBG Value ( &#8377; )</div>
																	<div class="div2">
																		<input type="text" style="text-align:right;" maxlength="12" class="tboxsmclass" name='txt_pg_value' id='txt_pg_value' value="<?php if (isset($pg_value)){ echo $pg_value; } ?>">
																	</div>
																	<!--	<div class="div3 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PBG Validity Date</div>
																	<div class="div2">
																		<input type="text" readonly="" class="tboxsmclass datepicker" name='txt_pg_valdidate' id='txt_pg_valdidate' value="<?php if (isset($pbg_valid_date)){ echo $pbg_valid_date; } ?>">
																	</div>	-->
																	<div class="row clearrow"></div>


																	<table class="dataTable etable " align="center" width="100%" id="PGTable">
																		<tr class="label" style="background-color:#FFF">
																			<td class="" align="center">PG Type</td>
																			<td class="" align="center">BG/FDR Serial No.</td>
																			<td class="" align="center">Bank Name</td>
																			<td class="" align="center">BG/FDR Date</td>
																			<td class="" align="center">Expiry Date</td>
																			<td class="" align="center">PG Amount ( &#8377; )</td>
																			<td class="" align="center">Action</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<select name="cmd_instype_0" id ="cmd_instype_0" class="tboxsmclass">  
																					<option value="">---- Select ---- </option>
																					<option value="BG">Bank Guarantee</option>
																					<option value="FDR">Fixed Deposit Receipt</option>
																				</select>
																			</td>
																			<td align="center"><input type="text" name="instrunum_0" id ="instrunum_0" class="tboxsmclass" style="width:110px;"></td>
																			<td align="center"><input type="text" maxlength="30" class="tboxsmclass" style="width:100px;" name="txt_bankname_pg_0" id="txt_bankname_pg_0"></td>
																			<td align="center"><input type="text"  placeholder="DD/MM/YYYY" class="tboxsmclass datepicker date"style="width:100px;" readonly name="txt_date_pg_0" id="txt_date_pg_0"></td>
																			<td align="center"><input type="text" placeholder="DD/MM/YYYY" class="tboxsmclass datepicker expdate" style="width:100px;" readonly name="txt_expir_date_pg_0" id="txt_expir_date_pg_0"></td>
																			<td align="center"><input type="text" maxlength="12" class="tboxsmclass" style="width:100px;" name="txt_part_amt_0" id="txt_part_amt_0"></td>
																			<td align="center"><input type="button" name="pg_add" id="pg_add"  value="ADD" class="fa btn btn-info"></td>
																		</tr>
																		<?php if(isset($bfdid)){ ?>
																		<tr>
																			<td align="center"><input type="text" name="cmd_instype[]" class="tboxsmclass" style="width:110px;" value="<?php if (isset($inst_type)){ echo $inst_type; } ?>"></td>
																			<td align="center"><input type="text" name="instrunum[]" class="tboxsmclass" style="width:100px;" value="<?php if (isset($inst_serial_no)){ echo $inst_serial_no; } ?>"></td>
																			<td align="center"><input type="text" name="txt_bankname_pg[]" class="tboxsmclass" style="width:100px;" value="<?php if (isset($inst_bank_name)){ echo $inst_bank_name; } ?>"></td>
																			<td align="center"><input type="text" name="txt_date_pg[]" class="tboxsmclass" style="width:100px;" readonly value="<?php if (isset($inst_date)){ echo $inst_date; } ?>"></td>
																			<td align="center"><input type="text" name="txt_expir_date_pg[]" class="tboxsmclass" style="width:100px;" readonly value="<?php if (isset($inst_exp_date)){ echo $inst_exp_date; } ?>"></td>
																			<td align="center"><input type="text" name="txt_part_amt[]" class="tboxsmclass EmAmt" style="text-align:right; width:100px;" value="<?php if (isset($inst_amt)){ echo $inst_amt; } ?>"></td>
																			<td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td>
																		</tr>
																		<?php } ?>
																		<input type="hidden" name="text_totalamt" id ="text_totalamt" class="textbox-new" style="width:110px;">
																	</table>
																	<div class="row clearrow"></div>


																	<div class="card-header inkblue-card" align="left">GST Details Entry </div>
																	<div class="row clearrow"></div>

																	<div class="div3 lboxlabel">Whether GST applicable</div>
																	<div class="div2 no-padding-lr gstselapp">
																		<div class="inputGroup div6">
																			<input id="gst_app_yes" name="gstapplicable" type="radio" value="Y" <?php if (isset($is_gst_appl)){ if($is_gst_appl == 'Y'){ echo "checked='checked'"; } } ?>/>
																			<label for="gst_app_yes" style="padding:1px 0px; width:95%; font-size:11px;" class="cboxlabel"> &nbsp; YES</label>
																		</div>
																	<!--</div>
																	<div class="div1 gstselapp" style="padding-left:10px; width:10%;">-->
																		<div class="inputGroup div6">
																			<input id="gst_app_no" name="gstapplicable" type="radio" value="N" <?php if (isset($is_gst_appl)){ if($is_gst_appl == 'N'){ echo "checked='checked'"; } } ?>/>
																			<label for="gst_app_no" style="padding:1px 0px; width:95%; font-size:11px;" class="cboxlabel"> &nbsp; NO</label>
																		</div>
																	</div>

																	<div class="div2 lboxlabel"l>&emsp;LCESS Applicable</div>
																	<div class="div4 no-padding-lr">
																		<div class="inputGroup div6">
																			<input id="lcesapp_y" name="lcesapp" type="radio" value="Y" <?php if (isset($is_less_appl)){ if($is_less_appl == 'Y'){ echo "checked='checked'"; } } ?>/>
																			<label for="lcesapp_y" style="padding:1px 0px 0px 10px; width:80%; font-size:11px;" class="cboxlabel"> &nbsp; YES</label>
																		</div>
																	
																		<div class="inputGroup div6">
																			<input id="lcesapp_n" name="lcesapp" type="radio" value="N" <?php if (isset($is_less_appl)){ if($is_less_appl == 'N'){ echo "checked='checked'"; } } ?>/>
																			<label for="lcesapp_n" style="padding:1px 15px; width:80%; font-size:11px;" class="cboxlabel"> &nbsp; NO</label>
																		</div>
																	</div>
																	<div class="row clearrow"></div>
																	<?php
																	$GstClass = "hide";
																	if(isset($is_gst_appl)){
																		if($is_gst_appl == "Y"){
																			$GstClass = "";
																		}
																	}
																	?>
																	<div class="div3 lboxlabel <?php echo $GstClass; ?> gstapplicab">GST Rate on Work Order ( % )</div>
																	<div class="div2 <?php echo $GstClass; ?> gstapplicab">
																		<input type="text" class="tboxsmclass" name='txt_gst_value'   id='txt_gst_value' value="<?php if (isset($gst_perc_rate)){ echo $gst_perc_rate; } ?>">
																	</div>
																	<div class="div2 lboxlabel <?php echo $GstClass; ?> gstapplicab">&emsp;GST Incl./Excl. </div>
																	<div class="div4 no-padding-lr <?php echo $GstClass; ?> gstapplicab">
																		<div class="inputGroup div6">
																			<input id="gst_inc" name="gstincexc" type="radio" value="I" <?php if (isset($gst_inc_exc)){ if($gst_inc_exc == 'I'){ echo "checked='checked'"; } } ?>/>
																			<label for="gst_inc" style="padding:1px 0px 0px 10px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; INCLUSIVE</label>
																		</div>
																	
																		<div class="inputGroup div6">
																			<input id="gst_exc" name="gstincexc" type="radio" value="E" <?php if (isset($gst_inc_exc)){ if($gst_inc_exc == 'E'){ echo "checked='checked'"; } } ?>/>
																			<label for="gst_exc" style="padding:1px 15px; width:80%; font-size:11px;" class="lboxlabel"> &nbsp; EXCLUSIVE</label>
																		</div>
																	</div>
																	<div class="row clearrow <?php echo $GstClass; ?> gstapplicab"></div>
																	<!--<div class="div2 lboxlabel">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LCESS Applicable</div>
																	<div class="div2 lboxlabel">
																		<input type="radio" class="lboxlabel lcess_app" name="lcess_app" value="Y" id="lcess_app_y" />
																		<label for="y">YES</label>
																		<input type="radio" class="lboxlabel lcess_app" name="lcess_app" value="N" id="lcess_app_n" />
																		<label for="z">NO</label>
																	</div>-->
																	<div class="row smclearrow"></div>

																	<div class="card-header inkblue-card" align="left">Other Recovery Entry</div>
																	<div class="row clearrow"></div>

																	<div class="div2 lboxlabel">&nbsp;Security Deposit ( % )</div>
																	<div class="div2">
																		<input type="text" maxlength="10" class="tboxsmclass" name='txt_sd_per' id='txt_sd_per' value="<?php if (isset($sd_perc)){ echo $sd_perc; } ?>">
																	</div>
																	
																	<div class="div2 rboxlabel">Total SD Value ( &#8377; )&nbsp;&emsp;</div>
																	<div class="div2 rboxlabel">
																		<input type="text" maxlength="12" readonly="" class="tboxsmclass" name='txt_sd_value' id='txt_sd_value' value="<?php if (isset($sd_amt)){ echo $sd_amt; } ?>">
																	</div>

																	<!--	<div class="div2 cboxlabel">&nbsp;Last Payment Date</div>
																	<div class="div2">
																		<input type="text" class="tboxsmclass datepicker" readonly="" name='txt_paymentdate' id='txt_paymentdate' value="<?php //if (isset($pass_order_dt)){ echo $pass_order_dt; } ?>">
																	</div>
																	<div class="row clearrow"></div>


																	<div class="div2 lboxlabel">Upto Date Security Deposit</div>
																	<div class="div4 cboxlabel">
																		<input type="text" maxlength="12" class="tboxsmclass" name='txt_securitydepoe' id='txt_securitydepoe' value="<?php //if (isset($upto_dt_sd_rec_amt)){ echo $upto_dt_sd_rec_amt; } ?>">
																	</div>


																	<div class="div2 cboxlabel">Upto Date Value of Work</div>
																	<div class="div4 cboxlabel">
																		<input type="text" maxlength="12" class="tboxsmclass" name='txt_valuework' id='txt_valuework' value="<?php //if (isset($upto_date_total_amount)){ echo $upto_date_total_amount; } ?>">
																	</div>
																	<div class="row clearrow"></div>	-->
																	<div class="row smclearrow"></div>
																	<div class="div12" align="center">
																		<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" data-type="submit" value=" Submit "/>
																		<input type="button" class="btn btn-info" name="btn_back" id="btn_back" value="Back" onClick="goBack();"/>
																	</div>
																	<div class="row smclearrow"></div>
																	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--<div class="div1">&nbsp;</div>-->
							</div>
						</div>
					</blockquote>
				</div>
			</div>
		</div>
            <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
	<script>
		$("#cmb_hoa").chosen();
		$("#cmb_eic").chosen();
		$("#civil_workorderno").chosen();
		$("#section").chosen();
		$("#section_code").chosen();
		
		//$(".gstapplicab").hide();
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
		$('body').on("change",".gstselapp", function(e){ 
			var Gstcheckval = $('input[name="gstapplicable"]:checked').val();  //alert(Gstcheckval);
			if(Gstcheckval == 'Y'){ 
				$(".gstapplicab").removeClass('hide');
			}else if(Gstcheckval == 'N'){ 
				$(".gstapplicab").addClass('hide');
			}
		});
		$('body').on("change","#cmb_eic", function(e){ 
			$("#txt_eic_name").val('');
			var eicname = $("#cmb_eic option:selected").attr("data-name"); 
			$("#txt_eic_name").val(eicname);
		});
		
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
			var RowStr = '<tr><td><input type="text" name="cmd_instype[]" class="tboxsmclass" style="width:100px;" value="'+InstType+'"></td><td><input type="text" name="instrunum[]" class="tboxsmclass" style="width:100px;" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" class="tboxsmclass" style="width:100px;" value="'+BankName+'"></td><td><input type="text" name="txt_date_pg[]" class="tboxsmclass" style="width:100px;" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]" class="tboxsmclass" style="width:100px;" readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_part_amt[]" class="tboxsmclass EmAmt" style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
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
		$("#hid_txt_contname").val('');
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
					var BankStr = "<div class='card-header inkblue-card' align='left'>Bank Detail</div>";
						BankStr += "<table  class='itemtable etable'  width='100%'>";
						BankStr += "<tr style='background-color:#EAEAEA'class ='lboxlabe'><th>Select</th>";
						BankStr += "<th>Account No.</th>";
						BankStr += "<th>Bank Name</th>";
						BankStr += "<th>Branch Name</th>";
						BankStr += "<th>Ifsc Code</th></tr>";
					$.each(data, function(index, element) {
						var ConName 	= $("#hid_txt_contname").val(element.name_contractor);
						var ConAdress 	= $("#txt_contadd").val(element.addr_contractor);
						var ConState  	= $("#txt_state").val(element.state_contractor);
						BankStr += "<tr>";
						BankStr += "<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox[]' id='bank_checkbox' value='"+element.cbdtid+"'></td>";
						BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_accno[]' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)'  value='"+element.bank_acc_no+"' ></td>";
						BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_name[]' id='txt_bank_name'  value='"+element.bank_name+"' ></td>";
						BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_branch[]' id='txt_bank_branch'  value='"+element.branch_address+"' ></td>";
						BankStr +="<td align='left'><input type='text' readonly='' class='tboxsmclass' name='txt_bank_ifsc[]' id='txt_bank_ifsc'  value='"+element.ifsc_code+"' ></td></tr>";
					});
					BankStr += "</table>";
					BankStr += "<div class='clearrow'></div>";
					$("#Cont_Bank").html(BankStr);
				}
			}
		});
	});
	$('#txt_pg_per').change(function() {
		var Pgper= $(this).val(); 
		var Workvalue = $("#txt_work_value").val(); 
		$("#txt_pg_value").val('');
			var PGBvalue= ((Number(Pgper) / 100) *Number(Workvalue)).toFixed();
			$("#txt_pg_value").val(PGBvalue); 
	});
	$('#txt_sd_per').change(function() {
		var SDper= $(this).val();
		var Workvalue = $("#txt_work_value").val(); 
		$("#txt_sd_value").val('');
			var SDvalue= ((Number(SDper) / 100) *Number(Workvalue)).toFixed();
			$("#txt_sd_value").val(SDvalue); 
	});
	$("#btn_save").click(function(){ //alert(1);
		var pgamt = $("#txt_pg_value").val(); //alert(pgamt);
		var totalamt = $("#text_totalamt").val(); //alert(totalamt);
		var text_sheetid = $("#text_sheetid").val();
		if(text_sheetid == ""){
			if(pgamt!=totalamt){
				var a="PG Amount is not Equal to the Total BG/FDR Amount";
				BootstrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				$('#val_date').text(a);
			}
		}
	});
	//////// Auto load and trigger button //////
	/*
	$(window).load(function() {
		var Ccno = $("#txt_ccode").val();
		$("#btn_save").removeClass("hide");
		$("#complete").addClass("hide");
		$("#live").addClass("hide");
		
		if(Ccno != ''){
			$("#Go").trigger( "click" );
		}
	});
	*/
	
	$("body").on("click","#ExtenData", function(event){
		var SheetId = $("#text_sheetid").val();
		if(SheetId != ''){
			$.ajax({ 
				type: 'POST', 
				url: 'ajax/FindAllWorkExtensions.php', 
				data: { SheetId: SheetId }, 
				dataType: 'json',
				success: function (data) {   //alert(data['computer_code_no']);
					if(data != null){
						var TableStr = '<table class="dynamicTable" align="center" width="100%" id="RecTable">';
						TableStr += '<thead><tr><th class="cboxlabel">SNo.</th><th class="cboxlabel">Description</th><th class="cboxlabel">Extension Date</th></tr></thead>';
						TableStr += '<tbody>';
						var Sno = 1; 
						$.each(data, function(index, element) {
							var RowCls = "";
							TableStr += '<tr class="BankRow '+RowCls+'"><td class="cboxlabel">'+Sno+'</td><td class="cboxlabel">Extension - '+Sno+'</td><td class="cboxlabel">'+element.work_orders_ext+'</td></tr>';
							Sno++;
						});
						TableStr += '</tbody>';
						TableStr += '</table>';
						BootstrapDialog.show({
							title: 'Work Extensions List',
							message: TableStr,
							onshown: function(dialogRef){
							},
							buttons: [{
								label: 'Close',
								cssClass: 'btn btn-info',
								action: function(dialog) {
									dialog.close();
								}
							}]
						});
					}
				}
			});
		}
	});
	
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
		//$("#cmb_hoa").chosen("destroy");
		$("#cmb_hoa").val('').trigger('chosen:updated');
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
		$("#cmd_instype_0").val('');
		$("#instrunum_0").val('');
		$("#txt_bankname_pg_0").val('');
		$("#txt_date_pg_0").val('');
		$("#txt_expir_date_pg_0").val('');
		$("#txt_part_amt_0").val('');
		$("#lcess_app_y").prop("checked",false);
		$("#lcess_app_n").prop("checked",false);
		$("#gst_app_yes").prop('checked', false);
		$("#gst_app_no").prop('checked', false);
		$("#gst_inc").prop('checked', false);
		$("#gst_exc").prop('checked', false);
		$("#Cont_Bank").html('');
		$("#text_totalamt").val('');
		$("#PGTable").find("tr:gt(1)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/GetWorkMasterDetail.php', 
			data: { ccno: ccno}, 
			dataType: 'json',
			success: function (data) { 
				/*var Result1 = data['row1']; 
				var Result2 = data['row2']; //alert(Result2);
				var Result3 = data['row3'];*/

				var CheckVal = data['CheckVal'];
				//alert(CheckVal);

				if(CheckVal == 1){
					var FromSheetData = data['SheetData'];
					var FromWorkData = null;	//undef
				//alert(JSON.stringify(FromSheetData));
				}else if(CheckVal == 0){
					var FromSheetData = null;
					var FromWorkData = data['WorkData'];//undef
				//alert(JSON.stringify(FromWorkData));
				}
				var WorkProcess 	= data['WORKPROCESS'];//0
				var ContBankDetails = data['ContBankDet'];//null
				var BgFdrDetails 	= data['BgFdrDet'];//null

				//alert(JSON.stringify(WorkProcess));
				//alert(JSON.stringify(ContBankDetails));
				//alert(JSON.stringify(BgFdrDetails));

				var BankStr  = "<table  class='itemtable etable'  width='100%'>";
					BankStr += "<tr style'background-color:#EAEAEA'class ='lboxlabe'><th >Select</th>";
					BankStr += "<th>Account No.</th>";
					BankStr += "<th>Bank Name</th>";
					BankStr += "<th>Branch Name</th>";
					BankStr += "<th>Ifsc Code</th></tr>";
				if((FromSheetData != null) || (FromWorkData != null)){
					if(FromSheetData != null){
						//$.each(FromSheetData, function(index, element) {
						//alert(JSON.stringify(FromSheetData));
						if(FromSheetData.active == 2){
							$("#btn_save").addClass("hide");
							$("#complete").removeClass("hide");
							$("#live").addClass("hide");
						}else{
							$("#btn_save").removeClass("hide");
							$("#complete").addClass("hide");
							$("#live").removeClass("hide");
						}
						var HoaId 		= FromSheetData.hoaid;
						var ContName	= FromSheetData.name_contractor;
						var Contid	   = FromSheetData.contid;
						$("#txt_globid").val(FromSheetData.globid );
						$("#text_sheetid").val(FromSheetData.sheetid );
						$("#txt_loiid").val(FromSheetData.loa_pg_id);
						$("#txt_workname").val(FromSheetData.work_name);
						$("#txt_workorderno").val(FromSheetData.work_order_no);
						$("#txt_work_value").val(FromSheetData.work_order_cost);
						$("#txt_workorderdate").val(FromSheetData.work_order_date);
						$("#txt_workduration").val(FromSheetData.work_duration);
						$("#text_techsanctionno").val(FromSheetData.ts_no);
						$("#txt_agreementdate").val(FromSheetData.agree_date);
						$("#txt_dateofcompletion").val(FromSheetData.date_of_completion);
						$("#cmb_hoa").chosen("destroy");
						var SplitHoa = HoaId.split(",");
						for(var i=0; i<SplitHoa.length; i++){
							var Hoa = SplitHoa[i];
							$("#cmb_hoa").find("option[value="+Hoa+"]").prop("selected", "selected");
						}
						$("#cmb_hoa").chosen();
						$("#txt_ICNO").val(FromSheetData.staffcode);
						$("#txt_enggname").val(FromSheetData.staffname);
						$("#txt_enggdesig").val(FromSheetData.designationname);
						$("#txt_enggroup").val(FromSheetData.section_name);
						$("#txt_staffid").val(FromSheetData.staffid);
						$('#cmb_contractorname').chosen('destroy');
						$("#cmb_contractorname").append('<option selected="selected" value="'+Contid+'">'+ContName+'</option>');
						$('#cmb_contractorname').chosen();
						$("#txt_contadd").val(FromSheetData.addr_contractor);
						$("#txt_state").val(FromSheetData.state_contractor);
						$("#txt_pg_per").val(FromSheetData.pg_per);
						$("#txt_pg_value").val(FromSheetData.pg_amt);
						$("#txt_pg_valdidate").val(FromSheetData.pg_validity);
						$("#txt_gst_value").val(FromSheetData.gst_perc_rate);
						$("#txt_sd_per").val(FromSheetData.sd_perc);
						$("#txt_sd_value").val(FromSheetData.sd_amt);
						if(FromSheetData.is_less_appl == "Y"){
							$("#lcess_app_y").prop("checked",true);
						}else{
							$("#lcess_app_n").prop("checked",true);
						}
						//});
						$.each(ContBankDetails, function(index, element) {
							BankStr += "<tr>";
							BankStr += "<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox[] id='bank_checkbox' checked='checked' value="+element.cbdtid+"></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_accno_0' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)'  value="+element.bank_acc_no+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_name_0' id='txt_bank_name'  value="+element.bank_name+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_branch_0' id='txt_bank_branch'  value="+element.branch_address+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_ifsc_0' id='txt_bank_ifsc'  value="+element.ifsc_code+" ></td></tr>";
						});
						BankStr += "</table>";
						$("#Cont_Bank").html(BankStr);
						$.each(BgFdrDetails, function(index, element) {
							var Bgid	     = element.bfdid; 
							var InstType 	 = element.inst_type;
							var InstNum 	 = element.inst_serial_no;
							var BankName   	 = element.inst_bank_name;
							var DateofIssue  = element.inst_date;
							var DateofExpiry = element.inst_exp_date; 
							var AmtDetail	 = element.inst_amt;  //alert(AmtDetail);
							var RowStr = '<tr><td><input type="hidden" name="txt_Bfdid[]" id="txt_Bfdid" readonly class="tboxsmclass" style="width:100px;" value="'+Bgid+'"><input type="text" name="cmd_instype[]" readonly id="cmd_instype" class="tboxsmclass" style="width:100px;" value="'+InstType+'"></td><td><input type="text" readonly name="instrunum[]" id="instrunum" class="tboxsmclass" readonly style="width:100px;" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg" readonly class="tboxsmclass" style="width:100px;" value="'+BankName+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg" class="tboxsmclass" readonly style="width:100px;" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg" class="tboxsmclass" style="width:100px;" readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt" class="tboxsmclass EmAmt" readonly style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
							
							$("#PGTable").append(RowStr);
							$("#txt_Bfdid_0").val('');
							$("#cmd_instype_0").val('');
							$("#instrunum_0").val('');
							$("#txt_bankname_0").val('');
							$("#txt_date_pg_0").val('');
							$("#txt_expir_date_pg_0").val('');
							$("#txt_part_amt_0").val('');
							TotalUnitAmountCalc();
						});
					}else if(FromWorkData != null){
					
						//$.each(FromWorkData, function(index, element) {
						var Hoaid 		= FromWorkData.hoaid;
						//alert(hoaid);
						var ContName	= FromWorkData.name_contractor;
						var Contid	   = FromWorkData.contid;
						$("#txt_globid").val(FromWorkData.globid );
						$("#text_sheetid").val(FromWorkData.sheetid );
						$("#txt_loiid").val(FromWorkData.loa_pg_id);
						$("#txt_workname").val(FromWorkData.work_name);
						$("#txt_workorderno").val(FromWorkData.work_order_no);
						$("#txt_work_value").val(FromWorkData.work_order_cost);
						$("#txt_workorderdate").val(FromWorkData.work_order_date);
						$("#txt_workduration").val(FromWorkData.work_duration);
						$("#text_techsanctionno").val(FromWorkData.ts_no);
						$("#txt_agreementdate").val(FromWorkData.agree_date);
						$("#txt_dateofcompletion").val(FromWorkData.date_of_completion);
						$("#cmb_hoa").chosen("destroy");
						var SplitHoa = Hoaid.split(",");
						for(var i=0; i<SplitHoa.length; i++){
							var Hoa = SplitHoa[i];
							$("#cmb_hoa").find("option[value="+Hoa+"]").prop("selected", "selected");
						}
						$("#cmb_hoa").chosen();
						$("#txt_ICNO").val(FromWorkData.staffcode);
						$("#txt_enggname").val(FromWorkData.staffname);
						$("#txt_enggdesig").val(FromWorkData.designationname);
						$("#txt_enggroup").val(FromWorkData.section_name);
						$("#txt_staffid").val(FromWorkData.staffid);
						$('#cmb_contractorname').chosen('destroy');
						$("#cmb_contractorname").append('<option selected="selected" value="'+Contid+'">'+ContName+'</option>');
						$('#cmb_contractorname').chosen();
						$("#txt_contadd").val(FromWorkData.addr_contractor);
						$("#txt_state").val(FromWorkData.state_contractor);
						$("#txt_pg_per").val(FromWorkData.pg_per);
						$("#txt_pg_value").val(FromWorkData.pg_amt);
						$("#txt_pg_valdidate").val(FromWorkData.pg_validity);
						$("#txt_gst_value").val(FromWorkData.gst_perc_rate);
						$("#txt_sd_per").val(FromWorkData.sd_perc);
						$("#txt_sd_value").val(FromWorkData.sd_amt);
						if(FromWorkData.is_less_appl == "Y"){
							$("#lcess_app_y").prop("checked",true);
						}else{
							$("#lcess_app_n").prop("checked",true);
						}
						//});
						$.each(ContBankDetails, function(index, element) {
							BankStr += "<tr>";
							BankStr += "<td align='center'><input type='checkbox' class='tboxsmclass' name='bank_checkbox[] id='bank_checkbox' checked='checked' value="+element.cbdtid+"></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_accno_0' id='txt_bank_accno' onKeyPress='return isNumberKey(event,this)'  value="+element.bank_acc_no+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_name_0' id='txt_bank_name'  value="+element.bank_name+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_branch_0' id='txt_bank_branch'  value="+element.branch_address+" ></td>";
							BankStr +="<td align='left'><input type='text' class='tboxsmclass' name='txt_bank_ifsc_0' id='txt_bank_ifsc'  value="+element.ifsc_code+" ></td></tr>";
						});
						BankStr += "</table>";
						$("#Cont_Bank").html(BankStr);
						$.each(BgFdrDetails, function(index, element) {
							var Bgid	     = element.bfdid; 
							var InstType 	 = element.inst_type;
							var InstNum 	 = element.inst_serial_no;
							var BankName   	 = element.inst_bank_name;
							var DateofIssue  = element.inst_date;
							var DateofExpiry = element.inst_exp_date; 
							var AmtDetail	 = element.inst_amt;  //alert(AmtDetail);
							var RowStr = '<tr><td><input type="hidden" name="txt_Bfdid[]" id="txt_Bfdid" readonly class="tboxsmclass" style="width:100px;" value="'+Bgid+'"><input type="text" name="cmd_instype[]" readonly id="cmd_instype" class="tboxsmclass" style="width:100px;" value="'+InstType+'"></td><td><input type="text" readonly name="instrunum[]" id="instrunum" class="tboxsmclass" readonly style="width:100px;" value="'+InstNum+'"></td><td><input type="text" name="txt_bankname_pg[]" id="txt_bankname_pg" readonly class="tboxsmclass" style="width:100px;" value="'+BankName+'"></td><td><input type="text" readonly name="txt_date_pg[]" id="txt_date_pg" class="tboxsmclass" readonly style="width:100px;" readonly value="'+DateofIssue+'"></td><td><input type="text" name="txt_expir_date_pg[]"  id="txt_expir_date_pg" class="tboxsmclass" style="width:100px;" readonly value="'+DateofExpiry+'"></td><td><input type="text" name="txt_part_amt[]"  id="txt_part_amt" class="tboxsmclass EmAmt" readonly style="text-align:right; width:100px;" value="'+AmtDetail+'"></td><td align="center"><input type="button" class="delete fa btn btn-info" name="emp_delete" id="emp_delete" value="DELETE"></td></tr>'; 
							
							$("#PGTable").append(RowStr);
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
				}else{
					BootstrapDialog.alert("CCNo is not available");
				}
			}
		});
	});
	var KillEvent = 0;
	var OktoSave = 0;
	$("body").on("click","#btn_save", function(event){
		if(KillEvent == 0){
			var TrnoVal 			= $("#cmb_tr_no").val();
			var WorkNameVal 		= $("#txt_workname").val();
			var ShortNameVal 		= $("#txt_shortname").val();
			var TSNumberVal 		= $("#text_techsanctionno").val();
			var HoaNumVal  		= $('#cmb_hoa > option:selected');//$("#cmb_hoa").val();
			var WorkOrderNoVal 	= $("#txt_workorderno").val();
         var WorkOrderDateVal = $("#workorderdate").val();
			var WorkOrderAmtVal 	= $("#txt_workorderamt").val();
			var WorkDurVal 		= $("#workduration").val();
			var WorkCommDateVal 	= $("#workcommencedate").val();
			var DateOfCompVal 	= $("#txt_dateofcompletion").val();
			var AggreNoVal 		= $("#txt_agreementno").val();
			var AggreDateVal 		= $("#txt_aggrementdate").val();
			var EnggIcnoVal 		= $("#txt_ICNO").val();
			var EnggNameVal 		= $("#txt_enggname").val();
			var EnggDesigVal 		= $("#txt_enggdesig").val();
			var EnggGrpVal 		= $("#txt_enggroup").val();
			var ContNameVal 		= $("#txt_cont_name").val();  
			var ContAddrVal 		= $("#txt_contadd").val();  
			var ContStateVal 		= $("#txt_state").val();  
			var PGPerc	   		= $("#txt_pg_per").val();
			var PGValue	   		= $("#txt_pg_value").val();
			var PGValidDate  		= $("#txt_pg_valdidate").val();
         var IsGstApply       = $("[name='gstapplicable']:checked").length;
         var IsLcessApply     = $("[name='lcesapp']:checked").length;
			var GstPercVal 		= $("#txt_gst_value").val();
         var IsGstIncExc      = $("[name='gstincexc']:checked").length;
			var SDPercVal   		= $("#txt_sd_per").val();
			var TotalSDVal 		= $("#txt_sd_value").val();
			var UptoDtSD			= $("#txt_securitydepoe").val();
			var LastDtPmtDt		= $("#txt_paymentdate").val();
			var UptoDtWrkVal		= $("#txt_valuework").val();                  
			var checksavupt		= $("#txt_hid_checkpt").val();
			var GstApplCheck 		= $('input[name="gstapplicable"]:checked').val();	
			/* var CompCodeNoVal 	= $("#txt_computercodeno").val();
			var BKAcHoldNameVal	= $("#txt_acc_hold_name").val();             
			var BankNameVal  		= $("#txt_bank_name").val();                 
			var BankBranchVal 	= $("#txt_bank_branch").val();               
			var BankAccNoVal 		= $("#txt_bank_accno").val();                
			var BankIfscVal 		= $("#txt_bank_ifsc").val();                
			var WorktypeVal 		= $("#worktype").val(); */

			if(checksavupt == 1){
				var msgstr = "Update";
			}else{
				var msgstr = "Save";
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
			}else if(TSNumberVal == ""){
				BootstrapDialog.alert("Technical Sanction Number should not be empty..!!");
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
			}else if(WorkDurVal == ""){
				BootstrapDialog.alert("Duration of Work should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(WorkCommDateVal == ""){
				BootstrapDialog.alert("Date of Commencement should not be empty..!!");
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
			}/*else if(EnggIcnoVal == ""){
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
			}*/else if(ContNameVal == ""){
				BootstrapDialog.alert("Please Select Contractor Name..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if($('input[name="gstapplicable"]:checked').length == 0){
				BootstrapDialog.alert("Please Select GST Applicable or Not Applicable..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if ($('input[name="lcesapp"]:checked').length == 0){
				BootstrapDialog.alert("Please Select LCESS Applicable or Not Applicable..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(SDPercVal == ""){	
				BootstrapDialog.alert(" SD Percentage Should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}else if(TotalSDVal == ""){	
				BootstrapDialog.alert(" Total SD Value should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}/*else if(UptoDtSD == ""){	
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
			}*/else if(GstApplCheck == 'Y'){
				OktoSave = 1;
				switch(true) {
					case (GstPercVal == "") :
					BootstrapDialog.alert("GST Rate on Work Order should not be empty..!!");
					event.preventDefault();
					event.returnValue = false;
					break;
					case ($('input[name="gstincexc"]:checked').length == 0) :
					BootstrapDialog.alert("Please Select GST Incusive/Exclusive..!!");
					event.preventDefault();
					event.returnValue = false;
					break;
					case (OktoSave == 1) :
						event.preventDefault();
						BootstrapDialog.confirm({
							title: 'Confirmation Message',
							message: 'Are you sure want to '+msgstr+' this Work Order ?',
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
			}else{
				event.preventDefault();
				BootstrapDialog.confirm({
					title: 'Confirmation Message',
					message: 'Are you sure want to '+msgstr+' Work Master ?',
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
	/*else if($('input[name="worktype"]:checked').length == 0){
				BootstrapDialog.alert("Please Select Work Type..!!");
				event.preventDefault();
				event.returnValue = false;
			}*/
			/*if(BKAcHoldNameVal == ""){
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
			}*//*else if($('input[name="gstapplicable"]:checked').length > 0){
				var GstApplCheck = 1;
				event.returnValue = true;
			}else if(RebPercVal == ""){
				BootstrapDialog.alert("Rebate ( % ) should not be empty..!!");
				event.preventDefault();
				event.returnValue = false;
			}*/


</script>
<style>
	.inputGroup label::after{
		right: 8px;
		content: '';
	}
</style>
	</form>
  </body>
</html>
