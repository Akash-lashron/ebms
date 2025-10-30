<?php
@ob_start();
require_once '../../library/config.php';
$rem = $_POST['AcctName'];
$id	 =	$_POST[id];
$Status = 0;
$SelectQuery	= "select * from schdule where sch_id = '$id'";
$SelectSql		= mysql_query($SelectQuery);
  if($SelectSql == true){
	 if(mysql_num_rows($SelectSql)>0){
	 	$Status = 1; 
	 }
  }
if($Status == 1){
	$InsertQuery 	= "update schdule set vari_stmt_rem = '$rem' where sch_id='$id' and active='1'";
	$InsertSql 		= mysql_query($InsertQuery);
	if($InsertSql == true){
		$Status 	= 2;
	}
}
echo json_encode($InsertQuery);
?>