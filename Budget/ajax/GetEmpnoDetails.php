<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsICExist = 0;
$StNameFromDB = null;
$OutputArray = array();
$EmpNumber 	= trim($_POST['EmpNOval']);

$EmpNOMastQuery = "SELECT staff_emp_no,staffname FROM staff WHERE staff_emp_no = '$EmpNumber' AND active = 1";
$EmpNOMastQuery1 = mysqli_query($dbConn,$EmpNOMastQuery);

if($EmpNOMastQuery1 == true){
	if(mysqli_num_rows($EmpNOMastQuery1)>0){
		$List = mysqli_fetch_assoc($EmpNOMastQuery1);
		$EmpNumFromDB 	= $List['staff_emp_no'];
		$StNameFromDB 	= $List['staffname'];
		if($EmpNumFromDB == $EmpNumber){
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