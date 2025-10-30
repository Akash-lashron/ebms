<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$EstTsTrId 	= $_POST['Id'];
$OutputArr = array(); 
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$TSID = '';
	$SelectTSQuery = "SELECT ts_id FROM tender_register WHERE tr_id = '$EstTsTrId'";
	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			$CList = mysqli_fetch_object($SelectTSSql);
			$TSID =$CList->ts_id;
		}
	}
	$TSdate= '';
	$SelectTSQuery = "SELECT * FROM technical_sanction WHERE ts_id = '$TSID'";


	$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
	if($SelectTSSql == true){
		if(mysqli_num_rows($SelectTSSql)>0){
			while($List = mysqli_fetch_array($SelectTSSql)){
		    $TSdate         = $List['ts_date'];
			$TSdate1        =dt_display($TSdate );
		

			$OutputArr['ts_date'] = $TSdate1;
		}
	}
	}
echo json_encode($OutputArr);
?>
