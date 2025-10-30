<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";

$TenNo      = $_POST['TenNo'];
$OutputArr  = array();
$SelectQuery1 = "select tr_est from tender_register where tr_id = '$TenNo' ";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		while($List = mysqli_fetch_array($SelectSql1)){
		$TSAMt         = $List['tr_est'];

	    $OutputArr['tr_est'] = $TSAMt;
     }
  }
}
echo json_encode($OutputArr);
?>