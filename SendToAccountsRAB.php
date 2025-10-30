<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
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
//$check_accounts_sheetid = checkSendAccounts();
if($_POST["submit"] == " Forward to Accounts ") 
{
	$staffid 	= $_SESSION['sid'];
	$sheetid 	= $_POST['cmb_work_no'];
	$rbn 		= $_POST['txt_rbn'];
	$staffid 	= $_SESSION['sid'];
	
	//$minmax_level_str 		= getstaff_minmax_level();
	//$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	//$min_levelid 			= $exp_minmax_level_str[0];
	//$max_levelid 			= $exp_minmax_level_str[1];
	
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  STARTS******************/
	$AlAsCount = 0;
	$select_acal_query = "select * from al_as where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_acal_sql = mysql_query($select_acal_query);
	if($select_acal_sql == true){
		$AlAsCount = mysql_num_rows($select_acal_sql);
	}
	
	if($AlAsCount == 0){ 
		if(($rbn != "")&&($rbn != 0)&&($sheetid != "")&&($sheetid != 0)){  
			$select_wo_cost_query 	= "select work_order_cost from sheet where sheet_id = '$sheetid'";
			$select_wo_cost_sql 	= mysql_query($select_wo_cost_query);
			if($select_wo_cost_sql == true){
				$WoCoList 			= mysql_fetch_object($select_wo_cost_sql);
				$work_order_cost 	= round($WoCoList->work_order_cost);
				
				$FBCount = 0;
				$select_final_bill_query 	= "select is_finalbill from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' and is_finalbill = 'Y'";
				$select_final_bill_sql 		= mysql_query($select_final_bill_query);
				if($select_final_bill_sql == true){
					$FBCount = mysql_num_rows($select_final_bill_sql);
				}
				if($FBCount > 0){
					$is_finalbill 	= "Y";
					$bill_type 		= "F";
				}else{
					$is_finalbill 	= "";
					$bill_type 		= "R";
				}
				
				$select_levl_query 	= "select level from wol_al where wo_val_from <= '$work_order_cost' and wo_val_to >= '$work_order_cost' and bill_type = '$bill_type'";
				$select_levl_sql 	= mysql_query($select_levl_query);
				if($select_levl_sql == true){
					$WoAclist 		= mysql_fetch_object($select_levl_sql);
					$WoAcLevel 		= $WoAclist->level;
					$expWoAcLevel 	= explode(",",$WoAcLevel);
					$WoAcStatus 	= $expWoAcLevel[0];
				}
			}
			//echo $select_levl_query."<br/>";
		
			$insert_alas_query 	= "insert into al_as set sheetid = '$sheetid', rbn = '$rbn', al_level = '$WoAcLevel', is_finalbill = '$is_finalbill', status = '$WoAcStatus', createddate = NOW()";
			//echo $insert_alas_query;exit;
			$insert_alas_sql 	= mysql_query($insert_alas_query);
			//$AlAsid 			= mysql_insert_id();
			//$insert_al_as_query = "insert into al_as_dt set alasid = '$AlAsid', sheetid = '$sheetid', rbn = '$rbn', level = '$WoAcStatus', action = 'FW', staffid = '".$_SESSION['sid']."', section = '".$_SESSION['staff_section']."', createddate = NOW()";
			//$insert_al_as_sql 	= mysql_query($insert_al_as_query);
			$RabStr = ""; $FBillStr = ""; $EscStr = ""; $SecAdvStr = ""; $MonAdvStr = "";
			$SelectQuery = "SELECT * FROM abstractbook WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
			$SelectSql   = mysql_query($SelectQuery);
			if($SelectSql == true){
				if(mysql_num_rows($SelectSql)){
					$List = mysql_fetch_object($SelectSql);
					$RabStr 	= $List->is_rab;
					$FBillStr 	= $List->is_final_bill;
					$SecAdvStr 	= $List->is_sec_adv;
					$MobAdvStr 	= $List->is_mob_adv;
					$EscStr 	= $List->is_esc;
				}
			}
			
			$InsertBillRegQuery = "insert into bill_register set sheetid = '$sheetid', rbn = '$rbn', br_no = (SELECT IFNULL(MAX(a.br_no),0)+1 FROM bill_register a), is_rab = '$RabStr', is_final_bill = '$FBillStr', is_sec_adv = '$SecAdvStr', is_mob_adv = '$MobAdvStr', is_esc = '$EscStr', sent_by = '".$_SESSION['sid']."', sent_on = NOW(), civil_status = 'C', active = 1";
			$InsertBillRegSql 	= mysql_query($InsertBillRegQuery);
			//echo "H".$InsertBillRegQuery;exit;
		}
	}
	else if($AlAsCount > 0){ 
		$AlAsList 		= mysql_fetch_object($select_acal_sql);
		$AlAsid 		= $AlAsList->alasid;
		$AlAsLevel 		= $AlAsList->al_level;
		$expWoAcLevel 	= explode(",",$AlAsLevel);
		$WoAcStatus 	= $expWoAcLevel[0];
		$is_finalbill 	= $AlAsList->is_finalbill;
		$update_alas_query 	= "update al_as set status = '$WoAcStatus', ret_status = '', createddate = NOW() where sheetid = '$sheetid' and rbn = '$rbn'";
		$update_alas_sql 	= mysql_query($update_alas_query);
		//$insert_al_as_query = "insert into al_as_dt set alasid = '$AlAsid', sheetid = '$sheetid', rbn = '$rbn', level = '$WoAcStatus', action = 'FW', staffid = '".$_SESSION['sid']."', section = '".$_SESSION['staff_section']."', createddate = NOW()";
		//$insert_al_as_sql 	= mysql_query($insert_al_as_query); 
		//echo $WoAcStatus; exit;
		
		$RabStr = ""; $FBillStr = ""; $EscStr = ""; $SecAdvStr = ""; $MonAdvStr = "";
		$SelectQuery = "SELECT * FROM abstractbook WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
		$SelectSql   = mysql_query($SelectQuery);
		if($SelectSql == true){
			if(mysql_num_rows($SelectSql)){
				$List = mysql_fetch_object($SelectSql);
				$RabStr 	= $List->is_rab;
				$FBillStr 	= $List->is_final_bill;
				$SecAdvStr 	= $List->is_sec_adv;
				$MobAdvStr 	= $List->is_mob_adv;
				$EscStr 	= $List->is_esc;
			}
		}
		$InsertBillRegQuery = "update bill_register set sheetid = '$sheetid', rbn = '$rbn', is_rab = '$RabStr', is_finalbill = '$FBillStr', is_sec_adv = '$SecAdvStr', is_mob_adv = '$MobAdvStr', is_esc = '$EscStr', sent_by = '".$_SESSION['sid']."', sent_on = NOW(), civil_status = 'C', active = 1 where sheetid = '$sheetid' and rbn = '$rbn'";
		$InsertBillRegSql 	= mysql_query($InsertBillRegQuery);
	}
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  ENDS******************/
	
	
	
	//exit;
	/******* ACCOUNTS LEVEL ASSIGN PARTS BASED ON WORK ORDER COST  STARTS******************/
	$count = 0; $AccountsMbArr = array();
	$select_sent_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
	$select_sent_mb_sql = mysql_query($select_sent_mb_query);
	if($select_sent_mb_sql == true)
	{
		if(mysql_num_rows($select_sent_mb_sql)>0)
		{
			$count = 1;
			while($SAList = mysql_fetch_object($select_sent_mb_sql)){
				array_push($AccountsMbArr,$SAList->mbookno);
			}
		}
	}
	$update = 0; 
	//if($count == 0)
	//{
		$MbNoArr = array();
		$select_mbook_query = "select distinct(mbno), zone_id, genlevel, mtype, staffid from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and active = 1 and genlevel != 'ppayabs'";
		$select_mbook_sql = mysql_query($select_mbook_query); 
		if($select_mbook_sql == true)
		{
			if(mysql_num_rows($select_mbook_sql)>0)
			{
				while($ZoneList = mysql_fetch_object($select_mbook_sql))
				{
					$mbno = $ZoneList->mbno;
					$zone_id = $ZoneList->zone_id;
					$genlevel = $ZoneList->genlevel;
					$mtype = $ZoneList->mtype;
					$generated_staff = $ZoneList->staffid;
					array_push($MbNoArr,$ZoneList->mbno);
					if($genlevel == 'staff')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "RAB";
					}
					if($genlevel == 'composite')
					{
						$mb_ac = "";
						$sa_ac = "SA";
						$ab_ac = "";
						$flag  = "RAB";
					}
					if($genlevel == 'abstract')
					{
						$mb_ac = "";
						$sa_ac = "";
						$ab_ac = "SA";
						$flag  = "RAB";
					}
					if($genlevel == 'cem_consum')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					if($genlevel == 'stl_consum')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					if($genlevel == 'escalation')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					if($genlevel == 'esc_abstract')
					{
						$mb_ac = "SA";
						$sa_ac = "";
						$ab_ac = "";
						$flag  = "ESC";
					}
					$ExistCount = 0;
					$SelectQuery1 = "select sacid from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";
					$SelectSql1 = mysql_query($SelectQuery1);
					if($SelectSql1 == true){
						if(mysql_num_rows($SelectSql1)>0){
							$ExistCount = 1;
						}
					}
					
					if($ExistCount == 0){
						$insert_query = "insert into send_accounts_and_civil set sheetid = '$sheetid', rbn = '$rbn', 
										mbookno = '$mbno', mb_ac = '$mb_ac', sa_ac = '$sa_ac', ab_ac = '$ab_ac', zone_id = '$zone_id', 
										mtype = '$mtype', genlevel = '$genlevel', level='$WoAcStatus', level_status = 'P', send_civil_staff_ids = '".$_SESSION['sid']."',  
										civil_staffid  = '$generated_staff', userid = '$userid', modifieddate = NOW(), flag = '$flag', active = 1";				
						$insert_sql = mysql_query($insert_query);

						$log_linkid = mysql_insert_id();
						$linsert_log_query = "insert into acc_log set linkid = '$log_linkid', sheetid = '$sheetid', rbn = '$rbn', log_date = NOW(), mbookno = '$mbno', 
											zone_id = '$zone_id', mtype = '$mtype', genlevel = '$genlevel', status = 'SA',
											comment = 0, levelid = '$WoAcStatus', sectionid = 2,
											rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END ";
						$linsert_log_sql = mysql_query($linsert_log_query);
					}else{
						$update_sent_mb_query = "update send_accounts_and_civil set mb_ac = '$mb_ac', sa_ac = '$sa_ac', ab_ac = '$ab_ac', level = '$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";
						$update_sent_mb_sql = mysql_query($update_sent_mb_query);
						
						$update_acc_log_query 	= "update acc_log set status = 'SA', AC_status = '', levelid = '$WoAcStatus', rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END where sheetid = '$sheetid' and rbn = '$rbn' and mbookno = '$mbno' and mtype = '$mtype' and genlevel = '$genlevel' and zone_id = '$zone_id'";// and status = 'SC'";// and levelid = 0";
						$update_acc_log_sql 	= mysql_query($update_acc_log_query);
						if($update_sent_mb_sql == true)
						{
							$update++;
						}
					}
				}
			}
		}
		if(($insert_sql == true)||($update > 0))
		{
			$SelectRecQuery = "select * from generate_otherrecovery where sheetid = '$sheetid' and rbn = '$rbn'";
			$SelectRecSql   = mysql_query($SelectRecQuery);
			if($SelectRecSql == true){
				if(mysql_num_rows($SelectRecSql)>0){
					$RecList = mysql_fetch_object($SelectRecSql);
					$MopData = 0;
					$WaterCost = 0;
					$SelectWCQuery = "select * from generate_waterbill where sheetid = '$sheetid' and rbn = '$rbn'";
					$SelectWCSql   = mysql_query($SelectWCQuery);
					if($SelectWCSql == true){
						if(mysql_num_rows($SelectWCSql)>0){
							$WCList = mysql_fetch_object($SelectWCSql);
							$WaterCost = $WCList->water_cost;
						}
					}
					$ElectCost = 0;
					$SelectECQuery = "select * from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
					$SelectECSql   = mysql_query($SelectECQuery);
					if($SelectECSql == true){
						if(mysql_num_rows($SelectECSql)>0){
							$ECList = mysql_fetch_object($SelectECSql);
							$ElectCost = $ECList->electricity_cost;
						}
					}
					
					$SelectMopQuery = "select * from memo_payment_accounts_edit where sheetid = '$sheetid' and rbn = '$rbn'";
					$SelectMopSql   = mysql_query($SelectMopQuery);
					if($SelectMopSql == true){
						if(mysql_num_rows($SelectMopSql)>0){
							$MopData = 1;
						}
					}
					if($MopData == 0){
						$InsertRecQuery = "INSERT INTO memo_payment_accounts_edit SET sheetid = '$sheetid', rbn = '$rbn', abstract_net_amt = '$RecList->abstract_net_amt', 
										sec_adv_amt = '$RecList->sec_adv_amt', esc_amt = '$RecList->esc_amt', pl_mac_adv_amt = '$RecList->pl_mac_adv_amt', mob_adv_percent = '$RecList->mob_adv_percent', mob_adv_amt = '$RecList->mob_adv_amt', 
										mob_adv_amt_rec = '$RecList->mob_adv_amt_rec', mob_adv_int_perc = '$RecList->mob_adv_int_perc', mob_adv_int_amt = '$RecList->mob_adv_int_amt', pl_mac_adv_rec = '$RecList->pl_mac_adv_rec', pl_mac_adv_int_perc = '$RecList->pl_mac_adv_int_perc',  
										pl_mac_adv_int_amt = '$RecList->pl_mac_adv_int_amt', hire_charges = '$RecList->hire_charges', sd_percent = '$RecList->sd_percent', sd_amt = '$RecList->sd_amt', bill_amt_gst = '$RecList->bill_amt_gst',  
										gst_rate = '$RecList->gst_rate', gst_amount = '$RecList->gst_amount', sgst_tds_perc = '$RecList->sgst_tds_perc', sgst_tds_amt = '$RecList->sgst_tds_amt', cgst_tds_perc = '$RecList->cgst_tds_perc',  
										cgst_tds_amt = '$RecList->cgst_tds_amt', igst_tds_perc = '$RecList->igst_tds_perc', igst_tds_amt = '$RecList->igst_tds_amt', wct_percent = '$RecList->wct_percent', wct_amt = '$RecList->wct_amt',  
										vat_percent = '$RecList->vat_percent', vat_amt = '$RecList->vat_amt', lw_cess_percent = '$RecList->lw_cess_percent', lw_cess_amt = '$RecList->lw_cess_amt', is_ldc_appl = '$RecList->is_ldc_appl', 
										pan_type = '$RecList->pan_type', bill_amt_it = '$RecList->bill_amt_it', incometax_percent = '$RecList->incometax_percent', incometax_amt = '$RecList->incometax_amt', it_cess_percent = '$RecList->it_cess_percent', 
										it_cess_amt = '$RecList->it_cess_amt', it_edu_percent = '$RecList->it_edu_percent', it_edu_amt = '$RecList->it_edu_amt', land_rent = '$RecList->land_rent', liquid_damage = '$RecList->liquid_damage', 
										other_recovery_1_desc = '$RecList->other_recovery_1_desc', other_recovery_1_amt = '$RecList->other_recovery_1', other_recovery_2_desc = '$RecList->other_recovery_2_desc', other_recovery_2_amt = '$RecList->other_recovery_2', 
										other_recovery_3_desc = '$RecList->other_recovery_3_desc', other_recovery_3_amt = '$RecList->other_recovery_3', non_dep_machine_equip = '$RecList->non_dep_machine_equip', 
										non_dep_man_power = '$RecList->non_dep_man_power', nonsubmission_qa = '$RecList->nonsubmission_qa', electricity_cost = '$ElectCost', water_cost = '$WaterCost', modifieddate = NOW(), active = 1";
						//$InsertRecSql   = mysql_query($InsertRecQuery);
					}else{
						$InsertRecQuery = "UPDATE memo_payment_accounts_edit SET sheetid = '$sheetid', rbn = '$rbn', abstract_net_amt = '$RecList->abstract_net_amt', 
										sec_adv_amt = '$RecList->sec_adv_amt', esc_amt = '$RecList->esc_amt', pl_mac_adv_amt = '$RecList->pl_mac_adv_amt', mob_adv_percent = '$RecList->mob_adv_percent', mob_adv_amt = '$RecList->mob_adv_amt', 
										mob_adv_amt_rec = '$RecList->mob_adv_amt_rec', mob_adv_int_perc = '$RecList->mob_adv_int_perc', mob_adv_int_amt = '$RecList->mob_adv_int_amt', pl_mac_adv_rec = '$RecList->pl_mac_adv_rec', pl_mac_adv_int_perc = '$RecList->pl_mac_adv_int_perc',  
										pl_mac_adv_int_amt = '$RecList->pl_mac_adv_int_amt', hire_charges = '$RecList->hire_charges', sd_percent = '$RecList->sd_percent', sd_amt = '$RecList->sd_amt', bill_amt_gst = '$RecList->bill_amt_gst',  
										gst_rate = '$RecList->gst_rate', gst_amount = '$RecList->gst_amount', sgst_tds_perc = '$RecList->sgst_tds_perc', sgst_tds_amt = '$RecList->sgst_tds_amt', cgst_tds_perc = '$RecList->cgst_tds_perc',  
										cgst_tds_amt = '$RecList->cgst_tds_amt', igst_tds_perc = '$RecList->igst_tds_perc', igst_tds_amt = '$RecList->igst_tds_amt', wct_percent = '$RecList->wct_percent', wct_amt = '$RecList->wct_amt',  
										vat_percent = '$RecList->vat_percent', vat_amt = '$RecList->vat_amt', lw_cess_percent = '$RecList->lw_cess_percent', lw_cess_amt = '$RecList->lw_cess_amt', is_ldc_appl = '$RecList->is_ldc_appl', 
										pan_type = '$RecList->pan_type', bill_amt_it = '$RecList->bill_amt_it', incometax_percent = '$RecList->incometax_percent', incometax_amt = '$RecList->incometax_amt', it_cess_percent = '$RecList->it_cess_percent', 
										it_cess_amt = '$RecList->it_cess_amt', it_edu_percent = '$RecList->it_edu_percent', it_edu_amt = '$RecList->it_edu_amt', land_rent = '$RecList->land_rent', liquid_damage = '$RecList->liquid_damage', 
										other_recovery_1_desc = '$RecList->other_recovery_1_desc', other_recovery_1_amt = '$RecList->other_recovery_1', other_recovery_2_desc = '$RecList->other_recovery_2_desc', other_recovery_2_amt = '$RecList->other_recovery_2', 
										other_recovery_3_desc = '$RecList->other_recovery_3_desc', other_recovery_3_amt = '$RecList->other_recovery_3', non_dep_machine_equip = '$RecList->non_dep_machine_equip', 
										non_dep_man_power = '$RecList->non_dep_man_power', nonsubmission_qa = '$RecList->nonsubmission_qa', electricity_cost = '$ElectCost', water_cost = '$WaterCost', modifieddate = NOW(), active = 1 WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
						//$InsertRecSql   = mysql_query($InsertRecQuery);
					}
					//echo $InsertRecQuery;exit;
				}
			}
			$msg = "RAB Sucessfully sent to Accounts";
			$success = 1;
			if($update == 0){
				$RABTranActStatusStr = "RAB Sent to Accounts";
			}else{
				$RABTranActStatusStr = "RAB Re-sent to Accounts";
			}
			if(count($MbNoArr)>0){
				$MbNoStr = implode(", ",$MbNoArr);
			}else{
				$MbNoStr = '';
			}
			$InsertLogQuery = "INSERT INTO acc_log_detail SET sheetid = '$sheetid', rbn = '$rbn', mbookno = '$MbNoStr', log_date = NOW(), staffid = '".$_SESSION['sid']."', sent_by = 'EIC'";
			$InsertLogSql   = mysql_query($InsertLogQuery);
			//UpdateWorkTransaction($sheetid,$rbn,"R",$RABTranActStatusStr,"");
		}
		else
		{
			$msg = "Error";
		}
	//}
	/*else if($count == 1)
	{
		$update = 0;
		$update_sent_mb_query = "update send_accounts_and_civil set mb_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and mb_ac != ''";
		$update_sent_mb_sql = mysql_query($update_sent_mb_query);
		
		if($update_sent_mb_sql == true)
		{
			$update++;
		}
		
		$update_sent_sa_query = "update send_accounts_and_civil set sa_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and sa_ac != ''";
		$update_sent_sa_sql = mysql_query($update_sent_sa_query);
		if($update_sent_sa_sql == true)
		{
			$update++;
		}
		
		$update_sent_ab_query = "update send_accounts_and_civil set ab_ac = 'SA', level='$WoAcStatus', level_status = 'P', userid  = '$userid', send_civil_staff_ids = CONCAT(send_civil_staff_ids, ',', '".$_SESSION['sid']."') where sheetid = '$sheetid' and rbn = '$rbn' and ab_ac != ''";
		$update_sent_ab_sql = mysql_query($update_sent_ab_query);
		if($update_sent_ab_sql == true)
		{
			$update++;
		}
		
		$update_acc_log_query 	= "update acc_log set status = 'SA', AC_status = '', levelid = '$WoAcStatus', rec_dt_list = CASE WHEN (rec_dt_list = '') THEN NOW() ELSE CONCAT(rec_dt_list, ',', NOW()) END where sheetid = '$sheetid' and rbn = '$rbn'";// and status = 'SC'";// and levelid = 0";
		$update_acc_log_sql 	= mysql_query($update_acc_log_query);
		
		if($update>0)
		{
			$msg = "RAB Sucessfully sent to Accounts";
			UpdateWorkTransaction($sheetid,$rbn,"R","RAB Re-sent to Accounts","");
			$success = 1;
		}
		else
		{
			$msg = "Error";
		}
	}
	else
	{
		$msg = "Error";
	}*/
	//exit;
}

?>
<?php require_once "Header.html"; ?>
<script>
     
	function find_workname()
	{		
		
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
					//alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value			=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
					/*document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);*/
	
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	/*function check_bill_confirm()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
		document.form.txt_rbn.value	= "";
		document.form.txt_empty_page.value	= "";	
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_bill_confirm.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				//alert(data);
				if((data == "")||(data == 0))
				{
					BootstrapDialog.alert("No RAB available to forward Accounts", "");
				}
				else
				{
					document.form.txt_rbn.value	= name[0];
					document.form.txt_empty_page.value	= name[1];
				}
			}
		}
		xmlHttp.send(strURL);	
	}*/
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.ftable table, .ftable td{
		border:1px solid #C6CCD0;
		border-collapse:collapse;
	}
	.ftable td{
		padding:2px 4px;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Running Account Bill - Forward to Accounts</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       		
							<div class="container">
								<div class="row clearrow"></div>
								<div class="div2" align="center">&nbsp;</div>
								<div class="div8" align="center">
									<div class="innerdiv2">
										<div class="row divhead" align="center">Forward to Accounts - Running Account Bill Details</div>
										<div class="row innerdiv" align="center">
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;Work Short Name</div>
												<div class="div8">
													<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();" class="tboxsmclass">
                                        				<option value=""> --------------- Select --------------- </option>
														<?php echo $objBind->BindWorkOrderNoSendAcc(0); ?>
                                            		</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;Work Order No.</div>
												<div class="div8">
													<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="tboxsmclass" disabled="disabled">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;Name of the Work</div>
												<div class="div8">
													<textarea name="workname" class="tboxsmclass" rows="2" disabled="disabled"></textarea>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4 lboxlabel" align="left">&nbsp;RAB No.</div>
												<div class="div2">
													<input type="text" name="txt_rbn" id="txt_rbn" readonly="" value="" class="tboxsmclass"/>
													<input type="hidden" name="txt_empty_page" id="txt_empty_page" value="" class="textboxdisplay"/>
													<input type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
												</div>
											</div>
											
											<div class="row clearrow"></div>
											<div id="RabCheck"></div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div12" align="center">
												<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
												<input type="submit" class="btn" data-type="submit" value=" Forward to Accounts " name="submit" id="submit"/>
												</div>
											</div>
											<div class="row clearrow"></div>
										</div>
									</div>
								</div>
							</div>
          				</form>
       				</blockquote>
				</div>

			</div>
		</div>
 	<!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		$(function() {
			var EscEF = 0; var MesEF = 0; var SadvEF = 0; var MobadvEF = 0; var Ef = 0;
			var KillEvent = 0;
			$('#submit').on('click', function(event){ 
				if(KillEvent == 0){
					var WorkName = $("#cmb_work_no").val();
					var WorkOrderNo = $("#txt_workorder_no").val();
					var RabNo = $("#txt_rbn").val();
					if(WorkName == ""){
						BootstrapDialog.alert("Please select work short name");
						event.preventDefault();
						event.returnValue = false;
					}else if(WorkOrderNo == ""){
						BootstrapDialog.alert("Work order number should not be empty");
						event.preventDefault();
						event.returnValue = false;
					}else if(RabNo == ""){
						BootstrapDialog.alert("Please enter RAB number");
						event.preventDefault();
						event.returnValue = false;
					}else if(Ef == 0){
						BootstrapDialog.alert("Invalid try. You must generate atleast any one option <br/>(Measurements/Secured Advance/Mobilization Advance/Escalation ");
						event.preventDefault();
						event.returnValue = false;
					}else if(MesEF == 1){
						BootstrapDialog.alert("You have to generate Measurements or Remove Measurement option from RAB create");
						event.preventDefault();
						event.returnValue = false;
					}else if(SadvEF == 1){
						BootstrapDialog.alert("You have to generate Secured Advance or Remove Secured Advance option from RAB create");
						event.preventDefault();
						event.returnValue = false;
					}else if(MobadvEF == 1){
						BootstrapDialog.alert("You have to generate Mobilization Advance or Remove option Mobilization Advance from RAB create");
						event.preventDefault();
						event.returnValue = false;
					}else if(EscEF == 1){
						BootstrapDialog.alert("You have to generate Escalation or Remove Escalation option from RAB create");
						event.preventDefault();
						event.returnValue = false;
					}else{
						event.preventDefault();
						BootstrapDialog.confirm({
							title: 'Confirmation Message',
							message: 'Are you sure want to forward Bill to Accounts ?',
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
			$("#cmb_work_no").bind("change", function(){ 
				var WorkId = $(this).val();
				$("#RabCheck").html('');
				EscEF = 0; MesEF = 0; SadvEF = 0; MobadvEF = 0; Ef = 0;
				$.ajax({
					type: 'POST', 
					url: 'FindBillForwardToAcc.php', 
					data: { WorkId: WorkId }, 
					dataType: 'json',
					success: function (data) { //alert(data);
						if(data != null){
							$("#txt_rbn").val(data['rbn']);
							
							if(data['is_rab'] == "Y"){
								var RabStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
								if(data['MeasGen'] == 1){
									var MesGenStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
									Ef++;
								}else{
									var MesGenStr = '<i class="fa fa-times-circle" style="font-size:24px; color:#EA253C;"></i>';
									MesEF = 1;
								}
							}else{
								var RabStr = '';
								var MesGenStr = '';
							}
							
							if(data['is_sec_adv'] == "Y"){
								var SecAdStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
								if(data['SaGen'] == 1){
									var SecAdGenStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
									Ef++;
								}else{
									var SecAdGenStr = '<i class="fa fa-times-circle" style="font-size:24px; color:#EA253C;"></i>';
									SadvEF = 1;
								}
							}else{
								var SecAdStr = '';
								var SecAdGenStr = '';
							}
							
							if(data['is_mob_adv'] == "Y"){
								var MobAdvStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
								if(data['MobAdvGen'] == 1){
									var MobAdvGenStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
									Ef++;
								}else{
									var MobAdvGenStr = '<i class="fa fa-times-circle" style="font-size:24px; color:#EA253C;"></i>';
									MobadvEF = 1;
								}
							}else{
								var MobAdvStr = '';
								var MobAdvGenStr = '';
							}
							
							if(data['is_esc'] == "Y"){
								var EscStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
								if(data['EscGen'] == 1){
									var EscGenStr = '<i class="fa fa-check-circle-o" style="font-size:24px; color:#046929;"></i>';
									Ef++;
								}else{
									var EscGenStr = '<i class="fa fa-times-circle" style="font-size:24px; color:#EA253C;"></i>';
									EscEF = 1;
								}
							}else{
								var EscStr = '';
								var EscGenStr = '';
							}
							if(Ef > 0){
								var TableStr = "";
								TableStr += '<div class="row divhead" align="center">Running Account Bill Details</div>';
								TableStr += '<div class="row innerdiv" align="center">';
								TableStr += '<div class="row">';
								TableStr += '<table class="div12 ftable">';
								TableStr += '<tr><td class="lboxlabel">&nbsp;</td><td class="lboxlabel">Measurements</td><td class="lboxlabel">Secured Advance</td><td class="lboxlabel">Mobilization Advance</td><td class="lboxlabel">Escalation</td></tr>';
								TableStr += '<tr><td class="lboxlabel">RAB Created For</td><td class="lboxlabel">'+RabStr+'</td><td class="lboxlabel">'+SecAdStr+'</td><td class="lboxlabel">'+MobAdvStr+'</td><td class="lboxlabel">'+EscStr+'</td></tr>';
								TableStr += '<tr><td class="lboxlabel">Ready to Send to Accounts</td><td class="lboxlabel">'+MesGenStr+'</td><td class="lboxlabel">'+SecAdGenStr+'</td><td class="lboxlabel">'+MobAdvGenStr+'</td><td class="lboxlabel">'+EscGenStr+'</td></tr>';
								TableStr += '</table>';
								TableStr += '</div>';
								TableStr += '</div>';
								TableStr += '</div>';
								TableStr += '<div class="row clearrow"></div>';
								$("#RabCheck").html(TableStr);
							}
						}
					}
				});
			});
		});
	
		$("#cmb_work_no").chosen();
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
	</script>

    </body>
</html>

