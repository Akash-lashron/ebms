<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$OutputArrayTemp = array();
$IsBeExist = 0;
$FinYear 	= $_GET['selyear'];
$FrPage 	= $_GET['page'];

if($FrPage == 'BEHOA'){
	$HoaBePropQuery = "SELECT * FROM hoa_be WHERE fin_year='$FinYear' AND status='P' ";
	$HoaBePropQuery1 = mysqli_query($dbConn, $HoaBePropQuery);

	if($HoaBePropQuery1 == true){
		if(mysqli_num_rows($HoaBePropQuery1)>0){
			while($List = mysqli_fetch_assoc($HoaBePropQuery1)){
				$OutputArrayTemp[] = $List;
				$IsBeExist = 1;
			}
		}
		
	}

	$HoaBeApprovQuery = "SELECT * FROM hoa_be WHERE fin_year='$FinYear' AND status='A' ";
	$HoaBeApprovQuery1 = mysqli_query($dbConn, $HoaBeApprovQuery);

	if($HoaBeApprovQuery1 == true){
		if(mysqli_num_rows($HoaBeApprovQuery1)>0){
			while($List = mysqli_fetch_assoc($HoaBeApprovQuery1)){
				$OutputArrayTemp[] = $List;
				$IsBeExist = 2;
			}
		}
		
	}


	if($IsBeExist == 0){
		$HoaQuery = "SELECT * FROM hoa ORDER BY hoa_id ASC";
		$HoaQuery1 = mysqli_query($dbConn, $HoaQuery);
		if($HoaQuery1 == true){
			if(mysqli_num_rows($HoaQuery1)>0){
				while($List = mysqli_fetch_assoc($HoaQuery1)){
					$OutputArrayTemp[] = $List;
				}
			}
		}
	}
}else{
	$HoaRePropQuery = "SELECT * FROM hoa_re WHERE fin_year='$FinYear' AND status='P' ";
	$HoaRePropQuery1 = mysqli_query($dbConn, $HoaRePropQuery);

	if($HoaRePropQuery1 == true){
		if(mysqli_num_rows($HoaRePropQuery1)>0){
			while($List = mysqli_fetch_assoc($HoaRePropQuery1)){
				$OutputArrayTemp[] = $List;
				$IsBeExist = 1;
			}
		}
		
	}

	$HoaReApprovQuery = "SELECT * FROM hoa_re WHERE fin_year='$FinYear' AND status='A' ";
	$HoaReApprovQuery1 = mysqli_query($dbConn, $HoaReApprovQuery);

	if($HoaReApprovQuery1 == true){
		if(mysqli_num_rows($HoaReApprovQuery1)>0){
			while($List = mysqli_fetch_assoc($HoaReApprovQuery1)){
				$OutputArrayTemp[] = $List;
				$IsBeExist = 2;
			}
		}
		
	}
	//echo $IsBeExist;exit;

	if($IsBeExist == 0){
		$HoaBeQuery = "SELECT * FROM hoa_be WHERE fin_year='$FinYear' AND status='A' ORDER BY hoa_id ASC";
		$HoaBeQuery1 = mysqli_query($dbConn, $HoaBeQuery);
		if($HoaBeQuery1 == true){
			if(mysqli_num_rows($HoaBeQuery1)>0){
				while($List = mysqli_fetch_assoc($HoaBeQuery1)){
					$OutputArrayTemp[] = $List;
				}
			}
		}
	}
	//echo $OutputArrayTemp;exit;
}
$OutputArray = array('TableFlag'=>$IsBeExist,'HoaData'=>$OutputArrayTemp);
echo json_encode($OutputArray);
?>