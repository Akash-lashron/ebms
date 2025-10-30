<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$Id 		= $_POST['id'];
$ParId 		= $_POST['parid'];
$AlreadyCreated = 0; $Type = "";
$SelectGroupQuery = "select * from datasheet_master_hc where id = '$Id' and par_id = '$ParId'";
$SelectGroupSql = mysqli_query($dbConn,$SelectGroupQuery);
if($SelectGroupSql == true){
	if(mysqli_num_rows($SelectGroupSql)>0){
		$AlreadyCreated = 1;
		$List = mysqli_fetch_object($SelectGroupSql);
		$Type = $List->type;
	}
}
$Result = array('AlreadyCreated'=>$AlreadyCreated,'Type'=>$Type);
echo json_encode($Result);
?>