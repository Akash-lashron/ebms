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
$RecType  = $_POST['RecType'];
$SheetId  = $_POST['SheetId'];
if($RecType == "SD"){
	$SelectQuery1 = "select * from bg_fdr_details where master_id = '$SheetId'";
	$SelectSql1   = mysqli_query($dbConn,$SelectQuery1);
	if($SelectSql1 == true){
		if(mysqli_num_rows($SelectSql1)>0){
			while($List1 = mysqli_fetch_assoc($SelectSql1)){
				$OutPutArr[] = $List1;
			}
		}
	}
}
echo json_encode($OutPutArr);
?>
