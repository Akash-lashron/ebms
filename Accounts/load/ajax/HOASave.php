<?php
@ob_start();
require_once '../../library/config.php';
$hoa = $_POST['txt_head_of_acc_name'];
$LastId = '';
$Status = 0;
$SelectQuery	= "select * from hoa where hoa = '$hoa'";
$SelectSql		= mysql_query($SelectQuery);
if($SelectSql == true){
	if(mysql_num_rows($SelectSql)>0){
		$Status = 1; 
	}
}
if($Status == 0){
	$InsertQuery 	= "insert into hoa set hoa = '$hoa', active = 1, createddate = NOW(), userid = ".$_SESSION['sid'];
	$InsertSql 		= mysql_query($InsertQuery);
	$LastId = mysql_insert_id();
	if($InsertSql == true){
		$Status 	= 2;
	}
}
//echo $InsertQuery;exit;
echo $Status."@*@".$LastId;;
?>