<?php
@ob_start();
require_once '../../library/config.php';
$pin_no = $_POST['txt_Pin_no'];
$LastId = '';
$Status = 0;
$SelectQuery	= "select * from pin_entry where pin_no = '$pin_no'";
$SelectSql		= mysql_query($SelectQuery);
if($SelectSql == true){
	if(mysql_num_rows($SelectSql)>0){
		$Status = 1; 
	}
}
if($Status == 0){
	$InsertQuery 	= "insert into pin_entry set pin_no = '$pin_no',pin_value ='', upto_automat_value ='', project_title='', active = 1, createddate = NOW(), userid = ".$_SESSION['sid'];
	$InsertSql 		= mysql_query($InsertQuery);
	$LastId = mysql_insert_id();
	if($InsertSql == true){
		$Status 	= 2;
	}
}
//echo $InsertQuery;exit;
echo $Status."@*@".$LastId;
?>