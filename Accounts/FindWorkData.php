<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
$WorkId       = $_POST['WorkId'];
$SelectQuery1 = "select * from sheet where sheet_id = '$WorkId'";
$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
if($SelectSql1 == true){
	if(mysqli_num_rows($SelectSql1)>0){
		$List1 = mysqli_fetch_array($SelectSql1);
		$ContId = $List1['contid'];
		if($ContId != 0){
			$SelectQuery2 = "select * from contractor where contid = '$ContId'";
			$SelectSql2 = mysqli_query($dbConn,$SelectQuery2);
			if($SelectSql2 == true){
				if(mysqli_num_rows($SelectSql2)>0){
					$List2 = mysqli_fetch_object($SelectSql2);
					$ContName = $List2->name_contractor;
					$List1['name_contractor'] = $ContName;
				}
			}
		}
		$Rbn = "";
		$SelectQuery3 = "select distinct a.rbn from mbookgenerate a inner join abstractbook b on (a.rbn = b.rbn AND a.sheetid = b.sheetid) where a.sheetid = '$WorkId'";
		$SelectSql3 = mysqli_query($dbConn,$SelectQuery3);
		if($SelectSql3 == true){
			if(mysqli_num_rows($SelectSql3)>0){
				$List3 = mysqli_fetch_object($SelectSql3);
				$Rbn = $List3->rbn;
			}
		}
		$List1['rbn'] = $Rbn;
	}
}
echo $SelectQuery3;exit;
echo json_encode($List1);
?>