<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$itemCode 	= $_POST['itemCode'];
$Output 	= CalculateTSandIGCARRateSubData($itemCode,$conn);
$ExpOutput 	= explode("@**@",$Output);
$TSRate 	= $ExpOutput[0];
$IGCARRate 	= $ExpOutput[1];
$WoutCalcRate 	= $ExpOutput[2];
$ItemUnit 	= $ExpOutput[3];
$Result 	= array('TSRate'=>$TSRate,'IGCARRate'=>$IGCARRate,'MasterDesc'=>$MasterDesc,'WoutCalcRate'=>$WoutCalcRate,'ItemUnit'=>$ItemUnit);
//echo $Output;
echo json_encode($Result);
?>