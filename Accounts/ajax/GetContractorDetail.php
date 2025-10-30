<?php
@ob_start();
require_once '../library/config.php';
$CondID		= $_POST['ContID'];
$SelectQuery 	= "select contractor.*, contractor_bank_detail.* from contractor JOIN contractor_bank_detail ON contractor_bank_detail.contid = contractor.contid   where contractor.contid = '$CondID'";
//echo $SelectQuery; exit;
$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
			$rows[] = $List;
		}
	}
}
echo json_encode($rows);
?> 
