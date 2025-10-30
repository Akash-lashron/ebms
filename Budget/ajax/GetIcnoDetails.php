<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsICExist = 0;
$StNameFromDB = null;
$OutputArray = array();
$ICNumber 	= trim($_POST['ICNOval']);

$ICNOMastQuery = "SELECT staffcode,staffname FROM staff WHERE staffcode = '$ICNumber' AND active = 1";
$ICNOMastQuery1 = mysqli_query($dbConn, $ICNOMastQuery);

if($ICNOMastQuery1 == true){
	if(mysqli_num_rows($ICNOMastQuery1)>0){
		$List = mysqli_fetch_assoc($ICNOMastQuery1);
		$ICNumFromDB 	= $List['staffcode'];
		$StNameFromDB 	= $List['staffname'];
		if($ICNumFromDB == $ICNumber){
			$IsICExist = 1;
		}else{
			$IsICExist = 0;
		}
	}
}
$OutputArray['IsICExist'] = $IsICExist;
$OutputArray['StaffName'] = $StNameFromDB;

echo json_encode($OutputArray);
?>