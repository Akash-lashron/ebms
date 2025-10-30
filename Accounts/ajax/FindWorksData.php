<?php
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/declaration.php';

$OutputArrayTemp = array();
$IsBeExist  = 0;
$SheetId 	= $_POST['Work'];
$Rbn 		= $_POST['Rab'];
$Ccno 		= $_POST['Ccno'];
$Type		= $_POST['Type'];
//$BillSerialNo = 1000;
$OutputArray = array();
if($Type == "A"){
	$SelectQuery = "SELECT a.*, b.work_name, b.short_name, b.computer_code_no, b.sheet_id FROM bill_register a INNER JOIN sheet b ON (a.sheetid = b.sheet_id) WHERE a.sheetid = '$SheetId' AND a.rbn = '$Rbn' AND b.computer_code_no = '$Ccno'";
}
$BrNo = 0;
if($Type == "M"){
	$SelectQuery = "SELECT * FROM sheet WHERE computer_code_no = '$Ccno' AND under_civil_sheetid = '0'";
	$SelectBrQuery = "SELECT MAX(br_no) as br_no FROM bill_register";
	$SelectBrSql   = mysqli_query($dbConn,$SelectBrQuery);
	if($SelectBrSql == true){
		if(mysqli_num_rows($SelectBrSql)>0){
			$BRList = mysqli_fetch_object($SelectBrSql);
			$BrNo = $BRList->br_no;
		}
	}
}
$BrNo++;
$SelectSql   = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$List = mysqli_fetch_assoc($SelectSql);
		if(($List['sent_on'] != "0000-00-00 00:00:00")&&($List['sent_on'] != "")){
			$SentOn 			= date('d/m/Y', strtotime($List['sent_on']));
			$List['sent_on'] 	= $SentOn;
		}else{
			$List['sent_on'] 	= '';
		}
		//$List['bill_serial_no'] = $BillSerialNo;
		if($Type == "M"){
			$List['rbn'] 	= '';
			$List['br_no'] 	= $BrNo;
		}
		$MBList = ""; $MBookArr = array();
		$SelectQueryA = "select DISTINCT mbno from mymbook where sheetid = '$SheetId' AND rbn = '$Rbn' ORDER BY mbno ASC";
		$SelectSqlA   = mysqli_query($dbConn,$SelectQueryA);
		if($SelectSqlA == true){
			if(mysqli_num_rows($SelectSqlA)>0){
				while($ListA = mysqli_fetch_object($SelectSqlA)){
					$MBookNo = $ListA->mbno;
					array_push($MBookArr,$MBookNo);
				}
			}
		}
		//print_r($MBookArr);exit;
		if(count($MBookArr)>0){
			$MBList = implode(",",$MBookArr);
		}
		$List['mb_received'] = $MBList;
		$OutputArray[] 			= $List;
		//$BillSerialNo++;
	}
}
//print_r($OutputArray);exit;
echo json_encode($OutputArray);
?>