<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsICExist = 0;
$StNameFromDB = null;
$OutputArray = array();
$EmpMailId 	= trim($_POST['Empmailval']);

$EmpMailIdQuery = "SELECT email,staffname FROM staff WHERE email = '$EmpMailId' AND active = 1";
$EmpMailIdQuery1 = mysqli_query($dbConn,$EmpMailIdQuery);

if($EmpMailIdQuery1 == true){
	if(mysqli_num_rows($EmpMailIdQuery1)>0){
		$List = mysqli_fetch_assoc($EmpMailIdQuery1);
		$EmpMailIdFromDB 	= $List['email'];
		$StNameFromDB 		= $List['staffname'];
		if($EmpMailIdFromDB == $EmpMailId){
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