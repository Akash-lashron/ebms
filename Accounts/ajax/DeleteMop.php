<?php
@ob_start();
require_once '../library/config.php';
//$output = ''
$Page	= $_POST['Page'];
$MopId	= $_POST['MopId'];
$OutputArr = array();
$IsVrMade = 0;
//if($Page == "SDR"){
	$SelectQuery = "SELECT vr_dt FROM memo_payment_accounts_edit WHERE memoid = '$MopId'";
	$SelectSql = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$MemoList = mysqli_fetch_object($SelectSql);
			$VrDate = $MemoList->vr_dt;
			if(($VrDate != NULL)&&($VrDate != '')&&($VrDate != "0000-00-00")){ 
				$IsVrMade = 1;
			}
		}
	}
	if($IsVrMade == 0){
		$DeleteQuery = "DELETE FROM memo_payment_accounts_edit WHERE memoid = '$MopId'";
		$DeleteSql = mysqli_query($dbConn,$DeleteQuery);
		if($DeleteSql == true){
			$Msg = "Data deleted successfully";
		}else{
			$Msg = "Sorry, unable to delete. Please try again.";
		}
	}else{
		$Msg = "Sorry, unable to delete. Voucher already created.";
	}
	$OutputArr['msg'] = $Msg;
//}

echo json_encode($OutputArr);
//echo $select_query;
?> 