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
$OutputArr = array(); 
if(($ContId != "")&&($ContId != NULL)){
	$SheetArr = array(); $SheetId = ""; $Rbn = ""; $WorkArr = array(); $HoaArr = array(); $Hoa = "";
	$SelectQuery2A = "select * from gst_rate_master";
	$SelectSql2A   = mysqli_query($dbConn,$SelectQuery2A);
	if($SelectSql2A == true){
		if(mysqli_num_rows($SelectSql2A)>0){
			while($List2A = mysqli_fetch_object($SelectSql2A)){
				$ContArr[$List2A->gst_desc] = $List2A->gst_rate;
			}
		}
	}
	
	
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
		$ContArr = $List5;
	}
	
	$BankDtArr = array(); $x = 0;
	$SelectQuery6 = "select * from contractor_bank_detail where contid = '$ContId' and (bk_dt_conf_by = 'AAO' OR bk_dt_conf_status ='AAO')";
	$SelectSql6   = mysqli_query($dbConn,$SelectQuery6);
	if($SelectSql6 == true){
		while($List6 = mysqli_fetch_assoc($SelectSql6)){
			if($x == 0){
				$List6['active_status'] = 1;
			}else{
				$List6['active_status'] = 0;
			}
			$BankDtArr[] = $List6; $x++;
		}
	}
	$OutputArr['CONTData'] = $ContArr;
	$OutputArr['BKData'] = $BankDtArr;
}
echo json_encode($OutputArr);
	
?>
