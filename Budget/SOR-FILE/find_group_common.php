<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$GroupId 	= $_POST['group_id'];
$Id 		= $_POST['id'];
$ParId 		= $_POST['parid'];

$SelectGroupQuery = "select group_desc from group_datasheet where id = '$Id'";
$SelectGroupSql = mysqli_query($dbConn,$SelectGroupQuery);
if($SelectGroupSql == true){
	if(mysqli_num_rows($SelectGroupSql)>0){
		while($List = mysqli_fetch_array($SelectGroupSql)){
			$Result2[] = $List;
		}
	}
}
$SelectGroupQuery = "select * from group_datasheet where par_id = '$Id'";
$SelectGroupSql = mysqli_query($dbConn,$SelectGroupQuery);
if($SelectGroupSql == true){
	if(mysqli_num_rows($SelectGroupSql)>0){
		while($List = mysqli_fetch_array($SelectGroupSql)){
			$Result1[] = $List;
		}
	}
}
$Result = array('Result1'=>$Result1,'Result2'=>$Result2);
echo json_encode($Result);

/*$SelectGroupQuery = "select * from group_datasheet where par_id = '$Id'";
$SelectGroupSql = mysqli_query($dbConn,$SelectGroupQuery);
if($SelectGroupSql == true){
	if(mysqli_num_rows($SelectGroupSql)>0){
		while($List = mysqli_fetch_array($SelectGroupSql)){
			$Result[] = $List;
		}
	}
}
echo json_encode($Result);*/

?>