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
$SheetId = $_POST['SheetId'];
$BankDtArr = array();
if(($SheetId != '')&&($SheetId != NULL)){
	$SelectQuery6 = "select * from work_orders_ext where sheetid = '$SheetId' order by ext_id asc";
	$SelectSql6   = mysqli_query($dbConn,$SelectQuery6);
	if($SelectSql6 == true){
		while($List6 = mysqli_fetch_assoc($SelectSql6)){
			$List6['work_orders_ext'] = dt_display($List6['work_orders_ext']);
			$BankDtArr[] = $List6;
		}
	}
}
echo json_encode($BankDtArr);
	
?>
