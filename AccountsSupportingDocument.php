<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
CheckUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
$success = 0; $msg = "";
//$_SESSION['staff_section']
if(isset($_POST["next"]) == " Next "){ 
	$Sheetid = $_POST['txt_workshortname'];
	$_SESSION['UpSheetid'] = $Sheetid;
	$_SESSION['UpAction'] = "SAVE";
}
if(isset($_POST["view"]) == " View "){ 
	$Sheetid = $_POST['txt_workshortname'];
	$_SESSION['UpSheetid'] = $Sheetid;
	$_SESSION['UpAction'] = "VIEW";
}
if($_SESSION['UpAction'] == "SAVE"){
	include("AccountsSupportingDocumentSend.php");
}
if($_SESSION['UpAction'] == "VIEW"){
	include("AccountsSupportingDocumentView.php");
}
?>