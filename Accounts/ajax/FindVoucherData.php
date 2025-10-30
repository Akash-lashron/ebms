<?php
require_once '../library/config.php';
$RbnArr = array();
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$SheetArr = array(); $SheetId = ""; $Rbn = ""; $WorkArr = array(); $HoaArr = array(); $HoaIdArr = array(); $HoaScodeIdArr = array(); $HoaScodeArr = array(); $Hoa = "";
$WhereClause = "";
if($_POST['PageCode'] == "ACC"){
	$Ccno = $_POST['Ccno'];
	$WhereClause = "computer_code_no = '$Ccno'";
}
if($_POST['PageCode'] == "EIC"){
	$SheetId = $_POST['SheetId'];
	$WhereClause = "sheet_id = '$SheetId'";
}
$PayType = $_POST['PayType'];
$OutputArr = array(); $BillStatusArr = array(); 
$BillCompStatus = ""; $BillRetStatus = ''; $BillCurrLevel = ""; $BillVouchStatus = ""; $BillPassOrdStatus = ""; $BillPayOrdStatus = "";
if((($Ccno != "")&&($Ccno != NULL))||(($SheetId != NULL)&&($SheetId != ""))){
	$WorkSdAmount = 0;
	$SelectQuery = "select * from sheet where under_civil_sheetid = 0 AND ".$WhereClause;
	$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		$List = mysqli_fetch_assoc($SelectSql);
		$SheetId = $List['sheet_id'];
		$GlobId = $List['globid']; 
		$ContId1 = $List['contid'];
		$ContBId = $List['cbdtid'];
		$HoaId1  = $List['hoaid'];
		$WorkArr['sheet_id'] 		= $List['sheet_id'];
		$WorkArr['globid'] 			= $List['globid'];
		$WorkArr['cbdtid'] 			= $List['cbdtid'];
		$WorkArr['work_order_no'] 	= $List['work_order_no'];
		$WorkArr['work_name'] 		= $List['work_name'];
		$WorkArr['tech_sanction'] 	= $List['tech_sanction'];
		$WorkArr['agree_no'] 		= $List['agree_no'];
		$WorkArr['date_of_completion'] = $List['date_of_completion'];
		$WorkArr['gst_inc_exc'] 	= $List['gst_inc_exc'];
		$WorkArr['gst_perc_rate'] 	= $List['gst_perc_rate'];
		$WorkArr['is_less_appl'] 	= $List['is_less_appl'];
		$WorkArr['is_gst_appl'] 	= $List['is_gst_appl'];
		$WorkArr['sd_perc'] 		= $List['sd_perc'];
		$WorkOrderCost 				= $List['work_order_cost'];
		$WorkSdAmount 				= round(($WorkOrderCost * $WorkArr['sd_perc'] / 100),2);
		$WorkArr['work_sd_amt'] 	= $WorkSdAmount;
		
		$UnderCivilIdArr = array();
		//array_push($UnderCivilIdArr,$SheetId);
		$SelectQueryA = "select * from sheet where under_civil_sheetid = '$SheetId'";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA);
		if($SelectSqlA == true){
			if(mysqli_num_rows($SelectSqlA)>0){
				while($ListA = mysqli_fetch_object($SelectSqlA)){
					array_push($UnderCivilIdArr,$ListA->sheet_id);
				}
			}
		}
		if(count($UnderCivilIdArr)>0){
			$UnderCivilIdStr = implode(",",$UnderCivilIdArr);
		}else{
			$UnderCivilIdStr = "";
		}
	}
	$IsMesExist = 0; $BillMode =  "ON";
	$SelectBRQuery = "SELECT * FROM bill_register WHERE sheetid = '$SheetId' AND acc_status = 'P' AND reg_status = 'R' ORDER BY rbn DESC LIMIT 1";
	$SelectBRSql   = mysqli_query($dbConn,$SelectBRQuery);
	if($SelectBRSql == true){
		if(mysqli_num_rows($SelectBRSql)>0){
			$BRRbnList = mysqli_fetch_object($SelectBRSql);
			$BRRbn = $BRRbnList->rbn;
			$Rbn = $BRRbn;
		}
	}
	//ech
	if($_POST['PageCode'] == "ACC"){
		$SelectCheckQuery = "SELECT mbheaderid FROM mbookheader WHERE sheetid = '$SheetId' ORDER BY mbheaderid ASC LIMIT 1";
		$SelectCheckSql   = mysqli_query($dbConn,$SelectCheckQuery);
		if($SelectCheckSql == true){
			if(mysqli_num_rows($SelectCheckSql)>0){
				$IsMesExist = 1;
			}
		}
		if($IsMesExist == 0){
			
			$BillMode =  "OFF";
		}
	}
	
	//echo $Rbn;exit;
	//echo $UnderCivilIdStr;exit;
	//if(count($WorkArr) == 0){
		/*$SelectQuery2 = "select * from works where ccno = '$Ccno'";
		$SelectSql2   = mysqli_query($dbConn,$SelectQuery2);
		if($SelectSql2 == true){
			$List2 = mysqli_fetch_assoc($SelectSql2);
			if(count($WorkArr) == 0){
				$ContId2 = $List2['contid'];
				$HoaId2  = $List2['hoaid'];
				$WorkArr['sheetid'] 		= $List2['sheetid'];
				$WorkArr['globid'] 			= $List2['globid'];
				$WorkArr['cbdtid'] 			= '';
				$WorkArr['work_order_no'] 	= $List2['wo_no'];
				$WorkArr['work_name'] 		= $List2['work_name'];
				$WorkArr['tech_sanction'] 	= $List2['ts_no'];
				$WorkArr['agree_no'] 		= $List2['agmt_no'];
				$WorkArr['date_of_completion'] = '';
				$WorkArr['gst_inc_exc'] 	= '';
				$WorkArr['gst_perc_rate'] 	= '';
				$WorkArr['is_less_appl'] 	= '';
				$WorkArr['is_gst_appl'] 	= $List2['is_gst_appl'];
			}
			$Hoa = $List2['hoa_no'];
		}*/
	//}
	$ToDayDt = date("Y-m-d");
	$IsSDBGExist = 'N'; $TotSDBGAmt = 0; $SDBGValid = 1; $SDValidDateDp = NULL;
	$SelectQuery2B = "select * from bg_fdr_details where globid = '$GlobId' and inst_purpose = 'SD'";
	$SelectSql2B   = mysqli_query($dbConn,$SelectQuery2B);
	if($SelectSql2B == true){
		if(mysqli_num_rows($SelectSql2B)>0){
			while($List2B = mysqli_fetch_object($SelectSql2B)){
				$IsSDBGExist = 'Y';
				$TotSDBGAmt = $TotSDBGAmt + $List2B->inst_amt;
				if(($List2B->inst_ext_date != '0000-00-00')&&($List2B->inst_ext_date != NULL)){
					$SDValidDate = $List2B->inst_ext_date;
				}else if(($List2B->inst_exp_date != '0000-00-00')&&($List2B->inst_exp_date != NULL)){
					$SDValidDate = $List2B->inst_exp_date;
				}else{
					$SDValidDate = "";
				}
				if($SDValidDate != ""){
					if($SDValidDate <= $ToDayDt){
						$SDBGValid = 0;
					}
					$SDValidDateDp = dt_display($SDValidDate);
				}
			}
		}
	}
	//echo $SelectQuery2B;exit;
	$WorkArr['work_sd_bg_exist'] 	= $IsSDBGExist;
	$WorkArr['work_sd_bg_amt'] 	 	= $TotSDBGAmt;
	$WorkArr['work_sd_bg_valid'] 	= $SDBGValid;
	$WorkArr['work_sd_bg_valid_dt'] = $SDValidDateDp;
	
	$SelectQuery2A = "select * from gst_rate_master";
	$SelectSql2A   = mysqli_query($dbConn,$SelectQuery2A);
	if($SelectSql2A == true){
		if(mysqli_num_rows($SelectSql2A)>0){
			while($List2A = mysqli_fetch_object($SelectSql2A)){
				$WorkArr[$List2A->gst_desc] = $List2A->gst_rate;
			}
		}
	}
	$SDRecPerc = 0;
	$SelectQuery2C = "select * from default_values";
	$SelectSql2C   = mysqli_query($dbConn,$SelectQuery2C);
	if($SelectSql2C == true){
		if(mysqli_num_rows($SelectSql2C)>0){
			$List2C = mysqli_fetch_object($SelectSql2C);
			$WorkArr['sd_rec_perc'] = $List2C->sd_rec;
			$SDRecPerc = $List2C->sd_rec;
		}
	}
	
	if(($HoaId1 != '')&&($HoaId1 != NULL)){
		$HoaId = $HoaId1;
	}else if(($HoaId2 != '')&&($HoaId2 != NULL)){
		$HoaId = $HoaId2;
	}else{
		$HoaId = '';
	}
	if(($HoaId != '')&&($HoaId != NULL)){
		$SelectQuery1 = "select * from hoa_master where hoamast_id IN ($HoaId)";
		$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				while($List1 = mysqli_fetch_object($SelectSql1)){
					$HoaNo = $List1->new_hoa_no;
					array_push($HoaIdArr,$List1->hoamast_id);
					array_push($HoaScodeIdArr,$List1->shortcode_id);
					array_push($HoaArr,$HoaNo);
					
					$SelectQuery1A = "select * from shortcode_master where shortcode_id IN ($List1->shortcode_id)";
					//echo $SelectQuery1A;exit;
					$SelectSql1A = mysqli_query($dbConn,$SelectQuery1A);
					if($SelectSql1A == true){ 
						if(mysqli_num_rows($SelectSql1A)>0){ 
							while($List1A = mysqli_fetch_object($SelectSql1A)){
								$SCode = $List1A->shortcode;
								array_push($HoaScodeArr,$SCode);
							}
						}
					}
					
				}
			}
		}
	}/*else{
		$SelectQuery2 = "select * from works where globid = '$GlobId'";
		$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
		if($SelectSql2 == true){
			if(mysqli_num_rows($SelectSql2)>0){
				$List2 = mysqli_fetch_object($SelectSql2);
				$HoaNo = $List2->hoa;
				if(($HoaNo != '')&&($HoaNo != NULL)){
					array_push($HoaIdArr,$List2->hoaid);
					array_push($HoaArr,$HoaNo);
				}else{
					$SelectQuery3 = "select * from hoa_master where hoamast_id IN ($List2->hoaid)";
					$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
					if($SelectSql3 == true){
						if(mysqli_num_rows($SelectSql3)>0){
							while($List3 = mysqli_fetch_object($SelectSql3)){
								$HoaNo = $List3->new_hoa_no;
								array_push($HoaIdArr,$List3->hoamast_id);
								array_push($HoaArr,$HoaNo);
							}
						}
					}
				}
			}
		}
	}*/
	//print_r($HoaScodeArr);exit;
	/*if($Hoa != ''){
		$WorkArr['hoa_no'] = $Hoa;
		$WorkArr['hoa'] = $Hoa;
	}else{*/
	if(($HoaId != '')&&($HoaId != NULL)){
		$HoaStr 				= implode(",",$HoaArr);
		$WorkArr['hoa_no'] 		= $HoaStr;
		$WorkArr['hoa'] 		= $HoaStr;
		$HoaIdStr 				= implode(",",$HoaIdArr);
		$WorkArr['hoaid'] 		= $HoaIdStr;
		$HoaScodeIdStr 			= implode(",",$HoaScodeIdArr);
		$WorkArr['hoascodeid'] 	= $HoaScodeIdStr;
		$HoaScodeStr 			= implode(",",$HoaScodeArr);
		$WorkArr['hoascode'] 	= $HoaScodeStr;
	}else{
		$WorkArr['hoa_no'] 		= '';
		$WorkArr['hoa'] 		= '';
		$WorkArr['hoaid'] 		= '';
		$WorkArr['hoascodeid'] 	= '';
		$WorkArr['hoascode'] 	= '';
	}
	
	
	$RabArr = array(); $UptoDtSDAmt = 0; $SDBalanceAmt = 0; $SDLastFullRec = 0; $CurrBillSDRec = 0; $CurrBillActSDRec = 0;
	//$SelectQuery3 = "select * from abstractbook where sheetid = '$SheetId' and rab_status = 'P'";
	if($_POST['PageCode'] == "EIC"){
		$SelectQuery3 = "select * from abstractbook where sheetid = '$SheetId' and (rab_status = 'P' OR payment_dt = '0000-00-00' OR payment_dt IS NULL) ORDER BY rbn DESC LIMIT 1";
	}else{
		$SelectQuery3 = "select * from abstractbook where sheetid = '$SheetId' and rbn = '$Rbn' and (rab_status = 'P' OR payment_dt = '0000-00-00' OR payment_dt IS NULL) ORDER BY rbn DESC LIMIT 1";
	}
	$SelectSql3 	 = mysqli_query($dbConn,$SelectQuery3); 
	if($SelectSql3 == true){
		$List3 = mysqli_fetch_assoc($SelectSql3);
		
		
		//if(($_POST['PageCode'] == "ACC")&&($IsMesExist == 0)){
			if(($List3['rbn'] != "")&&($List3['rbn'] != NULL)&&($List3['rbn'] != 0)){
				//$Rbn = $BRRbn;
				$Rbn = $List3['rbn'];
				$List3['rbn'] = $Rbn;
			}else{
			    $List3['rbn'] = $Rbn;
			}
		//}
		//echo $UnderCivilIdStr;exit;
		$SelectQuery3A = "select * from abstractbook where sheetid IN($UnderCivilIdStr) and rbn = '$Rbn'";
		$SelectSql3A   = mysqli_query($dbConn,$SelectQuery3A); //echo $SelectQuery3A;exit;
		if($SelectSql3A == true){
			while($List3A = mysqli_fetch_assoc($SelectSql3A)){
				//echo $List3A['slm_total_amount']."<br/>";
				$List3['slm_total_amount'] = $List3['slm_total_amount'] + $List3A['slm_total_amount'];
				$List3['dpm_total_amount'] = $List3['dpm_total_amount'] + $List3A['dpm_total_amount'];
				$List3['upto_date_total_amount'] = $List3['upto_date_total_amount'] + $List3A['upto_date_total_amount'];
				
				//$List3['secured_adv_amt'] = $List3['secured_adv_amt'] + $List3A['secured_adv_amt'];
				//$List3['secured_adv_amt'] = $List3['secured_adv_amt'] + $List3A['secured_adv_amt'];
				$List3['slm_total_amount_esc'] = $List3['slm_total_amount_esc'] + $List3A['slm_total_amount_esc'];
				$List3['mob_adv_amt'] = $List3['mob_adv_amt'] + $List3A['mob_adv_amt'];
			}
		}
		$List3['secured_adv_amt'] = 0;
		if($UnderCivilIdStr != ''){
			$SelectQuery3B = "select * from secured_advance where (sheetid = '$SheetId' OR sheetid IN($UnderCivilIdStr)) and rbn = '$Rbn'";
		}else{
			$SelectQuery3B = "select * from secured_advance where sheetid = '$SheetId' and rbn = '$Rbn'";
		}
		$SelectSql3B   = mysqli_query($dbConn,$SelectQuery3B); //echo $SelectQuery3A;exit;
		if($SelectSql3B == true){
			while($List3B = mysqli_fetch_assoc($SelectSql3B)){
				$List3['secured_adv_amt'] = $List3['secured_adv_amt'] + $List3B['upto_dt_ots_amt'];
				$List3['dpm_total_amount'] = $List3['dpm_total_amount'] + $List3B['ded_prev_ots_amt'];
			}
		}
		
		//echo $SelectQuery3B;exit;
		
		$List3['slm_total_amount'] 		 = round($List3['slm_total_amount']);
		$List3['dpm_total_amount'] 		 = round($List3['dpm_total_amount']);
		$List3['upto_date_total_amount'] = round($List3['upto_date_total_amount']);
		$List3['secured_adv_amt'] 		 = round($List3['secured_adv_amt']);
		$List3['slm_total_amount_esc']   = round($List3['slm_total_amount_esc']);
		$List3['mob_adv_amt'] 			 = round($List3['mob_adv_amt']);
		$List3['pl_mac_adv_amt'] 		 = round($List3['pl_mac_adv_amt']);
		//$ThisBillValue = round(($List3['slm_total_amount'] + $List3['secured_adv_amt'] + $List3['slm_total_amount_esc'] + $List3['mob_adv_amt']));
		$ThisBillValue = round(($List3['upto_date_total_amount'] - $List3['dpm_total_amount'] + $List3['secured_adv_amt'] + $List3['slm_total_amount_esc'] + $List3['mob_adv_amt'] + $List3['pl_mac_adv_amt']));
		//echo $List3['slm_total_amount'];
		//exit;
		$List3['this_bill_val'] = $ThisBillValue;
		if((isset($WorkArr['gst_inc_exc']))&&($WorkArr['gst_inc_exc'] == "E")){
			$AmtForGstCalc 	= $ThisBillValue;
			$GstAmount 		= round(($AmtForGstCalc*$GstPercRate/100));
		}else{
			$AmtForGstCalc 	= round(($ThisBillValue*100/($WorkArr['gst_perc_rate']+100)));
			$GstAmount 		= round((($AmtForGstCalc*$WorkArr['gst_perc_rate'])/100));
		}
		$List3['bill_amt_for_gst'] = $AmtForGstCalc;
		$List3['gst_amt'] = $GstAmount;
		$List3['bill_amt_it'] = $ThisBillValue;
		
		$UptoDtSDAmt = $List3['upto_dt_sd_rec_amt'];
		if($WorkSdAmount != 0){
			$SDBalanceAmt = $WorkSdAmount - $UptoDtSDAmt;
			$CurrBillActSDRec = $ThisBillValue * $SDRecPerc / 100; 
			if($SDBalanceAmt < $CurrBillActSDRec){
				$CurrBillSDRec = round($SDBalanceAmt);
				$SDLastFullRec = 1;
			}else{
				$CurrBillSDRec = round($CurrBillActSDRec);
			}
		}
		
		$List3['balance_sd_rec_amt'] = $SDBalanceAmt;
		$List3['curr_bill_sd_rec_amt'] = $CurrBillSDRec;
		$List3['curr_bill_act_sd_rec_amt'] = $CurrBillActSDRec;
		$List3['balance_sd_full_rec'] = $SDLastFullRec;
		$RabArr = $List3;
		$BillCompStatus = $List3['rab_status'];
	}
	
	
	
	
	
	$MopHoaRecArr  = array();
	$SelectQuery4A = "select a.*, b.shortcode_id, b.rec_code as hoa_rec_code, b.shortcode, b.shortcode_desc, b.fin_year from mop_rec_dt a inner join shortcode_master b on (a.shortcode_id = b.shortcode_id) where a.sheetid = '$SheetId' and a.rbn = '$Rbn'";
	$SelectSql4A   = mysqli_query($dbConn,$SelectQuery4A);
	if($SelectSql4A == true){
		while($List4A = mysqli_fetch_array($SelectSql4A)){
			$RecCode = $List4A['rec_code'];
			$MopHoaRecArr[$RecCode] = $List4A;
		}
	}
	//echo $SelectQuery4A;exit;
	$RecRow   = 0;
	$RecovArr = array();
	$SelectQuery4 = "select * from memo_payment_accounts_edit where sheetid = '$SheetId' and rbn = '$Rbn'";
	$SelectSql4   = mysqli_query($dbConn,$SelectQuery4);
	if($SelectSql4 == true){
		if(mysqli_num_rows($SelectSql4)>0){
			$List4 = mysqli_fetch_assoc($SelectSql4);
			$RecovArr = $List4;
			$RecRow   = 1;
			if(($List4['pass_order_dt'] != NULL)&&($List4['pass_order_dt'] != '0000-00-00')){
				$BillPassOrdStatus   = "Y";
			}
			if(($List4['pay_order_dt'] != NULL)&&($List4['pay_order_dt'] != '0000-00-00')){
				$BillPayOrdStatus 	= "Y";
			}
			if(($List4['payment_dt'] != NULL)&&($List4['payment_dt'] != '0000-00-00')){
				$BillVouchStatus    = "Y";
			}
			if($List4['edit_flag'] == "EDIT"){ //exit;
				$List3['bill_amt_for_gst'] =$List4['bill_amt_gst'];
				$List3['gst_amt'] = $List4['gst_amount'];
				$List3['bill_amt_it'] = $List4['bill_amt_it'];
				
				$List3['slm_total_amount'] 		= round($List4['abstract_net_amt']);
				$List3['dpm_total_amount'] 		= round($List4['cmb_ded_prev_amt']);
				$List3['upto_date_total_amount'] = round($List4['cmb_uptodt_amt']);
				$List3['secured_adv_amt'] 		 = round($List4['sec_adv_amt']);
				$List3['slm_total_amount_esc']   = round($List4['esc_amt']);
				$List3['mob_adv_amt'] 			 = round($List4['mob_adv_amt']);
				$List3['pl_mac_adv_amt'] 		 = round($List4['pl_mac_adv_amt']);
				$ThisBillValue = round(($List3['upto_date_total_amount'] - $List3['dpm_total_amount'] + $List3['secured_adv_amt'] + $List3['slm_total_amount_esc'] + $List3['mob_adv_amt'] + $List3['pl_mac_adv_amt']));
				$List3['this_bill_val'] = $ThisBillValue;
				$RabArr = $List3;
				
			}
		}
	}
	//echo $SelectQuery4;exit;
	if($RecRow == 0){
		$SelectQuery4 = "select * from generate_otherrecovery where sheetid = '$SheetId' and rbn = '$Rbn'";
		$SelectSql4   = mysqli_query($dbConn,$SelectQuery4);
		if($SelectSql4 == true){
			if(mysqli_num_rows($SelectSql4)>0){
				$List4 = mysqli_fetch_assoc($SelectSql4);
				$RecovArr = $List4;
			}
		}
		$SelectQuery4A = "select SUM(water_cost) as water_amt from generate_waterbill where sheetid = '$SheetId' and rbn = '$Rbn'";
		$SelectSql4A   = mysqli_query($dbConn,$SelectQuery4A);
		if($SelectSql4A == true){
			if(mysqli_num_rows($SelectSql4A)>0){
				$List4A = mysqli_fetch_object($SelectSql4A);
				$WaterAmt = $List4A->water_amt;
				$RecovArr['water_cost'] = round($WaterAmt);
			}
		}
		$SelectQuery4B = "select SUM(electricity_cost) as elec_amt from generate_electricitybill where sheetid = '$SheetId' and rbn = '$Rbn'";
		$SelectSql4B   = mysqli_query($dbConn,$SelectQuery4B);
		if($SelectSql4B == true){
			if(mysqli_num_rows($SelectSql4B)>0){
				$List4B = mysqli_fetch_object($SelectSql4B);
				$ElecAmt = $List4B->elec_amt;
				$RecovArr['electricity_cost'] = round($ElecAmt);
			}
		}
		
	}
	//echo $SelectQuery4;exit;
	
	if(($ContId1 != NULL)&&($ContId1 != 0)){
		$ContId = $ContId1;
	}else if(($ContId2 != NULL)&&($ContId2 != 0)){
		$ContId = $ContId2;
	}else{
		$ContId = "";
	}
	$WorkArr['contid'] = $ContId;
	
	$ContArr = array();
	$LdcFromDate = ""; $LdcToDate = ""; $LdcMaxAmt = 0; $LdcValidity = 0; $IsBalanceValid = 0; $BalanceAmt = 0;
	$SelectQuery5 = "select * from contractor where contid = '$ContId'";
	$SelectSql5   = mysqli_query($dbConn,$SelectQuery5);
	if($SelectSql5 == true){
		$List5 = mysqli_fetch_assoc($SelectSql5);
		$SelectQuery5A = "select * from it_rate_master where pan_type = '".$List5['pan_type']."'";
		$SelectSql5A   = mysqli_query($dbConn,$SelectQuery5A);
		if($SelectSql5A == true){
			$List5A = mysqli_fetch_object($SelectSql5A);
			$List5['it_rate'] = $List5A->it_rate;
		}
		$LdcFromDate = $List5['ldc_validty_from'];
		$LdcToDate   = $List5['ldc_validity'];
		$LdcMaxAmt 	 = $List5['ldc_max_amt'];
		if(($LdcToDate != '')&&($LdcToDate != '0000-00-00')){
			$ToDayDateForLdc = date("Y-m-d");
			if($LdcToDate >= $ToDayDateForLdc){
				$UsedAmtForLdc = 0;
				//$SelectQuery4 = "select SUM(slm_total_amount+secured_adv_amt) from abstractbook where sheetid = '$SheetId' and pass_order_date >= '$LdcFromDate' AND pass_order_date <= '$LdcToDate'";
				$SelectQuery7 = "select SUM(slm_total_amount) as tot_amt from abstractbook where sheetid = '$SheetId' and pass_order_date >= '$LdcFromDate' AND pass_order_date <= '$LdcToDate'";
				$SelectSql7   = mysqli_query($dbConn,$SelectQuery7);
				if($SelectSql7 == true){
					$List7 = mysqli_fetch_object($SelectSql7);
					$UsedAmtForLdc = $List7->tot_amt;
				}
				$BalanceAmt = $LdcMaxAmt - $UsedAmtForLdc;
				if($ThisBillValue <= $BalanceAmt){
					$IsBalanceValid = 1;
				}
			}
		}
		$List5['ldc_bal_amt'] = $BalanceAmt;
		$List5['ldc_bal_valid'] = $IsBalanceValid;
		$ContArr = $List5;
	}
	//echo $IsBalanceAvail." = ".$LdcMaxAmt." - ".$UsedAmtForLdc;
	//print_r($ContArr);
	//exit;
	$BankDtArr = array();
	if(($ContBId != "")&&($ContBId != NULL)&&($ContBId != 0)){
		$SelectQuery6 = "select * from contractor_bank_detail where contid = '$ContId' and cbdtid = '$ContBId' and (bk_dt_conf_by = 'AAO' OR bk_dt_conf_status = 'AAO')";
	}else{
		$SelectQuery6 = "select * from contractor_bank_detail where contid = '$ContId' and (bk_dt_conf_by = 'AAO' OR bk_dt_conf_status = 'AAO')";
	}
	$SelectSql6   = mysqli_query($dbConn,$SelectQuery6);
	if($SelectSql6 == true){
		while($List6 = mysqli_fetch_assoc($SelectSql6)){
			if($ContBId == $List6['cbdtid']){
				$List6['active_status'] = 1;
			}else{
				$List6['active_status'] = 0;
			}
			$BankDtArr[] = $List6;
		}
	}
	
	$BillLevelFlag = "";
	$SelectQuery6A = "select * from al_as where sheetid = '$SheetId' AND rbn = '$Rbn'";
	$SelectSql6A   = mysqli_query($dbConn,$SelectQuery6A);
	if($SelectSql6A == true){
		$List6A = mysqli_fetch_object($SelectSql6A);
		$BillCurrLevel = $List6A->status;
		$BillRetStatus = $List6A->ret_status;
		if($BillCurrLevel != 'C'){
			if($BillCurrLevel > $_SESSION['levelid']){
				$BillLevelFlag = "H";
			}else if($BillCurrLevel < $_SESSION['levelid']){
				$BillLevelFlag = "L";
			}else if($BillCurrLevel == $_SESSION['levelid']){
				$BillLevelFlag = $_SESSION['levelid'];
			}
		}
	}
	$BillRegStatus = ""; $BillRegNo = "";
	$SelectQuery6B = "select * from bill_register where sheetid = '$SheetId' AND rbn = '$Rbn'";
	$SelectSql6B   = mysqli_query($dbConn,$SelectQuery6B);
	if($SelectSql6B == true){
		if(mysqli_num_rows($SelectSql6B)>0){
			$List6B = mysqli_fetch_object($SelectSql6B);
			$BillRegStatus 	= $List6B->acc_status;
			$BillRegNo 		= $List6B->br_no;
		}
	}
	//echo $SelectQuery6B;exit;
	if($BillRegStatus == ""){
		$BillStatusArr['bill_reg_status'] 	= "N";
	}else if($BillRegStatus == "C"){
		$BillStatusArr['bill_reg_status'] 	= "Y";
	}else if($BillRegStatus == "P"){
		$BillStatusArr['bill_reg_status'] 	= "Y";
	}else{
		$BillStatusArr['bill_reg_status'] 	= "";
	}
	$BillStatusArr['bill_curr_level'] 		= $BillCurrLevel;
	$BillStatusArr['bill_ret_status'] 		= $BillRetStatus;
	$BillStatusArr['bill_comp_status'] 		= $BillCompStatus;
	$BillStatusArr['bill_vouch_status'] 	= $BillVouchStatus;
	$BillStatusArr['bill_payord_status'] 	= $BillPayOrdStatus;
	$BillStatusArr['bill_pasord_status'] 	= $BillPassOrdStatus;
	$BillStatusArr['curr_session_level'] 	= $_SESSION['levelid'];
	$BillStatusArr['bill_level_flag'] 		= $BillLevelFlag;
	$BillStatusArr['bill_mode'] 			= $BillMode;
	$WorkArr['br_no'] = $BillRegNo;
	$OutputArr['WData'] = $WorkArr;
	$OutputArr['RABData'] = $RabArr;
	$OutputArr['RECData'] = $RecovArr;
	$OutputArr['CONTData'] = $ContArr;
	$OutputArr['BKData'] = $BankDtArr;
	$OutputArr['RecHoaData'] = $MopHoaRecArr;
	$OutputArr['StatusData'] = $BillStatusArr;
}
echo json_encode($OutputArr);
	
?>
