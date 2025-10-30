<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$Id 		= $_POST['Id'];
$NitRel 	= $_POST['NitRel'];
$PreBidMeet = $_POST['PreBidMeet'];
$Part1Recom = $_POST['Part1Recom'];
$Part2Recom = $_POST['Part2Recom'];
$WoRel 	 	= $_POST['WoRel'];
$OutputArr = array(); 

$UpdateQuery1  	= "UPDATE works SET is_nit_rel = '$NitRel', is_prebid_meet = '$PreBidMeet', is_part1_recom = '$Part1Recom', is_part2_recom = '$Part2Recom', is_wo_rel = '$WoRel' WHERE globid = '$Id'";
$UpdateSql1 	= mysqli_query($dbConn,$UpdateQuery1);
if($UpdateSql1 == true){
	$Msg = "Work Progressiva Status Updated Successfully";
}else{
	$Msg = "Work Progressiva Status Not Updated. Please Try Again.";
}
echo $Msg;
?>
