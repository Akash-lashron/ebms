<?php
@ob_start();
require_once '../library/config.php';
//$output = ''
$ContName	= $_POST['ContName'];
$ContId		= $_POST['ContId'];
$ContFirstCode 	= substr($ContName, 0, 1);
$MaxContCode = 0; $TempCodeStr = $ContFirstCode."-";

$CodeExist = 0;
$SelectCodeQuery = "SELECT cont_code_frfcf FROM contractor WHERE contid = '$ContId'";
$SelectCodeSql 	 = mysqli_query($dbConn,$SelectCodeQuery);
if($SelectCodeSql == true){
	if(mysqli_num_rows($SelectCodeSql)>0){
		$List = mysqli_fetch_object($SelectCodeSql);
		if($List->cont_code_frfcf != ''){
			$ContCode = $List->cont_code_frfcf;
			$CodeExist = 1;
		}
	}
}

if($CodeExist == 0){
	$SelectCodeQuery = "SELECT MAX(REPLACE(cont_code_frfcf,'$TempCodeStr','')) as cont_code FROM contractor WHERE cont_code_frfcf LIKE '".$ContFirstCode."%'";
	$SelectCodeSql 	 = mysqli_query($dbConn,$SelectCodeQuery);
	if($SelectCodeSql == true){
		if(mysqli_num_rows($SelectCodeSql)>0){
			$List = mysqli_fetch_object($SelectCodeSql);
			$MaxContCode = $List->cont_code;
		}
	}
	
	$MaxContCode = $MaxContCode+1;
	if(strlen($MaxContCode) == 1){
		$MaxContCode = "0".$MaxContCode;
	}
	$ContCode = $ContFirstCode."-".$MaxContCode;
}
echo $ContCode;
?> 