<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";

$TenNo      = $_POST['TenNo'];
$Bidname      = $_POST['Bidname'];	
$OutputArr  = array();
$OutputArr['status'] = 0;
$SelectQuery1 = "select * from bidder_bid_details where tr_id = '$TenNo' AND contid = '$Bidname' ";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
	    $OutputArr['status'] = 1;
   }
}
//print_r( $OutputArr);  exit;
echo json_encode($OutputArr);
?>