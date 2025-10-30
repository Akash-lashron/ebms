<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);

    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$SheetId 	 = $_POST['Id'];
$OutputArr = array();

$GlobID= '';
$SelectTSQuery = "SELECT globid FROM sheet where sheet_id = '$SheetId'";
$SelectTSSql 	= mysqli_query($dbConn,$SelectTSQuery);
if($SelectTSSql == true){
	if(mysqli_num_rows($SelectTSSql)>0){
		$CList = mysqli_fetch_object($SelectTSSql);
		$GlobID = $CList->globid;
  }
}

$SelectQuery2 = "select *  from  tender_register where globid = '$GlobID'";
$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
if($SelectSql2 == true){
	if(mysqli_num_rows($SelectSql2)>0){
		$List = mysqli_fetch_object($SelectSql2);
			$SDper                     =     $List->sd_per;
			$OutputArr[] =        $List;
	}
}

$OutputArr['sd_per'] = $SDper;
echo json_encode($OutputArr);
?>
