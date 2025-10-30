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
$SheetId = $_POST['WorkId'];
$PageType = $_POST['PageType'];
$PayFlag = $_POST['PayFlag'];;
$BillCompStatus = ""; $BillRetStatus = ''; $BillCurrLevel = ""; $BillVouchStatus = ""; $BillPassOrdStatus = ""; $BillPayOrdStatus = "";
$SelectQuery = "select * from sheet where sheet_id = '".$_POST['WorkId']."'";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	while($RbnList = mysqli_fetch_assoc($SelectSql)){
		$HoaArr = array();
		$GlobId = $RbnList['globid'];
		$HoaId = $RbnList['hoaid'];
		$RbnList['hoa_no'] = $RbnList['hoa'];
		if(($RbnList['hoaid'] != '')&&($RbnList['hoaid'] != NULL)){
			$SelectQuery1 = "select * from hoa_master where hoamast_id IN ($HoaId)";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$HoaNo = $List1->new_hoa_no;
						array_push($HoaArr,$HoaNo);
					}
				}
			}
		}else{
			$SelectQuery2 = "select * from works where globid = '$GlobId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$HoaNo = $List2->hoa;
					if(($HoaNo != '')&&($HoaNo != NULL)){
						array_push($HoaArr,$HoaNo);
					}else{
						$SelectQuery3 = "select * from hoa_master where hoamast_id IN ($List2->hoaid)";
						$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								while($List3 = mysqli_fetch_object($SelectSql3)){
									$HoaNo = $List3->new_hoa_no;
									array_push($HoaArr,$HoaNo);
								}
							}
						}
					}
				}
			}
		}
		$LastRbn= ''; $IsAdvPayFlag = "";  $IsAdvPayFlag = "";
		//$SelectQuery4 = "select * from abstractbook where sheetid = '$SheetId' and rbn = (select max(rbn) from abstractbook where sheetid = '$SheetId')";
		$SelectQuery4 = "select * from abstractbook where sheetid = '$SheetId' and (rab_status = 'P' OR payment_dt = '0000-00-00' OR payment_dt IS NULL) ORDER BY rbn DESC LIMIT 1";
		$SelectSql4 = mysqli_query($dbConn,$SelectQuery4);
		if($SelectSql4 == true){
			if(mysqli_num_rows($SelectSql4)>0){
				$List4 = mysqli_fetch_object($SelectSql4);
				
				$LastRbn = $List4->rbn;
				$RbnList['rbn'] = $List4->rbn;
				
				$RbnList['fromdate'] = $List4->fromdate;
				$RbnList['todate'] = $List4->todate	;
				$RbnList['mbookno'] = $List4->mbookno;
				$RbnList['mbookpage'] = $List4->mbookpage;
				$RbnList['upto_date_total_amount'] = $List4->upto_date_total_amount;
				$RbnList['dpm_total_amount'] = $List4->dpm_total_amount;
				$RbnList['slm_total_amount'] = $List4->slm_total_amount;
				$RbnList['total_rec_amt'] = $List4->total_rec_amt;
				$RbnList['secured_adv_amt'] = $List4->secured_adv_amt;
				$RbnList['total_rec_rel_amt'] = $List4->total_rec_rel_amt;
				$RbnList['paid_amount'] = $List4->paid_amount;
				$RbnList['upto_date_total_amount_esc'] = $List4->upto_date_total_amount_esc;
				$RbnList['dpm_total_amount_esc'] = $List4->dpm_total_amount_esc;
				$RbnList['slm_total_amount_esc'] = $List4->slm_total_amount_esc;
				$RbnList['mbookno_esc'] = $List4->mbookno_esc;
				$RbnList['mbookpage_esc'] = $List4->mbookpage_esc;
				/*$RbnList['pass_order_date'] = dt_display($List4->pass_order_date);
				if(($List4->pass_order_date != NULL)&&($List4->pass_order_date != "0000-00-00")){
					$RbnList['pass_order_date_dp'] = dt_display($List4->pass_order_date);
				}else{
					$RbnList['pass_order_date_dp'] = "";
				}*/
				$RbnList['pass_order_pin'] = $List4->pass_order_pin;
				$RbnList['pass_order_pin_amt'] = $List4->pass_order_pin_amt;
				$RbnList['rab_flag'] = $List4->rab_flag;
				$RbnList['rab_status'] = $List4->rab_status;
				$RbnList['staffid'] = $List4->staffid;
				$BillValue = $List4->slm_total_amount + $List4->slm_total_amount_esc + $List4->secured_adv_amt;
				$RbnList['this_bill_value'] = $BillValue;
				
				$RbnList['is_adv_pay'] = $List4->is_adv_pay;
				$RbnList['adv_perc'] = $List4->adv_perc;
				$RbnList['adv_amt'] = $List4->adv_amt;
				if($List4->is_adv_pay == "Y"){
					$SelectQuery4A = "select * from abstractbook_dt where sheetid = '$SheetId' and rbn = '$LastRbn'";
					$SelectSql4A = mysqli_query($dbConn,$SelectQuery4A);
					if($SelectSql4A == true){
						if(mysqli_num_rows($SelectSql4A)>0){
							$List4A = mysqli_fetch_object($SelectSql4A);
							if($PageType == "PSO"){
								if(($List4A->pass_order_dt == '')||($List4A->pass_order_dt == '0000-00-00')||($List4A->pass_order_dt == NULL)){
									$IsAdvPayFlag = "Y";
								}
							}
							if($PageType == "PYO"){
								if(($List4A->pay_order_dt == '')||($List4A->pay_order_dt == '0000-00-00')||($List4A->pay_order_dt == NULL)){
									$IsAdvPayFlag = "Y";
								}
							}
						}
					}
				}
				//echo $PageType;exit;
				$BillCompStatus = $List4->rab_status;
			}
		}
		if($IsAdvPayFlag == "Y"){
			$WhereClause = " and is_adv_pay = 'Y'";
		}else{
			$WhereClause = "";
		}
		//echo $IsAdvPayFlag;exit;
		$RbnList['is_adv_pay_flag'] = $IsAdvPayFlag;
		$IsMop = ""; $IsPassOrd = ""; $IsPayOrd = ""; $IsPaid = "";
		$SelectQuery5 = "select * from memo_payment_accounts_edit where sheetid = '$SheetId' and rbn = '$LastRbn'".$WhereClause;//(select max(rbn) from memo_payment_accounts_edit where sheetid = '$SheetId')";
		$SelectSql5 = mysqli_query($dbConn,$SelectQuery5);
		if($SelectSql5 == true){
			if(mysqli_num_rows($SelectSql5)>0){
				$List5 = mysqli_fetch_object($SelectSql5);
				$IsMop = "Y";
				if(($IsAdvPayFlag == "Y")&&($PayFlag == "PPAY")){ 
					$RbnList['pass_order_amt'] = $List5->adv_amt;
					$RbnList['pay_order_amt'] = $List5->adv_amt;
				}else{ 
					//$RbnList['pass_order_amt'] = $List5->abstract_net_amt + $List5->sec_adv_amt + $List5->esc_amt + $List5->pl_mac_adv_amt + $List5->mob_adv_amt;
					$RbnList['pass_order_amt'] = $List5->cmb_uptodt_amt + $List5->sec_adv_amt + $List5->esc_amt + $List5->pl_mac_adv_amt + $List5->mob_adv_amt - $List5->cmb_ded_prev_amt;
					$RbnList['pay_order_amt'] = $List5->net_payable_amt;
				}
				
				$RbnList['pass_order_dt'] = $List5->pass_order_dt;
				$RbnList['pay_order_dt'] = $List5->pay_order_dt;
				$RbnList['payment_dt'] = $List5->payment_dt;
				if(($List5->pass_order_dt != NULL)&&($List5->pass_order_dt != "0000-00-00")){
					$RbnList['pass_order_dt_dp'] = dt_display($List5->pass_order_dt);
					$BillPassOrdStatus = "Y";
				}else{
					$RbnList['pass_order_dt_dp'] = "";
				}
				if(($List5->pay_order_dt != NULL)&&($List5->pay_order_dt != "0000-00-00")){
					$RbnList['pay_order_dt_dp'] = dt_display($List5->pay_order_dt);
					$BillPayOrdStatus = "Y";
				}else{
					$RbnList['pay_order_dt_dp'] = "";
				}
				if(($List5->payment_dt != NULL)&&($List5->payment_dt != "0000-00-00")){
					$RbnList['payment_dt_dp'] = dt_display($List5->payment_dt);
					$BillVouchStatus = "Y";
				}else{
					$RbnList['payment_dt_dp'] = "";
				}
				$RbnList['bill_mode'] = $List5->bill_mode;;
			}
		}
		$RbnList['is_mop'] 			= $IsMop;
		$RbnList['is_pass_order'] 	= $IsPassOrd;
		$RbnList['is_pay_order'] 	= $IsPayOrd;
		
		//echo $List5->pay_order_dt;exit;
		
		$BillLevelFlag = "";
		$SelectQuery6A = "select * from al_as where sheetid = '$SheetId' AND rbn = '$LastRbn'";
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
		$BillRegStatus = "";
		$SelectQuery6B = "select * from bill_register where sheetid = '$SheetId' AND rbn = '$LastRbn'";
		$SelectSql6B   = mysqli_query($dbConn,$SelectQuery6B);
		if($SelectSql6B == true){
			if(mysqli_num_rows($SelectSql6B)>0){
				$List6B = mysqli_fetch_object($SelectSql6B);
				$BillRegStatus = $List6B->acc_status;
			}
		}
		
		
		
		if($BillRegStatus == ""){
			$RbnList['bill_reg_status'] = "N";
		}else if($BillRegStatus == "C"){
			$RbnList['bill_reg_status'] = "Y";
		}else if($BillRegStatus == "P"){
			$RbnList['bill_reg_status'] = "Y";
		}else{
			$RbnList['bill_reg_status'] = "";
		}
	
		$RbnList['bill_curr_level'] 	= $BillCurrLevel;
		$RbnList['bill_ret_status'] 	= $BillRetStatus;
		$RbnList['bill_comp_status'] 	= $BillCompStatus;
		$RbnList['bill_vouch_status'] 	= $BillVouchStatus;
		$RbnList['bill_payord_status'] 	= $BillPayOrdStatus;
		$RbnList['bill_pasord_status'] 	= $BillPassOrdStatus;
		$RbnList['curr_session_level'] 	= $_SESSION['levelid'];
		$RbnList['bill_level_flag'] 	= $BillLevelFlag;
		
		
		if(count($HoaArr)>0){
			$HoaStr = implode(", ",$HoaArr);
			//$RbnList['hoa_no'] = $HoaStr;
			$RbnList['hoa'] = $HoaStr;
		}
		//echo $HoaStr;exit;
		$RbnArr[]  = $RbnList;
	}
}
echo json_encode($RbnArr);
	
?>
