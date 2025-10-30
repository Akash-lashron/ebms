<?php
@ob_start();
require_once '../library/config.php';
//$output = ''
$ApprPwd	=	$_POST['ApprPwd'];
$Page		=	$_POST['Page'];
$IsValid = 0;
$SelectQuery = "SELECT * FROM pwd_mod WHERE pwd = '$ApprPwd' AND pwd_mod = '$Page' AND pwd_level = '".$_SESSION['levelid']."' AND pwd_sec = '".$_SESSION['staff_section']."'";
$SelectSql 	 = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$IsValid = 1;
	}
}
//$OutputArr = array('isvalid'=>$IsValid);
echo $IsValid;
//echo $select_query;
?> 