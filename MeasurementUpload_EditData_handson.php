<?php
//session_start();
@ob_start();
require_once 'library/config.php';
$userid 			= 	$_SESSION['userid'];
$editvalue 			= 	$_POST['editvalue'];
//echo $editvalue[2];
	$mdate 			= $editvalue[0];
	$itemno 		= $editvalue[1];
	$idescrip 		= $editvalue[2];
	$inumber 		= $editvalue[3];
	$ilength 		= $editvalue[4];
	$ibreadth 		= $editvalue[5];
	$idepth 		= $editvalue[6];
	$iarea 			= $editvalue[7];
	$iremarks 		= $editvalue[8];
?>
