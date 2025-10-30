<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$newICode = $_POST['newICode'];
$ItemId = $_POST['ItemId'];
$ItemCount = 0;
if($ItemId == ""){
	$SelectItemQuery = "select item_code from item_master where item_code = '$newICode' and active = 1";
}else{
	$SelectItemQuery = "select item_code from item_master where item_code = '$newICode' and item_id != '$ItemId' and active = 1";
}
$SelectItemSql = mysqli_query($dbConn,$SelectItemQuery);
if($SelectItemSql == true){
	$ItemCount = mysqli_num_rows($SelectItemSql);
}
echo $ItemCount;
?>