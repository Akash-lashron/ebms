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
$ContId = $_POST['ContId'];
if(($ContId != '')&&($ContId != NULL)){
	$BankDtArr = array();
	$SelectQuery6 = "select * from contractor_bank_detail where contid = '$ContId' and (bk_dt_conf_by = 'AAO' or bk_dt_conf_status = 'AAO')";
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
}
echo json_encode($BankDtArr);
	
?>
