<?php
require_once '../library/config.php';
$RbnArr = array();
$SelectQuery = "select distinct rbn from abstractbook where sheetid = '".$_POST['WorkId']."'";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	while($RbnList = mysqli_fetch_object($SelectSql)){
		$RbnArr[]  = $RbnList->rbn;
	}
}
echo json_encode($RbnArr);
	
?>
