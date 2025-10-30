<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";

$newGroup = $_POST['newGroup'];
$GroupId = $_POST['GroupId'];
$GroupCount = 0;
if($GroupId == ""){
	$SelectGroupQuery = "select type from group_datasheet where type = '$newGroup' and delete_In = '' and active = 1";
}else{
	$SelectGroupQuery = "select type from group_datasheet where type = '$newGroup' and id != '$GroupId' and delete_In = '' and active = 1";
}
$SelectGroupSql = mysqli_query($dbConn,$SelectGroupQuery);
if($SelectGroupSql == true){
	$GroupCount = mysqli_num_rows($SelectGroupSql);
}
echo $GroupCount;//$GroupCount;
?>