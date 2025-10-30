<?php
require_once 'library/config.php';
$SchId 	= $_POST['id'];
$ItemId = $_POST['sid'];
$SelectQuery1 	= "select * from subdivision where subdiv_id = '$ItemId'";
$SelectSql1 	= mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		$List = mysql_fetch_object($SelectSql1);
		$DivId = $List->div_id;
	}
}
$DeleteQuery1 = "delete from subdivision where subdiv_id = '$ItemId'";
$DeleteSql1   = mysql_query($DeleteQuery1);
$DeleteQuery2 = "delete from schdule where sch_id = '$SchId'";
$DeleteSql2   = mysql_query($DeleteQuery2);

$RowExist = 0;
$SelectQuery2 	= "select * from subdivision where subdiv_id = '$ItemId'";
$SelectSql2 	= mysql_query($SelectQuery2);
if($SelectSql2 == true){
	if(mysql_num_rows($SelectSql2)>0){
		$RowExist = 1;
	}
}
if($RowExist  == 0){
	$DeleteQuery3 = "delete from division where div_id = '$DivId'";
	$DeleteSql3   = mysql_query($DeleteQuery3);
}
if(($DeleteSql1 == true)&&($DeleteSql2 == true)){
	echo 1;
}else{
	echo 0;
}
//echo $DeleteQuery2;
?>
