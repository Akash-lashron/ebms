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
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
$OutPutArr 	= array();
$FromDate 	= dt_format($_POST['FromDate']);
$ToDate 	= dt_format($_POST['ToDate']);
$Code 		= $_POST['Code'];
$Type 		= $_POST['Type'];
if($Code == "LCESS"){
	if($Type == "SUM"){
		$LCessAmt = 0;
		$SelectQuery1 = "select SUM(lw_cess_amt) as lcess_amt from memo_payment_accounts_edit where payment_dt >= '$FromDate' and payment_dt <= '$ToDate'";
		$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				$List1 = mysqli_fetch_object($SelectSql1);
				if(($List1->lcess_amt != '')&&($List1->lcess_amt != NULL)){
					$OutPutArr['lcess_amt'] = round($List1->lcess_amt,2);
				}
			}
		}
	}
	if($Type == "ALL"){
		$LCessAmt = 0;
		$SelectQuery1 = "select a.*, b.*, c.* from memo_payment_accounts_edit a inner join sheet b on (a.sheetid = b.sheet_id) inner join contractor c on (b.contid = c.contid) where a.payment_dt >= '$FromDate' and a.payment_dt <= '$ToDate' order by payment_dt asc";
		$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
		//echo $SelectQuery1;exit;
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				while($List1 = mysqli_fetch_assoc($SelectSql1)){
					$OutPutArr[] = $List1;
				}
			}
		}
	}
}

if($Code == "SD"){
	$SheetId = $_POST['WorkId'];
	if($Type == "SUM"){
		$LCessAmt = 0;
		$SelectQuery1 = "select SUM(sd_amt) as sd_amt from memo_payment_accounts_edit where sheetid = '$SheetId'";
		$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				$List1 = mysqli_fetch_object($SelectSql1);
				if(($List1->sd_amt != '')&&($List1->sd_amt != NULL)){
					$OutPutArr['sd_amt'] = round($List1->sd_amt,2);
				}
			}
		}
	}
	//echo $SheetId;exit;
	if($Type == "ALL"){
		$LCessAmt = 0;
		$SelectQuery1 = "select a.*, b.*, c.*, c.name_contractor as cont_name, a.rbn as bill_rbn from memo_payment_accounts_edit a inner join sheet b on (a.sheetid = b.sheet_id) inner join contractor c on (b.contid = c.contid) where a.sheetid = '$SheetId' and b.sheet_id = '$SheetId' order by payment_dt asc";
		$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
		//echo $SelectQuery1;exit;
		if($SelectSql1 == true){
			if(mysqli_num_rows($SelectSql1)>0){
				while($List1 = mysqli_fetch_assoc($SelectSql1)){
					$List1['bill_value'] = $List1['abstract_net_amt']+$List1['sec_adv_amt']+$List1['esc_amt']+$List1['pl_mac_adv_amt']+$List1['mob_adv_amt'];
					$List1['payment_dt'] = dt_display($List1['payment_dt']);
					$OutPutArr[] = $List1;
				}
			}
		}
	}
}
echo json_encode($OutPutArr);
?>
