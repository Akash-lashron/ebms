<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$IsBeExist = 0;
$FrPage 	= $_POST['page'];
$Output = 0;
if($FrPage == 'GSTTYPE'){
	$StateId 	= $_POST['stateval'];
	$StateMastQuery = "SELECT state_code FROM state_master WHERE state_id = '$StateId'";
	$StateMastQuery1 = mysqli_query($dbConn, $StateMastQuery);

	if($StateMastQuery1 == true){
		if(mysqli_num_rows($StateMastQuery1)>0){
			$List = mysqli_fetch_assoc($StateMastQuery1);
				$StName = $List['state_code'];
			if($StName == 'TN'){
				$Output = 1;
			}else{
				$Output = 0;
			}
		}
	}
}
if($FrPage == 'CCNUMVERIFY'){
	$CCNO 	= $_POST['ccnoval'];
	$CCNOMastQuery = "SELECT ccno FROM works WHERE ccno = '$CCNO'";
	$CCNOMastQuery1 = mysqli_query($dbConn, $CCNOMastQuery);

	if($CCNOMastQuery1 == true){
		if(mysqli_num_rows($CCNOMastQuery1)>0){
			$List = mysqli_fetch_assoc($CCNOMastQuery1);
			$CCNumFromDB = $List['ccno'];
			if($CCNumFromDB == $CCNO){
				$Output = 1;
			}else{
				$Output = 0;
			}
		}
	}
}
echo json_encode($Output);
?>