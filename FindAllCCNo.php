<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
//include "common.php";
$Staffid 	 = $_SESSION['sid'];
$WorkArr = array(); 
if($_SESSION['isadmin'] == 1){
	$SelectQuery1 = "SELECT sheet_id, computer_code_no FROM sheet WHERE (active = 1 OR active = 2) ORDER BY CAST(computer_code_no AS SIGNED) ASC";
}else if($_SESSION['staff_section'] == 2){
	$SelectQuery1 = "SELECT sheet_id, computer_code_no FROM sheet WHERE (active = 1 OR active = 2) ORDER BY CAST(computer_code_no AS SIGNED) ASC";
}else{
	$SelectQuery1 = "SELECT sheet_id, computer_code_no FROM sheet WHERE (active = 1 OR active = 2) AND FIND_IN_SET($Staffid,assigned_staff) ORDER BY CAST(computer_code_no AS SIGNED) ASC";
}
$SelectSql1 = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		while($List = mysql_fetch_object($SelectSql1)){
			$WorkArr[] = $List;
		}
	}
}
echo json_encode($WorkArr);
?>
