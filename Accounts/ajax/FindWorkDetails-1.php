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
$SelectQuery = "select * from sheet where sheet_id = '".$_POST['WorkId']."'";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	while($RbnList = mysqli_fetch_assoc($SelectSql)){
		$HoaArr = array();
		$GlobId = $RbnList['globid'];
		$HoaId = $RbnList['hoaid'];
		if(($RbnList['hoaid'] != '')&&($RbnList['hoaid'] != NULL)){
			$SelectQuery1 = "select * from hoa where hoa_id IN ($HoaId)";
			$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
			if($SelectSql1 == true){
				if(mysqli_num_rows($SelectSql1)>0){
					while($List1 = mysqli_fetch_object($SelectSql1)){
						$HoaNo = $List1->hoa_no;
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
						$SelectQuery3 = "select * from hoa where hoa_id IN ($List2->hoaid)";
						$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
						if($SelectSql3 == true){
							if(mysqli_num_rows($SelectSql3)>0){
								while($List3 = mysqli_fetch_object($SelectSql3)){
									$HoaNo = $List3->hoa_no;
									array_push($HoaArr,$HoaNo);
								}
							}
						}
					}
				}
			}
		}
		$SelectQuery4 = "select * from abstractbook where sheetid = '$SheetId' and rbn = (select max(rbn) from abstractbook where sheetid = '$SheetId')";
		$SelectSql4 = mysqli_query($dbConn,$SelectQuery4);
		if($SelectSql4 == true){
			if(mysqli_num_rows($SelectSql4)>0){
				$List4 = mysqli_fetch_object($SelectSql4);
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
				$RbnList['pass_order_date'] = dt_display($List4->pass_order_date);
				if(($List4->pass_order_date != NULL)&&($List4->pass_order_date != "0000-00-00")){
					$RbnList['pass_order_date_dp'] = dt_display($List4->pass_order_date);
				}else{
					$RbnList['pass_order_date_dp'] = "";
				}
				$RbnList['pass_order_pin'] = $List4->pass_order_pin;
				$RbnList['pass_order_pin_amt'] = $List4->pass_order_pin_amt;
				$RbnList['rab_flag'] = $List4->rab_flag;
				$RbnList['rab_status'] = $List4->rab_status;
				$RbnList['staffid'] = $List4->staffid;
				$BillValue = $List4->slm_total_amount + $List4->slm_total_amount_esc + $List4->secured_adv_amt;
				$RbnList['this_bill_value'] = $BillValue;
			}
		}
		$SelectQuery5 = "select * from memo_payment_accounts_edit where sheetid = '$SheetId' and rbn = (select max(rbn) from abstractbook where sheetid = '$SheetId')";
		$SelectSql5 = mysqli_query($dbConn,$SelectQuery5);
		if($SelectSql5 == true){
			if(mysqli_num_rows($SelectSql5)>0){
				$List5 = mysqli_fetch_object($SelectSql5);
				$RbnList['pass_order_amt'] = $List5->abstract_net_amt + $List5->sec_adv_amt + $List5->esc_amt + $List5->pl_mac_adv_amt;
				$RbnList['pay_order_amt'] = $List5->net_payable_amt;
			}
		}
		if(count($HoaArr)>0){
			$HoaStr = implode(", ",$HoaArr);
			$RbnList['hoa_no'] = $HoaStr;
			$RbnList['hoa'] = $HoaStr;
		}
		//echo $HoaStr;exit;
		$RbnArr[]  = $RbnList;
	}
}
echo json_encode($RbnArr);
	
?>
