<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";

$TenNo      = $_POST['TenNo'];
$Bidname      = $_POST['Bidname'];	
$OutputArr  = array();
$OutputArr['status'] = 0;
$SelectQuery1 = "select a.*,b.name_contractor from bidder_bid_details a LEFT JOIN contractor b ON (a.contid = b.contid) WHERE a.tr_id = '$TenNo' AND a.contid = '$Bidname' ";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		$ContList = mysqli_fetch_object($SelectSql1);
		$NameCont =	$ContList->name_contractor;
		$OutputArr['ContName'] = $NameCont;
		$OutputArr['status'] = 1;
   }
}
//print_r( $OutputArr);  exit;
echo json_encode($OutputArr);
?>