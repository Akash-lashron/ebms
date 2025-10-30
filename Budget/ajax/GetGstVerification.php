<?php
@ob_start();
require_once '../library/config.php';
//$output = ''
$ContGst	=	$_POST['contgstval'];

$select_query = "SELECT name_contractor FROM contractor WHERE gst_no = '$ContGst'";

$select_sql = mysqli_query($dbConn,$select_query);
if($select_sql == true){
	while ($row = $select_sql->fetch_assoc()) {
		$output = $row['name_contractor'];
	}
}else{
	$output = null;
}
echo json_encode($output);
//echo $select_query;
?> 