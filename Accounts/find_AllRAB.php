<?php
require_once 'library/config.php';
$sheetid		 	=  $_GET['sheetid'];
$RbnStr = "";
/*$select_sa_query 	= "select distinct rbn from mbookgenerate_staff where sheetid = '$sheetid' order by rbn desc";
$select_sa_sql 	= mysqli_query($dbConn,$select_sa_query);
if($select_sa_sql == true)
{
	if(mysqli_num_rows($select_sa_sql)>0)
	{
		while($SAList = mysqli_fetch_object($select_sa_sql))
		{
			$rbn 	 = $SAList->rbn;
			$RbnStr .= $rbn."*";
		}
		$RbnStr = rtrim($RbnStr,"*");
	}
}
echo $RbnStr;*/
$RbnArr = array();
$WorkId =  $_POST['WorkId'];
$SelectQuery = "select rbn from abstractbook where sheetid  = '$WorkId'";
$SelectSql = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_object($SelectSql)){
			$RbnArr[] = $List;
		}
	}
}
echo json_encode($RbnArr);
?>