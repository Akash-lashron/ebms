<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsBeExist = 0;
$WorkId  = $_POST['WorkId'];
$FinYear = $_POST['FinYear'];
$List = array();
$StateMastQuery = "SELECT * FROM budget_expenditure WHERE globid = '$WorkId' AND fin_year = '$FinYear'";
$StateMastQuery1 = mysqli_query($dbConn, $StateMastQuery);
if($StateMastQuery1 == true){
	if(mysqli_num_rows($StateMastQuery1)>0){
		$List = mysqli_fetch_assoc($StateMastQuery1);
	}
}
echo json_encode($List);
?>