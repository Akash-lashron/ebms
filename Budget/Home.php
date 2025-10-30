<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName 	= $PTPart1.$PTIcon.'Home';
$msg 		= ""; $del = 0;
$RowCount 	= 0;
$staffid 	= $_SESSION['sid'];
$UnconfirmSor = 0; $UnconfirmRate = 0;
if(in_array("BUD", $_SESSION['ModuleAccArr'])){
	include "HomeBudget.php";
}else{
	include "HomeUser.php";
}
?>
